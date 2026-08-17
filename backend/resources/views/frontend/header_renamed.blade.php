
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-PZ8171LDK1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-PZ8171LDK1');
</script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-P88PMC');</script>
<!-- End Google Tag Manager -->
</head>

<!-- header -->

<!-- Include Mobile Header -->
@include('frontend.mobile_header')

<link rel="stylesheet" href="{{asset('assets/frontend/css/header_new.css')}}?v={{ time() }}">
<link rel="stylesheet" href="{{asset('assets/frontend/css/custom_header.css')}}?v={{ time() }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<!-- Include Select2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
 <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    
<div class="custom-progress-bar">
    <div class="custom-progress"></div>
</div>
<style>
    .select2-results__option {
   
    font-weight: bold;
}
</style>
<!-- Additional CSS Fix -->
<style>
 .main.my-4{margin-top:0px !important;}
 
 
 
    /* Make Select2 height match Bootstrap input */
    .select2-container .select2-selection--single {
        height: calc(2.25rem + 2px) !important;
        padding: 0.375rem 0.75rem !important;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(2.25rem + 2px) !important;
    }
    .select2-dropdown {
        z-index: 99999 !important;
    }
    
    /* Additional Select2 z-index fixes for header_new1 */
    .select2-container {
        z-index: 99999 !important;
    }
    
    .select2-container--open {
        z-index: 99999 !important;
    }
    
    .select2-container--open .select2-dropdown {
        z-index: 99999 !important;
    }
    
    .select2-results__options {
        z-index: 99999 !important;
    }
    
    .select2-results__option {
        z-index: 99999 !important;
    }
    
    /* Ultra-high z-index for all Select2 elements */
    .select2-container,
    .select2-dropdown,
    .select2-results__options,
    .select2-results__option,
    .select2-container--default,
    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-dropdown {
        z-index: 99999 !important;
    }
    
    /* Force Select2 to be above everything */
    .header .select2-container,
    .header .select2-dropdown,
    .header .select2-results__options {
        z-index: 99999 !important;
    }
    
    /* Specific z-index for search elements */
    .sc-search-new .select2-container,
    .sc-search-new .select2-dropdown {
        z-index: 99999 !important;
    }
    
    /* Ensure search container has high z-index */
    .sc-search-new {
        z-index: 99999 !important;
    }
    
    /* Force all Select2 elements to be above everything */
    *[class*="select2"] {
        z-index: 99999 !important;
    }
    
    /* Ensure search input elements are visible */
    .sc-search-new input,
    .sc-search-new select,
    .sc-search-new #search-box,
    .sc-search-new #city_header_main {
        z-index: 99999 !important;
    }
    
    /* Ensure search container and its children are visible */
    .sc-search-new,
    .sc-search-new * {
        z-index: 99999 !important;
    }
    
    /* Specific z-index for search form elements */
    .header-search,
    .header-search * {
        z-index: 99999 !important;
    }
    
    /* Ensure search section is visible */
    .header-search-section {
        z-index: 99999 !important;
    }
    
    /* Ensure Select2 search input is visible */
    .select2-search__field {
        z-index: 99999 !important;
    }
    
    /* Ensure Select2 selection is visible */
    .select2-selection {
        z-index: 99999 !important;
    }
    
    /* Ensure Select2 rendered text is visible */
    .select2-selection__rendered {
        z-index: 99999 !important;
    }
    
    /* Force all form elements in search to be visible */
    .sc-search-new form,
    .sc-search-new form * {
        z-index: 99999 !important;
    }
    
    /* Select2 search dropdown styling */
    .select2-search--dropdown {
        display: block;
        padding: 4px;
        margin-top: 42px;
    }
    
    /* Hide header_new1 on mobile devices */
    @media (max-width: 767px) {
        .header {
            display: none !important;
        }
    }

    /* Modal Close Button (Cross Icon) */
    .btn-close {
        color: #000 !important;
        background: none;
        border: none;
        font-size: 1.5rem;
        opacity: 1 !important;
    }

    .btn-close:hover {
        color: #f00;
        opacity: 0.8;
    }

    /* Modal z-index */
    .modal-backdrop {
        z-index: 1040 !important;
    }

    .modal {
        z-index: 1050 !important;
    }
    
     #cityDropdown .select2{width: 100% !important;}
      .select2-search--dropdown{margin-top: 0px;}
   .search-form .select2{display: none;}
