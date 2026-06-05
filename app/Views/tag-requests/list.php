<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Tag: Requests (New Rule, New Tag)
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    use App\Models\Constants;
    use App\Models\PropertiesModel;
    use App\Models\SubsidiariesModel;
    use App\Models\TagRequestsModel;
    use App\Models\VendorsModel;
    use App\Models\JiraHistoryModel;
    use App\Models\TagsModel;
    helper('custom_functions');

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->WebsiteLinks();
    $propertyOptions = $propertiesModel->Options();

    $subsidiariesModel = new SubsidiariesModel();
    $subsidiaries = $subsidiariesModel->Options();

    $vendorsModel = new VendorsModel();
    $vendors = $vendorsModel->Options();

    $tagsModel = new TagsModel();

    $tagRequestsModel = new TagRequestsModel();

    $newTagRequestsCount = 0;
    $newRuleRequestsCount = 0;

    $activeFiltersCount = 0;
    $advancedFilterKeys = ['subsidiary_id', 'property_id', 'vendor_id', 'type', 'status', 'created_at_from', 'created_at_to'];
    foreach ($advancedFilterKeys as $key) {
        if (!empty($_GET[$key]) || (isset($_GET[$key]) && $_GET[$key] !== '')) {
            $activeFiltersCount++;
        }
    }
    $hasActiveFilters = ($activeFiltersCount > 0);
?>

