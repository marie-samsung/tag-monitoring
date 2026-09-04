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

    public function exportAsExcel()
    {
        $filters = $this->request->getGet();

        $jiraModel = new JiraHistoryModel();
        $tagModel  = new TagsModel();
        $propertiesModel = new PropertiesModel();
        $subsidiaryModel = new SubsidiariesModel();

        $jiraTickets = $jiraModel->filterJiraTickets($filters);

        // CSV filename
        $filename = "JIRA_Tickets_" . date('Ymd') . ".xls";

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="UTF-8"></head>';
        echo '<body><table border="1">';

        // CSV headers
        $headers = [ 'Subsidiary', 'Property', 'Ticket ID', 'Type', 'Comments' ];
        
        echo '<tr>';
        foreach ($headers as $header) {
            echo '<th style="background-color:#f2f2f2;">' . esc($header) . '</th>';
        }
        echo '</tr>';

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

            echo '<tr>';
            echo '<td>' . esc($subsidiary['name'] ?? 'N/A') . '</td>';
            echo '<td>' . esc($property['name'] ?? 'N/A') . '</td>';
            echo '<td>' . esc($ticket['ticket_id']) . '</td>';
            echo '<td>' . esc(Constants::JIRA_TICKET_TYPES[$ticket['type']]) . '</td>';
            echo '<td>' . esc($comments) . '</td>';
            echo '</tr>';
        }

        echo '</table></body></html>';
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

        // Find the subsidiary info and all subsidiaries options
        $subsidiary = $subsidiaryModel->find($ticket['subsidiary_id']);
        $subsidiaries = $subsidiaryModel->Options() ?: Constants::SUBSIDIARIES;

        // Load all tags with property name and subsidiary_id (grouped by property)
        $tagsQuery = $tagModel->select('tags.*, properties.name as property_name, properties.subsidiary_id')
            ->join('properties', 'properties.id = tags.property_id', 'left');
        $availableTags = $tagsQuery->orderBy('properties.name', 'ASC')
            ->orderBy('tags.name', 'ASC')
            ->findAll();

        // Get currently related tags by collating from all matching jira_history rows for this ticket_id
        $currentTagIds = [];
        if (!empty($ticket['ticket_id'])) {
            $jiraTickets = $jiraModel->where('ticket_id', $ticket['ticket_id'])->findAll();
            foreach ($jiraTickets as $jt) {
                if (!empty($jt['tag_id'])) {
                    $currentTagIds[] = (int)$jt['tag_id'];
                }
                if (!empty($jt['comments'])) {
                    $rawIds = explode(',', $jt['comments']);
                    foreach ($rawIds as $rawId) {
                        $rawId = trim($rawId);
                        if (is_numeric($rawId)) {
                            $currentTagIds[] = (int)$rawId;
                        }
                    }
                }
            }
            $currentTagIds = array_unique($currentTagIds);
        }

        // Group available tags by property name for grouping in the select dropdown
        $groupedTags = [];
        foreach ($availableTags as $t) {
            $groupedTags[$t['property_name'] ?? 'Other'][] = $t;
        }

        return view('jira/update_tags', [
            'ticket' => $ticket,
            'subsidiary' => $subsidiary,
            'subsidiaries' => $subsidiaries,
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

        $ticketId = trim($this->request->getPost('ticket_id') ?? $ticket['ticket_id']);
        $subsidiaryId = $this->request->getPost('subsidiary_id');
        $type = $this->request->getPost('type');

        $updateData = [
            'comments' => $comments,
            'tag_id' => $tagId,
            'updated_by' => session()->get('user_id') ?? 0,
        ];

        if (!empty($ticketId)) {
            $updateData['ticket_id'] = $ticketId;
        }
        if (!empty($subsidiaryId)) {
            $updateData['subsidiary_id'] = (int)$subsidiaryId;
        }
        if (!empty($type)) {
            $updateData['type'] = (int)$type;
        }

        $oldTicketId = $ticket['ticket_id'];
        $success = false;
        if (!empty($oldTicketId)) {
            $success = $jiraModel->where('ticket_id', $oldTicketId)->set($updateData)->update();
        } else {
            $success = $jiraModel->update($id, $updateData);
        }

        if ($success) {
            return redirect()
                ->to('/jira')
                ->with('success', 'Ticket information and related tags updated successfully for ' . esc($ticketId ?: $oldTicketId) . '.');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Failed to update ticket information.');
        }
    }
}
