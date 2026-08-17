<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Filter</title>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" defer></script>

    <!-- Include Owl Carousel CSS & JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    </noscript>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" defer></script>

    <style>
        .filter-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: end;
        }
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        .search-group {
            flex: 2;
            min-width: 300px;
        }
        .search-input-group {
            display: flex;
            gap: 10px;
        }
        .search-input {
            flex: 1;
        }
        .btn-search {
            white-space: nowrap;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }
        
        .btn-search:hover {
            background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
            color: white;
        }
        
        .btn-search:active {
            transform: translateY(0);
        }
        .filter-label {
            font-weight: 700;
            margin-bottom: 8px;
            color: #2c3e50;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .select2-container {
            width: 100% !important;
        }
        
        .select2-container--default .select2-selection--single {
            border: 2px solid #e9ecef;
            border-radius: 6px;
            height: 40px;
            transition: all 0.3s ease;
        }
        
        .select2-container--default .select2-selection--single:hover {
            border-color: #667eea;
        }
        
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
            padding-left: 12px;
            color: #495057;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }
        .reset-section {
            margin-top: 20px;
            text-align: center;
            padding: 15px 0;
            border-top: 1px solid #e9ecef;
        }
        
        .reset-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .reset-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            color: white;
            text-decoration: none;
        }
        
        .reset-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
        }
        
        .reset-btn i {
            margin-right: 8px;
            font-size: 16px;
            transition: transform 0.3s ease;
        }
        
        .reset-btn:active i {
            animation: spin 0.5s linear;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
            }
            .filter-group, .search-group {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="widget_top_filter">
    <div class="col-12 mb-3">
        <strong>Total Results Found: {{$mypages->total()}}</strong> Results
    </div>
    
    <div class="filter-section">
        <form method="GET" action="{{ route('page.category.city.area', ['category_slug' => $category->category_slug, 'city_slug' => $city->city_slug,'area_slug' => $area->area_slug]) }}" id="filterForm">
            <div class="filter-row">
                <!-- Area Filter -->
                <div class="filter-group">
                    <label class="filter-label">Area</label>
                    <select id="filter_area" name="area" class="form-control select2">
                        <option value="0">All Areas</option>
                        @foreach($all_cities as $cityItem)
                            @if($cityItem->id == $city->id)
                                @foreach($cityItem->areas as $areaItem)
                                    <option value="{{ $areaItem->id }}" {{ $filter_area == $areaItem->id ? 'selected' : '' }}>
                                        {{ $areaItem->area_name }}
                                    </option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Category Filter -->
                <div class="filter-group">
                    <label class="filter-label">Category</label>
                    <select id="filter_category" name="category" class="form-control select2">
                        <option value="0">All Categories</option>
                        @foreach($all_categories as $cat)
                            @if($cat->category_parent_id == null)
                                <option value="{{ $cat->id }}" {{ $filter_category == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Subcategory Filter -->
                <div class="filter-group">
                    <label class="filter-label">Subcategory</label>
                    <select id="filter_subcategory" name="subcategory" class="form-control select2">
                        <option value="0">All Subcategories</option>
                    </select>
                </div>

                <!-- Sort By -->
                <div class="filter-group">
                    <label class="filter-label">Sort By</label>
                    <select id="filter_sort_by" name="filter_sort_by" class="form-control select2">
                        <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>Oldest</option>
                        <option value="highest-rated" {{ $filter_sort_by == "highest-rated" ? 'selected' : '' }}>Highest Rated</option>
                        <option value="lowest-rated" {{ $filter_sort_by == "lowest-rated" ? 'selected' : '' }}>Lowest Rated</option>
                    </select>
                </div>

                <!-- Search -->
                <div class="search-group">
                    <label class="filter-label">Search</label>
                    <div class="search-input-group">
                        <input type="text" id="filter_search" name="search" class="form-control search-input" 
                               placeholder="Search businesses, services..." value="{{ $filter_search }}">
                        <button type="submit" class="btn btn-primary btn-search">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </div>

            <div class="reset-section">
                <a class="reset-btn" href="{{ route('page.category.city.area', ['category_slug' => $category->category_slug, 'city_slug' => $city->city_slug,'area_slug' => $area->area_slug]) }}">
                    <i class="fas fa-undo-alt"></i> Reset Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Tags Section -->
    <div class="tags_Outer">
        <div class="owl-carousel tag-carousel owl-theme">
            @foreach ($all_categories as $cat)
            <div class="item">
                <a href="{{ route('page.category.city.area', ['category_slug' => $cat->category_slug, 'city_slug' => $city->city_slug,'area_slug' => $area->area_slug]) }}">
                    {{ $cat->category_name }} 
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Flag to prevent multiple form submissions
    var isSubmitting = false;
    
    // Initialize Select2
    $('.select2').select2({
        placeholder: "Select an option",
        allowClear: true
    });

    // Initialize Owl Carousel
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

    // Category change handler
    $('#filter_category').on('change', function() {
        var categoryId = $(this).val();
        var subcategorySelect = $('#filter_subcategory');
        
        // Clear subcategory options
        subcategorySelect.html('<option value="0">All Subcategories</option>');
        
        if (categoryId && categoryId !== '0') {
            // Show loading state
            subcategorySelect.prop('disabled', true);
            
            // Fetch subcategories
            $.ajax({
                url: '{{ route("json.subcategories", ["category_id" => ":category_id"]) }}'.replace(':category_id', categoryId),
                method: 'GET',
                success: function(data) {
                    if (data && data.length > 0) {
                        data.forEach(function(subcategory) {
                            subcategorySelect.append('<option value="' + subcategory.id + '">' + subcategory.category_name + '</option>');
                        });
                    }
                    subcategorySelect.prop('disabled', false);
                    // Mark as programmatic change to prevent auto-submit
                    subcategorySelect.data('programmatic', true);
                    subcategorySelect.trigger('change');
                    subcategorySelect.removeData('programmatic');
                },
                error: function(xhr, status, error) {
                    console.log('Error fetching subcategories:', error);
                    subcategorySelect.prop('disabled', false);
                    // Show user-friendly error message
                    subcategorySelect.html('<option value="0">Error loading subcategories</option>');
                }
            });
        }
    });

    // Auto-submit form on filter changes (except search and subcategory)
    $('#filter_area, #filter_category, #filter_sort_by').on('change', function() {
        // Prevent infinite loop by checking if this is a programmatic change
        if (!$(this).data('programmatic') && !isSubmitting) {
            isSubmitting = true;
            $('#filterForm').submit();
        }
    });
    
    // Manual submit for subcategory to avoid conflicts with dynamic loading
    $('#filter_subcategory').on('change', function() {
        if (!$(this).data('programmatic') && !isSubmitting) {
            isSubmitting = true;
            $('#filterForm').submit();
        }
    });

    // Handle search button click
    $('#filterForm').on('submit', function(e) {
        // If search is empty, remove it from the form
        if ($('#filter_search').val().trim() === '') {
            $('#filter_search').remove();
        }
        // Reset submission flag after a short delay
        setTimeout(function() {
            isSubmitting = false;
        }, 1000);
    });

    // Initialize subcategories if category is pre-selected
    if ($('#filter_category').val() && $('#filter_category').val() !== '0') {
        // Mark as programmatic to prevent auto-submit during initialization
        $('#filter_category').data('programmatic', true);
        $('#filter_category').trigger('change');
        $('#filter_category').removeData('programmatic');
    }
});
</script>
</body>
</html>

