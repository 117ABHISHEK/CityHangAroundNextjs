<div class="container my-4">
    <div class="card shadow-sm p-4">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.contact.queries') }}" class="row g-3 align-items-end">
            <div class="col-md-3 col-sm-6">
                <label class="form-label fw-semibold">From</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control" />
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label fw-semibold">To</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control" />
            </div>
            <div class="col-md-3 col-sm-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                <a href="{{ route('admin.contact.queries') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>

        <!-- Table Section -->
        <div class="table-responsive mt-4">
            <table class="table table-sm align-middle mb-0">
                <thead class="text-black">
                    <tr>
                        <th class="border-bottom border-2">Name</th>
                        <th class="border-bottom border-2">Phone</th>
                        <th class="border-bottom border-2">Email</th>
                        <th class="border-bottom border-2">City</th>
                        <th class="border-bottom border-2">Query</th>
                        <th class="border-bottom border-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($queries as $query)
                    <tr>
                        <td>{{ $query->name }}</td>
                        <td>{{ $query->phone }}</td>
                        <td>{{ $query->email }}</td>
                        <td>{{ $query->city }}</td>
                        <td>{{ $query->query }}</td>
                        <td>{{ $query->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">No queries found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-center">
            {{ $queries->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<style>
    .table td,
    .table th {
        border: none !important;
    }

    .table thead th {
        border-bottom: 2px solid #dee2e6 !important;
    }

    .card {
        border-radius: 10px;
    }
</style>
