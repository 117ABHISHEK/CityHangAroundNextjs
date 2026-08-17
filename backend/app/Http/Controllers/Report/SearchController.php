<?php

namespace App\Http\Controllers\Report;

use Artesaos\SEOTools\Facades\SEOMeta;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Group;
use App\Models\Marketplace;
use App\Models\Page;
use App\Models\Posts;
use App\Models\Blog;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use DB;
use App\Models\Pagecategory;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use App\Services\CategoryCitySeoService;

class SearchController extends Controller
{
    public function search_globally(Request $request)
    {
        // Validate search input (make search optional for empty query focus)
        $rules = [
            'search' => 'nullable|string|max:100',
            'cityid' => 'nullable'
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json([
                'pages' => [],
                'marketplace' => [],
                'events' => [],
                'blogs' => [],
                'users' => []
            ]);
        }
    
        // Get search query and cityid
        $search_param = $request->input('search');
        $cityid = $request->input('cityid'); // Get city ID from request

        if (empty($cityid) || $cityid === '0' || $cityid === 0) {
            $cityid = session('selected_city_id', 0);
        }

        // Resolve city slug to city ID if slug is passed
        if (!empty($cityid) && $cityid !== '0' && $cityid !== 0) {
            if (!is_numeric($cityid)) {
                $city = DB::table('cities')->where('city_slug', $cityid)->first();
                $cityid = $city ? $city->id : 0;
            }
        } else {
            $cityid = 0;
        }

        // 1. Fetch Pages (Businesses) from content_master
        $query = DB::table('content_master')
            ->select(
                'content_master.source_id as id', 
                'content_master.title', 
                'content_master.slug as item_slug', 
                'cities.city_slug', 
                'areas.area_slug', 
                'pagecategories.category_slug',
                'pagecategories.category_name'
            )
            ->leftJoin('cities', 'cities.id', '=', 'content_master.city_id')
            ->leftJoin('areas', 'areas.id', '=', 'content_master.area_id')
            ->leftJoin('pagecategories', 'pagecategories.id', '=', 'content_master.category_id')
            ->where('content_master.source_type', 'listing')
            ->where('content_master.status', '2'); // status is the page's item_status (2 for active)

        if (!empty($cityid) && $cityid != 0) {
            $query->where('content_master.city_id', $cityid);
        }

        if (!empty($search_param)) {
            $query->where('content_master.title', 'LIKE', "%{$search_param}%")
                  ->orderByRaw("CASE 
                      WHEN content_master.title LIKE ? THEN 1 
                      WHEN content_master.title LIKE ? THEN 2 
                      ELSE 3 
                  END ASC", ["{$search_param}%", "% {$search_param}%"]);
        }

        $data['pages'] = $query->limit(50)->get();

        // 2. Fetch other results only if search param is NOT empty
        if (!empty($search_param)) {
            // Marketplace
            $marketplaceQuery = Marketplace::select('marketplaces.id', 'marketplaces.title')
                ->join('pages', 'marketplaces.page_id', '=', 'pages.id')
                ->where('marketplaces.product_status', 2)
                ->where('marketplaces.title', 'LIKE', "%{$search_param}%");
            if (!empty($cityid) && $cityid != 0) {
                $marketplaceQuery->where('pages.city_id', $cityid);
            }
            $data['marketplace'] = $marketplaceQuery->groupBy('marketplaces.id', 'marketplaces.title')->limit(50)->get();

            // Events
            $eventQuery = Event::where('title', 'LIKE', "%{$search_param}%")
                ->where('events.event_date', '>=', Carbon::now());
            if (!empty($cityid) && $cityid != 0) {
                $eventQuery->where('events.city_id', $cityid);
            }
            $data['events'] = $eventQuery->limit(50)->get();

            // Blogs
            $blogQuery = Blog::where('title', 'LIKE', "%{$search_param}%");
            if (!empty($cityid) && $cityid != 0) {
                $blogQuery->where('blogs.city_id', $cityid);
            }
            $data['blogs'] = $blogQuery->limit(50)->get();

            // Users
            $userQuery = User::where('name', 'LIKE', "%{$search_param}%");
            $data['users'] = $userQuery->limit(50)->get();
        } else {
            // Empty arrays if search_param is empty (suggestion mode)
            $data['marketplace'] = [];
            $data['events'] = [];
            $data['blogs'] = [];
            $data['users'] = [];
        }
    
        return response()->json($data);
    }
    
    public function search_globallyold(Request $request)
    {
        // Validate search input
        $rules = [
            'search' => 'required|string|min:2|max:100'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'validationError' => $validator->getMessageBag()->toArray()
            ]);
        }

