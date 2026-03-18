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

    /**
     * Filtering of tags in list view
     * @param mixed $filters
     * @return array
     */
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

        return $builder
            // ->orderBy('status', 'DESC')
            ->orderBy('(CASE 
                    WHEN status = 4 THEN 1
                    WHEN status = 1 THEN 2
                    WHEN status = 2 THEN 3
                    WHEN status = 3 THEN 4
                    ELSE 5
                END)', 'ASC')
            // ->orderBy('(CASE WHEN al_end_date IS NULL OR al_end_date = "" THEN 1 ELSE 0 END)', 'ASC')
            ->orderBy('al_end_date', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Create the folder structure and file when uploading the Launch Publishing file on JIRA History
     * @param int $id
     * @return string The path where the LP file should be stored
     */
    public function uploadLPFolderPath($id)
    {
        $tag = $this->find($id);
        $propertiesModel = new PropertiesModel();
        $subsidiaryModel = new SubsidiariesModel();
        $property = $propertiesModel->find($tag['property_id']);
        $subsidiary = $subsidiaryModel->find($property['subsidiary_id']);
        // $today = date('d-m-Y');
        return $subsidiary['name'] . '/' . $property['name'] . '/' . $tag['name'];
    }
}
