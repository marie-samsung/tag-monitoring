<?php

namespace App\Controllers;

use App\Models\TaskCommentsModel;
use App\Models\TasksModel;
use App\Models\Constants;
use App\Models\JiraHistoryModel;
use App\Models\PropertiesModel;
use App\Models\RenewalLogsModel;
use App\Models\SubsidiariesModel;
use App\Models\TagsModel;
use App\Models\VendorsModel;
use App\Models\OrganizationsModel;
use ReflectionException;

class TagsController extends BaseController
{
    public function index()
    {
        $tagModel = new TagsModel();

        $status = $this->request->getGet('status');

        if ($status === 'all') {
            $status = null; // null triggers filterTags to ignore status and fetch everything
        } elseif ($status == Constants::STATUS_DEFAULT || empty($status)) {
            $status = [1, 4, 5, 9]; // Default to show Enabled, Pending Verification, and Updated in AL
        }

        if ($status == Constants::STATUS_ALL) {
            $status = null;
        }

        $filters = [
            'keyword' => $this->request->getGet('keyword'),
            'conditions' => $this->request->getGet('conditions'),
            'status' => $status,
            'property_id' => $this->request->getGet('property_id'),
            'subsidiary_id' => $this->request->getGet('subsidiary_id'),
            'vendor_id' => $this->request->getGet('vendor_id'),
            'al_end_date_from' => $this->request->getGet('al_end_date_from'),
            'al_end_date_to' => $this->request->getGet('al_end_date_to'),
            'sort_by' => $this->request->getGet('sort_by'),
            'sort_dir' => $this->request->getGet('sort_dir'),
        ];
        $data['tags'] = $tagModel->filterTags($filters, 25);
        $data['pager'] = $tagModel->pager;

        if (is_array($status)) {
            $filters['status'] = 145;
        }
        $data['filters'] = $filters;

        return view('tags/list', $data);
    }

    public function repository()
    {
        $tagModel = new TagsModel();

        $status = $this->request->getGet('status');

        if ($status === 'all' || $status == Constants::STATUS_ALL) {
            $status = null;
        }

        $filters = [
            'keyword' => $this->request->getGet('keyword'),
            'js_code' => $this->request->getGet('js_code'),
            'conditions' => $this->request->getGet('conditions'),
            'status' => $status,
            'property_id' => $this->request->getGet('property_id'),
            'subsidiary_id' => $this->request->getGet('subsidiary_id'),
            'vendor_id' => $this->request->getGet('vendor_id'),
            'sort_by' => $this->request->getGet('sort_by'),
            'sort_dir' => $this->request->getGet('sort_dir'),
            'is_repository' => true,
        ];
        $data['tags'] = $tagModel->filterTags($filters, 25);
        $data['pager'] = $tagModel->pager;

        if (is_array($status)) {
            $filters['status'] = 145;
        }
        $data['filters'] = $filters;

        return view('tags/repository', $data);
    }

