<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enquirymaster;
use App\Models\Category;
use App\Models\LeadPurchases;
use App\Models\City;
use App\Models\State;
use App\Models\Area;
use App\Models\User;
use App\Models\buyerLeadStage;
use App\Models\WalletTransaction;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Auth;
  use App\Models\Wallet;
  use Illuminate\Support\Facades\DB;
  use Razorpay\Api\Api;
  use Carbon\Carbon;
  use Illuminate\Pagination\LengthAwarePaginator;
class LeadController extends Controller
{

    public function getAreasByCity(Request $request)
{
    //echo "123";exit;
    if ($request->filled('city_id')) {
        $areas = Area::where('city_id', $request->city_id)->select('id', 'area_name')->get();
        return response()->json($areas);
    }
    
    return response()->json([]);
}
    

public function index(Request $request)
{
    $query = Enquirymaster::with(['marketplace.page.city', 'buyerLeadStage']);

    // Only get leads where the enquiry_lead_stage is "Open"
    $query->where(function ($q) {
        $q->whereDoesntHave('buyerLeadStage') // ✅ Include leads without an entry in buyerLeadStage
          ->orWhereHas('buyerLeadStage', function ($subQuery) {
              $subQuery->whereHas('leadStage', function ($leadStageQuery) {
                  $leadStageQuery->where('stage_name', '!=', 'Close'); // ✅ Exclude "Close" status
              });
          });
    });

    // City Filter
    if ($request->filled('city')) { 
        $query->whereHas('marketplace.page.city', function ($q) use ($request) {
            $q->where('id', $request->city);
        });
    }

    // Area Filter (if city is selected)
    if ($request->filled('area')) {
        $query->whereHas('marketplace.page', function ($q) use ($request) {
            $q->where('area_id', $request->area);
        });
    }

    // Category Filter (If category is comma-separated)
    if ($request->filled('category')) {
        $query->whereHas('marketplace', function ($q) use ($request) {
            $q->whereRaw("FIND_IN_SET(?, category)", [$request->category]);
        });
    }

    // Start Date & End Date Filters
    if ($request->filled('start_date')) {
        $query->whereDate('createdAt', '>=', $request->start_date);
    }
    if ($request->filled('end_date')) {
        $query->whereDate('createdAt', '<=', $request->end_date);
    }

    // Fetch Leads with Pagination
    $leads = $query->paginate(10)->appends($request->query());

    // Fetch Only Cities Having Enquiries
    $cities = City::whereIn('id', Enquirymaster::pluck('cityid'))->select('id', 'city_name')->get();

    // Fetch Areas Based on Selected City
    $areas = $request->filled('city') 
        ? Area::where('city_id', $request->city)->select('id', 'area_name')->get() 
        : collect([]);

    // Fetch Only Categories Having Enquiries
    $categories = Category::whereHas('marketplaces', function ($query) {
        $query->whereRaw("FIND_IN_SET(categories.id, marketplaces.category)");
    })->select('id', 'product_category_name')->get();

    return view('backend.index', [
        'leads'      => $leads,
        'cities'     => $cities,
        'areas'      => $areas,
        'categories' => $categories,
        'view_path'  => 'leads.index',
    ]);
}



    


    


    


public function viewLead($id)
{
    $lead = Enquirymaster::findOrFail($id);
    
    // Load marketplace if available and fetch categories
    if ($lead->marketplace) {
        $categories = $lead->marketplace->category_objects;
        $masterCategory = $categories->first(); // Get only the first category (assuming it's the master category)
    } else {
        $masterCategory = null;
    }

    return view('backend.index', [
        'lead' => $lead,
        'masterCategory' => $masterCategory,
        'view_path' => 'leads.view',
    ]);
}



  
    
public function buyLead($id)
{
    $lead = Enquirymaster::findOrFail($id);
    $user = Auth::user();

    if (!$user) {
        return redirect()->back()->with('error', 'User not found.');
    }

    // Fetch wallet balance from wallets table
    $wallet = Wallet::where('user_id', $user->id)->first();

    if (!$wallet) {
        return redirect()->back()->with('error', 'Wallet not found.');
    }

    $walletBalance = $wallet->balance;

    // Fetch category price
    $categoryIds = explode(',', $lead->marketplace->category);
    $masterCategory = Category::whereIn('id', $categoryIds)->first();

    if (!$masterCategory) {
        return redirect()->back()->with('error', 'Category not found.');
    }

    $leadPrice = $masterCategory->lead_price ?? 0;

    if ($walletBalance < $leadPrice) {
        return redirect()->back()->with('error', 'Insufficient balance.');
    }

    // Start transaction to ensure data integrity
    DB::beginTransaction();

    try {
        // Deduct balance from wallet
        DB::table('wallets')
            ->where('user_id', $user->id)
            ->update(['balance' => DB::raw("balance - $leadPrice")]);

        // Insert purchase record into lead_purchases table
        LeadPurchases::create([
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'purchased_at' => now(),
        ]);

        DB::commit();
        return redirect()->route('leads.index')->with('success', 'Lead purchased successfully!');
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error',  $e->getmessage());
    }
}

public function buyLeadFromWallet(Request $request)
{
    $lead = Enquirymaster::findOrFail($request->lead_id);
    $user = Auth::user();
    
    $wallet = Wallet::where('user_id', $user->id)->first();
    $leadPrice = optional($lead->marketplace)->master_category_lead_price ?? 0; // Ensure we get a valid price

    if (!$wallet || $wallet->balance < $leadPrice || $leadPrice <= 0) {
        return response()->json(['success' => false, 'error' => 'Invalid lead price or insufficient wallet balance.'], 400);
    }

    DB::transaction(function () use ($wallet, $lead, $user, $leadPrice) {
        // Deduct balance
        $wallet->balance -= $leadPrice;
        $wallet->save();

        // Insert wallet transaction with Lead Name
       // Create a new WalletTransaction record
        $walletTransaction = WalletTransaction::create([
            'user_id'     => $user->id,
            'amount'      => $leadPrice,
            'type'        => 'debit',
            'description' => 'Lead purchased (Lead: ' . $lead->name . ')',
            'status'      => 'successful',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Get the inserted ID
        $insertedId = $walletTransaction->id;

       

        // Insert lead purchase
        LeadPurchases::create([
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'purchased_at' => now(),
            'transactionid'=>$insertedId
        ]);

       
        
    });

    
    return response()->json(['success' => true, 'message' => 'Lead purchased successfully!']);
}



public function buyLeadOnline(Request $request)
{
    try {
        $lead = Enquirymaster::findOrFail($request->lead_id);
        $user = Auth::user();

        // ✅ Validate Lead Price
        $leadPrice = optional($lead->marketplace)->master_category_lead_price;

        if (!is_numeric($leadPrice) || $leadPrice <= 0) {
            return response()->json([
                'success' => false,
                'error'   => 'Invalid lead price. Please contact support.'
            ], 400);
        }

        $amount = $leadPrice * 100; // Convert INR to paise

        // ✅ Initialize Razorpay API
        $api = new \Razorpay\Api\Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        // ✅ Log API Call for Debugging
        \Log::info('Creating Razorpay Order', [
            'receipt'  => 'lead_' . $lead->id,
            'amount'   => $amount,
            'currency' => 'INR'
        ]);

        // ✅ Create order in Razorpay
        $order = $api->order->create([
            'receipt'         => 'lead_' . $lead->id,
            'amount'          => $amount,
            'currency'        => 'INR',
            'payment_capture' => 1 // Auto capture payment
        ]);

        if (!$order || !isset($order['id'])) {
            throw new \Exception('Razorpay did not return an order ID');
        }

        // ✅ Store Order ID in Session
        session([
            'lead_id'  => $lead->id,
            'order_id' => $order['id'],
            'amount'   => $leadPrice, // Store in INR
        ]);

        // ✅ Return success response
        return response()->json([
            'success'  => true,
            'order_id' => $order['id'],
            'amount'   => $leadPrice, // Return in INR
            'key'      => env('RAZORPAY_KEY'),
            'lead'     => $lead,
            'view_path' => 'payment.razorpay',
        ]);

    } catch (\Exception $e) {
        \Log::error('Razorpay Order Error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'error'   => 'Payment failed: ' . $e->getMessage()
        ], 500);
    }
}


public function paymentSuccess(Request $request)
{
    $user = Auth::user();

    // Ensure these fields exist in the request
    if (!$request->has('razorpay_payment_id') || !$request->has('razorpay_order_id')) {
        return redirect()->route('leads.index')->with('error', 'Payment failed or incomplete!');
    }

    $lead = Enquirymaster::findOrFail(session('lead_id')); // Retrieve from session

   // Store Payment Transaction and get the inserted model instance
        $paymentTransaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'amount' => $lead->marketplace->master_category_lead_price,
            'status' => 'successful',
            'transaction_id' => $request->razorpay_payment_id,  // ✅ FIXED
            'order_id' => $request->razorpay_order_id,           // ✅ FIXED
            'payment_method' => $request->method ?? 'N/A',  // ✅ Insert payment method
            'description' => 'Lead purchased (Lead: ' . $lead->name . ')',
        ]);

