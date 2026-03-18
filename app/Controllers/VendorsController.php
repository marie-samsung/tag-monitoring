<?php

namespace App\Controllers;

use App\Models\VendorsModel;

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
}