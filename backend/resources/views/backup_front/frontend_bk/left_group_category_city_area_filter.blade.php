<div class="widget_top_filter">
    <div class="row align-items-center">
        <div class="col-md-6">
            <strong>Total Results Found: {{ $groups->count() }}</strong>
        </div>

        <!-- Sorting Dropdown -->
        <div class="row g-2 align-items-end">
    <form method="GET" action="{{ route('group.category.city.area', ['city_slug'=>$city->city_slug,'category_slug'=>$category->category_slug,'area_slug' => $area->area_slug]) }}">
        <div class="row">
            <!-- Sorting Dropdown -->
            <div class="col-md-4">
                <select id="filter_sort_by" name="filter_sort_by" class="selectpicker form-control">
                    <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>Oldest</option>
                </select>
            </div>

            <!-- Reset Button -->
            <div class="col-md-2">
                <a href="{{ route('group.category.city.area', ['city_slug'=>$city->city_slug,'category_slug'=>$category->category_slug,'area_slug' => $area->area_slug]) }}" 
                   class="btn btn-outline-primary w-100 py-2">
                    Reset
                </a>
            </div>

            <!-- Submit Button -->
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 py-2">Submit</button>
            </div>
        </div>
    </form>
</div>

    </div>

    <!-- Tags Section -->
    <div class="tags_Outer mt-3">
        <div class="owl-carousel tag-carousel owl-theme">
            @foreach ($categories as $key => $category)
                <div class="item">
                    <a href="{{ route('group.category.city.area', ['city_slug'=>$city->city_slug,'category_slug'=>$category->category_slug,'area_slug' => $area->area_slug]) }}">
                        {{ $category->category_name }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Owl Carousel & Select2 Initialization -->
<script>
    $(document).ready(function() {
        // Initialize Select2 for better styling
        $('.select2').select2();

        var owl = $('.tag-carousel');

        // Initialize Owl Carousel
        owl.owlCarousel({
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

        // On carousel initialized
        checkNavButtons();

        // When carousel is changed
        owl.on('changed.owl.carousel', function(event) {
            checkNavButtons();
        });

        function checkNavButtons() {
            var itemsCount = $('.owl-item').length; // Total items in the carousel
            var visibleItems = owl.find('.owl-item.active').length; // Visible items at a time
            var currentIndex = owl.find('.owl-item.active').first().index(); // Index of first visible item

            $('.owl-prev').toggle(currentIndex !== 0);
            $('.owl-next').toggle(currentIndex + visibleItems < itemsCount);
        }
    });
</script>