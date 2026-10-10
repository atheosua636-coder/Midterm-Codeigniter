<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function loginForm()
    {
        return view('authentication/login');
    }

    public function attemptLogin()
    {
        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

       if ($username === '' || $password === '') {
            session()->setFlashdata('error', 'Enter your username and password.');
            return redirect()->to(site_url('login'));
        }

       $user = db_connect()
            ->table('users')
            ->where('username', $username)
            ->get()
            ->getRowArray();

        if (! $user || ! password_verify($password, $user['password'])) {
            session()->setFlashdata('error', 'The username or password is incorrect.');
            return redirect()->to(site_url('login'));
        }
        
        session()->regenerate();
        session()->set([
            'staff_id'   => $user['id'],
            'staff_name' => $user['full_name'],
        ]);

        return redirect()->to(site_url('products'));

    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('login'));
    }
}