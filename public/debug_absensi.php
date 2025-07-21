<?php

// Simple debug file for absensi
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);

// Load CodeIgniter environment
require_once __DIR__ . '/app/Config/Paths.php';
require_once SYSTEMPATH . 'bootstrap.php';

$app = new \CodeIgniter\CodeIgniter(new \Config\App());
$app->initialize();

// Get database connection
$db = \Config\Database::connect();

echo "<h2>Absensi Debug Test</h2>\n";

try {
    // Test table exists
    $tables = $db->listTables();
    $absensiExists = in_array('absensi', $tables);
    
    echo "<p>Table 'absensi' exists: " . ($absensiExists ? "✓ YES" : "✗ NO") . "</p>\n";
    
    if ($absensiExists) {
        // Show table structure
        $fields = $db->getFieldData('absensi');
        echo "<h3>Table Structure:</h3>\n";
        echo "<ul>\n";
        foreach ($fields as $field) {
            echo "<li>{$field->name} ({$field->type}) - Max Length: {$field->max_length}</li>\n";
        }
        echo "</ul>\n";
        
        // Test insert
        echo "<h3>Testing Insert:</h3>\n";
        
        $testData = [
            'siswa_id' => 999,
            'tanggal' => '2025-07-21',
            'status' => 'hadir',
            'keterangan' => 'Debug test',
            'created_by' => 1
        ];
        
        $builder = $db->table('absensi');
        $result = $builder->insert($testData);
        
        if ($result) {
            echo "<p>✓ Insert successful</p>\n";
            $insertedId = $db->insertID();
            echo "<p>Inserted ID: $insertedId</p>\n";
            
            // Clean up
            $builder->where('id', $insertedId)->delete();
            echo "<p>✓ Test data cleaned up</p>\n";
        } else {
            echo "<p>✗ Insert failed</p>\n";
            echo "<p>Error: " . $db->error()['message'] . "</p>\n";
        }
    }
    
} catch (Exception $e) {
    echo "<p>✗ Exception: " . $e->getMessage() . "</p>\n";
}

echo "<p>Debug test completed.</p>\n";
