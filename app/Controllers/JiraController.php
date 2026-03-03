<?php

namespace App\Controllers;

use App\Models\JiraHistoryModel;
use App\Models\TagsModel;

class JiraController extends BaseController
{
    public function create()
    {
        $model = new JiraHistoryModel();
        
        if ($this->request->getMethod() === 'GET') {
            $tag_id = $this->request->getGet('tag_id');

            if ($tag_id) {
                $data['tag_id'] = $tag_id;
                return view('jira/create', $data);
            }

            return redirect()
                ->to('/tags')
                ->with('errors', 'Tag ID not found.');
        }

        $post = $this->request->getPost();

        $insertData = [

        ];

        $model->insert($insertData);

        return redirect()
            ->to('/tags/view')
            ->with('success', 'Tag created successfully.');
    }
}
