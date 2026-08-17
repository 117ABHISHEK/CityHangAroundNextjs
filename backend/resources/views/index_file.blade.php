<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    @php
        $system_name = \App\Models\Setting::where('type', 'system_name')->value('description');
        $system_favicon = \App\Models\Setting::where('type', 'system_fav_icon')->value('description');
    @endphp
    
    {!! SEO::generate() !!}
    <title>{{ $system_name }}</title>

    <!-- CSRF Token for ajax for submission -->
    <meta name="csrf_token" content="{{ csrf_token() }}" />

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="shortcut icon" href="{{ get_system_logo_favicon($system_favicon,'favicon') }}" />

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="{{asset('assets/frontend/css/fontawesome/all.min.css')}}" media="print" onload="this.media='all'">
    <!-- CSS Library -->

    <link rel="stylesheet" href="{{asset('assets/frontend/css/owl.carousel.min.css')}}" media="print" onload="this.media='all'">

    <!-- Style css -->
    <link rel="stylesheet" href="{{asset('assets/frontend/css/nice-select.css')}}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{asset('assets/frontend/plyr/plyr.css')}}" media="print" onload="this.media='all'">
    <link href="{{asset('assets/frontend/leafletjs/leaflet.css')}}" rel="stylesheet" media="print" onload="this.media='all'">

    <link href="{{asset('assets/frontend/css/plyr_cdn_dw.css')}}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{asset('assets/frontend/css/tagify.css')}}" rel="stylesheet" media="print" onload="this.media='all'">

    <link href="{{asset('assets/frontend/uploader/file-uploader.css')}}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{asset('assets/frontend/css/jquery-rbox.css')}}" rel="stylesheet" media="print" onload="this.media='all'">

    <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap.min.css')}}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/style.css')}}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{asset('assets/frontend/gallery/justifiedGallery.min.css')}}" media="print" onload="this.media='all'">
    <link href="{{asset('assets/frontend/toaster/toaster.css')}}" rel="stylesheet" media="print" onload="this.media='all'">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/summernote-0.8.18-dist/summernote-lite.min.css') }}" media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="{{asset('assets/frontend/css/own.css')}}" media="print" onload="this.media='all'">
    
    <script src="{{asset('assets/frontend/js/jquery-3.6.0.min.js')}}"></script>



  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" media="print" onload="this.media='all'">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" media="print" onload="this.media='all'">
  <!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'" />

