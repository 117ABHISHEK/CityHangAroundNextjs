<div class="biz-featured-sidebar">
    <div class="widget-card">
        <h3 class="widget-title">{{ get_phrase('Featured Products') }}</h3>
        <div class="featured-products-list">
            @forelse($featuredProducts as $product)
                @php
                    $productSlug = $product->product_slug ?? '';
                    $productCategorySlug = $product->product_category_slug ?? 'subcategory';
                @endphp
                <a href="{{ url($product->city_slug . '/' . $product->area_slug . '/' . $productCategorySlug . '/' . $product->page_slug . '/' . $productCategorySlug . '/' . $productSlug) }}"
                   class="biz-featured-item">
                    <img src="{{ get_marketplace_banner_image((object) ['image' => $product->image], 'thumbnail') }}"
                         alt="{{ $product->title }}"
                         class="biz-featured-img"
                         loading="lazy">
                    <div class="biz-featured-info">
                        <h6>{{ ellipsis($product->title, 40) }}</h6>
                        <p>{{ $product->city_name ?? '' }}, {{ $product->area_name ?? '' }}</p>
                        <strong class="biz-featured-price">
                            {{ $product->currency_symbol ?? '₹' }}{{ $product->product_selling_price }}
                        </strong>
                    </div>
                </a>
            @empty
                <p class="text-muted small">{{ get_phrase('No featured products found') }}</p>
            @endforelse
        </div>
    </div>
</div>