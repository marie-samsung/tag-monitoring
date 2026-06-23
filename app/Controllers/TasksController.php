<?php

namespace App\Controllers;

use App\Models\TaskCommentsModel;
use App\Models\TaskFilesModel;
use App\Models\TasksModel;
use App\Models\Constants;
use CodeIgniter\Files\Exceptions\FileException;

class TasksController extends BaseController
{
    public function index()
    {
        $model = new TasksModel();

        $meModeGet = $this->request->getGet('me_mode');
        if ($meModeGet !== null) {
            if ($meModeGet == '1') {
                session()->set('al_me_mode', 1);
            } else {
                session()->set('al_me_mode', 0);
            }
        }

        $meMode = (session()->get('al_me_mode') == 1);

        $filters = [
            'keyword' => $this->request->getGet('keyword'),
            'subsidiary_id' => $this->request->getGet('subsidiary_id'),
            'team_id' => $this->request->getGet('team_id'),
            'project' => $this->request->getGet('project'),
            'type' => $this->request->getGet('type'),
            'status' => $this->request->getGet('status'),
            'status_group' => $this->request->getGet('status_group'),
            'priority' => $this->request->getGet('priority'),
            'assignee' => $this->request->getGet('assignee'),
            'me_mode' => $meMode ? 1 : 0,
            'sort_by' => $this->request->getGet('sort_by') ?? 'created_at',
            'sort_dir' => $this->request->getGet('sort_dir') ?? 'desc',
            'show_completed' => $this->request->getGet('show_completed'),
        ];
        $data['status_counts'] = $model->getStatusCounts($filters);
        $data['logs'] = $model->filterLogs($filters, 25);
        $data['pager'] = $model->pager;
        $data['filters'] = $filters;

        $distinctProjects = $model->getDistinctProjects();
        $data['project_names'] = array_column($distinctProjects, 'project_name');

        return view('tasks/list', $data);
    }

    public function create()
    {
        $model = new TasksModel();

        if ($this->request->getMethod() === 'GET') {
            $log = null;
            $duplicateId = $this->request->getGet('duplicate_id');
            if (!empty($duplicateId)) {
                $originalLog = $model->find($duplicateId);
                if ($originalLog) {
                    $log = $originalLog;
                    $log['title'] = 'Copy of ' . $log['title'];
                    $log['finished_date'] = null;
                }
            }

            $projectGet = $this->request->getGet('project');
            if (!empty($projectGet)) {
                if ($log === null) {
                    $log = [];
                }
                $log['project'] = trim($projectGet);
            }

            $distinctProjects = $model->getDistinctProjects();
            $projectNames = array_column($distinctProjects, 'project_name');
            $distinctDepartments = $model->select('DISTINCT(department_id) as department_name')->where('department_id IS NOT NULL')->where("department_id != ''")->orderBy('department_id', 'ASC')->findAll();
            $departmentNames = array_column($distinctDepartments, 'department_name');
            return view('tasks/create', [
                'project_names' => $projectNames,
                'department_names' => $departmentNames,
                'log' => $log
            ]);
        }

        $post = $this->request->getPost();
        $assigneeId = $post['assignee'] ?? null;

        $insertData = [
            'title' => trim($post['title']),
            'description' => $post['description'] ?? null,
            'subsidiary_id' => (int) $post['subsidiary_id'],
            'department_id' => !empty($post['department_id']) ? trim((string) $post['department_id']) : null,
            'team_id' => (int) $post['team_id'],
            'project' => !empty($post['project']) ? trim($post['project']) : 'Generic',
            'type' => (int) $post['type'] ?? null,
            'ticket_id' => !empty($post['ticket_id']) ? $post['ticket_id'] : null,
            'assignee' => $assigneeId,
            'requested_by' => $post['requested_by'] ?? null,
            'status' => (int) $post['status'],
            'priority' => (int) $post['priority'] ?? Constants::PRIORITY_LOW,
            'size' => (int) $post['size'] ?? null,
            'requested_date' => !empty($post['requested_date']) ? $post['requested_date'] : null,
            'start_date' => !empty($post['start_date']) ? $post['start_date'] : null,
            'finished_date' => !empty($post['finished_date']) ? $post['finished_date'] : null,
            'campaign_status' => !empty($post['campaign_status']) ? (int) $post['campaign_status'] : null,
            'campaign_id' => $post['campaign_id'] ?? null,
        ];

        $model->insert($insertData);

        $referer = $this->request->getServer('HTTP_REFERER');
        $redirectUrl = '/tasks';
        if ($referer && strpos($referer, '/tasks/project/') !== false) {
            $redirectUrl = '/tasks/project/' . rawurlencode($insertData['project']);
        }

        return redirect()
            ->to($redirectUrl)
            ->with('success', "Activity log '{$insertData['title']}' created successfully.");
    }

