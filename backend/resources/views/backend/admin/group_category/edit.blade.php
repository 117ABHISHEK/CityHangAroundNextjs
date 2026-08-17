
<div class="main_content">
    <!-- Page Header -->
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex flex-column">
                        <h4>{{ get_phrase('Edit Group Category') }}</h4>
                    </div>
                    <div class="export-btn-area">
                        <a href="{{ route('admin.group.categories') }}" class="export_btn">
                            {{ get_phrase('View All Categories') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-md-6">
            <div class="eSection-wrap-2">
                <form method="POST" action="{{ route('admin.update.group.category', $groupCategory->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Category Name -->
                    <div class="mb-3">
                        <label for="category_name" class="form-label eForm-label">{{ get_phrase('Category Name') }}</label>
                        <input type="text" class="form-control eForm-control" id="category_name" 
                               name="category_name" value="{{ $groupCategory->category_name }}" required>
                    </div>

                    <!-- Parent Category Selection -->
                    <div class="mb-3">
                        <label for="parent_category" class="form-label eForm-label">{{ get_phrase('Select Parent Category (Optional)') }}</label>
                        <select name="parent_id" id="parent_category" class="form-control select2">
                            <option value="0">No Parent</option>
                            @foreach (\App\Models\GroupCategory::whereNull('category_parent_id')->get() as $category)
                                <option value="{{ $category->id }}" 
                                    {{ ($category->id == $groupCategory->category_parent_id) ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
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

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">{{ get_phrase('Update Category') }}</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('backend.footer')

</div>

<!-- jQuery & Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>

<!-- Initialize Select2 -->
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>


