<?php
/**
 * Ultimate Direct Apply Updates Script
 * Usage: https://test1.cityhangaround.com/apply.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (function_exists('opcache_reset')) {
    @opcache_reset();
}

$currentDir = __DIR__; // e.g. /htdocs/test1.cityhangaround.com/public
$rootPath = dirname($currentDir); // e.g. /htdocs/test1.cityhangaround.com

echo "<html><head><title>Direct Apply Updates</title>";
echo "<style>body{font-family:sans-serif;background:#1a1a1a;color:#eee;padding:20px;}h2{color:#ff4d4d;}pre{background:#333;padding:15px;border-radius:5px;overflow-x:auto;}</style>";
echo "</head><body>";
echo "<h2>Applying Code Updates...</h2>";
echo "<pre>";

// Diagnostic list of what exists
echo "Diagnostic Scan:\n";
$zipLocations = [
    $currentDir . '/code_updates.zip',
    $rootPath . '/code_updates.zip'
];
$folderLocations = [
    $currentDir . '/code_updates',
    $rootPath . '/code_updates'
];

$foundZip = null;
foreach ($zipLocations as $zipLoc) {
    if (file_exists($zipLoc)) {
        echo " - Found ZIP file: " . htmlspecialchars($zipLoc) . " (" . filesize($zipLoc) . " bytes)\n";
        $foundZip = $zipLoc;
    }
}

$foundFolder = null;
foreach ($folderLocations as $folderLoc) {
    if (is_dir($folderLoc)) {
        echo " - Found Folder: " . htmlspecialchars($folderLoc) . "\n";
        $foundFolder = $folderLoc;
    }
}

if (!$foundZip && !$foundFolder) {
    echo "\nERROR: No ZIP file (code_updates.zip) or Folder (code_updates) found in public or root directories.\n";
    echo "\nListing public directory files:\n";
    foreach (scandir($currentDir) as $f) {
        if ($f !== '.' && $f !== '..') echo "  - $f\n";
    }
    echo "\nListing root directory files:\n";
    foreach (scandir($rootPath) as $f) {
        if ($f !== '.' && $f !== '..') echo "  - $f\n";
    }
    echo "</pre></body></html>";
    exit;
}

$extracted = false;

// 1. Handle Zip extraction if found
if ($foundZip) {
    echo "\nExtracting ZIP file directly to root directory...\n";
    $zip = new ZipArchive;
    if ($zip->open($foundZip) === TRUE) {
        if ($zip->extractTo($rootPath)) {
            echo "SUCCESS: Extracted all ZIP contents directly to website root!\n";
            $extracted = true;
        } else {
            echo "ERROR: Failed to extract ZIP contents.\n";
        }
        $zip->close();
        unlink($foundZip);
        echo "Deleted ZIP file: " . htmlspecialchars($foundZip) . "\n";
    } else {
        echo "ERROR: Failed to open ZIP archive.\n";
    }
}

// 2. Handle Folder copy if found (and zip wasn't just extracted)
if ($foundFolder && !$extracted) {
    echo "\nCopying files recursively from folder to root...\n";
    $copyCount = 0;

    function copyRecursive($src, $dst, &$copyCount, $rootPath) {
        if (is_dir($src)) {
            if (!is_dir($dst)) {
                mkdir($dst, 0755, true);
            }
            foreach (scandir($src) as $file) {
                if ($file === '.' || $file === '..') continue;
                copyRecursive("$src/$file", "$dst/$file", $copyCount, $rootPath);
            }
        } else if (file_exists($src)) {
            if (basename($src) === 'apply.php' && dirname($src) === __DIR__) {
                return;
            }
            if (copy($src, $dst)) {
                chmod($dst, 0644);
                if (function_exists('opcache_invalidate')) {
                    @opcache_invalidate($dst, true);
                }
                $relPath = substr($dst, strlen($rootPath) + 1);
                echo "SUCCESS: Copied '" . htmlspecialchars($relPath) . "'\n";
                $copyCount++;
            } else {
                echo "ERROR: Failed to copy to " . htmlspecialchars($dst) . "\n";
            }
        }
    }

    copyRecursive($foundFolder, $rootPath, $copyCount, $rootPath);
    echo "\nTotal files copied: $copyCount\n";
    
    // Clean up temporary code_updates folder
    function deleteDirectory($dir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!deleteDirectory($dir . '/' . $item)) return false;
        }
        return rmdir($dir);
    }
    
    if (deleteDirectory($foundFolder)) {
        echo "SUCCESS: Cleaned up temporary folder " . htmlspecialchars($foundFolder) . "\n";
    }
}

// Clear Laravel cache
echo "\nBootstrapping Laravel to clear cache...\n";
try {
    require $rootPath . '/vendor/autoload.php';
    $app = require_once $rootPath . '/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "Clearing application cache...\n";
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    echo \Illuminate\Support\Facades\Artisan::output();

    echo "Clearing view cache...\n";
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo \Illuminate\Support\Facades\Artisan::output();

    echo "Clearing config cache...\n";
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    echo \Illuminate\Support\Facades\Artisan::output();

    echo "Laravel cache successfully cleared.\n";
} catch (\Throwable $e) {
    echo "WARNING: Laravel cache clearing failed: " . $e->getMessage() . "\n";
}

// Self delete this script
@unlink(__FILE__);
echo "\nSUCCESS: Cleanup complete!\n";
echo "</pre></body></html>";
