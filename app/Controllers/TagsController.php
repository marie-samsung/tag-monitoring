<?php

namespace App\Controllers;

use App\Models\Constants;
use App\Models\JiraHistoryModel;
use App\Models\TagsModel;

class TagsController extends BaseController
{
    public function index()
    {
        $tagModel = new TagsModel();

        $filters = [
            'keyword'     => $this->request->getGet('keyword'),
            'status'      => $this->request->getGet('status'),
            'property_id' => $this->request->getGet('property_id'),
        ];
        $data['tags'] = $tagModel->filterTags($filters);
        $data['filters'] = $filters;

        // $data['tags'] = $tagModel->findAll();

        return view('tags/list', $data);
    }

    public function create()
    {
        $model = new TagsModel();

        if ($this->request->getMethod() === 'GET') {
            return view('tags/create', []);
        }

        $post = $this->request->getPost();

        $insertData = [
            'property_id' => (int)$post['property_id'],
            'vendor_id' => (int)$post['vendor_id'],
            'name'       => $post['name'],
            'conditions' => $post['conditions'],
            'status' => $post['status'],
            'al_end_date'   => $post['al_end_date'],
            'ref_link' => $post['ref_link'],
        ];

        $model->insert($insertData);

        return redirect()
            ->to('/tags')
            ->with('success', "Tag {$post['name']} created successfully.");
    }

    public function view($id)
    {
        $tagModel = new TagsModel();
        $tag = $tagModel->find($id);

        $jiraModel = new JiraHistoryModel();

        if (!$tag) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Tag with ID $id not found.");
        }

        return view('tags/view', ['tag' => $tag, 'jiraTickets' => $jiraModel->getRelatedTags($id)]);
    }

    public function update($id)
    {
        $model = new TagsModel();
        $tag = $model->find($id);

        if (!$tag) {
            return redirect()
                ->to('/tags')
                ->with('error', 'Tag not found.');
        }

        if ($this->request->getMethod() === 'GET') {
            return view('tags/update', [
                'tag' => $tag
            ]);
        }

        $post = $this->request->getPost();

        $updateData = [
            'property_id' => (int)$post['property_id'],
            'vendor_id' => (int)$post['vendor_id'],
            'name'        => $post['name'],
            'conditions'  => $post['conditions'],
            'status'      => $post['status'],
            'al_end_date' => $post['al_end_date'],
            'ref_link'    => $post['ref_link'],
        ];

        $model->update($id, $updateData);

        return redirect()
            ->to('/tags')
            ->with('success', "Tag {$post['name']} updated successfully.");
    }

    public function renew()
    {
        $model = new TagsModel();
        $post = $this->request->getGet();
        $tag_ids = $post['tags'] ?? [];
        $ticket_id = $post['ticket_id'] ?? null;

        if (empty($tag_ids)) {
            return redirect()
                ->to('/tags')
                ->with('error', 'No tags selected for renewal.');
        }

        $tag_ids = is_array($tag_ids) ? $tag_ids : explode(',', $tag_ids);

        $data['tags'] = [];
        $data['ticket_id'] = $ticket_id;

        foreach ($tag_ids as $id) {
            $tag = $model->find($id);
            if ($tag) {
                $data['tags'][] = $tag;
            }
        }

        return view('tags/renew', $data);
    }

    public function renewConfirm()
    {
        $post = $this->request->getPost();
        $tags = $post['tags'] ?? [];
        $info = $post['info'] ?? [];

        if (empty($tags)) {
            return redirect()
                ->to('/tags')
                ->with('error', 'No tags data received for renewal.');
        }

        $model = new TagsModel();
        foreach ($tags as $tag) {
            $updated = $model->update(
                $tag['id'], 
                [
                    'al_end_date' => date('Y-m-d', strtotime($tag['next_end_date'])),
                    'status' => Constants::STATUS_PENDING_VERIFICATION,
                ]
            );

            // THIS IS WHERE YOU LEFT OFF, CONTINUE THIS MODIFY THIS IF STATEMENT TO ALSO LOG TO RENEWALLOGS AND JIRAHISTORY
             if ($updated) {
                // Log to JiraHistory
                $jiraHistoryModel = new JiraHistoryModel();
                $jiraHistoryModel->insert([
                    'tag_id' => $tag['id'],
                    'ticket_id' => $info['ticket_id'] ?? null,
                    'action' => 'renewed',
                    'details' => json_encode([
                        'library_name' => $tag['library_name'],
                        'property_id' => $tag['property_id'],
                        'vendor_id' => $tag['vendor_id'],
                        'next_end_date' => $tag['next_end_date'],
                    ]),
                ]);
            } else {
                var_dump("Failed to update tag ID: {$tag['id']}");
            }
        }

        $propertyIds = array_unique(array_column($tags, 'property_id'));
        $vendorIds = array_unique(array_column($tags, 'vendor_id'));

        // add function here to save to RenewalLogs
        // add function here to auto generate the Launch Publishing File on correct path
        // add function here to save to JiraHistory

        return redirect()
            ->to('/tags')
            ->with('success', 'Selected tags have been renewed successfully.');
    }

    public function verify($id)
    {
        $model = new TagsModel();
        $tag = $model->find($id);

        if (!$tag) {
            return redirect()
                ->to('/tags')
                ->with('error', "Tag ID: {$id} not found.");
        }

        $model->update($id, ['status' => Constants::STATUS_ENABLED]);

        return redirect()
            ->to('/tags/view/' . $id)
            ->with('success', 'Tag renewal has been verified.');
    }

    public function downloadRenewalsXls()
    {
        $tags = session()->get('tags_export');

        $properties = (new \App\Models\PropertiesModel())->Options();
        $vendors    = (new \App\Models\VendorsModel())->Options();

        $today = date('d-m-Y');
        $library_name = '3PARTYTAGS_RENEW_' . date('dmy');
        $next_end_date = date('d-m-Y', strtotime('+61 days'));
        $requester = 'Marie Tiomico';
        $event = 'Window Loaded';

        $filename = 'renewals_' . date('Ymd_His') . '.xls';

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        echo "<table border='1'>";
        echo "<tr>
                <th>Request Date</th>
                <th>Library Name</th>
                <th>Site Code</th>
                <th>Rule Name</th>
                <th>Requester</th>
                <th>Event</th>
                <th>Conditions</th>
                <th>Actions</th>
                <th>Extensions</th>
                <th>Expired Date</th>
            </tr>";

        foreach ($tags as $tag) {
            echo "<tr>
                    <td>{$today}</td>
                    <td>{$library_name}</td>
                    <td>{$properties[$tag['property_id']]}</td>
                    <td>{$tag['name']}</td>
                    <td>{$requester}</td>
                    <td>{$event}</td>
                    <td>{$tag['conditions']}</td>
                    <td>{$vendors[$tag['vendor_id']]}</td>
                    <td>N</td>
                    <td>{$next_end_date}</td>
                </tr>";
        }

        echo "</table>";
        exit;
    }
}
