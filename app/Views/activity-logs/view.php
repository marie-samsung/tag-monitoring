<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= $log['title'] ?> : Details
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    use App\Models\Constants;
    helper('custom_functions');

    $subsidiary = Constants::ACTIVITY_LOG_SUBSIDIARIES[$log['subsidiary_id']] ?? null;
    $status = Constants::ACTIVITY_LOGS_STATUS[$log['status']] ?? 'Unknown';
    $type = Constants::ACTIVITY_TYPES[$log['type']] ?? 'Unknown';
    $project = Constants::PROJECTS[$log['project_id']] ?? 'Unknown';
    $assignee = Constants::USERS[$log['assignee']] ?? Constants::USER_SYSTEM_LABEL;
    $size = Constants::ACTIVITY_SIZES[$log['size']] ?? 'Unknown';
    $priority = Constants::PRIORITIES[$log['priority']] ?? 'Unknown';
    $isAdmin = (session()->get('user_role') === Constants::USER_ROLE_ADMIN);
?>

<div class="container-fluid">
    <h3><?= esc($log['title']) ?></h3>
    <div class="d-flex gap-2 justify-content-start align-items-center mt-2">
        <span class="badge" style="<?= random_bg_style($subsidiary) ?>" title="Subsidiary"><?= $subsidiary ?></span>
        <span class="badge" style="<?= random_bg_style($project) ?>" title="Project"><?= $project ?></span>
        <span class="badge" style="<?= random_bg_style($type) ?>" title="Activity Log Type"><?= $type ?></span>
        <?php if (!$isAdmin): ?>
        <span class="badge <?= assignee_class($log['assignee']) ?>" title="Assigned To"><?= $assignee ?></span>
        <?php endif; ?>
        <span class="badge <?= size_class($log['size']) ?>" title="Size"><?= $size ?></span>
        <span class="badge <?= priority_class($log['priority']) ?>" title="Priority"><?= $priority ?></span>
        <span class="badge <?= status_activity_logs_class($log['status']) ?>" title="Status"><?= $status ?></span>
    </div>
</div>

<div class="container-fluid d-flex my-3 gap-2 justify-content-evenly">
    <!-- #region | LOG DETAILS -->
    <div class="col p-4 card">
        <div class="mb-2">
            <label class="form-label-xs text-primary">Title</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($log['title'] ?: '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Description</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= $log['description'] ?: '—' ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Ticket ID</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?php if (!empty($log['ticket_id'])) : ?>
                    <a href="<?= Constants::JIRA_LINK . $log['ticket_id'] ?>" target="_blank">
                        <?= esc($log['ticket_id']) ?>
                    </a>
                <?php else: ?>
                    <?= '-' ?>
                <?php endif; ?>
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

    <!-- #region | COMMENTS & FILES -->
    <?php if (!$isAdmin): ?>
    <div class="col">
        <div class="card p-4">
            <h4>Updates</h4>

            <?php if (!empty($comments)) :
                 foreach ($comments as $comment) : ?>
                    <?php if ($comment['commented_by'] != Constants::USER_SYSTEM) : ?>
                         <div class="d-flex gap-2">
                             <span class="form-label-xs text-primary">
                                 <?= Constants::USERS[$comment['commented_by']] ?>
                             </span>
                             <span class="form-label-xs text-muted"><?= $comment['created_at'] ?></span>
                         </div>
                         <span class="ps-3 pt-3 rounded-2 bg-body-tertiary mb-3"><?= $comment['comment'] ?></span>
                    <?php else: ?>
                         <div class="d-flex gap-2">
                             <span class="form-label-xs text-info">
                                 <?= Constants::USER_SYSTEM_LABEL ?>
                             </span>
                             <span class="form-label-xs text-muted"><?= $comment['created_at'] ?></span>
                         </div>
                         <span class="p-3 rounded-2 bg-info-subtle mb-3"><?= $comment['comment'] ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <span class="alert alert-info">No update(s) for this log yet.</span>
            <?php endif; ?>

            <hr>
            <form action="<?= site_url('activity-logs/comment') ?>" method="post">
                <div>
                    <label for="comment" class="form-label-xs text-primary">Post an Update</label>
                    <textarea name="comment" id="comment"
                              class="form-control"
                              rows="4"><?= old('comment', $log['comment'] ?? '') ?></textarea>
                </div>
                <div class="d-flex gap-2 mt-2">
                    <input type="hidden" name="log_id" value="<?= $log['id'] ?>">
                    <?php if ($isAdmin): ?>
                        <input type="hidden" name="commented_by" value="<?= session()->get('user_id') ?>">
                    <?php else: ?>
                        <select name="commented_by" class="form-select" required>
                            <?php foreach (Constants::USERS as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= $log['assignee'] == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary btn-sm">Post</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="col">
        <div class="card p-4">
            <div class="d-flex gap-2">
                <h4>File(s)</h4>
                <i class="bi bi-info-circle-fill text-primary" title="Clicking the name will download the file"></i>
            </div>

            <?php if (!empty($files)) : ?>
                <?php foreach ($files as $file) : ?>
                    <p>
                        <a href="<?= '/tag-monitoring/download.php?file=' . rawurlencode($file['file_path']) ?>" download="<?= $file['file_name'] ?>" rel="noopener" class="row">
                            <?= $file['file_name'] ?>
                        </a>
                        <span class="row form-label-xs text-muted"><?= $file['created_at'] ?></span>
                    </p>
                <?php endforeach; ?>
            <?php else : ?>
                <span class="alert alert-info">No file available for this request.</span>
            <?php endif; ?>

            <form action="<?= site_url('activity-logs/upload') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="log_id" value="<?= esc($log['id']) ?>">
                <input type="hidden" name="created_by" value="<?= $isAdmin ? session()->get('user_id') : esc($log['assignee']) ?>">
                <div class="d-flex gap-3 justify-content-between align-items-center">
                    <div>
                        <label for="file_path" class="form-label-xs text-primary">Upload File</label>
                        <br />
                        <input type="file" name="file_path" required>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Confirm uploading of this file?');">
                            Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- #endregion -->
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $(document).ready(function() {
            $('#comment').summernote({
                height: 200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    // ['font', ['strikethrough', 'superscript', 'subscript']],
                    // ['fontsize', ['fontsize']],
                    // ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    // ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            var detailsHtml = `<?= $detailsHtml ?? null ?>`;
            $('#details').summernote('code', detailsHtml);
        });
    });
</script>

<?= $this->endSection() ?>
