<link href="{{asset('assets/frontend/leafletjs/leaflet.css')}}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap Select -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

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
<form id="listing-form"  action="{{ route('page.store') }}" method="POST" enctype="multipart/form-data">
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
        <label for="#">{{ get_phrase('Page Name') }} <span class="text-danger">*</span></label>
        <input type="text" class="border-0 bg-secondary" name="name"  placeholder="Enter your page Name" value="{{ old('name') }}">
    </div>

    <div class="form-group">
        <label for="#">{{ get_phrase('Parent Category') }} <span class="text-danger">*</span></label>
        <select name="parent" id="parent" class="selectpicker form-control @error('parent') is-invalid @enderror">
            <option value="">Select Parent Category</option>
            @foreach($parent as $printable_category)
                <option value="{{ $printable_category->id }}" 
                    {{ old('parent') == $printable_category->id ? 'selected' : '' }}>
                    {{ $printable_category->category_name }}
                </option>
            @endforeach
        </select>

        <a  class="text-info pl-2 float-left" onclick="showparentcategorymodel();" >
                                                <i class="far fa-add"></i>
                                                Click here to add Parent category(If not in list)
                                            </a>
    </div>
    <div class="form-group">
        <label for="#">{{ get_phrase('Tag Category') }} <span class="text-danger">*</span></label>
        <select name="category[]" id="category" class="selectpicker form-control @error('category') is-invalid @enderror" multiple>
            @if(old('category'))
                @foreach(old('category') as $selectedCategory)
                    <option value="{{ $selectedCategory }}" selected>{{ \App\Models\Pagecategory::find($selectedCategory)->category_name ?? '' }}</option>
                @endforeach
            @endif
        </select>

        <a  class="text-info pl-2 float-left" onclick="showcategorymodel();" >
                                                <i class="far fa-add"></i>
                                                Click here to add category(If not in list)
                                            </a>
    </div>
    <div class="form-group">
        <label for="#">{{ get_phrase('Page BIO') }}</label>
        <textarea  name="description" class="border-0 bg-secondary content" id="description" rows="5" placeholder="Description" value="{{ old('description') }}"></textarea>
    </div>
   

<div class="row">
<div class="col-lg-6">
<label for="#">{{ get_phrase('Address') }}</label>
 <input id="address" name="address" type="text" class="border-0 bg-secondary"   placeholder="Enter your address" value="{{ old('address') }}"/>
