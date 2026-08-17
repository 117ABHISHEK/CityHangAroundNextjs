
<?php 
    $selectedCategories = isset($page_details->product_categories_ids) ? explode(',', $page_details->product_categories_ids) : []; 
?>
<link href="{{asset('assets/frontend/leafletjs/leaflet.css')}}" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
              <h4>{{ get_phrase('Edit your page information') }}</h4>
              
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
              <form method="POST" action="{{ route('admin.page.updated', $page_details->id) }}" enctype="multipart/form-data">
                  @csrf
                  <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    @if($page_details->item_type==1)
                                    <input checked class="form-check-input" type="radio" name="item_type" id="item_type_regular" value="1" aria-describedby="item_type_regularHelpBlock">
                                    @else
                                    <input  class="form-check-input" type="radio" name="item_type" id="item_type_regular" value="1" aria-describedby="item_type_regularHelpBlock">
                                    @endif
                                    <label class="form-check-label" for="item_type_regular">
                                        Regular
                                    </label>
                                   
                                </div>
                                <small id="item_type_regularHelpBlock" class="form-text text-muted">
                                For business that has a physical address
                                    </small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                @if($page_details->item_type==0)
                                    <input checked class="form-check-input" type="radio" name="item_type" id="item_type_online" value="0" aria-describedby="item_type_onlineHelpBlock">
                                    @else
                                    <input class="form-check-input" type="radio" name="item_type" id="item_type_online" value="0" aria-describedby="item_type_onlineHelpBlock">
                                    @endif
                                    <label class="form-check-label" for="item_type_online">
                                       Online
                                    </label>
                                  
                                </div>
                                <small id="item_type_regularHelpBlock" class="form-text text-muted">
                                For business that entirely online with no physical address
                                    </small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="item_status" class="text-black">Status</label>
                                <select class="form-select eForm-control select2 @error('item_status') is-invalid @enderror" name="item_status">

                                    <option value="1" {{ (1==$page_details->item_status) ? 'selected' : '' }}>Submitted</option>
                                    <option value="2" {{ (2==$page_details->item_status) ? 'selected' : '' }}>Published</option>
                                    <option value="3" {{ (3==$page_details->item_status) ? 'selected' : '' }}>Suspended</option>

                                </select>
                                @error('item_status')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                   
                               
                            </div>
                            <div class="col-md-6">
                                <label for="item_featured" class="text-black">Featured</label>
                                <select class="form-select eForm-control select2 @error('item_featured') is-invalid @enderror" name="item_featured">

                                    <option value="1" {{ (1==$page_details->item_featured) ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ (0==$page_details->item_featured) ? 'selected' : '' }}>No</option>

                                    </select>
                                    @error('item_featured')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>
                        </div>
                  <div class="mb-3">
                    <label for="title" class="form-label eForm-label">{{ get_phrase('Page title') }}</label>
                    <input type="text" class="form-control eForm-control" value="{{$page_details->title}}" id="title" name="title" placeholder="Page title">
                  </div>
                  <?php $category_array=explode(',', $page_details->category_id);
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
                    @foreach (\App\Models\Pagecategory::all() as $category )
                        <option value="{{ $category->id }}"{{ (in_array($category->id, $category_array)) ? 'selected' : '' }}> {{ $category->category_name }} </option>
                        @endforeach
                    </select>
                      <a  class="text-info pl-2 float-left" onclick="showcategorymodel();" >
                                                <i class="far fa-add"></i>
                                                Click here to add category(If not in list)
                                            </a>
                  </div>

                  <div class="mb-3">
                      <label for="description" class="form-label eForm-label">{{ get_phrase('Page details') }}</label>
                      <textarea id="description" name="description" class="content">{{$page_details->description}}</textarea>
                  </div>

                   <div class="form-group">
        <label for="#">{{ get_phrase('Address') }}</label>
        <input id="address" name="address" type="text" class="form-control eForm-control"  required placeholder="Enter your address" value="{{ $page_details->address }}">
    </div>
    <div class="row">
    <div class="col-lg-6">
    <div class="form-group">
        <label for="#">{{ get_phrase('State') }}</label>
        <select name="state" id="state" class="form-select eForm-control select2 @error('state') is-invalid @enderror" required>
        @foreach ($all_states as $state )
            <option value="{{ $state->id }}"{{ ($state->id==$page_details->state_id) ? 'selected' : '' }}> {{ $state->state_name }} </option>
            @endforeach
        </select>
    </div>
