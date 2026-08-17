<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Helpers\CityHelper;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\City;
use App\Models\Setting; // 🔥 ADDED

use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\QueueCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use App\Models\CustomPage;


class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        Paginator::useBootstrap();

        Health::checks([
            DatabaseCheck::new(),
            CacheCheck::new(),
            QueueCheck::new(),
            UsedDiskSpaceCheck::new(),
            DebugModeCheck::new(),
        ]);

        Paginator::useBootstrap();

        if ($currentCitySlug = request()->segment(1)) {
            $cityName = Cache::remember('city_name_slug_' . $currentCitySlug, 3600, function() use ($currentCitySlug) {
                $currentCity = City::where('city_slug', $currentCitySlug)->first();
                return $currentCity ? $currentCity->city_name : 'Select City';
            });

            View::share('cityName', $cityName);
        } else {
            View::share('cityName', 'Select City');
        }

        View::composer('frontend.*', function ($view) {

            if (request()->ajax() && !request()->routeIs('load_modal_content')) {
                return;
            }

            $cacheKey = 'global_frontend_view_data_v2';

          $data = Cache::remember($cacheKey, 3600, function() {

    // 🔥 SYSTEM SETTINGS
    $settings = Setting::whereIn('type', ['system_name', 'system_fav_icon'])
        ->pluck('description', 'type');

    $system_name = $settings['system_name'] ?? 'Cityhangaround';
    $system_favicon = $settings['system_fav_icon'] ?? '';

    // 🔥 ADD THIS (CUSTOM PAGES)
    $customPages = CustomPage::latest()->get();

    // EXISTING CODE
    $menuCategories = DB::table('pagecategories')
        ->where('is_parent', 'Yes')
        ->where('is_approved', 'Y')
        ->orderBy('category_name')
        ->get();

    $marketplaceCategories = DB::table('categories')
        ->select('categories.*')
        ->where(function ($query) {
            $query->whereNull('categories.category_parent_id')
                  ->orWhere('categories.category_parent_id', 0);
        })
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('category_product')
                  ->join('marketplaces', 'marketplaces.id', '=', 'category_product.product_id')
                  ->whereColumn('category_product.product_category_id', 'categories.id')
                  ->where('marketplaces.product_status', 2);
        })
        ->orderByRaw('(SELECT COUNT(*) FROM category_product cp JOIN marketplaces m ON m.id = cp.product_id WHERE cp.product_category_id = categories.id AND m.product_status = 2) DESC')
        ->take(12)
        ->get();

    $groupCategories = DB::table('groupcategories')
        ->select('groupcategories.*')
        ->where(function ($query) {
            $query->whereNull('groupcategories.category_parent_id')
                  ->orWhere('groupcategories.category_parent_id', 0);
        })
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('group_category')
                  ->join('groups', 'groups.id', '=', 'group_category.group_id')
                  ->whereColumn('group_category.category_id', 'groupcategories.id')
                  ->where('groups.group_status', 2);
        })
        ->orderByRaw('(SELECT COUNT(*) FROM group_category gc JOIN `groups` g ON g.id = gc.group_id WHERE gc.category_id = groupcategories.id AND g.group_status = 2) DESC')
        ->take(12)
        ->get();

    $eventCategories = DB::table('eventcategories')
        ->select('eventcategories.*')
        ->where(function ($query) {
            $query->whereNull('eventcategories.category_parent_id')
                  ->orWhere('eventcategories.category_parent_id', 0);
        })
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('event_category')
                  ->join('events', 'events.id', '=', 'event_category.event_id')
                  ->whereColumn('event_category.category_id', 'eventcategories.id')
                  ->where('events.event_status', 2);
        })
        ->orderByRaw('(SELECT COUNT(*) FROM event_category ec JOIN events e ON e.id = ec.event_id WHERE ec.category_id = eventcategories.id AND e.event_status = 2) DESC')
        ->take(12)
        ->get();

    $blogCategories = DB::table('blogcategories')
        ->select('blogcategories.*')
        ->where(function ($query) {
            $query->whereNull('blogcategories.category_parent_id')
                  ->orWhere('blogcategories.category_parent_id', 0);
        })
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('blog_category')
                  ->join('blogs', 'blogs.id', '=', 'blog_category.blog_id')
                  ->whereColumn('blog_category.category_id', 'blogcategories.id')
                  ->where('blogs.blog_status', 2);
        })
        ->orderByRaw('(SELECT COUNT(*) FROM blog_category bc JOIN blogs b ON b.id = bc.blog_id WHERE bc.category_id = blogcategories.id AND b.blog_status = 2) DESC')
        ->take(12)
        ->get();

    $all_categories = DB::table('groupcategories')
        ->whereNull('category_parent_id')
        ->orWhere('category_parent_id', 0)
        ->orderBy('category_name', 'ASC')
        ->get();

    $all_group_cities = DB::table('cities')
        ->orderBy('city_name', 'ASC')
        ->get();

    return compact(
        'menuCategories',
        'marketplaceCategories',
        'groupCategories',
        'eventCategories',
        'blogCategories',
        'all_categories',
        'all_group_cities',
        'system_name',
        'system_favicon',
        'customPages' // 🔥 IMPORTANT
    );
});

            $view->with($data);
        });
    }
}