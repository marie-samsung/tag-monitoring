<?php

namespace App\Models;
use CodeIgniter\Model;

class TagRequestsModel extends Model
{
    protected $table = 'tag_requests';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'ticket_id', // GTA-XXXXX
        'tags', // Comma separated list of tag ids, 'tags' table
        'property_id',
        'vendor_id',
        'status',
        'type',
        'counterpart_ticket', // New Rule or New Tag ticket
        'requester_name',
        'requester_email',
        'requester_organization',
        'updated_by', // Refer to 'Users' table
        'created_by',
        'deleted_by',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
}
