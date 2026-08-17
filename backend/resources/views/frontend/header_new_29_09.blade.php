
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
 <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<!-- header -->

<link rel="stylesheet" href="{{asset('assets/frontend/css/header_new.css')}}?v={{ time() }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<!-- Include Select2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<div class="custom-progress-bar">
    <div class="custom-progress"></div>
</div>
 <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <script src="https://cdn.tailwindcss.com"></script>

<style>
/* Select2 Customization */
.select2-results__option {
    font-weight: bold;
}
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
    z-index: 9999 !important;
}

/* Modal Tweaks */
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
.modal-backdrop {
    z-index: 1040 !important;
}
.modal {
    z-index: 1050 !important;
}

/* Responsive Controls */
.only-large,
.only-medium,
.only-mobile {
    display: none !important;
}

@media (min-width: 1024px) {
    .only-large {
        display: inline-block !important;
    }
    .only-medium,
    .only-mobile,
    #mobileDropdownMenu,
    #mobileMenuToggle {
        display: none !important;
    }
}

@media (min-width: 768px) and (max-width: 1023.98px) {
    .only-medium {
        display: inline-block !important;
    }
    .only-large,
    .only-mobile,
    #search-form {
        display: none !important;
    }
    #mobileMenuToggle {
        display: inline-block !important;
    }

    /* 🔴 Remove this line below */
    /* #mobileDropdownMenu {
        display: none !important;
    } */

    .only-medium-flex {
        display: flex !important;
        justify-content: flex-end;
        align-items: center;
        gap: 1rem;
    }
}

@media (max-width: 767.98px) {
    .only-mobile {
        display: inline-block !important;
    }
    .only-large,
    .only-medium,
    #search-form,
    .group,
    .notify-control {
        display: none !important;
    }
    #mobileMenuToggle {
        display: inline-block !important;
    }
}

.header-row {
    display: flex;
    flex-wrap: nowrap !important;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    gap: 1rem;
    white-space: nowrap;
}

.row.header-row > [class*="col"] {
    flex-shrink: 0;
    white-space: nowrap;
}
</style>



<!-- Include Mobile Header -->
@include('frontend.mobile_header')

