<div class="container py-4">
    <h2 class="text-center mb-4">📋 Available Leads</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Filters Section -->
    <form method="GET" action="{{ route('leads.index') }}" class="mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" placeholder="Start Date">
            </div>
            <div class="col-md-3">
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" placeholder="End Date">
            </div>
            <div class="col-md-3">
                <select name="city" id="city" class="selectpicker eForm-control select2 @error('parent') is-invalid @enderror">
                    <option value="">Select City</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                            {{ $city->city_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
            <select name="area" id="area" class="selectpicker eForm-control select2 @error('parent') is-invalid @enderror">
    <option value="">Select Area</option>
    @if(request('city'))
        @php
            $selectedAreas = \App\Models\Area::where('city_id', request('city'))->get();
        @endphp
        @foreach($selectedAreas as $area)
            <option value="{{ $area->id }}" {{ request('area') == $area->id ? 'selected' : '' }}>
                {{ $area->area_name }}
            </option>
        @endforeach
    @endif
</select>

            </div>
            <div class="col-md-3">
                <select name="category" class="selectpicker eForm-control select2 @error('parent') is-invalid @enderror">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->product_category_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
                <a href="{{ route('leads.index') }}" class="btn btn-secondary">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </div>
        </div>
    </form>

    <!-- Leads Grid -->
 
     
     <!-- All Leads in One Table -->
<div class="table-responsive shadow-lg p-3 mb-4 bg-white rounded">
    <table class="table table-bordered table-striped">
        <thead class="bg-primary text-white">
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>City</th>
                <th>Product</th>
                <th>Categories</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leads as $lead)
                @php
                    $isPurchased = $lead->purchases()->where('user_id', auth()->id())->exists();
                    $categoryIds = explode(',', $lead->marketplace->category);
                    $leadCategories = App\Models\Category::whereIn('id', $categoryIds)->get();
                    $leadPrice = $lead->marketplace->master_category_lead_price ?? 0;
                    // Fallback to first category lead_price if master_category_lead_price is null/zero
                    if (!$leadPrice && $leadCategories->isNotEmpty()) {
                        $leadPrice = $leadCategories->first()->lead_price ?? 0;
                    }
                @endphp
                <tr>
                    <td>
                        <i class="fas fa-user-circle"></i> 
                        {{ $isPurchased ? $lead->name : str_repeat('*', strlen($lead->name) - 2) . substr($lead->name, -2) }}
                    </td>
                    <td>
                        <i class="fas fa-phone-alt text-success"></i> 
                        {{ $isPurchased ? $lead->mobileno : substr($lead->mobileno, 0, 2) . str_repeat('*', strlen($lead->mobileno) - 4) . substr($lead->mobileno, -2) }}
                    </td>
                    <td><i class="fas fa-city text-warning"></i>{{ $lead->marketplace?->page?->city?->city_name ?? 'N/A' }}</td>
                    <td><i class="fas fa-box text-info"></i> {{ optional($lead->marketplace)->title ?? 'N/A' }}</td>
                    <td>
                        <i class="fas fa-tags text-danger"></i>
                        @foreach($leadCategories as $category)
                            <span class="badge bg-secondary">{{ $category->product_category_name }}</span>
                        @endforeach
                    </td>
                    <td class="text-success">₹{{ $leadPrice }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('leads.view', $lead->id) }}" class="btn btn-info btn-sm d-flex align-items-center">
                                <i class="fas fa-eye me-1"></i> View
                            </a>
                            @if(!$isPurchased)
                                <button type="button" class="btn btn-success btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $lead->id }}">
                                    <i class="fas fa-shopping-cart me-1"></i> Buy
                                </button>
                            @else
                                <button class="btn btn-secondary btn-sm d-flex align-items-center" disabled>
                                    <i class="fas fa-check-circle me-1"></i> Purchased
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

     
    <!-- Load More Button -->
    <div class="d-flex justify-content-center mt-3">
        <button id="load-more" class="btn btn-primary" data-page="1">Load More</button>
    </div>
</div>

<!-- Payment Modals for each lead -->
@foreach($leads as $lead)
    @php
        $leadPrice = $lead->marketplace->master_category_lead_price ?? 0;
        // Fallback to first category lead_price if master_category_lead_price is null/zero
        if (!$leadPrice) {
            $categoryIds = explode(',', $lead->marketplace->category);
            $leadCategories = App\Models\Category::whereIn('id', $categoryIds)->get();
            if ($leadCategories->isNotEmpty()) {
                $leadPrice = $leadCategories->first()->lead_price ?? 0;
            }
        }
    @endphp
    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal{{ $lead->id }}" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Choose Payment Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>How would you like to pay for this lead?</p>
                    <div class="lead-info mb-3">
                        <p><strong>Lead:</strong> {{ $lead->name }}</p>
                        <p><strong>Price:</strong> ₹{{ $leadPrice }}</p>
                    </div>
                    <form id="paymentForm{{ $lead->id }}" method="POST">
                        @csrf
                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                        <input type="hidden" id="paymentMethod{{ $lead->id }}" name="payment_method" value="wallet">
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <div>
                        <button onclick="buyLead({{ $lead->id }}, {{ $leadPrice }}, 'wallet')" class="btn btn-primary me-2">
                            <i class="fas fa-wallet"></i> Wallet
                        </button>
                        <button onclick="buyLead({{ $lead->id }}, {{ $leadPrice }}, 'online')" class="btn btn-success">
                            <i class="fas fa-credit-card"></i> Online
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

