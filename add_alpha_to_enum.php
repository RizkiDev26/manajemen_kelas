<?php
// Script untuk menambahkan 'alpha' ke enum status di tabel absensi

echo "=== ADDING 'alpha' TO ABSENSI STATUS ENUM ===\n\n";

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sdngu09';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected to database\n\n";
    
    // Check current enum values
    echo "=== CURRENT STATUS ENUM ===\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM absensi LIKE 'status'");
    $statusColumn = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Current Type: {$statusColumn['Type']}\n";
    
    // Update enum to include 'alpha'
    echo "\n=== UPDATING ENUM TO INCLUDE 'alpha' ===\n";
    
    $sql = "ALTER TABLE absensi MODIFY COLUMN status ENUM('hadir','izin','sakit','alpha') NOT NULL";
    
    echo "SQL: $sql\n";
    
    $pdo->exec($sql);
    
    echo "✅ Successfully updated status enum!\n\n";
    
    // Verify the change
    echo "=== VERIFYING UPDATE ===\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM absensi LIKE 'status'");
    $statusColumn = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "New Type: {$statusColumn['Type']}\n";
    
    // Test inserting alpha status
    echo "\n=== TESTING ALPHA INSERT ===\n";
    try {
        $testStmt = $pdo->prepare("INSERT INTO absensi (siswa_id, tanggal, status, keterangan) VALUES (?, ?, ?, ?)");
        $testStmt->execute([1, '2025-07-13', 'alpha', 'Test alpha status']);
        echo "✅ Alpha insert test SUCCESSFUL\n";
        
        // Clean up test data
        $pdo->prepare("DELETE FROM absensi WHERE status = 'alpha' AND keterangan = 'Test alpha status'")->execute();
        echo "✅ Test data cleaned up\n";
        
    } catch (PDOException $e) {
        echo "❌ Alpha insert test FAILED: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== STATUS VALUES NOW AVAILABLE ===\n";
    echo "✅ hadir\n";
    echo "✅ izin\n"; 
    echo "✅ sakit\n";
    echo "✅ alpha\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== UPDATE COMPLETE ===\n";
echo "Now you can use 'alpha' status in the attendance system!\n";
?>
