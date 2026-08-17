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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </noscript>
    <script src="{{asset('assets/frontend/js/jquery-3.6.0.min.js')}}"></script>
    
   <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-P88PMC');</script>
<!-- End Google Tag Manager -->

    @include('frontend.partials.page_shell_head')
</head>

<body>
    @include('frontend.partials.page_shell_loader')

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P88PMC"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    @php $user_info = Auth()->user() @endphp
    
    @include('frontend.header')

    <div class="top-menu-wrap-outer ">
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
    <!-- Left sidebar -->
    <div class="col-12 col-md-3 col-lg-2 mb-3 mb-lg-0">
        @include('frontend.left_qna_filter_index')
    </div>

    <!-- Main content -->
    <div class="col-12 col-md-6 col-lg-7 order-2 order-lg-2 mb-3 mb-lg-0">
        @include('frontend.qna.tab_page')
    </div>

    <!-- Right sidebar -->
    <div class="col-12 col-md-3 col-lg-3 order-3 order-lg-3">
        @include('frontend.right_sidebar')
    </div>
</div>


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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                duplicates :false
            });
        });
    </script>

    
    @include('frontend.partials.page_shell_script')
</body>

</html>