<!-- jQuery (required by Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Travel Hero Section</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" media="print" onload="this.media='all'" />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" media="print" onload="this.media='all'" />
  <link rel="stylesheet" href="{{asset('assets/main/css/style.css')}}" media="print" onload="this.media='all'" />

  <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" media="print" onload="this.media='all'" />

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="{{asset('assets/frontend/css/fontawesome/all.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/owl.carousel.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/nice-select.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/plyr/plyr.css')}}">
        <link href="{{asset('assets/frontend/leafletjs/leaflet.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/css/plyr_cdn_dw.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/css/tagify.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/uploader/file-uploader.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/css/jquery-rbox.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/gallery/justifiedGallery.min.css')}}">
        <link href="{{asset('assets/frontend/toaster/toaster.css')}}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/summernote-0.8.18-dist/summernote-lite.min.css') }}" />
        <link rel="stylesheet" href="{{asset('assets/frontend/css/own.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
        <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <link rel="stylesheet" href="{{asset('assets/main/css/style.css')}}"/>
        <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
/>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    </noscript>
<style>
  .modal-content {
  transition: all 0.3s ease;
}

.modal-body .btn {
  font-weight: 600;
  letter-spacing: 0.5px;
}

.modal-body h5 {
  font-size: 1.3rem;
}
.category-card {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: #f04c3c;
    color: white;
    border-radius: 8px;
    font-weight: bold;
    transition: 0.3s;
    min-width: 120px;
    justify-content: center;
}
a{
    text-decoration: none;
}

/*.category-card:hover {*/
/*    background: #e63b2a;*/
/*}*/

.category-card img.category-icon {
    width: 25px;       /* 👈 Size fix */
    height: 30px;
    object-fit: contain;
}

.top-features li a{
    color: white !important;
    text-decoration: none !important;
}
.top-features li :hover{
    color: #a31907 !important;
}
.footer-column a:hover {
    color: #a31907;
}

 </style> 
</head>
<body>

 @include('frontend.header')
 


  <div class="hero-container">
    <video autoplay muted loop class="bg-video">
      <source src="{{asset('assets/main/images/backgroundvideo.mp4')}}" type="video/mp4" />
      Your browser does not support the video tag.
    </video>

    <div class="overlay"></div>

    <div class="content">
      <p class="tagline">🌍 Explore Without Limitations</p>
      <h1 class="title" style="font-size:36px !important;color:white !important">Discover what you are looking for <br> The City's Best <br> Events,Deals,Places,Stories & Manymore</h1>
    
      <input id="look" input="text" placeholder="What are u looking for?">
      <button id="btn1">Start Exploring</button>
      <button id="btn2">Post an activity</button>


    </div>
  </div>

                                          


                                                          <!-- category section -->
<div class="category">
   <!-- Popular Cities Section -->
 <section class="section">
  <h2 style="font-weight:700; font-size:24px">Popular Cities</h2>
  <div class="city-scroll">
    @foreach($top_cities as $city)
      <a href="{{ route('page.city', ['city_slug' => $city->city_slug]) }}" class="city-card-link">
        <div class="city-card" style="background-image: url('{{ Str::startsWith($city->city_image, 'http') ? $city->city_image : asset('storage/cities/logo/' . $city->city_image) }}');">
    {{ $city->city_name }}
</div>

      </a>
    @endforeach
  </div>
</section>


  <!-- Popular Categories Section -->
 <section class="section">
    <h2 style="font-weight:700; font-size:24px">Popular Categories</h2>
    <div class="grid-container category-grid">
        @foreach($top_categories as $category)
            <a href="{{ route('page.category', $category->category_slug) }}" class="category-card-link">
                <div class="category-card">
                    <img src="{{ $category->category_icon }}" alt="{{ $category->category_name }}" class="category-icon">
                    <span>{{ $category->category_name }}</span>
                </div>
            </a>
        @endforeach
    </div>
</section>



  

  <!--business banner-->
  <section class="business-banner">
  <div class="banner-top">
    <h2>
    Reach Local Customers & Grow Your Business —
    <button onclick="window.location.href='{{ route('login') }}'">
        Register Now <strong>It’s Free!</strong>
    </button>
</h2>

<div class="banner-action">
    <ul class="top-features">
        <li>
            @auth
                <a href="{{ route('pages.create') }}">➕ Add Business Listing</a>
            @else
                <a href="javascript:void(0);" onclick="showLoginPopup()">➕ Add Business Listing</a>
            @endauth
        </li>
        <li>
            @auth
                <a href="{{ route('pages.create.product') }}">🎁 Add Deals</a>
            @else
                <a href="javascript:void(0);" onclick="showLoginPopup()">🎁 Add Deals</a>
            @endauth
        </li>
        <li>
            @auth
                <a href="{{ route('events.create') }}">🎉 Add Events</a>
            @else
                <a href="javascript:void(0);" onclick="showLoginPopup()">🎉 Add Events</a>
            @endauth
        </li>
         <li>🤝 Work with Local Influencers</li>
    </ul>
</div>

    
</section>
</div>



<!--counter-->

<h2 style="color: #585757; display: flex; justify-content: center; align-items: center;" >Explore cityhangaround now</h2>
  <section class="stats-section" id="statsSection">
    <div class="stat">
      <h2 class="count" data-target="100000">0</h2>
      <p>Business Listed</p>
    </div>
    <div class="stat">
      <h2 class="count" data-target="2000">0</h2>
      <p>Happy Clients</p>
    </div>
    <div class="stat">
      <h2 class="count" data-target="1000">0</h2>
      <p>Campaign Complete</p>
    </div>
    <div class="stat">
      <h2 class="count" data-target="15">0</h2>
      <p>Years Experience</p>
    </div>
  </section>
   <script>
    const counters = document.querySelectorAll('.count');
    let hasAnimated = false;

    function animateCounter(counter, target, duration = 2000) {
      let startTimestamp = null;

      const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = timestamp - startTimestamp;
        const value = Math.min(Math.floor((progress / duration) * target), target);
        counter.innerText = value;
        if (value < target) {
          window.requestAnimationFrame(step);
        }
      };

      window.requestAnimationFrame(step);
    }

    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting && !hasAnimated) {
          counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            animateCounter(counter, target);
          });
          hasAnimated = true;
        }
      });
    }, { threshold: 0.5 });

    observer.observe(document.getElementById('statsSection'));
  </script>



  <!--client section-->

 <section class="clients-section">
    <h2><span> Our Clients</span></h2>
    <div class="swiper">
      <div class="swiper-wrapper">
        <div class="swiper-slide"><img src="{{asset('assets/main/images/client1.png')}}" alt="Client 1"></div>
        <div class="swiper-slide"><img src="{{asset('assets/main/images/client2.png')}}" alt="Client 2"></div>
        <div class="swiper-slide"><img src="{{asset('assets/main/images/client3.png')}}" alt="Client 3"></div>
        <div class="swiper-slide"><img src="{{asset('assets/main/images/client4.png')}}" alt="Client 4"></div>
        <div class="swiper-slide"><img src="{{asset('assets/main/images/client3.png')}}" alt="Client 5"></div>
        <div class="swiper-slide"><img src="{{asset('assets/main/images/client1.png')}}" alt="Client 6"></div>
        <div class="swiper-slide"><img src="{{asset('assets/main/images/client3.png')}}" alt="Client 3"></div>
        <div class="swiper-slide"><img src="{{asset('assets/main/images/client4.png')}}" alt="Client 4"></div>
        <div class="swiper-slide"><img src="{{asset('assets/main/images/client3.png')}}" alt="Client 5"></div>
        <div class="swiper-slide"><img src="{{asset('assets/main/images/client1.png')}}" alt="Client 6"></div>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </section>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
  <script>
    const swiper = new Swiper('.swiper', {
      slidesPerView: 4,
      spaceBetween: 20,
      loop: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
      breakpoints: {
        320: { slidesPerView: 2 },
        640: { slidesPerView: 3 },
        1024: { slidesPerView: 4 }
      }
    });
  </script>



                                                                 <!-- testimonals-->
 
  <div class="section-title">
  <small>Curious how people are using Cityhangaround</small>
  <h2>Hear what our customers are saying</h2>