<div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100vh; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function buyLead(leadId, amount, paymentMethod) {
    if (paymentMethod === 'wallet') {
        buyWithWallet(leadId, amount);
    } else if (paymentMethod === 'online') {
        buyWithRazorpay(leadId, amount);
    }
}

// ✅ Razorpay Payment Function
function buyWithRazorpay(leadId, amount) {
    if (isNaN(amount) || amount <= 0) {
        Swal.fire("Error", "Invalid amount. Please check and try again.", "error");
        return;
    }

    let loadingOverlay = document.getElementById("loading-overlay");
    loadingOverlay.style.display = "flex"; // Show loader

    fetch("{{ route('leads.buy.online') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ lead_id: leadId })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Server responded with status: " + response.status);
        }
        return response.json();
    })
    .then(data => {
        loadingOverlay.style.display = "none"; // Hide loader

        if (!data.success) {
            Swal.fire("Error", data.error || "Unknown error occurred", "error");
            return;
        }

        let options = {
            "key": "{{ env('RAZORPAY_KEY') }}",
            "amount": data.amount * 100, // Convert to paise
            "currency": "INR",
            "name": "Lead Purchase",
            "description": "Buying a lead",
            "order_id": data.order_id,
            "handler": function (response) {
                let form = document.createElement("form");
                form.method = "POST";
                form.action = "{{ route('user.payment.success') }}";
                form.innerHTML = `
                    @csrf
                    <input type="hidden" name="razorpay_payment_id" value="${response.razorpay_payment_id}">
                    <input type="hidden" name="razorpay_order_id" value="${response.razorpay_order_id}">
                    <input type="hidden" name="razorpay_signature" value="${response.razorpay_signature}">
                    <input type="hidden" name="lead_id" value="${leadId}">
                `;
                document.body.appendChild(form);
                form.submit();
            },
            "prefill": {
                "name": "{{ Auth::user()->name }}",
                "email": "{{ Auth::user()->email }}"
            },
            "theme": {
                "color": "#3399cc"
            }
        };

        let rzp = new Razorpay(options);
        rzp.open();
    })
    .catch(error => {
        console.error("Razorpay Order Error:", error);
        Swal.fire("Error", "Failed to create Razorpay order. Please try again.", "error");
        loadingOverlay.style.display = "none"; // Hide loader on error
    });
}


// ✅ Wallet Payment Function
function buyWithWallet(leadId, amount) {
    Swal.fire({
        title: "Confirm Wallet Payment",
        text: "Are you sure you want to pay ₹" + amount + " using your wallet?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Pay Now",
        cancelButtonText: "Cancel",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            let loadingOverlay = document.getElementById("loading-overlay");
            loadingOverlay.style.display = "flex"; // Show loader

            fetch("{{ route('leads.buy.wallet') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ lead_id: leadId })
            })
            .then(response => response.json())
            .then(data => {
                loadingOverlay.style.display = "none"; // Hide loader

                if (data.success) {
                    Swal.fire("Success", "Lead purchased successfully!", "success").then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire("Payment Failed", data.error || "Insufficient balance", "error");
                }
            })
            .catch(error => {
                console.error("Wallet payment error:", error);
                loadingOverlay.style.display = "none"; // Hide loader on error
            });
        }
    });
}
</script>


<script>
    // Initialize Select2 for filters
    $(document).ready(function() {
    $('.select2').select2();

    function loadAreas(cityId, selectedArea = null) {
        if (cityId) {
            $('#area').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('get.areas.by.city') }}",
                type: "GET",
                data: { city_id: cityId },
                success: function(response) {
                    $('#area').html('<option value="">Select Area</option>');
                    $.each(response, function(key, value) {
                        let selected = (selectedArea == value.id) ? 'selected' : '';
                        $('#area').append('<option value="' + value.id + '" ' + selected + '>' + value.area_name + '</option>');
                    });
                },
                error: function(xhr) {
                    console.error("Error fetching areas:", xhr.responseText);
                    $('#area').html('<option value="">Error loading areas</option>');
                }
            });
        } else {
            $('#area').html('<option value="">Select Area</option>');
        }
    }

    // Fetch areas when city is changed
    $('#city').change(function() {
        let cityId = $(this).val();
        loadAreas(cityId);
    });

    // Fetch areas on page load if a city is already selected
    let selectedCity = $('#city').val();
    let selectedArea = "{{ request('area') }}";  // Get the previously selected area
    if (selectedCity) {
        loadAreas(selectedCity, selectedArea);
    }
});


</script>