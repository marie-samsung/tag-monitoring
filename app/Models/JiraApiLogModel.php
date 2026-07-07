<?php

namespace App\Models;

use CodeIgniter\Model;

class JiraApiLogModel extends Model
{
    protected $table = 'mt_jira_api_log';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'task_id',
        'request_type',
        'endpoint_url',
        'payload',
        'response_body',
        'status_code',
        'error_message',
        'status',
        'retry_count'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected function initialize()
    {
        parent::initialize();
        
        if (!$this->db->tableExists($this->table)) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `{$this->table}` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `task_id` int(11) DEFAULT NULL,
                  `request_type` varchar(50) NOT NULL COMMENT 'e.g., CREATE_ISSUE, UPDATE_STATUS',
                  `endpoint_url` varchar(255) NOT NULL,
                  `payload` text,
                  `response_body` text,
                  `status_code` int(11) DEFAULT NULL,
                  `error_message` varchar(255) DEFAULT NULL,
                  `status` enum('SUCCESS','FAILED','PENDING_RETRY') NOT NULL DEFAULT 'FAILED',
                  `retry_count` int(11) NOT NULL DEFAULT 0,
                  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  KEY `idx_task_id` (`task_id`),
                  KEY `idx_status` (`status`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        }
    }

    /**
     * Helper to log a new JIRA API request.
     *
     * @param int|null $taskId
     * @param string $requestType (e.g. 'CREATE_ISSUE', 'UPDATE_STATUS')
     * @param string $endpointUrl
     * @param string $payload
     * @param string|null $responseBody
     * @param int|null $statusCode
     * @param string|null $errorMessage
     * @param string $status ('SUCCESS', 'FAILED', 'PENDING_RETRY')
     * @return int|string|bool insert ID or false
     */
    public function logRequest($taskId, $requestType, $endpointUrl, $payload, $responseBody = null, $statusCode = null, $errorMessage = null, $status = 'FAILED')
    {
        $data = [
            'task_id'       => $taskId,
            'request_type'  => $requestType,
            'endpoint_url'  => $endpointUrl,
            'payload'       => $payload,
            'response_body' => $responseBody,
            'status_code'   => $statusCode,
            'error_message' => $errorMessage,
            'status'        => $status,
            'retry_count'   => 0
        ];

        $insertId = $this->insert($data);

        // Notify Admins on failure
        if ($status === 'FAILED') {
            $usersModel = new \App\Models\UsersModel();
            $notificationsModel = new \App\Models\NotificationsModel();
            
            // Get Admin users (account_type = 1)
            $admins = $usersModel->where('account_type', 1)->findAll();
            $currentUserId = (function_exists('session') && session('user_id')) ? session('user_id') : 0;
            
            foreach ($admins as $admin) {
                $notificationsModel->insert([
                    'user_id' => $admin['id'],
                    'sender_id' => $currentUserId,
                    'task_id' => $taskId,
                    'message' => "JIRA API Request ({$requestType}) failed. Please check API Logs.",
                ]);
            }
        }

        return $insertId;
    }
}
