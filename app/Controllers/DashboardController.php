<?php

namespace App\Controllers;

use App\Models\TagsModel;
use App\Models\TagRequestsModel;
use App\Models\ActivityLogsModel;
use App\Models\Constants;

class DashboardController extends BaseController
{
    public function index()
    {
        $tagsModel = new TagsModel();
        $tagRequestsModel = new TagRequestsModel();
        $activityLogsModel = new ActivityLogsModel();
        $db = \Config\Database::connect();

        // 1. Metric Counts
        $data['active_tags'] = $tagsModel->countActiveTags();
        $data['expiring_tags'] = $tagsModel->countExpiringTags();
        $data['pending_verification'] = $tagsModel->countPendingVerificationTags();
        $data['total_tags'] = $tagsModel->countAllTags();

        $data['pending_requests'] = $tagRequestsModel
            ->whereNotIn('status', [
                Constants::STATUS_REQUEST_PUBLISHED,
                Constants::STATUS_REQUEST_REJECTED
            ])
            ->countAllResults();

        $data['total_requests'] = $tagRequestsModel->countAllRequests();

        $data['activities_last_30_days'] = $activityLogsModel
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->countAllResults();

        // 2. 30-Day Activity Trends
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));

        $tagsQuery = $db->table('tags')
            ->select("DATE(created_at) as date, COUNT(*) as count")
            ->where('created_at >=', $thirtyDaysAgo)
            ->where('deleted_at', null)
            ->groupBy("DATE(created_at)")
            ->orderBy("date", "ASC")
            ->get()
            ->getResultArray();

        $requestsQuery = $db->table('tag_requests')
            ->select("DATE(created_at) as date, COUNT(*) as count")
            ->where('created_at >=', $thirtyDaysAgo)
            ->where('deleted_at', null)
            ->groupBy("DATE(created_at)")
            ->orderBy("date", "ASC")
            ->get()
            ->getResultArray();

        $activitiesQuery = $db->table('activity_logs')
            ->select("DATE(created_at) as date, COUNT(*) as count")
            ->where('created_at >=', $thirtyDaysAgo)
            ->where('deleted_at', null)
            ->groupBy("DATE(created_at)")
            ->orderBy("date", "ASC")
            ->get()
            ->getResultArray();

        // Setup 30-day bucket
        $dates = [];
        $tagsData = [];
        $requestsData = [];
        $activitiesData = [];

        for ($i = 29; $i >= 0; $i--) {
            $dateStr = date('Y-m-d', strtotime("-$i days"));
            $dates[] = date('d M', strtotime($dateStr));
            $tagsData[$dateStr] = 0;
            $requestsData[$dateStr] = 0;
            $activitiesData[$dateStr] = 0;
        }

        foreach ($tagsQuery as $row) {
            if (isset($tagsData[$row['date']])) {
                $tagsData[$row['date']] = (int)$row['count'];
            }
        }
        foreach ($requestsQuery as $row) {
            if (isset($requestsData[$row['date']])) {
                $requestsData[$row['date']] = (int)$row['count'];
            }
        }
        foreach ($activitiesQuery as $row) {
            if (isset($activitiesData[$row['date']])) {
                $activitiesData[$row['date']] = (int)$row['count'];
            }
        }

        $data['chart_labels'] = $dates;
        $data['chart_tags'] = array_values($tagsData);
        $data['chart_requests'] = array_values($requestsData);
        $data['chart_activities'] = array_values($activitiesData);

        // 3. Tag Status Distribution
        $statusCounts = $db->table('tags')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $statusDistribution = [];
        foreach (Constants::STATUS as $val => $label) {
            if ($val == Constants::STATUS_DEFAULT || $val == Constants::STATUS_ALL) {
                continue;
            }
            $statusDistribution[$label] = 0;
        }
        foreach ($statusCounts as $row) {
            $label = Constants::STATUS[$row['status']] ?? 'Unknown';
            if ($label !== 'Default') {
                $statusDistribution[$label] = (int)$row['count'];
            }
        }
        $data['status_distribution'] = $statusDistribution;

        // 4. Subsidiary Distribution
        $subsidiaryCounts = $db->table('tags')
            ->select('properties.subsidiary_id, COUNT(tags.id) as count')
            ->join('properties', 'properties.id = tags.property_id', 'left')
            ->where('tags.deleted_at', null)
            ->groupBy('properties.subsidiary_id')
            ->get()
            ->getResultArray();

        $subsidiaryDistribution = [];
        foreach (Constants::SUBSIDIARIES as $val => $label) {
            $subsidiaryDistribution[$label] = 0;
        }
        foreach ($subsidiaryCounts as $row) {
            $label = Constants::SUBSIDIARIES[$row['subsidiary_id']] ?? null;
            if ($label) {
                $subsidiaryDistribution[$label] = (int)$row['count'];
            }
        }
        $data['subsidiary_distribution'] = $subsidiaryDistribution;

        // 5. Vendor Distribution (Top 10)
        $vendorCounts = $db->table('tags')
            ->select('vendors.name as vendor_name, COUNT(tags.id) as count')
            ->join('vendors', 'vendors.id = tags.vendor_id', 'left')
            ->where('tags.deleted_at', null)
            ->groupBy('tags.vendor_id')
            ->orderBy('count', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        $vendorDistribution = [];
        foreach ($vendorCounts as $row) {
            $name = $row['vendor_name'] ?: 'Unknown';
            $vendorDistribution[$name] = (int)$row['count'];
        }
        $data['vendor_distribution'] = $vendorDistribution;

        return view('dashboard/index', $data);
    }
}
