<?php

namespace App\Models;
use CodeIgniter\Model;

class ActivityLogsModel extends Model
{
    protected $table = 'activity_logs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title',
        'description',
        'subsidiary_id',
        'department_id',
        'project_id',
        'ticket_id',
        'assignee',
        'requested_by',
        'status',
        'type',
        'size',
        'priority',
        'requested_date',
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

        if (!empty($filters['project_id'])) {
            $this->where('project_id', $filters['project_id']);
        }

        if (!empty($filters['type'])) {
            $this->where('type', $filters['type']);
        }

        if (!empty($filters['priority'])) {
            $this->where('priority', $filters['priority']);
        }

        if (!empty($filters['assignee'])) {
            $this->where('assignee', $filters['assignee']);
        }

        if (!empty($filters['status'])) {
            $this->where('status', $filters['status']);
        }

        // Dynamic Sort options
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        
        $allowedSortColumns = [
            'id', 'subsidiary_id', 'project_id', 'title', 'ticket_id', 'assignee', 'status', 'priority', 'type', 'size', 'created_at'
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