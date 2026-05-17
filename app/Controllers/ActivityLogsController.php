<?php

namespace App\Controllers;

use App\Models\ActivityLogsModel;

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
        $log = $model->find($id);

        if (!$log) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Activity log with ID $id not found.");
        }

        return view('activity-logs/view', ['log' => $log]);
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
            ->to('/activity-logs')
            ->with('success', "Activity log '{$updateData['title']}' updated successfully.");
    }
}
