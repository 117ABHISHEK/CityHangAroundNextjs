<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Approval</h2>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($pages->count() > 0)
                <table class="table table-hover align-middle">
                <thead>
    <tr>
        <th>#</th>
        <th>Title</th>
        <th>Status</th>
    </tr>
</thead>
<tbody>
    @foreach($pages as $key => $page)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>
                {{ $page->title }}
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

        </tr>
    @endforeach
</tbody>

                </table>
            @else
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-circle"></i> No custom pages found.
                </div>
            @endif
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".toggle-status").forEach(button => {
            button.addEventListener("click", function () {
                let pageId = this.getAttribute("data-id");
                let icon = this.querySelector("i");

                // Check if pageId is valid
                if (!pageId) {
                    console.error("Error: Page ID not found.");
                    return;
                }

                fetch(`/admin/manage/toggle/${pageId}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({}) // Send empty body if needed
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        if (data.status) {
                            icon.classList.remove("fa-toggle-off", "text-danger");
                            icon.classList.add("fa-toggle-on", "text-success");
                        } else {
                            icon.classList.remove("fa-toggle-on", "text-success");
                            icon.classList.add("fa-toggle-off", "text-danger");
                        }
                    } else {
                        console.error("Error: Server did not return success.");
                    }
                })
                .catch(error => console.error("Fetch Error:", error));
            });
        });
    });
</script>


