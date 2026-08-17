<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

header('Content-Type: text/plain');

echo "--- APPROVING NOIDA BLOGS ---\n\n";

$blogs = DB::table('blogs')->whereIn('id', [20, 22])->get();

foreach ($blogs as $b) {
    echo "Before approval: ID={$b->id}, Title='{$b->title}', Status={$b->blog_status}, category_id='{$b->category_id}'\n";
}

$updated = DB::table('blogs')->whereIn('id', [20, 22])->update(['blog_status' => 2]);
echo "Updated rows: {$updated}\n\n";

$blogs_after = DB::table('blogs')->whereIn('id', [20, 22])->get();

foreach ($blogs_after as $b) {
    echo "After approval: ID={$b->id}, Title='{$b->title}', Status={$b->blog_status}\n";
    
    // Check categories and print URLs
    $categories = DB::table('blog_category')
        ->join('blogcategories', 'blogcategories.id', '=', 'blog_category.category_id')
        ->where('blog_category.blog_id', $b->id)
        ->select('blogcategories.category_slug', 'blogcategories.category_name')
        ->get();
        
    echo "Categories:\n";
    foreach ($categories as $cat) {
        $url = "https://test1.cityhangaround.com/blog/{$cat->category_slug}-in-noida";
        echo "  - '{$cat->category_name}' (Slug: '{$cat->category_slug}') -> URL: {$url}\n";
    }
}

// Clear relevant caches
echo "\nClearing caches...\n";
Cache::forget('active_blog_cities_v2');
Cache::forget('blog_all_cities_v1');
Cache::forget('blog_categories_city_11798_v2');
\Illuminate\Support\Facades\Artisan::call('cache:clear');
echo "Cache cleared successfully.\n";
