<!-- Mobile Header Component -->
<header class="mobile-header d-lg-none">
    <div class="mobile-header-container">
        <!-- Top Header Row -->
        <div class="mobile-header-top">
            <div class="mobile-logo">
                @php
                    $system_light_logo = \App\Models\Setting::where('type', 'system_light_logo')->value('description');
                @endphp
                <a href="{{ route('timeline') }}">
                    <img src="{{ get_system_logo_favicon($system_light_logo,'light') }}" alt="logo" class="mobile-logo-img" />
                </a>
            </div>
            
            <div class="mobile-header-controls">
                <!-- City Search Button -->
                <button class="mobile-city-btn" id="mobileCityBtn">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>Search City</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                
                <!-- Right Icons -->
                <div class="mobile-icons">
                    @if(auth()->user())
                        <!-- Chat Icon -->
                        <a href="#" class="mobile-icon-btn">
                            <i class="fa-solid fa-comment"></i>
                        </a>
                        
                        <!-- Notification Icon -->
                        @php
                            $unread_notification = \App\Models\Notification::where('reciver_user_id', auth()->user()->id)->where('status','0')->count();
                        @endphp
                        <a href="{{ route('notifications') }}" class="mobile-icon-btn position-relative">
                            <i class="fa-solid fa-bell"></i>
                            @if ($unread_notification > 0)
                                <span class="mobile-notification-badge">{{ $unread_notification }}</span>
                            @endif
                        </a>
                        
                        <!-- Profile Icon -->
                        <div class="dropdown">
                            <button class="mobile-icon-btn" type="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-user"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
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
                    @else
                        <!-- Login/Register for non-authenticated users -->
                        <a href="{{ route('login') }}" class="mobile-icon-btn">
                            <i class="fa-solid fa-sign-in-alt"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
                 <!-- Search Bar Row -->
         <div class="mobile-search-row">
             <div class="mobile-search-container">
                 <i class="fa-solid fa-magnifying-glass mobile-search-icon"></i>
                 <select id="mobileSearchInput" class="mobile-search-select" style="width: 100%;">
                     <option value="">{{ get_phrase('Search...') }}</option>
                 </select>
             </div>
         </div>
        
        <!-- Navigation Row -->
        <div class="mobile-nav-row">
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fa-solid fa-bars"></i>
            </button>
            
            <div class="mobile-nav-buttons">
                <button class="mobile-nav-btn" id="forBusinessBtn">
                    <span>For Business</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                
                <button class="mobile-nav-btn" id="forCityGuideBtn">
                    <span>For City Guide</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Dropdown Menus -->
    <div class="mobile-dropdown-overlay" id="mobileDropdownOverlay"></div>
    
    <!-- City Selection Dropdown -->
    <div class="mobile-dropdown mobile-city-dropdown" id="mobileCityDropdown">
        <div class="mobile-dropdown-header">
            <h6>Select City</h6>
            <button class="mobile-dropdown-close" id="closeCityDropdown">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="mobile-dropdown-content">
            <select name="city_slug" id="mobile_city_slug" class="form-select">
                <option value="">-- Select City --</option>
                @foreach ($all_cities as $city)
                    <option value="{{ $city->city_slug }}">{{ $city->city_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    
         <!-- For Business Dropdown -->
     <div class="mobile-dropdown mobile-business-dropdown" id="mobileBusinessDropdown">
         <div class="mobile-dropdown-header">
             <h6>For Business</h6>
             <button class="mobile-dropdown-close" id="closeBusinessDropdown">
                 <i class="fa-solid fa-times"></i>
             </button>
         </div>
         <div class="mobile-dropdown-content">
             <a href="#" class="mobile-dropdown-item">
                 <i class="fa-solid fa-building"></i> Add Business
             </a>
             <a href="#" class="mobile-dropdown-item">
                 <i class="fa-solid fa-tags"></i> Add Deals
             </a>
             @if(auth()->user())
                 <a href="{{ route('profile') }}" class="mobile-dropdown-item">
                     <i class="fa-solid fa-user"></i> My Profile
                 </a>
             @else
                 <a href="{{ route('login') }}" class="mobile-dropdown-item">
                     <i class="fa-solid fa-sign-in-alt"></i> Login/SignUp
                 </a>
             @endif
         </div>
     </div>
    
         <!-- For City Guide Dropdown -->
     <div class="mobile-dropdown mobile-guide-dropdown" id="mobileGuideDropdown">
         <div class="mobile-dropdown-header">
             <h6>For City Guide</h6>
             <button class="mobile-dropdown-close" id="closeGuideDropdown">
                 <i class="fa-solid fa-times"></i>
             </button>
         </div>
         <div class="mobile-dropdown-content">
             <a href="#" class="mobile-dropdown-item">
                 <i class="fa-solid fa-map-marked-alt"></i> City Guide
             </a>
             <a href="#" class="mobile-dropdown-item">
                 <i class="fa-solid fa-tags"></i> Deals
             </a>
             <a href="#" class="mobile-dropdown-item">
                 <i class="fa-solid fa-user-friends"></i> Following
             </a>
             <a href="#" class="mobile-dropdown-item">
                 <i class="fa-solid fa-calendar-alt"></i> Event
             </a>
             <a href="#" class="mobile-dropdown-item">
                 <i class="fa-solid fa-rss"></i> Feed
             </a>
             <a href="#" class="mobile-dropdown-item">
                 <i class="fa-solid fa-video"></i> Trending Video
             </a>
             <a href="#" class="mobile-dropdown-item">
                 <i class="fa-solid fa-newspaper"></i> Blog
             </a>
             <a href="#" class="mobile-dropdown-item">
                 <i class="fa-solid fa-clipboard-list"></i> Post Requirement
             </a>
             @if(auth()->user())
                 <a href="{{ route('profile') }}" class="mobile-dropdown-item">
                     <i class="fa-solid fa-user"></i> My Profile
                 </a>
             @else
                 <a href="{{ route('login') }}" class="mobile-dropdown-item">
                     <i class="fa-solid fa-sign-in-alt"></i> Login/SignUp
                 </a>
             @endif
         </div>
     </div>
</header>

<style>
/* Mobile Header Styles */
.mobile-header {
    background: white;
    border-bottom: 1px solid #e9ecef;
    position: sticky;
    top: 0;
    z-index: 1000;
    padding: 10px 15px;
}

.mobile-header-container {
    max-width: 100%;
}

/* Top Header Row */
.mobile-header-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
}

.mobile-logo-img {
    height: 30px;
    width: auto;
}

.mobile-header-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.mobile-city-btn {
    display: flex;
    align-items: center;
    gap: 5px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 20px;
    padding: 8px 12px;
    font-size: 14px;
    color: #333;
    cursor: pointer;
    transition: all 0.3s ease;
}

.mobile-city-btn:hover {
    background: #e9ecef;
}

.mobile-icons {
    display: flex;
    align-items: center;
    gap: 8px;
}

.mobile-icon-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    background: transparent;
    border: none;
    color: #333;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.mobile-icon-btn:hover {
    color: #FF4939;
}

