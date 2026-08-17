
<style>
    .conversation-icon {
    color:  #ff4b4b;  
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
        color: #fff;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0d6efd, #3b82f6);
        border: none;
        font-weight: 500;
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
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .table {
        margin-bottom: 0;
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
</style>
<div class="container mt-4">
    <h4 class="mb-4">
        <i class="fa-solid fa-comments conversation-icon"></i> Page Conversations
    </h4>

    {{-- ✅ Filter Form --}}
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
            <button type="submit" class="btn" style="background-color:#ff4b4b; color:white">
                <i class="fa-solid fa-filter me-1"></i> Filter
            </button>
            <a href="{{ route('admin.all.conversations.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-rotate-left me-1"></i> Reset
            </a>
        </div>
    </form>

    {{-- ✅ Table --}}

@if($conversations->count() > 0)
        <div class="table-responsive">
            <table class="table  table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Page Title</th>
                        <th>User</th>
                        <th>Started At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($conversations as $index => $conversation)
                        <tr>
                            <td>{{ $conversations->firstItem() + $index }}</td>
                            <td>
                                @php
        $page = $conversation->page;

        $citySlug = $page->city?->city_slug ?? 'city';
        $areaSlug = $page->area?->area_slug ?? 'area';

        // 🟦 Get last category (from pageCategories relation)
        $lastCategory = $page->pageCategories->last(); // 'pageCategories' is a many-to-many relationship
        $categorySlug = $lastCategory?->category_slug ?? 'category';

        $itemSlug = $page->item_slug ?? 'item';
    @endphp

    <a href="{{ route('single.page', [$citySlug, $areaSlug, $categorySlug, $itemSlug]) }}" target="_blank">
        {{ $page->title ?? 'Untitled Page' }}
    </a>
                            </td>
                            <td>
    @if($conversation->user)
        <a href="{{ route('user.profile.view', $conversation->user->id) }}" target="_blank">
            {{ $conversation->user->name }}
        </a>
    @else
        Guest User
    @endif
</td>
<td>{{ $conversation->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <a href="{{ route('admin.all.conversations.show', $conversation->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-paper-plane me-1"></i> View Chat
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ✅ Pagination --}}
        <div class="mt-3 mb-3 d-flex justify-content-center">
            {{ $conversations->appends(request()->input())->links() }}
        </div>

@else
    <div class="alert alert-info mt-4">
        <i class="fa-solid fa-circle-info me-1"></i> No conversations found.
    </div>
@endif
</div>

