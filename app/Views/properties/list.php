<?php
function getSortUrl($field, $currentSortBy, $currentSortDir) {
    $queryParams = $_GET;
    $queryParams['sort_by'] = $field;
    if ($currentSortBy === $field) {
        $queryParams['sort_dir'] = ($currentSortDir === 'asc') ? 'desc' : 'asc';
    } else {
        $queryParams['sort_dir'] = 'asc';
    }
    return site_url('properties') . '?' . http_build_query($queryParams);
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
Properties List
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    use App\Models\SubsidiariesModel;
    use App\Models\LanguagesModel;
    use App\Models\CountriesModel;

    $subsidiariesModel = new SubsidiariesModel();
    $subsidiaryOptions = $subsidiariesModel->Options();

    $subsidiariesList = $subsidiariesList ?? $subsidiariesModel->orderBy('name', 'ASC')->findAll();
    $languagesList = $languagesList ?? (new LanguagesModel())->orderBy('name', 'ASC')->findAll();
    $countriesList = $countriesList ?? (new CountriesModel())->orderBy('name', 'ASC')->findAll();

    helper('custom_functions');

    $activeFiltersCount = 0;
    $advancedFilterKeys = ['subsidiary_id', 'is_epp', 'is_shop'];
    foreach ($advancedFilterKeys as $key) {
        if (isset($_GET[$key]) && $_GET[$key] !== '') {
            $activeFiltersCount++;
        }
    }
    $hasActiveFilters = ($activeFiltersCount > 0);
?>

<?= csrf_field() ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <?php $totalProperties = isset($pager) ? $pager->getTotal() : count($properties); ?>
            <h2 class="mb-0 fw-bold d-flex align-items-center gap-2">
                <span>Properties</span>
                <span class="badge bg-secondary text-secondary" style="font-size: 0.85rem; font-weight: 600; padding: 0.35em 0.7em; border-radius: 20px;">
                    <?= $totalProperties ?>
                </span>
            </h2>
            <p class="text-secondary small mb-0">Overview of property domains, platforms, AEM configuration refs, and shops.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('properties/create') ?>" title="Add a new property" class="apple-btn-primary btn-sm px-3">
                <i class="bi bi-plus-lg me-1"></i> Create New
            </a>

            <!-- Feature to add values to subsidiaries, languages, and countries -->
            <div class="btn-group">
                <button type="button" class="apple-btn-secondary btn-sm px-3 d-inline-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#addAttributeModal" title="Add Subsidiary, Language, or Country">
                    <i class="bi bi-plus-circle"></i> Add Attribute
                </button>
                <button type="button" class="apple-btn-secondary btn-sm dropdown-toggle dropdown-toggle-split px-2" data-bs-toggle="dropdown" aria-expanded="false" title="Quick select attribute">
                    <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm py-2" style="border-radius: 12px; font-size: 0.85rem; border: 1px solid var(--color-border-subtle); min-width: 180px;">
                    <li><h6 class="dropdown-header text-uppercase text-muted" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.5px;">Add Value To</h6></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0)" onclick="openAddAttributeModal('subsidiary')">
                            <i class="bi bi-buildings text-primary"></i> Subsidiary
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0)" onclick="openAddAttributeModal('language')">
                            <i class="bi bi-translate text-success"></i> Language
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0)" onclick="openAddAttributeModal('country')">
                            <i class="bi bi-globe-americas text-info"></i> Country
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
                        placeholder="Search properties..."
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
                    <a href="<?= site_url('properties') ?>" class="apple-btn-secondary btn-sm px-4 text-decoration-none d-inline-flex align-items-center justify-content-center" style="height: 36px; border-radius: 20px;">Clear</a>
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
                            <?php foreach ($subsidiaryOptions as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('subsidiary_id', $filters['subsidiary_id'] ?? '') == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Is EPP? -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Is EPP?</label>
                        <select name="is_epp" class="form-select form-select-xs" style="height: 34px; border-radius: 8px;">
                            <option value="">All</option>
                            <option value="1" <?= (isset($_GET['is_epp']) && $_GET['is_epp'] === '1') ? 'selected' : '' ?>>Yes</option>
                            <option value="0" <?= (isset($_GET['is_epp']) && $_GET['is_epp'] === '0') ? 'selected' : '' ?>>No</option>
                        </select>
                    </div>

                    <!-- Is Shop? -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Is Shop?</label>
                        <select name="is_shop" class="form-select form-select-xs" style="height: 34px; border-radius: 8px;">
                            <option value="">All</option>
                            <option value="1" <?= (isset($_GET['is_shop']) && $_GET['is_shop'] === '1') ? 'selected' : '' ?>>Yes</option>
                            <option value="0" <?= (isset($_GET['is_shop']) && $_GET['is_shop'] === '0') ? 'selected' : '' ?>>No</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="sort_by" value="<?= esc($filters['sort_by'] ?? '') ?>">
        <input type="hidden" name="sort_dir" value="<?= esc($filters['sort_dir'] ?? 'asc') ?>">
    </form>

    <!-- Active Filters Pills -->
    <?php if ($activeFiltersCount > 0): ?>
        <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
            <span class="text-secondary small fw-medium">Active Filters:</span>
            <?php
            foreach ($advancedFilterKeys as $key) {
                if (isset($_GET[$key]) && $_GET[$key] !== '') {
                    $val = $_GET[$key];
                    $label = '';
                    if ($key === 'subsidiary_id') {
                        $label = 'Subsidiary: ' . ($subsidiaryOptions[$val] ?? $val);
                    } elseif ($key === 'is_epp') {
                        $label = 'EPP: ' . ($val === '1' ? 'Yes' : 'No');
                    } elseif ($key === 'is_shop') {
                        $label = 'Shop: ' . ($val === '1' ? 'Yes' : 'No');
                    }
                    
                    $tempParams = $_GET;
                    unset($tempParams[$key]);
                    $clearUrl = site_url('properties') . '?' . http_build_query($tempParams);
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

    <!-- #region | PROPERTIES TABLE -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-secondary small fw-medium">
            Showing <?= count($properties) ?> of <?= $totalProperties ?> results
        </span>
    </div>

    <div class="apple-table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tblProperties">
                <thead>
                    <tr>
                        <?php
                        $currentSortBy = $filters['sort_by'] ?? '';
                        $currentSortDir = $filters['sort_dir'] ?? 'asc';
                        ?>
                        <th class="form-label-sm fw-semibold">
                            <a href="<?= getSortUrl('id', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                                # <?= getSortIcon('id', $currentSortBy, $currentSortDir) ?>
                            </a>
                        </th>
                        <th class="form-label-sm fw-semibold">
                            <a href="<?= getSortUrl('subsidiary_id', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                                Subsidiary <?= getSortIcon('subsidiary_id', $currentSortBy, $currentSortDir) ?>
                            </a>
                        </th>
                        <th class="form-label-sm fw-semibold">
                            <a href="<?= getSortUrl('name', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                                Property <?= getSortIcon('name', $currentSortBy, $currentSortDir) ?>
                            </a>
                        </th>
                        <th class="form-label-sm fw-semibold">
                            Site URL
                        </th>
                        <th class="form-label-sm fw-semibold">
                            <a href="<?= getSortUrl('is_epp', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                                Is EPP? <?= getSortIcon('is_epp', $currentSortBy, $currentSortDir) ?>
                            </a>
                        </th>
                        <th class="form-label-sm fw-semibold text-dark">Is AEM?</th>
                        <th class="form-label-sm fw-semibold">
                            <a href="<?= getSortUrl('is_shop', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                                Is Shop? <?= getSortIcon('is_shop', $currentSortBy, $currentSortDir) ?>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($properties)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 mb-2 d-block text-secondary" style="opacity: 0.5;"></i>
                                <span class="fw-semibold">No properties found</span>
                                <p class="small text-secondary mb-0">Try adjusting your filters or search keywords.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($properties as $property): ?>
                            <tr>
                                <td><?= $property['id'] ?></td>
                                <td><?= $subsidiaryOptions[$property['subsidiary_id']] ?? 'Unknown' ?></td>
                                <td class="form-label-sm">
                                    <span class="group-hover-container align-items-center">
                                        <a href="<?= $property['ref_link'] ?>" target="_blank" rel="noopener" class="apple-link-dark form-label-sm ms-1">
                                            <?= $property['name'] ?>
                                        </a>
                                        <a href="javascript:void(0)" onclick="copyName('<?= esc($property['name']) ?>', this)" title="Copy property name" class="text-muted ms-2" style="transition: color 0.2s; text-decoration: none; line-height: 1;">
                                            <i class="bi bi-copy" style="font-size: 0.85rem;"></i>
                                        </a>
                                    </span>
                                </td>
                                <td class="form-label-sm">
                                    <span class="group-hover-container align-items-center">
                                        <a href="<?= $property['website'] ?>" target="_blank" rel="noopener" class="apple-link-dark form-label-sm ms-1">
                                            <?= $property['website'] ?>
                                        </a>
                                        <a href="javascript:void(0)" onclick="copyWebsite('<?= esc($property['website']) ?>', this)" title="Copy site URL" class="text-muted ms-2" style="transition: color 0.2s; text-decoration: none; line-height: 1;">
                                            <i class="bi bi-copy" style="font-size: 0.85rem;"></i>
                                        </a>
                                        <a href="javascript:void(0)" onclick="openEditUrlModal(<?= $property['id'] ?>, '<?= esc($property['website']) ?>')" title="Edit site URL" class="text-primary ms-2" style="transition: color 0.2s; text-decoration: none; line-height: 1;">
                                            <i class="bi bi-pencil" style="font-size: 0.85rem;"></i>
                                        </a>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= bool_class($property['is_epp']) ?>">
                                        <?= esc(bool_label($property['is_epp'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= bool_class(!$property['is_shop']) ?>">
                                        <?= esc(bool_label(!$property['is_shop'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= bool_class($property['is_shop']) ?>">
                                        <?= esc(bool_label($property['is_shop'])) ?>
                                    </span>
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

<!-- Edit URL Modal -->
<div class="modal fade" id="editUrlModal" tabindex="-1" aria-labelledby="editUrlModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header border-bottom-0 pb-0 mt-2">
                <h5 class="modal-title fw-bold" id="editUrlModalLabel">Edit Site URL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUrlForm" method="post" action="">
                <?= csrf_field() ?>
                <div class="modal-body pt-3 pb-4">
                    <div class="mb-2">
                        <label for="edit_website" class="form-label fw-semibold text-secondary small mb-1">Site URL</label>
                        <input type="text" class="form-control form-control-sm" id="edit_website" name="website" required style="border-radius: 8px;">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 pb-3">
                    <button type="button" class="btn btn-light btn-sm px-3 fw-medium" data-bs-dismiss="modal" style="border-radius: 20px;">Cancel</button>
                    <button type="submit" class="apple-btn-primary btn-sm px-4 fw-medium" style="border-radius: 20px;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Attribute Modal (Subsidiaries, Languages, Countries) -->
<div class="modal fade" id="addAttributeModal" tabindex="-1" aria-labelledby="addAttributeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <!-- Header -->
            <div class="modal-header border-bottom pb-3 pt-3 px-4 bg-light bg-opacity-50">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 42px; height: 42px; flex-shrink: 0;">
                        <i class="bi bi-plus-square-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-dark" id="addAttributeModalLabel">Add Property Attribute</h5>
                        <p class="text-secondary small mb-0">Add values to subsidiaries, languages, or countries database tables.</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Tab Pills Navigation -->
            <div class="px-4 pt-3 pb-2 bg-light bg-opacity-25 border-bottom">
                <ul class="nav nav-pills nav-fill gap-2" id="attributeTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active d-flex align-items-center justify-content-center gap-2 py-2 fw-semibold" id="tab-subsidiary-btn" data-bs-toggle="pill" data-bs-target="#tab-subsidiary" type="button" role="tab" aria-controls="tab-subsidiary" aria-selected="true" style="border-radius: 10px;">
                            <i class="bi bi-buildings"></i>
                            <span>Subsidiary</span>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1" style="font-size: 0.72rem;"><?= count($subsidiariesList ?? []) ?></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center justify-content-center gap-2 py-2 fw-semibold" id="tab-language-btn" data-bs-toggle="pill" data-bs-target="#tab-language" type="button" role="tab" aria-controls="tab-language" aria-selected="false" style="border-radius: 10px;">
                            <i class="bi bi-translate"></i>
                            <span>Language</span>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1" style="font-size: 0.72rem;"><?= count($languagesList ?? []) ?></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center justify-content-center gap-2 py-2 fw-semibold" id="tab-country-btn" data-bs-toggle="pill" data-bs-target="#tab-country" type="button" role="tab" aria-controls="tab-country" aria-selected="false" style="border-radius: 10px;">
                            <i class="bi bi-globe-americas"></i>
                            <span>Country</span>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1" style="font-size: 0.72rem;"><?= count($countriesList ?? []) ?></span>
                        </button>
                    </li>
                </ul>
            </div>

            <div class="modal-body p-4">
                <div class="tab-content" id="attributeTabContent">
                    
                    <!-- TAB 1: SUBSIDIARY -->
                    <div class="tab-pane fade show active" id="tab-subsidiary" role="tabpanel" aria-labelledby="tab-subsidiary-btn">
                        <form action="<?= site_url('properties/add-attribute') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="type" value="subsidiary">

                            <div class="mb-3">
                                <label for="subsidiary_name_input" class="form-label fw-semibold text-secondary small mb-1">
                                    Subsidiary Name / Code <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted" style="border-radius: 8px 0 0 8px;">
                                        <i class="bi bi-buildings"></i>
                                    </span>
                                    <input 
                                        type="text" 
                                        class="form-control text-uppercase" 
                                        id="subsidiary_name_input" 
                                        name="name" 
                                        placeholder="e.g. SEBNL or SGE" 
                                        maxlength="10" 
                                        required
                                        style="border-radius: 0 8px 8px 0;"
                                    >
                                </div>
                                <div class="form-text text-muted small mt-1">
                                    Max 10 characters. Example: <code>SGE</code>, <code>SESAR</code>, <code>SETK</code>, <code>SEIL</code>.
                                </div>
                            </div>

                            <!-- Existing values pill list -->
                            <div class="p-3 bg-light rounded-3 mb-4" style="border: 1px solid var(--color-border-subtle);">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold text-secondary" style="font-size: 0.8rem;">Existing Subsidiaries:</span>
                                    <span class="text-muted" style="font-size: 0.75rem;"><?= count($subsidiariesList ?? []) ?> total</span>
                                </div>
                                <div class="d-flex flex-wrap gap-1.5" style="max-height: 120px; overflow-y: auto;">
                                    <?php if (!empty($subsidiariesList)): ?>
                                        <?php foreach ($subsidiariesList as $sub): ?>
                                            <span class="badge bg-white text-dark border px-2 py-1" style="font-size: 0.78rem; font-weight: 500; border-radius: 6px;">
                                                <?= esc($sub['name']) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">No subsidiaries recorded yet.</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                                <button type="button" class="btn btn-light btn-sm px-3 fw-medium" data-bs-dismiss="modal" style="border-radius: 20px;">Cancel</button>
                                <button type="submit" class="apple-btn-primary btn-sm px-4 fw-medium" style="border-radius: 20px;">
                                    <i class="bi bi-plus-lg me-1"></i> Add Subsidiary
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 2: LANGUAGE -->
                    <div class="tab-pane fade" id="tab-language" role="tabpanel" aria-labelledby="tab-language-btn">
                        <form action="<?= site_url('properties/add-attribute') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="type" value="language">

                            <div class="mb-3">
                                <label for="language_name_input" class="form-label fw-semibold text-secondary small mb-1">
                                    Language Name / Code <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted" style="border-radius: 8px 0 0 8px;">
                                        <i class="bi bi-translate"></i>
                                    </span>
                                    <input 
                                        type="text" 
                                        class="form-control text-uppercase" 
                                        id="language_name_input" 
                                        name="name" 
                                        placeholder="e.g. EN, AR, or DE" 
                                        maxlength="10" 
                                        required
                                        style="border-radius: 0 8px 8px 0;"
                                    >
                                </div>
                                <div class="form-text text-muted small mt-1">
                                    Max 10 characters. Example: <code>EN</code>, <code>AR</code>, <code>TR</code>, <code>FR</code>, <code>DE</code>.
                                </div>
                            </div>

                            <!-- Existing values pill list -->
                            <div class="p-3 bg-light rounded-3 mb-4" style="border: 1px solid var(--color-border-subtle);">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold text-secondary" style="font-size: 0.8rem;">Existing Languages:</span>
                                    <span class="text-muted" style="font-size: 0.75rem;"><?= count($languagesList ?? []) ?> total</span>
                                </div>
                                <div class="d-flex flex-wrap gap-1.5" style="max-height: 120px; overflow-y: auto;">
                                    <?php if (!empty($languagesList)): ?>
                                        <?php foreach ($languagesList as $lang): ?>
                                            <span class="badge bg-white text-dark border px-2 py-1" style="font-size: 0.78rem; font-weight: 500; border-radius: 6px;">
                                                <?= esc($lang['name']) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">No languages recorded yet.</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                                <button type="button" class="btn btn-light btn-sm px-3 fw-medium" data-bs-dismiss="modal" style="border-radius: 20px;">Cancel</button>
                                <button type="submit" class="apple-btn-primary btn-sm px-4 fw-medium" style="border-radius: 20px;">
                                    <i class="bi bi-plus-lg me-1"></i> Add Language
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 3: COUNTRY -->
                    <div class="tab-pane fade" id="tab-country" role="tabpanel" aria-labelledby="tab-country-btn">
                        <form action="<?= site_url('properties/add-attribute') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="type" value="country">

                            <div class="row g-3 mb-3">
                                <div class="col-md-7">
                                    <label for="country_name_input" class="form-label fw-semibold text-secondary small mb-1">
                                        Country Name <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted" style="border-radius: 8px 0 0 8px;">
                                            <i class="bi bi-globe-americas"></i>
                                        </span>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="country_name_input" 
                                            name="name" 
                                            placeholder="e.g. Germany or Spain" 
                                            maxlength="50" 
                                            required
                                            style="border-radius: 0 8px 8px 0;"
                                        >
                                    </div>
                                    <div class="form-text text-muted small mt-1">Full country name.</div>
                                </div>
                                <div class="col-md-5">
                                    <label for="country_code_input" class="form-label fw-semibold text-secondary small mb-1">
                                        Country Code <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted" style="border-radius: 8px 0 0 8px;">
                                            <i class="bi bi-qr-code"></i>
                                        </span>
                                        <input 
                                            type="text" 
                                            class="form-control text-uppercase" 
                                            id="country_code_input" 
                                            name="code" 
                                            placeholder="e.g. DE or ES" 
                                            maxlength="10" 
                                            required
                                            style="border-radius: 0 8px 8px 0;"
                                        >
                                    </div>
                                    <div class="form-text text-muted small mt-1">ISO / site code (e.g. AE, SA, DE).</div>
                                </div>
                            </div>

                            <!-- Existing values pill list -->
                            <div class="p-3 bg-light rounded-3 mb-4" style="border: 1px solid var(--color-border-subtle);">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold text-secondary" style="font-size: 0.8rem;">Existing Countries:</span>
                                    <span class="text-muted" style="font-size: 0.75rem;"><?= count($countriesList ?? []) ?> total</span>
                                </div>
                                <div class="d-flex flex-wrap gap-1.5" style="max-height: 120px; overflow-y: auto;">
                                    <?php if (!empty($countriesList)): ?>
                                        <?php foreach ($countriesList as $cntry): ?>
                                            <span class="badge bg-white text-dark border px-2 py-1 d-inline-flex align-items-center gap-1" style="font-size: 0.78rem; font-weight: 500; border-radius: 6px;">
                                                <span><?= esc($cntry['name']) ?></span>
                                                <code class="text-primary fw-bold" style="font-size: 0.72rem;"><?= esc($cntry['code']) ?></code>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">No countries recorded yet.</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                                <button type="button" class="btn btn-light btn-sm px-3 fw-medium" data-bs-dismiss="modal" style="border-radius: 20px;">Cancel</button>
                                <button type="submit" class="apple-btn-primary btn-sm px-4 fw-medium" style="border-radius: 20px;">
                                    <i class="bi bi-plus-lg me-1"></i> Add Country
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.copyName = function(text, el) {
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

        window.copyWebsite = function(text, el) {
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

        window.openEditUrlModal = function(id, website) {
            const form = document.getElementById('editUrlForm');
            form.action = '<?= site_url('properties/update-url/') ?>' + id;
            document.getElementById('edit_website').value = website;
            
            const modal = new bootstrap.Modal(document.getElementById('editUrlModal'));
            modal.show();
        };

        window.openAddAttributeModal = function(tabName) {
            const modalEl = document.getElementById('addAttributeModal');
            if (!modalEl) return;
            if (tabName) {
                const tabTrigger = document.querySelector(`#attributeTab button[data-bs-target="#tab-${tabName}"]`);
                if (tabTrigger) {
                    const tab = bootstrap.Tab.getOrCreateInstance(tabTrigger);
                    tab.show();
                }
            }
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        };

        const addAttrModal = document.getElementById('addAttributeModal');
        if (addAttrModal) {
            addAttrModal.addEventListener('shown.bs.modal', function () {
                const activePane = addAttrModal.querySelector('.tab-pane.show.active');
                if (activePane) {
                    const firstInput = activePane.querySelector('input[type="text"]:not([type="hidden"])');
                    if (firstInput) firstInput.focus();
                }
            });

            const tabButtons = addAttrModal.querySelectorAll('#attributeTab button[data-bs-toggle="pill"]');
            tabButtons.forEach(btn => {
                btn.addEventListener('shown.bs.tab', function (e) {
                    const targetPaneId = e.target.getAttribute('data-bs-target');
                    const targetPane = document.querySelector(targetPaneId);
                    if (targetPane) {
                        const input = targetPane.querySelector('input[type="text"]:not([type="hidden"])');
                        if (input) input.focus();
                    }
                });
            });
        }
    });
</script>

<?= $this->endSection() ?>
