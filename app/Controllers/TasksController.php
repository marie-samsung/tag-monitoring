<?php

namespace App\Controllers;

use App\Models\JiraMapping;
use App\Models\SubsidiariesModel;
use App\Models\TaskCommentsModel;
use App\Models\TaskFilesModel;
use App\Models\TasksModel;
use App\Models\Constants;
use App\Models\UsersModel;
use App\Models\JiraApiLogModel;
use App\Models\ProjectCommentsModel;
use App\Models\NotificationsModel;
use CodeIgniter\Files\Exceptions\FileException;

class TasksController extends BaseController
{
    public function index()
    {
        $model = new TasksModel();

        $sysSettings = \App\Controllers\SettingsController::getSystemSettings();
        $forceMeMode = $sysSettings['force_me_mode_system_wide'] ?? false;
        $isAdmin = (int) session()->get('user_role') === Constants::USER_ROLE_ADMIN || (int) session()->get('account_type') === 1;

        if ($forceMeMode && !$isAdmin) {
            session()->set('al_me_mode', 1);
            $meMode = true;
        } else {
            $meModeGet = $this->request->getGet('me_mode');
            if ($meModeGet !== null) {
                if ($meModeGet == '1') {
                    session()->set('al_me_mode', 1);
                } else {
                    session()->set('al_me_mode', 0);
                }
            }
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
            'created_from' => $this->request->getGet('created_from'),
            'created_to' => $this->request->getGet('created_to'),
            'started_from' => $this->request->getGet('started_from'),
            'started_to' => $this->request->getGet('started_to'),
            'finished_from' => $this->request->getGet('finished_from'),
            'finished_to' => $this->request->getGet('finished_to'),
            'labels' => $this->request->getGet('labels'),
        ];
        $data['status_counts'] = $model->getStatusCounts($filters);
        $data['needs_attention_count'] = $model->getNeedsAttentionCount($filters);
        $data['logs'] = $model->filterLogs($filters, 25);

        $data['pager'] = $model->pager;
        $data['filters'] = $filters;

        $distinctProjects = $model->getDistinctProjects();
        $data['project_names'] = array_column($distinctProjects, 'project_name');
        $data['all_labels'] = $model->getAllLabels();

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
            $allLabels = $model->getAllLabels();
            return view('tasks/create', [
                'project_names' => $projectNames,
                'department_names' => $departmentNames,
                'log' => $log,
                'all_labels' => $allLabels
            ]);
        }

        $post = $this->request->getPost();
        $assigneeId = $post['assignee'] ?? null;

