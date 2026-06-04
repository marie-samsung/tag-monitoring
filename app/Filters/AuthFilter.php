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

        // Dynamically set/refresh role and permissions if missing or out-of-sync
        $userId = session()->get('user_id');
        $accountType = session()->get('account_type');

        if ($userId) {
            $roleId = Constants::USER_ACCESS_LEVELS[$userId] ?? null;
            if ($roleId === null) {
                if ((int)$accountType === 1) {
                    $roleId = Constants::USER_ROLE_ADMIN;
                } else {
                    $roleId = Constants::USER_ROLE_MEDIA_TAGGING;
                }
            }
            $roleName = Constants::USER_ROLES[$roleId] ?? 'Unknown';
            $permissions = Constants::USER_ROLES_ACCESS[$roleId] ?? [];

            // Update session if it's out of sync
            if (session()->get('user_role') !== $roleId || session()->get('permissions') === null) {
                session()->set([
                    'user_role'      => $roleId,
                    'user_role_name' => $roleName,
                    'permissions'    => $permissions,
                ]);
            }
        }

        // Get the first segment of the URI
        $uri = $request->getUri();
        $segment = $uri->getTotalSegments() > 0 ? $uri->getSegment(1) : '';

        // If a segment matches one of our role-based keys, enforce authorization
        if ($segment !== '') {
            $permissions = session()->get('permissions');
            // We use USER_ROLE_MEDIA_TAGGING to extract the full list of controlled pages
            $restrictedPages = array_keys(Constants::USER_ROLES_ACCESS[Constants::USER_ROLE_MEDIA_TAGGING] ?? []);
            
            if (in_array($segment, $restrictedPages, true)) {
                if (empty($permissions[$segment])) {
                    return redirect()->to(site_url('dashboard'))
                        ->with('error', 'You do not have permission to access the ' . esc($segment) . ' section.');
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
