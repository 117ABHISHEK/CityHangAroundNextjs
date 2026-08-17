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

</style>
<div class="page-content">
<div class="page-tab bg-white border rounded p-3 pb-1">
<form class="ajaxForm" action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
    <label for="#">{{ get_phrase('Please choose your Product Type*') }}</label>
    <select name="producttype" required class="form-control border-0 bg-secondary" >
                                <option value="0"  >{{ get_phrase('select') }}</option>
                                <option value="Physical"  selected>{{get_phrase('Physical')}}</option>
                                <option value="Affiliate"  >{{get_phrase('Affiliate')}}</option>
                            </select>
   </div>  
   
   <div class="form-group">
    <label for="#">{{ get_phrase('Please choose your Product Nature Type*') }}</label>
    <select name="productnaturetype" required class="form-control border-0 bg-secondary" >
                                <option value="0"  selected>{{ get_phrase('select') }}</option>
                                <option value="Service"  >{{get_phrase('Service')}}</option>
                                <option value="Product"  >{{get_phrase('Product')}}</option>
                            </select>
   </div>  


   <div class="form-group">
        <label for="#">{{ get_phrase('Parent Category') }}</label>
        <select name="parent" id="parent" class="selectpicker form-control @error('parent') is-invalid @enderror"   required>
        <option value="0">Select Parent Category</option>
                                @foreach($parent as $key => $printable_category)
                                    <option value="{{ $printable_category->id }}">{{ $printable_category->product_category_name }}</option>
                                @endforeach
        </select>
        <a  class="text-info pl-2 float-left" onclick="showparentcategorymodel();" >
         <i class="far fa-add"></i>
         Click here to add Parent category(If not in list)
         </a>
    </div>

   

    <!-- <div id="selected-value"></div> -->
    <div class="form-group">
        <label for="#">{{ get_phrase('Tag Category') }}</label>
        <select name="category[]" id="category" class="selectpicker form-control @error('category') is-invalid @enderror"  multiple required>
           
        </select>
        <a  class="text-info pl-2 float-left" onclick="showcategorymodel();" >
                                                <i class="far fa-add"></i>
                                                Click here to add category(If not in list)
                                            </a>
    </div>


    <div class="form-group">
        <label for="#">{{ get_phrase('Product Name') }}</label>
        <input type="text" name="title" class="border-0 bg-secondary" placeholder="Your Product Title">
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <label for="brand">{{get_phrase('Brand')}}</label>
            <select name="brand" id="brand" required class="selectpicker form-control @error('category') is-invalid @enderror" >
                <option value="" disabled selected>{{ get_phrase('Select Brand') }}</option>
                @foreach (\App\Models\Brand::all() as $brand )
                    <option value="{{ $brand->id }}" >{{ ucfirst($brand->name) }}</option>
                @endforeach
            </select>
            <a  class="text-info pl-2 float-left" onclick="showbrandmodel();" >
         <i class="far fa-add"></i>
         Click here to add Brand(If not in list)
         </a>
        </div>
     </div>

     

    <div class="form-group">
        <label for="#">{{ get_phrase('Currency') }}</label>
        <select name="currency" id="currency" required class="form-control border-0 bg-secondary">
            <option value="">{{ get_phrase('Select Currency') }}</option>
            @foreach (\App\Models\Currency::all() as $currency)
                <option value="{{ $currency->id }}">{{ $currency->name }}</option>
            @endforeach
        </select>
    </div>


    <div class="form-group">
        <label for="#">{{ get_phrase('List') }}</label>
        <select name="List" id="List" required class="selectpicker form-control @error('category') is-invalid @enderror">
            <option value="">{{ get_phrase('Select') }}</option>
            @foreach ($listing as $list)
                <option value="{{ $list->id }}">{{ $list->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="row">
        <div class="col-lg-6">
        <div class="form-group">
        <label for="#">{{ get_phrase('Original Price') }}</label>
        <input type="number" name="price" class="border-0 bg-secondary" placeholder="Your Price">
    </div>
        </div>

        <div class="col-lg-6">
        <div class="form-group">
        <label for="#">{{ get_phrase('Selling Price') }}</label>
        <input type="number" name="selling_price" class="border-0 bg-secondary" placeholder="Your Selling Price">
    </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
        <div class="form-group">
        <label for="#">{{ get_phrase('Video Url') }}</label>
        <input type="text" name="video_url" class="border-0 bg-secondary" placeholder="Video URL">
    </div>
        </div>

        <div class="col-lg-6">
        <div class="form-group">
        <label for="#">{{ get_phrase('Featured Product-Service') }}</label>
        <select name="featured" required class="form-control border-0 bg-secondary" >
                                <option value="0"  >{{ get_phrase('select') }}</option>
                                <option value="Yes"  >{{get_phrase('Yes')}}</option>
                                <option value="No" selected >{{get_phrase('No')}}</option>
                            </select>
    </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-6">
        <div class="form-group">
        <label for="#">{{ get_phrase('Start Date') }}</label>
        <input type="date" name="start_date" class="border-0 bg-secondary" placeholder="Start Date">
    </div>
        </div>

        <div class="col-lg-6">
        <div class="form-group">
        <label for="#">{{ get_phrase('End Date') }}</label>
        <input type="date" name="end_date" class="border-0 bg-secondary" placeholder="End Date">
    </div>
        </div>
    </div>
   

    
    <div class="form-group">
        <label for="#">{{ get_phrase('Location') }}</label>
        <input type="text" name="location" class="border-0 bg-secondary" placeholder="Your Location">
    </div>
    <!-- <div class="form-group row">
        <div class="col-md-12">
            <label for="category">{{ get_phrase('Category') }}</label>
            <select name="category" required class="form-control border-0 bg-secondary">
                <option value="" disabled selected>{{ get_phrase('Select Category') }}</option>
                @foreach (\App\Models\Category::all() as $category )
                    <option value="{{ $category->id }}" >{{ ucfirst($category->name) }}</option>
                @endforeach
            </select>
        </div>
     </div> -->
     <div class="form-group row">
        <div class="col-md-12">
            <label for="condition">{{ get_phrase('Condition') }}</label>
            <select name="condition" required class="form-control border-0 bg-secondary">
                <option value="" disabled selected>{{ get_phrase('Select Condition') }}</option>
                <option value="used" >{{ get_phrase('Used') }}</option>
                <option value="new" >{{ get_phrase('New') }}</option>
            </select>
        </div>
     </div>

     <div class="form-group row">
        <div class="col-md-12">
            <label for="status">{{ get_phrase('Available') }}</label>
            <select name="status" required class="form-control border-0 bg-secondary">
                <option value="" disabled selected>{{ get_phrase('Select Status') }}</option>
                <option value="1" >{{ get_phrase('In Stock') }}</option>
                <option value="0" >{{ get_phrase('Out Of Stock') }}</option>
            </select>
        </div>
     </div>

    

     <div class="form-group">
        <label for="buy_link">{{ get_phrase('Enquire link') }}</label>
        <input type="url" name="buy_link" id="buy_link" class="border-0 bg-secondary" placeholder="{{get_phrase('Enter the enquire link')}}">
    </div>

    <div class="form-group">
        <label for="#">{{ get_phrase('Description') }}</label>
        <textarea type="text" name="description" class="border-0 bg-secondary content" id="description" rows="10" placeholder="Your Description"></textarea>
    </div>
    <div id="frames"></div>
    <div class="form-group">
        <label for="#">{{ get_phrase('Product Image') }}</label>
        <input type="file" multiple id="image" class="border-0 bg-secondary" name="multiple_files[]">
    </div>
    <input type="submit" class="btn btn-primary"  value="Submit">
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
                                @foreach($parent as $key => $printable_category)
                                    <option value="{{ $printable_category->id }}" {{ ($printable_category->id==$selectedValue) ? 'selected' : '' }}>{{ $printable_category->product_category_name }}</option>
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


@include('frontend.initialize')

<script>
     
 $(document).ready(function() {
    $('.selectpicker').select2();
 $('#category').select2({
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



 </script>
