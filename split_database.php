<?php
// Script untuk memecah database export menjadi file-file kecil

echo "=== MEMECAH DATABASE MENJADI FILE KECIL ===\n\n";

// Database config
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sdngu09';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $tables = ['absensi', 'berita', 'kalender_akademik', 'migrations', 'tb_guru', 'tb_siswa', 'users', 'walikelas'];
    
    // Buat folder untuk file-file kecil
    $splitDir = __DIR__ . '/deployment/database_split';
    if (!is_dir($splitDir)) {
        mkdir($splitDir, 0755, true);
    }
    
    // Header SQL umum
    $sqlHeader = "SET FOREIGN_KEY_CHECKS = 0;\n";
    $sqlHeader .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
    $sqlHeader .= "SET AUTOCOMMIT = 0;\n";
    $sqlHeader .= "START TRANSACTION;\n\n";
    
    $sqlFooter = "\nSET FOREIGN_KEY_CHECKS = 1;\n";
    $sqlFooter .= "COMMIT;\n";
    
    foreach ($tables as $table) {
        echo "Creating file for table: $table\n";
        
        $tableSQL = $sqlHeader;
        
        // Get table structure
        $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
        $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
        $createTableSQL = $createTable['Create Table'];
        
        // Remove foreign key constraints
        $createTableSQL = preg_replace('/,\s*CONSTRAINT[^,]*FOREIGN KEY[^,]*/', '', $createTableSQL);
        
        $tableSQL .= "-- Table: $table\n";
        $tableSQL .= "DROP TABLE IF EXISTS `$table`;\n";
        $tableSQL .= $createTableSQL . ";\n\n";
        
        // Get table data
        $stmt = $pdo->query("SELECT * FROM `$table`");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($rows)) {
            $tableSQL .= "-- Data for $table\n";
            
            // Jika tabel besar, pecah menjadi batch
            $batchSize = 100;
            $totalRows = count($rows);
            
            if ($totalRows > $batchSize) {
                echo "  -> Large table, splitting into batches\n";
                
                for ($i = 0; $i < $totalRows; $i += $batchSize) {
                    $batch = array_slice($rows, $i, $batchSize);
                    $batchSQL = $tableSQL;
                    
                    $batchSQL .= "INSERT INTO `$table` VALUES\n";
                    
                    $batchCount = count($batch);
                    $currentRow = 0;
                    
                    foreach ($batch as $row) {
                        $currentRow++;
                        $values = [];
                        
                        foreach ($row as $value) {
                            if ($value === null) {
                                $values[] = 'NULL';
                            } elseif (is_numeric($value)) {
                                $values[] = $value;
                            } else {
                                $escaped = str_replace("'", "''", $value);
                                $values[] = "'" . $escaped . "'";
                            }
                        }
                        
                        $batchSQL .= "(" . implode(', ', $values) . ")";
                        
                        if ($currentRow < $batchCount) {
                            $batchSQL .= ",\n";
                        } else {
                            $batchSQL .= ";\n";
                        }
                    }
                    
                    $batchSQL .= $sqlFooter;
                    
                    $batchNumber = floor($i / $batchSize) + 1;
                    $filename = sprintf("%s/%02d_%s_batch%d.sql", $splitDir, array_search($table, $tables) + 1, $table, $batchNumber);
                    file_put_contents($filename, $batchSQL);
                    
                    echo "  -> Batch $batchNumber saved\n";
                }
            } else {
                // Tabel kecil, simpan dalam satu file
                $tableSQL .= "INSERT INTO `$table` VALUES\n";
                
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
                            $escaped = str_replace("'", "''", $value);
                            $values[] = "'" . $escaped . "'";
                        }
                    }
                    
                    $tableSQL .= "(" . implode(', ', $values) . ")";
                    
                    if ($currentRow < $rowCount) {
                        $tableSQL .= ",\n";
                    } else {
                        $tableSQL .= ";\n";
                    }
                }
                
                $tableSQL .= $sqlFooter;
                
                $filename = sprintf("%s/%02d_%s.sql", $splitDir, array_search($table, $tables) + 1, $table);
                file_put_contents($filename, $tableSQL);
            }
        } else {
            // Tabel kosong
            $tableSQL .= "-- No data for $table\n";
            $tableSQL .= $sqlFooter;
            
            $filename = sprintf("%s/%02d_%s.sql", $splitDir, array_search($table, $tables) + 1, $table);
            file_put_contents($filename, $tableSQL);
        }
        
        echo "  ✓ Done\n";
    }
    
    // Buat file install order
    $installOrder = "# URUTAN UPLOAD FILE KE INFINITYFREE\n\n";
    $installOrder .= "Upload file-file ini ke phpMyAdmin dalam urutan berikut:\n\n";
    
    $files = glob($splitDir . '/*.sql');
    sort($files);
    
    foreach ($files as $i => $file) {
        $installOrder .= ($i + 1) . ". " . basename($file) . "\n";
    }
    
    $installOrder .= "\nTIPS:\n";
    $installOrder .= "- Upload satu per satu\n";
    $installOrder .= "- Tunggu sampai selesai sebelum upload file berikutnya\n";
    $installOrder .= "- Jika ada error, coba upload ulang file yang error\n";
    
    file_put_contents($splitDir . '/UPLOAD_ORDER.txt', $installOrder);
    
    echo "\n✓ Database berhasil dipecah menjadi file-file kecil!\n";
    echo "Folder: deployment/database_split/\n";
    echo "Total files: " . count($files) . "\n";
    echo "Baca UPLOAD_ORDER.txt untuk urutan upload\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
