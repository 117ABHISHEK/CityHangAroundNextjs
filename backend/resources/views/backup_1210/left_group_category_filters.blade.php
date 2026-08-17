<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropdown with Select2 and Owl Carousel</title>

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
</head>
<body>

<div class="widget_top_filter">
    <div class="col-12">
        <strong>Total Results Found: 0</strong> Results
    </div>
    <div class="row">
        <form method="GET" action="{{ route('category.group', ['category_slug' => $category->category_slug]) }}">
            <div class="form-group">
                <div class="form-group-col">
                    <select id="city" name="city" class="selectpicker form-control">
                        <option value="">Select a city</option>
                        @foreach ($all_group_cities as $city)
                        <option value="{{$city->id}}" {{ $filter_city == $city->id ? 'selected' : '' }}>
                            {{$city->city_name}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-col">
                    <select id="area" name="area" class="selectpicker form-control">
                        <option value="">Select an area</option>
                    </select>
                </div>
                <div class="form-group-col">
                    <select id="filter_sort_by" name="filter_sort_by" class="selectpicker form-control">
                        <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>Oldest</option>
                    </select>
                </div>
                <div class="form-group-col">
                    <a class="reset-btn btn btn-outline-primary rounded" href="{{ route('category.group', ['category_slug' => $category->category_slug]) }}">
                        Reset
                    </a>
                </div>
                <div class="form-group-col">
                    <button type="submit" class="btn btn-primary d-block w-100">Submit</button>
                </div>
            </div>
        </form>

        <!-- Owl Carousel for Category Tags -->
        <div class="tags_Outer">
            <div class="owl-carousel tag-carousel owl-theme">
                @foreach ($all_categories as $category)
                <div class="item">
                    <a href="{{ route('category.group',['category_slug'=>$category->category_slug]) }}">
                        {{ $category->category_name }}
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize Select2 on dropdowns
    $('#city, #area, #filter_sort_by').select2();
    
    // Initialize Owl Carousel for category tags
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

    // Load areas based on selected city
    $('#city').on('change', function() {
        var city_id = $(this).val();
        $('#area').html("<option value=''>Select an area</option>");

        if (city_id) {
            $.ajax({
                url: '/ajax/groupareas/' + city_id,
                method: 'GET',
                success: function(result) {
                    $.each(JSON.parse(result), function(key, value) {
                        $('#area').append('<option value="'+ value.id +'">'+ value.area_name +'</option>');
                    });
                }
            });
        }
    });

    // Preload areas if a city is already selected
    @if($filter_city)
        var filter_city_id = {{ $filter_city }};
        $.ajax({
            url: '/ajax/groupareas/' + filter_city_id,
            method: 'GET',
            success: function(result) {
                $('#area').html("<option value=''>Select an area</option>");
                $.each(JSON.parse(result), function(key, value) {
                    var selected = (value.id == {{ $filter_area }}) ? 'selected' : '';
                    $('#area').append('<option value="'+ value.id +'" '+ selected +'>'+ value.area_name +'</option>');
                });
            }
        });
    @endif
});
</script>

</body>
</html>