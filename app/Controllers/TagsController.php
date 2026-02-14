<?php

namespace App\Controllers;

use App\Models\TagsModel;

class TagsController extends BaseController
{
    public function index()
    {
        $tagModel = new TagsModel();

        $filters = [
            'keyword'     => $this->request->getGet('keyword'),
            'status'      => $this->request->getGet('status'),
            'property_id' => $this->request->getGet('property_id'),
            'date_from'   => $this->request->getGet('date_from'),
            'date_to'     => $this->request->getGet('date_to'),
        ];

        $data['tags'] = $tagModel->filter($filters);

        return view('tags/list', $data);

    }
}