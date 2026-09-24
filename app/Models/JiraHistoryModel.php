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

        $this->applyVendorAndKeywordFilter($this, $filters);

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

        $this->applyVendorAndKeywordFilter($builder, $filters);

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

    /**
     * Retrieves ticket IDs associated with given vendor IDs through tag requests and related tags.
     */
    protected function getTicketIdsForVendors(array $vendorIds): array
    {
        if (empty($vendorIds)) {
            return [];
        }

        $db = $this->db;
        $matched = [];

        // 1. From tag_requests table
        $trTickets = $db->table('tag_requests')
            ->select('new_tag_ticket, new_rule_ticket')
            ->whereIn('vendor_id', $vendorIds)
            ->get()
            ->getResultArray();

        foreach ($trTickets as $tr) {
            if (!empty($tr['new_tag_ticket'])) {
                $matched[] = $tr['new_tag_ticket'];
            }
            if (!empty($tr['new_rule_ticket'])) {
                $matched[] = $tr['new_rule_ticket'];
            }
        }

        // 2. From tags table belonging to these vendors
        $tagRows = $db->table('tags')
            ->select('id')
            ->whereIn('vendor_id', $vendorIds)
            ->get()
            ->getResultArray();

        if (!empty($tagRows)) {
            $tagIds = array_column($tagRows, 'id');

            // Tickets directly referencing these tags via tag_id in any jira_history record
            $jhTags = $db->table('jira_history')
                ->select('ticket_id')
                ->whereIn('tag_id', $tagIds)
                ->distinct()
                ->get()
                ->getResultArray();

            foreach ($jhTags as $jt) {
                if (!empty($jt['ticket_id'])) {
                    $matched[] = $jt['ticket_id'];
                }
            }

            // Tickets with comments containing any of these tag IDs
            foreach (array_chunk($tagIds, 50) as $chunk) {
                $regexPattern = '(^|,)(' . implode('|', $chunk) . ')(,|$)';
                $jhComments = $db->table('jira_history')
                    ->select('ticket_id')
                    ->where("comments REGEXP '{$regexPattern}'", null, false)
                    ->distinct()
                    ->get()
                    ->getResultArray();

                foreach ($jhComments as $jc) {
                    if (!empty($jc['ticket_id'])) {
                        $matched[] = $jc['ticket_id'];
                    }
                }
            }
        }

        return array_values(array_unique($matched));
    }

    /**
     * Applies vendor and keyword filtering to the query builder.
     */
    protected function applyVendorAndKeywordFilter($builder, array $filters)
    {
        $keyword = !empty($filters['keyword']) ? trim($filters['keyword']) : null;
        $vendorId = !empty($filters['vendor_id']) ? (int)$filters['vendor_id'] : null;

        if (empty($keyword) && empty($vendorId)) {
            return;
        }

        $db = $this->db;
        $vendorIds = [];

        if (!empty($vendorId)) {
            $vendorIds = [$vendorId];
        } elseif (!empty($keyword)) {
            $escapedKw = $db->escapeLikeString($keyword);
            $vendorRows = $db->table('vendors')
                ->select('id')
                ->like('name', $escapedKw)
                ->get()
                ->getResultArray();

            if (!empty($vendorRows)) {
                $vendorIds = array_column($vendorRows, 'id');
            }
        }

        // If a specific vendor_id was filtered:
        if (!empty($vendorId)) {
            $vendorTicketIds = $this->getTicketIdsForVendors($vendorIds);
            if (!empty($vendorTicketIds)) {
                $builder->whereIn('ticket_id', $vendorTicketIds);
            } else {
                $builder->where('1 = 0', null, false);
            }

            if (!empty($keyword)) {
                $builder->like('ticket_id', $keyword);
            }
            return;
        }

        // When keyword is provided without a fixed vendor_id filter:
        $vendorTicketIds = [];
        if (!empty($vendorIds)) {
            $vendorTicketIds = $this->getTicketIdsForVendors($vendorIds);
        }

        $builder->groupStart();
        $builder->like('ticket_id', $keyword);
        if (!empty($vendorTicketIds)) {
            $builder->orWhereIn('ticket_id', $vendorTicketIds);
        }
        $builder->groupEnd();
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
