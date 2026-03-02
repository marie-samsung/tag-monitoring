<?php

namespace App\Models;
use CodeIgniter\Model;

class TagsModel extends Model
{
    protected $table = 'tags';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'property_id', // Refers to 'Properties' table
        'vendor_id', // Refer to 'Vendors' table
        'conditions', // (.*)
        'status', // Refer to 'Constants' model for values
        'is_active', // bool
        'al_end_date',
        'ref_link', // Link to AL
        'updated_by', // Refer to 'Users' table
        'created_by',
        'deleted_by',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    public function filterTags($filters = [])
    {
        $builder = $this->builder();

        if (!empty($filters['keyword'])) {
            $builder->like('name', $filters['keyword']);
        }

        if ($filters['status'] !== '' && $filters['status'] !== null) {
            $builder->where('status', $filters['status']);
        }

        if (!empty($filters['property_id'])) {
            $builder->where('property_id', $filters['property_id']);
        }

        return $builder->orderBy('al_end_date', 'DESC')->get()->getResultArray();
    }
}