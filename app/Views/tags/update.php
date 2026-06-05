<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= esc($tag['name']) ?> : Update
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= site_url('tags') ?>" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;" title="Back to Inventory">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold">Update Tag</h2>
            <p class="text-secondary small mb-0"><?= esc($tag['name']) ?></p>
        </div>
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
        <div class="alert alert-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= $validation->listErrors() ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url("tags/update/{$tag['id']}") ?>" method="post" style="max-width: 800px;">
        <?= csrf_field() ?>
        <input type="hidden" name="vendor_id" value="<?= esc($tag['vendor_id']) ?>" />

        <!-- Tag Details Section -->
        <div class="apple-card mb-4" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
            <h5 class="mb-4 text-primary d-flex align-items-center gap-2 fw-bold">
                <i class="bi bi-tag-fill"></i>
                <span>Tag Details</span>
            </h5>
            
            <div class="row g-3">
                <!-- Property -->
                <div class="col-md-6">
                    <label class="form-label-sm fw-semibold text-secondary mb-1">Property</label>
                    <select name="property_id" class="form-select searchable-select" required>
                        <?php foreach ($properties as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= ($tag['property_id'] == $value) ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tag Name -->
                <div class="col-md-6">
                    <label for="name" class="form-label-sm fw-semibold text-secondary mb-1">Tag Name</label>
                    <input type="text" name="name" id="name"
                        class="form-control form-control-xs"
                        value="<?= esc($tag['name']) ?>"
                        placeholder="LC-XXXX"
                        required>
                    <small class="form-text text-muted" style="font-size: 0.75rem;">
                        Tag should start with <strong>LC-</strong>
                    </small>
                </div>

                <!-- Conditions -->
                <div class="col-md-6">
                    <label for="conditions" class="form-label-sm fw-semibold text-secondary mb-1">Conditions</label>
                    <input type="text" name="conditions" id="conditions"
                        class="form-control form-control-xs"
                        value="<?= esc($tag['conditions']) ?>"
                        placeholder="e.g. (.*)"
                        required>
                    <small class="form-text text-muted" style="font-size: 0.75rem;">
                        RegEx or condition string (e.g. <code>(.*)</code>)
                    </small>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label class="form-label-sm fw-semibold text-secondary mb-1">Status</label>
                    <select name="status" class="form-select form-select-xs" required>
                        <?php foreach (Constants::STATUS as $value => $label): ?>
                            <option value="<?= esc($value) ?>"
                                <?= ($tag['status'] == $value) ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Launch Expiry Date -->
                <div class="col-md-6">
                    <label class="form-label-sm fw-semibold text-secondary mb-1" for="al_end_date">Launch Expiry Date</label>
                    <div class="position-relative">
                        <input type="text"
                            name="al_end_date"
                            id="al_end_date"
                            class="form-control form-control-xs pe-5"
                            value="<?= esc(old('al_end_date', date('Y-m-d', strtotime($tag['al_end_date'])))) ?>"
                            required>
                        <i class="bi bi-calendar-event text-muted position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.875rem;"></i>
                    </div>
                </div>

                <!-- Launch Link -->
                <div class="col-md-6">
                    <label for="ref_link" class="form-label-sm fw-semibold text-secondary mb-1">Launch Link</label>
                    <input type="text" name="ref_link" id="ref_link"
                        class="form-control form-control-xs"
                        value="<?= esc($tag['ref_link']) ?>"
                        placeholder="https://..."
                        required>
                    <small class="form-text text-muted" style="font-size: 0.75rem;">
                        Must start with <strong>https://</strong>
                    </small>
                </div>
            </div>
        </div>

        <!-- Requester Details Section -->
        <div class="apple-card mb-4" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
            <h5 class="mb-4 text-primary d-flex align-items-center gap-2 fw-bold">
                <i class="bi bi-person-fill"></i>
                <span>Requester Details</span>
            </h5>
            
            <div class="row g-3">
                <!-- Organization -->
                <div class="col-md-4">
                    <label class="form-label-sm fw-semibold text-secondary mb-1">Organization</label>
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

                <!-- Name -->
                <div class="col-md-4">
                    <label class="form-label-sm fw-semibold text-secondary mb-1" for="requester_name">Name</label>
                    <input type="text" name="requester_name" id="requester_name"
                        class="form-control form-control-xs"
                        value="<?= esc(old('requester_name', $tag['requester_name'] ?? '')) ?>"
                        placeholder="Requester Name">
                </div>

                <!-- Email Address -->
                <div class="col-md-4">
                    <label class="form-label-sm fw-semibold text-secondary mb-1" for="requester_email">Email Address</label>
                    <input type="email" name="requester_email" id="requester_email"
                        class="form-control form-control-xs"
                        value="<?= esc(old('requester_email', $tag['requester_email'] ?? '')) ?>"
                        placeholder="email@example.com">
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a href="<?= site_url('tags') ?>" title="Cancel" class="apple-btn-secondary px-4 text-decoration-none">Cancel</a>
            <button type="submit" class="apple-btn-primary px-4">Update Tag</button>
        </div>
    </form>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const picker = flatpickr("#al_end_date", {
            dateFormat: "Y-m-d",
            disableMobile: "true",
            defaultDate: "<?= esc(old('al_end_date', date('Y-m-d', strtotime($tag['al_end_date'])))) ?>"
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

