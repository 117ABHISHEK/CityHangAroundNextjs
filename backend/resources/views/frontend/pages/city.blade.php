<script type="application/ld+json">
            {
                 "@@context":"https://schema.org",
                 "@type":"Review","itemReviewed":{
                 "@type":"LocalBusiness",
                 "name":"Top 5 LocalBusiness in {{$city->city_name}}",
                 "url":"{{$_SERVER['REQUEST_URI']}}",
                 "address":{"@type":"PostalAddress","addressLocality":"{{$city->city_name}}"}},
                 "author":"Users",
                 "ReviewRating":{
                    "@type":"AggregateRating",
                    "ratingValue":"4.1",
                    "ratingCount":"14198",
                    "bestRating":"5"
            }}
</script>

{{-- Tailwind CDN removed - using existing Bootstrap CSS + inline utilities for performance --}}
<style>
    /* ===== Tailwind-equivalent utilities for city.blade.php ===== */
    .logo-color { color: #ff4939; }
    .city-card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .city-card-hover:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.13); }
    .sugg-card { border: 1px solid #f0f0f0; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .category-section { border-left: 4px solid #ff4939; padding-left: 10px; }
    .trending-card { background: #f9f9f9; border-radius: 10px; overflow: hidden; transition: all 0.2s ease; }
    .trending-card:hover { transform: translateY(-3px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .trending-img { width: 100%; height: 90px; object-fit: cover; }
    .btn-trans { background: transparent; border: none; outline: none; width: 100%; text-align: left; color: #888; }

    /* Layout grid */
    #pagedata { display: flex; flex-wrap: wrap; gap: 1.5rem; }
    #pagedata > div { flex: 1 1 45%; min-width: 300px; }

    /* Tailwind spacing / sizing replacements */
    .w-full { width: 100%; }
    .w-10 { width: 2.5rem; }
    .h-10 { height: 2.5rem; }
    .h-32 { height: 8rem; }
    .h-24 { height: 6rem; }
    .h-full { height: 100%; }
    .mx-auto { margin-left: auto; margin-right: auto; }
    .mb-1 { margin-bottom: 0.25rem; }
    .mb-2 { margin-bottom: 0.5rem; }
    .mb-3 { margin-bottom: 0.75rem; }
    .mb-4 { margin-bottom: 1rem; }
    .mr-1 { margin-right: 0.25rem; }
    .p-2 { padding: 0.5rem; }
    .p-3 { padding: 0.75rem; }
    .p-4 { padding: 1rem; }
    .px-5 { padding-left: 1.25rem; padding-right: 1.25rem; }
    .py-2\.5 { padding-top: 0.625rem; padding-bottom: 0.625rem; }
    .pl-0 { padding-left: 0; }
    .pr-0 { padding-right: 0; }
    .mt-1 { margin-top: 0.25rem; }
    .mt-auto { margin-top: auto; }

    /* Flex */
    .flex { display: flex; }
    .flex-col { flex-direction: column; }
    .flex-grow { flex-grow: 1; }
    .flex-shrink-0 { flex-shrink: 0; }
    .items-center { align-items: center; }
    .justify-between { justify-content: space-between; }
    .justify-center { justify-content: center; }
    .space-x-3 > * + * { margin-left: 0.75rem; }
    .space-y-4 > * + * { margin-top: 1rem; }
    .space-y-6 > * + * { margin-top: 1.5rem; }

    /* Grid for listing cards */
    .grid { display: grid; }
    .grid-cols-1 { grid-template-columns: 1fr; }
    .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
    .gap-3 { gap: 0.75rem; }
    .gap-4 { gap: 1rem; }
    @media (min-width: 640px) {
        .sm\:grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        .sm\:grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
    }

    /* Text */
    .text-sm { font-size: 0.875rem; }
    .text-lg { font-size: 1.125rem; }
    .text-xs { font-size: 0.75rem; }
    .font-bold { font-weight: 700; }
    .font-semibold { font-weight: 600; }
    .uppercase { text-transform: uppercase; }
    .tracking-tight { letter-spacing: -0.015em; }
    .leading-tight { line-height: 1.25; }
    .text-center { text-align: center; }
    .text-gray-500 { color: #6b7280; }
    .text-gray-400 { color: #9ca3af; }
    .text-gray-800 { color: #1f2937; }
    .text-black { color: #000; }
    .text-primary { color: #0d6efd; }
    .font-weight-light { font-weight: 300; }
    .line-clamp-1 { overflow: hidden; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; }
    .line-clamp-2 { overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }

    /* Backgrounds & borders */
    .bg-white { background: #fff; }
    .bg-gray-50 { background: #f9fafb; }
    .bg-gray-100 { background: #f3f4f6; }
    .border { border: 1px solid #e5e7eb; }
    .border-gray-100 { border-color: #f3f4f6; }
    .rounded-lg { border-radius: 0.5rem; }
    .rounded-full { border-radius: 9999px; }
    .overflow-hidden { overflow: hidden; }
    .shadow { box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06); }
    .shadow-sm { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .object-cover { object-fit: cover; }
    .block { display: block; }
    .relative { position: relative; }

    /* Hover / transitions */
    .transition-all { transition: all; }
    .duration-300 { transition-duration: 300ms; }
    .duration-500 { transition-duration: 500ms; }
    .transform { transform: translateZ(0); }
    .hover\:-translate-y-1:hover { transform: translateY(-0.25rem); }
    .hover\:shadow-md:hover { box-shadow: 0 4px 6px rgba(0,0,0,0.07), 0 2px 4px rgba(0,0,0,0.06); }
    .hover\:bg-gray-200:hover { background: #e5e7eb; }
    .group:hover .group-hover\:scale-110 { transform: scale(1.1); }
    .transition-transform { transition: transform; }
    .transition-colors { transition: color; }
    .group:hover .group-hover\:text-\[\#ff4939\] { color: #ff4939; }
    .hover\:underline:hover { text-decoration: underline; }
    .hover\:text-\[\#ff4939\]:hover { color: #ff4939; }
</style>

{{-- Breadcrumb --}}
<div class="row mb-2">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white pl-0 pr-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('pages') }}">
                        <i class="fas fa-bars"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('pages') }}">All Categories</a></li>
                <li class="breadcrumb-item"><a href="">{{ $city->city_name }}</a></li>
            </ol>
        </nav>
    </div>
</div>

{{-- Page Title --}}
<h1 class="font-weight-light text-primary mb-4">
    Explore {{ $city->city_name }} – Your Ultimate City Guide!
</h1>

{{-- Main Layout: Left Feed & Right Listings (matching 3-column system with outer Sponsored column) --}}
<div class="w-full mx-auto grid grid-cols-1 md:grid-cols-12 gap-6" id="pagedata">

    {{-- LEFT COLUMN: Feed (Timeline Feed) --}}
    <div class="md:col-span-6 space-y-6">
        
        {{-- Create Post input box if user is logged in --}}
        @if(auth()->check())
            @php
                $user_info = auth()->user();
            @endphp
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 mb-4">
                <div class="flex items-center space-x-3">
                    <img src="{{ get_user_image(auth()->user()->photo, 'optimized') }}"
                        class="w-10 h-10 rounded-full object-cover" alt="User" />
                    <button class="bg-gray-100 hover:bg-gray-200 text-gray-500 rounded-full py-2.5 px-5 text-left text-sm flex-grow transition-colors btn-trans" data-bs-toggle="modal" data-bs-target="#createPost">
                        {{ get_phrase("What's on your mind, :name?", ['name' => auth()->user()->name]) }}
                    </button>
                </div>
            </div>
            @include('frontend.main_content.create_post_modal')
        @endif

        {{-- Scrollable timeline posts --}}
        <div id="timeline-posts" class="space-y-4">
            @include('frontend.main_content.posts', ['types' => ['user_post', 'page', 'events', 'blogs']])
        </div>

    </div>{{-- end Left column (Feed) --}}


    {{-- RIGHT COLUMN: Directory Listings & Categories Catalog --}}
    <div class="md:col-span-6 space-y-6">

        {{-- Categories and listings under them --}}
        @foreach ($categories as $category)

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-bold category-section">{{ $category->category_name }}</h2>
                    @if($category->category_slug)
                        <a href="{{ route('page.category', $category->category_slug) }}"
                           class="text-sm logo-color hover:underline">
                            View more
                        </a>
                    @endif
                </div>

                @php
                    $mypages = isset($pagesByCategory[$category->id]) ? array_slice($pagesByCategory[$category->id], 0, 4) : [];
                @endphp

                {{-- Grid showing pages --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($mypages as $mypage)
                        @php
                            // Get category details from eager loaded collection in memory
                            $pageCategory = $mypage->categories->first(function($c) use ($category) {
                                return $c->id == $category->id || $c->category_parent_id == $category->id;
                            });

                            $catslug = $pageCategory->category_slug ?? null;
                            $categoryName = $pageCategory->category_name ?? null;
                            $likecount = $mypage->likes_count; // From withCount('likes')
                        @endphp

                        @if($catslug)
                        <div class="group bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col h-full hover:-translate-y-1 transform" id="page-{{ $mypage->id }}">
                            {{-- Image --}}
                            <a href="{{ route('single.page', [
                                    'city_slug'     => $mypage->city_slug,
                                    'area_slug'     => $mypage->area_slug,
                                    'category_slug' => $catslug,
                                    'item_slug'     => $mypage->item_slug
                                ]) }}" class="block h-32 bg-gray-50 overflow-hidden relative">
                                <img src="{{ get_page_logo($mypage->logo, 'logo') }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                     alt="{{ $mypage->title }}"
                                     onerror="this.onerror=null; this.src='{{ asset('storage/pages/logo/default.png') }}';">
                            </a>

                            {{-- Content --}}
                            <div class="p-3 text-center flex flex-col flex-grow">
                                <p class="text-[10px] font-bold uppercase text-gray-500 group-hover:text-[#ff4939] mb-1 transition-colors tracking-tight">
                                    {{ $categoryName }}
                                </p>
                                
                                <a href="{{ route('single.page', [
                                        'city_slug'     => $mypage->city_slug,
                                        'area_slug'     => $mypage->area_slug,
                                        'category_slug' => $catslug,
                                        'item_slug'     => $mypage->item_slug
                                    ]) }}" class="text-sm font-bold text-gray-800 line-clamp-1 mb-1 group-hover:text-[#ff4939] transition-colors leading-tight">
                                    {{ $mypage->title }}
                                </a>

                                <p class="text-[11px] text-gray-500 line-clamp-1 mb-2">
                                    {{ $mypage->area_name }}, {{ $city->city_name }}
                                </p>
                                
                                <div class="flex items-center justify-center text-[11px] text-black mt-auto">
                                    <i class="fa fa-thumbs-up mr-1 text-gray-400 group-hover:text-[#ff4939] transition-colors"></i>
                                    <span class="text-black group-hover:text-[#ff4939] transition-colors">{{ $likecount }} People follow this</span>
                                </div>
                            </div>
                        </div>
                        @endif

                    @endforeach
                </div>{{-- end pages grid --}}

            </div>{{-- end category box --}}
        @endforeach

        {{-- Trending Listings --}}
        @if(isset($recentBusinesses) && count($recentBusinesses) > 0)
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-bold">Trending Listings</h2>
                <a href="{{ route('pages') }}" class="text-sm logo-color hover:underline">View more</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @php $bizCount = 0; @endphp
                @foreach ($recentBusinesses as $business)
                    @if($bizCount == 6) @break @endif
                    @php
                        // Support both Eloquent models and cached stdClass light objects
                        $bizCitySlug = $business->city_slug ?? $business->city?->city_slug ?? null;
                        $bizAreaSlug = $business->area_slug ?? $business->area?->area_slug ?? null;
                        $bizCategory = $business->categories->first() ?? null;
                    @endphp
                    @if($bizCitySlug && $bizAreaSlug && $bizCategory)
                    <a href="{{ route('single.page', [
                            'city_slug'     => $bizCitySlug,
                            'area_slug'     => $bizAreaSlug,
                            'category_slug' => $bizCategory->category_slug,
                            'item_slug'     => $business->item_slug,
                        ]) }}" class="block">
                        <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm flex flex-col h-full trending-card">
                            <img src="{{ get_page_logo($business->logo, 'logo') }}"
                                 alt="{{ $business->title }}"
                                 class="w-full h-24 object-cover flex-shrink-0"
                                 onerror="this.onerror=null; this.src='{{ asset('storage/pages/logo/default.png') }}';">
                            <div class="p-2 flex flex-col justify-between flex-grow">
                                <p class="font-semibold text-xs line-clamp-2">{{ strip_tags($business->title ?? '') }}</p>
                                <p class="text-[10px] text-gray-500 mt-1">
                                    {{ $bizCategory->category_name }}
                                </p>
                            </div>
                        </div>
                    </a>
                    @php $bizCount++; @endphp
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- Trending Events --}}
        @if(isset($recentEvents) && count($recentEvents) > 0)
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-bold">Trending Events</h2>
                <a href="{{ route('event') }}" class="text-sm logo-color hover:underline">View more</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @php $count = 0; @endphp
                @foreach ($recentEvents as $event)
                    @if ($count == 6) @break @endif
                    @php
                        $evtCity     = $event->city ?? null;
                        $evtArea     = $event->area ?? null;
                        $evtCategory = $event->categories->first() ?? null;
                    @endphp
                    @if($evtCity && $evtArea && $evtCategory)
                    <a href="{{ route('single.event', [
                            'id'            => $event->id,
                            'city_slug'     => $evtCity->city_slug,
                            'area_slug'     => $evtArea->area_slug,
                            'category_slug' => $evtCategory->category_slug ?? 'event',
                            'event_slug'    => $event->event_slug,
                        ]) }}" class="block">
                        <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm flex flex-col h-full trending-card">
                            <img src="{{ get_event_banner_image($event, 'thumbnail') }}"
                                 alt="{{ strip_tags($event->title ?? '') }}"
                                 class="w-full h-24 object-cover flex-shrink-0">
                            <div class="p-2 flex flex-col justify-between flex-grow">
                                <p class="font-semibold text-xs line-clamp-2">{{ strip_tags($event->title ?? '') }}</p>
                                <p class="text-[10px] text-gray-500 mt-1">
                                    {{ $evtCategory->category_name }}
                                </p>
                            </div>
                        </div>
                    </a>
                    @php $count++; @endphp
                    @endif
                @endforeach
            </div>
        </div>
        @endif

    </div>{{-- end Right column (Listings) --}}

</div>{{-- end main grid --}}

{{-- Load the feed-related interactive JavaScript scripts for comments/reactions/modal creation --}}
@include('frontend.main_content.scripts')
