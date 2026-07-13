<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Tag: Create
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= site_url('tags') ?>" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;" title="Back to Inventory">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold">Add Tag Information</h2>
            <p class="text-secondary small mb-0">Register a new tag and assign property and organization details.</p>
        </div>
    </div>

    <?php 
        use App\Models\Constants;
        use App\Models\PropertiesModel;
        use App\Models\VendorsModel;
        use App\Models\OrganizationsModel;
        use App\Models\TagsModel;

        $propertiesModel = new PropertiesModel();
        $propertiesList = $propertiesModel->select('id, name, is_shop')->findAll();

        $vendorsModel = new VendorsModel();
        $vendors = $vendorsModel->Options();

        $organizationsModel = new OrganizationsModel();
        $organizations = $organizationsModel->Options();

        $tagsModel = new TagsModel();
        $conditionsList = array_column($tagsModel->select('conditions')->distinct()->where('conditions !=', '')->where('conditions IS NOT NULL')->orderBy('conditions', 'ASC')->findAll(), 'conditions');

        $tagsModelInst = new \App\Models\TagsModel();
        $tagReqModelInst = new \App\Models\TagRequestsModel();
        $tasksModelInst = new \App\Models\TasksModel();

        $names1 = array_column($tagsModelInst->select('requester_name')->distinct()->where('requester_name !=', '')->where('requester_name IS NOT NULL')->findAll(), 'requester_name');
        $names2 = array_column($tagReqModelInst->select('requester_name')->distinct()->where('requester_name !=', '')->where('requester_name IS NOT NULL')->findAll(), 'requester_name');
        $names3 = array_column($tasksModelInst->select('requested_by')->distinct()->where('requested_by !=', '')->where('requested_by IS NOT NULL')->findAll(), 'requested_by');
        $requesterNamesList = array_unique(array_merge($names1, $names2, $names3));
        sort($requesterNamesList);

        $emails1 = array_column($tagsModelInst->select('requester_email')->distinct()->where('requester_email !=', '')->where('requester_email IS NOT NULL')->findAll(), 'requester_email');
        $emails2 = array_column($tagReqModelInst->select('requester_email')->distinct()->where('requester_email !=', '')->where('requester_email IS NOT NULL')->findAll(), 'requester_email');
        $requesterEmailsList = array_unique(array_merge($emails1, $emails2));
        sort($requesterEmailsList);
    ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('tags/create') ?>" method="post" style="max-width: 800px;">
        <?= csrf_field() ?>
        
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
                        <?php foreach ($propertiesList as $prop): ?>
                            <option value="<?= esc($prop['id']) ?>"
                                data-is-shop="<?= $prop['is_shop'] == Constants::YES ? 'Shop' : 'AEM' ?>"
                                <?= old('property_id', $tag['property_id'] ?? '') == $prop['id'] ? 'selected' : '' ?>>
                                <?= esc($prop['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tag Name -->
                <div class="col-md-6">
                    <label for="name" class="form-label-sm fw-semibold text-secondary mb-1">Tag Name</label>
                    <select name="name" id="name" class="form-select advanced-select" required>
                        <?php if (old('name', $old['name'] ?? '')): ?>
                            <option value="<?= esc(old('name', $old['name'] ?? '')) ?>" selected>
                                <?= esc(old('name', $old['name'] ?? '')) ?>
                            </option>
                        <?php endif; ?>
                    </select>
                    <small class="form-text text-muted" style="font-size: 0.75rem;">
                        Tag should start with <strong>LC-</strong>
                    </small>
                </div>

                <!-- Conditions -->
                <div class="col-md-6">
                    <label for="conditions" class="form-label-sm fw-semibold text-secondary mb-1">Conditions</label>
                    <input type="text" name="conditions" id="conditions"
                        class="form-control form-control-xs"
                        list="conditions-list"
                        value="<?= old('conditions', $old['conditions'] ?? '') ?>"
                        placeholder="e.g. (.*)"
                        required>
                    <datalist id="conditions-list">
                        <?php foreach ($conditionsList as $cond): ?>
                            <option value="<?= esc($cond) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
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
                                <?= old('status', $tag['status'] ?? 1) == $value ? 'selected' : '' ?>>
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
                            value="<?= esc(old('al_end_date', $tag['al_end_date'] ?? date('Y-m-d', strtotime('+2 months')))) ?>"
                            required>
                        <i class="bi bi-calendar-event text-muted position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.875rem;"></i>
                    </div>
                </div>

                <!-- Launch Link -->
                <div class="col-md-6">
                    <label for="ref_link" class="form-label-sm fw-semibold text-secondary mb-1">Launch Link</label>
                    <input type="text" name="ref_link" id="ref_link"
                        class="form-control form-control-xs"
                        value="<?= old('ref_link', $old['ref_link'] ?? '') ?>"
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
                    <select name="requester_organization" id="requester_organization" class="form-select advanced-select">
                        <option value="">-Select-</option>
                        <?php foreach ($organizations as $value => $label): ?>
                            <option value="<?= esc($label) ?>"
                                <?= old('requester_organization', $tag['requester_organization'] ?? '') == $label ? 'selected' : '' ?>>
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
                        list="requester-name-list"
                        value="<?= old('requester_name', $old['requester_name'] ?? '') ?>"
                        placeholder="Requester Name">
                    <datalist id="requester-name-list">
                        <?php foreach ($requesterNamesList as $name): ?>
                            <option value="<?= esc($name) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>

                <!-- Email Address -->
                <div class="col-md-4">
                    <label class="form-label-sm fw-semibold text-secondary mb-1" for="requester_email">Email Address</label>
                    <input type="email" name="requester_email" id="requester_email"
                        class="form-control form-control-xs"
                        list="requester-email-list"
                        value="<?= old('requester_email', $old['requester_email'] ?? '') ?>"
                        placeholder="email@example.com">
                    <datalist id="requester-email-list">
                        <?php foreach ($requesterEmailsList as $email): ?>
                            <option value="<?= esc($email) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a href="<?= site_url('tags') ?>" title="Cancel" class="apple-btn-secondary px-4 text-decoration-none">Cancel</a>
            <button type="submit" class="apple-btn-primary px-4">Create Tag</button>
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
            defaultDate: "<?= esc(old('al_end_date', $tag['al_end_date'] ?? date('Y-m-d', strtotime('+2 months')))) ?>"
        });

        // Flatpickr triggers on input click/focus

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

        $('select[name="property_id"]').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var isShop = selectedOption.data('is-shop') || 'AEM';
            
            var $searchField = $('.select2-search__field');
            if ($searchField.length) {
                var val = $searchField.val() || '';
                if (val.startsWith('LC-')) {
                    var parts = val.split('-');
                    if (parts.length > 1 && (parts[1] === 'Shop' || parts[1] === 'AEM')) {
                        parts[1] = isShop;
                        $searchField.val(parts.join('-')).trigger('input');
                    }
                }
            }

            var currentVal = $('#name').val() || '';
            if (currentVal.startsWith('LC-')) {
                var parts = currentVal.split('-');
                if (parts.length > 1 && (parts[1] === 'Shop' || parts[1] === 'AEM')) {
                    parts[1] = isShop;
                    var newVal = parts.join('-');
                    if ($('#name option[value="' + newVal + '"]').length === 0) {
                        var newOption = new Option(newVal, newVal, true, true);
                        $('#name').append(newOption).trigger('change');
                    } else {
                        $('#name').val(newVal).trigger('change');
                    }
                }
            }
        });

        $('#name').on('select2:open', function() {
            setTimeout(function() {
                var $searchField = $('.select2-search__field');
                var currentVal = $('#name').val() || '';
                if ($searchField.length && !currentVal) {
                    var selectedOption = $('select[name="property_id"] option:selected');
                    var isShop = selectedOption.data('is-shop') || 'AEM';
                    $searchField.val('LC-' + isShop + '-').trigger('input');
                }
            }, 50);
        });
    });
</script>

<?= $this->endSection() ?>
