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
        $file = $this->request->getFile('lp_path');

        var_dump($post);
        var_dump($file);
        die();

        $insertData = [
            'ticket_id' => $post['ticket_id'],
            'type' => $post['type'],
            'tag_id' => $post['tag_id'],
            'lp_path' => $this->request->getFile('lp_path')->getName(),
            'created_by' => 0, // @TODO: Replace with actual user ID from session
        ];

        $model->insert($insertData);

        return redirect()
            ->to('/tags/view')
            ->with('success', 'Tag created successfully.');
    }
}
