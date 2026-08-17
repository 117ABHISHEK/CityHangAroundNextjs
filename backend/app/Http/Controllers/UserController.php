<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Sponsor, FileUploader,Ticket,BuyerLeadStage,Page,Page_like,IncompleteListing,ManageApproval};
use DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
class UserController extends Controller
{

    function page_create(){
        $page_data['printable_categories'] =  DB::table('pagecategories')->where('category_parent_id',null)
        ->get();
        $page_data['all_states'] = DB::table('states')->select('states.*')
        ->where('country_id' , 101)->get();
        $page_data['parent'] =  DB::table('pagecategories')
        ->where('pagecategories.category_parent_id',null)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();
        $page_data['countries'] = DB::table('countries')->select('countries.*')
        ->where('id' , 101)->get();
        //print_r($page_data['parent']);exit;
        $page_data['view_path'] = 'frontend.pages.create_page';
    return view('frontend.form_index', $page_data);
    }


    function page_created(Request $request){
        

        if($request->category == 'Select a category'){
            flash()->addError('Please select a category');
            return redirect()->back()->withInput();
        }

        $request->validate([
            'logo' => 'mimes:jpeg,jpg,png,gif|nullable',
            'coverphoto' => 'mimes:jpeg,jpg,png,gif|nullable',
            'item_type'=> 'required',
            'title' => 'required|max:255',
            'parent' => 'required',
            'category' => 'required',
            'item_phone' => ['nullable', 'regex:/^(\+?\d{1,3}[-. ]?)?\d{10}$/'],
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

        if ($request->Proof_of_Ownership && !empty($request->Proof_of_Ownership)) {

            $proof_of_ownership_file_name = FileUploader::upload($request->image,'public/storage/pages/logo');
           
        }
        $page_slug=preg_replace("/[^A-Za-z0-9 ]/", '', $request->title);

       
        $multiSelectArray=$request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }


        if($request->servicestate){
            $service_state_ids=implode(',', $request->servicestate);
        }
        else{
            $service_state_ids="";
        }

        if($request->servicecity){
            $service_city_ids=implode(',', $request->servicecity);
        }
        else{
            $service_city_ids="";
        }

        if($request->servicecategory){
            $product_categories_ids=implode(',', $request->servicecategory);
        }
        else{
            $product_categories_ids="";
        }

        if($request->servicearea){
            $service_offeres_areas_ids=implode(',', $request->servicearea);
        } 
        else{
            $service_offeres_areas_ids="";
        }


            $title = 'listing';
            $approval = ManageApproval::where('title', $title)->first();

            if ($approval && $approval->status == 1) {
                // Approval status is ON
                $item_status = 2;

            } else {
                //Status is OFF and user is not admin
                $item_status = 1;
            }
        
        $categories_id=implode(',', $multiSelectArray);
        //print_r($request->item_type);exit;
        $item_type=$request->item_type;
        $item_status=$item_status;
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


        $page->product_categories_ids = $product_categories_ids;
        
        $page->why_visit_us = $request->visitus;
        $page->our_story = $request->our_story;
        $page->year_of_establishment = $request->yrofest;
        $page->service_offeres_areas_ids = $service_offeres_areas_ids;



        $page->country_id = $request->country;
        $page->open_hours = $request->open_hours;
        $page->service_offered_state = $service_state_ids;
        $page->service_offered_city = $service_city_ids;

        $page->policy = $request->policy;

        if($request->Proof_of_Ownership && !empty($request->Proof_of_Ownership)){
            $page->ownership_document = $proof_of_ownership_file_name;
        }
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

                foreach ($multiSelectArray as $category_id) 
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
           $slug_count=DB::table('pages')->select('pages.id')
            ->where('pages.item_slug',str_slug($request->title))->count();;
    
            if($slug_count>1){
    
                DB::table('pages')->where('id', $page->id)
                ->update(array('item_slug' =>DB::raw('concat("'.str_slug($request->title).'",'.'-'.$page->id.')')));
            }
                flash()->addSuccess('Page created successfully');
            }
        }

