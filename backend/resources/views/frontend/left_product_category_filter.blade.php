
<!-- Bootstrap Select CSS -->
<link rel="stylesheet" href="{{ asset('assets/frontend/css/bootstrap-select.min.css') }}">

<style>
/* ===== SIDEBAR CARD ===== */
.widget_top_filter {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 20px;
    position: -webkit-sticky;
    position: sticky;
    top: 20px;
    z-index: 10;
    overflow: visible;
}

/* ===== TOTAL RESULTS ===== */
.total-results {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f3f4f6;
}

/* ===== FORM GROUPS ===== */
.widget_top_filter .form-group {
    width: 100%;
    margin-bottom: 10px;
}

.widget_top_filter .form-group:last-of-type {
    margin-bottom: 14px;
}

/* ===== SELECTPICKER BUTTONS - Clean white style ===== */
.widget_top_filter .bootstrap-select > .dropdown-toggle {
    height: 42px !important;
    padding: 8px 12px !important;
    font-size: 13px !important;
    border-radius: 6px !important;
    border: 1px solid #d1d5db !important;
    background-color: #fff !important;
    color: #374151 !important;
    box-shadow: none !important;
    transition: border-color 0.15s ease !important;
}

.widget_top_filter .bootstrap-select > .dropdown-toggle:hover {
    border-color: #9ca3af !important;
}

.widget_top_filter .bootstrap-select > .dropdown-toggle:focus,
.widget_top_filter .bootstrap-select > .dropdown-toggle:active {
    box-shadow: none !important;
    outline: none !important;
    border-color: #d1d5db !important;
}

/* Placeholder / filter-option color */
.widget_top_filter .bootstrap-select .filter-option {
    color: #6b7280 !important;
}

.widget_top_filter .bootstrap-select.show .filter-option {
    color: #111827 !important;
}

/* Always show Sort By selected values as dark */
.widget_top_filter select[name="filter_sort_by"] ~ .dropdown-toggle .filter-option {
    color: #111827 !important;
}

/* Dropdown arrow */
.widget_top_filter .bootstrap-select .dropdown-toggle::after {
    margin-top: 0 !important;
}

/* Dropdown menu */
.widget_top_filter .bootstrap-select .dropdown-menu {
    width: 100% !important;
    z-index: 99999 !important;
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    transform: none !important;
    border-radius: 6px !important;
    border: 1px solid #e5e7eb !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
    margin-top: 4px !important;
}

/* Remove blue from dropdown items */
.bootstrap-select .dropdown-menu li a.active,
.bootstrap-select .dropdown-menu li a:focus,
.bootstrap-select .dropdown-menu li a:hover {
    background-color: #f3f4f6 !important;
    color: #111827 !important;
}

/* Elevate active filter */
.widget_top_filter .form-group.active-form-group,
.widget_top_filter .form-group:focus-within {
    z-index: 999999 !important;
}

/* Full-width selects */
.left_size_custom_section .bootstrap-select,
.left_size_custom_section .bootstrap-select > .dropdown-toggle,
.left_size_custom_section .bootstrap-select .btn,
.form-group .bootstrap-select,
.form-group .bootstrap-select > .dropdown-toggle,
.form-group .bootstrap-select .dropdown-menu {
    width: 100% !important;
    max-width: 100% !important;
}

/* ===== BUTTONS ===== */
.filter-buttons {
    display: flex;
    gap: 8px;
    margin-top: 14px;
}

.filter-buttons .btn-submit {
    flex: 1;
    padding: 8px 12px;
    font-size: 13px;
    font-weight: 600;
    background-color: #111827;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    line-height: 1.5;
}

.filter-buttons .btn-submit:hover {
    background-color: #1f2937;
    color: #fff;
}

.filter-buttons .btn-reset {
    flex: 1;
    padding: 8px 12px;
    font-size: 13px;
    font-weight: 600;
    background-color: #fff;
    color: #374151;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    line-height: 1.5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.filter-buttons .btn-reset:hover {
    background-color: #f9fafb;
    border-color: #9ca3af;
    color: #111827;
}

/* ===== CATEGORY SECTION ===== */
.category-box {
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid #f3f4f6;
}

