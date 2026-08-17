<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

header('Content-Type: text/plain');

echo "--- EXPLAIN QUERY 1 ---\n";
print_r(\DB::select("EXPLAIN SELECT city_id, COUNT(*) as pages_count FROM pages WHERE item_status = 2 AND city_id IS NOT NULL GROUP BY city_id ORDER BY pages_count DESC LIMIT 10"));

echo "\n--- EXPLAIN QUERY 2 ---\n";
print_r(\DB::select("EXPLAIN SELECT cities.id, COUNT(pages.id) as pages_count FROM cities JOIN pages ON cities.id = pages.city_id WHERE cities.is_approved = 'Y' AND pages.item_status = 2 GROUP BY cities.id ORDER BY pages_count DESC LIMIT 10"));

echo "\n--- EXPLAIN QUERY 3 (Current Eloquent query) ---\n";
print_r(\DB::select("EXPLAIN select cities.*, (select count(*) from pages where cities.id = pages.city_id and item_status = 2) as pages_count from cities where is_approved = 'Y' order by pages_count desc limit 10"));
