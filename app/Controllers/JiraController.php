<?php

namespace App\Controllers;

use App\Models\JiraHistoryModel;
use App\Models\TagsModel;
use App\Models\Constants;
use CodeIgniter\Files\Exceptions\FileException;

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
                ->with('errors', "Tag ID not found.");
        }

        $post = $this->request->getPost();
        $tag_id = $post['tag_id'];

        if ($tag_id == null) {
            return redirect()
                ->to('/tags')
                ->with('errors', "Tag ID not found.");
        }

        $file = $this->request->getFile('lp_path');

        try {
            if ($file && $file->isValid()) {
                $tag = new TagsModel();
                $upload_path = $tag->uploadLPFolderPath($tag_id);
                // $uploadPath = WRITEPATH . 'uploads/lp';
                $file_name = $file->getClientName();
                $file->move(Constants::LP_WRITE_PATH . $upload_path, $file_name);
                $lp_path = $upload_path . '/' . $file_name;
            }
        } catch(FileException $e) {
            var_dump($e);
        }

        $insertData = [
            'ticket_id' => $post['ticket_id'],
            'type' => $post['type'],
            'comments' => $post['comments'],
            'tag_id' => $post['tag_id'],
            'lp_path' => $lp_path ?? null,
            'created_by' => 0, // @TODO: Replace with actual user ID from session
        ];

        $model->insert($insertData);

        return redirect()
            ->to('/tags/view/'.$post['tag_id'])
            ->with('success', 'JIRA entry created successfully.');
    }

    public function download()
    {
        $file_path = $this->request->getGet('file_path');
        $file_path = urldecode($file_path);

        if (!file_exists($file_path)) {
            return redirect()
                ->back()
                ->with('errors', "{$file_path} not found.");
        }

        return $this->response->download($file_path, null)->setFileName(basename($file_path));
    }
}
