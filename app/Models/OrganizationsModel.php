<?php

namespace App\Models;
use CodeIgniter\Model;

class OrganizationsModel extends Model
{
    protected $table = 'organizations';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'name',
        'updated_by', // Refer to 'Users' table
        'created_by',
        'deleted_by',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    public function Options()
    {
        $rows = $this->select('id, name')->findAll();
        return array_column($rows, 'name', 'id');
    }
}
