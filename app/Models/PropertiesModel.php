<?php

namespace App\Models;
use CodeIgniter\Model;
use App\Models\LanguagesModel;
use App\Models\CountriesModel;

class PropertiesModel extends Model
{
    protected $table = 'properties';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'is_active', // bool
        'is_epp', // bool
        'is_shop', // bool
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

    public function WebsiteLinks()
    {
        $rows = $this->select('id, name, website, subsidiary_id, ref_link, is_shop')->findAll();
        $links = [];
        foreach ($rows as $row) {
            $links[$row['id']] = [
                'name' => $row['name'],
                'is_shop' => $row['is_shop'],
                'website' => $row['website'],
                'subsidiary_id' => $row['subsidiary_id'],
                'ref_link' => $row['ref_link'],
            ];
        }
        return $links;
    }

    public function getSiteCode($propertyId)
    {
        $property = $this->find($propertyId);
        $country = (new CountriesModel())->find($property['country_id']);
        // $language = (new LanguagesModel())->find($property['language_id']);

        // if ($country['code'] != $language['name']) {
        //     return $country['code'] . '_' . $language['name'];
        // } else {
        //     return $country['code'];
        // }

        return $country['code'];
    }

    public function isShop($propertyId)
    {
        $property = $this->find($propertyId);
        return $property['is_shop'] == Constants::YES ? 'Shop' : 'AEM';
    }

    /**
     * Filtering of properties in list view
     * @param mixed $filters
     * @return array
     */
    public function filterProperties($filters = [], $paginate = 0)
    {
        $this->select('properties.*');

        if (!empty($filters['keyword'])) {
            $this->like('properties.name', $filters['keyword']);
        }

        if (!empty($filters['subsidiary_id'])) {
            $this->where('properties.subsidiary_id', $filters['subsidiary_id']);
        }

        if (isset($filters['is_epp']) && $filters['is_epp'] !== '') {
            $this->where('properties.is_epp', $filters['is_epp']);
        }

        if (isset($filters['is_shop']) && $filters['is_shop'] !== '') {
            $this->where('properties.is_shop', $filters['is_shop']);
        }

        $sortBy = $filters['sort_by'] ?? 'subsidiary_id';
        $sortDir = $filters['sort_dir'] ?? 'asc';
        if (!in_array(strtolower($sortDir), ['asc', 'desc'])) {
            $sortDir = 'asc';
        }

        $allowedColumns = ['id', 'subsidiary_id', 'name', 'is_epp', 'is_shop'];
        if (in_array($sortBy, $allowedColumns)) {
            $this->orderBy($sortBy, $sortDir);
        } else {
            $this->orderBy('subsidiary_id', 'ASC');
        }

        if ($paginate > 0) {
            return $this->paginate($paginate);
        }

        return $this->findAll();
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
