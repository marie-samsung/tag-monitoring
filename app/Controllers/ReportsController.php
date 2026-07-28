<?php

namespace App\Controllers;

use App\Models\TagsModel;
use App\Models\TagRequestsModel;
use App\Models\TasksModel;
use App\Models\Constants;

class ReportsController extends BaseController
{
    /**
     * Display the weekly report dashboard.
     */
    public function index()
    {
        $permissions = session()->get('permissions') ?? [];
        if (empty($permissions['reports'])) {
            return redirect()->to(site_url('dashboard'))
                ->with('error', 'You do not have permission to access the Reports section.');
        }

        helper('custom_functions');
        $data = $this->getReportData();

        return view('reports/index', $data);
    }

    /**
     * Export the weekly report as an Excel spreadsheet file.
     */
    public function exportExcel()
    {
        $permissions = session()->get('permissions') ?? [];
        if (empty($permissions['reports'])) {
            return redirect()->to(site_url('dashboard'))
                ->with('error', 'You do not have permission to access the Reports section.');
        }

        helper('custom_functions');
        $data = $this->getReportData();

        $filename = 'Weekly_Report_' . str_replace(' ', '_', $data['start_date']) . '_to_' . str_replace(' ', '_', $data['end_date']) . '.xls';

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Simple HTML-based Excel formatting
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="UTF-8"><style>th{background-color:#f2f2f2;font-weight:bold;border:1px solid #ccc;} td{border:1px solid #ccc;}</style></head>';
        echo '<body>';

        echo '<h2>Weekly Performance & Activity Report</h2>';
        echo '<p>Period: <b>' . esc($data['start_date']) . '</b> to <b>' . esc($data['end_date']) . '</b>';
        if (!empty($data['team_id'])) {
            $teamName = Constants::TEAMS[$data['team_id']] ?? 'Unknown';
            echo ' | Team: <b>' . esc($teamName) . '</b>';
        }
        echo '</p>';
        echo '<p>Generated At: ' . date('d-m-Y H:i:s') . '</p>';
        echo '<br>';

        // Section 1: KPIs
        echo '<h3>Key Metrics Summary</h3>';
        echo '<table border="1">';
        echo '<tr><th>Metric</th><th>Count</th></tr>';
        echo '<tr><td>Completed Tasks</td><td>' . count($data['completed_tasks']) . '</td></tr>';
        echo '<tr><td>New Tasks Created</td><td>' . count($data['new_tasks']) . '</td></tr>';
        echo '<tr><td>New Tag Requests Submitted</td><td>' . count($data['new_requests']) . '</td></tr>';
        echo '<tr><td>Tags Published</td><td>' . count($data['published_requests']) . '</td></tr>';
        echo '<tr><td>Total Tags Created/Updated</td><td>' . (count($data['new_tags']) + count($data['updated_tags'])) . '</td></tr>';
        echo '</table>';
        echo '<br>';

        $sysSettings = \App\Controllers\SettingsController::getSystemSettings();
        $hideAssignee = $sysSettings['hide_assignee_system_wide'] || $sysSettings['hide_assignee_weekly_reports'];

        // Section 2: Completed Tasks
        echo '<h3>Completed Tasks</h3>';
        echo '<table border="1">';
        echo '<tr><th>Subsidiary</th><th>Task Title</th><th>Ticket ID</th><th>Priority</th>' . (!$hideAssignee ? '<th>Assignee</th>' : '') . '<th>Finished Date</th></tr>';
        foreach ($data['completed_tasks'] as $task) {
            echo '<tr>';
            echo '<td>' . esc(Constants::ACTIVITY_LOG_SUBSIDIARIES[$task['subsidiary_id']] ?? 'N/A') . '</td>';
            echo '<td>' . esc($task['title']) . '</td>';
            echo '<td>' . esc($task['ticket_id'] ?: '—') . '</td>';
            echo '<td>' . esc(Constants::PRIORITIES[$task['priority']] ?? 'N/A') . '</td>';
            if (!$hideAssignee) {
                echo '<td>' . esc(Constants::USERS[$task['assignee']] ?? Constants::USER_SYSTEM_LABEL) . '</td>';
            }
            echo '<td>' . esc($task['finished_date']) . '</td>';
            echo '</tr>';
        }
        if (empty($data['completed_tasks'])) {
            $colspan = $hideAssignee ? 5 : 6;
            echo '<tr><td colspan="' . $colspan . '">No completed tasks recorded in this period.</td></tr>';
        }
        echo '</table>';
        echo '<br>';

        // Section 3: New Tag Requests
        echo '<h3>New Tag Requests Submitted</h3>';
        echo '<table border="1">';
        echo '<tr><th>Ticket ID / Request</th><th>Type</th><th>Property</th><th>Vendor</th><th>Status</th><th>Requested By</th><th>Created Date</th></tr>';
        foreach ($data['new_requests'] as $req) {
            $ticket = $req['type'] == Constants::JIRA_TYPE_NEW_RULE ? $req['new_rule_ticket'] : $req['new_tag_ticket'];
            echo '<tr>';
            echo '<td>' . esc($ticket ?: '—') . '</td>';
            echo '<td>' . esc($req['type'] == Constants::JIRA_TYPE_NEW_RULE ? 'New Rule' : 'New Tag') . '</td>';
            echo '<td>' . esc($req['property_name'] ?: 'N/A') . '</td>';
            echo '<td>' . esc($req['vendor_name'] ?: 'N/A') . '</td>';
            echo '<td>' . esc(Constants::STATUS_REQUESTS[$req['status']] ?? 'Unknown') . '</td>';
            echo '<td>' . esc($req['requester_name'] ?: '—') . '</td>';
            echo '<td>' . esc($req['created_at']) . '</td>';
            echo '</tr>';
        }
        if (empty($data['new_requests'])) {
            echo '<tr><td colspan="7">No new tag requests submitted in this period.</td></tr>';
        }
        echo '</table>';
        echo '<br>';

        // Section 4: Tags Published
        echo '<h3>Tags Published</h3>';
        echo '<table border="1">';
        echo '<tr><th>Ticket ID</th><th>Type</th><th>Property</th><th>Vendor</th><th>Requester</th><th>Published Date</th></tr>';
        foreach ($data['published_requests'] as $req) {
            $ticket = $req['type'] == Constants::JIRA_TYPE_NEW_RULE ? $req['new_rule_ticket'] : $req['new_tag_ticket'];
            echo '<tr>';
            echo '<td>' . esc($ticket ?: '—') . '</td>';
            echo '<td>' . esc($req['type'] == Constants::JIRA_TYPE_NEW_RULE ? 'New Rule' : 'New Tag') . '</td>';
            echo '<td>' . esc($req['property_name'] ?: 'N/A') . '</td>';
            echo '<td>' . esc($req['vendor_name'] ?: 'N/A') . '</td>';
            echo '<td>' . esc($req['requester_name'] ?: '—') . '</td>';
            echo '<td>' . esc($req['updated_at']) . '</td>';
            echo '</tr>';
        }
        if (empty($data['published_requests'])) {
            echo '<tr><td colspan="6">No tags published in this period.</td></tr>';
        }
        echo '</table>';
        echo '<br>';

        // Section 5: Ongoing Tasks
        echo '<h3>Ongoing Tasks (Todo, In Progress, Blocked)</h3>';
        echo '<table border="1">';
        echo '<tr><th>Subsidiary</th><th>Task Title</th><th>Ticket ID</th><th>Priority</th>' . (!$hideAssignee ? '<th>Assignee</th>' : '') . '<th>Status</th><th>Created Date</th></tr>';
        foreach ($data['ongoing_tasks'] as $task) {
            echo '<tr>';
            echo '<td>' . esc(Constants::ACTIVITY_LOG_SUBSIDIARIES[$task['subsidiary_id']] ?? 'N/A') . '</td>';
            echo '<td>' . esc($task['title']) . '</td>';
            echo '<td>' . esc($task['ticket_id'] ?: '—') . '</td>';
            echo '<td>' . esc(Constants::PRIORITIES[$task['priority']] ?? 'N/A') . '</td>';
            if (!$hideAssignee) {
                echo '<td>' . esc(Constants::USERS[$task['assignee']] ?? Constants::USER_SYSTEM_LABEL) . '</td>';
            }
            echo '<td>' . esc(Constants::ACTIVITY_LOGS_STATUS[$task['status']] ?? 'N/A') . '</td>';
            echo '<td>' . esc($task['created_at']) . '</td>';
            echo '</tr>';
        }
        if (empty($data['ongoing_tasks'])) {
            $colspan = $hideAssignee ? 6 : 7;
            echo '<tr><td colspan="' . $colspan . '">No active ongoing tasks recorded.</td></tr>';
        }
        echo '</table>';

        echo '</body></html>';
        exit;
    }

    /**
     * Display a clean, print-ready weekly report page.
     */
    public function exportPDF()
    {
        $permissions = session()->get('permissions') ?? [];
        if (empty($permissions['reports'])) {
            return redirect()->to(site_url('dashboard'))
                ->with('error', 'You do not have permission to access the Reports section.');
        }

        helper('custom_functions');
        $data = $this->getReportData();

        $reportType = 'detailed';
        if ($this->request->getGet('summary') === '1') {
            $reportType = 'summary';
        } elseif ($this->request->getGet('inventory') === '1') {
            $reportType = 'inventory';
        }
        $data['report_type'] = $reportType;

        return view('reports/pdf', $data);
    }

    /**
     * Core logic to load metrics and query data for the reports.
     */
    private function getReportData(): array
    {
        $tagsModel = new TagsModel();
        $tagRequestsModel = new TagRequestsModel();
        $tasksModel = new TasksModel();

        // 1. Date Range setup
        $week = $this->request->getGet('week');
        $startDateParam = $this->request->getGet('start_date');
        $endDateParam = $this->request->getGet('end_date');

        if (empty($week) && empty($startDateParam)) {
            $week = date('Y-\WW'); // Default to current week
        }

        $startDate = null;
        $endDate = null;

        if (!empty($week)) {
            if (preg_match('/^(\d{4})-W(\d{2})$/', $week, $matches)) {
                $year = (int)$matches[1];
                $weekNum = (int)$matches[2];
                
                $dto = new \DateTime();
                $dto->setISODate($year, $weekNum);
                $startDate = $dto->format('Y-m-d');
                $dto->modify('+6 days');
                $endDate = $dto->format('Y-m-d');
            }
        } else {
            $startDate = $startDateParam;
            $endDate = !empty($endDateParam) ? $endDateParam : date('Y-m-d');
        }

        // Fallbacks
        if (empty($startDate)) {
            $startDate = date('Y-m-d', strtotime('monday this week'));
        }
        if (empty($endDate)) {
            $endDate = date('Y-m-d', strtotime('sunday this week'));
        }

        // Ensure start date is before end date
        if (strtotime($startDate) > strtotime($endDate)) {
            $startDate = $endDate;
        }

        $startDateQuery = $startDate . ' 00:00:00';
        $endDateQuery = $endDate . ' 23:59:59';

        // 2. Determine User Role and Team restrictions
        $userId = session()->get('user_id');
        $userRole = Constants::USER_ACCESS_LEVELS[$userId] ?? null;
        $teamParam = $this->request->getGet('team_id');
        $selectedTeam = null;
        if ($teamParam !== null && $teamParam !== '') {
            $selectedTeam = (int)$teamParam;
        } elseif ($userRole !== Constants::USER_ROLE_ADMIN) {
            $selectedTeam = Constants::USER_TEAMS[$userId] ?? null;
        }

        // 3. Query Completed Tasks (Finished within range)
        $completedTasksQuery = $tasksModel
            ->where('status', Constants::ACTIVITY_LOGS_STATUS_COMPLETED)
            ->where('finished_date >=', $startDate)
            ->where('finished_date <=', $endDate);
        if ($selectedTeam !== null) {
            $completedTasksQuery->where('team_id', $selectedTeam);
        }
        $completedTasks = $completedTasksQuery->orderBy('finished_date', 'DESC')->findAll();

        // 4. Query New Tasks Created (Created within range)
        $newTasksQuery = $tasksModel
            ->where('created_at >=', $startDateQuery)
            ->where('created_at <=', $endDateQuery);
        if ($selectedTeam !== null) {
            $newTasksQuery->where('team_id', $selectedTeam);
        }
        $newTasks = $newTasksQuery->orderBy('created_at', 'DESC')->findAll();

        // 5. Query Ongoing Tasks (Active during the week)
        $ongoingTasksQuery = $tasksModel
            ->where('DATE(created_at) <=', $endDate)
            ->groupStart()
                ->where('status !=', Constants::ACTIVITY_LOGS_STATUS_COMPLETED)
                ->orGroupStart()
                    ->where('status', Constants::ACTIVITY_LOGS_STATUS_COMPLETED)
                    ->where('finished_date >', $endDate)
                ->groupEnd()
            ->groupEnd();
        if ($selectedTeam !== null) {
            $ongoingTasksQuery->where('team_id', $selectedTeam);
        }
        $ongoingTasks = $ongoingTasksQuery->orderBy('priority', 'DESC')->orderBy('created_at', 'DESC')->findAll();

        // 6. Query New Tag Requests (Created within range)
        $newRequests = $tagRequestsModel
            ->select('tag_requests.*, properties.name as property_name, vendors.name as vendor_name')
            ->join('properties', 'properties.id = tag_requests.property_id', 'left')
            ->join('vendors', 'vendors.id = tag_requests.vendor_id', 'left')
            ->where('tag_requests.created_at >=', $startDateQuery)
            ->where('tag_requests.created_at <=', $endDateQuery)
            ->orderBy('tag_requests.created_at', 'DESC')
            ->findAll();

        // 7. Query Published Requests (Published within range)
        $publishedRequests = $tagRequestsModel
            ->select('tag_requests.*, properties.name as property_name, vendors.name as vendor_name')
            ->join('properties', 'properties.id = tag_requests.property_id', 'left')
            ->join('vendors', 'vendors.id = tag_requests.vendor_id', 'left')
            ->where('tag_requests.status', Constants::STATUS_REQUEST_PUBLISHED)
            ->where('tag_requests.updated_at >=', $startDateQuery)
            ->where('tag_requests.updated_at <=', $endDateQuery)
            ->orderBy('tag_requests.updated_at', 'DESC')
            ->findAll();

        // 8. Query Tags Created / Updated
        $newTags = $tagsModel
            ->select('tags.*, properties.name as property_name, properties.subsidiary_id, vendors.name as vendor_name')
            ->join('properties', 'properties.id = tags.property_id', 'left')
            ->join('vendors', 'vendors.id = tags.vendor_id', 'left')
            ->where('tags.created_at >=', $startDateQuery)
            ->where('tags.created_at <=', $endDateQuery)
            ->orderBy('tags.created_at', 'DESC')
            ->findAll();

        $updatedTags = $tagsModel
            ->select('tags.*, properties.name as property_name, properties.subsidiary_id, vendors.name as vendor_name')
            ->join('properties', 'properties.id = tags.property_id', 'left')
            ->join('vendors', 'vendors.id = tags.vendor_id', 'left')
            ->where('tags.updated_at >=', $startDateQuery)
            ->where('tags.updated_at <=', $endDateQuery)
            ->where('tags.created_at <', $startDateQuery)
            ->orderBy('tags.updated_at', 'DESC')
            ->findAll();

        // 9. Aggregate counts for charts
        // A. Task Status Counts
        $statusCounts = [
            'To Do' => 0,
            'In Progress' => 0,
            'QA/Review' => 0,
            'Completed' => count($completedTasks),
            'Blocked' => 0
        ];
        foreach ($ongoingTasks as $task) {
            $statusVal = (int)$task['status'];
            if ($statusVal === Constants::ACTIVITY_LOGS_STATUS_TO_DO) {
                $statusCounts['To Do']++;
            } elseif (in_array($statusVal, [Constants::ACTIVITY_LOGS_STATUS_INVESTIGATION, Constants::ACTIVITY_LOGS_STATUS_IMPLEMENTATION])) {
                $statusCounts['In Progress']++;
            } elseif (in_array($statusVal, [Constants::ACTIVITY_LOGS_STATUS_QA, Constants::ACTIVITY_LOGS_STATUS_REVIEW])) {
                $statusCounts['QA/Review']++;
            } elseif ($statusVal === Constants::ACTIVITY_LOGS_STATUS_BLOCKED) {
                $statusCounts['Blocked']++;
            }
        }

        // B. Subsidiary distribution (Tags Created/Updated or requests made)
        $subsidiaryActivity = [];
        foreach (Constants::SUBSIDIARIES as $id => $name) {
            $subsidiaryActivity[$name] = 0;
        }
        foreach ($newTags as $tag) {
            $subId = (int)$tag['subsidiary_id'];
            $subName = Constants::SUBSIDIARIES[$subId] ?? null;
            if ($subName) {
                $subsidiaryActivity[$subName]++;
            }
        }
        foreach ($newRequests as $req) {
            // Find property subsidiary
            $db = \Config\Database::connect();
            $prop = $db->table('properties')->where('id', $req['property_id'])->get()->getRowArray();
            if ($prop) {
                $subName = Constants::SUBSIDIARIES[$prop['subsidiary_id']] ?? null;
                if ($subName) {
                    $subsidiaryActivity[$subName]++;
                }
            }
        }

        // C. Project workload (Number of active tasks)
        $projectActivity = [];
        foreach ($newTasks as $t) {
            $pName = $t['project'] ?: 'Generic';
            $projectActivity[$pName] = ($projectActivity[$pName] ?? 0) + 1;
        }
        foreach ($completedTasks as $t) {
            $pName = $t['project'] ?: 'Generic';
            $projectActivity[$pName] = ($projectActivity[$pName] ?? 0) + 1;
        }
        arsort($projectActivity);
        $projectActivity = array_slice($projectActivity, 0, 8, true); // Keep top 8 projects

        // D. Assignee Workload (Completed Tasks count)
        $assigneeWorkload = [];
        foreach ($completedTasks as $t) {
            $uName = Constants::USERS[$t['assignee']] ?? Constants::USER_SYSTEM_LABEL;
            $assigneeWorkload[$uName] = ($assigneeWorkload[$uName] ?? 0) + 1;
        }

        return [
            'week' => $week,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'team_id' => $selectedTeam,
            'completed_tasks' => $completedTasks,
            'new_tasks' => $newTasks,
            'ongoing_tasks' => $ongoingTasks,
            'new_requests' => $newRequests,
            'published_requests' => $publishedRequests,
            'new_tags' => $newTags,
            'updated_tags' => $updatedTags,
            'status_counts' => $statusCounts,
            'subsidiary_activity' => $subsidiaryActivity,
            'project_activity' => $projectActivity,
            'assignee_workload' => $assigneeWorkload,
        ];
    }
}
