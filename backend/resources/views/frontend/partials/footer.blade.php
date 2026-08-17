<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title','City Hang Arounds')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'" />

    <link rel="stylesheet" href="{{ asset('assets/frontend/css/header_new.css') }}?v={{ time() }}" media="print" onload="this.media='all'">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" media="print" onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/header_new.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/custom_header.css') }}?v={{ time() }}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/landingpage-new.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/landingpage-new-hero.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    </noscript>
    <link rel="icon" type="image/png" href="https://cdn.cityhangaround.com/file/cityhangaround/uploads/wYMNRNks5mOFSD2V1eOkZbjeH0m3dhFpyT79tsHW.png">

    <!--<link rel="stylesheet" href="{{ asset('assets/frontend/css/custom_header.css') }}?v={{ time() }}" media="print" onload="this.media='all'">-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet" media="print" onload="this.media='all'" />
    <!-- Include Select2 JavaScript -->
    
    
     <!--  Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" media="print" onload="this.media='all'" />
    
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/landingpage-new.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/landingpage-new-hero.css') }}" media="print" onload="this.media='all'">

    <!-- Bootstrap JavaScript -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'" /> -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" media="print" onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/header_new.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/custom_header.css') }}?v={{ time() }}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/landingpage-new.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/landingpage-new-hero.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    </noscript>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
</head>
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
        </style>
        <body>
{{-- resources/views/frontend/partials/footer.blade.php --}}
<footer class="footer">
    {{-- About --}}
    <div class="footer-column">
        <img src="https://cdn.cityhangaround.com/file/cityhangaround/uploads/wYMNRNks5mOFSD2V1eOkZbjeH0m3dhFpyT79tsHW.png"
             alt="CityHangaround Logo" />
        <h3>About CityHangaround</h3>
        <p>Discover What’s Hot Around You – Events, Shops, Creators & More on CityHangaround!</p>
    </div>

    {{-- Platform --}}
    <div class="footer-column">
        <h3>Platform</h3>
        <ul>
            <li><a href="/event/all">Trending Events</a></li>
            <li><a href="/home">Trending Business</a></li>
            <li><a href="/product/filter">Trending Deals</a></li>
            <li><a href="/blogs">Trending Blogs</a></li>
            <li><a href="/groups">Trending Discussions</a></li>
        </ul>
    </div>

    {{-- Company --}}
    <div class="footer-column">
        <h3>Company</h3>
        <ul>
            <li><a href="{{ url('pages/custom/contact-us') }}">Contact us</a></li>
            <li><a href="{{ url('pages/custom/partnership') }}">Partnership</a></li>
            <li><a href="{{ url('pages/custom/advertise') }}">Advertisement</a></li>
            <li><a href="/admin/page/create">Add Business</a></li>
        </ul>
    </div>

    {{-- Reach Us --}}
    <div class="footer-column">
        <h3>Reach Us</h3>
        <form class="contact-form" onsubmit="return validateCaptcha()">
            <input type="text" placeholder="Name" required/>
            <input type="tel" placeholder="Phone no." required/>
            <input type="email" placeholder="Email id" required/>
            <input type="text" placeholder="City" required/>
            <textarea placeholder="Query" rows="3" required></textarea>
            <input type="text" id="captchaInput" placeholder="Enter Captcha" required/>
            <div>
                <img src="{{ asset('assets/frontend/images/captacha.png') }}" alt="Captcha"
                     style="margin-bottom: 10px"/>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
</footer>

<div class="footer-bottom">
    <p>Powered by Cityhangaround</p>
    <div class="footer-links">
        <a href="{{ url('pages/custom/about-us') }}" target="_blank">About Us</a>
        <a href="{{ url('pages/custom/disclaimer') }}" target="_blank">Disclaimer</a>
        <a href="{{ url('pages/custom/privacy-policy') }}" target="_blank">Privacy Policy</a>
        <a href="{{ url('pages/custom/contact-us') }}" target="_blank">Contact Us</a>
        <a href="{{ url('pages/custom/advertise') }}" target="_blank">Advertise</a>
        <a href="{{ url('pages/custom/terms-and-conditions') }}" target="_blank">Terms & Conditions</a>
    </div>
    <div class="social-icons">
        <a href="https://www.facebook.com/Cityhangaround/" target="_blank"><i class="fab fa-facebook-f"></i></a>
        <a href="https://in.linkedin.com/company/cityhangaround" target="_blank"><i class="fab fa-linkedin-in"></i></a>
        <a href="https://www.instagram.com/cityhangaround/" target="_blank"><i class="fab fa-instagram"></i></a>
        <a href="https://www.youtube.com/c/Cityhangaround" target="_blank"><i class="fab fa-youtube"></i></a>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/frontend/js/landingpage-new.js') }}" defer></script>
{{-- add validateCaptcha() here if not already in JS --}}
@endpush
    </body>
    </html>
