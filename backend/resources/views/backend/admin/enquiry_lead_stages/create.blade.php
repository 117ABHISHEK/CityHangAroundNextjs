
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h3 class="fw-bold text-primary"><i class="fas fa-plus-circle"></i> Add Lead Stage</h3>
        <a href="{{ route('enquiry-lead-stages.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('enquiry-lead-stages.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="stage_name" class="form-label fw-bold"><i class="fas fa-tag"></i> Stage Name</label>
                    <input type="text" name="stage_name" class="form-control shadow-sm" placeholder="Enter stage name" required>
                </div>

                <div class="mb-3">
                    <label for="for_role" class="form-label fw-bold"><i class="fas fa-user-tag"></i> Assign to</label>
                    <select name="for_role" class="form-select shadow-sm" required>
                        <option value="both">Both Buyer & Seller</option>
                        <option value="buyer">Buyer</option>
                        <option value="seller">Seller</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary shadow">
                        <i class="fas fa-save"></i> Save Stage
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
