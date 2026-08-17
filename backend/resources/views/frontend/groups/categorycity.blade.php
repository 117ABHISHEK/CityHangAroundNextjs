<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>
<script type="application/ld+json">
            {
            @php

            $ratcount = rand(90,99);
        @endphp

        "@@context":"https://schema.org",
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
@include('frontend.left_group_category_city_filter')
<div class="row gx-3">
    <div class="col-lg-12">
        <div class="group-inner">
            
            <div class="page-suggest mt-4">
                <h1 class="h1">{{ $category->category_name }} in {{$city->city_name}}</h1>
                <div class="ps-wrap mt-3 justify-content-between">
                @include('frontend.groups.custom_single_group')
                </div>
              
            </div>
        </div>
    </div><!--  Group Content Inner Col End -->
    
   
</div>