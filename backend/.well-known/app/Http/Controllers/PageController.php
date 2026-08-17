<?php

namespace App\Http\Controllers;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Review;
use App\Models\Friendships;
use App\Models\Page;
use App\Models\Posts;
use App\Models\Page_like;
use App\Models\Media_files;
use App\Models\Albums;
use App\Models\Pagecategory;
use App\Models\ClaimListing;
use App\Models\FileUploader;
use App\Models\PageMedia;
use App\Models\Event;
use App\Models\Group;
use App\Models\State;
use App\Models\Area;
use App\Models\Marketplace;
use App\Models\OpeningHour;
use App\Models\IncompleteListing;
use App\Models\City;
use App\Models\ManageApproval;
use Illuminate\Http\Request;
use Session;
use Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Artesaos\SEOTools\Facades\SEOMeta;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Helpers\CityHelper;
use App\Services\UserActivityService;
use Illuminate\Support\Str;

class PageController extends Controller
{


    public function pages(Request $request)
    {
        SEOMeta::setTitle('Explore All Pages on City Hangaround – Find Local Deals & Businesses');
        SEOMeta::setDescription('Browse all pages on City Hangaround to discover local business listings, deals, events, and more.');
        SEOMeta::setKeywords('City Hangaround pages, local business directory, find deals online');
        SEOMeta::setCanonical(url()->full());

        $relations = ['city', 'area', 'state', 'categories', 'likes'];
        $filter_city = $request->input('city');
        $filter_area = $request->input('area', '0');
        $filter_sort_by = $request->input('filter_sort_by', 'newest');
        $perPage = 51;

        $cacheKey = 'pages_' . md5(json_encode([
            $filter_city,
            $filter_area,
            $filter_sort_by,
            request('page', 1)
        ]));

        $paginated = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($relations, $filter_city, $filter_area, $filter_sort_by, $perPage) {
            $query = Page::with($relations)
                ->where('item_status', 2)
                ->when($filter_city, fn($q) => $q->where('city_id', $filter_city))
                ->when($filter_area && $filter_area !== '0', fn($q) => $q->where('area_id', $filter_area))
                ->orderByDesc('item_featured'); //  Fast, simple and indexed

            // Sorting options
            switch ($filter_sort_by) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'popular':
                    $query->withCount('likes')->orderBy('likes_count', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }

            return $query->paginate($perPage);
        });

        $all_cities = CityHelper::getActiveCities();
        $all_categories = Pagecategory::whereNull('category_parent_id')
            ->whereHas('pages', fn($q) => $q->where('item_status', 2))
            ->orderBy('id')
            ->get();

