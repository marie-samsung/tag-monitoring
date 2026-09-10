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
        'reason',
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
        $rows = $this
            ->select('id, name')
            ->orderBy('name', 'ASC')
            ->findAll();
        return array_column($rows, 'name', 'id');
    }

    public function getTagsCount($vendorId)
    {
        $builder = $this->db->table('tags');
        $builder->where('vendor_id', $vendorId)
            ->whereNotIn('status', [Constants::STATUS_DISABLED, Constants::STATUS_ENDED, Constants::STATUS_EXPIRED]);
        return $builder->countAllResults();
    }

    public function getTags($vendorId, $subsidiaryId, $isCount = true)
    {
        $builder = $this->db
            ->table('tags')
            ->select('tags.*')
            ->join('properties', 'properties.id = tags.property_id', 'left')
            ->where('tags.vendor_id', $vendorId)
            ->where('properties.subsidiary_id', $subsidiaryId)
            ->whereNotIn('tags.status', [Constants::STATUS_DISABLED, Constants::STATUS_ENDED, Constants::STATUS_EXPIRED]);

        return $isCount ? $builder->countAllResults() : $builder->get()->getResultArray();
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

        $sortBy = $filters['sort_by'] ?? 'name';
        $sortDir = $filters['sort_dir'] ?? 'asc';
        if (!in_array(strtolower($sortDir), ['asc', 'desc'])) {
            $sortDir = 'asc';
        }

        $allowedColumns = ['id', 'name'];
        if (in_array($sortBy, $allowedColumns)) {
            $builder->orderBy($sortBy, $sortDir);
        } else {
            $builder->orderBy('name', 'ASC');
        }

        return $builder->get()->getResultArray();
    }

    protected $beforeInsert = ['setCreatedBy'];
    protected $beforeUpdate = ['setUpdatedBy'];

    protected function setCreatedBy(array $data)
    {
        if (session()->has('user_id') && !isset($data['data']['created_by'])) {
            $data['data']['created_by'] = session()->get('user_id');
        }
        return $data;
    }

    protected function setUpdatedBy(array $data)
    {
        if (session()->has('user_id') && !isset($data['data']['updated_by'])) {
            $data['data']['updated_by'] = session()->get('user_id');
        }
        return $data;
    }
}
