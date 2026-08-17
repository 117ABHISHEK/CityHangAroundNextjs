<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    @media (max-width: 1056px) {
        .d-lg-icon {
            display: none !important;
        }
    }
    .text-blue{
        color: blue;
    }
</style>
<div class="container mt-5">
    <!-- Header: Title + Create Button -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 col-12 mb-2 mb-md-0">
            <h2 class="fw-bold mb-0 h4 h2-md">📄 Custom Pages</h2>
        </div>
        <div class="col-md-6 col-12 text-md-end text-start">
            <a href="{{ route('custom_pages.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Page
            </a>
        </div>
    </div>

    <!-- Table or No Data Message -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if($pages->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead >
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Page URL</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pages as $key => $page)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <a href="{{ route('custom_pages.show', $page->slug) }}" target="_blank" class="text-decoration-none fw-bold text-primary">
                                            <i class="fas fa-file-alt"></i> {{ $page->title }}
                                        </a>
                                    </td>
                                    <td class="text-break">
                                        <a href="{{ route('custom_pages.show', $page->slug) }}" target="_blank" class="badge  text-blue">
                                            {{ route('custom_pages.show', $page->slug) }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" class="toggle-status" data-id="{{ $page->id }}">
                                            @if($page->status)
                                                <i class="fas fa-toggle-on text-success fa-2x"></i>
                                            @else
                                                <i class="fas fa-toggle-off text-danger fa-2x"></i>
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            <a href="{{ route('custom_pages.edit', $page->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('custom_pages.destroy', $page->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1" onclick="return confirm('Are you sure?')">
    <i class="fas fa-trash d-inline-block d-lg-icon"></i>
    <span>Delete</span>
</button>

                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warning text-center m-3">
                    <i class="fas fa-exclamation-circle"></i> No custom pages found.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Toggle Status Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".toggle-status").forEach(button => {
            button.addEventListener("click", function () {
                let pageId = this.getAttribute("data-id");
                let icon = this.querySelector("i");

                if (!pageId) return;

                fetch(`/admin/custom_pages/toggle/${pageId}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({})
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        icon.classList.toggle("fa-toggle-on");
                        icon.classList.toggle("fa-toggle-off");
                        icon.classList.toggle("text-success");
                        icon.classList.toggle("text-danger");
                    }
                })
                .catch(err => console.error("Error:", err));
            });
        });
    });
</script>
