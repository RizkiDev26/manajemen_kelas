<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\HabitModel;
use App\Models\HabitLogModel;
use App\Models\StudentModel;
use App\Models\TbSiswaModel;

class HabitController extends BaseController
{
    protected $habitModel;
    protected $habitLogModel;
    protected $studentModel;
    protected $tbSiswaModel;

    public function __construct()
    {
        helper(['form','date']);
        $this->habitModel = new HabitModel();
        $this->habitLogModel = new HabitLogModel();
        $this->studentModel = new StudentModel();
    $this->tbSiswaModel = new TbSiswaModel();
    }

    /**
     * Resolve current logged-in student's ID from session.
     * Priority: session('student_id') -> find by session('username') as NISN -> fallback NIS.
     * When found by username, sync session('student_id').
     */
    protected function resolveStudentId(): ?int
    {
        $sid = session('student_id');
        if ($sid) return (int)$sid;
        $username = session('username');
        if (!$username) return null;
        $stu = $this->studentModel->where('nisn', $username)->first();
        if (!$stu) {
            $stu = $this->studentModel->where('nis', $username)->first();
        }
        if ($stu) {
            session()->set('student_id', (int)$stu['id']);
            // Simpan nama lengkap agar tampilan tidak menampilkan angka NIS/NISN
            if (!empty($stu['nama'])) {
                session()->set('student_name', $stu['nama']);
            }
            return (int)$stu['id'];
        }
        return null;
    }

    public function index()
    {
    $date = $this->request->getGet('date') ?: date('Y-m-d');
        $habits = $this->habitModel->orderBy('id')->findAll();

        // Resolve student id strictly from session/username; never default to first student
        $studentId = $this->resolveStudentId();
        if (!$studentId) {
            return redirect()->to('/login')->with('error', 'Sesi siswa tidak ditemukan');
        }

    $summary = $this->habitLogModel->getDailySummary($studentId, $date);
        $summaryByHabit = [];
        foreach ($summary as $row) {
            $summaryByHabit[$row['habit_id']] = $row;
        }

        // Detect religion (Islam) for special UI in "Beribadah"
        $isIslam = false;
        $student = $this->studentModel->find($studentId);
        if ($student && !empty($student['nama']) && session('student_name') !== $student['nama']) {
            session()->set('student_name', $student['nama']);
        }
        $agama = $student['agama'] ?? null;
        if (!$agama && !empty($student['nisn'] ?? null)) {
            $fromTb = $this->tbSiswaModel->where('nisn', $student['nisn'])->first();
            $agama = $fromTb['agama'] ?? null;
        }
        if ($agama && stripos($agama, 'islam') !== false) {
            $isIslam = true;
        }
        
        // For testing: Force Islam detection (remove this in production)
        // TODO: Remove this after testing
        $isIslam = true;

        return view('siswa/habits/index', [
            'habits' => $habits,
            'date' => $date,
            'summary' => $summaryByHabit,
            'isIslam' => $isIslam,
        ]);
    }

    public function store()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        $date = $input['date'] ?? null;
        if (! $date || ! \CodeIgniter\I18n\Time::createFromFormat('Y-m-d', $date)) {
            return $this->response->setStatusCode(422)->setJSON(['message' => 'Tanggal tidak valid']);
        }
        $studentId = $this->resolveStudentId();
        if (!$studentId) {
            return $this->response->setStatusCode(400)->setJSON(['message' => 'Sesi siswa tidak ditemukan']);
        }

        $payload = $input['habits'] ?? null; // array: habit_id => [bool, number, time]
        if (!is_array($payload)) $payload = [];

        $createdBy = session('user_id');
        $createdBy = $createdBy ?: null;

