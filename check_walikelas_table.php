<?php
// Check walikelas table structure
try {
    $db = new mysqli('localhost', 'root', '', 'sdngu09');
    
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    
    echo "Walikelas table structure:\n";
    echo str_repeat("-", 80) . "\n";
    echo sprintf("%-20s | %-20s | %-5s | %-5s | %-10s | %-10s\n", 
        "Field", "Type", "Null", "Key", "Default", "Extra");
    echo str_repeat("-", 80) . "\n";
    
    $result = $db->query('DESCRIBE walikelas');
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo sprintf("%-20s | %-20s | %-5s | %-5s | %-10s | %-10s\n", 
                $row['Field'], 
                $row['Type'], 
                $row['Null'], 
                $row['Key'], 
                $row['Default'] ?: 'NULL', 
                $row['Extra']);
        }
    }
    
    echo str_repeat("-", 80) . "\n";
    
    // Check sample data
    $result = $db->query('SELECT id, nama, kelas, nip FROM walikelas LIMIT 5');
    if ($result && $result->num_rows > 0) {
        echo "\nSample walikelas data:\n";
        echo str_repeat("-", 70) . "\n";
        echo sprintf("%-5s | %-20s | %-15s | %-15s\n", "ID", "Nama", "Kelas", "NIP");
        echo str_repeat("-", 70) . "\n";
        while($row = $result->fetch_assoc()) {
            echo sprintf("%-5s | %-20s | %-15s | %-15s\n", 
                $row['id'], 
                substr($row['nama'], 0, 20), 
                $row['kelas'], 
                $row['nip'] ?? 'NULL');
        }
    }
    
    // Check for duplicate NIP
    $result = $db->query('SELECT nip, COUNT(*) as count FROM walikelas WHERE nip IS NOT NULL AND nip != "" GROUP BY nip HAVING count > 1');
    if ($result && $result->num_rows > 0) {
        echo "\nDuplicate NIP found:\n";
        echo str_repeat("-", 40) . "\n";
        echo sprintf("%-20s | %-10s\n", "NIP", "Count");
        echo str_repeat("-", 40) . "\n";
        while($row = $result->fetch_assoc()) {
            echo sprintf("%-20s | %-10s\n", $row['nip'], $row['count']);
        }
    } else {
        echo "\nNo duplicate NIP found in walikelas table.\n";
    }
    
    $db->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
