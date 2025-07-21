<?php

require_once 'vendor/autoload.php';

// Initialize CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

// Get database connection
$db = \Config\Database::connect();

echo "=== Checking Current Classes ===\n";

// Check current classes in tb_siswa
try {
    $query = $db->query("SELECT DISTINCT kelas FROM tb_siswa ORDER BY kelas");
    $classes = $query->getResultArray();
    
    echo "Current classes in tb_siswa:\n";
    foreach ($classes as $class) {
        echo "- " . $class['kelas'] . "\n";
    }
    
    // Check if there are students with the old class names
    $oldClasses = ['rombel test backup a', 'test back up B', 'test backup a', 'test backup B'];
    $studentsToUpdate = [];
    
    foreach ($oldClasses as $oldClass) {
        $query = $db->query("SELECT COUNT(*) as count FROM tb_siswa WHERE kelas = ?", [$oldClass]);
        $result = $query->getRowArray();
        if ($result['count'] > 0) {
            $studentsToUpdate[$oldClass] = $result['count'];
        }
    }
    
    if (!empty($studentsToUpdate)) {
        echo "\n=== Students with old class names ===\n";
        foreach ($studentsToUpdate as $oldClass => $count) {
            echo "- $oldClass: $count students\n";
        }
        
        echo "\n=== Updating class names ===\n";
        
        // Update rombel test backup a -> Kelas 5 A
        if (isset($studentsToUpdate['rombel test backup a'])) {
            $db->query("UPDATE tb_siswa SET kelas = 'Kelas 5 A' WHERE kelas = 'rombel test backup a'");
            echo "✓ Updated 'rombel test backup a' to 'Kelas 5 A'\n";
        }
        
        // Update test back up B -> Kelas 5 B
        if (isset($studentsToUpdate['test back up B'])) {
            $db->query("UPDATE tb_siswa SET kelas = 'Kelas 5 B' WHERE kelas = 'test back up B'");
            echo "✓ Updated 'test back up B' to 'Kelas 5 B'\n";
        }
        
        // Update test backup a -> Kelas 5 A (alternative naming)
        if (isset($studentsToUpdate['test backup a'])) {
            $db->query("UPDATE tb_siswa SET kelas = 'Kelas 5 A' WHERE kelas = 'test backup a'");
            echo "✓ Updated 'test backup a' to 'Kelas 5 A'\n";
        }
        
        // Update test backup B -> Kelas 5 B (alternative naming)
        if (isset($studentsToUpdate['test backup B'])) {
            $db->query("UPDATE tb_siswa SET kelas = 'Kelas 5 B' WHERE kelas = 'test backup B'");
            echo "✓ Updated 'test backup B' to 'Kelas 5 B'\n";
        }
        
        // Also update any existing attendance records
        foreach (array_keys($studentsToUpdate) as $oldClass) {
            $newClass = '';
            if (stripos($oldClass, 'a') !== false) {
                $newClass = 'Kelas 5 A';
            } else if (stripos($oldClass, 'b') !== false) {
                $newClass = 'Kelas 5 B';
            }
            
            if ($newClass) {
                $db->query("UPDATE absensi SET kelas = ? WHERE kelas = ?", [$newClass, $oldClass]);
                echo "✓ Updated attendance records for '$oldClass' to '$newClass'\n";
            }
        }
        
        echo "\n=== Updated Classes ===\n";
        $query = $db->query("SELECT DISTINCT kelas FROM tb_siswa ORDER BY kelas");
        $updatedClasses = $query->getResultArray();
        
        foreach ($updatedClasses as $class) {
            echo "- " . $class['kelas'] . "\n";
        }
        
    } else {
        echo "\nNo students found with old class names. Classes might already be updated.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\nDone!\n";
