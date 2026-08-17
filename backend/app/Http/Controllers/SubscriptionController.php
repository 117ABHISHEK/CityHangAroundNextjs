<?php
namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

use App\Models\Page;
use App\Models\Blog;
use App\Models\Event;
use App\Models\Marketplace;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSubscription;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\User;
Use DB;
use Razorpay\Api\Api;
use Carbon\Carbon;
use App\Helpers\CityHelper;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Illuminate\Support\Facades\URL;
class SubscriptionController extends Controller
{

    public function showPlans()
{
    $subscriptions = Subscription::with(['features' => function ($query) {
        $query->orderBy('subscription_feature_mappings.id');
    }])->orderBy('price')->get();
    $user = Auth::user();
    $wallet = Wallet::where('user_id', $user->id)->first();
    $walletBalance = $wallet ? $wallet->balance : 0;

    $userSubscriptions = UserSubscription::with(['subscription.features' => function ($query) {
            $query->orderBy('subscription_feature_mappings.id');
        }])
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

    $subscriptionIds = $userSubscriptions->pluck('subscription_id')->unique()->filter()->values();

    $paymentTransactions = DB::table('payment_transactions')
        ->where('user_id', $user->id)
        ->whereIn('user_subscription_id', $subscriptionIds)
        ->orderByDesc('created_at')
        ->get()
        ->keyBy('user_subscription_id');

    $walletTransactions = DB::table('wallet_transactions')
        ->where('user_id', $user->id)
        ->whereIn('user_subscription_id', $subscriptionIds)
        ->orderByDesc('created_at')
        ->get()
        ->keyBy('user_subscription_id');

    $subscriptionData = $userSubscriptions->map(function ($userSub) use ($paymentTransactions, $walletTransactions) {
        $expiresAt = $userSub->expires_at ? Carbon::parse($userSub->expires_at) : null;
        $isActive = $userSub->status === 'active' && $expiresAt && now()->lessThanOrEqualTo($expiresAt);

        return [
            'user_subscription' => $userSub,
            'payment_transaction' => $paymentTransactions->get($userSub->subscription_id),
            'wallet_transaction' => $walletTransactions->get($userSub->subscription_id),
            'is_active' => $isActive,
            'days_remaining' => $isActive && $expiresAt ? now()->diffInDays($expiresAt, false) : 0,
            'expires_at' => $expiresAt,
        ];
    });

    $currentSubscriptions = $subscriptionData
        ->filter(fn ($item) => $item['is_active'])
        ->keyBy(fn ($item) => $item['user_subscription']->subscription_id);

    $pricedPlans = $subscriptions
        ->filter(function ($subscription) {
            $finalPrice = $subscription->offer_price && $subscription->offer_price < $subscription->price
                ? $subscription->offer_price
                : $subscription->price;

            return $finalPrice > 0;
        })
        ->values();

    $bestDiscountPlanId = $subscriptions
        ->mapWithKeys(function ($subscription) {
            $discount = ($subscription->price > 0 && $subscription->offer_price && $subscription->offer_price < $subscription->price)
                ? round((($subscription->price - $subscription->offer_price) / $subscription->price) * 100)
                : 0;

            return [$subscription->id => $discount];
        })
        ->sortDesc()
        ->keys()
        ->first();

    $bestValuePlanId = $pricedPlans
        ->sortBy(function ($subscription) {
            $finalPrice = $subscription->offer_price && $subscription->offer_price < $subscription->price
                ? $subscription->offer_price
                : $subscription->price;

            return $finalPrice / max((int) $subscription->duration, 1);
        })
        ->pluck('id')
        ->first();

    $popularPlanId = $pricedPlans->count() >= 3
        ? $pricedPlans->values()->get(1)?->id
        : $bestDiscountPlanId;

    SEOMeta::setTitle('Subscription Plans | CityHangAround');
    SEOMeta::setDescription('Explore CityHangAround subscription plans, compare pricing, features, wallet balance, and manage your active plan in one place.');
    SEOMeta::setCanonical(URL::current());
    OpenGraph::setTitle('Subscription Plans | CityHangAround');
    OpenGraph::setDescription('Compare subscription plans, features, pricing, and your active membership on CityHangAround.');
    OpenGraph::setUrl(URL::current());

    $page_data['all_cities'] = CityHelper::getActiveCities();
    $page_data['walletBalance'] = $walletBalance;
    $page_data['subscriptions'] = $subscriptions;
    $page_data['mySubscriptions'] = $subscriptionData;
    $page_data['currentSubscriptions'] = $currentSubscriptions;
    $page_data['bestDiscountPlanId'] = $bestDiscountPlanId;
    $page_data['bestValuePlanId'] = $bestValuePlanId;
    $page_data['popularPlanId'] = $popularPlanId;
    $page_data['view_path'] = 'frontend.subscriptions.index';

    return view('frontend.index', $page_data);
}


public function index(Request $request)
{
    $query = Subscription::query();

    if ($request->has('name') && !empty($request->name)) {
        $query->where('name', 'LIKE', '%' . $request->name . '%');
    }

    if ($request->has('date') && !empty($request->date)) {
        $query->whereDate('created_at', $request->date);
    }

    $subscriptions = $query->latest()->paginate(10);

    $page_data['subscriptions'] = $subscriptions;
    $page_data['view_path'] = 'subscriptions.index';

    return view('backend.index', $page_data);
}


