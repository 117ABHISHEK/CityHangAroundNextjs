<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Spam Words Management</title>

  <!-- Bootstrap (required for responsive modals/buttons) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'" />

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" media="print" onload="this.media='all'" />
    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    </noscript>

  <!-- jQuery & DataTables JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>

  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }

    .container {
      max-width: 1000px;
      margin: auto;
      padding: 20px 15px;
    }

    .btn {
      padding: 8px 15px;
      border-radius: 5px;
    }

    .table-container {
      background: white;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
      margin-top: 20px;
    }

    .scrollable-input {
      width: 100%;
      height: 120px;
      overflow-y: auto;
      border: 1px solid #ccc;
      padding: 8px;
      border-radius: 5px;
      resize: none;
    }

    @media (max-width: 576px) {
      .top-buttons {
        flex-direction: column !important;
        gap: 10px;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <h2 class="mb-4">Spam Words Management</h2>

    <!-- Top Buttons -->
    <div class="d-flex justify-content-between align-items-center flex-wrap top-buttons mb-3">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSpamModal">Add Spam Words</button>
      <a href="{{ route('spam.downloadTemplate') }}" class="btn btn-info">Download CSV Template</a>
    </div>

    <!-- CSV Upload Form -->
    <div class="table-container">
      <h4>Upload CSV File</h4>
      <form action="{{ route('spam.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-2">
          <input type="file" name="file" required class="form-control" />
        </div>
        <button type="submit" class="btn btn-success">Upload CSV</button>
      </form>
    </div>

    <!-- Spam Words Display -->
    <div class="table-container">
      <h4>Spam Words List</h4>
      <div class="table-responsive">
        <table class="table table-bordered" id="spamTable">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Spam Words</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @php
              $normalizedRows = [];

              foreach (($spamWords ?? []) as $item) {
                $rowId = '';
                $rawWord = '';

                if (is_object($item)) {
                  $rowId = $item->id ?? '';
                  $rawWord = $item->word ?? '';
                } elseif (is_array($item)) {
                  $rowId = $item['id'] ?? '';
                  $rawWord = $item['word'] ?? '';
                } else {
                  $rawWord = (string) $item;
                }

                $parts = preg_split('/[\n,\r]+/', (string) $rawWord);
                foreach ($parts as $part) {
                  $part = trim($part);
                  if ($part !== '') {
                    $normalizedRows[] = ['id' => $rowId, 'word' => $part];
                  }
                }
              }

              $allWordsText = implode(', ', array_map(function ($row) {
                return $row['word'];
              }, $normalizedRows));
            @endphp

            @if(!empty($normalizedRows))
              <tr>
                <td>1</td>
                <td>{{ $allWordsText }}</td>
                <td class="text-center">
                  <button class="btn btn-warning btn-sm me-1" onclick="editSpam(@js($normalizedRows[0]['id'] ?? ''), @js($allWordsText))">Edit</button>
                  @if(!empty($normalizedRows[0]['id']))
                    <form action="{{ route('spam.destroy', $normalizedRows[0]['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete all spam words?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                  @endif
                </td>
              </tr>
            @else
            <tr>
              <td colspan="3" class="text-center">No spam words found.</td>
            </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Add Spam Modal -->
  <div class="modal fade" id="addSpamModal" tabindex="-1" aria-labelledby="addSpamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('spam.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Add Spam Words</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <textarea name="word" class="scrollable-input" placeholder="Enter words separated by commas" required></textarea>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Spam Modal -->
  <div class="modal fade" id="editSpamModal" tabindex="-1" aria-labelledby="editSpamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="editSpamForm">
          @csrf
          <input type="hidden" id="editSpamId" name="id" />
          <div class="modal-header">
            <h5 class="modal-title">Edit Spam Words</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <textarea id="editSpamWords" name="word" class="scrollable-input" required></textarea>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function editSpam(id, words) {
      $('#editSpamId').val(id);
      $('#editSpamWords').val(words);
      var modal = new bootstrap.Modal(document.getElementById('editSpamModal'));
      modal.show();
    }

    $('#editSpamForm').on('submit', function (e) {
      e.preventDefault();

      const payload = {
        _token: "{{ csrf_token() }}",
        word: $('#editSpamWords').val()
      };
      const editId = $('#editSpamId').val();
      if (editId) {
        payload.id = editId;
      }

      $.ajax({
        url: "{{ route('spam.update') }}",
        type: "POST",
        data: payload,
        success: function (response) {
          alert(response.message);
          location.reload();
        },
        error: function (xhr) {
          alert("Error updating spam words.");
        }
      });
    });

    $(document).ready(function () {
      $('#spamTable').DataTable();
    });
  </script>

</body>
</html>
