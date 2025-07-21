<?php
/**
 * Add Sample Student and Attendance Data for Testing
 */

// Database configuration
$host = 'localhost';
$database = 'sdngu09';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Adding Sample Student and Attendance Data ===\n\n";
    
    // Check if tb_siswa table exists and has data
    $siswaCount = $pdo->query("SELECT COUNT(*) as count FROM tb_siswa WHERE kelas = '5A'")->fetch()['count'];
    echo "Current students in class 5A: $siswaCount\n";
    
    if ($siswaCount == 0) {
        echo "Adding sample students...\n";
        
        // Sample students for class 5A
        $students = [
            ['nama' => 'Ahmad Rizki', 'kelas' => '5A'],
            ['nama' => 'Siti Nurhaliza', 'kelas' => '5A'],
            ['nama' => 'Budi Santoso', 'kelas' => '5A'],
            ['nama' => 'Dewi Lestari', 'kelas' => '5A'],
            ['nama' => 'Fahmi Ramadhan', 'kelas' => '5A'],
            ['nama' => 'Gita Savitri', 'kelas' => '5A'],
            ['nama' => 'Hendra Wijaya', 'kelas' => '5A'],
            ['nama' => 'Indira Sari', 'kelas' => '5A'],
            ['nama' => 'Joko Susilo', 'kelas' => '5A'],
            ['nama' => 'Kartika Putri', 'kelas' => '5A']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO tb_siswa (nama, kelas, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
        
        foreach ($students as $student) {
            $stmt->execute([$student['nama'], $student['kelas']]);
            echo "Added student: {$student['nama']}\n";
        }
        
        echo "Sample students added successfully.\n\n";
    }
    
    // Get student IDs for attendance data
    $students = $pdo->query("SELECT id, nama FROM tb_siswa WHERE kelas = '5A' ORDER BY nama")->fetchAll();
    echo "Found " . count($students) . " students for attendance data.\n";
    
    // Clear existing attendance data for July 2025
    $pdo->exec("DELETE FROM absensi WHERE YEAR(tanggal) = 2025 AND MONTH(tanggal) = 7");
    echo "Cleared existing July 2025 attendance data.\n";
    
    // Add sample attendance data for first 10 days of July 2025
    echo "Adding sample attendance data...\n";
    
    $stmt = $pdo->prepare("INSERT INTO absensi (siswa_id, tanggal, status, created_by, created_at, updated_at) VALUES (?, ?, ?, 1, NOW(), NOW())");
    
    for ($day = 2; $day <= 10; $day++) {
        $date = "2025-07-" . str_pad($day, 2, '0', STR_PAD_LEFT);
        
        // Skip weekends and holidays
        $dayOfWeek = date('w', strtotime($date));
        if ($dayOfWeek == 0 || $dayOfWeek == 6) continue; // Skip weekends
        
        // Check if it's a holiday
        $isHoliday = $pdo->query("SELECT COUNT(*) as count FROM kalender_akademik WHERE '$date' BETWEEN tanggal_mulai AND tanggal_selesai")->fetch()['count'] > 0;
        if ($isHoliday) continue; // Skip holidays
        
        echo "Adding attendance for $date...\n";
        
        foreach ($students as $student) {
            // Generate random but realistic attendance
            $rand = rand(1, 100);
            if ($rand <= 85) {
                $status = 'hadir';
            } elseif ($rand <= 92) {
                $status = 'izin';
            } else {
                $status = 'sakit';
            }
            
            $stmt->execute([$student['id'], $date, $status]);
        }
    }
    
    // Verify attendance data
    $attendanceCount = $pdo->query("SELECT COUNT(*) as count FROM absensi WHERE YEAR(tanggal) = 2025 AND MONTH(tanggal) = 7")->fetch()['count'];
    echo "\nTotal attendance records for July 2025: $attendanceCount\n";
    
    // Show attendance summary
    $summary = $pdo->query("
        SELECT status, COUNT(*) as count 
        FROM absensi 
        WHERE YEAR(tanggal) = 2025 AND MONTH(tanggal) = 7 
        GROUP BY status
    ")->fetchAll();
    
    echo "\nAttendance summary for July 2025:\n";
    foreach ($summary as $row) {
        echo "- {$row['status']}: {$row['count']}\n";
    }
    
    echo "\n=== Sample Data Added Successfully ===\n";
    echo "You can now test the attendance recap at: /admin/absensi/rekap\n";
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
