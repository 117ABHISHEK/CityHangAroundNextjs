<?php

namespace App\Http\Controllers;

use App\Models\{Blogcategory,Brand,Category,Pagecategory,Eventcategory,User,Page,Page_like,Blog,FileUploader, Payment_gateway};
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Image, DB, Session, DataTables, Flasher;
use Illuminate\Support\Facades\Validator;
use App\Models\Album_image;
use App\Models\Group;
use App\Models\Group_member;
use App\Models\Marketplace;
use App\Models\Media_files;
class AdminCrudController_old extends Controller
{

    function __construct(){

        //Don't remove it
        session(['admin_login' => 1]);
    }

    // admin change pass 

    public function admin_change_password(){
        $page_data['view_path'] ='profile_view.password';
        return view('backend.index',$page_data);
    }

    // admin profile 

    public function admin_profile(){
        
        $page_data['view_path'] ='profile_view.profile';
        return view('backend.index',$page_data);
    }

    public function admin_profile_update(Request $request){
        $validated = $request->validate([
            'profile_photo' => 'mimes:jpeg,jpg,png,gif|nullable',
        ]);

        
        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->date_of_birth = $request->dateofbirth;
        $user->profession = $request->profession;
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        $user->address = $request->address;
        if ($request->profile_photo && !empty($request->profile_photo)) {


            $file_name = FileUploader::upload($request->profile_photo, 'public/storage/userimage', 800, null, 200, 200);
            //Update to database
            $user->photo = $file_name;
        }

        $user->save();
        flash()->addSuccess('Profile updated successfully!');
        return redirect()->back();
    }



    // dashboard 

    public function admin_dashboard(){
        $page_data['all_category'] = Pagecategory::all();
        $page_data['view_path'] ='dashboard.index';
        return view('backend.index',$page_data);
    }

    // page category 
    public function view_category(){
        $page_data['all_category'] =  DB::table('pagecategories')->select('pagecategories.id','pagecategories.category_name','cat.category_name as parent')
        ->leftjoin('pagecategories as cat','cat.id','=','pagecategories.category_parent_id')->orderby('id', 'asc')
        ->paginate(50);
        $page_data['view_path'] ='page_category.index';
        return view('backend.index',$page_data);
    }

    public function create_category(){
        $page_data['view_path'] ='page_category.create';
        return view('backend.index',$page_data);
    }



    public function save_category(Request $request){
        $validated = $request->validate([
            'pagecategory' => 'required|max:255|string|unique:pagecategories,category_name',
        ]);
        if($request->category==0){

            $category_parent_id=null;
        }
        else{
            $category_parent_id=$request->category;
        }
        $pagecategory = new Pagecategory();
        $pagecategory->category_name = $request->pagecategory;
        $pagecategory->category_slug =str_slug($request->pagecategory);
        $pagecategory->category_parent_id = $category_parent_id;
        $done = $pagecategory->save();
        if($done){
            flash()->addSuccess('Page Category has been added successfully!');
        }
        return redirect()->back();
    }

    public function edit_category($id){
        $page_data['pagecategory'] = Pagecategory::find($id);
        $page_data['view_path'] ='page_category.edit';
        return view('backend.index',$page_data);
    }


    public function  update_category(Request $request,$id){
        $validated = $request->validate([
            'pagecategory' => 'required|max:255|string|unique:pagecategories,category_name,'.$id,
        ]);
        $pagecategory = Pagecategory::find($id);
        if($request->category==0){

            $category_parent_id=null;
        }
        else{
            $category_parent_id=$request->category;
        }
        $pagecategory->category_name = $request->pagecategory;
        $pagecategory->category_slug =str_slug($request->pagecategory);
        $pagecategory->category_parent_id = $category_parent_id;
        $done = $pagecategory->save();
        if($done){
            flash()->addSuccess('Page Category has been updated successfully!');
        }
        return redirect()->route('admin.view.category');
    }

    public function delete_category($id){
        $category = Pagecategory::find($id);
        $category->delete();
        flash()->addSuccess('Page Category has been Deleted successfully!');
        return redirect()->back();
    }








    // event category 
    public function view_event_category(){
        $page_data['all_category'] =  DB::table('eventcategories')->select('eventcategories.id','eventcategories.category_name','cat.category_name as parent')
        ->leftjoin('eventcategories as cat','cat.id','=','eventcategories.category_parent_id')->orderby('id', 'asc')
        ->paginate(50);
        $page_data['view_path'] ='event_category.index';
        return view('backend.index',$page_data);
    }

    public function create_event_category(){
        $page_data['view_path'] ='event_category.create';
        return view('backend.index',$page_data);
    }



    public function save_event_category(Request $request){
        //echo "123";exit;
        $validated = $request->validate([
            'pagecategory' => 'required|max:255|string|unique:eventcategories,category_name',
        ]);
        if($request->category==0){

            $category_parent_id=null;
        }
        else{
            $category_parent_id=$request->category;
        }
        $pagecategory = new Eventcategory();
        $pagecategory->category_name = $request->pagecategory;
        $pagecategory->category_slug =str_slug($request->pagecategory);
        $pagecategory->category_parent_id = $category_parent_id;
        $done = $pagecategory->save();
        if($done){
            flash()->addSuccess('Event Category has been added successfully!');
        }
        return redirect()->back();
    }

    public function edit_event_category($id){
        $page_data['pagecategory'] = Eventcategory::find($id);
        $page_data['view_path'] ='event_category.edit';
        return view('backend.index',$page_data);
    }


    public function  update_event_category(Request $request,$id){
        $validated = $request->validate([
            'pagecategory' => 'required|max:255|string|unique:eventcategories,category_name,'.$id,
        ]);
        $pagecategory = Eventcategory::find($id);
        if($request->category==0){

            $category_parent_id=null;
        }
        else{
            $category_parent_id=$request->category;
        }
        $pagecategory->category_name = $request->pagecategory;
        $pagecategory->category_slug =str_slug($request->pagecategory);
        $pagecategory->category_parent_id = $category_parent_id;
        $done = $pagecategory->save();
        if($done){
            flash()->addSuccess('Event Category has been updated successfully!');
        }
        return redirect()->route('admin.view.event.category');
    }

    public function delete_event_category($id){
        $category = Eventcategory::find($id);
        $category->delete();
        flash()->addSuccess('Event Category has been Deleted successfully!');
        return redirect()->back();
    }



