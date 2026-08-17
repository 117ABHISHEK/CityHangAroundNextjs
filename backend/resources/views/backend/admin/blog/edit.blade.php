<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{asset('assets/frontend/leafletjs/leaflet.css')}}" rel="stylesheet">
<style>
  
.leaflet-container {
            height: 400px;width: 600px;max-width: 100%;max-height: 100%;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 13px!important;
}

</style>
<div class="main_content">
    <!-- Mani section header and breadcrumb -->
    <div class="mainSection-title">
      <div class="row">
        <div class="col-12">
          <div
            class="d-flex justify-content-between align-items-center flex-wrap gr-15"
          >
            <div class="d-flex flex-column">
              <h4>{{ get_phrase('Add a new blgo') }}</h4>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Start Admin area -->
    <div class="row">
      <div class="col-md-12">
        <div class="eSection-wrap-2">
            <div class="eForm-layouts">
              @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
              <form method="POST" action="{{ route('admin.blog.updated', $blog_details->id) }}" enctype="multipart/form-data">
                  @csrf
                  <div class="row">
                            <div class="col-md-12">
                                <label for="item_status" class="text-black">Status</label>
                                <select class="form-select eForm-control select2 @error('item_status') is-invalid @enderror" name="item_status">

                                <option value="1" {{ (1==$blog_details->blog_status) ? 'selected' : '' }}>Submitted</option>
                                    <option value="2" {{ (2==$blog_details->blog_status) ? 'selected' : '' }}>Published</option>
                                    <option value="3" {{ (3==$blog_details->blog_status) ? 'selected' : '' }}>Suspended</option>

                                </select>
                                @error('item_status')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                   
                               
                            </div>
                            <div class="form-group">
                    <label for="#">{{ get_phrase('Author') }}</label>
                    <input type="text" class="form-control eForm-control" name="author" placeholder="Enter Author Name" value="{{$blog_details->auther_name}}">
                </div>
                  <div class="mb-3">
                    <label for="title" class="form-label eForm-label">{{ get_phrase('Blog title') }}</label>
                    <input type="text" class="form-control eForm-control" value="{{$blog_details->title}}" id="title" name="title" placeholder="Blog title">
                  </div>

                  <?php $category_array=explode(',', $blog_details->category_id);
                                                            ?>

             <div class="form-group">
                      <label for="page_category" class="form-label eForm-label">{{ get_phrase('Parent category') }}</label>
                      <select name="parent" id="parent" class="form-select eForm-control select2  @error('category') is-invalid @enderror"  multiple required>
                      <option value="0">Select Parent Category</option>
                                @foreach($parent as $key => $printable_category)
                                    <option value="{{ $printable_category->id }}" {{ (in_array($printable_category->id, $category_array)) ? 'selected' : '' }}>{{ $printable_category->category_name }}</option>
                                @endforeach
        
                    </select>
                      <a  class="text-info pl-2 float-left" onclick="showparentcategorymodel();" >
                                                <i class="far fa-add"></i>
                                                Click here to add category(If not in list)
                                            </a>
                  </div>
                  <div class="form-group">
                      <label for="page_category" class="form-label eForm-label">{{ get_phrase('Tag category') }}</label>
                      <select name="category[]" id="category" class="form-select eForm-control select2  @error('category') is-invalid @enderror"  multiple required>
                    @foreach ($blog_categories as $category )
                        <option value="{{ $category->id }}"{{ (in_array($category->id, $category_array)) ? 'selected' : '' }}> {{ $category->category_name }} </option>
                        @endforeach
                    </select>
                      <a  class="text-info pl-2 float-left" onclick="showcategorymodel();" >
                                                <i class="far fa-add"></i>
                                                Click here to add category(If not in list)
                                            </a>
                  </div>


                  <div class="row">
                  <div class="col-lg-6">
    <div class="form-group">
        <label for="#">{{ get_phrase('Country') }}</label>
        <select name="country" id="country" class="form-select eForm-control select2 @error('state') is-invalid @enderror" >
        <option value="">{{ get_phrase('Select') }}</option>
                    @foreach ($countries as $list)
                        <option value="{{ $list->id }}" {{ ($list->id==$blog_details->country_id) ? 'selected' : '' }}>{{ $list->country_name }}</option>
                    @endforeach
        </select>
    </div>
</div>
    <div class="col-lg-6">
    <div class="form-group">
        <label for="#">{{ get_phrase('State') }}</label>
        <select name="state" id="state" class="form-select eForm-control select2 @error('state') is-invalid @enderror" required>
        @foreach ($all_states as $state )
            <option value="{{ $state->id }}"{{ ($state->id==$blog_details->state_id) ? 'selected' : '' }}> {{ $state->state_name }} </option>
            @endforeach
        </select>
    </div>
