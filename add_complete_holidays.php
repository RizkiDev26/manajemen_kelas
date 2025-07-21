<?php

// Script untuk menambahkan libur nasional yang lengkap untuk tahun 2025
// Database configuration
$host = 'localhost';
$dbname = 'sdngu09';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== ADDING COMPLETE NATIONAL HOLIDAYS FOR 2025 ===\n\n";
    
    // Libur nasional lengkap 2025 (berdasarkan kalender resmi Indonesia)
    $additionalHolidays = [
        // Hari libur keagamaan Islam (estimasi, bisa berubah sesuai pengumuman pemerintah)
        ['2025-03-31', '2025-03-31', 'libur_nasional', 'Hari Raya Idul Fitri 1446 H', 0],
        ['2025-04-01', '2025-04-01', 'libur_nasional', 'Hari Raya Idul Fitri 1446 H', 0],
        ['2025-06-07', '2025-06-07', 'libur_nasional', 'Hari Raya Idul Adha 1446 H', 0],
        ['2025-06-28', '2025-06-28', 'libur_nasional', 'Tahun Baru Islam 1447 H', 0],
        ['2025-09-05', '2025-09-05', 'libur_nasional', 'Maulid Nabi Muhammad SAW', 0],
        
        // Hari libur nasional lainnya yang mungkin terlewat
        ['2025-01-29', '2025-01-29', 'libur_nasional', 'Tahun Baru Imlek 2576', 0],
        
        // Cuti bersama (biasanya diumumkan oleh pemerintah)
        ['2025-04-02', '2025-04-02', 'libur_nasional', 'Cuti Bersama Idul Fitri', 0],
        ['2025-04-03', '2025-04-03', 'libur_nasional', 'Cuti Bersama Idul Fitri', 0],
        ['2025-05-30', '2025-05-30', 'libur_nasional', 'Cuti Bersama Kenaikan Isa Almasih', 0],
        ['2025-08-18', '2025-08-18', 'libur_nasional', 'Cuti Bersama Hari Kemerdekaan', 0],
        ['2025-12-24', '2025-12-24', 'libur_nasional', 'Cuti Bersama Natal', 0],
        ['2025-12-26', '2025-12-26', 'libur_nasional', 'Cuti Bersama Natal', 0],
        
        // Hari libur daerah yang sering dijadikan nasional
        ['2025-03-11', '2025-03-11', 'libur_nasional', 'Hari Raya Nyepi Saka 1947', 0],
    ];
    
    $inserted = 0;
    $skipped = 0;
    
    foreach ($additionalHolidays as $holiday) {
        // Check if holiday already exists
        $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM kalender_akademik WHERE tanggal_mulai = ? AND status = "libur_nasional"');
        $stmt->execute([$holiday[0]]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] == 0) {
            // Insert new holiday
            $stmt2 = $pdo->prepare('INSERT INTO kalender_akademik (tanggal_mulai, tanggal_selesai, status, keterangan, is_manual, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())');
            $stmt2->execute([
                $holiday[0], // tanggal_mulai
                $holiday[1], // tanggal_selesai
                $holiday[2], // status
                $holiday[3], // keterangan
                $holiday[4]  // is_manual
            ]);
            
            echo "✅ Added: {$holiday[0]} - {$holiday[3]}\n";
            $inserted++;
        } else {
            echo "⏭️ Skipped: {$holiday[0]} - {$holiday[3]} (already exists)\n";
            $skipped++;
        }
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "✅ Total inserted: $inserted holidays\n";
    echo "⏭️ Total skipped: $skipped holidays\n";
    
    // Show all national holidays for 2025
    echo "\n=== ALL NATIONAL HOLIDAYS 2025 ===\n";
    $stmt3 = $pdo->query('SELECT * FROM kalender_akademik WHERE status = "libur_nasional" AND YEAR(tanggal_mulai) = 2025 ORDER BY tanggal_mulai');
    $allHolidays = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($allHolidays as $holiday) {
        $date = date('d M Y', strtotime($holiday['tanggal_mulai']));
        $dayName = date('l', strtotime($holiday['tanggal_mulai']));
        echo "📅 {$date} ({$dayName}) - {$holiday['keterangan']}\n";
    }
    
    echo "\n✅ Total national holidays in 2025: " . count($allHolidays) . "\n";
    echo "\n💡 Note: Tanggal libur keagamaan (Idul Fitri, Idul Adha, dll) adalah estimasi.\n";
    echo "   Tanggal pasti biasanya diumumkan oleh Kementerian Agama menjelang hari H.\n";
    
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
