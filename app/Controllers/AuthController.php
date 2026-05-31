<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\Constants;

class AuthController extends BaseController
{
    /**
     * Handle user login.
     * Displays the login form and processes credentials.
     */
    public function login()
    {
        $usersModel = new UsersModel();

        // 1. Auto-seed default accounts on first load if users table is empty
        if ($usersModel->countAllResults() === 0) {
            $defaultUsers = [
                [
                    'id'           => 1, // Matches Constants::USER_MARIE
                    'first_name'   => 'Marie',
                    'last_name'    => 'Samsung',
                    'username'     => 'marie',
                    'password'     => password_hash('Password123', PASSWORD_DEFAULT),
                    'account_type' => 1, // Admin
                    'is_active'    => 1,
                ],
                [
                    'id'           => 2, // Matches Constants::USER_KHRISTINE
                    'first_name'   => 'Khristine',
                    'last_name'    => 'Samsung',
                    'username'     => 'khristine',
                    'password'     => password_hash('Password123', PASSWORD_DEFAULT),
                    'account_type' => 2, // User
                    'is_active'    => 1,
                ],
                [
                    'id'           => 3, // Matches Constants::USER_LUCKY
                    'first_name'   => 'Lucky',
                    'last_name'    => 'Samsung',
                    'username'     => 'lucky',
                    'password'     => password_hash('Password123', PASSWORD_DEFAULT),
                    'account_type' => 2, // User
                    'is_active'    => 1,
                ],
                [
                    'id'           => 4, // Matches Constants::USER_JANITHA
                    'first_name'   => 'Janitha',
                    'last_name'    => 'Samsung',
                    'username'     => 'janitha',
                    'password'     => password_hash('Password123', PASSWORD_DEFAULT),
                    'account_type' => 2, // User
                    'is_active'    => 1,
                ],
                [
                    'id'           => 5, // Matches Constants::USER_GEORGE
                    'first_name'   => 'George',
                    'last_name'    => 'Samsung',
                    'username'     => 'george',
                    'password'     => password_hash('Password123', PASSWORD_DEFAULT),
                    'account_type' => 2, // User
                    'is_active'    => 1,
                ],
                [
                    'id'           => 6, // Matches Constants::USER_OTHER
                    'first_name'   => 'Other',
                    'last_name'    => 'User',
                    'username'     => 'other',
                    'password'     => password_hash('Password123', PASSWORD_DEFAULT),
                    'account_type' => 2, // User
                    'is_active'    => 1,
                ],
                [
                    'id'           => 10,
                    'first_name'   => 'System',
                    'last_name'    => 'Admin',
                    'username'     => 'admin',
                    'password'     => password_hash('Password123', PASSWORD_DEFAULT),
                    'account_type' => 1, // Admin
                    'is_active'    => 1,
                ],
            ];

            foreach ($defaultUsers as $userData) {
                $usersModel->insert($userData);
            }
        }

        // 2. Redirect authenticated users away from the login page
        if (session()->get('isLoggedIn')) {
            return redirect()->to(site_url('tags'));
        }

        // 3. Process Login Attempt (POST)
        if ($this->request->getMethod() === 'POST') {
            $username = trim($this->request->getPost('username'));
            $password = $this->request->getPost('password');

            if (empty($username) || empty($password)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Please enter both username and password.');
            }

            // Find user
            $user = $usersModel->where('username', $username)
                               ->where('is_active', 1)
                               ->first();

            if ($user && password_verify($password, $user['password'])) {
                // Set session variables
                $sessionData = [
                    'user_id'      => (int)$user['id'],
                    'username'     => $user['username'],
                    'user_name'    => $user['first_name'] . ' ' . $user['last_name'],
                    'account_type' => (int)$user['account_type'],
                    'isLoggedIn'   => true,
                ];
                session()->set($sessionData);

                return redirect()->to(site_url('dashboard'))
                    ->with('success', 'Welcome back, ' . $user['first_name'] . '!');
            }

            // Invalid credentials
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid username or password.');
        }

        // 4. Render Login Form (GET)
        return view('auth/login');
    }

    /**
     * Handle user logout.
     * Clears all session data.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'))
            ->with('success', 'You have been logged out successfully.');
    }
}
