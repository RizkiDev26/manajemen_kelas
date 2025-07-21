<?php
// Simple script to fix NIP data
$db = new mysqli('localhost', 'root', '', 'sdngu09');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

echo "Current walikelas data:\n";
$result = $db->query("SELECT id, nama, kelas, nip FROM walikelas ORDER BY id");
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: {$row['id']} | Nama: {$row['nama']} | Kelas: {$row['kelas']} | NIP: {$row['nip']}\n";
        
        // Check if NIP is non-numeric or empty
        if (!is_numeric($row['nip']) || empty($row['nip'])) {
            // Generate new NIP
            do {
                $newNIP = '19' . date('Ymd') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                $checkResult = $db->query("SELECT id FROM walikelas WHERE nip = '$newNIP'");
            } while ($checkResult && $checkResult->num_rows > 0);
            
            // Update the record
            $updateQuery = "UPDATE walikelas SET nip = '$newNIP' WHERE id = {$row['id']}";
            if ($db->query($updateQuery)) {
                echo "  ✅ Updated with new NIP: $newNIP\n";
            } else {
                echo "  ❌ Failed to update: " . $db->error . "\n";
            }
        } else {
            echo "  ✅ NIP is already numeric\n";
        }
    }
}

$db->close();
echo "\nDone!\n";
?>
