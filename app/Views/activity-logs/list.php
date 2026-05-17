<?php

use App\Models\SubsidiariesModel;

?>
<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Activity Logs
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    use App\Models\Constants;
    helper('custom_functions');
?>

<?= csrf_field() ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Activity Logs</h2>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('activity-logs/create') ?>" target="_blank" title="Create a new log" class="btn btn-success btn-sm ms-auto px-2">Add Log</a>
            <!-- <a href="<?= site_url('tags/export?' . http_build_query($_GET)) ?>" class="btn btn-sm btn-warning ms-auto px-2">Export as CSV</a> -->
        </div>
    </div>

    <!-- #region | SEARCH FILTER -->
    <div>
        <form method="get" class="row g-3 mb-3">
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

            <div class="col-md-2 d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-primary w-100">Filter</button>
                <a href="<?= site_url('tags') ?>" class="btn btn-sm btn-secondary w-100">Clear</a>
            </div>

        </form>
    </div>
    <!-- #endregion -->
</div>

<!-- #region | LOGS TABLE -->
<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-active">
            <tr>
                <th class="form-label-sm fw-semibold">ID</th>
                <th class="form-label-sm fw-semibold">Subsidiary</th>
                <th class="form-label-sm fw-semibold">Project</th>
                <th class="form-label-sm fw-semibold">Title</th>
                <th class="form-label-sm fw-semibold">Assignee</th>
                <th class="form-label-sm fw-semibold">Status</th>
                <th class="form-label-sm fw-semibold">Type</th>
                <th class="form-label-sm fw-semibold">Size</th>
                <th class="form-label-sm fw-semibold">Created At</th>
                <th class="form-label-sm fw-semibold">Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= $log['id'] ?></td>
                    <td><?= Constants::ACTIVITY_LOG_SUBSIDIARIES[$log['subsidiary_id']] ?? 'Unknown' ?></td>
                    <td><?= Constants::PROJECTS[$log['project_id']] ?? 'Unknown' ?></td>
                    <td><?= $log['title'] ?></td>
                    <td><?= Constants::USERS[$log['assignee']] ?? 'Unknown' ?></td>
                    <td><?= Constants::ACTIVITY_LOGS_STATUS[$log['status']] ?? 'Unknown' ?></td>
                    <td><?= Constants::ACTIVITY_TYPES[$log['type']] ?? 'Unknown' ?></td>
                    <td><?= Constants::ACTIVITY_SIZES[$log['size']] ?? 'Unknown' ?></td>
                    <td><?= $log['created_at'] ?></td>
                    <td>
                        <a href="<?= site_url('activity-logs/view/' . $log['id']) ?>" class="btn btn-sm btn-secondary">Details</a>
                        <a href="<?= site_url('activity-logs/update/' . $log['id']) ?>" class="btn btn-sm btn-primary">Update</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- #endregion -->

<?= $this->endSection() ?>
