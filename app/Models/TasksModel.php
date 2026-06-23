<?php

namespace App\Models;
use CodeIgniter\Model;

class TasksModel extends Model
{
    protected $table = 'activity_logs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title',
        'description',
        'subsidiary_id',
        'department_id',
        'team_id',
        'project',
        'ticket_id',
        'assignee',
        'requested_by',
        'status',
        'type',
        'size',
        'priority',
        'requested_date',
        'start_date',
        'finished_date',
        'created_by',
        'updated_by',
        'campaign_status',
        'campaign_id',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    public function filterLogs($filters = [], $paginate = 0)
    {
        $this->applyRoleFilters();
        if (!empty($filters['keyword'])) {
            $this->groupStart()
                 ->like('title', $filters['keyword'])
                 ->orLike('description', $filters['keyword'])
                 ->orLike('ticket_id', $filters['keyword'])
                 ->groupEnd();
        }

        if (!empty($filters['subsidiary_id'])) {
            $this->where('subsidiary_id', $filters['subsidiary_id']);
        }

        if (!empty($filters['team_id'])) {
            $this->where('team_id', $filters['team_id']);
        }

        if (!empty($filters['project'])) {
            $this->where('project', $filters['project']);
        }

        if (!empty($filters['type'])) {
            $this->where('type', $filters['type']);
        }

        if (!empty($filters['priority'])) {
            $this->where('priority', $filters['priority']);
        }

        if (!empty($filters['campaign_status'])) {
            $this->where('campaign_status', $filters['campaign_status']);
        }

        if (!empty($filters['created_from'])) {
            $this->where('DATE(created_at) >=', $filters['created_from']);
        }

        if (!empty($filters['created_to'])) {
            $this->where('DATE(created_at) <=', $filters['created_to']);
        }

        if (!empty($filters['started_from'])) {
            $this->where('DATE(start_date) >=', $filters['started_from']);
        }

        if (!empty($filters['started_to'])) {
            $this->where('DATE(start_date) <=', $filters['started_to']);
        }

        if (!empty($filters['finished_from'])) {
            $this->where('DATE(finished_date) >=', $filters['finished_from']);
        }

        if (!empty($filters['finished_to'])) {
            $this->where('DATE(finished_date) <=', $filters['finished_to']);
        }

        if (!empty($filters['me_mode'])) {
            $userId = session()->get('user_id');
            if ($userId !== null) {
                $this->where('assignee', $userId);
            }
        } elseif (!empty($filters['assignee'])) {
            $this->where('assignee', $filters['assignee']);
        }

        if (!empty($filters['status_group'])) {
            if ($filters['status_group'] === 'pending') {
                $this->whereIn('status', [1, 5]);
            } elseif ($filters['status_group'] === 'in_progress') {
                $this->whereIn('status', [2, 3, 4]);
            } elseif ($filters['status_group'] === 'blocked') {
                $this->whereIn('status', [7]);
            } elseif ($filters['status_group'] === 'completed') {
                $this->whereIn('status', [6]);
            }
        } elseif (!empty($filters['status'])) {
            $this->where('status', $filters['status']);
        }

        if (empty($filters['show_completed']) || $filters['show_completed'] !== '1') {
            if (($filters['status_group'] ?? '') !== 'completed' && ($filters['status'] ?? null) != Constants::ACTIVITY_LOGS_STATUS_COMPLETED) {
                $this->where('status !=', Constants::ACTIVITY_LOGS_STATUS_COMPLETED);
            }
        }

        // Dynamic Sort options
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        
        $allowedSortColumns = [
            'id', 'subsidiary_id', 'team_id', 'project', 'title', 'ticket_id', 'assignee', 'status', 'priority', 'type', 'size', 'created_at', 'requested_date', 'start_date', 'requested_by', 'campaign_status'
        ];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }
        
        $sortDir = (strtolower($sortDir) === 'asc') ? 'ASC' : 'DESC';
        
        $this->orderBy($sortBy, $sortDir);
        if ($sortBy !== 'id') {
            $this->orderBy('id', 'DESC'); // secondary sorting for stable pagination
        }

        if ($paginate > 0) {
            return $this->paginate($paginate);
        }

