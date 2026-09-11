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
            ->groupStart()
            ->where('tag_id', $id)
            ->orGroupStart()
            ->whereIn('type', [
                Constants::JIRA_TYPE_DATE_EXTENSION_RULE,
                Constants::JIRA_TYPE_NEW_TAG,
                Constants::JIRA_TYPE_NEW_RULE,
                Constants::JIRA_TYPE_UPDATE_RULE
            ])
            ->where("FIND_IN_SET('{$id}', comments) >", 0)
            ->groupEnd()
            ->groupEnd()
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

    public function getRelatedTagDateExtension(int $id)
    {
        return $this->select()
            ->groupStart()
            ->where('tag_id', $id)
            ->orWhere("FIND_IN_SET('{$id}', comments) >", 0)
            ->groupEnd()
            ->where('type', Constants::JIRA_TYPE_DATE_EXTENSION_RULE)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    public function getRelatedTagUpdateRule(int $id)
    {
        return $this->select()
            ->groupStart()
            ->where('tag_id', $id)
            ->orWhere("FIND_IN_SET('{$id}', comments) >", 0)
            ->groupEnd()
            ->where('type', Constants::JIRA_TYPE_UPDATE_RULE)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    public function getRelatedTagRequest(int $id)
    {
        return $this->select()
            ->groupStart()
            ->where('tag_id', $id)
            ->orWhere("FIND_IN_SET('{$id}', comments) >", 0)
            ->groupEnd()
            ->whereIn('type', [Constants::JIRA_TYPE_NEW_TAG, Constants::JIRA_TYPE_NEW_RULE])
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    public function getBatchRelatedTickets(array $tagIds, array $types)
    {
        if (empty($tagIds)) return [];

        $builder = $this->builder();
        $builder->select('tag_id, comments, ticket_id, type, created_at');
        
        $builder->groupStart();
        $builder->whereIn('tag_id', $tagIds);
        foreach ($tagIds as $id) {
            $builder->orWhere("FIND_IN_SET('{$id}', comments) >", 0);
        }
        $builder->groupEnd();
        
        $builder->whereIn('type', $types);
        $builder->orderBy('created_at', 'ASC');
        
        return $builder->get()->getResultArray();
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
        $this->where('id IN (SELECT MAX(id) FROM jira_history GROUP BY ticket_id, property_id)', null, false);

        $sortBy = $filters['sort_by'] ?? null;
        $sortDir = $filters['sort_dir'] ?? 'desc';
        if (!in_array(strtolower($sortDir), ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        if (!empty($sortBy)) {
            $allowedColumns = ['id', 'subsidiary_id', 'property_id', 'ticket_id', 'type', 'created_at'];
            if (in_array($sortBy, $allowedColumns)) {
                $this->orderBy($sortBy, $sortDir);
            } else {
                $this->orderBy('created_at', 'DESC');
            }
        } else {
            $this->orderBy('created_at', 'DESC');
        }

        if ($paginate > 0) {
            return $this->paginate($paginate);
        }

        return $this->findAll();
    }

    public function countUniqueTags(array $filters = []): int
    {
        $builder = $this->builder();
        $builder->select('comments');

        if (!empty($filters['keyword'])) {
            $builder->like('ticket_id', $filters['keyword']);
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

        if (!empty($filters['created_at_from'])) {
            $builder->where('created_at >=', $filters['created_at_from'] . ' 00:00:00');
        }

        if (!empty($filters['created_at_to'])) {
            $builder->where('created_at <=', $filters['created_at_to'] . ' 23:59:59');
        }

        // Use a subquery to fetch the latest entry per ticket_id without triggering ONLY_FULL_GROUP_BY
        $builder->where('id IN (SELECT MAX(id) FROM jira_history GROUP BY ticket_id, property_id)', null, false);

        $results = $builder->get()->getResultArray();

        $uniqueTags = [];
        foreach ($results as $row) {
            if (!empty($row['comments'])) {
                $tags = explode(',', $row['comments']);
                foreach ($tags as $tag) {
                    $tag = trim($tag);
                    if ($tag !== '' && is_numeric($tag) && (int)$tag > 0) {
                        $uniqueTags[(int)$tag] = true;
                    }
                }
            }
        }

        return count($uniqueTags);
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
