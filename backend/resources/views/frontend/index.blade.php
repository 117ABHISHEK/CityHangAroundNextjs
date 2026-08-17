<!DOCTYPE html>
<html lang="en" style="overflow-x:hidden;max-width:100vw">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    {!! SEO::generate() !!}
    
    <!-- CSRF Token for ajax for submission -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="csrf_token" content="{{ csrf_token() }}" />
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
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="shortcut icon" href="{{ get_system_logo_favicon($system_favicon,'favicon') }}" />

    <!-- Preconnects -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    <!-- Deferred Stylesheets (non-render-blocking) -->
    <link rel="stylesheet" href="{{asset('assets/frontend/css/fontawesome/all.min.css')}}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/owl.carousel.min.css')}}" media="print" onload="this.media='all'">
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
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/summernote-0.8.18-dist/summernote-lite.min.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/assets/frontend/css/own.css?v=2.0.5" media="print" onload="this.media='all'">
    <!-- Select2 & SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet" href="{{asset('assets/frontend/css/fontawesome/all.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/owl.carousel.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/nice-select.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/plyr/plyr.css')}}">
        <link href="{{asset('assets/frontend/leafletjs/leaflet.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/css/plyr_cdn_dw.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/css/tagify.css')}}">
        <link href="{{asset('assets/frontend/uploader/file-uploader.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/css/jquery-rbox.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/gallery/justifiedGallery.min.css')}}">
        <link href="{{asset('assets/frontend/toaster/toaster.css')}}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/summernote-0.8.18-dist/summernote-lite.min.css') }}" />
        <link rel="stylesheet" href="/assets/frontend/css/own.css?v=2.0.5">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    </noscript>
    
    <noscript>
        <link rel="stylesheet" href="{{asset('assets/frontend/css/fontawesome/all.min.css')}}" media="print" onload="this.media='all'">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/owl.carousel.min.css')}}" media="print" onload="this.media='all'">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/nice-select.css')}}" media="print" onload="this.media='all'">
        <link rel="stylesheet" href="{{asset('assets/frontend/plyr/plyr.css')}}" media="print" onload="this.media='all'">
        <link href="{{asset('assets/frontend/leafletjs/leaflet.css')}}" rel="stylesheet" media="print" onload="this.media='all'">
        <link href="{{asset('assets/frontend/css/plyr_cdn_dw.css')}}" rel="stylesheet" media="print" onload="this.media='all'">
        <link href="{{asset('assets/frontend/css/tagify.css')}}" media="print" onload="this.media='all'">
        <link href="{{asset('assets/frontend/uploader/file-uploader.css')}}" rel="stylesheet" media="print" onload="this.media='all'">
        <link href="{{asset('assets/frontend/css/jquery-rbox.css')}}" rel="stylesheet" media="print" onload="this.media='all'">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap.min.css')}}" media="print" onload="this.media='all'">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/style.css')}}" media="print" onload="this.media='all'">
        <link rel="stylesheet" href="{{asset('assets/frontend/gallery/justifiedGallery.min.css')}}" media="print" onload="this.media='all'">
        <link href="{{asset('assets/frontend/toaster/toaster.css')}}" rel="stylesheet" media="print" onload="this.media='all'">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/summernote-0.8.18-dist/summernote-lite.min.css') }}" media="print" onload="this.media='all'" />
        <link rel="stylesheet" href="/assets/frontend/css/own.css?v=2.0.5" media="print" onload="this.media='all'">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet" href="{{asset('assets/frontend/css/fontawesome/all.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/owl.carousel.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/nice-select.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/plyr/plyr.css')}}">
        <link href="{{asset('assets/frontend/leafletjs/leaflet.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/css/plyr_cdn_dw.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/css/tagify.css')}}">
        <link href="{{asset('assets/frontend/uploader/file-uploader.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/css/jquery-rbox.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/gallery/justifiedGallery.min.css')}}">
        <link href="{{asset('assets/frontend/toaster/toaster.css')}}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/summernote-0.8.18-dist/summernote-lite.min.css') }}" />
        <link rel="stylesheet" href="/assets/frontend/css/own.css?v=2.0.5">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    </noscript>
    </noscript>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    
    <script src="{{asset('assets/frontend/js/jquery-3.6.0.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" defer></script>
    
    <!-- Google Tag Manager -->
    <script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});
    var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
    j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
    f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-P88PMC');
    </script>
    <!-- Comment reaction capsule overlap & empty capsule fixes -->
    <style>
        .comment-wrap .comment-content a.comment-reaction-capsule:not(:has(.reaction-icon)) {
            display: none !important;
        }
        .comment-wrap .comment-content {
            margin-bottom: 16px !important;
        }
    </style>
</head>

<body style="overflow-x:hidden;max-width:100vw">
<style>
/* === CRITICAL MOBILE FIXES for index.blade.php ===
   Loaded inline so they apply before any deferred CSS */
@media (max-width: 767px) {
    html, body {
        overflow-x: hidden !important;
        max-width: 100vw !important;
        width: 100% !important;
    }
    body { padding-bottom: 68px !important; }
    /* Hide right sidebar column on mobile */
    .col-lg-3.order-2.order-lg-3 { display: none !important; }
    /* Make main content full width on mobile */
    .col-lg-9.col-sm-12 { width: 100% !important; max-width: 100% !important; flex: 0 0 100% !important; padding: 0 !important; }
    /* Fix row negative margins causing overflow */
    .row { margin-left: 0 !important; margin-right: 0 !important; }
    /* Container full width */
    .container { max-width: 100% !important; padding-left: 0 !important; padding-right: 0 !important; }
    /* Old mobile nav - hide the desktop nav on mobile */
    .desktop-view { display: none !important; }
}
</style>

<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P88PMC"
    height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>



@include('frontend.header')

<div class="top-menu-wrap-outer">
    <div class="">
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

<!-- Main Start -->
<main class="main my-4">
    <div class="container">
        <div class="row">
            
            <div class="col-lg-9 col-sm-12 order-3 order-lg-2">
                @include($view_path)
            </div>

            <div class="col-lg-3 order-2 order-lg-3">
                @include('frontend.right_sidebar')
            </div>

        </div>
    </div>
</main>
<!-- Main End -->

<!-- Common modals -->
@include('frontend.modal')
{{-- enquiry_modal is already included in frontend.header (line 47) --}}

<!-- Javascript -->
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

{{-- Select2 JS is already loaded in header.blade.php (select2@4.0.13). --}}
{{-- Enquiry modal Select2 init is handled in global-scripts.blade.php via shown.bs.modal. --}}

<script>
"use strict";

$(document).ready(function() {
    $('[name=tag]').tagify({
        duplicates: false
    });
});
</script>

</body>
</html>