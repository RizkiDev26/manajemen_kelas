<?php

// Database connection based on .env settings
$host = 'localhost';
$dbname = 'sdngu09';   // From .env file
$username = 'root';    // From .env file
$password = '';        // From .env file

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database '$dbname' successfully!\n";
    
    // Check if habits table exists and has data
    $stmt = $pdo->query("SELECT COUNT(*) FROM habits");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        echo "Creating 7 Kebiasaan Anak Indonesia Hebat data...\n";
        
        $habits = [
            ['wake_up', 'Bangun Pagi', 'Memulai hari dengan semangat dan disiplin', 'time'],
            ['worship', 'Beribadah', 'Membentuk pribadi yang memiliki nilai spiritual kuat', 'multiple'],
            ['exercise', 'Berolahraga', 'Mendorong kebugaran fisik dan kesehatan mental', 'duration'],
            ['healthy_food', 'Makan Sehat dan Bergizi', 'Menunjang pertumbuhan dan kecerdasan', 'multiple'],
            ['learning', 'Gemar Belajar', 'Menumbuhkan rasa ingin tahu dan kreativitas', 'multiple'],
            ['social', 'Bermasyarakat', 'Mengajarkan kepedulian dan tanggung jawab sosial', 'multiple'],
            ['sleep', 'Tidur Cepat', 'Memastikan kualitas istirahat yang baik', 'time']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO habits (code, name, description, input_type, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        
        foreach ($habits as $habit) {
            $stmt->execute($habit);
            echo "✅ Created: {$habit[1]}\n";
        }
        
        echo "\n🎉 All 7 habits created successfully!\n";
    } else {
        echo "✅ Habits already exist in database ($count records)\n";
        
        $stmt = $pdo->query("SELECT name FROM habits ORDER BY id");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "- {$row['name']}\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    echo "Please check your database connection settings.\n";
}

echo "\nDone!\n";
