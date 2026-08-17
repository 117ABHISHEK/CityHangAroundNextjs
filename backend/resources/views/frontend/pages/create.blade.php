<link href="{{asset('assets/frontend/leafletjs/leaflet.css')}}" rel="stylesheet">

<style>

    /* Limit Select2 dropdown height to 5 items */
.limit-select2 .select2-results__options {
    max-height: 180px; /* ~5 items */
    overflow-y: auto;
}

.leaflet-container {
    height: 400px;
    width: 600px;
    max-width: 100%;
    max-height: 100%;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 13px !important;
}

.faq-form-group {
    margin-bottom: 20px;
}

.text-logo {
    color: #ff4939;
}

.btn-logo {
    background-color: #ff4939;
    color: #fff;
}

/* ===== TAB FORM UI ===== */
.form-tab {
    display: none;
}
.form-tab.active {
    display: block;
}

.tab-steps {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.tab-steps .step {
    padding: 8px 14px;
    border-radius: 4px;
    background: #eee;
    font-size: 14px;
}

.tab-steps .step.active {
    background: #ff4939;
    color: #fff;
}

.tab-actions {
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
}
.select2-container {
    width: 100% !important;
}


@media (max-width: 576px) {
    .tab-steps {
        flex-direction: column;
        gap: 8px;
    }

    .tab-steps .step {
        width: 100%;
        text-align: center;
        font-size: 13px;
    }
     label {
        font-size: 14px;
    }

    .form-control,
    .form-select {
        font-size: 14px;
        padding: 10px;
    }

    .text-logo {
        display: inline-block;
        margin-top: 6px;
    }
}
</style>

<div class="main_content">

    <!-- Page Header -->
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
                    <div class="d-flex flex-column">
                        <h4>{{ get_phrase('Add a new page') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Area -->
    <div class="row">
        <div class="col-md-12">
            <div class="eSection-wrap-2">
                <div class="eForm-layouts">

                    {{-- Error Messages --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- ===== TAB STEPS ===== -->
                    <div class="tab-steps mb-4">
                        <div class="step active">1. Basic & Category</div>
                        <div class="step">2. Address & Location</div>
                        <div class="step">3. Online & Business</div>
                        <div class="step">4. Services, Policy & FAQ</div>
                        <div class="step">5. Media & Submit</div>
                    </div>
                    <!-- ===== END TAB STEPS ===== -->

                    <!-- ===== FORM START ===== -->
                    <form method="POST" action="{{ route('admin.page.created') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- ================= TAB 1 : BASIC & CATEGORY ================= -->
<div class="form-tab active">

    <div class="row">
        <div class="col-md-6">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="item_type" value="1" checked>
                <label class="form-check-label">Regular</label>
            </div>
            <small class="form-text text-muted">
                For business that has a physical address
            </small>
        </div>

        <div class="col-md-6">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="item_type" value="0">
                <label class="form-check-label">Online</label>
            </div>
            <small class="form-text text-muted">
                For business that entirely online with no physical address
            </small>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <label>Status</label>
            <select name="item_status" class="form-select eForm-control select2">
                <option value="1">Submitted</option>
                <option value="2" selected>Published</option>
                <option value="3">Suspended</option>
            </select>
        </div>

        <div class="col-md-6">
            <label>Featured</label>
            <select name="item_featured" class="form-select eForm-control select2">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
    </div>

    <div class="mt-3">
        <label>{{ get_phrase('Page title') }}</label>
        <input type="text" name="title" class="form-control eForm-control">
    </div>

    <div class="mt-3">
        <label>{{ get_phrase('Parent category') }}</label>
        <select name="parent" id="parent" class="form-select eForm-control select2">
            <option value="0">Select Parent Category</option>
            @foreach($parent as $cat)
                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
            @endforeach
        </select>
        <a class="text-logo" onclick="showparentcategorymodel();">+ Add Category</a>
    </div>

    <div class="mt-3">
        <label>{{ get_phrase('Tag category') }}</label>
        <select name="category[]" id="category" class="form-select eForm-control select2" multiple></select>
        <a class="text-logo" onclick="showcategorymodel();">+ Add Category</a>
    </div>

    <div class="mt-3">
        <label>{{ get_phrase('Page details') }}</label>
        <textarea name="description" class="content"></textarea>
    </div>
</div>

<!-- ================= TAB 2 : ADDRESS & LOCATION ================= -->
<div class="form-tab">

    <div class="row">
        <div class="col-lg-6">
            <label>Address</label>
            <input type="text" name="address" class="form-control eForm-control">
        </div>

        <div class="col-lg-6">
            <label>Phone</label>
            <input type="text" name="item_phone" class="form-control eForm-control">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6">
            <label>Country</label>
            <select name="country" id="country" class="form-select eForm-control select2">
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-6">
            <label>State</label>
            <select name="state" id="state" class="form-select eForm-control select2"></select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6">
            <label>City</label>
            <select name="city" id="city" class="form-select eForm-control select2"></select>
        </div>

        <div class="col-lg-6">
            <label>Area</label>
            <select name="area" id="area" class="form-select eForm-control select2"></select>
        </div>
    </div>

    <div class="mt-3">
        <label>Pincode</label>
        <input type="number" name="pincode" class="form-control eForm-control">
    </div>

    <div class="row mt-3">
        <div class="col-lg-6">
            <label>Latitude</label>
            <input type="text" name="item_lat" id="item_lat" class="form-control eForm-control">
            <a class="btn btn-sm btn-primary lat_lng_select_button">Select on map</a>
        </div>

        <div class="col-lg-6">
            <label>Longitude</label>
            <input type="text" name="item_lng" id="item_lng" class="form-control eForm-control">
        </div>
    </div>
</div>

<!-- ================= TAB 3 : ONLINE & BUSINESS ================= -->
<div class="form-tab">

    <div class="row">
        <div class="col-lg-6">
            <label>Youtube Video ID</label>
            <input type="text" name="youtube_video_id" class="form-control eForm-control">
        </div>

        <div class="col-lg-6">
            <label>Website</label>
            <input type="text" name="website" class="form-control eForm-control">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6">
            <label>Business Email</label>
            <input type="text" name="business_email" class="form-control eForm-control">
        </div>

        <div class="col-lg-6">
            <label>Whatsapp URL</label>
            <input type="text" name="business_whatsapp_url" class="form-control eForm-control">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6">
            <label>Facebook</label>
            <input type="text" name="facebook" class="form-control eForm-control">
        </div>

        <div class="col-lg-6">
            <label>Twitter</label>
            <input type="text" name="twitter" class="form-control eForm-control">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6">
            <label>LinkedIn</label>
            <input type="text" name="linkedIn" class="form-control eForm-control">
        </div>
    </div>

    <div class="mt-3">
        <label>Products / Services</label>
        <select name="servicecategory[]" id="servicecategory" class="form-select eForm-control" multiple></select>
    </div>

    <div class="mt-3">
        <label>Why Visit Us</label>
        <textarea name="visitus" class="content"></textarea>
    </div>

    <div class="mt-3">
        <label>Our Story</label>
        <textarea name="our_story" class="content"></textarea>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6">
            <label>Year of Establishment</label>
            <input type="number" name="yrofest" class="form-control eForm-control">
        </div>

        <div class="col-lg-6">
            <label>Opening Hours</label>
            <input type="text" name="open_hours" class="form-control eForm-control">
        </div>
    </div>
</div>

<!-- ================= TAB 4 : SERVICES, POLICY & FAQ ================= -->
<div class="form-tab">

    <div class="row">
        <div class="col-lg-4">
            <select name="servicestate[]" id="servicestate" class="form-select eForm-control" multiple></select>
        </div>

        <div class="col-lg-4">
            <select name="servicecity[]" id="servicecity" class="form-select eForm-control select2" multiple></select>
        </div>

        <div class="col-lg-4">
            <select name="servicearea[]" id="servicearea" class="form-select eForm-control select2" multiple></select>
        </div>
    </div>

    <div class="mt-3">
        <label>Return / Refund Policy</label>
        <textarea name="policy" class="content"></textarea>
    </div>

    <div id="faq-container" class="mt-3">
        <label>FAQ</label>
        <div class="faq-form-group">
            <input type="text" name="faqs[0][question]" class="form-control mb-2">
            <textarea name="faqs[0][answer]" class="form-control"></textarea>
        </div>
    </div>

    <button type="button" id="add-faq" class="btn btn-success mt-2">Add FAQ</button>
</div>

<!-- ================= TAB 5 : MEDIA & SUBMIT ================= -->
<div class="form-tab">

    <div class="mt-3">
        <label>Business Images / Videos</label>
        <input type="file" name="media[]" multiple class="form-control">
    </div>

    <div class="mt-3">
        <label>Proof of Ownership</label>
        <input type="file" name="Proof_of_Ownership" class="form-control">
    </div>

    <div class="mt-3">
        <label>Logo</label>
        <input type="file" name="logo" class="form-control">
    </div>

    <div class="mt-3">
        <label>Cover Photo</label>
        <input type="file" name="coverphoto" class="form-control">
    </div>

    <button type="submit" class="btn btn-logo mt-4">Submit</button>
</div>

<div class="tab-actions">
    <button type="button" class="btn btn-secondary" id="prevBtn">Back</button>
    <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
</div>

<script src="{{asset('assets/frontend/leafletjs/leaflet.js')}}"></script>
<script src="{{asset('assets/frontend/leafletjs/leaflet-search.js')}}"></script>

<script>
/* ================= TAB NAVIGATION (CLICK + NEXT/BACK) ================= */
let currentTab = 0;
const tabs = document.querySelectorAll('.form-tab');
const steps = document.querySelectorAll('.tab-steps .step');

function showTab(index) {
    if (index < 0 || index >= tabs.length) return;

    tabs.forEach(t => t.classList.remove('active'));
    steps.forEach(s => s.classList.remove('active'));

    tabs[index].classList.add('active');
    steps[index].classList.add('active');

    currentTab = index;

    document.getElementById('prevBtn').style.display =
        index === 0 ? 'none' : 'inline-block';

    document.getElementById('nextBtn').style.display =
        index === tabs.length - 1 ? 'none' : 'inline-block';
}

/* NEXT BUTTON */
document.getElementById('nextBtn').addEventListener('click', function () {
    showTab(currentTab + 1);

    if (currentTab === 1) {
        setTimeout(function () {
            if (typeof map !== 'undefined') {
                map.invalidateSize();
            }
        }, 400);
    }
});


/* BACK BUTTON */
document.getElementById('prevBtn').addEventListener('click', function () {
    showTab(currentTab - 1);
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

/* CLICK ON TAB HEADERS */
steps.forEach((step, index) => {
    step.style.cursor = 'pointer';
    step.addEventListener('click', function () {
        showTab(index);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    steps.forEach((step, index) => {
    step.style.cursor = 'pointer';
    step.addEventListener('click', function () {
        showTab(index);

        // 🔥 FIX: refresh map when Location tab opens
        if (index === 1) { // Tab 2 = Address & Location
            setTimeout(function () {
                if (typeof map !== 'undefined') {
                    map.invalidateSize();
                }
            }, 400);
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});

});

/* INIT */
showTab(currentTab);




document.getElementById('nextBtn').onclick = () => {
    if (currentTab < tabs.length - 1) {
        currentTab++;
        showTab(currentTab);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};

document.getElementById('prevBtn').onclick = () => {
    if (currentTab > 0) {
        currentTab--;
        showTab(currentTab);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};

showTab(currentTab);

/* ================= FAQ ADD ================= */
let faqIndex = 1;
document.getElementById('add-faq').addEventListener('click', function () {
    const container = document.getElementById('faq-container');
    const faqGroup = document.createElement('div');
    faqGroup.classList.add('faq-form-group');
    faqGroup.innerHTML = `
        <input type="text" name="faqs[${faqIndex}][question]" class="form-control mb-2">
        <textarea name="faqs[${faqIndex}][answer]" class="form-control"></textarea>
    `;
    container.appendChild(faqGroup);
    faqIndex++;
});

/* ================= SELECT2 + AJAX ================= */
$(document).ready(function () {
    $('.select2').select2();

    $('#state').select2({
        ajax: {
            url: '/states-autocomplete-ajax',
            dataType: 'json',
            delay: 250,
            processResults: data => ({
                results: data.map(item => ({ id: item.id, text: item.state_name }))
            })
        }
    });

    $('#servicestate').select2({
        ajax: {
            url: '/states-autocomplete-ajax',
            dataType: 'json',
            delay: 250,
            processResults: data => ({
                results: data.map(item => ({ id: item.id, text: item.state_name }))
            })
        }
    });

    $('#servicecategory').select2({
        ajax: {
            url: '/product-categories-autocomplete-ajax',
            dataType: 'json',
            delay: 250,
            processResults: data => ({
                results: data.map(item => ({ id: item.id, text: item.product_category_name }))
            })
        }
    });

    $('#servicecity').select2({
        ajax: {
            url: '/city-autocomplete-ajax',
            dataType: 'json',
            delay: 250,
            data: params => ({
                search: params.term,
                selectedStates: $('#servicestate').val()
            }),
            processResults: data => ({
                results: data.map(item => ({ id: item.id, text: item.city_name }))
            })
        }
    });

    $('#servicearea').select2({
        ajax: {
            url: '/area-autocomplete-ajax',
            dataType: 'json',
            delay: 250,
            data: params => ({
                search: params.term,
                selectedStates: $('#servicecity').val()
            }),
            processResults: data => ({
                results: data.map(item => ({ id: item.id, text: item.area_name }))
            })
        }
    });

  $('#category').select2({
    placeholder: 'Type to search tag category',
    minimumInputLength: 1,
    ajax: {
        url: '/categories-autocomplete-ajax',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            return {
                results: $.map(data, function (item) {
                    return {
                        id: item.id,
                        text: item.category_name
                    }
                })
            };
        },
        cache: true
    }
});



    $('#parent').select2({
    placeholder: 'Search parent category',
    allowClear: true,
    width: '100%',
    dropdownAutoWidth: true,
    dropdownCssClass: 'limit-select2',
});

});

/* ================= MAP ================= */
let map = L.map('map-modal-body', { center: [20.5937, 78.9629], zoom: 5 });
let layerGroup = L.layerGroup().addTo(map);
let current_lat = 0;
let current_lng = 0;

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

map.on('click', function (e) {
    layerGroup.clearLayers();
    L.marker([e.latlng.lat, e.latlng.lng]).addTo(layerGroup);
    current_lat = e.latlng.lat;
    current_lng = e.latlng.lng;
});

$('.lat_lng_select_button').on('click', function () {
    $('#map-modal').modal('show');
    setTimeout(() => map.invalidateSize(), 400);
});

$('#lat_lng_confirm').on('click', function () {
    $('#item_lat').val(current_lat);
    $('#item_lng').val(current_lng);
    $('#map-modal').modal('hide');
});
</script>
