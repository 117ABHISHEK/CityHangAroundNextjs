<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Country</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'" />
  
  <!-- Bootstrap Icons CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet" media="print" onload="this.media='all'" />
    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet" />
    </noscript>

  <style>
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
      margin-bottom: 30px;
      font-weight: bold;
      color: #333;
    }

    .form-label {
      font-weight: 600;
      color: #555;
    }

    .form-control:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #0056b3;
    }

    .btn-secondary {
      background-color: #6c757d;
      border-color: #6c757d;
    }

    .btn-secondary:hover {
      background-color: #545b62;
      border-color: #545b62;
    }

    .flag-preview {
      max-width: 100px;
      max-height: 60px;
      border: 1px solid #ddd;
      border-radius: 4px;
      margin-top: 10px;
    }

    .current-flag {
      max-width: 100px;
      max-height: 60px;
      border: 1px solid #ddd;
      border-radius: 4px;
      margin: 10px 0;
    }

    .help-text {
      font-size: 0.875rem;
      color: #6c757d;
      margin-top: 5px;
    }

    /* Responsive Adjustments */
    @media (max-width: 576px) {
      .container {
        max-width: 100% !important;
        padding: 20px !important;
        margin: 20px auto;
      }

      h2 {
        font-size: 1.5rem;
      }

      .btn {
        width: 100%;
        margin-bottom: 10px;
      }
    }
  </style>
</head>
<body class="bg-light">

<div class="container">

  <h2>✏️ Edit Country</h2>

  <!-- Back Button -->
  <div class="mb-3">
    <a href="{{ route('admin.countries') }}" class="btn btn-secondary">
      <i class="bi bi-arrow-left"></i> Back to Countries
    </a>
  </div>

  <!-- Success Message -->
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <!-- Error Messages -->
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Country Form -->
  <form method="POST" action="{{ route('admin.countries.update', $country->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
      <!-- Country Name -->
      <div class="col-md-6 mb-3">
        <label for="country_name" class="form-label">Country Name *</label>
        <input type="text" 
               class="form-control @error('country_name') is-invalid @enderror" 
               id="country_name" 
               name="country_name" 
               value="{{ old('country_name', $country->country_name) }}" 
               placeholder="e.g., United States"
               required>
        @error('country_name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="help-text">Enter the full name of the country</div>
      </div>

      <!-- Country Code -->
      <div class="col-md-6 mb-3">
        <label for="country_code" class="form-label">Country Code *</label>
        <input type="text" 
               class="form-control @error('country_code') is-invalid @enderror" 
               id="country_code" 
               name="country_code" 
               value="{{ old('country_code', $country->country_code) }}" 
               placeholder="e.g., USA"
               maxlength="3"
               style="text-transform: uppercase;"
               required>
        @error('country_code')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="help-text">3-letter ISO country code (e.g., USA, GBR, IND)</div>
      </div>
    </div>

    <!-- Country Flag -->
    <div class="mb-3">
      <label for="country_flag" class="form-label">Country Flag</label>
      
      <!-- Current Flag Display -->
      @if($country->country_flag)
        <div class="mb-2">
          <strong>Current Flag:</strong>
          <img src="{{ asset('storage/countries/flags/' . $country->country_flag) }}" 
               alt="{{ $country->country_name }}" 
               class="current-flag">
        </div>
      @endif
      
      <input type="file" 
             class="form-control @error('country_flag') is-invalid @enderror" 
             id="country_flag" 
             name="country_flag" 
             accept="image/*">
      @error('country_flag')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <div class="help-text">Upload a new flag image to replace the current one (JPEG, PNG, JPG, GIF - max 2MB)</div>
      <div id="flag-preview-container" style="display: none;">
        <strong>New Flag Preview:</strong>
        <img id="flag-preview" class="flag-preview" alt="Flag Preview">
      </div>
    </div>

    <!-- Country About -->
    <div class="mb-3">
      <label for="country_about" class="form-label">Country Description</label>
      <textarea class="form-control @error('country_about') is-invalid @enderror" 
                id="country_about" 
                name="country_about" 
                rows="4" 
                placeholder="Brief description about the country...">{{ old('country_about', $country->country_about) }}</textarea>
      @error('country_about')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <div class="help-text">Optional description about the country</div>
    </div>

    <!-- Country Stats -->
    <div class="row mb-3">
      <div class="col-md-6">
        <div class="card bg-light">
          <div class="card-body text-center">
            <h5 class="card-title">{{ $country->states_count ?? 0 }}</h5>
            <p class="card-text">States</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card bg-light">
          <div class="card-body text-center">
            <h5 class="card-title">{{ $country->cities_count ?? 0 }}</h5>
            <p class="card-text">Cities</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Form Actions -->
    <div class="d-flex justify-content-between">
      <a href="{{ route('admin.countries') }}" class="btn btn-secondary">
        <i class="bi bi-x-circle"></i> Cancel
      </a>
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-circle"></i> Update Country
      </button>
    </div>
  </form>

</div>

<!-- jQuery & Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

<script>
$(document).ready(function() {
  // Auto-uppercase country code
  $('#country_code').on('input', function() {
    this.value = this.value.toUpperCase();
  });

  // Flag preview
  $('#country_flag').on('change', function() {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        $('#flag-preview').attr('src', e.target.result);
        $('#flag-preview-container').show();
      };
      reader.readAsDataURL(file);
    } else {
      $('#flag-preview-container').hide();
    }
  });

  // Form validation
  $('form').on('submit', function() {
    const countryName = $('#country_name').val().trim();
    const countryCode = $('#country_code').val().trim();
    
    if (!countryName) {
      alert('Please enter a country name');
      return false;
    }
    
    if (!countryCode) {
      alert('Please enter a country code');
      return false;
    }
    
    if (countryCode.length !== 3) {
      alert('Country code must be exactly 3 characters');
      return false;
    }
  });
});
</script>

</body>
</html> 