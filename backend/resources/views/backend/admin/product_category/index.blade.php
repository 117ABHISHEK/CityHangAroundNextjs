<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Product Categories</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    </noscript>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
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
    <!-- Main section header and breadcrumb -->
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gr-15">
                <div class="d-flex flex-column">
                    <h4>{{ get_phrase('All Product Categories') }}</h4>
                </div>
                <div class="export-btn-area">
                    <a href="{{ route('admin.create.product.category') }}" class="export_btn" data-bs-toggle="tooltip" 
                       data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                       data-bs-title="Create Product Category">
                        {{ get_phrase('Create') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

 <input type="text" name="search" id="searchInput" class="form-control mb-3" placeholder="Search category...">

    <!-- Table Area -->
    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap-2">
                <div class="table-responsive">
                    <table class="table eTable">
    <thead>
        <tr>
            <th>Sl No</th>
            <th>Type</th>
            <th>Category Name</th>
            <th>Product</th>
            <th>Parent</th>
            <th>No. of Views</th>
            <th>No. of Inquiries</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody id="categoryTableBody">
        @php $i = ($all_category->currentPage() - 1) * $all_category->perPage(); @endphp
        @foreach ($all_category as $category)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $category->category_type }}</td>
                <td>
                    <a href="{{ route('admin.product') }}?category={{ $category->id }}">
                        {{ $category->product_category_name }}
                    </a>
                </td>

                <td>{{ $category->product_count ?? 0 }}</td>
                <td>{{ $category->parent ?? '-' }}</td>
                <td>{{ $category->view_count ?? 0 }}</td>
                <td>{{ $category->inquiry_count ?? 0 }}</td>
                <td class="text-center">
                    <div class="adminTable-action me-auto">
                        <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2"
                                data-bs-toggle="dropdown">
                            Actions
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-1"
                                   href="{{ route('admin.edit.product.category', $category->id) }}">
                                    <i class="fas fa-edit d-none d-sm-inline"></i>
                                    <span>Edit</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-1 text-danger"
                                   onclick="return confirm('Are You Sure Want To Delete?')"
                                   href="{{ route('admin.delete.product.category', $category->id) }}">
                                    <i class="fas fa-trash d-none d-sm-inline"></i>
                                    <span>Delete</span>
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
                    <div class="pagination-area mt-3 d-flex justify-content-center" id="paginationLinks">
                        {{ $all_category->links() }}
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
    function fetch_data(page = 1, search = '') {
        $.ajax({
            url: "{{ route('admin.view.product.category') }}",
            type: "GET",
            data: { page: page, search: search },
            success: function (res) {
                $('#categoryTableBody').html(res.table);
                $('#paginationLinks').html(res.pagination);
            },
            error: function () {
                alert('Something went wrong.');
            }
        });
    }

    $(document).ready(function () {
        $('#searchInput').on('keyup', function () {
            fetch_data(1, $(this).val());
        });

        // Handle pagination clicks
        $(document).on('click', '#paginationLinks a', function (e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            let search = $('#searchInput').val();
            fetch_data(page, search);
        });
    });
</script>
