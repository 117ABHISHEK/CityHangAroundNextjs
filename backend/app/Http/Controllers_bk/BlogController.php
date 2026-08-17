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
use Image, Session,Share;
use DB;

use Illuminate\Support\Facades\URL;
use Artesaos\SEOTools\Facades\SEOMeta;
use App\Helpers\CityHelper;
use App\Services\UserActivityService;
use Illuminate\Support\Str;
class BlogController extends Controller
{
    // public function blogs(){
    //     $page_data['all_cities']= DB::table('cities')->select('cities.*','pages.id')
    //     ->join('pages','pages.city_id','cities.id')
    //     ->join('page_category','page_category.page_id','pages.id')
    //     ->join('pagecategories','page_category.category_id','=','pagecategories.id')
    //     ->distinct('pages.id')
    //     ->where('pages.item_status',2)
    //     ->orderBy('pages.id','DESC')->get();
    //     //$page_data['categories'] = Category::all();

    //     $page_data['categories']= DB::table('blogcategories')->select('blogcategories.*')
    //     ->join('blog_category','blog_category.category_id','=','blogcategories.id')
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

    public function blogs(Request $request){

        SEOMeta::setTitle('Latest Blogs & Articles | Cityhangaround');
        SEOMeta::setDescription("Explore the latest blogs, articles, and guides on Cityhangaround. Stay updated with trending topics, tips, and insights from various categories. Discover what's happening around your city today!");
        SEOMeta::setCanonical(URL::current());
        //  Adding Keywords
        SEOMeta::setKeywords([
        'City blogs', 'latest blogs', 'trending articles', 'local news', 
        'city events', 'lifestyle blogs', 'CityHangAround blogs', 
        'community blogs', 'tips and guides', 'latest updates'
        ]);


        $search_param = $request->title;
        $page_data['all_cities'] = CityHelper::getActiveCities();
        //$page_data['categories'] = Category::all();

        $page_data['categories']= DB::table('blogcategories')->select('blogcategories.*')
        ->join('blog_category','blog_category.category_id','=','blogcategories.id')
        ->join('blogs','blogs.id','=','blog_category.blog_id')
        ->where('blogs.blog_status',2)
        ->distinct()
        ->get();

        // if(!empty($search_param) && $search_param!=""){

        //     $page_data['blogs'] = DB::table('blogs')->select('blogs.*','cities.city_slug','areas.area_slug',
        // 'cities.city_name','areas.area_name','states.state_name','users.name as username','users.id as userid' )
        // ->leftjoin('cities','cities.id','blogs.city_id')
        // ->leftjoin('areas','areas.id','blogs.area_id')
        // ->leftjoin('states','states.id','blogs.state_id')
        // ->join('blog_category','blog_category.blog_id','blogs.id')
        // ->join('users','users.id','blogs.user_id')
        // ->distinct('blogs.id')
        // ->where('title', 'LIKE', "%{$search_param}%")
        // ->where('blogs.blog_status',2)->orderBy('id','DESC')->limit('50')->get();
        // }
        // else
        // {
        //     $page_data['blogs'] = DB::table('blogs')->select('blogs.*','cities.city_slug','areas.area_slug',
        //     'cities.city_name','areas.area_name','states.state_name','users.name as username','users.id as userid' )
        //     ->leftjoin('cities','cities.id','blogs.city_id')
        //     ->leftjoin('areas','areas.id','blogs.area_id')
        //     ->leftjoin('states','states.id','blogs.state_id')
        //     ->join('blog_category','blog_category.blog_id','blogs.id')
        //     ->join('users','users.id','blogs.user_id')
        //     ->distinct('blogs.id')
        //     ->where('blogs.blog_status',2)->orderBy('id','DESC')->limit('50')->get();
        // }


     $blog_query = DB::table('blogs')->select(
    'blogs.*',
    'cities.city_slug', 'areas.area_slug',
    'cities.city_name', 'areas.area_name', 'states.state_name',
    'users.name as username', 'users.id as userid'
)
->leftJoin('cities', 'cities.id', '=', 'blogs.city_id')
->leftJoin('areas', 'areas.id', '=', 'blogs.area_id')
->leftJoin('states', 'states.id', '=', 'blogs.state_id')
->join('blog_category', 'blog_category.blog_id', '=', 'blogs.id')
->join('users', 'users.id', '=', 'blogs.user_id')
->where('blogs.blog_status', 2)
->distinct('blogs.id');

// 🔍 Optional: Search filter
if (!empty($search_param)) {
    $blog_query->where('blogs.title', 'LIKE', "%{$search_param}%");
}

// 🔁 Featured sorting first, then by created_at
$blog_query
    ->orderByDesc('blogs.item_featured')  //  Featured on top
    ->orderByDesc('blogs.id');            //  Newest blogs after that

// 🔚 Final result
$page_data['blogs'] = $blog_query->limit(50)->get();


$page_data['view_path'] = 'frontend.blogs.blogs';
return view('frontend.index', $page_data);

    }

