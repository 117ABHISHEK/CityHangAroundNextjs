
  
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>              
<div class="marketplace-wrap">
    
<script type="application/ld+json">
            {
            @php

            $ratcount = rand(90,99);
        @endphp

        "@context":"https://schema.org",
        "@type":"Review",
        "itemReviewed":{
        "@type":"LocalBusiness",
  	"name": "Top 10 {{$category->category_name}} in {{$city->city_name}}",
                 "url":"{{$_SERVER['REQUEST_URI']}}",
                 "address":{"@type":"PostalAddress","addressLocality":"{{$city->city_name}}"}},
                 "author":"Users",
                 "ReviewRating":{
                    "@type":"AggregateRating",
                    "ratingValue":"9.3",
                    "ratingCount":"{{$ratcount}}",
                    "bestRating":"10"
            }}
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


                                @foreach($parent_categories as $key => $parent_category)
                                    <li class="breadcrumb-item"><a href="{{ route('product.category', ['category_slug'=>$parent_category->product_category_slug]) }}">{{ $parent_category->product_category_name }}</a></li>
                                @endforeach
                                <li class="breadcrumb-item"><a href="{{ route('product.category', ['category_slug'=>$category->product_category_slug]) }}">{{ $category->product_category_name }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
    <div class="product-listing">
    <h1 class="font-weight-light text-primary">Best {{$category->product_category_name}} deals in {{ $city->city_name}}</h1>
        <div class="row g-3" id="@if(str_contains(url()->current(), '/productdata')) single-item-countable @endif">
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



