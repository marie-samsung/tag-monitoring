<?php
function getSortUrl($field, $currentSortBy, $currentSortDir) {
    $queryParams = $_GET;
    $queryParams['sort_by'] = $field;
    if ($currentSortBy === $field) {
        $queryParams['sort_dir'] = ($currentSortDir === 'asc') ? 'desc' : 'asc';
    } else {
        $queryParams['sort_dir'] = 'asc';
    }
    return site_url('jira') . '?' . http_build_query($queryParams);
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
JIRA Tickets List
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
use App\Models\TasksModel;
use App\Models\Constants;
use App\Models\TagRequestsModel;
use App\Models\TagsModel;
use App\Models\PropertiesModel;
use App\Models\SubsidiariesModel;

$tagModel = new TagsModel();
$propertiesModel = new PropertiesModel();
$subsidiaryModel = new SubsidiariesModel();

$properties = $propertiesModel->WebsiteLinks();
$propertyOptions = $propertiesModel->Options();
$subsidiaries = $subsidiaryModel->Options();

helper('custom_functions');

$activeFiltersCount = 0;
$advancedFilterKeys = ['subsidiary_id', 'property_id', 'ticket_type', 'created_at_from', 'created_at_to'];
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
            <?php $totalTickets = isset($pager) ? $pager->getTotal() : count($jiraTickets); ?>
            <h2 class="mb-0 fw-bold d-flex align-items-center gap-2">
                <span>JIRA Tickets</span>
                <span class="badge bg-secondary text-secondary" style="font-size: 0.85rem; font-weight: 600; padding: 0.35em 0.7em; border-radius: 20px;">
                    <?= $totalTickets ?>
                </span>
            </h2>
            <p class="text-secondary small mb-0">Overview of GTA JIRA tickets created for approvals, new tags, date extensions, and updates.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('jira/export?' . http_build_query($_GET)) ?>" class="apple-btn-secondary btn-sm px-3">Export as CSV</a>
            <a href="<?= site_url('jira/create') ?>" title="Create new JIRA ticket" class="apple-btn-primary btn-sm px-3">Add a JIRA Ticket</a>
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
                        placeholder="Search Ticket ID or keyword..."
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
                    <a href="<?= site_url('jira') ?>" class="apple-btn-secondary btn-sm px-4 text-decoration-none d-inline-flex align-items-center justify-content-center" style="height: 36px; border-radius: 20px;">Clear</a>
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

                    <!-- Ticket Type -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1">Ticket Type</label>
                        <select name="ticket_type" class="form-select form-select-xs" style="height: 34px; border-radius: 8px;">
                            <option value="">All Ticket Types</option>
                            <?php foreach (Constants::JIRA_TICKET_TYPES as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('ticket_type', $filters['ticket_type'] ?? '') == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Created From -->
                    <div class="col-md-4 col-lg-3">
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
                    <div class="col-md-4 col-lg-3">
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
        <input type="hidden" name="sort_by" value="<?= esc($filters['sort_by'] ?? '') ?>">
        <input type="hidden" name="sort_dir" value="<?= esc($filters['sort_dir'] ?? 'desc') ?>">
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
                    } elseif ($key === 'ticket_type') {
                        $label = 'Type: ' . (Constants::JIRA_TICKET_TYPES[$val] ?? $val);
                    } elseif ($key === 'created_at_from') {
                        $label = 'Created From: ' . date('d-m-Y', strtotime($val));
                    } elseif ($key === 'created_at_to') {
                        $label = 'Created To: ' . date('d-m-Y', strtotime($val));
                    }
                    
                    $tempParams = $_GET;
                    unset($tempParams[$key]);
                    $clearUrl = site_url('jira') . '?' . http_build_query($tempParams);
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

    <!-- #region | JIRA HISTORY TABLE -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-secondary small fw-medium">
            Showing <?= count($jiraTickets) ?> of <?= $totalTickets ?> results
        </span>
    </div>

    <div class="apple-table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
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
                            <a href="<?= getSortUrl('ticket_id', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                                Ticket ID <?= getSortIcon('ticket_id', $currentSortBy, $currentSortDir) ?>
                            </a>
                        </th>
                        <th class="form-label-sm fw-semibold">
                            <a href="<?= getSortUrl('type', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                                Type <?= getSortIcon('type', $currentSortBy, $currentSortDir) ?>
                            </a>
                        </th>
                        <th class="form-label-sm fw-semibold text-dark">Comments</th>
                        <th class="form-label-sm fw-semibold">
                            <a href="<?= getSortUrl('created_at', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                                Created At <?= getSortIcon('created_at', $currentSortBy, $currentSortDir) ?>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($jiraTickets)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 mb-2 d-block text-secondary" style="opacity: 0.5;"></i>
                                <span class="fw-semibold">No JIRA tickets found</span>
                                <p class="small text-secondary mb-0">Try adjusting your filters or search keywords.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php
                            foreach ($jiraTickets as $ticket):
                                $tag = $tagModel->find($ticket['tag_id']);
                                $property = null;
                                $type = $ticket['type'];

                                if ($tag) {
                                    $property = $propertiesModel->find($ticket['property_id']);
                                    $subsidiary = $subsidiaryModel->find($ticket['subsidiary_id']);
                                } else {
                                    $subsidiary = $subsidiaryModel->find($ticket['subsidiary_id']);
                                }

                                $ticketTagsList = [];
                                if ($type == Constants::JIRA_TYPE_DATE_EXTENSION_RULE ||
                                        $type == Constants::JIRA_TYPE_NEW_TAG ||
                                        $type == Constants::JIRA_TYPE_NEW_RULE ||
                                        $type == Constants::JIRA_TYPE_UPDATE_RULE) {
                                    $ticketTags = explode(',', $ticket['comments']);
                                    
                                    foreach ($ticketTags as $tagId) {
                                        $tagInfo = $tagModel->find($tagId);
                                        if ($tagInfo) {
                                            $ticketTagsList[$tagInfo['id']] = $tagInfo['name'];
                                        }
                                    }
                                }
                        ?>
                            <tr>
                                <td><?= $ticket['id'] ?></td>
                                <td>
                                    <span class="badge" style="<?= random_bg_style($subsidiary['name'] ?? '') ?>">
                                        <?= esc($subsidiary['name'] ?? '') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($property): ?>
                                    <i class="bi bi-tag-fill" style="<?= random_text_style($property['id']) ?>"></i>
                                    <a href="<?= $property['ref_link'] ?>" target="_blank" rel="noopener" class="apple-link-dark form-label-sm">
                                        <?= esc($property['name']) ?>
                                    </a>
                                    <?php else: ?>
                                        None
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= Constants::JIRA_LINK . $ticket['ticket_id'] ?>" target="_blank" rel="noopener" class="apple-link form-label-sm">
                                        <?= $ticket['ticket_id'] ?>
                                    </a>
                                </td>
                                <td class="form-label-sm">
                                    <?php
                                        if ($type == Constants::JIRA_TYPE_NEW_TAG || $type == Constants::JIRA_TYPE_NEW_RULE) {
                                            $tagRequestsModel = new TagRequestsModel();
                                            $request_id = $tagRequestsModel->getRequestByTicketId($ticket['ticket_id']);

                                            if ($request_id) {
                                    ?>
                                                <a href="<?= site_url('/tag-requests/view/' . $request_id) ?>" class="apple-link-dark">
                                                    <?= Constants::JIRA_TICKET_TYPES[$type] ?>
                                                </a>
                                        <?php } else { ?>
                                                <?= Constants::JIRA_TICKET_TYPES[$type] ?>
                                    <?php
                                            }
                                        } elseif ($type == Constants::JIRA_TYPE_UPDATE_RULE) {
                                            $tasksModel = new TasksModel();
                                            $activityLog = $tasksModel->where('ticket_id', $ticket['ticket_id'])->first();

                                            if ($activityLog) {
                                    ?>
                                            <a href="<?= site_url('/tasks/view/' . $activityLog['id']) ?>" class="apple-link-dark">
                                                <?= Constants::JIRA_TICKET_TYPES[$type] ?>
                                            </a>

                                            <?php } else { ?>
                                                <?= Constants::JIRA_TICKET_TYPES[$type] ?>
                                    <?php }} else { ?>
                                        <?= Constants::JIRA_TICKET_TYPES[$type] ?>
                                    <?php } ?>
                                </td>
                                <!-- COMMENTS -->
                                <td class="form-label-sm">
                                    <?php if ($ticketTagsList): ?>

                                        <?php foreach ($ticketTagsList as $id => $name): ?>
                                            <a href="<?= site_url('tags/view/' . $id) ?>" class="apple-link-dark"><?= esc($name) ?></a><br>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <?= esc($ticket['comments']) ?>
                                    <?php endif; ?>
                                </td>
                                <td class="form-label-sm" style="width:max-content; white-space:nowrap;"><?= esc(date('d-m-Y', strtotime($ticket['created_at']))) ?></td>
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
});
</script>

<?= $this->endSection() ?>
