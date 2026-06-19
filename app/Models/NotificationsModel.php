<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationsModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'sender_id', 'task_id', 'message', 'is_read', 'created_at'];

    public function getUserNotifications($userId, $limit = 50)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit);
    }
}
