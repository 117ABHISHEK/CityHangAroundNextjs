<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Blogs</title>

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
                        <h4>{{ get_phrase('All Blogs') }}</h4>
                    </div>
                    <div class="export-btn-area">
                        <a href="{{ route('admin.blog.create') }}" class="export_btn">
                            <i class="fas fa-plus me-2"></i> {{ get_phrase('Create') }}
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
                    <table class="table eTable" id="blogTable">
                        <thead>
                            <tr>
                                <th scope="col">{{ get_phrase('Sl No') }}</th>
                                <th scope="col">{{ get_phrase('Blog') }}</th>
                                <th scope="col">{{ get_phrase('Blog Owner') }}</th>
                                <th scope="col" class="text-center">{{ get_phrase('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($blogs as $key => $blog)
                                <?php
                                    // Fetch the last category for the blog
                                    $category = DB::table('blogcategories')
                                        ->where('id', function ($query) use ($blog) {
                                            $query->select('category_id')
                                                  ->from('blog_category')
                                                  ->where('blog_id', $blog->id)
                                                  ->orderByDesc('id')
                                                  ->limit(1);
                                        })
                                        ->first();

                                    // Set category slug and name
                                    $catslug = $category->category_slug ?? "";
                                    $cat_name = $category->category_name ?? "";

                                    // Format creation date
                                    $created_at = \Carbon\Carbon::parse($blog->created_at)->format('d M Y');
                                ?>
                                <tr>
                                    <th scope="row">
                                        <p class="row-number">{{ ++$key }}</p>
                                    </th>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                            <a href="{{ route('single.blog', ['city_slug' => $blog->city_slug, 'area_slug' => $blog->area_slug, 'category_slug' => $catslug, 'blog_slug' => $blog->blog_slug]) }}" 
                                               class="text-dark" target="_blank">
                                                {{ $blog->title }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                            <a href="{{ route('user.profile.view', $blog->userid) }}" class="text-dark" target="_blank">
                                                {{ $blog->username ?? "" }}
                                            </a>
                                            <br><small>{{ $blog->useremail }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="adminTable-action">
                                            <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2" 
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ get_phrase('Actions') }}
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('single.blog', ['city_slug' => $blog->city_slug, 'area_slug' => $blog->area_slug, 'category_slug' => $catslug, 'blog_slug' => $blog->blog_slug]) }}">
                                                        {{ get_phrase('View on frontend') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.blog.edit', $blog->id) }}">
                                                        {{ get_phrase('Edit') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" onclick="return confirm('{{ get_phrase('Are You Sure Want To Delete?') }}')" 
                                                       href="{{ route('admin.blog', ['delete' => 'yes', 'id' => $blog->id]) }}">
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
        $('#blogTable').DataTable({
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
