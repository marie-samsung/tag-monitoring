<?php

namespace App\Controllers;

use App\Models\ActivityLogCommentsModel;
use App\Models\ActivityLogsModel;
use App\Models\ActivityLogFilesModel;
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
            'created_at_from' => $this->request->getGet('created_at_from'),
            'created_at_to' => $this->request->getGet('created_at_to'),
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
        $excelFilePath = $post['excel_file_path'] ?? null;
        $excelFileName = $post['excel_file_name'] ?? null;
        $jiraType = $post['type'];
        $endDate = date('Y-m-d', strtotime('+61 days'));
        
        $background = $post['background'] ?? null;
        $q1 = $post['q1'] ?? null;
        $q2 = $post['q2'] ?? null;
        $q3 = $post['q3'] ?? null;
        $q4 = $post['q4'] ?? null;
        $q5 = $post['q5'] ?? null;
        
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
                    'created_by' => session()->get('user_id') ?? 0,
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
                'created_by' => session()->get('user_id') ?? 0,
            ];

            $requestId = $model->insert($data);

            if ($excelFilePath && file_exists($excelFilePath)) {
                $finalVendorName = $vendorName ?? 'Unknown';
                $uploadPath = Constants::NEW_TAG_FILE_WRITE_PATH . $model->fileUploadPath($propertyId, $finalVendorName);
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                $finalFilePath = $uploadPath . '/' . $excelFileName;
                if (rename($excelFilePath, $finalFilePath)) {
                    $filesModel = new TagRequestFilesModel();
                    $filesModel->insert([
                        'file_name' => $excelFileName,
                        'file_path' => $finalFilePath,
                        'type' => Constants::ATTACHMENT_REQUEST_FORM,
                        'request_id' => $requestId,
                        'created_by' => session()->get('user_id') ?? 0,
                    ]);
                }
            }

            return view('tag-requests/create', [
                'tag_ids' => $tagIds,
                'request_id' => $requestId,
                'vendor_name' => $vendorName,
                'property_id' => $propertyId,
                'vendor_id' => $vendorId,
                'request_type' => $jiraType,
                'ticket_id' => null,
                'al_end_date' => $endDate,
                'background' => $background,
                'q1' => $q1,
                'q2' => $q2,
                'q3' => $q3,
                'q4' => $q4,
                'q5' => $q5,
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

    public function newRuleRequest($id)
    {
        $model = new TagRequestsModel();
        $request = $model->find($id);

        if (!$request) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Tag request with ID $id not found.");
        }

        return view('tag-requests/create', [
            'request_id' => $request['id'],
            'request_type' => Constants::JIRA_TYPE_NEW_RULE,
            'property_id' => $request['property_id'],
            'vendor_id' => $request['vendor_id'],
            'ticket_id' => $request['new_rule_ticket'],
            'al_end_date' => null, // @TODO: Get from tags
            'tag_ids' => $request['tags'],
        ]);
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
            if ($activityLog['status'] != Constants::ACTIVITY_LOGS_STATUS_COMPLETED) {
                $from = Constants::ACTIVITY_LOGS_STATUS[$activityLog['status']];
                $to = Constants::ACTIVITY_LOGS_STATUS[Constants::ACTIVITY_LOGS_STATUS_COMPLETED];

                $commentsModel->insert([
                    'log_id' => $activityLog['id'],
                    'comment' => "Status updated from [{$from}] to [{$to}]",
                    'commented_by' => Constants::USER_SYSTEM,
                    'type' => Constants::COMMENT_TYPE_UPDATE,
                ]);

                $activityLogModel->update($activityLog['id'], [
                    'status' => Constants::ACTIVITY_LOGS_STATUS_COMPLETED,
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
            ->to('/tag-requests/')
            ->with('success', "[{$ticketId}] Tag Request approved, and associated tags are now enabled.");
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
                         'created_by' => session()->get('user_id') ?? 0,
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
                'created_by' => session()->get('user_id') ?? 0,
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

        $activityLogId = $activityLogModel->insert([
            'title' => $title . $vendor['name'],
            'description' => $description . '</ul></ol>',
            'subsidiary_id' => $property['subsidiary_id'],
            'project_id' => Constants::PROJECT_MEDIA_TAGGING,
            'ticket_id' => $ticketId ?? null,
            'assignee' => session()->get('user_id') ?? 0,
            'status' => Constants::ACTIVITY_LOGS_STATUS_IMPLEMENTATION,
            'type' => Constants::ACTIVITY_TYPE_REQUEST,
            'size' => Constants::ACTIVITY_SIZE_M,
            'priority' => Constants::PRIORITY_MEDIUM,
            'created_by' => session()->get('user_id') ?? 0,
        ]);

        if ($activityLogId) {
            $filesModel = new TagRequestFilesModel();
            $relatedFiles = $filesModel->where('request_id', $request['id'])
                                       ->where('type', Constants::ATTACHMENT_REQUEST_FORM)
                                       ->findAll();

            if (!empty($relatedFiles)) {
                $activityLogFilesModel = new ActivityLogFilesModel();
                foreach ($relatedFiles as $file) {
                    $activityLogFilesModel->insert([
                        'file_name' => $file['file_name'],
                        'file_path' => $file['file_path'],
                        'log_id' => $activityLogId,
                        'created_by' => session()->get('user_id') ?? 0,
                    ]);
                }
            }
        }
    }

    public function uploadExcel()
    {
        return view('tag-requests/upload_excel');
    }

    public function parseExcel()
    {
        $file = $this->request->getFile('excel_file');

        if (!$file || !$file->isValid() || $file->getExtension() !== 'xlsx') {
            return redirect()->back()->with('error', 'Please upload a valid .xlsx file.');
        }

        require_once APPPATH . 'Libraries/SimpleXLSX.php';

        $xlsx = \Shuchkin\SimpleXLSX::parse($file->getTempName());
        
        if (!$xlsx) {
            return redirect()->back()->with('error', 'Failed to parse Excel file: ' . \Shuchkin\SimpleXLSX::parseError());
        }

        $rows = $xlsx->rows();

        // Save the uploaded file temporarily so we can link it when the request is created
        $tempFolder = WRITEPATH . 'uploads/temp';
        if (!is_dir($tempFolder)) {
            mkdir($tempFolder, 0777, true);
        }
        $originalName = $file->getClientName();
        $tempFileName = uniqid() . '_' . $originalName;
        $tempFilePath = $tempFolder . '/' . $tempFileName;
        
        $fileSaved = false;
        if ($file->move($tempFolder, $tempFileName)) {
            $fileSaved = true;
        }

        $propertiesModel = new PropertiesModel();
        $properties = $propertiesModel->select('id, website')->findAll();

        $extractedData = [
            'property_id' => '',
            'default_tag' => '',
            'excel_file_path' => $fileSaved ? $tempFilePath : '',
            'excel_file_name' => $fileSaved ? $originalName : '',
            'vendor_name' => '',
            'validity' => '',
            'target_properties' => '',
            'target_pages' => '',
            'events' => '',
            'requester_name' => '',
            'requester_email' => '',
            'tag_script' => '',
            'background' => '',
            'q1' => '',
            'q2' => '',
            'q3' => '',
            'q4' => '',
            'q5' => ''
        ];

        foreach ($rows as $row) {
            if (count($row) < 3) continue;
            
            $key = $row[1] ?? '';
            $value = $row[2] ?? '';

            if (strpos($key, 'Partner/Vendor') !== false) {
                $extractedData['vendor_name'] = $value;
            } elseif (strpos($key, 'Validity of the Tags') !== false) {
                $extractedData['validity'] = $value;
            } elseif (strpos($key, 'Target Property/Sites') !== false) {
                $extractedData['target_properties'] = $value;
            } elseif (strpos($key, 'Target Page(s)') !== false) {
                $extractedData['target_pages'] = $value;
            } elseif (strpos($key, 'Events need to be captured') !== false) {
                $extractedData['events'] = $value;
            } elseif (strpos($key, 'Requestor Name') !== false || strpos($key, 'Requester Name') !== false) {
                $extractedData['requester_name'] = $value;
            } elseif (strpos($key, 'Requestor Email') !== false || strpos($key, 'Requester Email') !== false) {
                $extractedData['requester_email'] = $value;
            } elseif (strpos($key, 'Tag') !== false) {
                $extractedData['tag_script'] = $value;
            } elseif (strpos($key, 'Background') !== false) {
                $extractedData['background'] = $value;
            } elseif (strpos($key, 'What is the official website of the platform/company?') !== false) {
                $extractedData['q1'] = $value;
            } elseif (strpos($key, 'What is the duration of usage?') !== false) {
                $extractedData['q2'] = $value;
            } elseif (strpos($key, 'Any KPIs applied to review this tag bi-monthly') !== false) {
                $extractedData['q3'] = $value;
            } elseif (strpos($key, 'Performance thresholds that will determine renewal or deactivation of this tag?') !== false) {
                $extractedData['q4'] = $value;
            } elseif (strpos($key, 'What is your internal process for reviewing and validating these KPIs every two months?') !== false) {
                $extractedData['q5'] = $value;
            }
        }

        $hasData = false;
        foreach ($extractedData as $field => $val) {
            if ($field === 'property_id' || $field === 'default_tag' || $field === 'excel_file_path' || $field === 'excel_file_name') continue;
            if (trim((string) $val) !== '') {
                $hasData = true;
                break;
            }
        }

        if (!$hasData) {
            if (!empty($extractedData['excel_file_path']) && file_exists($extractedData['excel_file_path'])) {
                unlink($extractedData['excel_file_path']);
            }
            return redirect()->back()->with('error', 'The uploaded Excel file does not contain any valid tag request data.');
        }

        // Try to match property_id by finding which property website is first listed in target_properties
        if (!empty($extractedData['target_properties'])) {
            $urls = preg_split('/[\s,;]+/', $extractedData['target_properties']);
            $urls = array_filter(array_map('trim', $urls));
            
            $normalizeUrl = function ($url) {
                $url = trim($url);
                $url = preg_replace('#^https?://(www\.)?#', '', $url);
                return rtrim($url, '/');
            };

            foreach ($urls as $url) {
                $normalizedUrl = $normalizeUrl($url);
                $bestMatchLen = 0;
                $bestMatchId = null;

                foreach ($properties as $property) {
                    if (empty($property['website'])) continue;
                    $normalizedPropWebsite = $normalizeUrl($property['website']);
                    
                    if (strpos($normalizedUrl, $normalizedPropWebsite) === 0) {
                        $len = strlen($normalizedPropWebsite);
                        if ($len > $bestMatchLen) {
                            $bestMatchId = $property['id'];
                            $bestMatchLen = $len;
                        }
                    }
                }
                
                if ($bestMatchId) {
                    $extractedData['property_id'] = $bestMatchId;
                    break;
                }
            }
        }

        // Generate default tag based on selected property type and vendor name with a random UUID
        $shopOrAem = 'AEM'; // default fallback
        if (!empty($extractedData['property_id'])) {
            $matchedProperty = $propertiesModel->find($extractedData['property_id']);
            if ($matchedProperty) {
                $shopOrAem = ($matchedProperty['is_shop'] == Constants::YES) ? 'Shop' : 'AEM';
            }
        }
        $vendorName = !empty($extractedData['vendor_name']) ? $extractedData['vendor_name'] : 'Vendor';
        $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
        $extractedData['default_tag'] = "LC-{$shopOrAem}-AD({$vendorName})-CM-ALL-WL-SDSME_{$uuid}";

        return view('tag-requests/upload_excel', ['extracted_data' => $extractedData]);
    }

    public function prefill()
    {
        $extractedData = $this->request->getPost();
        return view('tag-requests/create', ['extracted_data' => $extractedData]);
    }
}
