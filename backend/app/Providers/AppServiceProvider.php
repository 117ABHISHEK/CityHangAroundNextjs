<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Helpers\CityHelper;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\JsonLd;
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
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(\App\Providers\TelescopeServiceProvider::class);
        }
    }

    public function boot()
    {
        Paginator::useBootstrap();

        if (!app()->runningInConsole() && request()->has('profile_queries')) {
            $queriesFile = storage_path('logs/query_profile.log');
            $startTime = microtime(true);
            @file_put_contents($queriesFile, "\n--- Request Start: " . request()->fullUrl() . " ---\n", FILE_APPEND);
            
            DB::listen(function($query) use ($queriesFile) {
                $bindings = json_encode($query->bindings);
                $trace = collect(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS))
                    ->first(function($frame) {
                        return isset($frame['file']) && 
                               (str_contains(str_replace('\\', '/', $frame['file']), 'app/') || str_contains(str_replace('\\', '/', $frame['file']), 'resources/')) && 
                               !str_contains($frame['file'], 'AppServiceProvider.php');
                    });
                $origin = $trace ? ($trace['file'] . ':' . $trace['line']) : 'unknown';
                @file_put_contents($queriesFile, "[{$query->time}ms] {$query->sql} bindings={$bindings} origin={$origin}\n", FILE_APPEND);
            });
            
            app()->terminating(function() use ($queriesFile, $startTime) {
                $duration = (microtime(true) - $startTime) * 1000;
                @file_put_contents($queriesFile, "--- Request End. Duration: " . number_format($duration, 2) . "ms ---\n", FILE_APPEND);
            });
        }

        Health::checks([
            DatabaseCheck::new(),
            CacheCheck::new(),
            QueueCheck::new(),
            UsedDiskSpaceCheck::new(),
            DebugModeCheck::new(),
        ]);

        Paginator::useBootstrap();

        if (!app()->runningInConsole() && app()->bound('request')) {
            $currentCitySlug = request()->segment(1);
            $isHomepage = request()->path() === '/';

            if ($isHomepage || empty($currentCitySlug)) {
                session()->forget(['selected_city_id', 'selected_city_name', 'selected_city_slug']);
                $currentCitySlug = null;
            }

            $city = null;

            try {
                $resolveCityBySlug = function ($slug) {
                    if (!$slug) {
                        return null;
                    }

                    $city = Cache::remember('city_model_by_slug_' . $slug, 3600, function() use ($slug) {
                        return City::where('city_slug', $slug)->first() ?: 'not_found';
                    });

                    return $city === 'not_found' ? null : $city;
                };

                $resolveCityById = function ($id) {
                    if (!$id) {
                        return null;
                    }

                    $city = Cache::remember('city_model_by_id_' . $id, 3600, function() use ($id) {
                        return City::where('id', $id)->first() ?: 'not_found';
                    });

                    return $city === 'not_found' ? null : $city;
                };

                if ($currentCitySlug) {
                    $city = $resolveCityBySlug($currentCitySlug);
                }

                if (!$city && $currentCitySlug && strpos($currentCitySlug, '-in-') !== false) {
                    $parts = explode('-in-', $currentCitySlug);
                    $city = $resolveCityBySlug(end($parts));
                }

                if (!$city && !$isHomepage) {
                    $queryCity = request('city_filter') ?: request('city');
                    if ($queryCity) {
                        $city = is_numeric($queryCity)
                            ? $resolveCityById($queryCity)
                            : $resolveCityBySlug($queryCity);
                    }
                }

                if (!$city && !$isHomepage && session()->has('selected_city_slug')) {
                    $city = $resolveCityBySlug(session('selected_city_slug'));
                }
            } catch (\Exception $e) {
                // DB unavailable — gracefully fall back
                $city = null;
            }

            if ($city) {
                session([
                    'selected_city_id' => $city->id,
                    'selected_city_name' => $city->city_name,
                    'selected_city_slug' => $city->city_slug,
                ]);
            }

            View::share('cityName', $city?->city_name ?? 'Select City');
            View::share('currentCity', $city);
        } else {
            View::share('cityName', 'Select City');
            View::share('currentCity', null);
        }

        $canonicalUrl = $this->buildCanonicalUrl();
        View::share('canonicalUrl', $canonicalUrl);

        View::composer(['frontend.*', 'index', 'errors.*'], function ($view) use ($canonicalUrl) {
            if (!$this->shouldApplyCanonical()) {
                return;
            }

            SEOMeta::setCanonical($canonicalUrl);
            OpenGraph::setUrl($canonicalUrl);
            JsonLd::setUrl($canonicalUrl);
            $view->with('canonicalUrl', $canonicalUrl);
        });

        View::composer('frontend.*', function ($view) {
            static $alreadyShared = false;

            if (app()->runningInConsole() || !app()->bound('request')) {
                return;
            }

            if ($alreadyShared) {
                return;
            }

            if (request()->ajax() && !request()->routeIs('load_modal_content')) {
                return;
            }

            $city = view()->shared('currentCity');
            $cacheKey = 'global_frontend_view_data_v12_' . ($city ? $city->id : 'global');

            try {
            $lockKey = "global_frontend_view_data_v12_lock_" . ($city ? $city->id : 'global');
            $data = Cache::get($cacheKey);
            if ($data === null) {
                $data = Cache::lock($lockKey, 30)->block(30, function () use ($cacheKey, $city) {
                    $cached = Cache::get($cacheKey);
                    if (!is_null($cached)) {
                        return $cached;
                    }
                    return Cache::remember($cacheKey, 3600, function() use ($city) {

                // 🔥 SYSTEM SETTINGS
                $settings = Setting::whereIn('type', ['system_name', 'system_fav_icon'])
                    ->pluck('description', 'type');

                $system_name = $settings['system_name'] ?? 'Cityhangaround';
                $system_favicon = $settings['system_fav_icon'] ?? '';

                // 🔥 ADD THIS (CUSTOM PAGES)
                $customPages = CustomPage::latest()->take(50)->get();

                // 1. Page Guide Categories ($menuCategories)
                $menuCategoriesQuery = DB::table('pagecategories')
                    ->where('is_parent', 'Yes')
                    ->where('is_approved', 'Y');

                if ($city) {
                    $menuCategoriesQuery->whereExists(function ($query) use ($city) {
                        $query->select(DB::raw(1))
                              ->from('pages')
                              ->join('pagecategories as subcat', function ($join) {
                                  $join->on('subcat.id', '=', DB::raw("ANY(string_to_array(pages.category_id, ',')::bigint[])"));
                              })
                              ->where('pages.city_id', $city->id)
                              ->where('pages.item_status', 2)
                              ->where(function($q) {
                                  $q->whereRaw("pagecategories.id = ANY(string_to_array(pages.category_id, ',')::bigint[])")
                                    ->orWhereColumn('subcat.category_parent_id', 'pagecategories.id');
                              });
                    });
                }
                $menuCategories = $menuCategoriesQuery->orderBy('category_name')->get();

                // 2. Marketplace Categories ($marketplaceCategories)
                if ($city) {
                    $marketCountSub = DB::table('category_product as cp')
                        ->select(
                            DB::raw('COALESCE(parent_cat.id, cat.id) as parent_id'),
                            DB::raw('COUNT(*) as listing_count')
                        )
                        ->join('categories as cat', 'cat.id', '=', 'cp.product_category_id')
                        ->leftJoin('categories as parent_cat', 'parent_cat.id', '=', 'cat.category_parent_id')
                        ->join('marketplaces as m', 'm.id', '=', 'cp.product_id')
                        ->join('pages as p', 'p.id', '=', 'm.page_id')
                        ->where('m.product_status', 2)
                        ->where('p.item_status', 2)
                        ->where('p.city_id', $city->id)
                        ->groupBy(DB::raw('COALESCE(parent_cat.id, cat.id)'));

                    $marketplaceCategories = DB::table('categories')
                        ->select('categories.*', DB::raw('COALESCE(mc.listing_count, 0) as listing_count'))
                        ->where(function ($query) {
                            $query->whereNull('categories.category_parent_id')
                                  ->orWhere('categories.category_parent_id', 0);
                        })
                        ->leftJoinSub($marketCountSub, 'mc', 'categories.id', '=', 'mc.parent_id')
                        ->where('mc.listing_count', '>', 0)
                        ->orderByDesc('listing_count')
                        ->take(12)
                        ->get();
                } else {
                    $marketCountSub = DB::table('category_product as cp')
                        ->select(
                            DB::raw('COALESCE(parent_cat.id, cat.id) as parent_id'),
                            DB::raw('COUNT(*) as listing_count')
                        )
                        ->join('categories as cat', 'cat.id', '=', 'cp.product_category_id')
                        ->leftJoin('categories as parent_cat', 'parent_cat.id', '=', 'cat.category_parent_id')
                        ->join('marketplaces as m', 'm.id', '=', 'cp.product_id')
                        ->where('m.product_status', 2)
                        ->groupBy(DB::raw('COALESCE(parent_cat.id, cat.id)'));

                    $marketplaceCategories = DB::table('categories')
                        ->select('categories.*', DB::raw('COALESCE(mc.listing_count, 0) as listing_count'))
                        ->where(function ($query) {
                            $query->whereNull('categories.category_parent_id')
                                  ->orWhere('categories.category_parent_id', 0);
                        })
                        ->leftJoinSub($marketCountSub, 'mc', 'categories.id', '=', 'mc.parent_id')
                        ->where('mc.listing_count', '>', 0)
                        ->orderByDesc('listing_count')
                        ->take(12)
                        ->get();
                }

                // 3. Group Categories ($groupCategories)
                $groupCountSub = DB::table('group_category as gc')
                    ->select(
                        DB::raw('COALESCE(parent_cat.id, cat.id) as parent_id'),
                        DB::raw('COUNT(*) as listing_count')
                    )
                    ->join('groups as g', 'g.id', '=', 'gc.group_id')
                    ->join('groupcategories as cat', 'cat.id', '=', 'gc.category_id')
                    ->leftJoin('groupcategories as parent_cat', 'parent_cat.id', '=', 'cat.category_parent_id')
                    ->where('g.group_status', 2)
                    ->when($city, function ($q) use ($city) {
                        $q->where('g.city_id', $city->id);
                    })
                    ->groupBy(DB::raw('COALESCE(parent_cat.id, cat.id)'));

                $groupCategories = DB::table('groupcategories')
                    ->select('groupcategories.*', DB::raw('COALESCE(gc.listing_count, 0) as listing_count'))
                    ->where(function ($query) {
                        $query->whereNull('groupcategories.category_parent_id')
                              ->orWhere('groupcategories.category_parent_id', 0);
                    })
                    ->leftJoinSub($groupCountSub, 'gc', 'groupcategories.id', '=', 'gc.parent_id')
                    ->where('gc.listing_count', '>', 0)
                    ->orderByDesc('listing_count')
                    ->take(12)
                    ->get();

                // 4. Event Categories ($eventCategories)
                $eventCountSub = DB::table('event_category as ec')
                    ->select(
                        DB::raw('COALESCE(parent_cat.id, cat.id) as parent_id'),
                        DB::raw('COUNT(*) as listing_count')
                    )
                    ->join('events as e', 'e.id', '=', 'ec.event_id')
                    ->join('eventcategories as cat', 'cat.id', '=', 'ec.category_id')
                    ->leftJoin('eventcategories as parent_cat', 'parent_cat.id', '=', 'cat.category_parent_id')
                    ->where('e.event_status', 2)
                    ->when($city, function ($q) use ($city) {
                        $q->where('e.city_id', $city->id);
                    })
                    ->groupBy(DB::raw('COALESCE(parent_cat.id, cat.id)'));

                $eventCategories = DB::table('eventcategories')
                    ->select('eventcategories.*', DB::raw('COALESCE(ec.listing_count, 0) as listing_count'))
                    ->where(function ($query) {
                        $query->whereNull('eventcategories.category_parent_id')
                              ->orWhere('eventcategories.category_parent_id', 0);
                    })
                    ->leftJoinSub($eventCountSub, 'ec', 'eventcategories.id', '=', 'ec.parent_id')
                    ->where('ec.listing_count', '>', 0)
                    ->orderByDesc('listing_count')
                    ->take(12)
                    ->get();

                // 5. Blog Categories ($blogCategories)
                $blogCountSub = DB::table('blog_category as bc')
                    ->select(
                        DB::raw('COALESCE(parent_cat.id, cat.id) as parent_id'),
                        DB::raw('COUNT(*) as listing_count')
                    )
                    ->join('blogs as b', 'b.id', '=', 'bc.blog_id')
                    ->join('blogcategories as cat', 'cat.id', '=', 'bc.category_id')
                    ->leftJoin('blogcategories as parent_cat', 'parent_cat.id', '=', 'cat.category_parent_id')
                    ->where('b.blog_status', 2)
                    ->when($city, function ($q) use ($city) {
                        $q->where('b.city_id', $city->id);
                    })
                    ->groupBy(DB::raw('COALESCE(parent_cat.id, cat.id)'));

                $blogCategories = DB::table('blogcategories')
                    ->select('blogcategories.*', DB::raw('COALESCE(bc.listing_count, 0) as listing_count'))
                    ->where(function ($query) {
                        $query->whereNull('blogcategories.category_parent_id')
                              ->orWhere('blogcategories.category_parent_id', 0);
                    })
                    ->leftJoinSub($blogCountSub, 'bc', 'blogcategories.id', '=', 'bc.parent_id')
                    ->where('bc.listing_count', '>', 0)
                    ->orderByDesc('listing_count')
                    ->take(12)
                    ->get();

                $all_categories = DB::table('groupcategories')
                    ->whereNull('category_parent_id')
                    ->orWhere('category_parent_id', 0)
                    ->orderBy('category_name', 'ASC')
                    ->get();

                $all_group_cities = CityHelper::getActiveCities();

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
                    'customPages'
                );
            });
                });
            }

            $view->with($data);
            } catch (\Exception $e) {
                // DB unavailable — skip sharing frontend view data
            }
            $alreadyShared = true;
        });
    }

    private function shouldApplyCanonical(): bool
    {
        if (app()->runningInConsole() || !app()->bound('request')) {
            return false;
        }

        $request = request();

        if (!in_array($request->method(), ['GET', 'HEAD'], true)) {
            return false;
        }

        if ($request->ajax() || $request->expectsJson()) {
            return false;
        }

        return true;
    }

    private function buildCanonicalUrl(): string
    {
        if (!app()->bound('request')) {
            return rtrim((string) config('app.url'), '/');
        }

        $request = request();
        $trackingParams = [
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_term',
            'utm_content',
            'fbclid',
            'gclid',
            'msclkid',
        ];

        $query = $request->query();
        foreach ($trackingParams as $param) {
            unset($query[$param]);
        }

        $appUrl = (string) config('app.url');
        $parsed = parse_url($appUrl);
        $host = $parsed['host'] ?? $request->getHost();
        $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';
        $basePath = isset($parsed['path']) ? trim($parsed['path'], '/') : '';
        $requestPath = trim($request->path(), '/');

        $pathSegments = array_filter([$basePath, $requestPath], fn ($segment) => $segment !== '');
        $path = '/' . implode('/', $pathSegments);
        if ($path === '//') {
            $path = '/';
        }

        $canonical = 'https://' . $host . $port . $path;

        if (!empty($query)) {
            $canonical .= '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
        }

        return $canonical;
    }
}
