
<?php 
    $selectedCategories = isset($page->product_categories_ids) ? explode(',', $page->product_categories_ids) : []; 
?>
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
<div class="page-tab bg-white border rounded p-3 pb-1">
<form  action="{{ route('page.update',$page->id) }}" method="POST" enctype="multipart/form-data">
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
        <label for="#">{{ get_phrase('Page Name') }}</label>
        <input type="text" class="border-0 bg-secondary" name="name" value="{{ $page->title }}" placeholder="Enter your page Name">
    </div>
    <?php $category_array=explode(',', $page->category_id);?>
    <div class="form-group">
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
    <div class="form-group">
        <label for="#">{{ get_phrase('Tag Category') }}</label>
        <select name="category[]" id="category" class="selectpicker form-control @error('category') is-invalid @enderror"  multiple required>
       
            @foreach ($all_tag_categories as $category )
            <option value="{{ $category->id }}"{{ (in_array($category->id, $category_array)) ? 'selected' : '' }}> {{ $category->category_name }} </option>
            @endforeach
           </select>
           <a  class="text-info pl-2 float-left" onclick="showcategorymodel();" >
                                                <i class="far fa-add"></i>
                                                Click here to add category(If not in list)
                                            </a>
       
    </div>
    <div class="form-group">
        <label for="#">{{ get_phrase('Page BIO') }}</label>
        <textarea class="border-0 bg-secondary content" name="description" id="description" rows="5" placeholder="Description">@php echo script_checker($page->description, false); @endphp</textarea>
    </div>
    <div class="form-group">
        <label for="#">{{ get_phrase('Address') }}</label>
        <input id="address" name="address" type="text" class="border-0 bg-secondary"  required placeholder="Enter your address" value="{{ $page->address }}">
    </div>
    <div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label for="country">{{ get_phrase('Country') }}</label>
            <select name="country" id="country" class="selectpicker form-control @error('country') is-invalid @enderror" >
                <option value="0">Select Country</option>
                @foreach($countries as $key => $country)
                    <option value="{{ $country->id }}" {{ ($country->id==$page->country_id) ? 'selected' : '' }}>{{ $country->country_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-6">
    <div class="form-group">
        <label for="#">{{ get_phrase('State') }}</label>
        <select name="state" id="state" class="selectpicker form-control @error('state') is-invalid @enderror" required>
        @foreach ($all_states as $state )
            <option value="{{ $state->id }}"{{ ($state->id==$page->state_id) ? 'selected' : '' }}> {{ $state->state_name }} </option>
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
        <select name="city" id="city" class="selectpicker form-control @error('city') is-invalid @enderror" required>
        @foreach ($all_cities as $city )
            <option value="{{ $city->id }}"{{ ($city->id==$page->city_id) ? 'selected' : '' }}> {{ $city->city_name }} </option>
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
        <select name="area" id="area" class="selectpicker form-control @error('area') is-invalid @enderror"  required>
        @foreach ($all_areas as $area )
            <option value="{{ $area->id }}"{{ ($area->id==$page->area_id) ? 'selected' : '' }}> {{ $area->area_name }} </option>
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
    <div class="form-group">
        <label for="#">{{ get_phrase('Pincode') }}</label>
        <input id="pincode" name="pincode" type="number" class="border-0 bg-secondary"   placeholder="Enter your pincode"  value="{{ $page->pincode }}" >
    </div>
  </div>
</div>


<div class="row" >
<div class="col-lg-6">
<label for="#">{{ get_phrase('Latitude') }}</label>
        <input id="item_lat" name="item_lat" type="text" class="border-0 bg-secondary"  value="{{ $page->item_lat }}" >
        <a class="lat_lng_select_button btn btn-sm btn-primary text-white">Select on map</a>
</div>

<div class="col-lg-6">

<label for="#">{{ get_phrase('Longitude') }}</label>
<input type="text" class="border-0 bg-secondary" id="item_lng" name="item_lng"  value="{{ $page->item_lng }}" >
</div>

</div>


<div class="row">
<div class="col-lg-6">
<label for="#">{{ get_phrase('Youtube video id') }}</label>
<input type="text" class="border-0 bg-secondary" name="youtube_video_id"  placeholder="Enter Youtube video id" value="{{ $page->item_youtube_id }}">
</div>

<div class="col-lg-6">
<label for="#">{{ get_phrase('Website') }}</label>
<input type="text" class="border-0 bg-secondary" name="website"  placeholder="Enter Website" value="{{ $page->item_website }}">
</div>
</div>

<div class="row">
<div class="col-lg-6">
<label for="#">{{ get_phrase('Business Email') }}</label>
<input type="text" class="border-0 bg-secondary" name="business_email"  placeholder="Enter Business Email" value="{{ $page->item_email }}">
</div>

<div class="col-lg-6">
<label for="#">{{ get_phrase('Business Whatsapp URL') }}</label>
<input type="text" class="border-0 bg-secondary" name="business_whatsapp_url"  placeholder="Enter Business Whatsapp URL" value="{{ $page->item_whatsapp }}">
</div>

</div>

<div class="form-group">
<label for="#">{{ get_phrase('Phone no') }}</label>
<input type="text" class="border-0 bg-secondary" name="item_phone"  placeholder="Enter Phone no" value="{{ $page->item_phone }}">
</div>

<div class="row">
<div class="col-lg-6">
<label for="#">{{ get_phrase('Facebook') }}</label>
<input type="text" class="border-0 bg-secondary" name="facebook"  placeholder="Facebook" value="{{ $page->item_social_facebook }}">
</div>

<div class="col-lg-6">
<label for="#">{{ get_phrase('Twitter') }}</label>
<input type="text" class="border-0 bg-secondary" name="twitter"  placeholder="Twitter" value="{{ $page->item_social_twitter }}">
</div>

</div>


<div class="row">
<div class="col-lg-6">
<label for="#">{{ get_phrase('LinkedIn') }}</label>
<input type="text" class="border-0 bg-secondary" name="linkedIn"  placeholder="LinkedIn" value="{{ $page->item_social_linkedin }}">
</div>

<div class="col-lg-6">
<label for="#">{{ get_phrase('Instagram link') }}</label>
<input type="text" class="border-0 bg-secondary" name="instalink"  placeholder="Instagram link" value="{{ $page->insta_link }}">
</div>

</div>
<?php $product_categories_ids=explode(',', $page->product_categories_ids);?>
<div class="form-group">
        <label for="#">{{ get_phrase('Products/service tags') }}</label>
        <select name="servicecategory[]" id="servicecategory" class="selectpicker form-control @error('servicecategory') is-invalid @enderror" multiple></select>
        <!-- <select name="servicecategory[]" id="servicecategory" class="selectpicker form-control @error('servicecategory') is-invalid @enderror"  multiple >
        @foreach (\App\Models\Category::all() as $category )
            <option value="{{ $category->id }}"{{ (in_array($category->id, $product_categories_ids)) ? 'selected' : '' }}> {{ $category->product_category_name }} </option>
            @endforeach
        </select> -->
    </div>

    <div class="form-group">
        <label for="#">{{ get_phrase('Why Visit Us') }}</label>
        <textarea  name="visitus" class="border-0 bg-secondary content" id="visitus" rows="5" placeholder="Why Visit Us" value="{{ $page->why_visit_us }}">{{ $page->why_visit_us }}</textarea>
    </div>

    <div class="form-group">
        <label for="#">{{ get_phrase('Our Story') }}</label>
        <textarea  name="our_story" class="border-0 bg-secondary content" id="our_story" rows="5" placeholder="Our Story" value="{{ $page->our_story }}">{{ $page->our_story }}</textarea>
    </div>

    <div class="row">
    <div class="col-lg-6">
    <label for="#">{{ get_phrase('Year of Establishment') }}</label>
    <input type="number" class="border-0 bg-secondary" name="yrofest"  placeholder="Year of Establishment" value="{{ $page->year_of_establishment }}">
    </div>

    @php
    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
@endphp

<div class="col-lg-12 mb-3">
    <label><strong>{{ get_phrase('Opening Hours') }}</strong></label>
    <div class="row g-2">
        @foreach ($days as $day)
            @php
                $data = $openingHours[$day] ?? null;

                $isClosed = old("opening_hours.$day.closed") !== null
                            ? old("opening_hours.$day.closed") == '1'
                            : ($data->closed ?? false);

                $openTime = old("opening_hours.$day.open") ?? ($data?->open ? date('H:i', strtotime($data->open)) : '');
                $closeTime = old("opening_hours.$day.close") ?? ($data?->close ? date('H:i', strtotime($data->close)) : '');
            @endphp

            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>{{ ucfirst($day) }}</strong>

                        <div class="d-flex align-items-center gap-2">
                            <span class="status-label fw-bold {{ $isClosed ? 'text-danger' : 'text-success' }}"
                                  id="status_{{ $day }}">
                                {{ $isClosed ? 'Closed' : 'Open' }}
                            </span>

                            <div class="form-check form-switch">
                                <input type="checkbox"
                                       class="form-check-input closed-toggle"
                                       id="closed_{{ $day }}"
                                       name="opening_hours[{{ $day }}][closed]"
                                       value="1"
                                       {{ $isClosed ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 mt-2 time-inputs" id="time_inputs_{{ $day }}">
                        <input type="time"
                               class="form-control"
                               name="opening_hours[{{ $day }}][open]"
                               value="{{ $openTime }}"
                               {{ $isClosed ? 'disabled' : '' }}>

                        <span>to</span>

                        <input type="time"
                               class="form-control"
                               name="opening_hours[{{ $day }}][close]"
                               value="{{ $closeTime }}"
                               {{ $isClosed ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


    
    
    </div>
    <?php $service_offeres_country_ids=explode(',', $page->service_offered_country);?>
    <?php $service_offeres_state_ids=explode(',', $page->service_offered_state);?>
    <?php $service_offeres_city_ids=explode(',', $page->service_offered_city);?>
    <?php $service_offeres_areas_ids=explode(',', $page->service_offeres_areas_ids);
   
    ?>
    <div class="form-group">
    <label for="location">{{ get_phrase('Services Offered Location') }}</label>
    <div class="row">

    <div class="col-lg-3">
            <select name="servicecountry[]" id="servicecountry" class="selectpicker form-control @error('servicecountry') is-invalid @enderror" multiple>
                <option value="">Select Country</option>
                @foreach ($all_countries as $country )
                <option value="{{ $country->id }}"{{ (in_array($country->id, $service_offeres_country_ids)) ? 'selected' : '' }}> {{ $country->country_name }} </option>
                @endforeach
            </select>
        </div>
        <!-- State Dropdown -->
        <div class="col-lg-3">
            <select name="servicestate[]" id="servicestate" class="selectpicker form-control @error('servicestate') is-invalid @enderror" multiple>
                <option value="">Select State</option>
                @foreach ($all_states as $state )
                <option value="{{ $state->id }}"{{ (in_array($state->id, $service_offeres_state_ids)) ? 'selected' : '' }}> {{ $state->state_name }} </option>
                @endforeach
            </select>
        </div>

        <!-- City Dropdown -->
        <div class="col-lg-3">
            <select name="servicecity[]" id="servicecity" class="selectpicker form-control @error('city') is-invalid @enderror" multiple>
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
        <div class="col-lg-3">
           <select name="servicearea[]" id="servicearea" class="selectpicker form-control @error('servicearea') is-invalid @enderror"  multiple >
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
        <textarea  name="policy" class="border-0 bg-secondary content" id="policy" rows="5" placeholder="Return/Refund Policy" value="{{ old('policy') }}">{{ $page->policy }}</textarea>
    </div>
    <br>
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
    
            <div class="faq-form-group">
                <label>Question:</label>
                <input type="text" name="faqs[0][question]" class="border-0 bg-secondary" >
                <br>
                <label>Answer:</label>
                <textarea name="faqs[0][answer]" class="border-0 bg-secondary" ></textarea>
            </div>
        </div>
  

        <button type="button" id="add-faq" class="btn btn-success py-2 px-4 text-white">Add Another FAQ</button>
        <br><br>


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

        <div class="form-group">
    <label for="media">{{ get_phrase('Business Images/Videos') }}</label>
    <input type="file" name="media[]" id="media" class="form-control bg-secondary border-0" multiple accept="image/*,video/*">
</div>

    <div class="form-group">
        <label for="#">{{ get_phrase('Proof of Ownership (Document Upload - Business License, Utility Bill, etc.)') }}</label>
        <input type="file" name="Proof_of_Ownership" class="border-0 bg-secondary" id="image" class="form-control">
    </div>
    <div>
        <label for="">{{ get_phrase('Previous Profile Photo') }}</label> <br>
        <img src="{{ get_page_logo($page->logo, 'logo') }}" alt="">
    </div>
    <div class="form-group">
        <label for="#">{{ get_phrase('Update Profile Photo') }}</label>
        <input type="file" name="image" id="image" class="form-control border-0 bg-secondary">
    </div>
    
    <button type="submit" class="w-100 btn btn-primary">{{ get_phrase('Save Page') }}</button>
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
    document.querySelectorAll('.closed-toggle').forEach(toggle => {
        toggle.addEventListener('change', function () {
            const day = this.id.replace('closed_', '');
            const timeInputs = document.getElementById(`time_inputs_${day}`);
            const status = document.getElementById(`status_${day}`);

            if (this.checked) {
                status.textContent = 'Closed';
                status.classList.remove('text-success');
                status.classList.add('text-danger');
                timeInputs.querySelectorAll('input').forEach(input => input.disabled = true);
            } else {
                status.textContent = 'Open';
                status.classList.remove('text-danger');
                status.classList.add('text-success');
                timeInputs.querySelectorAll('input').forEach(input => input.disabled = false);
            }
        });
    });
</script>

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
   $('#category_parent_id').select2({
        dropdownParent: $('#categoriesModal') // Append to modal to fix z-index issue
    });

    

    let selectedCategories = @json($selectedCategories); // Preselected category IDs

    $('#servicecategory').select2({
        placeholder: 'Select a category',
        ajax: {
            url: '{{ route("categories.search") }}', // Laravel route to fetch categories dynamically
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term }; // Search query
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


    $('#servicecountry').select2({
        placeholder: 'Type Country',
        ajax: {
          url: '/country-autocomplete-ajax',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.country_name,
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
    tags: true, // Enable tagging (create new entries)
    placeholder: 'Type Category',
    multiple: true,
    tokenSeparators: [','],
    ajax: {
        url: '/categories-autocomplete-ajax',
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
            url: "/page-categories-create-from-select2",
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


            
        }});
}

});
 });
 


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
 

 </script>