.mobile-notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #FF4939;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Search Row */
.mobile-search-row {
    margin-bottom: 15px;
}

.mobile-search-container {
    position: relative;
    width: 100%;
}

.mobile-search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    font-size: 16px;
}

   .mobile-search-select {
      width: 100%;
      padding: 12px 15px 12px 45px;
      padding-right: 55px; /* Space for search button */
      border: 1px solid #e9ecef;
      border-radius: 25px;
      font-size: 14px;
      background: white;
      outline: none;
      transition: border-color 0.3s ease;
  }

 .mobile-search-select:focus {
     border-color: #FF4939;
 }

 /* Select2 Mobile Styling */
 .mobile-search-container .select2-container {
     width: 100% !important;
 }

 .mobile-search-container .select2-container .select2-selection--single {
     height: 45px !important;
     border: none !important;
     background: transparent !important;
     padding: 12px 15px 12px 45px !important;
     padding-right: 55px !important;
     border-radius: 25px !important;
     font-size: 14px !important;
 }

 .mobile-search-container .select2-container--default .select2-selection--single .select2-selection__rendered {
     line-height: 20px !important;
     padding-left: 0 !important;
     color: #333 !important;
 }

 .mobile-search-container .select2-container--default .select2-selection--single .select2-selection__placeholder {
     color: #666 !important;
 }

 .mobile-search-container .select2-container--default .select2-selection--single .select2-selection__arrow {
     display: none !important;
 }

 .mobile-search-container .select2-dropdown {
     border: 1px solid #e9ecef !important;
     border-radius: 12px !important;
     box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
     z-index: 9999 !important;
 }

 .mobile-search-container .select2-results__option {
     padding: 10px 15px !important;
     font-size: 14px !important;
 }

 .mobile-search-container .select2-results__option--highlighted[aria-selected] {
     background-color: #FF4939 !important;
     color: white !important;
 }

