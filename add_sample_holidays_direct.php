<?php
/**
 * Add Sample Holiday Data for Testing - Direct DB Connection
 */

// Database configuration
$host = 'localhost';
$database = 'sdngu09';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Adding Sample Holiday Data ===\n\n";
    
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
    
    // Check if table exists
    $result = $pdo->query("SHOW TABLES LIKE 'kalender_akademik'")->fetch();
    if (!$result) {
        echo "Error: kalender_akademik table does not exist.\n";
        echo "Creating table...\n";
        
        $createTable = "
        CREATE TABLE kalender_akademik (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tanggal_mulai DATE NOT NULL,
            tanggal_selesai DATE NOT NULL,
            status ENUM('libur_nasional', 'libur_sekolah', 'off') NOT NULL,
            keterangan TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $pdo->exec($createTable);
        echo "Table created successfully.\n";
    }
    
    // Clear existing data for July 2025
    $pdo->exec("DELETE FROM kalender_akademik WHERE YEAR(tanggal_mulai) = 2025 AND MONTH(tanggal_mulai) = 7");
    echo "Cleared existing July 2025 holiday data.\n";
    
    // Prepare insert statement
    $stmt = $pdo->prepare("
        INSERT INTO kalender_akademik (tanggal_mulai, tanggal_selesai, status, keterangan, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    // Insert new data
    foreach ($holidays as $holiday) {
        $stmt->execute([
            $holiday['tanggal_mulai'],
            $holiday['tanggal_selesai'],
            $holiday['status'],
            $holiday['keterangan'],
            $holiday['created_at'],
            $holiday['updated_at']
        ]);
        echo "Added holiday: {$holiday['tanggal_mulai']} - {$holiday['keterangan']}\n";
    }
    
    // Verify data was inserted
    $count = $pdo->query("SELECT COUNT(*) as count FROM kalender_akademik WHERE YEAR(tanggal_mulai) = 2025 AND MONTH(tanggal_mulai) = 7")->fetch()['count'];
    echo "\nTotal holidays in July 2025: $count\n";
    
    // Show all holidays
    echo "\nAll holidays in July 2025:\n";
    $holidays = $pdo->query("SELECT * FROM kalender_akademik WHERE YEAR(tanggal_mulai) = 2025 AND MONTH(tanggal_mulai) = 7 ORDER BY tanggal_mulai")->fetchAll();
    foreach ($holidays as $holiday) {
        echo "- {$holiday['tanggal_mulai']} to {$holiday['tanggal_selesai']}: {$holiday['status']} - {$holiday['keterangan']}\n";
    }
    
    echo "\n=== Sample Data Added Successfully ===\n";
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
