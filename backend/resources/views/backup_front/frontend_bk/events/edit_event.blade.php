@php
    $event = \App\Models\Event::where('id',$event_id)->first();
@endphp

<link href="{{asset('assets/frontend/leafletjs/leaflet.css')}}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .select2-container{
    z-index:100000;
}
.select2-container--default .select2-selection--multiple{
    /* height: 40px; */
    padding: 5px;
}
.select2-search__field{
    height: 27px!important;
    padding: 5px;
}
.modal:nth-of-type(even) {
    z-index: 1052 !important;
}
.modal-backdrop.show:nth-of-type(even) {
    z-index: 1051 !important;
}
.bigdrop {
    width: 600px !important;
}
.select2{

    width:100%!important;
}
.leaflet-container {
            height: 400px;width: 600px;max-width: 100%;max-height: 100%;
        }
        
.select2-results__option {
   
    font-weight: bold;
}
/* Fix for cursor visibility in Select2 search box */
.select2-container--default .select2-search--dropdown .select2-search__field {
    color: #333; /* Set text color (Dark Grey) */
    background-color: #fff; /* Set background color (White) */
    font-size: 14px; /* Adjust font size */
    line-height: 1.5; /* Fix line-height if necessary */
    caret-color: auto; /* Ensures the cursor (caret) is visible */
}

/* Ensure placeholder text is distinguishable */
.select2-container--default .select2-search--dropdown .select2-search__field::placeholder {
    color: #aaa; /* Light Grey for placeholder text */
    font-style: italic; /* Optional for differentiation */
}

/* Prevent parent containers from hiding overflow */
.select2-container {
    overflow: visible !important;
}


        .faq-form-group {
            margin-bottom: 20px;
        }

