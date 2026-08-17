<div class="main_content bg-white p-3">
    <div class="mainSection-title mb-3">
        <h4>{{ get_phrase('All Tickets') }}</h4>
    </div>

    <!-- Filter Section -->
    <form method="GET" action="{{ route('admin.tickets.list') }}">
        <div class="row g-3 mb-4">
            <!-- From Date -->
            <div class="col-12 col-md-3">
                <label for="from_date">{{ get_phrase('From Date') }}</label>
                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>

            <!-- To Date -->
            <div class="col-12 col-md-3">
                <label for="to_date">{{ get_phrase('To Date') }}</label>
                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>

            <!-- Priority Filter -->
            <div class="col-12 col-md-3">
                <label for="priority">{{ get_phrase('Priority') }}</label>
                <select name="priority" id="priority" class="form-control">
                    <option value="">{{ get_phrase('All') }}</option>
                    <option value="Low" @selected(request('priority') == 'Low')>{{ get_phrase('Low') }}</option>
                    <option value="Medium" @selected(request('priority') == 'Medium')>{{ get_phrase('Medium') }}</option>
                    <option value="High" @selected(request('priority') == 'High')>{{ get_phrase('High') }}</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="col-12 col-md-3">
                <label for="status">{{ get_phrase('Status') }}</label>
                <select name="status" id="status" class="form-control">
                    <option value="">{{ get_phrase('All') }}</option>
                    <option value="Open" @selected(request('status') == 'Open')>{{ get_phrase('Open') }}</option>
                    <option value="In Progress" @selected(request('status') == 'In Progress')>{{ get_phrase('In Progress') }}</option>
                    <option value="Closed" @selected(request('status') == 'Closed')>{{ get_phrase('Closed') }}</option>
                </select>
            </div>

            <!-- Filter Button -->
            <div class="col-12 col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100 w-md-auto">
                    <i class="fas fa-search me-1 d-none d-sm-inline"></i> {{ get_phrase('Filter') }}
                </button>
            </div>
        </div>
    </form>

    <!-- Tickets Table -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Title</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Updated At</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $key => $ticket)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $ticket->user->name ?? "N/A" }}</td>
                    <td>{{ $ticket->title }}</td>
                    <td>{{ ucfirst($ticket->priority) }}</td>
                    <td>
                        <span class="badge 
                            @if($ticket->status == 'Open') bg-success 
                            @elseif($ticket->status == 'In Progress') bg-warning 
                            @else bg-danger 
                            @endif">
                            {{ $ticket->status }}
                        </span>
                    </td>
                    <td>{{ $ticket->updated_at->format('Y-m-d') }}</td>
                    <td class="text-center">
                        <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye me-1 d-none d-sm-inline"></i> View & Update
                            </a>
                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#commentsModal{{ $ticket->id }}">
                                <i class="fas fa-comments me-1 d-none d-sm-inline"></i> View Comments
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Comments Modal -->
                <div class="modal fade" id="commentsModal{{ $ticket->id }}" tabindex="-1" aria-labelledby="commentsModalLabel{{ $ticket->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="commentsModalLabel{{ $ticket->id }}">Comments for Ticket #{{ $ticket->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <ul class="list-group">
                                    @foreach ($ticket->comments as $comment)
                                        <li class="list-group-item">
                                            <strong>
                                                @if ($comment->admin)
                                                    Admin ({{ $comment->admin->name }})
                                                @else
                                                    {{ $comment->user->name }}
                                                @endif
                                            </strong>: 
                                            {{ $comment->comment }}
                                            @if ($comment->screenshot)
                                                <br>
                                                <a href="{{ asset('storage/' . $comment->screenshot) }}" target="_blank">View Screenshot</a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination-area d-flex justify-content-center mt-3">
            {{ $tickets->appends(request()->query())->links() }}
        </div>
    </div>

    @include('backend.footer')
</div>
