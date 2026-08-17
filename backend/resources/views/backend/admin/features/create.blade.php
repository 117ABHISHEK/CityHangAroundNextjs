<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h3 class="mb-4 text-center text-primary">Add New Feature</h3>

        <form action="{{ route('admin.features.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Feature Name</label>
                <input type="text" name="feature_name" class="form-control shadow-sm" placeholder="Enter feature name" required>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.features.index') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save"></i> Save Feature
                </button>
            </div>
        </form>
    </div>
</div>
