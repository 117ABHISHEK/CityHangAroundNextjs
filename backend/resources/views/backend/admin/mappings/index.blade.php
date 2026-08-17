<style>
.table-responsive {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}
.eTable {
  min-width: 760px; 
}
.row-number {
  color:black;
}
.eTable th, .eTable td {
 border: none !important;
}
.eTable thead tr {
  border-bottom: 2px solid black !important;
}
.eTable thead th {
  font-weight: 600;
  padding: 0.75rem 0.75rem;
}
</style>
<div class="container my-4">
    <h2 class="text-center mb-4">Subscription Feature Mappings</h2>

    <!-- Search & Filter Form -->
    <form method="GET" action="{{ route('admin.mappings.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Subscription</label>
            <select name="subscription_id" class="form-select">
                <option value="">All</option>
                @foreach($subscriptions as $subscription)
                    <option value="{{ $subscription->id }}" {{ request('subscription_id') == $subscription->id ? 'selected' : '' }}>
                        {{ $subscription->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Feature</label>
            <select name="feature_id" class="form-select">
                <option value="">All</option>
                @foreach($features as $feature)
                    <option value="{{ $feature->id }}" {{ request('feature_id') == $feature->id ? 'selected' : '' }}>
                        {{ $feature->feature_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>

        <div class="col-md-2">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Search</button>
        </div>
    </form>

    <!-- Add Button -->
    <div class="text-end mb-3">
        <a href="{{ route('admin.mappings.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Add Feature Mapping</a>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subscription</th>
                    <th>Feature</th>
                    <th>Value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mappings as $key => $mapping)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $mapping->subscription->name }}</td>
                    <td>{{ $mapping->feature->feature_name }}</td>
                    <td>{{ $mapping->value }}</td>
                   <td class="text-center text-md-start">
                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-2">
                        <a href="{{ route('admin.mappings.edit', $mapping->id) }}" class="btn btn-outline-primary btn-sm w-100 w-md-auto">
                            <!--<i class="fas fa-edit me-1 d-none d-sm-inline"></i> Edit-->
                            Edit
                        </a>
                        <form action="{{ route('admin.mappings.destroy', $mapping->id) }}" method="POST" class="w-100 w-md-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 w-md-auto" onclick="return confirm('Are you sure?')">
                                <!--<i class="fas fa-trash me-1 d-none d-sm-inline"></i> Delete-->
                                Delete
                            </button>
                        </form>
                    </div>
                </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3 d-flex justify-content-center mb-4">
        {{ $mappings->appends(request()->query())->links() }}
    </div>
</div>