</div>

   </div>
   <div class="row">
   <div class="col-lg-6">
    <div class="form-group">
        <label for="#">{{ get_phrase('City') }}</label>
        <select name="city" id="city" class="form-select eForm-control select2 @error('city') is-invalid @enderror" required>
        @foreach ($all_cities as $city )
            <option value="{{ $city->id }}"{{ ($city->id==$blog_details->city_id) ? 'selected' : '' }}> {{ $city->city_name }} </option>
            @endforeach
        </select>
        <a  class="text-info pl-2 float-left" onclick="showcitymodel();" >
                                                <i class="far fa-add"></i>
                                                Suggest City
                                            </a>
    </div>
    </div>
   <div class="col-lg-6">
    <div class="form-group">
        <label for="#">{{ get_phrase('Area') }}</label>
        <select name="area" id="area" class="form-select eForm-control select2 @error('area') is-invalid @enderror"  required>
        @foreach ($all_areas as $area )
            <option value="{{ $area->id }}"{{ ($area->id==$blog_details->area_id) ? 'selected' : '' }}> {{ $area->area_name }} </option>
            @endforeach
        </select>
        <a  class="text-info pl-2 float-center" onclick="showareamodel();" data-bs-toggle="modal">
        <i class="far fa-add"></i> Suggest Area</a>
        @error('type')
                                                            <div class="alert alert-danger">{{$message}}</div>
                                                            @enderror
    </div>
  </div>
</div>
<div class="row">
            <div class="col-lg-6">
            <div class="form-group">
                <label for="#">{{ get_phrase('Publication Date') }}</label>
                <input type="date" name="publication_date" class="form-control eForm-control" placeholder="Publication Date"
       value="{{ $blog_details->publication_date ? \Carbon\Carbon::parse($blog_details->publication_date)->format('Y-m-d') : '' }}">
            </div>
            </div>
            <div class="col-lg-6">
            <div class="form-group">
            <label for="#">{{ get_phrase('List') }}</label>
                <select name="List" id="List"  class="form-select eForm-control select2 @error('category') is-invalid @enderror">
                    <option value="">{{ get_phrase('Select') }}</option>
                    @foreach ($listing as $list)
                        <option value="{{ $list->id }}" {{ ($list->id==$blog_details->list_id) ? 'selected' : '' }}>{{ $list->title }}</option>
                    @endforeach
                </select>
            </div>
            </div>
        </div>
                  <div class="mb-3">
                      <label for="description" class="form-label eForm-label">{{ get_phrase('Blog details') }}</label>
                      <textarea id="description" name="description" class="content">{{$blog_details->description}}</textarea>
                  </div>

                  @php
                      $blogTags = json_decode($blog_details->tag ?? '[]', true);
                      if (!is_array($blogTags)) {
                          $blogTags = [];
                      }
                      $blogTags = array_values(array_filter($blogTags, function ($tag) {
                          return is_string($tag) || is_numeric($tag);
                      }));
                  @endphp
                  <div class="mb-3">
                    <label for="tag" class="form-label eForm-label">{{ get_phrase('Tags') }}</label>
                    <input type="text" class="form-control eForm-control py-1" id="tag" value="{{ implode(', ', $blogTags) }}" name="tag" placeholder="Tags">
                  </div>

                  <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-select eForm-control select2 @error('category') is-invalid @enderror">
                <option value="">{{ get_phrase('Select') }}</option>
                <option value="publish" {{ ("publish"==$blog_details->publication_status) ? 'selected' : '' }}>Publish</option>
                <option value="scheduled" {{ ("scheduled"==$blog_details->publication_status) ? 'selected' : '' }}>Scheduled</option>
                <option value="draft" {{ ("draft"==$blog_details->publication_status) ? 'selected' : '' }}>Draft</option>
                </select>
                </div>

                  <div class="mb-3">
                      <label for="image" class="form-label eForm-label">{{ get_phrase('Cover photo') }}</label>
                      <input id="image" class="form-control eForm-control-file" type="file" name="image">
                  </div>
                  
                  <button type="submit" class="btn btn-primary">{{ get_phrase('Submit') }}</button>
              </form>
            </div>

        </div>
      </div>
    </div>
    <!-- Start Footer -->
    @include('backend.footer')
    <!-- End Footer -->
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
$(document).ready(function() {
    $('.selectpicker').select2();

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
          url: '/blog-categories-autocomplete-ajax',
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

   $('#List').select2({
        placeholder: 'Type Listing',
        allowClear: true,
        ajax: {
          url: "{{ route('admin.blog.listing.search') }}",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
                q: params.term || ''
            };
          },
          processResults: function (data) {
            return {
              results: $.map(data, function (item) {
                    return {
                        text: item.title,
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
                $.each(JSON.parse(result), function(key, value) {
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
</script>

