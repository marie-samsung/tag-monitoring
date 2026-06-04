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

    public function filterJiraTickets($filters = [], $paginate = 0)
    {
        $this->select();

        if (!empty($filters['keyword'])) {
            $this->like('ticket_id', $filters['keyword']);
        }

        if (!empty($filters['ticket_type'])) {
            $this->where('type', $filters['ticket_type']);
        }

        if (!empty($filters['property_id'])) {
            $this->where('property_id', $filters['property_id']);
        }

        if (!empty($filters['subsidiary_id'])) {
            $this->where('subsidiary_id', $filters['subsidiary_id']);
        }

        if (!empty($filters['created_at_from'])) {
            $this->where('created_at >=', $filters['created_at_from'] . ' 00:00:00');
        }

        if (!empty($filters['created_at_to'])) {
            $this->where('created_at <=', $filters['created_at_to'] . ' 23:59:59');
        }

        // Use a subquery to fetch the latest entry per ticket_id without triggering ONLY_FULL_GROUP_BY
        $this->where('id IN (SELECT MAX(id) FROM jira_history GROUP BY ticket_id)', null, false);
        
        $this->orderBy('created_at', 'DESC');

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