</div>
<div class="col-lg-6">
    <div class="form-group">
        <label for="#">{{ get_phrase('City') }}</label>
        <select name="city" id="city" class="form-select eForm-control select2 @error('city') is-invalid @enderror" required>
        @foreach ($all_cities as $city )
            <option value="{{ $city->id }}"{{ ($city->id==$page_details->city_id) ? 'selected' : '' }}> {{ $city->city_name }} </option>
            @endforeach
        </select>
        <a  class="text-info pl-2 float-left" onclick="showcitymodel();" >
                                                <i class="far fa-add"></i>
                                                Suggest City
                                            </a>
    </div>
    </div>
   </div>
   <div class="row">
   <div class="col-lg-6">
    <div class="form-group">
        <label for="#">{{ get_phrase('Area') }}</label>
        <select name="area" id="area" class="form-select eForm-control select2 @error('area') is-invalid @enderror"  required>
        @foreach ($all_areas as $area )
            <option value="{{ $area->id }}"{{ ($area->id==$page_details->area_id) ? 'selected' : '' }}> {{ $area->area_name }} </option>
            @endforeach
        </select>
        <a  class="text-info pl-2 float-center" onclick="showareamodel();" data-bs-toggle="modal">
        <i class="far fa-add"></i> Suggest Area</a>
        @error('type')
                                                            <div class="alert alert-danger">{{$message}}</div>
                                                            @enderror
    </div>
  </div>
  <div class="col-lg-6">
    <div class="form-group">
        <label for="#">{{ get_phrase('Pincode') }}</label>
        <input id="pincode" name="pincode" type="number" class="form-control eForm-control"  required placeholder="Enter your pincode"  value="{{ $page_details->pincode }}" >
    </div>
  </div>
</div>


<div class="row" >
<div class="col-lg-6">
<label for="#">{{ get_phrase('Latitude') }}</label>
        <input id="item_lat" name="item_lat" type="text" class="form-control eForm-control"  value="{{ $page_details->item_lat }}" >
        <a class="lat_lng_select_button btn btn-sm btn-primary text-white">Select on map</a>
</div>

<div class="col-lg-6">

<label for="#">{{ get_phrase('Longitude') }}</label>
<input type="text" class="form-control eForm-control" id="item_lng" name="item_lng"  value="{{ $page_details->item_lng }}" >
</div>

</div>


<div class="row">
<div class="col-lg-6">
<label for="#">{{ get_phrase('Youtube video id') }}</label>
<input type="text" class="form-control eForm-control" name="youtube_video_id"  placeholder="Enter Youtube video id" value="{{ $page_details->item_youtube_id }}">
</div>

<div class="col-lg-6">
<label for="#">{{ get_phrase('Website') }}</label>
<input type="text" class="form-control eForm-control" name="website"  placeholder="Enter Website" value="{{ $page_details->item_website }}">
</div>
</div>

<div class="row">
<div class="col-lg-6">
<label for="#">{{ get_phrase('Business Email') }}</label>
<input type="text" class="form-control eForm-control" name="business_email"  placeholder="Enter Business Email" value="{{ $page_details->item_email }}">
</div>

<div class="col-lg-6">
<label for="#">{{ get_phrase('Business Whatsapp URL') }}</label>
<input type="text" class="form-control eForm-control" name="business_whatsapp_url"  placeholder="Enter Business Whatsapp URL" value="{{ $page_details->item_whatsapp }}">
</div>

</div>

<div class="row">
<div class="col-lg-6">
<label for="#">{{ get_phrase('Facebook') }}</label>
<input type="text" class="form-control eForm-control" name="facebook"  placeholder="Facebook" value="{{ $page_details->item_social_facebook }}">
</div>

<div class="col-lg-6">
<label for="#">{{ get_phrase('Twitter') }}</label>
<input type="text" class="form-control eForm-control" name="twitter"  placeholder="Twitter" value="{{ $page_details->item_social_twitter }}">
</div>

</div>


<div class="row">
<div class="col-lg-6">
<label for="#">{{ get_phrase('LinkedIn') }}</label>
<input type="text" class="form-control eForm-control" name="linkedIn"  placeholder="LinkedIn" value="{{ $page_details->item_social_linkedin }}">
</div>

<div class="col-lg-6">
</div>

