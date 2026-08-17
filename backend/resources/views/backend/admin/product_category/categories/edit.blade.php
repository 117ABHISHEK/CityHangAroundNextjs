
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit Lead Price</h4>
                </div>

                <div class="card-body">
                    <h5 class="text-center mb-3 text-muted">
                        {{ $category->product_category_name }}
                    </h5>

                    <form action="{{ route('categories.updateLeadPrice', $category->id) }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Lead Price</label>
                            <input type="number" step="0.01" name="lead_price" class="form-control" value="{{ old('lead_price', $category->lead_price) }}" required>
                            
                            @error('lead_price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Update Lead Price</button>
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back to Categories</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>