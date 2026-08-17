<?php
if (!isset($_GET['key']) || $_GET['key'] !== 'clear123') {
    die('Unauthorized');
}
header('Content-Type: text/plain');

$files = [
    'read_telescope_config.php',
    'test_telescope.php',
    'run_command_on_server.php',
    'delete_temp_files.php',
    'list_temp_files.php'
];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    echo "$file: " . (file_exists($path) ? 'EXISTS' : 'NOT_FOUND') . "\n";
}
