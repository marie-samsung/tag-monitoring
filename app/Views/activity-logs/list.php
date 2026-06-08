<?php

use App\Models\SubsidiariesModel;

function getSortUrl($field, $currentSortBy, $currentSortDir) {
    $queryParams = $_GET;
    $queryParams['sort_by'] = $field;
    if ($currentSortBy === $field) {
        $queryParams['sort_dir'] = ($currentSortDir === 'asc') ? 'desc' : 'asc';
    } else {
        $queryParams['sort_dir'] = 'asc';
    }
    return site_url('activity-logs') . '?' . http_build_query($queryParams);
}

function getSortIcon($field, $currentSortBy, $currentSortDir) {
    if ($currentSortBy === $field) {
        return $currentSortDir === 'asc' ? ' <i class="bi bi-arrow-up text-primary"></i>' : ' <i class="bi bi-arrow-down text-primary"></i>';
    }
    return ' <i class="bi bi-arrow-down-up text-muted" style="font-size: 0.75rem; opacity: 0.4;"></i>';
}

?>
<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Activity Logs
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    use App\Models\Constants;
    helper('custom_functions');
    $isAdmin = (session()->get('user_role') === Constants::USER_ROLE_ADMIN);

    $activeFiltersCount = 0;
    $advancedFilterKeys = ['subsidiary_id', 'project_id', 'assignee', 'status', 'priority', 'type'];
    foreach ($advancedFilterKeys as $key) {
        if (!empty($_GET[$key]) || (isset($_GET[$key]) && $_GET[$key] !== '')) {
            $activeFiltersCount++;
        }
    }
    $hasActiveFilters = ($activeFiltersCount > 0);
?>

