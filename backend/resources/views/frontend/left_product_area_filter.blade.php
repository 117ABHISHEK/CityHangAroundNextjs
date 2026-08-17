<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/css/bootstrap-select.min.css">
<!-- Tailwind CSS (for layout) -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

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
        background-color: #c45500 !important;
        border: 1px solid #ced4da !important;
        /* padding: 8px 12px !important; */
    }

    /* Make all selectpicker boxes the same full width */
    .left_size_custom_section .bootstrap-select,
    .left_size_custom_section .bootstrap-select > .dropdown-toggle,
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
        margin-top: 5px;
        font-size: 18px;
        font-weight: bold;
    }

    .category-box {
        width: 100%;
        overflow: visible;
    }

    .category-list {
        list-style: none;
        margin: 0;
        padding: 5px 0;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .category-item {
        margin-bottom: 5px;
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
        margin-bottom: 0px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.08);
        transition: all 0.2s ease;
    }
    .category-item a:hover {
        background: transparent;
        
        border-color: transparent;
        box-shadow: 0 6px 12px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    .category-item a:active {
        transform: translateY(-5px); /* Slide UP on click */
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    .category-item.active a, .category-item a.active {
        background: transparent;
       
        border-color: transparent;
    }

    .hidden-category {
        display: none;
    }

    .show-more {
        display: inline-block;
        margin-top: 6px;
        font-size: 14px;
        color: transparent !important;
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
.form-group .bootstrap-select > .dropdown-toggle {
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
    min-height: 52px;           /* adjust as you like */
    display: flex;
    align-items: center;        /* vertically center selectpicker button */
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
.widget_top_filter .bootstrap-select > .dropdown-toggle {
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
    overflow: visible;   /* important for dropdowns */
}
/* Remove blue background from active option in dropdown */
.bootstrap-select .dropdown-menu li a.active,
.bootstrap-select .dropdown-menu li a:focus,
.bootstrap-select .dropdown-menu li a:hover {
    background-color: #f5f5f5 !important;  /* light gray */
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

<!-- Mobile trigger button -->
<div class="lg:hidden mb-2">
    <button id="burgerBtn" onclick="var m=document.getElementById('mobileSidebar'); var o=document.getElementById('overlay'); if(m){m.classList.remove('-translate-x-full'); m.classList.add('active');} if(o){o.classList.remove('hidden'); o.style.display='block';} document.body.style.overflow='hidden';" class="flex items-center gap-2 p-2 px-4 border rounded bg-white shadow-sm w-full justify-center">
        <i class="fas fa-filter"></i> Filters
    </button>
</div>

<!-- ===================== DESKTOP FILTER ===================== -->
<div class="widget_top_filter hidden lg:block">
    <div class="mb-2">
        <strong>Total Results Found: {{$products->total()}}</strong>
    </div>

    <form method="GET" action="{{ route('product.city.area', ['city_slug'=>$city->city_slug,'area_slug' => $area->area_slug]) }}"  id="filterFormDesktop">
        <div class="left_size_custom_section">
            
            <!-- City Search (Selectpicker with Live Search) -->
            <div class="form-group mb-1">
                <!-- <label class="text-xs font-bold text-gray-500 uppercase">City</label> -->
                <select id="city" name="city" class="selectpicker form-control" data-live-search="true"  data-width="100%" data-dropup-auto="false" title="Select City">
                    <option value="">Select City</option>
                  @foreach ($all_cities as $key => $city_row)
                    <option value="{{ $city_row->id }}{{ ($filter_city ?? '') == $city_row->id ? 'selected' : '' }}">
                        {{ $city_row->city_name }}
                    </option>
                @endforeach
                </select>
            </div>

            <!-- Area Select -->
            <div class="form-group mb-1">
                <!-- <label class="text-xs font-bold text-gray-500 uppercase">Area</label> -->
                <select id="area" name="area" class="selectpicker form-control" data-live-search="true"  data-width="100%" data-dropup-auto="false" title="Select Area">
                    <option value="">Select Area</option>
                    @foreach($all_areas ?? [] as $area_row)
                                    <option value="{{ $area_row->id }}" {{ request('area') == $area_row->id ? 'selected' : '' }}>
                                        {{ $area_row->area_name }}
                                    </option>
                                @endforeach
                    
                </select>
            </div>

            <!-- Category Select -->
            <div class="form-group mb-1">
                <!-- <label class="text-xs font-bold text-gray-500 uppercase">Category</label> -->
                <select id="category_filter" name="category" class="selectpicker form-control" data-live-search="true"  data-width="100%" data-dropup-auto="false" title="Select Category">
                    <option value="">Select Category</option>
                    @foreach($all_categories ?? [] as $category)
                        @if($category->category_parent_id == 0 || $category->category_parent_id == null)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->product_category_name ?? $category->category_name ?? $category->name ?? '' }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            

            <!-- Sort By -->
            <div class="form-group mb-1">
                <!-- <label class="text-xs font-bold text-gray-500 uppercase">Sort By</label> -->
                <select id="filter_sort_by" name="filter_sort_by"
        class="selectpicker form-control"
        data-live-search="true"
        data-width="100%"
        
        data-dropup-auto="false"
        title="Sort_By">
                    <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>Oldest</option>
                    <option value="highest-rated" {{ $filter_sort_by == "highest-rated" ? 'selected' : '' }}>Highest Rated</option>
                    <option value="lowest-rated" {{ $filter_sort_by == "lowest-rated" ? 'selected' : '' }}>Lowest Rated</option>
                </select>
            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary w-1/2">Submit</button>
                <a class="btn btn-outline-primary rounded w-1/2 flex items-center justify-center text-sm" href="{{ route('allproducts') }}">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div class="category-box mt-4">
        <h4 class="category-title">Category</h4>
        <ul class="category-list">
              @foreach ($all_printable_categories as $key => $category)
            <li class="category-item {{ $key >= 10 ? 'hidden-category' : '' }} {{ request('category') == $category->id ? 'active' : '' }}">
                <a href="{{ route('product.city.area', ['city_slug'=>$city->city_slug,'area_slug' => $area->area_slug, 'category' => $category->id]) }}">
                    {{ $category->product_category_name }} 
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
    <div class="flex justify-between items-center p-4 border-b">
        <h3 class="text-lg font-semibold">Filters</h3>
        <button id="closeFilterSidebar" onclick="var m=document.getElementById('mobileSidebar'); var o=document.getElementById('overlay'); if(m){m.classList.add('-translate-x-full'); m.classList.remove('active');} if(o){o.classList.add('hidden'); o.style.display='none';} document.body.style.overflow='';" class="text-gray-600 text-2xl">&times;</button>
    </div>

    <div class="p-4">
        <div class="mb-3">
            <strong>Total Results Found: {{$products->total()}}</strong>
        </div>

        <form method="GET" action="{{ route('product.city.area', ['city_slug'=>$city->city_slug,'area_slug' => $area->area_slug]) }}" id="filterForm">
            <div class="left_size_custom_section">
                <!-- Mobile City Search -->
                <div class="form-group mb-3">
                    <!-- <label class="text-xs font-bold text-gray-500 uppercase">City</label> -->
                    <select id="mcity" name="city" class="selectpicker form-control" data-live-search="true"  data-width="100%" data-dropup-auto="false" title="Select City">
                        <option value="">Select City</option>
                      @foreach ($all_cities as $key => $city_row)
                    <option value="{{ $city_row->id }}{{ ($filter_city ?? '') == $city_row->id ? 'selected' : '' }}">
                        {{ $city_row->city_name }}
                    </option>
                @endforeach
                    </select>
                </div>

                <!-- Mobile Area Select -->
                <div class="form-group mb-3">
                    <!-- <label class="text-xs font-bold text-gray-500 uppercase">Area</label> -->
                    <select id="marea" name="area" class="selectpicker form-control"  data-live-search="true" data-width="100%" data-dropup-auto="false" title="Select Area">
                        <option value="">Select Area</option>
                         @foreach($all_areas ?? [] as $area_row)
                                    <option value="{{ $area_row->id }}" {{ request('area') == $area_row->id ? 'selected' : '' }}>
                                        {{ $area_row->area_name }}
                                    </option>
                                @endforeach
                    </select>
                </div>

                <!-- Mobile Category Select -->
                <div class="form-group mb-3">
                    <!-- <label class="text-xs font-bold text-gray-500 uppercase">Category</label> -->
                    <select id="mcategory_filter" name="category" class="selectpicker form-control" data-live-search="true"  data-width="100%" data-dropup-auto="false" title="Select Category">
                    <option value="">Select Category</option>
                    @foreach($all_categories ?? [] as $category)
                        @if($category->category_parent_id == 0 || $category->category_parent_id == null)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                              {{ $category->product_category_name ?? $category->category_name ?? $category->name ?? '' }}
                            </option>
                        @endif
                    @endforeach
                </select>
                </div>

               
                <!-- Sort By -->
                <div class="form-group mb-3">
                    <!-- <label class="text-xs font-bold text-gray-500 uppercase">Sort By</label> -->
                    <select id="mfilter_sort_by" name="filter_sort_by" class="selectpicker form-control" data-live-search="true"  data-width="100%" title="Sort By">
                        <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>Oldest</option>
                        <option value="highest-rated" {{ $filter_sort_by == "highest-rated" ? 'selected' : '' }}>Highest Rated</option>
                        <option value="lowest-rated" {{ $filter_sort_by == "lowest-rated" ? 'selected' : '' }}>Lowest Rated</option>
                    </select>
                </div>

                <div class="flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary w-1/2">Submit</button>
                    <a class="btn btn-outline-primary rounded w-1/2 flex items-center justify-center text-sm" href="{{ route('allproducts') }}">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="category-box mt-5">
            <h4 class="category-title">Category</h4>
            <ul class="category-list">
                @foreach ($all_printable_categories as $key => $category)
            <li class="category-item {{ $key >= 10 ? 'hidden-category' : '' }} {{ request('category') == $category->id ? 'active' : '' }}">
                <a href="{{ route('product.city.area', ['city_slug'=>$city->city_slug,'area_slug' => $area->area_slug, 'category' => $category->id]) }}">
                    {{ $category->product_category_name }} 
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

<div id="overlay" onclick="var m=document.getElementById('mobileSidebar'); if(m){m.classList.add('-translate-x-full'); m.classList.remove('active');} this.classList.add('hidden'); this.style.display='none'; document.body.style.overflow='';" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 lg:hidden"></div>

<!-- Scripts: Bootstrap JS is required for selectpicker to work -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/js/bootstrap-select.min.js"></script>

<script>
$(document).ready(function() {
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

    // Category Show More
    $(document).on('click', '.show-more', function () {
        const list = $(this).prev('.category-list');
        list.find('.hidden-category').slideToggle();
        $(this).text($(this).text() === 'View More' ? 'View Less' : 'View More');
    });

    // Initialize Bootstrap Select
    $('.selectpicker').selectpicker();
    
/* ===== FIX bootstrap-select + sticky sidebar ===== */
$(document).on('show.bs.select', '.selectpicker', function () {
    $(this).closest('.form-group').addClass('active-form-group');
    $('.widget_top_filter').css({
        position: 'relative'
    });
});

$(document).on('shown.bs.select', '.selectpicker', function () {
    const $menu = $(this).parent().find('.dropdown-menu');

    // Force dropdown BELOW the select
    $menu.css({
        top: '100%',
        bottom: 'auto',
        transform: 'none'
    });
});

$(document).on('hide.bs.select', '.selectpicker', function () {
    $(this).closest('.form-group').removeClass('active-form-group');
    $('.widget_top_filter').css({
        position: 'sticky',
        top: '20px'
    });
});


    // Make Sort By show grey placeholder (like City/Area/Category) when no sort is active
    @if(!$filter_sort_by)
        $('#filter_sort_by').val('').selectpicker('refresh');
        $('#mfilter_sort_by').val('').selectpicker('refresh');
    @endif

    // Reusable Area Loader
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

    // City Change Events
    $('#city').on('changed.bs.select', function() {
        loadAreas($(this).val(), '#area');
    });
    $('#mcity').on('changed.bs.select', function() {
        loadAreas($(this).val(), '#marea');
    });

    // Reusable Subcategory Loader
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

    // Category Change Events
    $('#category_filter').on('changed.bs.select', function() {
        loadSubcategories($(this).val(), '#subcategory_filter');
    });
    $('#mcategory_filter').on('changed.bs.select', function() {
        loadSubcategories($(this).val(), '#msubcategory_filter');
    });

    // Auto-submit on Sort Change (desktop + mobile)
    $('#filter_sort_by, #mfilter_sort_by').on('changed.bs.select', function() {
        $(this).closest('form').submit();
    });

    // Pre-load if values exist
    if($('#city').val()) {
        loadAreas($('#city').val(), '#area');
    }
    if($('#category_filter').val()) {
        loadSubcategories($('#category_filter').val(), '#subcategory_filter');
    }
});
</script>

<style>
/* === FORCE SAME LOOK FOR ALL SELECTPICKERS (INCLUDING SORT BY) === */
.widget_top_filter .bootstrap-select > .dropdown-toggle {
    height: 42px !important;
    padding: 8px 12px !important;
    font-size: 14px !important;
    border-radius: 6px !important;
    border: 1px solid #ced4da !important;
    background-color: #c45500 !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    box-shadow: 0 4px 6px rgba(0,0,0,0.08) !important;
    transition: all 0.2s ease !important;
}

.widget_top_filter .bootstrap-select > .dropdown-toggle:hover {
    box-shadow: 0 6px 12px rgba(0,0,0,0.12) !important;
    border-color: #abb5be !important;
}

/* Same arrow alignment */
.widget_top_filter .bootstrap-select .dropdown-toggle::after {
    margin-top: 0 !important;
}

/* Placeholder color (grey) */
.widget_top_filter .bootstrap-select .filter-option {
    color: #6b7280; /* grey like City/Category */
}

/* Selected value color */
.widget_top_filter .bootstrap-select.show .filter-option,
.widget_top_filter .bootstrap-select .dropdown-toggle:focus .filter-option {
    color: #111827;
}

/* Always show Sort By selected values as black */
.widget_top_filter select[name="filter_sort_by"] ~ .dropdown-toggle .filter-option {
    color: #111827 !important;
}


</style>


