<?php
if (!isset($_GET['key']) || $_GET['key'] !== 'check123') {
    die("Unauthorized");
}

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

$citySlug = $_GET['city'] ?? 'lucknow';

$t0 = microtime(true);

// 1. Query city
$city = DB::table('cities')->where('city_slug', $citySlug)->first();
$t1 = microtime(true);
$time_city = ($t1 - $t0) * 1000;

if (!$city) {
    die("City not found: $citySlug");
}

// 2. Get active cities
$active_cities = \App\Helpers\CityHelper::getActiveCities();
$t2 = microtime(true);
$time_active_cities = ($t2 - $t1) * 1000;

// 3. Parent categories
$all_categories = Cache::remember("cityguide_parent_categories_city_v2_{$city->id}", 1800, function () use ($city) {
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

    return DB::table('pagecategories')
        ->select('id', 'category_name', 'category_slug', 'category_parent_id')
        ->whereNull('category_parent_id')
        ->whereIn('id', $activeCategoryIds)
        ->orderBy('category_name', 'asc')
        ->get();
});
$t3 = microtime(true);
$time_parent_cats = ($t3 - $t2) * 1000;

// 4. City pages - OPTIMIZED WITH LIGHTWEIGHT QUERY + PHP SLICING
$rawPages = DB::table('pages')
    ->join('page_category', 'pages.id', '=', 'page_category.page_id')
    ->where('pages.city_id', $city->id)
    ->where('pages.item_status', 2)
    ->select('pages.id as page_id', 'page_category.category_id')
    ->orderByDesc('pages.item_featured')
    ->orderByDesc('pages.id')
    ->get();

$categoryParentLookup = Cache::remember("page_categories_parent_lookup_v2", 3600, function() {
    return DB::table('pagecategories')
        ->select('id', 'category_parent_id')
        ->get()
        ->pluck('category_parent_id', 'id')
        ->toArray();
});

// Slice top 4 page IDs per parent category in PHP
$slicedPageMappings = [];
$categoryPageCounts = [];
foreach ($rawPages as $p) {
    $catId = $p->category_id;
    $parentCatId = isset($categoryParentLookup[$catId]) ? $categoryParentLookup[$catId] : null;
    if (!$parentCatId) {
        $parentCatId = $catId;
    }
    if (!isset($categoryPageCounts[$parentCatId])) {
        $categoryPageCounts[$parentCatId] = 0;
    }
    if ($categoryPageCounts[$parentCatId] < 4) {
        $categoryPageCounts[$parentCatId]++;
        $slicedPageMappings[] = (object)[
            'page_id' => $p->page_id,
            'parent_category_id' => $parentCatId
        ];
    }
}

$uniquePageIds = collect($slicedPageMappings)->pluck('page_id')->unique()->toArray();

$pages = [];
if (!empty($uniquePageIds)) {
    $pages = \App\Models\Page::with([
        'city:id,city_name,city_slug',
        'area:id,area_name,area_slug',
        'state:id,state_name',
        'categories:id,category_name,category_slug,category_parent_id'
    ])
    ->whereIn('id', $uniquePageIds)
    ->get();
}

$likeCounts = [];
if (!empty($uniquePageIds)) {
    $likeCounts = DB::table('page_likes')
        ->whereIn('page_id', $uniquePageIds)
        ->selectRaw('page_id, COUNT(*) as cnt')
        ->groupBy('page_id')
        ->pluck('cnt', 'page_id')
        ->toArray();
}

// Build pagesByCategory mapping in memory
$pagesLookup = collect($pages)->keyBy('id');
$pagesByCategory = [];
foreach ($all_categories as $parentCat) {
    $pagesByCategory[$parentCat->id] = [];
}

