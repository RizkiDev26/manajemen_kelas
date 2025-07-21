<?php
// Script untuk cek dan buat struktur database yang diperlukan

echo "=== CHECKING DATABASE STRUCTURE ===\n\n";

// Database config
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sdngu09';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected to database\n\n";
    
    // Check if profil_sekolah table exists
    try {
        $stmt = $pdo->query('DESCRIBE profil_sekolah');
        echo "✓ Table profil_sekolah exists\n";
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "Columns: " . implode(', ', $columns) . "\n\n";
    } catch (Exception $e) {
        echo "✗ Table profil_sekolah does not exist - creating...\n";
        
        $createTable = "
        CREATE TABLE `profil_sekolah` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nama_sekolah` varchar(255) NOT NULL,
            `npsn` varchar(20) DEFAULT NULL,
            `alamat_sekolah` text DEFAULT NULL,
            `kurikulum` varchar(100) DEFAULT NULL,
            `tahun_pelajaran` varchar(20) DEFAULT NULL,
            `nama_kepala_sekolah` varchar(255) DEFAULT NULL,
            `nip_kepala_sekolah` varchar(30) DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $pdo->exec($createTable);
        echo "✓ Table profil_sekolah created\n";
        
        // Insert default data
        $insertDefault = "
        INSERT INTO `profil_sekolah` 
        (`nama_sekolah`, `npsn`, `alamat_sekolah`, `kurikulum`, `tahun_pelajaran`, `nama_kepala_sekolah`, `nip_kepala_sekolah`) 
        VALUES 
        ('SD Negeri Gubeng 09', '20533543', 'Jl. Gubeng Raya No. 09, Surabaya', 'Kurikulum Merdeka', '2024/2025', 'Kepala Sekolah', '196501011986031001');
        ";
        
        $pdo->exec($insertDefault);
        echo "✓ Default school profile data inserted\n\n";
    }
    
    // Check users table structure
    try {
        $stmt = $pdo->query('DESCRIBE users');
        echo "✓ Table users exists\n";
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "Columns: " . implode(', ', $columns) . "\n\n";
    } catch (Exception $e) {
        echo "✗ Table users does not exist\n\n";
    }
    
    echo "=== DATABASE CHECK COMPLETE ===\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
