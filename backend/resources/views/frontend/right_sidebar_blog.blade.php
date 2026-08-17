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

    // 1. Fetch Featured & Trending Products (Deals) - Cached for performance
    $recentProducts = \Cache::remember('sidebar_recent_deals_blog_v5_' . ($current_city_id ?? 'global') . '_' . ($current_area_id ?? 'all') . '_' . ($current_category_id ?? 'all'), 3600, function() use ($current_city_id, $current_area_id, $current_category_id, $is_product_category, $is_page_category) {
        $productQuery = \App\Models\Marketplace::with(['page.city', 'page.area', 'page.categories', 'productCategories'])
            ->whereHas('page')
            ->where('product_status', 2);

        if ($current_city_id) {
            $productQuery->whereHas('page', function($q) use ($current_city_id) {
                $q->where('city_id', $current_city_id);
            });
        }

        if ($current_category_id) {
            if ($is_product_category) {
                $productQuery->where(function($q) use ($current_category_id) {
                    $q->where('category', $current_category_id)
                      ->orWhere('category', 'like', $current_category_id . ',%')
                      ->orWhere('category', 'like', '%,' . $current_category_id . ',%')
                      ->orWhere('category', 'like', '%,' . $current_category_id)
                      ->orWhereHas('productCategories', function($pq) use ($current_category_id) {
                          $pq->where('categories.id', $current_category_id);
                      });
                });
            } elseif ($is_page_category) {
                $productQuery->whereHas('page.categories', function($q) use ($current_category_id) {
                    $q->where('page_category.category_id', $current_category_id);
                });
            }
        }

        if ($current_city_id && $current_area_id) {
            $areaResults = (clone $productQuery)
                ->whereHas('page', fn($q) => $q->where('area_id', $current_area_id))
                ->orderByDesc('item_featured')
                ->orderByDesc('id')
                ->limit(10)
                ->get();

            if ($areaResults->isNotEmpty()) {
                return $areaResults;
            }
        }

        $results = $productQuery->orderBy('item_featured', 'desc')
                                ->orderBy('id', 'desc')
                                ->limit(10)
                                ->get();

        if ($results->isEmpty() && $current_city_id) {
            $results = \App\Models\Marketplace::with(['page.city', 'page.area', 'page.categories', 'productCategories'])
                ->whereHas('page', fn($q) => $q->where('city_id', $current_city_id))
                ->where('product_status', 2)
                ->orderBy('item_featured', 'desc')
                ->orderBy('id', 'desc')
                ->limit(10)
                ->get();
        }

        if ($results->isEmpty() && !$current_city_id) {
            $results = \App\Models\Marketplace::with(['page.city', 'page.area', 'page.categories', 'productCategories'])
                ->whereHas('page')
                ->where('product_status', 2)
                ->orderBy('item_featured', 'desc')
                ->orderBy('id', 'desc')
                ->limit(10)
                ->get();
        }

        return $results;
    });

    // 2. Sponsors - Cached
    $sponsorPost = \Cache::remember('sidebar_spons_v4', 3600, function() {
        return \App\Models\Sponsor::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('status', 1)
            ->orderByDesc('id')
            ->limit(5)
            ->get();
    });

    // 3. Trending Blogs - FAST using content_master + blogcategories (NOT pagecategories)
    $latestBlogs = \Cache::remember('sidebar_trending_blogs_v4_' . ($current_city_id ?? 'global') . '_' . ($current_area_id ?? 'all'), 3600, function() use ($current_city_id, $current_area_id) {
        $fetchBlogs = function ($areaId = null) use ($current_city_id) {
            return \DB::table('content_master')
                ->join('blogs', 'blogs.id', '=', 'content_master.source_id')
                ->where('content_master.source_type', 'blog')
                ->where('blogs.blog_status', 2)
                ->when($current_city_id, fn($query) => $query->where('content_master.city_id', $current_city_id))
                ->when($areaId, fn($query) => $query->where('content_master.area_id', $areaId))
                ->join('cities', 'cities.id', '=', 'content_master.city_id')
                ->leftJoin('areas', 'areas.id', '=', 'content_master.area_id')
                ->leftJoin('blogcategories', 'blogcategories.id', '=', 'content_master.category_id')
                ->select([
                    'blogs.id',
                    'blogs.title',
                    'blogs.blog_slug',
                    'blogs.created_at',
                    'blogs.thumbnail',
                    'cities.city_slug',
                    \DB::raw('MAX(areas.area_slug) as area_slug'),
                    \DB::raw('MAX(blogcategories.category_slug) as category_slug'),
                    \DB::raw('MAX(blogcategories.category_name) as category_name'),
                ])
                ->groupBy([
                    'blogs.id',
                    'blogs.title',
                    'blogs.blog_slug',
                    'blogs.created_at',
                    'blogs.thumbnail',
                    'cities.city_slug',
                ])
                ->orderByDesc('blogs.created_at')
                ->limit(10)
                ->get()
                ->unique('id')
                ->take(5);
        };

        $rows = $current_city_id && $current_area_id ? $fetchBlogs($current_area_id) : collect();
        if ($rows->isEmpty()) {
            $rows = $fetchBlogs();
        }

        return $rows->map(function ($r) {
            return (object) [
                'id'         => $r->id,
                'title'      => $r->title,
                'blog_slug'  => $r->blog_slug,
                'created_at' => \Carbon\Carbon::parse($r->created_at),
                'logo'       => $r->thumbnail,
                'area_slug'  => $r->area_slug,
                'category'   => (object) ['category_slug' => $r->category_slug ?? null],
                'city'       => (object) ['city_slug' => $r->city_slug ?? null],
            ];
        });
    });
