@php
    $menuItems = [
        ['route' => 'timeline', 'icon' => 'timeline-2.svg', 'label' => 'Buzz'],
        ['route' => 'profile', 'icon' => 'man-2.svg', 'label' => 'Profile'],
        ['route' => 'groups', 'icon' => 'group-2.svg', 'label' => 'Community'],
        ['route' => 'pages', 'icon' => 'page-2.svg', 'label' => 'Merchants'],
        ['route' => 'allproducts', 'icon' => 'marketplace-2.svg', 'label' => 'Deals'],
        ['route' => 'videos', 'icon' => 'video-2.svg', 'label' => 'Video and Shorts'],
        ['route' => 'event', 'icon' => 'events-2.svg', 'label' => 'Event'],
        ['route' => 'blogs', 'icon' => 'blogging-2.svg', 'label' => 'Blog'],
    ];
@endphp

<style>
    /* General Styles */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    /* Container style */

    .top-menu-wrap-outer {
        background-color: #fff;
        border-top: 1px solid #dedede !important;
        margin: 0 0 20px 0;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        position: relative;
        width: 100%;
        display: none;
        z-index: 5;
    }

    .offcanvas {
        position: fixed !important;
        z-index: 1055 !important;
        /* higher than anything else */
    }

    body.offcanvas-open .top-menu-wrap-outer {
        pointer-events: none;
    }


    .top-menu-wrap {

        padding: 0px 20px;
        display: block;
        width: 100%;

        position: static !important;
    }

    /* Menu styles */
    .top-menu-wrap ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        /* Default: horizontal layout */
        justify-content: space-around;
        z-index: 1;
        position: static !important;
    }

    .top-menu-wrap ul li {
        margin-right: 20px;
        /* Space between items */
    }

    .top-menu-wrap ul li a {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        text-decoration: none;
        color: #333;
        font-weight: 500;
        border-radius: 0px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .top-menu-wrap ul li a img {
        width: 20px;
        height: 20px;
        margin-right: 8px;
        border-radius: 50%;
    }


    /* Hover and active link styles */
    .top-menu-wrap ul li a:hover,
    .top-menu-wrap ul li.active a {
        background: none;
        color: #FF4939;
    }

    /* Mobile Styles */
    @media (max-width: 768px) {
        .top-menu-wrap ul {
            flex-direction: row;
            /* Vertical layout on mobile */
            align-items: flex-start;

            background-color: #f8f9fa;
            position: relative;
            margin-top: 10px;

            white-space: nowrap;
        }

        .top-menu-wrap ul li {
            margin-right: 15px;
            margin-bottom: 0;

            /* Space between items in mobile view */

            flex: 0 0 auto;
            width: 100%;
            /* Full width for touch-friendly design */
        }

        .top-menu-wrap ul li a {
            padding: 10px 20px;
            width: 100%;
        }
    }
</style>
<div class="top-menu-wrap-outer">
    <div class="top-menu-wrap">
        <ul>
            @foreach($menuItems as $item)
                <li class="{{ request()->routeIs($item['route']) ? 'act' : '' }}">
                    <a href="{{ route($item['route']) }}">
                        <img src="{{ asset('storage/images/' . $item['icon']) }}" alt="{{ $item['label'] }}">
                        <span>{{ get_phrase($item['label']) }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>