<?php

// Update habits input types to match the UI
$host = 'localhost';
$dbname = 'sdngu09';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔧 Updating habits input types...\n";
    
    $updates = [
        'bangun_pagi' => 'time',
        'beribadah' => 'multiple',
        'berolahraga' => 'duration', 
        'makan_sehat' => 'multiple',
        'gemar_belajar' => 'multiple',
        'bermasyarakat' => 'multiple',
        'tidur_cepat' => 'time'
    ];
    
    $stmt = $pdo->prepare("UPDATE habits SET input_type = ? WHERE code = ?");
    
    foreach ($updates as $code => $inputType) {
        $stmt->execute([$inputType, $code]);
        echo "✅ Updated $code to input_type: $inputType\n";
    }
    
    echo "\n📊 Current habits configuration:\n";
    $stmt = $pdo->query("SELECT code, name, input_type FROM habits ORDER BY id");
    $habits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($habits as $habit) {
        echo "- {$habit['name']}: {$habit['input_type']}\n";
    }
    
    echo "\n🎉 Habits input types updated successfully!\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

echo "\nDone!\n";