        $labelsInput = $post['labels'] ?? [];
        $labelArr = [];
        if (!empty($labelsInput)) {
            $rawLabels = is_array($labelsInput) ? $labelsInput : preg_split('/[\s,]+/', $labelsInput);
            foreach ($rawLabels as $rl) {
                $parts = preg_split('/[\s,]+/', $rl);
                foreach ($parts as $p) {
                    $p = trim($p);
                    if ($p !== '') {
                        $p = str_replace(' ', '', $p);
                        $labelArr[] = $p;
                    }
                }
            }
        }
        $labels = !empty($labelArr) ? implode(',', array_unique($labelArr)) : null;

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
            'labels' => $labels,
        ];

        $model->insert($insertData);
        $insertId = $model->getInsertID();

        if ($insertId) {
            $this->createJiraIssueFromTask($insertId);

            // Send notification to the assignee if they are a valid user
            if ($assigneeId && array_key_exists((int)$assigneeId, Constants::USERS)) {
                $notificationsModel = new NotificationsModel();
                $notificationsModel->insert([
                    'user_id'   => (int)$assigneeId,
                    'sender_id' => session()->get('user_id') ?: 0,
                    'task_id'   => $insertId,
                    'message'   => "assigned the task '" . $insertData['title'] . "' to you.",
                    'is_read'   => 0
                ]);
            }
        }

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
            $allLabels = $model->getAllLabels();
            return view('tasks/update', [
                'log' => $log,
                'project_names' => $projectNames,
                'department_names' => $departmentNames,
                'all_labels' => $allLabels
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

            // Send notification to the assignee if they are a valid user
            if (array_key_exists($assigneeId, Constants::USERS)) {
                $notificationsModel = new NotificationsModel();
                $notificationsModel->insert([
                    'user_id'   => $assigneeId,
                    'sender_id' => session()->get('user_id') ?: 0,
                    'task_id'   => $id,
                    'message'   => "assigned the task '" . $log['title'] . "' to you.",
                    'is_read'   => 0
                ]);
            }
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

        $labelsInput = $post['labels'] ?? [];
        $labelArr = [];
        if (!empty($labelsInput)) {
            $rawLabels = is_array($labelsInput) ? $labelsInput : preg_split('/[\s,]+/', $labelsInput);
            foreach ($rawLabels as $rl) {
                $parts = preg_split('/[\s,]+/', $rl);
                foreach ($parts as $p) {
                    $p = trim($p);
                    if ($p !== '') {
                        $p = str_replace(' ', '', $p);
                        $labelArr[] = $p;
                    }
                }
            }
        }
        $labels = !empty($labelArr) ? implode(',', array_unique($labelArr)) : null;

        $updateData = [
            'title' => trim($post['title']),
            'description' => $post['description'] ?? null,
            'subsidiary_id' => (int) $post['subsidiary_id'],
            'department_id' => !empty($post['department_id']) ? trim((string) $post['department_id']) : null,
            'team_id' => (int) $post['team_id'],
            'project' => !empty($post['project']) ? trim($post['project']) : 'Generic',
            'type' => (int) $post['type'] ?? null,
            'ticket_id' => !empty($post['ticket_id']) ? $post['ticket_id'] : null,
            'mt_jira_ticket' => !empty($post['mt_jira_ticket']) ? $post['mt_jira_ticket'] : null,
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
            'labels' => $labels,
        ];

        $model->update($id, $updateData);

        if (isset(Constants::TASK_STATUS_REFERENCE_MAPPING[$updateData['status']])) {
            $updatedLog = $model->find($id);
            if ($updatedLog) {
                $this->updateRelatedStatuses($updatedLog);
            }
        }

        // Trigger JIRA update
        $this->updateJiraIssueFromTask($id);

        if ($log['status'] != $updateData['status']) {
            $this->updateJiraStatusFromTask($id, $log['status'], $updateData['status']);
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

        // Sync comment to JIRA if task is associated with a JIRA ticket
        if ($insertedId) {
            $tasksModel = new TasksModel();
            $task = $tasksModel->find($post['log_id']);
            if ($task && !empty($task['mt_jira_ticket'])) {
                $commenterName = Constants::USERS[$post['commented_by']] ?? Constants::USER_SYSTEM_LABEL;

                $extractedImages = [];
                $jiraCommentText = $commenterName . " commented:\n" . $this->formatHtmlForJira($cleanComment, $extractedImages);

                $this->addCommentToJira($task['id'], $task['mt_jira_ticket'], $jiraCommentText);

                if (!empty($extractedImages)) {
                    foreach ($extractedImages as $img) {
                        $this->uploadAttachmentToJira($task['id'], $task['mt_jira_ticket'], $img['path'], $img['name']);
                        @unlink($img['path']); // clean up temp file
                    }
                }
            }
        }

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

                    $log = $model->find($logId);
                    if ($log && !empty($log['mt_jira_ticket'])) {
                        $this->uploadAttachmentToJira($logId, $log['mt_jira_ticket'], $file_path, $file_name);
                    }

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

    public function uploadAttachmentToJira($taskId, $ticketId, $filePath, $fileName)
    {
        $jiraEndpoint = getenv('MT_JIRA_UPLOAD_ATTACHMENT');
        $jiraToken = getenv('MT_JIRA_AUTH_TOKEN');

        if (empty($jiraEndpoint)) {
            return false;
        }

        if (strpos($jiraEndpoint, '{key}') !== false) {
            $jiraEndpoint = str_replace('{key}', $ticketId, $jiraEndpoint);
        } elseif (strpos($jiraEndpoint, '{id}') !== false) {
            $jiraEndpoint = str_replace('{id}', $ticketId, $jiraEndpoint);
        } else {
            $jiraEndpoint = rtrim($jiraEndpoint, '/') . '/' . $ticketId . '/attachments';
        }

        if (!file_exists($filePath)) {
            return false;
        }

        $mimeType = function_exists('mime_content_type') ? mime_content_type($filePath) : 'application/octet-stream';
        if (!$mimeType)
            $mimeType = 'application/octet-stream';

        $cfile = new \CURLFile($filePath, $mimeType, $fileName);

        $payload = [
            'file' => $cfile
        ];

        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $jiraEndpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $jiraToken,
                    'X-Atlassian-Token: no-check',
                    'User-Agent: PostmanRuntime/7.54.0',
                    'Accept: application/json',
                    'Expect:'
                ],
            ]);

            $body = curl_exec($curl);
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $errorMessage = curl_error($curl);
            curl_close($curl);

            // Log the API request
            $apiLogModel = new JiraApiLogModel();
            $status = ($statusCode >= 200 && $statusCode < 300) ? 'SUCCESS' : 'FAILED';

            $apiLogModel->logRequest(
                $taskId,
                'UPLOAD_ATTACHMENT',
                $jiraEndpoint,
                json_encode([
                    'type' => 'multipart/form-data',
                    'file_path' => $filePath,
                    'file_name' => $fileName
                ], JSON_PRETTY_PRINT),
                $body,
                $statusCode,
                $errorMessage,
                $status
            );

            return $status === 'SUCCESS';
        } catch (\Exception $e) {
            $apiLogModel = new JiraApiLogModel();
            $apiLogModel->logRequest($taskId, 'UPLOAD_ATTACHMENT', $jiraEndpoint, "File upload: {$fileName}", $e->getMessage(), 500, $e->getMessage(), 'FAILED');
            return false;
        }
    }

    public function addCommentToJira($taskId, $ticketId, $commentText)
    {
        $jiraEndpoint = getenv('MT_JIRA_ADD_COMMENT');
        $jiraToken = getenv('MT_JIRA_AUTH_TOKEN');

        if (empty($jiraEndpoint)) {
            return false;
        }

        if (strpos($jiraEndpoint, '{key}') !== false) {
            $jiraEndpoint = str_replace('{key}', $ticketId, $jiraEndpoint);
        } elseif (strpos($jiraEndpoint, '{id}') !== false) {
            $jiraEndpoint = str_replace('{id}', $ticketId, $jiraEndpoint);
        } else {
            $jiraEndpoint = rtrim($jiraEndpoint, '/') . '/' . $ticketId . '/comment';
        }

        $payload = [
            'body' => $commentText
        ];

        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $jiraEndpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $jiraToken,
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'User-Agent: PostmanRuntime/7.32.3',
                    'Expect:'
                ],
            ]);

            $body = curl_exec($curl);
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $errorMessage = curl_error($curl);
            curl_close($curl);

            // Log the API request
            $apiLogModel = new JiraApiLogModel();
            $status = ($statusCode >= 200 && $statusCode < 300) ? 'SUCCESS' : 'FAILED';
            $apiLogModel->logRequest(
                $taskId,
                'ADD_COMMENT',
                $jiraEndpoint,
                json_encode($payload),
                $body,
                $statusCode,
                $errorMessage,
                $status
            );

            return $statusCode >= 200 && $statusCode < 300;
        } catch (\Exception $e) {
            $apiLogModel = new JiraApiLogModel();
            $apiLogModel->logRequest($taskId, 'ADD_COMMENT', $jiraEndpoint, json_encode($payload), $e->getMessage(), 500, $e->getMessage(), 'FAILED');
            return false;
        }
    }

    public function updateCommentToJira($taskId, $ticketId, $commentId, $commentText)
    {
        $jiraEndpoint = getenv('MT_JIRA_UPDATE_COMMENT');
        $jiraToken = getenv('MT_JIRA_AUTH_TOKEN');

        if (empty($jiraEndpoint)) {
            return false;
        }

        if (strpos($jiraEndpoint, '{key}') !== false) {
            $jiraEndpoint = str_replace('{key}', $ticketId, $jiraEndpoint);
        } elseif (strpos($jiraEndpoint, '{id}') !== false) {
            $jiraEndpoint = str_replace('{id}', $ticketId, $jiraEndpoint);
        } else {
            $jiraEndpoint = rtrim($jiraEndpoint, '/');
        }

        if (strpos($jiraEndpoint, '{commentId}') !== false) {
            $jiraEndpoint = str_replace('{commentId}', $commentId, $jiraEndpoint);
        } else {
            $jiraEndpoint .= '/' . $commentId;
        }

        $payload = [
            'body' => $commentText
        ];

        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $jiraEndpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $jiraToken,
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'User-Agent: PostmanRuntime/7.32.3',
                    'Expect:'
                ],
            ]);

            $body = curl_exec($curl);
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $errorMessage = curl_error($curl);
            curl_close($curl);

            // Log the API request
            $apiLogModel = new JiraApiLogModel();
            $status = ($statusCode >= 200 && $statusCode < 300) ? 'SUCCESS' : 'FAILED';
            $apiLogModel->logRequest(
                $taskId,
                'UPDATE_COMMENT',
                $jiraEndpoint,
                json_encode($payload),
                $body,
                $statusCode,
                $errorMessage,
                $status
            );

            return $statusCode >= 200 && $statusCode < 300;
        } catch (\Exception $e) {
            $apiLogModel = new JiraApiLogModel();
            $apiLogModel->logRequest($taskId, 'UPDATE_COMMENT', $jiraEndpoint, json_encode($payload), $e->getMessage(), 500, $e->getMessage(), 'FAILED');
            return false;
        }
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
            'created_from' => $this->request->getGet('created_from'),
            'created_to' => $this->request->getGet('created_to'),
            'started_from' => $this->request->getGet('started_from'),
            'started_to' => $this->request->getGet('started_to'),
            'finished_from' => $this->request->getGet('finished_from'),
            'finished_to' => $this->request->getGet('finished_to'),
            'labels' => $this->request->getGet('labels'),
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
            'Subsidiary',
            'Team',
            'Project Name',
            'Activity Log Type',
            'Assigned To',
            'Title',
            'Description',
            'Status',
            'Priority',
            'Size',
            'Ticket ID',
            '(Campaign) Department',
            '(Campaign) Status',
            '(Campaign) ID',
            'Requested By',
            'Requested Date',
            'Finished Date',
            'Created Date'
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

            if (isset(Constants::TASK_STATUS_REFERENCE_MAPPING[$status])) {
                $updatedLog = $model->find($id);
                if ($updatedLog) {
                    $this->updateRelatedStatuses($updatedLog);
                }
            }

            $this->updateJiraStatusFromTask($id, $log['status'], $status);

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

            $this->updateJiraIssueFromTask($id);

            // Send notification to the assignee if they are a valid user
            if (array_key_exists($assigneeId, Constants::USERS)) {
                $notificationsModel = new NotificationsModel();
                $notificationsModel->insert([
                    'user_id'   => $assigneeId,
                    'sender_id' => session()->get('user_id') ?: 0,
                    'task_id'   => $id,
                    'message'   => "assigned the task '" . $log['title'] . "' to you.",
                    'is_read'   => 0
                ]);
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

            $this->updateJiraIssueFromTask($id);

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

                if (isset(Constants::TASK_STATUS_REFERENCE_MAPPING[$status])) {
                    $updatedLog = $model->find($id);
                    if ($updatedLog) {
                        $this->updateRelatedStatuses($updatedLog);
                    }
                }

                $this->updateJiraStatusFromTask($id, $log['status'], $status);

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

                // Sync the updated project to JIRA
                $this->updateJiraIssueFromTask($id);

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

                // Send notification to the assignee if they are a valid user
                if (array_key_exists($assigneeId, Constants::USERS)) {
                    $notificationsModel = new NotificationsModel();
                    $notificationsModel->insert([
                        'user_id'   => $assigneeId,
                        'sender_id' => session()->get('user_id') ?: 0,
                        'task_id'   => $id,
                        'message'   => "assigned the task '" . $log['title'] . "' to you.",
                        'is_read'   => 0
                    ]);
                }
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
        // Run migration automatically so it will create the table if doesn't exist
        try {
            $migration = \Config\Services::migrations();
            $migration->latest();
        } catch (\Throwable $e) {
            log_message('error', 'Auto migration failed: ' . $e->getMessage());
        }

        $projectName = urldecode($projectName);
        $model = new TasksModel();

        $sysSettings = \App\Controllers\SettingsController::getSystemSettings();
        $forceMeMode = $sysSettings['force_me_mode_system_wide'] ?? false;
        $isAdmin = (int) session()->get('user_role') === Constants::USER_ROLE_ADMIN || (int) session()->get('account_type') === 1;

        if ($forceMeMode && !$isAdmin) {
            session()->set('al_me_mode', 1);
            $meMode = true;
        } else {
            $meModeGet = $this->request->getGet('me_mode');
            if ($meModeGet !== null) {
                if ($meModeGet == '1') {
                    session()->set('al_me_mode', 1);
                } else {
                    session()->set('al_me_mode', 0);
                }
            }
            $meMode = (session()->get('al_me_mode') == 1);
        }

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

        $progressPercentage = $model->getProjectProgress($projectName, $meMode);

        $projectCommentsModel = new ProjectCommentsModel();
        $counts = $projectCommentsModel->select('project_name, COUNT(*) as count')
            ->groupBy('project_name')
            ->findAll();
        $projectCommentCounts = [];
        foreach ($counts as $row) {
            $pName = !empty($row['project_name']) ? $row['project_name'] : 'Generic';
            $projectCommentCounts[$pName] = (int) $row['count'];
        }

        return view('tasks/project_spreadsheet', [
            'project_name' => $projectName,
            'tasks' => $tasks,
            'pager' => $model->pager,
            'project_names' => $projectNames,
            'me_mode' => $meMode,
            'progress_percentage' => $progressPercentage,
            'project_comment_counts' => $projectCommentCounts,
        ]);
    }

    public function getProjectComments($projectName)
    {
        helper('custom_functions');
        $projectName = urldecode($projectName);
        $commentsModel = new ProjectCommentsModel();
        $comments = $commentsModel->getProjectComments($projectName);

        $userId = session()->get('user_id');
        $isAdmin = (Constants::USER_ACCESS_LEVELS[$userId] ?? null) === Constants::USER_ROLE_ADMIN;

        $formatted = [];
        foreach ($comments as $comment) {
            $formatted[] = [
                'id' => $comment['id'],
                'comment' => clean_html_comment($comment['comment']),
                'commented_by' => $comment['commented_by'],
                'user_label' => Constants::USERS[$comment['commented_by']] ?? Constants::USER_SYSTEM_LABEL,
                'created_at' => $comment['created_at'],
                'formatted_time' => format_comment_time($comment['created_at']),
                'can_delete' => ($comment['commented_by'] == $userId || $isAdmin),
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'comments' => $formatted,
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function postProjectComment()
    {
        helper('custom_functions');
        $commentsModel = new ProjectCommentsModel();
        $post = $this->request->getPost();

        if (!$post || empty($post['comment'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No comment data provided.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $cleanComment = clean_html_comment($post['comment']);

        $insertedId = $commentsModel->insert([
            'project_name' => $post['project_name'],
            'comment' => $cleanComment,
            'commented_by' => $post['commented_by'],
        ]);

        $insertedComment = $commentsModel->find($insertedId);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Comment posted successfully.',
            'csrf_hash' => csrf_hash(),
            'comment' => [
                'id' => $insertedComment['id'],
                'comment' => $insertedComment['comment'],
                'commented_by' => $insertedComment['commented_by'],
                'user_label' => Constants::USERS[$insertedComment['commented_by']] ?? Constants::USER_SYSTEM_LABEL,
                'created_at' => $insertedComment['created_at'],
                'formatted_time' => format_comment_time($insertedComment['created_at']),
                'can_delete' => true,
            ]
        ]);
    }

    public function deleteProjectComment()
    {
        $commentsModel = new ProjectCommentsModel();
        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid comment ID.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $comment = $commentsModel->find($id);
        if (!$comment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Comment not found.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $userId = session()->get('user_id');
        $isAdmin = (Constants::USER_ACCESS_LEVELS[$userId] ?? null) === Constants::USER_ROLE_ADMIN;
        if ($comment['commented_by'] != $userId && !$isAdmin) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are not authorized to delete this comment.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $commentsModel->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Comment deleted.',
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function restoreProjectComment()
    {
        $commentsModel = new ProjectCommentsModel();
        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid comment ID.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $comment = $commentsModel->onlyDeleted()->find($id);
        if (!$comment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Comment not found or not deleted.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $userId = session()->get('user_id');
        $isAdmin = (Constants::USER_ACCESS_LEVELS[$userId] ?? null) === Constants::USER_ROLE_ADMIN;
        if ($comment['commented_by'] != $userId && !$isAdmin) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are not authorized to restore this comment.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $db = \Config\Database::connect();
        $db->table('project_comments')
            ->where('id', $id)
            ->update(['deleted_at' => null]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Comment restored.',
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function getComments($logId)
    {
        helper('custom_functions');
        $commentsModel = new TaskCommentsModel();
        $comments = $commentsModel->getRelatedComments($logId);

        $formatted = [];
        foreach ($comments as $comment) {
            $formatted[] = [
                'id' => $comment['id'],
                'comment' => clean_html_comment($comment['comment']),
                'commented_by' => $comment['commented_by'],
                'user_label' => Constants::USERS[$comment['commented_by']] ?? Constants::USER_SYSTEM_LABEL,
                'is_system' => ($comment['commented_by'] == Constants::USER_SYSTEM),
                'created_at' => $comment['created_at'],
                'formatted_time' => format_comment_time($comment['created_at']),
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'comments' => $formatted,
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function exportProjectExcel($projectName)
    {
        $projectName = urldecode($projectName);
        $model = new TasksModel();
        $sysSettings = \App\Controllers\SettingsController::getSystemSettings();
        $forceMeMode = $sysSettings['force_me_mode_system_wide'] ?? false;
        $isAdmin = (int) session()->get('user_role') === Constants::USER_ROLE_ADMIN || (int) session()->get('account_type') === 1;
        $meMode = ($forceMeMode && !$isAdmin) ? true : (session()->get('al_me_mode') == 1);

        $tasks = $model->getProjectLogs($projectName, null, $meMode);

        $filename = str_replace(' ', '_', $projectName) . '_Tasks_Export_' . date('Y-m-d') . '.xls';

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="UTF-8"></head>';
        echo '<body><table border="1">';

        // Header row
        $headers = ['Subsidiary', 'Task Title', 'Ticket ID', 'Priority', 'Assignee', 'Created Date', 'Status', 'Latest Update'];
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
        $sysSettings = \App\Controllers\SettingsController::getSystemSettings();
        $forceMeMode = $sysSettings['force_me_mode_system_wide'] ?? false;
        $isAdmin = (int) session()->get('user_role') === Constants::USER_ROLE_ADMIN || (int) session()->get('account_type') === 1;
        $meMode = ($forceMeMode && !$isAdmin) ? true : (session()->get('al_me_mode') == 1);

        $tasks = $model->getProjectLogs($projectName, null, $meMode);

        return view('tasks/project_pdf', [
            'project_name' => $projectName,
            'tasks' => $tasks,
        ]);
    }

    private function formatHtmlForJira($html, &$extractedImages = null)
    {
        if (empty($html))
            return '';

        if ($extractedImages !== null) {
            $html = preg_replace_callback('/<img\s+[^>]*src=["\']data:image\/([^;]+);base64,([^"\']+)["\'][^>]*>/i', function ($matches) use (&$extractedImages) {
                $ext = $matches[1];
                if ($ext === 'jpeg')
                    $ext = 'jpg';
                $base64 = $matches[2];
                $data = base64_decode($base64);

                $filename = 'inline_' . substr(md5(uniqid()), 0, 8) . '.' . $ext;
                $tempDir = WRITEPATH . 'uploads/temp';
                if (!is_dir($tempDir)) {
                    mkdir($tempDir, 0777, true);
                }

                $tempPath = $tempDir . '/' . $filename;
                file_put_contents($tempPath, $data);

                $extractedImages[] = [
                    'path' => $tempPath,
                    'name' => $filename
                ];

                return " !$filename! ";
            }, $html);
        }

        // Convert line breaks and paragraphs
        $html = preg_replace('/<br\s*\/?>/i', "\n", $html);
        $html = preg_replace('/<\/p>/i', "\n\n", $html);

        // Convert list items to Jira bullets
        $html = preg_replace('/<li>/i', "\n* ", $html);

        // Convert bold, italic, and underline
        $html = preg_replace('/<(b|strong)[^>]*>(.*?)<\/\1>/is', '*$2*', $html);
        $html = preg_replace('/<(i|em)[^>]*>(.*?)<\/\1>/is', '_$2_', $html);
        $html = preg_replace('/<u[^>]*>(.*?)<\/u>/is', '+$1+', $html);

        // Strip the rest of the HTML tags
        $text = strip_tags($html);

        // Decode HTML entities (e.g. &nbsp; to space)
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Clean up excessive newlines
        $text = preg_replace("/\n\s*\n\s*\n/", "\n\n", $text);

        return trim($text);
    }

    public function createJiraIssueFromTask($id)
    {
        $model = new TasksModel();
        $log = $model->find($id);
        $usersModel = new UsersModel();

        if (!$log) {
            return false;
        }

        $jiraEndpoint = getenv('MT_JIRA_CREATE_ISSUE');
        $jiraToken = getenv('MT_JIRA_AUTH_TOKEN');
        $projectKey = getenv('MT_JIRA_PROJECT_KEY');

        if (empty($jiraEndpoint)) {
            return false;
        }

        $jiraLabels = [];
        $jiraLabels[] = (string) $log['id'];
        $jiraLabels[] = str_replace(' ', '_', (string) Constants::ACTIVITY_TYPES[$log['type']]);

        if (!empty($log['ticket_id'])) {
            $jiraLabels[] = str_replace(' ', '_', (string) $log['ticket_id']);
        }

        if (!empty($log['labels'])) {
            $labelsArr = is_array($log['labels']) ? $log['labels'] : explode(',', $log['labels']);
            foreach ($labelsArr as $lbl) {
                $lbl = trim($lbl);
                if (!empty($lbl)) {
                    // Jira labels cannot contain spaces
                    $jiraLabels[] = str_replace(' ', '_', $lbl);
                }
            }
        }

        $baseDate = !empty($log['start_date']) ? $log['start_date'] : $log['created_at'];
        $dueDate = date('Y-m-d', strtotime($baseDate . ' + 10 days'));

        $extractedImages = [];

        // Prepare the payload based on Task details
        $payloadFields = [
            'project' => [
                'key' => $projectKey
            ],
            'issuetype' => [
                'name' => 'Task' // this will always be Task
            ],
            'summary' => $log['title'],
            'components' => [
                [
                    'name' => Constants::TEAMS[$log['team_id']]
                ]
            ],
            'description' => $this->formatHtmlForJira($log['description'] ?? $log['title'], $extractedImages),
            'customfield_24905' => $log['project'],
            'customfield_12014' => Constants::ACTIVITY_LOG_SUBSIDIARIES[$log['subsidiary_id']],
            'priority' => [
                'name' => JiraMapping::PRIORITIES[$log['priority']],
            ],
            'labels' => $jiraLabels,
            'reporter' => [
                'name' => $usersModel->getUsernameById($log['created_by']) ?? session()->get('username'),
            ],
            'assignee' => [
                'name' => $usersModel->getUsernameById($log['assignee']) ?? session()->get('username'),
            ],
            'duedate' => $dueDate,
            'timetracking' => [
                'originalEstimate' => JiraMapping::ESTIMATES[$log['size']],
            ]
        ];

        $reporterName = $usersModel->getUsernameById($log['created_by']);
        if (!empty($reporterName)) {
            $payloadFields['reporter'] = ['name' => $reporterName];
        }

        $assigneeName = $usersModel->getUsernameById($log['assignee']);
        if (!empty($assigneeName)) {
            $payloadFields['assignee'] = ['name' => $assigneeName];
        }

        $payload = [
            'fields' => $payloadFields
        ];

        try {
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $jiraEndpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $jiraToken,
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'User-Agent: PostmanRuntime/7.32.3',
                    'Expect:'
                ],
            ]);

            $body = curl_exec($curl);
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $errorMessage = curl_error($curl);
            curl_close($curl);

            // Decode body if possible
            $decodedBody = json_decode($body, true);

            // Log the API request
            $apiLogModel = new JiraApiLogModel();
            $status = ($statusCode >= 200 && $statusCode < 300) ? 'SUCCESS' : 'FAILED';
            $apiLogModel->logRequest(
                $id,
                'CREATE_ISSUE',
                $jiraEndpoint,
                json_encode($payload),
                $body,
                $statusCode,
                $errorMessage,
                $status
            );

            if ($statusCode >= 200 && $statusCode < 300) {
                $ticketId = $decodedBody['key'] ?? null;

                if ($ticketId) {
                    $commentsModel = new TaskCommentsModel();
                    $oldTicketId = !empty($log['mt_jira_ticket']) ? trim((string) $log['mt_jira_ticket']) : 'None';

                    // Update local DB
                    $model->update($id, ['mt_jira_ticket' => $ticketId]);

                    // Upload inline images
                    if (!empty($extractedImages)) {
                        foreach ($extractedImages as $img) {
                            $this->uploadAttachmentToJira($id, $ticketId, $img['path'], $img['name']);
                            @unlink($img['path']); // clean up temp file
                        }
                    }

                    // Add an update comment
                    $commentsModel->insert([
                        'log_id' => $id,
                        'comment' => "MT JIRA Ticket updated from [{$oldTicketId}] to [{$ticketId}] via API creation",
                        'commented_by' => session()->get('user_id') ?? Constants::USER_SYSTEM,
                        'type' => Constants::COMMENT_TYPE_UPDATE,
                    ]);

                    // Send reference link on our system to JIRA as a comment
                    $referenceLink = site_url('tasks/view/' . $id);
                    $jiraCommentText = "Reference: " . $referenceLink;
                    $this->addCommentToJira($id, $ticketId, $jiraCommentText);

                    // Check if the current status requires transitioning the JIRA ticket from To Do
                    $currentStatus = $log['status'];
                    $mappedJiraStatus = JiraMapping::STATUS_MAPPING[(int) $currentStatus] ?? null;

                    if ($mappedJiraStatus && $mappedJiraStatus !== JiraMapping::JIRA_STATUS_TODO) {
                        $oldJiraStatus = JiraMapping::JIRA_STATUS_TODO;
                        $transitionId = JiraMapping::TRANSITIONS[$oldJiraStatus][$mappedJiraStatus] ?? null;

                        if ($transitionId) {
                            $jiraEndpointUpdate = getenv('MT_JIRA_UPDATE_STATUS');
                            if (!empty($jiraEndpointUpdate)) {
                                if (strpos($jiraEndpointUpdate, '{key}') !== false) {
                                    $jiraEndpointUpdate = str_replace('{key}', $ticketId, $jiraEndpointUpdate);
                                } elseif (strpos($jiraEndpointUpdate, '{id}') !== false) {
                                    $jiraEndpointUpdate = str_replace('{id}', $ticketId, $jiraEndpointUpdate);
                                } else {
                                    $jiraEndpointUpdate = rtrim($jiraEndpointUpdate, '/') . '/' . $ticketId . '/transitions';
                                }

                                $transitionPayload = [
                                    'transition' => [
                                        'id' => $transitionId
                                    ]
                                ];

                                try {
                                    $curl2 = curl_init();
                                    curl_setopt_array($curl2, [
                                        CURLOPT_URL => $jiraEndpointUpdate,
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 30,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'POST',
                                        CURLOPT_POSTFIELDS => json_encode($transitionPayload),
                                        CURLOPT_HTTPHEADER => [
                                            'Authorization: Bearer ' . $jiraToken,
                                            'Content-Type: application/json',
                                            'Accept: application/json',
                                            'User-Agent: PostmanRuntime/7.32.3',
                                            'Expect:'
                                        ],
                                    ]);

                                    $body2 = curl_exec($curl2);
                                    $statusCode2 = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
                                    $errorMessage2 = curl_error($curl2);
                                    curl_close($curl2);

                                    $status2 = ($statusCode2 >= 200 && $statusCode2 < 300) ? 'SUCCESS' : 'FAILED';
                                    $apiLogModel->logRequest(
                                        $id,
                                        'UPDATE_STATUS',
                                        $jiraEndpointUpdate,
                                        json_encode($transitionPayload),
                                        $body2,
                                        $statusCode2,
                                        $errorMessage2,
                                        $status2
                                    );
                                } catch (\Exception $e) {
                                    $apiLogModel->logRequest($id, 'UPDATE_STATUS', $jiraEndpointUpdate, json_encode($transitionPayload), $e->getMessage(), 500, $e->getMessage(), 'FAILED');
                                }
                            }
                        }
                    }
                }

                return true;
            }

            return false;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function createJiraIssue($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/tasks');
        }

        $success = $this->createJiraIssueFromTask($id);

        if ($success) {
            $model = new TasksModel();
            $log = $model->find($id);
            $ticketId = $log['mt_jira_ticket'] ?? '';

            return $this->response->setJSON([
                'success' => true,
                'message' => 'MT JIRA Ticket created successfully.',
                'ticket_id' => $ticketId,
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to create MT JIRA Ticket. Please check API Logs.',
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function updateJiraIssueFromTask($id)
    {
        $model = new TasksModel();
        $log = $model->find($id);
        $usersModel = new UsersModel();

        if (!$log || empty($log['mt_jira_ticket'])) {
            return false;
        }

        $jiraEndpoint = getenv('MT_JIRA_EDIT_ISSUE');
        $jiraToken = getenv('MT_JIRA_AUTH_TOKEN');

        if (empty($jiraEndpoint)) {
            return false;
        }

        if (strpos($jiraEndpoint, '{key}') !== false) {
            $jiraEndpoint = str_replace('{key}', $log['mt_jira_ticket'], $jiraEndpoint);
        } elseif (strpos($jiraEndpoint, '{id}') !== false) {
            $jiraEndpoint = str_replace('{id}', $log['mt_jira_ticket'], $jiraEndpoint);
        } else {
            $jiraEndpoint = rtrim($jiraEndpoint, '/') . '/' . $log['mt_jira_ticket'];
        }

        $jiraLabels = [];
        $jiraLabels[] = (string) $log['id'];
        $jiraLabels[] = str_replace(' ', '_', (string) Constants::ACTIVITY_TYPES[$log['type']]);

        if (!empty($log['ticket_id'])) {
            $jiraLabels[] = str_replace(' ', '_', (string) $log['ticket_id']);
        }

        if (!empty($log['labels'])) {
            $labelsArr = is_array($log['labels']) ? $log['labels'] : explode(',', $log['labels']);
            foreach ($labelsArr as $lbl) {
                $lbl = trim($lbl);
                if (!empty($lbl)) {
                    $jiraLabels[] = str_replace(' ', '_', $lbl);
                }
            }
        }

        $baseDate = !empty($log['start_date']) ? $log['start_date'] : $log['created_at'];
        $dueDate = date('Y-m-d', strtotime($baseDate . ' + 7 days'));

        $extractedImages = [];

        $payloadFields = [
            'summary' => $log['title'],
            'components' => [
                [
                    'name' => Constants::TEAMS[$log['team_id']]
                ]
            ],
            'description' => $this->formatHtmlForJira($log['description'] ?? $log['title'], $extractedImages),
            'customfield_24905' => $log['project'],
            'customfield_12014' => Constants::ACTIVITY_LOG_SUBSIDIARIES[$log['subsidiary_id']],
            'priority' => [
                'name' => JiraMapping::PRIORITIES[$log['priority']],
            ],
            'labels' => $jiraLabels,
            'assignee' => [
                'name' => $usersModel->getUsernameById($log['assignee']) ?? session()->get('username'),
            ],
            'duedate' => $dueDate,
            'timetracking' => [
                'originalEstimate' => JiraMapping::ESTIMATES[$log['size']],
            ]
        ];

        $payload = [
            'fields' => $payloadFields
        ];

        try {
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $jiraEndpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $jiraToken,
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'User-Agent: PostmanRuntime/7.32.3',
                    'Expect:'
                ],
            ]);

            $body = curl_exec($curl);
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $errorMessage = curl_error($curl);
            curl_close($curl);

            // Log the API request
            $apiLogModel = new JiraApiLogModel();
            $status = ($statusCode >= 200 && $statusCode < 300) ? 'SUCCESS' : 'FAILED';
            $apiLogModel->logRequest(
                $id,
                'EDIT_ISSUE',
                $jiraEndpoint,
                json_encode($payload),
                $body,
                $statusCode,
                $errorMessage,
                $status
            );
            if ($status === 'SUCCESS' && !empty($extractedImages)) {
                foreach ($extractedImages as $img) {
                    $this->uploadAttachmentToJira($id, $log['mt_jira_ticket'], $img['path'], $img['name']);
                    @unlink($img['path']); // clean up temp file
                }
            }

            return $status === 'SUCCESS';
        } catch (\Exception $e) {
            $apiLogModel = new JiraApiLogModel();
            $apiLogModel->logRequest($id, 'EDIT_ISSUE', $jiraEndpoint, json_encode($payload), $e->getMessage(), 500, $e->getMessage(), 'FAILED');
            return false;
        }
    }

    public function updateJiraStatusFromTask($id, $oldInternalStatus, $newInternalStatus)
    {
        $model = new TasksModel();
        $log = $model->find($id);

        if (!$log || empty($log['mt_jira_ticket'])) {
            return false; // Not a JIRA synced task
        }

        // Get JIRA buckets
        $oldJiraStatus = JiraMapping::STATUS_MAPPING[(int) $oldInternalStatus] ?? null;
        $newJiraStatus = JiraMapping::STATUS_MAPPING[(int) $newInternalStatus] ?? null;

        // Skip if either status is unmapped, or if the JIRA bucket hasn't actually changed
        if (!$oldJiraStatus || !$newJiraStatus || $oldJiraStatus === $newJiraStatus) {
            return false;
        }

        // Find transition ID
        $transitionId = JiraMapping::TRANSITIONS[$oldJiraStatus][$newJiraStatus] ?? null;

        if (!$transitionId) {
            return false; // No valid transition between these states
        }

        $jiraEndpoint = getenv('MT_JIRA_UPDATE_STATUS');
        $jiraToken = getenv('MT_JIRA_AUTH_TOKEN');

        if (empty($jiraEndpoint)) {
            return false;
        }

        if (strpos($jiraEndpoint, '{key}') !== false) {
            $jiraEndpoint = str_replace('{key}', $log['mt_jira_ticket'], $jiraEndpoint);
        } elseif (strpos($jiraEndpoint, '{id}') !== false) {
            $jiraEndpoint = str_replace('{id}', $log['mt_jira_ticket'], $jiraEndpoint);
        } else {
            $jiraEndpoint = rtrim($jiraEndpoint, '/') . '/' . $log['mt_jira_ticket'] . '/transitions';
        }

        $payload = [
            'transition' => [
                'id' => $transitionId
            ]
        ];

        try {
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $jiraEndpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $jiraToken,
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'User-Agent: PostmanRuntime/7.32.3',
                    'Expect:'
                ],
            ]);

            $body = curl_exec($curl);
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $errorMessage = curl_error($curl);
            curl_close($curl);

            // Log the API request
            $apiLogModel = new JiraApiLogModel();
            $status = ($statusCode >= 200 && $statusCode < 300) ? 'SUCCESS' : 'FAILED';
            $apiLogModel->logRequest(
                $id,
                'UPDATE_STATUS',
                $jiraEndpoint,
                json_encode($payload),
                $body,
                $statusCode,
                $errorMessage,
                $status
            );

            return $status === 'SUCCESS';
        } catch (\Exception $e) {
            $apiLogModel = new JiraApiLogModel();
            $apiLogModel->logRequest($id, 'UPDATE_STATUS', $jiraEndpoint, json_encode($payload), $e->getMessage(), 500, $e->getMessage(), 'FAILED');
            return false;
        }
    }

    private function updateRelatedStatuses($log)
    {
        if (!isset(Constants::TASK_STATUS_REFERENCE_MAPPING[$log['status']])) {
            return;
        }

        $mapping = Constants::TASK_STATUS_REFERENCE_MAPPING[$log['status']];

        // Update Tag Status
        if (isset($mapping['tag']) && ($log['type'] == Constants::ACTIVITY_TYPE_RENEWAL || $log['type'] == Constants::ACTIVITY_TYPE_REQUEST) && $log['team_id'] == Constants::TEAM_MEDIA_TAGGING) {
            if (!empty($log['ticket_id'])) {
                $tagIds = [];
                
                // 1. Try to get tags directly from Tag Requests
                $tagRequestsModel = new \App\Models\TagRequestsModel();
                $requests = $tagRequestsModel->groupStart()
                    ->where('new_tag_ticket', $log['ticket_id'])
                    ->orWhere('new_rule_ticket', $log['ticket_id'])
                    ->groupEnd()
                    ->findAll();
                    
                foreach ($requests as $request) {
                    if (!empty($request['tags'])) {
                        $rawIds = explode(',', $request['tags']);
                        foreach ($rawIds as $rid) {
                            if (is_numeric(trim($rid))) {
                                $tagIds[] = (int) trim($rid);
                            }
                        }
                    }
                }

                // 2. Fallback to JiraHistoryModel
                if (empty($tagIds)) {
                    $jiraHistoryModel = new \App\Models\JiraHistoryModel();
                    $jiraTickets = $jiraHistoryModel->where('ticket_id', $log['ticket_id'])->findAll();
                    if (!empty($jiraTickets)) {
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
                    }
                }

                $tagIds = array_unique($tagIds);
                if (!empty($tagIds)) {
                    $tagsModel = new \App\Models\TagsModel();
                    $query = $tagsModel->whereIn('id', $tagIds);

                    if ($mapping['tag'] == Constants::STATUS_PENDING_VERIFICATION && $log['type'] == Constants::ACTIVITY_TYPE_RENEWAL) {
                        $query->groupStart()
                              ->where('requested_date IS NOT NULL')
                              ->where("requested_date != ''")
                              ->groupEnd();
                    }

                    $query->set(['status' => $mapping['tag']])->update();
                }
            }
        }

        // Update Tag Request Status
        if (isset($mapping['request']) && !empty($log['ticket_id'])) {
            $tagRequestsModel = new \App\Models\TagRequestsModel();
            $requests = $tagRequestsModel->groupStart()
                ->where('new_tag_ticket', $log['ticket_id'])
                ->orWhere('new_rule_ticket', $log['ticket_id'])
                ->groupEnd()
                ->findAll();

            foreach ($requests as $request) {
                if ($request['status'] != $mapping['request']) {
                    $tagRequestsModel->update($request['id'], ['status' => $mapping['request']]);
                }
            }
        }
    }
}
