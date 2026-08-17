<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit City</title>

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
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: #343a40;
        }

        .form-label {
            font-weight: 600;
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
            font-weight: 600;
            border-radius: 6px;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit City</h2>
    
    <form action="{{ route('admin.cities.update', $city->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- City Name -->
        <div class="mb-3">
            <label class="form-label">City Name:</label>
            <input type="text" name="city_name" value="{{ $city->city_name }}" class="form-control" placeholder="Enter City Name" required>
        </div>
        
        <!-- State Dropdown -->
        <div class="mb-3">
            <label class="form-label">Select State:</label>
            <select name="state_id" id="state_id" class="form-control select2">
                <option value="">-- Select State --</option>
                @foreach($states as $state)
                    <option value="{{ $state->id }}" {{ $city->state_id == $state->id ? 'selected' : '' }}>
                        {{ $state->state_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- City State -->
        <div class="mb-3">
            <label class="form-label">City State:</label>
            <input type="text" name="city_state" value="{{ $city->city_state }}" class="form-control" placeholder="Enter City State" required>
        </div>

        <!-- City Slug (Auto-generated, Readonly) -->
        <div class="mb-3" hidden>
            <label class="form-label">City Slug:</label>
            <input type="text" name="city_slug" value="{{ $city->city_slug }}" class="form-control" placeholder="Auto-generated" readonly>
        </div>


        <!-- City Image Preview -->
       @php
            use Illuminate\Support\Str;
            $imageSrc = Str::startsWith($city->city_image, ['http://', 'https://'])
                ? $city->city_image
                : asset($city->city_image);
        @endphp

        @if($city->city_image)
            <div class="mb-3">
                <label class="form-label">Current Image:</label><br>
                <img src="{{ $imageSrc }}" alt="City Image" style="max-width: 100%; height: auto; border-radius: 8px;">
            </div>
        @endif


        <!-- Upload New Image -->
        <div class="mb-3">
            <label class="form-label">Upload New Image:</label>
            <input type="file" name="image" class="form-control">
        </div>

        <!-- City About -->
        <div class="mb-3">
            <label class="form-label">About City:</label>
            <textarea name="city_about" class="form-control eForm-control content" rows="4" placeholder="Enter details about the city">{{ $city->city_about }}</textarea>
        </div>
        
        <!-- Submit Button -->
        <button type="submit" class="btn btn-success btn-custom">Update City</button>
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

        // Auto-generate slug when typing city name
        $('input[name="city_name"]').on('input', function() {
            let slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
            $('input[name="city_slug"]').val(slug);
        });
    });
</script>

</body>
</html>
