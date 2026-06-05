<?php

namespace App\Models;
use CodeIgniter\Model;
use App\Models\TagsModel;

class TagRequestsModel extends Model
{
    protected $table = 'tag_requests';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'new_tag_ticket', // GTA-XXXXX
        'tags', // Comma separated list of tag ids, 'tags' table
        'property_id',
        'vendor_id',
        'status',
        'type',
        'new_rule_ticket',
        'requester_name',
        'requester_email',
        'requester_organization',
        'updated_by', // Refer to 'Users' table
        'created_by',
        'deleted_by',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    public function filterRequests($filters = [], $paginate = 0)
    {
        $this->select('tag_requests.*')
             ->join('properties', 'properties.id = tag_requests.property_id', 'left');

        if (!empty($filters['keyword'])) {
            $this->groupStart();
            $this->like('tag_requests.new_tag_ticket', $filters['keyword']);
            $this->orLike('tag_requests.new_rule_ticket', $filters['keyword']);
            $this->groupEnd();
        }

        if (!empty($filters['subsidiary_id'])) {
            $this->where('properties.subsidiary_id', $filters['subsidiary_id']);
        }

        if (!empty($filters['property_id'])) {
            $this->where('tag_requests.property_id', $filters['property_id']);
        }

        if (!empty($filters['vendor_id'])) {
            $this->where('tag_requests.vendor_id', $filters['vendor_id']);
        }

        if (!empty($filters['type'])) {
            $this->where('tag_requests.type', $filters['type']);
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'pending') {
                $this->whereNotIn('tag_requests.status', [
                    Constants::STATUS_REQUEST_PUBLISHED,
                    Constants::STATUS_REQUEST_REJECTED
                ]);
            } else {
                $this->where('tag_requests.status', $filters['status']);
            }
        }

        if (!empty($filters['created_at_from'])) {
            $this->where('tag_requests.created_at >=', $filters['created_at_from'] . ' 00:00:00');
        }

        if (!empty($filters['created_at_to'])) {
            $this->where('tag_requests.created_at <=', $filters['created_at_to'] . ' 23:59:59');
        }

        $this->orderBy('tag_requests.created_at', 'DESC');
        
        if ($paginate > 0) {
            return $this->paginate($paginate);
        }

        return $this->findAll();
    }

    public function getTags($id): array
    {
        $request = $this->find($id);
        $tagsModel = new TagsModel();

        $tagsList = explode(',', $request['tags']);
        $tags = [];

        foreach ($tagsList as $tag) {
            $tags[$tag] = $tagsModel->find($tag);
        }

        return $tags;
    }

    public function getRequestByTicketId($ticketId)
    {
        // New Tag Request
        $request = $this->where('new_tag_ticket', $ticketId)->first();

        // New Rule Request
        if (empty($request)) {
            $request = $this->where('new_rule_ticket', $ticketId)->first();
        }

        return empty($request) ? null : $request['id'];
    }

    public function countNewTags()
    {
        return $this->where('new_tag_ticket IS NOT NULL')->countAllResults();
    }

    public function countNewRules()
    {
        return $this->where('new_rule_ticket IS NOT NULL')->countAllResults();
    }

    public function countAllRequests()
    {
        return $this->countAllResults();
    }

    public function fileUploadPath($property_id, $vendor_name): string
    {
        $propertiesModel = new PropertiesModel();
        $property = $propertiesModel->find($property_id);
//        $today = date("Y-m-d");
        $path = Constants::SUBSIDIARIES[$property['subsidiary_id']] . '/' . $property['name'] . '/' . $vendor_name;

        return Constants::IS_DEV ? 'test-files/' . $path : $path;
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
