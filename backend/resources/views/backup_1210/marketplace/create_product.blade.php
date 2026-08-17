<?php $selectedValue=0;?>
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

 .error-message {
    color: red;
    font-size: 12px;
    margin-top: 5px;
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
  <div class="page-tab bg-white border rounded p-4 shadow-sm">
    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Product Name --}}
      <div class="mb-3">
        <label class="form-label">{{ get_phrase('Product Name') }}</label>
        <input type="text" name="title" id="product_title" class="form-control" placeholder="Enter Product/Service name here Ex: Iphone 16 pro" onkeyup="getProductSuggestions()">
        <div id="suggestion-box"></div>
      </div>

      {{-- Product Type --}}
      <div class="mb-3">
        <label class="form-label">{{ get_phrase('Please choose your Product Type*') }}</label>
        <select name="producttype" class="form-select">
          <option value="0" selected>{{ get_phrase('select') }}</option>
          <option value="Physical" >{{ get_phrase('Physical') }}</option>
          <option value="Affiliate">{{ get_phrase('Affiliate') }}</option>
        </select>
      </div>

      {{-- Product Nature Type --}}
      <div class="mb-3">
        <label class="form-label">{{ get_phrase('Please choose your Product Nature Type*') }}</label>
        <select name="productnaturetype" class="form-select">
          <option value="0" selected>{{ get_phrase('select') }}</option>
          <option value="Service">{{ get_phrase('Service') }}</option>
          <option value="Product">{{ get_phrase('Product') }}</option>
        </select>
      </div>

      {{-- Parent Category --}}
      <div class="mb-3">
        <label class="form-label">{{ get_phrase('Parent Category') }}</label>
        <select name="parent" id="parent" class="form-select">
          <option value="0">Select Parent Category</option>
          @foreach($parent as $key => $printable_category)
            <option value="{{ $printable_category->id }}">{{ $printable_category->product_category_name }}</option>
          @endforeach
        </select>
        <a class=" small  d-block mt-1" style="color: #FF4939" onclick="showparentcategorymodel();">
          <i class="far fa-add"></i> Click here to add Parent category (If not in list)
        </a>
      </div>

      {{-- Tags / Keywords --}}
      <div class="mb-3">
        <label class="form-label">{{ get_phrase('Product Tags/keyword') }}</label>
        <select name="category[]" id="category" class="form-select" multiple></select>
        <a class=" small d-block mt-1" style="color: #FF4939" onclick="showcategorymodel();">
          <i class="far fa-add"></i> Click here to add category (If not in list)
        </a>
      </div>

      {{-- Brand --}}
      <div class="mb-3">
        <label class="form-label">{{ get_phrase('Brand') }}</label>
        <select name="brand" id="brand" class="form-select">
          <option value="" disabled selected>{{ get_phrase('Select Brand') }}</option>
          @foreach (\App\Models\Brand::all() as $brand)
            <option value="{{ $brand->id }}">{{ ucfirst($brand->name) }}</option>
          @endforeach
        </select>
        <a class=" small d-block mt-1" style="color: #FF4939" onclick="showbrandmodel();">
          <i class="far fa-add"></i> Click here to add Brand (If not in list)
        </a>
      </div>

      {{-- Currency --}}
      <div class="mb-3">
        <label class="form-label">{{ get_phrase('Currency') }}</label>
        <select name="currency" id="currency" class="form-select">
          <option value="">{{ get_phrase('Select Currency') }}</option>
          @foreach (\App\Models\Currency::all() as $currency)
            <option value="{{ $currency->id }}" {{ 47 == $currency->id ? "selected" : "" }}>{{ $currency->name }}</option>
          @endforeach
        </select>
      </div>

      {{-- List --}}
      <div class="mb-3">
        <label class="form-label">{{ get_phrase('Link Product with Page') }}</label>
        <select name="List" id="List" class="form-select">
          <option value="">{{ get_phrase('Select') }}</option>
        </select>
      </div>

      {{-- Price & Selling Price --}}
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">{{ get_phrase('Original Price') }}</label>
          <input type="number" name="price" class="form-control" placeholder="Your Price">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">{{ get_phrase('Selling Price') }}</label>
          <input type="number" name="selling_price" class="form-control" placeholder="Your Selling Price">
        </div>
      </div>

      {{-- Video + Featured --}}
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">{{ get_phrase('Video Url') }}</label>
          <input type="text" name="video_url" class="form-control" placeholder="Video URL">
        </div>
        <div class="col-md-6 mb-3">
          <!--<label class="form-label">{{ get_phrase('Featured Product-Service') }}</label>-->
          <!--<select name="featured" class="form-select">-->
          <!--  <option value="0">{{ get_phrase('select') }}</option>-->
          <!--  <option value="Yes">{{ get_phrase('Yes') }}</option>-->
          <!--  <option value="No" selected>{{ get_phrase('No') }}</option>-->
          <!--</select>-->
        </div>
      </div>

      {{-- Start + End Dates --}}
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">{{ get_phrase('Start Date') }}</label>
          <input type="date" name="start_date" class="form-control">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">{{ get_phrase('End Date') }}</label>
          <input type="date" name="end_date" class="form-control">
        </div>
      </div>

      {{-- Buy Link --}}
      <div class="mb-3">
        <label class="form-label">{{ get_phrase('Enquire link') }}</label>
        <input type="url" name="buy_link" class="form-control" placeholder="{{ get_phrase('Enter the enquire link') }}">
      </div>

      {{-- Description --}}
      <div class="mb-3">
        <label class="form-label">{{ get_phrase('Description') }}</label>
        <textarea name="description" class="form-control" rows="6" placeholder="Your Description"></textarea>
      </div>

      {{-- Product Image --}}
      <div class="mb-3">
        <label class="form-label">{{ get_phrase('Product Image') }}</label>
        <input type="file" multiple id="image" name="multiple_files[]" class="form-control">
      </div>

      {{-- Terms & Conditions --}}
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
        <label class="form-check-label" for="terms">
          I agree to the
          <a onclick="showtermsymodel()" data-toggle="modal" data-target="#termsModal" class="text-primary fw-semibold text-decoration-none">Terms and Conditions</a>
        </label>
      </div>

      <div class="d-grid">
        <input type="submit" class="btn btn-primary" value="Submit">
      </div>
    </form>
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
<!-- Category Modal -->
<div class="modal fade" id="categoriesModal" tabindex="-1" role="dialog" aria-labelledby="categoriesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="categoriesModalLabel">{{ get_phrase('Add Category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form>

                    <!-- Category Name -->
                    <div class="mb-3">
                        <label for="category_name" class="form-label text-black">Category</label>
                        <input id="category_name" type="text" class="form-control @error('category_name') is-invalid @enderror" name="category_name" value="{{ old('category_name') }}" autofocus>
                        @error('category_name')
                        <span class="invalid-tooltip">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Parent Category Selection -->
                    <div class="mb-3">
                        <label class="form-label text-black" for="category_parent_id">Parent</label>
                        <select class="selectpicker form-control @error('category_parent_id') is-invalid @enderror" name="category_parent_id" id="category_parent_id">
                            <option value="0">Add Category</option>
                            @foreach($parent as $key => $printable_category)
                                <option value="{{ $printable_category->id }}" {{ ($printable_category->id == $selectedValue) ? 'selected' : '' }}>
                                    {{ $printable_category->product_category_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_parent_id')
                        <span class="invalid-tooltip">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="text-end">
                        <button type="button" class="btn btn-success" onclick="submitcategory();">
                            Submit
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>



    <!-- Modal categories -->
<div class="modal fade" id="brandModal" tabindex="-1" role="dialog" aria-labelledby="brandModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ get_phrase('Add Brand') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
                <div class="modal-body">
              
               

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="brandname" class="text-black">Brand</label>
                                <input id="brandname" type="text" class="form-control @error('brandname') is-invalid @enderror" name="brandname" value="{{ old('brandname') }}" autofocus>
                                @error('brandname')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       

                        

                       

                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white" onclick="submitbrand();">
                                    Submit
                                </button>
                            </div>
                        </div>

                  
               
    </div>
    </div>
    </div>
    </div>


    <div class="modal fade" id="termsAndConditionsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe src="{{ route('term.view') }}" width="100%" height="400px" style="border:none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
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

$('#List').select2({
        placeholder: "Search for Pages", // Placeholder text
        ajax: {
            url: '/search-pages',  // URL of the search route
            dataType: 'json',
            delay: 250,  // Time delay after typing (for performance)
            data: function (params) {
                return {
                    q: params.term  // Pass the search term as 'q'
                };
            },
            processResults: function (data) {
                // Map the results to Select2 format
                return {
                    results: $.map(data, function (item) {
                        return {
                            id: item.id,  // Value
                            text: item.title  // Displayed text
                        };
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
        url: '/product-categories-autocomplete-ajax',
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
                        text: item.product_category_name,
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
            url: "/product-categories-create-from-select2",
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
                var newOption = new Option(response.product_category_name, response.id, true, true);
                $('#category').append(newOption).trigger('change');
            }
        });
    }
});


      $('#parent').on('change', function () {
    let selectedValue = $(this).val();
    if ($('#category_parent_id').val() !== selectedValue) {
        $('#category_parent_id').val(selectedValue).trigger('change.select2');
    }
});


 $('#parent').select2({
        placeholder: 'Search for parent or sub-category',
        allowClear: true,
        ajax: {
            url: '/category-autocomplete',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return {
                    results: data.map(item => ({
                        id: item.id,
                        text: item.product_category_name
                    }))
                };
            }
        }
    });

   $('#parent').on('select2:select', function (e) {
    const selectedId = e.params.data.id;

    // Call backend to check if it's a subcategory
    $.get('/check-if-subcategory', { id: selectedId }, function (res) {
        if (res.is_subcategory) {
            // ✅ It's a subcategory: set parent in dropdown and add subcategory to tags
            $('#parent').val(res.parent_id).trigger('change');

            // Add subcategory to #category tag selector (if not already present)
            if ($("#category option[value='" + res.subcategory_id + "']").length === 0) {
                const newOption = new Option(res.subcategory_name, res.subcategory_id, true, true);
                $('#category').append(newOption).trigger('change');
            }
        } else {
            // ✅ It's a parent category: clear tag field if needed
            $('#category').val(null).trigger('change');
        }
    }).fail(function () {
        alert("Something went wrong while checking the category.");
    });
});


     
 });
 function showtermsymodel()
{
    $('#termsAndConditionsModal' ).modal('show');
}

 function showbrandmodel(){


$('#brandModal' ).modal('show');
}
 
 
 function showcategorymodel(){


$('#categoriesModal' ).modal('show');
}

