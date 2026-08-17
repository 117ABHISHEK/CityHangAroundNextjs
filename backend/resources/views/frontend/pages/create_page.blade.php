{{-- listing_form_multistep.blade.php --}}

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Geocoder plugin -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css">
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<!-- ✅ YOUR CUSTOM FILE (LAST) -->
<script src="{{ asset('js/map-search.js') }}"></script>

<link href="{{asset('assets/frontend/leafletjs/leaflet.css')}}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Bootstrap Select -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<!-- Leaflet core -->


<style>

/* ============================================================
   GLOBAL FORM LAYOUT
   ============================================================ */
.page-tab {
    max-width: 100% !important;
    width: 100% !important;
}

/* ============================================================
   STEP TABS
   ============================================================ */
.step-tabs {
    display: flex;
    flex-wrap: nowrap !important;
    overflow-x: auto !important;
    justify-content: flex-start;
    gap: 4px;
    margin-bottom: 0;
    padding: 0 4px;
    border-bottom: 2px solid #e9ecef;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
.step-tabs::-webkit-scrollbar { display: none; }
.step-tab {
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    border-bottom: 3px solid transparent;
    background: transparent;
    border-radius: 0;
    cursor: pointer;
    color: #6c757d;
    white-space: nowrap;
    transition: color 0.2s, border-color 0.2s;
    margin-bottom: -2px;
}
.step-tab.active {
    color: #e63946;
    border-bottom: 3px solid #e63946;
    background: transparent;
    box-shadow: none;
}
.step-tab:hover:not(.active) { color: #495057; }

.step-section { display: none; }
.step-section.active { display: block; }

.step-nav-buttons {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-top: 24px;
}

/* ============================================================
   LOCATION SECTION — CARD UI
   ============================================================ */
.location-card {
    background: #fff;
    border: 1.5px solid #e9ecef;
    border-radius: 14px;
    padding: 24px 20px 16px;
    margin-bottom: 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
}
.location-card-title {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #adb5bd;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.location-card-title i { color: #e63946; }

/* Location step indicator inside dropdowns */
.loc-step-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px; height: 22px;
    border-radius: 50%;
    background: #e63946;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    margin-right: 6px;
    flex-shrink: 0;
}

/* Disabled look for city/area before parent selected */
.location-locked .select2-container--default .select2-selection--single {
    background-color: #f0f0f0 !important;
    cursor: not-allowed !important;
    opacity: 0.7;
}
.location-locked label { opacity: 0.6; }
.location-locked .suggest-link { opacity: 0.4; pointer-events: none; }

/* Loading spinner inside select */
.loc-loading {
    display: none;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #6c757d;
    margin-top: 4px;
}
.loc-loading.show { display: flex; }
.loc-spinner {
    width: 14px; height: 14px;
    border: 2px solid #dee2e6;
    border-top-color: #e63946;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ============================================================
   FORM CONTROLS
   ============================================================ */
    .select2-container { z-index: 2000000 !important; }
    .form-group { position: relative; }
    .form-group > .select2-container:not(.select2) {
        top: 100% !important;
        left: 0 !important;
        margin-top: 2px !important;
        width: 100% !important;
    }
    .select2-container--default .select2-selection--multiple { padding: 5px; }
    .select2-search__field { height: 27px !important; padding: 5px; }

    .modal:nth-of-type(even) { z-index: 1052 !important; }
    .modal-backdrop.show:nth-of-type(even) { z-index: 1051 !important; }
    .modal { z-index: 1060 !important; }
    .modal-backdrop { z-index: 1050 !important; }
    .bigdrop { width: 600px !important; }
    .select2 { width: 100% !important; }

    .leaflet-container {
        height: 400px;
        width: 600px;
        max-width: 100%;
        max-height: 100%;
    }
    .select2-results__option { font-weight: bold; }
    .select2-container--default .select2-search--dropdown .select2-search__field::placeholder {
        color: #aaa;
        font-style: italic;
    }
    .faq-form-group { margin-bottom: 20px; }
    #categoriesModal, #areaModal { z-index: 1055 !important; }
    .modal-backdrop { z-index: 1050 !important; }
    .bootstrap-select .dropdown-menu { z-index: 1060 !important; position: absolute !important; }
    /* .modal, .modal-dialog, .modal-content { overflow: visible !important; } */

    /* Multi-step tabs */
    .step-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 10px;
        overflow-x: auto;
        white-space: nowrap;
    }
    .step-tab {
        padding: 8px 14px;
        border: 1px solid #ddd;
        border-radius: 6px 6px 0 0;
        background: #f5f5f5;
        font-weight: 600;
        cursor: pointer;
        font-size: 14px;
    }
    .step-tab.active {
        background: #ffffff;
        border-bottom: 2px solid #ff4b4b;
        color: #ff4b4b;
        box-shadow: 0 2px 4px rgba(0,0,0,0.06);
    }

    .step-section { display: none; }
    .step-section.active { display: block; }

    .step-nav-buttons {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-top: 20px;
    }
   .leaflet-control-geocoder-icon {
    width: 34px;
    height: 34px;
    border-radius: 6px;
}

.leaflet-control-geocoder-form input {
    height: 34px;
    font-size: 13px;
    border-radius: 6px;
}
#map-search-btn{
  position:absolute;
  top:10px;
  right:10px;
  z-index:1000;
  background:#fff;
  border-radius:50%;
  width:32px;
  height:32px;
  display:flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
  box-shadow:0 1px 4px rgba(0,0,0,0.3);
}

#map-search-input{
  position:absolute;
  top:10px;
  right:40px;
  z-index:1000;
  padding:4px 8px;
  width:180px;
  background:#fff;
  border:1px solid #ccc;
  border-radius:4px;
  display:none;
}
/* Professional Gray Type Styling for all elements */
.form-control,
.form-select, 
.bootstrap-select > .dropdown-toggle,
.select2-container--default .select2-selection--single,
.select2-container--default .select2-selection--multiple {
    background-color: #f8f9fa !important;
    border: none !important;
    border-radius: 8px !important;
    min-height: 45px !important;
}

.select2-container--default .select2-selection--single,
.select2-container--default .select2-selection--multiple {
    display: flex !important;
    align-items: center !important;
    padding-left: 12px !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #495057 !important;
    padding-left: 0 !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 45px !important;
}

/* Force all dropdowns to open downwards and not overlap inputs */
.bootstrap-select .dropdown-menu.show,
.suggestion-list,
.select2-container--open .select2-dropdown {
    top: 100% !important;
    bottom: auto !important;
    margin-top: 5px !important;
}

/* Page Name Suggestion Box Styles */
.suggestion-list {
    position: absolute;
    width: 100%;
    max-height: 300px;
    overflow-y: auto;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-radius: 12px;
    background: #ffffff;
    border: 1px solid #eee;
    padding: 8px 0;
    z-index: 9999;
}
.suggestion-item {
    padding: 12px 16px;
    cursor: pointer;
    border-bottom: 1px solid #f8f9fa;
    font-size: 14px;
    color: #333;
    transition: all 0.2s;
}
.suggestion-item:last-child {
    border-bottom: none;
}
.suggestion-item:hover {
    background: #f1f4f7;
    color: #0d6efd;
}

label{
    font-weight: 600;
    font-size:16px;
}



  /* Mobile responsiveness improvements */
