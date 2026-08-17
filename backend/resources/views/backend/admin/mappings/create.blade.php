<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Map Feature to Subscription</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.mappings.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <!-- Subscription Plan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Subscription Plan</label>
                            <select name="subscription_id" class="form-select" required>
                                <option value="">Select Plan</option>
                                @foreach($subscriptions as $subscription)
                                    <option value="{{ $subscription->id }}">{{ $subscription->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Feature -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Feature</label>
                            <select name="feature_id" class="form-select" required>
                                <option value="">Select Feature</option>
                                @foreach($features as $feature)
                                    <option value="{{ $feature->id }}">{{ $feature->feature_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Value -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Value</label>
                    <input type="text" name="value" class="form-control" value="{{ old('value') }}" placeholder="e.g. 10 Listings" required>
                </div>

                <!-- Buttons -->
                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Mapping
                    </button>
                    <a href="{{ route('admin.mappings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
