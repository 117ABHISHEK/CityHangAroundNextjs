
<!-- <style>
     .filter-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        margin-bottom: 25px;
    }
    
    .filter-card .card-header {
        background: linear-gradient(135deg, #0d6efd 0%, #3b82f6 100%);
        border-radius: 12px 12px 0 0;
        border: none;
        padding: 15px 20px;
    }
    
    .filter-card .card-header h5 {
        margin: 0;
        font-weight: 600;
        font-size: 16px;
    }
    
    .filter-card .card-body {
        padding: 20px;
    }
    
    .form-label {
        font-weight: 600;
        color: #2c3e50;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    
    .form-control, .selectpicker {
        border: 2px solid #e9ecef;
        border-radius: 6px;
        height: 40px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .selectpicker:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #f13b03ff);
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #f13b03ff 0%, #1ea085 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    }
    
    .btn-outline-secondary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        color: white;
        transform: translateY(-1px);
    }
    
    .select2-container {
        width: 100% !important;
    }
    
    .select2-container--default .select2-selection--single {
        border: 2px solid #e9ecef;
        border-radius: 6px;
        height: 40px;
        transition: all 0.3s ease;
    }
    
    .select2-container--default .select2-selection--single:hover {
        border-color: #667eea;
    }
    
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        padding-left: 12px;
        color: #495057;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
    }
    
    @media (max-width: 768px) {
        .filter-card .card-body .row > div {
            margin-bottom: 15px;
        }
    }
</style>   -->
@php
$schema = [
    "@@context" => "https://schema.org",
    "@type" => "ItemList",
    "name" => "Top Deals in {$city->city_name}",
    "url" => request()->fullUrl(),
];
@endphp

<script type="application/ld+json">
{!! json_encode(
    $schema,
    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
) !!}
</script>
       
<div class="marketplace-wrap">
    

<div class="row">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-white pl-0 pr-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('pages') }}">
                                        <i class="fas fa-bars"></i>
                                       Home
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('allproducts') }}">Deals</a></li>
                                <li class="breadcrumb-item">{{ $city->city_name }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>

<!-- Filter Section -->
<!-- <div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm filter-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Products</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('product.city', ['city_slug' => $city->city_slug]) }}" id="filterForm">
                    <div class="row"> -->
                        <!-- Area Filter -->
                        <!-- <div class="col-md-3 mb-3">
                            <label for="area_filter" class="form-label">Area</label>
                            <select class="form-control selectpicker" id="area_filter" name="area_filter">
                                <option value="0">All Areas</option>
                                @foreach($all_areas ?? [] as $area)
                                    <option value="{{ $area->id }}" {{ request('area_filter') == $area->id ? 'selected' : '' }}>
                                        {{ $area->area_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div> -->

                        <!-- Category Filter -->
                        <!-- <div class="col-md-3 mb-3">
                            <label for="category_filter" class="form-label">Category</label>
                            <select class="form-control selectpicker" id="category_filter" name="category_filter">
                                <option value="0">All Categories</option>
                                @foreach($all_categories ?? [] as $category)
                                    @if($category->category_parent_id == 0 || $category->category_parent_id == null)
                                        <option value="{{ $category->id }}" {{ request('category_filter') == $category->id ? 'selected' : '' }}>
                                         
                                    @endif
                                @endforeach
                            </select>
                        </div> -->

                        <!-- Sub-Category Filter -->
                        <!-- <div class="col-md-3 mb-3">
                            <label for="subcategory_filter" class="form-label">Sub-Category</label>
                            <select class="form-control selectpicker" id="subcategory_filter" name="subcategory_filter">
                                <option value="0">All Sub-Categories</option>
                            </select>
                        </div> -->

                        <!-- Search Input -->
                        <!-- <div class="col-md-2 mb-3">
                            <label for="search_filter" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search_filter" name="search_filter" 
                                   placeholder="Search products..." value="{{ request('search_filter') }}">
                        </div> -->

                        <!-- Search Button -->
                        <!-- <div class="col-md-1 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div> -->

                    <!-- Clear Filters Button -->
                    <!-- <div class="row">
                        <div class="col-12">
                            <a href="{{ route('product.city', ['city_slug' => $city->city_slug]) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times"></i> Clear Filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> -->
</div>

    <div class="product-listing"> 
    <div class="deals-page-header">
        <span class="deals-subtitle">City Wide Savings</span>
        <h1 class="deals-title">Best Deals & Discounts in {{ $city->city_name}}</h1>
        <p class="deals-desc">Find the hottest deals, daily discounts, and limited-time savings across {{ $city->city_name }}. Save more with our top handpicked offers!</p>
    </div>
        <div class="row g-3" id="{{ str_contains(url()->current(), '/productdata') ? 'single-item-countable' : '' }}">
            @include('frontend.marketplace.product-single')
        </div>
    </div>


<div class="container mt-4 p-4 bg-white shadow-sm rounded hidden" >
    <h1 class="text-center text-dark">Get the Best Deals, Discounts & Exclusive Offers in {{ $city->city_name }}</h1>

    <h2 class="mt-4 text-primary">✅ Top Deals & Hot Promotions in {{ $city->city_name }} You Can’t Miss</h2>
    <h2 class="mt-3 text-dark">✅ Daily Deals and Discounts – Save More in {{ $city->city_name }}</h2>
    <h2 class="mt-3 text-primary">✅ Flash Sale Deals & Limited-Time Offers in {{ $city->city_name }}</h2>
    <h2 class="mt-3 text-dark">✅ Best Savings Online – Seasonal Discount Deals in {{ $city->city_name }}</h2>

    <p class="mt-4 text-muted">
        Looking for the best deals and discounts in <strong>{{ $city->city_name }}</strong>? You’ve come to the right place! 
        <strong>City Hangaround Deals</strong> brings you the latest savings on restaurants, shopping, salons, spas, and entertainment. 
        Whether you're a foodie, a shopaholic, or just love a good deal, we’ve got something for everyone in {{ $city->city_name }}!
    </p>

    <h3 class="mt-4 text-danger">🔥 Trending Deals in {{ $city->city_name }} Today</h3>
    <ul class="list-unstyled mt-3">
        <li>✅ <strong>[Popular Restaurant]</strong> – Ex: Enjoy Flat 20% Off on All Food Orders 🍽️</li>
        <li>✅ <strong>[Fashion Store]</strong> – Ex: Get Up to 50% Off on Latest Trends 🛍️</li>
        <li>✅ <strong>[Luxury Spa]</strong> – Relax with a Buy 1 Get 1 Free Massage 💆‍♂️</li>
        <li>✅ <strong>[Cinema Hall]</strong> – Book Movie Tickets at 30% Off 🎬</li>
    </ul>

    <p class="mt-3">
        🔎 Looking for more savings? Browse our city-based deals and find the best local discounts in {{ $city->city_name }}!
    </p>
    <p class="mt-3">
        📍 <strong>Location-Based Offers:</strong> Find deals near you and save money at your favorite restaurants, malls, and entertainment spots in {{ $city->city_name }}.
    </p>
    <p class="mt-3">
        📢 <strong>Are You a Business Owner in {{ $city->city_name }}?</strong> Want to attract more customers? List your deals for <strong>FREE</strong> on City Hangaround and boost your business visibility!
    </p>

    <h3 class="mt-4 text-dark font-weight-bold">FAQs: Deals in {{ $city->city_name }}</h3>
    
    <div class="mt-3">
        <h4 class="text-dark">1. What types of deals are available in {{ $city->city_name }}?</h4>
        <p>We offer restaurant deals, shopping discounts, spa offers, travel discounts, and entertainment coupons from top businesses in {{ $city->city_name }}. New deals are added daily!</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">2. How do I claim a deal in {{ $city->city_name }}?</h4>
        <p>Click on the deal, check the details, and follow the instructions to redeem it online or at the store. Some deals may require a promo code.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">3. Do I need to sign up to access deals in {{ $city->city_name }}?</h4>
        <p>No, browsing deals is free. However, creating an account allows you to save your favorite offers and receive alerts for new deals in {{ $city->city_name }}.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">4. How often are {{ $city->city_name }} deals updated?</h4>
        <p>We update our best {{ $city->city_name }} deals daily, so you always get fresh discounts and exclusive offers!</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">5. Can businesses in {{ $city->city_name }} list their deals on City Hangaround?</h4>
        <p>Yes! If you own a business in {{ $city->city_name }}, you can list your deals for free and reach more customers. Visit our <a href="{{ route('pages.create.product') }}" class="text-primary">Business Listing</a> page to get started.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">6. What if a deal in {{ $city->city_name }} is not working?</h4>
        <p>If a deal isn’t working, check the terms & conditions or <a href="{{ route('contact.view') }}" class="text-primary">contact our support team</a> for assistance.</p>
    </div>
</div>
