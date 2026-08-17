<?php $selectedValue=0;?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

<style>
    /* PREMIUM GENERAL CONTAINER */
    .page-tab {
        border: none !important;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05) !important;
        border-radius: 20px !important;
        padding: 35px !important;
        background: #ffffff !important;
        font-family: 'Outfit', 'Inter', sans-serif !important;
    }

    /* PREMIUM STEPS PROGRESS BAR */
    .step-tabs {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        position: relative !important;
        margin-bottom: 50px !important;
        padding: 0 20px !important;
        background: none !important;
    }
    .step-tabs::before {
        content: '';
        position: absolute;
        top: 25px;
        left: 30px;
        right: 30px;
        height: 4px;
        background: #f1f5f9;
        z-index: 1;
        transform: translateY(-50%);
        border-radius: 2px;
    }
    .step-tab {
        position: relative !important;
        z-index: 2 !important;
        background: #fff !important;
        border: 2px solid #cbd5e1 !important;
        border-radius: 50% !important;
        width: 50px !important;
        height: 50px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 16px !important;
        color: #64748b !important;
        cursor: pointer !important;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
        padding: 0 !important;
    }
    .step-tab::after {
        content: attr(data-title) !important;
        position: absolute !important;
        top: 60px !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        white-space: nowrap !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #64748b !important;
        transition: color 0.4s ease !important;
    }
    .step-tab.active {
        border-color: #ff4d4d !important;
        background: linear-gradient(135deg, #ff4d4d, #f857a6) !important;
        color: #fff !important;
        box-shadow: 0 10px 15px -3px rgba(255, 77, 77, 0.3) !important;
    }
    .step-tab.active::after {
        color: #ff4d4d !important;
        font-weight: 700 !important;
    }
    .step-tab.completed {
        border-color: #10b981 !important;
        background: #10b981 !important;
        color: #fff !important;
    }
    .step-tab.completed::after {
        color: #10b981 !important;
    }

    /* PREMIUM FORM ELEMENTS */
    #product-form label.form-label,
    .modal-body label.form-label {
        font-weight: 600 !important;
        color: #475569 !important;
        font-size: 14px !important;
        margin-bottom: 8px !important;
        display: block !important;
    }

    #product-form .form-control, 
    #product-form .form-select,
    .modal-body .form-control,
    .modal-body .form-select {
        background-color: #fff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 12px !important;
        height: 48px !important;
        padding: 10px 18px !important;
        color: #1e293b !important;
        font-size: 15px !important;
        transition: all 0.3s ease !important;
        box-shadow: none !important;
        display: block !important;
        width: 100% !important;
    }

    #product-form textarea.form-control {
        height: auto !important;
        min-height: 120px !important;
    }

    #product-form .form-control:focus, 
    #product-form .form-select:focus,
    .modal-body .form-control:focus,
    .modal-body .form-select:focus {
        border-color: #ff4d4d !important;
        box-shadow: 0 0 0 3px rgba(255, 77, 77, 0.15) !important;
        background-color: #fff !important;
    }

    /* SELECT2 GLOBAL STYLING */
    .select2 {
        width: 100% !important;
    }
    .select2-container {
        width: 100% !important;
        overflow: visible !important;
        z-index: 1 !important; /* Override global own.css z-index: 9999 to allow modals to stack on top */
    }
    .select2-container--open {
        z-index: 100000 !important;
    }

    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--multiple {
        background-color: #fff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 12px !important;
        min-height: 48px !important;
        display: flex !important;
        align-items: center !important;
        padding-left: 6px !important;
        box-shadow: none !important;
        transition: all 0.3s ease !important;
        width: 100% !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--focus .select2-selection--multiple,
    .select2-container--default.select2-container--open .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--multiple {
        border-color: #ff4d4d !important;
        box-shadow: 0 0 0 3px rgba(255, 77, 77, 0.15) !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1e293b !important;
        font-size: 15px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px !important;
        right: 12px !important;
    }

    /* Force hide Select2 raw inputs */
    .select2-hidden-accessible {
        border: 0 !important;
        clip: rect(0 0 0 0) !important;
        -webkit-clip-path: inset(50%) !important;
        clip-path: inset(50%) !important;
        height: 1px !important;
        overflow: hidden !important;
        padding: 0 !important;
        position: absolute !important;
        width: 1px !important;
        white-space: nowrap !important;
        display: none !important;
    }

    /* Anchor relative to the input field's group */
    #product-form .mb-3 {
        position: relative !important;
    }

    /* Select2 Dropdown list styling */
    .select2-dropdown {
        background-color: #ffffff !important;
        border: 1.5px solid #ff4d4d !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
        z-index: 100000 !important;
        overflow: hidden !important;
    }

    /* Force downward opening and full width on main page form fields */
    #product-form .select2-container--open .select2-dropdown {
        position: absolute !important;
        top: 100% !important;
        bottom: auto !important;
        left: 0 !important;
        width: 100% !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #ff4d4d !important;
        color: white !important;
    }
    .select2-container--default .select2-results__option {
        padding: 10px 15px !important;
        font-size: 14px !important;
        color: #334155 !important;
    }
    .select2-results__options {
        max-height: 250px !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch !important;
    }

    /* MODAL AND BACKDROP FIXES */
    .modal { 
        z-index: 1060 !important; 
    }
    .modal-backdrop { 
        z-index: 1050 !important; 
    }
    .modal:nth-of-type(even) { 
        z-index: 1062 !important; 
    }
    .modal-backdrop.show:nth-of-type(even) { 
        z-index: 1061 !important; 
    }

    .modal-content {
        border: none !important;
        border-radius: 20px !important;
        box-shadow: 0 25px 60px rgba(0,0,0,0.2) !important;
        overflow: hidden !important;
    }
    .modal-header {
        background: linear-gradient(135deg, #ff4d4d, #f857a6) !important;
        color: #fff !important;
        padding: 20px 25px !important;
    }
    .modal-header .modal-title {
        font-weight: 700 !important;
        font-size: 18px !important;
        color: #fff !important;
    }
    .modal-header .btn-close {
        filter: brightness(0) invert(1) !important;
        opacity: 0.8 !important;
        background-color: transparent !important;
    }
    .modal-body {
        padding: 25px !important;
        background: #fff !important;
    }

    /* ADD LINK BUTTONS */
    .add-link-btn {
        display: inline-flex !important;
        align-items: center !important;
        background: #fff0f0 !important;
        color: #ff4d4d !important;
        padding: 6px 14px !important;
        border-radius: 8px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        margin-top: 8px !important;
        transition: all 0.2s ease !important;
        border: 1px solid rgba(255, 77, 77, 0.1) !important;
        cursor: pointer !important;
    }
    .add-link-btn:hover {
        background: #ff4d4d !important;
        color: #fff !important;
        transform: translateY(-1px) !important;
    }
    .add-link-btn i {
        margin-right: 4px !important;
    }

    /* ACTION BUTTONS */
    #product-form .btn-primary,
    .modal-body .btn-primary {
        background: linear-gradient(135deg, #ff4d4d, #f857a6) !important;
        border: none !important;
        height: 48px !important;
        padding: 0 28px !important;
        border-radius: 12px !important;
        font-weight: 700 !important;
        font-size: 15px !important;
        color: #fff !important;
        box-shadow: 0 6px 20px rgba(255, 77, 77, 0.25) !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.3s ease !important;
        cursor: pointer !important;
    }
    #product-form .btn-primary:hover,
    .modal-body .btn-primary:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 10px 25px rgba(255, 77, 77, 0.35) !important;
    }
    #product-form .btn-secondary,
    .modal-body .btn-secondary {
        background: #f1f5f9 !important;
        border: none !important;
        color: #475569 !important;
        height: 48px !important;
        padding: 0 24px !important;
        border-radius: 12px !important;
        font-weight: 600 !important;
        font-size: 15px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.3s ease !important;
    }
    #product-form .btn-secondary:hover,
    .modal-body .btn-secondary:hover {
        background: #e2e8f0 !important;
        color: #1e293b !important;
    }

    /* Product Name Suggestion Box Styles */
    #suggestion-box {
        position: relative;
        width: 100%;
        z-index: 1000;
    }
    .suggestion-card {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        margin-top: 5px;
        background: #ffffff;
        border: 1px solid #eef0f2;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        z-index: 100000 !important;
        overflow: hidden;
    }
    .suggestion-header {
        padding: 10px 16px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #8c98a5;
        letter-spacing: 0.8px;
        border-bottom: 1px solid #f8f9fa;
        background: #fafbfc;
    }
    .suggestion-list {
        max-height: 250px;
        overflow-y: auto;
        padding: 0;
        margin: 0;
        list-style: none;
    }
    .suggestion-item {
        padding: 12px 16px;
        cursor: pointer;
        font-size: 14px;
        color: #333333;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #f8f9fa;
    }
    .suggestion-item:last-child {
        border-bottom: none;
    }
    .suggestion-item i {
        font-size: 14px;
        color: #ff4b4b;
        margin-right: 10px;
    }
    .suggestion-item:hover {
        background: #fdf5f5;
        color: #ff4b4b;
        padding-left: 20px;
    }
    .suggestion-item strong {
        color: #2b303a;
        font-weight: 700;
    }
    .suggestion-item:hover strong {
        color: #ff4b4b;
    }
    .suggestion-empty {
        padding: 16px;
        font-size: 14px;
        color: #8c98a5;
        text-align: center;
        background: #ffffff;
    }

    /* Circular Progress Bar Styles */
    .circular-progress {
        position: relative;
        width: 60px;
        height: 60px;
        display: inline-block;
    }
    .circular-progress svg {
        transform: rotate(-90deg);
        width: 60px;
        height: 60px;
    }
    .circular-progress circle {
        fill: none;
        stroke-width: 5;
        stroke-linecap: round;
    }
    .circular-progress circle.bg {
        stroke: #e9ecef;
    }
    .circular-progress circle.fg {
        stroke: #198754;
        stroke-dasharray: 164;
        stroke-dashoffset: 164;
        transition: stroke-dashoffset 0.2s ease-out;
    }
    .circular-progress .progress-info {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-weight: bold;
        color: #198754;
        transition: all 0.3s ease;
    }
    .circular-progress .progress-info .percent {
        font-size: 11px;
    }
    .circular-progress.success circle.bg {
        stroke: #198754;
        opacity: 0.2;
    }
    .circular-progress.success .progress-info {
        background: #198754;
        color: #fff;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .circular-progress.success .progress-info .percent {
        font-size: 18px;
    }
    .circular-progress.error .progress-info {
        color: #dc3545;
    }
    .circular-progress.error .fg {
        stroke: #dc3545;
    }

    /* Progressive tweaks */
    @media (max-width: 576px) {
        .step-tabs {
            padding: 0 !important;
            margin-bottom: 30px !important;
        }
        .step-tab {
            width: 42px !important;
            height: 42px !important;
            font-size: 14px !important;
        }
        .step-tab::after {
            display: none !important; /* Hide labels on mobile to prevent overlapping */
        }
        .step-tabs::before {
            top: 21px !important;
            left: 10px !important;
            right: 10px !important;
        }
        .page-tab {
            padding: 20px !important;
        }
    }
