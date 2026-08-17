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
              <h4>{{ get_phrase('All Product Category') }}</h4>
              
            </div>
            <div class="export-btn-area">
              <a href="{{ route('admin.view.product.category') }}" class="export_btn">{{ get_phrase('View') }}</a>
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
                      <form method="POST" action="{{ route('admin.update.product.category',$productcategory->id) }}" enctype="multipart/form-data">
                          @csrf
                          <div class="mb-3">
                            <label for="page_category" class="form-label eForm-label">{{ get_phrase('Select a category type') }}</label>
                            <select name="category_type" id="category_type" class="form-control eForm-control select2 @error('category_type') is-invalid @enderror"   required>
                            <option value="0"  selected>{{ get_phrase('select') }}</option>
                                <option value="Service"  {{ ($productcategory->category_type=="Service") ? 'selected' : '' }}>{{get_phrase('Service')}}</option>
                                <option value="Product"  {{ ($productcategory->category_type=="Product") ? 'selected' : '' }}>{{get_phrase('Product')}}</option>
                          </select>
                        </div>
                          <div class="mb-3">
                            <label for="productcategory" class="form-label eForm-label">{{ get_phrase('Product Category') }}</label>
                            <input type="text" class="form-control eForm-control" id="productcategory" value="{{ $productcategory->product_category_name }}" name="productcategory" placeholder="Product Category">
                          </div>
                          <div class="mb-3">
                          <label for="page_category" class="form-label eForm-label">{{ get_phrase('Select a parent category') }}</label>
                          <select name="category" id="category" class="form-control eForm-control select2 @error('category') is-invalid @enderror"   required>
                          <option value="0"> Select Category </option>
                      @foreach (\App\Models\Category::where('category_parent_id',0)->get() as $category )
                        <option value="{{ $category->id }}" {{ ($category->id==$productcategory->category_parent_id) ? 'selected' : '' }}> {{ $category->product_category_name }} </option>
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