    public function create()
    {

         $page_data['cities'] = \App\Models\City::orderBy('city_name')->get();
        $page_data['view_path'] = 'subscriptions.create';
        return view('backend.index', $page_data);
    }

    public function getAreas($cityId)
{
    $areas = \App\Models\Area::where('city_id', $cityId)->get(['id', 'area_name']);
  return response()->json($areas);
}


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|unique:subscriptions',
    //         'price' => 'required|numeric',
    //         'duration' => 'required|integer|min:1', // Adding duration validation
    //         'offer_price' => 'nullable|numeric|min:0', // Adding offer_price validation
    //         'services' => 'nullable|array',
    //         'cities' => 'nullable|array',
    //     ]);
    
    //    // Subscription::create($request->all());

    //    \App\Models\Subscription::create([
    //     'name' => $request->name,
    //     'price' => $request->price,
    //     'offer_price' => $request->offer_price,
    //     'duration' => $request->duration,
    //     'offered_services' => implode(',', $request->services),
    //     'offered_cities' => implode(',', $request->cities),
    // ]);
    
    //     return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription Created Successfully!');
    // }


   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:subscriptions',
        'price' => 'required|numeric',
        'duration' => 'required|integer|min:1',
        'offer_price' => 'nullable|numeric|min:0',
        'services' => 'nullable|array',
        'durations' => 'nullable|array', // ✅ this holds your new city/area durations
    ]);

    \App\Models\Subscription::create([
        'name' => $request->name,
        'price' => $request->price,
        'offer_price' => $request->offer_price,
        'duration' => $request->duration,
        'offered_services' => implode(',', $request->services ?? []),
        'area_durations' => json_encode($request->durations ?? []), // ✅ your new duration structure
    ]);

     

    return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription Created Successfully!');
}



    // public function edit(Subscription $subscription)
    // {

    //     //print_r($subscription);exit;
    //     $page_data['subscription'] =$subscription;
    //      $page_data['cities'] = \App\Models\City::orderBy('city_name')->get();
    //     $page_data['view_path'] = 'subscriptions.edit';
    //     return view('backend.index', $page_data);
    //    // return view('admin.subscriptions.edit', compact('subscription'));
    // }


    public function edit(Subscription $subscription)
{
    $page_data['subscription'] = $subscription;
    $page_data['cities'] = \App\Models\City::orderBy('city_name')->get();

    // Get pre-selected areas per city
    $offeredCities = explode(',', $subscription->offered_cities);
    $areasByCity = [];

    foreach ($offeredCities as $cityId) {
        $areasByCity[$cityId] = \App\Models\Area::where('city_id', $cityId)->get();
    }

    // Parse durations JSON from DB
    $durations = json_decode($subscription->durations_json ?? '{}', true);

    $page_data['areasByCity'] = $areasByCity;
    $page_data['durations'] = $durations;
    $page_data['view_path'] = 'subscriptions.edit';

    return view('backend.index', $page_data);
}