</style>
<div class="desktop-header">
   <header
      class="top-0 left-0 w-full flex items-center bg-white px-5 py-2 shadow-md z-[1000] h-20"
    >
      <!-- Logo -->
      <div class="flex items-center w-auto pr-6">
         @php
            $system_light_logo = \App\Models\Setting::where('type', 'system_light_logo')->value('description');
        @endphp
       
        <img
          src="{{ get_system_logo_favicon($system_light_logo,'light') }}"
          alt="Logo"
          class="h-12"
        />
      </div>

      <!-- Center Content -->
      <div class="lg:flex justify-center flex-1">
        <div class="flex items-center gap-4">
        
          <div
            class="header-custom-search flex items-center w-[560px] bg-[#f9f9f9] rounded-2xl px-5 py-3"
            style="
              box-shadow: 4px 4px 20px rgba(0, 0, 0, 0.15),
                -4px -4px 12px rgba(255, 255, 255, 0.8);
            "
          >
            <!-- Location Dropdown -->
            <div class="relative">
              <button
                id="cityDropdownButton"
                class="flex items-center gap-2 text-gray-700 focus:outline-none"
                onclick="toggleCityDropdown()"
              >
                <i class="fas fa-map-marker-alt text-[#ff4939] text-lg"></i>
               @php
                    // Get the current city slug from URL
                    $currentCitySlug = request()->segment(1);
                
                    // Find matching city object from $all_cities
                    $currentCity = collect($all_cities)->firstWhere('city_slug', $currentCitySlug);
                
                    // Default city name if not found
                    $cityName = $currentCity ? $currentCity->city_name : 'Ahmedabad';
                @endphp
                
                <span id="selectedCity" class="font-semibold text-gray-800">
                    {{ $cityName }}
                </span>
                <i class="fas fa-chevron-down text-gray-500 text-sm ml-1"></i>
              </button>

              <!-- Dropdown Menu -->
              <div
                id="cityDropdown"
                class="absolute z-50 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-md hidden"
              >
                   <select name="city_header_main" id="city_header_main" required>
                    <option value="">Select City</option>
                    @foreach ($all_cities as $key => $city)
                        <option value="{{ $city->city_slug }}">{{ $city->city_name }}</option>
                    @endforeach
                </select>
                <!--<ul class="py-1 text-gray-700">-->
                <!--   @foreach ($all_cities as $key => $city)-->
                <!--      <li>-->
                <!--        <a-->
                <!--         href="/{{ $city->city_slug }}"-->
                <!--          class="block px-4 py-2 hover:bg-gray-100"-->
                <!--          >{{ $city->city_name }}</a-->
                <!--        >-->
                <!--      </li>-->
                <!--  @endforeach-->
                 
                 
                <!--</ul>-->
              </div>
            </div>

            <!-- Divider -->
            <div class="h-5 border-l border-gray-300 mx-4"></div>

            <!-- Search -->
            <div class="flex items-center flex-1 text-gray-600">
              <i class="fas fa-search mr-2 text-gray-400"></i>
                <form id="search-form">

               <input
                type="text"
                id="search-box"
                name="search"
                placeholder="Search for 'Restaurant' , 'Business' "
                class="flex-1 bg-transparent outline-none text-sm placeholder-gray-500"
              />
               </form>
            </div>
          </div>
         
        </div>
      </div>

      <!-- Right Actions -->
      <nav class="lg:flex items-center gap-2">
        <!-- Dropdown: For Business -->
        <div class="relative group cursor-pointer">
          <div class="font-medium px-3 py-2">{{ get_phrase('For Business') }} ▾</div>
          <div
            class="absolute top-full left-0 bg-white shadow-lg rounded-md hidden group-hover:block z-[1000] w-56"
          >
            <a href="#" class="block px-5 py-2 hover:bg-gray-100"
              >{{ get_phrase('Add Business') }}
              <span
                class="bg-orange-600 text-white text-[10px] px-2 py-1 rounded"
                >NEW</span
              ></a
            >
            <a href="#" class="block px-5 py-2 hover:bg-gray-100">Listings</a>
            <a href="#" class="block px-5 py-2 hover:bg-gray-100"
              >Explore Business</a
            >
            <a href="#" class="block px-5 py-2 hover:bg-gray-100"
              >List your Product</a
            >
            <a href="#" class="block px-5 py-2 hover:bg-gray-100"
              >About Business</a
            >
          </div>
        </div>

        <!-- Dropdown: For City Guide -->
        <div class="relative group cursor-pointer">
          <div class="font-medium px-3 py-2"> {{ get_phrase('City Guide') }} ▾</div>
          <div
            class="absolute top-full left-0 bg-white shadow-lg rounded-md hidden group-hover:block z-[1000] w-56"
          >
            <a href="#" class="block px-5 py-2 hover:bg-gray-100"
              >{{ get_phrase('Post Requirement') }}
              <span
                class="bg-orange-600 text-white text-[10px] px-2 py-1 rounded"
                >NEW</span
              ></a
            >
            <a href="#" class="block px-5 py-2 hover:bg-gray-100"
              >Local Business</a
            >
            <a href="#" class="block px-5 py-2 hover:bg-gray-100"
              >Service Providers</a
            >
            <a href="#" class="block px-5 py-2 hover:bg-gray-100"
              >Explore city</a
            >
            <!-- <a href="#" class="block px-5 py-2 hover:bg-gray-100">ABDM</a> -->
          </div>
        </div>

        <!-- Login Button -->

        <!-- Chat Button -->
        <button class="text-gray-700 hover:bg-gray-100 p-2 rounded-md">
          <i class="fas fa-comment-dots text-[#636262]"></i>
        </button>

        <!-- Notification Button -->
        <button class="text-gray-700 hover:bg-gray-100 p-2 rounded-md">
          <i class="fas fa-bell text-[#636262]"></i>
        </button>
       
        <!-- <a
          href="{{ route('login') }}"
          class="bg-[#ff4939] text-white px-4 py-2 rounded-md text-sm"
        >
          <i class="fas fa-sign-in-alt mr-2"></i> {{ get_phrase('Login/SignUp') }}
        </a> -->
              @if(auth()->user())
        <div class="dropdown profile-control">
                <button class="btn dropdown-toggle p-0 border-0 bg-transparent" type="button" id="dropdownMenuButton1"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    @if(auth()->user())
                        <img src="{{ get_user_image(auth()->user()->photo,'optimized') }}" class="rounded-circle" width="40" height="40" alt="">
                    @else
                        <img src="{{ get_user_image('','optimized') }}" class="rounded-circle" width="40" height="40" alt="">
                    @endif
                </button>
           
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                    
                        <li><a class="dropdown-item" href="{{ route('profile') }}">{{ get_phrase('My Profile') }}</a></li>

                        @if (auth()->user()->user_role == "admin")
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">{{ get_phrase('Go to admin panel') }}</a></li>
                        @endif

                        @if (auth()->user()->user_role == "general")
                            <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">{{ get_phrase('Dashboard') }}</a></li>
                        @endif

                        <li><a class="dropdown-item" href="{{ route('user.password.change') }}">{{ get_phrase('Change Password') }}</a></li>
                        
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">{{ get_phrase('Log Out') }}</button>
                            </form>
                        </li>
                         </ul>
            </div>
             @else
                <a
              href="{{ route('login') }}"
              class="header-btn-cus bg-[#ff4939] text-white px-4 py-2 rounded-md text-sm">
              <i class="fas fa-sign-in-alt mr-2"></i> {{ get_phrase('Login/SignUp') }}
            </a> 
            @endif


      </nav>
    </header>
