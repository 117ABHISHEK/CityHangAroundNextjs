<?php
/**
 * Storage Assets Extraction and Auto-Fix Tool
 * Usage: https://test1.cityhangaround.com/extract_storage_assets.php?key=clear123
 */

if (!isset($_GET['key']) || $_GET['key'] !== 'clear123') {
    header('HTTP/1.0 403 Forbidden');
    echo 'Unauthorized';
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(300);

$root = dirname(__DIR__);
$publicPath = $root . '/public';
$zipName = 'storage_assets.zip';
$zipPaths = [
    $root . '/' . $zipName,
    $publicPath . '/' . $zipName
];

echo '<h1>Storage Assets Extraction & Auto-Fix Tool</h1>';
echo '<pre>';

// 1. Locate Zip File
$zipFile = null;
foreach ($zipPaths as $path) {
    if (file_exists($path)) {
        $zipFile = $path;
        break;
    }
}

if (!$zipFile) {
    echo "ERROR: $zipName NOT found in root or public directory.\n";
    echo "Please upload $zipName to either:\n";
    echo " - Root directory: $root/\n";
    echo " - Public directory: $publicPath/\n";
    exit;
}

echo "Found ZIP file at: $zipFile (" . filesize($zipFile) . " bytes)\n";

// 2. Extract ZIP
echo "Extracting ZIP...\n";
$zip = new ZipArchive;
if ($zip->open($zipFile) === TRUE) {
    $zip->extractTo($root);
    $zip->close();
    echo "Extraction completed successfully!\n";
} else {
    echo "ERROR: Failed to open ZIP archive.\n";
    exit;
}

// 3. Recreate Storage Symbolic Link
$symlinkPath = $publicPath . '/storage';
$targetPath = $root . '/storage/app/public';

echo "\nRe-linking storage directory...\n";
echo "Target path: $targetPath\n";
echo "Link path: $symlinkPath\n";

if (file_exists($symlinkPath) || is_link($symlinkPath)) {
    if (is_link($symlinkPath)) {
        echo "Deleting existing symlink...\n";
        if (unlink($symlinkPath)) {
            echo "Deleted symlink.\n";
        } else {
            echo "ERROR: Could not delete symlink.\n";
        }
    } else if (is_dir($symlinkPath)) {
        echo "Existing path is a real directory! Renaming it to storage_backup_dir...\n";
        $backupPath = $publicPath . '/storage_backup_' . time();
        if (rename($symlinkPath, $backupPath)) {
            echo "Renamed directory to: " . basename($backupPath) . "\n";
        } else {
            echo "ERROR: Could not rename directory.\n";
        }
    }
}

// Create symlink
if (symlink($targetPath, $symlinkPath)) {
    echo "Symbolic link created successfully!\n";
} else {
    echo "ERROR: Failed to create symbolic link.\n";
    // Let's try executing native command via exec if symlink() failed
    echo "Attempting to create symlink via shell command...\n";
    $output = [];
    $retval = -1;
    exec("ln -s " . escapeshellarg($targetPath) . " " . escapeshellarg($symlinkPath), $output, $retval);
    if ($retval === 0) {
        echo "Symbolic link created via shell command successfully!\n";
    } else {
        echo "ERROR: Shell command failed too. Return code: $retval\n";
    }
}

// Verify symlink target
if (is_link($symlinkPath)) {
    $realTarget = readlink($symlinkPath);
    echo "Verified link target: $realTarget\n";
} else {
    echo "WARNING: Symbolic link verification failed.\n";
}

// 4. Run Laravel Optimizations
echo "\nRunning Laravel cache optimization...\n";
try {
    // Boostrap Laravel to run commands
    require $root . '/vendor/autoload.php';
    $app = require_once $root . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    // Clear and rebuild cache
    $status = $kernel->call('optimize:clear');
    echo "Laravel optimize:clear: " . ($status === 0 ? 'SUCCESS' : 'FAILED') . "\n";
    
    $status = $kernel->call('storage:link');
    echo "Laravel storage:link: " . ($status === 0 ? 'SUCCESS' : 'FAILED') . "\n";
    
} catch (\Exception $e) {
    echo "WARNING: Could not bootstrap Laravel for commands: " . $e->getMessage() . "\n";
}

// 5. Cleanup
echo "\nCleaning up zip file...\n";
if (unlink($zipFile)) {
    echo "Deleted $zipName file to save disk space.\n";
} else {
    echo "WARNING: Could not delete $zipFile.\n";
}

echo "\n<h3>All steps completed! Check if your images and reactions are loading now.</h3>";
echo '</pre>';
