<?php

namespace App\Models;
use CodeIgniter\Model;

class RenewalLogsModel extends Model
{
    protected $table = 'renewal_logs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'property_id', // Refer to Properties table
        'ticket_id', // Refer to Jira tickets
        'library_name',
        'vendor', // Refer to Vendors table
        'end_date',
        'user',
    ];

    protected $useTimestamps = true;

    public function countRenewalsToday($property_id)
    {
        $today = date('Y-m-d');
        return $this
            ->where('property_id', $property_id)
            ->like('created_at', $today, 'after')
            ->countAllResults();
    }
}