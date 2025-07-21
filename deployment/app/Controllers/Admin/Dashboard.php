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
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $guruModel = new GuruModel();
        $siswaModel = new SiswaModel();
        
        // Get current user info with walikelas data if applicable
        $currentUser = $userModel->getUserWithWalikelas($session->get('user_id'));
        
        // Dashboard statistics
        $totalGuru = $guruModel->countAllResults();
        $totalWalikelas = $userModel->where('role', 'walikelas')->where('is_active', 1)->countAllResults();
        $totalUsers = $userModel->where('is_active', 1)->countAllResults();
        $siswaStats = $siswaModel->getStatistics();
        
        $data = [
            'currentUser' => $currentUser,
            'totalGuru' => $totalGuru,
            'totalWalikelas' => $totalWalikelas,
            'totalUsers' => $totalUsers,
            'totalSiswa' => $siswaStats['total'],
            'siswaLaki' => $siswaStats['laki_laki'],
            'siswaPerempuan' => $siswaStats['perempuan'],
            'recentGuru' => $guruModel->orderBy('created_at', 'DESC')->findAll(5),
            'recentSiswa' => $siswaModel->orderBy('created_at', 'DESC')->findAll(5)
        ];

        return view('admin/dashboard', $data);
    }
}
