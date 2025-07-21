<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Profile extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Profile'
        ];
        
        return view('admin/profile/index', $data);
    }

    public function update()
    {
        // Validation rules
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'telepon' => 'permit_empty|numeric',
            'alamat' => 'permit_empty|max_length[255]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        // Update profile logic here
        return redirect()->to('/admin/profile')->with('success', 'Profile berhasil diperbarui');
    }

    public function changePassword()
    {
        // Validation rules
        $validation = \Config\Services::validation();
        $validation->setRules([
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('validation', $validation);
        }

        // Change password logic here
        // Verify current password, hash new password, update database
        return redirect()->to('/admin/profile')->with('success', 'Password berhasil diubah');
    }

    public function uploadAvatar()
    {
        // File upload validation
        $validation = \Config\Services::validation();
        $validation->setRules([
            'avatar' => [
                'label' => 'Avatar',
                'rules' => 'uploaded[avatar]|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png]|max_size[avatar,2048]'
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['success' => false, 'message' => $validation->getError('avatar')]);
        }

        $file = $this->request->getFile('avatar');
        
        if ($file->isValid() && !$file->hasMoved()) {
            // Generate unique filename
            $newName = $file->getRandomName();
            
            // Move file to uploads/avatars directory
            $file->move(WRITEPATH . 'uploads/avatars', $newName);
            
            // Update user avatar in database logic here
            
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Avatar berhasil diupload',
                'filename' => $newName
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengupload avatar']);
    }
}
