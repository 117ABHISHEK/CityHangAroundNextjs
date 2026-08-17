<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.onerror = function(message, source, lineno, colno, error) {
            try {
                var errData = {
                    url: window.location.href,
                    message: message,
                    source: source,
                    lineno: lineno,
                    colno: colno,
                    error: error ? error.stack : ''
                };
                fetch('/log_js_error.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(errData)
                });
            } catch(e) {}
        };
    </script>
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
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <!-- Preconnects -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    <!-- Preload LCP hero poster -->
    <link rel="preload" as="image" href="{{ asset('assets/main/images/hero_poster.webp') }}" fetchpriority="high">

    <!-- Critical CSS -->
    <link rel="stylesheet" href="/assets/frontend/css/header_new.css?v=2.0.5" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/assets/frontend/css/custom_header.css?v=2.0.5" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/assets/frontend/css/landingpage-new.css?v=2.0.5" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/assets/frontend/css/landingpage-new-hero.css?v=2.0.5" media="print" onload="this.media='all'">

    <!-- Deferred CSS (non-render-blocking) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" media="print" onload="this.media='all'" />
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" media="print" onload="this.media='all'"></noscript>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" media="print" onload="this.media='all'" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" media="print" onload="this.media='all'" />

    <!-- Google Fonts (single load) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Roboto:wght@400;500&display=swap" rel="stylesheet" media="print" onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet" href="/assets/frontend/css/header_new.css?v=2.0.5">
        <link rel="stylesheet" href="/assets/frontend/css/custom_header.css?v=2.0.5">
        <link rel="stylesheet" href="/assets/frontend/css/landingpage-new.css?v=2.0.5">
        <link rel="stylesheet" href="/assets/frontend/css/landingpage-new-hero.css?v=2.0.5">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Roboto:wght@400;500&display=swap" rel="stylesheet" />
    </noscript>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://cdn.cityhangaround.com/file/cityhangaround/uploads/wYMNRNks5mOFSD2V1eOkZbjeH0m3dhFpyT79tsHW.png">

    <!-- Deferred JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <style>
        /* === Critical Bootstrap resets (prevent CLS when BS loads deferred) === */
        *,*::before,*::after{box-sizing:border-box}
        html{-webkit-text-size-adjust:100%;overflow-x:hidden;max-width:100vw}
        body{margin:0;font-family:'Poppins',sans-serif;background-color:#f9f9f9;line-height:1.5;overflow-x:hidden;max-width:100vw}
        img,video{max-width:100%;height:auto}
        .container{width:100%;padding-right:12px;padding-left:12px;margin-right:auto;margin-left:auto;max-width:100%}
        @media(min-width:576px){.container{max-width:540px}}
        @media(min-width:768px){.container{max-width:720px}}
        @media(min-width:992px){.container{max-width:960px}}
        @media(min-width:1200px){.container{max-width:1140px}}
        .row{display:flex;flex-wrap:wrap;margin-right:-12px;margin-left:-12px}
        .col,.col-auto,[class*="col-"]{padding-right:12px;padding-left:12px;position:relative}
        .d-none{display:none!important}
        .d-flex{display:flex!important}
        /* Prevent font-invisible while loading */
        @font-face{font-display:swap}
        /* === KILL horizontal scroll from fixed off-screen sidebar === */
        @media(max-width:768px){
            html,body{overflow-x:hidden!important;width:100%!important;max-width:100vw!important;position:relative}
            body{padding-bottom:68px!important}
        }

        /* === Disable hero video on mobile (save ~3MB bandwidth) === */
        @media(max-width:767px){
            #heroBgVideo{display:none!important}
        }

        :root {
            --accent: #ff4b4b;
            --muted: #f3f4f6;
            --text: #333;
        }

        body {
            font-family: "Poppins", "Segoe UI", sans-serif;
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
        @media (max-width: 768px) {
            header {
                position: sticky !important;
                top: 0 !important;
                z-index: 1000 !important;
            }

            .desktop-header {
                display: none;
            }

            .top-header {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                padding: 8px 15px !important;
                gap: 8px !important;
                flex-wrap: nowrap !important;
                position: relative !important;
                top: auto !important;
                box-shadow: none !important;
            }

            .top-header-right {
                display: flex;
                align-items: center;
                gap: 8px;
                flex-shrink: 0;
            }

            .profile-control .dropdown-toggle::after {
                display: none !important;
            }

            .logo img {
                height: auto !important;
            }

            .nav-scroll-btn {
                display: none !important;
            }

            .top-header .logo img {
                max-width: 95px;
                height: auto !important;
            }

            .top-header .advertise,
            .top-header .login-btn {
                padding: 6px 10px;
                font-size: 12px;
                font-weight: 600;
                border-radius: 6px;
                white-space: nowrap;
            }

            .mobile-search-row {
                padding: 0 10px 10px;
            }

            .main-search-container {
                padding: 0;
                height: auto;
            }

            .search-inner {
                border-radius: 10px;
                padding: 6px 12px;
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

            /* Responsive Mega Menus for Mobile */
            .mega-menus {
                display: block !important;
                position: fixed !important;
                top: auto !important;
                bottom: calc(60px + env(safe-area-inset-bottom, 0px)) !important;
                left: 0 !important;
                right: 0 !important;
                width: 100vw !important;
                max-width: 100vw !important;
                margin: 0 !important;
                padding: 0 !important;
                pointer-events: none;
                z-index: 10000 !important;
            }

            .mega-menu {
                display: none !important;
                width: 100vw !important;
                max-width: 100vw !important;
                margin: 0 !important;
                box-sizing: border-box !important;
                padding: 15px 15px 25px 15px !important;
                max-height: 55vh !important;
                overflow-y: auto !important;
                overflow-x: hidden !important;
                border-radius: 16px 16px 0 0 !important;
                box-shadow: 0 -4px 30px rgba(0,0,0,0.15) !important;
                pointer-events: auto;
                background: #fff !important;
                animation: slideUp 0.2s ease-in-out !important;
            }

            .mega-menu::-webkit-scrollbar {
                width: 6px;
            }

            .mega-menu::-webkit-scrollbar-track {
                background: transparent;
            }

            .mega-menu::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 3px;
            }

            .mega-menu.active {
                display: block !important;
            }

            @keyframes slideUp {
                0% {
                    opacity: 0;
                    transform: translateY(10px);
                }
                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .mega-column {
                width: 100% !important;
                margin-bottom: 15px;
            }

            .mega-column:last-child {
                margin-bottom: 0;
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

        /* ===================================================
           SCROLL NAV BAR — FLEX ROW (no overflow:hidden clipping)
           =================================================== */
        .scroll-wrap {
            position: relative;
            background: #fff;
            z-index: 10;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
        }
        /* Flex row: [left-btn] [scroll-menu] [right-btn] */
        .scroll-nav-row {
            display: flex;
            align-items: stretch;
            width: 100%;
            overflow: visible;
        }
        .scroll-menu {
            flex: 1;
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
            background: #fff;
            cursor: pointer;
            justify-content: center;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
            min-width: 0;
        }
        .scroll-menu::-webkit-scrollbar { display: none; }

        .scroll-menu a {
            display: inline-flex;
            align-items: center;
            padding: 11px 16px;
            color: #333 !important;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            border-bottom: 2px solid transparent;
            white-space: nowrap;
            flex-shrink: 0;
            transition: color 0.2s, border-color 0.2s;
        }
        .scroll-menu a:hover { color: #e03d2f !important; }
        .scroll-menu a.nav-active {
            color: #e03d2f !important;
            border-bottom-color: #e03d2f;
            font-weight: 600;
        }

        /* Arrow buttons — flex siblings, always in flow */
        .nav-scroll-btn {
            display: none; /* hidden on desktop */
            align-items: center;
            justify-content: center;
            min-width: 34px;
            width: 34px;
            height: auto;
            background: #fff;
            border: none;
            cursor: pointer;
            color: #999;
            font-size: 20px;
            font-weight: 400;
            line-height: 1;
            flex-shrink: 0;
            transition: color 0.2s, background 0.2s;
            padding: 0;
        }
        .nav-scroll-btn:hover { color: #ff4b4b; background: #fff8f8; }
        .nav-scroll-btn.nav-btn-left  { border-right: 1px solid #eee; }
        .nav-scroll-btn.nav-btn-right { border-left:  1px solid #eee; }

        @media (max-width: 1028px) {
            .scroll-menu { justify-content: flex-start; }
            .nav-scroll-btn { display: flex; }
        }

        /* Mega menu: mobile-responsive (removed redundant block, unified below) */

        .dropdown-toggle { cursor: pointer; }
        #selectedCityDesktop { color: #000000; }

        /* ===== Mobile Bottom Navigation Bar (Instagram/Facebook Style) ===== */
        .mobile-bottom-nav {
            display: none;
        }

        @media (max-width: 768px) {
            .mobile-bottom-nav {
                display: flex !important;
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                z-index: 9999 !important;
                background: #fff !important;
                border-top: 1px solid #e8e8e8 !important;
                box-shadow: 0 -4px 20px rgba(0,0,0,0.10) !important;
                height: 60px !important;
                align-items: stretch !important;
                justify-content: space-around !important;
                padding: 0 !important;
                safe-area-inset-bottom: env(safe-area-inset-bottom, 0);
                padding-bottom: env(safe-area-inset-bottom, 0) !important;
            }

            .mob-nav-item {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                flex: 1 !important;
                text-decoration: none !important;
                color: #888 !important;
                gap: 3px !important;
                padding: 6px 4px 4px !important;
                transition: color 0.18s ease !important;
                border: none !important;
                background: transparent !important;
                cursor: pointer !important;
                position: relative !important;
                -webkit-tap-highlight-color: transparent !important;
            }

            .mob-nav-item:hover,
            .mob-nav-item.active {
                color: #ff4b4b !important;
                text-decoration: none !important;
            }

            /* Active indicator dot */
            .mob-nav-item.active::after {
                content: '';
                position: absolute;
                bottom: 4px;
                left: 50%;
                transform: translateX(-50%);
                width: 4px;
                height: 4px;
                background: #ff4b4b;
                border-radius: 50%;
            }

            .mob-nav-item i,
            .mob-nav-item svg {
                font-size: 20px !important;
                line-height: 1 !important;
                transition: transform 0.18s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
            }

            .mob-nav-item:active i {
                transform: scale(0.85) !important;
            }

            .mob-nav-item.active i {
                transform: scale(1.08) !important;
            }

            .mob-nav-label {
                font-size: 9.5px !important;
                font-weight: 600 !important;
                letter-spacing: 0.2px !important;
                line-height: 1 !important;
                color: inherit !important;
            }

            /* Center "Post" button — highlighted */
            .mob-nav-item.mob-nav-post {
                color: #ff4b4b !important;
            }

            .mob-nav-item.mob-nav-post .mob-nav-post-btn {
                width: 44px;
                height: 44px;
                background: linear-gradient(135deg, #ff4b4b 0%, #ff7856 100%);
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff !important;
                box-shadow: 0 4px 16px rgba(255,75,75,0.40);
                transition: transform 0.18s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.18s ease;
                margin-top: -8px;
            }

            .mob-nav-item.mob-nav-post:active .mob-nav-post-btn {
                transform: scale(0.90);
                box-shadow: 0 2px 8px rgba(255,75,75,0.30);
            }

            .mob-nav-item.mob-nav-post .mob-nav-post-btn i {
                font-size: 18px !important;
                transform: none !important;
            }

            /* Notification badge */
            .mob-nav-badge {
                position: absolute;
                top: 4px;
                right: calc(50% - 18px);
                min-width: 16px;
                height: 16px;
                background: #e41e3f;
                color: #fff;
                font-size: 9px;
                font-weight: 700;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0 3px;
                line-height: 1;
                border: 2px solid #fff;
            }
        }

            </style>
    
    @stack('styles')
</head>

<body class="landing-page">

    {{-- Shimmer templates and progress bar provided by @include('frontend.header') --}}

    @include('frontend.header')

    {{-- Sidebar, overlay and bottom nav are rendered by @include('frontend.header') above --}}

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

@include('frontend.partials.global-scripts')
@stack('scripts')

    {{-- Navigation shimmer script handled by @include('frontend.header') --}}

    <script>
    // ============================================================
    // NAV SCROLL ARROWS (flex-sibling approach, no inner wrapper)
    // ============================================================
    (function() {
        var menu = document.getElementById('mainScrollMenuH');
        var btnL = document.getElementById('navScrollLeftH');
        var btnR = document.getElementById('navScrollRightH');
        if (!menu) return;

        var STEP = 150;

        if (btnL) btnL.addEventListener('click', function(e) {
            e.preventDefault();
            menu.scrollBy({ left: -STEP, behavior: 'smooth' });
        });
        if (btnR) btnR.addEventListener('click', function(e) {
            e.preventDefault();
            menu.scrollBy({ left:  STEP, behavior: 'smooth' });
        });

        function updateBtns() {
            var atStart = menu.scrollLeft <= 2;
            var atEnd   = menu.scrollLeft >= (menu.scrollWidth - menu.clientWidth - 2);
            if (btnL) btnL.style.opacity = atStart ? '0.35' : '1';
            if (btnR) btnR.style.opacity = atEnd   ? '0.35' : '1';
        }
        menu.addEventListener('scroll', updateBtns, { passive: true });
        updateBtns();

        // Highlight active nav link
        var path = window.location.pathname;
        menu.querySelectorAll('a[href]').forEach(function(a) {
            try {
                var u = new URL(a.href, window.location.origin);
                if (u.pathname.length > 1 && path.startsWith(u.pathname)) {
                    a.classList.add('nav-active');
                }
            } catch(e) {}
        });
    })();
    </script>
</body>

</html>
