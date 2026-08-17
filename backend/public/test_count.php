<?php
if (!isset($_GET['key']) || $_GET['key'] !== 'check123') {
    die("Unauthorized");
}

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$citySlug = $_GET['city'] ?? 'lucknow';
$city = DB::table('cities')->where('city_slug', $citySlug)->first();

if (!$city) {
    die("City not found: $citySlug");
}

$pageCount = DB::table('pages')
    ->where('city_id', $city->id)
    ->where('item_status', 2)
    ->count();

$activeCategoryIds = DB::table('content_master')
    ->where('source_type', 'category_count')
    ->where('status', 'listing')
    ->where('city_id', $city->id)
    ->where('total_count', '>', 0)
    ->pluck('category_id')
    ->merge(
        DB::table('content_master')
            ->where('source_type', 'category_count')
            ->where('status', 'listing')
            ->where('city_id', $city->id)
            ->where('total_count', '>', 0)
            ->pluck('parent_category_id')
    )
    ->filter()
    ->unique()
    ->toArray();

$categories = DB::table('pagecategories')
    ->whereNull('category_parent_id')
    ->whereIn('id', $activeCategoryIds)
    ->get();

echo json_encode([
    'city_name' => $city->city_name,
    'total_active_pages_in_city' => $pageCount,
    'total_active_parent_categories' => count($categories),
    'categories' => $categories->pluck('category_name')->toArray()
], JSON_PRETTY_PRINT);
