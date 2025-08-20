<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table            = 'kelas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama',
        'tingkat',
        'walikelas_id',
        'tahun_ajaran',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Get all active classes
    public function getKelasAktif()
    {
        return $this->where('status', 'aktif')->findAll();
    }

    // Get class with walikelas information
    public function getKelasWithWalikelas($id = null)
    {
        $builder = $this->db->table('kelas k');
        $builder->select('k.*, u.nama as nama_walikelas');
        $builder->join('users u', 'u.id = k.walikelas_id', 'left');
        
        if ($id !== null) {
            $builder->where('k.id', $id);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }

    // Get students in a class
    public function getSiswaByKelas($kelasId)
    {
        $builder = $this->db->table('tb_siswa s');
        $builder->select('s.*');
        $builder->where('s.kelas_id', $kelasId);
        $builder->where('s.deleted_at', null);
        
        return $builder->get()->getResultArray();
    }
}