        return view('frontend.index', [
            'mypages' => $paginated,
            'all_cities' => $all_cities,
            'all_categories' => $all_categories,
            'filter_city' => $filter_city,
            'filter_area' => $filter_area,
            'filter_sort_by' => $filter_sort_by,
            'view_path' => 'frontend.pages.pages'
        ]);
    }



    public function userpages(Request $request)
    {
        SEOMeta::setTitle('Explore All Pages on City Hangaround – Find Local Deals & Businesses');
        SEOMeta::setDescription('Browse all pages on City Hangaround to discover local business listings, deals, events, and more.');
        SEOMeta::setKeywords('City Hangaround pages, local business directory, find deals online');
        $canonicalUrl = URL::current();

        // If there's pagination beyond the first page, strip ?page=2, etc., from canonical
        // if ($request->has('page') && $request->input('page') > 1) {
        //     $canonicalUrl = $request->url(); // Only base URL without query params
        // }

        $canonicalUrl = url()->full(); // includes query params like ?filter_sort_by=newest&page=2

        SEOMeta::setCanonical($canonicalUrl);



        // Eager loading!
        $relations = ['city', 'area', 'state', 'categories', 'likes'];

        $page_data['mypages'] = Page::with($relations)
            ->where('user_id', auth()->user()->id)
            ->latest()
            ->paginate(52);


        //print_r($page_data['mypages']);exit;
        $userId = auth()->user()->id;


        $page_data['all_cities'] = CityHelper::getActiveCities();

        $page_data['all_categories'] = Pagecategory::whereNull('category_parent_id')
            ->whereHas('pages', fn($q) => $q->where('item_status', 2))
            ->orderBy('id')
            ->get();

        $page_data['incompleteListings'] = IncompleteListing::where('user_id', auth()->id())
            ->whereNotNull('data')
            ->where('data', '!=', '[]')
            ->latest()
            ->get();

        $page_data['view_path'] = 'frontend.pages.mypages';

        return view('frontend.index', $page_data);

    }

    public function page_delete(Request $request)
    {
        $id = $request->input('id');
        $page = Page::findOrFail($id);

        if ($page->user_id != auth()->id() && auth()->user()->user_role != 'admin') {
            abort(403);
        }

        $page->pageCategories()->detach();
        $page->delete();

        flash()->addSuccess(get_phrase('Page deleted successfully.'));
        return redirect()->back();
    }


    public function suggestedpages(Request $request)
    {
        SEOMeta::setTitle('Explore All Pages on City Hangaround – Find Local Deals & Businesses');
        SEOMeta::setDescription('Browse all pages on City Hangaround to discover local business listings, deals, events, and more.');
        SEOMeta::setKeywords('City Hangaround pages, local business directory, find deals online');
        $canonicalUrl = URL::current();

        // If there's pagination beyond the first page, strip ?page=2, etc., from canonical
        // if ($request->has('page') && $request->input('page') > 1) {
        //     $canonicalUrl = $request->url(); // Only base URL without query params
        // }

        $canonicalUrl = url()->full(); // includes query params like ?filter_sort_by=newest&page=2

        SEOMeta::setCanonical($canonicalUrl);


        // Eager loading!
        //     $relations = ['city', 'area', 'state', 'categories', 'likes'];
        //  $userId =auth()->user()->id;
        //    $page_data['suggestedpages'] = Page::with($relations)
        //     ->where('item_status', 2)
        //     ->whereDoesntHave('likes', function ($query) use ($userId) {
        //         $query->where('user_id', $userId);
        //     })
        //     ->paginate(52);

        $userId = auth()->user()->id;

        $page_data['suggestedpages'] = Page::with(['city', 'area', 'state', 'categories', 'likes'])
            ->where('item_status', 2)
            ->whereDoesntHave('likes', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where(function ($query) {
                $query->whereHas('user.subscriptions', function ($q) {
                    $q->where('status', 'active')
                        ->where('expires_at', '>=', now())
                        ->whereHas('subscription', function ($subQuery) {
                            $subQuery->where('offered_services', 'like', '%listings%');
                        });
                })
                    ->orWhereDoesntHave('user.subscriptions'); // Include pages with no subscription
            })
            ->paginate(52);




        $page_data['all_cities'] = CityHelper::getActiveCities();

        $page_data['all_categories'] = Pagecategory::whereNull('category_parent_id')
            ->whereHas('pages', fn($q) => $q->where('item_status', 2))
            ->orderBy('id')
            ->get();

        $page_data['incompleteListings'] = IncompleteListing::where('user_id', auth()->id())
            ->whereNotNull('data')
            ->where('data', '!=', '[]')
            ->latest()
            ->get();

        $page_data['view_path'] = 'frontend.pages.suggested';

        return view('frontend.index', $page_data);

    }


    function joinedpages(Request $request)
    {

        SEOMeta::setTitle('Explore All Pages on City Hangaround – Find Local Deals & Businesses');
        SEOMeta::setDescription('Browse all pages on City Hangaround to discover local business listings, deals, events, and more.');
        SEOMeta::setKeywords('City Hangaround pages, local business directory, find deals online');
        $canonicalUrl = URL::current();

        // If there's pagination beyond the first page, strip ?page=2, etc., from canonical
        // if ($request->has('page') && $request->input('page') > 1) {
        //     $canonicalUrl = $request->url(); // Only base URL without query params
        // }

        $canonicalUrl = url()->full(); // includes query params like ?filter_sort_by=newest&page=2

        SEOMeta::setCanonical($canonicalUrl);



        // Eager loading!
        $relations = ['city', 'area', 'state', 'categories', 'likes'];
        $userId = auth()->user()->id;
        $page_data['likedpage'] = Page::with($relations)
            ->where('item_status', 2)
            ->whereHas('likes', fn($q) => $q->where('user_id', auth()->id()))
            ->latest()
            ->paginate(52);



        $page_data['all_cities'] = CityHelper::getActiveCities();

        $page_data['all_categories'] = Pagecategory::whereNull('category_parent_id')
            ->whereHas('pages', fn($q) => $q->where('item_status', 2))
            ->orderBy('id')
            ->get();

        $page_data['incompleteListings'] = IncompleteListing::where('user_id', auth()->id())
            ->whereNotNull('data')
            ->where('data', '!=', '[]')
            ->latest()
            ->get();

        $page_data['view_path'] = 'frontend.pages.liked-page';

        return view('frontend.index', $page_data);
    }

    function incompletepages(Request $request)
    {

        SEOMeta::setTitle('Explore All Pages on City Hangaround – Find Local Deals & Businesses');
        SEOMeta::setDescription('Browse all pages on City Hangaround to discover local business listings, deals, events, and more.');
        SEOMeta::setKeywords('City Hangaround pages, local business directory, find deals online');
        $canonicalUrl = URL::current();

        // If there's pagination beyond the first page, strip ?page=2, etc., from canonical
        // if ($request->has('page') && $request->input('page') > 1) {
        //     $canonicalUrl = $request->url(); // Only base URL without query params
        // }

        $canonicalUrl = url()->full(); // includes query params like ?filter_sort_by=newest&page=2

        SEOMeta::setCanonical($canonicalUrl);




        $page_data['all_cities'] = CityHelper::getActiveCities();

        $page_data['all_categories'] = Pagecategory::whereNull('category_parent_id')
            ->whereHas('pages', fn($q) => $q->where('item_status', 2))
            ->orderBy('id')
            ->get();

        $page_data['incompleteListings'] = IncompleteListing::where('user_id', auth()->id())
            ->whereNotNull('data')
            ->where('data', '!=', '[]')
            ->latest()
            ->paginate(20);

        $page_data['view_path'] = 'frontend.pages.draft.index';

        return view('frontend.index', $page_data);
    }


    public function pages_old(Request $request)
    {
        SEOMeta::setTitle('Explore All Pages on City Hangaround – Find Local Deals & Businesses');
        SEOMeta::setDescription('Browse all pages on City Hangaround to discover local business listings, deals, events, and more.');
        SEOMeta::setKeywords('City Hangaround pages, local business directory, find deals online');
        SEOMeta::setCanonical(URL::current());



        // Eager loading!
        $relations = ['city', 'area', 'state', 'categories', 'likes'];

        $page_data['mypages'] = Page::with($relations)
            ->where('user_id', auth()->user()->id)
            ->latest()
            ->paginate(20);


        //print_r($page_data['mypages']);exit;
        $userId = auth()->user()->id;
        $page_data['suggestedpages'] = Page::with($relations)
            ->where('item_status', 2)
            ->whereDoesntHave('likes', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->paginate(20);

        $page_data['likedpage'] = Page::with($relations)
            ->where('item_status', 2)
            ->whereHas('likes', fn($q) => $q->where('user_id', auth()->id()))
            ->latest()
            ->take(10)
            ->paginate(20);



        $page_data['all_cities'] = CityHelper::getActiveCities();

        $page_data['all_categories'] = Pagecategory::whereNull('category_parent_id')
            ->whereHas('pages', fn($q) => $q->where('item_status', 2))
            ->orderBy('id')
            ->get();

        $page_data['incompleteListings'] = IncompleteListing::where('user_id', auth()->id())
            ->whereNotNull('data')
            ->where('data', '!=', '[]')
            ->latest()
            ->get();

        // Request Filters
        $filter_city = $request->input('city');
        $filter_area = $request->input('area', '0');
        $filter_sort_by = $request->input('filter_sort_by', 'newest');

        $page_data['filter_city'] = $filter_city;
        $page_data['filter_area'] = $filter_area;
        $page_data['filter_sort_by'] = $filter_sort_by;

        $page_data['view_path'] = 'frontend.pages.pages';

        return view('frontend.index', $page_data);
    }


    public function getStates($country_id)
    {
        $states = State::where('country_id', $country_id)->select('id', 'state_name')->get();
        return response()->json($states);
    }




    public function deleteIncompleteListing($id)
    {
        $incomplete = IncompleteListing::findOrFail($id);
        $incomplete->delete();

        return redirect()->route('pages')->with('success', 'Listing deleted successfully.');
    }



    public function area(Request $request, string $city_slug, string $area_slug)
    {
        $cache_ttl = now()->addMinutes(30); // Set cache time

        $city = Cache::remember("city_$city_slug", $cache_ttl, function () use ($city_slug) {
            return City::where('city_slug', $city_slug)->first();
        });



        if (!$city) {
            abort(404, 'City not found');
        }

        $area = Cache::remember("area_{$city->id}_$area_slug", $cache_ttl, function () use ($area_slug, $city) {
            return Area::where('area_slug', $area_slug)
                ->where('city_id', $city->id)
                ->first();
        });



        if (!$area) {
            abort(404, 'Area not found');
        }

        // SEO
        SEOMeta::setTitle("Best Businesses & Services in $area->area_name, $city->city_name – Find Top Listings");
        SEOMeta::setDescription("Explore the best businesses, services, and deals in $area->area_name, $city->city_name. Top-rated listings, reviews, and offers on Cityhangaround.");
        SEOMeta::setKeywords([
            "$city->city_name $area->area_name businesses",
            "best services in $area->area_name",
            "top local businesses in $area->area_name $city->city_name",
            "deals in $area->area_name",
            "$area->area_name directory"
        ]);
        // $canonicalUrl = URL::current();

        // // If there's pagination beyond the first page, strip ?page=2, etc., from canonical
        // if ($request->has('page') && $request->input('page') > 1) {
        //     $canonicalUrl = $request->url(); // Only base URL without query params
        // }

        $canonicalUrl = url()->full(); // includes query params like ?filter_sort_by=newest&page=2

        SEOMeta::setCanonical($canonicalUrl);

        // All cities with listings — Query Builder, only needed columns, 60-min cache
        $all_cities = Cache::remember('all_cities_sidebar_v2', now()->addMinutes(60), function () {
            return DB::table('cities')
                ->select('id', 'city_name', 'city_slug')
                ->whereExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('pages')
                        ->whereRaw('pages.city_id = cities.id')
                        ->where('pages.item_status', 2);
                })
                ->orderBy('city_name')
                ->get();
        });

        // Only parent categories for this city/area — pre-filtered at DB level, 60-min cache
        $all_categories = Cache::remember("parent_categories_area_{$city->id}_{$area->id}_v2", now()->addMinutes(60), function () use ($city, $area) {
            return DB::table('pagecategories')
                ->select('id', 'category_name', 'category_slug')
                ->whereNull('category_parent_id')
                ->whereExists(function ($q) use ($city, $area) {
                    $q->select(DB::raw(1))
                        ->from('page_category')
                        ->join('pages', 'pages.id', '=', 'page_category.page_id')
                        ->whereRaw('page_category.category_id = pagecategories.id')
                        ->where('pages.item_status', 2)
                        ->where('pages.city_id', $city->id)
                        ->where('pages.area_id', $area->id);
                })
                ->orderBy('id')
                ->get();
        });

        // Pre-fetch areas for the currently-selected filter city (avoids nested loops in Blade)
        $filterCityId = $request->input('city', '0');
        $filter_areas = ($filterCityId && $filterCityId !== '0')
            ? Cache::remember("areas_for_city_{$filterCityId}", now()->addMinutes(60), function () use ($filterCityId) {
                return DB::table('areas')
                    ->select('id', 'area_name', 'area_slug')
                    ->where('city_id', $filterCityId)
                    ->orderBy('area_name')
                    ->get();
              })
            : collect();

        // Enhanced filters
        $filter_sort_by = $request->input('filter_sort_by', 'newest');
        $filter_city = $request->input('city', '0');
        $filter_area = $request->input('area', '0');
        $filter_category = $request->input('category', '0');
        $filter_subcategory = $request->input('subcategory', '0');
        $filter_search = $request->input('search', '');
        $sort_column = 'created_at';
        $sort_direction = 'DESC';

        switch ($filter_sort_by) {
            case 'oldest':
                $sort_direction = 'ASC';
                break;
            case 'highest-rated':
                $sort_column = 'item_average_rating';
                $sort_direction = 'DESC';
                break;
            case 'lowest-rated':
                $sort_column = 'item_average_rating';
                $sort_direction = 'ASC';
                break;
        }



        $pages = Page::with(['city', 'area', 'state', 'pageCategories'])
            ->where('item_status', 2)
            ->where('city_id', $city->id)
            ->where('area_id', $area->id);

        // Apply additional filters
        if (!empty($filter_city) && $filter_city !== "0") {
            $pages->whereHas('pageCategories', function ($q) use ($filter_city) {
                $q->where('city_id', $filter_city);
            });
        }
        if (!empty($filter_area) && $filter_area !== "0") {
            $pages->whereHas('pageCategories', function ($q) use ($filter_area) {
                $q->where('area_id', $filter_area);
            });
        }
        if (!empty($filter_category) && $filter_category !== "0") {
            $pages->whereHas('pageCategories', function ($q) use ($filter_category) {
                $q->where('category_id', $filter_category);
            });
        }

        if (!empty($filter_subcategory) && $filter_subcategory !== "0") {
            $pages->whereHas('pageCategories', function ($q) use ($filter_subcategory) {
                $q->where('category_id', $filter_subcategory);
            });
        }

        if (!empty($filter_search)) {
            $pages->where(function ($q) use ($filter_search) {
                $q->where('title', 'LIKE', "%{$filter_search}%")
                    ->orWhere('description', 'LIKE', "%{$filter_search}%")
                    ->orWhere('address', 'LIKE', "%{$filter_search}%");
            });
        }

        $pages = $pages->orderByDesc('item_featured') // 👈 yahi ab priority hai
            ->orderBy('id', 'DESC')        // fallback sort
            ->paginate(50)
            ->appends([
                'filter_sort_by' => $filter_sort_by,
                'category' => $filter_category,
                'subcategory' => $filter_subcategory,
                'city' => $filter_city,
                'area' => $filter_area,
                'search' => $filter_search
            ]);






        return view('frontend.index_page_area', [
            'city'              => $city,
            'area'              => $area,
            'all_cities'        => $all_cities,
            'all_categories'    => $all_categories,
            'filter_areas'      => $filter_areas,      // pre-fetched for selected city
            'filter_sort_by'    => $filter_sort_by,
            'filter_city'       => $filter_city,
            'filter_area'       => $filter_area,
            'filter_category'   => $filter_category,
            'filter_subcategory'=> $filter_subcategory,
            'filter_search'     => $filter_search,
            'total_pages'       => $pages->total(),    // avoids ->total() call in Blade partial
            'mypages'           => $pages,
            'view_path'         => 'frontend.pages.area',
        ]);
    }


    public function category(Request $request, string $category_slug)
    {
        // Cache category object
        $category = Cache::remember("category_{$category_slug}", now()->addHours(6), function () use ($category_slug) {
            return Pagecategory::where('category_slug', $category_slug)->firstOrFail();
        });
        $page_data['category'] = $category;

        // All cities with active listings — lean Query Builder, 60-min cache
        $page_data['all_cities'] = Cache::remember('all_cities_sidebar_category', now()->addHours(1), function () {
            return DB::table('cities')
                ->select('id', 'city_name', 'city_slug')
                ->whereExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('pages')
                        ->whereRaw('pages.city_id = cities.id')
                        ->where('pages.item_status', 2);
                })
                ->orderBy('city_name')
                ->get();
        });

        // Parent-only categories — pre-filtered at DB level so Blade needs no @if checks
        $page_data['all_categories'] = Cache::remember('all_parent_categories_sidebar', now()->addHours(1), function () {
            return DB::table('pagecategories')
                ->select('id', 'category_name', 'category_slug')
                ->where(function ($q) {
                    $q->where('category_parent_id', 0)->orWhereNull('category_parent_id');
                })
                ->whereExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('page_category')
                        ->join('pages', 'pages.id', '=', 'page_category.page_id')
                        ->whereRaw('page_category.category_id = pagecategories.id')
                        ->where('pages.item_status', 2);
                })
                ->orderByDesc('id')
                ->get();
        });

        // Request Filters
        $filter_city     = $request->input('city');
        $filter_area     = $request->input('area', '0');
        $filter_sort_by  = $request->input('filter_sort_by', 'newest');
        $filter_category = $request->input('category_filter');

        // Pre-fetch areas for selected city (avoids triple-nested @foreach in Blade)
        $filter_areas = ($filter_city && $filter_city !== '0')
            ? Cache::remember("areas_for_city_{$filter_city}_v2", now()->addHours(1), function () use ($filter_city) {
                return DB::table('areas')
                    ->select('id', 'area_name', 'area_slug')
                    ->where('city_id', $filter_city)
                    ->orderBy('area_name')
                    ->get();
              })
            : collect();

        $page_data['filter_city']     = $filter_city;
        $page_data['filter_area']     = $filter_area;
        $page_data['filter_sort_by']  = $filter_sort_by;
        $page_data['filter_category'] = $filter_category;
        $page_data['filter_areas']    = $filter_areas;

        // Key for caching filtered listings
        $cacheKey = "pages_category_{$category->id}_city_{$filter_city}_area_{$filter_area}_sort_{$filter_sort_by}_page_" . $request->get('page', 1);

        $mypages = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($category, $filter_city, $filter_area, $filter_sort_by) {

            $query = Page::with(['city', 'area', 'state', 'pageCategories'])
                ->where('item_status', 2)
                ->whereHas('pageCategories', function ($q) use ($category) {
                    $q->where('category_id', $category->id);
                });

            if (!empty($filter_city)) {
                $query->where('city_id', $filter_city);
            }

            if (!empty($filter_area) && $filter_area !== "0") {
                $query->where('area_id', $filter_area);
            }

            // 👇 sabse pehle featured (priority) wale
            $query->orderByDesc('item_featured');

            // 👇 then apply sort logic
            switch ($filter_sort_by) {
                case 'oldest':
                    $query->orderBy('created_at', 'ASC');
                    break;
                case 'highest-rated':
                    $query->orderBy('item_average_rating', 'DESC');
                    break;
                case 'lowest-rated':
                    $query->orderBy('item_average_rating', 'ASC');
                    break;
                default:
                    $query->orderBy('created_at', 'DESC');
            }

            return $query->orderBy('id', 'DESC')->paginate(50);
        });

        $mypages->appends([
            'filter_sort_by' => $filter_sort_by,
            'filter_city' => $filter_city,
            'filter_area' => $filter_area,
        ]);

        $page_data['mypages'] = $mypages;

        // $parentcategory = Cache::remember("parent_category_$category->id", 3600, function () use ($category, $city) {
        //     return Pagecategory::where('id', $category->category_parent_id)->first();
        // });
        // // SEO
        // SEOMeta::setTitle('Near by top ' . $category->category_name . ', listing, deals, offers');
        // SEOMeta::setDescription('Near by top ' . $category->category_name . ' deals, offers');
        // SEOMeta::setCanonical(URL::current());


        $parentcategory = Cache::remember("parent_category_$category->id", 3600, function () use ($category) {
            return Pagecategory::find($category->category_parent_id);
        });

        $pageNumber = $request->get('page', 1);

        $categoryName = $category->category_name;
        $parentName = $parentcategory ? $parentcategory->category_name : null;

        $title = "Top $categoryName";
        $description = "Top $categoryName";

        if ($parentName) {
            $title .= ", $parentName";
            $description .= ", $parentName";
        }

        if ($pageNumber > 1) {
            $title .= ", page $pageNumber";
            $description .= ", page $pageNumber";
        }

        $title .= ", listing, deals, offers Near by";
        $description .= ", listing, deals, offers Near by";

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);

        // $canonicalUrl = $pageNumber > 1 
        //     ? $request->fullUrl()
        //     : URL::current() . (http_build_query($request->except('page')) ? '?' . http_build_query($request->except('page')) : '');

        // SEOMeta::setCanonical($canonicalUrl);

        $canonicalUrl = url()->full(); // includes query params like ?filter_sort_by=newest&page=2

        SEOMeta::setCanonical($canonicalUrl);

        //get video
        $videoInfo = Page::where('item_status', 2)
            ->whereNotNull('featured_video')  // Ensure the featured_video is not null
            ->where('featured_video', '!=', ''); // Ensure the featured_video is not an empty string

        // Sorting logic
        $videoInfo = $videoInfo->orderByDesc('item_featured')
            ->orderBy('id', 'DESC')
            ->paginate(50);  // Get the paginated result

        // Pass the paginated data to the view
        $page_data['videoInfo'] = $videoInfo;


        $page_data['total_pages'] = $mypages->total(); // avoids ->total() twice in Blade
        $page_data['view_path'] = 'frontend.pages.category';
        return view('frontend.page_index', $page_data);
    }

    public function checkMatch(Request $request)
    {
        $page = Page::with(['city', 'area', 'pageCategories']) // eager load relationships
            ->where('title', $request->name)
            ->where('address', $request->address)
            ->when($request->city, function ($q) use ($request) {
                return $q->where('city_id', $request->city);
            })
            ->first();

        if ($page) {
            $category = $page->pageCategories()->latest('id')->first(); // get latest category

            return response()->json([
                'exists' => true,
                'hasPhone' => !empty($page->item_phone),
                'city_slug' => $page->city?->city_slug,
                'area_slug' => $page->area?->area_slug,
                'category_slug' => $category?->category_slug,
                'item_slug' => $page->item_slug,
            ]);
        }

        return response()->json(['exists' => false]);
    }


    public function category_old(Request $request, string $category_slug)
    {

        //print_r($request->city);exit;
        $category = Pagecategory::where('category_slug', $category_slug)->first();
        $page_data['category'] = $category;
        $page_data['all_cities'] = CityHelper::getActiveCities();


        $page_data['all_category_cities'] = DB::table('cities')->select('cities.*')
            ->join('pages', 'pages.city_id', 'cities.id')
            ->join('page_category', 'page_category.page_id', 'pages.id')
            ->join('pagecategories', 'page_category.category_id', '=', 'pagecategories.id')
            ->distinct('cities.id')
            ->where('pages.item_status', 2)
            ->where(function ($query) use ($category) {
                $query->where('page_category.category_id', $category->id);
            })
            ->orderBy('cities.city_name', 'asc')->get();

        // Dropdown categories must not show empty categories for selected city (use content_master as source of truth)
        $filter_city_for_categories = $request->city ?: session('selected_city_id');
        $page_data['all_categories'] = DB::table('pagecategories as pc')
            ->select('pc.*')
            ->where(function ($q) {
                $q->whereNull('pc.category_parent_id')
                  ->orWhere('pc.category_parent_id', 0);
            })
            ->whereExists(function ($q) use ($filter_city_for_categories) {
                $q->select(DB::raw(1))
                    ->from('content_master as cm')
                    ->where('cm.source_type', 'category_count')
                    ->where('cm.status', 'listing')
                    ->where('cm.total_count', '>', 0)
                    ->when($filter_city_for_categories, fn($qq) => $qq->where('cm.city_id', $filter_city_for_categories))
                    ->whereRaw('(cm.category_id = pc.id OR cm.parent_category_id = pc.id)');
            })
            ->orderBy('pc.category_name', 'asc')
            ->get();


        $pageLiked = [];
        // $likepages = Page_like::where('user_id',auth()->user()->id)->get();
        // foreach($likepages as $likepage){
        //     $likepageid = $likepage->page_id;
        //     array_push($pageLiked,$likepageid);
        // }
        if ($category) {
            SEOMeta::setTitle('Near by top ' . $category->category_name . ', listing, deals, offers');
            SEOMeta::setDescription('Near by top ' . $category->category_name . ' deals, offers');
            SEOMeta::setCanonical(URL::current());
            //SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);



            $filter_city = empty($request->city) ? null : $request->city;
            $filter_area = empty($request->area) ? null : $request->area;
            if ($filter_area == "" || is_null($filter_area)) {
                $filter_area = "0";
            }
            $page_data['filter_city'] = $filter_city;
            $page_data['filter_area'] = $filter_area;

            //echo $filter_area;exit;

            $paid_items_query = DB::table('pages')->select(
                'pages.id',
                'pages.item_slug',
                'pages.logo',
                'pages.title',
                'cities.city_slug',
                'areas.area_slug'
                ,
                'cities.city_name',
                'areas.area_name',
                'states.state_name',
                'pages.created_at'
            )
                ->join('cities', 'cities.id', 'pages.city_id')
                ->join('areas', 'areas.id', 'pages.area_id')
                ->join('states', 'states.id', 'pages.state_id')
                ->join('page_category', 'page_category.page_id', 'pages.id')
                ->where('pages.item_status', 2)
                ->where(function ($query) use ($category) {
                    $query->where('page_category.category_id', $category->id);
                })
                ->distinct('page_category.id');

            // // filter paid listings city
            if (!empty($filter_city)) {
                $paid_items_query->where('pages.city_id', $filter_city);
            }

            // // filter paid listings city
            if (!empty($filter_area)) {
                $paid_items_query->where('pages.area_id', $filter_area);
            }

            $filter_sort_by = empty($request->filter_sort_by) ? "newest" : $request->filter_sort_by;
            $page_data['filter_sort_by'] = $filter_sort_by;
            if ($filter_sort_by == "newest") {
                $paid_items_query->orderBy('pages.created_at', 'DESC');
            } elseif ($filter_sort_by == "oldest") {
                $paid_items_query->orderBy('pages.created_at', 'ASC');
            } elseif ($filter_sort_by == "highest-rated") {
                $paid_items_query->orderBy('pages.item_average_rating', 'DESC');
            } elseif ($filter_sort_by == "lowest-rated") {
                $paid_items_query->orderBy('pages.item_average_rating', 'ASC');
            }
            $paid_items = $paid_items_query->orderBy('pages.id', 'DESC')->paginate(50);

            $querystringArray = [
                'filter_sort_by' => $filter_sort_by,
                'filter_city' => $filter_city,
                'filter_area' => $filter_area,
            ];
            $paid_items->appends($querystringArray);
            $page_data['mypages'] = $paid_items;

            //print_r($paid_items);exit;


            $page_data['view_path'] = 'frontend.pages.category';
            return view('frontend.page_index', $page_data);

        } else {
            abort(404);
        }

    }

    public function city(Request $request, $city_slug)
    {
        $city = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();
        $page_data['city'] = $city;


        $page_data['all_cities'] = CityHelper::getActiveCities();




        if (!is_null($city)) {


            SEOMeta::setTitle('Explore ' . $city->city_name . ' – Top Business Listings, Deals, Events, Blogs  & trending videos');
            SEOMeta::setDescription('Find the best businesses, latest offers, and trending local events in ' . $city->city_name . ' Stay connected with blogs, videos, and city updates!');
            SEOMeta::setKeywords([
                "$city->city_name business listings",
                "best deals in $city->city_name",
                "restaurants in $city->city_name",
                "local businesses in $city->city_name",
                "shopping in $city->city_name",
                "things to do in $city->city_name"
            ]);
            //      $canonicalUrl = URL::current();

            // // If there's pagination beyond the first page, strip ?page=2, etc., from canonical
            // if ($request->has('page') && $request->input('page') > 1) {
            //     $canonicalUrl = $request->url(); // Only base URL without query params
            // }

            // SEOMeta::setCanonical($canonicalUrl);

            $canonicalUrl = url()->full(); // includes query params like ?filter_sort_by=newest&page=2

            SEOMeta::setCanonical($canonicalUrl);

            $page_data['filter_city'] = $city->id;
            $page_data['filter_area'] = $request->input('area', '0');
            $page_data['filter_category'] = $request->input('category', '0');
            $page_data['filter_sort_by'] = $request->input('filter_sort_by', 'newest');
            $page_data['all_cities'] = CityHelper::getActiveCities(); // Already there, but standard naming

            /**
             * Category dropdown must not show empty categories for this city.
             * Source of truth: content_master (category_count rows).
             */
            $page_data['all_categories'] = Cache::remember("cityguide_parent_categories_city_{$city->id}_v1", 1800, function () use ($city) {
                return DB::table('pagecategories as pc')
                    ->select('pc.id', 'pc.category_name', 'pc.category_slug', 'pc.category_parent_id')
                    ->whereNull('pc.category_parent_id')
                    ->whereExists(function ($q) use ($city) {
                        $q->select(DB::raw(1))
                            ->from('content_master as cm')
                            ->where('cm.source_type', 'category_count')
                            ->where('cm.status', 'listing')
                            ->where('cm.city_id', $city->id)
                            ->where('cm.total_count', '>', 0)
                            ->whereRaw('(cm.category_id = pc.id OR cm.parent_category_id = pc.id)');
                    })
                    ->orderBy('pc.category_name', 'asc')
                    ->get();
            });

            $categories = DB::table('pagecategories')
                ->select('pagecategories.*', 'pages.item_featured')
                ->join('page_category', 'page_category.category_id', '=', 'pagecategories.id')
                ->join('pages', function ($join) use ($city) {
                    $join->on('page_category.page_id', '=', 'pages.id')
                        ->where('pages.item_status', 2)
                        ->where('pages.city_id', $city->id);
                })
                ->whereNull('pagecategories.category_parent_id')
                ->distinct()
                ->orderBy('pages.item_featured', 'desc')
                ->orderBy('pagecategories.id', 'DESC')
                ->get();


            //print_r($categories);exit;

            $page_data['categories'] = $categories;

            // Timeline Feed (Left Column) - Works for guests & authenticated users
            $postsQuery = \App\Models\Posts::where('posts.status', 'active')
                ->where('posts.report_status', '0')
                ->join('users', 'posts.user_id', '=', 'users.id');

            if (auth()->check()) {
                $userId = auth()->id();
                $postsQuery->where(function ($query) use ($userId) {
                    $query->whereJsonContains('users.friends', [$userId])
                        ->where('posts.privacy', '!=', 'private')
                        ->orWhere('posts.user_id', $userId)
                        ->orWhere('posts.privacy', 'public');
                });
            } else {
                $postsQuery->where('posts.privacy', 'public');
            }

            $posts = $postsQuery->whereIn('posts.publisher', ['post', 'page', 'group', 'event'])
                ->where('posts.publisher', '!=', 'video_and_shorts')
                ->select('posts.*', 'users.name', 'users.photo', 'users.friends', 'posts.created_at as created_at')
                ->orderByDesc('posts.created_at')
                ->orderByDesc('posts.post_id')
                ->take(5)
                ->get();

            $page_data['posts'] = $posts;

            // Trending Listings for right sidebar (city-filtered)
            $page_data['recentBusinesses'] = \App\Models\Page::with(['city', 'area', 'categories'])
                ->where('item_status', 2)
                ->where('city_id', $city->id)
                ->orderBy('item_featured', 'desc')
                ->orderBy('id', 'desc')
                ->limit(6)
                ->get();

            // Trending Events for right sidebar (city-filtered)
            $page_data['recentEvents'] = \App\Models\Event::with(['city', 'area', 'categories'])
                ->where('event_status', 2)
                ->where('city_id', $city->id)
                ->orderBy('id', 'desc')
                ->limit(6)
                ->get();

            $page_data['view_path'] = 'frontend.pages.city';
            return view('frontend.page_index_city', $page_data);
        } else {

            abort(404);
        }

    }

    public static function getpagesbycategoryid($categoryid, $cityid, $area_id = 0, $sort_by = 'newest')
    {
        $query = DB::table('pages')
            ->select(
                'pages.id',
                'pages.item_slug',
                'pages.logo',
                'pages.title',
                'cities.city_slug',
                'areas.area_slug',
                'cities.city_name',
                'areas.area_name',
                'states.state_name'
            )
            ->join('cities', 'cities.id', '=', 'pages.city_id')
            ->join('areas', 'areas.id', '=', 'pages.area_id')
            ->join('states', 'states.id', '=', 'pages.state_id')
            ->join('page_category', 'page_category.page_id', '=', 'pages.id')
            ->join('pagecategories', 'page_category.category_id', '=', 'pagecategories.id')
            ->where('pages.item_status', 2)
            ->where(function ($q) use ($categoryid) {
                $q->where('page_category.category_id', $categoryid)
                    ->orWhere('pagecategories.category_parent_id', $categoryid);
            })
            ->where('pages.city_id', $cityid);

        if ($area_id > 0) {
            $query->where('pages.area_id', $area_id);
        }

        if ($sort_by == 'oldest') {
            $query->orderBy('pages.id', 'asc');
        } elseif ($sort_by == 'highest-rated') {
            $query->orderBy('pages.item_featured', 'desc');
        } else {
            $query->orderBy('pages.id', 'desc');
        }

        return $query->orderBy('pages.item_featured', 'desc')
            ->distinct()
            ->limit(4)
            ->get();
    }

    public function categoryByCityArea(Request $request, $city_slug, $category_slug, $area_slug)
    {
        // 1. Fetch Category, City, Area using separate, light caches
        $category = Cache::remember(
            "category_slug_{$category_slug}",
            3600,
            fn() =>
            Pagecategory::where('category_slug', $category_slug)->select('id', 'category_name', 'category_slug', 'category_parent_id')->firstOrFail()
        );

        $city = Cache::remember(
            "city_slug_{$city_slug}",
            3600,
            fn() =>
            City::where('city_slug', $city_slug)->select('id', 'city_name', 'city_slug')->firstOrFail()
        );

        $area = Cache::remember(
            "area_slug_{$city_slug}_{$area_slug}",
            3600,
            fn() =>
            $city->areas()->where('area_slug', $area_slug)->select('id', 'area_name', 'area_slug')->firstOrFail()
        );

        // 2. Cache sidebar data together (rarely changes)
        $cacheKey = "categoryByCityArea_sidebar_{$city_slug}_{$category_slug}_{$area_slug}";

        $sidebarData = Cache::remember($cacheKey, 3600, function () use ($category, $city, $area) {
            // All cities with relevant pages — lean Query Builder, only needed columns
            $all_cities = DB::table('cities')
                ->select('id', 'city_name', 'city_slug')
                ->whereExists(function ($q) use ($category) {
                    $q->select(DB::raw(1))
                        ->from('pages')
                        ->join('page_category', 'page_category.page_id', '=', 'pages.id')
                        ->whereRaw('pages.city_id = cities.id')
                        ->where('pages.item_status', 2)
                        ->where(function ($q2) use ($category) {
                            $q2->where('page_category.category_id', $category->id)
                               ->orWhereExists(function ($q3) use ($category) {
                                   $q3->select(DB::raw(1))
                                       ->from('pagecategories as pc')
                                       ->whereRaw('pc.id = page_category.category_id')
                                       ->where('pc.category_parent_id', $category->id);
                               });
                        });
                })
                ->orderBy('city_name')
                ->get();

            // Pre-fetch matching page IDs
            $pageIds = Page::where('item_status', 2)
                ->where('city_id', $city->id)
                ->where('area_id', $area->id)
                ->whereHas('categories', function ($q) use ($category) {
                    $q->where('pagecategories.id', $category->id)
                        ->orWhere('pagecategories.category_parent_id', $category->id);
                })
                ->pluck('id');

            // Parent-only categories in sidebar — pre-filtered so Blade needs no @if check
            $all_categories = DB::table('pagecategories')
                ->select('id', 'category_name', 'category_slug')
                ->whereNull('category_parent_id')
                ->whereExists(function ($q) use ($pageIds) {
                    $q->select(DB::raw(1))
                        ->from('page_category')
                        ->whereRaw('page_category.category_id = pagecategories.id')
                        ->whereIn('page_category.page_id', $pageIds->toArray());
                })
                ->orderBy('category_name')
                ->get();

            // Parent categories (optional sidebar breadcrumb)
            $parent_categories = Pagecategory::where('id', $category->category_parent_id)
                ->whereHas('pages', function ($q) use ($city, $area) {
                    $q->where('item_status', 2)
                        ->where('city_id', $city->id)
                        ->where('area_id', $area->id);
                })
                ->orderBy('category_name')
                ->select('id', 'category_name', 'category_slug')
                ->get();

            return compact('all_cities', 'all_categories', 'parent_categories');
        });

        extract($sidebarData); // make $all_cities, $all_categories, $parent_categories available

        // 3. SEO metadata (dynamic — don't cache)
        SEOMeta::setTitle("Best {$category->category_name} in {$area->area_name}, {$city->city_name} – Top Listings & Deals");
        SEOMeta::setDescription("Find the best {$category->category_name} services in {$area->area_name}, {$city->city_name} with top-rated businesses, exclusive deals, and customer reviews. Discover trusted service providers near you on City Hangaround!");
        SEOMeta::setKeywords([
            "$city->city_name $area->area_name $category->category_name",
            "best $category->category_name in $area->area_name $city->city_name",
            "top $category->category_name businesses in $area->area_name $city->city_name",
            "local $category->category_name services in $area->area_name",
            "$category->category_name deals in $area->area_name"
        ]);
        //  $canonicalUrl = URL::current();

        // // If there's pagination beyond the first page, strip ?page=2, etc., from canonical
        // if ($request->has('page') && $request->input('page') > 1) {
        //     $canonicalUrl = $request->url(); // Only base URL without query params
        // }

        $canonicalUrl = url()->full(); // includes query params like ?filter_sort_by=newest&page=2

        SEOMeta::setCanonical($canonicalUrl);


        // Pre-fetch areas for selected filter city (avoids triple-nested @foreach in Blade)
        $filterCityIdForAreas = $request->input('city', '0');
        $filter_areas = ($filterCityIdForAreas && $filterCityIdForAreas !== '0')
            ? Cache::remember("areas_for_city_{$filterCityIdForAreas}_v2", 3600, function () use ($filterCityIdForAreas) {
                return DB::table('areas')
                    ->select('id', 'area_name', 'area_slug')
                    ->where('city_id', $filterCityIdForAreas)
                    ->orderBy('area_name')
                    ->get();
              })
            : collect();

        // Enhanced filters
        $filter_city = $request->input('city', '0');
        $filter_area = $request->input('area', '0');
        $filter_category = $request->input('category', '0');
        $filter_subcategory = $request->input('subcategory', '0');
        $filter_search = $request->input('search', '');
        $filter_sort_by = $request->input('filter_sort_by', 'newest');
        $perPage = 51;

        $cacheKey = 'category_pages_' . md5(json_encode([
            $category->id,
            $city->id,
            $filter_area,
            $filter_category,
            $filter_subcategory,
            $filter_search,
            $filter_sort_by,
            $request->get('page', 1)
        ]));

        $mypages = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($category, $city, $filter_area, $filter_category, $filter_subcategory, $filter_search, $filter_sort_by, $perPage) {
            $query = Page::with([
                'city:id,city_name,city_slug',
                'area:id,area_name,area_slug',
                'state:id,state_name',
                'categories:id,category_name,category_slug,category_parent_id'
            ])
                ->where('item_status', 2)
                ->where('city_id', $city->id)
                ->whereHas('categories', function ($q) use ($category) {
                    $q->where('pagecategories.id', $category->id)
                        ->orWhere('pagecategories.category_parent_id', $category->id);
                });
            if (!empty($filter_city) && $filter_city !== "0") {
                $query->where('city_id', $filter_city);
            }


            if (!empty($filter_area) && $filter_area !== "0") {
                $query->where('area_id', $filter_area);
            }

            // Category filter
            if (!empty($filter_category) && $filter_category !== "0") {
                $query->whereHas('categories', function ($q) use ($filter_category) {
                    $q->where('pagecategories.id', $filter_category);
                });
            }

            // Subcategory filter
            if (!empty($filter_subcategory) && $filter_subcategory !== "0") {
                $query->whereHas('categories', function ($q) use ($filter_subcategory) {
                    $q->where('pagecategories.id', $filter_subcategory);
                });
            }

            // Search filter
            if (!empty($filter_search)) {
                $query->where(function ($q) use ($filter_search) {
                    $q->where('title', 'LIKE', "%{$filter_search}%")
                        ->orWhere('description', 'LIKE', "%{$filter_search}%")
                        ->orWhere('address', 'LIKE', "%{$filter_search}%");
                });
            }

            // First priority based on featured flag
            $query->orderByDesc('item_featured');

            // Then by user selected sort
            switch ($filter_sort_by) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'highest-rated':
                    $query->orderBy('item_average_rating', 'desc');
                    break;
                case 'lowest-rated':
                    $query->orderBy('item_average_rating', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }

            return $query->paginate($perPage)->appends([
                //  'city' => $filter_city,
                'area' => $filter_area,
                'category' => $filter_category,
                'subcategory' => $filter_subcategory,
                'search' => $filter_search,
                'filter_sort_by' => $filter_sort_by
            ]);
        });

        // Step 6: Return
        return view('frontend.page_index_area_city_category', compact(
            'city',
            'area',
            'category',
            'parent_categories',
            'all_cities',
            'all_categories',
            'filter_areas',
            'mypages',
            'filter_sort_by',
            'filter_city',
            'filter_area',
            'filter_category',
            'filter_subcategory',
            'filter_search'
        ) + [
            'view_path'   => 'frontend.pages.categorycityarea',
            'total_pages' => $mypages->total(),
        ]);
    }

    public function categoryByCity(Request $request, $category_slug, $city_slug)
    {
        $category = Cache::remember("category_slug_$category_slug", 3600, function () use ($category_slug) {
            return Pagecategory::where('category_slug', $category_slug)->first();
        });

        $city = Cache::remember("city_slug_$city_slug", 3600, function () use ($city_slug) {
            return City::where('city_slug', $city_slug)->first();
        });

        if (!$category || !$city) {
            abort(404);
        }

        $page_data['category'] = $category;
        $page_data['city'] = $city;

        // All cities with relevant pages — lean Query Builder, 60-min cache
        $page_data['all_cities'] = Cache::remember('all_cities_with_pages_v2', 3600, function () {
            return DB::table('cities')
                ->select('id', 'city_name', 'city_slug')
                ->whereExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('pages')
                        ->whereRaw('pages.city_id = cities.id')
                        ->where('pages.item_status', 2);
                })
                ->orderBy('city_name')
                ->get();
        });

        // Parent-only categories for this city/category — pre-filtered at DB level
        $page_data['all_categories'] = Cache::remember("city_cat_sidebar_{$city->id}_{$category->id}", 3600, function () use ($city, $category) {
            return DB::table('pagecategories')
                ->select('id', 'category_name', 'category_slug')
                ->whereNull('category_parent_id')
                ->whereExists(function ($q) use ($city, $category) {
                    $q->select(DB::raw(1))
                        ->from('page_category')
                        ->join('pages', 'pages.id', '=', 'page_category.page_id')
                        ->whereRaw('page_category.category_id = pagecategories.id')
                        ->where('pages.item_status', 2)
                        ->where('pages.city_id', $city->id)
                        ->where(function ($q2) use ($category) {
                            $q2->where('page_category.category_id', $category->id)
                               ->orWhereExists(function ($q3) use ($category) {
                                   $q3->select(DB::raw(1))
                                       ->from('pagecategories as pc')
                                       ->whereRaw('pc.id = page_category.category_id')
                                       ->where('pc.category_parent_id', $category->id);
                               });
                        });
                })
                ->orderBy('id')
                ->get();
        });

        $parentcategories = Pagecategory::select('pagecategories.*')
            ->join('page_category', 'page_category.category_id', '=', 'pagecategories.id')
            ->join('pages', 'pages.id', '=', 'page_category.page_id')
            ->where('pages.item_status', 2)
            ->where('pagecategories.id', $category->category_parent_id)
            ->where('pages.city_id', $city->id)
            ->distinct('category_name')
            ->orderBy('category_name')
            ->get();

        $page_data['parent_categories'] = $parentcategories;

        $parentcategory = Pagecategory::find($category->category_parent_id);

        SEOMeta::setKeywords("{$city->city_name} {$category->category_name}, best {$category->category_name} in {$city->city_name}, top {$category->category_name} businesses in {$city->city_name}, {$category->category_name} services in {$city->city_name}, local {$category->category_name} listings in {$city->city_name}");

        if ($parentcategory) {
            SEOMeta::setTitle("Best {$category->category_name} {$parentcategory->category_name} in {$city->city_name} – Top Businesses & Services");
        } else {
            SEOMeta::setTitle("Best {$category->category_name} in {$city->city_name} – Top Businesses & Services");
        }

        SEOMeta::setDescription("Discover the best {$category->category_name} in {$city->city_name} with top-rated businesses, exclusive deals, and customer reviews. Find the perfect service near you on City Hangaround!");
        //$canonicalUrl = URL::current();

        // If there's pagination beyond the first page, strip ?page=2, etc., from canonical
        // if ($request->has('page') && $request->input('page') > 1) {
        //     $canonicalUrl = $request->url(); // Only base URL without query params
        // }

        // SEOMeta::setCanonical($canonicalUrl);

        $canonicalUrl = url()->full(); // includes query params like ?filter_sort_by=newest&page=2

        SEOMeta::setCanonical($canonicalUrl);

        //  Canonical Tag Logic for Pagination
        $currentPage = $request->get('page');
        if ($currentPage && $currentPage > 1) {
            // Point all paginated pages to main city page (SEO best practice)
            SEOMeta::setCanonical(url('/' . $city->city_slug));
        } else {
            // Page 1 or no pagination
            SEOMeta::setCanonical(URL::current());
        }

        // Enhanced filters
        $filter_sort_by = $request->filter_sort_by ?? 'newest';
        $filter_city = $request->input('city', '0');
        $filter_area = $request->input('area', '0');
        $filter_category = $request->input('category', '0');
        $filter_subcategory = $request->input('subcategory', '0');
        $filter_search = $request->input('search', '');

        // Pre-fetch areas for selected filter city (avoids triple-nested @foreach in Blade)
        $filterCityIdForAreas = $request->input('city', '0');
        $page_data['filter_areas'] = ($filterCityIdForAreas && $filterCityIdForAreas !== '0')
            ? Cache::remember("areas_sidebar_city_{$filterCityIdForAreas}", 3600, function () use ($filterCityIdForAreas) {
                return DB::table('areas')
                    ->select('id', 'area_name', 'area_slug')
                    ->where('city_id', $filterCityIdForAreas)
                    ->orderBy('area_name')
                    ->get();
              })
            : collect();

        $page_data['filter_sort_by'] = $filter_sort_by;
        $page_data['filter_city']    = $filter_city;
        $page_data['filter_area']    = $filter_area;
        $page_data['filter_category']    = $filter_category;
        $page_data['filter_subcategory'] = $filter_subcategory;
        $page_data['filter_search']      = $filter_search;

        $pagesQuery = Page::with([
            'city:id,city_name,city_slug',
            'area:id,area_name,area_slug',
            'state:id,state_name',
            'pagecategories:id,category_name,category_slug,category_parent_id',
            'user.userSubscriptions.subscription'
        ])
            ->withCount('likes')
            ->where('item_status', 2)
            ->where('city_id', $city->id)
            ->whereHas('pagecategories', function ($q) use ($category) {
                $q->where('pagecategories.id', $category->id)
                    ->orWhere('pagecategories.category_parent_id', $category->id);
            });

        // Apply additional filters
        if (!empty($filter_area) && $filter_area !== "0") {
            $pagesQuery->where('area_id', $filter_area);
        }

        if (!empty($filter_category) && $filter_category !== "0") {
            $pagesQuery->whereHas('pagecategories', function ($q) use ($filter_category) {
                $q->where('pagecategories.id', $filter_category);
            });
        }

        if (!empty($filter_subcategory) && $filter_subcategory !== "0") {
            $pagesQuery->whereHas('pagecategories', function ($q) use ($filter_subcategory) {
                $q->where('pagecategories.id', $filter_subcategory);
            });
        }

        if (!empty($filter_search)) {
            $pagesQuery->where(function ($q) use ($filter_search) {
                $q->where('title', 'LIKE', "%{$filter_search}%")
                    ->orWhere('description', 'LIKE', "%{$filter_search}%")
                    ->orWhere('address', 'LIKE', "%{$filter_search}%");
            });
        }

        // 🔀 Sorting from UI
        switch ($filter_sort_by) {
            case 'oldest':
                $pagesQuery->orderBy('created_at', 'asc');
                break;
            case 'highest-rated':
                $pagesQuery->orderBy('item_average_rating', 'desc');
                break;
            case 'lowest-rated':
                $pagesQuery->orderBy('item_average_rating', 'asc');
                break;
            default:
                $pagesQuery->orderBy('created_at', 'desc');
        }

        //  Top priority featured pages first
        $pagesQuery->orderBy('item_featured', 'desc');

        //  Final pagination
        $pages = $pagesQuery->paginate(50)->appends([
            'filter_sort_by' => $filter_sort_by,
            'area' => $filter_area,
            'category' => $filter_category,
            'subcategory' => $filter_subcategory,
            'search' => $filter_search,
        ]);

        // ➕ Send to view
        $page_data['mypages']     = $pages;
        $page_data['total_pages'] = $pages->total();
        $page_data['view_path']   = 'frontend.pages.categorycity';

        return view('frontend.page_filter_index', $page_data);
    }

    public function resumeIncompleteListing($id)
    {
        $page_data['incompleteListing'] = IncompleteListing::findOrFail($id);

        $page_data['listing'] = $page_data['incompleteListing'];

        $data = $page_data['incompleteListing']->data;

        if (is_string($data)) {
            $data = json_decode($data);
        } elseif (is_array($data)) {
            $data = (object) $data;
        }



        $page_data['listing']->data = $data;




        //print_r( $page_data['listing']);exit;

        $page_data['all_cities'] = CityHelper::getActiveCities();
        $page_data['printable_categories'] = DB::table('pagecategories')->where('category_parent_id', null)
            ->get();
        $page_data['countries'] = DB::table('countries')->select('countries.*')
            ->where('id', 101)->get();


        $countryId = $page_data['listing']->data->country ?? null;

        if ($countryId) {
            $page_data['all_states'] = DB::table('states')
                ->select('states.*')
                ->where('country_id', $countryId)
                ->get();
        } else {
            $page_data['all_states'] = collect(); // empty collection
        }

        $serviceCategoryIds = $page_data['listing']->data->servicecategory ?? [];


        $page_data['preselectedCategories'] = DB::table('categories')->whereIn('id', $serviceCategoryIds)
            ->get(['id', 'product_category_name']);

        $serviceStateIds = $page_data['listing']->data->servicestate ?? [];


        $page_data['preselectedStates'] = DB::table('states')->whereIn('id', $serviceStateIds)
            ->get(['id', 'state_name']);

        $serviceCityIds = $page_data['listing']->data->servicecity ?? [];


        $page_data['preselectedCity'] = DB::table('cities')->whereIn('id', $serviceCityIds)
            ->get(['id', 'city_name']);

        $serviceAreaIds = $page_data['listing']->data->servicearea ?? [];


        $page_data['preselectedArea'] = DB::table('areas')->whereIn('id', $serviceAreaIds)
            ->get(['id', 'area_name']);


        //print_r( $page_data['preselectedStates']);exit;
        $page_data['parent'] = DB::table('pagecategories')
            ->where('pagecategories.category_parent_id', null)
            // ->orWhereNull('pagecategories.category_parent_id')
            ->get();


        //print_r($page_data['parent']);exit;
        $page_data['view_path'] = 'frontend.pages.draft.create_incomplete_page';
        return view('frontend.index', $page_data);
    }

    public function create()
    {


        $page_data['all_cities'] = CityHelper::getActiveCities();
        $page_data['printable_categories'] = DB::table('pagecategories')->where('category_parent_id', null)
            ->get();
        $page_data['countries'] = DB::table('countries')->select('countries.*')
            ->where('id', 101)->get();
        $page_data['all_states'] = DB::table('states')->select('states.*')
            ->where('country_id', 101)->get();
        $page_data['parent'] = DB::table('pagecategories')
            ->where('pagecategories.category_parent_id', null)
            // ->orWhereNull('pagecategories.category_parent_id')
            ->get();

        SEOMeta::setTitle('Create Free Local Business Page | CityHangaround');
        SEOMeta::setDescription('Easily create a free page for your local business on CityHangaround. Share details, photos, services, and contact info to attract local customers and boost your online presence.');
        SEOMeta::setCanonical(URL::current());

        $page_data['listing'] = IncompleteListing::create([
            'user_id' => auth()->id(),
            'data' => [],
        ]);
        //print_r($page_data['parent']);exit;
        $page_data['view_path'] = 'frontend.pages.create_page';
        return view('frontend.form_index', $page_data);

    }

    public function jsonGetParentCategories()
    {

        $parents = DB::table('pagecategories')
            ->where('pagecategories.category_parent_id', null)
            // ->orWhereNull('pagecategories.category_parent_id')
            ->get()->toJson();

        return response()->json($parents);
    }

    public function destroy($id)
    {
        $media = PageMedia::find($id);

        if (!$media) {
            return response()->json(['success' => false, 'message' => 'Media not found.']);
        }

        $media->delete();

        return response()->json(['success' => true, 'message' => 'Media deleted successfully!']);
    }

    public function edit($id)
    {


        if (isset($_GET['delete']) && $_GET['delete'] == 'yes' && isset($_GET['id'])) {
            Blog::find($_GET['id'])->delete();
            flash()->addSuccess('Blog deleted successfully');
            return redirect()->back();
        }

        $page_data['all_cities'] = CityHelper::getActiveCities();
        $page_data['page'] = \App\Models\Page::find($id);
        $page_data['printable_categories'] = DB::table('pagecategories')->where('category_parent_id', null)
            ->get();

        $page_data['all_states'] = DB::table('states')->select('states.*')
            ->where('country_id', 101)->get();
        $page_data['all_cities'] = DB::table('cities')->select('cities.*')
            ->where('state_id', $page_data['page']->state_id)->get();
        $page_data['all_areas'] = DB::table('areas')->select('areas.*')
            ->where('city_id', $page_data['page']->city_id)->get();




        $page_data['all_service_city'] = DB::table('cities')
            ->select('cities.*')
            ->where(function ($query) use ($page_data) {
                $cityIds = explode(',', $page_data['page']->service_offered_state); // Convert to array
                foreach ($cityIds as $cityId) {
                    $query->orWhereRaw("FIND_IN_SET(?, state_id)", [$cityId]);
                }
            })
            ->get();


        $page_data['all_service_areas'] = DB::table('areas')
            ->select('areas.*')
            ->where(function ($query) use ($page_data) {
                $cityIds = explode(',', $page_data['page']->service_offered_city); // Convert to array
                foreach ($cityIds as $cityId) {
                    $query->orWhereRaw("FIND_IN_SET(?, city_id)", [$cityId]);
                }
            })
            ->get();


        $page_data['all_countries'] = DB::table('countries')
            ->select('countries.*')
            ->where(function ($query) use ($page_data) {
                $cityIds = explode(',', $page_data['page']->service_offered_country); // Convert to array
                foreach ($cityIds as $cityId) {
                    $query->orWhereRaw("FIND_IN_SET(?, id)", [$cityId]);
                }
            })
            ->get();

        $page_data['all_tag_categories'] = DB::table('pagecategories')
            ->select('pagecategories.*')
            ->where(function ($query) use ($page_data) {
                $cityIds = explode(',', $page_data['page']->category_id); // Convert to array
                foreach ($cityIds as $cityId) {
                    $query->orWhereRaw("FIND_IN_SET(?, id)", [$cityId]);
                }
            })
            ->get();

        //print_r($page_data['all_tag_categories']);exit;





        // print_r($page_data['page']->service_offered_city);exit;
        $page_data['parent'] = DB::table('pagecategories')
            ->where('pagecategories.category_parent_id', null)
            // ->orWhereNull('pagecategories.category_parent_id')
            ->get();

        $page_data['page_faq'] = DB::table('pag_faq')
            ->where('page_id', $id)
            // ->orWhereNull('pagecategories.category_parent_id')
            ->get();
        $page_data['countries'] = DB::table('countries')->select('countries.*')
            ->where('id', 101)->get();

        $page_data['openingHours'] = \App\Models\OpeningHour::where('page_id', $id)
            ->get()
            ->keyBy('day');

        //print_r($page_data['openingHours'] );exit;

        $page_data['media'] = PageMedia::where('page_id', $id)->get();
        $page_data['view_path'] = 'frontend.pages.edit-modal';
        return view('frontend.index', $page_data);
    }



    public function dataAjax(Request $request)
    {
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $data = DB::table("pagecategories")
                ->select("id", "category_name")
                ->where('category_name', 'LIKE', "$search%")
                ->where('category_parent_id', '!=', null)
                ->get();
        }
        return response()->json($data);
    }


    public function CountrydataAjax(Request $request)
    {
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $data = DB::table("countries")
                ->select("id", "country_name")
                ->where('country_name', 'LIKE', "$search%")
                ->where('id', 101)
                ->get();
        }
        return response()->json($data);
    }

    public function StatedataAjax(Request $request)
    {
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $data = DB::table("states")
                ->select("id", "state_name")
                ->where('state_name', 'LIKE', "$search%")
                ->get();
        }
        return response()->json($data);
    }


    public function CitydataAjax(Request $request)
    {
        $query = DB::table("cities")->select("id", "city_name");

        if ($request->has('search')) {
            $query->where('city_name', 'LIKE', "%" . $request->search . "%");
        }

        // Check if selectedStates exists and is an array
        if ($request->has('selectedStates') && is_array($request->selectedStates)) {
            $query->whereIn('state_id', $request->selectedStates); // Use whereIn for filtering
        }

        return response()->json($query->get());
    }



    public function AreaDataAjax(Request $request)
    {
        $query = DB::table("areas")->select("id", "area_name");

        if ($request->has('search')) {
            $query->where('area_name', 'LIKE', "%" . $request->search . "%");
        }

        // Filter areas based on selected cities
        if ($request->has('selectedCities') && is_array($request->selectedCities)) {
            $query->whereIn('city_id', $request->selectedCities); // Assuming `city_id` is the column in the `areas` table
        }

        return response()->json($query->get());
    }







    public function jsonGetAreasByCity(int $city_id)
    {

        $areas = DB::table("areas")
            ->select("id", "area_name")
            ->where('city_id', $city_id)
            ->get()->toJson();

        return response()->json($areas);
    }


    public function jsonGetCategoriesByCity(int $city_id)
    {

        $areas = DB::table("pagecategories")
            ->select("pagecategories.*")
            ->join('page_category', 'page_category.category_id', '=', 'pagecategories.id')
            ->join('pages', 'pages.id', 'page_category.page_id')
            ->join('cities', 'cities.id', 'pages.city_id')
            ->distinct('pages.id')
            ->where('pages.item_status', 2)
            ->where('pages.city_id', $city_id)
            ->get()->toJson();

        return response()->json($areas);
    }

    public function jsonGetAreasByCityforitem(int $city_id)
    {


        $areas = DB::table("areas")
            ->select("areas.*")
            ->join('cities', 'cities.id', 'areas.city_id')
            ->join('pages', 'pages.area_id', 'areas.id')
            ->join('page_category', 'page_category.page_id', 'pages.id')
            ->join('pagecategories', 'page_category.category_id', '=', 'pagecategories.id')
            ->distinct('pages.id')
            ->where('pages.item_status', 2)
            ->where('areas.city_id', $city_id)
            ->where('pages.city_id', $city_id)
            ->get()->toJson();

        return response()->json($areas);
    }

    public function jsonGetAllCities()
    {
        $cities = CityHelper::getActiveCities();
        return response()->json($cities);
    }

    public function jsonGetCitiesByState(int $state_id)
    {

        $cities = DB::table("cities")
            ->select("id", "city_name")
            ->where('state_id', $state_id)
            ->get()->toJson();

        return response()->json($cities);
    }


    public function jsonGetCategories()
    {

        $parents = DB::table('pagecategories')->select('pagecategories.id', 'pagecategories.category_name', 'cat.category_name as parent')
            ->leftjoin('pagecategories as cat', 'cat.id', '=', 'pagecategories.category_parent_id')->orderby('id', 'asc')
            ->get()->toJson();

        return response()->json($parents);
    }

    public function jsonGetSubcategoriesByCategory(int $category_id)
    {
        try {
            $subcategories = DB::table("pagecategories")
                ->select("pagecategories.*")
                ->join('page_category', 'page_category.category_id', '=', 'pagecategories.id')
                ->join('pages', 'pages.id', 'page_category.page_id')
                ->distinct('pages.id')
                ->where('pages.item_status', 2)
                ->where('pagecategories.category_parent_id', $category_id)
                ->get();

            return response()->json($subcategories);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch subcategories'], 500);
        }
    }


    public function search(Request $request)
    {
        $query = $request->input('q');

        $categories = \App\Models\Category::where('product_category_name', 'LIKE', "%{$query}%")
            ->select('id', 'product_category_name')
            ->limit(10)
            ->get();

        return response()->json($categories);
    }

    public function selected(Request $request)
    {
        $categoryIds = $request->input('ids', []);

        $categories = \App\Models\Category::whereIn('id', $categoryIds)
            ->select('id', 'product_category_name')
            ->get();

        return response()->json($categories);
    }

    public function createCategoryFromSelect2(Request $request)
    {
        $duplicateCount = DB::table('pagecategories')
            ->where('category_name', $request->category_name)
            ->count();

        if ($duplicateCount === 0) {
            $category = new Pagecategory();

            $category->category_name = $request->category_name;
            $category->category_slug = clean_slug($request->category_name);
            $category->category_icon = "";
            $category->category_parent_id = 0; // Or set dynamically if needed
            $category->category_description = "";
            $category->category_createdby = auth()->user()->id;

            $category->save();

            if (auth()->user()) {
                app(UserActivityService::class)->log(auth()->user()->id, 'category_suggest', 'page_category', $category->id, $category->id);
            }
            return response()->json([
                'id' => $category->id,
                'category_name' => $category->category_name
            ]);
        } else {
            // Return existing category if duplicate found (optional fallback)
            $existing = DB::table('pagecategories')
                ->where('category_name', $request->category_name)
                ->first();

            return response()->json([
                'id' => $existing->id,
                'category_name' => $existing->category_name,
                'duplicate' => true
            ]);
        }
    }



    public function store(Request $request)
    {

        //print_r($request->category);exit;
        $user = auth()->user();
        $rules = array(
            'image' => 'mimes:jpeg,jpg,png,gif|nullable',
            'name' => 'required|max:255',
            'parent' => 'required',
            'category' => 'nullable',
            'item_phone' => ['nullable', 'regex:/^(\+?\d{1,3}[-. ]?)?\d{10}$/'],
            'state' => 'required|not_in:0',
            'city' => 'required|not_in:0',
            'area' => 'required|not_in:0',
            'featured_video' => 'nullable|mimes:mp4,avi,mkv,flv,webm|max:512000', // Allow up to 500MB for video
            'featured_thumbnail' => 'nullable|mimes:jpeg,jpg,png,gif|max:102400',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()->all()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->image && !empty($request->image)) {

            $file_name = FileUploader::upload($request->image, 'public/storage/pages/logo', 250);

        }


        if ($request->Proof_of_Ownership && !empty($request->Proof_of_Ownership)) {

            $proof_of_ownership_file_name = FileUploader::upload($request->Proof_of_Ownership, 'public/storage/pages/logo', 250);

        }

        $count = DB::table('pages')->where('title', trim($request->name))
            ->where('address', trim($request->address))
            ->where('city_id', $request->city)
            ->count();

        if ($count == 0) {


            $title = 'listing';
            $approval = ManageApproval::where('title', $title)->first();

            if ($approval && $approval->status == 1) {
                // Approval status is ON
                $item_status = 2;
                $item_featured = 0;
                $item_featured_by_admin = 0;

            } elseif (auth()->check() && auth()->user()->user_role == 'admin') {
                // Status is OFF but user is admin
                $item_status = 2;
                $item_featured = 1;
                $item_featured_by_admin = 1;

            } else {
                //Status is OFF and user is not admin
                $item_status = 1;
                $item_featured = 0;
                $item_featured_by_admin = 0;
            }

            // if(auth()->user()->user_role=="admin"){

            //     $item_status=2;
            //     $item_featured=1;
            //     $item_featured_by_admin=1;

            // }
            // else{

            //     $item_status=1;
            //     $item_featured=0;
            //     $item_featured_by_admin=0;
            // }


            $page_slug = preg_replace("/[^A-Za-z0-9 ]/", '', $request->name);


            $multiSelectArray = $request->category ?? [];
            // Single select ID to be added
            $parent_id = $request->parent;

            // Check if the ID is already in the array
            if (!in_array($parent_id, $multiSelectArray)) {
                $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
            }

            if ($request->servicestate) {
                $service_state_ids = implode(',', $request->servicestate);
            } else {
                $service_state_ids = "";
            }

            if ($request->servicecity) {
                $service_city_ids = implode(',', $request->servicecity);
            } else {
                $service_city_ids = "";
            }


            if ($request->servicecategory) {
                $product_categories_ids = implode(',', $request->servicecategory);
            } else {
                $product_categories_ids = "";
            }

            if ($request->servicearea) {
                $service_offeres_areas_ids = implode(',', $request->servicearea);
            } else {
                $service_offeres_areas_ids = "";
            }

            if ($request->servicecountry) {
                $service_offeres_countries_ids = implode(',', $request->servicecountry);
            } else {
                $service_offeres_countries_ids = "";
            }



            // Upload the featured video if provided
            // Handle featured video upload (if any) to the same "logo" directory
            if ($request->hasFile('featured_video')) {
                $featured_video_name = FileUploader::upload($request->featured_video, 'public/storage/pages/logo', 512000);  // 500MB for video
            } else {
                $featured_video_name = null; // If no video is uploaded, set it to null
            }

            // Handle featured thumbnail upload (if any) to the same "logo" directory
            if ($request->hasFile('featured_thumbnail')) {
                $featured_thumbnail_name = FileUploader::upload($request->featured_thumbnail, 'public/storage/pages/logo', 102400);  // 100MB for thumbnail
            } else {
                $featured_thumbnail_name = null; // If no thumbnail is uploaded, set it to null
            }
            //end video



            $categories_id = implode(',', $multiSelectArray);
            $page = new Page();
            $page->user_id = auth()->user()->id;
            $page->title = $request->name;
            $page->item_slug = str_slug($page_slug);
            $page->address = $request->address;
            $page->state_id = $request->state;
            $page->city_id = $request->city;
            $page->area_id = $request->area;
            $page->pincode = $request->pincode;
            $page->category_id = $categories_id;

            $page->item_status = $item_status;
            $page->item_featured = $item_featured;
            $page->item_featured_by_admin = $item_featured_by_admin;
            $page->item_website = $request->website;
            $page->item_email = $request->business_email;
            $page->item_whatsapp = $request->business_whatsapp_url;
            $page->item_phone = $request->item_phone;
            $page->item_lat = $request->item_lat;
            $page->item_lng = $request->item_lng;
            $page->item_social_facebook = $request->facebook;
            $page->item_social_twitter = $request->twitter;
            $page->item_social_linkedin = $request->linkedIn;
            $page->item_youtube_id = $request->youtube_video_id;







            $page->insta_link = $request->instalink;
            $page->product_categories_ids = $product_categories_ids;

            $page->why_visit_us = $request->visitus;
            $page->our_story = $request->our_story;
            $page->year_of_establishment = $request->yrofest;
            $page->service_offeres_areas_ids = $service_offeres_areas_ids;




            $page->country_id = $request->country;
            //$page->open_hours = $request->open_hours;
            $page->service_offered_country = $service_offeres_countries_ids;
            $page->service_offered_state = $service_state_ids;
            $page->service_offered_city = $service_city_ids;

            $page->policy = $request->policy;

            $page->description = $request->description;
            if ($request->image && !empty($request->image)) {
                $page->logo = $file_name;
            }
            if ($request->Proof_of_Ownership && !empty($request->Proof_of_Ownership)) {
                $page->ownership_document = $proof_of_ownership_file_name;
            }

            $activeSubscription = $user->activeSubscription()->with('subscription')->first();

            if ($activeSubscription && $activeSubscription->subscription && Str::contains($activeSubscription->subscription->offered_services, 'listings')) {
                $durations = json_decode($activeSubscription->subscription->area_durations, true);

                $cityDays = $durations['listings']['city'] ?? 0;
                $areaDays = $durations['listings']['area'] ?? 0;

                $subscriptionStart = Carbon::parse($activeSubscription->created_at ?? now());


                $priorityEnd = $subscriptionStart->copy()->addDays(max($cityDays, $areaDays));

                if ($cityDays > 0)
                    $page->priority_until_city = $subscriptionStart->copy()->addDays($cityDays);
                if ($areaDays > 0)
                    $page->priority_until_area = $subscriptionStart->copy()->addDays($areaDays);
                if ($priorityEnd->isFuture())
                    $page->item_featured = 1;
            }

            //start video
            $page->featured_video = $featured_video_name; // Save the video file name
            $page->featured_thumbnail = $featured_thumbnail_name; // Save the thumbnail file name
            //end video
            $done = $page->save();
            if ($done) {


                foreach ($multiSelectArray as $category_id) {
                    $data = array(
                        'category_id' => $category_id,
                        "page_id" => $page->id
                    );
                    $row = DB::table('page_category')->insertGetId($data);


                }
                $faqs = $request->input('faqs', []);
                foreach ($faqs as $faq) {
                    if (!empty($faq['question']) && !empty($faq['answer'])) {
                        $data = array(
                            'question' => $faq['question'] ?? null,
                            'answer' => $faq['answer'] ?? null,
                            "page_id" => $page->id
                        );
                        $row = DB::table('pag_faq')->insertGetId($data);
                    }
                }


                $uploadedFiles = [];

                if ($request->hasFile('media')) {
                    $uploadedFiles = []; // Initialize array

                    foreach ($request->file('media') as $file) {
                        // Determine file type
                        $mimeType = $file->getMimeType();
                        // Upload the file without resizing
                        $fileName = FileUploader::upload($file, 'public/storage/pages/media');


                        $fileType = str_starts_with($mimeType, 'image') ? 'image' : (str_starts_with($mimeType, 'video') ? 'video' : 'other');

                        // Store file info in the database
                        $uploadedFiles[] = [
                            'page_id' => $page->id,
                            'file' => $fileName,
                            'file_type' => $fileType, // Add file type
                            'createdAt' => now()
                        ];
                    }

                    // Insert into database
                    DB::table('page_media')->insert($uploadedFiles);
                }


                $openingHours = $request->input('opening_hours', []);

                foreach ($openingHours as $day => $data) {
                    OpeningHour::create([
                        'page_id' => $page->id,
                        'day' => $day,
                        'open' => isset($data['closed']) ? null : $data['open'],
                        'close' => isset($data['closed']) ? null : $data['close'],
                        'closed' => isset($data['closed']) && $data['closed'] == '1',
                    ]);
                }

                $pagelike = new Page_like();
                $pagelike->page_id = $page->id;
                $pagelike->user_id = auth()->user()->id;
                $pagelike->role = 'admin';
                $done = $pagelike->save();

                $slug_count = DB::table('pages')->select('pages.id')
                    ->where('pages.item_slug', str_slug($request->name))->count();
                ;

                if ($slug_count > 1) {

                    DB::table('pages')->where('id', $page->id)
                        ->update(array('item_slug' => DB::raw('concat("' . str_slug($request->name) . '",' . '-' . $row . ')')));
                }
                if ($done) {
                    $user = User::find(auth()->user()->id);


                    // Delete the draft entry for this listing
                    IncompleteListing::where('user_id', auth()->id())
                        ->where('listing_id', $request->listing_id)
                        ->delete();

                    if (auth()->user()) {
                        app(UserActivityService::class)->log(auth()->user()->id, 'listing', 'page', $page->id, $page->id);
                    }


                    Mail::to($user->email)->queue(new WelcomeMail($user));
                    Session::flash('success_message', get_phrase('Page Created Successfully'));

                    if ($request->ajax()) {
                        return response()->json([
                            'status' => 'success',
                            'message' => get_phrase('Page Created Successfully'),
                            'redirect_url' => route('pages')
                        ]);
                    }

                    return redirect()->route('pages');
                    //return json_encode(array('reload' => 1));
                }


            }
        } else {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => get_phrase('Duplicate Entry')
                ], 400);
            }

            Session::flash('success_message', get_phrase('Duplicate Entry'));
            return redirect()->route('pages.create');
            //return json_encode(array('reload' => 0));
        }

    }

    public function showIncompleteListings()
    {

        $page_data['incompleteListings'] = IncompleteListing::with('user')->latest()->get();
        $page_data['view_path'] = 'frontend.pages.draft.index';
        return view('frontend.index', $page_data);

        //return view('listings.incomplete', compact('incompleteListings'));
    }


    public function saveIncomplete(Request $request)
    {
        $userId = auth()->id();
        $formData = $request->except(['_token', '_method', 'listing_id']);
        $listingId = $request->input('listing_id');

        if ($listingId) {
            $draft = IncompleteListing::where('id', $listingId)
                ->where('user_id', $userId)
                ->first();

            if ($draft) {
                // Check if new data is different from existing
                if ($draft->data !== $formData) {
                    $draft->update([
                        'data' => $formData,
                        'listing_id' => $listingId
                    ]);
                }

                return response()->json([
                    'status' => 'unchanged_or_updated',
                    'listing_id' => $draft->id,
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Draft not found',
                ], 404);
            }
        } else {
            // Create new draft
            $draft = IncompleteListing::create([
                'user_id' => $userId,
                'data' => $formData,
                'listing_id' => null, // or some initial value
            ]);

            return response()->json([
                'status' => 'created',
                'listing_id' => $draft->id,
            ]);
        }
    }







    public function storecategories(Request $request)
    {


        $duplicatecount = DB::table('pagecategories')->where('category_name', $request->category_name)
            ->count();

        if ($duplicatecount == 0) {



            $category = new Pagecategory();




            $category->category_name = $request->category_name;
            $category->category_slug = strtolower(str_replace(' ', '-', $request->category_name));
            $category->category_icon = "";
            $category->category_parent_id = $request->category_parent_id;
            $category->category_description = "";
            $category->category_createdby = auth()->user()->id;

            $category->save();

            if (auth()->user()) {
                app(UserActivityService::class)->log(auth()->user()->id, 'category_suggest', 'page_category', $category->id, $category->id);
            }

            \Session::flash('flash_message', __('Created'));
            \Session::flash('flash_type', 'success');
            return response()->json(1);
        } else {
            return response()->json("duplicate");
        }
        //return redirect()->route('user.items.create');
    }

    public function storeareas(Request $request)
    {

        $duplicatecount = DB::table('areas')->where('city_id', $request->cityid)
            ->where('area_name', $request->area_name)
            ->count();

        if ($duplicatecount == 0) {
            $cityid = $request->cityid;
            $area_name = $request->area_name;
            $area_slug = str_slug($request->area_name);



            $CreatedOn = Carbon::now();
            $data = array(
                'city_id' => $cityid,
                "area_name" => $area_name,
                "area_slug" => $area_slug,
                "created_at" => $CreatedOn,
                "updated_at" => $CreatedOn,
                "createdBy" => auth()->user()->id,
                "is_approved" => "N",
            );


            $row = DB::table('areas')->insertGetId($data);
            return response()->json($cityid);
        } else {
            return response()->json("duplicate");
        }
    }

    public function storecities(Request $request)
    {

        $stateid = $request->statid;
        $city_name = $request->city_name;
        $city_slug = str_slug($request->city_name);

        $count = DB::table('cities')
            ->where('city_name', $city_name)
            ->where('state_id', $stateid)->get()->count();
        if ($count == 0) {
            $city_state = DB::table('states')->where('id', $request->statid)->get();

            $CreatedOn = Carbon::now();
            $data = array(
                'state_id' => $stateid,
                "city_name" => $city_name,
                "city_state" => $city_state[0]->state_abbr,
                "city_slug" => $city_slug,
                "created_at" => $CreatedOn,
                "updated_at" => $CreatedOn,
                "createdBy" => auth()->user()->id,
                "is_approved" => "N",
            );


            $row = DB::table('cities')->insertGetId($data);
            return response()->json($stateid);
        } else {
            return response()->json("duplicate");
        }



    }

    public function deletefaq(Request $request, $id)
    {

        //echo $id;exit;

        $deleted = DB::table('pag_faq')->where('id', $id)->delete();
        if ($deleted) {
            Session::flash('success_message', get_phrase('Faq deleted Successfully'));
            return redirect()->route('pages.create');
        } else {
            Session::flash('success_message', get_phrase('Something went wrong'));
            return redirect()->route('pages.create');
        }
    }


    public function update(Request $request, $id)
    {
        $rules = array(
            'image' => 'mimes:jpeg,jpg,png,gif|nullable',
            'name' => 'required|max:255',
            'parent' => 'required',
            'category' => 'required',
            'item_phone' => ['nullable', 'regex:/^(\+?\d{1,3}[-. ]?)?\d{10}$/'],
            'state' => 'required|not_in:0',
            'city' => 'required|not_in:0',
            'area' => 'required|not_in:0',
            'featured_video' => 'nullable|mimes:mp4,avi,mkv,flv,webm|max:512000', // 500MB limit for video
            'featured_thumbnail' => 'nullable|mimes:jpeg,jpg,png,gif|max:102400', // 100MB limit for image
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()->all()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // $categories_id=implode(',', $request->category);
        $page = Page::find($id);
        //previous image name
        $imagename = $page->logo;
        if ($request->image && !empty($request->image)) {
            $file_name = FileUploader::upload($request->image, 'public/storage/pages/logo', 250);
        }

        if ($request->Proof_of_Ownership && !empty($request->Proof_of_Ownership)) {

            $proof_of_ownership_file_name = FileUploader::upload($request->Proof_of_Ownership, 'public/storage/pages/logo', 250);

        }

        $title = 'listing';
        $approval = ManageApproval::where('title', $title)->first();

        if ($approval && $approval->status == 1) {
            // Approval status is ON
            $item_status = 2;
            $item_featured = 0;
            $item_featured_by_admin = 0;

        } elseif (auth()->check() && auth()->user()->user_role == 'admin') {
            // Status is OFF but user is admin
            $item_status = 2;
            $item_featured = 1;
            $item_featured_by_admin = 1;

        } else {
            //Status is OFF and user is not admin
            $item_status = $page->item_status;
            $item_featured = 0;
            $item_featured_by_admin = 0;
        }

        // if(auth()->user()->user_role=="admin"){

        //     $item_status=2;
        //     $item_featured=1;
        //     $item_featured_by_admin=1;

        // }
        // else{

        //     $item_status=1;
        //     $item_featured=0;
        //     $item_featured_by_admin=0;
        // }

        $page_slug = preg_replace("/[^A-Za-z0-9 ]/", '', $request->name);


        $multiSelectArray = $request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }

        if ($request->servicestate) {
            $service_state_ids = implode(',', $request->servicestate);
        } else {
            $service_state_ids = "";
        }

        if ($request->servicecity) {
            $service_city_ids = implode(',', $request->servicecity);
        } else {
            $service_city_ids = "";
        }

        if ($request->servicecountry) {
            $service_offeres_countries_ids = implode(',', $request->servicecountry);
        } else {
            $service_offeres_countries_ids = "";
        }


        $categories_id = implode(',', $multiSelectArray);
        $page->user_id = auth()->user()->id;
        $page->title = $request->name;
        $page->item_slug = str_slug($page_slug);
        $page->address = $request->address;
        $page->state_id = $request->state;
        $page->city_id = $request->city;
        $page->area_id = $request->area;
        $page->pincode = $request->pincode;
        $page->category_id = $categories_id;

        // $page->item_status = $item_status;
        // $page->item_featured = $item_featured;
        $page->item_featured_by_admin = $item_featured_by_admin;
        $page->item_website = $request->website;
        $page->item_email = $request->business_email;
        $page->item_whatsapp = $request->business_whatsapp_url;
        $page->item_phone = $request->item_phone;
        $page->item_lat = $request->item_lat;
        $page->item_lng = $request->item_lng;
        $page->item_social_facebook = $request->facebook;
        $page->item_social_twitter = $request->twitter;
        $page->item_social_linkedin = $request->linkedIn;
        $page->item_youtube_id = $request->youtube_video_id;


        if ($request->servicecategory) {
            $product_categories_ids = implode(',', $request->servicecategory);
        } else {
            $product_categories_ids = "";
        }

        if ($request->servicearea) {
            $service_offeres_areas_ids = implode(',', $request->servicearea);
        } else {
            $service_offeres_areas_ids = "";
        }


        $page->insta_link = $request->instalink;
        $page->product_categories_ids = $product_categories_ids;

        $page->why_visit_us = $request->visitus;
        $page->our_story = $request->our_story;
        $page->year_of_establishment = $request->yrofest;
        $page->service_offeres_areas_ids = $service_offeres_areas_ids;

        $page->description = $request->description;
        $page->country_id = $request->country;
        //$page->open_hours = $request->open_hours;
        $page->service_offered_country = $service_offeres_countries_ids;
        $page->service_offered_state = $service_state_ids;
        $page->service_offered_city = $service_city_ids;

        $page->policy = $request->policy;

        $page->description = $request->description;
        if ($request->image && !empty($request->image)) {
            $page->logo = $file_name;
        }
        if ($request->Proof_of_Ownership && !empty($request->Proof_of_Ownership)) {
            $page->ownership_document = $proof_of_ownership_file_name;
        }


        // Handle featured video upload (if any) to the same "logo" directory
        if ($request->hasFile('featured_video')) {
            $featured_video_name = FileUploader::upload($request->featured_video, 'public/storage/pages/logo', 512000);  // 500MB for video
        } else {
            $featured_video_name = null; // If no video is uploaded, set it to null
        }

        // Handle featured thumbnail upload (if any) to the same "logo" directory
        if ($request->hasFile('featured_thumbnail')) {
            $featured_thumbnail_name = FileUploader::upload($request->featured_thumbnail, 'public/storage/pages/logo', 102400);  // 100MB for thumbnail
        } else {
            $featured_thumbnail_name = null; // If no thumbnail is uploaded, set it to null
        }
        //start video
        $page->featured_video = $featured_video_name; // Save the video file name
        $page->featured_thumbnail = $featured_thumbnail_name; // Save the thumbnail file name
        //end video

        $done = $page->save();
        if ($done) {

            foreach ($multiSelectArray as $category_id) {
                $category_count = DB::table('page_category')->select('page_category.id')
                    ->where('category_id', $category_id)
                    ->where('page_id', $id)
                    ->count();
                if ($category_count == 0) {
                    $data = array(
                        'category_id' => $category_id,
                        "page_id" => $id
                    );
                    $row = DB::table('page_category')->insertGetId($data);
                }


            }
            $slug_count = DB::table('pages')->select('pages.id')
                ->where('pages.item_slug', str_slug($request->name))->count();
            ;

            if ($slug_count > 1) {

                DB::table('pages')->where('id', $id)
                    ->update(array('item_slug' => DB::raw('concat("' . str_slug($request->name) . '",' . '-' . $id . ')')));
            }
            $faqs = $request->input('faqs', []);
            foreach ($faqs as $faq) {
                $page_faq_count = DB::table('pag_faq')->select('pag_faq.id')
                    ->where('pag_faq.question', $faq['question'])
                    ->where('pag_faq.answer', $faq['answer'])
                    ->count();
                if ($page_faq_count == 0) {
                    if (!empty($faq['question']) && !empty($faq['answer'])) {
                        $data = array(
                            'question' => $faq['question'] ?? null,
                            'answer' => $faq['answer'] ?? null,
                            "page_id" => $page->id
                        );
                        $row = DB::table('pag_faq')->insertGetId($data);
                    }
                }
            }

            $uploadedFiles = [];

            if ($request->hasFile('media')) {
                $uploadedFiles = []; // Initialize array

                foreach ($request->file('media') as $file) {
                    $mimeType = $file->getMimeType();
                    // Upload the file without resizing
                    $fileName = FileUploader::upload($file, 'public/storage/pages/media');

                    // Determine file type

                    $fileType = str_starts_with($mimeType, 'image') ? 'image' : (str_starts_with($mimeType, 'video') ? 'video' : 'other');

                    // Store file info in the database
                    $uploadedFiles[] = [
                        'page_id' => $page->id,
                        'file' => $fileName,
                        'file_type' => $fileType, // Add file type
                        'createdAt' => now()
                    ];
                }

                // Insert into database
                DB::table('page_media')->insert($uploadedFiles);
            }
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            $openingHours = $request->input('opening_hours', []);

            foreach ($days as $day) {
                $data = $openingHours[$day] ?? [];

                $isClosed = isset($data['closed']) ? 1 : 0;

                $open = $isClosed ? null : ($data['open'] ?? null);
                $close = $isClosed ? null : ($data['close'] ?? null);

                OpeningHour::updateOrCreate(
                    ['page_id' => $page->id, 'day' => $day],
                    ['open' => $open, 'close' => $close, 'closed' => $isClosed]
                );
            }
            // just put the file name and folder name nothing more :) 
            if (!empty($request->image)) {
                if (File::exists(public_path('storage/pages/logo/' . $imagename))) {
                    File::delete(public_path('storage/pages/logo/' . $imagename));
                }
            }
        }

        // CLEAR CACHE HERE — use the same keys as your single_page cache
        Cache::forget("page_{$page->item_slug}");
        Cache::forget("photos_{$page->id}");
        Cache::forget("posts_{$page->id}");
        Session::flash('success_message', get_phrase('Page Updated Successfully'));

        $relations = ['city', 'area', 'state', 'categories', 'likes'];

        $page_data = Page::with($relations)->findOrFail($page->id);



        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => get_phrase('Page Updated Successfully'),
                'redirect_url' => route('single.page', [
                    'city_slug' => optional($page_data->city)->city_slug ?? 'unknown-city',
                    'area_slug' => optional($page_data->area)->area_slug ?? 'unknown-area',
                    'category_slug' => $page_data->categories->last()->category_slug,
                    'item_slug' => $page_data->item_slug,
                ])
            ]);
        }

        return redirect()->route('single.page', [
            'city_slug' => optional($page_data->city)->city_slug ?? 'unknown-city',
            'area_slug' => optional($page_data->area)->area_slug ?? 'unknown-area',
            'category_slug' => $page_data->categories->last()->category_slug,
            'item_slug' => $page_data->item_slug,
        ]);


        //return redirect()->route('pages');
    }






    public function updatecoverphoto(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $oldImage = $page->coverphoto;

        if ($request->hasFile('cover_photo')) {

            // Get the uploaded file
            $file = $request->file('cover_photo');

            // Generate a unique name
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Move to the desired folder (public/storage/pages/coverphoto)
            $file->move(public_path('storage/pages/coverphoto'), $filename);

            // Update the DB record
            $page->coverphoto = $filename;
        }

        if ($page->save()) {
            // Delete old image if exists
            if (!empty($oldImage) && File::exists(public_path('storage/pages/coverphoto/' . $oldImage))) {
                File::delete(public_path('storage/pages/coverphoto/' . $oldImage));
            }

            // Clear related cache
            Cache::forget("page_{$page->item_slug}");
            Cache::forget("photos_{$page->id}");
            Cache::forget("posts_{$page->id}");

            Session::flash('success_message', 'Cover Photo Updated Successfully');

            return response()->json(['reload' => 1]);
        }

        return response()->json(['error' => 'Something went wrong'], 500);
    }


    public function updatecoverphoto_bk(Request $request, $id)
    {

        $page = Page::find($id);
        $imagename = $page->coverphoto;



        if ($request->cover_photo && !empty($request->cover_photo)) {
            $file_name = FileUploader::upload($request->cover_photo, 'public/storage/pages/coverphoto', 1120);


            $page->coverphoto = $file_name;
        }
        $done = $page->save();
        if ($done) {
            // just put the file name and folder name nothing more :) 
            if (!empty($request->cover_photo)) {
                if (File::exists(public_path('storage/pages/coverphoto/' . $imagename))) {
                    File::delete(public_path('storage/pages/coverphoto/' . $imagename));
                }
            }
            // CLEAR CACHE HERE — use the same keys as your single_page cache
            Cache::forget("page_{$page->item_slug}");
            Cache::forget("photos_{$page->id}");
            Cache::forget("posts_{$page->id}");
        }
        Session::flash('success_message', get_phrase('Cover Photo Updated Successfully'));
        return json_encode(array('reload' => 1));




    }


    public function updateinfo(Request $request, $id)
    {
        // echo $id;exit;
        $page = Page::find($id);
        $page->job = $request->job;
        $page->lifestyle = $request->lifestyle;
        $page->location = $request->location;
        $page->save();
        Cache::forget("page_{$page->item_slug}");
        Cache::forget("photos_{$page->id}");
        Cache::forget("posts_{$page->id}");
        Session::flash('success_message', get_phrase('Info Updated Successfully'));
        return redirect()->back();
    }


    // load event on scroll 

    public function load_page_by_scrolling(Request $request)
    {

        // $mypages =  Page::where('user_id',auth()->user()->id)->skip($request->offset)->take(6)->orderBy('id', 'DESC')->get();
        // $page_data['mypages'] = $mypages;
        // return view('frontend.pages.single-page', $page_data);
    }




    public function single_page($city_slug, $area_slug, $category_slug, $item_slug)
    {
        // Get all cities (Cached)
        $page_data['all_cities'] = Cache::remember('all_cities', 3600, function () {
            return City::select('cities.*')
                ->join('pages', 'pages.city_id', 'cities.id')
                ->join('page_category', 'page_category.page_id', 'pages.id')
                ->join('pagecategories', 'page_category.category_id', 'pagecategories.id')
                ->where('pages.item_status', 2)
                ->distinct()
                ->orderBy('cities.city_name', 'asc')
                ->get();
        });

        // Get page
        $page = Cache::remember("page_$item_slug", 3600, function () use ($item_slug) {
            return Page::with(['city', 'area', 'pagecategories', 'openingHours'])
                ->withCount('products')
                ->where('item_slug', $item_slug)
                ->first();
        });

        // If page not found and slug ends with numeric suffix, try to find base slug and redirect
        if (!$page && preg_match('/^(.+)-(\d+)$/', $item_slug, $matches)) {
            $base_slug = $matches[1];
            $suffix = $matches[2];

            // Try to find a page with the base slug
            $base_page = Page::with(['city', 'area', 'pagecategories', 'openingHours'])
                ->withCount('products')
                ->where('item_slug', $base_slug)
                ->where('item_status', 2)
                ->first();

            if ($base_page) {
                // Build the clean URL without the numeric suffix
                $clean_url = url("/{$city_slug}/{$area_slug}/{$category_slug}/{$base_slug}");

                // 301 redirect to the clean URL
                return redirect($clean_url, 301);
            }
        }

        if (!$page) {
            abort(404);
        }

        $pages = Page::findOrFail($page->id); // or whatever method you're using

        if (auth()->user()) {
            app(UserActivityService::class)->log(auth()->user()->id, 'view', 'page', $page->id, $page->id);
        }


        //print_r($pages);exit;

        if ($pages) {
            $page_view_data = $pages->view ? json_decode($pages->view, true) : [];

            if (auth()->user() && !in_array(auth()->user()->id, $page_view_data)) {
                $page_view_data[] = auth()->user()->id;
                $pages->view = json_encode($page_view_data);
            }

            $pages->save();
        }
        $page_data['pages'] = $pages;

        $id = $page->id;

        //print_r($page);exit;

        // Get the requested category
        $category = Cache::remember("category_$category_slug", 3600, function () use ($category_slug) {
            return Pagecategory::where('category_slug', $category_slug)->first();
        });

        // Get parent category and subcategories
        $city = $page->city;
        $parentcategory = Cache::remember("parent_category_$category->id", 3600, function () use ($category, $city) {
            return Pagecategory::where('id', $category->category_parent_id)->first();
        });

        $parentcategories = Cache::remember("parent_categories_{$category->id}_{$city->id}", 3600, function () use ($category, $city) {
            return Pagecategory::select('pagecategories.*')
                ->join('page_category', 'page_category.category_id', '=', 'pagecategories.id')
                ->join('pages', 'pages.id', '=', 'page_category.page_id')
                ->where('pages.item_status', 2)
                ->where('pagecategories.id', $category->category_parent_id)
                ->where('pages.city_id', $city->id)
                ->distinct()
                ->orderBy('category_name')
                ->get();
        });

        $subcategories = $parentcategories->pluck('category_name');
        $subcategoryString = $subcategories->implode(', ');


        $idArray = explode(',', $page->category_id);

        $categoryNames = Pagecategory::whereIn('id', $idArray)->pluck('category_name')->implode(', ');


        // Get all videos
        $page_data['all_videos'] = Cache::remember("videos_$id", 600, function () use ($id) {
            return Media_files::where('page_id', $id)
                ->where('file_type', 'video')
                ->orderByDesc('id')
                ->take(20)
                ->get();
        });

        // Get all photos
        $page_data['all_photos'] = Cache::remember("photos_$id", 600, function () use ($id) {
            return Media_files::where('page_id', $id)
                ->orderByDesc('id')
                ->take(30)
                ->get();
        });

        $all_reviews = Review::where('marketplace_id', $page->id)
            ->with('user')
            ->where('type', 'pages')
            ->latest()
            ->get();

        $page_data['reviews'] = $all_reviews->take(5); // First 5 reviews
        $page_data['has_more_reviews'] = $all_reviews->count() > 5;


        $ratingValue = $all_reviews->avg('rating') ?? 4.0;
        $reviewCount = $all_reviews->count();

        $page_data['rating'] = round($ratingValue, 1);
        $page_data['review_count'] = $reviewCount;




        SEOMeta::setTitle($page->title . ' - ' . $category->category_name . ' ' . $page->address . ' , ' . $city->city_name . ' | CityHangAround');
        SEOMeta::setDescription('Looking for ' . $category->category_name . ' in ' . $city->city_name . ' ? Visit ' . $page->title . ' ' . $page->address . ' for ' . $categoryNames . ' Check reviews, location & contact details on CityHangAround.');

        SEOMeta::setCanonical(URL::current());

        // Get posts
        $page_data['posts'] = Cache::remember("posts_$id", 300, function () use ($id) {
            return Posts::select(
                'posts.*',
                'pages.id as page_id',
                'pages.item_slug',
                'pages.logo',
                'pages.title',
                'pages.coverphoto',
                'pages.user_id',
                'pages.description',
                'pages.job',
                'pages.location',
                'pages.lifestyle',
                'cities.city_slug',
                'areas.area_slug',
                'pagecategories.category_slug',
                'pagecategories.category_name',
                'cities.city_name',
                'areas.area_name'
            )
                ->join('pages', 'posts.publisher_id', '=', 'pages.id')
                ->join('cities', 'cities.id', 'pages.city_id')
                ->join('areas', 'areas.id', 'pages.area_id')
                ->join('page_category', 'page_category.page_id', 'pages.id')
                ->join('pagecategories', 'page_category.category_id', '=', 'pagecategories.id')
                ->where('posts.publisher', 'page')
                ->where('posts.publisher_id', $id)
                ->where('posts.status', 'active')
                ->orderByDesc('posts.post_id')
                ->get();
        });

        //print_r( $page_data['posts']);exit;

        // Suggested pages (based on friends)
        if (auth()->check()) {
            $friendsid = Friendships::where(function ($query) {
                $query->where('requester', auth()->id())
                    ->orWhere('accepter', auth()->id());
            })->where('is_accepted', 1)
                ->get()
                ->map(function ($friend) {
                    return $friend->requester == auth()->id() ? $friend->accepter : $friend->requester;
                });

            $page_data['suggestedpages'] = Cache::remember("suggested_pages_" . auth()->id(), 600, function () use ($friendsid) {
                return Page_like::with(['page.city', 'page.area', 'page.pagecategories'])
                    ->whereIn('user_id', $friendsid)
                    ->where('user_id', '!=', auth()->id())
                    ->limit(1)
                    ->get();
            });

            //print_r($page_data['suggestedpages']);exit;


        } else {
            $page_data['suggestedpages'] = [];
        }

        // Final page details
        $page_data['page'] = $page;
        $page_data['category'] = $category;
        $page_data['parent_categories'] = $parentcategories;
        $page_data['view_path'] = 'frontend.pages.page-timeline';

        return view('frontend.index', $page_data);
    }



    public function page_photos($city_slug, $area_slug, $category_slug, $item_slug)
    {

        $page_data['all_cities'] = CityHelper::getActiveCities();
        $id = Page::where('item_slug', $item_slug)->value('id');
        $friendsid = [];
        $friendidfind = '';
        if (auth()->user()) {
            $friends = Friendships::where('requester', auth()->user()->id)->orWhere('accepter', auth()->user()->id)->where('is_accepted', '1')->get();
            foreach ($friends as $friend) {
                $friendidfind = $friend->accepter == auth()->user()->id ? "$friend->requester" : "$friend->accepter";
                array_push($friendsid, $friendidfind);
            }
        }


        $all_photos = Media_files::where('page_id', $id)
            ->where('file_type', 'image')
            ->take(20)->orderBy('id', 'DESC')->get();

        $all_albums = Albums::where('page_id', $id)
            ->take(6)->orderBy('id', 'DESC')->get();

        $page_data['all_videos'] = Media_files::where('page_id', $id)
            ->where('file_type', 'video')
            ->take(20)->orderBy('id', 'DESC')->get();

        $page_data['all_photos'] = $all_photos;
        $page_data['all_albums'] = $all_albums;

        $pages = Page::findOrFail($id); // or whatever method you're using

        $city = City::where('city_slug', $city_slug)->first();
        $area = Area::where('area_slug', $area_slug)->where('city_id', $city->id)->first();
        $category = Pagecategory::where('category_slug', $category_slug)->first();

        SEOMeta::setTitle('Photos,' . $pages->title . ', ' . $area->area_name . ',' . $city->city_name);
        SEOMeta::setDescription('Photos,' . $pages->title . ' ' . $area->area_name . ' ' . $city->city_name . ' ' . $category->categpry_name);

        SEOMeta::setCanonical(URL::current());


        $page_data['pages'] = $pages;
        $page_data['page'] = Cache::remember("page_with_relations_$id", 600, function () use ($id) {
            return Page::with(['city', 'area', 'pagecategories', 'likedByUsers'])
                ->withCount('likedByUsers') // adds liked_by_users_count
                ->find($id);
        });


        $all_reviews = Review::where('marketplace_id', $id)
            ->with('user')
            ->where('type', 'pages')
            ->latest()
            ->get();

        $page_data['reviews'] = $all_reviews->take(5); // First 5 reviews
        $page_data['has_more_reviews'] = $all_reviews->count() > 5;


        $ratingValue = $all_reviews->avg('rating') ?? 4.0;
        $reviewCount = $all_reviews->count();

        $page_data['rating'] = round($ratingValue, 1);
        $page_data['review_count'] = $reviewCount;
        // Check if the current user liked the page
        $page_data['page']['liked_by_user'] = false;

        if (auth()->user() && $page_data['page']) {
            $user = auth()->user();
            $page_data['page']['liked_by_user'] = $page_data['page']->likedByUsers->contains($user->id);
        }

        $page_data['suggestedpages'] = Cache::remember("suggested_pages_" . auth()->id(), 600, function () use ($friendsid) {
            return Page_like::with(['page.city', 'page.area', 'page.pagecategories'])
                ->whereIn('user_id', $friendsid)
                ->where('user_id', '!=', auth()->id())
                ->limit(1)
                ->get();
        });

        $page_data['view_path'] = 'frontend.pages.photos';
        return view('frontend.index', $page_data);
    }

    public function videos($city_slug, $area_slug, $category_slug, $item_slug)
    {



        $page_data['all_cities'] = CityHelper::getActiveCities();

        $id = Page::where('item_slug', $item_slug)->value('id');

        $friendsid = [];
        $friendidfind = '';
        if (auth()->user()) {
            $friends = Friendships::where('requester', auth()->user()->id)->orWhere('accepter', auth()->user()->id)->where('is_accepted', '1')->get();
            foreach ($friends as $friend) {
                $friendidfind = $friend->accepter == auth()->user()->id ? "$friend->requester" : "$friend->accepter";
                array_push($friendsid, $friendidfind);
            }
        }

        $all_videos = Media_files::where('page_id', $id)
            ->where('file_type', 'video')
            ->take(20)->orderBy('id', 'DESC')->get();

        $page_data['all_videos'] = $all_videos;

        $pages = Page::findOrFail($id); // or whatever method you're using

        $city = City::where('city_slug', $city_slug)->first();
        $area = Area::where('area_slug', $area_slug)->where('city_id', $city->id)->first();
        $category = Pagecategory::where('category_slug', $category_slug)->first();


        $page_data['pages'] = $pages;

        $page_data['page'] = Cache::remember("page_with_relations_$id", 600, function () use ($id) {
            return Page::with(['city', 'area', 'pagecategories', 'likedByUsers'])
                ->withCount('likedByUsers') // adds liked_by_users_count
                ->find($id);
        });

        $all_reviews = Review::where('marketplace_id', $id)
            ->with('user')
            ->where('type', 'pages')
            ->latest()
            ->get();

        $page_data['reviews'] = $all_reviews->take(5); // First 5 reviews
        $page_data['has_more_reviews'] = $all_reviews->count() > 5;


        $ratingValue = $all_reviews->avg('rating') ?? 4.0;
        $reviewCount = $all_reviews->count();

        $page_data['rating'] = round($ratingValue, 1);
        $page_data['review_count'] = $reviewCount;

        SEOMeta::setTitle('Videos,' . $pages->title . ', ' . $area->area_name . ',' . $city->city_name);
        SEOMeta::setDescription('Videos,' . $pages->title . ' ' . $area->area_name . ' ' . $city->city_name . ' ' . $category->categpry_name);

        SEOMeta::setCanonical(URL::current());


        // Check if the current user liked the page
        $page_data['page']['liked_by_user'] = false;

        if (auth()->user() && $page_data['page']) {
            $user = auth()->user();
            $page_data['page']['liked_by_user'] = $page_data['page']->likedByUsers->contains($user->id);
        }

        $all_photos = Media_files::where('page_id', $id)
            ->where('file_type', 'image')
            ->take(20)->orderBy('id', 'DESC')->get();
        $page_data['all_photos'] = $all_photos;

        $page_data['suggestedpages'] = Cache::remember("suggested_pages_" . auth()->id(), 600, function () use ($friendsid) {
            return Page_like::with(['page.city', 'page.area', 'page.pagecategories'])
                ->whereIn('user_id', $friendsid)
                ->where('user_id', '!=', auth()->id())
                ->limit(1)
                ->get();
        });
        $page_data['view_path'] = 'frontend.pages.video';
        return view('frontend.index', $page_data);
    }

    function load_videos(Request $request)
    {
        $all_videos = Media_files::where('user_id', $this->user->id)
            ->where('file_type', 'video')
            ->skip($request->offset)->take(12)->orderBy('id', 'DESC')->get();

        $page_data['all_videos'] = $all_videos;
        $page_data['user_info'] = $this->user;
        return view('frontend.profile.video_single', $page_data);
    }


    public function like($id)
    {
        $response = array();
        $pagelike = new Page_like();
        $pagelike->page_id = $id;
        $pagelike->user_id = auth()->user()->id;
        $pagelike->role = 'general';
        $pagelike->save();

        if (auth()->user()) {
            app(UserActivityService::class)->log(auth()->user()->id, 'follow', 'page', $id, $id);
        }
        // Session::flash('success_message', get_phrase('Page Liked Successfully'));
        Session::flash('success_message', get_phrase('Page followed Successfully'));
        $response = array('reload' => 1);
        return json_encode($response);
    }

    public function dislike($id)
    {
        $response = array();
        $pagelike = Page_like::where('page_id', $id)->delete();

        if (auth()->user()) {
            app(UserActivityService::class)->deleteBypagelikeActivityId($id);
        }
        // Session::flash('success_message', get_phrase('Page Disliked'));
        Session::flash('success_message', get_phrase('Page Unfollowed'));
        $response = array('reload' => 1);
        return json_encode($response);
    }


    public function submitClaim(Request $request)
    {

        // Validate the form data
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'business_name' => 'required|string',
            'business_address' => 'required|string',
            'ownership_proof' => 'required|file|mimes:pdf,jpg,png,doc,docx|max:2048',
            'agree_terms' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Handle file upload
        if ($request->hasFile('ownership_proof')) {
            // $filePath = $request->file('ownership_proof')->store('claim-listings', 'public');
            $filePath = FileUploader::upload($request->ownership_proof, 'public/storage/pages/ownership_proof', 250);
        } else {
            $filePath = null;
        }

        // Save to database
        ClaimListing::create([
            'page_id' => $request->page_id,
            'user_id' => auth()->user()->id,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'business_name' => $request->business_name,
            'business_address' => $request->business_address,
            'ownership_proof' => $filePath,
            'additional_comments' => $request->additional_comments ?? null,
        ]);

        if (auth()->user()) {
            app(UserActivityService::class)->log(auth()->user()->id, 'claim_listing', 'page', $request->page_id, $request->page_id);
        }

        return back()->with('success', 'Your claim has been submitted successfully!');
    }


    function events($city_slug, $area_slug, $category_slug, $item_slug)
    {
        $page_data['all_cities'] = CityHelper::getActiveCities();

        $id = Page::where('item_slug', $item_slug)->value('id');

        $friendsid = [];
        $friendidfind = '';
        if (auth()->user()) {
            $friends = Friendships::where('requester', auth()->user()->id)->orWhere('accepter', auth()->user()->id)->where('is_accepted', '1')->get();
            foreach ($friends as $friend) {
                $friendidfind = $friend->accepter == auth()->user()->id ? "$friend->requester" : "$friend->accepter";
                array_push($friendsid, $friendidfind);
            }
        }


        $all_photos = Media_files::where('page_id', $id)
            ->where('file_type', 'image')
            ->take(20)->orderBy('id', 'DESC')->get();

        $all_albums = Albums::where('page_id', $id)
            ->take(6)->orderBy('id', 'DESC')->get();

        $page_data['all_videos'] = Media_files::where('page_id', $id)
            ->where('file_type', 'video')
            ->take(20)->orderBy('id', 'DESC')->get();

        $page_data['all_photos'] = $all_photos;
        $page_data['all_albums'] = $all_albums;

        $pages = Page::findOrFail($id); // or whatever method you're using


        $page_data['pages'] = $pages;

        $city = City::where('city_slug', $city_slug)->first();
        $area = Area::where('area_slug', $area_slug)->where('city_id', $city->id)->first();
        $category = Pagecategory::where('category_slug', $category_slug)->first();

        $all_reviews = Review::where('marketplace_id', $id)
            ->with('user')
            ->where('type', 'pages')
            ->latest()
            ->get();

        $page_data['reviews'] = $all_reviews->take(5); // First 5 reviews
        $page_data['has_more_reviews'] = $all_reviews->count() > 5;


        $ratingValue = $all_reviews->avg('rating') ?? 4.0;
        $reviewCount = $all_reviews->count();

        $page_data['rating'] = round($ratingValue, 1);
        $page_data['review_count'] = $reviewCount;


        SEOMeta::setTitle('Events by ' . $pages->title . ' ' . $area->area_name . ' ' . $city->city_name);
        SEOMeta::setDescription('Events by ' . $pages->title . ' ' . $area->area_name . ' ' . $city->city_name . ' ' . $category->categpry_name);

        SEOMeta::setCanonical(URL::current());

        $page_data['page'] = Cache::remember("page_with_relations_$id", 600, function () use ($id) {
            return Page::with(['city', 'area', 'pagecategories', 'likedByUsers'])
                ->withCount('likedByUsers') // adds liked_by_users_count
                ->find($id);
        });

        // Check if the current user liked the page
        $page_data['page']['liked_by_user'] = false;

        if (auth()->user() && $page_data['page']) {
            $user = auth()->user();
            $page_data['page']['liked_by_user'] = $page_data['page']->likedByUsers->contains($user->id);
        }

        $page_data['suggestedpages'] = Cache::remember("suggested_pages_" . auth()->id(), 600, function () use ($friendsid) {
            return Page_like::with(['page.city', 'page.area', 'page.pagecategories'])
                ->whereIn('user_id', $friendsid)
                ->where('user_id', '!=', auth()->id())
                ->limit(1)
                ->get();
        });
        $page_data['events'] = Event::where('user_id', $page_data['page']->user_id)->where('event_status', 2)
            ->where('events.event_date', '>=', Carbon::now())
            ->whereNull('group_id')->orderBy('id', 'DESC')->get();


        $page_data['view_path'] = 'frontend.pages.events';
        return view('frontend.index', $page_data);

        // $page_data['view_path'] = 'frontend.profile.index';
        // return view('frontend.index', $page_data);

    }

    function groups($city_slug, $area_slug, $category_slug, $item_slug)
    {

        $page_data['all_cities'] = CityHelper::getActiveCities();

        $id = Page::where('item_slug', $item_slug)->value('id');

        $friendsid = [];
        $friendidfind = '';
        if (auth()->user()) {
            $friends = Friendships::where('requester', auth()->user()->id)->orWhere('accepter', auth()->user()->id)->where('is_accepted', '1')->get();
            foreach ($friends as $friend) {
                $friendidfind = $friend->accepter == auth()->user()->id ? "$friend->requester" : "$friend->accepter";
                array_push($friendsid, $friendidfind);
            }
        }


        $all_photos = Media_files::where('page_id', $id)
            ->where('file_type', 'image')
            ->take(20)->orderBy('id', 'DESC')->get();

        $all_albums = Albums::where('page_id', $id)
            ->take(6)->orderBy('id', 'DESC')->get();

        $page_data['all_videos'] = Media_files::where('page_id', $id)
            ->where('file_type', 'video')
            ->take(20)->orderBy('id', 'DESC')->get();

        $page_data['all_photos'] = $all_photos;
        $page_data['all_albums'] = $all_albums;

        $pages = Page::findOrFail($id); // or whatever method you're using


        $page_data['pages'] = $pages;


        $city = City::where('city_slug', $city_slug)->first();
        $area = Area::where('area_slug', $area_slug)->where('city_id', $city->id)->first();
        $category = Pagecategory::where('category_slug', $category_slug)->first();




        SEOMeta::setTitle('Groups, ' . $pages->title . ' ' . $area->area_name . ' ' . $city->city_name);
        SEOMeta::setDescription('Groups, ' . $pages->title . ' ' . $area->area_name . ' ' . $city->city_name . ' ' . $category->categpry_name);

        SEOMeta::setCanonical(URL::current());


        $page_data['page'] = Cache::remember("page_with_relations_$id", 600, function () use ($id) {
            return Page::with(['city', 'area', 'pagecategories', 'likedByUsers'])
                ->withCount('likedByUsers') // adds liked_by_users_count
                ->find($id);
        });

        $all_reviews = Review::where('marketplace_id', $id)
            ->with('user')
            ->where('type', 'pages')
            ->latest()
            ->get();

        $page_data['reviews'] = $all_reviews->take(5); // First 5 reviews
        $page_data['has_more_reviews'] = $all_reviews->count() > 5;


        $ratingValue = $all_reviews->avg('rating') ?? 4.0;
        $reviewCount = $all_reviews->count();

        $page_data['rating'] = round($ratingValue, 1);
        $page_data['review_count'] = $reviewCount;
        // Check if the current user liked the page
        $page_data['page']['liked_by_user'] = false;

        if (auth()->user() && $page_data['page']) {
            $user = auth()->user();
            $page_data['page']['liked_by_user'] = $page_data['page']->likedByUsers->contains($user->id);
        }

        $page_data['suggestedpages'] = Cache::remember("suggested_pages_" . auth()->id(), 600, function () use ($friendsid) {
            return Page_like::with(['page.city', 'page.area', 'page.pagecategories'])
                ->whereIn('user_id', $friendsid)
                ->where('user_id', '!=', auth()->id())
                ->limit(1)
                ->get();
        });

        $page_data['groups'] = Group::select('groups.*', 'groupcategories.category_name')
            ->join('group_category', 'groups.id', '=', 'group_category.group_id')
            ->join('groupcategories', 'group_category.category_id', '=', 'groupcategories.id')
            ->where('groups.user_id', $page_data['page']->user_id)->get();

        $page_data['view_path'] = 'frontend.pages.groups';
        return view('frontend.index', $page_data);
    }


    function pageinfo($city_slug, $area_slug, $category_slug, $item_slug)
    {

        $page_data['all_cities'] = CityHelper::getActiveCities();

        $id = Page::where('item_slug', $item_slug)->value('id');

        $pages = Page::findOrFail($id); // or whatever method you're using


        $page_data['pages'] = $pages;

        $city = City::where('city_slug', $city_slug)->first();
        $area = Area::where('area_slug', $area_slug)->where('city_id', $city->id)->first();
        $category = Pagecategory::where('category_slug', $category_slug)->first();



        SEOMeta::setTitle('About ' . $pages->title . ' ' . $area->area_name . ' ' . $city->city_name);
        SEOMeta::setDescription('About ' . $pages->title . ' ' . $area->area_name . ' ' . $city->city_name . ' ' . $category->categpry_name);

        SEOMeta::setCanonical(URL::current());


        $friendsid = [];
        $friendidfind = '';
        if (auth()->user()) {
            $friends = Friendships::where('requester', auth()->user()->id)->orWhere('accepter', auth()->user()->id)->where('is_accepted', '1')->get();
            foreach ($friends as $friend) {
                $friendidfind = $friend->accepter == auth()->user()->id ? "$friend->requester" : "$friend->accepter";
                array_push($friendsid, $friendidfind);
            }
        }


        $all_photos = Media_files::where('page_id', $id)
            ->where('file_type', 'image')
            ->take(20)->orderBy('id', 'DESC')->get();

        $all_albums = Albums::where('page_id', $id)
            ->take(6)->orderBy('id', 'DESC')->get();

        $page_data['all_videos'] = Media_files::where('page_id', $id)
            ->where('file_type', 'video')
            ->take(20)->orderBy('id', 'DESC')->get();

        $page_data['all_photos'] = $all_photos;
        $page_data['all_albums'] = $all_albums;
        $page_data['page'] = Cache::remember("page_with_relations_$id", 600, function () use ($id) {
            return Page::with(['city', 'area', 'pagecategories', 'likedByUsers'])
                ->withCount('likedByUsers') // adds liked_by_users_count
                ->find($id);
        });


        $all_reviews = Review::where('marketplace_id', $id)
            ->with('user')
            ->where('type', 'pages')
            ->latest()
            ->get();

        $page_data['reviews'] = $all_reviews->take(5); // First 5 reviews
        $page_data['has_more_reviews'] = $all_reviews->count() > 5;


        $ratingValue = $all_reviews->avg('rating') ?? 4.0;
        $reviewCount = $all_reviews->count();

        $page_data['rating'] = round($ratingValue, 1);
        $page_data['review_count'] = $reviewCount;
        // Check if the current user liked the page
        $page_data['page']['liked_by_user'] = false;

        if (auth()->user() && $page_data['page']) {
            $user = auth()->user();
            $page_data['page']['liked_by_user'] = $page_data['page']->likedByUsers->contains($user->id);
        }

        $page_data['suggestedpages'] = Cache::remember("suggested_pages_" . auth()->id(), 600, function () use ($friendsid) {
            return Page_like::with(['page.city', 'page.area', 'page.pagecategories'])
                ->whereIn('user_id', $friendsid)
                ->where('user_id', '!=', auth()->id())
                ->limit(1)
                ->get();
        });

        $page_data['groups'] = Group::select('groups.*', 'groupcategories.category_name')
            ->join('group_category', 'groups.id', '=', 'group_category.group_id')
            ->join('groupcategories', 'group_category.category_id', '=', 'groupcategories.id')
            ->where('groups.user_id', $page_data['page']->user_id)->get();

        //print_r($page_data['page']);exit;

        $page_data['view_path'] = 'frontend.pages.page_info';
        return view('frontend.index', $page_data);
    }



    function products($city_slug, $area_slug, $category_slug, $item_slug)
    {

        $page_data['all_cities'] = CityHelper::getActiveCities();

        $id = Page::where('item_slug', $item_slug)->value('id');

        $friendsid = [];
        $friendidfind = '';
        if (auth()->user()) {
            $friends = Friendships::where('requester', auth()->user()->id)->orWhere('accepter', auth()->user()->id)->where('is_accepted', '1')->get();
            foreach ($friends as $friend) {
                $friendidfind = $friend->accepter == auth()->user()->id ? "$friend->requester" : "$friend->accepter";
                array_push($friendsid, $friendidfind);
            }
        }

        $pages = Page::findOrFail($id); // or whatever method you're using


        $page_data['pages'] = $pages;

        $city = City::where('city_slug', $city_slug)->first();
        $area = Area::where('area_slug', $area_slug)->where('city_id', $city->id)->first();
        $category = Pagecategory::where('category_slug', $category_slug)->first();



        SEOMeta::setTitle('Top Deals by ' . $pages->title . ' ' . $area->area_name . ' ' . $city->city_name);
        SEOMeta::setDescription('Top Deals by ' . $pages->title . ' ' . $area->area_name . ' ' . $city->city_name . ' ' . $category->categpry_name);

        SEOMeta::setCanonical(URL::current());


        $all_photos = Media_files::where('page_id', $id)
            ->where('file_type', 'image')
            ->take(20)->orderBy('id', 'DESC')->get();

        $all_albums = Albums::where('page_id', $id)
            ->take(6)->orderBy('id', 'DESC')->get();

        $page_data['all_videos'] = Media_files::where('page_id', $id)
            ->where('file_type', 'video')
            ->take(20)->orderBy('id', 'DESC')->get();

        $page_data['all_photos'] = $all_photos;
        $page_data['all_albums'] = $all_albums;
        $page_data['page'] = Cache::remember("page_with_relations_$id", 600, function () use ($id) {
            return Page::with(['city', 'area', 'pagecategories', 'likedByUsers'])
                ->withCount('likedByUsers') // adds liked_by_users_count
                ->find($id);
        });


        $all_reviews = Review::where('marketplace_id', $id)
            ->with('user')
            ->where('type', 'pages')
            ->latest()
            ->get();

        $page_data['reviews'] = $all_reviews->take(5); // First 5 reviews
        $page_data['has_more_reviews'] = $all_reviews->count() > 5;


        $ratingValue = $all_reviews->avg('rating') ?? 4.0;
        $reviewCount = $all_reviews->count();

        $page_data['rating'] = round($ratingValue, 1);
        $page_data['review_count'] = $reviewCount;

        // Check if the current user liked the page
        $page_data['page']['liked_by_user'] = false;

        if (auth()->user() && $page_data['page']) {
            $user = auth()->user();
            $page_data['page']['liked_by_user'] = $page_data['page']->likedByUsers->contains($user->id);
        }

        $page_data['suggestedpages'] = Cache::remember("suggested_pages_" . auth()->id(), 600, function () use ($friendsid) {
            return Page_like::with(['page.city', 'page.area', 'page.pagecategories'])
                ->whereIn('user_id', $friendsid)
                ->where('user_id', '!=', auth()->id())
                ->limit(1)
                ->get();
        });

        $page_data['groups'] = Group::select('groups.*', 'groupcategories.category_name')
            ->join('group_category', 'groups.id', '=', 'group_category.group_id')
            ->join('groupcategories', 'group_category.category_id', '=', 'groupcategories.id')
            ->where('groups.user_id', $page_data['page']->user_id)->get();

        $page_data['products'] = Marketplace::
            select('marketplaces.*')
            ->join('pages', 'marketplaces.page_id', 'pages.id')
            ->join('cities', 'pages.city_id', 'cities.id')
            ->join('category_product', 'marketplaces.id', 'category_product.product_id')
            ->distinct('marketplaces.id')
            ->where('marketplaces.product_status', 2)
            ->where('marketplaces.page_id', $page_data['page']->id)->orderBy('id', 'DESC')->get();

        $page_data['view_path'] = 'frontend.pages.products';
        return view('frontend.index', $page_data);
    }


    function blogs($city_slug, $area_slug, $category_slug, $item_slug)
    {
        $page_data['all_cities'] = CityHelper::getActiveCities();

        $id = Page::where('item_slug', $item_slug)->value('id');

        $friendsid = [];
        $friendidfind = '';
        if (auth()->user()) {
            $friends = Friendships::where('requester', auth()->user()->id)->orWhere('accepter', auth()->user()->id)->where('is_accepted', '1')->get();
            foreach ($friends as $friend) {
                $friendidfind = $friend->accepter == auth()->user()->id ? "$friend->requester" : "$friend->accepter";
                array_push($friendsid, $friendidfind);
            }
        }


        $all_photos = Media_files::where('page_id', $id)
            ->where('file_type', 'image')
            ->take(20)->orderBy('id', 'DESC')->get();

        $all_albums = Albums::where('page_id', $id)
            ->take(6)->orderBy('id', 'DESC')->get();

        $page_data['all_videos'] = Media_files::where('page_id', $id)
            ->where('file_type', 'video')
            ->take(20)->orderBy('id', 'DESC')->get();

        $page_data['all_photos'] = $all_photos;
        $page_data['all_albums'] = $all_albums;

        $pages = Page::findOrFail($id); // or whatever method you're using


        $page_data['pages'] = $pages;

        $city = City::where('city_slug', $city_slug)->first();
        $area = Area::where('area_slug', $area_slug)->where('city_id', $city->id)->first();
        $category = Pagecategory::where('category_slug', $category_slug)->first();



        SEOMeta::setTitle('Blogs ,' . $pages->title . ', ' . $area->area_name . ',' . $city->city_name);
        SEOMeta::setDescription('Blogs,' . $pages->title . ' ' . $area->area_name . ' ' . $city->city_name . ' ' . $category->categpry_name);

        SEOMeta::setCanonical(URL::current());

        $page_data['page'] = Cache::remember("page_with_relations_$id", 600, function () use ($id) {
            return Page::with(['city', 'area', 'pagecategories', 'likedByUsers'])
                ->withCount('likedByUsers') // adds liked_by_users_count
                ->find($id);
        });

        $all_reviews = Review::where('marketplace_id', $id)
            ->with('user')
            ->where('type', 'pages')
            ->latest()
            ->get();

        $page_data['reviews'] = $all_reviews->take(5); // First 5 reviews
        $page_data['has_more_reviews'] = $all_reviews->count() > 5;


        $ratingValue = $all_reviews->avg('rating') ?? 4.0;
        $reviewCount = $all_reviews->count();

        $page_data['rating'] = round($ratingValue, 1);
        $page_data['review_count'] = $reviewCount;

        // Check if the current user liked the page
        $page_data['page']['liked_by_user'] = false;

        if (auth()->user() && $page_data['page']) {
            $user = auth()->user();
            $page_data['page']['liked_by_user'] = $page_data['page']->likedByUsers->contains($user->id);
        }

        $page_data['suggestedpages'] = Cache::remember("suggested_pages_" . auth()->id(), 600, function () use ($friendsid) {
            return Page_like::with(['page.city', 'page.area', 'page.pagecategories'])
                ->whereIn('user_id', $friendsid)
                ->where('user_id', '!=', auth()->id())
                ->limit(1)
                ->get();
        });

        $page_data['blogs'] = DB::table('blogs')->select(
            'blogs.*',
            'cities.city_slug',
            'areas.area_slug',
            'cities.city_name',
            'areas.area_name',
            'states.state_name',
            'users.name as username',
            'users.id as userid'
        )
            ->join('cities', 'cities.id', 'blogs.city_id')
            ->join('areas', 'areas.id', 'blogs.area_id')
            ->join('states', 'states.id', 'blogs.state_id')
            ->join('blog_category', 'blog_category.blog_id', 'blogs.id')
            ->join('users', 'users.id', 'blogs.user_id')
            ->distinct('blogs.id')->where('list_id', $page_data['page']->id)
            ->where('blogs.blog_status', 2)->orderBy('id', 'DESC')->get();

        $page_data['view_path'] = 'frontend.pages.blogs';
        return view('frontend.index', $page_data);
    }

    public function getPageSuggestions(Request $request)
    {
        $query = $request->input("query");
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        $pages = Page::where("title", "LIKE", "%$query%")->take(10)->get([
            "id",
            "title",
        ]);
        return response()->json($pages);
    }

    public function ad_demo(Request $request)
    {
        $page_data['view_path'] = 'frontend.ads.master';
        return view('frontend.index', $page_data);
    }
}