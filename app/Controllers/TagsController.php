<?php

namespace App\Controllers;

use App\Models\Constants;
use App\Models\JiraHistoryModel;
use App\Models\PropertiesModel;
use App\Models\RenewalLogsModel;
use App\Models\TagsModel;
use App\Models\VendorsModel;

class TagsController extends BaseController
{
    public function index()
    {
        $tagModel = new TagsModel();

        $filters = [
            'keyword'     => $this->request->getGet('keyword'),
            'status'      => $this->request->getGet('status'),
            'property_id' => $this->request->getGet('property_id'),
            'subsidiary_id' => $this->request->getGet('subsidiary_id'),
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
                    'requested_date' => date('Y-m-d', strtotime($tag['next_end_date'])),
                    'status' => Constants::STATUS_PENDING_VERIFICATION,
                ]
            );

            if ($updated) {
                $vendorModel = new VendorsModel();
                $vendor = $vendorModel->find($tag['vendor_id']);
                $this->saveJiraHistory($tag['id'], $info['ticket_id'], $vendor['name'] ?? 'Unknown Vendor');
            } else {
                var_dump("Failed to update tag ID: {$tag['id']}");
            }
        }

        $propertyIds = array_unique(array_column($tags, 'property_id'));
        $vendorIds = array_unique(array_column($tags, 'vendor_id'));

        $this->saveRenewalLogs($propertyIds, $vendorIds, $info);

        return redirect()
            ->to('/tags')
            ->with('success', 'Selected tags have been renewed successfully.');
    }

    public function verify($id)
    {
        $model = new TagsModel();
        $tag = $model->find($id);
        $requestedDate = $tag['requested_date'];

        if (!$tag) {
            return redirect()
                ->to('/tags')
                ->with('error', "Tag ID: {$id} not found.");
        }

        $model->update($id, [
            'status' => Constants::STATUS_ENABLED,
            'al_end_date' => $requestedDate,
            'requested_date' => null,
        ]);

        return redirect()
            ->to('/tags/view/' . $id)
            ->with('success', 'Tag renewal has been verified.');
    }

    public function saveJiraHistory($tagId, $ticketId, $vendorId)
    {
        $jiraHistoryModel = new JiraHistoryModel();
        if ($jiraHistoryModel->insert([
            'tag_id' => $tagId,
            'ticket_id' => $ticketId,
            'type' => Constants::JIRA_TYPE_DATE_EXTENSION_RULE,
            'comments' => 'Vendor: ' . $vendorId,
        ]) == false) {
            var_dump("Failed to save JIRA history for tag ID: {$tagId}");
        }
    }
 
    public function saveRenewalLogs($propertyIds, $vendorIds, $info)
    {
        $vendorModel = new VendorsModel();
        $vendorList = $vendorModel->Options();

        $log = new RenewalLogsModel();

        $vendors = [];

        foreach ($vendorIds as $vendorId) {
            $vendors[] = $vendorList[(int)$vendorId];
        }

        foreach ($propertyIds as $propertyId) {
            $log->insert([
                'property_id' => $propertyId,
                'vendor' => implode(', ', $vendors),
                'ticket_id' => $info['ticket_id'] ?? 'N/A',
                'al_end_date' => date('Y-m-d', strtotime($info['al_end_date'] ?? null)),
                'library_name' => $info['library_name'] ?? null,
            ]) or var_dump("Failed to save renewal log for property ID: {$propertyId}");
        }
    }
}
