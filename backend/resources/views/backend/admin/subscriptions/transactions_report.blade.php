<div class="container mt-4">
    <div class="card shadow-sm p-4">
        <h4 class="mb-3">Admin - Subscription Transactions</h4>

        <!-- Filters -->
        <form method="GET" action="{{ route('admin.transactions.report') }}">
            <div class="row g-3">
                <!-- User Search -->
                <div class="col-md-3 position-relative">
                    <label class="form-label">Search User</label>
                    <input type="text" id="userSearch" class="form-control" placeholder="Type user name..." autocomplete="off">
                    <input type="hidden" name="user_id" id="user_id" value="{{ request('user_id') }}">
                    <div id="userList" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1000;"></div>
                </div>

                <!-- Start Date -->
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>

                <!-- End Date -->
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>

                <!-- Buttons -->
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">🔎 Filter</button>
                    <a href="{{ route('admin.transactions.report') }}" class="btn btn-outline-secondary ms-2">🔄 Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Subscription Transactions Table -->
    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">📊 Transaction Details</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead >
                        <tr>
                            <th>User</th>
                            <th>Subscription</th>
                            <th>Status</th>
                            <th>Expires At</th>
                            <th>Transaction ID</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th>Transaction Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $subscription)
                        <tr>
                            <td>
                                <a href="{{ route('user.profile.view', $subscription->user_id) }}" class="text-decoration-none fw-bold text-primary">
                                    {{ $subscription->user_name }}
                                </a>
                            </td>
                            <td>{{ $subscription->subscription_name }}</td>
                            <td>
                                <span class="badge bg-{{ $subscription->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <td>{{ $subscription->expires_at }}</td>
                            <td><code>{{ $subscription->transaction_id }}</code></td>
                            <td>{{ ucfirst($subscription->payment_method) }}</td>
                            <td><strong>₹{{ number_format($subscription->amount, 2) }}</strong></td>
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

<!-- jQuery for User Search -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#userSearch').on('keyup', function () {
            let query = $(this).val();
            if (query.length > 1) {
                $.ajax({
                    url: "{{ route('admin.user.search') }}",
                    type: "GET",
                    data: { search: query },
                    success: function (data) {
                        $('#userList').html(data).fadeIn();
                    }
                });
            } else {
                $('#userList').fadeOut();
            }
        });

        // Select user from dropdown
        $(document).on('click', '.user-option', function () {
            let userId = $(this).data('id');
            let userName = $(this).text();
            $('#userSearch').val(userName);
            $('#user_id').val(userId);
            $('#userList').fadeOut();
        });

        // Hide dropdown when clicking outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#userSearch, #userList').length) {
                $('#userList').fadeOut();
            }
        });
    });
</script>
