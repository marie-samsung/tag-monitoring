<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= esc($tag['name']) ?> : Details
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
use App\Models\TasksModel;
use App\Models\Constants;
use App\Models\TagsModel;
use App\Models\PropertiesModel;
use App\Models\VendorsModel;
use App\Models\OrganizationsModel;
use App\Models\TagRequestsModel;
helper('custom_functions');

$propertiesModel = new PropertiesModel();
$properties = $propertiesModel->WebsiteLinks();

$vendorsModel = new VendorsModel();
$vendors = $vendorsModel->Options();

$organizationsModel = new OrganizationsModel();
$organizations = $organizationsModel->Options();

$tagModel = new TagsModel();
$tagRequestsModel = new TagRequestsModel();
?>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= site_url('tags') ?>" onclick="if (document.referrer) { window.history.back(); return false; }" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;" title="Back to Inventory">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold">Tag Details</h2>
            <p class="text-secondary small mb-0"><?= esc($tag['name']) ?></p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Tag Details Card -->
        <div class="col-lg-4">
            <div class="apple-card" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
                <h5 class="mb-4 text-primary d-flex align-items-center gap-2 fw-bold">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Specifications</span>
                </h5>

                <div class="mb-3">
                    <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Property</label>
                    <span class="fw-medium text-dark ms-1" style="font-size: 0.9rem;">
                        <a href="<?= esc($properties[$tag['property_id']]['ref_link'] ?? '#') ?>" target="_blank" class="apple-link-dark text-decoration-none fw-semibold">
                            <i class="bi bi-tag-fill text-muted me-1"></i>
                            <?= esc($properties[$tag['property_id']]['name'] ?? '—') ?>
                        </a>
                    </span>
                </div>

                <div class="mb-3">
                    <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Vendor</label>
                    <span class="fw-medium text-dark ms-1" style="font-size: 0.9rem;">
                        <?= esc($vendors[$tag['vendor_id']] ?? '—') ?>
                    </span>
                </div>

                <div class="mb-3">
                    <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Tag Name</label>
                    <span class="fw-medium text-dark ms-1" style="font-size: 0.9rem;">
                        <a href="<?= esc($tag['ref_link'] ?? '#') ?>" target="_blank" class="apple-link text-decoration-none fw-semibold">
                            <?= esc($tag['name']) ?>
                        </a>
                    </span>
                </div>

                <div class="mb-3">
                    <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Conditions</label>
                    <span class="fw-medium text-dark ms-1" style="font-size: 0.9rem;">
                        <code><?= esc($tag['conditions'] ?: '—') ?></code>
                    </span>
                </div>

                <div class="mb-3">
                    <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Status</label>
                    <div class="d-flex align-items-center gap-2 ms-1">
                        <span class="badge <?= status_class($tag['status']) ?>">
                            <?= esc(Constants::STATUS[$tag['status']] ?? 'Unknown') ?>
                        </span>
                        <?php if ($tag['status'] == Constants::STATUS_PENDING_VERIFICATION): ?>
                            <a href="<?= site_url('tags/verify/' . $tag['id']) ?>" 
                                class="badge bg-primary text-decoration-none"
                                onclick="return confirm('Have you verified the end date matches in AL?');">
                                Verify
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Adobe Launch End Date</label>
                    <span class="fw-medium ms-1 <?= date_class($tag['al_end_date'], $tag['status']) ?>" style="font-size: 0.9rem;">
                        <?= esc($tag['al_end_date'] ?: '—') ?>
                        <?php if ($tag['requested_date']) : ?>
                            <small class="text-muted ms-1">[<?= esc($tag['requested_date']) ?>]</small>
                        <?php endif; ?>
                    </span>
                </div>

                <!-- Requester Details Sub-Section -->
                <h6 class="fw-bold mt-4 mb-3 text-secondary border-top pt-3">Requester Details</h6>

                <div class="mb-3">
                    <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Name</label>
                    <span class="fw-medium text-dark ms-1" style="font-size: 0.9rem;">
                        <?= esc($tag['requester_name'] ?? '—') ?>
                    </span>
                </div>

                <div class="mb-3">
                    <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Email Address</label>
                    <span class="fw-medium text-dark ms-1" style="font-size: 0.9rem;">
                        <?php if (!empty($tag['requester_email'])): ?>
                            <a href="mailto:<?= esc($tag['requester_email']) ?>" class="apple-link text-decoration-none">
                                <?= esc($tag['requester_email']) ?>
                            </a>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </span>
                </div>

                <div class="mb-3">
                    <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Organization</label>
                    <span class="fw-medium text-dark ms-1" style="font-size: 0.9rem;">
                        <?= esc($organizations[$tag['requester_organization']] ?? '—') ?>
                    </span>
                </div>

                <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                    <a href="<?= site_url('tags') ?>" onclick="if (document.referrer) { window.history.back(); return false; }" class="apple-btn-secondary btn-sm px-3 text-decoration-none">Back</a>
                    <a href="<?= site_url('tags/update/' . $tag['id']) ?>" class="apple-btn-primary btn-sm px-3 text-decoration-none ms-auto">Edit Details</a>
                </div>
            </div>
        </div>

        <!-- JIRA References Section -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0 text-primary d-flex align-items-center gap-2 fw-bold">
                    <i class="bi bi-jira"></i>
                    <span>JIRA References</span>
                </h4>
                <a href="<?= site_url('jira/add-entry?tag_id='.$tag['id']) ?>" target="_blank" title="Add a new JIRA ticket" class="apple-btn-primary btn-sm px-3 text-decoration-none">
                    <i class="bi bi-plus-lg me-1"></i> Add Entry
                </a>
            </div>
            
            <div class="apple-table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="form-label-sm fw-semibold">#</th>
                                <th class="form-label-sm fw-semibold">Ticket ID</th>
                                <th class="form-label-sm fw-semibold">Type</th>
                                <th class="form-label-sm fw-semibold">Comments</th>
                                <th class="form-label-sm fw-semibold text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                                $hasTickets = false;
                                foreach ($jiraTickets as $jira) {
                                    if ($jira['tag_id'] == $tag['id']) {
                                        $hasTickets = true;
                                        break;
                                    }
                                }
                            ?>
                            <?php if (!$hasTickets): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-info-circle d-block fs-4 mb-2 text-secondary" style="opacity: 0.5;"></i>
                                        No JIRA references associated with this tag.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                    $displayedTickets = [];
                                    foreach ($jiraTickets as $jira):
                                        if ($jira['tag_id'] != $tag['id']) {
                                            continue;
                                        }
                                        if (in_array($jira['ticket_id'], $displayedTickets)) {
                                            continue;
                                        }
                                        $displayedTickets[] = $jira['ticket_id'];
                                        $ticketTagsList = [];
                                        $type = $jira['type'];
                                        if ($type == Constants::JIRA_TYPE_DATE_EXTENSION_RULE ||
                                                $type == Constants::JIRA_TYPE_NEW_TAG ||
                                                $type == Constants::JIRA_TYPE_NEW_RULE ||
                                                $type == Constants::JIRA_TYPE_UPDATE_RULE) {
                                            $ticketTags = explode(',', $jira['comments']);
                                            
                                            foreach ($ticketTags as $tagId) {
                                                $tagInfo = $tagModel->find($tagId);
                                                if ($tagInfo) {
                                                    $ticketTagsList[$tagInfo['name']] = $tagInfo['ref_link'];
                                                }
                                            }
                                        }
                                ?>
                                    <tr>
                                        <td class="fw-semibold"><?= $jira['id'] ?: '-' ?></td>
                                        <td>
                                            <a href="<?= Constants::JIRA_LINK ?><?= $jira['ticket_id'] ?: '#' ?>" target="_blank" class="apple-link text-decoration-none fw-semibold">
                                                <?= $jira['ticket_id'] ?: '-' ?>
                                            </a>
                                        </td>
                                        <!-- TYPE -->
                                        <td>
                                            <?php
                                                if ($type == Constants::JIRA_TYPE_NEW_TAG || $type == Constants::JIRA_TYPE_NEW_RULE) {
                                                    $request_id = $tagRequestsModel->getRequestByTicketId($jira['ticket_id']);
     
                                                    if ($request_id) {
                                            ?>
                                                        <a href="<?= site_url('/tag-requests/view/' . $request_id) ?>" target="_blank" class="apple-link-dark text-decoration-none fw-semibold">
                                                            <?= Constants::JIRA_TICKET_TYPES[$type] ?>
                                                        </a>
                                                <?php } else { ?>
                                                    <?= Constants::JIRA_TICKET_TYPES[$type] ?>
                                                <?php } ?>
     
                                            <?php
                                                } elseif ($type == Constants::JIRA_TYPE_UPDATE_RULE) {
                                                    $activityLogsModel = new TasksModel();
                                                    $activityLog = $activityLogsModel->where('ticket_id', $jira['ticket_id'])->first();
     
                                                    if ($activityLog) {
                                            ?>
                                                    <a href="<?= site_url('/tasks/view/' . $activityLog['id']) ?>" target="_blank" class="apple-link-dark text-decoration-none fw-semibold">
                                                        <?= Constants::JIRA_TICKET_TYPES[$type] ?>
                                                    </a>
     
                                                    <?php } else { ?>
                                                        <?= Constants::JIRA_TICKET_TYPES[$type] ?>
     
                                            <?php }} else { ?>
                                                <?= Constants::JIRA_TICKET_TYPES[$type] ?>
                                            <?php } ?>
                                        </td>
                                        <!-- COMMENTS -->
                                        <td>
                                            <?php if ($ticketTagsList): ?>
                                                <?php foreach ($ticketTagsList as $name => $link): ?>
                                                    <a href="<?= $link ?>" target="_blank" class="apple-link text-decoration-none"><?= esc($name) ?></a><br>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="text-secondary"><?= esc($jira['comments']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <!-- ACTIONS -->
                                        <td class="text-center">
                                            <a href="<?= site_url('jira/update-tags/' . $jira['id']) ?>" class="apple-btn-secondary btn-sm px-2 py-1" style="font-size: 0.75rem; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;" title="Update Related Tags">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
