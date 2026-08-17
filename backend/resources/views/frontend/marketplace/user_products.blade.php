<div class="marketplace-wrap">
    <nav class="market-nav border bg-white mb-3 rounded">
        <ul class="nav align-items-center">
            <li class="nav-item"><a href="{{ route('allproducts') }}" class="nav-link">{{ get_phrase('Marketplace') }}</a></li>
            <li class="nav-item active"><a href="{{ route('allproducts') }}" class="nav-link">{{ get_phrase('My Products') }}</a></li>
        </ul>
    </nav>

    <div class="d-flex pagetab-head border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h3 class="h5 pt-3"><span><i class="fa-solid fa-calendar-days"></i></span> {{ get_phrase('Marketplace') }}</h3>
        <div>
            <a href="{{ route('pages.create.product') }}" class="btn btn-primary">
                <i class="fa fa-plus-circle"></i> {{ get_phrase('Create Product') }}
            </a>
        </div>
    </div>

    <div class="product-listing">
        <div class="row g-3">
            @foreach ($products as $product)
                @php
    $citySlug = $product->page->area->city->city_slug ?? 'city';
    $areaSlug = $product->page->area->area_slug ?? 'area';

    $pageCategories = $product->page->categories ?? collect();
    $lastPageCategory = $pageCategories->last();
    $categorySlug = $lastPageCategory->category_slug ?? 'category';

    $itemSlug = $product->page->item_slug ?? 'item';

    $productCategories = $product->productCategories ?? collect();
    $lastProductCategory = $productCategories->last();
    $productCategorySlug = $lastProductCategory->product_category_slug ?? 'subcategory';

    $productSlug = $product->product_slug ?? 'product';
     $productSellingPrice = $product->product_selling_price ?? 0;
    $currencySymbol = $product->getCurrency->symbol ?? '₹';
@endphp

                <div class="col-6 col-md-4 col-lg-6 col-xl-4" id="product-{{ $product->id }}">
                    <div class="card product p-3">
                        <div class="product-figure position-relative">
                            <a href="{{ route('single.product', [
    'city_slug' => $citySlug,
    'area_slug' => $areaSlug,
    'category_slug' => $categorySlug,
    'item_slug' => $itemSlug,
    'product_category_slug' => $productCategorySlug,
    'product_slug' => $productSlug,
]) }}">
                                <img src="{{ get_marketplace_banner_image($product, 'thumbnail') }}" alt="" class="img-fluid">
                            </a>
                        </div>
                        <h3 class="h6">
                            <a href="{{ route('single.product', [
    'city_slug' => $citySlug,
    'area_slug' => $areaSlug,
    'category_slug' => $categorySlug,
    'item_slug' => $itemSlug,
    'product_category_slug' => $productCategorySlug,
    'product_slug' => $productSlug,
]) }}">
                                {{ ellipsis($product->title, 30) }}
                            </a>
                        </h3>
                        <span class="location">{{ $product->location }}</span>
                        <div class="prodoct-footer">
                            <a href="{{ route('single.product', [
    'city_slug' => $citySlug,
    'area_slug' => $areaSlug,
    'category_slug' => $categorySlug,
    'item_slug' => $itemSlug,
    'product_category_slug' => $productCategorySlug,
    'product_slug' => $productSlug,
]) }}" class="btn btn-primary">
                                {{ $currencySymbol }} {{ $productSellingPrice }}
                            </a>

                            <a href="{{ route('product.edit', ['product_id' => $product->id]) }}" class="">
                                <i class="fa fa-edit"></i>
                            </a>

                            <a href="javascript:void(0)" onclick="confirmAction('{{ route('product.delete', ['product_id' => $product->id]) }}', true)" class="">
                                <i class="fa fa-trash-can me-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
