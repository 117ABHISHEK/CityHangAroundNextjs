<!DOCTYPE html>
<html>
<head>
    <title>Subscription Plans</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </noscript>

    <style>
        .subscription-card {
            transition: 0.2s;
        }
        .subscription-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.12);
        }
    </style>
</head>

<body class="bg-light">

<div class="container my-5">

    <h2 class="text-center mb-4">Subscription Management</h2>

    <h3 class="text-center mb-4">Choose Your Plan</h3>

    <div class="row justify-content-center">

        @foreach($subscriptions as $subscription)
        <div class="col-md-4 mb-4">
            <div class="card pricing-card text-center shadow-lg border-0 rounded-lg">

                <div class="card-header bg-primary text-white rounded-top p-4">
                    <h4>{{ $subscription->name }}</h4>

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

                    <!-- Public Page: No payment logic -->
                    <button class="btn btn-success mt-3 rounded-pill py-2 px-4">
                        Subscribe Now
                    </button>

                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>

</body>
</html>
