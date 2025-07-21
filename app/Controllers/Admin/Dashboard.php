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

        // Initialize default data structure
        $data = [
            'currentUser' => null,
            'totalGuru' => 0,
            'totalWalikelas' => 0,
            'totalUsers' => 0,
            'totalSiswa' => 0,
            'siswaLaki' => 0,
            'siswaPerempuan' => 0,
            'recentGuru' => [],
            'recentSiswa' => [],
            'error_message' => null
        ];

        try {
            $userModel = new UserModel();
            $guruModel = new GuruModel();
            $siswaModel = new SiswaModel();
            
            // Get current user info with walikelas data if applicable
            $data['currentUser'] = $userModel->getUserWithWalikelas($session->get('user_id'));
            
            // Dashboard statistics with individual error handling
            try {
                $data['totalGuru'] = $guruModel->countAllResults();
            } catch (\Exception $e) {
                $this->handleDatabaseError($e, 'Failed to count guru');
                $data['totalGuru'] = 0;
            }

            try {
                $data['totalWalikelas'] = $userModel->where('role', 'walikelas')->where('is_active', 1)->countAllResults();
            } catch (\Exception $e) {
                $this->handleDatabaseError($e, 'Failed to count walikelas');
                $data['totalWalikelas'] = 0;
            }

            try {
                $data['totalUsers'] = $userModel->where('is_active', 1)->countAllResults();
            } catch (\Exception $e) {
                $this->handleDatabaseError($e, 'Failed to count users');
                $data['totalUsers'] = 0;
            }

            try {
                $siswaStats = $siswaModel->getStatistics();
                $data['totalSiswa'] = $siswaStats['total'] ?? 0;
                $data['siswaLaki'] = $siswaStats['laki_laki'] ?? 0;
                $data['siswaPerempuan'] = $siswaStats['perempuan'] ?? 0;
            } catch (\Exception $e) {
                $this->handleDatabaseError($e, 'Failed to get siswa statistics');
                $data['totalSiswa'] = $data['siswaLaki'] = $data['siswaPerempuan'] = 0;
            }

            try {
                $data['recentGuru'] = $guruModel->orderBy('created_at', 'DESC')->findAll(5);
            } catch (\Exception $e) {
                $this->handleDatabaseError($e, 'Failed to get recent guru');
                $data['recentGuru'] = [];
            }

            try {
                $data['recentSiswa'] = $siswaModel->orderBy('created_at', 'DESC')->findAll(5);
            } catch (\Exception $e) {
                $this->handleDatabaseError($e, 'Failed to get recent siswa');
                $data['recentSiswa'] = [];
            }

            log_message('info', 'Dashboard data loaded successfully for user ID: ' . $session->get('user_id'));

        } catch (\Exception $e) {
            // Handle critical errors that prevent dashboard loading
            $this->handleApplicationError($e, 'Critical error loading dashboard');
            
            // Use fallback data
            $fallbackStats = $this->getFallbackData('dashboard_stats');
            $data = array_merge($data, $fallbackStats);
            $data['error_message'] = 'Sedang mengalami gangguan teknis. Data mungkin tidak terbaru.';
        }

        return view('admin/dashboard', $data);
    }
}
