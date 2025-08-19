<?php

namespace App\Controllers;

class LoginTest extends BaseController
{
    public function index()
    {
        return '<h1>Login Test</h1>
        <form action="/login-test" method="post">
            <div>
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>';
    }

    public function authenticate()
    {
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        if ($username === 'admin' && $password === '123456') {
            return '<h1>Login Successful!</h1><p>Welcome, admin!</p><a href="/admin/dashboard">Go to Dashboard</a>';
        } else {
            return '<h1>Login Failed!</h1><p>Invalid credentials</p><a href="/login-test">Try Again</a>';
        }
    }
}
