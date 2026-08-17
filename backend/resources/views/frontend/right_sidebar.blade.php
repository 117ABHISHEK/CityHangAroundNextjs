@php
    $current_city = $city ?? $filter_city ?? null;
    $current_city_id = is_numeric($current_city) ? $current_city : ($current_city->id ?? null);
    if (!isset($featuredProducts)) {
        $featuredProducts = \Cache::remember('sidebar_featured_products_v10_' . ($current_city_id ?? 'global'), 3600, function() use ($current_city_id) {
            $query = \DB::table('marketplaces')
                ->join('pages', function($join) {
                    $join->on('pages.id', '=', 'marketplaces.page_id')
                         ->where('pages.item_status', '=', '2');
                })
                ->join('cities', 'cities.id', '=', 'pages.city_id')
                ->join('areas', 'areas.id', '=', 'pages.area_id')
                ->leftJoin('pagecategories', function($join) {
                    $join->on(\DB::raw('pagecategories.id'), '=', \DB::raw('CAST(NULLIF(SPLIT_PART(pages.category_id, \',\', 1), \'\') AS BIGINT)'));
                })
                ->leftJoin('currencies', 'currencies.id', '=', 'marketplaces.currency_id')
                ->where('marketplaces.product_status', 2)
                ->select(
                    'marketplaces.id',
                    'marketplaces.title',
                    'marketplaces.product_slug',
                    'marketplaces.image',
                    'marketplaces.product_selling_price',
                    'currencies.symbol as currency_symbol',
                    'cities.city_slug',
                    'cities.city_name',
                    'areas.area_slug',
                    'areas.area_name',
                    \DB::raw('MAX(pagecategories.category_slug) as page_category_slug'),
                    'pages.item_slug as page_slug',
                    \DB::raw('(SELECT categories.product_category_slug 
                               FROM category_product 
                               JOIN categories ON categories.id = category_product.product_category_id 
                               WHERE category_product.product_id = marketplaces.id 
                               LIMIT 1) as product_category_slug')
                )->groupBy(
                    'marketplaces.id',
                    'marketplaces.title',
                    'marketplaces.product_slug',
                    'marketplaces.image',
                    'marketplaces.product_selling_price',
                    'currencies.symbol',
                    'cities.city_slug',
                    'cities.city_name',
                    'areas.area_slug',
                    'areas.area_name',
                    'pages.item_slug'
                );

            if ($current_city_id) {
                $prods = (clone $query)->where('pages.city_id', $current_city_id)
                    ->orderByDesc(\DB::raw('MAX(CAST(marketplaces.item_featured AS INTEGER))'))
                    ->orderByDesc('marketplaces.id')
                    ->limit(6)
                    ->get();

                if ($prods->count() >= 2) return $prods;
            }

            return $query->orderByDesc(\DB::raw('MAX(CAST(marketplaces.item_featured AS INTEGER))'))
                ->orderByDesc('marketplaces.id')
                ->limit(6)
                ->get();
        });
    }

    if (!isset($sponsorPost)) {
        $sponsorPost = \Cache::remember('sidebar_sponsors_listing_v4', 3600, function () {
            return \App\Models\Sponsor::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->where('status', 1)
                ->orderByDesc('id')
                ->limit(5)
                ->get();
        });
    }
