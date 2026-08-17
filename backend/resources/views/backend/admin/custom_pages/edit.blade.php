
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Page</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('custom_pages.update', $page->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label"><strong>Page Title:</strong></label>
                            <input type="text" name="title" class="form-control shadow-sm" value="{{ $page->title }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Page Content:</strong></label>
                            <textarea name="content" id="content" class="form-control eForm-control content" rows="6" placeholder="Enter page content...">{{ $page->content }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('custom_pages.list') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Update Page
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
