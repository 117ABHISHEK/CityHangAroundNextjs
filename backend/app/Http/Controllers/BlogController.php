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
    private function getBlogFilterData(Request $request)
    {
        $filter_city = $request->input('city');
        $filter_area = $request->input('area', '0');
        $filter_sort_by = $request->input('filter_sort_by', 'newest');
        $filter_category = $request->input('category', '0');

        // Optimized cities/categories/areas for blogs WITHOUT relying on category_counts_master.
        // `/blog/all` must work even when category_counts_master table is missing.
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
            // Count listings in content_master mapped to groupcategories
            $base = DB::table('content_master')
                ->select('content_master.category_id', DB::raw('COUNT(*) as total'))
                ->where('content_master.source_type', 'blog')
                ->when($filter_city, fn($q) => $q->where('content_master.city_id', $filter_city))
                ->when($filter_area && $filter_area !== '0', fn($q) => $q->where('content_master.area_id', $filter_area))
                ->groupBy('content_master.category_id');

            return DB::table('groupcategories')
                ->select(
                    'groupcategories.id',
                    'groupcategories.category_name',
                    'groupcategories.category_slug',
                    'counts.total as blog_count'
                )
                ->joinSub($base, 'counts', 'counts.category_id', '=', 'groupcategories.id')
                ->distinct()
                ->orderBy('groupcategories.category_name')
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
    // public function blogs(){
    //     $page_data['all_cities']= DB::table('cities')->select('cities.*','pages.id')
    //     ->join('pages','pages.city_id','cities.id')
    //     ->join('page_category','page_category.page_id','pages.id')
    //     ->join('pagecategories','page_category.category_id','=','pagecategories.id')
    //     ->distinct('pages.id')
    //     ->where('pages.item_status',2)
    //     ->orderBy('pages.id','DESC')->get();
    //     //$page_data['categories'] = Category::all();

    //     $page_data['categories']= DB::table('groupcategories')->select('groupcategories.*')
    //     ->join('blog_category','blog_category.category_id','=','groupcategories.id')
    //     ->join('blogs','blogs.id','=','blog_category.blog_id')
    //     ->where('blogs.blog_status',2)
    //     ->distinct()
    //     ->get();

    //     $page_data['blogs'] = DB::table('blogs')->select('blogs.*','cities.city_slug','areas.area_slug',
    //     'cities.city_name','areas.area_name','states.state_name','users.name as username','users.id as userid' )
    //     ->join('cities','cities.id','blogs.city_id')
    //     ->join('areas','areas.id','blogs.area_id')
    //     ->join('states','states.id','blogs.state_id')
    //     ->join('blog_category','blog_category.blog_id','blogs.id')
    //     ->join('users','users.id','blogs.user_id')
    //     ->distinct('blogs.id')
    //     ->where('blogs.blog_status',2)->orderBy('id','DESC')->limit('10')->get();

    //     //$page_data['blogs'] = Blog::orderBy('id','DESC')->limit('10')->get();
    //     $page_data['view_path'] = 'frontend.blogs.blogs';
    //     return view('frontend.index', $page_data);
    // }

    public function blogs(Request $request)
    {

        SEOMeta::setTitle('Latest Blogs & Articles | Cityhangaround');
        SEOMeta::setDescription("Explore the latest blogs, articles, and guides on Cityhangaround. Stay updated with trending topics, tips, and insights from various categories. Discover what's happening around your city today!");
        SEOMeta::setCanonical(URL::current());
        //  Adding Keywords
        SEOMeta::setKeywords([
            'City blogs',
            'latest blogs',
            'trending articles',
            'local news',
            'city events',
            'lifestyle blogs',
            'CityHangAround blogs',
            'community blogs',
            'tips and guides',
            'latest updates'
        ]);


        $page_data = $this->getBlogFilterData($request);
        $filter_city = $page_data['filter_city'];
        $filter_area = $page_data['filter_area'];
        $filter_sort_by = $page_data['filter_sort_by'];
        $filter_category = $page_data['filter_category'];
        $search_param = $request->title;

        /**
         * FAST LISTING:
         * Use content_master instead of heavy blogs + blog_category + groupcategories joins.
         * content_master is already used in your sidebar filters for better performance.
         */
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
                'users.id as userid',
                'users.photo as user_photo',

                // Blade expects cat_slug/cat_name
                DB::raw('MAX(groupcategories.category_slug) as cat_slug'),
                DB::raw('MAX(groupcategories.category_name) as cat_name'),
            ])
            ->join('blogs', 'blogs.id', '=', 'content_master.source_id')
            ->join('cities', 'cities.id', '=', 'content_master.city_id')
            ->join('areas', 'areas.id', '=', 'content_master.area_id')
            ->leftJoin('states', 'states.id', '=', 'blogs.state_id')
            ->join('users', 'users.id', '=', 'blogs.user_id')
            ->leftJoin('groupcategories', 'groupcategories.id', '=', 'content_master.category_id')
            ->where('content_master.source_type', 'blog')
            ->where('blogs.blog_status', 2);

        // Search
        if (!empty($search_param)) {
            $blog_query->where('blogs.title', 'LIKE', "%{$search_param}%");
        }

        // City
        if (!empty($filter_city) && $filter_city !== '0') {
            $blog_query->where('content_master.city_id', $filter_city);
        }

        // Area
        if (!empty($filter_area) && $filter_area !== '0') {
            $blog_query->where('content_master.area_id', $filter_area);
        }

        // Category (UI sends category slug for category.blog)
        if (!empty($filter_category) && $filter_category !== '0') {
            $blog_query->whereIn('content_master.category_id', function ($q) use ($filter_category) {
                $q->select('groupcategories.id')
                    ->from('groupcategories')
                    ->where('groupcategories.category_slug', $filter_category);
            });
        }

        // Sorting
        if ($filter_sort_by === 'oldest') {
            $blog_query->orderBy('blogs.created_at', 'ASC');
        } elseif ($filter_sort_by === 'highest-rated') {
            $blog_query->orderByDesc('blogs.id');
        } else {
            $blog_query->orderByDesc('blogs.item_featured')
                ->orderByDesc('blogs.id');
        }

        $blog_query->groupBy([
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

            'users.name',
            'users.id',
            'users.photo',
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

        // Fetch trending items for the sidebar (like home page)
        $page_data['recentBusinesses'] = collect();
        $page_data['recentProducts'] = collect();
        $page_data['recentEvents'] = collect();

        $page_data['view_path'] = 'frontend.blogs.blogs';
        return view('frontend.blog_index', $page_data);

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
        // $blogs = Blog::where('user_id',auth()->user()->id)->orderBy('id','DESC')->get();
        // $page_data['blogs'] = $blogs;

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
            ->where('blogs.blog_status', 2)->orderBy('blogs.id', 'DESC')->get();
        $page_data['view_path'] = 'frontend.blogs.user_blog';
        return view('frontend.index', $page_data);
    }

    public function create()
    {
        // Use blog-specific city function to prevent memory exhaustion
        $page_data['all_cities'] = CityHelper::getCitiesForBlogs();
        $page_data['parent'] = DB::table('groupcategories')
            ->where(function($q) {
                $q->whereNull('category_parent_id')
                  ->orWhere('category_parent_id', 0);
            })->get();
        $page_data['printable_categories'] = DB::table('groupcategories')
            ->where(function($q) {
                $q->whereNull('category_parent_id')
                  ->orWhere('category_parent_id', 0);
            })->get();
        $page_data['all_states'] = DB::table('states')->select('states.*')
            ->where('country_id', 101)->get();

        $page_data['listing'] = DB::table('pages')->select('pages.*')
            ->join('page_category', 'page_category.page_id', 'pages.id')
            ->where('pages.item_status', 2)
            ->where('pages.user_id', auth()->user()->id)
            ->distinct('pages.id')
            ->orderBy('pages.id', 'DESC')
            ->limit(500) // Add limit to prevent memory issues
            ->get();

        $page_data['countries'] = DB::table('countries')->select('countries.*')
            ->where('id', 101)->get();

        //$page_data['blog_category'] = Blogcategory::all();
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
            // Approval status is ON
            $blog_status = 2;

        } elseif (auth()->check() && auth()->user()->user_role == 'admin') {
            // Status is OFF but user is admin
            $blog_status = 2;

        } else {
            //Status is OFF and user is not admin
            $blog_status = 1;
        }




        if ($request->state) {

            $state_id = $request->state;
        } else {
            $state_id = null;

        }

        if ($request->city) {

            $city_id = $request->city;
        } else {
            $city_id = null;

        }

        if ($request->area) {

            $area_id = $request->area;
        } else {
            $area_id = null;

        }

        if ($request->country) {

            $country_id = $request->country;
        } else {
            $country_id = null;

        }


        $blog_slug = preg_replace("/[^A-Za-z0-9 ]/", '', $request->title);


        $multiSelectArray = $request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
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

        $blog->publication_date = $request->publication_date ?? date('Y-m-d');
        $blog->country_id = $country_id;
        $blog->list_id = $request->List;
        $blog->publication_status = $request->status ?? 'publish';





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

            $subscriptionStart = Carbon::parse($activeSubscription->created_at ?? now());


            $priorityEnd = $subscriptionStart->copy()->addDays(max($cityDays, $areaDays));

            if ($cityDays > 0)
                $blog->priority_until_city = $subscriptionStart->copy()->addDays($cityDays);
            if ($areaDays > 0)
                $blog->priority_until_area = $subscriptionStart->copy()->addDays($areaDays);
            if ($priorityEnd->isFuture())
                $blog->item_featured = 1;
        }
        $done = Blog::withoutSyncingToSearch(function () use ($blog) {
            return $blog->save();
        });
        if ($done) {


            foreach ($request->category as $key => $category_id) {
                $data = array(
                    'category_id' => $category_id,
                    "blog_id" => $blog->id
                );
                $row = DB::table('blog_category')->insertGetId($data);



            }

            $slug_count = DB::table('blogs')->select('blogs.id')
                ->where('blogs.blog_slug', str_slug($request->title))->count();
            ;

            if ($slug_count > 1) {

                DB::table('blogs')->where('id', $blog->id)
                    ->update(array('blog_slug' => DB::raw('concat("' . str_slug($request->title) . '",' . '-' . $blog->id . ')')));
            }

            if (auth()->user()) {
                app(UserActivityService::class)->log(auth()->user()->id, 'blog_listing', 'blog', $blog->id, $blog->id);
            }
            Session::flash('success_message', get_phrase('Blog Created Successfully'));
            return redirect()->route('blogs');
        }

    }

    public function createCategoryFromSelect2(Request $request)
    {
        $duplicateCount = DB::table('groupcategories')
            ->where('category_name', $request->category_name)
            ->count();

        if ($duplicateCount === 0) {
            $category = new Blogcategory();

            $category->category_name = $request->category_name;
            $category->category_slug = clean_slug($request->category_name);
            $category->category_icon = "";
            $category->category_parent_id = 0; // Or set dynamically if needed
            $category->category_description = "";
            $category->category_createdby = auth()->user()->id;

            $category->save();

            if (auth()->user()) {
                app(UserActivityService::class)->log(auth()->user()->id, 'category_suggest', 'blog_category', $category->id, $category->id);
            }

            return response()->json([
                'id' => $category->id,
                'category_name' => $category->category_name
            ]);
        } else {
            // Return existing category if duplicate found (optional fallback)
            $existing = DB::table('groupcategories')
                ->where('category_name', $request->category_name)
                ->first();

            return response()->json([
                'id' => $existing->id,
                'category_name' => $existing->category_name,
                'duplicate' => true
            ]);
        }
    }



    public function dataAjax(Request $request)
    {
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $data = DB::table("groupcategories")
                ->select("id", "category_name")
                ->where('category_name', 'LIKE', "$search%")
                ->whereNotNull('category_parent_id')
                ->get();
        }
        return response()->json($data);
    }


    public function edit($id)
    {
        $page_data['all_cities'] = CityHelper::getActiveCities();
        $page_data['blog_category'] = Blogcategory::all();
        $page_data['blog'] = Blog::find($id);


        $page_data['all_states'] = DB::table('states')->select('states.*')
            ->where('country_id', 101)->get();
        $page_data['all_cities'] = DB::table('cities')->select('cities.*')
            ->where('state_id', $page_data['blog']->state_id)->get();
        $page_data['all_areas'] = DB::table('areas')->select('areas.*')
            ->where('city_id', $page_data['blog']->city_id)->get();

        $page_data['printable_categories'] = DB::table('groupcategories')
            ->where(function($q) {
                $q->whereNull('category_parent_id')
                  ->orWhere('category_parent_id', 0);
            })->get();

        $page_data['parent'] = DB::table('groupcategories')
            ->where(function($q) {
                $q->whereNull('category_parent_id')
                  ->orWhere('category_parent_id', 0);
            })->get();

        // $page_data['listing']= DB::table('pages')->select('pages.*')
        // ->join('page_category','page_category.page_id','pages.id')
        // ->where('pages.item_status',2)
        // ->where('pages.user_id',auth()->user()->id)
        // ->distinct('pages.id')
        // ->orderBy('pages.id','DESC')->get();

        $page_data['countries'] = DB::table('countries')->select('countries.*')
            ->where('id', 101)->get();

        $page_data['view_path'] = 'frontend.blogs.edit_blog';
        return view('frontend.index', $page_data);
    }


    public function getPages(Request $request)
    {
        $search = $request->input('search');

        $pages = DB::table('pages')
            ->select('pages.id', 'pages.title')
            ->join('page_category', 'page_category.page_id', 'pages.id')
            ->where('pages.item_status', 2)
            ->where('pages.user_id', auth()->id())
            ->when($search, function ($query, $search) {
                $query->where('pages.title', 'like', "%{$search}%");
            })
            ->distinct('pages.id')
            ->orderBy('pages.id', 'DESC')
            ->limit(10)
            ->get();

        return response()->json($pages);
    }




    public function update(Request $request, $id)
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

        // if(auth()->user()->user_role=="admin"){

        //     $blog_status=2;

        // }
        // else{

        //     $blog_status=1;
        // }

        if ($request->state) {

            $state_id = $request->state;
        } else {
            $state_id = null;

        }

        if ($request->city) {

            $city_id = $request->city;
        } else {
            $city_id = null;

        }

        if ($request->area) {

            $area_id = $request->area;
        } else {
            $area_id = null;

        }

        if ($request->country) {

            $country_id = $request->country;
        } else {
            $country_id = null;

        }


        $blog_slug = preg_replace("/[^A-Za-z0-9 ]/", '', $request->title);


        $multiSelectArray = $request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }

        $categories_id = implode(',', $multiSelectArray);

        $blog = Blog::find($id);

        $title = 'blog';
        $approval = ManageApproval::where('title', $title)->first();

        if ($approval && $approval->status == 1) {
            // Approval status is ON
            $blog_status = 2;

        } elseif (auth()->check() && auth()->user()->user_role == 'admin') {
            // Status is OFF but user is admin
            $blog_status = 2;

        } else {
            //Status is OFF and user is not admin
            $blog_status = $blog->blog_status;
        }

        $blog->user_id = Auth::user()->id;
        // store image name for delete file operation 
        $imagename = $blog->thumbnail;

        $blog->user_id = Auth::user()->id;
        $blog->title = $request->title;
        $blog->blog_slug = str_slug($blog_slug);
        $blog->category_id = $categories_id;
        $blog->state_id = $state_id;
        $blog->city_id = $city_id;
        $blog->area_id = $area_id;
        $blog->blog_status = $blog_status;

        $blog->auther_name = $request->author;
        $blog->publication_date = $request->publication_date ?? ($blog->publication_date ?? date('Y-m-d'));
        $blog->country_id = $country_id;
        $blog->list_id = $request->List;
        $blog->publication_status = $request->status ?? ($blog->publication_status ?? 'publish');


        $tags = json_decode($request->tag, true);
        $tag_array = array();

        if (is_array($tags)) {
            foreach ($tags as $key => $tag) {
                $tag_array[$key] = $tag['value'];
            }
        }
        $blog->tag = json_encode($tag_array);
        $blog->description = $request->description;
        !empty($request->image) ? $blog->thumbnail = $file_name : $blog->thumbnail;
        $done = Blog::withoutSyncingToSearch(function () use ($blog) {
            return $blog->save();
        });
        if ($done) {

            foreach ($request->category as $key => $category_id) {
                $category_count = DB::table('blog_category')->select('blog_category.id')
                    ->where('category_id', $category_id)
                    ->where('blog_id', $id)
                    ->count();
                if ($category_count == 0) {
                    $data = array(
                        'category_id' => $category_id,
                        "blog_id" => $id
                    );
                    $row = DB::table('blog_category')->insertGetId($data);
                }


            }
            $slug_count = DB::table('blogs')->select('pages.id')
                ->where('blogs.title', str_slug($request->title))->count();
            ;

            if ($slug_count > 1) {

                DB::table('blogs')->where('id', $id)
                    ->update(array('blog_slug' => DB::raw('concat("' . str_slug($request->title) . '",' . '-' . $id . ')')));
            }
            // just put the file name and folder name nothing more :) 
            if (!empty($request->image)) {
                removeFile('blog', $imagename);
            }
            Session::flash('success_message', get_phrase('Blog Updated Successfully'));
            return redirect()->route('myblog');
        }
    }





    public function delete()
    {
        $response = array();
        $blog = Blog::find($_GET['blog_id']);
        // store image name for delete file operation 
        $imagename = $blog->thumbnail;

        $done = $blog->delete();
        if ($done) {
            $response = array('alertMessage' => get_phrase('Blog Deleted Successfully'), 'fadeOutElem' => "#blog-" . $_GET['blog_id']);
            // just put the file name and folder name nothing more :) 
            removeFile('blog', $imagename);
        }
        return json_encode($response);
    }



    public function load_blog_by_scrolling(Request $request)
    {
        $blogs = Blog::orderBy('id', 'DESC')->skip($request->offset)->take(6)->get();
        $page_data['blogs'] = $blogs;
        return view('frontend.blogs.blog-single', $page_data);
    }



    public function single_blog(Request $request, $category_slug, $blog_slug, $city_slug = null, $area_slug = null)
    {
        $page_data['all_cities'] = Cache::remember('blog_single_all_cities', 3600, function () {
            return DB::table('cities')->select('cities.id', 'cities.city_name', 'cities.city_slug')
                ->join('pages', 'pages.city_id', 'cities.id')
                ->join('page_category', 'page_category.page_id', 'pages.id')
                ->join('pagecategories', 'page_category.category_id', '=', 'pagecategories.id')
                ->distinct('cities.id')
                ->orderBy('cities.id', 'asc')
                ->where('pages.item_status', 2)
                ->orderBy('cities.city_name', 'asc')
                ->get();
        });

        $category = DB::table('blogcategories')
            ->select('id', 'category_name', 'category_slug', 'category_parent_id')
            ->where('category_slug', $category_slug)
            ->first();
        $city = null;
        $area = null;
        $parentcategories = collect(); // default empty

        if ($city_slug) {
            $city = DB::table('cities')->select('id', 'city_name', 'city_slug')
                ->where('city_slug', $city_slug)
                ->first();

            if ($area_slug && $city) {
                $area = DB::table('areas')->select('id', 'area_name', 'area_slug')
                    ->where('city_id', $city->id)
                    ->where('area_slug', $area_slug)
                    ->first();
            }

            if ($city && $category && $category->category_parent_id) {
                $parentcategories = DB::table('blogcategories')
                    ->select('blogcategories.id', 'blogcategories.category_name')
                    ->join('blog_category', 'blog_category.category_id', '=', 'blogcategories.id')
                    ->join('blogs', 'blogs.id', '=', 'blog_category.blog_id')
                    ->where('blogs.blog_status', 2)
                    ->where('blogcategories.id', $category->category_parent_id)
                    ->where('blogs.city_id', $city->id)
                    ->distinct('category_name')
                    ->orderBy('category_name')
                    ->get();
            }
        }

        $page_data['category'] = $category;
        $page_data['city'] = $city;
        $page_data['area'] = $area;
        $page_data['parent_categories'] = $parentcategories;

        $blog = Blog::query()
            ->select([
                'id',
                'user_id',
                'title',
                'blog_slug',
                'thumbnail',
                'description',
                'tag',
                'view',
                'created_at',
                'city_id',
                'area_id',
            ])
            ->with('getUser:id,name')
            ->where('blog_slug', $blog_slug)
            ->first();

        if (!$blog) {
            abort(404);
        }

        if (auth()->check()) {
            app(UserActivityService::class)->log(auth()->id(), 'view', 'blog', $blog->id, $blog->id);
        }
        $page_data['comments'] = collect();
        $page_data['total_comments'] = Comments::where('is_type', 'blog')
            ->where('id_of_type', $blog->id)
            ->count();
        $page_data['socailshare'] = Share::currentPage()
            ->facebook()
            ->twitter()
            ->linkedin()
            ->telegram()
            ->getRawLinks();

        if ($blog && auth()->check()) {
            $blog_view_data = $blog->view ? json_decode($blog->view, true) : [];
            $blog_view_data = is_array($blog_view_data) ? $blog_view_data : [];
            $viewerId = auth()->id();

            if (!in_array($viewerId, $blog_view_data)) {
                $blog_view_data[] = $viewerId;

                Blog::withoutSyncingToSearch(function () use ($blog, $blog_view_data) {
                    $blog->view = json_encode($blog_view_data);
                    $blog->save();
                });
            }
        }

        $page_data['blog'] = $blog;

        $reviewsQuery = Review::where('marketplace_id', $blog->id)
            ->with('user')
            ->where('type', 'blog')
            ->latest();

        $reviewPreview = (clone $reviewsQuery)->limit(6)->get();
        $page_data['reviews'] = $reviewPreview->take(5);
        $page_data['has_more_reviews'] = $reviewPreview->count() > 5;

        $page_data['recent_posts'] = Cache::remember('blog_single_recent_posts_v1', 900, function () {
            return DB::table('blogs')
                ->select('blogs.id', 'blogs.title', 'blogs.blog_slug', 'blogs.thumbnail', 'blogs.created_at')
                ->where('blogs.blog_status', 2)
                ->orderBy('blogs.id', 'DESC')
                ->limit(10)
                ->get();
        });


        $search_param = $request->title;
        $filter_city = $request->city;
        $filter_area = $request->area;
        $filter_sort_by = $request->filter_sort_by;
        $filter_category = $request->category;
        $currentCityId = $filter_city ?: ($city->id ?? null);

        $page_data['all_blog_cities'] = Cache::remember('active_blog_cities', 3600, function () {
            return DB::table('cities')->select('cities.id', 'cities.city_name')
                ->join('blogs', 'blogs.city_id', '=', 'cities.id')
                ->where('blogs.blog_status', 2)
                ->distinct()
                ->orderBy('cities.city_name')
                ->get();
        });

        $page_data['categories'] = Cache::remember('all_blog_categories', 3600, function () {
            return DB::table('blogcategories')->select('blogcategories.id', 'blogcategories.category_name', 'blogcategories.category_slug')
                ->join('blog_category', 'blog_category.category_id', '=', 'blogcategories.id')
                ->join('blogs', 'blogs.id', '=', 'blog_category.blog_id')
                ->where('blogs.blog_status', 2)
                ->distinct()
                ->orderBy('blogcategories.category_name')
                ->get();
        });

        $page_data['all_areas'] = [];
        if (!empty($filter_city)) {
            $page_data['all_areas'] = DB::table('areas')
                ->select('id', 'area_name')
                ->where('city_id', $filter_city)
                ->orderBy('area_name')
                ->get();
        }

        $blog_query = DB::table('blogs')
            ->join('blog_category', 'blog_category.blog_id', '=', 'blogs.id')
            ->where('blogs.blog_status', 2);

        // 🔍 Search filter
        if (!empty($search_param)) {
            $blog_query->where('blogs.title', 'LIKE', "%{$search_param}%");
        }

        // 🏙️ City filter
        if (!empty($filter_city)) {
            $blog_query->where('blogs.city_id', $filter_city);
        }

        // 📍 Area filter
        if (!empty($filter_area) && $filter_area !== "0") {
            $blog_query->where('blogs.area_id', $filter_area);
        }

        // 🔀 Sorting
        // if ($filter_sort_by === "oldest") {
        //     $blog_query->orderBy('blogs.created_at', 'ASC');
        // } elseif ($filter_sort_by === "highest-rated") {
        //     // Logic for highest rated if applicable, else default to newest
        //     $blog_query->orderByDesc('blogs.id');
        // } else {
        //     $blog_query->orderByDesc('blogs.item_featured')
        //                ->orderByDesc('blogs.id');
        // }

        // 🔚 Final result with pagination
        $page_data['total_blogs'] = (clone $blog_query)->distinct()->count('blogs.id');
        $page_data['filter_city'] = $filter_city;
        $page_data['filter_area'] = $filter_area;
        $page_data['filter_sort_by'] = $filter_sort_by;
        $page_data['filter_category'] = $filter_category;
        $page_data['capsuleCategories'] = Cache::remember('blog_capsule_categories_' . ($currentCityId ?? 'global'), 3600, function () use ($currentCityId) {
            $rows = DB::table('content_master')
                ->join('blogcategories', 'blogcategories.id', '=', 'content_master.category_id')
                ->join('blogs', 'blogs.id', '=', 'content_master.source_id')
                ->where('content_master.source_type', 'blog')
                ->where('blogs.blog_status', 2)
                ->when($currentCityId, function ($query) use ($currentCityId) {
                    return $query->where('content_master.city_id', $currentCityId);
                })
                ->select('blogcategories.category_slug', 'blogcategories.category_name')
                ->groupBy('blogcategories.category_slug', 'blogcategories.category_name')
                ->orderBy('blogcategories.category_name')
                ->get();

            return $rows->map(function ($row) {
                return (object) [
                    'category_slug' => $row->category_slug,
                    'category_name' => $row->category_name,
                ];
            });
        });

        $page_data['view_path'] = 'frontend.blogs.single_blog';

        return view('frontend.blog_view_index', $page_data);
    }



    public function storegroupcategories(Request $request)
    {
        $duplicatecount = DB::table('groupcategories')->where('category_name', $request->category_name)
            ->count();

        if ($duplicatecount == 0) {



            $category = new Blogcategory();




            $category->category_name = $request->category_name;
            $category->category_slug = strtolower(str_replace(' ', '-', $request->category_name));
            $category->category_icon = "";
            $category->category_parent_id = $request->category_parent_id;
            $category->category_description = "";
            $category->category_createdby = auth()->user()->id;

            $category->save();

            if (auth()->user()) {
                app(UserActivityService::class)->log(auth()->user()->id, 'category_suggest', 'blog_category', $category->id, $category->id);
            }

            \Session::flash('flash_message', __('Created'));
            \Session::flash('flash_type', 'success');
            return response()->json(1);
        } else {
            return response()->json("duplicate");
        }
    }

    public function jsonGetBlogParentCategories()
    {
        $parents = DB::table('groupcategories')
            ->where(function($q) {
                $q->whereNull('category_parent_id')
                  ->orWhere('category_parent_id', 0);
            })->get()->toJson();

        return response()->json($parents);
    }


    public function jsonGetBlogCategories()
    {
        $parents = DB::table('groupcategories')->select('groupcategories.id', 'groupcategories.category_name', 'cat.category_name as parent')
            ->leftjoin('groupcategories as cat', 'cat.id', '=', 'groupcategories.category_parent_id')->orderby('id', 'asc')
            ->get()->toJson();

        return response()->json($parents);
    }


  
  public function jsonGetAreasByCityforblog(int $city_id)
{
    // STEP 1 – try to get areas that actually have blogs (preferred source)
    $areas = DB::table('content_master')
        ->join('blogs', 'blogs.id', '=', 'content_master.source_id')
        ->join('areas', 'areas.id', '=', 'blogs.area_id')
        ->where('content_master.city_id', $city_id)
        ->where('content_master.source_type', 'blog')
        ->where('blogs.blog_status', 2)
        ->whereNotNull('blogs.area_id')
        ->where('blogs.area_id', '!=', 0)
        ->select('areas.id', 'areas.area_name', 'areas.area_slug')
        ->distinct()
        ->orderBy('areas.area_name', 'asc')
        ->get();

    // STEP 2 – fallback: if no blog has an area_id (common on live servers),
    // return **all** areas for the selected city so the dropdown is never empty.
    if ($areas->isEmpty()) {
        $areas = DB::table('areas')
            ->where('city_id', $city_id)
            ->select('id', 'area_name', 'area_slug')
            ->orderBy('area_name', 'asc')
            ->get();
    }

    return response()->json($areas);
}



    // category wise page view
    public function category_blog(Request $request, $category_slug)
    {
        $category = Blogcategory::where('category_slug', $category_slug)->first();
        $page_data['category'] = $category;

        if ($category) {
            // Cache cities that have blogs in this category for 1 hour
            $cityCacheKey = "blog_cities_for_category_{$category->id}";
            $page_data['all_blog_cities'] = Cache::remember($cityCacheKey, 3600, function () use ($category) {
                return DB::table('cities')->select('cities.*')
                    ->join('blogs', 'blogs.city_id', 'cities.id')
                    ->join('blog_category', 'blog_category.blog_id', 'blogs.id')
                    ->distinct('cities.id')
                    ->orderBy('cities.id', 'asc')
                    ->where('blogs.blog_status', 2)
                    ->where('blog_category.category_id', $category->id)
                    ->orderBy('cities.city_name', 'asc')->get();
            });

            // Cache global blog categories for 1 hour
            $page_data['categories'] = Cache::remember('all_blog_categories', 3600, function () {
                return DB::table('blogcategories')->select('blogcategories.*')
                    ->join('blog_category', 'blog_category.category_id', '=', 'blogcategories.id')
                    ->join('blogs', 'blogs.id', '=', 'blog_category.blog_id')
                    ->where('blogs.blog_status', 2)
                    ->distinct()
                    ->get();
            });

            SEOMeta::setTitle($category->category_name . ' Blogs & Articles | Latest ' . $category->category_name . ' News | Cityhangaround');
            SEOMeta::setDescription("Explore the latest {$category->category_name} blogs, articles, tips, and guides on CityHangAround. Stay updated with trending {$category->category_name} topics and insights shared by experts.");
            SEOMeta::addKeyword([
                "{$category->category_name} blogs",
                "latest {$category->category_name} articles",
                "trending {$category->category_name} blogs",
                "{$category->category_name} tips and guides",
                "{$category->category_name} news",
                "CityHangAround {$category->category_name}"
            ]);

            SEOMeta::setCanonical(URL::current());

            $page_data['category_id'] = $category->id;

            $filter_city = empty($request->city) ? null : $request->city;
            $filter_area = empty($request->area) ? "0" : $request->area;

            $page_data['filter_city'] = $filter_city;
            $page_data['filter_area'] = $filter_area;

            // Sorting
            $filter_sort_by = $request->filter_sort_by ?? "newest";
            $page_data['filter_sort_by'] = $filter_sort_by;

            $blogs_query = DB::table('blogs')->select(
                'blogs.*',
                'cities.city_slug',
                'areas.area_slug',
                'cities.city_name',
                'areas.area_name',
                'states.state_name',
                'users.name as username',
                'users.id as userid',
                'users.photo as user_photo',
                DB::raw('MAX(blogcategories.category_slug) as catslug'),
                DB::raw('MAX(blogcategories.category_name) as cat_name'),
            )
                ->leftJoin('cities', 'cities.id', '=', 'blogs.city_id')
                ->leftJoin('areas', 'areas.id', '=', 'blogs.area_id')
                ->leftJoin('states', 'states.id', '=', 'blogs.state_id')
                ->join('blog_category', 'blog_category.blog_id', '=', 'blogs.id')
                ->join('blogcategories', 'blogcategories.id', '=', 'blog_category.category_id')
                ->join('users', 'users.id', '=', 'blogs.user_id')
                ->where('blogs.blog_status', 2)
                ->where(function ($query) use ($category) {
                    $query->where('blog_category.category_id', $category->id);
                })
                ->groupBy('blogs.id', 'cities.id', 'areas.id', 'states.id', 'users.id');

            //  City filter
            if (!empty($filter_city)) {
                $blogs_query->where('blogs.city_id', $filter_city);
            }

            //  Area filter
            if (!empty($filter_area) && $filter_area !== "0") {
                $blogs_query->where('blogs.area_id', $filter_area);
            }

            //  Sorting: orderBy('blogs.id') must be first for DISTINCT ON, then featured, then created_at
            $blogs_query->orderBy('blogs.id', 'DESC');
            $blogs_query->orderByDesc('blogs.item_featured');

            if ($filter_sort_by === "oldest") {
                $blogs_query->orderBy('blogs.created_at', 'ASC');
            } else {
                $blogs_query->orderBy('blogs.created_at', 'DESC');
            }

            //  Pagination
            $paid_items = $blogs_query->distinct('blogs.id')->paginate(50);
            $paid_items->appends([
                'filter_sort_by' => $filter_sort_by,
                'filter_city' => $filter_city,
                'filter_area' => $filter_area,
            ]);


            //  Return to View
            $page_data['blogs'] = $paid_items;
            $page_data['view_path'] = 'frontend.blogs.category_blog';

            return view('frontend.category_blog_index', $page_data);

        }
    }


    public function blogcategorycity(Request $request, $category_slug, $city_slug)
    {

        $category = Blogcategory::where('category_slug', $category_slug)->first();
        $city = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();
        if (!$category || !$city) {
            abort(404);
        }
        $page_data['city'] = $city;
        //  $page_data['area']=$area;
        $page_data['category'] = $category;

        // Only areas that have blogs for this specific category+city (cached)
        $page_data['all_areas'] = Cache::remember("blog_areas_cat_{$category->id}_city_{$city->id}_v2", 3600, function () use ($city, $category) {
            return DB::table('areas')
                ->join('blogs', 'blogs.id', '>', DB::raw('0'))
                ->leftJoin('pages', 'pages.id', '=', 'blogs.list_id')
                ->join('blog_category', 'blog_category.blog_id', '=', 'blogs.id')
                ->whereRaw('areas.id = COALESCE(blogs.area_id, pages.area_id)')
                ->where('blogs.blog_status', 2)
                ->whereRaw('COALESCE(blogs.city_id, pages.city_id) = ?', [$city->id])
                ->where('areas.city_id', $city->id)
                ->where('blog_category.category_id', $category->id)
                ->select('areas.id', 'areas.area_name', 'areas.area_slug')
                ->distinct()
                ->orderBy('areas.area_name')
                ->get();
        });

        // All cities for the city dropdown in the sidebar (cached)
        $page_data['all_cities'] = Cache::remember('blog_all_cities_v1', 86400, function () {
            return DB::table('cities')->orderBy('city_name')->get();
        });

        // Cache categories with blogs in this city for 1 hour
        $cacheKey = "blog_categories_city_{$city->id}_v2";
        $page_data['categories'] = Cache::remember($cacheKey, 3600, function () use ($city) {
            return DB::table('groupcategories')->select('groupcategories.*')
                ->join('blog_category', 'blog_category.category_id', '=', 'groupcategories.id')
                ->join('blogs', 'blogs.id', '=', 'blog_category.blog_id')
                ->leftJoin('pages', 'pages.id', '=', 'blogs.list_id')
                ->where('blogs.blog_status', 2)
                ->whereRaw('COALESCE(blogs.city_id, pages.city_id) = ?', [$city->id])
                ->distinct()
                ->orderBy('groupcategories.category_name')
                ->get();
        });

        // Use the same cached list for all_categories to avoid another query
        $page_data['all_categories'] = $page_data['categories'];

        if (!is_null($category) && !is_null($city)) {
            // Cache parent categories query
            $parentcategories = Cache::remember("blog_parent_cats_city_{$city->id}_cat_{$category->category_parent_id}_v1", 3600, function () use ($category, $city) {
                return DB::table('groupcategories')->select('groupcategories.*')
                    ->join('blog_category', 'blog_category.category_id', '=', 'groupcategories.id')
                    ->join('blogs', 'blog_category.blog_id', '=', 'blogs.id')
                    ->leftJoin('pages', 'pages.id', '=', 'blogs.list_id')
                    ->where('blogs.blog_status', 2)
                    ->where('groupcategories.id', $category->category_parent_id)
                    ->whereRaw('COALESCE(blogs.city_id, pages.city_id) = ?', [$city->id])
                    ->distinct('category_name')
                    ->orderBy('category_name')
                    ->get();
            });

            $parentcategory = Blogcategory::select('id', 'category_name', 'category_slug')->where('id', $category->category_parent_id)->first();

            $subcategories = [];
            foreach ($parentcategories as $allcategoriesresult) {
                $subcategories[] = $allcategoriesresult->category_name;
            }
            $page_data['parent_categories'] = $parentcategories;

            // if($parentcategory){
            //     SEOMeta::setTitle($city->city_name.' Near by top '.$category->category_name.' '.$parentcategory->category_name.', listing, deals, offers');
            //     SEOMeta::setDescription($city->city_name.' Near by top '.$category->category_name.' '.$parentcategory->category_name.' listing, deals, offers');
            // }
            // else{
            //     SEOMeta::setTitle($city->city_name.' Near by top '.$category->category_name.', listing, deals, offers');
            //     SEOMeta::setDescription($city->city_name.' Near by top '.$category->category_name.' listing, deals, offers');
            // }

            SEOMeta::setTitle('Latest ' . $category->category_name . ' Blogs in ' . $city->city_name . ' | CityHangAround');

            SEOMeta::setDescription('Explore the latest ' . $category->category_name . ' blogs, articles, and updates in ' . $city->city_name . ' on CityHangAround. Stay connected with trending topics, local guides, and insights in ' . $city->city_name . '\'s ' . $category->category_name . ' scene.');

            SEOMeta::setKeywords([
                $category->category_name . ' blogs in ' . $city->city_name,
                'latest ' . $category->category_name . ' articles ' . $city->city_name,
                'trending ' . $category->category_name . ' blogs ' . $city->city_name,
                $city->city_name . ' ' . $category->category_name . ' tips',
                $city->city_name . ' ' . $category->category_name . ' news',
                'CityHangAround ' . $city->city_name . ' ' . $category->category_name
            ]);



            SEOMeta::setCanonical(URL::current());




            //echo  $request->city;exit;

            // DB::table('blogs')->select('blogs.*','cities.city_slug','areas.area_slug',
            // 'cities.city_name','areas.area_name','states.state_name','users.name as username','users.id as userid' )
            // ->leftjoin('cities','cities.id','blogs.city_id')
            // ->leftjoin('areas','areas.id','blogs.area_id')
            // ->leftjoin('states','states.id','blogs.state_id')
            // ->join('blog_category','blog_category.blog_id','blogs.id')
            // ->join('groupcategories','blog_category.category_id','=','groupcategories.id')
            // ->join('users','users.id','blogs.user_id')
            // ->distinct('blogs.id') ->where(function ($query) use ($category) {
            //     $query->where('blog_category.category_id', $category->id)
            //     ->orWhere('groupcategories.category_parent_id',$category->id);
            // })
            // ->where('blogs.blog_status',2)
            // ->distinct('blogs.id');

            //  $paid_items_query=  DB::table('blogs')->select('blogs.*','cities.city_slug','areas.area_slug',
            //  'cities.city_name','areas.area_name','states.state_name','users.name as username','users.id as userid' )
            //  ->leftjoin('cities','cities.id','blogs.city_id')
            //  ->leftjoin('areas','areas.id','blogs.area_id')
            //  ->leftjoin('states','states.id','blogs.state_id')
            //  ->join('blog_category','blog_category.blog_id','blogs.id')
            //  ->join('groupcategories','blog_category.category_id','=','groupcategories.id')
            //  ->join('users','users.id','blogs.user_id')
            //  ->distinct('blogs.id') ->where(function ($query) use ($category) {
            //      $query->where('blog_category.category_id', $category->id)
            //      ->orWhere('groupcategories.category_parent_id',$category->id);
            //  })
            //  ->where('blogs.blog_status',2)
            //  ->distinct('blogs.id');


            $paid_items_query = DB::table('blogs')
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
                    'blogs.description',
                    'blogs.tag',

                    'cities.city_slug',
                    'areas.area_slug',
                    'cities.city_name',
                    'areas.area_name',
                    'states.state_name',

                    'users.name as username',
                    'users.id as userid',
                    'users.photo as user_photo',

                    DB::raw('MAX(groupcategories.category_slug) as cat_slug'),
                    DB::raw('MAX(groupcategories.category_name) as cat_name'),
                ])
                ->leftJoin('pages', 'pages.id', '=', 'blogs.list_id')
                ->leftJoin('cities', 'cities.id', '=', DB::raw('COALESCE(blogs.city_id, pages.city_id)'))
                ->leftJoin('areas', 'areas.id', '=', DB::raw('COALESCE(blogs.area_id, pages.area_id)'))
                ->leftJoin('states', 'states.id', '=', DB::raw('COALESCE(blogs.state_id, pages.state_id)'))
                ->join('users', 'users.id', '=', 'blogs.user_id')
                ->join('blog_category', 'blog_category.blog_id', '=', 'blogs.id')
                ->join('groupcategories', 'groupcategories.id', '=', 'blog_category.category_id')
                ->where('blogs.blog_status', 2)
                ->whereRaw('COALESCE(blogs.city_id, pages.city_id) = ?', [$city->id])
                ->where(function ($query) use ($category) {
                    $query->where('blog_category.category_id', $category->id)
                        ->orWhere('groupcategories.category_parent_id', $category->id);
                })
                ->groupBy([
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
                    'blogs.description',
                    'blogs.tag',

                    'cities.city_slug',
                    'areas.area_slug',
                    'cities.city_name',
                    'areas.area_name',
                    'states.state_name',

                    'users.name',
                    'users.id',
                    'users.photo',
                ]);

            // ➕ Sort by item_featured first
            $paid_items_query->orderByDesc('blogs.item_featured');

            // ➕ Apply filter_sort_by from UI
            $filter_sort_by = $request->filter_sort_by ?? 'newest';
            $page_data['filter_sort_by'] = $filter_sort_by;

            if ($filter_sort_by === 'oldest') {
                $paid_items_query->orderBy('blogs.created_at', 'ASC');
            } else {
                $paid_items_query->orderBy('blogs.created_at', 'DESC');
            }

            $filter_city = $request->input('city', $city->id);
            $filter_area = $request->input('area', '0');

            if (!empty($filter_city) && $filter_city !== '0') {
                $paid_items_query->whereRaw('COALESCE(blogs.city_id, pages.city_id) = ?', [$filter_city]);
            }
            if (!empty($filter_area) && $filter_area !== '0') {
                $paid_items_query->whereRaw('COALESCE(blogs.area_id, pages.area_id) = ?', [$filter_area]);
            }

            $page_data['filter_city'] = $filter_city;
            $page_data['filter_area'] = $filter_area;
            $page_data['all_categories'] = $page_data['categories'];

            // ➕ Final paginate
            $paid_items = $paid_items_query->paginate(50);
            $paid_items->appends([
                'filter_sort_by' => $filter_sort_by,
                'city' => $filter_city,
                // 'area' => $filter_area,
            ]);


            // ➕ Preserve query params
            $querystringArray = ['filter_sort_by' => $filter_sort_by];
            $paid_items->appends($querystringArray);

            // ➕ Assign to blade/view
            $page_data['blogs'] = $paid_items;

            // Capsule categories: ONLY the current page's category (e.g. "Online Listing" only)
            // This prevents fallback to $categories which shows all city categories.
            $page_data['capsule_categories'] = collect([$category]);

            $page_data['view_path'] = 'frontend.blogs.category_city_blog';

            return view('frontend.blog_category_city_index', $page_data);


        } else {
            abort(404);
        }

    }


    public function area(Request $request, string $city_slug, string $area_slug)
    {

        $page_data['city'] = DB::table('cities')->where('city_slug', $city_slug)->first();
        $city = $page_data['city'];

        if ($city) {
            $page_data['area'] = DB::table('areas')->where('area_slug', $area_slug)
                ->where('city_id', $city->id)
                ->first();
            $area = $page_data['area'];

            // Only areas with blogs for this city — filtered by areas.city_id to avoid cross-city data (cached)
            $page_data['all_areas'] = Cache::remember("blog_areas_city_{$city->id}_v2", 3600, function () use ($city) {
                return DB::table('areas')
                    ->join('blogs', 'blogs.area_id', '=', 'areas.id')
                    ->where('blogs.blog_status', 2)
                    ->where('blogs.city_id', $city->id)
                    ->where('areas.city_id', $city->id)
                    ->select('areas.id', 'areas.area_name', 'areas.area_slug')
                    ->distinct()
                    ->orderBy('areas.area_name')
                    ->get();
            });

            // All cities for the city dropdown (cached 24h)
            $page_data['all_cities'] = Cache::remember('blog_all_cities_v1', 86400, function () {
                return DB::table('cities')->orderBy('city_name')->get();
            });

            if ($area) {
                // Cache categories that have blogs in this specific area for 1 hour
                $cacheKey = "blog_categories_area_{$area->id}_v2";
                $page_data['categories'] = Cache::remember($cacheKey, 3600, function () use ($city, $area) {
                    return DB::table('groupcategories')->select('groupcategories.*')
                        ->join('blog_category', 'blog_category.category_id', '=', 'groupcategories.id')
                        ->join('blogs', 'blog_category.blog_id', '=', 'blogs.id')
                        ->distinct('groupcategories.id')
                        ->orderBy('groupcategories.id', 'asc')
                        ->where('blogs.blog_status', 2)
                        ->where('blogs.city_id', $city->id)
                        ->where('blogs.area_id', $area->id)
                        ->orderBy('groupcategories.category_name')
                        ->get();
                });

                SEOMeta::setTitle($area->area_name . ',' . $city->city_name . ' nearby top business listing, deals, offers');
                SEOMeta::setDescription($area->area_name . ',' . $city->city_name . ' nearby top business listings, deals, offers, local business');

                SEOMeta::setCanonical(URL::current());




                //print_r($area);exit;




                //  $paid_items_query=   DB::table('blogs')->select('blogs.*','cities.city_slug','areas.area_slug',
                //  'cities.city_name','areas.area_name','states.state_name','users.name as username','users.id as userid' )
                //  ->join('cities','cities.id','blogs.city_id')
                //  ->join('areas','areas.id','blogs.area_id')
                //  ->join('states','states.id','blogs.state_id')
                //  ->join('blog_category','blog_category.blog_id','blogs.id')
                //  ->join('groupcategories','blog_category.category_id','=','groupcategories.id')
                //  ->join('users','users.id','blogs.user_id')
                //  ->where("blogs.city_id", $city->id)
                //  ->where("blogs.area_id", $area->id)
                //  ->where('blogs.blog_status',2)
                //  ->distinct('blogs.id');


                $filter_sort_by = $request->filter_sort_by ?? "newest";
                $filter_city = $city->id ?? null;
                $filter_area = $area->id ?? null;

                $page_data['filter_sort_by'] = $filter_sort_by;
                $page_data['filter_city']    = $filter_city;
                $page_data['filter_area']    = $filter_area;

                // Step 1: Build base query
                $paid_items_query = DB::table('content_master')
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
                        'blogs.description',
                        'blogs.tag',

                        'cities.city_slug',
                        'areas.area_slug',
                        'cities.city_name',
                        'areas.area_name',
                        'states.state_name',

                        'users.name as username',
                        'users.id as userid',
                        'users.photo as user_photo',

                        DB::raw('MAX(groupcategories.category_slug) as cat_slug'),
                        DB::raw('MAX(groupcategories.category_name) as cat_name'),
                    ])
                    ->join('blogs', 'blogs.id', '=', 'content_master.source_id')
                    ->join('cities', 'cities.id', '=', 'content_master.city_id')
                    ->join('areas', 'areas.id', '=', 'content_master.area_id')
                    ->leftJoin('states', 'states.id', '=', 'blogs.state_id')
                    ->join('users', 'users.id', '=', 'blogs.user_id')
                    ->leftJoin('groupcategories', 'groupcategories.id', '=', 'content_master.category_id')
                    ->where('content_master.source_type', 'blog')
                    ->where('blogs.blog_status', 2)
                    ->where('content_master.city_id', $filter_city)
                    ->where('content_master.area_id', $filter_area)
                    ->groupBy([
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
                        'blogs.description',
                        'blogs.tag',

                        'cities.city_slug',
                        'areas.area_slug',
                        'cities.city_name',
                        'areas.area_name',
                        'states.state_name',

                        'users.name',
                        'users.id',
                        'users.photo',
                    ])
                    ->orderByDesc('blogs.item_featured')
                    ->orderByDesc('blogs.id');

                // Step 2: Sort by filter
                if ($filter_sort_by == "oldest") {
                    $paid_items_query->orderBy('blogs.created_at', 'asc');
                } else {
                    $paid_items_query->orderBy('blogs.created_at', 'desc');
                }

                // Step 3: Paginate
                $paid_items = $paid_items_query->paginate(50);
                $paid_items->appends([
                    'filter_sort_by' => $filter_sort_by,
                    'filter_city' => $filter_city,
                    'filter_area' => $filter_area
                ]);

                // Step 4: Pass to view
                $page_data['blogs'] = $paid_items;

                // Capsule categories: all categories present in this area
                $page_data['capsule_categories'] = $page_data['categories'];

                $page_data['view_path'] = 'frontend.blogs.city_area_blog';

                return view('frontend.blog_city_area_index', $page_data);




            }
        }

    }


    public function blogcategorycityarea(Request $request, $city_slug, $category_slug, $area_slug)
    {

        $category = Blogcategory::where('category_slug', $category_slug)->first();
        $city = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();
        if (!$category || !$city) {
            abort(404);
        }
        $page_data['area'] = DB::table('areas')->select('areas.*')->where('area_slug', $area_slug)
            ->where('city_id', $city->id)
            ->first();
        $area = $page_data['area'];
        if (!$area) {
            abort(404);
        }
        $page_data['city'] = $city;
        $page_data['category'] = $category;

        // Fetch all cities for the city dropdown (cached 24h)
        $page_data['all_cities'] = Cache::remember('blog_all_cities_v1', 86400, function () {
            return DB::table('cities')->orderBy('city_name')->get();
        });

        // Fetch all areas for the current city for the filter dropdown
        $page_data['all_areas'] = DB::table('areas')->where('city_id', $city->id)->get();

        // Cache the categories available in this specific city and area for 1 hour
        $cacheKey = "blog_categories_city_{$city->id}_area_{$area->id}";
        $page_data['categories'] = Cache::remember($cacheKey, 3600, function () use ($city, $area, $category) {
            return DB::table('groupcategories')->select('groupcategories.*')
                ->join('blog_category', 'blog_category.category_id', '=', 'groupcategories.id')
                ->join('blogs', 'blog_category.blog_id', 'blogs.id')
                ->leftJoin('pages', 'pages.id', '=', 'blogs.list_id')
                ->distinct('groupcategories.id')
                ->where('blogs.blog_status', 2)
                ->whereRaw('COALESCE(blogs.city_id, pages.city_id) = ?', [$city->id])
                ->whereRaw('COALESCE(blogs.area_id, pages.area_id) = ?', [$area->id])
                ->orderBy('groupcategories.id', 'asc')
                ->get(); // We remove the parent category filter here to show all relevant ones in the filter
        });




        if (!is_null($category) && !is_null($city) && !is_null($area)) {

            $parentcategories = DB::table('groupcategories')->select('groupcategories.*')
                ->join('blog_category', 'blog_category.category_id', '=', 'groupcategories.id')
                ->join('blogs', 'blog_category.blog_id', 'blogs.id')
                ->leftJoin('pages', 'pages.id', '=', 'blogs.list_id')
                ->where('blogs.blog_status', 2)
                ->where('groupcategories.id', $category->category_parent_id)
                ->whereRaw('COALESCE(blogs.city_id, pages.city_id) = ?', [$city->id])
                ->whereRaw('COALESCE(blogs.area_id, pages.area_id) = ?', [$area->id])
                ->distinct('category_name')
                ->orderBy('category_name')->get();




            $parentcategory = Blogcategory::select('id', 'category_name', 'category_slug')->where('id', $category->category_parent_id)->first();


            $subcategories = [];

            foreach ($parentcategories as $allcategoriesresult) {
                $subcategories[] = $allcategoriesresult->category_name;
            }
            $page_data['parent_categories'] = $parentcategories;

            SEOMeta::setTitle($area->area_name . ' Near by top ' . $category->category_name . ', listing, deals, offers');
            //SEOMeta::setDescription($area->area_name . ', ' . 'Top 10 ' . $parentcategory->category_name . ', ' .  implode(',',$subcategories) . ' Free Listing Cityhangaround');
            SEOMeta::setDescription($area->area_name . ' Near by top ' . $category->category_name . ', deals, offers');

            SEOMeta::setCanonical(URL::current());




            //echo  $request->city;exit;

            // $paid_items_query= DB::table('blogs')->select('blogs.*','cities.city_slug','areas.area_slug',
            //      'cities.city_name','areas.area_name','states.state_name','users.name as username','users.id as userid' )
            //      ->join('cities','cities.id','blogs.city_id')
            //      ->join('areas','areas.id','blogs.area_id')
            //      ->join('states','states.id','blogs.state_id')
            //      ->join('blog_category','blog_category.blog_id','blogs.id')
            //      ->join('groupcategories','blog_category.category_id','=','groupcategories.id')
            //      ->join('users','users.id','blogs.user_id')
            //      ->where("blogs.city_id", $city->id)
            //      ->where("blogs.area_id", $area->id)
            //      ->where(function ($query) use ($category) {
            //         $query->where('blog_category.category_id', $category->id)
            //         ->orWhere('groupcategories.category_parent_id',$category->id);
            //     })
            //      ->where('blogs.blog_status',2)
            //      ->distinct('blogs.id');
            $filter_sort_by = empty($request->filter_sort_by) ? "newest" : $request->filter_sort_by;
            $page_data['filter_sort_by'] = $filter_sort_by;

            $filter_city = $request->input('city', $city->id);
            $filter_area = $request->input('area', $area->id);

            $page_data['filter_city'] = $filter_city;
            $page_data['filter_area'] = $filter_area;
            $page_data['all_categories'] = $page_data['categories'];
            $page_data['capsule_categories'] = collect([$category]);


            $paid_items_query = DB::table('content_master')
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
                    'blogs.description',
                    'blogs.tag',

                    'cities.city_slug',
                    'areas.area_slug',
                    'cities.city_name',
                    'areas.area_name',
                    'states.state_name',

                    'users.name as username',
                    'users.id as userid',
                    'users.photo as user_photo',

                    DB::raw('MAX(groupcategories.category_slug) as cat_slug'),
                    DB::raw('MAX(groupcategories.category_name) as cat_name'),
                ])
                ->join('blogs', 'blogs.id', '=', 'content_master.source_id')
                ->leftJoin('pages', 'pages.id', '=', 'blogs.list_id')
                ->leftJoin('cities', 'cities.id', '=', DB::raw('COALESCE(content_master.city_id, pages.city_id)'))
                ->leftJoin('areas', 'areas.id', '=', DB::raw('COALESCE(content_master.area_id, pages.area_id)'))
                ->leftJoin('states', 'states.id', '=', DB::raw('COALESCE(blogs.state_id, pages.state_id)'))
                ->join('users', 'users.id', '=', 'blogs.user_id')
                ->leftJoin('groupcategories', 'groupcategories.id', '=', 'content_master.category_id')
                ->where('content_master.source_type', 'blog')
                ->where('blogs.blog_status', 2)
                ->whereRaw('COALESCE(content_master.city_id, pages.city_id) = ?', [$city->id])
                ->whereRaw('COALESCE(content_master.area_id, pages.area_id) = ?', [$area->id])
                ->where(function ($query) use ($category) {
                    $query->where('content_master.category_id', $category->id)
                        ->orWhere('groupcategories.category_parent_id', $category->id);
                })
                ->groupBy([
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
                    'blogs.description',
                    'blogs.tag',

                    'cities.city_slug',
                    'areas.area_slug',
                    'cities.city_name',
                    'areas.area_name',
                    'states.state_name',

                    'users.name',
                    'users.id',
                    'users.photo',
                ])
                ->orderByDesc('blogs.item_featured')
                ->orderByDesc('blogs.id');

            // ✅ Optional sorting from UI
            if ($filter_sort_by === "oldest") {
                $paid_items_query->orderBy('blogs.created_at', 'ASC');
            } else {
                $paid_items_query->orderBy('blogs.created_at', 'DESC');
            }

            // ✅ Paginate
            $paid_items = $paid_items_query->paginate(50);
            $paid_items->appends([
                'filter_sort_by' => $filter_sort_by,
            ]);
            $paid_items->appends([
                'filter_sort_by' => $filter_sort_by,
                'city' => $filter_city,
                'area' => $filter_area,
            ]);

            // ✅ Final result to view
            $page_data['blogs'] = $paid_items;

            $page_data['view_path'] = 'frontend.blogs.category_city_area_blog';

            return view('frontend.blog_category_city_area_index', $page_data);


        } else {
            abort(404);
        }
    }


    public function city(Request $request, $city_slug)
    {
        $city = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();
        $page_data['city'] = $city;

        if (!is_null($city)) {
            // SEO
            SEOMeta::setTitle('Latest Blogs & Articles in ' . $city->city_name . ' | Cityhangaround');
            SEOMeta::setDescription("Explore the latest blogs, articles, city news, events, and lifestyle updates in " . $city->city_name . " Stay connected with what's happening around you on CityHangAround.");
            SEOMeta::setKeywords([
                "blogs in {$city->city_name}",
                "latest blogs {$city->city_name}",
                "{$city->city_name} news",
                "{$city->city_name} events",
                "lifestyle blogs {$city->city_name}",
                "trending articles {$city->city_name}",
                "local blogs {$city->city_name}",
                "CityHangAround blogs"
            ]);
            SEOMeta::setCanonical(URL::current());

            // Optimized: Cache parent categories with blogs in this city using master table
            $page_data['categories'] = Cache::remember("blog_cats_city_{$city->id}_v3", 3600, function () use ($city) {
                return DB::table('groupcategories')
                    ->join('content_master', 'content_master.category_id', '=', 'groupcategories.id')
                    ->leftJoin('blogs', 'blogs.id', '=', 'content_master.source_id')
                    ->leftJoin('pages', 'pages.id', '=', 'blogs.list_id')
                    ->whereRaw('COALESCE(content_master.city_id, pages.city_id) = ?', [$city->id])
                    ->where('content_master.source_type', 'blog')
                    ->where('blogs.blog_status', 2)
                    ->whereNull('groupcategories.category_parent_id')
                    ->select('groupcategories.id', 'groupcategories.category_name', 'groupcategories.category_slug')
                    ->distinct()
                    ->orderBy('groupcategories.category_name', 'asc')
                    ->get();
            });

            // Optimized: Pre-fetch active blog areas for the current city
            $page_data['filter_areas'] = Cache::remember("active_blog_areas_for_city_{$city->id}_v3", 3600, function () use ($city) {
                return DB::table('areas')
                    ->join('content_master', 'content_master.area_id', '=', 'areas.id')
                    ->leftJoin('blogs', 'blogs.id', '=', 'content_master.source_id')
                    ->leftJoin('pages', 'pages.id', '=', 'blogs.list_id')
                    ->whereRaw('COALESCE(content_master.city_id, pages.city_id) = ?', [$city->id])
                    ->where('content_master.source_type', 'blog')
                    ->where('blogs.blog_status', 2)
                    ->select('areas.id', 'areas.area_name', 'areas.area_slug')
                    ->distinct()
                    ->orderBy('areas.area_name', 'asc')
                    ->get();
            });

            $filter_city = $request->input('city', $city->id);
            $filter_area = $request->input('area', '0');
            $filter_sort_by = $request->input('filter_sort_by', 'newest');

            $page_data['filter_city'] = $filter_city;
            $page_data['filter_area'] = $filter_area;
            $page_data['filter_sort_by'] = $filter_sort_by;

            // Optimized: All cities with active blogs for sidebar dropdown
            $page_data['all_cities'] = Cache::remember('active_blog_cities_cached_v3', 3600, function () {
                return DB::table('cities')
                    ->join('content_master', 'content_master.city_id', '=', 'cities.id')
                    ->join('blogs', 'blogs.id', '=', 'content_master.source_id')
                    ->where('content_master.source_type', 'blog')
                    ->where('blogs.blog_status', 2)
                    ->select('cities.id', 'cities.city_name', 'cities.city_slug')
                    ->distinct()
                    ->orderBy('cities.city_name', 'asc')
                    ->get();
            });
            $page_data['all_blog_cities'] = $page_data['all_cities'];

            // Pre-compute total blogs count
            $page_data['total_blogs'] = DB::table('blogs')
                ->leftJoin('pages', 'pages.id', '=', 'blogs.list_id')
                ->whereRaw('COALESCE(blogs.city_id, pages.city_id) = ?', [$city->id])
                ->where('blogs.blog_status', 2)
                ->count();

            $page_data['view_path'] = 'frontend.blogs.city_blog';
            return view('frontend.blog_city_index', $page_data);
        } else {

            abort(404);
        }

    }

    public static function getblogsbycategoryid($categoryid, $cityid)
    {


        //echo $categoryid;


        //     $paid_items_query=   DB::table('blogs')->select('blogs.*','cities.city_slug','areas.area_slug',
        //     'cities.city_name','areas.area_name','states.state_name','users.name as username','users.id as userid' )
        //     ->join('cities','cities.id','blogs.city_id')
        //     ->join('areas','areas.id','blogs.area_id')
        //     ->join('states','states.id','blogs.state_id')
        //     ->join('blog_category','blog_category.blog_id','blogs.id')
        //     ->join('groupcategories','blog_category.category_id','=','groupcategories.id')
        //     ->join('users','users.id','blogs.user_id')
        //     ->where(function ($query) use ($categoryid) {
        //        $query->where('blog_category.category_id', $categoryid)
        //        ->orWhere('groupcategories.category_parent_id',$categoryid);
        //    })
        //    ->where('blogs.city_id', $cityid)
        //     ->where('blogs.blog_status',2)
        //     ->distinct('blogs.id')->limit(4)->get();

        $paid_items_query = DB::table('blogs')->select(
            'blogs.*',
            'cities.city_slug',
            'areas.area_slug',
            'cities.city_name',
            'areas.area_name',
            'states.state_name',
            'users.name as username',
            'users.id as userid'
        )
            ->join('cities', 'cities.id', '=', 'blogs.city_id')
            ->leftJoin('areas', 'areas.id', '=', 'blogs.area_id')
            ->leftJoin('states', 'states.id', '=', 'blogs.state_id')
            ->join('blog_category', 'blog_category.blog_id', '=', 'blogs.id')
            ->join('groupcategories', 'blog_category.category_id', '=', 'groupcategories.id')
            ->join('users', 'users.id', '=', 'blogs.user_id')

            // ✅ Category check
            ->where(function ($query) use ($categoryid) {
                $query->where('blog_category.category_id', $categoryid)
                    ->orWhere('groupcategories.category_parent_id', $categoryid);
            })

            // ✅ Basic filters
            ->where('blogs.city_id', $cityid)
            ->where('blogs.blog_status', 2)

            // ✅ DISTINCT ON: blogs.id must be FIRST in ORDER BY, then featured, then id for tiebreak
            ->orderBy('blogs.id', 'DESC')
            ->orderByDesc('blogs.item_featured')
            ->orderByDesc('blogs.id')
            ->distinct('blogs.id')
            ->limit(4)
            ->get();




        return $paid_items_query;
    }




    // blog search 

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
                    '<span class="date-meta"><a href="#">' . $post->created_at->format("d-M-Y") . '</a></span>' .
                    '</div>' .
                    '</div>' .
                    '</div>';
            }
            return Response($output);
        }


    }

}