@endphp

<style>
    .RightSidebar { position: sticky; top: 70px; width: 100%; z-index: 10; background: transparent; }
    .widget-card { background: #fff; border-radius: 12px; padding: 15px; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); border: 1px solid #f0f0f0; }
    .widget-title { font-size: 16px; font-weight: 700; color: #333; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; }
    .view-more-link { font-size: 13px; font-weight: 500; color: #ff5a5f; text-decoration: none; transition: 0.3s; display: flex; align-items: center; justify-content: center; }
    .view-more-link:hover { color: #e04a4f; text-decoration: underline; }
    .sponsor-item { display: flex; align-items: center; padding: 10px; border-radius: 8px; border: 1px solid #f5f5f5; margin-bottom: 10px; transition: 0.3s; text-decoration: none !important; }
    .sponsor-item:hover { background: #fafafa; }
    .sidebar-mini-img { width: 50px; height: 50px; object-fit: cover; border-radius: 6px; margin-right: 12px; }
    .sponsor-info h6 { font-size: 13px; font-weight: 600; margin-bottom: 2px; color: #333; }
    .sponsor-info p { font-size: 11px; color: #777; margin-bottom: 0; line-height: 1.4; }
    .listing-list-item { display: flex; align-items: center; padding: 10px; background: #fff; border-radius: 8px; border: 1px solid #f0f0f0; margin-bottom: 8px; text-decoration: none !important; transition: 0.2s; }
    .listing-list-item:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .listing-list-img { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; margin-right: 12px; }
    .listing-list-info { flex: 1; min-width: 0; }
    .listing-list-info h6 { font-size: 13px; font-weight: 600; margin-bottom: 2px; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .listing-list-info p { font-size: 11px; color: #999; margin-bottom: 0; }
    @media (max-width: 767px) { .RightSidebar { position: relative; top: 0; } }
</style>

    <aside class="RightSidebar d-none d-lg-block" id="sidebarToggle" tabindex="-1" style="position: sticky; top: 20px;">
        <!-- 1. Sponsored -->
        @if($sponsorPost->isNotEmpty())
        <div class="widget-card">
            <h3 class="widget-title">{{ get_phrase('Sponsored') }}</h3>
            <div class="sponsors">
                @foreach ($sponsorPost as $sponsor)
                <a target="_blank" href="{{ $sponsor->ext_url }}" class="sponsor-item">
                    <img src="{{ get_sponsor_image($sponsor->image, 'thumbnail') }}" class="sidebar-mini-img" alt="{{ $sponsor->name }}">
                    <div class="sponsor-info">
                        <h6>{{ ellipsis($sponsor->name, 30) }}</h6>
                        <p>{{ ellipsis(strip_tags($sponsor->description), 65) }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
        

           
        <!-- 2. Trending Blogs -->
        @if($latestBlogs->isNotEmpty())
        <div class="widget-card">
            <div class="widget-title">
                <span>{{ get_phrase('Trending Blogs') }}</span>
            </div>
            <div class="listing-vertical-list">
                @foreach ($latestBlogs as $blog)
                @php
                    $bCatSlug  = $blog->category->category_slug ?? null;
                    $bCitySlug = $blog->city->city_slug ?? null;
                    $bAreaSlug = $blog->area_slug ?? null;
                    $bCanLink  = $bCatSlug && $bCitySlug;
                @endphp
                @if($bCanLink)
                <a href="{{ route('single.blog', [
                        'category_slug' => $bCatSlug,
                        'blog_slug'     => $blog->blog_slug,
                        'city_slug'     => $bCitySlug,
                        'area_slug'     => $bAreaSlug,
                    ]) }}" class="listing-list-item">
                @else
                <div class="listing-list-item">
                @endif
                    <img src="{{ get_blog_banner_image($blog, 'thumbnail') }}" class="listing-list-img" alt="{{ $blog->title }}">
                    <div class="listing-list-info">
                        <h6>{{ ellipsis($blog->title, 40) }}</h6>
                        <p>{{ $blog->created_at->format('d M Y') }}</p>
                    </div>
                @if($bCanLink)
                </a>
                @else
                </div>
                @endif
                @endforeach
            </div>
            <a href="{{ route('blogs') }}" class="view-more-link">{{ get_phrase('View more') }}</a>
        </div>
        @endif
    </aside>
