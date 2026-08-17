<style>
  /* Hide menu on mobile devices */
  @media (max-width: 991.98px) {
    .main-nav {
      display: none !important;
    }
  }
  
  .main-nav {
    background-color: white;
    border-bottom: 1px solid #eee;
    position: relative;
    z-index: 10000;
    padding: 10px 0;
  }

  .nav-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
  }

  .nav-brand {
    display: flex;
    align-items: center;
    font-weight: bold;
    font-size: 18px;
    color: #333;
    text-decoration: none;
  }

  .nav-logo {
    height: 30px;
    width: auto;
    object-fit: contain;
  }

  .nav-text {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .nav-brand i {
    margin-right: 8px;
    color: #ff4939;
  }

  .nav-controls {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .city-selector {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 20px;
    background: white;
    cursor: pointer;
    font-size: 14px;
    color: #666;
    transition: all 0.3s ease;
    position: relative;
    min-width: 110px;
    max-width: 140px;
    white-space: nowrap;
  }

  .city-selector:hover {
    border-color: #ff4939;
    color: #ff4939;
  }

  .city-selector.active {
    border-color: #ff4939;
    color: #ff4939;
  }

  .city-dropdown {
    position: absolute;
    top: calc(100% + 5px);
    left: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    z-index: 10002;
    max-height: 300px;
    overflow-y: auto;
    display: none;
    min-width: 150px;
    width: auto;
    max-width: 250px;
  }

  .city-dropdown.active {
    display: block;
    animation: fadeInDown 0.3s ease;
  }

  .city-search-container {
    position: relative;
    padding: 10px 15px;
    border-bottom: 1px solid #eee;
  }

  .city-search-input {
    width: 100%;
    padding: 8px 35px 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.3s ease;
  }

  .city-search-input:focus {
    border-color: #ff4939;
  }

  .city-search-icon {
    position: absolute;
    right: 25px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    font-size: 14px;
  }

  .city-options-container {
    max-height: 250px;
    overflow-y: auto;
  }

  @keyframes fadeInDown {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .city-option {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #f5f5f5;
    transition: background-color 0.3s ease;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 14px;
    line-height: 1.2;
    min-height: 20px;
  }

  .city-option:hover {
    background-color: #f8f9fa;
  }

  .city-option.selected {
    background-color: #ff4939;
    color: white;
  }

  .search-toggle {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 20px;
    background: white;
    cursor: pointer;
    font-size: 14px;
    color: #666;
    transition: all 0.3s ease;
  }

  .search-toggle:hover {
    border-color: #ff4939;
    color: #ff4939;
  }

  .search-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    z-index: 10003;
    display: none;
    align-items: center;
    justify-content: center;
  }

  .search-modal.active {
    display: flex;
  }

  .search-container {
    background: white;
    border-radius: 12px;
    padding: 30px;
    width: 90%;
    max-width: 600px;
    position: relative;
  }

  .search-close {
    position: absolute;
    top: 15px;
    right: 20px;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
  }

  .search-close:hover {
    color: #ff4939;
  }

  .search-input-group {
    position: relative;
    margin-bottom: 20px;
  }

  .search-input {
    width: 100%;
    padding: 15px 50px 15px 20px;
    border: 2px solid #eee;
    border-radius: 25px;
    font-size: 16px;
    outline: none;
    transition: border-color 0.3s ease;
    color: #000;
  }

  .search-input:focus {
    border-color: #ff4939;
  }

  .search-btn {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    background: #ff4939;
    border: none;
    color: white;
    padding: 10px 15px;
    border-radius: 20px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .search-btn:hover {
    background-color: #e63946;
  }

  .search-results {
    max-height: 400px;
    overflow-y: auto;
  }

  .search-result-item {
    padding: 15px;
    border-bottom: 1px solid #f5f5f5;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .search-result-item:hover {
    background-color: #f8f9fa;
  }

  .search-section {
    margin-bottom: 20px;
  }

  .search-section h4 {
    margin: 0 0 10px 0;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
    color: #333;
    font-size: 14px;
    font-weight: 600;
  }

  .search-result-title {
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
  }

  .search-result-category {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
  }

  .search-result-location {
    font-size: 12px;
    color: #999;
  }

  .menu-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 24px;
    padding: 8px;
    cursor: pointer;
    color: #333;
    border-radius: 4px;
    transition: background-color 0.3s ease;
  }

  @media (max-width: 991px) {
    .menu-toggle {
      display: block;
    }
  }

  .menu-toggle:hover {
    background-color: #f5f5f5;
    color: #ff4939;
  }

  .main-menu {
    list-style: none;
    display: flex;
    gap: 20px;
    padding: 0;
    margin: 0;
    justify-content: center;
    align-items: center;
    min-height: 60px;
  }

  .mobile-menu-header {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: #fff;
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    z-index: 10051;
    align-items: center;
    justify-content: space-between;
  }

  .mobile-menu-header.active {
    display: flex;
  }

  .mobile-menu-title {
    font-size: 18px;
    font-weight: bold;
    color: #333;
  }

  .mobile-menu-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
    padding: 5px;
    border-radius: 4px;
    transition: all 0.3s ease;
  }

  .mobile-menu-close:hover {
    background-color: #f5f5f5;
    color: #ff4939;
  }

  .main-menu > li {
    position: relative;
  }

  .main-menu > li > a {
    display: block;
    padding: 15px 20px;
    text-decoration: none;
    color: black;
    font-weight: 600;
    cursor: pointer;
    transition: color 0.3s ease;
  }

  .main-menu > li > a:hover {
    color: #ff4939;
  }

  .has-mega-menu:hover .mega-menu {
    display: flex;
  }

  .mega-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: white;
    padding: 20px 30px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    z-index: 1100;
    width: 700px;
    justify-content: space-between;
    border-top: 2px solid #ff4939;
    border-radius: 0 0 8px 8px;
    max-width: calc(100vw - 40px);
    overflow: hidden;
  }

  /* Ensure mega menu doesn't go off-screen */
  .has-mega-menu {
    position: relative;
  }

  .has-mega-menu:nth-last-child(-n+2) .mega-menu {
    left: auto;
    right: 0;
  }

  .mega-column {
    flex: 1;
    padding: 0 15px;
  }

  .mega-column h4 {
    font-size: 16px;
    margin-bottom: 10px;
    color: #111;
  }

  .mega-column a {
    display: block;
    padding: 4px 0;
    color: #444;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s ease;
  }

  .mega-column a:hover {
    color: #ff4939;
  }

  /* Mobile overlay for menu */
  .mobile-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1049;
  }

  .mobile-overlay.active {
    display: block;
  }

  /* Hide menu elements on desktop */
  @media (min-width: 992px) {
    .city-selector,
    .search-toggle {
      display: none !important;
    }
  }

  /* Responsive Styles */
  @media (max-width: 1200px) {
    .mega-menu {
      width: 600px;
      max-width: calc(100vw - 40px);
    }
  }

  @media (max-width: 768px) {
    .mega-menu {
      width: 100%;
      max-width: 100%;
      left: 0 !important;
      right: auto !important;
    }
  }

      @media (max-width: 992px) {
      .nav-container {
        justify-content: space-between;
      }

      .nav-controls {
        gap: 10px;
      }

      .city-selector,
      .search-toggle {
        padding: 6px 10px;
        font-size: 13px;
      }

      .city-dropdown {
        position: fixed;
        top: 70px;
        left: 10px;
        right: 10px;
        max-height: 250px;
        z-index: 1005;
        min-width: auto;
        max-width: none;
      }

    .menu-toggle {
      display: block;
    }

    .main-menu {
      position: fixed;
      top: 0;
      left: -100%;
      height: 100vh;
      width: 100%;
      background-color: #fff;
      flex-direction: column;
      padding: 80px 20px 20px;
      box-shadow: 2px 0 12px rgba(0,0,0,0.1);
      transition: left 0.3s ease;
      z-index: 1050;
      overflow-y: auto;
      justify-content: flex-start;
      gap: 0;
    }

    .main-menu.active {
      left: 0;
    }

    .main-menu > li {
      width: 100%;
      margin-bottom: 5px;
      position: relative;
    }

    .main-menu > li > a {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 20px;
      font-size: 16px;
      font-weight: 600;
      color: #333;
      background-color: #f8f9fa;
      border-radius: 8px;
      border: 1px solid #e9ecef;
      margin-bottom: 5px;
    }

    .main-menu > li > a:hover {
      background-color: #e9ecef;
      color: #ff4939;
    }

    /* Mobile Mega Menu */
    .mega-menu {
      position: static;
      width: 100%;
      padding: 15px;
      margin-top: 10px;
      box-shadow: none;
      border: 1px solid #e9ecef;
      border-radius: 8px;
      background-color: #f8f9fa;
      flex-direction: column;
    }

    .has-mega-menu.open .mega-menu {
      display: flex;
    }

    .mega-column {
      padding: 10px 0;
      border-bottom: 1px solid #e9ecef;
    }

    .mega-column:last-child {
      border-bottom: none;
    }

    .mega-column a {
      padding: 10px 0;
      font-size: 15px;
      color: #444;
      text-decoration: none;
      border-radius: 4px;
      padding-left: 15px;
      transition: all 0.3s ease;
    }

    .mega-column a:hover {
      color: #ff4939;
      background-color: #e9ecef;
      padding-left: 20px;
    }

    /* Dropdown arrow for mobile */
    .has-mega-menu > a::after {
      content: '\f107';
      font-family: 'Font Awesome 5 Free';
      font-weight: 900;
      transition: transform 0.3s ease;
      color: #666;
    }

    .has-mega-menu.open > a::after {
      transform: rotate(180deg);
      color: #ff4939;
    }

    .mobile-menu-header.active {
      display: flex;
    }
  }

      @media (max-width: 768px) {
      .nav-container {
        padding: 0 10px;
      }

      .nav-brand {
        font-size: 16px;
      }

      .nav-controls {
        gap: 8px;
      }

      .city-selector,
      .search-toggle {
        padding: 5px 8px;
        font-size: 12px;
      }

      .city-dropdown {
        left: 5px;
        right: 5px;
        max-height: 200px;
        min-width: auto;
        max-width: none;
      }

    .main-menu > li > a {
      font-size: 15px;
      padding: 12px 16px;
    }

    .mega-column a {
      font-size: 14px;
    }

    .search-container {
      padding: 20px;
      width: 95%;
    }
  }

  @media (max-width: 576px) {
    .nav-brand {
      font-size: 14px;
    }

    .nav-controls {
      gap: 5px;
    }

    .city-selector,
    .search-toggle {
      padding: 4px 6px;
      font-size: 11px;
    }

    .menu-toggle {
      font-size: 20px;
      padding: 6px;
    }
  }

  /* Desktop menu styles */
  @media (min-width: 992px) {
    .main-menu {
      position: static !important;
      height: auto !important;
      width: auto !important;
      background-color: transparent !important;
      flex-direction: row !important;
      padding: 0 !important;
      box-shadow: none !important;
      overflow: visible !important;
      justify-content: center !important;
      gap: 20px !important;
    }

    .main-menu > li {
      width: auto !important;
      margin-bottom: 0 !important;
    }

    .main-menu > li > a {
      background-color: transparent !important;
      border: none !important;
      border-radius: 0 !important;
      margin-bottom: 0 !important;
    }

    .mega-menu {
      position: absolute !important;
      width: 700px !important;
      flex-direction: row !important;
      padding: 20px 30px !important;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1) !important;
      border: 1px solid #e9ecef !important;
      border-top: 2px solid #ff4939 !important;
      border-radius: 0 0 8px 8px !important;
      background-color: white !important;
    }

    .mega-column {
      padding: 0 15px !important;
      border-bottom: none !important;
    }

    .mega-column a {
      padding: 4px 0 !important;
      font-size: 14px !important;
      padding-left: 0 !important;
    }

    .mega-column a:hover {
      padding-left: 0 !important;
      background-color: transparent !important;
    }
  }
