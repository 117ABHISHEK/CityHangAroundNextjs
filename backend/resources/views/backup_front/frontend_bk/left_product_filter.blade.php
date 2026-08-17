<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropdown with Select2</title>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'" />
    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    </noscript>

    <!-- Include Select2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" defer></script>
</head>
<body>

<!-- <div class="row rightSideBarToggler d-hidden">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body text-end">
                <button class="btn" onclick="toggleRightSideBar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</div> -->


    <div class="widget">
    <div class="col-12">
                    <strong>Total Results Found left_product_filter.blade: {{$products->count() }}</strong>
                    Results
                </div>
    <h2>Filter by</h2>
    <div class="row">
    <form method="GET" action="{{ route('allproducts')}}">
    <div class="form-group">

    <!-- Select City Dropdown -->
    <select id="city" name="city" style="width: 100%;" class="selectpicker form-control @error('city') is-invalid @enderror">
        <option value="">Select a city</option>
        
    @foreach ($all_product_cities as $key => $city)
        <option value="{{$city->id}}" {{ $filter_city == $city->id ? 'selected' : '' }}>{{$city->city_name}}</option>
    @endforeach
    </select>

    <br><br>

    <!-- Select Area Dropdown -->
    <select id="area" name="area" style="width: 100%;" class="selectpicker form-control @error('area') is-invalid @enderror">
        <option value="">Select an area</option>
    </select>

    <br><br>

    <!-- Sorting Dropdown -->
    <select id="filter_sort_by" name="filter_sort_by"  style="width: 100%;" class="selectpicker form-control @error('sort-dropdown') is-invalid @enderror">
        <option value="newest" {{ $filter_sort_by == "newest" ? 'selected' : '' }}>Newest</option>
        <option value="oldest" {{ $filter_sort_by == "oldest" ? 'selected' : '' }}>Oldest</option>
    </select>

</div>

   

    <!-- Tags Section -->
    <div class="tags_left">
        <ul>
        @foreach ($all_printable_categories as $key => $category)
        <li ><a href="{{ route('product.category',['category_slug'=>$category->product_category_slug]) }}"> {{ $category->product_category_name }}</a></li>
         @endforeach   
        </ul>
    </div>
    <a class="btn btn-sm btn-outline-primary rounded" href="{{ route('allproducts') }}">
        Reset
    </a>
    <button type="submit" class="btn btn-primary d-block w-100">Submit</button>
    </form>
</div>
</div>

<script>
    $(document).ready(function() {
        // Initialize Select2 on the dropdowns
        $('#city, #area, #filter_sort_by').select2({
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
            $('#area').empty();
            $('#area').append('<option value="">Select an area</option>');
            sortedItems.forEach(item => {
                $('#area').append(`<option value="${item.id}">${item.name}</option>`);
            });

            // Reinitialize Select2 for the updated dropdown
            $('#area').select2({
                placeholder: 'Select an area'
            });
        });
    });
    @if($filter_city)
             filter_city_id= {{ $filter_city}},
            //(filter_city_id);
             ajax_url_initial_areas = '/ajax/productareas/'+filter_city_id;

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
                success: function(result){
            //console.log(result);
            $('#area').html("<option selected value='0'>Select an area</option>");
            $.each(JSON.parse(result), function(key, value) {
                //alert(value.id);
                var city_id = value.id;
                var city_name = value.area_name;
                if(city_id === {{ $filter_area }})
                        {
                            $('#area').append('<option value="'+ city_id +'" selected>' + city_name + '</option>');
                        }
                        else
                        {
                            $('#area').append('<option value="'+ city_id +'">' + city_name + '</option>');
                        }
                //$('#area').append('<option value="'+ city_id +'" {{ $filter_area == "'+city_id+'" ? 'selected' : '' }}>' + city_name + '</option>');
            });
            
        }});
            @endif
    $('#city').on('change', function() {

$('#area').html("<option selected value='0'>Select Area</option>");


if(this.value > 0)
{
    var ajax_url = '/ajax/productareas/' + this.value;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery.ajax({
        url: ajax_url,
        method: 'get',
        data: {
        },
        success: function(result){
            //console.log(result);
            $('#area').html("<option selected value='0'>Select an area</option>");
            $.each(JSON.parse(result), function(key, value) {
                var city_id = value.id;
                var city_name = value.area_name;
                $('#area').append('<option value="'+ city_id +'" {{ $filter_area == "'+city_id+'" ? 'selected' : '' }}>' + city_name + '</option>');
            });
            
        }});
}

});



           


            

</script>

</body>
</html>
