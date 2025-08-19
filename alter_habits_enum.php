<?php

$pdo = new PDO('mysql:host=localhost;dbname=sdngu09', 'root', '');

echo "🔧 Updating habits table input_type enum...\n";

// Alter table to add new enum values
$sql = "ALTER TABLE habits MODIFY COLUMN input_type ENUM('boolean','time','number','text','multiple','duration')";
$pdo->exec($sql);
echo "✅ Added 'multiple' and 'duration' to input_type enum\n";

// Now update the input types
$updates = [
    1 => 'time',      // Bangun Pagi
    2 => 'multiple',  // Beribadah
    3 => 'duration',  // Berolahraga  
    4 => 'multiple',  // Makan Sehat
    5 => 'multiple',  // Gemar Belajar
    6 => 'multiple',  // Bermasyarakat
    7 => 'time'       // Tidur Cepat
];

$stmt = $pdo->prepare("UPDATE habits SET input_type = ? WHERE id = ?");

foreach ($updates as $id => $inputType) {
    $stmt->execute([$inputType, $id]);
    echo "✅ Updated habit ID $id to input_type: $inputType\n";
}

echo "\n📊 Final habits configuration:\n";
$stmt = $pdo->query("SELECT id, code, name, input_type FROM habits ORDER BY id");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "- {$row['name']}: {$row['input_type']}\n";
}

echo "\n🎉 Habits table updated successfully!\n";
