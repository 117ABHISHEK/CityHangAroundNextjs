<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    
    {!! SEO::generate() !!}
    <title>{{ $system_name ?? 'Cityhangaround' }}</title>

    <!-- CSRF Token for ajax for submission -->
    <meta name="csrf_token" content="{{ csrf_token() }}" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="shortcut icon" href="{{ get_system_logo_favicon($system_favicon ?? '', 'favicon') }}" />

    <!-- Style css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/fontawesome/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/plyr/plyr.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/leafletjs/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/plyr_cdn_dw.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/tagify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/uploader/file-uploader.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/jquery-rbox.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/gallery/justifiedGallery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/toaster/toaster.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/summernote-0.8.18-dist/summernote-lite.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/own.css') }}?v=1.5">
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/fontawesome/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/nice-select.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/plyr/plyr.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/leafletjs/leaflet.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/plyr_cdn_dw.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/tagify.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/uploader/file-uploader.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/jquery-rbox.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/gallery/justifiedGallery.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/toaster/toaster.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/summernote-0.8.18-dist/summernote-lite.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/own.css') }}">
    </noscript>

    <script src="{{ asset('assets/frontend/js/jquery-3.6.0.min.js') }}"></script>

    <style>
        @media (max-width: 768px) { .desktop-view { display: none; } }
        @media (min-width: 769px) { .mobile-view { display: none; } }
    </style>
    @include('frontend.partials.page_shell_head')
</head>

@php $render_start = microtime(true); @endphp
<body>
    @include('frontend.partials.page_shell_loader')
    @include('frontend.header')
    @php echo "<!-- PROFILE: after header = " . number_format((microtime(true) - $render_start) * 1000, 2) . "ms -->\n"; @endphp

    <!-- Main Start -->
    <main class="main my-4">
        <div class="top-menu-wrap-outer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 desktop-view">
                        @include('frontend.top_navigation')
                    </div>
                    <div class="mobile-view">
                        @include('frontend.left_navigation')
                    </div>
                </div>
            </div>
        </div>
        @php echo "<!-- PROFILE: after navigation = " . number_format((microtime(true) - $render_start) * 1000, 2) . "ms -->\n"; @endphp

        <div class="container">
            <div class="row">
                <div class="col-lg-2">
                    @include('frontend.left_product_filter')
                </div>
                @php echo "<!-- PROFILE: after left_product_filter = " . number_format((microtime(true) - $render_start) * 1000, 2) . "ms -->\n"; @endphp
                
                <!-- Content Section Start -->
                <div class="col-lg-7 col-sm-12 order-3 order-lg-2">
                    @include($view_path)
                </div>
                @php echo "<!-- PROFILE: after view_path ($view_path) = " . number_format((microtime(true) - $render_start) * 1000, 2) . "ms -->\n"; @endphp
                
                <div class="col-lg-3 order-2 order-lg-3">
                    @include('frontend.right_sidebar')
                </div>
                @php echo "<!-- PROFILE: after right_sidebar = " . number_format((microtime(true) - $render_start) * 1000, 2) . "ms -->\n"; @endphp
            </div> 
        </div> 
    </main>

    @include('frontend.modal')
    @php echo "<!-- PROFILE: after modal = " . number_format((microtime(true) - $render_start) * 1000, 2) . "ms -->\n"; @endphp

    <!-- Javascript -->
    <script src="{{ asset('assets/frontend/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/js/owl.carousel.min.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/js/venobox.min.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/js/timepicker.min.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/js/jquery.datepicker.min.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/js/jquery.nice-select.min.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/plyr/plyr.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/jquery-form/jquery.form.min.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/leafletjs/leaflet.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/leafletjs/leaflet-search.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/toaster/toaster.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/gallery/jquery.justifiedGallery.min.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/js/jQuery.tagify.min.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/js/jquery-rbox.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/js/plyr_cdn_dw.js') }}" defer></script>
    <script src="{{ asset('js/share.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/uploader/file-uploader.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/summernote-0.8.18-dist/summernote-lite.min.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/js/custom.js') }}" defer></script>

    @include('frontend.common_scripts')
    @include('frontend.toaster')
    @include('frontend.initialize')

    <script>
        "use strict";
        $(document).ready(function() {
            if ($.fn.tagify) {
                $('[name=tag]').tagify({ duplicates: false });
            }
        });
    </script>
    @include('frontend.partials.page_shell_script')
</body>
</html>