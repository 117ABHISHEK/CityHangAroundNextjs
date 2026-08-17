<?php
/**
 * Laravel Server Performance Dashboard (Cache & Database Optimizer)
 * 
 * INSTRUCTIONS:
 * 1. Upload this file and the new migration file to your server.
 * 2. Visit: https://your-website-domain.com/clean_server.php in your browser.
 * 3. Use the dashboard to clear caches, run migrations (adds speed indexes), and enable production caching.
 * 4. IMPORTANT: Delete this file from the server immediately after use for security reasons.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize Laravel application to use Artisan
$autoloadPath = __DIR__.'/../vendor/autoload.php';
$bootstrapPath = __DIR__.'/../bootstrap/app.php';
$laravelLoaded = false;
$app = null;

if (is_file($autoloadPath) && is_file($bootstrapPath)) {
    require $autoloadPath;
    $app = require $bootstrapPath;
    $laravelLoaded = true;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$outputConsole = '';
$actionTitle = '';

if ($laravelLoaded && $app) {
    try {
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        
        if ($action === 'clear') {
            $actionTitle = 'Clear Caches & Logs';
            
            // 1. Run optimize:clear
            $outputBuffer = new Symfony\Component\Console\Output\BufferedOutput();
            $kernel->handle(new Symfony\Component\Console\Input\StringInput('optimize:clear'), $outputBuffer);
            $outputConsole .= "⚡ Laravel Caches Cleared:\n" . $outputBuffer->fetch() . "\n";
            
            // 2. Clear debugbar files
            $debugbarPath = __DIR__ . '/../storage/debugbar';
            if (is_dir($debugbarPath)) {
                $files = glob($debugbarPath . '/*.json');
                $count = 0;
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                        $count++;
                    }
                }
                $outputConsole .= "🛠️ Debugbar: Deleted $count cached JSON profiles.\n";
            }
            
            // 3. Truncate log file
            $logPath = __DIR__ . '/../storage/logs/laravel.log';
            if (is_file($logPath)) {
                $currentSize = filesize($logPath);
                file_put_contents($logPath, '');
                $sizeMb = round($currentSize / 1024 / 1024, 2);
                $outputConsole .= "📝 System Log: Truncated laravel.log (Saved {$sizeMb} MB).\n";
            }
        } 
        elseif ($action === 'migrate') {
            $actionTitle = 'Run Database Migrations';
            
            // Run php artisan migrate
            $outputBuffer = new Symfony\Component\Console\Output\BufferedOutput();
            $status = $kernel->handle(new Symfony\Component\Console\Input\StringInput('migrate --force'), $outputBuffer);
            $outputConsole .= "🛢️ Running Database Migrations:\n" . $outputBuffer->fetch() . "\n";
        } 
        elseif ($action === 'optimize') {
            $actionTitle = 'Production Performance Optimization';
            
            // 1. Config Cache
            $outputBuffer = new Symfony\Component\Console\Output\BufferedOutput();
            $kernel->handle(new Symfony\Component\Console\Input\StringInput('config:cache'), $outputBuffer);
            $outputConsole .= "📁 Configuration Cached:\n" . $outputBuffer->fetch() . "\n";
            
            // 2. Route Cache
            $outputBuffer = new Symfony\Component\Console\Output\BufferedOutput();
            $kernel->handle(new Symfony\Component\Console\Input\StringInput('route:cache'), $outputBuffer);
            $outputConsole .= "🛤️ Routes Cached:\n" . $outputBuffer->fetch() . "\n";
            
            // 3. View Cache
            $outputBuffer = new Symfony\Component\Console\Output\BufferedOutput();
            $kernel->handle(new Symfony\Component\Console\Input\StringInput('view:cache'), $outputBuffer);
            $outputConsole .= "🎨 Views Cached:\n" . $outputBuffer->fetch() . "\n";
        }
    } catch (Exception $e) {
        $outputConsole .= "❌ Error executing action: " . $e->getMessage() . "\n";
    }
} else {
    $outputConsole = "❌ Laravel application could not be bootstrapped. Make sure this file is placed in the public/ directory.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Performance Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Outfit", sans-serif;
            background-color: #0b0f19;
            color: #f1f5f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .container {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 28px;
            padding: 40px;
            width: 100%;
            max-width: 750px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        header {
            text-align: center;
            margin-bottom: 35px;
        }
        h1 {
            font-size: 2.4rem;
            font-weight: 800;
            margin: 0 0 10px 0;
            background: linear-gradient(135deg, #38bdf8, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        p.subtitle {
            color: #94a3b8;
            font-size: 1rem;
            margin: 0;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 24px;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }
        .card:hover {
            transform: translateY(-4px);
            border-color: rgba(56, 189, 248, 0.4);
            background: rgba(30, 41, 59, 0.8);
            box-shadow: 0 10px 20px -5px rgba(56, 189, 248, 0.1);
        }
        .card-icon {
            font-size: 2rem;
            margin-bottom: 12px;
        }
        .card-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 8px;
            color: #f8fafc;
        }
        .card-desc {
            font-size: 0.85rem;
            color: #94a3b8;
            margin-bottom: 20px;
            line-height: 1.4;
        }
        .btn {
            display: inline-block;
            width: 100%;
            padding: 12px 20px;
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: opacity 0.2s;
            border: none;
            cursor: pointer;
            box-sizing: border-box;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .btn-orange {
            background: linear-gradient(135deg, #fb923c, #ea580c);
        }
        .btn-purple {
            background: linear-gradient(135deg, #c084fc, #7c3aed);
        }
        .output-box {
            background: #030712;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 20px;
            margin-top: 30px;
        }
        .output-header {
            font-size: 0.9rem;
            font-weight: 600;
            color: #38bdf8;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .output-header .badge {
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
        }
        pre {
            margin: 0;
            color: #34d399;
            font-family: "Fira Code", Consolas, Monaco, monospace;
            font-size: 0.9rem;
            white-space: pre-wrap;
            word-break: break-all;
            line-height: 1.5;
            max-height: 300px;
            overflow-y: auto;
        }
        .warning-footer {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 16px;
            color: #fca5a5;
            padding: 16px;
            margin-top: 40px;
            text-align: center;
            font-size: 0.88rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Server Performance Dashboard</h1>
            <p class="subtitle">Optimize database queries and cache speed for Cityhangaround</p>
        </header>

        <div class="grid">
            <!-- Clear Cache Card -->
            <div class="card">
                <div>
                    <div class="card-icon">🧹</div>
                    <div class="card-title">Clear Caches</div>
                    <div class="card-desc">Flush Laravel view/config cache and clear massive debugbar and log files.</div>
                </div>
                <a href="?action=clear" class="btn">Clean Server</a>
            </div>

            <!-- Run Migrations Card -->
            <div class="card">
                <div>
                    <div class="card-icon">🛢️</div>
                    <div class="card-title">Apply Speed Indexes</div>
                    <div class="card-desc">Run database migrations to add performance indexes on cities and pages.</div>
                </div>
                <a href="?action=migrate" class="btn btn-orange">Run Migrations</a>
            </div>

            <!-- Production Optimize Card -->
            <div class="card">
                <div>
                    <div class="card-icon">🚀</div>
                    <div class="card-title">Production Speedup</div>
                    <div class="card-desc">Cache configurations, routes, and views so Laravel runs at maximum speed.</div>
                </div>
                <a href="?action=optimize" class="btn btn-purple">Enable Cache</a>
            </div>
        </div>

        <?php if ($action): ?>
        <div class="output-box">
            <div class="output-header">
                <span>Execution Logs</span>
                <span class="badge"><?php echo htmlspecialchars($actionTitle); ?></span>
            </div>
            <pre><?php echo htmlspecialchars($outputConsole); ?></pre>
        </div>
        <?php endif; ?>

        <div class="warning-footer">
            ⚠️ SECURITY NOTICE: Delete the <b>clean_server.php</b> file from your server's public directory immediately after you finish.
        </div>
    </div>
</body>
</html>