        foreach ($payload as $habitId => $vals) {
            $data = [
                'student_id' => (int)$studentId,
                'habit_id'   => (int)$habitId,
                'log_date'   => $date,
                'value_bool' => isset($vals['bool']) ? (int)!!$vals['bool'] : null,
                'value_time' => $vals['time'] ?? null,
                'value_number' => isset($vals['number']) && $vals['number'] !== '' ? (float)$vals['number'] : null,
                'notes'      => null,
                'value_json' => null,
                'created_by' => $createdBy,
            ];

            // Handle complex data (prayers, activities, etc.)
            $complexData = [];
            
            // Handle prayer times for worship habit
            if (isset($vals['prayers']) && is_array($vals['prayers'])) {
                $complexData['prayers'] = $vals['prayers'];
            }
            
            // Handle activities list (exercise, learning, social, etc.)
            if (isset($vals['activities']) && is_array($vals['activities'])) {
                $complexData['activities'] = $vals['activities'];
            }
            
            // Handle food items for healthy food habit
            if (isset($vals['items']) && is_array($vals['items'])) {
                $complexData['items'] = $vals['items'];
            }
            
            // Store complex data as JSON
            if (!empty($complexData)) {
                $data['value_json'] = json_encode($complexData, JSON_UNESCAPED_UNICODE);
            }

            // Handle simple notes
            if (isset($vals['notes']) && is_string($vals['notes']) && $vals['notes'] !== '') {
                $data['notes'] = $vals['notes'];
            }

            $this->habitLogModel->upsertLog($data);
        }

