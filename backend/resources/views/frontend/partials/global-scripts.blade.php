{{-- resources/views/frontend/partials/global-scripts.blade.php --}}
@if(!defined('GLOBAL_SCRIPTS_LOADED'))
@php define('GLOBAL_SCRIPTS_LOADED', true); @endphp
<script>
(function() {
    function ensureJQuery(callback) {
        if (window.jQuery) {
            callback(window.jQuery);
            return;
        }
        var scripts = document.getElementsByTagName('script');
        var alreadyLoading = false;
        for (var i = 0; i < scripts.length; i++) {
            if (scripts[i].src && scripts[i].src.indexOf('jquery') !== -1) {
                alreadyLoading = true;
                break;
            }
        }
        if (alreadyLoading) {
            var attempts = 0;
            var interval = setInterval(function() {
                if (window.jQuery || attempts > 100) {
                    clearInterval(interval);
                    if (window.jQuery) callback(window.jQuery);
                }
                attempts++;
            }, 50);
        } else {
            var script = document.createElement('script');
            script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
            script.onload = function() {
                callback(window.jQuery);
            };
            document.head.appendChild(script);
        }
    }

    function ensureSelect2($, callback) {
        var styles = document.getElementsByTagName('link');
        var cssLoaded = false;
        for (var i = 0; i < styles.length; i++) {
            if (styles[i].href && styles[i].href.indexOf('select2') !== -1) {
                cssLoaded = true;
                break;
            }
        }
        if (!cssLoaded) {
            var css = document.createElement('link');
            css.rel = 'stylesheet';
            css.href = 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css';
            document.head.appendChild(css);
        }
        if ($.fn.select2) {
            callback();
            return;
        }
        var scripts = document.getElementsByTagName('script');
        var alreadyLoading = false;
        for (var i = 0; i < scripts.length; i++) {
            if (scripts[i].src && scripts[i].src.indexOf('select2') !== -1) {
                alreadyLoading = true;
                break;
            }
        }
        if (alreadyLoading) {
            var attempts = 0;
            var interval = setInterval(function() {
                if ($.fn.select2 || attempts > 100) {
                    clearInterval(interval);
                    if ($.fn.select2) callback();
                }
                attempts++;
            }, 50);
        } else {
            var script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js';
            script.onload = function() {
                callback();
            };
            document.head.appendChild(script);
        }
    }

    function runWithJQuery(callback) {
        ensureJQuery(function($) {
            ensureSelect2($, function() {
                callback($);
            });
        });
    }
    
    function clearSelectedCityContext() {
        localStorage.removeItem('selectedCityId');
        localStorage.removeItem('selectedCityName');
        localStorage.removeItem('selectedCitySlug');
        localStorage.removeItem('selectedCity');
        window.__headerLastCityId = undefined;
    }

    window.clearSelectedCityContext = clearSelectedCityContext;
    window.clearLocationContext = clearSelectedCityContext;

    // Expose interaction functions globally so that inline onclick/onkeyup attributes continue to work
    window.toggleCityDropdownDesktop = toggleCityDropdownDesktop;
    window.ensureCitiesLoaded = ensureCitiesLoaded;
    window.filterCityListDesktop = filterCityListDesktop;
    window.selectCityDesktop = selectCityDesktop;
    window.toggleCityDropdown = toggleCityDropdown;
    window.selectCity = selectCity;
    window.openEnquiryForm = openEnquiryForm;

    // ==========================================
    // 1. CITY DROPDOWN (DESKTOP + MOBILE SEARCH)
    // ==========================================
    function toggleCityDropdownDesktop() {
        // Handled by setupCityDropdown via event listeners
    }

    window.allApprovedCities = null;

    function ensureCitiesLoaded(callback) {
        if (window.allApprovedCities) {
            if (callback) callback();
            return;
        }

        try {
            let cached = localStorage.getItem('cached_cities_v2');
            let cachedTime = localStorage.getItem('cached_cities_time_v2');
            let now = new Date().getTime();
            // Cache for 2 hours (7200000 ms)
            if (cached && cachedTime && (now - cachedTime < 7200000)) {
                window.allApprovedCities = (typeof cached === 'string' ? JSON.parse(cached) : cached);
                if (callback) callback();
                return;
            }
        } catch(e) {
            console.warn("localStorage load failed", e);
        }

        runWithJQuery(function($) {
            $.ajax({
                url: "{{ route('load-all-cities-json') }}",
                method: "GET",
                success: function(cities) {
                    window.allApprovedCities = cities;
                    try {
                        localStorage.setItem('cached_cities_v2', JSON.stringify(cities));
                        localStorage.setItem('cached_cities_time_v2', new Date().getTime().toString());
                    } catch(e) {
                        console.warn("localStorage save failed", e);
                    }
                    if (callback) callback();
                }
            });
        });
    }

    // Pre-trigger background city loading
    setTimeout(function() {
        ensureCitiesLoaded();
    }, 500);

    function filterCityListDesktop(element) {
        if (!element) return;
        let filter = element.value.trim().toLowerCase();
        
        if (filter.length < 1) {
            if (document.getElementById("cityListDesktop")) {
                document.getElementById("cityListDesktop").innerHTML = '';
            }
            if (document.getElementById("cityListMobile")) {
                document.getElementById("cityListMobile").innerHTML = '';
            }
            return;
        }

        ensureCitiesLoaded(function() {
            let cities = window.allApprovedCities || [];
            let matches = cities.filter(function(city) {
                return city.city_name && city.city_name.toLowerCase().indexOf(filter) !== -1;
            });

            matches.sort(function(a, b) {
                let aName = a.city_name.toLowerCase();
                let bName = b.city_name.toLowerCase();
                let aStartsWith = aName.indexOf(filter) === 0;
                let bStartsWith = bName.indexOf(filter) === 0;
                if (aStartsWith && !bStartsWith) return -1;
                if (!aStartsWith && bStartsWith) return 1;
                return aName.localeCompare(bName);
            });

            let limit = Math.min(matches.length, 30);
            var html = '';
            for (var i = 0; i < limit; i++) {
                var city = matches[i];
                var safeName = city.city_name ? city.city_name.replace(/'/g, "\\'") : '';
                html += '<div class="option" onclick="selectCityDesktop(\'' + safeName + '\', \'' + city.city_slug + '\', ' + city.id + ')">' + city.city_name + '</div>';
            }

            if (document.getElementById("cityListDesktop")) {
                document.getElementById("cityListDesktop").innerHTML = html;
            }
            if (document.getElementById("cityListMobile")) {
                document.getElementById("cityListMobile").innerHTML = html;
            }
        });
    }

    function selectCityDesktop(cityName, citySlug, cityId) {
        if (window.event) window.event.preventDefault();

        localStorage.setItem('selectedCityId', cityId);
        localStorage.setItem('selectedCityName', cityName);
        localStorage.setItem('selectedCitySlug', citySlug);
        localStorage.setItem('selectedCity', JSON.stringify({ id: cityId, name: cityName, slug: citySlug }));

        var dLabel = document.getElementById("selectedCityDesktop");
        var mLabel = document.getElementById("selectedCityMobile");
        if (dLabel) dLabel.textContent = cityName;
        if (mLabel) mLabel.textContent = cityName;

        var menu = document.getElementById("cityMenuDesktop");
        if (menu) menu.classList.remove("active");

        if (window.loadHeaderMegaMenus) {
            window.loadHeaderMegaMenus(cityId);
        }

        var cityInput = document.getElementById("city_header") || document.getElementById("city_header_main");
        if (cityInput) {
            cityInput.value = cityId || '';
        }

        console.log("Switched to city:", cityName);
        window.location.href = '/' + citySlug;
        return false;
    }

    document.addEventListener("click", function(event) {
        const toggle = document.getElementById("cityToggleDesktop");
        const menu = document.getElementById("cityMenuDesktop");

        if (!toggle || !menu) return;
        if (!(event.target instanceof Element)) return;

        if (!toggle.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.remove("active");

            if (document.getElementById("citySearchDesktop")) {
                document.getElementById("citySearchDesktop").value = "";
            }
            if (document.getElementById("cityListDesktop")) {
                document.getElementById("cityListDesktop").innerHTML = "";
            }
            if (document.getElementById("cityListMobile")) {
                document.getElementById("cityListMobile").innerHTML = "";
            }
            if (document.getElementById("citySearchMobile")) {
                document.getElementById("citySearchMobile").value = "";
            }
        }
    });

    runWithJQuery(function($) {
        // ==========================================
        // 2. PROFILE DROPDOWN (DESKTOP)
        // ==========================================
        $('#dropdownMenuButton1').on('click', function(e) {
            e.preventDefault();
            var $parent = $(this).closest('.dropdown');
            var $menu = $parent.find('.dropdown-menu');
            $parent.toggleClass('show');
            $menu.toggleClass('show');
            $(this).attr('aria-expanded', $parent.hasClass('show'));
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown.show').removeClass('show').find('.dropdown-menu.show').removeClass('show');
                $('[data-bs-toggle="dropdown"]').attr('aria-expanded', 'false');
            }
        });

        $('#city_header_main').on('change', function() {
            var citySlug = $(this).val();
            if (citySlug) {
                let url = `/${citySlug}`;
                window.location.href = url;
            }
        });

        // ==========================================
        // 3. PROFILE DROPDOWN (MOBILE)
        // ==========================================
        $('#dropdownMenuButton2').on('click', function(e) {
            e.preventDefault();
            var $parent = $(this).closest('.dropdown');
            var $menu = $parent.find('.dropdown-menu');
            $parent.toggleClass('show');
            $menu.toggleClass('show');
            $(this).attr('aria-expanded', $parent.hasClass('show'));
        });
    });

    // ==========================================
    // 4. SIDEBAR + FOR BUSINESS MENU
    // ==========================================
    var sidebar = document.getElementById("sidebar");
    var menuBtn = document.getElementById("menuBtn");
    var closeSidebarBtn = document.getElementById("closeSidebar");
    var businessToggleMobile = document.getElementById("businessToggleMobile");
    var businessMenuMobile = document.getElementById("businessMenuMobile");
    var businessToggleDesktop = document.getElementById("businessToggleDesktop");
    var businessMenuDesktop = document.getElementById("businessMenuDesktop");

    if (menuBtn && sidebar) {
        menuBtn.addEventListener("click", () => {
            sidebar.classList.add("active");
            var overlay = document.getElementById("sidebarOverlay");
            if (overlay) overlay.classList.add("active");
            document.body.style.overflow = 'hidden';
            if (businessMenuMobile) {
                businessMenuMobile.style.display = "block";
                if (businessToggleMobile) businessToggleMobile.dataset.locked = "true";
            }
        });
    }
    if (closeSidebarBtn && sidebar) {
        closeSidebarBtn.addEventListener("click", () => {
            sidebar.classList.remove("active");
            var overlay = document.getElementById("sidebarOverlay");
            if (overlay) overlay.classList.remove("active");
            document.body.style.overflow = '';
            if (businessMenuMobile) {
                businessMenuMobile.style.display = "none";
                if (businessToggleMobile) delete businessToggleMobile.dataset.locked;
            }
        });
    }

    if (businessToggleDesktop && businessMenuDesktop) {
        businessToggleDesktop.addEventListener("click", (e) => {
            e.stopPropagation();
            businessMenuDesktop.classList.toggle("active");
        });

        document.addEventListener("click", (e) => {
            if (!businessToggleDesktop.contains(e.target) && !businessMenuDesktop.contains(e.target)) {
                businessMenuDesktop.classList.remove("active");
            }
        });
    }

    if (businessToggleMobile && businessMenuMobile) {
        businessToggleMobile.addEventListener("click", (e) => {
            if (sidebar.classList.contains("active") && businessToggleMobile.dataset.locked === "true") {
                e.preventDefault();
                return;
            }
            businessMenuMobile.style.display = businessMenuMobile.style.display === "block" ? "none" : "block";
        });
    }

    function setupCityDropdown(toggleId, menuId, searchId, optionSelector, selectedId) {
        const toggle = document.getElementById(toggleId);
        const menu = document.getElementById(menuId);
        const search = document.getElementById(searchId);
        if (!toggle || !menu) return;

        const selected = document.getElementById(selectedId);

        toggle.addEventListener("click", () => {
            menu.classList.toggle("active");
            if (search) search.focus();
        });

        if (search) {
            search.addEventListener("keyup", () => {
                const filter = search.value.toLowerCase();
                const options = menu.querySelectorAll(optionSelector);
                options.forEach((opt) => {
                    opt.style.display = opt.textContent.toLowerCase().includes(filter) ? "block" : "none";
                });
            });
        }

        document.addEventListener("click", (e) => {
            if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.remove("active");
            }
        });
    }

    setupCityDropdown("cityToggleDesktop", "cityMenuDesktop", "citySearchDesktop", ".option", "selectedCityDesktop");
    setupCityDropdown("cityToggleMobile", "cityMenuMobile", "citySearchMobile", ".option", "selectedCityMobile");

    // ==========================================
    // 5. MEGA MENU SCROLL SECTIONS
    // ==========================================
    var links = document.querySelectorAll(".scroll-menu a[data-menu]");
    var menus = document.querySelectorAll(".mega-menu");

    function hideAllMega() {
        const hadOpenMegaMenu = Array.from(menus).some((menu) => menu.classList.contains("active"));
        menus.forEach((menu) => menu.classList.remove("active"));
        links.forEach((link) => link.classList.remove("active"));
        if (window.innerWidth <= 768 && hadOpenMegaMenu) {
            document.body.style.overflow = '';
        }
    }

    links.forEach((link) => {
        const menuId = link.getAttribute("data-menu");
        const menu = document.getElementById(menuId);
        link.addEventListener("click", (e) => {
            e.preventDefault();
            if (!menu) return;
            const isActive = menu.classList.contains("active");
            hideAllMega();
            if (!isActive) {
                menu.classList.add("active");
                link.classList.add("active");
                if (window.innerWidth <= 768) {
                    document.body.style.overflow = 'hidden';
                }
            }
        });
    });

    document.addEventListener("click", (e) => {
        if (!e.target.closest(".scroll-menu") && !e.target.closest(".mega-menu")) {
            hideAllMega();
        }
    });

    runWithJQuery(function($) {
        // ==========================================
        // 6. ENQUIRY FORM SELECT2 + AJAX STORE
        // ==========================================
        $('#city_header').on('change', function() {
            $('#category_header').html("<option selected value='0'>Select Category</option>");
            if (this.value > 0) {
                var ajax_url = '/ajax/categories/' + this.value;
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });
                $.ajax({
                    url: ajax_url,
                    method: 'get',
                    data: {},
                    success: function(result) {
                        $('#category_header').html("<option selected value='0'>Select Category</option>");
                        $.each((typeof result === 'string' ? JSON.parse(result) : result), function(key, value) {
                            $('#category_header').append('<option value="' + value.id + '">' + value.category_name + '</option>');
                        });
                    }
                });
            }
        });

        function initDynamicSelect2(selector, url, dropdownParent, placeholder, formatData, minInputLength = 0, allowTags = false) {
            let select2Config = {
                placeholder: placeholder,
                allowClear: true,
                tags: allowTags,
                ajax: {
                    url: url,
                    dataType: 'json',
                    delay: 300,
                    data: formatData,
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                return { id: item.id, text: item.text || item.title || item.city_name };
                            })
                        };
                    },
                    cache: false,
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error loading data for ' + selector, textStatus, errorThrown);
                    }
                },
                width: '100%',
                dropdownParent: $(dropdownParent),
                minimumInputLength: minInputLength,
                language: {
                    noResults: function() { return "No results found"; },
                    searching: function() { return "Searching..."; }
                }
            };

            if (allowTags) {
                select2Config.createTag = function (params) {
                    var term = $.trim(params.term);
                    if (term === '') {
                        return null;
                    }
                    return {
                        id: term,
                        text: term,
                        newTag: true
                    };
                };
            }

            $(selector).select2(select2Config);
        }

        function initializeEnquirySelect2() {
            // If Select2 is not ready yet, return
            if (!$.fn.select2) {
                return;
            }

            if ($('#city_modal').hasClass('select2-hidden-accessible')) {
                $('#city_modal').select2('destroy');
            }
            if ($('#product').hasClass('select2-hidden-accessible')) {
                $('#product').select2('destroy');
            }

            // Clear options first
            $('#city_modal').html('<option value="">Select Location</option>');
            $('#product').html('<option value="">Select Product</option>');

            @auth
                $('#name').val('{{ Auth::user()->name ?? '' }}');
                $('#mobile').val('{{ isset(Auth::user()->phone) ? remove_country_code(Auth::user()->phone) : '' }}');
            @endauth

            initDynamicSelect2('#city_modal', "{{ route('ajax.cities.enquiry') }}", '#enquiryModal', 'Select Location', function(params) {
                return { q: params.term };
            }, 2);

            initDynamicSelect2('#product', "{{ route('ajax.products') }}", '#enquiryModal', 'Select Product', function(params) {
                return { q: params.term, location: $('#city_modal').val() };
            }, 0, true);

            // 1. Pre-populate Location (City) after Select2 initialization
            @if (isset($urlCityId) && isset($urlCityName) && !empty($urlCityId))
                var pageCityOption = new Option('{{ addslashes($urlCityName) }}', '{{ $urlCityId }}_0', true, true);
                $('#city_modal').append(pageCityOption).trigger('change');
            @else
                @auth
                    @if (Auth::user() && Auth::user()->city_id)
                        var userCityOption = new Option('{{ optional(Auth::user()->city)->city_name ?? '' }}', '{{ Auth::user()->city_id ?? '' }}_0', true, true);
                        $('#city_modal').append(userCityOption).trigger('change');
                    @endif
                @endauth
            @endif

            // 2. Pre-populate Product if window.pageProductDetails is defined
            if (window.pageProductDetails && window.pageProductDetails.id) {
                var prodOption = new Option(window.pageProductDetails.title, window.pageProductDetails.id, true, true);
                $('#product').append(prodOption).trigger('change');
            }
        }

        $('#enquiryModal').on('shown.bs.modal', function() {
            initializeEnquirySelect2();
        });

        // Periodic check to initialize Select2 when the modal becomes visible
        setInterval(function() {
            if ($('#enquiryModal').length && $('#enquiryModal').is(':visible')) {
                if (!$('#city_modal').hasClass('select2-hidden-accessible')) {
                    initializeEnquirySelect2();
                }
            }
        }, 300);

        $('#city_modal').on('change', function() {
            let locationVal = $(this).val();
            $('#product').val(null).trigger('change');
            if (locationVal) {
                $('#product').prop('disabled', false).trigger('change');
            } else {
                $('#product').prop('disabled', true).trigger('change');
            }
        });

        $('#enquiryModal').on('hidden.bs.modal', function() {
            if ($('#enquiryForm').length) $('#enquiryForm')[0].reset();
            if ($('#city_modal').hasClass('select2-hidden-accessible')) {
                $('#city_modal').val(null).trigger('change');
                $('#city_modal').select2('destroy');
            }
            if ($('#product').hasClass('select2-hidden-accessible')) {
                $('#product').val(null).trigger('change');
                $('#product').select2('destroy');
            }
            $('#product').prop('disabled', true);
        });

        $('#enquiryForm').on('submit', function(event) {
            event.preventDefault();
            
            const showAlert = (icon, title, text) => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: icon, title: title, text: text });
                } else {
                    alert(title + ': ' + text);
                }
            };

            let locationVal = $('#city_modal').val();
            if (!locationVal) {
                showAlert('warning', 'Validation Error', 'Please select a valid location.');
                return;
            }

            let city_id = locationVal.toString().split('_')[0];
            let area_id = locationVal.toString().split('_')[1] || '0';
            if (!city_id || isNaN(city_id)) {
                showAlert('error', 'Invalid Location', 'The selected location is invalid. Please try again.');
                return;
            }

            let product_val = $('#product').val();
            if (!product_val) {
                showAlert('warning', 'Validation Error', 'Please select or type a product.');
                return;
            }

            let product_id = null;
            let custom_product = null;
            if ($.isNumeric(product_val)) {
                product_id = product_val;
            } else {
                custom_product = product_val;
            }

            let csrfToken = $('#enquiryForm input[name="_token"]').val()
                         || $('meta[name="csrf-token"]').attr('content')
                         || '{{ csrf_token() }}';

            let formData = {
                _token: csrfToken,
                name: $('#name').val(),
                mobile: $('#mobile').val(),
                city_id: city_id,
                area_id: area_id,
                product_id: product_id,
                custom_product: custom_product,
            };

            $.ajax({
                url: "{{ route('enquiry.store') }}",
                method: "POST",
                data: formData,
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function(response) {
                    showAlert('success', 'Submitted!', response.message);
                    $('#enquiryForm')[0].reset();
                    $('#enquiryModal').modal('hide');
                },
                error: function(xhr) {
                    let errorMessage = 'Something went wrong!';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join(', ');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showAlert('error', 'Oops...', errorMessage);
                }
            });
        });

        // ==========================================
        // 7. GLOBAL SEARCH AUTOCOMPLETE (DESKTOP + MOBILE)
        // ==========================================
        var inMemoryCache = {};
        function getCache(key) {
            try {
                var cached = sessionStorage.getItem('cha_autocomplete_cache_' + key);
                return cached ? (typeof cached === 'string' ? JSON.parse(cached) : cached) : null;
            } catch (e) {
                return inMemoryCache[key] || null;
            }
        }

        function setCache(key, value) {
            try {
                sessionStorage.setItem('cha_autocomplete_cache_' + key, JSON.stringify(value));
            } catch (e) {
                inMemoryCache[key] = value;
            }
        }

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-inner').length) {
                $('.search-suggestions-dropdown').remove();
            }
        });

        function filterCachedData(data, query) {
            var q = query.toLowerCase().trim();
            return {
                pages: (data.pages || []).filter(function(page) {
                    return page.title && page.title.toLowerCase().indexOf(q) !== -1;
                }),
                marketplace: (data.marketplace || []).filter(function(item) {
                    return item.title && item.title.toLowerCase().indexOf(q) !== -1;
                }),
                events: (data.events || []).filter(function(event) {
                    return event.title && event.title.toLowerCase().indexOf(q) !== -1;
                }),
                blogs: (data.blogs || []).filter(function(blog) {
                    return blog.title && blog.title.toLowerCase().indexOf(q) !== -1;
                }),
                users: (data.users || []).filter(function(user) {
                    return user.name && user.name.toLowerCase().indexOf(q) !== -1;
                })
            };
        }

        function setupAutocomplete(inputSelector, formSelector, cityInputSelector) {
            var $input = $(inputSelector);
            var $form = $(formSelector);
            if (!$input.length) return;

            $input.attr('autocomplete', 'off');
            var xhr = null;
            var debounceTimer = null;

            function renderDropdown($dropdown, data, query) {
                $dropdown.empty();
                var items = [];
            
                if (data.pages && data.pages.length) {
                    data.pages.forEach(function(page) {
                        items.push({
                            id: page.id,
                            title: page.title,
                            url: `/${page.city_slug}/${page.area_slug}/${page.category_slug}/${page.item_slug}`,
                            category: page.category_name || 'Business',
                            area: page.area_slug ? page.area_slug.replace(/-/g, ' ') : '',
                            icon: 'fa-search'
                        });
                    });
                }

                if (query.trim().length > 0) {
                    if (data.marketplace && data.marketplace.length) {
                        data.marketplace.forEach(function(item) {
                            items.push({
                                id: item.id,
                                title: item.title,
                                url: `/product/filter?search=${encodeURIComponent(item.title)}`,
                                category: 'Deals/Product',
                                icon: 'fa-shopping-bag'
                            });
                        });
                    }
                    if (data.events && data.events.length) {
                        data.events.forEach(function(event) {
                            items.push({
                                id: event.id,
                                title: event.title,
                                url: `/events?title=${encodeURIComponent(event.title)}`,
                                category: 'Event',
                                icon: 'fa-calendar-alt'
                            });
                        });
                    }
                    if (data.blogs && data.blogs.length) {
                        data.blogs.forEach(function(blog) {
                            items.push({
                                id: blog.id,
                                title: blog.title,
                                url: `/blogs?title=${encodeURIComponent(blog.title)}`,
                                category: 'Blog',
                                icon: 'fa-file-alt'
                            });
                        });
                    }
                }

                var labelText = (query.trim().length > 0) ? 'Search Results' : 'Suggested Businesses';
                $dropdown.append($('<div class="search-suggestion-section-label">' + labelText + '</div>'));

                if (items.length === 0) {
                    $dropdown.append('<div class="p-3 text-muted text-center">No results found</div>');
                    return;
                }

                items.forEach(function(item) {
                    var icon = item.icon || 'fa-search';
                    var subtitle = item.category;
                    if (item.area) {
                        subtitle += ' • ' + item.area;
                    }

                    var $itemEl = $(`
                        <div class="search-suggestion-item">
                            <div class="search-suggestion-icon-badge">
                                <i class="fas ${icon}"></i>
                            </div>
                            <div class="search-suggestion-content">
                                <span class="search-suggestion-title">${item.title}</span>
                                <span class="search-suggestion-subtitle">${subtitle}</span>
                            </div>
                        </div>
                    `);

                    $itemEl.on('mousedown click', function(e) {
                        e.preventDefault();
                        window.location.href = item.url;
                    });

                    $itemEl.on('mouseenter', function() {
                        var targetUrl = item.url;
                        if (targetUrl && targetUrl.startsWith('/') && !targetUrl.startsWith('//')) {
                            var absoluteUrl = window.location.origin + targetUrl;
                            window.prefetchedUrls = window.prefetchedUrls || new Set();
                            if (!window.prefetchedUrls.has(absoluteUrl)) {
                                window.prefetchedUrls.add(absoluteUrl);

                                if (HTMLScriptElement.supports && HTMLScriptElement.supports('speculationrules')) {
                                    var specScript = document.createElement('script');
                                    specScript.type = 'speculationrules';
                                    specScript.textContent = JSON.stringify({
                                        prerender: [{ source: 'list', urls: [absoluteUrl] }]
                                    });
                                    document.head.appendChild(specScript);
                                } else {
                                    var link = document.createElement('link');
                                    link.rel = 'prefetch';
                                    link.href = absoluteUrl;
                                    document.head.appendChild(link);

                                    var prerenderLink = document.createElement('link');
                                    prerenderLink.rel = 'prerender';
                                    prerenderLink.href = absoluteUrl;
                                    document.head.appendChild(prerenderLink);
                                }
                            }
                        }
                    });

                    $dropdown.append($itemEl);
                });
            }

            function doSearch() {
                var query = $input.val().trim();
                var cityId = $(cityInputSelector).val() || '0';
                var cacheKey = cityId + ':' + query.toLowerCase();

                var $searchInner = $input.closest('.search-inner');
                if (!$searchInner.length) return;

                var $dropdown = $searchInner.find('.search-suggestions-dropdown');
                if (!$dropdown.length) {
                    $dropdown = $('<div class="search-suggestions-dropdown"></div>');
                    $searchInner.append($dropdown);
                }

                if (query.length < 2) {
                    if (xhr) xhr.abort();
                    $dropdown.remove();
                    return;
                }

                var cachedVal = getCache(cacheKey);
                if (cachedVal) {
                    renderDropdown($dropdown, cachedVal.data, query);
                    return;
                }

                if (query.length > 2) {
                    for (var i = query.length - 1; i >= 2; i--) {
                        var parentQuery = query.substring(0, i).toLowerCase();
                        var parentCacheKey = cityId + ':' + parentQuery;
                        var parentCachedVal = getCache(parentCacheKey);
                        if (parentCachedVal && parentCachedVal.isComplete) {
                            var filteredData = filterCachedData(parentCachedVal.data, query);
                            setCache(cacheKey, { data: filteredData, isComplete: true });
                            renderDropdown($dropdown, filteredData, query);
                            return;
                        }
                    }
                }

                if (xhr) xhr.abort();

                var spinnerTimer = setTimeout(function() {
                    $dropdown.html('<div class="p-3 text-muted text-center"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</div>');
                }, 50);

                xhr = $.ajax({
                    url: "{{ route('search.globally') }}",
                    method: 'GET',
                    data: { search: query, cityid: cityId },
                    success: function(data) {
                        clearTimeout(spinnerTimer);
                        var isComplete = true;
                        if (data.pages && data.pages.length >= 50) isComplete = false;
                        if (data.marketplace && data.marketplace.length >= 50) isComplete = false;
                        if (data.events && data.events.length >= 50) isComplete = false;
                        if (data.blogs && data.blogs.length >= 50) isComplete = false;

                        setCache(cacheKey, { data: data, isComplete: isComplete });
                        renderDropdown($dropdown, data, query);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        clearTimeout(spinnerTimer);
                        if (textStatus === 'abort') return;
                        $dropdown.remove();
                    }
                });
            }

            $input.on('focus click', function() { doSearch(); });

            $input.on('input keyup', function(e) {
                if ([37, 38, 39, 40, 13].indexOf(e.which) !== -1) return;
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() { doSearch(); }, 250);
            });

            $input.on('blur', function() {
                setTimeout(function() { $searchInner.find('.search-suggestions-dropdown').remove(); }, 200);
            });
        }

        setupAutocomplete('#search-box', '#search-form', '#city_header');
        setupAutocomplete('#mobile-search-box', '#mobile-search-form', '#mobile_city_slug');
    });

    // ==========================================
    // 8. SEARCH FORM (NON-SELECT2 FALLBACK)
    // ==========================================
    function toggleCityDropdown() {
        const dropdown = document.getElementById("cityDropdown");
        if (dropdown) dropdown.classList.toggle("hidden");
    }

    function selectCity(slug, name) {
        if (document.getElementById("selectedCity")) document.getElementById("selectedCity").textContent = name;
        if (document.getElementById("city_header_main")) document.getElementById("city_header_main").value = slug;
        if (document.getElementById("cityDropdown")) document.getElementById("cityDropdown").classList.add("hidden");
    }

    const desktopForm = document.getElementById('search-form');
    if (desktopForm) {
        desktopForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const citySlug = document.getElementById('city_header_main') ? document.getElementById('city_header_main').value : '';
            const search = document.getElementById('search-box') ? document.getElementById('search-box').value : '';
            if (!citySlug) { alert('Please select a city.'); return; }
            let url = `/${citySlug}`;
            if (search) url += `?search=${encodeURIComponent(search)}`;
            window.location.href = url;
        });
    }

    const mobileForm = document.getElementById('mobile-search-form');
    if (mobileForm) {
        mobileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const citySlug = document.getElementById('mobile_city_slug') ? document.getElementById('mobile_city_slug').value : '';
            const search = document.getElementById('mobile-search-box') ? document.getElementById('mobile-search-box').value : '';
            if (!citySlug) { alert('Please select a city.'); return; }
            let url = `/${citySlug}`;
            if (search) url += `?search=${encodeURIComponent(search)}`;
            window.location.href = url;
        });
    }

    window.addEventListener("click", function(e) {
        const btn = document.getElementById("cityDropdownButton");
        const dd  = document.getElementById("cityDropdown");
        if (!btn || !dd) return;
        if (!btn.contains(e.target) && !dd.contains(e.target)) {
            dd.classList.add("hidden");
        }
    });

    // ==========================================
    // 9. GENERIC NAV DROPDOWNS (MOBILE & DESKTOP HOVER)
    // ==========================================
    const navDropdowns = document.querySelectorAll('.nav-dropdown');
    navDropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.nav-dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');

        if (toggle && menu) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                navDropdowns.forEach(otherDropdown => {
                    if (otherDropdown !== dropdown) {
                        otherDropdown.classList.remove('show');
                        const otherMenu = otherDropdown.querySelector('.dropdown-menu');
                        if (otherMenu) otherMenu.classList.remove('show');
                    }
                });

                dropdown.classList.toggle('show');
                menu.classList.toggle('show');
            });

            if (window.innerWidth >= 992) {
                dropdown.addEventListener('mouseenter', function() {
                    navDropdowns.forEach(otherDropdown => {
                        if (otherDropdown !== dropdown) {
                            otherDropdown.classList.remove('show');
                            const otherMenu = otherDropdown.querySelector('.dropdown-menu');
                            if (otherMenu) otherMenu.classList.remove('show');
                        }
                    });
                    dropdown.classList.add('show');
                    menu.classList.add('show');
                });

                dropdown.addEventListener('mouseleave', function() {
                    setTimeout(() => {
                        if (!dropdown.matches(':hover')) {
                            dropdown.classList.remove('show');
                            menu.classList.remove('show');
                        }
                    }, 100);
                });
            }
        }
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.nav-dropdown')) {
            navDropdowns.forEach(dropdown => {
                dropdown.classList.remove('show');
                const menu = dropdown.querySelector('.dropdown-menu');
                if (menu) menu.classList.remove('show');
            });
        }
    });

    window.addEventListener('resize', function() {
        navDropdowns.forEach(dropdown => {
            dropdown.classList.remove('show');
            const menu = dropdown.querySelector('.dropdown-menu');
            if (menu) menu.classList.remove('show');
        });
    });

    // openEnquiryForm: robust checker and trigger for Bootstrap/jQuery modals
    function openEnquiryForm() {
        var modalEl = document.getElementById('enquiryModal');
        if (!modalEl) return;
        if (window.bootstrap && bootstrap.Modal) {
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        } else if (window.jQuery) {
            jQuery('#enquiryModal').modal('show');
        }
    }

    // ==========================================
    // 10. GLOBAL HOVER PREFETCH & SPECULATIVE PRERENDERING (0-LATENCY NAVIGATION)
    // ==========================================
    window.prefetchedUrls = window.prefetchedUrls || new Set();
    document.body.addEventListener('mouseover', function(e) {
        var anchor = e.target.closest('a');
        if (!anchor) return;
        
        var href = anchor.getAttribute('href');
        if (href && href.startsWith('/') && !href.startsWith('//') && !href.startsWith('#') && 
            !href.includes('/logout') && !href.includes('/admin') && !href.includes('/delete') && 
            !href.includes('/edit') && !href.includes('/destroy') && !href.includes('/post')) {
            
            var cleanUrl = href.split('#')[0];
            var absoluteUrl = window.location.origin + cleanUrl;
            
            if (!window.prefetchedUrls.has(absoluteUrl)) {
                window.prefetchedUrls.add(absoluteUrl);
                
                if (HTMLScriptElement.supports && HTMLScriptElement.supports('speculationrules')) {
                    var specScript = document.createElement('script');
                    specScript.type = 'speculationrules';
                    specScript.textContent = JSON.stringify({
                        prerender: [{ source: 'list', urls: [absoluteUrl] }]
                    });
                    document.head.appendChild(specScript);
                } else {
                    var link = document.createElement('link');
                    link.rel = 'prefetch';
                    link.href = absoluteUrl;
                    document.head.appendChild(link);

                    var prerenderLink = document.createElement('link');
                    prerenderLink.rel = 'prerender';
                    prerenderLink.href = absoluteUrl;
                    document.head.appendChild(prerenderLink);
                }
            }
        }
    });

    // Header navigation and dynamic mega menu loaders
    var menuByCityUrl = "{{ url('/get-menu-by-city') }}";
    var urlCityId = @json($urlCityId ?? null);
    var urlCityName = @json($urlCityName ?? null);

    window.__headerLastCityId = undefined;

    window.loadHeaderMegaMenus = function (cityId) {
        var $ = window.jQuery;
        if (!$) return;

        var loadId = (cityId === null || cityId === undefined || cityId === 'null') ? '' : cityId;
        if (urlCityId && !loadId) return;
        if (window.__headerLastCityId === loadId) return;
        window.__headerLastCityId = loadId;

        jQuery.ajax({
            url: menuByCityUrl,
            method: 'GET',
            data: { city_id: loadId },
            success: function (response) {
                var map = [
                    ['city_guide', '#header-city-guide-grid'],
                    ['marketplace', '#header-marketplace-grid'],
                    ['community', '#header-community-grid'],
                    ['event', '#header-event-grid'],
                    ['blog', '#header-blog-grid']
                ];
                map.forEach(function (pair) {
                    var key = pair[0], sel = pair[1];
                    if (response[key] !== undefined && jQuery(sel).length) {
                        var $grid = jQuery(sel);
                        $grid.html(response[key]);
                        // Move CTA strip OUTSIDE the grid (before it, inside .mega-menu)
                        var $strip = $grid.find('.mega-cta-strip');
                        if ($strip.length) {
                            $grid.parent().find('.mega-cta-strip-wrapper').remove();
                            var $wrapper = jQuery('<div class="mega-cta-strip-wrapper" style="width:100%;flex-shrink:0;"></div>').append($strip);
                            $grid.before($wrapper);
                        }
                    }
                });
            },
            error: function () {
                window.__headerLastCityId = undefined;
            }
        });
    };

    function initHeaderContext() {
        if (window.jQuery) {
            var lsId = localStorage.getItem('selectedCityId');
            var lsName = localStorage.getItem('selectedCityName');
            var legacySelectedCity = null;

            try {
                legacySelectedCity = JSON.parse(localStorage.getItem('selectedCity') || 'null');
            } catch (e) {
                legacySelectedCity = null;
            }

            if ((!lsId || !lsName) && legacySelectedCity) {
                lsId = lsId || legacySelectedCity.id || null;
                lsName = lsName || legacySelectedCity.name || null;
            }

            var isHomepage = window.location.pathname === '/' || window.location.pathname === '';

            if (isHomepage) {
                clearSelectedCityContext();
                lsId = null;
                lsName = null;
            }

            var activeId = isHomepage ? null : (urlCityId || lsId || null);
            var activeName = isHomepage ? 'Select City' : (urlCityName || lsName || 'Select City');

            var d = document.getElementById('selectedCityDesktop');
            var m = document.getElementById('selectedCityMobile');
            
            if (d) d.textContent = activeName;
            if (m) m.textContent = activeName;

            if (urlCityId && urlCityName) {
                localStorage.setItem('selectedCityId', urlCityId);
                localStorage.setItem('selectedCityName', urlCityName);
            }

            var cityInput = document.getElementById("city_header");
            if (cityInput) cityInput.value = activeId || '';
            var mCityInput = document.getElementById("mobile_city_slug");
            if (mCityInput) mCityInput.value = activeId || '';

            window.loadHeaderMegaMenus(activeId);
        } else {
            setTimeout(initHeaderContext, 50);
        }
    }

    initHeaderContext();

    function shouldClearLocationContext(element) {
        if (!element) {
            return false;
        }

        var $element = $(element);
        var className = ($element.attr('class') || '').toLowerCase();
        var text = (($element.text() || $element.val() || '') + '').replace(/\s+/g, ' ').trim().toLowerCase();

        if ($element.is('[data-clear-location-context="true"]')) {
            return true;
        }

        if (className.indexOf('btn-reset') !== -1 || className.indexOf('reset-btn') !== -1 || className.indexOf('btn-action-reset') !== -1) {
            return true;
        }

        return text === 'reset';
    }

    $(document).on('click', 'a, button, input[type="reset"]', function (e) {
        if (!shouldClearLocationContext(this)) {
            return;
        }

        clearSelectedCityContext();

        if ($(this).is('a')) {
            e.preventDefault();

            var targetHref = $(this).attr('href') || '/';

            fetch('/', {
                credentials: 'same-origin',
                cache: 'no-store'
            }).catch(function () {
                return null;
            }).then(function () {
                window.location.href = targetHref;
            });
        } else {
            fetch('/', {
                credentials: 'same-origin',
                cache: 'no-store'
            }).catch(function () {
                return null;
            });
        }
    });

})();
</script>
@endif
