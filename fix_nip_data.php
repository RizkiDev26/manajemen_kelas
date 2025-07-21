<?php
// Fix existing NIP data that contains text instead of numbers
try {
    $db = new mysqli('localhost', 'root', '', 'sdngu09');
    
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    
    echo "Checking walikelas table for non-numeric NIP values...\n";
    echo str_repeat("-", 70) . "\n";
    
    // Find all walikelas records with non-numeric NIP
    $result = $db->query("SELECT id, nama, kelas, nip FROM walikelas WHERE nip REGEXP '[^0-9]' OR nip = ''");
    
    if ($result && $result->num_rows > 0) {
        echo "Found walikelas records with invalid NIP:\n";
        echo sprintf("%-5s | %-25s | %-15s | %-20s\n", "ID", "Nama", "Kelas", "Current NIP");
        echo str_repeat("-", 70) . "\n";
        
        $toUpdate = [];
        while($row = $result->fetch_assoc()) {
            echo sprintf("%-5s | %-25s | %-15s | %-20s\n", 
                $row['id'], 
                substr($row['nama'], 0, 25), 
                $row['kelas'], 
                substr($row['nip'] ?: 'EMPTY', 0, 20));
            
            $toUpdate[] = $row['id'];
        }
        
        echo str_repeat("-", 70) . "\n";
        echo "Found " . count($toUpdate) . " records that need NIP update.\n";
        
        // Ask for confirmation
        echo "\nDo you want to generate new numeric NIP for these records? (y/n): ";
        $handle = fopen("php://stdin", "r");
        $confirmation = trim(fgets($handle));
        fclose($handle);
        
        if (strtolower($confirmation) === 'y' || strtolower($confirmation) === 'yes') {
            echo "\nGenerating new numeric NIP values...\n";
            
            foreach ($toUpdate as $id) {
                // Generate unique NIP
                do {
                    // Generate NIP format: 19 + YYYYMMDD + 4 random digits
                    $newNIP = '19' . date('Ymd') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                    
                    // Check if this NIP already exists
                    $checkResult = $db->query("SELECT id FROM walikelas WHERE nip = '$newNIP'");
                } while ($checkResult && $checkResult->num_rows > 0);
                
                // Update the record
                $updateQuery = "UPDATE walikelas SET nip = '$newNIP' WHERE id = $id";
                if ($db->query($updateQuery)) {
                    echo "✅ Updated ID $id with new NIP: $newNIP\n";
                } else {
                    echo "❌ Failed to update ID $id: " . $db->error . "\n";
                }
            }
            
            echo "\n✅ NIP update process completed!\n";
        } else {
            echo "❌ Update cancelled by user.\n";
        }
    } else {
        echo "✅ All NIP values are already numeric. No updates needed.\n";
    }
    
    // Show current state
    echo "\n" . str_repeat("-", 70) . "\n";
    echo "Current walikelas data:\n";
    $result = $db->query("SELECT id, nama, kelas, nip FROM walikelas ORDER BY id");
    if ($result && $result->num_rows > 0) {
        echo sprintf("%-5s | %-25s | %-15s | %-20s\n", "ID", "Nama", "Kelas", "NIP");
        echo str_repeat("-", 70) . "\n";
        while($row = $result->fetch_assoc()) {
            echo sprintf("%-5s | %-25s | %-15s | %-20s\n", 
                $row['id'], 
                substr($row['nama'], 0, 25), 
                $row['kelas'], 
                $row['nip']);
        }
    }
    
    $db->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
