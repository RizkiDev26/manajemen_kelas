<?php
// Script untuk membuat database export yang kompatibel dengan MariaDB InfinityFree

echo "=== MEMBUAT DATABASE EXPORT KOMPATIBEL MARIADB ===\n\n";

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
    $sqlDump .= "-- SQL Export kompatibel MariaDB\n";
    $sqlDump .= "-- Database: $database\n";
    $sqlDump .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
    $sqlDump .= "-- ======================================\n\n";
    
    // Settings untuk MariaDB compatibility
    $sqlDump .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n";
    $sqlDump .= "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n";
    $sqlDump .= "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n";
    $sqlDump .= "/*!40101 SET NAMES utf8mb4 */;\n";
    $sqlDump .= "/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;\n";
    $sqlDump .= "/*!40103 SET TIME_ZONE='+00:00' */;\n";
    $sqlDump .= "/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;\n";
    $sqlDump .= "/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;\n";
    $sqlDump .= "/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;\n";
    $sqlDump .= "/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;\n\n";
    
    // Export setiap tabel dengan format MariaDB-compatible
    foreach ($tables as $table) {
        echo "Processing table: $table\n";
        
        $sqlDump .= "--\n";
        $sqlDump .= "-- Table structure for table `$table`\n";
        $sqlDump .= "--\n\n";
        
        $sqlDump .= "DROP TABLE IF EXISTS `$table`;\n";
        $sqlDump .= "/*!40101 SET @saved_cs_client     = @@character_set_client */;\n";
        $sqlDump .= "/*!40101 SET character_set_client = utf8 */;\n";
        
        // Get table structure dengan format yang lebih kompatibel
        $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
        $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
        $createTableSQL = $createTable['Create Table'];
        
        // Fix untuk MariaDB compatibility
        $createTableSQL = str_replace('AUTO_INCREMENT=', 'AUTO_INCREMENT = ', $createTableSQL);
        $createTableSQL = preg_replace('/CONSTRAINT `[^`]*` FOREIGN KEY[^,]*,[\\s]*/php', '', $createTableSQL);
        $createTableSQL = preg_replace('/CONSTRAINT `[^`]*` FOREIGN KEY[^)]*\\)[^,]*/', '', $createTableSQL);
        
        $sqlDump .= $createTableSQL . ";\n";
        $sqlDump .= "/*!40101 SET character_set_client = @saved_cs_client */;\n\n";
        
        // Get table data
        $stmt = $pdo->query("SELECT * FROM `$table`");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($rows)) {
            $sqlDump .= "--\n";
            $sqlDump .= "-- Dumping data for table `$table`\n";
            $sqlDump .= "--\n\n";
            
            $sqlDump .= "LOCK TABLES `$table` WRITE;\n";
            $sqlDump .= "/*!40000 ALTER TABLE `$table` DISABLE KEYS */;\n";
            
            // Get column names
            $stmt = $pdo->query("DESCRIBE `$table`");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $sqlDump .= "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES\n";
            
            $rowCount = count($rows);
            $currentRow = 0;
            
            foreach ($rows as $row) {
                $currentRow++;
                $values = [];
                
                foreach ($row as $value) {
                    if ($value === null) {
                        $values[] = 'NULL';
                    } elseif (is_numeric($value) && !is_string($value)) {
                        $values[] = $value;
                    } else {
                        // Proper escaping untuk MariaDB
                        $escaped = addslashes($value);
                        $values[] = "'" . $escaped . "'";
                    }
                }
                
                $sqlDump .= "(" . implode(',', $values) . ")";
                
                if ($currentRow < $rowCount) {
                    $sqlDump .= ",\n";
                } else {
                    $sqlDump .= ";\n";
                }
            }
            
            $sqlDump .= "/*!40000 ALTER TABLE `$table` ENABLE KEYS */;\n";
            $sqlDump .= "UNLOCK TABLES;\n\n";
        } else {
            $sqlDump .= "-- No data for table `$table`\n\n";
        }
    }
    
    // Restore settings
    $sqlDump .= "/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;\n";
    $sqlDump .= "/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;\n";
    $sqlDump .= "/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;\n";
    $sqlDump .= "/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;\n";
    $sqlDump .= "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n";
    $sqlDump .= "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n";
    $sqlDump .= "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n";
    $sqlDump .= "/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;\n\n";
    $sqlDump .= "-- Dump completed\n";
    
    // Save to file
    $exportFile = __DIR__ . '/deployment/database_mariadb_compatible.sql';
    file_put_contents($exportFile, $sqlDump);
    
    echo "\n✓ MariaDB-compatible database export berhasil!\n";
    echo "File: database_mariadb_compatible.sql\n";
    echo "Size: " . number_format(filesize($exportFile) / 1024, 2) . " KB\n";
    
    // Buat juga versi minimal tanpa data besar
    echo "\nCreating minimal version...\n";
    
    $minimalDump = $sqlDump;
    
    // Remove large table data, keep only essential tables
    $essentialTables = ['users', 'walikelas', 'tb_guru', 'migrations'];
    
    $minimalExport = __DIR__ . '/deployment/database_minimal.sql';
    
    // Create minimal version dengan hanya tabel penting
    $minimalSQL = "-- Minimal database untuk testing\n\n";
    $minimalSQL .= "/*!40101 SET NAMES utf8mb4 */;\n";
    $minimalSQL .= "/*!40014 SET FOREIGN_KEY_CHECKS=0 */;\n\n";
    
    foreach ($essentialTables as $table) {
        if (in_array($table, $tables)) {
            $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
            $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
            $createTableSQL = $createTable['Create Table'];
            
            // Clean foreign keys
            $createTableSQL = preg_replace('/CONSTRAINT `[^`]*` FOREIGN KEY[^,]*,[\\s]*/', '', $createTableSQL);
            
            $minimalSQL .= "DROP TABLE IF EXISTS `$table`;\n";
            $minimalSQL .= $createTableSQL . ";\n\n";
            
            // Add data untuk tabel penting
            $stmt = $pdo->query("SELECT * FROM `$table`");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (!empty($rows)) {
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
                            $escaped = addslashes($value);
                            $values[] = "'" . $escaped . "'";
                        }
                    }
                    
                    $minimalSQL .= "(" . implode(',', $values) . ")";
                    
                    if ($currentRow < $rowCount) {
                        $minimalSQL .= ",\n";
                    } else {
                        $minimalSQL .= ";\n\n";
                    }
                }
            }
        }
    }
    
    $minimalSQL .= "/*!40014 SET FOREIGN_KEY_CHECKS=1 */;\n";
    
    file_put_contents($minimalExport, $minimalSQL);
    echo "✓ Minimal database export created: database_minimal.sql\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n=== EXPORT COMPLETE ===\n";
echo "Files created:\n";
echo "1. database_mariadb_compatible.sql (full database)\n";
echo "2. database_minimal.sql (essential tables only)\n\n";
echo "Try uploading database_minimal.sql first for testing!\n";
?>
