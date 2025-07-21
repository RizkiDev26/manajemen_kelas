<?php
// Bootstrap CodeIgniter
define('SYSTEMPATH', __DIR__ . '/vendor/codeigniter4/framework/system/');
define('APPPATH', __DIR__ . '/app/');
define('WRITEPATH', __DIR__ . '/writable/');
define('ROOTPATH', __DIR__ . '/');

require_once SYSTEMPATH . 'bootstrap.php';

// Initialize config
$config = config('App');

// Connect to database
$db = \Config\Database::connect();

echo "=== CHECKING WALIKELAS TABLE STRUCTURE ===\n\n";

// Check if table exists
if ($db->tableExists('walikelas')) {
    echo "✓ Table 'walikelas' exists\n\n";
    
    // Get table structure
    $fields = $db->getFieldData('walikelas');
    
    echo "TABLE STRUCTURE:\n";
    echo str_repeat("-", 50) . "\n";
    
    foreach ($fields as $field) {
        echo sprintf("%-15s | %-15s | %-10s | %s\n", 
            $field->name, 
            $field->type, 
            $field->max_length ?? 'N/A',
            $field->nullable ? 'NULL' : 'NOT NULL'
        );
    }
    
    // Check for user_id field specifically
    $hasUserId = false;
    foreach ($fields as $field) {
        if ($field->name === 'user_id') {
            $hasUserId = true;
            break;
        }
    }
    
    echo "\n" . str_repeat("-", 50) . "\n";
    echo $hasUserId ? "✓ user_id field EXISTS" : "✗ user_id field MISSING";
    echo "\n\n";
    
    // Show sample data
    $query = $db->query("SELECT * FROM walikelas LIMIT 5");
    $results = $query->getResultArray();
    
    echo "SAMPLE DATA:\n";
    echo str_repeat("-", 50) . "\n";
    
    if (!empty($results)) {
        // Print headers
        $headers = array_keys($results[0]);
        echo implode(" | ", array_map(function($h) { return sprintf("%-15s", $h); }, $headers)) . "\n";
        echo str_repeat("-", count($headers) * 18) . "\n";
        
        // Print data
        foreach ($results as $row) {
            echo implode(" | ", array_map(function($v) { 
                return sprintf("%-15s", $v ?? 'NULL'); 
            }, array_values($row))) . "\n";
        }
    } else {
        echo "No data found in walikelas table\n";
    }
    
} else {
    echo "✗ Table 'walikelas' does NOT exist\n";
}

echo "\n=== END STRUCTURE CHECK ===\n";
?>
