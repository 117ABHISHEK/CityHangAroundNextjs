
  
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>              
<div class="marketplace-wrap">
    
@php
$schema = [
    "@@context" => "https://schema.org",
    "@type" => "ItemList",
    "name" => "Top 10 {$category->category_name} in {$city->city_name}",
    "url" => request()->fullUrl(),
];
@endphp

<script type="application/ld+json">
{!! json_encode(
    $schema,
    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
) !!}
</script>

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
                                <li class="breadcrumb-item"><a href="{{ route('product.category.city', ['category_slug'=>$category->product_category_slug, 'city_slug'=>$city->city_slug]) }}">{{ $city->city_name }}</a></li>


                                @if(isset($parent_categories) && count($parent_categories))
                                @foreach($parent_categories as $key => $parent_category)
                                    <li class="breadcrumb-item"><a href="{{ route('product.category', ['category_slug'=>$parent_category->product_category_slug]) }}">{{ $parent_category->product_category_name }}</a></li>
                                @endforeach
                                @endif
                                <li class="breadcrumb-item"><a href="{{ route('product.category', ['category_slug'=>$category->product_category_slug]) }}">{{ $category->product_category_name }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
    <div class="product-listing">
    <div class="deals-page-header">
        <span class="deals-subtitle">Handpicked Deals</span>
        <h1 class="deals-title">Best {{$category->product_category_name}} Deals in {{ $city->city_name}}</h1>
        <p class="deals-desc">Discover the top offers, discounts, and high-quality items in {{ $city->city_name }}. Grab yours now!</p>
    </div>
        <div class="row g-3" id="{{ str_contains(url()->current(), '/productdata') ? 'single-item-countable' : '' }}">
            @include('frontend.marketplace.product-single')
        </div>
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



