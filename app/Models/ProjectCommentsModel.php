<?php

namespace App\Models;
use CodeIgniter\Model;

class ProjectCommentsModel extends Model
{
    protected $table = 'project_comments';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'project_name',
        'comment',
        'commented_by',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted_at';

    public function getProjectComments($projectName)
    {
        return $this->select()
            ->where('project_name', $projectName)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
