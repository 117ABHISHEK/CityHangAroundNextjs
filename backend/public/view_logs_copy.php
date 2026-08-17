<?php
/**
 * Apply Code Updates and Clean Up
 * Usage: https://test1.cityhangaround.com/code_updates/public/apply_updates.php?key=clear123
 * Or: https://test1.cityhangaround.com/apply_updates.php?key=clear123
 */

if (!isset($_GET['key']) || $_GET['key'] !== 'clear123') {
    header('HTTP/1.0 403 Forbidden');
    echo 'Unauthorized';
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (function_exists('opcache_reset')) {
    @opcache_reset();
}

// Auto-detect root path and source path
$currentDir = __DIR__; // e.g. /htdocs/test1.cityhangaround.com/code_updates/public OR /htdocs/test1.cityhangaround.com/public
$parentDir = dirname($currentDir); // e.g. /htdocs/test1.cityhangaround.com/code_updates OR /htdocs/test1.cityhangaround.com

if (basename($parentDir) === 'code_updates') {
    // Accessed via /code_updates/public/apply_updates.php
    $rootPath = dirname($parentDir); // /htdocs/test1.cityhangaround.com
    $sourceDir = $parentDir;         // /htdocs/test1.cityhangaround.com/code_updates
} else {
    // Accessed via /public/apply_updates.php or /apply_updates.php
    $rootPath = $parentDir;          // /htdocs/test1.cityhangaround.com
    $sourceDir = $rootPath . '/code_updates';
}

echo "<html><head><title>Apply Updates</title>";
echo "<style>body{font-family:sans-serif;background:#1a1a1a;color:#eee;padding:20px;}h2{color:#ff4d4d;}pre{background:#333;padding:15px;border-radius:5px;overflow-x:auto;}</style>";
echo "</head><body>";
echo "<h2>Applying Code Updates...</h2>";
echo "<pre>";
echo "Detected Website Root Path: $rootPath\n";
echo "Detected Updates Source Path: $sourceDir\n\n";

// Auto-extract code_updates.zip if present in root or public folder
$zipPath = $rootPath . '/code_updates.zip';
if (!file_exists($zipPath)) {
    $zipPath = $currentDir . '/code_updates.zip';
}

if (file_exists($zipPath)) {
    echo "Found ZIP file at: $zipPath (" . filesize($zipPath) . " bytes)\n";
    echo "Extracting to: $sourceDir...\n";
    if (!is_dir($sourceDir)) {
        mkdir($sourceDir, 0755, true);
    }
    $zip = new ZipArchive;
    if ($zip->open($zipPath) === TRUE) {
        $zip->extractTo($sourceDir);
        $zip->close();
        echo "SUCCESS: Extracted code_updates.zip to code_updates folder.\n";
        unlink($zipPath);
        echo "SUCCESS: Deleted code_updates.zip.\n\n";
    } else {
        echo "ERROR: Failed to open ZIP archive '$zipPath'.\n\n";
    }
}

if (!is_dir($sourceDir)) {
    echo "ERROR: Updates directory '$sourceDir' not found.\n";
    echo "Please ensure the ZIP file is extracted, creating the 'code_updates' folder.\n";
    echo "</pre></body></html>";
    exit;
}

$filesToMove = [
    'app/Helpers/CommonHelper.php',
    'app/Providers/AppServiceProvider.php',
    'app/Http/Controllers/Report/SearchController.php',
    'app/Http/Controllers/MenuDemoController.php',
    'app/Http/Controllers/DevToolsController.php',
    'app/Http/Controllers/AdminCrudController.php',
    'app/Http/Controllers/MarketplaceController.php',
    'app/Http/Middleware/UserMiddleware.php',
    'resources/views/backend/admin/users/list.blade.php',
    'resources/views/backend/admin/page/list.blade.php',
    'resources/views/backend/admin/page/pending_page.blade.php',
    'resources/views/backend/admin/page/edit.blade.php',
    'resources/views/backend/admin/product/list.blade.php',
    'resources/views/frontend/pages/create_page.blade.php',
    'resources/views/frontend/pages/draft/create_incomplete_page.blade.php',
    'resources/views/frontend/layouts/app.blade.php',
    'resources/views/frontend/header.blade.php',
    'resources/views/frontend/main_content/post_reacts.blade.php',
    'resources/views/frontend/main_content/posts.blade.php',
    'resources/views/frontend/main_content/scripts.blade.php',
    'resources/views/frontend/partials/global-scripts.blade.php',
    'resources/views/auth/login.blade.php',
    'public/extract_storage_assets.php',
    'public/diagnose_storage.php',
    'public/inspect_categories.php',
    'public/view_logs.php',
    'resources/views/frontend/marketplace/productcategorycity.blade.php',
    'resources/views/frontend/marketplace/productcategorycityarea.blade.php',
    'routes/web.php'
];

// Include the apply_updates.php script itself if we are running from root path
if (basename($parentDir) !== 'code_updates') {
    $filesToMove[] = 'public/apply_updates.php';
}

$successCount = 0;
foreach ($filesToMove as $relPath) {
    $srcFile = $sourceDir . '/' . $relPath;
    $dstFile = $rootPath . '/' . $relPath;

    // Skip self if we are copying the running file and it's already there
    if ($srcFile === __FILE__) {
        continue;
    }

    if (!file_exists($srcFile)) {
        echo "WARNING: Source file '$relPath' not found in code_updates.\n";
        continue;
    }

    $dstDir = dirname($dstFile);
    if (!is_dir($dstDir)) {
        if (!mkdir($dstDir, 0755, true)) {
            echo "ERROR: Failed to create directory '$dstDir'.\n";
            continue;
        }
    }

    if (copy($srcFile, $dstFile)) {
        chmod($dstFile, 0644);
        echo "SUCCESS: Copied '$relPath' to correct location.\n";
        $successCount++;
    } else {
        echo "ERROR: Failed to copy '$relPath'.\n";
    }
}

echo "\nCopied $successCount files.\n";

// Bootstrap Laravel to clear cache
echo "\nBootstrapping Laravel to clear cache...\n";
try {
    require $rootPath . '/vendor/autoload.php';
    $app = require_once $rootPath . '/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "Clearing application cache...\n";
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    echo Artisan::output();

    echo "Clearing view cache...\n";
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo Artisan::output();

    echo "Clearing config cache...\n";
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    echo Artisan::output();

    echo "Laravel cache successfully cleared.\n";
} catch (\Throwable $e) {
    echo "WARNING: Laravel cache clearing failed: " . $e->getMessage() . "\n";
}

// Clean up code_updates directory
echo "\nCleaning up temporary '$sourceDir' directory...\n";
function deleteDirectory($dir, $avoidFile = '') {
    if (!file_exists($dir)) {
        return true;
    }
    if (!is_dir($dir)) {
        if ($dir === $avoidFile) {
            return true; // Don't delete our running file yet
        }
        return unlink($dir);
    }
    $success = true;
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        $path = $dir . DIRECTORY_DIR_SEPARATOR . $item;
        if (!deleteDirectory($path, $avoidFile)) {
            $success = false;
        }
    }
    if ($dir !== dirname($avoidFile) && $dir !== dirname(dirname($avoidFile))) {
        // Only rmdir if it's not the directory of the running file
        return rmdir($dir);
    }
    return $success;
}

if (!defined('DIRECTORY_DIR_SEPARATOR')) {
    define('DIRECTORY_DIR_SEPARATOR', '/');
}

// Pass __FILE__ to avoid unlinking ourselves while executing
if (deleteDirectory($sourceDir, __FILE__)) {
    echo "SUCCESS: Temporary directory 'code_updates' cleaned up.\n";
} else {
    echo "WARNING: Partial cleanup of temporary directory 'code_updates'. You may delete it manually.\n";
}

echo "\nUpdates applied successfully!\n";
echo "You can now check the website or run the inspector:\n";
echo "<a href='/inspect_categories.php?key=clear123&city=surat' style='color:#ff4d4d;'>Run Category Inspector for Surat</a>\n";
echo "</pre></body></html>";
