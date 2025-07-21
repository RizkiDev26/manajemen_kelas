<?php
// Check users table structure
try {
    $db = new mysqli('localhost', 'root', '', 'sdngu09');
    
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    
    echo "Users table structure:\n";
    echo str_repeat("-", 80) . "\n";
    echo sprintf("%-20s | %-20s | %-5s | %-5s | %-10s | %-10s\n", 
        "Field", "Type", "Null", "Key", "Default", "Extra");
    echo str_repeat("-", 80) . "\n";
    
    $result = $db->query('DESCRIBE users');
    
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
    
    // Check if there are any users with NIP
    $result = $db->query('SELECT id, username, nama, nip FROM users LIMIT 5');
    if ($result && $result->num_rows > 0) {
        echo "\nSample users data:\n";
        echo str_repeat("-", 60) . "\n";
        echo sprintf("%-5s | %-15s | %-20s | %-15s\n", "ID", "Username", "Nama", "NIP");
        echo str_repeat("-", 60) . "\n";
        while($row = $result->fetch_assoc()) {
            echo sprintf("%-5s | %-15s | %-20s | %-15s\n", 
                $row['id'], 
                $row['username'], 
                substr($row['nama'], 0, 20), 
                $row['nip'] ?? 'NULL');
        }
    }
    
    $db->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
