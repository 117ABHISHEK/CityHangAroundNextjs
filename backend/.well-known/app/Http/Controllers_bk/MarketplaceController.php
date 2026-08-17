<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use App\Models\Media_files;
use App\Models\SavedProduct;
use App\Models\FileUploader;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ManageApproval;
use Illuminate\Http\Request;
use App\Models\Review;
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
use App\Helpers\CityHelper;
 use Illuminate\Support\Facades\Cache;
 use App\Services\UserActivityService;
 use Illuminate\Support\Str;

class MarketplaceController extends Controller
{

    public function updateLeadPrice(Request $request, $categoryId)
    {
        $request->validate([
            'lead_price' => 'required|numeric|min:0'
        ]);

        $category = Category::findOrFail($categoryId);
        $newPrice = $request->input('lead_price');

        // Update price recursively
        $category->updateLeadPriceRecursively($newPrice);

        return response()->json(['message' => 'Lead price updated successfully']);
    }

    public function getCities(Request $request)
    {
        //echo "123";exit;
        $search = $request->get('q'); // Get the search query
        $cities =  DB::table('cities')->where('city_name', 'like', '%' . $search . '%')
            ->select('id', 'city_name')
            ->get();

        return response()->json($cities);
    }

    public function getProducts(Request $request){
        //echo "123";exit;
        $search = $request->get('q'); // Get the search query
        $cities =  DB::table('marketplaces')->where('title', 'like', '%' . $search . '%')
        ->where('product_status',2)
            ->select('id', 'title')
            ->get();

        return response()->json($cities);
    }

