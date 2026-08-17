{{-- resources/views/frontend/partials/header.blade.php --}}
<header>
    {{-- DESKTOP HEADER --}}
    <div class="desktop-header">
        <div class="d-flex align-items-center gap-3">
            {{-- Logo --}}
            <div class="logo">
                <a href="{{ url('/home') }}">
                    <img src="https://cdn.cityhangaround.com/file/cityhangaround/uploads/wYMNRNks5mOFSD2V1eOkZbjeH0m3dhFpyT79tsHW.png"
                        alt="Logo" />
                </a>
            </div>

            {{-- SEARCH --}}
            <div class="main-search-container mb-0">
                <div class="search-inner">
                    <div class="city-dropdown-wrapper">
                        <i class="fas fa-map-marker-alt location-icon"></i>
                        <div class="custom-dropdown">
                            <div class="dropdown-toggle" id="cityToggleDesktop">
                                <span id="selectedCityDesktop">{{ $cityName ?? 'Select City' }}</span>
                            </div>
                            <div class="dropdown-menu" id="cityMenuDesktop">
                                <input type="text" id="citySearchDesktop" placeholder="Search city..."
                                    class="dropdown-search" onkeyup="filterCityListDesktop(this)" />
                                <div class="dropdown-options" id="cityListDesktop">
                                    {{-- Filled by AJAX --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <form id="search-form" class="w-100" style="flex: 1; display: flex;">
                            <input type="hidden" id="city_header" value="{{ $citySlug ?? '' }}">
                            <select id="search-box" name="search" data-placeholder="Search for businesses..."
                                style="width: 100%;"></select>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE --}}
        <div class="desktop-right">
            <div class="dropdown-wrapper">
                <div class="dropdown-toggle" id="businessToggleDesktop">For Business</div>
                <div class="menu-dropdown" id="businessMenuDesktop">
                    <a href="{{ url('admin/page/create') }}">Add Business</a>
                    <a href="{{ url('products/create') }}">List Product/Service</a>
                    <a href="{{ url('login') }}">Login / Signup</a>
                    <a href="{{ url('user/leads/view') }}">Get Leads</a>
                    <a href="{{ url('user/subscriptions/view') }}">Advertisement</a>
                    <a href="{{ url('pages/custom/contact-us') }}">Contact</a>
                    <a href="{{ route('public.subscriptions') }}">Promotion</a>
                </div>
            </div>

            <button class="advertise" onclick="window.location.href='{{ url('pages/custom/advertise') }}';">
                Advertise
            </button>

            @if (auth()->user())
                <div class="dropdown profile-control">
                    <button class="btn dropdown-toggle p-0 border-0 bg-transparent" type="button"
                        id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ get_user_image(auth()->user()->photo, 'optimized') }}" class="rounded-circle"
                            width="40" height="40" alt="">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="{{ route('profile') }}">{{ get_phrase('My Profile') }}</a>
                        </li>
                        @if (auth()->user()->user_role == 'admin')
                            <li><a class="dropdown-item"
                                    href="{{ route('admin.dashboard') }}">{{ get_phrase('Go to admin panel') }}</a>
                            </li>
                        @endif
                        @if (auth()->user()->user_role == 'general')
                            <li><a class="dropdown-item"
                                    href="{{ route('user.dashboard') }}">{{ get_phrase('Dashboard') }}</a></li>
                        @endif
                        <li><a class="dropdown-item"
                                href="{{ route('user.password.change') }}">{{ get_phrase('Change Password') }}</a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">{{ get_phrase('Log Out') }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <button class="login-btn" onclick="window.location.href='{{ route('login') }}'">
                    Login
                </button>
            @endif
        </div>
    </div>

    {{-- MOBILE HEADER --}}
    <div class="top-header">
        <div class="logo">
            <a href="{{ url('/home') }}">
                <img src="https://cdn.cityhangaround.com/file/cityhangaround/uploads/wYMNRNks5mOFSD2V1eOkZbjeH0m3dhFpyT79tsHW.png"
                    alt="Logo" />
            </a>
        </div>

        <button class="advertise" onclick="window.location.href='{{ url('pages/custom/advertise') }}';">
            Advertise
        </button>

        @if (auth()->user())
            <div class="dropdown profile-control">
                <button class="btn dropdown-toggle p-0 border-0 bg-transparent" type="button" id="dropdownMenuButton2"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ get_user_image(auth()->user()->photo, 'optimized') }}" class="rounded-circle"
                        width="40" height="40" alt="">
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                    <li><a class="dropdown-item" href="{{ route('profile') }}">{{ get_phrase('My Profile') }}</a></li>
                    @if (auth()->user()->user_role == 'admin')
                        <li><a class="dropdown-item"
                                href="{{ route('admin.dashboard') }}">{{ get_phrase('Go to admin panel') }}</a></li>
                    @endif
                    @if (auth()->user()->user_role == 'general')
                        <li><a class="dropdown-item"
                                href="{{ route('user.dashboard') }}">{{ get_phrase('Dashboard') }}</a></li>
                    @endif
                    <li><a class="dropdown-item"
                            href="{{ route('user.password.change') }}">{{ get_phrase('Change Password') }}</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">{{ get_phrase('Log Out') }}</button>
                        </form>
                    </li>
                </ul>
            </div>
        @else
            <button class="login-btn" onclick="window.location.href='{{ route('login') }}'">
                Login
            </button>
        @endif

        <button class="navbar-toggler" id="menuBtn">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    {{-- MOBILE SEARCH ROW --}}
    <div class="mobile-search-row">
        <div class="main-search-container">
            <div class="search-inner">
                <div class="city-dropdown-wrapper">
                    <i class="fas fa-map-marker-alt location-icon"></i>
                    <div class="custom-dropdown">
                        <div class="dropdown-toggle" id="cityToggleMobile">
                            <span id="selectedCityMobile">{{ $cityName ?? 'Select City' }}</span>
                        </div>
                        <div class="dropdown-menu" id="cityMenuMobile">
                            <input type="text" id="citySearchMobile" placeholder="Search city..."
                                class="dropdown-search" onkeyup="filterCityListDesktop(this)" />
                            <div class="dropdown-options" id="cityListMobile"></div>
                        </div>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <form id="mobile-search-form" class="w-100" style="flex: 1; display: flex;">
                        <input type="hidden" id="mobile_city_slug" value="{{ $citySlug ?? '' }}">
                        <select id="mobile-search-box" name="search" data-placeholder="Search for businesses..." style="width: 100%;"></select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCROLL MENU + MEGA MENUS --}}
    <div class="scroll-wrap">
        <div class="scroll-menu">
            <a data-menu="cityGuide">City Guide ▾</a>
            <a data-menu="Sell">Buy/Sell ▾</a>
            <a data-menu="marketplace">Marketplace ▾</a>
            <a data-menu="community">Community ▾</a>
            <a data-menu="event">Event ▾</a>
            <a data-menu="blog">Blog ▾</a>
        </div>

        <div class="mega-menus" id="megaContainer">
            {{-- City Guide --}}
            <div class="mega-menu" id="cityGuide">
                @if($menuCategories->isEmpty())
                    <div class="mega-column w-100 text-center py-4" style="flex: 1; padding: 20px;">
                        <p class="text-muted mb-2">No businesses listed in {{ $cityName }} yet.</p>
                        <a href="{{ url('pages/create') }}" class="btn btn-sm btn-danger text-white px-3" style="display:inline-block; border-radius:6px; background-color:#ff4939; border:none; padding: 6px 12px; text-decoration:none; font-size:13px; font-weight:600;">Add Business</a>
                    </div>
                @else
                    @foreach ($menuCategories->chunk(4) as $chunk)
                        <div class="mega-column">
                            @foreach ($chunk as $category)
                                <a href="{{ route('page.category', $category->category_slug) }}">
                                    {{ $category->category_name }}
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Marketplace --}}
            <div class="mega-menu" id="marketplace">
                @if($marketplaceCategories->isEmpty())
                    <div class="mega-column w-100 text-center py-4" style="flex: 1; padding: 20px;">
                        <p class="text-muted mb-2">No deals/products in {{ $cityName }} yet.</p>
                        <a href="{{ url('products/create') }}" class="btn btn-sm btn-danger text-white px-3" style="display:inline-block; border-radius:6px; background-color:#ff4939; border:none; padding: 6px 12px; text-decoration:none; font-size:13px; font-weight:600;">Add Deal</a>
                    </div>
                @else
                    @php $chunks = $marketplaceCategories->chunk(ceil($marketplaceCategories->count() / 3)); @endphp
                    @foreach ($chunks as $chunk)
                        <div class="mega-column">
                            @foreach ($chunk as $category)
                                <a href="{{ route('product.category', $category->product_category_slug) }}">
                                    {{ ucwords($category->product_category_name) }}
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Buy/Sell --}}
            <div class="mega-menu" id="Sell">
                <div class="mega-column">
                    <a href="">Post Requirement</a>
                    <a href="{{ url('pages/create') }}">Add Business</a>
                    <a href="{{ url('products/create') }}">Add Deals</a>
                </div>
            </div>

            {{-- Community --}}
            <div class="mega-menu" id="community">
                @if($groupCategories->isEmpty())
                    <div class="mega-column w-100 text-center py-4" style="flex: 1; padding: 20px;">
                        <p class="text-muted mb-2">No groups created in {{ $cityName }} yet.</p>
                        <a href="{{ url('groups/create') }}" class="btn btn-sm btn-danger text-white px-3" style="display:inline-block; border-radius:6px; background-color:#ff4939; border:none; padding: 6px 12px; text-decoration:none; font-size:13px; font-weight:600;">Create Group</a>
                    </div>
                @else
                    @php $chunks = $groupCategories->chunk(ceil($groupCategories->count() / 3)); @endphp
                    @foreach ($chunks as $chunk)
                        <div class="mega-column">
                            @foreach ($chunk as $category)
                                <a href="{{ route('category.group', $category->category_slug ?? $category->id) }}">
                                    {{ ucwords($category->category_name) }}
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Event --}}
            <div class="mega-menu" id="event">
                @if($eventCategories->isEmpty())
                    <div class="mega-column w-100 text-center py-4" style="flex: 1; padding: 20px;">
                        <p class="text-muted mb-2">No events scheduled in {{ $cityName }} yet.</p>
                        <a href="{{ url('pages/create') }}" class="btn btn-sm btn-danger text-white px-3" style="display:inline-block; border-radius:6px; background-color:#ff4939; border:none; padding: 6px 12px; text-decoration:none; font-size:13px; font-weight:600;">Add Event</a>
                    </div>
                @else
                    @php $chunks = $eventCategories->chunk(ceil($eventCategories->count() / 3)); @endphp
                    @foreach ($chunks as $chunk)
                        <div class="mega-column">
                            @foreach ($chunk as $category)
                                <a href="{{ route('event.category', $category->category_slug ?? $category->id) }}">
                                    {{ ucwords($category->category_name) }}
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Blog --}}
            <div class="mega-menu" id="blog">
                @if($blogCategories->isEmpty())
                    <div class="mega-column w-100 text-center py-4" style="flex: 1; padding: 20px;">
                        <p class="text-muted mb-2">No blogs published in {{ $cityName }} yet.</p>
                        <a href="{{ url('blog/create') }}" class="btn btn-sm btn-danger text-white px-3" style="display:inline-block; border-radius:6px; background-color:#ff4939; border:none; padding: 6px 12px; text-decoration:none; font-size:13px; font-weight:600;">Add Blog</a>
                    </div>
                @else
                    @php $chunks = $blogCategories->chunk(ceil($blogCategories->count() / 3)); @endphp
                    @foreach ($chunks as $chunk)
                        <div class="mega-column">
                            @foreach ($chunk as $category)
                                <a href="{{ route('category.blog', $category->category_slug ?? $category->id) }}">
                                    {{ ucwords($category->category_name) }}
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</header>

