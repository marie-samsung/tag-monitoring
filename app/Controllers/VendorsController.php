<?php

namespace App\Controllers;

use App\Models\SubsidiariesModel;
use App\Models\PropertiesModel;
use App\Models\VendorsModel;
use App\Models\TagsModel;
use App\Models\Constants;

class VendorsController extends BaseController
{
    public function index()
    {
        $vendorsModel = new VendorsModel();
        $tagsModel = new \App\Models\TagsModel();
        
        $filters = [
            'keyword' => $this->request->getGet('keyword'),
            'id' => $this->request->getGet('id'),
            'sort_by' => $this->request->getGet('sort_by'),
            'sort_dir' => $this->request->getGet('sort_dir'),
        ];

        $data['vendors'] = $vendorsModel->filterVendors($filters);
        $data['filters'] = $filters;

        // Pre-fetch tags to avoid N+1 query problem and support dynamic bubble coloring
        $allTags = $tagsModel->select('tags.id, tags.vendor_id, tags.status, tags.al_end_date, properties.subsidiary_id')
            ->join('properties', 'properties.id = tags.property_id', 'left')
            ->whereNotIn('tags.status', [
                Constants::STATUS_DISABLED,
                Constants::STATUS_ENDED,
                Constants::STATUS_EXPIRED
            ])
            ->findAll();

        $tagsByVendorAndSub = [];
        foreach ($allTags as $tag) {
            $vId = $tag['vendor_id'];
            $sId = $tag['subsidiary_id'];
            if ($vId && $sId) {
                $tagsByVendorAndSub[$vId][$sId][] = $tag;
            }
        }
        $data['tagsByVendorAndSub'] = $tagsByVendorAndSub;

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

    public function exportAsExcel($vendorId = null)
    {
        helper('custom_functions');

        if ($vendorId !== null) {
            $subsidiaryId = $this->request->getGet('subsidiary_id');
            $tagsModel = new TagsModel();
            $tags = $tagsModel->getTagsByVendor($vendorId, $subsidiaryId);

            $vendorsModel = new VendorsModel();
            $vendor = $vendorsModel->find($vendorId);

            $subsidiariesModel = new SubsidiariesModel();
            $subsidiary = $subsidiariesModel->find($subsidiaryId);

            $filename = "Tags_[{$subsidiary['name']}][{$vendor['name']}]_" . date('Ymd') . ".xls";

            header('Content-Type: application/vnd.ms-excel; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta charset="UTF-8"></head>';
            echo '<body><table border="1">';

            // Header
            $headers = [ 'Subsidiary', 'Property Name', 'Tag Name', 'Days', 'AL End Date', 'Requested Date', 'Updated', 'Status' ];
            echo '<tr>';
            foreach ($headers as $header) {
                echo '<th style="background-color:#f2f2f2;">' . esc($header) . '</th>';
            }
            echo '</tr>';

            // Rows
            foreach ($tags as $tag) {
                $propertyId = $tag['property_id'];
                $propertiesModel = new PropertiesModel();
                $property = $propertiesModel->find($propertyId);

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

        // Export All Vendors List
        $vendorsModel = new VendorsModel();
        $tagsModel = new TagsModel();

        $filters = [
            'keyword'  => $this->request->getGet('keyword'),
            'id'       => $this->request->getGet('id'),
            'sort_by'  => $this->request->getGet('sort_by'),
            'sort_dir' => $this->request->getGet('sort_dir'),
        ];

        $vendors = $vendorsModel->filterVendors($filters);

        $allTags = $tagsModel->select('tags.id, tags.vendor_id, tags.status, properties.subsidiary_id')
            ->join('properties', 'properties.id = tags.property_id', 'left')
            ->whereNotIn('tags.status', [
                Constants::STATUS_DISABLED,
                Constants::STATUS_ENDED,
                Constants::STATUS_EXPIRED
            ])
            ->findAll();

        $tagsByVendorAndSub = [];
        foreach ($allTags as $tag) {
            $vId = $tag['vendor_id'];
            $sId = $tag['subsidiary_id'];
            if ($vId && $sId) {
                $tagsByVendorAndSub[$vId][$sId][] = $tag;
            }
        }

        $filename = 'Vendors_' . date('Y-m-d') . '.xls';

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="UTF-8"></head>';
        echo '<body><table border="1">';

        // Header
        $headers = ['#', 'Vendor'];
        foreach (Constants::SUBSIDIARIES as $subId => $subName) {
            $headers[] = $subName;
        }
        $headers[] = 'Total';

        echo '<tr>';
        foreach ($headers as $header) {
            echo '<th style="background-color:#f2f2f2; text-align:center;">' . esc($header) . '</th>';
        }
        echo '</tr>';

        // Rows
        foreach ($vendors as $vendor) {
            echo '<tr>';
            echo '<td style="text-align:center;">#' . esc($vendor['id']) . '</td>';
            echo '<td>' . esc($vendor['name']) . '</td>';
            $vendorTotal = 0;
            foreach (Constants::SUBSIDIARIES as $subId => $subName) {
                $count = count($tagsByVendorAndSub[$vendor['id']][$subId] ?? []);
                $vendorTotal += $count;
                echo '<td style="text-align:center;">' . ($count > 0 ? $count : '-') . '</td>';
            }
            echo '<td style="text-align:center; font-weight:bold;">' . ($vendorTotal > 0 ? $vendorTotal : '-') . '</td>';
            echo '</tr>';
        }

        echo '</table></body></html>';
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