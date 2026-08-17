<!DOCTYPE html>
<html>
<head>
    <title>Menu Demo</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .topbar { display: flex; gap: 20px; padding: 20px; background: #f8f8f8; border-bottom: 1px solid #ddd; align-items: center; }
        
        .city-search-container { position: relative; width: 250px; margin-right: 20px; }
        .city-search-container input { 
            width: 100%; padding: 10px; padding-right: 35px; 
            border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; 
        }

        .city-search-container input[readonly] {
            background-color: #f0f7ff;
            border-color: #007bff;
            font-weight: bold;
            color: #0056b3;
        }

        .clear-city {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-weight: bold;
            color: #dc3545;
            display: none;
            font-size: 20px;
            z-index: 5;
        }

        .city-results { 
            position: absolute; top: 100%; left: 0; right: 0; 
            background: #fff; border: 1px solid #ddd; border-top: none;
            z-index: 1000; max-height: 200px; overflow-y: auto; display: none;
        }

        .city-item { padding: 10px; cursor: pointer; }
        .city-item:hover { background: #f0f0f0; }

        .dropdown { position: relative; }
        .dropdown button { padding: 10px 15px; font-weight: bold; cursor: pointer; }
        .dropdown-content { 
            display: none; position: absolute; top: 100%; left: 0; width: 500px; 
            background: #fff; border: 1px solid #ddd; padding: 15px; z-index: 999; 
            max-height: 300px; overflow-y: auto;
        }

        .grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
        .item-link { text-decoration: none; color: #333; font-size: 13px; display: block; padding: 8px; }
        .item-link:hover { background: #ff4939; color: #fff !important; }
        .count { opacity: 0.6; font-size: 11px; }
    </style>
</head>
<body>

<div class="topbar">

    {{-- CITY SEARCH --}}
    <div class="city-search-container">
        <input type="text" id="citySearchInput" placeholder="Search City..." autocomplete="off">
        <span id="clearCityBtn" class="clear-city">&times;</span>
        <div id="cityResults" class="city-results"></div>
    </div>

    @php
        $menus = [
            ['label' => 'City Guide', 'data' => $newCityGuide, 'id' => 'city-guide-grid'],
            ['label' => 'Marketplace', 'data' => $marketplaceData, 'id' => 'marketplace-grid'],
            ['label' => 'Community', 'data' => $communityData, 'id' => 'community-grid'],
            ['label' => 'Event', 'data' => $eventData, 'id' => 'event-grid']
        ];
    @endphp

    @foreach($menus as $menu)
    <div class="dropdown">
        <button>{{ $menu['label'] }} ▼</button>
        <div class="dropdown-content">
            <div class="grid" id="{{ $menu['id'] }}">
                @foreach($menu['data'] as $item)
                <div>
                    <a href="#" class="item-link">
                        {{ $item->name }} 
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach

    {{-- BLOG --}}
    <div class="dropdown">
        <button>Blog ▼</button>
        <div class="dropdown-content">
            <div class="grid" id="blog-grid">
                @foreach($blogData as $item)
                <div>
                    <a href="#" class="item-link">
                        {{ $item->name }} ({{ $item->count }})
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {

    let isCitySelected = false;

    // 🔥 AUTO LOAD SAVED CITY
    let savedCityId = localStorage.getItem('selectedCityId');
    let savedCityName = localStorage.getItem('selectedCityName');
    let savedCitySlug = localStorage.getItem('selectedCitySlug');

    if (savedCityId && savedCityName) {
        isCitySelected = true;

        $('#citySearchInput')
            .val(savedCityName)
            .prop('readonly', true);

        $('#clearCityBtn').show();

        loadMenu(savedCityId, savedCitySlug);
    }

    // SEARCH
    $('#citySearchInput').on('keyup', function() {
        if (isCitySelected) return;

        let term = $(this).val();
        if (term.length >= 2) {
            $.get("/get-smart-cities", { term: term }, function(data) {
                let html = '';
                data.forEach(city => {
                    html += `<div class="city-item" onclick="updateMenuForCity(${city.id}, '${city.city_name}', '${city.city_slug}')">${city.city_name}</div>`;
                });
                $('#cityResults').html(html).show();
            });
        } else {
            $('#cityResults').hide();
        }
    });

    // SELECT CITY
    window.updateMenuForCity = function(cityId, cityName, citySlug) {

        isCitySelected = true;

        // SAVE 🔥
        localStorage.setItem('selectedCityId', cityId);
        localStorage.setItem('selectedCityName', cityName);
        localStorage.setItem('selectedCitySlug', citySlug);
        localStorage.setItem('selectedCity', JSON.stringify({ id: cityId, name: cityName, slug: citySlug }));

        $('#citySearchInput').val(cityName).prop('readonly', true);
        $('#cityResults').hide();
        $('#clearCityBtn').show();

        loadMenu(cityId, citySlug);
    };

    // CLEAR CITY
    $('#clearCityBtn').on('click', function() {

        isCitySelected = false;

        // REMOVE 🔥
        if (window.clearSelectedCityContext) {
            window.clearSelectedCityContext();
        } else {
            localStorage.removeItem('selectedCityId');
            localStorage.removeItem('selectedCityName');
            localStorage.removeItem('selectedCitySlug');
            localStorage.removeItem('selectedCity');
        }

        fetch('/', {
            credentials: 'same-origin',
            cache: 'no-store'
        }).catch(function () {
            return null;
        });

        $('#citySearchInput').val('').prop('readonly', false).focus();
        $(this).hide();

        loadMenu(null, 'all');
    });

    function loadMenu(cityId, citySlug) {
        $.ajax({
            url: "/get-menu-by-city",
            method: "GET",
            data: { city_id: cityId, city_slug: citySlug },
            beforeSend: function() { $('.grid').css('opacity', '0.4'); },
            success: function(response) {
                $('.grid').css('opacity', '1');
                $('#city-guide-grid').html(response.city_guide);
                $('#marketplace-grid').html(response.marketplace);
                $('#community-grid').html(response.community);
                $('#event-grid').html(response.event);
                $('#blog-grid').html(response.blog);
            }
        });
    }

    // CLOSE DROPDOWN
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.city-search-container').length) {
            $('#cityResults').hide();
        }
    });

    // DROPDOWN HOVER
    $('.dropdown').hover(
        function() { $(this).find('.dropdown-content').stop(true, true).fadeIn(200); },
        function() { $(this).find('.dropdown-content').stop(true, true).fadeOut(200); }
    );

});
</script>

</body>
</html>
