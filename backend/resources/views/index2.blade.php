@extends('frontend.layouts.app')

@section('title','CityHangarounds – Discover Local Businesses, Deals, Events & City Guide')
@section('content')


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

        <!-- Corner Accents -->
        <div class="corner-accent corner-top-left"></div>
        <div class="corner-accent corner-bottom-right"></div>

        <!-- Animated Gradient Orbs -->
        <div class="gradient-orb orb-cyan"></div>
        <div class="gradient-orb orb-orange"></div>

        <!-- Content -->
        <div class="content-wrapper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10 col-xl-8">
                        <div class="hero-content" id="heroContent">
                            <!-- Floating Icons -->
                            <div class="floating-icon icon-left">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="floating-icon icon-right">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>

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
                                    <span class="title-inner">
                                        <span class="wave-letter" style="animation-delay: 0s">D</span><span
                                            class="wave-letter" style="animation-delay: 0.05s">I</span><span
                                            class="wave-letter" style="animation-delay: 0.1s">S</span><span
                                            class="wave-letter" style="animation-delay: 0.15s">C</span><span
                                            class="wave-letter" style="animation-delay: 0.2s">O</span><span
                                            class="wave-letter" style="animation-delay: 0.25s">V</span><span
                                            class="wave-letter" style="animation-delay: 0.3s">E</span><span
                                            class="wave-letter" style="animation-delay: 0.35s">R</span><span
                                            class="wave-letter" style="animation-delay: 0.4s">&nbsp;</span><span
                                            class="wave-letter" style="animation-delay: 0.45s">W</span><span
                                            class="wave-letter" style="animation-delay: 0.5s">H</span><span
                                            class="wave-letter" style="animation-delay: 0.55s">A</span><span
                                            class="wave-letter" style="animation-delay: 0.6s">T</span><span
                                            class="wave-letter" style="animation-delay: 0.65s">&nbsp;</span><span
                                            class="wave-letter" style="animation-delay: 0.7s">Y</span><span
                                            class="wave-letter" style="animation-delay: 0.75s">O</span><span
                                            class="wave-letter" style="animation-delay: 0.8s">U</span><span
                                            class="wave-letter" style="animation-delay: 0.85s">&nbsp;</span><span
                                            class="wave-letter" style="animation-delay: 0.9s">A</span><span
                                            class="wave-letter" style="animation-delay: 0.95s">R</span><span
                                            class="wave-letter" style="animation-delay: 1s">E</span>
                                    </span>
                                    <span class="title-glow glow-red"></span>
                                </span>
                                <span class="title-line title-secondary">
                                    <span class="title-inner">
                                        <span class="wave-letter" style="animation-delay: 0.5s">l</span><span
                                            class="wave-letter" style="animation-delay: 0.55s">o</span><span
                                            class="wave-letter" style="animation-delay: 0.6s">o</span><span
                                            class="wave-letter" style="animation-delay: 0.65s">k</span><span
                                            class="wave-letter" style="animation-delay: 0.7s">i</span><span
                                            class="wave-letter" style="animation-delay: 0.75s">n</span><span
                                            class="wave-letter" style="animation-delay: 0.8s">g</span><span
                                            class="wave-letter" style="animation-delay: 0.85s">&nbsp;</span><span
                                            class="wave-letter" style="animation-delay: 0.9s">f</span><span
                                            class="wave-letter" style="animation-delay: 0.95s">o</span><span
                                            class="wave-letter" style="animation-delay: 1s">r</span>
                                    </span>
                                    <span class="title-glow glow-cyan"></span>
                                </span>
                            </h1>

                            <!-- Subtitle -->
                            <p class="hero-subtitle" id="heroSubtitle">
                                <span class="subtitle-text">The City's Best Events, Deals, Places, Stories & Many
                                    more</span>
                                <span class="subtitle-shimmer"></span>
                            </p>

                            <!-- Buttons -->
                            <div class="hero-buttons" id="heroButtons">
                                <button class="btn-hero btn-primary-hero">
                                    <span class="btn-gradient"></span>
                                    <span class="btn-overlay"></span>
                                    <i class="bi bi-play-fill btn-icon"></i>
                                    <span class="btn-text">Start Exploring</span>
                                    <span class="btn-glow"></span>
                                    <span class="btn-ripple ripple-1"></span>
                                    <span class="btn-ripple ripple-2"></span>
                                    <span class="btn-ripple ripple-3"></span>
                                </button>

                                <a href="{{ route('login') }}" target="_blank"
                                    class="text-decoration-none">
                                    <button class="btn-hero btn-secondary-hero">
                                        <span class="btn-gradient"></span>
                                        <span class="btn-overlay"></span>
                                        <i class="bi bi-calendar-event btn-icon"></i>
                                        <span class="btn-text">Post an Activity</span>
                                        <span class="btn-glow"></span>
                                        <span class="btn-ripple ripple-1"></span>
                                        <span class="btn-ripple ripple-2"></span>
                                        <span class="btn-ripple ripple-3"></span>
                                    </button>
                                </a>
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
            <svg class="wave-svg wave-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"
                preserveAspectRatio="none">
                <path fill="rgba(255, 107, 53, 0.1)"
                    d="M0,192L48,197.3C96,203,192,213,288,192C384,171,480,117,576,106.7C672,96,768,128,864,154.7C960,181,1056,203,1152,197.3C1248,192,1344,160,1392,144L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
            <div class="wave-overlay"></div>
        </div>
    </div>
    <!--popular cities -->
    <section class="trending-section">
        <div class="city-container">
            <h2 class="city-title">
                <span class="icon">🔥</span> Popular Destinations
            </h2>
            <h1 class="trending-city-title">TRENDING CITIES</h1>
            <p>Discover what's happening in these amazing destinations</p>

            <div class="carousel">
                <!--Prev Button -->
                <button class="carousel-btn prev" id="cityPrev">&#10094;</button>

                <!--Cards Wrapper -->

                <div class="cards-wrapper" id="cityWrapper">
                    @foreach ($top_cities as $city)
                        <div class="card">
                            <div class="card-image">
                                <img src="{{ Str::startsWith($city->city_image ?? '', 'http') ? $city->city_image ?? '' : ($city->city_image ? asset('storage/cities/logo/' . $city->city_image) : asset('images/default-city.jpg')) }}"
                                    alt="{{ $city->city_name ?? 'City' }}" />
                                <span class="badge trending">Trending</span>
                                <span class="badge rating">4.8⭐</span>
                            </div>
                            <div class="card-content">
                                <h3>{{ $city->city_name ?? 'City' }}</h3>
                                <p>8,336,817 people</p>
                                <p>
                                    <span class="activities">1247 activities</span>
                                    <span class="growth">+9% this month</span>
                                </p>
                                <button>Explore {{ $city->city_name ?? 'City' }}</button>
                            </div>

                        </div>
                    @endforeach
                </div>
                <!--Next Button -->
                <button class="carousel-btn next" id="cityNext">&#10095;</button>
            </div>

            <!--Pagination Dots -->
            <div class="carousel-pagination" id="cityDots"></div>

        </div>

    </section>
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
                            <!--<img-->
                            <!--  src="./images/Food.gif"-->
                            <!--  alt="Food"-->
                            <!--/>-->
                            <img src="{{ asset('assets/frontend/images/food_icon.webp') }}" alt="Food" loading="lazy" width="80" height="80">
                        </div>
                        <h6 class="category-name">Food</h6>
                    </a>
                </div>

                <!-- Category: Health -->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="{{ route('page.category', 'health') }}" class="category-link">
                        <div class="category-card">
                            <!--<img-->
                            <!--  src="./images/Heart Dementia Doctor.gif"-->
                            <!--  alt="Health"-->
                            <!--/>-->
                            <img src="{{ asset('assets/frontend/images/doctor_icon.webp') }}" alt="Health " loading="lazy" width="80" height="80">
                        </div>
                        <h6 class="category-name">Health</h6>
                    </a>
                </div>

                <!-- Category: Education -->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="{{ route('page.category', 'Education') }}" class="category-link">
                        <div class="category-card">
                            <!--<img-->
                            <!--  src="./images/Knowledge, Idea, Power, Books, Creativity, Learning Animation icon..gif"-->
                            <!--  alt="Education"-->
                            <!--/>-->
                            <img src="{{ asset('assets/frontend/images/education_icon.webp') }}" alt="Education" loading="lazy" width="80" height="80">
                        </div>
                        <h6 class="category-name">Education</h6>
                    </a>
                </div>

                <!-- Category: Home Service -->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="{{ route('page.category', 'car-repair-services') }}" class="category-link">
                        <div class="category-card">
                            <!--<img-->
                            <!--  src="./images/Settings icon.gif"-->
                            <!--  alt="Home Service"-->
                            <!--/>-->
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
                            <!--<img-->
                            <!--  src="./images/shopping cart.gif"-->
                            <!--  alt="Food"-->
                            <!--/>-->
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
                <!-- <img
            src="https://www.getmarvia.com/hs-fs/hubfs/lokale%20zoekopdrachten.webp?width=500&height=269&name=lokale%20zoekopdrachten.webp"
            class="card-image-discovery"
          /> -->
                <video autoplay muted loop playsinline class="card-image-discovery"
                    poster="{{ asset('assets/frontend/images/video-th.jpg') }}">
                    <source src="{{ asset('assets/frontend/images/social media networkundefined.mp4') }}"
                        type="video/mp4" />

                    Your browser does not support the video tag.
                </video>
                <!-- <img
           src="./images/rech-local-customers.png"
            class="card-image-discovery"
          /> -->
            </div>

            <!-- Card 2 -->
            <div class="info-card-discovery">
                <!-- <img
            src="https://www.datameer.com/wp-content/uploads/2018/02/Revenue-Increase-Featured-Image--909x504.png"
            alt="Boost Revenue"
            class="card-image-discovery"
          /> -->

                <video autoplay muted loop playsinline class="card-image-discovery"
                    poster="{{ asset('assets/frontend/images/video-th.jpg') }}">
                    <source src="{{ asset('assets/frontend/images/enhancesales.mp4') }}" type="video/mp4" />

                    Your browser does not support the video tag.
                </video>
                <!-- <img
            src="./images/boost-revenue.png"
            alt="Boost Revenue"
            class="card-image-discovery"
          /> -->
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
                <video autoplay muted loop playsinline class="card-image-discovery"
                    poster="{{ asset('assets/frontend/images/video-th.jpg') }}">
                    <source src="{{ asset('assets/frontend/images/Shareundefined.mp4') }}" type="video/mp4" />

                    Your browser does not support the video tag.
                </video>
                <!-- <img
            src="./images/Gemini_Generated_Image_796bhk796bhk796b.png"
            alt="Build Reputation"
            class="card-image-discovery"
          /> -->

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
                        <button class="primary-button" onclick="window.location.href='{{ route('login') }}'">Register
                            Now - It's Free!</button>
                        <button class="primary-button">Watch Demo</button>
                    </div>
                </div>

                <!-- RIGHT: Image -->
                <div class="col-md-6 text-center mt-3 mt-md-0">
                    <video class="hero-video" autoplay muted loop
                        poster="{{ asset('assets/frontend/images/video-th.jpg') }}">
                        <source src="{{ asset('assets/frontend/images/handshake1.mp4') }}" type="video/mp4" />
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
                        <button class="btn-feature"
                            onclick="window.location.href='{{ route('pages') }}';">
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
                        <button class="btn-feature"
                            onclick="window.location.href='{{ route('allproducts') }}';">
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
                        <button class="btn-feature"
                            onclick="window.location.href='{{ route('blogs.direct') }}';">
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
                        <button class="btn-feature"
                            onclick="window.location.href='{{ route('groups') }}';">
                            <span>Join Now →</span>
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

                    <!--<img-->
                    <!--  src="./images/local-deal-removebg-preview-Picsart-AiImageEnhancer.png"-->
                    <!--  alt="Traveler"-->
                    <!--  class="img-fluid rounded"-->
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
                        <!-- <div class="col-4 counter-item mt-3">
                <img
                  src="https://img.icons8.com/?size=50&id=2koI9uU0dBK7&format=png&color=1A1A1A"
                />
                <h4>15+</h4>
                <p>Years of Experience</p>
              </div> -->
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
                        <!--<img-->
                        <!--  src="images/The_SoulButtonsLogo-removebg-preview.png"-->
                        <!--  alt="Client Logo 1"-->
                        <!--/>-->
                        <img src="{{ asset('assets/frontend/images/SoulButtonsLogo.webp') }}"
                            alt="The SoulButtonsLogo" width="120" height="60" loading="lazy">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <!--<img-->
                        <!--  src="images/Classplus_Logo-removebg-preview.png"-->
                        <!--  alt="Client Logo 2"-->
                        <!--/>-->
                        <img src="{{ asset('assets/frontend/images/Classplus_Logo.webp') }}"
                            alt="Classplus Logo" width="120" height="60" loading="lazy">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <!--<img-->
                        <!--  src="images/De-Brewerz LOGO.jpg"-->
                        <!--  alt="Client Logo 3"-->
                        <!--/>-->
                        <img src="{{ asset('assets/frontend/images/De_Brewerz_Logo.webp') }}" alt="De-Brewerz LOGO" width="120" height="60" loading="lazy">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <!--<img-->
                        <!--  src="images/dhpgraphics.png"-->
                        <!--  alt="Client Logo 4"-->
                        <!--/>-->
                        <img src="{{ asset('assets/frontend/images/dhpgraphics.webp') }}" alt="dhpgraphics" width="120" height="60" loading="lazy">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <!--<img-->
                        <!--  src="images/DNJK-Logo-New.webp"-->
                        <!--  alt="Client Logo 5"-->
                        <!--/>-->
                        <img src="{{ asset('assets/frontend/images/DNJK-Logo-New.webp') }}" alt="DNJK-Logo">
                    </div>
                </div>

                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <!--<img-->
                        <!--  src="images/ebizee.png"-->
                        <!--  alt="Client Logo 6"-->
                        <!--/>-->
                        <img src="{{ asset('assets/frontend/images/ebizee.webp') }}" alt="Ebizee Image" width="120" height="60" loading="lazy">

                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <!--<img-->
                        <!--  src="images/genuine_hosting_reviews_logo-removebg-preview.png"-->
                        <!--  alt="Client Logo 7"-->
                        <!--/>-->
                        <img src="{{ asset('assets/frontend/images/genuine_hosting.webp') }}"
                            alt="genuine_hosting" width="120" height="60" loading="lazy">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <!--<img-->
                        <!--  src="images/shri_exports_logo.jpg"-->
                        <!--  alt="Client Logo 8"-->
                        <!--/>-->
                        <img src="{{ asset('assets/frontend/images/shri_exports.webp') }}"
                            alt="shri_exports_logo" width="120" height="60" loading="lazy">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <!--<img-->
                        <!--  src="images/thequeensway.png"-->
                        <!--  alt="Client Logo 9"-->
                        <!--/>-->
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
                            <img src="https://img.youtube.com/vi/1jveHrbRzmU/maxresdefault.jpg"
                                alt="Video thumbnail" />
                            <div class="play-overlay">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>
                        <div class="video-frame" style="display: none">
                            <video controls width="100%">
                                <source src="{{ asset('assets/frontend/images/testimonal2.mp4') }}"
                                    type="video/mp4" />
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
                            <img src="https://img.youtube.com/vi/ZTZUiUshPj0/maxresdefault.jpg"
                                alt="Video thumbnail" />
                            <div class="play-overlay">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>
                        <div class="video-frame" style="display: none">
                            <video controls playsinline width="100%">
                                <source src="{{ asset('assets/frontend/images/testimonal1.mp4') }}"
                                    type="video/mp4" />
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
                            <img src="https://img.youtube.com/vi/KavGoogIDng/maxresdefault.jpg"
                                alt="Video thumbnail" />
                            <div class="play-overlay">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>
                        <div class="video-frame" style="display: none">
                            <video controls width="100%">
                                <source src="{{ asset('assets/frontend/images/testimonal4.mp4') }}"
                                    type="video/mp4" />
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
                            <img src="https://img.youtube.com/vi/KSFi9LAjyAU/maxresdefault.jpg"
                                alt="Video thumbnail" />
                            <div class="play-overlay">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>
                        <div class="video-frame" style="display: none">
                            <video controls width="100%">
                                <source src="{{ asset('assets/frontend/images/testimonal3.mp4') }}"
                                    type="video/mp4" />
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




    
    @push('scripts')
    <script src="{{ asset('assets/frontend/js/landingpage-new.js') }}"></script>
    @endpush

@endsection
