<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Property: Create
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= site_url('properties') ?>" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;" title="Back to Properties">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold">Add Property Information</h2>
            <p class="text-secondary small mb-0">Register a new property and configure localization and domain settings.</p>
        </div>
    </div>

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

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('properties/create') ?>" method="post" style="max-width: 800px;">
        <?= csrf_field() ?>
        
        <!-- Property Details Section -->
        <div class="apple-card mb-4" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
            <h5 class="mb-4 text-primary d-flex align-items-center gap-2 fw-bold">
                <i class="bi bi-building-fill"></i>
                <span>Property Details</span>
            </h5>
            
            <div class="row g-3">
                <!-- Property Name -->
                <div class="col-md-6">
                    <label for="name" class="form-label-sm fw-semibold text-secondary mb-1">Property Name</label>
                    <input type="text" name="name" id="name"
                        class="form-control form-control-xs"
                        value="<?= old('name', $property['name'] ?? ($old['name'] ?? '')) ?>"
                        placeholder="e.g. ZP6_AE or ZS_UK"
                        required>
                    <small class="form-text text-muted" style="font-size: 0.75rem;">
                        Property names should start with <strong>ZP6_XXX</strong> or <strong>ZS_XXX</strong>
                    </small>
                </div>

                <!-- Subsidiary -->
                <div class="col-md-6">
                    <label for="subsidiary_id" class="form-label-sm fw-semibold text-secondary mb-1">Subsidiary</label>
                    <select name="subsidiary_id" id="subsidiary_id" class="form-select searchable-select" required>
                        <option value="">-Select Subsidiary-</option>
                        <?php foreach ($subsidiaries as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= old('subsidiary_id', $property['subsidiary_id'] ?? '') == $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Country -->
                <div class="col-md-6">
                    <label for="country_id" class="form-label-sm fw-semibold text-secondary mb-1">Country</label>
                    <select name="country_id" id="country_id" class="form-select searchable-select" required>
                        <option value="">-Select Country-</option>
                        <?php foreach ($countries as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= old('country_id', $property['country_id'] ?? '') == $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Language -->
                <div class="col-md-6">
                    <label for="language_id" class="form-label-sm fw-semibold text-secondary mb-1">Language</label>
                    <select name="language_id" id="language_id" class="form-select searchable-select" required>
                        <option value="">-Select Language-</option>
                        <?php foreach ($languages as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= old('language_id', $property['language_id'] ?? '') == $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Status -->
                <div class="col-md-4">
                    <label for="is_active" class="form-label-sm fw-semibold text-secondary mb-1">Status</label>
                    <select name="is_active" id="is_active" class="form-select form-select-xs" required>
                        <?php foreach (Constants::IS_ACTIVE as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= old('is_active', $property['is_active'] ?? 1) == $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Is Shop -->
                <div class="col-md-4">
                    <label for="is_shop" class="form-label-sm fw-semibold text-secondary mb-1">Is Shop?</label>
                    <select name="is_shop" id="is_shop" class="form-select form-select-xs" required>
                        <?php foreach (Constants::YES_NO as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= old('is_shop', $property['is_shop'] ?? 0) == $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Is EPP -->
                <div class="col-md-4">
                    <label for="is_epp" class="form-label-sm fw-semibold text-secondary mb-1">Is EPP?</label>
                    <select name="is_epp" id="is_epp" class="form-select form-select-xs" required>
                        <?php foreach (Constants::YES_NO as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= old('is_epp', $property['is_epp'] ?? 0) == $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Site & Launch Details Section -->
        <div class="apple-card mb-4" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
            <h5 class="mb-4 text-primary d-flex align-items-center gap-2 fw-bold">
                <i class="bi bi-globe2"></i>
                <span>Site & Launch Details</span>
            </h5>
            
            <div class="row g-3">
                <!-- Launch Link -->
                <div class="col-md-6">
                    <label for="ref_link" class="form-label-sm fw-semibold text-secondary mb-1">Launch Link</label>
                    <input type="text" name="ref_link" id="ref_link"
                        class="form-control form-control-xs"
                        value="<?= old('ref_link', $property['ref_link'] ?? ($old['ref_link'] ?? '')) ?>"
                        placeholder="https://..."
                        required>
                    <small class="form-text text-muted" style="font-size: 0.75rem;">
                        Must start with <strong>https://</strong>
                    </small>
                </div>

                <!-- Website (Samsung) -->
                <div class="col-md-6">
                    <label for="website" class="form-label-sm fw-semibold text-secondary mb-1">Website (Samsung)</label>
                    <input type="text" name="website" id="website"
                        class="form-control form-control-xs"
                        value="<?= old('website', $property['website'] ?? ($old['website'] ?? '')) ?>"
                        placeholder="https://www.samsung.com/..."
                        required>
                    <small class="form-text text-muted" style="font-size: 0.75rem;">
                        Must start with <strong>https://</strong>
                    </small>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a href="<?= site_url('properties') ?>" title="Cancel" class="apple-btn-secondary px-4 text-decoration-none">Cancel</a>
            <button type="submit" class="apple-btn-primary px-4">Create Property</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('.searchable-select').select2({
            width: '100%',
        });
    });
</script>

<?= $this->endSection() ?>
