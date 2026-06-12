<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= $log['title'] ?> : Details
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    use App\Models\Constants;
    helper('custom_functions');

    $subsidiary = Constants::ACTIVITY_LOG_SUBSIDIARIES[$log['subsidiary_id']] ?? null;
    $status = Constants::ACTIVITY_LOGS_STATUS[$log['status']] ?? 'Unknown';
    $type = Constants::ACTIVITY_TYPES[$log['type']] ?? 'Unknown';
    $team = Constants::TEAMS[$log['team_id']] ?? 'Unknown';
    $project = $log['project'] ?: 'Generic';
    $assignee = Constants::USERS[$log['assignee']] ?? Constants::USER_SYSTEM_LABEL;
    $size = Constants::ACTIVITY_SIZES[$log['size']] ?? 'Unknown';
    $priority = Constants::PRIORITIES[$log['priority']] ?? 'Unknown';

    // Fetch related tags if type is Renewal/Request and Team is Media Tagging
    $showRelatedTags = (($log['type'] == Constants::ACTIVITY_TYPE_RENEWAL || $log['type'] == Constants::ACTIVITY_TYPE_REQUEST) && $log['team_id'] == Constants::TEAM_MEDIA_TAGGING);
    $relatedTags = [];
    $jiraTicketRecord = null;
    if ($showRelatedTags && !empty($log['ticket_id'])) {
        $jiraHistoryModel = new \App\Models\JiraHistoryModel();
        $jiraTickets = $jiraHistoryModel->where('ticket_id', $log['ticket_id'])->findAll();
        if (!empty($jiraTickets)) {
            $jiraTicketRecord = $jiraTickets[0];
            $tagIds = [];
            foreach ($jiraTickets as $jt) {
                if (!empty($jt['tag_id'])) {
                    $tagIds[] = (int)$jt['tag_id'];
                }
                if (!empty($jt['comments'])) {
                    $rawIds = explode(',', $jt['comments']);
                    foreach ($rawIds as $rid) {
                        if (is_numeric(trim($rid))) {
                            $tagIds[] = (int)trim($rid);
                        }
                    }
                }
            }
            $tagIds = array_unique($tagIds);
            if (!empty($tagIds)) {
                $tagModel = new \App\Models\TagsModel();
                $relatedTags = $tagModel->whereIn('id', $tagIds)->findAll();
            }
        }
    }
?>

<?= csrf_field() ?>

<style>
.status-select-badge {
    display: inline-block;
    padding: 0.3em 1.6em 0.3em 0.6em !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
    line-height: 1.25 !important;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    border-radius: 6px !important;
    cursor: pointer;
    width: auto !important;
    height: auto !important;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-repeat: no-repeat;
    background-position: right 0.45rem center;
    background-size: 8px 10px;
    transition: background-color 0.25s ease, border-color 0.25s ease, color 0.25s ease, box-shadow 0.25s ease;
}

.status-select-badge:focus {
    outline: none;
    box-shadow: 0 0 0 0.15rem rgba(0, 123, 255, 0.25) !important;
}

/* Specific theme styles for Status and Priority */
.status-select-badge.bg-success {
    background-color: var(--color-badge-success-bg) !important;
    color: var(--color-badge-success-text) !important;
    border: 1px solid var(--color-badge-success-border) !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%232E7D32' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
}

.status-select-badge.bg-danger {
    background-color: var(--color-badge-danger-bg) !important;
    color: var(--color-badge-danger-text) !important;
    border: 1px solid var(--color-badge-danger-border) !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23C62828' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
}

.status-select-badge.bg-warning {
    background-color: var(--color-badge-warning-bg) !important;
    color: var(--color-badge-warning-text) !important;
    border: 1px solid var(--color-badge-warning-border) !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23F57F17' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
}

.status-select-badge.bg-secondary {
    background-color: var(--color-badge-secondary-bg) !important;
    color: var(--color-badge-secondary-text) !important;
    border: 1px solid var(--color-badge-secondary-border) !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2349494D' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
}

