<style>
  @media (max-width: 576px) {
    .mainSection-title h4 {
      font-size: 1.1rem;
    }
    .export-btn-area .export_btn {
      font-size: 0.875rem;
      padding: 0.4rem 0.8rem;
    }
  }
  .btn-logo{
      background-color: #ff4939;
  }
</style>

<div class="main_content">
  <!-- Header -->
  <div class="mainSection-title mb-3">
    <div class="row">
      <div class="col-12">
        <div class="d-flex flex-wrap justify-content-between align-items-center gr-15">
          <div class="d-flex flex-column mb-2 mb-sm-0">
            <h4 class="m-0">{{ get_phrase('Add Sponsors Post') }}</h4>
          </div>
          <div class="export-btn-area">
            <a href="{{ route('admin.view.sponsor') }}" class="export_btn btn btn-sm " data-bs-toggle="tooltip" data-bs-placement="top"
              data-bs-custom-class="custom-tooltip" data-bs-title="All Ads">
              {{ get_phrase('View') }}
            </a>
          </div>
           <!--<div class="export-btn-area">-->
           <!--   <a href="{{ route('admin.view.sponsor') }}" class="export_btn  btn btn-sm " data-bs-toggle="tooltip" data-bs-placement="top"-->
           <!--   data-bs-custom-class="custom-tooltip"-->
           <!--   data-bs-title="All Ads">{{ get_phrase('View') }}</a>-->
           <!-- </div>-->
        </div>
      </div>
    </div>
  </div>

  <!-- Form Area -->
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-sm-12">
      <div class="eSection-wrap-2">
        <div class="eForm-layouts">
          <form method="POST" action="{{ route('admin.save.sponsor') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
              <label for="name" class="form-label eForm-label">{{ get_phrase('Title') }}</label>
              <input type="text" class="form-control eForm-control" id="name" name="name" placeholder="Title">
            </div>

            <div class="mb-3">
              <label for="ext_url" class="form-label eForm-label">{{ get_phrase('URL') }}</label>
              <input type="url" class="form-control eForm-control" id="ext_url" name="ext_url" placeholder="URL">
            </div>

            <div class="mb-3">
              <label for="image" class="form-label eForm-label">{{ get_phrase('Image') }}</label>
              <input type="file" class="form-control eForm-control" id="image" name="image">
            </div>

            <div class="form-group mb-3">
              <label for="description" class="form-label eForm-label">{{ get_phrase('Description') }} 
                <small>{{ get_phrase('(50 Character Show In Front End)') }}</small></label>
              <textarea name="description" id="description" class="form-control eForm-control content" rows="3" placeholder="Description"></textarea>
            </div>

            @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="text-end">
              <button type="submit" class="btn btn-logo text-white">{{ get_phrase('Submit') }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  @include('backend.footer')
</div>
