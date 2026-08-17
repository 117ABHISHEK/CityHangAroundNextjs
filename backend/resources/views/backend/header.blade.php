<div class="home-header">
  <div class="row w-100 justify-content-between align-items-center">

    <div class="col-auto d-flex">
      <div class="sidebar_menu_icon">
        <div class="menuList">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="12" viewBox="0 0 15 12">
            <path
              id="Union_5"
              data-name="Union 5"
              d="M-2188.5,52.5v-2h15v2Zm0-5v-2h15v2Zm0-5v-2h15v2Z"
              transform="translate(2188.5 -40.5)"
              fill="#6e6f78" />
          </svg>
        </div>
      </div>

      <div class="ms-4">
        <a href="{{ route('timeline') }}" class="btn btn-outline-primary" data-bs-toggle="tooltip"
          data-bs-title="{{ get_phrase('Visit Website') }}">
          <i class="bi bi-globe d-inline-block d-md-none"></i>
          <span class="d-md-inline-block d-none">{{ get_phrase('Visit Website') }}</span>
        </a>
      </div>
      
      

      <div class="ms-4">
    <button
        type="button"
        class="btn btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#enquiryModal"
        data-bs-title="{{ get_phrase('Add Enquiry') }}"
        style="border: none;">
        
        <!-- Icon on mobile -->
        <i class="bi bi-plus-lg d-inline-block d-md-none"></i>

        <!-- Text on desktop -->
        <span class="d-md-inline-block d-none">
            {{ get_phrase('Add Enquiry') }}
        </span>
    </button>
</div>
    </div>

    <!-- Language Dropdown -->
    <div class="col-auto d-xl-block d-none">
      <ul class="header-menu">
        <li class="user-profile pe-2">
          <select class="form-control text-capitalize text-13px py-1 me-2"
           onchange="window.location.href='/language/switch/'+this.value;" style="margin-top: 7px;">
            @foreach(get_all_language() as $language)
              <option value="{{$language->name}}"
                @if($language->name == Session('active_language')) selected @endif>
                {{$language->name}}
              </option>
            @endforeach
          </select>
        </li>
      </ul>
    </div>




    <!-- USER MENU -->
    @if($authenticatedUser)
    <div class="col-auto">
      <div class="header-menu">
        <ul>

        

        <!-- HEADER ICONS -->
<li class="header-icons d-flex align-items-center">

    <!-- Friends -->
    <a href="https://cityhangaround.com/profile/friends" class="icon-btn">
        <i class="bi bi-people-fill"></i>
    </a>

    <!-- Messages -->
    <a href="https://cityhangaround.com/profile/friends" class="icon-btn">
        <i class="bi bi-chat-dots-fill"></i>
    </a>

    <!-- Notifications -->
    <a href="https://cityhangaround.com/all/notification" class="icon-btn">
        <i class="bi bi-bell-fill"></i>
    </a>

 

</li>




          <li class="user-profile">
            <div class="btn-group">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="defaultDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">

                <img src="{{ get_user_image($authenticatedUser->photo,'optimized') }}" height="42px" />
                <div class="px-2 text-start">
                  <span class="user-name">{{ $authenticatedUser->name }}</span>
                  <span class="user-title">{{ $authenticatedUser->user_role }}</span>
                </div>

              </button>

              <ul class="dropdown-menu dropdown-menu-end eDropdown-menu" aria-labelledby="defaultDropdown">

                <li class="user-profile user-profile-inner">
                  <button class="btn w-100 d-flex align-items-center" type="button">
                    <img class="radious-5px"
                      src="{{ get_user_image($authenticatedUser->photo,'optimized') }}" height="42px" />
                    <div class="px-2 text-start">
                      <span class="user-name">{{ $authenticatedUser->name }}</span>
                      <span class="user-title">{{ $authenticatedUser->user_role }}</span>
                    </div>
                  </button>
                </li>

                <li>
                  <a class="dropdown-item" href="{{ route('profile.load_my_profile') }}">
                    {{ get_phrase('My Account') }}
                  </a>
                </li>

                <li>
                  <a class="dropdown-item" href="{{ route('admin.change.password') }}">
                    {{ get_phrase('Change Password') }}
                  </a>
                </li>

                <li>
                  <a class="dropdown-item" href="{{ route('admin.system.settings.view') }}">
                    {{ get_phrase('Settings') }}
                  </a>
                </li>

                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn eLogut_btn" type="submit">
                      {{ get_phrase('Log out') }}
                    </button>
                  </form>
                </li>

              </ul>
            </div>
          </li>

        </ul>
      </div>
    </div>
    @endif

  </div>