    public function myblog(){
        $page_data['all_cities']= DB::table('cities')->select('cities.*','pages.id')
        ->join('pages','pages.city_id','cities.id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','page_category.category_id','=','pagecategories.id')
        ->distinct('pages.id')
        ->where('pages.item_status',2)
        ->orderBy('pages.id','DESC')->get();
        // $blogs = Blog::where('user_id',auth()->user()->id)->orderBy('id','DESC')->get();
        // $page_data['blogs'] = $blogs;
        
        $page_data['blogs'] = DB::table('blogs')->select('blogs.*','cities.city_slug','areas.area_slug',
        'cities.city_name','areas.area_name','states.state_name','users.name as username','users.id as userid' )
        ->leftjoin('cities','cities.id','blogs.city_id')
        ->leftjoin('areas','areas.id','blogs.area_id')
        ->leftjoin('states','states.id','blogs.state_id')
        ->join('blog_category','blog_category.blog_id','blogs.id')
        ->join('users','users.id','blogs.user_id')
        ->distinct('blogs.id')->where('user_id',auth()->user()->id)
        ->where('blogs.blog_status',2)->orderBy('id','DESC')->get();
        $page_data['view_path'] = 'frontend.blogs.user_blog';
        return view('frontend.index', $page_data);
    }

    public function create(){
        // Use blog-specific city function to prevent memory exhaustion
        $page_data['all_cities'] = CityHelper::getCitiesForBlogs();
        $page_data['parent'] =  DB::table('pagecategories')
        ->where('pagecategories.category_parent_id',null)->get();
        $page_data['printable_categories'] =  DB::table('pagecategories')->where('category_parent_id',null)
        ->get();
        $page_data['all_states'] = DB::table('states')->select('states.*')
        ->where('country_id' , 101)->get();

        $page_data['listing']= DB::table('pages')->select('pages.*')
        ->join('page_category','page_category.page_id','pages.id')
        ->where('pages.item_status',2)
        ->where('pages.user_id',auth()->user()->id)
        ->distinct('pages.id')
        ->orderBy('pages.id','DESC')
        ->limit(500) // Add limit to prevent memory issues
        ->get();

        $page_data['countries'] = DB::table('countries')->select('countries.*')
        ->where('id' , 101)->get();

        //$page_data['blog_category'] = Blogcategory::all();
        $page_data['view_path'] = 'frontend.blogs.create_blog';
        return view('frontend.index', $page_data);
    }

    public function store(Request $request){
        $request->validate([
            'author' => 'required|max:255',
            'title' => 'required|max:255',
            'category' => 'required',
        ]);

        if ($request->image && !empty($request->image)) {

            $file_name = FileUploader::upload($request->image, 'public/storage/blog/thumbnail', 370);
            FileUploader::upload($request->image, 'public/storage/blog/coverphoto/'.$file_name, 900);
        }

       
           $title = 'blog';
            $approval = ManageApproval::where('title', $title)->first();

            if ($approval && $approval->status == 1) {
                // Approval status is ON
                $blog_status=2;

            } elseif (auth()->check() && auth()->user()->user_role == 'admin') {
                // Status is OFF but user is admin
                $blog_status=2;

            } else {
                //Status is OFF and user is not admin
                $blog_status=1;
            }

        // if(auth()->user()->user_role=="admin"){

        //     $blog_status=2;

        // }
        // else{

        //     $blog_status=1;
        // }


        if($request->state){

            $state_id=$request->state;
        }
        else
        {
            $state_id=null;
            
        }

        if($request->city){

            $city_id=$request->city;
        }
        else
        {
            $city_id=null;
            
        }

        if($request->area){

            $area_id=$request->area;
        }
        else
        {
            $area_id=null;
            
        }

        if($request->country){

            $country_id=$request->country;
        }
        else
        {
            $country_id=null;
            
        }


        $blog_slug=preg_replace("/[^A-Za-z0-9 ]/", '', $request->title);

       
        $multiSelectArray=$request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }
        
        $categories_id=implode(',', $multiSelectArray);

        $blog = new Blog();
        $blog->user_id = Auth::user()->id;
        $blog->auther_name = $request->author;
        $blog->title = $request->title;
        $blog->blog_slug =str_slug($blog_slug);
        $blog->category_id = $categories_id;
        $blog->state_id =$state_id;
        $blog->city_id =$city_id;
        $blog->area_id =$area_id;
        $blog->blog_status = $blog_status;

        $blog->publication_date = $request->publication_date;
        $blog->country_id = $country_id;
        $blog->list_id = $request->List;
        $blog->publication_status = $request->status;