</style>
<div class="event-content">
<div class="event-tab bg-white border rounded p-3 pb-1">          
<form class="event-form" action="{{ route('event.update',$event->id) }}" method="POST" enctype="multipart/form-data" >
    @csrf

    <div class="entry-header d-flex mb-10 justify-content-between">
        <div class="ava-info d-flex align-items-center">
            <div class="flex-shrink-0">
                <img src="{{get_user_image(Auth()->user()->photo, 'optimized')}}" class="rounded-circle user_image_show_on_modal" alt="...">
            </div>
            <div class="ava-desc ms-2">
                <h3 class="mb-0 h6">{{ Auth::user()->name }}</h3>
            </div>
        </div>
        <div class="post-controls dropdown">
            <select name="privacy" id="privacy" class="form-control bg-secondary">
                <option value="public" @php $event->privacy=="public"?"selected":"" @endphp>Public</option>
                <option value="private" @php $event->privacy=="private"?"selected":"" @endphp>Private</option>
            </select>
        </div>
    </div>
    @if ($errors->any())
                              <div class="alert alert-danger">
                                  <ul>
                                      @foreach ($errors->all() as $error)
                                          <li>{{ $error }}</li>
                                      @endforeach
                                  </ul>
                              </div>
                          @endif
                          @if (auth()->user()->user_role=="admin")
     <div class="form-group mt-3">
        <label for="#">{{ get_phrase('Event Status') }}</label>
        <select class="selectpicker form-control @error('event_status') is-invalid @enderror" name="event_status">

                                    <option value="1" {{ (1==$event->event_status) ? 'selected' : '' }}>Submitted</option>
                                    <option value="2" {{ (2==$event->event_status) ? 'selected' : '' }}>Published</option>
                                    <option value="3" {{ (3==$event->event_status) ? 'selected' : '' }}>Suspended</option>

                                </select>
    </div>
    @endif
    <div class="form-group mt-3">
        <label for="#">{{ get_phrase('Event Name') }}</label>
        <input type="text" name="eventname" value="{{ $event->title }}" placeholder="Enter your event name">
    </div>
    <?php $category_array=explode(',', $event->category_id);?>
    <div class="form-group mt-3">
        <label for="#">{{ get_phrase('Parent Category') }}</label>
        <select name="parent" id="parent" class="selectpicker form-control @error('parent') is-invalid @enderror"   required>
        <option value="0">Select Parent Category</option>
                                @foreach($parent as $key => $printable_category)
                                    <option value="{{ $printable_category->id }}" {{ (in_array($printable_category->id, $category_array)) ? 'selected' : '' }}>{{ $printable_category->category_name }}</option>
                                @endforeach
        </select>
        <a  class="text-info pl-2 float-left" onclick="showparentcategorymodel();" >
                                                <i class="far fa-add"></i>
                                                Click here to add Parent category(If not in list)
                                            </a>
    </div>
    <div class="form-group mt-3">
        <label for="#">{{ get_phrase('Tag Category') }}</label>
        <select name="category[]" id="category" class="selectpicker form-control @error('category') is-invalid @enderror"  multiple required>
        @foreach (\App\Models\Eventcategory::all() as $category )
            <option value="{{ $category->id }}"{{ (in_array($category->id, $category_array)) ? 'selected' : '' }}> {{ $category->category_name }} </option>
            @endforeach
        </select>
        <a  class="text-info pl-2 float-left" onclick="showcategorymodel();" >
                                                <i class="far fa-add"></i>
                                                Click here to add category(If not in list)
                                            </a>
    </div>
    <div class="row">
        <div class="form-group col-md-6 col-lg-6 col-sm-12">
            <label for="#">{{ get_phrase('Event Date') }}</label>
            <input type="date" name="eventdate" value="{{ $event->event_date }}" placeholder="Event Date">
        </div>
        <div class="form-group col-md-6 col-lg-6 col-sm-12">
            <label for="#">{{ get_phrase('Event Time') }}</label>
            <input type="time" name="eventtime" value="{{ $event->event_time }}" placeholder="Event Time">
        </div>
    </div>
    <div class="row">
    <div class="form-group col-md-6 col-lg-6 col-sm-12">
        <label for="#">{{ get_phrase('State') }}</label>
        <select name="state" id="state" class="selectpicker form-control @error('state') is-invalid @enderror" required>
        @foreach ($all_states as $state )
            <option value="{{ $state->id }}"{{ ($state->id==$event->state_id) ? 'selected' : '' }}> {{ $state->state_name }} </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-6 col-lg-6 col-sm-12">
        <label for="#">{{ get_phrase('City') }}</label>
        <select name="city" id="city" class="selectpicker form-control @error('city') is-invalid @enderror" required>
        @foreach ($all_cities as $city )
            <option value="{{ $city->id }}"{{ ($city->id==$event->city_id) ? 'selected' : '' }}> {{ $city->city_name }} </option>
            @endforeach
        </select>
        <a  class="text-info pl-2 float-left" onclick="showcitymodel();" >
                                                <i class="far fa-add"></i>
                                                Suggest City
                                            </a>
    </div>
    </div>
   <div class="row">
    <div class="form-group col-md-6 col-lg-6 col-sm-12">
        <label for="#">{{ get_phrase('Area') }}</label>
        <select name="area" id="area" class="selectpicker form-control @error('area') is-invalid @enderror"  required>
        @foreach ($all_areas as $area )
            <option value="{{ $area->id }}"{{ ($area->id==$event->area_id) ? 'selected' : '' }}> {{ $area->area_name }} </option>
            @endforeach    
    </select>
        <a  class="text-info pl-2 float-center" onclick="showareamodel();" data-bs-toggle="modal">
        <i class="far fa-add"></i> Suggest Area</a>
        @error('type')
                                                            <div class="alert alert-danger">{{$message}}</div>
                                                            @enderror
    </div>
  <div class="form-group col-md-6 col-lg-6 col-sm-12">
  <label for="#">{{ get_phrase('Location') }}</label>
  <input type="text" name="eventlocation" value="{{ $event->location }}" placeholder="Enter your location">
  </div>