.status-select-badge.bg-info {
    background-color: var(--color-badge-primary-bg) !important;
    color: var(--color-badge-primary-text) !important;
    border: 1px solid var(--color-badge-primary-border) !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%231565C0' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
}

/* Specific theme styles for Assignee users */
.status-select-badge.bg-purple {
    background-color: var(--color-badge-purple-bg) !important;
    color: var(--color-badge-purple-text) !important;
    border: 1px solid var(--color-badge-purple-border) !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%237B1FA2' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
}

.status-select-badge.bg-pink {
    background-color: var(--color-badge-pink-bg) !important;
    color: var(--color-badge-pink-text) !important;
    border: 1px solid var(--color-badge-pink-border) !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23C2185B' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
}

.status-select-badge.bg-green {
    background-color: var(--color-badge-green-bg) !important;
    color: var(--color-badge-green-text) !important;
    border: 1px solid var(--color-badge-green-border) !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%231B5E20' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
}

.status-select-badge.bg-blue {
    background-color: var(--color-badge-blue-bg) !important;
    color: var(--color-badge-blue-text) !important;
    border: 1px solid var(--color-badge-blue-border) !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%230071E3' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
}

.status-select-badge.bg-orange {
    background-color: var(--color-badge-orange-bg) !important;
    color: var(--color-badge-orange-text) !important;
    border: 1px solid var(--color-badge-orange-border) !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23E65100' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
}

.task-description img {
    max-width: 100%;
    height: auto;
    object-fit: contain;
    border-radius: 8px;
    margin-top: 8px;
    margin-bottom: 8px;
}
.remove-related-tag-btn i {
    transition: opacity 0.2s, transform 0.2s;
}
.remove-related-tag-btn:hover i {
    opacity: 1 !important;
    transform: scale(1.15);
}
.remove-related-tag-btn:active i {
    transform: scale(0.9);
}
</style>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="<?= site_url('tasks') ?>" onclick="if (document.referrer) { window.history.back(); return false; }" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0; display: inline-flex;" title="Back to Tasks">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h2 class="mb-0 fw-bold"><?= esc($log['title']) ?></h2>
                <div class="d-flex gap-2 justify-content-start align-items-center flex-wrap mt-2">
                    <span class="badge" style="<?= random_bg_style($subsidiary) ?>" title="Subsidiary"><?= $subsidiary ?></span>
                    <span class="badge" style="<?= random_bg_style($type) ?>" title="Task Type"><?= $type ?></span>
                    <span class="badge <?= size_class($log['size']) ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= esc(size_description($log['size'])) ?>" style="cursor: help;"><?= $size ?></span>
                    <select class="badge status-select-badge <?= priority_class($log['priority']) ?> priority-select" data-log-id="<?= $log['id'] ?>" title="Priority">
                        <?php foreach (Constants::PRIORITIES as $val => $lbl): ?>
                            <option value="<?= $val ?>" <?= $log['priority'] == $val ? 'selected' : '' ?> class="bg-white text-dark text-start">
                                <?= esc($lbl) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select class="badge status-select-badge <?= assignee_class($log['assignee']) ?> assignee-select" data-log-id="<?= $log['id'] ?>" title="Assigned To">
                        <?php foreach (Constants::USERS as $val => $lbl): ?>
                            <option value="<?= $val ?>" <?= $log['assignee'] == $val ? 'selected' : '' ?> class="bg-white text-dark text-start">
                                <?= esc($lbl) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= site_url('tasks/create?duplicate_id=' . $log['id']) ?>" class="apple-btn-secondary btn-sm px-3">Duplicate</a>
            <a href="<?= site_url('tasks/update/' . $log['id']) ?>" class="apple-btn-primary btn-sm px-3">Edit</a>
        </div>
    </div>
</div>

