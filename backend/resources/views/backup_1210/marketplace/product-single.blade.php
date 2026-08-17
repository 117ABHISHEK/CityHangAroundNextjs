<style>
  @media (max-width: 375px) {
    .col-6.custom-xs-full {
      flex: 0 0 100%;
      max-width: 100%;
    }
  }
</style>

@foreach ($products as $key => $product)
    @php
        $page = $product->page;
        $city = $page->city ?? null;
        $area = $page->area ?? null;
        $itemCategory = $page->categories->last(); // Assuming last is primary
        $productCategory = $product->productCategories->last();

        $selling_price = $product->product_selling_price ?? 0;
        $original_price = $product->product_price ?? null;
        $rating = round($product->averageRating() ?? 0);

        $productRoute = route('single.product', [
            'city_slug' => $city->city_slug ?? 'city',
            'area_slug' => $area->area_slug ?? 'area',
            'category_slug' => $itemCategory->category_slug ?? 'category',
            'item_slug' => $page->item_slug ?? 'item',
            'product_category_slug' => $productCategory->product_category_slug ?? 'subcategory',
            'product_slug' => $product->product_slug ?? 'product'
        ]);
    @endphp

    <div class="col-6 col-sm-6 col-md-4 col-lg-6 col-xl-4 custom-xs-full @if(str_contains(url()->current(), '/products')) single-item-countable @endif">
        <div class="card product p-3">
            <a href="{{ $productRoute }}" class="thumbnail-196-196"
               style="background-image: url('{{ get_product_image($product->image, 'thumbnail') }}');"></a>

            <h3 class="h6">
                <a href="{{ $productRoute }}">{{ $product->title }}</a>
            </h3>

            <span class="location"><a href="{{ route('product.city.area', ['city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug]) }}">{{ $city->city_name ?? '' }}, {{ $area->area_name ?? '' }}</a></li></span>

            <div class="price">
                @if($original_price && $original_price > $selling_price)
                    <span class="text-muted"><del>{{ $product->getCurrency->symbol ?? '₹' }}{{ $original_price }}</del></span>
                @endif
                <strong>{{ $product->getCurrency->symbol ?? '₹' }} {{ $selling_price }}</strong>
            </div>

            <div class="rating mt-1">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $rating)
                        ⭐
                    @else
                        ☆
                    @endif
                @endfor
            </div>

            <a href="{{ $productRoute }}" class="btn btn-primary d-block mt-3">
                View
            </a>
        </div>
    </div>

    @if (!empty($search) && $key == 2)
        @break
    @endif
@endforeach
