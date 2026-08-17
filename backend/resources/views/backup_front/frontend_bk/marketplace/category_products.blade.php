
  
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>              
<div class="marketplace-wrap">
   


    <!-- Product Listing Start -->
    <h1 class="font-weight-light text-primary">Top {{ $category->product_category_name }} Deals & Offers – Save More Today!</h1>
    <div class="product-listing">
    
        <div class="row g-3" id="@if(str_contains(url()->current(), '/productdata')) single-item-countable @endif">
            @include('frontend.marketplace.product-single')
        </div>
    </div>
</div>


<div class="container mt-4 p-4 bg-white shadow-sm rounded" hidden>
    <h1 class="text-center text-dark">Find the Best {{ $category->category_name }} Deals Near You</h1>

    <h2 class="mt-4 text-primary">Exclusive {{ $category->category_name }} Discounts You Can’t Miss</h2>
    <h2 class="mt-3 text-dark">Top Trending {{ $category->category_name }} Deals This Month</h2>

    <p class="mt-4 text-muted">
        Looking for amazing <strong>{{ $category->category_name }}</strong> deals? <strong>City Hangaround</strong> brings you the best discounts on restaurants, shopping, entertainment, and more. Whether you’re dining out, getting a spa treatment, or shopping for your favorite brands, we’ve got the hottest offers to help you save big!
    </p>

    <h3 class="mt-4 text-danger">🔥 Trending {{ $category->category_name }} Deals Today</h3>
    <ul class="list-unstyled mt-3">
        <li>✅ <strong>Popular Restaurant</strong> – Enjoy Flat 30% Off on All Orders 🍽️</li>
        <li>✅ <strong>[Fashion Store]</strong> – Get Up to 50% Off on Top Brands 🛍️</li>
        <li>✅ <strong>[Luxury Spa]</strong> – Buy 1 Get 1 Free on All Massages 💆‍♂️</li>
        <li>✅ <strong>[Movie Theater]</strong> – Book Movie Tickets at 40% Off 🎬</li>
    </ul>

    <p class="mt-3">
        🔎 Want more discounts? Explore our <strong>{{ $category->category_name }}</strong> offers and grab the best deals today!
    </p>
    <p class="mt-3">
        📍 <strong>Location-Based Offers:</strong> Discover top <strong>{{ $category->category_name }}</strong> deals near your location and save money at top-rated places.
    </p>
    <p class="mt-3">
        📢 <strong>Are You a Business Owner?</strong> List your <strong>{{ $category->category_name }}</strong> deals for <strong>FREE</strong> on City Hangaround and attract more customers today!
    </p>

    <h3 class="mt-4 text-dark font-weight-bold">FAQs: {{ $category->category_name }} Deals</h3>
    
    <div class="mt-3">
        <h4 class="text-dark">1. What types of {{ $category->category_name }} deals are available?</h4>
        <p>We offer exclusive discounts on {{ $category->category_name }} services, including restaurants, salons, shopping, entertainment, and more.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">2. How do I claim a {{ $category->category_name }} deal?</h4>
        <p>Click on the deal, check the details, and follow the instructions to redeem it online or at the store. Some deals may require a promo code.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">3. Do I need to sign up to access {{ $category->category_name }} deals?</h4>
        <p>No, browsing deals is free. However, signing up allows you to save your favorite offers and receive alerts for new deals.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">4. How often are {{ $category->category_name }} deals updated?</h4>
        <p>We update our {{ $category->category_name }} deals daily, so you always get the best discounts and exclusive offers!</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">5. Can businesses list their {{ $category->category_name }} deals on City Hangaround?</h4>
        <p>Yes! If you own a {{ $category->category_name }} business, you can list your deals for free and reach more customers. Visit our <a href="{{ route('pages.create.product') }}" class="text-primary">Business Listing</a> page to get started.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">6. What if a {{ $category->category_name }} deal is not working?</h4>
        <p>If a deal isn’t working, check the terms & conditions or <a href="{{ route('contact.view') }}" class="text-primary">contact our support team</a> for assistance.</p>
    </div>
</div>



@section('specific_code_niceselect')
    $('select').niceSelect();
@endsection
<script>
     
     $(document).ready(function() {
        $('.selectpicker').select2();
     });
</script>    



