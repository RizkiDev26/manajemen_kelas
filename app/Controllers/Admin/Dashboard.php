<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\GuruModel;
use App\Models\SiswaModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // Validate session security
        $sessionCheck = $this->validateSession();
        if ($sessionCheck) {
            return $sessionCheck;
        }

        $userModel = new UserModel();
        $guruModel = new GuruModel();
        $siswaModel = new SiswaModel();
        
        // Get current user info with walikelas data if applicable
        $currentUser = $userModel->getUserWithWalikelas($this->session->get('user_id'));
        
        // Dashboard statistics
        $totalGuru = $guruModel->countAllResults();
        $totalWalikelas = $userModel->where('role', 'walikelas')->where('is_active', 1)->countAllResults();
        $totalUsers = $userModel->where('is_active', 1)->countAllResults();
        $siswaStats = $siswaModel->getStatistics();
        
        // Sanitize output data
        $data = [
            'currentUser' => $this->sanitizeOutput($currentUser),
            'totalGuru' => (int)$totalGuru,
            'totalWalikelas' => (int)$totalWalikelas,
            'totalUsers' => (int)$totalUsers,
            'totalSiswa' => (int)$siswaStats['total'],
            'siswaLaki' => (int)$siswaStats['laki_laki'],
            'siswaPerempuan' => (int)$siswaStats['perempuan'],
            'recentGuru' => $this->sanitizeOutput($guruModel->orderBy('created_at', 'DESC')->findAll(5)),
            'recentSiswa' => $this->sanitizeOutput($siswaModel->orderBy('created_at', 'DESC')->findAll(5))
        ];

        return view('admin/dashboard', $data);
    }
}