        // Get search query
        $search_param = $request->input('search');

        // Fetch relevant data from multiple models
        $data = [
            $subQuery = DB::table('page_category')
                ->select('page_id', DB::raw('MAX(page_category.id) as latest_page_category_id'))
                ->groupBy('page_id'),

            // Now, join the subquery with the main query
            'pages' => DB::table('pages')
                ->select('pages.id', 'pages.item_slug', 'pages.logo', 'pages.title', 'cities.city_slug', 'areas.area_slug', 'pagecategories.category_slug',
                    'cities.city_name', 'areas.area_name', 'states.state_name')
                ->join('cities', 'cities.id', '=', 'pages.city_id')
                ->join('areas', 'areas.id', '=', 'pages.area_id')
                ->join('states', 'states.id', '=', 'pages.state_id')
                ->join('page_category', 'page_category.page_id', '=', 'pages.id')
                ->join('pagecategories', 'page_category.category_id', '=', 'pagecategories.id')
                // Correctly join the subquery
                ->joinSub($subQuery, 'latest_page_category', function($join) {
                    $join->on('page_category.id', '=', 'latest_page_category.latest_page_category_id');
                })
                ->where('pages.item_status', 2)
                ->where('title', 'LIKE', "%{$search_param}%")
                ->limit(50)
                ->get(),
            'marketplace' => Marketplace::select('marketplaces.id', 'marketplaces.title')
                ->join('pages', 'marketplaces.page_id', '=', 'pages.id')
                ->where('marketplaces.product_status', 2)
                ->where('marketplaces.title', 'LIKE', "%{$search_param}%")
                ->groupBy('marketplaces.id', 'marketplaces.title') // Group by all columns used in select
                ->limit(50)
                ->get(),

            'events' => Event::where('title', 'LIKE', "%{$search_param}%") ->where('events.event_date', '>=', Carbon::now())->limit(50)->get(),
            'blogs' => Blog::where('title', 'LIKE', "%{$search_param}%")->limit(50)->get(),
            'users' => User::where('name', 'LIKE', "%{$search_param}%")->limit(50)->get(),
        ];

