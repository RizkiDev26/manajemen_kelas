<?php
// Script untuk melihat semua tabel di database

echo "=== LISTING ALL TABLES ===\n\n";

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sdngu09';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected to database\n\n";
    
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tables in database '$database':\n";
    foreach ($tables as $table) {
        echo "- $table\n";
    }
    
    // Look for tables containing 'absen'
    echo "\n=== TABLES CONTAINING 'absen' ===\n";
    $absenTables = array_filter($tables, function($table) {
        return stripos($table, 'absen') !== false;
    });
    
    if (!empty($absenTables)) {
        foreach ($absenTables as $table) {
            echo "Found: $table\n";
            
            // Check structure
            $stmt = $pdo->query("DESCRIBE $table");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "  Columns:\n";
            foreach ($columns as $column) {
                echo "    - {$column['Field']} ({$column['Type']})\n";
            }
            echo "\n";
        }
    } else {
        echo "No tables found containing 'absen'\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
