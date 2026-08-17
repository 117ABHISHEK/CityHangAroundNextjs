
  
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>

<style>
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
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
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
</style> 
<script type="application/ld+json">
            {
                 "@context":"https://schema.org",
                 "@type":"Review","itemReviewed":{
                 "@type":"LocalBusiness",
                 "name":"Top 5 Deals in {{$area->area_name}},{{$city->city_name}}",
                 "url":"{{$_SERVER['REQUEST_URI']}}",
                 "address":{"@type":"PostalAddress","addressLocality":"{{$city->city_name}}"}},
                 "author":"Users",
                 "ReviewRating":{
                    "@type":"AggregateRating",
                    "ratingValue":"4.1",
                    "ratingCount":"14198",
                    "bestRating":"5"
            }}
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
                                <li class="breadcrumb-item"><a href="{{ route('allproducts') }}">All Categories</a></li>
                                <li class="breadcrumb-item">
    <a href="{{ route('product.city', ['city_slug' => $city->city_slug]) }}">
        {{ $city->city_name }}
    </a>
</li>
                                <li class="breadcrumb-item">{{ $area->area_name }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm filter-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Products</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('product.city.area', ['city_slug' => $city->city_slug, 'area_slug' => $area->area_slug]) }}" id="filterForm">
                    <div class="row">
                        <!-- Category Filter -->
                        <div class="col-md-4 mb-3">
                            <label for="category_filter" class="form-label">Category</label>
                            <select class="form-control selectpicker" id="category_filter" name="category_filter">
                                <option value="0">All Categories</option>
                                @foreach($all_categories ?? [] as $category)
                                    @if($category->category_parent_id == 0 || $category->category_parent_id == null)
                                        <option value="{{ $category->id }}" {{ request('category_filter') == $category->id ? 'selected' : '' }}>
                                            {{ $category->product_category_name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Sub-Category Filter -->
                        <div class="col-md-4 mb-3">
                            <label for="subcategory_filter" class="form-label">Sub-Category</label>
                            <select class="form-control selectpicker" id="subcategory_filter" name="subcategory_filter">
                                <option value="0">All Sub-Categories</option>
                            </select>
                        </div>

                        <!-- Search Input -->
                        <div class="col-md-3 mb-3">
                            <label for="search_filter" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search_filter" name="search_filter" 
                                   placeholder="Search products..." value="{{ request('search_filter') }}">
                        </div>

                        <!-- Search Button -->
                        <div class="col-md-1 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Clear Filters Button -->
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('product.city.area', ['city_slug' => $city->city_slug, 'area_slug' => $area->area_slug]) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times"></i> Clear Filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <div class="product-listing"> 
    

    <h1 class="font-weight-light text-primary">Best Deals & Discounts in {{ $area->area_name}} , {{ $city->city_name}} – Find the Top Offers Near You</h1>
        <div class="row g-3" id="@if(str_contains(url()->current(), '/productdata')) single-item-countable @endif">
            @include('frontend.marketplace.product-single')
        </div>
    </div>
</div>


<div class="container mt-4 p-4 bg-white shadow-sm rounded" hidden>
   
    <h2 class="mt-4 text-primary">Top Deals & Discounts in {{ $area->area_name }}, {{ $city->city_name }} You Can’t Miss</h2>
    <h2 class="mt-3 text-dark">Best Offers on Restaurants, Shopping & More in {{ $area->area_name }}</h2>
    <h2 class="mt-3 text-primary">City Hangaround – Your #1 Destination for Discounts in {{ $area->area_name }}</h2>

    <p class="mt-4 text-muted">
        Looking for the best deals in <strong>{{ $area->area_name }}, {{ $city->city_name }}</strong>? You’ve come to the right place! City Hangaround Deals brings you the latest offers on restaurants, shopping, beauty salons, spas, and entertainment in <strong>{{ $area->area_name }}</strong>. Whether you want to dine at a top restaurant, shop for the latest trends, or relax at a spa, we’ve got the best discounts near you.
    </p>

    <h3 class="mt-4 text-danger">🔥 Trending Deals in {{ $area->area_name }}, {{ $city->city_name }} Today</h3>
    <ul class="list-unstyled mt-3">
        <li>✅ <strong>[Popular Restaurant]</strong> – Enjoy Flat 25% Off on All Food Orders 🍽️</li>
        <li>✅ <strong>[Fashion Store]</strong> – Get Up to 60% Off on the Latest Styles 🛍️</li>
        <li>✅ <strong>[Luxury Spa]</strong> – Relax with a Buy 1 Get 1 Free Massage 💆‍♂️</li>
        <li>✅ <strong>[Movie Theater]</strong> – Book Movie Tickets at 30% Off 🎬</li>
    </ul>

    <p class="mt-3">
        🔎 Looking for more savings? Browse the best local discounts in {{ $area->area_name }}, {{ $city->city_name }} and start saving today!
    </p>

    <p class="mt-3">
        📍 <strong>Nearby Offers:</strong> Find deals near your location and save money at your favorite restaurants, malls, and entertainment spots in <strong>{{ $area->area_name }}</strong>.
    </p>

    <p class="mt-3">
        📢 <strong>Are You a Business Owner in {{ $area->area_name }}?</strong> Want to attract more customers? List your deals for <strong>FREE</strong> on City Hangaround and increase your business visibility!
    </p>

    <h3 class="mt-4 text-dark font-weight-bold">FAQs: Deals in {{ $area->area_name }}, {{ $city->city_name }}</h3>

    <div class="mt-3">
        <h4 class="text-dark">1. What types of deals are available in {{ $area->area_name }}, {{ $city->city_name }}?</h4>
        <p>We offer restaurant deals, shopping discounts, salon & spa offers, entertainment coupons, and more from top businesses in {{ $area->area_name }}, {{ $city->city_name }}.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">2. How do I claim a deal in {{ $area->area_name }}?</h4>
        <p>Click on the deal, check the details, and follow the instructions to redeem it online or at the store. Some deals may require a promo code.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">3. Do I need to sign up to access deals in {{ $area->area_name }}?</h4>
        <p>No, browsing deals is free. However, creating an account lets you save favorite offers and receive alerts for new deals in {{ $area->area_name }}.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">4. How often are deals updated in {{ $area->area_name }}?</h4>
        <p>We update our best {{ $area->area_name }} deals daily, so you always get fresh discounts and exclusive offers!</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">5. Can businesses in {{ $area->area_name }} list their deals on City Hangaround?</h4>
        <p>Yes! If you own a business in {{ $area->area_name }}, {{ $city->city_name }}, you can list your deals for free and reach more customers. Visit our <a href="{{ route('pages.create.product') }}" class="text-primary">Business Listing</a> page to get started.</p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">6. What if a deal in {{ $area->area_name }} is not working?</h4>
        <p>If a deal isn’t working, check the terms & conditions or <a href="{{ route('contact.view') }}" class="text-primary">contact us</a> for assistance.</p>
    </div>
</div>



@section('specific_code_niceselect')
    $('select').niceSelect();
@endsection
<script>
     
     $(document).ready(function() {
        $('.selectpicker').select2();
        
        // Category change event to load subcategories
        $('#category_filter').on('change', function() {
            var categoryId = $(this).val();
            var subcategorySelect = $('#subcategory_filter');
            
            // Clear subcategory options
            subcategorySelect.html('<option value="0">All Sub-Categories</option>');
            
            if (categoryId && categoryId != '0') {
                // Load subcategories via AJAX
                $.ajax({
                    url: '{{ route("json.get.subcategories.by.category") }}',
                    type: 'GET',
                    data: { category_id: categoryId },
                    success: function(data) {
                        $.each(data, function(index, subcategory) {
                            subcategorySelect.append('<option value="' + subcategory.id + '">' + subcategory.product_category_name + '</option>');
                        });
                        subcategorySelect.trigger('change');
                    },
                    error: function() {
                        console.log('Error loading subcategories');
                    }
                });
            }
        });
        
        // Auto-submit form when filters change
        $('#category_filter, #subcategory_filter').on('change', function() {
            $('#filterForm').submit();
        });
        
        // Initialize subcategories if category is pre-selected
        var selectedCategory = $('#category_filter').val();
        if (selectedCategory && selectedCategory != '0') {
            $('#category_filter').trigger('change');
        }
     });
</script>



