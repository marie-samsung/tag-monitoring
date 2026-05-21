<?php

namespace App\Controllers;

use App\Models\ActivityLogCommentsModel;
use App\Models\ActivityLogsModel;
use App\Models\Constants;
use App\Models\JiraHistoryModel;
use App\Models\OrganizationsModel;
use App\Models\SubsidiariesModel;
use App\Models\TagRequestFilesModel;
use App\Models\TagRequestsModel;
use App\Models\PropertiesModel;
use App\Models\VendorsModel;
use App\Models\TagsModel;
use CodeIgniter\Files\Exceptions\FileException;

class TagRequestsController extends BaseController
{
    public function index()
    {
        $model = new TagRequestsModel();
        $filters = [
            'keyword' => $this->request->getGet('keyword'),
            'subsidiary_id' => $this->request->getGet('subsidiary_id'),
            'property_id' => $this->request->getGet('property_id'),
            'vendor_id' => $this->request->getGet('vendor_id'),
            'type' => $this->request->getGet('type'),
            'status' => $this->request->getGet('status'),
        ];
        $data['requests'] = $model->filterRequests($filters, 25);
        $data['pager'] = $model->pager;
        $data['filters'] = $filters;

        return view('tag-requests/list', $data);
    }

    public function view($id)
    {
        $model = new TagRequestsModel();
        $tagsModel = new TagsModel();
        $filesModel = new TagRequestFilesModel();
        $request = $model->find($id);

        if (!$request) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Tag request with ID $id not found.");
        }

