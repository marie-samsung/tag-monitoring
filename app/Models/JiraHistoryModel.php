<?php

namespace App\Models;
use CodeIgniter\Model;

class JiraHistoryModel extends Model
{
    protected $table = 'jira_history';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'ticket_id', // GTA-XXXXX
        'type',
        'comments',
        'tag_id', // Refer to 'Tags' table
        'lp_path',
        'is_active', // bool
        'created_by', // Refer to 'Users' table
    ];

    protected $useTimestamps = true;

    public function getRelatedTags(int $id)
    {
        return $this->select()
            ->where('tag_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
