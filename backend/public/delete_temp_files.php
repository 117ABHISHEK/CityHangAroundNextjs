<?php
if (!isset($_GET['key']) || $_GET['key'] !== 'clear123') {
    die('Unauthorized');
}
header('Content-Type: text/plain');

$files = [
    __DIR__ . '/read_telescope_config.php',
    __DIR__ . '/test_telescope.php',
    __DIR__ . '/run_command_on_server.php',
    __DIR__ . '/delete_temp_files.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        if (unlink($file)) {
            echo "Deleted: $file\n";
            if (function_exists('opcache_invalidate')) {
                opcache_invalidate($file, true);
            }
        } else {
            echo "Failed to delete: $file\n";
        }
    } else {
        echo "Not found: $file\n";
    }
}
