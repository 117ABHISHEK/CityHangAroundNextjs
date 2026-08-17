
  
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script> 
<script type="application/ld+json">
	{
  	"@context": "https://schema.org",
  	"@type": ["ItemList"],
  	"name": "Top 10 {{$category->category_name}} in {{$area->area_name}}, {{$city->city_name}}",
   	"numberOfItems": 5,
  	"itemListElement": [
    	@foreach ($products as $paid_items_key => $item)
        @php
        $item_categories = DB::table('page_category')
        ->where('page_id', $item->page_id)
        ->get();


        $item_count=count($item_categories);
        $itemcategoriesss = DB::table('pagecategories')
            ->where('id', $item_categories[$item_count-1]->category_id)
            ->get();
                        
        $itemcatslug = !is_null($itemcategoriesss) ? $itemcategoriesss[0]->category_slug:null; 
         
         $product_categories = DB::table('category_product')
         ->where('product_id', $item->id)
         ->get();
 
         
         $item_count=count($product_categories);
         $categoriesss = DB::table('categories')
             ->where('id', $product_categories[$item_count-1]->product_category_id)
             ->get();
                                 
         $catslug = !is_null($categoriesss) ? $categoriesss[0]->product_category_slug:null; 
         $catname = !is_null($categoriesss) ? $categoriesss[0]->product_category_name:null; 

         

         $items=DB::table('pages')->select('pages.*')
         ->where('pages.id',$item->page_id)->get();
      @endphp
            @if($paid_items_key <9)
                {
                "@type": "ListItem",
                        "position": {{$paid_items_key+1 }},
                    "name" : "{{$item->title}}",
            "url": "{{ route('single.product', ['city_slug'=> $city->city_slug,'area_slug'=> $area->area_slug,'category_slug'=>$itemcatslug ,'item_slug'=>$items[0]->item_slug,'product_category_slug'=>$catslug,'product_slug'=>$item->product_slug]) }}"

            },
            @elseif(($paid_items_key == 9 ))
                {
                "@type": "ListItem",
                "position": {{$paid_items_key+1 }},
                    "name" : "{{$item->item_title}}",
                    "url": "{{ route('single.product', ['city_slug'=> $city->city_slug,'area_slug'=> $area->area_slug,'category_slug'=>$itemcatslug ,'item_slug'=>$items[0]->item_slug,'product_category_slug'=>$catslug,'product_slug'=>$item->product_slug]) }}"
             }
                @else
                @break
            @endif
        @endforeach
        ]
      }

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
                                <li class="breadcrumb-item"><a href="{{ route('product.category.city', ['category_slug'=>$category->product_category_slug, 'city_slug'=>$city->city_slug]) }}">{{ $city->city_name }}</a></li>


                                @foreach($parent_categories as $key => $parent_category)
                                    <li class="breadcrumb-item"><a href="{{ route('product.category',['category_slug'=>$category->product_category_slug]) }}">{{ $parent_category->product_category_name }}</a></li>
                                @endforeach
                                <li class="breadcrumb-item"><a href="{{ route('product.category',['category_slug'=>$category->product_category_slug]) }}">{{ $category->product_category_name }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
    <div class="product-listing">
    <h1 class="font-weight-light text-primary">Best {{$category->product_category_name}} deals in {{ $area->area_name}} - {{ $city->city_name}}</h1>
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



