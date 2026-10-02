<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= esc($tag['name']) ?> : Update
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= site_url('tags') ?>" onclick="if (document.referrer) { window.history.back(); return false; }"
            class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center"
            style="width: 36px; height: 36px; padding: 0;" title="Back to Inventory">
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

    $user = session()->get('user_name') ?? 'User';
    ?>

    <?php if (isset($validation)): ?>
        <div class="alert alert-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= $validation->listErrors() ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url("tags/update/{$tag['id']}") ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="vendor_id" value="<?= esc($tag['vendor_id']) ?>" />

        <div class="row g-4">
            <!-- Left Column: Form Details -->
            <div class="col-lg-5">

                <!-- Tag Details Section -->
                <div class="apple-card mb-4"
                    style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
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
                                        <?= ($tag['property_id'] == $prop['id']) ? 'selected' : '' ?>>
                                        <?= esc($prop['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Tag Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label-sm fw-semibold text-secondary mb-1">Tag Name</label>
                            <a href="javascript:void(0)" onclick="copyTagName(this)" class="text-muted ms-1"
                               style="font-size: 0.75rem; text-decoration: none; cursor: pointer;" title="Copy Tag Name">
                                <i class="bi bi-copy"></i>
                            </a>
                            <select name="name" id="name" class="form-select advanced-select" required>
                                <option value="<?= esc($tag['name']) ?>" selected>
                                    <?= esc($tag['name']) ?>
                                </option>
                            </select>
                            <small class="form-text text-muted" style="font-size: 0.75rem;">
                                Tag should start with <strong>LC-</strong>
                            </small>
                        </div>

                        <!-- Conditions -->
                        <div class="col-md-6">
                            <label for="conditions"
                                class="form-label-sm fw-semibold text-secondary mb-1">Conditions</label>
                            <input type="text" name="conditions" id="conditions" class="form-control form-control-xs"
                                list="conditions-list" value="<?= esc($tag['conditions']) ?>" placeholder="e.g. (.*)"
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
                                <?php foreach (Constants::TAG_STATUS as $value => $label): ?>
                                    <option value="<?= esc($value) ?>" <?= ($tag['status'] == $value) ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Launch Expiry Date -->
                        <div class="col-md-6">
                            <label class="form-label-sm fw-semibold text-secondary mb-1" for="al_end_date">Launch Expiry
                                Date</label>
                            <div class="position-relative">
                                <input type="text" name="al_end_date" id="al_end_date"
                                    class="form-control form-control-xs pe-5"
                                    value="<?= esc(old('al_end_date', date('Y-m-d', strtotime($tag['al_end_date'])))) ?>"
                                    required>
                                <i class="bi bi-calendar-event text-muted position-absolute"
                                    style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.875rem;"></i>
                            </div>
                        </div>

                        <!-- Requested Date -->
                        <div class="col-md-6">
                            <label class="form-label-sm fw-semibold text-secondary mb-1" for="requested_date">Requested Date</label>
                            <div class="position-relative">
                                <input type="text" name="requested_date" id="requested_date"
                                    class="form-control form-control-xs pe-5"
                                    value="<?= !empty($tag['requested_date']) ? date('Y-m-d', strtotime($tag['requested_date'])) : '' ?>">
                                <i class="bi bi-calendar-event text-muted position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.875rem;"></i>
                            </div>
                        </div>

                        <!-- Requested Name -->
                        <div class="col-md-6">
                            <label class="form-label-sm fw-semibold text-secondary mb-1" for="requested_name">Requested Name</label>
                            <input type="text" name="requested_name" id="requested_name"
                                class="form-control form-control-xs"
                                value="<?= esc($tag['requested_name'] ?? '') ?>"
                                placeholder="Requested name will be the new name after tag update is verified">
                            <small class="form-text text-muted" style="font-size: 0.75rem;">
                                Requested name
                            </small>
                        </div>

                        <!-- Launch Link -->
                        <div class="col-md-12">
                            <label for="ref_link" class="form-label-sm fw-semibold text-secondary mb-1">Launch
                                Link</label>
                            <a href="<?= $tag['ref_link'] ?>" target="_blank" rel="noopener" class="text-muted ms-1"
                               style="font-size: 0.75rem; text-decoration: none;" title="View in Adobe Launch">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                            <input type="text" name="ref_link" id="ref_link" class="form-control form-control-xs"
                                value="<?= esc($tag['ref_link']) ?>" placeholder="https://..." required>
                            <small class="form-text text-muted" style="font-size: 0.75rem;">
                                Must start with <strong>https://</strong>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Requester Details Section -->
                <div class="apple-card mb-4"
                    style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
                    <h5 class="mb-4 text-primary d-flex align-items-center gap-2 fw-bold">
                        <i class="bi bi-person-fill"></i>
                        <span>Requester Details</span>
                    </h5>

                    <div class="row g-3">
                        <!-- Organization -->
                        <div class="col-md-4">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Organization</label>
                            <?php
                            $orgId = $tag['requester_organization'] ?? '';
                            $orgName = $orgId && isset($organizations[$orgId]) ? $organizations[$orgId] : $orgId;
                            ?>
                            <select name="requester_organization" id="requester_organization"
                                class="form-select advanced-select">
                                <option value="">-Select-</option>
                                <?php foreach ($organizations as $value => $label): ?>
                                    <option value="<?= esc($label) ?>" <?= old('requester_organization', $orgName) == $label ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Name -->
                        <div class="col-md-4">
                            <label class="form-label-sm fw-semibold text-secondary mb-1"
                                for="requester_name">Name</label>
                            <input type="text" name="requester_name" id="requester_name"
                                class="form-control form-control-xs"
                                list="requester-name-list"
                                value="<?= esc(old('requester_name', $tag['requester_name'] ?? '')) ?>"
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
                                value="<?= esc(old('requester_email', $tag['requester_email'] ?? '')) ?>"
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
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top mb-4">
                    <a href="<?= site_url('tags') ?>"
                        onclick="if (document.referrer) { window.history.back(); return false; }" title="Cancel"
                        class="apple-btn-secondary px-4 text-decoration-none">Cancel</a>
                    <button type="submit" class="apple-btn-primary px-4">Update Tag</button>
                </div>

            </div> <!-- End Left Column -->

            <!-- Right Column: JS Code Section -->
            <div class="col-lg-7">
                <!-- JS Code Section -->
                <div class="accordion mb-4" id="jsCodeAccordion">
                    <div class="accordion-item apple-card p-0"
                        style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none; border-radius: 8px; overflow: hidden;">
                        <h4 class="accordion-header" id="headingJsCode">
                            <button
                                class="accordion-button fw-bold text-primary d-flex align-items-center gap-2"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseJsCode"
                                aria-expanded="true" aria-controls="collapseJsCode"
                                style="background-color: var(--color-bg-base); box-shadow: none;">
                                <i class="bi bi-code-slash"></i>
                                <span>Script Editor</span>
                            </button>
                        </h4>
                        <div id="collapseJsCode" class="accordion-collapse collapse show"
                            aria-labelledby="headingJsCode" data-bs-parent="#jsCodeAccordion">
                            <div class="accordion-body p-0">
                                <!-- Editor View -->
                                <div id="jsEditorView">
                                    <div id="jsEditor"
                                        style="height: 500px; width: 100%; border-top: 1px solid rgba(0,0,0,0.05); border-bottom: 1px solid rgba(0,0,0,0.05);">
                                    </div>
                                    <div id="jsValidationPanel" class="p-2 border-bottom"
                                        style="font-size: 0.85rem; max-height: 150px; overflow-y: auto; background-color: var(--color-bg-base);">
                                        <div class="text-muted"><i class="bi bi-hourglass-split me-1"></i>
                                            Validating...</div>
                                    </div>
                                    <div class="p-3 bg-light d-flex justify-content-between align-items-center">
                                        <div class="form-check form-switch ms-2 mb-0">
                                            <input class="form-check-input" type="checkbox" id="themeToggle"
                                                style="cursor: pointer;">
                                            <label class="form-check-label text-muted" for="themeToggle"
                                                style="font-size: 0.9rem; cursor: pointer;">
                                                <i class="bi bi-moon-stars me-1"></i> Dark Mode
                                            </label>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="apple-btn-secondary btn-sm px-3"
                                                id="reviewChangesBtn" style="border-radius: 20px;">
                                                <i class="bi bi-file-diff me-1"></i> Review Changes
                                            </button>
                                            <button type="button" class="apple-btn-primary btn-sm px-4"
                                                id="saveJsCodeBtn" style="border-radius: 20px;">
                                                <i class="bi bi-save me-1"></i> Save Script
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Comparison Section (Review Changes Before Saving) -->
                                <div id="jsComparisonView" style="display: none;">
                                    <!-- Comparison Toolbar -->
                                    <div class="p-2 px-3 bg-light border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle d-flex align-items-center gap-1">
                                                <i class="bi bi-file-diff"></i>
                                                <span>Review Changes</span>
                                            </span>
                                            <span class="text-secondary small fw-medium" id="diffSubtitle">
                                                Original (left) vs Modified (right)
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <!-- Layout Mode Toggle -->
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Diff view layout">
                                                <button type="button" class="btn btn-outline-secondary btn-sm px-2 py-1" id="diffSideBySideBtn" title="Side by Side Split View">
                                                    <i class="bi bi-columns me-1"></i>Split
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm px-2 py-1 active" id="diffInlineBtn" title="Unified Inline View">
                                                    <i class="bi bi-view-stacked me-1"></i>Unified
                                                </button>
                                            </div>
                                            <!-- Fullscreen Toggle -->
                                            <button type="button" class="btn btn-outline-secondary btn-sm px-2 py-1" id="diffFullscreenBtn" title="Toggle Fullscreen">
                                                <i class="bi bi-arrows-fullscreen"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Warning banner if errors present -->
                                    <div id="diffErrorsBanner" class="p-2 px-3 border-bottom alert-warning text-warning-emphasis d-none" style="font-size: 0.82rem;">
                                        <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>
                                        <span id="diffErrorsCountText">JavaScript syntax errors detected in modified code.</span>
                                    </div>

                                    <!-- Monaco Diff Editor Container -->
                                    <div id="jsDiffEditor" style="height: 500px; width: 100%; border-bottom: 1px solid rgba(0,0,0,0.05);"></div>

                                    <!-- Comparison Footer Actions -->
                                    <div class="p-3 bg-light d-flex justify-content-between align-items-center">
                                        <div class="form-check form-switch ms-2 mb-0">
                                            <input class="form-check-input" type="checkbox" id="themeToggleDiff"
                                                style="cursor: pointer;">
                                            <label class="form-check-label text-muted" for="themeToggleDiff"
                                                style="font-size: 0.9rem; cursor: pointer;">
                                                <i class="bi bi-moon-stars me-1"></i> Dark Mode
                                            </label>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="apple-btn-secondary btn-sm px-3"
                                                id="backToEditorBtn" style="border-radius: 20px;">
                                                <i class="bi bi-arrow-left me-1"></i> Back to Editor
                                            </button>
                                            <button type="button" class="apple-btn-primary btn-sm px-4"
                                                id="confirmSaveJsCodeBtn" style="border-radius: 20px;">
                                                <i class="bi bi-check-lg me-1"></i> Confirm &amp; Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div> <!-- End Right Column -->
        </div> <!-- End Row -->
    </form>
</div>



<?= view('components/chatbot', ['contextId' => $tag['id'] ?? 0]) ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const picker = flatpickr("#al_end_date", {
            dateFormat: "Y-m-d",
            disableMobile: "true",
            defaultDate: "<?= esc(old('al_end_date', date('Y-m-d', strtotime($tag['al_end_date'])))) ?>"
        });
        
        flatpickr("#requested_date", {
            dateFormat: "Y-m-d",
            disableMobile: "true",
            defaultDate: "<?= !empty($tag['requested_date']) ? date('Y-m-d', strtotime($tag['requested_date'])) : '' ?>"
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

        $('select[name="property_id"]').on('change', function () {
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

        $('#name').on('select2:open', function () {
            setTimeout(function () {
                var $searchField = $('.select2-search__field');
                var currentVal = $('#name').val() || '';
                if ($searchField.length && !currentVal) {
                    var selectedOption = $('select[name="property_id"] option:selected');
                    var isShop = selectedOption.data('is-shop') || 'AEM';
                    $searchField.val('LC-' + isShop + '-').trigger('input');
                }
            }, 50);
        });

        window.copyTagName = function (textOrEl, optionalEl) {
            let text = '';
            let el = null;
            if (optionalEl) {
                text = textOrEl;
                el = optionalEl;
            } else if (typeof textOrEl === 'string') {
                text = textOrEl;
            } else {
                el = textOrEl;
            }

            if (!text) {
                const nameSelect = document.getElementById('name');
                text = (nameSelect && nameSelect.value) ? nameSelect.value : '<?= esc($tag['name']) ?>';
            }

            const temp = document.createElement('textarea');
            temp.value = text;
            temp.style.position = 'fixed';
            temp.style.left = '-9999px';
            document.body.appendChild(temp);
            temp.focus();
            temp.select();
            try {
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(text);
                } else {
                    document.execCommand('copy');
                }
            } catch (e) {
                document.execCommand('copy');
            }
            document.body.removeChild(temp);

            if (el) {
                el.innerHTML = '<i class="bi bi-check-lg text-success"></i>';
                setTimeout(() => {
                    el.innerHTML = '<i class="bi bi-copy"></i>';
                }, 1200);
            }
        };
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
    .diff-modal-fullscreen {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 1055 !important;
        background: var(--color-bg-base, #ffffff) !important;
        display: flex !important;
        flex-direction: column !important;
    }
    .diff-modal-fullscreen #jsDiffEditor {
        flex: 1 1 auto !important;
        height: calc(100vh - 130px) !important;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Configure the Monaco AMD loader path
        require.config({ paths: { vs: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs' } });

        require(['vs/editor/editor.main'], function () {
            // Set compiler options specifically for JavaScript autocomplete and diagnostics
            monaco.languages.typescript.javascriptDefaults.setCompilerOptions({
                target: monaco.languages.typescript.ScriptTarget.ES2020,
                allowNonTsExtensions: true,
                checkJs: true,
                allowReturnOutsideFunction: true, // Crucial for standalone JS tagging snippets
                lib: ['es2020', 'dom'] // Provide standard JS and DOM API definitions
            });

            // Set diagnostics options
            monaco.languages.typescript.javascriptDefaults.setDiagnosticsOptions({
                noSemanticValidation: false,
                noSyntaxValidation: false
            });

            // Add dedicated global definitions for common tagging scripts (Adobe Launch, Google Tag Manager, etc.)
            const tagGlobals = `
                /**
                 * Adobe Launch _satellite object for tracking and utility methods.
                 */
                declare const _satellite: {
                    track: (eventName: string, detail?: any) => void;
                    getVar: (variableName: string) => any;
                    setVar: (variableName: string, value: any) => void;
                    getVisitorId: () => any;
                    notify: (message: string, level?: number) => void;
                    cookie: {
                        get: (name: string) => string | null;
                        set: (name: string, value: string, options?: any) => void;
                        remove: (name: string) => void;
                    };
                    logger: {
                        log: (...args: any[]) => void;
                        warn: (...args: any[]) => void;
                        error: (...args: any[]) => void;
                    };
                };

                /**
                 * Google Tag Manager / Generic Data Layer array.
                 */
                declare const dataLayer: any[];

                /**
                 * Global Adobe object.
                 */
                declare const adobe: any;

                /**
                 * Global digitalData object (commonly used for data layers).
                 */
                declare const digitalData: any;

                /**
                 * Allow custom properties on the window object.
                 */
                interface Window {
                    [key: string]: any;
                }
            `;

            monaco.languages.typescript.javascriptDefaults.addExtraLib(
                tagGlobals,
                'ts:tag-globals.d.ts'
            );

            // Determine initial theme from local storage (default is vs-dark/twilight theme)
            var localTheme = localStorage.getItem('monacoTheme') || 'vs';
            var themeToggle = document.getElementById('themeToggle');

            if (localTheme === 'vs-dark' && themeToggle) {
                themeToggle.checked = true;
            }

            var initialCode = <?= json_encode($tag['js_code'] ?? '') ?>;
            var container = document.getElementById('jsEditor');

            if (!container) return; // In case the editor container doesn't exist

            // Create Monaco Editor
            var editor = monaco.editor.create(container, {
                value: initialCode,
                language: 'javascript',
                theme: localTheme,
                automaticLayout: true,
                minimap: { enabled: false },
                fontSize: 14,
                fontFamily: 'Fira Code, Menlo, Monaco, Consolas, monospace',
                lineHeight: 20,
                tabSize: 4,
                insertSpaces: true,
                wordWrap: 'on',
                cursorBlinking: 'smooth',
                smoothScrolling: true,
                padding: { top: 10, bottom: 10 },
                roundedSelection: true
            });

            // Make editor globally accessible if needed for saving
            window.editorInstance = editor;

            // Diff Editor state
            var diffEditor = null;
            var diffOriginalModel = null;
            var diffModifiedModel = null;

            function initDiffEditor() {
                var diffContainer = document.getElementById('jsDiffEditor');
                if (!diffContainer || diffEditor) return;

                diffOriginalModel = monaco.editor.createModel(initialCode, 'javascript');
                diffModifiedModel = monaco.editor.createModel(editor.getValue(), 'javascript');

                diffEditor = monaco.editor.createDiffEditor(diffContainer, {
                    theme: localStorage.getItem('monacoTheme') || 'vs',
                    readOnly: true,
                    automaticLayout: true,
                    fontSize: 14,
                    fontFamily: 'Fira Code, Menlo, Monaco, Consolas, monospace',
                    lineHeight: 20,
                    renderSideBySide: false, // Default to unified inline diff
                    wordWrap: 'on',
                    minimap: { enabled: false },
                    scrollBeyondLastLine: false,
                    originalEditable: false,
                    padding: { top: 10, bottom: 10 }
                });

                diffEditor.setModel({
                    original: diffOriginalModel,
                    modified: diffModifiedModel
                });

                window.diffEditorInstance = diffEditor;
            }

            // Synchronize theme across both toggles and all Monaco editors
            function applyTheme(newTheme) {
                monaco.editor.setTheme(newTheme);
                localStorage.setItem('monacoTheme', newTheme);
                var isDark = (newTheme === 'vs-dark');
                var t1 = document.getElementById('themeToggle');
                var t2 = document.getElementById('themeToggleDiff');
                if (t1) t1.checked = isDark;
                if (t2) t2.checked = isDark;
            }

            var themeToggle = document.getElementById('themeToggle');
            var themeToggleDiff = document.getElementById('themeToggleDiff');

            if (localTheme === 'vs-dark') {
                if (themeToggle) themeToggle.checked = true;
                if (themeToggleDiff) themeToggleDiff.checked = true;
            }

            if (themeToggle) {
                themeToggle.addEventListener('change', function () {
                    applyTheme(this.checked ? 'vs-dark' : 'vs');
                });
            }
            if (themeToggleDiff) {
                themeToggleDiff.addEventListener('change', function () {
                    applyTheme(this.checked ? 'vs-dark' : 'vs');
                });
            }

            // Recalculate editor layout when the Bootstrap accordion is shown
            var collapseElement = document.getElementById('collapseJsCode');
            if (collapseElement) {
                collapseElement.addEventListener('shown.bs.collapse', function () {
                    editor.layout();
                    if (diffEditor) diffEditor.layout();
                });
            }

            // Track dirty state
            var isDirty = false;
            editor.onDidChangeModelContent(function () {
                isDirty = true;
            });

            // Prevent closing page if unsaved
            window.addEventListener('beforeunload', function (e) {
                if (isDirty) {
                    var msg = "You have unsaved changes in the JavaScript code. Are you sure you want to leave?";
                    e.returnValue = msg;
                    return msg;
                }
            });

            // Register Ctrl-S / Cmd-S save keybinding inside Monaco
            editor.addCommand(monaco.KeyMod.CtrlCmd | monaco.KeyCode.KeyS, function () {
                var btn = document.getElementById('saveJsCodeBtn');
                if (btn) btn.click();
            });

            // Listen to markers/errors dynamically for the validation panel
            monaco.editor.onDidChangeMarkers(function () {
                var model = editor.getModel();
                if (!model) return;
                var markers = monaco.editor.getModelMarkers({ resource: model.uri });
                var panel = document.getElementById('jsValidationPanel');
                if (!panel) return;

                if (markers.length === 0) {
                    panel.innerHTML = '<div class="text-success fw-medium"><i class="bi bi-check-circle-fill me-1"></i> No errors or warnings</div>';
                } else {
                    var html = '';
                    markers.forEach(function (marker) {
                        // severity: 8 = Error, 4 = Warning, 2 = Info, 1 = Hint
                        var isError = marker.severity === 8;
                        var icon = isError ? '<i class="bi bi-x-circle-fill text-danger me-1"></i>' : '<i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>';
                        var colorClass = isError ? 'text-danger' : 'text-warning';
                        var msg = window.escapeHtml ? window.escapeHtml(marker.message) : marker.message;
                        html += `<div class="${colorClass} mb-1" style="font-family: monospace;">${icon} Line ${marker.startLineNumber}: ${msg}</div>`;
                    });
                    panel.innerHTML = html;
                }
            });

            // Format error list for custom themed popup dialog
            var formatErrorList = function (list) {
                var escapeFn = window.escapeHtml || function (t) { return t; };
                var html = list.map(item => `Line ${item.startLineNumber}: ${escapeFn(item.message)}`).join('<br>');
                return `<div style="max-height: 120px; overflow-y: auto; text-align: left; background: rgba(0,0,0,0.04); padding: 8px 12px; border-radius: 6px; font-family: monospace; font-size: 0.8rem; border: 1px solid rgba(0,0,0,0.08); margin: 10px 0; line-height: 1.4; word-break: break-word;">${html}</div>`;
            };

            // Switch to Comparison View to review changes before saving
            function showComparisonView() {
                var currentCode = editor.getValue();

                // If code is unchanged, notify user
                if (currentCode === initialCode) {
                    window.themeAlert({
                        title: 'No Changes',
                        message: 'No changes detected. The script is identical to the saved version.',
                        type: 'primary'
                    });
                    return;
                }

                if (!diffEditor) {
                    initDiffEditor();
                }

                diffOriginalModel.setValue(initialCode);
                diffModifiedModel.setValue(currentCode);

                // Check errors for banner in comparison section
                var model = editor.getModel();
                var markers = model ? monaco.editor.getModelMarkers({ resource: model.uri }) : [];
                var errors = markers.filter(function (m) { return m.severity === 8; });
                var banner = document.getElementById('diffErrorsBanner');
                var countText = document.getElementById('diffErrorsCountText');
                if (errors.length > 0) {
                    if (banner) banner.classList.remove('d-none');
                    if (countText) countText.textContent = `${errors.length} JavaScript syntax error(s) detected in your modified code.`;
                } else {
                    if (banner) banner.classList.add('d-none');
                }

                // Switch visible view
                document.getElementById('jsEditorView').style.display = 'none';
                document.getElementById('jsComparisonView').style.display = 'block';

                setTimeout(function () {
                    if (diffEditor) {
                        diffEditor.layout();
                    }
                }, 50);
            }

            // Return to Editor View
            function hideComparisonView() {
                var compView = document.getElementById('jsComparisonView');
                if (compView && compView.classList.contains('diff-modal-fullscreen')) {
                    toggleFullscreenDiff(false);
                }
                if (compView) compView.style.display = 'none';
                var editView = document.getElementById('jsEditorView');
                if (editView) editView.style.display = 'block';

                setTimeout(function () {
                    editor.layout();
                    editor.focus();
                }, 50);
            }

            // Confirm & Save action with themed JavaScript errors prompt
            function handleConfirmSave() {
                var model = editor.getModel();
                var markers = model ? monaco.editor.getModelMarkers({ resource: model.uri }) : [];
                var errors = markers.filter(function (m) { return m.severity === 8; });

                if (errors.length > 0) {
                    var messageHtml = `There are ${errors.length} JavaScript syntax error(s) detected in your code.<br>` +
                        formatErrorList(errors) +
                        `Are you sure you want to save anyway?`;

                    // Themed confirmation popup styled identically to the save popup
                    window.themeConfirm({
                        title: 'JavaScript Errors Detected',
                        message: messageHtml,
                        confirmText: 'Save Anyway',
                        cancelText: 'Cancel',
                        type: 'warning'
                    }).then(function (confirmed) {
                        if (confirmed) {
                            executeSave();
                        }
                    });
                } else {
                    executeSave();
                }
            }

            // Execute the AJAX save request
            function executeSave() {
                var btn = document.getElementById('confirmSaveJsCodeBtn');
                var originalText = btn ? btn.innerHTML : '<i class="bi bi-check-lg me-1"></i> Confirm &amp; Save';
                if (btn) {
                    btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Saving...';
                    btn.disabled = true;
                }

                var code = editor.getValue();
                var formData = new FormData();
                formData.append('js_code', code);

                fetch('<?= site_url('tags/save-js-code/' . $tag['id']) ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            isDirty = false;
                            initialCode = code; // Update baseline
                            if (diffOriginalModel) {
                                diffOriginalModel.setValue(code);
                            }

                            hideComparisonView();

                            window.themeAlert({
                                title: 'Success',
                                message: data.message,
                                type: 'success'
                            });
                        } else {
                            window.themeAlert({
                                title: 'Error',
                                message: data.message || 'Failed to save code.',
                                type: 'danger'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.themeAlert({
                            title: 'Error',
                            message: 'An unexpected error occurred.',
                            type: 'danger'
                        });
                    })
                    .finally(() => {
                        if (btn) {
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        }
                    });
            }

            // Event Listeners for Editor and Comparison Actions
            var saveBtn = document.getElementById('saveJsCodeBtn');
            if (saveBtn) {
                saveBtn.addEventListener('click', function () {
                    showComparisonView();
                });
            }

            var reviewBtn = document.getElementById('reviewChangesBtn');
            if (reviewBtn) {
                reviewBtn.addEventListener('click', function () {
                    showComparisonView();
                });
            }

            var backBtn = document.getElementById('backToEditorBtn');
            if (backBtn) {
                backBtn.addEventListener('click', function () {
                    hideComparisonView();
                });
            }

            var confirmSaveBtn = document.getElementById('confirmSaveJsCodeBtn');
            if (confirmSaveBtn) {
                confirmSaveBtn.addEventListener('click', function () {
                    handleConfirmSave();
                });
            }

            // Diff View Mode Buttons: Split (Side by Side) vs Unified (Inline)
            var diffSideBySideBtn = document.getElementById('diffSideBySideBtn');
            var diffInlineBtn = document.getElementById('diffInlineBtn');

            if (diffSideBySideBtn && diffInlineBtn) {
                diffSideBySideBtn.addEventListener('click', function () {
                    diffSideBySideBtn.classList.add('active');
                    diffInlineBtn.classList.remove('active');
                    if (diffEditor) {
                        diffEditor.updateOptions({ renderSideBySide: true });
                    }
                });

                diffInlineBtn.addEventListener('click', function () {
                    diffInlineBtn.classList.add('active');
                    diffSideBySideBtn.classList.remove('active');
                    if (diffEditor) {
                        diffEditor.updateOptions({ renderSideBySide: false });
                    }
                });
            }

            // Fullscreen Diff View Toggle
            function toggleFullscreenDiff(forceState) {
                var compView = document.getElementById('jsComparisonView');
                var fsBtn = document.getElementById('diffFullscreenBtn');
                if (!compView) return;

                var isFs = typeof forceState === 'boolean' ? forceState : !compView.classList.contains('diff-modal-fullscreen');

                if (isFs) {
                    compView.classList.add('diff-modal-fullscreen');
                    if (fsBtn) {
                        fsBtn.innerHTML = '<i class="bi bi-fullscreen-exit"></i>';
                        fsBtn.title = 'Exit Fullscreen';
                    }
                    document.body.style.overflow = 'hidden';
                } else {
                    compView.classList.remove('diff-modal-fullscreen');
                    if (fsBtn) {
                        fsBtn.innerHTML = '<i class="bi bi-arrows-fullscreen"></i>';
                        fsBtn.title = 'Toggle Fullscreen';
                    }
                    document.body.style.overflow = '';
                }

                setTimeout(function () {
                    if (diffEditor) {
                        diffEditor.layout();
                    }
                }, 60);
            }

            var fsBtn = document.getElementById('diffFullscreenBtn');
            if (fsBtn) {
                fsBtn.addEventListener('click', function () {
                    toggleFullscreenDiff();
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    var compView = document.getElementById('jsComparisonView');
                    if (compView && compView.classList.contains('diff-modal-fullscreen')) {
                        toggleFullscreenDiff(false);
                    }
                }
            });

            // Keydown shortcut Ctrl+S inside comparison view
            var compView = document.getElementById('jsComparisonView');
            if (compView) {
                compView.addEventListener('keydown', function (e) {
                    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
                        e.preventDefault();
                        handleConfirmSave();
                    }
                });
            }
        });
    });

    
</script>
<?= $this->endSection() ?>