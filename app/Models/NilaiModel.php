<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiModel extends Model
{
    protected $table = 'nilai';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'siswa_id',
        'mata_pelajaran',
        'jenis_nilai',
        'nilai',
        'tp_materi',
        'tanggal',
        'kelas',
        'created_by',
        'updated_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'siswa_id' => 'required|integer',
        'mata_pelajaran' => 'required|string|max_length[100]',
        'jenis_nilai' => 'required|in_list[harian,pts,pas]',
        'nilai' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        'kelas' => 'required|string|max_length[20]',
        'tanggal' => 'required|valid_date',
        'created_by' => 'required|integer'
    ];

    protected $validationMessages = [
        'siswa_id' => [
            'required' => 'Siswa harus dipilih',
            'integer' => 'ID siswa tidak valid'
        ],
        'mata_pelajaran' => [
            'required' => 'Mata pelajaran harus diisi',
            'max_length' => 'Mata pelajaran maksimal 100 karakter'
        ],
        'jenis_nilai' => [
            'required' => 'Jenis nilai harus dipilih',
            'in_list' => 'Jenis nilai tidak valid'
        ],
        'nilai' => [
            'required' => 'Nilai harus diisi',
            'numeric' => 'Nilai harus berupa angka',
            'greater_than_equal_to' => 'Nilai minimal 0',
            'less_than_equal_to' => 'Nilai maksimal 100'
        ],
        'kelas' => [
            'required' => 'Kelas harus diisi'
        ],
        'tanggal' => [
            'required' => 'Tanggal harus diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ]
    ];

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
     * Get nilai by class and mata pelajaran
     */
    public function getNilaiByKelas($kelas, $mataPelajaran = 'IPAS')
    {
        return $this->select('nilai.*, tb_siswa.nama, tb_siswa.nisn, tb_siswa.nipd')
                   ->join('tb_siswa', 'tb_siswa.id = nilai.siswa_id')
                   ->where('nilai.kelas', $kelas)
                   ->where('nilai.mata_pelajaran', $mataPelajaran)
                   ->where('tb_siswa.deleted_at IS NULL')
                   ->orderBy('tb_siswa.nama', 'ASC')
                   ->orderBy('nilai.tanggal', 'DESC')
                   ->findAll();
    }

    /**
     * Get nilai rekap (summary) by student
     */
    public function getNilaiRekap($kelas, $mataPelajaran = 'IPAS')
    {
        $db = \Config\Database::connect();
        
        $query = "
            SELECT 
                s.id,
                s.nama,
                s.nisn,
                s.nipd,
                s.kelas,
                AVG(CASE WHEN n.jenis_nilai = 'harian' THEN n.nilai END) as nilai_harian,
                COUNT(CASE WHEN n.jenis_nilai = 'harian' THEN n.nilai END) as jumlah_harian,
                AVG(CASE WHEN n.jenis_nilai = 'pts' THEN n.nilai END) as nilai_pts,
                AVG(CASE WHEN n.jenis_nilai = 'pas' THEN n.nilai END) as nilai_pas,
                ROUND(
                    (
                        COALESCE(AVG(CASE WHEN n.jenis_nilai = 'harian' THEN n.nilai END), 0) * 0.4 +
                        COALESCE(AVG(CASE WHEN n.jenis_nilai = 'pts' THEN n.nilai END), 0) * 0.3 +
                        COALESCE(AVG(CASE WHEN n.jenis_nilai = 'pas' THEN n.nilai END), 0) * 0.3
                    ), 1
                ) as nilai_akhir
            FROM tb_siswa s
            LEFT JOIN nilai n ON s.id = n.siswa_id 
                AND n.mata_pelajaran = ? 
                AND n.deleted_at IS NULL
            WHERE s.kelas = ? 
                AND s.deleted_at IS NULL
            GROUP BY s.id, s.nama, s.nisn, s.nipd, s.kelas
            ORDER BY s.nama ASC
        ";
        
        return $db->query($query, [$mataPelajaran, $kelas])->getResultArray();
    }

    /**
     * Get nilai detail by student and mata pelajaran
     */
    public function getNilaiDetailSiswa($siswaId, $mataPelajaran = 'IPAS')
    {
        return $this->select('nilai.*, tb_siswa.nama, tb_siswa.nisn')
                   ->join('tb_siswa', 'tb_siswa.id = nilai.siswa_id')
                   ->where('nilai.siswa_id', $siswaId)
                   ->where('nilai.mata_pelajaran', $mataPelajaran)
                   ->where('tb_siswa.deleted_at IS NULL')
                   ->orderBy('nilai.jenis_nilai', 'ASC')
                   ->orderBy('nilai.tanggal', 'DESC')
                   ->findAll();
    }

    /**
     * Check if user can access class
     */
    public function canAccessClass($userId, $kelas, $userRole)
    {
        if ($userRole === 'admin') {
            return true;
        }
        
        if ($userRole === 'wali_kelas' || $userRole === 'walikelas') {
            $db = \Config\Database::connect();
            $query = $db->query("
                SELECT w.kelas 
                FROM users u 
                JOIN walikelas w ON u.walikelas_id = w.id 
                WHERE u.id = ? AND w.kelas = ?
            ", [$userId, $kelas]);
            
            return $query->getNumRows() > 0;
        }
        
        return false;
    }

    /**
     * Get mata pelajaran list
     */
    public function getMataPelajaranList()
    {
        return [
            'IPAS' => 'Ilmu Pengetahuan Alam dan Sosial',
            'Bahasa Indonesia' => 'Bahasa Indonesia',
            'Matematika' => 'Matematika',
            'Bahasa Inggris' => 'Bahasa Inggris',
            'Pendidikan Agama' => 'Pendidikan Agama',
            'PJOK' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
            'Seni Budaya' => 'Seni Budaya',
            'Bahasa Jawa' => 'Bahasa Jawa'
        ];
    }

    /**
     * Get jenis nilai list
     */
    public function getJenisNilaiList()
    {
        return [
            'harian' => 'Nilai Harian',
            'pts' => 'Nilai PTS (Penilaian Tengah Semester)',
            'pas' => 'Nilai PAS (Penilaian Akhir Semester)'
        ];
    }
}
