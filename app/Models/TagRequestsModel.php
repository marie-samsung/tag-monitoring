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

    public function filterRequests($filters = [])
    {
        $builder = $this->builder()
            ->select('tag_requests.*')
            ->join('properties', 'properties.id = tag_requests.property_id', 'left');

        if (!empty($filters['keyword'])) {
            $builder->like('tag_requests.new_tag_ticket', $filters['keyword']);
            $builder->orLike('tag_requests.new_rule_ticket', $filters['keyword']);
        }

        if (!empty($filters['subsidiary_id'])) {
            $builder->where('properties.subsidiary_id', $filters['subsidiary_id']);
        }

        if (!empty($filters['property_id'])) {
            $builder->where('tag_requests.property_id', $filters['property_id']);
        }

        if (!empty($filters['vendor_id'])) {
            $builder->where('tag_requests.vendor_id', $filters['vendor_id']);
        }

        if (!empty($filters['type'])) {
            $builder->where('tag_requests.type', $filters['type']);
        }

        if (!empty($filters['status'])) {
            $builder->where('tag_requests.status', $filters['status']);
        }

        $builder->orderBy('tag_requests.created_at', 'DESC');
        return $builder->get()->getResultArray();
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
}