    public function storeenquiry(Request $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'mobile' => 'required|digits:10',
        'city_id' => 'required',
        'product_id' => 'required',
    ]);

    // // Check if a similar enquiry already exists using DB::table()
    // $existingEnquiry = DB::table('enquirymaster')
    //     ->where('name', $validated['name'])
    //     ->where('mobileno', $validated['mobile'])
    //     ->where('cityid', $validated['city_id'])
    //     ->where('productid', $validated['product_id'])
    //     ->where('userid', auth()->user()->id)
    //     ->first();

    // // If an enquiry with the same details already exists, return a validation error
    // if ($existingEnquiry) {
    //     return response()->json([
    //         'message' => 'This enquiry has already been submitted.',
    //     ], 400); // Return a 400 status code for bad request
    // }

    // Insert the new enquiry using DB::table()
    //echo $validated['name'];exit;
    DB::table('enquirymaster')->insert([
        'name' => $validated['name'],
        'mobileno' => $validated['mobile'],
        'cityid' => $validated['city_id'],
        'productid' => $validated['product_id'],
        'userid' => auth()->user()->id,
    ]);

     if (auth()->user()){
          app(UserActivityService::class)->log(auth()->user()->id, 'marketplace_enquiry', 'product', $validated['product_id'], $validated['product_id']);
     }

    // Return response with success message
    return response()->json([
        'message' => 'Your enquiry has been submitted successfully!'
    ]);
}
    // public function allproducts(Request $request){

    //     SEOMeta::setTitle('Best Local Deals & Discounts | Save on Restaurants, Shopping & services ');
    //     //SEOMeta::setDescription($area->area_name . ', ' . 'Top 10 ' . $parentcategory->category_name . ', ' .  implode(',',$subcategories) . ' Free Listing Cityhangaround');
    //     SEOMeta::setDescription('Find the best local deals, discounts, and offers on restaurants, shopping, entertainment, and more. Explore exclusive savings in your city with Cityhangaround!');
    //     SEOMeta::setKeywords([
    //         'local deals', 
    //         'discounts', 
    //         'offers', 
    //         'best deals near me', 
    //         'city discounts', 
    //         'shopping deals', 
    //         'restaurant deals', 
    //         'entertainment offers', 
    //         'local savings'
    //     ]);
        
    //     SEOMeta::setCanonical(URL::current());


    //     $page_data['all_cities'] = CityHelper::getActiveCities();

    //     $page_data['parent'] =  DB::table('categories')
    //     ->where('categories.category_parent_id',0)
    //     // ->orWhereNull('pagecategories.category_parent_id')
    //     ->get();


    //     $page_data['all_product_cities']= DB::table('cities')->select('cities.*')
    //     ->join('pages','pages.city_id','cities.id')
    //     ->join('marketplaces','marketplaces.page_id','pages.id')
    //     ->join('category_product','marketplaces.id','category_product.product_id')
    //     ->join('categories','category_product.product_category_id','=','categories.id')
    //     ->distinct('cities.id')
    //     ->where('marketplaces.product_status',2)
    //     ->orderBy('cities.city_name','asc')->get();

    //     $page_data['all_categories']= DB::table('categories')->select('categories.*')
    //     ->join('category_product','category_product.product_category_id','=','categories.id')
    //     ->join('marketplaces','marketplaces.id','category_product.product_id')
    //     ->distinct('categories.id')
    //     ->where('marketplaces.product_status',2)
    //     ->orderBy('categories.id','DESC')
    //     ->where('categories.category_parent_id',0)
    //         ->orWhereNull('categories.category_parent_id')
    //     ->get();

        // $page_data['all_printable_categories']=DB::table('categories')->select('categories.*')
        // ->join('category_product','category_product.product_category_id','=','categories.id')
        // ->join('marketplaces','marketplaces.id','category_product.product_id')
        // ->distinct('categories.id')
        // ->orderBy('categories.id','DESC')
        // ->where('marketplaces.product_status',2)
        // ->where('categories.category_parent_id',0)
        // ->orWhereNull('categories.category_parent_id')
        // ->get();

       


    //     $filter_city = empty($request->city_filter) ? null : $request->city_filter;
    //         $filter_area= empty($request->area_filter) ? null : $request->area_filter;
    //         if($filter_area=="" || is_null($filter_area)){
    //             $filter_area="0";
    //         }
    //         $page_data['filter_city']=$filter_city;
    //         $page_data['filter_area']=$filter_area;

    //         //echo  $filter_city;exit;


    //     $products_query= Marketplace::
    //     select('marketplaces.*')
    //     ->join('pages','marketplaces.page_id','pages.id')
    //     ->join('cities','pages.city_id','cities.id')
    //     ->join('category_product','marketplaces.id','category_product.product_id')
    //     ->distinct('marketplaces.id')
    //     ->where('marketplaces.product_status',2)
    //     ;

       

    //          // // filter paid listings city
    //          if(!empty($filter_city))
    //          {
    //              $products_query->where('pages.city_id', $filter_city);
    //          }
 
    //          // // filter paid listings city
    //          if(!empty($filter_area))
    //          {
    //              $products_query->where('pages.area_id', $filter_area);
    //          }

    //         $filter_sort_by = empty($request->filter_sort_by) ? "newest" : $request->filter_sort_by;
    //         $page_data['filter_sort_by']=$filter_sort_by ;
    //         if($filter_sort_by == "newest")
    //         {
    //             $products_query->orderBy('marketplaces.created_at', 'DESC');
    //         }
    //         elseif($filter_sort_by == "oldest")
    //         {
    //             $products_query->orderBy('marketplaces.created_at', 'ASC');
    //         }
    //         $paid_items=$products_query->orderBy('marketplaces.id','DESC')->paginate(12);
           
    //         $querystringArray = [
    //             'filter_sort_by' => $filter_sort_by,
    //             'filter_city' => $filter_city,
    //             'filter_area' => $filter_area,
    //         ];
    //         $paid_items->appends($querystringArray);
    //         $page_data['products']=$paid_items;

        

        
    //     $page_data['view_path'] = 'frontend.marketplace.products';
    //     return view('frontend.product_index', $page_data);
    // }

 public function allproducts(Request $request)
{

    // Get current URL
$currentUrl = URL::current();

// Detect current page number from query parameters (e.g., ?page=2)
$page = request()->query('page', 1); // default to 1 if 'page' not present

// If on a pagination page (page > 1), set canonical to first page URL
if ($page > 1) {
    // Remove the 'page' query parameter from current URL to get canonical URL
    $canonicalUrl = URL::current(); // base URL without query parameters
    $query = request()->query();
    unset($query['page']); // remove page param

    if (!empty($query)) {
        $canonicalUrl .= '?' . http_build_query($query);
    }
        } else {
            // On first page, canonical is current URL
            $canonicalUrl = $currentUrl;
        }

        SEOMeta::setTitle('Best Local Deals & Discounts | Save on Restaurants, Shopping & services ');
        //SEOMeta::setDescription($area->area_name . ', ' . 'Top 10 ' . $parentcategory->category_name . ', ' .  implode(',',$subcategories) . ' Free Listing Cityhangaround');
        SEOMeta::setDescription('Find the best local deals, discounts, and offers on restaurants, shopping, entertainment, and more. Explore exclusive savings in your city with Cityhangaround!');
        SEOMeta::setKeywords([
            'local deals', 
            'discounts', 
            'offers', 
            'best deals near me', 
            'city discounts', 
            'shopping deals', 
            'restaurant deals', 
            'entertainment offers', 
            'local savings'
        ]);

        SEOMeta::setCanonical($canonicalUrl);

    $page_data = [];

    // ✅ Cache city and category data
    $page_data['all_cities'] = Cache::remember('active_cities', 3600, fn () => CityHelper::getActiveCities());

    $page_data['parent'] = Cache::remember('parent_categories', 3600, fn () =>
        DB::table('categories')->where('category_parent_id', 0)->get()
    );

    $page_data['all_printable_categories'] = Cache::remember('leaf_marketplace_categories', 3600, function () {
        return \App\Models\Category::whereHas('marketplaces', function ($q) {
                $q->where('product_status', 2);
            })
            ->orderByDesc('id')
            ->distinct()
            ->get();
    });

    $page_data['all_product_cities'] = Cache::remember('all_product_cities', 3600, function () {
        return DB::table('cities')
            ->select('cities.*')
            ->join('pages', 'pages.city_id', '=', 'cities.id')
            ->join('marketplaces', 'marketplaces.page_id', '=', 'pages.id')
            ->where('marketplaces.product_status', 2)
            ->distinct()
            ->orderBy('cities.city_name', 'asc')
            ->get();
    });

    $page_data['all_product_areas'] = Cache::remember('all_product_areas', 3600, function () {
        return DB::table('areas')
            ->select('areas.*')
            ->join('pages', 'pages.area_id', '=', 'areas.id')
            ->join('marketplaces', 'marketplaces.page_id', '=', 'pages.id')
            ->where('marketplaces.product_status', 2)
            ->distinct()
            ->orderBy('areas.area_name', 'asc')
            ->get();
    });

    
    // ✅ Product Query with Filters (relations updated)
//        $products_query = Marketplace::with([
//         'page.area.city',
//         'reviews', // relation already filters 'type' => 'product'
//         'productCategories',
//         'page.categories'
//     ])
//    ->withAvg(['reviews as avg_rating' => function ($q) {
//     $q->where('type', 'product'); // ✅ this works now
// }], 'rating')
//     ->where('product_status', 2);
$products_query = Marketplace::with([
    'page.area.city',
    'reviews',
    'productCategories',
    'page.categories'
])
->withAvg(['reviews as avg_rating' => function ($q) {
    $q->where('type', 'product');
}], 'rating')
->where('product_status', 2);

// City Filter
if ($request->filled('city_filter')) {
    $products_query->whereHas('page', function ($q) use ($request) {
        $q->where('city_id', $request->city_filter);
    });
}

// Area Filter
if ($request->filled('area_filter') && $request->area_filter != 0) {
    $products_query->whereHas('page', function ($q) use ($request) {
        $q->where('area_id', $request->area_filter);
    });
}

// Sorting Logic
$sort_by = $request->input('filter_sort_by', 'newest');
$page_data['filter_sort_by'] = $sort_by;

// Prioritize featured, then by sorting
$products_query->orderByDesc('item_featured');
$products_query->orderBy('marketplaces.created_at', $sort_by === 'oldest' ? 'asc' : 'desc');

// Paginate
$page_data['products'] = $products_query->paginate(12);

// Save filters to pass to view
$page_data['filter_city'] = $request->input('city_filter');
$page_data['filter_area'] = $request->input('area_filter');

$page_data['view_path'] = 'frontend.marketplace.products';

return view('frontend.product_index', $page_data);
}






    public function Category($id)
    {
        $category = ProductCategory::find($id);

        return response()->json([
            'parent_id' => $category->parent_id // assuming 'parent_id' is the column name
        ]);
    }


    public function productcategory(Request $request,string $category_slug){

        $category = Category::where('product_category_slug', $category_slug)->first(); 
        $page_data['category']=$category;
        

        $page_data['all_cities'] = CityHelper::getActiveCities();

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

        

        SEOMeta::setTitle('Best '.$category->product_category_name.' Deals – Save Big on Top Offers & Discounts');
        SEOMeta::setDescription('Find the best '.$category->product_category_name.' deals and exclusive discounts on restaurants, shopping, salons, spas, and entertainment. Get the hottest offers and save more with City Hangaround!');
        SEOMeta::setKeywords([
            $category->name . ' deals',
            'best ' . $category->name . ' discounts',
            'shopping deals',
            'restaurant offers',
            'top savings on ' . $category->name,
            'limited-time ' . $category->name . ' promotions',
            'exclusive ' . $category->name . ' coupons',
        ]);
        
        SEOMeta::setCanonical(URL::current());

$filter_city = $request->city ?? null;
$filter_area = $request->area ?? '0';

$page_data['filter_city'] = $filter_city;
$page_data['filter_area'] = $filter_area;
       $products_query = Marketplace::select('marketplaces.*')
    ->join('pages', 'marketplaces.page_id', '=', 'pages.id')
    ->join('cities', 'pages.city_id', '=', 'cities.id')
    ->join('category_product', 'marketplaces.id', '=', 'category_product.product_id')
    ->where('marketplaces.product_status', 2)
    ->where('category_product.product_category_id', $category->id)
    ->when(!empty($filter_city), fn($q) => $q->where('pages.city_id', $filter_city))
    ->when(!empty($filter_area) && $filter_area !== '0', fn($q) => $q->where('pages.area_id', $filter_area))
    ->orderByDesc('marketplaces.item_featured') // 👈 Yeh line laayi item_featured top pe
    ->orderByDesc('marketplaces.created_at')   // 👈 Phir latest
    ->orderByDesc('marketplaces.id')           // 👈 Fallback
    ->distinct('marketplaces.id');

// Sorting (optional override)
$filter_sort_by = $request->input('filter_sort_by', 'newest');
$page_data['filter_sort_by'] = $filter_sort_by;

if ($filter_sort_by === "oldest") {
    $products_query->orderBy('marketplaces.created_at', 'ASC');
}

$paid_items = $products_query->paginate(10);

$paid_items->appends([
    'filter_sort_by' => $filter_sort_by,
    'filter_city' => $filter_city,
    'filter_area' => $filter_area,
]);

$page_data['products'] = $paid_items;

$page_data['view_path'] = 'frontend.marketplace.category_products';
return view('frontend.product_category_index', $page_data);

    }


    public function productarea(Request $request,$city_slug,$area_slug){

       

        $city = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();
        $area = DB::table('areas')->select('areas.*')->where('city_id', $city->id)->where('area_slug', $area_slug)->first();
        $page_data['city']=$city;
        $page_data['area']=$area;
        $page_data['all_cities'] = CityHelper::getActiveCities();

        // Get all categories for the area
        $page_data['all_categories']=DB::table('categories')->select('categories.*')
        ->join('category_product','category_product.product_category_id','=','categories.id')
        ->join('marketplaces','marketplaces.id','category_product.product_id')
        ->join('pages','marketplaces.page_id','pages.id')
        ->where('marketplaces.product_status',2)
        ->distinct('categories.id')
        ->orderBy('categories.id','DESC')
        ->where('pages.city_id',$city->id)
        ->where('pages.area_id',$area->id)
        ->get();

        $page_data['all_printable_categories'] = $page_data['all_categories'];

        //print_r($page_data['all_categories']);exit;

        
        $pageLiked = [];
        // $likepages = Page_like::where('user_id',auth()->user()->id)->get();
        // foreach($likepages as $likepage){
        //     $likepageid = $likepage->page_id;
        //     array_push($pageLiked,$likepageid);
        // }
        if(!is_null($city))
        {
            
            
            SEOMeta::setTitle('Top deals, offers, discount '.$area->area_name.', '.$city->city_name);
            SEOMeta::setDescription('Top deals, offers, discount '.$area->area_name.','. $city->city_name.', best product, services ');
            SEOMeta::setKeywords([
                $area->area_name . ' deals', 
                'best deals in ' . $area->area_name . ' ' . $city->city_name, 
                'discounts in ' . $area->area_name, 
                'shopping offers ' . $area->area_name, 
                'restaurant deals in ' . $area->area_name . ' ' . $city->city_name, 
                'best savings in ' . $area->area_name
            ]);
            
            SEOMeta::setCanonical(URL::current());

            

           


            //echo  $request->city;exit;

            // $paid_items_query=Marketplace::
            // select('marketplaces.*')
            // ->join('pages','marketplaces.page_id','pages.id')
            // ->join('cities','pages.city_id','cities.id')
            // ->join('category_product','marketplaces.id','category_product.product_id')
            // ->join('categories','categories.id','category_product.product_category_id')
            // ->distinct('marketplaces.id')
            // ->where('marketplaces.product_status',2)
            // ->where('pages.city_id',$city->id)
            // ->where('pages.area_id',$area->id);

     $paid_items_query = Marketplace::with([
    'page.area.city',
    'reviews',
    'productCategories',
    'page.categories'
])
->withAvg(['reviews as avg_rating' => function ($q) {
    $q->where('type', 'product');
}], 'rating')
->where('product_status', 2)
->whereHas('page', function ($q) use ($city, $area) {
    $q->where('city_id', $city->id)
      ->where('area_id', $area->id);
});

    // Apply filters
    if ($request->filled('category_filter') && $request->category_filter != '0') {
        $paid_items_query->whereHas('productCategories', function ($q) use ($request) {
            $q->where('product_category_id', $request->category_filter);
        });
    }

    if ($request->filled('subcategory_filter') && $request->subcategory_filter != '0') {
        $paid_items_query->whereHas('productCategories', function ($q) use ($request) {
            $q->where('product_category_id', $request->subcategory_filter);
        });
    }

    if ($request->filled('search_filter')) {
        $search = $request->search_filter;
        $paid_items_query->where(function($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('productCategories', function ($q) use ($search) {
                      $q->where('product_category_name', 'like', '%' . $search . '%');
                  });
        });
    }

    // 👇 No need for complex is_priority count anymore if item_featured is top
    $paid_items_query->where(function ($query) use ($city, $area) {
    $query
        ->whereHas('page.user.userSubscriptions', function ($q) use ($city, $area) {
            $q->where('status', 'active')
              ->where('expires_at', '>=', now())
              ->whereHas('subscription', function ($subQ) use ($city, $area) {
                  $subQ->where(function ($sq) {
                          $sq->where('offered_services', 'like', '%marketplace%')
                             ->orWhereNull('offered_services');
                      })
                      ->where(function ($sq) use ($city, $area) {
                          $sq->whereNull('area_durations')
                             ->orWhereRaw("JSON_SEARCH(JSON_EXTRACT(area_durations, '$.marketplace'), 'one', ?, NULL, '$.*.city') IS NOT NULL", [$city->id])
                             ->orWhereRaw("JSON_SEARCH(JSON_EXTRACT(area_durations, '$.marketplace'), 'one', ?, NULL, '$.*.area') IS NOT NULL", [$area->id]);
                      });
              });
        })
        ->orWhereDoesntHave('page.user.userSubscriptions');
});

// 🟢 Sort featured on top, then by created time or fallback to id
$paid_items_query->orderByDesc('marketplaces.item_featured'); // 👈 Yeh sabse important
$filter_sort_by = $request->input('filter_sort_by', 'newest');
$page_data['filter_sort_by'] = $filter_sort_by;

if ($filter_sort_by === "oldest") {
    $paid_items_query->orderBy('marketplaces.created_at', 'ASC');
} else {
    $paid_items_query->orderBy('marketplaces.created_at', 'DESC');
}

$paid_items = $paid_items_query->orderBy('marketplaces.id', 'DESC')->paginate(50);

// 🔁 Query string for pagination
$querystringArray = [
    'filter_sort_by' => $filter_sort_by,
];
$paid_items->appends($querystringArray);
$page_data['products'] = $paid_items;


            //print_r($paid_items);exit;
           

            $page_data['view_path'] = 'frontend.marketplace.productarea';
            return view('frontend.product_area_filter_index', $page_data);

        }
        else{
            abort(404);
        }
    }


    public function productcity(Request $request,$city_slug){

        $city = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();
        $page_data['city']=$city;
        $page_data['all_cities'] = CityHelper::getActiveCities();

        // Get all areas for the city
        $page_data['all_areas'] = DB::table('areas')
            ->select('areas.*')
            ->join('pages', 'pages.area_id', '=', 'areas.id')
            ->join('marketplaces', 'marketplaces.page_id', '=', 'pages.id')
            ->where('marketplaces.product_status', 2)
            ->where('areas.city_id', $city->id)
            ->distinct('areas.id')
            ->orderBy('areas.area_name', 'asc')
            ->get();

        // Get all categories for the city
        $page_data['all_categories'] = DB::table('categories')->select('categories.*')
        ->join('category_product','category_product.product_category_id','=','categories.id')
        ->join('marketplaces','marketplaces.id','category_product.product_id')
        ->join('pages','marketplaces.page_id','pages.id')
        ->where('marketplaces.product_status',2)
        ->distinct('categories.id')
        ->orderBy('categories.id','DESC')
        ->where('pages.city_id',$city->id)
        ->get();

        $page_data['all_printable_categories'] = $page_data['all_categories'];

        //print_r($page_data['all_categories']);exit;

        
        $pageLiked = [];
        // $likepages = Page_like::where('user_id',auth()->user()->id)->get();
        // foreach($likepages as $likepage){
        //     $likepageid = $likepage->page_id;
        //     array_push($pageLiked,$likepageid);
        // }
        if(!is_null($city))
        {
            

            SEOMeta::setTitle('Top deals, offers, discount in '.$city->city_name);
            SEOMeta::setDescription(' Top deals, offers, discount in '.$city->city_name.' best product, services');
            SEOMeta::setKeywords([
                'best deals today in ' . $city->city_name,
                'latest discounts in ' . $city->city_name,
                'exclusive offers in ' . $city->city_name,
                'top deals online ' . $city->city_name,
                'daily deals and discounts in ' . $city->city_name,
                'limited-time offers in ' . $city->city_name,
                'hot deals and promotions ' . $city->city_name,
                'flash sale deals ' . $city->city_name,
                'best savings online ' . $city->city_name,
                'seasonal discount deals ' . $city->city_name
            ]);
            
            SEOMeta::setCanonical(URL::current());

            

           


            //echo  $request->city;exit;

            // $paid_items_query=Marketplace::
            // select('marketplaces.*')
            // ->join('pages','marketplaces.page_id','pages.id')
            // ->join('cities','pages.city_id','cities.id')
            // ->join('category_product','marketplaces.id','category_product.product_id')
            // ->join('categories','categories.id','category_product.product_category_id')
            // ->distinct('marketplaces.id')
            // ->where('marketplaces.product_status',2)
            // ->where('pages.city_id',$city->id);

           $paid_items_query = Marketplace::select('marketplaces.*')
    ->join('pages', 'marketplaces.page_id', '=', 'pages.id')
    ->join('cities', 'pages.city_id', '=', 'cities.id')
    ->join('category_product', 'marketplaces.id', '=', 'category_product.product_id')
    ->join('categories', 'categories.id', '=', 'category_product.product_category_id')
    ->where('marketplaces.product_status', 2)
    ->where('pages.city_id', $city->id);

    // Apply filters
    if ($request->filled('area_filter') && $request->area_filter != '0') {
        $paid_items_query->where('pages.area_id', $request->area_filter);
    }

    if ($request->filled('category_filter') && $request->category_filter != '0') {
        $paid_items_query->where('category_product.product_category_id', $request->category_filter);
    }

    if ($request->filled('subcategory_filter') && $request->subcategory_filter != '0') {
        $paid_items_query->where('category_product.product_category_id', $request->subcategory_filter);
    }

    if ($request->filled('search_filter')) {
        $search = $request->search_filter;
        $paid_items_query->where(function($query) use ($search) {
            $query->where('marketplaces.title', 'like', '%' . $search . '%')
                  ->orWhere('marketplaces.description', 'like', '%' . $search . '%')
                  ->orWhere('categories.product_category_name', 'like', '%' . $search . '%');
        });
    }

    $paid_items_query->orderByDesc('marketplaces.item_featured')  // ✅ Featured on top
    ->orderByDesc('marketplaces.id')
    ->distinct('marketplaces.id');

$filter_sort_by = $request->input('filter_sort_by', 'newest');
$page_data['filter_sort_by'] = $filter_sort_by;

if ($filter_sort_by === 'newest') {
    $paid_items_query->orderBy('marketplaces.created_at', 'DESC');
} elseif ($filter_sort_by === 'oldest') {
    $paid_items_query->orderBy('marketplaces.created_at', 'ASC');
}

$paid_items = $paid_items_query->paginate(50);

$paid_items->appends([
    'filter_sort_by' => $filter_sort_by,
    'area_filter' => $request->area_filter,
    'category_filter' => $request->category_filter,
    'subcategory_filter' => $request->subcategory_filter,
    'search_filter' => $request->search_filter,
]);

$page_data['products'] = $paid_items;

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
        $page_data['all_cities'] = CityHelper::getActiveCities();

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

            // $paid_items_query=Marketplace::
            // select('marketplaces.*')
            // ->join('pages','marketplaces.page_id','pages.id')
            // ->join('cities','pages.city_id','cities.id')
            // ->join('category_product','marketplaces.id','category_product.product_id')
            // ->join('categories','categories.id','category_product.product_category_id')
            // ->distinct('marketplaces.id')
            // ->where('marketplaces.product_status',2)
            // ->where('pages.city_id',$city->id)
            // ->where(function ($query) use ($category) {
            //     $query->where('category_product.product_category_id', $category->id)
            //     ->orWhere('categories.category_parent_id',$category->id);
            // });


            $paid_items_query = Marketplace::select('marketplaces.*')
    ->join('pages', 'marketplaces.page_id', '=', 'pages.id')
    ->join('cities', 'pages.city_id', '=', 'cities.id')
    ->join('category_product', 'marketplaces.id', '=', 'category_product.product_id')
    ->join('categories', 'categories.id', '=', 'category_product.product_category_id')
    ->where('marketplaces.product_status', 2)
    ->where('pages.city_id', $city->id)
    ->where(function ($query) use ($category) {
        $query->where('category_product.product_category_id', $category->id)
              ->orWhere('categories.category_parent_id', $category->id);
    })
    ->orderByDesc('marketplaces.item_featured') // ✅ Featured pehle
    ->orderByDesc('marketplaces.id')
    ->distinct('marketplaces.id');
$filter_sort_by = empty($request->filter_sort_by) ? "newest" : $request->filter_sort_by;
$page_data['filter_sort_by'] = $filter_sort_by;

if ($filter_sort_by === "newest") {
    $paid_items_query->orderBy('marketplaces.created_at', 'DESC');
} elseif ($filter_sort_by === "oldest") {
    $paid_items_query->orderBy('marketplaces.created_at', 'ASC');
}
$paid_items = $paid_items_query->paginate(50);
$paid_items->appends([
    'filter_sort_by' => $filter_sort_by,
    'category_filter' => $request->category_filter,
    'subcategory_filter' => $request->subcategory_filter,
    'search_filter' => $request->search_filter,
]);

$page_data['products'] = $paid_items;


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
        $page_data['all_cities'] = CityHelper::getActiveCities();

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

            // $paid_items_query=Marketplace::
            // select('marketplaces.*')
            // ->join('pages','marketplaces.page_id','pages.id')
            // ->join('cities','pages.city_id','cities.id')
            // ->join('areas','pages.area_id','areas.id')
            // ->join('category_product','marketplaces.id','category_product.product_id')
            // ->join('categories','categories.id','category_product.product_category_id')
            // ->distinct('marketplaces.id')
            // ->where('pages.city_id',$city->id)
            // ->where('pages.area_id',$area->id)
            // ->where('marketplaces.product_status',2)
            // ->where(function ($query) use ($category) {
            //     $query->where('category_product.product_category_id', $category->id)
            //     ->orWhere('categories.category_parent_id',$category->id);
            // });


           $paid_items_query = Marketplace::select('marketplaces.*')
    ->join('pages', 'marketplaces.page_id', '=', 'pages.id')
    ->join('cities', 'pages.city_id', '=', 'cities.id')
    ->join('areas', 'pages.area_id', '=', 'areas.id')
    ->join('category_product', 'marketplaces.id', '=', 'category_product.product_id')
    ->join('categories', 'categories.id', '=', 'category_product.product_category_id')
    ->where('pages.city_id', $city->id)
    ->where('pages.area_id', $area->id)
    ->where('marketplaces.product_status', 2)
    ->where(function ($query) use ($category) {
        $query->where('category_product.product_category_id', $category->id)
              ->orWhere('categories.category_parent_id', $category->id);
    })
    ->orderByDesc('marketplaces.item_featured')  // ✅ Featured items first
    ->orderByDesc('marketplaces.id')
    ->distinct('marketplaces.id');
$filter_sort_by = empty($request->filter_sort_by) ? "newest" : $request->filter_sort_by;
$page_data['filter_sort_by'] = $filter_sort_by;

if ($filter_sort_by === "newest") {
    $paid_items_query->orderBy('marketplaces.created_at', 'DESC');
} elseif ($filter_sort_by === "oldest") {
    $paid_items_query->orderBy('marketplaces.created_at', 'ASC');
}
$paid_items = $paid_items_query->paginate(50);

$paid_items->appends([
    'filter_sort_by' => $filter_sort_by,
]);

$page_data['products'] = $paid_items;


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

    public function searchPages(Request $request)
    {
        $searchTerm = $request->input('q');  // 'q' parameter jo user ne search box me dala ho

        $pages = DB::table('pages')
            ->select('pages.id', 'pages.title')
            ->join('page_category', 'page_category.page_id', 'pages.id')
            ->where('pages.item_status', 2)
            ->where('pages.user_id', auth()->user()->id)
            ->where('pages.title', 'like', '%' . $searchTerm . '%')  // Filtering based on search term
            ->distinct('pages.id')
            ->orderBy('pages.id', 'DESC')
            ->get();

        return response()->json($pages);
    }


    public function create(){
        $page_data['all_cities'] = CityHelper::getActiveCities();

        $page_data['listing']= [];

         SEOMeta::setTitle('Free Local Business Product & Service Listing | CityHangaround');
         SEOMeta::setDescription('Promote your local business by listing products and services for free on CityHangaround. Connect with nearby customers, increase visibility, and grow your presence in your city today!');
         SEOMeta::setCanonical(URL::current());

        

        $page_data['parent'] =  DB::table('categories')
        ->where('categories.category_parent_id',0)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();
        $page_data['view_path'] = 'frontend.marketplace.create_product';
        return view('frontend.index', $page_data);
    }


    public function createCategoryFromSelect2(Request $request)
{
    $duplicateCount = DB::table('categories')
        ->where('product_category_name', $request->category_name)
        ->count();

    if ($duplicateCount === 0) {
        $category = new Category();

        $category->product_category_name = $request->category_name;
        $category->product_category_slug = clean_slug($request->category_name);
        $category->category_parent_id = 0; // Or set dynamically if needed
        $category->product_category_description = "";
        $category->category_createdby = auth()->user()->id;

        $category->save();

         if (auth()->user()){
            app(UserActivityService::class)->log(auth()->user()->id, 'category_suggest', 'market_category', $category->id, $category->id);
         }

        return response()->json([
            'id' => $category->id,
            'product_category_name' => $category->product_category_name
        ]);
    } else {
        // Return existing category if duplicate found (optional fallback)
        $existing = DB::table('categories')
            ->where('product_category_name', $request->category_name)
            ->first();

        return response()->json([
            'id' => $existing->id,
            'product_category_name' => $existing->product_category_name,
            'duplicate' => true
        ]);
    }
}

    public function edit(Request $request){

        $page_data['all_cities'] = CityHelper::getActiveCities();

        $page_data['listing']= [];

        

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
        $category->product_category_slug = clean_slug($request->category_name);
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

         if (auth()->user()){
            app(UserActivityService::class)->log(auth()->user()->id, 'category_suggest', 'market_category', $category->id, $category->id);
         }

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

  public function userproduct() {
    $page_data['all_cities'] = CityHelper::getActiveCities();

    $products_query = Marketplace::with([
            'page.area.city',
            'reviews',              // reviews relation with filter already
            'productCategories',
            'page.categories'
        ])
        ->withAvg(['reviews as avg_rating' => function ($q) {
            $q->where('type', 'product');  // filter for reviews of type product
        }], 'rating')
        ->where('user_id', auth()->user()->id)
        ->where('product_status', 2)
        ->orderBy('id', 'DESC');

    $products = $products_query->get();

    $page_data['products'] = $products;
    $page_data['view_path'] = 'frontend.marketplace.user_products';

    return view('frontend.index', $page_data);
}



    public function getSuggestions(Request $request)
    {
        $query = $request->input('query');
        $products = Marketplace::where('title', 'LIKE', "%$query%")->get(['id', 'title']);
        return response()->json($products);
    }

    public function getDetails(Request $request)
    {
        $product = Marketplace::findOrFail($request->id);
        return response()->json($product);
    }

    public function getCategoryNames(Request $request) {
        if (!$request->has('ids')) {
            return response()->json(['error' => 'No category IDs provided'], 400);
        }
    
        $categoryIds = explode(',', $request->ids); // Convert CSV to array
        $categories = Category::whereIn('id', $categoryIds)->get(['id', 'product_category_name']);
    
        return response()->json($categories);
    }

public function jsonGetProductAreasByCity(int $city_id)
    {
        
       $areas = \App\Models\Area::where('city_id', $city_id)
        ->select("id","area_name")
        ->whereHas('pages.products')
       ->get()->toJson();

    return response()->json($areas);
    }
    public function store(Request $request){
       // echo "123";exit;
        $rules = array(
            'producttype' => 'required|max:255',
            'productnaturetype' => 'required',
            'parent' => 'required',
            'category' => 'required',
            'title' => 'required|max:255',
            'brand' => 'required',
            'List' => 'required',
            
        );
        // $validator = Validator::make($request->all(), $rules);
        // if ($validator->fails()) {
        //     return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
        // }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            return redirect()->back()
            ->withErrors($validator)
            ->withInput(); 
            //return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
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

            $title = 'marketpalce';
            $approval = ManageApproval::where('title', $title)->first();

            if ($approval && $approval->status == 1) {
                // Approval status is ON
                $product_status = 2;

            } elseif (auth()->check() && auth()->user()->user_role == 'admin') {
                // Status is OFF but user is admin
                $product_status = 2;

            } else {
                //Status is OFF and user is not admin
                $product_status = 1;
            }
        
        $marketplace = new Marketplace();
        $marketplace->product_type = $request->producttype;
        $marketplace->product_status = $product_status;
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

    

        $user = auth()->user();
        $activeSubscription = $user->activeSubscription()->with('subscription')->first();

        if ($activeSubscription && $activeSubscription->subscription && Str::contains($activeSubscription->subscription->offered_services, 'marketplace')) {
            $durations = json_decode($activeSubscription->subscription->area_durations, true);

            $cityDays = $durations['marketplace']['city'] ?? 0;
            $areaDays = $durations['marketplace']['area'] ?? 0;

            $subscriptionStart = Carbon::parse($activeSubscription->created_at ?? now());


            $priorityEnd = $subscriptionStart->copy()->addDays(max($cityDays, $areaDays));

            if ($cityDays > 0) $marketplace->priority_until_city = $subscriptionStart->copy()->addDays($cityDays);
            if ($areaDays > 0) $marketplace->priority_until_area = $subscriptionStart->copy()->addDays($areaDays);
            if ($priorityEnd->isFuture()) $marketplace->item_featured = 1;
        }

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


            if (auth()->user()){
                app(UserActivityService::class)->log(auth()->user()->id, 'marketplace_listing', 'product',$product_id,$product_id);
            }
      
            Mail::to($user->email)->queue(new ProductMail($user));
            // Session::flash('success_message', get_phrase('Marketplace Product Added Successfully'));
            // return json_encode(array('reload' => 1));
            return redirect()->route('userproduct');
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

        $title = 'marketpalce';
        $approval = ManageApproval::where('title', $title)->first();

        if ($approval && $approval->status == 1) {
            // Approval status is ON
            $product_status = 2;

        } elseif (auth()->check() && auth()->user()->user_role == 'admin') {
            // Status is OFF but user is admin
            $product_status = 2;

        } else {
            //Status is OFF and user is not admin
            $product_status = $marketplace->product_status;
        }


        $marketplace->product_type = $request->producttype;
        $marketplace->product_status = $product_status;
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



    public function single_product($city_slug,$area_slug,$category_slug,$item_slug,$product_category_slug,$product_slug){

        $page_data['all_cities'] = CityHelper::getActiveCities();

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
            $product = Marketplace::where('product_slug', $product_slug)
        ->where('marketplaces.product_status', 2)
        ->with([
            'page.city',
            'page.area',
            'page.categories',
            'productCategories'
        ])
        ->first(); // direct first() kar le, get() ki zarurat nahi

    // Fallback in case not found
    if (!$product) {
        abort(404, 'Product not found');
    }
    if (auth()->user()){
        app(UserActivityService::class)->log(auth()->user()->id, 'view', 'product', $product->id,$product->id);
     }
    
        $marketplace = $product;
        $categories = $marketplace->productCategories;
        $lastCategory = $categories->last();
        $areaName = optional($marketplace->page->area)->area_name ?? '';
        $cityName = optional($marketplace->page->city)->city_name ?? '';
        $pageCategories = $marketplace->page->categories ?? collect();
        $pageCategoryLast = $pageCategories->last();
        $pageTitle = $marketplace->page->title ?? '';
        $brandName = optional($marketplace->getBrand)->name ?? '';
        $productPrice = $marketplace->price ?? 0;
        $productTitle = $marketplace->title ?? ''; // product hi marketplace hai

        // ✅ META TITLE
        $metaTitle = "$productTitle Deal " .
                    ($lastCategory?->product_category_name ?? '') . " " .
                    trim("$areaName,$cityName") . " " .
                    ($pageCategoryLast?->category_name ?? '') .
                    " - {$pageTitle}";

        SEOMeta::setTitle($metaTitle);

        // ✅ META DESCRIPTION
       // Prepare cityName without parentheses
$cleanCityName = str_replace(['(', ')'], '', $cityName);

// Compose area and city without comma or parentheses, separated by space
$location = trim("$areaName $cleanCityName");

// Construct the meta description string
$metaDescription = "{$productTitle} deal at Rs {$productPrice} " .    // no comma after price
                   ($lastCategory?->product_category_name ?? '') . " " .
                   "{$location} " .
                   "{$pageTitle} - Brand - {$brandName}";


        SEOMeta::setDescription($metaDescription);

        // ✅ Optional Keywords
        SEOMeta::setKeywords([
            'local deals', 
            'discounts', 
            'offers', 
            'best deals near me', 
            'city discounts', 
            'shopping deals', 
            'restaurant deals', 
            'entertainment offers', 
            'local savings'
        ]);



        $products = Marketplace::findOrFail($product->id); // or whatever method you're using

    //print_r($pages);exit;

    if ($products) {
        $product_view_data = $products->view ? json_decode($products->view, true) : [];

        if (auth()->user() && !in_array(auth()->user()->id, $product_view_data)) {
            $product_view_data[] = auth()->user()->id;
            $products->view = json_encode($product_view_data);
        }

        $products->save();
    }
    $page_data['products'] = $products;

    // $user = auth()->user();
    // $user->updateScoreBasedOnActivity('view');

    //print_r( $product[0]->brand );exit;

    $all_reviews = Review::where('marketplace_id', $product->id)
    ->with('user')
    ->where('type', 'product')
    ->latest()
    ->get();

    $page_data['reviews'] = $all_reviews->take(5); // First 5 reviews
    $page_data['has_more_reviews'] = $all_reviews->count() > 5;
    

        if($product){
            $page_data['related_product'] = Marketplace::Where('brand',$product->brand) ->where('marketplaces.product_status',2)->orWhere('category',$product->category)->with([
                'page.city',
                  'page.area',
                'page.categories',
                'productCategories'
            ])->get();


           

            $page_data['product'] = $product;
            $page_data['product_image'] = Media_files::where('product_id',$product->id)->where('file_type','image')->get();
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


        $page_data['all_cities'] = CityHelper::getActiveCities();
        $query = Marketplace::where('status', 1);

        $page_data['all_product_cities']= Marketplace::with('page.city')
        ->get()
        ->pluck('page.city')
        ->filter()                // Remove nulls
        ->unique('id')           // Unique by city ID
        ->values();   ;
        $query = Marketplace::where('status', 1);

        if(isset($search) && !empty($search)){
            $query->where(function ($query) use ($search){
                $query->where('title', 'like', '%'. $search .'%')
                ->orWhere('description', 'like', '%'. $search .'%');
            });
        }


        $filter_city = empty($request->city) ? null : $request->city;
        $filter_area= empty($request->area) ? null : $request->area;
        if($filter_area=="" || is_null($filter_area)){
            $filter_area="0";
        }
        $page_data['filter_city']=$filter_city;
        $page_data['filter_area']=$filter_area;

        $filter_sort_by = empty($request->filter_sort_by) ? "newest" : $request->filter_sort_by;
        $page_data['filter_sort_by']=$filter_sort_by ;

        $page_data['all_printable_categories']=DB::table('categories')->select('categories.*')
        ->join('category_product','category_product.product_category_id','=','categories.id')
        ->join('marketplaces','marketplaces.id','category_product.product_id')
        ->distinct('categories.id')
        ->orderBy('categories.id','DESC')
        ->where('marketplaces.product_status',2)
        ->get();

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




   public function saved_product()
{
    $page_data['all_cities'] = CityHelper::getActiveCities();

    $page_data['saved_products'] = SavedProduct::with([
        'productData.getUser',
        'productData.page.area.city',
        'productData.page.categories',
        'productData.productCategories'
    ])->where('user_id', auth()->id())->get();

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


    public function checkIfSubcategory(Request $request)
{
    $category = Category::find($request->id);

    if (!$category || $category->category_parent_id === null) {
        return response()->json(['is_subcategory' => false]);
    }

    return response()->json([
        'is_subcategory' => true,
        'subcategory_id' => $category->id,
        'subcategory_name' => $category->product_category_name,
        'parent_id' => $category->category_parent_id
    ]);
}

public function autocomplete(Request $request)
{
    $query = $request->q;

    $results = Category::where('product_category_name', 'like', "$query%")
        ->orderBy('product_category_name', 'asc') // optional: alphabetical order
        ->get();

    return response()->json($results);
}



    public function checkIfproductSubcategory(Request $request)
{
    $categoryId = $request->id;

    $category = Category::find($categoryId);

    if (!$category) {
        return response()->json([
            'success' => false,
            'message' => 'Category not found.'
        ], 404);
    }

    // If category has a parent_id (not null and not 0), it's a subcategory
    if (!is_null($category->category_parent_id) && $category->category_parent_id != 0) {
        $parent = Category::find($category->category_parent_id);

        return response()->json([
            'is_subcategory' => true,
            'subcategory_id' => $category->id,
            'subcategory_name' => $category->product_category_name,
            'parent_id' => $parent ? $parent->id : null,
            'parent_name' => $parent ? $parent->product_category_name : null
        ]);
    }

    // It's a parent category
    return response()->json([
        'is_subcategory' => false,
        'category_id' => $category->id,
        'category_name' => $category->product_category_name
    ]);
}

    public function jsonGetSubcategoriesByCategory(Request $request)
    {
        try {
            $categoryId = $request->category_id;
            
            $subcategories = DB::table('categories')
                ->select('categories.*')
                ->join('category_product', 'category_product.product_category_id', '=', 'categories.id')
                ->join('marketplaces', 'marketplaces.id', '=', 'category_product.product_id')
                ->join('pages', 'marketplaces.page_id', '=', 'pages.id')
                ->where('categories.category_parent_id', $categoryId)
                ->where('marketplaces.product_status', 2)
                ->distinct('categories.id')
                ->orderBy('categories.product_category_name', 'asc')
                ->get();

            return response()->json($subcategories);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch subcategories'], 500);
        }
    }



}
