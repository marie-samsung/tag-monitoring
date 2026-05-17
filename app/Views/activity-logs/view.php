<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= $log['title'] ?> : Details
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    use App\Models\Constants;
    helper('custom_functions');
?>

<h3 class="container"><?= esc($log['title']) ?></h3>

<div class="container d-flex my-3 gap-3">
    <!-- #region | LOG DETAILS -->
    <div class="w-50 p-4 card">
        <div class="mb-2">
            <label class="form-label-xs text-primary">Title</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($log['title'] ?: '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Description</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= nl2br(esc($log['description'] ?: '—')) ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Subsidiary</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc(Constants::ACTIVITY_LOG_SUBSIDIARIES[$log['subsidiary_id']] ?? '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Project</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc(Constants::PROJECTS[$log['project_id']] ?? '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Assignee</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc(Constants::USERS[$log['assignee']] ?? '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Status</label>
            <div class="form-control-plaintext p-0 ms-2">
                <span class="badge bg-secondary">
                    <?= esc(Constants::ACTIVITY_LOGS_STATUS[$log['status']] ?? 'Unknown') ?>
                </span>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Ticket ID</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($log['ticket_id'] ?: '—') ?>
            </div>
        </div>

        <label class="form-label m-0 fw-semibold mt-2">Request Details</label>
        <div class="mb-2">
            <label class="form-label-xs text-primary">Requested By</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($log['requested_by'] ?: '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Requested Date</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($log['requested_date'] ?: '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Finished Date</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($log['finished_date'] ?: '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Created At</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($log['created_at'] ?: '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Updated At</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($log['updated_at'] ?: '—') ?>
            </div>
        </div>

        <label class="form-label m-0 fw-semibold mt-2">Campaign Details</label>
        <div class="mb-2">
            <label class="form-label-xs text-primary">Department</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc(Constants::DWF_DEPARTMENTS[$log['department_id']] ?? '—') ?>
            </div>
        </div>
        
        <div class="mb-2">
            <label class="form-label-xs text-primary">Campaign Status</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc(Constants::CAMPAIGN_STATUS[$log['campaign_status']] ?? '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Campaign ID</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($log['campaign_id'] ?: '—') ?>
            </div>
        </div>

        <hr>

        <div class="d-flex align-items-center gap-1">
            <a href="<?= site_url('activity-logs') ?>" class="btn btn-secondary btn-sm px-2">Back</a>
            <a href="<?= site_url('activity-logs/update/' . $log['id']) ?>" class="btn btn-primary btn-sm">Edit</a>
        </div>
    </div>
    <!-- #endregion -->
</div>

<?= $this->endSection() ?>