    public function create()
    {
        $model = new TagsModel();
        $vendorsModel = new VendorsModel();
        $organizationsModel = new OrganizationsModel();

        if ($this->request->getMethod() === 'GET') {
            return view('tags/create', []);
        }

        $post = $this->request->getPost();
        $reqName = $post['requester_name'] ?? null;
        $reqEmail = $post['requester_email'] ?? null;
        $reqOrg = $post['requester_organization'] ?? null;
        $organizationId = null;

        $propertyId = (int) $post['property_id'];
        $name = trim($post['name']);

        preg_match('/\((.*?)\)/', $name, $matches);
        $vendorName = !empty($matches[1]) ? trim($matches[1]) : 'Unknown';

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

        if ($reqOrg) {
            $organizationInput = trim($reqOrg);
            $organization = $organizationsModel->where('name', $organizationInput)->first();
            if (!$organization) {
                // If not found by name, it could be a numeric ID passed from old select, let's check
                $organizationById = is_numeric($organizationInput) ? $organizationsModel->where('id', $organizationInput)->first() : null;
                if ($organizationById) {
                    $organizationId = $organizationById['id'];
                } else {
                    $organizationId = $organizationsModel->insert([
                        'name' => $organizationInput,
                    ]);
                }
            } else {
                $organizationId = $organization['id'];
            }
        }


        $insertTagData = [
            'property_id' => $propertyId,
            'vendor_id' => $vendorId,
            'name' => $name,
            'conditions' => $post['conditions'],
            'status' => $post['status'],
            'al_end_date' => $post['al_end_date'],
            'ref_link' => $post['ref_link'],
            'requester_name' => $reqName,
            'requester_email' => $reqEmail,
            'requester_organization' => $organizationId,
            'requested_date' => !empty($post['requested_date']) ? $post['requested_date'] : null,
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
            $aiModels = $this->fetchAiModels();
            return view('tags/update', [
                'tag' => $tag,
                'aiModels' => $aiModels
            ]);
        }

        $post = $this->request->getPost();

        $name = trim($post['name']);
        preg_match('/\((.*?)\)/', $name, $matches);
        $vendorName = !empty($matches[1]) ? trim($matches[1]) : 'Unknown';

        $vendorsModel = new VendorsModel();
        $vendor = $vendorsModel->where('name', $vendorName)->first();
        if (!$vendor) {
            $vendorId = $vendorsModel->insert([
                'name' => $vendorName,
            ]);
        } else {
            $vendorId = $vendor['id'];
        }

        $reqOrg = $post['requester_organization'] ?? null;
        $organizationId = null;
        if ($reqOrg) {
            $organizationsModel = new \App\Models\OrganizationsModel();
            $organizationInput = trim($reqOrg);
            $organization = $organizationsModel->where('name', $organizationInput)->first();
            if (!$organization) {
                $organizationById = is_numeric($organizationInput) ? $organizationsModel->where('id', $organizationInput)->first() : null;
                if ($organizationById) {
                    $organizationId = $organizationById['id'];
                } else {
                    $organizationId = $organizationsModel->insert([
                        'name' => $organizationInput,
                    ]);
                }
            } else {
                $organizationId = $organization['id'];
            }
        }

        if ($tag['status'] !== $post['status']) {
            $activityLogsModel = new TasksModel();
            $jiraHistoryModel = new JiraHistoryModel();
            $ticketId = $jiraHistoryModel->getRelatedTagDateExtension($tag['id'])['ticket_id'] ?? null;
            $activityLog = $activityLogsModel->getRelatedTagRenewal($ticketId) ?? null;

            if ($tag['status'] == Constants::STATUS_NEW_TAG_SUBMITTED || $tag['status'] == Constants::STATUS_NEW_RULE_SUBMITTED) {
                $ticketId = $jiraHistoryModel->getRelatedTagRequest($tag['id'])['ticket_id'] ?? null;
                $activityLog = $activityLogsModel->getRelatedTagRequest($ticketId) ?? null;
            }

            if ($activityLog) {
                $from = Constants::ACTIVITY_LOGS_STATUS[$activityLog['status']];
                $commentsModel = new TaskCommentsModel();

                if ($post['status'] == Constants::STATUS_PENDING_VERIFICATION) {
                    if ($activityLog['status'] != Constants::ACTIVITY_LOGS_STATUS_REVIEW) {
                        $to = Constants::ACTIVITY_LOGS_STATUS[Constants::ACTIVITY_LOGS_STATUS_REVIEW];
                        $commentsModel->insert([
                            'log_id' => $activityLog['id'],
                            'comment' => "Status updated from [{$from}] to [{$to}]",
                            'commented_by' => session()->get('user_id'),
                            'type' => Constants::COMMENT_TYPE_UPDATE,
                        ]);

                        $activityLogsModel->update($activityLog['id'], [
                            'status' => Constants::ACTIVITY_LOGS_STATUS_REVIEW,
                        ]);

                        $tasksController = new \App\Controllers\TasksController();
                        $tasksController->updateJiraStatusFromTask($activityLog['id'], $activityLog['status'], Constants::ACTIVITY_LOGS_STATUS_REVIEW);
                    }
                }

                if ($post['status'] == Constants::STATUS_ON_HOLD) {
                    if ($activityLog['status'] != Constants::ACTIVITY_LOGS_STATUS_BLOCKED) {
                        $to = Constants::ACTIVITY_LOGS_STATUS[Constants::ACTIVITY_LOGS_STATUS_BLOCKED];
                        $commentsModel->insert([
                            'log_id' => $activityLog['id'],
                            'comment' => "Status updated from [{$from}] to [{$to}]",
                            'commented_by' => session()->get('user_id'),
                            'type' => Constants::COMMENT_TYPE_UPDATE,
                        ]);
                        $activityLogsModel->update($activityLog['id'], [
                            'status' => Constants::ACTIVITY_LOGS_STATUS_BLOCKED,
                        ]);

                        $tasksController = new \App\Controllers\TasksController();
                        $tasksController->updateJiraStatusFromTask($activityLog['id'], $activityLog['status'], Constants::ACTIVITY_LOGS_STATUS_BLOCKED);
                    }
                }
            }
        }

        $updateData = [
            'property_id' => (int) $post['property_id'],
            'vendor_id' => (int) $vendorId,
            'name' => $name,
            'conditions' => $post['conditions'],
            'status' => $post['status'],
            'al_end_date' => $post['al_end_date'],
            'ref_link' => $post['ref_link'],
            'requester_name' => $post['requester_name'],
            'requester_email' => $post['requester_email'],
            'requester_organization' => $organizationId,
            'requested_date' => !empty($post['requested_date']) ? $post['requested_date'] : null,
        ];

        $model->update($id, $updateData);

        return redirect()
            ->to('/tags/view/' . $id)
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

        $justificationModel = new \App\Models\RenewalJustificationModel();

        foreach ($tag_ids as $id) {
            $tag = $model->find($id);
            if ($tag) {
                $tag['justification'] = $justificationModel->where('tag_id', $id)->orderBy('id', 'DESC')->first();
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
                $this->saveJiraHistory($tag, $info['ticket_id'], $tagIds);
            } else {
                var_dump("Failed to update tag ID: {$tag['id']}");
            }
        }

        $this->saveRenewalLogs($propertyIds, $tagIds, $info);
        $this->addTask($propertyIds[0], $tagIds, $info);

        return redirect()
            ->to('/tags')
            ->with('success', 'Selected tags have been renewed successfully.');
    }