</div>










<div class="mobile-header">
   <!-- Sidebar -->
    <div class="sidebar-overlay" id="sidebar">
      <div class="sidebar-header">
        <span class="close-btn" onclick="toggleSidebar()">×</span>
      </div>
      <ul class="main-menu">
        <li class="has-mega-menu">
          <a href="#">City Guide</a>
          <div class="mega-menu">
            <div class="mega-column">
              <h4>Design</h4>
              <a href="#">Web Design</a>
              <a href="#">Design Systems</a>
              <a href="#">Illustration Design</a>
              <a href="#">Motion Design</a>
              <a href="#">Branding</a>
            </div>
            <div class="mega-column">
              <h4>Development</h4>
              <a href="#">Frontend Development</a>
              <a href="#">Backend Development</a>
            </div>
          </div>
        </li>
        <li class="has-mega-menu">
          <a href="#">Marketplace</a>
          <div class="mega-menu">
            <div class="mega-column">
              <h4>Software</h4>
              <a href="#">CRM</a>
              <a href="#">ERP</a>
              <a href="#">Inventory</a>
            </div>
            <div class="mega-column">
              <h4>Industries</h4>
              <a href="#">Healthcare</a>
              <a href="#">Education</a>
            </div>
          </div>
        </li>
        <li class="has-mega-menu">
          <a href="#">Community</a>
          <div class="mega-menu">
            <div class="mega-column">
              <h4>About</h4>
              <a href="#">Who We Are</a>
              <a href="#">Careers</a>
            </div>
            <div class="mega-column">
              <h4>Contact</h4>
              <a href="#">Support</a>
            </div>
          </div>
        </li>
        <li><a href="#">Event</a></li>
        <li><a href="#">Blog</a></li>
      </ul>
    </div>

    <!-- Header -->
    <header>
      <!-- First Header -->

      <div class="first-header">
        <div class="left-side">
             @php
              $system_light_logo = \App\Models\Setting::where('type', 'system_light_logo')->value('description');
             @endphp
          <div class="logo">
            <img src="{{ get_system_logo_favicon($system_light_logo,'light') }}" alt="logo" class="mobile-logo-img" />
          </div>
        </div>
        <div class="input-container">
          <!-- Location Input -->
          <div class="input-box" style="padding: 0;padding-left: 7px;padding-right: 5px;box-shadow: 4px 4px 20px rgba(0, 0, 0, 0.15), -4px -4px 12px rgba(255, 255, 255, 0.8);">
            <i class="fas fa-map-marker-alt" style="color: #e03d2f"></i>
            <select name="city_slug" id="mobile_city_slug" style="background: unset;height: 100%;padding: 6px;width: 110px;">

                <option value="">Select City</option>

                @foreach ($all_cities as $city)

                    <option value="{{ $city->city_slug }}">{{ $city->city_name }}</option>

                @endforeach

            </select>
           
          </div>
        </div>
        <!-- <button class="header-icon-btn" title="Chat">
          <i class="fas fa-comment-dots"></i>
        </button>

       
        <button class="header-icon-btn" title="Notifications">
          <i class="fas fa-bell"></i>
        </button> -->
        <div class="right-side">
          <!-- <div class="dropdown-wrapper">
            <button id="profileToggle" class="bg-[#ff4939] text-white px-4 py-2 rounded-md text-sm">Login</button>
            <div class="profile-dropdown" id="profileDropdown">
              <a href="#">Profile</a>
              <a href="#">Chat</a>
              <a href="#">Notifications</a>
              <a href="#">Logout</a>
            </div>
          </div> -->
          <button class="login-btn">Login/SignUp</button>
        </div>

      </div>
      <div class="input-container">
        <!-- Location Input -->

        <!-- Search Input -->
        <div
          class="input-box"
          style="margin: auto; margin-top: 7px; margin-bottom: 5px; width: 80%"
        >
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Search ‘Business’" />
        </div>
      </div>

      <!-- Second Header -->
      <div class="second-header">
        <div class="action-buttons">
          <button class="burger-menu" style="color: #e03d2f" onclick="toggleSidebar()">☰</button>

          <!-- For Business -->
          <div class="dropdown-wrapper">
            <div class="dropdown-toggle" id="businessToggle">
              For Business ▾
            </div>
            <div class="menu-dropdown" id="businessMenu">
              <a href="https://assured.practo.com/">
                Add Business <span class="star-icon">✦</span>
                <span class="new-tag">NEW</span>
              </a>
              <a href="https://www.practo.com/providers/prime">List</a>
              <a href="https://www.practo.com/providers"
                >Software for Providers</a
              >
              <a href="https://www.practo.com/providers/clinics/profile"
                >List your Practice for Free</a
              >
              <a href="https://www.practo.com/providers/abdm">ABDM</a>
            </div>
          </div>

          <!-- For City Guide -->
          <div class="dropdown-wrapper">
            <div class="dropdown-toggle" id="cityGuideToggle">
              For City Guide ▾
            </div>
            <div class="menu-dropdown" id="cityGuideMenu">
              <a href="#">
                Post Requirement <span class="star-icon">✦</span>
                <span class="new-tag">NEW</span>
              </a>
              <a href="#">Local Business</a>
              <a href="#">Software for Providers</a>
              <a href="#">List your Practice for Free</a>
              <a href="#">ABDM</a>
            </div>
          </div>
        </div>
      </div>
    </header>   
