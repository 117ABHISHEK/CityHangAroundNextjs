<style>
    /* Premium Sidebar Filter Styling */
    .widget_top_filter {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        position: -webkit-sticky;
        position: sticky;
        top: 20px;
    }
    
    .widget-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    
    .filter-section-title {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    
    .custom-select-wrapper {
        position: relative;
        width: 100%;
        margin-bottom: 16px;
    }
    
    .custom-select-wrapper select {
        width: 100% !important;
        padding: 10px 36px 10px 16px !important;
        border-radius: 8px !important;
        border: 1.5px solid #e2e8f0 !important;
        background: #fff !important;
        font-size: 14px !important;
        color: #1e293b !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        font-weight: 500 !important;
    }
    
    .custom-select-wrapper select:focus {
        border-color: #ff4d4d !important;
        box-shadow: 0 0 0 3px rgba(255, 77, 77, 0.15) !important;
        outline: none !important;
    }
    
    .custom-select-wrapper::after {
        content: "\f078";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 12px;
        color: #64748b;
        pointer-events: none;
    }
    
    .btn-action-submit,
    #mobileSidebar .btn-action-submit {
        background: linear-gradient(135deg, #ff4d4d, #f857a6) !important;
        border: none !important;
        color: #ffffff !important;
        padding: 10px 20px !important;
        border-radius: 8px !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        width: 100% !important;
        margin-bottom: 8px !important;
        box-shadow: 0 4px 12px rgba(255, 77, 77, 0.2) !important;
        display: block !important;
    }
    
    .btn-action-submit:hover,
    #mobileSidebar .btn-action-submit:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 16px rgba(255, 77, 77, 0.3) !important;
        color: #ffffff !important;
    }
    
    .btn-action-reset,
    #mobileSidebar .btn-action-reset {
        border: 1px solid #e2e8f0 !important;
        background: #f8fafc !important;
        color: #475569 !important;
        padding: 10px 16px !important;
        border-radius: 8px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        text-align: center !important;
        display: block !important;
        width: 100% !important;
        transition: all 0.2s ease !important;
    }
    
    .btn-action-reset:hover,
    #mobileSidebar .btn-action-reset:hover {
        background: #f1f5f9 !important;
        color: #1e293b !important;
        border-color: #cbd5e1 !important;
        text-decoration: none !important;
    }
    
    /* Category List Styling */
    .category-box {
        border-top: 1px solid #edf2f7;
        margin-top: 20px;
        padding-top: 20px;
    }
    
    .category-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .category-item {
        margin-bottom: 8px;
    }
    
    .category-item a,
    #mobileSidebar .category-item a {
        display: block !important;
        padding: 10px 14px !important;
        border-radius: 8px !important;
        background: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        color: #475569 !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        transition: all 0.2s ease !important;
    }
    
    .category-item a:hover,
    .category-item.active a,
    #mobileSidebar .category-item a:hover,
    #mobileSidebar .category-item.active a {
        background: linear-gradient(135deg, #ff4d4d, #f857a6) !important;
        border-color: transparent !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(255, 77, 77, 0.2) !important;
        text-decoration: none !important;
    }

    /* Mobile Burger Trigger - Floating Pill Style */
    .btn-mobile-filter-trigger {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        border: 1px solid rgba(229, 231, 235, 0.8) !important;
        background: #ffffff !important;
        color: #1e293b !important;
        padding: 10px 22px !important;
        border-radius: 50px !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        cursor: pointer !important;
        justify-content: center !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06) !important;
        width: fit-content !important;
        margin: 10px auto !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
    }
    
    .btn-mobile-filter-trigger:hover,
    .btn-mobile-filter-trigger:focus {
        background: linear-gradient(135deg, #ff4d4d, #f857a6) !important;
        border-color: transparent !important;
        color: #ffffff !important;
        box-shadow: 0 6px 20px rgba(255, 77, 77, 0.3) !important;
        transform: translateY(-1px) !important;
    }

    #mobileSidebar {
        border-top-right-radius: 16px !important;
        border-bottom-right-radius: 16px !important;
        box-shadow: 10px 0 40px rgba(0, 0, 0, 0.1) !important;
        background: #ffffff !important;
    }

    /* Responsive displaying rules replacing Tailwind */
    @media (min-width: 992px) {
        .desktop-filter-sidebar {
            display: block !important;
        }
        .mobile-filter-trigger-wrapper,
        .mobile-filter-sidebar-drawer,
        .mobile-filter-overlay {
            display: none !important;
        }
    }
    
    @media (max-width: 991.98px) {
        .desktop-filter-sidebar {
            display: none !important;
        }
        .mobile-filter-trigger-wrapper {
            display: block !important;
            text-align: center;
            margin-bottom: 15px;
        }
        
        /* Mobile Sidebar Drawer Layout */
        .mobile-filter-sidebar-drawer {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            height: 100% !important;
            width: 280px !important;
            background: #ffffff !important;
            box-shadow: 10px 0 40px rgba(0, 0, 0, 0.15) !important;
            z-index: 100050 !important;
            overflow-y: auto !important;
            transition: transform 0.3s ease-in-out !important;
            transform: translateX(-100%) !important;
            display: block !important;
        }
        
        .mobile-filter-sidebar-drawer.active {
            transform: translateX(0) !important;
        }
        
        .mobile-filter-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            background: rgba(0, 0, 0, 0.5) !important;
            z-index: 100040 !important;
            display: none !important;
        }
        
        .mobile-filter-overlay.active {
            display: block !important;
        }
    }
