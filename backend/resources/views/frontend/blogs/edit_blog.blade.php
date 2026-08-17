
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js"></script>
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
<div class="page-wrap">
    <div class="d-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h3 class="h5 pt-3"><span><img width="12" src="{{ asset('assets/frontend/images/stickies-fill.png') }}" alt=""></span> {{ get_phrase('Edit Article') }}</h3>
       
    </div>
    <div class="card mt-3 px-3 py-4">
        <div class="create-article">
            @if ($errors->any())
                <div class="text-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('blog.update',$blog->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="#">{{ get_phrase('Author') }} <span class="text-danger">*</span></label>
                    <input type="text" name="author" placeholder="Enter Author Name" value="{{$blog->auther_name}}">
                </div>
                <div class="form-group">
                    <label for="#">{{ get_phrase('Title') }} <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ $blog->title }}" placeholder="Enter your title">
                </div>
                <?php $category_array=explode(',', $blog->category_id);?>
    <div class="form-group">
        <label for="#">{{ get_phrase('Parent Category') }} <span class="text-danger">*</span></label>
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
    <div class="form-group">
        <label for="#">{{ get_phrase('Tag Category') }}</label>
        <select name="category[]" id="category" class="selectpicker form-control @error('category') is-invalid @enderror"  multiple required>
       
            @foreach (\App\Models\Pagecategory::all() as $category )
            <option value="{{ $category->id }}"{{ (in_array($category->id, $category_array)) ? 'selected' : '' }}> {{ $category->category_name }} </option>
            @endforeach
           </select>
           <a  class="text-info pl-2 float-left" onclick="showcategorymodel();" >
                                                <i class="far fa-add"></i>
                                                Click here to add category(If not in list)
                                            </a>
       
    </div>

    <div class="row">
            <div class="form-group col-md-12 col-lg-12 col-sm-12">
            <label for="#">{{ get_phrase('Tag Page') }}</label>
                <select name="List" id="List"  class="selectpicker form-control @error('category') is-invalid @enderror">
                @if($blog->list_id)
                    <option value="{{ $blog->list_id }}" selected>{{ \App\Models\Page::find($blog->list_id)?->title }}</option>
                @endif
                </select>
            </div>
        </div>

    <div class="row">
    <div class="col-lg-6">
    <div class="form-group">
        <label for="#">{{ get_phrase('Country') }} <span class="text-danger">*</span></label>
        <select name="country" id="country" class="selectpicker form-control @error('state') is-invalid @enderror" >
        <option value="">{{ get_phrase('Select') }}</option>
                    @foreach ($countries as $list)
                        <option value="{{ $list->id }}" {{ ($list->id==$blog->country_id) ? 'selected' : '' }}>{{ $list->country_name }}</option>
                    @endforeach
        </select>
    </div>
