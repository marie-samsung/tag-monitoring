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
            'is_epp' => $this->request->getGet('is_epp'),
            'is_shop' => $this->request->getGet('is_shop'),
            'sort_by' => $this->request->getGet('sort_by'),
            'sort_dir' => $this->request->getGet('sort_dir'),
        ];

        $data['properties'] = $propertiesModel->filterProperties($filters, 25);
        $data['pager'] = $propertiesModel->pager;
        $data['filters'] = $filters;

        return view('properties/list', $data);
    }

    public function create()
    {
        $model = new PropertiesModel();

        if ($this->request->getMethod() === 'GET') {
            return view('properties/create', []);
        }

        $post = $this->request->getPost();

        $insertData = [
            'name' => $post['name'],
            'is_active' => (int)$post['is_active'],
            'is_epp' => (int)$post['is_epp'],
            'is_shop' => (int)$post['is_shop'],
            'subsidiary_id' => (int)$post['subsidiary_id'],
            'country_id' => (int)$post['country_id'],
            'language_id' => (int)$post['language_id'],
            'website' => $post['website'],
            'ref_link' => $post['ref_link'],
        ];

        $model->insert($insertData);

        return redirect()
            ->to('/properties')
            ->with('success', "Property {$post['name']} created successfully.");
    }

    public function updateUrl($id)
    {
        $model = new PropertiesModel();
        
        $website = $this->request->getPost('website');
        
        if ($model->update($id, ['website' => $website])) {
            return redirect()
                ->to('/properties')
                ->with('success', "Property URL updated successfully.");
        } else {
            return redirect()
                ->to('/properties')
                ->with('error', "Failed to update Property URL.");
        }
    }
}
