<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Tag: Create
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container">
    <h2>Add Tag Information</h2>
</div>

<?php 
    use App\Models\Constants;
    use App\Models\PropertiesModel;
    use App\Models\VendorsModel;
    use App\Models\OrganizationsModel;

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->Options();

    $vendorsModel = new VendorsModel();
    $vendors = $vendorsModel->Options();

    $organizationsModel = new OrganizationsModel();
    $organizations = $organizationsModel->Options();
?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="container alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<form action="<?= site_url('tags/create') ?>" method="post">
    <?= csrf_field() ?>
    <div class="container-fluid" style="max-width: 800px; margin-left: 0;">
        <div class="apple-card mb-4">
            <h5 class="mb-4">Tag Details</h5>
            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label-sm fw-semibold text-dark mb-1">Property</label>
                    <select name="property_id" class="form-select searchable-select" required>
                        <?php foreach ($properties as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= old('property_id', $tag['property_id'] ?? '') == $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="name" class="form-label-sm fw-semibold text-dark mb-1">Tag Name</label>
                    <input type="text" name="name" id="name"
                        class="form-control form-control-xs"
                        value="<?= old('name', $old['name'] ?? '') ?>"
                        required>
                    <small class="form-text text-muted" style="font-size: 0.75rem;">
                        Tag should start with LC-
                    </small>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label for="conditions" class="form-label-sm fw-semibold text-dark mb-1">Conditions</label>
                    <input type="text" name="conditions" id="conditions"
                        class="form-control form-control-xs"
                        value="<?= old('conditions', $old['conditions'] ?? '') ?>"
                        required>
                    <small class="form-text text-muted" style="font-size: 0.75rem;">
                        e.g. (.*)
                    </small>
                </div>

                <div class="col-md-6">
                    <label class="form-label-sm fw-semibold text-dark mb-1">Status</label>
                    <select name="status" class="form-select form-select-xs" required>
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
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label-sm fw-semibold text-dark mb-1" for="al_end_date">Launch Expiry Date</label>
                    <input type="text"
                        name="al_end_date"
                        id="al_end_date"
                        class="form-control form-control-xs"
                        value="<?= esc(old('al_end_date', $tag['al_end_date'] ?? '')) ?>"
                        required>
                </div>

                <div class="col-md-6">
                    <label for="ref_link" class="form-label-sm fw-semibold text-dark mb-1">Launch Link</label>
                    <input type="text" name="ref_link" id="ref_link"
                        class="form-control form-control-xs"
                        value="<?= old('ref_link', $old['ref_link'] ?? '') ?>"
                        required>
                    <small class="form-text text-muted" style="font-size: 0.75rem;">
                        https://
                    </small>
                </div>
            </div>
        </div>

        <!-- #region | REQUESTER DETAILS -->
        <div class="apple-card mb-4">
            <h5 class="mb-4">Requester Details</h5>
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label-sm fw-semibold text-dark mb-1">Organization</label>
                    <select name="requester_organization" class="form-select advanced-select">
                        <option value="">-Select-</option>
                        <?php foreach ($organizations as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= old('requester_organization', $tag['requester_organization'] ?? '') == $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label-sm fw-semibold text-dark mb-1">Name</label>
                    <input type="text" name="requester_name" id="requester_name"
                        class="form-control form-control-xs"
                        value="<?= old('requester_name', $old['requester_name'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label-sm fw-semibold text-dark mb-1">Email Address</label>
                    <input type="text" name="requester_email" id="requester_email"
                        class="form-control form-control-xs"
                        value="<?= old('requester_email', $old['requester_email'] ?? '') ?>">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="<?= site_url('tags') ?>" title="Cancel" class="apple-btn-secondary px-4">Cancel</a>
            <button type="submit" class="apple-btn-primary px-4">Submit</button>
        </div>
        <!-- #endregion -->
    </div>
</form>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#al_end_date", {
            dateFormat: "Y-m-d",
            disableMobile: "true"
        });

        $('.searchable-select').select2({
            width: '100%',
        });

        $('.advanced-select').select2({
            tags: true,
            width: '100%',
            allowClear: true,
            createTag: function (params) {
                let term = $.trim(params.term);

                if (term === '') {
                    return null;
                }

                return {
                    id: term,   // this will be sent to backend
                    text: term,
                    newOption: true
                };
            },
            templateResult: function (data) {
                if (data.newOption) {
                    return $('<span>Add: <strong>' + data.text + '</strong></span>');
                }
                return data.text;
            }
        });
    });
</script>

<?= $this->endSection() ?>
