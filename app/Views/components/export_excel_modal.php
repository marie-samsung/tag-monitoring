<?php
/**
 * Dynamic Excel Export Modal Component
 *
 * @var string $modalId
 * @var string $formAction
 * @var string $title
 * @var string|null $description
 * @var string|null $entityName
 * @var int|null $totalCount
 * @var array $defaultFields    Associative array: ['key' => 'Label']
 * @var array $additionalFields Associative array: ['key' => 'Label']
 * @var string $storageKey      localStorage key to remember choices
 */

$description = $description ?? 'Customize the columns you want to include in your Excel file.';
$entityName = $entityName ?? 'records';
$totalCount = $totalCount ?? null;
$totalFieldsCount = count($defaultFields) + count($additionalFields);
$defaultKeys = array_keys($defaultFields);
?>

<div class="modal fade export-excel-dynamic-modal" id="<?= esc($modalId) ?>" tabindex="-1" aria-labelledby="<?= esc($modalId) ?>Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 860px;">
        <div class="modal-content shadow-lg" style="border-radius: 20px; border: 1px solid rgba(0, 0, 0, 0.08); overflow: hidden;">
            <form action="<?= esc($formAction) ?>" method="GET" id="<?= esc($modalId) ?>Form">
                
                <!-- Preserve all active GET filters -->
                <?php foreach ($_GET as $gKey => $gVal): ?>
                    <?php if ($gKey === 'fields' || $gKey === 'page') continue; ?>
                    <?php if (is_array($gVal)): ?>
                        <?php foreach ($gVal as $subVal): ?>
                            <input type="hidden" name="<?= esc($gKey) ?>[]" value="<?= esc($subVal) ?>">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <input type="hidden" name="<?= esc($gKey) ?>" value="<?= esc($gVal) ?>">
                    <?php endif; ?>
                <?php endforeach; ?>

                <!-- Modal Header -->
                <div class="modal-header px-4 py-3 border-bottom d-flex align-items-center justify-content-between" style="background-color: var(--color-bg-base, #ffffff);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width: 44px; height: 44px; background: rgba(52, 199, 89, 0.14); color: #28a745; box-shadow: 0 2px 8px rgba(40, 167, 69, 0.15);">
                            <i class="bi bi-file-earmark-excel fs-4"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="modal-title fw-bold mb-0 text-dark" id="<?= esc($modalId) ?>Label"><?= esc($title) ?></h5>
                                <?php if ($totalCount !== null): ?>
                                    <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1" style="font-size: 0.75rem; font-weight: 600;">
                                        <?= (int) $totalCount ?> <?= esc($entityName) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-secondary small mb-0 mt-0.5"><?= esc($description) ?></p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body p-4" style="background-color: #fafbfc; max-height: calc(85vh - 160px); overflow-y: auto;">
                    
                    <!-- Quick Toolbar -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 pb-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-3 py-1 fw-medium" id="<?= esc($modalId) ?>BtnSelectAll" style="font-size: 0.78rem;">
                                <i class="bi bi-check-all me-1"></i>Select All
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-3 py-1 fw-medium" id="<?= esc($modalId) ?>BtnDeselectAll" style="font-size: 0.78rem;">
                                <i class="bi bi-dash-circle me-1"></i>Deselect All
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-primary rounded-pill px-3 py-1 fw-medium" id="<?= esc($modalId) ?>BtnResetDefault" style="font-size: 0.78rem;">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset to Default
                            </button>
                        </div>
                        <div>
                            <span class="badge bg-primary text-white rounded-pill px-3 py-1.5" id="<?= esc($modalId) ?>SelectedBadge" style="font-size: 0.78rem; font-weight: 600;">
                                <span class="count-num"><?= count($defaultFields) ?></span> of <?= $totalFieldsCount ?> fields selected
                            </span>
                        </div>
                    </div>

                    <!-- Warning if 0 fields selected -->
                    <div class="alert alert-warning py-2 px-3 small d-none align-items-center gap-2 mb-3" id="<?= esc($modalId) ?>ZeroAlert" style="border-radius: 10px;">
                        <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                        <span>Please select at least one field before exporting.</span>
                    </div>

                    <!-- Section 1: Default Fields -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-2.5">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold text-dark small text-uppercase tracking-wider" style="letter-spacing: 0.5px; font-size: 0.8rem;">
                                    <i class="bi bi-star-fill text-warning me-1"></i>Default Fields
                                </span>
                                <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-0.5" style="font-size: 0.7rem; font-weight: 500;">
                                    Standard Export (<?= count($defaultFields) ?>)
                                </span>
                            </div>
                            <span class="text-muted small" style="font-size: 0.75rem;">Selected by default</span>
                        </div>

                        <div class="row g-2">
                            <?php foreach ($defaultFields as $key => $label): ?>
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="export-field-card p-2.5 rounded-3 d-flex align-items-center justify-content-between border bg-white h-100"
                                        style="cursor: pointer; transition: all 0.15s ease; border-color: rgba(0, 0, 0, 0.08) !important;"
                                        data-field-key="<?= esc($key) ?>">
                                        <div class="form-check d-flex align-items-center gap-2 mb-0" style="pointer-events: none;">
                                            <input class="form-check-input export-field-cb mt-0" type="checkbox" name="fields[]"
                                                value="<?= esc($key) ?>" id="<?= esc($modalId) ?>_field_<?= esc($key) ?>" checked
                                                data-is-default="1" style="width: 1.15em; height: 1.15em; cursor: pointer;">
                                            <label class="form-check-label text-dark fw-medium small mb-0 text-truncate"
                                                for="<?= esc($modalId) ?>_field_<?= esc($key) ?>" style="cursor: pointer; max-width: 170px;" title="<?= esc($label) ?>">
                                                <?= esc($label) ?>
                                            </label>
                                        </div>
                                        <span class="badge bg-light text-muted border rounded-pill px-1.5 py-0.5 flex-shrink-0" style="font-size: 0.65rem;">
                                            Default
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Section 2: Additional Fields -->
                    <?php if (!empty($additionalFields)): ?>
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2.5">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-dark small text-uppercase tracking-wider" style="letter-spacing: 0.5px; font-size: 0.8rem;">
                                        <i class="bi bi-plus-circle-fill text-primary me-1"></i>Additional Available Fields
                                    </span>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.5" style="font-size: 0.7rem; font-weight: 500;">
                                        Optional (<?= count($additionalFields) ?>)
                                    </span>
                                </div>
                                <span class="text-muted small" style="font-size: 0.75rem;">Pick any extra feeds needed</span>
                            </div>

                            <div class="row g-2">
                                <?php foreach ($additionalFields as $key => $label): ?>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="export-field-card p-2.5 rounded-3 d-flex align-items-center justify-content-between border bg-white h-100"
                                            style="cursor: pointer; transition: all 0.15s ease; border-color: rgba(0, 0, 0, 0.08) !important;"
                                            data-field-key="<?= esc($key) ?>">
                                            <div class="form-check d-flex align-items-center gap-2 mb-0" style="pointer-events: none;">
                                                <input class="form-check-input export-field-cb mt-0" type="checkbox" name="fields[]"
                                                    value="<?= esc($key) ?>" id="<?= esc($modalId) ?>_field_<?= esc($key) ?>"
                                                    data-is-default="0" style="width: 1.15em; height: 1.15em; cursor: pointer;">
                                                <label class="form-check-label text-dark fw-medium small mb-0 text-truncate"
                                                    for="<?= esc($modalId) ?>_field_<?= esc($key) ?>" style="cursor: pointer; max-width: 170px;" title="<?= esc($label) ?>">
                                                    <?= esc($label) ?>
                                                </label>
                                            </div>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-1.5 py-0.5 flex-shrink-0" style="font-size: 0.65rem;">
                                                + Extra
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer px-4 py-3 border-top d-flex align-items-center justify-content-between" style="background-color: var(--color-bg-base, #ffffff);">
                    <div class="small text-muted d-flex align-items-center gap-1.5">
                        <i class="bi bi-info-circle text-primary"></i>
                        <span>Export includes current filters &amp; searches.</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="apple-btn-secondary btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="apple-btn-primary btn-sm px-4 d-inline-flex align-items-center gap-2" id="<?= esc($modalId) ?>SubmitBtn" style="background: #28a745; border-color: #28a745;">
                            <i class="bi bi-download"></i>
                            <span>Export Excel</span>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
    .export-field-card:hover {
        background-color: #f8f9fc !important;
        border-color: rgba(0, 113, 227, 0.4) !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    .export-field-card.card-selected {
        background-color: rgba(0, 113, 227, 0.04) !important;
        border-color: rgba(0, 113, 227, 0.35) !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalId = <?= json_encode($modalId) ?>;
    const storageKey = <?= json_encode($storageKey) ?>;
    const defaultKeys = <?= json_encode($defaultKeys) ?>;
    const totalFields = <?= (int) $totalFieldsCount ?>;

    const modalEl = document.getElementById(modalId);
    if (!modalEl) return;

    const form = document.getElementById(modalId + 'Form');
    const checkboxes = modalEl.querySelectorAll('.export-field-cb');
    const cards = modalEl.querySelectorAll('.export-field-card');
    const badgeCount = modalEl.querySelector('#' + modalId + 'SelectedBadge .count-num');
    const zeroAlert = document.getElementById(modalId + 'ZeroAlert');
    const submitBtn = document.getElementById(modalId + 'SubmitBtn');

    // Update styling and counter
    function updateState() {
        let checkedCount = 0;
        checkboxes.forEach(cb => {
            const card = cb.closest('.export-field-card');
            if (cb.checked) {
                checkedCount++;
                if (card) card.classList.add('card-selected');
            } else {
                if (card) card.classList.remove('card-selected');
            }
        });

        if (badgeCount) {
            badgeCount.textContent = checkedCount;
        }

        if (checkedCount === 0) {
            if (zeroAlert) zeroAlert.classList.remove('d-none');
            if (zeroAlert) zeroAlert.classList.add('d-flex');
            if (submitBtn) submitBtn.disabled = true;
        } else {
            if (zeroAlert) zeroAlert.classList.add('d-none');
            if (zeroAlert) zeroAlert.classList.remove('d-flex');
            if (submitBtn) submitBtn.disabled = false;
        }
    }

    // Toggle card on click
    cards.forEach(card => {
        card.addEventListener('click', function (e) {
            const cb = this.querySelector('.export-field-cb');
            if (cb) {
                cb.checked = !cb.checked;
                updateState();
            }
        });
    });

    // Checkbox direct change
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            updateState();
        });
    });

    // Select All
    const btnSelectAll = document.getElementById(modalId + 'BtnSelectAll');
    if (btnSelectAll) {
        btnSelectAll.addEventListener('click', function () {
            checkboxes.forEach(cb => cb.checked = true);
            updateState();
        });
    }

    // Deselect All
    const btnDeselectAll = document.getElementById(modalId + 'BtnDeselectAll');
    if (btnDeselectAll) {
        btnDeselectAll.addEventListener('click', function () {
            checkboxes.forEach(cb => cb.checked = false);
            updateState();
        });
    }

    // Reset to Default
    const btnResetDefault = document.getElementById(modalId + 'BtnResetDefault');
    if (btnResetDefault) {
        btnResetDefault.addEventListener('click', function () {
            checkboxes.forEach(cb => {
                cb.checked = (cb.dataset.isDefault === '1');
            });
            try {
                localStorage.removeItem(storageKey);
            } catch (err) {}
            updateState();
        });
    }

    // Restore from localStorage
    function restoreSavedState() {
        try {
            const saved = localStorage.getItem(storageKey);
            if (saved) {
                const parsed = JSON.parse(saved);
                if (Array.isArray(parsed) && parsed.length > 0) {
                    checkboxes.forEach(cb => {
                        cb.checked = parsed.includes(cb.value);
                    });
                }
            }
        } catch (err) {}
        updateState();
    }

    // Save selection to localStorage on submit & close modal
    if (form) {
        form.addEventListener('submit', function (e) {
            const checkedVals = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
            if (checkedVals.length === 0) {
                e.preventDefault();
                if (zeroAlert) {
                    zeroAlert.classList.remove('d-none');
                    zeroAlert.classList.add('d-flex');
                }
                return false;
            }

            try {
                localStorage.setItem(storageKey, JSON.stringify(checkedVals));
            } catch (err) {}

            // Close modal shortly after triggering native file download
            setTimeout(() => {
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }, 600);
        });
    }

    // Restore on initial load
    restoreSavedState();

    // Re-check when modal is opened
    modalEl.addEventListener('show.bs.modal', function () {
        restoreSavedState();
    });
});
</script>
