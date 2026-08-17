<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Group Category</title>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'" />
    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    </noscript>

    <!-- Custom Styles -->
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 13px !important;
        }
    </style>
</head>
<body>

<div class="main_content">
    <!-- Page Title -->
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex flex-column">
                        <h4>{{ get_phrase('Create Group Category') }}</h4>
                    </div>
                    <div class="export-btn-area">
                        <a href="{{ route('admin.group.categories') }}" class="export_btn">
                            {{ get_phrase('Back to Categories') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Category Form -->
    <div class="row">
        <div class="col-md-6">
            <div class="eForm-layouts">
                <form method="POST" action="{{ route('admin.store.group.category') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="category_name" class="form-label eForm-label">{{ get_phrase('Category Name') }}</label>
                        <input type="text" class="form-control eForm-control" id="category_name" name="category_name" placeholder="Enter category name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="parent_category" class="form-label eForm-label">{{ get_phrase('Parent Category (Optional)') }}</label>
                        <select name="parent_id" id="parent_category" class="form-control select2">
                            <option value="0">No Parent</option>
                            @foreach (\App\Models\GroupCategory::whereNull('category_parent_id')->get() as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
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

                    <button type="submit" class="btn btn-primary">{{ get_phrase('Create Category') }}</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Start Footer -->
    @include('backend.footer')
    <!-- End Footer -->
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

</body>
</html>