    // product category 
    public function view_product_category(){
        $page_data['all_category'] =  DB::table('categories')->select('categories.id','categories.product_category_name','cat.product_category_name as parent')
        ->leftjoin('categories as cat','cat.id','=','categories.category_parent_id')->orderby('id', 'asc')
        ->paginate(50);
        //$page_data['all_category'] = Category::paginate(50);
        $page_data['view_path'] ='product_category.index';
        return view('backend.index',$page_data);
    }

    public function create_product_category(){
        $page_data['view_path'] ='product_category.create';
        return view('backend.index',$page_data);
    }



    public function save_product_category(Request $request){
        //echo "category";exit;
        $validated = $request->validate([
            'productcategory' => 'required|max:255|string|unique:categories,product_category_name',
        ]);
        $productcategory = new Category();
        $productcategory->product_category_name = $request->productcategory;
        $productcategory->product_category_slug =str_slug($request->productcategory);
        $productcategory->category_parent_id = $request->category;
        $done = $productcategory->save();
        if($done){
            flash()->addSuccess('Product Category has been added successfully!');
        }
        return redirect()->back();
    }

    public function edit_product_category($id){
        $page_data['productcategory'] = Category::find($id);
        $page_data['view_path'] ='product_category.edit';
        return view('backend.index',$page_data);
    }


    public function  update_product_category(Request $request,$id){
        $validated = $request->validate([
            'productcategory' => 'required|max:255|string|unique:categories,product_category_name,'.$id,
        ]);
        $productcategory = Category::find($id);
        $productcategory->product_category_name = $request->productcategory;
        $productcategory->product_category_slug =str_slug($request->productcategory);
        $productcategory->category_parent_id = $request->category;
        $done = $productcategory->save();
        if($done){
            flash()->addSuccess('Product Category has been updated successfully!');
        }
        return redirect()->route('admin.view.product.category');
    }

    public function delete_product_category($id){
        $category = Category::find($id);
        $category->delete();
        flash()->addSuccess('Product Category has been Deleted successfully!');
        return redirect()->back();
    }




    // product brand 
    public function view_brand_category(){
        $page_data['brand'] = Brand::all();
        $page_data['view_path'] ='brand.index';
        return view('backend.index',$page_data);
    }

    public function create_brand_category(){
        $page_data['view_path'] ='brand.create';
        return view('backend.index',$page_data);
    }



    public function save_brand_category(Request $request){
        $validated = $request->validate([
            'brand' => 'required|max:255|string|unique:brands,name',
        ]);
        $brand = new Brand();
        $brand->name = $request->brand;
        $done = $brand->save();
        if($done){
            flash()->addSuccess('Product Brand has been added successfully!');
        }
        return redirect()->back();
    }

    public function edit_brand_category($id){
        $page_data['brand'] = Brand::find($id);
        $page_data['view_path'] ='brand.edit';
        return view('backend.index',$page_data);
    }


    public function  update_brand_category(Request $request,$id){
        $validated = $request->validate([
            'brand' => 'required|max:255|string|unique:brands,name,'.$id,
        ]);
        $brand = Brand::find($id);
        $brand->name = $request->brand;
        $done = $brand->save();
        if($done){
            flash()->addSuccess('Product Brand has been updated successfully!');
        }
        return redirect()->route('admin.view.product.brand');
    }

    public function delete_brand_category($id){
        $brand = Brand::find($id);
        $brand->delete();
        flash()->addSuccess('Product Brand has been Deleted successfully!');
        return redirect()->back();
    }





    // blog category  
    public function view_blog_category(){
        $page_data['all_category'] =  DB::table('blogcategories')->select('blogcategories.id','blogcategories.category_name','cat.category_name as parent')
        ->leftjoin('blogcategories as cat','cat.id','=','blogcategories.category_parent_id')->orderby('id', 'asc')
        ->paginate(50);
        //$page_data['all_category'] = Blogcategory::all();
        $page_data['view_path'] ='blog_category.index';
        return view('backend.index',$page_data);
    }

    public function create_blog_category(){
        $page_data['view_path'] ='blog_category.create';
        return view('backend.index',$page_data);
    }



    public function save_blog_category(Request $request){
        $validated = $request->validate([
            'blogcategory' => 'required|max:255|string|unique:blogcategories,category_name',
        ]);
        if($request->category==0){

            $category_parent_id=null;
        }
        else{
            $category_parent_id=$request->category;
        }
        $blogcategories = new Blogcategory();
        $blogcategories->category_name = $request->blogcategory;
        $blogcategories->category_slug =str_slug($request->blogcategory);
        $blogcategories->category_parent_id = $category_parent_id;
        $done = $blogcategories->save();
        if($done){
            flash()->addSuccess('Blog Category has been added successfully!');
        }
        return redirect()->back();
    }

    public function edit_blog_category($id){
        $page_data['blogcategories'] = Blogcategory::find($id);
        $page_data['view_path'] ='blog_category.edit';
        return view('backend.index',$page_data);
    }


    public function  update_blog_category(Request $request,$id){
        $validated = $request->validate([
            'blogcategory' => 'required|max:255|string|unique:blogcategories,category_name,'.$id,
        ]);
        if($request->category==0){

            $category_parent_id=null;
        }
        else{
            $category_parent_id=$request->category;
        }
        $blogcategories = Blogcategory::find($id);
        $blogcategories->category_name = $request->blogcategory;
        $blogcategories->category_slug =str_slug($request->blogcategory);
        $blogcategories->category_parent_id = $category_parent_id;
        $done = $blogcategories->save();
        if($done){
            flash()->addSuccess('Blog Category has been updated successfully!');
        }
        return redirect()->route('admin.view.blog.category');
    }

    public function delete_blog_category($id){
        $blogcategories = Blogcategory::find($id);
        $blogcategories->delete();
        flash()->addSuccess('Blog Category Brand has been Deleted successfully!');
        return redirect()->back();
    }


