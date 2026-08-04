<?php

namespace App\Models;

use CodeIgniter\Model;

class AiConversationsModel extends Model
{
    protected $table = 'ai_conversations';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'user_id', 
        'tag_id', 
        'title', 
        'messages', 
        'created_at', 
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    /**
     * Get recent conversations for a specific user
     */
    public function getRecentByUser($userId, $limit = 20)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('updated_at', 'DESC')
                    ->findAll($limit);
    }
}