</div>
   
    <div class="form-group">
        <label for="#">{{ get_phrase('Event Description') }}</label>
        <textarea name="description" class="content" id="description" cols="30" rows="10" placeholder="Description"> {{ $event->description }}</textarea>
    </div>

    <div class="form-group">
        <label for="#">{{ get_phrase('Previous Uploaded Image') }}</label> <br>
        <img src="{{ viewImage('event',$event->banner,'coverphoto') }}" class="w-50"
                            alt="No Cover Photo Found">
    </div>
    
    <div class="form-group">
        <label for="#">{{ get_phrase('Cover Photo') }}</label>
        <div class="mb-3 mt-1 text-center">
            <input type="file" id="coverphoto" name="coverphoto" class="form-control w-100" name="profile_photo" accept="image/*">
        </div>
    </div>
    <div class="inline-btn mt-5">
        <button class="btn btn-primary w-100" type="submit" onclick="document.getElementById('description').value=CKEDITOR.instances.description.getData(); CKEDITOR.instances.description.destroy()">{{get_phrase('Update Event')}}</button>
    </div>
</form>
</div>
</div>

@include('frontend.initialize')


<!-- Modal categories -->
<div class="modal fade" id="parentcategoriesModal" tabindex="-1" role="dialog" aria-labelledby="parentcategoriesModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ get_phrase('Add Category') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
                <div class="modal-body">
              
               

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="parent_category_name" class="text-black">Category</label>
                                <input id="parent_category_name" type="text" class="form-control @error('parent_category_name') is-invalid @enderror" name="parent_category_name" value="{{ old('parent_category_name') }}" autofocus>
                                @error('parent_category_name')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       

                        

                       

                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white" onclick="submitparentcategory();">
                                    Submit
                                </button>
                            </div>
                        </div>

                  
               
    </div>
    </div>
    </div>
    </div>


<!-- Modal categories -->
<div class="modal fade" id="categoriesModal" tabindex="-1" role="dialog" aria-labelledby="categoriesModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ get_phrase('Add Category') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
                <div class="modal-body">
              
               

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="category_name" class="text-black">Category</label>
                                <input id="category_name" type="text" class="form-control @error('category_name') is-invalid @enderror" name="category_name" value="{{ old('category_name') }}" autofocus>
                                @error('category_name')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="text-black" for="category_parent_id">Parent</label>
                            <div class="col-md-12">
                            
                            <select class="selectpicker form-control @error('category_parent_id') is-invalid @enderror" name="category_parent_id" id="category_parent_id">
                                <option value="0">Add Category</option>
                                @foreach($printable_categories as $key => $printable_category)
                                    <option value="{{ $printable_category->id }}">{{ $printable_category->category_name }}</option>
                                @endforeach
                            </select>
                            @error('category_parent_id')
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            </div>
                        </div>

                        

                       

                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white" onclick="submitcategory();">
                                    Submit
                                </button>
                            </div>
                        </div>

                  
               
    </div>
    </div>
    </div>
    </div>

    <div class="modal fade" id="cityModal" tabindex="-1" role="dialog" aria-labelledby="cityModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ get_phrase('Add City') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
                <div class="modal-body">
               
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="city_name" class="text-black">City Name</label>
                                <input id="city_name" type="text" class="form-control @error('city_name') is-invalid @enderror" name="city_name" value="{{ old('city_name') }}" autofocus>
                                @error('city_name')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        

                       

                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white" onclick="submitcity();">
                                    Submit
                                </button>
                            </div>
                        </div>

                   
               
    </div>

    </div>
    </div>
    </div>

