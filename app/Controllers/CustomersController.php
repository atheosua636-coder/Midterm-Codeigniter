<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class CustomersController extends BaseController
{
    public function index()
    {
        $model = new CustomerModel();

        return view('customers/index', [
            'customers' => $model->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function createForm()
    {
        return view('customers/form', ['customer' => null]);
    }

    public function create()
    {
        if (! $this->validateCustomer()) {
            return redirect()->back()->withInput();
        }

        $data = $this->request->getPost(['full_name', 'email', 'phone']);
        $data['phone'] = $data['phone'] ?: null;
        $data['created_at'] = date('Y-m-d H:i:s');

        $model = new CustomerModel();

        if ($model->insert($data) === false) {
            session()->setFlashdata('errors', $model->errors());
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Customer added.');
        return redirect()->to(site_url('customers'));
    }

    public function edit($id)
    {
        $customer = (new CustomerModel())->find($id);

        if (! $customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('customers/form', ['customer' => $customer]);
    }

    public function update($id)
    {
        $model = new CustomerModel();
        $customer = $model->find($id);

        if (! $customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (! $this->validateCustomer()) {
            return redirect()->back()->withInput();
        }

        $data = $this->request->getPost(['full_name', 'email', 'phone']);
        $data['phone'] = $data['phone'] ?: null;

        if ($model->update($id, $data) === false) {
            session()->setFlashdata('errors', $model->errors());
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Customer updated.');
        return redirect()->to(site_url('customers'));
    }

    public function delete($id)
    {
        $model = new CustomerModel();
        $customer = $model->find($id);

        if (! $customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Keep sales history, but remove its link to this customer.
        $db = db_connect();
        $db->transBegin();

        $db->table('sales')
            ->where('customer_id', $id)
            ->update(['customer_id' => null]);

        $deleted = $model->delete($id);

        if ($deleted === false || $db->transStatus() === false) {
            $db->transRollback();
            session()->setFlashdata('error', 'Could not delete this customer.');
            return redirect()->to(site_url('customers'));
        }

        $db->transCommit();

        session()->setFlashdata('success', 'Customer deleted.');
        return redirect()->to(site_url('customers'));
    }

    private function validateCustomer(): bool
    {
        $valid = $this->validate([
            'full_name' => 'required|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'phone' => 'permit_empty|max_length[20]',
        ]);

        if (! $valid) {
            session()->setFlashdata('errors', $this->validator->getErrors());
        }

        return $valid;
    }
}