    public function about()
    {

        $purchase_code = get_settings('purchase_code');
        $returnable_array = array(
            'purchase_code_status' => get_phrase('Not found'),
            'support_expiry_date'  => get_phrase('Not found'),
            'customer_name'        => get_phrase('Not found')
        );

        $personal_token = "gC0J1ZpY53kRpynNe4g2rWT5s4MW56Zg";
        $url = "https://api.envato.com/v3/market/author/sale?code=" . $purchase_code;
        $curl = curl_init($url);

        //setting the header for the rest of the api
        $bearer   = 'bearer ' . $personal_token;
        $header   = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json; charset=utf-8';
        $header[] = 'Authorization: ' . $bearer;

        $verify_url = 'https://api.envato.com/v1/market/private/user/verify-purchase:' . $purchase_code . '.json';
        $ch_verify = curl_init($verify_url . '?code=' . $purchase_code);

        curl_setopt($ch_verify, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch_verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch_verify, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch_verify, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch_verify, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        $cinit_verify_data = curl_exec($ch_verify);
        curl_close($ch_verify);

        $response = json_decode($cinit_verify_data, true);

        if (is_array($response) && isset($response['verify-purchase']) && count($response['verify-purchase']) > 0) {

            $item_name         = $response['verify-purchase']['item_name'];
            $purchase_time       = $response['verify-purchase']['created_at'];
            $customer         = $response['verify-purchase']['buyer'];
            $licence_type       = $response['verify-purchase']['licence'];
            $support_until      = $response['verify-purchase']['supported_until'];
            $customer         = $response['verify-purchase']['buyer'];

            $purchase_date      = date("d M, Y", strtotime($purchase_time));

            $todays_timestamp     = strtotime(date("d M, Y"));
            $support_expiry_timestamp = strtotime($support_until);

            $support_expiry_date  = date("d M, Y", $support_expiry_timestamp);

            if ($todays_timestamp > $support_expiry_timestamp)
                $support_status    = 'expired';
            else
                $support_status    = 'valid';

            $returnable_array = array(
                'purchase_code_status' => $support_status,
                'support_expiry_date'  => $support_expiry_date,
                'customer_name'        => $customer,
                'product_license'      => 'valid',
                'license_type'         => $licence_type
            );
        } else {
            $returnable_array = array(
                'purchase_code_status' => 'invalid',
                'support_expiry_date'  => 'invalid',
                'customer_name'        => 'invalid',
                'product_license'      => 'invalid',
                'license_type'         => 'invalid'
            );
        }


        $page_data['application_details'] = $returnable_array;
        $page_data['view_path'] ='setting.system_about';
        return view('backend.index',$page_data);
    }


    function curl_request($code = '')
    {

        $purchase_code = $code;

        $personal_token = "FkA9UyDiQT0YiKwYLK3ghyFNRVV9SeUn";
        $url = "https://api.envato.com/v3/market/author/sale?code=" . $purchase_code;
        $curl = curl_init($url);

        //setting the header for the rest of the api
        $bearer   = 'bearer ' . $personal_token;
        $header   = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json; charset=utf-8';
        $header[] = 'Authorization: ' . $bearer;

        $verify_url = 'https://api.envato.com/v1/market/private/user/verify-purchase:' . $purchase_code . '.json';
        $ch_verify = curl_init($verify_url . '?code=' . $purchase_code);

        curl_setopt($ch_verify, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch_verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch_verify, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch_verify, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch_verify, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        $cinit_verify_data = curl_exec($ch_verify);
        curl_close($ch_verify);

        $response = json_decode($cinit_verify_data, true);

        if (is_array($response) && count($response['verify-purchase']) > 0) {
            return true;
        } else {
            return false;
        }
    }


    //Don't remove this code for security reasons
    function save_valid_purchase_code($action_type, Request $request){

        if($action_type == 'update'){
            $data['description'] = $request->purchase_code;

            $status = $this->curl_request($data['description']);
            if($status){  
                DB::table('settings')->where('type', 'purchase_code')->update($data);
                session()->flash('message', get_phrase('Purchase code has been updated'));
                echo 1;
            }else{
                echo 0;
            }
        }else{
            return view('backend.admin.settings.save_purchase_code_form');
        }
        
    }

    function product(){
        if(isset($_GET['delete']) && $_GET['delete'] == 'yes' && isset($_GET['id'])){
            Marketplace::find($_GET['id'])->delete();
            DB::table('category_product')->where('product_id',$_GET['id'])->delete();
            flash()->addSuccess('Product deleted successfully');
            return redirect()->back();
        }

        $page_data['view_path'] ='product.list';
        $page_data['products'] =DB::table('marketplaces')->select('marketplaces.id','marketplaces.title','users.email','users.name','marketplaces.user_id')
        ->join('category_product','category_product.product_id','marketplaces.id')
        ->join('users','users.id','marketplaces.user_id')
        ->distinct()
                                ->paginate(50);
        return view('backend.index', $page_data);

    }

    function product_edit(Request $request){

        $page_data['listing']= DB::table('pages')->select('pages.*')
        ->join('page_category','page_category.page_id','pages.id')
        ->where('pages.item_status',2)
        ->distinct('pages.id')
        ->orderBy('pages.id','DESC')->get();

        $page_data['parent'] =  DB::table('categories')
        ->where('categories.category_parent_id',0)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();
        $page_data['product_id'] =$request->id;
        $page_data['view_path'] = 'product.edit';
        return view('backend.index', $page_data);
    }

    function pages(){
        if(isset($_GET['delete']) && $_GET['delete'] == 'yes' && isset($_GET['id'])){
            Page::find($_GET['id'])->delete();
            DB::table('page_category')->where('page_id',$_GET['id'])->delete();
            flash()->addSuccess('Page deleted successfully');
            return redirect()->back();
        }

        $page_data['view_path'] ='page.list';
        $page_data['pages'] =DB::table('pages')->select('pages.id','pages.item_slug','pages.logo','pages.title','cities.city_slug','areas.area_slug','pagecategories.category_slug'
        ,'cities.city_name','areas.area_name','states.state_name','pagecategories.category_name','users.email','users.name','pages.user_id')
        ->join('cities','cities.id','pages.city_id')
        ->join('areas','areas.id','pages.area_id')
        ->join('states','states.id','pages.state_id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('users','users.id','pages.user_id')
        ->join(DB::raw('(select max(page_category.id) as max,max(page_category.category_id) as category_id,page_id
                    from page_category
                    inner join pagecategories on pagecategories.id =page_category.category_id  group by page_id) t1'), 
                function($join)
                {
                $join->on('t1.page_id', '=', 'pages.id');
                })
        ->join('pagecategories','t1.category_id','=','pagecategories.id')
        ->distinct('pages.id')->paginate(50);
        return view('backend.index', $page_data);
    }

    function product_updated(Request $request,$id){
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

        $product_status=$request->item_status;

        $user_id=DB::table('pages')->select('pages.user_id')
        ->join('page_category','page_category.page_id','pages.id')
        ->where('pages.id',$request->List)
        ->distinct('pages.id')->first();

        $marketplace = Marketplace::find($id);
        $marketplace->product_type = $request->producttype;
        $marketplace->product_status = $product_status;
        $marketplace->product_nature_type = $request->productnaturetype;
        $marketplace->user_id = $user_id->user_id;
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
            flash()->addSuccess('success_message', get_phrase('Marketplace Product Updated Successfully'));
            return redirect(route('admin.product'));
        }
    }

    function product_created(Request $request){
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

        $user_id=DB::table('pages')->select('pages.user_id')
        ->join('page_category','page_category.page_id','pages.id')
        ->where('pages.id',$request->List)
        ->distinct('pages.id')->first();

        $marketplace = new Marketplace();
        $marketplace->product_type = $request->producttype;
        $marketplace->product_status = 2;
        $marketplace->product_nature_type = $request->productnaturetype;
        $marketplace->user_id = $user_id->user_id;
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
             flash()->addSuccess('success_message', get_phrase('Marketplace Product Added Successfully'));
            return redirect(route('admin.product'));
        }

    }

    function product_create(){

        $page_data['listing']= DB::table('pages')->select('pages.*')
        ->join('page_category','page_category.page_id','pages.id')
        ->where('pages.item_status',2)
        ->distinct('pages.id')
        ->orderBy('pages.id','DESC')->get();

        

        $page_data['parent'] =  DB::table('categories')
        ->where('categories.category_parent_id',0)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();
        $page_data['view_path'] = 'product.create';
        return view('backend.index', $page_data);
    }

    function page_create(){
        $page_data['printable_categories'] =  DB::table('pagecategories')->where('category_parent_id',null)
        ->get();
        $page_data['all_states'] = DB::table('states')->select('states.*')
        ->where('country_id' , 101)->get();
        $page_data['parent'] =  DB::table('pagecategories')
        ->where('pagecategories.category_parent_id',null)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();
        //print_r($page_data['parent']);exit;
        $page_data['view_path'] ='page.create';
        return view('backend.index', $page_data);
    }

    function page_edit($id=""){
        $page_data['page_details'] = \App\Models\Page::find($id);
        //print_r($page_data['page_details']);exit;
        $page_data['printable_categories'] =  DB::table('pagecategories')->where('category_parent_id',null)
        ->get();
        $page_data['all_states'] = DB::table('states')->select('states.*')
        ->where('country_id' , 101)->get();
        $page_data['all_cities'] = DB::table('cities')->select('cities.*')
        ->where('state_id' , $page_data['page_details']->state_id)->get();
        $page_data['all_areas'] = DB::table('areas')->select('areas.*')
        ->where('city_id' , $page_data['page_details']->city_id)->get();

        $page_data['parent'] =  DB::table('pagecategories')
        ->where('pagecategories.category_parent_id',null)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();
        $page_data['view_path'] ='page.edit';
        return view('backend.index', $page_data);
    }

    function page_created(Request $request){
        

        if($request->category == 'Select a category'){
            flash()->addError('Please select a category');
            return redirect()->back()->withInput();
        }

        $request->validate([
            'logo' => 'mimes:jpeg,jpg,png,gif|nullable',
            'coverphoto' => 'mimes:jpeg,jpg,png,gif|nullable',
            'title' => 'required|max:255',
            'category' => 'required',
            'state' => 'required|not_in:0',
            'city' => 'required|not_in:0',
            'area' => 'required|not_in:0',
        ]);


        if ($request->logo && !empty($request->logo)) {
            $logo_file_name = FileUploader::upload($request->logo, 'public/storage/pages/logo', 250);
        }else{
            $logo_file_name = null;
        }

        if ($request->coverphoto && !empty($request->coverphoto)) {
            $coverphoto_file_name = FileUploader::upload($request->coverphoto, 'public/storage/pages/coverphoto', 250);
        }else{
            $coverphoto_file_name = null;
        }
        $page_slug=preg_replace("/[^A-Za-z0-9 ]/", '', $request->title);

       
        $multiSelectArray=$request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }
        
        $categories_id=implode(',', $multiSelectArray);
        //print_r($request->item_type);exit;
        $item_type=$request->item_type;
        $item_status=$request->item_status;
        $item_featured=$request->item_featured;
        $item_featured_by_admin=$request->item_featured;


        $page = new Page();
        $page->user_id = auth()->user()->id;
        $page->title = $request->title;
        $page->item_slug = str_slug($page_slug);
        $page->address =$request->address;
        $page->state_id =$request->state;
        $page->city_id =$request->city;
        $page->area_id =$request->area;
        $page->pincode =$request->pincode;
        $page->category_id = $categories_id;

        $page->item_type = $item_type;
        $page->item_status = $item_status;
        $page->item_featured = $item_featured;
        $page->item_featured_by_admin = $item_featured_by_admin;
        $page->item_website = $request->website;
        $page->item_email = $request->business_email;
        $page->item_whatsapp = $request->business_whatsapp_url;
        $page->item_phone = $request->item_phone;
        $page->item_lat = $request->item_lat;
        $page->item_lng = $request->item_lng;
        $page->item_social_facebook =$request->facebook;
        $page->item_social_twitter = $request->twitter;
        $page->item_social_linkedin = $request->linkedIn;
        $page->item_youtube_id = $request->youtube_video_id;
        $page->description = $request->description;
        if ($request->logo && !empty($request->logo)) {
            $page->logo = $logo_file_name;
        }
        if ($request->coverphoto && !empty($request->coverphoto)) {
            $page->coverphoto = $coverphoto_file_name;
        }
        $done = $page->save();
        if($done){
            $pagelike = new Page_like();
            $pagelike->page_id = $page->id;
            $pagelike->user_id = auth()->user()->id;
            $pagelike->role = 'admin';
            $done = $pagelike->save();
            if($done){

                foreach($request->category as $key => $category_id)
            {
            $category_count=DB::table('page_category')->select('page_category.id')
            ->where('category_id',$category_id)
            ->where('page_id',$page->id)
            ->count();
            if($category_count==0){
                $data=array(
                    'category_id'=>$category_id,
                    "page_id"=>$page->id
                );
                $row=DB::table('page_category')->insertGetId($data);
            }
           }
           $slug_count=DB::table('pages')->select('pages.id')
            ->where('pages.item_slug',str_slug($request->title))->count();;
    
            if($slug_count>1){
    
                DB::table('pages')->where('id', $page->id)
                ->update(array('item_slug' =>DB::raw('concat("'.str_slug($request->title).'",'.'-'.$page->id.')')));
            }
                flash()->addSuccess('Page created successfully');
            }
        }

        return redirect(route('admin.page'));
    }

