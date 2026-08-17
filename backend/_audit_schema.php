<?php
$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=cityhangaround2', 'postgres', 'PgLoader2026');

$tables = [
    'marketplaces', 'events', 'pages', 'categories', 'cities', 'areas',
    'users', 'event_masters', 'groups', 'posts', 'blogs', 'blog_master',
    'eventcategories', 'groupcategories', 'pagecategories',
    'category_product', 'event_category', 'group_category', 'page_category',
    'subscriptions', 'leads', 'enquirymaster', 'reviews',
    'stories', 'albums', 'album_images', 'notifications',
    'live_streamings', 'videos', 'opening_hours',
    'countries', 'states', 'custom_pages',
    'listmaster', 'incomplete_listings', 'claim_listings'
];

foreach ($tables as $table) {
    $stmt = $pdo->query("SELECT column_name, data_type, character_maximum_length, is_nullable, column_default FROM information_schema.columns WHERE table_schema = 'cityhangaround2' AND table_name = '" . $table . "' ORDER BY ordinal_position");
    $cols = $stmt->fetchAll(PDO::FETCH_OBJ);
    if (empty($cols)) {
        echo "\nTABLE: " . $table . " -- NOT FOUND\n";
        continue;
    }
    echo "\n=== TABLE: " . $table . " ===\n";
    foreach ($cols as $c) {
        $nullable = $c->is_nullable === 'YES' ? 'NULL' : 'NOT NULL';
        $default = $c->column_default ? ' DEFAULT ' . $c->column_default : '';
        $maxlen = $c->character_maximum_length ? '(' . $c->character_maximum_length . ')' : '';
        echo str_pad($c->column_name, 30) . ' ' . str_pad($c->data_type . $maxlen, 18) . ' ' . str_pad($nullable, 8) . $default . "\n";
    }
}
echo "\nDone.\n";
