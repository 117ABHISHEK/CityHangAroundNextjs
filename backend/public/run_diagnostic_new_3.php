<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

header('Content-Type: text/plain');

echo "--- MAPPING CITIES WITH APPROVED BLOGS ---\n";

$city_ids = DB::table('blogs')
    ->where('blog_status', 2)
    ->pluck('city_id')
    ->unique()
    ->toArray();

echo "Active City IDs: " . implode(', ', $city_ids) . "\n\n";

foreach ($city_ids as $cid) {
    if (!$cid) {
        echo "City ID is NULL\n";
        continue;
    }
    $c = DB::table('cities')->where('id', $cid)->first();
    if ($c) {
        $blog_count = DB::table('blogs')->where('blog_status', 2)->where('city_id', $cid)->count();
        echo "ID={$cid}: Name='{$c->city_name}', Slug='{$c->city_slug}', Blogs Count={$blog_count}\n";
    } else {
        echo "ID={$cid}: NOT FOUND in cities table!\n";
    }
}
