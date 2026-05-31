<?php

namespace App\Models;
use CodeIgniter\Model;

class ActivityLogFilesModel extends Model
{
    protected $table = 'activity_log_files';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'file_name',
        'file_path',
        'log_id',
        'created_by',
    ];

    protected $useTimestamps = true;

    public function getRelatedFiles($log_id)
    {
        return $this->select()
            ->where('log_id', $log_id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    protected $beforeInsert = ['setCreatedBy'];

    protected function setCreatedBy(array $data)
    {
        if (session()->has('user_id') && !isset($data['data']['created_by'])) {
            $data['data']['created_by'] = session()->get('user_id');
        }
        return $data;
    }
}