<?php

namespace App\Models;
use CodeIgniter\Model;

class PropertiesModel extends Model
{
    protected $table = 'properties';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'name',
        'is_active', // bool
        'is_epp', // bool
        'subsidiary_id', // Refer to 'Subsidiaries' table
        'country_id', // Refer to 'Countries' table
        'language_id', // Refer to 'Languages' table
        'website',
        'ref_link', // Link to AL
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
