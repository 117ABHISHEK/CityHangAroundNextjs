<style>
     @media (max-width: 346px) {
         .del-btn{
             display: none;
         }
</style>
<div class="container mt-4">
    <!-- Header with Add Button -->
    <div class="row align-items-center mb-3">
        <div class="col-12 col-md-9 mb-2 mb-md-0">
            <h2 class="text-primary mb-0">Subscription Features</h2>
        </div>
        <div class="col-12 col-md-3 text-md-end">
            <a href="{{ route('admin.features.create') }}" class="btn btn-success w-100 w-md-auto">
                <i class="fa fa-plus"></i> Add Feature
            </a>
        </div>
    </div>

    <!-- Search Filters -->
    <form action="{{ route('admin.features.index') }}" method="GET" class="mb-4">
        <div class="row g-2">
            <div class="col-12 col-md-4">
                <input type="text" name="name" class="form-control" placeholder="Search by Name" value="{{ request()->get('name') }}">
            </div>
            <div class="col-6 col-md-3">
                <input type="date" name="start_date" class="form-control" value="{{ request()->get('start_date') }}">
            </div>
            <div class="col-6 col-md-3">
                <input type="date" name="end_date" class="form-control" value="{{ request()->get('end_date') }}">
            </div>
            <div class="col-12 col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search"></i> Filter
                </button>
            </div>
        </div>
    </form>

    <!-- Features Table -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Feature Name</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($features as $key => $feature)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $feature->feature_name }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.features.edit', $feature->id) }}" class="btn btn-outline-primary btn-sm mb-1">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.features.destroy', $feature->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fa fa-trash del-btn"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
