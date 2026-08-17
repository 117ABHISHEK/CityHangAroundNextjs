<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ get_phrase('All Products') }}</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    </noscript>

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" defer></script>
    <style>
    @media (max-width: 527px) {
        .pagination-area ul.pagination {
            font-size: 12px;
        }

        .pagination-area .page-link {
            padding: 0.4rem 0.6rem;
        }

        .pagination-area {
            margin-bottom: 1rem;
        }
    }
    .btn-logo{
        background-color: #ff4939;
    }
    
.table-responsive {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}
.eTable {
  min-width: 760px; 
}
.row-number {
  color:black;
}
.eTable th, .eTable td {
 border: none !important;
}
.eTable thead tr {
  border-bottom: 2px solid black !important;
}
.eTable thead th {
  font-weight: 600;
  padding: 0.75rem 0.75rem;
}
</style>

</head>
<body>

<div class="main_content">
    <!-- Header & Breadcrumb -->
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h4 class="mb-0">{{ get_phrase('All Products') }}</h4>
                    </div>
                    <div class="export-btn-area">
                        <a href="{{ route('admin.product.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> {{ get_phrase('Create') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" id="productFilterForm">
        <div class="row gy-3">
            <div class="col-md-3 col-sm-6">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" id="start_date" value="{{ request('start_date') }}">
            </div>

            <div class="col-md-3 col-sm-6">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" id="end_date" value="{{ request('end_date') }}">
            </div>

            <div class="col-md-3 col-sm-6">
                <label class="form-label">Category</label>
                <select name="category" class="form-select select2">
                    <option value="">{{ get_phrase('All Categories') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->product_category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 col-sm-6">
                <label class="form-label">City</label>
                <select name="city" id="city" class="form-select select2">
                    <option value="">{{ get_phrase('All Cities') }}</option>
                    @foreach($cities as $c)
                        <option value="{{ $c->id }}" {{ request('city') == $c->id ? 'selected' : '' }}>
                            {{ $c->city_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 col-sm-6">
                <label class="form-label">Area</label>
                <select name="area" id="area" class="form-select select2">
                    <option value="">{{ get_phrase('All Areas') }}</option>
                    <!-- Dynamic Area Options -->
                </select>
            </div>

            <div class="col-md-3 col-sm-6 d-grid align-self-end">
                <button type="submit" class="btn btn-logo mt-1 text-white">
                    {{ get_phrase('Search') }}
                </button>
            </div>
        </div>
    </form>
</div>


    <!-- Table Area -->
    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap-2">
                <div class="table-responsive">
                    <table class=" eTable" id="productTable">
                        <thead>
                            <tr>
                                <th scope="col">{{ get_phrase('Sl No') }}</th>
                                <th scope="col">{{ get_phrase('Product') }}</th>
                                <th scope="col">{{ get_phrase('Page Name') }}</th>
                                <th scope="col">{{ get_phrase('Product Owner') }}</th>
                                <th scope="col">{{ get_phrase('Created At') }}</th>

                                <th scope="col" class="text-center">{{ get_phrase('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $product)
                                <tr>
                                    <th scope="row">
                                        <p class="row-number">{{ $loop->iteration }}</p>
                                    </th>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                          @php
    $page = $product->page;
    $pageCity = $page?->city?->city_slug;
    $pageArea = $page?->area?->area_slug;
    $itemSlug = $page?->item_slug;
    $lastPageCategory = $page && $page->pageCategories->isNotEmpty()
        ? $page->pageCategories->last()
        : null;
    $categorySlug = $lastPageCategory?->category_slug;
    $productCategorySlug = optional($product->productCategories->last())->product_category_slug;
    $productSlug = $product->product_slug;

    $singleProductRouteReady = $pageCity && $pageArea && $categorySlug && $itemSlug && $productCategorySlug && $productSlug;
    $singlePageRouteReady = $pageCity && $pageArea && $categorySlug && $itemSlug;

    $pageIntegrityIssues = [];
    if (!$page) {
        $pageIntegrityIssues[] = 'Missing page';
    } else {
        if (!$pageCity) {
            $pageIntegrityIssues[] = 'Missing city slug';
        }
        if (!$pageArea) {
            $pageIntegrityIssues[] = 'Missing area slug';
        }
        if (!$categorySlug) {
            $pageIntegrityIssues[] = 'Missing page category slug';
        }
        if (!$itemSlug) {
            $pageIntegrityIssues[] = 'Missing page slug';
        }
    }
@endphp

@if ($singleProductRouteReady)
    <a href="{{ route('single.product', [
        'city_slug' => $pageCity,
        'area_slug' => $pageArea,
        'category_slug' => $categorySlug,
        'item_slug' => $itemSlug,
        'product_category_slug' => $productCategorySlug,
        'product_slug' => $productSlug,
    ]) }}" class="text-dark" target="_blank">
        {{ $product->title }}
    </a>
@else
    <span class="text-muted">{{ $product->title }}</span>
@endif

                                        </div>
                                    </td>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                          @if($page && $singlePageRouteReady)
    <a href="{{ route('single.page', [
        'city_slug'    => $pageCity,
        'area_slug'    => $pageArea,
        'category_slug'=> $categorySlug,
        'item_slug'    => $itemSlug
    ]) }}" class="text-dark" target="_blank">
        {{ $page->title }}
    </a>
