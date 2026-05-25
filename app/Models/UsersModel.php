<?php

namespace App\Models;
use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'first_name',
        'last_name',
        'username',
        'password',
        'account_type', // Refer to 'Constants' model for values
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = false;
}