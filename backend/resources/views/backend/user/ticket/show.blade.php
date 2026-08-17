
<div class="main_content">
    <!-- Main section header -->
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
                    <div class="d-flex flex-column">
                        <h4>{{ get_phrase('Ticket Details') }}</h4>
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

    <!-- Ticket Details -->
    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap-2">
                <div class="eForm-layouts">
                    
                    <!-- Ticket Title -->
                    <div class="mb-3">
                        <label class="form-label eForm-label">{{ get_phrase('Title') }}</label>
                        <input type="text" class="form-control eForm-control" value="{{ $ticket->title }}" readonly>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label eForm-label">{{ get_phrase('Description') }}</label>
                        <textarea class="form-control eForm-control" rows="4" readonly>{{ $ticket->description }}</textarea>
                    </div>

                    <!-- Priority -->
                    <div class="mb-3">
                        <label class="form-label eForm-label">{{ get_phrase('Priority') }}</label>
                        <input type="text" class="form-control eForm-control" value="{{ $ticket->priority }}" readonly>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label eForm-label">{{ get_phrase('Status') }}</label>
                        <input type="text" class="form-control eForm-control" value="{{ $ticket->status }}" readonly>
                    </div>

                    <!-- Screenshot Display -->
                    @if ($ticket->screenshot)
                        <div class="mb-3">
                            <label class="form-label eForm-label">{{ get_phrase('Screenshot') }}</label>
                            <div>
                                <img src="{{ asset('storage/' . $ticket->screenshot) }}" class="img-fluid rounded" alt="Screenshot">
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Start Footer -->
    @include('backend.footer')
    <!-- End Footer -->
</div>