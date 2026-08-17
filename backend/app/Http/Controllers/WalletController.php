<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Payment_gateway;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
use Carbon\Carbon;
class WalletController extends Controller
{
    // Show User Wallet
    public function index(Request $request)
{
    $userId = Auth::id();

    // Fetch wallet details
    $page_data['wallet'] = Wallet::where('user_id', $userId)->first();

    // Fetch successful transactions with filters
    $transactions = WalletTransaction::where('user_id', $userId)
        ->where('status', 'successful')
        ->orderBy('created_at', 'desc');

    // Apply Date Range Filter (if provided)
    if ($request->has('start_date') && $request->has('end_date')) {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $transactions->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Apply Transaction Type Filter (credit/debit)
    if ($request->has('type') && in_array($request->type, ['credit', 'debit'])) {
        $transactions->where('type', $request->type);
    }

    $page_data['transactions'] = $transactions->paginate(10);

    // Fetch available payment gateways
    $page_data['paymentGateways'] = Payment_gateway::where('status', 1)->get();

    // Set the view path
    $page_data['view_path'] = 'wallet.index';

    return view('backend.index', $page_data);
}


    // Add Money to Wallet
    // public function addMoney(Request $request)
    // {
    //     $request->validate([
    //         'amount' => 'required|numeric|min:1',
    //     ]);

    //     $wallet = Wallet::firstOrCreate(['user_id' => Auth::id()], ['balance' => 0]);

    //     $wallet->balance += $request->amount;
    //     $wallet->save();

    //     WalletTransaction::create([
    //         'user_id' => Auth::id(),
    //         'amount' => $request->amount,
    //         'type' => 'credit',
    //         'description' => 'Money added to wallet'
    //     ]);

    //     return back()->with('success', 'Money added successfully!');
    // }

    public function addMoney(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            // 'payment_gateway' => 'required|string',
        ]);
        
        $request->validate(['amount' => 'required|numeric|min:1']);
        $amount = $request->amount;

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $order = $api->order->create([
            'amount' => $amount * 100,
            'currency' => 'INR',
            'payment_capture' => 1
        ]);

        WalletTransaction::create([
            'user_id' => Auth::id(),
            'amount' => $amount,
            'transaction_id' => $order['id'],
            'status' => 'pending',
            'type' => 'credit',
            'description' => 'Wallet Recharge via Razorpay'
        ]);

        //$page_data['wallet'] = $wallet;
        //$page_data['transactions'] = $transactions;
        $page_data['orderId'] = $order['id'];
        $page_data['amount'] = $amount;
        $page_data['key'] = env('RAZORPAY_KEY');

        $page_data['view_path'] = 'wallet.checkout';
        return view('backend.index', $page_data);
        //return view('wallet.checkout');

        // return view('wallet.checkout', [
        //     'orderId' => $order['id'],
        //     'amount' => $amount,
        //     'key' => env('RAZORPAY_KEY')
        // ]);
    }

    public function paymentSuccess(Request $request)
{
    $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    $payment = $api->payment->fetch($request->razorpay_payment_id);

    if ($payment->status === 'captured') {
        // Fetch Wallet Transaction
        $walletTransaction = WalletTransaction::where('transaction_id', $request->razorpay_order_id)->first();
        
        if ($walletTransaction) {
            // ✅ Update Wallet Transaction Status
            $walletTransaction->update([
                'status' => 'successful',
                'bank_name' => $payment->bank ?? 'Unknown',
                'payment_method' => $payment->method ?? 'Unknown'
            ]);

            // ✅ Insert into Payment Transactions
            PaymentTransaction::create([
                'user_id' => Auth::id(),
                'amount' => $walletTransaction->amount,
                'status' => 'successful',
                'transaction_id' => $request->razorpay_payment_id,
                'order_id' => $request->razorpay_order_id,
                'payment_method' => $payment->method ?? 'Unknown',
                'bank_name' => $payment->bank ?? 'Unknown',
                'description' => 'Wallet Recharge via Razorpay'
            ]);

            // ✅ Update Wallet Balance
            $wallet = Wallet::firstOrCreate(['user_id' => Auth::id()], ['balance' => 0]);
            $wallet->balance += $walletTransaction->amount;
            $wallet->save();
        }

        return redirect()->route('wallet.index')->with('success', 'Payment successful!');
    }

    return redirect()->route('wallet.index')->with('error', 'Payment failed.');
}



    // Use Wallet Money
    public function useMoney(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $wallet = Wallet::where('user_id', Auth::id())->first();

        if (!$wallet || $wallet->balance < $request->amount) {
            return back()->with('error', 'Insufficient balance!');
        }

        $wallet->balance -= $request->amount;
        $wallet->save();

        WalletTransaction::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'type' => 'debit',
            'description' => 'Money used from wallet'
        ]);

        return back()->with('success', 'Transaction successful!');
    }


    
}
