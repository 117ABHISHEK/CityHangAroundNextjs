<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Payment</title>

    <!-- Bootstrap CSS (For Modern UI) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </noscript>

    <!-- Razorpay Script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js" defer></script>
</head>
<body>

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-3">Purchase Lead</h2>
        <p class="text-center text-muted">
            You're about to purchase the lead <strong>{{ $lead->name }}</strong> for <strong>₹{{ number_format($amount, 2) }}</strong>.
        </p>

        <!-- Pay Now Button -->
        <div class="text-center mt-4">
            <button id="pay-now-btn" class="btn btn-success btn-lg">Proceed to Payment</button>
        </div>

        <!-- Hidden Form for Payment Submission -->
        <form id="razorpay-form" method="POST" action="{{ route('user.payment.success') }}">
            @csrf
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
            <input type="hidden" name="razorpay_signature" id="razorpay_signature">
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
        </form>

        <!-- Back Button -->
        <div class="text-center mt-3">
            <a href="{{ route('leads.index') }}" class="btn btn-outline-secondary">← Back to Leads</a>
        </div>
    </div>
</div>

<!-- Razorpay Payment Script -->
<script>
document.getElementById("pay-now-btn").addEventListener("click", function() {
    var options = {
        "key": "{{ $key }}",  // Razorpay API Key
        "amount": "{{ $amount * 100 }}",  // Convert to paise
        "currency": "INR",
        "name": "Lead Purchase",
        "description": "Purchasing Lead: {{ $lead->name }}",
        "order_id": "{{ $order_id }}",
        "handler": function (response) {
            // Autofill hidden form and submit
            document.getElementById("razorpay_payment_id").value = response.razorpay_payment_id;
            document.getElementById("razorpay_order_id").value = response.razorpay_order_id;
            document.getElementById("razorpay_signature").value = response.razorpay_signature;
            document.getElementById("razorpay-form").submit();
        },
        "prefill": {
            "name": "{{ Auth::user()->name }}",
            "email": "{{ Auth::user()->email }}",
            "contact": "{{ Auth::user()->phone }}"
        },
        "theme": {
            "color": "#28a745"
        }
    };
    var rzp = new Razorpay(options);
    rzp.open();
});
</script>

</body>
</html>
