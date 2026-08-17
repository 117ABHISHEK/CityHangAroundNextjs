<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Blogcategory;
use App\Models\Page;

header('Content-Type: text/plain');

echo "--- DATABASE BLOG DIAGNOSTICS ---\n";

$total_blogs = DB::table('blogs')->count();
echo "Total blogs in database: $total_blogs\n";

$total_content_master = DB::table('content_master')->count();
echo "Total records in content_master: $total_content_master\n";

$blog_content_master = DB::table('content_master')->where('source_type', 'blog')->count();
echo "Total blog records in content_master: $blog_content_master\n";

echo "\n--- DUMPING RECENT 20 BLOGS ---\n";
$recent_blogs = DB::table('blogs')
    ->orderBy('id', 'desc')
    ->limit(20)
    ->get();

foreach ($recent_blogs as $b) {
    echo "ID={$b->id}, Title='{$b->title}', Status={$b->blog_status}, list_id=" . var_export($b->list_id, true) . ", city_id=" . var_export($b->city_id, true) . ", created_at={$b->created_at}\n";
    
    // Check if in content_master
    $cm = DB::table('content_master')
        ->where('source_type', 'blog')
        ->where('source_id', $b->id)
        ->first();
    if ($cm) {
        echo "  - In content_master: ID={$cm->id}, city_id={$cm->city_id}, area_id={$cm->area_id}, category_id={$cm->category_id}\n";
    } else {
        echo "  - NOT in content_master!\n";
    }

    // Check category mapping
    $cats = DB::table('blog_category')->where('blog_id', $b->id)->pluck('category_id')->toArray();
    echo "  - Categories in blog_category: " . implode(', ', $cats) . "\n";
}

echo "\n--- NOIDA DETAILS ---\n";
$city_slug = 'noida';
$city = DB::table('cities')->where('city_slug', $city_slug)->first();
if ($city) {
    echo "Noida City ID: {$city->id}\n";
    $noida_pages = DB::table('pages')->where('city_id', $city->id)->pluck('id')->toArray();
    echo "Noida Pages (Listings) Count: " . count($noida_pages) . "\n";
    if (count($noida_pages) > 0) {
        $blogs_linked = DB::table('blogs')->whereIn('list_id', $noida_pages)->count();
        echo "Blogs linked to Noida pages: $blogs_linked\n";
    }
} else {
    echo "Noida city not found!\n";
}

