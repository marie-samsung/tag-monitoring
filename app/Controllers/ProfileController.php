<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\Constants;

class ProfileController extends BaseController
{
    /**
     * Display current user's profile.
     */
    public function index()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to(site_url('login'))->with('error', 'Please log in to access your profile.');
        }

        $usersModel = new UsersModel();
        $user = $usersModel->find($userId);

        if (!$user) {
            return redirect()->to(site_url('login'))->with('error', 'User details not found.');
        }

        // Get dynamic role name
        $db = \Config\Database::connect();
        $roleId = $user['role_id'] !== null ? (int)$user['role_id'] : null;
        if ($roleId === null) {
            $roleId = Constants::USER_ACCESS_LEVELS[$userId] ?? null;
            if ($roleId === null) {
                if ((int)$user['account_type'] === 1) {
                    $roleId = Constants::USER_ROLE_ADMIN;
                } else {
                    $roleId = Constants::USER_ROLE_MEDIA_TAGGING;
                }
            }
        }

        $roleRow = $db->table('roles')->select('name')->where('id', $roleId)->get()->getRowArray();
        $roleName = $roleRow ? $roleRow['name'] : (Constants::USER_ROLES[$roleId] ?? 'Unknown');

        return view('profile/index', [
            'user' => $user,
            'roleName' => $roleName,
        ]);
    }

    /**
     * Handle profile update requests.
     */
    public function update()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to(site_url('login'))->with('error', 'Please log in to update your profile.');
        }

        $usersModel = new UsersModel();
        $user = $usersModel->find($userId);

        if (!$user) {
            return redirect()->to(site_url('login'))->with('error', 'User details not found.');
        }

        $firstName = trim($this->request->getPost('first_name'));
        $lastName = trim($this->request->getPost('last_name'));
        $username = trim($this->request->getPost('username'));

        // Validation
        if (empty($firstName) || empty($lastName) || empty($username)) {
            return redirect()->back()->with('error', 'First name, last name, and username are required.');
        }

        // Check if username is taken by someone else
        $existing = $usersModel->where('username', $username)->where('id !=', $userId)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Username is already taken by another user.');
        }

        $data = [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'username'   => $username,
        ];

        $removePhotoFlag = $this->request->getPost('remove_photo');

        // Photo upload check
        $photoFile = $this->request->getFile('profile_photo');
        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $type = $photoFile->getMimeType();
            $size = $photoFile->getSize(); // in bytes
            $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if (!in_array($type, $allowedTypes, true)) {
                return redirect()->back()->with('error', 'Invalid photo file format. Allowed formats: JPG, JPEG, PNG, GIF, WEBP.');
            }

            if ($size > 2 * 1024 * 1024) { // 2MB
                return redirect()->back()->with('error', 'The profile photo file size exceeds the 2MB limit.');
            }

            // Generate a unique name
            $newName = $photoFile->getRandomName();

            // Move to public/uploads/profile_photos/
            $uploadPath = ROOTPATH . 'public/uploads/profile_photos/';
            
            // Ensure directory exists
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Delete old profile photo if it exists
            if (!empty($user['profile_photo']) && file_exists($uploadPath . $user['profile_photo'])) {
                @unlink($uploadPath . $user['profile_photo']);
            }

            $photoFile->move($uploadPath, $newName);
            $data['profile_photo'] = $newName;
        } elseif ($removePhotoFlag === '1') {
            // Delete current profile photo if it exists
            $uploadPath = ROOTPATH . 'public/uploads/profile_photos/';
            if (!empty($user['profile_photo']) && file_exists($uploadPath . $user['profile_photo'])) {
                @unlink($uploadPath . $user['profile_photo']);
            }
            $data['profile_photo'] = null;
        }

        // Password update check
        $currentPassword = $this->request->getPost('current_password');
        $newPassword     = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (!empty($newPassword) || !empty($currentPassword) || !empty($confirmPassword)) {
            if (empty($currentPassword)) {
                return redirect()->back()->with('error', 'Please enter your current password to set a new password.');
            }

            if (empty($newPassword) || empty($confirmPassword)) {
                return redirect()->back()->with('error', 'Please enter and confirm your new password.');
            }

            if (!password_verify($currentPassword, $user['password'])) {
                return redirect()->back()->with('error', 'Incorrect current password.');
            }

            if ($newPassword !== $confirmPassword) {
                return redirect()->back()->with('error', 'New password and confirm password do not match.');
            }

            // Valid password update
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        if ($usersModel->update($userId, $data)) {
            // Update session values dynamically
            $sessionUpdate = [
                'username'  => $username,
                'user_name' => $firstName . ' ' . $lastName,
            ];
            if (array_key_exists('profile_photo', $data)) {
                $sessionUpdate['profile_photo'] = $data['profile_photo'];
            }
            session()->set($sessionUpdate);

            return redirect()->to(site_url('profile'))->with('success', 'Profile updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update profile details.');
    }
}
