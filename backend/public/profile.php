<?php
$start = microtime(true);
require __DIR__.'/../vendor/autoload.php';
$t_autoload = microtime(true) - $start;

$app = require_once __DIR__.'/../bootstrap/app.php';

// Bind request
$request = \Illuminate\Http\Request::capture();
$app->instance('request', $request);

// Run early bootstrappers
$app->make(\Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class)->bootstrap($app);
$app->make(\Illuminate\Foundation\Bootstrap\LoadConfiguration::class)->bootstrap($app);
$app->make(\Illuminate\Foundation\Bootstrap\HandleExceptions::class)->bootstrap($app);
$app->make(\Illuminate\Foundation\Bootstrap\RegisterFacades::class)->bootstrap($app);

// Get the list of providers from config
$providers = $app->make('config')->get('app.providers');

// Merge auto-discovered package providers
$packagesPath = __DIR__.'/../bootstrap/cache/packages.php';
if (file_exists($packagesPath)) {
    $packages = require $packagesPath;
    foreach ($packages as $pkg) {
        if (!empty($pkg['providers'])) {
            foreach ($pkg['providers'] as $provider) {
                if (!in_array($provider, $providers)) {
                    $providers[] = $provider;
                }
            }
        }
    }
}

header('Content-Type: text/plain');
echo "Autoload Time: " . number_format($t_autoload, 4) . "s\n\n";

echo "--- REGISTERING PROVIDERS ---\n";
foreach ($providers as $providerClass) {
    $s = microtime(true);
    $app->register($providerClass);
    $d = microtime(true) - $s;
    if ($d > 0.002) { // Print if > 2ms
        echo "$providerClass: " . number_format($d, 4) . "s\n";
    }
}

echo "\n--- BOOTING PROVIDERS ---\n";
$reflection = new ReflectionClass($app);
$method = $reflection->getMethod('bootProvider');
$method->setAccessible(true);

// Set booted flag to false so we can run bootProvider manually
$bootedProp = $reflection->getProperty('booted');
$bootedProp->setAccessible(true);
$bootedProp->setValue($app, false);

$serviceProvidersProp = $reflection->getProperty('serviceProviders');
$serviceProvidersProp->setAccessible(true);
$serviceProviders = $serviceProvidersProp->getValue($app);

foreach ($serviceProviders as $provider) {
    $providerClass = get_class($provider);
    $s = microtime(true);
    $method->invoke($app, $provider);
    $d = microtime(true) - $s;
    if ($d > 0.002) { // Print if > 2ms
        echo "$providerClass: " . number_format($d, 4) . "s\n";
    }
}

$total = microtime(true) - $start;
echo "\nTotal Time: " . number_format($total, 4) . "s\n";
