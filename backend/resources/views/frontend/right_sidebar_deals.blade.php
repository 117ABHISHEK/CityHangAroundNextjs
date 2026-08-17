@php
    $current_city = $city ?? $filter_city ?? $currentCity ?? null;
    $current_city_id = is_numeric($current_city) ? $current_city : ($current_city->id ?? null);
    $current_area = $area ?? $filter_area ?? null;
    $current_area_id = is_numeric($current_area) && (int) $current_area > 0
        ? (int) $current_area
        : ($current_area->id ?? null);

    // Detect context categories
    $is_product_category = (isset($category) && $category instanceof \App\Models\Category);
    $is_page_category = (isset($category) && $category instanceof \App\Models\Pagecategory);
    $current_category_id = (isset($category) && is_object($category)) ? $category->id : null;

    // 1. Fetch Featured & Trending Products (Deals) - 10 items total
    if (!isset($recentProducts) || $recentProducts->isEmpty()) {
        $recentProducts = \Cache::remember('sidebar_recent_deals_final_v7_' . ($current_city_id ?? 'global') . '_' . ($current_area_id ?? 'all') . '_' . ($current_category_id ?? 'all'), 3600, function () use ($current_city_id, $current_area_id, $current_category_id, $is_product_category, $is_page_category) {
            $productQuery = \App\Models\Marketplace::with(['page.city', 'page.area', 'page.categories', 'productCategories'])
                ->whereHas('page')
                ->where('product_status', 2);

            if ($current_city_id) {
                $productQuery->whereHas('page', fn($q) => $q->where('city_id', $current_city_id));
            }

            if ($current_category_id) {
                if ($is_product_category) {
                    $productQuery->where(function ($q) use ($current_category_id) {
                        $q->where('category', $current_category_id)
                            ->orWhere('category', 'like', $current_category_id . ',%')
                            ->orWhere('category', 'like', '%,' . $current_category_id . ',%')
                            ->orWhere('category', 'like', '%,' . $current_category_id)
                            ->orWhereHas('productCategories', fn($pq) => $pq->where('categories.id', $current_category_id));
                    });
                } elseif ($is_page_category) {
                    $productQuery->whereHas('page.categories', fn($q) => $q->where('page_category.category_id', $current_category_id));
                }
            }

            if ($current_city_id && $current_area_id) {
                $areaProducts = (clone $productQuery)
                    ->whereHas('page', fn($q) => $q->where('area_id', $current_area_id))
                    ->orderByDesc('item_featured')
                    ->orderByDesc('id')
                    ->limit(12)
                    ->get();

                if ($areaProducts->isNotEmpty()) {
                    return $areaProducts;
                }
            }

            return $productQuery->orderByDesc('item_featured')->orderByDesc('id')->limit(12)->get();
        });
    }

    // 2. Fetch Sponsors
    $sponsorPost = \Cache::remember('sidebar_sponsors_deals_final_v6', 3600, function () {
        return \App\Models\Sponsor::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('status', 1)
            ->orderByDesc('id')
            ->limit(5)
            ->get();
    });
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
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 20px !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid #f0f0f0;
    }

    .widget-title {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .view-more-link {
        font-size: 13px;
        font-weight: 500;
        color: #ff5a5f;
        text-decoration: none;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 10px;
    }

    .view-more-link:hover {
        color: #e04a4f;
        text-decoration: underline;
    }

    .sponsor-item {
        display: flex;
        align-items: center;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #f5f5f5;
        margin-bottom: 10px;
        transition: 0.3s;
        text-decoration: none !important;
    }

    .sponsor-item:hover {
        background: #fafafa;
    }

    .sponsor-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
        margin-right: 12px;
    }

    .sponsor-info h6 {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 2px;
        color: #333;
    }

    .sponsor-info p {
        font-size: 11px;
        color: #777;
        margin-bottom: 0;
        line-height: 1.4;
    }

    .listing-list-item {
        display: flex;
        align-items: center;
        padding: 10px;
        background: #fff;
        border-radius: 8px;
        border: 1px solid #f0f0f0;
        margin-bottom: 10px;
        text-decoration: none !important;
        transition: 0.2s;
    }

    .listing-list-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .listing-list-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        margin-right: 12px;
    }

    .listing-list-info {
        flex: 1;
        min-width: 0;
    }

    .listing-list-info h6 {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 2px;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .listing-list-info p {
        font-size: 11px;
        color: #999;
        margin-bottom: 0;
    }

    .price-tag {
        color: #ff5a5f;
        font-weight: 700;
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
                <!-- 1. Sponsored -->
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
                                        <p>{{ ellipsis(strip_tags($sponsor->description), 65) }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- 2. Trending Deals (Replaced Vertical Cards with Listing Style) -->
                @if($recentProducts->isNotEmpty())
                    <div class="widget-card">
                        <div class="widget-title">
                            <span>{{ get_phrase('Trending Deals') }}</span>
                        </div>
                        <div class="deal-vertical-list">
                            @foreach ($recentProducts->take(6) as $product)
                                @php
                                    $pPage = $product->page;
                                    $pCity = $pPage->city ?? null;
                                    $pArea = $pPage->area ?? null;
                                    $pItemCategory = optional($pPage->categories)->first();
                                    $pProdCategory = optional($product->productCategories)->first();
                                @endphp
                                @if($pPage && $pCity && $pArea && $pItemCategory && $pProdCategory)
                                                <a href="{{ route('single.product', [
                                        'city_slug' => $pCity->city_slug,
                                        'area_slug' => $pArea->area_slug,
                                        'category_slug' => $pItemCategory->category_slug,
                                        'item_slug' => $pPage->item_slug,
                                        'product_category_slug' => $pProdCategory->product_category_slug,
                                        'product_slug' => $product->product_slug,
                                    ]) }}" class="listing-list-item">
                                                    <img src="{{ get_marketplace_banner_image($product, 'thumbnail') }}" alt="{{ $product->title }}"
                                                        class="listing-list-img">
                                                    <div class="listing-list-info">
                                                        <h6>{{ ellipsis($product->title, 40) }}</h6>
                                                        <p class="price-tag">
                                                            {{ $product->getCurrency->symbol ?? '₹' }}{{ $product->product_selling_price }}
                                                        </p>
                                                    </div>
                                                </a>
                                @endif
                            @endforeach
                        </div>
                        <a href="{{ route('allproducts') }}" class="view-more-link">{{ get_phrase('View more') }}</a>
                    </div>
                @endif
            </div>
        </aside>
    </div>
</div>
