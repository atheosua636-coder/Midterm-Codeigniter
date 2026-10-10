<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $useTimestamps = false;

    protected $allowedFields = [
        'username',
        'full_name',
        'password',
        'avatar',
        'created_at',
    ];

    protected $validationRules = [
        'username' => 'required|max_length[50]',
        'full_name' => 'required|max_length[100]',
        'password' => 'permit_empty|max_length[255]',
        'avatar' => 'permit_empty|max_length[255]',
    ];
}