        return view('tag-requests/view', [
            'request' => $request,
            'tags' => $model->getTags($id),
            'files' => $filesModel->getRelatedFiles($id),
        ] );
    }

    public function create()
    {
        $model = new TagRequestsModel();
        $tagsModel = new TagsModel();
        $vendorsModel = new VendorsModel();
        $propertiesModel = new PropertiesModel();
        $organizationsModel = new OrganizationsModel();
        $organizations = $organizationsModel->Options();

        if ($this->request->getMethod() === 'GET') {
            return view('tag-requests/create', []);
        }

        $post = $this->request->getPost();

        $propertyId = $post['property_id'];
        $reqName = $post['requester_name'] ?? null;
        $reqEmail = $post['requester_email'] ?? null;
        $reqOrg = $post['requester_organization'] ?? null;
        $jiraType = $post['type'];
        $endDate = $post['al_end_date'] ?? null;
        $tagIds = [];
        $vendorId = null;
        $organizationId = null;

        if (count($post['tags']) > 0) {
            $name = trim($post['tags'][0]);
            preg_match('/\((.*?)\)/', $name, $matches);
            $vendorName = $matches[1] ?? null;

            $vendor = $vendorsModel->where('name', $vendorName)->first();
            if (!$vendor) {
                $vendorId = $vendorsModel->insert([
                    'name' => $vendorName,
                ]);
            } else {
                $vendorId = $vendor['id'];
            }

            if ($reqOrg) {
                $organizationInput = trim($reqOrg);
                $organization = $organizationsModel->where('id', $organizationInput)->first();
                if (!$organization) {
                    $organizationId = $organizationsModel->insert([
                        'name' => $organizationInput,
                    ]);
                } else {
                    $organizationId = $organization['id'];
                }
            }

            foreach ($post['tags'] as $tag) {
                $match = $tagsModel->where('property_id', $propertyId)
                    ->where('name', $tag)
                    ->first();

                if ($match) {
                    $property = $propertiesModel->find($propertyId);
                    return redirect()
                        ->to('/tag-requests/create')
                        ->with('error', "An existing tag with the name [{$name}] on the selected property [{$property['name']}] was found.");
                }
            }

            foreach ($post['tags'] as $tag) {
                $tagIds[] = $tagsModel->insert([
                    'property_id' => $propertyId,
                    'vendor_id' => $vendorId,
                    'name' => $tag,
                    'status' => $jiraType == Constants::JIRA_TYPE_NEW_TAG ? Constants::STATUS_NEW_TAG_SUBMITTED : Constants::STATUS_NEW_RULE_SUBMITTED,
                    'al_end_date' => $endDate,
                    'ref_link' => 'https://www.samsung.com/',
                    'requester_name' => $reqName,
                    'requester_email' => $reqEmail,
                    'requester_organization' => $organizationId,
                ]);
            }
        }

        if (count($tagIds) > 0) {
            $data = [
                'tags' => implode(',', $tagIds),
                'property_id' => $propertyId,
                'vendor_id' => $vendorId,
                'status' => Constants::STATUS_REQUEST_HQ_REVIEW,
                'type' => $jiraType,
                'requester_name' => $reqName,
                'requester_email' => $reqEmail,
                'requester_organization' => $organizationId,
            ];

            $requestId = $model->insert($data);

            return view('tag-requests/create', [
                'tag_ids' => $tagIds,
                'request_id' => $requestId,
                'vendor_name' => $vendorName,
                'property_id' => $propertyId,
                'vendor_id' => $vendorId,
                'request_type' => $jiraType,
                'ticket_id' => null,
                'al_end_date' => $endDate,
            ]);
        } else {
            return redirect()
                ->to('/tag-requests')
                ->with('error', "Tags were not successfully created."); 
        }
    }

    public function createConfirm()
    {
        $model = new TagRequestsModel();
        $jiraHistoryModel = new JiraHistoryModel();
        $post = $this->request->getPost();

        $ticketId = $post['ticket_id'];
        $id = $post['request_id'];

        if (empty($ticketId)) {
            return redirect()
                ->to('/tag-requests')
                ->with('error', "Ticket was not successfully created.");
        } else {
            $tagRequest = $model->find($id);
            $ticketColumn = 'new_tag_ticket';
            $title = "New Tag Request: ";
            if ($tagRequest['type'] == Constants::JIRA_TYPE_NEW_RULE) {
                $ticketColumn = 'new_rule_ticket';
                $title = "New Rule Request: ";
            }

            $this->addActivityLog($tagRequest, $title, $ticketId);

            if (!$tagRequest) {
                return redirect()
                    ->to('/tag-requests')
                    ->with('error', "Tag request was not found.");
            } else {
                $model->update($id, [
                    $ticketColumn => $ticketId,
                ]);

                $this->saveJiraHistory($tagRequest['tags'], $tagRequest['property_id'], $ticketId, $tagRequest['type']);

                return redirect()
                    ->to('/tag-requests')
                    ->with('success', "[{$ticketId}] Tag Request successfully created.");
            }
        }
    }

    public function addNewTag()
    {
        $model = new TagRequestsModel();
        $post = $this->request->getPost();
        $request = $model->find($post['request_id']);

        if ($model->update($request['id'], [
           'new_tag_ticket' => $post['new_tag_ticket'],
        ])) {

            $this->saveJiraHistory($request['tags'], $request['property_id'], $post['new_tag_ticket'], Constants::JIRA_TYPE_NEW_TAG);

            return redirect()
                ->to('/tag-requests/view/' . $post['request_id'])
                ->with('success', "Tag Request updated successfully: Added NEW TAG ticket");
        }

        return redirect()
            ->to('/tag-requests/view/' . $post['request_id'])
            ->with('error', "Tag Request was not updated.");
    }

    public function addNewRule()
    {
        $model = new TagRequestsModel();
        $tagsModel = new TagsModel();
        $post = $this->request->getPost();
        $ticketId = $post['new_rule_ticket'];
        $request = $model->find($post['request_id']);

        if ($model->update($request['id'], [
            'new_rule_ticket' => $ticketId,
            'status' => Constants::STATUS_REQUEST_NEW_RULE_REQUEST_SUBMITTED,
        ])) {

            $tags = explode(',', $request['tags']);

            foreach ($tags as $tag) {
                $tagsModel->update($tag, [
                    'status' => Constants::STATUS_NEW_RULE_SUBMITTED,
                ]);
            }

            $this->saveJiraHistory($request['tags'], $request['property_id'], $ticketId, Constants::JIRA_TYPE_NEW_RULE);

            return view('tag-requests/create', [
                'request_id' => $request['id'],
                'request_type' => Constants::JIRA_TYPE_NEW_RULE,
                'property_id' => $request['property_id'],
                'vendor_id' => $request['vendor_id'],
                'ticket_id' => $ticketId,
                'al_end_date' => null, // @TODO: Get from tags
                'tag_ids' => $request['tags'],
            ]);

//            return redirect()
//                ->to('/tag-requests/view/' . $post['request_id'])
//                ->with('success', "Tag Request updated successfully: Added NEW RULE ticket");
        }

        return redirect()
            ->to('/tag-requests/view/' . $post['request_id'])
            ->with('error', "Tag Request was not updated.");
    }

    public function requestApproved($id)
    {
        $model = new TagRequestsModel();
        $tagsModel = new TagsModel();
        $activityLogModel = new ActivityLogsModel();
        $commentsModel = new ActivityLogCommentsModel();
        $request = $model->find($id);

        if (!$request) {
            return redirect()
                ->to('/tag-requests')
                ->with('error', "Tag Request with ID: {$id} was not found.");
        }

        $tags = explode(',', $request['tags']);

        if (!$tags) {
            return redirect()
                ->to('/tag-requests')
                ->with('error', "There were no tags associated with this request.");
        }

        $ticketId = $request['new_tag_ticket'] ?? $request['new_rule_ticket'];
        $activityLog = $activityLogModel->where('ticket_id', $ticketId)->first();

        if ($activityLog) {
            if ($activityLog['status'] != Constants::STATUS_COMPLETED) {
                $commentsModel->insert([
                    'log_id' => $activityLog['id'],
                    'comment' => "Status updated from [{$activityLog['status']}] to [". Constants::STATUS_COMPLETED ."]",
                    'commented_by' => Constants::USER_SYSTEM,
                    'type' => Constants::COMMENT_TYPE_UPDATE,
                ]);

                $activityLogModel->update($activityLog['id'], [
                    'status' => Constants::STATUS_COMPLETED,
                ]);
            }
        }

        foreach ($tags as $tag) {
            $tagsModel->update($tag, [
                'status' => Constants::STATUS_ENABLED,
            ]);
        }

        $model->update($id, [
            'status' => Constants::STATUS_REQUEST_PUBLISHED,
        ]);

        return redirect()
            ->to('/tag-requests/view/' . $id)
            ->with('success', "Tag Request approved, and associated tags are now enabled.");
    }

    public function exportAsCSV()
    {
        helper('custom_functions');
        $filters = $this->request->getGet();
        $propertiesModel = new PropertiesModel();
        $subsidiariesModel = new SubsidiariesModel();
        $tagRequestsModel = new TagRequestsModel();
        $requests = $tagRequestsModel->filterRequests($filters);

        $vendorModel = new VendorsModel();
        $vendor = !empty($filters['vendor_id'])
            ? '(' . $vendorModel->find($filters['vendor_id'])['name'] . ')'
            : null;

        $subsidiary = !empty($filters['subsidiary_id'])
            ? '(' . Constants::SUBSIDIARIES[$filters['subsidiary_id']] . ')'
            : null;

        $filename = 'TagRequests' . $subsidiary . $vendor . '_' . date('Ymd') . '.csv';

        $csv = fopen('php://output', 'w');

        // Headers
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // CSV column headers
        fputcsv($csv, [
            'Subsidiary',
            'Property Name',
            'Ticket ID',
            'Ticket Type',
            'Status',
            'Created At',
        ]);

        // Rows
        foreach ($requests as $request) {
            $propertyId = $request['property_id'];
            $property = $propertiesModel->find($propertyId);
            $subsidiary = $subsidiariesModel->find($property['subsidiary_id'] ?? 0);
            $ticketColumn = $request['type'] == Constants::JIRA_TYPE_NEW_RULE ? 'new_rule_ticket' : 'new_tag_ticket';

            fputcsv($csv, [
                $subsidiary['name'] ?? 'N/A',
                $property['name'] ?? 'N/A',
                $request[$ticketColumn] ?? 'N/A',
                Constants::JIRA_TICKET_TYPES[$request['type']] ?? 'N/A',
                Constants::STATUS_REQUESTS[$request['status']] ?? 'Unknown',
                "'" . date_ymd($request['created_at']),
            ]);
        }

        fclose($csv);
        exit;
    }

    public function uploadFile()
    {
        $model = new TagRequestsModel();
        $filesModel = new TagRequestFilesModel();

        $post = $this->request->getPost();
        $requestId = $post['request_id'];
        $file = $this->request->getFile('file_path');

         try {
             if ($file && $file->isValid()) {
                 $upload_path = Constants::NEW_TAG_FILE_WRITE_PATH . $model->fileUploadPath($post['property_id'], $post['vendor_name']);
                 $file_name = $file->getClientName();

                 if ($file->move($upload_path, $file_name)) {
                     $file_path = $upload_path . '/' . $file_name;

                     $filesModel->insert([
                         'file_name' => $file_name,
                         'file_path' => $file_path,
                         'type' => $post['type'],
                         'request_id' => $requestId,
                         'created_by' => 0,
                     ]);

                     return redirect()
                         ->to('/tag-requests/view/' . $post['request_id'])
                         ->with('success', "File successfully uploaded.");
                 }
             }
         } catch(FileException|\ReflectionException $e) {
             var_dump($e);
         }

        return redirect()
            ->to('/tag-requests/view/' . $post['request_id'])
            ->with('error', "An error occurred. File was not uploaded successfully.");
    }

    public function saveJiraHistory($tagIds, $propertyId, $ticketId, $requestType)
    {
        $tags = explode(',', $tagIds);
        $propertiesModel = new PropertiesModel();
        $jiraHistoryModel = new JiraHistoryModel();
        $property = $propertiesModel->find($propertyId);

        foreach ($tags as $tag) {
            $jiraHistoryModel->insert([
                'ticket_id' => $ticketId,
                'type' => $requestType,
                'comments' => $tagIds,
                'tag_id' => $tag,
                'property_id' => $property['id'],
                'subsidiary_id' => $property['subsidiary_id'],
                'created_by' => 0, // @TODO: Replace with actual user ID from session
            ]);
        }
    }

    public function addActivityLog($request, $title, $ticketId)
    {
        $activityLogModel = new ActivityLogsModel();
        $propertiesModel = new PropertiesModel();
        $vendorsModel = new VendorsModel();
        $tagsModel = new TagsModel();
        $vendor = $vendorsModel->find($request['vendor_id']);
        $property = $propertiesModel->find($request['property_id']);
        $description = "<ol><li><b>{$property['name']}</b></li><ul>";
        $tags = explode(',', $request['tags']);

        foreach ($tags as $tag) {
            $tagInfo = $tagsModel->find($tag);
            $description .= '<li>' . $tagInfo['name'] . '</li>';
        }

        $activityLogModel->insert([
            'title' => $title . $vendor['name'],
            'description' => $description . '</ul></ol>',
            'subsidiary_id' => $property['subsidiary_id'],
            'project_id' => Constants::PROJECT_MEDIA_TAGGING,
            'ticket_id' => $ticketId ?? null,
            'assignee' => Constants::USER_SYSTEM,
            'status' => Constants::STATUS_IMPLEMENTATION,
            'type' => Constants::ACTIVITY_TYPE_REQUEST,
            'size' => Constants::ACTIVITY_SIZE_M,
        ]);
    }
}
