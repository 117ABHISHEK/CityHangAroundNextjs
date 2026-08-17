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
</head>
<body>

<div class="widget_top_filter">
    <div class="col-12">
        <strong>Total Results Found: {{$products->count()}}</strong> Results
    </div>
    <div class="row">
        <form method="GET" action="{{ route('product.category.city', ['category_slug' => $category->product_category_slug, 'city_slug' => $city->city_slug]) }}">
            <div class="form-group">
                <div class="form-group-col">
                    <!-- Sorting Dropdown -->
                    <select id="filter_sort_by" name="filter_sort_by" class="selectpicker form-control">
                        <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>Oldest</option>
                        <option value="highest-rated" {{ $filter_sort_by == "highest-rated" ? 'selected' : '' }}>Highest Rated</option>
                        <option value="lowest-rated" {{ $filter_sort_by == "lowest-rated" ? 'selected' : '' }}>Lowest Rated</option>
                    </select>
                </div>
                <div class="form-group-col">
                    <a class="reset-btn btn btn-outline-primary rounded" href="{{ route('product.category.city', ['category_slug' => $category->product_category_slug, 'city_slug' => $city->city_slug]) }}">
                        Reset
                    </a>
                </div>
                <div class="form-group-col">
                    <button type="submit" class="btn btn-primary d-block w-100">Submit</button>
                </div>
            </div>
        </form>

        <!-- Tags Section -->
        <div class="tags_Outer">
            <div class="owl-carousel tag-carousel owl-theme">
                @foreach ($all_categories as $category)
                <div class="item">
                    <a href="{{ route('product.category.city', ['category_slug' => $category->product_category_slug, 'city_slug' => $city->city_slug]) }}">
                        {{ $category->product_category_name }} 
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#filter_sort_by').select2();

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

</body>
</html>