{{-- SIDEBAR (MOBILE) --}}
<div class="sidebar" id="sidebar">
    <div class="sidebar-close" id="closeSidebar">
        <i class="fas fa-times"></i>
    </div>
    <div class="dropdown-wrapper">
        <div class="dropdown-toggle" id="businessToggleMobile">
            For Business
        </div>
        <div class="menu-dropdown" id="businessMenuMobile">
            <a href="{{ url('pages/create') }}">Add Business</a>
            <a href="{{ url('products/create') }}">List Product/Service</a>
            <a href="{{ url('login') }}">Login / Signup</a>
            <a href="{{ url('user/leads/view') }}">Get Leads</a>
            <a href="{{ url('user/subscriptions/view') }}">Advertisement</a>
            <a href="{{ url('pages/custom/contact-us') }}">Contact</a>
            <a href="#">Promotion</a>
        </div>
    </div>
</div>

{{-- All global JS you had earlier --}}


@push('scripts')
    {{-- 🔽 Paste all your header JS blocks here from original code 🔽 --}}
    @include('frontend.partials.global-scripts')


    <script>
        // Example chunks (shortened):

        function toggleCityDropdownDesktop() {
            document.getElementById("cityMenuDesktop").classList.toggle("active");
        }

        function filterCityListDesktop(element) {
            if (!element) return;
            let filter = element.value.toLowerCase();
            if (filter.length < 3) return;

            $.ajax({
                url: "{{ route('load-cities-ajax') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    filter: filter
                },
                success: function(response) {
                    var html = '';
                    const cityArray = Object.values(response.cities);
                    for (var i = 0; i < cityArray.length; i++) {
                        html += '<div class="option" onclick="selectCityDesktop(\'' + cityArray[i].city_name +
                            '\', \'' + cityArray[i].city_slug + '\')">' + cityArray[i].city_name + '</div>';
                    }
                    document.getElementById("cityListDesktop").innerHTML = html;
                    document.getElementById("cityListMobile").innerHTML = html;
                }
            });
        }

        function selectCityDesktop(cityName, citySlug) {
            document.getElementById("selectedCityDesktop").textContent = cityName;
            document.getElementById("cityMenuDesktop").classList.remove("active");
            window.location.href = "/" + citySlug;
        }

        // Sidebar toggle & business menu, profile dropdowns, mega menu open/close etc.
        // 👉 Copy ALL remaining <script> blocks from your header file into this section
        // so behavior stays 100% the same.
    </script>
@endpush
