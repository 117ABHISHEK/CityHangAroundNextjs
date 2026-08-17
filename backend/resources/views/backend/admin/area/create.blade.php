<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Area</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    </noscript>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: #007bff;
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-custom {
            font-size: 16px;
            font-weight: bold;
        }

        /* Fix Select2 dropdown issues */
        .select2-container--default .select2-selection--single {
            height: 38px; /* Match Bootstrap input height */
            padding: 6px;
            border-radius: 5px;
        }

        .select2-container {
            width: 100% !important; /* Ensure full width */
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">➕ Add New Area</div>
                <div class="card-body">
                    
                    <form action="{{ route('admin.areas.store') }}" method="POST">
                        @csrf

                        <!-- Area Name -->
                        <div class="mb-3">
                            <label class="form-label">🏙️ Area Name:</label>
                            <input type="text" name="area_name" class="form-control" placeholder="Enter Area Name" required>
                        </div>

                        <!-- City Dropdown (Fixed) -->
                        <div class="mb-3">
                            <label class="form-label">🏢 Select City:</label>
                            <select name="city_id" class="form-select select2">
                                <option value="">-- Select City --</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.areas') }}" class="btn btn-secondary btn-custom">⬅️ Back</a>
                            <button type="submit" class="btn btn-success btn-custom">✅ Save Area</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery & Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" defer></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select a City",
            allowClear: true,
            width: 'resolve', 
            containerCssClass: 'form-select' // Makes it match Bootstrap styling
        });
    });
</script>

</body>
</html>