    function page_updated($id = "", Request $request){
        if($request->category == 'Select a category'){
            flash()->addError('Please select a category');
            return redirect()->back()->withInput();
        }

        $request->validate([
            'logo' => 'mimes:jpeg,jpg,png,gif|nullable',
            'coverphoto' => 'mimes:jpeg,jpg,png,gif|nullable',
            'title' => 'required|max:255',
            'category' => 'required',
            'state' => 'required|not_in:0',
            'city' => 'required|not_in:0',
            'area' => 'required|not_in:0',
        ]);

        if ($request->logo && !empty($request->logo)) {
            $logo_file_name = FileUploader::upload($request->logo, 'public/storage/pages/coverphoto', 250);
        }else{
            $logo_file_name = null;
        }

        if ($request->coverphoto && !empty($request->coverphoto)) {
            $coverphoto_file_name = FileUploader::upload($request->coverphoto, 'public/storage/pages/coverphoto', 250);
        }else{
            $coverphoto_file_name = null;
        }
        $page_slug=preg_replace("/[^A-Za-z0-9 ]/", '', $request->title);

       
        $multiSelectArray=$request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }
        
        $categories_id=implode(',', $multiSelectArray);
        //print_r($request->item_type);exit;
        $item_type=$request->item_type;
        $item_status=$request->item_status;
        $item_featured=$request->item_featured;
        $item_featured_by_admin=$request->item_featured;


