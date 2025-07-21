<?php
/**
 * Add Sample Holiday Data for Testing
 * This script adds sample holiday data to the kalender_akademik table for July 2025
 */

require_once 'preload.php';

try {
    $db = \Config\Database::connect();
    
    echo "=== Adding Sample Holiday Data ===\n\n";
    
    // Check if table exists
    if (!$db->tableExists('kalender_akademik')) {
        echo "Error: kalender_akademik table does not exist. Please run the migration first.\n";
        exit(1);
    }
    
    // Sample holiday data for July 2025
    $holidays = [
        [
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-07-01',
            'status' => 'libur_sekolah',
            'keterangan' => 'Libur Awal Bulan',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],
        [
            'tanggal_mulai' => '2025-07-17',
            'tanggal_selesai' => '2025-07-17',
            'status' => 'libur_nasional',
            'keterangan' => 'Hari Kemerdekaan Indonesia (Observed)',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],
        [
            'tanggal_mulai' => '2025-07-20',
            'tanggal_selesai' => '2025-07-22',
            'status' => 'libur_sekolah',
            'keterangan' => 'Libur Semester',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],
        [
            'tanggal_mulai' => '2025-07-25',
            'tanggal_selesai' => '2025-07-25',
            'status' => 'off',
            'keterangan' => 'Hari Bebas Sekolah',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]
    ];
    
    // Clear existing data for July 2025
    $db->query("DELETE FROM kalender_akademik WHERE YEAR(tanggal_mulai) = 2025 AND MONTH(tanggal_mulai) = 7");
    echo "Cleared existing July 2025 holiday data.\n";
    
    // Insert new data
    $builder = $db->table('kalender_akademik');
    foreach ($holidays as $holiday) {
        $builder->insert($holiday);
        echo "Added holiday: {$holiday['tanggal_mulai']} - {$holiday['keterangan']}\n";
    }
    
    // Verify data was inserted
    $count = $db->query("SELECT COUNT(*) as count FROM kalender_akademik WHERE YEAR(tanggal_mulai) = 2025 AND MONTH(tanggal_mulai) = 7")->getRow()->count;
    echo "\nTotal holidays in July 2025: $count\n";
    
    echo "\n=== Sample Data Added Successfully ===\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
