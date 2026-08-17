<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    {!! SEO::generate() !!}
    <title>{{ $system_name }}</title>

    <link rel="shortcut icon" href="{{ get_system_logo_favicon($system_favicon,'favicon') }}" />
    <!-- CSRF Token for ajax for submission -->
    <meta name="csrf_token" content="{{ csrf_token() }}" />

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="shortcut icon" href="{{ get_system_logo_favicon($system_favicon,'favicon') }}" />

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="{{asset('assets/frontend/css/fontawesome/all.min.css')}}">
    <!-- CSS Library -->

    <link rel="stylesheet" href="{{asset('assets/frontend/css/owl.carousel.min.css')}}">

    <!-- Style css -->
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
    
    <script src="https://cdn.tailwindcss.com" defer></script>
    <script src="{{asset('assets/frontend/js/jquery-3.6.0.min.js')}}"></script>
    

<!-- Bootstrapâ€‘Select (sirf ye, extra bootstrap CSS nahi) -->
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/css/bootstrap-select.min.css">
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
        <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/css/bootstrap-select.min.css">
    </noscript>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/js/bootstrap-select.min.js" defer></script>

    
   <style>
    @media (max-width: 768px) {
        .desktop-view { display: none; }
    }
    @media (min-width: 769px) {
        .mobile-view { display: none; }
    }

    .text-primary-brand { color: #ff4939; }
    .logo-color { color: #ff4939; }

    /* Trending sidebar cards */
    .trending-sidebar .trending-card {
        background: #fff;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #f0f0f0;
        margin-bottom: 16px;
    }
    .trending-sidebar .trending-card .trending-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid #f0f0f0;
    }
    .trending-sidebar .trending-card .trending-header h2 {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }
    .trending-sidebar .trending-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px;
        border-radius: 8px;
        transition: all 0.2s ease;
        margin-bottom: 4px;
        text-decoration: none !important;
        color: inherit !important;
    }
    .trending-sidebar .trending-item:hover {
        background: #f8f9fa;
        transform: translateX(4px);
    }
    .trending-sidebar .trending-item img {
        width: 56px;
        height: 56px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
    }
    .trending-sidebar .trending-item .item-info h6 {
        font-size: 13px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 2px;
        line-height: 1.3;
    }
    .trending-sidebar .trending-item .item-info small {
        font-size: 11px;
        color: #888;
    }
    </style>

    @include('frontend.partials.page_shell_head')
</head>

<body>
    @include('frontend.partials.page_shell_loader')
    @php $user_info = Auth()->user() @endphp
    
    @include('frontend.header')

    <!-- Main Start -->
    <main class="main my-4">
        <div class="top-menu-wrap-outer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 desktop-view"></div>
                    <div class="mobile-view">
                        @include('frontend.left_navigation')
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <!-- Left Sidebar (2 columns) -->
                <div class="col-lg-2 order-2 order-lg-1">
                    @include('frontend.left_blog_filter')
                </div>

                <!-- Main Blog Content (7 columns) -->
                <div class="col-lg-7 col-sm-12 order-3 order-lg-2">
                    @include($view_path)
                </div>

                <!-- Right Sidebar (3 columns) -->
                <div class="col-lg-3 order-1 order-lg-3">
                    <div class="trending-sidebar" style="position: sticky; top: 20px;">
                        <!-- Standard Right Sidebar (Sponsors, Featured Products, Blogs) -->
                        @include('frontend.right_sidebar')
                    </div>
                </div>
            </div> <!-- row end -->
        </div> <!-- container end -->
    </main>
    <!-- Main End -->

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
    
    @include('frontend.toaster')

    @include('frontend.initialize')

    <script>
        "use strict";
        $(document).ready(function() {
            $('[name=tag]').tagify({
                duplicates: false
            });
        });
    </script>
    <script>
        $(function () {
            $('.selectpicker').selectpicker();
        });
    </script>

    @include('frontend.partials.page_shell_script')
</body>

</html>