<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h3 class="mb-4 text-center text-warning">Edit Feature</h3>

        <form action="{{ route('admin.features.update', ['feature' => $feature->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Feature Name</label>
                <input type="text" name="feature_name" class="form-control shadow-sm" 
                       value="{{ $feature->feature_name }}" required>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.features.index') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save"></i> Update Feature
                </button>
            </div>
        </form>
    </div>
</div>
