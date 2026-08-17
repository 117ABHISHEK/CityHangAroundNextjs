@php
    $capsuleCategories = collect($capsuleCategories ?? []);
@endphp

<style>
    /* Sticky Sidebar Fix */
    .widget_top_filter {
        background: #ffffff;
        padding: 20px;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        position: -webkit-sticky;
        position: sticky;
        top: 20px;
        z-index: 10;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
    }

    /* Premium Custom Select Styling */
    .custom-select-premium {
        display: block;
        width: 100%;
        height: 44px;
        padding: 10px 36px 10px 16px;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.5;
        color: #1e293b;
        background-color: #fafbfe;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 14px 10px;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        transition: all 0.2s ease-in-out;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .custom-select-premium:focus {
        border-color: var(--primary, #ff4939);
        outline: 0;
        box-shadow: 0 0 0 3px rgba(255, 73, 57, 0.15);
        background-color: #ffffff;
    }

    .form-group {
        width: 100%;
        margin-bottom: 16px;
    }

    .form-group label {
        font-size: 11.5px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 6px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Mobile Sidebar Toggle styling */
    #mobileSidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 300px;
        background-color: #ffffff;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: translateX(-100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 99999;
        overflow-y: auto;
    }

    #mobileSidebar.active {
        transform: translateX(0);
    }

    #overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(4px);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        z-index: 99998;
    }

    #overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .category-title {
        margin-top: 20px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #475569;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 8px;
    }

    .category-box {
        width: 100%;
        overflow: visible;
    }

    .category-list {
        list-style: none;
        margin: 15px 0 0 0;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .category-item {
        margin: 0;
    }

    .category-item a {
        display: inline-flex;
        align-items: center;
        justify-content: left;
        min-height: 28px;
        position: relative;
        padding: 6px 14px;
        background: #f1f5f9;
        color: #475569 !important;
        text-decoration: none !important;
        font-size: 12.5px;
        font-weight: 500;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
    }

    .category-item a:hover,
    .category-item.active a,
    .category-item a.active {
        background: var(--primary, #ff4939);
        color: #fff !important;
        border-color: var(--primary, #ff4939);
        box-shadow: 0 4px 10px rgba(255, 73, 57, 0.15);
        transform: translateY(-1px);
    }

    .hidden-category {
        display: none;
    }

    .show-more {
        display: inline-block;
        margin-top: 10px;
        font-size: 13.5px;
        color: var(--primary, #ff4939) !important;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
    }

    .action-row {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .action-row .btn-reset {
        flex: 1;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #475569 !important;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 500;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .action-row .btn-reset:hover {
        background: #f8fafc;
        border-color: #94a3b8;
    }

    .action-row .btn-submit {
        flex: 1.5;
        height: 42px;
        background: var(--primary, #ff4939);
        border: 1px solid var(--primary, #ff4939);
        color: #fff;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(255, 73, 57, 0.15);
        transition: all 0.25s ease;
    }

    .action-row .btn-submit:hover {
        background: var(--primary-hover, #e03d2f);
        border-color: var(--primary-hover, #e03d2f);
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(255, 73, 57, 0.25);
    }
</style>

<!-- Mobile trigger button -->
<div class="d-lg-none mb-3">
    <button id="burgerBtn" onclick="var m=document.getElementById('mobileSidebar'); var o=document.getElementById('overlay'); if(m){m.classList.remove('-translate-x-full'); m.classList.add('active');} if(o){o.classList.remove('hidden'); o.style.display='block';} document.body.style.overflow='hidden';" class="d-flex align-items-center justify-content-center gap-2 p-2.5 px-4 border rounded-3 bg-white shadow-sm w-100 fw-semibold text-secondary transition">
        <i class="fas fa-filter text-danger"></i> Filters
    </button>
</div>

<!-- ===================== DESKTOP FILTER ===================== -->
<div class="widget_top_filter d-none d-lg-block">
    <div class="mb-4">
        <strong class="text-secondary small">Total Results Found: {{ $total_blogs ?? (isset($blogs) ? $blogs->total() : 0) }}</strong>
    </div>

    <form method="GET" action="{{ route('blogs') }}" id="filterFormDesktop">
        <input type="hidden" name="page" value="" />
        <div class="left_size_custom_section">
            
            <!-- City Search -->
            <div class="form-group">
                <label for="city">City</label>
                <select id="city" name="city" class="custom-select-premium">
                    <option value="">Select City</option>
                    @foreach ($all_blog_cities as $c)
                        <option value="{{ $c->id }}" {{ $filter_city == $c->id ? 'selected' : '' }}>
                            {{ $c->city_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Area Select -->
            <div class="form-group">
                <label for="area">Area</label>
                <select id="area" name="area" class="custom-select-premium">
                    <option value="">Select Area</option>
                    @foreach($all_areas ?? [] as $a)
                        <option value="{{ $a->id }}" {{ $filter_area == $a->id ? 'selected' : '' }}>
                            {{ $a->area_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Category Select -->
            <div class="form-group">
                <label for="category_filter">Category</label>
                <select id="category_filter" name="category" class="custom-select-premium">
                    <option value="">Select Category</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->category_slug }}" {{ (isset($filter_category) && $filter_category == $cat->category_slug) ? 'selected' : '' }}>
                            {{ $cat->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort By -->
            <div class="form-group">
                <label for="filter_sort_by">Sort By</label>
                <select id="filter_sort_by" name="filter_sort_by" class="custom-select-premium">
                    <option value="">Sort By</option>
                    <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>Oldest</option>
                    <option value="highest-rated" {{ $filter_sort_by == "highest-rated" ? 'selected' : '' }}>Highest Rated</option>
                    <option value="lowest-rated" {{ $filter_sort_by == "lowest-rated" ? 'selected' : '' }}>Lowest Rated</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="action-row">
                <a class="btn-reset" href="{{ route('blogs') }}">
                    Reset
                </a>
                <button type="submit" class="btn-submit">Submit</button>
            </div>
        </div>
    </form>

    <!-- Categories capsule section -->
    <div class="category-box mt-4">
        <h4 class="category-title">Categories</h4>
        @if($capsuleCategories->isNotEmpty())
        <ul class="category-list">
            @foreach ($capsuleCategories as $key => $cat)
                @php
                    $isActive = (isset($filter_category) && $filter_category == $cat->category_slug) || (isset($category->category_slug) && $category->category_slug == $cat->category_slug);
                @endphp
                <li class="category-item {{ $key >= 10 ? 'hidden-category' : '' }} {{ $isActive ? 'active' : '' }}">
                    <a href="{{ route('category.blog', ['category_slug' => $cat->category_slug]) }}" class="{{ $isActive ? 'active' : '' }}">
                        {{ $cat->category_name }}
                    </a>
                </li>
            @endforeach
        </ul>
        @if($capsuleCategories->count() > 10)
            <a href="javascript:void(0);" class="show-more">View More</a>
        @endif
        @else
            <div class="text-muted" style="font-size:13px; padding:6px 0;">No categories found</div>
        @endif
    </div>
</div>

<!-- ===================== MOBILE SIDEBAR DRAWER ===================== -->
<div id="mobileSidebar" class="d-lg-none">
    <div class="d-flex justify-content-between align-items-center p-4 border-bottom">
        <h3 class="fs-5 fw-bold text-dark mb-0">Filters</h3>
        <button id="closeFilterSidebar" onclick="var m=document.getElementById('mobileSidebar'); var o=document.getElementById('overlay'); if(m){m.classList.add('-translate-x-full'); m.classList.remove('active');} if(o){o.classList.add('hidden'); o.style.display='none';} document.body.style.overflow='';" class="text-secondary" style="font-size: 1.8rem; background: none; border: none; line-height: 1;">&times;</button>
    </div>

    <div class="p-4">
        <div class="mb-4">
            <strong class="text-secondary small">Total Results Found: {{ $total_blogs ?? (isset($blogs) ? $blogs->total() : 0) }}</strong>
        </div>

        <form method="GET" action="{{ route('blogs') }}" id="filterForm">
            <input type="hidden" name="page" value="" />
            <div class="left_size_custom_section">
                <!-- Mobile City Search -->
                <div class="form-group">
                    <label for="mcity">City</label>
                    <select id="mcity" name="city" class="custom-select-premium">
                        <option value="">Select City</option>
                        @foreach ($all_blog_cities as $c)
                            <option value="{{ $c->id }}" {{ $filter_city == $c->id ? 'selected' : '' }}>
                                {{ $c->city_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Mobile Area Select -->
                <div class="form-group">
                    <label for="marea">Area</label>
                    <select id="marea" name="area" class="custom-select-premium">
                        <option value="">Select Area</option>
                        @foreach($all_areas ?? [] as $a)
                            <option value="{{ $a->id }}" {{ $filter_area == $a->id ? 'selected' : '' }}>
                                {{ $a->area_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Mobile Category Select -->
                <div class="form-group">
                    <label for="mcategory_filter">Category</label>
                    <select id="mcategory_filter" name="category" class="custom-select-premium">
                        <option value="">Select Category</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->category_slug }}" {{ (isset($filter_category) && $filter_category == $cat->category_slug) ? 'selected' : '' }}>
                                {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Mobile Sort By -->
                <div class="form-group">
                    <label for="mfilter_sort_by">Sort By</label>
                    <select id="mfilter_sort_by" name="filter_sort_by" class="custom-select-premium">
                        <option value="">Sort By</option>
                        <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>Oldest</option>
                        <option value="highest-rated" {{ $filter_sort_by == "highest-rated" ? 'selected' : '' }}>Highest Rated</option>
                        <option value="lowest-rated" {{ $filter_sort_by == "lowest-rated" ? 'selected' : '' }}>Lowest Rated</option>
                    </select>
                </div>

                <!-- Mobile Buttons -->
                <div class="action-row">
                    <a class="btn-reset" href="{{ route('blogs') }}">
                        Reset
                    </a>
                    <button type="submit" class="btn-submit">Submit</button>
                </div>
            </div>
        </form>

        <div class="category-box mt-4">
            <h4 class="category-title">Categories</h4>
            @if($capsuleCategories->isNotEmpty())
            <ul class="category-list">
                @foreach ($capsuleCategories as $key => $cat)
                    @php
                        $isActive = (isset($filter_category) && $filter_category == $cat->category_slug) || (isset($category->category_slug) && $category->category_slug == $cat->category_slug);
                    @endphp
                    <li class="category-item {{ $key >= 10 ? 'hidden-category' : '' }} {{ $isActive ? 'active' : '' }}">
                        <a href="{{ route('category.blog', ['category_slug' => $cat->category_slug]) }}" class="{{ $isActive ? 'active' : '' }}">
                            {{ $cat->category_name }}
                        </a>
                    </li>
                @endforeach
            </ul>
            @if($capsuleCategories->count() > 10)
                <a href="javascript:void(0);" class="show-more">View More</a>
            @endif
            @else
                <div class="text-muted" style="font-size:13px; padding:6px 0;">No categories found</div>
            @endif
        </div>
    </div>
</div>

<div id="overlay" onclick="var m=document.getElementById('mobileSidebar'); if(m){m.classList.add('-translate-x-full'); m.classList.remove('active');} this.classList.add('hidden'); this.style.display='none'; document.body.style.overflow='';" class="d-lg-none"></div>

<script>
$(document).ready(function() {
    // Sidebar Drawer Slide Toggles
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
            $("body").css("overflow", "hidden");
        });
    }

    function hideSidebar() {
        $msidebar.removeClass("active");
        $overlay.removeClass("active");
        $("body").css("overflow", "");
    }

    if($closeBtn.length) {
        $closeBtn.on("click", hideSidebar);
    }

    if($overlay.length) {
        $overlay.on("click", hideSidebar);
    }

    // Category Expand Show More
    $(document).off('click', '.show-more').on('click', '.show-more', function () {
        const list = $(this).prev('.category-list');
        list.find('.hidden-category').slideToggle(200);
        $(this).text($(this).text() === 'View More' ? 'View Less' : 'View More');
    });

    // Reusable Area Loader
    function loadAreas(cityId, areaSelector) {
        const $area = $(areaSelector);
        $area.empty().append('<option value="">Select Area</option>');

        if (!cityId) return;

        $.ajax({
            url: "{{ url('ajax/blogareas') }}/" + cityId,
            type: "GET",
            success: function (response) {
                let areasList = response;
                if (typeof response === 'string') {
                    try { areasList = JSON.parse(response); } catch (e) { areasList = []; }
                }

                if (areasList && areasList.length > 0) {
                    $.each(areasList, function (key, area) {
                        const areaId = area.id ?? area.area_id;
                        const areaName = area.area_name ?? area.name ?? '';
                        if (!areaId) return;
                        $area.append(`<option value="${areaId}">${areaName}</option>`);
                    });
                }
            },
            error: function () {
                console.error('Unable to load areas');
            }
        });
    }

    // City Dropdown Change listeners
    $('#city').on('change', function() {
        loadAreas($(this).val(), '#area');
    });
    $('#mcity').on('change', function() {
        loadAreas($(this).val(), '#marea');
    });

    // Auto-submit Sort options
    $('#filter_sort_by, #mfilter_sort_by').on('change', function() {
        $(this).closest('form').submit();
    });

    // Pre-load areas if city is pre-selected on page load
    var initialCity = $('#city').val();
    if(initialCity) {
        if($('#area option').length <= 2) {
            loadAreas(initialCity, '#area');
        }
    }
});
</script>
