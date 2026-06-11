<?php
function getSortUrl($field, $currentSortBy, $currentSortDir) {
    $queryParams = $_GET;
    $queryParams['sort_by'] = $field;
    if ($currentSortBy === $field) {
        $queryParams['sort_dir'] = ($currentSortDir === 'asc') ? 'desc' : 'asc';
    } else {
        $queryParams['sort_dir'] = 'asc';
    }
    return site_url('tags') . '?' . http_build_query($queryParams);
}

function getSortIcon($field, $currentSortBy, $currentSortDir) {
    if ($currentSortBy === $field) {
        return $currentSortDir === 'asc' ? ' <i class="bi bi-arrow-up text-primary"></i>' : ' <i class="bi bi-arrow-down text-primary"></i>';
    }
    return ' <i class="bi bi-arrow-down-up text-muted" style="font-size: 0.75rem; opacity: 0.4;"></i>';
}
?>
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
    white-space: nowrap;
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
.floating-btn-verify {
    background: #34c759;
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
    white-space: nowrap;
    transition: background-color 0.2s, transform 0.2s;
}
.floating-btn-verify:hover {
    background: #30b852;
    color: #ffffff;
    transform: scale(1.02);
}
.floating-btn-verify:active {
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
    white-space: nowrap;
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

/* Action Dropdown Style Experiment */
.actions-cell {
    position: relative;
    width: 90px;
    min-width: 90px;
    text-align: center;
    padding-left: 4px !important;
    padding-right: 4px !important;
}

#tblTags tbody td.actions-cell,
#tblTags tbody td:not(.actions-cell):has(a:not([onclick]):not(.text-muted)) {
    cursor: pointer;
}

#tblTags tbody td:not(.actions-cell):has(a:not([onclick]):not(.text-muted)):hover a:not([onclick]):not(.text-muted) {
    color: var(--color-primary) !important;
}

.action-style-original-wrapper,
.action-style-glassmorphic-wrapper,
.action-style-hover-inline-wrapper {
    display: none;
}

#tblTags.style-original .action-style-original-wrapper {
    display: block;
}

#tblTags.style-glassmorphic .action-style-glassmorphic-wrapper {
    display: block;
}

#tblTags.style-hover-inline .action-style-hover-inline-wrapper {
    display: flex;
    justify-content: center;
}

