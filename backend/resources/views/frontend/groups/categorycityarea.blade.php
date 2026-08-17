<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>
<script type="application/ld+json">
	{
  	"@@context": "https://schema.org",
  	"@type": ["ItemList"],
  	"name": "Top 10 {{$category->category_name}} in {{$area->area_name}}, {{$city->city_name}}",
   	"numberOfItems": 5,
  	"itemListElement": [
    	@foreach ($groups as $paid_items_key => $item)
        @php
         $item_categories = DB::table('group_category')
         ->where('group_id', $item->id)
         ->get();
 
         
         $item_count=count($item_categories);
         $categoriesss = DB::table('pagecategories')
             ->where('id', $item_categories[$item_count-1]->category_id)
             ->get();
                                 
         $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 
      @endphp
            @if($paid_items_key <9)
                {
                "@type": "ListItem",
                        "position": {{$paid_items_key+1 }},
                    "name" : "{{$item->title}}",
            "url": "{{ route('single.group', ['city_slug'=> $city->city_slug, 'area_slug' => $area->area_slug, 'category_slug'=>$catslug , $item->group_slug]) }}"

            },
            @elseif(($paid_items_key == 9 ))
                {
                "@type": "ListItem",
                "position": {{$paid_items_key+1 }},
                    "name" : "{{$item->item_title}}",
                    "url": "{{ route('single.group', ['city_slug'=> $city->city_slug, 'area_slug' => $area->area_slug, 'category_slug'=>$catslug , $item->group_slug]) }}"
             }
                @else
                @break
            @endif
        @endforeach
        ]
      }

</script>
@include('frontend.left_group_category_city_area_filter')
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
                                <li class="breadcrumb-item"><a href="{{ route('groups') }}">All Categories</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('group.category.city', ['category_slug'=>$category->category_slug, 'city_slug'=>$city->city_slug]) }}">{{ $city->city_name }}</a></li>


                                @foreach($parent_categories as $key => $parent_category)
                                    <li class="breadcrumb-item"><a href="{{ route('group.category.city', ['category_slug'=>$parent_category->category_slug, 'city_slug'=>$city->city_slug]) }}">{{ $parent_category->category_name }}</a></li>
                                @endforeach
                                <li class="breadcrumb-item"><a href="{{ route('group.category.city', ['category_slug'=>$category->category_slug, 'city_slug'=>$city->city_slug]) }}">{{ $category->category_name }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
<div class="row gx-3">
    <div class="col-lg-12">
        <div class="group-inner">
            
            <div class="page-suggest mt-4">
                <h1 class="h1">Top {{ $category->category_name }} groups listings in {{$city->city_name}}</h1>
                <div class="ps-wrap mt-3 justify-content-between">
                @include('frontend.groups.custom_single_group')
                </div>
              
            </div>
        </div>
    </div><!--  Group Content Inner Col End -->
    
   
</div>