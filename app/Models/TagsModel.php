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
        'js_code',
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

        if (!empty($filters['js_code'])) {
            $this->like('tags.js_code', $filters['js_code']);
        }

        if (!empty($filters['conditions'])) {
            $this->like('tags.conditions', $filters['conditions']);
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

        if (!empty($filters['vendor_id'])) {
            $this->where('tags.vendor_id', $filters['vendor_id']);
        }

        if (!empty($filters['al_end_date_from'])) {
            $this->where('tags.al_end_date >=', $filters['al_end_date_from']);
        }

        if (!empty($filters['al_end_date_to'])) {
            $this->where('tags.al_end_date <=', $filters['al_end_date_to']);
        }

        $sortBy = $filters['sort_by'] ?? null;
        $sortDir = $filters['sort_dir'] ?? 'desc';
        if (!in_array(strtolower($sortDir), ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        $hasActiveFilters = false;
        if (!empty($filters['keyword']) || !empty($filters['js_code']) || !empty($filters['conditions']) || !empty($filters['property_id']) || !empty($filters['subsidiary_id']) || !empty($filters['vendor_id']) || !empty($filters['al_end_date_from']) || !empty($filters['al_end_date_to'])) {
            $hasActiveFilters = true;
        }
        if (!empty($filters['status']) && !is_array($filters['status']) && $filters['status'] !== 'all' && $filters['status'] != 145) {
            $hasActiveFilters = true;
        }

        if (!empty($sortBy)) {
            if ($sortBy === 'id') {
                $this->orderBy('tags.id', $sortDir);
            } elseif ($sortBy === 'subsidiary_id') {
                $this->orderBy('properties.subsidiary_id', $sortDir);
            } elseif ($sortBy === 'property_id') {
                $this->orderBy('properties.name', $sortDir);
            } elseif ($sortBy === 'name') {
                $this->orderBy('tags.name', $sortDir);
            } elseif ($sortBy === 'status') {
                $this->orderBy('tags.status', $sortDir);
            } elseif ($sortBy === 'al_end_date' || $sortBy === 'days') {
                $this->orderBy('tags.al_end_date', $sortDir);
            } elseif ($sortBy === 'updated_at') {
                $this->orderBy('tags.updated_at', $sortDir);
            }

            if ($hasActiveFilters)
                $this->orderBy('properties.id', 'ASC');
        } else {
            if (!empty($filters['is_repository'])) {
                $this->orderBy('properties.subsidiary_id', 'ASC');
                $this->orderBy('properties.name', 'ASC');
                $this->orderBy('IF(tags.js_code IS NOT NULL AND tags.js_code != "", 1, 0)', 'DESC', false);
                $this->orderBy('tags.updated_at', 'DESC');
            } else {
                $this->orderBy(
                    '(CASE 
                        WHEN tags.al_end_date < CURDATE() AND tags.status = 1 THEN 0
                        WHEN tags.al_end_date <= DATE_ADD(CURDATE(), INTERVAL 16 DAY) AND tags.status = 5 THEN 1
                        WHEN tags.al_end_date <= DATE_ADD(CURDATE(), INTERVAL 16 DAY) AND tags.status = 9 THEN 2
                        WHEN tags.al_end_date <= DATE_ADD(CURDATE(), INTERVAL 9 DAY) AND tags.status = 1 THEN 3
                        WHEN tags.al_end_date <= DATE_ADD(CURDATE(), INTERVAL 16 DAY) AND tags.status = 4 THEN 4
                        WHEN tags.al_end_date <= DATE_ADD(CURDATE(), INTERVAL 16 DAY) AND tags.status = 1 THEN 5
                        WHEN tags.status = 4 THEN 6
                        WHEN tags.status = 1 THEN 7
                        ELSE 8
                    END)',
                    'ASC',
                    false
                );
                $this->orderBy('tags.al_end_date', 'ASC');
                $this->orderBy('properties.id', 'ASC');
            }
        }

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