        return redirect(route('user.pages'));
    }

    public function storeBuyerLeadStage(Request $request) {
        $request->validate([
            'enquiry_id' => 'required|exists:enquirymaster,id',
            'user_id' => 'required|exists:users,id',
            'lead_stage_id' => 'required|exists:enquiry_lead_stages,id',
            'comment' => 'nullable|string',
        ]);
    
        BuyerLeadStage::updateOrCreate(
            ['enquiry_id' => $request->enquiry_id, 'user_id' => $request->user_id],
            ['lead_stage_id' => $request->lead_stage_id, 'comment' => $request->comment]
        );
    
        return response()->json(['success' => true, 'message' => 'Lead stage updated successfully']);
    }

    public function view_product_enquiry(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $cityId = $request->input('city');
    $categoryId = $request->input('category');
    $userId = auth()->user()->id; // Get logged-in user ID

    // Base query for enquiries
    $query = DB::table('enquirymaster')
    ->select(
        'enquirymaster.*',
        'cities.city_name',
        'marketplaces.title',
        'marketplaces.page_id',
        'marketplaces.product_slug',
        'marketplaces.id as product_id',
        'buyer_lead_stages.lead_stage_id',
        'buyer_lead_stages.comment',
        'enquiry_lead_stages.stage_name'
    )
    ->join('cities', 'cities.id', '=', 'enquirymaster.cityid')
    ->join('marketplaces', 'marketplaces.id', '=', 'enquirymaster.productid')
    ->leftJoin('buyer_lead_stages', function ($join) {
        $join->on('buyer_lead_stages.enquiry_id', '=', 'enquirymaster.id')
             ->orderBy('buyer_lead_stages.created_at', 'desc')
             ->limit(1);
    })
    ->leftJoin('enquiry_lead_stages', 'enquiry_lead_stages.id', '=', 'buyer_lead_stages.lead_stage_id')
    ->where('enquirymaster.userid', $userId);

    // Apply filters dynamically
    if ($startDate) {
        $query->whereDate('enquirymaster.createdAt', '>=', $startDate);
    }
    if ($endDate) {
        $query->whereDate('enquirymaster.createdAt', '<=', $endDate);
    }
    if ($cityId) {
        $query->where('enquirymaster.cityid', $cityId);
    }
    if ($categoryId) {
        $query->whereRaw("? = ANY(string_to_array(marketplaces.category, ',')::bigint[])", [$categoryId]);
    }

    // Fetch filtered enquiries
    $page_data['enquiries'] = $query->orderBy('enquirymaster.id', 'desc')->paginate(50);

    // Fetch only cities that have enquiries from this user
    $page_data['cities'] = DB::table('cities')
        ->whereIn('id', function ($query) use ($userId) {
            $query->select('cityid')->from('enquirymaster')->where('userid', $userId);
        })
        ->get();

    // Fetch areas based on selected city (only if a city is selected)
    $page_data['areas'] = [];
    if ($cityId) {
        $page_data['areas'] = DB::table('areas')
            ->where('city_id', $cityId)
            ->get();
    }

    // Fetch categories that have been used in this user's enquiries
    $page_data['categories'] = Category::whereExists(function ($query) {
        $query->select(DB::raw(1))
              ->from('marketplaces')
              ->whereRaw("categories.id = ANY(string_to_array(marketplaces.category, ',')::bigint[])");
    })->select('id', 'product_category_name')->get();

     // Fetch only lead stages meant for buyers
$page_data['leadStages'] = DB::table('enquiry_lead_stages')
->where('for_role', 'buyer') // Assuming there’s a 'type' column to differentiate buyer/seller stages
->select('id', 'stage_name')
->get();



    $page_data['view_path'] = 'enquiry';

    return view('backend.index', $page_data);
}




     function view_pages(){
        //echo "123";exit;

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
        ->where('pages.user_id', auth()->user()->id)
        ->distinct('pages.id')->orderBy('pages.id', 'DESC')->paginate(50);
        $page_data['view_path'] ='pages';
        return view('backend.index',$page_data);
    }


    public function showIncompleteListings()
    {
       
        $page_data['incompleteListings'] =IncompleteListing::with('user')->latest()->where('user_id',auth()->user()->id)->paginate(10);
        $page_data['view_path'] = 'page.draft.index';
        return view('backend.index', $page_data);

        //return view('listings.incomplete', compact('incompleteListings'));
    }

    function view_products(){
        $page_data['products'] = DB::table('marketplaces')
        ->select('marketplaces.id','marketplaces.title','marketplaces.product_slug','users.email','users.name','marketplaces.user_id',
                 'cities.city_slug','areas.area_slug','pagecategories.category_slug as page_category_slug','pages.item_slug',
                 'categories.product_category_slug')
        ->join('users','users.id','marketplaces.user_id')
        ->join('pages','pages.id','marketplaces.page_id')
        ->join('cities','cities.id','pages.city_id')
        ->join('areas','areas.id','pages.area_id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','pagecategories.id','page_category.category_id')
        ->join(DB::raw('(select max(page_category.id) as max, page_id from page_category group by page_id) t1'), 't1.page_id', '=', 'pages.id')
        ->join('category_product','category_product.product_id','marketplaces.id')
        ->join('categories','categories.id','category_product.product_category_id')
        ->where('marketplaces.user_id', auth()->user()->id)
        ->distinct('marketplaces.id')->orderBy('marketplaces.id', 'DESC')
        ->paginate(50);
        $page_data['view_path'] ='products';
        return view('backend.index',$page_data);

    }

    function view_events(){

        $page_data['events'] =  DB::table('events')
        ->select('events.*','users.id as userid','users.name','users.email','cities.city_slug','areas.area_slug','eventcategories.category_slug')
        ->join('users','users.id','=','events.user_id')
        ->leftJoin('cities','cities.id','=','events.city_id')
        ->leftJoin('areas','areas.id','=','events.area_id')
        ->leftJoin('event_category','events.id','=','event_category.event_id')
        ->leftJoin('eventcategories','eventcategories.id','=','event_category.category_id')
        ->where('events.user_id', auth()->user()->id)
        ->orderby('events.id', 'asc')
        ->distinct()
        ->paginate(50);
        $page_data['view_path'] ='event';
        return view('backend.index',$page_data);

    }

    function dashboard(){
        $page_data['view_path'] ='dashboard';
        return view('backend.index',$page_data);
    }

    function ads(){
        $page_data['ads'] = Sponsor::where('user_id', auth()->user()->id)->get();
        $page_data['view_path'] = 'ads';
        return view('backend.index', $page_data);
    }
    function ad_create(){
        $page_data['view_path'] = 'ad_create';
        return view('backend.index', $page_data);
    }
    function ad_store(Request $request){
        $validated = $request->validate([
            'name' => 'required|max:255|string',
            'image' => ['required', 'mimes:jpg,jpeg,png'],
        ]);

        $data['status'] = 1;
        $data['user_id'] = auth()->user()->id;
        $data['name'] = $request->name;
        $data['description'] = $request->description;
        $data['ext_url'] = $request->ext_url;
        $data['image'] = random(40).'.'.$request->image->extension();
        Sponsor::insert($data);
        FileUploader::upload($request->image,'public/storage/sponsor/thumbnail/'.$data['image'], 300);

        flash()->addSuccess('New ad created successfully');
        return redirect(route('user.ads'));
    }
    function ad_edit($id){
        $page_data['ad'] = Sponsor::where('id', $id)->where('user_id', auth()->user()->id)->first();
        $page_data['view_path'] = 'ad_edit';
        return view('backend.index', $page_data);
    }

    function ad_update($id, Request $request){
        $validated = $request->validate([
            'name' => 'required|max:255|string',
        ]);
        $query = Sponsor::where('id', $id)->where('user_id', auth()->user()->id);
        $data['name'] = $request->name;
        $data['description'] = $request->description;
        $data['ext_url'] = $request->ext_url;

        if($request->image){
            $data['image'] = random(40).'.'.$request->image->extension();
            remove_file('public/storage/sponsor/thumbnail/'.$query->first()->image);
        }

        $query->update($data);

        if($request->image){
            FileUploader::upload($request->image,'public/storage/sponsor/thumbnail/'.$data['image'], 300);
        }

        flash()->addSuccess('Ad updated successfully');
        return redirect(route('user.ads'));
    }

    // function ad_status($id){
    //     $query = Sponsor::where('id', $id)->where('user_id', auth()->user()->id);

    //     if($query->first()->status == 1){
    //         $query->update(['status' => 0]);
    //         flash()->addSuccess('Ad has been deactivated');
    //     }else{
    //         $query->update(['status' => 1]);
    //         flash()->addSuccess('Ad activated successfully');
    //     }

    //     return redirect()->back();
    // }

    function ad_delete($id){
        $query = Sponsor::where('id', $id)->where('user_id', auth()->user()->id);

        remove_file('public/storage/sponsor/thumbnail/'.$query->first()->image);

        $query->delete();
        flash()->addSuccess('Ad deleted successfully');
        return redirect()->back();
    }

    function ad_activation($id, Request $request){
        $page_data['ad'] = Sponsor::where('id', $id)->where('user_id', auth()->user()->id)->first();
        $page_data['view_path'] = 'ad_edit';
        return view('backend.index', $page_data);
    }

    function ad_charge_by_daterange(Request $request){
        $total_days = \Carbon\Carbon::parse($request->start_date)->diffInDays($request->end_date);

        if(strtotime($request->start_date) < strtotime($request->end_date)){
            return ($total_days*get_settings('ad_charge_per_day'));
        }else{
            return 0;
        }
    }

    function payment_configuration($id, Request $request){
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $total_days = \Carbon\Carbon::parse($request->start_date)->diffInDays($request->end_date);
        $payable_amount = ($total_days*get_settings('ad_charge_per_day'))+get_settings('ad_charge_per_day');
        $start_timestamp = strtotime($request->start_date.' '.date('H:i:s'));
        $end_timestamp = strtotime($request->end_date.' '.date('H:i:s'));

        $payment_details = [
            'items' =>[
                [
                    'id' => $id,
                    'title' => get_phrase('Ad Activation for ____ days', [$total_days]),
                    'subtitle' => get_phrase('Your ad will be published on ____', [$request->start_date]),
                    'price' => $payable_amount,
                    'discount_price' => $payable_amount,
                    'discount_percentage' => 0
                ]
            ],
            'custom_field' => [
                'start_date' => date('Y-m-d H:i:s', $start_timestamp),
                'end_date' => date('Y-m-d H:i:s', $end_timestamp),
            ],
            'success_method' => [
                'model_name' => 'Sponsor',
                'function_name' => 'add_payment_success'
            ],
            'tax' => 0,
            'coupon' => null,
            'payable_amount' => $payable_amount,
            'cancel_url' => route('user.ads'),
            'success_url' => route('payment.success', ''),
        ];
        session(['payment_details' => $payment_details]);
        return redirect()->route('payment');
    }

    





    // Customer: View all tickets
    public function ticket_index(Request $request)
{
    $userId = auth()->user()->id;
    $query = Ticket::where('user_id', $userId);

    // Apply Filters
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    if ($request->filled('start_date')) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }

    // Fetch tickets with comments, their users, and admins
    $page_data['tickets'] = $query->with(['comments.user', 'comments.admin'])->paginate(20);
    $page_data['view_path'] = 'ticket.index';

    return view('backend.index', $page_data);
}



    // Customer: Show form to create a new ticket
    public function ticket_create()
    {

        $page_data['view_path'] ='ticket.create_ticket';
        return view('backend.index',$page_data);
    }

    // Customer: Store a new ticket
    public function ticket_store(Request $request)
    {

        //dd($request->all(), $request->file('screenshot'));
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|string|in:Low,Medium,High',
            'status' => 'required|string|in:Open,In Progress,Closed',
            'screenshot' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Allow only images up to 2MB
        ]);

        // Handle file upload
        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('screenshots', 'public');
        }

        

        Ticket::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
            'screenshot' => $screenshotPath, // Save file path in DB
        ]);

        return redirect()->route('user.tickets')->with('success', 'Ticket created successfully');
    }


    // Customer: View a single ticket
    public function ticket_show(Ticket $ticket)
    {
        //dd(auth()->user());
        if ($ticket->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized access.');
        }

        $page_data['ticket'] =$ticket;
        $page_data['view_path'] ='ticket.show';
        return view('backend.index',$page_data);
    }


    public function ticket_edit(Ticket $ticket)
    {
        // Ensure only the owner of the ticket can edit it
        if ($ticket->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized access.');
        }
        $page_data['ticket'] =$ticket;
        $page_data['view_path'] ='ticket.edit';
        return view('backend.index',$page_data);
    }

