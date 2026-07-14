<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Settings: Administration
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php use App\Models\Constants; ?>

<?php
// Build a lookup map for dynamic roles
$rolesMap = [];
foreach ($roles as $r) {
    $rolesMap[(int)$r['id']] = $r['name'];
}
?>

<style>
    /* Premium Design Aesthetics & Animations */
    .apple-settings-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 16px;
        padding: 24px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .apple-tabs-container {
        border-bottom: 1px solid var(--color-border-subtle);
        margin-bottom: 24px;
    }

    .apple-tab-btn {
        background: none;
        border: none;
        padding: 10px 20px;
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--color-text-secondary);
        border-bottom: 2px solid transparent;
        transition: all 0.2s ease;
    }

    .apple-tab-btn:hover {
        color: var(--color-primary);
    }

    .apple-tab-btn.active {
        color: var(--color-primary);
        border-bottom-color: var(--color-primary);
        font-weight: 600;
    }

    /* Modal Backdrop Blur */
    .modal {
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    .modal-content {
        border-radius: 20px;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        background: #fdfdfd;
    }

    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.04);
        background: #fdfdfd;
    }

    /* Quick Action Buttons Hover */
    .btn-action-hover {
        transition: all 0.2s ease;
        border-radius: 8px;
    }

    .btn-action-hover:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    /* Permission Matrix Grid Styling */
    .permission-matrix th {
        font-weight: 600;
        color: var(--color-text-primary);
        font-size: 0.85rem;
        letter-spacing: 0.2px;
    }

    .permission-matrix td {
        font-size: 0.88rem;
    }

    .permission-checkbox {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        border: 1.5px solid var(--color-border-input);
        cursor: pointer;
        transition: all 0.1s ease;
    }

    .permission-checkbox:checked {
        background-color: var(--color-primary);
        border-color: var(--color-primary);
    }
