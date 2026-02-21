<?php

namespace App\Models;
use CodeIgniter\Model;

class CountriesModel extends Model
{
    protected $table = 'countries';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'name',
        'code',
        'updated_by', // Refer to 'Users' table
        'created_by',
        'deleted_by',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
}