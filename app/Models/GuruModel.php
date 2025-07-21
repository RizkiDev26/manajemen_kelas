<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruModel extends Model
{
    protected $table = 'tb_guru';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'nama_lengkap',
        'gelar',
        'nip',
        'nuptk',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'jabatan'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'nama_lengkap' => 'required|min_length[3]|max_length[100]',
        'nip' => 'required|min_length[10]|max_length[20]|is_unique[tb_guru.nip,id,{id}]',
        'jenis_kelamin' => 'required|in_list[Laki-laki,Perempuan]',
        'tempat_lahir' => 'required|max_length[50]',
        'tanggal_lahir' => 'required|valid_date',
        'jabatan' => 'required|max_length[100]'
    ];

    protected $validationMessages = [
        'nama_lengkap' => [
            'required' => 'Nama lengkap harus diisi',
            'min_length' => 'Nama lengkap minimal 3 karakter',
            'max_length' => 'Nama lengkap maksimal 100 karakter'
        ],
        'nip' => [
            'required' => 'NIP harus diisi',
            'min_length' => 'NIP minimal 10 karakter',
            'max_length' => 'NIP maksimal 20 karakter',
            'is_unique' => 'NIP sudah terdaftar'
        ],
        'jenis_kelamin' => [
            'required' => 'Jenis kelamin harus dipilih',
            'in_list' => 'Jenis kelamin harus Laki-laki atau Perempuan'
        ],
        'tempat_lahir' => [
            'required' => 'Tempat lahir harus diisi',
            'max_length' => 'Tempat lahir maksimal 50 karakter'
        ],
        'tanggal_lahir' => [
            'required' => 'Tanggal lahir harus diisi',
            'valid_date' => 'Format tanggal lahir tidak valid'
        ],
        'jabatan' => [
            'required' => 'Jabatan harus diisi',
            'max_length' => 'Jabatan maksimal 100 karakter'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get all teachers with optional search
     */
    public function getGuru($search = null, $limit = null, $offset = null)
    {
        $builder = $this->builder();
        
        if ($search) {
            $builder->groupStart()
                    ->like('nama_lengkap', $search)
                    ->orLike('nip', $search)
                    ->orLike('jabatan', $search)
                    ->groupEnd();
        }
        
        if ($limit) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get teacher by NIP
     */
    public function getByNip($nip)
    {
        return $this->where('nip', $nip)->first();
    }

    /**
     * Get teachers by jabatan (position)
     */
    public function getByJabatan($jabatan)
    {
        return $this->like('jabatan', $jabatan)->findAll();
    }

    /**
     * Get homeroom teachers (wali kelas)
     */
    public function getWaliKelas()
    {
        return $this->like('jabatan', 'Wali Kelas')->findAll();
    }

    /**
     * Get teacher full name with title
     */
    public function getFullName($id)
    {
        $guru = $this->find($id);
        if ($guru) {
            return trim($guru['nama_lengkap'] . ' ' . $guru['gelar']);
        }
        return null;
    }

    /**
     * Count teachers by gender
     */
    public function countByGender()
    {
        return [
            'laki_laki' => $this->where('jenis_kelamin', 'Laki-laki')->countAllResults(),
            'perempuan' => $this->where('jenis_kelamin', 'Perempuan')->countAllResults()
        ];
    }
}
