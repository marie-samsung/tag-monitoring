<?php
    use App\Models\Constants;
    $sysSettings = \App\Controllers\SettingsController::getSystemSettings();
    $hideAssigneeSystemWide = $sysSettings['hide_assignee_system_wide'] ?? false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Report - <?= esc($project_name) ?></title>
    <!-- Use Google Font Inter for modern premium look -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS for grid / styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #1d1d1f;
            background-color: #ffffff;
            padding: 40px 30px;
            font-size: 0.85rem;
        }
        .report-header {
            border-bottom: 2px solid #e5e5ea;
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .report-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }
        .report-subtitle {
            font-size: 0.9rem;
            color: #86868b;
            margin-bottom: 0;
        }
        .report-meta {
            text-align: right;
            font-size: 0.8rem;
            color: #86868b;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .report-table th {
            background-color: #f5f5f7;
            font-weight: 600;
            color: #1d1d1f;
            border: 1px solid #d1d1d6;
            padding: 10px 12px;
            text-align: left;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .report-table td {
            border: 1px solid #e5e5ea;
            padding: 10px 12px;
            vertical-align: top;
            line-height: 1.4;
        }
        .report-table tr:nth-child(even) {
            background-color: #fafafc;
        }
        .badge-priority {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .priority-1 { background-color: #f4f9ff; color: #0071e3; border: 1px solid rgba(0, 113, 227, 0.15); } /* Low */
        .priority-2 { background-color: #f5f5f7; color: #1d1d1f; border: 1px solid rgba(29, 29, 31, 0.15); } /* Medium */
        .priority-3 { background-color: #fffbf0; color: #b46b00; border: 1px solid rgba(180, 107, 0, 0.15); } /* High */
        .priority-4 { background-color: #fff4f4; color: #de3535; border: 1px solid rgba(222, 53, 53, 0.15); } /* Critical */
        .priority-5 { background-color: #ffe5e5; color: #d32f2f; border: 1px solid rgba(211, 47, 47, 0.25); font-weight: 700; } /* Blocker */

        .badge-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-todo { background-color: #f5f5f7; color: #86868b; border: 1px solid #d1d1d6; }
        .status-inprogress { background-color: #fffbf0; color: #b46b00; border: 1px solid rgba(180, 107, 0, 0.15); }
        .status-completed { background-color: #f4fbf6; color: #1d8a44; border: 1px solid rgba(29, 138, 68, 0.15); }
        .status-blocked { background-color: #fff4f4; color: #de3535; border: 1px solid rgba(222, 53, 53, 0.15); }

        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="report-header">
        <div>
            <h1 class="report-title">Project: <?= esc($project_name) ?></h1>
            <p class="report-subtitle">Tasks Export Report</p>
        </div>
        <div class="report-meta">
            <div>Date Generated: <strong><?= date('d-m-Y H:i') ?></strong></div>
            <div>Total Tasks: <strong><?= count($tasks) ?></strong></div>
        </div>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 80px;">Subsidiary</th>
                <th>Task Title</th>
                <th style="width: 110px;">Ticket ID</th>
                <th style="width: 80px;">Priority</th>
                <?php if (!$hideAssigneeSystemWide): ?>
                    <th style="width: 100px;">Assignee</th>
                <?php endif; ?>
                <th style="width: 100px;">Created Date</th>
                <th style="width: 120px;">Status</th>
                <th>Last Comment</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tasks)): ?>
                <tr>
                    <td colspan="<?= $hideAssigneeSystemWide ? 7 : 8 ?>" class="text-center py-4 text-muted">No tasks recorded for this project.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($tasks as $task): 
                    $subsidiary = Constants::ACTIVITY_LOG_SUBSIDIARIES[$task['subsidiary_id']] ?? 'Unknown';
                    $priority = Constants::PRIORITIES[$task['priority']] ?? 'Unknown';
                    $assignee = Constants::USERS[$task['assignee']] ?? Constants::USER_SYSTEM_LABEL;
                    $createdDate = date('d-m-Y', strtotime($task['created_at']));
                    $status = Constants::ACTIVITY_LOGS_STATUS[$task['status']] ?? 'Unknown';
                    
                    // Simple class grouping for priority badges
                    $prioClass = 'priority-2';
                    if ($task['priority'] == Constants::PRIORITY_LOW) $prioClass = 'priority-1';
                    if ($task['priority'] == Constants::PRIORITY_HIGH) $prioClass = 'priority-3';
                    if ($task['priority'] == Constants::PRIORITY_CRITICAL) $prioClass = 'priority-4';
                    if ($task['priority'] == Constants::PRIORITY_BLOCKER) $prioClass = 'priority-5';

                    // Simple status badge class grouping
                    $statusClass = 'status-inprogress';
                    if ($task['status'] == Constants::ACTIVITY_LOGS_STATUS_TO_DO) $statusClass = 'status-todo';
                    if ($task['status'] == Constants::ACTIVITY_LOGS_STATUS_COMPLETED) $statusClass = 'status-completed';
                    if ($task['status'] == Constants::ACTIVITY_LOGS_STATUS_BLOCKED) $statusClass = 'status-blocked';
                ?>
                    <tr>
                        <td><strong><?= esc($subsidiary) ?></strong></td>
                        <td><?= esc($task['title']) ?></td>
                        <td><?= esc($task['ticket_id'] ?: '—') ?></td>
                        <td>
                            <span class="badge-priority <?= $prioClass ?>"><?= esc($priority) ?></span>
                        </td>
                        <?php if (!$hideAssigneeSystemWide): ?>
                            <td><?= esc($assignee) ?></td>
                        <?php endif; ?>
                        <td><?= esc($createdDate) ?></td>
                        <td>
                            <span class="badge-status <?= $statusClass ?>"><?= esc($status) ?></span>
                        </td>
                        <td style="font-size: 0.8rem; color: #48484a;"><?= esc($task['last_comment'] ?: '—') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
