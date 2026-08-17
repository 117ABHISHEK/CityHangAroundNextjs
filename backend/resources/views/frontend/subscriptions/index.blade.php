<style>
    .subscription-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 3rem 2.5rem;
        background:
            radial-gradient(circle at top right, rgba(255, 255, 255, 0.22), transparent 30%),
            linear-gradient(135deg, #ff6b3d 0%, #ff4939 45%, #1b2747 100%);
        color: #fff;
        box-shadow: 0 20px 48px rgba(27, 39, 71, 0.18);
    }

    .subscription-hero::after {
        content: "";
        position: absolute;
        inset: auto -60px -80px auto;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }

    .subscription-page .summary-card,
    .subscription-page .pricing-card,
    .subscription-page .history-card {
        border: 0;
        border-radius: 22px;
        box-shadow: 0 14px 36px rgba(15, 23, 42, 0.08);
    }

    .subscription-page .summary-card {
        background: #fff;
    }

    .subscription-page .pricing-card {
        position: relative;
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .subscription-page .pricing-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 42px rgba(15, 23, 42, 0.14);
    }

    .subscription-page .pricing-card.featured-plan {
        border: 2px solid rgba(255, 73, 57, 0.18);
        box-shadow: 0 24px 48px rgba(255, 73, 57, 0.16);
    }

    .subscription-page .plan-badge {
        position: absolute;
        top: 18px;
        right: 18px;
        z-index: 2;
        border-radius: 999px;
        padding: 0.45rem 0.8rem;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.04em;
    }

    .subscription-page .price-line {
        display: flex;
        align-items: baseline;
        gap: 0.65rem;
        flex-wrap: wrap;
    }

    .subscription-page .price-final {
        font-size: 2.35rem;
        font-weight: 800;
        color: #172554;
        line-height: 1;
    }

    .subscription-page .price-original {
        color: #94a3b8;
        text-decoration: line-through;
        font-weight: 600;
    }

    .subscription-page .discount-pill {
        border-radius: 999px;
        background: #fff3e8;
        color: #d9480f;
        padding: 0.3rem 0.7rem;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .subscription-page .feature-preview li,
    .subscription-page .feature-full li {
        display: flex;
        gap: 0.7rem;
        align-items: flex-start;
        margin-bottom: 0.8rem;
        color: #334155;
    }

    .subscription-page .feature-preview i,
    .subscription-page .feature-full i {
        color: #16a34a;
        margin-top: 0.2rem;
    }

    .subscription-page .current-plan-banner {
        border-radius: 20px;
        background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
        color: #fff;
    }

    .subscription-page .wallet-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.24);
        border-radius: 999px;
        padding: 0.65rem 1rem;
        font-weight: 600;
    }

    .subscription-page .action-stack .btn {
        border-radius: 14px;
        font-weight: 700;
        padding: 0.8rem 1rem;
    }

    .subscription-page .history-card .status-pill {
        border-radius: 999px;
        padding: 0.35rem 0.7rem;
        font-size: 0.76rem;
        font-weight: 700;
    }

    .subscription-page .history-meta {
        color: #64748b;
        font-size: 0.92rem;
    }

    .subscription-page .empty-state {
        border: 1px dashed #cbd5e1;
        border-radius: 20px;
        background: #fff;
    }

    @media (max-width: 767px) {
        .subscription-hero {
            padding: 2rem 1.4rem;
            border-radius: 22px;
        }

        .subscription-page .price-final {
            font-size: 1.95rem;
        }
    }
</style>