<?= csrf_field() ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-3">
        <div>
            <?php $totalRequests = isset($pager) ? $pager->getTotal() : count($requests); ?>
            <h2 class="mb-0 fw-bold d-flex align-items-center gap-2">
                <span>Tag Requests</span>
                <span class="badge bg-secondary text-secondary" style="font-size: 0.85rem; font-weight: 600; padding: 0.35em 0.7em; border-radius: 20px;">
                    <?= $totalRequests ?>
                </span>
            </h2>
            <p class="text-secondary small mb-0">Manage and track rules and new vendor tag implementation requests across properties.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('tag-requests/create') ?>" title="Create a new request" class="apple-btn-primary btn-sm px-3 text-decoration-none">
                <i class="bi bi-plus-lg me-1"></i> Create New Request
            </a>
            
            <div class="dropdown">
                <button class="apple-btn-secondary btn-sm dropdown-toggle px-3 d-flex align-items-center gap-1" type="button" id="headerActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i> Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="headerActionsDropdown" style="border-radius: 10px; border: 1px solid var(--color-border-input);">
                    <li>
                        <a class="dropdown-item py-2" href="<?= site_url('tag-requests/upload-excel') ?>" style="font-size: 0.9rem;">
                            <i class="bi bi-file-earmark-excel-fill text-success me-2"></i>Upload Request Form
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="<?= site_url('tag-requests/export?' . http_build_query($_GET)) ?>" style="font-size: 0.9rem;">
                            <i class="bi bi-file-earmark-arrow-down-fill text-secondary me-2"></i>Export as CSV
                        </a>
                    </li>
                </ul>
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
                        placeholder="Search by Ticket ID or keyword..."
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
                    <a href="<?= site_url('tag-requests') ?>" class="apple-btn-secondary btn-sm px-4 text-decoration-none d-inline-flex align-items-center justify-content-center" style="height: 36px; border-radius: 20px;">Clear</a>
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
                        <select name="vendor_id" class="form-select form-select-xs" style="height: 34px; border-radius: 8px;">
                            <option value="">All Vendors</option>
                            <?php foreach ($vendors as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                        <?= old('vendor_id', $filters['vendor_id'] ?? '') == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Type -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Type</label>
                        <select name="type" class="form-select form-select-xs" style="height: 34px; border-radius: 8px;">
                            <option value="">All Types</option>
                            <?php foreach (Constants::REQUEST_TYPES as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('type', $filters['type'] ?? null) == $value ? 'selected' : '' ?>>
                                    <?= esc($label . ' Request') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4 col-lg-4">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Status</label>
                        <select name="status" class="form-select form-select-xs" style="height: 34px; border-radius: 8px;">
                            <option value="">All Statuses</option>
                            <option value="pending" <?= old('status', $filters['status'] ?? null) === 'pending' ? 'selected' : '' ?>>Pending Requests</option>
                            <?php foreach (Constants::STATUS_REQUESTS as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('status', $filters['status'] ?? null) == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Created From -->
                    <div class="col-md-4 col-lg-4">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Created From</label>
                        <div class="position-relative">
                            <input 
                                type="text" 
                                name="created_at_from" 
                                id="created_at_from"
                                class="form-control form-select-xs pe-5" 
                                style="height: 34px; border-radius: 8px;"
                                value="<?= esc($_GET['created_at_from'] ?? '') ?>"
                                placeholder="YYYY-MM-DD"
                            >
                            <i class="bi bi-calendar-event text-muted position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.875rem;"></i>
                        </div>
                    </div>

                    <!-- Created To -->
                    <div class="col-md-4 col-lg-4">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Created To</label>
                        <div class="position-relative">
                            <input 
                                type="text" 
                                name="created_at_to" 
                                id="created_at_to"
                                class="form-control form-select-xs pe-5" 
                                style="height: 34px; border-radius: 8px;"
                                value="<?= esc($_GET['created_at_to'] ?? '') ?>"
                                placeholder="YYYY-MM-DD"
                            >
                            <i class="bi bi-calendar-event text-muted position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.875rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Active Filters Pills -->
    <?php if ($activeFiltersCount > 0): ?>
        <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
            <span class="text-secondary small fw-medium">Active Filters:</span>
            <?php
            foreach ($advancedFilterKeys as $key) {
                if (!empty($_GET[$key]) || (isset($_GET[$key]) && $_GET[$key] !== '')) {
                    $val = $_GET[$key];
                    $label = '';
                    if ($key === 'subsidiary_id') {
                        $label = 'Subsidiary: ' . ($subsidiaries[$val] ?? $val);
                    } elseif ($key === 'property_id') {
                        $label = 'Property: ' . ($propertyOptions[$val] ?? $val);
                    } elseif ($key === 'vendor_id') {
                        $label = 'Vendor: ' . ($vendors[$val] ?? $val);
                    } elseif ($key === 'type') {
                        $label = 'Type: ' . (Constants::REQUEST_TYPES[$val] ?? $val);
                    } elseif ($key === 'status') {
                        if ($val === 'pending') {
                            $label = 'Status: Pending';
                        } else {
                            $label = 'Status: ' . (Constants::STATUS_REQUESTS[$val] ?? $val);
                        }
                    } elseif ($key === 'created_at_from') {
                        $label = 'From: ' . date('d-m-Y', strtotime($val));
                    } elseif ($key === 'created_at_to') {
                        $label = 'To: ' . date('d-m-Y', strtotime($val));
                    }
                    
                    $tempParams = $_GET;
                    unset($tempParams[$key]);
                    $clearUrl = site_url('tag-requests') . '?' . http_build_query($tempParams);
                    ?>
                    <a href="<?= $clearUrl ?>" class="badge bg-secondary text-decoration-none d-inline-flex align-items-center gap-1.5 py-1.5 px-2.5 rounded-pill border border-secondary" style="font-size: 0.8rem; font-weight: 500;">
                        <span><?= esc($label) ?></span>
                        <i class="bi bi-x-circle-fill text-muted" style="font-size: 0.75rem;"></i>
                    </a>
                    <?php
                }
            }
            ?>
        </div>
    <?php endif; ?>
    <!-- #endregion -->

    <!-- #region | TAGS TABLE -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-secondary small fw-medium">
            Showing <?= count($requests) ?> of <?= $totalRequests ?> results
        </span>
    </div>

    <div class="apple-table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tblTags">
            <thead>
                <tr>
                    <th class="form-label-sm fw-semibold">#</th>
                    <th class="form-label-sm fw-semibold">Subsidiary</th>
                    <th class="form-label-sm fw-semibold">Property</th>
                    <th class="form-label-sm fw-semibold">Ticket ID</th>
                    <th class="form-label-sm fw-semibold">Type</th>
                    <th class="form-label-sm fw-semibold">Status</th>
                    <th class="form-label-sm fw-semibold">Created At</th>
                    <th class="form-label-sm fw-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($requests)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 mb-2 d-block text-secondary" style="opacity: 0.5;"></i>
                            <span class="fw-semibold">No requests found</span>
                            <p class="small text-secondary mb-0">Try adjusting your filters or search keywords.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                        foreach ($requests as $request):
                            $subsidiary = Constants::SUBSIDIARIES[$properties[$request['property_id']]['subsidiary_id']];
                            $property = $properties[$request['property_id']];
                            $type = $request['type'];
                            $ticketColumn = 'new_tag_ticket';

                            if ($type == Constants::JIRA_TYPE_NEW_RULE) {
                                $newRuleRequestsCount++;
                                $ticketColumn = 'new_rule_ticket';
                            } else {
                                $newTagRequestsCount++;
                            }
                    ?>
                        <tr>
                            <!-- ID -->
                            <td><?= $request['id'] ?></td>
                            <!-- Subsidiary -->
                            <td>
                                <span class="badge" style="<?= random_bg_style($subsidiary) ?>">
                                    <?= esc($subsidiary) ?>
                                </span>
                            </td>
                            <!-- Property -->
                            <td>
                                <i class="bi bi-tag-fill" style="<?= random_text_style($property['name']) ?>"></i>
                                <a href="<?= $property['ref_link'] ?>" target="_blank" rel="noopener" class="apple-link-dark form-label-sm ms-1">
                                    <?= $property['name'] ?>
                                </a>
                            </td>
                            <td>
                                <a href="<?= Constants::JIRA_LINK . $request[$ticketColumn] ?>" target="_blank" rel="noopener" class="apple-link">
                                    <?= $request[$ticketColumn] ?>
                                </a>
                                <span class="text-muted ms-1" style="font-size: 0.8rem;">(<?= $vendors[$request['vendor_id']] ?? '' ?>)</span>
                                <?php if ($type == Constants::JIRA_TYPE_NEW_TAG && !empty($request['new_rule_ticket'])) : ?>
                                    <br/>
                                    <small class="text-muted" style="font-size: 0.75rem;">New Rule:</small>
                                    <a href="<?= Constants::JIRA_LINK . $request['new_rule_ticket'] ?>" target="_blank" rel="noopener" class="apple-link" style="font-size: 0.85rem;">
                                        <?= $request['new_rule_ticket'] ?>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= Constants::REQUEST_TYPES[$type] ?>
                            </td>
                            <td>
                                <span class="badge <?= status_request_class($request['status']) ?>">
                                    <?= Constants::STATUS_REQUESTS[$request['status']] ?>
                                </span>
                            </td>
                            <td class="form-label-sm" style="width:max-content; white-space:nowrap;"><?= esc(date('d-m-Y', strtotime($request['created_at']))) ?></td>
                            <!-- Actions -->
                            <td class="text-nowrap">
                                <div class="dropdown">
                                    <button class="apple-btn-secondary btn-sm dropdown-toggle px-2.5 py-1 d-inline-flex align-items-center gap-1" type="button" id="actionsDropdown_<?= $request['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.8rem; border-radius: 9999px;">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="actionsDropdown_<?= $request['id'] ?>" style="border-radius: 10px; border: 1px solid var(--color-border-input); z-index: 1050;">
                                        <li>
                                            <a class="dropdown-item py-2" href="<?= site_url('tag-requests/view/'.$request['id']) ?>" style="font-size: 0.85rem;">
                                                <i class="bi bi-eye text-primary me-2"></i>View Details
                                            </a>
                                        </li>
                                        <?php if (!empty($request['new_rule_ticket'])): ?>
                                            <li>
                                                <a class="dropdown-item py-2" href="<?= site_url('tag-requests/new-rule-request/' . $request['id']) ?>" style="font-size: 0.85rem;">
                                                    <i class="bi bi-file-earmark-text text-info me-2"></i>View Rule Request
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($request['status'] == Constants::STATUS_REQUEST_NEW_RULE_REQUEST_SUBMITTED ||
                                                ($request['status'] == Constants::STATUS_REQUEST_HQ_REVIEW && $request['type'] == Constants::JIRA_TYPE_NEW_RULE)): ?>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item py-2 text-success" href="<?= site_url('tag-requests/request-approved/' . $request['id']) ?>"
                                                   onclick="return confirm('Confirm that the tags are published live? (This will set tags to ENABLED status)');"
                                                   style="font-size: 0.85rem; font-weight: 500;">
                                                    <i class="bi bi-check-circle-fill text-success me-2"></i>Publish Tags
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
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

<!--    <div class="d-flex justify-content-start gap-2 form-label-xs">-->
        <!--        <p>New Tag Requests: <span class="text-dark fw-semibold">--><?php //= $tagRequestsModel->countNewTags() ?><!--</span></p>-->
        <!--        <p>New Rule Requests: <span class="text-dark fw-semibold">--><?php //= $tagRequestsModel->countNewRules() ?><!--</span></p>-->
        <!--        <p>Total: <span class="text-dark fw-semibold">--><?php //= $tagRequestsModel->countAllRequests() ?><!--</span></p>-->

<!--        <p>New Tag Requests: <span class="text-dark fw-semibold">--><?php //= $newTagRequestsCount ?><!--</span></p>-->
<!--        <p>New Rule Requests: <span class="text-dark fw-semibold">--><?php //= $newRuleRequestsCount ?><!--</span></p>-->
<!--        <p>Total: <span class="text-dark fw-semibold">--><?php //= $newTagRequestsCount + $newRuleRequestsCount ?><!--</span></p>-->
<!--    </div>-->
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#created_at_from, #created_at_to", {
        dateFormat: "Y-m-d",
        disableMobile: "true"
    });

    $('.searchable-select').select2({
        width: '100%',
        placeholder: "All Properties",
        allowClear: true
    });

    const btnRenew = document.getElementById('btnRenew');
    const table = document.getElementById('tblTags');

    const toggleButton = () => {
        if (!btnRenew || !table) return;
        const anyChecked = table.querySelectorAll('input[type="checkbox"]:checked').length > 0;
        btnRenew.style.display = anyChecked ? 'inline-block' : 'none';
    };

    if (table) {
        table.addEventListener('change', function (e) {
            if (e.target && e.target.type === 'checkbox') {
                toggleButton();
            }
        });
    }

    toggleButton();

    if (btnRenew) {
        btnRenew.addEventListener('click', function (e) {
            e.preventDefault();

            const checked = [...document.querySelectorAll('input[name="tag_ids[]"]:checked')]
                .map(cb => cb.value);

            if (checked.length === 0) return;

            const url = "<?= site_url('tags/renew') ?>?tags=" + checked.join(',');
            window.open(url, "_blank");
        });
    }

    window.copyTagName = function(text, el) {
        navigator.clipboard.writeText(text).then(() => {
            el.innerHTML = '<i class="bi bi-check-lg text-success fs-7"></i>';
            setTimeout(() => {
                el.innerHTML = '<i class="bi bi-copy text-secondary fs-7"></i>';
            }, 1200);
        });
    };
});

</script>

<?= $this->endSection() ?>
