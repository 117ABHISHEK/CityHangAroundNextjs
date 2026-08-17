<style>
    @media (max-width: 576px) {
        .action-buttons {
            flex-direction: column !important;
            gap: 0.5rem;
        }
    }

    @media (max-width: 527px) {
        .pagination .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
    
  

    @media (max-width: 456px) {
        .delete-icon {
            display: none !important;
        }
    }
     
      @media (max-width:380px){
        .pagination .page-link {
            font-size: 0.5rem;
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
    <div class="row align-items-center">
        <div class="col-12 col-md-6 mb-3 mb-md-0">
            <h2 class="mb-0">Category List ({{ $categories->total() }})</h2>
        </div>
        <div class="col-12 col-md-6">
            <form action="{{ route('categories.index') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                <input type="text" name="search" class="form-control" placeholder="Search by category or subcategory" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <div class="card mt-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead >
                        <tr>
                            <th>Category Name</th>
                            <th>Lead Price</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->product_category_name }}</td>
                            <td>
                                <span class="lead-price-text" id="lead-price-text-{{ $category->id }}">
                                    {{ $category->lead_price !== null ? number_format($category->lead_price, 2) : 'Not Set' }}
                                </span>
                                <input type="number" class="form-control lead-price-input d-none" id="lead-price-input-{{ $category->id }}" value="{{ $category->lead_price ?? '' }}">
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end action-buttons">
                                    <button class="btn btn-sm btn-outline-primary px-3 " onclick="editLeadPrice({{ $category->id }})">
                                        <i class="bi bi-pencil-square"></i>Edit
                                    </button>
                                    <button class="btn btn-sm btn-success d-none me-1" id="save-btn-{{ $category->id }}" onclick="saveLeadPrice({{ $category->id }})">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                    <form action="{{ route('categories.deleteLeadPrice', $category->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-3 ml-3" onclick="return confirm('Remove Lead Price?')">
                                            <i class="bi bi-trash delete-icon"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No categories found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3 mb-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
<script>
    function editLeadPrice(id) {
        document.getElementById(`lead-price-text-${id}`).classList.add('d-none');
        document.getElementById(`lead-price-input-${id}`).classList.remove('d-none');
        document.getElementById(`save-btn-${id}`).classList.remove('d-none');
    }
    
    function saveLeadPrice(id) {
    let price = document.getElementById(`lead-price-input-${id}`).value;
    let url = "{{ route('categories.updateLeadPrice', ':id') }}".replace(':id', id);

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ lead_price: price })
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              location.reload(); // Reload the page after a successful update
          } else {
              alert('Error updating lead price. Please try again.');
          }
      }).catch(error => {
          console.error('Error:', error);
      });
}

</script>
