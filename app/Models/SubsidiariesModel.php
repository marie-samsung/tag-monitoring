<?php

namespace App\Models;
use CodeIgniter\Model;

class SubsidiariesModel extends Model
{
    protected $table = 'subsidiaries';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'name',
        'updated_by', // Refer to 'Users' table
        'created_by',
        'deleted_by',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
}