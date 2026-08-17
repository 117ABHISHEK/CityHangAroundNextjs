<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Edit Subscription Plan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subscriptions.update', $subscription->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Plan Name -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Plan Name</label>
                            <input type="text" name="name" class="form-control border-2" value="{{ $subscription->name }}" required>
                        </div>

                        <!-- Services -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Services Offered</label>
                            <select name="services[]" id="servicesSelect" class="form-control select2" multiple required>
                                @foreach (['listings', 'event', 'marketplace', 'blogs', 'group'] as $service)
                                    <option value="{{ $service }}" {{ in_array($service, explode(',', $subscription->offered_services)) ? 'selected' : '' }}>
                                        {{ ucfirst($service) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Duration Grid -->
                        <div class="mb-3" id="durationGrid"></div>

                        <!-- Price -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Price (₹)</label>
                            <input type="number" name="price" class="form-control border-2" value="{{ $subscription->price }}" step="0.01" required>
                        </div>

                        <!-- Offer Price -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Offer Price (₹)</label>
                            <input type="number" name="offer_price" class="form-control border-2" value="{{ $subscription->offer_price }}" step="0.01">
                        </div>

                        <!-- Total Duration -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Plan Duration (Days)</label>
                            <input type="number" name="duration" class="form-control border-2" value="{{ $subscription->duration }}" required>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fa-solid fa-save"></i> Update Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    const servicesSelect = $('#servicesSelect');
    const durationGrid = $('#durationGrid');

    const prefilledDurations = {!! json_encode(json_decode($subscription->area_durations, true) ?? []) !!};

    function updateDurationGrid() {
        durationGrid.empty();
        const services = servicesSelect.val() || [];

        if (!services.length) return;

        let html = `<label class="form-label fw-bold">Duration Details (Optional):</label>`;

        services.forEach(service => {
            const cityValue = prefilledDurations?.[service]?.city ?? '';
            const areaValue = prefilledDurations?.[service]?.area ?? '';

            html += `
                <div class="card mb-3 border border-secondary-subtle">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">${service}</h5>

                        <div class="mb-2">
                            <label class="form-label">City Duration (in days)</label>
                            <input type="number" name="durations[${service}][city]" class="form-control" placeholder="e.g. 30" value="${cityValue}">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Area Duration (in days)</label>
                            <input type="number" name="durations[${service}][area]" class="form-control" placeholder="e.g. 15" value="${areaValue}">
                        </div>
                    </div>
                </div>
            `;
        });

        durationGrid.html(html);
    }

    $(document).ready(function () {
        $('.select2').select2();
        $('#servicesSelect').on('change', updateDurationGrid);
        updateDurationGrid(); // Trigger once for initial load
    });
</script>
