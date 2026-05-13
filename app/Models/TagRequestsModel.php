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

    public function fileUploadPath($property_id, $vendor_name): string
    {
        $propertiesModel = new PropertiesModel();
        $property = $propertiesModel->find($property_id);
//        $today = date("Y-m-d");
        $path = Constants::SUBSIDIARIES[$property['subsidiary_id']] . '/' . $property['name'] . '/' . $vendor_name;

        return Constants::IS_DEV ? 'test-files/' . $path : $path;
    }
}
