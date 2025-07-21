<?php

namespace App\Models;

use CodeIgniter\Model;

class BeritaModel extends Model
{
    protected $table = 'berita';
    protected $primaryKey = 'id';
    protected $allowedFields = ['judul', 'isi', 'tanggal', 'gambar'];

    /**
     * Get latest berita with error handling
     * 
     * @param int $limit
     * @return array
     */
    public function getLatestBerita($limit = 5)
    {
        try {
            $result = $this->orderBy('tanggal', 'desc')->findAll($limit);
            
            // Log database query success
            log_message('info', 'BeritaModel: Successfully retrieved ' . count($result) . ' berita records');
            
            return $result ?? [];
            
        } catch (\Exception $e) {
            // Log database errors
            log_message('error', 'BeritaModel: Failed to retrieve berita - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Return empty array instead of throwing error
            return [];
        }
    }

    /**
     * Override insert with error handling
     */
    public function insert($data = null, bool $returnID = true)
    {
        try {
            $result = parent::insert($data, $returnID);
            log_message('info', 'BeritaModel: Successfully inserted berita record');
            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'BeritaModel: Failed to insert berita - ' . $e->getMessage());
            throw $e; // Re-throw to allow controller to handle
        }
    }

    /**
     * Override update with error handling
     */
    public function update($id = null, $data = null): bool
    {
        try {
            $result = parent::update($id, $data);
            log_message('info', 'BeritaModel: Successfully updated berita record ID: ' . $id);
            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'BeritaModel: Failed to update berita ID ' . $id . ' - ' . $e->getMessage());
            throw $e; // Re-throw to allow controller to handle
        }
    }

    /**
     * Override delete with error handling
     */
    public function delete($id = null, bool $purge = false)
    {
        try {
            $result = parent::delete($id, $purge);
            log_message('info', 'BeritaModel: Successfully deleted berita record ID: ' . $id);
            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'BeritaModel: Failed to delete berita ID ' . $id . ' - ' . $e->getMessage());
            throw $e; // Re-throw to allow controller to handle
        }
    }
}
