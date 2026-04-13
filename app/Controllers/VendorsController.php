<?php

namespace App\Controllers;

use App\Models\VendorsModel;
use App\Models\TagsModel;

class VendorsController extends BaseController
{
    public function index()
    {
        $vendorsModel = new VendorsModel();
        
        $filters = [
            'keyword' => $this->request->getGet('keyword'),
            'id' => $this->request->getGet('id'),
        ];

        $data['vendors'] = $vendorsModel->filterVendors($filters);
        $data['filters'] = $filters;

        return view('vendors/list', $data);
    }

    public function view($id)
    {
        $vendorsModel = new VendorsModel();
        $vendor = $vendorsModel->find($id);
        $tagsModel = new TagsModel();

        if (!$vendor) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Vendor with ID $id not found.");
        }

        $post = $this->request->getGet();
        $subsidiary_id = $post['subsidiary_id'] ?? null;

        if (empty($subsidiary_id)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Subsidiary ID not provided.");
        }

        return view('vendors/view', [
            'vendor' => $vendor, 
            'tags' => $tagsModel->getTagsByVendor($id, $subsidiary_id),
            'subsidiary_id' => $subsidiary_id,
        ]);
    }
}