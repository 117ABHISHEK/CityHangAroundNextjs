<div class="main_content">
  <!-- Section Header and Breadcrumb -->
  <div class="mainSection-title mb-3">
    <div class="row">
      <div class="col-12">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 flex-wrap">
          <div>
            <h4 class="mb-0">{{ get_phrase('Create your new Ad') }}</h4>
          </div>
          <div class="export-btn-area">
            <a href="{{ route('user.ads') }}" class="export_btn" data-bs-toggle="tooltip" data-bs-placement="top"
              data-bs-custom-class="custom-tooltip"
              data-bs-title="All Ads">
              <i class="bi bi-arrow-left me-1"></i> {{ get_phrase('Back') }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Admin Area -->
  <div class="row">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="eSection-wrap-2">
        <div class="eForm-layouts">

          <form method="POST" action="{{ route('user.ad.store') }}" enctype="multipart/form-data">
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

            <div class="mb-3">
              <label for="name" class="form-label eForm-label">{{ get_phrase('Title') }}</label>
              <input type="text" class="form-control eForm-control" id="name" name="name" placeholder="Title" required>
            </div>

            <div class="mb-3">
              <label for="ext_url" class="form-label eForm-label">{{ get_phrase('URL') }}</label>
              <input type="url" class="form-control eForm-control" id="ext_url" name="ext_url" placeholder="URL">
            </div>

            <div class="mb-3">
              <label for="image" class="form-label eForm-label">{{ get_phrase('Image') }}</label>
              <input type="file" class="form-control eForm-control" id="image" name="image" required>
            </div>

            <div class="form-group mb-3">
              <label for="description" class="form-label eForm-label">
                {{ get_phrase('Description') }}
                <small>{{ get_phrase('(50 Character Show In Front End)') }}</small>
              </label>
              <textarea name="description" id="description" class="form-control eForm-control content" placeholder="Description"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">{{ get_phrase('Submit') }}</button>
          </form>

        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  @include('backend.footer')
</div>
