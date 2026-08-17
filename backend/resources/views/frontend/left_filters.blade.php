
<!-- Bootstrap Select CSS (CDN) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/css/bootstrap-select.min.css">
<!-- Tailwind CSS (for layout) -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<style>
    /* Premium Redesigned Sidebar Container */
    .widget_top_filter {
        background: #ffffff;
        padding: 24px 20px;
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 16px;
        position: -webkit-sticky;
        position: sticky;
        top: 24px;
        z-index: 10;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
    }
    .widget_top_filter:hover {
        box-shadow: 0 12px 36px -8px rgba(0, 0, 0, 0.08), 0 2px 5px rgba(0, 0, 0, 0.03);
    }

    /* Fix Bootstrap selectpicker dropdown z-index */
    .bootstrap-select .dropdown-menu {
        z-index: 99999 !important;
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
    .left_size_custom_section .bootstrap-select > .dropdown-toggle,
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
        justify-content: flex-start;
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
        color: #ffffff !important;
        border-color: #ff5a5f;
        box-shadow: 0 8px 16px -4px rgba(255, 90, 95, 0.35);
        transform: translateY(-2px);
    }
    .category-item a:active {
        transform: translateY(0);
    }
    .category-item.active a, .category-item a.active {
        background: #ff5a5f;
        color: #ffffff !important;
        border-color: #ff5a5f;
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

    /* All form-group rows take full width */
    .left_size_custom_section .form-group {
        width: 100%;
        margin-bottom: 12px;
    }

    /* Custom Submit & Reset Button Styling */
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
    /* All form-group rows take full width */
    .left_size_custom_section .form-group {
        width: 100%;
        margin-bottom: 6px;
    }

    /* Force every selectpicker wrapper + its button to 100% width */
    .left_size_custom_section .bootstrap-select,
    .left_size_custom_section .bootstrap-select > .dropdown-toggle,
    .left_size_custom_section .bootstrap-select .btn {
        width: 100% !important;
        max-width: 100% !important;
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
  /* Always show Sort By selected values as black */
.widget_top_filter select[name="filter_sort_by"] ~ .dropdown-toggle .filter-option {
    color: #333333!important;
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
        <strong>Total Results Found: {{ $mypages->total() }}</strong>
    </div>

    <form method="GET" action="{{ route('page.category', ['category_slug' => $category->category_slug]) }}" id="filterFormDesktop">
        <div class="left_size_custom_section">
            
            <!-- City Search (Selectpicker with Live Search) -->
            <div class="form-group mb-1">
                <!-- <label class="text-xs font-bold text-gray-500 uppercase">City</label> -->
                <select id="city" name="city" class="selectpicker form-control" data-live-search="true"  data-width="100%" title="Select city">
                    <option value="">Select City</option>
                    @foreach($all_cities as $c)
                        <option value="{{ $c->id }}" {{ $filter_city == $c->id ? 'selected' : '' }}>
                            {{ $c->city_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Area Select -->
            <div class="form-group mb-1">
                <!-- <label class="text-xs font-bold text-gray-500 uppercase">Area</label> -->
                <select id="area" name="area" class="selectpicker form-control" data-live-search="true"  data-width="100%" title="Select Area">
                    <option value="">Select Area</option>
                    @if(!empty($filter_areas))
                        @foreach($filter_areas as $areaItem)
                            <option value="{{ $areaItem->id }}" {{ $filter_area == $areaItem->id ? 'selected' : '' }}>
                                {{ $areaItem->area_name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Category Select -->
            <div class="form-group mb-1">
                <!-- <label class="text-xs font-bold text-gray-500 uppercase">Category</label> -->
                <select id="category_filter" name="category" class="selectpicker form-control" data-live-search="true"  data-width="100%" title="Select Category">
                    <option value="">Select Category</option>
                    @php
                        $display_categories = collect($all_categories);
                        if ($display_categories->isEmpty()) {
                            $display_categories = Cache::remember("fallback_all_parent_categories", 3600, function() {
                                return DB::table('pagecategories')
                                    ->where(fn($q) => $q->whereNull('category_parent_id')->orWhere('category_parent_id', 0))
                                    ->select('id', 'category_name', 'category_slug', 'category_parent_id')
                                    ->orderBy('category_name')
                                    ->get();
                            });
                        }
                    @endphp
                    @foreach($display_categories as $cat)
                        @if($cat->category_parent_id == null || $cat->category_parent_id == 0)
                            <option value="{{ $cat->id }}" {{ request('category_filter') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->category_name }}
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
                <button type="submit" class="btn-submit-premium w-1/2">Submit</button>
                <a class="btn-reset-premium w-1/2 flex items-center justify-center text-sm" href="{{ route('page.category', ['category_slug' => $category->category_slug]) }}">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div class="category-box mt-4">
        <h4 class="category-title">Category</h4>
        <ul class="category-list">
            @foreach($all_categories as $key => $cat)
                @if($cat->category_parent_id == null)
                    <li class="category-item {{ $key >= 10 ? 'hidden-category' : '' }} mb-2">
                        <a href="{{ route('page.category', ['category_slug' => $cat->category_slug]) }}">
                            {{ $cat->category_name }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
        @if($all_categories->where('category_parent_id', null)->count() > 10)
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
            <strong>Total Results Found: {{ $mypages->total() }}</strong>
        </div>

        <form method="GET" action="{{ route('page.category', ['category_slug' => $category->category_slug]) }}" id="filterFormMobile">
            <div class="left_size_custom_section">
                <!-- Mobile City Search -->
                <div class="form-group mb-3">
                    <!-- <label class="text-xs font-bold text-gray-500 uppercase">City</label> -->
                    <select id="mcity" name="city" class="selectpicker form-control" data-live-search="true" data-width="100%" title="Search City...">
                     
                        @foreach($all_cities as $c)
                            <option value="{{ $c->id }}" {{ $filter_city == $c->id ? 'selected' : '' }}>
                                {{ $c->city_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Mobile Area Select -->
                <div class="form-group mb-3">
                    <!-- <label class="text-xs font-bold text-gray-500 uppercase">Area</label> -->
                    <select id="marea" name="area" class="selectpicker form-control" data-live-search="true"  data-width="100%" title="Select Area">
                        <option value="">Select Area</option>
                        @if(!empty($filter_areas))
                            @foreach($filter_areas as $areaItem)
                                <option value="{{ $areaItem->id }}" {{ $filter_area == $areaItem->id ? 'selected' : '' }}>
                                    {{ $areaItem->area_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
               

                <!-- Mobile Category Select -->
                <div class="form-group mb-3">
                    <!-- <label class="text-xs font-bold text-gray-500 uppercase">Category</label> -->
                    <select id="mcategory_filter" name="category" class="selectpicker form-control"  data-live-search="true" data-width="100%" title="Search Category...">
                        <option value="">Select Category</option>
                        @php
                            $display_categories = collect($all_categories);
                            if ($display_categories->isEmpty()) {
                                $display_categories = Cache::remember("fallback_all_parent_categories", 3600, function() {
                                    return DB::table('pagecategories')
                                        ->where(fn($q) => $q->whereNull('category_parent_id')->orWhere('category_parent_id', 0))
                                        ->select('id', 'category_name', 'category_slug', 'category_parent_id')
                                        ->orderBy('category_name')
                                        ->get();
                                });
                            }
                        @endphp
                        @foreach($display_categories as $cat)
                            @if($cat->category_parent_id == null || $cat->category_parent_id == 0)
                                <option value="{{ $cat->id }}" {{ request('category_filter') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                
                <!-- Sort By -->
                <div class="form-group mb-3">
                    <!-- <label class="text-xs font-bold text-gray-500 uppercase">Sort By</label> -->
                    <select id="mfilter_sort_by" name="filter_sort_by"
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
                    <button type="submit" class="btn-submit-premium w-1/2">Submit</button>
                    <a class="btn-reset-premium w-1/2 flex items-center justify-center text-sm" href="{{ route('page.category', ['category_slug' => $category->category_slug]) }}">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="category-box mt-5">
            <h4 class="category-title">Categories</h4>
            <ul class="category-list">
                @foreach($all_categories as $key => $cat)
                    @if($cat->category_parent_id == null)
                        <li class="category-item {{ $key >= 10 ? 'hidden-category' : '' }}">
                            <a href="{{ route('page.category', ['category_slug' => $cat->category_slug]) }}">
                                {{ $cat->category_name }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
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
    let desktopAreaRequest = null;
    let mobileAreaRequest = null;

    // Reusable Area Loader
  function getAreaFilterParams(areaSelector) {
    return {
        category_id: areaSelector === '#marea' ? $('#mcategory_filter').val() : $('#category_filter').val(),
        subcategory_id: areaSelector === '#marea' ? $('#msubcategory_filter').val() : $('#subcategory_filter').val()
    };
}

  function loadAreas(cityId, areaSelector, selectedAreaId = '') {
    const $area = $(areaSelector);

    $area.empty().append('<option value="">Select Area</option>');

    if($.fn.selectpicker) {
        $area.selectpicker('refresh');
    }

    if (!cityId) return;

    const isMobile = areaSelector === '#marea';
    const params = getAreaFilterParams(areaSelector);

    if (isMobile && mobileAreaRequest) {
        mobileAreaRequest.abort();
    }

    if (!isMobile && desktopAreaRequest) {
        desktopAreaRequest.abort();
    }

    const request = $.ajax({
        url: "{{ url('ajax/areas') }}/" + cityId,
        data: params,
        type: "GET",
        success: function (response) {

            if (response && response.length > 0) {
                $.each(response, function (key, area) {
                    const selected = String(selectedAreaId) === String(area.id) ? ' selected' : '';
                    $area.append(`<option value="${area.id}"${selected}>${area.area_name}</option>`);
                });
            }

            // 🔥 FIX FOR SEARCH NOT WORKING
            if($.fn.selectpicker) {
                $area.selectpicker('refresh');
                $area.selectpicker('render');
            }
        },
        error: function (xhr, status) {
            if (status !== 'abort') {
                console.error('Unable to load areas');
            }
        }
    });

    if (isMobile) {
        mobileAreaRequest = request;
    } else {
        desktopAreaRequest = request;
    }
}

    // City Change Events
  $('#city').on('changed.bs.select change', function() {
    loadAreas($(this).val(), '#area');
});
  $('#mcity').on('changed.bs.select change', function() {
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
                        $sub.append(`<option value="${sub.id}">${sub.category_name}</option>`);
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
        loadAreas($('#city').val(), '#area');
    });
    $('#mcategory_filter').on('changed.bs.select', function() {
        loadSubcategories($(this).val(), '#msubcategory_filter');
        loadAreas($('#mcity').val(), '#marea');
    });
    $('#subcategory_filter').on('changed.bs.select', function() {
        loadAreas($('#city').val(), '#area');
    });
    $('#msubcategory_filter').on('changed.bs.select', function() {
        loadAreas($('#mcity').val(), '#marea');
    });

    // Auto-submit on Sort Change (desktop + mobile)
    $('#filter_sort_by, #mfilter_sort_by').on('changed.bs.select', function() {
        $(this).closest('form').submit();
    });

    // Pre-load if values exist
    if($('#city').val()) {
        loadAreas($('#city').val(), '#area', '{{ $filter_area ?? '' }}');
    }

    if($('#mcity').val()) {
        loadAreas($('#mcity').val(), '#marea', '{{ $filter_area ?? '' }}');
    }
    if($('#category_filter').val()) {
        loadSubcategories($('#category_filter').val(), '#subcategory_filter');
    }
});
</script>

