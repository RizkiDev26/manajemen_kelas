<?php
// Script untuk mempersiapkan deployment ke InfinityFree

echo "=== PERSIAPAN DEPLOYMENT KE INFINITYFREE ===\n\n";

// 1. Buat folder deployment
$deploymentPath = __DIR__ . '/deployment';
if (!is_dir($deploymentPath)) {
    mkdir($deploymentPath, 0755, true);
    echo "✓ Created deployment folder\n";
}

// 2. Buat struktur folder untuk InfinityFree
$folders = [
    'htdocs',
    'htdocs/css',
    'htdocs/js', 
    'htdocs/assets',
    'htdocs/uploads',
    'app',
    'app/Config',
    'app/Controllers',
    'app/Models', 
    'app/Views',
    'app/Database',
    'app/Helpers',
    'app/Libraries',
    'vendor',
    'writable',
    'writable/cache',
    'writable/logs',
    'writable/session',
    'writable/uploads'
];

foreach ($folders as $folder) {
    $fullPath = $deploymentPath . '/' . $folder;
    if (!is_dir($fullPath)) {
        mkdir($fullPath, 0755, true);
    }
}

echo "✓ Created folder structure for InfinityFree\n";

// 3. Copy public files ke htdocs
$publicFiles = [
    'public/index.php' => 'htdocs/index.php',
    'public/.htaccess' => 'htdocs/.htaccess',
    'public/favicon.ico' => 'htdocs/favicon.ico',
    'public/robots.txt' => 'htdocs/robots.txt'
];

foreach ($publicFiles as $source => $dest) {
    if (file_exists($source)) {
        copy($source, $deploymentPath . '/' . $dest);
        echo "✓ Copied {$source} -> {$dest}\n";
    }
}

// 4. Copy CSS folder
if (is_dir('public/css')) {
    copyDir('public/css', $deploymentPath . '/htdocs/css');
    echo "✓ Copied CSS files\n";
}

// 5. Buat file .htaccess untuk InfinityFree
$htaccess = '
RewriteEngine On

# Handle Angular and Vue.js HTML5 mode
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]

# Deny access to system folders
RewriteCond %{REQUEST_URI} ^/app.*
RewriteRule ^(.*)$ /index.php/$1 [L]

RewriteCond %{REQUEST_URI} ^/writable.*
RewriteRule ^(.*)$ /index.php/$1 [L]

# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
</IfModule>

# Disable server signature
ServerSignature Off

# Prevent access to .htaccess
<Files .htaccess>
Order allow,deny
Deny from all
</Files>
';

file_put_contents($deploymentPath . '/htdocs/.htaccess', $htaccess);
echo "✓ Created optimized .htaccess for InfinityFree\n";

echo "\n=== DEPLOYMENT PREPARATION COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Setup InfinityFree account and get database details\n";
echo "2. Run create_production_config.php to generate config files\n";
echo "3. Copy remaining files manually\n";
echo "4. Upload to InfinityFree\n";

// Helper function
function copyDir($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copyDir($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}
?>
