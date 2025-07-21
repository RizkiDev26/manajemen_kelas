<?php

// Debug script untuk memeriksa logika tanggal
$testDate = '2025-07-15';
$currentTime = time();
$testDateTime = strtotime($testDate);

echo "=== DEBUG TANGGAL LOGIC ===\n";
echo "Tanggal test: $testDate\n";
echo "Current time: " . date('Y-m-d H:i:s', $currentTime) . "\n";
echo "Test datetime: " . date('Y-m-d H:i:s', $testDateTime) . "\n";
echo "Is future? " . ($testDateTime > $currentTime ? 'YES' : 'NO') . "\n";

echo "\nPerbedaan waktu: " . ($testDateTime - $currentTime) . " detik\n";
echo "Atau: " . round(($testDateTime - $currentTime) / 86400, 2) . " hari\n";

// Check day of week
$dayOfWeek = date('w', strtotime($testDate));
echo "\nDay of week (0=Sunday, 6=Saturday): $dayOfWeek\n";
echo "Is weekend? " . (($dayOfWeek == 0 || $dayOfWeek == 6) ? 'YES' : 'NO') . "\n";

echo "\n=== SIMULASI LOGIKA DARI MODEL ===\n";

$year = 2025;
$month = 7;
$day = 15;
$monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);
$dayStr = str_pad($day, 2, '0', STR_PAD_LEFT);
$currentDate = "$year-$monthStr-$dayStr";

echo "Current date string: $currentDate\n";

// Simulate holiday check (assuming no holiday)
$isHoliday = false; // Simulate no holiday
$isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);

echo "Is holiday: " . ($isHoliday ? 'YES' : 'NO') . "\n";
echo "Is weekend: " . ($isWeekend ? 'YES' : 'NO') . "\n";

if ($isHoliday || $isWeekend) {
    echo "RESULT: Would skip - Holiday or Weekend\n";
} else {
    // Check if future date (only for non-holidays)
    if (strtotime($currentDate) > time()) {
        echo "RESULT: Would set to NULL - Future date\n";
    } else {
        echo "RESULT: Would process normally - Past or current date\n";
    }
}

echo "\n=== END DEBUG ===\n";
