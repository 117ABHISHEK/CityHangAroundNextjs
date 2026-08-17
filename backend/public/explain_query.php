<?php
if (!isset($_GET['key']) || $_GET['key'] !== 'check123') {
    die("Unauthorized");
}

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$city = DB::table('cities')->where('city_slug', 'lucknow')->first();
if (!$city) {
    die("City not found");
}

$sql = "
    EXPLAIN SELECT page_id, parent_category_id FROM (
        SELECT pc.page_id, 
               COALESCE(cat.category_parent_id, cat.id) as parent_category_id,
               ROW_NUMBER() OVER (
                   PARTITION BY COALESCE(cat.category_parent_id, cat.id) 
                   ORDER BY p.item_featured DESC, p.id DESC
               ) as row_num
        FROM page_category pc
        JOIN pagecategories cat ON pc.category_id = cat.id
        JOIN pages p ON pc.page_id = p.id
        WHERE p.city_id = ? AND p.item_status = 2
    ) t WHERE row_num <= 4
";

$explain = DB::select($sql, [$city->id]);

echo json_encode($explain, JSON_PRETTY_PRINT);
