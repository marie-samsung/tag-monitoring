<?php

use App\Models\SubsidiariesModel;

function getSortUrl($field, $currentSortBy, $currentSortDir)
{
    $queryParams = $_GET;
    $queryParams['sort_by'] = $field;
    if ($currentSortBy === $field) {
        $queryParams['sort_dir'] = ($currentSortDir === 'asc') ? 'desc' : 'asc';
    } else {
        $queryParams['sort_dir'] = 'asc';
    }
    return site_url('tasks') . '?' . http_build_query($queryParams);
}

function getGroupFilterUrl($group)
{
    $queryParams = $_GET;
    if (($queryParams['status_group'] ?? '') === $group) {
        unset($queryParams['status_group']);
    } else {
        $queryParams['status_group'] = $group;
        unset($queryParams['status']);
    }
    unset($queryParams['page']);
    return site_url('tasks') . '?' . http_build_query($queryParams);
}

function getMeModeUrl()
{
    $queryParams = $_GET;
    $meModeSession = (session()->get('al_me_mode') == 1);
    $isMeModeActive = isset($queryParams['me_mode']) ? ($queryParams['me_mode'] == '1') : $meModeSession;

    if ($isMeModeActive) {
        $queryParams['me_mode'] = '0';
    } else {
        $queryParams['me_mode'] = '1';
        unset($queryParams['assignee']);
    }
    unset($queryParams['page']);
    return site_url('tasks') . '?' . http_build_query($queryParams);
}

function getSortIcon($field, $currentSortBy, $currentSortDir)
{
    if ($currentSortBy === $field) {
        return $currentSortDir === 'asc' ? ' <i class="bi bi-arrow-up text-primary"></i>' : ' <i class="bi bi-arrow-down text-primary"></i>';
    }
    return ' <i class="bi bi-arrow-down-up text-muted" style="font-size: 0.75rem; opacity: 0.4;"></i>';
}

?>
<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Tasks
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
use App\Models\Constants;
helper('custom_functions');

$statusCounts = $status_counts ?? [];
$pendingCount = ($statusCounts[1] ?? 0) + ($statusCounts[5] ?? 0);
$inProgressCount = ($statusCounts[2] ?? 0) + ($statusCounts[3] ?? 0) + ($statusCounts[4] ?? 0);
$blockedCount = $statusCounts[7] ?? 0;
$completedCount = $statusCounts[6] ?? 0;

$isMeMode = isset($_GET['me_mode']) ? ($_GET['me_mode'] == '1') : (session()->get('al_me_mode') == 1);

$isDWF = false;
$currentUserId = session()->get('user_id');
if ($currentUserId !== null) {
    $userTeamId = Constants::USER_TEAMS[$currentUserId] ?? null;
    if ($userTeamId === Constants::TEAM_DWF) {
        $isDWF = true;
    }
}

// Advanced filters inside the collapsible panel
$advancedFiltersCount = 0;
$advancedFilterKeys = ['subsidiary_id', 'team_id', 'project', 'assignee', 'status', 'priority', 'type', 'labels', 'created_from', 'created_to', 'started_from', 'started_to', 'finished_from', 'finished_to'];

if ($isDWF) {
    $advancedFilterKeys[] = 'campaign_status';
}

foreach ($advancedFilterKeys as $key) {
    if (!empty($_GET[$key]) || (isset($_GET[$key]) && $_GET[$key] !== '')) {
        $advancedFiltersCount++;
    }
}
$hasActiveAdvancedFilters = ($advancedFiltersCount > 0);

// Total active filters (including dashboard counts and me mode)
$activeFiltersCount = $advancedFiltersCount;
if (!empty($_GET['status_group'])) {
    $activeFiltersCount++;
}
if ($isMeMode && !($sysSettings['force_me_mode_system_wide'] ?? false && !((int) session()->get('user_role') === Constants::USER_ROLE_ADMIN || (int) session()->get('account_type') === 1))) {
    $activeFiltersCount++;
}
if (!empty($_GET['show_completed']) && $_GET['show_completed'] == '1') {
    $activeFiltersCount++;
}

// For general filter key rendering (like filter pills)
$allFilterKeys = ['subsidiary_id', 'team_id', 'project', 'assignee', 'status', 'priority', 'type', 'labels', 'created_from', 'created_to', 'started_from', 'started_to', 'finished_from', 'finished_to', 'status_group', 'me_mode', 'show_completed'];

if ($isDWF) {
    $allFilterKeys[] = 'campaign_status';
}

$sysSettings = \App\Controllers\SettingsController::getSystemSettings();
$hideAssigneeSystemWide = $sysSettings['hide_assignee_system_wide'] ?? false;
$forceMeMode = $sysSettings['force_me_mode_system_wide'] ?? false;
$isAdmin = (int) session()->get('user_role') === Constants::USER_ROLE_ADMIN || (int) session()->get('account_type') === 1;
?>

