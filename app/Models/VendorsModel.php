<?php

namespace App\Models;
use CodeIgniter\Model;

class VendorsModel extends Model
{
    protected $table = 'vendors';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'name',
        'purpose',
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
