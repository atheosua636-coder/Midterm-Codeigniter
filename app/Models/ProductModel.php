<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $useTimestamps = false;

    protected $allowedFields = [
        'name',
        'price',
        'stock_quantity',
        'image',
        'created_at',
    ];

    protected $validationRules = [
        'name' => 'required|max_length[100]',
        'price' => 'required|decimal|greater_than_equal_to[0]',
        'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
        'image' => 'permit_empty|max_length[255]',
    ];
}