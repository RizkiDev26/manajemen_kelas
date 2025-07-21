<?php

namespace App\Models;

use CodeIgniter\Model;

class KalenderAkademikModel extends Model
{
    protected $table            = 'kalender_akademik';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal_mulai',
        'tanggal_selesai', 
        'status',
        'keterangan',
        'is_manual',
        'created_by'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'tanggal_mulai'   => 'required|valid_date',
        'tanggal_selesai' => 'required|valid_date',
        'status'          => 'required|in_list[off,libur_nasional,libur_sekolah,ujian,kegiatan]',
        'keterangan'      => 'permit_empty|string|max_length[500]',
    ];
    protected $validationMessages   = [
        'tanggal_mulai' => [
            'required'   => 'Tanggal mulai harus diisi',
            'valid_date' => 'Format tanggal mulai tidak valid'
        ],
        'tanggal_selesai' => [
            'required'   => 'Tanggal selesai harus diisi',
            'valid_date' => 'Format tanggal selesai tidak valid'
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list'  => 'Status tidak valid'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get calendar events for a specific month/year
     */
    public function getCalendarEvents($year, $month)
    {
        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        
        return $this->where('tanggal_mulai <=', $endDate)
                   ->where('tanggal_selesai >=', $startDate)
                   ->orderBy('tanggal_mulai', 'ASC')
                   ->findAll();
    }

    /**
     * Get events for a specific date
     */
    public function getEventsByDate($date)
    {
        return $this->where('tanggal_mulai <=', $date)
                   ->where('tanggal_selesai >=', $date)
                   ->orderBy('tanggal_mulai', 'ASC')
                   ->findAll();
    }

    /**
     * Check if a date is off/holiday
     */
    public function isDateOff($date)
    {
        $dayOfWeek = date('w', strtotime($date)); // 0 = Sunday, 6 = Saturday
        
        // Check if it's weekend
        if ($dayOfWeek == 0 || $dayOfWeek == 6) {
            return true;
        }

        // Check if there's a calendar event for this date
        $events = $this->getEventsByDate($date);
        return !empty($events);
    }

    /**
     * Get status color class for display
     */
    public function getStatusColor($status)
    {
        $colors = [
            'off'           => 'bg-gray-500',
            'libur_nasional'=> 'bg-red-500',
            'libur_sekolah' => 'bg-orange-500',
            'ujian'         => 'bg-blue-500',
            'kegiatan'      => 'bg-green-500',
        ];

        return $colors[$status] ?? 'bg-gray-500';
    }

    /**
     * Get status label for display
     */
    public function getStatusLabel($status)
    {
        $labels = [
            'off'           => 'Off',
            'libur_nasional'=> 'Libur Nasional',
            'libur_sekolah' => 'Libur Sekolah',
            'ujian'         => 'Ujian',
            'kegiatan'      => 'Kegiatan',
        ];

        return $labels[$status] ?? 'Unknown';
    }

    /**
     * Delete overlapping events before inserting new one
     */
    public function insertWithOverlapCheck($data)
    {
        // Find overlapping events
        $overlapping = $this->where('tanggal_mulai <=', $data['tanggal_selesai'])
                           ->where('tanggal_selesai >=', $data['tanggal_mulai'])
                           ->where('is_manual', 1) // Only delete manual entries
                           ->findAll();

        // Begin transaction
        $this->db->transStart();

        // Delete overlapping manual entries
        foreach ($overlapping as $event) {
            $this->delete($event['id']);
        }

        // Insert new event
        $result = $this->insert($data);

        // Complete transaction
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return false;
        }

        return $result;
    }
}
