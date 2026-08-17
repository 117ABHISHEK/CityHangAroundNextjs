<style>
    @media (max-width: 570px) {
      .action-btn-custom {
        width: 100%;
      }
    }
</style>
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mt-4 mb-3">
        <div class="col-12 col-md-8 mb-2 mb-md-0">
            <h3 class="fw-bold text-primary">
                <i class="fas fa-layer-group"></i> Enquiry Lead Stages
            </h3>
        </div>
        <div class="col-12 col-md-4 text-md-end">
            <a href="{{ route('enquiry-lead-stages.create') }}" class="btn btn-primary shadow w-100 w-md-auto">
                <i class="fas fa-plus"></i> Add New Stage
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead >
                    <tr>
                        <th>#</th>
                        <th><i class="fas fa-tag"></i> Stage Name</th>
                        <th><i class="fas fa-user-tag"></i> For Role</th>
                        <th class="text-center"><i class="fas fa-cogs"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stages as $key => $stage)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-bold text-dark">{{ $stage->stage_name }}</td>
                            <td>
                                <span class="badge bg-info text-white">{{ ucfirst($stage->for_role) }}</span>
                            </td>
                            <td class="text-center">
  <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
    <a href="{{ route('enquiry-lead-stages.edit', $stage->id) }}" 
       class="btn btn-sm btn-outline-primary action-btn-custom">
       <i class="fas fa-edit me-1 d-none d-sm-inline"></i> Edit
    </a>

    <form action="{{ route('enquiry-lead-stages.destroy', $stage->id) }}" method="POST"
          onsubmit="return confirm('Are you sure you want to delete this stage?');">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-sm btn-outline-danger action-btn-custom">
        <i class="fas fa-trash me-1 d-none d-sm-inline"></i> Delete
      </button>
    </form>
  </div>
</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $stages->links() }}
            </div>
        </div>
    </div>
</div>