.category-title {
    font-size: 14px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 12px 0;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.category-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.category-item {
    margin-bottom: 0;
}

.category-item a {
    display: block;
    padding: 7px 4px;
    color: #4b5563 !important;
    text-decoration: none !important;
    font-size: 13px;
    font-weight: 400;
    line-height: 1.4;
    border-bottom: 1px solid #f3f4f6;
    transition: color 0.15s ease;
}

.category-item:last-child a {
    border-bottom: none;
}

.category-item a:hover {
    color: #111827 !important;
    background: none;
    box-shadow: none;
    transform: none;
}

.category-item.active a,
.category-item a.active {
    color: #111827 !important;
    font-weight: 600;
    background: none;
    border-color: transparent;
    box-shadow: none;
}

.show-more {
    display: inline-block;
    margin-top: 10px;
    font-size: 13px;
    color: #6b7280 !important;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
}

.show-more:hover {
    color: #111827 !important;
}

/* ===== MOBILE SIDEBAR ===== */
#mobileSidebar {
    z-index: 9999;
    padding-bottom: 4rem;
}

#overlay {
    z-index: 9998;
}

.mobile-sidebar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid #f3f4f6;
}

.mobile-sidebar-header h3 {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.mobile-sidebar-close {
    background: none;
    border: none;
    font-size: 22px;
    color: #6b7280;
    cursor: pointer;
    padding: 0;
    line-height: 1;
}

.mobile-sidebar-close:hover {
    color: #111827;
}

.mobile-sidebar-body {
    padding: 16px 20px;
}

.mobile-sidebar-body .total-results {
    margin-bottom: 16px;
}

.mobile-sidebar-body .form-group {
    margin-bottom: 10px;
}

.mobile-sidebar-body .form-group:last-of-type {
    margin-bottom: 14px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 991.98px) {
    .widget_top_filter {
        position: static;
    }
}
</style>

<!-- ===================== DESKTOP FILTER ===================== -->
<div class="widget_top_filter hidden lg:block">
    <div class="total-results">Total Results Found: {{ $products->count() }}</div>

    <form method="GET"
          action="{{ isset($category) ? route('product.category', ['category_slug' => $category->category_slug ?? $category->product_category_slug]) : route('allproducts') }}"
          id="filterFormDesktop">

        <!-- City -->
        <div class="form-group">
            <select id="city" name="city" class="selectpicker form-control" data-live-search="true" data-width="100%" data-dropup-auto="false" title="Select City">
                @foreach ($all_product_cities as $city)
                    <option value="{{ $city->id }}" {{ $filter_city == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Area -->
        <div class="form-group">
            <select id="area" name="area" class="selectpicker form-control" data-live-search="true" data-width="100%" data-dropup-auto="false" title="Select Area">
                <option value="">Select Area</option>
                @if($filter_city)
                    @foreach($all_cities as $cityItem)
                        @if($cityItem->id == $filter_city)
                            @foreach($cityItem->areas as $areaItem)
                                <option value="{{ $areaItem->id }}" {{ $filter_area == $areaItem->id ? 'selected' : '' }}>
                                    {{ $areaItem->area_name }}
                                </option>
                            @endforeach
                        @endif
                    @endforeach
                @endif
            </select>
        </div>

        <!-- Category -->
        <div class="form-group">
            <select id="category_filter" name="category" class="selectpicker form-control" data-live-search="true" data-width="100%" data-dropup-auto="false" title="Select Category">
                <option value="">Select Category</option>
                @foreach($all_categories ?? [] as $catSidebar)
                    @if(($catSidebar->category_parent_id ?? 0) == 0)
                        <option value="{{ $catSidebar->id }}" {{ (request('category') == $catSidebar->id || (isset($category) && $category->id == $catSidebar->id)) ? 'selected' : '' }}>
                            {{ $catSidebar->category_name ?? $catSidebar->product_category_name }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <!-- Sort By -->
        <div class="form-group">
            <select id="filter_sort_by" name="filter_sort_by" class="selectpicker form-control" data-live-search="true" data-width="100%" data-dropup-auto="false" title="Newest">
                <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>Newest</option>
                <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>Oldest</option>
                <option value="highest-rated" {{ $filter_sort_by == "highest-rated" ? 'selected' : '' }}>Highest Rated</option>
                <option value="lowest-rated" {{ $filter_sort_by == "lowest-rated" ? 'selected' : '' }}>Lowest Rated</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="filter-buttons">
            <button type="submit" class="btn-submit">Submit</button>
            <a class="btn-reset" href="{{ route('allproducts') }}">Reset</a>
        </div>
    </form>

    <!-- Category List -->
    <div class="category-box">
        <h4 class="category-title">Category</h4>
        <ul class="category-list">
            @foreach($all_categories ?? [] as $key => $catSidebar)
                <li class="category-item {{ $key >= 10 ? 'hidden-category' : '' }} {{ (isset($category) && $category->id == $catSidebar->id) ? 'active' : '' }}">
                    <a href="{{ route('product.category', ['category_slug' => $catSidebar->category_slug ?? $catSidebar->product_category_slug]) }}">
                        {{ $catSidebar->category_name ?? $catSidebar->product_category_name }}
                    </a>
                </li>
            @endforeach
        </ul>
        @if(count($all_categories) > 10)
            <a href="javascript:void(0);" class="show-more">View More</a>
        @endif
    </div>
</div>

<!-- ===================== MOBILE SIDEBAR ===================== -->
<div id="mobileSidebar" class="fixed top-0 left-0 h-full w-72 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-50 overflow-y-auto lg:hidden">
    <div class="mobile-sidebar-header">
        <h3>Filters</h3>
        <button id="closeFilterSidebar" class="mobile-sidebar-close">&times;</button>
    </div>

    <div class="mobile-sidebar-body">
        <div class="total-results">Total Results Found: {{ $products->count() }}</div>

        <form method="GET"
              action="{{ isset($category) ? route('product.category', ['category_slug' => $category->category_slug ?? $category->product_category_slug]) : route('allproducts') }}"
              id="filterFormMobile">

            <!-- Mobile City -->
            <div class="form-group">
                <select id="mcity" name="city" class="selectpicker form-control" data-live-search="true" data-width="100%" data-dropup-auto="false" title="Search City...">
                    <option value="">Search City...</option>
                    @foreach ($all_product_cities as $city)
                        <option value="{{ $city->id }}" {{ $filter_city == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Mobile Area -->
            <div class="form-group">
                <select id="marea" name="area" class="selectpicker form-control" data-width="100%" data-live-search="true" data-dropup-auto="false" title="Select Area">
                    <option value="">Select Area</option>
                </select>
            </div>

            <!-- Mobile Category -->
            <div class="form-group">
                <select id="mcategory_filter" name="category" class="selectpicker form-control" data-live-search="true" data-width="100%" data-dropup-auto="false" title="Search Category...">
                    <option value="">Select Category</option>
                    @foreach($all_categories ?? [] as $catSidebar)
                        @if(($catSidebar->category_parent_id ?? 0) == 0)
                            <option value="{{ $catSidebar->id }}" {{ (request('category') == $catSidebar->id || (isset($category) && $category->id == $catSidebar->id)) ? 'selected' : '' }}>
                                {{ $catSidebar->category_name ?? $catSidebar->product_category_name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Mobile Sort By -->
            <div class="form-group">
                <select id="mfilter_sort_by" name="filter_sort_by" class="selectpicker form-control" data-live-search="true" data-width="100%" title="Sort By">
                    <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>Oldest</option>
                    <option value="highest-rated" {{ $filter_sort_by == "highest-rated" ? 'selected' : '' }}>Highest Rated</option>
                    <option value="lowest-rated" {{ $filter_sort_by == "lowest-rated" ? 'selected' : '' }}>Lowest Rated</option>
                </select>
            </div>

            <!-- Mobile Buttons -->
            <div class="filter-buttons">
                <button type="submit" class="btn-submit">Submit</button>
                <a class="btn-reset" href="{{ route('allproducts') }}">Reset</a>
            </div>
        </form>

        <!-- Mobile Category List -->
        <div class="category-box">
            <h4 class="category-title">Category</h4>
            <ul class="category-list">
                @foreach($all_categories ?? [] as $key => $catSidebar)
                    <li class="category-item {{ $key >= 10 ? 'hidden-category' : '' }} {{ (isset($category) && $category->id == $catSidebar->id) ? 'active' : '' }}">
                        <a href="{{ route('product.category', ['category_slug' => $catSidebar->category_slug ?? $catSidebar->product_category_slug]) }}">
                            {{ $catSidebar->category_name ?? $catSidebar->product_category_name }}
                        </a>
                    </li>
                @endforeach
            </ul>
            @if(count($all_categories) > 10)
                <a href="javascript:void(0);" class="show-more">View More</a>
            @endif
        </div>
    </div>
</div>

<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 lg:hidden"></div>

<!-- Scripts -->
<script src="{{ asset('assets/frontend/js/bootstrap.bundle.min.js') }}" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js" defer></script>

<script>
$(document).ready(function() {
    // Sidebar open/close
    const $msidebar = $('#mobileSidebar');
    const $overlay = $('#overlay');

    function openSidebar() {
        $msidebar.removeClass('-translate-x-full');
        $overlay.removeClass('hidden').show();
        $('body').css('overflow', 'hidden');
    }

    function closeSidebar() {
        $msidebar.addClass('-translate-x-full');
        $overlay.addClass('hidden').hide();
        $('body').css('overflow', '');
    }

    $('#burgerBtn').off('click').on('click', openSidebar);
    $('#closeFilterSidebar, #overlay').off('click').on('click', closeSidebar);

    // Category Show More
    $(document).on('click', '.show-more', function () {
        const list = $(this).prev('.category-list');
        list.find('.hidden-category').slideToggle();
        $(this).text($(this).text() === 'View More' ? 'View Less' : 'View More');
    });

    // Initialize Bootstrap Select
    $('.selectpicker').selectpicker();

    // Sticky sidebar fix
    $(document).on('show.bs.select', '.selectpicker', function () {
        $(this).closest('.form-group').addClass('active-form-group');
        $('.widget_top_filter').css('position', 'relative');
    });

    $(document).on('shown.bs.select', '.selectpicker', function () {
        const $menu = $(this).parent().find('.dropdown-menu');
        $menu.css({ top: '100%', bottom: 'auto', transform: 'none' });
    });

    $(document).on('hide.bs.select', '.selectpicker', function () {
        $(this).closest('.form-group').removeClass('active-form-group');
        $('.widget_top_filter').css({ position: 'sticky', top: '20px' });
    });

    // Sort By: grey placeholder when no sort selected
    @if(!$filter_sort_by)
        $('#filter_sort_by').val('').selectpicker('refresh');
        $('#mfilter_sort_by').val('').selectpicker('refresh');
    @endif

    // Area loader
    function loadAreas(cityId, areaSelector) {
        const $area = $(areaSelector);
        $area.empty().append('<option value="">Select Area</option>');
        if($.fn.selectpicker) $area.selectpicker('refresh');
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
                if($.fn.selectpicker) $area.selectpicker('refresh');
            },
            error: function () {
                console.error('Unable to load areas');
            }
        });
    }

    $('#city').on('changed.bs.select', function() {
        loadAreas($(this).val(), '#area');
    });
    $('#mcity').on('changed.bs.select', function() {
        loadAreas($(this).val(), '#marea');
    });

    // Subcategory loader
    function loadSubcategories(categoryId, subSelector) {
        const $sub = $(subSelector);
        $sub.empty().append('<option value="">Select Subcategory</option>');
        if($.fn.selectpicker) $sub.selectpicker('refresh');
        if (!categoryId) return;

        $.ajax({
            url: "{{ url('ajax/subcategories') }}/" + categoryId,
            type: "GET",
            success: function (res) {
                if (res && res.length > 0) {
                    $.each(res, function (key, sub) {
                        $sub.append(`<option value="${sub.id}">${sub.text}</option>`);
                    });
                }
                if($.fn.selectpicker) $sub.selectpicker('refresh');
            },
            error: function() {
                console.error('Unable to load subcategories');
            }
        });
    }

    $('#category_filter').on('changed.bs.select', function() {
        loadSubcategories($(this).val(), '#subcategory_filter');
    });
    $('#mcategory_filter').on('changed.bs.select', function() {
        loadSubcategories($(this).val(), '#msubcategory_filter');
    });

    // Auto-submit on Sort change
    $('#filter_sort_by, #mfilter_sort_by').on('changed.bs.select', function() {
        $(this).closest('form').submit();
    });

    // Pre-load areas/categories if values exist
    if($('#city').val()) {
        loadAreas($('#city').val(), '#area');
    }
    if($('#category_filter').val()) {
        loadSubcategories($('#category_filter').val(), '#subcategory_filter');
    }
});
</script>
