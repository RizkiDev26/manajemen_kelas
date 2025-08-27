<?php
namespace App\Models;

use CodeIgniter\Model;

class NilaiModel extends Model
{
    protected $table = 'nilai';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    // Legacy + new columns
    protected $allowedFields = [
        'siswa_id',        // existing
        'subject_id',      // new FK to subjects
        'mata_pelajaran',  // existing text subject
        'jenis_nilai',     // harian|pts|pas
        'nilai',
        'tp_materi',
        'tanggal',
        'kelas',
        'semester',        // optional future
        'tahun_ajar'
    ];

    public function byStudentAndSubject($siswaId, $subjectId)
    {
        return $this->where('siswa_id',$siswaId)->where('subject_id',$subjectId)->findAll();
    }

    public function averageBySubject($subjectId)
    {
        return $this->selectAvg('nilai','avg_nilai')->where('subject_id',$subjectId)->first();
    }
}
