<?php
/**
 * Check Database Structure
 */

// Database configuration
$host = 'localhost';
$database = 'sdngu09';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Database Structure Check ===\n\n";
    
    // Show all tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in database '$database':\n";
    
    if (empty($tables)) {
        echo "No tables found.\n";
    } else {
        foreach ($tables as $table) {
            echo "- $table\n";
        }
        
        // Show structure of each table
        echo "\n=== Table Structures ===\n";
        foreach ($tables as $table) {
            echo "\nTable: $table\n";
            $columns = $pdo->query("DESCRIBE $table")->fetchAll();
            foreach ($columns as $column) {
                echo "  - {$column['Field']}: {$column['Type']}\n";
            }
        }
    }
    
    echo "\n=== Check Complete ===\n";
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