    return $this->response->setJSON(['message' => 'Tersimpan']);
    }

    public function today()
    {
        $date = date('Y-m-d');
    $studentId = (int) ($this->resolveStudentId() ?? 0);
        $summary = $this->habitLogModel->getDailySummary($studentId, $date);
        return $this->response->setJSON($summary);
    }

    public function summary()
    {
        $date = $this->request->getGet('date');
        if (! $date || ! \CodeIgniter\I18n\Time::createFromFormat('Y-m-d', $date)) {
            return $this->response->setStatusCode(422)->setJSON(['message' => 'Tanggal tidak valid']);
        }
        $studentId = (int) ($this->resolveStudentId() ?? 0);
        if (!$studentId) {
            return $this->response->setStatusCode(400)->setJSON(['message' => 'Sesi siswa tidak ditemukan']);
        }
        $rows = $this->habitLogModel->getDailySummary($studentId, $date);
        
        // Parse JSON data if exists
        foreach ($rows as &$row) {
            if (!empty($row['value_json'])) {
                $row['complex_data'] = json_decode($row['value_json'], true);
            }
        }
        
        return $this->response->setJSON(['data' => $rows]);
    }

    /**
     * Calculate current streak for student
     */
    private function calculateCurrentStreak($studentId)
    {
        $db = db_connect();
        $sql = "
            WITH RECURSIVE date_series AS (
                SELECT DATE(NOW()) as log_date
                UNION ALL
                SELECT DATE_SUB(log_date, INTERVAL 1 DAY)
                FROM date_series
                WHERE log_date > DATE_SUB(NOW(), INTERVAL 30 DAY)
            ),
            daily_completion AS (
                SELECT 
                    ds.log_date,
                    COUNT(DISTINCT hl.habit_id) as completed_habits,
                    (SELECT COUNT(*) FROM habits WHERE status = 'active') as total_habits
                FROM date_series ds
                LEFT JOIN habit_logs hl ON DATE(hl.date) = ds.log_date 
                    AND hl.student_id = ? 
                    AND hl.value_bool = 1
                GROUP BY ds.log_date
                ORDER BY ds.log_date DESC
            )
            SELECT 
                log_date,
                completed_habits,
                total_habits,
                CASE WHEN completed_habits >= total_habits * 0.8 THEN 1 ELSE 0 END as is_good_day
            FROM daily_completion
        ";
        
        $query = $db->query($sql, [$studentId]);
        $results = $query->getResultArray();
        
        $streak = 0;
        foreach ($results as $row) {
            if ($row['is_good_day'] == 1) {
                $streak++;
            } else {
                break;
            }
        }
        
        return $streak;
    }

    /**
     * Calculate weekly progress for student
     */
    private function calculateWeeklyProgress($studentId)
    {
        $db = db_connect();
        $sql = "
            SELECT 
                COUNT(DISTINCT DATE(hl.date)) as days_with_activity,
                COUNT(DISTINCT CASE WHEN hl.value_bool = 1 THEN CONCAT(DATE(hl.date), '_', hl.habit_id) END) as completed_habits,
                (SELECT COUNT(*) FROM habits WHERE status = 'active') * 7 as total_possible_habits
            FROM habit_logs hl
            WHERE hl.student_id = ? 
                AND DATE(hl.date) >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
                AND DATE(hl.date) <= CURDATE()
        ";
        
        $query = $db->query($sql, [$studentId]);
        $result = $query->getRowArray();
        
        return [
            'completed' => $result['completed_habits'] ?? 0,
            'total' => $result['total_possible_habits'] ?? 35
        ];
    }

    /**
     * Get student statistics
     */
    public function getStats()
    {
        $studentId = (int) ($this->resolveStudentId() ?? 0);
        if (!$studentId) {
            return $this->response->setStatusCode(400)->setJSON(['message' => 'Sesi siswa tidak ditemukan']);
        }

        $currentStreak = $this->calculateCurrentStreak($studentId);
        $weeklyProgress = $this->calculateWeeklyProgress($studentId);
        
        // Best streak (simplified - could be enhanced with proper tracking)
        $bestStreak = max($currentStreak, 21); // Assuming 21 as historical best
        
        return $this->response->setJSON([
            'current_streak' => $currentStreak,
            'best_streak' => $bestStreak,
            'weekly_progress' => $weeklyProgress
        ]);
    }

    /**
     * Show monthly report page
     */
    public function monthlyReport()
    {
        $studentId = $this->resolveStudentId();
        if (!$studentId) {
            return redirect()->to('/login')->with('error', 'Sesi siswa tidak ditemukan');
        }

        return view('siswa/habits/monthly_report');
    }

    /**
     * Get monthly data for student
     */
    public function monthlyData()
    {
        $month = $this->request->getGet('month');
        if (!$month || !preg_match('/^\d{4}-\d{2}$/', $month)) {
            return $this->response->setStatusCode(422)->setJSON(['message' => 'Format bulan tidak valid (YYYY-MM)']);
        }

        $studentId = $this->resolveStudentId();
        
        // Enhanced debugging
        log_message('info', 'Monthly data request - Month: ' . $month);
        log_message('info', 'Session data - student_id: ' . (session('student_id') ?: 'null'));
        log_message('info', 'Session data - username: ' . (session('username') ?: 'null'));
        log_message('info', 'Resolved student ID: ' . ($studentId ?: 'null'));
        
        if (!$studentId) {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Sesi siswa tidak ditemukan',
                'debug' => [
                    'session_student_id' => session('student_id'),
                    'session_username' => session('username'),
                    'resolved_student_id' => $studentId
                ]
            ]);
        }

        // Get all habit logs for the month
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate)); // Last day of month

        $db = db_connect();
        $sql = "
            SELECT 
                hl.log_date as log_date,
                hl.habit_id,
                hl.value_bool as completed,
                hl.value_time as time,
                hl.value_number as duration,
                hl.notes,
                h.name as habit_name
            FROM habit_logs hl
            JOIN habits h ON h.id = hl.habit_id
            WHERE hl.student_id = ? 
                AND hl.log_date BETWEEN ? AND ?
            ORDER BY hl.log_date ASC, hl.habit_id ASC
        ";

        $query = $db->query($sql, [$studentId, $startDate, $endDate]);
        $results = $query->getResultArray();

        // Debug logging
        log_message('info', 'Monthly data query - Student ID: ' . $studentId . ', Start: ' . $startDate . ', End: ' . $endDate);
        log_message('info', 'Monthly data results count: ' . count($results));
        
        // Organize data by date and habit
        $monthlyData = [];
        
        foreach ($results as $row) {
            $date = $row['log_date'];
            $habitKey = 'habit_' . $row['habit_id'];
            
            if (!isset($monthlyData[$date])) {
                $monthlyData[$date] = [];
            }
            
            $notes = '';
            if ($row['notes']) {
                $notesData = json_decode($row['notes'], true);
                if (is_array($notesData)) {
                    if (isset($notesData['prayers']) && is_array($notesData['prayers'])) {
                        $prayers = array_keys(array_filter($notesData['prayers']));
                        if (!empty($prayers)) {
                            $notes .= 'Sholat: ' . implode(', ', $prayers) . '. ';
                        }
                    }
                    if (isset($notesData['note'])) {
                        $notes .= $notesData['note'];
                    }
                } else {
                    $notes = $row['notes'];
                }
            }
            
            // Determine if habit is completed based on data type
            $completed = false;
            if ($row['completed'] == 1) {
                // value_bool is set to 1
                $completed = true;
            } elseif ($row['time']) {
                // Has time value (for wake up and sleep habits)
                $completed = true;
            } elseif ($row['duration'] && $row['duration'] > 0) {
                // Has duration value (for exercise)
                $completed = true;
            }
            
            $monthlyData[$date][$habitKey] = [
                'completed' => $completed,
                'time' => $row['time'],
                'duration' => $row['duration'],
                'notes' => trim($notes),
                'habit_name' => $row['habit_name']
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $monthlyData,
            'month' => $month,
            'debug' => [
                'query_results_count' => count($results),
                'student_id' => $studentId,
                'date_range' => [$startDate, $endDate],
                'sample_data' => array_slice($results, 0, 3) // First 3 rows for debugging
            ]
        ]);
    }
    
    /**
     * Save habit data (for editing/adding from monthly report)
     */
    public function saveHabitData()
    {
        $studentId = $this->resolveStudentId();
        
        if (!$studentId) {
            return $this->response->setStatusCode(401)->setJSON([
                'status' => 'error',
                'message' => 'Sesi siswa tidak ditemukan'
            ]);
        }
        
        $json = $this->request->getJSON(true);
        
        // Validate required fields
        if (!isset($json['habit_id']) || !isset($json['log_date'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Data tidak lengkap'
            ]);
        }
        
        $habitId = (int) $json['habit_id'];
        $logDate = $json['log_date'];
        $completed = $json['completed'] ?? false;
        $time = $json['time'] ?? null;
        $duration = $json['duration'] ?? null;
        $notes = $json['notes'] ?? null;
        
        // Validate habit_id
        if ($habitId < 1 || $habitId > 7) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'ID kebiasaan tidak valid'
            ]);
        }
        
        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $logDate)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Format tanggal tidak valid'
            ]);
        }
        
        try {
            // Prepare data for upsert
            $logData = [
                'student_id' => $studentId,
                'habit_id' => $habitId,
                'log_date' => $logDate,
                'value_bool' => $completed ? 1 : 0,
                'value_time' => $time,
                'value_number' => $duration ? (float) $duration : null,
                'notes' => $notes
            ];
            
            // Use upsertLog method from HabitLogModel
            $result = $this->habitLogModel->upsertLog($logData);
            
            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Data kebiasaan berhasil disimpan',
                    'data' => $logData
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan data kebiasaan'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error saving habit data: ' . $e->getMessage());
            
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Delete habit data
     */
    public function deleteHabitData()
    {
        $studentId = $this->resolveStudentId();
        
        if (!$studentId) {
            return $this->response->setStatusCode(401)->setJSON([
                'status' => 'error',
                'message' => 'Sesi siswa tidak ditemukan'
            ]);
        }
        
        $json = $this->request->getJSON(true);
        
        // Validate required fields
        if (!isset($json['habit_id']) || !isset($json['log_date'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Data tidak lengkap'
            ]);
        }
        
        $habitId = (int) $json['habit_id'];
        $logDate = $json['log_date'];
        
        try {
            $db = db_connect();
            $deleted = $db->table('habit_logs')
                ->where('student_id', $studentId)
                ->where('habit_id', $habitId)
                ->where('log_date', $logDate)
                ->delete();
            
            if ($deleted) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Data kebiasaan berhasil dihapus'
                ]);
            } else {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error deleting habit data: ' . $e->getMessage());
            
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }
}
