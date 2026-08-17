<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Creativeitem Software Installation" />
	<meta name="author" content="Creativeitem" />
	<title>{{ __('Installation').' | '.__('Sociopro') }}</title>
	
	<!-- CSRF Token for ajax for submission -->
    <meta name="csrf_token" content="{{ csrf_token() }}" />

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="shortcut icon" href="{{asset('storage/logo/favicon/favicon.png')}}" />

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

    <link href="{{asset('assets/frontend/uploader/jquery.uploader.css')}}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{asset('assets/frontend/css/jquery-rbox.css')}}" rel="stylesheet" media="print" onload="this.media='all'">

   <link rel="stylesheet" href="{{ asset('assets/frontend/summernote-0.8.18-dist/summernote-lite.min.css') }}" media="print" onload="this.media='all'">

    <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap.min.css')}}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/style.css')}}" media="print" onload="this.media='all'">
    <link href="{{asset('assets/frontend/toaster/toaster.css')}}" rel="stylesheet" media="print" onload="this.media='all'">

    <link rel="stylesheet" href="{{asset('assets/frontend/gallery/justifiedGallery.min.css')}}" media="print" onload="this.media='all'">

    <link rel="stylesheet" href="{{asset('assets/frontend/css/own.css')}}" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="{{asset('assets/frontend/css/fontawesome/all.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/owl.carousel.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/nice-select.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/plyr/plyr.css')}}">
        <link href="{{asset('assets/frontend/leafletjs/leaflet.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/css/plyr_cdn_dw.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/css/tagify.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/uploader/jquery.uploader.css')}}" rel="stylesheet">
        <link href="{{asset('assets/frontend/css/jquery-rbox.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/frontend/summernote-0.8.18-dist/summernote-lite.min.css') }}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/style.css')}}">
        <link href="{{asset('assets/frontend/toaster/toaster.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('assets/frontend/gallery/justifiedGallery.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/own.css')}}">
    </noscript>
    
    <script src="{{asset('assets/frontend/js/jquery-3.6.0.min.js')}}"></script>


</head>
<body class="page-body">

<div class="page-container horizontal-menu">

	<header class="navbar navbar-fixed-top ins-one bg-dark">
		<div class="container">
			<div class="navbar-inner">
				<!-- logo -->
				<div class="navbar-brand">
					<a href="#">
						<img width="130px" src="{{ asset('storage') }}/logo/light/logo.png" alt="">
					</a>
					<span class="logo_name ms-4">{{ __('Installation') }}</span>
				</div>
			</div>
		</div>
	</header>
	<div class="main_content py-4">
		@yield('content')
	</div>

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

    <script src="{{asset('assets/frontend/uploader/jquery.uploader.min.js')}}" defer></script>

    <script src="{{ asset('assets/frontend/summernote-0.8.18-dist/summernote-lite.min.js') }}" defer></script>


</body>
</html>