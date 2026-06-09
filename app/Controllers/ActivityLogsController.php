<?php

namespace App\Controllers;

use App\Models\ActivityLogCommentsModel;
use App\Models\ActivityLogFilesModel;
use App\Models\ActivityLogsModel;
use App\Models\Constants;
use CodeIgniter\Files\Exceptions\FileException;

class ActivityLogsController extends BaseController
{
    public function index()
    {
        $model = new ActivityLogsModel();
        
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
            'project_id' => $this->request->getGet('project_id'),
            'type' => $this->request->getGet('type'),
            'status' => $this->request->getGet('status'),
            'status_group' => $this->request->getGet('status_group'),
            'priority' => $this->request->getGet('priority'),
            'assignee' => $this->request->getGet('assignee'),
            'me_mode' => $meMode ? 1 : 0,
            'sort_by' => $this->request->getGet('sort_by') ?? 'created_at',
            'sort_dir' => $this->request->getGet('sort_dir') ?? 'desc',
        ];
        $data['status_counts'] = $model->getStatusCounts($filters);
        $data['logs'] = $model->filterLogs($filters, 25);
        $data['pager'] = $model->pager;
        $data['filters'] = $filters;
        return view('activity-logs/list', $data);
    }

    public function create()
    {
        $model = new ActivityLogsModel();

        if ($this->request->getMethod() === 'GET') {
            return view('activity-logs/create', []);
        }

        $post = $this->request->getPost();
        $isAdmin = (session()->get('user_role') === Constants::USER_ROLE_ADMIN);
        $assigneeId = $isAdmin ? Constants::USER_SYSTEM : (int)($post['assignee'] ?? Constants::USER_SYSTEM);

        $insertData = [
            'title'           => trim($post['title']),
            'description'     => $post['description'] ?? null,
            'subsidiary_id'   => (int)$post['subsidiary_id'],
            'department_id'   => !empty($post['department_id']) ? (int)$post['department_id'] : null,
            'project_id'      => (int)$post['project_id'],
            'type'            => (int)$post['type'] ?? null,
            'ticket_id'       => !empty($post['ticket_id']) ? $post['ticket_id'] : null,
            'assignee'        => $assigneeId,
            'requested_by'    => $post['requested_by'] ?? null,
            'status'          => (int)$post['status'],
            'size'            => (int)$post['size'] ?? null,
            'requested_date'  => !empty($post['requested_date']) ? $post['requested_date'] : null,
            'finished_date'   => !empty($post['finished_date']) ? $post['finished_date'] : null,
            'campaign_status' => !empty($post['campaign_status']) ? (int)$post['campaign_status'] : null,
            'campaign_id'     => $post['campaign_id'] ?? null,
        ];

        $model->insert($insertData);

        return redirect()
            ->to('/activity-logs')
            ->with('success', "Activity log '{$insertData['title']}' created successfully.");
    }

    public function view($id)
    {
        $model = new ActivityLogsModel();
        $comments = new ActivityLogCommentsModel();
        $files = new ActivityLogFilesModel();
        $log = $model->find($id);

        if (!$log) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Activity log with ID $id not found.");
        }

        return view('activity-logs/view', [
            'log' => $log,
            'comments' => $comments->getRelatedComments($id),
            'files' => $files->getRelatedFiles($id),
        ]);
    }

    public function update($id)
    {
        helper('custom_functions');
        $model = new ActivityLogsModel();
        $commentsModel = new ActivityLogCommentsModel();
        $log = $model->find($id);

        if (!$log) {
            return redirect()
                ->to('/activity-logs')
                ->with('error', 'Activity log not found.');
        }

        if ($this->request->getMethod() === 'GET') {
            return view('activity-logs/update', [
                'log' => $log
            ]);
        }

        $post = $this->request->getPost();
        $isAdmin = (session()->get('user_role') === Constants::USER_ROLE_ADMIN);
        $assigneeId = $isAdmin ? (int)$log['assignee'] : (int)($post['assignee'] ?? $log['assignee']);

        // @NOTE: ACTIVITY LOGS AUTOMATIC UPDATE COMMENTS
        if ($log['status'] !== $post['status']) {
            $from = Constants::ACTIVITY_LOGS_STATUS[$log['status']];
            $to = Constants::ACTIVITY_LOGS_STATUS[$post['status']];

            $commentsModel->insert([
                'log_id' => $id,
                'comment' => "Status updated from [{$from}] to [{$to}]",
                'commented_by' => Constants::USER_SYSTEM,
                'type' => Constants::COMMENT_TYPE_UPDATE,
            ]);

            if ($post['status'] == Constants::ACTIVITY_LOGS_STATUS_COMPLETED) {
                $post['finished_date'] = date_ymd();
            }
        }

        if (!$isAdmin && $log['assignee'] !== $assigneeId) {
            $from = $log['assignee'] == Constants::USER_SYSTEM
                ? Constants::USER_SYSTEM_LABEL
                : Constants::USERS[$log['assignee']];

            $to = $assigneeId == Constants::USER_SYSTEM
                ? Constants::USER_SYSTEM_LABEL
                : Constants::USERS[$assigneeId];

            $commentsModel->insert([
                'log_id' => $id,
                'comment' => "Assignee changed from [{$from}] to [{$to}]",
                'commented_by' => Constants::USER_SYSTEM,
                'type' => Constants::COMMENT_TYPE_UPDATE,
            ]);
        }

        if ($log['priority'] !== $post['priority']) {
            $from = Constants::PRIORITIES[$log['priority']];
            $to = Constants::PRIORITIES[$post['priority']];

            $commentsModel->insert([
                'log_id' => $id,
                'comment' => "Priority updated from [{$from}] to [{$to}]",
                'commented_by' => Constants::USER_SYSTEM,
                'type' => Constants::COMMENT_TYPE_UPDATE,
            ]);
        }

        $updateData = [
            'title'           => trim($post['title']),
            'description'     => $post['description'] ?? null,
            'subsidiary_id'   => (int)$post['subsidiary_id'],
            'department_id'   => !empty($post['department_id']) ? (int)$post['department_id'] : null,
            'project_id'      => (int)$post['project_id'],
            'type'            => (int)$post['type'] ?? null,
            'ticket_id'       => !empty($post['ticket_id']) ? $post['ticket_id'] : null,
            'assignee'        => $assigneeId,
            'requested_by'    => $post['requested_by'] ?? null,
            'status'          => (int)$post['status'],
            'size'            => (int)$post['size'] ?? null,
            'priority'        => (int)$post['priority'] ?? null,
            'requested_date'  => !empty($post['requested_date']) ? $post['requested_date'] : null,
            'finished_date'   => !empty($post['finished_date']) ? $post['finished_date'] : null,
            'campaign_status' => !empty($post['campaign_status']) ? (int)$post['campaign_status'] : null,
            'campaign_id'     => $post['campaign_id'] ?? null,
        ];

        $model->update($id, $updateData);

        return redirect()
            ->to('/activity-logs/view/' . $id)
            ->with('success', "Activity log '{$updateData['title']}' updated successfully.");
    }

    public function comment()
    {
        $commentsModel = new ActivityLogCommentsModel();
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

        $insertedId = $commentsModel->insert([
            'log_id' => $post['log_id'],
            'comment' => $post['comment'],
            'commented_by' => $post['commented_by'],
            'type' => Constants::COMMENT_TYPE_COMMENT,
        ]);

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
        $model = new ActivityLogsModel();
        $filesModel = new ActivityLogFilesModel();

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
                        ->to('/activity-logs/view/' . $post['log_id'])
                        ->with('success', "File successfully uploaded.");
                }
            }
        } catch(FileException|\ReflectionException $e) {
            var_dump($e);
        }

        return redirect()
            ->to('/activity-logs/view/' . $post['log_id'])
            ->with('error', "An error occurred. File was not uploaded successfully.");
    }

    public function exportAsCSV()
    {
        helper('custom_functions');
        $logsModel = new ActivityLogsModel();
        
        $meModeGet = $this->request->getGet('me_mode');
        if ($meModeGet !== null) {
            $meMode = ($meModeGet == '1');
        } else {
            $meMode = (session()->get('al_me_mode') == 1);
        }

        $filters = [
            'keyword' => $this->request->getGet('keyword'),
            'subsidiary_id' => $this->request->getGet('subsidiary_id'),
            'project_id' => $this->request->getGet('project_id'),
            'type' => $this->request->getGet('type'),
            'status' => $this->request->getGet('status'),
            'status_group' => $this->request->getGet('status_group'),
            'priority' => $this->request->getGet('priority'),
            'assignee' => $this->request->getGet('assignee'),
            'me_mode' => $meMode ? 1 : 0,
            'sort_by' => $this->request->getGet('sort_by') ?? 'created_at',
            'sort_dir' => $this->request->getGet('sort_dir') ?? 'desc',
        ];
        
        $logs = $logsModel->filterLogs($filters);

        $subsidiary = (!empty($filters['subsidiary_id']))
            ? '(' . Constants::ACTIVITY_LOG_SUBSIDIARIES[$filters['subsidiary_id']] . ')'
            : null;

        $project = (!empty($filters['project_id']))
            ? '(' . Constants::PROJECTS[$filters['project_id']] . ')'
            : null;

        $assignee = (!empty($filters['assignee']))
            ? '(' . Constants::USERS[$filters['assignee']] . ')'
            : null;

        $filename = 'ActivityLogs' . $subsidiary . $project . $assignee . '_' . date('Y-m-d') . '.csv';

        $csv = fopen('php://output', 'w');

        // Headers
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $headers = [
            'Subsidiary',
            'Project Name',
            'Activity Log Type',
            'Assigned To',
            'Title',
            'Description',
            'Status',
            'Size',
            'Ticket ID',
            '(Campaign) Department',
            '(Campaign) Status',
            '(Campaign) ID',
            'Requested By',
            'Requested Date',
            'Finished Date',
            'Created Date',
        ];

        // CSV column headers
        fputcsv($csv, $headers);

        // Rows
        foreach ($logs as $log) {
            $row = [
                Constants::ACTIVITY_LOG_SUBSIDIARIES[$log['subsidiary_id']] ?? 'N/A',
                Constants::PROJECTS[$log['project_id']] ?? 'N/A',
                Constants::ACTIVITY_TYPES[$log['type']] ?? 'N/A',
                Constants::USERS[$log['assignee']] ?? Constants::USER_SYSTEM_LABEL,
                $log['title'],
                esc($log['description']),
                Constants::ACTIVITY_LOGS_STATUS[$log['status']] ?? 'Unknown',
                Constants::ACTIVITY_SIZES[$log['size']] ?? 'Unknown',
                $log['ticket_id'] ?? '-',
                Constants::DWF_DEPARTMENTS[$log['department_id']] ?? '-',
                Constants::CAMPAIGN_STATUS[$log['campaign_status']] ?? '-',
                $log['campaign_id'] ?? '-',
                '\'' . $log['requested_by'] ?? '-',
                '\'' . $log['requested_date'] ?? '-',
                '\'' . $log['finished_date'] ?? '-',
                '\'' . $log['created_at'] ?? '-',
            ];

            fputcsv($csv, $row);
        }

        fclose($csv);
        exit;
    }

    public function updateStatus($id)
    {
        $model = new ActivityLogsModel();
        $commentsModel = new ActivityLogCommentsModel();
        
        $log = $model->find($id);
        if (!$log) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Activity log not found.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $status = (int)$this->request->getPost('status');
        if (!array_key_exists($status, Constants::ACTIVITY_LOGS_STATUS)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid status value.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if ((int)$log['status'] !== $status) {
            $from = Constants::ACTIVITY_LOGS_STATUS[$log['status']] ?? 'Unknown';
            $to = Constants::ACTIVITY_LOGS_STATUS[$status] ?? 'Unknown';

            $insertedId = $commentsModel->insert([
                'log_id' => $id,
                'comment' => "Status updated from [{$from}] to [{$to}]",
                'commented_by' => Constants::USER_SYSTEM,
                'type' => Constants::COMMENT_TYPE_UPDATE,
            ]);

            $updateData = [
                'status' => $status,
            ];

            if ($status == Constants::ACTIVITY_LOGS_STATUS_COMPLETED) {
                $updateData['finished_date'] = date('Y-m-d');
            }

            $model->update($id, $updateData);

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
        $model = new ActivityLogsModel();
        $commentsModel = new ActivityLogCommentsModel();
        
        $log = $model->find($id);
        if (!$log) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Activity log not found.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $assigneeId = (int)$this->request->getPost('assignee');
        if (!array_key_exists($assigneeId, Constants::USERS) && $assigneeId !== Constants::USER_SYSTEM) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid assignee value.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if ((int)$log['assignee'] !== $assigneeId) {
            $from = $log['assignee'] == Constants::USER_SYSTEM
                ? Constants::USER_SYSTEM_LABEL
                : Constants::USERS[$log['assignee']] ?? 'Unknown';

            $to = $assigneeId == Constants::USER_SYSTEM
                ? Constants::USER_SYSTEM_LABEL
                : Constants::USERS[$assigneeId] ?? 'Unknown';

            $insertedId = $commentsModel->insert([
                'log_id' => $id,
                'comment' => "Assignee changed from [{$from}] to [{$to}]",
                'commented_by' => Constants::USER_SYSTEM,
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
        $model = new ActivityLogsModel();
        $commentsModel = new ActivityLogCommentsModel();
        
        $log = $model->find($id);
        if (!$log) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Activity log not found.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $priority = (int)$this->request->getPost('priority');
        if (!array_key_exists($priority, Constants::PRIORITIES)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid priority value.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        if ((int)$log['priority'] !== $priority) {
            $from = Constants::PRIORITIES[$log['priority']] ?? 'Unknown';
            $to = Constants::PRIORITIES[$priority] ?? 'Unknown';

            $insertedId = $commentsModel->insert([
                'log_id' => $id,
                'comment' => "Priority updated from [{$from}] to [{$to}]",
                'commented_by' => Constants::USER_SYSTEM,
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
}
