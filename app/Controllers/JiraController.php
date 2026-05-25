<?php

namespace App\Controllers;

use App\Models\JiraHistoryModel;
use App\Models\TagsModel;
use App\Models\PropertiesModel;
use App\Models\SubsidiariesModel;
use App\Models\Constants;

class JiraController extends BaseController
{
    public function index()
    {
        $jiraHistoryModel = new JiraHistoryModel();

        $filters = [
            'keyword' => $this->request->getGet('keyword'),
            'ticket_type' => $this->request->getGet('ticket_type'),
            'property_id' => $this->request->getGet('property_id'),
            'subsidiary_id' => $this->request->getGet('subsidiary_id'),
        ];

        $data['jiraTickets'] = $jiraHistoryModel->filterJiraTickets($filters);
        $data['filters'] = $filters;

        return view('jira/list', $data);
    }

    public function create()
    {
        $model = new JiraHistoryModel();
        $post = $this->request->getPost();

        if ($this->request->getMethod() == 'GET') {
            return view('jira/create', []);
        }

        $model->insert([
            'ticket_id' => $post['ticket_id'],
            'subsidiary_id' => $post['subsidiary_id'],
            'type' => $post['type'],
            'comments' => $post['comments'],
            'created_by' => session()->get('user_id') ?? 0,
        ]);

        return redirect()
            ->to('/jira')
            ->with('success_message', 'JIRA Ticket added successfully.');
    }

    public function addEntry()
    {
        $model = new JiraHistoryModel();
        
        if ($this->request->getMethod() === 'GET') {
            $tag_id = $this->request->getGet('tag_id');

            if ($tag_id) {
                $data['tag_id'] = $tag_id;
                return view('jira/add-entry', $data);
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

        // $file = $this->request->getFile('lp_path');

        // try {
        //     if ($file && $file->isValid()) {
        //         $tag = new TagsModel();
        //         $upload_path = $tag->uploadLPFolderPath($tag_id);
        //         // $uploadPath = WRITEPATH . 'uploads/lp';
        //         $file_name = $file->getClientName();
        //         $file->move(Constants::LP_WRITE_PATH . $upload_path, $file_name);
        //         $lp_path = $upload_path . '/' . $file_name;
        //     }
        // } catch(FileException $e) {
        //     var_dump($e);
        // }

        $insertData = [
            'ticket_id' => $post['ticket_id'],
            'type' => $post['type'],
            'comments' => $post['comments'],
            'tag_id' => $post['tag_id'],
            'created_by' => session()->get('user_id') ?? 0,
        ];

        $model->insert($insertData);

        return redirect()
            ->to('/tags/view/'.$post['tag_id'])
            ->with('success', 'JIRA entry created successfully.');
    }

    public function exportAsCSV()
    {
        $filters = $this->request->getGet();

        $jiraModel = new JiraHistoryModel();
        $tagModel  = new TagsModel();
        $propertiesModel = new PropertiesModel();
        $subsidiaryModel = new SubsidiariesModel();

        $jiraTickets = $jiraModel->filterJiraTickets($filters);

        // CSV filename
        $filename = "JIRA_Tickets_" . date('Ymd') . ".csv";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $csv = fopen('php://output', 'w');

        // CSV headers
        fputcsv($csv, [
            'Subsidiary', 
            'Property', 
            'Ticket ID', 
            'Type', 
            'Comments'
        ]);

        foreach ($jiraTickets as $ticket) {
            $subsidiary = $subsidiaryModel->find($ticket['subsidiary_id']);
            $property   = $propertiesModel->find($ticket['property_id']);

            $comments = $ticket['comments'];

            if ($ticket['type'] == Constants::JIRA_TYPE_DATE_EXTENSION_RULE) {
                $tagIds = explode(',', $ticket['comments']);
                $names = [];

                foreach ($tagIds as $id) {
                    $tag = $tagModel->find($id);
                    if ($tag) {
                        $names[] = $tag['name'];
                    }
                }

                $comments = implode(', ', $names);
            }

            fputcsv($csv, [
                $subsidiary['name'] ?? 'N/A',
                $property['name'] ?? 'N/A',
                $ticket['ticket_id'],
                Constants::JIRA_TICKET_TYPES[$ticket['type']],
                $comments,
            ]);
        }

        fclose($csv);
        exit;
    }

    // public function download()
    // {
    //     $file_path = $this->request->getGet('file_path');
    //     $file_path = urldecode($file_path);

    //     if (!file_exists($file_path)) {
    //         return redirect()
    //             ->back()
    //             ->with('errors', "{$file_path} not found.");
    //     }

    //     return $this->response->download($file_path, null)->setFileName(basename($file_path));
    // }
}
