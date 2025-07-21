<?php

namespace App\Models;

use CodeIgniter\Model;

class BeritaModel extends Model
{
    protected $table = 'berita';
    protected $primaryKey = 'id';
    protected $allowedFields = ['judul', 'isi', 'tanggal', 'gambar'];

    // Optionally, add methods to get latest berita or limit results
    public function getLatestBerita($limit = 5)
    {
        return $this->orderBy('tanggal', 'desc')->findAll($limit);
    }
}
