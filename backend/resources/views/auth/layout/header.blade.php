<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Prevent caching to fix CSRF 419 errors -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    @php
        $system_name = \App\Models\Setting::where('type', 'system_name')->value('description');
        $system_favicon = \App\Models\Setting::where('type', 'system_fav_icon')->value('description');
        $display_title = isset($auth_title) ? $auth_title : 'Login';
    @endphp
    <title>{{ $display_title }} – {{ $system_name }}</title>
    <meta name="description" content="{{ $meta_description ?? 'Login to ' . $system_name . ' to discover local businesses, deals, events and more.' }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="shortcut icon" href="{{ get_system_logo_favicon($system_favicon,'favicon') }}">

    <!-- Preconnects -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/bootstrap.min.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Premium Custom Styles -->
    <style>
        :root {
            --primary: #ff4939;
            --primary-hover: #e03d2f;
            --primary-glow: rgba(255, 73, 57, 0.15);
            --bg: #fafbfe;
            --card-bg: rgba(255, 255, 255, 0.9);
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --border-hover: #cbd5e1;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Mesh Background */
        .mesh-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            overflow: hidden;
            background: radial-gradient(at 0% 0%, rgba(255, 73, 57, 0.04) 0px, transparent 50%),
                        radial-gradient(at 100% 0%, rgba(255, 140, 0, 0.04) 0px, transparent 50%),
                        radial-gradient(at 50% 100%, rgba(255, 73, 57, 0.02) 0px, transparent 50%);
        }

        .mesh-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.5;
            z-index: -1;
            animation: float 20s infinite alternate ease-in-out;
        }

        .mesh-blob-1 {
            top: -10%;
            left: -10%;
            width: 45vw;
            height: 45vw;
            background: radial-gradient(circle, rgba(255, 73, 57, 0.12) 0%, rgba(255, 73, 57, 0) 70%);
        }

        .mesh-blob-2 {
            bottom: -15%;
            right: -10%;
            width: 55vw;
            height: 55vw;
            background: radial-gradient(circle, rgba(255, 140, 0, 0.08) 0%, rgba(255, 140, 0, 0) 70%);
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(5vw, 5vh) scale(1.08); }
        }

        /* Glassmorphic Header Navigation */
        .header {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 14px 0;
            transition: all 0.3s ease;
        }

        .logo-branding img {
            height: 38px;
            width: auto;
            max-width: 180px;
            transition: transform 0.2s ease;
        }

        .logo-branding img:hover {
            transform: scale(1.02);
        }

        .login-btns {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .login-btns a.btn {
            font-family: 'Outfit', sans-serif;
            text-decoration: none;
            padding: 8px 20px;
            border-radius: 24px;
            font-weight: 500;
            font-size: 14.5px;
            color: var(--text-muted);
            border: 1px solid transparent;
            background: transparent;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .login-btns a.btn:hover {
            color: var(--text-main);
            background: rgba(0, 0, 0, 0.03);
        }

        .login-btns a.btn.active {
            background: var(--primary);
            color: #fff !important;
            border-color: var(--primary);
            box-shadow: 0 4px 14px var(--primary-glow);
        }

        .login-btns a.btn.active:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            box-shadow: 0 6px 18px rgba(255, 73, 57, 0.25);
            transform: translateY(-1px);
        }

        /* Global Form Card Layout Adjustments */
        .main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 991px) {
            .header {
                padding: 10px 0;
            }
            .logo-branding img {
                height: 32px;
            }
            .login-btns a.btn {
                padding: 6px 16px;
                font-size: 13.5px;
            }
        }
    </style>
</head>

<body>

<!-- Background Mesh Graphics -->
<div class="mesh-bg">
    <div class="mesh-blob mesh-blob-1"></div>
    <div class="mesh-blob mesh-blob-2"></div>
</div>

@php $system_light_logo = \App\Models\Setting::where('type', 'system_light_logo')->value('description'); @endphp

<!-- header -->
<header class="header py-3">
    <nav class="navigation">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto">
                    <div class="logo-branding">
                        <a class="navbar-brand" href="/">
                            <img src="{{ get_system_logo_favicon($system_light_logo,'light') }}" height="38" alt="logo" />
                        </a>
                    </div>
                </div>

                <div class="col-auto">
                    <div class="login-btns">
                        <a href="{{ route('login') }}" class="btn @if(Route::currentRouteName() == 'login') active @endif">{{  __('Login') }}</a>
                        @if(get_settings('public_signup') == 1)
                            <a href="{{ route('register') }}" class="btn @if(Route::currentRouteName() == 'register') active @endif">{{ __('Sign up')  }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
<!-- Header End -->
