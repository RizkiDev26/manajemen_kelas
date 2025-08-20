<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\ClassModel;
use App\Models\HabitModel;
use App\Models\TbSiswaModel;

class ProfileController extends BaseController
{
    protected $studentModel;
    protected $classModel;
    protected $habitModel;
    protected $tbSiswaModel;

    public function __construct()
    {
    $this->studentModel = new StudentModel();
    $this->classModel = new ClassModel();
    $this->habitModel = new HabitModel();
    $this->tbSiswaModel = new TbSiswaModel();
    }

    public function index()
    {
        $studentId = session('student_id');
        $username = session('username'); // siswa login via NISN
        if (!$username && !$studentId) {
            return redirect()->to('/login')->with('error', 'Sesi siswa berakhir');
        }

        // Primary: resolve by session student_id; Fallback: resolve by username (nisn)
        $student = null;
        if ($studentId) {
            $student = $this->studentModel->find($studentId);
        }
        if (!$student && $username) {
            // Try by NISN first, fallback to NIS
            $student = $this->studentModel->where('nisn', $username)->first();
            if (!$student) {
                $student = $this->studentModel->where('nis', $username)->first();
            }
            // Sync session student_id if found
            if ($student && (!session()->has('student_id') || session('student_id') !== $student['id'])) {
                session()->set('student_id', $student['id']);
            }
        }

        if (!$student) {
            return redirect()->to('/login')->with('error', 'Data siswa tidak ditemukan');
        }

        $class = $student && !empty($student['kelas_id']) ? $this->classModel->find($student['kelas_id']) : null;

        // detect Islam
        $isIslam = false;
        $fromTb = null;
    $agama = $student['agama'] ?? null;
        if (!empty($student['nisn'] ?? null)) {
            $fromTb = $this->tbSiswaModel->where('nisn', $student['nisn'])->first();
            if (!$agama) { $agama = $fromTb['agama'] ?? null; }
        }
        if ($agama && stripos($agama, 'islam') !== false) $isIslam = true;

        // Fallback Tempat/Tanggal Lahir
        $ttlTempat = $student['tempat_lahir'] ?? ($fromTb['tempat_lahir'] ?? null);
        $ttlTanggal = $student['tanggal_lahir'] ?? ($fromTb['tanggal_lahir'] ?? null);

        // get beribadah habit id (fallback to berdoa)
        $ibadahHabit = $this->habitModel->where('code', 'beribadah')->first();
        if (!$ibadahHabit) $ibadahHabit = $this->habitModel->where('code', 'berdoa')->first();
        $ibadahHabitId = $ibadahHabit['id'] ?? null;

        return view('siswa/profile/index', [
            'student' => $student,
            'class' => $class,
            'isIslam' => $isIslam,
            'ibadahHabitId' => $ibadahHabitId,
            'today' => date('Y-m-d'),
            'agama' => $agama,
            'ttlTempat' => $ttlTempat,
            'ttlTanggal' => $ttlTanggal,
        ]);
    }
}
