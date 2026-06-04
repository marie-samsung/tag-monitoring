<?php

namespace App\Controllers;

use App\Models\SubsidiariesModel;
use App\Models\PropertiesModel;
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

    public function exportAsCSV($vendorId)
    {
        helper('custom_functions');
        $subsidiaryId = $this->request->getGet('subsidiary_id');
        $tagsModel = new TagsModel();
        $tags = $tagsModel->getTagsByVendor($vendorId, $subsidiaryId);

        $vendorsModel = new VendorsModel();
        $vendor = $vendorsModel->find($vendorId);

        $subsidiariesModel = new SubsidiariesModel();
        $subsidiary = $subsidiariesModel->find($subsidiaryId);

        $filename = "Tags_[{$subsidiary['name']}][{$vendor['name']}]_" . date('Ymd') . ".csv";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $csv = fopen('php://output', 'w');

        // CSV column headers
        fputcsv($csv, [
            'Subsidiary',
            'Property Name',
            'Tag Name',
            'Days',
            'AL End Date',
            'Requested Date',
            'Updated',
            'Status',
        ]);

        // Rows
        foreach ($tags as $tag) {
            $propertyId = $tag['property_id'];
            $propertiesModel = new PropertiesModel();
            $property = $propertiesModel->find($propertyId);

            fputcsv($csv, [
                $subsidiary['name'] ?? 'N/A',
                $property['name'] ?? 'N/A',
                $tag['name'],
                isset($tag['al_end_date']) ? ceil((strtotime($tag['al_end_date']) - time()) / 86400) : 'N/A',
                "'" . date_ymd($tag['al_end_date']),
                "'" . date_ymd($tag['requested_date']) ?? date_ymd($tag['al_end_date']) ?? '-',
                "'" . date_ymd($tag['updated_at']) ?? '-',
                status_label($tag['status']) ?? 'Unknown',
            ]);
        }

        fclose($csv);
        exit;
    }

    public function update($id)
    {
        $vendorsModel = new VendorsModel();
        $vendor = $vendorsModel->find($id);

        if (!$vendor) {
            return redirect()
                ->to('/vendors')
                ->with('error', "Vendor with ID $id not found.");
        }

        $subsidiaryId = $this->request->getGet('subsidiary_id') ?? $this->request->getPost('subsidiary_id');

        if ($this->request->getMethod() === 'GET') {
            return view('vendors/update', [
                'vendor' => $vendor,
                'subsidiary_id' => $subsidiaryId,
            ]);
        }

        $post = $this->request->getPost();

        $updateData = [
            'name'        => trim($post['name']),
            'about'       => $post['about'],
            'reason'      => $post['reason'],
            'tag_purpose' => $post['tag_purpose'],
        ];

        $vendorsModel->update($id, $updateData);

        $redirectUrl = '/vendors';
        if (!empty($subsidiaryId)) {
            $redirectUrl = '/vendors/view/' . $id . '?subsidiary_id=' . $subsidiaryId;
        }

        return redirect()
            ->to($redirectUrl)
            ->with('success', "Vendor '{$updateData['name']}' updated successfully.");
    }
}