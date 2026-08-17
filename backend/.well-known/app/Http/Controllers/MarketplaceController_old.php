<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use App\Models\Media_files;
use App\Models\SavedProduct;
use App\Models\FileUploader;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Image;
use Session;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Artesaos\SEOTools\Facades\SEOMeta;
use App\Mail\ProductMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class MarketplaceController_old extends Controller
{
    public function allproducts(Request $request){
        $page_data['all_cities']= DB::table('cities')->select('cities.*')
        ->join('pages','pages.city_id','cities.id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','page_category.category_id','=','pagecategories.id')
        ->distinct('cities.id')
        ->where('pages.item_status',2)
        ->orderBy('cities.id','DESC')->get();

        $page_data['parent'] =  DB::table('categories')
        ->where('categories.category_parent_id',0)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();


        $page_data['all_product_cities']= DB::table('cities')->select('cities.*')
        ->join('pages','pages.city_id','cities.id')
        ->join('marketplaces','marketplaces.page_id','pages.id')
        ->join('category_product','marketplaces.id','category_product.product_id')
        ->join('categories','category_product.product_category_id','=','categories.id')
        ->distinct('cities.id')
        ->where('marketplaces.product_status',2)
        ->orderBy('cities.city_name','asc')->get();

        $page_data['all_categories']= DB::table('categories')->select('categories.*')
        ->join('category_product','category_product.product_category_id','=','categories.id')
        ->join('marketplaces','marketplaces.id','category_product.product_id')
        ->distinct('categories.id')
        ->where('marketplaces.product_status',2)
        ->orderBy('categories.id','DESC')
        ->where('categories.category_parent_id',0)
            ->orWhereNull('categories.category_parent_id')
        ->get();

        $page_data['all_printable_categories']=DB::table('categories')->select('categories.*')
        ->join('category_product','category_product.product_category_id','=','categories.id')
        ->join('marketplaces','marketplaces.id','category_product.product_id')
        ->distinct('categories.id')
        ->orderBy('categories.id','DESC')
        ->where('marketplaces.product_status',2)
        ->where('categories.category_parent_id',0)
        ->orWhereNull('categories.category_parent_id')
        ->get();

        SEOMeta::setTitle(' Products Near me,');
        SEOMeta::setDescription('Near me Local City guide, Attractions, Travel Guide Local Listing, Shopping, Services, Restaurants, Travel, Free Listing Cityhangaround');
        SEOMeta::setCanonical(URL::current());


        $filter_city = empty($request->city) ? null : $request->city;
            $filter_area= empty($request->area) ? null : $request->area;
            if($filter_area=="" || is_null($filter_area)){
                $filter_area="0";
            }
            $page_data['filter_city']=$filter_city;
            $page_data['filter_area']=$filter_area;

            //echo  $filter_city;exit;


        $products_query= Marketplace::
        select('marketplaces.*')
        ->join('pages','marketplaces.page_id','pages.id')
        ->join('cities','pages.city_id','cities.id')
        ->join('category_product','marketplaces.id','category_product.product_id')
        ->distinct('marketplaces.id')
        ->where('marketplaces.product_status',2)
        ;
        

       

             // // filter paid listings city
             if(!empty($filter_city))
             {
                 $products_query->where('pages.city_id', $filter_city);
             }
 
             // // filter paid listings city
             if(!empty($filter_area))
             {
                 $products_query->where('pages.area_id', $filter_area);
             }

            $filter_sort_by = empty($request->filter_sort_by) ? "newest" : $request->filter_sort_by;
            $page_data['filter_sort_by']=$filter_sort_by ;
            if($filter_sort_by == "newest")
            {
                $products_query->orderBy('marketplaces.created_at', 'DESC');
            }
            elseif($filter_sort_by == "oldest")
            {
                $products_query->orderBy('marketplaces.created_at', 'ASC');
            }
            $paid_items=$products_query->orderBy('marketplaces.id','DESC')->paginate(12);
           
            $querystringArray = [
                'filter_sort_by' => $filter_sort_by,
                'filter_city' => $filter_city,
                'filter_area' => $filter_area,
            ];
            $paid_items->appends($querystringArray);
            $page_data['products']=$paid_items;

        

        
        $page_data['view_path'] = 'frontend.marketplace.products';

        return view('frontend.product_index', $page_data);
    }


    public function productcategory(Request $request,string $category_slug){

        $category = Category::where('product_category_slug', $category_slug)->first(); 
        $page_data['category']=$category;

        $page_data['all_cities']= DB::table('cities')->select('cities.*')
        ->join('pages','pages.city_id','cities.id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','page_category.category_id','=','pagecategories.id')
        ->distinct('cities.id')
        ->where('pages.item_status',2)
        ->orderBy('cities.id','DESC')->get();

        $page_data['parent'] =  DB::table('categories')
        ->where('categories.category_parent_id',0)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();


        $page_data['all_product_cities']= DB::table('cities')->select('cities.*')
        ->join('pages','pages.city_id','cities.id')
        ->join('marketplaces','marketplaces.page_id','pages.id')
        ->join('category_product','marketplaces.id','category_product.product_id')
        ->join('categories','category_product.product_category_id','=','categories.id')
        ->distinct('cities.id')
        ->where('marketplaces.product_status',2)
        ->where('category_product.product_category_id',$category->id)
        ->orderBy('cities.city_name','asc')->get();

        $page_data['all_categories']= DB::table('categories')->select('categories.*')
        ->join('category_product','category_product.product_category_id','=','categories.id')
        ->join('marketplaces','marketplaces.id','category_product.product_id')
        ->distinct('categories.id')
        ->orderBy('categories.id','DESC')
        ->where('marketplaces.product_status',2)
        ->where('categories.category_parent_id',0)
            ->orWhereNull('categories.category_parent_id')
        ->get();

        $page_data['all_printable_categories']=DB::table('categories')->select('categories.*')
        ->join('category_product','category_product.product_category_id','=','categories.id')
        ->join('marketplaces','marketplaces.id','category_product.product_id')
        ->distinct('categories.id')
        ->orderBy('categories.id','DESC')
        ->where('marketplaces.product_status',2)
        ->where(function ($query) use ($category) {
            // $query->where('category_product.product_category_id', $category->id)
            $query->Where('categories.category_parent_id',$category->id);
        })
        ->get();


        SEOMeta::setTitle('Best '.$category->product_category_name.' Deals');
        SEOMeta::setDescription('Get best '.$category->product_category_name.' deals ');
        SEOMeta::setCanonical(URL::current());


        $filter_city = empty($request->city) ? null : $request->city;
            $filter_area= empty($request->area) ? null : $request->area;
            if($filter_area=="" || is_null($filter_area)){
                $filter_area="0";
            }
            $page_data['filter_city']=$filter_city;
            $page_data['filter_area']=$filter_area;

            //echo  $filter_city;exit;


        $products_query= Marketplace::
        select('marketplaces.*')
        ->join('pages','marketplaces.page_id','pages.id')
        ->join('cities','pages.city_id','cities.id')
        ->join('category_product','marketplaces.id','category_product.product_id')
        ->distinct('marketplaces.id')
        ->where('marketplaces.product_status',2)
        ->where(function ($query) use ($category) {
            $query->where('category_product.product_category_id', $category->id);
        })
        ;

       

             // // filter paid listings city
             if(!empty($filter_city))
             {
                 $products_query->where('pages.city_id', $filter_city);
             }
 
             // // filter paid listings city
             if(!empty($filter_area))
             {
                 $products_query->where('pages.area_id', $filter_area);
             }

            $filter_sort_by = empty($request->filter_sort_by) ? "newest" : $request->filter_sort_by;
            $page_data['filter_sort_by']=$filter_sort_by ;
            if($filter_sort_by == "newest")
            {
                $products_query->orderBy('marketplaces.created_at', 'DESC');
            }
            elseif($filter_sort_by == "oldest")
            {
                $products_query->orderBy('marketplaces.created_at', 'ASC');
            }
            $paid_items=$products_query->orderBy('marketplaces.id','DESC')->paginate(10);
           
            $querystringArray = [
                'filter_sort_by' => $filter_sort_by,
                'filter_city' => $filter_city,
                'filter_area' => $filter_area,
            ];
            $paid_items->appends($querystringArray);
            $page_data['products']=$paid_items;

        

        
        $page_data['view_path'] = 'frontend.marketplace.category_products';
        return view('frontend.product_category_index', $page_data);
    }


    public function productcity(Request $request,$city_slug){

        $city = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();
        $page_data['city']=$city;
        $page_data['all_cities']= DB::table('cities')->select('cities.*')
        ->join('pages','pages.city_id','cities.id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','page_category.category_id','=','pagecategories.id')
        ->distinct('cities.id')
        ->where('pages.item_status',2)
        ->orderBy('cities.city_name','asc')->get();

        $page_data['all_printable_categories']=DB::table('categories')->select('categories.*')
        ->join('category_product','category_product.product_category_id','=','categories.id')
        ->join('marketplaces','marketplaces.id','category_product.product_id')
        ->join('pages','marketplaces.page_id','pages.id')
        ->where('marketplaces.product_status',2)
        ->distinct('categories.id')
        ->orderBy('categories.id','DESC')
        ->where('pages.city_id',$city->id)
        ->get();

        //print_r($page_data['all_categories']);exit;

        
        $pageLiked = [];
        // $likepages = Page_like::where('user_id',auth()->user()->id)->get();
        // foreach($likepages as $likepage){
        //     $likepageid = $likepage->page_id;
        //     array_push($pageLiked,$likepageid);
        // }
        if(!is_null($city))
        {
            

            SEOMeta::setTitle('Best Deals in' . ' ' . $city->city_name);
            SEOMeta::setDescription('Get best deal in'  . ' ' . $city->city_name);

            SEOMeta::setCanonical(URL::current());

            

           


            //echo  $request->city;exit;

            $paid_items_query=Marketplace::
            select('marketplaces.*')
            ->join('pages','marketplaces.page_id','pages.id')
            ->join('cities','pages.city_id','cities.id')
            ->join('category_product','marketplaces.id','category_product.product_id')
            ->join('categories','categories.id','category_product.product_category_id')
            ->distinct('marketplaces.id')
            ->where('marketplaces.product_status',2)
            ->where('pages.city_id',$city->id);

            
            
            

            $filter_sort_by = empty($request->filter_sort_by) ? "newest" : $request->filter_sort_by;
            $page_data['filter_sort_by']=$filter_sort_by ;
            if($filter_sort_by == "newest")
            {
                $paid_items_query->orderBy('marketplaces.created_at', 'DESC');
            }
            elseif($filter_sort_by == "oldest")
            {
                $paid_items_query->orderBy('marketplaces.created_at', 'ASC');
            }
            $paid_items=$paid_items_query->orderBy('marketplaces.id','DESC')->paginate(50);
           
            $querystringArray = [
                'filter_sort_by' => $filter_sort_by,
            ];
            $paid_items->appends($querystringArray);
            $page_data['products']=$paid_items;

            //print_r($paid_items);exit;
           

            $page_data['view_path'] = 'frontend.marketplace.productcity';
            return view('frontend.product_city_filter_index', $page_data);

        }
        else{
            abort(404);
        }
    }


    public function productcategorycity(Request $request,$category_slug,$city_slug){

        $category = Category::where('product_category_slug', $category_slug)->first(); 
        $city = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();
        $page_data['city']=$city;
        $page_data['category']=$category;
        $page_data['all_cities']= DB::table('cities')->select('cities.*')
        ->join('pages','pages.city_id','cities.id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','page_category.category_id','=','pagecategories.id')
        ->distinct('cities.id')
        ->where('pages.item_status',2)
        ->orderBy('cities.city_name','asc')->get();

        $page_data['all_categories']=DB::table('categories')->select('categories.*')
        ->join('category_product','category_product.product_category_id','=','categories.id')
        ->join('marketplaces','marketplaces.id','category_product.product_id')
        ->join('pages','marketplaces.page_id','pages.id')
        ->distinct('categories.id')
        ->orderBy('categories.id','DESC')
        ->where('pages.city_id',$city->id)
        ->where('marketplaces.product_status',2)
        ->where(function ($query) use ($category) {
             $query->where('category_product.product_category_id', $category->id)
            ->orWhere('categories.category_parent_id',$category->id);
        })
        ->get();

        //print_r($page_data['all_categories']);exit;

        
        $pageLiked = [];
        // $likepages = Page_like::where('user_id',auth()->user()->id)->get();
        // foreach($likepages as $likepage){
        //     $likepageid = $likepage->page_id;
        //     array_push($pageLiked,$likepageid);
        // }
        if(!is_null($category) && !is_null($city))
        {
            $parentcategories = DB::table('categories')->select('categories.*')
            ->join('category_product','category_product.product_category_id','=','categories.id')
            ->join('marketplaces','marketplaces.id','category_product.product_id')
            ->join('pages','marketplaces.page_id','pages.id')
            ->where('categories.id', $category->category_parent_id)
            ->where('pages.city_id', $city->id)
            ->where('marketplaces.product_status',2)
            ->distinct('product_category_name')
            ->orderBy('product_category_name')->get();

        $parentcategory = Category::where('id', $category->category_parent_id)->first();
        //echo  $parentcategory;exit;


        $subcategories = [];

        foreach ($parentcategories as $allcategoriesresult) {
            $subcategories[] = $allcategoriesresult->product_category_name;
        }
        $page_data['parent_categories']=$parentcategories;

            SEOMeta::setTitle('Best '. $category->product_product_category_name . ' Deals in ' . $city->city_name);
            SEOMeta::setDescription('Get best' . ' ' . $category->product_category_name .' deals in' . ' ' .  $city->city_name);

            SEOMeta::setCanonical(URL::current());

            

           


            //echo  $request->city;exit;

            $paid_items_query=Marketplace::
            select('marketplaces.*')
            ->join('pages','marketplaces.page_id','pages.id')
            ->join('cities','pages.city_id','cities.id')
            ->join('category_product','marketplaces.id','category_product.product_id')
            ->join('categories','categories.id','category_product.product_category_id')
            ->distinct('marketplaces.id')
            ->where('marketplaces.product_status',2)
            ->where('pages.city_id',$city->id)
            ->where(function ($query) use ($category) {
                $query->where('category_product.product_category_id', $category->id)
                ->orWhere('categories.category_parent_id',$category->id);
            });

            
            
            

            $filter_sort_by = empty($request->filter_sort_by) ? "newest" : $request->filter_sort_by;
            $page_data['filter_sort_by']=$filter_sort_by ;
            if($filter_sort_by == "newest")
            {
                $paid_items_query->orderBy('marketplaces.created_at', 'DESC');
            }
            elseif($filter_sort_by == "oldest")
            {
                $paid_items_query->orderBy('marketplaces.created_at', 'ASC');
            }
            $paid_items=$paid_items_query->orderBy('marketplaces.id','DESC')->paginate(50);
           
            $querystringArray = [
                'filter_sort_by' => $filter_sort_by,
            ];
            $paid_items->appends($querystringArray);
            $page_data['products']=$paid_items;

            //print_r($paid_items);exit;
           

            $page_data['view_path'] = 'frontend.marketplace.productcategorycity';
            return view('frontend.product_filter_index', $page_data);

        }
        else{
            abort(404);
        }
    }

    public function productcategorycityarea(Request $request,$city_slug,$category_slug,$area_slug){

       
        $category = Category::where('product_category_slug', $category_slug)->first(); 
        $city = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();
        $area = DB::table('areas')->select('areas.*')->where('area_slug', $area_slug)
        ->where('city_id', $city->id)
        ->first();
        //print_r($area);exit;
        $page_data['city']=$city;
        $page_data['area']=$area;
        $page_data['category']=$category;
        $page_data['all_cities']= DB::table('cities')->select('cities.*')
        ->join('pages','pages.city_id','cities.id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','page_category.category_id','=','pagecategories.id')
        ->distinct('cities.id')
        ->where('pages.item_status',2)
        ->orderBy('cities.city_name','asc')->get();

        $page_data['all_categories']=DB::table('categories')->select('categories.*')
        ->join('category_product','category_product.product_category_id','=','categories.id')
        ->join('marketplaces','marketplaces.id','category_product.product_id')
        ->join('pages','marketplaces.page_id','pages.id')
        ->distinct('categories.id')
        ->orderBy('categories.id','DESC')
        ->where('pages.city_id',$city->id)
        ->where('pages.area_id',$area->id)
        ->where('marketplaces.product_status',2)
        ->where(function ($query) use ($category) {
             $query->where('category_product.product_category_id', $category->id)
            ->orWhere('categories.category_parent_id',$category->id);
        })
        ->get();

        //print_r($page_data['all_categories']);exit;

        
        $pageLiked = [];
        // $likepages = Page_like::where('user_id',auth()->user()->id)->get();
        // foreach($likepages as $likepage){
        //     $likepageid = $likepage->page_id;
        //     array_push($pageLiked,$likepageid);
        // }
        if(!is_null($category) && !is_null($city))
        {
            $parentcategories = DB::table('categories')->select('categories.*')
            ->join('category_product','category_product.product_category_id','=','categories.id')
            ->join('marketplaces','marketplaces.id','category_product.product_id')
            ->join('pages','marketplaces.page_id','pages.id')
            ->where('categories.id', $category->category_parent_id)
            ->where('pages.city_id', $city->id)
            ->where('pages.area_id',$area->id)
            ->where('marketplaces.product_status',2)
            ->distinct('product_category_name')
            ->orderBy('product_category_name')->get();

        $parentcategory = Category::where('id', $category->category_parent_id)->first();
        //echo  $parentcategory;exit;


        $subcategories = [];

        foreach ($parentcategories as $allcategoriesresult) {
            $subcategories[] = $allcategoriesresult->product_category_name;
        }
        $page_data['parent_categories']=$parentcategories;

            SEOMeta::setTitle('Best '. $category->product_product_category_name . ' Deals in ' .$area->area_name.' - '.$city->city_name);
            SEOMeta::setDescription('Get best' . ' ' . $category->product_category_name .' deals in' . ' ' .$area->area_name.' - '.$city->city_name);

            SEOMeta::setCanonical(URL::current());

            

           


            //echo  $area->id;exit;

            $paid_items_query=Marketplace::
            select('marketplaces.*')
            ->join('pages','marketplaces.page_id','pages.id')
            ->join('cities','pages.city_id','cities.id')
            ->join('areas','pages.area_id','areas.id')
            ->join('category_product','marketplaces.id','category_product.product_id')
            ->join('categories','categories.id','category_product.product_category_id')
            ->distinct('marketplaces.id')
            ->where('pages.city_id',$city->id)
            ->where('pages.area_id',$area->id)
            ->where('marketplaces.product_status',2)
            ->where(function ($query) use ($category) {
                $query->where('category_product.product_category_id', $category->id)
                ->orWhere('categories.category_parent_id',$category->id);
            });

            
            
            

            $filter_sort_by = empty($request->filter_sort_by) ? "newest" : $request->filter_sort_by;
            $page_data['filter_sort_by']=$filter_sort_by ;
            if($filter_sort_by == "newest")
            {
                $paid_items_query->orderBy('marketplaces.created_at', 'DESC');
            }
            elseif($filter_sort_by == "oldest")
            {
                $paid_items_query->orderBy('marketplaces.created_at', 'ASC');
            }
            $paid_items=$paid_items_query->orderBy('marketplaces.id','DESC')->paginate(50);
           
            $querystringArray = [
                'filter_sort_by' => $filter_sort_by,
            ];
            $paid_items->appends($querystringArray);
            $page_data['products']=$paid_items;

            //print_r($paid_items);exit;
           

            $page_data['view_path'] = 'frontend.marketplace.productcategorycityarea';
            return view('frontend.product_category_city_area_index', $page_data);

        }
        else{
            abort(404);
        }
    }


    public function jsonGetAreasByCityforproduct(int $city_id){

        $areas = DB::table("areas")
        ->select("areas.*")
        ->join('cities','cities.id','areas.city_id')
        ->join('pages','pages.area_id','areas.id')
        ->join('marketplaces','marketplaces.page_id','pages.id')
        ->join('category_product','marketplaces.id','category_product.product_id')
        ->join('categories','category_product.product_category_id','=','categories.id')
        ->distinct('areas.id')
        ->where('marketplaces.product_status',2)
        ->where('areas.city_id',$city_id)
        ->where('pages.city_id',$city_id)
        ->get()->toJson();

        return response()->json($areas);
    }

    public function create(){
        $page_data['all_cities']= DB::table('cities')->select('cities.*')
        ->join('pages','pages.city_id','cities.id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','page_category.category_id','=','pagecategories.id')
        ->distinct('cities.id')
        ->where('pages.item_status',2)
        ->orderBy('cities.id','DESC')->get();

        $page_data['listing']= DB::table('pages')->select('pages.*')
        ->join('page_category','page_category.page_id','pages.id')
        ->where('pages.item_status',2)
        ->where('pages.user_id',auth()->user()->id)
        ->distinct('pages.id')
        ->orderBy('pages.id','DESC')->get();

        

        $page_data['parent'] =  DB::table('categories')
        ->where('categories.category_parent_id',0)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();
        $page_data['view_path'] = 'frontend.marketplace.create_product';
        return view('frontend.index', $page_data);
    }

    public function edit(Request $request){

        $page_data['all_cities']= DB::table('cities')->select('cities.*')
        ->join('pages','pages.city_id','cities.id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','page_category.category_id','=','pagecategories.id')
        ->distinct('cities.id')
        ->where('pages.item_status',2)
        ->orderBy('cities.id','DESC')->get();

        $page_data['listing']= DB::table('pages')->select('pages.*')
        ->join('page_category','page_category.page_id','pages.id')
        ->where('pages.item_status',2)
        ->where('pages.user_id',auth()->user()->id)
        ->distinct('pages.id')
        ->orderBy('pages.id','DESC')->get();

        

        $page_data['parent'] =  DB::table('categories')
        ->where('categories.category_parent_id',0)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();
        $page_data['product_id'] =$request->product_id;
        $page_data['view_path'] = 'frontend.marketplace.edit_product';
        return view('frontend.index', $page_data);
    }

    public function jsonGetParentCategories()
    {
        
        $parents =  DB::table('categories')->select('categories.id','categories.product_category_name','cat.product_category_name as parent')
        ->leftjoin('categories as cat','cat.id','=','categories.category_parent_id')->orderby('id', 'asc')
        ->get()->toJson();

        return response()->json($parents);
    }

    public function jsonGetproductbrand(){

        $brands =  DB::table('brands')->select('brands.id','brands.name')->orderby('name', 'asc')
        ->get()->toJson();

        return response()->json($brands);
    }

    public function storeparentcategories(Request $request)
    {
        
       
        $duplicatecount= DB::table('categories')->where('product_category_name',$request->category_name)
        ->count();

        if($duplicatecount==0){

        $category = new Category();

        $category->product_category_name = $request->category_name;
        $category->product_category_slug = strtolower(str_replace(' ', '-', $request->category_name));
        $category->category_parent_id = 0;
        $category->product_category_description = "";
        $category->product_category_createdby=auth()->user()->id;

        $category->save();

        \Session::flash('flash_message', __('Created'));
        \Session::flash('flash_type', 'success');
        return response()->json(1);
        }
        else{
            return response()->json("duplicate");
        }
        //return redirect()->route('user.items.create');
    }

    public function storebrand(Request $request)
    {

        $duplicatecount= DB::table('brands')->where('name',$request->brandname)
        ->count();

        if($duplicatecount==0){

        $brand = new Brand();

        $brand->name = $request->brandname;
        $brand->created_at = strtotime(date("d M, Y"));
        $brand->updated_at = strtotime(date("d M, Y"));

        $brand->save();

        \Session::flash('flash_message', __('Created'));
        \Session::flash('flash_type', 'success');
        return response()->json(1);
        }
        else{
            return response()->json("duplicate");
        }

    }

    public function storecategories(Request $request)
    {
        
       
        $duplicatecount= DB::table('categories')->where('product_category_name',$request->category_name)
        ->count();

        if($duplicatecount==0){

        $category = new Category();

        $category->product_category_name = $request->category_name;
        $category->product_category_slug = strtolower(str_replace(' ', '-', $request->category_name));
        $category->category_parent_id = $request->category_parent_id;
        $category->product_category_description = "";
        $category->product_category_createdby=auth()->user()->id;

        $category->save();

        \Session::flash('flash_message', __('Created'));
        \Session::flash('flash_type', 'success');
        return response()->json(1);
        }
        else{
            return response()->json("duplicate");
        }
        //return redirect()->route('user.items.create');
    }

    public function dataAjax(Request $request)
    {
    $data = [];
    if($request->has('q')){
    $search = $request->q;
    $data = DB::table("categories")
    ->select("id","product_category_name")
    ->where('product_category_name','LIKE',"$search%")
    ->where('category_parent_id','!=',0)
    ->get();
    }
    return response()->json($data);
    }

    public function userproduct(){
        $page_data['all_cities']= DB::table('cities')->select('cities.*')
        ->join('pages','pages.city_id','cities.id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','page_category.category_id','=','pagecategories.id')
        ->distinct('cities.id')
        ->where('pages.item_status',2)
        ->orderBy('cities.city_name','asc')->get();
        $products = Marketplace::where('user_id',auth()->user()->id)
        ->where('marketplaces.product_status',2)->orderBy('id','DESC')->get();
        $page_data['products'] = $products;
        $page_data['view_path'] = 'frontend.marketplace.user_products';
        return view('frontend.index', $page_data);
    }


    public function store(Request $request){
        $rules = array(
            'producttype' => 'required|max:255',
            'productnaturetype' => 'required',
            'parent' => 'required',
            'category' => 'required',
            'title' => 'required|max:255',
            'brand' => 'required',
            'List' => 'required',
            
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
        }

        $product_slug=preg_replace("/[^A-Za-z0-9 ]/", '', $request->title);


        $multiSelectArray=$request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }
        
        $categories_id=implode(',', $multiSelectArray);

        $marketplace = new Marketplace();
        $marketplace->product_type = $request->producttype;
        $marketplace->product_nature_type = $request->productnaturetype;
        $marketplace->user_id = auth()->user()->id;
        $marketplace->title = $request->title;
        $marketplace->product_slug = str_slug($product_slug);
        $marketplace->brand = $request->brand;
        $marketplace->category = $categories_id;
        $marketplace->currency_id = $request->currency;
        $marketplace->page_id = $request->List;
        $marketplace->product_original_price = $request->price;
        $marketplace->product_selling_price = $request->selling_price;
        $marketplace->video_url = $request->video_url;
        $marketplace->product_featured_service = $request->featured;

        $marketplace->startdate = $request->start_date;
        $marketplace->enddate = $request->end_date;
        $marketplace->location = $request->location;
        $marketplace->condition = $request->condition;
        $marketplace->status = $request->status;

        $marketplace->buy_link = $request->buy_link;
        $marketplace->description = $request->description;
        $marketplace->save();
        $product_id = $marketplace->id;
        if ($product_id) {
            foreach($request->category as $key => $category_id)
            {
                $data=array(
                    'product_category_id'=>$category_id,
                    "product_id"=>$product_id
                );
                $row=DB::table('category_product')->insertGetId($data);


            }

            $slug_count=DB::table('marketplaces')->select('marketplaces.id')
            ->where('marketplaces.product_slug',str_slug($product_slug))->count();;
    
            if($slug_count>1){
    
                DB::table('marketplaces')->where('id', $product_id)
                ->update(array('product_slug' =>DB::raw('concat("'.str_slug($product_slug).'",'.'-'.$product_id.')')));
            }


            if(is_array($request->multiple_files) && $request->multiple_files[0] != null){
                //Data validation
                $rules = array('multiple_files' => 'mimes:jpeg,jpg,png,gif');
                $validator = Validator::make($request->multiple_files, $rules);
                if ($validator->fails()){
                     return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
                }
    
                foreach ($request->multiple_files as $key => $media_file) {
                        
                    $file_name = FileUploader::upload($media_file,'public/storage/marketplace/thumbnail', 315);
                    FileUploader::upload($media_file,'public/storage/marketplace/coverphoto/'.$file_name, 315);

                    $file_type = 'image';

                    $productupdate = Marketplace::find($product_id);
                    $media_file_data = array('user_id' => auth()->user()->id, 'product_id' => $product_id, 'file_name' => $file_name, 'file_type' => $file_type);
                    $media_file_data['created_at'] = time();
                    $media_file_data['updated_at'] = $media_file_data['created_at'];
                    Media_files::create($media_file_data);
                    if($key=='0'){
                        $productupdate = Marketplace::find($product_id);
                        $productupdate->image = $file_name;
                        $productupdate->save();
                    }
                }
            }
            $user = User::find(auth()->user()->id);
      
            Mail::to($user->email)->queue(new ProductMail($user));
            Session::flash('success_message', get_phrase('Marketplace Product Added Successfully'));
            return json_encode(array('reload' => 1));
        }
    }


    public function update(Request $request,$id){
        $rules = array(
            'producttype' => 'required|max:255',
            'productnaturetype' => 'required',
            'parent' => 'required',
            'category' => 'required',
            'title' => 'required|max:255',
            'brand' => 'required',
            'List' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
        }

        
        $product_slug=preg_replace("/[^A-Za-z0-9 ]/", '', $request->title);


        $multiSelectArray=$request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }
        
        $categories_id=implode(',', $multiSelectArray);

        $marketplace = Marketplace::find($id);
        $marketplace->product_type = $request->producttype;
        $marketplace->product_nature_type = $request->productnaturetype;
        $marketplace->user_id = auth()->user()->id;
        $marketplace->title = $request->title;
        $marketplace->product_slug = str_slug($product_slug);
        $marketplace->brand = $request->brand;
        $marketplace->category = $categories_id;
        $marketplace->currency_id = $request->currency;
        $marketplace->page_id = $request->List;
        $marketplace->product_original_price = $request->price;
        $marketplace->product_selling_price = $request->selling_price;
        $marketplace->video_url = $request->video_url;
        $marketplace->product_featured_service = $request->featured;

        $marketplace->startdate = $request->start_date;
        $marketplace->enddate = $request->end_date;
        $marketplace->location = $request->location;
        $marketplace->condition = $request->condition;
        $marketplace->status = $request->status;

        $marketplace->buy_link = $request->buy_link;
        $marketplace->description = $request->description;
        $marketplace->save();
        $product_id = $id;
        if ($product_id) {

            foreach($request->category as $key => $category_id)
            {
                $category_count=DB::table('category_product')->select('category_product.id')
            ->where('product_category_id',$category_id)
            ->where('product_id',$product_id)
            ->count();
            if($category_count==0){
                $data=array(
                    'product_category_id'=>$category_id,
                    "product_id"=>$product_id
                );
                $row=DB::table('category_product')->insertGetId($data);


            }
          }

          $slug_count=DB::table('marketplaces')->select('marketplaces.id')
            ->where('marketplaces.product_slug',str_slug($product_slug))->count();;
    
            if($slug_count>1){
    
                DB::table('marketplaces')->where('id', $product_id)
                ->update(array('product_slug' =>DB::raw('concat("'.str_slug($product_slug).'",'.'-'.$product_id.')')));
            }
            if(is_array($request->multiple_files) && $request->multiple_files[0] != null){
                //Data validation
                $rules = array('multiple_files' => 'mimes:jpeg,jpg,png,gif');
                $validator = Validator::make($request->multiple_files, $rules);
                if ($validator->fails()){
                     return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
                }

                if(isset($request->multiple_files)){
                     // this for deleting previous data file 
                     $previousfile = Media_files::where('product_id',$id)->get();
                     foreach($previousfile as $previousfile){
                         $market = Media_files::find($previousfile->id);
                         // store image name for delete file operation 
                         $imagename = $market->banner;
                         $done = $market->delete();
                         if ($done) {
                             // just put the file name and folder name nothing more :) 
                             removeFile('marketplace', $imagename);
                         }
                     }
                 // end code sec 
                }
    
                foreach ($request->multiple_files as $key => $media_file) {
                    $file_name = FileUploader::upload($media_file,'public/storage/marketplace/thumbnail', 315);
                    FileUploader::upload($media_file,'public/storage/marketplace/coverphoto/'.$file_name, 315);
                    $file_type = 'image';
    
                    $productupdate = Marketplace::find($product_id);
                    $media_file_data = array('user_id' => auth()->user()->id, 'product_id' => $product_id, 'file_name' => $file_name, 'file_type' => $file_type);
                    $media_file_data['created_at'] = time();
                    $media_file_data['updated_at'] = $media_file_data['created_at'];
                    Media_files::create($media_file_data);
                    if($key=='0'){
                        $productupdate = Marketplace::find($product_id);
                        $productupdate->image = $file_name;
                        $productupdate->save();
                    }
                }
            }
            Session::flash('success_message', get_phrase('Marketplace Product Updated Successfully'));
            return json_encode(array('reload' => 1));
        }
    }



    public function product_delete(){
        $response = array();
        $market = Marketplace::find($_GET['product_id']);
        // store image name for delete file operation 
        $imagename = $market->banner;

        $done = $market->delete();
        if ($done) {
            $response = array('alertMessage' => get_phrase('Product Deleted Successfully'), 'fadeOutElem' => "#product-" . $_GET['product_id']);
            // just put the file name and folder name nothing more :) 
            removeFile('marketplace', $imagename);
        }
        return json_encode($response);
    }



    public function load_product_by_scrolling(Request $request){
        $products =  Marketplace::orderBy('id', 'DESC')->skip($request->offset)->take(6)->get();

        $page_data['products'] = $products;
        return view('frontend.marketplace.product-single', $page_data);
    }



    public function single_product($city_slug,$category_slug,$item_slug,$product_category_slug,$product_slug){

        $page_data['all_cities']= DB::table('cities')->select('cities.*')
        ->join('pages','pages.city_id','cities.id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','page_category.category_id','=','pagecategories.id')
        ->distinct('cities.id')
        ->where('pages.item_status',2)
        ->orderBy('cities.city_name','asc')->get();

        $category = Category::where('product_category_slug', $product_category_slug)->first(); 
       $city = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();

        $parentcategories = DB::table('categories')->select('categories.*')
       ->join('category_product','category_product.product_category_id','=','categories.id')
       ->join('marketplaces','marketplaces.id','=','category_product.product_id')
       ->join('pages','pages.id','=','marketplaces.page_id')
       ->where('categories.id', $category->category_parent_id)
       ->where('pages.city_id', $city->id)
       ->where('marketplaces.product_status',2)
       ->distinct('product_category_name')
       ->orderBy('product_category_name')->get();

       

        $parentcategory = Category::where('id', $category->category_parent_id)->first();
        //echo  $parentcategory;exit;


        $subcategories = [];

        foreach ($parentcategories as $allcategoriesresult) {
            $subcategories[] = $allcategoriesresult->product_category_name;
        }
        $page_data['parent_categories']=$parentcategories;
        $page_data['category']=$category;
        $page_data['city']=$city;
        $product = Marketplace::where('product_slug', $product_slug)->where('marketplaces.product_status',2)->get();

    //print_r( $product[0]->brand );exit;
        

        if($product){
            $page_data['related_product'] = Marketplace::Where('brand',$product[0]->brand) ->where('marketplaces.product_status',2)->orWhere('category',$product[0]->category)->get();
            $page_data['product'] = $product;
            $page_data['product_image'] = Media_files::where('product_id',$product[0]->id)->where('file_type','image')->get();
            $page_data['view_path'] = 'frontend.marketplace.single_product';
            return view('frontend.index', $page_data);
        }else{
            if(isset($_GET['shared'])){
                $page_data['post'] = '';
                return view('frontend.marketplace.custom_shared_view', $page_data);
            }else{
                return redirect()->back()->with('error_message', 'This product is not available');
            }
        }
    }

   


    // on key up product search 
    public function filter(){
        $search =  $_GET['search'];
        // $category =  $_GET['category'];
        // $condition =  $_GET['condition'];
        // $min =  $_GET['min'];
        // $max =  $_GET['max'];
        // $brand =  $_GET['brand'];
        // $location =  $_GET['location'];


        $page_data['all_cities']= DB::table('cities')->select('cities.*')
        ->join('pages','pages.city_id','cities.id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','page_category.category_id','=','pagecategories.id')
        ->distinct('cities.id')
        ->where('pages.item_status',2)
        ->orderBy('cities.city_name','asc')->get();
        $query = Marketplace::where('status', 1);

        if(isset($search) && !empty($search)){
            $query->where(function ($query) use ($search){
                $query->where('title', 'like', '%'. $search .'%')
                ->orWhere('description', 'like', '%'. $search .'%');
            });
        }

        // if(isset($condition) && !empty($condition)){
        //     $query->where('condition', $condition);
        // }

        // if(isset($category) && !empty($category)){
        //     $query->join('category_product', 'marketplaces.id','=','category_product.product_id');
        //     $query->where('category_product.product_category_id', $category);
        // }

        // if(isset($min) && !empty($min)){
        //     $query->where('price', '>=', $min);
        // }

        // if(isset($max) && !empty($max)){
        //     $query->where('price', '<=', $max);
        // }

        // if(isset($brand) && !empty($brand)){
        //     $query->where('brand', $brand);
        // }

        // if(isset($location) && !empty($location)){
        //     $query->where('location', 'like', '%'.$location.'%');
        // }

        $page_data['products'] = $query->paginate(12);
        $page_data['view_path'] = 'frontend.marketplace.products';
        return view('frontend.index', $page_data); 

    }




    public function saved_product(){
        $page_data['all_cities']= DB::table('cities')->select('cities.*')
        ->join('pages','pages.city_id','cities.id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','page_category.category_id','=','pagecategories.id')
        ->distinct('cities.id')
        ->where('pages.item_status',2)
        ->orderBy('cities.city_name','asc')->get();
        $page_data['saved_products'] = SavedProduct::all();
        $page_data['view_path'] = 'frontend.marketplace.saved_product';
        return view('frontend.index', $page_data);
    }

    public function save_for_later($id){
        $saveproduct = new SavedProduct();
        $saveproduct->user_id = auth()->user()->id;
        $saveproduct->product_id = $id;
        $saveproduct->save();

        Session::flash('success_message', get_phrase('Saved Successfully'));
        $response = array('reload' => 1);
        return json_encode($response);
    }


    public function unsave_for_later($id){
        $done = SavedProduct::where('product_id',$id)->where('user_id',auth()->user()->id)->delete();
        if($done){
            Session::flash('success_message', get_phrase('Unsaved Successfully'));
            $response = array('reload' => 1);
            return json_encode($response);
        }
    }



    public function single_product_ifrane($id){
        $product = Marketplace::find($id);
        $page_data['product'] = $product;
        $page_data['product_image'] = Media_files::where('product_id',$id)->where('file_type','image')->get();

        if($product){

            if(isset($_GET['shared'])){
                return view('frontend.marketplace.single_product_iframe', $page_data);
            }else{
                return redirect(route('single.product', $id));
            }
        }else{

            if(isset($_GET['shared'])){
                $page_data['post'] = '';
                return view('frontend.main_content.custom_shared_view', $page_data);
            }else{
                $page_data['post'] = '';
                $page_data['view_path'] = 'frontend.main_content.custom_shared_view';
                return view('frontend.index', $page_data);
            }
        }
    }


}
