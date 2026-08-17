<div class="modal fade" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content enquiry-modal-content">

            <div class="modal-header enquiry-modal-header">
                <div class="d-flex align-items-center">
                    <div class="header-icon-wrapper me-3">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div>
                        <h5 class="modal-title enquiry-modal-title" id="enquiryModalLabel">Send Enquiry</h5>
                        <p class="enquiry-modal-subtitle">Connect with top sellers instantly</p>
                    </div>
                </div>
                <button type="button" class="btn-close enquiry-close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body enquiry-modal-body">
                <form id="enquiryForm" method="POST" class="needs-validation">
                    @csrf

                    <div class="form-field-wrapper mb-3">
                        <label class="form-label-custom"><i class="fas fa-user-circle me-2"></i>Full Name</label>
                        <div class="input-group-custom">
                            <input type="text" id="name" class="form-input-custom" placeholder="Enter your full name" required>
                        </div>
                    </div>

                    <div class="form-field-wrapper mb-3">
                        <label class="form-label-custom"><i class="fas fa-phone-alt me-2"></i>Mobile Number</label>
                        <div class="input-group-custom">
                            <input type="tel" id="mobile" class="form-input-custom" pattern="[0-9]{10}" placeholder="Enter 10-digit mobile number" required>
                        </div>
                    </div>

                    <div class="form-field-wrapper mb-3">
                        <label class="form-label-custom"><i class="fas fa-map-marker-alt me-2"></i>Location (City, Area)</label>
                        <div class="select-wrapper-custom">
                            <select id="city_modal" class="form-control select2" required>
                                <option value="">Select Location</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-field-wrapper mb-4">
                        <label class="form-label-custom"><i class="fas fa-shopping-bag me-2"></i>Product / Service</label>
                        <div class="select-wrapper-custom">
                            <select id="product" class="form-control select2" required>
                                <option value="">Select Product</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-enquiry-submit w-100">
                        <span>Submit Enquiry</span>
                        <i class="fas fa-arrow-right ms-2 btn-arrow"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
/* CUSTOM PREMIUM ENQUIRY MODAL STYLING */
.enquiry-modal-content {
    border: none !important;
    border-radius: 20px !important;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15) !important;
    overflow: hidden !important;
    background: #fff !important;
    font-family: 'Outfit', 'Inter', sans-serif !important;
}

.enquiry-modal-header {
    background: linear-gradient(135deg, #ff4d4d, #f857a6) !important;
    padding: 24px 28px !important;
    border-bottom: none !important;
    position: relative !important;
}

.header-icon-wrapper {
    background: rgba(255, 255, 255, 0.2) !important;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.enquiry-modal-title {
    color: #fff !important;
    font-weight: 700 !important;
    font-size: 20px !important;
    margin: 0 !important;
    line-height: 1.2 !important;
}

.enquiry-modal-subtitle {
    color: rgba(255, 255, 255, 0.85) !important;
    font-size: 13px !important;
    margin: 4px 0 0 0 !important;
}

.enquiry-close-btn {
    position: absolute !important;
    top: 24px !important;
    right: 24px !important;
    background: rgba(255, 255, 255, 0.15) !important;
    border: none !important;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    color: #fff !important;
    display: flex !important;
    align-items: center !justify-content;
    justify-content: center !important;
    font-size: 14px !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    opacity: 1 !important;
}

.enquiry-close-btn:hover {
    background: rgba(255, 255, 255, 0.3) !important;
    transform: rotate(90deg) !important;
}

.enquiry-modal-body {
    padding: 28px !important;
    background: #fdfdfd !important;
}

.form-label-custom {
    display: block !important;
    font-weight: 600 !important;
    color: #4a5568 !important;
    font-size: 14px !important;
    margin-bottom: 8px !important;
}

.form-input-custom {
    width: 100% !important;
    height: 48px !important;
    padding: 10px 18px !important;
    border-radius: 12px !important;
    border: 1.5px solid #e2e8f0 !important;
    background: #fff !important;
    color: #2d3748 !important;
    font-size: 15px !important;
    transition: all 0.3s ease !important;
    outline: none !important;
}

.form-input-custom:focus {
    border-color: #ff4d4d !important;
    box-shadow: 0 0 0 3px rgba(255, 77, 77, 0.15) !important;
    background: #fff !important;
}

/* FORCE HIDE THE RAW SELECT ELEMENTS ONCE SELECT2 STYLES THEM */
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

/* CUSTOM SELECT2 MATCHING STYLE */
#enquiryModal .select2-container--default .select2-selection--single {
    height: 48px !important;
    border-radius: 12px !important;
    border: 1.5px solid #e2e8f0 !important;
    padding: 10px 18px !important;
    background: #fff !important;
    outline: none !important;
    transition: all 0.3s ease !important;
    display: flex !important;
    align-items: center !important;
}

#enquiryModal .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: normal !important;
    padding-left: 0 !important;
    color: #2d3748 !important;
    font-size: 15px !important;
}

#enquiryModal .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 48px !important;
    right: 12px !important;
}

#enquiryModal .select2-container--default.select2-container--focus .select2-selection--single,
#enquiryModal .select2-container--default.select2-container--open .select2-selection--single {
    border-color: #ff4d4d !important;
    box-shadow: 0 0 0 3px rgba(255, 77, 77, 0.15) !important;
}

/* Select2 Dropdown Styling */
.select2-container--open .select2-dropdown {
    border: 1.5px solid #ff4d4d !important;
    border-radius: 12px !important;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1) !important;
    overflow: hidden !important;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #ff4d4d !important;
    color: white !important;
}

.btn-enquiry-submit {
    background: linear-gradient(135deg, #ff4d4d, #f857a6) !important;
    border: none !important;
    height: 52px !important;
    border-radius: 14px !important;
    color: #fff !important;
    font-weight: 700 !important;
    font-size: 16px !important;
    box-shadow: 0 6px 20px rgba(255, 77, 77, 0.25) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.3s ease !important;
    cursor: pointer !important;
}

.btn-enquiry-submit:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 10px 25px rgba(255, 77, 77, 0.35) !important;
}

.btn-enquiry-submit:active {
    transform: translateY(1px) !important;
}

.btn-arrow {
    transition: transform 0.3s ease !important;
}

.btn-enquiry-submit:hover .btn-arrow {
    transform: translateX(4px) !important;
}

/* Responsive tweaks */
@media (max-width: 576px) {
    .enquiry-modal-content {
        margin: 12px !important;
    }
    .enquiry-modal-header {
        padding: 20px 24px !important;
    }
    /* Add extra padding-bottom on mobile to ensure submit button is not covered by bottom app navigation bar */
    .enquiry-modal-body {
        padding: 24px 24px 80px 24px !important;
    }
}
</style>