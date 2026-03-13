<?php

namespace App\Controllers;

use App\Models\PropertiesModel;

class PropertiesController extends BaseController
{
    public function index()
    {
        $propertiesModel = new PropertiesModel();
        
        $filters = [
            'keyword' => $this->request->getGet('keyword'),
            'subsidiary_id' => $this->request->getGet('subsidiary_id'),
        ];

        $data['properties'] = $propertiesModel->filterProperties($filters);
        $data['filters'] = $filters;

        return view('properties/list', $data);
    }
}