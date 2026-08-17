<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h4 class="mb-3 text-primary">
            <i class="fa-solid fa-wallet"></i> {{ $user->name }} - Wallet Transactions
        </h4>

        <!-- Filters -->
        <form method="GET" action="{{ route('admin.wallet.transactions', $user->id) }}" class="mb-4">
            <div class="row g-3">
                <!-- Start Date -->
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>

                <!-- End Date -->
                <div class="col-md-4">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>

                <!-- Filter & Reset Buttons -->
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-50">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.wallet.transactions', $user->id) }}" class="btn btn-secondary w-50 ms-2">
                        <i class="fa-solid fa-undo"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Wallet Transactions Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Amount (₹)</th>
                        <th>Transaction ID</th>
                        <th>Bank Name</th>
                        <th>Payment Method</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                    <tr>
                        <td class="fw-bold text-success">₹{{ number_format($transaction->amount, 2) }}</td>
                        <td>{{ $transaction->transaction_id }}</td>
                        <td>{{ $transaction->bank_name ?: 'N/A' }}</td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($transaction->payment_method) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $transaction->type == 'credit' ? 'success' : 'danger' }}">
                                {{ ucfirst($transaction->type) }}
                            </span>
                        </td>
                        <td>{{ $transaction->description }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaction->created_at)->timezone(Auth::user()->timezone ?? 'Asia/Kolkata')->format('Y-m-d H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
