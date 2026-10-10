<?php

namespace App\Controllers;

use App\Models\UserModel;

class StaffController extends BaseController
{
    public function index()
    {
        $model = new UserModel();

        // Never send password hashes to a view.
        $staff = $model
            ->select('id, username, full_name, avatar, created_at')
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('staff/index', ['staff' => $staff]);
    }

    public function createForm()
    {
        return view('staff/form', ['staff' => null]);
    }

    public function create()
    {
        if (! $this->validateStaff(null, true)) {
            return redirect()->back()->withInput();
        }

        $avatar = $this->storeAvatar();

        if ($avatar['error']) {
            session()->setFlashdata('error', $avatar['error']);
            return redirect()->back()->withInput();
        }

        $data = $this->request->getPost(['username', 'full_name']);
        $data['password'] = password_hash(
            (string) $this->request->getPost('password'),
            PASSWORD_DEFAULT
        );
        $data['avatar'] = $avatar['filename'];
        $data['created_at'] = date('Y-m-d H:i:s');

        $model = new UserModel();

        if ($model->insert($data) === false) {
            session()->setFlashdata('errors', $model->errors());
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Staff account added.');
        return redirect()->to(site_url('staff'));
    }

    public function edit($id)
    {
        $staff = (new UserModel())->find($id);

        if (! $staff) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        unset($staff['password']);

        return view('staff/form', ['staff' => $staff]);
    }

    public function update($id)
    {
        $model = new UserModel();
        $staff = $model->find($id);

        if (! $staff) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (! $this->validateStaff((int) $id, false)) {
            return redirect()->back()->withInput();
        }

        $avatar = $this->storeAvatar();

        if ($avatar['error']) {
            session()->setFlashdata('error', $avatar['error']);
            return redirect()->back()->withInput();
        }

        $data = $this->request->getPost(['username', 'full_name']);
        $newPassword = (string) $this->request->getPost('password');

        // A blank password field means keep the existing password.
        if ($newPassword !== '') {
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        if ($avatar['filename'] !== null) {
            $data['avatar'] = $avatar['filename'];
        }

        if ($model->update($id, $data) === false) {
            session()->setFlashdata('errors', $model->errors());
            return redirect()->back()->withInput();
        }

        if (
            $avatar['filename'] !== null
            && ! empty($staff['avatar'])
            && is_file(FCPATH . 'uploads/avatars/' . $staff['avatar'])
        ) {
            unlink(FCPATH . 'uploads/avatars/' . $staff['avatar']);
        }

        session()->setFlashdata('success', 'Staff account updated.');
        return redirect()->to(site_url('staff'));
    }

    public function delete($id)
    {
        $model = new UserModel();
        $staff = $model->find($id);

        if (! $staff) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ((int) session()->get('staff_id') === (int) $id) {
            session()->setFlashdata('error', 'You cannot delete your own account.');
            return redirect()->to(site_url('staff'));
        }

        // Sales retain their staff reference, so accounts with sales cannot be deleted.
        $salesCount = db_connect()
            ->table('sales')
            ->where('sold_by', $id)
            ->countAllResults();

        if ($salesCount > 0) {
            session()->setFlashdata(
                'error',
                'This staff account has sales records and cannot be deleted.'
            );
            return redirect()->to(site_url('staff'));
        }

        $model->delete($id);

        if (
            ! empty($staff['avatar'])
            && is_file(FCPATH . 'uploads/avatars/' . $staff['avatar'])
        ) {
            unlink(FCPATH . 'uploads/avatars/' . $staff['avatar']);
        }

        session()->setFlashdata('success', 'Staff account deleted.');
        return redirect()->to(site_url('staff'));
    }

    private function validateStaff(?int $id, bool $creating): bool
    {
        $usernameRule = $id === null
            ? 'is_unique[users.username]'
            : 'is_unique[users.username,id,' . $id . ']';

        $passwordRule = $creating
            ? 'required|min_length[8]'
            : 'permit_empty|min_length[8]';

        $valid = $this->validate([
            'username' => 'required|max_length[50]|' . $usernameRule,
            'full_name' => 'required|max_length[100]',
            'password' => $passwordRule,
        ]);

        if (! $valid) {
            session()->setFlashdata('errors', $this->validator->getErrors());
        }

        return $valid;
    }

    private function storeAvatar(): array
    {
        $file = $this->request->getFile('avatar');

        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return ['filename' => null, 'error' => null];
        }

        if (! $file->isValid() || $file->hasMoved()) {
            return ['filename' => null, 'error' => 'The avatar upload failed.'];
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
                'error' => 'Upload a JPG, PNG, or WEBP avatar.',
            ];
        }

        if ($file->getSize() > 2 * 1024 * 1024) {
            return [
                'filename' => null,
                'error' => 'The avatar must be 2 MB or smaller.',
            ];
        }

        if (@getimagesize($file->getTempName()) === false) {
            return ['filename' => null, 'error' => 'The uploaded file is not a valid image.'];
        }

        $directory = FCPATH . 'uploads/avatars/';

        if (! is_dir($directory) && ! mkdir($directory, 0755, true)) {
            return ['filename' => null, 'error' => 'Could not create the avatar upload folder.'];
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
        $file->move($directory, $filename);

        return ['filename' => $filename, 'error' => null];
    }
}