<!-- Desktop Header -->
<header class="header header-default py-2 py-lg-1 d-none d-lg-block">
    <nav class="navigation">
        <div class="container">
            <div class="row header-row align-items-center">
                <!-- Logo -->
                <div class="col-lg-2 col-6">
                    <div class="logo-branding">
                        @php
                            $system_light_logo = \App\Models\Setting::where('type', 'system_light_logo')->value('description');
                        @endphp
                        <a class="navbar-brand mt-2" href="{{ route('timeline') }}">
                            <img src="{{ get_system_logo_favicon($system_light_logo,'light') }}" class="logo_height_width" alt="logo" />
                        </a>
                    </div>
                </div>

                <!-- Profile + Burger + Dropdown for Mobile -->
                <div class="col-6 d-lg-none d-flex justify-content-end align-items-center gap-3 position-relative">
                    @if(auth()->user())
                        <!-- Profile Dropdown Styled Button -->
                        <div class="dropdown">
                            <button class="d-flex align-items-center border-0 bg-transparent p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="d-flex align-items-center rounded-pill overflow-hidden" style="background-color: #FF4D4D;">
                                    <img src="{{ get_user_image(auth()->user()->photo,'optimized') }}"
                                         class="rounded-circle"
                                         style="height: 40px; width: 40px; object-fit: cover;" alt="Profile">
                                    <div class="d-flex justify-content-center align-items-center" style="background-color: #FF4D4D; height: 40px; width: 40px;">
                                        <i class="fa fa-caret-down text-white"></i>
                                    </div>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end mt-2">
                                <li><a class="dropdown-item" href="{{ route('profile') }}">{{ get_phrase('My Profile') }}</a></li>
                                @if(auth()->user()->user_role=="admin")
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">{{ get_phrase('Go to admin panel') }}</a></li>
                                @elseif(auth()->user()->user_role=="general")
                                    <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">{{ get_phrase('Dashboard') }}</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('user.password.change') }}">{{ get_phrase('Change Password') }}</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ get_phrase('Log Out') }}
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif

                    <!-- Burger Menu Button -->
                    <button class="btn p-0" type="button" id="mobileMenuToggle" style="font-size: 1.5rem;">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <!-- Mobile Dropdown Menu (inside relative container) -->
                    <div class="mobile-dropdown-menu d-none bg-white p-3 shadow rounded"
                         id="mobileDropdownMenu"
                         style="position: absolute; top: 100%; right: 0; width: 280px; margin-top: 4px; z-index: 1050;">
                        <!-- Mobile Search Form -->
                        <form method="GET" id="mobile-search-form" class="mb-4">
                            <label for="mobile_city_slug" class="form-label fw-bold">Select City</label>
                            <select name="city_slug" id="mobile_city_slug" class="form-select mb-3" required>
                                <option value="">-- Select City --</option>
                                @foreach ($all_cities as $city)
                                    <option value="{{ $city->city_slug }}">{{ $city->city_name }}</option>
                                @endforeach
                            </select>

                            <label for="mobile-search-box" class="form-label fw-bold">Search</label>
                              <input type="text" name="query" id="mobile-search-box" class="form-control mb-3" placeholder="Search..." />
                            <!--<select id="mobile-search-box" class="form-select mb-3" style="width: 100%;">-->
                            <!--    <option value="">{{ get_phrase('Search...') }}</option>-->
                            <!--</select>-->

                            <button type="submit" class="btn btn-danger w-100">Search</button>
                        </form>

                        <!-- For Business -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2 text-uppercase text-muted">For Business</h6>
                            <div class="list-group list-group-flush">
                                <a href="#" class="list-group-item list-group-item-action">Add Business</a>
                                <a href="#" class="list-group-item list-group-item-action">Listings</a>
                                <a href="#" class="list-group-item list-group-item-action">Explore Business</a>
                                <a href="#" class="list-group-item list-group-item-action">List your Product</a>
                                <a href="#" class="list-group-item list-group-item-action">About Business</a>
                            </div>
                        </div>

                        <!-- City Guide -->
                        <div>
                            <h6 class="fw-bold mb-2 text-uppercase text-muted">City Guide</h6>
                            <div class="list-group list-group-flush">
                                <a href="#" class="list-group-item list-group-item-action">Add Business</a>
                                <a href="#" class="list-group-item list-group-item-action">Listings</a>
                                <a href="#" class="list-group-item list-group-item-action">Explore Business</a>
                                <a href="#" class="list-group-item list-group-item-action">List your Product</a>
                                <a href="#" class="list-group-item list-group-item-action">About Business</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Large screen search (unchanged) -->
                <div class="col-lg-4 only-large">
                    <div class="d-flex w-100 gap-2">
                        <a href="{{ route('timeline') }}">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-secondary" style="width: 38px; height: 38px;">
                                <i class="fa-solid fa-house text-white"></i>
                            </div>
                        </a>

                        <form method="GET" id="search-form" class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 w-100" style="max-width: 750px; margin-left: auto; margin-right: auto;">
                                <select name="city_header_main" required class="form-select" style="width: 140px;">
                                    <option value="">Select City</option>
                                    @foreach ($all_cities as $city)
                                        <option value="{{ $city->city_slug }}">{{ $city->city_name }}</option>
                                    @endforeach
                                </select>

                                <select id="search-box" class="search-dropdown form-control" style="width: 200px;">
                                    <option value="">{{ get_phrase("Search…") }}</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Controls (unchanged) -->
                @if(auth()->user())
                     <div class="col-lg-6 col-sm-12">
                    <div class="header-controls d-flex justify-content-end gap-3">

                        <!-- For Business - medium+ -->
                       <div class="group show-large show-medium relative cursor-pointer">
                            <div class="font-medium px-3 py-2">For Business ▾</div>
                            <div class="absolute top-full left-0 bg-white shadow-lg rounded-md hidden group-hover:block z-[1000] w-56">
                                <a href="#" class="block px-5 py-2 hover:bg-gray-100">Add Business</a>
                                <a href="#" class="block px-5 py-2 hover:bg-gray-100">Listings</a>
                                <a href="#" class="block px-5 py-2 hover:bg-gray-100">Explore Business</a>
                                <a href="#" class="block px-5 py-2 hover:bg-gray-100">List your Product</a>
                                <a href="#" class="block px-5 py-2 hover:bg-gray-100">About Business</a>
                            </div>
                        </div>

                        <!-- City Guide - medium+ -->
                        <div class="group show-large show-medium relative cursor-pointer">
                            <div class="font-medium px-3 py-2">For City Guide ▾</div>
                            <div class="absolute top-full left-0 bg-white shadow-lg rounded-md hidden group-hover:block z-[1000] w-56">
                                <a href="#" class="block px-5 py-2 hover:bg-gray-100">Add Business</a>
                                <a href="#" class="block px-5 py-2 hover:bg-gray-100">Listings</a>
                                <a href="#" class="block px-5 py-2 hover:bg-gray-100">Explore Business</a>
                                <a href="#" class="block px-5 py-2 hover:bg-gray-100">List your Product</a>
                                <a href="#" class="block px-5 py-2 hover:bg-gray-100">About Business</a>
                            </div>
                        </div>

                        <!-- Notification -->
                        @php
                            $unread_notification = \App\Models\Notification::where('reciver_user_id', auth()->user()->id)->where('status','0')->count();
                        @endphp
                        <div class="notify-control only-large">
                            <a class="notification-button position-relative" href="{{ route('notifications') }}" data-bs-toggle="tooltip" title="Notifications">
                                <i class="fa-solid fa-bell"></i>
                                @if ($unread_notification > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill notificatio_counter_bg">
                                        {{ get_phrase($unread_notification) }}
                                    </span>
                                @endif
                            </a>
                        </div>

                        <!-- Profile -->
                        <div class="profile-control dropdown">
                            <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <img src="{{ get_user_image(auth()->user()->photo,'optimized') }}" class="rounded-circle" alt="">
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile') }}">{{ get_phrase('My Profile') }}</a></li>
                                @if(auth()->user()->user_role=="admin")
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">{{ get_phrase('Go to admin panel') }}</a></li>
                                @elseif(auth()->user()->user_role=="general")
                                    <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">{{ get_phrase('Dashboard') }}</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('user.password.change') }}">{{ get_phrase('Change Password') }}</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ get_phrase('Log Out') }}
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
                @endif
            </div>
        </div>
    </nav>
</header>


<script>
    document.getElementById('mobileMenuToggle').addEventListener('click', function () {
        const menu = document.getElementById('mobileDropdownMenu');
        menu.classList.toggle('d-none');
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
    // Initialize Select2
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
                    results.push({ text: '🛍️ Marketplace', children: data.marketplace.map(item => ({ id: item.id, text: item.title, type: 'marketplace' })) });
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

    // Handle item selection
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
});
</script>
<script>
    // Desktop Form Logic
    document.getElementById('search-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const citySlug = document.getElementById('city_slug').value;
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

        const citySlug = document.getElementById('mobile_city_slug').value;
        const search = document.getElementById('mobile-search-box').value;

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
</script>