/* Navigation Row */
.mobile-nav-row {
    display: flex;
    align-items: center;
    gap: 10px;
}

.mobile-menu-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    color: #333;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.mobile-menu-btn:hover {
    background: #e9ecef;
}

.mobile-nav-buttons {
    display: flex;
    gap: 10px;
    flex: 1;
}

.mobile-nav-btn {
    display: flex;
    align-items: center;
    gap: 5px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 20px;
    padding: 10px 15px;
    font-size: 14px;
    color: #333;
    cursor: pointer;
    transition: all 0.3s ease;
    flex: 1;
    justify-content: center;
}

.mobile-nav-btn:hover {
    background: #e9ecef;
}

/* Dropdown Styles */
.mobile-dropdown-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1001;
    display: none;
}

.mobile-dropdown {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    z-index: 1002;
    width: 90%;
    max-width: 350px;
    display: none;
}

.mobile-dropdown-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 20px 15px;
    border-bottom: 1px solid #e9ecef;
}

.mobile-dropdown-header h6 {
    margin: 0;
    font-weight: 600;
    color: #333;
}

.mobile-dropdown-close {
    background: none;
    border: none;
    font-size: 18px;
    color: #666;
    cursor: pointer;
    padding: 5px;
}

.mobile-dropdown-content {
    padding: 15px 20px 20px;
}

.mobile-dropdown-item {
    display: block;
    padding: 12px 0;
    color: #333;
    text-decoration: none;
    border-bottom: 1px solid #f8f9fa;
    transition: color 0.3s ease;
}

.mobile-dropdown-item:last-child {
    border-bottom: none;
}

.mobile-dropdown-item:hover {
    color: #FF4939;
    text-decoration: none;
}

/* Show dropdown when active */
.mobile-dropdown.active,
.mobile-dropdown-overlay.active {
    display: block;
}