</div>



<!-- ENQUIRY MODAL -->
<div class="modal fade" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="enquiryModalLabel">Enquiry Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">x</button>
            </div>

            <div class="modal-body">
                <form id="enquiryForm" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" id="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mobile No</label>
                        <input type="tel" id="mobile" class="form-control" pattern="[0-9]{10}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">City</label>
                        <select id="city_modal" class="form-control select2" required>
                            <option value="">Select City</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Product</label>
                        <select id="product" class="form-control select2" required>
                            <option value="">Select Product</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">Submit</button>
                </form>
            </div>

        </div>
    </div>
</div>



<style>
  @media (max-width: 452px) {
    .home-header .user-name,
    .home-header .user-title,
    .home-header .px-2.text-start { display: none !important; }
    .home-header .dropdown-toggle { padding-left: 0.5rem; padding-right: 0.5rem; }
    .home-header select.form-control { font-size: 12px; padding: 2px 4px; margin-top: 4px !important; }
    .home-header .ms-4 { margin-left: 0.5rem !important; }
    .home-header .btn-outline-primary { font-size: 12px; padding: 3px 6px; }
    .home-header .user-profile { padding-right: 0 !important; }
  }

.header-icons a.icon-btn {
    width: 40px;
    height: 40px;
    background: #6c6f78;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    margin-right: 10px;
    font-size: 18px;
    text-decoration: none;
}

.header-icons .icon-btn.icon-red {
    background: #ff3b30 !important;
}

.header-icons .icon-btn:hover {
    color: #ffffff;
    background: #6c6f78;
}

.header-icons .icon-btn.icon-red:hover {
    background: #ff3b30 !important;
}
</style>



<!-- ENQUIRY MODAL JS -->
<script>
$(document).ready(function () {

    function initDynamicSelect2(selector, url, dropdownParent, placeholder, formatData, minInputLength = 0) {
        $(selector).select2({
            placeholder: placeholder,
            allowClear: true,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: formatData,
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return { id: item.id, text: item.text || item.title || item.city_name };
                        })
                    };
                },
                cache: false,
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error loading data for ' + selector, textStatus, errorThrown);
                }
            },
            width: '100%',
            dropdownParent: $(dropdownParent),
            minimumInputLength: minInputLength,
            language: {
                noResults: function() { return "No results found"; },
                searching: function() { return "Searching..."; }
            }
        });
    }

    // Load Select2 Inside Modal
    $('#enquiryModal').on('shown.bs.modal', function () {
        if ($('#city_modal').hasClass('select2-hidden-accessible')) {
            $('#city_modal').select2('destroy');
        }
        if ($('#product').hasClass('select2-hidden-accessible')) {
            $('#product').select2('destroy');
        }

        initDynamicSelect2('#city_modal', "{{ route('ajax.cities.enquiry') }}", '#enquiryModal', 'Select City', function(params) {
            return { q: params.term };
        }, 0);

        initDynamicSelect2('#product', "{{ route('ajax.products') }}", '#enquiryModal', 'Select Product', function(params) {
            return { q: params.term, location: $('#city_modal').val() };
        }, 0);
    });

    $('#city_modal').on('change', function() {
        let locationVal = $(this).val();
        $('#product').val(null).trigger('change');
        if (locationVal) {
            $('#product').prop('disabled', false).trigger('change');
        } else {
            $('#product').prop('disabled', true).trigger('change');
        }
    });

    $('#enquiryModal').on('hidden.bs.modal', function() {
        if ($('#enquiryForm').length) $('#enquiryForm')[0].reset();
        if ($('#city_modal').hasClass('select2-hidden-accessible')) {
            $('#city_modal').val(null).trigger('change');
            $('#city_modal').select2('destroy');
        }
        if ($('#product').hasClass('select2-hidden-accessible')) {
            $('#product').val(null).trigger('change');
            $('#product').select2('destroy');
        }
        $('#product').prop('disabled', true);
    });

    // Submit form
    $('#enquiryForm').submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('enquiry.store') }}",
            method: "POST",
            data: {
                name: $('#name').val(),
                mobile: $('#mobile').val(),
                city_id: $('#city_modal').val(),
                product_id: $('#product').val(),
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                Swal.fire('Success!', response.message, 'success');
                $('#enquiryModal').modal('hide');
                $('#enquiryForm')[0].reset();
            },
            error: function () {
                Swal.fire('Error', 'Please fill all fields correctly.', 'error');
            }
        });

    });

});
</script>











































