<?php
$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=cityhangaround2', 'postgres', 'PgLoader2026');

// Get sample data from key columns
$queries = [
    "SELECT id, city_name FROM cities LIMIT 5",
    "SELECT id, area_name, city_id FROM areas LIMIT 5",
    "SELECT id, product_category_name FROM categories LIMIT 5",
    "SELECT id, title, city_id, area_id, category, view FROM marketplaces LIMIT 5",
    "SELECT id, title, city_id, area_id, category_id, event_date, created_at, view FROM events LIMIT 5",
    "SELECT id, title, city_id, area_id, category_id, created_at FROM pages LIMIT 5",
    "SELECT id, title FROM blogs LIMIT 5",
    "SELECT id, title, city_id, area_id FROM groups LIMIT 5",
    "SELECT post_id, description, publisher, created_at FROM posts LIMIT 5",
    "SELECT story_id, content_type, expires_at, created_at FROM stories LIMIT 5",
];

foreach ($queries as $sql) {
    echo "\n=== " . $sql . " ===\n";
    $stmt = $pdo->query($sql);
    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        foreach ($r as $k => $v) {
            echo "  $k: " . var_export($v, true) . "\n";
        }
        echo "  ---\n";
    }
}
