<?php
if (!isset($_GET['key']) || $_GET['key'] !== 'check123') {
    die("Unauthorized");
}

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

$city = DB::table('cities')->where('city_slug', 'lucknow')->first();
if (!$city) {
    die("City not found");
}

$queryHash = md5(http_build_query([
    'area'           => '0',
    'category'       => '0',
    'filter_sort_by' => 'newest',
]));
$htmlCacheKey = "city_page_html_v4_{$city->id}_{$queryHash}";

$cachedVal = Cache::get($htmlCacheKey);
echo "HTML Cache Key: " . $htmlCacheKey . "\n";
echo "Cached HTML length: " . ($cachedVal ? strlen($cachedVal) : "NULL (Cache Miss)") . "\n";

if ($cachedVal) {
    echo "Total <img tags: " . substr_count($cachedVal, '<img') . "\n";
    echo "Total data:image/ (base64) tags: " . substr_count($cachedVal, 'data:image/') . "\n";
    echo "Total <a links: " . substr_count($cachedVal, '<a ') . "\n";
    echo "Total <div tags: " . substr_count($cachedVal, '<div') . "\n";
}
