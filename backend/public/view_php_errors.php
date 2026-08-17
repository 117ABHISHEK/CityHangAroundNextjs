<?php
if (!isset($_GET['key']) || $_GET['key'] !== 'clear123') {
    die('Unauthorized');
}

$root = dirname(__DIR__);
$logPaths = [
    $root . '/error_log',
    $root . '/public/error_log',
    '/var/log/nginx/error.log',
    '/var/log/php-fpm/error.log'
];

echo "<h2>PHP Server Error Logs</h2>";

foreach ($logPaths as $logPath) {
    if (file_exists($logPath)) {
        echo "<h3>Log file: $logPath</h3>";
        $data = file($logPath);
        $count = count($data);
        $start = max(0, $count - 100);
        echo "<pre style='background: #1e1e1e; color: #d4d4d4; padding: 15px; border-radius: 8px; overflow: auto; max-height: 50vh;'>";
        for ($i = $start; $i < $count; $i++) {
            echo htmlspecialchars($data[$i]);
        }
        echo "</pre>";
    } else {
        echo "<p>Log file not found: $logPath</p>";
    }
}
