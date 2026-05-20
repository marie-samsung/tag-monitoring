<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<?php 
    use App\Models\Constants;
    use App\Models\TagsModel;

    $tagModel = new TagsModel();
    $tag = $tagModel->find($tag_id);
?>

<div class="container mb-2">
    <h7 class="text-danger fw-semibold">Add JIRA Entry</h7>
    <h3><?= $tag['name'] ?></h3>    
</div>

<?php if (isset($validation)): ?>
    <div class="alert alert-danger">
        <?= $validation->listErrors() ?>
    </div>
<?php endif; ?>

<form action="<?= site_url('jira/add-entry?tag_id='.$tag['id']) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="container card p-3">
        <div class="row mb-2">
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
                    <?php foreach (Constants::JIRA_TICKET_TYPES as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('type', $tag['type'] ?? 1) == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- <div class="col">
                <label for="lp_path" class="form-label-xs">Launch Publishing File Path</label>
                <br />
                <input type="file" name="lp_path" accept=".xls,.xlsx">
            </div> -->
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
                ><?= esc(old('comments', $tag['comments'] ?? '')) ?></textarea>
            </div>
        </div>

        <input type="hidden" name="tag_id" value="<?= esc($tag['id'] ?? '') ?>">

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
