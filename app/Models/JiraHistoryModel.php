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
        'property_id', // Refer to 'Properties' table
        'subsidiary_id', // Refer to 'Subsidiaries' table
        'file_path',
        'created_by', // Refer to 'Users' table
        'updated_by', // Refer to 'Users' table
    ];

    protected $useTimestamps = true;

    public function getRelatedTags(int $id): array
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
            $builder->like('ticket_id', '%' . $filters['keyword'] . '%');
        }

        if (!empty($filters['ticket_type'])) {
            $builder->where('type', $filters['ticket_type']);
        }

        if (!empty($filters['property_id'])) {
            $builder->where('property_id', $filters['property_id']);
        }

        if (!empty($filters['subsidiary_id'])) {
            $builder->where('subsidiary_id', $filters['subsidiary_id']);
        }

        // Use a subquery to fetch the latest entry per ticket_id without triggering ONLY_FULL_GROUP_BY
        $builder->where('id IN (SELECT MAX(id) FROM jira_history GROUP BY ticket_id)', null, false);
        
        $builder->orderBy('created_at', 'DESC');


        return $builder->get()->getResultArray();
    }
}
