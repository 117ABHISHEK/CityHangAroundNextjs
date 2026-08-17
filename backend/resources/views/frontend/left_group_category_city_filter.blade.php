<div class="premium-filter-panel mb-4">
    <form method="GET" action="{{ route('group.category.city', ['category_slug' => $category->category_slug,'city_slug'=>$city->city_slug]) }}">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <!-- Results count -->
            <div class="results-count">
                <span class="count-label">Total Results:</span>
                <span class="count-number">{{ method_exists($groups, 'total') ? $groups->total() : $groups->count() }}</span>
            </div>
            
            <!-- Controls Group -->
            <div class="d-flex align-items-center gap-2 flex-wrap flex-grow-1 flex-sm-grow-0 justify-content-end">
                <div class="select-wrapper">
                    <select id="filter_sort_by" name="filter_sort_by" class="form-select premium-select">
                        <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>Newest first</option>
                        <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>Oldest first</option>
                    </select>
                </div>
                
                <a href="{{ route('group.category.city', ['category_slug' => $category->category_slug,'city_slug'=>$city->city_slug]) }}" 
                   class="btn btn-premium-outline">
                    Reset
                </a>
                
                <button type="submit" class="btn btn-premium-submit">
                    Apply
                </button>
            </div>
        </div>
    </form>

    <!-- Categories Tags Carousel -->
    @if(count($categories) > 0)
        <div class="tags-carousel-container mt-3">
            <div class="owl-carousel tag-carousel owl-theme">
                @foreach ($categories as $cat)
                    <div class="item">
                        <a href="{{ route('group.category.city',['category_slug'=>$cat->category_slug,'city_slug'=>$city->city_slug]) }}" 
                           class="tag-pill {{ $category->id == $cat->id ? 'active' : '' }}"> 
                            {{ $cat->category_name }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
/* Premium Filter Panel Styling */
.premium-filter-panel {
    background: #ffffff;
    border: 1px solid rgba(229, 231, 235, 0.7);
    border-radius: 14px;
    padding: 16px 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

.results-count {
    font-size: 0.95rem;
    color: #4b5563;
}

.count-label {
    font-weight: 500;
}

.count-number {
    font-weight: 700;
    color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
    padding: 2px 8px;
    border-radius: 6px;
    margin-left: 4px;
}

.premium-select {
    border-radius: 10px;
    border-color: #e5e7eb;
    font-size: 0.85rem;
    font-weight: 500;
    color: #374151;
    padding: 8px 36px 8px 12px;
    height: 38px;
    background-color: #f9fafb;
    cursor: pointer;
    min-width: 140px;
}

.premium-select:focus {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
}

.btn-premium-outline {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    color: #4b5563;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 10px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-premium-outline:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    color: #1f2937;
}

.btn-premium-submit {
    background: #ef4444;
    border: 1px solid #ef4444;
    color: #ffffff;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 8px 20px;
    border-radius: 10px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.btn-premium-submit:hover {
    background: #dc2626;
    border-color: #dc2626;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
}

/* Category Tags Carousel */
.tags-carousel-container {
    border-top: 1px solid #f3f4f6;
    padding-top: 12px;
}

.tag-pill {
    display: inline-block;
    padding: 6px 16px;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    color: #4b5563;
    border-radius: 9999px;
    font-size: 0.82rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.tag-pill:hover {
    background: #e5e7eb;
    color: #1f2937;
}

.tag-pill.active {
    background: #ef4444;
    border-color: #ef4444;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.2);
}

.tag-carousel .owl-nav button {
    width: 28px;
    height: 28px;
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 50% !important;
    color: #4b5563 !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    margin: 0 2px !important;
    font-size: 14px !important;
    transition: all 0.2s ease;
}

.tag-carousel .owl-nav button:hover {
    background: #ef4444 !important;
    border-color: #ef4444 !important;
    color: #ffffff !important;
}
</style>

<!-- Owl Carousel Initialization -->
<script>
    $(document).ready(function() {
        var owl = $('.tag-carousel');

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

        // Navigation visibility
        checkNavButtons();
        owl.on('changed.owl.carousel', function(event) {
            checkNavButtons();
        });

        function checkNavButtons() {
            var itemsCount = owl.find('.owl-item').length;
            var visibleItems = owl.find('.owl-item.active').length;
            var activeItems = owl.find('.owl-item.active');
            if (activeItems.length > 0) {
                var currentIndex = activeItems.first().index();
                owl.find('.owl-prev').toggle(currentIndex !== 0);
                owl.find('.owl-next').toggle(currentIndex + visibleItems < itemsCount);
            }
        }
    });
</script>

