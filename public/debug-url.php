<?php
// Simple debug script to check base URL
echo "Current Base URL: " . base_url() . "\n";
echo "Current Site URL: " . site_url() . "\n";
echo "Current URI: " . current_url() . "\n";
echo "Server variables:\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'not set') . "\n";
echo "SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'not set') . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'not set') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'not set') . "\n";
?>
