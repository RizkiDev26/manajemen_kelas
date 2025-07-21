<?php
// Script untuk copy semua file yang diperlukan ke deployment folder

echo "=== COPY FILES UNTUK DEPLOYMENT ===\n\n";

$deploymentPath = __DIR__ . '/deployment';

// Function untuk copy directory
function copyDirectory($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copyDirectory($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

// Copy app folder (exclude Config yang sudah dibuat)
echo "Copying app folder...\n";
$excludeFiles = ['Database.php', 'App.php']; // Skip config files yang sudah dibuat

copyDirectory('app/Controllers', $deploymentPath . '/app/Controllers');
copyDirectory('app/Models', $deploymentPath . '/app/Models');
copyDirectory('app/Views', $deploymentPath . '/app/Views');
copyDirectory('app/Database', $deploymentPath . '/app/Database');
copyDirectory('app/Helpers', $deploymentPath . '/app/Helpers');
copyDirectory('app/Libraries', $deploymentPath . '/app/Libraries');
copyDirectory('app/Language', $deploymentPath . '/app/Language');

// Copy remaining Config files (skip yang sudah ada)
$configFiles = glob('app/Config/*.php');
foreach ($configFiles as $file) {
    $filename = basename($file);
    if (!in_array($filename, $excludeFiles)) {
        copy($file, $deploymentPath . '/app/Config/' . $filename);
    }
}

// Copy Common.php dan index.html
copy('app/Common.php', $deploymentPath . '/app/Common.php');
copy('app/index.html', $deploymentPath . '/app/index.html');

echo "✓ App folder copied\n";

// Copy vendor folder
echo "Copying vendor folder...\n";
copyDirectory('vendor', $deploymentPath . '/vendor');
echo "✓ Vendor folder copied\n";

// Copy writable folder
echo "Copying writable folder...\n";
copyDirectory('writable', $deploymentPath . '/writable');
echo "✓ Writable folder copied\n";

// Copy composer files
copy('composer.json', $deploymentPath . '/composer.json');
copy('composer.lock', $deploymentPath . '/composer.lock');
echo "✓ Composer files copied\n";

// Copy spark dan preload
copy('spark', $deploymentPath . '/spark');
if (file_exists('preload.php')) {
    copy('preload.php', $deploymentPath . '/preload.php');
}
echo "✓ Additional files copied\n";

// Buat file zip untuk upload mudah
echo "\nCreating ZIP file for easy upload...\n";

$zip = new ZipArchive();
$zipFile = __DIR__ . '/ci4_infinityfree_deployment.zip';

if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    
    // Add htdocs files
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($deploymentPath . '/htdocs'),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    
    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = 'htdocs/' . substr($filePath, strlen($deploymentPath . '/htdocs') + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    
    // Add app files
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($deploymentPath . '/app'),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    
    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = 'app/' . substr($filePath, strlen($deploymentPath . '/app') + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    
    // Add vendor files (selective)
    $vendorDirs = ['autoload.php', 'codeigniter4', 'composer'];
    foreach ($vendorDirs as $dir) {
        if (is_file($deploymentPath . '/vendor/' . $dir)) {
            $zip->addFile($deploymentPath . '/vendor/' . $dir, 'vendor/' . $dir);
        } elseif (is_dir($deploymentPath . '/vendor/' . $dir)) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($deploymentPath . '/vendor/' . $dir),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
            
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = 'vendor/' . $dir . '/' . substr($filePath, strlen($deploymentPath . '/vendor/' . $dir) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }
    }
    
    // Add writable folder structure (without content)
    $zip->addEmptyDir('writable');
    $zip->addEmptyDir('writable/cache');
    $zip->addEmptyDir('writable/logs');
    $zip->addEmptyDir('writable/session');
    $zip->addEmptyDir('writable/uploads');
    
    // Add config files
    $zip->addFile($deploymentPath . '/env', '.env');
    $zip->addFile($deploymentPath . '/composer.json', 'composer.json');
    $zip->addFile($deploymentPath . '/spark', 'spark');
    $zip->addFile($deploymentPath . '/database_export.sql', 'database_export.sql');
    $zip->addFile($deploymentPath . '/INSTALL_GUIDE.txt', 'INSTALL_GUIDE.txt');
    
    $zip->close();
    
    echo "✓ ZIP file created: " . basename($zipFile) . "\n";
    echo "Size: " . number_format(filesize($zipFile) / (1024*1024), 2) . " MB\n";
} else {
    echo "✗ Failed to create ZIP file\n";
}

echo "\n=== DEPLOYMENT FILES READY ===\n";
echo "Files prepared in deployment/ folder:\n";
echo "- htdocs/ (web root files)\n";
echo "- app/ (application files)\n";
echo "- vendor/ (dependencies)\n";
echo "- writable/ (cache, logs, uploads)\n";
echo "- database_export.sql (database backup)\n";
echo "- .env (environment config)\n";
echo "- INSTALL_GUIDE.txt (installation instructions)\n\n";

echo "ZIP file created: ci4_infinityfree_deployment.zip\n";
echo "You can upload this ZIP file to InfinityFree!\n";
?>
