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

    /**
     * Data TP (Tujuan Pembelajaran)
     */
    public function dataTP()
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
            
            $result = $query->getRow();
            if ($result) {
                $userKelas = $result->kelas;
            }
        }

        $data = [
            'title' => 'Data Tujuan Pembelajaran',
            'userRole' => $userRole,
            'userKelas' => $userKelas,
            'currentUser' => [
                'nama' => session()->get('nama'),
                'role' => session()->get('role')
            ]
        ];

        return view('admin/nilai/data_tp', $data);
    }

    /**
     * Input Nilai
     */
    public function inputNilai($kelasSlug = null, $mapelSlug = null)
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
            
            $result = $query->getRow();
            if ($result) {
                $userKelas = $result->kelas;
            }
        }

        // Get available classes (exclude 'Lulus')
        $availableClasses = [];
        if ($userRole === 'admin') {
            $availableClasses = $this->tbSiswaModel->select('kelas')
                ->distinct()
                ->where('kelas !=', 'Lulus')
                ->orderBy('kelas', 'ASC')
                ->findAll();
        } else if ($userKelas && $userKelas !== 'Lulus') {
            $availableClasses = [['kelas' => $userKelas]];
        }

        // Get students for selected class
    $students = [];
    // Support pretty URL slugs or fallback to query params
    $selectedKelas = $kelasSlug ? $this->unslugKelas($kelasSlug) : $this->request->getVar('kelas');
    $selectedMapel = $mapelSlug ? $this->unslugMapel($mapelSlug) : $this->request->getVar('mapel');
        $harianMatrix = null;
        
        if ($selectedKelas) {
            $students = $this->tbSiswaModel->where('kelas', $selectedKelas)
                                          ->where('deleted_at IS NULL')
                                          ->orderBy('nama', 'ASC')
                                          ->findAll();
            if ($selectedMapel) {
                // Cached fetch (60s) to reduce repeated heavy grouping
                $harianMatrix = $this->nilaiModel->getCachedNilaiHarianMatrix($selectedKelas, $selectedMapel, 60);
            }
        }

        // Dynamic subjects (mapel) from subjects table filtered by grade level if possible
        $subjectsDynamic = [];
        try {
            $subjectModel = new \App\Models\SubjectModel();
            $allSubjects = $subjectModel->listAll();
            // If selected class like "5 A" ambil angka awal sebagai grade
            $gradeNumber = null;
            if ($selectedKelas) {
                if (preg_match('/^(\d+)/', $selectedKelas, $m)) {
                    $gradeNumber = (int)$m[1];
                }
            }
            foreach ($allSubjects as $s) {
                if (!empty($s['grades']) && $gradeNumber) {
                    $grades = array_filter(array_map('intval', explode(',', $s['grades'])));
                    if ($grades && !in_array($gradeNumber, $grades)) {
                        continue; // skip not for this grade
                    }
                }
                $subjectsDynamic[] = $s['name'];
            }
        } catch(\Throwable $e) {}

        $data = [
            'title' => 'Input Nilai Siswa',
            'userRole' => $userRole,
            'userKelas' => $userKelas,
            'availableClasses' => $availableClasses,
            'students' => $students,
            'selectedKelas' => $selectedKelas,
            'selectedMapel' => $selectedMapel,
            'harianMatrix' => $harianMatrix,
            'subjectsDynamic' => $subjectsDynamic,
            'kelasSlug' => $selectedKelas ? $this->slugifyKelas($selectedKelas) : null,
            'mapelSlug' => $selectedMapel ? $this->slugifyMapel($selectedMapel) : null,
            'currentUser' => [
                'nama' => session()->get('nama'),
                'role' => session()->get('role')
            ]
        ];

        return view('admin/nilai/input', $data);
    }

    private function slugifyMapel(string $mapel): string
    {
        $mapel = strtolower(trim($mapel));
        $mapel = preg_replace('/[^a-z0-9]+/','-', $mapel);
        return trim($mapel,'-');
    }

    private function slugifyKelas(string $kelas): string
    {
        $kelas = strtolower(trim($kelas));
        $kelas = str_replace([' ','/'], '-', $kelas);
        return preg_replace('/[^a-z0-9\-]+/','', $kelas);
    }

    private function unslugKelas(?string $slug): ?string
    {
        if(!$slug) return null;
        // revert dashes to spaces & capitalize words with 'kelas'
        $slug = str_replace('-', ' ', $slug);
        // ensure leading 'kelas' capitalized
        return ucwords($slug);
    }

    private function unslugMapel(?string $slug): ?string
    {
        if(!$slug) return null;
        $slug = str_replace('-', ' ', $slug);
        return ucwords($slug);
    }

    /**
     * Cetak Nilai
     */
    public function cetakNilai()
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
            
            $result = $query->getRow();
            if ($result) {
                $userKelas = $result->kelas;
            }
        }

        // Get available classes
        $availableClasses = [];
        if ($userRole === 'admin') {
            $availableClasses = $this->tbSiswaModel->select('kelas')
                                                  ->distinct()
                                                  ->orderBy('kelas', 'ASC')
                                                  ->findAll();
        } else {
            if ($userKelas) {
                $availableClasses = [['kelas' => $userKelas]];
            }
        }

        $data = [
            'title' => 'Cetak Nilai Siswa',
            'userRole' => $userRole,
            'userKelas' => $userKelas,
            'availableClasses' => $availableClasses,
            'currentUser' => [
                'nama' => session()->get('nama'),
                'role' => session()->get('role')
            ]
        ];

        return view('admin/nilai/cetak', $data);
    }

    /**
     * Store bulk nilai harian (AJAX)
     */
    public function storeBulkHarian()
    {
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method not allowed']);
        }

        if (!session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $userRole = session()->get('role');
        $userId = session()->get('user_id');
        if (!in_array($userRole, ['admin', 'wali_kelas', 'walikelas'])) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Forbidden']);
        }

        // Support JSON and form-encoded
        $payload = $this->request->getJSON(true);
        if (!is_array($payload)) {
            $payload = $this->request->getPost();
        }

        $kelas  = $payload['kelas'] ?? null;
        $mapel  = $payload['mapel'] ?? null;
        $tanggal = $payload['tanggal'] ?? null;
    $deskripsi = $payload['deskripsi'] ?? null; // topik / TP
    $kode = $payload['kode_penilaian'] ?? null;
        $grades = $payload['grades'] ?? null; // array of ['siswa_id' => int, 'nilai' => number]

        if (!$kelas || !$mapel || !$tanggal || !is_array($grades)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
        }

        if (!$this->nilaiModel->canAccessClass($userId, $kelas, $userRole)) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Tidak memiliki akses ke kelas ini']);
        }

        // Pre-validate grades: reject if any >100 or <0
        foreach($grades as $g){
            if(isset($g['nilai']) && $g['nilai'] !== '' && $g['nilai'] !== null){
                $v = (float)$g['nilai'];
                if($v > 100){
                    return $this->response->setStatusCode(400)->setJSON(['status'=>'error','message'=>'Nilai tidak boleh lebih dari 100']);
                }
                if($v < 0){
                    return $this->response->setStatusCode(400)->setJSON(['status'=>'error','message'=>'Nilai tidak boleh kurang dari 0']);
                }
            }
        }

        $db = \Config\Database::connect();
        $hasKodeCol = false; try { $hasKodeCol = $db->fieldExists('kode_penilaian','nilai'); } catch(\Throwable $e){}
        // Duplicate kode_penilaian guard: if client sends a kode already existing for this kelas+mapel, reject to prevent accidental overwrite
        if($hasKodeCol && $kode){
            $exists = $db->table('nilai')
                ->where('kelas',$kelas)
                ->where('mata_pelajaran',$mapel)
                ->where('jenis_nilai','harian')
                ->where('kode_penilaian',$kode)
                ->where('deleted_at IS NULL')
                ->countAllResults();
            if($exists>0){
                return $this->response->setStatusCode(409)->setJSON(['status'=>'error','message'=>'Kode penilaian sudah dipakai. Silakan pilih nomor lain.']);
            }
        }

        $db->transStart();
        $inserted = 0;
        if (!$kode && $hasKodeCol) {
            // generate once if column exists
            $kode = $this->nilaiModel->getNextKodeHarian($kelas, $mapel);
        }
        foreach ($grades as $g) {
            if (!isset($g['siswa_id']) || $g['siswa_id'] === '' || $g['nilai'] === '' || $g['nilai'] === null) {
                continue;
            }
            $val = (float)$g['nilai'];
            if ($val < 0 || $val > 100) { // extra guard (should already have errored)
                continue;
            }
            $data = [
                'siswa_id' => (int)$g['siswa_id'],
                'mata_pelajaran' => $mapel,
                'jenis_nilai' => 'harian',
                'nilai' => $val,
                'tp_materi' => $deskripsi,
                'tanggal' => $tanggal,
                'kelas' => $kelas,
                'created_by' => $userId,
                'updated_by' => $userId,
            ];
            if($hasKodeCol && $kode){ $data['kode_penilaian'] = $kode; }
            $this->nilaiModel->insert($data);
            $inserted++;
        }
        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan nilai']);
        }

        // Invalidate caches for this kelas+mapel
        $this->nilaiModel->invalidateHarianCaches($kelas,$mapel);
        return $this->response->setJSON([
            'status' => 'ok',
            'message' => 'Nilai berhasil disimpan',
            'inserted' => $inserted,
            'kode_penilaian' => $kode
        ]);
    }

    /**
     * Update bulk nilai harian for selected PH (by date+tp). If record doesn't exist for a student, create it.
     */
    public function updateBulkHarian()
    {
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method not allowed']);
        }

        if (!session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $userRole = session()->get('role');
        $userId = session()->get('user_id');
        if (!in_array($userRole, ['admin', 'wali_kelas', 'walikelas'])) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Forbidden']);
        }

        $payload = $this->request->getJSON(true);
        if (!is_array($payload)) {
            $payload = $this->request->getPost();
        }

    $kelas  = $payload['kelas'] ?? null;
    $mapel  = $payload['mapel'] ?? null;
    $tanggal = $payload['tanggal'] ?? null; // updating target date
    $deskripsi = $payload['deskripsi'] ?? '';
    $kode = $payload['kode_penilaian'] ?? null; // explicit kode_penilaian from client (e.g. PH-3)
        $grades = $payload['grades'] ?? null; // [{siswa_id, nilai}]

        if (!$kelas || !$mapel || !$tanggal || !is_array($grades)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
        }

        if (!$this->nilaiModel->canAccessClass($userId, $kelas, $userRole)) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Tidak memiliki akses ke kelas ini']);
        }

        // Validate values upfront
        foreach($grades as $g){
            if(isset($g['nilai']) && $g['nilai'] !== '' && $g['nilai'] !== null){
                $v=(float)$g['nilai'];
                if($v>100){
                    return $this->response->setStatusCode(400)->setJSON(['status'=>'error','message'=>'Nilai tidak boleh lebih dari 100']);
                }
                if($v<0){
                    return $this->response->setStatusCode(400)->setJSON(['status'=>'error','message'=>'Nilai tidak boleh kurang dari 0']);
                }
            }
        }

        $db = \Config\Database::connect();
        $hasKodeCol = false; try { $hasKodeCol = $db->fieldExists('kode_penilaian','nilai'); } catch(\Throwable $e){}
        $db->transStart();
        $updated = 0;
        foreach ($grades as $g) {
            if (!isset($g['siswa_id'])) continue;
            $sid = (int)$g['siswa_id'];
            $val = $g['nilai'];
            if ($val === '' || $val === null) continue;
            $val = (float)$val;
            if ($val < 0 || $val > 100) continue;

            // Find existing row matching this PH. Priority: kode_penilaian if provided, else date.
            $builder = $this->nilaiModel->where('deleted_at IS NULL', null, false)
                ->where('kelas', $kelas)
                ->where('siswa_id', $sid)
                ->where('mata_pelajaran', $mapel)
                ->where('jenis_nilai', 'harian');
            if($hasKodeCol && $kode){
                $builder->where('kode_penilaian', $kode);
            } else {
                $builder->where('DATE(tanggal)', $tanggal);
            }
            $row = $builder->first();

        if ($row) {
                $updateData = [
                    'nilai' => $val,
                    'tp_materi' => $deskripsi,
                    'updated_by' => $userId,
                ];
                if($hasKodeCol && $kode){ $updateData['kode_penilaian'] = $kode ?: ($row['kode_penilaian'] ?? null); }
                $this->nilaiModel->update($row['id'], $updateData);
                $updated++;
            } else {
                $insertData = [
                    'siswa_id' => $sid,
                    'mata_pelajaran' => $mapel,
                    'jenis_nilai' => 'harian',
                    'nilai' => $val,
                    'tp_materi' => $deskripsi,
                    'tanggal' => $tanggal,
                    'kelas' => $kelas,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ];
                if($hasKodeCol){ $insertData['kode_penilaian'] = $kode ?: $this->nilaiModel->getNextKodeHarian($kelas, $mapel); }
                $this->nilaiModel->insert($insertData);
                $updated++;
            }
        }
        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan perubahan']);
        }

    $this->nilaiModel->invalidateHarianCaches($kelas,$mapel);
    return $this->response->setJSON(['status' => 'ok', 'updated' => $updated]);
    }

    /**
     * Dapatkan kode penilaian harian berikutnya (AJAX)
     */
    public function nextKodeHarian()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }
        $kelas = $this->request->getGet('kelas');
        $mapel = $this->request->getGet('mapel');
        if (!$kelas || !$mapel) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Parameter kelas & mapel wajib']);
        }
        $kode = $this->nilaiModel->getNextKodeHarian($kelas, $mapel);
        return $this->response->setJSON(['status' => 'ok', 'kode' => $kode]);
    }

    /**
     * Daftar nomor kode penilaian yang sudah terpakai (untuk disable dropdown).
     * GET params: kelas, mapel, jenis (harian|pts|pas), prefix (PH|PTS|PAS)
     */
    public function usedKodeHarian()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }
        $kelas = $this->request->getGet('kelas');
        $mapel = $this->request->getGet('mapel');
        $jenis = $this->request->getGet('jenis') ?: 'harian';
        $prefix = $this->request->getGet('prefix') ?: ($jenis === 'harian' ? 'PH' : strtoupper($jenis));
        if (!$kelas || !$mapel) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Parameter kelas & mapel wajib']);
        }
    $used = $this->nilaiModel->getCachedUsedKodeNumbers($kelas, $mapel, $jenis, $prefix, 60);
        return $this->response->setJSON(['status' => 'ok', 'used' => $used]);
    }

    /**
     * Hapus satu penilaian harian (seluruh nilai siswa untuk satu kode / tanggal).
     * Expect POST JSON: kelas,mapel,kode(optional),tanggal(optional)
     */
    public function deleteHarianAssessment()
    {
        // Endpoint disederhanakan: wajib kirim kelas, mapel, dan kode_penilaian (atau kode) -> hapus seluruh baris sesuai.
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method not allowed']);
        }
        if (!session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }
        $userRole = session()->get('role');
        $userId = session()->get('user_id');
        if (!in_array($userRole, ['admin','wali_kelas','walikelas'])) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Forbidden']);
        }
        $payload = $this->request->getJSON(true); if(!is_array($payload)) $payload = $this->request->getPost();
        $kelas = $payload['kelas'] ?? null;
        $mapel = $payload['mapel'] ?? null;
        $kode  = $payload['kode_penilaian'] ?? ($payload['kode'] ?? null); // accept both keys
        if(!$kelas || !$mapel || !$kode){
            return $this->response->setStatusCode(400)->setJSON(['status'=>'error','message'=>'Parameter kurang (kelas,mapel,kode_penilaian)']);
        }
        if(!$this->nilaiModel->canAccessClass($userId, $kelas, $userRole)){
            return $this->response->setStatusCode(403)->setJSON(['status'=>'error','message'=>'Tidak punya akses']);
        }
        $db = \Config\Database::connect();
        if(!$db->fieldExists('kode_penilaian','nilai')){
            return $this->response->setStatusCode(400)->setJSON(['status'=>'error','message'=>'Kolom kode_penilaian belum tersedia']);
        }
        // Hitung dulu
        $count = $db->table('nilai')
            ->where('kelas',$kelas)
            ->where('mata_pelajaran',$mapel)
            ->where('jenis_nilai','harian')
            ->where('kode_penilaian',$kode)
            ->where('deleted_at IS NULL')
            ->countAllResults();
        if($count === 0){
            return $this->response->setJSON(['status'=>'ok','deleted'=>0,'message'=>'Tidak ada data']);
        }
        // Bulk soft delete (lebih cepat dari loop)
    $db->table('nilai')
            ->where('kelas',$kelas)
            ->where('mata_pelajaran',$mapel)
            ->where('jenis_nilai','harian')
            ->where('kode_penilaian',$kode)
            ->where('deleted_at IS NULL')
            ->update(['deleted_at'=>date('Y-m-d H:i:s')]);
    $this->nilaiModel->invalidateHarianCaches($kelas,$mapel);
    return $this->response->setJSON(['status'=>'ok','deleted'=>$count]);
    }

    /**
     * Show PTS input page
     */
    public function pts()
    {
        return $this->renderExamPage('pts');
    }

    /**
     * Show PAS input page
     */
    public function pas()
    {
        return $this->renderExamPage('pas');
    }

    /**
     * Shared renderer for exam pages (PTS/PAS)
     */
    private function renderExamPage(string $examType)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userRole = session()->get('role');
        $userId = session()->get('user_id');
        if (!in_array($userRole, ['admin', 'wali_kelas', 'walikelas'])) {
            return redirect()->to('/admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Resolve walikelas' class
        $userKelas = null;
        if ($userRole === 'wali_kelas' || $userRole === 'walikelas') {
            $db = \Config\Database::connect();
            $res = $db->query("SELECT w.kelas FROM users u JOIN walikelas w ON u.walikelas_id = w.id WHERE u.id = ?", [$userId])->getRow();
            if ($res) { $userKelas = $res->kelas; }
        }

        // Admin can choose class; walikelas fixed
        $availableClasses = [];
        if ($userRole === 'admin') {
            $availableClasses = $this->tbSiswaModel->select('kelas')->distinct()->orderBy('kelas','ASC')->findAll();
        } elseif ($userKelas) {
            $availableClasses = [['kelas' => $userKelas]];
        }

        $selectedKelas = $this->request->getVar('kelas') ?? $userKelas;
        $selectedMapel = $this->request->getVar('mapel') ?? '';

        // Subject list in required order
        $orderedMapel = $this->nilaiModel->getOrderedMapelList();

        // Students in selected class
        $students = [];
        if ($selectedKelas) {
            $students = $this->tbSiswaModel->where('kelas', $selectedKelas)
                ->where('deleted_at IS NULL', null, false)
                ->orderBy('nama', 'ASC')->findAll();
        }

        $data = [
            'title' => strtoupper($examType) . ' - Input Nilai',
            'userRole' => $userRole,
            'userKelas' => $userKelas,
            'availableClasses' => $availableClasses,
            'students' => $students,
            'selectedKelas' => $selectedKelas,
            'selectedMapel' => $selectedMapel,
            'orderedMapel' => $orderedMapel,
            'examType' => $examType,
        ];

        return view('admin/nilai/pts_pas', $data);
    }

    /**
     * Store bulk PTS/PAS
     */
    public function storeBulkExam()
    {
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method not allowed']);
        }
        if (!session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }
        $userRole = session()->get('role');
        $userId = session()->get('user_id');
        if (!in_array($userRole, ['admin', 'wali_kelas', 'walikelas'])) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Forbidden']);
        }

        $payload = $this->request->getJSON(true);
        if (!is_array($payload)) { $payload = $this->request->getPost(); }

        $kelas  = $payload['kelas'] ?? null;
        $mapel  = $payload['mapel'] ?? null;
        $tanggal = $payload['tanggal'] ?? date('Y-m-d');
        $jenis = strtolower($payload['jenis'] ?? ''); // 'pts' or 'pas'
        $grades = $payload['grades'] ?? null; // [{siswa_id, nilai}]

        if (!$kelas || !$mapel || !in_array($jenis, ['pts','pas']) || !is_array($grades)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
        }
        if (!$this->nilaiModel->canAccessClass($userId, $kelas, $userRole)) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Tidak memiliki akses ke kelas ini']);
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $upserted = 0;
        foreach ($grades as $g) {
            if (!isset($g['siswa_id']) || $g['nilai'] === '' || $g['nilai'] === null) { continue; }
            $sid = (int)$g['siswa_id'];
            $val = (float)$g['nilai'];
            if ($val < 0 || $val > 100) { continue; }

            // Upsert by unique key (siswa_id, kelas, mapel, jenis) on the same semester
            $row = $this->nilaiModel->where('deleted_at IS NULL', null, false)
                ->where('kelas', $kelas)
                ->where('siswa_id', $sid)
                ->where('mata_pelajaran', $mapel)
                ->where('jenis_nilai', $jenis)
                ->orderBy('id','DESC')
                ->first();

            if ($row) {
                $this->nilaiModel->update($row['id'], [
                    'nilai' => $val,
                    'tanggal' => $tanggal,
                    'updated_by' => $userId,
                ]);
            } else {
                $this->nilaiModel->insert([
                    'siswa_id' => $sid,
                    'mata_pelajaran' => $mapel,
                    'jenis_nilai' => $jenis,
                    'nilai' => $val,
                    'tp_materi' => null,
                    'tanggal' => $tanggal,
                    'kelas' => $kelas,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);
            }
            $upserted++;
        }
        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan nilai']);
        }
        return $this->response->setJSON(['status' => 'ok', 'saved' => $upserted]);
    }
}
