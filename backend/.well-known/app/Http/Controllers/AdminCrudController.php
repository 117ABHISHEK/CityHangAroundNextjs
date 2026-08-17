<?php

namespace App\Http\Controllers;

use App\Models\{Blogcategory,Brand,Category,Pagecategory,Eventcategory,User,Page,Page_like,Blog,FileUploader, Payment_gateway,Ticket,TicketComment,PageMedia,IncompleteListing,ManageApproval,ClaimListing};
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Image, DB, Session, DataTables, Flasher;
use Illuminate\Support\Facades\Validator;
use App\Models\Album_image;
use App\Models\Group;
use App\Models\Event;
use App\Models\Posts;
use App\Models\Group_member;
use App\Models\Marketplace;
use App\Models\Media_files;
use App\Models\Video;
use App\Models\Groupcategory;
use App\Models\WalletTransaction;

use App\Models\State;
use App\Models\City;
use App\Models\Area;
use App\Models\Country;
use Illuminate\Support\Str; 


use App\Models\Enquirymaster;
use App\Models\LeadPurchases;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\StoreItemsFromApi;
use App\Models\PageConversation;
use App\Models\PageMessage;

class AdminCrudController extends Controller
{

    function __construct(){

        //Don't remove it
        session(['admin_login' => 1]);
    }


     // Show all conversations to admin
    public function page_enquiry_index(Request $request)
{
    $query = \App\Models\PageConversation::with('user', 'page');

    // ✅ Date Filter (optional)
    if ($request->filled('from')) {
        $query->whereDate('created_at', '>=', $request->from);
    }

    if ($request->filled('to')) {
        $query->whereDate('created_at', '<=', $request->to);
    }

    $conversations = $query->latest()->paginate(10);

    return view('backend.index', [
        'conversations' => $conversations,
        'view_path' => 'chat.index'
    ]);
}




    // Show specific chat by conversation id
   public function page_enquiry_show($id)
{
    $conversation = \App\Models\PageConversation::with(['user', 'page', 'messages.sender'])->findOrFail($id);
    $user = auth()->user();

    $isPageOwner = $conversation->page && $conversation->page->user_id === $user->id;
    $isInitiator = $conversation->user_id === $user->id;

    // 👇 Allow admin also
    $isAdmin = $user->role === 'admin' || $user->is_admin; // Adjust based on your system

    

    $page_data['conversation'] = $conversation;
    $page_data['messages'] = $conversation->messages()->with('sender')->oldest()->get();
    $page_data['view_path'] = 'chat.show';

    return view('backend.index', $page_data);
}



    // Send message as admin
   public function page_enquiry_sendMessage(Request $request, $id)
{
    $request->validate([
        'message' => 'nullable|string|max:1000',
        'image' => 'nullable|image|max:2048',
    ]);

    $conversation = PageConversation::findOrFail($id);

    $data = [
        'conversation_id' => $conversation->id,
        'sender_id' => auth()->id(),
        'message' => $request->message,
    ];

    $image = $request->file('image');
     $imagePath = null;
    if ($image && !empty($image)) {
    $imagePath = FileUploader::upload($image, 'public/pages/chat_images', 250);
    if ($imagePath) {
        $data['image'] = $imagePath;
        }
    }


    $imageUrl = null;
        if ($imagePath) {
            $imageUrl = Str::startsWith($imagePath, 'https')
                ? $imagePath
                : asset('pages/chat_images/' . $imagePath);
        }
    $message = PageMessage::create($data);
    

    return response()->json([
        'id' => $message->id,
        'message' => $message->message,
        'image_url' =>  $imageUrl,
        'time' => $message->created_at->format('d M Y, h:i A'),
        'sender_photo' => auth()->user()->photo ?? asset('assets/default-avatar.png')
    ]);
}

public function fetchpagechatMessages($id)
{
    $conversation = PageConversation::with('messages.sender')->findOrFail($id);
    $lastId = request('last_id', 0); // default 0
    $messages = $conversation->messages
       ->where('id', '>', $lastId)
        ->sortBy('created_at')
        ->values();

    return response()->json([
        'messages' => $messages->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'sender_name' => $msg->sender->name ?? 'Unknown',
                'sender_photo' => $msg->sender->photo ?? asset('assets/default-avatar.png'),
                'message' => $msg->message,
                'image_url' => $msg->image
                    ? (Str::startsWith($msg->image, 'https') ? $msg->image : asset('pages/chat_images/' . $msg->image))
                    : null,
                'time' => $msg->created_at->format('d M Y, h:i A'),
            ];
        }),
    ]);
}


// Controller: MarketplaceChatController.php (or wherever you keep it)

public function market_enquiry_index(Request $request)
{
    $query = \App\Models\MarketplaceConversation::with('user', 'marketplace');

    if ($request->filled('from')) {
        $query->whereDate('created_at', '>=', $request->from);
    }

    if ($request->filled('to')) {
        $query->whereDate('created_at', '<=', $request->to);
    }

    $conversations = $query->latest()->paginate(10);

    return view('backend.index', [
        'conversations' => $conversations,
        'view_path' => 'market_chat.index' // ✅ reuse view
    ]);
}


public function market_enquiry_show($id)
{
    $conversation = \App\Models\MarketplaceConversation::with(['user', 'marketplace', 'messages.sender'])->findOrFail($id);
    $user = auth()->user();

    $isOwner = $conversation->market && $conversation->market->user_id === $user->id;
    $isInitiator = $conversation->user_id === $user->id;

    $isAdmin = $user->role === 'admin' || $user->is_admin;

    $page_data['conversation'] = $conversation;
    $page_data['messages'] = $conversation->messages()->with('sender')->oldest()->get();
    $page_data['view_path'] = 'market_chat.show';

    return view('backend.index', $page_data);
}


public function market_enquiry_sendMessage(Request $request, $id)
{
    $request->validate([
        'message' => 'nullable|string|max:1000',
        'image' => 'nullable|image|max:2048',
    ]);

    $conversation = \App\Models\MarketplaceConversation::findOrFail($id);

    $data = [
        'conversation_id' => $conversation->id,
        'sender_id' => auth()->id(),
        'message' => $request->message,
    ];

    $image = $request->file('image');
    $imagePath = null;

    if ($image && !empty($image)) {
        $imagePath = FileUploader::upload($image, 'public/marketplace/chat_images', 250);
        if ($imagePath) {
            $data['image'] = $imagePath;
        }
    }

    $imageUrl = null;
    if ($imagePath) {
        $imageUrl = Str::startsWith($imagePath, 'https')
            ? $imagePath
            : asset('marketplace/chat_images/' . $imagePath);
    }

    $message = \App\Models\MarketplaceMessage::create($data);

    return response()->json([
        'id' => $message->id,
        'message' => $message->message,
        'image_url' =>  $imageUrl,
        'time' => $message->created_at->format('d M Y, h:i A'),
        'sender_photo' => auth()->user()->photo ?? asset('assets/default-avatar.png')
    ]);
}