</div>

<div class="scroll-wrapper">
  <div class="scroll-container" id="scrollContainer">

    <div class="review-card">
      <div class="video-thumb">
        <iframe width="100%" height="250"
            src="https://www.youtube.com/embed/1jveHrbRzmU"
            title="Customer Review" frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen>
        </iframe>
      </div>
    </div>

    <div class="review-card">
      <div class="video-thumb">
        <iframe width="100%" height="250"
          src="https://www.youtube.com/embed/ZTZUiUshPj0"
          title="Customer Review 2" frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
          allowfullscreen>
        </iframe>
      </div>
    </div>

    <div class="review-card">
      <div class="video-thumb">
        <iframe width="100%" height="250"
          src="https://www.youtube.com/embed/KavGoogIDng"
          title="Customer Review 3" frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
          allowfullscreen>
        </iframe>
      </div>
    </div>

    <div class="review-card">
      <div class="video-thumb">
        <iframe width="100%" height="250"
          src="https://www.youtube.com/embed/KSFi9LAjyAU"
          title="Customer Review 4" frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
          allowfullscreen>
        </iframe>
      </div>
    </div>

  </div>

  <div class="scroll-buttons">
    <button onclick="scrollLeft()"><i class="fas fa-chevron-left"></i></button>
    <button onclick="scrollRight()"><i class="fas fa-chevron-right"></i></button>
  </div>
</div>


                                                                   <!--footer-->

 <footer class="footer">
  <!-- About -->
  <div class="footer-column">
    <img src="{{asset('assets/main/images/logoheader.png')}}" alt="eKal Academy Logo" >
    <h3>About CityHangaround</h3>
    <p>Discover What’s Hot Around You – Events, Shops, Creators & More on CityHangaround!</p>
  </div>

  <!-- Platform -->
  <div class="footer-column">
    <h3>Platform</h3>
    <ul>
      <li><a href="https://www.cityhangaround.com/events">Trending Events</a></li>
      <li><a href="https://www.cityhangaround.com/pages">Trending Business</a></li>
      <li><a href="https://www.cityhangaround.com/deals">Trending Deals</a></li>
      <li><a href="https://www.cityhangaround.com/blogs">Trending Blogs</a></li>
      <li><a href="https://www.cityhangaround.com/groups">Trending Discussions</a></li>
    </ul>
  </div>

  <!-- Company -->
  <div class="footer-column">
    <h3>Company</h3>
    <ul>
      <li><a href="https://www.cityhangaround.com/pages/custom/contact-us ">Contact us</a></li>
      <li><a href="#">Partnership</a></li>
      <li><a href="#">Advertisement</a></li>
      <li><a href="https://www.cityhangaround.com/pages">Add Business</a></li>
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
      <div><img src="{{asset('assets/main/images/captacha.png')}}" alt="Captcha" style="margin-bottom: 10px;"/></div>
      <button type="submit">Submit</button>
    </form>
  </div>
</footer>

