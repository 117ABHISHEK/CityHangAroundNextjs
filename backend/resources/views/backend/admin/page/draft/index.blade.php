<style>
  @media (max-width: 583px) {
    .pagination-area {
      font-size: 0.75rem;
    }

    .pagination-area .page-link {
      padding: 0.3rem 0.5rem;
      font-size: 0.7rem;
    }

    h2.incomplete-heading {
      font-size: 1.1rem;
    }

    .incomplete-actions i {
      display: none; /* Hide icons */
    }

    .incomplete-actions .btn {
      font-size: 0.75rem;
      padding: 0.3rem 0.6rem;
    }
  }

  @media (max-width: 416px) {
    .fix-no-margin {
      margin: 0 !important;
      width: 100%;
    }

    .pagination-area {
      padding: 0 0.5rem;
      font-size: 0.75rem;
    }

    .pagination-area .page-link {
      padding: 0.25rem 0.5rem;
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


<div class="container-fluid py-4 fix-no-margin">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <h2 class="fw-bold mb-2 mb-md-0 incomplete-heading">📝 Incomplete Listings</h2>

  </div>

  @if($incompleteListings->isEmpty())
    <div class="alert alert-info text-center">
      <i class="bi bi-info-circle-fill me-2"></i>No incomplete listings found.
    </div>
  @else
    <div class="card shadow-sm border-0">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table eTable table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th scope="col">User</th>
                <th scope="col">Created</th>
                <th scope="col" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($incompleteListings as $item)
              <tr>
                <td>
                  <div class="dAdmin_info_name min-w-100px">
                    <a href="{{ route('user.profile.view', $item->user->id) }}" class="text-dark" target="_blank">
                      {{ $item->user->name ?? '' }}
                    </a><br>
                    <small>{{ $item->user->email }}</small>
                  </div>
                </td>
                <td>{{ $item->created_at->format('M d, Y') }}</td>
                <td class="text-end">
                  <div class="d-flex flex-column flex-sm-row justify-content-end gap-1 incomplete-actions">
    <a href="{{ route('incomplete.resume', $item->id) }}" class="btn btn-sm btn-outline-primary px-3">
        <i class="bi bi-arrow-repeat"></i> Resume
    </a>
    <form action="{{ route('incomplete.delete', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this listing?');">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-outline-danger px-3">
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
      </div>
    </div>
  @endif

  <div class="pagination-area mt-3">
    <div aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
       {{ $incompleteListings->onEachSide(1)->links() }}
      </ul>
    </div>
  </div>
</div>
