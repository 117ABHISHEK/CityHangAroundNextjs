<?php

namespace App\Http\Controllers\Report;

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

class SearchController extends Controller
{
    public function search_globally(Request $request)
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
    
        // Get search query and cityid
        $search_param = $request->input('search');
        $cityid = $request->input('cityid'); // Get city ID from request

        $subQuery = DB::table('page_category')
        ->select('page_id', DB::raw('MAX(page_category.id) as latest_page_category_id'))
        ->groupBy('page_id');
    
        // Fetch relevant data from multiple models
        $query = DB::table('pages')
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
        ->where('title', 'LIKE', "%{$search_param}%");
        // Filter by cityid if provided and not 0
        if (!empty($cityid) && $cityid != 0) {
            $query->where('pages.city_id', $cityid);
        }
    
        // Execute the query
        $data['pages'] = $query->limit(50)->get();
    
        // Filter marketplace results
        $marketplaceQuery = Marketplace::select('marketplaces.id', 'marketplaces.title')
            ->join('pages', 'marketplaces.page_id', '=', 'pages.id')
            ->where('marketplaces.product_status', 2)
            ->where('marketplaces.title', 'LIKE', "%{$search_param}%");
    
        if (!empty($cityid) && $cityid != 0) {
            $marketplaceQuery->where('pages.city_id', $cityid);
        }
    
        $data['marketplace'] = $marketplaceQuery->groupBy('marketplaces.id', 'marketplaces.title')->limit(50)->get();
    
        // Filter event results
        $eventQuery = Event::where('title', 'LIKE', "%{$search_param}%")
            ->where('events.event_date', '>=', Carbon::now());
    
        if (!empty($cityid) && $cityid != 0) {
            $eventQuery->where('events.city_id', $cityid);
        }
    
        $data['events'] = $eventQuery->limit(50)->get();
    
        // Filter blog results
        $blogQuery = Blog::where('title', 'LIKE', "%{$search_param}%");
    
        if (!empty($cityid) && $cityid != 0) {
            $blogQuery->where('blogs.city_id', $cityid);
        }
    
        $data['blogs'] = $blogQuery->limit(50)->get();
    
        // Filter users by city if applicable
        $userQuery = User::where('name', 'LIKE', "%{$search_param}%");
    
        // if (!empty($cityid) && $cityid != 0) {
        //     $userQuery->where('users.cityid', $cityid);
        // }
    
        $data['users'] = $userQuery->limit(50)->get();
    
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

        // SEO Slug Resolution
        if ($city_slug && !$cityId) {
            $city = DB::table('cities')->where('city_slug', $city_slug)->first();
            if ($city) {
                $cityId = $city->id;
            } else {
                // Check if it's a category (fallback for search/{category})
                $category = DB::table('pagecategories')->where('category_slug', $city_slug)->first();
                if ($category) {
                    $catId = $category->id;
                    $city_slug = null; // It was actually a category
                }
            }
        }

        if ($category_slug && !$catId) {
            $category = DB::table('pagecategories')->where('category_slug', $category_slug)->first();
            if ($category) {
                $catId = $category->id;
            } else {
                $areaCheck = DB::table('areas')->where('area_slug', $category_slug)->first();
                if ($areaCheck) {
                    return app(\App\Http\Controllers\PageController::class)->area($request, $city_slug, $category_slug);
                }
                
                // Check if it's a city (fallback for search/{city}/{something_else})
                $city = DB::table('cities')->where('city_slug', $category_slug)->first();
                if ($city) {
                    $cityId = $city->id;
                }
            }
        }

        $category = Pagecategory::where('id', $catId)->first(); 
        $city = DB::table('cities')->select('cities.*')->where('id', $cityId)->first();

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
            
            // Use paginate(1) on an empty query to get a Paginator instance instead of a simple Collection
            $page_data['mypages'] = DB::table('pages')->where('id', 0)->paginate(50);
            
            $page_data['parent_categories'] = collect();
            $page_data['all_cities'] = DB::table('cities')->where('is_approved', 'Y')->get();
            $page_data['all_categories'] = collect();
            $page_data['filter_areas'] = DB::table('areas')->where('city_id', $cityId)->get();
            $page_data['filter_sort_by'] = "newest";
            
            $page_data['view_path'] = 'frontend.pages.searchview';
            return view('frontend.page_index_search', $page_data);
        }

        $page_data['city']=$city;
        $page_data['category']=$category;

        $page_data['category_header']=$catId;
        $page_data['city_header']=$cityId;

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


        $page_data['all_cities'] = DB::table('cities')->where('is_approved', 'Y')->orderBy('city_name', 'asc')->get();

        $page_data['filter_areas'] = DB::table('areas')->where('city_id', $cityId)->get();

        /**
         * Category dropdown must not show empty categories for the selected city.
         * Source of truth: content_master (category_count rows).
         *
         * NOTE: We return parent categories only (category_parent_id NULL) because the UI
         * shows parent categories in the dropdown and tag list.
         */
        $page_data['all_categories'] = DB::table('pagecategories as pc')
            ->select('pc.*')
            ->whereNull('pc.category_parent_id')
            ->whereExists(function ($q) use ($cityId) {
                $q->select(DB::raw(1))
                    ->from('content_master as cm')
                    ->where('cm.source_type', 'category_count')
                    ->where('cm.status', 'listing')
                    ->where('cm.total_count', '>', 0)
                    ->when(!empty($cityId) && $cityId != 0, fn($qq) => $qq->where('cm.city_id', $cityId))
                    ->whereRaw('(cm.category_id = pc.id OR cm.parent_category_id = pc.id)');
            })
            ->orderBy('pc.category_name', 'asc')
            ->get();

        $paid_items_query= DB::table('pages')->select('pages.id','pages.item_slug','pages.logo','pages.title','cities.city_slug','areas.area_slug'
        ,'cities.city_name','areas.area_name','states.state_name','pages.created_at')
        ->join('cities','cities.id','pages.city_id')
        ->join('areas','areas.id','pages.area_id')
        ->join('states','states.id','pages.state_id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','page_category.category_id','=','pagecategories.id')
        ->where('pages.item_status',2)
        ->when($cityId, fn($q) => $q->where('pages.city_id', $cityId))
        ->where(function ($query) use ($catId) {
            $query->where('page_category.category_id', $catId)
            ->orWhere('pagecategories.category_parent_id',$catId);
        })
        ->distinct('pages.id');

        $filter_sort_by = empty($request->filter_sort_by) ? "newest" : $request->filter_sort_by;
        $page_data['filter_sort_by']=$filter_sort_by ;
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
        $paid_items=$paid_items_query->orderBy('pages.id','DESC')->paginate(50);
       
        $querystringArray = [
            'filter_sort_by' => $filter_sort_by,
        ];
        $paid_items->appends($querystringArray);
        $page_data['mypages']=$paid_items;

        // Fetch areas for the selected city to fix "Undefined variable $filter_areas"
        $page_data['filter_areas'] = DB::table('areas')->where('city_id', $cityId)->get();

        $page_data['view_path'] = 'frontend.pages.searchview';
        return view('frontend.page_index_search', $page_data);
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