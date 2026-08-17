<div class="main_content">
    <!-- Header -->
<div class="mainSection-title mb-3">
  <div class="row">
    <div class="col-12">
      <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
        <h4 class="mb-0">{{ get_phrase('All Tickets') }}</h4>
        <a href="{{ route('tickets.create') }}" class="btn btn-primary">
          + {{ get_phrase('Create Ticket') }}
        </a>
      </div>
    </div>
  </div>
</div>



    <!-- Filters -->
  <!-- Filters -->
<form id="filterForm">
  <div class="row g-3 align-items-end">
    
    <!-- Search Input -->
    <div class="col-12 col-md-6 col-lg-3">
      <input type="text" name="search" id="search" class="form-control" placeholder="{{ get_phrase('Search...') }}">
    </div>

    <!-- Priority Dropdown -->
    <div class="col-12 col-md-6 col-lg-3">
      <select name="priority" id="priority_filter" class="form-control">
        <option value="">{{ get_phrase('All Priorities') }}</option>
        <option value="Low">{{ get_phrase('Low') }}</option>
        <option value="Medium">{{ get_phrase('Medium') }}</option>
        <option value="High">{{ get_phrase('High') }}</option>
      </select>
    </div>

    <!-- Start Date -->
    <div class="col-12 col-md-6 col-lg-3">
      <input type="date" name="start_date" id="start_date" class="form-control">
    </div>

    <!-- End Date -->
    <div class="col-12 col-md-6 col-lg-3">
      <input type="date" name="end_date" id="end_date" class="form-control">
    </div>

    <!-- Buttons -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary w-100">{{ get_phrase('Filter') }}</button>
        <button type="reset" class="btn btn-secondary w-100" id="resetBtn">{{ get_phrase('Reset') }}</button>
      </div>
    </div>

  </div>
</form>





    <!-- Ticket Table -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="ticketTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ get_phrase('User') }}</th>
                            <th>{{ get_phrase('Title') }}</th>
                            <th>{{ get_phrase('Priority') }}</th>
                            <th>{{ get_phrase('Status') }}</th>
                            <th>{{ get_phrase('Created At') }}</th>
                            <th>{{ get_phrase('Screenshot') }}</th>
                            <th>{{ get_phrase('Comments') }}</th>
                            <th>{{ get_phrase('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $key => $ticket)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    <a href="{{ route('user.profile.view', $ticket->user_id) }}" class="text-dark">
                                        {{ $ticket->user->name ?? "N/A" }}
                                    </a>
                                    <br><small>{{ $ticket->user->email }}</small>
                                </td>
                                <td>{{ $ticket->title }}</td>
                                <td>
                                    <span class="badge bg-{{ $ticket->priority == 'Low' ? 'success' : ($ticket->priority == 'Medium' ? 'warning' : 'danger') }}">
                                        {{ $ticket->priority }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $ticket->status == 'Open' ? 'success' : ($ticket->status == 'In Progress' ? 'warning' : 'danger') }}">
                                        {{ $ticket->status }}
                                    </span>
                                </td>
                                <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                                <td>
                                    @if($ticket->screenshot)
                                        <a href="{{ asset('storage/screenshots/' . $ticket->screenshot) }}" target="_blank">
                                            <img src="{{ asset('storage/screenshots/' . $ticket->screenshot) }}" width="50">
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-secondary btn-sm view-comments" data-comments="{{ json_encode($ticket->comments) }}">
                                        {{ get_phrase('View Comments') }}
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-info btn-sm">{{ get_phrase('View') }}</a>
                                    <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="btn btn-warning btn-sm">{{ get_phrase('Edit') }}</a>
                                    <form action="{{ route('admin.tickets.destroy', $ticket->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ get_phrase('Are you sure?') }}')">
                                            {{ get_phrase('Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination-area">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Comments Modal -->
    <div class="modal fade" id="commentsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ get_phrase('Ticket Comments') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="commentsBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ get_phrase('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".view-comments").forEach(button => {
        button.addEventListener("click", function () {
            let comments = JSON.parse(this.dataset.comments);
            let commentsBody = document.getElementById("commentsBody");

            console.log("Comments Data:", comments); // Debugging output

            if (comments.length === 0) {
                commentsBody.innerHTML = "<p>No comments available.</p>";
            } else {
                commentsBody.innerHTML = comments.map(comment => {
                    let userName = comment.user ? comment.user.name : 
                                   comment.admin ? `Admin (${comment.admin.name})` : 'Unknown User';
                    let commentContent = comment.comment ?? 'No content available';
                    let createdAt = comment.created_at ? new Date(comment.created_at).toLocaleString() : 'N/A';
                    let screenshot = comment.screenshot ? 
                        `<br><a href="/storage/${comment.screenshot}" target="_blank">View Screenshot</a>` : '';

                    return `<div class="card p-2 mb-2">
                                <strong>${userName}:</strong>
                                <p>${commentContent}</p>
                                <small class="text-muted">${createdAt}</small>
                                ${screenshot}
                            </div>`;
                }).join('');
            }

            new bootstrap.Modal(document.getElementById("commentsModal")).show();
        });
    });
});
</script>

