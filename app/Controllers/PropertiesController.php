<?php

namespace App\Controllers;

use App\Models\PropertiesModel;
use App\Models\SubsidiariesModel;
use App\Models\LanguagesModel;
use App\Models\CountriesModel;

class PropertiesController extends BaseController
{
    public function index()
    {
        $propertiesModel = new PropertiesModel();
        $subsidiaryModel = new SubsidiariesModel();
        $languageModel = new LanguagesModel();
        $countryModel = new CountriesModel();
        
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

        $data['subsidiariesList'] = $subsidiaryModel->orderBy('name', 'ASC')->findAll();
        $data['languagesList'] = $languageModel->orderBy('name', 'ASC')->findAll();
        $data['countriesList'] = $countryModel->orderBy('name', 'ASC')->findAll();

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
                ->back()
                ->with('success', "Property URL updated successfully.");
        } else {
            return redirect()
                ->back()
                ->with('error', "Failed to update Property URL.");
        }
    }

    public function addAttribute()
    {
        $type = trim((string)$this->request->getPost('type'));
        $name = trim((string)$this->request->getPost('name'));
        $code = trim((string)$this->request->getPost('code'));

        $isAjax = $this->request->isAJAX();

        if (empty($type)) {
            $msg = 'Attribute type is required.';
            return $isAjax 
                ? $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => $msg])
                : redirect()->back()->with('error', $msg);
        }

        if (empty($name)) {
            $msg = 'Name is required.';
            return $isAjax 
                ? $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => $msg])
                : redirect()->back()->with('error', $msg);
        }

        try {
            switch ($type) {
                case 'subsidiary':
                    $subsidiaryModel = new SubsidiariesModel();
                    $cleanName = strtoupper($name);

                    // Check duplicate
                    $existing = $subsidiaryModel->withDeleted()->where('name', $cleanName)->first();
                    if ($existing) {
                        if (!empty($existing['deleted_at'])) {
                            $subsidiaryModel->update($existing['id'], [
                                'is_active' => 1,
                                'deleted_at' => null,
                                'deleted_by' => 0,
                            ]);
                            $msg = "Subsidiary '{$cleanName}' has been restored.";
                            return $isAjax 
                                ? $this->response->setJSON(['status' => 'success', 'message' => $msg, 'data' => $existing])
                                : redirect()->back()->with('success', $msg);
                        }
                        $msg = "Subsidiary '{$cleanName}' already exists.";
                        return $isAjax 
                            ? $this->response->setStatusCode(422)->setJSON(['status' => 'error', 'message' => $msg])
                            : redirect()->back()->with('error', $msg);
                    }

                    $insertId = $subsidiaryModel->insert([
                        'name' => $cleanName,
                        'is_active' => 1,
                    ]);

                    $msg = "Subsidiary '{$cleanName}' added successfully.";
                    return $isAjax 
                        ? $this->response->setJSON(['status' => 'success', 'message' => $msg, 'id' => $insertId])
                        : redirect()->back()->with('success', $msg);

                case 'language':
                    $languageModel = new LanguagesModel();
                    $cleanName = strtoupper($name);

                    $existing = $languageModel->withDeleted()->where('name', $cleanName)->first();
                    if ($existing) {
                        if (!empty($existing['deleted_at'])) {
                            $languageModel->update($existing['id'], [
                                'deleted_at' => null,
                                'deleted_by' => 0,
                            ]);
                            $msg = "Language '{$cleanName}' has been restored.";
                            return $isAjax 
                                ? $this->response->setJSON(['status' => 'success', 'message' => $msg, 'data' => $existing])
                                : redirect()->back()->with('success', $msg);
                        }
                        $msg = "Language '{$cleanName}' already exists.";
                        return $isAjax 
                            ? $this->response->setStatusCode(422)->setJSON(['status' => 'error', 'message' => $msg])
                            : redirect()->back()->with('error', $msg);
                    }

                    $insertId = $languageModel->insert([
                        'name' => $cleanName,
                    ]);

                    $msg = "Language '{$cleanName}' added successfully.";
                    return $isAjax 
                        ? $this->response->setJSON(['status' => 'success', 'message' => $msg, 'id' => $insertId])
                        : redirect()->back()->with('success', $msg);

                case 'country':
                    $countryModel = new CountriesModel();
                    $cleanName = $name;
                    $cleanCode = strtoupper($code);

                    if (empty($cleanCode)) {
                        $msg = 'Country code is required.';
                        return $isAjax 
                            ? $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => $msg])
                            : redirect()->back()->with('error', $msg);
                    }

                    $existing = $countryModel->withDeleted()
                        ->groupStart()
                            ->where('name', $cleanName)
                            ->orWhere('code', $cleanCode)
                        ->groupEnd()
                        ->first();

                    if ($existing) {
                        if (!empty($existing['deleted_at'])) {
                            $countryModel->update($existing['id'], [
                                'name' => $cleanName,
                                'code' => $cleanCode,
                                'deleted_at' => null,
                                'deleted_by' => 0,
                            ]);
                            $msg = "Country '{$cleanName}' ({$cleanCode}) has been restored.";
                            return $isAjax 
                                ? $this->response->setJSON(['status' => 'success', 'message' => $msg, 'data' => $existing])
                                : redirect()->back()->with('success', $msg);
                        }
                        $msg = "Country with name '{$cleanName}' or code '{$cleanCode}' already exists.";
                        return $isAjax 
                            ? $this->response->setStatusCode(422)->setJSON(['status' => 'error', 'message' => $msg])
                            : redirect()->back()->with('error', $msg);
                    }

                    $insertId = $countryModel->insert([
                        'name' => $cleanName,
                        'code' => $cleanCode,
                    ]);

                    $msg = "Country '{$cleanName}' ({$cleanCode}) added successfully.";
                    return $isAjax 
                        ? $this->response->setJSON(['status' => 'success', 'message' => $msg, 'id' => $insertId])
                        : redirect()->back()->with('success', $msg);

                default:
                    $msg = 'Invalid attribute type.';
                    return $isAjax 
                        ? $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => $msg])
                        : redirect()->back()->with('error', $msg);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error adding attribute: ' . $e->getMessage());
            $msg = 'Failed to add attribute: ' . $e->getMessage();
            return $isAjax 
                ? $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => $msg])
                : redirect()->back()->with('error', $msg);
        }
    }
}
