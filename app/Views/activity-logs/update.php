<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= $log['title'] ?> : Update
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mb-2">
    <h7 class="text-danger fw-semibold">Update Activity Log</h7>
    <h3><?= $log['title'] ?></h3>
</div>

<?php
    use App\Models\Constants;
    $isAdmin = (session()->get('user_role') === Constants::USER_ROLE_ADMIN);
?>

<?php if (isset($validation)): ?>
    <div class="alert alert-danger">
        <?= $validation->listErrors() ?>
    </div>
<?php endif; ?>

<form action="<?= site_url("activity-logs/update/{$log['id']}") ?>" method="post">
    <?= csrf_field() ?>

    <div class="container">
        <div class="card p-3">
            <div class="row mb-2">
                <div class="<?= $isAdmin ? 'col' : 'col-10' ?>">
                    <label for="title" class="form-label-xs text-primary">Title</label>
                    <input type="text" name="title" id="title"
                        class="form-control"
                        value="<?= $log['title'] ?>"
                        required>
                </div>

                <?php if (!$isAdmin): ?>
                <div class="col-2">
                    <label class="form-label-xs text-primary">Assignee</label>
                    <select name="assignee" class="form-select" required>
                        <?php foreach (Constants::USERS as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                    <?= ($log['assignee'] == $value) ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
            </div>

            <div class="row mb-2">
                <div class="col">
                    <label for="description" class="form-label-xs text-primary">Description</label>
                    <textarea name="description" id="description"
                        class="form-control"
                        rows="4"><?= $log['description'] ?></textarea>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col">
                    <label class="form-label-xs text-primary">Subsidiary</label>
                    <select name="subsidiary_id" class="form-select searchable-select" required>
                        <?php foreach (Constants::ACTIVITY_LOG_SUBSIDIARIES as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= ($log['subsidiary_id'] == $value) ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col">
                    <label class="form-label-xs text-primary">Project</label>
                    <select name="project_id" class="form-select" required>
                        <?php foreach (Constants::PROJECTS as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= ($log['project_id'] == $value) ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col">
                    <label class="form-label-xs text-primary">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="">-Select-</option>
                        <?php foreach (Constants::ACTIVITY_TYPES as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= ($log['type'] == $value) ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col">
                    <label for="ticket_id" class="form-label-xs text-primary">Ticket ID</label>
                    <input type="text" name="ticket_id" id="ticket_id"
                        class="form-control"
                        value="<?= $log['ticket_id'] ?>">
                </div>

                <div class="col">
                    <label class="form-label-xs text-primary">Size</label>
                    <select name="size" class="form-select" required>
                        <option value="">-Select-</option>
                        <?php foreach (Constants::ACTIVITY_SIZES as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= ($log['size'] == $value) ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col">
                    <label class="form-label-xs text-primary">Priority</label>
                    <select name="priority" class="form-select" required>
                        <option value="">-Select-</option>
                        <?php foreach (Constants::PRIORITIES as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                    <?= ($log['priority'] == $value) ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col">
                    <label class="form-label-xs text-primary">Status</label>
                    <select name="status" class="form-select" required>
                        <?php foreach (Constants::ACTIVITY_LOGS_STATUS as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                    <?= ($log['status'] == $value) ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col">
                    <label for="requested_by" class="form-label-xs text-primary">Requested By</label>
                    <input type="text" name="requested_by" id="requested_by"
                        class="form-control"
                        value="<?= old('requested_by', $log['requested_by'] ?? '') ?>">
                </div>

                <div class="col">
                    <label for="requested_date" class="form-label-xs text-primary">Requested Date</label>
                    <input type="date" name="requested_date" id="requested_date"
                        class="form-control"
                        value="<?= !empty($log['requested_date']) ? date('Y-m-d', strtotime($log['requested_date'])) : '' ?>">
                </div>
            </div>
        </div>

        <!-- #region | CAMPAIGN DETAILS -->
        <h5 class="mt-3">Campaign Details</h5>
        <div class="card p-3">
            <div class="row mb-2">
                <div class="col">
                    <label class="form-label-xs text-primary">Department</label>
                    <select name="department_id" class="form-select">
                        <option value="">-Select-</option>
                        <?php foreach (Constants::DWF_DEPARTMENTS as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                    <?= ($log['department_id'] == $value) ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col">
                    <label class="form-label-xs text-primary">Campaign Status</label>
                    <select name="campaign_status" class="form-select">
                        <option value="">-Select-</option>
                        <?php foreach (Constants::CAMPAIGN_STATUS as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= ($log['campaign_status'] == $value) ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col">
                    <label for="campaign_id" class="form-label-xs text-primary">Campaign ID</label>
                    <input type="text" name="campaign_id" id="campaign_id"
                        class="form-control"
                        value="<?= old('campaign_id', $log['campaign_id'] ?? '') ?>">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="<?= site_url('activity-logs') ?>" title="Cancel" class="btn btn-secondary btn-sm">Cancel</a>
            <button type="submit" class="btn btn-success btn-sm">Update</button>
        </div>
        <!-- #endregion -->
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('.searchable-select').select2({
            width: '100%',
        });

        $(document).ready(function() {
            $('#description').summernote({
                height: 300,
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
