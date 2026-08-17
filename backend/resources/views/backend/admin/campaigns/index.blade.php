<div class="container py-5">
    <!-- Header with Create Button -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6 col-12 mb-2 mb-md-0">
            <h2 class="mb-0">📢 Campaigns</h2>
        </div>
        <div class="col-md-6 col-12 text-md-end text-start">
            <a href="{{ route('admin.campaigns.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Create Campaign
            </a>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Campaigns Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle text-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Template</th>
                            <th>Mailing List</th>
                            <th>Status</th>
                            <th>Scheduled At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($campaigns as $campaign)
                            <tr>
                                <td>{{ $campaign->name }}</td>
                                <td>{{ $campaign->template->name }}</td>
                                <td>{{ $campaign->mailingList->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $campaign->status == 'sent' ? 'success' : ($campaign->status == 'scheduled' ? 'primary' : 'secondary') }}">
                                        {{ ucfirst($campaign->status) }}
                                    </span>
                                </td>
                                <td>{{ $campaign->scheduled_at ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('admin.campaigns.edit', $campaign->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.campaigns.destroy', $campaign->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No campaigns found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
