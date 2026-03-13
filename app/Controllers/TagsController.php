<?php

namespace App\Controllers;

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
            // 'vendor_id' => (int)$post['vendor_id'],
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
            // 'vendor_id' => (int)$post['vendor_id'],
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

        $post = $this->request->getPost();
        $tagIds = $post['tag_ids'] ?? [];

        if (empty($tagIds)) {
            return redirect()
                ->to('/tags')
                ->with('error', 'No tags selected for renewal.');
        }

        foreach ($tagIds as $id) {
            $tag = $model->find($id);
            if ($tag) {
                // Example renewal logic: extend end date by 1 year
                $newEndDate = date('Y-m-d', strtotime('+1 year', strtotime($tag['al_end_date'])));
                $model->update($id, ['al_end_date' => $newEndDate]);
            }
        }

        return redirect()
            ->to('/tags')
            ->with('success', 'Selected tags renewed successfully.');
    }
}
