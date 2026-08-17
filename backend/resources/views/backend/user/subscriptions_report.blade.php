<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📄 My Subscription Transactions</h4>
        </div>
        <div class="card-body">

            <!-- Filters -->
            <form method="GET" action="{{ route('transactions.report') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter"></i> Filter</button>
                        <a href="{{ route('transactions.report') }}" class="btn btn-secondary"><i class="fas fa-sync"></i> Reset</a>
                    </div>
                </div>
            </form>

            <!-- Subscription Table -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Subscription</th>
                            <th>Status</th>
                            <!-- <th>Expires At</th> -->
                            <th>Transaction ID</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th>Transaction Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $subscription)
                        <tr>
                            <td><strong>{{ $subscription->subscription_name }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $subscription->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <!-- <td>{{ \Carbon\Carbon::parse($subscription->expires_at)->format('Y-m-d') }}</td> -->
                            <td>{{ $subscription->transaction_id }}</td>
                            <td>{{ ucfirst($subscription->payment_method) }}</td>
                            <td class="fw-bold text-success">₹{{ number_format($subscription->amount, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($subscription->transaction_date)->format('Y-m-d H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $subscriptions->links() }}
            </div>
        </div>
    </div>
</div>
