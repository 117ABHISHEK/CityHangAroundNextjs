<div class="container my-4">
    <div class="card shadow-sm p-4">
        {{-- Success Message --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
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
                        <th class="border-bottom border-2">#</th>
                        <th class="border-bottom border-2">Name</th>
                        <th class="border-bottom border-2">Phone</th>
                        <th class="border-bottom border-2">Email</th>
                        <th class="border-bottom border-2">City</th>
                        <th class="border-bottom border-2">Query</th>
                        <th class="border-bottom border-2">Date</th>
                        <th class="border-bottom border-2 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                 
                    @forelse($queries as $i => $query)
                    <tr>
                      
                        <td class="text-muted small">{{ $queries->firstItem() + $i }}</td>
                        <td class="fw-semibold">{{ $query->name }}</td>
                        <td>{{ $query->phone }}</td>
                        <td>{{ $query->email }}</td>
                        <td>{{ $query->city }}</td>
                      
                     
                        <td style="max-width:260px; white-space:normal;">{{ $query->query }}</td>
                        <td class="text-nowrap">{{ $query->created_at->format('d M Y') }}</td>
                        <td class="text-center text-nowrap">
                            {{-- Edit --}}
                            <a href="{{ route('admin.contact.queries.edit', $query->id) }}"
                               class="btn btn-sm btn-outline-primary me-1"
                               title="Edit">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            {{-- Delete --}}
                            <form action="{{ route('admin.contact.queries.destroy', $query->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this query?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash3"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                    
                        <td colspan="8" class="text-center text-muted py-3">No queries found.</td>
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
   
    .table td, .table th { border: none !important; }
    .table thead th { border-bottom: 2px solid #dee2e6 !important; }
    .card { border-radius: 10px; }

   
</style>