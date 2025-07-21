<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NilaiModel;
use App\Models\TbSiswaModel;
use App\Models\WalikelasModel;

class Nilai extends BaseController
{
    protected $nilaiModel;
    protected $tbSiswaModel;
    protected $walikelasModel;

    public function __construct()
    {
        $this->nilaiModel = new NilaiModel();
        $this->tbSiswaModel = new TbSiswaModel();
        $this->walikelasModel = new WalikelasModel();
    }

    /**
     * Index - List nilai by mata pelajaran
     */
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userRole = session()->get('role');
        $userId = session()->get('user_id');

        if (!in_array($userRole, ['admin', 'wali_kelas', 'walikelas'])) {
            return redirect()->to('/admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get user's class if wali kelas
        $userKelas = null;
        if ($userRole === 'wali_kelas' || $userRole === 'walikelas') {
            $db = \Config\Database::connect();
            $query = $db->query("
                SELECT w.kelas 
                FROM users u 
                JOIN walikelas w ON u.walikelas_id = w.id 
                WHERE u.id = ?
            ", [$userId]);
            
            $result = $query->getRowArray();
            if (!$result) {
                return redirect()->to('/admin')->with('error', 'Data wali kelas tidak ditemukan');
            }
            $userKelas = $result['kelas'];
        }

        // Get selected parameters
        $selectedKelas = $this->request->getVar('kelas') ?? $userKelas;
        $selectedMapel = $this->request->getVar('mapel') ?? 'IPAS';

        // Get all classes for admin
        $allKelas = [];
        if ($userRole === 'admin') {
            $kelasData = $this->tbSiswaModel->getActiveClasses();
            $allKelas = array_column($kelasData, 'kelas');
        }

        // Get nilai rekap
        $nilaiRekap = [];
        if ($selectedKelas) {
            $nilaiRekap = $this->nilaiModel->getNilaiRekap($selectedKelas, $selectedMapel);
        }

        // Get mata pelajaran list
        $mataPelajaranList = $this->nilaiModel->getMataPelajaranList();

        $data = [
            'title' => 'Daftar Nilai - ' . ($mataPelajaranList[$selectedMapel] ?? $selectedMapel),
            'userRole' => $userRole,
            'userKelas' => $userKelas,
            'selectedKelas' => $selectedKelas,
            'selectedMapel' => $selectedMapel,
            'allKelas' => $allKelas,
            'nilaiRekap' => $nilaiRekap,
            'mataPelajaranList' => $mataPelajaranList
        ];

        return view('admin/nilai/index', $data);
    }

    /**
     * Form tambah nilai
     */
    public function create()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userRole = session()->get('role');
        $userId = session()->get('user_id');

        if (!in_array($userRole, ['admin', 'wali_kelas', 'walikelas'])) {
            return redirect()->to('/admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get user's class if wali kelas
        $userKelas = null;
        if ($userRole === 'wali_kelas' || $userRole === 'walikelas') {
            $db = \Config\Database::connect();
            $query = $db->query("
                SELECT w.kelas 
                FROM users u 
                JOIN walikelas w ON u.walikelas_id = w.id 
                WHERE u.id = ?
            ", [$userId]);
            
            $result = $query->getRowArray();
            if (!$result) {
                return redirect()->to('/admin')->with('error', 'Data wali kelas tidak ditemukan');
            }
            $userKelas = $result['kelas'];
        }

        // Get parameters
        $selectedKelas = $this->request->getVar('kelas') ?? $userKelas;
        $selectedMapel = $this->request->getVar('mapel') ?? 'IPAS';
        $selectedSiswaId = $this->request->getVar('siswa_id') ?? '';

        // Get all classes for admin
        $allKelas = [];
        if ($userRole === 'admin') {
            $kelasData = $this->tbSiswaModel->getActiveClasses();
            $allKelas = array_column($kelasData, 'kelas');
        }

        // Get students in selected class
        $students = [];
        if ($selectedKelas) {
            $students = $this->tbSiswaModel->where('kelas', $selectedKelas)
                                          ->where('deleted_at IS NULL')
                                          ->orderBy('nama', 'ASC')
                                          ->findAll();
        }

        // Get mata pelajaran and jenis nilai list
        $mataPelajaranList = $this->nilaiModel->getMataPelajaranList();
        $jenisNilaiList = $this->nilaiModel->getJenisNilaiList();
        
        // Get selected siswa ID from request
        $selectedSiswaId = $this->request->getVar('siswa_id');

        $data = [
            'title' => 'Tambah Nilai - ' . ($mataPelajaranList[$selectedMapel] ?? $selectedMapel),
            'userRole' => $userRole,
            'userKelas' => $userKelas,
            'selectedKelas' => $selectedKelas,
            'selectedMapel' => $selectedMapel,
            'selectedSiswaId' => $selectedSiswaId,
            'allKelas' => $allKelas,
            'students' => $students,
            'mataPelajaranList' => $mataPelajaranList,
            'jenisNilaiList' => $jenisNilaiList
        ];

        return view('admin/nilai/create', $data);
    }

    /**
     * Store nilai
     */
    public function store()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userRole = session()->get('role');
        $userId = session()->get('user_id');

        if (!in_array($userRole, ['admin', 'wali_kelas', 'walikelas'])) {
            return redirect()->to('/admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get form data
        $siswaId = $this->request->getPost('siswa_id');
        $mataPelajaran = $this->request->getPost('mata_pelajaran');
        $jenisNilai = $this->request->getPost('jenis_nilai');
        $nilai = $this->request->getPost('nilai');
        $tpMateri = $this->request->getPost('tp_materi');
        $tanggal = $this->request->getPost('tanggal');
        $kelas = $this->request->getPost('kelas');

        // Validate access to class
        if (!$this->nilaiModel->canAccessClass($userId, $kelas, $userRole)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kelas tersebut');
        }

        // Prepare data
        $data = [
            'siswa_id' => $siswaId,
            'mata_pelajaran' => $mataPelajaran,
            'jenis_nilai' => $jenisNilai,
            'nilai' => $nilai,
            'tp_materi' => $tpMateri,
            'tanggal' => $tanggal,
            'kelas' => $kelas,
            'created_by' => $userId,
            'updated_by' => $userId
        ];

        // Save data
        if ($this->nilaiModel->save($data)) {
            return redirect()->to('/admin/nilai?kelas=' . $kelas . '&mapel=' . $mataPelajaran)
                           ->with('success', 'Nilai berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()
                           ->with('error', 'Gagal menambahkan nilai')
                           ->with('validation', $this->nilaiModel->errors());
        }
    }

    /**
     * Detail nilai siswa
     */
    public function detail($siswaId)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userRole = session()->get('role');
        $userId = session()->get('user_id');

        if (!in_array($userRole, ['admin', 'wali_kelas', 'walikelas'])) {
            return redirect()->to('/admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get parameters
        $selectedMapel = $this->request->getVar('mapel') ?? 'IPAS';

        // Get student data
        $student = $this->tbSiswaModel->find($siswaId);
        if (!$student) {
            return redirect()->to('/admin/nilai')->with('error', 'Siswa tidak ditemukan');
        }

        // Check access to student's class
        if (!$this->nilaiModel->canAccessClass($userId, $student['kelas'], $userRole)) {
            return redirect()->to('/admin/nilai')->with('error', 'Anda tidak memiliki akses ke kelas tersebut');
        }

        // Get nilai detail
        $nilaiDetail = $this->nilaiModel->getNilaiDetailSiswa($siswaId, $selectedMapel);

        // Get mata pelajaran list
        $mataPelajaranList = $this->nilaiModel->getMataPelajaranList();

        $data = [
            'title' => 'Detail Nilai - ' . $student['nama'],
            'student' => $student,
            'selectedMapel' => $selectedMapel,
            'nilaiDetail' => $nilaiDetail,
            'mataPelajaranList' => $mataPelajaranList
        ];

        return view('admin/nilai/detail', $data);
    }

    /**
     * Edit nilai
     */
    public function edit($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userRole = session()->get('role');
        $userId = session()->get('user_id');

        if (!in_array($userRole, ['admin', 'wali_kelas', 'walikelas'])) {
            return redirect()->to('/admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get nilai data
        $nilai = $this->nilaiModel->find($id);
        if (!$nilai) {
            return redirect()->to('/admin/nilai')->with('error', 'Nilai tidak ditemukan');
        }

        // Check access to class
        if (!$this->nilaiModel->canAccessClass($userId, $nilai['kelas'], $userRole)) {
            return redirect()->to('/admin/nilai')->with('error', 'Anda tidak memiliki akses ke nilai tersebut');
        }

        // Get student data
        $student = $this->tbSiswaModel->find($nilai['siswa_id']);

        // Get mata pelajaran and jenis nilai list
        $mataPelajaranList = $this->nilaiModel->getMataPelajaranList();
        $jenisNilaiList = $this->nilaiModel->getJenisNilaiList();

        $data = [
            'title' => 'Edit Nilai - ' . $student['nama'],
            'nilai' => $nilai,
            'student' => $student,
            'mataPelajaranList' => $mataPelajaranList,
            'jenisNilaiList' => $jenisNilaiList
        ];

        return view('admin/nilai/edit', $data);
    }

    /**
     * Update nilai
     */
    public function update($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userRole = session()->get('role');
        $userId = session()->get('user_id');

        if (!in_array($userRole, ['admin', 'wali_kelas', 'walikelas'])) {
            return redirect()->to('/admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get existing nilai
        $existingNilai = $this->nilaiModel->find($id);
        if (!$existingNilai) {
            return redirect()->to('/admin/nilai')->with('error', 'Nilai tidak ditemukan');
        }

        // Check access to class
        if (!$this->nilaiModel->canAccessClass($userId, $existingNilai['kelas'], $userRole)) {
            return redirect()->to('/admin/nilai')->with('error', 'Anda tidak memiliki akses ke nilai tersebut');
        }

        // Get form data
        $data = [
            'mata_pelajaran' => $this->request->getPost('mata_pelajaran'),
            'jenis_nilai' => $this->request->getPost('jenis_nilai'),
            'nilai' => $this->request->getPost('nilai'),
            'tp_materi' => $this->request->getPost('tp_materi'),
            'tanggal' => $this->request->getPost('tanggal'),
            'updated_by' => $userId
        ];

        // Update data
        if ($this->nilaiModel->update($id, $data)) {
            return redirect()->to('/admin/nilai?kelas=' . $existingNilai['kelas'] . '&mapel=' . $existingNilai['mata_pelajaran'])
                           ->with('success', 'Nilai berhasil diperbarui');
        } else {
            return redirect()->back()->withInput()
                           ->with('error', 'Gagal memperbarui nilai')
                           ->with('validation', $this->nilaiModel->errors());
        }
    }

    /**
     * Delete nilai
     */
    public function delete($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userRole = session()->get('role');
        $userId = session()->get('user_id');

        if (!in_array($userRole, ['admin', 'wali_kelas', 'walikelas'])) {
            return redirect()->to('/admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get nilai data
        $nilai = $this->nilaiModel->find($id);
        if (!$nilai) {
            return redirect()->to('/admin/nilai')->with('error', 'Nilai tidak ditemukan');
        }

        // Check access to class
        if (!$this->nilaiModel->canAccessClass($userId, $nilai['kelas'], $userRole)) {
            return redirect()->to('/admin/nilai')->with('error', 'Anda tidak memiliki akses ke nilai tersebut');
        }

        // Delete data
        if ($this->nilaiModel->delete($id)) {
            return redirect()->to('/admin/nilai?kelas=' . $nilai['kelas'] . '&mapel=' . $nilai['mata_pelajaran'])
                           ->with('success', 'Nilai berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus nilai');
        }
    }
}
