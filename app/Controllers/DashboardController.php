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

        $tagsQuery = $db->table('tag_requests')
            ->select("DATE(created_at) as date, SUM(LENGTH(tags) - LENGTH(REPLACE(tags, ',', '')) + 1) as count", false)
            ->where('new_tag_ticket IS NOT NULL')
            ->where('new_tag_ticket !=', '')
            ->where('tags !=', '')
            ->where('created_at >=', $thirtyDaysAgo)
            ->where('deleted_at', null)
            ->groupBy("DATE(created_at)")
            ->orderBy("date", "ASC")
            ->get()
            ->getResultArray();

        $newRulesQuery = $db->table('tag_requests')
            ->select("DATE(updated_at) as date, SUM(LENGTH(tags) - LENGTH(REPLACE(tags, ',', '')) + 1) as count", false)
            ->where('new_rule_ticket IS NOT NULL')
            ->where('new_rule_ticket !=', '')
            ->where('tags !=', '')
            ->where('updated_at >=', $thirtyDaysAgo)
            ->where('deleted_at', null)
            ->groupBy("DATE(updated_at)")
            ->orderBy("date", "ASC")
            ->get()
            ->getResultArray();

        $pendingQuery = $db->table('tag_requests')
            ->select("DATE(created_at) as date, SUM(LENGTH(tags) - LENGTH(REPLACE(tags, ',', '')) + 1) as count", false)
            ->where('new_tag_ticket IS NOT NULL')
            ->where('new_tag_ticket !=', '')
            ->groupStart()
                ->where('new_rule_ticket', null)
                ->orWhere('new_rule_ticket', '')
            ->groupEnd()
            ->where('tags !=', '')
            ->where('created_at >=', $thirtyDaysAgo)
            ->where('deleted_at', null)
            ->groupBy("DATE(created_at)")
            ->orderBy("date", "ASC")
            ->get()
            ->getResultArray();

        $requestsQuery = $db->table('jira_history')
            ->select("DATE(jira_history.created_at) as date, COUNT(tags.status) as count", false)
            ->join('tags', 'tags.id = jira_history.tag_id', 'inner')
            ->where('jira_history.type', \App\Models\Constants::JIRA_TYPE_DATE_EXTENSION_RULE)
            ->where('jira_history.created_at >=', $thirtyDaysAgo)
            ->where('tags.deleted_at', null)
            ->groupBy("DATE(jira_history.created_at)")
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
        $newRulesData = [];
        $pendingData = [];

        for ($i = 29; $i >= 0; $i--) {
            $dateStr = date('Y-m-d', strtotime("-$i days"));
            $dates[] = date('d M', strtotime($dateStr));
            $tagsData[$dateStr] = 0;
            $requestsData[$dateStr] = 0;
            $activitiesData[$dateStr] = 0;
            $newRulesData[$dateStr] = 0;
            $pendingData[$dateStr] = 0;
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
        foreach ($newRulesQuery as $row) {
            if (isset($newRulesData[$row['date']])) {
                $newRulesData[$row['date']] = (int)$row['count'];
            }
        }
        foreach ($pendingQuery as $row) {
            if (isset($pendingData[$row['date']])) {
                $pendingData[$row['date']] = (int)$row['count'];
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
        $data['chart_new_rules'] = array_values($newRulesData);
        $data['chart_pending'] = array_values($pendingData);

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

        // 6. Tag Expiration Forecast (Next 45 Days)
        $forecastDates = [];
        $forecastData = [];
        for ($i = 0; $i < 45; $i++) {
            $dateStr = date('Y-m-d', strtotime("+$i days"));
            $forecastDates[] = date('d M', strtotime($dateStr));
            $forecastData[$dateStr] = 0;
        }

        $forecastQuery = $db->table('tags')
            ->select("al_end_date, COUNT(*) as count")
            ->whereIn('status', [
                Constants::STATUS_ENABLED,
                Constants::STATUS_PENDING_VERIFICATION,
                Constants::STATUS_UPDATED_IN_AL
            ])
            ->where('al_end_date >=', date('Y-m-d'))
            ->where('al_end_date <=', date('Y-m-d', strtotime('+44 days')))
            ->where('deleted_at', null)
            ->groupBy('al_end_date')
            ->get()
            ->getResultArray();

        foreach ($forecastQuery as $row) {
            if (isset($forecastData[$row['al_end_date']])) {
                $forecastData[$row['al_end_date']] = (int)$row['count'];
            }
        }

        $data['forecast_labels'] = $forecastDates;
        $data['forecast_data'] = array_values($forecastData);

        // 7. Tag Requests Status Distribution
        $requestStatusCounts = $db->table('tag_requests')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $requestStatusDistribution = [];
        foreach (Constants::STATUS_REQUESTS as $val => $label) {
            $requestStatusDistribution[$label] = 0;
        }
        foreach ($requestStatusCounts as $row) {
            $label = Constants::STATUS_REQUESTS[$row['status']] ?? 'Unknown';
            if (isset($requestStatusDistribution[$label])) {
                $requestStatusDistribution[$label] += (int)$row['count'];
            }
        }
        $data['request_status_distribution'] = $requestStatusDistribution;

        // 8. Activity Logs Analytics (by Status, Priority, Project)
        $activityStatusCounts = $db->table('activity_logs')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $activityStatusDistribution = [];
        foreach (Constants::ACTIVITY_LOGS_STATUS as $val => $label) {
            $activityStatusDistribution[$label] = 0;
        }
        foreach ($activityStatusCounts as $row) {
            $label = Constants::ACTIVITY_LOGS_STATUS[$row['status']] ?? 'Unknown';
            if (isset($activityStatusDistribution[$label])) {
                $activityStatusDistribution[$label] += (int)$row['count'];
            }
        }
        $data['activity_status_distribution'] = $activityStatusDistribution;

        $activityPriorityCounts = $db->table('activity_logs')
            ->select('priority, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('priority')
            ->get()
            ->getResultArray();

        $activityPriorityDistribution = [];
        foreach (Constants::PRIORITIES as $val => $label) {
            $activityPriorityDistribution[$label] = 0;
        }
        foreach ($activityPriorityCounts as $row) {
            $label = Constants::PRIORITIES[$row['priority']] ?? 'Unknown';
            if (isset($activityPriorityDistribution[$label])) {
                $activityPriorityDistribution[$label] += (int)$row['count'];
            }
        }
        $data['activity_priority_distribution'] = $activityPriorityDistribution;

        $activityProjectCounts = $db->table('activity_logs')
            ->select('project_id, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('project_id')
            ->get()
            ->getResultArray();

        $activityProjectDistribution = [];
        foreach (Constants::PROJECTS as $val => $label) {
            $activityProjectDistribution[$label] = 0;
        }
        foreach ($activityProjectCounts as $row) {
            $label = Constants::PROJECTS[$row['project_id']] ?? 'Unknown';
            if (isset($activityProjectDistribution[$label])) {
                $activityProjectDistribution[$label] += (int)$row['count'];
            }
        }
        $data['activity_project_distribution'] = $activityProjectDistribution;

        return view('dashboard/index', $data);
    }
}
