<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Area</title>

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
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            font-weight: bold;
        }
        .btn-custom {
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
        }
        .select2-container--default .select2-selection--single {
            height: 45px;
            padding: 8px;
            font-size: 16px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>✏️ Edit Area</h2>

    <form action="{{ route('admin.areas.update', $area->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Area Name -->
        <div class="mb-3">
            <label class="form-label">Area Name:</label>
            <input type="text" name="area_name" class="form-control" value="{{ $area->area_name }}" required>
        </div>

        <!-- City Dropdown -->
        <div class="mb-3">
            <label class="form-label">Select City:</label>
            <select name="city_id" class="form-control select2">
                <option value="">-- Select City --</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ $area->city_id == $city->id ? 'selected' : '' }}>
                        {{ $city->city_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.areas') }}" class="btn btn-secondary btn-custom">⬅️ Back</a>
            <button type="submit" class="btn btn-primary btn-custom">✅ Update Area</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" defer></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select a City",
            allowClear: true,
            width: '100%'
        });
    });
</script>

</body>
</html>
