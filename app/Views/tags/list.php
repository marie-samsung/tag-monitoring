<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Martech TMS
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
#tblTags tbody tr {
    cursor: pointer;
}
.selected-row td {
    background-color: rgba(0, 113, 227, 0.05) !important;
}
.floating-action-bar {
    position: fixed;
    bottom: -80px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(29, 29, 31, 0.92);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 40px;
    padding: 10px 24px;
    z-index: 1050;
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35);
    transition: bottom 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
    opacity: 0;
    display: flex;
    align-items: center;
    gap: 16px;
}
.floating-action-bar.show {
    bottom: 30px;
    opacity: 1;
}
.floating-action-info {
    color: #ffffff;
    font-size: 0.95rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}
.floating-action-info .count-badge {
    background: #0071e3;
    color: #ffffff;
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}
.floating-action-divider {
    width: 1px;
    height: 24px;
    background: rgba(255, 255, 255, 0.2);
}
.floating-btn-renew {
    background: #0071e3;
    color: #ffffff;
    border: none;
    border-radius: 30px;
    padding: 8px 18px;
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 6px;
    text-decoration: none !important;
    transition: background-color 0.2s, transform 0.2s;
}
.floating-btn-renew:hover {
    background: #147ce5;
    color: #ffffff;
    transform: scale(1.02);
}
.floating-btn-renew:active {
    transform: scale(0.98);
}
.floating-btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 30px;
    padding: 8px 18px;
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 6px;
    text-decoration: none !important;
    transition: background-color 0.2s, border-color 0.2s, transform 0.2s;
}
.floating-btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
    color: #ffffff;
    transform: scale(1.02);
}
.floating-btn-secondary:active {
    transform: scale(0.98);
}
</style>

<?php
    use App\Models\Constants;
    use App\Models\PropertiesModel;
    use App\Models\SubsidiariesModel;
    use App\Models\JiraHistoryModel;
    use App\Models\TagsModel;
    helper('custom_functions');

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->WebsiteLinks();
    $propertyOptions = $propertiesModel->Options();

    $subsidiariesModel = new SubsidiariesModel();
    $subsidiaries = $subsidiariesModel->Options();

    $tagsModel = new TagsModel();
?>

