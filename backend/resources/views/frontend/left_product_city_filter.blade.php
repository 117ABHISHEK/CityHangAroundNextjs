<style>
    .deals-filter-card {
        position: sticky;
        top: 20px;
        z-index: 10;
        padding: 15px;
        overflow: visible;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }

    .deals-filter .form-group {
        position: relative;
        z-index: auto;
        width: 100%;
        margin-bottom: 8px;
    }

    .deals-filter .bootstrap-select,
    .deals-filter .bootstrap-select > .dropdown-toggle {
        width: 100%;
    }

    .deals-filter .bootstrap-select > .dropdown-toggle {
        min-height: 42px;
        padding: 8px 12px;
        color: #212529;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 6px;
        box-shadow: none;
    }

    .deals-filter .bootstrap-select > .dropdown-toggle:hover,
    .deals-filter .bootstrap-select > .dropdown-toggle:focus,
    .deals-filter .bootstrap-select.show > .dropdown-toggle {
        color: #212529;
        background-color: #fff;
        border-color: #ff7856;
        box-shadow: 0 0 0 0.2rem rgba(255, 120, 86, 0.16);
        outline: 0;
    }

    .deals-filter .bootstrap-select .filter-option-inner-inner {
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .deals-filter .bootstrap-select > .dropdown-menu {
        width: 100%;
        max-width: 100%;
        z-index: 1030;
        border-color: #e5e7eb;
        border-radius: 6px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .deals-filter .bootstrap-select .dropdown-menu.inner {
        max-height: 280px;
        overflow-y: auto;
    }

    .deals-filter .bootstrap-select .bs-searchbox {
        padding: 8px;
    }

    .deals-filter .bootstrap-select .bs-searchbox .form-control {
        height: 36px;
        padding: 6px 10px;
        border: 1px solid #ced4da;
    }

    .deals-filter .bootstrap-select .dropdown-item {
        padding: 7px 12px;
        color: #333;
        white-space: normal;
    }

    .deals-filter .bootstrap-select .dropdown-item:hover,
    .deals-filter .bootstrap-select .dropdown-item:focus,
    .deals-filter .bootstrap-select .dropdown-item.active,
    .deals-filter .bootstrap-select .dropdown-item:active {
        color: #212529;
        background-color: #fff2ee;
    }

    .deals-filter .category-title {
        margin: 0 0 10px;
        font-size: 18px;
        font-weight: 600;
    }

    .deals-filter .category-list {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .deals-filter .category-item a {
        display: block;
        padding: 4px 0;
        color: #4b5563;
        font-size: 13px;
        line-height: 1.4;
        text-decoration: none;
    }

    .deals-filter .category-item a:hover,
    .deals-filter .category-item a:focus {
        color: #ff5a5f;
        text-decoration: underline;
    }

    .deals-filter .hidden-category {
        display: none;
    }

    .deals-filter .show-more {
        display: inline-block;
        margin-top: 8px;
        color: #ff5a5f;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-mobile-filter-trigger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 22px;
        color: #1f2937;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    #mobileSidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 1050;
        width: min(18rem, 88vw);
        overflow-y: auto;
        background: #fff;
        border-radius: 0 16px 16px 0;
        box-shadow: 10px 0 40px rgba(0, 0, 0, 0.16);
        transform: translateX(-105%);
        transition: transform 0.25s ease;
    }

    #mobileSidebar.mobile-sidebar-open {
        transform: translateX(0);
    }

    #filterOverlay {
        position: fixed;
        inset: 0;
        z-index: 1040;
        display: none;
        background: rgba(0, 0, 0, 0.5);
    }

    #filterOverlay.is-visible {
        display: block;
    }
</style>
@php
    $catSlug = $category->category_slug ?? $category->product_category_slug ?? '';
    $citySlug = $city->city_slug ?? '';
    $formAction = route('product.city', ['city_slug' => $citySlug]);
    $sortOptions = [
        'newest' => 'Newest',
        'oldest' => 'Oldest',
        'highest-rated' => 'Highest Rated',
        'lowest-rated' => 'Lowest Rated'
    ];
@endphp

<!-- Mobile trigger button -->
<div class="d-lg-none mb-2 text-center">
    <button id="burgerBtn" class="btn-mobile-filter-trigger">
        <i class="fas fa-filter"></i> Filters
    </button>
</div>