<div class="modal fade" id="areaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ get_phrase('Add Area') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="area_name" class="text-black">Area Name</label>
                                <input id="area_name" type="text" class="form-control @error('area_name') is-invalid @enderror" name="area_name" value="{{ old('area_name') }}" autofocus >
                                @error('area_name')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       

                       

                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white" onclick="submitarea();">
                                  Create
                                </button>
                            </div>
                        </div>
        </div>
        
      </div>
    </div>
  </div>

    <!-- Modal - map -->
    <div class="modal fade" id="map-modal" tabindex="-1" role="dialog" aria-labelledby="map-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ get_phrase('Select Location') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div id="map-modal-body"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span id="lat_lng_span"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="lat_lng_confirm" type="button" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>



@include('frontend.initialize')
<script src="{{asset('assets/frontend/leafletjs/leaflet.js')}}"></script>
<script src="{{asset('assets/frontend/leafletjs/leaflet-search.js')}}"></script>
<script>
        let faqIndex = 1;

        document.getElementById('add-faq').addEventListener('click', function () {
            const container = document.getElementById('faq-container');

            const faqGroup = document.createElement('div');
            faqGroup.classList.add('faq-form-group');
            faqGroup.innerHTML = `
                <label>Question:</label>
                <input type="text" name="faqs[${faqIndex}][question]" >
                <br>
                <label>Answer:</label>
                <textarea name="faqs[${faqIndex}][answer]" ></textarea>
            `;
            container.appendChild(faqGroup);

            faqIndex++;
        });
    </script>
<script>
     
 $(document).ready(function() {
    $('.selectpicker').select2();
    $("#category_parent_id").select2({ width: 'resolve' }); 
   

    var map = L.map('map-modal-body', {

        
                center: [20.5937, 78.9629],
                zoom: 5,
            });

            var layerGroup = L.layerGroup().addTo(map);
            var current_lat = 0;
            var current_lng = 0;

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            map.on('click', function(e) {

                // remove all the markers in one go
                layerGroup.clearLayers();
                L.marker([e.latlng.lat, e.latlng.lng]).addTo(layerGroup);

                current_lat = e.latlng.lat;
                current_lng = e.latlng.lng;

                $('#lat_lng_span').text("Lat, Lng : " + e.latlng.lat + ", " + e.latlng.lng);
            });

            $('#lat_lng_confirm').on('click', function(){

                $('#item_lat').val(current_lat);
                $('#item_lng').val(current_lng);
                $('#map-modal').modal('hide')
            });
            $('.lat_lng_select_button').on('click', function(){
                $('#map-modal').modal('show');
                setTimeout(function(){ map.invalidateSize()}, 500);
            });

    

    $('#state').select2({
        placeholder: 'Type State',
        ajax: {
          url: '/states-autocomplete-ajax',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.state_name,
                        id: item.id
                    }
                })
            };
          },
          cache: true
        }
      });

  
   $('#category').select2({
        placeholder: 'Type Category',
        ajax: {
          url: '/event-categories-autocomplete-ajax',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.category_name,
                        id: item.id
                    }
                })
            };
          },
          cache: true
        }
      });


      $('#servicecategory').select2({
        placeholder: 'Type Category',
        ajax: {
          url: '/product-categories-autocomplete-ajax',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.product_category_name,
                        id: item.id
                    }
                })
            };
          },
          cache: true
        }
      });

      $('#parent').on('change', function () {
                // Get the selected value from the parent dropdown
                let selectedValue = $(this).val();

                // Update the Select2 dropdown with the selected value
                $('#category_parent_id').val(selectedValue).trigger('change');
            });
      
 });

 $('#state').on('change', function() {

$('#city').html("<option selected value='0'>Select City</option>");


if(this.value > 0)
{
    var ajax_url = '/ajax/cities/' + this.value;

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
            $('#city').html("<option selected value='0'>Select City</option>");
            $.each(JSON.parse(result), function(key, value) {
                var city_id = value.id;
                var city_name = value.city_name;
                $('#city').append('<option value="'+ city_id +'">' + city_name + '</option>');
            });
            
        }});
}

});