</style>

<!-- Mobile Burger Trigger -->
<div class="mobile-filter-trigger-wrapper">
    <button id="burgerBtn" onclick="var m=document.getElementById('mobileSidebar'); var o=document.getElementById('overlay'); if(m){m.classList.add('active');} if(o){o.classList.add('active');} document.body.style.overflow='hidden';" class="btn-mobile-filter-trigger">
        <i class="fas fa-filter"></i> Filters
    </button>
</div>

<!-- Desktop Filter Sidebar -->
<div class="widget_top_filter desktop-filter-sidebar">
    @if(!isset($is_single_product) || !$is_single_product)
    <div class="mb-3 text-secondary small fw-bold">
        Total Results Found: {{$products->count()}}
    </div>
    @endif
    
    <h3 class="widget-title">Filter by</h3>
    
    <form method="GET" action="{{ route('product.category.city', ['category_slug' => $category->product_category_slug, 'city_slug' => $city->city_slug]) }}">
        <div class="filter-section-title">Sort By</div>
        <div class="custom-select-wrapper">
            <select name="filter_sort_by" id="filter_sort_by_custom">
                <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>Newest</option>
                <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>Oldest</option>
                <option value="highest-rated" {{ $filter_sort_by == "highest-rated" ? 'selected' : '' }}>Highest Rated</option>
                <option value="lowest-rated" {{ $filter_sort_by == "lowest-rated" ? 'selected' : '' }}>Lowest Rated</option>
            </select>
        </div>
        
        <button type="submit" class="btn-action-submit">Submit</button>
        <a class="btn-action-reset" href="{{ route('product.category.city', ['category_slug' => $category->product_category_slug, 'city_slug' => $city->city_slug]) }}">
            Reset
        </a>
    </form>

    <!-- Categories List -->
    @if(count($market_categories) > 0)
    <div class="category-box">
        <h4 class="filter-section-title">Categories</h4>
        <ul class="category-list">
            @foreach ($market_categories as $cat)
            <li class="category-item {{ isset($category) && $category->id == $cat->id ? 'active' : '' }}">
                <a href="{{ route('product.category.city', ['category_slug' => $cat->product_category_slug, 'city_slug' => $city->city_slug]) }}">
                    {{ $cat->product_category_name }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>

<!-- Mobile Sidebar (Slide-out Drawer) -->
<div id="mobileSidebar" class="mobile-filter-sidebar-drawer">
    <div class="d-flex justify-content-between align-items-center p-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800 mb-0">Filters</h3>
        <button id="closeFilterSidebar" onclick="var m=document.getElementById('mobileSidebar'); var o=document.getElementById('overlay'); if(m){m.classList.remove('active');} if(o){o.classList.remove('active');} document.body.style.overflow='';" class="text-gray-500 hover:text-gray-700 text-2xl font-bold border-none bg-transparent">
            &times;
        </button>
    </div>
    
    <div class="p-4">
        @if(!isset($is_single_product) || !$is_single_product)
        <div class="mb-4 text-secondary small fw-bold">
            Total Results Found: {{$products->count()}}
        </div>
        @endif
        
        <form method="GET" action="{{ route('product.category.city', ['category_slug' => $category->product_category_slug, 'city_slug' => $city->city_slug]) }}">
            <div class="mb-4">
                <div class="filter-section-title">Sort By</div>
                <div class="custom-select-wrapper">
                    <select name="filter_sort_by" id="filter_sort_by_mobile">
                        <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>Oldest</option>
                        <option value="highest-rated" {{ $filter_sort_by == "highest-rated" ? 'selected' : '' }}>Highest Rated</option>
                        <option value="lowest-rated" {{ $filter_sort_by == "lowest-rated" ? 'selected' : '' }}>Lowest Rated</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="btn-action-submit">Apply</button>
            <a class="btn-action-reset" href="{{ route('product.category.city', ['category_slug' => $category->product_category_slug, 'city_slug' => $city->city_slug]) }}">
                Reset
            </a>
        </form>
        
        @if(count($market_categories) > 0)
        <div class="category-box">
            <h4 class="filter-section-title">Categories</h4>
            <ul class="category-list">
                @foreach ($market_categories as $cat)
                <li class="category-item {{ isset($category) && $category->id == $cat->id ? 'active' : '' }}">
                    <a href="{{ route('product.category.city', ['category_slug' => $cat->product_category_slug, 'city_slug' => $city->city_slug]) }}">
                        {{ $cat->product_category_name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>

<div id="overlay" onclick="var m=document.getElementById('mobileSidebar'); if(m){m.classList.remove('active');} this.classList.remove('active'); document.body.style.overflow='';" class="mobile-filter-overlay"></div>

<script>
$(document).ready(function() {
    const $msidebar = $('#mobileSidebar');
    const $overlay = $('#overlay');
    
    function openSidebar() {
        $msidebar.addClass('active');
        $overlay.addClass('active');
        $('body').css('overflow', 'hidden');
    }
    
    function closeSidebar() {
        $msidebar.removeClass('active');
        $overlay.removeClass('active');
        $('body').css('overflow', '');
    }

    // Direct bindings
    $('#burgerBtn').off('click').on('click', openSidebar);
    $('#closeFilterSidebar, #closeSidebar, #overlay').off('click').on('click', closeSidebar);
});
</script>
