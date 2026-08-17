<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Page Categories</title>

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
                        <h4>{{ get_phrase('All Page Categories') }}</h4>
                    </div>
                    <div class="export-btn-area">
                        <a href="{{ route('admin.create.category') }}" class="export_btn" 
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           data-bs-custom-class="custom-tooltip"
                           data-bs-title="Create Page Category">
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
               <input type="text" id="liveSearchInput" placeholder="Type to search category..." style="margin: 20px 0; padding: 8px; width: 100%;">

<div id="liveSearchTable"></div>

            <div class="eSection-wrap-2">
                <!-- Filter area -->
                <div class="table-responsive">
                 
                    <table class="table eTable" id="categoryTable">
                        <thead>
                            <tr>
                                <th scope="col">{{ get_phrase('Sl No') }}</th>
                                <th scope="col">{{ get_phrase('Category Name') }}</th>
                                <th scope="col">{{ get_phrase('Page Count') }}</th>
                                <th scope="col">{{ get_phrase('Parent') }}</th>
                                <th scope="col">{{ get_phrase('Is Parent') }}</th>
<th scope="col">{{ get_phrase('Has Banner') }}</th>
<th scope="col">{{ get_phrase('Has Icon') }}</th>

                                <th scope="col" class="text-center">{{ get_phrase('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="categoryTableBody">
                            @foreach ($all_category as $key => $category)
                                <tr>
                                    <th scope="row">
                                        <p class="row-number">{{ ++$key }}</p>
                                    </th>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                            <p><span>
                                            <a href="{{ route('admin.page', ['category_ids' => $category->id]) }}">
                                                {{ $category->category_name }} 
                                            </a>
                                            </span></p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                            <p>{{ $category->pages_count }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dAdmin_info_name min-w-100px">
                                            <p>{{ $category->parentCategory->category_name ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td>
    <div class="dAdmin_info_name min-w-100px">
        <p>
            @if ($category->is_parent === 'Yes')
                ✅
            @else
                ❌ 
            @endif
        </p>
    </div>
</td>

<td>
    <div class="dAdmin_info_name min-w-100px">
        <p>
            @if (!empty($category->category_banner))
               ✅
            @else
                ❌ 
            @endif
        </p>
    </div>
</td>
<td>
    @if(!empty($category->category_icon))
        ✅
    @else
        ❌
    @endif
</td>

                                    <td class="text-center">
                                        <div class="adminTable-action me-auto">
                                            <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2" 
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ get_phrase('Actions') }}
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.edit.category', ['id' => $category->id, 'page' => request('page', 1)]) }}">
                                                            {{ get_phrase('Edit') }}
                                                        </a>

                                                </li>
                                                <li>
                                                    <a class="dropdown-item" 
                                                       onclick="return confirm('{{ get_phrase('Are You Sure Want To Delete?') }}')" 
                                                       href="{{ route('admin.delete.category',$category->id) }}">
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
                                                    {{ $all_category->links() }}
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
    const editRoute = "{{ route('admin.edit.category', ['id' => 'CATEGORY_ID']) }}";
    const deleteRoute = "{{ route('admin.delete.category', 'CATEGORY_ID') }}";

    document.getElementById("liveSearchInput").addEventListener("input", function () {
        const query = this.value.trim();

        if (query.length === 0) {
            location.reload();
            return;
        }

        fetch(`/admin/page/search-category?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById("categoryTableBody");
                tbody.innerHTML = "";

                if (data.length === 0) {
                    tbody.innerHTML = "<tr><td colspan='8'>No results found</td></tr>";
                    return;
                }

                data.forEach((cat, index) => {
                    const isParent = cat.is_parent === 'Yes' ? '✅' : '❌';
                    const hasBanner = cat.category_banner ? '✅' : '❌';
                    const hasIcon = cat.category_icon ? '✅' : '❌';

                    const editUrl = editRoute.replace('CATEGORY_ID', cat.id) + '?page=1';
                    const deleteUrl = deleteRoute.replace('CATEGORY_ID', cat.id);

                    const row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td><a href="/admin/page?category_ids=${cat.id}">${cat.category_name}</a></td>
                            <td>${cat.page_count}</td>
                            <td>${cat.parent ?? '-'}</td>
                            <td>${isParent}</td>
                            <td>${hasBanner}</td>
                            <td>${hasIcon}</td>
                            <td class="text-center">
                                <div class="adminTable-action me-auto">
                                    <button class="eBtn eBtn-black dropdown-toggle table-action-btn-2" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="${editUrl}">{{ get_phrase('Edit') }}</a></li>
                                        <li><a class="dropdown-item" onclick="return confirm('{{ get_phrase('Are You Sure Want To Delete?') }}')" href="${deleteUrl}">{{ get_phrase('Delete') }}</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML("beforeend", row);
                });
            });
    });
</script>

</body>
</html>
