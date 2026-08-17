<div class="widget_top_filter">
    <div class="col-12 mb-2">
        <strong>{{ get_phrase('Total Results Found') }} : {{ $groups->count() }}</strong>
    </div>
    <div class="row">
        @if(isset($all_printable_categories) && $all_printable_categories->isNotEmpty())
        <div class="tags_Outer">
            <div class="owl-carousel tag-carousel owl-theme">
                @foreach ($all_printable_categories as $category)
                    <div class="item">
                        <a href="{{ route('category.group', ['category_slug' => $category->category_slug]) }}">
                            {{ $category->category_name }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <script>
        $(document).ready(function() {
            var owl = $('.tag-carousel');
            if (owl.length > 0) {
                owl.owlCarousel({
                    margin: 10, nav: true, dots: false, autoWidth: true, loop: false,
                    responsive: { 0: { items: 1 }, 600: { items: 3 }, 1000: { items: 5 } }
                });

                function checkNavButtons() {
                    var itemsCount = owl.find('.owl-item').length;
                    var visibleItems = owl.find('.owl-item.active').length;
                    var currentIndex = owl.find('.owl-item.active').first().index();
                    (currentIndex === 0) ? $('.owl-prev').hide() : $('.owl-prev').show();
                    (currentIndex + visibleItems >= itemsCount) ? $('.owl-next').hide() : $('.owl-next').show();
                }

                checkNavButtons();
                owl.on('changed.owl.carousel', checkNavButtons);
            }
        });
        </script>
    </div>
</div>