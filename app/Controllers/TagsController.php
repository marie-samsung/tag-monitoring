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
            'name'       => $post['name'],
            'conditions' => $post['conditions'],
            'status' => $post['status'],
            'al_end_date'   => $post['al_end_date'],
            'ref_link' => $post['ref_link'],
        ];

        $model->insert($insertData);

        return redirect()
            ->to('/tags')
            ->with('success', 'Tag created successfully.');
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

        if ($this->request->getMethod() === 'GET') {
            return view('tags/create', []);
        }

        $post = $this->request->getPost();

        $insertData = [
            'property_id' => (int)$post['property_id'],
            'name'       => $post['name'],
            'conditions' => $post['conditions'],
            'status' => $post['status'],
            'al_end_date'   => $post['al_end_date'],
            'ref_link' => $post['ref_link'],
        ];

        $model->insert($insertData);

        return redirect()
            ->to('/tags')
            ->with('success', 'Tag created successfully.');
    }
}