public function ticket_update(Request $request, Ticket $ticket)
{
    // Ensure only the owner can update the ticket
    if ($ticket->user_id !== auth()->user()->id) {
        abort(403, 'Unauthorized access.');
    }

    $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'required|string',
        'priority'    => 'required|in:Low,Medium,High',
        'status'      => 'required|in:Open,In Progress,Closed',
        'screenshot'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Handle file upload
    if ($request->hasFile('screenshot')) {
        // Delete old screenshot if exists
        if ($ticket->screenshot) {
            Storage::delete($ticket->screenshot);
        }

        // Store new screenshot
        $filePath = $request->file('screenshot')->store('ticket_screenshots', 'public');
        $ticket->screenshot = $filePath;
    }

    // Update ticket details
    $ticket->update([
        'title'       => $request->title,
        'description' => $request->description,
        'priority'    => $request->priority,
        'status'      => $request->status,
        'screenshot'  => $ticket->screenshot ?? null,
    ]);

    return redirect()->route('user.tickets')->with('success', 'Ticket updated successfully.');
}


public function ticket_destroy(Ticket $ticket)
{
    // Ensure only the owner can delete the ticket
    if ($ticket->user_id !== auth()->user()->id) {
        abort(403, 'Unauthorized access.');
    }

    // Delete associated screenshot if exists
    if ($ticket->screenshot) {
        Storage::delete($ticket->screenshot);
    }

    // Delete the ticket
    $ticket->delete();

    return redirect()->route('user.tickets')->with('success', 'Ticket deleted successfully.');
}



   







}