        $page = Page::find($id);
        // $page->user_id = auth()->user()->id;
        $page->title = $request->title;
        $page->item_slug = str_slug($page_slug);
        $page->address =$request->address;
        $page->state_id =$request->state;
        $page->city_id =$request->city;
        $page->area_id =$request->area;
        $page->pincode =$request->pincode;
        $page->category_id = $categories_id;

        $page->item_type = $item_type;
        $page->item_status = $item_status;
        $page->item_featured = $item_featured;
        $page->item_featured_by_admin = $item_featured_by_admin;
        $page->item_website = $request->website;
        $page->item_email = $request->business_email;
        $page->item_whatsapp = $request->business_whatsapp_url;
        $page->item_phone = $request->item_phone;
        $page->item_lat = $request->item_lat;
        $page->item_lng = $request->item_lng;
        $page->item_social_facebook =$request->facebook;
        $page->item_social_twitter = $request->twitter;
        $page->item_social_linkedin = $request->linkedIn;
        $page->item_youtube_id = $request->youtube_video_id;
        $page->description = $request->description;
        if ($request->logo && !empty($request->logo)) {
            $page->logo = $logo_file_name;
        }
        if ($request->coverphoto && !empty($request->coverphoto)) {
            $page->coverphoto = $coverphoto_file_name;
        }
        $done = $page->save();

