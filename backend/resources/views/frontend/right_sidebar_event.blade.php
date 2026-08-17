@php
    $current_city = $city ?? $filter_city ?? $currentCity ?? null;
    $current_city_id = is_numeric($current_city) ? $current_city : ($current_city->id ?? null);
    $current_area = $area ?? $filter_area ?? null;
    $current_area_id = is_numeric($current_area) && (int) $current_area > 0
        ? (int) $current_area
        : ($current_area->id ?? null);

    // 1. Fetch Trending Events (priority given to current city)
    if (!isset($recentEvents) || $recentEvents->isEmpty()) {
        $recentEvents = \Cache::remember('sidebar_trending_events_v4_' . ($current_city_id ?? 'global') . '_' . ($current_area_id ?? 'all'), 3600, function () use ($current_city_id, $current_area_id) {
            $eventQuery = \App\Models\Event::with(['city', 'area', 'categories'])
                ->where('event_status', 2);

            if ($current_city_id) {
                $cityQuery = (clone $eventQuery)->where('city_id', $current_city_id);

                if ($current_area_id) {
                    $areaEvents = (clone $cityQuery)->where('area_id', $current_area_id)
                        ->orderByDesc('id')
                        ->limit(6)
                        ->get();

                    if ($areaEvents->isNotEmpty()) {
                        return $areaEvents;
                    }
                }

                return $cityQuery
                    ->orderByDesc('id')
                    ->limit(6)
                    ->get();
            }

            return $eventQuery->orderByDesc('id')->limit(6)->get();
        });
    }

    // 2. Fetch Sponsors (Shared global cache)
    $sponsorPost = \Cache::remember('sidebar_sponsors_global_v5', 3600, function () {
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
        background: transparent;
        z-index: 10;
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
        transition: color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .view-more-link:hover {
        color: #e04a4f;
        text-decoration: underline;
    }

    /* Sponsor Styles */
    .sponsor-item {
        display: flex;
        align-items: center;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #f5f5f5;
        margin-bottom: 10px;
        transition: background 0.3s;
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

    /* Trending Listings List (Horizontal) */
    .listing-list-item {
        display: flex;
        align-items: center;
        padding: 8px;
        background: #fff;
        border-radius: 8px;
        border: 1px solid #f0f0f0;
        margin-bottom: 8px;
        text-decoration: none !important;
        transition: transform 0.2s, box-shadow 0.2s;
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

    /* Featured Product Cards (Vertical) */
    .product-list-item {
        text-decoration: none !important;
        display: block;
        margin-bottom: 15px;
    }

    .product-side-card {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #eee;
        transition: box-shadow 0.3s;
        text-align: center;
        padding-bottom: 12px;
    }

    .product-side-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .product-side-img {
        width: 100%;
        height: 180px;
        object-fit: contain;
        background: #f9f9f9;
        padding: 10px;
    }

    .product-side-info {
        padding: 10px 10px 0 10px;
    }

    .product-side-info h6 {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .product-side-info .location {
        font-size: 12px;
        color: #999;
        margin-bottom: 5px;
        display: block;
    }

    .product-side-info .price {
        font-size: 15px;
        font-weight: 700;
        color: #ff5a5f;
    }

    @media (max-width: 767px) {
        .RightSidebar {
            position: relative;
            top: 0;
        }
    }
</style>

<div class="row">
    <aside class="RightSidebar d-none d-lg-block" id="sidebarToggle" tabindex="-1">
        <div class="p-0">
            <!-- 1. Sponsored Section -->
            @if($sponsorPost->isNotEmpty())
                <div class="widget-card">
                    <h3 class="widget-title">{{ get_phrase('Sponsored') }}</h3>
                    <div class="sponsors">
                        @foreach ($sponsorPost as $sponsor)
                            <a target="_blank" href="{{ $sponsor->ext_url }}" class="sponsor-item">
                                <img src="{{ get_sponsor_image($sponsor->image, 'thumbnail') }}" class="sidebar-mini-img"
                                    alt="{{ $sponsor->name }}">
                                <div class="sponsor-info">
                                    <h6 class="sidebar-info-h">{{ ellipsis($sponsor->name, 30) }}</h6>
                                    <p class="sidebar-info-p">{{ ellipsis(strip_tags($sponsor->description), 70) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 2. Trending Events -->
            @if($recentEvents->isNotEmpty())
                <div class="widget-card">
                    <div class="widget-title">
                        <span>{{ get_phrase('Trending Events') }}</span>
                    </div>
                    <div class="listing-vertical-list">
                        @foreach ($recentEvents as $event)
                            @php
                                $evtCity = $event->city ?? null;
                                $evtArea = $event->area ?? null;
                                $evtCategory = optional($event->categories)->first();
                            @endphp
                            @if($evtCity && $evtArea && $evtCategory)
                                        <a href="{{ route('single.event', [
                                    'city_slug' => $evtCity->city_slug,
                                    'area_slug' => $evtArea->area_slug,
                                    'category_slug' => $evtCategory->category_slug,
                                    'event_slug' => $event->event_slug,
                                ]) }}" class="listing-list-item">
                                            <img src="{{ get_page_logo($event->logo, 'logo') }}" alt="{{ $event->title }}"
                                                class="sidebar-mini-img">
                                            <div class="listing-list-info">
                                                <h6 class="sidebar-info-h">{{ $event->title }}</h6>
                                                <p class="sidebar-info-p">{{ $evtCategory->category_name }}</p>
                                            </div>
                                        </a>
                            @endif
                        @endforeach
                    </div>
                    <a href="{{ route('event') }}" class="view-more-link">{{ get_phrase('View more') }}</a>
                </div>
            @endif
        </div>
    </aside>
</div>
