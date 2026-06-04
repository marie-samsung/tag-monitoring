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
    public function filterTags($filters = [], $paginate = 0)
    {
        $this->select('tags.*')
             ->join('properties', 'properties.id = tags.property_id', 'left');

        if (!empty($filters['keyword'])) {
            $this->like('tags.name', $filters['keyword']);
        }

        if (!empty($filters['property_id'])) {
            $this->where('tags.property_id', $filters['property_id']);
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'expiring') {
                $base = date('Y-m-d', strtotime('-10 days'));
                $expiring = date('Y-m-d', strtotime('+15 days'));
                $this->whereIn('tags.status', [
                    Constants::STATUS_ENABLED,
                    Constants::STATUS_PENDING_VERIFICATION,
                    Constants::STATUS_UPDATED_IN_AL
                ])
                ->where('tags.al_end_date >=', $base)
                ->where('tags.al_end_date <=', $expiring);
            } else if (is_array($filters['status'])) {
                $this->whereIn('tags.status', $filters['status']);
            } else {
                $this->where('tags.status', $filters['status']);
            }
        }

        if (!empty($filters['subsidiary_id'])) {
            $this->where('properties.subsidiary_id', $filters['subsidiary_id']);
        }

        // echo "<pre>";
        // echo $builder->getCompiledSelect();
        // echo "</pre>";
        // die();

        $this->orderBy(
                '(CASE 
                    WHEN tags.al_end_date < CURDATE() AND tags.status = 1 THEN 0
                    WHEN tags.al_end_date <= DATE_ADD(CURDATE(), INTERVAL 9 DAY) AND tags.status = 1 THEN 1
                    WHEN tags.al_end_date <= DATE_ADD(CURDATE(), INTERVAL 9 DAY) AND (tags.status IN (4,5)) THEN 2
                    WHEN tags.al_end_date <= DATE_ADD(CURDATE(), INTERVAL 16 DAY) AND (tags.status IN (1,4,5)) THEN 3
                    WHEN tags.status = 4 THEN 4
                    WHEN tags.status = 1 THEN 5
                    ELSE 6
                END)', 'ASC', false
            );

        $this->orderBy('properties.id', 'ASC');

        if ($paginate > 0) {
            return $this->paginate($paginate);
        }

        return $this->findAll();
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

    public function countActiveTags()
    {
        return $this->whereIn('status', [
            Constants::STATUS_ENABLED, 
            Constants::STATUS_PENDING_VERIFICATION, 
            Constants::STATUS_UPDATED_IN_AL
        ])->countAllResults();
    }

    public function countPendingVerificationTags()
    {
        return $this->where('status', Constants::STATUS_PENDING_VERIFICATION)->countAllResults();
    }

    public function countExpiringTags()
    {
        $today = date('Y-m-d');
        $base = date('Y-m-d', strtotime('-10 days'));
        $expiring = date('Y-m-d', strtotime('+15 days'));

        return $this->whereIn('status', [
                Constants::STATUS_ENABLED,
                Constants::STATUS_PENDING_VERIFICATION,
                Constants::STATUS_UPDATED_IN_AL
            ])
            ->where('al_end_date >=', $base)
            ->where('al_end_date <=', $expiring)
            ->countAllResults();
    }

    public function countAllTags()
    {
        return $this->countAllResults();
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
