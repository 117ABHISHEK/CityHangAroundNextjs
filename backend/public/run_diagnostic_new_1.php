<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

header('Content-Type: text/plain');

echo "--- ALL PENDING BLOGS (STATUS = 1) ---\n";
$pending_blogs = DB::table('blogs')->where('blog_status', 1)->orderBy('id', 'desc')->get();
echo "Count: " . count($pending_blogs) . "\n\n";

foreach ($pending_blogs as $b) {
    echo "ID={$b->id}, Title='{$b->title}', city_id=" . var_export($b->city_id, true) . ", list_id=" . var_export($b->list_id, true) . ", created_at={$b->created_at}\n";
    if ($b->list_id) {
        $p = DB::table('pages')->where('id', $b->list_id)->first();
        if ($p) {
            echo "  - Listing: ID={$p->id}, Title='{$p->title}', CityID={$p->city_id}\n";
        }
    }
}
