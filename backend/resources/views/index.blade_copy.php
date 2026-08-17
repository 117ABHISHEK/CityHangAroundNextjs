<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Core Meta -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CityHangarounds – Discover Local Businesses, Deals, Events & City Guide</title>
    <meta name="description"
        content="Explore your city’s top businesses, best deals, events, jobs, blogs, and more on CityHangaround. Find local shops, services, influencers, and trending updates instantly.">

    <!--  Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet" />

    <!--  Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!--  Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!--  Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/landingpage-new.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/landingpage-new-hero.css') }}">

    <!--  jQuery & Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


</head>

<body>
    @include('frontend.header')

    <!-- Floating Particles -->
    <div class="particles-container" id="particles"></div>
    <!-- Hero Container -->
    <div class="hero-container">
        <!-- Background Video -->
        <video autoplay muted loop playsinline class="bg-video"
            poster="https://images.pexels.com/photos/2224861/pexels-photo-2224861.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080">
            <source src="{{ asset('assets/main/images/backgroundvideo.mp4') }}" type="video/mp4" />
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
                                <a href="https://www.cityhangaround.com/login" target="_blank"
                                    class="text-decoration-none">
                                    <button class="btn-hero btn-secondary-hero">
                                        <span class="btn-text">Post an Activity</span>
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
                <button class="carousel-btn prev" id="cityPrev">&#10094;</button>
                <div class="cards-wrapper" id="cityWrapper">
                    @foreach ($top_cities as $city)
                        @php
                            $state_name = is_object($city->display_state) ? ($city->display_state->state_name ?? 'India') : ($city->display_state ?? 'India');
                        @endphp
                        <div class="listing-card">
                            <div class="listing-card-image">
                                <img src="{{ $city->thumbnail }}" alt="{{ $city->city_name }}" loading="lazy" />
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
                            <a href="{{ route('page.city', $city->city_slug) }}" class="stretched-link"></a>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-btn next" id="cityNext">&#10095;</button>
            </div>
    
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
                    <a href="https://www.cityhangaround.com/category/food" class="category-link">
                        <div class="category-card">
                            <!--<img-->
                            <!--  src="./images/Food.gif"-->
                            <!--  alt="Food"-->
                            <!--/>-->
                            <img src="{{ asset('assets/frontend/images/Food.gif') }}" alt="Food">
                        </div>
                        <h6 class="category-name">Food</h6>
                    </a>
                </div>

                <!-- Category: Health -->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="https://www.cityhangaround.com/category/health" class="category-link">
                        <div class="category-card">
                            <!--<img-->
                            <!--  src="./images/Heart Dementia Doctor.gif"-->
                            <!--  alt="Health"-->
                            <!--/>-->
                            <img src="{{ asset('assets/frontend/images/Heart Dementia Doctor.gif') }}" alt="Health ">
                        </div>
                        <h6 class="category-name">Health</h6>
                    </a>
                </div>

                <!-- Category: Education -->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="https://www.cityhangaround.com/category/Education" class="category-link">
                        <div class="category-card">
                            <!--<img-->
                            <!--  src="./images/Knowledge, Idea, Power, Books, Creativity, Learning Animation icon..gif"-->
                            <!--  alt="Education"-->
                            <!--/>-->
                            <img src="{{ asset('assets/frontend/images/Knowledge, Idea, Power, Books, Creativity, Learning Animation icon..gif') }}"
                                alt="Education">
                        </div>
                        <h6 class="category-name">Education</h6>
                    </a>
                </div>

                <!-- Category: Home Service -->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="https://www.cityhangaround.com/category/car-repair-services" class="category-link">
                        <div class="category-card">
                            <!--<img-->
                            <!--  src="./images/Settings icon.gif"-->
                            <!--  alt="Home Service"-->
                            <!--/>-->
                            <img src="{{ asset('assets/frontend/images/Settings icon.gif') }}" alt="Home Service">
                        </div>
                        <h6 class="category-name">Car Repair</h6>
                    </a>
                </div>
            </div>
            <div class="row text-center g-4 mt-3 justify-content-center">
                <!-- Category: Shooping -->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="https://www.cityhangaround.com/category/shopping" class="category-link">
                        <div class="category-card">
                            <!--<img-->
                            <!--  src="./images/shopping cart.gif"-->
                            <!--  alt="Food"-->
                            <!--/>-->
                            <img src="{{ asset('assets/frontend/images/shopping cart.gif') }}" alt="shopping cart">
                        </div>
                        <h6 class="category-name">Shopping</h6>
                    </a>
                </div>

                <!-- Category: event -->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="#" class="category-link">
                        <div class="category-card">
                            <img src="https://cdn-icons-gif.flaticon.com/17905/17905363.gif" alt="Health" />
                        </div>
                        <h6 class="category-name">Event</h6>
                    </a>
                </div>

                <!-- Category: community -->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="#" class="category-link">
                        <div class="category-card">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTmNHLC5OThjRU_GI-DsRzOMJaKu9gMJXa15w&s"
                                alt="Education" />
                        </div>
                        <h6 class="category-name">Community</h6>
                    </a>
                </div>

                <!-- Category: trending-->
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="#" class="category-link">
                        <div class="category-card">
                            <img src="https://img.freepik.com/premium-vector/trending-now-typography-sticker-flat-style_9206-29985.jpg?semt=ais_hybrid&w=740&q=80"
                                alt="Home Service" />
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
                    poster="https://images.pexels.com/photos/2224861/pexels-photo-2224861.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080">
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
                    poster="https://images.pexels.com/photos/2224861/pexels-photo-2224861.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080">
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
                    poster="https://images.pexels.com/photos/2224861/pexels-photo-2224861.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080">
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
                        poster="https://images.pexels.com/photos/2224861/pexels-photo-2224861.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080">
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
                        <button class="btn-feature" onclick="window.location.href='https://cityhangaround.com/pages';">
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
                        <button class="btn-feature" onclick="window.location.href='https://cityhangaround.com/deals';">
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
                            onclick="window.location.href='https://cityhangaround.com/event/all';">
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
                            onclick="window.location.href='https://www.cityhangaround.com/pages/custom/influencer';">
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
                        <button class="btn-feature" onclick="window.location.href='https://cityhangaround.com/blogs';">
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
                        <button class="btn-feature" onclick="window.location.href='https://cityhangaround.com/groups';">
                            <span>Join Now →</span>
                        </button>
                    </div>
                </div>

                <!-- Videos -->
                <div class="col-md-3 col-sm-6">
                    <div class="feature-card">
                        <div class="icon-circle">
                            <i class="fas fa-video"></i>
                        </div>
                        <h3 class="feature-title">Trending Videos</h3>
                        <p class="feature-description">
                            Discover and share popular video content around your niche.
                        </p>
                        <button class="btn-feature" onclick="window.location.href='https://cityhangaround.com/videos';">
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

                    <!--<img-->
                    <!--  src="./images/local-deal-removebg-preview-Picsart-AiImageEnhancer.png"-->
                    <!--  alt="Traveler"-->
                    <!--  class="img-fluid rounded"-->
                    <img src="{{ asset('assets/frontend/images/local-deal-removebg-preview-Picsart-AiImageEnhancer.png') }}"
                        alt="Traveler" class="img-fluid rounded" />
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
                            <img src="https://img.icons8.com/ios-filled/50/000000/worldwide-location.png" />
                            <h4>5,000+</h4>
                            <p>Business Listed</p>
                        </div>
                        <div class="col-4 counter-item">
                            <img src="https://img.icons8.com/?size=50&id=yXBNCr2DEgJN&format=png&color=000000" />
                            <h4>3,000+</h4>
                            <p>Campaign Completed</p>
                        </div>
                        <div class="col-4 counter-item">
                            <img src="https://img.icons8.com/ios-filled/50/000000/smiling.png" />
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
                        <img src="{{ asset('assets/frontend/images/The_SoulButtonsLogo-removebg-preview.png') }}"
                            alt="The_SoulButtonsLogo">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <!--<img-->
                        <!--  src="images/Classplus_Logo-removebg-preview.png"-->
                        <!--  alt="Client Logo 2"-->
                        <!--/>-->
                        <img src="{{ asset('assets/frontend/images/Classplus_Logo-removebg-preview.png') }}"
                            alt="Classplus_Logo">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <!--<img-->
                        <!--  src="images/De-Brewerz LOGO.jpg"-->
                        <!--  alt="Client Logo 3"-->
                        <!--/>-->
                        <img src="{{ asset('assets/frontend/images/De-Brewerz LOGO.jpg') }}" alt="De-Brewerz LOGO">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <!--<img-->
                        <!--  src="images/dhpgraphics.png"-->
                        <!--  alt="Client Logo 4"-->
                        <!--/>-->
                        <img src="{{ asset('assets/frontend/images/dhpgraphics.png') }}" alt="dhpgraphics">
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
                        <img src="{{ asset('assets/frontend/images/ebizee.png') }}" alt="Ebigee Image">

                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <!--<img-->
                        <!--  src="images/genuine_hosting_reviews_logo-removebg-preview.png"-->
                        <!--  alt="Client Logo 7"-->
                        <!--/>-->
                        <img src="{{ asset('assets/frontend/images/genuine_hosting_reviews_logo-removebg-preview.png') }}"
                            alt="genuine_hosting">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <!--<img-->
                        <!--  src="images/shri_exports_logo.jpg"-->
                        <!--  alt="Client Logo 8"-->
                        <!--/>-->
                        <img src="{{ asset('assets/frontend/images/shri_exports_logo.jpg') }}" alt="shri_exports_logo">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-5-custom">
                    <div class="client-logo-box">
                        <!--<img-->
                        <!--  src="images/thequeensway.png"-->
                        <!--  alt="Client Logo 9"-->
                        <!--/>-->
                        <img src="{{ asset('assets/frontend/images/thequeensway.png') }}" alt="thequeensway">
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
                            <img src="https://img.youtube.com/vi/1jveHrbRzmU/maxresdefault.jpg" alt="Video thumbnail" />
                            <div class="play-overlay">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>
                        <div class="video-frame" style="display: none">
                            <video controls width="100%">
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
                            <img src="https://img.youtube.com/vi/ZTZUiUshPj0/maxresdefault.jpg" alt="Video thumbnail" />
                            <div class="play-overlay">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>
                        <div class="video-frame" style="display: none">
                            <video controls playsinline width="100%">
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
                            <img src="https://img.youtube.com/vi/KavGoogIDng/maxresdefault.jpg" alt="Video thumbnail" />
                            <div class="play-overlay">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>
                        <div class="video-frame" style="display: none">
                            <video controls width="100%">
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
                            <img src="https://img.youtube.com/vi/KSFi9LAjyAU/maxresdefault.jpg" alt="Video thumbnail" />
                            <div class="play-overlay">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>
                        <div class="video-frame" style="display: none">
                            <video controls width="100%">
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


    <!-- Footer Bottom -->
    <!-- ✅ Footer Section -->
    <div class="footer-bottom">
        <p>Powered by Cityhangaround</p>

        <div class="footer-links">
            <a href="https://www.cityhangaround.com/pages/custom/about-us" target="_blank" rel="noopener noreferrer"
                style="display: inline-block">About Us</a>
            <a href="https://www.cityhangaround.com/pages/custom/disclaimer" target="_blank" rel="noopener noreferrer"
                style="display: inline-block">Disclaimer</a>
            <a href="https://www.cityhangaround.com/pages/custom/privacy-policy" target="_blank"
                rel="noopener noreferrer" style="display: inline-block">Privacy Policy</a>
            <a href="https://www.cityhangaround.com/pages/custom/contact-us" target="_blank" rel="noopener noreferrer"
                style="display: inline-block">Contact Us</a>
            <a href="https://www.cityhangaround.com/pages/custom/influencer" target="_blank" rel="noopener noreferrer"
                style="display: inline-block">Influencers</a>
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


    <script src="{{ asset('assets/frontend/js/landingpage-new.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>