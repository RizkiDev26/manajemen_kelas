<?php

namespace App\Controllers;

use App\Models\BeritaModel;

class Home extends BaseController
{
    public function index(): string
    {
        $data['berita'] = [];
        
        try {
            $beritaModel = new BeritaModel();
            $data['berita'] = $beritaModel->getLatestBerita(5);
            
            // Log successful data loading
            if (!empty($data['berita'])) {
                log_message('info', 'Successfully loaded ' . count($data['berita']) . ' berita items for homepage');
            } else {
                log_message('warning', 'No berita found in database, using fallback content');
                $data['berita'] = $this->getFallbackData('berita');
            }
            
        } catch (\Exception $e) {
            // Handle database or model errors
            $this->handleDatabaseError($e, 'Failed to load berita for homepage');
            
            // Use fallback content when database fails
            $data['berita'] = $this->getFallbackData('berita');
            
            // Optionally set a user-friendly message
            $data['error_message'] = 'Sedang mengalami gangguan teknis. Menampilkan konten standar.';
        }
        
        return view('home', $data);
    }
}