//     public function update(Request $request, Subscription $subscription)
// {
//     $request->validate([
//         'name' => 'required|unique:subscriptions,name,' . $subscription->id,
//         'price' => 'required|numeric',
//         'duration' => 'required|integer|min:1',
//         'offer_price' => 'nullable|numeric|min:0',
//          'services' => 'nullable|array',
//     'cities' => 'nullable|array',
//     ]);

//     //$subscription->update($request->all());
//     $subscription->update([
//     'name' => $request->name,
//     'price' => $request->price,
//     'offer_price' => $request->offer_price,
//     'duration' => $request->duration,
//     'offered_services' => $request->services ? implode(',', $request->services) : null,
//     'offered_cities' => $request->cities ? implode(',', $request->cities) : null,
//     ]);


//     return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription Updated Successfully!');
// }


public function update(Request $request, Subscription $subscription)
{
    $request->validate([
        'name' => 'required|unique:subscriptions,name,' . $subscription->id,
        'price' => 'required|numeric|min:0',
        'duration' => 'required|integer|min:1',
        'offer_price' => 'nullable|numeric|min:0',
        'services' => 'nullable|array',
        'services.*' => 'string|in:listings,event,marketplace,blogs,group',
        'durations' => 'nullable|array',
        'durations.*.city' => 'nullable|numeric|min:0',
        'durations.*.area' => 'nullable|numeric|min:0',
    ]);

    $subscription->update([
        'name' => $request->name,
        'price' => $request->price,
        'offer_price' => $request->offer_price,
        'duration' => $request->duration,
        'offered_services' => $request->filled('services') ? implode(',', $request->services) : null,
        'area_durations' => $request->filled('durations') ? json_encode($request->durations) : null,
    ]);

    return redirect()
        ->route('admin.subscriptions.index')
        ->with('success', 'Subscription Updated Successfully!');
}



    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription Deleted Successfully!');
    }


    public function subscribe($id)
    {
        $subscription = Subscription::findOrFail($id);
        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)->first();
        $walletBalance = $wallet ? $wallet->balance : 0;

        // Set Razorpay API Key
        $apiKey = env('RAZORPAY_KEY');

        // Generate Order ID for Razorpay
        $api = new Api($apiKey, env('RAZORPAY_SECRET'));
        $order = $api->order->create([
            'amount' => $subscription->price * 100, // Razorpay uses paise
            'currency' => 'INR',
            'receipt' => 'sub_' . uniqid(),
            'payment_capture' => 1
        ]);

        return view('subscriptions.checkout', [
            'subscription' => $subscription,
            'walletBalance' => $walletBalance,
            'orderId' => $order['id'],
            'amount' => $subscription->price,
            'key' => $apiKey
        ]);
    }

    /**
     * Handle Free Subscription
     */
    public function subscribeFree($id)
{
    $subscription = Subscription::findOrFail($id);
    $userId = Auth::id();

    // Check if the user is already subscribed
    $existingSubscription = UserSubscription::where('user_id', $userId)
        ->where('subscription_id', $id)
        ->first();

    if ($existingSubscription) {
        return redirect()->route('user.subscriptions')->with('error', 'You are already subscribed!');
    }

    // Determine expiry date based on subscription duration
    $duration = $subscription->duration ?? 1; // Default to 1 month if duration is not set
    $expiresAt = now()->addMonths($duration);

    // Assign Free Subscription with Calculated Expiry Date
    UserSubscription::create([
        'user_id' => $userId,
        'subscription_id' => $id,
        'status' => 'active',
        'expires_at' => $expiresAt
    ]);


     DB::table('payment_transactions')->insert([
                'user_id' => $userId,
                'amount' => '0', // Correct amount paid
                'status' => 'successful',
                'transaction_id' => '',
                'order_id' => '',
                'payment_method' => 'Free',
                'bank_name' =>'N/A',
                'description' => 'Subscription',
                'user_subscription_id' => $id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

    return redirect()->route('user.subscriptions')->with('success', 'You have subscribed successfully! Your plan will expire on ' . $expiresAt->format('d M, Y'));
}


    public function createRazorpayOrder(Request $request)
    {
        try {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    
            $order = $api->order->create([
                'amount' => $request->amount * 100,  // Convert to paisa
                'currency' => 'INR',
                'payment_capture' => 1
            ]);
    
            return response()->json(['success' => true, 'order_id' => $order->id]);
        } catch (\Exception $e) {
            Log::error("Razorpay Order Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Could not create order. Try again.']);
        }
    }

    /**
     * Handle Subscription Payment Success
     */


     protected function applyPriorityForUser($user, $subscription)
{
    $services = explode(',', $subscription->offered_services);
    $durations = json_decode($subscription->area_durations, true);
    $now = now();

    $models = [
        'listings' => \App\Models\Page::class,
        'blogs' => \App\Models\Blog::class,
        'events' => \App\Models\Event::class,
        'marketplace' => \App\Models\Marketplace::class,
        'group' => \App\Models\Group::class,
    ];

    foreach ($services as $service) {
        if (!isset($models[$service]) || !isset($durations[$service])) continue;

        $model = $models[$service];
        $cityDays = $durations[$service]['city'] ?? 0;
        $areaDays = $durations[$service]['area'] ?? 0;

        $update = [];

        //  Set priority dates
        if ($cityDays > 0) $update['priority_until_city'] = $now->copy()->addDays($cityDays);
        if ($areaDays > 0) $update['priority_until_area'] = $now->copy()->addDays($areaDays);

        //  Set item_featured = 1 if any duration is given
        if ($cityDays > 0 || $areaDays > 0) {
            $update['item_featured'] = 1;
        }

        if (!empty($update)) {
            $model::where('user_id', $user->id)->update($update);
        }
    }
}


    public function paymentSuccess(Request $request)
{
    DB::beginTransaction();
    try {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $payment = $api->payment->fetch($request->razorpay_payment_id);

        if ($payment->status === 'captured') {
            $subscriptionId = $request->subscription_id;
            $userId = Auth::id();
            $subscription = Subscription::findOrFail($subscriptionId);

            // ✅ Use offer price if available, otherwise fallback to normal price
            $finalPrice = $subscription->offer_price && $subscription->offer_price < $subscription->price
                ? $subscription->offer_price
                : $subscription->price;

            // ✅ Store Razorpay Payment in a Dedicated Table
            DB::table('payment_transactions')->insert([
                'user_id' => $userId,
                'amount' => $finalPrice, // Correct amount paid
                'status' => 'successful',
                'transaction_id' => $payment->id,
                'order_id' => $request->razorpay_order_id,
                'payment_method' => $payment->method,
                'bank_name' => $payment->bank ?? 'N/A',
                'description' => 'Subscription Payment via Razorpay',
                'user_subscription_id' => $subscriptionId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // ✅ Assign Subscription to User
            UserSubscription::create([
                'user_id' => $userId,
                'subscription_id' => $subscriptionId,
                'status' => 'active',
                'expires_at' => now()->addDays($subscription->duration) // Uses plan duration dynamically
            ]);

            DB::commit();
            $this->applyPriorityForUser(Auth::user(), $subscription);

            return redirect()->route('user.subscriptions')->with('success', 'Subscription successful!');
        }

        return redirect()->route('user.subscriptions')->with('error', 'Payment failed.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('user.subscriptions')->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

    

public function payWithWallet(Request $request)
{
    DB::beginTransaction();
    try {
        $user = Auth::user();
        $subscription = Subscription::findOrFail($request->subscription_id);
        $wallet = Wallet::where('user_id', $user->id)->first();

        // Determine the final price (offer price if available)
        $finalPrice = $subscription->offer_price && $subscription->offer_price < $subscription->price 
            ? $subscription->offer_price 
            : $subscription->price;

        // Check wallet balance
        if (!$wallet || $wallet->balance < $finalPrice) {
            return response()->json(['success' => false, 'message' => 'Insufficient wallet balance.']);
        }

        // Deduct from Wallet
        $wallet->balance -= $finalPrice;
        $wallet->save();

        // Log Transaction
        WalletTransaction::create([
            'user_id' => $user->id,
            'amount' => -$finalPrice,
            'status' => 'successful',
            'transaction_id' => 'WALLET_' . uniqid(),
            'bank_name' => 'Wallet',
            'payment_method' => 'wallet',
            'user_subscription_id' => $subscription->id,
            'description' => 'Subscription Payment'
        ]);

        // Assign Subscription to User
        UserSubscription::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'status' => 'active',
            'expires_at' => now()->addMonth()
        ]);

        DB::commit();
        $this->applyPriorityForUser($user, $subscription);

        return response()->json(['success' => true, 'message' => 'Subscription successful.']);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => 'Something went wrong. ' . $e->getMessage()]);
    }
}


// public function transactions_report(Request $request){

//     $userId = Auth::id(); // Get logged-in user ID

//         // Query user subscriptions with transactions
//         $query = UserSubscription::where('user_subscriptions.user_id', $userId) // Specify table name explicitly
//     ->join('subscriptions', 'user_subscriptions.subscription_id', '=', 'subscriptions.id')
//     ->join('payment_transactions', 'user_subscriptions.user_id', '=', 'payment_transactions.user_id') // Ensure it joins on user_id
//     ->select(
//         'user_subscriptions.status',
//         'user_subscriptions.expires_at',
//         'subscriptions.name as subscription_name',
//         'subscriptions.price',
//         'payment_transactions.transaction_id',
//         'payment_transactions.payment_method',
//         'payment_transactions.amount',
//         'payment_transactions.created_at as transaction_date'
//     );



//         // Apply start date filter
//         if ($request->has('start_date') && !empty($request->start_date)) {
//             $query->whereDate('payment_transactions.created_at', '>=', $request->start_date);
//         }

//         // Apply end date filter
//         if ($request->has('end_date') && !empty($request->end_date)) {
//             $query->whereDate('payment_transactions.created_at', '<=', $request->end_date);
//         }

//         $subscriptions = $query->orderBy('payment_transactions.created_at', 'desc')->paginate(10);

//         $page_data['subscriptions'] =$subscriptions;
//         $page_data['view_path'] = 'subscriptions_report';
//         return view('backend.index', $page_data);

//        // return view('subscription.report', compact('subscriptions'));
// }


public function transactions_report(Request $request)
{
    $userId = Auth::id();

    // PAYMENT TRANSACTIONS
   $paymentQuery = DB::table('user_subscriptions')
    ->join('subscriptions', 'user_subscriptions.subscription_id', '=', 'subscriptions.id')
    ->join('payment_transactions','user_subscriptions.subscription_id', '=', 'payment_transactions.user_subscription_id')
    ->where('user_subscriptions.user_id', $userId)
    ->select(
        'subscriptions.name as subscription_name',
        'user_subscriptions.status',
        'user_subscriptions.expires_at',
        'payment_transactions.transaction_id',
        'payment_transactions.payment_method',
        'payment_transactions.amount',
        'payment_transactions.created_at as transaction_date'
    );

    // WALLET TRANSACTIONS
    $walletQuery = DB::table('user_subscriptions')
        ->join('subscriptions', 'user_subscriptions.subscription_id', '=', 'subscriptions.id')
        ->join('wallet_transactions','user_subscriptions.subscription_id', '=', 'wallet_transactions.user_subscription_id')
        ->where('user_subscriptions.user_id', $userId)
        ->select(
            'subscriptions.name as subscription_name',
            'user_subscriptions.status',
            'user_subscriptions.expires_at',
            'wallet_transactions.transaction_id',
            'wallet_transactions.payment_method',
            'wallet_transactions.amount',
            'wallet_transactions.created_at as transaction_date'
        );

    // UNION BOTH
    $unionQuery = $paymentQuery->unionAll($walletQuery);

    // WRAP UNION AS SUBQUERY
    $finalQuery = DB::table(DB::raw("({$unionQuery->toSql()}) as transactions"))
        ->mergeBindings($unionQuery); // Keep SQL bindings intact

    // OPTIONAL FILTERS
    if ($request->filled('start_date')) {
        $finalQuery->whereDate('transaction_date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $finalQuery->whereDate('transaction_date', '<=', $request->end_date);
    }

    // FINAL PAGINATED RESULT
    $subscriptions = $finalQuery->orderBy('transaction_date', 'desc')->paginate(10);

    return view('backend.index', [
        'subscriptions' => $subscriptions,
        'view_path' => 'subscriptions_report'
    ]);
}



public function adminTransactionsReport(Request $request)
    {
        // Query user subscriptions with transactions
        // $query = UserSubscription::join('users', 'user_subscriptions.user_id', '=', 'users.id')
        // ->join('subscriptions', 'user_subscriptions.subscription_id', '=', 'subscriptions.id')
        // ->join('payment_transactions', 'user_subscriptions.user_id', '=', 'payment_transactions.user_id')
        // ->select(
        //     'users.id as user_id', // Ensure this is included
        //     'users.name as user_name',
        //     'user_subscriptions.status',
        //     'user_subscriptions.expires_at',
        //     'subscriptions.name as subscription_name',
        //     'payment_transactions.transaction_id',
        //     'payment_transactions.payment_method',
        //     'payment_transactions.amount',
        //     'payment_transactions.created_at as transaction_date'
        // );


        $paymentQuery = DB::table('user_subscriptions')
       
        ->join('users', 'user_subscriptions.user_id', '=', 'users.id')
    ->join('subscriptions', 'user_subscriptions.subscription_id', '=', 'subscriptions.id')
    ->join('payment_transactions','user_subscriptions.subscription_id', '=', 'payment_transactions.user_subscription_id')
   
    ->select(
        'users.id as user_id',
        'users.name as user_name',
        'subscriptions.name as subscription_name',
        'user_subscriptions.status',
        'user_subscriptions.expires_at',
        'payment_transactions.transaction_id',
        'payment_transactions.payment_method',
        'payment_transactions.amount',
        'payment_transactions.created_at as transaction_date'
    );

    // WALLET TRANSACTIONS
    $walletQuery = DB::table('user_subscriptions')
        ->join('subscriptions', 'user_subscriptions.subscription_id', '=', 'subscriptions.id')
        ->join('wallet_transactions','user_subscriptions.subscription_id', '=', 'wallet_transactions.user_subscription_id')
        ->join('users', 'user_subscriptions.user_id', '=', 'users.id')
        ->select(
             'users.id as user_id',
             'users.name as user_name',
            'subscriptions.name as subscription_name',
            'user_subscriptions.status',
            'user_subscriptions.expires_at',
            'wallet_transactions.transaction_id',
            'wallet_transactions.payment_method',
            'wallet_transactions.amount',
            'wallet_transactions.created_at as transaction_date'
        );

    // UNION BOTH
    $unionQuery = $paymentQuery->unionAll($walletQuery);

    // WRAP UNION AS SUBQUERY
    $query = DB::table(DB::raw("({$unionQuery->toSql()}) as transactions"))
        ->mergeBindings($unionQuery); // Keep SQL bindings intact

    // Apply filters
    if ($request->has('user_id') && !empty($request->user_id)) {
        $query->where('users.id', $request->user_id);
    }
    if ($request->has('start_date') && !empty($request->start_date)) {
        $query->whereDate('transaction_date', '>=', $request->start_date);
    }
    if ($request->has('end_date') && !empty($request->end_date)) {
        $query->whereDate('transaction_date', '<=', $request->end_date);
    }

    $subscriptions = $query->orderBy('transaction_date', 'desc')->paginate(10);

        $page_data['subscriptions'] =$subscriptions;
        
        //$page_data['users'] =$users;
        $page_data['view_path'] = 'subscriptions.transactions_report';
        return view('backend.index', $page_data);

        //return view('admin.transactions_report', compact('subscriptions', 'users'));
    }

    public function searchUsers(Request $request)
{
    $users = User::where('name', 'LIKE', "%{$request->search}%")->limit(10)->get();
    
    $output = '';
    if ($users->count() > 0) {
        foreach ($users as $user) {
            $output .= '<a href="#" class="list-group-item list-group-item-action user-option" data-id="'.$user->id.'">'.$user->name.'</a>';
        }
    } else {
        $output = '<div class="list-group-item">No users found</div>';
    }
    
    return response($output);
}


}
