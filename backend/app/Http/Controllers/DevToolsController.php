<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cache;

class DevToolsController extends Controller
{
    /**
     * Apply auth middleware.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the developer tools dashboard.
     */
    public function index()
    {
        $storagePath = storage_path();
        $logPath = storage_path('logs');
        $cachePath = storage_path('framework/cache/data');
        $sessionPath = storage_path('framework/sessions');
        $viewCachePath = storage_path('framework/views');

        $folders = [
            'storage' => $storagePath,
            'logs' => $logPath,
            'cache' => $cachePath,
            'sessions' => $sessionPath,
            'views' => $viewCachePath,
        ];

        $sizes = Cache::remember('dev_tools_folder_sizes', now()->addHours(12), function () use ($folders) {
            $sizes = [];
            foreach ($folders as $key => $path) {
                $size = $this->getFolderSize($path);
                $sizes[$key] = $this->humanFileSize($size);
            }
            return $sizes;
        });

        // Detailed statistics
        $logFilesRaw = File::exists($logPath) ? File::files($logPath) : [];
        $logFilesCount = count($logFilesRaw);

        // Log files information
        $logFiles = collect($logFilesRaw)
            ->map(function ($file) {
                return [
                    'name' => $file->getFilename(),
                    'size' => $this->humanFileSize($file->getSize()),
                    'modified' => $file->getMTime(),
                ];
            });

        $cacheStatus = (File::exists($cachePath) && count(File::files($cachePath)) > 0) ? 'Enabled' : 'Empty';

        // Fetch last cleanup date/time
        $lastCleanupRow = DB::table('settings')->where('type', 'last_cleanup_time')->first();
        $lastCleanup = $lastCleanupRow ? \Carbon\Carbon::parse($lastCleanupRow->description)->format('Y-m-d H:i:s') : 'Never';

        $page_data = [
            'sizes' => $sizes,
            'logFiles' => $logFiles,
            'logFilesCount' => $logFilesCount,
            'cacheStatus' => $cacheStatus,
            'lastCleanup' => $lastCleanup,
            'view_path' => 'dev-tools',
        ];

        return view('backend.index', $page_data);
    }

    /**
     * Handle cache and storage clearing actions.
     */
    public function clear(Request $request)
    {
        $action = $request->input('action');

        switch ($action) {
            case 'app':
                Artisan::call('cache:clear');
                break;
            case 'config':
                Artisan::call('config:clear');
                break;
            case 'route':
                Artisan::call('route:clear');
                break;
            case 'view':
                Artisan::call('view:clear');
                break;
            case 'optimize':
                Artisan::call('optimize:clear');
                break;
            case 'session':
                $this->clearFolder(storage_path('framework/sessions'));
                break;
            case 'logs':
                $this->clearFolder(storage_path('logs'));
                break;
            case 'temp':
                $this->clearFolder(storage_path('framework/cache/data'));
                break;
            case 'all':
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('route:clear');
                Artisan::call('view:clear');
                Artisan::call('optimize:clear');
                $this->clearFolder(storage_path('framework/sessions'));
                $this->clearFolder(storage_path('logs'));
                $this->clearFolder(storage_path('framework/cache/data'));
                break;
            default:
                flash()->addError('Invalid action.');
                return redirect()->back();
        }

        $this->updateLastCleanupTime();
        Cache::forget('dev_tools_folder_sizes');
        flash()->addSuccess(ucfirst($action) . ' cleared successfully!');
        return redirect()->back();
    }

    /**
     * Download a specific log file.
     */
    public function downloadLog($filename)
    {
        $path = storage_path('logs/' . $filename);
        if (!File::exists($path)) {
            abort(404);
        }
        return Response::download($path);
    }

    /**
     * Delete a specific log file.
     */
    public function deleteLog($filename)
    {
        $path = storage_path('logs/' . $filename);
        if (File::exists($path)) {
            File::delete($path);
        }
        $this->updateLastCleanupTime();
        Cache::forget('dev_tools_folder_sizes');
        flash()->addSuccess('Log deleted successfully!');
        return redirect()->back();
    }

    /**
     * Delete all log files.
     */
    public function deleteAllLogs()
    {
        $this->clearFolder(storage_path('logs'));
        $this->updateLastCleanupTime();
        Cache::forget('dev_tools_folder_sizes');
        flash()->addSuccess('All logs deleted successfully!');
        return redirect()->back();
    }

    /**
     * Persistently save the last cleanup date/time in the settings table.
     */
    protected function updateLastCleanupTime()
    {
        DB::table('settings')->updateOrInsert(
            ['type' => 'last_cleanup_time'],
            [
                'description' => now()->format('Y-m-d H:i:s'),
                'updated_at'  => now(),
            ]
        );
    }

    /**
     * Helper to delete all files in a folder.
     */
    protected function clearFolder($path)
    {
        if (File::exists($path)) {
            foreach (File::files($path) as $file) {
                File::delete($file);
            }
        }
    }

    /**
     * Compute folder size recursively.
     */
    protected function getFolderSize($dir)
    {
        $size = 0;
        if (File::exists($dir)) {
            foreach (File::allFiles($dir) as $file) {
                $size += $file->getSize();
            }
        }
        return $size;
    }

    /**
     * Convert bytes to human readable format.
     */
    protected function humanFileSize($bytes, $decimals = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen((string)$bytes) - 1) / 3);
        return $factor == 0 ? $bytes . ' ' . $units[$factor] : sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . $units[$factor];
    }
}
