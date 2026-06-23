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
$tasksModel = new TasksModel();

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
            <a href="<?= site_url('jira/export?' . http_build_query($_GET)) ?>" class="apple-btn-secondary btn-sm px-3">Export as Excel</a>
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
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <span class="text-secondary small fw-medium">
            Showing <?= count($jiraTickets) ?> of <?= $totalTickets ?> results
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
            <table class="table table-hover align-middle mb-0 style-glassmorphic" id="tblJira">
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
                        <th class="form-label-sm fw-semibold text-dark">Status</th>
                        <th class="form-label-sm fw-semibold">
                            <a href="<?= getSortUrl('created_at', $currentSortBy, $currentSortDir) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center">
                                Created At <?= getSortIcon('created_at', $currentSortBy, $currentSortDir) ?>
                            </a>
                        </th>
                        <th class="form-label-sm fw-semibold text-dark text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($jiraTickets)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
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
                                            $ticketTagsList[$tagInfo['id']] = $tagInfo;
                                        }
                                    }
                                }

                                // Get status from one of the related tags
                                $relatedTagStatus = null;
                                if ($tag) {
                                    $relatedTagStatus = $tag['status'];
                                } elseif (!empty($ticketTagsList)) {
                                    $firstTag = reset($ticketTagsList);
                                    if ($firstTag) {
                                        $relatedTagStatus = $firstTag['status'];
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
                                <td style="width:25%;">
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
                                    <?php $relatedTask = $tasksModel->where('ticket_id', $ticket['ticket_id'])->first(); ?>
                                    <?php if ($relatedTask): ?>
                                        <a href="<?= site_url('tasks/view/' . $relatedTask['id']) ?>" class="apple-link form-label-sm" title="View related Task">
                                            <?= esc($ticket['ticket_id']) ?>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= Constants::JIRA_LINK . $ticket['ticket_id'] ?>" target="_blank" rel="noopener" class="apple-link form-label-sm">
                                            <?= esc($ticket['ticket_id']) ?>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= Constants::JIRA_LINK . $ticket['ticket_id'] ?>" target="_blank" rel="noopener" class="text-muted ms-1" style="font-size: 0.75rem; text-decoration: none;" title="View in JIRA">
                                        <i class="bi bi-box-arrow-up-right"></i>
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
                                <td class="form-label-sm td-comments" style="width:25%">
                                    <?php if ($ticketTagsList): ?>
                                        <?php
                                            $tagCount = count($ticketTagsList);
                                            $collapseId = 'tagCollapse_' . $ticket['id'];
                                        ?>
                                        <div class="tag-expand-cell">
                                            <!-- Collapsed Summary -->
                                            <div class="tag-summary d-flex align-items-center gap-2">
                                                <span class="badge tag-count-badge">
                                                    <i class="bi bi-tags-fill me-1"></i><?= $tagCount ?> tag<?= $tagCount !== 1 ? 's' : '' ?>
                                                </span>
                                                <button
                                                    type="button"
                                                    class="btn-tag-expand"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#<?= $collapseId ?>"
                                                    aria-expanded="false"
                                                    aria-controls="<?= $collapseId ?>"
                                                    title="Show related tags"
                                                >
                                                    <span class="expand-label">Show more</span>
                                                    <i class="bi bi-chevron-down expand-icon"></i>
                                                </button>
                                            </div>
                                            <!-- Expanded Tag List -->
                                            <div class="collapse mt-2" id="<?= $collapseId ?>">
                                                <div class="tag-list-expanded">
                                                    <?php foreach ($ticketTagsList as $id => $tagObj): ?>
                                                        <a href="<?= site_url('tags/view/' . $id) ?>" class="tag-pill-link" title="<?= esc($tagObj['name']) ?>">
                                                            <i class="bi bi-tag-fill me-1"></i><?= esc($tagObj['name']) ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="comment-text-plain">
                                            <?= esc($ticket['comments']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <!-- STATUS -->
                                <td>
                                    <?php if ($relatedTagStatus !== null): ?>
                                        <span class="badge <?= status_class($relatedTagStatus) ?>">
                                            <?= esc(Constants::STATUS[$relatedTagStatus] ?? 'Unknown') ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="form-label-sm" style="width:max-content; white-space:nowrap;"><?= esc(date('d-m-Y', strtotime($ticket['created_at']))) ?></td>
                                <td class="text-nowrap align-middle actions-cell">
                                    <!-- Original Style -->
                                    <div class="action-style-original-wrapper">
                                        <a href="<?= site_url('jira/update-tags/' . $ticket['id']) ?>" class="apple-btn-secondary btn-sm px-2 py-1" style="font-size: 0.75rem; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;" title="Update Related Tags">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </div>

                                    <!-- Style A: Glassmorphic Dropdown -->
                                    <div class="action-style-glassmorphic-wrapper">
                                        <div class="dropdown">
                                            <button class="action-trigger-dot d-inline-flex align-items-center justify-content-center" type="button" id="actionsDropdownA_<?= $ticket['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end glassmorphic-menu shadow" aria-labelledby="actionsDropdownA_<?= $ticket['id'] ?>" style="z-index: 1050;">
                                                <li>
                                                    <a class="dropdown-item py-2" href="<?= site_url('jira/update-tags/' . $ticket['id']) ?>">
                                                        <i class="bi bi-pencil-square text-warning me-2"></i>Update Related Tags
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Style B: Hover-Revealed Inline Actions -->
                                    <div class="action-style-hover-inline-wrapper">
                                        <div class="hover-actions-container d-inline-flex align-items-center gap-1">
                                            <a href="<?= site_url('jira/update-tags/' . $ticket['id']) ?>" class="hover-action-btn btn-update" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Related Tags">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
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

    // Action Style Switcher logic
    const actionStyleControl = document.getElementById('actionStyleControl');
    const table = document.getElementById('tblJira');
    if (actionStyleControl && table) {
        const savedStyle = localStorage.getItem('tag_action_style') || 'glassmorphic';
        table.classList.remove('style-original', 'style-glassmorphic', 'style-hover-inline');
        table.classList.add(`style-${savedStyle}`);
        
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
            
            table.classList.remove('style-original', 'style-glassmorphic', 'style-hover-inline');
            table.classList.add(`style-${selectedStyle}`);
            
            localStorage.setItem('tag_action_style', selectedStyle);
        });
    }

    // Cell Click Redirection logic
    if (table) {
        table.addEventListener('click', function (e) {
            const td = e.target.closest('td');
            if (td) {
                const clickedLinkOrBtn = e.target.closest('a, button, select, label, input');
                
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
                                const updateBtn = wrapper.querySelector('.hover-action-btn.btn-update');
                                if (updateBtn) updateBtn.click();
                            }
                        }
                    }
                    return;
                }

                // Redirect clicks inside cells containing primary links
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

                if (clickedLinkOrBtn) {
                    return;
                }
            }
        });
    }
    // Tag expand/collapse label toggling
    document.querySelectorAll('.btn-tag-expand').forEach(function (btn) {
        var label = btn.querySelector('.expand-label');
        btn.addEventListener('click', function () {
            var expanded = btn.getAttribute('aria-expanded') === 'true';
            // aria-expanded is toggled by Bootstrap AFTER this event fires,
            // so we read the CURRENT value (before toggle) and flip it:
            label.textContent = expanded ? 'Show more' : 'Show less';
        });
    });
});
</script>

