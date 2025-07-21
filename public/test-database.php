<?php

// Test database connectivity and student data
require_once 'vendor/autoload.php';

// Load CI4 environment
$pathsConfig = new \Config\Paths();
require $pathsConfig->systemDirectory . '/bootstrap.php';

// Create the application
$app = \Config\Services::codeigniter();

echo "<h2>Test Database Connection & Student Data</h2>";

try {
    $db = \Config\Database::connect();
    
    // Test 1: Check database connection
    echo "<h3>1. Database Connection</h3>";
    $result = $db->query("SELECT 1 as test")->getRow();
    echo "✅ Database connection: " . ($result ? "SUCCESS" : "FAILED") . "<br>";
    
    // Test 2: Check tb_siswa table exists
    echo "<h3>2. Table Check</h3>";
    $tables = $db->listTables();
    $hasStudentTable = in_array('tb_siswa', $tables);
    echo "✅ Table tb_siswa exists: " . ($hasStudentTable ? "YES" : "NO") . "<br>";
    
    if ($hasStudentTable) {
        // Test 3: Check student data
        echo "<h3>3. Student Data</h3>";
        $studentCount = $db->table('tb_siswa')->countAll();
        echo "📊 Total students: " . $studentCount . "<br>";
        
        if ($studentCount > 0) {
            // Test 4: Check required fields
            echo "<h3>4. Field Check</h3>";
            $fields = $db->getFieldNames('tb_siswa');
            $requiredFields = ['id', 'nama', 'jk', 'nisn', 'nipd', 'kelas'];
            
            foreach ($requiredFields as $field) {
                $exists = in_array($field, $fields);
                echo ($exists ? "✅" : "❌") . " Field '{$field}': " . ($exists ? "EXISTS" : "MISSING") . "<br>";
            }
            
            // Test 5: Sample data
            echo "<h3>5. Sample Data</h3>";
            $sample = $db->table('tb_siswa')
                         ->select('id, nama, jk, nisn, nipd, kelas')
                         ->limit(3)
                         ->get()
                         ->getResultArray();
            
            if (!empty($sample)) {
                echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
                echo "<tr><th>ID</th><th>Nama</th><th>JK</th><th>NISN</th><th>NIPD</th><th>Kelas</th></tr>";
                
                foreach ($sample as $student) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($student['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($student['nama']) . "</td>";
                    echo "<td>" . htmlspecialchars($student['jk'] ?? 'NULL') . "</td>";
                    echo "<td>" . htmlspecialchars($student['nisn'] ?? 'NULL') . "</td>";
                    echo "<td>" . htmlspecialchars($student['nipd'] ?? 'NULL') . "</td>";
                    echo "<td>" . htmlspecialchars($student['kelas'] ?? 'NULL') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            
            // Test 6: Classes available
            echo "<h3>6. Available Classes</h3>";
            $classes = $db->table('tb_siswa')
                          ->select('kelas')
                          ->distinct()
                          ->where('kelas IS NOT NULL')
                          ->orderBy('kelas')
                          ->get()
                          ->getResultArray();
            
            if (!empty($classes)) {
                echo "📚 Available classes: ";
                $kelasNames = array_map(function($item) { return $item['kelas']; }, $classes);
                echo implode(', ', $kelasNames) . "<br>";
            } else {
                echo "❌ No classes found<br>";
            }
        } else {
            echo "❌ No student data found. Please run data seeder first.<br>";
        }
    }
    
    echo "<br><h3>Next Steps:</h3>";
    echo "1. ✅ Database connection working<br>";
    echo "2. ✅ Fixed AbsensiModel to include 'jk' and 'nisn' fields<br>";
    echo "3. ✅ Updated view to handle missing 'jk' field gracefully<br>";
    echo "4. 🔄 Test the attendance input page: <a href='/admin/absensi/input'>/admin/absensi/input</a><br>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Check your database configuration in app/Config/Database.php<br>";
}

?>
