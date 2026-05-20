<?php

namespace App\Models;
use CodeIgniter\Model;

class ActivityLogCommentsModel extends Model
{
    protected $table = 'activity_log_comments';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'log_id',
        'comment',
        'type',
        'commented_by',
        'created_at',
    ];

    protected $useTimestamps = true;

    public function getRelatedComments($log_id)
    {
        return $this->select()
            ->where('log_id', $log_id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}