    /**
     * On tag/view - this is to verify the pending verification tags that are recently renewed
     * @param $id The tag ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     * @throws ReflectionException
     */
    public function verify($id)
    {
        $model = new TagsModel();
        $tag = $model->find($id);
        $jiraModel = new JiraHistoryModel();
        $activityLogModel = new TasksModel();
        $commentsModel = new TaskCommentsModel();
        $requestedDate = $tag['requested_date'] ?? $tag['al_end_date'];

        if (!$tag) {
            return redirect()
                ->to('/tags')
                ->with('error', "Tag ID: {$id} not found.");
        }

        $jiraTicket = $jiraModel->getRelatedTags($id);
        $activityLog = !empty($jiraTicket) && !empty($jiraTicket[0]['ticket_id']) ? $activityLogModel->where('ticket_id', $jiraTicket[0]['ticket_id'])->first() : null;

        if ($activityLog) {
            if ($activityLog['status'] != Constants::ACTIVITY_LOGS_STATUS_COMPLETED) {
                $from = Constants::ACTIVITY_LOGS_STATUS[$activityLog['status']];
                $to = Constants::ACTIVITY_LOGS_STATUS[Constants::ACTIVITY_LOGS_STATUS_COMPLETED];

                $commentsModel->insert([
                    'log_id' => $activityLog['id'],
                    'comment' => "Status updated from [{$from}] to [{$to}]",
                    'commented_by' => session()->get('user_id'),
                    'type' => Constants::COMMENT_TYPE_UPDATE,
                ]);

                $activityLogModel->update($activityLog['id'], [
                    'status' => Constants::ACTIVITY_LOGS_STATUS_COMPLETED,
                ]);

                $tasksController = new \App\Controllers\TasksController();
                $tasksController->updateJiraStatusFromTask($activityLog['id'], $activityLog['status'], Constants::ACTIVITY_LOGS_STATUS_COMPLETED);
            }
        }

        $model->update($id, [
            'status' => Constants::STATUS_ENABLED,
            'al_end_date' => $requestedDate,
            'requested_date' => null,
        ]);

        $returnUrl = $this->request->getGet('return_url');
        if ($returnUrl) {
            return redirect()
                ->to($returnUrl)
                ->with('success', "Tag renewal [{$tag['id']}] [{$tag['name']}] has been verified.");
        }

        return redirect()
            ->back()
            ->with('success', "Tag renewal [{$tag['id']}] [{$tag['name']}] has been verified.");
    }

