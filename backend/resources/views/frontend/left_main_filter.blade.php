<!-- Bootstrap CSS -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->

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

{{-- ── ONE minified CSS block ──────────────────────────────────────── --}}
<style>
    /* Sticky Sidebar Fix */
    .widget_top_filter {
        background: #fff;
        padding: 15px;
        border: 1px solid #eee;
        border-radius: 8px;
        position: -webkit-sticky;
        position: sticky;
        top: 20px;
        z-index: 10;
        /* DO NOT add overflow-y here — it clips selectpicker/select2 dropdowns */
    }

    /* Fix Bootstrap selectpicker dropdown z-index */
    .bootstrap-select .dropdown-menu {
        z-index: 99999 !important;
    }

    /* Style improvements for selectpicker */
    .left_size_custom_section .form-control {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .bootstrap-select .btn {
        background-color: #fff !important;
        border: 1px solid #ced4da !important;
        /* padding: 8px 12px !important; */
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
    }

    #overlay {
        z-index: 9998;
    }

    .category-title {
        margin-top: 10px;
        font-size: 18px;
        font-weight: bold;
    }

    .category-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .category-item a {
        display: inline-flex;
        align-items: center;
        justify-content: left;
        min-height: 28px;
        position: relative;
        padding: 6px 16px;
        background: #eeeeee;
        color: #1e2125ff !important;
        text-decoration: none !important;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-align: left;
        line-height: 1.3;
        border: 1px solid #e5e7eb;
        border-radius: 50px;
        margin-bottom: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
        transition: all 0.2s ease;
    }

    .category-item a:hover {
        background: #ff4b4b;

        border-color: #ff4b4b;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .category-item a:active {
        transform: translateY(-5px);
        /* Slide UP on click */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .category-item.active a,
    .category-item a.active {
        background: #ff4b4b;

        border-color: #ff4b4b;
    }

    .hidden-category {
        display: none;
    }

    .show-more {
        display: inline-block;
        margin-top: 6px;
        font-size: 14px;
        color: #FF4939 !important;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
    }

    .form-group {
        width: 100%;
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
    .widget_top_filter .form-group.active-form-group,
.left_size_custom_section .form-group.active-form-group {
    z-index: 999999 !important;
}

.widget_top_filter .form-group {
        width: 100%;
        margin-bottom: 8px;
        min-height: 52px;
        /* adjust as you like */
        display: flex;
        align-items: center;
        /* vertically center selectpicker button */
        position: relative !important; /* Ensure z-index works */
}

/* Elevate active filter container */
.widget_top_filter .form-group:has(.bootstrap-select.show),
.left_size_custom_section .form-group:has(.bootstrap-select.show),
.widget_top_filter .form-group:focus-within,
.left_size_custom_section .form-group:focus-within {
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

    /* Make dropdown float on top, not push or overlap oddly */
    .widget_top_filter .bootstrap-select .dropdown-menu {
        width: 100% !important;
        z-index: 99999 !important;
        z-index: 999999 !important;
    position: absolute !important;
}

    /* Prevent clipping from parent */
    .widget_top_filter {
        overflow: visible;
        /* important for dropdowns */
    }

    /* Remove blue background from active option in dropdown */
    .bootstrap-select .dropdown-menu li a.active,
    .bootstrap-select .dropdown-menu li a:focus,
    .bootstrap-select .dropdown-menu li a:hover {
        background-color: #f5f5f5 !important;
        /* light gray */
        color: #333 !important;
    }

    /* Remove blue border when button focused */
    .bootstrap-select .btn:focus,
    .bootstrap-select .btn:active {
        box-shadow: none !important;
        outline: none !important;
        border-color: #ced4da !important;
    }

    /* Ensure dropdown menu starts at bottom of button */
    .widget_top_filter .bootstrap-select .dropdown-menu {
        top: 100% !important;
        left: 0 !important;
        transform: none !important;
    }
</style>
{{-- ── Pre-compute values ONCE ────────────────────────────────────── --}}
@php
    $formAction = route('pages');
    $sortOptions = [
        'newest' => 'Newest',
        'oldest' => 'Oldest',
        'highest-rated' => 'Highest-rated',
        'lowest-rated' => 'Lowest-rated'
    ];
@endphp

{{-- ── Mobile trigger button ────────────────────────────────────────── --}}
<div class="lg:hidden mb-2">
    <button id="burgerBtn" onclick="var m=document.getElementById('mobileSidebar'); var o=document.getElementById('overlay'); if(m){m.classList.remove('-translate-x-full'); m.classList.add('active');} if(o){o.classList.remove('hidden'); o.style.display='block';} document.body.style.overflow='hidden';"
        class="flex items-center gap-2 p-2 px-4 border rounded bg-white shadow-sm w-full justify-center">
        <i class="fas fa-filter"></i> Filters
    </button>
</div>

{{-- ── DESKTOP FILTER ────────────────────────────────────────────────── --}}
<div class="widget_top_filter hidden lg:block">
    <div class="mb-2">
        <strong>Total Results Found: {{ $total_blogs }}</strong>
    </div>

    <form method="GET" action="{{ $formAction }}" id="filterFormDesktop">
        <div class="left_size_custom_section">

            {{-- City Select --}}
            <div class="form-group mb-1">
                <select id="city" name="city" class="selectpicker form-control" data-live-search="true"
                     data-width="100%" title="Select City">
                    <option value="">Select City</option>
                    @foreach ($all_cities as $c)
                        <option value="{{ $c->id }}" {{ $filter_city == $c->id ? 'selected' : '' }}>{{ $c->city_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Area Select --}}
            <div class="form-group mb-1">
                <select id="area" name="area" class="selectpicker form-control" data-live-search="true"
                     data-width="100%" title="Select Area">
                    <option value="">Select Area</option>
                    @foreach($filter_areas as $a)
                        <option value="{{ $a->id }}" {{ $filter_area == $a->id ? 'selected' : '' }}>{{ $a->area_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Category Select --}}
            <div class="form-group mb-1">
                <select id="category_filter" name="category" class="selectpicker form-control" data-live-search="true"
                     data-width="100%" title="Select Category">
                    <option value="">Select Category</option>
                    @foreach ($categories as $cat)
                        <option value="{{ route('blog.city', ['city_slug' => $city->city_slug]) }}">
                            {{ $cat->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Sort By --}}
            <div class="form-group mb-1">
                <select id="filter_sort_by" name="filter_sort_by" class="selectpicker form-control"
                    data-live-search="true" data-width="100%"  data-dropup-auto="false">
                    @foreach($sortOptions as $val => $label)
                        <option value="{{ $val }}" {{ request('filter_sort_by') == $val ? 'selected' : '' }}>{{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary w-1/2">Submit</button>
                <a class="btn btn-outline-primary rounded w-1/2 flex items-center justify-center text-sm"
                    href="{{ route('category.blog', ['category_slug' => $category->category_slug ?? 'all']) }}">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div class="category-box mt-4">
        <h4 class="category-title">Category</h4>
        <ul class="category-list">
            @foreach ($categories as $key => $cat)
                <li class="category-item {{ $key >= 10 ? 'hidden-category' : '' }}">
                    <a href="{{ route('blog.city', ['city_slug' => $city->city_slug]) }}">
                        {{ $cat->category_name }}
                    </a>
                </li>
            @endforeach
        </ul>
        @if(count($categories) > 10)
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
            <strong>Total Results Found: {{ $total_blogs }}</strong>
        </div>

        <form method="GET" action="{{ $formAction }}" id="filterFormMobile">
            <div class="left_size_custom_section">
                {{-- Mobile City --}}
                <div class="form-group mb-3">
                    <select id="mcity" name="city" class="selectpicker form-control" data-live-search="true"
                         data-width="100%" title="Select City">
                        <option value="">Select City</option>
                        @foreach ($all_cities as $c)
                            <option value="{{ $c->id }}" {{ $filter_city == $c->id ? 'selected' : '' }}>{{ $c->city_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Mobile Area --}}
                <div class="form-group mb-3">
                    <select id="marea" name="area" class="selectpicker form-control" data-width="100%"
                         data-live-search="true" title="Select Area">
                        <option value="">Select Area</option>
                        @foreach($filter_areas as $a)
                            <option value="{{ $a->id }}" {{ $filter_area == $a->id ? 'selected' : '' }}>{{ $a->area_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Mobile Category --}}
                <div class="form-group mb-3">
                    <select id="mcategory_filter" name="category" class="selectpicker form-control"
                        data-live-search="true"  data-width="100%" title="Select Category">
                        <option value="">Select Category</option>
                        @foreach ($categories as $cat)
                            <option value="{{ route('blog.city', ['city_slug' => $city->city_slug]) }}">
                                {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Mobile Sort By --}}
                <div class="form-group mb-3">
                    <select id="mfilter_sort_by" name="filter_sort_by" class="selectpicker form-control"
                        data-live-search="true"  data-width="100%">
                        @foreach($sortOptions as $val => $label)
                            <option value="{{ $val }}" {{ request('filter_sort_by') == $val ? 'selected' : '' }}>{{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary w-1/2">Submit</button>
                    <a class="btn btn-outline-primary rounded w-1/2 flex items-center justify-center text-sm"
                        href="{{ route('category.blog', ['category_slug' => $category->category_slug ?? 'all']) }}">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="category-box mt-5">
            <h4 class="category-title">Categories</h4>
            <ul class="category-list">
                @foreach ($categories as $key => $cat)
                    <li class="category-item {{ $key >= 10 ? 'hidden-category' : '' }}">
                        <a href="{{ route('blog.city', ['city_slug' => $city->city_slug]) }}">
                            {{ $cat->category_name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div id="overlay" onclick="var m=document.getElementById('mobileSidebar'); if(m){m.classList.add('-translate-x-full'); m.classList.remove('active');} this.classList.add('hidden'); this.style.display='none'; document.body.style.overflow='';" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 lg:hidden"></div>

{{-- ── Scripts ───────────────────────────────────────────────────────── --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/js/bootstrap-select.min.js"></script>

<script>
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

        $('.selectpicker').selectpicker();

        // Fix bootstrap-select + sticky sidebar
        $(document).on('show.bs.select', '.selectpicker', function () {
        $(this).closest('.form-group').addClass('active-form-group'); $('.widget_top_filter').css({ position: 'relative' }); });
        $(document).on('shown.bs.select', '.selectpicker', function () { $(this).parent().find('.dropdown-menu').css({ top: '100%', bottom: 'auto', transform: 'none' }); });
        $(document).on('hide.bs.select', '.selectpicker', function () {
    $(this).closest('.form-group').removeClass('active-form-group');
        $(this).closest('.form-group').removeClass('active-form-group'); $('.widget_top_filter').css({ position: 'sticky', top: '20px' }); });

        function loadAreas(cityId, areaSelector) {
            const $area = $(areaSelector);
            $area.empty().append('<option value="">Select Area</option>');
            if ($.fn.selectpicker) $area.selectpicker('refresh');
            if (!cityId) return;

            $.ajax({
                url: "{{ url('ajax/areas') }}/" + cityId,
                type: "GET",
                success: function (response) {
                    if (response && response.length > 0) {
                        $.each(response, function (key, area) {
                            $area.append(`<option value="${area.id}">${area.area_name}</option>`);
                        });
                    }
                    if ($.fn.selectpicker) $area.selectpicker('refresh');
                },
                error: function () { console.error('Unable to load areas'); }
            });
        }

        $('#city').on('changed.bs.select', function () { loadAreas($(this).val(), '#area'); });
        $('#mcity').on('changed.bs.select', function () { loadAreas($(this).val(), '#marea'); });

        $('#filter_sort_by, #mfilter_sort_by').on('changed.bs.select', function () {
            $(this).closest('form').submit();
        });
    });
</script>