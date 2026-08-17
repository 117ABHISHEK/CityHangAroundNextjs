
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<!-- Include Select2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
</style>
<header class="header header-default py-3 @guest guest-user @endguest"> 
    <nav class="navigation @guest guest-user @endguest">
        <div class="container">
            <div class="header-flex-container">
                <div class="header-logo-section">
                    <div class="logo-branding"> 
                        <!-- <button class="d-lg-none" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i
                                class="fw-bold fa-solid fa-sliders-h"></i></button> -->
                        <!-- logo -->
                        @php
                            $system_light_logo = \App\Models\Setting::where('type', 'system_light_logo')->value('description');
                        @endphp
                        <a class="navbar-brand mt-2" href="{{route('timeline')}}"><img src="{{ get_system_logo_favicon($system_light_logo,'light') }}" class="logo_height_width" alt="logo" /></a>
                    </div>
                </div>

                <div class="header-search-section d-none d-lg-block">
                    <div class="header-search">
                        <a href="{{route('timeline')}}">
                            <div class="sc-home rounded">
                                <i class="fa-solid fa-house"></i>
                            </div>
                        </a>
                        
                        <form method="GET" id="search-form">
                            <div class="sc-search-new">
                                <select name="city_header_main" id="city_header_main" required>
                                    <option value="">Select City</option>
                                    @foreach ($all_cities as $key => $city)
                                        <option value="{{ $city->city_slug }}">{{ $city->city_name }}</option>
                                    @endforeach
                                </select>

                                <select id="search-box" class="search-dropdown form-control" style="width: 100%;">
                                    <option value="">{{ get_phrase('Search...') }}</option>
                                </select>

                                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="header-nav-section d-none d-lg-block">
                    <!-- Navigation Dropdowns -->
                    <div class="header-navigation">
                        <div class="nav-dropdowns">
                            <!-- City Guide Dropdown -->
                            <div class="nav-dropdown dropdown">
                                <button class="nav-dropdown-toggle dropdown-toggle" type="button" id="cityGuideDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-map-marker-alt"></i>
                                    {{ get_phrase('City Guide') }}
                                    <i class="fa-solid fa-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="cityGuideDropdown">
                                    <li><a class="dropdown-item nav-dropdown-item" href="{{ route('timeline') }}">{{ get_phrase('City Guide') }}</a></li>
                                    <li><a class="dropdown-item nav-dropdown-item" href="{{ route('allproducts') }}">{{ get_phrase('Deals') }}</a></li>
                                    <li><a class="dropdown-item nav-dropdown-item" href="{{ route('profile.friends') }}">{{ get_phrase('Following') }}</a></li>
                                    <li><a class="dropdown-item nav-dropdown-item" href="{{ route('event') }}">{{ get_phrase('Event') }}</a></li>
                                    <li><a class="dropdown-item nav-dropdown-item" href="{{ route('timeline') }}">{{ get_phrase('Feed') }}</a></li>
                                    <li><a class="dropdown-item nav-dropdown-item" href="#">{{ get_phrase('Trending Video') }}</a></li>
                                    <li><a class="dropdown-item nav-dropdown-item" href="{{ route('blogs') }}">{{ get_phrase('Blog') }}</a></li>
                                    <li><a class="dropdown-item nav-dropdown-item" href="#">{{ get_phrase('Post Requirement') }}</a></li>
                                    <li><a class="dropdown-item nav-dropdown-item" href="{{ route('login') }}">{{ get_phrase('Login/SignUp') }}</a></li>
                                </ul>
                            </div>

                            <!-- For Business Dropdown -->
                            <div class="nav-dropdown dropdown">
                                <button class="nav-dropdown-toggle dropdown-toggle" type="button" id="forBusinessDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-briefcase"></i>
                                    {{ get_phrase('For Business') }}
                                    <i class="fa-solid fa-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="forBusinessDropdown">
                                    <li><a class="dropdown-item nav-dropdown-item" href="#">{{ get_phrase('Add Business') }}</a></li>
                                    <li><a class="dropdown-item nav-dropdown-item" href="#">{{ get_phrase('Add Deals') }}</a></li>
                                    <li><a class="dropdown-item nav-dropdown-item" href="{{ route('login') }}">{{ get_phrase('Login/SignUp') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-8 d-lg-none">
                    <div class="header-controls">  
                        <div class="align-items-center headerRightMenu d-flex justify-content-around">
                            @if(auth()->user())
                            <div class="group-control onlydesktop">
                                <a href="{{ route('profile.friends') }}" class="notification-button" data-bs-toggle="tooltip" title="Following"><i class="fa-solid fa-user-group"></i></a>
                            </div>
                            @php
                            if(auth()->user()){
                                $last_msg = \App\Models\Chat::where('sender_id',auth()->user()->id)->orWhere('reciver_id',auth()->user()->id)->orderBy('id','DESC')->limit('1')->first();
                                if(!empty($last_msg)){
                                    if($last_msg->sender_id == auth()->user()->id){
                                        $msg_to = $last_msg->reciver_id;
                                    }else{
                                        $msg_to = $last_msg->sender_id;
                                    }
                                }

                                $unread_msg = \App\Models\Chat::where('reciver_id',auth()->user()->id)->where('read_status','0')->count();
                            }else{
                                $last_msg ="";
                                $unread_msg ="";
                            }
                            @endphp
                            <div class="inbox-control onlydesktop">
                                <a href="@if(!empty($last_msg)){{ route('chat',$msg_to) }} @endif" class="message_custom_button position-relative" data-bs-toggle="tooltip" title="Chat">
                                    <i class="fa-brands fa-rocketchat"></i>
                                    @if ($unread_msg>0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill notificatio_counter_bg">
                                            {{ get_phrase($unread_msg) }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                            @php
                            if(auth()->user()){
                                $unread_notification = \App\Models\Notification::where('reciver_user_id',auth()->user()->id)->where('status','0')->count();
                            }
                            else{
                                $unread_notification ="";
                            }
                            @endphp
                            <div class="notify-control onlydesktop">
                                <a class="notification-button position-relative" href="{{ route('notifications') }}" data-bs-toggle="tooltip" title="Notifications">
                                    <i class="fa-solid fa-bell"></i>
                                    @if ($unread_notification>0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill notificatio_counter_bg">
                                            {{ get_phrase($unread_notification) }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                            <!-- Enquiry Button with Centered Plus Icon - Only for logged in users -->
                            <div class="enquiry-control d-flex align-items-center onlydesktop">
                                <button 
                                    class="btn btn-primary enquiry-btn d-flex align-items-center justify-content-center rounded-circle"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#enquiryModal" 
                                    data-bs-toggle="tooltip" 
                                    title="Create New Enquiry">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                            @endif

                            <!-- Mobile Search Icon - Available for all users -->
                            <div class="onlymobile mobilesearchIcon">
                                <button id="mobileMenuToggle"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>

                            <!-- Hidden Mobile Dropdown Menu with Profile + Search -->
                            <div class="mobile-dropdown-menu d-none" id="mobileDropdownMenu">
                                <!-- Search Form -->
                                <form method="GET" id="mobile-search-form">
                                    <div class="sc-search-new">
                                        <select name="city_slug" id="mobile_city_slug" required>
                                            <option value="">Select City</option>
                                            @foreach ($all_cities as $key => $city)
                                                <option value="{{ $city->city_slug }}">{{ $city->city_name }}</option>
                                            @endforeach
                                        </select>

                                        <select id="mobile-search-box" class="search-dropdown form-control" style="width: 100%;">
                                            <option value="">{{ get_phrase('Search...') }}</option>
                                        </select>

                                        <button type="submit">Search</button>
                                    </div>
                                </form>

                                <!-- Mobile Navigation Menu -->
                                <div class="mobile-nav-menu">
                                    <div class="mobile-nav-section">
                                        <h6 class="mobile-nav-title">{{ get_phrase('City Guide') }}</h6>
                                        <ul class="mobile-nav-list">
                                            <li><a href="{{ route('timeline') }}">{{ get_phrase('City Guide') }}</a></li>
                                            <li><a href="{{ route('allproducts') }}">{{ get_phrase('Deals') }}</a></li>
                                            <li><a href="{{ route('profile.friends') }}">{{ get_phrase('Following') }}</a></li>
                                            <li><a href="{{ route('event') }}">{{ get_phrase('Event') }}</a></li>
                                            <li><a href="{{ route('timeline') }}">{{ get_phrase('Feed') }}</a></li>
                                            <li><a href="#">{{ get_phrase('Trending Video') }}</a></li>
                                            <li><a href="{{ route('blogs') }}">{{ get_phrase('Blog') }}</a></li>
                                            <li><a href="#">{{ get_phrase('Post Requirement') }}</a></li>
                                            <li><a href="{{ route('login') }}">{{ get_phrase('Login/SignUp') }}</a></li>
                                        </ul>
                                    </div>

                                    <div class="mobile-nav-section">
                                        <h6 class="mobile-nav-title">{{ get_phrase('For Business') }}</h6>
                                        <ul class="mobile-nav-list">
                                            <li><a href="#">{{ get_phrase('Add Business') }}</a></li>
                                            <li><a href="#">{{ get_phrase('Add Deals') }}</a></li>
                                            <li><a href="{{ route('login') }}">{{ get_phrase('Login/SignUp') }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
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
             $.each(JSON.parse(result), function(key, value) {
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

