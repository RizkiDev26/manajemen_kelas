<?php

// Debug script using CodeIgniter 4 Spark
// This should be run via: php spark list

use App\Models\AbsensiModel;
use App\Models\KalenderAkademikModel;

$absensiModel = new AbsensiModel();
$kalenderModel = new KalenderAkademikModel();

echo "=== DEBUG HOLIDAY DETECTION ===\n\n";

// Test for July 2025
$year = 2025;
$month = 7;
$kelas = '5A';

echo "Testing for: $year-$month (Kelas: $kelas)\n\n";

// Check what holidays exist in the calendar
echo "1. Holidays in calendar for July 2025:\n";
$holidays = $kalenderModel->getCalendarEvents($year, $month);
foreach ($holidays as $holiday) {
    echo "   - {$holiday['tanggal_mulai']} to {$holiday['tanggal_selesai']}: {$holiday['status']} - {$holiday['keterangan']}\n";
}

echo "\n2. Getting detailed attendance data:\n";
$attendanceData = $absensiModel->getDetailedAttendanceRecap($year, $month, $kelas);

echo "   - Year: " . $attendanceData['year'] . "\n";
echo "   - Month: " . $attendanceData['month'] . "\n";
echo "   - Holidays detected: " . count($attendanceData['holidays'] ?? []) . "\n";

if (!empty($attendanceData['holidays'])) {
    echo "   - Holiday dates:\n";
    foreach ($attendanceData['holidays'] as $date) {
        $details = $attendanceData['holidayDetails'][$date] ?? [];
        echo "     * $date: {$details['status']} - {$details['keterangan']}\n";
    }
}

echo "\n3. Sample student data (first student daily attendance):\n";
if (!empty($attendanceData['students'])) {
    $firstStudent = $attendanceData['students'][0];
    echo "   - Student: {$firstStudent['nama']}\n";
    echo "   - Daily attendance sample:\n";
    
    for ($day = 1; $day <= 10; $day++) {
        $dayStr = str_pad($day, 2, '0', STR_PAD_LEFT);
        $status = $firstStudent['daily'][$dayStr] ?? 'no data';
        echo "     * July $day: $status\n";
    }
}

echo "\n=== END DEBUG ===\n";
