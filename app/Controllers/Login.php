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
        $session = session();

        // Enhanced validation rules with more security checks
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|regex_match[/^[a-zA-Z0-9@._-]+$/]',
            'password' => 'required|min_length[6]|max_length[255]'
        ];

        // Use enhanced validation method
        $validatedData = $this->validateAndSanitizeInput($rules);
        if ($validatedData === false) {
            return view('login', [
                'validation' => $this->validator
            ]);
        }

        $model = new UserModel();
        $username = $validatedData['username'];
        $password = $validatedData['password'];

        // Add rate limiting protection
        $ipAddress = $this->request->getIPAddress();
        $loginAttemptKey = 'login_attempts_' . md5($ipAddress);
        $attempts = $session->get($loginAttemptKey) ?? 0;
        
        if ($attempts >= 5) {
            $session->setFlashdata('error', 'Terlalu banyak percobaan login. Silakan coba lagi nanti.');
            return redirect()->to('/login');
        }

        $user = $model->authenticate($username, $password);

        if ($user) {
            // Clear login attempts on successful login
            $session->remove($loginAttemptKey);
            
            // Regenerate session ID for security
            $session->regenerate();
            
            // Update last login
            $model->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            
            $session->set([
                'user_id' => $user['id'],
                'username' => esc($user['username']),
                'nama' => esc($user['nama']),
                'role' => esc($user['role']),
                'walikelas_id' => $user['walikelas_id'],
                'isLoggedIn' => true,
                'login_time' => time(),
                'user_agent' => $this->request->getUserAgent(),
            ]);
            
            // Redirect berdasarkan role
            if ($user['role'] === 'admin') {
                return redirect()->to('/admin/dashboard');
            } else {
                return redirect()->to('/admin/dashboard'); // Sementara semua ke dashboard yang sama
            }
        } else {
            // Increment login attempts
            $session->set($loginAttemptKey, $attempts + 1);
            $session->setFlashdata('error', 'Username atau password tidak valid');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        $session = session();
        
        // Log logout activity for security audit
        log_message('info', 'User ' . ($session->get('username') ?? 'unknown') . ' logged out from IP: ' . $this->request->getIPAddress());
        
        // Destroy all session data
        $session->destroy();
        
        // Redirect to login page with success message
        return redirect()->to('/login')->with('success', 'Anda berhasil logout');
    }
}
