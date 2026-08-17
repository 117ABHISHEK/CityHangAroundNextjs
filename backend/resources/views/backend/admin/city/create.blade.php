<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add City</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    </noscript>
    
    <style>
        /* Custom Styling */
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
            margin-bottom: 20px;
            font-weight: bold;
        }

        .select2-container--default .select2-selection--single {
            height: 45px;
            padding: 10px;
            font-size: 16px;
            border-radius: 6px;
        }

        .btn-custom {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            border-radius: 6px;
        }
    </style>
</head>
<body class="bg-light">

<div class="container">
    <h2>Add City</h2>
    
    <form action="{{ route('admin.cities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- City Name -->
        <div class="mb-3">
            <label class="form-label">City Name:</label>
            <input type="text" name="city_name" class="form-control" placeholder="Enter City Name" required>
        </div>
        
        <!-- State Dropdown -->
        <div class="mb-3">
            <label class="form-label">Select State:</label>
            <select name="state_id" id="state_id" class="form-control select2">
                <option value="">-- Select State --</option>
                @foreach($states as $state)
                    <option value="{{ $state->id }}" {{ isset($city) && $city->state_id == $state->id ? 'selected' : '' }}>
                        {{ $state->state_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- City Slug -->
        <div class="mb-3">
            <label class="form-label">City State:</label>
            <input type="text" name="city_state" class="form-control" placeholder="Enter City State" required>
        </div>

        <!-- City Image Upload -->
        <div class="mb-3">
            <label class="form-label">City Image:</label>
            <input type="file" name="city_image" class="form-control">
        </div>

        <!-- City About -->
        <div class="mb-3">
            <label class="form-label">About City:</label>
            <textarea name="city_about" id="city_about" class="form-control eForm-control content" rows="6" placeholder="Write your content here..."></textarea>
        </div>
        
        <!-- Submit Button -->
        <button type="submit" class="btn btn-success btn-custom">Save City</button>
    </form>
</div>

<!-- jQuery & Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" defer></script>

<script>
    $(document).ready(function() {
        $('#state_id').select2({
            placeholder: "Select a State",
            allowClear: true,
            width: '100%'
        });
    });
</script>

</body>
</html>
