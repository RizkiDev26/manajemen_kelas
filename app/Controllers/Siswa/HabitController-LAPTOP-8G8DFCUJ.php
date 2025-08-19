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
     * If no siswa record exists, create one automatically.
     */
    protected function resolveStudentId(): ?int
    {
        $sid = session('student_id');
        if ($sid) return (int)$sid;
        
        $username = session('username');
        $userId = session('user_id');
        if (!$username || !$userId) return null;
        
        // First try to find existing record
        $stu = $this->studentModel->where('nisn', $username)->first();
        if (!$stu) {
            $stu = $this->studentModel->where('nis', $username)->first();
        }
        
        // If no record found, create one automatically from user data
        if (!$stu) {
            $userModel = new \App\Models\UserModel();
            $userData = $userModel->find($userId);
            
            if ($userData && $userData['role'] === 'siswa') {
                $newStudentData = [
                    'nama' => $userData['nama'],
                    'nisn' => $userData['username'],
                    'nis' => $userData['username'], // Use NISN as NIS for now
                    'kelas_id' => 1, // Default class, can be updated later
                    'status' => 'aktif',
                    'jenis_kelamin' => 'L', // Default, can be updated later
                    'tempat_lahir' => '',
                    'tanggal_lahir' => '',
                    'alamat' => '',
                    'nama_ayah' => '',
                    'nama_ibu' => '',
                    'no_telepon_ortu' => ''
                ];
                
                try {
                    $newId = $this->studentModel->insert($newStudentData);
                    if ($newId) {
                        session()->set('student_id', (int)$newId);
                        return (int)$newId;
                    }
                } catch (\Exception $e) {
                    // Log error but don't fail
                    log_message('error', 'Failed to create siswa record: ' . $e->getMessage());
                }
            }
            return null;
        }
        
        if ($stu) {
            session()->set('student_id', (int)$stu['id']);
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
        $agama = $student['agama'] ?? null;
        if (!$agama && !empty($student['nisn'] ?? null)) {
            $fromTb = $this->tbSiswaModel->where('nisn', $student['nisn'])->first();
            $agama = $fromTb['agama'] ?? null;
        }
        if ($agama && stripos($agama, 'islam') !== false) {
            $isIslam = true;
        }

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
                // notes can store JSON (e.g., prayers) or plain text
                'notes'      => null,
                'created_by' => $createdBy,
            ];

            // Serialize notes/prayers if provided
            $notesPayload = [];
            if (isset($vals['prayers']) && is_array($vals['prayers'])) {
                $notesPayload['prayers'] = $vals['prayers'];
            }
            if (isset($vals['notes']) && is_string($vals['notes']) && $vals['notes'] !== '') {
                $notesPayload['note'] = $vals['notes'];
            }
            if (!empty($notesPayload)) {
                $data['notes'] = json_encode($notesPayload, JSON_UNESCAPED_UNICODE);
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
        return $this->response->setJSON(['data' => $rows]);
    }
}
