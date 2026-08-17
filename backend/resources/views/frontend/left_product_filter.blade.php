<style>
    .widget-product-filter {
        background: #ffffff;
        padding: 20px;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        margin-bottom: 25px;
    }
    .widget-product-filter h2 {
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #475569;
        margin-top: 0;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }
    .widget-product-filter .form-group {
        margin-bottom: 16px;
    }
    .widget-product-filter .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
        display: block;
    }
    /* Select2 customizations to look extremely modern and match theme */
    .widget-product-filter .select2-container--default .select2-selection--single {
        height: 44px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 10px !important;
        display: flex;
        align-items: center;
        background-color: #fafbfe !important;
        transition: all 0.2s ease;
    }
    .widget-product-filter .select2-container--default .select2-selection--single:hover {
        border-color: var(--primary, #ff4939) !important;
    }
    .widget-product-filter .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1e293b !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        padding-left: 12px !important;
    }
    .widget-product-filter .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px !important;
        right: 8px !important;
    }
    /* Filter Tags / Categories List */
    .widget-product-filter .tags_left {
        margin: 20px 0;
    }
    .widget-product-filter .tags_left h4 {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 10px;
        text-transform: uppercase;
    }
    .widget-product-filter .tags_left ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .widget-product-filter .tags_left li {
        margin: 0;
    }
    .widget-product-filter .tags_left li a {
        display: inline-block;
        padding: 6px 14px;
        background: #f1f5f9;
        color: #475569 !important;
        font-size: 12.5px;
        font-weight: 500;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }
    .widget-product-filter .tags_left li a:hover,
    .widget-product-filter .tags_left li a.active {
        background: var(--primary, #ff4939);
        color: #fff !important;
        border-color: var(--primary, #ff4939);
        box-shadow: 0 4px 10px rgba(255, 73, 57, 0.15);
        transform: translateY(-1px);
    }
    .widget-product-filter .action-btns {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }
    .widget-product-filter .action-btns .btn-reset {
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
    .widget-product-filter .action-btns .btn-reset:hover {
        background: #f8fafc;
        border-color: #94a3b8;
    }
    .widget-product-filter .action-btns .btn-submit {
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
    .widget-product-filter .action-btns .btn-submit:hover {
        background: var(--primary-hover, #e03d2f);
        border-color: var(--primary-hover, #e03d2f);
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(255, 73, 57, 0.25);
    }
</style>

<div class="widget-product-filter">
    <h2>{{ get_phrase('Filter by') }}</h2>
    
    <div class="mb-3 d-flex align-items-center justify-content-between">
        <span class="text-muted" style="font-size: 13px;">{{ get_phrase('Total Results') }}</span>
        <span class="badge bg-secondary px-2.5 py-1.5" style="border-radius: 12px; font-size: 12px; background-color: #64748b !important; color:#fff;">{{ $products->total() }}</span>
    </div>

    <form method="GET" action="{{ route('allproducts') }}">
        <!-- City Select -->
        <div class="form-group">
            <label for="city">{{ get_phrase('City') }}</label>
            <select id="city" name="city" style="width: 100%;" class="form-control @error('city') is-invalid @enderror">
                <option value="">{{ get_phrase('Select a city') }}</option>
                @foreach ($all_product_cities as $city)
                    <option value="{{ $city->id }}" {{ $filter_city == $city->id ? 'selected' : '' }}>
                        {{ $city->city_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Area Select -->
        <div class="form-group">
            <label for="area">{{ get_phrase('Area') }}</label>
            <select id="area" name="area" style="width: 100%;" class="form-control @error('area') is-invalid @enderror">
                <option value="">{{ get_phrase('Select an area') }}</option>
            </select>
        </div>

        <!-- Sort Select -->
        <div class="form-group">
            <label for="filter_sort_by">{{ get_phrase('Sort by') }}</label>
            <select id="filter_sort_by" name="filter_sort_by" style="width: 100%;" class="form-control @error('sort-dropdown') is-invalid @enderror">
                <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>{{ get_phrase('Newest') }}</option>
                <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>{{ get_phrase('Oldest') }}</option>
            </select>
        </div>

        <!-- Tags / Categories -->
        @if(isset($all_printable_categories) && count($all_printable_categories) > 0)
        <div class="tags_left">
            <h4>{{ get_phrase('Categories') }}</h4>
            <ul>
                @foreach ($all_printable_categories as $category)
                    <li>
                        <a href="{{ route('product.category', ['category_slug' => $category->product_category_slug]) }}">
                            {{ $category->product_category_name }}
                        </a>
                    </li>
                @endforeach   
            </ul>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="action-btns">
            <a class="btn-reset" href="{{ route('allproducts') }}">
                {{ get_phrase('Reset') }}
            </a>
            <button type="submit" class="btn-submit">{{ get_phrase('Submit') }}</button>
        </div>
    </form>
</div>

<script>
    function runWithJQuery(callback) {
        if (window.jQuery) {
            var attempts = 0;
            var interval = setInterval(function() {
                if (window.jQuery.fn.select2 || attempts > 40) {
                    clearInterval(interval);
                    callback(window.jQuery);
                }
                attempts++;
            }, 50);
        }
    }

    runWithJQuery(function($) {
        $(document).ready(function() {
            // Initialize Select2 on the dropdowns
            $('#city, #area, #filter_sort_by').select2({
                minimumResultsForSearch: 10
            });

            // Initial load of areas if a city is pre-selected
            @if($filter_city)
                var filter_city_id = {{ $filter_city }};
                var filter_area_id = {{ $filter_area ?? 0 }};
                loadAreas(filter_city_id, filter_area_id);
            @endif

            // Change event for City dropdown
            $('#city').on('change', function() {
                var cityId = $(this).val();
                loadAreas(cityId, 0);
            });

            // Reusable function to load areas
            function loadAreas(cityId, selectedAreaId) {
                var $areaSelect = $('#area');
                $areaSelect.html("<option value=''>Select an area</option>").trigger('change');

                if (!cityId) return;

                $.ajax({
                    url: '/ajax/productareas/' + cityId,
                    method: 'GET',
                    success: function(result) {
                        var areas = (typeof result === 'string' ? JSON.parse(result) : result);
                        $.each(areas, function(key, value) {
                            var isSelected = (value.id == selectedAreaId) ? 'selected' : '';
                            $areaSelect.append('<option value="' + value.id + '" ' + isSelected + '>' + value.area_name + '</option>');
                        });
                        $areaSelect.trigger('change');
                    }
                });
            }
        });
    });
</script>