        return response()->json($data);
    }
    
    // search function
    public function search(Request $request, $city_slug = null, $category_slug = null)
    {
        $resolvedCategory = null;
        $resolvedCity = null;

        // Redirect ID-based URLs to Slug-based URLs for SEO (Format: search/city/category)
        if (($request->has('cat') || $request->has('category')) && $request->has('city') && ($request->type == 'listing' || !$request->has('type'))) {
            $catId = $request->cat ?? $request->category;
            $cityId = $request->city;

            $category = DB::table('pagecategories')->where('id', $catId)->first();
            $city = DB::table('cities')->where('id', $cityId)->first();

            if ($category && $city) {
                return redirect()->to(url($city->city_slug . '/' . $category->category_slug));
            }
        }

        $catId = $request->category_header ?? $request->category ?? $request->cat ?? 0;
        $cityId = $request->city_header ?? $request->city ?? 0;
        $areaId = (int) ($request->area ?? $request->area_header ?? 0);

        // SEO Slug Resolution
        if ($city_slug && !$cityId) {
            $resolvedCity = DB::table('cities')
                ->select('id', 'city_name', 'city_slug')
                ->where('city_slug', $city_slug)
                ->first();

            if ($resolvedCity) {
                $cityId = $resolvedCity->id;
            } else {
                // Check if it's a category (fallback for search/{category})
                $resolvedCategory = Pagecategory::select('id', 'category_name', 'category_slug', 'category_parent_id')
                    ->where('category_slug', $city_slug)
                    ->first();

                if ($resolvedCategory) {
                    $catId = $resolvedCategory->id;
                    $city_slug = null; // It was actually a category
                }
            }
        }

        if ($category_slug && !$catId) {
            $resolvedCategory = Pagecategory::select('id', 'category_name', 'category_slug', 'category_parent_id')
                ->where('category_slug', $category_slug)
                ->first();

            if ($resolvedCategory) {
                $catId = $resolvedCategory->id;
            } else {
                $areaCheck = DB::table('areas')->where('area_slug', $category_slug)->first();
                if ($areaCheck) {
                    return app(\App\Http\Controllers\PageController::class)->area($request, $city_slug, $category_slug);
                }
                
                // Check if it's a city (fallback for search/{city}/{something_else})
                $resolvedCity = DB::table('cities')
                    ->select('id', 'city_name', 'city_slug')
                    ->where('city_slug', $category_slug)
                    ->first();

                if ($resolvedCity) {
                    $cityId = $resolvedCity->id;
                }
            }
        }

        $category = ($resolvedCategory && (int) $resolvedCategory->id === (int) $catId)
            ? $resolvedCategory
            : Pagecategory::select('id', 'category_name', 'category_slug', 'category_parent_id')->find($catId);

        if (empty($cityId) || $cityId == 0) {
            $cityId = session('selected_city_id', 0);
        }

        $city = ($resolvedCity && (int) $resolvedCity->id === (int) $cityId)
            ? $resolvedCity
            : DB::table('cities')->select('cities.*')->where('id', $cityId)->first();

        // FULL-PAGE HTML CACHE for guests
        if (!auth()->check()) {
            $queryHash = md5(http_build_query($request->all()));
            $htmlCacheKey = "search_page_html_v7_{$cityId}_{$catId}_{$queryHash}";
            $cachedHtml = Cache::get($htmlCacheKey);
            if ($cachedHtml) {
                return response($cachedHtml)->header('Content-Type', 'text/html; charset=UTF-8');
            }
        }

        // If category not found, we provide a dummy category to prevent view crashes
        if (!$category) {
            // Fallback: Show empty results or redirect
            $page_data['city'] = $city;
            $page_data['category'] = (object)[
                'category_name' => 'General Search',
                'category_slug' => 'all',
                'id' => 0,
                'category_parent_id' => 0
            ];
            $page_data['category_header'] = $catId;
            $page_data['city_header'] = $cityId;
            $page_data['area_header'] = $areaId;
            
            // Use paginate(1) on an empty query to get a Paginator instance instead of a simple Collection
            $page_data['mypages'] = DB::table('pages')->where('id', 0)->paginate(50);
            
            $page_data['parent_categories'] = collect();
            $page_data['all_cities'] = Cache::remember('all_cities_approved_sorted_v3', 86400, function () {
                return DB::table('cities')
                    ->select('id', 'city_name', 'city_slug')
                    ->where('is_approved', 'Y')
                    ->orderBy('city_name', 'asc')
                    ->get();
            });
            $page_data['all_categories'] = collect();
            $page_data['filter_areas'] = DB::table('areas')->where('city_id', $cityId)->get();
            $page_data['filter_sort_by'] = "newest";
            
            $page_data['view_path'] = 'frontend.pages.searchview';
            return view('frontend.page_index_search', $page_data);
        }

        $filter_sort_by = $request->input('filter_sort_by', 'newest');
        $page_data['filter_sort_by'] = $filter_sort_by;

        $page_data['city']=$city;
        $page_data['category']=$category;

        $page_data['category_header']=$catId;
        $page_data['city_header']=$cityId;
        $page_data['area_header']=$areaId;

        $parentcategories = DB::table('pagecategories')->select('pagecategories.*')
            ->join('page_category','page_category.category_id','=','pagecategories.id')
            ->join('pages','pages.id','=','page_category.page_id')
            ->where('pages.item_status', 2)
            ->where('pagecategories.id', $category->category_parent_id ?? 0)
             ->when($cityId, fn($q) => $q->where('pages.city_id', $cityId))
            ->distinct('category_name')
            ->orderBy('category_name')->get();

        $parentcategory = Pagecategory::where('id', $category->category_parent_id ?? 0)->first();

        $subcategories = [];

        foreach ($parentcategories as $allcategoriesresult) {
            $subcategories[] = $allcategoriesresult->category_name;
        }
        $page_data['parent_categories']=$parentcategories;


        // Cache approved cities with selected columns — 24-hr cache, 0ms warm run
        $page_data['all_cities'] = Cache::remember('all_cities_approved_sorted_v3', 86400, function () {
            return DB::table('cities')
                ->select('id', 'city_name', 'city_slug')
                ->where('is_approved', 'Y')
                ->orderBy('city_name', 'asc')
                ->get();
        });

        $page_data['filter_areas'] = $cityId
            ? Cache::remember("search_filter_areas_{$cityId}_{$catId}_v2", 3600, function () use ($cityId, $catId) {
                $areas = DB::table('areas')
                    ->select('areas.id', 'areas.area_name', 'areas.area_slug')
                    ->join('pages', 'pages.area_id', '=', 'areas.id')
                    ->join('page_category', 'page_category.page_id', '=', 'pages.id')
                    ->leftJoin('pagecategories', 'pagecategories.id', '=', 'page_category.category_id')
                    ->where('pages.item_status', 2)
                    ->where('areas.city_id', $cityId)
                    ->where('pages.city_id', $cityId)
                    ->when($catId, function ($query) use ($catId) {
                        $query->where(function ($nested) use ($catId) {
                            $nested->where('page_category.category_id', $catId)
                                ->orWhere('pagecategories.category_parent_id', $catId);
                        });
                    })
                    ->distinct()
                    ->orderBy('areas.area_name')
                    ->get();

                if ($areas->isNotEmpty()) {
                    return $areas;
                }

                return DB::table('areas')
                    ->where('city_id', $cityId)
                    ->orderBy('area_name')
                    ->get();
            })
            : collect();

        if ($city && $category && $cityId && $catId) {
            $categoryName = $this->formatSeoLabel($category->category_name);
            $cityName = $this->formatSeoLabel($city->city_name);
            $page_data['meta_title'] = "Best {$categoryName} in {$cityName} | CityHangAround";
            $page_data['meta_description'] = app(CategoryCitySeoService::class)->description($category, $city);
            SEOMeta::setTitle($page_data['meta_title'], false);
            SEOMeta::setDescription($page_data['meta_description']);
            SEOMeta::setCanonical(URL::current());
        }

        /**
         * Category dropdown must not show empty categories for the selected city.
         * Source of truth: content_master (category_count rows).
         *
         * NOTE: We return parent categories only (category_parent_id NULL) because the UI
         * shows parent categories in the dropdown and tag list.
         */
        if (empty($cityId) || $cityId == 0) {
            // Global search: Get parent categories directly (super fast, no content_master scans)
            $page_data['all_categories'] = Cache::remember("search_all_parent_categories_v2", 3600, function () {
                return DB::table('pagecategories')
                    ->whereNull('category_parent_id')
                    ->orWhere('category_parent_id', 0)
                    ->orderBy('category_name', 'asc')
                    ->select('id', 'category_name', 'category_slug', 'category_parent_id')
                    ->get();
            });
        } else {
            // City-specific search: query content_master filtered by city_id
            $activeCategoryIds = Cache::remember("search_active_categories_{$cityId}", 3600, function () use ($cityId) {
                return DB::table('content_master')
                    ->where('source_type', 'category_count')
                    ->where('status', 'listing')
                    ->where('total_count', '>', 0)
                    ->where('city_id', $cityId)
                    ->select('category_id', 'parent_category_id')
                    ->get();
            });

            $ids = [];
            foreach ($activeCategoryIds as $row) {
                if ($row->category_id) $ids[] = $row->category_id;
                if ($row->parent_category_id) $ids[] = $row->parent_category_id;
            }
            $ids = array_unique($ids);

            if (empty($ids)) {
                $page_data['all_categories'] = Cache::remember("fallback_all_parent_categories", 3600, function() {
                    return DB::table('pagecategories')
                        ->where(fn($q) => $q->whereNull('category_parent_id')->orWhere('category_parent_id', 0))
                        ->select('id', 'category_name', 'category_slug', 'category_parent_id')
                        ->orderBy('category_name')
                        ->get();
                });
            } else {
                $page_data['all_categories'] = Cache::remember("search_parent_categories_v2_" . md5(implode(',', $ids)), 3600, function () use ($ids) {
                    return DB::table('pagecategories')
                        ->whereNull('category_parent_id')
                        ->whereIn('id', $ids)
                        ->orderBy('category_name', 'asc')
                        ->select('id', 'category_name', 'category_slug', 'category_parent_id')
                        ->get();
                });
            }
        }

        $cacheKey = "search_pages_" . md5(json_encode([
            $catId,
            $cityId,
            $areaId,
            $filter_sort_by,
            $request->get('page', 1)
        ]));

        $paid_items = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($catId, $cityId, $areaId, $filter_sort_by) {
            $paid_items_query = \App\Models\Page::with([
                'city:id,city_name,city_slug',
                'area:id,area_name,area_slug',
                'state:id,state_name',
                'categories:id,category_name,category_slug,category_parent_id,category_icon,category_banner'
            ])
            ->join('page_category', 'page_category.page_id', '=', 'pages.id')
            ->join('pagecategories', 'page_category.category_id', '=', 'pagecategories.id')
            ->where('pages.item_status', 2)
            ->when($cityId, fn($q) => $q->where('pages.city_id', $cityId))
            ->when($areaId, fn($q) => $q->where('pages.area_id', $areaId))
            ->where(function ($query) use ($catId) {
                $query->where('page_category.category_id', $catId)
                    ->orWhere('pagecategories.category_parent_id', $catId);
            })
            ->select('pages.*')
            ->distinct('pages.id')
            ->orderBy('pages.id', 'DESC');

            if($filter_sort_by == "newest")
            {
                $paid_items_query->orderBy('pages.created_at', 'DESC');
            }
            elseif($filter_sort_by == "oldest")
            {
                $paid_items_query->orderBy('pages.created_at', 'ASC');
            }
            elseif($filter_sort_by == "highest-rated")
            {
                $paid_items_query->orderBy('pages.item_average_rating', 'DESC');
            }
            elseif($filter_sort_by == "lowest-rated")
            {
                $paid_items_query->orderBy('pages.item_average_rating', 'ASC');
            }

            $results = $paid_items_query->orderBy('pages.id','DESC')->paginate(50);

            // Bulk fetch likes count
            $pageIds = $results->pluck('id')->toArray();
            $likeCounts = DB::table('page_likes')
                ->whereIn('page_id', $pageIds)
                ->selectRaw('page_id, COUNT(*) as cnt')
                ->groupBy('page_id')
                ->pluck('cnt', 'page_id')
                ->toArray();

            foreach ($results as $mypage) {
                $mypage->likes_count = $likeCounts[$mypage->id] ?? 0;
                $mypage->city_slug = $mypage->city?->city_slug;
                $mypage->area_slug = $mypage->area?->area_slug;
                $mypage->city_name = $mypage->city?->city_name;
                $mypage->area_name = $mypage->area?->area_name;
                $mypage->state_name = $mypage->state?->state_name;
                
                // Resolve last category info for single route compatibility in blade
                $lastCategory = $mypage->categories->last();
                $mypage->catslug = $lastCategory->category_slug ?? 'all';
                $mypage->catname = $lastCategory->category_name ?? 'Uncategorized';
            }

            return $results;
        });

        $querystringArray = [
            'area' => $areaId ?: null,
            'filter_sort_by' => $filter_sort_by,
        ];
        $paid_items->appends($querystringArray);
        $page_data['mypages'] = $paid_items;

        $page_data['view_path'] = 'frontend.pages.searchview';

        if (!auth()->check()) {
            $rendered = view('frontend.page_index_search', $page_data)->render();
            Cache::put($htmlCacheKey, $rendered, 300); // 5 minutes
            return response($rendered)->header('Content-Type', 'text/html; charset=UTF-8');
        }

        return view('frontend.page_index_search', $page_data);
    }

    private function formatSeoLabel($value): string
    {
        $value = trim(preg_replace('/\s+/', ' ', (string) $value));

        if ($value === '') {
            return $value;
        }

        return $value === mb_strtolower($value)
            ? mb_convert_case($value, MB_CASE_TITLE, 'UTF-8')
            : $value;
    }


    public function search_people(){
        $search_param = $_GET['search'];
        $page_data['peoples']= User::where('name','Like','%'.$search_param.'%')->limit(100)->get(); 
        $page_data['view_path'] = 'frontend.search.people';
        return view('frontend.index', $page_data);
    }

    public function search_post(){
        $search_param = $_GET['search'];
        $page_data['posts']= Posts::where('description','Like','%'.$search_param.'%')->limit(100)->get();
        $page_data['view_path'] = 'frontend.search.post';
        return view('frontend.index', $page_data);
    }

    public function search_video(){
        $search_param = $_GET['search'];
        $page_data['videos'] = Video::where('title','like',"%".$search_param."%")->where('privacy','public')->limit(100)->get();
        $page_data['view_path'] = 'frontend.search.video';
        return view('frontend.index', $page_data);
    }

    public function search_product(){
        $search_param = $_GET['search'];
        $page_data['products'] = Marketplace::where('title','like',"%".$search_param."%")->limit(100)->get();
        $page_data['view_path'] = 'frontend.search.product';
        return view('frontend.index', $page_data);
    }

    public function search_page(){
        $search_param = $_GET['search'];
        $page_data['pages'] = DB::table('pages')->select('pages.id','pages.item_slug','pages.logo','pages.title','pages.coverphoto','pages.user_id',
        'pages.description', 'pages.job','pages.location','pages.lifestyle',
        'cities.city_slug','areas.area_slug','pagecategories.category_slug','pagecategories.category_name')
        ->join('cities','cities.id','pages.city_id')
        ->join('areas','areas.id','pages.area_id')
        ->join('page_category','page_category.page_id','pages.id')
        ->leftjoin('page_likes','page_likes.page_id','pages.id')
        ->join(DB::raw('(select max(page_category.id) as max,max(page_category.category_id) as category_id,page_id
                    from page_category
                    inner join pagecategories on pagecategories.id =page_category.category_id  group by page_id) t1'), 
                function($join)
                {
                $join->on('t1.page_id', '=', 'pages.id');
                })
        ->join('pagecategories','t1.category_id','=','pagecategories.id')
        ->distinct('pages.id')
        ->orderBy('pages.id', 'DESC')
        ->where('pages.item_status',2)
        ->where('pages.title','like',"%".$search_param."%")->limit(100)->get();
        $page_data['view_path'] = 'frontend.search.page';
        return view('frontend.index', $page_data);
    }

    public function search_group(){
        $search_param = $_GET['search'];
        $page_data['groups'] = Group::where('title','like',"%".$search_param."%")->where('privacy','public')->limit(100)->get();
        $page_data['view_path'] = 'frontend.search.group';
        return view('frontend.index', $page_data);
    }

    public function search_event(){
        $search_param = $_GET['search'];
        $page_data['events'] = Event::where('title','like',"%".$search_param."%")->where('privacy','public')->limit(100)->get();
        $page_data['view_path'] = 'frontend.search.event';
        return view('frontend.index', $page_data);
    }
}
