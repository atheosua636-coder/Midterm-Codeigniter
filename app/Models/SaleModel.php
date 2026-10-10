<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $useTimestamps = false;

    protected $allowedFields = [
        'product_id',
        'customer_id',
        'sold_by',
        'quantity',
        'total_price',
        'created_at',
    ];

    protected $validationRules = [
        'product_id' => 'required|integer|greater_than[0]',
        'customer_id' => 'permit_empty|integer|greater_than[0]',
        'sold_by' => 'required|integer|greater_than[0]',
        'quantity' => 'required|integer|greater_than[0]',
        'total_price' => 'required|decimal|greater_than_equal_to[0]',
    ];

    public function history(): array
    {
        return $this
            ->select(
                'sales.id,
                 products.name AS product_name,
                 customers.full_name AS customer_name,
                 users.full_name AS staff_name,
                 sales.quantity,
                 sales.total_price,
                 sales.created_at'
            )
            ->join('products', 'products.id = sales.product_id')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->join('users', 'users.id = sales.sold_by')
            ->orderBy('sales.created_at', 'DESC')
            ->findAll();
    }
}