    /**
     * Verify multiple tags
     * @return \CodeIgniter\HTTP\RedirectResponse
     * @throws ReflectionException
     */
    public function verifyMultiple()
    {
        $model = new TagsModel();
        $jiraModel = new JiraHistoryModel();
        $activityLogModel = new TasksModel();
        $commentsModel = new TaskCommentsModel();

        // Support both GET query parameters and POST
        $tag_ids_raw = $this->request->getVar('tags');
        if (empty($tag_ids_raw)) {
            return redirect()
                ->to('/tags')
                ->with('error', 'No tags selected for verification.');
        }

        $tag_ids = is_array($tag_ids_raw) ? $tag_ids_raw : explode(',', $tag_ids_raw);
        $tag_ids = array_filter(array_map('intval', $tag_ids));

        if (empty($tag_ids)) {
            return redirect()
                ->to('/tags')
                ->with('error', 'No valid tags selected for verification.');
        }

        $verifiedCount = 0;
        $skippedCount = 0;
        $verifiedNames = [];

        foreach ($tag_ids as $id) {
            $tag = $model->find($id);
            if (!$tag) {
                $skippedCount++;
                continue;
            }

            $jiraTicket = $jiraModel->getRelatedTags($id);
            $hasTicketId = !empty($jiraTicket) && !empty($jiraTicket[0]['ticket_id']);

            // Only verify tags in STATUS_PENDING_VERIFICATION or (STATUS_ON_HOLD with a ticket ID)
            if ($tag['status'] != Constants::STATUS_PENDING_VERIFICATION) {
                if ($tag['status'] != Constants::STATUS_ON_HOLD || !$hasTicketId) {
                    $skippedCount++;
                    continue;
                }
            }

            $requestedDate = $tag['requested_date'] ?? $tag['al_end_date'];

            if ($hasTicketId) {
                $activityLog = $activityLogModel->where('ticket_id', $jiraTicket[0]['ticket_id'])->first();

                if ($activityLog) {
                    if ($activityLog['status'] != Constants::ACTIVITY_LOGS_STATUS_COMPLETED) {
                        $from = Constants::ACTIVITY_LOGS_STATUS[$activityLog['status']];
                        $to = Constants::ACTIVITY_LOGS_STATUS[Constants::ACTIVITY_LOGS_STATUS_COMPLETED];

                        $commentsModel->insert([
                            'log_id' => $activityLog['id'],
                            'comment' => "Status updated from [{$from}] to [{$to}] (Bulk Verification)",
                            'commented_by' => session()->get('user_id') ?? 0,
                            'type' => Constants::COMMENT_TYPE_UPDATE,
                        ]);

                        $activityLogModel->update($activityLog['id'], [
                            'status' => Constants::ACTIVITY_LOGS_STATUS_COMPLETED,
                        ]);

                        $tasksController = new \App\Controllers\TasksController();
                        $tasksController->updateJiraStatusFromTask($activityLog['id'], $activityLog['status'], Constants::ACTIVITY_LOGS_STATUS_COMPLETED);
                    }
                }
            }

            $model->update($id, [
                'status' => Constants::STATUS_ENABLED,
                'al_end_date' => $requestedDate,
                'requested_date' => null,
            ]);

            $verifiedCount++;
            $verifiedNames[] = "#{$tag['id']} {$tag['name']}";
        }

        $returnUrl = $this->request->getGet('return_url');

        if ($verifiedCount > 0) {
            $msg = "Successfully verified {$verifiedCount} tag(s).";
            if ($skippedCount > 0) {
                $msg .= " Skipped {$skippedCount} tag(s) that were not pending verification.";
            }
            if ($returnUrl) {
                return redirect()
                    ->to($returnUrl)
                    ->with('success', $msg);
            }
            return redirect()
                ->to('/tags')
                ->with('success', $msg);
        } else {
            if ($returnUrl) {
                return redirect()
                    ->to($returnUrl)
                    ->with('error', "No tags were verified. Make sure the selected tags are in 'Pending Verification' or 'Blocked' status with a valid ticket ID.");
            }
            return redirect()
                ->to('/tags')
                ->with('error', "No tags were verified. Make sure the selected tags are in 'Pending Verification' or 'Blocked' status with a valid ticket ID.");
        }
    }