$('#city').on('change', function() {

$('#area').html("<option selected value='0'>Select Area</option>");



if(this.value > 0)
{
    var ajax_url = '/ajax/areas/' + this.value;

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
            $('#area').html("<option selected value='0'>Select Area</option>");
            $.each(JSON.parse(result), function(key, value) {
                var city_id = value.id;
                var city_name = value.area_name;
                $('#area').append('<option value="'+ city_id +'">' + city_name + '</option>');
            });

            
            $.each(JSON.parse(result), function(key, value) {
                var city_id = value.id;
                var city_name = value.area_name;
                $('#servicearea').append('<option value="'+ city_id +'">' + city_name + '</option>');
            });
            
        }});
}

});


@if(old('city'))
            var ajax_url_initial_cities = '/ajax/cities/{{ old('state') }}';

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: ajax_url_initial_cities,
                method: 'get',
                data: {
                },
                success: function(result){

                    $('#city').html("<option selected value='0'>Select City</option>");
                    $.each(JSON.parse(result), function(key, value) {
                        var city_id = value.id;
                        var city_name = value.city_name;

                        if(city_id === {{ old('city_id') }})
                        {
                            $('#city').append('<option value="'+ city_id +'" selected>' + city_name + '</option>');
                        }
                        else
                        {
                            $('#city').append('<option value="'+ city_id +'">' + city_name + '</option>');
                        }
                    });
                    // $('#city').selectpicker('refresh');
                }});
            @endif

            @if(old('city'))
            var ajax_url_initial_areas = '/ajax/areas/{{ old('city') }}';

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

                    $('#area').html("<option selected value='0'>Select Area</option>");
                    $.each(JSON.parse(result), function(key, value) {
                        var area_id = value.id;
                        var area_name = value.area_name;

                        if(area_id === {{ old('area_id') }})
                        {
                            $('#area').append('<option value="'+ area_id +'" selected>' + area_name + '</option>');
                        }
                        else
                        {
                            $('#area').append('<option value="'+ area_id +'">' + area_name + '</option>');
                        }
                    });
                    
                }});
            @endif


function showcategorymodel(){

$('#categoriesModal' ).modal('show');
}

function showparentcategorymodel(){

$('#parentcategoriesModal' ).modal('show');
}

function showcitymodel(){

var statid=$('#state').val();
if(statid>0){
$('#cityModal' ).modal('show');
}
else{
    Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please select state first!"
                });
}
}


function submitcity(){
var statid=$('#state').val();
var city_name=$('#city_name').val();
// alert(city_name);
if(statid>0 && city_name!=""){

var ajax_url = "{{route('ajax.storecities')}}";
      
        jQuery.ajax({
            url: ajax_url,
            method: 'POST',
            data: {
                statid:statid,
                city_name:city_name,
                _token: '{{csrf_token()}}' 

            },
            success: function(result){
                console.log(result);

               

    if(result > 0)
    {
        $('#city').html("<option selected value='0'>Select City</option>");
        // $('#city').selectpicker('refresh');
        var ajax_url = '/ajax/cities/' + result;

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
                console.log(result);
                $('#city').html("<option selected value='0'>Select City</option>");
                $.each(JSON.parse(result), function(key, value) {
                    var city_id = value.id;
                    var city_name = value.city_name;
                    $('#city').append('<option value="'+ city_id +'">' + city_name + '</option>');
                });
                // $('#city').selectpicker('refresh');
            }});

            $('#cityModal' ).modal('hide');
    }else{
        Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "City already exists!"
                });
        
    }
    
                
            }});

}else{
    Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please enter city name!"
                });
}

}