        return $this->findAll();
    }

    public function getRelatedTagRenewal($ticketId)
    {
        return $this->select()
            ->where('ticket_id', $ticketId)
            ->where('type', Constants::ACTIVITY_TYPE_RENEWAL)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    public function getRelatedTagRequest($ticketId)
    {
        return $this->select()
            ->where('ticket_id', $ticketId)
            ->where('type', Constants::ACTIVITY_TYPE_REQUEST)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    public function getStatusCounts($filters = [])
    {
        $this->applyRoleFilters();
        // Remove status and status_group filters to count across all statuses
        unset($filters['status']);
        unset($filters['status_group']);

        if (!empty($filters['keyword'])) {
            $this->groupStart()
                 ->like('title', $filters['keyword'])
                 ->orLike('description', $filters['keyword'])
                 ->orLike('ticket_id', $filters['keyword'])
                 ->groupEnd();
        }

        if (!empty($filters['subsidiary_id'])) {
            $this->where('subsidiary_id', $filters['subsidiary_id']);
        }

        if (!empty($filters['team_id'])) {
            $this->where('team_id', $filters['team_id']);
        }

        if (!empty($filters['project'])) {
            $this->where('project', $filters['project']);
        }

        if (!empty($filters['type'])) {
            $this->where('type', $filters['type']);
        }

        if (!empty($filters['priority'])) {
            $this->where('priority', $filters['priority']);
        }

        if (!empty($filters['campaign_status'])) {
            $this->where('campaign_status', $filters['campaign_status']);
        }

        if (!empty($filters['created_from'])) {
            $this->where('DATE(created_at) >=', $filters['created_from']);
        }

        if (!empty($filters['created_to'])) {
            $this->where('DATE(created_at) <=', $filters['created_to']);
        }

        if (!empty($filters['started_from'])) {
            $this->where('DATE(start_date) >=', $filters['started_from']);
        }

        if (!empty($filters['started_to'])) {
            $this->where('DATE(start_date) <=', $filters['started_to']);
        }

        if (!empty($filters['finished_from'])) {
            $this->where('DATE(finished_date) >=', $filters['finished_from']);
        }

        if (!empty($filters['finished_to'])) {
            $this->where('DATE(finished_date) <=', $filters['finished_to']);
        }

        if (!empty($filters['me_mode'])) {
            $userId = session()->get('user_id');
            if ($userId !== null) {
                $this->where('assignee', $userId);
            }
        } elseif (!empty($filters['assignee'])) {
            $this->where('assignee', $filters['assignee']);
        }

        $results = $this->select('status, COUNT(*) as count')
                        ->groupBy('status')
                        ->findAll();

        $counts = [];
        foreach ($results as $row) {
            $counts[(int)$row['status']] = (int)$row['count'];
        }
        return $counts;
    }

    public function getProjectLogs($projectName, $limit = null, $meMode = false)
    {
        $this->applyRoleFilters();
        
        $query = $this->select('activity_logs.*, (SELECT comment FROM activity_log_comments WHERE activity_log_comments.log_id = activity_logs.id ORDER BY activity_log_comments.created_at DESC, activity_log_comments.id DESC LIMIT 1) as last_comment')
            ->where('project', $projectName);

        if ($meMode) {
            $userId = session()->get('user_id');
            if ($userId !== null) {
                $query->where('assignee', $userId);
            }
        }

        $query->orderBy('created_at', 'DESC');

        if ($limit !== null) {
            return $query->paginate($limit);
        }
        return $query->findAll();
    }

    public function getDistinctProjects($userId = null)
    {
        $this->select('DISTINCT(project) as project_name')->orderBy('project', 'ASC');
        
        $this->applyRoleFilters();

        if ($userId !== null) {
            $this->where('assignee', $userId);
        }

        return $this->findAll();
    }

    protected function applyRoleFilters()
    {
        $userId = session()->get('user_id');
        if ($userId !== null) {
            $userRole = Constants::USER_ACCESS_LEVELS[$userId] ?? null;
            if ($userRole !== Constants::USER_ROLE_ADMIN) {
                $userTeam = Constants::USER_TEAMS[$userId] ?? null;
                if ($userTeam !== null) {
                    $this->where('team_id', $userTeam);
                }
            }
        }
        return $this;
    }

    protected $beforeInsert = ['setCreatedBy'];
    protected $beforeUpdate = ['setUpdatedBy'];

    protected function setCreatedBy(array $data)
    {
        if (session()->has('user_id') && !isset($data['data']['created_by'])) {
            $data['data']['created_by'] = session()->get('user_id');
        }
        return $data;
    }

    protected function setUpdatedBy(array $data)
    {
        if (session()->has('user_id') && !isset($data['data']['updated_by'])) {
            $data['data']['updated_by'] = session()->get('user_id');
        }
        return $data;
    }
}