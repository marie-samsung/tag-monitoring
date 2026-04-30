<?php

namespace App\Models;

use CodeIgniter\Model;

class TagRequestFilesModel extends Model
{
    protected $table = 'tag_request_files';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'file_name',
        'file_path',
        'type',
        'request_id',
        'created_by',
    ];

    protected $useTimestamps = true;

    public function getRelatedFiles($request_id)
    {
        return $this->select()
            ->where('request_id', $request_id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
