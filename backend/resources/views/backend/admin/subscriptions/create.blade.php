<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Add New Subscription Plan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subscriptions.store') }}" method="POST">
                        @csrf
                        
                        <!-- Plan Name -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Plan Name</label>
                            <input type="text" name="name" class="form-control border-2" placeholder="Enter Plan Name" required>
                        </div>

                        <!-- Services -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Services Offered</label>
                            <select name="services[]" id="servicesSelect" class="form-control select2" multiple required>
                                <option value="listings">Listings</option>
                                <option value="event">Event</option>
                                <option value="marketplace">Marketplace</option>
                                <option value="blogs">Blogs</option>
                                <option value="group">Group</option>
                            </select>
                        </div>

                        <!-- Duration Inputs -->
                        <div class="mb-3" id="durationGrid"></div>

                        <!-- Price -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Price (₹)</label>
                            <input type="number" name="price" class="form-control border-2" step="0.01" required>
                        </div>

                        <!-- Offer Price -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Offer Price (₹)</label>
                            <input type="number" name="offer_price" class="form-control border-2" step="0.01">
                        </div>

                        <!-- Total Duration -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Plan Duration (Days)</label>
                            <input type="number" name="duration" class="form-control border-2" required>
                        </div>

                        <!-- Submit -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fa-solid fa-save"></i> Save Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script>
    const servicesSelect = $('#servicesSelect');
    const durationGrid = $('#durationGrid');

    function updateDurationGrid() {
        durationGrid.empty();
        const services = servicesSelect.val() || [];

        if (!services.length) return;

        let html = `<label class="form-label fw-bold">Duration Details (Optional):</label>`;

        services.forEach(service => {
            html += `
                <div class="card mb-3 border border-secondary-subtle">
                    <div class="card-body">
                        <h5 class="card-title">${service}</h5>

                        <div class="mb-2">
                            <label class="form-label">City Duration (in days)</label>
                            <input type="number" name="durations[${service}][city]" class="form-control" placeholder="e.g. 30">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Area Duration (in days)</label>
                            <input type="number" name="durations[${service}][area]" class="form-control" placeholder="e.g. 15">
                        </div>
                    </div>
                </div>
            `;
        });

        durationGrid.html(html);
    }

    $(document).ready(function () {
        $('.select2').select2(); // If you're using Select2
        $('#servicesSelect').on('change', updateDurationGrid);
    });
</script>
