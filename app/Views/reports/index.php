<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Weekly Reports & Analytics
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php 
    use App\Models\Constants; 
    $sysSettings = \App\Controllers\SettingsController::getSystemSettings();
    $hideAssignee = $sysSettings['hide_assignee_system_wide'] || $sysSettings['hide_assignee_weekly_reports'];
?>

<style>
    /* Premium Aesthetic Dashboard styling */
    .report-kpi-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 16px;
        padding: 20px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.015);
    }
    
    .report-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
    }

    .report-kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
    }

    .bg-kpi-purple { background-color: rgba(147, 51, 234, 0.1); color: rgb(147, 51, 234); }
    .bg-kpi-blue { background-color: rgba(59, 130, 246, 0.1); color: rgb(59, 130, 246); }
    .bg-kpi-green { background-color: rgba(16, 185, 129, 0.1); color: rgb(16, 185, 129); }
    .bg-kpi-orange { background-color: rgba(249, 115, 22, 0.1); color: rgb(249, 115, 22); }

    .report-tab-nav {
        border-bottom: 1px solid var(--color-border-subtle);
        margin-bottom: 20px;
    }

    .report-tab-btn {
        background: none;
        border: none;
        padding: 12px 20px;
        font-size: 0.92rem;
        font-weight: 500;
        color: var(--color-text-secondary);
        border-bottom: 2px solid transparent;
        transition: all 0.2s ease;
    }

    .report-tab-btn:hover {
        color: var(--color-primary);
    }

    .report-tab-btn.active {
        color: var(--color-primary);
        border-bottom-color: var(--color-primary);
        font-weight: 600;
    }
    
    .chart-container {
        position: relative;
        height: 230px;
        width: 100%;
    }

    /* Print styling overrides (screen-only controls) */
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="container-fluid px-0">
    <!-- Header Title & Export controls -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom no-print">
        <div>
            <h2 class="mb-0 fw-bold">Weekly Performance Report</h2>
            <p class="text-secondary small mb-0">Summarizing tagging requests, completed tasks, and tracking updates.</p>
        </div>
        
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('reports/export/excel?' . http_build_query(service('request')->getGet())) ?>" class="apple-btn-secondary btn-sm px-3 d-flex align-items-center gap-1.5" title="Export report to Excel">
                <i class="bi bi-file-earmark-excel-fill text-success"></i>
                <strong>Export Excel</strong>
            </a>
            <?php 
                $summaryParams = service('request')->getGet();
                $summaryParams['summary'] = '1';
                unset($summaryParams['inventory']);

                $inventoryParams = service('request')->getGet();
                $inventoryParams['inventory'] = '1';
                unset($inventoryParams['summary']);

                $detailedParams = service('request')->getGet();
                unset($detailedParams['summary'], $detailedParams['inventory']);
            ?>
            <a href="<?= site_url('reports/export/pdf?' . http_build_query($summaryParams)) ?>" target="_blank" class="apple-btn-secondary btn-sm px-3 d-flex align-items-center gap-1.5" title="Export summary report with charts and ongoing tasks to PDF">
                <i class="bi bi-file-earmark-bar-graph-fill text-primary"></i>
                <strong>Export Summary PDF</strong>
            </a>
            <a href="<?= site_url('reports/export/pdf?' . http_build_query($inventoryParams)) ?>" target="_blank" class="apple-btn-secondary btn-sm px-3 d-flex align-items-center gap-1.5" title="Export tags inventory changes to PDF">
                <i class="bi bi-tags-fill text-success"></i>
                <strong>Export Inventory PDF</strong>
            </a>
            <a href="<?= site_url('reports/export/pdf?' . http_build_query($detailedParams)) ?>" target="_blank" class="apple-btn-primary btn-sm px-3 d-flex align-items-center gap-1.5" title="Export detailed tables to PDF">
                <i class="bi bi-file-earmark-pdf-fill"></i>
                <strong>Export Detailed PDF</strong>
            </a>
        </div>
    </div>

    <!-- Period Selection / Filtering -->
    <div class="apple-settings-card mb-4 no-print">
        <form method="get" action="<?= site_url('reports') ?>" id="report-filter-form">
            <div class="row align-items-end g-3">
                <!-- Week Picker -->
                <div class="col-md-4 col-sm-6">
                    <label class="form-label small fw-semibold text-secondary mb-1.5">Select Week (ISO format)</label>
                    <div class="input-group">
                        <input type="week" name="week" class="form-control form-control-xs" value="<?= esc($week) ?>" onchange="clearCustomDates()" style="height: 36px; border-radius: 20px 0 0 20px; border-right: 0;">
                        <button type="button" class="btn btn-outline-secondary btn-xs" onclick="adjustWeek(-1)" title="Previous Week" style="height: 36px; border-radius: 0; border-right: 0;"><i class="bi bi-chevron-left"></i></button>
                        <button type="button" class="btn btn-outline-secondary btn-xs" onclick="adjustWeek(1)" title="Next Week" style="height: 36px; border-radius: 0 20px 20px 0;"><i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Custom Date Range -->
                <div class="col-md-4 col-sm-12">
                    <label class="form-label small fw-semibold text-secondary mb-1.5">Custom Date Range</label>
                    <div class="d-flex align-items-center gap-2 w-100">
                        <div class="position-relative flex-grow-1">
                            <input type="text" name="start_date" id="start_date" class="form-control form-control-xs pe-5" value="<?= esc($start_date) ?>" onchange="clearWeekPicker()" style="height: 36px; border-radius: 20px; padding-right: 32px !important;" placeholder="YYYY-MM-DD">
                            <i class="bi bi-calendar-event text-muted position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.875rem;"></i>
                        </div>
                        <span class="text-secondary small fw-semibold px-1" style="font-size: 0.85rem;">to</span>
                        <div class="position-relative flex-grow-1">
                            <input type="text" name="end_date" id="end_date" class="form-control form-control-xs pe-5" value="<?= esc($end_date) ?>" onchange="clearWeekPicker()" style="height: 36px; border-radius: 20px; padding-right: 32px !important;" placeholder="YYYY-MM-DD">
                            <i class="bi bi-calendar-event text-muted position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.875rem;"></i>
                        </div>
                    </div>
                </div>

                <!-- Quick actions -->
                <div class="col-md-4 col-sm-12">
                    <label class="form-label small fw-semibold text-secondary mb-1.5">&nbsp;</label>
                    <div class="d-flex gap-2 w-100" style="height: 36px;">
                        <button type="submit" class="apple-btn-primary btn-sm flex-grow-1 px-3 d-flex align-items-center justify-content-center gap-1.5" style="height: 36px; border-radius: 20px; font-weight: 600;">
                            <i class="bi bi-filter"></i> Apply
                        </button>
                        <button type="button" class="apple-btn-secondary btn-sm flex-grow-1 px-3 d-flex align-items-center justify-content-center" onclick="resetToCurrentWeek()" title="Reset to current week" style="height: 36px; border-radius: 20px; font-weight: 600; white-space: nowrap;">
                            Reset to Current Week
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Active Period Display -->
    <div class="mb-4">
        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <span class="badge bg-primary rounded-pill px-2.5" style="font-size: 0.8rem; font-weight: 600;">Active Period</span>
            <span>Week of <?= date('d M Y', strtotime($start_date)) ?> to <?= date('d M Y', strtotime($end_date)) ?></span>
        </h4>
        <p class="text-muted small mb-0">Showing metrics based on date boundaries: <code><?= esc($start_date) ?></code> to <code><?= esc($end_date) ?></code></p>
    </div>

    <!-- KPI Summary Row -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Tasks Completed -->
        <div class="col-xl-3 col-sm-6">
            <div class="report-kpi-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Tasks Completed</span>
                    <h3 class="fw-bold mb-0 text-dark"><?= count($completed_tasks) ?></h3>
                </div>
                <div class="report-kpi-icon bg-kpi-green">
                    <i class="bi bi-check2-circle"></i>
                </div>
            </div>
        </div>

        <!-- KPI 2: New Tasks -->
        <div class="col-xl-3 col-sm-6">
            <div class="report-kpi-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">New Tasks Created</span>
                    <h3 class="fw-bold mb-0 text-dark"><?= count($new_tasks) ?></h3>
                </div>
                <div class="report-kpi-icon bg-kpi-purple">
                    <i class="bi bi-plus-circle"></i>
                </div>
            </div>
        </div>

        <!-- KPI 3: Tag Requests -->
        <div class="col-xl-3 col-sm-6">
            <div class="report-kpi-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">New Tag Requests</span>
                    <h3 class="fw-bold mb-0 text-dark"><?= count($new_requests) ?></h3>
                </div>
                <div class="report-kpi-icon bg-kpi-blue">
                    <i class="bi bi-file-earmark-plus-fill"></i>
                </div>
            </div>
        </div>

        <!-- KPI 4: Tags Published -->
        <div class="col-xl-3 col-sm-6">
            <div class="report-kpi-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Requests Published</span>
                    <h3 class="fw-bold mb-0 text-dark"><?= count($published_requests) ?></h3>
                </div>
                <div class="report-kpi-icon bg-kpi-orange">
                    <i class="bi bi-rocket-takeoff-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics / Charts Row -->
    <div class="row g-4 mb-4 no-print">
        <!-- Chart 1: Task Status Breakdown -->
        <div class="col-lg-4 col-md-6">
            <div class="report-kpi-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Task Status Breakdown</h6>
                <div class="chart-container">
                    <canvas id="reportsStatusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart 2: Top Active Projects -->
        <div class="col-lg-4 col-md-6">
            <div class="report-kpi-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-folder-fill me-2 text-success"></i>Task Output by Project</h6>
                <div class="chart-container">
                    <canvas id="reportsProjectChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart 3: Subsidiary Tag Changes -->
        <div class="col-lg-4 col-md-12">
            <div class="report-kpi-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-building-fill me-2 text-info"></i>Tag Activity by Subsidiary</h6>
                <div class="chart-container">
                    <canvas id="reportsSubsidiaryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Listings Section -->
    <div class="apple-settings-card mb-4">
        <!-- Tabs Navigation -->
        <div class="report-tab-nav d-flex gap-2 border-bottom flex-wrap no-print">
            <button class="report-tab-btn active" id="tab-completed-btn" onclick="switchReportTab('completed')">
                <i class="bi bi-check-circle-fill text-success me-1"></i> Completed Tasks (<?= count($completed_tasks) ?>)
            </button>
            <button class="report-tab-btn" id="tab-requests-btn" onclick="switchReportTab('requests')">
                <i class="bi bi-file-earmark-plus-fill text-primary me-1"></i> Tag Requests (<?= count($new_requests) ?>)
            </button>
            <button class="report-tab-btn" id="tab-ongoing-btn" onclick="switchReportTab('ongoing')">
                <i class="bi bi-arrow-repeat text-warning me-1"></i> Ongoing Sprint Tasks (<?= count($ongoing_tasks) ?>)
            </button>
            <button class="report-tab-btn" id="tab-tags-btn" onclick="switchReportTab('tags')">
                <i class="bi bi-tags-fill text-purple me-1"></i> Tags Inventory Changes (<?= count($new_tags) + count($updated_tags) ?>)
            </button>
        </div>

        <!-- TAB 1: COMPLETED TASKS -->
        <div class="report-tab-content" id="tab-completed">
            <h5 class="fw-bold mb-3 d-none d-print-block"><i class="bi bi-check2-circle me-2 text-success"></i>Completed Tasks</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--color-border-subtle);">
                            <th style="width: 110px;">Subsidiary</th>
                            <th>Task Title</th>
                            <th style="width: 120px;">Ticket ID</th>
                            <th style="width: 100px;">Priority</th>
                            <?php if (!$hideAssignee): ?>
                                <th style="width: 120px;">Assignee</th>
                            <?php endif; ?>
                            <th style="width: 110px;">Finished Date</th>
                            <th>Latest Progress Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($completed_tasks)): ?>
                            <tr>
                                <td colspan="<?= $hideAssignee ? 6 : 7 ?>" class="text-center py-4 text-muted">No completed tasks recorded in this week.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($completed_tasks as $task): 
                                $subLabel = Constants::ACTIVITY_LOG_SUBSIDIARIES[$task['subsidiary_id']] ?? 'Unknown';
                                $prioLabel = Constants::PRIORITIES[$task['priority']] ?? 'Unknown';
                                $assigneeLabel = Constants::USERS[$task['assignee']] ?? Constants::USER_SYSTEM_LABEL;
                                $db = \Config\Database::connect();
                                $commentRow = $db->table('activity_log_comments')
                                    ->where('log_id', $task['id'])
                                    ->orderBy('created_at', 'DESC')
                                    ->limit(1)
                                    ->get()
                                    ->getRowArray();
                                $lastComment = $commentRow ? strip_tags($commentRow['comment']) : '—';
                            ?>
                                <tr>
                                    <td><span class="fw-bold"><?= esc($subLabel) ?></span></td>
                                    <td>
                                        <a href="<?= site_url('tasks/view/' . $task['id']) ?>" class="fw-semibold text-decoration-none text-dark hover-primary" target="_blank">
                                            <?= esc($task['title']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($task['ticket_id']): ?>
                                            <a href="<?= Constants::JIRA_LINK . esc($task['ticket_id']) ?>" target="_blank" class="badge bg-secondary-bg text-secondary border font-monospace text-decoration-none">
                                                <i class="bi bi-ticket-perforated me-1"></i><?= esc($task['ticket_id']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= priority_class($task['priority']) ?> rounded-pill" style="font-size: 0.72rem; font-weight: 600;">
                                            <?= esc($prioLabel) ?>
                                        </span>
                                    <?php if (!$hideAssignee): ?>
                                        <td>
                                            <span class="text-secondary small fw-medium"><?= esc($assigneeLabel) ?></span>
                                        </td>
                                    <?php endif; ?>
                                    <td><code class="text-dark"><?= esc(date('d-m-Y', strtotime($task['finished_date']))) ?></code></td>
                                    <td style="font-size: 0.8rem; color: var(--color-text-secondary); max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <?= esc($lastComment) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 2: TAG REQUESTS -->
        <div class="report-tab-content d-none" id="tab-requests">
            <h5 class="fw-bold mb-3 d-none d-print-block"><i class="bi bi-file-earmark-plus me-2 text-primary"></i>New Tag Requests</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--color-border-subtle);">
                            <th style="width: 140px;">Ticket / Reference</th>
                            <th>Request Type</th>
                            <th>Website Property</th>
                            <th>Tracking Vendor</th>
                            <th>Workflow Status</th>
                            <th>Requester</th>
                            <th style="width: 120px;">Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($new_requests)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No new tag requests submitted in this week.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($new_requests as $req): 
                                $ticket = $req['type'] == Constants::JIRA_TYPE_NEW_RULE ? $req['new_rule_ticket'] : $req['new_tag_ticket'];
                                $reqType = $req['type'] == Constants::JIRA_TYPE_NEW_RULE ? 'New Rule' : 'New Tag';
                                $statusLabel = Constants::STATUS_REQUESTS[$req['status']] ?? 'Unknown';
                            ?>
                                <tr>
                                    <td>
                                        <a href="<?= site_url('tag-requests/view/' . $req['id']) ?>" class="fw-bold text-decoration-none" target="_blank">
                                            <?= esc($ticket ?: '#' . $req['id']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-bg text-secondary border rounded-pill px-2.5 py-1" style="font-size: 0.72rem; font-weight: 600;">
                                            <?= esc($reqType) ?>
                                        </span>
                                    </td>
                                    <td class="fw-medium text-dark"><?= esc($req['property_name'] ?: 'N/A') ?></td>
                                    <td><span class="text-secondary small fw-medium"><?= esc($req['vendor_name'] ?: 'N/A') ?></span></td>
                                    <td>
                                        <span class="badge <?= status_request_class($req['status']) ?> rounded-pill" style="font-size: 0.72rem; font-weight: 600;">
                                            <?= esc($statusLabel) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold"><?= esc($req['requester_name'] ?: '—') ?></div>
                                        <div class="text-muted" style="font-size: 0.7rem;"><?= esc($req['requester_email'] ?: '') ?></div>
                                    </td>
                                    <td><code class="text-secondary"><?= esc(date('d-m-Y', strtotime($req['created_at']))) ?></code></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 3: ONGOING SPRINT TASKS -->
        <div class="report-tab-content d-none" id="tab-ongoing">
            <h5 class="fw-bold mb-3 d-none d-print-block"><i class="bi bi-arrow-repeat me-2 text-warning"></i>Ongoing Tasks</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--color-border-subtle);">
                            <th style="width: 110px;">Subsidiary</th>
                            <th>Ongoing Task Title</th>
                            <th style="width: 120px;">Ticket ID</th>
                            <th style="width: 100px;">Priority</th>
                            <?php if (!$hideAssignee): ?>
                                <th style="width: 120px;">Assignee</th>
                            <?php endif; ?>
                            <th style="width: 120px;">Current Status</th>
                            <th style="width: 110px;">Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ongoing_tasks)): ?>
                            <tr>
                                <td colspan="<?= $hideAssignee ? 6 : 7 ?>" class="text-center py-4 text-muted">No active ongoing tasks recorded.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ongoing_tasks as $task): 
                                $subLabel = Constants::ACTIVITY_LOG_SUBSIDIARIES[$task['subsidiary_id']] ?? 'Unknown';
                                $prioLabel = Constants::PRIORITIES[$task['priority']] ?? 'Unknown';
                                $assigneeLabel = Constants::USERS[$task['assignee']] ?? Constants::USER_SYSTEM_LABEL;
                                $statusLabel = Constants::ACTIVITY_LOGS_STATUS[$task['status']] ?? 'Unknown';
                            ?>
                                <tr>
                                    <td><span class="fw-bold"><?= esc($subLabel) ?></span></td>
                                    <td>
                                        <a href="<?= site_url('tasks/view/' . $task['id']) ?>" class="fw-semibold text-decoration-none text-dark hover-primary" target="_blank">
                                            <?= esc($task['title']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($task['ticket_id']): ?>
                                            <a href="<?= Constants::JIRA_LINK . esc($task['ticket_id']) ?>" target="_blank" class="badge bg-secondary-bg text-secondary border font-monospace text-decoration-none">
                                                <?= esc($task['ticket_id']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= priority_class($task['priority']) ?> rounded-pill" style="font-size: 0.72rem; font-weight: 600;">
                                            <?= esc($prioLabel) ?>
                                        </span>
                                    </td>
                                    <?php if (!$hideAssignee): ?>
                                        <td>
                                            <span class="text-secondary small fw-medium"><?= esc($assigneeLabel) ?></span>
                                        </td>
                                    <?php endif; ?>
                                    <td>
                                        <span class="badge <?= status_activity_logs_class($task['status']) ?> rounded-pill" style="font-size: 0.72rem; font-weight: 600;">
                                            <?= esc($statusLabel) ?>
                                        </span>
                                    </td>
                                    <td><code class="text-secondary"><?= esc(date('d-m-Y', strtotime($task['created_at']))) ?></code></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 4: TAGS INVENTORY CHANGES -->
        <div class="report-tab-content d-none" id="tab-tags">
            <h5 class="fw-bold mb-3 d-none d-print-block"><i class="bi bi-tags me-2 text-purple"></i>Tracking Tag Updates</h5>
            <div class="row g-4">
                <!-- Section: New Tags Added -->
                <div class="col-md-6 border-end">
                    <h6 class="fw-bold text-success border-bottom pb-2 mb-3">
                        <i class="bi bi-plus-circle me-1"></i> New Tags Registered (<?= count($new_tags) ?>)
                    </h6>
                    <div style="max-height: 450px; overflow-y: auto;">
                        <table class="table table-sm align-middle table-borderless">
                            <tbody>
                                <?php if (empty($new_tags)): ?>
                                    <tr><td class="text-muted py-3 text-center">No new tags created in this week.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($new_tags as $tag): 
                                        $sub = Constants::SUBSIDIARIES[$tag['subsidiary_id']] ?? 'N/A';
                                        $statusLabel = Constants::STATUS[$tag['status']] ?? 'N/A';
                                    ?>
                                        <tr class="border-bottom py-2">
                                            <td>
                                                <div class="fw-bold text-dark" style="font-size: 0.85rem;"><?= esc($tag['name']) ?></div>
                                                <small class="text-muted font-monospace"><?= esc($sub) ?> • <?= esc($tag['property_name']) ?></small>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge <?= status_class($tag['status']) ?> rounded-pill" style="font-size: 0.68rem; font-weight: 600;">
                                                    <?= esc($statusLabel) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Section: Existing Tags Updated -->
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                        <i class="bi bi-pencil-square me-1"></i> Existing Tags Updated (<?= count($updated_tags) ?>)
                    </h6>
                    <div style="max-height: 450px; overflow-y: auto;">
                        <table class="table table-sm align-middle table-borderless">
                            <tbody>
                                <?php if (empty($updated_tags)): ?>
                                    <tr><td class="text-muted py-3 text-center">No tags modified in this week.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($updated_tags as $tag): 
                                        $sub = Constants::SUBSIDIARIES[$tag['subsidiary_id']] ?? 'N/A';
                                        $statusLabel = Constants::STATUS[$tag['status']] ?? 'N/A';
                                    ?>
                                        <tr class="border-bottom py-2">
                                            <td>
                                                <div class="fw-bold text-dark" style="font-size: 0.85rem;"><?= esc($tag['name']) ?></div>
                                                <small class="text-muted font-monospace"><?= esc($sub) ?> • <?= esc($tag['property_name']) ?></small>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge <?= status_class($tag['status']) ?> rounded-pill" style="font-size: 0.68rem; font-weight: 600;">
                                                    <?= esc($statusLabel) ?>
                                                </span>
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
</div>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Tab controller
    function switchReportTab(tabId) {
        document.querySelectorAll('.report-tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelectorAll('.report-tab-content').forEach(content => {
            content.classList.add('d-none');
        });

        document.getElementById(`tab-${tabId}-btn`).classList.add('active');
        document.getElementById(`tab-${tabId}`).classList.remove('d-none');
    }

    // Helper functions for week/date selector UI
    function adjustWeek(direction) {
        const weekInput = document.querySelector('input[name="week"]');
        if (!weekInput || !weekInput.value) return;

        const parts = weekInput.value.split('-W');
        if (parts.length !== 2) return;

        let year = parseInt(parts[0], 10);
        let week = parseInt(parts[1], 10);

        week += direction;

        if (week > 53) {
            year += 1;
            week = 1;
        } else if (week < 1) {
            year -= 1;
            week = 52;
        }

        const weekStr = week.toString().padStart(2, '0');
        weekInput.value = `${year}-W${weekStr}`;
        
        clearCustomDates();
        document.getElementById('report-filter-form').submit();
    }

    function clearCustomDates() {
        document.getElementById('start_date').value = '';
        document.getElementById('end_date').value = '';
    }

    function clearWeekPicker() {
        const weekInput = document.querySelector('input[name="week"]');
        if (weekInput) {
            weekInput.value = '';
        }
    }

    function resetToCurrentWeek() {
        const weekInput = document.querySelector('input[name="week"]');
        if (weekInput) {
            const today = new Date();
            const startOfYear = new Date(today.getFullYear(), 0, 1);
            const pastDays = (today - startOfYear) / 86400000;
            const weekNumber = Math.ceil((pastDays + startOfYear.getDay() + 1) / 7);
            const weekStr = weekNumber.toString().padStart(2, '0');
            weekInput.value = `${today.getFullYear()}-W${weekStr}`;
        }
        clearCustomDates();
        document.getElementById('report-filter-form').submit();
    }

    // Chart.js renderers & date pickers
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize flatpickr on custom date range inputs
        if (typeof flatpickr !== 'undefined') {
            flatpickr("#start_date, #end_date", {
                dateFormat: "Y-m-d",
                disableMobile: "true"
            });
        }

        Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        Chart.defaults.color = '#86868B';

        // 1. Task Status Chart
        const statusCounts = <?= json_encode($status_counts) ?>;
        const statusCtx = document.getElementById('reportsStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusCounts),
                datasets: [{
                    data: Object.values(statusCounts),
                    backgroundColor: ['#86868B', '#0071E3', '#F59E0B', '#10B981', '#EF4444'],
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            padding: 10
                        }
                    }
                }
            }
        });

        // 2. Project Output Chart
        const projectData = <?= json_encode($project_activity) ?>;
        const projectCtx = document.getElementById('reportsProjectChart').getContext('2d');
        new Chart(projectCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(projectData).map(k => k.length > 12 ? k.substring(0, 10) + '..' : k),
                datasets: [{
                    label: 'Tasks Created/Completed',
                    data: Object.values(projectData),
                    backgroundColor: 'rgba(59, 130, 246, 0.75)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // 3. Subsidiary Activity Chart
        const subsidiaryData = <?= json_encode($subsidiary_activity) ?>;
        // Filter out zero-count subsidiaries to keep it clean
        const activeSubs = {};
        Object.entries(subsidiaryData).forEach(([key, val]) => {
            if (val > 0) activeSubs[key] = val;
        });

        const subCtx = document.getElementById('reportsSubsidiaryChart').getContext('2d');
        if (Object.keys(activeSubs).length === 0) {
            // Draw empty placeholder info
            subCtx.font = "14px 'Inter'";
            subCtx.fillStyle = "#86868B";
            subCtx.textAlign = "center";
            subCtx.fillText("No tag activities recorded for subsidiaries.", subCtx.canvas.width/2, subCtx.canvas.height/2);
        } else {
            new Chart(subCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(activeSubs),
                    datasets: [{
                        label: 'Tag Creations/Updates',
                        data: Object.values(activeSubs),
                        backgroundColor: 'rgba(16, 185, 129, 0.75)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }
    });
</script>

<!-- Flatpickr Datepicker Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<?= $this->endSection() ?>
