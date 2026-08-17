
  
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>   

<div class="container mt-4 p-4 bg-white shadow-sm rounded">
    <h1 class="text-center text-dark">Exclusive Deals & Discounts – Discover the Best Offers Near You</h1>

</div>

                    
<div class="marketplace-wrap">
    <div class="d-md-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h3 class="h5 pt-3"><span><i class="fa-solid fa-calendar-days"></i></span> {{ get_phrase('Deals') }}</h3>
        <div class="pagebtnListing">
                <!-- <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.marketplace.create_product'])}}', '{{get_phrase('Create Product')}}');" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#createProduct" class="btn btn-primary"> <i class="fa fa-plus-circle"></i></a> -->
                   @if(auth()->user())
                    <a  class="btn red-btn btn-primary mt-1"  class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#enquiryModal" > <i class="fa fa-plus-circle"></i>{{get_phrase('Enquiry')}}</a>
                    <a href="{{route('pages.create.product')}}" onclick="" class="btn red-btn btn-primary mt-1"  class="btn btn-primary"> <i class="fa fa-plus-circle"></i>{{get_phrase('Submit Deal')}}</a>
            <a href="{{ route('userproduct') }}" class="btn mx-1 mt-1">{{ get_phrase('My Deals') }}</a>
            <a href="{{ route('product.saved') }}" class="btn mt-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{get_phrase('Saved Product')}}">{{ get_phrase('Saved') }}</a>
            @endif
        </div>
    </div>
    <div class="product-form border mb-3 bg-white p-3 rounded">
        
        
        <div class="product-filter mt-3">
            
            <form method="GET" action="{{ route('filter.product') }}" class=" row">
                <div class="form-group">
                    <input type="search" class="submit_on_enter" name="search" value="@if(isset($_GET['search']) && $_GET['search']!="" ){{$_GET['search']}}@endif" class="bg-secondary rounded" placeholder="Type To Search">
                </div>
            </form>
        </div>
    </div>
    <!--  Product Form End 
    
    <!-- Product Listing Start -->
    <div class="deals-page-header">
        <span class="deals-subtitle">Discover Savings</span>
        <h1 class="deals-title">Exclusive Deals & Discounts</h1>
        <p class="deals-desc">Find the hottest deals, daily discounts, and limited-time savings near you. Browse and save today!</p>
    </div>
    <div class="product-listing">  
        <div class="row g-3" id="{{ str_contains(url()->current(), '/productdata') ? 'single-item-countable' : '' }}">
            @include('frontend.marketplace.product-single')
        </div>
    </div>
     <!-- pagination -->
     <div class="pagination-area" style="text-align:center;">
                            <div aria-label="Page navigation example">
                                <ul class="pagination">
                                {{ $products->links() }}
                                </ul>
                            </div>
                        </div>
                        <!-- pagination end -->
</div>
@include('frontend.footer')


<div class="container mt-4 p-4 bg-white shadow-sm rounded" hidden>
   
    <h2 class="mt-4 text-primary">Top Local Deals You Can’t Miss</h2>
    <h2 class="mt-3 text-dark">Latest Discounts on Restaurants, Shopping & More</h2>
    <h2 class="mt-3 text-primary">Explore Exclusive Offers Near You</h2>
    <h2 class="mt-3 text-dark">How to Avail the Best Deals in Your City?</h2>

    <p class="mt-4 text-muted">
        Welcome to <strong>City Hangaround Deals</strong>, your trusted platform for finding the best local deals and saving money on restaurants, shopping, salons, spas, and entertainment. Whether you’re looking for exclusive restaurant discounts, shopping coupons, or wellness offers, we’ve got the best deals near you!
    </p>

    <h3 class="mt-4 text-danger">🔥 Trending Deals Today</h3>
    <ul class="list-unstyled mt-3">
        <li>✅ <strong>[Restaurant Name]</strong> – Get 20% off on all dine-in orders 🍽️</li>
        <li>✅ <strong>[Fashion Store]</strong> – Flat 50% off on the latest fashion trends 👗</li>
        <li>✅ <strong>[Spa & Wellness]</strong> – Relax with a Buy 1 Get 1 Free massage 💆‍♂️</li>
        <li>✅ <strong>[Movie Tickets]</strong> – Book now and save up to 30% 🎬</li>
    </ul>

    <p class="mt-3">
        🔎 Looking for more savings? Browse our city-based deals and find the perfect offer near you!
    </p>
    <p class="mt-3">
        📍 <strong>Location-Based Savings:</strong> We provide customized deals based on your city so you can find the best offers in your area.
    </p>
    <p class="mt-3">
        📢 <strong>Business Owners:</strong> Want to attract more customers? List your deals for <strong>FREE</strong> on City Hangaround and boost your sales!
    </p>

    <h3 class="mt-4 text-dark font-weight-bold">FAQs: City Hangaround Deals</h3>
    
    <div class="mt-3">
        <h4 class="text-dark">1. What types of deals can I find on City Hangaround?</h4>
        <p>You can find discounts and offers on restaurants, shopping, travel, entertainment, salons, spas, and other local businesses. We update deals regularly to bring you the best savings in your city.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">2. How do I avail a deal?</h4>
        <p>Click on the deal you're interested in, check the terms and conditions, and follow the instructions provided. Some deals may require a promo code, while others can be redeemed directly at the store or online.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">3. Do I need to sign up to access deals?</h4>
        <p>No, browsing deals is free. However, creating an account allows you to get offers and notifications about new deals in your area.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">4. Are the deals available in all cities?</h4>
        <p>Yes! We feature deals in multiple cities across India. You can select your location to view deals near you.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">5. How often are deals updated?</h4>
        <p>We update deals daily to ensure you get the latest discounts and exclusive offers.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">6. Can businesses list their deals on City Hangaround?</h4>
        <p>Yes! If you own a business, you can list your deals for free and attract more customers. Visit our <a href="{{ route('pages.create.product') }}" class="text-primary">Business Listing</a> section to get started.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">7. What should I do if a deal is not working?</h4>
        <p>If you face any issues, please <a href="{{ route('contact.view') }}" class="text-primary">contact us</a> or check the deal’s terms and conditions for validity and restrictions.</p>
    </div>
</div>


@section('specific_code_niceselect')
    $('select').niceSelect();
@endsection
<script>
     
     $(document).ready(function() {
       
     });
</script>    



