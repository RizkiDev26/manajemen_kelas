<?php

namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        helper(['form']);
        return view('login');
    }

    public function authenticate()
    {
        helper(['form']);
        $session = session();

        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return view('login', [
                'validation' => $this->validator
            ]);
        }

        $model = new UserModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $model->authenticate($username, $password);

        if ($user) {
            // Update last login
            $model->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            
            $session->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'nama' => $user['nama'],
                'role' => $user['role'],
                'walikelas_id' => $user['walikelas_id'],
                'isLoggedIn' => true,
            ]);
            
            // Redirect berdasarkan role
            if ($user['role'] === 'admin') {
                return redirect()->to('/admin/dashboard');
            } else {
                return redirect()->to('/admin/dashboard'); // Sementara semua ke dashboard yang sama
            }
        } else {
            $session->setFlashdata('error', 'Invalid username or password');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        $session = session();
        
        // Destroy all session data
        $session->destroy();
        
        // Redirect to login page with success message
        return redirect()->to('/login')->with('success', 'Anda berhasil logout');
    }
}
