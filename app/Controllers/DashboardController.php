<?php

namespace App\Controllers;

use App\Models\TagsModel;
use App\Models\TagRequestsModel;
use App\Models\TasksModel;
use App\Models\Constants;

class DashboardController extends BaseController
{
    public function index()
    {
        $tagsModel = new TagsModel();
        $tagRequestsModel = new TagRequestsModel();
        $tasksModel = new TasksModel();
        $db = \Config\Database::connect();

        // Date Filter Setup
        $startDateParam = $this->request->getGet('start_date');
        $endDateParam = $this->request->getGet('end_date');
        $dateRange = $this->request->getGet('date_range');

        if (empty($dateRange)) {
            $dateRange = '30d';
        }

        $todayStr = date('Y-m-d');

        if ($dateRange === '7d') {
            $startDate = date('Y-m-d', strtotime('-6 days'));
            $endDate = $todayStr;
        } elseif ($dateRange === '3m') {
            $startDate = date('Y-m-d', strtotime('-3 months'));
            $endDate = $todayStr;
        } elseif ($dateRange === '1y') {
            $startDate = date('Y-m-d', strtotime('-1 year'));
            $endDate = $todayStr;
        } elseif ($dateRange === 'custom' && !empty($startDateParam)) {
            $startDate = $startDateParam;
            $endDate = !empty($endDateParam) ? $endDateParam : $todayStr;
        } else {
            $dateRange = '30d';
            $startDate = date('Y-m-d', strtotime('-29 days'));
            $endDate = $todayStr;
        }

        // Cap endDate to today to strictly prevent future dates
        if (strtotime($endDate) > strtotime($todayStr)) {
            $endDate = $todayStr;
        }
        
        // Ensure startDate is not after endDate
        if (strtotime($startDate) > strtotime($endDate)) {
            $startDate = $endDate;
        }

        $startDateQuery = $startDate . ' 00:00:00';
        $endDateQuery = $endDate . ' 23:59:59';

        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        $data['date_range'] = $dateRange;

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

        $data['total_requests'] = $tagRequestsModel
            ->where('created_at >=', $startDateQuery)
            ->where('created_at <=', $endDateQuery)
            ->countAllResults();

        // System Health Index calculation
        $today = date('Y-m-d');
        $active_tags_list = $tagsModel->select('id, name, status, al_end_date')
            ->whereIn('status', [
                Constants::STATUS_ENABLED,
                Constants::STATUS_UPDATED_IN_AL,
                Constants::STATUS_PENDING_VERIFICATION
            ])
            ->where('deleted_at', null)
            ->findAll();

        $critical_active = 0;       // Expired or < 7 days, NOT pending verification
        $critical_pending = 0;      // Expired or < 7 days, IS pending verification
        $attention_active = 0;      // 7-9 days, NOT pending verification
        $attention_pending = 0;     // 7-9 days, IS pending verification
        $normal_renewal = 0;        // 10-15 days
        $safe_tags = 0;             // > 15 days

        $total_penalty = 0;
        $active_tags_count = count($active_tags_list);

        foreach ($active_tags_list as $tag) {
            $endDate = $tag['al_end_date'];
            $status = (int)$tag['status'];

            if (empty($endDate)) {
                $days_remaining = 999;
            } else {
                $days_remaining = (int)round((strtotime($endDate) - strtotime($today)) / (60 * 60 * 24));
            }

            $is_pending = ($status === Constants::STATUS_PENDING_VERIFICATION);

            if ($days_remaining < 7) {
                if ($is_pending) {
                    $critical_pending++;
                    if ($days_remaining < 0) {
                        $total_penalty += 0.6; // Expired but pending verification
                    } else {
                        $total_penalty += 0.4; // Critical days left, pending verification
                    }
                } else {
                    $critical_active++;
                    if ($days_remaining < 0) {
                        $total_penalty += 1.0; // Expired and not renewed
                    } else {
                        $total_penalty += 0.8; // Critical days left, not renewed
                    }
                }
            } elseif ($days_remaining < 10) {
                if ($is_pending) {
                    $attention_pending++;
                    $total_penalty += 0.15; // Needs attention soon, but renewed
                } else {
                    $attention_active++;
                    $total_penalty += 0.3; // Needs attention soon, not renewed
                }
            } elseif ($days_remaining <= 15) {
                $normal_renewal++;
            } else {
                $safe_tags++;
            }
        }

        $sensitivity_multiplier = 10.0;
        if ($active_tags_count > 0) {
            $penalty_ratio = $total_penalty / $active_tags_count;
            $health_score = (int)round(100 * (1 - ($penalty_ratio * $sensitivity_multiplier)));
            $health_score = max(0, min(100, $health_score));
        } else {
            $health_score = 100;
        }

        $data['health_score'] = $health_score;
        $data['critical_active'] = $critical_active;
        $data['critical_pending'] = $critical_pending;
        $data['attention_active'] = $attention_active;
        $data['attention_pending'] = $attention_pending;
        $data['normal_renewal'] = $normal_renewal;
        $data['safe_tags'] = $safe_tags;
        
        // Backward compatibility for view variables
        $data['expired_active_count'] = $critical_active + $critical_pending;
        $data['expiring_soon_count'] = $attention_active + $attention_pending;

        $data['activities_last_30_days'] = $tasksModel
            ->where('created_at >=', $startDateQuery)
            ->where('created_at <=', $endDateQuery)
            ->countAllResults();

        // 2. Activity Trends
        $tagsQuery = $db->table('tag_requests')
            ->select("DATE(COALESCE(jh.created_at, tag_requests.created_at)) as date, SUM(LENGTH(tag_requests.tags) - LENGTH(REPLACE(tag_requests.tags, ',', '')) + 1) as count", false)
            ->join('(SELECT ticket_id, MIN(created_at) as created_at FROM jira_history GROUP BY ticket_id) jh', 'jh.ticket_id = tag_requests.new_tag_ticket', 'left')
            ->where('tag_requests.new_tag_ticket IS NOT NULL')
            ->where('tag_requests.new_tag_ticket !=', '')
            ->where('tag_requests.tags !=', '')
            ->where('COALESCE(jh.created_at, tag_requests.created_at) >=', $startDateQuery)
            ->where('COALESCE(jh.created_at, tag_requests.created_at) <=', $endDateQuery)
            ->where('tag_requests.deleted_at', null)
            ->groupBy("DATE(COALESCE(jh.created_at, tag_requests.created_at))")
            ->orderBy("date", "ASC")
            ->get()
            ->getResultArray();

        $newRulesQuery = $db->table('tag_requests')
            ->select("DATE(COALESCE(jh.created_at, tag_requests.created_at)) as date, SUM(LENGTH(tag_requests.tags) - LENGTH(REPLACE(tag_requests.tags, ',', '')) + 1) as count", false)
            ->join('(SELECT ticket_id, MIN(created_at) as created_at FROM jira_history GROUP BY ticket_id) jh', 'jh.ticket_id = tag_requests.new_rule_ticket', 'left')
            ->where('tag_requests.new_rule_ticket IS NOT NULL')
            ->where('tag_requests.new_rule_ticket !=', '')
            ->where('tag_requests.tags !=', '')
            ->where('COALESCE(jh.created_at, tag_requests.created_at) >=', $startDateQuery)
            ->where('COALESCE(jh.created_at, tag_requests.created_at) <=', $endDateQuery)
            ->where('tag_requests.deleted_at', null)
            ->groupBy("DATE(COALESCE(jh.created_at, tag_requests.created_at))")
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
            ->where('created_at >=', $startDateQuery)
            ->where('created_at <=', $endDateQuery)
            ->where('deleted_at', null)
            ->groupBy("DATE(created_at)")
            ->orderBy("date", "ASC")
            ->get()
            ->getResultArray();

        $requestsQuery = $db->table('jira_history')
            ->select("DATE(jira_history.created_at) as date, COUNT(tags.status) as count", false)
            ->join('tags', 'tags.id = jira_history.tag_id', 'inner')
            ->where('jira_history.type', \App\Models\Constants::JIRA_TYPE_DATE_EXTENSION_RULE)
            ->where('jira_history.created_at >=', $startDateQuery)
            ->where('jira_history.created_at <=', $endDateQuery)
            ->where('tags.deleted_at', null)
            ->groupBy("DATE(jira_history.created_at)")
            ->orderBy("date", "ASC")
            ->get()
            ->getResultArray();

        $activitiesQuery = $db->table('activity_logs')
            ->select("DATE(created_at) as date, COUNT(*) as count")
            ->where('created_at >=', $startDateQuery)
            ->where('created_at <=', $endDateQuery)
            ->where('deleted_at', null)
            ->groupBy("DATE(created_at)")
            ->orderBy("date", "ASC")
            ->get()
            ->getResultArray();

        // Setup dynamic date bucket
        $dates = [];
        $datesFull = [];
        $tagsData = [];
        $requestsData = [];
        $activitiesData = [];
        $newRulesData = [];
        $pendingData = [];

        $startDateTime = new \DateTime($startDate);
        $endDateTime = new \DateTime($endDate);
        
        // Prevent huge ranges from breaking memory (cap at 365 days)
        $interval = $startDateTime->diff($endDateTime);
        if ($interval->days > 366) {
            $startDateTime = (clone $endDateTime)->modify('-365 days');
        }

        $todayObj = new \DateTime(date('Y-m-d'));
        if ($endDateTime > $todayObj) {
            $endDateTime = clone $todayObj;
        }

        $dateRangePeriod = new \DatePeriod($startDateTime, new \DateInterval('P1D'), (clone $endDateTime)->modify('+1 day'));

        foreach ($dateRangePeriod as $dt) {
            if ($dt > $todayObj) continue; // Double check no future dates are added
            
            $dateStr = $dt->format('Y-m-d');
            $dates[] = $dt->format('d M y');
            $datesFull[] = $dateStr;
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
        $data['chart_labels_full'] = $datesFull;
        $data['chart_tags'] = array_values($tagsData);
        $data['chart_requests'] = array_values($requestsData);
        $data['chart_activities'] = array_values($activitiesData);
        $data['chart_new_rules'] = array_values($newRulesData);
        $data['chart_pending'] = array_values($pendingData);

        // 3. Tag Status Operations Counts
        // Run a migration update for existing renewal tasks to set their type to Constants::ACTIVITY_TYPE_RENEWAL (6)
        $db->query("UPDATE activity_logs 
                    SET type = " . Constants::ACTIVITY_TYPE_RENEWAL . " 
                    WHERE type = " . Constants::ACTIVITY_TYPE_REQUEST . " 
                      AND (title LIKE 'Tag Renewals%' OR title LIKE '%Tag Renewals%')");

        $data['blocked_renewal_requests'] = $tasksModel
            ->where('type', Constants::ACTIVITY_TYPE_RENEWAL)
            ->where('status', Constants::ACTIVITY_LOGS_STATUS_BLOCKED)
            ->where('created_at >=', $startDateQuery)
            ->where('created_at <=', $endDateQuery)
            ->countAllResults();

        $data['pending_review_renewals'] = $tagsModel
            ->where('status', Constants::STATUS_PENDING_VERIFICATION)
            ->where('updated_at >=', $startDateQuery)
            ->where('updated_at <=', $endDateQuery)
            ->countAllResults();

        $data['pending_new_tag_requests'] = $tagRequestsModel
            ->where('type', Constants::JIRA_TYPE_NEW_TAG)
            ->whereNotIn('status', [
                Constants::STATUS_REQUEST_PUBLISHED,
                Constants::STATUS_REQUEST_REJECTED,
                Constants::STATUS_REQUEST_NEW_RULE_REQUEST_SUBMITTED
            ])
            ->where('created_at >=', $startDateQuery)
            ->where('created_at <=', $endDateQuery)
            ->countAllResults();

        $data['pending_new_rule_requests'] = $tagRequestsModel
            ->groupStart()
                ->where('type', Constants::JIRA_TYPE_NEW_RULE)
                ->whereNotIn('status', [
                    Constants::STATUS_REQUEST_PUBLISHED,
                    Constants::STATUS_REQUEST_REJECTED
                ])
            ->groupEnd()
            ->orGroupStart()
                ->where('type', Constants::JIRA_TYPE_NEW_TAG)
                ->where('status', Constants::STATUS_REQUEST_NEW_RULE_REQUEST_SUBMITTED)
            ->groupEnd()
            ->where('created_at >=', $startDateQuery)
            ->where('created_at <=', $endDateQuery)
            ->countAllResults();

        // 4. Subsidiary Distribution
        $subsidiaryCounts = $db->table('tags')
            ->select('properties.subsidiary_id, COUNT(tags.id) as count')
            ->join('properties', 'properties.id = tags.property_id', 'left')
            ->where('tags.deleted_at', null)
            ->groupBy('properties.subsidiary_id')
            ->get()
            ->getResultArray();

        $subsidiaryDistribution = [];
        $subsidiaryIds = [];
        foreach (Constants::SUBSIDIARIES as $val => $label) {
            $subsidiaryDistribution[$label] = 0;
            $subsidiaryIds[$label] = $val;
        }
        foreach ($subsidiaryCounts as $row) {
            $label = Constants::SUBSIDIARIES[$row['subsidiary_id']] ?? null;
            if ($label) {
                $subsidiaryDistribution[$label] = (int)$row['count'];
            }
        }
        $data['subsidiary_distribution'] = $subsidiaryDistribution;
        $data['subsidiary_ids'] = $subsidiaryIds;

        // 5. Vendor Distribution (Top 10)
        $vendorCounts = $db->table('tags')
            ->select('tags.vendor_id, vendors.name as vendor_name, COUNT(tags.id) as count')
            ->join('vendors', 'vendors.id = tags.vendor_id', 'left')
            ->where('tags.deleted_at', null)
            ->groupBy('tags.vendor_id')
            ->orderBy('count', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        $vendorDistribution = [];
        $vendorIds = [];
        foreach ($vendorCounts as $row) {
            $name = $row['vendor_name'] ?: 'Unknown';
            $vendorDistribution[$name] = (int)$row['count'];
            $vendorIds[$name] = $row['vendor_id'];
        }
        $data['vendor_distribution'] = $vendorDistribution;
        $data['vendor_ids'] = $vendorIds;

        // 6. Tag Expiration Forecast (Next 45 Days)
        $forecastDates = [];
        $forecastDatesFull = [];
        $forecastExpiringData = [];
        $forecastPendingData = [];
        for ($i = 0; $i < 45; $i++) {
            $dateStr = date('Y-m-d', strtotime("+$i days"));
            $forecastDates[] = date('d M', strtotime($dateStr));
            $forecastDatesFull[] = $dateStr;
            $forecastExpiringData[$dateStr] = 0;
            $forecastPendingData[$dateStr] = 0;
        }

        $forecastQuery = $db->table('tags')
            ->select("al_end_date, status, COUNT(*) as count")
            ->whereIn('status', [
                Constants::STATUS_ENABLED,
                Constants::STATUS_PENDING_VERIFICATION,
                Constants::STATUS_UPDATED_IN_AL
            ])
            ->where('al_end_date >=', date('Y-m-d'))
            ->where('al_end_date <=', date('Y-m-d', strtotime('+44 days')))
            ->where('deleted_at', null)
            ->groupBy('al_end_date, status')
            ->get()
            ->getResultArray();

        foreach ($forecastQuery as $row) {
            $dateStr = $row['al_end_date'];
            if (isset($forecastExpiringData[$dateStr])) {
                if ((int)$row['status'] === Constants::STATUS_PENDING_VERIFICATION) {
                    $forecastPendingData[$dateStr] += (int)$row['count'];
                } else {
                    $forecastExpiringData[$dateStr] += (int)$row['count'];
                }
            }
        }

        $data['forecast_labels'] = $forecastDates;
        $data['forecast_labels_full'] = $forecastDatesFull;
        $data['forecast_data_expiring'] = array_values($forecastExpiringData);
        $data['forecast_data_pending'] = array_values($forecastPendingData);

        // 7. Tag Requests Status Distribution
        $requestStatusCounts = $db->table('tag_requests')
            ->select('status, COUNT(*) as count')
            ->where('created_at >=', $startDateQuery)
            ->where('created_at <=', $endDateQuery)
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
            ->where('created_at >=', $startDateQuery)
            ->where('created_at <=', $endDateQuery)
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
            ->where('created_at >=', $startDateQuery)
            ->where('created_at <=', $endDateQuery)
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

        $activityTeamCounts = $db->table('activity_logs')
            ->select('team_id, COUNT(*) as count')
            ->where('created_at >=', $startDateQuery)
            ->where('created_at <=', $endDateQuery)
            ->where('deleted_at', null)
            ->groupBy('team_id')
            ->get()
            ->getResultArray();

        $activityTeamDistribution = [];
        foreach (Constants::TEAMS as $val => $label) {
            $activityTeamDistribution[$label] = 0;
        }
        foreach ($activityTeamCounts as $row) {
            $label = Constants::TEAMS[$row['team_id']] ?? 'Unknown';
            if (isset($activityTeamDistribution[$label])) {
                $activityTeamDistribution[$label] += (int)$row['count'];
            }
        }
        $data['activity_team_distribution'] = $activityTeamDistribution;

        return view('dashboard/index', $data);
    }

    public function getTaskTags()
    {
        helper('custom_functions');

        $date = $this->request->getGet('date');
        $category = $this->request->getGet('category');

        if (empty($date) || empty($category)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Missing date or category parameter.'
            ]);
        }

        $db = \Config\Database::connect();
        $tags = [];
        $activities = [];

        switch ($category) {
            case 'new_requests':
                $requests = $db->table('tag_requests')
                    ->select('tag_requests.tags, tag_requests.new_tag_ticket as ticket_id')
                    ->join('(SELECT ticket_id, MIN(created_at) as created_at FROM jira_history GROUP BY ticket_id) jh', 'jh.ticket_id = tag_requests.new_tag_ticket', 'left')
                    ->where('tag_requests.new_tag_ticket IS NOT NULL')
                    ->where('tag_requests.new_tag_ticket !=', '')
                    ->where('tag_requests.tags !=', '')
                    ->where('DATE(COALESCE(jh.created_at, tag_requests.created_at))', $date)
                    ->where('tag_requests.deleted_at', null)
                    ->get()
                    ->getResultArray();
                break;

            case 'new_implementations':
                $requests = $db->table('tag_requests')
                    ->select('tag_requests.tags, tag_requests.new_rule_ticket as ticket_id')
                    ->join('(SELECT ticket_id, MIN(created_at) as created_at FROM jira_history GROUP BY ticket_id) jh', 'jh.ticket_id = tag_requests.new_rule_ticket', 'left')
                    ->where('tag_requests.new_rule_ticket IS NOT NULL')
                    ->where('tag_requests.new_rule_ticket !=', '')
                    ->where('tag_requests.tags !=', '')
                    ->where('DATE(COALESCE(jh.created_at, tag_requests.created_at))', $date)
                    ->where('tag_requests.deleted_at', null)
                    ->get()
                    ->getResultArray();
                break;

            case 'pending_requests':
                $requests = $db->table('tag_requests')
                    ->select('tags, new_tag_ticket as ticket_id')
                    ->where('new_tag_ticket IS NOT NULL')
                    ->where('new_tag_ticket !=', '')
                    ->groupStart()
                        ->where('new_rule_ticket', null)
                        ->orWhere('new_rule_ticket', '')
                    ->groupEnd()
                    ->where('tags !=', '')
                    ->where('DATE(created_at)', $date)
                    ->where('deleted_at', null)
                    ->get()
                    ->getResultArray();
                break;

            case 'date_extensions':
                $extensions = $db->table('jira_history')
                    ->select('jira_history.tag_id, jira_history.ticket_id')
                    ->join('tags', 'tags.id = jira_history.tag_id', 'inner')
                    ->where('jira_history.type', Constants::JIRA_TYPE_DATE_EXTENSION_RULE)
                    ->where('DATE(jira_history.created_at)', $date)
                    ->where('tags.deleted_at', null)
                    ->get()
                    ->getResultArray();

                $tagIds = [];
                $ticketMap = [];
                foreach ($extensions as $ext) {
                    $tagIds[] = (int)$ext['tag_id'];
                    $ticketMap[$ext['tag_id']] = $ext['ticket_id'];
                }
                $tagIds = array_unique($tagIds);
                break;

            case 'expiring_tags':
                $expiringQuery = $db->table('tags')
                    ->select('id')
                    ->whereIn('status', [
                        Constants::STATUS_ENABLED,
                        Constants::STATUS_UPDATED_IN_AL
                    ])
                    ->where('al_end_date', $date)
                    ->where('deleted_at', null)
                    ->get()
                    ->getResultArray();

                $tagIds = [];
                $ticketMap = [];
                foreach ($expiringQuery as $eq) {
                    $tagIds[] = (int)$eq['id'];
                }
                break;

            case 'pending_verification_tags':
                $pendingQuery = $db->table('tags')
                    ->select('id')
                    ->where('status', Constants::STATUS_PENDING_VERIFICATION)
                    ->where('al_end_date', $date)
                    ->where('deleted_at', null)
                    ->get()
                    ->getResultArray();

                $tagIds = [];
                $ticketMap = [];
                foreach ($pendingQuery as $pq) {
                    $tagIds[] = (int)$pq['id'];
                }
                break;

            case 'activities':
                $logs = $db->table('activity_logs')
                    ->select('id, title, ticket_id, status, priority, created_at')
                    ->where('DATE(created_at)', $date)
                    ->where('deleted_at', null)
                    ->get()
                    ->getResultArray();

                $ticketIds = [];
                foreach ($logs as $log) {
                    if (!empty($log['ticket_id'])) {
                        $ticketIds[] = $log['ticket_id'];
                    }
                }
                $ticketIds = array_unique($ticketIds);

                $ticketToTags = [];
                $tagIds = [];

                if (!empty($ticketIds)) {
                    // Fetch tag requests matching tickets
                    $trTags = $db->table('tag_requests')
                        ->select('tags, new_tag_ticket, new_rule_ticket')
                        ->groupStart()
                            ->whereIn('new_tag_ticket', $ticketIds)
                            ->orWhereIn('new_rule_ticket', $ticketIds)
                        ->groupEnd()
                        ->where('deleted_at', null)
                        ->get()
                        ->getResultArray();

                    foreach ($trTags as $tr) {
                        $ids = explode(',', $tr['tags']);
                        foreach ($ids as $id) {
                            $id = (int)trim($id);
                            if ($id > 0) {
                                $tagIds[] = $id;
                                if (!empty($tr['new_tag_ticket'])) {
                                    $ticketToTags[$tr['new_tag_ticket']][] = $id;
                                }
                                if (!empty($tr['new_rule_ticket'])) {
                                    $ticketToTags[$tr['new_rule_ticket']][] = $id;
                                }
                            }
                        }
                    }

                    // Fetch jira history matching tickets
                    $jhTags = $db->table('jira_history')
                        ->select('tag_id, ticket_id')
                        ->whereIn('ticket_id', $ticketIds)
                        ->get()
                        ->getResultArray();

                    foreach ($jhTags as $jh) {
                        $id = (int)$jh['tag_id'];
                        if ($id > 0) {
                            $tagIds[] = $id;
                            $ticketToTags[$jh['ticket_id']][] = $id;
                        }
                    }
                }

                $tagIds = array_unique($tagIds);
                $tagsDetail = [];
                if (!empty($tagIds)) {
                    $tagsQuery = $db->table('tags')
                        ->select('tags.id, tags.name, tags.al_end_date, tags.status, properties.name as property_name, properties.subsidiary_id, vendors.name as vendor_name')
                        ->join('properties', 'properties.id = tags.property_id', 'left')
                        ->join('vendors', 'vendors.id = tags.vendor_id', 'left')
                        ->whereIn('tags.id', $tagIds)
                        ->where('tags.deleted_at', null)
                        ->get()
                        ->getResultArray();

                    foreach ($tagsQuery as $t) {
                        $t['status_label'] = Constants::STATUS[$t['status']] ?? 'Unknown';
                        $t['status_class'] = status_class($t['status']);
                        $t['subsidiary'] = Constants::SUBSIDIARIES[$t['subsidiary_id']] ?? 'Unknown';
                        $t['jira_link'] = null;
                        $tagsDetail[$t['id']] = $t;
                    }
                }

                foreach ($logs as $log) {
                    $logTags = [];
                    if (!empty($log['ticket_id']) && isset($ticketToTags[$log['ticket_id']])) {
                        $uniqueLogTagIds = array_unique($ticketToTags[$log['ticket_id']]);
                        foreach ($uniqueLogTagIds as $tId) {
                            if (isset($tagsDetail[$tId])) {
                                $tagInfo = $tagsDetail[$tId];
                                $tagInfo['ticket_id'] = $log['ticket_id'];
                                $tagInfo['jira_link'] = Constants::JIRA_LINK . $log['ticket_id'];
                                $logTags[] = $tagInfo;
                            }
                        }
                    }
                    $activities[] = [
                        'id' => $log['id'],
                        'title' => $log['title'],
                        'ticket_id' => $log['ticket_id'],
                        'jira_link' => $log['ticket_id'] ? Constants::JIRA_LINK . $log['ticket_id'] : null,
                        'status' => Constants::ACTIVITY_LOGS_STATUS[$log['status']] ?? 'Unknown',
                        'status_class' => status_activity_logs_class($log['status']),
                        'priority' => Constants::PRIORITIES[$log['priority']] ?? 'Unknown',
                        'priority_class' => priority_class($log['priority']),
                        'tags' => $logTags
                    ];
                }
                break;
        }

        // For requests, collect tags
        if (isset($requests)) {
            $tagIds = [];
            $ticketMap = [];
            foreach ($requests as $r) {
                if (!empty($r['tags'])) {
                    $ids = explode(',', $r['tags']);
                    foreach ($ids as $id) {
                        $id = (int)trim($id);
                        if ($id > 0) {
                            $tagIds[] = $id;
                            if (!empty($r['ticket_id'])) {
                                $ticketMap[$id] = $r['ticket_id'];
                            }
                        }
                    }
                }
            }
            $tagIds = array_unique($tagIds);
        }

        // Fill in missing ticket mappings from jira_history or tag_requests
        if (!empty($tagIds)) {
            $missingIds = [];
            foreach ($tagIds as $tid) {
                if (empty($ticketMap[$tid])) {
                    $missingIds[] = $tid;
                }
            }
            
            if (!empty($missingIds)) {
                // Try from jira_history (e.g. most recent rule extension/new tag ticket)
                $jhTickets = $db->table('jira_history')
                    ->select('tag_id, ticket_id')
                    ->whereIn('tag_id', $missingIds)
                    ->orderBy('id', 'DESC')
                    ->get()
                    ->getResultArray();
                foreach ($jhTickets as $jh) {
                    if (empty($ticketMap[$jh['tag_id']])) {
                        $ticketMap[$jh['tag_id']] = $jh['ticket_id'];
                    }
                }
            }
            
            $missingIds = [];
            foreach ($tagIds as $tid) {
                if (empty($ticketMap[$tid])) {
                    $missingIds[] = $tid;
                }
            }
            
            if (!empty($missingIds)) {
                // Try from tag_requests
                foreach ($missingIds as $tid) {
                    $tr = $db->table('tag_requests')
                        ->select('new_rule_ticket, new_tag_ticket')
                        ->groupStart()
                            ->where("FIND_IN_SET('{$tid}', tags) > 0")
                        ->groupEnd()
                        ->where('deleted_at', null)
                        ->orderBy('id', 'DESC')
                        ->limit(1)
                        ->get()
                        ->getRowArray();
                    if ($tr) {
                        $ticketMap[$tid] = $tr['new_rule_ticket'] ?: $tr['new_tag_ticket'];
                    }
                }
            }
        }

        if ($category !== 'activities' && !empty($tagIds)) {
            $tagsQuery = $db->table('tags')
                ->select('tags.id, tags.name, tags.al_end_date, tags.status, properties.name as property_name, properties.subsidiary_id, vendors.name as vendor_name')
                ->join('properties', 'properties.id = tags.property_id', 'left')
                ->join('vendors', 'vendors.id = tags.vendor_id', 'left')
                ->whereIn('tags.id', $tagIds)
                ->where('tags.deleted_at', null)
                ->get()
                ->getResultArray();

            foreach ($tagsQuery as $t) {
                $t['ticket_id'] = $ticketMap[$t['id']] ?? null;
                $t['jira_link'] = $t['ticket_id'] ? Constants::JIRA_LINK . $t['ticket_id'] : null;
                $t['status_label'] = Constants::STATUS[$t['status']] ?? 'Unknown';
                $t['status_class'] = status_class($t['status']);
                $t['subsidiary'] = Constants::SUBSIDIARIES[$t['subsidiary_id']] ?? 'Unknown';
                $tags[] = $t;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'date' => date('d M Y', strtotime($date)),
            'category' => $category,
            'tags' => $tags,
        ]);
    }

    public function getWelcomeMessage()
    {
        $userName = session()->get('user_name') ?? session()->get('name');
        $userId = session()->get('user_id');
        $userRole = session()->get('user_role');
        
        $teamName = \App\Models\Constants::USER_ROLES[$userRole] ?? 'Unknown Team';
        if (isset(\App\Models\Constants::USER_TEAMS[$userId])) {
            $teamId = \App\Models\Constants::USER_TEAMS[$userId];
            $teamName = \App\Models\Constants::TEAMS[$teamId] ?? $teamName;
        }

        // Count Tasks
        $tasksModel = new \App\Models\TasksModel();
        
        // In Progress (Investigation, Implementation, QA, Review)
        $inProgressTasks = $tasksModel
            ->where('assignee', $userId)
            ->whereIn('status', [
                \App\Models\Constants::ACTIVITY_LOGS_STATUS_INVESTIGATION,
                \App\Models\Constants::ACTIVITY_LOGS_STATUS_IMPLEMENTATION,
                \App\Models\Constants::ACTIVITY_LOGS_STATUS_QA,
                \App\Models\Constants::ACTIVITY_LOGS_STATUS_REVIEW
            ])
            ->where('deleted_at', null)
            ->countAllResults();
            
        // Blocked
        $blockedTasks = $tasksModel
            ->where('assignee', $userId)
            ->where('status', \App\Models\Constants::ACTIVITY_LOGS_STATUS_BLOCKED)
            ->where('deleted_at', null)
            ->countAllResults();

        $promptParts = [
            "Generate a highly inspirational, welcoming, and encouraging welcome message for a dashboard.",
            "The user's name is {$userName} and they belong to the {$teamName} team.",
            "Today is " . date('l') . ", " . date('F jS') . ".",
            "They currently have {$inProgressTasks} task(s) in progress and {$blockedTasks} task(s) blocked/on-hold."
        ];

        // Expiring Tags (If Media Tagging Team)
        if ($userRole === \App\Models\Constants::USER_ROLE_MEDIA_TAGGING || stripos($teamName, 'Media Tagging') !== false) {
            $tagsModel = new \App\Models\TagsModel();
            
            $expiringTags = $tagsModel
                ->whereIn('status', [
                    \App\Models\Constants::STATUS_ENABLED,
                    \App\Models\Constants::STATUS_UPDATED_IN_AL,
                    \App\Models\Constants::STATUS_PENDING_VERIFICATION
                ])
                ->where('al_end_date <=', date('Y-m-d', strtotime('+15 days')))
                ->where('deleted_at', null)
                ->countAllResults();
                
            if ($expiringTags > 0) {
                $promptParts[] = "IMPORTANT: As they are in the Media Tagging team, please include a gentle reminder that they have {$expiringTags} tags expiring in the next 15 days, and add a quick motivational phrase related to keeping tags up-to-date.";
            }
        }
        
        $promptParts[] = "Keep it concise (2-3 sentences), natural, and very friendly. Do not include any placeholder text or markdown like **.";

        $prompt = implode(" ", $promptParts);

        // Call FABRIX API
        $apiUrl = env('FABRIX_API_URL') . '/openapi/llm/chat/completions';
        $apiKey = env('FABRIX_API_TOKEN') ?? '';
        $fabrixClient = env('FABRIX_CLIENT') ?? '';
        
        $client = \Config\Services::curlrequest();
        
        $fallbackMessage = "Hello {$userName}! Happy " . date('l') . ", have a wonderful week ahead! You have {$inProgressTasks} task(s) in progress today, and {$blockedTasks} task(s) blocked.";
        if (isset($expiringTags) && $expiringTags > 0) {
            $fallbackMessage .= " Please be reminded that there are {$expiringTags} tags expiring in the next 15 days.";
        }

        try {
            $response = $client->request('POST', $apiUrl, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'x-fabrix-client' => $fabrixClient,
                    'x-openapi-token' => 'Bearer ' . $apiKey,
                    'x-llm-model-id' => '7',
                ],
                'http_errors' => false,
                'json' => [
                    "messages" => [
                        ["role" => "system", "content" => "You are a helpful and highly motivational AI assistant for a corporate dashboard."],
                        ["role" => "user", "content" => $prompt]
                    ],
                    "temperature" => 0.7
                ],
                'timeout' => 10 // don't hang the dashboard for too long
            ]);
            
            $aiMessage = '';
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                $respData = json_decode($response->getBody(), true);
                if (isset($respData['choices'][0]['message']['content'])) {
                    $aiMessage = trim($respData['choices'][0]['message']['content']);
                } elseif (isset($respData['reply'])) {
                    $aiMessage = trim($respData['reply']);
                }
            }
            