        $tags =  json_decode($request->tag,true);
        $tag_array = array();
        if(is_array($tags)){
            foreach($tags as $key => $tag){
                $tag_array[$key]=$tag['value'];
            }
        }
        $blog->tag = json_encode($tag_array);
        $blog->description = $request->description;
        if($request->image && !empty($request->image)){
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

            if ($cityDays > 0) $blog->priority_until_city = $subscriptionStart->copy()->addDays($cityDays);
            if ($areaDays > 0) $blog->priority_until_area = $subscriptionStart->copy()->addDays($areaDays);
            if ($priorityEnd->isFuture()) $blog->item_featured = 1;
        }
        $done = $blog->save();
        if($done){
            
            
            foreach($request->category as $key => $category_id)
            {
                $data=array(
                    'category_id'=>$category_id,
                    "blog_id"=>$blog->id
                );
                $row=DB::table('blog_category')->insertGetId($data);


                
            }

            $slug_count=DB::table('blogs')->select('blogs.id')
            ->where('blogs.blog_slug',str_slug($request->title))->count();;
    
            if($slug_count>1){
    
                DB::table('blogs')->where('id', $blog->id)
                ->update(array('blog_slug' =>DB::raw('concat("'.str_slug($request->title).'",'.'-'.$blog->id.')')));
            }

            if (auth()->user()){
                    app(UserActivityService::class)->log(auth()->user()->id, 'blog_listing', 'blog', $blog->id, $blog->id);
             }
        Session::flash('success_message', get_phrase('Blog Created Successfully'));
        return redirect()->route('blogs');
        }
     
    }

    public function createCategoryFromSelect2(Request $request)
{
    $duplicateCount = DB::table('blogcategories')
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

         if (auth()->user()){
            app(UserActivityService::class)->log(auth()->user()->id, 'category_suggest', 'blog_category', $category->id, $category->id);
         }

        return response()->json([
            'id' => $category->id,
            'category_name' => $category->category_name
        ]);
    } else {
        // Return existing category if duplicate found (optional fallback)
        $existing = DB::table('blogcategories')
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
    if($request->has('q')){
    $search = $request->q;
    $data = DB::table("blogcategories")
    ->select("id","category_name")
    ->where('category_name','LIKE',"$search%")
    ->where('category_parent_id','!=',null)
    ->get();
    }
    return response()->json($data);
    }


    public function edit($id){
        $page_data['all_cities'] = CityHelper::getActiveCities();
        $page_data['blog_category'] = Blogcategory::all();
        $page_data['blog'] =  Blog::find($id);


        $page_data['all_states'] = DB::table('states')->select('states.*')
        ->where('country_id' , 101)->get();
        $page_data['all_cities'] = DB::table('cities')->select('cities.*')
        ->where('state_id' , $page_data['blog']->state_id)->get();
        $page_data['all_areas'] = DB::table('areas')->select('areas.*')
        ->where('city_id' , $page_data['blog']->city_id)->get();

        $page_data['printable_categories'] =  DB::table('blogcategories')->where('category_parent_id',null)
        ->get();

        $page_data['parent'] =  DB::table('blogcategories')
        ->where('blogcategories.category_parent_id',null)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();

        // $page_data['listing']= DB::table('pages')->select('pages.*')
        // ->join('page_category','page_category.page_id','pages.id')
        // ->where('pages.item_status',2)
        // ->where('pages.user_id',auth()->user()->id)
        // ->distinct('pages.id')
        // ->orderBy('pages.id','DESC')->get();

        $page_data['countries'] = DB::table('countries')->select('countries.*')
        ->where('id' , 101)->get();

        $page_data['view_path'] = 'frontend.blogs.edit_blog';
        return view('frontend.index', $page_data);
    }


    public function getPages(Request $request)
    {
        $search = $request->input('search');

        $pages = DB::table('pages')
            ->select('pages.id', 'pages.title')
            ->join('page_category','page_category.page_id','pages.id')
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




    public function update(Request $request,$id){
        
        $request->validate([
            'author' => 'required|max:255',
            'title' => 'required|max:255',
            'category' => 'required',
        ]);

        if ($request->image && !empty($request->image)) {

            $file_name = FileUploader::upload($request->image, 'public/storage/blog/thumbnail', 370);
            FileUploader::upload($request->image, 'public/storage/blog/coverphoto/'.$file_name, 900);
        }

        // if(auth()->user()->user_role=="admin"){

        //     $blog_status=2;

        // }
        // else{

        //     $blog_status=1;
        // }

        if($request->state){

            $state_id=$request->state;
        }
        else
        {
            $state_id=null;
            
        }

        if($request->city){

            $city_id=$request->city;
        }
        else
        {
            $city_id=null;
            
        }

        if($request->area){

            $area_id=$request->area;
        }
        else
        {
            $area_id=null;
            
        }

        if($request->country){

            $country_id=$request->country;
        }
        else
        {
            $country_id=null;
            
        }


        $blog_slug=preg_replace("/[^A-Za-z0-9 ]/", '', $request->title);

       
        $multiSelectArray=$request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }
        
        $categories_id=implode(',', $multiSelectArray);

        $blog = Blog::find($id);

        $title = 'blog';
            $approval = ManageApproval::where('title', $title)->first();

            if ($approval && $approval->status == 1) {
                // Approval status is ON
                $blog_status=2;

            } elseif (auth()->check() && auth()->user()->user_role == 'admin') {
                // Status is OFF but user is admin
                $blog_status=2;

            } else {
                //Status is OFF and user is not admin
                $blog_status=$blog->blog_status;
            }

        $blog->user_id = Auth::user()->id;
        // store image name for delete file operation 
        $imagename = $blog->thumbnail;

        $blog->user_id = Auth::user()->id;
        $blog->title = $request->title;
        $blog->blog_slug =str_slug($blog_slug);
        $blog->category_id = $categories_id;
        $blog->state_id =$state_id;
        $blog->city_id =$city_id;
        $blog->area_id =$area_id;
        $blog->blog_status = $blog_status;

        $blog->auther_name = $request->author;
        $blog->publication_date = $request->publication_date;
        $blog->country_id = $country_id;
        $blog->list_id = $request->List;
        $blog->publication_status = $request->status;


        $tags =  json_decode($request->tag,true);
        $tag_array = array();

        if(is_array($tags)){
            foreach($tags as $key => $tag){
                $tag_array[$key]=$tag['value'];
            }
        }
        $blog->tag = json_encode($tag_array);
        $blog->description = $request->description;
        !empty($request->image) ? $blog->thumbnail =  $file_name : $blog->thumbnail;
        $done = $blog->save();
        if ($done) {

            foreach($request->category as $key => $category_id)
            {
            $category_count=DB::table('blog_category')->select('blog_category.id')
            ->where('category_id',$category_id)
            ->where('blog_id',$id)
            ->count();
            if($category_count==0){
                $data=array(
                    'category_id'=>$category_id,
                    "blog_id"=>$id
                );
                $row=DB::table('blog_category')->insertGetId($data);
            }


            }
            $slug_count=DB::table('blogs')->select('pages.id')
            ->where('blogs.title',str_slug($request->title))->count();;
    
            if($slug_count>1){
    
                DB::table('blogs')->where('id', $id)
                ->update(array('blog_slug' =>DB::raw('concat("'.str_slug($request->title).'",'.'-'.$id.')')));
            }
            // just put the file name and folder name nothing more :) 
            if(!empty($request->image)){
                removeFile('blog', $imagename);
            }
            Session::flash('success_message', get_phrase('Blog Updated Successfully'));
            return redirect()->route('myblog');
        }
    }





    public function delete(){
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



    public function load_blog_by_scrolling(Request $request){
        $blogs =  Blog::orderBy('id', 'DESC')->skip($request->offset)->take(6)->get();
        $page_data['blogs'] = $blogs;
        return view('frontend.blogs.blog-single', $page_data);
    }



    public function single_blog($category_slug, $blog_slug, $city_slug = null, $area_slug = null) {
        $page_data['all_cities'] = DB::table('cities')->select('cities.*')
            ->join('pages', 'pages.city_id', 'cities.id')
            ->join('page_category', 'page_category.page_id', 'pages.id')
            ->join('pagecategories', 'page_category.category_id', '=', 'pagecategories.id')
            ->distinct('cities.id')
            ->where('pages.item_status', 2)
            ->orderBy('cities.city_name', 'asc')
            ->get();
    
        $category = Blogcategory::where('category_slug', $category_slug)->first(); 
        $city = null;
        $parentcategories = collect(); // default empty
    
        if ($city_slug) {
            $city = DB::table('cities')->where('city_slug', $city_slug)->first();
    
            if ($city && $category && $category->category_parent_id) {
                $parentcategories = DB::table('blogcategories')
                    ->select('blogcategories.*')
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
    
        $parentcategory = $category ? Blogcategory::find($category->category_parent_id) : null;
    
        $subcategories = [];
        foreach ($parentcategories as $allcategoriesresult) {
            $subcategories[] = $allcategoriesresult->category_name;
        }
    
        $page_data['category'] = $category;
        $page_data['parent_categories'] = $parentcategories;
    
        $blog = DB::table('blogs')->select('id')->where('blog_slug', $blog_slug)->first();
    
        if (!$blog) {
            abort(404);
        }
    
        if (auth()->user()){
            app(UserActivityService::class)->log(auth()->user()->id, 'view', 'blog', $blog->id,$blog->id);
        }
        $page_data['comments'] = Comments::where('is_type', 'blog')->where('id_of_type', $blog->id)->get();
        $page_data['socailshare'] = Share::currentPage()
            ->facebook()
            ->twitter()
            ->linkedin()
            ->telegram()
            ->getRawLinks();
    
        $blogs = Blog::find($blog->id);
        if ($blogs) {
            $blog_view_data = $blogs->view ? json_decode($blogs->view, true) : [];
    
            if (auth()->user() && !in_array(auth()->user()->id, $blog_view_data)) {
                $blog_view_data[] = auth()->user()->id;
                $blogs->view = json_encode($blog_view_data);
            }
    
            $blogs->save();
        }
    
        $page_data['blog'] = $blogs;

        $all_reviews = Review::where('marketplace_id', $blog->id)
        ->with('user')
        ->where('type', 'blog')
        ->latest()
        ->get();
    
        $page_data['reviews'] = $all_reviews->take(5); // First 5 reviews
        $page_data['has_more_reviews'] = $all_reviews->count() > 5;

        // $page_data['reviews'] = Review::where('marketplace_id', $blog->id)
        //             ->with('user')->where('type','blog')
        //             ->get();
    
        $page_data['categories'] = DB::table('blogcategories')
            ->select('blogcategories.*')
            ->join('blog_category', 'blog_category.category_id', '=', 'blogcategories.id')
            ->join('blogs', 'blogs.id', '=', 'blog_category.blog_id')
            ->where('blogs.blog_status', 2)
            ->distinct()
            ->get();
    
        $page_data['recent_posts'] = DB::table('blogs')
            ->select(
                'blogs.*', 'cities.city_slug', 'areas.area_slug',
                'cities.city_name', 'areas.area_name',
                'states.state_name', 'users.name as username', 'users.id as userid'
            )
            ->leftJoin('cities', 'cities.id', 'blogs.city_id')
            ->leftJoin('areas', 'areas.id', 'blogs.area_id')
            ->leftJoin('states', 'states.id', 'blogs.state_id')
            ->join('blog_category', 'blog_category.blog_id', 'blogs.id')
            ->join('users', 'users.id', 'blogs.user_id')
            ->distinct('blogs.id')
            ->where('blogs.blog_status', 2)
            ->orderBy('blogs.id', 'DESC')
            ->limit(10)
            ->get();
    
        $page_data['view_path'] = 'frontend.blogs.single_blog';
    
        return view('frontend.index', $page_data);
    }
    


    public function storeblogcategories(Request $request){
        $duplicatecount= DB::table('blogcategories')->where('category_name',$request->category_name)
        ->count();

        if($duplicatecount==0){
      
       

        $category = new Blogcategory();

    
        

        $category->category_name = $request->category_name;
        $category->category_slug = strtolower(str_replace(' ', '-', $request->category_name));
        $category->category_icon = "";
        $category->category_parent_id = $request->category_parent_id;
        $category->category_description = "";
        $category->category_createdby=auth()->user()->id;

        $category->save();

        if (auth()->user()){
            app(UserActivityService::class)->log(auth()->user()->id, 'category_suggest', 'blog_category', $category->id, $category->id);
         }

        \Session::flash('flash_message', __('Created'));
        \Session::flash('flash_type', 'success');
        return response()->json(1);
        }
        else{
            return response()->json("duplicate");
        }
    }

    public function jsonGetBlogParentCategories(){

        $parents =  DB::table('blogcategories')
        ->where('blogcategories.category_parent_id',null)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get()->toJson();

        return response()->json($parents);
    }


    public function jsonGetBlogCategories(){

        $parents =  DB::table('blogcategories')->select('blogcategories.id','blogcategories.category_name','cat.category_name as parent')
        ->leftjoin('blogcategories as cat','cat.id','=','blogcategories.category_parent_id')->orderby('id', 'asc')
        ->get()->toJson();

        return response()->json($parents);
    }


    public function jsonGetAreasByCityforblog(int $city_id)
    {
        
        
        $areas = DB::table("areas")
        ->select("areas.*")
        ->join('cities','cities.id','areas.city_id')
        ->join('blogs','blogs.area_id','areas.id')
        ->join('blog_category','blog_category.blog_id','blogs.id')
        ->join('blogcategories','blog_category.category_id','=','blogcategories.id')
        ->distinct('blogs.id')
        ->where('blogs.blog_status',2)
        ->where('areas.city_id',$city_id)
        ->where('blogs.city_id',$city_id)
        ->get()->toJson();

        return response()->json($areas);
    }
   


    // category wise page view
    public function category_blog(Request $request,$category_slug){
        $category = Blogcategory::where('category_slug', $category_slug)->first(); 
        $page_data['category']=$category;


        $page_data['all_cities'] = CityHelper::getActiveCities();
        if($category)
        {
            $page_data['all_blog_cities']= DB::table('cities')->select('cities.*')
            ->join('blogs','blogs.city_id','cities.id')
            ->join('blog_category','blog_category.blog_id','blogs.id')
            ->join('blogcategories','blog_category.category_id','=','blogcategories.id')
            ->distinct('cities.id')
            ->where('blogs.blog_status',2)->where(function ($query) use ($category) {
                $query->where('blog_category.category_id', $category->id);
            })
            ->orderBy('cities.city_name','asc')->get();

            
            SEOMeta::setTitle($category->category_name.' Blogs & Articles | Latest '.$category->category_name.' News | Cityhangaround');
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

            $page_data['categories']= DB::table('blogcategories')->select('blogcategories.*')
            ->join('blog_category','blog_category.category_id','=','blogcategories.id')
            ->join('blogs','blogs.id','=','blog_category.blog_id')
            ->where('blogs.blog_status',2)
            ->distinct()
            ->get();

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
    'cities.city_slug', 'areas.area_slug',
    'cities.city_name', 'areas.area_name',
    'states.state_name',
    'users.name as username', 'users.id as userid'
)
->leftJoin('cities', 'cities.id', '=', 'blogs.city_id')
->leftJoin('areas', 'areas.id', '=', 'blogs.area_id')
->leftJoin('states', 'states.id', '=', 'blogs.state_id')
->join('blog_category', 'blog_category.blog_id', '=', 'blogs.id')
->join('users', 'users.id', '=', 'blogs.user_id')
->where('blogs.blog_status', 2)
->where(function ($query) use ($category) {
    $query->where('blog_category.category_id', $category->id);
});

//  City filter
if (!empty($filter_city)) {
    $blogs_query->where('blogs.city_id', $filter_city);
}

//  Area filter
if (!empty($filter_area) && $filter_area !== "0") {
    $blogs_query->where('blogs.area_id', $filter_area);
}

//  Sorting: Featured First, then created_at based
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


    public function blogcategorycity(Request $request,$category_slug,$city_slug){

        $category = Blogcategory::where('category_slug', $category_slug)->first(); 
        $city = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();
        $page_data['city']=$city;
        $page_data['category']=$category;
       
        $page_data['all_cities'] = CityHelper::getActiveCities();

        $page_data['all_categories']= DB::table('blogcategories')->select('blogcategories.*')
        ->join('blog_category','blog_category.category_id','=','blogcategories.id')
        ->join('blogs','blog_category.blog_id','blogs.id')
        ->distinct('blogcategories.id')
        ->where('blogs.blog_status',2)
        ->orderBy('blogcategories.id','asc')
         ->where('blogs.city_id',$city->id)
       // ->Where('pagecategories.category_parent_id',$category->id)
             ->where(function ($query) use ($category) {
                $query->where('blog_category.category_id', $category->id)
                    ->orWhere('blogcategories.category_parent_id',$category->id);
            })
        ->get();

        
        $pageLiked = [];
        // $likepages = Page_like::where('user_id',auth()->user()->id)->get();
        // foreach($likepages as $likepage){
        //     $likepageid = $likepage->page_id;
        //     array_push($pageLiked,$likepageid);
        // }
        if(!is_null($category) && !is_null($city))
        {

            $page_data['categories']= DB::table('blogcategories')->select('blogcategories.*')
            ->join('blog_category','blog_category.category_id','=','blogcategories.id')
            ->join('blogs','blogs.id','=','blog_category.blog_id')
            ->where('blogs.blog_status',2)
            ->where('blogs.city_id', $city->id)
            ->distinct()
            ->get();


            $parentcategories = DB::table('blogcategories')->select('blogcategories.*')
            ->join('blog_category','blog_category.category_id','=','blogcategories.id')
            ->join('blogs','blog_category.blog_id','blogs.id')
            ->where('blogs.blog_status',2)
            ->where('blogcategories.id', $category->category_parent_id)
            ->where('blogs.city_id', $city->id)
            ->distinct('category_name')
            ->orderBy('category_name')->get();

        $parentcategory = Blogcategory::where('id', $category->category_parent_id)->first();
        //echo  $parentcategory;exit;


        $subcategories = [];

        foreach ($parentcategories as $allcategoriesresult) {
            $subcategories[] = $allcategoriesresult->category_name;
        }
        $page_data['parent_categories']=$parentcategories;

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
            // ->join('blogcategories','blog_category.category_id','=','blogcategories.id')
            // ->join('users','users.id','blogs.user_id')
            // ->distinct('blogs.id') ->where(function ($query) use ($category) {
            //     $query->where('blog_category.category_id', $category->id)
            //     ->orWhere('blogcategories.category_parent_id',$category->id);
            // })
            // ->where('blogs.blog_status',2)
            // ->distinct('blogs.id');

            //  $paid_items_query=  DB::table('blogs')->select('blogs.*','cities.city_slug','areas.area_slug',
            //  'cities.city_name','areas.area_name','states.state_name','users.name as username','users.id as userid' )
            //  ->leftjoin('cities','cities.id','blogs.city_id')
            //  ->leftjoin('areas','areas.id','blogs.area_id')
            //  ->leftjoin('states','states.id','blogs.state_id')
            //  ->join('blog_category','blog_category.blog_id','blogs.id')
            //  ->join('blogcategories','blog_category.category_id','=','blogcategories.id')
            //  ->join('users','users.id','blogs.user_id')
            //  ->distinct('blogs.id') ->where(function ($query) use ($category) {
            //      $query->where('blog_category.category_id', $category->id)
            //      ->orWhere('blogcategories.category_parent_id',$category->id);
            //  })
            //  ->where('blogs.blog_status',2)
            //  ->distinct('blogs.id');


          $paid_items_query = DB::table('blogs')->select(
    'blogs.*',
    'cities.city_slug', 'areas.area_slug',
    'cities.city_name', 'areas.area_name',
    'states.state_name',
    'users.name as username', 'users.id as userid'
)
->leftJoin('cities', 'cities.id', '=', 'blogs.city_id')
->leftJoin('areas', 'areas.id', '=', 'blogs.area_id')
->leftJoin('states', 'states.id', '=', 'blogs.state_id')
->join('blog_category', 'blog_category.blog_id', '=', 'blogs.id')
->join('blogcategories', 'blog_category.category_id', '=', 'blogcategories.id')
->join('users', 'users.id', '=', 'blogs.user_id')
->where('blogs.blog_status', 2)
->where('blogs.city_id', $city->id)
->where(function ($query) use ($category) {
    $query->where('blog_category.category_id', $category->id)
          ->orWhere('blogcategories.category_parent_id', $category->id);
})
->distinct('blogs.id');

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

// ➕ Final paginate
$paid_items = $paid_items_query->paginate(50);

// ➕ Preserve query params
$querystringArray = ['filter_sort_by' => $filter_sort_by];
$paid_items->appends($querystringArray);

// ➕ Assign to blade/view
$page_data['blogs'] = $paid_items;

$page_data['view_path'] = 'frontend.blogs.category_city_blog';

return view('frontend.blog_category_city_index', $page_data);


        }
        else{
            abort(404);
        }

    }


    public function area(Request $request, string $city_slug, string $area_slug){

        $page_data['city']= DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();
        $city=$page_data['city'];


        $page_data['all_cities'] = CityHelper::getActiveCities();

       

        if ($city) {
            $page_data['area'] = DB::table('areas')->select('areas.*')->where('area_slug', $area_slug)
            ->where('city_id',$city->id)
            ->first();
            $area=$page_data['area'];



             if($area) {

                SEOMeta::setTitle($area->area_name .','.$city->city_name.' nearby top business listing, deals, offers');
                SEOMeta::setDescription($area->area_name .','.$city->city_name.' nearby top business listings, deals, offers, local business');

                SEOMeta::setCanonical(URL::current());

                $page_data['categories']= DB::table('blogcategories')->select('blogcategories.*')
                ->join('blog_category','blog_category.category_id','=','blogcategories.id')
                ->join('blogs','blog_category.blog_id','blogs.id')
                ->distinct('blogcategories.id')
                ->where('blogs.blog_status',2)
                ->orderBy('blogcategories.id','asc')
                ->where("blogs.city_id", $city->id)
                ->where("blogs.area_id", $area->id)
                ->get();


              

                 //print_r($area);exit;

               


                //  $paid_items_query=   DB::table('blogs')->select('blogs.*','cities.city_slug','areas.area_slug',
                //  'cities.city_name','areas.area_name','states.state_name','users.name as username','users.id as userid' )
                //  ->join('cities','cities.id','blogs.city_id')
                //  ->join('areas','areas.id','blogs.area_id')
                //  ->join('states','states.id','blogs.state_id')
                //  ->join('blog_category','blog_category.blog_id','blogs.id')
                //  ->join('blogcategories','blog_category.category_id','=','blogcategories.id')
                //  ->join('users','users.id','blogs.user_id')
                //  ->where("blogs.city_id", $city->id)
                //  ->where("blogs.area_id", $area->id)
                //  ->where('blogs.blog_status',2)
                //  ->distinct('blogs.id');


              $filter_sort_by = $request->filter_sort_by ?? "newest";
$filter_city = $city->id ?? null;
$filter_area = $area->id ?? null;

$page_data['filter_sort_by'] = $filter_sort_by;

// Step 1: Build base query
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
->join('cities', 'cities.id', 'blogs.city_id')
->join('areas', 'areas.id', 'blogs.area_id')
->join('states', 'states.id', 'blogs.state_id')
->join('blog_category', 'blog_category.blog_id', 'blogs.id')
->join('blogcategories', 'blog_category.category_id', '=', 'blogcategories.id')
->join('users', 'users.id', 'blogs.user_id')
->where('blogs.city_id', $filter_city)
->where('blogs.area_id', $filter_area)
->where('blogs.blog_status', 2)
->orderByDesc('blogs.item_featured') // ✅ Featured blogs on top
->orderBy('blogs.id', 'desc')        // ✅ Latest first
->distinct('blogs.id');

// Step 2: Sort by filter
if ($filter_sort_by == "oldest") {
    $paid_items_query->orderBy('blogs.created_at', 'asc');
} else {
    $paid_items_query->orderBy('blogs.created_at', 'desc');
}

// Step 3: Paginate
$paid_items = $paid_items_query->paginate(50);
$paid_items->appends([
    'filter_sort_by' => $filter_sort_by
]);

// Step 4: Pass to view
$page_data['blogs'] = $paid_items;

$page_data['view_path'] = 'frontend.blogs.city_area_blog';

return view('frontend.blog_city_area_index', $page_data);




             }
            }

    }


    public function blogcategorycityarea(Request $request,$city_slug,$category_slug,$area_slug){

        $category = Blogcategory::where('category_slug', $category_slug)->first(); 
        $city = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();
        $page_data['area'] = DB::table('areas')->select('areas.*')->where('area_slug', $area_slug)
        ->where('city_id',$city->id)
        ->first();
        $area=$page_data['area'];
        $page_data['city']=$city;
        $page_data['category']=$category;
        $page_data['all_cities'] = CityHelper::getActiveCities();

        
        $page_data['categories']= DB::table('blogcategories')->select('blogcategories.*')
        ->join('blog_category','blog_category.category_id','=','blogcategories.id')
        ->join('blogs','blog_category.blog_id','blogs.id')
        ->distinct('blogcategories.id')
        ->where('blogs.blog_status',2)
        ->orderBy('blogcategories.id','asc')
        ->where(function ($query) use ($category) {
            $query->where('blog_category.category_id', $category->id)
                ->orWhere('blogcategories.category_parent_id',$category->id);
        })
        ->where("blogs.city_id", $city->id)
        ->where("blogs.area_id", $area->id)
        ->get();

       


        if(!is_null($category) && !is_null($city) && !is_null($area))
        {

            $parentcategories = DB::table('blogcategories')->select('blogcategories.*')
            ->join('blog_category','blog_category.category_id','=','blogcategories.id')
            ->join('blogs','blog_category.blog_id','blogs.id')
            ->where('blogs.blog_status',2)
            ->where('blogcategories.id', $category->category_parent_id)
            ->where('blogs.city_id', $city->id)
            ->where('blogs.city_id', $area->id)
            ->distinct('category_name')
            ->orderBy('category_name')->get();


          

        $parentcategory = Blogcategory::where('id', $category->category_parent_id)->first();
        //echo  $parentcategory;exit;


        $subcategories = [];

        foreach ($parentcategories as $allcategoriesresult) {
            $subcategories[] = $allcategoriesresult->category_name;
        }
        $page_data['parent_categories']=$parentcategories;

        SEOMeta::setTitle($area->area_name.' Near by top '.$category->category_name.', listing, deals, offers');
        //SEOMeta::setDescription($area->area_name . ', ' . 'Top 10 ' . $parentcategory->category_name . ', ' .  implode(',',$subcategories) . ' Free Listing Cityhangaround');
        SEOMeta::setDescription($area->area_name.' Near by top '.$category->category_name.', deals, offers');

        SEOMeta::setCanonical(URL::current());

           


            //echo  $request->city;exit;

            // $paid_items_query= DB::table('blogs')->select('blogs.*','cities.city_slug','areas.area_slug',
            //      'cities.city_name','areas.area_name','states.state_name','users.name as username','users.id as userid' )
            //      ->join('cities','cities.id','blogs.city_id')
            //      ->join('areas','areas.id','blogs.area_id')
            //      ->join('states','states.id','blogs.state_id')
            //      ->join('blog_category','blog_category.blog_id','blogs.id')
            //      ->join('blogcategories','blog_category.category_id','=','blogcategories.id')
            //      ->join('users','users.id','blogs.user_id')
            //      ->where("blogs.city_id", $city->id)
            //      ->where("blogs.area_id", $area->id)
            //      ->where(function ($query) use ($category) {
            //         $query->where('blog_category.category_id', $category->id)
            //         ->orWhere('blogcategories.category_parent_id',$category->id);
            //     })
            //      ->where('blogs.blog_status',2)
            //      ->distinct('blogs.id');
$filter_sort_by = empty($request->filter_sort_by) ? "newest" : $request->filter_sort_by;
$page_data['filter_sort_by'] = $filter_sort_by;

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
->join('cities', 'cities.id', 'blogs.city_id')
->join('areas', 'areas.id', 'blogs.area_id')
->join('states', 'states.id', 'blogs.state_id')
->join('blog_category', 'blog_category.blog_id', 'blogs.id')
->join('blogcategories', 'blog_category.category_id', '=', 'blogcategories.id')
->join('users', 'users.id', 'blogs.user_id')

// ✅ Match category or parent
->where(function ($query) use ($category) {
    $query->where('blog_category.category_id', $category->id)
          ->orWhere('blogcategories.category_parent_id', $category->id);
})

// ✅ Base filters
->where('blogs.blog_status', 2)
->where('blogs.city_id', $city->id)
->where('blogs.area_id', $area->id)

// ✅ Featured first, then latest
->orderByDesc('blogs.item_featured')
->orderByDesc('blogs.id')
->distinct('blogs.id');

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

// ✅ Final result to view
$page_data['blogs'] = $paid_items;

$page_data['view_path'] = 'frontend.blogs.category_city_area_blog';

return view('frontend.blog_category_city_area_index', $page_data);


        }
        else{
            abort(404);
        }
    }


    public function city(Request $request,$city_slug){
        $city = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();
        $page_data['city']=$city;


        $page_data['all_cities'] = CityHelper::getActiveCities();

        


        if(!is_null($city)){
            
            SEOMeta::setTitle('Latest Blogs & Articles in '.$city->city_name.' | Cityhangaround');
            SEOMeta::setDescription("Explore the latest blogs, articles, city news, events, and lifestyle updates in ".$city->city_name." Stay connected with what's happening around you on CityHangAround.");
            SEOMeta::setKeywords([
                "blogs in {{ $city->city_name }}", 
                "latest blogs {{ $city->city_name }}", 
                "{{ $city->city_name }} news", 
                "{{ $city->city_name }} events", 
                "lifestyle blogs {{ $city->city_name }}", 
                "trending articles {{ $city->city_name }}", 
                "local blogs {{ $city->city_name }}", 
                "CityHangAround blogs"
            ]);
            SEOMeta::setCanonical(URL::current());

            

            $categories=DB::table('blogcategories')->select('blogcategories.*')
            ->join('blog_category','blog_category.category_id','=','blogcategories.id')
            ->join('blogs','blog_category.blog_id','blogs.id')
            ->distinct('blogcategories.id')
            ->where('blogs.blog_status',2)
            ->orderBy('blogcategories.id','asc')
            ->where("blogs.city_id", $city->id)
            ->where('blogcategories.category_parent_id',null)
            ->get();

            //print_r($categories);exit;

            $page_data['categories']=$categories;

            $page_data['view_path'] = 'frontend.blogs.city_blog';
            return view('frontend.blog_city_index', $page_data);
        }
        else{

            abort(404);
        }

    }

    public static function getblogsbycategoryid($categoryid,$cityid){

       
        //echo $categoryid;

       
    //     $paid_items_query=   DB::table('blogs')->select('blogs.*','cities.city_slug','areas.area_slug',
    //     'cities.city_name','areas.area_name','states.state_name','users.name as username','users.id as userid' )
    //     ->join('cities','cities.id','blogs.city_id')
    //     ->join('areas','areas.id','blogs.area_id')
    //     ->join('states','states.id','blogs.state_id')
    //     ->join('blog_category','blog_category.blog_id','blogs.id')
    //     ->join('blogcategories','blog_category.category_id','=','blogcategories.id')
    //     ->join('users','users.id','blogs.user_id')
    //     ->where(function ($query) use ($categoryid) {
    //        $query->where('blog_category.category_id', $categoryid)
    //        ->orWhere('blogcategories.category_parent_id',$categoryid);
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
->join('areas', 'areas.id', '=', 'blogs.area_id')
->join('states', 'states.id', '=', 'blogs.state_id')
->join('blog_category', 'blog_category.blog_id', '=', 'blogs.id')
->join('blogcategories', 'blog_category.category_id', '=', 'blogcategories.id')
->join('users', 'users.id', '=', 'blogs.user_id')

// ✅ Category check
->where(function ($query) use ($categoryid) {
    $query->where('blog_category.category_id', $categoryid)
          ->orWhere('blogcategories.category_parent_id', $categoryid);
})

// ✅ Basic filters
->where('blogs.city_id', $cityid)
->where('blogs.blog_status', 2)

// ✅ Sorting: featured first
->orderByDesc('blogs.item_featured')
->orderByDesc('blogs.id')
->distinct('blogs.id')
->limit(4)
->get();




        return $paid_items_query;
    }




    // blog search 

    public function search(){
        $search = $_GET['search'];
        $output="";
        $posts=Blog::where('title','LIKE','%'.$search."%")->get();
        if($posts){
            foreach ($posts as $key => $post) {
            $output.='<div class="post-entry d-flex">'.
            '<div class="post-thumb"><img class="img-fluid rounded" src=" '. get_blog_image($post->thumbnail,"thumbnail") .' " alt="Recent Post"> </div>'.
            '<div class="post-txt ms-2">'.
            '<h3><a href="'. route("single.blog",$post->id) .'"> '. $post->title .'</a></h3>'.
            '<div class="post-meta">'.
            '<span class="date-meta"><a href="#">'.$post->created_at->format("d-M-Y").'</a></span>'.
            '</div>'.
            '</div>'.
            '</div>';
            }               
            return Response($output);
        }


    }

}
