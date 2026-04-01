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
        'subsidiary_id', // Refer to 'Subsidiaries' table
        'file_path',
        'created_by', // Refer to 'Users' table
        'updated_by', // Refer to 'Users' table
    ];

    protected $useTimestamps = true;

    public function getRelatedTags(int $id)
    {
        return $this->select()
            ->where('tag_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function getRelatedSubsidiary(int $id)
    {
        return $this->select()
            ->where('subsidiary_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function getLatestTag(int $id)
    {
        return $this->select()
            ->where('tag_id', $id)
            ->where('type', Constants::JIRA_TYPE_DATE_EXTENSION_RULE)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    public function getLatestSubsidiary(int $id)
    {
        return $this->select()
            ->where('subsidiary_id', $id)
            ->where('type', Constants::JIRA_TYPE_MASTER_APPROVAL)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    public function filterJiraTickets($filters = [])
    {
        $builder = $this->builder()->select();

        if (!empty($filters['keyword'])) {
            $builder->like('ticket_id', $filters['keyword']);
        }

        $builder->groupBy('ticket_id');


        return $builder->get()->getResultArray();
    }
}