</style>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
        <div>
            <h2 class="mb-0 fw-bold">Settings &amp; Configuration</h2>
            <p class="text-secondary small mb-0">Manage system users, dynamic access roles, and database backups.</p>
        </div>
    </div>

    <!-- Dynamic Settings Navigation Tabs -->
    <div class="apple-tabs-container d-flex gap-2">
        <button class="apple-tab-btn active" id="tab-general-btn" onclick="switchTab('general')">
            <i class="bi bi-sliders me-1"></i> General Settings
        </button>
        <button class="apple-tab-btn" id="tab-jira-btn" onclick="switchTab('jira')">
            <i class="bi bi-kanban me-1"></i> Martech JIRA
        </button>
        <button class="apple-tab-btn" id="tab-api-logs-btn" onclick="switchTab('api-logs')">
            <i class="bi bi-journal-code me-1"></i> API Logs
        </button>
        <button class="apple-tab-btn" id="tab-users-btn" onclick="switchTab('users')">
            <i class="bi bi-people-fill me-1"></i> Users Management
        </button>
        <button class="apple-tab-btn" id="tab-permissions-btn" onclick="switchTab('permissions')">
            <i class="bi bi-shield-lock-fill me-1"></i> Roles &amp; Permissions
        </button>
        <button class="apple-tab-btn" id="tab-backups-btn" onclick="switchTab('backups')">
            <i class="bi bi-database-fill-gear me-1"></i> System Backups
        </button>
    </div>

    <!-- ============================================== -->
    <!-- TAB 1: GENERAL SETTINGS -->
    <!-- ============================================== -->
    <div class="settings-tab-content" id="tab-general">
        <div class="apple-settings-card mt-2">
            <h4 class="fw-bold mb-3">General Settings</h4>
            <p class="text-secondary small mb-4">Configure system-wide behavior and default visibility parameters.</p>
            
            <form action="<?= site_url('settings/save-system-settings') ?>" method="post" style="max-width: 600px;">
                <?= csrf_field() ?>
                
                <!-- Toggle 1: Hide Assignees by Default for Weekly Reports -->
                <div class="d-flex align-items-start justify-content-between p-3 rounded-3 border mb-3 bg-white shadow-sm" style="transition: all 0.2s ease;">
                    <div style="flex: 1; padding-right: 20px;">
                        <h6 class="fw-bold mb-1">Hide Assignees in Weekly Reports</h6>
                        <span class="text-secondary" style="font-size: 0.8rem;">By default, weekly performance reports will omit assignee details to focus on aggregated metrics.</span>
                    </div>
                    <div class="form-check form-switch m-0 d-flex align-items-center" style="height: 24px;">
                        <input class="form-check-input permission-checkbox" type="checkbox" name="hide_assignee_weekly_reports" id="hideWeeklyReports" value="1" <?= ($systemSettings['hide_assignee_weekly_reports'] ?? true) ? 'checked' : '' ?> style="width: 2.2em; height: 1.1em; cursor: pointer;">
                    </div>
                </div>

                <!-- Toggle 2: Hide Assignees system-wide across all tables -->
                <div class="d-flex align-items-start justify-content-between p-3 rounded-3 border mb-3 bg-white shadow-sm" style="transition: all 0.2s ease;">
                    <div style="flex: 1; padding-right: 20px;">
                        <h6 class="fw-bold mb-1">Hide Assignees System-Wide</h6>
                        <span class="text-secondary" style="font-size: 0.8rem;">Completely hide the "Assignee" column, dropdowns, and filtering fields across all task lists, kanbans, spreadsheet views, and ticket details.</span>
                    </div>
                    <div class="form-check form-switch m-0 d-flex align-items-center" style="height: 24px;">
                        <input class="form-check-input permission-checkbox" type="checkbox" name="hide_assignee_system_wide" id="hideSystemWide" value="1" <?= ($systemSettings['hide_assignee_system_wide'] ?? false) ? 'checked' : '' ?> style="width: 2.2em; height: 1.1em; cursor: pointer;">
                    </div>
                </div>

                <!-- Toggle 3: Force Me Mode System-Wide -->
                <div class="d-flex align-items-start justify-content-between p-3 rounded-3 border mb-3 bg-white shadow-sm" style="transition: all 0.2s ease;">
                    <div style="flex: 1; padding-right: 20px;">
                        <h6 class="fw-bold mb-1">Force Me Mode System-Wide</h6>
                        <span class="text-secondary" style="font-size: 0.8rem;">Force all non-administrative users to view only their assigned tasks. They will not be able to turn off Me Mode. Administrative users are unaffected.</span>
                    </div>
                    <div class="form-check form-switch m-0 d-flex align-items-center" style="height: 24px;">
                        <input class="form-check-input permission-checkbox" type="checkbox" name="force_me_mode_system_wide" id="forceMeModeSystemWide" value="1" <?= ($systemSettings['force_me_mode_system_wide'] ?? false) ? 'checked' : '' ?> style="width: 2.2em; height: 1.1em; cursor: pointer;">
                    </div>
                </div>

                <!-- Dropdown: CodeIgniter Environment -->
                <div class="d-flex align-items-start justify-content-between p-3 rounded-3 border mb-4 bg-white shadow-sm" style="transition: all 0.2s ease;">
                    <div style="flex: 1; padding-right: 20px;">
                        <h6 class="fw-bold mb-1">CodeIgniter Environment Mode</h6>
                        <span class="text-secondary" style="font-size: 0.8rem;">Switch the application environment between **Development** (displays verbose error logs, debug toolbar, and disables query caching) and **Production** (hides detailed error logs and optimizes system performance).</span>
                    </div>
                    <div class="d-flex align-items-center" style="height: 24px;">
                        <select name="ci_environment" id="ciEnvironment" class="form-select form-select-xs" style="width: auto; min-width: 140px; cursor: pointer;">
                            <option value="development" <?= ($ci_environment === 'development') ? 'selected' : '' ?>>Development</option>
                            <option value="production" <?= ($ci_environment === 'production') ? 'selected' : '' ?>>Production</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-start">
                    <button type="submit" class="apple-btn-primary btn-action-hover px-4">
                        <i class="bi bi-check-circle-fill me-1.5"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- TAB: MARTECH JIRA SETTINGS -->
    <!-- ============================================== -->
    <div class="settings-tab-content d-none" id="tab-jira">
        <div class="apple-settings-card mt-2">
            <h4 class="fw-bold mb-3">Martech JIRA Configuration</h4>
            <p class="text-secondary small mb-4">Configure the API endpoints, project key, and authentication token for JIRA integration.</p>
            
            <form action="<?= site_url('settings/save-jira-settings') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-1">Create JIRA Issue Endpoint</label>
                        <p class="text-secondary small mb-2">Endpoint used when submitting a new task.</p>
                        <input type="text" class="form-control" name="mt_jira_create_issue" value="<?= esc($envVars['MT_JIRA_CREATE_ISSUE'] ?? '') ?>" placeholder="https://your-domain.atlassian.net/rest/api/2/issue">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-1">Edit JIRA Details Endpoint</label>
                        <p class="text-secondary small mb-2">Endpoint used for updating existing issue properties.</p>
                        <input type="text" class="form-control" name="mt_jira_edit_issue" value="<?= esc($envVars['MT_JIRA_EDIT_ISSUE'] ?? '') ?>" placeholder="https://your-domain.atlassian.net/rest/api/2/issue/">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-1">Update JIRA Status Endpoint</label>
                        <p class="text-secondary small mb-2">Endpoint used to transition an issue's status.</p>
                        <input type="text" class="form-control" name="mt_jira_update_status" value="<?= esc($envVars['MT_JIRA_UPDATE_STATUS'] ?? '') ?>" placeholder="https://your-domain.atlassian.net/rest/api/2/issue/{id}/transitions">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-1">Upload Attachment Endpoint</label>
                        <p class="text-secondary small mb-2">Endpoint for pushing attachments to a JIRA issue.</p>
                        <input type="text" class="form-control" name="mt_jira_upload_attachment" value="<?= esc($envVars['MT_JIRA_UPLOAD_ATTACHMENT'] ?? '') ?>" placeholder="https://your-domain.atlassian.net/rest/api/2/issue/{id}/attachments">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-1">Add JIRA Comment Endpoint</label>
                        <p class="text-secondary small mb-2">Endpoint for posting comments to a JIRA issue.</p>
                        <input type="text" class="form-control" name="mt_jira_add_comment" value="<?= esc($envVars['MT_JIRA_ADD_COMMENT'] ?? '') ?>" placeholder="https://your-domain.atlassian.net/rest/api/2/issue/{id}/comment">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-1">Update JIRA Comment Endpoint</label>
                        <p class="text-secondary small mb-2">Endpoint for editing/updating comments on a JIRA issue.</p>
                        <input type="text" class="form-control" name="mt_jira_update_comment" value="<?= esc($envVars['MT_JIRA_UPDATE_COMMENT'] ?? '') ?>" placeholder="https://your-domain.atlassian.net/rest/api/2/issue/{id}/comment/{commentId}">
                    </div>

                    <hr class="my-2 border-secondary opacity-25">

                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-1">Project Key</label>
                        <p class="text-secondary small mb-2">The default project key (e.g. MEACRMMT).</p>
                        <input type="text" class="form-control" name="mt_jira_project_key" value="<?= esc($envVars['MT_JIRA_PROJECT_KEY'] ?? '') ?>" placeholder="MEACRMMT">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-1">Authentication Token</label>
                        <p class="text-secondary small mb-2">The Bearer token or PAT used to authenticate.</p>
                        <div class="input-group">
                            <input type="password" class="form-control" id="mt_jira_auth_token" name="mt_jira_auth_token" value="<?= esc($envVars['MT_JIRA_AUTH_TOKEN'] ?? '') ?>" placeholder="Enter token...">
                            <button class="btn btn-outline-secondary" type="button" onclick="const input = document.getElementById('mt_jira_auth_token'); const icon = this.querySelector('i'); if (input.type === 'password') { input.type = 'text'; icon.classList.remove('bi-eye'); icon.classList.add('bi-eye-slash'); } else { input.type = 'password'; icon.classList.remove('bi-eye-slash'); icon.classList.add('bi-eye'); }">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-start">
                    <button type="submit" class="apple-btn-primary btn-action-hover px-4">
                        <i class="bi bi-check-circle-fill me-1.5"></i> Save JIRA Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- TAB 2: USERS MANAGEMENT -->
    <!-- ============================================== -->
    <div class="settings-tab-content d-none" id="tab-users">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
            <!-- Search field -->
            <div class="position-relative" style="max-width: 320px; flex: 1;">
                <input type="text" id="user-search" class="form-control form-control-xs ps-5" placeholder="Search users by name or username..." onkeyup="filterUsersTable()">
                <i class="bi bi-search text-muted position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); font-size: 0.85rem;"></i>
            </div>
            
            <button class="apple-btn-primary btn-action-hover" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-person-plus-fill me-1"></i> Add New User
            </button>
        </div>

        <div class="apple-settings-card table-responsive">
            <table class="table table-hover align-middle mb-0" id="users-table">
                <thead>
                    <tr style="border-bottom: 2px solid var(--color-border-subtle);">
                        <th style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: var(--color-text-secondary);">User ID</th>
                        <th style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: var(--color-text-secondary);">Full Name</th>
                        <th style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: var(--color-text-secondary);">Username</th>
                        <th style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: var(--color-text-secondary);">Role</th>
                        <th style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: var(--color-text-secondary);">Status</th>
                        <th style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: var(--color-text-secondary);">Created At</th>
                        <th class="text-end" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: var(--color-text-secondary);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No users found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr class="user-row" data-name="<?= esc(strtolower($user['first_name'] . ' ' . $user['last_name'])) ?>" data-username="<?= esc(strtolower($user['username'])) ?>">
                                <td class="fw-semibold">#<?= $user['id'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold text-primary" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                            <?= esc(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <span class="fw-semibold d-block"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td><code><?= esc($user['username']) ?></code></td>
                                <td>
                                    <?php 
                                        $uRole = $user['role_id'] ? (int)$user['role_id'] : null;
                                        if ($uRole === null) {
                                            $uRole = Constants::USER_ACCESS_LEVELS[$user['id']] ?? ((int)$user['account_type'] === 1 ? Constants::USER_ROLE_ADMIN : Constants::USER_ROLE_MEDIA_TAGGING);
                                        }
                                        $roleName = $rolesMap[$uRole] ?? 'User';
                                        
                                        $badgeClass = 'bg-secondary';
                                        if ($uRole === Constants::USER_ROLE_ADMIN) $badgeClass = 'bg-danger-subtle text-danger';
                                        elseif ($uRole === Constants::USER_ROLE_MARTECH) $badgeClass = 'bg-warning-subtle text-warning-emphasis';
                                        elseif ($uRole === Constants::USER_ROLE_MEDIA_TAGGING) $badgeClass = 'bg-primary-subtle text-primary';
                                    ?>
                                    <span class="badge <?= $badgeClass ?> rounded-pill px-2.5 py-1" style="font-size: 0.72rem; font-weight: 600;">
                                        <?= esc($roleName) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ((int)$user['is_active'] === 1): ?>
                                        <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1" style="font-size: 0.72rem; font-weight: 600;">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-secondary rounded-pill px-2.5 py-1" style="font-size: 0.72rem; font-weight: 600;">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-secondary" style="font-size: 0.85rem;"><?= esc($user['created_at']) ?></td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <button class="btn btn-outline-secondary btn-sm btn-action-hover px-2.5" onclick="openEditUserModal(<?= htmlspecialchars(json_encode($user)) ?>, <?= $uRole ?>)">
                                            <i class="bi bi-pencil-fill me-1"></i> Edit
                                        </button>
                                        <?php if ((int)$user['id'] !== (int)session()->get('user_id')): ?>
                                            <button class="btn btn-outline-danger btn-sm btn-action-hover px-2.5" onclick="confirmDeleteUser(<?= htmlspecialchars(json_encode($user)) ?>)">
                                                <i class="bi bi-trash-fill"></i> Delete
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- TAB 2: ROLES & PERMISSIONS MATRIX -->
    <!-- ============================================== -->
    <div class="settings-tab-content d-none" id="tab-permissions">
        <form action="<?= site_url('settings/permissions/save') ?>" method="post">
            <?= csrf_field() ?>
            <div class="apple-settings-card mb-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="fw-bold mb-1"><i class="bi bi-shield-check text-primary me-2"></i>Access Level Mapping Matrix</h5>
                        <p class="text-secondary small mb-0">Customize what components and menu items are accessible to each user role group.</p>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary btn-action-hover" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                            <i class="bi bi-shield-plus me-1"></i> Add New Role
                        </button>
                        <button type="submit" class="apple-btn-primary btn-action-hover">
                            <i class="bi bi-check-circle-fill me-1"></i> Save Permissions Matrix
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle permission-matrix mb-0">
                        <thead>
                            <tr class="table-light text-secondary" style="border-bottom: 2px solid var(--color-border-subtle);">
                                <th style="width: 250px;">Section / Component</th>
                                <?php foreach ($roles as $r): ?>
                                    <th class="text-center">
                                        <div class="d-inline-flex align-items-center gap-2 justify-content-center">
                                            <span><?= esc($r['name']) ?></span>
                                            <button type="button" class="btn btn-link p-0 text-muted d-inline-flex align-items-center" style="font-size: 0.8rem; text-decoration: none;" onclick="openEditRoleModal(<?= $r['id'] ?>, '<?= esc($r['name'], 'js') ?>')" title="Rename Role">
                                                <i class="bi bi-pencil-square text-secondary"></i>
                                            </button>
                                        </div>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $modules = [
                                    'dashboard' => ['label' => 'Dashboard View', 'desc' => 'View status charts and pending request summary statistics.'],
                                    'tags' => ['label' => 'Tags (Inventory)', 'desc' => 'Browse, export, create, update, and manage core tracking tags.'],
                                    'tag-requests' => ['label' => 'Tag Requests', 'desc' => 'Manage tag request workflows, approve, and upload bulk Excel request files.'],
                                    'properties' => ['label' => 'Properties', 'desc' => 'Register and list website property configurations (AEM/Shop).'],
                                    'vendors' => ['label' => 'Vendors', 'desc' => 'List, create, edit, and audit details of third-party tags vendors.'],
                                    'jira' => ['label' => 'Jira Tickets Log', 'desc' => 'Log, download, and map JIRA tasks, linking tags to ticketing systems.'],
                                    'tasks' => ['label' => 'Workspace Tasks & Projects', 'desc' => 'Plan team projects and task assignments in inline-editable sprint views.'],
                                    'reports' => ['label' => 'Weekly Reports', 'desc' => 'Generate and export weekly performance reports of tags, requests, and tasks.'],
                                    'settings' => ['label' => 'Settings Administration', 'desc' => 'Superuser settings panel to manage users, roles, and create system backups.'],
                                ];

                                foreach ($modules as $key => $meta):
                            ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold d-block text-primary" style="font-size: 0.9rem;"><?= esc($meta['label']) ?></span>
                                        <span class="text-secondary" style="font-size: 0.75rem; font-weight: 400;"><?= esc($meta['desc']) ?></span>
                                    </td>
                                    <?php foreach ($roles as $r): ?>
                                        <td class="text-center">
                                            <?php 
                                                $roleId = (int)$r['id'];
                                                // Check permission
                                                $checked = isset($rolePermissions[$roleId][$key]) && $rolePermissions[$roleId][$key];
                                                // Fallback if not configured in DB
                                                if (empty($rolePermissions)) {
                                                    $checked = Constants::USER_ROLES_ACCESS[$roleId][$key] ?? false;
                                                }
                                            ?>
                                            <input type="checkbox" 
                                                   name="permissions[<?= $roleId ?>][<?= $key ?>]" 
                                                   value="1" 
                                                   class="form-check-input permission-checkbox mx-auto"
                                                   <?= $checked ? 'checked' : '' ?>
                                                   <?= $roleId === 3 && $key === 'settings' ? 'disabled onclick="return false;"' : '' ?>
                                            >
                                            <!-- Hidden input so that checked settings always have a value and disabled admin settings gets passed -->
                                            <?php if ($roleId === 3 && $key === 'settings'): ?>
                                                <input type="hidden" name="permissions[3][settings]" value="1">
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>

    <!-- ============================================== -->
    <!-- TAB 3: SYSTEM BACKUPS -->
    <!-- ============================================== -->
    <div class="settings-tab-content d-none" id="tab-backups">
        <div class="row mb-4">
            <!-- Daily Auto Backup Configuration Card -->
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="apple-settings-card h-100" style="border-left: 4px solid var(--color-primary);">
                    <h5 class="fw-bold mb-2"><i class="bi bi-clock-history text-primary me-2"></i>Daily Auto Backup Settings</h5>
                    <p class="text-secondary small mb-4">Automatically backup the database once a day. Backups are stored locally and pruned based on your retention limit.</p>
                    
                    <form action="<?= site_url('settings/backup/settings') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input permission-checkbox" type="checkbox" name="enabled" id="autoBackupEnabled" value="1" <?= $autoBackupSettings['enabled'] ? 'checked' : '' ?>>
                            <label class="form-check-label fw-semibold text-secondary ms-2" for="autoBackupEnabled">Enable Daily Auto Backup</label>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary mb-1">Retention Count (Files)</label>
                            <input type="number" name="retention_count" class="form-control form-control-xs" style="max-width: 120px;" min="1" max="100" value="<?= esc($autoBackupSettings['retention_count']) ?>" required>
                            <small class="text-muted d-block mt-1">Keep only the last N backup files. Older backups will be deleted automatically.</small>
                        </div>
                        
                        <div class="bg-light p-3 rounded-3 mb-4" style="font-size: 0.82rem;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-secondary">Last Auto-Backup:</span>
                                <span class="fw-semibold text-dark font-monospace"><?= esc($autoBackupSettings['last_backup'] ?? 'Never run') ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="text-secondary">Status Log:</span>
                                <span class="fw-semibold text-end font-monospace text-secondary" style="max-width: 70%; word-break: break-all;"><?= esc($autoBackupSettings['last_status'] ?? 'Never run') ?></span>
                            </div>
                        </div>
                        
                        <button type="submit" class="apple-btn-primary btn-action-hover w-100">
                            <i class="bi bi-save2 me-1"></i> Save Backup Settings
                        </button>
                    </form>
                </div>
            </div>

            <!-- Manual Snapshot Card -->
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="apple-settings-card h-100 d-flex flex-column justify-content-between" style="border-left: 4px solid #198754;">
                    <div>
                        <h5 class="fw-bold mb-2"><i class="bi bi-cloud-upload-fill text-success me-2"></i>Database Snapshots &amp; Backups</h5>
                        <p class="text-secondary small mb-3">Create database restoration snapshots. Keep copies locally, download them for safe keeping, or restore system state.</p>
                        <p class="text-secondary small mb-0">Manual backups also respect the retention limit and will trigger automatic pruning of older files.</p>
                    </div>
                    
                    <form action="<?= site_url('settings/backup/create') ?>" method="post" class="mt-4">
                        <?= csrf_field() ?>
                        <button type="submit" class="apple-btn-primary btn-action-hover w-100" style="background-color: #198754; border-color: #198754;">
                            <i class="bi bi-database-add me-1"></i> Create Manual Backup
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="apple-settings-card table-responsive">
            <h6 class="fw-bold mb-3"><i class="bi bi-list-task me-2 text-secondary"></i>Existing Backup Files</h6>
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr style="border-bottom: 2px solid var(--color-border-subtle);">
                        <th style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: var(--color-text-secondary);">Filename</th>
                        <th style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: var(--color-text-secondary);">File Size</th>
                        <th style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: var(--color-text-secondary);">Backup Date</th>
                        <th class="text-end" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: var(--color-text-secondary);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($backups)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No database backups found. Click "Create Database Backup" above.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($backups as $backup): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-file-earmark-code-fill text-success fs-4"></i>
                                        <span class="font-monospace fw-semibold"><?= esc($backup['filename']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark font-monospace" style="font-size: 0.8rem; font-weight: 500;">
                                        <?= number_format($backup['size'] / 1024, 2) ?> KB
                                    </span>
                                </td>
                                <td class="text-secondary" style="font-size: 0.85rem;">
                                    <?= esc($backup['created_at']) ?>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="<?= site_url('settings/backup/download/' . esc($backup['filename'])) ?>" class="btn btn-outline-primary btn-sm btn-action-hover px-2.5" title="Download SQL Dump">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                        
                                        <button type="button" class="btn btn-outline-warning btn-sm btn-action-hover px-2.5" onclick="confirmRestore('<?= esc($backup['filename']) ?>')">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </button>
                                        
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-action-hover px-2.5" onclick="confirmDelete('<?= esc($backup['filename']) ?>')">
                                            <i class="bi bi-trash-fill"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- TAB: API LOGS -->
    <!-- ============================================== -->
    <div class="settings-tab-content d-none" id="tab-api-logs">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">JIRA API Logs</h4>
                <p class="text-secondary small mb-0">View API logs and retry failed JIRA requests.</p>
            </div>
            <div>
                <select id="api-log-filter" class="form-select form-select-sm" onchange="filterApiLogs()">
                    <option value="all">All Statuses</option>
                    <option value="SUCCESS">Success</option>
                    <option value="FAILED">Failed</option>
                </select>
            </div>
        </div>

        <div class="table-responsive rounded-3 border bg-white">
            <table class="table table-hover align-middle mb-0 apple-table" id="api-logs-table">
                <thead class="table-light">
                    <tr>
                        <th class="text-secondary" style="font-weight: 600; font-size: 0.85rem;">ID</th>
                        <th class="text-secondary" style="font-weight: 600; font-size: 0.85rem;">Task ID</th>
                        <th class="text-secondary" style="font-weight: 600; font-size: 0.85rem;">Type</th>
                        <th class="text-secondary" style="font-weight: 600; font-size: 0.85rem;">Endpoint</th>
                        <th class="text-secondary" style="font-weight: 600; font-size: 0.85rem;">Payload</th>
                        <th class="text-secondary" style="font-weight: 600; font-size: 0.85rem;">Response</th>
                        <th class="text-secondary" style="font-weight: 600; font-size: 0.85rem;">Status</th>
                        <th class="text-secondary" style="font-weight: 600; font-size: 0.85rem;">Retries</th>
                        <th class="text-secondary" style="font-weight: 600; font-size: 0.85rem;">Date</th>
                        <th class="text-secondary text-end" style="font-weight: 600; font-size: 0.85rem;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($apiLogs)): ?>
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">No API logs found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($apiLogs as $log): ?>
                            <tr class="api-log-row" data-status="<?= esc($log['status']) ?>">
                                <td><span class="text-muted small">#<?= esc($log['id']) ?></span></td>
                                <td>
                                    <?php if ($log['task_id']): ?>
                                        <a href="<?= site_url('tasks/view/' . esc($log['task_id'])) ?>" class="text-primary text-decoration-none fw-medium" target="_blank">
                                            <?= esc($log['task_id']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge bg-secondary"><?= esc($log['request_type']) ?></span></td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 150px; font-size: 0.85rem;" title="<?= esc($log['endpoint_url']) ?>">
                                        <?= esc($log['endpoint_url']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 150px; font-size: 0.85rem; font-family: monospace;" title="<?= esc($log['payload']) ?>">
                                        <?= esc($log['payload']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 150px; font-size: 0.85rem; font-family: monospace;" title="<?= esc($log['response_body']) ?>">
                                        <?= esc($log['response_body']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($log['status'] === 'SUCCESS'): ?>
                                        <span class="badge bg-success text-white"><i class="bi bi-check-circle me-1"></i> Success</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger text-white" title="<?= esc($log['error_message']) ?>"><i class="bi bi-x-circle me-1"></i> Failed</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($log['retry_count'] > 0): ?>
                                        <span class="badge bg-warning text-dark rounded-pill"><?= esc($log['retry_count']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small">0</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="text-muted small"><?= date('M d, H:i', strtotime($log['created_at'])) ?></span></td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <?php if ($log['status'] !== 'SUCCESS'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" onclick="retryApiLog(<?= esc($log['id']) ?>)">
                                                <i class="bi bi-arrow-clockwise"></i> Retry
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-outline-info py-0 px-2" onclick="viewApiLog(<?= htmlspecialchars(json_encode($log), ENT_QUOTES, 'UTF-8') ?>)">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- API Logs Pagination -->
        <?php if (isset($apiLogsPager) && $apiLogsPager->getPageCount('apilogs') > 1): ?>
            <div class="mt-4 d-flex justify-content-center">
                <?= $apiLogsPager->links('apilogs', 'apple_full') ?>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>


<!-- ============================================== -->
<!-- MODAL: ADD NEW USER -->
<!-- ============================================== -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= site_url('settings/user/create') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header px-4 py-3">
                    <h5 class="modal-title fw-bold" id="addUserModalLabel">Add New System User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary mb-1">First Name</label>
                            <input type="text" name="first_name" class="form-control form-control-xs" required placeholder="Marie">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary mb-1">Last Name</label>
                            <input type="text" name="last_name" class="form-control form-control-xs" required placeholder="Samsung">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary mb-1">Username</label>
                            <input type="text" name="username" class="form-control form-control-xs" required placeholder="marie">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary mb-1">Password</label>
                            <input type="password" name="password" class="form-control form-control-xs" required placeholder="••••••••">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold text-secondary mb-1">System Role</label>
                            <select name="role_id" class="form-select form-select-xs" required>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= esc($r['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary mb-1">Status</label>
                            <select name="is_active" class="form-select form-select-xs" required>
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-4 py-3">
                    <button type="button" class="apple-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="apple-btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL: EDIT USER -->
<!-- ============================================== -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="edit-user-form" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-header px-4 py-3">
                    <h5 class="modal-title fw-bold" id="editUserModalLabel">Edit User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary mb-1">First Name</label>
                            <input type="text" name="first_name" id="edit-first-name" class="form-control form-control-xs" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary mb-1">Last Name</label>
                            <input type="text" name="last_name" id="edit-last-name" class="form-control form-control-xs" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary mb-1">Username</label>
                            <input type="text" name="username" id="edit-username" class="form-control form-control-xs" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary mb-1">New Password (leave blank to keep current)</label>
                            <input type="password" name="password" class="form-control form-control-xs" placeholder="••••••••">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold text-secondary mb-1">System Role</label>
                            <select name="role_id" id="edit-role-id" class="form-select form-select-xs" required>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= esc($r['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary mb-1">Status</label>
                            <select name="is_active" id="edit-is-active" class="form-select form-select-xs" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-4 py-3">
                    <button type="button" class="apple-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="apple-btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL: ADD NEW ROLE -->
<!-- ============================================== -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content">
            <form action="<?= site_url('settings/roles/create') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header px-4 py-3">
                    <h5 class="modal-title fw-bold" id="addRoleModalLabel">Create Custom Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary mb-1">Role Name</label>
                        <input type="text" name="role_name" class="form-control form-control-xs" required placeholder="e.g. Spoke Team Lead">
                        <small class="text-muted d-block mt-2" style="font-size: 0.72rem; line-height: 1.35;">
                            New roles start with default dashboard access only. You can adjust sections permissions in the matrix panel afterwards.
                        </small>
                    </div>
                </div>
                <div class="modal-footer px-4 py-3">
                    <button type="button" class="apple-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="apple-btn-primary">Create Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- DESTRUCTIVE ACTIONS CONFIRMATION MODALS -->
<!-- ============================================== -->

<!-- Restore Confirmation -->
<div class="modal fade" id="restoreBackupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content">
            <form id="restore-backup-form" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 3.5rem;"></i>
                    <h4 class="fw-bold mt-3 mb-2">Restore Database?</h4>
                    <p class="text-secondary small px-3">This action will **completely overwrite** the current database tables, settings, tags, and users with the selected snapshot data. There is no undo.</p>
                    <p class="font-monospace fw-semibold bg-light py-2 rounded text-danger small mt-3" id="restore-filename-display"></p>
                </div>
                <div class="modal-footer d-flex gap-2 justify-content-center p-3 border-0">
                    <button type="button" class="apple-btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="apple-btn-primary px-4 bg-warning border-warning text-dark fw-bold">Yes, Restore DB</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation -->
<div class="modal fade" id="deleteBackupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content">
            <form id="delete-backup-form" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-trash3-fill text-danger" style="font-size: 3.5rem;"></i>
                    <h4 class="fw-bold mt-3 mb-2">Delete Backup File?</h4>
                    <p class="text-secondary small px-3">Are you sure you want to permanently delete this SQL database backup snapshot? This cannot be recovered.</p>
                    <p class="font-monospace fw-semibold bg-light py-2 rounded text-danger small mt-3" id="delete-filename-display"></p>
                </div>
                <div class="modal-footer d-flex gap-2 justify-content-center p-3 border-0">
                    <button type="button" class="apple-btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="apple-btn-primary px-4 bg-danger border-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL: EDIT ROLE NAME -->
<!-- ============================================== -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content">
            <form id="edit-role-form" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-header px-4 py-3">
                    <h5 class="modal-title fw-bold" id="editRoleModalLabel">Rename System Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary mb-1">Role Name</label>
                        <input type="text" name="role_name" id="edit-role-name" class="form-control form-control-xs" required>
                    </div>
                </div>
                <div class="modal-footer px-4 py-3">
                    <button type="button" class="apple-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="apple-btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL: VIEW API LOG -->
<!-- ============================================== -->
<div class="modal fade" id="viewApiLogModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header px-4 py-3">
                <h5 class="modal-title fw-bold" id="viewLogTitle">API Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Endpoint</label>
                    <input type="text" class="form-control bg-light" id="viewLogEndpoint">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Payload</label>
                    <pre id="viewLogPayloadContainer" class="bg-light p-3 rounded text-dark overflow-auto mb-0" style="max-height: 250px; font-family: monospace; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.05);"><code id="viewLogPayload"></code></pre>
                    <textarea id="viewLogPayloadInput" class="form-control font-monospace bg-light" rows="8" style="font-size: 0.85rem; display: none;"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Response</label>
                    <pre class="bg-light p-3 rounded text-dark overflow-auto mb-0" style="max-height: 250px; font-family: monospace; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.05);"><code id="viewLogResponse"></code></pre>
                </div>
            </div>
            <div class="modal-footer px-4 py-3">
                <button type="button" class="apple-btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="apple-btn-primary" id="viewLogRetryBtn" onclick="retryApiLogFromModal()">
                    <i class="bi bi-arrow-clockwise me-1"></i> Retry Request
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL: DELETE USER CONFIRMATION -->
<!-- ============================================== -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content">
            <form id="delete-user-form" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-person-x-fill text-danger" style="font-size: 3.5rem;"></i>
                    <h4 class="fw-bold mt-3 mb-2">Delete User Account?</h4>
                    <p class="text-secondary small px-3">Are you sure you want to permanently delete this user account? All access for this user will be revoked immediately.</p>
                    <p class="font-monospace fw-semibold bg-light py-2 rounded text-danger small mt-3" id="delete-user-display"></p>
                </div>
                <div class="modal-footer d-flex gap-2 justify-content-center p-3 border-0">
                    <button type="button" class="apple-btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="apple-btn-primary px-4 bg-danger border-danger">Yes, Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- SCRIPTS FOR TAB SWITCHING, SEARCH & MODALS -->
<!-- ============================================== -->
<script>
    // Tab switcher
    function switchTab(tabId) {
        document.querySelectorAll('.apple-tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelectorAll('.settings-tab-content').forEach(tab => {
            tab.classList.add('d-none');
        });

        document.getElementById(`tab-${tabId}-btn`).classList.add('active');
        document.getElementById(`tab-${tabId}`).classList.remove('d-none');
        
        // Save current tab to local storage for persistence across redirects
        localStorage.setItem('activeSettingsTab', tabId);
    }

    // Switch tab on page load if stored or flash-redirected
    document.addEventListener('DOMContentLoaded', () => {
        let activeTab = '<?= session()->getFlashdata('active_tab') ?? '' ?>';
        if (!activeTab) {
            activeTab = localStorage.getItem('activeSettingsTab') || 'general';
        }
        
        // Force API logs tab if paginating
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('page_apilogs')) {
            activeTab = 'api-logs';
        }
        if (activeTab && document.getElementById(`tab-${activeTab}`)) {
            switchTab(activeTab);
        }
    });

    // Real-time client-side search filtering
    function filterUsersTable() {
        const query = document.getElementById('user-search').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.user-row');
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const username = row.getAttribute('data-username');
            
            if (name.includes(query) || username.includes(query)) {
                row.classList.remove('d-none');
            } else {
                row.classList.add('d-none');
            }
        });
    }

    // Fill Edit User Form
    function openEditUserModal(user, currentRole) {
        document.getElementById('edit-first-name').value = user.first_name;
        document.getElementById('edit-last-name').value = user.last_name;
        document.getElementById('edit-username').value = user.username;
        document.getElementById('edit-role-id').value = currentRole;
        document.getElementById('edit-is-active').value = user.is_active;
        
        // Set action url
        document.getElementById('edit-user-form').action = `<?= site_url('settings/user/update/') ?>${user.id}`;
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
        modal.show();
    }

    // Restore Backup Trigger
    function confirmRestore(filename) {
        document.getElementById('restore-filename-display').textContent = filename;
        document.getElementById('restore-backup-form').action = `<?= site_url('settings/backup/restore/') ?>${filename}`;
        
        const modal = new bootstrap.Modal(document.getElementById('restoreBackupModal'));
        modal.show();
    }

    // Delete Backup Trigger
    function confirmDelete(filename) {
        document.getElementById('delete-filename-display').textContent = filename;
        document.getElementById('delete-backup-form').action = `<?= site_url('settings/backup/delete/') ?>${filename}`;
        
        const modal = new bootstrap.Modal(document.getElementById('deleteBackupModal'));
        modal.show();
    }

    // Edit Role Name
    function openEditRoleModal(roleId, currentName) {
        document.getElementById('edit-role-name').value = currentName;
        document.getElementById('edit-role-form').action = `<?= site_url('settings/roles/update/') ?>${roleId}`;
        const modal = new bootstrap.Modal(document.getElementById('editRoleModal'));
        modal.show();
    }

    // Delete User Trigger
    function confirmDeleteUser(user) {
        document.getElementById('delete-user-display').textContent = `${user.first_name} ${user.last_name} (@${user.username})`;
        document.getElementById('delete-user-form').action = `<?= site_url('settings/user/delete/') ?>${user.id}`;
        const modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
        modal.show();
    }
    // API Logs Filter
    function filterApiLogs() {
        const status = document.getElementById('api-log-filter').value;
        const rows = document.querySelectorAll('.api-log-row');
        
        rows.forEach(row => {
            if (status === 'all' || row.dataset.status === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // API Logs Retry
    function retryApiLog(id, endpoint = null, payload = null) {
        if (!confirm('Are you sure you want to retry this JIRA API request?')) {
            return;
        }
        
        // Show loading state
        const btn = window.event ? window.event.currentTarget : null;
        let originalHtml = '';
        if (btn) {
            originalHtml = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            btn.disabled = true;
        }

        const bodyData = {};
        if (endpoint !== null) bodyData.endpoint = endpoint;
        if (payload !== null) bodyData.payload = payload;

        const fetchOptions = {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }
        };

        if (Object.keys(bodyData).length > 0) {
            fetchOptions.body = JSON.stringify(bodyData);
        }

        fetch(`<?= site_url('settings/retry-api-log/') ?>${id}`, fetchOptions)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Success: ' + data.message);
            } else {
                alert('Error: ' + data.message);
            }
            window.location.reload();
        })
        .catch(err => {
            alert('An unexpected error occurred while retrying the request.');
            if (btn) {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        });
    }

    let currentApiLogId = null;

    function formatJsonStr(str) {
        if (!str) return '';
        try {
            const obj = JSON.parse(str);
            return JSON.stringify(obj, null, 4);
        } catch (e) {
            return str;
        }
    }

    function viewApiLog(log) {
        currentApiLogId = log.id;
        document.getElementById('viewLogEndpoint').value = log.endpoint_url || '';
        document.getElementById('viewLogPayload').textContent = formatJsonStr(log.payload || '');
        document.getElementById('viewLogPayloadInput').value = formatJsonStr(log.payload || '');
        document.getElementById('viewLogResponse').textContent = formatJsonStr(log.response_body || '');
        
        let titleHtml = 'API Log Details';
        if (log.status === 'SUCCESS') {
            titleHtml += ' <span class="badge bg-success ms-2 align-middle" style="font-size: 0.8rem;"><i class="bi bi-check-circle me-1"></i>Success</span>';
            document.getElementById('viewLogRetryBtn').style.display = 'none';
            document.getElementById('viewLogEndpoint').setAttribute('readonly', true);
            document.getElementById('viewLogPayloadContainer').style.display = 'block';
            document.getElementById('viewLogPayloadInput').style.display = 'none';
        } else {
            titleHtml += ' <span class="badge bg-danger ms-2 align-middle" style="font-size: 0.8rem;"><i class="bi bi-x-circle me-1"></i>Failed</span>';
            document.getElementById('viewLogRetryBtn').style.display = 'inline-block';
            document.getElementById('viewLogEndpoint').removeAttribute('readonly');
            document.getElementById('viewLogPayloadContainer').style.display = 'none';
            document.getElementById('viewLogPayloadInput').style.display = 'block';
        }
        document.getElementById('viewLogTitle').innerHTML = titleHtml;
        
        const modal = new bootstrap.Modal(document.getElementById('viewApiLogModal'));
        modal.show();
    }

    function retryApiLogFromModal() {
        if (currentApiLogId) {
            const endpoint = document.getElementById('viewLogEndpoint').value;
            const payload = document.getElementById('viewLogPayloadInput').value;

            const modalEl = document.getElementById('viewApiLogModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
            retryApiLog(currentApiLogId, endpoint, payload);
        }
    }
</script>

<?= $this->endSection() ?>