public function fetchMarketChatMessages($id)
{
    $conversation = \App\Models\MarketplaceConversation::with('messages.sender')->findOrFail($id);
    $lastId = request('last_id', 0);

    $messages = $conversation->messages
       ->where('id', '>', $lastId)
       ->sortBy('created_at')
       ->values();

    return response()->json([
        'messages' => $messages->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'sender_name' => $msg->sender->name ?? 'Unknown',
                'sender_photo' => $msg->sender->photo ?? asset('assets/default-avatar.png'),
                'message' => $msg->message,
                'image_url' => $msg->image
                    ? (Str::startsWith($msg->image, 'https') ? $msg->image : asset('marketplace/chat_images/' . $msg->image))
                    : null,
                'time' => $msg->created_at->format('d M Y, h:i A'),
            ];
        }),
    ]);
}



    public function showallIncompleteListings()
    {
       
        $page_data['incompleteListings'] =IncompleteListing::with('user')->latest()->paginate(10);
        $page_data['view_path'] = 'page.draft.index';
        return view('backend.index', $page_data);

        //return view('listings.incomplete', compact('incompleteListings'));
    }


    public function leadPurchaseReport(Request $request)
    {
        // Get the authenticated user
        $user = auth()->user();
        $userId = $user->id;
        $isAdmin = $user->user_role;  // Admin role in your case
        $userName = $user->name;
    
        // Extract other filters from the request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $paymentType = $request->input('payment_type');
        $selectedUserId = $request->input('user_id'); // Get user_id filter if provided
    
        // ✅ Ensure Start & End Dates are Carbon Instances
        $startDate = $startDate
            ? Carbon::parse($startDate)->startOfDay()
            : now()->startOfDay();  // Default to current date if no start_date is provided
    
        $endDate = $endDate
            ? Carbon::parse($endDate)->endOfDay()
            : now()->endOfDay();    // Default to current date if no end_date is provided
    
        // Initialize an empty collection to hold transactions
        $transactions = collect();

        if ($paymentType == 'Wallet' || !$paymentType) {
    
        // Fetch Wallet Transactions (Lead Purchases)
        $walletTransactionsQuery = WalletTransaction::whereBetween('wallet_transactions.created_at', [$startDate, $endDate])
            ->where('wallet_transactions.type', 'debit')
            ->where('wallet_transactions.description', 'LIKE', '%Lead purchased%')
            ->join('lead_purchases', 'wallet_transactions.id', '=', 'lead_purchases.transactionid')
            ->join('enquirymaster', 'lead_purchases.lead_id', '=', 'enquirymaster.id')
            ->join('marketplaces', 'enquirymaster.productid', '=', 'marketplaces.id')
            ->select(
                'wallet_transactions.id',
                'wallet_transactions.user_id',
                'wallet_transactions.amount',
                'wallet_transactions.created_at',
                'wallet_transactions.description',
                'enquirymaster.name as lead_name',
                'marketplaces.category as category_name',
                'marketplaces.title as product_name'
            )
            ->distinct();
    
        // Apply user_id filter if provided (only for admin)
        if ($isAdmin && $selectedUserId) {
            $walletTransactionsQuery->where('wallet_transactions.user_id', $selectedUserId);
        } elseif (!$isAdmin) {
            // If user is not admin, show only their own transactions
            $walletTransactionsQuery->where('wallet_transactions.user_id', $userId);
        }
    
        // Apply payment type filter if provided (Wallet)
        if ($paymentType && $paymentType == 'Wallet') {
            $walletTransactionsQuery->where('wallet_transactions.description', 'LIKE', '%Lead purchased%');
        }
    
        $walletTransactions = $walletTransactionsQuery->get()
            ->map(function ($transaction) {
                $transaction->display_transaction_id = 'Wallet Transaction';
                $transaction->payment_type = 'Wallet';
                return $transaction;
            });
    
        // Add Wallet transactions to the collection
        $transactions = $transactions->concat($walletTransactions);
        }

        if ($paymentType == 'Online' || !$paymentType) {
    
        // Fetch Online Transactions (Lead Purchases)
        $paymentTransactionsQuery = PaymentTransaction::whereBetween('payment_transactions.created_at', [$startDate, $endDate])
            ->where('payment_transactions.status', 'successful')
            ->where('payment_transactions.description', 'LIKE', '%Lead purchased%')
            ->join('lead_purchases', 'payment_transactions.transaction_id', '=', 'lead_purchases.transactionid')
            ->join('enquirymaster', 'lead_purchases.lead_id', '=', 'enquirymaster.id')
            ->join('marketplaces', 'enquirymaster.productid', '=', 'marketplaces.id')
            ->select(
                'payment_transactions.transaction_id',
                'payment_transactions.user_id',
                'payment_transactions.amount',
                'payment_transactions.created_at',
                'payment_transactions.description',
                'enquirymaster.name as lead_name',
                'marketplaces.category as category_name',
                'marketplaces.title as product_name'
            )
            ->distinct();
    
        // Apply user_id filter if provided (only for admin)
        if ($isAdmin && $selectedUserId) {
            $paymentTransactionsQuery->where('payment_transactions.user_id', $selectedUserId);
        } elseif (!$isAdmin) {
            // If user is not admin, show only their own transactions
            $paymentTransactionsQuery->where('payment_transactions.user_id', $userId);
        }
    
        // Apply payment type filter if provided (Online)
        if ($paymentType && $paymentType == 'Online') {
            $paymentTransactionsQuery->where('payment_transactions.description', 'LIKE', '%Lead purchased%');
        }
    
        $paymentTransactions = $paymentTransactionsQuery->get()
            ->map(function ($transaction) {
                $transaction->display_transaction_id = $transaction->transaction_id;
                $transaction->payment_type = 'Online Payment';
                return $transaction;
            });
    
        // Add Payment transactions to the collection
        $transactions = $transactions->concat($paymentTransactions);
        }
    
        // If the user is an admin, fetch all users for filtering (Optional for Admin)
        $users = $isAdmin ? User::all() : collect([]);
    
        // Return the view with all transactions (by default) and available filters
        return view('backend.index', [
            'transactions' => $transactions,
            'startDate'    => $startDate->format('Y-m-d'),
            'endDate'      => $endDate->format('Y-m-d'),
            'userId'       => $selectedUserId,  // Pass selected user ID for the filter (null if not set)
            'users'        => $users, // Pass users list for admin (optional)
            'userName'     => $userName, 
            'view_path'    => 'leads.index',
        ]);
    }
    


    public function reportsList(Request $request)
    {
        $query = DB::table('reports_all')
            ->leftJoin('groups', function ($join) {
                $join->on('reports_all.entity_id', '=', 'groups.id')
                    ->where('reports_all.type', '=', 'group');
            })
            ->leftJoin('pages', function ($join) {
                $join->on('reports_all.entity_id', '=', 'pages.id')
                    ->where('reports_all.type', '=', 'page');
            })
            ->leftJoin('events', function ($join) {
                $join->on('reports_all.entity_id', '=', 'events.id')
                    ->where('reports_all.type', '=', 'event');
            })
            ->leftJoin('blogs', function ($join) {
                $join->on('reports_all.entity_id', '=', 'blogs.id')
                    ->where('reports_all.type', '=', 'blog');
            })
            ->leftJoin('marketplaces', function ($join) {
                $join->on('reports_all.entity_id', '=', 'marketplaces.id')
                    ->where('reports_all.type', '=', 'products');
            })
            ->leftJoin('users', function ($join) {
                $join->on('reports_all.entity_id', '=', 'users.id')
                    ->where('reports_all.type', '=', 'profile');
            })
            ->select(
                'reports_all.id',
                'reports_all.type',
                'reports_all.entity_id',
                'reports_all.full_name',
                'reports_all.email',
                'reports_all.phone',
                'reports_all.reason',
                'reports_all.additional_comments',
                'reports_all.proof_attachment',
                'reports_all.response_required',
                'reports_all.created_at',
                DB::raw("COALESCE(`groups`.title, pages.title, events.title, blogs.title, marketplaces.title, users.name) as entity_name")
            );

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('reports_all.created_at', [$request->start_date, $request->end_date]);
        }

        $page_data['reports'] = $query->orderBy('reports_all.created_at', 'DESC')->paginate(10);

        $page_data['view_path'] ='report.reports';
        return view('backend.index',$page_data);

        //return view('admin.reports', compact('reports'));
    }

    public function searchclaimListings(Request $request)
{
    $claimsQuery = ClaimListing::with([
        'page.city',
        'page.area',
        'page.categories',
        'user'
    ])
    ->whereNotNull('page_id') // ✅ Only claims that have a page_id
    ->has('page');            // ✅ Only claims where related page actually exists

    // If search is present
    if ($request->filled('search')) {
        $search = $request->search;

        $claimsQuery->where(function ($query) use ($search) {
            $query->whereHas('page', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        });
    }

    $claims = $claimsQuery->orderBy('created_at', 'desc')->get();

    // Format response
    $data = $claims->map(function ($claim) {
        $page = $claim->page;

        return [
            'id' => $claim->id,
            'title' => $page->title ?? 'N/A',
            'item_slug' => $page->item_slug ?? '',
            'city_slug' => optional($page?->city)->city_slug ?? '',
            'area_slug' => optional($page?->area)->area_slug ?? '',
            'category_slug' => optional($page?->categories?->last())->category_slug ?? '',
            'ownership_proof' => $claim->ownership_proof,
            'is_approved' => $claim->is_approved,
            'user_name' => optional($claim->user)->name ?? 'Unknown',
            'user_email' => optional($claim->user)->email ?? 'Unknown'
        ];
    });

    return response()->json($data);
}




    public function claimListings(Request $request)
    {
        $query = DB::table('claim_listings')
            ->select(
                'claim_listings.*', 
                'users.name as user_name', 
                'users.email as user_email',
                'users.id as user_id', 
                'pages.title', 
            )
            ->join('users', 'claim_listings.user_id', '=', 'users.id')
            ->join('pages', 'claim_listings.page_id', '=', 'pages.id');

        // Apply date filter if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('claim_listings.created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('pages.title', 'like', "%{$search}%")
                ->orWhere('users.name', 'like', "%{$search}%")
                ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        $page_data['claims'] = $query->orderBy('claim_listings.created_at', 'DESC')->paginate(10);

        

        $page_data['view_path'] ='page.claim_listing';
        return view('backend.index',$page_data);
    }


    



    public function updateClaimStatus(Request $request)
{
    $claim = DB::table('claim_listings')->where('id', $request->claim_id)->first();

    if (!$claim) {
        return response()->json(['success' => false, 'message' => 'Claim not found!']);
    }

    // If approved, get the user_id from claim_listings, otherwise set to admin (ID = 1)
    $newUserId = $request->status === 'Y' ? $claim->user_id : 1;

    // Update claim status in claim_listings
    DB::table('claim_listings')->where('id', $request->claim_id)->update([
        'is_approved' => $request->status
    ]);

    // Update user_id in pages and marketplaces tables based on claim
    if ($claim->page_id) {
        DB::table('pages')->where('id', $claim->page_id)->update(['user_id' => $newUserId]);
    }

    if ($claim->page_id) {
        DB::table('marketplaces')->where('page_id', $claim->page_id)->update(['user_id' => $newUserId]);
    }

    return response()->json(['success' => true, 'message' => 'Claim status updated successfully!']);
}

    // public function updateClaimStatus(Request $request)
    // {
    //     $claim = DB::table('claim_listings')->where('id', $request->claim_id)->update([
    //         'is_approved' => $request->status
    //     ]);

    //     return response()->json(['success' => true, 'message' => 'Claim status updated successfully!']);
    // }




    // Display all group categories
    public function group_category_index()
    {
        $page_data['categories'] = GroupCategory::with('parent')->paginate(10);
        $page_data['view_path'] ='group_category.index';
        return view('backend.index',$page_data);
    }

    // Show the create category form
    public function group_category_create()
    {
        $page_data['categories'] = GroupCategory::all();

        $page_data['view_path'] ='group_category.create';
        return view('backend.index',$page_data);
    }

    // Store a new category
    public function group_category_store(Request $request)
    {
        //echo "123";exit;
            $validated = $request->validate([
                'category_name' => 'required|max:255|string|unique:groupcategories,category_name',
            ]);

            if ($request->parent_id == 0) {
                $category_parent_id = null;
            } else {
                $category_parent_id = $request->parent_id;
            }

            $groupCategory = new GroupCategory();
            $groupCategory->category_name = $request->category_name;
            $groupCategory->category_slug = str_slug($request->category_name);
            $groupCategory->category_parent_id = $category_parent_id;
            $done = $groupCategory->save();

            if ($done) {
                flash()->addSuccess('Group Category has been added successfully!');
            }

            return redirect()->back();

    }

    // Show the edit category form
    public function group_category_edit($id)
    {
        $groupCategory = GroupCategory::findOrFail($id);
        $categories = GroupCategory::where('id', '!=', $id)->get();
        $page_data['groupCategory'] =$groupCategory;
        $page_data['categories'] =$categories;

        $page_data['view_path'] ='group_category.edit';
        return view('backend.index',$page_data);
    }

    // Update category
    public function group_category_update(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'category_name' => 'required|max:255|string|unique:groupcategories,category_name,' . $id,
        ]);
    
        // Find the category by ID
        $groupCategory = GroupCategory::findOrFail($id);
    
        // Set parent category ID based on selection
        $category_parent_id = ($request->parent_id == 0) ? null : $request->parent_id;
    
        // Update category details
        $groupCategory->category_name = $request->category_name;
        $groupCategory->category_slug = str_slug($request->category_name);
        $groupCategory->category_parent_id = $category_parent_id;
    
        // Save and check success
        if ($groupCategory->save()) {
            flash()->addSuccess('Group Category has been updated successfully!');
        }
    
        return redirect()->route('admin.group.categories');
    }
    


    // Delete category
    public function group_category_destroy($id)
    {
        $category = GroupCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.group.categories')->with('success', 'Category deleted successfully!');
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
        $page_data['all_category'] =[];
        $page_data['view_path'] ='dashboard.index';
        return view('backend.index',$page_data);
    }

    // page category 
    public function view_category(){
        // $page_data['all_category'] =  DB::table('pagecategories')->select('pagecategories.id','pagecategories.category_name','cat.category_name as parent')
        // ->leftjoin('pagecategories as cat','cat.id','=','pagecategories.category_parent_id')->orderby('id', 'asc')
        // ->paginate(50);
        // $page_data['view_path'] ='page_category.index';
        // return view('backend.index',$page_data);

       $page_data['all_category'] =Pagecategory::with('parentCategory')
    ->withCount('pages') // fast and efficient
    ->orderBy('id', 'asc')
    ->paginate(50);



$page_data['view_path'] = 'page_category.index';

return view('backend.index', $page_data);

    }

//     public function jsonSearch(Request $request)
// {
//     $query = $request->input('q');

//     $results = DB::table('pagecategories')
//         ->select(
//             'pagecategories.id',
//             'pagecategories.category_name',
//             'cat.category_name as parent',
//             DB::raw('COUNT(page_category.page_id) as page_count')
//         )
//         ->leftJoin('pagecategories as cat', 'cat.id', '=', 'pagecategories.category_parent_id')
//         ->leftJoin('page_category', 'pagecategories.id', '=', 'page_category.category_id')
//         ->when($query, function ($q) use ($query) {
//             $q->where('pagecategories.category_name', 'LIKE', '%' . $query . '%');
//         })
//         ->groupBy('pagecategories.id', 'pagecategories.category_name', 'cat.category_name')
//         ->orderBy('pagecategories.id', 'asc')
//         ->limit(20) // Optional: to control response size
//         ->get();

//     return response()->json($results);
// }



public function jsonSearch(Request $request)
{
    $query = $request->input('q');

    $results = Pagecategory::with('parentCategory')
        ->withCount('pages')
        ->when($query, function ($q) use ($query) {
            $q->where('category_name', 'LIKE', '%' . $query . '%');
        })
        ->orderBy('id', 'asc')
        ->limit(20)
        ->get()
        ->map(function ($cat) {
            return [
                'id' => $cat->id,
                'category_name' => $cat->category_name,
                'parent' => optional($cat->parentCategory)->category_name,
                'page_count' => $cat->pages_count,
                'category_icon' => $cat->category_icon,
                'is_parent' => $cat->is_parent,
                 'category_banner' => $cat->category_banner,
            ];
        });

    return response()->json($results);
}