</style>

<div class="page-content">
  <div class="page-tab bg-white border rounded p-4 shadow-sm">
    <form action="{{ route('product.store', [], false) }}" method="POST" enctype="multipart/form-data" id="product-form">
      @csrf

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- ============= STEP TABS ============= --}}
      <div class="step-tabs">
          <div class="step-tab active" data-step="1" data-title="Basic Info"><i class="fas fa-info-circle"></i></div>
          <div class="step-tab" data-step="2" data-title="Pricing"><i class="fas fa-tag"></i></div>
          <div class="step-tab" data-step="3" data-title="Details"><i class="fas fa-file-alt"></i></div>
      </div>

      {{-- ================= STEP 1 ================= --}}
      <div class="step-section active" data-step="1">
        {{-- Product Name --}}
        <div class="mb-3">
          <label class="form-label">{{ get_phrase('Product Name') }}</label>
          <div class="position-relative">
              <input type="text" name="title" id="product_title" class="form-control"
                     placeholder="Enter Product/Service name here Ex: Iphone 16 pro"
                     onkeyup="getProductSuggestions()">
              <div id="product-loader" class="spinner-border spinner-border-sm text-danger position-absolute" style="display:none; right: 15px; top: 14px; z-index: 10;"></div>
              <div id="suggestion-box"></div>
          </div>
        </div>

        {{-- Product Type --}}
        <div class="mb-3">
          <label class="form-label">{{ get_phrase('Please choose your Product Type*') }}</label>
          <select name="producttype" class="form-select selectpicker">
            <option value="0" selected>{{ get_phrase('select') }}</option>
            <option value="Physical">{{ get_phrase('Physical') }}</option>
            <option value="Affiliate">{{ get_phrase('Affiliate') }}</option>
          </select>
        </div>

        {{-- Product Nature Type --}}
        <div class="mb-3">
          <label class="form-label">{{ get_phrase('Please choose your Product Nature Type*') }}</label>
          <select name="productnaturetype" class="form-select selectpicker" data-placeholder="Type Nature Product">
            <option value="0" selected>{{ get_phrase('select') }}</option>
            <option value="Service">{{ get_phrase('Service') }}</option>
            <option value="Product">{{ get_phrase('Product') }}</option>
          </select>
        </div>

        {{-- Parent Category --}}
        <div class="mb-3">
          <label class="form-label">{{ get_phrase('Parent Category') }}</label>
          <select name="parent" id="parent" class="form-select" data-container="body">
            <option value="0">Select Parent Category</option>
            @foreach($parent as $key => $printable_category)
              <option value="{{ $printable_category->id }}">{{ $printable_category->product_category_name }}</option>
            @endforeach
          </select>
          <a class="add-link-btn" onclick="showparentcategorymodel();">
            <i class="fas fa-plus"></i> Add Parent category
          </a>
        </div>

        {{-- Tags / Keywords --}}
        <div class="mb-3">
          <label class="form-label">{{ get_phrase('Product Tags/keyword') }}</label>
          <select name="category[]" id="category" class="form-select" multiple></select>
          <a class="add-link-btn" onclick="showcategorymodel();">
            <i class="fas fa-plus"></i> Add Tag / Sub-category
          </a>
        </div>

        {{-- Brand --}}
        <div class="mb-3">
          <label class="form-label">{{ get_phrase('Brand') }}</label>
          <select name="brand" id="brand" class="form-select selectpicker" data-live-search="true" data-container="body">
            <option value="" disabled selected>{{ get_phrase('Select Brand') }}</option>
            @foreach (\App\Models\Brand::all() as $brand)
              <option value="{{ $brand->id }}">{{ ucfirst($brand->name) }}</option>
            @endforeach
          </select>
          <a class="add-link-btn" onclick="showbrandmodel();">
            <i class="fas fa-plus"></i> Add Brand
          </a>
        </div>

        {{-- Currency --}}
        <div class="mb-3">
          <label class="form-label">{{ get_phrase('Currency') }}</label>
          <select name="currency" id="currency" class="form-select border-0 bg-secondary">
            <option value="">{{ get_phrase('Select Currency') }}</option>
            @foreach (\App\Models\Currency::all() as $currency)
              <option value="{{ $currency->id }}" {{ 47 == $currency->id ? "selected" : "" }}>{{ $currency->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- Link Product with Page --}}
        <div class="mb-3">
          <label class="form-label">{{ get_phrase('Link Product with Page') }}</label>
          <select name="List" id="List" class="form-select border-0 bg-secondary">
            <option value="">{{ get_phrase('Select') }}</option>
          </select>
        </div>

        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-primary btn-next-step" data-next="2">
            Save & Continue →
          </button>
        </div>
      </div>

      {{-- ================= STEP 2 ================= --}}
      <div class="step-section" data-step="2">
        {{-- Price & Selling Price + Auto Discount --}}
        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label">{{ get_phrase('Original Price') }}</label>
            <input type="number" name="price" class="form-control" placeholder="Your Price">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">{{ get_phrase('Selling Price') }}</label>
            <input type="number" name="selling_price" class="form-control" placeholder="Your Selling Price">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">% Discount (auto)</label>
            <input type="text" id="discount_display" class="form-control" placeholder="Auto calculated" readonly>
          </div>
        </div>

        <div class="d-flex justify-content-between mt-3">
          <button type="button" class="btn btn-secondary btn-prev-step" data-prev="1">← Back</button>
          <button type="button" class="btn btn-primary btn-next-step" data-next="3">
            Save & Continue →
          </button>
        </div>
      </div>

      {{-- ================= STEP 3 ================= --}}
      <div class="step-section" data-step="3">
        {{-- Video + Featured (second col kept empty as in original) --}}
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ get_phrase('Video Url') }}</label>
            <input type="text" name="video_url" class="form-control" placeholder="Video URL">
          </div>
          <div class="col-md-6 mb-3">
            {{-- Featured Product-Service (kept commented as original) --}}
            {{-- <label class="form-label">{{ get_phrase('Featured Product-Service') }}</label>
            <select name="featured" class="form-select">
              <option value="0">{{ get_phrase('select') }}</option>
              <option value="Yes">{{ get_phrase('Yes') }}</option>
              <option value="No" selected>{{ get_phrase('No') }}</option>
            </select> --}}
          </div>
        </div>

        {{-- Start + End Dates --}}
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ get_phrase('Start Date') }}</label>
            <input type="date" name="start_date" class="form-control">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ get_phrase('End Date') }}</label>
            <input type="date" name="end_date" class="form-control">
          </div>
        </div>

        {{-- Buy Link --}}
        <div class="mb-3">
          <label class="form-label">{{ get_phrase('Enquire link') }}</label>
          <input type="url" name="buy_link" class="form-control" placeholder="{{ get_phrase('Enter the enquire link') }}">
        </div>

        {{-- Description --}}
        <div class="mb-3">
          <label class="form-label">{{ get_phrase('Description') }}</label>
          <textarea name="description" id="description" class="form-control" rows="6" placeholder="Your Description"></textarea>
        </div>

        {{-- Product Image --}}
        <div class="mb-3">
          <label class="form-label">{{ get_phrase('Product Image') }}</label>
          <input type="file" multiple id="image" name="multiple_files[]" class="form-control" onchange="$('#uploadProgressImage').removeClass('d-none');">
          <div id="uploadProgressImage" class="uploadProgress d-none mt-3">
              <div class="bg-light p-2 rounded border shadow-sm">
                  <div class="d-flex align-items-center">
                      <div class="circular-progress me-2" style="transform: scale(0.6); transform-origin: left center;">
                          <svg width="60" height="60">
                              <circle class="bg" cx="30" cy="30" r="26"></circle>
                              <circle class="fg" cx="30" cy="30" r="26"></circle>
                          </svg>
                          <div class="progress-info">
                              <span class="percent">0%</span>
                          </div>
                      </div>
                      <div class="progress-details flex-grow-1">
                          <div class="fw-bold text-success small mb-0">
                          <span class="percent-label">0%</span> {{get_phrase('completed')}}
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        </div>

        <p>Featured Video</p>
        <div class="mb-3">
            <label for="#">Video</label>
            <input type="file" name="featured_video" class="border-0 bg-secondary form-control" id="featured_video" accept="video/*" onchange="$('#uploadProgressVideo').removeClass('d-none');">
            <div id="uploadProgressVideo" class="uploadProgress d-none mt-3">
              <div class="bg-light p-2 rounded border shadow-sm">
                  <div class="d-flex align-items-center">
                      <div class="circular-progress me-2" style="transform: scale(0.6); transform-origin: left center;">
                          <svg width="60" height="60">
                              <circle class="bg" cx="30" cy="30" r="26"></circle>
                              <circle class="fg" cx="30" cy="30" r="26"></circle>
                          </svg>
                          <div class="progress-info">
                              <span class="percent">0%</span>
                          </div>
                      </div>
                      <div class="progress-details flex-grow-1">
                          <div class="fw-bold text-success small mb-0">
                          <span class="percent-label">0%</span> {{get_phrase('completed')}}
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        </div>

        {{-- Terms & Conditions --}}
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
          <label class="form-check-label" for="terms">
            I agree to the
            <a onclick="showtermsymodel()" data-toggle="modal" data-target="#termsModal"
               class="text-primary fw-semibold text-decoration-none">
              Terms and Conditions
            </a>
          </label>
        </div>

        <div class="d-flex justify-content-between mt-3">
          <button type="button" class="btn btn-secondary btn-prev-step" data-prev="2">← Back</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </form>
  </div>
