<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Videos</title>

    <!-- Bootstrap & DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    </noscript>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

    <style>
        .video-thumbnail {
            width: 100px;
            height: 60px;
            object-fit: cover;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }
        .video-thumbnail:hover {
            opacity: 0.7;
        }
        .approval-icon {
            font-size: 20px;
            cursor: pointer;
            transition: 0.3s;
        }
        .approval-icon:hover {
            opacity: 0.7;
        }
        .selected-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
    </style>
    <style>

.table1 th, .table1 td {
 border: none !important;
}
.table1 thead tr {
  border-bottom: 2px solid black !important;
}

</style>
</head>
<body>

<div class="container mt-5">
    <h4>All Videos</h4>

    <div class="mb-3">
        <button id="approveAllBtn" class="btn btn-success">Approve All</button>
        <button id="approveSelectedBtn" class="btn btn-primary">Approve Selected</button>
    </div>
    
    <div class="table-responsive">
        <table class="table table1" id="videoTable">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Sl No</th>
                    <th>Video Title</th>
                    <th>Uploaded By</th>
                    <th class="text-center">Play Video</th>
                    <th class="text-center">Approval Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($videos as $key => $video)
                    <tr>
                        <td><input type="checkbox" class="selected-checkbox" value="{{ $video->id }}"></td>
                        <td>{{ ++$key }}</td>
                        <td>{{ $video->title }}</td>
                        <td>{{ $video->user->name ?? 'Unknown' }}</td>
                        <td class="text-center">
                            <img src="https://img.icons8.com/ios/50/video.png" 
                                 class="video-thumbnail" 
                                 data-bs-toggle="modal" 
                                 data-bs-target="#videoModal" 
                                 data-video="{{ asset('storage/videos/' . $video->file) }}">
                        </td>
                        <td class="text-center">
                            <i class="fas fa-check-circle approval-icon" 
                               data-id="{{ $video->id }}" 
                               data-status="{{ $video->approve_status }}"
                               style="color: {{ $video->approve_status == 2 ? 'green' : 'red' }};">
                            </i>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Video Player</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <video id="videoPlayer" width="100%" controls>
                    <source src="" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#videoTable').DataTable();

        // Handle video modal
        $('#videoModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var videoSrc = button.data('video');
            $("#videoPlayer source").attr("src", videoSrc);
            $("#videoPlayer")[0].load();
            $("#videoPlayer")[0].play();
        });

        // Stop video when modal is closed
        $('#videoModal').on('hidden.bs.modal', function () {
            $("#videoPlayer")[0].pause();
        });

        // Approve/Disapprove Video
        $('.approval-icon').click(function () {
            var icon = $(this);
            var videoId = icon.data('id');
            var currentStatus = icon.data('status');
            var newStatus = currentStatus == 1 ? 2 : 1;

            $.ajax({
                url: "{{ route('admin.video.approve') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: videoId,
                    approve_status: newStatus
                },
                success: function (response) {
                    if (response.success) {
                        icon.data('status', newStatus);
                        icon.css('color', newStatus == 2 ? 'green' : 'red');
                    }
                },
                error: function () {
                    alert("Failed to update approval status!");
                }
            });
        });

        // Select/Deselect All
        $('#selectAll').click(function() {
            $('.selected-checkbox').prop('checked', this.checked);
        });

        // Approve Selected
        $('#approveSelectedBtn').click(function() {
            var selectedVideos = $('.selected-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedVideos.length === 0) {
                alert("No videos selected!");
                return;
            }

            $.ajax({
                url: "{{ route('admin.video.approve.multiple') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    ids: selectedVideos,
                    approve_status: 2
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                error: function() {
                    alert("Failed to approve selected videos!");
                }
            });
        });

        // Approve All
        $('#approveAllBtn').click(function() {
            $.ajax({
                url: "{{ route('admin.video.approve.all') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}", approve_status: 2 },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                error: function() {
                    alert("Failed to approve all videos!");
                }
            });
        });

    });
</script>

</body>
</html>