<?= csrf_field() ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-3">
        <div>
            <?php $totalLogs = isset($pager) ? $pager->getTotal() : count($logs); ?>
            <h2 class="mb-0 fw-bold d-flex align-items-center gap-2">
                <span>Tasks</span>
                <span class="badge bg-secondary text-secondary"
                    style="font-size: 0.85rem; font-weight: 600; padding: 0.35em 0.7em; border-radius: 20px;">
                    <?= $totalLogs ?>
                </span>
                <span id="selectedCountBadge" class="badge bg-primary text-white"
                    style="display: none; font-size: 0.85rem; font-weight: 500; padding: 0.35em 0.7em; border-radius: 20px;">
                    0 selected
                </span>
            </h2>
            <p class="text-secondary small mb-0">Record and track project management activities, support tasks, and
                campaign tag updates.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('tasks/create') ?>" title="Create a new log"
                class="apple-btn-primary btn-sm px-3 text-decoration-none">
                <i class="bi bi-plus-lg me-1"></i> Create Task
            </a>

            <!-- Me Mode Toggle Button -->
            <?php if (!($forceMeMode && !$isAdmin)): ?>
                <a href="<?= getMeModeUrl() ?>"
                    class="<?= $isMeMode ? 'apple-btn-primary' : 'apple-btn-secondary' ?> btn-sm px-3 text-decoration-none">
                    <i class="bi <?= $isMeMode ? 'bi-person-fill' : 'bi-person' ?> me-1"></i> Assigned To Me
                </a>
            <?php endif; ?>

            <div class="dropdown">
                <button class="apple-btn-secondary btn-sm dropdown-toggle px-3 d-flex align-items-center gap-1"
                    type="button" id="headerActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i> Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="headerActionsDropdown"
                    style="border-radius: 10px; border: 1px solid var(--color-border-input);">
                    <li>
                        <a class="dropdown-item py-2" href="#" data-bs-toggle="collapse"
                            data-bs-target="#sizeLegendCollapse" style="font-size: 0.9rem;">
                            <i class="bi bi-info-circle-fill text-primary me-2"></i>Size Legend
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="<?= site_url('tasks/export?' . http_build_query($_GET)) ?>"
                            style="font-size: 0.9rem;">
                            <i class="bi bi-file-earmark-arrow-down-fill text-secondary me-2"></i>Export as Excel
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Size Legend Collapse Panel -->
    <div class="collapse" id="sizeLegendCollapse">
        <div class="apple-card mb-4 bg-white">
            <h5 class="fw-bold mb-3 text-secondary d-flex align-items-center gap-2"
                style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">
                <i class="bi bi-info-circle-fill text-primary"></i> T-Shirt Size Legend (Project Management)
            </h5>
            <div class="row g-3">
                <div class="col-md col-sm-6">
                    <div class="p-2 rounded border border-light bg-light h-100">
                        <span class="badge bg-secondary text-white mb-1">S</span>
                        <p class="small mb-0 text-muted"><strong>Small:</strong> Minor tasks, quick fixes, basic configs
                            (effort &lt; 2 hours).</p>
                    </div>
                </div>
                <div class="col-md col-sm-6">
                    <div class="p-2 rounded border border-light bg-light h-100">
                        <span class="badge bg-warning text-dark mb-1">M</span>
                        <p class="small mb-0 text-muted"><strong>Medium:</strong> Standard setups, simple
                            troubleshooting, standard tracking (effort 2-5 hours).</p>
                    </div>
                </div>
                <div class="col-md col-sm-6">
                    <div class="p-2 rounded border border-light bg-light h-100">
                        <span class="badge bg-danger text-white mb-1">L</span>
                        <p class="small mb-0 text-muted"><strong>Large:</strong> New tag integrations, complex tracking
                            rules, multi-page setups (effort 1-2 days).</p>
                    </div>
                </div>
                <div class="col-md col-sm-6">
                    <div class="p-2 rounded border border-light bg-light h-100">
                        <span class="badge bg-danger text-white mb-1">XL</span>
                        <p class="small mb-0 text-muted"><strong>Extra Large:</strong> Major property setups,
                            migrations, complex custom JS (effort 3-5 days).</p>
                    </div>
                </div>
                <div class="col-md col-sm-6">
                    <div class="p-2 rounded border border-light bg-light h-100">
                        <span class="badge bg-danger text-white mb-1">Sprint</span>
                        <p class="small mb-0 text-muted"><strong>Sprint:</strong> Massive undertakings, complex
                            migrations, multi-system integration (effort 1-2 weeks).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Status Counts -->
    <div class="row g-3 mb-4">
        <!-- Pending Card -->
        <div class="col-6 col-md-3">
            <a href="<?= getGroupFilterUrl('pending') ?>" class="dashboard-metric-link">
                <div
                    class="dashboard-metric-card card-pending <?= ($filters['status_group'] ?? '') === 'pending' ? 'active' : '' ?>">
                    <div class="dashboard-metric-info">
                        <span class="dashboard-metric-label">Pending</span>
                        <h3 class="dashboard-metric-value"><?= $pendingCount ?></h3>
                        <span class="dashboard-metric-desc">Not yet started & Pending review</span>
                    </div>
                    <i class="bi bi-hourglass-split dashboard-metric-icon"></i>
                </div>
            </a>
        </div>
        <!-- In Progress Card -->
        <div class="col-6 col-md-3">
            <a href="<?= getGroupFilterUrl('in_progress') ?>" class="dashboard-metric-link">
                <div
                    class="dashboard-metric-card card-in-progress <?= ($filters['status_group'] ?? '') === 'in_progress' ? 'active' : '' ?>">
                    <div class="dashboard-metric-info">
                        <span class="dashboard-metric-label">In Progress</span>
                        <h3 class="dashboard-metric-value"><?= $inProgressCount ?></h3>
                        <span class="dashboard-metric-desc">Investigation, Implementation & QA</span>
                    </div>
                    <i class="bi bi-gear-wide-connected dashboard-metric-icon"></i>
                </div>
            </a>
        </div>
        <!-- Blocked/Hold Card -->
        <div class="col-6 col-md-3">
            <a href="<?= getGroupFilterUrl('blocked') ?>" class="dashboard-metric-link">
                <div
                    class="dashboard-metric-card card-blocked <?= ($filters['status_group'] ?? '') === 'blocked' ? 'active' : '' ?>">
                    <div class="dashboard-metric-info">
                        <span class="dashboard-metric-label">Blocked / Hold</span>
                        <h3 class="dashboard-metric-value"><?= $blockedCount ?></h3>
                        <span class="dashboard-metric-desc">Blocked / On Hold logs</span>
                    </div>
                    <i class="bi bi-pause-circle dashboard-metric-icon"></i>
                </div>
            </a>
        </div>
        <!-- Completed Card -->
        <div class="col-6 col-md-3">
            <a href="<?= getGroupFilterUrl('completed') ?>" class="dashboard-metric-link">
                <div
                    class="dashboard-metric-card card-completed <?= ($filters['status_group'] ?? '') === 'completed' ? 'active' : '' ?>">
                    <div class="dashboard-metric-info">
                        <span class="dashboard-metric-label">Completed</span>
                        <h3 class="dashboard-metric-value"><?= $completedCount ?></h3>
                        <span class="dashboard-metric-desc">Completed logs</span>
                    </div>
                    <i class="bi bi-check-circle dashboard-metric-icon"></i>
                </div>
            </a>
        </div>
    </div>

    <!-- #region | SEARCH FILTER -->
    <form method="get" class="m-0" id="filterForm">
        <?php if (!empty($_GET['status_group'])): ?>
            <input type="hidden" name="status_group" value="<?= esc($_GET['status_group']) ?>">
        <?php endif; ?>
        <!-- Primary Search Bar -->
        <div class="apple-control-bar mb-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Search Input -->
                <div class="position-relative flex-grow-1" style="min-width: 250px;">
                    <i class="bi bi-search text-muted position-absolute"
                        style="left: 14px; top: 50%; transform: translateY(-50%); font-size: 0.9rem;"></i>
                    <input type="text" name="keyword" class="form-control form-control-xs"
                        style="height: 36px; padding-left: 36px !important; border-radius: 20px;"
                        placeholder="Search logs..." value="<?= esc($_GET['keyword'] ?? '') ?>">
                </div>

                <!-- Toggle Filters Button -->
                <button type="button" class="apple-btn-secondary btn-sm d-inline-flex align-items-center gap-2"
                    style="height: 36px; border-radius: 20px; font-weight: 500;" data-bs-toggle="collapse"
                    data-bs-target="#advancedFiltersCollapse"
                    aria-expanded="<?= $hasActiveAdvancedFilters ? 'true' : 'false' ?>"
                    aria-controls="advancedFiltersCollapse">
                    <i class="bi bi-funnel"></i>
                    <span>Filters</span>
                    <?php if ($advancedFiltersCount > 0): ?>
                        <span class="badge bg-primary text-white rounded-circle px-1.5 py-0.5"
                            style="font-size: 0.75rem; min-width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center;">
                            <?= $advancedFiltersCount ?>
                        </span>
                    <?php endif; ?>
                </button>

                <!-- Action Buttons -->
                <button type="submit" class="apple-btn-primary btn-sm px-4"
                    style="height: 36px; border-radius: 20px;">Apply</button>
                <?php if (!empty($_GET)): ?>
                    <a href="<?= site_url('tasks') ?>"
                        class="apple-btn-secondary btn-sm px-4 text-decoration-none d-inline-flex align-items-center justify-content-center"
                        style="height: 36px; border-radius: 20px;">Clear</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Collapsible Advanced Filters Panel -->
        <div class="collapse <?= $hasActiveAdvancedFilters ? 'show' : '' ?> mb-4" id="advancedFiltersCollapse">
            <div class="apple-card"
                style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none; padding: 20px;">
                <h5 class="fw-bold mb-3 text-secondary d-flex align-items-center gap-2"
                    style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="bi bi-sliders text-primary"></i> Advanced Filters
                </h5>
                <div class="row g-3">
                    <!-- Subsidiary -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Subsidiary</label>
                        <select name="subsidiary_id" class="form-select form-select-xs"
                            style="height: 34px; border-radius: 8px;">
                            <option value="">All Subsidiaries</option>
                            <?php foreach (Constants::ACTIVITY_LOG_SUBSIDIARIES as $value => $label): ?>
                                <option value="<?= esc($value) ?>" <?= old('subsidiary_id', $filters['subsidiary_id'] ?? '') == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Team -->
                    <!--                    <div class="col-md-4 col-lg-3">-->
                    <!--                        <label class="form-label-xs fw-semibold text-secondary mb-1">Team</label>-->
                    <!--                        <select name="team_id" class="form-select form-select-xs" style="height: 34px; border-radius: 8px;">-->
                    <!--                            <option value="">All Teams</option>-->
                    <!--                            --><?php //foreach (Constants::TEAMS as $value => $label): ?>
                    <!--                                <option value="--><?php //= esc($value) ?><!--"-->
                    <!--                                    --><?php //= old('team_id', $filters['team_id'] ?? '') == $value ? 'selected' : '' ?>
                    <!--                                    --><?php //= esc($label) ?>
                    <!--                                </option>-->
                    <!--                            --><?php //endforeach; ?>
                    <!--                        </select>-->
                    <!--                    </div>-->

                    <!-- Project -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Project</label>
                        <select name="project" class="form-select form-select-xs"
                            style="height: 34px; border-radius: 8px;">
                            <option value="">All Projects</option>
                            <?php foreach ($project_names as $pName): ?>
                                <option value="<?= esc($pName) ?>" <?= ($filters['project'] ?? '') == $pName ? 'selected' : '' ?>>
                                    <?= esc($pName) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Assignee -->
                    <?php if (!$hideAssigneeSystemWide): ?>
                        <div class="col-md-4 col-lg-3">
                            <label class="form-label-xs fw-semibold text-secondary mb-1">Assignee</label>
                            <select name="assignee" class="form-select form-select-xs"
                                style="height: 34px; border-radius: 8px;">
                                <option value="">All Assignees</option>
                                <?php foreach (Constants::USERS as $value => $label): ?>
                                    <option value="<?= esc($value) ?>" <?= old('assignee', $filters['assignee'] ?? '') == $value ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <!-- Status -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Status</label>
                        <select name="status" class="form-select form-select-xs"
                            style="height: 34px; border-radius: 8px;">
                            <option value="">All Statuses</option>
                            <?php foreach (Constants::ACTIVITY_LOGS_STATUS as $value => $label): ?>
                                <option value="<?= esc($value) ?>" <?= old('status', $filters['status'] ?? null) == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Priority</label>
                        <select name="priority" class="form-select form-select-xs"
                            style="height: 34px; border-radius: 8px;">
                            <option value="">All Priorities</option>
                            <?php foreach (Constants::PRIORITIES as $value => $label): ?>
                                <option value="<?= esc($value) ?>" <?= old('priority', $filters['priority'] ?? null) == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Type -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Type</label>
                        <select name="type" class="form-select form-select-xs"
                            style="height: 34px; border-radius: 8px;">
                            <option value="">All Types</option>
                            <?php foreach (Constants::ACTIVITY_TYPES as $value => $label): ?>
                                <option value="<?= esc($value) ?>" <?= old('type', $filters['type'] ?? '') == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Labels -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Labels</label>
                        <select name="labels[]" class="form-select form-select-xs select2-multiple" multiple="multiple"
                            style="width: 100%; border-radius: 8px;">
                            <?php
                            $selectedLabels = (array) ($filters['labels'] ?? []);
                            foreach ($all_labels as $lbl): ?>
                                <option value="<?= esc($lbl) ?>" <?= in_array($lbl, $selectedLabels) ? 'selected' : '' ?>>
                                    <?= esc($lbl) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <?php if ($isDWF): ?>
                        <!-- Campaign Status -->
                        <div class="col-md-4 col-lg-3">
                            <label class="form-label-xs fw-semibold text-secondary mb-1">Campaign Status</label>
                            <select name="campaign_status" class="form-select form-select-xs"
                                style="height: 34px; border-radius: 8px;">
                                <option value="">All Campaign Statuses</option>
                                <?php foreach (Constants::CAMPAIGN_STATUS as $value => $label): ?>
                                    <option value="<?= esc($value) ?>" <?= old('campaign_status', $filters['campaign_status'] ?? null) == $value ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <!-- Created From -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Created From</label>
                        <input type="date" name="created_from" class="form-control form-control-xs"
                            style="height: 34px; border-radius: 8px;"
                            value="<?= esc($filters['created_from'] ?? '') ?>">
                    </div>

                    <!-- Created To -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Created To</label>
                        <input type="date" name="created_to" class="form-control form-control-xs"
                            style="height: 34px; border-radius: 8px;" value="<?= esc($filters['created_to'] ?? '') ?>">
                    </div>

                    <!-- Started From -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Started From</label>
                        <input type="date" name="started_from" class="form-control form-control-xs"
                            style="height: 34px; border-radius: 8px;"
                            value="<?= esc($filters['started_from'] ?? '') ?>">
                    </div>

                    <!-- Started To -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Started To</label>
                        <input type="date" name="started_to" class="form-control form-control-xs"
                            style="height: 34px; border-radius: 8px;" value="<?= esc($filters['started_to'] ?? '') ?>">
                    </div>

                    <!-- Finished From -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Finished From</label>
                        <input type="date" name="finished_from" class="form-control form-control-xs"
                            style="height: 34px; border-radius: 8px;"
                            value="<?= esc($filters['finished_from'] ?? '') ?>">
                    </div>

                    <!-- Finished To -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Finished To</label>
                        <input type="date" name="finished_to" class="form-control form-control-xs"
                            style="height: 34px; border-radius: 8px;" value="<?= esc($filters['finished_to'] ?? '') ?>">
                    </div>

                    <!-- Show Completed -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">&nbsp;</label>
                        <div class="form-check form-switch d-flex align-items-center gap-2" style="height: 34px;">
                            <input class="form-check-input" type="checkbox" name="show_completed" value="1"
                                id="showCompletedSwitch" <?= (isset($_GET['show_completed']) && $_GET['show_completed'] == '1') ? 'checked' : '' ?> onchange="this.form.submit()"
                                style="cursor: pointer; width: 2.4em; height: 1.2em; margin-top: 0;">
                            <label class="form-check-label small fw-medium text-secondary mb-0"
                                for="showCompletedSwitch" style="cursor: pointer; user-select: none;">Show
                                Completed</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="sort_by" value="<?= esc($filters['sort_by'] ?? 'created_at') ?>">
        <input type="hidden" name="sort_dir" value="<?= esc($filters['sort_dir'] ?? 'desc') ?>">
        <input type="hidden" name="me_mode" value="<?= $isMeMode ? '1' : '0' ?>">
    </form>

    <!-- Active Filters Pills -->
    <?php if ($activeFiltersCount > 0): ?>
        <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
            <span class="text-secondary small fw-medium">Active Filters:</span>
            <?php
            foreach ($allFilterKeys as $key) {
                $showPill = false;
                $val = '';
                if ($key === 'me_mode') {
                    if ($isMeMode && !($forceMeMode && !$isAdmin)) {
                        $showPill = true;
                        $val = '1';
                    }
                } else {
                    if (!empty($_GET[$key]) || (isset($_GET[$key]) && $_GET[$key] !== '')) {
                        $showPill = true;
                        $val = $_GET[$key];
                    }
                }

                if ($showPill) {
                    $label = '';
                    if ($key === 'subsidiary_id') {
                        $label = 'Subsidiary: ' . (Constants::ACTIVITY_LOG_SUBSIDIARIES[$val] ?? $val);
                    } elseif ($key === 'team_id') {
                        $label = 'Team: ' . (Constants::TEAMS[$val] ?? $val);
                    } elseif ($key === 'project') {
                        $label = 'Project: ' . $val;
                    } elseif ($key === 'assignee') {
                        $label = 'Assignee: ' . (Constants::USERS[$val] ?? $val);
                    } elseif ($key === 'status') {
                        $label = 'Status: ' . (Constants::ACTIVITY_LOGS_STATUS[$val] ?? $val);
                    } elseif ($key === 'status_group') {
                        $groupLabel = '';
                        if ($val === 'pending')
                            $groupLabel = 'Pending';
                        elseif ($val === 'in_progress')
                            $groupLabel = 'In Progress';
                        elseif ($val === 'blocked')
                            $groupLabel = 'Blocked/Hold';
                        elseif ($val === 'completed')
                            $groupLabel = 'Completed';
                        $label = 'Status Group: ' . $groupLabel;
                    } elseif ($key === 'priority') {
                        $label = 'Priority: ' . (Constants::PRIORITIES[$val] ?? $val);
                    } elseif ($key === 'type') {
                        $label = 'Type: ' . (Constants::ACTIVITY_TYPES[$val] ?? $val);
                    } elseif ($key === 'labels') {
                        $label = 'Labels: ' . implode(', ', (array) $val);
                    } elseif ($key === 'me_mode') {

                        $label = 'Assigned To Me: Enabled';
                    } elseif ($key === 'show_completed') {
                        $label = 'Show Completed: Enabled';
                    } elseif ($key === 'campaign_status') {
                        $label = 'Campaign Status: ' . (Constants::CAMPAIGN_STATUS[$val] ?? $val);
                    } elseif ($key === 'created_from') {
                        $label = 'Created From: ' . $val;
                    } elseif ($key === 'created_to') {
                        $label = 'Created To: ' . $val;
                    } elseif ($key === 'started_from') {
                        $label = 'Started From: ' . $val;
                    } elseif ($key === 'started_to') {
                        $label = 'Started To: ' . $val;
                    } elseif ($key === 'finished_from') {
                        $label = 'Finished From: ' . $val;
                    } elseif ($key === 'finished_to') {
                        $label = 'Finished To: ' . $val;
                    }

                    $tempParams = $_GET;
                    if ($key === 'me_mode') {
                        $tempParams['me_mode'] = '0';
                    } else {
                        unset($tempParams[$key]);
                    }
                    $clearUrl = site_url('tasks') . '?' . http_build_query($tempParams);
                    ?>
                    <a href="<?= $clearUrl ?>"
                        class="badge bg-secondary text-decoration-none d-inline-flex align-items-center gap-1.5 py-1.5 px-2.5 rounded-pill border border-secondary"
                        style="font-size: 0.8rem; font-weight: 500;">
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
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <span class="text-secondary small fw-medium">
        Showing <?= count($logs) ?> of <?= $totalLogs ?> results
    </span>

    <!-- Action Style Switcher -->
    <div class="d-flex align-items-center gap-2">
        <span class="text-secondary"
            style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.8;">Action
            Style:</span>
        <div class="micro-segmented-control" id="actionStyleControl">
            <button type="button" data-style="original">Original</button>
            <button type="button" data-style="glassmorphic">Glassmorphic</button>
            <button type="button" data-style="hover-inline">Hover Inline</button>
        </div>
    </div>
</div>

<div class="apple-table-container">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 style-glassmorphic" id="tblActivityLogs">
            <thead>
                <tr>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('id', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>"
                            class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            ID
                            <?= getSortIcon('id', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                        </a>
                    </th>
                    <th><input type="checkbox" id="selectAllLogs" title="Select All" class="form-check-input"></th>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('subsidiary_id', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>"
                            class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            Subsidiary
                            <?= getSortIcon('subsidiary_id', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                        </a>
                    </th>
                    <!--                <th class="form-label-sm fw-semibold">-->
                    <!--                    <a href="--><?php //= getSortUrl('team_id', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?><!--" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">-->
                    <!--                        Team --><?php //= getSortIcon('team_id', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                    <!--                    </a>-->
                    <!--                </th>-->
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('project', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>"
                            class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            Project
                            <?= getSortIcon('project', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                        </a>
                    </th>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('title', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>"
                            class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            Title
                            <?= getSortIcon('title', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                        </a>
                    </th>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('ticket_id', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>"
                            class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            Ticket ID
                            <?= getSortIcon('ticket_id', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                        </a>
                    </th>
                    <?php if (!$hideAssigneeSystemWide): ?>
                        <th class="form-label-sm fw-semibold">
                            <a href="<?= getSortUrl('assignee', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>"
                                class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                                Assignee
                                <?= getSortIcon('assignee', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                            </a>
                        </th>
                    <?php endif; ?>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('status', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>"
                            class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            Status
                            <?= getSortIcon('status', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                        </a>
                    </th>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('priority', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>"
                            class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            Priority
                            <?= getSortIcon('priority', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                        </a>
                    </th>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('type', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>"
                            class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            Type
                            <?= getSortIcon('type', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                        </a>
                    </th>
                    <?php if ($isDWF): ?>
                        <th class="form-label-sm fw-semibold">
                            <a href="<?= getSortUrl('requested_by', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>"
                                class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                                Requested By
                                <?= getSortIcon('requested_by', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                            </a>
                        </th>
                        <th class="form-label-sm fw-semibold">
                            <a href="<?= getSortUrl('campaign_status', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>"
                                class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                                Campaign Status
                                <?= getSortIcon('campaign_status', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                            </a>
                        </th>
                    <?php endif; ?>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('size', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>"
                            class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            Size
                            <?= getSortIcon('size', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                        </a>
                    </th>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('requested_date', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>"
                            class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            Requested Date
                            <?= getSortIcon('requested_date', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                        </a>
                    </th>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('start_date', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>"
                            class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            Start Date
                            <?= getSortIcon('start_date', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                        </a>
                    </th>
                    <th class="form-label-sm fw-semibold text-dark text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="<?= ($isDWF ? 16 : 14) - ($hideAssigneeSystemWide ? 1 : 0) ?>"
                            class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 mb-2 d-block text-secondary" style="opacity: 0.5;"></i>
                            <span class="fw-semibold">No tasks found</span>
                            <p class="small text-secondary mb-0">Try adjusting your filters or search keywords.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                    foreach ($logs as $log):

                        $subsidiary = Constants::ACTIVITY_LOG_SUBSIDIARIES[$log['subsidiary_id']] ?? 'Unknown';
                        $status = Constants::ACTIVITY_LOGS_STATUS[$log['status']] ?? 'Unknown';
                        $type = Constants::ACTIVITY_TYPES[$log['type']] ?? 'Unknown';
                        $team = Constants::TEAMS[$log['team_id']] ?? 'Unknown';
                        $project = $log['project'] ?: 'Generic';
                        $assignee = Constants::USERS[$log['assignee']] ?? Constants::USER_SYSTEM_LABEL;
                        $size = Constants::ACTIVITY_SIZES[$log['size']] ?? 'Unknown';
                        ?>
                        <tr>
                            <td><?= $log['id'] ?></td>
                            <td>
                                <input type="checkbox" name="log_ids[]" value="<?= htmlspecialchars($log['id']) ?>"
                                    class="form-check-input">
                            </td>
                            <td>
                                <span class="badge" style="<?= random_bg_style($subsidiary) ?>">
                                    <?= $subsidiary ?>
                                </span>
                            </td>
                            <!--                        <td>-->
                            <!--                            <span class="badge" style="--><?php //= random_bg_style($team) ?><!--">-->
                            <!--                                --><?php //= $team ?>
                            <!--                            </span>-->
                            <!--                        </td>-->
                            <td>
                                <a href="<?= site_url('tasks/project/' . rawurlencode($project)) ?>"
                                    class="text-decoration-none" title="View Project Spreadsheet">
                                    <span class="badge" style="<?= random_bg_style($project) ?>">
                                        <?= esc($project) ?>
                                    </span>
                                </a>
                            </td>
                            <td>
                                <a href="<?= site_url('tasks/view/' . $log['id']) ?>" class="apple-link-dark">
                                    <?= $log['title'] ?>
                                </a>
                                <?php if (!empty($log['labels'])): ?>
                                    <div class="mt-1 d-flex flex-wrap gap-1">
                                        <?php foreach (explode(',', $log['labels']) as $lbl): 
                                            $lbl = trim($lbl);
                                            $currentLabels = isset($_GET['labels']) && is_array($_GET['labels']) ? $_GET['labels'] : [];
                                            if (!in_array($lbl, $currentLabels)) {
                                                $newLabels = array_merge($currentLabels, [$lbl]);
                                            } else {
                                                $newLabels = $currentLabels;
                                            }
                                            $tempParams = $_GET;
                                            $tempParams['labels'] = $newLabels;
                                            // Ensure we are redirecting to the first page when applying a new filter
                                            unset($tempParams['page']);
                                            $labelUrl = site_url('tasks') . '?' . http_build_query($tempParams);
                                        ?>
                                            <a href="<?= $labelUrl ?>" class="text-decoration-none" title="Filter by <?= esc($lbl) ?>">
                                                <span class="badge bg-primary text-white d-inline-flex align-items-center gap-1 rounded-pill"
                                                    style="font-size: 0.68rem; font-weight: 500; background-color: var(--color-badge-primary-bg) !important; color: var(--color-badge-primary-text) !important; border: 1px solid var(--color-badge-primary-border) !important; padding: 0.2em 0.5em; cursor: pointer; transition: transform 0.15s ease;"
                                                    onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                                                    <i class="bi bi-tag-fill text-primary" style="font-size: 0.65rem; opacity: 0.7;"></i>
                                                    <?= esc($lbl) ?>
                                                </span>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($log['ticket_id'])): ?>
                                    <a href="<?= Constants::JIRA_LINK . $log['ticket_id'] ?>" target="_blank"
                                        class="apple-link fw-bold">
                                        <?= esc($log['ticket_id']) ?>
                                    </a>
                                <?php elseif (!empty($log['mt_jira_ticket'])): ?>
                                    <a href="<?= Constants::MT_JIRA_LINK . $log['mt_jira_ticket'] ?>" target="_blank"
                                        class="apple-link fw-bold text-success">
                                        <?= esc($log['mt_jira_ticket']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-secondary small">-</span>
                                <?php endif; ?>
                            </td>
                            <?php if (!$hideAssigneeSystemWide): ?>
                                <td>
                                    <select
                                        class="badge status-select-badge <?= assignee_class($log['assignee']) ?> assignee-select"
                                        data-log-id="<?= $log['id'] ?>">
                                        <?php foreach (Constants::USERS as $val => $lbl): ?>
                                            <option value="<?= $val ?>" <?= $log['assignee'] == $val ? 'selected' : '' ?>
                                                class="bg-white text-dark text-start">
                                                <?= esc($lbl) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            <?php endif; ?>
                            <td>
                                <select class="badge status-select-badge <?= status_activity_logs_class($log['status']) ?>"
                                    data-log-id="<?= $log['id'] ?>">
                                    <?php foreach (Constants::ACTIVITY_LOGS_STATUS as $val => $lbl): ?>
                                        <option value="<?= $val ?>" <?= $log['status'] == $val ? 'selected' : '' ?>
                                            class="bg-white text-dark text-start">
                                            <?= esc($lbl) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select
                                    class="badge status-select-badge <?= priority_class($log['priority']) ?> priority-select"
                                    data-log-id="<?= $log['id'] ?>">
                                    <?php foreach (Constants::PRIORITIES as $val => $lbl): ?>
                                        <option value="<?= $val ?>" <?= $log['priority'] == $val ? 'selected' : '' ?>
                                            class="bg-white text-dark text-start">
                                            <?= esc($lbl) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <span class="badge" style="<?= random_bg_style($type) ?>">
                                    <?= $type ?>
                                </span>
                            </td>
                            <?php if ($isDWF): ?>
                                <td class="form-label-sm" style="width:max-content; white-space:nowrap;">
                                    <?= esc($log['requested_by'] ?? '—') ?>
                                </td>
                                <td>
                                    <?php
                                    $cStatus = Constants::CAMPAIGN_STATUS[$log['campaign_status']] ?? '—';
                                    if (empty($log['campaign_status'])) {
                                        $cStatus = '—';
                                    }
                                    ?>
                                    <span class="badge" style="<?= random_bg_style($cStatus) ?>">
                                        <?= esc($cStatus) ?>
                                    </span>
                                </td>
                            <?php endif; ?>
                            <td>
                                <span class="badge <?= size_class($log['size']) ?>" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="<?= esc(size_description($log['size'])) ?>"
                                    style="cursor: help;">
                                    <?= $size ?>
                                </span>
                            </td>
                            <td class="form-label-sm" style="width:max-content; white-space:nowrap;">
                                <?= !empty($log['requested_date']) ? esc(date('d-m-Y', strtotime($log['requested_date']))) : '<span class="text-secondary small">—</span>' ?>
                            </td>
                            <td class="form-label-sm" style="width:max-content; white-space:nowrap;">
                                <?= !empty($log['start_date']) ? esc(date('d-m-Y', strtotime($log['start_date']))) : '<span class="text-secondary small">—</span>' ?>
                            </td>
                            <td class="text-nowrap align-middle actions-cell">
                                <!-- Original Style -->
                                <div class="action-style-original-wrapper">
                                    <div class="dropdown">
                                        <button
                                            class="apple-btn-secondary btn-sm dropdown-toggle px-2.5 py-1 d-inline-flex align-items-center gap-1"
                                            type="button" id="actionsDropdown_<?= $log['id'] ?>" data-bs-toggle="dropdown"
                                            aria-expanded="false" style="font-size: 0.8rem; border-radius: 9999px;">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow"
                                            aria-labelledby="actionsDropdown_<?= $log['id'] ?>"
                                            style="border-radius: 10px; border: 1px solid var(--color-border-input); z-index: 1050;">
                                            <li>
                                                <a class="dropdown-item py-2" href="<?= site_url('tasks/view/' . $log['id']) ?>"
                                                    style="font-size: 0.85rem;">
                                                    <i class="bi bi-eye text-primary me-2"></i>View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-2"
                                                    href="<?= site_url('tasks/create?duplicate_id=' . $log['id']) ?>"
                                                    style="font-size: 0.85rem;">
                                                    <i class="bi bi-files text-info me-2"></i>Duplicate
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-2"
                                                    href="<?= site_url('tasks/update/' . $log['id']) ?>"
                                                    style="font-size: 0.85rem;">
                                                    <i class="bi bi-pencil-square text-warning me-2"></i>Edit Task
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Style A: Glassmorphic Dropdown -->
                                <div class="action-style-glassmorphic-wrapper">
                                    <div class="dropdown">
                                        <button
                                            class="action-trigger-dot d-inline-flex align-items-center justify-content-center"
                                            type="button" id="actionsDropdownA_<?= $log['id'] ?>" data-bs-toggle="dropdown"
                                            aria-expanded="false" title="Actions">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end glassmorphic-menu shadow"
                                            aria-labelledby="actionsDropdownA_<?= $log['id'] ?>" style="z-index: 1050;">
                                            <li>
                                                <a class="dropdown-item py-2"
                                                    href="<?= site_url('tasks/view/' . $log['id']) ?>">
                                                    <i class="bi bi-eye text-primary me-2"></i>View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-2"
                                                    href="<?= site_url('tasks/create?duplicate_id=' . $log['id']) ?>">
                                                    <i class="bi bi-files text-info me-2"></i>Duplicate
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-2"
                                                    href="<?= site_url('tasks/update/' . $log['id']) ?>">
                                                    <i class="bi bi-pencil-square text-warning me-2"></i>Edit Task
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Style B: Hover-Revealed Inline Actions -->
                                <div class="action-style-hover-inline-wrapper">
                                    <div class="hover-actions-container d-inline-flex align-items-center gap-1">
                                        <a href="<?= site_url('tasks/view/' . $log['id']) ?>" class="hover-action-btn btn-view"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="<?= site_url('tasks/create?duplicate_id=' . $log['id']) ?>"
                                            class="hover-action-btn btn-duplicate" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Duplicate Task">
                                            <i class="bi bi-files"></i>
                                        </a>
                                        <a href="<?= site_url('tasks/update/' . $log['id']) ?>"
                                            class="hover-action-btn btn-update" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Edit Task">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                    </div>
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

<!-- Floating Action Bar -->
<div id="floatingActionBar" class="floating-action-bar">
    <div class="floating-action-info">
        <span id="floatingSelectedCount" class="count-badge">0</span>
        <span>selected</span>
    </div>
    <div class="floating-action-divider"></div>

    <button type="button" id="floatingBtnComplete" class="floating-btn-primary" title="Mark Selected as Complete">
        <i class="bi bi-check-circle-fill"></i>
        <span>Mark Complete</span>
    </button>

    <div class="dropdown">
        <button class="floating-btn-secondary dropdown-toggle" type="button" id="floatingDropdownProject"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-folder-plus"></i>
            <span>Add to Project</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-dark shadow" aria-labelledby="floatingDropdownProject"
            style="max-height: 250px; overflow-y: auto; z-index: 1060;">
            <?php foreach ($project_names as $pName): ?>
                <?php if (!empty($pName)): ?>
                    <li><a class="dropdown-item py-2 floating-action-project-item" href="#"
                            data-project="<?= esc($pName) ?>"><?= esc($pName) ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
            <li>
                <hr class="dropdown-divider" style="background-color: rgba(255, 255, 255, 0.15);">
            </li>
            <li><a class="dropdown-item py-2 floating-action-project-new" href="#"><i
                        class="bi bi-plus-circle me-2"></i>New Project...</a></li>
        </ul>
    </div>

    <div class="dropdown">
        <button class="floating-btn-secondary dropdown-toggle" type="button" id="floatingDropdownAssignee"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-plus"></i>
            <span>Change Assignee</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-dark shadow" aria-labelledby="floatingDropdownAssignee"
            style="max-height: 250px; overflow-y: auto; z-index: 1060;">
            <?php foreach (Constants::USERS as $uId => $uName): ?>
                <li><a class="dropdown-item py-2 floating-action-assignee-item" href="#"
                        data-assignee-id="<?= $uId ?>"><?= esc($uName) ?></a></li>
            <?php endforeach; ?>
            <li><a class="dropdown-item py-2 floating-action-assignee-item" href="#"
                    data-assignee-id="<?= Constants::USER_SYSTEM ?>"><?= esc(Constants::USER_SYSTEM_LABEL) ?></a></li>
        </ul>
    </div>

    <div class="floating-action-divider"></div>

    <a href="#" id="floatingBtnDeselect" class="floating-btn-secondary" title="Deselect All">
        <i class="bi bi-x-circle"></i>
        <span>Deselect All</span>
    </a>
</div>

<style>
    /* Dashboard Count Cards Styling */
    .dashboard-metric-link {
        text-decoration: none !important;
        color: inherit !important;
        display: block;
    }

    .dashboard-metric-card {
        background: var(--color-card-bg);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid var(--color-border-subtle);
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .dashboard-metric-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-3px);
    }

    .dashboard-metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        transition: width 0.25s ease;
    }

    /* Specific Border Indicators */
    .dashboard-metric-card.card-pending::before {
        background-color: var(--color-badge-warning-text);
    }

    .dashboard-metric-card.card-in-progress::before {
        background-color: var(--color-badge-blue-text);
    }

    .dashboard-metric-card.card-blocked::before {
        background-color: var(--color-badge-danger-text);
    }

    .dashboard-metric-card.card-completed::before {
        background-color: var(--color-badge-success-text);
    }

    /* Active Selected States */
    .dashboard-metric-card.active {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .dashboard-metric-card.card-pending.active {
        background-color: var(--color-badge-warning-bg);
        border-color: var(--color-badge-warning-border);
    }

    .dashboard-metric-card.card-in-progress.active {
        background-color: var(--color-badge-blue-bg);
        border-color: var(--color-badge-blue-border);
    }

    .dashboard-metric-card.card-blocked.active {
        background-color: var(--color-badge-danger-bg);
        border-color: var(--color-badge-danger-border);
    }

    .dashboard-metric-card.card-completed.active {
        background-color: var(--color-badge-success-bg);
        border-color: var(--color-badge-success-border);
    }

    /* Expand the indicator bar slightly on hover/active */
    .dashboard-metric-card:hover::before,
    .dashboard-metric-card.active::before {
        width: 8px;
    }

    .dashboard-metric-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .dashboard-metric-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--color-text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .dashboard-metric-value {
        font-size: 2.25rem;
        font-weight: 700;
        line-height: 1.1;
        color: var(--color-text-base);
        margin: 0;
    }

    .dashboard-metric-desc {
        font-size: 0.725rem;
        color: var(--color-text-muted);
        font-weight: 500;
        margin-top: 4px;
        line-height: 1.25;
        transition: color 0.25s ease;
    }

    .dashboard-metric-card:hover .dashboard-metric-desc,
    .dashboard-metric-card.active .dashboard-metric-desc {
        color: var(--color-text-secondary);
    }

    .dashboard-metric-icon {
        font-size: 1.75rem;
        color: var(--color-text-muted);
        opacity: 0.6;
        transition: all 0.25s ease;
    }

    .dashboard-metric-card:hover .dashboard-metric-icon,
    .dashboard-metric-card.active .dashboard-metric-icon {
        opacity: 1;
        transform: scale(1.1);
    }

    /* Icon Colors when active or hovered */
    .dashboard-metric-card.card-pending:hover .dashboard-metric-icon,
    .dashboard-metric-card.card-pending.active .dashboard-metric-icon {
        color: var(--color-badge-warning-text);
    }

    .dashboard-metric-card.card-in-progress:hover .dashboard-metric-icon,
    .dashboard-metric-card.card-in-progress.active .dashboard-metric-icon {
        color: var(--color-badge-blue-text);
    }

    .dashboard-metric-card.card-blocked:hover .dashboard-metric-icon,
    .dashboard-metric-card.card-blocked.active .dashboard-metric-icon {
        color: var(--color-badge-danger-text);
    }

    .dashboard-metric-card.card-completed:hover .dashboard-metric-icon,
    .dashboard-metric-card.card-completed.active .dashboard-metric-icon {
        color: var(--color-badge-success-text);
    }

    .selected-row td {
        background-color: rgba(0, 113, 227, 0.05) !important;
    }

    .floating-action-bar {
        position: fixed;
        bottom: -80px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(29, 29, 31, 0.92);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 40px;
        padding: 10px 24px;
        z-index: 1050;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35);
        transition: bottom 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
        opacity: 0;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .floating-action-bar.show {
        bottom: 30px;
        opacity: 1;
    }

    .floating-action-info {
        color: #ffffff;
        font-size: 0.95rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .floating-action-info .count-badge {
        background: #0071e3;
        color: #ffffff;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .floating-action-divider {
        width: 1px;
        height: 24px;
        background: rgba(255, 255, 255, 0.2);
    }

    .floating-btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 30px;
        padding: 8px 18px;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none !important;
        white-space: nowrap;
        transition: background-color 0.2s, border-color 0.2s, transform 0.2s;
    }

    .floating-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.3);
        color: #ffffff;
        transform: scale(1.02);
    }

    .floating-btn-secondary:active {
        transform: scale(0.98);
    }

    .floating-btn-primary {
        background: #0071e3;
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 30px;
        padding: 8px 18px;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none !important;
        cursor: pointer;
        white-space: nowrap;
        transition: background-color 0.2s, border-color 0.2s, transform 0.2s;
    }

    .floating-btn-primary:hover {
        background: #0077ed;
        border-color: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        transform: scale(1.02);
    }

    .floating-btn-primary:active {
        transform: scale(0.98);
    }

    .floating-action-bar button {
        border: none;
        outline: none;
    }

    .floating-action-bar .dropdown-menu {
        border-radius: 12px !important;
        background: rgba(30, 30, 30, 0.96) !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .floating-action-bar .dropdown-item {
        color: rgba(255, 255, 255, 0.8) !important;
        font-size: 0.85rem;
        transition: background-color 0.2s, color 0.2s;
    }

    .floating-action-bar .dropdown-item:hover {
        background-color: rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
    }

    #tblActivityLogs tbody td.actions-cell,
    #tblActivityLogs tbody td:not(.actions-cell):has(a:not([onclick]):not(.text-muted)) {
        cursor: pointer;
    }

    #tblActivityLogs tbody td:not(.actions-cell):has(a:not([onclick]):not(.text-muted)):hover a:not([onclick]):not(.text-muted) {
        color: var(--color-primary) !important;
    }

    /* Action Dropdown Style Experiment */
    .actions-cell {
        position: relative;
        width: 90px;
        min-width: 90px;
        text-align: center;
        padding-left: 4px !important;
        padding-right: 4px !important;
    }

    .action-style-original-wrapper,
    .action-style-glassmorphic-wrapper,
    .action-style-hover-inline-wrapper {
        display: none;
    }

    #tblActivityLogs.style-original .action-style-original-wrapper {
        display: block;
    }

    #tblActivityLogs.style-glassmorphic .action-style-glassmorphic-wrapper {
        display: block;
    }

    #tblActivityLogs.style-hover-inline .action-style-hover-inline-wrapper {
        display: flex;
        justify-content: center;
    }

    /* 1. Sleek Minimalist Dot Trigger Styling */
    .action-trigger-dot {
        background: transparent;
        border: none;
        color: var(--color-text-muted);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        transition: all 0.2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-trigger-dot:hover,
    .action-trigger-dot[aria-expanded="true"] {
        background-color: var(--color-secondary-bg);
        color: var(--color-text-base);
        transform: scale(1.05);
    }

    .action-trigger-dot:active {
        transform: scale(0.95);
    }

    /* 2. Glassmorphic Dropdown Styling */
    .glassmorphic-menu {
        background: rgba(255, 255, 255, 0.82) !important;
        backdrop-filter: blur(12px) saturate(180%) !important;
        -webkit-backdrop-filter: blur(12px) saturate(180%) !important;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        border-radius: 12px !important;
        padding: 6px !important;
        transform-origin: top right;
        animation: dropdownFadeIn 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes dropdownFadeIn {
        from {
            opacity: 0;
            transform: scale(0.96) translateY(-4px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .glassmorphic-menu .dropdown-item {
        border-radius: 8px !important;
        padding: 6px 12px !important;
        font-size: 0.85rem !important;
        font-weight: 500 !important;
        transition: background-color 0.15s ease, color 0.15s ease !important;
        color: var(--color-text-base) !important;
    }

    .glassmorphic-menu .dropdown-item:hover {
        background-color: var(--color-primary) !important;
        color: #ffffff !important;
    }

    /* Ensure standard Bootstrap icons inside dropdown inherit color changes */
    .glassmorphic-menu .dropdown-item:hover i {
        color: #ffffff !important;
    }

    /* 3. Hover-Revealed Inline Action Buttons Styling */
    .hover-actions-container {
        opacity: 0;
        transform: translateX(6px);
        transition: opacity 0.2s ease, transform 0.2s ease;
    }

    /* When the table row is hovered, show the actions */
    #tblActivityLogs tr:hover .hover-actions-container {
        opacity: 1;
        transform: translateX(0);
    }

    /* Also show them if select check is active or focus is inside */
    #tblActivityLogs tr:focus-within .hover-actions-container,
    #tblActivityLogs tr.selected-row .hover-actions-container {
        opacity: 1;
        transform: translateX(0);
    }

    .hover-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        color: var(--color-text-muted);
        background-color: var(--color-secondary-bg);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none !important;
        font-size: 0.75rem;
    }

    .hover-action-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .hover-action-btn:active {
        transform: scale(0.95);
    }

    .hover-action-btn.btn-view:hover {
        background-color: var(--color-badge-primary-bg) !important;
        color: var(--color-badge-primary-text) !important;
    }

    .hover-action-btn.btn-duplicate:hover {
        background-color: var(--color-badge-purple-bg) !important;
        color: var(--color-badge-purple-text) !important;
    }

    .hover-action-btn.btn-update:hover {
        background-color: var(--color-badge-warning-bg) !important;
        color: var(--color-badge-warning-text) !important;
    }

    /* Micro Segmented Control Styling */
    .micro-segmented-control {
        background-color: var(--color-secondary-bg);
        border-radius: 20px;
        padding: 3px;
        display: inline-flex;
        gap: 2px;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .micro-segmented-control button {
        border: none;
        background: transparent;
        padding: 4px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--color-text-secondary);
        border-radius: 15px;
        transition: all 0.2s ease;
        cursor: pointer;
        outline: none;
    }

    .micro-segmented-control button:hover {
        color: var(--color-text-base);
    }

    .micro-segmented-control button.active {
        background-color: var(--color-card-bg);
        color: var(--color-text-base);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    }

    .status-select-badge {
        display: inline-block;
        padding: 0.3em 1.6em 0.3em 0.6em !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        line-height: 1.25 !important;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        border-radius: 6px !important;
        cursor: pointer;
        width: auto !important;
        height: auto !important;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-repeat: no-repeat;
        background-position: right 0.45rem center;
        background-size: 8px 10px;
        transition: background-color 0.25s ease, border-color 0.25s ease, color 0.25s ease, box-shadow 0.25s ease;
    }

    .status-select-badge:focus {
        outline: none;
        box-shadow: 0 0 0 0.15rem rgba(0, 123, 255, 0.25) !important;
    }

    /* Specific theme styles for Status and Priority */
    .status-select-badge.bg-success {
        background-color: var(--color-badge-success-bg) !important;
        color: var(--color-badge-success-text) !important;
        border: 1px solid var(--color-badge-success-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%232E7D32' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    .status-select-badge.bg-danger {
        background-color: var(--color-badge-danger-bg) !important;
        color: var(--color-badge-danger-text) !important;
        border: 1px solid var(--color-badge-danger-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23C62828' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    .status-select-badge.bg-warning {
        background-color: var(--color-badge-warning-bg) !important;
        color: var(--color-badge-warning-text) !important;
        border: 1px solid var(--color-badge-warning-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23F57F17' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    .status-select-badge.bg-secondary {
        background-color: var(--color-badge-secondary-bg) !important;
        color: var(--color-badge-secondary-text) !important;
        border: 1px solid var(--color-badge-secondary-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2349494D' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    .status-select-badge.bg-info {
        background-color: var(--color-badge-primary-bg) !important;
        color: var(--color-badge-primary-text) !important;
        border: 1px solid var(--color-badge-primary-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%231565C0' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    /* Specific theme styles for Assignee users */
    .status-select-badge.bg-purple {
        background-color: var(--color-badge-purple-bg) !important;
        color: var(--color-badge-purple-text) !important;
        border: 1px solid var(--color-badge-purple-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%237B1FA2' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    .status-select-badge.bg-pink {
        background-color: var(--color-badge-pink-bg) !important;
        color: var(--color-badge-pink-text) !important;
        border: 1px solid var(--color-badge-pink-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23C2185B' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    .status-select-badge.bg-green {
        background-color: var(--color-badge-green-bg) !important;
        color: var(--color-badge-green-text) !important;
        border: 1px solid var(--color-badge-green-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%231B5E20' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    .status-select-badge.bg-blue {
        background-color: var(--color-badge-blue-bg) !important;
        color: var(--color-badge-blue-text) !important;
        border: 1px solid var(--color-badge-blue-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%230071E3' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    .status-select-badge.bg-orange {
        background-color: var(--color-badge-orange-bg) !important;
        color: var(--color-badge-orange-text) !important;
        border: 1px solid var(--color-badge-orange-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23E65100' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }
</style>

<!-- Custom Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
    <div id="toastNotification" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true"
        style="display: none; min-width: 280px; border-radius: 12px; backdrop-filter: blur(10px); box-shadow: 0 8px 32px rgba(0,0,0,0.15); transition: opacity 0.3s ease;">
        <div class="d-flex p-3 align-items-center">
            <div class="me-2" id="toastIcon">
                <!-- Icon will be set dynamically -->
            </div>
            <div class="toast-body flex-grow-1 fw-medium" id="toastMessage" style="font-size: 0.9rem;">
                <!-- Message will be set dynamically -->
            </div>
            <button type="button" class="btn-close ms-auto" style="box-shadow: none; opacity: 0.6;"
                onclick="$('#toastNotification').fadeOut(300);"></button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function getStatusGroup(statusId) {
        statusId = parseInt(statusId);
        if (statusId === 1 || statusId === 5) return 'pending';
        if (statusId === 2 || statusId === 3 || statusId === 4) return 'in_progress';
        if (statusId === 7) return 'blocked';
        if (statusId === 6) return 'completed';
        return null;
    }

    document.addEventListener('DOMContentLoaded', function () {
        $(document).ready(function () {
            $('.select2-multiple').select2({
                placeholder: "Select...",
                allowClear: true,
                width: '100%'
            });
            // Checkbox and Floating Action Bar Logic

            const table = document.getElementById('tblActivityLogs');
            const selectAllLogs = document.getElementById('selectAllLogs');
            const selectedCountBadge = document.getElementById('selectedCountBadge');
            const floatingActionBar = document.getElementById('floatingActionBar');
            const floatingSelectedCount = document.getElementById('floatingSelectedCount');
            const floatingBtnDeselect = document.getElementById('floatingBtnDeselect');

            // Action Style Switcher logic
            const actionStyleControl = document.getElementById('actionStyleControl');
            if (actionStyleControl && table) {
                const savedStyle = localStorage.getItem('tag_action_style') || 'glassmorphic';
                table.classList.remove('style-original', 'style-glassmorphic', 'style-hover-inline');
                table.classList.add(`style-${savedStyle}`);

                actionStyleControl.querySelectorAll('button').forEach(btn => {
                    if (btn.dataset.style === savedStyle) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });

                actionStyleControl.addEventListener('click', function (e) {
                    const btn = e.target.closest('button[data-style]');
                    if (!btn) return;

                    const selectedStyle = btn.dataset.style;
                    actionStyleControl.querySelectorAll('button').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    table.classList.remove('style-original', 'style-glassmorphic', 'style-hover-inline');
                    table.classList.add(`style-${selectedStyle}`);

                    localStorage.setItem('tag_action_style', selectedStyle);
                });
            }

            const updateSelectAllState = () => {
                if (!selectAllLogs || !table) return;
                const total = table.querySelectorAll('input[name="log_ids[]"]').length;
                const checked = table.querySelectorAll('input[name="log_ids[]"]:checked').length;

                selectAllLogs.checked = (total > 0 && checked === total);
                selectAllLogs.indeterminate = (checked > 0 && checked < total);
            };

            const toggleButton = () => {
                if (!table) return;
                const checkedCount = table.querySelectorAll('input[name="log_ids[]"]:checked').length;
                const anyChecked = checkedCount > 0;

                if (selectedCountBadge) {
                    selectedCountBadge.textContent = `${checkedCount} selected`;
                    selectedCountBadge.style.display = anyChecked ? 'inline-block' : 'none';
                }

                if (floatingActionBar) {
                    if (anyChecked) {
                        if (floatingSelectedCount) {
                            floatingSelectedCount.textContent = checkedCount;
                        }
                        floatingActionBar.classList.add('show');
                    } else {
                        floatingActionBar.classList.remove('show');
                    }
                }

                // Highlight selected rows
                table.querySelectorAll('input[name="log_ids[]"]').forEach(cb => {
                    const row = cb.closest('tr');
                    if (row) {
                        if (cb.checked) {
                            row.classList.add('selected-row');
                        } else {
                            row.classList.remove('selected-row');
                        }
                    }
                });
            };

            if (table) {
                table.addEventListener('change', function (e) {
                    if (e.target && e.target.name === 'log_ids[]') {
                        toggleButton();
                        updateSelectAllState();
                    }
                });

                // Prevent text selection when shift-clicking the table rows
                table.addEventListener('mousedown', function (e) {
                    if (e.shiftKey) {
                        e.preventDefault();
                    }
                });

                let lastChecked = null;

                table.addEventListener('click', function (e) {
                    const isCheckbox = e.target.matches('input[name="log_ids[]"]');

                    const td = e.target.closest('td');
                    if (td) {
                        const clickedLinkOrBtn = e.target.closest('a, button, select, label, input');

                        if (clickedLinkOrBtn && clickedLinkOrBtn !== e.target.closest('input[name="log_ids[]"]')) {
                            return;
                        }

                        // Redirect Actions Column cell clicks to target buttons
                        if (td.classList.contains('actions-cell')) {
                            if (!clickedLinkOrBtn) {
                                let activeStyle = 'glassmorphic';
                                if (table.classList.contains('style-original')) {
                                    activeStyle = 'original';
                                } else if (table.classList.contains('style-hover-inline')) {
                                    activeStyle = 'hover-inline';
                                }

                                const wrapper = td.querySelector(`.action-style-${activeStyle}-wrapper`);
                                if (wrapper) {
                                    const toggleBtn = wrapper.querySelector('[data-bs-toggle="dropdown"]');
                                    if (toggleBtn) {
                                        toggleBtn.click();
                                    } else {
                                        const viewBtn = wrapper.querySelector('.hover-action-btn.btn-view');
                                        if (viewBtn) viewBtn.click();
                                    }
                                }
                            }
                            return;
                        }

                        // Redirect clicks inside cells containing primary links (Jira Ticket ID, etc.)
                        const primaryLink = td.querySelector('a:not([onclick]):not(.text-muted)');
                        if (primaryLink) {
                            if (!clickedLinkOrBtn) {
                                if (primaryLink.target === '_blank') {
                                    window.open(primaryLink.href, '_blank');
                                } else {
                                    primaryLink.click();
                                }
                            }
                            return;
                        }

                        // Redirect clicks inside cells containing select dropdowns (Assignee, Status, Priority)
                        const selectEl = td.querySelector('select');
                        if (selectEl) {
                            if (!clickedLinkOrBtn) {
                                selectEl.focus();
                            }
                            return;
                        }
                    }

                    const row = e.target.closest('tr');
                    if (row && row.parentNode.tagName === 'TBODY') {
                        const cb = row.querySelector('input[name="log_ids[]"]');
                        if (cb) {
                            if (!isCheckbox) {
                                cb.checked = !cb.checked;
                            }

                            if (e.shiftKey && lastChecked && lastChecked !== cb) {
                                window.getSelection().removeAllRanges();

                                const checkboxes = Array.from(table.querySelectorAll('input[name="log_ids[]"]'));
                                const start = checkboxes.indexOf(cb);
                                const end = checkboxes.indexOf(lastChecked);

                                if (start !== -1 && end !== -1) {
                                    const rangeStart = Math.min(start, end);
                                    const rangeEnd = Math.max(start, end);

                                    for (let i = rangeStart; i <= rangeEnd; i++) {
                                        checkboxes[i].checked = cb.checked;
                                    }
                                }
                            }

                            lastChecked = cb;

                            cb.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                });
            }

            if (selectAllLogs) {
                selectAllLogs.addEventListener('change', function () {
                    if (!table) return;
                    table.querySelectorAll('input[name="log_ids[]"]').forEach(cb => {
                        cb.checked = selectAllLogs.checked;
                    });
                    lastChecked = null;
                    toggleButton();
                });
            }

            if (floatingBtnDeselect) {
                floatingBtnDeselect.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (!table) return;
                    table.querySelectorAll('input[name="log_ids[]"]').forEach(cb => {
                        cb.checked = false;
                    });
                    if (selectAllLogs) {
                        selectAllLogs.checked = false;
                        selectAllLogs.indeterminate = false;
                    }
                    toggleButton();
                    updateSelectAllState();
                });
            }

            // Helper to get selected log IDs
            function getSelectedLogIds() {
                if (!table) return [];
                const checkedCbs = table.querySelectorAll('input[name="log_ids[]"]:checked');
                return Array.from(checkedCbs).map(cb => cb.value);
            }

            // Helper to run bulk AJAX operation
            function runBulkOperation(url, data, successCallback) {
                const logIds = getSelectedLogIds();
                if (logIds.length === 0) {
                    showToast('error', 'No tasks selected.');
                    return;
                }

                // Show loading state
                const originalOpacity = $('#floatingActionBar').css('opacity');
                $('#floatingActionBar').css('opacity', '0.6');
                $('#floatingActionBar button, #floatingActionBar a').prop('disabled', true).addClass('disabled');

                const csrfToken = $('input[name="csrf_test_name"]').val();
                const postData = Object.assign({
                    csrf_test_name: csrfToken,
                    log_ids: logIds
                }, data);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: postData,
                    dataType: 'json',
                    success: function (response) {
                        // Restore state
                        $('#floatingActionBar').css('opacity', originalOpacity);
                        $('#floatingActionBar button, #floatingActionBar a').prop('disabled', false).removeClass('disabled');

                        if (response.csrf_hash) {
                            $('input[name="csrf_test_name"]').val(response.csrf_hash);
                        }

                        if (response.success) {
                            showToast('success', response.message);
                            if (successCallback) {
                                successCallback(response);
                            } else {
                                setTimeout(function () {
                                    window.location.reload();
                                }, 1000);
                            }
                        } else {
                            showToast('error', response.message || 'Bulk update failed.');
                        }
                    },
                    error: function () {
                        // Restore state
                        $('#floatingActionBar').css('opacity', originalOpacity);
                        $('#floatingActionBar button, #floatingActionBar a').prop('disabled', false).removeClass('disabled');
                        showToast('error', 'An error occurred during bulk update.');
                    }
                });
            }

            // Mark Complete handler
            const floatingBtnComplete = document.getElementById('floatingBtnComplete');
            if (floatingBtnComplete) {
                floatingBtnComplete.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to mark all selected tasks as Completed?')) {
                        runBulkOperation('<?= site_url("tasks/bulk-status") ?>', {
                            status: <?= Constants::ACTIVITY_LOGS_STATUS_COMPLETED ?>
                        });
                    }
                });
            }

            // Add to Project handlers
            $(document).on('click', '.floating-action-project-item', function (e) {
                e.preventDefault();
                const project = $(this).data('project');
                if (project) {
                    runBulkOperation('<?= site_url("tasks/bulk-project") ?>', {
                        project: project
                    });
                }
            });

            $(document).on('click', '.floating-action-project-new', function (e) {
                e.preventDefault();
                const project = prompt("Enter new project name:");
                if (project && project.trim() !== '') {
                    runBulkOperation('<?= site_url("tasks/bulk-project") ?>', {
                        project: project.trim()
                    });
                }
            });

            // Change Assignee handlers
            $(document).on('click', '.floating-action-assignee-item', function (e) {
                e.preventDefault();
                const assigneeId = $(this).data('assignee-id');
                runBulkOperation('<?= site_url("tasks/bulk-assignee") ?>', {
                    assignee: assigneeId
                });
            });

            let previousValue = '';

            // Cache previous status value when dropdown is focused
            $('.status-select-badge').on('focus', function () {
                previousValue = $(this).val();
            }).on('change', function () {
                const dropdown = $(this);
                const logId = dropdown.data('log-id');
                const newValue = dropdown.val();
                const csrfToken = $('input[name="csrf_test_name"]').val();

                let url = '';
                let postData = { csrf_test_name: csrfToken };

                if (dropdown.hasClass('assignee-select')) {
                    url = '<?= site_url("tasks/update-assignee") ?>/' + logId;
                    postData.assignee = newValue;
                } else if (dropdown.hasClass('priority-select')) {
                    url = '<?= site_url("tasks/update-priority") ?>/' + logId;
                    postData.priority = newValue;
                } else {
                    url = '<?= site_url("tasks/update-status") ?>/' + logId;
                    postData.status = newValue;
                }

                // Low opacity to indicate loading state
                dropdown.css('opacity', '0.6');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: postData,
                    dataType: 'json',
                    success: function (response) {
                        dropdown.css('opacity', '1');
                        if (response.success) {
                            // Clear old status badge classes and apply new one
                            dropdown.removeClass('bg-secondary bg-warning bg-info bg-success bg-danger bg-purple bg-pink bg-green bg-blue bg-orange text-white text-dark');
                            dropdown.addClass(response.status_class);

                            // Success toast notification
                            showToast('success', response.message);

                            // Dynamic counts update
                            if (!dropdown.hasClass('assignee-select') && !dropdown.hasClass('priority-select')) {
                                const oldGroup = getStatusGroup(previousValue);
                                const newGroup = getStatusGroup(newValue);
                                if (oldGroup && newGroup && oldGroup !== newGroup) {
                                    const oldCardValEl = document.querySelector(`.dashboard-metric-card.card-${oldGroup} .dashboard-metric-value`);
                                    const newCardValEl = document.querySelector(`.dashboard-metric-card.card-${newGroup} .dashboard-metric-value`);
                                    if (oldCardValEl) {
                                        const oldVal = parseInt(oldCardValEl.textContent) || 0;
                                        oldCardValEl.textContent = Math.max(0, oldVal - 1);
                                    }
                                    if (newCardValEl) {
                                        const newVal = parseInt(newCardValEl.textContent) || 0;
                                        newCardValEl.textContent = newVal + 1;
                                    }
                                }
                            }

                            previousValue = newValue; // update cached previous value
                        } else {
                            // Revert to previous value
                            dropdown.val(previousValue);
                            showToast('error', response.message || 'Failed to update.');
                        }

                        // Update CSRF token in the document
                        if (response.csrf_hash) {
                            $('input[name="csrf_test_name"]').val(response.csrf_hash);
                        }
                    },
                    error: function (xhr, status, error) {
                        dropdown.css('opacity', '1');
                        dropdown.val(previousValue);
                        showToast('error', 'An error occurred while updating.');
                    }
                });
            });

            // Reusable Premium Toast Notification function
            function showToast(type, message) {
                const toast = $('#toastNotification');
                const messageEl = $('#toastMessage');
                const iconEl = $('#toastIcon');

                messageEl.text(message);

                toast.removeClass('bg-success bg-danger bg-warning text-dark text-white border-0');

                if (type === 'success') {
                    toast.css({
                        'background-color': 'var(--color-alert-success-bg)',
                        'color': 'var(--color-alert-success-text)',
                        'border': '1px solid rgba(29, 138, 68, 0.2)'
                    });
                    iconEl.html('<i class="bi bi-check-circle-fill fs-5" style="color: var(--color-alert-success-text);"></i>');
                } else if (type === 'error') {
                    toast.css({
                        'background-color': 'var(--color-alert-danger-bg)',
                        'color': 'var(--color-alert-danger-text)',
                        'border': '1px solid rgba(222, 53, 53, 0.2)'
                    });
                    iconEl.html('<i class="bi bi-exclamation-triangle-fill fs-5" style="color: var(--color-alert-danger-text);"></i>');
                } else {
                    toast.css({
                        'background-color': 'var(--color-alert-warning-bg)',
                        'color': 'var(--color-alert-warning-text)',
                        'border': '1px solid rgba(180, 107, 0, 0.2)'
                    });
                    iconEl.html('<i class="bi bi-exclamation-circle-fill fs-5" style="color: var(--color-alert-warning-text);"></i>');
                }

                toast.fadeIn(300);

                if (window.toastTimeout) {
                    clearTimeout(window.toastTimeout);
                }
                window.toastTimeout = setTimeout(function () {
                    toast.fadeOut(300);
                }, 4000);
            }
        });
    });
</script>
<?= $this->endSection() ?>