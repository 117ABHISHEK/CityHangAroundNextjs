@php
    $current_city_id = $filter_city ?? null;
    $current_area_id = $filter_area ?? null;
    $sort_by = $filter_sort_by ?? 'newest';

    // Fetch active product cities if not already shared
    if (!isset($all_cities)) {
        $all_cities = \Cache::remember('active_product_cities_v3', 3600, function() {
            return \DB::table('cities')
                ->whereExists(fn($q) => $q->select(DB::raw(1))->from('marketplaces')->join('pages', 'pages.id', '=', 'marketplaces.page_id')->whereColumn('pages.city_id', 'cities.id')->where('marketplaces.product_status', 2))
                ->orderBy('city_name')
                ->get();
        });
    }
@endphp

<div class="widget_top_filter">
    <div class="col-12 mb-2">
        <strong>{{ get_phrase('Total Results Found') }} : {{ $products->count() }}</strong>
    </div>

    <div class="row">
        <form method="GET" action="{{ route('allproducts') }}" class="container">
            <div class="row gx-2 gy-2 align-items-start">
                <!-- City Dropdown -->
                <div class="col-4 col-md-3">
                    <select id="city_filter" name="city_filter" class="form-control form-control-sm">
                        <option value="">{{ get_phrase('Select a city') }}</option>
                        @foreach($all_cities as $city)
                            <option value="{{ $city->id }}" {{ $current_city_id == $city->id ? 'selected' : '' }}>
                                {{ $city->city_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Area Dropdown (Populated via AJAX) -->
                <div class="col-4 col-md-3">
                    <select id="area_filter" name="area_filter" class="form-control form-control-sm">
                        <option value="0">{{ get_phrase('Select an area') }}</option>
                    </select>
                </div>

                <!-- Sort Dropdown -->
                <div class="col-4 col-md-2">
                    <select id="filter_sort_by" name="filter_sort_by" class="form-control form-control-sm">
                        <option value="newest" {{ $sort_by == 'newest' ? 'selected' : '' }}>{{ get_phrase('Newest') }}</option>
                        <option value="oldest" {{ $sort_by == 'oldest' ? 'selected' : '' }}>{{ get_phrase('Oldest') }}</option>
                    </select>
                </div>

                <div class="col-6 col-md-2 d-flex">
                    <a href="{{ route('allproducts') }}" class="btn-sm w-100 btn btn-primary py-2">{{ get_phrase('Reset') }}</a>
                </div>

                <div class="col-6 col-md-2 d-flex">
                    <button type="submit" class="btn btn-primary btn-sm w-100 py-2">{{ get_phrase('Submit') }}</button>
                </div>
            </div>
        </form>

        <!-- Product Categories Carousel -->
        @if(isset($all_printable_categories) && $all_printable_categories->isNotEmpty())
        <div class="tags_Outer mt-3">
            <div class="owl-carousel tag-carousel owl-theme">
                @foreach ($all_printable_categories as $category)
                    <div class="item">
                        <a href="{{ route('product.category', ['category_slug' => $category->product_category_slug]) }}">
                            {{ $category->product_category_name }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
$(document).ready(function() {
    // 1. Initialize Carousels
    if ($('.tag-carousel').length > 0) {
        var owl = $('.tag-carousel');
        owl.owlCarousel({
            margin: 10, nav: true, dots: false, autoWidth: true, loop: false,
            responsive: { 0: { items: 1 }, 600: { items: 3 }, 1000: { items: 5 } }
        });

        function checkNavButtons() {
            var itemsCount = $('.owl-item').length;
            var visibleItems = owl.find('.owl-item.active').length;
            var currentIndex = owl.find('.owl-item.active').first().index();
            (currentIndex === 0) ? $('.owl-prev').hide() : $('.owl-prev').show();
            (currentIndex + visibleItems >= itemsCount) ? $('.owl-next').hide() : $('.owl-next').show();
        }

        checkNavButtons();
        owl.on('changed.owl.carousel', checkNavButtons);
    }

    // 2. Initialize Selection (optimized select2 integration if exists)
    if ($.fn.select2) {
        $('#city_filter, #area_filter, #filter_sort_by').select2();
    }

    // 3. Dynamic Area Loading
    function loadAreas(cityId, selectedAreaId = 0) {
        if (!cityId) return;
        $.ajax({
            url: '/ajax/productareas/' + cityId,
            method: 'GET',
            success: function(result) {
                var areas = (typeof result === 'string' ? JSON.parse(result) : result);
                var $areaSelect = $('#area_filter');
                $areaSelect.html("<option value='0'>Select an area</option>");
                $.each(areas, function(k, v) {
                    var selected = (v.id == selectedAreaId) ? 'selected' : '';
                    $areaSelect.append('<option value="' + v.id + '" ' + selected + '>' + v.area_name + '</option>');
                });
                if ($.fn.select2) $areaSelect.select2();
            }
        });
    }

    // Initial load if city is selected
    @if($current_city_id)
        loadAreas({{ $current_city_id }}, {{ $current_area_id ?? 0 }});
    @endif

    $('#city_filter').on('change', function() {
        loadAreas($(this).val());
    });
});
</script>
