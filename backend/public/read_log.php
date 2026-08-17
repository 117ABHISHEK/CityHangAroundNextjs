<?php
// Simple tail of laravel.log
$logPath = __DIR__ . '/../storage/logs/laravel.log';
if (!file_exists($logPath)) {
    echo "Log not found at: " . realpath($logPath);
    exit;
}

$size = filesize($logPath);
$readBytes = min(150000, $size); // Read last 150KB

$fp = fopen($logPath, 'r');
fseek($fp, -$readBytes, SEEK_END);
$data = fread($fp, $readBytes);
fclose($fp);

echo "<html><body style='font-family:monospace;background:#111;color:#eee;padding:20px;'>";
echo "<h2>laravel.log tail (last 150KB)</h2>";
echo "<pre style='white-space: pre-wrap; word-break: break-all;'>" . htmlspecialchars($data) . "</pre>";
echo "</body></html>";
