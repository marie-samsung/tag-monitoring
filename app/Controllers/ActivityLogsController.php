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
        $data = $model->findAll();
        return view('activity-logs/list', ['logs' => $data]);
    }

    public function create()
    {
        $model = new ActivityLogsModel();

        if ($this->request->getMethod() === 'GET') {
            return view('activity-logs/create', []);
        }

        $post = $this->request->getPost();

        $insertData = [
            'title'           => trim($post['title']),
            'description'     => $post['description'] ?? null,
            'subsidiary_id'   => (int)$post['subsidiary_id'],
            'department_id'   => !empty($post['department_id']) ? (int)$post['department_id'] : null,
            'project_id'      => (int)$post['project_id'],
            'type'            => (int)$post['type'] ?? null,
            'ticket_id'       => !empty($post['ticket_id']) ? $post['ticket_id'] : null,
            'assignee'        => (int)$post['assignee'],
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
        $model = new ActivityLogsModel();
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

        $updateData = [
            'title'           => trim($post['title']),
            'description'     => $post['description'] ?? null,
            'subsidiary_id'   => (int)$post['subsidiary_id'],
            'department_id'   => !empty($post['department_id']) ? (int)$post['department_id'] : null,
            'project_id'      => (int)$post['project_id'],
            'type'            => (int)$post['type'] ?? null,
            'ticket_id'       => !empty($post['ticket_id']) ? $post['ticket_id'] : null,
            'assignee'        => (int)$post['assignee'],
            'requested_by'    => $post['requested_by'] ?? null,
            'status'          => (int)$post['status'],
            'size'            => (int)$post['size'] ?? null,
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
            return redirect()->back()->with('error', 'No comment data provided.');
        }

        $commentsModel->insert([
            'log_id' => $post['log_id'],
            'comment' => $post['comment'],
            'commented_by' => $post['commented_by'],
        ]);

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
}
