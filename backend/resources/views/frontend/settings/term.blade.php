@php
    $noimage = $noimage ?? request()->has('noimage');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/bootstrap.min.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}">
    </noscript>
    <style>
        body { 
            background-color: #ffffff !important; 
            margin: 0; 
            padding: 0; 
            overflow-y: auto !important; 
            min-height: 100vh;
        }
        .term-container { 
            padding: {{ $noimage ? '15px' : '40px' }}; 
            background: #fff;
        }
        @if($noimage)
        .header-title { display: none !important; }
        @endif
        /* Custom scrollbar for better visibility */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
    </style>
</head>
<body class="bg-white">
    <main class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="term-container">
                        @if(!$noimage)
                            <h3 class="header-title mb-4">{{ get_phrase('Terms And Condition') }}</h3>
                        @endif
                        <div class="content-body">
                            @php echo script_checker($term, false); @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/frontend/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/bootstrap.bundle.min.js') }}" defer></script>
</body>
</html>

