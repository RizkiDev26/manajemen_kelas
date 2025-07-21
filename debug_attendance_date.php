<?php

// Simple debug script using PDO
$host = 'localhost';
$dbname = 'sdngu09'; // Sesuaikan dengan nama database Anda
$username = 'root'; // Sesuaikan dengan username database Anda
$password = ''; // Sesuaikan dengan password database Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DEBUG ATTENDANCE DATA UNTUK TANGGAL 15-07-2025 ===\n\n";
    
    // Check if there's any data for that date
    $stmt = $pdo->prepare("SELECT * FROM absensi WHERE tanggal = '2025-07-15' ORDER BY siswa_id");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Total records untuk tanggal 2025-07-15: " . count($results) . "\n\n";
    
    if (count($results) > 0) {
        echo "Data yang ditemukan:\n";
        foreach ($results as $row) {
            echo "- Siswa ID: {$row['siswa_id']}, Status: {$row['status']}, Keterangan: {$row['keterangan']}\n";
        }
    } else {
        echo "Tidak ada data absensi untuk tanggal 2025-07-15\n";
    }
    
    echo "\n";
    
    // Check for July 2025 data
    $stmt2 = $pdo->prepare("SELECT tanggal, COUNT(*) as count FROM absensi WHERE tanggal LIKE '2025-07%' GROUP BY tanggal ORDER BY tanggal");
    $stmt2->execute();
    $julyResults = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Data absensi untuk bulan Juli 2025:\n";
    if (count($julyResults) > 0) {
        foreach ($julyResults as $row) {
            echo "- {$row['tanggal']}: {$row['count']} records\n";
        }
    } else {
        echo "Tidak ada data absensi untuk bulan Juli 2025\n";
    }
    
    echo "\n";
    
    // Check for any data around that date
    $stmt3 = $pdo->prepare("SELECT tanggal, siswa_id, status FROM absensi WHERE tanggal BETWEEN '2025-07-10' AND '2025-07-20' ORDER BY tanggal, siswa_id");
    $stmt3->execute();
    $aroundResults = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Data absensi antara 10-20 Juli 2025:\n";
    if (count($aroundResults) > 0) {
        foreach ($aroundResults as $row) {
            echo "- {$row['tanggal']}: Siswa {$row['siswa_id']} = {$row['status']}\n";
        }
    } else {
        echo "Tidak ada data absensi antara 10-20 Juli 2025\n";
    }
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    echo "Silakan periksa konfigurasi database di atas script ini.\n";
}

echo "\n=== END DEBUG ===\n";
