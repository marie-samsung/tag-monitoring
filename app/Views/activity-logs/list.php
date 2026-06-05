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
?>

<?= csrf_field() ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-3">
        <div>
            <h2 class="mb-0 fw-bold">Activity Logs</h2>
            <p class="text-secondary small mb-0">Record and track project management activities, support tasks, and campaign tag updates.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="apple-btn-secondary btn-sm px-3" data-bs-toggle="collapse" data-bs-target="#sizeLegendCollapse" aria-expanded="false" aria-controls="sizeLegendCollapse">
                <i class="bi bi-info-circle me-1"></i> Size Legend
            </button>
            <a href="<?= site_url('activity-logs/export?' . http_build_query($_GET)) ?>" class="apple-btn-secondary btn-sm px-3">Export as CSV</a>
            <a href="<?= site_url('activity-logs/create') ?>" title="Create a new log" class="apple-btn-primary btn-sm px-3">Log Activity</a>
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
    <div class="apple-control-bar mb-4">
        <form method="get" class="row g-2 w-100 m-0">
            <div class="col">
                <input
                        type="text"
                        name="keyword"
                        class="form-control form-select-xs"
                        placeholder="Search..."
                        value="<?= esc($_GET['keyword'] ?? '') ?>"
                >
            </div>

            <div class="col">
                <select name="subsidiary_id" class="form-select form-select-xs">
                    <option value="">All Subsidiaries</option>
                    <?php foreach (Constants::ACTIVITY_LOG_SUBSIDIARIES as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                                <?= old('subsidiary_id', $filters['subsidiary_id'] ?? '') == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <select name="project_id" class="form-select form-select-xs">
                    <option value="">All Projects</option>
                    <?php foreach (Constants::PROJECTS as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                                <?= old('project_id', $filters['project_id'] ?? '') == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <select name="assignee" class="form-select form-select-xs">
                    <option value="">All Assignee</option>
                    <?php foreach (Constants::USERS as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                                <?= old('assignee', $filters['assignee'] ?? '') == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <select name="status" class="form-select form-select-xs">
                    <option value="">All Status</option>
                    <?php foreach (Constants::ACTIVITY_LOGS_STATUS as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                                <?= old('status', $filters['status'] ?? null) == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <select name="priority" class="form-select form-select-xs">
                    <option value="">All Priorities</option>
                    <?php foreach (Constants::PRIORITIES as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                                <?= old('priority', $filters['priority'] ?? null) == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <select name="type" class="form-select form-select-xs">
                    <option value="">All Types</option>
                    <?php foreach (Constants::ACTIVITY_TYPES as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                                <?= old('type', $filters['type'] ?? '') == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <input type="hidden" name="sort_by" value="<?= esc($filters['sort_by'] ?? 'created_at') ?>">
            <input type="hidden" name="sort_dir" value="<?= esc($filters['sort_dir'] ?? 'desc') ?>">

            <div class="col-md-2 d-flex align-items-center gap-2">
                <button class="apple-btn-primary btn-sm w-100">Filter</button>
                <a href="<?= site_url('activity-logs') ?>" class="apple-btn-secondary btn-sm w-100 text-center text-decoration-none">Clear</a>
            </div>

        </form>
    </div>
    <!-- #endregion -->
    </div>
</div>

<!-- #region | LOGS TABLE -->
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
                <th class="form-label-sm fw-semibold">
                    <a href="<?= getSortUrl('assignee', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                        Assignee <?= getSortIcon('assignee', $filters['sort_by'] ?? 'created_at', $filters['sort_dir'] ?? 'desc') ?>
                    </a>
                </th>
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
                    <td>
                        <span class="badge <?= assignee_class($log['assignee']) ?>">
                            <?= $assignee ?>
                        </span>
                    </td>
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
                        <a href="<?= site_url('activity-logs/view/' . $log['id']) ?>" class="apple-btn-secondary btn-sm">Details</a>
                        <a href="<?= site_url('activity-logs/update/' . $log['id']) ?>" class="apple-btn-primary btn-sm">Update</a>
                    </td>
                </tr>
            <?php endforeach; ?>
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
