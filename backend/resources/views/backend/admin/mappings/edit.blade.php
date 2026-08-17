<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-warning text-white">
            <h4 class="mb-0">Edit Feature Mapping</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.mappings.update', ['mapping' => $mapping->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Subscription Plan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Subscription Plan</label>
                            <select name="subscription_id" class="form-select" required>
                                <option value="">Select Plan</option>
                                @foreach($subscriptions as $subscription)
                                    <option value="{{ $subscription->id }}" {{ $subscription->id == $mapping->subscription_id ? 'selected' : '' }}>
                                        {{ $subscription->name }}
                                    </option>
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
                                    <option value="{{ $feature->id }}" {{ $feature->id == $mapping->feature_id ? 'selected' : '' }}>
                                        {{ $feature->feature_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Value -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Value</label>
                    <input type="text" name="value" class="form-control" value="{{ $mapping->value }}" required>
                </div>

                <!-- Buttons -->
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Update Mapping
                    </button>
                    <a href="{{ route('admin.mappings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