    public function view($id)
    {
        $model = new TasksModel();
        $comments = new TaskCommentsModel();
        $files = new TaskFilesModel();
        $log = $model->find($id);

        if (!$log) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Activity log with ID $id not found.");
        }

        return view('tasks/view', [
            'log' => $log,
            'comments' => $comments->getRelatedComments($id),
            'files' => $files->getRelatedFiles($id),
        ]);
    }

    public function update($id)
    {
        helper('custom_functions');
        $model = new TasksModel();
        $commentsModel = new TaskCommentsModel();
        $log = $model->find($id);

        if (!$log) {
            return redirect()
                ->to('/tasks')
                ->with('error', 'Activity log not found.');
        }

        if ($this->request->getMethod() === 'GET') {
            $distinctProjects = $model->getDistinctProjects();
            $projectNames = array_column($distinctProjects, 'project_name');
            $distinctDepartments = $model->select('DISTINCT(department_id) as department_name')->where('department_id IS NOT NULL')->where("department_id != ''")->orderBy('department_id', 'ASC')->findAll();
            $departmentNames = array_column($distinctDepartments, 'department_name');
            return view('tasks/update', [
                'log' => $log,
                'project_names' => $projectNames,
                'department_names' => $departmentNames
            ]);
        }

        $post = $this->request->getPost();
        $assigneeId = (int) ($post['assignee'] ?? $log['assignee']);

        // @NOTE: ACTIVITY LOGS AUTOMATIC UPDATE COMMENTS
        if ($log['status'] != $post['status']) {
            $from = Constants::ACTIVITY_LOGS_STATUS[$log['status']];
            $to = Constants::ACTIVITY_LOGS_STATUS[$post['status']];

            $commentsModel->insert([
                'log_id' => $id,
                'comment' => "Status updated from [{$from}] to [{$to}]",
                'commented_by' => session()->get('user_id'),
                'type' => Constants::COMMENT_TYPE_UPDATE,
            ]);

            if ($post['status'] == Constants::ACTIVITY_LOGS_STATUS_COMPLETED) {
                $post['finished_date'] = date_ymd();
            }
        }

        if ((int) $log['assignee'] != (int) $assigneeId) {
            $from = $log['assignee'] == Constants::USER_SYSTEM
                ? Constants::USER_SYSTEM_LABEL
                : Constants::USERS[$log['assignee']];

            $to = $assigneeId == Constants::USER_SYSTEM
                ? Constants::USER_SYSTEM_LABEL
                : Constants::USERS[$assigneeId];

            $commentsModel->insert([
                'log_id' => $id,
                'comment' => "Assignee changed from [{$from}] to [{$to}]",
                'commented_by' => session()->get('user_id'),
                'type' => Constants::COMMENT_TYPE_UPDATE,
            ]);
        }

        if ((int) $log['priority'] != (int) $post['priority']) {
            $from = Constants::PRIORITIES[$log['priority']];
            $to = Constants::PRIORITIES[$post['priority']];

            $commentsModel->insert([
                'log_id' => $id,
                'comment' => "Priority updated from [{$from}] to [{$to}]",
                'commented_by' => session()->get('user_id'),
                'type' => Constants::COMMENT_TYPE_UPDATE,
            ]);
        }

        $updateData = [
            'title' => trim($post['title']),
            'description' => $post['description'] ?? null,
            'subsidiary_id' => (int) $post['subsidiary_id'],
            'department_id' => !empty($post['department_id']) ? trim((string) $post['department_id']) : null,
            'team_id' => (int) $post['team_id'],
            'project' => !empty($post['project']) ? trim($post['project']) : 'Generic',
            'type' => (int) $post['type'] ?? null,
            'ticket_id' => !empty($post['ticket_id']) ? $post['ticket_id'] : null,
            'assignee' => $assigneeId,
            'requested_by' => $post['requested_by'] ?? null,
            'status' => (int) $post['status'],
            'size' => (int) $post['size'] ?? null,
            'priority' => (int) $post['priority'] ?? null,
            'requested_date' => !empty($post['requested_date']) ? $post['requested_date'] : null,
            'start_date' => !empty($post['start_date']) ? $post['start_date'] : null,
            'finished_date' => !empty($post['finished_date']) ? $post['finished_date'] : null,
            'campaign_status' => !empty($post['campaign_status']) ? (int) $post['campaign_status'] : null,
            'campaign_id' => $post['campaign_id'] ?? null,
        ];

        $model->update($id, $updateData);

        if ($updateData['status'] == Constants::ACTIVITY_LOGS_STATUS_BLOCKED) {
            $updatedLog = $model->find($id);
            if ($updatedLog) {
                $this->updateRelatedTagsStatusToOnHold($updatedLog);
            }
        } elseif ($updateData['status'] == Constants::ACTIVITY_LOGS_STATUS_REVIEW) {
            $updatedLog = $model->find($id);
            if ($updatedLog) {
                $this->updateRelatedTagsStatusToPendingVerification($updatedLog);
            }
        }

        return redirect()
            ->to('/tasks/view/' . $id)
            ->with('success', "Activity log '{$updateData['title']}' updated successfully.");
    }

    public function comment()
    {
        helper('custom_functions');
        $commentsModel = new TaskCommentsModel();
        $post = $this->request->getPost();

        if (!$post) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No comment data provided.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'No comment data provided.');
        }

        $cleanComment = clean_html_comment($post['comment'] ?? '');

        $insertedId = $commentsModel->insert([
            'log_id' => $post['log_id'],
            'comment' => $cleanComment,
            'commented_by' => $post['commented_by'],
            'type' => Constants::COMMENT_TYPE_COMMENT,
        ]);

        // Process Mentions
        preg_match_all('/@(\w+)/', $cleanComment, $matches);
        if (!empty($matches[1])) {
            $notificationsModel = new \App\Models\NotificationsModel();
            $mentionedNames = array_unique($matches[1]);
            foreach ($mentionedNames as $name) {
                $userId = null;
                foreach (Constants::USERS as $id => $uName) {
                    if (strcasecmp($uName, $name) === 0) {
                        $userId = $id;
                        break;
                    }
                }
                
                if ($userId && $userId != $post['commented_by']) {
                    $notificationsModel->insert([
                        'user_id' => $userId,
                        'sender_id' => $post['commented_by'],
                        'task_id' => $post['log_id'],
                        'message' => 'mentioned you in an update.',
                        'is_read' => 0
                    ]);
                }
            }
        }

        if ($this->request->isAJAX()) {
            $insertedComment = $commentsModel->find($insertedId);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Update posted successfully.',
                'csrf_hash' => csrf_hash(),
                'comment' => [
                    'comment' => $insertedComment['comment'],
                    'commented_by' => $insertedComment['commented_by'],
                    'created_at' => $insertedComment['created_at'],
                    'user_label' => Constants::USERS[$insertedComment['commented_by']] ?? Constants::USER_SYSTEM_LABEL,
                    'is_system' => ($insertedComment['commented_by'] == Constants::USER_SYSTEM)
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Update posted successfully.');
    }

    public function uploadFile()
    {
        $model = new TasksModel();
        $filesModel = new TaskFilesModel();

        $post = $this->request->getPost();
        $logId = $post['log_id'];
        $file = $this->request->getFile('file_path');

        try {
            if ($file && $file->isValid()) {
                $upload_path = Constants::ACTIVITY_LOGS_FILE_WRITE_PATH . (Constants::IS_DEV ? 'test-files/' : '');
                $file_name = $file->getClientName();

                if ($file->move($upload_path, $file_name)) {
                    $file_path = $upload_path . '/' . $file_name;

                    $filesModel->insert([
                        'file_name' => $file_name,
                        'file_path' => $file_path,
                        'log_id' => $logId,
                        'created_by' => $post['created_by'],
                    ]);

                    return redirect()
                        ->to('/tasks/view/' . $post['log_id'])
                        ->with('success', "File successfully uploaded.");
                }
            }
        } catch (FileException | \ReflectionException $e) {
            var_dump($e);
        }

        return redirect()
            ->to('/tasks/view/' . $post['log_id'])
            ->with('error', "An error occurred. File was not uploaded successfully.");
    }

    public function exportAsExcel()
    {
        helper('custom_functions');
        $logsModel = new TasksModel();

        $meModeGet = $this->request->getGet('me_mode');
        if ($meModeGet !== null) {
            $meMode = ($meModeGet == '1');
        } else {
            $meMode = (session()->get('al_me_mode') == 1);
        }

        $filters = [
            'keyword' => $this->request->getGet('keyword'),
            'subsidiary_id' => $this->request->getGet('subsidiary_id'),
            'team_id' => $this->request->getGet('team_id'),
            'project' => $this->request->getGet('project'),
            'type' => $this->request->getGet('type'),
            'status' => $this->request->getGet('status'),
            'status_group' => $this->request->getGet('status_group'),
            'priority' => $this->request->getGet('priority'),
            'assignee' => $this->request->getGet('assignee'),
            'me_mode' => $meMode ? 1 : 0,
            'sort_by' => $this->request->getGet('sort_by') ?? 'created_at',
            'sort_dir' => $this->request->getGet('sort_dir') ?? 'desc',
            'show_completed' => $this->request->getGet('show_completed'),
        ];

        $logs = $logsModel->filterLogs($filters);

        $subsidiary = (!empty($filters['subsidiary_id']))
            ? '(' . Constants::ACTIVITY_LOG_SUBSIDIARIES[$filters['subsidiary_id']] . ')'
            : null;

        $team = (!empty($filters['team_id']))
            ? '(' . Constants::TEAMS[$filters['team_id']] . ')'
            : null;

        $project = (!empty($filters['project']))
            ? '(' . $filters['project'] . ')'
            : null;

        $assignee = (!empty($filters['assignee']))
            ? '(' . Constants::USERS[$filters['assignee']] . ')'
            : null;

        $filename = 'ActivityLogs' . $subsidiary . $team . $project . $assignee . '_' . date('Y-m-d') . '.xls';

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="UTF-8"></head>';
        echo '<body><table border="1">';

        // Headers
        $headers = [
            'Subsidiary', 'Team', 'Project Name', 'Activity Log Type', 'Assigned To', 'Title', 'Description', 'Status', 'Priority', 'Size', 'Ticket ID', '(Campaign) Department', '(Campaign) Status', '(Campaign) ID', 'Requested By', 'Requested Date', 'Finished Date', 'Created Date'
        ];
        
        echo '<tr>';
        foreach ($headers as $header) {
            echo '<th style="background-color:#f2f2f2;">' . esc($header) . '</th>';
        }
        echo '</tr>';

        // Rows
        foreach ($logs as $log) {
            echo '<tr>';
            echo '<td>' . esc(Constants::ACTIVITY_LOG_SUBSIDIARIES[$log['subsidiary_id']] ?? 'N/A') . '</td>';
            echo '<td>' . esc(Constants::TEAMS[$log['team_id']] ?? 'N/A') . '</td>';
            echo '<td>' . esc($log['project'] ?: 'Generic') . '</td>';
            echo '<td>' . esc(Constants::ACTIVITY_TYPES[$log['type']] ?? 'N/A') . '</td>';
            echo '<td>' . esc(Constants::USERS[$log['assignee']] ?? Constants::USER_SYSTEM_LABEL) . '</td>';
            echo '<td>' . esc($log['title']) . '</td>';
            echo '<td>' . strip_tags($log['description'] ?? '') . '</td>';
            echo '<td>' . esc(Constants::ACTIVITY_LOGS_STATUS[$log['status']] ?? 'Unknown') . '</td>';
            echo '<td>' . esc(Constants::PRIORITIES[$log['priority']] ?? 'Unknown') . '</td>';
            echo '<td>' . esc(Constants::ACTIVITY_SIZES[$log['size']] ?? 'Unknown') . '</td>';
            echo '<td>' . esc($log['ticket_id'] ?? '-') . '</td>';
            echo '<td>' . esc($log['department_id'] ?? '-') . '</td>';
            echo '<td>' . esc(Constants::CAMPAIGN_STATUS[$log['campaign_status']] ?? '-') . '</td>';
            echo '<td>' . esc($log['campaign_id'] ?? '-') . '</td>';
            echo '<td>' . esc($log['requested_by'] ?? '-') . '</td>';
            echo '<td>' . esc($log['requested_date'] ?? '-') . '</td>';
            echo '<td>' . esc($log['finished_date'] ?? '-') . '</td>';
            echo '<td>' . esc($log['created_at'] ?? '-') . '</td>';
            echo '</tr>';
        }

        echo '</table></body></html>';
        exit;
    }

    public function updateStatus($id)
    {
        $model = new TasksModel();
        $commentsModel = new TaskCommentsModel();

        $log = $model->find($id);
        if (!$log) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Activity log not found.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $status = (int) $this->request->getPost('status');
        if (!array_key_exists($status, Constants::ACTIVITY_LOGS_STATUS)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid status value.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if ((int) $log['status'] !== $status) {
            $from = Constants::ACTIVITY_LOGS_STATUS[$log['status']] ?? 'Unknown';
            $to = Constants::ACTIVITY_LOGS_STATUS[$status] ?? 'Unknown';

            $insertedId = $commentsModel->insert([
                'log_id' => $id,
                'comment' => "Status updated from [{$from}] to [{$to}]",
                'commented_by' => session()->get('user_id'),
                'type' => Constants::COMMENT_TYPE_UPDATE,
            ]);

            $updateData = [
                'status' => $status,
            ];

            if ($status == Constants::ACTIVITY_LOGS_STATUS_COMPLETED) {
                $updateData['finished_date'] = date('Y-m-d');
            }

            $model->update($id, $updateData);

            if ($status == Constants::ACTIVITY_LOGS_STATUS_BLOCKED) {
                $updatedLog = $model->find($id);
                if ($updatedLog) {
                    $this->updateRelatedTagsStatusToOnHold($updatedLog);
                }
            } elseif ($status == Constants::ACTIVITY_LOGS_STATUS_REVIEW) {
                $updatedLog = $model->find($id);
                if ($updatedLog) {
                    $this->updateRelatedTagsStatusToPendingVerification($updatedLog);
                }
            }

            $insertedComment = $commentsModel->find($insertedId);
            $commentData = [
                'comment' => $insertedComment['comment'],
                'commented_by' => $insertedComment['commented_by'],
                'created_at' => $insertedComment['created_at'],
                'user_label' => Constants::USERS[$insertedComment['commented_by']] ?? Constants::USER_SYSTEM_LABEL,
                'is_system' => ($insertedComment['commented_by'] == Constants::USER_SYSTEM)
            ];

            helper('custom_functions');
            return $this->response->setJSON([
                'success' => true,
                'message' => "Status updated to '{$to}' successfully.",
                'status_class' => status_activity_logs_class($status),
                'csrf_hash' => csrf_hash(),
                'comment' => $commentData
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status remains unchanged.',
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function updateAssignee($id)
    {
        $model = new TasksModel();
        $commentsModel = new TaskCommentsModel();

        $log = $model->find($id);
        if (!$log) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Activity log not found.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $assigneeId = (int) $this->request->getPost('assignee');
        if (!array_key_exists($assigneeId, Constants::USERS) && $assigneeId !== Constants::USER_SYSTEM) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid assignee value.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if ((int) $log['assignee'] !== $assigneeId) {
            $from = $log['assignee'] == Constants::USER_SYSTEM
                ? Constants::USER_SYSTEM_LABEL
                : Constants::USERS[$log['assignee']] ?? 'Unknown';

            $to = $assigneeId == Constants::USER_SYSTEM
                ? Constants::USER_SYSTEM_LABEL
                : Constants::USERS[$assigneeId] ?? 'Unknown';

            $insertedId = $commentsModel->insert([
                'log_id' => $id,
                'comment' => "Assignee changed from [{$from}] to [{$to}]",
                'commented_by' => session()->get('user_id'),
                'type' => Constants::COMMENT_TYPE_UPDATE,
            ]);

            $model->update($id, [
                'assignee' => $assigneeId,
            ]);

            $insertedComment = $commentsModel->find($insertedId);
            $commentData = [
                'comment' => $insertedComment['comment'],
                'commented_by' => $insertedComment['commented_by'],
                'created_at' => $insertedComment['created_at'],
                'user_label' => Constants::USERS[$insertedComment['commented_by']] ?? Constants::USER_SYSTEM_LABEL,
                'is_system' => ($insertedComment['commented_by'] == Constants::USER_SYSTEM)
            ];

            helper('custom_functions');
            return $this->response->setJSON([
                'success' => true,
                'message' => "Assignee changed to '{$to}' successfully.",
                'status_class' => assignee_class($assigneeId),
                'csrf_hash' => csrf_hash(),
                'comment' => $commentData
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Assignee remains unchanged.',
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function updatePriority($id)
    {
        $model = new TasksModel();
        $commentsModel = new TaskCommentsModel();

        $log = $model->find($id);
        if (!$log) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Activity log not found.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $priority = (int) $this->request->getPost('priority');
        if (!array_key_exists($priority, Constants::PRIORITIES)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid priority value.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if ((int) $log['priority'] !== $priority) {
            $from = Constants::PRIORITIES[$log['priority']] ?? 'Unknown';
            $to = Constants::PRIORITIES[$priority] ?? 'Unknown';

            $insertedId = $commentsModel->insert([
                'log_id' => $id,
                'comment' => "Priority updated from [{$from}] to [{$to}]",
                'commented_by' => session()->get('user_id'),
                'type' => Constants::COMMENT_TYPE_UPDATE,
            ]);

            $model->update($id, [
                'priority' => $priority,
            ]);

            $insertedComment = $commentsModel->find($insertedId);
            $commentData = [
                'comment' => $insertedComment['comment'],
                'commented_by' => $insertedComment['commented_by'],
                'created_at' => $insertedComment['created_at'],
                'user_label' => Constants::USERS[$insertedComment['commented_by']] ?? Constants::USER_SYSTEM_LABEL,
                'is_system' => ($insertedComment['commented_by'] == Constants::USER_SYSTEM)
            ];

            helper('custom_functions');
            return $this->response->setJSON([
                'success' => true,
                'message' => "Priority updated to '{$to}' successfully.",
                'status_class' => priority_class($priority),
                'csrf_hash' => csrf_hash(),
                'comment' => $commentData
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Priority remains unchanged.',
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function updateTicketId($id)
    {
        $model = new TasksModel();
        $commentsModel = new TaskCommentsModel();

        $log = $model->find($id);
        if (!$log) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Activity log not found.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $ticketId = $this->request->getPost('ticket_id');
        $ticketId = !empty($ticketId) ? trim((string) $ticketId) : null;

        $oldTicketId = !empty($log['ticket_id']) ? trim((string) $log['ticket_id']) : null;

        if ($oldTicketId !== $ticketId) {
            $from = $oldTicketId ?? 'None';
            $to = $ticketId ?? 'None';

            $insertedId = $commentsModel->insert([
                'log_id' => $id,
                'comment' => "Ticket ID updated from [{$from}] to [{$to}]",
                'commented_by' => session()->get('user_id'),
                'type' => Constants::COMMENT_TYPE_UPDATE,
            ]);

            $model->update($id, [
                'ticket_id' => $ticketId,
            ]);

            $insertedComment = $commentsModel->find($insertedId);
            $commentData = [
                'comment' => $insertedComment['comment'],
                'commented_by' => $insertedComment['commented_by'],
                'created_at' => $insertedComment['created_at'],
                'user_label' => Constants::USERS[$insertedComment['commented_by']] ?? Constants::USER_SYSTEM_LABEL,
                'is_system' => ($insertedComment['commented_by'] == Constants::USER_SYSTEM)
            ];

            return $this->response->setJSON([
                'success' => true,
                'message' => "Ticket ID updated successfully.",
                'ticket_id' => $ticketId,
                'jira_link' => Constants::JIRA_LINK . $ticketId,
                'csrf_hash' => csrf_hash(),
                'comment' => $commentData
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Ticket ID remains unchanged.',
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function removeTag($id)
    {
        $model = new TasksModel();
        $log = $model->find($id);
        if (!$log) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Activity log not found.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $tagIdToRemove = (int) $this->request->getPost('tag_id');
        if (empty($tagIdToRemove)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid tag ID.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if (empty($log['ticket_id'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Task is not associated with any JIRA ticket ID.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $jiraHistoryModel = new \App\Models\JiraHistoryModel();
        $jiraTickets = $jiraHistoryModel->where('ticket_id', $log['ticket_id'])->findAll();

        if (empty($jiraTickets)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No JIRA history entries found for this task.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $updatedCount = 0;
        foreach ($jiraTickets as $jt) {
            $jtTagId = !empty($jt['tag_id']) ? (int) $jt['tag_id'] : null;

            // Retrieve tag IDs from comments
            $commentsTagIds = [];
            if (!empty($jt['comments'])) {
                $rawIds = explode(',', $jt['comments']);
                foreach ($rawIds as $rid) {
                    if (is_numeric(trim($rid))) {
                        $commentsTagIds[] = (int) trim($rid);
                    }
                }
            }

            // Remove tag ID from the list
            $newCommentsTagIds = array_diff($commentsTagIds, [$tagIdToRemove]);

            // Determine new tag_id (first from new list, or null if empty)
            $newTagId = $jtTagId;
            if ($jtTagId === $tagIdToRemove) {
                $newTagId = !empty($newCommentsTagIds) ? reset($newCommentsTagIds) : null;
            }

            $updateData = [
                'comments' => implode(',', $newCommentsTagIds),
                'tag_id' => $newTagId,
                'updated_by' => session()->get('user_id') ?? 0,
            ];

            $jiraHistoryModel->update($jt['id'], $updateData);
            $updatedCount++;
        }

        // Add a system update comment to the task
        $commentsModel = new TaskCommentsModel();
        $tagModel = new \App\Models\TagsModel();
        $tag = $tagModel->find($tagIdToRemove);
        $tagName = $tag ? $tag['name'] : "#{$tagIdToRemove}";

        $insertedId = $commentsModel->insert([
            'log_id' => $id,
            'comment' => "Removed related tag [{$tagName}] from task.",
            'commented_by' => Constants::USER_SYSTEM,
            'type' => Constants::COMMENT_TYPE_UPDATE,
        ]);

        $insertedComment = $commentsModel->find($insertedId);
        $commentData = [
            'comment' => $insertedComment['comment'],
            'commented_by' => $insertedComment['commented_by'],
            'created_at' => $insertedComment['created_at'],
            'user_label' => Constants::USERS[$insertedComment['commented_by']] ?? Constants::USER_SYSTEM_LABEL,
            'is_system' => ($insertedComment['commented_by'] == Constants::USER_SYSTEM)
        ];

        return $this->response->setJSON([
            'success' => true,
            'message' => "Tag '{$tagName}' removed successfully.",
            'csrf_hash' => csrf_hash(),
            'comment' => $commentData
        ]);
    }

    public function bulkStatus()
    {
        $model = new TasksModel();
        $commentsModel = new TaskCommentsModel();

        $logIds = $this->request->getPost('log_ids');
        $status = (int) $this->request->getPost('status');

        if (empty($logIds) || !is_array($logIds)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No activity logs selected.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if (!array_key_exists($status, Constants::ACTIVITY_LOGS_STATUS)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid status value.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $to = Constants::ACTIVITY_LOGS_STATUS[$status] ?? 'Unknown';
        $updatedCount = 0;

        foreach ($logIds as $id) {
            $log = $model->find($id);
            if ($log && (int) $log['status'] != $status) {
                $from = Constants::ACTIVITY_LOGS_STATUS[$log['status']] ?? 'Unknown';

                $commentsModel->insert([
                    'log_id' => $id,
                    'comment' => "Status updated from [{$from}] to [{$to}]",
                    'commented_by' => session()->get('user_id'),
                    'type' => Constants::COMMENT_TYPE_UPDATE,
                ]);

                $updateData = [
                    'status' => $status,
                ];

                if ($status == Constants::ACTIVITY_LOGS_STATUS_COMPLETED) {
                    $updateData['finished_date'] = date('Y-m-d');
                }

                $model->update($id, $updateData);

                if ($status == Constants::ACTIVITY_LOGS_STATUS_BLOCKED) {
                    $updatedLog = $model->find($id);
                    if ($updatedLog) {
                        $this->updateRelatedTagsStatusToOnHold($updatedLog);
                    }
                } elseif ($status == Constants::ACTIVITY_LOGS_STATUS_REVIEW) {
                    $updatedLog = $model->find($id);
                    if ($updatedLog) {
                        $this->updateRelatedTagsStatusToPendingVerification($updatedLog);
                    }
                }

                $updatedCount++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Successfully marked {$updatedCount} activity log(s) as '{$to}'.",
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function bulkProject()
    {
        $model = new TasksModel();
        $commentsModel = new TaskCommentsModel();

        $logIds = $this->request->getPost('log_ids');
        $project = trim((string) $this->request->getPost('project'));

        if (empty($logIds) || !is_array($logIds)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No activity logs selected.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if ($project === '') {
            $project = 'Generic';
        }

        $updatedCount = 0;

        foreach ($logIds as $id) {
            $log = $model->find($id);
            if ($log && $log['project'] != $project) {
                $from = $log['project'] ?: 'Generic';

                $commentsModel->insert([
                    'log_id' => $id,
                    'comment' => "Project changed from [{$from}] to [{$project}]",
                    'commented_by' => session()->get('user_id'),
                    'type' => Constants::COMMENT_TYPE_UPDATE,
                ]);

                $model->update($id, [
                    'project' => $project,
                ]);
                $updatedCount++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Successfully added {$updatedCount} activity log(s) to project '{$project}'.",
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function bulkAssignee()
    {
        $model = new TasksModel();
        $commentsModel = new TaskCommentsModel();

        $logIds = $this->request->getPost('log_ids');
        $assigneeId = (int) $this->request->getPost('assignee');

        if (empty($logIds) || !is_array($logIds)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No activity logs selected.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if (!array_key_exists($assigneeId, Constants::USERS) && $assigneeId !== Constants::USER_SYSTEM) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid assignee value.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $to = $assigneeId == Constants::USER_SYSTEM
            ? Constants::USER_SYSTEM_LABEL
            : Constants::USERS[$assigneeId] ?? 'Unknown';

        $updatedCount = 0;

        foreach ($logIds as $id) {
            $log = $model->find($id);
            if ($log && (int) $log['assignee'] != $assigneeId) {
                $from = $log['assignee'] == Constants::USER_SYSTEM
                    ? Constants::USER_SYSTEM_LABEL
                    : Constants::USERS[$log['assignee']] ?? 'Unknown';

                $commentsModel->insert([
                    'log_id' => $id,
                    'comment' => "Assignee changed from [{$from}] to [{$to}]",
                    'commented_by' => session()->get('user_id'),
                    'type' => Constants::COMMENT_TYPE_UPDATE,
                ]);

                $model->update($id, [
                    'assignee' => $assigneeId,
                ]);
                $updatedCount++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Successfully reassigned {$updatedCount} activity log(s) to '{$to}'.",
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function projectView($projectName)
    {
        $projectName = urldecode($projectName);
        $model = new TasksModel();

        $meModeGet = $this->request->getGet('me_mode');
        if ($meModeGet !== null) {
            if ($meModeGet == '1') {
                session()->set('al_me_mode', 1);
            } else {
                session()->set('al_me_mode', 0);
            }
        }
        $meMode = (session()->get('al_me_mode') == 1);

        $tasks = $model->getProjectLogs($projectName, 25, $meMode);

        if ($meMode) {
            $userId = session()->get('user_id');
            $distinctProjects = $model->getDistinctProjects($userId);
        } else {
            $distinctProjects = $model->getDistinctProjects();
        }
        $projectNames = array_column($distinctProjects, 'project_name');
        // Ensure no empty values, and if so default to Generic
        $projectNames = array_map(function ($p) {
            return !empty($p) ? $p : 'Generic';
        }, $projectNames);
        $projectNames = array_unique($projectNames);

        return view('tasks/project_spreadsheet', [
            'project_name' => $projectName,
            'tasks' => $tasks,
            'pager' => $model->pager,
            'project_names' => $projectNames,
            'me_mode' => $meMode,
        ]);
    }

    public function exportProjectExcel($projectName)
    {
        $projectName = urldecode($projectName);
        $model = new TasksModel();
        $meMode = (session()->get('al_me_mode') == 1);
        $tasks = $model->getProjectLogs($projectName, null, $meMode);

        $filename = str_replace(' ', '_', $projectName) . '_Tasks_Export_' . date('Y-m-d') . '.xls';

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="UTF-8"></head>';
        echo '<body><table border="1">';

        // Header row
        $headers = [ 'Subsidiary', 'Task Title', 'Ticket ID', 'Priority', 'Assignee', 'Created Date', 'Status', 'Latest Update' ];
        echo '<tr>';
        foreach ($headers as $header) {
            echo '<th style="background-color:#f2f2f2;">' . esc($header) . '</th>';
        }
        echo '</tr>';

        foreach ($tasks as $task) {
            $subsidiary = Constants::ACTIVITY_LOG_SUBSIDIARIES[$task['subsidiary_id']] ?? 'Unknown';
            $priority = Constants::PRIORITIES[$task['priority']] ?? 'Unknown';
            $assignee = Constants::USERS[$task['assignee']] ?? Constants::USER_SYSTEM_LABEL;
            $createdDate = date('d-m-Y', strtotime($task['created_at']));
            $status = Constants::ACTIVITY_LOGS_STATUS[$task['status']] ?? 'Unknown';

            echo '<tr>';
            echo '<td>' . esc($subsidiary) . '</td>';
            echo '<td>' . esc($task['title']) . '</td>';
            echo '<td>' . esc($task['ticket_id'] ?: '') . '</td>';
            echo '<td>' . esc($priority) . '</td>';
            echo '<td>' . esc($assignee) . '</td>';
            echo '<td>' . esc($createdDate) . '</td>';
            echo '<td>' . esc($status) . '</td>';
            echo '<td>' . strip_tags($task['last_comment'] ?: '') . '</td>';
            echo '</tr>';
        }

        echo '</table></body></html>';
        exit;
    }

    public function exportProjectPDF($projectName)
    {
        $projectName = urldecode($projectName);
        $model = new TasksModel();
        $meMode = (session()->get('al_me_mode') == 1);
        $tasks = $model->getProjectLogs($projectName, null, $meMode);

        return view('tasks/project_pdf', [
            'project_name' => $projectName,
            'tasks' => $tasks,
        ]);
    }

    private function updateRelatedTagsStatusToOnHold($log)
    {
        if (($log['type'] == Constants::ACTIVITY_TYPE_RENEWAL || $log['type'] == Constants::ACTIVITY_TYPE_REQUEST) && $log['team_id'] == Constants::TEAM_MEDIA_TAGGING) {
            if (!empty($log['ticket_id'])) {
                $jiraHistoryModel = new \App\Models\JiraHistoryModel();
                $jiraTickets = $jiraHistoryModel->where('ticket_id', $log['ticket_id'])->findAll();
                if (!empty($jiraTickets)) {
                    $tagIds = [];
                    foreach ($jiraTickets as $jt) {
                        if (!empty($jt['tag_id'])) {
                            $tagIds[] = (int) $jt['tag_id'];
                        }
                        if (!empty($jt['comments'])) {
                            $rawIds = explode(',', $jt['comments']);
                            foreach ($rawIds as $rid) {
                                if (is_numeric(trim($rid))) {
                                    $tagIds[] = (int) trim($rid);
                                }
                            }
                        }
                    }
                    $tagIds = array_unique($tagIds);
                    if (!empty($tagIds)) {
                        $tagsModel = new \App\Models\TagsModel();
                        $tagsModel->whereIn('id', $tagIds)->set(['status' => Constants::STATUS_ON_HOLD])->update();
                    }
                }
            }
        }
    }

    private function updateRelatedTagsStatusToPendingVerification($log)
    {
        if (($log['type'] == Constants::ACTIVITY_TYPE_RENEWAL || $log['type'] == Constants::ACTIVITY_TYPE_REQUEST) && $log['team_id'] == Constants::TEAM_MEDIA_TAGGING) {
            if (!empty($log['ticket_id'])) {
                $jiraHistoryModel = new \App\Models\JiraHistoryModel();
                $jiraTickets = $jiraHistoryModel->where('ticket_id', $log['ticket_id'])->findAll();
                if (!empty($jiraTickets)) {
                    $tagIds = [];
                    foreach ($jiraTickets as $jt) {
                        if (!empty($jt['tag_id'])) {
                            $tagIds[] = (int) $jt['tag_id'];
                        }
                        if (!empty($jt['comments'])) {
                            $rawIds = explode(',', $jt['comments']);
                            foreach ($rawIds as $rid) {
                                if (is_numeric(trim($rid))) {
                                    $tagIds[] = (int) trim($rid);
                                }
                            }
                        }
                    }
                    $tagIds = array_unique($tagIds);
                    if (!empty($tagIds)) {
                        $tagsModel = new \App\Models\TagsModel();
                        $tagsModel->whereIn('id', $tagIds)
                                  ->where('requested_date IS NOT NULL')
                                  ->where("requested_date != ''")
                                  ->set(['status' => Constants::STATUS_PENDING_VERIFICATION])
                                  ->update();
                    }
                }
            }
        }
    }
}