foreach ($slicedPageMappings as $mapping) {
    $parentCatId = $mapping->parent_category_id;
    $pageId = $mapping->page_id;
    
    if (isset($pagesLookup[$pageId]) && isset($pagesByCategory[$parentCatId])) {
        $page = $pagesLookup[$pageId];
        $cats = [];
        foreach ($page->categories as $cat) {
            $cats[] = (object)[
                'id' => $cat->id,
                'category_name' => $cat->category_name,
                'category_slug' => $cat->category_slug,
                'category_parent_id' => $cat->category_parent_id
            ];
        }

        $lightPage = (object)[
            'id' => $page->id,
            'title' => $page->title,
            'logo' => $page->logo,
            'item_slug' => $page->item_slug,
            'item_featured' => $page->item_featured,
            'city_slug' => $page->city?->city_slug,
            'area_slug' => $page->area?->area_slug,
            'city_name' => $page->city?->city_name,
            'area_name' => $page->area?->area_name,
            'likes_count' => $likeCounts[$page->id] ?? 0,
            'categories' => collect($cats)
        ];

        // Avoid duplicates in the same parent category list
        if (!collect($pagesByCategory[$parentCatId])->contains('id', $page->id)) {
            $pagesByCategory[$parentCatId][] = $lightPage;
        }
    }
}

$t4 = microtime(true);
$time_city_pages_cache = ($t4 - $t3) * 1000;

// 5. Array slicing & flat mapping
$allCityPagesFlat = collect(array_merge(...array_values($pagesByCategory)))
    ->unique('id')->take(6);
$t5 = microtime(true);
$time_slicing = ($t5 - $t4) * 1000;

// 6. Posts query
$posts = \App\Models\Posts::where('posts.status', 'active')
    ->where('posts.report_status', '0')
    ->where('posts.privacy', 'public')
    ->select('posts.*')
    ->whereIn('posts.publisher', ['post', 'page', 'group', 'event'])
    ->where('posts.publisher', '!=', 'video_and_shorts')
    ->with([
        'getUser:id,name,photo',
        'media_files',
        'page:id,title,item_slug,city_id,area_id',
        'page.city:id,city_name,city_slug',
        'page.area:id,area_name,area_slug',
        'page.categories:id,category_name,category_slug',
        'group:id,title,group_slug,city_id,area_id,category_id',
        'group.city:id,city_name,city_slug',
        'group.area:id,area_name,area_slug',
        'group.category:id,category_name,category_slug',
        'event:id,title,event_slug,city_id,area_id,category_id',
        'event.city:id,city_name,city_slug',
        'event.area:id,area_name,area_slug',
        'event.category:id,category_name,category_slug'
    ])
    ->orderByDesc('posts.created_at')
    ->orderByDesc('posts.post_id')
    ->take(5)
    ->get();

$t6 = microtime(true);
$time_posts_query = ($t6 - $t5) * 1000;

// 7. Comments
$postIds = collect($posts)->pluck('post_id')->filter()->unique()->values()->all();
$commentCounts = [];
if (!empty($postIds)) {
    $commentCounts = DB::table('comments')
        ->select('id_of_type', DB::raw('COUNT(*) as total'))
        ->where('is_type', 'post')
        ->whereIn('id_of_type', $postIds)
        ->groupBy('id_of_type')
        ->pluck('total', 'id_of_type')
        ->toArray();
}
$t7 = microtime(true);
$time_comments = ($t7 - $t6) * 1000;

// 8. Recent events query
$recentEvents = \App\Models\Event::with(['city', 'area', 'categories'])
    ->where('event_status', 2)
    ->where('city_id', $city->id)
    ->orderBy('id', 'desc')
    ->limit(6)
    ->get();
$t8 = microtime(true);
$time_recent_events = ($t8 - $t7) * 1000;

// 9. Total
$total_time = ($t8 - $t0) * 1000;

echo json_encode([
    'city_slug' => $citySlug,
    'total_unique_pages_loaded' => count($uniquePageIds),
    'profile_ms' => [
        '1_query_city' => round($time_city, 2),
        '2_active_cities' => round($time_active_cities, 2),
        '3_parent_categories' => round($time_parent_cats, 2),
        '4_city_pages_optimized_query' => round($time_city_pages_cache, 2),
        '5_slicing_flatmap' => round($time_slicing, 2),
        '6_posts_query' => round($time_posts_query, 2),
        '7_comments_query' => round($time_comments, 2),
        '8_recent_events_query' => round($time_recent_events, 2),
        'total_db_time_ms' => round($total_time, 2)
    ]
], JSON_PRETTY_PRINT);