</div>

@include('frontend.initialize')

{{-- ================= MODALS ================= --}}

<!-- parentcategoriesModal -->
<div class="modal fade" id="parentcategoriesModal" tabindex="-1" role="dialog" aria-labelledby="parentcategoriesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg bg-white" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="parentcategoriesModalLabel">{{ get_phrase('Add Category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label for="parent_category_name" class="form-label fw-semibold">Category Name</label>
                    <input id="parent_category_name" type="text" class="form-control bg-secondary border-0 px-3" style="height: 45px; border-radius: 8px;" name="parent_category_name" placeholder="Enter category name" autofocus>
                </div>
                <div class="d-grid mt-4">
                    <button type="button" class="btn btn-primary fw-bold" style="height: 45px; border-radius: 8px;" onclick="submitparentcategory();">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- categoriesModal -->
<div class="modal fade" id="categoriesModal" tabindex="-1" role="dialog" aria-labelledby="categoriesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg bg-white" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="categoriesModalLabel">{{ get_phrase('Add Category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label for="category_name_modal" class="form-label fw-semibold">Sub-Category Name</label>
                    <input id="category_name_modal" type="text" class="form-control bg-secondary border-0 px-3" style="height: 45px; border-radius: 8px;" name="category_name" placeholder="Enter sub-category name" autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="category_parent_id">Select Parent</label>
                    <select class="form-select bg-secondary border-0" style="height: 45px; border-radius: 8px;" name="category_parent_id" id="category_parent_id">
                        <option value="0">Add Category</option>
                        @foreach($parent as $key => $printable_category)
                            <option value="{{ $printable_category->id }}" {{ ($printable_category->id == $selectedValue) ? 'selected' : '' }}>
                                {{ $printable_category->product_category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-grid mt-4">
                    <button type="button" class="btn btn-primary fw-bold" style="height: 45px; border-radius: 8px;" onclick="submitcategory();">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- brandModal -->
<div class="modal fade" id="brandModal" tabindex="-1" role="dialog" aria-labelledby="brandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="brandModalLabel">{{ get_phrase('Add Brand') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label for="brandname" class="form-label fw-semibold">Brand Name</label>
                    <input id="brandname" type="text" class="form-control bg-secondary border-0 px-3" style="height: 45px; border-radius: 8px;" name="brandname" placeholder="Enter brand name">
                </div>
                <div class="d-grid mt-4">
                    <button type="button" class="btn btn-primary fw-bold" style="height: 45px; border-radius: 8px;" onclick="submitbrand();">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsAndConditionsModal" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg bg-white" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="rounded border p-1 bg-light">
                    <iframe src="{{ route('term.view', ['noimage' => 1], false) }}" width="100%" height="450px" style="border:none; border-radius: 8px;" scrolling="yes"></iframe>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary fw-bold px-4" style="border-radius: 8px;" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // For small selectpickers (product type and product nature type), disable search and set dropdownParent
    $('select[name="producttype"], select[name="productnaturetype"]').each(function() {
        $(this).select2({
            minimumResultsForSearch: Infinity,
            dropdownParent: $(this).parent()
        });
    });

    // For brand selectpicker, keep search and set dropdownParent
    $('select[name="brand"]').each(function() {
        $(this).select2({
            dropdownParent: $(this).parent()
        });
    });

    $('#category_parent_id').select2({
        dropdownParent: $('#categoriesModal')
    });

    $('#List').select2({
        dropdownParent: $('#List').parent(),
        placeholder: "Search for Pages",
        ajax: {
            url: '/search-pages',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return { id: item.id, text: item.title };
                    })
                };
            },
            cache: true
        }
    });

    $('#category').select2({
        dropdownParent: $('#category').parent(),
        tags: true,
        placeholder: 'Type Category',
        multiple: true,
        tokenSeparators: [','],
        ajax: {
            url: '/product-categories-autocomplete-ajax',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { 
                    q: params.term,
                    parent_id: $('#parent').val()
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return { text: item.product_category_name, id: item.id };
                    })
                };
            },
            cache: true
        },
        createTag: function (params) {
            return {
                id: params.term,
                text: params.term,
                newOption: true
            };
        },
        templateResult: function (data) {
            var $result = $("<span></span>");
            $result.text(data.text);
            if (data.newOption) {
                $result.append(" <em>(new)</em>");
            }
            return $result;
        }
    });

    $('#category').on('select2:select', function (e) {
        var data = e.params.data;

        if (data.newOption) {
            $.ajax({
                type: "POST",
                url: "/product-categories-create-from-select2",
                data: {
                    _token: '{{ csrf_token() }}',
                    category_name: data.text
                },
                success: function (response) {
                    var selectedValues = $('#category').val().filter(function(val) {
                        return val !== data.id;
                    });

                    $('#category').val(selectedValues).trigger('change');

                    var newOption = new Option(response.product_category_name, response.id, true, true);
                    $('#category').append(newOption).trigger('change');
                }
            });
        }
    });

    // Make parent category a fast local Select2 search (no AJAX)
    $('#parent').select2({
        dropdownParent: $('#parent').parent(),
        placeholder: 'Select Parent Category',
        allowClear: true
    });

    $('#parent').on('change', function () {
        let selectedValue = $(this).val();
        
        // Sync with the add-category modal select
        if ($('#category_parent_id').val() !== selectedValue) {
            $('#category_parent_id').val(selectedValue).trigger('change.select2');
        }

        // Clear subcategories since parent category has changed
        $('#category').val(null).trigger('change');
    });

    /* -------- MULTI STEP NAVIGATION -------- */
    function goToStep(step) {
        $('.step-section').removeClass('active');
        $('.step-section[data-step="'+step+'"]').addClass('active');

        // Update step tabs UI
        $('.step-tab').each(function() {
            let tabStep = parseInt($(this).attr('data-step'));
            if (tabStep < step) {
                $(this).addClass('completed').removeClass('active');
            } else if (tabStep === step) {
                $(this).addClass('active').removeClass('completed');
            } else {
                $(this).removeClass('active completed');
            }
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    $('.step-tab').on('click', function(){
        goToStep($(this).data('step'));
    });

    $('.btn-next-step').on('click', function(){
        goToStep($(this).data('next'));
    });

    $('.btn-prev-step').on('click', function(){
        goToStep($(this).data('prev'));
    });

    /* -------- AUTO DISCOUNT CALCULATION -------- */
    function updateDiscount() {
        const original = parseFloat($('input[name="price"]').val());
        const selling  = parseFloat($('input[name="selling_price"]').val());
        const $field   = $('#discount_display');

        if (!isNaN(original) && original > 0 && !isNaN(selling)) {
            const discount = ((original - selling) / original) * 100;
            $field.val(discount.toFixed(2) + '%');
        } else {
            $field.val('');
        }
    }

    $('input[name="price"], input[name="selling_price"]').on('input', updateDiscount);

    /* -------- AJAX SUBMISSION WITH PROGRESS -------- */
    $('#product-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const url = form.attr('action');
        const formData = new FormData(this);
        
        const hasImage = $('#image')[0] && $('#image')[0].files.length > 0;
        const hasVideo = $('#featured_video')[0] && $('#featured_video')[0].files.length > 0;

        // Reset
        $('.uploadProgress').addClass('d-none')
            .find('.fg').css('stroke-dashoffset', '164').end()
            .find('.percent, .percent-label').text('0%').end()
            .find('.circular-progress').removeClass('success error');

        if (hasImage) $('#uploadProgressImage').removeClass('d-none');
        if (hasVideo) $('#uploadProgressVideo').removeClass('d-none');

        const $btn = form.find('button[type="submit"]');
        $btn.prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(evt) {
                    if (!evt.lengthComputable) return;
                    const percent = Math.round((evt.loaded / evt.total) * 100);
                    const offset = 164 - (164 * percent / 100);

                    // Update visible progress boxes
                    $('.uploadProgress:not(.d-none)').each(function () {
                        $(this).find('.fg').css('stroke-dashoffset', offset);
                        $(this).find('.percent, .percent-label').text(percent + '%');
                    });
                }, false);
                return xhr;
            },
            success: function(response) {
                $('.uploadProgress:not(.d-none)').each(function () {
                    $(this).find('.circular-progress').addClass('success');
                    $(this).find('.fg').css('stroke-dashoffset', '0');
                    $(this).find('.percent').text('✓');
                    $(this).find('.percent-label').text('100%');
                });

                Swal.fire({
                    icon: 'success',
                    title: 'Product Created Successfully!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    if (response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else if (response.status === 'success') {
                        window.location.reload();
                    } else {
                        window.location.href = "{{ route('userproduct', [], false) }}";
                    }
                });
            },
            error: function(xhr) {
                $('.uploadProgress:not(.d-none) .circular-progress').addClass('error');
                $('.uploadProgress:not(.d-none) .percent').text('✕');

                let errorMsg = 'An error occurred during upload.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>'); // Changed to <br> instead of \n for Swal.fire HTML
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }

                Swal.fire({ icon: 'error', title: 'Upload Failed', html: errorMsg });
                $btn.prop('disabled', false);
            }
        });
    });
});