public function checkIfCategoryExists(Request $request)
{
    $categoryName = $request->get('name');
    
    $exists = \App\Models\Pagecategory::where('category_name', $categoryName)->exists();

    return response()->json(['exists' => $exists]);
}



   

    // public function view_user_suggest_category(){
    //     $page_data['all_category'] =  DB::table('pagecategories')->select('pagecategories.id','pagecategories.category_name','cat.category_name as parent',
    //     'users.name','users.email','users.id as user_id')
    //     ->leftjoin('pagecategories as cat','cat.id','=','pagecategories.category_parent_id')
    //     ->join('users','users.id','pagecategories.category_createdby')
    //     ->orderby('id', 'asc')
    //     ->paginate(50);
    //     $page_data['view_path'] ='user_suggestion.page_category';
    //     return view('backend.index',$page_data);
    // }

    public function view_user_suggest_category(){
        $page_data['all_category'] = DB::table('pagecategories')
    ->select(
        'pagecategories.id',
        'pagecategories.category_name',
        'cat.category_name as parent',
        'users.name',
        'users.email',
        'users.id as user_id',
        'pages.title as page_name',
        'pages.item_slug',
        'cities.city_slug as city_slug',
        'areas.area_slug as area_slug',
        'pagecategories.category_slug as category_slug'
    )
    ->leftJoin('pagecategories as cat', 'cat.id', '=', 'pagecategories.category_parent_id')
    ->leftJoin('pagecategories as child', 'child.category_parent_id', '=', 'pagecategories.id') // child categories join
    ->leftJoin('pages', 'pages.category_id', '=', 'pagecategories.id')
    ->leftJoin('cities', 'cities.id', '=', 'pages.city_id')
    ->leftJoin('areas', 'areas.id', '=', 'pages.area_id')
    ->join('users', 'users.id', '=', 'pagecategories.category_createdby')
    ->whereNull('child.id')   // only categories which do NOT have child categories (last categories)
    ->orderBy('pagecategories.id', 'asc')
    ->paginate(50);

        $page_data['view_path'] ='user_suggestion.page_category';
        return view('backend.index',$page_data);
    }

    public function create_category(){
        $page_data['view_path'] ='page_category.create';
        return view('backend.index',$page_data);
    }



    // public function save_category(Request $request){
    //     $validated = $request->validate([
    //         'pagecategory' => 'required|max:255|string|unique:pagecategories,category_name',
    //     ]);
    //     if($request->category==0){

    //         $category_parent_id=null;
    //     }
    //     else{
    //         $category_parent_id=$request->category;
    //     }
    //     $pagecategory = new Pagecategory();
    //     $pagecategory->category_name = $request->pagecategory;
    //     $pagecategory->category_slug =str_slug($request->pagecategory);
    //     $pagecategory->category_parent_id = $category_parent_id;
    //     $done = $pagecategory->save();
    //     if($done){
    //         flash()->addSuccess('Page Category has been added successfully!');
    //     }
    //     return redirect()->back();
    // }


    public function save_category(Request $request)
{
    $validated = $request->validate([
        'pagecategory'    => 'required|max:255|string|unique:pagecategories,category_name',
        'category_icon'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'category_banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
    ]);

    $category_parent_id = ($request->category == 0) ? null : $request->category;

    $pagecategory = new Pagecategory();
    $pagecategory->category_name = $request->pagecategory;
    $pagecategory->category_slug = clean_slug($request->pagecategory);
    $pagecategory->category_parent_id = $category_parent_id;
    $pagecategory->is_parent = $request->has('is_parent') ? 'Yes' : 'No';

    // Upload icon if exists
    if ($request->hasFile('category_icon')) {
        $iconPath = FileUploader::upload($request->file('category_icon'), 'public/storage/categories/icons', 150);
        $pagecategory->category_icon = $iconPath;
    }

    // Upload banner if exists
    if ($request->hasFile('category_banner')) {
        $bannerPath = FileUploader::upload($request->file('category_banner'), 'public/storage/categories/banners', 600);
        $pagecategory->category_banner = $bannerPath;
    }

    if ($pagecategory->save()) {
        flash()->addSuccess('Page Category has been added successfully!');
    }

    return redirect()->back();
}


    public function edit_category($id){
        $page_data['pagecategory'] = Pagecategory::find($id);
        $page_data['view_path'] ='page_category.edit';
        return view('backend.index',$page_data);
    }


    public function edit_city($id){

        $page_data['city'] =  DB::table('cities')->select('cities.*')->where('id',$id)->first();
        $page_data['view_path'] ='user_suggestion.edit_city';
        return view('backend.index',$page_data);
    }


    public function edit_area($id){

        $page_data['area'] =  DB::table('areas')->select('areas.*')->where('id',$id)->first();
        $page_data['view_path'] ='user_suggestion.edit_area';
        return view('backend.index',$page_data);
    }


    public function update_city(Request $request,$id){
        $validated = $request->validate([
            'city' => 'required',
        ]);
        DB::table('cities')
        ->where('id', $id)
        ->update([
            'city_name' => $request->city,
        ]);
        flash()->addSuccess('City has been updated successfully!');
        return redirect()->route('admin.user.city');

    }


    public function update_area(Request $request,$id){
        $validated = $request->validate([
            'area' => 'required',
        ]);
        DB::table('areas')
        ->where('id', $id)
        ->update([
            'area_name' => $request->area,
        ]);
        flash()->addSuccess('Area has been updated successfully!');
        return redirect()->route('admin.user.area');

    }

    // public function  update_category(Request $request,$id){
    //     $validated = $request->validate([
    //         'pagecategory' => 'required|max:255|string|unique:pagecategories,category_name,'.$id,
    //     ]);
    //     $pagecategory = Pagecategory::find($id);
    //     if($request->category==0){

    //         $category_parent_id=null;
    //     }
    //     else{
    //         $category_parent_id=$request->category;
    //     }
    //     $pagecategory->category_name = $request->pagecategory;
    //     $pagecategory->category_slug =str_slug($request->pagecategory);
    //     $pagecategory->category_parent_id = $category_parent_id;
    //     $done = $pagecategory->save();
    //     if($done){
    //         flash()->addSuccess('Page Category has been updated successfully!');
    //     }
    //     return redirect()->route('admin.view.category');
    // }


    public function update_category(Request $request, $id)
    {
        $validated = $request->validate([
            'pagecategory'     => 'required|max:255|string|unique:pagecategories,category_name,' . $id,
            'category_icon'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_banner'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        $pagecategory = Pagecategory::findOrFail($id);

        // Determine parent ID
        $category_parent_id = $request->category == 0 ? null : $request->category;

        // Upload icon if provided
        if ($request->hasFile('category_icon')) {
            $pagecategory->category_icon = FileUploader::upload($request->file('category_icon'), 'public/storage/pagecategories/icons', 100);
        }

        // Upload banner if provided
        if ($request->hasFile('category_banner')) {
            $pagecategory->category_banner = FileUploader::upload($request->file('category_banner'), 'public/storage/pagecategories/banners', 800);
        }

        $pagecategory->category_name = $request->pagecategory;
        $pagecategory->category_slug = clean_slug($request->pagecategory);
        $pagecategory->category_parent_id = $category_parent_id;
        $pagecategory->is_parent = $request->has('is_parent') ? 'Yes' : 'No';
        $pagecategory->save();

        flash()->addSuccess('Page Category has been updated successfully!');
        //return redirect()->route('admin.view.category');
        $page = $request->query('page', 1); // Get current page or fallback to 1
        return redirect()->route('admin.view.category', ['page' => $page]);

    }


    public function delete_category($id){
        $category = Pagecategory::find($id);
        $category->delete();
        flash()->addSuccess('Page Category has been Deleted successfully!');
        return redirect()->back();
    }

    public function delete_city($id){
        DB::table('cities')
        ->where('id', $id)
        ->delete();
        flash()->addSuccess('City has been Deleted successfully!');
        return redirect()->back();
    }

    public function delete_area($id){
        DB::table('areas')
        ->where('id', $id)
        ->delete();
        flash()->addSuccess('Area has been Deleted successfully!');
        return redirect()->back();
    }


    public function view_all_event(){
        if(isset($_GET['delete']) && $_GET['delete'] == 'yes' && isset($_GET['id'])){
            Event::find($_GET['id'])->delete();
            DB::table('event_category')->where('event_id',$_GET['id'])->delete();
            flash()->addSuccess('Event deleted successfully');
            return redirect()->back();
        }
        $page_data['events'] =  DB::table('events')->select('events.*','users.id as userid','users.name','users.email')
        ->join('event_category','events.id','=','event_category.event_id')
        ->join('users','users.id','=','events.user_id')->orderby('events.id', 'asc')
        ->distinct()
        ->paginate(50);
        $page_data['view_path'] ='event.index';
        return view('backend.index',$page_data);
    }


    public function view_upcoming_event(){

        if(isset($_GET['delete']) && $_GET['delete'] == 'yes' && isset($_GET['id'])){
            Event::find($_GET['id'])->delete();
            DB::table('event_category')->where('event_id',$_GET['id'])->delete();
            flash()->addSuccess('Event deleted successfully');
            return redirect()->back();
        }
        $page_data['events'] =  DB::table('events')->select('events.*','users.id as userid','users.name','users.email')
        ->join('event_category','events.id','=','event_category.event_id')
        ->join('users','users.id','=','events.user_id')->orderby('events.id', 'asc')
        ->where('events.event_date', '>=', Carbon::now()) // Add the date filter
        ->distinct()
        ->paginate(50);
        $page_data['view_path'] ='event.upcoming';
        return view('backend.index',$page_data);
    }

    public function view_previous_event(){

        if(isset($_GET['delete']) && $_GET['delete'] == 'yes' && isset($_GET['id'])){
            Event::find($_GET['id'])->delete();
            DB::table('event_category')->where('event_id',$_GET['id'])->delete();
            flash()->addSuccess('Event deleted successfully');
            return redirect()->back();
        }
        $page_data['events'] =  DB::table('events')->select('events.*','users.id as userid','users.name','users.email')
        ->join('event_category','events.id','=','event_category.event_id')
        ->join('users','users.id','=','events.user_id')->orderby('events.id', 'asc')
        ->where('events.event_date', '<=', Carbon::now()) // Add the date filter
        ->distinct()
        ->paginate(50);
        $page_data['view_path'] ='event.previous';
        return view('backend.index',$page_data);
    }


    public function create_event(){

        $page_data['printable_categories'] =  DB::table('eventcategories')->where('category_parent_id',null)
        ->get();
        $page_data['all_states'] = DB::table('states')->select('states.*')
        ->where('country_id' , 101)->get();
        $page_data['parent'] =  DB::table('eventcategories')
        ->where('eventcategories.category_parent_id',null)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();
        $page_data['view_path'] ='event.create';
        return view('backend.index',$page_data);
    }

    public function edit_event($id){
        //echo $id;exit;
        $page_data['event_id']=$id;
        $page_data['printable_categories'] =  DB::table('eventcategories')->where('category_parent_id',null)
        ->get();
       
        $page_data['parent'] =  DB::table('eventcategories')
        ->where('eventcategories.category_parent_id',null)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();
        //print_r($page_data['parent']);exit;
        $page_data['event'] = \App\Models\Event::find($id);
        //print_r($page_data['event']);exit;
        $page_data['all_states'] = DB::table('states')->select('states.*')
        ->where('country_id' , 101)->get();
        $page_data['all_cities'] = DB::table('cities')->select('cities.*')
        ->where('state_id' , $page_data['event']->state_id)->get();
        $page_data['all_areas'] = DB::table('areas')->select('areas.*')
        ->where('city_id' , $page_data['event']->city_id)->get();


        $page_data['view_path'] = 'event.edit';
        return view('backend.index', $page_data);
    }

    //  update event 
    public function update_event(Request $request, $id)
    {
        $rules = array(
            'coverphoto' => 'mimes:jpeg,jpg,png,gif|nullable',
            'eventname' => 'required|max:255',
            'eventdate' => 'required',
            'eventtime' => 'required',
            'eventlocation' => 'required',
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
            ->withInput(); 
        }
        if ($request->coverphoto && !empty($request->coverphoto)) {

            //Upload image
            $file_name = rand(1, 35000) . '.' . $request->coverphoto->getClientOriginalExtension();

            //thumbnail
            $img = Image::make($request->coverphoto);
            $img->resize(325, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save(uploadTo('event/thumbnail') . $file_name);

            // cover photo 
            $img = Image::make($request->coverphoto);
            $img->resize(1120, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save(uploadTo('event/coverphoto') . $file_name);
        }

        


        $event_slug=preg_replace("/[^A-Za-z0-9 ]/", '', $request->eventname);

       
        $multiSelectArray=$request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }
        
        $categories_id=implode(',', $multiSelectArray);


        $event = Event::find($id);

        //$event->user_id = Auth::user()->id;
        // store image name for delete file operation 
        $imagename = $event->banner;

        $event->title = $request->eventname;
        $event->event_slug =str_slug($event_slug);
       
        $event->event_status =$request->event_status;
        $event->state_id =$request->state;
        $event->city_id =$request->city;
        $event->area_id =$request->area;
        $event->category_id = $categories_id;
        $event->description = $request->description;
        $event->event_date = $request->eventdate;
        $event->event_time = $request->eventtime;
        $event->location = $request->eventlocation;
        !empty($request->coverphoto) ? $event->banner =  $file_name : $event->banner;
        $event->privacy = $request->privacy;
        $done = $event->save();
        if ($done) {
            // just put the file name and folder name nothing more :) 
            removeFile('event', $imagename);

            foreach($request->category as $key => $category_id)
            {
                $data=array(
                    'category_id'=>$category_id,
                    "event_id"=>$id
                );
                $row=DB::table('event_category')->insertGetId($data);


            }

            $slug_count=DB::table('events')->select('events.id')
            ->where('events.event_slug',str_slug($request->name))->count();;
    
            if($slug_count>1){
    
                DB::table('events')->where('id', $id)
                ->update(array('event_slug' =>DB::raw('concat("'.str_slug($request->eventname).'",'.'-'.$id.')')));
            }
            flash()->addSuccess('Event Updated Successfully');
            return redirect(route('admin.view.event'));
        }
    }

    // event store
    public function store(Request $request)
    {
        // return $request->all();

        $rules = array(
            'coverphoto' => 'mimes:jpeg,jpg,png,gif|nullable',
            'eventname' => 'required|max:255',
            'eventdate' => 'required',
            'eventtime' => 'required',
            'eventlocation' => 'required',
            'category' => 'required',
            'state' => 'required|not_in:0',
            'city' => 'required|not_in:0',
            'area' => 'required|not_in:0',
        );
        $validator = Validator::make($request->all(), $rules);
        // if ($validator->fails()) {
        //     return redirect()->back();
        //     //return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
        // }
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput(); // Optional: to retain the input data
        }
        if ($request->coverphoto && !empty($request->coverphoto)) {

            //Upload image
            $file_name = rand(1, 35000) . '.' . $request->coverphoto->getClientOriginalExtension();

            //thumbnail
            $img = Image::make($request->coverphoto);
            $img->resize(325, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save(uploadTo('event/thumbnail') . $file_name);

            // cover photo 
            $img = Image::make($request->coverphoto);
            $img->resize(1120, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save(uploadTo('event/coverphoto') . $file_name);
        }
       

       

            $event_status=2;

      


        $event_slug=preg_replace("/[^A-Za-z0-9 ]/", '', $request->eventname);

       
        $multiSelectArray=$request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }
        
        $categories_id=implode(',', $multiSelectArray);

        //print_r($categories_id);exit;

        $event = new Event();

        $event->user_id =auth()->user()->id;
        $event->title = $request->eventname;
        $event->event_status =$event_status;
        $event->event_slug =str_slug($event_slug);
        $event->state_id =$request->state;
        $event->city_id =$request->city;
        $event->area_id =$request->area;
        $event->category_id = $categories_id;

        $event->description = $request->description;
        $event->event_date = $request->eventdate;
        $event->event_time = $request->eventtime;
        $event->location = $request->eventlocation;
        if (isset($request->group_id)) {
            $event->group_id = $request->group_id;
        }
        !empty($request->coverphoto) ? $event->banner =  $file_name : "";
        $event->going_users_id = "[]";
        $event->interested_users_id = "[]";
        $event->privacy = $request->privacy;
        $done = $event->save();
        if ($done) {
            
            $data['user_id'] = auth()->user()->id;
            $data['privacy'] = $request->privacy;
            $data['publisher'] = 'event';
            $data['publisher_id'] = $event->id;
            $data['post_type'] = "event";
            $data['status'] = 'active';
            $data['description'] = $request->description;
            $data['user_reacts'] = json_encode(array());
            $data['user_reacts'] = json_encode(array());
            $data['tagged_user_ids'] = json_encode(array());
            $data['created_at'] = time();
            $data['updated_at'] = $data['created_at'];
            Posts::create($data);

            foreach($request->category as $key => $category_id)
            {
                $data=array(
                    'category_id'=>$category_id,
                    "event_id"=>$event->id
                );
                $row=DB::table('event_category')->insertGetId($data);


            }

            $slug_count=DB::table('events')->select('events.id')
            ->where('events.event_slug',str_slug($request->name))->count();;
    
            if($slug_count>1){
    
                DB::table('events')->where('id', $event->id)
                ->update(array('event_slug' =>DB::raw('concat("'.str_slug($request->eventname).'",'.'-'.$event->id.')')));
            }

            flash()->addSuccess('Event Created Successfully');
            return redirect(route('admin.view.event'));
        }
       
    }



    public function view_user_suggest_city()
{
    $page_data['all_category'] = City::whereNotNull('createdBy')
        ->whereHas('creator')
        ->select('cities.id', 'city_name', 'city_slug', 'createdBy', 'state_id') // ✅ include state_id
        ->with([
            'creator:id,name,email',
            'state:id,state_name', // ✅ eager load state name

            'pages' => function ($q) {
                $q->select(
                    'pages.id',
                    'title',
                    'item_slug',
                    'city_id',
                    'area_id',
                    'category_id'
                )->with([
                    'area:id,area_slug',
                    'getCategory:id,category_slug'
                ]);
            },

            'events' => function ($q) {
                $q->select(
                    'events.id',
                    'event_slug',
                    'title',
                    'city_id',
                    'area_id',
                    'category_id'
                )->with([
                    'area:id,area_slug',
                    'categories:id,category_slug'
                ]);
            },

            'marketplaces' => function ($q) {
                $q->select(
                    'marketplaces.id',
                    'product_slug',
                    'city_id',
                    'page_id'
                )->with([
                     'page:id,item_slug,category_id', // relation to Page
                      'page.category:id,category_slug', // Page's category
                      'productCategories:id,product_category_slug' // Marketplace's own categories
                ]);
            }
        ])
        ->orderBy('cities.id', 'asc')
        ->paginate(50);

    $page_data['view_path'] = 'user_suggestion.city';
    return view('backend.index', $page_data);
}



    // public function view_user_suggest_city(){

    // $page_data['all_category'] =  DB::table('cities')->select('cities.id','cities.city_name','states.state_name',
    // 'users.id as user_id','users.name','users.email')
    // ->join('states','states.id','=','cities.state_id')
    // ->join('users','users.id','cities.createdBy')->orderby('cities.id', 'asc')
    // ->paginate(50);
    // $page_data['view_path'] ='user_suggestion.city';
    // return view('backend.index',$page_data);
    // }


    // public function view_user_suggest_area(){

    //     $page_data['all_category'] =  DB::table('areas')->select('areas.id','areas.area_name','cities.city_name',
    //     'users.id as user_id','users.name','users.email')
    //     ->join('cities','cities.id','=','areas.city_id')
    //     ->join('users','users.id','areas.createdBy')->orderby('cities.id', 'asc')
    //     ->paginate(50);
    //     $page_data['view_path'] ='user_suggestion.area';
    //     return view('backend.index',$page_data);
    // }


    public function view_user_suggest_area()
{
    $page_data['all_category'] = Area::whereNotNull('createdBy')
        ->whereHas('creator')
        ->select('id', 'area_name', 'city_id', 'createdBy')
        ->with([
            'creator:id,name,email',
            'city:id,city_name,city_slug',

            // PAGE -> hasOne Category
            'pages' => function ($q) {
                $q->select('pages.id', 'pages.title', 'pages.item_slug', 'pages.city_id', 'pages.area_id', 'category_id')
                  ->with([
                      'category:id,category_slug'
                  ]);
            },

            // EVENTS -> many Categories (assuming pivot table exists)
            'events' => function ($q) {
                $q->select('events.id', 'title', 'event_slug', 'city_id', 'area_id', 'category_id')
                  ->with([
                      'categories:id,category_slug'
                  ]);
            },

            // MARKETPLACES
            'marketplaces' => function ($q) {
                $q->select('marketplaces.id', 'product_slug', 'page_id')
                  ->with([
                      'page:id,item_slug,category_id', // relation to Page
                      'page.category:id,category_slug', // Page's category
                      'productCategories:id,product_category_slug' // Marketplace's own categories
                  ]);
            }
        ])
        ->orderBy('id', 'asc')
        ->paginate(50);

    $page_data['view_path'] = 'user_suggestion.area';
    return view('backend.index', $page_data);
}




    public function view_user_suggest_event_category()
{
    $categories = Eventcategory::with([
        'parent:id,category_name',
        'creator:id,name,email',
        'events' => function ($query) {
            $query->with([
                'city:id,city_slug',
                'area:id,area_slug'
            ]);
        }
    ])
    ->whereNotNull('category_createdby')  // ✅ User-created
    ->whereDoesntHave('children')         // ✅ Leaf categories
    ->orderBy('id', 'asc')
    ->paginate(50);

    return view('backend.index', [
        'all_category' => $categories,
        'view_path' => 'user_suggestion.event_category',
    ]);
}



//   public function view_user_suggest_event_category(){

//     $page_data['all_category'] =  DB::table('eventcategories')->select('eventcategories.id','eventcategories.category_name','cat.category_name as parent',
//     'users.id as user_id','users.name','users.email')
//     ->leftjoin('eventcategories as cat','cat.id','=','eventcategories.category_parent_id')->orderby('id', 'asc')
//     ->join('users','users.id','eventcategories.category_createdby')->orderby('id', 'asc')
//     ->paginate(50);
//     $page_data['view_path'] ='user_suggestion.event_category';
//     return view('backend.index',$page_data);
//   }




    // event category 
    public function view_event_category()
    {
        $page_data['all_category'] = Eventcategory::withCount('events')
            ->with('parent:id,category_name') // fetch parent name also
            ->orderBy('id', 'asc')
            ->paginate(50);

        $page_data['view_path'] = 'event_category.index';
        return view('backend.index', $page_data);
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
        $pagecategory->category_slug = clean_slug($request->pagecategory);
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
        $pagecategory->category_slug = clean_slug($request->pagecategory);
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


      public function view_product_enquiry(){
        $page_data['enquiries'] =  DB::table('enquirymaster')->select('enquirymaster.*','cities.city_name','marketplaces.title')
        ->join('cities','cities.id','=','enquirymaster.cityid')
        ->join('marketplaces','marketplaces.id','enquirymaster.productid')
        ->orderby('enquirymaster.id', 'desc')
        ->paginate(50);
        $page_data['view_path'] ='product.enquiry';
        return view('backend.index',$page_data);
    }


  // product category 
   public function view_product_category(Request $request)
{
    $search = $request->input('search');

    $query = DB::table('categories')
        ->select(
            'categories.id',
            'categories.product_category_name',
            'categories.category_type',
            'cat.product_category_name as parent',
            DB::raw('COALESCE(cp.product_count, 0) as product_count'),
            DB::raw('COALESCE(vc.view_count, 0) as view_count'),
            DB::raw('COALESCE(ic.inquiry_count, 0) as inquiry_count')
        )
        ->leftJoin('categories as cat', 'cat.id', '=', 'categories.category_parent_id')
        ->leftJoin(DB::raw('(
            SELECT product_category_id, COUNT(*) as product_count
            FROM category_product
            GROUP BY product_category_id
        ) as cp'), 'categories.id', '=', 'cp.product_category_id')
        ->leftJoin(DB::raw('(
            SELECT cp.product_category_id, 
                   SUM(CASE WHEN m.view IS NOT NULL AND m.view != "" THEN JSON_LENGTH(m.view) ELSE 0 END) as view_count
            FROM category_product cp
            JOIN marketplaces m ON cp.product_id = m.id
            WHERE m.product_status = 2
            GROUP BY cp.product_category_id
        ) as vc'), 'categories.id', '=', 'vc.product_category_id')
        ->leftJoin(DB::raw('(
            SELECT cp.product_category_id, COUNT(e.id) as inquiry_count
            FROM category_product cp
            JOIN marketplaces m ON cp.product_id = m.id
            JOIN enquirymaster e ON m.id = e.productid
            WHERE m.product_status = 2
            GROUP BY cp.product_category_id
        ) as ic'), 'categories.id', '=', 'ic.product_category_id');

    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('categories.product_category_name', 'like', "%$search%")
              ->orWhere('categories.category_type', 'like', "%$search%")
              ->orWhere('cat.product_category_name', 'like', "%$search%");
        });
    }

    $all_category = $query->orderBy('categories.id', 'asc')->paginate(50);
    $all_category->appends(['search' => $search]);

    if ($request->ajax()) {
        $html = '';
        $i = ($all_category->currentPage() - 1) * $all_category->perPage();
        foreach ($all_category as $category) {
            $html .= '<tr>
                <td>' . (++$i) . '</td>
                <td>' . $category->category_type . '</td>
               <td><a href="' . route('admin.product') . '?category=' . $category->id . '">' . $category->product_category_name . '</a></td>
                <td>' . ($category->product_count ?? 0) . '</td>
                <td>' . ($category->parent ?? '-') . '</td>
                <td>' . ($category->view_count ?? 0) . '</td>
                <td>' . ($category->inquiry_count ?? 0) . '</td>
                <td class="text-center">
                    <div class="adminTable-action me-auto">
                        <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2" data-bs-toggle="dropdown">
                            Actions
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                            <li><a class="dropdown-item" href="' . route('admin.edit.product.category', $category->id) . '">Edit</a></li>
                            <li><a class="dropdown-item" onclick="return confirm(\'Are You Sure Want To Delete?\')" href="' . route('admin.delete.product.category', $category->id) . '">Delete</a></li>
                        </ul>
                    </div>
                </td>
            </tr>';
        }

        $pagination = $all_category->links()->render();

        return response()->json(['table' => $html, 'pagination' => $pagination]);
    }

    return view('backend.index', [
        'all_category' => $all_category,
        'view_path' => 'product_category.index'
    ]);
}


    // product category 
   public function viewProductCategories(Request $request)
{
    $search = $request->input('search');

    $query = DB::table('categories')
        ->select(
            'categories.id',
            'categories.product_category_name',
            'categories.category_type',
            'cat.product_category_name as parent',
            DB::raw('COALESCE(cp.product_count, 0) as product_count'),
            DB::raw('COALESCE(vc.view_count, 0) as view_count'),
            DB::raw('COALESCE(ic.inquiry_count, 0) as inquiry_count')
        )
        ->leftJoin('categories as cat', 'cat.id', '=', 'categories.category_parent_id')
        ->leftJoin(DB::raw('(
            SELECT product_category_id, COUNT(*) as product_count
            FROM category_product
            GROUP BY product_category_id
        ) as cp'), 'categories.id', '=', 'cp.product_category_id')
        ->leftJoin(DB::raw('(
            SELECT cp.product_category_id, 
                   SUM(CASE WHEN m.view IS NOT NULL AND m.view != "" THEN JSON_LENGTH(m.view) ELSE 0 END) as view_count
            FROM category_product cp
            JOIN marketplaces m ON cp.product_id = m.id
            WHERE m.product_status = 2
            GROUP BY cp.product_category_id
        ) as vc'), 'categories.id', '=', 'vc.product_category_id')
        ->leftJoin(DB::raw('(
            SELECT cp.product_category_id, COUNT(e.id) as inquiry_count
            FROM category_product cp
            JOIN marketplaces m ON cp.product_id = m.id
            JOIN enquirymaster e ON m.id = e.productid
            WHERE m.product_status = 2
            GROUP BY cp.product_category_id
        ) as ic'), 'categories.id', '=', 'ic.product_category_id');

    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('categories.product_category_name', 'like', "%$search%")
              ->orWhere('categories.category_type', 'like', "%$search%")
              ->orWhere('cat.product_category_name', 'like', "%$search%");
        });
    }

    $all_category = $query->orderBy('categories.id', 'asc')->paginate(50);
    $all_category->appends(['search' => $search]);

    if ($request->ajax()) {
        $html = '';
        $i = ($all_category->currentPage() - 1) * $all_category->perPage();
        foreach ($all_category as $category) {
            $html .= '<tr>
                <td>' . (++$i) . '</td>
                <td>' . $category->category_type . '</td>
               <td><a href="' . route('admin.product') . '?category=' . $category->id . '">' . $category->product_category_name . '</a></td>
                <td>' . ($category->product_count ?? 0) . '</td>
                <td>' . ($category->parent ?? '-') . '</td>
                <td>' . ($category->view_count ?? 0) . '</td>
                <td>' . ($category->inquiry_count ?? 0) . '</td>
                <td class="text-center">
                    <div class="adminTable-action me-auto">
                        <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2" data-bs-toggle="dropdown">
                            Actions
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                            <li><a class="dropdown-item" href="' . route('admin.edit.product.category', $category->id) . '">Edit</a></li>
                            <li><a class="dropdown-item" onclick="return confirm(\'Are You Sure Want To Delete?\')" href="' . route('admin.delete.product.category', $category->id) . '">Delete</a></li>
                        </ul>
                    </div>
                </td>
            </tr>';
        }

        $pagination = $all_category->links()->render();

        return response()->json(['table' => $html, 'pagination' => $pagination]);
    }

    return view('backend.index', [
        'all_category' => $all_category,
        'view_path' => 'product_category.index'
    ]);
}




public function view_user_suggest_product_category()
{
    $categories = Category::with([
        'parent:id,product_category_name',
        'creator:id,name,email',
        'productCategories.marketplace' => function($q) {
            $q->with([
                'page' => function($q2) {
                    $q2->with([
                        'city:id,city_slug',
                        'area:id,area_slug',
                        'categories:id,category_slug'
                    ]);
                }
            ]);
        }
    ])
    ->whereDoesntHave('children') // ✅ Only leaf categories
    ->whereHas('creator')         // ✅ Only if the creator (user) exists
    ->orderBy('id', 'asc')
    ->paginate(50);

    // Add view and inquiry counts to each category
    foreach ($categories as $category) {
        // Calculate view count
        $viewCount = DB::table('category_product')
            ->join('marketplaces', 'marketplaces.id', '=', 'category_product.product_id')
            ->where('category_product.product_category_id', $category->id)
            ->where('marketplaces.product_status', 2)
            ->whereNotNull('marketplaces.view')
            ->where('marketplaces.view', '!=', '')
            ->sum(DB::raw('JSON_LENGTH(marketplaces.view)'));

        // Calculate inquiry count
        $inquiryCount = DB::table('category_product')
            ->join('marketplaces', 'marketplaces.id', '=', 'category_product.product_id')
            ->join('enquirymaster', 'enquirymaster.productid', '=', 'marketplaces.id')
            ->where('category_product.product_category_id', $category->id)
            ->where('marketplaces.product_status', 2)
            ->count();

        $category->view_count = $viewCount;
        $category->inquiry_count = $inquiryCount;
    }

    return view('backend.index', [
        'all_category' => $categories,
        'view_path' => 'user_suggestion.product_category',
    ]);
}







    // public function view_user_suggest_product_category(){
    //     $page_data['all_category'] =  DB::table('categories')->select('categories.id','categories.product_category_name','cat.product_category_name as parent',
    //     'users.id as user_id','users.name','users.email')
    //     ->leftjoin('categories as cat','cat.id','=','categories.category_parent_id')
    //     ->join('users','users.id','=','categories.product_category_createdby')
    //     ->orderby('id', 'asc')
    //     ->paginate(50);
    //     //print_r( $page_data['all_category']);exit;
    //     //$page_data['all_category'] = Category::paginate(50);
    //     $page_data['view_path'] ='user_suggestion.product_category';
    //     return view('backend.index',$page_data);
    // }

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
        $productcategory->category_type = $request->category_type;
        $productcategory->product_category_name = $request->productcategory;
        $productcategory->product_category_slug = clean_slug($request->productcategory);
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
public function update_product_category(Request $request, $id)
{
    $category = Category::findOrFail($id);

    // Only apply unique validation if name has changed
    $rules = [
        'productcategory' => ['required', 'max:50', 'string'],
        'category_type' => ['required', 'string'],
        'category' => ['required', 'integer'],
    ];

    if (trim($request->productcategory) !== trim($category->product_category_name)) {
        $rules['productcategory'][] = Rule::unique('categories', 'product_category_name')->ignore($id, 'id');
    }

    $validated = $request->validate($rules);

    $category->category_type = $request->category_type;
    $category->product_category_name = $request->productcategory;
    $category->product_category_slug = clean_slug($request->productcategory);
    $category->category_parent_id = $request->category;

    $category->save();

    flash()->addSuccess('Product Category has been updated successfully!');
    return redirect()->route('admin.view.product.category');
}

    // public function  update_product_category(Request $request,$id){
    //     $validated = $request->validate([
    //        'productcategory' => 'required|max:255|string|unique:categories,product_category_name,' . $id . ',id',
    //     ]);
    //     $productcategory = Category::find($id);
    //     $productcategory->category_type = $request->category_type;
    //     $productcategory->product_category_name = $request->productcategory;
    //     $productcategory->product_category_slug =str_slug($request->productcategory);
    //     $productcategory->category_parent_id = $request->category;
    //     $done = $productcategory->save();
    //     if($done){
    //         flash()->addSuccess('Product Category has been updated successfully!');
    //     }
    //     return redirect()->route('admin.view.product.category');
    // }

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
   public function view_blog_category()
{
    $page_data['all_category'] = \App\Models\Blogcategory::withCount('blogs')
    ->leftJoin('blogcategories as cat', 'cat.id', '=', 'blogcategories.category_parent_id')
    ->addSelect('blogcategories.*', 'cat.category_name as parent')
    ->orderBy('blogcategories.id', 'asc')
    ->paginate(50);


    $page_data['view_path'] = 'blog_category.index';
    return view('backend.index', $page_data);
}

public function blogCategoryAjax(Request $request)
{
    $query = Blogcategory::withCount('blogs')
        ->leftJoin('blogcategories as cat', 'cat.id', '=', 'blogcategories.category_parent_id')
        ->addSelect('blogcategories.*', 'cat.category_name as parent');

    // Search filter
    if ($request->search['value'] ?? false) {
        $search = $request->search['value'];
        $query->where(function ($q) use ($search) {
            $q->where('blogcategories.category_name', 'like', "%$search%")
              ->orWhere('cat.category_name', 'like', "%$search%");
        });
    }

    $totalFiltered = $query->count();

    // Ordering
    $columns = ['blogcategories.id', 'blogcategories.category_name', 'cat.category_name', 'blogs_count'];
    if ($request->order[0] ?? false) {
        $columnIndex = $request->order[0]['column'];
        $direction = $request->order[0]['dir'];
        $query->orderBy($columns[$columnIndex] ?? 'blogcategories.id', $direction);
    }

    // Pagination
    $start = $request->start ?? 0;
    $length = $request->length ?? 10;

    $data = $query->skip($start)->take($length)->get();

    // Prepare data
    $jsonData = $data->map(function ($item, $index) use ($start) {
        return [
            'sl_no' => $start + $index + 1,
            'category_name' => $item->category_name,
            'parent' => $item->parent ?? 'None',
            'blogs_count' => $item->blogs_count ?? 0,
            'actions' => '
                <div class="adminTable-action me-auto">
                    <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                        <li>
                            <a class="dropdown-item" href="' . route('admin.edit.blog.category', $item->id) . '">Edit</a>
                        </li>
                        <li>
                            <a class="dropdown-item" onclick="return confirm(\'Are you sure want to delete?\')" 
                                href="' . route('admin.delete.blog.category', $item->id) . '">Delete</a>
                        </li>
                    </ul>
                </div>
            '
        ];
    });

    return response()->json([
        'draw' => intval($request->draw),
        'recordsTotal' => Blogcategory::count(),
        'recordsFiltered' => $totalFiltered,
        'data' => $jsonData,
    ]);
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
        $blogcategories->category_slug = clean_slug($request->blogcategory);
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
        $blogcategories->category_slug = clean_slug($request->blogcategory);
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

    // function product(){
    //     if(isset($_GET['delete']) && $_GET['delete'] == 'yes' && isset($_GET['id'])){
    //         Marketplace::find($_GET['id'])->delete();
    //         DB::table('category_product')->where('product_id',$_GET['id'])->delete();
    //         flash()->addSuccess('Product deleted successfully');
    //         return redirect()->back();
    //     }

    //     $page_data['view_path'] ='product.list';
    //     $page_data['products'] =DB::table('marketplaces')->select('marketplaces.id','marketplaces.title','users.email','users.name','marketplaces.user_id')
    //     ->join('category_product','category_product.product_id','marketplaces.id')
    //     ->join('users','users.id','marketplaces.user_id')
    //     ->distinct()
    //                             ->paginate(50);
    //     return view('backend.index', $page_data);

    // }


//     public function product(Request $request)
// {
//     if ($request->get('delete') == 'yes' && $request->get('id')) {
//         Marketplace::find($request->get('id'))->delete();
//         DB::table('category_product')->where('product_id', $request->get('id'))->delete();
//         flash()->addSuccess('Product deleted successfully');
//         return redirect()->back();
//     }

//     // Filters
//     $category = $request->input('category');
//     $city = $request->input('city');
//     $area = $request->input('area');

//     $query = Marketplace::with([
//     'user',
//     'productCategories',
//     'page.city',
//     'page.area',
//     'page.pageCategories', // <- ye important hai!
// ])
//         ->whereHas('productCategories');

//     if ($category) {
//         $query->whereHas('productCategories', function ($q) use ($category) {
//             $q->where('product_category_id', $category);
//         });
//     }

//     if ($city) {
//         $query->whereHas('page.city', function ($q) use ($city) {
//             $q->where('id', $city);
//         });
//     }

//     if ($area) {
//         $query->whereHas('page.area', function ($q) use ($area) {
//             $q->where('id', $area);
//         });
//     }

//     $products = $query->paginate(50);

//     // Filters dropdowns: only those used in products
//     $categories = Category::whereHas('marketplaces')->get();
//     $cities = City::whereHas('pages.products')->get();
//     $areas = Area::whereHas('pages.products')->get();

//     $page_data = [
//         'view_path' => 'product.list',
//         'products' => $products,
//         'categories' => $categories,
//         'cities' => $cities,
//         'areas' => $areas,
//     ];

//     return view('backend.index', $page_data);
// }

public function product(Request $request)
{
    if ($request->get('delete') == 'yes' && $request->get('id')) {
        Marketplace::find($request->get('id'))->delete();
        DB::table('category_product')->where('product_id', $request->get('id'))->delete();
        flash()->addSuccess('Product deleted successfully');
        return redirect()->back();
    }

    $category = $request->input('category');
    $city = $request->input('city');
    $area = $request->input('area');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $query = Marketplace::with([
        'user',
        'productCategories',
        'page.city',
        'page.area',
        'page.pageCategories',
    ])->whereHas('productCategories');

    if ($category) {
        $query->whereHas('productCategories', function ($q) use ($category) {
            $q->where('product_category_id', $category);
        });
    }

    if ($city) {
        $query->whereHas('page.city', function ($q) use ($city) {
            $q->where('id', $city);
        });
    }

    if ($area) {
        $query->whereHas('page.area', function ($q) use ($area) {
            $q->where('id', $area);
        });
    }

    if ($startDate) {
        $query->whereDate('created_at', '>=', $startDate);
    }

    if ($endDate) {
        $query->whereDate('created_at', '<=', $endDate);
    }

    $products = $query->orderBy('created_at', 'desc')->paginate(50);

    $categories = Category::whereHas('marketplaces')->get();
    $cities = City::whereHas('pages.products')->get();
    $areas = Area::whereHas('pages.products')->get();

    return view('backend.index', [
        'view_path' => 'product.list',
        'products' => $products,
        'categories' => $categories,
        'cities' => $cities,
        'areas' => $areas,
    ]);
}


public function productAjax(Request $request)
{
    $query = Marketplace::with(['user', 'city', 'area',
    'productCategories',
    'page.city', 'page.area', 'page.item.category',
    'item.category']);

    if ($request->category) {
        $query->whereHas('productCategories', fn($q) => $q->where('product_category_id', $request->category));
    }
    if ($request->city) {
        $query->whereHas('page.city', fn($q) => $q->where('id', $request->city));
    }
    if ($request->area) {
        $query->whereHas('page.area', fn($q) => $q->where('id', $request->area));
    }

    return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('title', fn($row) => $row->title)
        ->addColumn('owner', function($row) {
            $name = $row->user->name ?? '';
            $email = $row->user->email ?? '';
            $link = route('user.profile.view', $row->user_id);
            return "<a href='$link' target='_blank'>$name</a><br><small>$email</small>";
        })
        ->addColumn('action', function ($row) {
            $edit = route('admin.product.edit', $row->id);
            $delete = route('admin.product', ['delete' => 'yes', 'id' => $row->id]);
            return <<<HTML
                <div class="adminTable-action me-auto">
                    <button type="button" class="btn btn-sm btn-dark dropdown-toggle" data-bs-toggle="dropdown">
                        Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="$edit">Edit</a></li>
                        <li><a class="dropdown-item text-danger" onclick="return confirm('Are You Sure Want To Delete?')" href="$delete">Delete</a></li>
                    </ul>
                </div>
            HTML;
        })
        ->rawColumns(['owner', 'action'])
        ->make(true);
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

    public function searchPages(Request $request)
    {
        $query = Page::with(['city', 'area', 'state', 'categories', 'getUser']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                ->orWhereHas('city', fn($q) => $q->where('city_name', 'like', "%$search%"))
                ->orWhereHas('area', fn($q) => $q->where('area_name', 'like', "%$search%"))
                ->orWhereHas('getUser', fn($q) => $q->where('name', 'like', "%$search%")
                                                    ->orWhere('email', 'like', "%$search%"))
                ->orWhereHas('categories', fn($q) => $q->where('category_name', 'like', "%$search%"));
            });
        }

        return response()->json($query->limit(100)->get());
    }


    public function searchPendingages(Request $request)
    {
        $query = Page::with(['city', 'area', 'state', 'categories', 'getUser']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                ->orWhereHas('city', fn($q) => $q->where('city_name', 'like', "%$search%"))
                ->orWhereHas('area', fn($q) => $q->where('area_name', 'like', "%$search%"))
                ->orWhereHas('getUser', fn($q) => $q->where('name', 'like', "%$search%")
                                                    ->orWhere('email', 'like', "%$search%"))
                ->orWhereHas('categories', fn($q) => $q->where('category_name', 'like', "%$search%"));
            });
        }

        return response()->json($query->where('item_status',1)->limit(100)->get());
    }


public function toggleVerified(Request $request)
{
    $page = Page::find($request->id);

    if ($page) {
        $page->is_verified_seller = $request->is_verified_seller;
        $page->save();

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 404);
}


    public function pages(Request $request)
    {

        if ($request->filled('delete') && $request->delete === 'yes' && $request->filled('id')) {
            $page = Page::find($request->id);
            if ($page) {
                // Detach all categories linked to this page
                $page->pageCategories()->detach();
        
                // Then delete the page
                $page->delete();
        
                session()->flash('success', 'Page and related category links deleted successfully.');
            } else {
                session()->flash('error', 'Page not found.');
            }
            return redirect()->route('admin.page');
        }
        
        $query = Page::with(['city', 'area', 'state', 'categories', 'getUser']);

        // Filter by City
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // Filter by Area
        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        // Filter by Categories (Multiple)
        if ($request->filled('category_ids')) {
            $query->whereHas('pageCategories', function($q) use ($request) {
                $q->where('category_id', $request->category_ids); // multiple categories filter
            });
        }

        // Filter by Date Range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $page_data['cities'] = City::select('id', 'city_name')->get();
        $page_data['areas'] =[]; // Assuming you have an Area model
        $page_data['categories'] = Pagecategory::select('id', 'category_name')->get();
        $page_data['pages'] = $query->where('item_status',2)->latest()->paginate(50)->appends($request->except('page'));

        $page_data['view_path'] = 'page.list';

        return view('backend.index', $page_data);
    }


    public function pendingpages(Request $request)
    {

        if ($request->filled('delete') && $request->delete === 'yes' && $request->filled('id')) {
            $page = Page::find($request->id);
            if ($page) {
                // Detach all categories linked to this page
                $page->pageCategories()->detach();
        
                // Then delete the page
                $page->delete();
        
                session()->flash('success', 'Page and related category links deleted successfully.');
            } else {
                session()->flash('error', 'Page not found.');
            }
            return redirect()->route('admin.page');
        }
        
        $query = Page::with(['city', 'area', 'state', 'categories', 'getUser']);

        // Filter by City
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // Filter by Area
        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        // Filter by Categories (Multiple)
        if ($request->filled('category_ids')) {
            $query->whereHas('pageCategories', function($q) use ($request) {
                $q->where('category_id', $request->category_ids); // multiple categories filter
            });
        }

        // Filter by Date Range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $page_data['cities'] = City::select('id', 'city_name')->get();
        $page_data['areas'] =[]; // Assuming you have an Area model
        $page_data['categories'] = Pagecategory::select('id', 'category_name')->get();
        $page_data['pages'] = $query->where('item_status',1)->latest()->paginate(100)->appends($request->except('page'));

        $page_data['view_path'] = 'page.pending_page';

        return view('backend.index', $page_data);
    }


    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $ids = $request->ids;

        if (!$action || !$ids || !is_array($ids)) {
            return response()->json(['message' => 'Invalid request.'], 400);
        }

        $pages = Page::whereIn('id', $ids)->get();

        foreach ($pages as $page) {
            if ($action === 'approve') {
                $page->item_status = 2; // or whatever your approved status is
            } elseif ($action === 'disapprove') {
                $page->item_status = 1; // or whatever your disapproved status is
            }
            $page->save();
        }

        return response()->json(['message' => 'Pages updated successfully.']);
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

    function videos(){
        $videos = DB::table('videos')
        ->join('users', 'videos.user_id', '=', 'users.id')
        ->select('videos.*', 'users.name as user_name', 'users.email')
        ->get(); // Fetch videos with user details
        $page_data['videos'] = $videos;
        $page_data['view_path'] = 'video.index';
        return view('backend.index', $page_data);
    }

   public function approve(Request $request)
{
    $video = Video::find($request->id);
    if ($video) {
        $video->approve_status = $request->approve_status;
        $video->save();

        return response()->json(['success' => true, 'status' => $video->approve_status]);
    }
    return response()->json(['success' => false]);
}

public function approveMultiple(Request $request)
{
    Video::whereIn('id', $request->ids)->update(['approve_status' => 2]);
    return response()->json(['success' => true]);
}

public function approveAll(Request $request)
{
    Video::query()->update(['approve_status' => 2]);
    return response()->json(['success' => true]);
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
        $page_data['countries'] = DB::table('countries')->select('countries.*')
        ->where('id' , 101)->get();
      $page_data['listing'] = \App\Models\IncompleteListing::create([
        'user_id' => auth()->id(),
        'data' => [],
    ]);
    $page_data['all_cities'] = \App\Helpers\CityHelper::getActiveCities();
    $page_data['view_path'] = 'frontend.pages.create_page';
    return view('frontend.form_index', $page_data);
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
       
        $page_data['all_service_city'] = DB::table('cities')
        ->select('cities.*')
        ->where(function($query) use ($page_data) {
            $cityIds = explode(',', $page_data['page_details']->service_offered_state); // Convert to array
            foreach ($cityIds as $cityId) {
                $query->orWhereRaw("FIND_IN_SET(?, state_id)", [$cityId]);
            }
        })
        ->get();


        $page_data['all_service_areas'] = DB::table('areas')
        ->select('areas.*')
        ->where(function($query) use ($page_data) {
            $cityIds = explode(',', $page_data['page_details']->service_offered_city); // Convert to array
            foreach ($cityIds as $cityId) {
                $query->orWhereRaw("FIND_IN_SET(?, city_id)", [$cityId]);
            }
        })
        ->get();


        

       // print_r($page_data['page']->service_offered_city);exit;
        $page_data['parent'] =  DB::table('pagecategories')
        ->where('pagecategories.category_parent_id',null)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();

        $page_data['page_faq'] =  DB::table('pag_faq')
        ->where('page_id',$id)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();
        $page_data['countries'] = DB::table('countries')->select('countries.*')
        ->where('id' , 101)->get();

        $page_data['media'] = PageMedia::where('page_id', $id)->get();
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
            'parent' => 'required',
            'category' => 'required',
            'item_phone' => ['nullable', 'regex:/^(\+?\d{1,3}[-. ]?)?\d{10}$/'],
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

        if ($request->Proof_of_Ownership && !empty($request->Proof_of_Ownership)) {

            $proof_of_ownership_file_name = FileUploader::upload($request->image,'public/storage/pages/logo', 250);
           
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

        $page->product_categories_ids = $product_categories_ids;
        
        $page->why_visit_us = $request->visitus;
        $page->our_story = $request->our_story;
        $page->year_of_establishment = $request->yrofest;
        $page->service_offeres_areas_ids = $service_offeres_areas_ids;

        $page->description = $request->description;
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
            foreach ($multiSelectArray as $category_id) 
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
        $faqs = $request->input('faqs', []);
            foreach ($faqs as $faq) {
                $page_faq_count=DB::table('pag_faq')->select('pag_faq.id')
                ->where('pag_faq.question',$faq['question'])
                ->where('pag_faq.answer',$faq['answer'])
                ->count();
                if($page_faq_count==0){
                    if(!empty($faq['question']) && !empty($faq['answer']))
                    {
                    $data=array(
                        'question'=>$faq['question'] ?? null,
                        'answer'=>$faq['answer'] ?? null,
                        "page_id"=>$page->id
                    );
                    $row=DB::table('pag_faq')->insertGetId($data);
                    }
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
        ->leftjoin('cities','cities.id','groups.city_id')
        ->leftjoin('areas','areas.id','groups.area_id')
        ->leftjoin('states','states.id','groups.state_id')
        ->join('group_category','group_category.group_id','groups.id')
        ->join('users','users.id','groups.user_id')
        ->distinct('groups.id')->orderBy('id','DESC')->paginate('10');

        //$page_data['blogs'] = Blog::get();

        return view('backend.index', $page_data);
    }

    function group_create(){
        $page_data['parent'] =  DB::table('groupcategories')
        ->where('groupcategories.category_parent_id',null)
        // ->orWhereNull('pagecategories.category_parent_id')
        ->get();
       // print_r( $page_data['parent'] );exit;
        $page_data['printable_categories'] =  DB::table('groupcategories')->where('category_parent_id',null)
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


            foreach ($multiSelectArray as $category_id) 
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
        $page_data['printable_categories'] =  DB::table('groupcategories')->where('category_parent_id',null)
        ->get();
        $page_data['all_states'] = DB::table('states')->select('states.*')
        ->where('country_id' , 101)->get();
        $page_data['all_cities'] = DB::table('cities')->select('cities.*')
        ->where('state_id' , $page_data['group']->state_id)->get();
        $page_data['all_areas'] = DB::table('areas')->select('areas.*')
        ->where('city_id' , $page_data['group']->city_id)->get();

        $page_data['parent'] =  DB::table('groupcategories')
        ->where('groupcategories.category_parent_id',null)
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

            foreach ($multiSelectArray as $category_id) 
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
        ->leftjoin('cities','cities.id','blogs.city_id')
        ->leftjoin('areas','areas.id','blogs.area_id')
        ->leftjoin('states','states.id','blogs.state_id')
        ->join('blog_category','blog_category.blog_id','blogs.id')
        ->join('users','users.id','blogs.user_id')
        ->distinct('blogs.id')->orderBy('id','DESC')->get();

        //$page_data['blogs'] = Blog::get();

        return view('backend.index', $page_data);
    }

  
  
  

  function blog_create(){
    $page_data['parent'] = DB::table('blogcategories')
        ->whereNull('category_parent_id')
        ->get();

    $page_data['printable_categories'] = DB::table('blogcategories')
        ->whereNull('category_parent_id')
        ->get();

    $page_data['all_states'] = DB::table('states')
        ->where('country_id', 101)
        ->get();

    $page_data['listing'] = DB::table('pages')
        ->join('page_category','page_category.page_id','pages.id')
        ->where('pages.item_status',2)
        ->distinct('pages.id')
        ->orderBy('pages.id','DESC')
        ->limit(50)              // ✅ IMPORTANT: safety limit
        ->get();

    $page_data['countries'] = DB::table('countries')
        ->where('id', 101)
        ->get();

    // ✅ ADD THIS LINE (THIS IS WHAT WAS MISSING)
    $page_data['page_categories'] = \App\Models\Pagecategory::select(
        'id',
        'category_name'
    )->get();

    $page_data['view_path'] = 'blog.create';

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

        $page_data['listing']= DB::table('pages')->select('pages.*')
        ->join('page_category','page_category.page_id','pages.id')
        ->where('pages.item_status',2)
        ->distinct('pages.id')
        ->orderBy('pages.id','DESC')->get();

        $page_data['countries'] = DB::table('countries')->select('countries.*')
        ->where('id' , 101)->get();

        $page_data['view_path'] ='blog.edit';
        return view('backend.index', $page_data);
    }

    function blog_created(Request $request){

        if($request->category == 'Select a category'){
            flash()->addError('Please select a category');
            return redirect()->back()->withInput();
        }

        $request->validate([
            'author' => 'required|max:255',
            'title' => 'required|max:255',
            'category' => 'required',
            'country'=> 'required|not_in:0',
           
            'state' => 'nullable',
            'city' => 'nullable',
            'area' => 'nullable',
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
        $data['state_id'] = empty($request->state) || $request->state == 0 ? null : $request->state;
        $data['city_id'] = empty($request->city) || $request->city == 0 ? null : $request->city;
        $data['area_id'] = empty($request->area) || $request->area == 0 ? null : $request->area;
        $data['blog_status'] = $request->item_status;

        $data['auther_name']= $request->author;
        $data['publication_date']= $request->publication_date;
        $data['country_id']= $request->country;
        $data['list_id']= $request->List;
        $data['publication_status']= $request->status;

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

    public function page_sync(){

       // Step 1: Get latest db_primary_id from pages table
    $lastId = DB::table('pages')->max('db_primary_id') ?? 0;

    // Step 2: Call the API with lastId
    $apiUrl = 'https://db.citydealsbazar.com/api/sync/items?id=' . $lastId;
    $response = Http::get($apiUrl);

    //print_r($response);exit;

    if ($response->ok()) {
        $items = $response->json()['data'];

        //print_r($items);exit;

        if (!empty($items)) {
            // Step 3: Chunk and dispatch to queue
            collect($items)->chunk(50)->each(function ($chunk) {
                StoreItemsFromApi::dispatch($chunk->toArray());
            });

            // Step 4: Optionally run queue instantly
            //Artisan::call('queue:work');

            flash()->addSuccess('Data fetched and queued.');
            //return response()->json(['status' => 'Success', 'message' => 'Data fetched and queued.']);
        } else {
            flash()->addSuccess('No new items found.');
            //return response()->json(['status' => 'No new items found']);
        }
    }
    //flash()->addSuccess('API call failed.');
    return redirect(route('admin.page'));
    }

    function blog_updated(Request $request,$id){

        if($request->category == 'Select a category'){
            flash()->addError('Please select a category');
            return redirect()->back()->withInput();
        }
        
        $request->validate([
           'author' => 'required|max:255',
            'title' => 'required|max:255',
            'category' => 'required',
            'country'=> 'required|not_in:0',
           'state' => 'nullable',
            'city' => 'nullable',
            'area' => 'nullable',
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
         $blog->state_id = empty($request->state) || $request->state == 0 ? null : $request->state;
        $blog->city_id = empty($request->city) || $request->city == 0 ? null : $request->city;
        $blog->area_id = empty($request->area) || $request->area == 0 ? null : $request->area;
        $blog->blog_status = $request->item_status;


        $blog->auther_name = $request->author;
        $blog->publication_date = $request->publication_date;
        $blog->country_id = $request->country;
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

public function server_side_users_data(Request $request)
{
    $data = [];
    $columns = ['id', 'id', 'name', 'email', 'city', 'created_at', 'status', 'id'];

    $limit = $request->length;
    $start = $request->start;
    $column_index = $columns[$request->order[0]['column']];
    $dir = $request->order[0]['dir'];
    $search = $request->search['value'];

    $baseQuery = User::with('city')
        ->where('user_role', '!=', 'admin')
        ->select('id', 'name', 'email', 'photo', 'status', 'email_verified_at', 'created_at', 'city_id');

    // ✅ Apply filters if present
    if ($request->filled('start_date')) {
        $baseQuery->whereDate('created_at', '>=', $request->start_date);
    }
    if ($request->filled('end_date')) {
        $baseQuery->whereDate('created_at', '<=', $request->end_date);
    }
    if ($request->filled('state_id')) {
        $baseQuery->where('state_id', $request->state_id);
    }
    if ($request->filled('city_id')) {
        $baseQuery->where('city_id', $request->city_id);
    }
    if ($request->filled('area_id')) {
        $baseQuery->where('area_id', $request->area_id);
    }

    $total_number_of_row = $baseQuery->count();
    $filtered_number_of_row = $total_number_of_row;

    // ✅ Search
    if (!empty($search)) {
        $baseQuery = $baseQuery->where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        });
        $filtered_number_of_row = $baseQuery->count();
    }

    $users = $baseQuery->skip($start)
        ->take($limit)
        ->orderBy($column_index, $dir)
        ->get();

    foreach ($users as $key => $user) {
        $photo = '<img src="' . User::get_user_image($user->photo) . '" alt="" height="50" width="50" class="img-fluid rounded-circle img-thumbnail">';
        $emailStatus = is_null($user->email_verified_at) ? '<br><span class="badge bg-danger">' . get_phrase('Unverified') . '</span>' : '';
        $name = $user->name . '<small>' . $emailStatus . '</small>';
        $email = $user->email;
        $cityName = optional($user->city)->city_name ?? '-';
        $statusText = $user->status != 1
            ? '<span class="badge bg-danger">' . get_phrase('Disabled') . '</span>'
            : '<span class="badge bg-success">' . get_phrase('Active') . '</span>';
        $status_btn = $user->status != 1
            ? '<a class="dropdown-item" onclick="return confirm(\'Are You Sure?\')" href="' . route('admin.user.status', $user->id) . '">' . get_phrase('Active') . '</a>'
            : '<a class="dropdown-item" onclick="return confirm(\'Are You Sure?\')" href="' . route('admin.user.status', $user->id) . '">' . get_phrase('Deactive') . '</a>';
        $action = '
        <div class="adminTable-action me-auto">
            <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2" data-bs-toggle="dropdown" aria-expanded="false">
                ' . get_phrase("Actions") . '
            </button>
            <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                <li><a class="dropdown-item" href="' . route('admin.user.edit', $user->id) . '">' . get_phrase('Edit') . '</a></li>
                <li>' . $status_btn . '</li>
                <li><a class="dropdown-item" onclick="return confirm(\'Are You Sure Want To Delete?\')" href="' . route('admin.user.delete', $user->id) . '">' . get_phrase('Delete') . '</a></li>
            </ul>
        </div>';

        $nestedData = [
            'key' => ++$key,
            'photo' => $photo,
            'name' => $name,
            'email' => $email,
            'city' => $cityName,
            'date' => $user->created_at->format('d-m-Y'), // ✅ make sure this exists
            'status' => $statusText,
            'action' => $action . '<script>$("a, i").tooltip();</script>',
        ];

        $data[] = $nestedData;
    }

    $json_data = [
        "draw" => intval($request->draw),
        "recordsTotal" => intval($total_number_of_row),
        "recordsFiltered" => intval($filtered_number_of_row),
        "data" => $data,
    ];

    return response()->json($json_data);
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


    function create_payment_gateway(){

        $page_data['currencies'] = DB::table('currencies')->get();
        $page_data['view_path'] = 'payment.create_gateway';
        return view('backend.index', $page_data);
    }

   

public function store_payment_gateway(Request $request)
{
    // Validate input
    $request->validate([
        'title'      => 'required|string|max:255',
        'currency'   => 'required|string|max:10',
        'api_key'    => 'required|string',
        'secret_key' => 'required|string',
        'test_mode'  => 'required|in:0,1',
        'status'     => 'required|in:0,1',
    ]);

    // Prepare data for insertion
    $data = [
        'identifier' => Str::slug($request->title), // Generates a slug for identifier
        'currency'   => $request->currency,
        'title'      => $request->title,
        'description'=> $request->description ?? '', // Default to empty if not provided
        'keys'       => json_encode([
            'api_key'    => $request->api_key,
            'secret_key' => $request->secret_key
        ]),
        'test_mode'  => $request->test_mode,
        'status'     => $request->status
    ];

    // Insert into database
    Payment_gateway::create($data);

    return redirect()->route('admin.settings.payment')->with('success', 'Payment Gateway added successfully');
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



    public function ticket_list(Request $request)
{
    $query = Ticket::latest()->with('comments'); // Load comments with tickets

    // Filtering logic
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('updated_at', [$request->from_date, $request->to_date]);
    } elseif ($request->filled('from_date')) {
        $query->whereDate('updated_at', '>=', $request->from_date);
    } elseif ($request->filled('to_date')) {
        $query->whereDate('updated_at', '<=', $request->to_date);
    }

    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Paginate results
    $page_data['tickets'] = $query->paginate(10);
    $page_data['from_date'] = $request->from_date;
    $page_data['to_date'] = $request->to_date;
    $page_data['priority'] = $request->priority;
    $page_data['status'] = $request->status;

    $page_data['view_path'] = 'tickets.index';

    return view('backend.index', $page_data);
}




    /**
     * Show a single ticket with details.
     */
    public function ticket_show(Ticket $ticket)
    {
        $page_data['ticket'] = $ticket;
        $page_data['view_path'] = 'tickets.show';
        return view('backend.index', $page_data);
    }

    /**
     * Update ticket status, add comments and upload screenshot.
     */
    public function ticket_update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status'        => 'required|in:Open,In Progress,Closed',
            'admin_comment' => 'nullable|string',
            'screenshot'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update the ticket status
        $ticket->update(['status' => $request->status]);

        // Handle Screenshot Upload
        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('screenshots', 'public');
        }

        // Store admin comment as a new entry in ticket_comments table
        if ($request->admin_comment || $screenshotPath) {
            TicketComment::create([
                'ticket_id' => $ticket->id,
                'admin_id'  => auth()->id(), // Assuming admin is logged in
                'comment'   => $request->admin_comment,
                'screenshot' => $screenshotPath,
            ]);
        }

        return redirect()->route('admin.tickets.list')->with('success', 'Ticket updated successfully.');
    }



    public function state_index(Request $request)
{
    $query = State::query();

    // Search Filter for State Name
    if ($request->has('search') && !empty($request->search)) {
        $query->where('state_name', 'LIKE', '%' . $request->search . '%');
    }

    $states = $query->orderBy('id', 'desc')->paginate(10);

    // Pass data to the view
    return view('backend.index', [
        'states' => $states,
        'view_path' => 'states.index',
        'search' => $request->search
    ]);
}


    // Show create form
    public function state_create()
    {
        $page_data['view_path'] = 'states.create';
        return view('backend.index', $page_data);
        //return view('admin.states.create');
    }

    // Store new state
    public function state_store(Request $request)
    {
        $request->validate([
            'state_name' => 'required|unique:states,state_name',
            'state_abbr' => 'required',
        ]);

        State::create([
            'country_id' => 101, // Change this to dynamic if needed
            'state_name' => $request->state_name,
            'state_abbr' => $request->state_abbr,
            'state_slug' => strtolower(str_replace(' ', '-', $request->state_name)),
            'state_country_abbr' => 'IN',
        ]);

        return redirect()->route('admin.state')->with('success', 'State Added Successfully');
    }

    // Show edit form
    public function state_edit($id)
    {
        $state = State::findOrFail($id);

        $page_data['state'] = $state;
        $page_data['view_path'] = 'states.edit';
        return view('backend.index', $page_data);


        //return view('admin.states.edit', compact('state'));
    }

    // Update state
    public function state_update(Request $request, $id)
    {
        $request->validate([
            'state_name' => 'required|unique:states,state_name,' . $id,
            'state_abbr' => 'required',
        ]);

        $state = State::findOrFail($id);
        $state->update([
            'state_name' => $request->state_name,
            'state_abbr' => $request->state_abbr,
            'state_slug' => strtolower(str_replace(' ', '-', $request->state_name)),
            'state_country_abbr' => 'IN',
        ]);

        return redirect()->route('admin.state')->with('success', 'State Updated Successfully');
    }

    // Delete state
    public function state_destroy($id)
    {
        $state = State::findOrFail($id);
        $state->delete();

        return redirect()->route('admin.state')->with('success', 'State Deleted Successfully');
    }




    public function city_index(Request $request)
{
    $query = City::with('state')
        ->withCount('pages'); // Count the related pages

    // Apply city name filter
    if ($request->filled('city_name')) {
        $query->where('city_name', 'like', '%' . $request->city_name . '%');
    }

    // Apply state filter
    if ($request->filled('state_id')) {
        $query->where('state_id', $request->state_id);
    }

    // Handle sorting
    $sortBy = $request->get('sort_by', 'pages_count');
    $sortOrder = $request->get('sort_order', 'desc');
    
    // Validate sort_by parameter
    $allowedSortFields = ['city_name', 'pages_count', 'id', 'created_at'];
    if (!in_array($sortBy, $allowedSortFields)) {
        $sortBy = 'pages_count';
    }
    
    // Validate sort_order parameter
    if (!in_array($sortOrder, ['asc', 'desc'])) {
        $sortOrder = 'desc';
    }

    // Apply sorting
    $cities = $query->orderBy($sortBy, $sortOrder)->paginate(10);

    $states = State::all(); // For filter dropdown

    return view('backend.index', [
        'cities' => $cities,
        'states' => $states,
        'sort_by' => $sortBy,
        'sort_order' => $sortOrder,
        'view_path' => 'city.index'
    ]);
}



    public function city_create()
    {
        $states = State::orderBy('state_name')->get();
        //print_r($states);exit;
        $page_data['states'] =$states;
        $page_data['view_path'] = 'city.create';
        return view('backend.index', $page_data);
        //return view('cities.create');
    }

public function city_store(Request $request)
{
    $request->validate([
        'city_name' => 'required',
        'state_id' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        'city_about' => 'nullable|string',
    ]);

    $slug = Str::slug($request->city_name);
    $count = City::where('city_slug', $slug)->count();
    if ($count > 0) {
        $slug .= '-' . ($count + 1);
    }

    // Use your custom FileUploader
    $file_name = null;
    if ($request->hasFile('city_image')) {
        $file_name = FileUploader::upload($request->city_image, 'public/storage/cities/logo', 250);
    }

    City::create([
        'city_name' => $request->city_name,
        'state_id' => $request->state_id,
        'city_slug' => $slug,
        'city_state' => $request->city_state ?? 'Unknown',
        'city_image' => $file_name,
        'city_about' => $request->city_about,
        'createdBy' => auth()->id(),
        'is_approved' => 'N',
    ]);

    return redirect()->route('admin.cities')->with('success', 'City added successfully!');
}


// public function city_store(Request $request)
// {
//     $request->validate([
//         'city_name' => 'required',
//         'state_id' => 'required',
//     ]);

//     $slug = Str::slug($request->city_name); // Generate slug from city name

//     // Check if slug already exists and append a number if necessary
//     $count = City::where('city_slug', $slug)->count();
//     if ($count > 0) {
//         $slug .= '-' . ($count + 1);
//     }

//     City::create([
//         'city_name' => $request->city_name,
//         'state_id' => $request->state_id,
//         'city_slug' => $slug, // Use the generated slug
//         'city_state' => $request->city_state ?? 'Unknown',
//         'createdBy' => auth()->id(), // Assign the authenticated user (if applicable)
//         'is_approved' => 'N', // Default to not approved
//     ]);

//     return redirect()->route('admin.cities')->with('success', 'City added successfully!');
// }


    public function city_show(City $city)
    {
        return view('cities.show', compact('city'));
    }

    public function city_edit(City $city)
    {
        $states = State::all(); 
        $page_data['states'] =$states;
        $page_data['city'] =$city;
        $page_data['view_path'] = 'city.edit';
        return view('backend.index', $page_data);
        //return view('cities.edit', compact('city'));
    }

//     public function city_update(Request $request, City $city)
// {
//     // Validate request
//     $request->validate([
//         'city_name'  => 'required|string|max:255',
//         'state_id'   => 'required|exists:states,id', // Ensures state exists
//         'city_state' => 'required|string|max:255',
//         'city_slug'  => 'nullable|string|unique:cities,city_slug,' . $city->id,
//     ]);

//     // Generate slug if not provided
//     $city_slug = $request->city_slug ?? Str::slug($request->city_name);

//     // Update city details
//     $city->update([
//         'city_name'  => $request->city_name,
//         'state_id'   => $request->state_id,
//         'city_state' => $request->city_state,
//         'city_slug'  => $city_slug,
//     ]);

//     // Redirect with success message
//     return redirect()->route('admin.cities')->with('success', 'City updated successfully!');
// }

public function city_update(Request $request, City $city)
{
    $request->validate([
        'city_name'  => 'required|string|max:255',
        'state_id'   => 'required|exists:states,id',
        'city_state' => 'required|string|max:255',
        'city_slug'  => 'nullable|string|unique:cities,city_slug,' . $city->id,
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        'city_about' => 'nullable|string',
    ]);

    // Generate slug if not provided
    $city_slug = $request->city_slug ?? Str::slug($request->city_name);

    // Handle image upload
    if ($request->hasFile('image')) {
        // Delete old image if needed
        if ($city->city_image && file_exists(public_path($city->city_image))) {
            //unlink(public_path($city->city_image));
        }

        // Upload new image using helper
        $file_name = FileUploader::upload($request->image, 'public/storage/cities/logo', 250);
        $city->city_image = $file_name;
    }

    // Update city fields
    $city->update([
        'city_name'  => $request->city_name,
        'state_id'   => $request->state_id,
        'city_state' => $request->city_state,
        'city_slug'  => $city_slug,
        'city_about' => $request->city_about,
        'city_image' => $city->city_image, // This line ensures the new image is saved
    ]);

    return redirect()->route('admin.cities')->with('success', 'City updated successfully!');
}


    public function city_destroy(City $city)
    {
        $city->delete();

        return redirect()->route('admin.cities')->with('success', 'City deleted successfully!');
    }



    public function area_index(Request $request)
{
    $query = Area::with(['city.state']);

    // Filter by area name (case-insensitive search)
    if ($request->filled('area_name')) {
        $query->where('area_name', 'like', '%' . $request->area_name . '%');
    }

    // Filter by city
    if ($request->filled('city_id')) {
        $query->where('city_id', $request->city_id);
    }

    // Filter by state
    if ($request->filled('state_id')) {
        $query->whereHas('city', function($q) use ($request) {
            $q->where('state_id', $request->state_id);
        });
    }

    $areas = $query->latest()->paginate(10);
    $cities = City::with('state')->get(); // Fetch cities with state info
    $states = State::all(); // Fetch all states for dropdown

        $page_data['areas'] = $areas;
        $page_data['cities'] = $cities;
        $page_data['states'] = $states;
        $page_data['view_path'] = 'area.index';
        return view('backend.index', $page_data);
}


public function area_create()
{
    $cities = City::all(); // Fetch all cities for the dropdown

    return view('backend.index', [
        'cities' => $cities,
        'view_path' => 'area.create'
    ]);
}

public function area_store(Request $request)
    {
        $request->validate([
            'area_name' => 'required|string|max:255',
            'city_id'   => 'required|exists:cities,id', // Ensure city exists
        ]);

        $slug = Str::slug($request->area_name);

        // Check if slug exists and append number if necessary
        $count = Area::where('area_slug', $slug)->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        Area::create([
            'area_name'  => $request->area_name,
            'city_id'    => $request->city_id,
            'area_slug'  => $slug,
            'createdBy'  => auth()->id(),
            'is_approved'=> 'N',
        ]);

        return redirect()->route('admin.areas')->with('success', 'Area added successfully!');
    }

    // Show an area
    public function area_show(Area $area)
    {
        return view('areas.show', compact('area'));
    }

    // Edit area
    public function area_edit(Area $area)
    {
        $cities = City::all();
        $page_data['cities'] = $cities;
        $page_data['area'] = $area;
        $page_data['view_path'] = 'area.edit';

        return view('backend.index', $page_data);
    }

    // Update area
    public function area_update(Request $request, Area $area)
    {
        $request->validate([
            'area_name' => 'required|string|max:255',
            'city_id'   => 'required|exists:cities,id',
            'area_slug' => 'nullable|string|unique:areas,area_slug,' . $area->id,
        ]);

        $area_slug = $request->area_slug ?? Str::slug($request->area_name);

        $area->update([
            'area_name'  => $request->area_name,
            'city_id'    => $request->city_id,
            'area_slug'  => $area_slug,
        ]);

        return redirect()->route('admin.areas')->with('success', 'Area updated successfully!');
    }

    // Delete area
    public function area_destroy(Area $area)
    {
        $area->delete();

        return redirect()->route('admin.areas')->with('success', 'Area deleted successfully!');
    }
    
    // AJAX method to get cities by state
    public function getCitiesByState(Request $request)
    {
        $stateId = $request->get('state_id');
        
        if (!$stateId) {
            return response()->json([]);
        }
        
        $cities = City::where('state_id', $stateId)
                     ->select('id', 'city_name')
                     ->orderBy('city_name')
                     ->get();
        
        return response()->json($cities);
    }



    public function wallet_report(Request $request)
    {
        $query = User::leftJoin('wallet_transactions', 'users.id', '=', 'wallet_transactions.user_id')
    ->select(
        'users.id', 
        'users.name', 
        DB::raw('
            COALESCE(SUM(
                CASE 
                    WHEN wallet_transactions.status = "successful" AND wallet_transactions.type = "credit" 
                    THEN wallet_transactions.amount 
                    WHEN wallet_transactions.status = "successful" AND wallet_transactions.type = "debit" 
                    THEN -wallet_transactions.amount 
                    ELSE 0 
                END
            ), 0) as wallet_balance
        ')
    )
    ->groupBy('users.id', 'users.name')
    ->orderByDesc('wallet_balance');
    
        // Apply user search filter
        if ($request->has('user_search') && !empty($request->user_search)) {
            $query->where('users.name', 'LIKE', "%{$request->user_search}%");
        }
    
        $users = $query->paginate(10);
    
        $page_data['users'] = $users;
        $page_data['view_path'] = 'wallet.wallet_report';
    
        return view('backend.index', $page_data);
    }
    



    public function wallet_transactions(Request $request, $user_id)
{
    $user = User::findOrFail($user_id);

    $query = WalletTransaction::where('user_id', $user_id)
        ->where('status', 'successful')
        ->orderBy('created_at', 'desc');

    // Apply start date filter
    if ($request->has('start_date') && !empty($request->start_date)) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }

    // Apply end date filter
    if ($request->has('end_date') && !empty($request->end_date)) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }

    $transactions = $query->paginate(10);

    $page_data['transactions'] = $transactions;
    $page_data['user'] = $user;
        $page_data['view_path'] = 'wallet.wallet_transactions';

        return view('backend.index', $page_data);

    //return view('backend.wallet_transactions', compact('transactions', 'user'));
}


public function manage_approval(){

    $pages = ManageApproval::all();
    $page_data['pages'] =$pages;
    $page_data['view_path'] ='approval.index';
    return view('backend.index',$page_data);

    
}

public function toggleServiceStatus($id) {
    $page = ManageApproval::findOrFail($id);
    $page->status = !$page->status; // Toggle the status
    $page->save();

    return response()->json([
        'success' => true,
        'status' => $page->status
    ]);
}

    // ==================== COUNTRY MANAGEMENT ====================

    public function country_index(Request $request)
    {
        $query = Country::withCount(['cities', 'states']);

        // Apply country name filter
        if ($request->filled('country_name')) {
            $query->where('country_name', 'like', '%' . $request->country_name . '%');
        }

        // Apply country code filter
        if ($request->filled('country_code')) {
            $query->where('country_code', 'like', '%' . $request->country_code . '%');
        }

        // Handle sorting
        $sortBy = $request->get('sort_by', 'country_name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        // Validate sort_by parameter
        $allowedSortFields = ['country_name', 'country_code', 'cities_count', 'states_count', 'id', 'created_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'country_name';
        }
        
        // Validate sort_order parameter
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        // Apply sorting
        $countries = $query->orderBy($sortBy, $sortOrder)->paginate(10);

        return view('backend.index', [
            'countries' => $countries,
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
            'view_path' => 'country.index'
        ]);
    }

    public function country_create()
    {
        return view('backend.index', [
            'view_path' => 'country.create'
        ]);
    }

    public function country_store(Request $request)
    {
        $request->validate([
            'country_name' => 'required|string|max:255',
            'country_code' => 'required|string|max:3|unique:countries,country_code',
            'country_about' => 'nullable|string',
            'country_flag' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slug = Str::slug($request->country_name);
        $count = Country::where('country_slug', $slug)->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        // Handle flag image upload
        $flag_name = null;
        if ($request->hasFile('country_flag')) {
            $flag_name = FileUploader::upload($request->country_flag, 'public/storage/countries/flags', 250);
        }

        Country::create([
            'country_name' => $request->country_name,
            'country_code' => strtoupper($request->country_code),
            'country_slug' => $slug,
            'country_flag' => $flag_name,
            'country_about' => $request->country_about,
            'createdBy' => auth()->id(),
            'is_approved' => 'Y', // Auto-approve countries
        ]);

        return redirect()->route('admin.countries')->with('success', 'Country added successfully!');
    }

    public function country_show(Country $country)
    {
        return view('backend.index', [
            'country' => $country,
            'view_path' => 'country.show'
        ]);
    }

    public function country_edit(Country $country)
    {
        // Load the country with counts
        $country->loadCount(['states', 'cities']);
        
        return view('backend.index', [
            'country' => $country,
            'view_path' => 'country.edit'
        ]);
    }

    public function country_update(Request $request, Country $country)
    {
        $request->validate([
            'country_name' => 'required|string|max:255',
            'country_code' => 'required|string|max:3|unique:countries,country_code,' . $country->id,
            'country_about' => 'nullable|string',
            'country_flag' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slug = Str::slug($request->country_name);
        if ($slug !== $country->country_slug) {
            $count = Country::where('country_slug', $slug)->where('id', '!=', $country->id)->count();
            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }
        }

        // Handle flag image upload
        $flag_name = $country->country_flag;
        if ($request->hasFile('country_flag')) {
            $flag_name = FileUploader::upload($request->country_flag, 'public/storage/countries/flags', 250);
        }

        $country->update([
            'country_name' => $request->country_name,
            'country_code' => strtoupper($request->country_code),
            'country_slug' => $slug,
            'country_flag' => $flag_name,
            'country_about' => $request->country_about,
        ]);

        return redirect()->route('admin.countries')->with('success', 'Country updated successfully!');
    }

    public function country_destroy(Country $country)
    {
        // Check if country has states
        if ($country->states()->count() > 0) {
            return redirect()->route('admin.countries')->with('error', 'Cannot delete country with existing states!');
        }

        $country->delete();
        return redirect()->route('admin.countries')->with('success', 'Country deleted successfully!');
    }

    // AJAX method to get countries
    public function getCountries(Request $request)
    {
        $countries = Country::select('id', 'country_name', 'country_code')
                           ->orderBy('country_name')
                           ->get();
        
        return response()->json($countries);
    }
public function serverSideUsersData(Request $request)
{
    $query = User::query();

    // Filters
    if ($request->start_date) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }

    if ($request->end_date) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }

    if ($request->state_id) {
        $query->where('state_id', $request->state_id);
    }

    if ($request->city_id) {
        $query->where('city_id', $request->city_id);
    }

    if ($request->area_id) {
        $query->where('area_id', $request->area_id);
    }

    return DataTables::of($query)
        ->addColumn('key', function ($user) {
            return $user->id;
        })
        ->addColumn('photo', function ($user) {
            return '<img src="'.asset($user->photo).'" width="40">';
        })
        ->addColumn('name', function ($user) {
            return $user->name;
        })
        ->addColumn('email', function ($user) {
            return $user->email;
        })
        ->addColumn('city', function ($user) {
            return optional($user->city)->city_name;
        })
        ->addColumn('date', function ($user) {
    return $user->created_at 
        ? $user->created_at->format('d-m-Y') 
        : '';
})
        ->addColumn('status', function ($user) {
            return $user->status ? 'Active' : 'Inactive';
        })
        ->addColumn('action', function ($user) {
    return '<a href="'.route('admin.user.edit', $user->id).'"
            class="btn btn-sm btn-primary">
            Edit
            </a>';
})
->rawColumns(['action'])
        ->rawColumns(['photo','action'])
        ->make(true);
}

}