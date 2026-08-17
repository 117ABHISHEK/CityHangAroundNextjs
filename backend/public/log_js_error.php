<?php
// public/log_js_error.php
$data = file_get_contents('php://input');
if ($data) {
    $logFile = __DIR__ . '/js_errors.log';
    file_put_contents($logFile, date('Y-m-d H:i:s') . ' - ' . $data . "\n", FILE_APPEND);
}
echo json_encode(['status' => 'ok']);
