<?php

namespace App\Models;
use CodeIgniter\Model;

class JiraHistoryModel extends Model
{
    protected $table = 'jira_history';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'ticket_id', // GTA-XXXXX
        'tag_id', // Refer to 'Tags' table
        'is_active', // bool
        'created_by', // Refer to 'Users' table
    ];

    protected $useTimestamps = true;
}