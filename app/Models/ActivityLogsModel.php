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
        'requested_date',
        'finished_date',
        'created_by',
        'updated_by',
        'campaign_status',
        'campaign_id',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    public function filterLogs($filters = [])
    {
        $builder = $this->builder();

        if (!empty($filters['keyword'])) {
            $builder->like('title', $filters['keyword']);
            $builder->orLike('description', $filters['keyword']);
            $builder->orLike('ticket_id', $filters['keyword']);
        }

        if (!empty($filters['subsidiary_id'])) {
            $builder->where('subsidiary_id', $filters['subsidiary_id']);
        }

        if (!empty($filters['project_id'])) {
            $builder->where('project_id', $filters['project_id']);
        }

        if (!empty($filters['type'])) {
            $builder->where('type', $filters['type']);
        }

        if (!empty($filters['assignee'])) {
            $builder->where('assignee', $filters['assignee']);
        }

        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }

        $builder->orderBy('created_at', 'DESC');
        return $builder->get()->getResultArray();
    }
}