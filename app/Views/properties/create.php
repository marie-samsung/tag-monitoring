<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<?php 
    use App\Models\Constants;
    use App\Models\CountriesModel;
    use App\Models\LanguagesModel;
    use App\Models\SubsidiariesModel;

    $subsidiaryModel = new SubsidiariesModel();
    $subsidiaries = $subsidiaryModel->Options();

    $countryModel = new CountriesModel();
    $countries = $countryModel->Options();

    $languageModel = new LanguagesModel();
    $languages = $languageModel->Options();
?>

<div class="container mb-2">
    <h4>Add a New Property</h4> 
</div>

<?php if (isset($validation)): ?>
    <div class="alert alert-danger">
        <?= $validation->listErrors() ?>
    </div>
<?php endif; ?>

<form action="<?= site_url('properties/create') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="container card p-3">
        <div class="row mb-2">
            <div class="col mb-2">
                <label for="name" class="form-label-xs">Name</label>
                <input type="text" name="name" id="name"
                    class="form-control"
                    value="<?= old('ticket_id', $old['ticket_id'] ?? '') ?>"
                    required>
                <small class="form-label-xs text-muted">
                    Property names should start with ZP6_XXX or ZS_XXX
                </small>
            </div>

            <div class="col mb-2">
                <label class="form-label-xs">Subsidiary</label>
                <select name="subsidiary_id" class="form-select searchable-select" required>
                    <?php foreach ($subsidiaries as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('subsidiary_id', $property['subsidiary_id'] ?? '') == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col mb-2">
                <label class="form-label-xs">Country</label>
                <select name="country_id" class="form-select searchable-select" required>
                    <?php foreach ($countries as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('country_id', $property['country_id'] ?? '') == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col mb-2">
                <label class="form-label-xs">Language</label>
                <select name="language_id" class="form-select searchable-select" required>
                    <?php foreach ($languages as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('language_id', $property['language_id'] ?? '') == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col">
                <label class="form-label-xs">Is Active?</label>
                <select name="is_active" class="form-select" required>
                    <?php foreach (Constants::IS_ACTIVE as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('is_active', $property['is_active'] ?? 1) == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <label class="form-label-xs">Is EPP?</label>
                <select name="is_epp" class="form-select" required>
                    <?php foreach (Constants::YES_NO as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('is_epp', $property['is_epp'] ?? 1) == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <label class="form-label-xs">Is Shop?</label>
                <select name="is_shop" class="form-select" required>
                    <?php foreach (Constants::YES_NO as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('is_shop', $property['is_shop'] ?? 1) == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col">
                <label for="ref_link" class="form-label-xs">Launch Link</label>
                <input type="text" name="ref_link" id="ref_link"
                    class="form-control"
                    value="<?= old('ref_link', $old['ref_link'] ?? '') ?>"
                    required>
                <small class="form-label-xs text-muted">
                    https://[link_to_adobe_launch]
                </small>
            </div>

            <div class="col">
                <label for="website" class="form-label-xs">Website (Samsung)</label>
                <input type="text" name="website" id="website"
                    class="form-control"
                    value="<?= old('website', $old['website'] ?? '') ?>"
                    required>
                <small class="form-label-xs text-muted">
                    https://www.samsung.com/
                </small>
            </div>
        </div>

        <input type="hidden" name="tag_id" value="<?= esc($tag['id'] ?? '') ?>">

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
