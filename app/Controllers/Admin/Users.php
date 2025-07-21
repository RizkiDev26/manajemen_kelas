<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\WalikelasModel;
use App\Models\TbSiswaModel;

class Users extends BaseController
{
    protected $userModel;
    protected $walikelasModel;
    protected $tbSiswaModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->walikelasModel = new WalikelasModel();
        $this->tbSiswaModel = new TbSiswaModel();
    }

    /**
     * Display users list
     */
    public function index()
    {
        // Validate session security
        $sessionCheck = $this->validateSession();
        if ($sessionCheck) {
            return $sessionCheck;
        }

        $userRole = $this->session->get('role');
        if ($userRole !== 'admin') {
            return redirect()->to('/admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get all users with walikelas info using prepared statements for security
        $db = \Config\Database::connect();
        $query = $db->prepare(function($db) {
            return $db->query("
                SELECT u.*, w.kelas 
                FROM users u 
                LEFT JOIN walikelas w ON u.walikelas_id = w.id 
                ORDER BY u.created_at DESC
            ");
        });
        $users = $query->execute()->getResultArray();

        // Get all walikelas for dropdown
        $walikelas = $this->walikelasModel->findAll();

        $data = [
            'title' => 'Kelola User',
            'users' => $this->sanitizeOutput($users),
            'walikelas' => $this->sanitizeOutput($walikelas)
        ];

        return view('admin/users/index', $data);
    }

    /**
     * Create new user
     */
    public function create()
    {
        // Validate session security
        $sessionCheck = $this->validateSession();
        if ($sessionCheck) {
            return $sessionCheck;
        }

        $userRole = $this->session->get('role');
        if ($userRole !== 'admin') {
            return redirect()->to('/admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get all walikelas for existing assignments
        $walikelas = $this->walikelasModel->findAll();
        
        // Get all available kelas from tb_siswa (actual classes with students)
        $tbSiswaModel = new \App\Models\TbSiswaModel();
        $availableKelas = $tbSiswaModel->getActiveClasses();

        $data = [
            'title' => 'Tambah User',
            'walikelas' => $this->sanitizeOutput($walikelas),
            'availableKelas' => $this->sanitizeOutput($availableKelas)
        ];

        return view('admin/users/create', $data);
    }

    /**
     * Store new user
     */
    public function store()
    {
        // Validate session security
        $sessionCheck = $this->validateSession();
        if ($sessionCheck) {
            return $sessionCheck;
        }

        $userRole = $this->session->get('role');
        if ($userRole !== 'admin') {
            return redirect()->to('/admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Enhanced validation rules with comprehensive security checks
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]|regex_match[/^[a-zA-Z0-9@._-]+$/]',
            'password' => 'required|min_length[8]|max_length[255]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/]',
            'nama' => 'required|max_length[255]|regex_match[/^[a-zA-Z\s]+$/]',
            'email' => 'permit_empty|valid_email|is_unique[users.email]|max_length[255]',
            'role' => 'required|in_list[admin,walikelas,wali_kelas]',
            'nip' => 'permit_empty|numeric|min_length[8]|max_length[20]'
        ];

        // Use enhanced validation method
        $validatedData = $this->validateAndSanitizeInput($rules);
        if ($validatedData === false) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get walikelas_id and handle it before validation
        $walikelasId = $this->request->getPost('walikelas_id');
        $finalWalikelasId = null;
        
        // Handle new class assignment (with "new_" prefix)
        if ($walikelasId && strpos($walikelasId, 'new_') === 0) {
            // Extract class name from "new_ClassName" format
            $kelasName = urldecode(substr($walikelasId, 4));
            
            // Validate class name format
            if (!preg_match('/^[a-zA-Z0-9\s-]+$/', $kelasName)) {
                return redirect()->back()->withInput()->with('error', 'Format nama kelas tidak valid');
            }
            
            // Check if this class already has a wali kelas
            $existingWali = $this->walikelasModel->where('kelas', $kelasName)->first();
            if ($existingWali) {
                return redirect()->back()->withInput()->with('error', 'Kelas ' . esc($kelasName) . ' sudah memiliki wali kelas');
            }
            
            // Get NIP from request and validate
            $nipInput = $validatedData['nip'] ?? null;
            if ($nipInput) {
                // Check uniqueness
                $existingNip = $this->walikelasModel->where('nip', $nipInput)->first();
                if ($existingNip) {
                    return redirect()->back()->withInput()->with('error', 'NIP ' . esc($nipInput) . ' sudah digunakan');
                }
            }
            
            // Create new walikelas entry
            $walikelasData = [
                'nama' => $validatedData['nama'],
                'kelas' => $kelasName,
                'nip' => $nipInput ?: $this->generateUniqueNIP(),
            ];
            
            $newWalikelasId = $this->walikelasModel->insert($walikelasData);
            if ($newWalikelasId) {
                $finalWalikelasId = $newWalikelasId;
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal membuat assignment kelas baru');
            }
        } else {
            // Use existing walikelas_id
            $finalWalikelasId = $walikelasId ? (int)$walikelasId : null;
        }
        
        $userData = [
            'username' => $validatedData['username'],
            'password' => password_hash($validatedData['password'], PASSWORD_DEFAULT),
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'] ?: null,
            'role' => $validatedData['role'],
            'walikelas_id' => $finalWalikelasId,
            'is_active' => 1
        ];

        if ($this->userModel->insert($userData)) {
            return redirect()->to('/admin/users')->with('success', 'User berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan user');
        }
    }

    /**
     * Edit user
     */
    public function edit($id)
    {
        // Check if user is logged in and is admin
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userRole = session()->get('role');
        if ($userRole !== 'admin') {
            return redirect()->to('/admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get user with walikelas information
        $user = $this->userModel->getUserWithWalikelas($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan');
        }

        // Get all walikelas for existing assignments
        $walikelas = $this->walikelasModel->findAll();
        
        // Get all available kelas from tb_siswa (actual classes with students)
        $tbSiswaModel = new \App\Models\TbSiswaModel();
        $availableKelas = $tbSiswaModel->getActiveClasses();

        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'walikelas' => $walikelas,
            'availableKelas' => $availableKelas
        ];

        return view('admin/users/edit', $data);
    }

    /**
     * Update user
     */
    public function update($id)
    {
        // Check if user is logged in and is admin
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userRole = session()->get('role');
        if ($userRole !== 'admin') {
            return redirect()->to('/admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan');
        }

        // Debug: Log incoming request data
        log_message('debug', 'Update User Request Data: ' . json_encode($this->request->getPost()));

        // Get walikelas_id and handle it before validation
        $walikelasId = $this->request->getPost('walikelas_id');
        $finalWalikelasId = null;
        
        // Handle new class assignment (with "new_" prefix)
        if ($walikelasId && strpos($walikelasId, 'new_') === 0) {
            // Extract class name from "new_ClassName" format
            $kelasName = urldecode(substr($walikelasId, 4));
            
            // Check if this class already has a wali kelas (except current user)
            $existingWali = $this->walikelasModel->where('kelas', $kelasName)->first();
            if ($existingWali && $user['walikelas_id'] != $existingWali['id']) {
                return redirect()->back()->withInput()->with('error', 'Kelas ' . $kelasName . ' sudah memiliki wali kelas');
            }
            
            // If user already has this class, use existing ID
            if ($existingWali && $user['walikelas_id'] == $existingWali['id']) {
                $finalWalikelasId = $existingWali['id'];
                
                // Update NIP if provided
                $nipInput = $this->request->getPost('nip');
                if ($nipInput) {
                    // Validate NIP format (must be numeric)
                    if (!is_numeric($nipInput)) {
                        return redirect()->back()->withInput()->with('error', 'NIP harus berupa angka');
                    }
                    
                    // Check if NIP is unique (excluding current walikelas)
                    $nipCheck = $this->walikelasModel->where('nip', $nipInput)->where('id !=', $existingWali['id'])->first();
                    if ($nipCheck) {
                        return redirect()->back()->withInput()->with('error', 'NIP ' . $nipInput . ' sudah digunakan');
                    }
                    // Update existing walikelas with new NIP
                    $this->walikelasModel->update($existingWali['id'], ['nip' => $nipInput]);
                }
            } else {
                // Get NIP from request and validate
                $nipInput = $this->request->getPost('nip');
                if ($nipInput) {
                    // Validate NIP format (must be numeric)
                    if (!is_numeric($nipInput)) {
                        return redirect()->back()->withInput()->with('error', 'NIP harus berupa angka');
                    }
                    
                    // Check uniqueness
                    $existingNip = $this->walikelasModel->where('nip', $nipInput)->first();
                    if ($existingNip) {
                        return redirect()->back()->withInput()->with('error', 'NIP ' . $nipInput . ' sudah digunakan');
                    }
                }
                
                // Create new walikelas entry
                $walikelasData = [
                    'nama' => $this->request->getPost('nama'),
                    'kelas' => $kelasName,
                    'nip' => $nipInput ?: $this->generateUniqueNIP(), // Use input NIP or generate unique numeric NIP
                ];
                
                $newWalikelasId = $this->walikelasModel->insert($walikelasData);
                if ($newWalikelasId) {
                    $finalWalikelasId = $newWalikelasId;
                } else {
                    return redirect()->back()->withInput()->with('error', 'Gagal membuat assignment kelas baru');
                }
            }
        } else {
            // Use existing walikelas_id
            $finalWalikelasId = $walikelasId ? (int)$walikelasId : null;
            
            // If user selects existing walikelas and provides NIP, update the walikelas record
            $nipInput = $this->request->getPost('nip');
            if ($finalWalikelasId && $nipInput) {
                // Validate NIP format (must be numeric)
                if (!is_numeric($nipInput)) {
                    return redirect()->back()->withInput()->with('error', 'NIP harus berupa angka');
                }
                
                // Get current walikelas data
                $currentWalikelas = $this->walikelasModel->find($finalWalikelasId);
                if ($currentWalikelas) {
                    // Check if NIP is unique (excluding current walikelas)
                    $nipCheck = $this->walikelasModel->where('nip', $nipInput)->where('id !=', $finalWalikelasId)->first();
                    if ($nipCheck) {
                        return redirect()->back()->withInput()->with('error', 'NIP ' . $nipInput . ' sudah digunakan');
                    }
                    // Update existing walikelas with new NIP
                    $this->walikelasModel->update($finalWalikelasId, ['nip' => $nipInput]);
                }
            }
        }

        // Validation rules
        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,$id]",
            'nama' => 'required|max_length[255]',
            'email' => "permit_empty|valid_email|is_unique[users.email,id,$id]",
            'role' => 'required|in_list[admin,walikelas,wali_kelas]'
        ];

        // Add password validation only if password is provided
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data
        $data = [
            'username' => $this->request->getPost('username'),
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email') ?: null, // Handle empty email
            'role' => $this->request->getPost('role'),
            'walikelas_id' => $finalWalikelasId,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        // Update password if provided
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        // Debug: Log update data
        log_message('debug', 'Update User Data: ' . json_encode($data));

        try {
            // Use skipValidation to bypass model validation since we already validated in controller
            $updateResult = $this->userModel->skipValidation()->update($id, $data);
            
            // Debug: Log update result
            log_message('debug', 'Update User Result: ' . ($updateResult ? 'success' : 'failed'));
            
            if ($updateResult) {
                return redirect()->to('/admin/users')->with('success', 'User berhasil diupdate');
            } else {
                // Get database errors if any
                $errors = $this->userModel->errors();
                log_message('error', 'Update User Errors: ' . json_encode($errors));
                return redirect()->back()->withInput()->with('error', 'Gagal mengupdate user. ' . (!empty($errors) ? implode(', ', $errors) : 'Database error'));
            }
        } catch (\Exception $e) {
            log_message('error', 'Update User Exception: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        // Check if user is logged in and is admin
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userRole = session()->get('role');
        if ($userRole !== 'admin') {
            return redirect()->to('/admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan');
        }

        // Prevent deleting current logged in user
        if ($id == session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'Tidak dapat menghapus user yang sedang login');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/users')->with('success', 'User berhasil dihapus');
        } else {
            return redirect()->to('/admin/users')->with('error', 'Gagal menghapus user');
        }
    }

    /**
     * Toggle user active status via AJAX
     */
    public function toggleStatus($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/users');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User tidak ditemukan']);
        }

        // Prevent deactivating current logged in user
        if ($id == session()->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak dapat menonaktifkan user yang sedang login']);
        }

        $newStatus = $user['is_active'] ? 0 : 1;
        
        if ($this->userModel->update($id, ['is_active' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Status user berhasil diubah',
                'newStatus' => $newStatus
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengubah status user']);
        }
    }

    /**
     * Generate unique numeric NIP
     */
    private function generateUniqueNIP()
    {
        do {
            // Generate NIP format: 19 + YYYYMMDD + 4 random digits
            $nip = '19' . date('Ymd') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            
            // Check if this NIP already exists
            $existing = $this->walikelasModel->where('nip', $nip)->first();
        } while ($existing);
        
        return $nip;
    }
}
