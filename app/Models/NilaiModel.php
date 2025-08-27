<?php
namespace App\Models;

use CodeIgniter\Model;

class NilaiModel extends Model
{
    protected $table = 'nilai';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $allowedFields = ['student_id','subject_id','jenis','nilai','semester','tahun_ajar'];

    public function byStudentAndSubject($studentId, $subjectId)
    {
        return $this->where('student_id',$studentId)->where('subject_id',$subjectId)->findAll();
    }

    public function averageBySubject($subjectId)
    {
        return $this->selectAvg('nilai','avg_nilai')->where('subject_id',$subjectId)->first();
    }
}
