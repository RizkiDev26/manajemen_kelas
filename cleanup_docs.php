<?php
/**
 * Cleanup Documentation Files
 * Menghapus file dokumentasi yang duplikat/tidak diperlukan
 */

echo "=== Cleanup Documentation Files ===\n\n";

$docsToDelete = [
    'CSS_ERROR_FIX_DOCUMENTATION.md',
    'DATABASE_FIX_COMPLETE.md', 
    'REKAP_ENHANCED_COMPLETE.md',
    'REKAP_ENHANCEMENT_DOCUMENTATION.md',
    'REKAP_FIX_COMPLETE.md',
    'REKAP_IMPLEMENTATION_COMPLETE.md',
    'SYNTAX_ERROR_FIX.md',
    'ABSENSI_FIXES_DOCUMENTATION.md',
    'DATABASE_STRUCTURE.md'
];

$deletedDocs = 0;

foreach ($docsToDelete as $file) {
    $filePath = __DIR__ . '/' . $file;
    
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            echo "✅ Deleted: $file\n";
            $deletedDocs++;
        } else {
            echo "❌ Failed to delete: $file\n";
        }
    } else {
        echo "⚠️  Not found: $file\n";
    }
}

echo "\n=== Dokumentasi yang DIPERTAHANKAN ===\n";
$importantDocs = [
    'FINAL_IMPLEMENTATION_SUMMARY.md' => 'Dokumentasi lengkap final',
    'TAILWIND_REKAP_DOCUMENTATION.md' => 'Dokumentasi implementasi Tailwind CSS',
    'ADMIN_REKAP_ENHANCEMENT_COMPLETE.md' => 'Dokumentasi enhancement admin recap',
    'SUGGESTED_ENHANCEMENTS.md' => 'Saran pengembangan selanjutnya',
    'REFACTOR_FINAL_SUMMARY.md' => 'Summary refactoring',
    'README.md' => 'Dokumentasi utama project (jika ada)'
];

foreach ($importantDocs as $file => $desc) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "✅ $file - $desc\n";
    }
}

echo "\n=== Summary Cleanup Dokumentasi ===\n";
echo "Documentation files deleted: $deletedDocs\n";
echo "Workspace documentation sudah dibersihkan.\n";
echo "Sekarang hanya tersisa dokumentasi yang penting dan tidak duplikat.\n";
