
@php
    $group = \App\Models\Group::find($group_id);
@endphp
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
        #categoriesModal {
    z-index: 1055 !important; /* Ensure it's above other elements */
}
#areaModal {
    z-index: 1055 !important; /* Ensure it's above other elements */
}

.modal-backdrop {
    z-index: 1050 !important; /* Ensure backdrop is behind modal */
}
.bootstrap-select .dropdown-menu {
    z-index: 1060 !important;
    position: absolute !important;
}
.modal {
    overflow: visible !important;
}

.modal-dialog {
    overflow: visible !important;
}

.modal-content {
    overflow: visible !important;
}

</style>
<div class="page-content">
<div class="page-tab bg-white border rounded p-3 pb-1">
<form  action="{{ route('group.update',$group->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if ($errors->any())
                              <div class="alert alert-danger">
                                  <ul>
                                      @foreach ($errors->all() as $error)
                                          <li>{{ $error }}</li>
                                      @endforeach
                                  </ul>
                              </div>
                          @endif

    <div class="form-group">
        <label for="#">{{ get_phrase('Group Title') }}</label>
        <input type="text" class="border-0 bg-secondary" name="name" value="{{ $group->title }}" placeholder="{{ get_phrase('Enter your group title')}}">
    </div>
    <div class="form-group" hidden>
        <label for="#">{{ get_phrase('Group Sub Title') }}</label>
        <input type="text" class="border-0 bg-secondary" name="subtitle" value="{{ $group->subtitle }}" placeholder="{{ get_phrase('Enter your group sub title')}}">
    </div>

    <?php $category_array=explode(',', $group->category_id);?>
    <div class="form-group">
        <label for="#">{{ get_phrase('Category') }}</label>
        <select name="parent" id="parent" class="selectpicker form-control @error('parent') is-invalid @enderror"   required>
        <option value="0">Select Parent Category</option>
                                @foreach($parent as $key => $printable_category)
                                    <option value="{{ $printable_category->id }}" {{ (in_array($printable_category->id, $category_array)) ? 'selected' : '' }}>{{ $printable_category->category_name }}</option>
                                @endforeach
        </select>
        <a  class="text-info pl-2 float-left" onclick="showparentcategorymodel();" >
                                                <i class="far fa-add"></i>
                                                Suggest Category(If not in list)
                                            </a>
    </div>
    <div class="form-group">
        <label for="#">{{ get_phrase('Keyword tag(Hashtag)') }}</label>
        <select name="category[]" id="category" class="selectpicker form-control @error('category') is-invalid @enderror"  multiple required>
       
            @foreach (\App\Models\Groupcategory::all() as $category )
            <option value="{{ $category->id }}"{{ (in_array($category->id, $category_array)) ? 'selected' : '' }}> {{ $category->category_name }} </option>
            @endforeach
           </select>
           <a  class="text-info pl-2 float-left" onclick="showcategorymodel();" >
                                                <i class="far fa-add"></i>
                                                Suggest Sub Category(If not in list)
                                            </a>
       
    </div>

    <div class="form-group" hidden>
        <label for="#">{{ get_phrase('Group Location') }}</label>
        <input type="text" class="border-0 bg-secondary" name="location" value="{{ $group->location }}" placeholder="{{ get_phrase('Enter your group location')}}">
    </div>
    <div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label for="country">{{ get_phrase('Country') }}</label>
            <select name="country" id="country" class="selectpicker form-control @error('country') is-invalid @enderror" >
                <option value="0">Select Country</option>
                @foreach($countries as $key => $country)
                    <option value="{{ $country->id }}" {{ ($country->id==$group->country_id) ? 'selected' : '' }}>{{ $country->country_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-6">
    <div class="form-group">
        <label for="#">{{ get_phrase('State') }}</label>
        <select name="state" id="state" class="selectpicker form-control @error('state') is-invalid @enderror" >
        @foreach ($all_states as $state )
            @if($group->state_id)
            <option value="{{ $state->id }}"{{ ($state->id==$group->state_id) ? 'selected' : '' }}> {{ $state->state_name }} </option>
            @endif
            @endforeach
        </select>
        <input type="hidden" id="old_state_id" value="{{ old('state') }}">
    </div>
</div>

   </div>
   <div class="row">
   <div class="col-lg-6">
    <div class="form-group">
        <label for="#">{{ get_phrase('City') }}</label>
        <select name="city" id="city" class="selectpicker form-control @error('city') is-invalid @enderror" >
        @foreach ($all_cities as $city )
            <option value="{{ $city->id }}"{{ ($city->id==$group->city_id) ? 'selected' : '' }}> {{ $city->city_name }} </option>
            @endforeach
        </select>
        <input type="hidden" id="old_city_id" value="{{ old('city') }}">
        <a  class="text-info pl-2 float-left" onclick="showcitymodel();" >
                                                <i class="far fa-add"></i>
                                                Suggest City
                                            </a>
    </div>
    </div>
   <div class="col-lg-6">
    <div class="form-group">
        <label for="#">{{ get_phrase('Area') }}</label>
        <select name="area" id="area" class="selectpicker form-control @error('area') is-invalid @enderror"  >
        @foreach ($all_areas as $area )
            <option value="{{ $area->id }}"{{ ($area->id==$group->area_id) ? 'selected' : '' }}> {{ $area->area_name }} </option>
            @endforeach
        </select>
        <input type="hidden" id="old_area_id" value="{{ old('area') }}">
        <a  class="text-info pl-2 float-center" onclick="showareamodel();" data-bs-toggle="modal">
        <i class="far fa-add"></i> Suggest Area</a>
        @error('type')
                                                            <div class="alert alert-danger">{{$message}}</div>
                                                            @enderror
    </div>
  </div>
  <div class="col-lg-6">
   
  </div>
</div>
    <div class="form-group" hidden>
        <label for="#">{{ get_phrase('Group Type') }}</label>
        <input type="text" class="border-0 bg-secondary" name="group_type" value="{{ $group->group_type }}" placeholder="{{ get_phrase('Enter your group type')}}">
    </div>
    <div class="form-group">
        <label for="#">{{ get_phrase('About') }}</label>
        <textarea name="about" class="border-0 bg-secondary content" id="about" cols="30" rows="10">{{ $group->about }}</textarea>
    </div>
    <div class="form-group">
        <label for="#">{{ get_phrase('Privacy') }}</label>
        <select name="privacy" id="privacy" class="form-control border-0 bg-secondary">
            <option value="public" {{ $group->privacy=="public" ? "selected":"" }}>Public</option>
            <option value="private" {{ $group->privacy=="private" ? "selected":"" }}>Private</option>
        </select>
    </div>
    <div class="form-group">
        <label for="#">{{ get_phrase('Status') }}</label>
        <select name="status" id="status" class="form-control border-0 bg-secondary">
            <option value="1" {{ $group->status=="1" ? "selected":"" }}>Active</option>
            <option value="0" {{ $group->status=="0" ? "selected":"" }}>Deactive</option>
        </select>
    </div>
    <div>
        <label for="">{{ get_phrase('Previous Profile Photo') }}</label> <br>
        <img src="{{ get_group_logo($group->logo, 'logo') }}" class="w-20 height-100-css" alt="">
    </div>
    <div class="form-group">
        <label for="#">{{ get_phrase('Update Profile Photo') }}</label>
        <input type="file" name="image" id="image" class="form-control border-0 bg-secondary">
    </div>
    <button type="submit" class="w-100 btn btn-primary">{{ get_phrase('Edit Group') }}</button>
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

  @include('frontend.initialize')
  <script>
     
     $(document).ready(function() {
        $('.selectpicker').select2(); // Initialize other Select2 dropdowns normally
    
    // Select2 for dropdowns inside the modal
    $('#category_parent_id').select2({
        dropdownParent: $('#categoriesModal') // Ensures dropdown stays inside modal
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
    tags: true, // Enable tagging (create new entries)
    placeholder: 'Type Category',
    multiple: true,
    tokenSeparators: [','],
    ajax: {
        url: '/group-categories-autocomplete-ajax',
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term
            };
        },
        processResults: function (data) {
            return {
                results: $.map(data, function (item) {
                    return {
                        text: item.category_name,
                        id: item.id
                    };
                })
            };
        },
        cache: true
    },
    createTag: function (params) {
        return {
            id: params.term, // Temp id
            text: params.term,
            newOption: true
        };
    },
    templateResult: function (data) {
        var $result = $("<span></span>");
        $result.text(data.text);
        if (data.newOption) {
            $result.append(" <em>(new)</em>");
        }
        return $result;
    }
});

$('#category').on('select2:select', function (e) {
    var data = e.params.data;

    if (data.newOption) {
        // Save category via AJAX
        $.ajax({
            type: "POST",
            url: "/group-categories-create-from-select2",
            data: {
                _token: '{{ csrf_token() }}',
                category_name: data.text
            },
            success: function (response) {
                // Remove the temporary tag (selected before)
                var selectedValues = $('#category').val().filter(function(val) {
                    return val !== data.id; // Remove old (text-based) ID
                });

                $('#category').val(selectedValues).trigger('change');

                // Add the new one with real ID
                var newOption = new Option(response.category_name, response.id, true, true);
                $('#category').append(newOption).trigger('change');
            }
        });
    }
})
    
    
          
    
          $('#parent').on('change', function () {
                    // Get the selected value from the parent dropdown
                    let selectedValue = $(this).val();
    
                    // Update the Select2 dropdown with the selected value
                    $('#category_parent_id').val(selectedValue).trigger('change');
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
          
     });
    
    
    
    
    // After select2 is initialized, load the old value if it exists
  let oldStateId = $('#old_state_id').val();

if (oldStateId) {
    $.ajax({
        type: 'GET',
        url: '/states-autocomplete-ajax', // Use the same endpoint
        data: { q: '' }, // force a fetch of all or filter if needed
        success: function (data) {
            let matched = data.find(item => item.id == oldStateId);
            if (matched) {
                let option = new Option(matched.state_name, matched.id, true, true);
                $('#state').append(option).trigger('change');
            }
        }
    });
}



let old_city_id = $('#old_city_id').val(); // likely a string

if (oldStateId) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'GET',
        url: '/ajax/cities/' + oldStateId,
        success: function (result) {
            // Parse only if result is a string
            let cities = typeof result === 'string' ? JSON.parse(result) : result;

            $('#city').html("<option selected value='0'>Select City</option>");

            $.each(cities, function (key, value) {
                let city_id = value.id.toString(); // convert to string to match
                let city_name = value.city_name;

                if (city_id === old_city_id) {
                    
                    $('#city').append('<option value="' + city_id + '" selected>' + city_name + '</option>');
                } else {
                    $('#city').append('<option value="' + city_id + '">' + city_name + '</option>');
                }
            });
            // ✅ Now set the value and trigger
            $('#city').val(old_city_id).trigger('change');
            


        },
        error: function (xhr) {
            console.error('City load failed:', xhr.responseText);
        }
    });
}


let old_area_id = $('#old_area_id').val();
if (old_city_id) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'GET',
        url:  '/ajax/areas/' + old_city_id, // Use the same endpoint
        success: function (result) {
            $('#area').html("<option selected value='0'>Select Area</option>");
                    $.each(JSON.parse(result), function(key, value) {
                        var area_id = value.id;
                        var area_name = value.area_name;

                        if(area_id === old_area_id)
                        {
                           
                            $('#area').append('<option value="'+ area_id +'" selected>' + area_name + '</option>');
                        }
                        else
                        {
                            $('#area').append('<option value="'+ area_id +'">' + area_name + '</option>');
                        }
                    });


                    // ✅ Now set the value and trigger
            $('#area').val(old_area_id).trigger('change');
        }
    });
}
    
    
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
    
    var ajax_url = "{{route('ajax.store.group.categories')}}";
          
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
            var ajax_url = "{{route('page.json.group.catgories')}}";
    
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
    
    var ajax_url = "{{route('ajax.store.group.categories')}}";
          
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
            var ajax_url = "{{route('page.json.group.catgories')}}";
    
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