@elseif($page)
    <span class="text-muted">{{ $page->title }}</span>
    <br><small class="text-danger">{{ implode(', ', $pageIntegrityIssues) }}</small>
@else
    <span class="text-muted">-</span>
    <br><small class="text-danger">{{ implode(', ', $pageIntegrityIssues) }}</small>
@endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                            <a href="{{ route('user.profile.view', $product->user_id) }}" class="text-dark" target="_blank">
                                                {{ $product->user->name ?? '-' }}
                                            </a>
                                            <br><small>{{ $product->user->email ?? '-' }}</small>
                                        </div>
                                    </td>
                                    <td>
    {{ $product->created_at->format('d M Y, h:i A') }}
</td>

                                    <td class="text-center">
                                        <div class="adminTable-action me-auto">
                                            <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ get_phrase('Actions') }}
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.product.edit', $product->id) }}">
                                                        {{ get_phrase('Edit') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" onclick="return confirm('{{ get_phrase('Are You Sure Want To Delete?') }}')"
                                                       href="{{ route('admin.product', ['delete' => 'yes', 'id' => $product->id]) }}">
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
                   <div class="pagination-area mt-4 mb-4 d-flex justify-content-center">
    <div class="d-flex justify-content-center justify-content-sm-between flex-wrap">
        <nav aria-label="Page navigation example">
            <ul class="pagination pagination-sm mb-0">
                {{ $products->links() }}
            </ul>
        </nav>
    </div>
</div>

                    <!-- End Pagination -->
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('backend.footer')
</div>

<!-- DataTable Init -->
<script>
    $(document).ready(function () {
        $('#productTable').DataTable({
            paging: false,
            searching: true,
            ordering: true,
            lengthMenu: [10, 25, 50, 100],
            language: {
                search: "{{ get_phrase('Search') }}:",
                lengthMenu: "{{ get_phrase('Show _MENU_ entries') }}",
                info: "{{ get_phrase('Showing _START_ to _END_ of _TOTAL_ entries') }}",
                infoFiltered: "{{ get_phrase('(filtered from _MAX_ total entries)') }}",
                paginate: {
                    first: "{{ get_phrase('First') }}",
                    last: "{{ get_phrase('Last') }}",
                    next: "{{ get_phrase('Next') }}",
                    previous: "{{ get_phrase('Previous') }}"
                }
            }
        });


       
    });
    const cityID = $('#city').val();
    const selectedArea = "{{ request('area') }}";

    function loadAreas(cityID, selectedArea = null) {
        if (cityID > 0) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/ajax/product/areas/' + cityID,
                method: 'GET',
                success: function (result) {
                    let areas = typeof result === 'string' ? JSON.parse(result) : result;
                    $('#area').html("<option value=''>{{ get_phrase('Select Area') }}</option>");
                    $.each(areas, function (key, value) {
                        var selected = selectedArea == value.id ? 'selected' : '';
                        $('#area').append('<option value="' + value.id + '" ' + selected + '>' + value.area_name + '</option>');
                    });
                    $('#area').trigger('change');
                }
            });
        } else {
            $('#area').html("<option value=''>{{ get_phrase('Select Area') }}</option>");
        }
    }

    // On page load (if city is already selected)
    if (cityID) {
        loadAreas(cityID, selectedArea);
    }

    // On city change
    $('#city').on('change', function () {
        loadAreas($(this).val());
    });
</script>

</body>
</html>
