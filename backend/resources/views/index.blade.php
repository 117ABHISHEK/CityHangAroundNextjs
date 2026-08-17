
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Core Meta -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CityHangarounds – Discover Local Businesses, Deals, Events & City Guide</title>
    <meta name="description"
        content="Explore your city’s top businesses, best deals, events, jobs, blogs, and more on CityHangaround. Find local shops, services, influencers, and trending updates instantly.">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Preload Hero Poster Image for faster LCP -->
    <link rel="preload" as="image" href="{{ asset('assets/main/images/hero_poster.webp') }}" fetchpriority="high" />

    <!-- Bootstrap (deferred, non-render-blocking) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" media="print" onload="this.media='all'" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet" media="print" onload="this.media='all'" />

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'" />

    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" media="print" onload="this.media='all'" />

    <!-- Header CSS -->
    <link rel="stylesheet" href="/assets/frontend/css/header_new.css?v=2.0.5" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/assets/frontend/css/custom_header.css?v=2.0.5" media="print" onload="this.media='all'">

    <!-- Landing CSS -->
    <link rel="stylesheet" href="/assets/frontend/css/landingpage-new.css?v=2.0.5" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/assets/frontend/css/landingpage-new-hero.css?v=2.0.5" media="print" onload="this.media='all'">

    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <link rel="stylesheet" href="/assets/frontend/css/header_new.css?v=2.0.5">
        <link rel="stylesheet" href="/assets/frontend/css/custom_header.css?v=2.0.5">
        <link rel="stylesheet" href="/assets/frontend/css/landingpage-new.css?v=2.0.5">
        <link rel="stylesheet" href="/assets/frontend/css/landingpage-new-hero.css?v=2.0.5">
    </noscript>

    <!--  jQuery & Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>

    <style>
        /* Critical resets to prevent CLS from deferred Bootstrap */
        *,*::before,*::after{box-sizing:border-box}
        html{-webkit-text-size-adjust:100%}
        body{margin:0;font-family:'Poppins',sans-serif;background:#fff;line-height:1.5}
        img,video{max-width:100%;height:auto}
        .container{width:100%;padding:0 12px;margin:0 auto}
        @media(min-width:576px){.container{max-width:540px}}
        @media(min-width:768px){.container{max-width:720px}}
        @media(min-width:992px){.container{max-width:960px}}
        @media(min-width:1200px){.container{max-width:1140px}}
        .row{display:flex;flex-wrap:wrap;margin:0 -12px}
        [class*="col-"]{padding:0 12px}

        /* Disable video on mobile — saves 3MB+ on slow 4G */
        @media(max-width:767px){
            #heroBgVideo{display:none!important}
            .bg-video{display:none!important}
        }

        /* === CRITICAL: Hero section CSS (inlined to eliminate render-blocking) === */
        .hero-container { position: relative; height: 100vh; width: 100%; overflow: hidden; background: #000; } .bg-video { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; } .overlay-dark { position: absolute; inset: 0; background: linear-gradient( to bottom, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.7) ); z-index: 2; } .particles-container { position: absolute; inset: 0; overflow: hidden; pointer-events: none; z-index: 3; } .mouse-light { position: absolute; width: 384px; height: 384px; border-radius: 50%; background: radial-gradient( circle, rgba(6, 182, 212, 0.2), transparent, transparent ); filter: blur(60px); pointer-events: none; z-index: 4; transition: all 0.3s ease-out; } .corner-accent { position: absolute; width: 0; height: 0; animation: border-expand 2s ease-out forwards; } .corner-top-left { top: 0; left: 0; border-top: 4px solid rgba(6, 182, 212, 0.3); border-left: 4px solid rgba(6, 182, 212, 0.3); z-index: 5; } .corner-bottom-right { bottom: 0; right: 0; border-bottom: 4px solid rgba(255, 107, 53, 0.3); border-right: 4px solid rgba(255, 107, 53, 0.3); z-index: 5; animation-delay: 0.5s; } .gradient-orb { position: absolute; border-radius: 50%; filter: blur(60px); z-index: 3; } .orb-cyan { bottom: 80px; left: 80px; width: 256px; height: 256px; background: radial-gradient(circle, rgba(6, 182, 212, 0.2), transparent); animation: float-slow 8s ease-in-out infinite; } .orb-orange { top: 80px; right: 80px; width: 384px; height: 384px; background: radial-gradient(circle, rgba(255, 107, 53, 0.2), transparent); animation: float-slow 8s ease-in-out infinite; animation-delay: 1s; } .content-wrapper { position: relative; z-index: 10; height: 100%; display: flex; align-items: center; justify-content: center; padding: 1rem; } .hero-content { text-align: center; position: relative; transition: all 0.1s ease-out; } .floating-icon { position: absolute; opacity: 0.5; animation: float-diagonal 6s ease-in-out infinite; } .icon-left { left: -80px; top: 80px; } .icon-right { right: -80px; top: 160px; animation-delay: 1s; } .floating-icon i { font-size: 48px; animation: pulse-glow 3s ease-in-out infinite; } .icon-left i { color: #06b6d4; } .icon-right i { color: #ff6b35; } .hero-badge { position: relative; display: inline-flex; align-items: center; gap: 12px; background: linear-gradient( to right, rgba(31, 41, 55, 0.4), rgba(55, 65, 81, 0.4), rgba(31, 41, 55, 0.4) ); backdrop-filter: blur(20px); border: 2px solid rgba(209, 213, 219, 0.3); border-radius: 50px; padding: 12px 24px; margin-bottom: 32px; overflow: hidden; opacity: 0; transform: translateY(-40px); animation: fade-in-down 1s ease-out forwards; transition: all 0.3s ease; margin-left: 15px; } .hero-badge:hover { border-color: rgba(255, 107, 53, 0.7); transform: translateY(0) scale(1.1); box-shadow: 0 25px 50px -12px rgba(255, 107, 53, 0.3); } .badge-shimmer { position: absolute; inset: 0; background: linear-gradient( to right, transparent, rgba(255, 255, 255, 0.1), transparent ); animation: slide-across 3s linear infinite; } .badge-icon { font-size: 24px; animation: bounce-slow 2s ease-in-out infinite; } .hero-badge:hover .badge-icon { animation: spin-slow 3s linear infinite; } .badge-text { color: #fff; font-weight: 600; font-size: 18px; letter-spacing: 0.025em; position: relative; z-index: 1; } .badge-sparkle { position: absolute; top: -4px; right: -4px; color: #06b6d4; font-size: 20px; animation: pulse 2s ease-in-out infinite; } .hero-title { margin-bottom: 24px; opacity: 0; transform: translateY(40px); animation: fade-in-up 1s ease-out 0.2s forwards; } .title-line { display: block; position: relative; font-size: 3.7rem; font-weight: 800; line-height: 1.2; letter-spacing: -0.025em; } .title-primary { color: #ef4444; margin-bottom: 8px; } .title-secondary { color: #fff; } .title-inner { position: relative; display: inline-block; } .wave-letter { display: inline-block; animation: wave-text 2s ease-in-out infinite; transition: color 0.3s ease; } .wave-letter:hover { color: #ff6b35; } .title-secondary .wave-letter:hover { color: #06b6d4; } .title-glow { position: absolute; inset: -4px; filter: blur(20px); z-index: -1; animation: pulse-glow 3s ease-in-out infinite; } .glow-red { background: linear-gradient( to right, rgba(239, 68, 68, 0.2), rgba(249, 115, 22, 0.2) ); } .glow-cyan { background: linear-gradient( to right, rgba(255, 255, 255, 0.2), rgba(6, 182, 212, 0.2) ); } .hero-subtitle { position: relative; font-size: 1.5rem; font-weight: 400; color: #e5e7eb; margin-bottom: 40px; opacity: 0; transform: translateY(40px); animation: fade-in-up 1s ease-out 0.4s forwards; } .subtitle-text { position: relative; z-index: 1; display: inline-block; animation: text-glow 3s ease-in-out infinite; } .subtitle-shimmer { position: absolute; inset: 0; background: linear-gradient( to right, transparent, rgba(6, 182, 212, 0.1), transparent ); animation: slide-across-slow 8s linear infinite; } .hero-buttons { display: flex; flex-wrap: wrap; justify-content: center; gap: 16px; opacity: 0; transform: translateY(40px); animation: fade-in-up 1s ease-out 0.6s forwards; } .btn-hero { position: relative; display: inline-flex; align-items: center; justify-content: center; gap: 12px; padding: 16px 32px; border: none; border-radius: 50px; font-weight: 600; font-size: 18px; color: #fff; overflow: hidden; cursor: pointer; transition: all 0.3s ease; animation: button-pulse 2s ease-in-out infinite; } .btn-primary-hero { background: linear-gradient(to right, #06b6d4, #0891b2, #2563eb); } .btn-secondary-hero { background: linear-gradient(to right, #f97316, #ea580c, #dc2626); animation-delay: 0.3s; } .btn-gradient { position: absolute; inset: 0; opacity: 0; transition: opacity 0.3s ease; background-size: 200% 200%; animation: gradient 3s ease infinite; } .btn-primary-hero .btn-gradient { background: linear-gradient(to right, #22d3ee, #3b82f6); } .btn-secondary-hero .btn-gradient { background: linear-gradient(to right, #fb923c, #ef4444); } .btn-overlay { position: absolute; inset: 0; background: rgba(255, 255, 255, 0.2); transform: scaleX(0); transform-origin: left; transition: transform 0.7s ease; } .btn-icon { position: relative; z-index: 10; font-size: 20px; transition: transform 0.3s ease; } .btn-text { position: relative; z-index: 10; transition: letter-spacing 0.3s ease; } .btn-glow { position: absolute; inset: -2px; border-radius: 50px; filter: blur(8px); opacity: 0.3; transition: opacity 0.3s ease; animation: pulse-glow 3s ease-in-out infinite; } .btn-primary-hero .btn-glow { background: linear-gradient(to right, #06b6d4, #2563eb); } .btn-secondary-hero .btn-glow { background: linear-gradient(to right, #f97316, #dc2626); } .btn-ripple { position: absolute; inset: 0; border-radius: 50px; border: 2px solid; opacity: 0; transform: scale(1); transition: all 0.7s ease; } .btn-primary-hero .btn-ripple { border-color: rgba(34, 211, 238, 0.5); } .btn-secondary-hero .btn-ripple { border-color: rgba(251, 146, 60, 0.5); } .ripple-2 { transition-delay: 0.1s; } .ripple-3 { transition-delay: 0.2s; } .btn-hero:hover { transform: scale(1.1); box-shadow: 0 25px 50px -12px; } .btn-primary-hero:hover { box-shadow: 0 25px 50px -12px rgba(6, 182, 212, 0.6); } .btn-secondary-hero:hover { box-shadow: 0 25px 50px -12px rgba(249, 115, 22, 0.6); } .btn-hero:hover .btn-gradient { opacity: 1; } .btn-hero:hover .btn-overlay { transform: scaleX(1); } .btn-hero:hover .btn-icon { animation: bounce 1s ease-in-out infinite; } .btn-secondary-hero:hover .btn-icon { transform: rotate(12deg); } .btn-hero:hover .btn-text { letter-spacing: 0.05em; } .btn-hero:hover .btn-glow { opacity: 0.7; } .btn-hero:hover .btn-ripple { opacity: 0; transform: scale(1.5); } .btn-hero:active { transform: scale(0.95); } .wave-container { position: absolute; bottom: 0; left: 0; width: 100%; height: 128px; overflow: hidden; z-index: 6; } .wave-svg { position: absolute; bottom: 0; width: 100%; height: 100%; animation: wave-svg 20s linear infinite; } .wave-2 { animation-delay: 0.5s; } .wave-overlay { position: absolute; bottom: 0; left: 0; width: 100%; height: 100%; background: linear-gradient( to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.4), transparent ); } @keyframes float { 0%, 100% { transform: translate(0, 0); opacity: 0; } 10% { opacity: 0.3; } 50% { transform: translate(50px, -100px); opacity: 0.6; } 90% { opacity: 0.3; } } @keyframes pulse-slow { 0%, 100% { opacity: 0.55; } 50% { opacity: 0.65; } } @keyframes border-expand { 0% { opacity: 0; width: 0; height: 0; } 100% { opacity: 1; width: 256px; height: 256px; } } @keyframes float-slow { 0%, 100% { transform: translateY(0) scale(1); } 50% { transform: translateY(-20px) scale(1.1); } } @keyframes fade-in-down { from { opacity: 0; transform: translateY(-40px); } to { opacity: 1; transform: translateY(0); } } @keyframes fade-in-up { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } } @keyframes bounce-slow { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } } @keyframes spin-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } } @keyframes slide-across { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } } @keyframes slide-across-slow { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } } @keyframes wave-text { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } } @keyframes pulse-glow { 0%, 100% { opacity: 0.3; transform: scale(1); } 50% { opacity: 0.7; transform: scale(1.05); } } @keyframes text-glow { 0%, 100% { text-shadow: 0 0 10px rgba(255, 255, 255, 0.3); } 50% { text-shadow: 0 0 20px rgba(255, 255, 255, 0.6); } } @keyframes button-pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.02); } } @keyframes gradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } } @keyframes wave-svg { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } } @keyframes float-diagonal { 0%, 100% { transform: translate(0, 0); } 25% { transform: translate(10px, -10px); } 50% { transform: translate(20px, 0); } 75% { transform: translate(10px, 10px); } } @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } } @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }         @media (max-width: 768px) { .hero-container { height: auto !important; min-height: auto !important; overflow: hidden !important; } .content-wrapper { height: auto !important; padding: 50px 1rem 100px !important; } .title-line { font-size: 2rem; } .hero-subtitle { font-size: 1rem; } .btn-hero { padding: 12px 24px; font-size: 16px; } .floating-icon { display: none; } .corner-accent { max-width: 128px; max-height: 128px; } .trending-city-title, .category-title, .discovery-section h2, .section-title { font-size: 1.8rem !important; line-height: 1.25 !important; margin-bottom: 8px !important; } .category-subtitle, .discovery-section p { font-size: 0.95rem !important; margin-bottom: 15px !important; line-height: 1.45 !important; } .category-subtitle + section, .category-subtitle + section.py-5 { padding-top: 10px !important; padding-bottom: 25px !important; } .trending-section, .discovery-section, .features, .counter-section, .client-section, .faq { overflow: hidden !important; } } @media (max-width: 576px) { .title-line { font-size: 1.5rem; } .badge-text { font-size: 14px; } .hero-buttons { flex-direction: column; } }
    </style>

</head>

<body class="landing-page">
    @include('frontend.header')

    <!-- Floating Particles -->
    <div class="particles-container" id="particles"></div>
    <!-- Hero Container -->
    <div class="hero-container">
        <!-- Background Video -->
        <video autoplay muted loop playsinline class="bg-video" id="heroBgVideo"
            poster="{{ asset('assets/main/images/hero_poster.webp') }}"
            data-src="{{ asset('assets/main/images/backgroundvideo.mp4') }}" preload="none">
            Your browser does not support the video tag.
        </video>

        <!-- Dark Overlay -->
        <div class="overlay-dark"></div>

        <!-- Mouse Follower Light -->
        <div class="mouse-light" id="mouseLight"></div>

        <!-- Content -->
        <div class="content-wrapper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10 col-xl-8">
                        <div class="hero-content" id="heroContent">
                            <!-- Badge -->
                            <div class="hero-badge" id="heroBadge">
                                <span class="badge-shimmer"></span>
                                <span class="badge-icon">🌍</span>
                                <span class="badge-text">Explore Without Limitations</span>
                                <i class="bi bi-stars badge-sparkle"></i>
                            </div>

                            <!-- Title -->
                            <h1 class="hero-title" id="heroTitle">
                                <span class="title-line title-primary">
                                    <span class="title-inner">DISCOVER WHAT YOU ARE</span>
                                    <span class="title-glow glow-red"></span>
                                </span>
                                <span class="title-line title-secondary">
                                    <span class="title-inner">looking for</span>
                                    <span class="title-glow glow-cyan"></span>
                                </span>
                            </h1>

                            <!-- Subtitle -->
                            <p class="hero-subtitle" id="heroSubtitle">
                                <span class="subtitle-text">The City's Best Events, Deals, Places, Stories & Many
                                    more</span>
                            </p>

                            <!-- Buttons -->
                            <div class="hero-buttons" id="heroButtons">
                                <button class="btn-hero btn-primary-hero">
                                    <span class="btn-text">Start Exploring</span>
                                </button>
                                @if (auth()->user())
                                    <a href="{{ route('timeline') }}" class="text-decoration-none">
                                        <button class="btn-hero btn-secondary-hero">
                                            <span class="btn-text">Post an Activity</span>
                                        </button>
                                    </a>
                                @else
                                    <a href="javascript:void(0)" onclick="openLoginModal(event)" class="text-decoration-none">
                                        <button class="btn-hero btn-secondary-hero">
                                            <span class="btn-text">Post an Activity</span>
                                        </button>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Wave -->
        <div class="wave-container">
            <svg class="wave-svg wave-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"
                preserveAspectRatio="none">
                <path fill="rgba(0, 188, 212, 0.1)"
                    d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,106.7C1248,96,1344,96,1392,96L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
            <div class="wave-overlay"></div>
        </div>
    </div>






    <!---->



    <!--popular cities -->

    <section class="trending-section">
        <div class="city-container">
            <h2 class="city-title">
                <span class="icon">🔥</span> Popular Destinations
            </h2>
            <h1 class="trending-city-title">TRENDING CITIES</h1>
            <p>Discover what's happening in these amazing destinations</p>

            <div class="carousel">
                <button class="carousel-btn prev" id="cityPrev" aria-label="Previous destinations">&#10094;</button>
                <div class="cards-wrapper" id="cityWrapper">
                    @foreach ($top_cities as $city)
                        @php
                            $state_name = is_object($city->display_state) ? ($city->display_state->state_name ?? 'India') : ($city->display_state ?? 'India');
                            $webp_path = 'assets/main/images/cities/' . $city->city_slug . '.webp';
                            $has_webp = file_exists(public_path($webp_path));
                            $city_img_src = $has_webp ? asset($webp_path) : $city->city_image;
                        @endphp
                        <div class="listing-card">
                            <div class="listing-card-image">
                                @if($loop->index < 2)
                                    <img src="{{ $city_img_src }}" alt="{{ $city->city_name }}" width="400" height="300" loading="lazy" onerror="this.style.display='none';" />
                                @else
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 3'%3E%3C/svg%3E" data-src="{{ $city_img_src }}" alt="{{ $city->city_name }}" width="400" height="300" class="lazy-img" onerror="this.style.display='none';" />
                                @endif
                                <div class="listing-card-badges">
                                    <span class="l-badge featured">Trending</span>
                                    <span class="l-badge closed">Popular</span>
                                </div>
                            </div>
                            <div class="listing-card-body">
                                <div class="l-category">
                                    <i class="fas fa-city" style="color: #1dbf73;"></i>
                                    {{ $city->country ?? 'India' }}
                                </div>
                                <div class="l-rating">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                        class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                                    <span>(4.5k Reviews)</span>
                                </div>
                                <h3 class="l-title">{{ $city->city_name }}</h3>
                                <p class="l-subtitle">Discover the best of {{ $city->city_name }} City</p>

                                <div class="l-info">
                                    <div class="l-info-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $state_name }}, India
                                    </div>
                                    <div class="l-info-item">
                                        <i class="fas fa-briefcase"></i>
                                        {{ number_format($city->pages_count) }}+ Active Listings
                                    </div>
                                </div>
                            </div>
                            <div class="listing-card-footer">
                                <div class="l-price">
                                    From <span>Free</span>
                                </div>
                                <div class="l-actions">
                                    <div class="l-action-icon">
                                        <i class="fas fa-camera"></i>
                                        <span class="l-action-badge">5</span>
                                    </div>
                                    <div class="l-action-icon"><i class="fas fa-video"></i></div>
                                    <div class="l-action-icon"><i class="far fa-heart"></i></div>
                                </div>
                            </div>
                            <a href="{{ route('page.city', $city->city_slug) }}" class="stretched-link" aria-label="Explore listings in {{ $city->city_name }}"></a>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-btn next" id="cityNext" aria-label="Next destinations">&#10095;</button>
            </div>

            <div class="carousel-pagination" id="cityDots"></div>
        </div>
    </section>




    <!---->





    <!-- popular categories -->
    <h2 class="category-title">POPULAR CATEGORIES</h2>
    <p class="category-subtitle">Choose a category to find trusted local businesses</p>

    <section class="py-5">
        <div class="container">
            <div class="row text-center g-4 justify-content-center">
                <!-- Category: Food -->
                <div class="col-6 col-md-3 col-lg-2"> 
                    <a href="{{ route('page.category', 'food') }}" class="category-link">
                        <div class="category-card">
                            <img src="{{ asset('assets/frontend/images/food_icon.webp') }}" alt="Food" loading="lazy" width="80" height="80">
                        </div>
                        <h6 class="category-name">Food</h6>
                    </a>
                </div>

                <!-- Category: Health -->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="{{ route('page.category', 'health') }}" class="category-link">
                        <div class="category-card">
                            <img src="{{ asset('assets/frontend/images/doctor_icon.webp') }}" alt="Health " loading="lazy" width="80" height="80">
                        </div>
                        <h6 class="category-name">Health</h6>
                    </a>
                </div>

                <!-- Category: Education -->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="{{ route('page.category', 'Education') }}" class="category-link">
                        <div class="category-card">
                            <img src="{{ asset('assets/frontend/images/education_icon.webp') }}"
                                alt="Education" loading="lazy" width="80" height="80">
                        </div>
                        <h6 class="category-name">Education</h6>
                    </a>
                </div>

                <!-- Category: Home Service -->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="{{ route('page.category', 'car-repair-services') }}" class="category-link">
                        <div class="category-card">
                            <img src="{{ asset('assets/frontend/images/settings_icon.webp') }}" alt="Home Service" loading="lazy" width="80" height="80">
                        </div>
                        <h6 class="category-name">Car Repair</h6>
                    </a>
                </div>
            </div>
            <div class="row text-center g-4 mt-3 justify-content-center">
                <!-- Category: Shooping -->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="{{ route('page.category', 'shopping') }}" class="category-link">
                        <div class="category-card">
                            <img src="{{ asset('assets/frontend/images/shopping_cart.webp') }}" alt="shopping cart" loading="lazy" width="80" height="80">
                        </div>
                        <h6 class="category-name">Shopping</h6>
                    </a>
                </div>

                <!-- Category: event -->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="#" class="category-link">
                        <div class="category-card">
                            <img src="{{ asset('assets/frontend/images/event_icon.webp') }}" alt="Event" loading="lazy" width="80" height="80" />
                        </div>
                        <h6 class="category-name">Event</h6>
                    </a>
                </div>

                <!-- Category: community -->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="#" class="category-link">
                        <div class="category-card">
                            <img src="{{ asset('assets/frontend/images/community_icon.webp') }}"
                                alt="Community" loading="lazy" width="80" height="80" />
                        </div>
                        <h6 class="category-name">Community</h6>
                    </a>
                </div>

                <!-- Category: trending-->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="#" class="category-link">
                        <div class="category-card">
                            <img src="{{ asset('assets/frontend/images/trending_icon.webp') }}"
                                alt="Trending" loading="lazy" width="80" height="80" />
                        </div>
                        <h6 class="category-name">Trending</h6>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- discovery section -->

    <section class="discovery-section">
        <h2>GET DISCOVERED BY <span>local customers</span></h2>
        <p>
            Join 3,200+ businesses that are already connecting with their community.
            From restaurants to fitness studios, we help you reach the right
            audience.
        </p>

        <div class="cards-wrapper-discovery">
            <!-- Card 1 -->
            <div class="info-card-discovery">
                <div class="card-content-discovery">
                    <h3>Reach 89K+ Local Customers</h3>
                    <p>
                        Connect with thousands of potential customers actively searching
                        in your area and get noticed faster than ever before.
                    </p>
                    <div class="stat-box-discovery">
                        <strong>3x more visibility</strong><br />
                        <span>Average improvement</span>
                    </div>
                </div>
                {{-- <img
            src="https://www.getmarvia.com/hs-fs/hubfs/lokale%20zoekopdrachten.webp?width=500&height=269&name=lokale%20zoekopdrachten.webp"
            class="card-image-discovery"
          /> --}}
                <video muted loop playsinline class="card-image-discovery lazy-video"
                    poster="{{ asset('assets/frontend/images/video-th.jpg') }}"
                    data-src="{{ asset('assets/frontend/images/social media networkundefined.mp4') }}" preload="none">
                    Your browser does not support the video tag.
                </video>
                {{-- <img
           src="./images/rech-local-customers.png"
            class="card-image-discovery"
          /> --}}
            </div>

            <!-- Card 2 -->
            <div class="info-card-discovery">
                {{-- <img
            src="https://www.datameer.com/wp-content/uploads/2018/02/Revenue-Increase-Featured-Image--909x504.png"
            alt="Boost Revenue"
            class="card-image-discovery"
          /> --}}

                <video muted loop playsinline class="card-image-discovery lazy-video"
                    poster="{{ asset('assets/frontend/images/video-th.jpg') }}"
                    data-src="{{ asset('assets/frontend/images/enhancesales.mp4') }}" preload="none">
                    Your browser does not support the video tag.
                </video>
                {{-- <img
            src="./images/boost-revenue.png"
            alt="Boost Revenue"
            class="card-image-discovery"
          /> --}}
                <div class="card-content-discovery">
                    <h3>Boost Your Revenue</h3>
                    <p>
                        Businesses experience an average of 40% growth in bookings within
                        just 3 months of joining our platform.
                    </p>
                    <div class="stat-box-discovery">
                        <strong>40% revenue boost</strong><br />
                        <span>Average improvement</span>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="info-card-discovery">
                <div class="card-content-discovery">
                    <h3>Build Your Reputation</h3>
                    <p>
                        Collect verified reviews, improve credibility, and showcase your
                        business as the trusted choice in your community.
                    </p>
                    <div class="stat-box-discovery">
                        <strong>4.8★ avg rating</strong><br />
                        <span>Average improvement</span>
                    </div>
                </div>
                <video muted loop playsinline class="card-image-discovery lazy-video"
                    poster="{{ asset('assets/frontend/images/video-th.jpg') }}"
                    data-src="{{ asset('assets/frontend/images/Shareundefined.mp4') }}" preload="none">
                    Your browser does not support the video tag.
                </video>
                {{-- <img
            src="./images/Gemini_Generated_Image_796bhk796bhk796b.png"
            alt="Build Reputation"
            class="card-image-discovery"
          /> --}}

            </div>
        </div>
    </section>

    <!-- reach customer -->

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <!-- LEFT: Content -->
                <div class="col-md-6">
                    <div class="hero-badge-customers">
                        <span>⭐ Trusted by 10,000+ businesses</span>
                    </div>
                    <h1 class="hero-title">
                        Reach More Customers,
                        <span class="gradient-text">Grow Faster</span>
                    </h1>
                    <!-- <h1 class="hero-title">
              Reach Local Customers &
              <span class="gradient-text">Grow Your Business</span>
            </h1> -->
                    <p class="hero-description">
                        Thousands of small businesses use CityHangaround to attract local customers every day
                    </p>
                    <!-- <p class="hero-description">
              Connect with your community, showcase your offerings, and build
              lasting relationships with local customers.
            </p> -->
                    <div class="hero-buttons">
                        @if (auth()->user())
                            <a href="{{ route('timeline') }}" class="text-decoration-none">
                                <button class="primary-button">Go to Feed</button>
                            </a>
                        @else
                            <button class="primary-button" onclick="openLoginModal(event, 'register')">Register Now - It's Free!</button>
                        @endif
                        <button class="primary-button">Watch Demo</button>
                    </div>
                </div>

                <!-- RIGHT: Image -->
                <div class="col-md-6 text-center mt-3 mt-md-0">
                    <video class="hero-video lazy-video" muted loop playsinline
                        poster="{{ asset('assets/frontend/images/video-th.jpg') }}"
                        data-src="{{ asset('assets/frontend/images/handshake1.mp4') }}" preload="none">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->

    <!-- animated feature section  -->
    <section id="features" class="features">
        <div class="container-feature">
            <h2 class="section-title">Our <span class="gradient-text-feature">Features</span></h2>
            <div class="row g-4">
                <!-- Business -->
                <div class="col-md-3 col-sm-6">
                    <div class="feature-card">
                        <!-- Icon with circle -->
                        <div class="icon-circle">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3 class="feature-title">Add Business</h3>
                        <p class="feature-description">
                            Showcase your business details, services, and contact info.
                        </p>
                        <button class="btn-feature" onclick="window.location.href='{{ route('pages') }}';">
                            <span>Get Started →</span>
                        </button>
                    </div>
                </div>

                <!-- Deals -->
                <div class="col-md-3 col-sm-6">
                    <div class="feature-card">
                        <div class="icon-circle">
                            <i class="fas fa-tags"></i>
                        </div>
                        <h3 class="feature-title">Add Deals</h3>
                        <p class="feature-description">
                            Attract new customers with offers, discounts, and promotions.
                        </p>
                        <button class="btn-feature" onclick="window.location.href='{{ route('allproducts') }}';">
                            <span>Create Deal →</span>
                        </button>
                    </div>
                </div>

                <!-- Events -->
                <div class="col-md-3 col-sm-6">
                    <div class="feature-card">
                        <div class="icon-circle">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="feature-title">Add Events</h3>
                        <p class="feature-description">
                            Promote community events, workshops, and networking opportunities.
                        </p>
                        <button class="btn-feature"
                            onclick="window.location.href='{{ route('event') }}';">
                            <span>Plan Event →</span>
                        </button>
                    </div>
                </div>

                <!-- Influencers -->
                <div class="col-md-3 col-sm-6">
                    <div class="feature-card">
                        <div class="icon-circle">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <h3 class="feature-title">Influencers</h3>
                        <p class="feature-description">
                            Connect with local personalities to expand your brand.
                        </p>
                        <button class="btn-feature"
                            onclick="window.location.href='{{ route('custom_pages.show', 'influencer') }}';">
                            <span>Find Influencers →</span>
                        </button>
                    </div>
                </div>

                <!-- Blog -->
                <div class="col-md-3 col-sm-6">
                    <div class="feature-card">
                        <div class="icon-circle">
                            <i class="fas fa-blog"></i>
                        </div>
                        <h3 class="feature-title">Add Blog</h3>
                        <p class="feature-description">
                            Share industry insights, stories, and updates through blogging.
                        </p>
                        <button class="btn-feature" onclick="window.location.href='{{ route('blogs.direct') }}';">
                            <span>Write Blog →</span>
                        </button>
                    </div>
                </div>

                <!-- Community -->
                <div class="col-md-3 col-sm-6">
                    <div class="feature-card">
                        <div class="icon-circle">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="feature-title">Join Community</h3>
                        <p class="feature-description">
                            Engage with like-minded individuals and businesses locally.
                        </p>
                        <button class="btn-feature" onclick="window.location.href='{{ route('groups') }}';">
                            <span>Join Now →</span>
                        </button>
                    </div>
                </div>

                <!-- Trending Deals -->
                <div class="col-md-3 col-sm-6">
                    <div class="feature-card">
                        <div class="icon-circle">
                            <i class="fas fa-tags"></i>
                        </div>
                        <h3 class="feature-title">Trending Deals</h3>
                        <p class="feature-description">
                            Discover popular products and services with the best deals near you.
                        </p>
                        <button class="btn-feature" onclick="window.location.href='/product/filter';">
                            <span>Explore Now →</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- explore city hangaround -->

    <div class="container-explore">
        <div class="header-explore">
            <h2>
                Explore
                <span class="gradient-text-cityhangaround">cityhangaround</span>
                now
            </h2>
        </div>


    </div>
    <!-- //new counters ........  -->/
    <section class="counter-section">
        <div class="container">
            <div class="row align-items-center">
                <!-- Left image -->
                <div class="col-md-6 text-center mb-4 mb-md-0">

                    {{--<img--}}
                    {{--  src="./images/local-deal-removebg-preview-Picsart-AiImageEnhancer.png"--}}
                    <!--  alt="Traveler"-->
                    {{--  class="img-fluid rounded"--}}
                    <img src="{{ asset('assets/frontend/images/local_deal.webp') }}"
                        alt="Traveler" class="img-fluid rounded" loading="lazy" width="500" height="400" />
                    <!--/>-->
                </div>

                <!-- Right content -->
                <div class="col-md-6">
                    <span class="tagline">Who We Are</span>
                    <h2>
                        Here Is Great Opportunity For <br />
                        Business & Advertisement
                    </h2>
                    <p class="mt-3 explore-content">
                        Welcome to CityHang Around — your one-stop destination for
                        discovering local businesses, exploring marketplace deals, and
                        promoting your brand through powerful advertisements. Whether
                        you're a customer searching for trusted services or a business
                        looking to grow your presence, CityHang Around connects
                        communities with opportunities. Start listing, exploring, and
                        advertising today!
                    </p>

                    <!-- Counters -->
                    <div class="row mt-4">
                        <div class="col-4 counter-item">
                            <img src="{{ asset('assets/frontend/images/counter_location.webp') }}" alt="Businesses" loading="lazy" width="50" height="50" />
                            <h4>5,000+</h4>
                            <p>Business Listed</p>
                        </div>
                        <div class="col-4 counter-item">
                            <img src="{{ asset('assets/frontend/images/counter_campaign.webp') }}" alt="Campaigns" loading="lazy" width="50" height="50" />
                            <h4>3,000+</h4>
                            <p>Campaign Completed</p>
                        </div>
                        <div class="col-4 counter-item">
                            <img src="{{ asset('assets/frontend/images/counter_happy.webp') }}" alt="Happy Clients" loading="lazy" width="50" height="50" />
                            <h4>11,000+</h4>
                            <p>Happy Clients</p>
                        </div>
                        {{-- <div class="col-4 counter-item mt-3">
                <img
                  src="https://img.icons8.com/?size=50&id=2koI9uU0dBK7&format=png&color=1A1A1A"
                />
                <h4>15+</h4>
                <p>Years of Experience</p>
              </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- client section -->

    <section class="client-section">
        <div class="container">
            <h2 class="section-title"> Trusted by our<br />
                customers & partners</h2>

            <div class="row justify-content-center">
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <img src="{{ asset('assets/frontend/images/SoulButtonsLogo.webp') }}"
                            alt="The SoulButtonsLogo" width="120" height="60" loading="lazy">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <img src="{{ asset('assets/frontend/images/Classplus_Logo.webp') }}"
                            alt="Classplus Logo" width="120" height="60" loading="lazy">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <img src="{{ asset('assets/frontend/images/De_Brewerz_Logo.webp') }}" alt="De-Brewerz LOGO" width="120" height="60" loading="lazy">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <img src="{{ asset('assets/frontend/images/dhpgraphics.webp') }}" alt="dhpgraphics" width="120" height="60" loading="lazy">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <img src="{{ asset('assets/frontend/images/DNJK-Logo-New.webp') }}" alt="DNJK-Logo" width="120" height="60" loading="lazy">
                    </div>
                </div>

                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <img src="{{ asset('assets/frontend/images/ebizee.webp') }}" alt="Ebizee Image" width="120" height="60" loading="lazy">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <img src="{{ asset('assets/frontend/images/genuine_hosting.webp') }}"
                            alt="genuine hosting reviews" width="120" height="60" loading="lazy">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <img src="{{ asset('assets/frontend/images/shri_exports.webp') }}" alt="shri exports logo" width="120" height="60" loading="lazy">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <img src="{{ asset('assets/frontend/images/thequeensway.webp') }}" alt="thequeensway" width="120" height="60" loading="lazy">
                    </div>
                </div>

            </div>
        </div>
        </div>
    </section>

    <!-- testimonials -->
    <div class="testimonials-container">
        <!-- Header Section -->
        <div class="test-header-section">
            <div class="test-badge">
                <i class="fas fa-users"></i>
                <span>Customer Stories</span>
            </div>
            <h1 class="main-title">
                Hear what our
                <span class="test-gradient-text">customers</span>
                are saying
            </h1>
            <p class="subtitle">
                Discover how Cityhangaround is transforming businesses worldwide
            </p>
        </div>

        <!-- Controls -->
        <!--  Testimonials Carousel -->
        <div class="test-container">
            <div class="test-track" id="testTrack">
                <!-- Testimonial 1 -->
                <div class="testimonial-card">
                    <div class="video-container">
                        <div class="video-placeholder">
                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 9'%3E%3C/svg%3E" data-src="https://img.youtube.com/vi/1jveHrbRzmU/maxresdefault.jpg" alt="Video thumbnail" width="320" height="180" class="lazy-img" />
                            <div class="play-overlay">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>
                        <div class="video-frame" style="display: none">
                            <video controls width="100%" preload="none">
                                <source src="{{ asset('assets/frontend/images/testimonal2.mp4') }}" type="video/mp4" />
                            </video>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <h4>Amazing Experience</h4>
                        <p>This platform completely transformed how we work and collaborate...</p>
                        <div class="author">
                            <div class="avatar">HM</div>
                            <div class="author-info">
                                <span class="name">Heena Mongia</span>
                                <span class="title">Verified Customer</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 2 -->
                <div class="testimonial-card">
                    <div class="video-container">
                        <div class="video-placeholder">
                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 9'%3E%3C/svg%3E" data-src="https://img.youtube.com/vi/ZTZUiUshPj0/maxresdefault.jpg" alt="Video thumbnail" width="320" height="180" class="lazy-img" />
                            <div class="play-overlay">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>
                        <div class="video-frame" style="display: none">
                            <video controls playsinline width="100%" preload="none">
                                <source src="{{ asset('assets/frontend/images/testimonal1.mp4') }}" type="video/mp4" />
                            </video>

                        </div>
                    </div>
                    <div class="card-content">
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <h4>Game Changer</h4>
                        <p>I've never seen anything like this before. Absolutely revolutionary...</p>
                        <div class="author">
                            <div class="avatar">PM</div>
                            <div class="author-info">
                                <span class="name">Peehu Mongia</span>
                                <span class="title">Verified Customer</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 3 -->
                <div class="testimonial-card">
                    <div class="video-container">
                        <div class="video-placeholder">
                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 9'%3E%3C/svg%3E" data-src="https://img.youtube.com/vi/KavGoogIDng/maxresdefault.jpg" alt="Video thumbnail" width="320" height="180" class="lazy-img" />
                            <div class="play-overlay">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>
                        <div class="video-frame" style="display: none">
                            <video controls width="100%" preload="none">
                                <source src="{{ asset('assets/frontend/images/testimonal4.mp4') }}" type="video/mp4" />
                            </video>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <h4>Incredible Results</h4>
                        <p>The results speak for themselves. Our productivity increased by 300%...</p>
                        <div class="author">
                            <div class="avatar">VT</div>
                            <div class="author-info">
                                <span class="name">Vishal Tiwari</span>
                                <span class="title">Verified Customer</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 4 -->
                <div class="testimonial-card">
                    <div class="video-container">
                        <div class="video-placeholder">
                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 9'%3E%3C/svg%3E" data-src="https://img.youtube.com/vi/KSFi9LAjyAU/maxresdefault.jpg" alt="Video thumbnail" width="320" height="180" class="lazy-img" />
                            <div class="play-overlay">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>
                        <div class="video-frame" style="display: none">
                            <video controls width="100%" preload="none">
                                <source src="{{ asset('assets/frontend/images/testimonal3.mp4') }}" type="video/mp4" />
                            </video>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <h4>Perfect Solution</h4>
                        <p>Exactly what we were looking for. Simple, powerful, and effective...</p>
                        <div class="author">
                            <div class="avatar">CB</div>
                            <div class="author-info">
                                <span class="name">Chandni Bhatia</span>
                                <span class="title">Verified Customer</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Indicators -->
        <div class="progress-indicators" id="progressIndicators">
            <div class="indicator active"></div>
            <div class="indicator"></div>
            <div class="indicator"></div>
            <div class="indicator"></div>
        </div>
    </div>

    <!-- FAQ section  -->
    <section id="faq" class="faq">
        <h2>Frequently Asked Questions</h2>
        <ul>
            <li>
                <a><span>How cityhangaround help us?</span>
                    <span class="icon">+</span></a>
                <p>
                    Cityhangaround promotes your business and product in your local area
                    that helps you to get new customers and sales. It provides local
                    online marketing solutions for your business at very low cost.
                </p>
            </li>
            <li>
                <a><span>Which type of business can be listed here?</span>
                    <span class="icon">+</span></a>
                <p>
                    There are many types of businesses that can be listed here. Some
                    examples include: Small businesses, Local businesses, Service
                    businesses, Retail businesses, Online businesses, Wholesale
                    businesses, Import/export businesses, Franchise businesses.
                </p>
            </li>
            <li>
                <a><span>What cost do I have to pay for a listing?</span>
                    <span class="icon">+</span></a>
                <p>
                    There is no cost for listing your business and adding products and
                    services. But for promotion of your business we have affordable paid
                    plans according to the requirements of your business. You can select
                    any paid plan according to your budget and enjoy the growth of your
                    business.
                </p>
            </li>
            <li>
                <a><span>Can we add our products?</span>
                    <span class="icon">+</span></a>
                <p>
                    Yes, you can login via your registered email id and add products,
                    deals, and offers.
                </p>
            </li>
            <li>
                <a><span>Can I see how many people viewed my listing?</span>
                    <span class="icon">+</span></a>
                <p>
                    Yes! Go to your listing and you can see the total visitors and the
                    daily visitors of your listing.
                </p>
            </li>
            <li>
                <a><span>What will be the benefits of doing listing?</span>
                    <span class="icon">+</span></a>
                <p>
                    Your business gets more visibility in your area and you may get more
                    customers with the help of this free listing.
                </p>
            </li>
            <li>
                <a><span>What details do I have to put in the listing?</span>
                    <span class="icon">+</span></a>
                <p>
                    You have to add the name of your business, contact details,
                    address/location, and products/services/deals/offers you are
                    providing.
                </p>
            </li>
        </ul>
    </section>
    <!--footer-->

    <footer class="footer">
        <!-- About -->
        <div class="footer-column">
            <img src="https://cdn.cityhangaround.com/file/cityhangaround/uploads/wYMNRNks5mOFSD2V1eOkZbjeH0m3dhFpyT79tsHW.png"
                alt="eKal Academy Logo" width="200" height="50" />
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
                        width="276" height="108" style="margin-bottom: 10px" />
                </div>
                <button type="submit">Submit</button>
            </form>
        </div>
    </footer>


    <!-- Footer Bottom -->
    <!-- ✅ Footer Section -->
    <div class="footer-bottom">
        <p>Powered by Cityhangaround</p>

        <div class="footer-links">
            <a href="{{ route('custom_pages.show', 'about-us') }}" target="_blank" rel="noopener noreferrer"
                style="display: inline-block">About Us</a>
            <a href="{{ route('custom_pages.show', 'disclaimer') }}" target="_blank" rel="noopener noreferrer"
                style="display: inline-block">Disclaimer</a>
            <a href="{{ route('custom_pages.show', 'privacy-policy') }}" target="_blank"
                rel="noopener noreferrer" style="display: inline-block">Privacy Policy</a>
            <a href="{{ route('custom_pages.show', 'contact-us') }}" target="_blank" rel="noopener noreferrer"
                style="display: inline-block">Contact Us</a>
            <a href="{{ route('custom_pages.show', 'advertise') }}" target="_blank" rel="noopener noreferrer"
                style="display: inline-block">Advertise</a>
            <a href="{{ route('custom_pages.show', 'terms-and-conditions') }}" target="_blank"
                rel="noopener noreferrer" style="display: inline-block">Terms & Conditions</a>
        </div>

        <div class="social-icons">
            <a href="https://www.facebook.com/Cityhangaround/" target="_blank" aria-label="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://in.linkedin.com/company/cityhangaround" target="_blank" aria-label="LinkedIn">
                <i class="fab fa-linkedin-in"></i>
            </a>
            <a href="https://www.instagram.com/cityhangaround/" target="_blank" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.youtube.com/c/Cityhangaround" target="_blank" aria-label="YouTube">
                <i class="fab fa-youtube"></i>
            </a>
        </div>
    </div>


    <script src="{{ asset('assets/frontend/js/landingpage-new.js') }}?v=2.0.2" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>

    @include('frontend.partials.global-scripts')

    <script>
        // Scroll navigation arrows
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

        // Hero Background Video dynamic loading
        document.addEventListener("DOMContentLoaded", function() {
            var heroVideo = document.getElementById('heroBgVideo');
            if (heroVideo && window.innerWidth >= 768) {
                var src = heroVideo.getAttribute('data-src');
                if (src) {
                    var source = document.createElement('source');
                    source.src = src;
                    source.type = 'video/mp4';
                    heroVideo.appendChild(source);
                    heroVideo.load();
                    heroVideo.play().catch(function(e) {
                        console.log("Hero background video play failed/blocked:", e);
                    });
                }
            }

            // Lazy load all sub-fold videos using IntersectionObserver
            var lazyVideos = [].slice.call(document.querySelectorAll("video.lazy-video"));
            if ("IntersectionObserver" in window) {
                var videoObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(videoEntry) {
                        if (videoEntry.isIntersecting) {
                            var video = videoEntry.target;
                            var src = video.getAttribute('data-src');
                            if (src) {
                                var source = document.createElement('source');
                                source.src = src;
                                source.type = 'video/mp4';
                                video.appendChild(source);
                                video.load();
                                video.play().catch(function(e) {
                                    console.log("Lazy video play failed/blocked:", e);
                                });
                                video.classList.remove("lazy-video");
                                videoObserver.unobserve(video);
                            }
                        }
                    });
                });

                lazyVideos.forEach(function(video) {
                    videoObserver.observe(video);
                });
            } else {
                // Fallback for older browsers
                lazyVideos.forEach(function(video) {
                    var src = video.getAttribute('data-src');
                    if (src) {
                        var source = document.createElement('source');
                        source.src = src;
                        source.type = 'video/mp4';
                        video.appendChild(source);
                        video.load();
                    }
                });
            }

            // Lazy load all images with class 'lazy-img' using IntersectionObserver
            var lazyImages = [].slice.call(document.querySelectorAll("img.lazy-img"));
            if ("IntersectionObserver" in window) {
                var imgObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var img = entry.target;
                            var src = img.getAttribute('data-src');
                            if (src) {
                                img.src = src;
                                img.classList.remove("lazy-img");
                                imgObserver.unobserve(img);
                            }
                        }
                    });
                });

                lazyImages.forEach(function(img) {
                    imgObserver.observe(img);
                });
            } else {
                // Fallback for older browsers
                lazyImages.forEach(function(img) {
                    var src = img.getAttribute('data-src');
                    if (src) {
                        img.src = src;
                    }
                });
            }
        });
    </script>
</body>

</html>
