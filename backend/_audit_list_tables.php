<?php
$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=cityhangaround2', 'postgres', 'PgLoader2026');
echo "CONNECTED\n";

// List all schemas
$stmt = $pdo->query("SELECT schema_name FROM information_schema.schemata ORDER BY schema_name");
echo "\nSchemas:\n";
while ($r = $stmt->fetch(PDO::FETCH_OBJ)) {
    echo "  " . $r->schema_name . "\n";
}

// Try searching in all schemas
$stmt = $pdo->query("SELECT table_schema, table_name FROM information_schema.tables ORDER BY table_schema, table_name");
echo "\nAll tables:\n";
$count = 0;
while ($r = $stmt->fetch(PDO::FETCH_OBJ)) {
    echo "  " . $r->table_schema . "." . $r->table_name . "\n";
    $count++;
}
echo "Total tables: $count\n";