/* Responsive adjustments */
@media (max-width: 480px) {
    .mobile-header {
        padding: 8px 10px;
    }
    
    .mobile-nav-buttons {
        gap: 5px;
    }
    
    .mobile-nav-btn {
        padding: 8px 10px;
        font-size: 12px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // City dropdown functionality
    const cityBtn = document.getElementById('mobileCityBtn');
    const cityDropdown = document.getElementById('mobileCityDropdown');
    const closeCityDropdown = document.getElementById('closeCityDropdown');
    const overlay = document.getElementById('mobileDropdownOverlay');
    
    cityBtn.addEventListener('click', function() {
        cityDropdown.classList.add('active');
        overlay.classList.add('active');
    });
    
    closeCityDropdown.addEventListener('click', function() {
        cityDropdown.classList.remove('active');
        overlay.classList.remove('active');
    });
    
    // Business dropdown functionality
    const businessBtn = document.getElementById('forBusinessBtn');
    const businessDropdown = document.getElementById('mobileBusinessDropdown');
    const closeBusinessDropdown = document.getElementById('closeBusinessDropdown');
    
    businessBtn.addEventListener('click', function() {
        businessDropdown.classList.add('active');
        overlay.classList.add('active');
    });
    
    closeBusinessDropdown.addEventListener('click', function() {
        businessDropdown.classList.remove('active');
        overlay.classList.remove('active');
    });
    
    // City Guide dropdown functionality
    const guideBtn = document.getElementById('forCityGuideBtn');
    const guideDropdown = document.getElementById('mobileGuideDropdown');
    const closeGuideDropdown = document.getElementById('closeGuideDropdown');
    
    guideBtn.addEventListener('click', function() {
        guideDropdown.classList.add('active');
        overlay.classList.add('active');
    });
    
    closeGuideDropdown.addEventListener('click', function() {
        guideDropdown.classList.remove('active');
        overlay.classList.remove('active');
    });
    
         // Close dropdowns when clicking overlay
     overlay.addEventListener('click', function() {
         document.querySelectorAll('.mobile-dropdown').forEach(dropdown => {
             dropdown.classList.remove('active');
         });
         overlay.classList.remove('active');
     });
     
     // Close dropdowns when pressing Escape key
     document.addEventListener('keydown', function(e) {
         if (e.key === 'Escape') {
             document.querySelectorAll('.mobile-dropdown').forEach(dropdown => {
                 dropdown.classList.remove('active');
             });
             overlay.classList.remove('active');
         }
     });
    
                   // Initialize Select2 for mobile search (same as original header)
      $("#mobileSearchInput").select2({
          placeholder: "{{ get_phrase('Search...') }}",
          allowClear: true,
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

      // Handle item selection (same as original header)
      $('#mobileSearchInput').on('select2:select', function (e) {
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
    
         // City selection change
     const citySelect = document.getElementById('mobile_city_slug');
     citySelect.addEventListener('change', function() {
         if (this.value) {
             window.location.href = `/${this.value}`;
         }
     });
     
     // Hamburger menu functionality
     const menuBtn = document.getElementById('mobileMenuBtn');
     const menuDropdown = document.getElementById('mobileMenuDropdown');
     const closeMenuDropdown = document.getElementById('closeMenuDropdown');
     
     if (menuBtn) {
         menuBtn.addEventListener('click', function() {
             // Create menu dropdown if it doesn't exist
             if (!menuDropdown) {
                 createMobileMenuDropdown();
             } else {
                 menuDropdown.classList.add('active');
                 overlay.classList.add('active');
             }
         });
     }
     
     // Function to create mobile menu dropdown
     function createMobileMenuDropdown() {
         const menuDropdown = document.createElement('div');
         menuDropdown.className = 'mobile-dropdown mobile-menu-dropdown';
         menuDropdown.id = 'mobileMenuDropdown';
         
         menuDropdown.innerHTML = `
             <div class="mobile-dropdown-header">
                 <h6>Menu</h6>
                 <button class="mobile-dropdown-close" id="closeMenuDropdown">
                     <i class="fa-solid fa-times"></i>
                 </button>
             </div>
             <div class="mobile-dropdown-content">
                 <a href="{{ route('timeline') }}" class="mobile-dropdown-item">
                     <i class="fa-solid fa-home"></i> Home
                 </a>
                 <a href="#" class="mobile-dropdown-item">
                     <i class="fa-solid fa-building"></i> Businesses
                 </a>
                 <a href="#" class="mobile-dropdown-item">
                     <i class="fa-solid fa-calendar"></i> Events
                 </a>
                 <a href="#" class="mobile-dropdown-item">
                     <i class="fa-solid fa-newspaper"></i> Blog
                 </a>
                 <a href="#" class="mobile-dropdown-item">
                     <i class="fa-solid fa-users"></i> Groups
                 </a>
                 <a href="#" class="mobile-dropdown-item">
                     <i class="fa-solid fa-shopping-cart"></i> Marketplace
                 </a>
                 <a href="#" class="mobile-dropdown-item">
                     <i class="fa-solid fa-video"></i> Videos
                 </a>
                 <a href="#" class="mobile-dropdown-item">
                     <i class="fa-solid fa-image"></i> Photos
                 </a>
                 @if(auth()->user())
                     <hr class="my-3">
                     <a href="{{ route('profile') }}" class="mobile-dropdown-item">
                         <i class="fa-solid fa-user"></i> My Profile
                     </a>
                     <a href="{{ route('notifications') }}" class="mobile-dropdown-item">
                         <i class="fa-solid fa-bell"></i> Notifications
                     </a>
                     <a href="#" class="mobile-dropdown-item">
                         <i class="fa-solid fa-cog"></i> Settings
                     </a>
                     <a href="{{ route('logout') }}" class="mobile-dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                         <i class="fa-solid fa-sign-out-alt"></i> Logout
                     </a>
                     <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                         @csrf
                     </form>
                 @else
                     <hr class="my-3">
                     <a href="{{ route('login') }}" class="mobile-dropdown-item">
                         <i class="fa-solid fa-sign-in-alt"></i> Login
                     </a>
                     <a href="{{ route('register') }}" class="mobile-dropdown-item">
                         <i class="fa-solid fa-user-plus"></i> Register
                     </a>
                 @endif
             </div>
         `;
         
         document.body.appendChild(menuDropdown);
         
         // Add event listener to close button
         const closeBtn = menuDropdown.querySelector('#closeMenuDropdown');
         closeBtn.addEventListener('click', function() {
             menuDropdown.classList.remove('active');
             overlay.classList.remove('active');
         });
         
         // Show the dropdown
         menuDropdown.classList.add('active');
         overlay.classList.add('active');
     }
 });
</script>
