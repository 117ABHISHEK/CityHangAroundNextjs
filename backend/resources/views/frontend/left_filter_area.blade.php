@php
    $areaCats = $area_categories ?? $all_categories ?? $listing_categories ?? collect();
    $cityCats = $city_categories ?? $all_categories ?? $listing_categories ?? collect();
    $filter_category = $filter_category ?? 0;
    $filter_area = $filter_area ?? 0;
    $filter_city = $filter_city ?? 0;
    $filter_sort_by = $filter_sort_by ?? 'newest';
    $total_pages = $total_pages ?? 0;
@endphp
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Select CSS -->
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

<!-- Popper -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

<!-- Bootstrap Select JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script> -->
<!-- Tailwind CSS (for layout) -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<style>
    /* WHEN OPEN -> BRING TO FRONT */
    .bootstrap-select.show,
    .bootstrap-select.open {
        z-index: 999999 !important;
    }

    /* Fix Bootstrap selectpicker dropdown z-index */
    .bootstrap-select .dropdown-menu {
        z-index: 999999 !important;
    }

    .widget_top_filter {
        background: #fff;
        padding: 15px;
        border: 1px solid #eee;
        border-radius: 8px;
        position: sticky;
        top: 20px;
        z-index: 20 !important;
        overflow: visible !important;
    }

    .left_size_custom_section,
    .form-group,
    .bootstrap-select {
        overflow: visible !important;
    }

    .left_size_custom_section .form-control {
        padding-left: 0 !important;
        padding-right: 0 !important
    }

    .bootstrap-select .btn {
        background-color: #fff !important;
        border: 1px solid #ced4da !important
    }

    .bootstrap-select .btn:focus,
    .bootstrap-select .btn:active {
        box-shadow: none !important;
        outline: none !important;
        border-color: #ced4da !important
    }

    .left_size_custom_section .bootstrap-select,
    .left_size_custom_section .bootstrap-select>.dropdown-toggle,
    .left_size_custom_section .bootstrap-select .btn {
        width: 100% !important;
        max-width: 100% !important
    }

    .form-group {
        width: 100%
    }

    .form-group .bootstrap-select {
        width: 100% !important;
        display: block !important
    }

    .form-group .bootstrap-select>.dropdown-toggle,
    .form-group .bootstrap-select .dropdown-menu {
        width: 100% !important
    }

    .widget_top_filter .form-group.active-form-group,
.left_size_custom_section .form-group.active-form-group {
    z-index: 999999 !important;
}

.widget_top_filter .form-group {
        width: 100%;
        margin-bottom: 8px;
        min-height: 52px;
        display: flex;
        align-items: center
        position: relative !important; /* Ensure z-index works */
}

