<?php

namespace App\Models;
use CodeIgniter\Model;

class TagsModel extends Model
{

    protected $table = 'tags';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'property_id',
        'status',
        'al_end_date',
    ];

    protected $useTimestamps = true;

    public function search($keyword)
    {
        $fields = $this->allowedFields;
        $builder = $this->builder();

        foreach ($fields as $field) {
            $builder->orLike($field, $keyword);
        }

        return $builder->get()->getResultArray();
    }

    public function filter(array $filters = [])
    {
        $builder = $this->builder();

        if (!empty($filters['keyword'])) {
            $builder->groupStart()
                    ->like('name', $filters['keyword'])
                    ->orLike('property_id', $filters['keyword'])
                    ->orLike('status', $filters['keyword'])
                    ->orLike('al_end_date', $filters['keyword'])
                    ->groupEnd();
        }

        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }

        if (!empty($filters['property_id'])) {
            $builder->where('property_id', $filters['property_id']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('al_end_date >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('al_end_date <=', $filters['date_to']);
        }

        return $builder->get()->getResultArray();
    }
}