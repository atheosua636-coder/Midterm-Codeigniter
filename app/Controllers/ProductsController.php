<?php

namespace App\Controllers;

use App\Models\ProductModel;

class ProductsController extends BaseController
{
    public function index()
    {
        $model = new ProductModel();

        return view('products/index', [
            'products' => $model->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function createForm()
    {
        return view('products/form', ['product' => null]);
    }

    public function create()
    {
        if (! $this->validateProduct()) {
            return redirect()->back()->withInput();
        }

        $image = $this->storeImage();

        if ($image['error']) {
            session()->setFlashdata('error', $image['error']);
            return redirect()->back()->withInput();
        }

        $model = new ProductModel();
        $data = $this->request->getPost([
            'name',
            'price',
            'stock_quantity',
        ]);

        $data['image'] = $image['filename'];
        $data['created_at'] = date('Y-m-d H:i:s');

        if ($model->insert($data) === false) {
            session()->setFlashdata('errors', $model->errors());
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Product added.');
        return redirect()->to(site_url('products'));
    }

    public function edit($id)
    {
        $product = (new ProductModel())->find($id);

        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('products/form', ['product' => $product]);
    }


    public function update($id)
    {
        $model = new ProductModel();
        $product = $model->find($id);

        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (! $this->validateProduct()) {
            return redirect()->back()->withInput();
        }

        $image = $this->storeImage();

        if ($image['error']) {
            session()->setFlashdata('error', $image['error']);
            return redirect()->back()->withInput();
        }

        $data = $this->request->getPost([
            'name',
            'price',
            'stock_quantity',
        ]);

        if ($image['filename'] !== null) {
            $data['image'] = $image['filename'];
        }

        if ($model->update($id, $data) === false) {
            session()->setFlashdata('errors', $model->errors());
            return redirect()->back()->withInput();
        }

        if (
            $image['filename'] !== null
            && ! empty($product['image'])
            && is_file(FCPATH . 'uploads/products/' . $product['image'])
        ) {
            unlink(FCPATH . 'uploads/products/' . $product['image']);
        }

        session()->setFlashdata('success', 'Product updated.');
        return redirect()->to(site_url('products'));
    }

    public function delete($id)
    {
        $model = new ProductModel();
        $product = $model->find($id);

        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $salesCount = db_connect()
            ->table('sales')
            ->where('product_id', $id)
            ->countAllResults();

        if ($salesCount > 0) {
            session()->setFlashdata(
                'error',
                'This product has sales records and cannot be deleted.'
            );
            return redirect()->to(site_url('products'));
        }

        $model->delete($id);

        if (
            ! empty($product['image'])
            && is_file(FCPATH . 'uploads/products/' . $product['image'])
        ) {
            unlink(FCPATH . 'uploads/products/' . $product['image']);
        }

        session()->setFlashdata('success', 'Product deleted.');
        return redirect()->to(site_url('products'));
    }

    private function validateProduct() :bool
    {
        $valid = $this->validate([
            'name' => 'required|max_length[100]',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
        ]);

        if (! $valid) {
            session()->setFlashdata('errors', $this->validator->getErrors());
        }

        return $valid;
    }

    private function storeImage(): array
    {
        $file = $this->request->getFile('image');

        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return ['filename' => null, 'error' => null];
        }

        if (! $file->isValid() || $file->hasMoved()) {
            return ['filename' => null, 'error' => 'The image upload failed.'];
        }

        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        $mimeType = $file->getMimeType();

        if (! isset($extensions[$mimeType])) {
            return [
                'filename' => null,
                'error' => 'Upload a JPG, PNG, or WEBP image.',
            ];
        }

        if ($file->getSize() > 2 * 1024 * 1024) {
            return [
                'filename' => null,
                'error' => 'The image must be 2 MB or smaller.',
            ];
        }

        if (@getimagesize($file->getTempName()) === false) {
            return ['filename' => null, 'error' => 'The uploaded file is not a valid image.'];
        }

        $directory = FCPATH . 'uploads/products/';

        if (! is_dir($directory) && ! mkdir($directory, 0755, true)) {
            return ['filename' => null, 'error' => 'Could not create the image upload folder.'];
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
        $file->move($directory, $filename);

        return ['filename' => $filename, 'error' => null];
    }
}