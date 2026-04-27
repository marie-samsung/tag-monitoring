<?php

namespace App\Controllers;

use App\Models\Constants;
use App\Models\OrganizationsModel;
use App\Models\TagRequestsModel;
use App\Models\PropertiesModel;
use App\Models\VendorsModel;
use App\Models\TagsModel;

class TagRequestsController extends BaseController
{
    public function index()
    {
        $model = new TagRequestsModel();
        $data['requests'] = $model->findAll();

        return view('tag-requests/list', $data);
    }

    public function create()
    {
        $model = new TagRequestsModel();
        $tagsModel = new TagsModel();
        $vendorsModel = new VendorsModel();
        $propertiesModel = new PropertiesModel();
        $organizationsModel = new OrganizationsModel();

        if ($this->request->getMethod() === 'GET') {
            return view('tag-requests/create', []);
        }

        $post = $this->request->getPost();

        $propertyId = $post['property_id'];
        $reqName = $post['requester_name'];
        $reqEmail = $post['requester_email'];
        $jiraType = $post['type'];
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

            $organizationInput = trim($post['requester_organization']);
            $organization = $organizationsModel->where('name', $organizationInput)->first();
            if (!$organization) {
                $organizationId = $organizationsModel->insert([
                    'name' => $organizationInput,
                ]);
            } else {
                $organizationId = $organization['id'];
            }

            foreach ($post['tags'] as $tag) {
                $tagIds[] = $tagsModel->insert([
                    'property_id' => $propertyId,
                    'vendor_id' => $vendorId,
                    'name' => $tag,
                    'status' => Constants::STATUS_NEW_TAG_SUBMITTED,
                    'al_end_date' => $post['al_end_date'],
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
        $post = $this->request->getPost();

        $ticketId = $post['ticket_id'];
        $id = $post['request_id'];

        if (empty($ticketId)) {
            return redirect()
                ->to('/tag-requests')
                ->with('error', "Ticket was not successfully created.");
        } else {
            $tagRequest = $model->find($id);

            if (!$tagRequest) {
                return redirect()
                    ->to('/tag-requests')
                    ->with('error', "Tag request was not found.");
            } else {
                $model->update($id, [
                    'ticket_id' => $ticketId,
                ]);

                return redirect()
                    ->to('/tag-requests')
                    ->with('success', "[{$ticketId}] Tag Request successfully created.");
            }
        }

        var_export($post);
        die();
    }
}