<div class="row my-3 g-3">
    <!-- LOG DETAILS & ATTACHMENTS -->
    <div class="col-lg-6 col-md-12">
        <div class="apple-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-secondary" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Task Details</h5>
                <select class="badge status-select-badge <?= status_activity_logs_class($log['status']) ?> status-select" data-log-id="<?= $log['id'] ?>" title="Status" style="font-size: 0.85rem !important; padding: 0.35em 1.7em 0.35em 0.7em !important; height: fit-content; margin: 0;">
                    <?php foreach (Constants::ACTIVITY_LOGS_STATUS as $val => $lbl): ?>
                        <option value="<?= $val ?>" <?= $log['status'] == $val ? 'selected' : '' ?> class="bg-white text-dark text-start">
                            <?= esc($lbl) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Title -->
            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Title</span>
                <div class="fw-semibold mt-1" style="font-size: 0.95rem; color: var(--color-text-base);">
                    <?= esc($log['title'] ?: '—') ?>
                </div>
            </div>

            <!-- Type -->
            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">TASK TYPE</span>
                <div class="fw-semibold mt-1 badge" style="<?= random_bg_style($type) ?>">
                    <?= esc($type) ?>
                </div>
            </div>

            <!-- Size -->
            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">T-SHIRT SIZE</span>
                <div id="sizeValue" class="fw-semibold mt-1 badge <?= size_class($log['size']) ?>">
                    <?= esc($size) ?>
                </div>

                <div class="form-text mt-1 text-muted fw-medium" style="font-size: 0.75rem; min-height: 1.25rem;">
                    <i class="bi bi-info-circle-fill text-primary"></i> <?= Constants::ACTIVITY_SIZES_DESCRIPTIONS[$log['size']] ?>
                </div>
            </div>

            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">PRIORITY</span>
                <div class="fw-semibold mt-1 badge <?= priority_class($log['priority']) ?>">
                    <?= esc($priority) ?>
                </div>
            </div>

            <?php if ($showRelatedTags): ?>
                <div class="mb-3 pb-2 border-bottom border-light">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Related Tags</span>
                        <?php if ($jiraTicketRecord): ?>
                            <a href="<?= site_url('jira/update-tags/' . $jiraTicketRecord['id']) ?>" class="apple-btn-secondary btn-sm py-1 px-2.5 d-inline-flex align-items-center gap-1.5" style="font-size: 0.75rem; border-radius: 8px; font-weight: 500; height: auto;">
                                <i class="bi bi-pencil-square"></i>
                                <span>Edit Tags</span>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="mt-2" style="font-size: 0.9rem; line-height: 1.5; color: var(--color-text-base);">
                        <div id="related-tags-container" class="d-flex flex-column gap-2">
                            <?php if (!empty($relatedTags)): ?>
                                <?php foreach ($relatedTags as $tag): ?>
                                    <?php
                                        $diffDays = null;
                                        if (!empty($tag['al_end_date'])) {
                                            $alEndDate = strtotime($tag['al_end_date']);
                                            $now = time();
                                            $diffDays = (int)ceil(($alEndDate - $now) / 86400);
                                        }
                                    ?>
                                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 border border-light tag-relation-item" data-tag-id="<?= $tag['id'] ?>" style="background: #F5F5F7; transition: all 0.3s ease;">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <i class="bi bi-tag-fill text-primary" style="font-size: 0.85rem;"></i>
                                            <a href="<?= site_url('tags/view/' . $tag['id']) ?>" class="apple-link-dark fw-medium text-decoration-none">
                                                <?= esc($tag['name']) ?>
                                            </a>
                                            <span class="badge bg-secondary text-secondary small py-0.5 px-1.5" style="font-size: 0.7rem; border-radius: 4px;">
                                                #<?= $tag['id'] ?>
                                            </span>
                                            <?php if ($diffDays !== null): ?>
                                                <span class="small fw-semibold <?= days_class($diffDays) ?>" style="font-size: 0.8rem;">
                                                    (<?= $diffDays ?> <?= $diffDays == 1 ? 'day' : 'days' ?> remaining)
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <button type="button" class="btn btn-link text-danger p-0 border-0 remove-related-tag-btn" data-tag-id="<?= $tag['id'] ?>" data-tag-name="<?= esc($tag['name']) ?>" title="Remove Tag from Task" style="box-shadow: none; line-height: 1;">
                                            <i class="bi bi-trash" style="font-size: 1.05rem; opacity: 0.65; transition: opacity 0.2s;"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted" id="no-tags-placeholder">No related tags associated with this ticket ID.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="mb-3 pb-2 border-bottom border-light">
                    <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Description</span>
                    <div class="mt-1 task-description" style="font-size: 0.9rem; line-height: 1.5; color: var(--color-text-base);">
                        <?= $log['description'] ?: '—' ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Team -->
            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Team</span>
                <div class="fw-semibold mt-1 badge" style="<?= random_bg_style($team) ?>">
                    <?= esc($team ?: '—') ?>
                </div>
            </div>

            <!-- Project -->
            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Project</span>
                <div class="fw-semibold mt-1 badge" style="<?= random_bg_style($project) ?>">
                    <?= esc($project ?: '—') ?>
                </div>
            </div>

            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Ticket ID</span>
                <div class="mt-1 d-flex align-items-center gap-2" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?php if (!empty($log['ticket_id'])) : ?>
                        <a href="<?= Constants::JIRA_LINK . $log['ticket_id'] ?>" target="_blank" class="apple-link" id="ticket-id-link">
                            <?= esc($log['ticket_id']) ?>
                        </a>
                        <button type="button" class="btn btn-link p-0 text-muted border-0 d-inline-flex align-items-center justify-content-center" id="copy-ticket-btn" title="Copy Ticket ID" style="box-shadow: none;">
                            <i class="bi bi-copy fs-6" id="copy-ticket-icon"></i>
                        </button>
                    <?php else: ?>
                        <span class="text-muted">—</span>
                    <?php endif; ?>
                </div>
            </div>

            <h5 class="fw-bold mt-4 mb-3 text-secondary" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Request Details</h5>
            
            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Requested By</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc($log['requested_by'] ?: '—') ?>
                </div>
            </div>

            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Requested Date</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc($log['requested_date'] ?: '—') ?>
                </div>
            </div>

            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Start Date</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc($log['start_date'] ?: '—') ?>
                </div>
            </div>

            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Finished Date</span>
                <div class="mt-1" id="finished-date-value" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc($log['finished_date'] ?: '—') ?>
                </div>
            </div>

            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Created At</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc($log['created_at'] ?: '—') ?>
                </div>
            </div>

            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Updated At</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc($log['updated_at'] ?: '—') ?>
                </div>
            </div>

            <h5 class="fw-bold mt-4 mb-3 text-secondary" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Campaign Details</h5>
            
            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Department</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc(Constants::DWF_DEPARTMENTS[$log['department_id']] ?? '—') ?>
                </div>
            </div>
            
            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Campaign Status</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc(Constants::CAMPAIGN_STATUS[$log['campaign_status']] ?? '—') ?>
                </div>
            </div>

            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Campaign ID</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc($log['campaign_id'] ?: '—') ?>
                </div>
            </div>

            <!-- ATTACHMENTS -->
            <div class="mt-4 pt-3 border-top">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-secondary" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Attachment(s)</h5>
                    <i class="bi bi-info-circle-fill text-primary" title="Clicking the name will download the file" style="cursor: help;"></i>
                </div>

                <div class="mb-3">
                    <?php if (!empty($files)) : ?>
                        <div class="apple-table-container">
                            <table class="table table-hover align-middle mb-0 apple-table">
                                <tbody>
                                    <?php foreach ($files as $file) : ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bi bi-file-earmark-arrow-down-fill text-primary fs-5"></i>
                                                    <div class="text-truncate" style="max-width: 400px;">
                                                        <a href="<?= base_url('download.php?file=' . rawurlencode($file['file_path'])) ?>" download="<?= $file['file_name'] ?>" rel="noopener" class="apple-link-dark fw-medium text-decoration-none">
                                                            <?= esc($file['file_name']) ?>
                                                        </a>
                                                        <span class="text-muted ms-2" style="font-size: 0.75rem;">(<?= esc($file['created_at']) ?>)</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info py-2 px-3 m-0" style="font-size: 0.85rem;">No files attached to this task.</div>
                    <?php endif; ?>
                </div>

                <form action="<?= site_url('tasks/upload') ?>" method="post" enctype="multipart/form-data" class="mt-3 pt-3 border-top">
                    <input type="hidden" name="log_id" value="<?= esc($log['id']) ?>">
                    <input type="hidden" name="created_by" value="<?= session()->get('user_id') ?>">
                    <div class="d-flex flex-column gap-2">
                        <label for="file_path" class="form-label-xs text-primary fw-medium">Upload Attachment</label>
                        <div class="d-flex gap-2 align-items-center justify-content-between">
                            <input type="file" name="file_path" class="form-control form-control-xs" required style="max-width: 70%;">
                            <button type="submit" class="apple-btn-primary btn-sm px-3" onclick="return confirm('Confirm uploading of this file?');">
                                Upload
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                <a href="<?= site_url('tasks') ?>" class="apple-btn-secondary btn-sm px-3">Back</a>
                <a href="<?= site_url('tasks/create?duplicate_id=' . $log['id']) ?>" class="apple-btn-secondary btn-sm px-3">Duplicate</a>
                <a href="<?= site_url('tasks/update/' . $log['id']) ?>" class="apple-btn-primary btn-sm px-3">Edit</a>
            </div>
        </div>
    </div>

    <!-- COMMENTS -->
    <div class="col-lg-6 col-md-12">
        <div class="apple-card p-4 h-100">
            <h5 class="fw-bold mb-3 text-secondary" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Updates</h5>

            <div id="comments-container" class="overflow-auto pe-1 mb-3" style="max-height: 700px;">
                <?php if (!empty($comments)) :
                     foreach ($comments as $comment) : ?>
                        <?php if ($comment['commented_by'] != Constants::USER_SYSTEM) : ?>
                            <div class="mb-3 p-3 rounded-3 comment-item" style="background: #F5F5F7; border: 1px solid rgba(0, 0, 0, 0.03); display: block;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold text-primary" style="font-size: 0.8rem;">
                                        <?= esc(Constants::USERS[$comment['commented_by']] ?? 'Unknown') ?>
                                    </span>
                                    <span class="text-muted" style="font-size: 0.75rem;"><?= esc(format_comment_time($comment['created_at'])) ?></span>
                                </div>
                                <div style="font-size: 0.875rem; color: var(--color-text-base);"><?= $comment['comment'] ?></div>
                            </div>
                        <?php else: ?>
                            <div class="mb-3 p-3 rounded-3 comment-item" style="background: var(--color-alert-info-bg); border: 1px solid rgba(0, 113, 227, 0.08); display: block;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold text-info" style="font-size: 0.8rem;">
                                        <?= esc(Constants::USER_SYSTEM_LABEL) ?>
                                    </span>
                                    <span class="text-muted" style="font-size: 0.75rem;"><?= esc(format_comment_time($comment['created_at'])) ?></span>
                                </div>
                                <div style="font-size: 0.875rem; color: var(--color-text-base);"><?= $comment['comment'] ?></div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div id="no-comments-alert" class="alert alert-info py-2 px-3">No update(s) for this task yet.</div>
                <?php endif; ?>
            </div>

            <form id="comment-form" action="<?= site_url('tasks/comment') ?>" method="post" class="mt-3 pt-3 border-top">
                <?= csrf_field() ?>
                <div>
                    <label for="comment" class="form-label-xs text-primary fw-medium mb-2">Post an Update</label>
                    <textarea name="comment" id="comment"
                               class="form-control apple-textarea"
                               rows="3"
                               placeholder="Write an update..."><?= old('comment', $log['comment'] ?? '') ?></textarea>
                </div>
                <div class="d-flex gap-2 mt-2 align-items-center">
                    <input type="hidden" name="log_id" value="<?= $log['id'] ?>">
                    <input type="hidden" name="commented_by" value="<?= session()->get('user_id') ?>">
                    <button type="submit" class="apple-btn-primary btn-sm px-3">Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
    <div id="toastNotification" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true" style="display: none; min-width: 280px; border-radius: 12px; backdrop-filter: blur(10px); box-shadow: 0 8px 32px rgba(0,0,0,0.15); transition: opacity 0.3s ease;">
        <div class="d-flex p-3 align-items-center">
            <div class="me-2" id="toastIcon">
                <!-- Icon will be set dynamically -->
            </div>
            <div class="toast-body flex-grow-1 fw-medium" id="toastMessage" style="font-size: 0.9rem;">
                <!-- Message will be set dynamically -->
            </div>
            <button type="button" class="btn-close ms-auto" style="box-shadow: none; opacity: 0.6;" onclick="$('#toastNotification').fadeOut(300);"></button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $(document).ready(function() {
            // Reusable function to prepend system-generated or user comment with animation
            function prependComment(comment) {
                if ($('#comments-container').length === 0) {
                    return; // Comments container not present (e.g. for admins)
                }
                
                // Reset Textarea value and height back to default if manual posting form exists
                var textarea = document.getElementById('comment');
                if (textarea && !comment.is_system) {
                    textarea.value = '';
                    textarea.style.height = 'auto';
                }
                
                // Hide empty states
                $('#no-comments-alert').hide();
                
                // Build animated comment block
                var normalBg = comment.is_system 
                    ? 'var(--color-alert-info-bg)'
                    : '#F5F5F7';
                var normalBorder = comment.is_system
                    ? 'rgba(0, 113, 227, 0.08)'
                    : 'rgba(0, 0, 0, 0.03)';
                    
                var highlightBg = '#FFF9E6'; // Soft amber/yellow glow
                var highlightBorder = '#FFE28A'; // Soft yellow border
                var textClass = comment.is_system ? 'text-info' : 'text-primary';
                
                // Start with height, padding, margin, border at 0 and transition all of them for a smooth push down effect
                var commentHtml = `
                    <div class="mb-3 p-3 rounded-3 comment-item" 
                         style="background-color: ${highlightBg}; border: 1px solid transparent; opacity: 0; max-height: 0; padding-top: 0; padding-bottom: 0; margin-top: 0; margin-bottom: 0; overflow: hidden; transform: translateY(-15px); transition: max-height 0.5s cubic-bezier(0.16, 1, 0.3, 1), padding 0.5s cubic-bezier(0.16, 1, 0.3, 1), margin 0.5s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1), transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.5s cubic-bezier(0.16, 1, 0.3, 1);">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold ${textClass}" style="font-size: 0.8rem;">
                                ${comment.user_label}
                            </span>
                            <span class="text-muted" style="font-size: 0.75rem;">Just now</span>
                        </div>
                        <div style="font-size: 0.875rem; color: var(--color-text-base);">${comment.comment}</div>
                    </div>
                `;
                
                var $newComment = $(commentHtml);
                $('#comments-container').prepend($newComment);
                
                // 1. Smoothly scroll browser window to the updates card (faster 250ms)
                var cardTop = $('#comments-container').closest('.apple-card').offset().top;
                $('html, body').animate({
                    scrollTop: cardTop - 100 // offset for sticky nav bar
                }, 250);
                
                // 2. Smoothly scroll internal comments container to top (faster 250ms)
                $('#comments-container').animate({ scrollTop: 0 }, 250);
                
                // 3. Trigger the entry CSS transition with a delay of 380ms (so scroll completes first)
                setTimeout(function() {
                    $newComment.css({
                        'max-height': '500px', // large value to hold comment text
                        'padding-top': '16px', // equivalent to p-3 (1rem)
                        'padding-bottom': '16px',
                        'margin-bottom': '16px', // equivalent to mb-3 (1rem)
                        'opacity': '1',
                        'border-color': highlightBorder,
                        'transform': 'translateY(0)'
                    });
                    
                    // After transition completes, clean up constraints so content isn't clipped
                    setTimeout(function() {
                        $newComment.css({
                            'max-height': 'none',
                            'overflow': 'visible'
                        });
                    }, 500);
                    
                    // 4. Smoothly fade out the yellow highlight back to normal after 3 seconds
                    setTimeout(function() {
                        $newComment.css({
                            'transition': 'background-color 3s ease, border-color 3s ease',
                            'background-color': normalBg,
                            'border-color': normalBorder
                        });
                    }, 3000);
                }, 380);
            }

            // Reusable Premium Toast Notification function
            function showToast(type, message) {
                const toast = $('#toastNotification');
                const messageEl = $('#toastMessage');
                const iconEl = $('#toastIcon');

                messageEl.text(message);

                toast.removeClass('bg-success bg-danger bg-warning text-dark text-white border-0');

                if (type === 'success') {
                    toast.css({
                        'background-color': 'var(--color-alert-success-bg)',
                        'color': 'var(--color-alert-success-text)',
                        'border': '1px solid rgba(29, 138, 68, 0.2)'
                    });
                    iconEl.html('<i class="bi bi-check-circle-fill fs-5" style="color: var(--color-alert-success-text);"></i>');
                } else if (type === 'error') {
                    toast.css({
                        'background-color': 'var(--color-alert-danger-bg)',
                        'color': 'var(--color-alert-danger-text)',
                        'border': '1px solid rgba(222, 53, 53, 0.2)'
                    });
                    iconEl.html('<i class="bi bi-exclamation-triangle-fill fs-5" style="color: var(--color-alert-danger-text);"></i>');
                } else {
                    toast.css({
                        'background-color': 'var(--color-alert-warning-bg)',
                        'color': 'var(--color-alert-warning-text)',
                        'border': '1px solid rgba(180, 107, 0, 0.2)'
                    });
                    iconEl.html('<i class="bi bi-exclamation-circle-fill fs-5" style="color: var(--color-alert-warning-text);"></i>');
                }

                toast.fadeIn(300);

                if (window.toastTimeout) {
                    clearTimeout(window.toastTimeout);
                }
                window.toastTimeout = setTimeout(function() {
                    toast.fadeOut(300);
                }, 4000);
            }

            $('#copy-ticket-btn').on('click', function() {
                var ticketId = $('#ticket-id-link').text().trim();
                var temp = document.createElement('textarea');
                temp.value = ticketId;
                temp.style.position = 'fixed';
                temp.style.left = '-9999px';
                document.body.appendChild(temp);
                temp.focus();
                temp.select();
                document.execCommand('copy');
                document.body.removeChild(temp);

                var icon = $('#copy-ticket-icon');
                icon.removeClass('bi-copy').addClass('bi-check-lg text-success');
                setTimeout(function() {
                    icon.animate({ opacity: 0 }, 400, function() {
                        icon.removeClass('bi-check-lg text-success').addClass('bi-copy');
                        icon.animate({ opacity: 1 }, 800);
                    });
                }, 1200);
            });

            // Dropdown selection state tracking for reverts
            let previousValue = '';

            $('.status-select-badge').on('focus', function() {
                previousValue = $(this).val();
            }).on('change', function() {
                const dropdown = $(this);
                const logId = dropdown.data('log-id');
                const newValue = dropdown.val();
                const csrfToken = $('input[name="csrf_test_name"]').val();

                let url = '';
                let postData = { csrf_test_name: csrfToken };

                if (dropdown.hasClass('assignee-select')) {
                    url = '<?= site_url("tasks/update-assignee") ?>/' + logId;
                    postData.assignee = newValue;
                } else if (dropdown.hasClass('priority-select')) {
                    url = '<?= site_url("tasks/update-priority") ?>/' + logId;
                    postData.priority = newValue;
                } else {
                    url = '<?= site_url("tasks/update-status") ?>/' + logId;
                    postData.status = newValue;
                }

                // Low opacity to indicate loading state
                dropdown.css('opacity', '0.6');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: postData,
                    dataType: 'json',
                    success: function(response) {
                        dropdown.css('opacity', '1');
                        if (response.success) {
                            // Clear old status badge classes and apply new one
                            dropdown.removeClass('bg-secondary bg-warning bg-info bg-success bg-danger bg-purple bg-pink bg-green bg-blue bg-orange text-white text-dark');
                            dropdown.addClass(response.status_class);
                            
                            // Success toast notification
                            showToast('success', response.message);
                            previousValue = newValue; // update cached previous value

                            // If finished date should be updated
                            if (dropdown.hasClass('status-select')) {
                                if (newValue == '6') { // Completed
                                    const today = new Date().toISOString().split('T')[0];
                                    $('#finished-date-value').text(today);
                                }
                            }

                            // Prepend system update comment if returned
                            if (response.comment) {
                                prependComment(response.comment);
                            }
                        } else {
                            // Revert to previous value
                            dropdown.val(previousValue);
                            showToast('error', response.message || 'Failed to update.');
                        }

                        // Update CSRF token in the document
                        if (response.csrf_hash) {
                            $('input[name="csrf_test_name"]').val(response.csrf_hash);
                        }
                    },
                    error: function(xhr, status, error) {
                        dropdown.css('opacity', '1');
                        dropdown.val(previousValue);
                        showToast('error', 'An error occurred while updating.');
                    }
                });
            });

            // Auto-resize handler for modern textarea
            var textarea = document.getElementById('comment');
            if (textarea) {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            }

            $('#comment-form').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                
                // Ensure textarea is not empty
                if ($.trim($('#comment').val()) === '') {
                    alert('Please write an update before posting.');
                    return;
                }
                
                var formData = form.serialize();
                
                // Disable button to prevent double post
                var submitBtn = form.find('button[type="submit"]');
                submitBtn.prop('disabled', true).text('Posting...');
                
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        submitBtn.prop('disabled', false).text('Post');
                        
                        if (response.success) {
                            // Prepend manual comment
                            prependComment(response.comment);
                        } else {
                            alert(response.message || 'An error occurred.');
                        }
                        
                        // Always update CSRF token
                        if (response.csrf_hash) {
                            $('input[name="csrf_test_name"]').val(response.csrf_hash);
                        }
                    },
                    error: function(xhr, status, error) {
                        submitBtn.prop('disabled', false).text('Post');
                        console.error('AJAX Error:', error);
                        alert('Failed to post update. Please try again.');
                    }
                });
            });

            // Handle related tag deletion from list
            $(document).on('click', '.remove-related-tag-btn', function() {
                const btn = $(this);
                const tagId = btn.data('tag-id');
                const tagName = btn.data('tag-name');
                const logId = '<?= $log['id'] ?>';
                const csrfToken = $('input[name="csrf_test_name"]').val();

                if (!confirm(`Are you sure you want to remove tag "${tagName}" (#${tagId}) from this task?`)) {
                    return;
                }

                // Fade out row slightly to show processing
                const row = btn.closest('.tag-relation-item');
                row.css('opacity', '0.5');

                $.ajax({
                    url: '<?= site_url("tasks/remove-tag") ?>/' + logId,
                    type: 'POST',
                    data: {
                        tag_id: tagId,
                        csrf_test_name: csrfToken
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showToast('success', response.message);
                            
                            // Animate tag item removal
                            row.css({
                                'opacity': '0',
                                'transform': 'scale(0.9)',
                                'transition': 'all 0.3s ease'
                            });
                            
                            setTimeout(function() {
                                row.remove();
                                if ($('#related-tags-container .tag-relation-item').length === 0) {
                                    $('#related-tags-container').html('<span class="text-muted" id="no-tags-placeholder">No related tags associated with this ticket ID.</span>');
                                }
                            }, 300);

                            // Prepend system update comment
                            if (response.comment) {
                                prependComment(response.comment);
                            }
                        } else {
                            row.css('opacity', '1');
                            showToast('error', response.message || 'Failed to remove tag.');
                        }

                        // Always update CSRF token
                        if (response.csrf_hash) {
                            $('input[name="csrf_test_name"]').val(response.csrf_hash);
                        }
                    },
                    error: function(xhr, status, error) {
                        row.css('opacity', '1');
                        showToast('error', 'An error occurred while removing the tag.');
                    }
                });
            });
        });
    });
</script>

<?= $this->endSection() ?>
