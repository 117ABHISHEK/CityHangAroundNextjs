<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Blogcategory;
use App\Models\Category;
use App\Models\Review;
use App\Models\Comments;
use App\Models\ManageApproval;
use App\Models\FileUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Image, Session, Share;
use DB;

use Illuminate\Support\Facades\URL;
use Artesaos\SEOTools\Facades\SEOMeta;
use App\Helpers\CityHelper;
use App\Services\UserActivityService;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Get optimized filter metrics for blogs without relying on heavy or missing master counters.
     */
    private function getBlogFilterData(Request $request)
    {
        $filter_city = $request->input('city');
        $filter_area = $request->input('area', '0');
        $filter_sort_by = $request->input('filter_sort_by', 'newest');
        $filter_category = $request->input('category', '0');

        // Optimized cities for blogs
        $all_blog_cities = Cache::remember('active_blog_cities_v2', 3600, function () {
            return DB::table('cities')
                ->join('content_master', 'content_master.city_id', '=', 'cities.id')
                ->where('content_master.source_type', 'blog')
                ->select('cities.id', 'cities.city_name', 'cities.city_slug')
                ->distinct()
                ->orderBy('cities.city_name')
                ->get();
        });

        $catCacheKey = "blog_cats_city_{$filter_city}_area_{$filter_area}_v2";
        $categories = Cache::remember($catCacheKey, 1800, function () use ($filter_city, $filter_area) {
            // Count listings in content_master mapped to blogcategories
            $base = DB::table('content_master')
                ->select('content_master.category_id', DB::raw('COUNT(*) as total'))
                ->where('content_master.source_type', 'blog')
                ->when($filter_city, fn($q) => $q->where('content_master.city_id', $filter_city))
                ->when($filter_area && $filter_area !== '0', fn($q) => $q->where('content_master.area_id', $filter_area))
                ->groupBy('content_master.category_id');

            return DB::table('blogcategories')
                ->select(
                    'blogcategories.id',
                    'blogcategories.category_name',
                    'blogcategories.category_slug',
                    'counts.total as blog_count'
                )
                ->joinSub($base, 'counts', 'counts.category_id', '=', 'blogcategories.id')
                ->distinct()
                ->orderBy('blogcategories.category_name')
                ->get();
        });

        $all_areas = ($filter_city && $filter_city !== '0')
            ? Cache::remember("active_blog_areas_city_{$filter_city}_v2", 3600, function () use ($filter_city) {
                return DB::table('areas')
                    ->join('content_master', 'content_master.area_id', '=', 'areas.id')
                    ->where('content_master.source_type', 'blog')
                    ->where('content_master.city_id', $filter_city)
                    ->select('areas.id', 'areas.area_name', 'areas.area_slug')
                    ->distinct()
                    ->orderBy('areas.area_name')
                    ->get();
            })
            : collect();

        return [
            'all_blog_cities' => $all_blog_cities,
            'categories' => $categories,
            'all_areas' => $all_areas,
            'filter_city' => $filter_city,
            'filter_area' => $filter_area,
            'filter_sort_by' => $filter_sort_by,
            'filter_category' => $filter_category
        ];
    }

    /**
     * Optimized main blogs directory stream combining layout expectations and fast indexing
     */
    public function blogs(Request $request)
    {
        SEOMeta::setTitle('Latest Blogs & Articles | Cityhangaround');
        SEOMeta::setDescription("Explore the latest blogs, articles, and guides on Cityhangaround. Stay updated with trending topics, tips, and insights from various categories. Discover what's happening around your city today!");
        SEOMeta::setCanonical(URL::current());
        SEOMeta::setKeywords([
            'City blogs', 'latest blogs', 'trending articles', 'local news', 'city events',
            'lifestyle blogs', 'CityHangAround blogs', 'community blogs', 'tips and guides', 'latest updates'
        ]);

        $page_data = $this->getBlogFilterData($request);
        $filter_city = $page_data['filter_city'];
        $filter_area = $page_data['filter_area'];
        $filter_sort_by = $page_data['filter_sort_by'];
        $filter_category = $page_data['filter_category'];
        $search_param = $request->title;

        // Content Master Based Query structure optimized for fast rendering
        $blog_query = DB::table('content_master')
            ->select([
                'blogs.id',
                'blogs.title',
                'blogs.blog_slug',
                'blogs.thumbnail',
                'blogs.created_at',
                'blogs.publication_date',
                'blogs.blog_status',
                'blogs.publication_status',
                'blogs.item_featured',
                'blogs.user_id',
                'blogs.state_id',
                'blogs.city_id',
                'blogs.area_id',
                'blogs.auther_name',

                'cities.city_slug',
                'areas.area_slug',
                'cities.city_name',
                'areas.area_name',
                'states.state_name',

                'users.name as username',
                'users.name as name', // UI fallback criteria compatibility
                'users.id as userid',
                'users.photo as user_photo',

                DB::raw('MAX(blogcategories.category_slug) as cat_slug'),
                DB::raw('MAX(blogcategories.category_name) as cat_name'),
            ])
            ->join('blogs', 'blogs.id', '=', 'content_master.source_id')
            ->join('cities', 'cities.id', '=', 'content_master.city_id')
            ->join('areas', 'areas.id', '=', 'content_master.area_id')
            ->leftJoin('states', 'states.id', '=', 'blogs.state_id')
            ->join('users', 'users.id', '=', 'blogs.user_id')
            ->leftJoin('blogcategories', 'blogcategories.id', '=', 'content_master.category_id')
            ->where('content_master.source_type', 'blog')
            ->where('blogs.blog_status', 2);

        // Filters mapping
        if (!empty($search_param)) {
            $blog_query->where('blogs.title', 'LIKE', "%{$search_param}%");
        }
        if (!empty($filter_city) && $filter_city !== '0') {
            $blog_query->where('content_master.city_id', $filter_city);
        }
        if (!empty($filter_area) && $filter_area !== '0') {
            $blog_query->where('content_master.area_id', $filter_area);
        }
        if (!empty($filter_category) && $filter_category !== '0') {
            $blog_query->whereIn('content_master.category_id', function ($q) use ($filter_category) {
                $q->select('blogcategories.id')
                    ->from('blogcategories')
                    ->where('blogcategories.category_slug', $filter_category);
            });
        }

        // Sorting Logic
        if ($filter_sort_by === 'oldest') {
            $blog_query->orderBy('blogs.created_at', 'ASC');
        } elseif ($filter_sort_by === 'highest-rated') {
            $blog_query->orderByDesc('blogs.id');
        } else {
            $blog_query->orderByDesc('blogs.item_featured')
                ->orderByDesc('blogs.id');
        }

        $blog_query->groupBy([
            'blogs.id', 'blogs.title', 'blogs.blog_slug', 'blogs.thumbnail', 'blogs.created_at',
            'blogs.publication_date', 'blogs.blog_status', 'blogs.publication_status', 'blogs.item_featured',
            'blogs.user_id', 'blogs.state_id', 'blogs.city_id', 'blogs.area_id', 'blogs.auther_name',
            'cities.city_slug', 'areas.area_slug', 'cities.city_name', 'areas.area_name', 'states.state_name',
            'users.name', 'users.id', 'users.photo',
        ]);

        $blogs = $blog_query->paginate(50);
        $blogs->appends([
            'title' => $search_param,
            'city' => $filter_city,
            'area' => $filter_area,
            'filter_sort_by' => $filter_sort_by,
            'category' => $filter_category
        ]);

        $page_data['blogs'] = $blogs;
        $page_data['filter_city'] = $filter_city;
        $page_data['filter_area'] = $filter_area;
        $page_data['filter_sort_by'] = $filter_sort_by;
        $page_data['filter_category'] = $filter_category;

        // Dynamic Trending Sidebar items logic preserved from blogcon.txt
        $page_data['recentBusinesses'] = \App\Models\Page::with(['user', 'category', 'city', 'area', 'categories'])->orderBy('created_at', 'DESC')->take(5)->get();
        $page_data['recentProducts'] = \App\Models\Marketplace::with(['user', 'category', 'page.city', 'page.area', 'page.categories', 'productCategories', 'getCurrency'])->where('product_status', 2)->orderBy('created_at', 'DESC')->take(5)->get();
        $page_data['recentEvents'] = \App\Models\Event::with(['user', 'category', 'city', 'area', 'categories'])->orderBy('created_at', 'DESC')->take(5)->get();

        $page_data['view_path'] = 'frontend.blogs.blogs';
        return view('frontend.blog_index', $page_data);
    }

    /**
     * Fix for Missing Method Route Error: Handles Category-wise Blog View
     */
    /**
     * Fix for Missing Method Route Error: Handles Category-wise Blog View
     */
   /**
     * Fix for: Undefined variable $category in Category-wise Blog View
     */
    /**
     * Fix for: Undefined variable $filter_sort_by in Category-wise Blog View
     */
    /**
     * Complete Fix for Category-wise Blog View: Handles all missing blade variables
     */
