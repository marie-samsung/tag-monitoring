<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= $tag['name'] ?> : Update
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mb-2">
    <h7 class="text-danger fw-semibold">Update Tag</h7>
    <h3><?= $tag['name'] ?></h3>
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

<?php if (isset($validation)): ?>
    <div class="alert alert-danger">
        <?= $validation->listErrors() ?>
    </div>
<?php endif; ?>

<form action="<?= site_url("tags/update/{$tag['id']}") ?>" method="post">
    <?= csrf_field() ?>

    <div class="container">
        <div class="card p-3">
            <div class="row mb-2">
                <div class="col">
                    <label class="form-label-xs text-primary">Property</label>
                    <select name="property_id" class="form-select searchable-select" required>
                        <?php foreach ($properties as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= ($tag['property_id'] == $value) ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col">
                    <label for="name" class="form-label-xs text-primary">Tag Name</label>
                    <input type="text" name="name" id="name"
                        class="form-control"
                        value="<?= $tag['name'] ?>"
                        required>
                    <small class="form-label-xs text-muted">
                        Tag should start with LC-
                    </small>
                </div>
            </div>
            
            <div class="row mb-2">
                <div class="col">
                    <label for="conditions" class="form-label-xs text-primary">Conditions</label>
                    <input type="text" name="conditions" id="conditions"
                        class="form-control"
                        value="<?= $tag['conditions'] ?>"
                        required>
                    <small class="form-label-xs text-muted">
                        e.g. (.*)
                    </small>
                </div>

                <div class="col">
                    <label class="form-label-xs text-primary">Status</label>
                    <select name="status" class="form-select" required>
                        <?php foreach (Constants::STATUS as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= ($tag['status'] == $value) ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label class="form-label-xs text-primary" for="al_end_date">Launch Expiry Date</label>
                    <input type="date"
                        name="al_end_date"
                        id="al_end_date"
                        class="form-control"
                        value="<?= date('Y-m-d', strtotime($tag['al_end_date'])) ?>"
                        required>
                </div>

                <div class="col">
                    <label for="ref_link" class="form-label-xs text-primary">Launch Link</label>
                    <input type="text" name="ref_link" id="ref_link"
                        class="form-control"
                        value="<?= $tag['ref_link'] ?>"
                        required>
                    <small class="form-label-xs text-muted">
                        https://
                    </small>
                </div>
            </div>
        </div>

        <!-- #region | REQUESTER DETAILS -->
        <h5 class="mt-3">Requester Details</h5>
        <div class="card p-3">
            <div class="row mb-2">
                <div class="col">
                    <label class="form-label-xs text-primary">Organization</label>
                    <select name="requester_organization" class="form-select advanced-select" required>
                        <?php foreach ($organizations as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= old('requester_organization', $tag['requester_organization'] ?? '') == $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col">
                    <label for="name" class="form-label-xs text-primary">Name</label>
                    <input type="text" name="requester_name" id="requester_name"
                        class="form-control"
                        value="<?= old('requester_name', $old['requester_name'] ?? '') ?>"
                        required>
                </div>

                <div class="col">
                    <label for="name" class="form-label-xs text-primary">Email Address</label>
                    <input type="text" name="requester_email" id="requester_email"
                        class="form-control"
                        value="<?= old('requester_email', $old['requester_email'] ?? '') ?>"
                        required>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="<?= site_url('tags') ?>" title="Cancel" class="btn btn-secondary btn-sm">Cancel</a>
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

        $('.advanced-select').select2({
            tags: true,
            width: '100%',
            height: '200%',
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
