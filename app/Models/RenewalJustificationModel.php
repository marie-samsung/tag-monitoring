<?php

namespace App\Models;
use CodeIgniter\Model;

class RenewalJustificationModel extends Model
{
    protected $table = 'renewal_justification';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'tag_id',
        'renew',
        'justification',
        'duration',
        'created_by',
        'updated_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
