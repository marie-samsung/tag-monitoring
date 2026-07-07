<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\Constants;
use CodeIgniter\Files\File;

class SettingsController extends BaseController
{
    /**
     * Settings Dashboard index.
     * Displays User Management, Permissions Matrix, and Backup listing.
     */
    public function index()
    {
        $usersModel = new UsersModel();
        $db = \Config\Database::connect();

        // 1. Get all users
        $users = $usersModel->orderBy('id', 'DESC')->findAll();

        // 2. Fetch all dynamic roles from DB
        $roles = $db->table('roles')->get()->getResultArray();

        // 3. Fetch all role permissions from DB
        $rolePermissionsRaw = $db->table('role_permissions')->get()->getResultArray();
        $rolePermissions = [];
        foreach ($rolePermissionsRaw as $row) {
            $rolePermissions[(int)$row['role_id']][$row['permission_key']] = (bool)$row['permission_value'];
        }

        // 4. Scan backups directory
        $backupDir = WRITEPATH . 'backups/';
        $backups = [];
        if (is_dir($backupDir)) {
            $files = array_diff(scandir($backupDir), ['.', '..']);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $filePath = $backupDir . $file;
                    $backups[] = [
                        'filename' => $file,
                        'size' => filesize($filePath),
                        'created_at' => date('Y-m-d H:i:s', filemtime($filePath))
                    ];
                }
            }
        }

        // Sort backups by filename (newest first)
        usort($backups, function ($a, $b) {
            return strcmp($b['filename'], $a['filename']);
        });

        $backupHandler = new \App\Libraries\BackupHandler();
        $autoBackupSettings = $backupHandler->getSettings();

        // 5. Fetch API Logs
        $apiLogModel = new \App\Models\JiraApiLogModel();
        $apiLogs = $apiLogModel->orderBy('id', 'DESC')->findAll(100); // Limit to top 100 for performance

        return view('settings/index', [
            'users' => $users,
            'roles' => $roles,
            'rolePermissions' => $rolePermissions,
            'backups' => $backups,
            'autoBackupSettings' => $autoBackupSettings,
            'systemSettings' => self::getSystemSettings(),
            'ci_environment' => self::getCodeIgniterEnvironment(),
            'envVars'        => self::getEnvVariables(),
            'apiLogs'        => $apiLogs,
        ]);
    }

    /**
     * Create a new user.
     */
    public function createUser()
    {
        $usersModel = new UsersModel();

        $username = trim($this->request->getPost('username'));
        $password = $this->request->getPost('password');
        $roleId = (int)$this->request->getPost('role_id');

        if (empty($username) || empty($password)) {
            return redirect()->back()->with('error', 'Username and Password are required.');
        }

        // Check if username already exists
        if ($usersModel->where('username', $username)->first()) {
            return redirect()->back()->with('error', 'Username is already taken.');
        }

        // Determine account_type based on role_id mapping
        $accountType = 2; // default User
        if ($roleId === Constants::USER_ROLE_ADMIN) {
            $accountType = 1; // Admin
        }

        $usersModel->insert([
            'first_name'   => trim($this->request->getPost('first_name')),
            'last_name'    => trim($this->request->getPost('last_name')),
            'username'     => $username,
            'password'     => password_hash($password, PASSWORD_DEFAULT),
            'role_id'      => $roleId,
            'account_type' => $accountType,
            'is_active'    => (int)$this->request->getPost('is_active'),
        ]);

        return redirect()->to(site_url('settings'))->with('success', 'User created successfully.');
    }

    /**
     * Update an existing user.
     */
    public function updateUser($id)
    {
        $usersModel = new UsersModel();
        $user = $usersModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $username = trim($this->request->getPost('username'));
        $roleId = (int)$this->request->getPost('role_id');

        if (empty($username)) {
            return redirect()->back()->with('error', 'Username is required.');
        }

        // Check if username is taken by another user
        $existing = $usersModel->where('username', $username)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Username is already taken.');
        }

        // Determine account_type based on role_id mapping
        $accountType = 2;
        if ($roleId === Constants::USER_ROLE_ADMIN) {
            $accountType = 1;
        }

        $data = [
            'first_name'   => trim($this->request->getPost('first_name')),
            'last_name'    => trim($this->request->getPost('last_name')),
            'username'     => $username,
            'role_id'      => $roleId,
            'account_type' => $accountType,
            'is_active'    => (int)$this->request->getPost('is_active'),
        ];

        // Only update password if filled
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $usersModel->update($id, $data);

        return redirect()->to(site_url('settings'))->with('success', 'User updated successfully.');
    }

    /**
     * Save dynamic permission grid changes.
     */
    public function savePermissions()
    {
        $db = \Config\Database::connect();
        $permissionsGrid = $this->request->getPost('permissions') ?? [];

        // All controlled modules list
        $allModules = ['dashboard', 'tags', 'tag-requests', 'properties', 'vendors', 'jira', 'tasks', 'settings', 'reports'];
        
        // Fetch all dynamic roles from DB
        $roles = $db->table('roles')->get()->getResultArray();

        $db->transStart();
        // Clear old settings
        $db->table('role_permissions')->truncate();

        // Save new matrix
        foreach ($roles as $r) {
            $roleId = (int)$r['id'];
            foreach ($allModules as $module) {
                // If checkbox is checked, permission value is 1, else 0
                $val = isset($permissionsGrid[$roleId][$module]) ? 1 : 0;
                
                // Hard-prevent lockout of Admin settings access
                if ($roleId === 3 && $module === 'settings') {
                    $val = 1;
                }
                
                $db->table('role_permissions')->insert([
                    'role_id'          => $roleId,
                    'permission_key'   => $module,
                    'permission_value' => $val,
                    'created_at'       => date('Y-m-d H:i:s'),
                    'updated_at'       => date('Y-m-d H:i:s'),
                ]);
            }
        }
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Failed to update permissions.');
        }

        // Focus permissions tab after save
        session()->setFlashdata('active_tab', 'permissions');

        return redirect()->to(site_url('settings'))->with('success', 'Permissions updated successfully.');
    }

    /**
     * Create a new user role dynamically.
     */
    public function createRole()
    {
        $db = \Config\Database::connect();
        $name = trim($this->request->getPost('role_name'));

        if (empty($name)) {
            return redirect()->back()->with('error', 'Role name is required.');
        }

        // Check unique constraint
        $existing = $db->table('roles')->where('name', $name)->get()->getRowArray();
        if ($existing) {
            return redirect()->back()->with('error', 'Role name already exists.');
        }

        $db->table('roles')->insert([
            'name' => $name,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $newRoleId = $db->insertID();

        // Initialize default permissions (dashboard enabled, others disabled)
        $allModules = ['dashboard', 'tags', 'tag-requests', 'properties', 'vendors', 'jira', 'tasks', 'settings', 'reports'];
        foreach ($allModules as $module) {
            $db->table('role_permissions')->insert([
                'role_id'          => $newRoleId,
                'permission_key'   => $module,
                'permission_value' => ($module === 'dashboard') ? 1 : 0,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ]);
        }

        // Set active tab to permissions on redirect
        session()->setFlashdata('active_tab', 'permissions');

        return redirect()->to(site_url('settings'))->with('success', 'Role "' . esc($name) . '" created successfully.');
    }

    /**
     * Create a SQL database backup.
     */
    public function createBackup()
    {
        $backupHandler = new \App\Libraries\BackupHandler();
        $result = $backupHandler->runBackup(true);

        session()->setFlashdata('active_tab', 'backups');

        if ($result['success']) {
            return redirect()->to(site_url('settings'))->with('success', $result['message']);
        }

        return redirect()->to(site_url('settings'))->with('error', $result['message']);
    }

    /**
     * Save auto backup settings.
     */
    public function saveBackupSettings()
    {
        $backupHandler = new \App\Libraries\BackupHandler();

        $enabled = $this->request->getPost('enabled') === '1';
        $retentionCount = (int)$this->request->getPost('retention_count');

        if ($retentionCount < 1) {
            session()->setFlashdata('active_tab', 'backups');
            return redirect()->to(site_url('settings'))->with('error', 'Retention count must be at least 1.');
        }

        $settings = $backupHandler->getSettings();
        $settings['enabled'] = $enabled;
        $settings['retention_count'] = $retentionCount;

        session()->setFlashdata('active_tab', 'backups');

        if ($backupHandler->saveSettings($settings)) {
            return redirect()->to(site_url('settings'))->with('success', 'Daily auto backup settings saved successfully.');
        }

        return redirect()->to(site_url('settings'))->with('error', 'Failed to save auto backup settings.');
    }

    /**
     * Download an existing backup file.
     */
    public function downloadBackup($filename)
    {
        // Prevent directory traversal
        $filename = basename($filename);
        $filePath = WRITEPATH . 'backups/' . $filename;

        if (!file_exists($filePath)) {
            return redirect()->to(site_url('settings'))->with('error', 'Backup file not found.');
        }

        return $this->response->download($filePath, null);
    }

    /**
     * Delete a backup file.
     */
    public function deleteBackup($filename)
    {
        $filename = basename($filename);
        $filePath = WRITEPATH . 'backups/' . $filename;

        if (file_exists($filePath)) {
            unlink($filePath);
            return redirect()->to(site_url('settings'))->with('success', 'Backup file deleted.');
        }

        return redirect()->to(site_url('settings'))->with('error', 'Backup file not found.');
    }

    /**
     * Restore the database from a backup file.
     */
    public function restoreBackup($filename)
    {
        $filename = basename($filename);
        $filePath = WRITEPATH . 'backups/' . $filename;

        if (!file_exists($filePath)) {
            return redirect()->to(site_url('settings'))->with('error', 'Backup file not found.');
        }

        $sql = file_get_contents($filePath);
        if (empty($sql)) {
            return redirect()->to(site_url('settings'))->with('error', 'Backup file is empty.');
        }

        $db = \Config\Database::connect();

        // Restore using native mysqli multi-query for full SQL transaction parsing
        if ($db->connID instanceof \mysqli) {
            $db->connID->query("SET FOREIGN_KEY_CHECKS=0");
            
            if ($db->connID->multi_query($sql)) {
                do {
                    // Flush and free all results to proceed to next query
                    if ($result = $db->connID->store_result()) {
                        $result->free();
                    }
                } while ($db->connID->more_results() && $db->connID->next_result());
            }

            $db->connID->query("SET FOREIGN_KEY_CHECKS=1");

            if ($db->connID->errno) {
                return redirect()->to(site_url('settings'))
                    ->with('error', 'Database restore error: ' . $db->connID->error);
            }
        } else {
            // Fallback for non-mysqli connections: split statements manually
            // (Note: This is just a fallback, mysqli is the standard DBDriver in this project)
            $queries = explode(";\n", $sql);
            $db->query("SET FOREIGN_KEY_CHECKS=0");
            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    $db->query($query);
                }
            }
            $db->query("SET FOREIGN_KEY_CHECKS=1");
        }

        return redirect()->to(site_url('settings'))->with('success', 'Database restored successfully from "' . esc($filename) . '".');
    }

    /**
     * Memory-safe database exporter.
     */
    private function exportDatabase($db)
    {
        $tables = $db->listTables();
        
        $sql = "-- Martech TMS Database Backup\n";
        $sql .= "-- Date: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- --------------------------------------------------------\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            // Don't backup sessions table if using DB sessions to avoid locking or session errors
            if ($table === 'ci_sessions') {
                continue;
            }

            // 1. SHOW CREATE TABLE
            $query = $db->query("SHOW CREATE TABLE `" . $db->escapeString($table) . "`")->getRowArray();
            if (isset($query['Create Table'])) {
                $sql .= "DROP TABLE IF EXISTS `" . $table . "`;\n";
                $sql .= $query['Create Table'] . ";\n\n";
            }

            // 2. Dump all rows
            $rows = $db->table($table)->get()->getResultArray();
            if (!empty($rows)) {
                $sql .= "INSERT INTO `" . $table . "` VALUES \n";
                $inserts = [];
                foreach ($rows as $row) {
                    $vals = [];
                    foreach ($row as $field => $val) {
                        if ($val === null) {
                            $vals[] = 'NULL';
                        } else {
                            $vals[] = "'" . $db->escapeString($val) . "'";
                        }
                    }
                    $inserts[] = "(" . implode(', ', $vals) . ")";
                }
                $sql .= implode(",\n", $inserts) . ";\n\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        return $sql;
    }

    /**
     * Delete a system user.
     */
    public function deleteUser($id)
    {
        $usersModel = new UsersModel();
        $user = $usersModel->find($id);

        if (!$user) {
            return redirect()->to(site_url('settings'))->with('error', 'User not found.');
        }

        // Prevent self-deletion
        if ((int)$id === (int)session()->get('user_id')) {
            return redirect()->to(site_url('settings'))->with('error', 'You cannot delete your own account.');
        }

        $usersModel->delete($id);

        return redirect()->to(site_url('settings'))->with('success', 'User "' . esc($user['username']) . '" deleted successfully.');
    }

    /**
     * Update dynamic role name.
     */
    public function updateRole($id)
    {
        $db = \Config\Database::connect();
        $name = trim($this->request->getPost('role_name'));

        if (empty($name)) {
            return redirect()->back()->with('error', 'Role name is required.');
        }

        $role = $db->table('roles')->where('id', $id)->get()->getRowArray();
        if (!$role) {
            return redirect()->back()->with('error', 'Role not found.');
        }

        // Check unique constraint for other roles
        $existing = $db->table('roles')->where('name', $name)->where('id !=', $id)->get()->getRowArray();
        if ($existing) {
            return redirect()->back()->with('error', 'Role name already exists.');
        }

        $db->table('roles')->where('id', $id)->update([
            'name' => $name,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Focus permissions tab after save
        session()->setFlashdata('active_tab', 'permissions');

        return redirect()->to(site_url('settings'))->with('success', 'Role name updated to "' . esc($name) . '" successfully.');
    }

    /**
     * Get the CodeIgniter environment mode from the root .env file.
     */
    public static function getCodeIgniterEnvironment(): string
    {
        $envFile = ROOTPATH . '.env';
        if (file_exists($envFile)) {
            $content = file_get_contents($envFile);
            if (preg_match('/^\s*CI_ENVIRONMENT\s*=\s*(\w+)/m', $content, $matches)) {
                return trim($matches[1]);
            }
        }
        return 'production';
    }

    /**
     * Update the CodeIgniter environment mode in the root .env file.
     */
    public static function setCodeIgniterEnvironment(string $env): bool
    {
        $envFile = ROOTPATH . '.env';
        if (!file_exists($envFile)) {
            return false;
        }

        $content = file_get_contents($envFile);
        if (preg_match('/^\s*CI_ENVIRONMENT\s*=\s*\w+/m', $content)) {
            $newContent = preg_replace('/^(\s*CI_ENVIRONMENT\s*=\s*)\w+/m', '${1}' . $env, $content);
        } else {
            $newContent = $content . "\nCI_ENVIRONMENT = " . $env . "\n";
        }

        return file_put_contents($envFile, $newContent) !== false;
    }

    /**
     * Get the general system settings.
     */
    public static function getSystemSettings(): array
    {
        $settingsFile = WRITEPATH . 'system_settings.json';
        $default = [
            'hide_assignee_weekly_reports' => true,
            'hide_assignee_system_wide'    => false,
            'force_me_mode_system_wide'    => false,
        ];

        if (file_exists($settingsFile)) {
            $data = json_decode(file_get_contents($settingsFile), true);
            if (is_array($data)) {
                return array_merge($default, $data);
            }
        }

        return $default;
    }

    /**
     * Save general system settings.
     */
    public function saveSystemSettings()
    {
        $permissions = session()->get('permissions') ?? [];
        if (empty($permissions['settings'])) {
            return redirect()->to(site_url('dashboard'))
                ->with('error', 'You do not have permission to access the Settings section.');
        }

        $hideWeekly = $this->request->getPost('hide_assignee_weekly_reports') === '1';
        $hideSystem = $this->request->getPost('hide_assignee_system_wide') === '1';
        $forceMeMode = $this->request->getPost('force_me_mode_system_wide') === '1';

        $settings = [
            'hide_assignee_weekly_reports' => $hideWeekly,
            'hide_assignee_system_wide'    => $hideSystem,
            'force_me_mode_system_wide'    => $forceMeMode,
        ];

        $settingsFile = WRITEPATH . 'system_settings.json';
        $dir = dirname($settingsFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // Save system settings to JSON
        $savedJson = file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT)) !== false;

        // Save CI environment to .env
        $ciEnv = $this->request->getPost('ci_environment');
        $savedEnv = true;
        if (in_array($ciEnv, ['development', 'production'])) {
            $savedEnv = self::setCodeIgniterEnvironment($ciEnv);
        }

        session()->setFlashdata('active_tab', 'general');

        if ($savedJson && $savedEnv) {
            return redirect()->to(site_url('settings'))->with('success', 'General system settings saved successfully.');
        } elseif ($savedJson) {
            return redirect()->to(site_url('settings'))->with('warning', 'General settings saved, but failed to update environment in root .env file.');
        }

        return redirect()->to(site_url('settings'))->with('error', 'Failed to save general system settings.');
    }

    /**
     * Get all environment variables from the .env file.
     */
    public static function getEnvVariables(): array
    {
        $envFile = ROOTPATH . '.env';
        $vars = [];
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || str_starts_with($line, '#')) {
                    continue;
                }
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $vars[trim($key)] = trim($value, " '\"");
                }
            }
        }
        return $vars;
    }

    /**
     * Safely update or append multiple variables in the root .env file.
     */
    public static function updateEnvVariables(array $updates): bool
    {
        $envFile = ROOTPATH . '.env';
        if (!file_exists($envFile)) {
            // Try to create it if it doesn't exist
            file_put_contents($envFile, '');
        }

        $content = file_get_contents($envFile);
        
        foreach ($updates as $key => $value) {
            $value = trim($value);
            // We'll wrap the value in single quotes to be safe, unless it's empty
            $safeValue = $value === '' ? '' : "'" . str_replace("'", "\'", $value) . "'";

            // Check if key exists
            if (preg_match('/^(\s*' . preg_quote($key, '/') . '\s*=)(.*)$/m', $content)) {
                $content = preg_replace('/^(\s*' . preg_quote($key, '/') . '\s*=)(.*)$/m', '${1}' . $safeValue, $content);
            } else {
                $content .= "\n" . $key . "=" . $safeValue . "\n";
            }
        }

        // Clean up multiple newlines that might occur
        $content = preg_replace("/\n{3,}/", "\n\n", $content);

        return file_put_contents($envFile, $content) !== false;
    }

    /**
     * Save Jira specific settings directly to the .env file.
     */
    public function saveJiraSettings()
    {
        $permissions = session()->get('permissions') ?? [];
        if (empty($permissions['settings'])) {
            return redirect()->to(site_url('dashboard'))
                ->with('error', 'You do not have permission to access the Settings section.');
        }

        $updates = [
            'MT_JIRA_CREATE_ISSUE'    => $this->request->getPost('mt_jira_create_issue'),
            'MT_JIRA_EDIT_ISSUE'      => $this->request->getPost('mt_jira_edit_issue'),
            'MT_JIRA_UPDATE_STATUS'   => $this->request->getPost('mt_jira_update_status'),
            'MT_JIRA_UPLOAD_ATTACHMENT' => $this->request->getPost('mt_jira_upload_attachment'),
            'MT_JIRA_AUTH_TOKEN'      => $this->request->getPost('mt_jira_auth_token'),
            'MT_JIRA_PROJECT_KEY'     => $this->request->getPost('mt_jira_project_key'),
        ];

        session()->setFlashdata('active_tab', 'jira');

        if (self::updateEnvVariables($updates)) {
            return redirect()->to(site_url('settings'))->with('success', 'Jira settings successfully updated in .env.');
        }

        return redirect()->to(site_url('settings'))->with('error', 'Failed to update Jira settings.');
    }

    /**
     * Retry a failed JIRA API request.
     */
    public function retryApiLog($id)
    {
        $permissions = session()->get('permissions') ?? [];
        if (empty($permissions['settings'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $apiLogModel = new \App\Models\JiraApiLogModel();
        $log = $apiLogModel->find($id);

        if (!$log) {
            return $this->response->setJSON(['success' => false, 'message' => 'Log not found']);
        }

        $jiraToken = getenv('MT_JIRA_AUTH_TOKEN');
        if (empty($jiraToken)) {
            return $this->response->setJSON(['success' => false, 'message' => 'JIRA Auth Token is not configured.']);
        }

        $endpoint = $log['endpoint_url'];
        $payload = $log['payload'];
        $requestType = $log['request_type'];

        try {
            $curl = curl_init();

            // Determine HTTP method and base headers
            $httpMethod = 'POST';
            if ($requestType === 'EDIT_ISSUE') {
                $httpMethod = 'PUT';
            }

            $headers = [
                'Authorization: Bearer ' . $jiraToken,
                'Content-Type: application/json',
                'Accept: application/json',
                'User-Agent: PostmanRuntime/7.32.3',
                'Expect:'
            ];

            // Reconstruct multipart/form-data for attachments
            if ($requestType === 'UPLOAD_ATTACHMENT') {
                $payloadData = json_decode($payload, true);
                if (is_array($payloadData) && isset($payloadData['file_path'])) {
                    $filePath = $payloadData['file_path'];
                    $fileName = $payloadData['file_name'];
                    
                    if (!file_exists($filePath)) {
                        return $this->response->setJSON(['success' => false, 'message' => 'File no longer exists for retry.']);
                    }
                    
                    $mimeType = function_exists('mime_content_type') ? mime_content_type($filePath) : 'application/octet-stream';
                    if (!$mimeType) $mimeType = 'application/octet-stream';
                    
                    $payload = ['file' => new \CURLFile($filePath, $mimeType, $fileName)];
                    
                    // Remove Content-Type so cURL automatically sets multipart/form-data with boundary
                    $headers = array_filter($headers, function($h) {
                        return strpos($h, 'Content-Type:') === false;
                    });
                    
                    // Add Atlassian Token
                    $headers[] = 'X-Atlassian-Token: no-check';
                } else {
                    return $this->response->setJSON(['success' => false, 'message' => 'Invalid payload format for attachment retry.']);
                }
            }

            curl_setopt_array($curl, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_HTTPHEADER => array_values($headers),
            ]);

            // Set method and payload
            if ($requestType === 'UPLOAD_ATTACHMENT') {
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
            } else {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $httpMethod);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
            }

            $body = curl_exec($curl);
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $errorMessage = curl_error($curl);
            curl_close($curl);

            $status = ($statusCode >= 200 && $statusCode < 300) ? 'SUCCESS' : 'FAILED';

            // Update log entry
            $apiLogModel->update($id, [
                'status' => $status,
                'status_code' => $statusCode,
                'response_body' => $body,
                'error_message' => $errorMessage,
                'retry_count' => $log['retry_count'] + 1,
            ]);

            session()->setFlashdata('active_tab', 'api-logs');

            if ($status === 'SUCCESS') {
                return $this->response->setJSON(['success' => true, 'message' => 'Request retried successfully.']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Request failed with status ' . $statusCode]);
            }
        } catch (\Exception $e) {
            $apiLogModel->update($id, [
                'status' => 'FAILED',
                'status_code' => 500,
                'error_message' => $e->getMessage(),
                'retry_count' => $log['retry_count'] + 1,
            ]);
            
            session()->setFlashdata('active_tab', 'api-logs');

            return $this->response->setJSON(['success' => false, 'message' => 'Exception occurred: ' . $e->getMessage()]);
        }
    }
}