/* ---------- Rest of your JS functions (unchanged) ---------- */

function showtermsymodel(){
    $('#termsAndConditionsModal' ).modal('show');
}
function showbrandmodel(){
    $('#brandModal' ).modal('show');
}
function showcategorymodel(){
    $('#categoriesModal' ).modal('show');
}
function showparentcategorymodel(){
    $('#parentcategoriesModal' ).modal('show');
}

function submitparentcategory(){
    var category_name = $('#parent_category_name').val();
    if (category_name != "") {
        const $btn = $(event.target);
        $btn.prop('disabled', true);
        var ajax_url = "{{route('ajax.storeproductcategories.parent', [], false)}}";
        $.ajax({
            url: ajax_url,
            method: 'POST',
            data: {
                category_name: category_name,
                category_parent_id: null,
                _token: '{{csrf_token()}}'
            },
            success: function(result) {
                if (result > 0) {
                    var fetch_url = "{{route('page.json.parent.product.catgories', [], false)}}";
                    $.ajax({
                        url: fetch_url,
                        method: 'get',
                        success: function(fetch_result) {
                            $('#parent').html("<option value='0'>Select Parent Category</option>");
                            $.each((typeof fetch_result === 'string' ? JSON.parse(fetch_result) : fetch_result), function(key, value) {
                                $('#parent').append('<option value="' + value.id + '">' + value.product_category_name + '</option>');
                            });
                            $('#parent').trigger('change');
                        }
                    });
                    $('#parentcategoriesModal').modal('hide');
                    $('#parent_category_name').val('');
                    Swal.fire({ icon: "success", title: "Success", text: "Parent Category added successfully!" });
                } else {
                    Swal.fire({ icon: "error", title: "Oops...", text: "Category already exists!" });
                }
            },
            error: function(xhr) {
                let errorMsg = 'Something went wrong. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({ icon: "error", title: "Oops...", html: errorMsg });
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
        });
    } else {
        Swal.fire({ icon: "error", title: "Oops...", text: "Please enter category name!" });
    }
}

function submitcategory() {
    var category_name = $('#category_name_modal').val();
    var category_parent_id = $('#category_parent_id').val();
    if (category_name != "") {
        const $btn = $(event.target);
        $btn.prop('disabled', true);
        var ajax_url = "{{route('ajax.storeproductcategories', [], false)}}";
        $.ajax({
            url: ajax_url,
            method: 'POST',
            data: {
                category_name: category_name,
                category_parent_id: category_parent_id,
                _token: '{{csrf_token()}}'
            },
            success: function(result) {
                if (result > 0) {
                    // Update tag list if needed or provide success feedback
                    $('#categoriesModal').modal('hide');
                    $('#category_name_modal').val('');
                    Swal.fire({ icon: "success", title: "Success", text: "Category added successfully!" });
                } else {
                    Swal.fire({ icon: "error", title: "Oops...", text: "Category already exists!" });
                }
            },
            error: function(xhr) {
                let errorMsg = 'Something went wrong. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({ icon: "error", title: "Oops...", html: errorMsg });
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
        });
    } else {
        Swal.fire({ icon: "error", title: "Oops...", text: "Please enter category name!" });
    }
}

function submitbrand() {
    var brandname = $('#brandname').val();
    if (brandname != "") {
        const $btn = $(event.target);
        $btn.prop('disabled', true);
        var ajax_url = "{{route('ajax.store.brand', [], false)}}";
        $.ajax({
            url: ajax_url,
            method: 'POST',
            data: {
                brandname: brandname,
                _token: '{{csrf_token()}}'
            },
            success: function(result) {
                if (result > 0) {
                    var brand_ajax_url = "{{route('product.json.brand', [], false)}}";
                    $.ajax({
                        url: brand_ajax_url,
                        method: 'get',
                        success: function(brand_result) {
                            $('#brand').html("<option value=''>Select Brand</option>");
                            $.each((typeof brand_result === 'string' ? JSON.parse(brand_result) : brand_result), function(key, value) {
                                $('#brand').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                    $('#brandModal').modal('hide');
                    $('#brandname').val('');
                    Swal.fire({ icon: "success", title: "Success", text: "Brand added successfully!" });
                } else {
                    Swal.fire({ icon: "error", title: "Oops...", text: "Brand already exists!" });
                }
            },
            error: function(xhr) {
                let errorMsg = 'Something went wrong. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({ icon: "error", title: "Oops...", html: errorMsg });
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
        });
    } else {
        Swal.fire({ icon: "error", title: "Oops...", text: "Please enter brand name!" });
    }
}

let suggestionTimeout = null;
let currentSuggestionRequest = null;

function getProductSuggestions() {
    clearTimeout(suggestionTimeout);
    let query = $('#product_title').val().trim();
    
    if (query.length > 1) {
        // Show the loader immediately when searching
        $('#product-loader').show();
        
        suggestionTimeout = setTimeout(function() {
            // Abort previous active AJAX request to prevent race conditions
            if (currentSuggestionRequest) {
                let tempReq = currentSuggestionRequest;
                currentSuggestionRequest = null;
                tempReq.abort();
            }
            
            currentSuggestionRequest = $.ajax({
                url: "{{ route('product.suggestions', [], false) }}",
                method: "GET",
                data: { query: query },
                success: function (data) {
                    // Check if current value in the input field still matches the query
                    let currentQuery = $('#product_title').val().trim();
                    if (currentQuery !== query) {
                        return; // Discard stale response
                    }
                    
                    let suggestions = '<div class="suggestion-card">';
                    suggestions += '<div class="suggestion-header">Suggestions</div><ul class="suggestion-list">';
                    if (data.length > 0) {
                        data.forEach(product => {
                            let safeTitle = product.title.replace(/'/g, "\\'");
                            // Bold matching query text
                            let highlightedTitle = product.title.replace(new RegExp("(" + query.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&') + ")", "gi"), "<strong>$1</strong>");
                            suggestions += `<li class="suggestion-item" onclick="fillProductDetails(${product.id}, '${safeTitle}')">
                                <i class="fas fa-tag"></i> ${highlightedTitle}
                            </li>`;
                        });
                    }
                    let safeQuery = query.replace(/'/g, "\\'");
                    suggestions += `<li class="suggestion-item border-top text-success" onclick="addCustomProduct('${safeQuery}')" style="background: #fafdfb; border-top: 1px solid #eef0f2 !important;">
                        <i class="fas fa-plus-circle text-success"></i> Can't find it? Add &nbsp;<strong>${query}</strong>&nbsp; as a custom product
                    </li>`;
                    suggestions += '</ul></div>';
                    $('#suggestion-box').html(suggestions).show();
                },
                complete: function (xhr) {
                    // Hide loader only if this was the active request that just finished
                    if (currentSuggestionRequest === xhr) {
                        currentSuggestionRequest = null;
                        $('#product-loader').hide();
                    }
                }
            });
        }, 150); // Shorter 150ms debounce for snappier feedback
    } else {
        if (currentSuggestionRequest) {
            currentSuggestionRequest.abort();
            currentSuggestionRequest = null;
        }
        $('#suggestion-box').hide().html('');
        $('#product-loader').hide();
    }
}

function addCustomProduct(title) {
    $('#product_title').val(title);
    $('#suggestion-box').hide().html('');
    $('#product-loader').hide();
    $('select[name="producttype"]').focus();
}

function fillProductDetails(productId, productTitle) {
    $('#product_title').val(productTitle);
    $('#suggestion-box').hide().html('');
    $('#product-loader').hide();
    resetFormFields();

    Swal.fire({
        title: 'Loading product details...',
        html: 'Please wait while we load the template information.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: "{{ route('product.details', [], false) }}",
        method: "GET",
        data: { id: productId },
        success: function (data) {
            Swal.close();
            
            $('input[name="title"]').val(data.title);
            $('select[name="producttype"]').val(data.product_type).trigger('change');
            $('select[name="productnaturetype"]').val(data.product_nature_type).trigger('change');
            $('select[name="parent"]').val(data.parent_id);
            $('select[name="currency"]').val(data.currency_id).trigger('change');
            $('input[name="price"]').val(data.product_original_price);
            $('input[name="selling_price"]').val(data.product_selling_price);
            $('input[name="video_url"]').val(data.video_url);
            $('select[name="featured"]').val(data.product_featured_service).trigger('change');
            $('input[name="location"]').val(data.location);
            $('select[name="condition"]').val(data.condition).trigger('change');
            $('select[name="status"]').val(data.status).trigger('change');
            $('input[name="buy_link"]').val(data.buy_link);
            $('#description').val(data.description);

            $('#brand').val(data.brand).trigger('change');
            // Do not overwrite the user's selected listing page

            if (data.category) {
                let parentIdsArray = data.category.split(',');
                let selectedParentId = parentIdsArray[0];
                $('#parent').val(selectedParentId).trigger('change');
            }

            if (data.category_objects && data.category) {
                $('#category').empty();
                data.category_objects.forEach(category => {
                    if ($('#category option[value="' + category.id + '"]').length === 0) {
                        $('#category').append(new Option(category.product_category_name, category.id));
                    }
                });
                let parentIds = data.category.split(',');
                $('#category').val(parentIds).trigger('change');
            }

            if (data.startdate && data.enddate) {
                let startDate = convertToDateFormat(data.startdate);
                let endDate = convertToDateFormat(data.enddate);
                $('input[name="start_date"]').val(startDate);
                $('input[name="end_date"]').val(endDate);
            }
        },
        error: function(xhr) {
            Swal.close();
            console.error("Error fetching product details:", xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to fetch template details.'
            });
        }
    });
}

function resetFormFields() {
    $('select').not('#List').val('').trigger('change');
    $('input[type="text"], input[type="number"], input[type="url"], input[type="date"]').val('');
    $('#description').html('');
}

function convertToDateFormat(dateTimeString) {
    return dateTimeString.split(' ')[0];
}

$(document).on('click', function(event) {
    if (!$(event.target).closest('#product_title, #suggestion-box').length) {
        $('#suggestion-box').hide().html('');
    }
});
</script>
