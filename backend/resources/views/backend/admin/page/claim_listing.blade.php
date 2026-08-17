<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Pages</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    </noscript>

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" defer></script>
<style>
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
</head>
<body>
<div class="container-fluid mt-4">
    <div class="mainSection-title mb-4">
        <div class="row">
            <div class="col-12">
                <h4>{{ get_phrase('All Claims') }}</h4>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3 gy-2">
        <div class="col-12 col-md-3">
            <label for="start_date" class="form-label">Start Date:</label>
            <input type="date" id="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="col-12 col-md-3">
            <label for="end_date" class="form-label">End Date:</label>
            <input type="date" id="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="col-12 col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100" onclick="filterByDate()">Filter</button>
        </div>
        <div class="col-12 col-md-4">
            <label for="globalSearchInput" class="form-label">Search Claims:</label>
            <input type="text" id="globalSearchInput" class="form-control" placeholder="Search claims...">
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table  eTable" id="searchableTable">
            <thead >
                <tr>
                    <th>#</th>
                    <th>Page</th>
                    <th>Current Owner</th>
                    <th>Claimant</th>
                    <th>Status</th>
                    <th>Ownership Proof</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($claims as $key => $claim)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a href="{{ url($claim->city_slug . '/' . $claim->area_slug . '/' . $claim->item_slug) }}" class="text-dark" target="_blank">
                                {{ $claim->title }}
                            </a>
                        </td>
                        <td>{{ $claim->current_owner ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('user.profile.view', $claim->user_id) }}" class="text-dark" target="_blank">
                                {{ $claim->claimant_name }}
                            </a>
                            <br><small>{{ $claim->claimant_email }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $claim->is_approved == 'Y' ? 'bg-success' : 'bg-warning' }}">
                                {{ $claim->is_approved == 'Y' ? 'Approved' : 'Pending' }}
                            </span>
                        </td>
                        <td>
                            @if ($claim->ownership_proof)
                                @php
                                    $proof = $claim->ownership_proof;
                                    $proofUrl = Str::startsWith($proof, ['http://', 'https://'])
                                        ? $proof
                                        : asset('storage/pages/ownership_proof/' . $proof);
                                @endphp
                                <a href="{{ $proofUrl}}" target="_blank">View Proof</a>
                            @else
                                <span class="text-muted">No Proof</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($claim->submitted_at)->format('M d, Y') }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary px-3" onclick="updateStatus({{ $claim->id }}, 'Y')">Approve</button>
                            <button class="btn btn-sm btn-outline-danger px-3" onclick="updateStatus({{ $claim->id }}, 'N')">Reject</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-area mt-3 d-flex justify-content-center">
        {{ $claims->links() }}
    </div>

    @include('backend.footer')
</div>

   
<script>
    function fetchClaims(search = '') {
        $.ajax({
            url: '{{ route("admin.claims.search") }}',
            method: 'GET',
            data: { search: search },
            success: function (data) {
                let tbody = '';

                if (data.length === 0) {
                    tbody = '<tr><td colspan="8" class="text-center">No results found.</td></tr>';
                } else {
                    data.forEach((row, index) => {
                        let pageUrl = `/${row.city_slug ?? ''}/${row.area_slug ?? ''}/${row.category_slug ?? ''}/${row.item_slug}`;
                        let statusBadge = row.is_approved === 'Y'
                            ? '<span class="badge bg-success">Approved</span>'
                            : '<span class="badge bg-warning">Pending</span>';

                        let proofHtml = row.ownership_proof
                            ? `<a href="/storage/pages/ownership_proof/${row.ownership_proof}" target="_blank">View Proof</a>`
                            : `<span class="text-muted">No Proof</span>`;

                        let submittedDate = row.submitted_at
                            ? new Date(row.submitted_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
                            : 'N/A';

                        tbody += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>
                                    <a href="${pageUrl}" class="text-dark" target="_blank">${row.title || 'Untitled'}</a>
                                </td>
                                <td>${row.current_owner || 'N/A'}</td>
                                <td>
                                    <a href="/user/view-profile/${row.user_id}" class="text-dark" target="_blank">
                                        ${row.claimant_name || 'Unknown'}
                                    </a><br>
                                    <small>${row.claimant_email || ''}</small>
                                </td>
                                <td>${statusBadge}</td>
                                <td>${proofHtml}</td>
                                <td>${submittedDate}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success" onclick="updateStatus(${row.id}, 'Y')">Approve</button>
                                    <button class="btn btn-sm btn-danger" onclick="updateStatus(${row.id}, 'N')">Reject</button>
                                </td>
                            </tr>
                        `;
                    });
                }

                $('#searchableTable tbody').html(tbody);
                $('.pagination-area').toggle(!search); // hide pagination if searching
            },
            error: function (xhr) {
                console.error('Search error:', xhr);
            }
        });
    }

    // Triggered on each key press
    $('#globalSearchInput').on('keyup', function () {
        let search = $(this).val().trim();

        // Call with or without search term
        fetchClaims(search);
    });
    </script>


<!-- Initialize DataTable -->
<script>
    function updateStatus(claimId, status) {
        if (!confirm("Are you sure?")) return;

        $.ajax({
            url: "{{ route('admin.claim.update') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                claim_id: claimId,
                status: status
            },
            success: function(response) {
                location.reload();
            }
        });
    }

    function filterByDate() {
        let startDate = document.getElementById("start_date").value;
        let endDate = document.getElementById("end_date").value;
        if (startDate && endDate) {
            window.location.href = `?start_date=${startDate}&end_date=${endDate}`;
        }
    }

    $(document).ready(function () {
        $('#searchableTable').DataTable({
            "paging": false,
            "searching": false,
            "ordering": false,
            "lengthMenu": [10, 25, 50, 100]
        });
       
    });
</script>


</body>
</html>
