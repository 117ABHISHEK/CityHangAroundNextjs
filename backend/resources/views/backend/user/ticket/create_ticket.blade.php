<div class="main_content">
    <!-- Main section header and breadcrumb -->
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
                    <div class="d-flex flex-column">
                        <h4>{{ get_phrase('Create New Ticket') }}</h4>
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

    <!-- Start Admin area -->
    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap-2">
                <div class="eForm-layouts">
                    <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
                        @csrf
                        
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
                                   placeholder="{{ get_phrase('Enter Ticket Title') }}" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label eForm-label">{{ get_phrase('Description') }}</label>
                            <textarea class="form-control eForm-control" id="description" name="description" rows="4"
                                      placeholder="{{ get_phrase('Describe your issue...') }}" required></textarea>
                        </div>

                        <div class="mb-3">
                        <label for="priority" class="form-label eForm-label">{{ get_phrase('Priority') }}</label>
                        <select class="form-select eForm-control" id="priority" name="priority" required>
                            <option value="Low">{{ get_phrase('Low') }}</option>
                            <option value="Medium">{{ get_phrase('Medium') }}</option>
                            <option value="High">{{ get_phrase('High') }}</option>
                        </select>
                    </div>


                        <!-- Status -->
                        <div class="mb-3" hidden>
                            <label for="status" class="form-label eForm-label" >{{ get_phrase('Status') }}</label>
                            <select class="form-select eForm-control" id="status" name="status">
                                <option value="Open">{{ get_phrase('Open') }}</option>
                                <option value="In Progress">{{ get_phrase('In Progress') }}</option>
                                <option value="Closed">{{ get_phrase('Closed') }}</option>
                            </select>
                        </div>


                        <!-- Screenshot Upload -->
                    <div class="mb-3">
                        <label for="screenshot" class="form-label eForm-label">{{ get_phrase('Upload Screenshot') }}</label>
                        <input type="file" class="form-control eForm-control" id="screenshot" name="screenshot" accept="image/*">
                    </div>


                        <button type="submit" class="btn btn-primary">{{ get_phrase('Submit Ticket') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Footer -->
    @include('backend.footer')
    <!-- End Footer -->
</div>
