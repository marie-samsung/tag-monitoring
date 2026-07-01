<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Performance Report: <?= esc($start_date) ?> to <?= esc($end_date) ?></title>
    <!-- Premium Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS for grid structure -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #1d1d1f;
            background-color: #ffffff;
            padding: 30px;
            font-size: 0.82rem;
        }
        .report-header {
            border-top: 4px solid #0071e3;
            padding-top: 15px;
            border-bottom: 2px solid #e5e5ea;
            padding-bottom: 15px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .report-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }
        .report-subtitle {
            font-size: 0.88rem;
            color: #86868b;
            margin-bottom: 0;
        }
        .report-meta {
            text-align: right;
            font-size: 0.78rem;
            color: #86868b;
        }
        
        .kpi-row {
            margin-bottom: 25px;
        }
        .kpi-box {
            border: 1px solid #e5e5ea;
            border-radius: 12px;
            padding: 16px 15px;
            background-color: #ffffff;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.015);
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
        }
        .kpi-value {
            font-size: 1.8rem;
            font-weight: 750;
            margin-top: 4px;
            line-height: 1.1;
        }
        .kpi-label {
            font-size: 0.7rem;
            color: #86868b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        .kpi-card-completed { border-top: 4px solid #10b981; }
        .kpi-card-new { border-top: 4px solid #9333ea; }
        .kpi-card-requests { border-top: 4px solid #3b82f6; }
        .kpi-card-published { border-top: 4px solid #f97316; }

        .text-success { color: #10b981 !important; }
        .text-purple { color: #9333ea !important; }
        .text-primary { color: #3b82f6 !important; }
        .text-warning { color: #f97316 !important; }

        .section-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 12px;
            border-bottom: 1px solid #d1d1d6;
            padding-bottom: 6px;
            page-break-after: avoid;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 0.78rem;
        }
        .report-table th {
            background-color: #f5f5f7;
            font-weight: 600;
            color: #1d1d1f;
            border: 1px solid #d1d1d6;
            padding: 8px 10px;
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .report-table td {
            border: 1px solid #e5e5ea;
            padding: 8px 10px;
            vertical-align: top;
            line-height: 1.35;
        }
        .report-table tr:nth-child(even) {
            background-color: #fafafc;
        }

        .badge-prio {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .priority-1 { background-color: #f4f9ff; color: #0071e3; border: 1px solid rgba(0, 113, 227, 0.15); }
        .priority-2 { background-color: #f5f5f7; color: #1d1d1f; border: 1px solid rgba(29, 29, 31, 0.15); }
        .priority-3 { background-color: #fffbf0; color: #b46b00; border: 1px solid rgba(180, 107, 0, 0.15); }
        .priority-4 { background-color: #fff4f4; color: #de3535; border: 1px solid rgba(222, 53, 53, 0.15); }
        .priority-5 { background-color: #ffe5e5; color: #d32f2f; border: 1px solid rgba(211, 47, 47, 0.25); font-weight: 700; }

        .badge-stat {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .status-todo { background-color: #f5f5f7; color: #86868b; border: 1px solid #d1d1d6; }
        .status-inprogress { background-color: #fffbf0; color: #b46b00; border: 1px solid rgba(180, 107, 0, 0.15); }
        .status-completed { background-color: #f4fbf6; color: #1d8a44; border: 1px solid rgba(29, 138, 68, 0.15); }
        .status-blocked { background-color: #fff4f4; color: #de3535; border: 1px solid rgba(222, 53, 53, 0.15); }

        .tag-row-item {
            font-size: 0.78rem;
            border-bottom: 1px solid #e5e5ea;
            padding: 6px 0;
        }

        .page-break {
            page-break-before: always;
        }

        .pdf-charts-row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -10px;
            margin-left: -10px;
            margin-top: 10px;
        }
        .pdf-chart-col {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 10px;
            box-sizing: border-box;
        }
        .pdf-chart-col-full {
            flex: 0 0 100%;
            max-width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }
        .pdf-chart-card {
            border: 1px solid #e5e5ea;
            border-radius: 12px;
            padding: 15px;
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.015);
            height: 100%;
        }
        .pdf-chart-status { border-top: 4px solid #0071e3; }
        .pdf-chart-project { border-top: 4px solid #10b981; }
        .pdf-chart-subsidiary { border-top: 4px solid #9333ea; }

        .pdf-chart-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #1d1d1f;
            margin-bottom: 12px;
            border-bottom: 1px solid #e5e5ea;
            padding-bottom: 6px;
        }
        .pdf-chart-container {
            position: relative;
            height: 240px;
            width: 100%;
        }

        @media print {
            body {
                padding: 0;
            }
            @page {
                size: A4 portrait;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>
    <?php 
        use App\Models\Constants; 
        $sysSettings = \App\Controllers\SettingsController::getSystemSettings();
        $hideAssignee = $sysSettings['hide_assignee_system_wide'] || $sysSettings['hide_assignee_weekly_reports'];
        $reportType = $report_type ?? 'detailed';
    ?>

    <!-- Report Header -->
    <div class="report-header">
        <div>
            <h1 class="report-title">Weekly Performance &amp; Activity Report</h1>
            <p class="report-subtitle">Martech Media Tagging System Audit</p>
        </div>
        <div class="report-meta">
            <div>Period: <strong><?= esc(date('d-m-Y', strtotime($start_date))) ?> to <?= esc(date('d-m-Y', strtotime($end_date))) ?></strong></div>
            <div>Generated: <strong><?= date('d-m-Y H:i') ?></strong></div>
        </div>
    </div>

    <!-- KPIs Box Summary -->
    <div class="row kpi-row g-3 mb-4">
        <div class="col-3">
            <div class="kpi-box kpi-card-completed">
                <div class="kpi-label">Tasks Completed</div>
                <div class="kpi-value text-success"><?= count($completed_tasks) ?></div>
            </div>
        </div>
        <div class="col-3">
            <div class="kpi-box kpi-card-new">
                <div class="kpi-label">New Tasks Created</div>
                <div class="kpi-value text-purple"><?= count($new_tasks) ?></div>
            </div>
        </div>
        <div class="col-3">
            <div class="kpi-box kpi-card-requests">
                <div class="kpi-label">Tag Requests Submitted</div>
                <div class="kpi-value text-primary"><?= count($new_requests) ?></div>
            </div>
        </div>
        <div class="col-3">
            <div class="kpi-box kpi-card-published">
                <div class="kpi-label">Requests Published</div>
                <div class="kpi-value text-warning"><?= count($published_requests) ?></div>
            </div>
        </div>
    </div>

    <?php if ($reportType === 'detailed'): ?>
    <!-- Completed Tasks Section -->
    <div class="section-title">Completed Tasks</div>
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 80px;">Subsidiary</th>
                <th>Task Title</th>
                <th style="width: 100px;">Ticket ID</th>
                <th style="width: 70px;">Priority</th>
                <?php if (!$hideAssignee): ?>
                    <th style="width: 90px;">Assignee</th>
                <?php endif; ?>
                <th style="width: 90px;">Finished Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($completed_tasks)): ?>
                <tr>
                    <td colspan="<?= $hideAssignee ? 5 : 6 ?>" class="text-center py-3 text-muted">No completed tasks recorded in this period.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($completed_tasks as $task): 
                    $subLabel = Constants::ACTIVITY_LOG_SUBSIDIARIES[$task['subsidiary_id']] ?? 'Unknown';
                    $prioLabel = Constants::PRIORITIES[$task['priority']] ?? 'Unknown';
                    $assigneeLabel = Constants::USERS[$task['assignee']] ?? Constants::USER_SYSTEM_LABEL;
                    $prioClass = 'priority-2';
                    if ($task['priority'] == Constants::PRIORITY_LOW) $prioClass = 'priority-1';
                    if ($task['priority'] == Constants::PRIORITY_HIGH) $prioClass = 'priority-3';
                    if ($task['priority'] == Constants::PRIORITY_CRITICAL) $prioClass = 'priority-4';
                    if ($task['priority'] == Constants::PRIORITY_BLOCKER) $prioClass = 'priority-5';
                ?>
                    <tr>
                        <td><strong><?= esc($subLabel) ?></strong></td>
                        <td><?= esc($task['title']) ?></td>
                        <td><code><?= esc($task['ticket_id'] ?: '—') ?></code></td>
                        <td><span class="badge-prio <?= $prioClass ?>"><?= esc($prioLabel) ?></span></td>
                        <?php if (!$hideAssignee): ?>
                            <td><?= esc($assigneeLabel) ?></td>
                        <?php endif; ?>
                        <td><?= esc(date('d-m-Y', strtotime($task['finished_date']))) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Tag Requests Section -->
    <div class="section-title">New Tag Requests</div>
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 110px;">Ticket ID / Ref</th>
                <th style="width: 80px;">Request Type</th>
                <th>Website Property</th>
                <th>Tracking Vendor</th>
                <th style="width: 100px;">Workflow Status</th>
                <th style="width: 90px;">Created Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($new_requests)): ?>
                <tr>
                    <td colspan="6" class="text-center py-3 text-muted">No new tag requests submitted in this period.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($new_requests as $req): 
                    $ticket = $req['type'] == Constants::JIRA_TYPE_NEW_RULE ? $req['new_rule_ticket'] : $req['new_tag_ticket'];
                    $reqType = $req['type'] == Constants::JIRA_TYPE_NEW_RULE ? 'New Rule' : 'New Tag';
                    $statusLabel = Constants::STATUS_REQUESTS[$req['status']] ?? 'Unknown';
                    
                    $statusClass = 'status-inprogress';
                    if ($req['status'] == Constants::STATUS_REQUEST_HQ_REVIEW) $statusClass = 'status-todo';
                    if ($req['status'] == Constants::STATUS_REQUEST_PUBLISHED) $statusClass = 'status-completed';
                    if ($req['status'] == Constants::STATUS_REQUEST_REJECTED) $statusClass = 'status-blocked';
                ?>
                    <tr>
                        <td><strong><?= esc($ticket ?: '#' . $req['id']) ?></strong></td>
                        <td><?= esc($reqType) ?></td>
                        <td><?= esc($req['property_name'] ?: 'N/A') ?></td>
                        <td><?= esc($req['vendor_name'] ?: 'N/A') ?></td>
                        <td><span class="badge-stat <?= $statusClass ?>"><?= esc($statusLabel) ?></span></td>
                        <td><?= esc(date('d-m-Y', strtotime($req['created_at']))) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <?php endif; ?>

    <?php if ($reportType === 'summary'): ?>
    <!-- Summary Charts Section -->
    <div class="pdf-charts-row">
        <!-- Chart 1: Task Status Breakdown -->
        <div class="pdf-chart-col">
            <div class="pdf-chart-card pdf-chart-status">
                <div class="pdf-chart-title">Task Status Breakdown</div>
                <div class="pdf-chart-container">
                    <canvas id="pdfStatusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart 2: Task Output by Project -->
        <div class="pdf-chart-col">
            <div class="pdf-chart-card pdf-chart-project">
                <div class="pdf-chart-title">Task Output by Project</div>
                <div class="pdf-chart-container">
                    <canvas id="pdfProjectChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart 3: Tag Activity by Subsidiary -->
        <div class="pdf-chart-col-full">
            <div class="pdf-chart-card pdf-chart-subsidiary">
                <div class="pdf-chart-title">Tag Activity by Subsidiary</div>
                <div class="pdf-chart-container" style="height: 240px;">
                    <canvas id="pdfSubsidiaryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($reportType === 'detailed'): ?>
    <div class="page-break"></div>
    <?php endif; ?>

    <?php if ($reportType === 'detailed' || $reportType === 'summary'): ?>
    <!-- Ongoing Sprint Tasks Section -->
    <div class="section-title">Ongoing Sprint Workload</div>
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 80px;">Subsidiary</th>
                <th>Ongoing Task Title</th>
                <th style="width: 100px;">Ticket ID</th>
                <th style="width: 70px;">Priority</th>
                <th style="width: 100px;">Requester</th>
                <?php if (!$hideAssignee): ?>
                    <th style="width: 90px;">Assignee</th>
                <?php endif; ?>
                <th style="width: 100px;">Current Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ongoing_tasks)): ?>
                <tr>
                    <td colspan="<?= $hideAssignee ? 6 : 7 ?>" class="text-center py-3 text-muted">No ongoing tasks active.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($ongoing_tasks as $task): 
                    $subLabel = Constants::ACTIVITY_LOG_SUBSIDIARIES[$task['subsidiary_id']] ?? 'Unknown';
                    $prioLabel = Constants::PRIORITIES[$task['priority']] ?? 'Unknown';
                    $assigneeLabel = Constants::USERS[$task['assignee']] ?? Constants::USER_SYSTEM_LABEL;
                    $statusLabel = Constants::ACTIVITY_LOGS_STATUS[$task['status']] ?? 'Unknown';
                    
                    $prioClass = 'priority-2';
                    if ($task['priority'] == Constants::PRIORITY_LOW) $prioClass = 'priority-1';
                    if ($task['priority'] == Constants::PRIORITY_HIGH) $prioClass = 'priority-3';
                    if ($task['priority'] == Constants::PRIORITY_CRITICAL) $prioClass = 'priority-4';
                    if ($task['priority'] == Constants::PRIORITY_BLOCKER) $prioClass = 'priority-5';

                    $statusClass = 'status-inprogress';
                    if ($task['status'] == Constants::ACTIVITY_LOGS_STATUS_TO_DO) $statusClass = 'status-todo';
                    if ($task['status'] == Constants::ACTIVITY_LOGS_STATUS_COMPLETED) $statusClass = 'status-completed';
                    if ($task['status'] == Constants::ACTIVITY_LOGS_STATUS_BLOCKED) $statusClass = 'status-blocked';
                ?>
                    <tr>
                        <td><strong><?= esc($subLabel) ?></strong></td>
                        <td><?= esc($task['title']) ?></td>
                        <td><code><?= esc($task['ticket_id'] ?: '—') ?></code></td>
                        <td><span class="badge-prio <?= $prioClass ?>"><?= esc($prioLabel) ?></span></td>
                        <td><?= esc($task['requested_by'] ?: '—') ?></td>
                        <?php if (!$hideAssignee): ?>
                            <td><?= esc($assigneeLabel) ?></td>
                        <?php endif; ?>
                        <td><span class="badge-stat <?= $statusClass ?>"><?= esc($statusLabel) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <?php if ($reportType === 'detailed' || $reportType === 'inventory'): ?>
    <!-- Tracking Tags Changes Section -->
    <div class="section-title">Tags Inventory Activity</div>
    <div class="row">
        <div class="col-6">
            <h6 class="fw-bold text-success border-bottom pb-1 mb-2">New Tags Created (<?= count($new_tags) ?>)</h6>
            <?php if (empty($new_tags)): ?>
                <div class="text-muted py-2 text-center" style="font-size: 0.75rem;">None</div>
            <?php else: ?>
                <?php foreach ($new_tags as $tag): 
                    $sub = Constants::SUBSIDIARIES[$tag['subsidiary_id']] ?? 'N/A';
                    $statusLabel = Constants::STATUS[$tag['status']] ?? 'N/A';
                ?>
                    <div class="tag-row-item d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold" style="font-size: 0.78rem;"><?= esc($tag['name']) ?></div>
                            <small class="text-secondary"><?= esc($sub) ?> • <?= esc($tag['property_name']) ?></small>
                        </div>
                        <span class="badge bg-success-subtle text-success border" style="font-size: 0.65rem; padding: 2px 5px;"><?= esc($statusLabel) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="col-6 border-start">
            <h6 class="fw-bold text-primary border-bottom pb-1 mb-2">Existing Tags Updated (<?= count($updated_tags) ?>)</h6>
            <?php if (empty($updated_tags)): ?>
                <div class="text-muted py-2 text-center" style="font-size: 0.75rem;">None</div>
            <?php else: ?>
                <?php foreach ($updated_tags as $tag): 
                    $sub = Constants::SUBSIDIARIES[$tag['subsidiary_id']] ?? 'N/A';
                    $statusLabel = Constants::STATUS[$tag['status']] ?? 'N/A';
                ?>
                    <div class="tag-row-item d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold" style="font-size: 0.78rem;"><?= esc($tag['name']) ?></div>
                            <small class="text-secondary"><?= esc($sub) ?> • <?= esc($tag['property_name']) ?></small>
                        </div>
                        <span class="badge bg-primary-subtle text-primary border" style="font-size: 0.65rem; padding: 2px 5px;"><?= esc($statusLabel) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($reportType === 'summary'): ?>
    <!-- Chart JS Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart.js renderers with animations disabled
        document.addEventListener('DOMContentLoaded', () => {
            Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
            Chart.defaults.color = '#86868B';

            // 1. Task Status Chart
            const statusCounts = <?= json_encode($status_counts) ?>;
            const statusCtx = document.getElementById('pdfStatusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(statusCounts),
                    datasets: [{
                        data: Object.values(statusCounts),
                        backgroundColor: ['#86868B', '#0071E3', '#F59E0B', '#10B981', '#EF4444'],
                        borderWidth: 2
                    }]
                },
                options: {
                    animation: false,
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 10, padding: 8, font: { size: 8 } }
                        }
                    }
                }
            });

            // 2. Project Output Chart
            const projectData = <?= json_encode($project_activity) ?>;
            const projectCtx = document.getElementById('pdfProjectChart').getContext('2d');
            new Chart(projectCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(projectData).map(k => k.length > 12 ? k.substring(0, 10) + '..' : k),
                    datasets: [{
                        data: Object.values(projectData),
                        backgroundColor: 'rgba(59, 130, 246, 0.75)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    animation: false,
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 8 } } },
                        x: { ticks: { font: { size: 8 } } }
                    }
                }
            });

            // 3. Subsidiary Activity Chart
            const subsidiaryData = <?= json_encode($subsidiary_activity) ?>;
            const activeSubs = {};
            Object.entries(subsidiaryData).forEach(([key, val]) => {
                if (val > 0) activeSubs[key] = val;
            });

            const subCtx = document.getElementById('pdfSubsidiaryChart').getContext('2d');
            if (Object.keys(activeSubs).length === 0) {
                subCtx.font = "12px 'Inter'";
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
                            borderRadius: 4
                        }]
                    },
                    options: {
                        animation: false,
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 8 } } },
                            x: { ticks: { font: { size: 8 } } }
                        }
                    }
                });
            }
        });
    </script>
    <?php endif; ?>

    <!-- Auto Print Script -->
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
