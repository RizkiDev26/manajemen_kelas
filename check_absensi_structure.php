<?php
// Script untuk memeriksa struktur tabel absensi

echo "=== CHECKING ABSENSI TABLE STRUCTURE ===\n\n";

// Database config
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sdngu09';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected to database\n\n";
    
    // Check tb_absensi structure
    echo "=== TB_ABSENSI STRUCTURE ===\n";
    $stmt = $pdo->query("DESCRIBE tb_absensi");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        echo "Column: {$column['Field']}\n";
        echo "Type: {$column['Type']}\n";
        echo "Null: {$column['Null']}\n";
        echo "Default: {$column['Default']}\n";
        echo "Extra: {$column['Extra']}\n";
        echo "---\n";
    }
    
    // Check specific status column
    echo "\n=== STATUS COLUMN DETAILS ===\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM tb_absensi LIKE 'status'");
    $statusColumn = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($statusColumn) {
        echo "Status Column Type: {$statusColumn['Type']}\n";
        
        // Extract enum values
        if (preg_match("/^enum\((.+)\)$/", $statusColumn['Type'], $matches)) {
            $enumValues = str_getcsv($matches[1], ',', "'");
            echo "Available enum values:\n";
            foreach ($enumValues as $value) {
                echo "  - '$value'\n";
            }
            
            // Check if 'alpha' exists
            if (in_array('alpha', $enumValues)) {
                echo "\n✅ 'alpha' value EXISTS in enum\n";
            } else {
                echo "\n❌ 'alpha' value NOT EXISTS in enum\n";
                echo "Need to add 'alpha' to enum values\n";
            }
        }
    } else {
        echo "❌ Status column not found!\n";
    }
    
    // Check sample data
    echo "\n=== SAMPLE ABSENSI DATA ===\n";
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM tb_absensi GROUP BY status");
    $statusCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Current status values in data:\n";
    foreach ($statusCounts as $row) {
        echo "  - '{$row['status']}': {$row['count']} records\n";
    }
    
    // Test inserting alpha status
    echo "\n=== TESTING ALPHA INSERT ===\n";
    try {
        $testStmt = $pdo->prepare("INSERT INTO tb_absensi (siswa_id, tanggal, status, keterangan) VALUES (?, ?, ?, ?)");
        $testStmt->execute([1, '2025-07-13', 'alpha', 'Test alpha status']);
        echo "✅ Alpha insert test SUCCESSFUL\n";
        
        // Clean up test data
        $pdo->prepare("DELETE FROM tb_absensi WHERE status = 'alpha' AND keterangan = 'Test alpha status'")->execute();
        echo "✅ Test data cleaned up\n";
        
    } catch (PDOException $e) {
        echo "❌ Alpha insert test FAILED: " . $e->getMessage() . "\n";
        
        if (strpos($e->getMessage(), "Data truncated") !== false || strpos($e->getMessage(), "invalid") !== false) {
            echo "💡 This confirms 'alpha' is not in the enum values\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}

echo "\n=== CHECK COMPLETE ===\n";
?>
