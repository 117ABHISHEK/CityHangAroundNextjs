
<div class="main_content">
    <!-- Main section header -->
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
                    <div class="d-flex flex-column">
                        <h4>{{ get_phrase('Edit Ticket') }}</h4>
                    </div>
                    <div class="export-btn-area">
                        <a href="{{ route('user.tickets') }}" class="export_btn" data-bs-toggle="tooltip"
                           data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                           data-bs-title="{{ get_phrase('Back') }}">{{ get_phrase('Back') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap-2">
                <div class="eForm-layouts">
                    <form method="POST" action="{{ route('tickets.update', $ticket->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Ticket Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label eForm-label">{{ get_phrase('Title') }}</label>
                            <input type="text" class="form-control eForm-control" id="title" name="title"
                                   value="{{ old('title', $ticket->title) }}" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label eForm-label">{{ get_phrase('Description') }}</label>
                            <textarea class="form-control eForm-control" id="description" name="description" rows="4" required>{{ old('description', $ticket->description) }}</textarea>
                        </div>

                        <!-- Priority -->
                        <div class="mb-3">
                            <label for="priority" class="form-label eForm-label">{{ get_phrase('Priority') }}</label>
                            <select class="form-select eForm-control" id="priority" name="priority" required>
                                <option value="Low" {{ $ticket->priority == 'Low' ? 'selected' : '' }}>{{ get_phrase('Low') }}</option>
                                <option value="Medium" {{ $ticket->priority == 'Medium' ? 'selected' : '' }}>{{ get_phrase('Medium') }}</option>
                                <option value="High" {{ $ticket->priority == 'High' ? 'selected' : '' }}>{{ get_phrase('High') }}</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label eForm-label">{{ get_phrase('Status') }}</label>
                            <select class="form-select eForm-control" id="status" name="status" required>
                                <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>{{ get_phrase('Open') }}</option>
                                <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>{{ get_phrase('In Progress') }}</option>
                                <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>{{ get_phrase('Closed') }}</option>
                            </select>
                        </div>

                        <!-- Screenshot Upload -->
                        <div class="mb-3">
                            <label for="screenshot" class="form-label eForm-label">{{ get_phrase('Upload Screenshot') }}</label>
                            <input type="file" class="form-control eForm-control" id="screenshot" name="screenshot" accept="image/*">
                            
                            @if ($ticket->screenshot)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $ticket->screenshot) }}" alt="Screenshot" width="150">
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">{{ get_phrase('Update Ticket') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('backend.footer')
</div>
