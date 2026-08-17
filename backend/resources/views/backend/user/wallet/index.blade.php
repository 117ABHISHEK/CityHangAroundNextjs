<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Wallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </noscript>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .wallet-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="wallet-card text-center">
                <h2 class="mb-3">My Wallet</h2>
                
                <div class="alert alert-info">
                    <h4>Current Balance</h4>
                    <h2 class="text-success">₹ {{ number_format($wallet->balance ?? 0.00, 2) }}</h2>
                </div>

                <!-- Add Money Form -->
                <form action="{{route('wallet.add')}}" method="POST" class="mb-3">
                    @csrf
                    <div class="input-group mb-2">
                        <input type="number" name="amount" class="form-control" placeholder="Enter amount" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Proceed to Pay</button>
                </form>
            </div>

            <!-- Transaction Filters -->
            <!-- Transaction Filters -->
<div class="wallet-card mt-4">
    <h4>Transaction History</h4>

    <form method="GET" action="{{ route('wallet.index') }}">
        <div class="row mb-3">
            <div class="col-6">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-6">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
        </div>

        <div class="mb-3">
            <label for="transaction_type" class="form-label">Transaction Type</label>
            <select name="type" id="transaction_type" class="form-control">
                <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All</option>
                <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">Search</button>
    </form>

    <ul class="list-group" id="transaction_list">
        @foreach($transactions as $transaction)
            <li class="list-group-item d-flex justify-content-between">
                <div>
                    <strong>{{ $transaction->description }}</strong><br>
                    <small>{{ \Carbon\Carbon::parse($transaction->created_at)->timezone(Auth::user()->timezone ?? 'Asia/Kolkata')->format('d-m-Y H:i') }}</small>
                </div>
                <span class="{{ $transaction->type == 'credit' ? 'text-success' : 'text-danger' }}">
                    ₹ {{ number_format($transaction->amount, 2) }}
                    ({{ ucfirst($transaction->type) }})
                </span>
            </li>
        @endforeach
    </ul>
    <!-- Laravel Pagination Links -->
<div class="mt-3">
    {{ $transactions->links() }}
</div>

</div>


        </div>
    </div>
</div>






<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
