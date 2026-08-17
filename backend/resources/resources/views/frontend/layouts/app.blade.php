<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-PZ8171LDK1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-PZ8171LDK1');
    </script>

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-P88PMC');
    </script>
    <!-- End Google Tag Manager -->

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="stylesheet" href="{{ asset('assets/frontend/css/header_new.css') }}?v={{ time() }}">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="icon" type="image/png" href="https://cdn.cityhangaround.com/file/cityhangaround/uploads/wYMNRNks5mOFSD2V1eOkZbjeH0m3dhFpyT79tsHW.png">

    <!--<link rel="stylesheet" href="{{ asset('assets/frontend/css/custom_header.css') }}?v={{ time() }}">-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet" />
    <!-- Include Select2 JavaScript -->
    
    
     <!--  Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/landingpage-new.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/landingpage-new-hero.css') }}">

    <!-- Bootstrap JavaScript -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
 <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-P88PMC');
</script>
<!-- End Google Tag Manager -->
    <style>
        :root {
            --accent: #ff4b4b;
            --muted: #f3f4f6;
            --text: #333;
        }

        body {
            font-family: "Segoe UI", sans-serif;
            background-color: #f9f9f9;
        }

        header {
            background: #fff;
            border-bottom: 1px solid #ddd;
            position: relative;
            z-index: 1000;
        }

        .logo img {
            width: 110px;
        }

        .advertise,
        .login-btn {
            background-color: #ff4939;
            color: #fff;
            border: none;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 14px;
            transition: background 0.3s ease;
        }

        .advertise:hover,
        .login-btn:hover {
            background-color: #e03d2f;
        }

        .navbar-toggler {
            border: none;
            font-size: 20px;
            background: transparent;
            color: #333;
        }

        /* ===== Search Bar ===== */
        .main-search-container {
            display: flex;
            justify-content: center;
            margin: 10px auto;
            width: 100%;
            padding: 0 10px;
        }

        .search-inner {
            display: flex;
            align-items: center;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 15px;
            padding: 5px 10px;
            width: 100%;
            max-width: 700px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .city-dropdown-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .location-icon {
            color: #ff4b4b;
        }

        .divider {
            width: 1px;
            height: 25px;
            background: #ddd;
            margin: 0 10px;
        }

        .search-box {
            display: flex;
            align-items: center;
            flex: 1;
            gap: 10px;
        }

        .search-input {
            border: none;
            outline: none;
            flex: 1;
            background: transparent;
        }

        .search-icon {
            color: #888;
        }

        /* ===== Sidebar ===== */
        .sidebar {
            position: fixed;
            top: 0;
            right: -300px;
            width: 260px;
            height: 100%;
            background: #fff;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            transition: right 0.3s ease;
            padding: 20px;
            overflow-y: auto;
        }

        .sidebar.active {
            right: 0;
        }

        .sidebar-close {
            text-align: right;
            font-size: 20px;
            cursor: pointer;
        }

        /* ===== Desktop Layout ===== */
        @media (min-width: 768px) {
            .desktop-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 15px;
                padding: 10px 20px;
                position: relative;
            }

            .top-header,
            .mobile-search-row {
                display: none;
            }

            .main-search-container {
                margin: 0;
            }

            .desktop-right {
                display: flex;
                align-items: center;
                gap: 15px;
                position: relative;
                margin-right: 2rem;
            }

            /* For Business dropdown fix */
            .dropdown-wrapper {
                position: relative;
                cursor: pointer;
                color: #000000;
            }

            .menu-dropdown {
                position: absolute;
                top: 100%;
                left: 0;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 10px;
                padding: 8px 0;
                width: 220px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                z-index: 9999;
                display: none;
                cursor: pointer;
            }

            .menu-dropdown.active {
                display: block !important;
            }

            .menu-dropdown a {
                display: block;
                padding: 8px 15px;
                color: #333;
                text-decoration: none;
                transition: background 0.3s ease;
            }

            .menu-dropdown a:hover {
                background: #f8f8f8;
                color: #e03d2f;
            }
        }

        /* ===== Mobile Layout ===== */
        @media (max-width: 767px) {
            .desktop-header {
                display: none;
            }

            .top-header {
                display: flex;
                align-items: center;
                justify-content: space-around;
                padding: 8px 12px;
                flex-wrap: nowrap;
            }

            .mobile-search-row {
                padding: 0 10px 0px;
            }

            .main-search-container {
                padding: 0;
                height: 50px;
            }

            .menu-dropdown {
                display: none;
                border-left: 2px solid #e03d2f;
                margin-top: 8px;
                margin-left: 5px;
                cursor: pointer;
            }

            .menu-dropdown a {
                display: block;
                padding: 8px 12px;
                color: #333;
                text-decoration: none;
                transition: background 0.3s ease;
            }

            .menu-dropdown a:hover {
                background: #f0f0f0;
            }
        }

        /* location dropdown */
        .custom-dropdown {
            position: relative;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 35px;
            left: 0;
            background: white;
            border: 1px solid #e0e0e0;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            border-radius: 10px;
            width: 200px;
            z-index: 1000;
            padding: 10px;
            animation: fadeIn 0.2s ease-in-out;
        }

        .dropdown-menu.active {
            display: block;
        }

        .dropdown-search {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 8px;
            outline: none;
        }

        .dropdown-options {
            max-height: 160px;
            overflow-y: auto;
        }

        .option {
            padding: 8px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .option:hover {
            background-color: #ffefef;
            color: #ff4b4b;
        }

        /* ===== Scroll Menu ===== */
        /* Scroll menu wrapper */
        .scroll-wrap {
            position: relative;
            background: #fff;
            /* border-top: 1px solid #e6e6e6; */
            /* border-bottom: 1px solid #e6e6e6; */
            z-index: 10;
        }

        /* Scroll menu */

        .scroll-menu a:hover,
        .scroll-menu a.active {
            background: var(--muted);
            color: var(--accent);
        }

        /* Container for mega menus */
        /* Container for mega menus */
        .mega-menus {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            /*background: #fff;*/
            z-index: 999;
            display: flex;
            justify-content: center;
            pointer-events: none;
            /* prevent interaction when hidden */
        }

        .mega-menu {
            display: none;
            /* hidden by default */
            width: 90%;
            /* almost full width */
            max-width: 1200px;
            background: #fff;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border-radius: 10px;
            padding: 20px 30px;
            animation: slideDown 0.2s ease-in-out;
            pointer-events: auto;
            /* enable interaction when visible */
        }

        .mega-menu.active {
            display: flex;
            /* allow flex layout for columns */
            gap: 20px;
            /* space between columns */
            justify-content: space-around;
        }

        .mega-column {
            flex: 1;
            /* equal width for each column */
        }

        .mega-column h4 a {
            margin: 0 0 8px 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--accent);
        }

        .mega-column a {
            display: block;
            padding: 8px 10px;
            margin: 4px 0;
            border-radius: 6px;
            font-size: 14px;
            text-decoration: none;
            color: var(--text);
            transition: background 0.2s, color 0.2s;
        }

        .mega-column a:hover {
            background: var(--muted);
            color: var(--accent);
        }

        @keyframes slideDown {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== Scroll Menu ===== */
        .scroll-menu {
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
            background: #fff;
            /* border-top: 1px solid #ddd; */
            /* border-bottom: 1px solid #ddd; */
            cursor: pointer;
            justify-content: center;

        }

        .scroll-menu a {
            display: inline-block;
            padding: 10px 18px;
            color: #000000 !important;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            font-size: 1rem;
        }

        .scroll-menu a:hover {
            color: #e03d2f !important;
        }

        /* .scroll-menu::-webkit-scrollbar {
        display: none;
      } */

        @media (max-width: 1028px) {
            .scroll-menu {
                justify-content: flex-start;
            }
        }

        .dropdown-toggle {
            cursor: pointer;
        }

        #selectedCityDesktop {
            color: #000000;
        }
    </style>
    
    @stack('styles')
