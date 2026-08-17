<?php
/**
 * Diagnostic tool for Cityhangaround Storage & Image Paths
 * Usage: https://test1.cityhangaround.com/diagnose_storage.php?key=clear123
 */

if (!isset($_GET['key']) || $_GET['key'] !== 'clear123') {
    header('HTTP/1.0 403 Forbidden');
    echo 'Unauthorized';
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$root = dirname(__DIR__);
$publicPath = $root . '/public';
$storagePath = $root . '/storage';
$appPublicStorage = $root . '/storage/app/public';
$symlinkPath = $publicPath . '/storage';

echo '<h1>Storage & Image Diagnostic Tool</h1>';
echo '<pre>';

// 1. Check Env
echo "<h3>--- Env Settings ---</h3>";
if (file_exists($root . '/.env')) {
    $env = file_get_contents($root . '/.env');
    preg_match('/APP_URL=(.+)/', $env, $m1);
    preg_match('/FILESYSTEM_DRIVER=(.+)/', $env, $m2);
    preg_match('/FILESYSTEM_DISK=(.+)/', $env, $m3);
    echo "APP_URL in .env: " . (trim($m1[1] ?? 'NOT FOUND')) . "\n";
    echo "FILESYSTEM_DRIVER: " . (trim($m2[1] ?? 'NOT FOUND')) . "\n";
    echo "FILESYSTEM_DISK: " . (trim($m3[1] ?? 'NOT FOUND')) . "\n";
} else {
    echo ".env file NOT found in $root\n";
}

// 2. Check Directory Structure
echo "\n<h3>--- Directory Paths & Existence ---</h3>";
echo "Root Path: $root (" . (is_dir($root) ? 'Exists' : 'DOES NOT EXIST') . ")\n";
echo "Public Path: $publicPath (" . (is_dir($publicPath) ? 'Exists' : 'DOES NOT EXIST') . ")\n";
echo "Storage Path: $storagePath (" . (is_dir($storagePath) ? 'Exists' : 'DOES NOT EXIST') . ")\n";
echo "Storage App Public Path: $appPublicStorage (" . (is_dir($appPublicStorage) ? 'Exists' : 'DOES NOT EXIST') . ")\n";

// 3. Symlink Status
echo "\n<h3>--- Symlink Status ---</h3>";
echo "Symlink Path: $symlinkPath\n";
if (file_exists($symlinkPath)) {
    echo "Symlink Path exists: YES\n";
} else {
    echo "Symlink Path exists: NO (Or is a broken symbolic link)\n";
}

if (is_link($symlinkPath)) {
    echo "Is symbolic link: YES\n";
    $target = readlink($symlinkPath);
    echo "Symlink points to: $target\n";
    if (file_exists($target)) {
        echo "Target exists: YES\n";
    } else {
        echo "Target exists: NO (BROKEN LINK!)\n";
    }
} else {
    echo "Is symbolic link: NO\n";
    if (is_dir($symlinkPath)) {
        echo "It is a regular directory!\n";
    }
}

// 4. Default Placeholder File Checks
echo "\n<h3>--- Placeholder File Existence Check ---</h3>";
$placeholders = [
    'userimage/default.png',
    'pages/default.png',
    'pages/default.jpg',
    'pages/logo/default.png',
    'pages/logo/default.jpg',
    'marketplace/thumbnail/default/default.png',
    'marketplace/thumbnail/default/default.jpg',
    'logo/dark/default/default.jpg',
    'logo/light/default/default.jpg',
    'cover_photo/default.jpg',
];

foreach ($placeholders as $relPath) {
    $fullPath = $appPublicStorage . '/' . $relPath;
    echo "File [$relPath]: " . (file_exists($fullPath) ? 'FOUND' : 'MISSING') . " (Size: " . (file_exists($fullPath) ? filesize($fullPath) . ' bytes' : 'N/A') . ")\n";
}

// 5. Check actual userimage folder contents
echo "\n<h3>--- Folder Contents Sample ---</h3>";
foreach (['userimage', 'pages/logo', 'marketplace/thumbnail'] as $subFolder) {
    $dir = $appPublicStorage . '/' . $subFolder;
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        echo "Folder [$subFolder] contains " . count($files) . " files/folders.\n";
        if (count($files) > 0) {
            echo "Sample files:\n";
            $count = 0;
            foreach ($files as $file) {
                if ($count >= 5) break;
                echo "  - " . basename($file) . " (" . (is_dir($file) ? 'dir' : filesize($file) . ' bytes') . ")\n";
                $count++;
            }
        }
    } else {
        echo "Folder [$subFolder] does not exist or is not a directory.\n";
    }
}

echo '</pre>';
