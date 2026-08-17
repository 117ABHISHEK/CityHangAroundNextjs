<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>City List</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'" />
  
  <!-- Bootstrap Icons CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet" media="print" onload="this.media='all'" />
  
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'" />
    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    </noscript>

  <style>
   .container {
    max-width: 900px;
    margin: 50px auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
  }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      font-weight: bold;
    }

    .table th {
      /*background-color: #007bff;*/
      color: white;
      text-align: center;
      border: none !important;
    }
    .table td {
      border: none !important;
    }
    .table th a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 0.5rem;
    }

    .table th a:hover {
      color: #f8f9fa;
      text-decoration: none;
    }

    .table th a i {
      margin-left: 5px;
      font-size: 0.8em;
    }

    .table td, .table th {
      vertical-align: middle;
      text-align: center;
      color: black;
    }

    .action-btn i {
      display: inline-block;
    }
    .table-responsive {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }
    /* Responsive Adjustments */
    @media (max-width: 576px) {
      h2 {
        font-size: 1.3rem;
      }

      .table th, .table td {
        font-size: 0.8rem;
        padding: 0.5rem;
      }

      .btn {
        font-size: 0.75rem;
        padding: 0.3rem 0.5rem;
      }

      .pagination {
        font-size: 0.7rem;
        flex-wrap: wrap;
        justify-content: center !important;
      }

      .pagination .page-item {
        margin: 2px;
      }

      .pagination .page-link {
        padding: 0.3rem 0.5rem;
      }

      .action-btn i {
        display: none; /* Hide icons on small screens */
      }

      .action-btn {
        display: block;
        margin-bottom: 5px;
        width: 100%;
      }
      
      .container {
        max-width: 100% !important;
        padding: 15px !important;
        margin: 0 auto;
      }

      /* Filter form responsive adjustments */
      .row.g-3 > div {
        margin-bottom: 10px;
      }

      .alert-info .badge {
        display: block;
        margin-bottom: 5px;
        margin-right: 0 !important;
      }
    }
    

    @media (max-width: 400px) {
      .select2-container--default .select2-selection--single {
        height: 36px !important;
        font-size: 0.85rem;
        padding: 6px 10px;
      }

      select.form-control,
      .select2-selection--single {
        font-size: 0.85rem;
        padding-right: 30px;
        width: 100% !important;
        box-sizing: border-box;
      }
    }
 @media (max-width: 375px) {
  .table th,
  .table td {
    font-size: 0.7rem;
    padding: 0.3rem;
  }

  .table {
    font-size: 0.7rem;
  }

  .btn {
    font-size: 0.65rem;
    padding: 0.25rem 0.4rem;
  }

  .container {
    padding: 0.5rem !important;
    width: 100vw;
    max-width: 100vw;
  }

  .table-responsive {
    overflow-x: auto;
  }
}


  </style>
</head>
<body class="bg-light">

