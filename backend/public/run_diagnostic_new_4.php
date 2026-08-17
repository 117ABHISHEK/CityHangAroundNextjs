<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

header('Content-Type: text/plain');

echo "--- LISTING APPROVED BLOGS WITH URL INFORMATION ---\n\n";

$approved_blogs = DB::table('blogs')
    ->select([
        'blogs.id as blog_id',
        'blogs.title as blog_title',
        'blogs.blog_slug',
        'blogs.blog_status',
        'blogs.city_id as blog_city_id',
        'blogs.list_id',
        'pages.city_id as page_city_id',
        'pages.title as page_title'
    ])
    ->leftJoin('pages', 'pages.id', '=', 'blogs.list_id')
    ->where('blogs.blog_status', 2)
    ->get();

foreach ($approved_blogs as $b) {
    $city_id = $b->blog_city_id ?: $b->page_city_id;
    $city = DB::table('cities')->where('id', $city_id)->first();
    $city_slug = $city ? $city->city_slug : 'unknown';
    $city_name = $city ? $city->city_name : 'unknown';

    // Get categories
    $categories = DB::table('blog_category')
        ->join('blogcategories', 'blogcategories.id', '=', 'blog_category.category_id')
        ->where('blog_category.blog_id', $b->blog_id)
        ->select('blogcategories.category_slug', 'blogcategories.category_name')
        ->get();

    echo "Blog ID: {$b->blog_id}\n";
    echo "Title: '{$b->blog_title}'\n";
    echo "Resolved City: {$city_name} (ID: {$city_id}, Slug: '{$city_slug}')\n";
    if ($b->list_id) {
        echo "Linked Listing: '{$b->page_title}' (ID: {$b->list_id})\n";
    } else {
        echo "Linked Listing: NONE\n";
    }
    
    echo "Categories:\n";
    foreach ($categories as $cat) {
        $url = "https://test1.cityhangaround.com/blog/{$cat->category_slug}-in-{$city_slug}";
        echo "  - Name: '{$cat->category_name}', Slug: '{$cat->category_slug}'\n";
        echo "    URL: {$url}\n";
    }
    echo "----------------------------------------\n\n";
}
