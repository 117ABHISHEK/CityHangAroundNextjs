
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-warning text-white">
            <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Event</h4>
        </div>
        <div class="card-body">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Oops! Something went wrong.</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Edit Event Form -->
            <form action="{{ route('admin.event.score.update', $event->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-calendar-alt"></i> Event Name</label>
                    <input type="text" name="event_name" class="form-control" value="{{ old('event_name', $event->event_name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-star"></i> Score</label>
                    <input type="number" name="score" class="form-control" value="{{ old('score', $event->score) }}" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.event.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
