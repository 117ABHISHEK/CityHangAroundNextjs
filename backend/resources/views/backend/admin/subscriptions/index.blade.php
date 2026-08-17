<style>
/* Prevent icon and text from stacking in buttons */
.btn i {
  vertical-align: middle;
  margin-right: 6px;
}
</style>
<style>
.table-responsive {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}


.table1 td {
 border: none !important;
}

</style>
<div class="container mt-4">
    <!-- Header with Add Subscription -->
    <div class="row align-items-center mb-3">
        <div class="col-12 col-md-9 mb-2 mb-md-0">
            <h2 class="text-primary mb-0">
                <i class="fa fa-list me-2 d-none d-sm-inline"></i> Subscription Plans
            </h2>
        </div>
        <div class="col-12 col-md-3 text-md-end">
            <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-success w-100 w-md-auto">
                <i class="fa fa-plus d-none d-sm-inline"></i> Add Subscription
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm p-3 mb-4">
        <form method="GET" action="{{ route('admin.subscriptions.index') }}">
            <div class="row g-2">
                <div class="col-12 col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Search by Name" value="{{ request()->get('name') }}">
                </div>
                <div class="col-6 col-md-3">
                    <input type="date" name="date" class="form-control" value="{{ request()->get('date') }}">
                </div>
                <div class="col-6 col-md-3">
                    <input type="number" name="duration" class="form-control" placeholder="Duration (days)" value="{{ request()->get('duration') }}">
                </div>
                <div class="col-12 col-md-3 d-flex flex-column flex-md-row gap-2">
                   <button type="submit" class="btn btn-primary w-100 w-md-auto d-flex align-items-center justify-content-center gap-1">
                        <i class="fa fa-search"></i>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary w-100 w-md-auto d-flex align-items-center justify-content-center gap-1">
                        <i class="fa fa-sync"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Subscription Table -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table1 table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Offer Price</th>
                        <th>Duration (Days)</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $key => $subscription)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $subscription->name }}</td>
                            <td>₹{{ number_format($subscription->price, 2) }}</td>
                            <td>
                                @if($subscription->offer_price)
                                    <span class="text-success">₹{{ number_format($subscription->offer_price, 2) }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $subscription->duration }} days</td>
                            <td>{{ $subscription->created_at->format('d M, Y') }}</td>
                            <td class="text-center">
                            <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                                <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" 
                           class="btn btn-sm btn-outline-primary px-3 d-flex align-items-center justify-content-center gap-1 mb-1">
                            <i class="fa fa-edit"></i>
                            <span>Edit</span>
                        </a>
                        
                        <form action="{{ route('admin.subscriptions.destroy', $subscription->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger px-3 d-flex align-items-center justify-content-center gap-1"
                                    onclick="return confirm('Are you sure?')">
                                <i class="fa fa-trash"></i>
                                <span>Delete</span>
                            </button>
                        </form>
                            </div>
                        </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No Subscriptions Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $subscriptions->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</div>
