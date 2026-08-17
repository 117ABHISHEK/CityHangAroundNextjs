<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CityHelper
{
    public static function getActiveCities()
    {
        return Cache::remember('active_cities_cached', 3600, function () {
            return DB::table('cities')
                ->select('cities.*')
                ->join('pages', function ($join) {
                    $join->on('pages.city_id', '=', 'cities.id')
                         ->where('pages.item_status', '=', 2);
                })
                ->join('page_category', 'page_category.page_id', '=', 'pages.id')
                ->distinct('cities.id')
                ->orderByDesc('cities.id')
                ->get();
        });
    }

    /**
     * Get cities specifically for blog functionality with memory-efficient query
     * This prevents memory exhaustion issues in blog-related pages
     */
    public static function getCitiesForBlogs()
    {
        return Cache::remember('blog_cities_cached', 3600, function () {
            // Memory-efficient query using whereExists instead of joins to prevent memory exhaustion
            return DB::table('cities')
                ->select('cities.id', 'cities.city_name', 'cities.city_slug', 'cities.state_id', 'cities.city_state')
                ->whereExists(function($query) {
                    $query->select(DB::raw(1))
                          ->from('pages')
                          ->whereRaw('pages.city_id = cities.id')
                          ->where('pages.item_status', 2);
                })
                ->where('cities.is_approved', 'Y') // Add approved filter for better performance
                ->orderBy('cities.city_name', 'ASC')
                ->limit(1000) // Add reasonable limit to prevent memory exhaustion
                ->get();
        });
    }
}