<?= csrf_field() ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Tag Inventory <span id="selectedCountBadge" class="badge bg-primary align-middle ms-2" style="display: none; font-size: 0.85rem; font-weight: 500; vertical-align: middle;">0 selected</span></h2>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('tags/renew') ?>" title="Date Extension" class="apple-btn-primary btn-sm ms-auto px-3" id="btnRenew" style="display:none;">Date Extension</a>
            <a href="<?= site_url('tags/update-rule-request') ?>" title="Create update rule request" class="apple-btn-primary btn-sm ms-auto px-3" id="btnUpdateRule" style="display:none;">Create Update Rule Request</a>
            <a href="<?= site_url('tags/create') ?>" title="Add Tag Information" class="apple-btn-primary btn-sm ms-auto px-3">Add Tag Information</a>
            <a href="<?= site_url('tags/export?' . http_build_query($_GET)) ?>" class="apple-btn-secondary btn-sm ms-auto px-3">Export as CSV</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Active Tags -->
        <div class="col-md-3">
            <div class="apple-metric-card border-start border-4 border-success" style="cursor: pointer;" onclick="window.location.href='<?= site_url('tags?status=' . Constants::STATUS_ENABLED) ?>'">
                <div class="apple-metric-label">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i> 
                    <span>Active Tags</span>
                </div>
                <div class="apple-metric-value text-success"><?= $tagsModel->countActiveTags() ?></div>
                <div class="text-secondary small mt-2">Currently active in AL</div>
            </div>
        </div>

        <!-- Expiring Soon -->
        <div class="col-md-3">
            <div class="apple-metric-card border-start border-4 border-danger" style="cursor: pointer;" onclick="window.location.href='<?= site_url('tags?status=expiring') ?>'">
                <div class="apple-metric-label">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i> 
                    <span>Expiring Soon</span>
                </div>
                <div class="apple-metric-value text-danger"><?= $tagsModel->countExpiringTags() ?></div>
                <div class="text-secondary small mt-2">Expiring within 15 days</div>
            </div>
        </div>

        <!-- Pending Verification -->
        <div class="col-md-3">
            <div class="apple-metric-card border-start border-4 border-warning" style="cursor: pointer;" onclick="window.location.href='<?= site_url('tags?status=' . Constants::STATUS_PENDING_VERIFICATION) ?>'">
                <div class="apple-metric-label">
                    <i class="bi bi-hourglass-split text-warning fs-5"></i> 
                    <span>Pending Verification</span>
                </div>
                <div class="apple-metric-value text-warning"><?= $tagsModel->countPendingVerificationTags() ?></div>
                <div class="text-secondary small mt-2">Renewed tags awaiting QA</div>
            </div>
        </div>

        <!-- Total Tags -->
        <div class="col-md-3">
            <div class="apple-metric-card border-start border-4 border-secondary" style="cursor: pointer;" onclick="window.location.href='<?= site_url('tags?status=all') ?>'">
                <div class="apple-metric-label">
                    <i class="bi bi-collection-fill text-secondary fs-5"></i> 
                    <span>Total Tags</span>
                </div>
                <div class="apple-metric-value text-secondary"><?= $tagsModel->countAllTags() ?></div>
                <div class="text-secondary small mt-2">Total tags in the system</div>
            </div>
        </div>
    </div>

    <!-- #region | SEARCH FILTER -->
    <div class="apple-control-bar mb-4">
        <form method="get" class="row g-2 w-100 m-0">
            <div class="col">
                <input 
                    type="text" 
                    name="keyword" 
                    class="form-control form-select-xs" 
                    placeholder="Search..."
                    value="<?= esc($_GET['keyword'] ?? '') ?>"
                >
            </div>

            <div class="col">
                <select name="subsidiary_id" class="form-select form-select-xs">
                    <option value="">All Subsidiaries</option>
                    <?php foreach ($subsidiaries as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('subsidiary_id', $filters['subsidiary_id'] ?? '') == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <select name="property_id" class="form-select searchable-select">
                    <option value="">All Properties</option>
                    <?php foreach ($propertyOptions as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('property_id', $filters['property_id'] ?? '') == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <select name="status" class="form-select form-select-xs">
                    <option value="<?= Constants::STATUS_ALL ?>" <?= (old('status', $filters['status'] ?? null) == Constants::STATUS_ALL || empty($filters['status'])) ? 'selected' : '' ?>>All Status</option>
                    <?php foreach (Constants::STATUS as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('status', $filters['status'] ?? null) == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-center gap-2">
                <button class="apple-btn-primary btn-sm w-100">Filter</button>
                <a href="<?= site_url('tags') ?>" class="apple-btn-secondary btn-sm w-100 text-center text-decoration-none">Clear</a>
            </div>

        </form>
    </div>
    <!-- #endregion -->

    <!-- #region | TAGS TABLE -->
    <div class="apple-table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tblTags">
            <thead>
                <tr>
                    <th class="form-label-sm fw-semibold">#</th>
                    <th><input type="checkbox" id="selectAllTags" title="Select All" class="form-check-input"></th>
                    <th class="form-label-sm fw-semibold">Subsidiary</th>
                    <th class="form-label-sm fw-semibold">Property</th>
                    <th class="form-label-sm fw-semibold">Name</th>
                    <th class="form-label-sm fw-semibold">Status</th>
                    <th class="form-label-sm fw-semibold">Days</th>
                    <th class="form-label-sm fw-semibold">End Date</th>
                    <th class="form-label-sm fw-semibold">Updated</th>
                    <th class="form-label-sm fw-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($tags as $tag): 
                    $ticketId = (new JiraHistoryModel())->getLatestTag($tag['id'])['ticket_id'] ?? null;
                    $subsidiaryId = $properties[$tag['property_id']]['subsidiary_id'];
                    $subsidiary = $subsidiaries[$subsidiaryId];
                ?>
                    <tr>
                        <!-- ID -->
                        <td><?= $tag['id'] ?></td>
                        <!-- Checkbox -->
                        <td>
                            <input type="checkbox"
                                name="tag_ids[]"
                                value="<?= htmlspecialchars($tag['id']) ?>"
                                class="form-check-input">
                        </td>
                        <!-- Subsidiary -->
                        <td>
                            <span class="badge" style="<?= random_bg_style($subsidiary) ?>">
                                <?= esc($subsidiary) ?>
                            </span>
                        </td>
                        <!-- Property -->
                        <td>
                            <i class="bi bi-tag-fill" style="<?= random_text_style($tag['property_id']) ?>"></i>
                            <a href="<?= $properties[$tag['property_id']]['ref_link'] ?>" class="apple-link-dark form-label-sm ms-1">
                                <?= $properties[$tag['property_id']]['name'] ?>
                            </a>
                        </td>
                        <!-- Name -->
                        <td class="form-label-sm">
                            <span class="group-hover-container align-items-center">
                                <a href="<?= $tag['ref_link'] ?>" class="apple-link-dark">
                                    <?= esc($tag['name']) ?>
                                </a>
                                <a href="javascript:void(0)" onclick="copyTagName('<?= esc($tag['name']) ?>', this)" title="Copy tag name" class="text-muted ms-2" style="transition: color 0.2s; text-decoration: none; line-height: 1;">
                                    <i class="bi bi-copy" style="font-size: 0.85rem;"></i>
                                </a>
                            </span>
                        </td>
                        <!-- Status -->
                        <td>
                            <span class="badge <?= status_class($tag['status']) ?>">
                                <?= esc(status_label($tag['status'])) ?>
                            </span>
                            <?php if ($tag['status'] == Constants::STATUS_PENDING_VERIFICATION): ?>
                                <br>
                                <a href="<?= Constants::JIRA_LINK . $ticketId ?>" class="apple-link ms-1" style="font-size: 0.8rem;">
                                    <?= $ticketId ?>
                                </a>
                                <a href="<?= site_url('tags/verify/' . $tag['id']) ?>"
                                   class="badge bg-primary text-decoration-none"
                                   onclick="return confirm('Have you verified the end date matches in AL?');">
                                    Verify
                                </a>
                            <?php endif; ?>
                        </td>
                        <!-- Days -->
                        <?php
                            $alEndDate = strtotime($tag['al_end_date']);
                            $now = time();
                            $diffDays = ceil(($alEndDate - $now) / 86400);
                        ?>
                        <td class="<?= days_class($diffDays) ?> form-label-sm">
                            <?= esc($diffDays) ?>
                        </td>
                        <!-- End Date -->
                        <td class="<?= date_class($tag['al_end_date'], $tag['status']) ?> form-label-sm" style="width:max-content; white-space:nowrap;">
                            <?= esc(date('d-m-Y', strtotime($tag['al_end_date']))) ?>
                            <?php if ($tag['requested_date']) : ?>
                                <br><small class="text-muted">[<?= esc(date('d-m-Y', strtotime($tag['requested_date']))) ?>]</small>
                            <?php endif; ?>
                        </td>
                        <!-- Updated At -->
                        <td class="form-label-sm" style="width:max-content; white-space:nowrap;"><?= esc(date('d-m-Y', strtotime($tag['updated_at']))) ?></td>
                        <!-- <td><?= esc($tag['updated_by']) ?></td> -->
                        <!-- Actions -->
                        <td class="text-nowrap">
                            <a href="<?= site_url('tags/view/'.$tag['id']) ?>" title="View tag details" class="apple-btn-secondary btn-sm text-decoration-none">View</a>
                            <a href="<?= site_url('tags/update/'.$tag['id']) ?>" title="Update tag details" class="apple-btn-primary btn-sm text-decoration-none">Update</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
            <div class="mt-4 d-flex justify-content-center">
                <?= $pager->links('default', 'apple_full') ?>
            </div>
        <?php endif; ?>
    </div>
    <!-- #endregion -->
</div>

<!-- Floating Action Bar -->
<div id="floatingActionBar" class="floating-action-bar">
    <div class="floating-action-info">
        <span id="floatingSelectedCount" class="count-badge">0</span>
        <span>Tags selected</span>
    </div>
    <div class="floating-action-divider"></div>
    <a href="#" id="floatingBtnRenew" class="floating-btn-renew" title="Date Extension">
        <i class="bi bi-calendar-check-fill"></i>
        <span>Date Extension</span>
    </a>
    <a href="#" id="floatingBtnUpdateRule" class="floating-btn-secondary" title="Create Update Rule Request">
        <i class="bi bi-pencil-square"></i>
        <span>Update Rule</span>
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.searchable-select').select2({
        width: '100%',
        placeholder: "All Properties",
        allowClear: true
    });

    const btnRenew = document.getElementById('btnRenew');
    const btnUpdateRule = document.getElementById('btnUpdateRule');
    const table = document.getElementById('tblTags');
    const selectAllTags = document.getElementById('selectAllTags');
    const selectedCountBadge = document.getElementById('selectedCountBadge');

    const updateSelectAllState = () => {
        if (!selectAllTags) return;
        const total = table.querySelectorAll('input[name="tag_ids[]"]').length;
        const checked = table.querySelectorAll('input[name="tag_ids[]"]:checked').length;
        
        selectAllTags.checked = (total > 0 && checked === total);
        selectAllTags.indeterminate = (checked > 0 && checked < total);
    };

    const toggleButton = () => {
        const checkedCount = table.querySelectorAll('input[name="tag_ids[]"]:checked').length;
        const anyChecked = checkedCount > 0;
        
        if (btnRenew) btnRenew.style.display = anyChecked ? 'inline-block' : 'none';
        if (btnUpdateRule) btnUpdateRule.style.display = anyChecked ? 'inline-block' : 'none';
        
        if (selectedCountBadge) {
            selectedCountBadge.textContent = `${checkedCount} selected`;
            selectedCountBadge.style.display = anyChecked ? 'inline-block' : 'none';
        }

        const floatingActionBar = document.getElementById('floatingActionBar');
        const floatingSelectedCount = document.getElementById('floatingSelectedCount');
        if (floatingActionBar) {
            if (anyChecked) {
                if (floatingSelectedCount) {
                    floatingSelectedCount.textContent = checkedCount;
                }
                floatingActionBar.classList.add('show');
            } else {
                floatingActionBar.classList.remove('show');
            }
        }

        // Highlight selected rows
        table.querySelectorAll('input[name="tag_ids[]"]').forEach(cb => {
            const row = cb.closest('tr');
            if (row) {
                if (cb.checked) {
                    row.classList.add('selected-row');
                } else {
                    row.classList.remove('selected-row');
                }
            }
        });
    };

    table.addEventListener('change', function (e) {
        if (e.target && e.target.name === 'tag_ids[]') {
            toggleButton();
            updateSelectAllState();
        }
    });

    // Prevent text selection when shift-clicking the table rows
    table.addEventListener('mousedown', function (e) {
        if (e.shiftKey) {
            e.preventDefault();
        }
    });

    let lastChecked = null;

    table.addEventListener('click', function (e) {
        // If clicking on an anchor tag, button, select, label, or an input that is not our tag checkboxes, do not toggle selection
        const isCheckbox = e.target.matches('input[name="tag_ids[]"]');
        if (!isCheckbox && e.target.closest('a, button, select, label, input')) {
            return;
        }
        
        // Find the tr row
        const row = e.target.closest('tr');
        if (row && row.parentNode.tagName === 'TBODY') {
            const cb = row.querySelector('input[name="tag_ids[]"]');
            if (cb) {
                // If they clicked the checkbox directly, it's already toggled by the browser's default action.
                // If they clicked the row (not the checkbox), toggle the checkbox state.
                if (!isCheckbox) {
                    cb.checked = !cb.checked;
                }
                
                // If shift key is held and we have a lastChecked checkbox
                if (e.shiftKey && lastChecked && lastChecked !== cb) {
                    // Clear any text selection that may have occurred
                    window.getSelection().removeAllRanges();
                    
                    const checkboxes = Array.from(table.querySelectorAll('input[name="tag_ids[]"]'));
                    const start = checkboxes.indexOf(cb);
                    const end = checkboxes.indexOf(lastChecked);
                    
                    if (start !== -1 && end !== -1) {
                        const rangeStart = Math.min(start, end);
                        const rangeEnd = Math.max(start, end);
                        
                        for (let i = rangeStart; i <= rangeEnd; i++) {
                            checkboxes[i].checked = cb.checked;
                        }
                    }
                }
                
                // Update lastChecked to the one just clicked
                lastChecked = cb;
                
                // If we clicked the row, we need to dispatch the change event to update styles & buttons
                if (!isCheckbox) {
                    cb.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }
        }
    });

    if (selectAllTags) {
        selectAllTags.addEventListener('change', function () {
            table.querySelectorAll('input[name="tag_ids[]"]').forEach(cb => {
                cb.checked = selectAllTags.checked;
            });
            lastChecked = null;
            toggleButton();
        });
    }

    toggleButton();
    updateSelectAllState();

    const handleRenew = (e) => {
        if (e) e.preventDefault();
        const checked = [...document.querySelectorAll('input[name="tag_ids[]"]:checked')]
            .map(cb => cb.value);
        if (checked.length === 0) return;
        const url = "<?= site_url('tags/renew') ?>?tags=" + checked.join(',');
        window.location.href = url;
    };

    const handleUpdateRule = (e) => {
        if (e) e.preventDefault();
        const checked = [...document.querySelectorAll('input[name="tag_ids[]"]:checked')]
            .map(cb => cb.value);
        if (checked.length === 0) return;
        const url = "<?= site_url('tags/update-rule-request') ?>?tags=" + checked.join(',');
        window.location.href = url;
    };

    if (btnRenew) btnRenew.addEventListener('click', handleRenew);
    if (btnUpdateRule) btnUpdateRule.addEventListener('click', handleUpdateRule);

    const floatingBtnRenew = document.getElementById('floatingBtnRenew');
    const floatingBtnUpdateRule = document.getElementById('floatingBtnUpdateRule');
    if (floatingBtnRenew) floatingBtnRenew.addEventListener('click', handleRenew);
    if (floatingBtnUpdateRule) floatingBtnUpdateRule.addEventListener('click', handleUpdateRule);

    window.copyTagName = function(text, el) {
        const temp = document.createElement('textarea');
        temp.value = text;

        temp.style.position = 'fixed';
        temp.style.left = '-9999px';

        document.body.appendChild(temp);

        temp.focus();
        temp.select();

        document.execCommand('copy');

        document.body.removeChild(temp);

        el.innerHTML = '<i class="bi bi-check-lg text-success fs-7"></i>';

        setTimeout(() => {
            el.innerHTML = '<i class="bi bi-copy text-secondary fs-7"></i>';
        }, 1200);
    };
});

</script>

<?= $this->endSection() ?>
