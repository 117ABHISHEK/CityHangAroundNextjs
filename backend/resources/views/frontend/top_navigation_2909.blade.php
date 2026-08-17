
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
            border-top:1px solid #dedede!important;
            margin: 0 0 20px 0 ;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
            width: 100%;
    z-index:5 ;
        }
.offcanvas {
    position: fixed !important;
    z-index: 1055 !important; /* higher than anything else */
}
body.offcanvas-open .top-menu-wrap-outer
{
    pointer-events:none;
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
            display: flex; /* Default: horizontal layout */
            justify-content: space-around;
            z-index: 1;
            position: static !important;
        }

        .top-menu-wrap ul li {
            margin-right: 20px; /* Space between items */
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
                flex-direction: row; /* Vertical layout on mobile */
                align-items: flex-start;
                
                background-color: #f8f9fa;
                position: relative;
                margin-top: 10px;
                
                white-space: nowrap;
            }

            .top-menu-wrap ul li {
                margin-right:15px;
                margin-bottom: 0;
                
                /* Space between items in mobile view */
                
                flex: 0 0 auto;
                width: 100%; /* Full width for touch-friendly design */
            }

            .top-menu-wrap ul li a {
                padding: 10px 20px;
                width: 100%;
            }
        }
    </style>
<div class="top-menu-wrap-outer">
    <div class="top-menu-wrap">
    <!-- Menu -->
    <ul>
        <li class="{{ request()->routeIs('timeline') ? 'act' : '' }}">
            <a href="{{ route('timeline') }}">
                <img src="{{ asset('storage/images/timeline-2.svg') }}" alt="Timeline">
                <span>{{ get_phrase('Buzz') }}</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('profile') ? 'act' : '' }}">
            <a href="{{ route('profile') }}">
                <img src="{{ asset('storage/images/man-2.svg') }}" alt="Profile">
                <span>{{ get_phrase('Profile') }}</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('groups') ? 'act' : '' }}">
            <a href="{{ route('groups') }}">
                <img src="{{ asset('storage/images/group-2.svg') }}" alt="Group">
                <span>{{ get_phrase('Community') }}</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('pages') ? 'act' : '' }}">
            <a href="{{ route('pages') }}">
                <img src="{{ asset('storage/images/page-2.svg') }}" alt="Page">
                <span>{{ get_phrase('Merchants') }}</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('allproducts') ? 'act' : '' }}">
            <a href="{{ route('allproducts') }}">
                <img src="{{ asset('storage/images/marketplace-2.svg') }}" alt="Marketplace">
                <span>{{ get_phrase('Deals') }}</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('videos') ? 'act' : '' }}">
            <a href="{{ route('videos') }}">
                <img src="{{ asset('storage/images/video-2.svg') }}" alt="Video and Shorts">
                <span>{{ get_phrase('Video and Shorts') }}</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('event') ? 'act' : '' }}">
            <a href="{{ route('event') }}">
                <img src="{{ asset('storage/images/events-2.svg') }}" alt="Event">
                <span>{{ get_phrase('Event') }}</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('blogs') ? 'act' : '' }}">
            <a href="{{ route('blogs') }}">
                <img src="{{ asset('storage/images/blogging-2.svg') }}" alt="Blog">
                <span>{{ get_phrase('Blog') }}</span>
            </a>
        </li>
    </ul>
</div>
    </div>