</style>

<nav class="main-nav">
  <div class="nav-container">
    <a href="{{ route('main') }}" class="nav-brand d-block d-lg-none">
      @php
        $system_light_logo = \App\Models\Setting::where('type', 'system_light_logo')->value('description');
      @endphp
      <img src="{{ get_system_logo_favicon($system_light_logo,'light') }}" class="nav-logo d-md-none" alt="logo" style="height: 30px; width: auto;">
      <div class="nav-text d-none d-md-flex">
        <i class="fas fa-map-marker-alt"></i>
        CITYHANGAROUND
      </div>
    </a>
    
    <div class="nav-controls">
      <div class="city-selector d-block d-lg-none" id="citySelector">
        <i class="fas fa-map-marker-alt"></i>
        <span id="selectedCity" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 100px;">Select City</span>
        <i class="fas fa-chevron-down"></i>
        
        <div class="city-dropdown" id="cityDropdown">
          <!-- City Search Input -->
          <div class="city-search-container">
            <input type="text" id="citySearchInput" class="city-search-input" placeholder="Search cities..." autocomplete="off">
            <i class="fas fa-search city-search-icon"></i>
          </div>
          
          <!-- City Options Container -->
          <div class="city-options-container" id="cityOptionsContainer">
            @if(isset($all_cities) && $all_cities->count() > 0)
              @foreach($all_cities as $city)
                <div class="city-option" data-city-id="{{ $city->id }}" data-city-name="{{ $city->city_name }}">
                  {{ $city->city_name }}
                </div>
              @endforeach
            @else
              @php
                $fallbackCities = \App\Helpers\CityHelper::getActiveCities();
              @endphp
              @if($fallbackCities->count() > 0)
                @foreach($fallbackCities as $city)
                  <div class="city-option" data-city-id="{{ $city->id }}" data-city-name="{{ $city->city_name }}">
                    {{ $city->city_name }}
                  </div>
                @endforeach
              @else
                <div class="city-option" data-city-id="1" data-city-name="New York">New York</div>
                <div class="city-option" data-city-id="2" data-city-name="Los Angeles">Los Angeles</div>
                <div class="city-option" data-city-id="3" data-city-name="Chicago">Chicago</div>
                <div class="city-option" data-city-id="4" data-city-name="Houston">Houston</div>
              @endif
            @endif
          </div>
        </div>
      </div>
      
      <div class="search-toggle d-block d-lg-none" id="searchToggle">
        <i class="fas fa-search"></i>
        <span>Search...</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      
      <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
        <i class="fas fa-bars"></i>
      </button>
    </div>
  </div>
  
  <!-- Mobile overlay -->
  <div class="mobile-overlay" id="mobileOverlay"></div>
  
  <!-- Mobile menu header with close button -->
  <div class="mobile-menu-header" id="mobileMenuHeader">
    <div class="mobile-menu-title">Menu</div>
    <button class="mobile-menu-close" id="mobileMenuClose" aria-label="Close menu">
      <i class="fas fa-times"></i>
    </button>
  </div>
  
  <ul class="main-menu" id="mainMenu">
    <li class="has-mega-menu">
      <a href="#">City Guide</a>
      <div class="mega-menu">
        @foreach ($menuCategories->chunk(4) as $chunk)
          <div class="mega-column">
            @foreach ($chunk as $category)
              <a href="{{ route('page.category', $category->category_slug) }}">
                {{ $category->category_name }}
              </a>
            @endforeach
          </div>
        @endforeach
      </div>
    </li>

    <li class="has-mega-menu">
      <a href="#">Marketplace</a>
      <div class="mega-menu">
        @php
        $chunks = $marketplaceCategories->chunk(ceil($marketplaceCategories->count() / 3));
        @endphp

        @foreach ($chunks as $chunk)
          <div class="mega-column">
            @foreach ($chunk as $category)
              <a href="{{ route('product.category', $category->product_category_slug) }}">
                {{ ucwords($category->product_category_name) }}
              </a>
            @endforeach
          </div>
        @endforeach
      </div>
    </li>

    <li class="has-mega-menu">
      <a href="#">Community</a>
      <div class="mega-menu">
        @php
        $chunks = $groupCategories->chunk(ceil($groupCategories->count() / 3));
        @endphp

        @foreach ($chunks as $chunk)
          <div class="mega-column">
            @foreach ($chunk as $category)
              <a href="{{ route('category.group', $category->category_slug ?? $category->id) }}">
                {{ ucwords($category->category_name) }}
              </a>
            @endforeach
          </div>
        @endforeach
      </div>
    </li>

    <li class="has-mega-menu">
      <a href="#">Event</a>
      <div class="mega-menu">
        @php
        $chunks = $eventCategories->chunk(ceil($eventCategories->count() / 3));
        @endphp

        @foreach ($chunks as $chunk)
          <div class="mega-column">
            @foreach ($chunk as $category)
              <a href="{{ route('event.category', $category->category_slug ?? $category->id) }}">
                {{ ucwords($category->category_name) }}
              </a>
            @endforeach
          </div>
        @endforeach
      </div>
    </li>

    <li class="has-mega-menu">
      <a href="#">Blog</a>
      <div class="mega-menu">
        @php
        $chunks = $blogCategories->chunk(ceil($blogCategories->count() / 3));
        @endphp

        @foreach ($chunks as $chunk)
          <div class="mega-column">
            @foreach ($chunk as $category)
              <a href="{{ route('category.blog', $category->category_slug ?? $category->id) }}">
                {{ ucwords($category->category_name) }}
              </a>
            @endforeach
          </div>
        @endforeach
      </div>
    </li>
  </ul>
