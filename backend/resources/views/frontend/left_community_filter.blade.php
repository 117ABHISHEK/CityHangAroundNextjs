
<!-- Bootstrap Select CSS (CDN) -->
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
        background: transparent !important;
        border: none !important;
    }

    .bootstrap-select .btn {
        background-color: #fff !important;
        border: 1px solid #ced4da !important;
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

    /* Premium Reddit-like Cards */
    .reddit-card {
        background: #ffffff;
        border-radius: 8px;
        padding: 16px;
        border: 1px solid #eee;
        transition: all 0.2s ease;
        margin-top: 15px;
    }

    .reddit-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .reddit-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1b;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .reddit-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .reddit-list li {
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        color: #4b5563;
        transition: all 0.2s ease;
        margin-bottom: 2px;
    }

    .reddit-list li:hover {
        background: #f8f9fa;
        color: #ff4939ff;
        padding-left: 16px;
    }

    .reddit-list a {
        text-decoration: none !important;
        color: inherit;
        display: block;
    }
    
    .reddit-list a:hover {
        color: #ff4939 !important;
    }

    .form-group {
        width: 100%;
        margin-bottom: 10px;
    }

    .form-group .bootstrap-select {
        width: 100% !important;
        display: block !important;
    }

    /* Ensure dropdown menu starts at bottom of button */
    .widget_top_filter .bootstrap-select .dropdown-menu {
        top: 100% !important;
        left: 0 !important;
        transform: none !important;
        width: 100% !important;
    }

    .widget_top_filter {
        overflow: visible;
    }

    /* Remove blue background from active option in dropdown */
    .bootstrap-select .dropdown-menu li a.active,
    .bootstrap-select .dropdown-menu li a:focus,
    .bootstrap-select .dropdown-menu li a:hover {
        background-color: #f5f5f5 !important;
        color: #333 !important;
    }

    /* Start Discussion Button */
    .btn-discussion {
        background: #ff4939ff;
        color: #ffffffff !important;
        font-weight: 600;
        padding: 10px;
        border-radius: 50px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
        border: 1px solid #ced4da;
    }

    .btn-discussion:hover {
        background: #ffffff;
        color: #000000 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
   
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
        margin-top: 10px;
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
        justify-content: center;
        min-height: 28px;
        position: relative;
        padding: 6px 16px;
        background: #f8f9fa;
        color: #4b5563 !important;
        text-decoration: none !important;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-align: center;
        line-height: 1.3;
        border: 1px solid #e5e7eb;
        border-radius: 50px;
        margin-bottom: 2px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.08);
    }
    .category-item a:hover {
        background: #ff4b4b;
        color: #fff !important;
        border-color: #ff4b4b;
        box-shadow: 0 6px 12px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    .category-item a:active {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    .category-item.active a, .category-item a.active {
        background: #ff4b4b;
        color: #fff !important;
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

.widget_top_filter .form-group,
.left_size_custom_section .form-group {
    width: 100%;
    margin-bottom: 8px;
    min-height: 52px;           /* adjust as you like */
    display: flex;
    align-items: center;        /* vertically center selectpicker button */
    position: relative !important; /* Ensure z-index works on active/open containers */
    position: relative !important; /* Ensure z-index works */
}

/* Elevate active filter container */
.widget_top_filter .form-group:has(.bootstrap-select.show),
.left_size_custom_section .form-group:has(.bootstrap-select.show),
.widget_top_filter .form-group:focus-within,
.left_size_custom_section .form-group:focus-within {
    z-index: 999999 !important;
}

/* Elevate the container of the active/opened selectpicker so it renders above other filters */
.widget_top_filter .form-group:has(.bootstrap-select.show),
.left_size_custom_section .form-group:has(.bootstrap-select.show),
.widget_top_filter .form-group:focus-within,
.left_size_custom_section .form-group:focus-within {
    z-index: 999999 !important;
}

/* Make the selectpicker fill that height and width */
.widget_top_filter .bootstrap-select,
.widget_top_filter .bootstrap-select > .dropdown-toggle,
.left_size_custom_section .bootstrap-select,
.left_size_custom_section .bootstrap-select > .dropdown-toggle {
    width: 100% !important;
    height: 100%;
}

/* Optional: make Submit / Reset row same width */
.widget_top_filter .flex.gap-2 .btn,
.left_size_custom_section .flex.gap-2 .btn {
    width: 100%;
}

/* Make dropdown float on top, not push or overlap oddly */
.widget_top_filter .bootstrap-select .dropdown-menu,
.left_size_custom_section .bootstrap-select .dropdown-menu {
    width: 100% !important;
    z-index: 999999 !important;
    position: absolute !important;
    background-color: #ffffff !important;
    border: 1px solid #ced4da !important;
    box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
}

/* IMPORTANT: fix mobile overlapping issue by raising z-index ONLY when dropdown is open */
.bootstrap-select.show {
    z-index: 999999 !important;
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
        <strong>Total Results Found: {{ $total_results ?? 0 }}</strong>
    </div>

    @php 
        $actionRoute = isset($category) ? route('category.group', ['category_slug' => $category->category_slug ?? '']) : route('groups');
    @endphp

    <form method="GET" action="{{ $actionRoute }}" id="filterFormDesktop">
        <div class="left_size_custom_section">
             <!-- City Select -->
               <div class="form-group mb-1">
                <!-- <label class="text-xs font-bold text-gray-500 uppercase">City</label> -->
                <select id="city" name="city" class="selectpicker form-control" data-live-search="true"  data-width="100%" data-dropup-auto="false" title="Select City">
                   @if(isset($all_group_cities))
                        @foreach ($all_group_cities as $city_item)
                            <option value="{{ $city_item->id }}" {{ ($filter_city ?? '') == $city_item->id ? 'selected' : '' }}>
                                {{ $city_item->city_name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

           
              <!-- Area Select -->
            <div class="form-group mb-1">
                <!-- <label class="text-xs font-bold text-gray-500 uppercase">Area</label> -->
                <select id="area" name="area" class="selectpicker form-control" data-live-search="true"  data-width="100%" data-dropup-auto="false" title="Select Area">
                    
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
            <!-- Category Select -->

              <div class="form-group mb-1">
                <!-- <label class="text-xs font-bold text-gray-500 uppercase">Category</label> -->
                <select id="category_filter" name="category" class="selectpicker form-control" data-live-search="true"  data-width="100%" data-dropup-auto="false" title="Select Category">
                  
                   
                    @if(isset($all_categories))
                        @foreach ($all_categories as $category_item)
                            <option value="{{ $category_item->id }}" {{ request('category_filter') == $category_item->id ? 'selected' : '' }}>
                                {{ $category_item->category_name }}
                            </option>
                        @endforeach
                    @endif
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
                <a class="btn btn-outline-primary rounded w-1/2 flex items-center justify-center text-sm" href="{{ $actionRoute }}">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

<!-- ===================== EXPLORE CARDS ===================== -->
<div class="space-y-4">
   

    <!-- My Discussions Link -->
    <div class="reddit-card">
        <a href="#" class="reddit-title hover:text-red-500">
            <i class="fas fa-comments"></i> My Discussions
        </a>
    </div>

    <!-- Explore Categories Card -->
    <div class="reddit-card">
        <h3 class="reddit-title">
            <i class="fas fa-compass"></i> Explore Categories
        </h3>
        <ul class="reddit-list">
            @php $exploreCats = collect($groupCategories ?? $categories ?? []); @endphp
            @foreach ($exploreCats->take(5) as $category_item)
                <a href="{{ route('category.group', $category_item->category_slug) }}">
                    <li>{{ $category_item->category_name }}</li>
                </a>
            @endforeach
        </ul>
    </div>

     <!-- Start Discussion Button Card -->
     <div class="reddit-card">
        <h3 class="reddit-title"> </h3>
        <button class="btn-discussion" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.main_content.create_discussion_model'])}}', '{{get_phrase('Start Discussion')}}');">
            <i class="fas fa-plus-circle"></i> {{get_phrase('Start Discussion')}}
        </button>
    </div>

    <!-- TRENDING TOPICS -->
<div class="reddit-card">
    <h3 class="reddit-title">
        <i class="fas fa-chart-line"></i> Trending Topics
    </h3>

    <ul class="reddit-list">
          @php $trendingTopics = collect($all_categories ?? $categories ?? []); @endphp
          @foreach($trendingTopics->take(5) as $category_item)
            <li><a href="{{ route('category.group', $category_item->category_slug) }}">{{ $category_item->category_name }}</a></li>
        @endforeach
       
    </ul>
</div>
    <!-- Trending Cities Card -->
    <div class="reddit-card">
        <h3 class="reddit-title">
            <i class="fas fa-fire"></i> Trending Cities
        </h3>
        <ul class="reddit-list">
            @php $trendingCities = collect($all_group_cities ?? $all_cities ?? []); @endphp
            @foreach($trendingCities->take(5) as $city_item)
                <li>{{ $city_item->city_name }}</li>
            @endforeach
        </ul>
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
            <strong>Total Results Found: {{ $total_results ?? 0 }}</strong>
        </div>

        <form method="GET" action="{{ $actionRoute }}" id="filterFormMobile">
            <div class="left_size_custom_section">
               
               
  <!-- Mobile City Search -->
                <div class="form-group mb-3">
                    <!-- <label class="text-xs font-bold text-gray-500 uppercase">City</label> -->
                    <select id="mcity" name="city" class="selectpicker form-control" data-live-search="true"  data-width="100%" data-dropup-auto="false" title="Search City...">
                        @if(isset($all_group_cities))
                            @foreach ($all_group_cities as $item)
                                <option value="{{ $item->id }}" {{ ($filter_city ?? '') == $item->id ? 'selected' : '' }}>
                                    {{ $item->city_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Mobile Area Select -->
                <div class="form-group mb-3">
                    <select id="marea" name="area" class="selectpicker form-control"  data-width="100%" data-dropup-auto="false" title="Select Area">
                        <option value="">Select Area</option>
                    </select>
                </div>

                 <!-- Mobile Category Select -->
               
                <div class="form-group mb-3">
                    <!-- <label class="text-xs font-bold text-gray-500 uppercase">Category</label> -->
                    <select id="mcategory_filter" name="category" class="selectpicker form-control" data-live-search="true"  data-width="100%" data-dropup-auto="false" title="Search Category...">
                        @if(isset($all_categories))
                            @foreach ($all_categories as $item)
                                <option value="{{ $item->id }}" {{ request('category_filter') == $item->id ? 'selected' : '' }}>
                                    {{ $item->category_name }}
                                </option>
                            @endforeach
                        @endif
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
                    <a class="btn btn-outline-primary rounded w-1/2 flex items-center justify-center text-sm" href="{{ $actionRoute }}">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="overlay" onclick="var m=document.getElementById('mobileSidebar'); if(m){m.classList.add('-translate-x-full'); m.classList.remove('active');} this.classList.add('hidden'); this.style.display='none'; document.body.style.overflow='';" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 lg:hidden"></div>

<!-- Scripts -->
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

    // Initialize Selectpicker
    $('.selectpicker').selectpicker();

    /* ===== FIX bootstrap-select + sticky sidebar ===== */
    $(document).on('show.bs.select', '.selectpicker', function () {
        $('.widget_top_filter').css({
            position: 'relative',
            'z-index': 9999
        });
    });

    $(document).on('shown.bs.select', '.selectpicker', function () {
        const $menu = $(this).parent().find('.dropdown-menu');
        $menu.css({
            top: '100%',
            bottom: 'auto',
            transform: 'none',
            display: 'block'
        });
    });

    $(document).on('hide.bs.select', '.selectpicker', function () {
    $(this).closest('.form-group').removeClass('active-form-group');
        $(this).closest('.form-group').removeClass('active-form-group');
        $('.widget_top_filter').css({
            position: 'sticky',
            top: '20px',
            'z-index': 10
        });
    });

    // Reusable Area Loader
    function loadAreas(cityId, areaSelector, selectedArea = 0) {
        const $area = $(areaSelector);
        $area.empty().append('<option value="">Select Area</option>');
        if($.fn.selectpicker) $area.selectpicker('refresh');

        if (!cityId) return;

        $.ajax({
            url: "{{ url('ajax/groupareas/') }}/" + cityId,
            method: 'GET',
            success: function(result) {
                const data = typeof result === 'string' ? (typeof result === 'string' ? JSON.parse(result) : result) : result;
                if (data && data.length > 0) {
                    $.each(data, function(key, area) {
                        const isSelected = area.id == selectedArea ? 'selected' : '';
                        $area.append(`<option value="${area.id}" ${isSelected}>${area.area_name}</option>`);
                    });
                }
                if($.fn.selectpicker) $area.selectpicker('refresh');
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

    // Pre-load if values exist
    @if(isset($filter_city) && $filter_city)
        loadAreas('{{ $filter_city }}', '#area', '{{ $filter_area ?? 0 }}');
        loadAreas('{{ $filter_city }}', '#marea', '{{ $filter_area ?? 0 }}');
    @endif
});
</script>

<style>
/* === FORCE SAME LOOK FOR ALL SELECTPICKERS === */
.widget_top_filter .bootstrap-select > .dropdown-toggle,
.left_size_custom_section .bootstrap-select > .dropdown-toggle {
    height: 42px !important;
    padding: 8px 12px !important;
    font-size: 14px !important;
    border-radius: 6px !important;
    border: 1px solid #ced4da !important;
    background-color: #fff !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    box-shadow: 0 4px 6px rgba(0,0,0,0.08) !important;
    transition: all 0.2s ease !important;
}

.widget_top_filter .bootstrap-select > .dropdown-toggle:hover,
.left_size_custom_section .bootstrap-select > .dropdown-toggle:hover {
    box-shadow: 0 6px 12px rgba(0,0,0,0.12) !important;
    border-color: #abb5be !important;
}

.widget_top_filter .bootstrap-select .dropdown-toggle::after,
.left_size_custom_section .bootstrap-select .dropdown-toggle::after {
    margin-top: 0 !important;
}

.widget_top_filter .bootstrap-select .filter-option,
.left_size_custom_section .bootstrap-select .filter-option {
    color: #6b7280;
}

.widget_top_filter .bootstrap-select.show .filter-option,
.widget_top_filter .bootstrap-select .dropdown-toggle:focus .filter-option,
.left_size_custom_section .bootstrap-select.show .filter-option,
.left_size_custom_section .bootstrap-select .dropdown-toggle:focus .filter-option {
    color: #111827;
}
</style>
