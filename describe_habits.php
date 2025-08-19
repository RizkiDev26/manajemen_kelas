<?php

$pdo = new PDO('mysql:host=localhost;dbname=sdngu09', 'root', '');
$stmt = $pdo->query('DESCRIBE habits');
echo "Habits table structure:\n";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "{$row['Field']}: {$row['Type']}\n";
}
