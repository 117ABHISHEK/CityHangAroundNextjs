
<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-3">Wallet Recharge</h2>
        <p class="text-center text-muted">You're about to add <strong>₹{{ number_format($amount, 2) }}</strong> to your wallet.</p>

        <!-- Pay Now Button -->
        <div class="text-center mt-4">
            <button id="pay-now-btn" class="btn btn-success btn-lg">Pay Now</button>
        </div>

        <!-- Hidden Form for Payment -->
        <form id="razorpay-form" method="POST" action="{{ route('wallet.payment.success') }}">
            @csrf
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
            <input type="hidden" name="razorpay_signature" id="razorpay_signature">
        </form>

        <!-- Back Button -->
        <div class="text-center mt-3">
            <a href="{{ route('wallet.index') }}" class="btn btn-outline-secondary">← Back to Wallet</a>
        </div>
    </div>
</div>

<!-- Razorpay Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById("pay-now-btn").addEventListener("click", function() {
    var options = {
        "key": "{{ $key }}", // Razorpay Key
        "amount": "{{ $amount * 100 }}", // Convert to paise
        "currency": "INR",
        "name": "Wallet Recharge",
        "description": "Adding Money to Wallet",
        "order_id": "{{ $orderId }}",
        "handler": function (response) {
            // Autofill hidden form and submit
            document.getElementById("razorpay_payment_id").value = response.razorpay_payment_id;
            document.getElementById("razorpay_order_id").value = response.razorpay_order_id;
            document.getElementById("razorpay_signature").value = response.razorpay_signature;
            document.getElementById("razorpay-form").submit();
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
});
</script>
