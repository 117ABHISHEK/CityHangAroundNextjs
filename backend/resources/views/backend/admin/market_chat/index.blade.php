<style>
    .conversation-icon {
        color: #0d6efd;
        margin-right: 10px;
    }

    .filter-row {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .filter-row .form-label {
        font-weight: 600;
        color: #333;
    }

    .btn-logo {
        background-color:#ff4939;
        border: none;
        font-weight: 500;
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #084cdf, #2563eb);
    }

    .btn-outline-secondary {
        border-color: #ced4da;
        color: #495057;
        font-weight: 500;
    }

    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
    }

    .table-responsive {
        border-radius: 10px;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch; 
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        background: white;
    }

    .table {
        margin-bottom: 0;
        min-width: 760px;
    }

    .table thead {
        background-color: #f1f3f5;
        font-weight: 600;
        color: #495057;
    }

    .table-hover tbody tr:hover {
        background-color: #f6fafe;
        transition: background-color 0.3s ease;
    }

    .table td, .table th {
        padding: 14px 16px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .btn-sm.btn-outline-primary {
        font-weight: 500;
        border-radius: 6px;
    }

    .alert-info {
        border-left: 4px solid #0d6efd;
        background: #f1f8ff;
        color: #0c5460;
    }

    .actions-cell {
        display: flex;
        gap: 8px;
        align-items: center;
        justify-content: flex-start;
        white-space: nowrap;
    }

    /* Responsive tweaks */
    @media (max-width: 767.98px) {
        .filter-row .col-md-3 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .filter-row .align-self-end.d-flex {
            justify-content: flex-start;
            gap: 8px;
        }


        .btn-sm.btn-outline-primary {
            min-width: 110px;
            white-space: nowrap;
            padding-left: 10px;
            padding-right: 10px;
            font-size: 0.9rem;
        }

        .table {
            min-width: 640px;
        }
    }

    @media (min-width: 768px) and (max-width: 991.98px) {
        .table {
            min-width: 720px;
        }
    }
    
    /* Pagination Centering */
    .pagination {
        justify-content: center !important; 
        margin: 20px;
        flex-wrap: wrap;
    }
    
    @media (max-width: 767.98px) {
        .pagination {
            font-size: 0.9rem;
            gap: 4px;
        }
    }

</style>


<div class="container mt-4">
    <h4 class="mb-4">
        <i class="fa-solid fa-comments conversation-icon"></i> Marketplace Conversations
    </h4>

    {{-- ilter Form --}}
    <form method="GET" class="filter-row row g-3">
        <div class="col-md-3">
            <label for="from" class="form-label">From Date</label>
            <input type="date" name="from" id="from" value="{{ request('from') }}" class="form-control">
        </div>

        <div class="col-md-3">
            <label for="to" class="form-label">To Date</label>
            <input type="date" name="to" id="to" value="{{ request('to') }}" class="form-control">
        </div>

        <div class="col-md-3 align-self-end d-flex gap-2">
            <button type="submit" class="btn btn-logo">
                <i class="fa-solid fa-filter me-1"></i> Filter
            </button>
            <a href="{{ route('admin.market.conversations.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-rotate-left me-1"></i> Reset
            </a>
        </div>
    </form>

    {{-- able --}}
    @if($conversations->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>User</th>
                        <th>Started At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($conversations as $index => $conversation)
                        <tr>
                            <td>{{ $conversations->firstItem() + $index }}</td>
                            <td>{{ $conversation->marketplace->title ?? 'Untitled Listing' }}</td>
                            <td>{{ $conversation->user->name ?? 'Guest User' }}</td>
                            <td>{{ $conversation->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <a href="{{ route('admin.all.market.conversations.show', $conversation->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-paper-plane me-1"></i> View Chat
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- agination --}}
        <div class="mt-3 d-flex justify-content-center">
            {{ $conversations->appends(request()->input())->links() }}
        </div>
    @else
        <div class="alert alert-info mt-4">
            <i class="fa-solid fa-circle-info me-1"></i> No marketplace conversations found.
        </div>
    @endif
</div>
