<?php

namespace App\Controllers;

use App\Models\BeritaModel;
use App\Models\UserModel;

class HealthController extends BaseController
{
    public function check()
    {
        $health = [
            'status' => 'healthy',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0.0',
            'checks' => []
        ];

        // Check database connectivity
        try {
            $db = \Config\Database::connect();
            $db->query("SELECT 1")->getRow();
            
            $health['checks']['database'] = [
                'status' => 'healthy',
                'message' => 'Database connection successful'
            ];
            
        } catch (\Exception $e) {
            $health['checks']['database'] = [
                'status' => 'unhealthy',
                'message' => 'Database connection failed: ' . $e->getMessage()
            ];
            $health['status'] = 'unhealthy';
        }

        // Check BeritaModel functionality
        try {
            $beritaModel = new BeritaModel();
            $beritaCount = $beritaModel->countAllResults();
            
            $health['checks']['berita_model'] = [
                'status' => 'healthy',
                'message' => "BeritaModel working, {$beritaCount} records found"
            ];
            
        } catch (\Exception $e) {
            $health['checks']['berita_model'] = [
                'status' => 'unhealthy',
                'message' => 'BeritaModel error: ' . $e->getMessage()
            ];
            $health['status'] = 'unhealthy';
        }

        // Check UserModel functionality
        try {
            $userModel = new UserModel();
            $userCount = $userModel->where('is_active', 1)->countAllResults();
            
            $health['checks']['user_model'] = [
                'status' => 'healthy',
                'message' => "UserModel working, {$userCount} active users found"
            ];
            
        } catch (\Exception $e) {
            $health['checks']['user_model'] = [
                'status' => 'unhealthy',
                'message' => 'UserModel error: ' . $e->getMessage()
            ];
            $health['status'] = 'unhealthy';
        }

        // Check writable directory
        try {
            $writablePath = WRITEPATH . 'uploads';
            if (!is_dir($writablePath)) {
                mkdir($writablePath, 0755, true);
            }
            
            $testFile = $writablePath . '/health_check_test.txt';
            if (file_put_contents($testFile, 'test') !== false) {
                unlink($testFile);
                $health['checks']['writable_directory'] = [
                    'status' => 'healthy',
                    'message' => 'Writable directory accessible'
                ];
            } else {
                throw new \Exception('Cannot write to uploads directory');
            }
            
        } catch (\Exception $e) {
            $health['checks']['writable_directory'] = [
                'status' => 'unhealthy',
                'message' => 'Writable directory error: ' . $e->getMessage()
            ];
            $health['status'] = 'unhealthy';
        }

        // Check logging functionality
        try {
            log_message('info', 'Health check performed successfully');
            $health['checks']['logging'] = [
                'status' => 'healthy',
                'message' => 'Logging system working'
            ];
            
        } catch (\Exception $e) {
            $health['checks']['logging'] = [
                'status' => 'unhealthy',
                'message' => 'Logging error: ' . $e->getMessage()
            ];
            $health['status'] = 'unhealthy';
        }

        // Log health check
        $statusMessage = $health['status'] === 'healthy' 
            ? 'Health check passed' 
            : 'Health check failed - some components unhealthy';
        log_message('info', $statusMessage);

        // Return appropriate HTTP status code
        $httpStatus = $health['status'] === 'healthy' ? 200 : 503;
        
        return $this->response
            ->setStatusCode($httpStatus)
            ->setJSON($health);
    }

    /**
     * Simple health check for basic uptime monitoring
     */
    public function ping()
    {
        return $this->response->setJSON([
            'status' => 'ok',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}