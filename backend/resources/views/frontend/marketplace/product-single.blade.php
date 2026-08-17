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
        $rating = round($product->avg_rating ?? 0);

        $productRoute = route('single.product', [
            'city_slug' => $city->city_slug ?? 'city',
            'area_slug' => $area->area_slug ?? 'area',
            'category_slug' => $itemCategory->category_slug ?? 'category',
            'item_slug' => $page->item_slug ?? 'item',
            'product_category_slug' => $productCategory->product_category_slug ?? 'subcategory',
            'product_slug' => $product->product_slug ?? 'product',
        ]);
    @endphp

    <div
        class="col-6 col-sm-6 col-md-4 col-lg-6 col-xl-4 custom-xs-full @if (str_contains(url()->current(), '/products')) single-item-countable @endif">
        <div class="amazon-deal-card">
            
            <!-- Image / Video Section -->
            <div class="amazon-image-wrapper">
                <!-- Discount Badge -->
                @if ($original_price && $original_price > $selling_price)
                    @php
                        $discount_percent = round((($original_price - $selling_price) / $original_price) * 100);
                    @endphp
                    @if($discount_percent > 0)
                        <div class="deal-badge-right">{{ $discount_percent }}% OFF</div>
                    @endif
                @endif

                <!-- Featured Badge -->
                @if($product->item_featured ?? false)
                    <div class="deal-badge-left">Featured</div>
                @endif

                @if (!empty($product->featured_video))
                    <a href="{{ $productRoute }}">
                        <video muted autoplay loop playsinline class="amazon-prod-media">
                            <source src="{{ $product->featured_video }}" type="video/mp4">
                        </video>
                    </a>
                @else
                    <a href="{{ $productRoute }}">
                        <img src="{{ get_marketplace_banner_image($product, 'thumbnail') }}" alt="{{ $product->title }}" class="amazon-prod-media" onerror="this.onerror=null; this.src='{{ get_product_image('', 'thumbnail') }}';">
                    </a>
                @endif
            </div>

            <!-- Card Body Content -->
            <div class="amazon-card-body">
                @if($productCategory)
                    <span class="amazon-card-brand">{{ $productCategory->product_category_name }}</span>
                @else
                    <span class="amazon-card-brand">Deal</span>
                @endif

                <h3 class="amazon-card-title">
                    <a href="{{ $productRoute }}">{{ $product->title }}</a>
                </h3>

                <div class="amazon-card-location">
                    <i class="fas fa-map-marker-alt" style="color: #565959; font-size: 11px; margin-right: 3px;"></i>
                    <span>
                        @if($city && $area)
                            <a href="{{ route('product.city.area', ['city_slug' => $city->city_slug, 'area_slug' => $area->area_slug]) }}">
                                {{ $city->city_name }}, {{ $area->area_name }}
                            </a>
                        @elseif($city)
                            <a href="{{ route('product.city', ['city_slug' => $city->city_slug]) }}">
                                {{ $city->city_name }}
                            </a>
                        @endif
                    </span>
                </div>

                <!-- Rating Section -->
                <div class="amazon-rating-row mt-1 mb-2">
                    <div class="rating-stars d-inline-block">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $rating)
                                <i class="fas fa-star" style="color: #ffa41c; font-size: 12px; margin-right: 1px;"></i>
                            @else
                                <i class="far fa-star" style="color: #ccc; font-size: 12px; margin-right: 1px;"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="amazon-rating-value">({{ $rating > 0 ? number_format($rating, 1) : 'New' }})</span>
                </div>

                <!-- Pricing & CTA Row -->
                <div class="amazon-price-row mt-auto">
                    <div class="d-flex align-items-baseline flex-wrap gap-1">
                        <span class="amazon-price-selling">{{ $product->getCurrency->symbol ?? '₹' }} {{ number_format($selling_price, 2) }}</span>
                        @if ($original_price && $original_price > $selling_price)
                            @php
                                $discount_percent = round((($original_price - $selling_price) / $original_price) * 100);
                            @endphp
                            @if($discount_percent > 0)
                                <span class="amazon-discount-badge">-{{ $discount_percent }}%</span>
                            @endif
                        @endif
                    </div>
                    @if ($original_price && $original_price > $selling_price)
                        <div class="amazon-price-original">M.R.P.: <del>{{ $product->getCurrency->symbol ?? '₹' }} {{ number_format($original_price, 2) }}</del></div>
                    @endif
                </div>

                <a href="{{ $productRoute }}" class="amazon-btn mt-3">
                    View Deal
                </a>
            </div>

        </div>
    </div>

    @if (!empty($search) && $key == 2)
        @break
    @endif
@endforeach