</head>

<body>
    
    <header>

        <div class="desktop-header">
            <div class="d-flex align-items-center gap-3">
                <!-- Logo -->
                <div class="logo">
                    <a href="/home">
                        <img src="https://cdn.cityhangaround.com/file/cityhangaround/uploads/wYMNRNks5mOFSD2V1eOkZbjeH0m3dhFpyT79tsHW.png"
                            alt="Logo" />
                    </a>

                </div>

                <!-- Search Section -->
                <div class="main-search-container mb-0">
                    <div class="search-inner">

                        <!-- City Dropdown -->
                        <div class="city-dropdown-wrapper">
                            <i class="fas fa-map-marker-alt location-icon"></i>

                            <div class="custom-dropdown">
                                {{-- @php
                $currentCitySlug = request()->segment(1);
                $currentCity = collect($all_cities)->firstWhere('city_slug', $currentCitySlug);
                $cityName = $currentCity ? $currentCity->city_name : 'Select City';
            @endphp --}}

                                <!-- Dropdown Toggle -->
                                <div class="dropdown-toggle" id="cityToggleDesktop"
                                    onclick="toggleCityDropdownDesktop()">
                                    <span id="selectedCityDesktop">{{ $cityName }}</span>
                                </div>

                                <!-- Dropdown Menu -->
                                <div class="dropdown-menu" id="cityMenuDesktop">
                                    <input type="text" id="citySearchDesktop" placeholder="Search city..."
                                        class="dropdown-search" onkeyup="filterCityListDesktop(this)" />
                                    <div class="dropdown-options" id="cityListDesktop">
                                        {{-- @foreach ($all_cities as $city)
                  <div
                    class="option"
                    onclick="selectCityDesktop('{{ $city->city_name }}', '{{ $city->city_slug }}')"
                  >
                    {{ $city->city_name }}
                  </div>
                @endforeach --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="divider"></div>

                        <!-- Search Box -->
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <form id="search-form">
                                <input type="text" id="search-box" name="search"
                                    placeholder="Search for businesses..." class="search-input" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--</div>-->
            <!-- ======= End Desktop Header ======= -->




            <!-- ======= End Desktop Header ======= -->




            <div class="desktop-right">
                <div class="dropdown-wrapper">
                    <div class="dropdown-toggle" id="businessToggleDesktop">
                        For Business
                    </div>
                    <div class="menu-dropdown" id="businessMenuDesktop">
                        <a href="/pages/create">Add Business </a>
                        <a href="/products/create">List Product/Service</a>
                        <a href="/login">Login / Signup</a>
                        <a href="javascript:void(0)" onclick="openEnquiryForm()">Get Leads</a>
                        <a href="/user/subscriptions/view">Advertisement</a>
                        <a href="/pages/custom/influencer">Influencer</a>
                        <a href="/pages/custom/contact-us">Contact</a>
                        <a href="{{ route('public.subscriptions') }}">Promotion</a>
                    </div>
                </div>
                <button class="advertise"
                    onclick="window.location.href='/pages/custom/advertise';">Advertise</button>


                @if (auth()->user())
                    <div class="dropdown profile-control">
                        <button class="btn dropdown-toggle p-0 border-0 bg-transparent" type="button"
                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            @if (auth()->user())
                                <img src="{{ get_user_image(auth()->user()->photo, 'optimized') }}"
                                    class="rounded-circle" width="40" height="40" alt="">
                            @else
                                <img src="{{ get_user_image('', 'optimized') }}" class="rounded-circle" width="40"
                                    height="40" alt="">
                            @endif
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">

                            <li><a class="dropdown-item"
                                    href="{{ route('profile') }}">{{ get_phrase('My Profile') }}</a></li>

                            @if (auth()->user()->user_role == 'admin')
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.dashboard') }}">{{ get_phrase('Go to admin panel') }}</a>
                                </li>
                            @endif

                            @if (auth()->user()->user_role == 'general')
                                <li><a class="dropdown-item"
                                        href="{{ route('user.dashboard') }}">{{ get_phrase('Dashboard') }}</a></li>
                            @endif

                            <li><a class="dropdown-item"
                                    href="{{ route('user.password.change') }}">{{ get_phrase('Change Password') }}</a>
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">{{ get_phrase('Log Out') }}</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <button class="login-btn" onclick="window.location.href='{{ route('login') }}'">Login</button>
                @endif




                <!--<button class="login-btn">Login</button>-->
            </div>
        </div>

        

        <!-- ======= Mobile Header ======= -->
        <div class="top-header">
            <div class="logo">
                <a href="/home">
                    <img src="https://cdn.cityhangaround.com/file/cityhangaround/uploads/wYMNRNks5mOFSD2V1eOkZbjeH0m3dhFpyT79tsHW.png"
                        alt="Logo" />
                </a>

            </div>
            <button class="advertise"
                onclick="window.location.href='/pages/custom/advertise';">Advertise</button>

            <!--<button class="login-btn">Login</button>-->
            @if (auth()->user())
                <div class="dropdown profile-control">
                    <button class="btn dropdown-toggle p-0 border-0 bg-transparent" type="button"
                        id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                        @if (auth()->user())
                            <img src="{{ get_user_image(auth()->user()->photo, 'optimized') }}"
                                class="rounded-circle" width="40" height="40" alt="">
                        @else
                            <img src="{{ get_user_image('', 'optimized') }}" class="rounded-circle" width="40"
                                height="40" alt="">
                        @endif
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">

                        <li><a class="dropdown-item"
                                href="{{ route('profile') }}">{{ get_phrase('My Profile') }}</a></li>

                        @if (auth()->user()->user_role == 'admin')
                            <li><a class="dropdown-item"
                                    href="{{ route('admin.dashboard') }}">{{ get_phrase('Go to admin panel') }}</a>
                            </li>
                        @endif

                        @if (auth()->user()->user_role == 'general')
                            <li><a class="dropdown-item"
                                    href="{{ route('user.dashboard') }}">{{ get_phrase('Dashboard') }}</a></li>
                        @endif

                        <li><a class="dropdown-item"
                                href="{{ route('user.password.change') }}">{{ get_phrase('Change Password') }}</a>
                        </li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">{{ get_phrase('Log Out') }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <button class="login-btn" onclick="window.location.href='{{ route('login') }}'">Login</button>
            @endif
            <button class="navbar-toggler" id="menuBtn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
       
        
        <div class="mobile-search-row">
            <div class="main-search-container">
                <div class="search-inner">
                    <div class="city-dropdown-wrapper">
                        <i class="fas fa-map-marker-alt location-icon"></i>
                        <div class="custom-dropdown">
                            {{-- @php 
            $currentCitySlug = request()->segment(1); 
            $currentCity = collect($all_cities)->firstWhere('city_slug', $currentCitySlug);
            $cityName = $currentCity ? $currentCity->city_name : 'Select City';
          @endphp --}}
                            <div class="dropdown-toggle" id="cityToggleMobile" onclick="toggleCityDropdownDesktop()">
                                <span id="selectedCityMobile">{{ $cityName }}</span>
                            </div>
                            <div class="dropdown-menu" id="cityMenuMobile">
                                <input type="text" id="citySearchMobile" placeholder="Search city..."
                                    class="dropdown-search" onkeyup="filterCityListDesktop(this)" />
                                <div class="dropdown-options" id="cityListMobile">
                                    {{-- @foreach ($all_cities as $city)
              <div
                class="option"
                onclick="selectCityDesktop('{{ $city->city_name }}', '{{ $city->city_slug }}')"
              >
                {{ $city->city_name }}
              </div>
              @endforeach --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Search for businesses..." class="search-input" />
                    </div>
                </div>
            </div>
        </div>

        <div class="scroll-wrap">
            <div class="scroll-menu">
                <a data-menu="cityGuide">City Guide ▾</a>
                <a data-menu="Sell">Buy/Sell ▾</a>
                <a data-menu="marketplace">Marketplace ▾</a>
                <a data-menu="community">Community ▾</a>
                <a data-menu="event">Event ▾</a>
                <a href="{{ route('timeline') }}">Feed</a>
                <a href="{{ route('videos') }}">Trending Video</a>
                <a data-menu="blog">Blog ▾</a>
                <a href="/category/influencer">Influencer</a>
            </div>

            <div class="mega-menus" id="megaContainer">
                <!-- City Guide -->
                <div class="mega-menu" id="cityGuide">
                    @if($menuCategories->isEmpty())
                        <div class="mega-column w-100 text-center py-4" style="flex: 1; padding: 20px;">
                            <p class="text-muted mb-2">No businesses listed in {{ $cityName }} yet.</p>
                            <a href="/pages/create" class="btn btn-sm btn-danger text-white px-3" style="display:inline-block; border-radius:6px; background-color:#ff4939; border:none; padding: 6px 12px; text-decoration:none; font-size:13px; font-weight:600;">Add Business</a>
                        </div>
                    @else
                        @foreach ($menuCategories->chunk(4) as $chunk)
                            <div class="mega-column">
                                @foreach ($chunk as $category)
                                    <a href="{{ $currentCity ? route('page.category.city', ['category_slug' => $category->category_slug, 'city_slug' => $currentCity->city_slug]) : route('page.category', $category->category_slug) }}">
                                        {{ $category->category_name }}
                                    </a>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Marketplace -->
                <div class="mega-menu" id="marketplace">
                    @if($marketplaceCategories->isEmpty())
                        <div class="mega-column w-100 text-center py-4" style="flex: 1; padding: 20px;">
                            <p class="text-muted mb-2">No deals/products in {{ $cityName }} yet.</p>
                            <a href="/products/create" class="btn btn-sm btn-danger text-white px-3" style="display:inline-block; border-radius:6px; background-color:#ff4939; border:none; padding: 6px 12px; text-decoration:none; font-size:13px; font-weight:600;">Add Deal</a>
                        </div>
                    @else
                        @php
                            $chunks = $marketplaceCategories->chunk(ceil($marketplaceCategories->count() / 3));
                        @endphp
                        @foreach ($chunks as $chunk)
                            <div class="mega-column">
                                @foreach ($chunk as $category)
                                    <a href="{{ $currentCity ? route('product.category.city', ['category_slug' => $category->product_category_slug, 'city_slug' => $currentCity->city_slug]) : route('product.category', $category->product_category_slug) }}">
                                        {{ ucwords($category->product_category_name) }}
                                    </a>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- buy/sell -->
                <div class="mega-menu" id="Sell">
                    <div class="mega-column">
                        <a href="">Post Requirement</a>
                        <a href="/pages/create">Add Business</a>
                        <a href="/products/create">Add Deals</a>
                    </div>
                </div>

                <!-- Community -->
                <div class="mega-menu" id="community">
                    @php
                        $chunks = $groupCategories->chunk(ceil($groupCategories->count() / 3));
                    @endphp

                    @foreach ($chunks as $chunk)
                        <div class="mega-column">
                            @foreach ($chunk as $category)
                                <a href="{{ route('category.group', $category->category_slug ?? $category->id) }}">
                                    {{ ucwords($category->category_name) }}
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <!-- Event -->
                <div class="mega-menu" id="event">
                    @if($eventCategories->isEmpty())
                        <div class="mega-column w-100 text-center py-4" style="flex: 1; padding: 20px;">
                            <p class="text-muted mb-2">No events scheduled in {{ $cityName }} yet.</p>
                            <a href="/pages/create" class="btn btn-sm btn-danger text-white px-3" style="display:inline-block; border-radius:6px; background-color:#ff4939; border:none; padding: 6px 12px; text-decoration:none; font-size:13px; font-weight:600;">Add Event</a>
                        </div>
                    @else
                        @php
                            $chunks = $eventCategories->chunk(ceil($eventCategories->count() / 3));
                        @endphp
                        @foreach ($chunks as $chunk)
                            <div class="mega-column">
                                @foreach ($chunk as $category)
                                    <a href="{{ $currentCity ? route('event.category.city', ['category_slug' => ($category->category_slug ?? $category->id), 'city_slug' => $currentCity->city_slug]) : route('event.category', ($category->category_slug ?? $category->id)) }}">
                                        {{ ucwords($category->category_name) }}
                                    </a>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Blog -->
                <div class="mega-menu" id="blog">
                    @if($blogCategories->isEmpty())
                        <div class="mega-column w-100 text-center py-4" style="flex: 1; padding: 20px;">
                            <p class="text-muted mb-2">No blogs published in {{ $cityName }} yet.</p>
                            <a href="/blog/create" class="btn btn-sm btn-danger text-white px-3" style="display:inline-block; border-radius:6px; background-color:#ff4939; border:none; padding: 6px 12px; text-decoration:none; font-size:13px; font-weight:600;">Add Blog</a>
                        </div>
                    @else
                        @php
                            $chunks = $blogCategories->chunk(ceil($blogCategories->count() / 3));
                        @endphp
                        @foreach ($chunks as $chunk)
                            <div class="mega-column">
                                @foreach ($chunk as $category)
                                    <a href="{{ $currentCity ? route('blog.category.city', ['category_slug' => ($category->category_slug ?? $category->id), 'city_slug' => $currentCity->city_slug]) : route('category.blog', ($category->category_slug ?? $category->id)) }}">
                                        {{ ucwords($category->category_name) }}
                                    </a>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- ===== Sidebar (Mobile Burger) ===== -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-close" id="closeSidebar">
            <i class="fas fa-times"></i>
        </div>
        <div class="dropdown-wrapper">
            <div class="dropdown-toggle" id="businessToggleMobile">
                For Business
            </div>
            <div class="menu-dropdown" id="businessMenuMobile">
                <a href="/pages/create">Add Business </a>
                <a href="/products/create">List Product/Service</a>
                <a href="/login">Login / Signup</a>
                <a href="javascript:void(0)" onclick="openEnquiryForm()">Get Leads</a>
                <a href="/user/subscriptions/view">Advertisement</a>
                <a href="/pages/custom/influencer">Influencer</a>
                <a href="/pages/custom/contact-us">Contact</a>
                <a href="#">Promotion</a>
            </div>
        </div>
    </div>

    @yield('content')

    {{-- Enquiry Modal (needed for Get Leads / openEnquiryForm on all pages) --}}
    @include('frontend.partials.enquiry_modal')



    <footer class="footer">
        <!-- About -->
        <div class="footer-column">
            <img src="https://cdn.cityhangaround.com/file/cityhangaround/uploads/wYMNRNks5mOFSD2V1eOkZbjeH0m3dhFpyT79tsHW.png"
                alt="eKal Academy Logo" />
            <h3>About CityHangaround</h3>
            <p>
                Discover What’s Hot Around You – Events, Shops, Creators & More on
                CityHangaround!
            </p>
        </div>

        <!-- Platform -->
        <div class="footer-column">
            <h3>Platform</h3>
            <ul>
                <li><a href="#">Trending Events</a></li>
                <li><a href="#">Trending Business</a></li>
                <li><a href="#">Trending Deals</a></li>
                <li><a href="#">Trending Blogs</a></li>
                <li><a href="#">Trending Videos</a></li>
                <li><a href="#">Trending Discussions</a></li>
            </ul>
        </div>

        <!-- Company -->
        <div class="footer-column">
            <h3>Company</h3>
            <ul>
                <li><a href="#">Contact us</a></li>
                <li><a href="#">Partnership</a></li>
                <li><a href="#">Advertisement</a></li>
                <li><a href="#">Influencers</a></li>
                <li><a href="#">Add Business</a></li>
            </ul>
        </div>

        <!-- Reach Us -->
        <div class="footer-column">
            <h3>Reach Us</h3>
            <form class="contact-form" onsubmit="return validateCaptcha()">
                <input type="text" placeholder="Name" required />
                <input type="tel" placeholder="Phone no." required />
                <input type="email" placeholder="Email id" required />
                <input type="text" placeholder="City" required />
                <textarea placeholder="Query" rows="3" required></textarea>
                <input type="text" id="captchaInput" placeholder="Enter Captcha" required />
                <div>
                    <img src="{{ asset('assets/frontend/images/captacha.png') }}" alt="Captcha"
                        style="margin-bottom: 10px" />
                </div>
                <button type="submit">Submit</button>
            </form>
        </div>
    </footer>
    
    <div class="footer-bottom">
        <p>Powered by Cityhangaround</p>

        <div class="footer-links">
            <a href="https://www.cityhangaround.com/pages/custom/about-us" target="_blank" rel="noopener noreferrer"
                style="display: inline-block">About Us</a>
            <a href="https://www.cityhangaround.com/pages/custom/disclaimer" target="_blank"
                rel="noopener noreferrer" style="display: inline-block">Disclaimer</a>
            <a href="https://www.cityhangaround.com/pages/custom/privacy-policy" target="_blank"
                rel="noopener noreferrer" style="display: inline-block">Privacy Policy</a>
            <a href="https://www.cityhangaround.com/pages/custom/contact-us" target="_blank"
                rel="noopener noreferrer" style="display: inline-block">Contact Us</a>
            <a href="https://www.cityhangaround.com/pages/custom/influencer" target="_blank"
                rel="noopener noreferrer" style="display: inline-block">Influencers</a>
            <a href="https://www.cityhangaround.com/pages/custom/advertise" target="_blank" rel="noopener noreferrer"
                style="display: inline-block">Advertise</a>
            <a href="https://www.cityhangaround.com/pages/custom/terms-and-conditions" target="_blank"
                rel="noopener noreferrer" style="display: inline-block">Terms & Conditions</a>
        </div>

        <div class="social-icons">
            <a href="https://www.facebook.com/Cityhangaround/" target="_blank">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://in.linkedin.com/company/cityhangaround" target="_blank">
                <i class="fab fa-linkedin-in"></i>
            </a>
            <a href="https://www.instagram.com/cityhangaround/" target="_blank">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.youtube.com/c/Cityhangaround" target="_blank">
                <i class="fab fa-youtube"></i>
            </a>
        </div>
    </div>
    <!-- jQuery and Select2 are loaded in the <head> -->

<script>
    // openEnquiryForm: opens the enquiry modal on ALL pages using this layout
    function openEnquiryForm() {
        var modalEl = document.getElementById('enquiryModal');
        if (!modalEl) return;
        if (window.bootstrap && bootstrap.Modal) {
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        } else if (window.jQuery) {
            jQuery('#enquiryModal').modal('show');
        }
    }
</script>
<script>
            $(function() {
                $('#dropdownMenuButton1').on('click', function(e) {
                    e.preventDefault();

                    var $parent = $(this).closest('.dropdown');
                    var $menu = $parent.find('.dropdown-menu');

                    $parent.toggleClass('show');
                    $menu.toggleClass('show');
                    $(this).attr('aria-expanded', $parent.hasClass('show'));
                });

                // close when clicking outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.dropdown').length) {
                        $('.dropdown.show').removeClass('show').find('.dropdown-menu.show').removeClass('show');
                        $('[data-bs-toggle="dropdown"]').attr('aria-expanded', 'false');
                    }
                });
                $('#city_header_main').on('change', function() {
                    var citySlug = $(this).val(); // Get the selected city slug
                    if (citySlug) {
                        let url = `/${citySlug}`; // Build the URL
                        window.location.href = url; // Redirect to the new URL
                    }
                });
            });
        </script>

         <script>
            $(function() {
                $('#dropdownMenuButton2').on('click', function(e) {
                    e.preventDefault();

                    var $parent = $(this).closest('.dropdown');
                    var $menu = $parent.find('.dropdown-menu');

                    $parent.toggleClass('show');
                    $menu.toggleClass('show');
                    $(this).attr('aria-expanded', $parent.hasClass('show'));
                });

                // close when clicking outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.dropdown').length) {
                        $('.dropdown.show').removeClass('show').find('.dropdown-menu.show').removeClass('show');
                        $('[data-bs-toggle="dropdown"]').attr('aria-expanded', 'false');
                    }
                });
                $('#city_header_main').on('change', function() {
                    var citySlug = $(this).val(); // Get the selected city slug
                    if (citySlug) {
                        let url = `/${citySlug}`; // Build the URL
                        window.location.href = url; // Redirect to the new URL
                    }
                });
            });
        </script>
        @include('frontend.partials.global-scripts')
    <script>
        // Sidebar toggle elements
        const sidebar = document.getElementById("sidebar");
        const menuBtn = document.getElementById("menuBtn");
        const closeSidebarBtn = document.getElementById("closeSidebar");

        // ---- Mobile "For Business" dropdown  ----
        const businessToggleMobile = document.getElementById("businessToggleMobile");
        const businessMenuMobile = document.getElementById("businessMenuMobile");

        // Sidebar open (mobile) 
        menuBtn.addEventListener("click", () => {
            sidebar.classList.add("active");
            if (businessMenuMobile) {
                businessMenuMobile.style.display = "block";
                businessToggleMobile.dataset.locked = "true";
            }
        });


        closeSidebarBtn.addEventListener("click", () => {
            sidebar.classList.remove("active");

            if (businessMenuMobile) {
                businessMenuMobile.style.display = "none";
                delete businessToggleMobile.dataset.locked;
            }
        });


        const businessToggleDesktop = document.getElementById("businessToggleDesktop");
        const businessMenuDesktop = document.getElementById("businessMenuDesktop");

        if (businessToggleDesktop && businessMenuDesktop) {
            businessToggleDesktop.addEventListener("click", (e) => {
                e.stopPropagation();
                businessMenuDesktop.classList.toggle("active");
            });

            document.addEventListener("click", (e) => {
                if (
                    !businessToggleDesktop.contains(e.target) &&
                    !businessMenuDesktop.contains(e.target)
                ) {
                    businessMenuDesktop.classList.remove("active");
                }
            });
        }

        if (businessToggleMobile && businessMenuMobile) {
            businessToggleMobile.addEventListener("click", (e) => {
                if (sidebar.classList.contains("active") && businessToggleMobile.dataset.locked === "true") {

                    e.preventDefault();
                    return;
                }

                businessMenuMobile.style.display =
                    businessMenuMobile.style.display === "block" ? "none" : "block";
            });
        }

        function setupCityDropdown(toggleId, menuId, searchId, optionSelector, selectedId) {
            const toggle = document.getElementById(toggleId);
            const menu = document.getElementById(menuId);
            const search = document.getElementById(searchId);
            if (!toggle || !menu) return;
            const options = menu.querySelectorAll(optionSelector);
            const selected = document.getElementById(selectedId);

            toggle.addEventListener("click", () => {
                menu.classList.toggle("active");
                if (search) search.focus();
            });

            options.forEach((opt) => {
                opt.addEventListener("click", () => {
                    if (selected) selected.textContent = opt.textContent;
                    menu.classList.remove("active");
                });
            });

            if (search) {
                search.addEventListener("keyup", () => {
                    const filter = search.value.toLowerCase();
                    options.forEach((opt) => {
                        opt.style.display = opt.textContent.toLowerCase().includes(filter) ?
                            "block" :
                            "none";
                    });
                });
            }

            document.addEventListener("click", (e) => {
                if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.remove("active");
                }
            });
        }

        setupCityDropdown(
            "cityToggleDesktop",
            "cityMenuDesktop",
            "citySearchDesktop",
            ".option",
            "selectedCityDesktop"
        );
        setupCityDropdown(
            "cityToggleMobile",
            "cityMenuMobile",
            "citySearchMobile",
            ".option",
            "selectedCityMobile"
        );
    </script>
    <script>
        const links = document.querySelectorAll(".scroll-menu a[data-menu]");
        const menus = document.querySelectorAll(".mega-menu");

        function hideAll() {
            menus.forEach((menu) => menu.classList.remove("active"));
            links.forEach((link) => link.classList.remove("active"));
        }

        links.forEach((link) => {
            const menuId = link.getAttribute("data-menu");
            const menu = document.getElementById(menuId);

            link.addEventListener("click", (e) => {
                e.preventDefault();
                const isActive = menu.classList.contains("active");
                hideAll();
                if (!isActive) {
                    menu.classList.add("active");
                    link.classList.add("active");
                }
            });
        });

        // Close menu if clicked outside
        document.addEventListener("click", (e) => {
            if (
                !e.target.closest(".scroll-menu") &&
                !e.target.closest(".mega-menu")
            ) {
                hideAll();
            }
        });
    </script>
    <script>
        $(document).ready(function() {

            // Get CSRF token from the meta tag
            // Commented out to prevent initializing Select2 on hidden inputs or non-existent header select dropdowns
            // $('#city_header, #category_header, #city_header_main').select2({
            //     placeholder: function() {
            //         return $(this).data('placeholder');
            //     }
            // });
            $('#city_header').on('change', function() {
                $('#category_header').html("<option selected value='0'>Select Category</option>");

                if (this.value > 0) {
                    var ajax_url = '/ajax/categories/' + this.value;

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    jQuery.ajax({
                        url: ajax_url,
                        method: 'get',
                        data: {},
                        success: function(result) {
                            //console.log(result);
                            $('#category_header').html(
                                "<option selected value='0'>Select Category</option>");
                            $.each(JSON.parse(result), function(key, value) {
                                var city_id = value.id;
                                var city_name = value.category_name;
                                $('#category_header').append('<option value="' +
                                    city_id + '">' + city_name + '</option>');
                            });

                        }
                    });
                }

            });



            console.log('Select2 Init Started (app.blade.php)');
            // Initialize Select2 only when modal is shown
            $('#enquiryModal').on('shown.bs.modal', function() {
                    console.log('Modal Opened (app.blade.php)');
                    // Auto-fill form with user details if logged in
                    @auth
                    // Pre-fill name and mobile if user is logged in
                    $('#name').val('{{ Auth::user()->name }}');

                    // Pre-fill mobile number without country code (remove 91 if present)
                    $('#mobile').val('{{ remove_country_code(Auth::user()->phone) }}');

                    // Pre-fill city if user has a city
                    @if (Auth::user()->city_id)
                        // Create option for user's city
                        var userCityOption = new Option('{{ Auth::user()->city->city_name }}',
                            '{{ Auth::user()->city_id }}', true, true);
                        $('#city_modal').append(userCityOption).trigger('change');
                    @endif
                @endauth

                $('#city_modal').select2({
                    placeholder: "Select City",
                    allowClear: true,
                    ajax: {
                        url: "{{ route('ajax.cities.enquiry') }}", // Your route here
                        dataType: 'json',
                        delay: 250, // Delay before sending request after typing
                        data: function(params) {
                            console.log('Location Search Fired (app.blade.php)', params.term);
                            return {
                                q: params.term // Search term that the user types
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(function(city) {
                                    return {
                                        id: city.id,
                                        text: city.city_name
                                    };
                                })
                            };
                        },
                        cache: true
                    },
                    width: '100%', // Ensure full width
                    dropdownParent: $('#enquiryModal'), // Ensure dropdown appears inside modal
                    minimumInputLength: 1 // Minimum characters required before making the request
                });

                // Product Select2 Initialization
                $('#product').select2({
                    placeholder: "Select Product",
                    allowClear: true,
                    ajax: {
                        url: "{{ route('ajax.products') }}", // Your route for products
                        dataType: 'json',
                        delay: 250, // Delay before sending request after typing
                        data: function(params) {
                            console.log('Product Search Fired (app.blade.php)', params.term);
                            return {
                                q: params.term // Search term that the user types
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(function(product) {
                                    return {
                                        id: product.id,
                                        text: product.title
                                    };
                                })
                            };
                        },
                        cache: true
                    },
                    width: '100%', // Ensure full width
                    dropdownParent: $('#enquiryModal'), // Ensure dropdown appears inside modal
                    minimumInputLength: 1 // Only trigger the AJAX request if the user types at least 1 character
                });
            });

        // Reset form when modal is hidden
        $('#enquiryModal').on('hidden.bs.modal', function() {
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
            event.preventDefault();

            let locationVal = $('#city_modal').val();
            if (!locationVal) {
                Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please select a valid location.' });
                return;
            }

            // city_modal value may be plain id or "id_0" format
            let city_id = locationVal.toString().split('_')[0];
            if (!city_id || isNaN(city_id)) {
                Swal.fire({ icon: 'error', title: 'Invalid Location', text: 'The selected location is invalid. Please try again.' });
                return;
            }

            // Read CSRF token from the @csrf hidden field inside the form
            // This works on ALL pages regardless of whether <meta name="csrf-token"> exists
            let csrfToken = $('#enquiryForm input[name="_token"]').val()
                         || $('meta[name="csrf-token"]').attr('content')
                         || '{{ csrf_token() }}';

            let formData = {
                _token: csrfToken,
                name: $('#name').val(),
                mobile: $('#mobile').val(),
                city_id: city_id,
                product_id: $('#product').val(),
            };

            $.ajax({
                url: "{{ route('enquiry.store') }}",
                method: "POST",
                data: formData,
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Submitted!',
                        text: response.message,
                    });
                    $('#enquiryForm')[0].reset();
                    $('#enquiryModal').modal('hide');
                },
                error: function(xhr) {
                    let errorMessage = 'Something went wrong!';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join(', ');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMessage,
                    });
                }
            });
        });


        });
    </script>
    <script>
        /* Commented out conflicting Select2 search box initialization to enable custom suggestions dropdown
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize Select2 for desktop search
            $("#search-box").select2({
                placeholder: "{{ get_phrase('Search...') }}",
                allowClear: true,
                ajax: {
                    url: "{{ route('search.globally') }}", // Backend route to fetch search results
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term, // Send search query
                            cityid: $("#city_header").val()
                        };
                    },
                    processResults: function(data) {
                        let results = [];

                        if (data.pages.length) {
                            results.push({
                                text: '📄 Pages',
                                children: data.pages.map(page => ({
                                    id: page.id,
                                    text: page.title,
                                    type: 'page',
                                    citySlug: page
                                        .city_slug, // Ensure citySlug is included
                                    areaSlug: page
                                        .area_slug, // Ensure areaSlug is included
                                    categorySlug: page
                                        .category_slug, // Ensure categorySlug is included
                                    itemSlug: page
                                        .item_slug // Ensure itemSlug is included
                                }))
                            });
                        }
                        if (data.marketplace.length) {
                            results.push({
                                text: '🛍️ Deals',
                                children: data.marketplace.map(item => ({
                                    id: item.id,
                                    text: item.title,
                                    type: 'marketplace'
                                }))
                            });
                        }
                        if (data.events.length) {
                            results.push({
                                text: '📅 Events',
                                children: data.events.map(event => ({
                                    id: event.id,
                                    text: event.title,
                                    type: 'event'
                                }))
                            });
                        }
                        if (data.blogs.length) {
                            results.push({
                                text: '📝 Blog',
                                children: data.blogs.map(blog => ({
                                    id: blog.id,
                                    text: blog.title,
                                    type: 'blog'
                                }))
                            });
                        }
                        if (data.users.length) {
                            results.push({
                                text: '👤 Users',
                                children: data.users.map(user => ({
                                    id: user.id,
                                    text: user.name,
                                    type: 'user'
                                }))
                            });
                        }

                        return {
                            results
                        };
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
                        data: function(params) {
                            return {
                                search: params.term, // Send search query
                                cityid: $("#mobile_city_slug").val()
                            };
                        },
                        processResults: function(data) {
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
                                results.push({
                                    text: '🛍️ Deals',
                                    children: data.marketplace.map(item => ({
                                        id: item.id,
                                        text: item.title,
                                        type: 'marketplace'
                                    }))
                                });
                            }
                            if (data.events.length) {
                                results.push({
                                    text: '📅 Events',
                                    children: data.events.map(event => ({
                                        id: event.id,
                                        text: event.title,
                                        type: 'event'
                                    }))
                                });
                            }
                            if (data.blogs.length) {
                                results.push({
                                    text: '📝 Blog',
                                    children: data.blogs.map(blog => ({
                                        id: blog.id,
                                        text: blog.title,
                                        type: 'blog'
                                    }))
                                });
                            }
                            if (data.users.length) {
                                results.push({
                                    text: '👤 Users',
                                    children: data.users.map(user => ({
                                        id: user.id,
                                        text: user.name,
                                        type: 'user'
                                    }))
                                });
                            }

                            return {
                                results
                            };
                        },
                        cache: true
                    }
                });
            }

            // Handle item selection for desktop search
            $('#search-box').on('select2:select', function(e) {
                let selectedData = e.params.data;
                let url = "";

                // Define redirection logic based on search type
                switch (selectedData.type) {
                    case 'page':
                        // Construct the URL for page
                        url =
                            `/${selectedData.citySlug}/${selectedData.areaSlug}/${selectedData.categorySlug}/${selectedData.itemSlug}`;
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
            $('#mobile-search-box').on('select2:select', function(e) {
                let selectedData = e.params.data;
                let url = "";

                // Define redirection logic based on search type
                switch (selectedData.type) {
                    case 'page':
                        // Construct the URL for page
                        url =
                            `/${selectedData.citySlug}/${selectedData.areaSlug}/${selectedData.categorySlug}/${selectedData.itemSlug}`;
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
        */
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
        document.getElementById('search-form').addEventListener('submit', function(e) {
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


       

        // Mobile dropdown toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.getElementById('mobileMenuToggle');
            const mobileDropdown = document.getElementById('mobileDropdownMenu');

            if (mobileToggle && mobileDropdown) {
                console.log('Mobile toggle elements found');
                mobileToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    console.log('Mobile toggle clicked');
                    mobileDropdown.classList.toggle('d-none');
                    console.log('Mobile dropdown visibility:', !mobileDropdown.classList.contains(
                        'd-none'));
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
                                const otherMenu = otherDropdown.querySelector(
                                    '.dropdown-menu');
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
                                    const otherMenu = otherDropdown.querySelector(
                                        '.dropdown-menu');
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

       
    </script>
   
    @stack('scripts')
</body>

</html>