    /**
     * In renew/confirm view, to track if tag is updated in AL but not submitted for review
     * @return \CodeIgniter\HTTP\ResponseInterface
     * @throws ReflectionException
     */
    public function updateAL()
    {
        $tagId = $this->request->getPost('tag_id');

        $model = new TagsModel();
        $model->update($tagId, [
            'status' => Constants::STATUS_UPDATED_IN_AL,
        ]);

        return $this->response->setJSON(['status' => 'ok']);
    }

    public function createUpdateRuleRequest()
    {
        $model = new TagsModel();
        $post = $this->request->getGet();
        $tag_ids = $post['tags'] ?? [];
        $ticket_id = $post['ticket_id'] ?? null;

        if (empty($tag_ids)) {
            return redirect()
                ->to('/tags')
                ->with('error', 'No tags selected for update rule request.');
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

        return view('tags/update-rule-request', $data);
    }

    public function updateRuleRequestConfirm()
    {
        $post = $this->request->getPost();
        $model = new TagsModel();
        $tags = $post['tags'] ?? [];
        $info = $post['info'] ?? [];
        $propertyId = null;
        $tagIds = array_unique(array_column($tags, 'id'));

        foreach ($tags as $tag) {
            if ($propertyId === null) {
                $propertyId = $tag['property_id'];
            }

            $currentTag = $model->find($tag['id']);
            $nextEndDate = date('Y-m-d', strtotime($tag['next_end_date']));
            $currentEndDate = ($currentTag && $currentTag['al_end_date']) ? date('Y-m-d', strtotime($currentTag['al_end_date'])) : null;

            $updateData = [
                'status' => Constants::STATUS_PENDING_VERIFICATION,
            ];

            if ($nextEndDate !== $currentEndDate) {
                $updateData['requested_date'] = $nextEndDate;
            }

            $updated = $model->update($tag['id'], $updateData);

            if ($updated) {
                $this->saveJiraHistory($tag, $info['ticket_id'], $tagIds, false);
            }
        }

        $this->addTask($propertyId, $tagIds, $info, false);

        return redirect()
            ->to('/tasks')
            ->with('success', 'Update Request created successfully.');
    }

    /**
     * @throws ReflectionException
     */
    public function saveJiraHistory($tag, $ticketId, $tagIds, $isRenewal = true)
    {
        if (is_array($tag)) {
            $tagId = $tag['id'];
            $propertyId = $tag['property_id'];
            $propertiesModel = new PropertiesModel();
            $subsidiaryId = $propertiesModel->find($propertyId)['subsidiary_id'] ?? 0;

            $jiraHistoryModel = new JiraHistoryModel();
            if (
                !$jiraHistoryModel->insert([
                    'tag_id' => $tagId,
                    'property_id' => $propertyId,
                    'subsidiary_id' => $subsidiaryId,
                    'ticket_id' => $ticketId,
                    'type' => $isRenewal ? Constants::JIRA_TYPE_DATE_EXTENSION_RULE : Constants::JIRA_TYPE_UPDATE_RULE,
                    'comments' => implode(',', $tagIds),
                ])
            ) {
                var_dump("Failed to save JIRA history for tag ID: {$tagId}");
            }
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

    public function addTask($propertyId, $tagIds, $info, $isRenewal = true)
    {
        $tasksModel = new TasksModel();
        $propertyModel = new PropertiesModel();
        $tagsModel = new TagsModel();
        $property = $propertyModel->find($propertyId);
        $description = $isRenewal
            ? "Renewal of tags: <br/><ul>"
            : "Update of tags: <br/><ul>";

        $title = $isRenewal
            ? 'Tag Renewals'
            : 'Update Request for Tags';

        foreach ($tagIds as $tagId) {
            $tag = $tagsModel->find($tagId);
            if ($tag) {
                $description .= "<li>{$tag['name']}</li>";
            }
        }

        // Auto-add Task
        $taskId = $tasksModel->insert([
            'title' => $title . ' | ' . $property['name'],
            'description' => $description . '</ul>',
            'subsidiary_id' => $property['subsidiary_id'],
            'team_id' => Constants::TEAM_MEDIA_TAGGING,
            'project' => $isRenewal ? 'Date Extension' : 'Maintenance',
            'ticket_id' => $info['ticket_id'] ?? null,
            'assignee' => session()->get('user_id') ?? 0,
            'status' => Constants::ACTIVITY_LOGS_STATUS_IMPLEMENTATION,
            'type' => $isRenewal ? Constants::ACTIVITY_TYPE_RENEWAL : Constants::ACTIVITY_TYPE_REQUEST,
            'size' => Constants::ACTIVITY_SIZE_M,
            'created_by' => session()->get('user_id') ?? 0,
            'requested_by' => 'SDSME/MENAO',
            'requested_date' => date('Y-m-d'),
            'start_date' => date('Y-m-d'),
        ]);

        if ($taskId) {
            $tasksController = new TasksController();
            $tasksController->createJiraIssueFromTask($taskId);
        }
    }

    public function exportAsExcel()
    {
        helper('custom_functions');
        $filters = $this->request->getGet();

        $status = $this->request->getGet('status');

        if ($status === 'all') {
            $status = null; // null triggers filterTags to ignore status and fetch everything
        } elseif ($status == Constants::STATUS_DEFAULT || empty($status)) {
            $status = [1, 4, 5]; // Default to show Enabled, Pending Verification, and Updated in AL
        }

        if ($status == Constants::STATUS_ALL) {
            $status = null;
        }

        $filters['status'] = $status;

        $tagsModel = new TagsModel();
        $tags = $tagsModel->filterTags($filters);

        $filename = 'TagsExport_' . date('Ymd') . '.xls';

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="UTF-8"></head>';
        echo '<body><table border="1">';

        // Headers
        $headers = [
            'Subsidiary',
            'Property Name',
            'Tag Name',
            'Days',
            'AL End Date',
            'Requested Date',
            'Updated',
            'Status'
        ];

        echo '<tr>';
        foreach ($headers as $header) {
            echo '<th style="background-color:#f2f2f2;">' . esc($header) . '</th>';
        }
        echo '</tr>';

        // Rows
        foreach ($tags as $tag) {
            $propertyId = $tag['property_id'];
            $propertiesModel = new PropertiesModel();
            $subsidiariesModel = new SubsidiariesModel();
            $property = $propertiesModel->find($propertyId);
            $subsidiary = $subsidiariesModel->find($property['subsidiary_id'] ?? 0);

            echo '<tr>';
            echo '<td>' . esc($subsidiary['name'] ?? 'N/A') . '</td>';
            echo '<td>' . esc($property['name'] ?? 'N/A') . '</td>';
            echo '<td>' . esc($tag['name']) . '</td>';
            echo '<td>' . esc(isset($tag['al_end_date']) ? ceil((strtotime($tag['al_end_date']) - time()) / 86400) : 'N/A') . '</td>';
            echo '<td>' . esc(date_ymd($tag['al_end_date'])) . '</td>';
            echo '<td>' . esc($tag['requested_date'] ? date_ymd($tag['requested_date']) : (date_ymd($tag['al_end_date']) ?? '-')) . '</td>';
            echo '<td>' . esc($tag['updated_at'] ? date_ymd($tag['updated_at']) : '-') . '</td>';
            echo '<td>' . esc(status_label($tag['status']) ?? 'Unknown') . '</td>';
            echo '</tr>';
        }

        echo '</table></body></html>';
        exit;
    }

    public function uploadRenewalJustifications()
    {
        return view('tags/upload_renewal_justifications');
    }

    public function parseRenewalJustifications()
    {
        $file = $this->request->getFile('excel_file');

        if (!$file || !$file->isValid() || $file->getExtension() !== 'xlsx') {
            return redirect()->back()->with('error', 'Please upload a valid .xlsx file.');
        }

        // Save file temporarily
        $tempFolder = WRITEPATH . 'uploads/temp';
        if (!is_dir($tempFolder)) {
            mkdir($tempFolder, 0777, true);
        }
        $originalName = $file->getClientName();
        $tempFileName = uniqid() . '_renewal_' . $originalName;
        $tempFilePath = $tempFolder . '/' . $tempFileName;

        if (!$file->move($tempFolder, $tempFileName)) {
            return redirect()->back()->with('error', 'Failed to save uploaded file temporarily.');
        }

        require_once APPPATH . 'Libraries/SimpleXLSX.php';

        $xlsx = \Shuchkin\SimpleXLSX::parse($tempFilePath);

        if (!$xlsx) {
            if (file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
            return redirect()->back()->with('error', 'Failed to parse Excel file.');
        }

        $rows = $xlsx->rows();

        $tagsModel = new TagsModel();
        $propertiesModel = new PropertiesModel();

        $isFirstRow = true;
        $extractedRows = [];

        foreach ($rows as $row) {
            if ($isFirstRow) {
                // Check if it's the header row
                $firstCol = trim($row[0] ?? '');
                if (stripos($firstCol, 'Property Name') !== false) {
                    $isFirstRow = false;
                    continue;
                }
                $isFirstRow = false;
            }

            // Ensure we have at least 5 columns
            if (count($row) >= 5) {
                $propertyName = trim((string) ($row[0] ?? ''));
                $tagName = trim((string) ($row[1] ?? ''));
                $renew = trim((string) ($row[2] ?? ''));
                $justification = trim((string) ($row[3] ?? ''));
                $duration = trim((string) ($row[4] ?? ''));

                if (empty($propertyName) && empty($tagName)) {
                    continue;
                }

                $status = 'Valid';
                $property = $propertiesModel->where('name', $propertyName)->first();
                if (!$property) {
                    $status = 'Property Not Found';
                } else {
                    $tag = $tagsModel->where('property_id', $property['id'])
                        ->where('name', $tagName)
                        ->first();
                    if (!$tag) {
                        $status = 'Tag Not Found';
                    }
                }

                $extractedRows[] = [
                    'property_name' => $propertyName,
                    'tag_name' => $tagName,
                    'renew' => $renew,
                    'justification' => $justification,
                    'duration' => $duration,
                    'status' => $status
                ];
            }
        }

        if (empty($extractedRows)) {
            if (file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
            return redirect()->back()->with('error', 'The uploaded Excel file does not contain any valid tag renewal rows.');
        }

        return view('tags/upload_renewal_justifications', [
            'extracted_rows' => $extractedRows,
            'temp_file_path' => $tempFilePath,
            'temp_file_name' => $originalName
        ]);
    }

    public function confirmRenewalJustifications()
    {
        $tempFilePath = $this->request->getPost('temp_file_path');

        if (empty($tempFilePath) || !file_exists($tempFilePath)) {
            return redirect()->to('/tags/upload-renewal-justifications')->with('error', 'Temporary file not found. Please upload again.');
        }

        require_once APPPATH . 'Libraries/SimpleXLSX.php';

        $xlsx = \Shuchkin\SimpleXLSX::parse($tempFilePath);
        if (!$xlsx) {
            if (file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
            return redirect()->to('/tags/upload-renewal-justifications')->with('error', 'Failed to parse temporary file. Please upload again.');
        }

        $rows = $xlsx->rows();

        $tagsModel = new TagsModel();
        $propertiesModel = new PropertiesModel();
        $renewalJustificationModel = new \App\Models\RenewalJustificationModel();
        $userId = session()->get('user_id') ?? 0;

        $isFirstRow = true;
        $savedCount = 0;

        foreach ($rows as $row) {
            if ($isFirstRow) {
                $firstCol = trim($row[0] ?? '');
                if (stripos($firstCol, 'Property Name') !== false) {
                    $isFirstRow = false;
                    continue;
                }
                $isFirstRow = false;
            }

            if (count($row) >= 5) {
                $propertyName = trim((string) ($row[0] ?? ''));
                $tagName = trim((string) ($row[1] ?? ''));
                $renew = trim((string) ($row[2] ?? ''));
                $justification = trim((string) ($row[3] ?? ''));
                $duration = trim((string) ($row[4] ?? ''));

                if (!empty($propertyName) && !empty($tagName)) {
                    $property = $propertiesModel->where('name', $propertyName)->first();
                    if ($property) {
                        $tag = $tagsModel->where('property_id', $property['id'])
                            ->where('name', $tagName)
                            ->first();

                        if ($tag) {
                            $renewalJustificationModel->insert([
                                'tag_id' => $tag['id'],
                                'renew' => $renew,
                                'justification' => $justification,
                                'duration' => $duration,
                                'created_by' => $userId,
                                'updated_by' => $userId
                            ]);
                            $savedCount++;
                        }
                    }
                }
            }
        }

        if (file_exists($tempFilePath)) {
            unlink($tempFilePath);
        }

        return redirect()->to('/tags')->with('success', "{$savedCount} renewal justifications have been successfully processed and saved.");
    }

    public function renewalJustifications()
    {
        $justificationModel = new \App\Models\RenewalJustificationModel();

        $latestIdsQuery = $justificationModel->db->table('renewal_justification')
            ->select('MAX(id) as id')
            ->groupBy('tag_id')
            ->get()
            ->getResultArray();

        $latestIds = array_column($latestIdsQuery, 'id');

        $justifications = [];
        if (!empty($latestIds)) {
            $justifications = $justificationModel
                ->select('renewal_justification.*, tags.name as tag_name, properties.name as property_name, subsidiaries.name as subsidiary_name, subsidiaries.id as subsidiary_id')
                ->join('tags', 'tags.id = renewal_justification.tag_id')
                ->join('properties', 'properties.id = tags.property_id')
                ->join('subsidiaries', 'subsidiaries.id = properties.subsidiary_id')
                ->whereIn('renewal_justification.id', $latestIds)
                ->orderBy('subsidiaries.name', 'ASC')
                ->orderBy('properties.name', 'ASC')
                ->orderBy('tags.name', 'ASC')
                ->findAll();
        }

        $grouped = [];
        foreach ($justifications as $j) {
            $grouped[$j['subsidiary_id']][] = $j;
        }

        $subsidiariesModel = new \App\Models\SubsidiariesModel();
        $subsidiaries = $subsidiariesModel
            ->where('is_active', Constants::YES)
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('tags/renewal_justifications', [
            'grouped' => $grouped,
            'subsidiaries' => $subsidiaries
        ]);
    }

    public function updateRenewalJustification($id)
    {
        $justificationModel = new \App\Models\RenewalJustificationModel();
        $justification = $justificationModel->find($id);

        if (!$justification) {
            return redirect()->to('/tags/renewal-justifications')->with('error', 'Renewal justification not found.');
        }

        $tagsModel = new TagsModel();
        $tag = $tagsModel->find($justification['tag_id']);

        if ($this->request->getMethod() === 'GET') {
            return view('tags/update_renewal_justification', [
                'justification' => $justification,
                'tag' => $tag
            ]);
        }

        $post = $this->request->getPost();

        $updateData = [
            'renew' => trim($post['renew']),
            'justification' => trim($post['justification']),
            'duration' => trim($post['duration']),
            'updated_by' => session()->get('user_id') ?? 0
        ];

        $justificationModel->update($id, $updateData);

        return redirect()->to('/tags/renewal-justifications')->with('success', 'Renewal justification updated successfully.');
    }
    public function saveJsCode($id)
    {
        $model = new TagsModel();
        $tag = $model->find($id);

        if (!$tag) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tag not found.']);
        }

        $jsCode = $this->request->getPost('js_code');

        $model->update($id, [
            'js_code' => $jsCode
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'JavaScript code saved successfully.']);
    }

    private function fetchAiModels()
    {
        $apiUrl = env('FABRIX_API_URL') . '/openapi/llm/v1/models';
        $apiKey = env('FABRIX_API_KEY') ?? '';

        $client = \Config\Services::curlrequest();

        try {
            $response = $client->request('GET', $apiUrl, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey
                ],
                'http_errors' => false
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 300) {
                $data = json_decode($response->getBody(), true);
                if (is_array($data)) {
                    return isset($data['data']) ? $data['data'] : (isset($data[0]) ? $data : array_values($data));
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error fetching AI Models: ' . $e->getMessage());
        }
        
        return [];
    }

    public function chatWithAi()
    {
        $apiUrl = env('FABRIX_API_URL') . '/openapi/llm/v1/chat';
        $apiKey = env('FABRIX_API_KEY') ?? '';

        $post = $this->request->getJSON();
        $modelId = $post->modelId ?? null;
        $message = $post->message ?? '';
        $context = $post->context ?? '';

        if (!$modelId || !$message) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing modelId or message']);
        }

        $client = \Config\Services::curlrequest();

        try {
            $response = $client->request('POST', $apiUrl, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey
                ],
                'http_errors' => false,
                'json' => [
                    'modelId' => $modelId,
                    'message' => $message,
                    'context' => $context
                ]
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 300) {
                return $this->response->setJSON(json_decode($response->getBody(), true));
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'API returned ' . $statusCode]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
