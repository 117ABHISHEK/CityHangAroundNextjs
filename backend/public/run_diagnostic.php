<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

header('Content-Type: text/plain');

echo "--- NOIDA SPECIFIC BLOG SEARCH ---\n";

// 1. Search for any blogs with city_id = 11798
$noida_blogs = DB::table('blogs')->where('city_id', 11798)->get();
echo "Blogs with city_id = 11798: " . count($noida_blogs) . "\n";
foreach ($noida_blogs as $b) {
    echo "ID={$b->id}, Title='{$b->title}', Status={$b->blog_status}, list_id={$b->list_id}\n";
}

// 2. Search for any blogs with "Noida" or "Digital" in the title
$matching_blogs = DB::table('blogs')
    ->where('title', 'like', '%Noida%')
    ->orWhere('title', 'like', '%Digital%')
    ->orWhere('title', 'like', '%Marketing%')
    ->get();
echo "\nBlogs matching 'Noida', 'Digital', or 'Marketing' in title: " . count($matching_blogs) . "\n";
foreach ($matching_blogs as $b) {
    echo "ID={$b->id}, Title='{$b->title}', Status={$b->blog_status}, city_id={$b->city_id}, list_id={$b->list_id}\n";
}

// 3. Noida listings related to "Digital Marketing Services"
echo "\n--- NOIDA PAGES ---\n";
$noida_pages = DB::table('pages')
    ->where('city_id', 11798)
    ->where(function($q) {
        $q->where('title', 'like', '%Digital%')
          ->orWhere('title', 'like', '%Marketing%');
    })
    ->get();
echo "Noida Listings matching 'Digital' or 'Marketing': " . count($noida_pages) . "\n";
foreach ($noida_pages as $p) {
    echo "ID={$p->id}, Title='{$p->title}', CityID={$p->city_id}\n";
}

// 4. Let's dump all blogs with status = 2 (approved) to see if there is any blog at all
echo "\n--- ALL APPROVED BLOGS ---\n";
$approved_blogs = DB::table('blogs')->where('blog_status', 2)->get();
echo "Total approved blogs: " . count($approved_blogs) . "\n";
foreach ($approved_blogs as $b) {
    echo "ID={$b->id}, Title='{$b->title}', city_id=" . var_export($b->city_id, true) . ", list_id=" . var_export($b->list_id, true) . "\n";
}
