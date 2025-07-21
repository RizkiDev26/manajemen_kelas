<?php
// Script untuk export database ke SQL file untuk InfinityFree

echo "=== EXPORT DATABASE UNTUK INFINITYFREE ===\n\n";

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
    
    $sqlDump = "-- SQL Export for InfinityFree\n";
    $sqlDump .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n";
    $sqlDump .= "-- Database: $database\n\n";
    
    $sqlDump .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
    $sqlDump .= "SET AUTOCOMMIT = 0;\n";
    $sqlDump .= "START TRANSACTION;\n";
    $sqlDump .= "SET time_zone = \"+00:00\";\n\n";
    
    foreach ($tables as $table) {
        echo "Exporting table: $table\n";
        
        // Get table structure
        $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
        $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $sqlDump .= "-- Structure for table `$table`\n";
        $sqlDump .= "DROP TABLE IF EXISTS `$table`;\n";
        $sqlDump .= $createTable['Create Table'] . ";\n\n";
        
        // Get table data
        $stmt = $pdo->query("SELECT * FROM `$table`");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($rows)) {
            $sqlDump .= "-- Data for table `$table`\n";
            
            foreach ($rows as $row) {
                $values = [];
                foreach ($row as $value) {
                    if ($value === null) {
                        $values[] = 'NULL';
                    } else {
                        $values[] = "'" . addslashes($value) . "'";
                    }
                }
                $sqlDump .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
            }
            $sqlDump .= "\n";
        }
    }
    
    $sqlDump .= "COMMIT;\n";
    
    // Save to file
    $exportFile = __DIR__ . '/deployment/database_export.sql';
    file_put_contents($exportFile, $sqlDump);
    
    echo "\n✓ Database exported successfully!\n";
    echo "File saved: deployment/database_export.sql\n";
    echo "Size: " . number_format(filesize($exportFile) / 1024, 2) . " KB\n";
    
    // Show table statistics
    echo "\nTable Statistics:\n";
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "- $table: $count records\n";
    }
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n=== EXPORT COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Upload database_export.sql ke InfinityFree via phpMyAdmin\n";
echo "2. Pastikan semua tabel ter-import dengan benar\n";
echo "3. Update prefix tabel jika diperlukan\n";
?>