<div class="subscription-page">
    <section class="subscription-hero mb-4">
        <div class="row align-items-center gy-4 position-relative" style="z-index:1;">
            <div class="col-lg-8">
                <span class="text-uppercase fw-bold small d-inline-block mb-3" style="letter-spacing:0.18em;">CityHangAround Membership</span>
                <h1 class="display-6 fw-bold mb-3">Choose a plan that fits your city growth.</h1>
                <p class="mb-0 opacity-75 fs-5">Compare active plans, view feature mappings, pay with wallet or Razorpay, and track your current subscription status from one frontend page.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <span class="wallet-chip">
                    <i class="fa-solid fa-wallet"></i>
                    Wallet Balance: Rs {{ number_format($walletBalance, 2) }}
                </span>
            </div>
        </div>
    </section>

    @php
        $activeSubscriptions = collect($currentSubscriptions ?? [])->values();
        $primaryActive = $activeSubscriptions->sortBy('expires_at')->last();
    @endphp

    @if($primaryActive)
        @php
            $activePlan = $primaryActive['user_subscription']->subscription;
            $activeExpiry = $primaryActive['expires_at'];
        @endphp
        <div class="current-plan-banner p-4 mb-4">
            <div class="row align-items-center gy-3">
                <div class="col-lg-7">
                    <div class="text-uppercase small fw-bold opacity-75 mb-2">Current Plan</div>
                    <h2 class="h3 fw-bold mb-2">{{ $activePlan->name }}</h2>
                    <p class="mb-0 opacity-75">Status: {{ ucfirst($primaryActive['user_subscription']->status) }}. Expires on {{ $activeExpiry?->format('d M, Y') }} with {{ max($primaryActive['days_remaining'], 0) }} day(s) remaining.</p>
                </div>
                <div class="col-lg-5">
                    <div class="row g-3 text-center">
                        <div class="col-4">
                            <div class="fw-bold fs-4">{{ $activePlan->duration }}</div>
                            <div class="small opacity-75">Days</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-4">{{ $activePlan->features->count() }}</div>
                            <div class="small opacity-75">Features</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-4">{{ max($primaryActive['days_remaining'], 0) }}</div>
                            <div class="small opacity-75">Remaining</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="summary-card h-100 p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h3 class="h5 mb-0">Plan Summary</h3>
                    <span class="badge text-bg-light">{{ $subscriptions->count() }} Plans</span>
                </div>
                <p class="text-muted mb-3">Every plan below uses the existing Subscription, Feature, and Mapping data from admin without duplicating business logic.</p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge text-bg-success">Mapped Features</span>
                    <span class="badge text-bg-primary">Wallet Pay</span>
                    <span class="badge text-bg-warning text-dark">Razorpay</span>
                    <span class="badge text-bg-info">Responsive</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="summary-card h-100 p-4">
                <h3 class="h5 mb-3">Included Services</h3>
                <p class="text-muted mb-2">These are the actual service keys configured in the plan records:</p>
                <div class="history-meta">
                    {{ $subscriptions->pluck('offered_services')->filter()->implode(', ') ?: 'No service labels configured.' }}
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="summary-card h-100 p-4">
                <h3 class="h5 mb-3">Quick Actions</h3>
                <div class="d-grid gap-2">
                    <a href="{{ route('transactions.report') }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-file-lines me-2"></i>Transaction Report
                    </a>
                    <a href="{{ route('wallet.index') }}" class="btn btn-outline-success">
                        <i class="fa-solid fa-wallet me-2"></i>Open Wallet
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @foreach($subscriptions as $subscription)
            @php
                $finalPrice = $subscription->offer_price && $subscription->offer_price < $subscription->price
                    ? $subscription->offer_price
                    : $subscription->price;
                $discount = ($subscription->price > 0 && $subscription->offer_price && $subscription->offer_price < $subscription->price)
                    ? round((($subscription->price - $subscription->offer_price) / $subscription->price) * 100)
                    : 0;
                $currentPlan = $currentSubscriptions->get($subscription->id);
                $isPopular = $popularPlanId === $subscription->id;
                $isBestValue = $bestValuePlanId === $subscription->id;
                $isFree = $finalPrice <= 0;
                $badge = $isFree ? 'Free' : ($isBestValue ? 'Best Value' : ($isPopular ? 'Popular' : ($bestDiscountPlanId === $subscription->id && $discount > 0 ? 'Top Offer' : null)));
            @endphp
            <div class="col-xl-4 col-md-6">
                <div class="pricing-card h-100 p-4 bg-white {{ ($isPopular || $isBestValue) ? 'featured-plan' : '' }}">
                    @if($badge)
                        <span class="plan-badge {{ $isFree ? 'bg-success-subtle text-success' : ($isBestValue ? 'bg-warning-subtle text-warning-emphasis' : 'bg-primary-subtle text-primary') }}">
                            {{ $badge }}
                        </span>
                    @endif

                    <div class="mb-4">
                        <h2 class="h3 fw-bold mb-2">{{ $subscription->name }}</h2>
                        <div class="price-line mb-2">
                            <span class="price-final">Rs {{ number_format($finalPrice, 0) }}</span>
                            @if($discount > 0)
                                <span class="price-original">Rs {{ number_format($subscription->price, 0) }}</span>
                                <span class="discount-pill">{{ $discount }}% Off</span>
                            @endif
                        </div>
                        <div class="text-muted">{{ $subscription->duration }} days access</div>
                    </div>

                    <div class="mb-3">
                        <div class="small text-uppercase text-muted fw-bold mb-2">Feature Preview</div>
                        <ul class="feature-preview list-unstyled mb-0">
                            @forelse($subscription->features->take(4) as $feature)
                                <li>
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span><strong>{{ $feature->feature_name }}</strong>@if(!blank($feature->pivot->value)): {{ $feature->pivot->value }}@endif</span>
                                </li>
                            @empty
                                <li class="text-muted">No feature mapping added yet.</li>
                            @endforelse
                        </ul>
                    </div>

                    @if($subscription->features->count() > 4)
                        <button class="btn btn-link px-0 mb-3 text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#plan-features-{{ $subscription->id }}" aria-expanded="false">
                            View all {{ $subscription->features->count() }} features
                        </button>
                        <div class="collapse mb-3" id="plan-features-{{ $subscription->id }}">
                            <div class="border rounded-4 p-3 bg-light-subtle">
                                <ul class="feature-full list-unstyled mb-0">
                                    @foreach($subscription->features as $feature)
                                        <li>
                                            <i class="fa-solid fa-check"></i>
                                            <span><strong>{{ $feature->feature_name }}</strong>@if(!blank($feature->pivot->value)): {{ $feature->pivot->value }}@endif</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="mt-auto action-stack d-grid gap-2">
                        @if($currentPlan)
                            <div class="btn btn-outline-success disabled text-start">
                                <div class="fw-bold">Current Plan</div>
                                <small>Expires {{ optional($currentPlan['expires_at'])->format('d M, Y') }} • {{ max($currentPlan['days_remaining'], 0) }} day(s) left</small>
                            </div>
                        @elseif($isFree)
                            <a href="{{ route('user.subscribe.free', $subscription->id) }}" class="btn btn-outline-primary">
                                <i class="fa-solid fa-bolt me-2"></i>Activate Free Plan
                            </a>
                        @else
                            @if($walletBalance >= $finalPrice)
                                <button class="btn btn-outline-success" onclick="payWithWallet({{ $subscription->id }}, {{ $finalPrice }})">
                                    <i class="fa-solid fa-wallet me-2"></i>Pay With Wallet
                                </button>
                            @endif
                            <button class="btn btn-primary" onclick="payNow({{ $subscription->id }}, {{ $finalPrice }})">
                                <i class="fa-solid fa-credit-card me-2"></i>Subscribe Now
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <section class="mt-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <h2 class="h4 fw-bold mb-0">My Subscription History</h2>
            <span class="text-muted">{{ $mySubscriptions->count() }} record(s)</span>
        </div>

        @if($mySubscriptions->isNotEmpty())
            <div class="row g-4">
                @foreach($mySubscriptions as $data)
                    @php
                        $userSub = $data['user_subscription'];
                        $plan = $userSub->subscription;
                        $paymentTransaction = $data['payment_transaction'];
                        $walletTransaction = $data['wallet_transaction'];
                    @endphp
                    <div class="col-lg-6">
                        <div class="history-card h-100 bg-white p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3 gap-3">
                                <div>
                                    <h3 class="h5 fw-bold mb-1">{{ $plan->name }}</h3>
                                    <div class="history-meta">{{ $plan->duration }} days • {{ $plan->features->count() }} mapped features</div>
                                </div>
                                <span class="status-pill {{ $data['is_active'] ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                    {{ $data['is_active'] ? 'Active' : 'Expired' }}
                                </span>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <div class="text-muted small">Status</div>
                                    <div class="fw-semibold">{{ ucfirst($userSub->status) }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small">Expiry</div>
                                    <div class="fw-semibold">{{ optional($data['expires_at'])->format('d M, Y') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small">Days Remaining</div>
                                    <div class="fw-semibold">{{ $data['is_active'] ? max($data['days_remaining'], 0) : 0 }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small">Payment</div>
                                    <div class="fw-semibold">
                                        @if($paymentTransaction)
                                            {{ ucfirst($paymentTransaction->payment_method) }}
                                        @elseif($walletTransaction)
                                            Wallet
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($paymentTransaction || $walletTransaction)
                                <div class="border rounded-4 p-3 bg-light mb-3">
                                    <div class="text-muted small mb-1">Transaction</div>
                                    <div class="fw-semibold mb-1">
                                        Rs {{ number_format(abs(($paymentTransaction->amount ?? $walletTransaction->amount ?? 0)), 2) }}
                                    </div>
                                    <div class="history-meta">
                                        {{ $paymentTransaction->transaction_id ?? $walletTransaction->transaction_id ?? 'N/A' }}
                                    </div>
                                </div>
                            @endif

                            <div class="feature-full">
                                @foreach($plan->features->take(3) as $feature)
                                    <li>
                                        <i class="fa-solid fa-check"></i>
                                        <span><strong>{{ $feature->feature_name }}</strong>@if(!blank($feature->pivot->value)): {{ $feature->pivot->value }}@endif</span>
                                    </li>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state text-center py-5 px-4">
                <i class="fa-solid fa-crown text-muted mb-3" style="font-size:3rem;"></i>
                <h3 class="h5 mb-2">No subscriptions yet</h3>
                <p class="text-muted mb-0">Pick a plan above to unlock premium features, featured visibility, and service access configured by the existing admin subscription module.</p>
            </div>
        @endif
    </section>
</div>

<div id="subscription-loading-overlay" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.45);z-index:9999;align-items:center;justify-content:center;">
    <div class="spinner-border text-light" role="status" style="width:3rem;height:3rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function payNow(subscriptionId, amount) {
    const loadingOverlay = document.getElementById('subscription-loading-overlay');
    loadingOverlay.style.display = 'flex';

    fetch("{{ route('create.razorpay.order') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify({ amount: amount, subscription_id: subscriptionId })
    })
    .then(response => response.json())
    .then(data => {
        loadingOverlay.style.display = 'none';

        if (!data.success) {
            Swal.fire('Payment Error', data.message || 'Unable to create payment order.', 'error');
            return;
        }

        const options = {
            key: "{{ env('RAZORPAY_KEY') }}",
            amount: amount * 100,
            currency: 'INR',
            name: 'CityHangAround Subscription',
            description: 'Subscription Purchase',
            order_id: data.order_id,
            handler: function (response) {
                const form = document.createElement('form');
                form.method = 'POST';
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
            prefill: {
                name: @json(Auth::user()->name),
                email: @json(Auth::user()->email)
            },
            theme: {
                color: '#ff4939'
            }
        };

        new Razorpay(options).open();
    })
    .catch(() => {
        loadingOverlay.style.display = 'none';
        Swal.fire('Payment Error', 'Unable to initiate payment right now.', 'error');
    });
}

function payWithWallet(subscriptionId, amount) {
    Swal.fire({
        title: 'Confirm Wallet Payment',
        text: `Pay Rs ${amount} using your wallet balance?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Pay Now',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }

        fetch("{{ route('user.wallet.payment') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ subscription_id: subscriptionId, amount: amount })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Success', data.message || 'Subscription successful.', 'success').then(() => window.location.reload());
                return;
            }

            Swal.fire('Payment Failed', data.message || 'Wallet payment failed.', 'error');
        })
        .catch(() => {
            Swal.fire('Payment Failed', 'Unable to process wallet payment.', 'error');
        });
    });
}
</script>