/* Elevate active filter container */
.widget_top_filter .form-group:has(.bootstrap-select.show),
.left_size_custom_section .form-group:has(.bootstrap-select.show),
.widget_top_filter .form-group:focus-within,
.left_size_custom_section .form-group:focus-within {
    z-index: 999999 !important;
}

    .widget_top_filter .bootstrap-select,
    .widget_top_filter .bootstrap-select>.dropdown-toggle {
        width: 100% !important;
        height: 100%
    }

    .widget_top_filter .flex.gap-2 .btn {
        width: 100%
    }

    .widget_top_filter .bootstrap-select .dropdown-menu {
        width: 100% !important;
        z-index: 99999 !important;
        top: 100% !important;
        left: 0 !important;
        transform: none !important
        z-index: 999999 !important;
    position: absolute !important;
}

    .widget_top_filter .bootstrap-select>.dropdown-toggle {
        height: 42px !important;
        padding: 8px 12px !important;
        font-size: 14px !important;
        border-radius: 6px !important;
        border: 1px solid #ced4da !important;
        background-color: #fff !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: space-between !important
    }

    .widget_top_filter .bootstrap-select .dropdown-toggle::after {
        margin-top: 0 !important
    }

    .widget_top_filter .bootstrap-select .filter-option {
        color: #6b7280
    }

    .widget_top_filter .bootstrap-select.show .filter-option,
    .widget_top_filter .bootstrap-select .dropdown-toggle:focus .filter-option {
        color: #111827
    }

    .bootstrap-select .dropdown-menu {
        z-index: 99999 !important
    }

    .bootstrap-select .dropdown-menu li a.active,
    .bootstrap-select .dropdown-menu li a:focus,
    .bootstrap-select .dropdown-menu li a:hover {
        background-color: #f5f5f5 !important;
        color: #333 !important
    }

    #mobileSidebar {
        z-index: 9999;
        padding-bottom: 4rem
    }

    #overlay {
        z-index: 9998
    }

    .category-title {
        margin-top: 5px;
        font-size: 18px;
        font-weight: bold
    }

    .category-box {
        width: 100%;
        overflow: visible
    }

    .category-list {
        list-style: none;
        margin: 0;
        padding: 5px 0;
        display: flex;
        flex-wrap: wrap;
        gap: 10px
    }

    .category-item {
        margin-bottom: 5px
    }

    .category-item a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 28px;
        position: relative;
        padding: 6px 16px;
        background: #eeeeee;
        color: #1e2125ff !important;
        text-decoration: none !important;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-align: center;
        line-height: 1.3;
        border: 1px solid #e5e7eb;
        border-radius: 50px;
        margin-bottom: 2px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08)
    }

    .category-item a:hover {
        background: #ff4b4b;
        color: #fff !important;
        border-color: #ff4b4b;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px)
    }

    .category-item a:active {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15)
    }

    .category-item.active a,
    .category-item a.active {
        background: #ff4b4b;
        color: #fff !important;
        border-color: #ff4b4b
    }

    .hidden-category {
        display: none
    }

    .show-more {
        display: inline-block;
        margin-top: 6px;
        font-size: 14px;
        color: #FF4939 !important;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer
    }
</style>

{{-- ── Mobile trigger button ─────────────────────────────────────────── --}}
<div class="lg:hidden mb-2">
    <button id="burgerBtn" onclick="var m=document.getElementById('mobileSidebar'); var o=document.getElementById('overlay'); if(m){m.classList.remove('-translate-x-full'); m.classList.add('active');} if(o){o.classList.remove('hidden'); o.style.display='block';} document.body.style.overflow='hidden';"
        class="flex items-center gap-2 p-2 px-4 border rounded bg-white shadow-sm w-full justify-center">
        <i class="fas fa-filter"></i> Filters
    </button>
</div>

{{-- ── DESKTOP FILTER ────────────────────────────────────────────────── --}}
@php $formAction = route('page.city.area', ['city_slug' => $city->city_slug, 'area_slug' => $area->area_slug]); @endphp

