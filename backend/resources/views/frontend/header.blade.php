@php
    $user_info = auth()->user();
    $urlCityId = $currentCity->id ?? null;
    $urlCityName = $currentCity->city_name ?? null;
    $initialCityName = $urlCityName ?? 'Select City';
@endphp

@include('frontend.partials.enquiry_modal')




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

    .sugg-card {
        min-height: 100%;
    }

    .sugg-card a:hover {
        color: #FF4939 !important;
    }

    header a:hover {
        color: #FF4939 !important;
    }

    header .tags a:focus,
    header .tags a:hover {
        border-color: #FF4939 !important;
        background-color: transparent !important;
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
        position: relative;
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

    /* ===== Mobile Sidebar CTA Quick Actions ===== */
    .mob-sidebar-cta-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        margin: 8px 0 4px;
    }

    .mob-sidebar-cta-tile {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 12px 8px;
        border-radius: 12px;
        background: color-mix(in srgb, var(--cta-c, #ff4b4b) 10%, white);
        border: 1.5px solid color-mix(in srgb, var(--cta-c, #ff4b4b) 25%, white);
        text-decoration: none !important;
        transition: transform 0.15s ease, background 0.15s ease;
    }

    .mob-sidebar-cta-tile:hover,
    .mob-sidebar-cta-tile:active {
        background: color-mix(in srgb, var(--cta-c, #ff4b4b) 18%, white);
        transform: scale(0.97);
    }

    .mob-sidebar-cta-icon {
        font-size: 22px;
        line-height: 1;
    }

    .mob-sidebar-cta-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--cta-c, #ff4b4b);
        text-align: center;
        line-height: 1.2;
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

    .custom-dropdown .dropdown-menu {
        display: none;
        position: absolute;
        top: 35px;
        left: 0;
        background: white;
        border: 1px solid #e0e0e0;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        border-radius: 10px;
        width: 200px;
        z-index: 999;
        padding: 10px;
        animation: fadeIn 0.2s ease-in-out;
    }

    .custom-dropdown .dropdown-menu.active {
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
        max-height: 50vh;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .mega-menu.active {
        display: flex !important;
        flex-direction: column !important;
        gap: 0 !important;
        justify-content: flex-start !important;
    }


    .mega-menu.active > .mega-cta-strip-wrapper {
        width: 100%;
        flex-shrink: 0;
        margin-bottom: 10px;
    }

    .mega-menu.active > .mega-menu-dynamic-grid,
    .mega-menu.active > .mega-column {
        width: 100%;
        flex: 1;
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
        margin: 6px 0;
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

    /* ===== Mega Menu CTA Strip ===== */
    .mega-cta-strip {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 12px;
        background: var(--cta-bg, #fff5f5);
        border-radius: 6px;
        border-left: 3px solid var(--cta-color, #ff4b4b);
        box-sizing: border-box;
    }

    .mcta-btn {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 50px;
        color: #fff !important;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none !important;
        white-space: nowrap;
        transition: opacity 0.15s;
    }

    .mcta-btn:hover { opacity: 0.85; }

    .no-cat-msg {
        padding: 12px;
        color: #999;
        text-align: center;
        font-size: 13px;
        width: 100%;
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
    .scroll-nav-row {
        display: flex;
        align-items: stretch;
        width: 100%;
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

    /* Arrow buttons — flex siblings, no positioning tricks needed */
    .nav-scroll-btn {
        display: none;
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

    /* Mega menu: mobile-responsive (Unified Block) */
    @media (max-width: 768px) {
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
        .mega-menu-dynamic-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 8px !important;
            width: 100% !important;
        }
        .mega-column {
            width: 100% !important;
            margin: 0 !important;
            min-width: 0 !important;
            flex: none !important;
        }
        .mega-column a {
            font-size: 13px !important;
            padding: 8px 10px !important;
            margin: 4px 0 !important;
            white-space: normal !important;
            text-overflow: clip !important;
            overflow: visible !important;
            display: block !important;
            border-radius: 4px !important;
            background: #fcfcfc !important;
            border: 1px solid #f0f0f0 !important;
            color: #333 !important;
            text-align: center !important;
        }
        .mega-column a:active, .mega-column a:hover {
            background: #ffebe9 !important;
            color: #ff4b4b !important;
            border-color: #ffd1cd !important;
        }
    }

    .custom-dropdown .dropdown-toggle {
        cursor: pointer;
    }

    /* ===== Custom Autocomplete Suggestions Dropdown ===== */
    .search-suggestions-dropdown {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        width: 100%;
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-radius: 16px;
        z-index: 10000;
        max-height: 400px;
        overflow-y: auto;
        overflow-x: hidden;
        margin-top: 8px;
        padding: 8px 0;
        text-align: left;
    }

    .search-suggestions-dropdown::-webkit-scrollbar {
        width: 6px;
    }

    .search-suggestions-dropdown::-webkit-scrollbar-track {
        background: transparent;
    }

    .search-suggestions-dropdown::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .search-suggestion-section-label {
        font-size: 10.5px;
        font-weight: 600;
        color: #94a3b8;
        padding: 10px 20px 6px 20px;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 4px;
    }

    .search-suggestion-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        cursor: pointer;
        transition: background 0.2s ease;
        border-bottom: 1px solid #f8fafc;
    }

    .search-suggestion-item:last-child {
        border-bottom: none;
    }

    .search-suggestion-item:hover {
        background-color: #f8fafc;
    }

    .search-suggestion-icon-badge {
        width: 36px;
        height: 36px;
        background-color: #f1f5f9;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 14px;
        flex-shrink: 0;
        color: #64748b;
        font-size: 14px;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .search-suggestion-item:hover .search-suggestion-icon-badge {
        background-color: #ffebe9;
        color: #ff4b4b;
    }

    .search-suggestion-content {
        display: flex;
        flex-direction: column;
        line-height: 1.3;
        overflow: hidden;
        flex: 1;
    }

    .search-suggestion-title {
        font-size: 14.5px;
        font-weight: 600;
        color: #1e293b;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        transition: color 0.2s ease;
    }

    .search-suggestion-item:hover .search-suggestion-title {
        color: #ff4b4b;
    }

    .search-suggestion-subtitle {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 3px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    /* ============================================================
       PAGE-SPECIFIC CONTENT SHIMMER
       Header & Footer load INSTANTLY — shimmer covers content area only
       ============================================================ */

    /* Navigation progress bar — always on top, z-index highest */
    #nav-progress-bar {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #ff4b4b 0%, #ff8c00 50%, #ff4b4b 100%);
        background-size: 200% 100%;
        z-index: 1000000;
        animation: nprogress-slide 1.5s ease-in-out infinite;
    }
    @keyframes nprogress-slide {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Shimmer animation keyframes */
    @keyframes shimmer-wave {
        0%   { background-position: -800px 0; }
        100% { background-position:  800px 0; }
    }
    .shimmer {
        background: linear-gradient(90deg, #ebebeb 25%, #e0e0e0 37%, #ebebeb 63%);
        background-size: 1600px 100%;
        animation: shimmer-wave 1.4s ease-in-out infinite;
        border-radius: 6px;
    }



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
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100% !important;
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            line-height: 1 !important;
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

    /* Inline "Create articles" button styling */
    .btn-create-article-inline {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 6px 16px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #ff4d5a !important;
        background-color: #ffffff !important;
        border: 1px solid rgba(255, 77, 90, 0.4) !important;
        border-radius: 6px !important;
        text-decoration: none !important;
        transition: all 0.2s ease-in-out !important;
        height: auto !important;
        width: auto !important;
        box-shadow: none !important;
        margin-left: 12px !important;
    }
    .btn-create-article-inline:hover {
        color: #ffffff !important;
        background-color: #ff4d5a !important;
        border-color: #ff4d5a !important;
        text-decoration: none !important;
    }

    /* Metadata row styling */
    .metadata-row {
        font-size: 13px !important;
        color: #718096 !important;
        margin-top: 6px !important;
        margin-bottom: 24px !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        font-weight: 500 !important;
    }
    .metadata-row .meta-sep {
        color: #cbd5e0 !important;
        margin: 0 4px !important;
    }

    /* Clean Breadcrumb Styling override */
    .breadcrumb {
        background-color: transparent !important;
        background: transparent !important;
        padding: 0 !important;
        margin-top: 8px !important;
        margin-bottom: 12px !important;
        font-size: 12px !important;
        font-weight: 500 !important;
    }
    .breadcrumb a {
        color: #718096 !important;
        text-decoration: none !important;
    }
    .breadcrumb a:hover {
        color: #ff4d5a !important;
    }
    .breadcrumb-item + .breadcrumb-item::before {
        content: ">" !important;
        color: #a0aec0 !important;
        padding-right: 8px !important;
        padding-left: 8px !important;
    }
    .breadcrumb-item.active {
        color: #1e293b !important;
    }

    /* Custom Select2 styling for filter dropdowns to match custom-select-premium */
    .select2-container {
        width: 100% !important;
        display: block !important;
    }
    
    .select2-container--default .select2-selection--single {
        background-color: #fafbfe !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 12px !important;
        height: 44px !important;
        display: flex !important;
        align-items: center !important;
        box-shadow: none !important;
        transition: all 0.2s ease-in-out !important;
        width: 100% !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #ff4d5a !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(255, 77, 90, 0.15) !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1e293b !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        padding-left: 16px !important;
        padding-right: 36px !important;
        line-height: 42px !important;
        width: 100% !important;
        text-align: left !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px !important;
        right: 14px !important;
        width: 20px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #475569 transparent transparent transparent !important;
        border-width: 5px 4px 0 4px !important;
        margin-left: 0 !important;
        margin-top: 0 !important;
        position: relative !important;
        top: auto !important;
        left: auto !important;
    }
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #475569 transparent !important;
        border-width: 0 4px 5px 4px !important;
    }
    
    /* DROPDOWN CONTAINER */
    .select2-dropdown {
        border: 1px solid #cbd5e1 !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden !important;
        z-index: 99999 !important;
        background-color: #ffffff !important;
    }
    
    /* SEARCH FIELD INSIDE DROPDOWN */
    .select2-search--dropdown {
        padding: 8px 12px !important;
        background-color: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        outline: none !important;
        font-size: 13px !important;
        width: 100% !important;
        background-color: #ffffff !important;
        box-sizing: border-box !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: #ff4d5a !important;
    }
    
    /* OPTIONS/RESULTS LIST */
    .select2-results__options {
        max-height: 250px !important;
        padding: 4px !important;
    }
    .select2-container--default .select2-results__option {
        padding: 8px 12px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        color: #334155 !important;
        border-radius: 6px !important;
        margin-bottom: 2px !important;
        transition: background-color 0.15s ease !important;
        text-align: left !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #ff4d5a !important;
        color: #ffffff !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
        font-weight: 600 !important;
    }

    /* CLEAR SELECTION BUTTON (the small x icon) */
    .select2-container--default .select2-selection--single .select2-selection__clear {
        margin-right: 8px !important;
        color: #94a3b8 !important;
        font-size: 16px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        float: none !important;
        order: -1 !important; /* show before the text for easy clearing */
    }
    .select2-container--default .select2-selection--single .select2-selection__clear:hover {
        color: #ff4d5a !important;
    }
</style>
<!-- ===== Navigation Progress Bar ===== -->
<div id="nav-progress-bar"></div>
<script>
// ============================================================
// INSTANT NAVIGATION + PAGE-SPECIFIC CONTENT SHIMMER
// Header & Footer always visible — shimmer only below header
// ============================================================
(function() {

    // Set CSS variable for header height (used to position content shimmer)
    function updateHeaderHeight() {
        var hdr = document.querySelector('header');
        if (hdr) {
            var rect = hdr.getBoundingClientRect();
            var topPos = Math.max(0, rect.bottom);
            document.documentElement.style.setProperty('--header-h', topPos + 'px');
        }
    }
    
    // URL → shimmer template mapping
    function getShimmerTplId(url) {
        var path = '';
        try { path = new URL(url).pathname.toLowerCase(); } catch(e) { path = String(url).toLowerCase(); }

        // Single article / blog view (deep path like /blog-view or /slug/slug)
        if (path.match(/blog[_-]view|article|blog\/[^/]+\/[^/]+/)) return 'shim-tpl-article';

        // Products / deals / marketplace
        if (path.match(/\/product|deal|marketplace|shop/)) return 'shim-tpl-product';

        // 3-column listings: blog, event, community, group, qna
        if (path.match(/\/blog|event|community|group|qna|timeline|feed|influencer/)) return 'shim-tpl-3col';

        // Default: home / business listing
        return 'shim-tpl-home';
    }

    // Close all open header menus/dropdowns/sidebar instantly
    window.closeAllHeaderMenus = function() {
        // 1. Hide Mega Menus
        var menus = document.querySelectorAll(".mega-menu");
        var links = document.querySelectorAll(".scroll-menu a[data-menu]");
        if (menus) menus.forEach((menu) => menu.classList.remove("active"));
        if (links) links.forEach((link) => link.classList.remove("active"));

        // 2. Hide City Dropdowns
        var cityD = document.getElementById("cityMenuDesktop");
        var cityM = document.getElementById("cityMenuMobile");
        if (cityD) cityD.classList.remove("active");
        if (cityM) cityM.classList.remove("active");

        // 3. Hide Business Dropdowns
        var busD = document.getElementById("businessMenuDesktop");
        var busM = document.getElementById("businessMenuMobile");
        if (busD) busD.classList.remove("active");
        if (busM) busM.style.display = "none";

        // 4. Hide Sidebar
        var sidebar = document.getElementById("sidebar");
        var overlay = document.getElementById("sidebarOverlay");
        if (sidebar) sidebar.classList.remove("active");
        if (overlay) overlay.classList.remove("active");
        document.body.style.overflow = '';

        // 5. Hide Profile Dropdowns
        var dropdowns = document.querySelectorAll('.dropdown, .dropdown-menu');
        dropdowns.forEach(function(el) {
            el.classList.remove('show');
        });
    };

    // Direct navigation fallback (closes menus first)
    window.showShimmerAndNavigate = function(url) {
        window.closeAllHeaderMenus();
        window.location.href = url;
    };

    // Close menus instantly on link click inside menus to keep UI responsive
    document.addEventListener('click', function(e) {
        var anchor = e.target.closest('.mega-menu a, .menu-dropdown a, .dropdown-menu a, #sidebar a');
        if (anchor) {
            var href = anchor.getAttribute('href');
            if (href && !href.startsWith('javascript:') && !href.startsWith('#')) {
                window.closeAllHeaderMenus();
            }
        }
    });
})();
</script>
<header>
    <!-- ======= Desktop Header (Dynamic) ======= -->
    <div class="desktop-header">
        <div class="d-flex align-items-center gap-3">
            <!-- Logo -->
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="https://cdn.cityhangaround.com/file/cityhangaround/uploads/wYMNRNks5mOFSD2V1eOkZbjeH0m3dhFpyT79tsHW.png"
                        alt="Logo" width="160" height="35" />
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
                            <div class="dropdown-toggle" id="cityToggleDesktop">
                                <span id="selectedCityDesktop">{{ $initialCityName }}</span>
                            </div>

                            <!-- Dropdown Menu -->
                            <div class="dropdown-menu" id="cityMenuDesktop">
                                <input type="text" id="citySearchDesktop" placeholder="Search city..."
                                    class="dropdown-search" onkeyup="filterCityListDesktop(this)" />
                                <div class="dropdown-options" id="cityListDesktop">
                                    {{-- @foreach ($all_cities as $city)
                                    <div class="option"
                                        onclick="selectCityDesktop('{{ $city->city_name }}', '{{ $city->city_slug }}')">
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
                        <form id="search-form" style="flex: 1; width: 100%; position: relative; display: flex; align-items: center;">
                            <input type="hidden" id="city_header" name="cityid" value="{{ $urlCityId }}">
                            <input type="text" id="search-box" name="search" placeholder="Search for businesses..."
                                class="search-input" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--</div>-->
        <!-- ======= End Desktop Header ======= -->


        <!-- City Dropdown logic consolidated in global-scripts.blade.php -->

        <!-- ======= End Desktop Header ======= -->




        <div class="desktop-right">
            <div class="dropdown-wrapper">
                <div class="dropdown-toggle" id="businessToggleDesktop">
                    For Business
                </div>
                <div class="menu-dropdown" id="businessMenuDesktop">
                    <a href="/admin/page/create">Add Business </a>
                    <a href="/products/create">List Product/Service</a>
                    @guest
                        <a href="javascript:void(0)" onclick="openLoginModal(event, 'login')">Login / Signup</a>
                    @endguest
                    <!-- <a href="/user/leads/view" onclick="enquiryForm('backend.header')"  class="story-entry m-0"> -->

                    <a href="/user/subscriptions/view">Advertisement</a>
                    <a href="/pages/custom/contact-us">Contact</a>
                    <a href="{{ route('public.subscriptions') }}">Promotion</a>
                </div>
            </div>
            <button class="advertise"
                onclick="window.location.href='/pages/custom/advertise';">Advertise</button>


            @if (auth()->user())
                <div class="dropdown profile-control">
                    <button class="btn dropdown-toggle p-0 border-0 bg-transparent" type="button" id="profileToggle"
                        data-bs-toggle="dropdown" aria-expanded="false" aria-label="Toggle profile menu">
                        @if (auth()->user())
                            <img src="{{ get_user_image(auth()->user()->photo, 'optimized') }}" class="rounded-circle"
                                width="40" height="40" alt="User Profile Image">
                        @else
                            <img src="{{ get_user_image('', 'optimized') }}" class="rounded-circle" width="40" height="40"
                                alt="User Profile Image">
                        @endif
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end" id="profileDropdown" aria-labelledby="profileToggle">

                        <li><a class="dropdown-item" href="{{ route('profile') }}">{{ get_phrase('My Profile') }}</a></li>

                        @if (auth()->user()->user_role == 'admin')
                            <li><a class="dropdown-item"
                                    href="{{ route('admin.dashboard') }}">{{ get_phrase('Go to admin panel') }}</a>
                            </li>
                        @endif

                        <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">{{ get_phrase('Dashboard') }}</a>
                        </li>

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
                <button class="login-btn" onclick="openLoginModal(event, 'login')">Login</button>
            @endif




            <!--<button class="login-btn">Login</button>-->
        </div>
    </div>



    <!-- ======= Mobile Header ======= -->
    <div class="top-header">
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="https://cdn.cityhangaround.com/file/cityhangaround/uploads/wYMNRNks5mOFSD2V1eOkZbjeH0m3dhFpyT79tsHW.png"
                    alt="Logo" width="160" height="35" style="height: auto !important;" />
            </a>
        </div>
        <div class="top-header-right">
            <button class="advertise"
                onclick="window.location.href='/pages/custom/advertise';">Advertise</button>

            @if (auth()->user())
                <div class="dropdown profile-control">
                    <button class="btn dropdown-toggle p-0 border-0 bg-transparent" type="button" id="profileToggleMobile"
                        data-bs-toggle="dropdown" aria-expanded="false" aria-label="Toggle profile menu">
                        @if (auth()->user())
                            <img src="{{ get_user_image(auth()->user()->photo, 'optimized') }}" class="rounded-circle" width="40"
                                height="40" alt="User Profile Image">
                        @else
                            <img src="{{ get_user_image('', 'optimized') }}" class="rounded-circle" width="40" height="40" alt="User Profile Image">
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" id="profileDropdownMobile"
                        aria-labelledby="profileToggleMobile">

                        <li><a class="dropdown-item" href="{{ route('profile') }}">{{ get_phrase('My Profile') }}</a></li>

                        @if (auth()->user()->user_role == 'admin')
                            <li><a class="dropdown-item"
                                    href="{{ route('admin.dashboard') }}">{{ get_phrase('Go to admin panel') }}</a>
                            </li>
                        @endif

                        <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">{{ get_phrase('Dashboard') }}</a></li>

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
                <button class="login-btn" onclick="openLoginModal(event, 'login')">Login</button>
            @endif
            <button class="navbar-toggler" id="menuBtn" aria-label="Toggle navigation menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!--<div class="mobile-search-row">-->
    <!--  <div class="main-search-container">-->
    <!--    <div class="search-inner">-->
    <!--      <div class="city-dropdown-wrapper">-->
    <!--        <i class="fas fa-map-marker-alt location-icon"></i>-->
    <!--        <div class="custom-dropdown">-->
    <!--          <div class="dropdown-toggle" id="cityToggleMobile">-->
    <!--            <span id="selectedCityMobile">Select City</span>-->
    <!--          </div>-->
    <!--          <div class="dropdown-menu" id="cityMenuMobile">-->
    <!--            <input-->
    <!--              type="text"-->
    <!--              id="citySearchMobile"-->
    <!--              placeholder="Search city..."-->
    <!--              class="dropdown-search"-->
    <!--            />-->
    <!--            <div class="dropdown-options">-->
    <!--              <div class="option">Ahmedabad</div>-->
    <!--              <div class="option">Surat</div>-->
    <!--              <div class="option">Vadodara</div>-->
    <!--              <div class="option">Rajkot</div>-->
    <!--              <div class="option">Bhavnagar</div>-->
    <!--              <div class="option">Jamnagar</div>-->
    <!--            </div>-->
    <!--          </div>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--      <div class="divider"></div>-->
    <!--      <div class="search-box">-->
    <!--        <i class="fas fa-search search-icon"></i>-->
    <!--        <input-->
    <!--          type="text"-->
    <!--          placeholder="Search for businesses..."-->
    <!--          class="search-input"-->
    <!--        />-->
    <!--      </div>-->
    <!--    </div>-->
    <!--  </div>-->
    <!--</div>-->
    <div class="mobile-search-row">
        <div class="main-search-container">
            <div class="search-inner">
                <div class="city-dropdown-wrapper">
                    <i class="fas fa-map-marker-alt location-icon" style="flex-shrink: 0;"></i>
                    <div class="custom-dropdown">
                        {{-- @php
                        $currentCitySlug = request()->segment(1);
                        $currentCity = collect($all_cities)->firstWhere('city_slug', $currentCitySlug);
                        $cityName = $currentCity ? $currentCity->city_name : 'Select City';
                        @endphp --}}
                        <div class="dropdown-toggle" id="cityToggleMobile">
                            <span id="selectedCityMobile">{{ $initialCityName }}</span>
                        </div>
                        <div class="dropdown-menu" id="cityMenuMobile">
                            <input type="text" id="citySearchMobile" placeholder="Search city..."
                                class="dropdown-search" onkeyup="filterCityListDesktop(this)" />
                            <div class="dropdown-options" id="cityListMobile">
                                {{-- @foreach ($all_cities as $city)
                                <div class="option"
                                    onclick="selectCityDesktop('{{ $city->city_name }}', '{{ $city->city_slug }}')">
                                    {{ $city->city_name }}
                                </div>
                                @endforeach --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="search-box">
                    <i class="fas fa-search search-icon" style="flex-shrink: 0;"></i>
                    <form id="mobile-search-form" style="flex: 1; min-width: 0; position: relative; display: flex; align-items: center;">
                        <input type="hidden" id="mobile_city_slug" name="cityid" value="{{ $urlCityId }}">
                        <input type="text" id="mobile-search-box" placeholder="Search for businesses..." class="search-input" style="width: 100%; min-width: 0; border: none; outline: none; background: transparent;" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="scroll-wrap">
        <div class="scroll-nav-row">
            <button class="nav-scroll-btn nav-btn-left"  id="navScrollLeftH"  aria-label="Scroll left">&#8249;</button>
            <div class="scroll-menu" id="mainScrollMenuH">
                <a data-menu="cityGuide">City Guide &#9662;</a>
                <a data-menu="Sell">Buy/Sell &#9662;</a>
                <a data-menu="marketplace">Marketplace &#9662;</a>
                <a data-menu="community">Community &#9662;</a>
                <a data-menu="event">Event &#9662;</a>
                <a data-menu="blog">Blog &#9662;</a>
            </div>
            <button class="nav-scroll-btn nav-btn-right" id="navScrollRightH" aria-label="Scroll right">&#8250;</button>
        </div>

        <div class="mega-menus" id="megaContainer">
            <!-- City Guide -->
            <div class="mega-menu" id="cityGuide">
                <div id="header-city-guide-grid" class="mega-menu-dynamic-grid"
                    style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; width: 100%;"></div>
            </div>

            <!-- Marketplace -->
            <div class="mega-menu" id="marketplace">
                <div id="header-marketplace-grid" class="mega-menu-dynamic-grid"
                    style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; width: 100%;"></div>
            </div>

            <!-- buy/sell -->
            <div class="mega-menu" id="Sell">
                <div class="mega-column">
                    <a href="javascript:void(0)" onclick="openEnquiryForm()">Post Requirement</a>
                    <a href="/admin/page/create">Add Business</a>
                    <a href="/products/create">Add Deals</a>
                </div>
            </div>

            <!-- Community -->
            <div class="mega-menu" id="community">
                <div id="header-community-grid" class="mega-menu-dynamic-grid"
                    style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; width: 100%;"></div>
            </div>

            <!-- Event -->
            <div class="mega-menu" id="event">
                <div id="header-event-grid" class="mega-menu-dynamic-grid"
                    style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; width: 100%;"></div>
            </div>

            <!-- Blog -->
            <div class="mega-menu" id="blog">
                <div id="header-blog-grid" class="mega-menu-dynamic-grid"
                    style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; width: 100%;"></div>
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
            {{-- Quick Action CTA tiles --}}
            <div class="mob-sidebar-cta-grid">
                <a href="/admin/page/create" class="mob-sidebar-cta-tile" style="--cta-c:#ff4b4b">
                    <span class="mob-sidebar-cta-icon">🏪</span>
                    <span class="mob-sidebar-cta-label">Add Business</span>
                </a>
                <a href="/products/create" class="mob-sidebar-cta-tile" style="--cta-c:#5b2ff9">
                    <span class="mob-sidebar-cta-icon">🛒</span>
                    <span class="mob-sidebar-cta-label">Add Deals</span>
                </a>
                <a href="/events/create" class="mob-sidebar-cta-tile" style="--cta-c:#0ea5e9">
                    <span class="mob-sidebar-cta-icon">🎉</span>
                    <span class="mob-sidebar-cta-label">Add Event</span>
                </a>
                <a href="/blog/create" class="mob-sidebar-cta-tile" style="--cta-c:#10b981">
                    <span class="mob-sidebar-cta-icon">✍️</span>
                    <span class="mob-sidebar-cta-label">Add Blog</span>
                </a>
                <a href="/groups" class="mob-sidebar-cta-tile" style="--cta-c:#f59e0b">
                    <span class="mob-sidebar-cta-icon">💬</span>
                    <span class="mob-sidebar-cta-label">Start Discussion</span>
                </a>
            </div>
            <hr style="margin:10px 0;border-color:#f0f0f0;">
            @guest
                <a href="javascript:void(0)" onclick="openLoginModal(event, 'login')"><i class="fas fa-sign-in-alt fa-fw me-2 text-muted"></i>Login / Signup</a>
            @endguest
            <a href="/user/subscriptions/view"><i class="fas fa-ad fa-fw me-2 text-muted"></i>Advertisement</a>
            <a href="/pages/custom/contact-us"><i class="fas fa-phone fa-fw me-2 text-muted"></i>Contact</a>
            <a href="{{ route('public.subscriptions') }}"><i class="fas fa-rocket fa-fw me-2 text-muted"></i>Promotion</a>
        </div>
    </div>
</div>

<!-- ===== Sidebar Overlay (Mobile) ===== -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ===== Mobile Bottom Navigation Bar ===== -->
@php
    $currentRoute = Route::currentRouteName() ?? '';
@endphp
<nav class="mobile-bottom-nav" id="mobileBottomNav" role="navigation" aria-label="Mobile bottom navigation">

    {{-- Home --}}
    <a href="{{ url('/') }}"
       class="mob-nav-item {{ in_array($currentRoute, ['home', 'index', 'frontend.home']) ? 'active' : '' }}"
       id="mob-nav-home"
       aria-label="Home">
        <i class="{{ in_array($currentRoute, ['home', 'index', 'frontend.home']) ? 'fas' : 'far' }} fa-compass"></i>
        <span class="mob-nav-label">Explore</span>
    </a>

    {{-- City Guide / Search --}}
    <a href="{{ $currentCity ? url('/' . $currentCity->city_slug) : url('/') }}"
       class="mob-nav-item {{ in_array($currentRoute, ['city.home', 'city.guide']) ? 'active' : '' }}"
       id="mob-nav-city"
       aria-label="City Guide">
        <i class="fas fa-map-marker-alt"></i>
        <span class="mob-nav-label">City</span>
    </a>

    {{-- Center: Post / Create --}}
    @auth
    <a href="{{ route('timeline') }}"
       class="mob-nav-item mob-nav-post"
       id="mob-nav-post"
       aria-label="Create post">
        <div class="mob-nav-post-btn">
            <i class="fas fa-plus"></i>
        </div>
        <span class="mob-nav-label" style="margin-top:4px;">Post</span>
    </a>
    @else
    <a href="{{ route('login') }}"
       class="mob-nav-item mob-nav-post"
       id="mob-nav-post"
       aria-label="Login to post">
        <div class="mob-nav-post-btn">
            <i class="fas fa-plus"></i>
        </div>
        <span class="mob-nav-label" style="margin-top:4px;">Post</span>
    </a>
    @endauth

    {{-- Profile / Account --}}
    @auth
    <a href="{{ route('profile') }}"
       class="mob-nav-item {{ str_starts_with($currentRoute, 'profile') ? 'active' : '' }}"
       id="mob-nav-profile"
       aria-label="Profile">
        <i class="{{ str_starts_with($currentRoute, 'profile') ? 'fas' : 'far' }} fa-user-circle"></i>
        <span class="mob-nav-label">Profile</span>
    </a>
    @else
    <a href="javascript:void(0)"
       onclick="openLoginModal(event, 'login')"
       class="mob-nav-item"
       id="mob-nav-profile"
       aria-label="Login">
        <i class="far fa-user-circle"></i>
        <span class="mob-nav-label">Login</span>
    </a>
    @endauth

</nav>

<script>
// ===== MOBILE: Sidebar Overlay click-to-close + Bottom nav tap bounce =====
(function() {
    // Overlay tap closes sidebar
    var overlay = document.getElementById('sidebarOverlay');
    var sidebar = document.getElementById('sidebar');
    if (overlay && sidebar) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            var bm = document.getElementById('businessMenuMobile');
            if (bm) bm.style.display = 'none';
        });
    }

    // Bottom nav tap bounce animation
    document.querySelectorAll('.mob-nav-item').forEach(function(el) {
        el.addEventListener('touchstart', function() {
            el.style.transition = 'transform 0.1s ease';
            el.style.transform = 'scale(0.91)';
        }, { passive: true });
        el.addEventListener('touchend', function() {
            setTimeout(function() {
                el.style.transform = '';
            }, 120);
        }, { passive: true });
    });
})();
</script>

<!-- Consolidated navigation scripts handled in global-scripts.blade.php -->
@include('frontend.partials.global-scripts')
@include('frontend.partials.login_modal')

