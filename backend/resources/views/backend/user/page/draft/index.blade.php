
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📝 Incomplete Listings</h2>
    </div>

    @if($incompleteListings->isEmpty())
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle-fill me-2"></i>No incomplete listings found.
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <!-- <th scope="col">#</th> -->
                            <th scope="col">User</th>
                            <th scope="col">Created</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incompleteListings as $item)
                            <tr>
                                <!-- <td>{{ $item->id }}</td> -->
                                <td>{{ $item->user->name ?? 'Unknown' }}</td>
                                <td>{{ $item->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                  <div class="d-flex flex-column flex-sm-row justify-content-end align-items-start align-items-sm-center">
                                    <a href="{{ route('incomplete.resume', $item->id) }}" class="btn btn-sm btn-success me-0 me-sm-1">
                                      <i class="bi bi-arrow-repeat"></i> Resume
                                    </a>
                                
                                    <form
                                      action="{{ route('incomplete.delete', $item->id) }}"
                                      method="POST"
                                      class="d-inline-block mt-2 mt-sm-0"
                                      onsubmit="return confirm('Are you sure you want to delete this listing?');"
                                    >
                                      @csrf
                                      @method('DELETE')
                                      <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Delete
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
    @endif
</div>
 <!-- Pagination -->
 <div class="pagination-area" style="padding-left: 1rem">
                        <div aria-label="Page navigation example">
                            <ul class="pagination">
                                {{ $incompleteListings->links() }}
                            </ul>
                        </div>
                    </div>
                    <!-- Pagination end -->