<div class="widget_top_filter hidden lg:block">
    <div class="mb-2">
        <strong>Total Results Found: {{ $total_pages }}</strong>
    </div>

    <form method="GET" action="{{ $formAction }}" id="filterFormDesktop">
        <div class="left_size_custom_section">

            {{-- City --}}
            <div class="form-group mb-1">
                <select id="city" name="city" class="selectpicker form-control" data-live-search="true"
                     data-width="100%" title="Select City">
                    <option value="">Select City</option>
                    @foreach($all_cities as $c)
                        <option value="{{ $c->id }}" {{ $filter_city == $c->id ? 'selected' : '' }}>{{ $c->city_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Area (pre-fetched from controller — no nested loops needed) --}}
            <div class="form-group mb-1">
                <select id="area" name="area" class="selectpicker form-control" data-live-search="true"
                     data-width="100%" title="Select Area">
                    <option value="">Select Area</option>
                    @foreach($filter_areas as $areaItem)
                        <option value="{{ $areaItem->id }}" {{ $filter_area == $areaItem->id ? 'selected' : '' }}>
                            {{ $areaItem->area_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Category (already parent-only from controller — no @if check needed) --}}
            <div class="form-group mb-1">
                <select id="category_filter" name="category" class="selectpicker form-control" data-live-search="true"
                     data-width="100%" title="Select Category">
                    <option value="">Select Category</option>
                    @foreach($cityCats as $cat)
                        <option value="{{ $cat->id }}" {{ $filter_category == $cat->id ? 'selected' : '' }}>
                            {{ $cat->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Sort By --}}
            <div class="form-group mb-1">
                <select id="filter_sort_by" name="filter_sort_by" class="selectpicker form-control"
                    data-live-search="true" data-width="100%"  data-dropup-auto="false"
                    title="Sort_By">
                    @foreach(['newest' => 'Newest', 'oldest' => 'Oldest', 'highest-rated' => 'Highest Rated', 'lowest-rated' => 'Lowest Rated'] as $val => $label)
                        <option value="{{ $val }}" {{ $filter_sort_by === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary w-1/2">Submit</button>
                <a class="btn btn-outline-primary rounded w-1/2 flex items-center justify-center text-sm"
                    href="{{ $formAction }}">Reset</a>
            </div>
        </div>
    </form>

    {{-- Desktop Category List --}}
    <div class="category-box mt-4">
        <h4 class="category-title">Category</h4>
        <ul class="category-list">
            @foreach($areaCats as $cat)
                <li class="category-item {{ $loop->index >= 10 ? 'hidden-category' : '' }} {{ (isset($category) && $category->id == $cat->id) || ($filter_category == $cat->id) ? 'active' : '' }}">
                    <a
                        href="{{ route('page.category.city.area', ['category_slug' => $cat->category_slug, 'city_slug' => $city->city_slug, 'area_slug' => $area->area_slug]) }}"
                        class="{{ (isset($category) && $category->id == $cat->id) || ($filter_category == $cat->id) ? 'active' : '' }}">#{{ ltrim($cat->category_name, '#') }}</a>
                </li>
            @endforeach
        </ul>
        @if($areaCats->count() > 10)
            <a href="javascript:void(0);" class="show-more">View More</a>
        @endif
    </div>
</div>

{{-- ── MOBILE SIDEBAR ────────────────────────────────────────────────── --}}
<div id="mobileSidebar"
    class="fixed top-0 left-0 h-full w-72 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-50 overflow-y-auto lg:hidden">
    <div class="flex justify-between items-center p-4 border-b">
        <h3 class="text-lg font-semibold">Filters</h3>
        <button id="closeFilterSidebar" onclick="var m=document.getElementById('mobileSidebar'); var o=document.getElementById('overlay'); if(m){m.classList.add('-translate-x-full'); m.classList.remove('active');} if(o){o.classList.add('hidden'); o.style.display='none';} document.body.style.overflow='';" class="text-gray-600 text-2xl">&times;</button>
    </div>

    <div class="p-4">
        <div class="mb-3">
            <strong>Total Results Found: {{ $total_pages }}</strong>
        </div>

        <form method="GET" action="{{ $formAction }}" id="filterForm">
            <div class="left_size_custom_section">

                {{-- Mobile City --}}
                <div class="form-group mb-3">
                    <select id="mcity" name="city" class="selectpicker form-control" data-live-search="true"
                        data-width="100%" title="Select City">
                        <option value="">Select City</option>
                        @foreach($all_cities as $c)
                            <option value="{{ $c->id }}" {{ $filter_city == $c->id ? 'selected' : '' }}>{{ $c->city_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Mobile Area (loaded via AJAX on city change) --}}
                <div class="form-group mb-3">
                    <select id="marea" name="area" class="selectpicker form-control" data-width="100%"
                        title="Select Area">
                        <option value="">Select Area</option>
                        @foreach($filter_areas as $areaItem)
                            <option value="{{ $areaItem->id }}" {{ $filter_area == $areaItem->id ? 'selected' : '' }}>
                                {{ $areaItem->area_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Mobile Category --}}
                <div class="form-group mb-3">
                    <select id="mcategory_filter" name="category" class="selectpicker form-control"
                        data-live-search="true" data-width="100%" title="Search Category...">
                        <option value="">Select Category</option>
                        @foreach($cityCats as $cat)
                            <option value="{{ $cat->id }}" {{ $filter_category == $cat->id ? 'selected' : '' }}>
                                {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Mobile Sort By --}}
                <div class="form-group mb-3">
                    <select id="mfilter_sort_by" name="filter_sort_by" class="selectpicker form-control"
                        data-live-search="true"  data-width="100%" title="Sort By">
                        @foreach(['newest' => 'Newest', 'oldest' => 'Oldest', 'highest-rated' => 'Highest Rated', 'lowest-rated' => 'Lowest Rated'] as $val => $label)
                            <option value="{{ $val }}" {{ $filter_sort_by === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary w-1/2">Submit</button>
                    <a class="btn btn-outline-primary rounded w-1/2 flex items-center justify-center text-sm"
                        href="{{ $formAction }}">Reset</a>
                </div>
            </div>
        </form>

        {{-- Mobile Category List --}}
        <div class="category-box mt-5">
            <h4 class="category-title">Category</h4>
            <ul class="category-list">
                @foreach($areaCats as $cat)
                    <li class="category-item {{ $loop->index >= 10 ? 'hidden-category' : '' }} {{ (isset($category) && $category->id == $cat->id) || ($filter_category == $cat->id) ? 'active' : '' }}">
                        <a
                            href="{{ route('page.category.city.area', ['category_slug' => $cat->category_slug, 'city_slug' => $city->city_slug, 'area_slug' => $area->area_slug]) }}"
                            class="{{ (isset($category) && $category->id == $cat->id) || ($filter_category == $cat->id) ? 'active' : '' }}">#{{ ltrim($cat->category_name, '#') }}</a>
                    </li>
                @endforeach
            </ul>
            @if($areaCats->count() > 10)
                <a href="javascript:void(0);" class="show-more">View More</a>
            @endif
        </div>
    </div>
</div>

<div id="overlay" onclick="var m=document.getElementById('mobileSidebar'); if(m){m.classList.add('-translate-x-full'); m.classList.remove('active');} this.classList.add('hidden'); this.style.display='none'; document.body.style.overflow='';" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 lg:hidden"></div>

{{-- ── Scripts ───────────────────────────────────────────────────────── --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

<script>
    $(document).ready(function () {

        // ── Sidebar toggle (mobile) ──────────────────────────────────────
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
        if (overlay) overlay.addEventListener('click', closeSidebar);

        // ── Category Show More ───────────────────────────────────────────
        $(document).on('click', '.show-more', function () {
            $(this).prev('.category-list').find('.hidden-category').slideToggle();
            $(this).text($(this).text() === 'View More' ? 'View Less' : 'View More');
        });

        // ── Reset Sort By placeholder when no sort is active ────────────
        @if(!$filter_sort_by)
            $('#filter_sort_by, #mfilter_sort_by').val('').selectpicker('refresh');
        @endif

        // ── Reusable area loader (AJAX) — used on city change ───────────
        function loadAreas(cityId, selector) {
            const $sel = $(selector);
            $sel.empty().append('<option value="">Select Area</option>');
            if ($.fn.selectpicker) $sel.selectpicker('refresh');
            if (!cityId) return;

            $.get("{{ url('ajax/areas') }}/" + cityId, function (data) {
                if (data && data.length) {
                    $.each(data, function (i, a) {
                        $sel.append('<option value="' + a.id + '">' + a.area_name + '</option>');
                    });
                }
                if ($.fn.selectpicker) $sel.selectpicker('refresh');
            }).fail(function () { console.error('Unable to load areas'); });
        }

        $('#city').on('changed.bs.select', function () { loadAreas($(this).val(), '#area'); });
        $('#mcity').on('changed.bs.select', function () { loadAreas($(this).val(), '#marea'); });

        // ── Auto-submit on Sort change ───────────────────────────────────
        $('#filter_sort_by, #mfilter_sort_by').on('changed.bs.select', function () {
            $(this).closest('form').submit();
        });
    });

    // ── Init Bootstrap Select ────────────────────────────────────────
    $('.selectpicker').selectpicker({
        liveSearch: true,
        dropupAuto: false
    });

    // GUARANTEE Z-INDEX: When a dropdown opens, bring its parent to the absolute front
    $('.selectpicker').on('show.bs.select', function () {
        $(this).closest('.form-group').css({ 'z-index': 999999, 'position': 'relative' });
        $(this).closest('.bootstrap-select').css('z-index', 999999);
    });

    $('.selectpicker').on('hide.bs.select', function () {
        $(this).closest('.form-group').css({ 'z-index': 'auto', 'position': 'static' });
        $(this).closest('.bootstrap-select').css('z-index', 'auto');
    });
</script>