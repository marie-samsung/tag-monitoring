<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\Constants;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during normal execution.
     * However, when an abnormal state is encountered, it should return
     * an instance of ResponseInterface. If it does, script execution
     * will end and that Response will be sent back to the client,
     * allowing for error pages, redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return ResponseInterface|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        $userId = session()->get('user_id');
        $accountType = session()->get('account_type');

        if ($userId) {
            $db = \Config\Database::connect();

            // Auto-heal database: run migration check
            if (!$db->fieldExists('role_id', 'users') || !$db->fieldExists('profile_photo', 'users') || !$db->tableExists('role_permissions') || !$db->tableExists('roles')) {
                self::autoMigrate($db);
            }

            // Fetch current user row to check is_active and role_id
            $userRow = $db->table('users')
                          ->select('role_id, account_type, is_active')
                          ->where('id', $userId)
                          ->get()
                          ->getRowArray();

            if (!$userRow || (int)$userRow['is_active'] === 0) {
                session()->destroy();
                return redirect()->to(site_url('login'))
                    ->with('error', 'Your account has been deactivated or does not exist.');
            }

            $roleId = $userRow['role_id'] !== null ? (int)$userRow['role_id'] : null;
            $accountType = (int)$userRow['account_type'];

            if ($roleId === null) {
                // Fallback to constants mapping if not set
                $roleId = Constants::USER_ACCESS_LEVELS[$userId] ?? null;
                if ($roleId === null) {
                    if ((int)$accountType === 1) {
                        $roleId = Constants::USER_ROLE_ADMIN;
                    } else {
                        $roleId = Constants::USER_ROLE_MEDIA_TAGGING;
                    }
                }
            }

            $roleRow = $db->table('roles')->select('name')->where('id', $roleId)->get()->getRowArray();
            $roleName = $roleRow ? $roleRow['name'] : (Constants::USER_ROLES[$roleId] ?? 'Unknown');

            // Query dynamic permissions from role_permissions
            $dbPermissions = $db->table('role_permissions')
                                ->where('role_id', $roleId)
                                ->get()
                                ->getResultArray();

            $permissions = [];
            if (!empty($dbPermissions)) {
                foreach ($dbPermissions as $row) {
                    $permissions[$row['permission_key']] = (bool)$row['permission_value'];
                }
            } else {
                // Fallback to static constants configuration
                $permissions = Constants::USER_ROLES_ACCESS[$roleId] ?? [];
            }

            // Always update session with the freshest settings
            session()->set([
                'user_role'      => $roleId,
                'user_role_name' => $roleName,
                'permissions'    => $permissions,
            ]);
        }

        // Get the first segment of the URI
        $uri = $request->getUri();
        $segment = $uri->getTotalSegments() > 0 ? $uri->getSegment(1) : '';

        // If a segment matches one of our role-based keys, enforce authorization
        if ($segment !== '') {
            $restrictedPages = ['dashboard', 'tags', 'tag-requests', 'properties', 'vendors', 'jira', 'tasks', 'settings'];
            
            if (in_array($segment, $restrictedPages, true)) {
                $permissions = session()->get('permissions') ?? [];
                if (empty($permissions[$segment])) {
                    return redirect()->to(site_url('dashboard'))
                        ->with('error', 'You do not have permission to access the ' . esc($segment) . ' section.');
                }
            }
        }
    }

    /**
     * Auto-migrate helper to add role_id and role_permissions table,
     * seeding them with legacy mappings and assigning Admin role to lucky.
     */
    private static function autoMigrate($db)
    {
        // 1. Create roles table
        if (!$db->tableExists('roles')) {
            $db->query("CREATE TABLE `roles` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(150) NOT NULL UNIQUE,
                `created_at` DATETIME DEFAULT NULL,
                `updated_at` DATETIME DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

            // Seed default roles
            $db->table('roles')->insertBatch([
                [
                    'id'         => 1,
                    'name'       => 'Media Tagging',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id'         => 2,
                    'name'       => 'Martech',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id'         => 3,
                    'name'       => 'Admin',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            ]);
        }

        // 2. Add role_id column to users
        if (!$db->fieldExists('role_id', 'users')) {
            $db->query("ALTER TABLE `users` ADD COLUMN `role_id` INT DEFAULT NULL AFTER `account_type`");
            
            // Seed initial roles based on approved plan
            $db->query("UPDATE `users` SET `role_id` = 3 WHERE `id` = 10"); // admin -> Admin
            $db->query("UPDATE `users` SET `role_id` = 1 WHERE `id` = 1");  // marie -> Media Tagging
            $db->query("UPDATE `users` SET `role_id` = 2 WHERE `id` = 2");  // khristine -> Martech
            $db->query("UPDATE `users` SET `role_id` = 3 WHERE `id` = 3");  // lucky -> Admin (approved requirement)
            $db->query("UPDATE `users` SET `role_id` = 2 WHERE `id` = 4");  // janitha -> Martech
            $db->query("UPDATE `users` SET `role_id` = 1 WHERE `id` = 5");  // george -> Media Tagging
            $db->query("UPDATE `users` SET `role_id` = 3 WHERE `id` = 6");  // other -> Admin
        }

        // Add profile_photo column to users
        if (!$db->fieldExists('profile_photo', 'users')) {
            $db->query("ALTER TABLE `users` ADD COLUMN `profile_photo` VARCHAR(255) DEFAULT NULL AFTER `role_id`");
        }

        // 3. Create role_permissions table
        if (!$db->tableExists('role_permissions')) {
            $db->query("CREATE TABLE `role_permissions` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `role_id` INT NOT NULL,
                `permission_key` VARCHAR(100) NOT NULL,
                `permission_value` TINYINT(1) NOT NULL DEFAULT 0,
                `created_at` DATETIME DEFAULT NULL,
                `updated_at` DATETIME DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `role_permission_unique` (`role_id`, `permission_key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

            // Seed initial permissions from modified Constants
            $initialPermissions = [
                // Media Tagging (1)
                1 => ['dashboard' => 1, 'tags' => 1, 'tag-requests' => 1, 'properties' => 1, 'vendors' => 1, 'jira' => 1, 'tasks' => 1, 'settings' => 0],
                // Martech (2)
                2 => ['dashboard' => 1, 'tags' => 0, 'tag-requests' => 0, 'properties' => 0, 'vendors' => 0, 'jira' => 0, 'tasks' => 1, 'settings' => 0],
                // Admin (3)
                3 => ['dashboard' => 1, 'tags' => 1, 'tag-requests' => 1, 'properties' => 1, 'vendors' => 1, 'jira' => 1, 'tasks' => 1, 'settings' => 1],
            ];

            foreach ($initialPermissions as $roleId => $perms) {
                foreach ($perms as $key => $val) {
                    $db->table('role_permissions')->insert([
                        'role_id'          => $roleId,
                        'permission_key'   => $key,
                        'permission_value' => $val ? 1 : 0,
                        'created_at'       => date('Y-m-d H:i:s'),
                        'updated_at'       => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other filters, only the before method
     * does.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