</div>





<!-- Header End -->

<!-- Enquiry Modal - Only for logged in users -->
@auth
<div class="modal fade" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enquiryModalLabel">Enquiry Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <form id="enquiryForm"  method="POST">
                @csrf 
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile No</label>
                        <input type="tel" class="form-control" id="mobile" pattern="[0-9]{10}" required>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <select class="form-control select2 w-100" id="city_modal"  name="city_modal" required>
                            <option value="">Select City</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="product" class="form-label mt-2">Product</label>
                        <select class="form-control select2 w-100" id="product" required>
                            <option value="">Select Product</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mb-1 mt-2 w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@else
<!-- Hidden modal for non-authenticated users -->
<div class="modal fade" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enquiryModalLabel">Login Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <p>Please log in to submit an enquiry.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            </div>
        </div>
    </div>
</div>
@endauth

<script>
$(function(){
  $('#dropdownMenuButton1').on('click', function(e){
    e.preventDefault();

    var $parent = $(this).closest('.dropdown');
    var $menu = $parent.find('.dropdown-menu');

    $parent.toggleClass('show');
    $menu.toggleClass('show');
    $(this).attr('aria-expanded', $parent.hasClass('show'));
  });

  // close when clicking outside
  $(document).on('click', function(e){
    if (!$(e.target).closest('.dropdown').length) {
      $('.dropdown.show').removeClass('show').find('.dropdown-menu.show').removeClass('show');
      $('[data-bs-toggle="dropdown"]').attr('aria-expanded', 'false');
    }
  });
   $('#city_header_main').on('change', function() {
            var citySlug = $(this).val();  // Get the selected city slug
            if (citySlug) {
                let url = `/${citySlug}`;  // Build the URL
                window.location.href = url; // Redirect to the new URL
            }
        });
});
</script>
<script>
    $(document).ready(function() {

        // Get CSRF token from the meta tag
            // Initialize Select2 on the dropdowns
       $('#city_header, #category_header, #city_header_main').select2({
   placeholder: function() {
       return $(this).data('placeholder');
   }
});
       $('#city_header').on('change', function() {
 $('#category_header').html("<option selected value='0'>Select Category</option>");

 if(this.value > 0)
 {
     var ajax_url = '/ajax/categories/' + this.value;

     $.ajaxSetup({
         headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
     });
     jQuery.ajax({
         url: ajax_url,
         method: 'get',
         data: {
         },
         success: function(result){
             //console.log(result);
             $('#category_header').html("<option selected value='0'>Select Category</option>");
             $.each((typeof result === 'string' ? JSON.parse(result) : result), function(key, value) {
                 var city_id = value.id;
                 var city_name = value.category_name;
                 $('#category_header').append('<option value="'+ city_id +'">' + city_name + '</option>');
             });
             
         }});
 }

 });



     // Initialize Select2 only when modal is shown
     $('#enquiryModal').on('shown.bs.modal', function () {
           // Auto-fill form with user details if logged in
           @auth
           // Pre-fill name and mobile if user is logged in
           $('#name').val('{{ Auth::user()->name }}');
           
           // Pre-fill mobile number without country code (remove 91 if present)
           $('#mobile').val('{{ remove_country_code(Auth::user()->phone) }}');
           
           // Pre-fill city if user has a city
           @if(Auth::user()->city_id)
               // Create option for user's city
               var userCityOption = new Option('{{ Auth::user()->city->city_name }}', '{{ Auth::user()->city_id }}', true, true);
               $('#city_modal').append(userCityOption).trigger('change');
           @endif
           @endauth
           
           $('#city_modal').select2({
               placeholder: "Select City",
               allowClear: true,
               ajax: {
                   url: "{{ route('ajax.cities.enquiry') }}",  // Your route here
                   dataType: 'json',
                   delay: 250,  // Delay before sending request after typing
                   data: function (params) {
                       return {
                           q: params.term  // Search term that the user types
                       };
                   },
                   processResults: function (data) {
                       return {
                           results: data.map(function (city) {
                               return {
                                   id: city.id,
                                   text: city.city_name
                               };
                           })
                       };
                   },
                   cache: true
               },
               width: '100%',  // Ensure full width
               dropdownParent: $('#enquiryModal'),  // Ensure dropdown appears inside modal
               minimumInputLength: 1  // Minimum characters required before making the request
           });

           // Product Select2 Initialization
           $('#product').select2({
               placeholder: "Select Product",
               allowClear: true,
               ajax: {
                   url: "{{ route('ajax.products') }}", // Your route for products
                   dataType: 'json',
                   delay: 250,  // Delay before sending request after typing
                   data: function (params) {
                       return {
                           q: params.term // Search term that the user types
                       };
                   },
                   processResults: function (data) {
                       return {
                           results: data.map(function (product) {
                               return {
                                   id: product.id,
                                   text: product.title
                               };
                           })
                       };
                   },
                   cache: true
               },
               width: '100%',  // Ensure full width
               dropdownParent: $('#enquiryModal'),  // Ensure dropdown appears inside modal
               minimumInputLength: 1  // Only trigger the AJAX request if the user types at least 1 character
           });
       });

       // Reset form when modal is hidden
       $('#enquiryModal').on('hidden.bs.modal', function () {
           // Reset the form
           $('#enquiryForm')[0].reset();
           
           // Clear Select2 dropdowns
           $('#city_modal').val(null).trigger('change');
           $('#product').val(null).trigger('change');
       });

       $.ajax({
           url: "{{ route('ajax.products') }}",
           method: "GET",
           success: function(data) {
               console.log(data); // Check if the response is correct
           },
           error: function(xhr, status, error) {
               console.log("Error: " + status + " " + error);
           }
       });

       // Optional: Test if data is returned from the AJAX endpoint
       $.ajax({
           url: "{{ route('ajax.cities.enquiry') }}",
           method: "GET",
           success: function(data) {
               console.log(data); // Check if the response is correct
           },
           error: function(xhr, status, error) {
               console.log("Error: " + status + " " + error);
           }
       });
       // Handle form submission
   $('#enquiryForm').on('submit', function(event) {
       event.preventDefault(); // Prevent default form submission

       // var formData = $(this).serialize(); // Serialize the form data

       // Get form data
   let formData = {
       name: $('#name').val(),
       mobile: $('#mobile').val(),
       city_id: $('#city_modal').val(),
       product_id: $('#product').val(),
   };

   $.ajaxSetup({
       headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
   });


       $.ajax({
           url: "{{ route('enquiry.store') }}",  // Replace with your form's submission URL
           method: "POST",
           data: formData,
           success: function(response) {
               Swal.fire({
                   icon: 'success',
                   title: 'Submitted!',
                   text: response.message, // Success message from the response
               });

               // Reset the form and optionally close the modal
               $('#enquiryForm')[0].reset();
               $('#enquiryModal').modal('hide');
           },
           error: function(xhr, status, error) {
               let errors = xhr.responseJSON.errors;

               // Show error messages if validation fails
               Swal.fire({
                   icon: 'error',
                   title: 'Oops...',
                   text: errors ? Object.values(errors).join(', ') : 'Something went wrong!',
               });
           }
       });
   });

//         $('#enquiryForm').on('submit', function(event) {
//     event.preventDefault(); // Prevent the form from submitting normally

//     // Get form data
//     let formData = {
//         name: $('#name').val(),
//         mobile: $('#mobile').val(),
//         city_id: $('#city').val(),
//         product_id: $('#product').val(),
//     };

//     // Submit the form using AJAX
//     $.ajax({
//         url: "{{ route('enquiry.store') }}",
//         method: "POST",
//         data: formData,
//         success: function(response) {
//             Swal.fire({
//                 icon: 'success',
//                 title: 'Submitted!',
//                 text: response.message,
//             });

//             // Optionally reset the form and close the modal
//             $('#enquiryForm')[0].reset();
//             $('#enquiryModal').modal('hide');
//         },
//         error: function(xhr, status, error) {
//             let errors = xhr.responseJSON.errors;
//             // Show error messages if validation fails
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Oops...',
//                 text: errors ? errors.join(', ') : 'Something went wrong!',
//             });
//         }
//     });
// });

});


   
   

       </script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Initialize Select2 for desktop search
    $("#search-box").select2({
        placeholder: "{{ get_phrase('Search...') }}",
        allowClear: true,
        ajax: {
            url: "{{ route('search.globally') }}", // Backend route to fetch search results
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term, // Send search query
                    cityid: $("#city_header").val()
                };
            },
            processResults: function (data) {
                let results = [];

                if (data.pages.length) {
                    results.push({
                        text: '📄 Pages', 
                        children: data.pages.map(page => ({
                            id: page.id,
                            text: page.title,
                            type: 'page',
                            citySlug: page.city_slug, // Ensure citySlug is included
                            areaSlug: page.area_slug, // Ensure areaSlug is included
                            categorySlug: page.category_slug, // Ensure categorySlug is included
                            itemSlug: page.item_slug // Ensure itemSlug is included
                        }))
                    });
                }
                if (data.marketplace.length) {
                    results.push({ text: '🛍️ Deals', children: data.marketplace.map(item => ({ id: item.id, text: item.title, type: 'marketplace' })) });
                }
                if (data.events.length) {
                    results.push({ text: '📅 Events', children: data.events.map(event => ({ id: event.id, text: event.title, type: 'event' })) });
                }
                if (data.blogs.length) {
                    results.push({ text: '📝 Blog', children: data.blogs.map(blog => ({ id: blog.id, text: blog.title, type: 'blog' })) });
                }
                if (data.users.length) {
                    results.push({ text: '👤 Users', children: data.users.map(user => ({ id: user.id, text: user.name, type: 'user' })) });
                }

                return { results };
            },
            cache: true
        }
    });

    // Initialize Select2 for mobile search
    if ($("#mobile-search-box").length) {
        console.log('Initializing mobile Select2');
        $("#mobile-search-box").select2({
            placeholder: "{{ get_phrase('Search...') }}",
            allowClear: true,
            dropdownParent: $('#mobileDropdownMenu'), // Ensure dropdown appears inside mobile menu
            ajax: {
                url: "{{ route('search.globally') }}", // Backend route to fetch search results
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term, // Send search query
                        cityid: $("#mobile_city_slug").val()
                    };
                },
                processResults: function (data) {
                    let results = [];

                    if (data.pages.length) {
                        results.push({
                            text: '📄 Pages', 
                            children: data.pages.map(page => ({
                                id: page.id,
                                text: page.title,
                                type: 'page',
                                citySlug: page.city_slug,
                                areaSlug: page.area_slug,
                                categorySlug: page.category_slug,
                                itemSlug: page.item_slug
                            }))
                        });
                    }
                    if (data.marketplace.length) {
                        results.push({ text: '🛍️ Deals', children: data.marketplace.map(item => ({ id: item.id, text: item.title, type: 'marketplace' })) });
                    }
                    if (data.events.length) {
                        results.push({ text: '📅 Events', children: data.events.map(event => ({ id: event.id, text: event.title, type: 'event' })) });
                    }
                    if (data.blogs.length) {
                        results.push({ text: '📝 Blog', children: data.blogs.map(blog => ({ id: blog.id, text: blog.title, type: 'blog' })) });
                    }
                    if (data.users.length) {
                        results.push({ text: '👤 Users', children: data.users.map(user => ({ id: user.id, text: user.name, type: 'user' })) });
                    }

                    return { results };
                },
                cache: true
            }
        });
    }

    // Handle item selection for desktop search
    $('#search-box').on('select2:select', function (e) {
        let selectedData = e.params.data;
        let url = "";

        // Define redirection logic based on search type
        switch (selectedData.type) {
            case 'page':
               // Construct the URL for page
            url = `/${selectedData.citySlug}/${selectedData.areaSlug}/${selectedData.categorySlug}/${selectedData.itemSlug}`;
            break;
            case 'marketplace':
               // Construct the URL for marketplace
                url = `/product/filter?search=${encodeURIComponent(selectedData.text)}`;
                break;
            case 'event':
                url = `/events?title=${encodeURIComponent(selectedData.text)}`;
                break;
            case 'blog':
                url = `/blogs?title=${encodeURIComponent(selectedData.text)}`;
                break;
            case 'user':
                url = "/user/view-profile/" + selectedData.id;
                break;
            
        }

        // Redirect to the correct page
        window.location.href = url;
    });

    // Handle item selection for mobile search
    $('#mobile-search-box').on('select2:select', function (e) {
        let selectedData = e.params.data;
        let url = "";

        // Define redirection logic based on search type
        switch (selectedData.type) {
            case 'page':
               // Construct the URL for page
            url = `/${selectedData.citySlug}/${selectedData.areaSlug}/${selectedData.categorySlug}/${selectedData.itemSlug}`;
            break;
            case 'marketplace':
               // Construct the URL for marketplace
                url = `/product/filter?search=${encodeURIComponent(selectedData.text)}`;
                break;
            case 'event':
                url = `/events?title=${encodeURIComponent(selectedData.text)}`;
                break;
            case 'blog':
                url = `/blogs?title=${encodeURIComponent(selectedData.text)}`;
                break;
            case 'user':
                url = "/user/view-profile/" + selectedData.id;
                break;
            
        }

        // Redirect to the correct page and close mobile dropdown
        window.location.href = url;
    });
});
</script>
<script>
function toggleCityDropdown() {
    document.getElementById("cityDropdown").classList.toggle("hidden");
  }

  // Select City
  function selectCity(slug, name) {
    document.getElementById("selectedCity").textContent = name;
    document.getElementById("city_header_main").value = slug;
    document.getElementById("cityDropdown").classList.add("hidden");
  }
    // Desktop Form Logic
    document.getElementById('search-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const citySlug = document.getElementById('city_header_main').value;
        const search = document.getElementById('search-box').value;

        if (!citySlug) {
            alert('Please select a city.');
            return;
        }

        let url = `/${citySlug}`;
        if (search) {
            url += `?search=${encodeURIComponent(search)}`;
        }

        // Redirect
        window.location.href = url;
    });

     window.addEventListener("click", function (e) {
    if (!document.getElementById("cityDropdownButton").contains(e.target) &&
        !document.getElementById("cityDropdown").contains(e.target)) {
      document.getElementById("cityDropdown").classList.add("hidden");
    }
  });

    // Mobile Form Logic
    document.getElementById('mobile-search-form').addEventListener('submit', function (e) {
        e.preventDefault();
        console.log('Mobile search form submitted');

        const citySlug = document.getElementById('mobile_city_slug').value;
        const search = document.getElementById('mobile-search-box').value;
        
        console.log('City slug:', citySlug);
        console.log('Search term:', search);

        if (!citySlug) {
            alert('Please select a city.');
            return;
        }

        let url = `/${citySlug}`;
        if (search) {
            url += `?search=${encodeURIComponent(search)}`;
        }
        
        console.log('Redirecting to:', url);

        // Redirect
        window.location.href = url;
    });

    // Mobile dropdown toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const mobileToggle = document.getElementById('mobileMenuToggle');
        const mobileDropdown = document.getElementById('mobileDropdownMenu');
        
        if (mobileToggle && mobileDropdown) {
            console.log('Mobile toggle elements found');
            mobileToggle.addEventListener('click', function (e) {
                e.stopPropagation();
                console.log('Mobile toggle clicked');
                mobileDropdown.classList.toggle('d-none');
                console.log('Mobile dropdown visibility:', !mobileDropdown.classList.contains('d-none'));
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileToggle.contains(event.target) && !mobileDropdown.contains(event.target)) {
                    mobileDropdown.classList.add('d-none');
                }
            });
        }
        
        // Fallback for mobile search if Select2 doesn't work
        const mobileSearchBox = document.getElementById('mobile-search-box');
        if (mobileSearchBox && !mobileSearchBox.classList.contains('select2-hidden-accessible')) {
            // If Select2 is not initialized, make it a simple text input
            mobileSearchBox.type = 'text';
            mobileSearchBox.placeholder = "{{ get_phrase('Search...') }}";
        }

        // Enhanced Navigation dropdown functionality
        const navDropdowns = document.querySelectorAll('.nav-dropdown');
        
        navDropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('.nav-dropdown-toggle');
            const menu = dropdown.querySelector('.dropdown-menu');
            
            if (toggle && menu) {
                // Handle click events
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Close other dropdowns
                    navDropdowns.forEach(otherDropdown => {
                        if (otherDropdown !== dropdown) {
                            otherDropdown.classList.remove('show');
                            const otherMenu = otherDropdown.querySelector('.dropdown-menu');
                            if (otherMenu) {
                                otherMenu.classList.remove('show');
                            }
                        }
                    });
                    
                    // Toggle current dropdown
                    dropdown.classList.toggle('show');
                    menu.classList.toggle('show');
                });
                
                // Handle hover events for desktop
                if (window.innerWidth >= 992) {
                    dropdown.addEventListener('mouseenter', function() {
                        // Close other dropdowns
                        navDropdowns.forEach(otherDropdown => {
                            if (otherDropdown !== dropdown) {
                                otherDropdown.classList.remove('show');
                                const otherMenu = otherDropdown.querySelector('.dropdown-menu');
                                if (otherMenu) {
                                    otherMenu.classList.remove('show');
                                }
                            }
                        });
                        
                        // Show current dropdown
                        dropdown.classList.add('show');
                        menu.classList.add('show');
                    });
                    
                    dropdown.addEventListener('mouseleave', function() {
                        // Hide dropdown after a small delay
                        setTimeout(() => {
                            if (!dropdown.matches(':hover')) {
                                dropdown.classList.remove('show');
                                menu.classList.remove('show');
                            }
                        }, 100);
                    });
                }
            }
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.nav-dropdown')) {
                navDropdowns.forEach(dropdown => {
                    dropdown.classList.remove('show');
                    const menu = dropdown.querySelector('.dropdown-menu');
                    if (menu) {
                        menu.classList.remove('show');
                    }
                });
            }
        });
        
        // Handle window resize to reset dropdown states
        window.addEventListener('resize', function() {
            navDropdowns.forEach(dropdown => {
                dropdown.classList.remove('show');
                const menu = dropdown.querySelector('.dropdown-menu');
                if (menu) {
                    menu.classList.remove('show');
                }
            });
        });
    });
