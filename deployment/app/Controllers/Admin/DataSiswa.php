<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\WalikelasModel;

class DataSiswa extends BaseController
{
    protected $siswaModel;
    protected $walikelasModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->walikelasModel = new WalikelasModel();
    }

    public function index()
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Get user info from session
        $userRole = $session->get('role');
        $userId = $session->get('user_id');
        $walikelasId = $session->get('walikelas_id');

        // Get parameters for pagination and search
        $perPage = 20;
        $page = $this->request->getVar('page') ?? 1;
        $search = $this->request->getVar('search') ?? '';
        $kelas = $this->request->getVar('kelas') ?? '';
        $jk = $this->request->getVar('jk') ?? '';

        // Build query with filters
        $builder = $this->siswaModel;

        // Apply role-based access control
        if ($userRole === 'walikelas' && !empty($walikelasId)) {
            // Get wali kelas data to find the class they manage
            $walikelasData = $this->walikelasModel->find($walikelasId);
            if ($walikelasData && !empty($walikelasData['kelas'])) {
                // Restrict to only students in this wali kelas's class
                $builder = $builder->where('kelas', $walikelasData['kelas']);
                
                // If kelas filter is provided, it must match the wali kelas's class
                if (!empty($kelas) && $kelas !== $walikelasData['kelas']) {
                    // Override kelas filter to match wali kelas's class
                    $kelas = $walikelasData['kelas'];
                }
                
                // Set default kelas filter for wali kelas
                if (empty($kelas)) {
                    $kelas = $walikelasData['kelas'];
                }
            } else {
                // If wali kelas data not found, show no data
                $builder = $builder->where('1', '0'); // This will return no results
            }
        }
        // For admin role, no restrictions are applied

        // Apply search filter
        if (!empty($search)) {
            $builder = $builder->groupStart()
                             ->like('nama', $search)
                             ->orLike('nisn', $search)
                             ->orLike('nipd', $search)
                             ->groupEnd();
        }

        // Apply class filter (already applied for walikelas above)
        if (!empty($kelas) && $userRole !== 'walikelas') {
            $builder = $builder->where('kelas', $kelas);
        }

        // Apply gender filter
        if (!empty($jk)) {
            $builder = $builder->where('jk', $jk);
        }

        // Get paginated data
        $siswa = $builder->paginate($perPage, 'default', $page);
        $pager = $this->siswaModel->pager;

        // Get filter options based on user role
        if ($userRole === 'walikelas' && !empty($walikelasId)) {
            // For wali kelas, only show their class as option
            $walikelasData = $this->walikelasModel->find($walikelasId);
            $kelasOnly = $walikelasData && !empty($walikelasData['kelas']) ? [$walikelasData['kelas']] : [];
        } else {
            // For admin, show all available classes
            $kelasOptions = $this->siswaModel->select('kelas')
                                            ->distinct()
                                            ->where('kelas IS NOT NULL')
                                            ->orderBy('kelas')
                                            ->findAll();
            
            $kelasOnly = [];
            foreach ($kelasOptions as $item) {
                $kelasOnly[] = $item['kelas'];
            }
        }

        // Get statistics based on user role
        if ($userRole === 'walikelas' && !empty($walikelasId)) {
            // For wali kelas, only count students in their class
            $walikelasData = $this->walikelasModel->find($walikelasId);
            if ($walikelasData && !empty($walikelasData['kelas'])) {
                $siswaModelStats = new SiswaModel();
                $totalSiswa = $siswaModelStats->where('kelas', $walikelasData['kelas'])->countAllResults();
                
                $siswaModelLaki = new SiswaModel();
                $siswaLaki = $siswaModelLaki->where('kelas', $walikelasData['kelas'])->where('jk', 'L')->countAllResults();
                
                $siswaModelPerempuan = new SiswaModel();
                $siswaPerempuan = $siswaModelPerempuan->where('kelas', $walikelasData['kelas'])->where('jk', 'P')->countAllResults();
            } else {
                $totalSiswa = 0;
                $siswaLaki = 0;
                $siswaPerempuan = 0;
            }
        } else {
            // For admin, count all students
            $siswaModelStats = new SiswaModel();
            $totalSiswa = $siswaModelStats->countAllResults();
            
            $siswaModelLaki = new SiswaModel();
            $siswaLaki = $siswaModelLaki->where('jk', 'L')->countAllResults();
            
            $siswaModelPerempuan = new SiswaModel();
            $siswaPerempuan = $siswaModelPerempuan->where('jk', 'P')->countAllResults();
        }

        // Get user wali kelas data if applicable
        $userWalikelasData = null;
        if ($userRole === 'walikelas' && !empty($walikelasId)) {
            $userWalikelasData = $this->walikelasModel->find($walikelasId);
        }

        $data = [
            'siswa' => $siswa,
            'pager' => $pager,
            'currentPage' => $page,
            'perPage' => $perPage,
            'search' => $search,
            'selectedKelas' => $kelas,
            'selectedJk' => $jk,
            'kelasOptions' => $kelasOnly,
            'totalSiswa' => $totalSiswa,
            'siswaLaki' => $siswaLaki,
            'siswaPerempuan' => $siswaPerempuan,
            'userRole' => $userRole,
            'userWalikelasData' => $userWalikelasData,
            'title' => 'Data Siswa'
        ];

        return view('admin/data-siswa/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Siswa Baru'
        ];
        
        return view('admin/data-siswa/create', $data);
    }

    public function store()
    {
        // Validation rules
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama' => 'required|min_length[3]|max_length[100]',
            'nisn' => 'required|numeric|is_unique[siswa.nisn]',
            'kelas' => 'required',
            'email' => 'valid_email|is_unique[siswa.email]',
            'telepon' => 'required|numeric'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        // Store student data logic here
        // For now, just redirect with success message
        return redirect()->to('/admin/data-siswa')->with('success', 'Data siswa berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Data Siswa',
            'id' => $id
        ];
        
        return view('admin/data-siswa/edit', $data);
    }

    public function update($id)
    {
        // Update student data logic here
        return redirect()->to('/admin/data-siswa')->with('success', 'Data siswa berhasil diperbarui');
    }

    public function delete($id)
    {
        // Delete student data logic here
        return redirect()->to('/admin/data-siswa')->with('success', 'Data siswa berhasil dihapus');
    }

    public function detail($id)
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Get user info from session
        $userRole = $session->get('role');
        $walikelasId = $session->get('walikelas_id');

        $siswa = $this->siswaModel->find($id);
        
        if (!$siswa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Siswa tidak ditemukan');
        }

        // Apply role-based access control for detail view
        if ($userRole === 'walikelas' && !empty($walikelasId)) {
            // Get wali kelas data to check if the student belongs to their class
            $walikelasData = $this->walikelasModel->find($walikelasId);
            if (!$walikelasData || $siswa['kelas'] !== $walikelasData['kelas']) {
                // If student is not in wali kelas's class, deny access
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Akses ditolak: Siswa bukan dari kelas yang Anda wali');
            }
        }

        $data = [
            'siswa' => $siswa,
            'userRole' => $userRole,
            'title' => 'Detail Siswa - ' . $siswa['nama']
        ];

        return view('admin/data-siswa/detail', $data);
    }

    public function export()
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Get user info from session
        $userRole = $session->get('role');
        $walikelasId = $session->get('walikelas_id');

        // Build query based on user role
        $builder = $this->siswaModel;

        // Apply role-based access control for export
        if ($userRole === 'walikelas' && !empty($walikelasId)) {
            // Get wali kelas data to find the class they manage
            $walikelasData = $this->walikelasModel->find($walikelasId);
            if ($walikelasData && !empty($walikelasData['kelas'])) {
                // Restrict export to only students in this wali kelas's class
                $builder = $builder->where('kelas', $walikelasData['kelas']);
                $filenamePrefix = 'data_siswa_' . str_replace(' ', '_', $walikelasData['kelas']) . '_';
            } else {
                // If wali kelas data not found, export nothing
                $builder = $builder->where('1', '0');
                $filenamePrefix = 'data_siswa_empty_';
            }
        } else {
            // For admin, export all students
            $filenamePrefix = 'data_siswa_all_';
        }

        // Get siswa data based on access control
        $siswa = $builder->orderBy('nama', 'ASC')->findAll();
        
        // Set headers for CSV download
        $filename = $filenamePrefix . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV Headers
        $headers = [
            'ID', 'Nama', 'NIPD', 'NISN', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir',
            'NIK', 'Agama', 'Alamat', 'RT', 'RW', 'Kelurahan', 'Kecamatan', 'Kode Pos',
            'Kelas', 'Nama Ayah', 'Nama Ibu', 'Telepon', 'Email'
        ];
        fputcsv($output, $headers);
        
        // Data rows
        foreach ($siswa as $row) {
            $csvRow = [
                $row['id'],
                $row['nama'],
                $row['nipd'],
                $row['nisn'],
                $row['jk'] == 'L' ? 'Laki-laki' : 'Perempuan',
                $row['tempat_lahir'],
                $row['tanggal_lahir'],
                $row['nik'],
                $row['agama'],
                $row['alamat'],
                $row['rt'],
                $row['rw'],
                $row['kelurahan'],
                $row['kecamatan'],
                $row['kode_pos'],
                $row['kelas'],
                $row['nama_ayah'],
                $row['nama_ibu'],
                $row['hp'],
                $row['email']
            ];
            fputcsv($output, $csvRow);
        }
        
        fclose($output);
        exit();
    }
}
