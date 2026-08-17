
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h3 class="fw-bold text-primary"><i class="fas fa-edit"></i> Edit Lead Stage</h3>
        <a href="{{ route('enquiry-lead-stages.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('enquiry-lead-stages.update', $enquiryLeadStage->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="stage_name" class="form-label fw-bold"><i class="fas fa-tag"></i> Stage Name</label>
                    <input type="text" name="stage_name" class="form-control shadow-sm" value="{{ $enquiryLeadStage->stage_name }}" required>
                </div>

                <div class="mb-3">
                    <label for="for_role" class="form-label fw-bold"><i class="fas fa-user-tag"></i> Assign to</label>
                    <select name="for_role" class="form-select shadow-sm" required>
                        <option value="both" {{ $enquiryLeadStage->for_role == 'both' ? 'selected' : '' }}>Both Buyer & Seller</option>
                        <option value="buyer" {{ $enquiryLeadStage->for_role == 'buyer' ? 'selected' : '' }}>Buyer</option>
                        <option value="seller" {{ $enquiryLeadStage->for_role == 'seller' ? 'selected' : '' }}>Seller</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success shadow">
                        <i class="fas fa-save"></i> Update Stage
                    </button>
                    <a href="{{ route('enquiry-lead-stages.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
