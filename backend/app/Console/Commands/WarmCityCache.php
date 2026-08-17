<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class WarmCityCache extends Command
{
    protected $signature = "cache:warm-cities {--limit=10 : Number of top cities to warm}";
    protected $description = "Pre-warm HTML page cache for top cities to eliminate cold-load latency";

    public function handle()
    {
        $limit = (int) $this->option("limit");

        $topCities = DB::table("cities")
            ->join("pages", "pages.city_id", "=", "cities.id")
            ->where("pages.item_status", 2)
            ->selectRaw("cities.city_slug, cities.city_name, COUNT(pages.id) as page_count")
            ->groupBy("cities.id", "cities.city_slug", "cities.city_name")
            ->orderByDesc("page_count")
            ->limit($limit)
            ->get();

        $baseUrl = config("app.url", "http://127.0.0.1:8000");

        $this->info("Warming cache for top {$limit} cities...");

        foreach ($topCities as $city) {
            $url = "{$baseUrl}/{$city->city_slug}";
            $this->line("  Warming: {$city->city_name} ({$city->page_count} listings)");

            try {
                $start = microtime(true);
                $response = Http::timeout(120)->get($url);
                $ms = round((microtime(true) - $start) * 1000);
                $this->line("    -> {$response->status()} in {$ms}ms");
            } catch (\Exception $e) {
                $this->error("    -> Failed: " . $e->getMessage());
            }
        }

        $this->info("Cache warming complete!");
        return 0;
    }
}