<!-- ===================== DESKTOP FILTER ===================== -->
<div class="widget_top_filter deals-filter deals-filter-card d-none d-lg-block">
    <div class="mb-2">
        <strong>Total Results Found: {{ $total_products ?? 0 }}</strong>
    </div>

    <form method="GET" action="{{ $formAction }}" id="filterFormDesktop">
        <div class="left_size_custom_section">
            
            <!-- City Search (Selectpicker with Live Search) -->
            <div class="form-group mb-1">
                <!-- <label class="text-xs font-bold text-gray-500 uppercase">City</label> -->
                <select id="city" name="city" class="selectpicker" data-live-search="true"  data-width="100%" title="Select City">
                    <option value="">Select City</option>
                    @foreach ($all_cities as $c)
                        <option value="{{ $c->id }}" data-route="{{ route('product.city', ['city_slug' => $c->city_slug]) }}" {{ $filter_city == $c->id ? 'selected' : '' }}>{{ $c->city_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Area Select -->
            <div class="form-group mb-1">
                <!-- <label class="text-xs font-bold text-gray-500 uppercase">Area</label> -->
                <select id="area" name="area"  class="selectpicker"
        data-live-search="true"
        data-width="100%"
        
        data-dropup-auto="false"
        title="Select Area"> 
                    <option value="">Select Area</option>
                    @foreach($filter_areas ?? [] as $area_row)
                        <option value="{{ $area_row->id }}" {{ $filter_area == $area_row->id ? 'selected' : '' }}>{{ $area_row->area_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Category Select -->
            <div class="form-group mb-1">
                <!-- <label class="text-xs font-bold text-gray-500 uppercase">Category</label> -->
                <select id="category_filter" name="category" class="selectpicker" data-live-search="true"  data-width="100%" title="Select Category">
                    <option value="">Select Category</option>
                    @foreach($sidebar_product_categories ?? [] as $cat_item)
                        @if(($cat_item->category_parent_id ?? 0) == 0)
                            <option value="{{ $cat_item->id }}" {{ (request('category') == $cat_item->id) ? 'selected' : '' }}>
                                {{ $cat_item->product_category_name ?? $cat_item->category_name ?? '' }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Sort By -->
            <div class="form-group mb-1">
                <select id="filter_sort_by" name="filter_sort_by" class="selectpicker" data-width="100%"  data-dropup-auto="false" title="Sort By">
                    @foreach($sortOptions as $val => $label)
                        <option value="{{ $val }}" {{ $filter_sort_by == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary w-50">Submit</button>
                <a class="btn btn-outline-primary w-50 d-flex align-items-center justify-content-center" href="{{ $formAction }}">Reset</a>
            </div>
        </div>
    </form>

    <div class="category-box mt-4">
        <h4 class="category-title">Category</h4>
        <ul class="category-list">
            @foreach($all_printable_categories as $key => $cat_sidebar)
                <li class="category-item {{ $key >= 10 ? 'hidden-category' : '' }}">
                    <a href="{{ route('product.category.city',['category_slug'=> $cat_sidebar->product_category_slug, 'city_slug'=>$citySlug]) }}">
                        {{ $cat_sidebar->product_category_name }}
                    </a>
                </li>
            @endforeach
        </ul>
        @if(count($all_printable_categories) > 10)
            <a href="javascript:void(0);" class="show-more">View More</a>
        @endif
    </div>
</div>

<!-- ===================== MOBILE SIDEBAR ===================== -->
<div id="mobileSidebar" class="deals-filter d-lg-none">
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
        <h3 class="h5 mb-0">Filters</h3>
        <button id="closeFilterSidebar" class="btn-close" aria-label="Close filters"></button>
    </div>

    <div class="p-3">
        <div class="mb-3">
            <strong>Total Results Found: {{ $total_products ?? 0 }}</strong>
        </div>

        <form method="GET" action="{{ $formAction }}" id="filterFormMobile">
            <div class="left_size_custom_section">
                <!-- Mobile City -->
                <div class="form-group mb-3">
                    <!-- <label class="text-xs font-bold text-gray-500 uppercase">City</label> -->
                    <select id="mcity" name="city" class="selectpicker"  data-live-search="true" data-width="100%" title="Select City">
                        <option value="">Select City</option>
                        @foreach ($all_cities as $c)
                            <option value="{{ $c->id }}" data-route="{{ route('product.city', ['city_slug' => $c->city_slug]) }}" {{ $filter_city == $c->id ? 'selected' : '' }}>{{ $c->city_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Mobile Area -->
                <div class="form-group mb-3">
                    <select id="marea" name="area" class="selectpicker" data-live-search="true"  data-width="100%" data-dropup-auto="false" title="Select Area">
                        <option value="">Select Area</option>
                        @foreach($filter_areas ?? [] as $area_row)
                            <option value="{{ $area_row->id }}" {{ $filter_area == $area_row->id ? 'selected' : '' }}>{{ $area_row->area_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Mobile Category -->
                <div class="form-group mb-3">
                    <!-- <label class="text-xs font-bold text-gray-500 uppercase">Category</label> -->
                    <select id="mcategory_filter" name="category" class="selectpicker" data-live-search="true" data-width="100%" title="Select Category">
                        <option value="">Select Category</option>
                        @foreach($sidebar_product_categories ?? [] as $cat_item)
                            @if(($cat_item->category_parent_id ?? 0) == 0)
                                <option value="{{ $cat_item->id }}" {{ (request('category') == $cat_item->id) ? 'selected' : '' }}>
                                    {{ $cat_item->product_category_name ?? $cat_item->category_name ?? '' }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Mobile Sort By -->
                <div class="form-group mb-3">
                    <select id="mfilter_sort_by" name="filter_sort_by" class="selectpicker" data-width="100%"  data-dropup-auto="false" title="Sort By">
                        @foreach($sortOptions as $val => $label)
                            <option value="{{ $val }}" {{ $filter_sort_by == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary w-50">Submit</button>
                    <a class="btn btn-outline-primary w-50 d-flex align-items-center justify-content-center" href="{{ $formAction }}">Reset</a>
                </div>
            </div>
        </form>

        <div class="category-box mt-5">
            <h4 class="category-title">Categories</h4>
            <ul class="category-list">
                @foreach($all_printable_categories as $key => $cat_sidebar)
                    <li class="category-item {{ $key >= 10 ? 'hidden-category' : '' }}">
                        <a href="{{ route('product.category.city',['category_slug'=> $cat_sidebar->product_category_slug, 'city_slug'=>$citySlug]) }}">
                            {{ $cat_sidebar->product_category_name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div id="filterOverlay" class="d-lg-none" aria-hidden="true"></div>

<script>
(function ($) {
    'use strict';

    $(function () {
        const $mobileSidebar = $('#mobileSidebar');
        const $filterOverlay = $('#filterOverlay');
        const $pickers = $('.deals-filter .selectpicker');

        function openSidebar() {
            $mobileSidebar.addClass('mobile-sidebar-open');
            $filterOverlay.addClass('is-visible').attr('aria-hidden', 'false');
            $('body').css('overflow-y', 'hidden');

        }

        function closeSidebar() {
            $mobileSidebar.removeClass('mobile-sidebar-open');
            $filterOverlay.removeClass('is-visible').attr('aria-hidden', 'true');
            $('body').css('overflow-y', '');
        }

        $('#burgerBtn')
            .off('click.dealsFilter')
            .on('click.dealsFilter', openSidebar);

        $('#closeFilterSidebar, #filterOverlay')
            .off('click.dealsFilter')
            .on('click.dealsFilter', closeSidebar);

        $(document)
            .off('keydown.dealsFilter')
            .on('keydown.dealsFilter', function (event) {
                if (event.key === 'Escape' && $mobileSidebar.hasClass('mobile-sidebar-open')) {
                    closeSidebar();
                }
            });

        $('.deals-filter .show-more')
            .off('click.dealsFilter')
            .on('click.dealsFilter', function () {
                const $link = $(this);
                $link.prev('.category-list').find('.hidden-category').stop(true, true).slideToggle(150);
                $link.text($link.text().trim() === 'View More' ? 'View Less' : 'View More');
            });

        $pickers.each(function () {
            const $picker = $(this);
            if (!$picker.data('selectpicker')) {
                $picker.selectpicker();
            }
        });

        function loadAreas(cityId, areaSelector) {
            const $area = $(areaSelector);
            $area.empty().append('<option value="">Select Area</option>');
            $area.selectpicker('refresh');

            if (!cityId) {
                return;
            }

            $.ajax({
                url: "{{ url('ajax/productareas') }}/" + encodeURIComponent(cityId),
                method: 'GET',
                dataType: 'json'
            }).done(function (areas) {
                $.each(areas || [], function (_, area) {
                    $('<option>', {
                        value: area.id,
                        text: area.area_name
                    }).appendTo($area);
                });
                $area.selectpicker('refresh');
            });
        }

        $('#city')
            .off('changed.bs.select.dealsFilter')
            .on('changed.bs.select.dealsFilter', function () {
                loadAreas($(this).val(), '#area');
            });

        $('#mcity')
            .off('changed.bs.select.dealsFilter')
            .on('changed.bs.select.dealsFilter', function () {
                loadAreas($(this).val(), '#marea');
            });

        $('#filter_sort_by, #mfilter_sort_by')
            .off('changed.bs.select.dealsFilter')
            .on('changed.bs.select.dealsFilter', function () {
                $(this).closest('form').trigger('submit');
            });

        $('#filterFormDesktop, #filterFormMobile')
            .off('submit.dealsFilter')
            .on('submit.dealsFilter', function () {
                const cityRoute = $(this).find('select[name="city"] option:selected').attr('data-route');
                if (cityRoute) {
                    this.action = cityRoute;
                }
            });
    });
})(jQuery);
</script>