<?= csrf_field() ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-3">
        <div>
            <?php $totalLogs = isset($pager) ? $pager->getTotal() : count($logs); ?>
            <h2 class="mb-0 fw-bold d-flex align-items-center gap-2">
                <span>Activity Logs</span>
                <span class="badge bg-secondary text-secondary" style="font-size: 0.85rem; font-weight: 600; padding: 0.35em 0.7em; border-radius: 20px;">
                    <?= $totalLogs ?>
                </span>
            </h2>
            <p class="text-secondary small mb-0">Record and track project management activities, support tasks, and campaign tag updates.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('activity-logs/create') ?>" title="Create a new log" class="apple-btn-primary btn-sm px-3 text-decoration-none">
                <i class="bi bi-plus-lg me-1"></i> Log Activity
            </a>
            
            <div class="dropdown">
                <button class="apple-btn-secondary btn-sm dropdown-toggle px-3 d-flex align-items-center gap-1" type="button" id="headerActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i> Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="headerActionsDropdown" style="border-radius: 10px; border: 1px solid var(--color-border-input);">
                    <li>
                        <a class="dropdown-item py-2" href="#" data-bs-toggle="collapse" data-bs-target="#sizeLegendCollapse" style="font-size: 0.9rem;">
                            <i class="bi bi-info-circle-fill text-primary me-2"></i>Size Legend
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="<?= site_url('activity-logs/export?' . http_build_query($_GET)) ?>" style="font-size: 0.9rem;">
                            <i class="bi bi-file-earmark-arrow-down-fill text-secondary me-2"></i>Export as CSV
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Size Legend Collapse Panel -->
    <div class="collapse" id="sizeLegendCollapse">
        <div class="apple-card mb-4 bg-white">
            <h5 class="fw-bold mb-3 text-secondary d-flex align-items-center gap-2" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">
                <i class="bi bi-info-circle-fill text-primary"></i> T-Shirt Size Legend (Project Management)
            </h5>
            <div class="row g-3">
                <div class="col-md col-sm-6">
                    <div class="p-2 rounded border border-light bg-light h-100">
                        <span class="badge bg-secondary text-white mb-1">S</span>
                        <p class="small mb-0 text-muted"><strong>Small:</strong> Minor tasks, quick fixes, basic configs (effort &lt; 2 hours).</p>
                    </div>
                </div>
                <div class="col-md col-sm-6">
                    <div class="p-2 rounded border border-light bg-light h-100">
                        <span class="badge bg-warning text-dark mb-1">M</span>
                        <p class="small mb-0 text-muted"><strong>Medium:</strong> Standard setups, simple troubleshooting, standard tracking (effort 2-5 hours).</p>
                    </div>
                </div>
                <div class="col-md col-sm-6">
                    <div class="p-2 rounded border border-light bg-light h-100">
                        <span class="badge bg-danger text-white mb-1">L</span>
                        <p class="small mb-0 text-muted"><strong>Large:</strong> New tag integrations, complex tracking rules, multi-page setups (effort 1-2 days).</p>
                    </div>
                </div>
                <div class="col-md col-sm-6">
                    <div class="p-2 rounded border border-light bg-light h-100">
                        <span class="badge bg-danger text-white mb-1">XL</span>
                        <p class="small mb-0 text-muted"><strong>Extra Large:</strong> Major property setups, migrations, complex custom JS (effort 3-5 days).</p>
                    </div>
                </div>
                <div class="col-md col-sm-6">
                    <div class="p-2 rounded border border-light bg-light h-100">
                        <span class="badge bg-danger text-white mb-1">Sprint</span>
                        <p class="small mb-0 text-muted"><strong>Sprint:</strong> Massive undertakings, complex migrations, multi-system integration (effort 1-2 weeks).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- #region | SEARCH FILTER -->
    <form method="get" class="m-0" id="filterForm">
        <!-- Primary Search Bar -->
        <div class="apple-control-bar mb-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Search Input -->
                <div class="position-relative flex-grow-1" style="min-width: 250px;">
                    <i class="bi bi-search text-muted position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); font-size: 0.9rem;"></i>
                    <input 
                        type="text" 
                        name="keyword" 
                        class="form-control form-control-xs" 
                        style="height: 36px; padding-left: 36px !important; border-radius: 20px;"
                        placeholder="Search logs..."
                        value="<?= esc($_GET['keyword'] ?? '') ?>"
                    >
                </div>
                
                <!-- Toggle Filters Button -->
                <button type="button" class="apple-btn-secondary btn-sm d-inline-flex align-items-center gap-2" 
                        style="height: 36px; border-radius: 20px; font-weight: 500;" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#advancedFiltersCollapse" 
                        aria-expanded="<?= $hasActiveFilters ? 'true' : 'false' ?>" 
                        aria-controls="advancedFiltersCollapse">
                    <i class="bi bi-funnel"></i>
                    <span>Filters</span>
                    <?php if ($activeFiltersCount > 0): ?>
                        <span class="badge bg-primary text-white rounded-circle px-1.5 py-0.5" style="font-size: 0.75rem; min-width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center;">
                            <?= $activeFiltersCount ?>
                        </span>
                    <?php endif; ?>
                </button>

                <!-- Action Buttons -->
                <button type="submit" class="apple-btn-primary btn-sm px-4" style="height: 36px; border-radius: 20px;">Apply</button>
                <?php if (!empty($_GET)): ?>
                    <a href="<?= site_url('activity-logs') ?>" class="apple-btn-secondary btn-sm px-4 text-decoration-none d-inline-flex align-items-center justify-content-center" style="height: 36px; border-radius: 20px;">Clear</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Collapsible Advanced Filters Panel -->
        <div class="collapse <?= $hasActiveFilters ? 'show' : '' ?> mb-4" id="advancedFiltersCollapse">
            <div class="apple-card" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none; padding: 20px;">
                <h5 class="fw-bold mb-3 text-secondary d-flex align-items-center gap-2" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="bi bi-sliders text-primary"></i> Advanced Filters
                </h5>
                <div class="row g-3">
                    <!-- Subsidiary -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Subsidiary</label>
                        <select name="subsidiary_id" class="form-select form-select-xs" style="height: 34px; border-radius: 8px;">
                            <option value="">All Subsidiaries</option>
                            <?php foreach (Constants::ACTIVITY_LOG_SUBSIDIARIES as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('subsidiary_id', $filters['subsidiary_id'] ?? '') == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Project -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Project</label>
                        <select name="project_id" class="form-select form-select-xs" style="height: 34px; border-radius: 8px;">
                            <option value="">All Projects</option>
                            <?php foreach (Constants::PROJECTS as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('project_id', $filters['project_id'] ?? '') == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php if (!$isAdmin): ?>
                        <!-- Assignee -->
                        <div class="col-md-4 col-lg-3">
                            <label class="form-label-xs fw-semibold text-secondary mb-1">Assignee</label>
                            <select name="assignee" class="form-select form-select-xs" style="height: 34px; border-radius: 8px;">
                                <option value="">All Assignees</option>
                                <?php foreach (Constants::USERS as $value => $label): ?>
                                    <option value="<?= esc($value) ?>"
                                        <?= old('assignee', $filters['assignee'] ?? '') == $value ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <!-- Status -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Status</label>
                        <select name="status" class="form-select form-select-xs" style="height: 34px; border-radius: 8px;">
                            <option value="">All Statuses</option>
                            <?php foreach (Constants::ACTIVITY_LOGS_STATUS as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('status', $filters['status'] ?? null) == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Priority</label>
                        <select name="priority" class="form-select form-select-xs" style="height: 34px; border-radius: 8px;">
                            <option value="">All Priorities</option>
                            <?php foreach (Constants::PRIORITIES as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('priority', $filters['priority'] ?? null) == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Type -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Type</label>
                        <select name="type" class="form-select form-select-xs" style="height: 34px; border-radius: 8px;">
                            <option value="">All Types</option>
                            <?php foreach (Constants::ACTIVITY_TYPES as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('type', $filters['type'] ?? '') == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="sort_by" value="<?= esc($filters['sort_by'] ?? 'created_at') ?>">
        <input type="hidden" name="sort_dir" value="<?= esc($filters['sort_dir'] ?? 'desc') ?>">
    </form>

    <!-- Active Filters Pills -->
    <?php if ($activeFiltersCount > 0): ?>
        <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
            <span class="text-secondary small fw-medium">Active Filters:</span>
            <?php
            foreach ($advancedFilterKeys as $key) {
                if (!empty($_GET[$key]) || (isset($_GET[$key]) && $_GET[$key] !== '')) {
                    $val = $_GET[$key];
                    $label = '';
                    if ($key === 'subsidiary_id') {
                        $label = 'Subsidiary: ' . (Constants::ACTIVITY_LOG_SUBSIDIARIES[$val] ?? $val);
                    } elseif ($key === 'project_id') {
                        $label = 'Project: ' . (Constants::PROJECTS[$val] ?? $val);
                    } elseif ($key === 'assignee') {
                        $label = 'Assignee: ' . (Constants::USERS[$val] ?? $val);
                    } elseif ($key === 'status') {
                        $label = 'Status: ' . (Constants::ACTIVITY_LOGS_STATUS[$val] ?? $val);
                    } elseif ($key === 'priority') {
                        $label = 'Priority: ' . (Constants::PRIORITIES[$val] ?? $val);
                    } elseif ($key === 'type') {
                        $label = 'Type: ' . (Constants::ACTIVITY_TYPES[$val] ?? $val);
                    }
                    
                    $tempParams = $_GET;
                    unset($tempParams[$key]);
                    $clearUrl = site_url('activity-logs') . '?' . http_build_query($tempParams);
                    ?>
                    <a href="<?= $clearUrl ?>" class="badge bg-secondary text-decoration-none d-inline-flex align-items-center gap-1.5 py-1.5 px-2.5 rounded-pill border border-secondary" style="font-size: 0.8rem; font-weight: 500;">
                        <span><?= esc($label) ?></span>
                        <i class="bi bi-x-circle-fill text-muted" style="font-size: 0.75rem;"></i>
                    </a>
                    <?php
                }
            }
            ?>
        </div>
    <?php endif; ?>
    <!-- #endregion -->
</div>

<!-- #region | LOGS TABLE -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-secondary small fw-medium">
        Showing <?= count($logs) ?> of <?= $totalLogs ?> results
    </span>
</div>

<div class="apple-table-container">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                <th class="form-label-sm fw-semibold">
                    <a href="<?= getSortUrl('id', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                        ID <?= getSortIcon('id', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                    </a>
                </th>
                <th class="form-label-sm fw-semibold">
                    <a href="<?= getSortUrl('subsidiary_id', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                        Subsidiary <?= getSortIcon('subsidiary_id', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                    </a>
                </th>
                <th class="form-label-sm fw-semibold">
                    <a href="<?= getSortUrl('project_id', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                        Project <?= getSortIcon('project_id', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                    </a>
                </th>
                <th class="form-label-sm fw-semibold">
                    <a href="<?= getSortUrl('title', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                        Title <?= getSortIcon('title', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                    </a>
                </th>
                <?php if (!$isAdmin): ?>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('assignee', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            Assignee <?= getSortIcon('assignee', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                        </a>
                    </th>
                <?php endif; ?>
                <th class="form-label-sm fw-semibold">
                    <a href="<?= getSortUrl('status', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                        Status <?= getSortIcon('status', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                    </a>
                </th>
                <th class="form-label-sm fw-semibold">
                    <a href="<?= getSortUrl('priority', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                        Priority <?= getSortIcon('priority', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                    </a>
                </th>
                <th class="form-label-sm fw-semibold">
                    <a href="<?= getSortUrl('type', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                        Type <?= getSortIcon('type', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                    </a>
                </th>
                <th class="form-label-sm fw-semibold">
                    <a href="<?= getSortUrl('size', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                        Size <?= getSortIcon('size', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                    </a>
                </th>
                <th class="form-label-sm fw-semibold">
                    <a href="<?= getSortUrl('created_at', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                        Created At <?= getSortIcon('created_at', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                    </a>
                </th>
                <th class="form-label-sm fw-semibold text-dark">Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="11" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2 mb-2 d-block text-secondary" style="opacity: 0.5;"></i>
                        <span class="fw-semibold">No activity logs found</span>
                        <p class="small text-secondary mb-0">Try adjusting your filters or search keywords.</p>
                    </td>
                </tr>
            <?php else: ?>
                <?php
                    foreach ($logs as $log):

                        $subsidiary = Constants::ACTIVITY_LOG_SUBSIDIARIES[$log['subsidiary_id']] ?? 'Unknown';
                        $status = Constants::ACTIVITY_LOGS_STATUS[$log['status']] ?? 'Unknown';
                        $type = Constants::ACTIVITY_TYPES[$log['type']] ?? 'Unknown';
                        $project = Constants::PROJECTS[$log['project_id']] ?? 'Unknown';
                        $assignee = Constants::USERS[$log['assignee']] ?? Constants::USER_SYSTEM_LABEL;
                        $size = Constants::ACTIVITY_SIZES[$log['size']] ?? 'Unknown';
                ?>
                    <tr>
                        <td><?= $log['id'] ?></td>
                        <td>
                            <span class="badge" style="<?= random_bg_style($subsidiary) ?>">
                                <?= $subsidiary ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge" style="<?= random_bg_style($project) ?>">
                                <?= $project ?>
                            </span>
                        </td>
                        <td><?= $log['title'] ?></td>
                        <?php if (!$isAdmin): ?>
                            <td>
                                <span class="badge <?= assignee_class($log['assignee']) ?>">
                                    <?= $assignee ?>
                                </span>
                            </td>
                        <?php endif; ?>
                        <td>
                            <span class="badge <?= status_activity_logs_class($log['status']) ?>">
                                <?= Constants::ACTIVITY_LOGS_STATUS[$log['status']] ?? 'Unknown' ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= priority_class($log['priority']) ?>">
                                <?= Constants::PRIORITIES[$log['priority']] ?? 'Unknown' ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge" style="<?= random_bg_style($type) ?>">
                                <?= $type ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= size_class($log['size']) ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= esc(size_description($log['size'])) ?>" style="cursor: help;">
                                <?= $size ?>
                            </span>
                        </td>
                        <td class="form-label-sm" style="width:max-content; white-space:nowrap;"><?= esc(date('d-m-Y', strtotime($log['created_at']))) ?></td>
                        <td class="text-nowrap align-middle">
                            <div class="dropdown">
                                <button class="apple-btn-secondary btn-sm dropdown-toggle px-2.5 py-1 d-inline-flex align-items-center gap-1" type="button" id="actionsDropdown_<?= $log['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.8rem; border-radius: 9999px;">
                                    Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="actionsDropdown_<?= $log['id'] ?>" style="border-radius: 10px; border: 1px solid var(--color-border-input); z-index: 1050;">
                                    <li>
                                        <a class="dropdown-item py-2" href="<?= site_url('activity-logs/view/' . $log['id']) ?>" style="font-size: 0.85rem;">
                                            <i class="bi bi-eye text-primary me-2"></i>View Details
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="<?= site_url('activity-logs/update/' . $log['id']) ?>" style="font-size: 0.85rem;">
                                            <i class="bi bi-pencil-square text-warning me-2"></i>Update Log
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
    <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
        <div class="mt-4 d-flex justify-content-center">
            <?= $pager->links('default', 'apple_full') ?>
        </div>
    <?php endif; ?>
</div>
<!-- #endregion -->

<?= $this->endSection() ?>
