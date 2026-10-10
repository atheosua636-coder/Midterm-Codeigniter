<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\ProductModel;

class SalesController extends BaseController
{
    public function createForm()
    {
        $products = (new ProductModel())
            ->where('stock_quantity >', 0)
            ->orderBy('name', 'ASC')
            ->findAll();

        $customers = (new CustomerModel())
            ->orderBy('full_name', 'ASC')
            ->findAll();

        return view('sales/form', [
            'products' => $products,
            'customers' => $customers,
        ]);
    }

    public function record()
    {
        $valid = $this->validate([
            'product_id' => 'required|integer|greater_than[0]',
            'customer_id' => 'permit_empty|integer|greater_than[0]',
            'quantity' => 'required|integer|greater_than[0]',
        ]);

        if (! $valid) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }

        $productId = (int) $this->request->getPost('product_id');
        $quantity = (int) $this->request->getPost('quantity');
        $customerInput = $this->request->getPost('customer_id');
        $customerId = $customerInput === '' ? null : (int) $customerInput;
        $staffId = (int) session()->get('staff_id');

        $db = db_connect();
        $db->transBegin();

        // Lock the product row while checking and updating its stock.
        $product = $db->query(
            'SELECT id, price, stock_quantity
             FROM products
             WHERE id = ?
             FOR UPDATE',
            [$productId]
        )->getRowArray();

        if (! $product) {
            $db->transRollback();
            session()->setFlashdata('error', 'That product was not found.');
            return redirect()->back()->withInput();
        }

        if ($quantity > (int) $product['stock_quantity']) {
            $db->transRollback();
            session()->setFlashdata(
                'error',
                'There is not enough stock for that quantity.'
            );
            return redirect()->back()->withInput();
        }

        if ($customerId !== null) {
            $customer = $db->table('customers')
                ->where('id', $customerId)
                ->get()
                ->getRowArray();

            if (! $customer) {
                $db->transRollback();
                session()->setFlashdata('error', 'That customer was not found.');
                return redirect()->back()->withInput();
            }
        }

        // Calculate using cents to avoid floating-point currency rounding.
        $priceParts = explode('.', (string) $product['price'], 2);
        $whole = (int) $priceParts[0];
        $fraction = str_pad($priceParts[1] ?? '0', 2, '0');
        $priceInCents = ($whole * 100) + (int) substr($fraction, 0, 2);
        $totalInCents = $priceInCents * $quantity;

        $totalPrice = intdiv($totalInCents, 100)
            . '.'
            . str_pad((string) ($totalInCents % 100), 2, '0', STR_PAD_LEFT);

        // The condition protects stock even if another request changes it.
        $db->query(
            'UPDATE products
             SET stock_quantity = stock_quantity - ?
             WHERE id = ? AND stock_quantity >= ?',
            [$quantity, $productId, $quantity]
        );

        if ($db->affectedRows() !== 1) {
            $db->transRollback();
            session()->setFlashdata('error', 'Stock changed. Please try the sale again.');
            return redirect()->back()->withInput();
        }

        $inserted = $db->table('sales')->insert([
            'product_id' => $productId,
            'customer_id' => $customerId,
            'sold_by' => $staffId,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if ($inserted === false || $db->transStatus() === false) {
            $db->transRollback();
            session()->setFlashdata('error', 'The sale could not be recorded.');
            return redirect()->back()->withInput();
        }

        $db->transCommit();

        session()->setFlashdata('success', 'Sale recorded.');
        return redirect()->to(site_url('sales/history'));
    }

    public function history()
    {
        $sales = db_connect()
            ->table('sales')
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
            ->get()
            ->getResultArray();

        return view('sales/history', ['sales' => $sales]);
    }
}