function showparentcategorymodel(){
 
$('#parentcategoriesModal' ).modal('show');
}

function submitparentcategory(){
var category_name=$('#parent_category_name').val();
//alert(category_name);
if(category_name!=""){

var ajax_url = "{{route('ajax.storeproductcategories.parent')}}";
      
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
        var ajax_url = "{{route('page.json.parent.product.catgories')}}";

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
                    var city_name = value.product_category_name;
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

var ajax_url = "{{route('ajax.storeproductcategories')}}";
      
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
        var ajax_url = "{{route('page.json.product.catgories')}}";

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
                    var city_name = value.product_category_name;
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


function submitbrand(){
var brandname=$('#brandname').val();
//alert(category_name);
if(brandname!=""){

var ajax_url = "{{route('ajax.store.brand')}}";
      
        jQuery.ajax({
            url: ajax_url,
            method: 'POST',
            data: {
                brandname:brandname,
                _token: '{{csrf_token()}}' 

            },
            success: function(result){
                console.log(result);

               

    if(result > 0)
    {
      // console.log(result);
       
        // $('#category').html("<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
        //$('#category').selectpicker('refresh');
        var ajax_url = "{{route('product.json.brand')}}";

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
                $('#brand').html("<option selected value='0'>Select Brand</option>");
                $.each(JSON.parse(result), function(key, value) {
                    var city_id = value.id;
                    var city_name = value.name;
                    $('#brand').append('<option value="'+ city_id +'">' + city_name +'</option>');
                });
                //$('#category').selectpicker('refresh');
            }});

            $('#brandModal' ).modal('hide');
    }else{
        Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Brand already exists!"
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


function getProductSuggestions() {
        let query = $('#product_title').val();
        if (query.length > 2) {
            $.ajax({
                url: "{{ route('product.suggestions') }}",
                method: "GET",
                data: { query: query },
                success: function (data) {
                    let suggestions = '<ul class="list-group bg-light border">';
                    data.forEach(product => {
                        suggestions += `<li class="list-group-item suggestion-item" onclick="fillProductDetails(${product.id}, '${product.title}')">${product.title}</li>`;
                    });
                    suggestions += '</ul>';
                    $('#suggestion-box').html(suggestions).show();
                }
            });
        } else {
            $('#suggestion-box').hide().html('');
        }
    }

    function fillProductDetails(productId, productTitle) {
    $('#product_title').val(productTitle);
    $('#suggestion-box').hide().html(''); // Hide and clear the suggestions

    // Reset form fields before setting new values
    resetFormFields();

    $.ajax({
        url: "{{ route('product.details') }}",
        method: "GET",
        data: { id: productId },
        success: function (data) {
            $('input[name="title"]').val(data.title);
            $('select[name="producttype"]').val(data.product_type);
            $('select[name="productnaturetype"]').val(data.product_nature_type);
            $('select[name="parent"]').val(data.parent_id);
            $('select[name="currency"]').val(data.currency_id);
            $('input[name="price"]').val(data.product_original_price);
            $('input[name="selling_price"]').val(data.product_selling_price);
            $('input[name="video_url"]').val(data.video_url);
            $('select[name="featured"]').val(data.product_featured_service);
            $('input[name="location"]').val(data.location);
            $('select[name="condition"]').val(data.condition);
            $('select[name="status"]').val(data.status);
            $('input[name="buy_link"]').val(data.buy_link);
            $('#description').val(data.description);

            // Set the brand
            $('#brand').val(data.brand).trigger('change');
            $('#List').val(data.page_id).trigger('change');

            // Handle Parent Category
            if (data.category) {
                let parentIdsArray = data.category.split(','); // Convert CSV into array
                let selectedParentId = parentIdsArray[0]; // Take the first valid value
                $('#parent').val(selectedParentId).trigger('change');
            }

            // Handle Dynamic Category Selection
            if (data.category) {
                let parentIds = data.category.split(','); // Convert CSV to array

                $.ajax({
                    url: '{{ route("category.names") }}',
                    type: 'GET',
                    data: { ids: data.category },
                    success: function(categoryData) {
                        $('#category').empty(); // Clear existing options

                        categoryData.forEach(category => {
                            // Add category options dynamically if they don't exist
                            if ($('#category option[value="' + category.id + '"]').length === 0) {
                                $('#category').append(new Option(category.product_category_name, category.id));
                            }
                        });

                        $('#category').val(parentIds).trigger('change');
                    },
                    error: function(xhr) {
                        console.error("Error fetching category names:", xhr.responseText);
                    }
                });
            }

            // Convert dates and set them in the date input fields
            if (data.startdate && data.enddate) {
                let startDate = convertToDateFormat(data.startdate); // Convert start date
                let endDate = convertToDateFormat(data.enddate); // Convert end date

                $('input[name="start_date"]').val(startDate); // Set start date
                $('input[name="end_date"]').val(endDate); // Set end date
            } else {
                console.error("Start date or end date is missing or improperly formatted");
            }
        },
        error: function(xhr) {
            console.error("Error fetching product details:", xhr.responseText);
        }
    });
}

function resetFormFields() {
  // Reset all fields except product_title to their default values
  $('select').val('').trigger('change');  // Reset select elements
    $('input[type="text"], input[type="number"], input[type="url"], input[type="date"]').val(''); // Reset input fields
    $('#description').html('');  // Clear description field
}


// Function to convert YYYY-MM-DD HH:MM:SS to YYYY-MM-DD
function convertToDateFormat(dateTimeString) {
    return dateTimeString.split(' ')[0]; // Get the first part (YYYY-MM-DD)
}

// Hide suggestions when clicking outside
$(document).on('click', function(event) {
    if (!$(event.target).closest('#product_title, #suggestion-box').length) {
        $('#suggestion-box').hide().html('');
    }
});


// $('form').submit(function(event) {
//     event.preventDefault();
    
//     // Check all  fields
//     var formIsValid = true;
    
//     $('input[], select[], textarea[]').each(function() {
//         if ($(this).val() === '') {
//             formIsValid = false;
//             $(this).next('.error-message').remove();
//             $(this).after('<div class="error-message">This field is </div>');
//         } else {
//             $(this).next('.error-message').remove();  // Remove error message if field is filled
//         }
//     });
    
//     // If form is valid, submit the form
//     if (formIsValid) {
//         this.submit();
//     }
// });


 </script>
