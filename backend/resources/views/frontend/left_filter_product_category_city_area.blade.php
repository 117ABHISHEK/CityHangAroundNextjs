<style>
    /* Premium Filter Bar Styling */
    .filter-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
    }
    
    .filter-bar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 16px;
        border-bottom: 1px solid #f7fafc;
        padding-bottom: 12px;
    }
    
    .results-badge {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .results-badge span {
        color: #ff4d4d;
    }
    
    .filter-form-inline {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    .custom-select-wrapper {
        position: relative;
        min-width: 160px;
    }
    
    .custom-select-wrapper select {
        width: 100% !important;
        padding: 8px 36px 8px 16px !important;
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
        padding: 8px 20px !important;
        border-radius: 8px !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 4px 12px rgba(255, 77, 77, 0.2) !important;
    }
    
    .btn-action-submit:hover,
    #mobileSidebar .btn-action-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(255, 77, 77, 0.3) !important;
        color: #ffffff !important;
    }
    
    .btn-action-reset,
    #mobileSidebar .btn-action-reset {
        border: 1px solid #e2e8f0 !important;
        background: #f8fafc !important;
        color: #475569 !important;
        padding: 8px 16px !important;
        border-radius: 8px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        text-align: center !important;
        display: inline-block !important;
        transition: all 0.2s ease !important;
    }
    
    .btn-action-reset:hover,
    #mobileSidebar .btn-action-reset:hover {
        background: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
        color: #1e293b !important;
        text-decoration: none !important;
    }
    
    /* Category Tags Carousel Styling */
    .tags-section-wrapper {
        padding-top: 8px;
    }
    
    .tag-carousel .item a {
        display: inline-block;
        padding: 8px 18px;
        border-radius: 20px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .tag-carousel .item a:hover, 
    .tag-carousel .item.active a {
        background: linear-gradient(135deg, #ff4d4d, #f857a6) !important;
        border-color: transparent !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(255, 77, 77, 0.2) !important;
    }
    
    /* Burger Menu Mobile Button */
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
    
    .btn-mobile-filter-trigger:hover {
        background: linear-gradient(135deg, #ff4d4d, #f857a6) !important;
        border-color: transparent !important;
        color: #ffffff !important;
        box-shadow: 0 6px 20px rgba(255, 77, 77, 0.3) !important;
        transform: translateY(-1px) !important;
    }
    
    /* Mobile Drawer */
    #mobileSidebar {
        border-top-right-radius: 16px !important;
        border-bottom-right-radius: 16px !important;
        box-shadow: 10px 0 40px rgba(0, 0, 0, 0.1) !important;
        background: #ffffff !important;
    }
    
    .mobile-filter-header {
        border-bottom: 1px solid #edf2f7;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .mobile-filter-content {
        padding: 20px;
    }
    
    .mobile-filter-section {
        margin-bottom: 24px;
    }
    
    .mobile-filter-section h4 {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    
    .mobile-tags-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .mobile-tags-list li {
        margin-bottom: 8px;
    }
    
    .mobile-tags-list a {
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
    
    .mobile-tags-list a:hover,
    .mobile-tags-list li.active a {
        background: linear-gradient(135deg, #ff4d4d, #f857a6) !important;
        border-color: transparent !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(255, 77, 77, 0.2) !important;
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

<!-- Desktop Filter Bar (Visible only on Large Screens) -->
<div class="filter-card desktop-filter-sidebar">
    <div class="filter-bar-header">
        @if(!isset($is_single_product) || !$is_single_product)
        <div class="results-badge">
            <i class="fas fa-search"></i>
            <span>{{$products->count()}}</span> deals found
        </div>
        @endif
        
        <form method="GET" action="{{ route('product.category.city.area',['city_slug'=>$city->city_slug,'category_slug'=>$category->product_category_slug,'area_slug'=>$area->area_slug]) }}" class="filter-form-inline">
            <div class="custom-select-wrapper">
                <select name="filter_sort_by" id="filter_sort_by_custom">
                    <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>Oldest</option>
                </select>
            </div>
            
            <a class="btn-action-reset" href="{{ route('product.category.city.area',['city_slug'=>$city->city_slug,'category_slug'=>$category->product_category_slug,'area_slug'=>$area->area_slug]) }}">
                Reset
            </a>
            
            <button type="submit" class="btn-action-submit">Submit</button>
        </form>
    </div>

    <!-- Tags Section -->
    @if(count($market_categories) > 0)
    <div class="tags-section-wrapper">
        <div class="owl-carousel tag-carousel owl-theme">
            @foreach ($market_categories as $cat)
            <div class="item {{ isset($category) && $category->id == $cat->id ? 'active' : '' }}">
                <a href="{{ route('product.category.city.area',['city_slug'=>$city->city_slug,'category_slug'=>$cat->product_category_slug,'area_slug'=>$area->area_slug]) }}">
                    {{ $cat->product_category_name }} in {{$area->area_name}}
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Mobile Sidebar (Slide-out Drawer) -->
<div id="mobileSidebar" class="mobile-filter-sidebar-drawer">
    <div class="mobile-filter-header">
        <h3 class="text-lg font-semibold text-gray-800 mb-0">Filters</h3>
        <button id="closeFilterSidebar" onclick="var m=document.getElementById('mobileSidebar'); var o=document.getElementById('overlay'); if(m){m.classList.remove('active');} if(o){o.classList.remove('active');} document.body.style.overflow='';" class="text-gray-500 hover:text-gray-700 text-2xl font-bold border-none bg-transparent">
            &times;
        </button>
    </div>
    
    <div class="mobile-filter-content">
        @if(!isset($is_single_product) || !$is_single_product)
        <div class="results-badge mb-4">
            <i class="fas fa-search"></i>
            <span>{{$products->count()}}</span> deals found
        </div>
        @endif
        
        <form method="GET" action="{{ route('product.category.city.area',['city_slug'=>$city->city_slug,'category_slug'=>$category->product_category_slug,'area_slug'=>$area->area_slug]) }}">
            <div class="mobile-filter-section">
                <h4>Sort by</h4>
                <div class="custom-select-wrapper w-full">
                    <select name="filter_sort_by" id="filter_sort_by_mobile">
                        <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>Oldest</option>
                    </select>
                </div>
            </div>
            
            <div class="mobile-filter-section">
                <h4>Categories</h4>
                <ul class="mobile-tags-list">
                    @foreach ($market_categories as $cat)
                    <li class="{{ isset($category) && $category->id == $cat->id ? 'active' : '' }}">
                        <a href="{{ route('product.category.city.area',['city_slug'=>$city->city_slug,'category_slug'=>$cat->product_category_slug,'area_slug'=>$area->area_slug]) }}">
                            {{ $cat->product_category_name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            
            <div class="d-flex gap-2 mt-4">
                <a class="btn-action-reset flex-1 text-center" href="{{ route('product.category.city.area',['city_slug'=>$city->city_slug,'category_slug'=>$category->product_category_slug,'area_slug'=>$area->area_slug]) }}">
                    Reset
                </a>
                <button type="submit" class="btn-action-submit flex-1">Apply</button>
            </div>
        </form>
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

    $('.tag-carousel').owlCarousel({
        margin: 10,
        nav: true,
        dots: false,
        autoWidth: true,
        loop: false,
        responsive: {
            0: { items: 1 },
            600: { items: 3 },
            1000: { items: 4 }
        }
    });
});
</script>
