<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Pages</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    </noscript>

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" defer></script>

    <style>
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  background-color: #ccc;
  border-radius: 34px;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  transition: 0.4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  border-radius: 50%;
  transition: 0.4s;
}

input:checked + .slider {
  background-color: #4CAF50;
}

input:checked + .slider:before {
  transform: translateX(26px);
}
</style>


</head>
<body>

<div class="main_content">
    <!-- Main section header and breadcrumb -->
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
                    <div class="d-flex flex-column">
                        <h4>{{ get_phrase('All Pages') }}</h4>
                    </div>

                    <div class="export-btn-area" hidden>
                        <a href="{{ route('admin.page.queue') }}" class="export_btn">
                            <i class="fas fa-plus me-2"></i> {{ get_phrase('Run Queue') }}
                        </a>
                    </div>

                    <div class="export-btn-area">
                        <a href="{{ route('admin.page.sync') }}" class="export_btn">
                            <i class="fas fa-plus me-2"></i> {{ get_phrase('Sync') }}
                        </a>
                    </div>

                    <div class="export-btn-area">
                        <a href="{{ route('admin.page.create') }}" class="export_btn">
                            <i class="fas fa-plus me-2"></i> {{ get_phrase('Create') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.page') }}">
    <div class="filters">
        <!-- First Row: City, Area, and Category Filters -->
        <div class="row mb-3">
            <div class="col-md-4">
                <!-- City Filter -->
                <label for="city_id" class="form-label">City:</label>
                <select name="city_id" id="city_id" class="form-select eForm-control select2">
                    <option value="">Select City</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                            {{ $city->city_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <!-- Area Filter -->
                <label for="area_id" class="form-label">Area:</label>
                <select name="area_id" id="area_id" class="form-select eForm-control select2">
                    <option value="">Select Area</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                            {{ $area->area_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <!-- Category Filter (Multiple) -->
                <label for="category_ids" class="form-label">Categories:</label>
                <select name="category_ids" id="category_ids" class="form-select eForm-control select2" >
                <option value="">Select</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_ids') == $category->id ? 'selected' : ''  }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Second Row: Date Range Filters -->
        <div class="row mb-3">
            <div class="col-md-6">
                <!-- Start Date Filter -->
                <label for="start_date" class="form-label">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="form-control eForm-control" value="{{ request('start_date') }}">
            </div>

            <div class="col-md-6">
                <!-- End Date Filter -->
                <label for="end_date" class="form-label">End Date:</label>
                <input type="date" name="end_date" id="end_date" class="form-control eForm-control" value="{{ request('end_date') }}">
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </div>
        </div>
    </div>
</form>


    <!-- Start Admin area -->
    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap-2">
                <!-- Filter area -->
                <div class="table-responsive">
                    <!-- Global Search -->
                    <div class="mb-3">
                        <input type="text" id="globalSearchInput" class="form-control" placeholder="Search across all pages...">
                    </div>

                    <table class="table eTable" id="searchableTable">
                        <thead>
                            <tr>
                                <th scope="col">{{ get_phrase('Sl No') }}</th>
                                <th scope="col">{{ get_phrase('Page') }}</th>
                                <th scope="col">{{ get_phrase('Verified Seller') }}</th>
                                <th scope="col">{{ get_phrase('Category') }}</th>
                                <th scope="col">{{ get_phrase('City') }}</th>
                                <th scope="col">{{ get_phrase('Area') }}</th>
                                <th scope="col">{{ get_phrase('Page owner') }}</th>
                                <th scope="col">{{ get_phrase('Created At') }}</th>
                                <th scope="col" class="text-center">{{ get_phrase('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="pageTableBody">
                            @foreach ($pages as $key => $page)
                            @php
                                    $itemCategories = $page->pageCategories;
                                    $lastCategory = $itemCategories->last();
                                @endphp
                                <tr>
                                    <th scope="row">
                                        <p class="row-number">{{ ++$key }}</p>
                                    </th>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                              @if($lastCategory)
                                        <a href="{{ route('single.page', [
                                                        'city_slug'     => optional($page->city)->city_slug ?: 'city',
    'area_slug'     => optional($page->area)->area_slug ?: 'area',
    'category_slug' => optional($lastCategory)->category_slug ?: 'category',
    'item_slug'     => $page->item_slug ?: $page->id
                                                    ]) }}" class="text-dark" target="_blank">
                                                    {{ $page->title }}
                                                    </a>
                                                    @endif
                                        </div>
                                    </td>
                                <td class="text-center">
    <label class="switch">
        <input type="checkbox" class="toggle-verified" 
               data-id="{{ $page->id }}" 
               {{ $page->is_verified_seller === 'Y' ? 'checked' : '' }}>
        <span class="slider round"></span>
    </label>
</td>


                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                        {{ $page->pageCategories->pluck('category_name')->join(', ') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                        {{ optional($page->city)->city_name ?? '—' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                       
                                                    {{ optional($page->area)->area_name ?? '—' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                            <a href="{{route('user.profile.view', $page->user_id)}}" class="text-dark" target="_blank">
                                                {{ $page->user->name ?? "" }}
                                            </a>
                                            <br><small>{{ $page->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                        {{ $page->created_at->format('d-m-Y H:i') }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="adminTable-action me-auto">
                                            <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2" 
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ get_phrase('Actions') }}
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                                                <li>
                                                @if($lastCategory)
                                                <a href="{{ route('single.page', [
                                                        'city_slug'     => optional($page->city)->city_slug ?: 'city',
        'area_slug'     => optional($page->area)->area_slug ?: 'area',
        'category_slug' => optional($lastCategory)->category_slug ?: 'category',
        'item_slug'     => $page->item_slug ?: $page->id
                                                    ]) }}" class="text-dark" target="_blank">
                                                    {{ get_phrase('View on frontend') }}
                                                    </a>
                                                    @endif
                                                </li>
                                                <li><a class="dropdown-item" href="{{route('admin.page.edit', $page->id)}}">{{ get_phrase('Edit') }}</a></li>
                                                <li>
                                                    <a class="dropdown-item" 
                                                       onclick="return confirm('{{ get_phrase('Are You Sure Want To Delete?') }}')" 
                                                       href="{{ route('admin.page', ['delete' => 'yes', 'id' => $page->id]) }}">
                                                        {{ get_phrase('Delete') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination-area">
                        <div aria-label="Page navigation example">
                            <ul class="pagination">
                                {{ $pages->links() }}
                            </ul>
                        </div>
                    </div>
                    <!-- Pagination end -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Admin area -->

    <!-- Start Footer -->
    @include('backend.footer')
    <!-- End Footer -->
</div>
<script>
    $('#globalSearchInput').on('keyup', function () {
        let search = $(this).val();
        const deleteConfirmMsg = `{{ get_phrase('Are You Sure Want To Delete?') }}`;
        const deleteRouteBase = `{{ route('admin.page') }}`;

        // Avoid firing on very short inputs except empty
        if (search.length < 2 && search.length !== 0) return;

        $.ajax({
            url: '{{ route("admin.pages.search") }}',
            method: 'GET',
            data: { search: search },
            success: function (data) {
                let tbody = '';

                if (data.length === 0) {
                    tbody = '<tr><td colspan="8" class="text-center">No results found.</td></tr>';
                } else {
                    data.forEach((row, index) => {
                        // Prepare comma separated category names
                        let categoryNames = '';
                        if (row.categories && row.categories.length) {
                            categoryNames = row.categories.map(cat => cat.category_name).join(', ');
                        }

                        // Get last category slug for URL
                        let lastCategorySlug = '';
                        if (row.categories && row.categories.length) {
                            lastCategorySlug = row.categories[row.categories.length - 1].category_slug || '';
                        }

                        // Format created_at date as dd-mm-yyyy HH:mm
                        let createdAtFormatted = '';
                        if (row.created_at) {
                            const dateObj = new Date(row.created_at);
                            const dd = String(dateObj.getDate()).padStart(2, '0');
                            const mm = String(dateObj.getMonth() + 1).padStart(2, '0'); // zero-based month
                            const yyyy = dateObj.getFullYear();
                            const hh = String(dateObj.getHours()).padStart(2, '0');
                            const min = String(dateObj.getMinutes()).padStart(2, '0');
                            createdAtFormatted = `${dd}-${mm}-${yyyy} ${hh}:${min}`;
                        }

                        tbody += `
                            <tr>
                                <th scope="row">${index + 1}</th>
                                <td>
                                    <a href="/${row.city?.city_slug ?? ''}/${row.area?.area_slug ?? ''}/${lastCategorySlug}/${row.item_slug}" target="_blank">
                                        ${row.title || ''}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <label class="switch">
                                        <input type="checkbox" class="toggle-verified" 
                                            data-id="${row.id}" 
                                            ${row.is_verified_seller === 'Y' ? 'checked' : ''}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>${categoryNames}</td>
                                <td>${row.city?.city_name ?? ''}</td>
                                <td>${row.area?.area_name ?? ''}</td>
                                <td>
                                    <a href="/user/view-profile/${row.user_id}" target="_blank">
                                        ${row.get_user?.name ?? ''}
                                    </a><br>
                                    <small>${row.get_user?.email ?? ''}</small>
                                </td>
                                <td>${createdAtFormatted}</td>
                                <td class="text-center">
                                    <div class="adminTable-action me-auto">
                                        <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2" 
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                                            <li>
                                                <a href="/${row.city?.city_slug ?? ''}/${row.area?.area_slug ?? ''}/${lastCategorySlug}/${row.item_slug}" class="text-dark" target="_blank">
                                                    View on frontend
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="/admin/page-edit/${row.id}">Edit</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="${deleteRouteBase}?delete=yes&id=${row.id}" onclick="return confirm(deleteConfirmMsg)">
                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                }

                $('#pageTableBody').html(tbody);

                // Reinitialize Bootstrap dropdowns after new content
                var dropdownTriggerList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
                dropdownTriggerList.map(function (dropdownTriggerEl) {
                    return new bootstrap.Dropdown(dropdownTriggerEl);
                });

                // Toggle pagination visibility
                if (search.length > 0) {
                    $('.pagination-area').hide();
                } else {
                    $('.pagination-area').show();
                }
            },
            error: function(xhr) {
                console.error('Search error:', xhr);
            }
        });
    });
</script>



<!-- Initialize DataTable -->
<script>
    $(document).ready(function () {
        // Initialize DataTable
       

        // Handle City change event to dynamically load Areas
        var preSelectedCityId = "{{ request('city_id') }}";
    if (preSelectedCityId) {
        $('#city_id').trigger('change');
    }

       
    });
    $('#city_id').on('change', function () {
    var cityId = this.value;
    $('#area_id').html("<option value=''>Select Area</option>"); // Reset Area dropdown

    if (cityId) {
        var ajax_url = '/ajax/areas/' + cityId;

        $.ajax({
            url: ajax_url,
            method: 'GET',
            success: function (response) {
                var areas = typeof response === 'string' ? JSON.parse(response) : response;
                $('#area_id').html("<option value=''>Select Area</option>"); // Reset again after fetching

                $.each(areas, function (index, area) {
                    $('#area_id').append(
                        '<option value="' + area.id + '">' + area.area_name + '</option>'
                    );
                });

                // After loading areas, if an old area is selected, select it
                var selectedAreaId = "{{ request('area_id') }}"; 
                if (selectedAreaId) {
                    $('#area_id').val(selectedAreaId);
                }
                $('#area_id').trigger('change');
            },
            error: function (xhr) {
                console.error('Error fetching areas:', xhr.responseText);
            }
        });
    }
});
</script>
<script>
$(document).on('change', '.toggle-verified', function () {
    let checkbox = $(this);
    let pageId = checkbox.data('id');
    let newStatus = checkbox.is(':checked') ? 'Y' : 'N';

    $.ajax({
        url: '/admin/page/toggle-verified',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            id: pageId,
            is_verified_seller: newStatus
        },
        success: function (response) {
            if (response.success) {
                // Optional: show toast or change row color
            } else {
                alert('Failed to update status.');
                // Revert checkbox
                checkbox.prop('checked', !checkbox.is(':checked'));
            }
        },
        error: function () {
            alert('Server error.');
            checkbox.prop('checked', !checkbox.is(':checked'));
        }
    });
});
</script>

</body>
</html>