<div class="container-fluid px-3 py-4">

  <h2>City List</h2>

  <!-- Add City Button -->
  <div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.cities.create') }}" class="btn btn-primary">➕ Add City</a>
  </div>

  <!-- Success Message -->
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <!-- Current Filters Summary -->
  @if(request('city_name') || request('state_id') || request('sort_by') != 'pages_count' || request('sort_order') != 'desc')
    <div class="alert alert-info">
      <strong>Current Filters:</strong>
      @if(request('city_name'))
        <span class="badge bg-primary me-2">City: {{ request('city_name') }}</span>
      @endif
      @if(request('state_id'))
        @php
          $selectedState = $states->firstWhere('id', request('state_id'));
        @endphp
        @if($selectedState)
          <span class="badge bg-primary me-2">State: {{ $selectedState->state_name }}</span>
        @endif
      @endif
      @if(request('sort_by') != 'pages_count' || request('sort_order') != 'desc')
        <span class="badge bg-info me-2">
          Sorted by: {{ ucfirst(str_replace('_', ' ', request('sort_by', 'pages_count'))) }} 
          ({{ ucfirst(request('sort_order', 'desc')) }})
        </span>
      @endif
    </div>
  @endif

  <!-- Filter Form -->
  <form method="GET" action="{{ route('admin.cities') }}" class="row g-3 align-items-end mb-4">
    <div class="col-md-4">
      <label class="form-label d-none d-md-block">City Name</label>
      <input type="text" name="city_name" class="form-control" placeholder="Search City Name" value="{{ request('city_name') }}">
    </div>
    <div class="col-md-3">
      <label class="form-label d-none d-md-block">State</label>
      <select name="state_id" class="form-select">
        <option value="">-- Select State --</option>
        @foreach ($states as $state)
          <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>
            {{ $state->state_name }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label d-none d-md-block">Sort By</label>
      <select name="sort_by" class="form-select">
        <option value="pages_count" {{ request('sort_by', 'pages_count') == 'pages_count' ? 'selected' : '' }}>Listing Count</option>
        <option value="city_name" {{ request('sort_by') == 'city_name' ? 'selected' : '' }}>City Name</option>
        <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>ID</option>
        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label d-none d-md-block">Order</label>
      <select name="sort_order" class="form-select">
        <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
      </select>
    </div>
    <div class="col-md-6 d-grid">
      <button type="submit" class="btn btn-success">
        🔍 Filter & Sort
      </button>
    </div>
    <div class="col-md-6 d-grid">
      <a href="{{ route('admin.cities') }}" class="btn btn-secondary">
        🗑️ Clear Filters
      </a>
    </div>
  </form>

  <!-- Cities Table -->
  <table class="table table-hover">
    <thead>
      <tr>
        <th>
          <a href="{{ route('admin.cities', array_merge(request()->query(), ['sort_by' => 'id', 'sort_order' => request('sort_by') == 'id' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="text-black text-decoration-none">
            ID
            @if(request('sort_by') == 'id')
              @if(request('sort_order') == 'asc')
                <i class="bi bi-arrow-up"></i>
              @else
                <i class="bi bi-arrow-down"></i>
              @endif
            @endif
          </a>
        </th>
        <th>
          <a href="{{ route('admin.cities', array_merge(request()->query(), ['sort_by' => 'city_name', 'sort_order' => request('sort_by') == 'city_name' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="text-black text-decoration-none">
            City Name
            @if(request('sort_by') == 'city_name')
              @if(request('sort_order') == 'asc')
                <i class="bi bi-arrow-up"></i>
              @else
                <i class="bi bi-arrow-down"></i>
              @endif
            @endif
          </a>
        </th>
        <th>
          <a href="{{ route('admin.cities', array_merge(request()->query(), ['sort_by' => 'pages_count', 'sort_order' => request('sort_by') == 'pages_count' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="text-black text-decoration-none">
            Listing
            @if(request('sort_by') == 'pages_count')
              @if(request('sort_order') == 'asc')
                <i class="bi bi-arrow-up"></i>
              @else
                <i class="bi bi-arrow-down"></i>
              @endif
            @endif
          </a>
        </th>
        <th>State</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($cities as $city)
        <tr>
          <td>{{ $city->id }}</td>
          <td>{{ $city->city_name }}</td>
          <td>{{ $city->pages_count }}</td>
          <td>{{ $city->state->state_name }}</td>
          <td>
            <a href="{{ route('admin.cities.edit', $city->id) }}" class="btn btn-sm btn-outline-primary px-3">
              <i class="bi bi-pencil-fill"></i> Edit
            </a>
            <form action="{{ route('admin.cities.destroy', $city->id) }}" method="POST" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger px-3" onclick="return confirm('Are you sure?')">
                <i class="bi bi-trash-fill"></i> Delete
              </button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <!-- Pagination -->
  <div class="d-flex justify-content-center">
    {{ $cities->appends(request()->query())->onEachSide(1)->links() }}
  </div>
</div>

<!-- jQuery & Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" defer></script>
<script>
  $(document).ready(function () {
    $('.select2').select2({
      placeholder: "Select a State",
      allowClear: true,
      width: '100%'
    });
  });
</script>

</body>
</html>
