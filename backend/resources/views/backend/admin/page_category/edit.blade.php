<style>
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
              <h4>{{ get_phrase('All Page Categories') }}</h4>
              
            </div>
            <div class="export-btn-area">
              <a href="{{ route('admin.view.category') }}" class="export_btn">{{ get_phrase('View') }}</a>
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
                    <div>
                       <form method="POST" action="{{ route('admin.update.category', ['id' => $pagecategory->id, 'page' => request('page', 1)]) }}" enctype="multipart/form-data">
                          @csrf
                          <div class="mb-3">
                            <label for="pagecategory" class="form-label eForm-label">{{ get_phrase('Page Category') }}</label>
                            <input type="text" class="form-control eForm-control" id="pagecategory" value="{{ $pagecategory->category_name }}" name="pagecategory" placeholder="Page Category">
                          </div>

                          <div class="mb-3">
                          <label for="page_category" class="form-label eForm-label">{{ get_phrase('Select a category') }}</label>
                          <select name="category" id="category" class="form-control eForm-control select2 @error('category') is-invalid @enderror"   required>
                          <option value="0"> Select Category </option>
                      @foreach (\App\Models\Pagecategory::where('category_parent_id',null)->get() as $category )
                        <option value="{{ $category->id }}" {{ ($category->id==$pagecategory->category_parent_id) ? 'selected' : '' }}> {{ $category->category_name }} </option>
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


                          <!-- Show Current Icon -->
                      @if($pagecategory->category_icon)
                          <div class="mb-3">
                              <label class="form-label">Current Icon:</label><br>
                              <img src="{{ Str::startsWith($pagecategory->category_icon, 'http') ? $pagecategory->category_icon : asset($pagecategory->category_icon) }}"
                                  alt="Icon" style="height: 40px; border-radius: 4px;">
                          </div>
                      @endif

                      <!-- Upload Icon -->
                      <div class="mb-3">
                          <label class="form-label">Upload Icon</label>
                          <input type="file" name="category_icon" class="form-control">
                      </div>

                      <!-- Show Current Banner -->
                      @if($pagecategory->category_banner)
                          <div class="mb-3">
                              <label class="form-label">Current Banner:</label><br>
                              <img src="{{ Str::startsWith($pagecategory->category_banner, 'http') ? $pagecategory->category_banner : asset($pagecategory->category_banner) }}"
                                  alt="Banner" style="max-width: 100%; border-radius: 6px;">
                          </div>
                      @endif

                      <!-- Upload Banner -->
                      <div class="mb-3">
                          <label class="form-label">Upload Banner</label>
                          <input type="file" name="category_banner" class="form-control">
                      </div>


                    <div class="form-check mt-2">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="is_parent" 
                        name="is_parent" 
                        value="Yes" 
                        {{ (isset($pagecategory) && $pagecategory->is_parent === 'Yes') ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="is_parent">
                        {{ get_phrase('Is Parent') }}
                    </label>
                </div>
<input type="hidden" name="page" value="{{ request()->get('page', 1) }}">
                          
                          
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



