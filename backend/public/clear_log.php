<?php
// Simple utility to clear or truncate the laravel log file for debugging
$logPath = __DIR__ . '/../storage/logs/laravel.log';

if (isset($_GET['key']) && $_GET['key'] === 'clear123') {
    if (file_exists($logPath)) {
        if (file_put_contents($logPath, '') !== false) {
            echo "Laravel log file cleared successfully!";
        } else {
            echo "Failed to clear the log file. Check file permissions.";
        }
    } else {
        echo "Log file does not exist, nothing to clear.";
    }
} else {
    echo "Unauthorized. Access denied.";
}
