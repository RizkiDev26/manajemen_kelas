<?php

// Create absensi table if not exists
$db = \Config\Database::connect();

$sql = "CREATE TABLE IF NOT EXISTS `absensi` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `siswa_id` int(11) NOT NULL,
    `tanggal` date NOT NULL,
    `status` enum('hadir','izin','sakit','alpha') NOT NULL DEFAULT 'hadir',
    `keterangan` text DEFAULT NULL,
    `created_by` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `idx_siswa_tanggal` (`siswa_id`, `tanggal`),
    KEY `idx_tanggal` (`tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $db->query($sql);
    echo "✓ Table 'absensi' created or already exists\n";
} catch (Exception $e) {
    echo "✗ Error creating table: " . $e->getMessage() . "\n";
}
