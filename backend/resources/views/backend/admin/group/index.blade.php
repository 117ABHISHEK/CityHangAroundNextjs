<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Groups</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    </noscript>

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" defer></script>

</head>

<body>

<div class="main_content">
    <!-- Main section header and breadcrumb -->
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
                    <div class="d-flex flex-column">
                        <h4>{{ get_phrase('All Groups') }}</h4>
                    </div>
                    <div class="export-btn-area">
                        <a href="{{ route('admin.group.create') }}" class="export_btn" 
                           data-bs-toggle="tooltip" data-bs-placement="top" 
                           data-bs-custom-class="custom-tooltip" 
                           data-bs-title="Create Group">
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
                <div class="table-responsive">
                    <table class="table eTable" id="groupsTable">
                        <thead>
                            <tr>
                                <th scope="col">{{ get_phrase('Sl No') }}</th>
                                <th scope="col">{{ get_phrase('Group Name') }}</th>
                                <th scope="col">{{ get_phrase('DATE') }}</th>
                                <th scope="col" class="text-center">{{ get_phrase('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groups as $key => $brand)
                                <tr>
                                    <th scope="row">
                                        <p class="row-number">{{ ++$key }}</p>
                                    </th>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                            <p><span>{{ $brand->title }}</span></p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                            <p>{{ date("d-m-Y", strtotime($brand->created_at)) }}</p>
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
                                                    <a class="dropdown-item" href="{{ route('admin.group.edit', $brand->id) }}">
                                                        {{ get_phrase('Edit') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" onclick="return confirm('{{ get_phrase('Are You Sure Want To Delete?') }}')" 
                                                       href="{{ route('admin.group', ['delete' => 'yes', 'id' => $brand->id]) }}">
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
                    <div class="pagination-area d-flex justify-content-center">
                        <div aria-label="Page navigation example">
                            <ul class="pagination">
                                {{ $groups->links() }}
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

<!-- Initialize DataTable -->
<script>
    $(document).ready(function () {
        $('#groupsTable').DataTable({
            "paging": true,     // Enables pagination
            "searching": true,  // Enables search box
            "ordering": true,   // Enables column sorting
            "lengthMenu": [10, 25, 50, 100], // Controls how many entries are shown per page
            "language": {
                "search": "{{ get_phrase('Search') }}:",
                "lengthMenu": "{{ get_phrase('Show _MENU_ entries') }}",
                "info": "{{ get_phrase('Showing _START_ to _END_ of _TOTAL_ entries') }}",
                "infoFiltered": "{{ get_phrase('(filtered from _MAX_ total entries)') }}",
                "paginate": {
                    "first": "{{ get_phrase('First') }}",
                    "last": "{{ get_phrase('Last') }}",
                    "next": "{{ get_phrase('Next') }}",
                    "previous": "{{ get_phrase('Previous') }}"
                }
            }
        });
    });
</script>

</body>
</html>
