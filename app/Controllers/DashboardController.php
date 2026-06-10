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

        // System Health Index calculation
        $today = date('Y-m-d');
        $expired_active = $tagsModel->whereIn('status', [
                Constants::STATUS_ENABLED,
                Constants::STATUS_UPDATED_IN_AL,
                Constants::STATUS_PENDING_VERIFICATION
            ])
            ->where('al_end_date <', $today)
            ->where('deleted_at', null)
            ->countAllResults();

        $expiring_soon = $tagsModel->whereIn('status', [
                Constants::STATUS_ENABLED,
                Constants::STATUS_UPDATED_IN_AL,
                Constants::STATUS_PENDING_VERIFICATION
            ])
            ->where('al_end_date >=', $today)
            ->where('al_end_date <=', date('Y-m-d', strtotime('+15 days')))
            ->where('deleted_at', null)
            ->countAllResults();

        $total_active = $data['active_tags'];
        
        if ($total_active > 0) {
            $health_score = 100 - (($expired_active * 8) + ($expiring_soon * 2));
            $health_score = max(0, min(100, $health_score));
        } else {
            $health_score = 100;
        }

        $data['health_score'] = $health_score;
        $data['expired_active_count'] = $expired_active;
        $data['expiring_soon_count'] = $expiring_soon;

        $data['activities_last_30_days'] = $activityLogsModel
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->countAllResults();

        // 2. 30-Day Activity Trends
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));

        $tagsQuery = $db->table('tag_requests')
            ->select("DATE(COALESCE(jh.created_at, tag_requests.created_at)) as date, SUM(LENGTH(tag_requests.tags) - LENGTH(REPLACE(tag_requests.tags, ',', '')) + 1) as count", false)
            ->join('(SELECT ticket_id, MIN(created_at) as created_at FROM jira_history GROUP BY ticket_id) jh', 'jh.ticket_id = tag_requests.new_tag_ticket', 'left')
            ->where('tag_requests.new_tag_ticket IS NOT NULL')
            ->where('tag_requests.new_tag_ticket !=', '')
            ->where('tag_requests.tags !=', '')
            ->where('COALESCE(jh.created_at, tag_requests.created_at) >=', $thirtyDaysAgo)
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
            ->where('COALESCE(jh.created_at, tag_requests.created_at) >=', $thirtyDaysAgo)
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
        $datesFull = [];
        $tagsData = [];
        $requestsData = [];
        $activitiesData = [];
        $newRulesData = [];
        $pendingData = [];

        for ($i = 29; $i >= 0; $i--) {
            $dateStr = date('Y-m-d', strtotime("-$i days"));
            $dates[] = date('d M', strtotime($dateStr));
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

        $activityTeamCounts = $db->table('activity_logs')
            ->select('team_id, COUNT(*) as count')
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

    public function getActivityTags()
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
            'activities' => $activities
        ]);
    }
}