function submitparentcategory(){
var category_name=$('#parent_category_name').val();
//alert(category_name);
if(category_name!=""){

var ajax_url = "{{route('ajax.store.event.categories')}}";
      
        jQuery.ajax({
            url: ajax_url,
            method: 'POST',
            data: {
                category_name:category_name,
                category_parent_id:null,
                _token: '{{csrf_token()}}' 

            },
            success: function(result){
                console.log(result);

               

    if(result > 0)
    {
      // console.log(result);
       
        // $('#category').html("<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
        //$('#category').selectpicker('refresh');
        var ajax_url = "{{route('page.json.parent.event.catgories')}}";

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
                $('#parent').html("<option selected value='0'>Select Category</option>");
                $.each(JSON.parse(result), function(key, value) {
                    var city_id = value.id;
                    var city_name = value.category_name;
                    var parent = value.parent;
                    $('#parent').append('<option value="'+ city_id +'">' + city_name +'</option>');
                });
                //$('#category').selectpicker('refresh');
            }});

            $('#parentcategoriesModal' ).modal('hide');
    }else{
        Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Category already exists!"
                });
        
    }
    
                
            }});

}
else{
    Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please enter category!"
                });
}

}



function submitcategory(){
var category_name=$('#category_name').val();
var category_parent_id=$('#category_parent_id').val();
//alert(category_name);
if(category_name!=""){

var ajax_url = "{{route('ajax.store.event.categories')}}";
      
        jQuery.ajax({
            url: ajax_url,
            method: 'POST',
            data: {
                category_name:category_name,
                category_parent_id:category_parent_id,
                _token: '{{csrf_token()}}' 

            },
            success: function(result){
                console.log(result);

               

    if(result > 0)
    {
      // console.log(result);
       
        // $('#category').html("<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
        //$('#category').selectpicker('refresh');
        var ajax_url = "{{route('page.json.event.catgories')}}";

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
                $('#category').html("<option selected value='0'>Select Category</option>");
                $.each(JSON.parse(result), function(key, value) {
                    var city_id = value.id;
                    var city_name = value.category_name;
                    var parent = value.parent;
                    $('#category').append('<option value="'+ city_id +'">' + city_name + ' | '+ parent + '</option>');
                });
                //$('#category').selectpicker('refresh');
            }});

            $('#categoriesModal' ).modal('hide');
    }else{
        Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Category already exists!"
                });
        
    }
    
                
            }});

}
else{
    Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please enter category!"
                });
}

}

            function showareamodel(){
                var cityid=$('#city').val();
                if(cityid>0){
                    $('#areaModal' ).modal('show');
                    }
                 else{
                    Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please select city first!"
                });
                    
                    }
}

function submitarea(){
    var cityid=$('#city').val();
    var area_name=$('#area_name').val();
   //alert(area_name);
    if(cityid>0 && area_name!=""){

        var ajax_url = "{{route('ajax.storeareas')}}";
                  
                    jQuery.ajax({
                        url: ajax_url,
                        method: 'POST',
                        data: {
                            cityid:cityid,
                            area_name:area_name,
                            _token: '{{csrf_token()}}' 

                        },
                        success: function(result){
                            console.log(result);

                           

                if(result > 0)
                {
                    $('#area').html("<option selected value='0'>Select Area</option>");
                    // $('#area').selectpicker('refresh');

                
                    var ajax_url = '/ajax/areas/' + result;

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
                            console.log(result);
                            $('#area').html("<option selected value='0'>Select Area</option>");
                            $.each(JSON.parse(result), function(key, value) {
                                var city_id = value.id;
                                var city_name = value.area_name;
                                $('#area').append('<option value="'+ city_id +'">' + city_name + '</option>');
                            });
                            // $('#area').selectpicker('refresh');
                        }});
                

                        $('#areaModal' ).modal('hide');
                }else{
                    Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Area already exists!"
                });
                }
                
                            
                        }});

    }
    else{
    Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please enter area name!"
                });
}

}
 
 
    // $('.selectpicker').select2({
    //     dropdownParent: $('#common-modal')
    // });
</script>