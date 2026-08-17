<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Blog Categories</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    </noscript>

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" defer></script>
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
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
                    <div class="d-flex flex-column">
                        <h4>{{ get_phrase('All Blog Categories') }}</h4>
                    </div>
                    <div class="export-btn-area">
                        <a href="{{ route('admin.create.blog.category') }}" class="export_btn" data-bs-toggle="tooltip" 
                           data-bs-placement="top" data-bs-custom-class="custom-tooltip" 
                           data-bs-title="Create Blog Category">
                            {{ get_phrase('Create') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Admin area -->
    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap-2">
                <!-- Table Area -->
                <div class="table-responsive">
                    <table class="table eTable" id="categoryTable">
                        <thead>
                            <tr>
                                <th scope="col">{{ get_phrase('Sl No') }}</th>
                                <th scope="col">{{ get_phrase('Category Name') }}</th>
                                <th scope="col">{{ get_phrase('Parent') }}</th>
                                 <th scope="col">{{ get_phrase('Total Blogs') }}</th>
                                <th scope="col" class="text-center">{{ get_phrase('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_category as $key => $category)
                                <tr>
                                    <th scope="row">
                                        <p class="row-number">{{ ++$key }}</p>
                                    </th>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                            <p><span>{{ $category->category_name }}</span></p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                            <p><span>{{ $category->parent ?? 'None' }}</span></p>
                                        </div>
                                    </td>
                                  <td>{{ $category->blogs_count ?? 0 }}</td>
                                    <td class="text-center">
                                        <div class="adminTable-action me-auto">
                                            <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2" 
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ get_phrase('Actions') }}
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.edit.blog.category', $category->id) }}">
                                                        {{ get_phrase('Edit') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" onclick="return confirm('{{ get_phrase('Are You Sure Want To Delete?') }}')" 
                                                       href="{{ route('admin.delete.blog.category', $category->id) }}">
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
                   <div class="pagination-area mt-3">
  <nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center justify-content-sm-end flex-wrap mb-0">
      <!-- Pagination links are auto-rendered by DataTables -->
    </ul>
  </nav>
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

<!-- Initialize DataTable -->
<script>
$(document).ready(function () {
    $('#categoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.blog.categories.ajax") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            }
        },
        columns: [
            { data: 'sl_no', name: 'sl_no' },
            { data: 'category_name', name: 'category_name' },
            { data: 'parent', name: 'parent' },
            { data: 'blogs_count', name: 'blogs_count' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
        ]
    });
});
</script>


</body>
</html>