@media (max-width: 768px) {
    .step-tabs {
        flex-wrap: nowrap !important;
        overflow-x: auto !important;
        justify-content: flex-start !important;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 10px;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .step-tab {
        padding: 10px 15px !important;
        font-size: 13px !important;
        flex: 0 0 auto;
        border: none !important;
        border-bottom: 2px solid transparent !important;
        background: transparent !important;
        border-radius: 0 !important;
        color: #666;
    }
    .step-tab.active {
        color: #0d6efd !important;
        border-bottom: 2px solid #0d6efd !important;
        background: transparent !important;
        box-shadow: none !important;
    }
    .page-tab {
        padding: 15px !important;
        border: none !important;
        border-radius: 0 !important;
    }
    .leaflet-container {
        width: 100% !important;
        height: 250px !important;
    }
    .step-nav-buttons {
        flex-direction: column-reverse;
        gap: 15px !important;
    }
    .step-nav-buttons .btn {
        width: 100% !important;
        margin: 0 !important;
        padding: 12px !important;
        font-size: 16px !important;
    }
    .btn-next {
        margin-right: 0 !important;
    }
    
    /* Opening Hours Modern UI */
    .opening-hours-container .border {
        border-color: #eee !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .day-row:hover {
        background-color: #fcfcfc;
    }
    .day-row:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }
    
    .day-rows-wrapper {
        padding-bottom: 0 !important;
        overflow: hidden;
    }

    /* FORCED GREEN SWITCH UI */
    .form-switch .form-check-input {
        background-color: #198754 !important; /* Green = Open */
        border-color: #198754 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e") !important;
        background-position: left center !important;
        cursor: pointer;
        height: 1.5em !important;
        width: 3em !important;
        transition: background-position 0.2s ease-in-out, background-color 0.2s ease-in-out !important;
    }
    .form-switch .form-check-input:checked {
        background-color: #dee2e6 !important; /* Grey = Closed */
        border-color: #adb5bd !important;
        background-position: right center !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e") !important;
    }

    .global-status-label, .status-label {
        min-width: 60px;
        text-align: right;
    }
    .time-inputs .form-control {
        border: 1px solid #dee2e6 !important;
        background-color: #fff !important;
        padding: 0.25rem 0.5rem;
        height: 38px;
    }
    .time-inputs .form-control:focus {
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Step 5: Media & Submit Modern Design (Option 1) */
    .media-card {
        border: 1px solid #eef0f2;
        border-radius: 12px;
        padding: 24px;
        background: #fff;
        height: auto;
        margin-bottom: 24px;
    }
    .media-card-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 4px;
        color: #1a1a1a;
    }
    .media-card-desc {
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 20px;
    }
    .upload-zone {
        border: 2px dashed #d1d9e0;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        background: #f8fbff;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }
    .upload-zone:hover {
        border-color: #007bff;
        background: #f0f7ff;
    }
    .upload-zone i {
        font-size: 40px;
        color: #007bff;
        margin-bottom: 15px;
        display: block;
    }
    .upload-zone p {
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
    }
    .upload-zone span {
        font-size: 12px;
        color: #888;
    }

    /* Media Previews */
    .media-preview-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 12px;
        margin-top: 20px;
    }
    .media-preview-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        aspect-ratio: 1;
        border: 1px solid #eee;
    }
    .media-preview-item img, .media-preview-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .media-remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        color: #dc3545;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        z-index: 10;
    }
    .media-info-badge {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.5);
        color: #fff;
        font-size: 10px;
        padding: 2px 5px;
        text-align: center;
    }

    /* Document Row Style */
    .document-upload-row {
        background: #fff;
        border: 1px solid #eef0f2;
        border-radius: 8px;
        padding: 12px 15px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 10px;
    }
    .doc-icon {
        width: 40px;
        height: 40px;
        background: #e7f3ff;
        color: #007bff;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .doc-info { flex: 1; overflow: hidden; }
    .doc-name { font-weight: 600; font-size: 14px; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .doc-size { font-size: 12px; color: #888; }
    
    /* Logo Upload Style */
    .logo-upload-container {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 15px;
        background: #f8fbff;
        border: 1px solid #e7f3ff;
        border-radius: 12px;
    }
    .logo-preview-box {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        background: #fff;
        border: 2px dashed #d1d9e0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }
    .logo-preview-box img { width: 100%; height: 100%; object-fit: contain; }
    .logo-preview-box i { font-size: 24px; color: #d1d9e0; }

    .btn-upload-outline {
        border: 1px solid #007bff;
        color: #007bff;
        background: transparent;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
    }
    .btn-upload-outline:hover {
        background: #007bff;
        color: #fff;
    }
    
    /* Responsive Opening Hours */
    @media (max-width: 576px) {
        .day-row {
            padding: 15px !important;
            border-bottom: 1px solid #eee !important;
        }
        .day-row > div {
            padding: 0 !important;
            margin-bottom: 8px;
        }
        .day-row > div:last-child {
            margin-bottom: 0;
        }
        .time-inputs {
            width: 100% !important;
            justify-content: space-between;
        }
        .time-inputs .form-control {
            flex: 1;
            max-width: 45%;
        }
    }
} /* End of @media (max-width: 768px) */
@media (max-width: 768px){

.media-card{
margin-bottom:20px;
}

.upload-zone{
padding:20px !important;
}

.logo-preview-box{
width:70px;
height:70px;
}

}
    /* Circular Progress Design */
    .circular-progress {
        position: relative;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .circular-progress svg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        transform: rotate(-90deg);
    }
    .circular-progress circle {
        fill: none;
        stroke-width: 5;
        stroke-linecap: round;
    }
    .circular-progress circle.bg {
        stroke: #eef0f2;
    }
    .circular-progress circle.fg {
        stroke: #0d6efd;
        stroke-dasharray: 164;
        stroke-dashoffset: 164;
        transition: stroke-dashoffset 0.3s ease;
    }
    .circular-progress .progress-info {
        font-size: 11px;
        font-weight: 700;
        color: #333;
        z-index: 1;
    }
    .circular-progress.success circle.fg {
        stroke: #198754;
    }
    .circular-progress.error circle.fg {
        stroke: #dc3545;
    }
</style>

<div class="page-content mb-5">
    <div class="page-tab bg-white border rounded p-3 p-md-4 pb-5 shadow-sm">
        <form id="listing-form" action="{{ route('page.store') }}" method="POST" enctype="multipart/form-data">
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

            {{-- STEP TABS --}}
            <div class="step-tabs">
                <div class="step-tab active" data-step="1">Basic Info</div>
                <!-- <div class="step-tab" data-step="2">Contact & Location</div> -->
                <div class="step-tab" data-step="3">Online & Social</div>
                <div class="step-tab" data-step="4">Business Details</div>
                <div class="step-tab" data-step="5">Media & Submit</div>
            </div>

            {{-- PROGRESS BAR --}}
            <div class="progress mb-3" style="height: 8px; border-radius: 20px;">
                <div id="step-progress" class="progress-bar bg-success" style="width:20%; border-radius: 20px;"></div>
            </div>

            {{-- ================= STEP 1 ================= --}}
            <div class="step-section active" data-step="1">
                <div class="form-group mb-3">
                    <label >{{ get_phrase('Page Name') }} <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="text" class="form-control border-0 bg-secondary" name="name" id="page_name" placeholder="Enter your page Name" value="{{ old('name') }}" onkeyup="getPageSuggestions()">
                        <div id="suggestion-box"></div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label>{{ get_phrase('Type Category Name Here') }} <span class="text-danger">*</span></label>
                    <select name="parent" id="parent" class="form-select location-sel2 @error('parent') is-invalid @enderror" style="width:100%"data-placeholder="Type Category">
                        <option value="">Select Parent Category</option>
                        @foreach($parent as $printable_category)
                            <option value="{{ $printable_category->id }}" {{ old('parent') == $printable_category->id ? 'selected' : '' }}>
                                {{ $printable_category->category_name }}
                            </option>
                        @endforeach
                    </select>
                    <a class="text-info pl-2 float-left" style="color:#ff4437 !important;" onclick="showparentcategorymodel();">
                        <i class="far fa-add"></i> Click here to add Parent category(If not in list)
                    </a>
                </div>

                

                <div class="form-group mb-3">
                    <label>{{ get_phrase('Description') }}</label>
                    <textarea name="description" class="form-control border-0 bg-secondary content" id="description" rows="5" placeholder="Description">{{ old('description') }}</textarea>
                </div>

                <!-- <div class="step-nav-buttons">
                    <span></span>
                    <button type="button" class="btn btn-primary btn-next" data-next="2">Save & Continue →</button>
                </div>
            -->

            {{-- ================= STEP 2 ================= --}}
            <!-- <div class="step-section" data-step="2"> -->
                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label>{{ get_phrase('Address') }}</label>
                        <input id="address" name="address" type="text" class="form-control border-0 bg-secondary" placeholder="Enter your address" value="{{ old('address') }}" />
                    </div>
                    <div class="col-lg-6">
                        <label>{{ get_phrase('Phone No') }}</label>
                        <input type="text" class="form-control border-0 bg-secondary" name="item_phone" placeholder="Enter Phone no" value="{{ old('item_phone') }}">
                    </div>
                </div>

                {{-- =============================================
                     LOCATION CARD — Country / State / City / Area
                     ============================================= --}}
                <div class="location-card">
                    <div class="location-card-title">
                        <i class="fas fa-map-marker-alt"></i> Business Location
                    </div>

                    {{-- Row 1: Country + State --}}
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6">
                            <div class="form-group" id="country-group">
                                <label for="country">
                                    <span class="loc-step-badge">1</span>{{ get_phrase('Country') }} <span class="text-danger">*</span>
                                </label>
                                <select name="country" id="country" class="form-select location-sel2 @error('country') is-invalid @enderror" style="width:100%">
                                    <option value="0">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('country') == $country->id ? 'selected':'' }}>{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="form-group" id="state-group">
                                <label for="state">
                                    <span class="loc-step-badge">2</span>{{ get_phrase('State') }} <span class="text-danger">*</span>
                                </label>
                                <select name="state" id="state" class="form-select location-sel2 @error('state') is-invalid @enderror" style="width:100%" data-placeholder="Select State">
                                    <option value="">Select State</option>
                                    @if(old('state'))
                                        @php $s_old = DB::table('states')->where('id', old('state'))->first(); @endphp
                                        @if($s_old) <option value="{{ $s_old->id }}" selected>{{ $s_old->state_name }}</option> @endif
                                    @endif
                                </select>
                                <input type="hidden" id="old_state_id" value="{{ old('state') }}">
                                <div class="loc-loading" id="state-loading"><div class="loc-spinner"></div> Loading states…</div>
                            </div>
                        </div>
                    </div>

                    {{-- Row 2: City + Area --}}
                    <div class="row g-3 mb-2">
                        <div class="col-12 col-sm-6">
                            <div class="form-group" id="city-group">
                                <label for="city">
                                    <span class="loc-step-badge">3</span>{{ get_phrase('City') }} <span class="text-danger">*</span>
                                </label>
                                <select name="city" id="city" class="form-select location-sel2 @error('city') is-invalid @enderror" style="width:100%" data-placeholder="Select City">
                                    <option value="">Select City</option>
                                    @if(old('city'))
                                        @php $c_old = DB::table('cities')->where('id', old('city'))->first(); @endphp
                                        @if($c_old) <option value="{{ $c_old->id }}" selected>{{ $c_old->city_name }}</option> @endif
                                    @endif
                                </select>
                                <input type="hidden" id="old_city_id" value="{{ old('city') }}">
                                <div class="loc-loading" id="city-loading"><div class="loc-spinner"></div> Loading cities…</div>
                                <a class="suggest-link" style="font-size:12px; color:#e63946; cursor:pointer; display:inline-block; margin-top:4px;" onclick="showcitymodel();">
                                    <i class="fas fa-plus-circle"></i> Suggest City
                                </a>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="form-group" id="area-group">
                                <label for="area">
                                    <span class="loc-step-badge">4</span>{{ get_phrase('Area') }} <span class="text-danger">*</span>
                                </label>
                                <select name="area" id="area" class="form-select location-sel2 @error('area') is-invalid @enderror" style="width:100%" data-placeholder="Select Area">
                                    <option value="">Select Area</option>
                                    @if(old('area'))
                                        @php $a_old = DB::table('areas')->where('id', old('area'))->first(); @endphp
                                        @if($a_old) <option value="{{ $a_old->id }}" selected>{{ $a_old->area_name }}</option> @endif
                                    @endif
                                </select>
                                <input type="hidden" id="old_area_id" value="{{ old('area') }}">
                                <div class="loc-loading" id="area-loading"><div class="loc-spinner"></div> Loading areas…</div>
                                <a class="suggest-link" style="font-size:12px; color:#e63946; cursor:pointer; display:inline-block; margin-top:4px;" onclick="showareamodel();" data-bs-toggle="modal">
                                    <i class="fas fa-plus-circle"></i> Suggest Area
                                </a>
                            </div>
                        </div>
                    </div>
                </div>{{-- end location-card --}}

                @error('type')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label>{{ get_phrase('Pincode') }}</label>
                        <input id="pincode" name="pincode" type="number" class="form-control border-0 bg-secondary" placeholder="Enter your pincode" value="{{ old('pincode') }}">
                    </div>
                    <div class="col-lg-6"></div>
                </div>
     

                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label>{{ get_phrase('Latitude') }}</label>
                       
                        <input id="item_lat" name="item_lat" type="text" class="form-control border-0 bg-secondary" value="{{ old('item_lat') }}">
                       <!-- <a class="location-post me-auto ms-auto" href="https://www.google.com/maps/place/" target="_blanck"> -->
                        <a class="lat_lng_select_button btn btn-sm btn-primary text-white mt-2 mb-1" >Select on map</a>
                      <!-- <div id="map" style="height:400px; position:relative;"></div> -->
   

                    <div class="col-lg-6">
                        <label>{{ get_phrase('Longitude') }}</label>
                        <input type="text" class="form-control border-0 bg-secondary" id="item_lng" name="item_lng" value="{{ old('item_lng') }}">
                    </div>
                </div>
</div>


                <div class="step-nav-buttons">
                    <!-- <button type="button" class="btn btn-secondary btn-prev" data-prev="1">← Back</button> -->
                    <button type="button" class="btn btn-primary btn-next" data-next="3">Save & Continue →</button>
                </div>
                </div>
        
             <!-- </div> -->

            {{-- ================= STEP 3 ================= --}}
            <div class="step-section" data-step="3">
                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label>{{ get_phrase('Youtube video id') }}</label>
                        <input type="text" class="form-control border-0 bg-secondary" name="youtube_video_id" placeholder="Enter Youtube video id" value="{{ old('youtube_video_id') }}">
                    </div>
                    <div class="col-lg-6">
                        <label>{{ get_phrase('Website') }}</label>
                        <input type="text" class="form-control border-0 bg-secondary" name="website" placeholder="Enter Website" value="{{ old('website') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label>{{ get_phrase('Business Email') }}</label>
                        <input type="text" class="form-control border-0 bg-secondary" name="business_email" placeholder="Enter Business Email" value="{{ old('business_email') }}">
                    </div>
                    <div class="col-lg-6">
                        <label>{{ get_phrase('Business Whatsapp URL') }}</label>
                        <input type="text" class="form-control border-0 bg-secondary" name="business_whatsapp_url" placeholder="Enter Business Whatsapp URL" value="{{ old('business_whatsapp_url') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label>{{ get_phrase('Facebook') }}</label>
                        <input type="text" class="form-control border-0 bg-secondary" name="facebook" placeholder="Facebook" value="{{ old('facebook') }}">
                    </div>
                    <div class="col-lg-6">
                        <label>{{ get_phrase('Twitter') }}</label>
                        <input type="text" class="form-control border-0 bg-secondary" name="twitter" placeholder="Twitter" value="{{ old('twitter') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label>{{ get_phrase('LinkedIn') }}</label>
                        <input type="text" class="form-control border-0 bg-secondary" name="linkedIn" placeholder="LinkedIn" value="{{ old('linkedIn') }}">
                    </div>
                    <div class="col-lg-6">
                        <label>{{ get_phrase('Instagram Link') }}</label>
                        <input type="text" class="form-control border-0 bg-secondary" name="instalink" placeholder="Instagram link" value="{{ old('instalink') }}">
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label>{{ get_phrase('Products/Service Tags') }}</label>
                    <select name="servicecategory[]" id="servicecategory" class="selectpicker form-control @error('servicecategory') is-invalid @enderror" multiple></select>
                </div>

                <div class="form-group mb-3">
                    <label>{{ get_phrase('Why Visit Us') }}</label>
                    <textarea name="visitus" class="form-control border-0 bg-secondary content" id="visitus" rows="5" placeholder="Why Visit Us">{{ old('visitus') }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label>{{ get_phrase('Our Story') }}</label>
                    <textarea name="our_story" class="form-control border-0 bg-secondary content" id="our_story" rows="5" placeholder="Our Story">{{ old('our_story') }}</textarea>
                </div>

                <div class="step-nav-buttons">
                    <button type="button" class="btn btn-secondary btn-prev" data-prev="2">← Back</button>
                    <button type="button" class="btn btn-primary btn-next" data-next="4">Save & Continue →</button>
                </div>
            </div>

            {{-- ================= STEP 4 ================= --}}
            <div class="step-section" data-step="4">
                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label>{{ get_phrase('Years of Establishments') }}</label>
                        <input type="number" class="form-control border-0 bg-secondary" name="yrofest" placeholder="Year of Establishment" value="{{ old('yrofest') }}">
                    </div>
                </div>

                @php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                @endphp

                <div class="col-lg-12 mb-3 p-0 opening-hours-container">
                    <label class="mb-2"><strong>{{ get_phrase('Opening Hours') }}</strong></label>
                    <div class="border rounded bg-white day-rows-wrapper">
                        <!-- Global Apply Checkbox -->
                        <div class="form-check mb-3 d-flex align-items-center gap-2">
                            <input type="checkbox" class="form-check-input" id="apply_same_hours">
                            <label class="form-check-label fw-bold" for="apply_same_hours">
                                {{ get_phrase('Apply same hours to all days') }}
                                <i class="fas fa-info-circle text-muted ms-1" title="Set hours once for all active days"></i>
                            </label>
                        </div>

                        <!-- Global Time Input (Hidden by default, shown when apply_same is checked) -->
                        <div id="global_hours_row" class="row mb-3 align-items-center bg-light p-2 rounded mx-0" style="display: none;">
                            <div class="col-auto">
                                <strong>{{ get_phrase('Time') }}:</strong>
                            </div>
                            <div class="col d-flex align-items-center gap-2">
                                <input type="time" id="global_open" class="form-control form-control-sm" style="max-width: 130px;">
                                <span>—</span>
                                <input type="time" id="global_close" class="form-control form-control-sm" style="max-width: 130px;">
                                <div class="ms-auto d-flex align-items-center gap-2">
                                    <span class="fw-bold global-status-label text-success">Open</span>
                                    <div class="form-check form-switch p-0" style="margin-left: 32px;">
                                        <input type="checkbox" class="form-check-input" id="global_closed_toggle">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="day-rows">
                            @foreach ($days as $day)
                                @php
                                    $dayKey = strtolower($day);
                                    $isClosed = old("opening_hours.$dayKey.closed") == '1';
                                @endphp
                                <div class="row align-items-center mx-0 px-3 {{ !$loop->last ? 'border-bottom py-3' : 'pt-3' }} day-row" data-day="{{ $dayKey }}" {!! $loop->last ? 'style="padding-bottom: 20px !important;"' : '' !!}>
                                    <div class="col-12 col-sm-3 mb-2 mb-sm-0">
                                        <strong>{{ $day }}</strong>
                                    </div>
                                    <div class="col-8 col-sm-6 d-flex align-items-center gap-2 time-inputs" id="time_inputs_{{ $dayKey }}">
                                        <input type="time"
                                            class="form-control form-control-sm opening-time"
                                            name="opening_hours[{{ $dayKey }}][open]"
                                            value="{{ old("opening_hours.$dayKey.open", '09:00') }}"
                                            {{ $isClosed ? 'disabled' : '' }}>
                                        <span>—</span>
                                        <input type="time"
                                            class="form-control form-control-sm closing-time"
                                            name="opening_hours[{{ $dayKey }}][close]"
                                            value="{{ old("opening_hours.$dayKey.close", '18:00') }}"
                                            {{ $isClosed ? 'disabled' : '' }}>
                                    </div>
                                    <div class="col-4 col-sm-3 d-flex align-items-center justify-content-end gap-2">
                                        <span class="status-label fw-bold {{ $isClosed ? 'text-danger' : 'text-success' }} d-none d-sm-inline" id="status_{{ $dayKey }}">
                                            {{ $isClosed ? 'Closed' : 'Open' }}
                                        </span>
                                        <div class="form-check form-switch p-0">
                                            <input type="checkbox"
                                                class="form-check-input closed-toggle ms-0"
                                                id="closed_{{ $dayKey }}"
                                                name="opening_hours[{{ $dayKey }}][closed]"
                                                value="1"
                                                {{ $isClosed ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="location">{{ get_phrase('Services Offered Location') }}</label>
                    <div class="row mb-3">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="servicecountry">{{ get_phrase('Country') }}</label>
                            <select name="servicecountry" id="servicecountry" class="form-select location-sel2 @error('servicecountry') is-invalid @enderror" style="width:100%">
                                <option value="0">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('servicecountry') == $country->id ? 'selected':'' }}>{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Service State Dropdown -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="servicestate">{{ get_phrase('State') }}</label>
                            <select name="servicestate" id="servicestate" class="form-select location-sel2 @error('servicestate') is-invalid @enderror" style="width:100%" data-placeholder="Select State">
                                <option value="">Select State</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Service City Dropdown -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="servicecity">{{ get_phrase('City') }}</label>
                            <select name="servicecity" id="servicecity" class="form-select location-sel2 @error('servicecity') is-invalid @enderror" style="width:100%"  data-placeholder="Select City">
                                <option value="">Select City</option>
                            </select>
                        </div>
                    </div>

                    <!-- Service Area Dropdown -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="servicearea">{{ get_phrase('Area') }}</label>
                            <select name="servicearea" id="servicearea" class="form-select location-sel2 @error('servicearea') is-invalid @enderror" style="width:100%" data-placeholder="Select Area">
                                <option value="">Select Area</option>
                            </select>
                        </div>
                    </div>
                </div>

</div>


                <div class="form-group mb-3">
                    <label for="#">{{ get_phrase('Return/Refund Policy') }}</label>
                    <textarea name="policy" class="form-control border-0 bg-secondary content" id="policy" rows="5" placeholder="Return/Refund Policy">{{ old('policy') }}</textarea>
                </div>

                <div id="faq-container" class="form-group mb-2">
                    <label for="#">{{ get_phrase('FAQ') }}</label>
                    <div class="faq-form-group">
                        <label>Question:</label>
                        <input type="text" name="faqs[0][question]" class="form-control border-0 bg-secondary">
                        <br>
                        <label>Answer:</label>
                        <textarea name="faqs[0][answer]" class="form-control border-0 bg-secondary"></textarea>
                    </div>
                </div>

                <button type="button" id="add-faq" class="btn btn-success py-2 px-4 text-white mb-3">Add Another FAQ</button>

                <div class="step-nav-buttons">
                    <button type="button" class="btn btn-secondary btn-prev" data-prev="3">← Back</button>
                    <button type="button" class="btn btn-primary btn-next" data-next="5">Save & Continue →</button>
                </div>
            </div>

            {{-- ================= STEP 5 ================= --}}
           {{-- ================= STEP 5: MEDIA & SUBMIT (OPTION 1) ================= --}}
<div class="step-section" data-step="5">

    <div class="row g-4 mb-4">
        {{-- LEFT SIDE – BUSINESS MEDIA --}}
        <div class="col-12 col-lg-6">
            <div class="media-card h-100">
                <div class="mb-3">
                    <h4 class="fw-bold mb-1">Business Media</h4>
                    <p class="text-muted small">Upload photos or videos of your business.</p>
                </div>

                <div class="upload-zone text-center p-3" style="border: 2px dashed #dee2e6; border-radius: 12px; background: #fafafa;"
                     onclick="document.getElementById('media').click();">
                    <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                    <p class="mb-1 small"><strong>Drag & Drop files</strong> or <span class="text-primary fw-semibold">Browse</span></p>
                    <small class="text-muted" style="font-size: 11px;">JPG, PNG, MP4 | Max 10MB</small>

                    <input type="file" name="media[]" id="media" class="d-none" multiple accept="image/*,video/*" onchange="previewMedia(this)">
                </div>
                <!-- Upload Progress Container (Business Media) -->
                <div id="uploadProgressMedia" class="uploadProgress d-none mt-3">
                    <div class="bg-light p-3 rounded border shadow-sm">
                        <div class="d-flex align-items-center mb-2">
                            <div class="circular-progress me-3">
                                <svg width="60" height="60">
                                    <circle class="bg" cx="30" cy="30" r="26"></circle>
                                    <circle class="fg" cx="30" cy="30" r="26"></circle>
                                </svg>
                                <div class="progress-info">
                                    <span class="percent">0%</span>
                                </div>
                            </div>
                            <div class="progress-details flex-grow-1">
                                <div class="fw-bold text-success mb-1">
                                    <span class="percent-label">0%</span> <span class="upload-status-label">{{get_phrase('completed')}}</span>
                                </div>
                                <small class="uploadFileName text-muted d-block text-truncate" style="max-width: 180px;"></small>
                            </div>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                <div id="media-preview" class="media-preview-container mt-3"></div>
            </div>
        </div>

        {{-- RIGHT SIDE – GROUPED BUSINESS DETAILS (Verification + Logo + Video) --}}
        <div class="col-12 col-lg-6">
            <div class="media-card h-100">
                <h4 class="fw-bold mb-3">Additional Details</h4>
                
                {{-- Segment 1: Business Verification --}}
                <div class="pb-4 mb-4 border-bottom">
                    <h6 class="fw-bold mb-2"><i class="fas fa-check-circle text-success me-1"></i> Business Verification</h6>
                    <p class="text-muted small mb-3">Upload proof that you own or manage this business.</p>
                    
                    <button type="button" class="btn btn-outline-primary btn-sm w-100 py-2" onclick="document.getElementById('Proof_of_Ownership').click();">
                        <i class="fas fa-file-upload me-2"></i> Upload Document
                    </button>
                    <input type="file" name="Proof_of_Ownership" id="Proof_of_Ownership" class="d-none" accept=".pdf,.doc,.docx,.jpg,.png" onchange="previewDoc(this)">
                    
                    <!-- Upload Progress Container (Verification) -->
                    <div id="uploadProgressProof" class="uploadProgress d-none mt-3">
                        <div class="bg-light p-2 rounded border shadow-sm">
                            <div class="d-flex align-items-center">
                                <div class="circular-progress me-2" style="transform: scale(0.7); transform-origin: left center;">
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

                    <div id="doc-preview-container" class="mt-2"></div>
                </div>

                {{-- Segment 2: Business Logo --}}
                <div class="pb-4 mb-4 border-bottom">
                    <h6 class="fw-bold mb-2"><i class="fas fa-image text-primary me-1"></i> Business Logo</h6>
                    <div class="d-flex align-items-center gap-3 mt-3">
                        <div class="logo-preview-box flex-shrink-0" id="logo-preview" style="width: 60px; height: 60px;">
                            <i class="fas fa-store fa-lg text-muted"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted small mb-2">Recommended: 500x500px</p>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('image').click();">
                                Select Logo
                            </button>
                        </div>
                        <input type="file" name="image" id="image" class="d-none" accept="image/*" onchange="previewLogo(this)">
                    </div>

                    <!-- Upload Progress Container (Logo) -->
                    <div id="uploadProgressLogo" class="uploadProgress d-none mt-2">
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
                                <div class="fw-bold text-success small mb-0 flex-grow-1">
                                    <span class="percent-label">0%</span> {{get_phrase('completed')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Segment 3: Featured Video --}}
                <div>
                    <h6 class="fw-bold mb-2"><i class="fas fa-video text-danger me-1"></i> Featured Video <span class="text-muted small fw-normal">(Optional)</span></h6>
                    <div class="d-flex flex-column flex-sm-row gap-2 mt-3">
                        <button type="button" class="btn btn-outline-primary btn-sm flex-fill" onclick="document.getElementById('featured_video').click();">
                            <i class="fas fa-video me-1"></i> Upload Video
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm flex-fill" onclick="document.getElementById('featured_thumbnail').click();">
                            <i class="fas fa-image me-1"></i> Thumbnail
                        </button>
                    </div>

                    <input type="file" name="featured_video" id="featured_video" class="d-none" accept="video/*" onchange="updateVideoBtn(this)">
                    <input type="file" name="featured_thumbnail" id="featured_thumbnail" class="d-none" accept="image/*" onchange="updateThumbBtn(this)">
                    
                    <!-- Upload Progress Container (Featured Video) -->
                    <div id="uploadProgressVideo" class="uploadProgress d-none mt-2">
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
                                <div class="fw-bold text-success small mb-0 flex-grow-1">
                                    <span class="percent-label">0%</span> {{get_phrase('completed')}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="video-file-info" class="mt-2 small text-muted"></div>
                </div>
            </div>
        </div>
    </div>


    {{-- Terms --}}
    <div class="form-check mb-4">
        <input class="form-check-input"
               type="checkbox"
               id="terms"
               name="terms"
               required>

        <label class="form-check-label" for="terms">
            I agree to the
            <a href="javascript:void(0)" onclick="showtermsymodel()">Terms and Conditions</a>
        </label>
    </div>


    {{-- Buttons --}}
    <div class="d-flex flex-column flex-md-row gap-3 mb-4">
        <button type="button"
                class="btn btn-secondary btn-prev flex-fill mb-2 mb-md-0"
                data-prev="4">
            ← Back
        </button>

        <button type="submit"
                class="btn btn-primary flex-fill fw-bold">
            Create Business Page
        </button>
    </div>
        </form>
    </div>
</div>

{{-- --------------- MODALS --------------- --}}

@include('frontend.initialize')

<!-- Modal parent categories -->
<div class="modal fade" id="parentcategoriesModal" tabindex="-1" role="dialog" aria-labelledby="parentcategoriesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
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

<!-- Modal sub categories -->
<div class="modal fade" id="categoriesModal" tabindex="-1" role="dialog" aria-labelledby="categoriesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
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
                        @foreach($printable_categories as $printable_category)
                            <option value="{{ $printable_category->id }}">{{ $printable_category->category_name }}</option>
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


<!-- Modal sub categories -->
<div class="modal fade" id="categoriesModal" tabindex="-1" role="dialog" aria-labelledby="categoriesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
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
                        @foreach($printable_categories as $printable_category)
                            <option value="{{ $printable_category->id }}">{{ $printable_category->category_name }}</option>
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

<div class="modal fade" id="cityModal" tabindex="-1" role="dialog" aria-labelledby="cityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="cityModalLabel">{{ get_phrase('Add City') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label for="city_name" class="form-label fw-semibold">City Name</label>
                    <input id="city_name" type="text" class="form-control bg-secondary border-0 px-3" style="height: 45px; border-radius: 8px;" name="city_name" placeholder="Enter city name">
                </div>
                <div class="d-grid mt-4">
                    <button type="button" class="btn btn-primary fw-bold" style="height: 45px; border-radius: 8px;" onclick="submitcity();">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="areaModal" tabindex="-1" aria-labelledby="areaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="areaModalLabel">{{ get_phrase('Add Area') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label for="area_name" class="form-label fw-semibold">Area Name</label>
                    <input id="area_name" type="text" class="form-control bg-secondary border-0 px-3" style="height: 45px; border-radius: 8px;" name="area_name" placeholder="Enter area name">
                </div>
                <div class="d-grid mt-4">
                    <button type="button" class="btn btn-primary fw-bold" style="height: 45px; border-radius: 8px;" onclick="submitarea();">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal - map -->
<div class="modal fade" id="map-modal" tabindex="-1" role="dialog" aria-labelledby="map-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ get_phrase('Select Location') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div id="map-modal-body" style="height:400px; position:relative;"></div>
                <div id="map-search-btn">
                    <i class="fa fa-search"></i>
                </div>
                <input id="map-search-input" type="text" placeholder="Search for location..." style="display:none">
            </div>
            <div class="modal-footer">
                <button id="lat_lng_confirm" type="button" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="termsAndConditionsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="termsModalLabel">{{ get_phrase('Terms and Conditions') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="iframe-container" style="height: 500px; padding: 10px;">
                    <iframe src="{{ route('term.view', ['noimage' => 1]) }}" width="100%" height="100%" style="border:none;" scrolling="yes"></iframe>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal" style="border-radius: 8px;">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Claim modal -->
<div class="modal fade" id="claimModal" tabindex="-1" role="dialog" aria-labelledby="claimModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="claimModalLabel">A Matching Listing Was Found</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                A listing with similar details exists. Would you like to view or claim it?
            </div>
            <div class="modal-footer">
                <a id="viewListingBtn" href="#" target="_blank" class="btn btn-primary">View Listing</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script src="{{asset('assets/frontend/leafletjs/leaflet.js')}}"></script>
<script src="{{asset('assets/frontend/leafletjs/leaflet-search.js')}}"></script>

<script>
    // FAQ add from original
    let faqIndex = 1;
    document.getElementById('add-faq').addEventListener('click', function() {
        const container = document.getElementById('faq-container');
        const faqGroup = document.createElement('div');
        faqGroup.classList.add('faq-form-group');
        faqGroup.innerHTML = `
            <label>Question:</label>
            <input type="text" name="faqs[${faqIndex}][question]" class="border-0 bg-secondary form-control">
            <br>
            <label>Answer:</label>
            <textarea name="faqs[${faqIndex}][answer]" class="border-0 bg-secondary form-control"></textarea>
        `;
        container.appendChild(faqGroup);
        faqIndex++;
    });
</script>



{{-- Removed redundant jQuery and Bootstrap 4 imports to prevent conflicts with Bootstrap 5 and other scripts --}}


{{-- ================= ORIGINAL JS (INITIALIZATION, AUTOSAVE, SELECT2, MAP, ETC) KEPT AS-IS ================= --}}
<!-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const btn  = document.getElementById('map-search-btn');
    const input = document.getElementById('map-search-input');

    if (btn && input) {
        btn.addEventListener('click', function () {
            if (input.style.display === 'none' || input.style.display === '') {
                input.style.display = 'block';
                input.focus();
            } else {
                input.style.display = 'none';
            }
        });
    }
});
</script> -->
<script>
    let saveTimeout;
    let listingId = null;

    $('#listing-form').on('input change', 'input, textarea, select', function() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(function() {

            let name = $('input[name="name"]').val();
            let address = $('input[name="address"]').val();
            let phone = $('input[name="item_phone"]').val();

            // Check for existing matching page
            $.ajax({
                url: '{{ route("page.check.match") }}',
                method: 'POST',
                data: {
                    name: name,
                    address: address,
                    item_phone: phone,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.exists && !response.hasPhone) {
                        let claimUrl = `${window.location.origin}/${response.city_slug}/${response.area_slug}/${response.category_slug}/${response.item_slug}`;
                        showModal(claimUrl);
                        return;
                    }

                    // Proceed to save draft
                    let formData = $('#listing-form').serialize();
                    if (listingId) {
                        formData += '&listing_id=' + encodeURIComponent(listingId);
                    }

                    $.ajax({
                        url: "{{ route('page.save.draft') }}",
                        method: 'POST',
                        data: formData,
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        success: function(response) {
                            console.log('Draft saved');
                            if (!listingId && response.listing_id) {
                                listingId = response.listing_id;
                            }
                        },
                        error: function(xhr) {
                            console.error('Draft save failed:', xhr.responseText);
                        }
                    });
                },
                error: function(xhr) {
                    console.error('Match check failed:', xhr.responseText);
                }
            });
        }, 2000);
    });
    // Global AJAX Setup for CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<script>
    $(document).ready(function() {

        // Initialize Select2 for all location and parent dropdowns with dropdownParent to fix position offset bugs
        $('.location-sel2').each(function() {
            $(this).select2({
                width: '100%',
                placeholder: 'Search...',
                dropdownParent: $(this).parent(),
                allowClear: true
            });
        });

        // Fix stacking context so dropdowns don't appear behind later labels
        $(document).on('select2:open', function(e) {
            $(e.target).parents('.form-group, .mb-3, .row').css('z-index', 99999);
        });
        $(document).on('select2:close', function(e) {
            $(e.target).parents('.form-group, .mb-3, .row').css('z-index', '');
        });



        // Initialize remaining selectpicker elements (non-location ones if any)
        if ($.fn.selectpicker && $('.selectpicker').length) {
            $('.selectpicker').selectpicker();
        }


        $('#claimModal .close, #claimModal .btn-secondary').on('click', function() {
            $('#claimModal').modal('hide');
        });



        // OPENING HOURS LOGIC
        function toggleTimeInputs($checkbox) {
            const dayKey = $checkbox.attr('id').replace('closed_', '');
            const $row = $checkbox.closest('.day-row');
            const $inputs = $('#time_inputs_' + dayKey).find('input');
            const $status = $('#status_' + dayKey);

            if ($checkbox.is(':checked')) {
                $inputs.prop('disabled', true);
                $status.text('Closed').removeClass('text-success').addClass('text-danger');
            } else {
                $inputs.prop('disabled', false);
                $status.text('Open').removeClass('text-danger').addClass('text-success');
            }
        }

        $('.closed-toggle').each(function() {
            toggleTimeInputs($(this));
        });

        // Toggle individual switch
        $(document).on('change', '.closed-toggle', function() {
            toggleTimeInputs($(this));
        });

        // Apply same hours logic
        $('#apply_same_hours').on('change', function() {
            if ($(this).is(':checked')) {
                $('#global_hours_row').slideDown();
                // Set initial global values from Monday
                $('#global_open').val($('.day-row[data-day="monday"] .opening-time').val());
                $('#global_close').val($('.day-row[data-day="monday"] .closing-time').val());
                $('#global_closed_toggle').prop('checked', $('#closed_monday').is(':checked'));
                syncGlobalToAll();
            } else {
                $('#global_hours_row').slideUp();
            }
        });

        function syncGlobalToAll() {
            if (!$('#apply_same_hours').is(':checked')) return;

            const openTime = $('#global_open').val();
            const closeTime = $('#global_close').val();
            const isClosed = $('#global_closed_toggle').is(':checked');

            $('.day-row').each(function() {
                const dayKey = $(this).data('day');
                $(this).find('.opening-time').val(openTime);
                $(this).find('.closing-time').val(closeTime);
                
                const $switch = $('#closed_' + dayKey);
                if ($switch.is(':checked') !== isClosed) {
                    $switch.prop('checked', isClosed).trigger('change');
                }
            });

            // Update global status label
            $('.global-status-label').text(isClosed ? 'Closed' : 'Open')
                .toggleClass('text-success', !isClosed)
                .toggleClass('text-danger', isClosed);
        }

        $('#global_open, #global_close, #global_closed_toggle').on('change input', syncGlobalToAll);

        $('#category_parent_id').select2({ dropdownParent: $('#categoriesModal') });



        $('#category').select2({
            tags: true,
            dropdownParent: $('#category').parent(),
            placeholder: 'Type Category',
            multiple: true,
            tokenSeparators: [','],
            ajax: {
                url: '/categories-autocomplete-ajax',
                dataType: 'json',
                delay: 250,
                data: function(params) { return { q: params.term }; },
                processResults: function(data) {
                    return { results: $.map(data, function(item) { return { text: item.category_name, id: item.id }; }) };
                },
                cache: true
            },
            createTag: function(params) {
                return { id: params.term, text: params.term, newOption: true };
            },
            templateResult: function(data) {
                var $result = $("<span></span>");
                $result.text(data.text);
                if (data.newOption) { $result.append(" <em>(new)</em>"); }
                return $result;
            }
        });

        $('#category').on('select2:select', function(e) {
            var data = e.params.data;
            if (data.newOption) {
                $.ajax({
                    type: "POST",
                    url: "/page-categories-create-from-select2",
                    data: { _token: '{{ csrf_token() }}', category_name: data.text },
                    success: function(response) {
                        var selectedValues = $('#category').val().filter(function(val) {
                            return val !== data.id;
                        });
                        $('#category').val(selectedValues).trigger('change');
                        var newOption = new Option(response.category_name, response.id, true, true);
                        $('#category').append(newOption).trigger('change');
                    }
                });
            }
        });

        $('#servicecategory').select2({
            placeholder: 'Type Category',
            dropdownParent: $('#servicecategory').parent(),
            ajax: {
                url: '/product-categories-autocomplete-ajax',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return { results: $.map(data, function(item) { return { text: item.product_category_name, id: item.id } }) };
                },
                cache: true
            }
        });

        // ============================================================
        // LOCATION CASCADE: Country → State → City → Area
        // ============================================================

        // Helper: show/hide loading spinner
        function locLoading(id, show) {
            show ? $('#' + id + '-loading').addClass('show') : $('#' + id + '-loading').removeClass('show');
        }

        // Helper: lock/unlock a location group
        function lockGroup(id) { $('#' + id + '-group').addClass('location-locked'); }
        function unlockGroup(id) { $('#' + id + '-group').removeClass('location-locked'); }

        // Reset downstream dropdowns
        function resetSelect(id, label) {
            $('#' + id).html('<option value="">' + label + '</option>').trigger('change');
        }

        // ---- COUNTRY CHANGE ----
        function loadStatesForCountry(country_id, preselect_state) {
            if (!country_id || country_id == 0) return;
            resetSelect('state', 'Select State');
            resetSelect('city',  'Select City');
            resetSelect('area',  'Select Area');
            lockGroup('city'); lockGroup('area');
            locLoading('state', true);

            $.ajax({
                url: '/states-autocomplete-ajax',
                type: 'GET',
                data: { q: '', country_id: country_id },
                success: function(data) {
                    locLoading('state', false);
                    unlockGroup('state');
                    $.each(data, function(key, item) {
                        var sel = (preselect_state && item.id == preselect_state) ? ' selected' : '';
                        $('#state').append('<option value="' + item.id + '"' + sel + '>' + item.state_name + '</option>');
                    });
                    $('#state').trigger('change');
                },
                error: function() { locLoading('state', false); }
            });
        }

        $('#country').on('change', function() {
            loadStatesForCountry($(this).val(), null);
        });

        // ---- STATE CHANGE ----
        function loadCitiesForState(state_id, preselect_city) {
            if (!state_id || state_id == 0) { lockGroup('city'); return; }
            resetSelect('city', 'Select City');
            resetSelect('area', 'Select Area');
            lockGroup('area');
            locLoading('city', true);

            $.ajax({
                url: '/ajax/cities/' + state_id,
                method: 'get',
                success: function(result) {
                    locLoading('city', false);
                    unlockGroup('city');
                    let parsed = typeof result === 'string' ? (typeof result === 'string' ? JSON.parse(result) : result) : result;
                    $('#city').html('<option value="">Select City</option>');
                    $.each(parsed, function(key, value) {
                        var sel = (preselect_city && value.id == preselect_city) ? ' selected' : '';
                        $('#city').append('<option value="' + value.id + '"' + sel + '>' + value.city_name + '</option>');
                    });
                    $('#city').val(preselect_city || '').trigger('change');
                },
                error: function() { locLoading('city', false); }
            });
        }

        $('#state').on('change', function() {
            loadCitiesForState($(this).val(), null);
        });

        // ---- CITY CHANGE ----
        function loadAreasForCity(city_id, preselect_area) {
            if (!city_id || city_id == 0) { lockGroup('area'); return; }
            resetSelect('area', 'Select Area');
            locLoading('area', true);

            $.ajax({
                url: '/ajax/areas/' + city_id,
                method: 'get',
                success: function(result) {
                    locLoading('area', false);
                    unlockGroup('area');
                    let parsed = typeof result === 'string' ? (typeof result === 'string' ? JSON.parse(result) : result) : result;
                    $('#area').html('<option value="">Select Area</option>');
                    $.each(parsed, function(key, value) {
                        var sel = (preselect_area && value.id == preselect_area) ? ' selected' : '';
                        $('#area').append('<option value="' + value.id + '"' + sel + '>' + value.area_name + '</option>');
                    });
                    $('#area').val(preselect_area || '').trigger('change');
                },
                error: function() { locLoading('area', false); }
            });
        }

        $('#city').on('change', function() {
            loadAreasForCity($(this).val(), null);
        });

        // ---- ON PAGE LOAD: auto-load states if country already selected ----
        (function initLocationCascade() {
            var initCountryId = $('#country').val();
            var initStateId   = $('#old_state_id').val() || '';
            var initCityId    = $('#old_city_id').val()  || '';
            var initAreaId    = $('#old_area_id').val()  || '';

            if (initCountryId && initCountryId != '0') {
                // Lock city+area until states load
                lockGroup('city'); lockGroup('area');

                $.ajax({
                    url: '/states-autocomplete-ajax',
                    type: 'GET',
                    data: { q: '', country_id: initCountryId },
                    success: function(data) {
                        locLoading('state', false);
                        unlockGroup('state');
                        $.each(data, function(key, item) {
                            var sel = (initStateId && item.id == initStateId) ? ' selected' : '';
                            $('#state').append('<option value="' + item.id + '"' + sel + '>' + item.state_name + '</option>');
                        });
                        $('#state').trigger('change.select2'); // refresh Select2 display only

                        // If old state, auto-load cities
                        if (initStateId) {
                            loadCitiesForState(initStateId, initCityId || null);
                        }
                    }
                });
            }
        })();

        // ---- SERVICE LOCATION (same pattern) ----
        $('#servicecountry').on('change', function() {
            var country_id = $(this).val();
            $('#servicestate').html('<option value="">Select State</option>').trigger('change');
            if (country_id > 0) {
                $.ajax({
                    url: '/states-autocomplete-ajax',
                    type: 'GET',
                    data: { q: '', country_id: country_id },
                    success: function(data) {
                        $.each(data, function(key, item) {
                            $('#servicestate').append('<option value="' + item.id + '">' + item.state_name + '</option>');
                        });
                        $('#servicestate').trigger('change');
                    }
                });
            }
        });
        $('#servicestate').on('change', function() {
            $('#servicecity').html('<option value="">Select City</option>').trigger('change');
            if (this.value > 0) {
                $.ajax({ url: '/ajax/cities/' + this.value, method: 'get', success: function(result) {
                    let parsed = typeof result === 'string' ? (typeof result === 'string' ? JSON.parse(result) : result) : result;
                    $.each(parsed, function(key, value) {
                        $('#servicecity').append('<option value="' + value.id + '">' + value.city_name + '</option>');
                    });
                    $('#servicecity').trigger('change');
                }});
            }
        });
        $('#servicecity').on('change', function() {
            $('#servicearea').html('<option value="">Select Area</option>').trigger('change');
            if (this.value > 0) {
                $.ajax({ url: '/ajax/areas/' + this.value, method: 'get', success: function(result) {
                    let parsed = typeof result === 'string' ? (typeof result === 'string' ? JSON.parse(result) : result) : result;
                    $.each(parsed, function(key, value) {
                        $('#servicearea').append('<option value="' + value.id + '">' + value.area_name + '</option>');
                    });
                    $('#servicearea').trigger('change');
                }});
            }
        });

        $('#parent').on('change', function() {
            let selectedValue = $(this).val();
            $('#category_parent_id').val(selectedValue).trigger('change');
        });

    });

    // Old cascade init is now handled inside initLocationCascade() above — no duplicate needed.

    function showModal(url) {
        $('#viewListingBtn').attr('href', url);
        $('#claimModal').modal('show');
    }

    function closeModal() { $('#claimModal').modal('hide'); }


    function showtermsymodel() { $('#termsAndConditionsModal').modal('show'); }

    function showcategorymodel() { $('#categoriesModal').modal('show'); }

    function showparentcategorymodel() { $('#parentcategoriesModal').modal('show'); }

    function showcitymodel() {
        var statid = $('#state').val();
        if (statid > 0) { $('#cityModal').modal('show'); }
        else {
            Swal.fire({ icon: "error", title: "Oops...", text: "Please select state first!" });
        }
    }

    function submitcity() {
        var statid = $('#state').val();
        var city_name = $('#city_name').val();
        if (statid > 0 && city_name != "") {
            const $btn = $(event.target);
            $btn.prop('disabled', true);
            var ajax_url = "{{route('ajax.storecities')}}";
            $.ajax({
                url: ajax_url,
                method: 'POST',
                data: { statid: statid, city_name: city_name, _token: '{{csrf_token()}}' },
                success: function(result) {
                    if (result > 0) {
                        var cities_ajax_url = '/ajax/cities/' + statid;
                        $.ajax({
                            url: cities_ajax_url,
                            method: 'get',
                            success: function(cities_result) {
                                let parsed = typeof cities_result === 'string' ? (typeof cities_result === 'string' ? JSON.parse(cities_result) : cities_result) : cities_result;
                                $('#city').html("<option value=''>Select City</option>");
                                $.each(parsed, function(key, value) {
                                    $('#city').append('<option value="' + value.id + '">' + value.city_name + '</option>');
                                });
                                $('#city').val(result).trigger('change');
                            }
                        });
                        $('#cityModal').modal('hide');
                        $('#city_name').val('');
                        Swal.fire({ icon: "success", title: "Success", text: "City added successfully!" });
                    } else {
                        Swal.fire({ icon: "error", title: "Oops...", text: "City already exists!" });
                    }
                },
                error: function() {
                    Swal.fire({ icon: "error", title: "Oops...", text: "Something went wrong. Please try again." });
                },
                complete: function() {
                    $btn.prop('disabled', false);
                }
            });
        } else {
            Swal.fire({ icon: "error", title: "Oops...", text: "Please enter city name and ensure state is selected!" });
        }
    }

    function submitparentcategory() {
        var category_name = $('#parent_category_name').val();
        if (category_name != "") {
            const $btn = $(event.target);
            $btn.prop('disabled', true);
            var ajax_url = "{{route('ajax.storecategories')}}";
            $.ajax({
                url: ajax_url,
                method: 'POST',
                data: { category_name: category_name, category_parent_id: null, _token: '{{csrf_token()}}' },
                success: function(result) {
                    if (result > 0) {
                        var parent_ajax_url = "{{route('page.json.parent.catgories')}}";
                        $.ajax({
                            url: parent_ajax_url,
                            method: 'get',
                            success: function(cat_result) {
                                let parsed = typeof cat_result === 'string' ? (typeof cat_result === 'string' ? JSON.parse(cat_result) : cat_result) : cat_result;
                                $('#parent').html('<option value="">Select Parent Category</option>');
                                $.each(parsed, function(key, value) {
                                    $('#parent').append('<option value="' + value.id + '">' + value.category_name + '</option>');
                                });
                                $('#parent').val(result).trigger('change');

                                $('#category_parent_id').html('<option value="0">Add Category</option>');
                                $.each(parsed, function(key, value) {
                                    $('#category_parent_id').append('<option value="' + value.id + '">' + value.category_name + '</option>');
                                });
                            }
                        });
                        $('#parentcategoriesModal').modal('hide');
                        $('#parent_category_name').val('');
                        Swal.fire({ icon: "success", title: "Success", text: "Parent Category added successfully!" });
                    } else {
                        Swal.fire({ icon: "error", title: "Oops...", text: "Category already exists!" });
                    }
                },
                error: function() {
                    Swal.fire({ icon: "error", title: "Oops...", text: "Something went wrong. Please try again." });
                },
                complete: function() {
                    $btn.prop('disabled', false);
                }
            });
        } else {
            Swal.fire({ icon: "error", title: "Oops...", text: "Please enter category!" });
        }
    }

    function submitcategory() {
        var category_name = $('#category_name_modal').val();
        var category_parent_id = $('#category_parent_id').val();
        if (category_name != "") {
            const $btn = $(event.target);
            $btn.prop('disabled', true);
            var ajax_url = "{{route('ajax.storecategories')}}";
            $.ajax({
                url: ajax_url,
                method: 'POST',
                data: { category_name: category_name, category_parent_id: category_parent_id, _token: '{{csrf_token()}}' },
                success: function(result) {
                    if (result > 0) {
                        $('#categoriesModal').modal('hide');
                        $('#category_name_modal').val('');
                        Swal.fire({ icon: "success", title: "Success", text: "Category added successfully!" });
                    } else {
                        Swal.fire({ icon: "error", title: "Oops...", text: "Category already exists!" });
                    }
                },
                error: function() {
                    Swal.fire({ icon: "error", title: "Oops...", text: "Something went wrong. Please try again." });
                },
                complete: function() {
                    $btn.prop('disabled', false);
                }
            });
        } else {
            Swal.fire({ icon: "error", title: "Oops...", text: "Please enter category name!" });
        }
    }

    function showareamodel() {
        var cityid = $('#city').val();
        if (cityid > 0) { $('#areaModal').modal('show'); }
        else {
            Swal.fire({ icon: "error", title: "Oops...", text: "Please select city first!" });
        }
    }

    function submitarea() {
        var cityid = $('#city').val();
        var area_name = $('#area_name').val();
        if (cityid > 0 && area_name != "") {
            const $btn = $(event.target);
            $btn.prop('disabled', true);
            var ajax_url = "{{route('ajax.storeareas')}}";
            $.ajax({
                url: ajax_url,
                method: 'POST',
                data: { cityid: cityid, area_name: area_name, _token: '{{csrf_token()}}' },
                success: function(result) {
                    if (result > 0) {
                        var areas_ajax_url = '/ajax/areas/' + cityid;
                        $.ajax({
                            url: areas_ajax_url,
                            method: 'get',
                            success: function(areas_result) {
                                let parsed = typeof areas_result === 'string' ? (typeof areas_result === 'string' ? JSON.parse(areas_result) : areas_result) : areas_result;
                                $('#area').html('<option value="0">Select Area</option>');
                                $.each(parsed, function(key, value) {
                                    $('#area').append('<option value="' + value.id + '">' + value.area_name + '</option>');
                                });
                                $('#area').val(result).trigger('change');
                            }
                        });
                        $('#areaModal').modal('hide');
                        $('#area_name').val('');
                        Swal.fire({ icon: "success", title: "Success", text: "Area added successfully!" });
                    } else {
                        Swal.fire({ icon: "error", title: "Oops...", text: "Area already exists!" });
                    }
                },
                error: function() {
                    Swal.fire({ icon: "error", title: "Oops...", text: "Something went wrong. Please try again." });
                },
                complete: function() {
                    $btn.prop('disabled', false);
                }
            });
        } else {
            Swal.fire({ icon: "error", title: "Oops...", text: "Please enter area name and ensure city is selected!" });
        }
    }
</script>

{{-- ********** MULTI-STEP NAVIGATION + PROGRESS ********** --}}
<script>
    $(function() {
        let totalSteps = 5;
        let currentStep = 1;

        function goToStep(step) {
            currentStep = step;
            $('.step-section').removeClass('active');
            $('.step-section[data-step="'+step+'"]').addClass('active');

            $('.step-tab').removeClass('active');
            $('.step-tab[data-step="'+step+'"]').addClass('active');

            let percent = (step / totalSteps) * 100;
            $('#step-progress').css('width', percent + '%');

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        $('.step-tab').on('click', function() {
            let step = $(this).data('step');
            goToStep(step);
        });

        $('.btn-next').on('click', function() {
            let next = $(this).data('next');
            if (next <= totalSteps) goToStep(next);
        });

        $('.btn-prev').on('click', function() {
            let prev = $(this).data('prev');
            if (prev >= 1) goToStep(prev);
        });

        goToStep(1);

        $('#parent').on('change', function() {
            var parent_id = $(this).val();
            if (parent_id) {
                $.ajax({
                    url: '/ajax/subcategories/' + parent_id,
                    type: 'GET',
                    success: function(data) {
                        $('#category').empty();
                        var subcategories = typeof data === 'string' ? (typeof data === 'string' ? JSON.parse(data) : data) : data;
                        $.each(subcategories, function(index, subcategory) {
                            $('#category').append('<option value="' + subcategory.id + '">' + subcategory.category_name + '</option>');
                        });
                        // If you use bootstrap-select
                        if ($('#category').hasClass('selectpicker')) {
                            $('#category').selectpicker('refresh');
                        }
                        $('#category').trigger('change');
                    }
                });
            }
        });
    });

  </script>  

<script>
    
        var map = L.map('map-modal-body', { center: [20.5937, 78.9629], zoom: 5 });
        var layerGroup = L.layerGroup().addTo(map);
        var current_lat = 0;
        var current_lng = 0;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        map.on('click', function(e) {
            layerGroup.clearLayers();
            L.marker([e.latlng.lat, e.latlng.lng]).addTo(layerGroup);
            current_lat = e.latlng.lat;
            current_lng = e.latlng.lng;
            $('#lat_lng_span').text("Lat, Lng : " + e.latlng.lat + ", " + e.latlng.lng);
        });

        $('#lat_lng_confirm').on('click', function() {
            $('#item_lat').val(current_lat);
            $('#item_lng').val(current_lng);
            $('#map-modal').modal('hide')
        });

        $('.lat_lng_select_button').on('click', function() {
            $('#map-modal').modal('show');
            setTimeout(function() { map.invalidateSize() }, 500);
        });

  
  document.getElementById('map-search-btn').onclick = function(){
    let input = document.getElementById('map-search-input');
    input.style.display = (input.style.display === 'none' || input.style.display === '') ? 'block' : 'none';
    if (input.style.display === 'block') input.focus();
  };

  // Search city on Enter
  document.getElementById('map-search-input').addEventListener('keydown', function(e){
    if(e.key === 'Enter'){
      let city = this.value.trim();
      if(!city) return;

      fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(city), {
        headers: { 'Accept-Language': 'en', 'User-Agent': 'cityhangaround-local' }
      })
        .then(r => r.json())
        .then(data => {
          if(data && data.length){
            let lat = parseFloat(data[0].lat);
            let lon = parseFloat(data[0].lon);
            map.setView([lat, lon], 13);
            L.marker([lat, lon]).addTo(map);
          }else{
            alert('Location not found');
          }
        })
        .catch(err => {
          console.error(err);
          alert('Error while searching location');
        });
      }
    });

    // MEDIA & SUBMIT LOGIC
    window.previewMedia = function(input) {
        const container = $('#media-preview');
        container.empty();
        $('#uploadProgressMedia').removeClass('d-none');
        if (input.files) {
            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const isVideo = file.type.startsWith('video/');
                    const size = (file.size / (1024 * 1024)).toFixed(1) + 'MB';
                    let html = `
                        <div class="media-preview-item" id="media-item-${index}">
                            <button type="button" class="media-remove-btn" onclick="removeMediaItem(${index})"><i class="fas fa-times"></i></button>
                            ${isVideo ? `<video src="${e.target.result}"></video>` : `<img src="${e.target.result}">`}
                            <div class="media-info-badge">${size}</div>
                        </div>
                    `;
                    container.append(html);
                }
                reader.readAsDataURL(file);
            });
        }
    };

    window.removeMediaItem = function(index) {
        $(`#media-item-${index}`).remove();
    };

    window.previewDoc = function(input) {
        const container = $('#doc-preview-container');
        container.empty();
        $('#uploadProgressProof').removeClass('d-none');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const size = (file.size / 1024).toFixed(0) + 'KB';
            let html = `
                <div class="document-upload-row">
                    <div class="doc-icon"><i class="fas fa-file-alt"></i></div>
                    <div class="doc-info">
                        <div class="doc-name">${file.name}</div>
                        <div class="doc-size">${size}</div>
                    </div>
                    <button type="button" class="btn btn-sm text-danger" onclick="$('#Proof_of_Ownership').val(''); $('#doc-preview-container').empty();">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            `;
            container.append(html);
        }
    };

    window.previewLogo = function(input) {
        $('#uploadProgressLogo').removeClass('d-none');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#logo-preview').html(`<img src="${e.target.result}">`);
            }
            reader.readAsDataURL(input.files[0]);
        }
    };

    window.updateVideoBtn = function(input) {
        $('#uploadProgressVideo').removeClass('d-none');
        if (input.files && input.files[0]) {
            $('#video-file-info').html(`<i class="fas fa-check-circle text-success"></i> Video: ${input.files[0].name}`);
        }
    };

    window.updateThumbBtn = function(input) {
        if (input.files && input.files[0]) {
            const info = $('#video-file-info').text() ? $('#video-file-info').html() + '<br>' : '';
            $('#video-file-info').html(`${info}<i class="fas fa-check-circle text-success"></i> Thumbnail: ${input.files[0].name}`);
        }
    };

    // Page Name Autosuggestion Logic
    window.getPageSuggestions = function() {
        let query = $('#page_name').val();
        if (query.length > 2) {
            $.ajax({
                url: "{{ route('page.suggestions') }}",
                method: "GET",
                data: { query: query },
                success: function (data) {
                    let suggestions = '<ul class="suggestion-list">';
                    data.forEach(page => {
                        let safeTitle = page.title.replace(/'/g, "\\'");
                        suggestions += `<li class="suggestion-item" onclick="fillPageDetails(${page.id}, '${safeTitle}')">${page.title}</li>`;
                    });
                    suggestions += '</ul>';
                    if(data.length > 0) {
                        $('#suggestion-box').html(suggestions).show();
                    } else {
                        $('#suggestion-box').hide().html('');
                    }
                }
            });
        } else {
            $('#suggestion-box').hide().html('');
        }
    };

    window.fillPageDetails = function(pageId, pageTitle) {
        $('#page_name').val(pageTitle);
        $('#suggestion-box').hide().html('');
        $('#page_name').trigger('change');
    };

    $(document).on('click', function(event) {
        if (!$(event.target).closest('#page_name, #suggestion-box').length) {
            $( '#suggestion-box').hide().html('');
        }
    });

    // FORM SUBMISSION WITH PROGRESS
   $('#listing-form').on('submit', function(e) {
    e.preventDefault();

    const form = $(this);
    const url = form.attr('action');
    const formData = new FormData(this);

    // Check files
    const hasMedia = $('#media')[0].files.length > 0;
    const hasProof = $('#Proof_of_Ownership')[0].files.length > 0;
    const hasLogo  = $('#image')[0].files.length > 0;
    const hasVideo = $('#featured_video')[0].files.length > 0;

    // Reset & show only relevant progress boxes
    $('.uploadProgress').addClass('d-none')
        .find('.fg').css('stroke-dashoffset', '164').end()
        .find('.percent, .percent-label').text('0%').end()
        .find('.progress-bar').css('width', '0%').attr('aria-valuenow', 0);

    if (hasMedia) $('#uploadProgressMedia').removeClass('d-none');
    if (hasProof) $('#uploadProgressProof').removeClass('d-none');
    if (hasLogo)  $('#uploadProgressLogo').removeClass('d-none');
    if (hasVideo) $('#uploadProgressVideo').removeClass('d-none');

    // Ajax upload
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        xhr: function() {
            const xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener('progress', function(evt) {
                if (!evt.lengthComputable) return;
                const percent = Math.round((evt.loaded / evt.total) * 100);
                const offset = 164 - (164 * percent / 100);

                // Update only visible progress boxes (those with files)
                $('.uploadProgress:not(.d-none)').each(function () {
                    $(this).find('.fg').css('stroke-dashoffset', offset);
                    $(this).find('.percent, .percent-label').text(percent + '%');
                    $(this).find('.progress-bar')
                        .css('width', percent + '%')
                        .attr('aria-valuenow', percent);
                });
            }, false);
            return xhr;
        },
        success: function(response) {
            // Mark only active boxes as success
            $('.uploadProgress:not(.d-none)').each(function () {
                $(this).find('.circular-progress').addClass('success');
                $(this).find('.fg').css('stroke-dashoffset', '0');
                $(this).find('.percent').text('✓');
                $(this).find('.percent-label').text('100%');
            });

            Swal.fire({
                icon: 'success',
                title: 'Page Created Successfully!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                if (response.redirect_url) {
                    window.location.href = response.redirect_url;
                } else if (response.status === 'success') {
                    window.location.reload();
                } else {
                    window.location.href = "{{ route('profile') }}";
                }
            });
        },
        error: function(xhr) {
            $('.uploadProgress:not(.d-none) .circular-progress').addClass('error');
            $('.uploadProgress:not(.d-none) .percent').text('✕');

            let errorMsg = 'An error occurred during upload.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }

            Swal.fire({
                icon: 'error',
                title: 'Upload Failed',
                html: errorMsg
            });
        }
    });
});

</script>



