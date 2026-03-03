<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<?php 
    use App\Models\Constants;
    use App\Models\TagsModel;

    $tagModel = new TagsModel();
    $tag = $tagModel->find($tag_id);
?>

<div class="mb-2">
    <h7><mark>Add JIRA Entry</mark></h7>
    <h3><?= $tag['name'] ?></h3>    
</div>

<?php if (isset($validation)): ?>
    <div class="alert alert-danger">
        <?= $validation->listErrors() ?>
    </div>
<?php endif; ?>

<form action="<?= site_url('tags/create') ?>" method="post">
    <?= csrf_field() ?>

    <div class="container card p-3">
        <div class="row mb-2">
            <div class="col">
                <label for="name" class="form-label-xs">Tag Name</label>
                <input type="text" name="name" id="name"
                    class="form-control"
                    value="<?= old('name', $old['name'] ?? '') ?>"
                    required>
                <small class="form-label-xs text-muted">
                    Tag should start with LC-
                </small>
            </div>
        </div>
        
        <div class="row mb-2">
            <div class="col">
                <label for="conditions" class="form-label-xs">Conditions</label>
                <input type="text" name="conditions" id="conditions"
                    class="form-control"
                    value="<?= old('conditions', $old['conditions'] ?? '') ?>"
                    required>
                <small class="form-label-xs text-muted">
                    e.g. (.*)
                </small>
            </div>

            <div class="col">
                <label class="form-label-xs">Status</label>
                <select name="status" class="form-select" required>
                    <?php foreach (Constants::STATUS as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('status', $tag['status'] ?? 1) == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label class="form-label-xs" for="al_end_date">Launch Expiry Date</label>
                <input type="date"
                    name="al_end_date"
                    id="al_end_date"
                    class="form-control"
                    value="<?= esc(old('al_end_date', $tag['al_end_date'] ?? '')) ?>"
                    required>
            </div>

            <div class="col">
                <label for="ref_link" class="form-label-xs">Launch Link</label>
                <input type="text" name="ref_link" id="ref_link"
                    class="form-control"
                    value="<?= old('ref_link', $old['ref_link'] ?? '') ?>"
                    required>
                <small class="form-label-xs text-muted">
                    https://
                </small>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="<?= site_url('tags') ?>" title="Cancel" class="btn btn-secondary btn-sm">Cancel</a>
            <button type="submit" class="btn btn-success btn-sm">Submit</button>
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
