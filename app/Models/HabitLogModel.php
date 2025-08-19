<?php

namespace App\Models;

use CodeIgniter\Model;

class HabitLogModel extends Model
{
    protected $table            = 'habit_logs';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'student_id','habit_id','log_date','value_bool','value_time','value_number','notes','created_by'
    ];
    protected $useTimestamps    = true;

    public function upsertLog(array $data)
    {
        // Upsert by unique key (student_id, habit_id, log_date)
        $existing = $this->where([
            'student_id' => $data['student_id'],
            'habit_id'   => $data['habit_id'],
            'log_date'   => $data['log_date'],
        ])->first();

        if ($existing) {
            $this->update($existing['id'], $data);
            return $existing['id'];
        }
        return $this->insert($data, true);
    }

    public function getDailySummary(int $studentId, string $date)
    {
        return $this->select('habit_id, value_bool, value_time, value_number, notes')
            ->where('student_id', $studentId)
            ->where('log_date', $date)
            ->findAll();
    }
}
