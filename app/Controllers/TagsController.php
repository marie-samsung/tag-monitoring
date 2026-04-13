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
        $vendorsModel = new VendorsModel();

        if ($this->request->getMethod() === 'GET') {
            return view('tags/create', []);
        }

        $post = $this->request->getPost();

        $propertyId = (int)$post['property_id'];
        $name = trim($post['name']);

        preg_match('/\((.*?)\)/', $name, $matches);
        $vendorName = $matches[1] ?? null;

        $existing = $model
            ->where('property_id', $propertyId)
            ->where('name', $name)
            ->first();

        if ($existing) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Tag '{$name}' already exists for this property.");
        }

        $vendor = $vendorsModel->where('name', $vendorName)->first();

        if (!$vendor) {
            $vendorId = $vendorsModel->insert([
                'name' => $vendorName,
            ]);
        } else {
            $vendorId = $vendor['id'];
        }


        $insertTagData = [
            'property_id' => $propertyId,
            'vendor_id' => $vendorId,
            'name'       => $name,
            'conditions' => $post['conditions'],
            'status' => $post['status'],
            'al_end_date'   => $post['al_end_date'],
            'ref_link' => $post['ref_link'],
            'requester_name' => $post['requester_name'],
            'requester_email' => $post['requester_email'],
            'requester_organization' => $post['requester_organization'],
        ];

        $model->insert($insertTagData);

        return redirect()
            ->to('/tags')
            ->with('success', "Tag {$name} created successfully.");
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
            'requester_name' => $post['requester_name'],
            'requester_email' => $post['requester_email'],
            'requester_organization' => $post['requester_organization'],
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

        $propertyIds = array_unique(array_column($tags, 'property_id'));
        $tagIds = array_unique(array_column($tags, 'id'));

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
                $this->saveJiraHistory($tag['id'], $info['ticket_id'], $tagIds);
            } else {
                var_dump("Failed to update tag ID: {$tag['id']}");
            }
        }

        $this->saveRenewalLogs($propertyIds, $tagIds, $info);

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

    public function updateAL()
    {
        $tagId = $this->request->getPost('tag_id');

        $model = new TagsModel();
        $model->update($tagId, [
            'status' => Constants::STATUS_UPDATED_IN_AL,
        ]);

        return $this->response->setJSON(['status' => 'ok']);
    }


    public function saveJiraHistory($tagId, $ticketId, $tagIds)
    {
        $jiraHistoryModel = new JiraHistoryModel();
        if ($jiraHistoryModel->insert([
            'tag_id' => $tagId,
            'ticket_id' => $ticketId,
            'type' => Constants::JIRA_TYPE_DATE_EXTENSION_RULE,
            'comments' => implode(',', $tagIds),
        ]) == false) {
            var_dump("Failed to save JIRA history for tag ID: {$tagId}");
        }
    }
 
    public function saveRenewalLogs($propertyIds, $tagIds, $info)
    {
        $log = new RenewalLogsModel();

        foreach ($propertyIds as $propertyId) {
            $log->insert([
                'property_id' => $propertyId,
                'vendor' => implode(',', $tagIds),
                'ticket_id' => $info['ticket_id'] ?? 'N/A',
                'al_end_date' => date('Y-m-d', strtotime($info['al_end_date'] ?? null)),
                'library_name' => $info['library_name'] ?? null,
            ]) or var_dump("Failed to save renewal log for property ID: {$propertyId}");
        }
    }
}