</div>
<div class="col-lg-6">
<label for="#">{{ get_phrase('Phone no') }}</label>
<input type="text" class="border-0 bg-secondary" name="item_phone"  placeholder="Enter Phone no" value="{{ old('item_phone') }}">
</div>
</div>
<div class="row">
    <!-- Country Dropdown -->
    <div class="col-lg-6">
        <div class="form-group">
            <label for="country">{{ get_phrase('Country') }} <span class="text-danger">*</span></label>
            <select name="country" id="country" class="selectpicker form-control @error('country') is-invalid @enderror" >
                <option value="0">Select Country</option>
                @foreach($countries as $key => $country)
                    <option value="{{ $country->id }}" selected>{{ $country->country_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- State Dropdown -->
    <div class="col-lg-6">
        <div class="form-group">
            <label for="state">{{ get_phrase('State') }} <span class="text-danger">*</span></label>
            <select name="state" id="state" class="selectpicker form-control @error('state') is-invalid @enderror" >
                <option value="">Select State</option>
                <!-- States will be dynamically loaded based on selected country -->
            </select>
            <input type="hidden" id="old_state_id" value="{{ old('state') }}">
        </div>
    </div>
</div>


   
<div class="row">
    <!-- City Dropdown -->
    <div class="col-lg-6">
        <div class="form-group">
            <label for="city">{{ get_phrase('City') }} <span class="text-danger">*</span></label>
            <select name="city" id="city" class="selectpicker form-control @error('city') is-invalid @enderror" >
                <option value="">Select City</option>
                <!-- Cities will be dynamically loaded -->
            </select>
            <input type="hidden" id="old_city_id" value="{{ old('city') }}">
            <a class="text-info pl-2 float-left" onclick="showcitymodel();">
                <i class="far fa-add"></i> Suggest City
            </a>
        </div>
    </div>

    <!-- Area Dropdown -->
    <div class="col-lg-6">
        <div class="form-group">
            <label for="area">{{ get_phrase('Area') }} <span class="text-danger">*</span></label>
            <select name="area" id="area" class="selectpicker form-control @error('area') is-invalid @enderror" >
                <option value="">Select Area</option>
                <!-- Areas will be dynamically loaded -->
            </select>
            <input type="hidden" id="old_area_id" value="{{ old('area') }}">
            <a class="text-info pl-2 float-left" onclick="showareamodel();" data-bs-toggle="modal">
                <i class="far fa-add"></i> Suggest Area
            </a>
        </div>
    </div>
</div>

<!-- Error Message -->
@error('type')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

<div class="row">
  <div class="col-lg-6">
        <label for="#">{{ get_phrase('Pincode') }}</label>
        <input id="pincode" name="pincode" type="number" class="border-0 bg-secondary" name="address"  placeholder="Enter your pincode" >
   
  </div>
  <div class="col-lg-6">
</div>
</div>


<div class="row" >
<div class="col-lg-6">
<label for="#">{{ get_phrase('Latitude') }}</label>
        <input id="item_lat" name="item_lat" type="text" class="border-0 bg-secondary"   value="{{ old('item_lat') }}" >
        <a class="lat_lng_select_button btn btn-sm btn-primary text-white">Select on map</a>
</div>

<div class="col-lg-6">

<label for="#">{{ get_phrase('Longitude') }}</label>
<input type="text" class="border-0 bg-secondary" id="item_lng" name="item_lng"  value="{{ old('item_lng') }}" >
</div>

</div>


<div class="row">
<div class="col-lg-6">
<label for="#">{{ get_phrase('Youtube video id') }}</label>
<input type="text" class="border-0 bg-secondary" name="youtube_video_id"  placeholder="Enter Youtube video id" value="{{ old('name') }}">
</div>

<div class="col-lg-6">
<label for="#">{{ get_phrase('Website') }}</label>
<input type="text" class="border-0 bg-secondary" name="website"  placeholder="Enter Website" value="{{ old('website') }}">
</div>
</div>

<div class="row">
<div class="col-lg-6">
<label for="#">{{ get_phrase('Business Email') }}</label>
<input type="text" class="border-0 bg-secondary" name="business_email"  placeholder="Enter Business Email" value="{{ old('business_email') }}">
</div>

<div class="col-lg-6">
<label for="#">{{ get_phrase('Business Whatsapp URL') }}</label>
<input type="text" class="border-0 bg-secondary" name="business_whatsapp_url"  placeholder="Enter Business Whatsapp URL" value="{{ old('website') }}">
</div>

</div>

<div class="row">
<div class="col-lg-6">
<label for="#">{{ get_phrase('Facebook') }}</label>
<input type="text" class="border-0 bg-secondary" name="facebook"  placeholder="Facebook" value="{{ old('facebook') }}">
</div>

<div class="col-lg-6">
<label for="#">{{ get_phrase('Twitter') }}</label>
<input type="text" class="border-0 bg-secondary" name="twitter"  placeholder="Twitter" value="{{ old('twitter') }}">
</div>

</div>


<div class="row">
<div class="col-lg-6">
<label for="#">{{ get_phrase('LinkedIn') }}</label>
<input type="text" class="border-0 bg-secondary" name="linkedIn"  placeholder="LinkedIn" value="{{ old('linkedIn') }}">
</div>

<div class="col-lg-6">
<label for="#">{{ get_phrase('Instagram link') }}</label>
<input type="text" class="border-0 bg-secondary" name="instalink"  placeholder="Instagram link" value="{{ old('instalink') }}">
</div>

</div>

<div class="form-group">
        <label for="#">{{ get_phrase('Products/service tags') }}</label>
        <select name="servicecategory[]" id="servicecategory" class="selectpicker form-control @error('servicecategory') is-invalid @enderror"  multiple >
           
        </select>
    </div>

    <div class="form-group">
        <label for="#">{{ get_phrase('Why Visit Us') }}</label>
        <textarea  name="visitus" class="border-0 bg-secondary content" id="visitus" rows="5" placeholder="Why Visit Us" ></textarea>
    </div>

    <div class="form-group">
        <label for="#">{{ get_phrase('Our Story') }}</label>
        <textarea  name="our_story" class="border-0 bg-secondary content" id="our_story" rows="5" placeholder="Our Story" ></textarea>
    </div>

    <div class="row">
    <div class="col-lg-6">
    <label for="#">{{ get_phrase('Year of Establishment') }}</label>
    <input type="number" class="border-0 bg-secondary" name="yrofest"  placeholder="Year of Establishment" value="{{ old('yrofest') }}">
    </div>

    @php
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
@endphp

<div class="col-lg-12 mb-3">
    <label><strong>{{ get_phrase('Opening Hours') }}</strong></label>
    <div class="row g-2">
        @foreach ($days as $day)
            @php
                $dayKey = strtolower($day);
                $isClosed = old("opening_hours.$dayKey.closed") == '1';
            @endphp

            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>{{ $day }}</strong>

                        <div class="d-flex align-items-center gap-2">
                            <span class="status-label fw-bold" id="status_{{ $dayKey }}">
                                {{ $isClosed ? 'Closed' : 'Open' }}
                            </span>

                            <div class="form-check form-switch">
                                <input type="checkbox"
                                       class="form-check-input closed-toggle"
                                       id="closed_{{ $dayKey }}"
                                       name="opening_hours[{{ $dayKey }}][closed]"
                                       value="1"
                                       {{ $isClosed ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 mt-2 time-inputs" id="time_inputs_{{ $dayKey }}">
                        <input type="time"
                               class="form-control"
                               name="opening_hours[{{ $dayKey }}][open]"
                               value="{{ old("opening_hours.$dayKey.open") }}"
                               {{ $isClosed ? 'disabled' : '' }}>
                        <span>to</span>
                        <input type="time"
                               class="form-control"
                               name="opening_hours[{{ $dayKey }}][close]"
                               value="{{ old("opening_hours.$dayKey.close") }}"
                               {{ $isClosed ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


    </div>

    <div class="form-group">
    <label for="location">{{ get_phrase('Services Offered Location') }}</label>
    <div class="row">

    <!-- State Dropdown -->
    <div class="col-lg-3">
            <select name="servicecountry[]" id="servicecountry" class="selectpicker form-control @error('servicecountry') is-invalid @enderror" multiple>
                <option value="">Select Country</option>
                <!-- States will be dynamically loaded based on selected country -->
            </select>
        </div>
        <!-- State Dropdown -->
        <div class="col-lg-3">
            <select name="servicestate[]" id="servicestate" class="selectpicker form-control @error('servicestate') is-invalid @enderror" multiple>
                <option value="">Select State</option>
                <!-- States will be dynamically loaded based on selected country -->
            </select>
        </div>

        <!-- City Dropdown -->
        <div class="col-lg-3">
            <select name="servicecity[]" id="servicecity" class="selectpicker form-control @error('city') is-invalid @enderror" multiple>
                <option value="">Select City</option>
                <!-- Cities will be dynamically loaded -->
            </select>
            <a class="text-info pl-2 float-left" onclick="showcitymodel();">
                <i class="far fa-add"></i> Suggest City
            </a>
        </div>

        <!-- Area Dropdown -->
        <div class="col-lg-3">
           <select name="servicearea[]" id="servicearea" class="selectpicker form-control @error('servicearea') is-invalid @enderror"  multiple >
           
           </select>
            <a class="text-info pl-2 float-left" onclick="showareamodel();" data-bs-toggle="modal">
                <i class="far fa-add"></i> Suggest Area
            </a>
        </div>
    </div>
    
</div>




<div class="form-group">
        <label for="#">{{ get_phrase('Return/Refund Policy') }}</label>
        <textarea  name="policy" class="border-0 bg-secondary content" id="policy" rows="5" placeholder="Return/Refund Policy" value="{{ old('policy') }}"></textarea>
    </div>



    <div id="faq-container" class="form-group">
    <label for="#">{{ get_phrase('FAQ') }}</label>
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


        <div class="form-group">
    <label for="media">{{ get_phrase('Business Images/Videos') }}</label>
    <input type="file" name="media[]" id="media" class="form-control bg-secondary border-0" multiple accept="image/*,video/*">
</div>

    <div class="form-group">
        <label for="#">{{ get_phrase('Proof of Ownership (Document Upload - Business License, Utility Bill, etc.)') }}</label>
        <input type="file" name="Proof_of_Ownership" class="border-0 bg-secondary" id="Proof_of_Ownership" class="form-control">
    </div>

    <div class="form-group">
        <label for="#">{{ get_phrase('Profile photo ( Logo)') }}</label>
        <input type="file" name="image" class="border-0 bg-secondary" id="image" class="form-control">
    </div>
    <div class="form-group" style="display: flex; gap: 8px; font-size: 16px; align-items: baseline;">
    <input type="checkbox" id="terms" name="terms"  style="width: 18px; height: 18px;" required>
    <label for="terms" class="mb-0" style="cursor: pointer;">
        I agree to the <a onclick="showtermsymodel()" data-toggle="modal" data-target="#termsModal" style="color: #007bff; text-decoration: none; font-weight: 500;" >Terms and Conditions</a>
    </label>
</div>

    <button type="submit" class="w-100 btn btn-primary">{{ get_phrase('Create Page') }}</button>

    <input type="hidden" name="listing_id" value="{{ $listing->id }}">

</form>
</div>
</div>







<!-- Modal categories -->
<div class="modal fade" id="parentcategoriesModal" tabindex="-1" role="dialog" aria-labelledby="parentcategoriesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="parentcategoriesModalLabel">{{ get_phrase('Add Category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Parent Category Name Input -->
                <div class="row form-group">
                    <div class="col-md-12">
                        <label for="parent_category_name" class="text-black">Category</label>
                        <input id="parent_category_name" type="text" 
                               class="form-control @error('parent_category_name') is-invalid @enderror" 
                               name="parent_category_name" value="{{ old('parent_category_name') }}" autofocus>
                        @error('parent_category_name')
                        <span class="invalid-tooltip">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row form-group">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success py-2 px-4 text-white" onclick="submitparentcategory();">
                            Submit
                        </button>
                    </div>
                </div>
            </div> <!-- End of Modal Body -->
        </div> <!-- End of Modal Content -->
    </div> <!-- End of Modal Dialog -->
</div> <!-- End of Modal -->



<!-- Modal categories -->
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
                <!-- Category Name Input -->
                <div class="row form-group">
                    <div class="col-md-12">
                        <label for="category_name" class="text-black">Category</label>
                        <input id="category_name" type="text" 
                               class="form-control @error('category_name') is-invalid @enderror" 
                               name="category_name" value="{{ old('category_name') }}" autofocus>
                        @error('category_name')
                        <span class="invalid-tooltip">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <!-- Parent Category Dropdown -->
                <div class="form-group">
                    <label class="text-black" for="category_parent_id">Parent</label>
                    <div class="col-md-12">
                        <select class="selectpicker form-control @error('category_parent_id') is-invalid @enderror" 
                                name="category_parent_id" id="category_parent_id">
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

                <!-- Submit Button -->
                <div class="row form-group">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success py-2 px-4 text-white" onclick="submitcategory();">
                            Submit
                        </button>
                    </div>
                </div>
            </div> <!-- End of Modal Body -->
        </div> <!-- End of Modal Content -->
    </div> <!-- End of Modal Dialog -->
</div> <!-- End of Modal -->


    <div class="modal fade" id="cityModal" tabindex="-1" role="dialog" aria-labelledby="cityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="cityModalLabel">{{ get_phrase('Add City') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-12">
                        <label for="city_name" class="text-black">City Name</label>
                        <input id="city_name" type="text" 
                               class="form-control @error('city_name') is-invalid @enderror" 
                               name="city_name" value="{{ old('city_name') }}" autofocus>
                        @error('city_name')
                        <span class="invalid-tooltip">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row form-group">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success py-2 px-4 text-white" onclick="submitcity();">
                            Submit
                        </button>
                    </div>
                </div>
            </div> <!-- End of Modal Body -->
        </div> <!-- End of Modal Content -->
    </div> <!-- End of Modal Dialog -->
</div> <!-- End of Modal -->


    <div class="modal fade" id="areaModal" tabindex="-1" aria-labelledby="areaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="areaModalLabel">{{ get_phrase('Add Area') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-12">
                        <label for="area_name" class="text-black">Area Name</label>
                        <input id="area_name" type="text" 
                               class="form-control @error('area_name') is-invalid @enderror" 
                               name="area_name" value="{{ old('area_name') }}" autofocus>
                        @error('area_name')
                        <span class="invalid-tooltip">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row form-group">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success py-2 px-4 text-white" onclick="submitarea();">
                            Create
                        </button>
                    </div>
                </div>
            </div> <!-- End of Modal Body -->
        </div> <!-- End of Modal Content -->
    </div> <!-- End of Modal Dialog -->
</div> <!-- End of Modal -->


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


    <div class="modal fade" id="termsAndConditionsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true" >

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe src="{{ route('term.view') }}" width="100%" height="400px" style="border:none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    let saveTimeout;
    let listingId = null; // initially null

    $('#listing-form').on('input change', 'input, textarea, select', function () {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(function () {
            
            let formData = $('#listing-form').serialize();

            // Append listing_id if available
            if (listingId) {
                formData += '&listing_id=' + encodeURIComponent(listingId);
            }

            $.ajax({
                url: "{{ route('page.save.draft') }}",
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    console.log('Draft saved');

                    // If listing_id wasn't set, store it from response
                    if (!listingId && response.listing_id) {
                        listingId = response.listing_id;
                    }
                },
                error: function (xhr) {
                    console.error('Draft save failed:', xhr.responseText);
                }
            });
        }, 2000);
    });
</script>




    
<script>
    

    
     
 $(document).ready(function() {
    

    
    $('.selectpicker').select2(); // Initialize other Select2 dropdowns normally




// Set placeholder inside the search input box
$('#city').on('select2:open', function () {
    $('.select2-search__field').attr('placeholder', 'Type here');
});

$('#area').on('select2:open', function () {
    $('.select2-search__field').attr('placeholder', 'Type here');
});

$('#state').on('select2:open', function () {
    $('.select2-search__field').attr('placeholder', 'Type here');
});
$('#country').on('select2:open', function () {
    $('.select2-search__field').attr('placeholder', 'Type here');
});



    
    function toggleTimeInputs($checkbox) {
            const dayKey = $checkbox.attr('id').replace('closed_', '');
            const $inputs = $('#time_inputs_' + dayKey).find('input');
            const $status = $('#status_' + dayKey);

            if ($checkbox.is(':checked')) {
                $inputs.prop('disabled', true);
                $status.text('Closed').removeClass('text-success').addClass('text-danger');
            } else {
                $inputs.prop('disabled', false);
                $status.text('Open').removeClass('text-danger').addClass('text-success');
            }
        }

        // Initialize state on page load
        $('.closed-toggle').each(function () {
            toggleTimeInputs($(this));
        });

        // On toggle change
        $('.closed-toggle').on('change', function () {
            toggleTimeInputs($(this));
        });
    
// Select2 for dropdowns inside the modal
$('#category_parent_id').select2({
    dropdownParent: $('#categoriesModal') // Ensures dropdown stays inside modal
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
          url: '/country-autocomplete-ajax',
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
function showtermsymodel()
{
    $('#termsAndConditionsModal' ).modal('show');
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
 
 
    // $('.selectpicker').select2({
    //     dropdownParent: $('#common-modal')
    // });
</script>
