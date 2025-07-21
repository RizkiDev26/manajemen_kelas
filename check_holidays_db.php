<?php

// Simple database check for holidays
$host = 'localhost';
$dbname = 'sdngu09';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CHECKING HOLIDAYS IN DATABASE ===\n\n";
    
    // Check holidays for July 2025
    $stmt = $pdo->prepare("
        SELECT tanggal_mulai, tanggal_selesai, status, keterangan
        FROM kalender_akademik 
        WHERE (
            (tanggal_mulai >= '2025-07-01' AND tanggal_mulai <= '2025-07-31') OR
            (tanggal_selesai >= '2025-07-01' AND tanggal_selesai <= '2025-07-31') OR
            (tanggal_mulai <= '2025-07-01' AND tanggal_selesai >= '2025-07-31')
        )
        ORDER BY tanggal_mulai
    ");
    
    $stmt->execute();
    $holidays = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Holidays found for July 2025:\n";
    if (empty($holidays)) {
        echo "  No holidays found!\n";
    } else {
        foreach ($holidays as $holiday) {
            echo "  - {$holiday['tanggal_mulai']} to {$holiday['tanggal_selesai']}: {$holiday['status']} - {$holiday['keterangan']}\n";
        }
    }
    
    echo "\n";
    
    // Check all holidays in the table
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM kalender_akademik");
    $stmt->execute();
    $total = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Total holidays in database: {$total['total']}\n\n";
    
    // Show some recent holidays
    $stmt = $pdo->prepare("
        SELECT tanggal_mulai, tanggal_selesai, status, keterangan
        FROM kalender_akademik 
        ORDER BY tanggal_mulai DESC
        LIMIT 10
    ");
    
    $stmt->execute();
    $recentHolidays = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Recent holidays:\n";
    foreach ($recentHolidays as $holiday) {
        echo "  - {$holiday['tanggal_mulai']} to {$holiday['tanggal_selesai']}: {$holiday['status']} - {$holiday['keterangan']}\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

echo "\n=== END CHECK ===\n";
