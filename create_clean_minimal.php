<?php
// Script untuk membuat file database minimal yang benar-benar clean

echo "=== MEMBUAT DATABASE MINIMAL TANPA FOREIGN KEY ===\n\n";

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sdngu09';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected to database\n";
    
    // Tabel yang diperlukan untuk testing basic
    $essentialTables = ['users', 'walikelas', 'tb_guru', 'migrations'];
    
    $minimalSQL = "-- ======================================\n";
    $minimalSQL .= "-- DATABASE MINIMAL UNTUK TESTING\n";
    $minimalSQL .= "-- Tanpa foreign key untuk avoid error\n";
    $minimalSQL .= "-- ======================================\n\n";
    
    $minimalSQL .= "/*!40101 SET NAMES utf8mb4 */;\n";
    $minimalSQL .= "/*!40014 SET FOREIGN_KEY_CHECKS=0 */;\n";
    $minimalSQL .= "/*!40101 SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;\n\n";
    
    foreach ($essentialTables as $table) {
        echo "Processing table: $table\n";
        
        $minimalSQL .= "-- ======================================\n";
        $minimalSQL .= "-- Table: $table\n";
        $minimalSQL .= "-- ======================================\n";
        
        // Get table structure
        $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
        $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
        $createTableSQL = $createTable['Create Table'];
        
        // Remove ALL foreign key constraints dan indexes
        $createTableSQL = preg_replace('/,\s*CONSTRAINT `[^`]*` FOREIGN KEY \([^)]*\) REFERENCES [^,]*/', '', $createTableSQL);
        $createTableSQL = preg_replace('/,\s*KEY `[^`]*` \([^)]*\)/', '', $createTableSQL);
        $createTableSQL = preg_replace('/,\s*UNIQUE KEY `[^`]*` \([^)]*\)/', '', $createTableSQL);
        
        // Clean up extra commas
        $createTableSQL = preg_replace('/,(\s*\))/', '$1', $createTableSQL);
        
        $minimalSQL .= "DROP TABLE IF EXISTS `$table`;\n";
        $minimalSQL .= $createTableSQL . ";\n\n";
        
        // Get data for essential tables
        $stmt = $pdo->query("SELECT * FROM `$table`");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($rows)) {
            $minimalSQL .= "-- Data untuk $table\n";
            
            // Get column info
            $stmt = $pdo->query("DESCRIBE `$table`");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $minimalSQL .= "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES\n";
            
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
                        // Escape dengan benar
                        $escaped = str_replace(["\\", "'"], ["\\\\", "\\'"], $value);
                        $values[] = "'" . $escaped . "'";
                    }
                }
                
                $minimalSQL .= "(" . implode(', ', $values) . ")";
                
                if ($currentRow < $rowCount) {
                    $minimalSQL .= ",\n";
                } else {
                    $minimalSQL .= ";\n\n";
                }
            }
        } else {
            $minimalSQL .= "-- No data for $table\n\n";
        }
    }
    
    $minimalSQL .= "/*!40014 SET FOREIGN_KEY_CHECKS=1 */;\n";
    $minimalSQL .= "/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;\n\n";
    $minimalSQL .= "-- ======================================\n";
    $minimalSQL .= "-- MINIMAL DATABASE EXPORT COMPLETE\n";
    $minimalSQL .= "-- Ready untuk upload ke InfinityFree\n";
    $minimalSQL .= "-- ======================================\n";
    
    // Save file
    $exportFile = __DIR__ . '/deployment/database_minimal_clean.sql';
    file_put_contents($exportFile, $minimalSQL);
    
    echo "\n✓ Clean minimal database export berhasil!\n";
    echo "File: database_minimal_clean.sql\n";
    echo "Size: " . number_format(filesize($exportFile) / 1024, 2) . " KB\n";
    
    // Buat juga file super minimal hanya users
    $superMinimal = "-- SUPER MINIMAL - HANYA USERS\n\n";
    $superMinimal .= "/*!40101 SET NAMES utf8mb4 */;\n";
    $superMinimal .= "/*!40014 SET FOREIGN_KEY_CHECKS=0 */;\n\n";
    
    // Users table tanpa foreign key
    $stmt = $pdo->query("SHOW CREATE TABLE users");
    $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
    $createTableSQL = $createTable['Create Table'];
    
    // Remove semua constraint
    $createTableSQL = preg_replace('/,\s*CONSTRAINT[^,]*/', '', $createTableSQL);
    $createTableSQL = preg_replace('/,\s*KEY[^,]*/', '', $createTableSQL);
    $createTableSQL = preg_replace('/,(\s*\))/', '$1', $createTableSQL);
    
    $superMinimal .= "DROP TABLE IF EXISTS `users`;\n";
    $superMinimal .= $createTableSQL . ";\n\n";
    
    // Insert admin user only
    $stmt = $pdo->query("SELECT * FROM users WHERE role = 'admin' LIMIT 1");
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        $superMinimal .= "-- Admin user\n";
        $superMinimal .= "INSERT INTO `users` (`id`, `username`, `password`, `nama`, `email`, `role`, `walikelas_id`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES\n";
        
        $values = [];
        foreach ($admin as $value) {
            if ($value === null) {
                $values[] = 'NULL';
            } elseif (is_numeric($value)) {
                $values[] = $value;
            } else {
                $escaped = str_replace(["\\", "'"], ["\\\\", "\\'"], $value);
                $values[] = "'" . $escaped . "'";
            }
        }
        
        $superMinimal .= "(" . implode(', ', $values) . ");\n\n";
    }
    
    $superMinimal .= "/*!40014 SET FOREIGN_KEY_CHECKS=1 */;\n";
    
    file_put_contents(__DIR__ . '/deployment/database_super_minimal.sql', $superMinimal);
    echo "✓ Super minimal (users only) export created\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n=== FILES CREATED ===\n";
echo "1. database_minimal_clean.sql - 4 tabel penting tanpa foreign key\n";
echo "2. database_super_minimal.sql - hanya tabel users untuk test login\n\n";
echo "REKOMENDASI:\n";
echo "1. Coba upload database_super_minimal.sql dulu\n";
echo "2. Jika berhasil, test login website\n";
echo "3. Lalu upload database_minimal_clean.sql\n";
?>
