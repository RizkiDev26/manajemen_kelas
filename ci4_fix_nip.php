<?php
require_once 'vendor/autoload.php';

// Load CodeIgniter environment
$app = \Config\Services::codeigniter();
$app->initialize();

// Load database
$db = \Config\Database::connect();

echo "Checking and fixing NIP data...\n";

// Get all walikelas with invalid NIP
$query = $db->query("SELECT id, nama, kelas, nip FROM walikelas");
$walikelas = $query->getResultArray();

foreach ($walikelas as $wali) {
    echo "ID {$wali['id']}: {$wali['nama']} - {$wali['kelas']} - NIP: '{$wali['nip']}'";
    
    // Check if NIP is non-numeric or empty
    if (!is_numeric($wali['nip']) || empty($wali['nip'])) {
        // Generate new NIP
        do {
            $newNIP = '19' . date('Ymd') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            $checkQuery = $db->query("SELECT id FROM walikelas WHERE nip = ? AND id != ?", [$newNIP, $wali['id']]);
        } while ($checkQuery->getNumRows() > 0);
        
        // Update the record
        $updateQuery = $db->query("UPDATE walikelas SET nip = ? WHERE id = ?", [$newNIP, $wali['id']]);
        if ($updateQuery) {
            echo " → Updated to: $newNIP ✅\n";
        } else {
            echo " → Failed to update ❌\n";
        }
    } else {
        echo " → Already numeric ✅\n";
    }
}

echo "\nCompleted!\n";
?>
