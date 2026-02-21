<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<h2>Create New</h2>

<?php 
    use App\Models\Constants;
    use App\Models\PropertiesModel;

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->Options();
?>

<?php if (isset($validation)): ?>
    <div class="alert alert-danger">
        <?= $validation->listErrors() ?>
    </div>
<?php endif; ?>

<form action="<?= site_url('tags/create') ?>" method="post">
    <?= csrf_field() ?>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label-sm">Property</label>
            <select name="property_id" class="form-select">
                <?php foreach ($properties as $value => $label): ?>
                    <option value="<?= esc($value) ?>"
                        <?= old('property_id', $tag['property_id'] ?? '') == $value ? 'selected' : '' ?>>
                        <?= esc($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col">
            <label for="name" class="form-label-sm">Tag Name</label>
            <input type="text" name="name" id="name"
                class="form-control"
                value="<?= old('name', $old['name'] ?? '') ?>"
                placeholder="LC-">
        </div>
    </div>

    
    <div class="row mb-3">
        <div class="col">
            <label for="conditions" class="form-label-sm">Conditions</label>
            <input type="text" name="conditions" id="conditions"
                class="form-control"
                value="<?= old('conditions', $old['conditions'] ?? '') ?>"
                placeholder="e.g. (*.')">
        </div>

        <div class="col">
            <label class="form-label-sm">Status</label>
            <select name="status" class="form-select">
                <?php foreach (Constants::IS_ACTIVE as $value => $label): ?>
                    <option value="<?= esc($value) ?>"
                        <?= old('status', $tag['status'] ?? 1) == $value ? 'selected' : '' ?>>
                        <?= esc($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col">
            <label class="form-label-sm" for="al_end_date">AL End Date</label>

            <input type="date"
                name="al_end_date"
                id="al_end_date"
                class="form-control"
                value="<?= esc(old('al_end_date', $tag['al_end_date'] ?? '')) ?>">
        </div>

        <div class="col">
            <label for="ref_link" class="form-label-sm">AL Link</label>
            <input type="text" name="ref_link" id="ref_link"
                class="form-control"
                value="<?= old('ref_link', $old['ref_link'] ?? '') ?>"
                placeholder="https://">
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?= $this->endSection() ?>