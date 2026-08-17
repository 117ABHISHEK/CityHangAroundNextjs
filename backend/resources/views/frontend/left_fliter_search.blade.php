<!-- Tailwind CSS (for layout) -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<style>
    /* Premium Redesigned Sidebar Container */
    .widget_top_filter {
        background: #ffffff;
        padding: 24px 20px;
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 16px;
        position: relative;
        top: 20px;
        z-index: 20 !important;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
    }
    .widget_top_filter:hover {
        box-shadow: 0 12px 36px -8px rgba(0, 0, 0, 0.08), 0 2px 5px rgba(0, 0, 0, 0.03);
    }

    /* WHEN OPEN -> BRING TO FRONT */
    .bootstrap-select.show,
    .bootstrap-select.open {
        z-index: 999999 !important;
    }
    /* Fix Bootstrap selectpicker dropdown z-index */
    .bootstrap-select .dropdown-menu {
        z-index: 999999 !important;
        border: 1px solid rgba(0,0,0,0.08) !important;
        box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important;
        border-radius: 8px !important;
    }
    /* Style improvements for selectpicker */
    .left_size_custom_section .form-control {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    .bootstrap-select .btn {
        background-color: #fff !important;
        border: 1.5px solid #e5e7eb !important;
        border-radius: 8px !important;
        height: 44px !important;
        padding: 8px 16px !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        color: #4b5563 !important;
        transition: all 0.2s ease-in-out !important;
    }
    .bootstrap-select .btn:hover {
        border-color: #ff5a5f !important;
        background-color: #fafafa !important;
    }

    /* Make all selectpicker boxes the same full width */
    .left_size_custom_section .bootstrap-select,
    .left_size_custom_section .bootstrap-select>.dropdown-toggle,
    .left_size_custom_section .bootstrap-select .btn {
        width: 100% !important;
        max-width: 100% !important;
    }
    #mobileSidebar {
        z-index: 9999;
        padding-bottom: 4rem;
        border-right: 1px solid rgba(229, 231, 235, 0.8);
    }
    #overlay {
        z-index: 9998;
    }
    .category-title {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        letter-spacing: -0.01em;
    }
    .category-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .category-item {
        margin: 0 !important;
    }
    .category-item a {
        display: inline-flex;
        align-items: center;
        justify-content: left;
        min-height: 32px;
        position: relative;
        padding: 6px 14px;
        background: #f3f4f6;
        color: #4b5563 !important;
        text-decoration: none !important;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid rgba(229, 231, 235, 0.5);
        border-radius: 50px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: left;
        line-height: 1.3;
    }
    .category-item a:hover {
        background: #ff5a5f;
        border-color: #ff5a5f;
        color: #ffffff !important;
        box-shadow: 0 8px 16px -4px rgba(255, 90, 95, 0.35);
        transform: translateY(-2px);
    }
    .category-item a:active {
        transform: translateY(0);
    }
    .category-item.active a,
    .category-item a.active {
        background: #ff5a5f;
        border-color: #ff5a5f;
        color: #ffffff !important;
        box-shadow: 0 8px 16px -4px rgba(255, 90, 95, 0.35);
    }
    .hidden-category {
        display: none;
    }
    .show-more {
        display: inline-flex;
        align-items: center;
        margin-top: 10px;
        font-size: 13px;
        color: #ff5a5f !important;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: color 0.2s ease;
    }
    .show-more:hover {
        color: #e04a4f !important;
        text-decoration: underline;
    }
    .form-group {
        width: 100%;
        margin-bottom: 12px;
    }
    .form-group .bootstrap-select {
        width: 100% !important;
        display: block !important;
    }
    /* button inside */
    .form-group .bootstrap-select>.dropdown-toggle {
        width: 100% !important;
    }
    /* dropdown menu */
    .form-group .bootstrap-select .dropdown-menu {
        width: 100% !important;
    }
    /* Same height for all filter rows (City, Area, Category, etc.) */
    .widget_top_filter .form-group {
        width: 100%;
        margin-bottom: 12px;
        min-height: 48px;
        display: flex;
        position: relative !important;
        align-items: center;
    }
    /* Elevate active form-group above siblings so dropdown is fully visible */
    .widget_top_filter .form-group.active-form-group {
        z-index: 999999 !important;
    }
    /* Make the selectpicker fill that height and width */
    .widget_top_filter .bootstrap-select,
    .widget_top_filter .bootstrap-select>.dropdown-toggle {
        width: 100% !important;
        height: 100%;
    }
    /* Optional: make Submit / Reset row same width */
    .widget_top_filter .flex.gap-2 .btn {
        width: 100%;
    }
    /* Submit & Reset button styles */
    .btn-submit-premium {
        background: linear-gradient(135deg, #ff5a5f 0%, #ff7b54 100%);
        color: #ffffff !important;
        border: none;
        border-radius: 8px;
        height: 42px;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.02em;
        cursor: pointer;
        flex: 1;
        box-shadow: 0 4px 14px rgba(255, 90, 95, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-submit-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 90, 95, 0.4);
        background: linear-gradient(135deg, #ff474d 0%, #ff6b42 100%);
    }
    .btn-submit-premium:active {
        transform: translateY(0);
        box-shadow: 0 3px 10px rgba(255, 90, 95, 0.2);
    }
    .btn-reset-premium {
        color: #4b5563 !important;
        background: #ffffff;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        height: 42px;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        text-decoration: none !important;
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .btn-reset-premium:hover {
        background: #ff5a5f;
        color: #ffffff !important;
        border-color: #ff5a5f;
        box-shadow: 0 6px 16px rgba(255, 90, 95, 0.25);
        transform: translateY(-2px);
    }
    .btn-reset-premium:active {
        transform: translateY(0);
    }
    /* Make dropdown float on top, not push or overlap oddly */
    .widget_top_filter .bootstrap-select .dropdown-menu {
        width: 100% !important;
        z-index: 99999 !important;
        z-index: 999999 !important;
    position: absolute !important;
}
    /* Prevent clipping from parent */
    .widget_top_filter,
    .left_size_custom_section,
    .form-group,
    .bootstrap-select {
        overflow: visible !important;
    }
    /* FIX FOR STICKY SIDEBAR */
    .widget_top_filter {
        position: sticky;
        top: 24px;
        z-index: 20 !important;
    }
    /* Mobile sidebar fix */
    #mobileSidebar {
        overflow-y: auto;
        overflow-x: visible !important;
    }
    /* Dropdown search box */
    .bootstrap-select .bs-searchbox input {
        height: 38px;
        border-radius: 6px;
    }
    /* Remove blue background from active option in dropdown */
    .bootstrap-select .dropdown-menu li a.active,
    .bootstrap-select .dropdown-menu li a:focus,
    .bootstrap-select .dropdown-menu li a:hover {
        background-color: #f5f5f5 !important;
        /* light gray */
        color: #333 !important;
    }
    /* Ensure dropdown option text is always visible */
    .bootstrap-select .dropdown-menu li a {
        color: #333 !important;
    }
    .bootstrap-select .dropdown-menu li a span.text {
        color: #333 !important;
    }
    .bootstrap-select .dropdown-menu.inner {
        display: block !important;
    }
    /* Remove blue border when button focused */
    .bootstrap-select .btn:focus,
    .bootstrap-select .btn:active {
        box-shadow: none !important;
        outline: none !important;
        border-color: #ced4da !important;
    }
    /* IMPORTANT FIX FOR BOOTSTRAP SELECT DROPDOWN */
    /* MAIN FIX */
    .bootstrap-select:not(.bs-container) {
        width: 100% !important;
        position: relative !important;
    }
    .bootstrap-select .dropdown-toggle {
        height: 42px !important;
        border-radius: 6px !important;
        background: #fff !important;
    }
    /* Keep the menu inside the selectpicker wrapper so its width follows the toggle. */
    /* Drop-up behavior is disabled in the selectpicker initialization below. */
    /* REMOVE LARGE TOP SPACE */
    .bootstrap-select .bs-searchbox {
        padding: 6px !important;
        margin: 0 !important;
        background: #fff !important;
    }
    /* REMOVE EXTRA INNER SPACE */
    .bootstrap-select .dropdown-menu.inner,
    .bootstrap-select .inner {
        margin: 0 !important;
        padding: 0 !important;
    }
    /* IMPORTANT */
    .widget_top_filter,
    .left_size_custom_section,
    .form-group,
    .bootstrap-select {
        overflow: visible !important;
    }
    /* FIX FOR STICKY SIDEBAR */
    .widget_top_filter {
        position: sticky;
        top: 20px;
        z-index: 20 !important;
    }
    /* Mobile sidebar fix */
    #mobileSidebar {
        overflow-y: auto;
        overflow-x: visible !important;
    }
    /* Dropdown search box */
    .bootstrap-select .bs-searchbox input {
        height: 38px;
        border-radius: 6px;
    }
    /* Ensure dropdown menu starts at bottom of button */
    /* .widget_top_filter .bootstrap-select .dropdown-menu {
        top: 100% !important;
        left: 0 !important;
        transform: none !important;
    } */
</style>
{{-- ── Mobile trigger button ─────────────────────────────────────────── --}}
<div class="lg:hidden mb-2">
    <button id="burgerBtn" onclick="var m=document.getElementById('mobileSidebar'); var o=document.getElementById('overlay'); if(m){m.classList.remove('-translate-x-full'); m.classList.add('active');} if(o){o.classList.remove('hidden'); o.style.display='block';} document.body.style.overflow='hidden';"
        class="flex items-center gap-2 p-2 px-4 border rounded bg-white shadow-sm w-full justify-center">
        <i class="fas fa-filter"></i> Filters
    </button>
</div>
@php
    // Resolve current city slug for SEO URL redirect
    $current_city_slug = $city->city_slug ?? null;
    $current_city_id   = $city->id ?? ($city_header ?? 0);
    $current_cat_id    = $category_header ?? 0;

    $cacheKey = "sidebar_categories_v2_{$current_city_id}";

    $sidebar_categories = Cache::remember($cacheKey, 3600, function () use ($current_city_id) {
        if (empty($current_city_id) || $current_city_id == 0) {
            return DB::table('pagecategories')
                ->whereNull('category_parent_id')
                ->orWhere('category_parent_id', 0)
                ->orderBy('category_name', 'asc')
                ->select('id', 'category_name', 'category_slug', 'category_parent_id')
                ->get();
        }

        $activeCategoryIds = DB::table('content_master')
            ->where('source_type', 'category_count')
            ->where('status', 'listing')
            ->where('total_count', '>', 0)
            ->where('city_id', $current_city_id)
            ->select('category_id', 'parent_category_id')
            ->get();

        $ids = [];
        foreach ($activeCategoryIds as $row) {
            if ($row->category_id) $ids[] = $row->category_id;
            if ($row->parent_category_id) $ids[] = $row->parent_category_id;
        }
        $ids = array_unique($ids);

        if (empty($ids)) {
            return DB::table('pagecategories')
                ->whereNull('category_parent_id')
                ->orWhere('category_parent_id', 0)
                ->orderBy('category_name', 'asc')
                ->select('id', 'category_name', 'category_slug', 'category_parent_id')
                ->get();
        }

        return DB::table('pagecategories as pc')
            ->select('pc.id', 'pc.category_name', 'pc.category_slug', 'pc.category_parent_id')
            ->whereNull('pc.category_parent_id')
            ->whereIn('pc.id', $ids)
            ->orderBy('pc.category_name', 'asc')
            ->get();
    });
    // Extract page IDs from the current page set to determine related categories
    $page_ids = [];
    if (isset($mypages)) {
        if (method_exists($mypages, 'items')) {
            $page_ids = collect($mypages->items())->pluck('id')->filter()->toArray();
        } elseif (is_iterable($mypages)) {
            $page_ids = collect($mypages)->pluck('id')->filter()->toArray();
        }
    }
    // Load categories that are directly related to the current page set (listings)
    $category_capsules = collect();
    if (!empty($page_ids)) {
        $category_capsules = DB::table('pagecategories as pc')
            ->join('page_category as pcat', 'pcat.category_id', '=', 'pc.id')
            ->whereIn('pcat.page_id', $page_ids)
            ->select('pc.id', 'pc.category_name', 'pc.category_slug')
            ->distinct()
            ->orderBy('pc.category_name', 'asc')
            ->get();
    }
    // Fallback: If no categories are related to the current page set, show top-level categories
    if ($category_capsules->isEmpty()) {
        $category_capsules = $sidebar_categories;
    }
    // Filter areas to only show those that have active listings in the selected city
    $active_areas = collect();
    if (!empty($current_city_id) && $current_city_id != 0) {
        $active_areas = DB::table('areas')
            ->select('areas.id', 'areas.area_name', 'areas.area_slug')
            ->join('pages', 'pages.area_id', '=', 'areas.id')
            ->where('pages.item_status', 2)
            ->where('areas.city_id', $current_city_id)
            ->where('pages.city_id', $current_city_id)
            ->distinct()
            ->orderBy('areas.area_name', 'asc')
            ->get();
    }
    // Fallback: If no active areas are found, show all areas for the city
    if ($active_areas->isEmpty()) {
        $active_areas = !empty($current_city_id) && $current_city_id != 0
            ? DB::table('areas')->where('city_id', $current_city_id)->orderBy('area_name', 'asc')->get()
            : collect();
    }
    $formAction = url('/'); // We handle redirect in JS using SEO URL
@endphp
{{-- ── DESKTOP FILTER ────────────────────────────────────────────────── --}}
@php $formAction = route('search'); @endphp
<div class="widget_top_filter hidden lg:block">
    <div class="mb-3">
        <strong>Total Results Found: {{ $mypages->total() }}</strong>
    </div>
    {{-- Form: submits but JS intercepts and redirects to SEO URL --}}
    <form method="GET" action="{{ route('search') }}" id="filterFormDesktop">
        <div class="left_size_custom_section">
            <div class="form-group">
                <select id="city" name="city" class="selectpicker form-control" data-live-search="true"
                    data-width="100%" title="Select City">
                    <option value="">Select City</option>
                    @foreach($all_cities as $c)
                        <option value="{{ $c->id }}"
                            data-slug="{{ $c->city_slug }}"
                            {{ $current_city_id == $c->id ? 'selected' : '' }}>
                            {{ $c->city_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <select id="area" name="area" class="selectpicker form-control" data-live-search="true"
                    data-width="100%" title="Select Area">
                    <option value="">Select Area</option>
                    @foreach($active_areas as $areaItem)
                        <option value="{{ $areaItem->id }}"
                            {{ ($area_header ?? 0) == $areaItem->id ? 'selected' : '' }}>
                            {{ $areaItem->area_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <select id="category_filter" name="category" class="selectpicker form-control" data-live-search="true"
                    data-width="100%" title="Select Category">
                    <option value="">Select Category</option>
                  @foreach($sidebar_categories as $cat)
                        <option value="{{ $cat->id }}"
                            data-slug="{{ $cat->category_slug }}"
                            {{ $current_cat_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <select id="filter_sort_by" name="filter_sort_by" class="selectpicker form-control" data-width="100%"
                    title="Sort_By">
                    @foreach(['newest' => 'Newest', 'oldest' => 'Oldest', 'highest-rated' => 'Highest Rated', 'lowest-rated' => 'Lowest Rated'] as $val => $label)
                      <option value="{{ $val }}" {{ ($filter_sort_by ?? 'newest') === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
             <div class="d-flex align-items-center mt-4" style="gap: 10px;">
                <button type="submit" class="btn-submit-premium">Submit</button>
                <a class="btn-reset-premium" href="{{ url('/') }}">Reset</a>
            </div>
        </div>
    </form>
    {{-- Desktop Category List --}}
    <div class="category-box mt-4">
        <h4 class="category-title mb-3">Category</h4>
        <ul class="category-list">
             @foreach($category_capsules as $cat)
                <li class="category-item {{ $loop->index >= 10 ? 'hidden-category' : '' }} {{ $current_cat_id == $cat->id ? 'active' : '' }}">
                    @php
                        if ($current_city_slug) {
                            $catRoute = url($current_city_slug . '/' . $cat->category_slug);
                        } else {
                            $catRoute = route('search.category.only', ['category_slug' => $cat->category_slug]);
                        }
                    @endphp
                   <a href="{{ $catRoute }}" class="{{ $current_cat_id == $cat->id ? 'active' : '' }}">
                        {{ $cat->category_name }}
                    </a>
                </li>
            @endforeach
        </ul>
        @if($category_capsules->count() > 10)
            <a href="javascript:void(0);" class="show-more">View More</a>
        @endif
    </div>
</div>
{{-- ── MOBILE SIDEBAR ────────────────────────────────────────────────── --}}
<div id="mobileSidebar"
    class="fixed top-0 left-0 h-full w-72 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-50 overflow-y-auto lg:hidden"
    style="position: fixed; top: 0; left: 0; height: 100%; width: 280px; z-index: 9999;">
    <div class="d-flex justify-content-between align-items-center p-4 border-b">
        <h3 class="text-lg font-semibold">Filters</h3>
        <button id="closeFilterSidebar" onclick="var m=document.getElementById('mobileSidebar'); var o=document.getElementById('overlay'); if(m){m.classList.add('-translate-x-full'); m.classList.remove('active');} if(o){o.classList.add('hidden'); o.style.display='none';} document.body.style.overflow='';" class="text-gray-600 text-2xl">&times;</button>
    </div>
    <div class="p-4">
        <div class="mb-3">
            <strong>Total Results Found: {{ $mypages->total() }}</strong>
        </div>
        <form method="GET" action="{{ $formAction }}" id="filterForm">
            <div class="left_size_custom_section">
                {{-- Mobile City --}}
                <div class="form-group mb-3">
                    <select id="mcity" name="city" class="selectpicker form-control" data-live-search="true"
                        data-width="100%" title="Select City">
                        <option value="">Select City</option>
                        @foreach($all_cities as $c)
                            <option value="{{ $c->id }}"
                                data-slug="{{ $c->city_slug }}"
                                {{ ($city_header ?? 0) == $c->id ? 'selected' : '' }}>
                                {{ $c->city_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- Mobile Area --}}
                <div class="form-group mb-3">
                    <select id="marea" name="area" class="selectpicker form-control" data-width="100%"
                        title="Select Area">
                        <option value="">Select Area</option>
                        @foreach($active_areas as $areaItem)
                            <option value="{{ $areaItem->id }}" {{ ($area_header ?? 0) == $areaItem->id ? 'selected' : '' }}>
                                {{ $areaItem->area_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- Mobile Category --}}
                <div class="form-group mb-3">
                    <select id="mcategory_filter" name="category" class="selectpicker form-control"
                        data-live-search="true"  data-width="100%" title="Search Category...">
                        <option value="">Select Category</option>
                        @foreach(($all_categories ?? $sidebar_categories) as $cat)
                            <option value="{{ $cat->id }}"
                                data-slug="{{ $cat->category_slug }}"
                                {{ ($category_header ?? 0) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- Mobile Sort By --}}
                <div class="form-group mb-3">
                    <select id="mfilter_sort_by" name="filter_sort_by" class="selectpicker form-control"
                        data-live-search="true" data-width="100%" title="Sort By">
                        @foreach(['newest' => 'Newest', 'oldest' => 'Oldest', 'highest-rated' => 'Highest Rated', 'lowest-rated' => 'Lowest Rated'] as $val => $label)
                            <option value="{{ $val }}" {{ ($filter_sort_by ?? 'newest') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-submit-premium w-50">Submit</button>
                    <a class="btn-reset-premium w-50 d-flex align-items-center justify-center text-sm"
                        href="{{ $formAction }}">Reset</a>
                </div>
            </div>
        </form>
        {{-- Mobile Category List --}}
        <div class="category-box mt-5">
            <h4 class="category-title">Category</h4>
            <ul class="category-list">
                @foreach($category_capsules as $cat)
                    <li class="category-item {{ $loop->index >= 10 ? 'hidden-category' : '' }} {{ $current_cat_id == $cat->id ? 'active' : '' }}">
                        @php
                            if ($current_city_slug) {
                                $catRoute = url($current_city_slug . '/' . $cat->category_slug);
                            } else {
                                $catRoute = route('search.category.only', ['category_slug' => $cat->category_slug]);
                            }
                        @endphp
                        <a href="{{ $catRoute }}" class="{{ $current_cat_id == $cat->id ? 'active' : '' }}">{{ $cat->category_name }}</a>
                    </li>
                @endforeach
            </ul>
            @if($category_capsules->count() > 10)
                <a href="javascript:void(0);" class="show-more">View More</a>
            @endif
        </div>
    </div>
</div>
<div id="overlay" onclick="var m=document.getElementById('mobileSidebar'); if(m){m.classList.add('-translate-x-full'); m.classList.remove('active');} this.classList.add('hidden'); this.style.display='none'; document.body.style.overflow='';" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 lg:hidden"
    style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none;">
</div>
{{-- ── Scripts ───────────────────────────────────────────────────────── --}}
<script>
  var currentCitySlug = "{{ $current_city_slug ?? '' }}";
    $(document).ready(function () {
        // Sidebar Toggles using robust event delegation and direct binding
        const $msidebar = $('#mobileSidebar');
        const $overlay = $('#overlay');
        
        function openSidebar() {
            $msidebar.addClass('active').removeClass('-translate-x-full');
            $overlay.addClass('active').removeClass('hidden').show();
            $('body').css('overflow', 'hidden');
        }
        
        function closeSidebar() {
            $msidebar.removeClass('active').addClass('-translate-x-full');
            $overlay.removeClass('active').addClass('hidden').hide();
            $('body').css('overflow', '');
        }

        // Direct bindings
        $('#burgerBtn').off('click').on('click', openSidebar);
        $('#closeFilterSidebar, #closeSidebar, #overlay').off('click').on('click', closeSidebar);

        // Delegated bindings as backup
        $(document).off('click', '#burgerBtn').on('click', '#burgerBtn', openSidebar);
        $(document).off('click', '#closeFilterSidebar, #closeSidebar, #overlay').on('click', '#closeFilterSidebar, #closeSidebar, #overlay', closeSidebar);
        $(document).on('click', '.show-more', function () {
            $(this).prev('.category-list').find('.hidden-category').slideToggle();
            $(this).text($(this).text() === 'View More' ? 'View Less' : 'View More');
        });
        function loadAreas(cityId, areaSelector) {
            const $area = $(areaSelector);
            $area.empty().append('<option value="">Select Area</option>');
            if ($.fn.selectpicker) $area.selectpicker('refresh');
            if (!cityId) return;
            const urlParams = new URLSearchParams(window.location.search);
            const type = urlParams.get('type') || 'listing';
            $.ajax({
                 url: "{{ url('ajax/itemareas') }}/" + cityId,
                type: "GET",
                success: function (response) {
                    let areas = response;
                    if (typeof areas === 'string') {
                        try { areas = (typeof areas === 'string' ? JSON.parse(areas) : areas); } catch(e) { console.error(e); }
                    }
                    if (areas && areas.length > 0) {
                        $.each(areas, function (key, area) {
                           $area.append('<option value="' + area.id + '">' + area.area_name + '</option>');
                        });
                    }
                    if ($.fn.selectpicker) $area.selectpicker('refresh');
                },
                error: function () { console.error('Unable to load areas'); }
            });
        }
     
        function loadCategories(cityId, catSelector) {
            const $cat = $(catSelector);
            $cat.empty().append('<option value="">Select Category</option>');
            if ($.fn.selectpicker) $cat.selectpicker('refresh');
            if (!cityId) return;
         $.ajax({
                url: "{{ url('ajax/categories') }}/" + cityId,
                type: "GET",
                success: function (response) {
                    let cats = response;
                    if (typeof cats === 'string') {
                        try { cats = (typeof cats === 'string' ? JSON.parse(cats) : cats); } catch(e) {}
                    }
                    if (cats && cats.length > 0) {
                        $.each(cats, function (key, cat) {
                            $cat.append('<option value="' + cat.id + '" data-slug="' + (cat.category_slug || '') + '">' + cat.category_name + '</option>');
                        });
                    }
                    if ($.fn.selectpicker) $cat.selectpicker('refresh');
                },
                error: function () { console.error('Unable to load categories'); }
            });
        }
        // When city changes → reload areas AND categories
        $('#city').on('changed.bs.select', function () {
            var cityId = $(this).val();
            var citySlug = $(this).find(':selected').data('slug') || '';
            currentCitySlug = citySlug;
            loadAreas(cityId, '#area');
            loadCategories(cityId, '#category_filter');
        });
        $('#mcity').on('changed.bs.select', function () {
            var cityId = $(this).val();
            var citySlug = $(this).find(':selected').data('slug') || '';
            currentCitySlug = citySlug;
            loadAreas(cityId, '#marea');
            loadCategories(cityId, '#mcategory_filter');
        });
        // ── FORM SUBMIT → REDIRECT TO SEO URL ───────────────────────────
        function buildSeoUrl(citySlug, catSlug, sortBy) {
            if (citySlug && catSlug) {
                // /ahmedabad/cafes-with-indoor-seating
                var url = '/' + citySlug + '/' + catSlug;
                if (sortBy && sortBy !== 'newest') {
                    url += '?filter_sort_by=' + sortBy;
                }
                return url;
            } else if (citySlug && !catSlug) {
                return '/' + citySlug;
            } else if (!citySlug && catSlug) {
                return '/search/' + catSlug;
            }
            return '/search';
        }
        $('#filterFormDesktop').on('submit', function (e) {
            e.preventDefault();
            var citySlug  = $('#city').find(':selected').data('slug') || currentCitySlug || '';
            var catSlug   = $('#category_filter').find(':selected').data('slug') || '';
            var sortBy    = $('#filter_sort_by').val();
            window.location.href = buildSeoUrl(citySlug, catSlug, sortBy);
        });
        $('#filterForm').on('submit', function (e) {
            e.preventDefault();
            var citySlug  = $('#mcity').find(':selected').data('slug') || currentCitySlug || '';
            var catSlug   = $('#mcategory_filter').find(':selected').data('slug') || '';
            var sortBy    = $('#mfilter_sort_by').val();
            window.location.href = buildSeoUrl(citySlug, catSlug, sortBy);
        });
        // Sort By auto-submit with SEO redirect
        $('#filter_sort_by').on('changed.bs.select', function () {
            $('#filterFormDesktop').trigger('submit');
        });
        $('#mfilter_sort_by').on('changed.bs.select', function () {
            $('#filterForm').trigger('submit');
        });
    });
    $('.selectpicker').selectpicker({
        liveSearch: true,
        dropupAuto: false,
        container: 'body'
    });

    // Start with the same menu parent that bootstrap-select retains after its first open.
    $('.selectpicker').each(function () {
        var picker = $(this).data('selectpicker');
        if (picker && picker.$bsContainer && picker.$menu) {
            picker.$bsContainer.append(picker.$menu);
        }
    });
    // Keep body-level menus for clipping safety, but match each menu to its toggle.
    $(document).on('shown.bs.select', '.selectpicker', function () {
        var picker = $(this).data('selectpicker');
        var $menu = picker && picker.$menu ? picker.$menu : $();
        var $container = $menu.closest('.bs-container');
        if (!$container.length) {
            $menu = $('.bs-container .dropdown-menu.show').last();
            $container = $menu.closest('.bs-container');
        }
        var toggleWidth = $(this).closest('.bootstrap-select').find('> .dropdown-toggle').outerWidth();

        if (!$menu.length || !$container.length || !toggleWidth) return;

        $container.css({
            width: toggleWidth + 'px',
            minWidth: toggleWidth + 'px',
            maxWidth: toggleWidth + 'px'
        });
        $menu.css({
            width: '100%',
            minWidth: '0',
            maxWidth: '100%'
        });
    });
    // Toggle sidebar position and elevate active form-group so dropdown appears above siblings
    $(document).on('show.bs.select', '.selectpicker', function () {
        $(this).closest('.form-group').addClass('active-form-group');
        $(this).closest('.widget_top_filter').css({
            position: 'relative',
            zIndex: 50
        });
    });

    $(document).on('hide.bs.select', '.selectpicker', function () {
        $(this).closest('.form-group').removeClass('active-form-group');
        $(this).closest('.widget_top_filter').css({
            position: '',
            zIndex: ''
        });
    });
</script>


