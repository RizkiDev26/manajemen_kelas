<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\TbSiswaModel;
use App\Models\WalikelasModel;

class Absensi extends BaseController
{
    protected $absensiModel;
    protected $tbSiswaModel;
    protected $walikelasModel;

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
        $this->tbSiswaModel = new TbSiswaModel();
        $this->walikelasModel = new WalikelasModel();
    }

    /**
     * Input absensi harian - untuk wali kelas
     */
    public function input()
    {
        // Check if user is logged in and has appropriate role
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
            // Get walikelas data using proper relation: users.walikelas_id -> walikelas.id
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

        // Get selected date and class
        $selectedDate = $this->request->getPost('tanggal') ?? $this->request->getGet('tanggal') ?? date('Y-m-d');
        $selectedKelas = $this->request->getPost('kelas') ?? $this->request->getGet('kelas') ?? $userKelas;

        // Debug logging for AJAX requests
        if ($this->request->isAJAX()) {
            log_message('debug', 'AJAX Input Request: Date=' . $selectedDate . ', Kelas=' . $selectedKelas);
        }

        // Get all classes for admin
        $allKelas = [];
        if ($userRole === 'admin') {
            $allKelas = $this->tbSiswaModel->getActiveClasses();
            
            // If admin hasn't selected a class yet, auto-select the first available class
            if (!$selectedKelas && !empty($allKelas)) {
                $selectedKelas = $allKelas[0]['kelas'];
            }
        }

        // Validate access to selected class
        if ($selectedKelas && !$this->absensiModel->canAccessClass($userId, $selectedKelas, $userRole)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Anda tidak memiliki akses ke kelas tersebut'
                ]);
            }
            return redirect()->to('/admin/absensi/input')->with('error', 'Anda tidak memiliki akses ke kelas tersebut');
        }

        // Get students with attendance status
        $students = [];
        if ($selectedKelas) {
            $students = $this->absensiModel->getStudentsWithAttendance($selectedKelas, $selectedDate);
            
            if ($this->request->isAJAX()) {
                log_message('debug', 'AJAX Input Students Count: ' . count($students));
            }
        }

        $data = [
            'title' => 'Input Absensi Harian',
            'userRole' => $userRole,
            'userKelas' => $userKelas,
            'allKelas' => $allKelas,
            'selectedDate' => $selectedDate,
            'selectedKelas' => $selectedKelas,
            'students' => $students
        ];

        // If AJAX request, return JSON data instead of full view
        if ($this->request->isAJAX()) {
            log_message('debug', 'AJAX Input Response: Success=true, Students=' . count($students));
            return $this->response->setJSON([
                'success' => true,
                'data' => $data
            ]);
        }

        return view('admin/absensi/input', $data);
    }

    /**
     * Save attendance data
     */
    public function save()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $userId = session()->get('user_id');
        $userRole = session()->get('role');
        
        $siswaId = $this->request->getPost('siswa_id');
        $tanggal = $this->request->getPost('tanggal');
        $status = $this->request->getPost('status');
        $keterangan = $this->request->getPost('keterangan');
        $kelas = $this->request->getPost('kelas');

        // Validate access
        if (!$this->absensiModel->canAccessClass($userId, $kelas, $userRole)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Anda tidak memiliki akses ke kelas tersebut'
            ]);
        }

        $data = [
            'siswa_id' => $siswaId,
            'tanggal' => $tanggal,
            'status' => $status,
            'keterangan' => $keterangan,
            'created_by' => $userId
        ];

        if ($this->absensiModel->saveAttendance($data)) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Absensi berhasil disimpan'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Gagal menyimpan absensi'
            ]);
        }
    }

    /**
     * Rekap absensi - untuk admin dan wali kelas
     */
    public function rekap()
    {
        // Check if user is logged in and has appropriate role
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
            // Get walikelas data using proper relation: users.walikelas_id -> walikelas.id
            $db = \Config\Database::connect();
            $query = $db->query("
                SELECT w.kelas 
                FROM users u 
                JOIN walikelas w ON u.walikelas_id = w.id 
                WHERE u.id = ?
            ", [$userId]);
            
            $result = $query->getRowArray();
            if ($result) {
                $userKelas = $result['kelas'];
            }
        }

        // Get filter parameters
        $filterKelas = $this->request->getGet('kelas') ?? $userKelas;
        $filterBulan = $this->request->getGet('bulan') ?? date('Y-m');
        
        // For wali kelas, force their class
        if ($userRole === 'wali_kelas' || $userRole === 'walikelas') {
            $filterKelas = $userKelas;
        }

        // Get all classes for admin
        $allKelas = [];
        if ($userRole === 'admin') {
            $allKelas = $this->tbSiswaModel->getActiveClasses();
        }

        // Get attendance summary
        $attendanceData = [];
        if ($filterKelas && $filterBulan) {
            list($year, $month) = explode('-', $filterBulan);
            // Ensure year and month are integers
            $year = (int)$year;
            $month = (int)$month;
            $attendanceData = $this->absensiModel->getDetailedAttendanceRecap($year, $month, $filterKelas);
        }

        // Additional data for Tailwind view
        $bulan_nama = isset($filterBulan) ? date('F', strtotime($filterBulan . '-01')) : date('F');
        $tahun = isset($filterBulan) ? (int)explode('-', $filterBulan)[0] : (int)date('Y');

        $data = [
            'title' => 'Rekap Absensi',
            'userRole' => $userRole,
            'userKelas' => $userKelas,
            'allKelas' => $allKelas,
            'filterKelas' => $filterKelas,
            'filterBulan' => $filterBulan,
            'attendanceData' => $attendanceData,
            'bulan_nama' => $bulan_nama,
            'tahun' => $tahun,
            'kelas' => $filterKelas
        ];

        return view('admin/absensi/rekap_tailwind', $data);
    }

    /**
     * Get detailed attendance data for AJAX
     */
    public function getDetailData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');
        $kelas = $this->request->getPost('kelas');
        
        $userId = session()->get('user_id');
        $userRole = session()->get('role');

        // Validate access
        if ($kelas && !$this->absensiModel->canAccessClass($userId, $kelas, $userRole)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Anda tidak memiliki akses ke kelas tersebut'
            ]);
        }

        $data = $this->absensiModel->getAttendanceRecap($startDate, $endDate, $kelas);

        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Export attendance data to CSV
     */
    public function export()
    {
        $userRole = session()->get('role');
        $userId = session()->get('user_id');

        if (!in_array($userRole, ['admin', 'wali_kelas', 'walikelas'])) {
            return redirect()->to('/admin')->with('error', 'Anda tidak memiliki akses');
        }

        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        $kelas = $this->request->getGet('kelas');

        // For wali kelas, get their class
        if ($userRole === 'wali_kelas' || $userRole === 'walikelas') {
            // Get walikelas data using proper relation: users.walikelas_id -> walikelas.id
            $db = \Config\Database::connect();
            $query = $db->query("
                SELECT w.kelas 
                FROM users u 
                JOIN walikelas w ON u.walikelas_id = w.id 
                WHERE u.id = ?
            ", [$userId]);
            
            $result = $query->getRowArray();
            if ($result) {
                $kelas = $result['kelas'];
            }
        }

        // Validate access
        if ($kelas && !$this->absensiModel->canAccessClass($userId, $kelas, $userRole)) {
            return redirect()->to('/admin/absensi/rekap')->with('error', 'Anda tidak memiliki akses ke kelas tersebut');
        }

        $data = $this->absensiModel->getAttendanceRecap($startDate, $endDate, $kelas);

        // Set headers for CSV download
        $filename = "rekap_absensi_" . ($kelas ? "kelas_{$kelas}_" : "") . $startDate . "_to_" . $endDate . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Create file pointer
        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fputs($output, "\xEF\xBB\xBF");
        
        // Add CSV headers
        fputcsv($output, ['Tanggal', 'No. Induk', 'Nama Siswa', 'Kelas', 'Status', 'Keterangan']);
        
        // Add data rows
        foreach ($data as $row) {
            fputcsv($output, [
                $row['tanggal'],
                $row['nipd'],
                $row['nama'],
                $row['kelas'],
                ucfirst($row['status']),
                $row['keterangan']
            ]);
        }
        
        fclose($output);
        exit;
    }

    /**
     * Save all attendance data at once
     */
    public function save_all()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $userId = session()->get('user_id');
        $userRole = session()->get('role');
        
        $tanggal = $this->request->getPost('tanggal');
        $kelas = $this->request->getPost('kelas');
        $attendanceData = json_decode($this->request->getPost('attendance_data'), true);

        // Validate access
        if (!$this->absensiModel->canAccessClass($userId, $kelas, $userRole)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Anda tidak memiliki akses ke kelas tersebut'
            ]);
        }

        if (empty($attendanceData)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Data absensi tidak valid'
            ]);
        }

        $successCount = 0;
        $totalCount = count($attendanceData);

        foreach ($attendanceData as $attendance) {
            $data = [
                'siswa_id' => $attendance['siswa_id'],
                'tanggal' => $tanggal,
                'status' => $attendance['status'],
                'keterangan' => $attendance['keterangan'] ?? '',
                'created_by' => $userId
            ];

            if ($this->absensiModel->saveAttendance($data)) {
                $successCount++;
            }
        }

        if ($successCount === $totalCount) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => "Semua absensi berhasil disimpan ({$successCount} dari {$totalCount})"
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false, 
                'message' => "Hanya {$successCount} dari {$totalCount} absensi yang berhasil disimpan"
            ]);
        }
    }

    /**
     * Get attendance summary for a specific month and class
     */
    public function getSummary()
    {
        $kelas = $this->request->getGet('kelas');
        $month = $this->request->getGet('month') ?: date('Y-m');
        
        if (!$kelas) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Kelas harus dipilih'
            ]);
        }

        // Get students in the class
        $students = $this->tbSiswaModel->where('kelas', $kelas)->orderBy('nama', 'ASC')->findAll();
        
        // Get number of days in the month
        $year = date('Y', strtotime($month . '-01'));
        $monthNum = date('m', strtotime($month . '-01'));
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);
        
        // Prepare attendance data
        $attendanceData = [];
        
        foreach ($students as $student) {
            $studentData = [
                'nama' => $student['nama'],
                'nipd' => $student['nipd'],
                'days' => [],
                'summary' => [
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'alpha' => 0
                ]
            ];
            
            // Get attendance for each day of the month
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = sprintf('%s-%02d-%02d', $year, $monthNum, $day);
                
                $attendance = $this->absensiModel
                    ->where('siswa_id', $student['siswa_id'])
                    ->where('tanggal', $date)
                    ->first();
                
                if ($attendance) {
                    $status = $attendance['status'];
                    $studentData['days'][$day] = $status;
                    $studentData['summary'][$status]++;
                } else {
                    // If no record and it's a past date on a weekday, mark as alpha
                    $dayOfWeek = date('N', strtotime($date));
                    $isWeekday = $dayOfWeek <= 5; // Monday = 1, Friday = 5
                    $isPastDate = strtotime($date) < strtotime(date('Y-m-d'));
                    
                    if ($isWeekday && $isPastDate) {
                        $studentData['days'][$day] = 'alpha';
                        $studentData['summary']['alpha']++;
                    } else {
                        $studentData['days'][$day] = null; // Future date or weekend
                    }
                }
            }
            
            $attendanceData[] = $studentData;
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'students' => $attendanceData,
                'month' => $month,
                'year' => $year,
                'monthName' => date('F', strtotime($month . '-01')),
                'daysInMonth' => $daysInMonth,
                'kelas' => $kelas
            ]
        ]);
    }
}
