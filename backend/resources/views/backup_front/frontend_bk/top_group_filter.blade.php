<div class="widget_top_filter">
    <div class="col-12">
        <strong>Total Results Found : {{$groups->count() }}</strong>
        Results
    </div>
    <div class="row">
     
        <!-- Tags Section -->
        <div class="tags_Outer">

            <div class="owl-carousel tag-carousel owl-theme">



                @foreach ($all_printable_categories as $key => $category)

                <div class="item">
                    <a href="{{ route('category.group',['category_slug'=>$category->category_slug]) }}">
                    {{ $category->category_name }}</a>
                </div>

                @endforeach

            </div>





        </div>
        <script>
        $(document).ready(function() {
            var owl = $('.tag-carousel');

            // Initialize Owl Carousel
            owl.owlCarousel({
                margin: 10,
                nav: true,
                dots: false,
                autoWidth:true,
                loop: false, // Set loop to false for proper scroll finish detection
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 3
                    },
                    1000: {
                        items: 4
                    }
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

                // Hide "prev" button if we're at the start
                if (currentIndex === 0) {
                    $('.owl-prev').hide();
                } else {
                    $('.owl-prev').show();
                }

                // Hide "next" button if we're at the end
                if (currentIndex + visibleItems >= itemsCount) {
                    $('.owl-next').hide();
                } else {
                    $('.owl-next').show();
                }
            }
        });
        </script>




    </div>
</div>


</body>

</html>