            $baseColor = '#0071E3'; // Default blue
            if (class_exists('\App\Models\Constants')) {
                $baseColor = match ((int)$userId) {
                    \App\Models\Constants::USER_MARIE => '#7B1FA2',     // Purple
                    \App\Models\Constants::USER_KHRISTINE => '#C2185B', // Pink
                    \App\Models\Constants::USER_LUCKY => '#1B5E20',     // Green
                    \App\Models\Constants::USER_JANITHA => '#1565C0',   // Blue
                    \App\Models\Constants::USER_GEORGE => '#E65100',    // Orange
                    default => '#0071E3',
                };
            }
            $userColor = $baseColor;

            if (empty($aiMessage)) {
                return $this->response->setJSON(['success' => true, 'message' => $fallbackMessage, 'user_color' => $userColor]);
            }

            return $this->response->setJSON([
                'success' => true, 
                'message' => $aiMessage,
                'user_color' => $userColor
            ]);

        } catch (\Exception $e) {
            $baseColor = '#0071E3'; // Default blue
            if (class_exists('\App\Models\Constants')) {
                $baseColor = match ((int)$userId) {
                    \App\Models\Constants::USER_MARIE => '#7B1FA2',
                    \App\Models\Constants::USER_KHRISTINE => '#C2185B',
                    \App\Models\Constants::USER_LUCKY => '#1B5E20',
                    \App\Models\Constants::USER_JANITHA => '#1565C0',
                    \App\Models\Constants::USER_GEORGE => '#E65100',
                    default => '#0071E3',
                };
            }
            $userColor = $baseColor;
            return $this->response->setJSON(['success' => true, 'message' => $fallbackMessage, 'user_color' => $userColor]);
        }
    }
}
