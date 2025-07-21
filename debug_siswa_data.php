<?php

// Initialize CodeIgniter
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);

require_once 'vendor/autoload.php';

// Load CI environment
$pathsPath = FCPATH . '../app/Config/Paths.php';
$paths = require realpath($pathsPath) ?: $pathsPath;

$bootstrap = require $paths->systemDirectory . '/bootstrap.php';

$app = $bootstrap(\Config\App::class);

use App\Models\TbSiswaModel;
use App\Models\NilaiModel;

// Debug siswa data
$siswaModel = new TbSiswaModel();
$nilaiModel = new NilaiModel();

echo "=== DEBUG DATA SISWA ===\n\n";

// Check if tb_siswa table has data
$allSiswa = $siswaModel->findAll();
echo "Total siswa di database: " . count($allSiswa) . "\n\n";

if (count($allSiswa) > 0) {
    echo "Sample data siswa:\n";
    foreach (array_slice($allSiswa, 0, 3) as $siswa) {
        echo "- ID: {$siswa['id']}, Nama: {$siswa['nama']}, Kelas: {$siswa['kelas']}\n";
    }
    echo "\n";
}

// Check active classes
$activeClasses = $siswaModel->getActiveClasses();
echo "Kelas aktif: " . count($activeClasses) . "\n";
foreach ($activeClasses as $kelas) {
    echo "- " . $kelas['kelas'] . "\n";
}
echo "\n";

// Test getNilaiRekap for first available class
if (count($activeClasses) > 0) {
    $testKelas = $activeClasses[0]['kelas'];
    echo "Testing getNilaiRekap untuk kelas: $testKelas\n";
    
    $nilaiRekap = $nilaiModel->getNilaiRekap($testKelas, 'IPAS');
    echo "Hasil getNilaiRekap: " . count($nilaiRekap) . " records\n\n";
    
    if (count($nilaiRekap) > 0) {
        echo "Sample data nilai rekap:\n";
        foreach (array_slice($nilaiRekap, 0, 3) as $siswa) {
            echo "- ID: {$siswa['id']}, Nama: " . ($siswa['nama'] ?? 'NULL') . ", NIPD: " . ($siswa['nipd'] ?? 'NULL') . "\n";
        }
    } else {
        echo "Tidak ada data nilai rekap ditemukan.\n";
    }
}

echo "\n=== END DEBUG ===\n";