@endphp
<style>
    .RightSidebar {
        position: sticky;
        top: 70px;
        width: 100%;
        z-index: 10;
        background: transparent;
    }
    .widget-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 24px !important;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.01);
        border: 1px solid rgba(229, 231, 235, 0.8);
        transition: all 0.3s ease;
    }
    .widget-card:hover {
        box-shadow: 0 12px 36px -8px rgba(0, 0, 0, 0.07);
    }
    .widget-title {
        font-size: 15px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        letter-spacing: -0.01em;
    }
    .sponsor-item {
        display: flex;
        align-items: center;
        padding: 12px;
        border-radius: 12px;
        border: 1px solid rgba(229, 231, 235, 0.6);
        margin-bottom: 10px;
        transition: all 0.3s ease;
        text-decoration: none !important;
    }
    .sponsor-item:hover {
        background: #fafafa;
        border-color: rgba(255, 90, 95, 0.2);
        transform: translateY(-2px);
    }
    .sponsor-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 12px;
    }
    .sponsor-info h6 {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 2px;
        color: #1f2937;
    }
    .sponsor-info p {
        font-size: 11px;
        color: #6b7280;
        margin-bottom: 0;
        line-height: 1.4;
    }
    .listing-list-item {
        display: flex;
        align-items: center;
        padding: 12px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid rgba(229, 231, 235, 0.6);
        margin-bottom: 10px;
        text-decoration: none !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .listing-list-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
        border-color: rgba(255, 90, 95, 0.3);
    }
    .listing-list-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 12px;
    }
    .listing-list-info {
        flex: 1;
        min-width: 0;
    }
    .listing-list-info h6 {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 4px;
        color: #1f2937;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .listing-list-info p {
        font-size: 11px;
        color: #9ca3af;
        margin-bottom: 0;
    }
    .price-tag {
        color: #ff5a5f;
        font-weight: 700;
        font-size: 12px;
    }
    .view-more-link {
        font-size: 13px;
        font-weight: 600;
        color: #ff5a5f;
        text-decoration: none;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 15px;
    }
    .view-more-link:hover {
        color: #e04a4f;
        text-decoration: none;
        transform: scale(1.02);
    }
    @media (max-width: 767px) {
        .RightSidebar {
            position: relative;
            top: 0;
        }
    }
</style>
<div class="container-fluid">
    <div class="row">
        <aside class="RightSidebar d-none d-lg-block" id="sidebarToggle" tabindex="-1">
            <div class="p-0">
                @if(!request()->is('*create*') && !request()->is('*edit*') && !request()->is('*blog/create*'))
                    <!-- 1. Sponsored Section -->
                    @if($sponsorPost->isNotEmpty())
                        <div class="widget-card">
                            <h3 class="widget-title">{{ get_phrase('Sponsored') }}</h3>
                            <div class="sponsors">
                                @foreach ($sponsorPost as $sponsor)
                                    <a target="_blank" href="{{ $sponsor->ext_url }}" class="sponsor-item">
                                        <img src="{{ get_sponsor_image($sponsor->image, 'thumbnail') }}" class="sponsor-img"
                                            alt="{{ $sponsor->name }}">
                                        <div class="sponsor-info">
                                            <h6>{{ ellipsis($sponsor->name, 30) }}</h6>
                                            <p>{{ ellipsis(strip_tags($sponsor->description), 70) }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- 2. Featured Products Section -->
                    @if($featuredProducts->isNotEmpty())
                        <div class="widget-card">
                            <h3 class="widget-title">{{ get_phrase('Featured Products') }}</h3>
                            <div class="featured-products">
                                @foreach ($featuredProducts as $product)
                                    @php
                                        $productRoute = route('single.product', [
                                            'city_slug' => $product->city_slug ?? 'city',
                                            'area_slug' => $product->area_slug ?? 'area',
                                            'category_slug' => $product->page_category_slug ?? 'category',
                                            'item_slug' => $product->page_slug ?? 'item',
                                            'product_category_slug' => $product->product_category_slug ?? 'subcategory',
                                            'product_slug' => $product->product_slug ?? 'product'
                                        ]);
                                    @endphp
                                    <a href="{{ $productRoute }}" class="listing-list-item">
                                        <img src="{{ get_product_image($product->image, 'thumbnail') }}" class="listing-list-img"
                                            alt="{{ $product->title }}">
                                        <div class="listing-list-info">
                                            <h6>{{ ellipsis($product->title, 40) }}</h6>
                                            <p>{{ $product->city_name ?? '' }}, {{ $product->area_name ?? '' }}</p>
                                            <strong class="price-tag">
                                                {{ $product->currency_symbol ?? '₹' }}{{ $product->product_selling_price }}
                                            </strong>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif

            </div>
        </aside>
    </div>
</div>
