<style>
    @media (max-width: 409px) {
        .btn .delete-icon {
            display: none !important;
        }
    }
</style>

<div class="container mt-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
        <h2 class="mb-0">Event Master List</h2>
        <a href="{{ route('admin.event.score.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-1"></i> Add New Event
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.event.index') }}" class="my-3">
        <div class="row g-2">
            <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by event name" value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-3">
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-6 col-md-3">
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-12 col-md-2 d-grid">
                <button type="submit" class="btn btn-info w-100"><i class="fa fa-filter me-1"></i> Filter</button>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table  table-hover mb-0">
                    <thead >
                        <tr>
                            <th>ID</th>
                            <th>Event Name</th>
                            <th>Score</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                        <tr>
                            <td>{{ $event->id }}</td>
                            <td>{{ $event->event_name }}</td>
                            <td>{{ $event->score }}</td>
                            <td>{{ $event->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('admin.event.score.edit', $event->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.event.score.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa fa-trash delete-icon"></i> Delete
                                        </button>

                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No Events Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-3 d-flex justify-content-center">
        {{ $events->links() }}
    </div>
</div>