/**
     * Complete Fix for Category-wise Blog View: Handles all missing blade variables including $view_path
     */
   /**
     * Bulletproof Fix for Category Blog View: Solves $view_path & Memory Exhaustion Issue
     */
    /**
     * Permanent Fix: Solves Column not found 'slug' and maintains Memory Protection
     */
    /**
     * Complete HTTP 500 Fix for category_blog route
     */
    public function category_blog(Request $request, $category_slug)
{
    $category = Blogcategory::where('category_slug', $category_slug)->first();
    $page_data['category'] = $category;

    if ($category) {
        
        // 1. CITIES DROPDOWN: Untouched
        $page_data['all_blog_cities'] = DB::table('cities')->select('cities.*')
            ->join('blogs', 'blogs.city_id', '=', 'cities.id')
            ->distinct('cities.id')
            ->where('blogs.blog_status', 2)
            ->orderBy('cities.city_name', 'asc')
            ->get();

        // 2. CATEGORIES DROPDOWN: Untouched
        $page_data['categories'] = DB::table('blogcategories')->select('blogcategories.*')
            ->join('blog_category', 'blog_category.category_id', '=', 'blogcategories.id')
            ->join('blogs', 'blogs.id', '=', 'blog_category.blog_id')
            ->where('blogs.blog_status', 2)
            ->distinct()
            ->get();

        $filter_city = $request->input('city');
        $filter_area = $request->input('area');
        $filter_category = $request->input('category'); 

        // 3. DYNAMIC AREAS DROPDOWN: Untouched
        $all_areas = collect();
        if (!empty($filter_city) && $filter_city !== "0") {
            $all_areas = DB::table('areas')
                ->where('city_id', $filter_city)
                ->select('id', 'area_name')
                ->orderBy('area_name', 'asc')
                ->get();
        }
        $page_data['all_areas'] = $all_areas; 

        // SEO Meta
        SEOMeta::setTitle($category->category_name . ' Blogs & Articles | Latest ' . $category->category_name . ' News | Cityhangaround');
        SEOMeta::setDescription("Explore the latest {$category->category_name} blogs, articles, tips, and guides on CityHangAround.");
        SEOMeta::setCanonical(URL::current());

        $page_data['category_id'] = $category->id;
        $page_data['filter_city'] = $filter_city;
        $page_data['filter_area'] = $filter_area;
        $page_data['filter_category'] = $filter_category;

        $filter_sort_by = $request->filter_sort_by ?? "newest";
        $page_data['filter_sort_by'] = $filter_sort_by;

        // 🟢 CONTENT_MASTER QUERY WITH CORRECT KEY MAPPING:
        $blogs_query = DB::table('content_master')
            ->leftJoin('cities', 'cities.id', '=', 'content_master.city_id')
            ->leftJoin('areas', 'areas.id', '=', 'content_master.area_id')
            ->leftJoin('users', 'users.id', '=', 'content_master.user_id')
            ->select(
                'content_master.source_id as id',                       
                'content_master.id as content_master_id',
                'content_master.title as title',
                'content_master.slug as blog_slug',
                'content_master.location as thumbnail',                  
                'content_master.description as description',
                'content_master.created_at',
                'cities.city_slug',
                'areas.area_slug',
                'cities.city_name',
                'areas.area_name',
                'users.name as username',
                'users.id as userid'
            )
            ->where('content_master.source_type', 'blog');

        // City Filter Apply
        if (!empty($filter_city) && $filter_city !== "0") {
            $blogs_query->where('content_master.city_id', $filter_city);
        }

        // Area Filter Apply
        if (!empty($filter_area) && $filter_area !== "0") {
            $blogs_query->where('content_master.area_id', $filter_area);
        }

        // Sorting
        if ($filter_sort_by === "oldest") {
            $blogs_query->orderBy('content_master.created_at', 'ASC');
        } else {
            $blogs_query->orderBy('content_master.created_at', 'DESC');
        }

        // Pagination
        $paid_items = $blogs_query->distinct('content_master.id')->paginate(50);
        
        $paid_items->appends([
            'filter_sort_by' => $filter_sort_by,
            'city' => $filter_city,
            'area' => $filter_area,
            'category' => $filter_category,
        ]);

        $page_data['blogs'] = $paid_items;
        $page_data['view_path'] = 'frontend.blogs.category_blog';

        return view('frontend.category_blog_index', $page_data);
    }
    
    abort(404);
}
    public function myblog()
    {
        $page_data['all_cities'] = DB::table('cities')->select('cities.*', 'pages.id')
            ->join('pages', 'pages.city_id', 'cities.id')
            ->join('page_category', 'page_category.page_id', 'pages.id')
            ->join('pagecategories', 'page_category.category_id', '=', 'pagecategories.id')
            ->distinct('pages.id')
            ->where('pages.item_status', 2)
            ->orderBy('pages.id', 'DESC')->get();

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
            ->leftjoin('cities', 'cities.id', 'blogs.city_id')
            ->leftjoin('areas', 'areas.id', 'blogs.area_id')
            ->leftjoin('states', 'states.id', 'blogs.state_id')
            ->join('blog_category', 'blog_category.blog_id', 'blogs.id')
            ->join('users', 'users.id', 'blogs.user_id')
            ->distinct('blogs.id')->where('user_id', auth()->user()->id)
            ->where('blogs.blog_status', 2)->orderBy('id', 'DESC')->get();

        $page_data['view_path'] = 'frontend.blogs.user_blog';
        return view('frontend.index', $page_data);
    }

    public function create()
    {
        $page_data['all_cities'] = CityHelper::getCitiesForBlogs();
        $page_data['parent'] = DB::table('pagecategories')->where('pagecategories.category_parent_id', null)->get();
        $page_data['printable_categories'] = DB::table('pagecategories')->where('category_parent_id', null)->get();
        $page_data['all_states'] = DB::table('states')->where('country_id', 101)->get();
        
        $page_data['listing'] = DB::table('pages')->select('pages.*')
            ->join('page_category', 'page_category.page_id', 'pages.id')
            ->where('pages.item_status', 2)
            ->where('pages.user_id', auth()->user()->id)
            ->distinct('pages.id')
            ->orderBy('pages.id', 'DESC')
            ->limit(500)
            ->get();

        $page_data['countries'] = DB::table('countries')->where('id', 101)->get();
        $page_data['view_path'] = 'frontend.blogs.create_blog';
        return view('frontend.index', $page_data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'author' => 'required|max:255',
            'title' => 'required|max:255',
            'category' => 'required',
        ]);

        if ($request->image && !empty($request->image)) {
            $file_name = FileUploader::upload($request->image, 'public/storage/blog/thumbnail', 370);
            FileUploader::upload($request->image, 'public/storage/blog/coverphoto/' . $file_name, 900);
        }

        $title = 'blog';
        $approval = ManageApproval::where('title', $title)->first();
        if ($approval && $approval->status == 1) {
            $blog_status = 2;
        } elseif (auth()->check() && auth()->user()->user_role == 'admin') {
            $blog_status = 2;
        } else {
            $blog_status = 1;
        }

        $state_id = $request->state ? $request->state : null;
        $city_id = $request->city ? $request->city : null;
        $area_id = $request->area ? $request->area : null;
        $country_id = $request->country ? $request->country : null;

        $blog_slug = preg_replace("/[^A-Za-z0-9 ]/", '', $request->title);
        $multiSelectArray = $request->category;
        $parent_id = $request->parent;

        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id;
        }
        $categories_id = implode(',', $multiSelectArray);

        $blog = new Blog();
        $blog->user_id = Auth::user()->id;
        $blog->auther_name = $request->author;
        $blog->title = $request->title;
        $blog->blog_slug = str_slug($blog_slug);
        $blog->category_id = $categories_id;
        $blog->state_id = $state_id;
        $blog->city_id = $city_id;
        $blog->area_id = $area_id;
        $blog->blog_status = $blog_status;
        $blog->publication_date = $request->publication_date;
        $blog->country_id = $country_id;
        $blog->list_id = $request->List;
        $blog->publication_status = $request->status;

        $tags = json_decode($request->tag, true);
        $tag_array = array();
        if (is_array($tags)) {
            foreach ($tags as $key => $tag) {
                $tag_array[$key] = $tag['value'];
            }
        }
        $blog->tag = json_encode($tag_array);
        $blog->description = $request->description;

        if ($request->image && !empty($request->image)) {
            $blog->thumbnail = $file_name;
        }
        $blog->view = json_encode(array());

        $user = auth()->user();
        $activeSubscription = $user->activeSubscription()->with('subscription')->first();
        if ($activeSubscription && $activeSubscription->subscription && Str::contains($activeSubscription->subscription->offered_services, 'blogs')) {
            $durations = json_decode($activeSubscription->subscription->area_durations, true);
            $cityDays = $durations['blogs']['city'] ?? 0;
            $areaDays = $durations['blogs']['area'] ?? 0;
            $subscriptionStart = \Carbon\Carbon::parse($activeSubscription->created_at ?? now());
            $priorityEnd = $subscriptionStart->copy()->addDays(max($cityDays, $areaDays));

            if ($cityDays > 0) $blog->priority_until_city = $subscriptionStart->copy()->addDays($cityDays);
            if ($areaDays > 0) $blog->priority_until_area = $subscriptionStart->copy()->addDays($areaDays);
            if ($priorityEnd->isFuture()) $blog->item_featured = 1;
        }

        $done = $blog->save();
        if ($done) {
            foreach ($request->category as $key => $category_id) {
                $data = array(
                    'category_id' => $category_id,
                    "blog_id" => $blog->id
                );
                DB::table('blog_category')->insertGetId($data);
            }
            $slug_count = DB::table('blogs')->where('blogs.blog_slug', str_slug($request->title))->count();
            if ($slug_count > 1) {
                DB::table('blogs')->where('id', $blog->id)
                    ->update(array('blog_slug' => DB::raw('concat("' . str_slug($request->title) . '",' . '-' . $blog->id . ')')));
            }
            if (auth()->user()) {
                app(UserActivityService::class)->log(auth()->user()->id, 'create_blog', 'Created a new blog post titled: ' . $blog->title);
            }
            return redirect()->route('user.myblog')->with('success', 'Blog saved successfully');
        }
    }

    public function search()
    {
        $search = $_GET['search'];
        $output = "";
        $posts = Blog::where('title', 'LIKE', '%' . $search . "%")->get();
        if ($posts) {
            foreach ($posts as $key => $post) {
                $output .= '<div class="post-entry d-flex">' .
                    '<div class="post-thumb"><img class="img-fluid rounded" src=" ' . get_blog_image($post->thumbnail, "thumbnail") . ' " alt="Recent Post"> </div>' .
                    '<div class="post-txt ms-2">' .
                    '<h3><a href="' . route("single.blog", $post->id) . '"> ' . $post->title . '</a></h3>' .
                    '<div class="post-meta">' .
                    '<span class="date-meta"><a href="#">' . $post->created_at . '</a></span>' .
                    '</div>' .
                    '</div>' .
                    '</div>';
            }
            return Response($output);
        }
    }
  
  public function get_city_areas(Request $request)
{
    
    $city_id = $request->input('city_id') ?? $request->input('city');

 
    if (empty($city_id) || $city_id == "0") {
        return response()->json([]);
    }

    try {
   
        $areas = DB::table('areas')
            ->where('city_id', $city_id)
            ->select('id', 'area_name') 
            ->orderBy('area_name', 'asc')
            ->get();

        
        return response()->json($areas);

    } catch (\Exception $e) {
        return response()->json([]);
    }
}


public function getCityAreas(Request $request)
{
    return $this->get_city_areas($request);
}
}