</script>
 <script>
    function toggleCityDropdown() {
      const dropdown = document.getElementById("cityDropdown");
      dropdown.classList.toggle("hidden");
    }

    function selectCity(city) {
      document.getElementById("selectedCity").textContent = city;
      document.getElementById("cityDropdown").classList.add("hidden");
    }

    // Close dropdown on outside click
    window.addEventListener("click", function (e) {
      const button = document.getElementById("cityDropdownButton");
      const dropdown = document.getElementById("cityDropdown");

      if (!button.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.add("hidden");
      }
    });
  </script>
   <script>
      function toggleDropdown(toggleId, menuId) {
        document
          .getElementById(toggleId)
          .addEventListener("click", function (e) {
            e.stopPropagation();
            const menu = document.getElementById(menuId);
            menu.style.display =
              menu.style.display === "block" ? "none" : "block";
          });

        document.addEventListener("click", function (e) {
          const menu = document.getElementById(menuId);
          const toggle = document.getElementById(toggleId);
          if (!toggle.contains(e.target)) {
            menu.style.display = "none";
          }
        });
      }

      toggleDropdown("profileToggle", "profileDropdown");
      toggleDropdown("businessToggle", "businessMenu");
      toggleDropdown("cityGuideToggle", "cityGuideMenu");

      function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("active");
      }
    </script>