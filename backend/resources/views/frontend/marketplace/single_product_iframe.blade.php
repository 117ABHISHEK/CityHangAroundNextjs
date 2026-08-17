<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    @php
        $system_name = \App\Models\Setting::where('type', 'system_name')->value('description');
        $system_favicon = \App\Models\Setting::where('type', 'system_fav_icon')->value('description');
    @endphp
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
    <link rel="stylesheet" href="{{asset('assets/frontend/css/own.css')}}">
    <link href="{{asset('assets/frontend/leafletjs/leaflet.css')}}" rel="stylesheet">
    <link href="{{asset('assets/frontend/toaster/toaster.css')}}" rel="stylesheet">

    <link href="{{asset('assets/frontend/uploader/jquery.uploader.css')}}" rel="stylesheet">
    <noscript>
        <link rel="stylesheet" href="{{asset('assets/frontend/css/fontawesome/all.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/owl.carousel.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/nice-select.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/plyr/plyr.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/own.css')}}">
        <link href="{{asset('assets/frontend/leafletjs/leaflet.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/toaster/toaster.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/uploader/jquery.uploader.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/style.css')}}">
        <link href="{{asset('assets/frontend/uploader/jquery.uploader.css')}}" rel="stylesheet">
    </noscript>

    <link rel="stylesheet" href="{{asset('assets/frontend/css/style.css')}}">

    <link href="{{asset('assets/frontend/uploader/jquery.uploader.css')}}" rel="stylesheet">
    <noscript>
        <link rel="stylesheet" href="{{asset('assets/frontend/css/fontawesome/all.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/owl.carousel.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/nice-select.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/plyr/plyr.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/own.css')}}">
        <link href="{{asset('assets/frontend/leafletjs/leaflet.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/toaster/toaster.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/uploader/jquery.uploader.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/style.css')}}">
        <link href="{{asset('assets/frontend/uploader/jquery.uploader.css')}}" rel="stylesheet">
    </noscript>

    <script src="{{asset('assets/frontend/js/jquery-3.6.0.min.js')}}"></script>
    
   

    @include('frontend.partials.page_shell_head')
</head>

<body>
    @include('frontend.partials.page_shell_loader')
    @php $user_info = Auth()->user() @endphp
    
 

    <!-- Main Start -->
    <main class="main">
        @include('frontend.marketplace.single_product')
    </main>
    <!-- Main End -->



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

    <script src="{{asset('assets/frontend/js/foundation.min.js')}}" defer></script>
    <script src="{{asset('assets/frontend/js/modernizr.min.js')}}" defer></script>


    <script src="{{asset('assets/frontend/uploader/jquery.uploader.min.js')}}" defer></script>

    
    <script src="{{asset('assets/frontend/js/initialize.js')}}" defer></script>

    <script src="{{asset('assets/frontend/js/custom.js')}}" defer></script>

    @include('frontend.initialize')
    
    @include('frontend.partials.page_shell_script')
</body>

</html>