<?php $product_categories_ids=explode(',', $page_details->product_categories_ids);?>
<div class="form-group">
        <label for="servicecategory">{{ get_phrase('Products/service tags') }}</label>
        <select name="servicecategory[]" id="servicecategory" class="form-select eForm-control" multiple></select>

    </div>

    <div class="form-group">
        <label for="#">{{ get_phrase('Why Visit Us') }}</label>
        <textarea  name="visitus" class="border-0 bg-secondary content" id="visitus" rows="5" placeholder="Why Visit Us" value="{{ old('visitus') }}">{{ $page_details->why_visit_us }}</textarea>
    </div>

    <div class="form-group">
        <label for="#">{{ get_phrase('Our Story') }}</label>
        <textarea  name="our_story" class="border-0 bg-secondary content" id="our_story" rows="5" placeholder="Our Story" value="{{ old('our_story') }}">{{ $page_details->our_story }}</textarea>
    </div>

    <div class="row">
    <div class="col-lg-6">
    <label for="#">{{ get_phrase('Year of Establishment') }}</label>
    <input type="number" class="form-control eForm-control" name="yrofest"  placeholder="Year of Establishment" value="{{  $page_details->year_of_establishment }}">
    </div>

    <div class="col-lg-6">
    <label for="#">{{ get_phrase('Opening Hours') }}</label>
    <input type="text" class="form-control eForm-control" name="open_hours"  placeholder="Opening Hours" value="{{ $page_details->open_hours }}">
    </div>

    </div>

    <?php $service_offeres_state_ids=explode(',', $page_details->service_offered_state);?>
    <?php $service_offeres_city_ids=explode(',', $page_details->service_offered_city);?>
    <?php $service_offeres_areas_ids=explode(',', $page_details->service_offeres_areas_ids);
   
    ?>

    <div class="form-group">
    <label for="location">{{ get_phrase('Services Offered Location') }}</label>
    <div class="row">
        <!-- State Dropdown -->
        <div class="col-lg-4">
            <select name="servicestate[]" id="servicestate" class="selectpicker form-control" multiple>
            <option value="">Select State</option>
                @foreach ($all_states as $state )
                <option value="{{ $state->id }}"{{ (in_array($state->id, $service_offeres_state_ids)) ? 'selected' : '' }}> {{ $state->state_name }} </option>
                @endforeach
            </select>
        </div>

        <!-- City Dropdown -->
        <div class="col-lg-4">
            <select name="servicecity[]" id="servicecity" class="form-select eForm-control select2 @error('city') is-invalid @enderror" multiple>
            <option value="">Select City</option>
                @foreach ($all_service_city as $city )
                <option value="{{ $city->id }}"{{ (in_array($city->id, $service_offeres_city_ids)) ? 'selected' : '' }}> {{ $city->city_name }} </option>
                @endforeach
            </select>
            <a class="text-info pl-2 float-left" onclick="showcitymodel();">
                <i class="far fa-add"></i> Suggest City
            </a>
        </div>

        <!-- Area Dropdown -->
        <div class="col-lg-4">
           <select name="servicearea[]" id="servicearea" class="form-select eForm-control select2 @error('servicearea') is-invalid @enderror"  multiple >
           @foreach ($all_service_areas as $area )
            <option value="{{ $area->id }}"{{ (in_array($area->id, $service_offeres_areas_ids)) ? 'selected' : '' }}> {{ $area->area_name }} </option>
            @endforeach
           </select>
            <a class="text-info pl-2 float-left" onclick="showareamodel();" data-bs-toggle="modal">
                <i class="far fa-add"></i> Suggest Area
            </a>
        </div>
    </div>
    
</div>




