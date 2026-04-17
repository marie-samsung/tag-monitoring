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
        'requested_date',
        'ref_link', // Link to AL
        'requester_name',
        'requester_email',
        'requester_organization',
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
        $builder = $this->builder()
                ->select('tags.*')
                ->join('properties', 'properties.id = tags.property_id', 'left');

        if (!empty($filters['keyword'])) {
            $builder->like('tags.name', $filters['keyword']);
        }

        if (!empty($filters['property_id'])) {
            $builder->where('tags.property_id', $filters['property_id']);
        }

        if (!empty($filters['status'])) {
            $builder->where('tags.status', $filters['status']);
        }

        if (!empty($filters['subsidiary_id'])) {
            $builder->where('properties.subsidiary_id', $filters['subsidiary_id']);
        }

        // echo "<pre>";
        // echo $builder->getCompiledSelect();
        // echo "</pre>";
        // die();

        $builder->orderBy(
                '(CASE 
                    WHEN tags.al_end_date < CURDATE() AND tags.status = 1 THEN 0
                    WHEN tags.al_end_date <= DATE_ADD(CURDATE(), INTERVAL 9 DAY) AND tags.status = 1 THEN 1
                    WHEN tags.al_end_date <= DATE_ADD(CURDATE(), INTERVAL 16 DAY) AND tags.status = 1 THEN 2
                    WHEN tags.status = 4 THEN 3
                    WHEN tags.status = 1 THEN 4
                    ELSE 5
                END)', 'ASC', false
            );

        $builder->orderBy('properties.id', 'ASC');

        return $builder->get()->getResultArray();
    }

    public function getTagsByVendor(int $vendorId, int $subsidiaryId)
    {
        return $this->select('tags.*, properties.subsidiary_id, properties.name as property_name')
            ->join('properties', 'properties.id = tags.property_id', 'left')
            ->where('vendor_id', $vendorId)
            ->where('properties.subsidiary_id', $subsidiaryId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
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
