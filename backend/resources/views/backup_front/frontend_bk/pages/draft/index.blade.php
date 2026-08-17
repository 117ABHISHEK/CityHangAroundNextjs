 <div class="d-md-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h3 class="h5"><span><i class="fa fa-flag"></i></span> {{ get_phrase('Incomplete Listings') }}</h3>
           <div class="pagebtnListing">
                <!-- <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.marketplace.create_product'])}}', '{{get_phrase('Create Product')}}');" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#createProduct" class="btn btn-primary"> <i class="fa fa-plus-circle"></i></a> -->
                    <a href="{{ route('pages.create') }}" class="btn btn-primary">
                <i class="fa fa-plus-circle"></i> {{ get_phrase('Create Page') }}
            </a>
            <a href="{{ route('pages') }}" class="btn  mx-1">{{ get_phrase('Pages') }}</a>
            <a href="{{ route('pages.user') }}" class="btn  mx-1">{{ get_phrase('My Pages') }}</a>
            <a href="{{ route('pages.suggested') }}" class="btn  mx-1">{{ get_phrase('Suggested Pages') }}</a>
            <a href="{{ route('pages.joined') }}" class="btn  mx-1">{{ get_phrase('Joined Pages') }}</a>
            
        </div>
    </div>
<div class="container py-4">
   

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
                                    <a href="{{ route('incomplete.resume', $item->id) }}" class="btn btn-sm btn-outline-success me-1">
                                        <i class="bi bi-arrow-repeat"></i> Resume
                                    </a>
                                    <form action="{{ route('incomplete.delete', $item->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this listing?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                 <div class="pagination-area" style="text-align:center;">
                            <div aria-label="Page navigation example">
                                <ul class="pagination">
                               {{ $incompleteListings->links() }}
                                </ul>
                            </div>
                        </div>
            </div>
        </div>
       
    @endif
</div>
