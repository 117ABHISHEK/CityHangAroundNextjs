
<div class="main_content main_content bg-white p-3">
    <div class="mainSection-title">
        <h4>{{ get_phrase('Ticket Details') }}</h4>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="ticket-details">
                <h5><strong>Title:</strong> {{ $ticket->title }}</h5>
                <p><strong>Description:</strong> {{ $ticket->description }}</p>
                <p><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge 
                        @if($ticket->status == 'Open') bg-success 
                        @elseif($ticket->status == 'In Progress') bg-warning 
                        @else bg-danger 
                        @endif">
                        {{ $ticket->status }}
                    </span>
                </p>
                <p><strong>Created At:</strong> {{ $ticket->created_at->format('Y-m-d H:i:s') }}</p>

                <!-- Screenshot Preview -->
                @if($ticket->screenshot)
                <p><strong>Uploaded Screenshot:</strong></p>
                <img src="{{ asset('storage/'.$ticket->screenshot) }}" alt="Screenshot" width="300">
                @endif
            </div>

            <hr>

            <!-- Admin Update Form -->
            <h5>{{ get_phrase('Update Ticket') }}</h5>
            <form method="POST" action="{{ route('admin.tickets.update', $ticket->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="admin_comment" class="form-label">Admin Comment</label>
                    <textarea class="form-control" id="admin_comment" name="admin_comment" rows="3">{{ $ticket->admin_comment }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="screenshot" class="form-label">Upload Screenshot</label>
                    <input type="file" class="form-control" id="screenshot" name="screenshot" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary">Update Ticket</button>
            </form>
        </div>
    </div>
    @include('backend.footer')
</div>