        // Get the inserted ID
        $insertedId = $paymentTransaction->id;

    // Store Lead Purchase
    LeadPurchases::create([
        'lead_id' => $lead->id,
        'user_id' => $user->id,
        'purchased_at' => now(),
        'transactionid'=>$request->razorpay_payment_id
    ]);

    return redirect()->route('leads.index')->with('success', 'Lead purchased successfully!');
}


public function fetchStates(Request $request)
{
    $query = State::select('id', 'state_name');

    if ($request->has('search')) {
        $search = $request->get('search');
        $query->where('state_name', 'LIKE', "%{$search}%");
    }

    $states = $query->limit(100)->get();

    // Ensure Select2 expects `{ id, text }` format
    return response()->json(
        $states->map(function($state) {
            return ['id' => $state->id, 'text' => $state->state_name];
        })
    );
}



public function fetchCities(Request $request)
{
    $cities = City::where('state_id', $request->state)->select('id', 'city_name')->get();
    return response()->json($cities);
}

public function fetchAreas(Request $request)
{
    $areas = Area::where('city_id', $request->city)->select('id', 'name')->get();
    return response()->json($areas);
}

public function fetchCategories()
{
    $categories = Category::select('id', 'product_category_name as name')->get();
    return response()->json($categories);
}




public function leadPurchaseReport(Request $request)
{
    $userId = auth()->id();

    // ✅ Set default start & end date (show all by default)
    $startDate = $request->input('start_date') 
        ? Carbon::parse($request->input('start_date'))->startOfDay() 
        : Carbon::create(2000, 1, 1)->startOfDay(); // Show all records from year 2000

    $endDate = $request->input('end_date') 
        ? Carbon::parse($request->input('end_date'))->endOfDay() 
        : now()->endOfDay(); // Up to today

    $paymentType = $request->input('payment_type');

    // ✅ Initialize Collection
    $transactions = collect();

    // ✅ Fetch Wallet Transactions
    if ($paymentType == 'Wallet' || !$paymentType) {
        $walletTransactions = WalletTransaction::where('wallet_transactions.user_id', $userId)
            ->whereBetween('wallet_transactions.created_at', [$startDate, $endDate])
            ->where('wallet_transactions.type', 'debit')
            ->where('wallet_transactions.description', 'LIKE', '%Lead purchased%')
            ->join('lead_purchases', 'wallet_transactions.id', '=', 'lead_purchases.transactionid')
            ->join('enquirymaster', 'lead_purchases.lead_id', '=', 'enquirymaster.id')
            ->join('marketplaces', 'enquirymaster.productid', '=', 'marketplaces.id')
            ->select(
                'wallet_transactions.id as transaction_id',
                'wallet_transactions.user_id',
                'wallet_transactions.amount',
                'wallet_transactions.created_at',
                'wallet_transactions.description',
                'enquirymaster.name as lead_name',
                'marketplaces.category as category_name',
                'marketplaces.title as product_name'
            )
            ->distinct()
            ->get()
            ->map(function ($transaction) {
                $transaction->display_transaction_id = 'Wallet Transaction';
                $transaction->payment_type = 'Wallet';
                return $transaction;
            });

        $transactions = $transactions->concat($walletTransactions);
    }

    // ✅ Fetch Online Transactions
    if ($paymentType == 'Online' || !$paymentType) {
        $paymentTransactions = PaymentTransaction::where('payment_transactions.user_id', $userId)
            ->whereBetween('payment_transactions.created_at', [$startDate, $endDate])
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
            ->distinct()
            ->get()
            ->map(function ($transaction) {
                $transaction->display_transaction_id = $transaction->transaction_id;
                $transaction->payment_type = 'Online Payment';
                return $transaction;
            });

        $transactions = $transactions->concat($paymentTransactions);
    }

    // ✅ Convert Collection to Pagination
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $perPage = 10; // Change this number to adjust items per page
    $currentItems = $transactions->slice(($currentPage - 1) * $perPage, $perPage)->values();
    $paginatedTransactions = new LengthAwarePaginator(
        $currentItems,
        $transactions->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url()]
    );

    return view('backend.index', [
        'transactions' => $paginatedTransactions, // ✅ Now Paginated
        'startDate'    => $startDate->format('Y-m-d'),
        'endDate'      => $endDate->format('Y-m-d'),
        'userId'       => $userId,
        'view_path'    => 'reports.lead_purchase',
    ]);
}








    

}
