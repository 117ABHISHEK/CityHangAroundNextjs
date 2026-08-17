<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>
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
                                <li class="breadcrumb-item"><a href="{{ route('groups') }}">All Categories</a></li>
                                <li class="breadcrumb-item"><a href="{{route('group.city',['city_slug'=>$city->city_slug])}}">{{ $city->city_name }}</a></li>
                                <li class="breadcrumb-item">{{ $area->area_name }}</a>
                            </ol>
                        </nav>
                    </div>
                </div>
<div class="row gx-3">
    <div class="col-lg-12">
        <div class="group-inner">
            
            <div class="page-suggest mt-4">
                <h1 class="h1">Best Groups Near {{$area->area_name}}, {{$city->city_name}}</h1>
                <div class="ps-wrap mt-3 justify-content-between">
                @include('frontend.groups.custom_single_group')
                </div>
              
            </div>
        </div>
    </div><!--  Group Content Inner Col End -->
    
   
</div>