<div class="container py-5">
    <!-- Header: Title + Create Button -->
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-6">
            <h2 class="mb-3 mb-md-0">Email Templates</h2>
        </div>
        <div class="col-12 col-md-6 text-md-end">
            <a href="{{ route('admin.campaign_templates.create') }}" class="btn btn-primary w-100 w-md-auto">
                <i class="bi bi-plus-circle me-1"></i> Create New Template
            </a>
        </div>
    </div>

    <!-- Template Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead >
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($templates as $template)
                            <tr>
                                <td>{{ $template->name }}</td>
                                <td class="text-end">
                                 <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                                    <a href="{{ route('admin.campaign_templates.edit', $template->id) }}"
                                       class="btn btn-sm btn-outline-primary px-3"
                                       style="max-width: 130px;">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                
                                    <form action="{{ route('admin.campaign_templates.destroy', $template->id) }}"
                                          method="POST"
                                          class=" w-sm-auto"
                                          style="max-width: 130px;"
                                          onsubmit="return confirm('Are you sure you want to delete this template?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                 </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">No templates found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
