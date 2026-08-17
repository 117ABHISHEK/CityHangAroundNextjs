
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-file-alt"></i> Create a Custom Page</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('custom_pages.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label"><strong>Page Title:</strong></label>
                            <input type="text" name="title" class="form-control shadow-sm" placeholder="Enter page title" required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label"><strong>Page Content:</strong></label>
                            <textarea name="content" id="content" class="form-control eForm-control content" rows="6" placeholder="Write your content here..."></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check-circle"></i> Create Page
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