<div class="form-group">
        <label for="#">{{ get_phrase('Return/Refund Policy') }}</label>
        <textarea  name="policy" class="border-0 bg-secondary content" id="policy" rows="5" placeholder="Return/Refund Policy" value="{{ old('policy') }}">{{ $page_details->policy }}</textarea>
    </div>


    <div  class="form-group">
    <label for="#">{{ get_phrase('FAQ') }}</label>
    </div>
    <div class="form-group">
    <div class="accordion" id="faqAccordion">
        @foreach ($page_faq as $faq)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $faq->id }}">
                    <button
                        class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse{{ $faq->id }}"
                        aria-expanded="false"
                        aria-controls="collapse{{ $faq->id }}"
                    >
                        {{ $faq->question }}
                    </button>
                </h2>
                <div
                    id="collapse{{ $faq->id }}"
                    class="accordion-collapse collapse"
                    aria-labelledby="heading{{ $faq->id }}"
                    data-bs-parent="#faqAccordion"
                >
                    <div class="accordion-body">
                        <p>{{ $faq->answer }}</p>
                    </div>
                    <!-- Delete button in accordion footer -->
                    <div class="accordion-footer text-end">
                        <button class="btn btn-success py-2 px-4 text-white" type="button" onclick="deletefaq('{{ $faq->id }}')">
                            {{ get_phrase('Delete') }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


    <div id="faq-container" class="form-group">
    <label for="#">{{ get_phrase('FAQ') }}</label>
            <div class="faq-form-group">
                <label>Question:</label>
                <input type="text" name="faqs[0][question]" class="form-control eForm-control" >
                <br>
                <label>Answer:</label>
                <textarea name="faqs[0][answer]" class="form-control eForm-control" ></textarea>
            </div>
        </div>

        <button type="button" id="add-faq" class="btn btn-success py-2 px-4 text-white">Add Another FAQ</button>


        <div class="container">
    <h2>Media Files</h2>
   <table class="table table-bordered">
    <thead>
        <tr>
            <th>Preview</th>
            <th>File Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($media as $item)
            <tr id="media-{{ $item->id }}">
                <td>
                    @if($item->file_type === 'image')
                        <img src="{{ asset('storage/pages/media/' . $item->file) }}"
                             alt="Image"
                             style="width: 100px; height: auto; object-fit: cover; border-radius: 5px;">
                    @elseif($item->file_type === 'video')
                        <video width="120" height="80" style="border-radius: 5px;" controls>
                            <source src="{{ asset('storage/pages/media/' . $item->file) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <a href="{{ asset('storage/pages/media/' . $item->file) }}" target="_blank">Download</a>
                    @endif
                </td>
                <td>{{ ucfirst($item->file_type ?? 'Unknown') }}</td>
                <td>
                    <button type="button" onclick="deleteMedia({{ $item->id }})" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
        <br><br>


        <div class="form-group">
    <label for="media">{{ get_phrase('Business Images/Videos') }}</label>
    <input type="file" name="media[]" id="media" class="form-control eForm-control-file" multiple accept="image/*,video/*">
</div>

    <div class="form-group">
        <label for="#">{{ get_phrase('Proof of Ownership (Document Upload - Business License, Utility Bill, etc.)') }}</label>
        <input type="file" name="Proof_of_Ownership" class="form-control eForm-control-file" id="image" class="form-control">
    </div>

                  <div class="mb-3">
                      <label for="logo" class="form-label eForm-label">{{ get_phrase('Logo') }}</label>
                      <input id="logo" class="form-control eForm-control-file" type="file" name="logo">
                  </div>

                  <div class="mb-3">
                      <label for="coverphoto" class="form-label eForm-label">{{ get_phrase('Cover photo') }}</label>
                      <input id="coverphoto" class="form-control eForm-control-file" type="file" name="coverphoto">
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
<div class="modal fade" id="categoriesModal" tabindex="-1" role="dialog" aria-labelledby="categoriesModal" aria-hidden="true" style="height:700px;">
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

                       
                        <label class="text-black" for="category_parent_id">Parent</label>
                        <div class="row form-group">
                            <div class="col-md-12">
                            
                            <select class="form-select eForm-control select2 @error('category_parent_id') is-invalid @enderror" name="category_parent_id" id="category_parent_id" >
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
                      

                        

                       

                        <div class="row form-group" style="margin-top:10px;">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" onclick="submitcategory();">
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
                                <button type="submit" class="btn btn-primary" onclick="submitcity();">
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
                                <button type="submit" class="btn btn-primary" onclick="submitarea();">
                                  Create
                                </button>
                            </div>
                        </div>
        </div>
        
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
                <input type="text" name="faqs[${faqIndex}][question]" class="form-control eForm-control">
                <br>
                <label>Answer:</label>
                <textarea name="faqs[${faqIndex}][answer]" class="form-control eForm-control"></textarea>
            `;
            container.appendChild(faqGroup);

            faqIndex++;
        });
    </script>

    
<script>
     @section('backend_custom_js')
 $(document).ready(function() {
    $('.selectpicker').select2();
   
   

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


      $('#servicestate').select2({
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

      
      let selectedCategories = @json($selectedCategories); 

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

      // Fetch selected categories and pre-load them
    if (selectedCategories.length > 0) {
        $.ajax({
            url: '{{ route("categories.selected") }}',
            type: 'GET',
            data: { ids: selectedCategories },
            success: function(data) {
                let selectedOptions = data.map(item => new Option(item.product_category_name, item.id, true, true));
                $('#servicecategory').append(selectedOptions).trigger('change');
            }
        });
    }


      $('#servicecity').select2({
        placeholder: 'Type City',
        ajax: {
          url: '/city-autocomplete-ajax',
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
                search: params.term, // Search term from user input
                selectedStates: $('#servicestate').val() // Pass currently selected values
            };
        },
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.city_name,
                        id: item.id
                    }
                })
            };
          },
          cache: true
        }
      });


      $('#servicearea').select2({
        placeholder: 'Type Area',
        ajax: {
          url: '/area-autocomplete-ajax',
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
                search: params.term, // Search term from user input
                selectedStates: $('#servicecity').val() // Pass currently selected values
            };
        },
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.area_name,
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
          url: '/categories-autocomplete-ajax',
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
            let parsed = typeof result === 'string' ? JSON.parse(result) : result;
            $('#city').html("<option selected value='0'>Select City</option>");
            $.each(parsed, function(key, value) {
                var city_id = value.id;
                var city_name = value.city_name;
                $('#city').append('<option value="'+ city_id +'">' + city_name + '</option>');
            });
            $('#city').trigger('change');
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
            let parsed = typeof result === 'string' ? JSON.parse(result) : result;
            $('#area').html("<option selected value='0'>Select Area</option>");
            $.each(parsed, function(key, value) {
                var city_id = value.id;
                var city_name = value.area_name;
                $('#area').append('<option value="'+ city_id +'">' + city_name + '</option>');
            });
            $('#area').trigger('change');
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
                    let parsed = typeof result === 'string' ? JSON.parse(result) : result;
                    $('#city').html("<option selected value='0'>Select City</option>");
                    $.each(parsed, function(key, value) {
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
                    $('#city').trigger('change');
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
                    let parsed = typeof result === 'string' ? JSON.parse(result) : result;
                    $('#area').html("<option selected value='0'>Select Area</option>");
                    $.each(parsed, function(key, value) {
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
                    $('#area').trigger('change');
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
                let parsed = typeof result === 'string' ? JSON.parse(result) : result;
                $('#city').html("<option selected value='0'>Select City</option>");
                $.each(parsed, function(key, value) {
                    var city_id = value.id;
                    var city_name = value.city_name;
                    $('#city').append('<option value="'+ city_id +'">' + city_name + '</option>');
                });
                $('#city').trigger('change');
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



function submitcategory(){
var category_name=$('#category_name').val();
var category_parent_id=$('#category_parent_id').val();
//alert(category_name);
if(category_name!=""){

var ajax_url = "{{route('ajax.storecategories')}}";
      
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
        var ajax_url = "{{route('page.json.catgories')}}";

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
                let parsed = typeof result === 'string' ? JSON.parse(result) : result;
                $('#category').html("<option selected value='0'>Select Category</option>");
                $.each(parsed, function(key, value) {
                    var city_id = value.id;
                    var city_name = value.category_name;
                    var parent = value.parent;
                    $('#category').append('<option value="'+ city_id +'">' + city_name + ' | '+ parent + '</option>');
                });
                $('#category').trigger('change');
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
                            let parsed = typeof result === 'string' ? JSON.parse(result) : result;
                            $('#area').html("<option selected value='0'>Select Area</option>");
                            $.each(parsed, function(key, value) {
                                var city_id = value.id;
                                var city_name = value.area_name;
                                $('#area').append('<option value="'+ city_id +'">' + city_name + '</option>');
                            });
                            $('#area').trigger('change');
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

function submitparentcategory(){
var category_name=$('#parent_category_name').val();
//alert(category_name);
if(category_name!=""){

var ajax_url = "{{route('ajax.storecategories')}}";
      
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
        var ajax_url = "{{route('page.json.parent.catgories')}}";

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
                let parsed = typeof result === 'string' ? JSON.parse(result) : result;
                $('#parent').html("<option selected value='0'>Select Category</option>");
                $.each(parsed, function(key, value) {
                    var city_id = value.id;
                    var city_name = value.category_name;
                    var parent = value.parent;
                    $('#parent').append('<option value="'+ city_id +'">' + city_name +'</option>');
                });
                $('#parent').trigger('change');
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


function deletefaq(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to undo this action!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            var ajax_url = '/pages/delete-faq/' + id;

            $.ajax({
                url: ajax_url,
                method: 'GET',  // Should be DELETE, but your backend might only support GET
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Your FAQ has been deleted.',
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });

                    // Remove the deleted FAQ from the UI
                    $("#faq-" + id).fadeOut(500, function () {
                        $(this).remove();
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.',
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Cancelled',
                text: 'The FAQ was not deleted.',
            });
        }
    });
}

function deleteMedia(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to undo this action!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/media/' + id,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            timer: 1500
                        }).then(() => {
                            $("#media-" + id).fadeOut(500, function () {
                                $(this).remove();
                            });
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.',
                    });
                }
            });
        }
    });
}
 

 </script>
 @endsection