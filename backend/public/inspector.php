<?php
// Secure key check
if (!isset($_GET['key']) || $_GET['key'] !== 'check123') {
    die("Unauthorized. Please append ?key=check123 to the URL.");
}

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Query active cities with events
$cities = DB::table('events')
    ->join('cities', 'cities.id', '=', 'events.city_id')
    ->where('events.event_status', 2)
    ->where('events.event_date', '>=', Carbon::now()->toDateString())
    ->select('cities.city_name', 'cities.city_slug', DB::raw('COUNT(events.id) as count'))
    ->groupBy('cities.id', 'cities.city_name', 'cities.city_slug')
    ->get();

// Query active event categories
$categories = DB::table('events')
    ->join('event_category', 'event_category.event_id', '=', 'events.id')
    ->join('eventcategories', 'eventcategories.id', '=', 'event_category.category_id')
    ->join('cities', 'cities.id', '=', 'events.city_id')
    ->where('events.event_status', 2)
    ->where('events.event_date', '>=', Carbon::now()->toDateString())
    ->select('eventcategories.category_name', 'eventcategories.category_slug', 'cities.city_name', 'cities.city_slug', DB::raw('COUNT(events.id) as count'))
    ->groupBy('eventcategories.id', 'eventcategories.category_name', 'eventcategories.category_slug', 'cities.id', 'cities.city_name', 'cities.city_slug')
    ->get();

// Also output all raw events that are active
$events = DB::table('events')
    ->join('cities', 'cities.id', '=', 'events.city_id')
    ->where('events.event_status', 2)
    ->where('events.event_date', '>=', Carbon::now()->toDateString())
    ->select('events.title', 'events.event_date', 'cities.city_name', 'events.event_slug')
    ->get();

header('Content-Type: application/json');
echo json_encode([
    'active_cities_with_events' => $cities,
    'categories_with_active_events' => $categories,
    'all_active_events' => $events
], JSON_PRETTY_PRINT);