<!-- Footer Bottom -->
<!-- ✅ Footer Section -->
  <div class="footer-bottom">
    <p>Powered by CityHangaround</p>

    <div class="footer-links">
      <a href="https://www.cityhangaround.com/pages/custom/about-us">About Us</a>
      <a href="https://www.cityhangaround.com/pages/custom/disclaimer">Disclaimer</a>
      <a href="https://www.cityhangaround.com/pages/custom/privacy-policy">Privacy Policy</a>
      <a href="https://www.cityhangaround.com/pages/custom/contact-us">Contact Us</a>
      <a href="https://www.cityhangaround.com/pages/custom/advertise">Advertise</a>
      <a href="https://www.cityhangaround.com/pages/custom/terms-and-conditions">Terms & Conditions</a>
    </div>

    <div class="social-icons">
      <a href="https://www.facebook.com/Cityhangaround/" target="_blank" >
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="https://in.linkedin.com/company/cityhangaround" target="_blank" >
        <i class="fab fa-linkedin-in"></i>
      </a>
      <a href="https://www.instagram.com/cityhangaround/" target="_blank" >
        <i class="fab fa-instagram"></i>
      </a>
      <a href="https://www.youtube.com/c/Cityhangaround" target="_blank" >
        <i class="fab fa-youtube"></i>
      </a>
    </div>
  </div>


 
  <script src="{{asset('assets/main/js/script.js')}}" defer></script>

    <!-- Common modals -->
    @include('frontend.modal')

    <!--Javascript
    ========================================================-->
    <script src="{{asset('assets/frontend/js/bootstrap.bundle.min.js')}}" defer></script>
    <script src="{{asset('assets/frontend/js/owl.carousel.min.js')}}" defer></script>
    <script src="{{asset('assets/frontend/js/venobox.min.js')}}" defer></script>
    <script src="{{asset('assets/frontend/js/timepicker.min.js')}}" defer></script>
    <script src="{{asset('assets/frontend/js/jquery.datepicker.min.js')}}" defer></script>

   
    <script src="{{asset('assets/frontend/js/jquery.nice-select.min.js')}}" defer></script>
    <script src="{{asset('assets/frontend/plyr/plyr.js')}}" defer></script>
    <script src="{{asset('assets/frontend/jquery-form/jquery.form.min.js')}}" defer></script>

    <script src="{{asset('assets/frontend/leafletjs/leaflet.js')}}" defer></script>
    <script src="{{asset('assets/frontend/leafletjs/leaflet-search.js')}}" defer></script>
    <script src="{{asset('assets/frontend/toaster/toaster.js')}}" defer></script>

    <script src="{{asset('assets/frontend/gallery/jquery.justifiedGallery.min.js')}}" defer></script>

    <script src="{{asset('assets/frontend/js/jQuery.tagify.min.js')}}" defer></script>
    <script src="{{asset('assets/frontend/js/jquery-rbox.js')}}" defer></script>


    <script src="{{asset('assets/frontend/js/plyr_cdn_dw.js')}}" defer></script>

    <script src="{{ asset('js/share.js') }}" defer></script>

    <script src="{{asset('assets/frontend/uploader/file-uploader.js')}}" defer></script>
    
    <script src="{{ asset('assets/frontend/summernote-0.8.18-dist/summernote-lite.min.js') }}" defer></script>

    <script src="{{asset('assets/frontend/js/custom.js')}}" defer></script>

    <script src="{{asset('assets/frontend/js/initialize.js')}}" defer></script>
   

    @include('frontend.common_scripts')
    
    <script>
        "use strict";
        
        $(document).ready(function() {
            $('[name=tag]').tagify({
                duplicates :false
            });
        });
    </script>
    <script>
function showLoginPopup() {
    var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    loginModal.show();
}
</script>

</body>
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 rounded-4" style="backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.85); box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);">
      <div class="modal-body text-center p-4">
        <div class="mb-3">
          <i class="bi bi-person-circle text-primary" style="font-size: 3rem;"></i>
        </div>
        <h5 class="fw-bold mb-2">Hold Up!</h5>
        <p class="text-muted mb-4">You need to be logged in to continue.</p>

        <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2 rounded-pill shadow-sm">
          <i class="bi bi-box-arrow-in-right me-1"></i> Login
        </a>

        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 rounded-pill">
          <i class="bi bi-person-plus me-1"></i> Register
        </a>
      </div>
    </div>
  </div>
</div>

<script>
    document.getElementById("btn1").addEventListener("click", function () {
        var query = document.getElementById("look").value.trim();
        if (query !== "") {
            window.location.href = "{{ route('universal.search') }}?search=" + encodeURIComponent(query);
        }
    });
</script>

</html>