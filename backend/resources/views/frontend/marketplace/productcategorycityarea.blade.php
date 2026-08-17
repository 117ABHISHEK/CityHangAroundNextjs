  
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script> 
@php
$listItems = [];
foreach ($products->take(10) as $paid_items_key => $item) {
    $item_categories = DB::table('page_category')
        ->where('page_id', $item->page_id)
        ->get();
    $item_count = count($item_categories);
    $itemcategoriesss = $item_count > 0 ? DB::table('pagecategories')
        ->where('id', $item_categories[$item_count-1]->category_id)
        ->get() : null;
    $itemcatslug = ($itemcategoriesss && count($itemcategoriesss) > 0) ? $itemcategoriesss[0]->category_slug : null; 

    $product_categories = DB::table('category_product')
        ->where('product_id', $item->id)
        ->get();
    $p_item_count = count($product_categories);
    $categoriesss = $p_item_count > 0 ? DB::table('categories')
        ->where('id', $product_categories[$p_item_count-1]->product_category_id)
        ->get() : null;
    $catslug = ($categoriesss && count($categoriesss) > 0) ? $categoriesss[0]->product_category_slug : null;

    $items = DB::table('pages')->select('pages.*')
        ->where('pages.id', $item->page_id)->get();
    $item_slug = count($items) > 0 ? $items[0]->item_slug : '';

    $listItems[] = [
        "@type" => "ListItem",
        "position" => $paid_items_key + 1,
        "name" => $item->title,
        "url" => route('single.product', [
            'city_slug' => $city->city_slug,
            'area_slug' => $area->area_slug,
            'category_slug' => $itemcatslug,
            'item_slug' => $item_slug,
            'product_category_slug' => $catslug,
            'product_slug' => $item->product_slug
        ])
    ];
}

$schema = [
    "@@context" => "https://schema.org",
    "@type" => "ItemList",
    "name" => "Top 10 {$category->category_name} in {$area->area_name}, {$city->city_name}",
    "numberOfItems" => count($listItems),
    "itemListElement" => $listItems
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
                                <li class="breadcrumb-item"><a href="{{ route('allproducts') }}">All Categories</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('product.category.city', ['category_slug'=>$category->product_category_slug, 'city_slug'=>$city->city_slug]) }}">{{ $city->city_name }}</a></li>


                                @if(isset($parent_categories) && count($parent_categories))
                                @foreach($parent_categories as $key => $parent_category)
                                    <li class="breadcrumb-item"><a href="{{ route('product.category',['category_slug'=>$parent_category->product_category_slug]) }}">{{ $parent_category->product_category_name }}</a></li>
                                @endforeach
                                @endif
                                <li class="breadcrumb-item"><a href="{{ route('product.category',['category_slug'=>$category->product_category_slug]) }}">{{ $category->product_category_name }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
    <div class="product-listing">
    <div class="deals-page-header">
        <span class="deals-subtitle">Local Area Offers</span>
        <h1 class="deals-title">Best {{$category->product_category_name}} Deals in {{ $area->area_name}} - {{ $city->city_name}}</h1>
        <p class="deals-desc">Explore the finest offers, discounts, and custom products in {{ $area->area_name }}, {{ $city->city_name }}. Save big on top local deals!</p>
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



