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
    public function search(Request $request){

        //echo $request->category_header;exit;
        // $search_param = $_GET['search'];
        // $page_data['photos']= Posts::where('description','Like','%'.$search_param.'%')->limit(50)->get();
        // $page_data['posts']= Posts::where('description','Like','%'.$search_param.'%')->limit(50)->get();
        // $page_data['peoples']= User::where('name','Like','%'.$search_param.'%')->limit(50)->get();
        // $page_data['products'] = Marketplace::where('title','like',"%".$search_param."%")->limit(50)->get();
        // $page_data['pages'] = DB::table('pages')->select('pages.id','pages.item_slug','pages.logo','pages.title','pages.coverphoto','pages.user_id',
        // 'pages.description', 'pages.job','pages.location','pages.lifestyle',
        // 'cities.city_slug','areas.area_slug','pagecategories.category_slug','pagecategories.category_name')
        // ->join('cities','cities.id','pages.city_id')
        // ->join('areas','areas.id','pages.area_id')
        // ->join('page_category','page_category.page_id','pages.id')
        // ->leftjoin('page_likes','page_likes.page_id','pages.id')
        // ->distinct('pages.id')
        // ->where('pages.item_status',2)
        // ->where('pages.title','like',"%".$search_param."%")->limit(50)->get();
        // $page_data['groups'] = Group::where('title','like',"%".$search_param."%")->where('privacy','public')->limit(50)->get();
        // $page_data['events'] = Event::where('title','like',"%".$search_param."%")->where('privacy','public')->limit(50)->get();
        // $page_data['videos'] = Video::where('title','like',"%".$search_param."%")->where('privacy','public')->limit(50)->get();
        
        // $rules = array(
        //     'category_header' => 'required|not_in:0',
        //     'city_header' => 'required|not_in:0',
        // );
        // $validator = Validator::make($request->all(), $rules);
        // if ($validator->fails()) {
        //     return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
        // }
        $category = Pagecategory::where('id', $request->category_header)->first(); 
        $city = DB::table('cities')->select('cities.*')->where('id', $request->city_header)->first();
        $page_data['city']=$city;
        $page_data['category']=$category;

        $page_data['category_header']=$request->category_header;
        $page_data['city_header']=$request->city_header;

        $parentcategories = DB::table('pagecategories')->select('pagecategories.*')
            ->join('page_category','page_category.category_id','=','pagecategories.id')
            ->join('pages','pages.id','=','page_category.page_id')
            ->where('pages.item_status',2)
            ->where('pagecategories.id', $category->category_parent_id)
            ->where('pages.city_id', $request->city_header)
            ->distinct('category_name')
            ->orderBy('category_name')->get();

        $parentcategory = Pagecategory::where('id', $category->category_parent_id)->first();
        //echo  $parentcategories;exit;


        $subcategories = [];

        foreach ($parentcategories as $allcategoriesresult) {
            $subcategories[] = $allcategoriesresult->category_name;
        }
        $page_data['parent_categories']=$parentcategories;


        $page_data['all_cities']= DB::table('cities')->select('cities.*')
        ->join('pages','pages.city_id','cities.id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','page_category.category_id','=','pagecategories.id')
        ->distinct('cities.id')
        ->where('pages.item_status',2)
        ->orderBy('cities.city_name','asc')->get();

        $page_data['all_categories']= DB::table('pagecategories')->select('pagecategories.*')
        ->join('page_category','page_category.category_id','=','pagecategories.id')
        ->join('pages','page_category.page_id','pages.id')
        ->distinct('pagecategories.id')
        ->where('pages.item_status',2)
        ->orderBy('pagecategories.id','DESC')
        ->where('pages.city_id',$request->city_header)
             ->where(function ($query) use ($category) {
                $query->where('page_category.category_id', $category->id)
                    ->orWhere('pagecategories.category_parent_id',$category->id);
            })
        ->get();

        $paid_items_query= DB::table('pages')->select('pages.id','pages.item_slug','pages.logo','pages.title','cities.city_slug','areas.area_slug'
        ,'cities.city_name','areas.area_name','states.state_name','pages.created_at')
        ->join('cities','cities.id','pages.city_id')
        ->join('areas','areas.id','pages.area_id')
        ->join('states','states.id','pages.state_id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','page_category.category_id','=','pagecategories.id')
        ->where('pages.item_status',2)
        ->where('pages.city_id',$request->city_header)
        ->where(function ($query) use ($request) {
            $query->where('page_category.category_id', $request->category_header)
            ->orWhere('pagecategories.category_parent_id',$request->category_header);
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
        //print_r($page_data['mypages']);exit;
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
