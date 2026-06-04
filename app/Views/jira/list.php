<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
JIRA Tickets List
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php

use App\Models\ActivityLogsModel;
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
?>

<?= csrf_field() ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <h2>JIRA Tickets</h2>
        <div class="d-flex justify-content-end align-items-center gap-2">
            <a href="<?= site_url('jira/export?' . http_build_query($_GET)) ?>" class="apple-btn-secondary btn-sm px-3">Export as CSV</a>
            <a href="<?= site_url('jira/create') ?>" title="Create new JIRA ticket" class="apple-btn-primary btn-sm ms-auto px-3">Add a JIRA Ticket</a>
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
                <select name="ticket_type" class="form-select form-select-xs">
                    <option value="">All Ticket Types</option>
                    <?php foreach (Constants::JIRA_TICKET_TYPES as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('ticket_type', $filters['ticket_type'] ?? '') == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-center gap-2">
                <button class="apple-btn-primary btn-sm w-100">Filter</button>
                <a href="<?= site_url('jira') ?>" class="apple-btn-secondary btn-sm w-100 text-center text-decoration-none">Clear</a>
            </div>
        </form>
    </div>
    <!-- #endregion -->

    <!-- #region | JIRA HISTORY TABLE -->
    <div class="apple-table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="form-label-sm fw-semibold">#</th>
                        <th class="form-label-sm fw-semibold">Subsidiary</th>
                        <th class="form-label-sm fw-semibold">Property</th>
                        <th class="form-label-sm fw-semibold">Ticket ID</th>
                        <th class="form-label-sm fw-semibold">Type</th>
                        <th class="form-label-sm fw-semibold">Comments</th>
                        <th class="form-label-sm fw-semibold">Created At</th>
                    </tr>
                </thead>
            <tbody>
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
                                    // $ticketTagsList[$tagInfo['name']] = $tagInfo['ref_link']; // If you want to direct at AL
                                    $ticketTagsList[$tagInfo['id']] = $tagInfo['name'];
                                }
                            }
                        }
                ?>
                    <tr>
                        <td><?= $ticket['id'] ?></td>
                        <td>
                            <span class="badge" style="<?= random_bg_style($subsidiary['name']) ?>">
                                <?= esc($subsidiary['name']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($property): ?>
                            <a href="<?= $property['ref_link'] ?>" class="apple-link-dark form-label-sm">
                                <?= esc($property['name']) ?>
                            </a>
                            <?php else: ?>
                                None
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= Constants::JIRA_LINK . $ticket['ticket_id'] ?>" class="apple-link form-label-sm">
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
                                    $activityLogsModel = new ActivityLogsModel();
                                    $activityLog = $activityLogsModel->where('ticket_id', $ticket['ticket_id'])->first();

                                    if ($activityLog) {
                            ?>
                                    <a href="<?= site_url('/activity-logs/view/' . $activityLog['id']) ?>" class="apple-link-dark">
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

                                <!-- If you want to direct at AL, use the below code -->
                                <!-- <?php foreach ($ticketTagsList as $name => $link): ?>
                                    <a href="<?= $link ?>" class="apple-link-dark"><?= esc($name) ?></a><br>
                                <?php endforeach; ?> -->
                            <?php else: ?>
                                <?= esc($ticket['comments']) ?>
                            <?php endif; ?>
                        </td>
                        <td class="form-label-sm" style="width:max-content; white-space:nowrap;"><?= esc(date('d-m-Y', strtotime($ticket['created_at']))) ?></td>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.searchable-select').select2({
        width: '100%',
        placeholder: "All Properties",
        allowClear: true
    });
});

</script>

<?= $this->endSection() ?>
