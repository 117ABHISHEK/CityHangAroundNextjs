<style>
  @media (max-width: 576px) {
    h2 {
      font-size: 1.3rem;
    }

    .table th,
    .table td {
      font-size: 0.8rem;
      padding: 0.5rem;
    }

    .btn {
      font-size: 0.75rem;
      padding: 0.3rem 0.5rem;
    }

    .pagination {
      font-size: 0.6rem;
      flex-wrap: wrap;
      justify-content: center !important;
    }

    .pagination .page-item {
      margin: 2px;
    }

    .pagination .page-link {
      padding: 0.3rem 0.5rem;
    }

    

    .action-btn {
      display: block !important;
      margin-bottom: 5px;
      width: 100%;
    }
  }

  @media (max-width: 375px) {
    .container {
      padding: 0.5rem !important;
      width: 100vw;
      max-width: 100vw;
    }

    .table th,
    .table td {
      font-size: 0.7rem;
      padding: 0.3rem;
    }

    .btn {
      font-size: 0.65rem;
      padding: 0.25rem 0.4rem;
    }

    .table-responsive {
      overflow-x: auto;
    }
  }
     @media (max-width: 992px) {
      .action-btn i {
       display: none;
    }
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

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h2 class="mb-0">State List</h2>
    <a href="{{ route('admin.states.create') }}" class="btn btn-primary">
      <i class="fas fa-plus d-none d-sm-inline"></i> Add State
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

 <form method="GET" action="{{ route('admin.state') }}" class="mb-3">
  <div class="row g-2">
    <div class="col-12 col-sm-6 col-md-6">
      <input type="text" name="search" class="form-control" placeholder="Search by State Name..." value="{{ request('search') }}">
    </div>
    <div class="col-6 col-sm-3 col-md-2">
      <button type="submit" class="btn btn-secondary w-100">
        <i class="fas fa-search d-none d-sm-inline"></i> Search
      </button>
    </div>
    <div class="col-6 col-sm-3 col-md-2">
      <a href="{{ route('admin.state') }}" class="btn btn-outline-secondary w-100">
        <i class="fas fa-times d-none d-sm-inline"></i> Clear
      </a>
    </div>
  </div>
</form>


  <div class="card shadow-sm">
    <div class="card-body p-0 table-responsive">
      <table class="table eTable table-hover mb-0">
        <thead >
          <tr>
            <th>#</th>
            <th>State Name</th>
            <th>Abbreviation</th>
            <th>Country Code</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($states as $state)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $state->state_name }}</td>
              <td>{{ $state->state_abbr }}</td>
              <td>{{ $state->state_country_abbr }}</td>
              <td class="text-center">
                <div class="d-flex flex-column flex-sm-row justify-content-center gap-1">
                  <a href="{{ route('admin.states.edit', $state->id) }}" class="btn btn-sm btn-outline-primary px-3">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <form action="{{ route('admin.states.destroy', $state->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this state?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger px-3">
                      <i class="fas fa-trash"></i> Delete
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted">No states found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3 d-flex justify-content-center">
    {{ $states->appends(['search' => request('search')])->onEachSide(1)->links() }}
  </div>
</div>
