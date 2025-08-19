<?php
/**
 * HTTPS Development Server for CodeIgniter 4
 * This script starts a development server with SSL support on port 443
 */

// Check if certificates exist
if (!file_exists('certificates/localhost.crt') || !file_exists('certificates/localhost.key')) {
    echo "SSL certificates not found!\n";
    echo "Please run generate-ssl-cert.bat first to create SSL certificates.\n";
    exit(1);
}

// Server configuration
$host = 'localhost';
$port = 443;
$docroot = __DIR__ . '/public';

echo "Starting CodeIgniter 4 HTTPS Development Server...\n";
echo "Server: https://{$host}:{$port}/\n";
echo "Document Root: {$docroot}\n";
echo "Press Ctrl+C to stop the server.\n\n";

// Create SSL context
$context = stream_context_create([
    'ssl' => [
        'local_cert' => 'certificates/localhost.crt',
        'local_pk' => 'certificates/localhost.key',
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ]
]);

// Start the server
$command = sprintf(
    'php -S %s:%d -t %s',
    $host,
    $port,
    escapeshellarg($docroot)
);

echo "Note: Your browser may show a security warning for self-signed certificates.\n";
echo "Click 'Advanced' and 'Proceed to localhost (unsafe)' to continue.\n\n";

// Execute the server command
passthru($command);
