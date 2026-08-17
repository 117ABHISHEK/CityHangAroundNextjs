<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Reports</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    </noscript>

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" defer></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js" defer></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js" defer></script>
</head>
<style>

.table th, .table td {
 border: none !important;
}
.table thead tr {
  border-bottom: 2px solid black !important;
}

</style>
<body>

<div class="main_content">
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
                    <div class="d-flex flex-column">
                        <h4>{{ get_phrase('All Reports') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="mb-3">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" class="form-control" value="{{ request('start_date') }}">

        <label for="end_date" class="mt-2">End Date:</label>
        <input type="date" id="end_date" class="form-control" value="{{ request('end_date') }}">
        
        <button class="btn btn-primary mt-2" onclick="filterByDate()">Filter</button>
    </div>

    <!-- Report Table -->
    <div class="table-responsive">
        <table class="table" id="reportTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Entity</th>
                    <th>Reported By</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Reason</th>
                    <th>Proof</th>
                    <th>Response Required</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $key => $report)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ ucfirst($report->type) }}</td>
                        <td>{{ $report->entity_name ?? 'N/A' }}</td>
                        <td>{{ $report->full_name }}</td>
                        <td>{{ $report->email }}</td>
                        <td>{{ $report->phone ?? 'N/A' }}</td>
                        <td>{{ $report->reason }}</td>
                        <td>
                            @if($report->proof_attachment)
                                <a href="{{ asset('storage/' . $report->proof_attachment) }}" target="_blank">View Proof</a>
                            @else
                                No Proof
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $report->response_required == 'Yes' ? 'bg-danger' : 'bg-success' }}">
                                {{ $report->response_required }}
                            </span>
                        </td>
                        <td>{{ $report->created_at }}</td>
                       
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination-area">
            {{ $reports->links() }}
        </div>
    </div>
</div>

<script>
    function deleteReport(reportId) {
        if (!confirm("Are you sure you want to delete this report?")) return;

        $.ajax({
            url: "/admin/reports/" + reportId,
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                alert(response.message);
                location.reload();
            },
            error: function(xhr) {
                alert('Error deleting report.');
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
        $('#reportTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "lengthMenu": [10, 25, 50, 100],
            "dom": 'Bfrtip',
            "buttons": ['copyHtml5', 'csvHtml5', 'excelHtml5']
        });
    });
</script>

</body>
</html>
