<?php
/**
 * Simple HTTPS Development Server for CodeIgniter 4
 * This script starts a development server that can handle HTTPS requests
 * without requiring SSL certificates for basic testing
 */

$host = 'localhost';
$port = 8443; // Using 8443 instead of 443 to avoid admin privileges
$docroot = __DIR__ . '/public';

echo "Starting CodeIgniter 4 Development Server with HTTPS support...\n";
echo "Server: https://{$host}:{$port}/\n";
echo "Document Root: {$docroot}\n";
echo "Press Ctrl+C to stop the server.\n\n";

// Update the base URL for this port
echo "Note: Update your baseURL in app/Config/App.php to: https://{$host}:{$port}/\n";
echo "Or create a .env file with: app.baseURL = 'https://{$host}:{$port}/'\n\n";

// Start the server
$command = sprintf(
    'php -S %s:%d -t %s',
    $host,
    $port,
    escapeshellarg($docroot)
);

echo "Starting server...\n";
passthru($command);