</div>
    <div class="col-lg-6">
    <div class="form-group">
        <label for="#">{{ get_phrase('State') }} <span class="text-danger">*</span></label>
        <select name="state" id="state" class="selectpicker form-control @error('state') is-invalid @enderror" required>
        @foreach ($all_states as $state )
          @if($blog->state_id)
            <option value="{{ $state->id }}"{{ ($state->id==$blog->state_id) ? 'selected' : '' }}> {{ $state->state_name }} </option>
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
        <label for="#">{{ get_phrase('City') }} <span class="text-danger">*</span></label>
        <select name="city" id="city" class="selectpicker form-control @error('city') is-invalid @enderror" required>
        @foreach ($all_cities as $city )
            @if($blog->city_id)
            <option value="{{ $city->id }}"{{ ($city->id==$blog->city_id) ? 'selected' : '' }}> {{ $city->city_name }} </option>
            @endif
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
        <label for="#">{{ get_phrase('Area') }} <span class="text-danger">*</span></label>
        <select name="area" id="area" class="selectpicker form-control @error('area') is-invalid @enderror"  required>
        @foreach ($all_areas as $area )
           @if($blog->area_id)
            <option value="{{ $area->id }}"{{ ($area->id==$blog->area_id) ? 'selected' : '' }}> {{ $area->area_name }} </option>
           @endif
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
                <div class="form-group">
                    <label for="#">{{ get_phrase('Tags') }}</label>
                    <input type="text" name="tag" value="{{ $blog->tag }}" class="form-control bg-secondary" placeholder="Enter your tags">
                </div>
                <div class="form-group">
                    <label for="#">{{ get_phrase('Description') }}</label>
                    <textarea name="description" id="description" class="content" placeholder="Description">{{ $blog->description }}</textarea>
                </div>


                <div class="form-group">
                    <label for="">{{ get_phrase('Previous Uploaded Image') }}</label> <br>
                    <img src="{{ get_blog_image($blog->thumbnail,'thumbnail') }}" class="w-60 custome-height-50"  alt="">
                </div>
                <div class="form-group">
                    <label for="#">{{ get_phrase('Image') }}</label>
                    <input type="file" name="image" id="image">
                </div>
                
                
                <div class="inline-btn mt-3">
                    <button type="submit" class="btn btn-primary w-100">{{ get_phrase('Update Post') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


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


<script>
function initSelect2() {
    if (typeof $.fn.select2 !== 'undefined') {
        $('.selectpicker:not(#state):not(#List):not(#category)').select2();

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


          $('#List').select2({
            placeholder: "{{ get_phrase('Select') }}",
            minimumInputLength: 2,
            ajax: {
                url: '{{ route("ajax.get.pages") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term // user typed term
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(obj) {
                            return { id: obj.id, text: obj.title };
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
            url: '/blog-categories-autocomplete-ajax',
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
    } else {
        setTimeout(initSelect2, 50);
    }
}

$(document).ready(function() {
    initSelect2();
});


$('#category').on('select2:select', function (e) {
    var data = e.params.data;

    if (data.newOption) {
        // Save category via AJAX
        $.ajax({
            type: "POST",
            url: "/blog-categories-create-from-select2",
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
});

       $('#parent').on('change', function () {
                // Get the selected value from the parent dropdown
                let selectedValue = $(this).val();

                // Update the Select2 dropdown with the selected value
                $('#category_parent_id').val(selectedValue).trigger('change');
            });


            $('#state').on('change', function() {

$('#city').html("<option selected value='0'>Select City</option>").trigger('change');


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
            $.each((typeof result === 'string' ? JSON.parse(result) : result), function(key, value) {
                var city_id = value.id;
                var city_name = value.city_name;
                $('#city').append('<option value="'+ city_id +'">' + city_name + '</option>');
            });
            $('#city').trigger('change');
        }});
}

});


$('#city').on('change', function() {

$('#area').html("<option selected value='0'>Select Area</option>").trigger('change');



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
            $.each((typeof result === 'string' ? JSON.parse(result) : result), function(key, value) {
                var city_id = value.id;
                var city_name = value.area_name;
                $('#area').append('<option value="'+ city_id +'">' + city_name + '</option>');
            });

            
            $.each((typeof result === 'string' ? JSON.parse(result) : result), function(key, value) {
                var city_id = value.id;
                var city_name = value.area_name;
                $('#servicearea').append('<option value="'+ city_id +'">' + city_name + '</option>');
            });
            $('#area').trigger('change');
        }});
}

});
});




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
            let cities = typeof result === 'string' ? (typeof result === 'string' ? JSON.parse(result) : result) : result;

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
                    $.each((typeof result === 'string' ? JSON.parse(result) : result), function(key, value) {
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
                $.each((typeof result === 'string' ? JSON.parse(result) : result), function(key, value) {
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

var ajax_url = "{{route('ajax.store.blog.categories')}}";
      
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
        var ajax_url = "{{route('page.json.parent.blog.catgories')}}";

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
                // $('#parent').html("<option selected value='0'>Select Category</option>");
                $.each((typeof result === 'string' ? JSON.parse(result) : result), function(key, value) {
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

var ajax_url = "{{route('ajax.store.blog.categories')}}";
      
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
        var ajax_url = "{{route('page.json.blog.catgories')}}";

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
                // $('#category').html("<option selected value='0'>Select Category</option>");
                $.each((typeof result === 'string' ? JSON.parse(result) : result), function(key, value) {
                    var city_id = value.id;
                    var city_name = value.category_name;
                    var parent = value.parent;
                    $('#category').append('<option value="'+ city_id +'">' + city_name + ' </option>');
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
                            $.each((typeof result === 'string' ? JSON.parse(result) : result), function(key, value) {
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
</script>


