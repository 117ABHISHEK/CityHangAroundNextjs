<div class="widget_top_filter">
    <div class="col-12">
        <strong>Total Results Found : {{$products->count() }}</strong>
        Results
    </div>
    <div class="row">
        <form method="GET" action="https://www.cityhangaround.com/deals" class="container" style="z-index:-1">
           <div class="row gx-2 gy-2 align-items-start">
              <!-- City Dropdown -->
              <div class="col-4 col-md-3">
                <select id="city_filter" name="city_filter" class="form-control form-control-sm">
                  <option value="">Select a city</option>
                  <option value="6332">Agar</option>
                  <option value="2969" selected>Ahmedabad</option>
                  <option value="6328">Alirajpur</option>
                  <option value="6473">Balaghat</option>
                  <option value="14554">Chandpur</option>
                  <option value="10757">Chandpur Uttar Pradesh</option>
                  <option value="11550">Deoria</option>
                  <option value="9199">Jaipur</option>
                  <option value="11798">Noida</option>
                </select>
              </div>
            
              <!-- Area Dropdown -->
              <div class="col-4 col-md-3">
                <select id="area_filter" name="area_filter" class="form-control form-control-sm">
                  <option value="0" selected>Select an area</option>
                  <option value="52540">Bapu Nagar</option>
                  <option value="52985">Navrangpura</option>
                  <option value="52631">Prahlad Nagar</option>
                </select>
              </div>
            
              <!-- Sort Dropdown -->
              <div class="col-4 col-md-2">
                <select id="filter_sort_by" name="filter_sort_by" class="form-control form-control-sm">
                  <option value="newest" selected>Newest</option>
                  <option value="oldest">Oldest</option>
                </select>
              </div>
            
              <!-- Reset Button -->
              <div class="col-6 col-md-2 d-flex">
                <a href="https://www.cityhangaround.com/deals" class="btn-sm w-100 btn btn-primary py-2">Reset</a>
              </div>
            
              <!-- Submit Button -->
              <div class="col-6 col-md-2 d-flex">
                <button type="submit" class="btn btn-primary btn-sm w-100 py-2">Submit</button>
              </div>
            </div>
        </form>



        <!-- Tags Section -->
        <div class="tags_Outer">

            <div class="owl-carousel tag-carousel owl-theme">



                @foreach ($all_printable_categories as $key => $category)

                <div class="item">
                    <a href="{{ route('product.category',['category_slug'=>$category->product_category_slug]) }}">
                    {{ $category->product_category_name }}</a>
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

<script>
$(document).ready(function() {
    // Initialize Select2 on the dropdowns
    $('#city_filter, #area_filter, #filter_sort_by').select2({
        placeholder: function() {
            return $(this).data('placeholder');
        }
    });


    // Sorting functionality
    $('#filter_sort_by').on('change', function() {
        const sortType = $(this).val();
        let sortedItems;

        if (sortType === "newest") {
            sortedItems = items.sort((a, b) => new Date(b.added) - new Date(a.added));
        } else if (sortType === "oldest") {
            sortedItems = items.sort((a, b) => new Date(a.added) - new Date(b.added));
        } else if (sortType === "highest-rated") {
            // Example sort for highest rated (you can add ratings data)
            sortedItems = items.sort((a, b) => b.id - a.id);
        } else if (sortType === "lowest-rated") {
            // Example sort for lowest rated (you can add ratings data)
            sortedItems = items.sort((a, b) => a.id - b.id);
        }

        // Update the Area dropdown dynamically
        $('#area_filter').empty();
        $('#area_filter').append('<option value="">Select an area</option>');
        sortedItems.forEach(item => {
            $('#area').append(`<option value="${item.id}">${item.name}</option>`);
        });

        // Reinitialize Select2 for the updated dropdown
        $('#area_filter').select2({
            placeholder: 'Select an area'
        });
    });
});
@if($filter_city)
filter_city_id= {{ $filter_city}},
    //(filter_city_id);
    ajax_url_initial_areas = '/ajax/productareas/' + filter_city_id;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
jQuery.ajax({
    url: ajax_url_initial_areas,
    method: 'get',
    data: {

    },
    success: function(result) {
        //console.log(result);
        $('#area_filter').html("<option selected value='0'>Select an area</option>");
        $.each(JSON.parse(result), function(key, value) {
            //alert(value.id);
            var city_id = value.id;
            var city_name = value.area_name;
            if(city_id === {{ $filter_area }}){
                $('#area_filter').append('<option value="' + city_id + '" selected>' + city_name +
                    '</option>');
            } else {
                $('#area_filter').append('<option value="' + city_id + '">' + city_name + '</option>');
            }
            //$('#area').append('<option value="'+ city_id +'" {{ $filter_area == "'+city_id+'" ? 'selected' : '' }}>' + city_name + '</option>');
        });

    }
});
@endif
$('#city_filter').on('change', function() {

    $('#area_filter').html("<option selected value='0'>Select Area</option>");


    if (this.value > 0) {
        var ajax_url = '/ajax/productareas/' + this.value;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: ajax_url,
            method: 'get',
            data: {},
            success: function(result) {
                //console.log(result);
                $('#area_filter').html("<option selected value='0'>Select an area</option>");
                $.each(JSON.parse(result), function(key, value) {
                    var city_id = value.id;
                    var city_name = value.area_name;
                    $('#area_filter').append('<option value="'+ city_id +'" {{ $filter_area == "'+city_id+'" ? 'selected' : '' }}>' + city_name + '</option>');
                });

            }
        });
    }

});
</script>

</body>
