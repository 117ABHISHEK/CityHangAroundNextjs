<!-- Include SweetAlert2 for Attractive Alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .spinner-border {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .subscription-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .subscription-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .status-badge {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }
    .feature-list {
        max-height: 200px;
        overflow-y: auto;
    }
    .payment-info {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }
    .nav-pills .nav-link.active {
        background-color: #007bff;
    }
    .tab-content {
        margin-top: 20px;
    }
</style>

<div class="container my-5">
    <!-- Tab Navigation -->
    <div class="text-center mb-4">
        <h2 class="mb-3">Subscription Management</h2>
        <ul class="nav nav-pills justify-content-center" id="subscriptionTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="available-plans-tab" data-bs-toggle="pill" data-bs-target="#available-plans" type="button" role="tab">
                    <i class="fa fa-shopping-cart me-2"></i>Available Plans
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="my-subscriptions-tab" data-bs-toggle="pill" data-bs-target="#my-subscriptions" type="button" role="tab">
                    <i class="fa fa-crown me-2"></i>My Subscriptions
                    @if(count($mySubscriptions) > 0)
                        <span class="badge bg-success ms-1">{{ count($mySubscriptions) }}</span>
                    @endif
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="subscriptionTabContent">
        <!-- Available Plans Tab -->
        <div class="tab-pane fade show active" id="available-plans" role="tabpanel">
            <h3 class="text-center mb-4">Choose Your Plan</h3>
    <div class="row justify-content-center">
        @foreach($subscriptions as $subscription)
        <div class="col-md-4 mb-4">
            <div class="card pricing-card text-center shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white rounded-top p-4">
                    <h4 class="h3">{{ $subscription->name }}</h4>

                    @php
                        $finalPrice = $subscription->offer_price && $subscription->offer_price < $subscription->price 
                            ? $subscription->offer_price 
                            : $subscription->price;
                    @endphp

                    @if($subscription->offer_price && $subscription->offer_price < $subscription->price)
                        <h3 class="fw-bold">
                            <span class="text-danger" style="text-decoration: line-through;">
                                ₹{{ number_format($subscription->price) }}
                            </span>
                            <span class="text-success">
                                ₹{{ number_format($subscription->offer_price) }}
                            </span>
                            <span class="badge bg-warning text-dark ms-2">Limited Offer!</span>
                        </h3>
                    @else
                        <h3 class="fw-bold">₹{{ number_format($subscription->price) }}</h3>
                    @endif

                    <p class="mb-0"><small>Valid for {{ $subscription->duration }} days</small></p>
                </div>

                <div class="card-body p-4">
                    <ul class="list-unstyled">
                        @foreach($subscription->features as $feature)
                        <li class="py-2 border-bottom">
                            <strong>{{ $feature->feature_name }}</strong>: 
                            <span class="text-success">{{ $feature->pivot->value }}</span>
                        </li>
                        @endforeach
                    </ul>

                    @php
                        $userSubscription = Auth::user()->userSubscriptions()->where('subscription_id', $subscription->id)->first();
                        $today = \Carbon\Carbon::now();
                    @endphp

                    @if($userSubscription)
                        @php
                            $nextPurchaseDate = \Carbon\Carbon::parse($userSubscription->expires_at);
                        @endphp

                        @if($nextPurchaseDate->greaterThan($today))
                            <button class="btn btn-info mt-3 rounded-pill py-2 px-4" disabled>
                                Next Purchase: {{ $nextPurchaseDate->format('d M, Y') }}
                            </button>
                        @else
                            @if($finalPrice > 0)
                                @if($walletBalance >= $finalPrice)
                                    <button class="btn btn-warning mt-3 rounded-pill py-2 px-4" onclick="payWithWallet({{ $subscription->id }}, {{ $finalPrice }})">
                                        Pay with Wallet (₹{{ number_format($walletBalance) }})
                                    </button>
                                @endif
                                <button class="btn btn-success mt-3 rounded-pill py-2 px-4" onclick="payNow({{ $subscription->id }}, {{ $finalPrice }})">
                                    Pay with Razorpay
                                </button>
                            @else
                                <a href="{{ route('user.subscribe.free', $subscription->id) }}" class="btn btn-secondary mt-3 rounded-pill py-2 px-4">
                                    Equip
                                </a>
                            @endif
                        @endif
                    @else
                        @if($finalPrice > 0)
                            @if($walletBalance >= $finalPrice)
                                <button class="btn btn-warning mt-3 rounded-pill py-2 px-4" onclick="payWithWallet({{ $subscription->id }}, {{ $finalPrice }})">
                                    Pay with Wallet (₹{{ number_format($walletBalance) }})
                                </button>
                            @endif
                            <button class="btn btn-success mt-3 rounded-pill py-2 px-4" onclick="payNow({{ $subscription->id }}, {{ $finalPrice }})">
                                Pay with Razorpay
                            </button>
                        @else
                            <a href="{{ route('user.subscribe.free', $subscription->id) }}" class="btn btn-secondary mt-3 rounded-pill py-2 px-4">
                                Equip
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
        </div>
        <!-- End Available Plans Tab -->

        <!-- My Subscriptions Tab -->
        <div class="tab-pane fade" id="my-subscriptions" role="tabpanel">
            @if(count($mySubscriptions) > 0)
                <h3 class="text-center mb-4">My Active Subscriptions</h3>
                <div class="row">
                    @foreach($mySubscriptions as $data)
                        @php
                            $userSub = $data['user_subscription'];
                            $subscription = $userSub->subscription;
                            $paymentTransaction = $data['payment_transaction'];
                            $walletTransaction = $data['wallet_transaction'];
                            $isActive = $data['is_active'];
                            $daysRemaining = $data['days_remaining'];
                        @endphp
                        
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="card subscription-card h-100 border-0 shadow-sm">
                                <!-- Card Header -->
                                <div class="card-header {{ $isActive ? 'bg-success' : 'bg-secondary' }} text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">{{ $subscription->name }}</h5>
                                        <span class="status-badge badge {{ $isActive ? 'bg-light text-success' : 'bg-light text-secondary' }}">
                                            {{ $isActive ? 'Active' : 'Expired' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body">
                                    <!-- Subscription Details -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">Duration</small>
                                            <p class="mb-0 fw-bold">{{ $subscription->duration }} days</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Status</small>
                                            <p class="mb-0 fw-bold text-{{ $userSub->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($userSub->status) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Expiry Information -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">Expires On</small>
                                            <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($userSub->expires_at)->format('d M, Y') }}</p>
                                        </div>
                                        <div class="col-6">
                                            @if($isActive)
                                                <small class="text-muted">Days Remaining</small>
                                                <p class="mb-0 fw-bold text-success">{{ $daysRemaining }} days</p>
                                            @else
                                                <small class="text-muted">Expired</small>
                                                <p class="mb-0 fw-bold text-danger">{{ abs($daysRemaining) }} days ago</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Features -->
                                    @if($subscription->features && count($subscription->features) > 0)
                                        <div class="mb-3">
                                            <small class="text-muted">Features Included</small>
                                            <div class="feature-list mt-1">
                                                @foreach($subscription->features as $feature)
                                                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                        <span class="text-dark">{{ $feature->feature_name }}</span>
                                                        <span class="badge bg-primary">{{ $feature->pivot->value }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Payment Information -->
                                    @if($paymentTransaction || $walletTransaction)
                                        <div class="payment-info">
                                            <h6 class="text-dark mb-2">
                                                <i class="fa fa-credit-card me-1"></i> Payment Details
                                            </h6>
                                            @if($paymentTransaction)
                                                <div class="row">
                                                    <div class="col-6">
                                                        <small class="text-muted">Amount Paid</small>
                                                        <p class="mb-1 fw-bold text-success">₹{{ number_format($paymentTransaction->amount, 2) }}</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">Payment Method</small>
                                                        <p class="mb-1 fw-bold">{{ ucfirst($paymentTransaction->payment_method) }}</p>
                                                    </div>
                                                    @if($paymentTransaction->transaction_id)
                                                        <div class="col-12 mt-2">
                                                            <small class="text-muted">Transaction ID</small>
                                                            <p class="mb-0 fw-bold text-break">{{ $paymentTransaction->transaction_id }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @elseif($walletTransaction)
                                                <div class="row">
                                                    <div class="col-6">
                                                        <small class="text-muted">Amount Paid</small>
                                                        <p class="mb-1 fw-bold text-success">₹{{ number_format($walletTransaction->amount, 2) }}</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">Payment Method</small>
                                                        <p class="mb-1 fw-bold">Wallet</p>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="mt-2">
                                                <small class="text-muted">Purchase Date</small>
                                                <p class="mb-0 fw-bold">
                                                    {{ \Carbon\Carbon::parse($paymentTransaction ? $paymentTransaction->created_at : $walletTransaction->created_at)->timezone(Auth::user()->timezone ?? 'Asia/Kolkata')->format('d M, Y H:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Card Footer -->
                                <div class="card-footer bg-transparent">
                                    @if(!$isActive)
                                        <button class="btn btn-primary btn-sm w-100" onclick="document.getElementById('available-plans-tab').click();">
                                            <i class="fa fa-refresh me-1"></i> Renew Subscription
                                        </button>
                                    @else
                                        <div class="text-center">
                                            <span class="text-success">
                                                <i class="fa fa-check-circle me-1"></i> Subscription Active
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Quick Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <h5 class="mb-3">Quick Actions</h5>
                                <div class="d-flex flex-wrap justify-content-center gap-2">
                                    <button class="btn btn-outline-primary" onclick="document.getElementById('available-plans-tab').click();">
                                        <i class="fa fa-eye me-1"></i> View All Plans
                                    </button>
                                    <a href="{{ route('transactions.report') }}" class="btn btn-outline-info">
                                        <i class="fa fa-file-alt me-1"></i> Transaction Report
                                    </a>
                                    <a href="{{ route('wallet.index') }}" class="btn btn-outline-success">
                                        <i class="fa fa-wallet me-1"></i> My Wallet
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- No Subscriptions State -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <div class="mb-4">
                                    <i class="fa fa-crown text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h4 class="text-muted mb-3">No Subscriptions Found</h4>
                                <p class="text-muted mb-4">You haven't purchased any subscription plans yet. Choose a plan that suits your needs and start enjoying premium features.</p>
                                <button class="btn btn-primary btn-lg" onclick="document.getElementById('available-plans-tab').click();">
                                    <i class="fa fa-shopping-cart me-2"></i> View Available Plans
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!-- End My Subscriptions Tab -->
    </div>
    <!-- End Tab Content -->
</div>

<div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100vh; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function payNow(subscriptionId, amount) {
    let loadingOverlay = document.getElementById("loading-overlay");
    
    // ✅ Show loading overlay before making the API call
    loadingOverlay.style.display = "flex";
    fetch("{{ route('create.razorpay.order') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ amount: amount, subscription_id: subscriptionId }) // ✅ Send subscription_id
    })
    .then(response => response.json())
    .then(data => {
       // ✅ Hide loading overlay after getting response
       loadingOverlay.style.display = "none";

        if (!data.success) {
            alert("Error creating order: " + data.message);
            return;
        }

        var options = {
            "key": "{{ env('RAZORPAY_KEY') }}",
            "amount": amount * 100, 
            "currency": "INR",
            "name": "Subscription Purchase",
            "description": "Purchasing Subscription Plan",
            "order_id": data.order_id, // ✅ Get correct order ID
            "handler": function (response) {
                var form = document.createElement("form");
                form.method = "POST";
                form.action = "{{ route('user.subscription.payment.success') }}";
                form.innerHTML = `
                    @csrf
                    <input type="hidden" name="razorpay_payment_id" value="${response.razorpay_payment_id}">
                    <input type="hidden" name="razorpay_order_id" value="${response.razorpay_order_id}">
                    <input type="hidden" name="razorpay_signature" value="${response.razorpay_signature}">
                    <input type="hidden" name="subscription_id" value="${subscriptionId}">
                `;
                document.body.appendChild(form);
                form.submit();
            },
            "prefill": {
                "name": "{{ Auth::user()->name }}",
                "email": "{{ Auth::user()->email }}"
            },
            "theme": {
                "color": "#28a745"
            }
        };
        var rzp = new Razorpay(options);
        rzp.open();
    })
    .catch(error => {
        console.error("Error creating order:", error);
        loadingOverlay.style.display = "none"; // ✅ Hide loader on fetch error
    });
}

function payWithWallet(subscriptionId, amount) {
    Swal.fire({
        title: "Confirm Payment",
        text: "Are you sure you want to pay ₹" + amount + " with your wallet?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Pay Now",
        cancelButtonText: "Cancel",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("{{ route('user.wallet.payment') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ subscription_id: subscriptionId, amount: amount })

            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire("Success", "Subscription successful!", "success").then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire("Payment Failed", data.message, "error");
                }
            })
            .catch(error => console.error("Wallet payment error:", error));
        }
    });
}

// Tab switching and animations
document.addEventListener('DOMContentLoaded', function() {
    // Add animation to cards on page load
    const cards = document.querySelectorAll('.subscription-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Handle tab switching
    const tabButtons = document.querySelectorAll('#subscriptionTabs button[data-bs-toggle="pill"]');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function(e) {
            // Re-animate cards when switching to My Subscriptions tab
            if (e.target.getAttribute('data-bs-target') === '#my-subscriptions') {
                setTimeout(() => {
                    const mySubscriptionCards = document.querySelectorAll('#my-subscriptions .subscription-card');
                    mySubscriptionCards.forEach((card, index) => {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, index * 100);
                    });
                }, 100);
            }
        });
    });

    // Check if there's a hash in URL to switch to My Subscriptions tab
    if (window.location.hash === '#my-subscriptions') {
        document.getElementById('my-subscriptions-tab').click();
    }
});

</script>
