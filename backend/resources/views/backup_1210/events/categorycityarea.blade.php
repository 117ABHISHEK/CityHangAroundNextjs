<script type="application/ld+json">
	{
  	"@context": "https://schema.org",
  	"@type": ["ItemList"],
  	"name": "Top 10 {{$category->category_name}} in {{$area->area_name}}, {{$city->city_name}}",
   	"numberOfItems": 5,
  	"itemListElement": [
    	@foreach ($events as $paid_items_key => $item)
        @php
         $item_categories = DB::table('event_category')
         ->where('event_id', $item->id)
         ->get();
 
         
         $item_count=count($item_categories);
         $categoriesss = DB::table('eventcategories')
             ->where('id', $item_categories[$item_count-1]->category_id)
             ->get();
                                 
         $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 
      @endphp
            @if($paid_items_key <9)
                {
                "@type": "ListItem",
                        "position": {{$paid_items_key+1 }},
                    "name" : "{{$item->title}}",
            "url": "{{ route('single.event', ['city_slug'=> $item->city_slug, 'area_slug' => $item->area_slug, 'category_slug'=>$catslug , $item->event_slug]) }}"

            },
            @elseif(($paid_items_key == 9 ))
                {
                "@type": "ListItem",
                "position": {{$paid_items_key+1 }},
                    "name" : "{{$item->item_title}}",
                    "url": "{{ route('single.event', ['city_slug'=> $item->city_slug, 'area_slug' => $item->area_slug, 'category_slug'=>$catslug , $item->event_slug]) }}"
             }
                @else
                @break
            @endif
        @endforeach
        ]
      }

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
                                <li class="breadcrumb-item"><a href="{{ route('event') }}">All Categories</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('event.category.city', ['category_slug'=>$category->category_slug, 'city_slug'=>$city->city_slug]) }}">{{ $city->city_name }}</a></li>


                                @foreach($parent_categories as $key => $parent_category)
                                    <li class="breadcrumb-item"><a href="{{ route('event.category.city', ['category_slug'=>$parent_category->category_slug, 'city_slug'=>$city->city_slug]) }}">{{ $parent_category->category_name }}</a></li>
                                @endforeach
                                <li class="breadcrumb-item"><a href="{{ route('event.category.city', ['category_slug'=>$category->category_slug, 'city_slug'=>$city->city_slug]) }}">{{ $category->category_name }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
<!-- Content Section Start -->
<div class="event-page-wrap">
    <div 
        class="d-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h1 class="font-weight-light text-primary">Explore the Top {{ $category->category_name }} Events in {{$city->city_name}} </h1>
        
    </div>
    
    <div class="event-wrap row" >
        @include('frontend.events.custom_event_single') 
    </div>

</div>

<div class="container mt-4 p-4 bg-white shadow-sm rounded">
    <h2>Upcoming {{ $category->category_name }} Events in {{ $city->city_name }}</h2>
    <h2>Top {{ $category->category_name }} Events You Can’t Miss in {{ $city->city_name }}</h2>
    <h2>Find the Best {{ $category->category_name }} Events Near You in {{ $city->city_name }}</h2>
    <h2>Plan Your Week with These {{ $category->category_name }} Events in {{ $city->city_name }}</h2>
    <h2>How to List Your {{ $category->category_name }} Event in {{ $city->city_name }}</h2>
</div>




<!-- Content Section End -->