</nav>

<!-- Search Modal -->
<div class="search-modal" id="searchModal">
  <div class="search-container">
    <button class="search-close" id="searchClose">
      <i class="fas fa-times"></i>
    </button>
    
    <div class="search-input-group">
      <!-- Search Input -->
      <input type="text" class="search-input" id="searchInput" placeholder="What are you looking for?" autocomplete="off">
      <button class="search-btn" id="searchBtn">
        <i class="fas fa-search"></i>
      </button>
    </div>
    
    <div class="search-results" id="searchResults">
      <!-- Search results will appear here -->
    </div>
  </div>
</div>

<!-- FontAwesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('menuToggle');
    const menu = document.getElementById('mainMenu');
    const overlay = document.getElementById('mobileOverlay');
    const mobileHeader = document.getElementById('mobileMenuHeader');
    const closeBtn = document.getElementById('mobileMenuClose');
    const dropdowns = document.querySelectorAll('.has-mega-menu');
    const citySelector = document.getElementById('citySelector');
    const cityDropdown = document.getElementById('cityDropdown');
    const selectedCity = document.getElementById('selectedCity');
    const citySearchInput = document.getElementById('citySearchInput');
    const cityOptionsContainer = document.getElementById('cityOptionsContainer');
    const searchToggle = document.getElementById('searchToggle');
    const searchModal = document.getElementById('searchModal');
    const searchClose = document.getElementById('searchClose');
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const searchResults = document.getElementById('searchResults');
    
    let isMenuOpen = false;
    let searchTimeout;

    // Initialize city options
    initializeCityOptions();

    // Toggle burger menu
    toggleBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      toggleMenu();
    });

    // Close menu with close button
    closeBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      toggleMenu();
    });

    // Toggle menu function
    function toggleMenu() {
      isMenuOpen = !isMenuOpen;
      menu.classList.toggle('active');
      overlay.classList.toggle('active');
      mobileHeader.classList.toggle('active');
      
      // Prevent body scroll when menu is open
      if (isMenuOpen) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = '';
      }
    }

    // Handle dropdown menu for mobile
    dropdowns.forEach(drop => {
      const anchor = drop.querySelector('a');

      anchor.addEventListener('click', function (e) {
        if (window.innerWidth <= 992) {
          const isParent = this.parentElement.classList.contains('has-mega-menu');

          if (isParent) {
            e.preventDefault();
            e.stopPropagation();

            // Close other dropdowns
            dropdowns.forEach(d => {
              if (d !== drop) {
                d.classList.remove('open');
              }
            });

            // Toggle current dropdown
            drop.classList.toggle('open');
          }
        }
      });
    });

    // Handle mega menu positioning to prevent off-screen overflow
    dropdowns.forEach(drop => {
      const megaMenu = drop.querySelector('.mega-menu');
      
      if (megaMenu) {
        drop.addEventListener('mouseenter', function() {
          if (window.innerWidth > 992) {
            const rect = this.getBoundingClientRect();
            const menuWidth = megaMenu.offsetWidth;
            const windowWidth = window.innerWidth;
            
            // Check if menu would go off the right edge
            if (rect.left + menuWidth > windowWidth - 20) {
              megaMenu.style.left = 'auto';
              megaMenu.style.right = '0';
            } else {
              megaMenu.style.left = '0';
              megaMenu.style.right = 'auto';
            }
          }
        });
      }
    });

    // City selector functionality
    citySelector.addEventListener('click', function(e) {
      e.stopPropagation();
      cityDropdown.classList.toggle('active');
      citySelector.classList.toggle('active');
      
      // Focus on search input when dropdown opens
      if (cityDropdown.classList.contains('active')) {
        setTimeout(() => {
          citySearchInput.focus();
          citySearchInput.value = '';
          showAllCities();
        }, 10);
      }
    });

    // Close city dropdown when clicking outside
    document.addEventListener('click', function(e) {
      if (!citySelector.contains(e.target)) {
        cityDropdown.classList.remove('active');
        citySelector.classList.remove('active');
      }
    });

    // Search functionality
    searchToggle.addEventListener('click', function(e) {
      e.stopPropagation();
      openSearchModal();
    });

    searchClose.addEventListener('click', function(e) {
      e.stopPropagation();
      closeSearchModal();
    });

    // Close search modal when clicking outside
    searchModal.addEventListener('click', function(e) {
      if (e.target === searchModal) {
        closeSearchModal();
      }
    });

    // Search input functionality
    searchInput.addEventListener('input', function() {
      clearTimeout(searchTimeout);
      const query = this.value.trim();
      
      if (query.length >= 2) {
        searchTimeout = setTimeout(() => {
          performSearch(query);
        }, 300);
      } else {
        searchResults.innerHTML = '';
      }
    });

    searchBtn.addEventListener('click', function() {
      const query = searchInput.value.trim();
      if (query) {
        performSearch(query);
      }
    });

    // Enter key to search
    searchInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        const query = this.value.trim();
        if (query) {
          performSearch(query);
        }
      }
    });

    // Close menu when clicking overlay
    overlay.addEventListener('click', function() {
      if (isMenuOpen) {
        toggleMenu();
      }
    });

    // Close menu when clicking outside
    document.addEventListener('click', function (e) {
      if (window.innerWidth <= 992 && isMenuOpen) {
        const clickedInsideMenu = menu.contains(e.target);
        const clickedToggle = toggleBtn.contains(e.target);
        const clickedOverlay = overlay.contains(e.target);

        if (!clickedInsideMenu && !clickedToggle && !clickedOverlay) {
          toggleMenu();
        }
      }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
      if (window.innerWidth > 992 && isMenuOpen) {
        toggleMenu();
      }
    });

    // Close dropdowns when clicking on menu items (mobile)
    menu.addEventListener('click', function(e) {
      if (window.innerWidth <= 992) {
        const link = e.target.closest('a');
        if (link && !link.parentElement.classList.contains('has-mega-menu')) {
          // Close all dropdowns when clicking a regular menu item
          dropdowns.forEach(drop => {
            drop.classList.remove('open');
          });
        }
      }
    });

    // Initialize city options from backend data
    function initializeCityOptions() {
      const cityOptions = cityOptionsContainer.querySelectorAll('.city-option');
      cityOptions.forEach(option => {
        option.addEventListener('click', function(e) {
          e.stopPropagation(); // Prevent event bubbling
          const cityId = this.dataset.cityId;
          const cityName = this.dataset.cityName;
          selectCity(cityId, cityName);
        });
      });
    }

    // City search functionality
    citySearchInput.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase().trim();
      filterCities(searchTerm);
    });

    // Filter cities based on search term
    function filterCities(searchTerm) {
      const cityOptions = cityOptionsContainer.querySelectorAll('.city-option');
      let hasVisibleCities = false;

      cityOptions.forEach(option => {
        const cityName = option.dataset.cityName.toLowerCase();
        if (cityName.includes(searchTerm)) {
          option.style.display = 'block';
          hasVisibleCities = true;
        } else {
          option.style.display = 'none';
        }
      });

      // Show "No cities found" message if no results
      const noResultsElement = cityOptionsContainer.querySelector('.no-cities-found');
      if (!hasVisibleCities && searchTerm !== '') {
        if (!noResultsElement) {
          const noResults = document.createElement('div');
          noResults.className = 'city-option no-cities-found';
          noResults.textContent = 'No cities found';
          noResults.style.color = '#666';
          noResults.style.fontStyle = 'italic';
          cityOptionsContainer.appendChild(noResults);
        }
      } else if (noResultsElement) {
        noResultsElement.remove();
      }
    }

    // Show all cities
    function showAllCities() {
      const cityOptions = cityOptionsContainer.querySelectorAll('.city-option');
      cityOptions.forEach(option => {
        option.style.display = 'block';
      });
      
      // Remove "No cities found" message
      const noResultsElement = cityOptionsContainer.querySelector('.no-cities-found');
      if (noResultsElement) {
        noResultsElement.remove();
      }
    }

    // Select a city
    function selectCity(cityId, cityName) {
      selectedCity.textContent = cityName;
      
      // Close dropdown immediately
      cityDropdown.classList.remove('active');
      citySelector.classList.remove('active');
      
      // Store selected city in localStorage
      localStorage.setItem('selectedCity', JSON.stringify({ id: cityId, name: cityName }));
      
      // You can add additional logic here, like filtering content by city
      console.log('Selected city:', cityId, cityName);
    }

    // Open search modal
    function openSearchModal() {
      searchModal.classList.add('active');
      searchInput.focus();
      document.body.style.overflow = 'hidden';
    }

    // Close search modal
    function closeSearchModal() {
      searchModal.classList.remove('active');
      searchInput.value = '';
      searchResults.innerHTML = '';
      document.body.style.overflow = '';
    }

    // Perform search
    function performSearch(query) {
      searchResults.innerHTML = '<div style="text-align: center; padding: 20px; color: #666;">Searching...</div>';
      
      fetch(`{{ route('search.globally') }}?search=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
          displaySearchResults(data, query);
        })
        .catch(error => {
          console.error('Search error:', error);
          searchResults.innerHTML = '<div style="text-align: center; padding: 20px; color: #666;">Search failed. Please try again.</div>';
        });
    }

    // Display search results
    function displaySearchResults(data, query) {
      searchResults.innerHTML = '';
      
      let hasResults = false;

      // Display pages
      if (data.pages && data.pages.length > 0) {
        hasResults = true;
        const pagesSection = document.createElement('div');
        pagesSection.className = 'search-section';
        pagesSection.innerHTML = '<h4>📄 Pages</h4>';
        
        data.pages.forEach(page => {
          const resultItem = createResultItem(
            page.title,
            'Page',
            page.city_slug || 'Unknown location',
            `/${page.city_slug}/${page.area_slug}/${page.category_slug}/${page.item_slug}`,
            null
          );
          pagesSection.appendChild(resultItem);
        });
        searchResults.appendChild(pagesSection);
      }

      // Display marketplace
      if (data.marketplace && data.marketplace.length > 0) {
        hasResults = true;
        const marketplaceSection = document.createElement('div');
        marketplaceSection.className = 'search-section';
        marketplaceSection.innerHTML = '<h4>🛍️ Marketplace</h4>';
        
        data.marketplace.forEach(item => {
          const resultItem = createResultItem(
            item.title,
            'Marketplace',
            'Marketplace',
            `/product/filter?search=${encodeURIComponent(item.title)}`,
            null
          );
          marketplaceSection.appendChild(resultItem);
        });
        searchResults.appendChild(marketplaceSection);
      }

      // Display events
      if (data.events && data.events.length > 0) {
        hasResults = true;
        const eventsSection = document.createElement('div');
        eventsSection.className = 'search-section';
        eventsSection.innerHTML = '<h4>📅 Events</h4>';
        
        data.events.forEach(event => {
          const resultItem = createResultItem(
            event.title,
            'Event',
            'Events',
            `/events?title=${encodeURIComponent(event.title)}`,
            null
          );
          eventsSection.appendChild(resultItem);
        });
        searchResults.appendChild(eventsSection);
      }

      // Display blogs
      if (data.blogs && data.blogs.length > 0) {
        hasResults = true;
        const blogsSection = document.createElement('div');
        blogsSection.className = 'search-section';
        blogsSection.innerHTML = '<h4>📝 Blog</h4>';
        
        data.blogs.forEach(blog => {
          const resultItem = createResultItem(
            blog.title,
            'Blog',
            'Blogs',
            `/blogs?title=${encodeURIComponent(blog.title)}`,
            null
          );
          blogsSection.appendChild(resultItem);
        });
        searchResults.appendChild(blogsSection);
      }

      // Display users
      if (data.users && data.users.length > 0) {
        hasResults = true;
        const usersSection = document.createElement('div');
        usersSection.className = 'search-section';
        usersSection.innerHTML = '<h4>👤 Users</h4>';
        
        data.users.forEach(user => {
          const resultItem = createResultItem(
            user.name,
            'User',
            'Users',
            `/user/view-profile/${user.id}`,
            null
          );
          usersSection.appendChild(resultItem);
        });
        searchResults.appendChild(usersSection);
      }

      if (!hasResults) {
        searchResults.innerHTML = `<div style="text-align: center; padding: 20px; color: #666;">No results found for "${query}"</div>`;
      }
    }

    // Create result item element
    function createResultItem(title, category, location, url, image) {
      const item = document.createElement('div');
      item.className = 'search-result-item';
      item.innerHTML = `
        <div class="search-result-title">${title}</div>
        <div class="search-result-category">${category}</div>
        <div class="search-result-location">${location}</div>
      `;
      
      item.addEventListener('click', function() {
        window.location.href = url;
        closeSearchModal();
      });
      
      return item;
    }

    // Load saved city on page load
    const savedCity = localStorage.getItem('selectedCity');
    if (savedCity) {
      try {
        const city = JSON.parse(savedCity);
        selectedCity.textContent = city.name;
      } catch (e) {
        console.error('Error parsing saved city:', e);
      }
    }
  });
</script>

                        
                            


