@php
$schema = [
    "@@context" => "https://schema.org",
    "@type" => "ItemList",
    "name" => "Top Businesses in {$city->city_name}",
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
                                    <a href="{{ route('event') }}">
                                        <i class="fas fa-bars"></i>
                                       Home
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('event') }}">All Categories</a></li>
                                <li class="breadcrumb-item"><a href="{{route('event.city',['city_slug'=>$city->city_slug])}}">{{ $city->city_name }}</a></li>
                                <li class="breadcrumb-item">{{ $area->area_name }}</a>
                            </ol>
                        </nav>
                    </div>
                </div>
<!-- Content Section Start -->
<div class="event-page-wrap">
    <div

        class="d-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h1 class="font-weight-light text-primary">Explore Exciting Events in {{$area->area_name}}, {{$city->city_name}} – What’s Happening Near You?</h1>
        
    </div>
    
    <div class="event-wrap row" >
        @include('frontend.events.custom_event_single') 
    </div>

</div>

<div class="container mt-4 p-4 bg-white shadow-sm rounded">
    <h2 class="text-primary">Upcoming Events in {{ $city->city_name }}</h2>
    <h2 class="mt-3 text-dark">Top Events You Can’t Miss in {{ $city->city_name }}</h2>
    <h2 class="mt-3 text-primary">Find the Best Events Near You in {{ $city->city_name }}</h2>
    <h2 class="mt-3 text-dark">Plan Your Week with These Events in {{ $city->city_name }}</h2>
    <h2 class="mt-3 text-primary">How to List Your Event in {{ $city->city_name }}</h2>
</div>







<!-- Content Section End -->