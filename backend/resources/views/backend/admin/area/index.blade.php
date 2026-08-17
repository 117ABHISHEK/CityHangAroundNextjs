<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Area List</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    </noscript>

   <style>
    /* Custom styles for area management */
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }


     .table td {
      border: none !important;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.5rem 0.75rem;
        color: black;
    }
    
    .text-primary {
        color: #007bff !important;
        font-weight: 500;
    }
    
    .text-info {
        color: #17a2b8 !important;
        font-weight: 500;
    }
    
    .text-success {
        color: #28a745 !important;
        font-weight: 500;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .gap-1 {
        gap: 0.25rem !important;
    }
    
    /* Responsive design */
    @media (min-width: 768px) {
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding-top: 5px;
        }
    }
    
    @media (max-width: 768px) {
        .table th, .table td {
            font-size: 0.8rem;
            padding: 0.5rem 0.25rem;
        }
        
        .btn-sm {
            font-size: 0.75rem;
            padding: 0.2rem 0.4rem;
        }
        
        .badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.5rem;
        }
        
        .form-label {
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
    }
    
    @media (max-width: 576px) {
        h2 {
            font-size: 1.3rem;
        }

        .table th, .table td {
            font-size: 0.75rem;
            padding: 0.4rem 0.2rem;
        }

        .btn {
            font-size: 0.7rem;
            padding: 0.25rem 0.4rem;
        }

        .pagination {
            font-size: 0.8rem;
            flex-wrap: wrap;
            justify-content: center !important;
        }

        .pagination .page-item {
            margin: 1px;
        }

        .pagination .page-link {
            padding: 0.25rem 0.4rem;
        }
        
        .d-flex.gap-1 {
            flex-direction: column;
            gap: 0.25rem !important;
        }
        
        .btn-sm {
            width: 100%;
            text-align: center;
        }
    }
    
    @media (max-width: 400px) {
        .select2-container--default .select2-selection--single {
            height: 36px !important;
            font-size: 0.85rem;
            padding: 6px 10px;
        }

        select.form-control,
        .select2-selection--single {
            font-size: 0.85rem;
            padding-right: 30px;
            width: 100% !important;
            box-sizing: border-box;
        }
        
        .table-responsive {
            font-size: 0.7rem;
        }
    }
</style>

</head>
<body class="bg-light">

<div class="container mt-3">
    <h2>Area List</h2>

    <!-- Add Area Button -->
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('admin.areas.create') }}" class="btn btn-primary">➕ Add Area</a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.areas') }}" class="row g-3 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label">Area Name</label>
            <input type="text" name="area_name" class="form-control" placeholder="Search Area Name" value="{{ request('area_name') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">State</label>
            <select name="state_id" class="form-select select2">
                <option value="">-- Select State --</option>
                @foreach ($states as $state)
                    <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>
                        {{ $state->state_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">City</label>
            <select name="city_id" class="form-select select2">
                <option value="">-- Select City --</option>
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                        {{ $city->city_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 d-grid">
            <button type="submit" class="btn btn-success">
                🔍 Filter
            </button>
        </div>
    </form>




    <!-- Areas Table -->
    <div class="table-responsive">
        <table class="table  table-hover">
            <thead >
                <tr>
                    <th width="5%">ID</th>
                    <th width="20%">Area Name</th>
                    <th width="20%">City</th>
                    <th width="20%">State</th>
                    <th width="15%">Country</th>
                    <th width="20%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($areas as $area)
                <tr>
                    <td>
                        <span class="badge">{{ $area->id }}</span>
                    </td>
                    <td>
                        {{ $area->area_name }}
                    </td>
                    <td>
                        <span class="">{{ $area->city?->city_name ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <span class="">{{ $area->city?->state?->state_name ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <span class="">{{ $area->city?->state?->state_country_abbr ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="{{ route('admin.areas.edit', $area->id) }}" class="btn btn-sm btn-outline-primary px-3">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </a>
                            <form action="{{ route('admin.areas.destroy', $area->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger px-3" onclick="return confirm('Are you sure you want to delete this area?')">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3 mb-3">
       {{ $areas->appends(request()->query())->onEachSide(1)->links() }}
    </div>
</div>

<!-- jQuery & Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" defer></script>

<script>
    $(document).ready(function() {
        // Initialize Select2 for all select elements
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true,
            width: '100%'
        });
        
        // Dynamic city loading based on state selection
        $('select[name="state_id"]').on('change', function() {
            var stateId = $(this).val();
            var citySelect = $('select[name="city_id"]');
            
            // Clear current cities
            citySelect.empty().append('<option value="">-- Select City --</option>');
            
            if (stateId) {
                // Show loading state
                citySelect.prop('disabled', true);
                
                // Fetch cities for selected state
                $.ajax({
                    url: '{{ route("admin.ajax.cities.by.state") }}',
                    method: 'GET',
                    data: { state_id: stateId },
                    success: function(response) {
                        $.each(response, function(index, city) {
                            citySelect.append('<option value="' + city.id + '">' + city.city_name + '</option>');
                        });
                        citySelect.prop('disabled', false);
                    },
                    error: function() {
                        citySelect.prop('disabled', false);
                        alert('Error loading cities. Please try again.');
                    }
                });
            }
        });
        
        // Auto-submit form when filters change (optional)
        $('select[name="state_id"], select[name="city_id"]').on('change', function() {
            // Uncomment the line below if you want auto-submit on filter change
            // $('#filterForm').submit();
        });
    });
</script>

</body>
</html>
