
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
<style>
    .select2-results__option {
   
    font-weight: bold;
}
</style>
<!-- Additional CSS Fix -->
<style>
    /* Make Select2 height match Bootstrap input */
    .select2-container .select2-selection--single {
        height: calc(2.25rem + 2px) !important; /* Same as Bootstrap input */
        padding: 0.375rem 0.75rem !important; /* Same padding as inputs */
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5 !important; /* Align text properly */
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(2.25rem + 2px) !important; /* Align dropdown arrow */
    }
    .select2-dropdown {
        z-index: 9999 !important; /* Ensures dropdown appears above modal */
    }

    /* Modal Close Button (Cross Icon) */
    .btn-close {
        color: #000 !important; /* Ensure the cross icon is black or visible */
        background: none; /* Remove background if any */
        border: none; /* Remove border if any */
        font-size: 1.5rem; /* Adjust the size of the cross icon */
        opacity: 1 !important; /* Ensure the icon is visible */
    }

    /* Optional: Hover effect for close button */
    .btn-close:hover {
        color: #f00; /* Red color when hovered */
        opacity: 0.8;
    }

    /* Optional: Adjusting z-index if there are modal overlay issues */
    .modal-backdrop {
        z-index: 1040 !important; /* Ensure backdrop stays behind the modal */
    }

    .modal {
        z-index: 1050 !important; /* Ensure modal is in front */
    }
</style>
<header class="header header-default py-3"> 
    <nav class="navigation">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-sm-4">
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
                @if(auth()->user())
                <div class="col-lg-7 d-none d-lg-block">
                    <div class="header-search">
                        <a href="{{route('timeline')}}">
                            <div class="sc-home rounded">
                                <i class="fa-solid fa-house"></i>
                            </div>
                        </a>
                        
                            <form action="{{route('search')}}" method="GET" id="search-form">
                                <div class="sc-search-new">

                                <select name="city_header" id="city_header" required>
                                    <option value="0">City</option>
                                    @foreach ($all_cities as $key => $city)
                                        <option value="{{$city->id}}">{{$city->city_name}}</option>
                                    @endforeach
                                </select>
                                <!-- Search Box -->
                               
                                    <select id="search-box" class="search-dropdown form-control" style="width: 100%;">
                                        <option value="">{{ get_phrase('Search...') }}</option>
                                    </select>
                               
                                <button><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                                
                            </form>
                       
                    </div>
                </div>
                <div class="col-lg-3 col-sm-8">
                    <div class="header-controls">  
                        <div class="align-items-center headerRightMenu d-flex justify-content-around">
                          
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
                      
                                        <!-- Enquiry Button with Centered Plus Icon -->
                                        <div class="enquiry-control d-flex align-items-center onlydesktop">
                                                    <button 
                                                        class="btn btn-primary enquiry-btn d-flex align-items-center justify-content-center rounded-circle p-3"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#enquiryModal" 
                                                        data-bs-toggle="tooltip" 
                                                        title="Create New Enquiry"
                                                        style="width: 40px; height: 40px; background-color: black; border: none;">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>
                                    </div>


<!-- Mobile Search Icon -->
<div class="onlymobile mobilesearchIcon">
    <button id="mobileMenuToggle"><i class="fa-solid fa-magnifying-glass"></i></button>
</div>

<!-- Hidden Mobile Dropdown Menu with Profile + Search -->
<div class="mobile-dropdown-menu d-none" id="mobileDropdownMenu">
   
    <!-- Search Form -->
    <form action="{{ route('search') }}" method="GET" id="search-form">
        <div class="sc-search-new">
            <select name="city_header" id="city_header" required>
                <option value="0">City</option>
                @foreach ($all_cities as $key => $city)
                    <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                @endforeach
            </select>

            <select id="search-box" class="search-dropdown form-control" style="width: 100%;">
                <option value="">{{ get_phrase('Search...') }}</option>
            </select>

            <button type="submit">Search </button> 
        </div>
    </form>
</div>

<script>
    document.getElementById('mobileMenuToggle').addEventListener('click', function () {
        const menu = document.getElementById('mobileDropdownMenu');
        menu.classList.toggle('d-none');
    });
</script> 






                                     
 

                            <div class="profile-control dropdown">
                                <button class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    
                                    @if(auth()->user())
                                    <img src="{{ get_user_image(auth()->user()->photo,'optimized') }}" class="rounded-circle" alt="">
                                    @else
                                    <img src="{{ get_user_image('','optimized') }}" class="rounded-circle" alt="">
                                    @endif
                                   
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="{{ route('profile') }}">{{ get_phrase('My Profile') }}</a></li>
                                    @if(auth()->user())
                                    @if (auth()->user()->user_role=="admin")
                                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">{{ get_phrase('Go to admin panel') }}</a></li>
                                    @endif
                                    @if (auth()->user()->user_role == "general")
                                        <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">{{ get_phrase('Dashboard') }}</a></li>
                                    @endif
                                    @endif
                                    <li><a class="dropdown-item" href="{{ route('user.password.change') }}">{{ get_phrase('Change Password') }}</a></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                {{ get_phrase('Log Out') }}
                                            </a>
                                        </form>
                                    </li>
                                </ul>
                            </div>



                           
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
<!-- Header End -->

<!-- Enquiry Modal -->
<div class="modal fade" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enquiryModalLabel">Enquiry Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <label for="product" class="form-label">Product</label>
                        <select class="form-control select2 w-100" id="product" required>
                            <option value="">Select Product</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        // Get CSRF token from the meta tag
       

        
       
        // Initialize Select2 on the dropdowns
        $('#city_header, #category_header').select2({
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


  // Validate form on submission
  $('#search-form').on('submit', function(event) {
            let isValid = true;

            // Validate City
            const cityValue = $('#city_header').val();
            if (cityValue == 0 || cityValue == null) {
                isValid = false;
                Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please select city!"
                });
            } else {
                $('#city_header').removeClass('is-invalid');
                $('#city_header_error').text(''); // Clear error message
            }

            if(cityValue>0){

            // Validate Category
            const categoryValue = $('#category_header').val();
            if (categoryValue == 0 || categoryValue == null) {
                isValid = false;
                Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please select category!"
                });
            } else {
                $('#category_header').removeClass('is-invalid');
                $('#category_header_error').text('');
            }
        }

            // Prevent form submission if validation fails
            if (!isValid) {
                event.preventDefault();
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