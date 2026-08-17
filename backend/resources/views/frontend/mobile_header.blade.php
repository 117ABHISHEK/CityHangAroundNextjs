<!-- Mobile Header Component -->

<header class="mobile-header d-lg-none">
    <div class="mobile-header-container">
        <!-- Top Header Row -->
        <div class="mobile-header-top">
            <div class="mobile-logo">
                <a href="{{ route('timeline') }}">
                    <img src="{{ get_system_logo_favicon($system_light_logo ?? '', 'light') }}" alt="logo"
                        class="mobile-logo-img" />
                </a>
            </div>

            <div class="mobile-header-controls">
                <!-- City Search Button -->
                <button class="mobile-city-btn" id="mobileCityBtn">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>{{ $cityName ?? 'Search City' }}</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>

                <!-- Right Icons -->
                <div class="mobile-icons">
                    @if(auth()->check())
                        <!-- Chat Icon -->
                        <a href="#" class="mobile-icon-btn">
                            <i class="fa-solid fa-comment"></i>
                        </a>

                        <!-- Notification Icon -->
                        <a href="{{ route('notifications') }}" class="mobile-icon-btn position-relative">
                            <i class="fa-solid fa-bell"></i>
                            @if (($unread_notifications_count ?? 0) > 0)
                                <span class="mobile-notification-badge">{{ $unread_notifications_count }}</span>
                            @endif
                        </a>

                        <!-- Profile Icon -->
                        <div class="dropdown">
                            <button class="mobile-icon-btn" type="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-user"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <li><a class="dropdown-item"
                                        href="{{ route('profile') }}">{{ get_phrase('My Profile') }}</a></li>

                                @if(auth()->user()->user_role == "admin")

                                    <li><a class="dropdown-item"
                                            href="{{ route('admin.dashboard') }}">{{ get_phrase('Go to admin panel') }}</a></li>

                                @elseif(auth()->user()->user_role == "general")

                                    <li><a class="dropdown-item"
                                            href="{{ route('user.dashboard') }}">{{ get_phrase('Dashboard') }}</a></li>

                                @endif
                                <li><a class="dropdown-item py-2"
                                        href="{{ route('user.password.change') }}">{{ get_phrase('Change Password') }}</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ get_phrase('Log Out') }}
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <!-- Login/Register for non-authenticated users -->
                        <a href="{{ route('login') }}" class="mobile-icon-btn">
                            <i class="fa-solid fa-sign-in-alt"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Search Bar Row -->
        <div class="mobile-search-row">
            <div class="mobile-search-container">
                <i class="fa-solid fa-magnifying-glass mobile-search-icon"></i>
                <select id="mobileSearchInput" class="mobile-search-select" style="width: 100%;">
                    <option value="">{{ get_phrase('Search...') }}</option>
                </select>
            </div>
        </div>

        <!-- Navigation Row -->
        <div class="mobile-nav-row">
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="mobile-nav-buttons">
                <button class="mobile-nav-btn" id="forBusinessBtn">
                    <span>For Business</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>

                <button class="mobile-nav-btn" id="forCityGuideBtn">
                    <span>For City Guide</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Dropdown Menus -->
    <div class="mobile-dropdown-overlay" id="mobileDropdownOverlay"></div>

    <!-- City Selection Dropdown -->
    <div class="mobile-dropdown mobile-city-dropdown" id="mobileCityDropdown">
        <div class="mobile-dropdown-header">
            <h6>Select City</h6>
            <button class="mobile-dropdown-close" id="closeCityDropdown">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="mobile-dropdown-content">
            <select name="city_slug" id="mobile_city_slug" class="form-select border-red-500">
                <option value="">-- Select City --</option>
                @foreach ($all_cities as $city)
                    <option value="{{ $city->city_slug }}">{{ $city->city_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- For Business Dropdown -->
    <div class="mobile-dropdown mobile-business-dropdown" id="mobileBusinessDropdown">
        <div class="mobile-dropdown-header">
            <h6>For Business</h6>
            <button class="mobile-dropdown-close" id="closeBusinessDropdown">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="mobile-dropdown-content">
            <a href="#" class="mobile-dropdown-item"><i class="fa-solid fa-building me-2"></i> Add Business</a>
            <a href="#" class="mobile-dropdown-item"><i class="fa-solid fa-tags me-2"></i> Add Deals</a>
            @if(auth()->check())
                <a href="{{ route('profile') }}" class="mobile-dropdown-item"><i class="fa-solid fa-user me-2"></i> My
                    Profile</a>
            @else
                <a href="{{ route('login') }}" class="mobile-dropdown-item"><i class="fa-solid fa-sign-in-alt me-2"></i>
                    Login/SignUp</a>
            @endif
        </div>
    </div>

    <!-- For City Guide Dropdown -->
    <div class="mobile-dropdown mobile-guide-dropdown" id="mobileGuideDropdown">
        <div class="mobile-dropdown-header">
            <h6>For City Guide</h6>
            <button class="mobile-dropdown-close" id="closeGuideDropdown">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="mobile-dropdown-content">
            <a href="#" class="mobile-dropdown-item"><i class="fa-solid fa-map-marked-alt me-2"></i> City Guide</a>
            <a href="#" class="mobile-dropdown-item"><i class="fa-solid fa-tags me-2"></i> Deals</a>
            <a href="#" class="mobile-dropdown-item"><i class="fa-solid fa-user-friends me-2"></i> Following</a>
            <a href="#" class="mobile-dropdown-item"><i class="fa-solid fa-calendar-alt me-2"></i> Event</a>
            <a href="#" class="mobile-dropdown-item"><i class="fa-solid fa-newspaper me-2"></i> Blog</a>
            <a href="#" class="mobile-dropdown-item"><i class="fa-solid fa-clipboard-list me-2"></i> Post
                Requirement</a>
            @if(auth()->check())
                <a href="{{ route('profile') }}" class="mobile-dropdown-item"><i class="fa-solid fa-user me-2"></i> My
                    Profile</a>
            @else
                <a href="{{ route('login') }}" class="mobile-dropdown-item"><i class="fa-solid fa-sign-in-alt me-2"></i>
                    Login/SignUp</a>
            @endif
        </div>
    </div>
</header>

<style>
    .mobile-header {
        background: #fff;
        border-bottom: 1px solid #eee;
        position: sticky;
        top: 0;
        z-index: 1000;
        padding: 12px 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .mobile-header-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .mobile-logo-img {
        height: 32px;
        width: auto;
        transition: transform 0.3s ease;
    }

    .mobile-logo-img:hover {
        transform: scale(1.05);
    }

    .mobile-header-controls {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .mobile-city-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #fdf2f2;
        border: 1px solid #fee2e2;
        border-radius: 50px;
        padding: 6px 14px;
        font-size: 13px;
        font-weight: 500;
        color: #cc1f1f;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .mobile-city-btn:hover {
        background: #fee2e2;
        border-color: #fca5a5;
    }

    .mobile-icons {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .mobile-icon-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        background: #f9fafb;
        border: 1px solid #f3f4f6;
        border-radius: 50%;
        color: #4b5563;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .mobile-icon-btn:hover {
        color: #ff4939;
        background: #fff1f0;
        border-color: #fecaca;
    }

    .mobile-notification-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        background: #ff4939;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
    }

    .mobile-search-row {
        margin-bottom: 12px;
    }

    .mobile-search-container {
        position: relative;
        width: 100%;
        border-radius: 50px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .mobile-search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 15px;
        z-index: 5;
    }

    .mobile-search-select {
        width: 100%;
        padding: 10px 15px 10px 48px;
        border: 1px solid #e5e7eb;
        border-radius: 50px;
        font-size: 14px;
        background: white;
        outline: none;
        transition: all 0.3s ease;
    }

    .mobile-search-select:focus {
        border-color: #ff4939;
        box-shadow: 0 0 0 4px rgba(255, 73, 57, 0.1);
    }

    .mobile-nav-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .mobile-menu-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        color: #374151;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .mobile-menu-btn:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        color: #ff4939;
    }

    .mobile-nav-buttons {
        display: flex;
        gap: 8px;
        flex: 1;
    }

    .mobile-nav-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 50px;
        padding: 10px 15px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        cursor: pointer;
        transition: all 0.3s ease;
        flex: 1;
        justify-content: center;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .mobile-nav-btn:hover {
        border-color: #ff4939;
        color: #ff4939;
        background: #fff1f0;
    }

    .mobile-dropdown-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(2px);
        z-index: 1001;
        display: none;
    }

    .mobile-dropdown {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        z-index: 1002;
        width: 90%;
        max-width: 360px;
        display: none;
        overflow: hidden;
    }

    .mobile-dropdown-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid #f3f4f6;
        background: #fafafa;
    }

    .mobile-dropdown-header h6 {
        margin: 0;
        font-weight: 700;
        color: #111827;
        font-size: 16px;
    }

    .mobile-dropdown-close {
        background: #f3f4f6;
        border: none;
        font-size: 14px;
        color: #6b7280;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .mobile-dropdown-close:hover {
        background: #fee2e2;
        color: #ef4444;
    }

    .mobile-dropdown-content {
        padding: 12px 24px 24px;
    }

    .mobile-dropdown-item {
        display: flex;
        align-items: center;
        padding: 14px 16px;
        color: #4b5563;
        text-decoration: none !important;
        border-radius: 12px;
        transition: all 0.2s ease;
        font-weight: 500;
        font-size: 15px;
        margin-bottom: 4px;
    }

    .mobile-dropdown-item i {
        width: 20px;
        color: #9ca3af;
        transition: color 0.2s ease;
    }

    .mobile-dropdown-item:hover {
        background: #fff1f0;
        color: #ff4939;
    }

    .mobile-dropdown-item:hover i {
        color: #ff4939;
    }

    .mobile-dropdown.active,
    .mobile-dropdown-overlay.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const overlay = document.getElementById('mobileDropdownOverlay');
        const toggleDropdown = (id, show) => {
            const el = document.getElementById(id);
            if (el) { el.classList.toggle('active', show); overlay.classList.toggle('active', show); }
        };

        const dropdowns = [
            { btn: 'mobileCityBtn', drop: 'mobileCityDropdown', close: 'closeCityDropdown' },
            { btn: 'forBusinessBtn', drop: 'mobileBusinessDropdown', close: 'closeBusinessDropdown' },
            { btn: 'forCityGuideBtn', drop: 'mobileGuideDropdown', close: 'closeGuideDropdown' },
            { btn: 'mobileMenuBtn', drop: 'mobileMenuDropdown', close: 'closeMenuDropdown' }
        ];

        dropdowns.forEach(d => {
            const btn = document.getElementById(d.btn);
            const close = document.getElementById(d.close);
            if (btn) btn.addEventListener('click', () => {
                if (d.drop === 'mobileMenuDropdown' && !document.getElementById(d.drop)) createMobileMenuDropdown();
                else toggleDropdown(d.drop, true);
            });
            if (close) close.addEventListener('click', () => toggleDropdown(d.drop, false));
        });

        if (overlay) overlay.addEventListener('click', () => {
            document.querySelectorAll('.mobile-dropdown').forEach(d => d.classList.remove('active'));
            overlay.classList.remove('active');
        });

        const citySelect = document.getElementById('mobile_city_slug');
        if (citySelect) citySelect.addEventListener('change', function () { if (this.value) window.location.href = `/${this.value}`; });

        function createMobileMenuDropdown() {
            if (document.getElementById('mobileMenuDropdown')) return;
            const menu = document.createElement('div');
            menu.className = 'mobile-dropdown mobile-menu-dropdown';
            menu.id = 'mobileMenuDropdown';
            menu.innerHTML = `
            <div class="mobile-dropdown-header"><h6>Menu</h6><button class="mobile-dropdown-close" id="closeMenuDropdown"><i class="fa-solid fa-times"></i></button></div>
            <div class="mobile-dropdown-content">
                <a href="{{ route('timeline') }}" class="mobile-dropdown-item"><i class="fa-solid fa-house me-2"></i> Home</a>
                <a href="#" class="mobile-dropdown-item"><i class="fa-solid fa-building me-2"></i> Businesses</a>
                <a href="#" class="mobile-dropdown-item"><i class="fa-solid fa-calendar me-2"></i> Events</a>
                <a href="#" class="mobile-dropdown-item"><i class="fa-solid fa-newspaper me-2"></i> Blog</a>
                <a href="#" class="mobile-dropdown-item"><i class="fa-solid fa-users me-2"></i> Groups</a>
                <a href="#" class="mobile-dropdown-item"><i class="fa-solid fa-shopping-cart me-2"></i> Marketplace</a>
                @if(auth()->check())
                    <hr class="my-2 border-gray-100">
                    <a href="{{ route('profile') }}" class="mobile-dropdown-item"><i class="fa-solid fa-user me-2"></i> My Profile</a>
                    <a href="{{ route('notifications') }}" class="mobile-dropdown-item"><i class="fa-solid fa-bell me-2"></i> Notifications</a>
                    <a href="{{ route('logout') }}" class="mobile-dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa-solid fa-sign-out-alt me-2"></i> Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                @else
                    <hr class="my-2 border-gray-100">
                    <a href="{{ route('login') }}" class="mobile-dropdown-item"><i class="fa-solid fa-sign-in-alt me-2"></i> Login</a>
                @endif
            </div>`;
            document.body.appendChild(menu);
            menu.querySelector('#closeMenuDropdown').addEventListener('click', () => toggleDropdown('mobileMenuDropdown', false));
            toggleDropdown('mobileMenuDropdown', true);
        }

        // Select2 Optimization
        if ($.fn.select2) {
            $("#mobileSearchInput").select2({
                placeholder: "{{ get_phrase('Search...') }}",
                allowClear: true,
                ajax: {
                    url: "{{ route('search.globally') }}",
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ search: params.term, cityid: $("#mobile_city_slug").val() }),
                    processResults: data => {
                        let results = [];
                        if (data.pages.length) results.push({ text: '📄 Pages', children: data.pages.map(p => ({ id: p.id, text: p.title, type: 'page', citySlug: p.city_slug, areaSlug: p.area_slug, categorySlug: p.category_slug, itemSlug: p.item_slug })) });
                        if (data.marketplace.length) results.push({ text: '🛍️ Marketplace', children: data.marketplace.map(i => ({ id: i.id, text: i.title, type: 'marketplace' })) });
                        if (data.events.length) results.push({ text: '📅 Events', children: data.events.map(e => ({ id: e.id, text: e.title, type: 'event' })) });
                        if (data.blogs.length) results.push({ text: '📝 Blog', children: data.blogs.map(b => ({ id: b.id, text: b.title, type: 'blog' })) });
                        if (data.users.length) results.push({ text: '👤 Users', children: data.users.map(u => ({ id: u.id, text: u.name, type: 'user' })) });
                        return { results };
                    },
                    cache: true
                }
            }).on('select2:select', function (e) {
                const d = e.params.data;
                let url = "";
                switch (d.type) {
                    case 'page': url = `/${d.citySlug}/${d.areaSlug}/${d.categorySlug}/${d.itemSlug}`; break;
                    case 'marketplace': url = `/product/filter?search=${encodeURIComponent(d.text)}`; break;
                    case 'event': url = `/events?title=${encodeURIComponent(d.text)}`; break;
                    case 'blog': url = `/blogs?title=${encodeURIComponent(d.text)}`; break;
                    case 'user': url = "/user/view-profile/" + d.id; break;
                }
                if (url) window.location.href = url;
            });
        }
    });
</script>