/* 1. Sleek Minimalist Dot Trigger Styling */
.action-trigger-dot {
    background: transparent;
    border: none;
    color: var(--color-text-muted);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    transition: all 0.2s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.action-trigger-dot:hover, 
.action-trigger-dot[aria-expanded="true"] {
    background-color: var(--color-secondary-bg);
    color: var(--color-text-base);
    transform: scale(1.05);
}
.action-trigger-dot:active {
    transform: scale(0.95);
}

/* 2. Glassmorphic Dropdown Styling */
.glassmorphic-menu {
    background: rgba(255, 255, 255, 0.82) !important;
    backdrop-filter: blur(12px) saturate(180%) !important;
    -webkit-backdrop-filter: blur(12px) saturate(180%) !important;
    border: 1px solid rgba(0, 0, 0, 0.08) !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
    border-radius: 12px !important;
    padding: 6px !important;
    transform-origin: top right;
    animation: dropdownFadeIn 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes dropdownFadeIn {
    from {
        opacity: 0;
        transform: scale(0.96) translateY(-4px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.glassmorphic-menu .dropdown-item {
    border-radius: 8px !important;
    padding: 6px 12px !important;
    font-size: 0.85rem !important;
    font-weight: 500 !important;
    transition: background-color 0.15s ease, color 0.15s ease !important;
    color: var(--color-text-base) !important;
}

.glassmorphic-menu .dropdown-item:hover {
    background-color: var(--color-primary) !important;
    color: #ffffff !important;
}
/* Ensure standard Bootstrap icons inside dropdown inherit color changes */
.glassmorphic-menu .dropdown-item:hover i {
    color: #ffffff !important;
}

/* 3. Hover-Revealed Inline Action Buttons Styling */
.hover-actions-container {
    opacity: 0;
    transform: translateX(6px);
    transition: opacity 0.2s ease, transform 0.2s ease;
}

/* When the table row is hovered, show the actions */
#tblTags tr:hover .hover-actions-container {
    opacity: 1;
    transform: translateX(0);
}

/* Also show them if select check is active or focus is inside */
#tblTags tr:focus-within .hover-actions-container,
#tblTags tr.selected-row .hover-actions-container {
    opacity: 1;
    transform: translateX(0);
}

.hover-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    color: var(--color-text-muted);
    background-color: var(--color-secondary-bg);
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    text-decoration: none !important;
    font-size: 0.75rem;
}

.hover-action-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.hover-action-btn:active {
    transform: scale(0.95);
}

.hover-action-btn.btn-view:hover {
    background-color: var(--color-badge-primary-bg) !important;
    color: var(--color-badge-primary-text) !important;
}

.hover-action-btn.btn-update:hover {
    background-color: var(--color-badge-warning-bg) !important;
    color: var(--color-badge-warning-text) !important;
}

.hover-action-btn.btn-verify:hover {
    background-color: var(--color-badge-success-bg) !important;
    color: var(--color-badge-success-text) !important;
}

/* Micro Segmented Control Styling */
.micro-segmented-control {
    background-color: var(--color-secondary-bg);
    border-radius: 20px;
    padding: 3px;
    display: inline-flex;
    gap: 2px;
    border: 1px solid rgba(0, 0, 0, 0.03);
}

.micro-segmented-control button {
    border: none;
    background: transparent;
    padding: 4px 12px;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    border-radius: 15px;
    transition: all 0.2s ease;
    cursor: pointer;
    outline: none;
}

.micro-segmented-control button:hover {
    color: var(--color-text-base);
}

.micro-segmented-control button.active {
    background-color: var(--color-card-bg);
    color: var(--color-text-base);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
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

    $vendorsModel = new \App\Models\VendorsModel();
    $vendors = $vendorsModel->Options();

    $tagsModel = new TagsModel();

    $activeFiltersCount = 0;
    $advancedFilterKeys = ['subsidiary_id', 'property_id', 'vendor_id', 'status', 'al_end_date_from', 'al_end_date_to'];
    foreach ($advancedFilterKeys as $key) {
        if ($key === 'status') {
            $statusVal = $_GET['status'] ?? null;
            if (!empty($statusVal) && $statusVal !== 'all' && $statusVal !== '145') {
                $activeFiltersCount++;
            }
        } else {
            if (!empty($_GET[$key]) || (isset($_GET[$key]) && $_GET[$key] !== '')) {
                $activeFiltersCount++;
            }
        }
    }
    $hasActiveFilters = ($activeFiltersCount > 0);
?>

<?= csrf_field() ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-3">
        <div>
            <?php $totalTags = isset($pager) ? $pager->getTotal() : count($tags); ?>
            <h2 class="mb-0 fw-bold d-flex align-items-center gap-2">
                <span>Tag Inventory</span>
                <span class="badge bg-secondary text-secondary" style="font-size: 0.85rem; font-weight: 600; padding: 0.35em 0.7em; border-radius: 20px;">
                    <?= $totalTags ?>
                </span>
                <span id="selectedCountBadge" class="badge bg-primary text-white" style="display: none; font-size: 0.85rem; font-weight: 500; padding: 0.35em 0.7em; border-radius: 20px;">
                    0 selected
                </span>
            </h2>
            <p class="text-secondary small mb-0">Manage and monitor marketing tag configurations, Adobe Launch rules, and expiration schedules.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('tags/create') ?>" title="Add Tag Information" class="apple-btn-primary btn-sm px-3 text-decoration-none">
                <i class="bi bi-plus-lg me-1"></i> Add Tag
            </a>
            
            <div class="dropdown">
                <button class="apple-btn-secondary btn-sm dropdown-toggle px-3 d-flex align-items-center gap-1" type="button" id="headerActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i> Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="headerActionsDropdown" style="border-radius: 10px; border: 1px solid var(--color-border-input);">
                    <li>
                        <a class="dropdown-item py-2 text-success" href="#" id="btnVerify" style="display: none; font-size: 0.9rem; font-weight: 500;">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>Verify Tag
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="#" id="btnRenew" style="display: none; font-size: 0.9rem;">
                            <i class="bi bi-calendar-check-fill text-primary me-2"></i>Date Extension
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="#" id="btnUpdateRule" style="display: none; font-size: 0.9rem;">
                            <i class="bi bi-pencil-square text-primary me-2"></i>Update Rule
                        </a>
                    </li>
                    <li id="menuBulkDivider" style="display: none;"><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item py-2" href="<?= site_url('tags/upload-renewal-justifications') ?>" style="font-size: 0.9rem;">
                            <i class="bi bi-file-earmark-excel-fill text-danger me-2"></i>Update Renewal Justifications
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="<?= site_url('tags/export?' . http_build_query($_GET)) ?>" style="font-size: 0.9rem;">
                            <i class="bi bi-file-earmark-arrow-down-fill text-secondary me-2"></i>Export as CSV
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
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
    </div>

    <!-- #region | SEARCH FILTER -->
    <form method="get" class="m-0" id="filterForm">
        <!-- Primary Search Bar -->
        <div class="apple-control-bar mb-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Search Input -->
                <div class="position-relative flex-grow-1" style="min-width: 250px;">
                    <i class="bi bi-search text-muted position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); font-size: 0.9rem;"></i>
                    <input 
                        type="text" 
                        name="keyword" 
                        class="form-control form-control-xs" 
                        style="height: 36px; padding-left: 36px !important; border-radius: 20px;"
                        placeholder="Search tags..."
                        value="<?= esc($_GET['keyword'] ?? '') ?>"
                    >
                </div>
                
                <!-- Toggle Filters Button -->
                <button type="button" class="apple-btn-secondary btn-sm d-inline-flex align-items-center gap-2" 
                        style="height: 36px; border-radius: 20px; font-weight: 500;" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#advancedFiltersCollapse" 
                        aria-expanded="<?= $hasActiveFilters ? 'true' : 'false' ?>" 
                        aria-controls="advancedFiltersCollapse">
                    <i class="bi bi-funnel"></i>
                    <span>Filters</span>
                    <?php if ($activeFiltersCount > 0): ?>
                        <span class="badge bg-primary text-white rounded-circle px-1.5 py-0.5" style="font-size: 0.75rem; min-width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center;">
                            <?= $activeFiltersCount ?>
                        </span>
                    <?php endif; ?>
                </button>

                <!-- Action Buttons -->
                <button type="submit" class="apple-btn-primary btn-sm px-4" style="height: 36px; border-radius: 20px;">Apply</button>
                <?php if (!empty($_GET)): ?>
                    <a href="<?= site_url('tags') ?>" class="apple-btn-secondary btn-sm px-4 text-decoration-none d-inline-flex align-items-center justify-content-center" style="height: 36px; border-radius: 20px;">Clear</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Collapsible Advanced Filters Panel -->
        <div class="collapse <?= $hasActiveFilters ? 'show' : '' ?> mb-4" id="advancedFiltersCollapse">
            <div class="apple-card" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none; padding: 20px;">
                <h5 class="fw-bold mb-3 text-secondary d-flex align-items-center gap-2" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="bi bi-sliders text-primary"></i> Advanced Filters
                </h5>
                <div class="row g-3">
                    <!-- Subsidiary -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Subsidiary</label>
                        <select name="subsidiary_id" class="form-select form-select-xs" style="height: 34px; border-radius: 8px;">
                            <option value="">All Subsidiaries</option>
                            <?php foreach ($subsidiaries as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('subsidiary_id', $filters['subsidiary_id'] ?? '') == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Property -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Property</label>
                        <select name="property_id" class="form-select form-select-xs searchable-select">
                            <option value="">All Properties</option>
                            <?php foreach ($propertyOptions as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('property_id', $filters['property_id'] ?? '') == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Vendor -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Vendor</label>
                        <select name="vendor_id" class="form-select form-select-xs searchable-select-vendor">
                            <option value="">All Vendors</option>
                            <?php foreach ($vendors as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('vendor_id', $filters['vendor_id'] ?? '') == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Status</label>
                        <select name="status" class="form-select form-select-xs" style="height: 34px; border-radius: 8px;">
                            <option value="<?= Constants::STATUS_ALL ?>" <?= (old('status', $filters['status'] ?? null) == Constants::STATUS_ALL || empty($filters['status']) || $filters['status'] == 145) ? 'selected' : '' ?>>All Statuses</option>
                            <?php foreach (Constants::STATUS as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= (old('status', $filters['status'] ?? null) == $value && $filters['status'] != 145) ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Expiry From -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Expiry From</label>
                        <div class="position-relative">
                            <input 
                                type="text" 
                                name="al_end_date_from" 
                                id="al_end_date_from"
                                class="form-control form-select-xs pe-5" 
                                style="height: 34px; border-radius: 8px;"
                                value="<?= esc($_GET['al_end_date_from'] ?? '') ?>"
                                placeholder="YYYY-MM-DD"
                            >
                            <i class="bi bi-calendar-event text-muted position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.875rem;"></i>
                        </div>
                    </div>

                    <!-- Expiry To -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Expiry To</label>
                        <div class="position-relative">
                            <input 
                                type="text" 
                                name="al_end_date_to" 
                                id="al_end_date_to"
                                class="form-control form-select-xs pe-5" 
                                style="height: 34px; border-radius: 8px;"
                                value="<?= esc($_GET['al_end_date_to'] ?? '') ?>"
                                placeholder="YYYY-MM-DD"
                            >
                            <i class="bi bi-calendar-event text-muted position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.875rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="sort_by" value="<?= esc($filters['sort_by'] ?? '') ?>">
        <input type="hidden" name="sort_dir" value="<?= esc($filters['sort_dir'] ?? 'desc') ?>">
    </form>

    <!-- Active Filters Pills -->
    <?php if ($activeFiltersCount > 0): ?>
        <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
            <span class="text-secondary small fw-medium">Active Filters:</span>
            <?php
            foreach ($advancedFilterKeys as $key) {
                if ($key === 'status') {
                    $val = $_GET['status'] ?? null;
                    if (empty($val) || $val === 'all' || $val === '145') {
                        continue;
                    }
                    $label = 'Status: ' . (Constants::STATUS[$val] ?? $val);
                } elseif ($key === 'al_end_date_from') {
                    $val = $_GET['al_end_date_from'] ?? null;
                    if (empty($val)) continue;
                    $label = 'Expiry From: ' . date('d-m-Y', strtotime($val));
                } elseif ($key === 'al_end_date_to') {
                    $val = $_GET['al_end_date_to'] ?? null;
                    if (empty($val)) continue;
                    $label = 'Expiry To: ' . date('d-m-Y', strtotime($val));
                } else {
                    if (empty($_GET[$key]) && (!isset($_GET[$key]) || $_GET[$key] === '')) {
                        continue;
                    }
                    $val = $_GET[$key];
                    if ($key === 'subsidiary_id') {
                        $label = 'Subsidiary: ' . ($subsidiaries[$val] ?? $val);
                    } elseif ($key === 'property_id') {
                        $label = 'Property: ' . ($propertyOptions[$val] ?? $val);
                    } elseif ($key === 'vendor_id') {
                        $label = 'Vendor: ' . ($vendors[$val] ?? $val);
                    }
                }
                
                $tempParams = $_GET;
                unset($tempParams[$key]);
                $clearUrl = site_url('tags') . '?' . http_build_query($tempParams);
                ?>
                <a href="<?= $clearUrl ?>" class="badge bg-secondary text-decoration-none d-inline-flex align-items-center gap-1.5 py-1.5 px-2.5 rounded-pill border border-secondary" style="font-size: 0.8rem; font-weight: 500;">
                    <span><?= esc($label) ?></span>
                    <i class="bi bi-x-circle-fill text-muted" style="font-size: 0.75rem;"></i>
                </a>
                <?php
            }
            ?>
        </div>
    <?php endif; ?>
    <!-- #endregion -->

    <!-- #region | TAGS TABLE -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <span class="text-secondary small fw-medium">
            Showing <?= count($tags) ?> of <?= $totalTags ?> results
        </span>
        
        <!-- Action Style Switcher -->
        <div class="d-flex align-items-center gap-2">
            <span class="text-secondary" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.8;">Action Style:</span>
            <div class="micro-segmented-control" id="actionStyleControl">
                <button type="button" data-style="original">Original</button>
                <button type="button" data-style="glassmorphic">Glassmorphic</button>
                <button type="button" data-style="hover-inline">Hover Inline</button>
            </div>
        </div>
    </div>

    <div class="apple-table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 style-glassmorphic" id="tblTags">
            <thead>
                <tr>
                    <?php
                    $currentSortBy = $filters['sort_by'] ?? '';
                    $currentSortDir = $filters['sort_dir'] ?? 'desc';
                    ?>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('id', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                            # <?= getSortIcon('id', $currentSortBy, $currentSortDir) ?>
                        </a>
                    </th>
                    <th><input type="checkbox" id="selectAllTags" title="Select All" class="form-check-input"></th>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('subsidiary_id', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                            Subsidiary <?= getSortIcon('subsidiary_id', $currentSortBy, $currentSortDir) ?>
                        </a>
                    </th>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('property_id', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                            Property <?= getSortIcon('property_id', $currentSortBy, $currentSortDir) ?>
                        </a>
                    </th>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('name', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                            Name <?= getSortIcon('name', $currentSortBy, $currentSortDir) ?>
                        </a>
                    </th>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('status', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                            Status <?= getSortIcon('status', $currentSortBy, $currentSortDir) ?>
                        </a>
                    </th>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('days', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                            Days <?= getSortIcon('days', $currentSortBy, $currentSortDir) ?>
                        </a>
                    </th>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('al_end_date', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                            End Date <?= getSortIcon('al_end_date', $currentSortBy, $currentSortDir) ?>
                        </a>
                    </th>
                    <th class="form-label-sm fw-semibold">
                        <a href="<?= getSortUrl('updated_at', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                            Updated <?= getSortIcon('updated_at', $currentSortBy, $currentSortDir) ?>
                        </a>
                    </th>
                    <th class="form-label-sm fw-semibold text-dark text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tags)): ?>
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 mb-2 d-block text-secondary" style="opacity: 0.5;"></i>
                            <span class="fw-semibold">No tags found</span>
                            <p class="small text-secondary mb-0">Try adjusting your filters or search keywords.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                        foreach ($tags as $tag): 
                        $ticketId = (new JiraHistoryModel())->getRelatedTagDateExtension($tag['id'])['ticket_id'] ?? null;
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
                                data-status="<?= htmlspecialchars($tag['status']) ?>"
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
                            <a href="<?= $properties[$tag['property_id']]['ref_link'] ?>" target="_blank" rel="noopener" class="apple-link-dark form-label-sm ms-1">
                                <?= $properties[$tag['property_id']]['name'] ?>
                            </a>
                        </td>
                        <!-- Name -->
                        <td class="form-label-sm">
                            <span class="group-hover-container align-items-center">
                                <a href="<?= $tag['ref_link'] ?>" target="_blank" rel="noopener" class="apple-link-dark">
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
                            <?php if (($tag['status'] == Constants::STATUS_PENDING_VERIFICATION || $tag['status'] == Constants::STATUS_ON_HOLD) && $ticketId != null): ?>
                                <br>
                                <a href="<?= Constants::JIRA_LINK . $ticketId ?>" target="_blank" rel="noopener" class="apple-link ms-1" style="font-size: 0.8rem;">
                                    <?= $ticketId ?>
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
                        <td class="text-nowrap actions-cell">
                            <!-- Original Style -->
                            <div class="action-style-original-wrapper">
                                <div class="dropdown">
                                    <button class="apple-btn-secondary btn-sm dropdown-toggle px-2.5 py-1 d-inline-flex align-items-center gap-1" type="button" id="actionsDropdown_<?= $tag['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.8rem; border-radius: 9999px;">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="actionsDropdown_<?= $tag['id'] ?>" style="border-radius: 10px; border: 1px solid var(--color-border-input); z-index: 1050;">
                                        <li>
                                            <a class="dropdown-item py-2" href="<?= site_url('tags/view/'.$tag['id']) ?>" style="font-size: 0.85rem;">
                                                <i class="bi bi-eye text-primary me-2"></i>View Details
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-2" href="<?= site_url('tags/update/'.$tag['id']) ?>" style="font-size: 0.85rem;">
                                                <i class="bi bi-pencil-square text-warning me-2"></i>Update Details
                                            </a>
                                        </li>
                                        <?php if ($tag['status'] == Constants::STATUS_PENDING_VERIFICATION): ?>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item py-2 text-success" href="<?= site_url('tags/verify/' . $tag['id']) ?>"
                                                   onclick="return confirm('Have you verified the end date matches in AL?');"
                                                   style="font-size: 0.85rem; font-weight: 500;">
                                                    <i class="bi bi-check-circle-fill text-success me-2"></i>Verify Tag
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>

                            <!-- Style A: Glassmorphic Dropdown -->
                            <div class="action-style-glassmorphic-wrapper">
                                <div class="dropdown">
                                    <button class="action-trigger-dot d-inline-flex align-items-center justify-content-center" type="button" id="actionsDropdownA_<?= $tag['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end glassmorphic-menu shadow" aria-labelledby="actionsDropdownA_<?= $tag['id'] ?>" style="z-index: 1050;">
                                        <li>
                                            <a class="dropdown-item py-2" href="<?= site_url('tags/view/'.$tag['id']) ?>">
                                                <i class="bi bi-eye text-primary me-2"></i>View Details
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-2" href="<?= site_url('tags/update/'.$tag['id']) ?>">
                                                <i class="bi bi-pencil-square text-warning me-2"></i>Update Details
                                            </a>
                                        </li>
                                        <?php if ($tag['status'] == Constants::STATUS_PENDING_VERIFICATION): ?>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item py-2 text-success" href="<?= site_url('tags/verify/' . $tag['id']) ?>"
                                                   onclick="return confirm('Have you verified the end date matches in AL?');"
                                                   style="font-weight: 500;">
                                                    <i class="bi bi-check-circle-fill text-success me-2"></i>Verify Tag
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>

                            <!-- Style B: Hover-Revealed Inline Actions -->
                            <div class="action-style-hover-inline-wrapper">
                                <div class="hover-actions-container d-inline-flex align-items-center gap-1">
                                    <a href="<?= site_url('tags/view/'.$tag['id']) ?>" class="hover-action-btn btn-view" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="<?= site_url('tags/update/'.$tag['id']) ?>" class="hover-action-btn btn-update" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Details">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <?php if ($tag['status'] == Constants::STATUS_PENDING_VERIFICATION): ?>
                                        <a href="<?= site_url('tags/verify/' . $tag['id']) ?>" class="hover-action-btn btn-verify" data-bs-toggle="tooltip" data-bs-placement="top" title="Verify Tag" onclick="return confirm('Have you verified the end date matches in AL?');">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>
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
    <a href="#" id="floatingBtnVerify" class="floating-btn-verify" title="Verify Tags" style="display: none;">
        <i class="bi bi-check-circle-fill"></i>
        <span>Verify</span>
    </a>
    <a href="#" id="floatingBtnRenew" class="floating-btn-renew" title="Date Extension">
        <i class="bi bi-calendar-check-fill"></i>
        <span>Extend</span>
    </a>
    <a href="#" id="floatingBtnUpdateRule" class="floating-btn-secondary" title="Create Update Rule Request">
        <i class="bi bi-pencil-square"></i>
        <span>Rule Update</span>
    </a>
    <a href="#" id="floatingBtnDeselect" class="floating-btn-secondary" title="Deselect All">
        <i class="bi bi-x-circle"></i>
        <span>Deselect</span>
    </a>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#al_end_date_from, #al_end_date_to", {
        dateFormat: "Y-m-d",
        disableMobile: "true"
    });

    $('.searchable-select').select2({
        width: '100%',
        placeholder: "All Properties",
        allowClear: true
    });

    $('.searchable-select-vendor').select2({
        width: '100%',
        placeholder: "All Vendors",
        allowClear: true
    });

    const btnRenew = document.getElementById('btnRenew');
    const btnUpdateRule = document.getElementById('btnUpdateRule');
    const btnVerify = document.getElementById('btnVerify');
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
        
        const pendingCheckedCount = [...table.querySelectorAll('input[name="tag_ids[]"]:checked')]
            .filter(cb => cb.getAttribute('data-status') === '4').length;
        const anyPendingChecked = pendingCheckedCount > 0;
        
        if (btnRenew) btnRenew.style.display = anyChecked ? 'block' : 'none';
        if (btnUpdateRule) btnUpdateRule.style.display = anyChecked ? 'block' : 'none';
        if (btnVerify) btnVerify.style.display = anyPendingChecked ? 'block' : 'none';
        
        const menuBulkDivider = document.getElementById('menuBulkDivider');
        if (menuBulkDivider) {
            menuBulkDivider.style.display = anyChecked ? 'block' : 'none';
        }
        
        if (selectedCountBadge) {
            selectedCountBadge.textContent = `${checkedCount} selected`;
            selectedCountBadge.style.display = anyChecked ? 'inline-block' : 'none';
        }

        const floatingActionBar = document.getElementById('floatingActionBar');
        const floatingSelectedCount = document.getElementById('floatingSelectedCount');
        const floatingBtnVerify = document.getElementById('floatingBtnVerify');
        if (floatingActionBar) {
            if (anyChecked) {
                if (floatingSelectedCount) {
                    floatingSelectedCount.textContent = checkedCount;
                }
                if (floatingBtnVerify) {
                    floatingBtnVerify.style.display = anyPendingChecked ? 'flex' : 'none';
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
        const isCheckbox = e.target.matches('input[name="tag_ids[]"]');

        const td = e.target.closest('td');
        if (td) {
            const clickedLinkOrBtn = e.target.closest('a, button, select, label, input');
            
            if (clickedLinkOrBtn && clickedLinkOrBtn !== e.target.closest('input[name="tag_ids[]"]')) {
                return;
            }
            
            // Redirect Actions Column cell clicks to target buttons
            if (td.classList.contains('actions-cell')) {
                if (!clickedLinkOrBtn) {
                    let activeStyle = 'glassmorphic';
                    if (table.classList.contains('style-original')) {
                        activeStyle = 'original';
                    } else if (table.classList.contains('style-hover-inline')) {
                        activeStyle = 'hover-inline';
                    }
                    
                    const wrapper = td.querySelector(`.action-style-${activeStyle}-wrapper`);
                    if (wrapper) {
                        const toggleBtn = wrapper.querySelector('[data-bs-toggle="dropdown"]');
                        if (toggleBtn) {
                            toggleBtn.click();
                        } else {
                            const viewBtn = wrapper.querySelector('.hover-action-btn.btn-view');
                            if (viewBtn) viewBtn.click();
                        }
                    }
                }
                return;
            }

            // Redirect clicks inside cells containing primary links (Name, Property, etc.)
            const primaryLink = td.querySelector('a:not([onclick]):not(.text-muted)');
            if (primaryLink) {
                if (!clickedLinkOrBtn) {
                    if (primaryLink.target === '_blank') {
                        window.open(primaryLink.href, '_blank');
                    } else {
                        primaryLink.click();
                    }
                }
                return;
            }
        }
        
        const row = e.target.closest('tr');
        if (row && row.parentNode.tagName === 'TBODY') {
            const cb = row.querySelector('input[name="tag_ids[]"]');
            if (cb) {
                if (!isCheckbox) {
                    cb.checked = !cb.checked;
                }
                
                if (e.shiftKey && lastChecked && lastChecked !== cb) {
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
                
                lastChecked = cb;
                
                cb.dispatchEvent(new Event('change', { bubbles: true }));
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

    const handleVerify = (e) => {
        if (e) e.preventDefault();
        const checkedPending = [...document.querySelectorAll('input[name="tag_ids[]"]:checked')]
            .filter(cb => cb.getAttribute('data-status') === '4')
            .map(cb => cb.value);
            
        if (checkedPending.length === 0) return;
        
        const msg = `Have you verified the end date matches in AL for the selected ${checkedPending.length} tag(s)?`;
        if (confirm(msg)) {
            const url = "<?= site_url('tags/verify-multiple') ?>?tags=" + checkedPending.join(',');
            window.location.href = url;
        }
    };

    if (btnRenew) btnRenew.addEventListener('click', handleRenew);
    if (btnUpdateRule) btnUpdateRule.addEventListener('click', handleUpdateRule);
    if (btnVerify) btnVerify.addEventListener('click', handleVerify);

    const floatingBtnRenew = document.getElementById('floatingBtnRenew');
    const floatingBtnUpdateRule = document.getElementById('floatingBtnUpdateRule');
    const floatingBtnVerify = document.getElementById('floatingBtnVerify');
    if (floatingBtnRenew) floatingBtnRenew.addEventListener('click', handleRenew);
    if (floatingBtnUpdateRule) floatingBtnUpdateRule.addEventListener('click', handleUpdateRule);
    if (floatingBtnVerify) floatingBtnVerify.addEventListener('click', handleVerify);

    const floatingBtnDeselect = document.getElementById('floatingBtnDeselect');
    if (floatingBtnDeselect) {
        floatingBtnDeselect.addEventListener('click', function(e) {
            e.preventDefault();
            table.querySelectorAll('input[name="tag_ids[]"]').forEach(cb => {
                cb.checked = false;
            });
            if (selectAllTags) {
                selectAllTags.checked = false;
                selectAllTags.indeterminate = false;
            }
            toggleButton();
            updateSelectAllState();
        });
    }

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

    // Action Style Switcher logic
    const actionStyleControl = document.getElementById('actionStyleControl');
    const tblTags = document.getElementById('tblTags');
    if (actionStyleControl && tblTags) {
        const savedStyle = localStorage.getItem('tag_action_style') || 'glassmorphic';
        tblTags.classList.remove('style-original', 'style-glassmorphic', 'style-hover-inline');
        tblTags.classList.add(`style-${savedStyle}`);
        
        actionStyleControl.querySelectorAll('button').forEach(btn => {
            if (btn.dataset.style === savedStyle) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
        
        actionStyleControl.addEventListener('click', function (e) {
            const btn = e.target.closest('button[data-style]');
            if (!btn) return;
            
            const selectedStyle = btn.dataset.style;
            actionStyleControl.querySelectorAll('button').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            tblTags.classList.remove('style-original', 'style-glassmorphic', 'style-hover-inline');
            tblTags.classList.add(`style-${selectedStyle}`);
            
            localStorage.setItem('tag_action_style', selectedStyle);
        });
    }
});
</script>
<?= $this->endSection() ?>
