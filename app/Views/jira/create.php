<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<?php
    use App\Models\Constants;
    use App\Models\TagsModel;
?>

<div class="container mb-2 d-flex justify-content-between align-items-center">
    <h3>Add a JIRA Ticket</h3>
    <a href="<?= Constants::JIRA_CREATE_ISSUE ?>" title="Create Issue in JIRA" class="apple-btn-secondary btn-sm" target="_blank">
        Create Ticket on JIRA
    </a>
</div>

<?php if (isset($validation)): ?>
    <div class="alert alert-danger">
        <?= $validation->listErrors() ?>
    </div>
<?php endif; ?>

<form action="<?= site_url('jira/create') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="container card p-3">
        <div class="row mb-2">
            <div class="col">
                <label class="form-label-xs text-primary">Subsidiary</label>
                <select name="subsidiary_id" class="form-select" required>
                    <?php foreach (Constants::SUBSIDIARIES as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('subsidiary_id', $tag['subsidiary_id'] ?? 1) == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col mb-2">
                <label for="ticket_id" class="form-label-xs text-primary">ID</label>
                <input type="text" name="ticket_id" id="ticket_id"
                    class="form-control"
                    value="<?= old('ticket_id', $old['ticket_id'] ?? '') ?>"
                    required>
                <small class="form-label-xs text-muted">
                    Ticket ID should start with GTA-
                </small>
            </div>

            <div class="col">
                <label class="form-label-xs text-primary">Type</label>
                <select name="type" class="form-select" required>
                    <?php foreach (Constants::JIRA_CREATE_TICKET_TYPES as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('type', $tag['type'] ?? 1) == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label class="form-label-xs text-primary" for="comments">Notes</label>
                <textarea
                    name="comments"
                    id="comments"
                    class="form-control"
                    rows="4"
                    placeholder="Enter comments..."
                    required
                ><?= esc(old('comments', $tag['comments'] ?? '')) ?></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="<?= site_url('tags') ?>" title="Cancel" class="apple-btn-secondary btn-sm">Cancel</a>
            <button type="submit" class="apple-btn-primary btn-sm">Create</button>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('.searchable-select').select2({
            width: '100%',
        });
    });
</script>

<?= $this->endSection() ?>
