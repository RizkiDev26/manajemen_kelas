<?php
// Script untuk membuat database export yang kompatibel dengan InfinityFree

echo "=== MEMBUAT DATABASE EXPORT KOMPATIBEL INFINITYFREE ===\n\n";

// Database config
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sdngu09';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected to database\n";
    
    // Get all tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $sqlDump = "-- ======================================\n";
    $sqlDump .= "-- SQL Export untuk InfinityFree\n";
    $sqlDump .= "-- Database: $database\n";
    $sqlDump .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
    $sqlDump .= "-- ======================================\n\n";
    
    // Disable foreign key checks untuk menghindari error
    $sqlDump .= "SET FOREIGN_KEY_CHECKS = 0;\n";
    $sqlDump .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
    $sqlDump .= "SET AUTOCOMMIT = 0;\n";
    $sqlDump .= "START TRANSACTION;\n";
    $sqlDump .= "SET time_zone = \"+00:00\";\n\n";
    
    // Export structure dan data untuk setiap tabel
    foreach ($tables as $table) {
        echo "Processing table: $table\n";
        
        // Get table structure tanpa foreign keys dulu
        $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
        $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $createTableSQL = $createTable['Create Table'];
        
        // Remove foreign key constraints dari CREATE TABLE
        $createTableSQL = preg_replace('/,\s*CONSTRAINT[^,]*FOREIGN KEY[^,]*/', '', $createTableSQL);
        $createTableSQL = preg_replace('/,\s*KEY[^,]*\([^)]*\)/', '', $createTableSQL);
        
        $sqlDump .= "-- ======================================\n";
        $sqlDump .= "-- Table: $table\n";
        $sqlDump .= "-- ======================================\n";
        $sqlDump .= "DROP TABLE IF EXISTS `$table`;\n";
        $sqlDump .= $createTableSQL . ";\n\n";
        
        // Get table data
        $stmt = $pdo->query("SELECT * FROM `$table`");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($rows)) {
            $sqlDump .= "-- Data untuk table $table\n";
            $sqlDump .= "INSERT INTO `$table` VALUES\n";
            
            $rowCount = count($rows);
            $currentRow = 0;
            
            foreach ($rows as $row) {
                $currentRow++;
                $values = [];
                
                foreach ($row as $value) {
                    if ($value === null) {
                        $values[] = 'NULL';
                    } elseif (is_numeric($value)) {
                        $values[] = $value;
                    } else {
                        // Escape single quotes untuk string
                        $escaped = str_replace("'", "''", $value);
                        $values[] = "'" . $escaped . "'";
                    }
                }
                
                $sqlDump .= "(" . implode(', ', $values) . ")";
                
                // Tambahkan koma atau semicolon
                if ($currentRow < $rowCount) {
                    $sqlDump .= ",\n";
                } else {
                    $sqlDump .= ";\n\n";
                }
            }
        } else {
            $sqlDump .= "-- No data for table $table\n\n";
        }
    }
    
    // Enable foreign key checks kembali
    $sqlDump .= "SET FOREIGN_KEY_CHECKS = 1;\n";
    $sqlDump .= "COMMIT;\n";
    
    // Save to file
    $exportFile = __DIR__ . '/deployment/database_infinityfree.sql';
    file_put_contents($exportFile, $sqlDump);
    
    echo "\n✓ Database export berhasil!\n";
    echo "File: database_infinityfree.sql\n";
    echo "Size: " . number_format(filesize($exportFile) / 1024, 2) . " KB\n";
    
    // Buat juga versi tanpa data untuk testing
    $structureOnly = "-- ======================================\n";
    $structureOnly .= "-- STRUCTURE ONLY - untuk testing\n";
    $structureOnly .= "-- ======================================\n\n";
    
    $structureOnly .= "SET FOREIGN_KEY_CHECKS = 0;\n";
    $structureOnly .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n\n";
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
        $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
        $createTableSQL = $createTable['Create Table'];
        
        // Remove foreign key constraints
        $createTableSQL = preg_replace('/,\s*CONSTRAINT[^,]*FOREIGN KEY[^,]*/', '', $createTableSQL);
        $createTableSQL = preg_replace('/,\s*KEY[^,]*\([^)]*\)/', '', $createTableSQL);
        
        $structureOnly .= "DROP TABLE IF EXISTS `$table`;\n";
        $structureOnly .= $createTableSQL . ";\n\n";
    }
    
    $structureOnly .= "SET FOREIGN_KEY_CHECKS = 1;\n";
    
    file_put_contents(__DIR__ . '/deployment/database_structure_only.sql', $structureOnly);
    echo "✓ Structure-only file created: database_structure_only.sql\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n=== TROUBLESHOOTING TIPS ===\n";
echo "1. Coba upload database_structure_only.sql dulu\n";
echo "2. Jika berhasil, lalu upload database_infinityfree.sql\n";
echo "3. Pastikan ukuran file < 50MB untuk phpMyAdmin\n";
echo "4. Jika masih error, upload tabel satu per satu\n";
?>
