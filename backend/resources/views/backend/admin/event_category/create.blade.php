<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>
<style>
  
        .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 13px!important;
}
.btn-logo{
    background-color: #ff4939;
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
              <h4>{{ get_phrase('All Event Categories') }}</h4>
              
            </div>
            <div class="export-btn-area">
              <a href="{{ route('admin.view.event.category') }}" class="export_btn" data-bs-toggle="tooltip" data-bs-placement="top"
              data-bs-custom-class="custom-tooltip"
              data-bs-title="All Page Category">{{ get_phrase('View') }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Start Admin area -->
    <div class="row">
      <div class="col-12">
        <div class="eSection-wrap-2">
            <div class="row">
                <div class="col-md-6 col-md-6 col-sm-6">
                    <div class="eForm-layouts">
                      <form method="POST" action="{{ route('admin.save.event.category') }}" enctype="multipart/form-data">
                          @csrf
                          <div class="mb-3">
                            <label for="pagecategory" class="form-label eForm-label">{{ get_phrase('Page Category') }}</label>
                            <input type="text" class="form-control eForm-control" id="pagecategory" name="pagecategory" placeholder="Page Category">
                          </div>
                          <div class="mb-3">
                      <label for="page_category" class="form-label eForm-label">{{ get_phrase('Select a category') }}</label>
                      <select name="category" id="category" class="form-control eForm-control select2 @error('category') is-invalid @enderror"   required>
                      <option value="0"> Select Category </option>
                      @foreach (\App\Models\Eventcategory::where('category_parent_id',null)->get() as $category )
                        <option value="{{ $category->id }}"> {{ $category->category_name }} </option>
                        @endforeach
                    </select>
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
                          
                          <button type="submit" class="btn btn-logo text-white">{{ get_phrase('Submit') }}</button>
                      </form>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
    <!-- Start Footer -->
    @include('backend.footer')
    <!-- End Footer -->
  </div>
  <script>
     
 $(document).ready(function() {
    $('.selectpicker').select2();
 });
 </script>



