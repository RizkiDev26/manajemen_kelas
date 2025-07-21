<?php
/**
 * Cleanup Test Files - Script untuk menghapus file test yang sudah tidak diperlukan
 * 
 * File ini akan menghapus file-file test dan debug yang sudah tidak diperlukan
 * setelah implementasi fitur selesai dan berfungsi dengan baik.
 */

echo "=== Cleanup Test Files ===\n\n";

// Daftar file yang AMAN untuk dihapus (sudah tidak diperlukan)
$filesToDelete = [
    // Test files yang sudah tidak diperlukan
    'test_ajax_endpoint.php',
    'test_ajax_input.html',
    'test_button_fix.html',
    'test_data_check.php',
    'test_db_simple_mysqli.php',
    'test_db_tables.php',
    'test_holiday_integration.php', // versi lama
    'test_holiday_integration_fixed.php',
    'test_pagination_fix.php',
    'test_pagination_simple.php',
    'test_patterns.php',
    'test_rekap_data.php',
    'test_rekap_fixed.php',
    'test_rekap_simple.php',
    'test_rekap_syntax_fix.php',
    'test_siswa.php',
    'test_tombol_sakit.html',
    'test_ui_preview.php',
    'test_ui_updated.php',
    'test_updated_logic.php',
    
    // Debug files yang sudah tidak diperlukan
    'debug_attendance_data.php',
    'debug_naik_kelas.php',
    'debug_page_access.php',
    
    // Check files yang sudah tidak diperlukan
    'check_current_status.php',
    'check_db.php',
    'check_db_simple.php',
    'check_db_structure.php',
    'check_siswa_data.php',
    'check_table_structure.php',
    'check_users_simple.php',
    
    // Temporary fix files
    'fix_class_names.php',
    'restore_original_classes.php',
    'restore_test_data.php',
    'update_kelas_format.php',
    'verify_class_update.php',
    
    // Other temporary files
    'analyze_class_changes.php',
    'prepare_test_data.php',
    'seed_data.php',
    'test_absensi_data.php',
    'test_absensi_fixes.php',
    'test_absensi_model.php',
    'test_controller_logic.php',
    'test_kalender.php',
    'test_login_access.php',
    'test_role_access.php',
    
    // Test HTML files yang sudah tidak diperlukan
    'test_rekap_enhanced.html',
    'preview_updated_ui.html'
];

// Daftar file yang HARUS DIPERTAHANKAN (penting untuk maintenance)
$filesToKeep = [
    'add_sample_holidays_direct.php',  // Untuk testing akademik calendar
    'add_sample_students_attendance.php', // Untuk testing data
    'check_database_structure.php',    // Untuk maintenance database
    'add_complete_holidays.php',       // Untuk data akademik
    'test_holiday_integration.php'     // Yang terbaru untuk testing integrasi
];

echo "Files yang akan dihapus:\n";
$deletedCount = 0;
$skippedCount = 0;

foreach ($filesToDelete as $file) {
    $filePath = __DIR__ . '/' . $file;
    
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            echo "✅ Deleted: $file\n";
            $deletedCount++;
        } else {
            echo "❌ Failed to delete: $file\n";
        }
    } else {
        echo "⚠️  Not found: $file\n";
        $skippedCount++;
    }
}

echo "\n=== Files yang DIPERTAHANKAN (penting untuk maintenance) ===\n";
foreach ($filesToKeep as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "✅ Kept: $file\n";
    }
}

echo "\n=== Dokumentasi markdown yang bisa dihapus ===\n";
$mdFilesToDelete = [
    'CSS_ERROR_FIX_DOCUMENTATION.md',
    'DATABASE_FIX_COMPLETE.md',
    'REKAP_ENHANCED_COMPLETE.md',
    'REKAP_ENHANCEMENT_DOCUMENTATION.md',
    'REKAP_FIX_COMPLETE.md',
    'REKAP_IMPLEMENTATION_COMPLETE.md',
    'SYNTAX_ERROR_FIX.md',
    'ABSENSI_FIXES_DOCUMENTATION.md'
];

echo "Dokumentasi markdown yang bisa dihapus:\n";
foreach ($mdFilesToDelete as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "📄 $file (dapat dihapus - informasi sudah ada di FINAL_IMPLEMENTATION_SUMMARY.md)\n";
    }
}

echo "\n=== Summary ===\n";
echo "Files deleted: $deletedCount\n";
echo "Files not found: $skippedCount\n";
echo "Files kept for maintenance: " . count($filesToKeep) . "\n";

echo "\n=== File-file yang HARUS TETAP ADA ===\n";
$importantFiles = [
    'add_sample_holidays_direct.php' => 'Untuk menambah data kalender akademik',
    'add_sample_students_attendance.php' => 'Untuk menambah data siswa dan absensi',
    'check_database_structure.php' => 'Untuk mengecek struktur database',
    'FINAL_IMPLEMENTATION_SUMMARY.md' => 'Dokumentasi final lengkap',
    'TAILWIND_REKAP_DOCUMENTATION.md' => 'Dokumentasi implementasi Tailwind',
    'ADMIN_REKAP_ENHANCEMENT_COMPLETE.md' => 'Dokumentasi enhancement admin'
];

foreach ($importantFiles as $file => $purpose) {
    echo "✅ $file - $purpose\n";
}

echo "\n=== Cleanup Complete ===\n";
echo "Workspace sudah dibersihkan dari file test yang tidak diperlukan.\n";
echo "VS Code sekarang akan lebih ringan karena file yang diindex berkurang.\n";
