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
            'created_at_from' => $this->request->getGet('created_at_from'),
            'created_at_to' => $this->request->getGet('created_at_to'),
            'sort_by' => $this->request->getGet('sort_by'),
            'sort_dir' => $this->request->getGet('sort_dir'),
        ];

        $data['jiraTickets'] = $jiraHistoryModel->filterJiraTickets($filters, 25);
        $data['pager'] = $jiraHistoryModel->pager;
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

    public function updateTags($id)
    {
        $jiraModel = new JiraHistoryModel();
        $ticket = $jiraModel->find($id);

        if (!$ticket) {
            return redirect()
                ->to('/jira')
                ->with('error', 'JIRA Ticket not found.');
        }

        $tagModel = new TagsModel();
        $propertiesModel = new PropertiesModel();
        $subsidiaryModel = new SubsidiariesModel();

        // Find the subsidiary info
        $subsidiary = $subsidiaryModel->find($ticket['subsidiary_id']);

        // Load all tags belonging to the same subsidiary (grouped by property)
        $tagsQuery = $tagModel->select('tags.*, properties.name as property_name')
            ->join('properties', 'properties.id = tags.property_id');
        if ($ticket['subsidiary_id']) {
            $tagsQuery->where('properties.subsidiary_id', $ticket['subsidiary_id']);
        }
        $availableTags = $tagsQuery->orderBy('properties.name', 'ASC')
            ->orderBy('tags.name', 'ASC')
            ->findAll();

        // Get currently related tags
        $currentTagIds = [];
        if (!empty($ticket['comments'])) {
            $rawIds = explode(',', $ticket['comments']);
            foreach ($rawIds as $rawId) {
                $rawId = trim($rawId);
                if (is_numeric($rawId)) {
                    $currentTagIds[] = (int)$rawId;
                }
            }
        }
        // Also include the main tag_id if set
        if (!empty($ticket['tag_id']) && !in_array((int)$ticket['tag_id'], $currentTagIds)) {
            $currentTagIds[] = (int)$ticket['tag_id'];
        }

        // Group available tags by property name for grouping in the select dropdown
        $groupedTags = [];
        foreach ($availableTags as $t) {
            $groupedTags[$t['property_name']][] = $t;
        }

        return view('jira/update_tags', [
            'ticket' => $ticket,
            'subsidiary' => $subsidiary,
            'groupedTags' => $groupedTags,
            'currentTagIds' => $currentTagIds,
        ]);
    }

    public function updateTagsConfirm($id)
    {
        $jiraModel = new JiraHistoryModel();
        $ticket = $jiraModel->find($id);

        if (!$ticket) {
            return redirect()
                ->to('/jira')
                ->with('error', 'JIRA Ticket not found.');
        }

        $tagIds = $this->request->getPost('tag_ids') ?? [];
        // Ensure they are numeric IDs
        $tagIds = array_filter(array_map('intval', $tagIds));

        // Format as comma-separated list of tag IDs
        $comments = implode(',', $tagIds);

        // First tag ID or null if none
        $tagId = !empty($tagIds) ? $tagIds[0] : null;

        $updateData = [
            'comments' => $comments,
            'tag_id' => $tagId,
            'updated_by' => session()->get('user_id') ?? 0,
        ];

        // Also, if the ticket type is not one of the tag-related types (e.g. date extension, new tag, rule request, update request),
        // we might want to check if the ticket type should be updated/converted to an update request (Constants::JIRA_TYPE_UPDATE_RULE).
        // Let's keep the type unchanged unless it is Master Approval/Other and now gets related tags.
        // But keeping it unchanged is safer unless user expects it to change.
        if (empty($ticket['type']) || !in_array($ticket['type'], [
            Constants::JIRA_TYPE_DATE_EXTENSION_RULE,
            Constants::JIRA_TYPE_NEW_TAG,
            Constants::JIRA_TYPE_NEW_RULE,
            Constants::JIRA_TYPE_UPDATE_RULE
        ])) {
            // If it was another type, change its type to Update Rule to render properly on lists
            if (!empty($tagIds)) {
                $updateData['type'] = Constants::JIRA_TYPE_UPDATE_RULE;
            }
        }

        if ($jiraModel->update($id, $updateData)) {
            return redirect()
                ->to('/jira')
                ->with('success', 'Related tags updated successfully for ticket ' . esc($ticket['ticket_id']) . '.');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Failed to update related tags.');
        }
    }
}
