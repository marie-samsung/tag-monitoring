<?php

namespace App\Models;
use CodeIgniter\Model;

class VendorsModel extends Model
{
    protected $table = 'vendors';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'name',
        'about', // RTE 
        'tag_purpose', // RTE
        'collected_data', // RTE
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

    /**
     * Filtering of vendors in list view
     * @param mixed $filters
     * @return array
     */
    public function filterVendors($filters = [])
    {
        $builder = $this->builder();

        if (!empty($filters['keyword'])) {
            $builder->like('name', $filters['keyword']);
        }

        if (!empty($filters['id'])) {
            $builder->where('id', $filters['id']);
        }

        return $builder->get()->getResultArray();
    }
}
