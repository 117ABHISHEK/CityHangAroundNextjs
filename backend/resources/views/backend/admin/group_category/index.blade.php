<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Group Categories</title>

  <!-- Bootstrap & DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" media="print" onload="this.media='all'">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" media="print" onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"/>
    </noscript>

  <!-- jQuery & DataTables JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" defer></script>
  <style>
  @media (max-width: 450px) {
    .pagination {
      font-size: 0.75rem; /* smaller text */
    }

    .pagination .page-link {
      padding: 0.25rem 0.5rem; /* tighter spacing */
    }

    .pagination .page-item {
      margin: 0 2px;
    }
  }
</style>
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

<body class="bg-light">

<div class="container mt-4 p-4 bg-white shadow rounded">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
    <h2 class="text-dark mb-0">Group Categories</h2>
    <a href="{{ route('admin.create.group.category') }}" class="btn btn-primary">
      + Add Category
    </a>
  </div>

  <!-- Responsive table -->
  <div class="table-responsive">
    <table class="table eTable table-hover align-middle" id="groupCategoriesTable">
      <thead>
        <tr>
          <th>#</th>
          <th>Category Name</th>
          <th>Parent Category</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($categories as $key => $category)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $category->category_name }}</td>
            <td>{{ $category->parent ? $category->parent->category_name : 'N/A' }}</td>
            <td class="text-center">
              <div class="d-flex justify-content-center flex-wrap gap-1">
                <a href="{{ route('admin.edit.group.category', $category->id) }}" class="btn btn-sm btn-outline-primary px-3">
                 <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('admin.delete.group.category', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="d-flex justify-content-center mt-3">
    {{ $categories->links() }}
  </div>
</div>

<!-- Initialize DataTable -->
<script>
  $(document).ready(function () {
    $('#groupCategoriesTable').DataTable({
      paging: true,
      searching: true,
      ordering: true,
      lengthMenu: [10, 25, 50, 100],
      responsive: true
    });
  });
</script>

</body>
</html>