        if($done){
            foreach($request->category as $key => $category_id)
            {
            $category_count=DB::table('page_category')->select('page_category.id')
            ->where('category_id',$category_id)
            ->where('page_id',$id)
            ->count();
            if($category_count==0){
                $data=array(
                    'category_id'=>$category_id,
                    "page_id"=>$id
                );
                $row=DB::table('page_category')->insertGetId($data);
            }
        }
        $slug_count=DB::table('pages')->select('pages.id')
            ->where('pages.item_slug',str_slug($request->title))->count();;
    
            if($slug_count>1){
    
                DB::table('pages')->where('id', $id)
                ->update(array('item_slug' =>DB::raw('concat("'.str_slug($request->title).'",'.'-'.$id.')')));
            }
            flash()->addSuccess('Page updated successfully');
        }
        return redirect(route('admin.page'));
    }


    function groups(){
        if(isset($_GET['delete']) && $_GET['delete'] == 'yes' && isset($_GET['id'])){
            Group::find($_GET['id'])->delete();
            DB::table('group_category')->where('group_id',$_GET['id'])->delete();
            Group_member::where('group_id',$_GET['id'])->delete();
            flash()->addSuccess('Group deleted successfully');
            return redirect()->back();
        }

        $page_data['view_path'] ='group.index';

        $page_data['groups'] = DB::table('groups')->select('groups.*','cities.city_slug','areas.area_slug',
        'cities.city_name','areas.area_name','states.state_name','users.name as username','users.id as userid','users.email as useremail' )
        ->join('cities','cities.id','groups.city_id')
        ->join('areas','areas.id','groups.area_id')
        ->join('states','states.id','groups.state_id')
        ->join('group_category','group_category.group_id','groups.id')
        ->join('users','users.id','groups.user_id')
        ->distinct('groups.id')->orderBy('id','DESC')->paginate('10');

        //$page_data['blogs'] = Blog::get();

        return view('backend.index', $page_data);
    }

    function group_create(){
        $page_data['parent'] =  DB::table('pagecategories')
        ->where('pagecategories.category_parent_id',null)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();
       // print_r( $page_data['parent'] );exit;
        $page_data['printable_categories'] =  DB::table('pagecategories')->where('category_parent_id',null)
        ->get();

        $page_data['all_states'] = DB::table('states')->select('states.*')
        ->where('country_id' , 101)->get();
        $page_data['view_path'] ='group.create';
        return view('backend.index', $page_data);
    }

    function group_created(Request $request){

        $rules = array(
            'image' => 'mimes:jpeg,jpg,png,gif|nullable',
            'name' => 'required|max:255',
            'parent' => 'required|not_in:0',
            'category' => 'required',
            'state' => 'required|not_in:0',
            'city' => 'required|not_in:0',
            'area' => 'required|not_in:0',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput(); // Optional: to retain the input data
            //return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
        }
        
        if ($request->image && !empty($request->image)) {
            $file_name = FileUploader::upload($request->image, 'public/storage/groups/logo', 300);
        }

     
            $group_status=2;


        $group_slug=preg_replace("/[^A-Za-z0-9 ]/", '', $request->name);

       
        $multiSelectArray=$request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }
        
        $categories_id=implode(',', $multiSelectArray);

        $group = new Group();
        $group->user_id = auth()->user()->id;
        $group->title = $request->name;
        $group->subtitle = $request->subtitle;
       
        $group->group_status =$group_status;
        $group->group_slug =str_slug($group_slug);
        $group->state_id =$request->state;
        $group->city_id =$request->city;
        $group->area_id =$request->area;
        $group->category_id = $categories_id;

        $group->about = $request->about;
        $group->privacy = $request->privacy;
        $group->status = $request->status;
        if($request->image && !empty($request->image)){
            $group->logo = $file_name;
        }
        $done = $group->save();
        if($done){
            $group_member = new Group_member();
            $group_member->group_id = $group->id;
            $group_member->user_id = auth()->user()->id;
            $group_member->role = 'admin';
            $group_member->is_accepted = '1';
            $done = $group_member->save();


            foreach($request->category as $key => $category_id)
            {
                $data=array(
                    'category_id'=>$category_id,
                    "group_id"=>$group->id
                );
                $row=DB::table('group_category')->insertGetId($data);


            }

            $slug_count=DB::table('groups')->select('groups.id')
            ->where('groups.group_slug',str_slug($request->name))->count();;
    
            if($slug_count>1){
    
                DB::table('groups')->where('id', $group->id)
                ->update(array('group_slug' =>DB::raw('concat("'.str_slug($request->name).'",'.'-'.$group->id.')')));
            }
            if($done){
                Session::flash('success_message', get_phrase('Group Created Successfully'));
                return redirect()->route('admin.group');
                //return json_encode(array('reload' => 1));
            }
        }
    }

    public function group_edit($id=""){
        $page_data['group'] = \App\Models\Group::find($id);
        //print_r($page_data['page_details']);exit;
        $page_data['printable_categories'] =  DB::table('pagecategories')->where('category_parent_id',null)
        ->get();
        $page_data['all_states'] = DB::table('states')->select('states.*')
        ->where('country_id' , 101)->get();
        $page_data['all_cities'] = DB::table('cities')->select('cities.*')
        ->where('state_id' , $page_data['group']->state_id)->get();
        $page_data['all_areas'] = DB::table('areas')->select('areas.*')
        ->where('city_id' , $page_data['group']->city_id)->get();

        $page_data['parent'] =  DB::table('pagecategories')
        ->where('pagecategories.category_parent_id',null)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();
        $page_data['view_path'] ='group.edit';
        return view('backend.index', $page_data);
    }


    public function group_updated(Request $request,$id){
        $rules = array(
            'image' => 'mimes:jpeg,jpg,png,gif|nullable',
            'name' => 'required|max:255',
            'parent' => 'required|not_in:0',
            'category' => 'required',
            'state' => 'required|not_in:0',
            'city' => 'required|not_in:0',
            'area' => 'required|not_in:0',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
           // return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));

           return redirect()->back()
                ->withErrors($validator)
                ->withInput(); // Optional: to retain the input data
        }


        $group_slug=preg_replace("/[^A-Za-z0-9 ]/", '', $request->name);

       
        $multiSelectArray=$request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }
        
        $categories_id=implode(',', $multiSelectArray);

        $group = Group::find($id);
        //previous image name
        $imagename = $group->logo;
        if ($request->image && !empty($request->image)) {
            $file_name = FileUploader::upload($request->image, 'public/storage/groups/logo', 300);
        }

        
        // $group->user_id = auth()->user()->id;
        $group->title = $request->name;
        $group->subtitle = $request->subtitle;

        
        $group->group_slug =str_slug($group_slug);
        $group->state_id =$request->state;
        $group->city_id =$request->city;
        $group->area_id =$request->area;
        $group->category_id = $categories_id;
        $group->group_status=$request->item_status;

        $group->about = $request->about;
        $group->privacy = $request->privacy;
        $group->status = $request->status;
        $group->location = $request->location;
        $group->group_type = $request->group_type;
        if($request->image && !empty($request->image)){
            $group->logo = $file_name;
        }
        $done = $group->save();
        if($done){
            // just put the file name and folder name nothing more :) 
            if(!empty($request->image)){
                if (File::exists(public_path('storage/groups/logo/'.$imagename))) {
                    File::delete(public_path('storage/groups/logo/'.$imagename));
                }
            }

            foreach($request->category as $key => $category_id)
            {
            $category_count=DB::table('group_category')->select('group_category.id')
            ->where('category_id',$category_id)
            ->where('group_id',$id)
            ->count();
            if($category_count==0){
                $data=array(
                    'category_id'=>$category_id,
                    "group_id"=>$id
                );
                $row=DB::table('group_category')->insertGetId($data);
            }


            }
            $slug_count=DB::table('groups')->select('groups.id')
            ->where('groups.group_slug',str_slug($request->name))->count();;
    
            if($slug_count>1){
    
                DB::table('groups')->where('id', $id)
                ->update(array('group_slug' =>DB::raw('concat("'.str_slug($request->name).'",'.'-'.$id.')')));
            }
        }
        Session::flash('success_message', get_phrase('Group Updated Successfully'));
        //return json_encode(array('reload' => 1));
        return redirect()->route('admin.group');
    }


    function blogs(){
        if(isset($_GET['delete']) && $_GET['delete'] == 'yes' && isset($_GET['id'])){
            Blog::find($_GET['id'])->delete();
            DB::table('blog_category')->where('blog_id',$_GET['id'])->delete();
            flash()->addSuccess('Blog deleted successfully');
            return redirect()->back();
        }

        $page_data['view_path'] ='blog.list';

        $page_data['blogs'] = DB::table('blogs')->select('blogs.*','cities.city_slug','areas.area_slug',
        'cities.city_name','areas.area_name','states.state_name','users.name as username','users.id as userid','users.email as useremail' )
        ->join('cities','cities.id','blogs.city_id')
        ->join('areas','areas.id','blogs.area_id')
        ->join('states','states.id','blogs.state_id')
        ->join('blog_category','blog_category.blog_id','blogs.id')
        ->join('users','users.id','blogs.user_id')
        ->distinct('blogs.id')
        ->where('blogs.blog_status',2)->orderBy('id','DESC')->paginate('10');

        //$page_data['blogs'] = Blog::get();

        return view('backend.index', $page_data);
    }

    function blog_create(){
        $page_data['parent'] =  DB::table('blogcategories')
        ->where('blogcategories.category_parent_id',null)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();
        $page_data['printable_categories'] =  DB::table('blogcategories')->where('category_parent_id',null)
        ->get();

        $page_data['all_states'] = DB::table('states')->select('states.*')
        ->where('country_id' , 101)->get();
        $page_data['view_path'] ='blog.create';
        return view('backend.index', $page_data);
    }

    function blog_edit($id=""){
        $page_data['blog_details'] = Blog::find($id);
        //print_r($page_data['blog_details']);exit;
        $page_data['parent'] =  DB::table('pagecategories')
        ->where('pagecategories.category_parent_id',null)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();
        $page_data['printable_categories'] =  DB::table('pagecategories')->where('category_parent_id',null)
        ->get();

        $page_data['all_states'] = DB::table('states')->select('states.*')
        ->where('country_id' , 101)->get();

        $page_data['all_states'] = DB::table('states')->select('states.*')
        ->where('country_id' , 101)->get();
        $page_data['all_cities'] = DB::table('cities')->select('cities.*')
        ->where('state_id' , $page_data['blog_details']->state_id)->get();
        $page_data['all_areas'] = DB::table('areas')->select('areas.*')
        ->where('city_id' , $page_data['blog_details']->city_id)->get();

        $page_data['view_path'] ='blog.edit';
        return view('backend.index', $page_data);
    }

    function blog_created(Request $request){

        if($request->category == 'Select a category'){
            flash()->addError('Please select a category');
            return redirect()->back()->withInput();
        }

        $request->validate([
            'title' => 'required|max:255',
            'category' => 'required',
            'state' => 'required|not_in:0',
            'city' => 'required|not_in:0',
            'area' => 'required|not_in:0',
        ]);

        if ($request->image && !empty($request->image)) {
            $file_name = FileUploader::upload($request->image, 'public/storage/blog/thumbnail', 370);
            FileUploader::upload($request->image, 'public/storage/blog/coverphoto/'.$file_name, 900);
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

        $data['user_id'] = Auth()->user()->id;
        $data['title'] = $request->title;
        $data['blog_slug'] = str_slug($blog_slug);
        $data['category_id'] = $categories_id;
        $data['state_id'] = $request->state;
        $data['city_id'] = $request->city;
        $data['area_id'] = $request->area;
        $data['blog_status'] = $request->item_status;

        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['updated_at'] = date('Y-m-d H:i:s', time());
        $tags =  json_decode($request->tag,true);
        $tag_array = array();
        if(is_array($tags)){
            foreach($tags as $key => $tag){
                $tag_array[$key]=$tag['value'];
            }
        }
        $data['tag'] = json_encode($tag_array);
        $data['description'] = $request->description;
        if($request->image && !empty($request->image)){
            $data['thumbnail'] = $file_name;
        }
        $data['view'] = json_encode(array());
        $id = DB::table('blogs')->insertGetId($data);
        // DB::Table('blogs')->insert($data);
        foreach($request->category as $key => $category_id)
            {
                $data=array(
                    'category_id'=>$category_id,
                    "blog_id"=>$id
                );
                $row=DB::table('blog_category')->insertGetId($data);


            }

            $slug_count=DB::table('blogs')->select('blogs.id')
            ->where('blogs.blog_slug',str_slug($request->title))->count();;
    
            if($slug_count>1){
    
                DB::table('blogs')->where('id', $id)
                ->update(array('blog_slug' =>DB::raw('concat("'.str_slug($request->title).'",'.'-'.$id.')')));
            }
        flash()->addSuccess('Blog created successfully');
        return redirect()->route('admin.blog');
    }

    function blog_updated(Request $request,$id){

        if($request->category == 'Select a category'){
            flash()->addError('Please select a category');
            return redirect()->back()->withInput();
        }
        
        $request->validate([
            'title' => 'required|max:255',
            'category' => 'required',
            'state' => 'required|not_in:0',
            'city' => 'required|not_in:0',
            'area' => 'required|not_in:0',
        ]);

        if ($request->image && !empty($request->image)) {

            $file_name = FileUploader::upload($request->image, 'public/storage/blog/thumbnail', 370);
            FileUploader::upload($request->image, 'public/storage/blog/coverphoto/'.$file_name, 900);
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

        //$blog->user_id = Auth::user()->id;
        // store image name for delete file operation 
        $imagename = $blog->thumbnail;

        //$blog->user_id = Auth::user()->id;
        $blog->title = $request->title;
        $blog->blog_slug =str_slug($blog_slug);
        $blog->category_id = $categories_id;
        $blog->state_id =$request->state;
        $blog->city_id =$request->city;
        $blog->area_id =$request->area;
        $blog->blog_status = $request->item_status;
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
            // just put the file name and folder name nothing more :) 
            if(!empty($request->image)){
                removeFile('blog', $imagename);
            }
            flash()->addSuccess('Blog updated successfully');
            return redirect()->route('admin.blog');
        }
    }


    function users(){
        $users = User::where('user_role', '!=', 'admin')->get();

        $page_data['users'] = $users;
        $page_data['view_path'] ='users.list';
        return view('backend.index', $page_data);
    }

    function user_add(){
        $page_data['view_path'] ='users.add';
        return view('backend.index', $page_data);
    }

    function user_store(Request $request){
        //password validation
        //  $request->validate([
        //     'current_password' => ['required', new MatchOldPassword],
        //     'new_password' => ['required'],
        //     'new_confirm_password' => ['same:new_password'],
        // ]);

        $this->validate($request, [
            'email' => ['required', 'email', Rule::unique('users')],
            'name' => 'required','max:255',
            'gender' => 'required',
            'date_of_birth' => 'required',
        ]);

        if ($request->photo && !empty($request->photo)) {
            $file_name = FileUploader::upload($request->photo, 'public/storage/userimage', 800);

            //Update to database
            $data['photo'] = $file_name;
        }

        $data['user_role'] = 'general';
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['phone'] = $request->phone;
        $data['address'] = $request->address;
        $data['date_of_birth'] = strtotime($request->date_of_birth);
        $data['about'] = $request->bio;
        $data['friends'] = '[]';
        $data['followers'] = '[]';
        $data['gender'] = $request->gender;
        $data['status'] = 1;
        $date['created_at'] = now();

        $user_insert = User::create($data);
        $user_insert->markEmailAsVerified();
        flash()->addSuccess('User added successfully');
        return redirect()->route('admin.users');
    }

    function user_edit($id = ""){
        $page_data['user_data'] = User::find($id);
        $page_data['view_path'] ='users.edit';
        return view('backend.index', $page_data);
    }

    function user_update($id = "", Request $request){
        //password validation
        //  $request->validate([
        //     'current_password' => ['required', new MatchOldPassword],
        //     'new_password' => ['required'],
        //     'new_confirm_password' => ['same:new_password'],
        // ]);

        $this->validate($request, [
            'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
            'name' => 'required','max:255',
            'gender' => 'required',
            'date_of_birth' => 'required',
        ]);

        if ($request->photo && !empty($request->photo)) {
            $file_name = FileUploader::upload($request->photo, 'public/storage/userimage', 800);

            $previous_image = public_path().'/storage/userimage/optimized/' . User::where('id', $id)->value('photo');
            $previous_image2 = public_path().'/storage/userimage/' . User::where('id', $id)->value('photo');
            if(file_exists($previous_image) && is_file($previous_image)){
                unlink($previous_image);
                unlink($previous_image2);
            }

            //Update to database
            $data['photo'] = $file_name;
        }

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['address'] = $request->address;
        $data['date_of_birth'] = strtotime($request->date_of_birth);
        $data['about'] = $request->bio;
        $data['gender'] = $request->gender;
        $date['updated_at'] = now();

        User::where('id', $id)->update($data);
        flash()->addSuccess('User updated successfully');
        return redirect()->route('admin.users');
    }

    function user_delete($user_id = ""){
        User::find($user_id)->delete();
        flash()->addSuccess('User deleted successfully');
        return redirect()->route('admin.users');
    }

    function user_status($user_id = ""){
        $query = User::where('id', $user_id);
        if($query->value('status') == 1){
            $query->update(['status' => 0]);
        }else{
            $query->update(['status' => 1]);
        }
        flash()->addSuccess('User deleted successfully');
        return redirect()->route('admin.users');
    }

    // public function server_side_users_data(Request $request)
    // {

    //     if ($request->ajax()) {
    //         $data = User::where('user_role', '!=', 'admin')->limit(5000)->get();
    //         return Datatables::of($data)->addIndexColumn()
    //             ->addColumn('action', function($row){
    //                 $btn = '<a href="javascript:void(0)" class="btn btn-primary btn-sm">View</a>';
    //                 return $btn;
    //             })
    //             ->rawColumns(['action'])
    //             ->make(true);
    //     }
    // }


    function server_side_users_data(Request $request){
        // echo $total_number_of_row = User::where('user_role', '!=', 'admin')->count();
        // $users = User::skip(12)->take(12)->select('name', 'id', 'email', 'photo', 'status')->where('user_role', '!=', 'admin')->orderBy('id', 'asc')->get();
        // print_r($users);
        // die;

        $data = array();
        //mentioned all with colum of database table that related with number of html table
        $columns = array('id','id','name','email','status', 'id');

        $limit = $request->length;
        $start = $request->start;

        $column_index = $columns[$request->order[0]['column']];

        $dir = $request->order[0]['dir'];
        $total_number_of_row = User::where('user_role', '!=', 'admin')->count();

        $filtered_number_of_row = $total_number_of_row;
        $search = $request->search['value'];

        if(empty($search)) {
            $users = User::skip($start)->take($limit)->select('name', 'id', 'email', 'photo', 'status')->where('user_role', '!=', 'admin')->orderBy($column_index, $dir)->get();
        }else{
            $users = User::where(function ($query) use($search) {
                $query->where('name','like','%'.$search.'%')
                    ->orWhere('email','like','%'.$search.'%');
            })
            ->where('user_role', '!=', 'admin');
            $filtered_number_of_row = $users->count();
            $users = $users->skip($start)->take($limit)->orderBy($column_index, $dir)->get();
        }

        foreach($users as $key => $user):

            //photo
            $photo = '<img src="'.User::get_user_image($user['photo']).'" alt="" height="50" width="50" class="img-fluid rounded-circle img-thumbnail">';

            //user name
            if($user['email_verified_at'] == null){ $status = '<small><br><span class="badge bg-danger">'.get_phrase('Unverified').'</span></small>';}else{$status = '';}
            $name = $user['name'].$status;

            //user email
            $email = $user['email'];


            //Status
            if($user['status'] != 1){
                $status = '<span class="badge bg-danger">'.get_phrase('Disabled').'</span>';
            }else{
                $status = '<span class="badge bg-success">'.get_phrase('Active').'</span>';
            }

            if($user['status'] != 1){
                $status_btn = '<a class="dropdown-item" onclick="return confirm(&#39;'.get_phrase('Are You Sure').' ?&#39;)" href="'.route('admin.user.status', $user['id']).'">'.get_phrase('Active').'</a>';
            }else{
                $status_btn = '<a class="dropdown-item" onclick="return confirm(&#39;'.get_phrase('Are You Sure').' ?&#39;)" href="'.route('admin.user.status', $user['id']).'">'.get_phrase('Deactive').'</a>';
            }


            $action = '<div class="adminTable-action me-auto">
                        <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2" data-bs-toggle="dropdown" aria-expanded="false">
                          '.get_phrase("Actions").'
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                          <li><a class="dropdown-item" href="'.route('admin.user.edit', $user['id']).'">'.get_phrase('Edit').'</a>
                          </li>
                          <li>'.$status_btn.'</li>
                          <li>
                            <a class="dropdown-item" onclick="return confirm(&#39;'.get_phrase('Are You Sure Want To Delete').' ?&#39;)" href="'.route('admin.user.delete', $user['id']).'">'.get_phrase('Delete').'</a>
                          </li>
                        </ul>
                    </div>';


            $nestedData['key'] = ++$key;
            $nestedData['photo'] = $photo;
            $nestedData['name'] = $name;
            $nestedData['email'] = $email;
            $nestedData['status'] = $status;
            $nestedData['action'] = $action.'<script>$("a, i").tooltip();</script>';
            $data[] = $nestedData;
        endforeach;

        $json_data = array(
            "draw"            => intval($request->draw),
            "recordsTotal"    => intval($total_number_of_row),  
            "recordsFiltered" => intval($filtered_number_of_row), 
            "data"            => $data   
        );
        echo json_encode($json_data);
    }

    function payment_settings(){
        $page_data['payment_gateways'] = Payment_gateway::get();
        $page_data['view_path'] = 'payment.payment_gateways';
        return view('backend.index', $page_data);
    }

    function payment_gateway_edit($id){
        $page_data['currencies'] = DB::table('currencies')->get();
        $page_data['payment_gateway'] = Payment_gateway::where('id', $id)->first();
        $page_data['view_path'] = 'payment.payment_gateway_edit';
        return view('backend.index', $page_data);
    }

    function payment_gateway_update($id, Request $request){
        $keys = array();
        $all_data = $request->all();
        $data['currency'] = $request->currency;

        unset($all_data['_token']);
        unset($all_data['currency']);
        $data['keys'] = json_encode($all_data);
        Payment_gateway::find($id)->update($data);
        flash()->addSuccess('Payment gateway has been updated');
        return redirect()->route('admin.settings.payment');
    }

    function payment_gateway_status($id){
        $query = Payment_gateway::where('id', $id);
        if($query->value('status') == 1){
            $query->update(['status' => 0]);
        }else{
            $query->update(['status' => 1]);
        }
        flash()->addSuccess('Payment gateway status changed successfully');
        return redirect()->route('admin.settings.payment');
    }

    function payment_gateway_environment($id){
        $query = Payment_gateway::where('id', $id);
        if($query->value('test_mode') == 1){
            $query->update(['test_mode' => 0]);
        }else{
            $query->update(['test_mode' => 1]);
        }
        flash()->addSuccess('Payment gateway environment changed successfully');
        return redirect()->back();
    }











}