<style>
/* Action Dropdown Style Experiment */
.actions-cell {
    position: relative;
    width: 90px;
    min-width: 90px;
    text-align: center;
    padding-left: 4px !important;
    padding-right: 4px !important;
}

.action-style-original-wrapper,
.action-style-glassmorphic-wrapper,
.action-style-hover-inline-wrapper {
    display: none;
}

#tblJira.style-original .action-style-original-wrapper {
    display: block;
}

#tblJira.style-glassmorphic .action-style-glassmorphic-wrapper {
    display: block;
}

#tblJira.style-hover-inline .action-style-hover-inline-wrapper {
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
#tblJira tr:hover .hover-actions-container {
    opacity: 1;
    transform: translateX(0);
}

/* Also show them if select check is active or focus is inside */
#tblJira tr:focus-within .hover-actions-container {
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

.hover-action-btn.btn-update:hover {
    background-color: var(--color-badge-warning-bg) !important;
    color: var(--color-badge-warning-text) !important;
}

.hover-action-btn.btn-jira:hover {
    background-color: var(--color-badge-primary-bg) !important;
    color: var(--color-badge-primary-text) !important;
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

#tblJira tbody td.actions-cell,
#tblJira tbody td:not(.actions-cell):has(a:not([onclick]):not(.text-muted)) {
    cursor: pointer;
}
#tblJira tbody td:not(.actions-cell):has(a:not([onclick]):not(.text-muted)):hover a:not([onclick]):not(.text-muted):not(.tag-pill-link) {
    color: var(--color-primary) !important;
}
/* ── Tag Expand Cell ────────────────────────────────────────── */

/* Keep comments column width consistent */
#tblJira td.td-comments {
    width: 220px;
    min-width: 220px;
    max-width: 220px;
}

/* When a row expands, top-align ALL its cells to prevent jumping */
#tblJira tbody tr:has(.collapse.show) td,
#tblJira tbody tr:has(.collapsing) td {
    vertical-align: top;
}

.tag-expand-cell {
    width: 100%;
}

/* Lock the summary row so badge + button never wrap or shift */
.tag-summary {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: nowrap;
    white-space: nowrap;
}

.tag-count-badge {
    background-color: var(--color-badge-primary-bg);
    color: var(--color-badge-primary-text);
    border: 1px solid var(--color-badge-primary-border);
    font-size: 0.72rem;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 20px;
    letter-spacing: 0.3px;
    white-space: nowrap;
}

.btn-tag-expand {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    background-color: var(--color-secondary-bg);
    border: 1px solid transparent;
    border-radius: 20px;
    cursor: pointer;
    transition: background-color 0.15s ease, color 0.15s ease;
    outline: none;
    line-height: 1.2;
    white-space: nowrap;
}

.btn-tag-expand:hover {
    background-color: var(--color-secondary-hover);
    color: var(--color-text-base);
}

.btn-tag-expand .expand-icon {
    font-size: 0.65rem;
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.btn-tag-expand[aria-expanded="true"] .expand-icon {
    transform: rotate(180deg);
}

.tag-list-expanded {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.tag-pill-link {
    display: inline-flex;
    align-items: flex-start;
    font-size: 0.72rem;
    font-weight: 500;
    color: var(--color-badge-primary-text);
    background: var(--color-badge-primary-bg);
    border: 1px solid var(--color-badge-primary-border);
    border-radius: 12px;
    padding: 4px 10px;
    text-decoration: none;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
    white-space: normal;
    word-break: break-word;
    width: fit-content;
    max-width: 100%;
    line-height: 1.3;
}

.tag-pill-link:hover {
    background: var(--color-primary);
    color: #fff !important;
    border-color: var(--color-primary);
}

.comment-text-plain {
    display: block;
    font-size: 0.75rem;
    color: var(--color-text-secondary);
    word-break: break-word;
    white-space: normal;
    line-height: 1.4;
}
</style>

<?= $this->endSection() ?>
