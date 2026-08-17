@php
    $sponsorPost = \Cache::remember('sidebar_sponsors_listing_v4', 3600, function () {
        return \App\Models\Sponsor::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('status', 1)
            ->orderByDesc('id')
            ->limit(5)
            ->get();
    });

    $featuredProducts = \Cache::remember('sidebar_featured_products_v11_' . ($city->id ?? 'global') . '_' . ($area->id ?? 'all'), 3600, function() use ($city, $area) {
        $query = \DB::table('marketplaces')
            ->join('pages', function($join) {
                $join->on('pages.id', '=', 'marketplaces.page_id')
                     ->where('pages.item_status', '=', '2');
            })
            ->join('cities', 'cities.id', '=', 'pages.city_id')
            ->join('areas', 'areas.id', '=', 'pages.area_id')
            ->leftJoin('pagecategories', function($join) {
                    $join->on(\DB::raw('pagecategories.id'), '=', \DB::raw('CAST(NULLIF(SPLIT_PART(pages.category_id, \',\', 1), \'\') AS BIGINT)'));
                })
            ->leftJoin('currencies', 'currencies.id', '=', 'marketplaces.currency_id')
            ->where('marketplaces.product_status', 2)
            ->select(
                'marketplaces.id',
                'marketplaces.title',
                'marketplaces.product_slug',
                'marketplaces.image',
                'marketplaces.product_selling_price',
                'currencies.symbol as currency_symbol',
                'cities.city_slug',
                'cities.city_name',
                'areas.area_slug',
                'areas.area_name',
                \DB::raw('MAX(pagecategories.category_slug) as page_category_slug'),
                'pages.item_slug as page_slug',
                \DB::raw('(SELECT categories.product_category_slug 
                           FROM category_product 
                           JOIN categories ON categories.id = category_product.product_category_id 
                           WHERE category_product.product_id = marketplaces.id 
                           LIMIT 1) as product_category_slug')
            )->groupBy(
                'marketplaces.id',
                'marketplaces.title',
                'marketplaces.product_slug',
                'marketplaces.image',
                'marketplaces.product_selling_price',
                'currencies.symbol',
                'cities.city_slug',
                'cities.city_name',
                'areas.area_slug',
                'areas.area_name',
                'pages.item_slug'
            );
        
        if (isset($city->id)) {
            $cityQuery = (clone $query)->where('pages.city_id', $city->id);

            if (isset($area->id)) {
                $areaProducts = (clone $cityQuery)->where('pages.area_id', $area->id)
                    ->orderByDesc(\DB::raw('MAX(CAST(marketplaces.item_featured AS INTEGER))'))
                    ->orderByDesc('marketplaces.id')
                    ->limit(6)
                    ->get();

                if ($areaProducts->isNotEmpty()) {
                    return $areaProducts;
                }
            }

            return $cityQuery
                ->orderByDesc(\DB::raw('MAX(CAST(marketplaces.item_featured AS INTEGER))'))
                ->orderByDesc('marketplaces.id')
                ->limit(6)
                ->get();
        }
        return $query->orderByDesc(\DB::raw('MAX(CAST(marketplaces.item_featured AS INTEGER))'))->orderByDesc('marketplaces.id')->limit(6)->get();
    });
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    :root {
        --primary-color: #ff4d5a;
        --secondary-color: #2563eb;
        --dark-bg: #0f172a;
        --slate-text: #334155;
        --light-slate: #64748b;
        --premium-shadow: 0 12px 30px -10px rgba(15, 23, 42, 0.08);
        --btn-shadow: 0 4px 15px rgba(255, 77, 90, 0.2);
    }

    .single-event-wrap {
        font-family: 'Outfit', sans-serif !important;
        color: var(--slate-text);
        background: #f8fafc;
        padding-bottom: 60px;
    }

    /* Breadcrumbs */
    .single-event-wrap .breadcrumb {
        background: transparent !important;
        padding: 16px 0 !important;
        margin-bottom: 20px !important;
        font-size: 14px;
        display: flex;
        flex-wrap: wrap;
        list-style: none;
    }
    .single-event-wrap .breadcrumb-item a {
        color: var(--light-slate);
        text-decoration: none;
        transition: color 0.2s ease;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .single-event-wrap .breadcrumb-item a:hover {
        color: var(--primary-color);
    }
    .single-event-wrap .breadcrumb-item.active {
        color: #0f172a;
        font-weight: 600;
    }
    .single-event-wrap .breadcrumb-item + .breadcrumb-item::before {
        color: #cbd5e1;
    }

    /* Cover Image Container */
    .event-cover-container {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.15);
        background: var(--dark-bg);
        aspect-ratio: 21 / 9;
        min-height: 250px;
        max-height: 420px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .event-cover-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .event-cover-container:hover img {
        transform: scale(1.03);
    }
    .event-cover-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(15, 23, 42, 0) 30%, rgba(15, 23, 42, 0.7) 100%);
        pointer-events: none;
    }

    /* Floating Date Badge */
    .floating-date-badge {
        position: absolute;
        top: 24px;
        left: 24px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        padding: 10px 18px;
        border-radius: 18px;
        box-shadow: 0 12px 30px -5px rgba(15, 23, 42, 0.2);
        z-index: 5;
        text-align: center;
        min-width: 80px;
        transition: transform 0.3s ease;
    }
    .floating-date-badge:hover {
        transform: translateY(-2px);
    }
    .floating-date-badge .day {
        display: block;
        font-size: 28px;
        font-weight: 800;
        color: var(--primary-color);
        line-height: 1;
    }
    .floating-date-badge .month {
        display: block;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--light-slate);
        margin-top: 4px;
        letter-spacing: 0.5px;
    }

    /* Floating View Count */
    .floating-views {
        position: absolute;
        bottom: 24px;
        right: 24px;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        color: #ffffff;
        padding: 8px 18px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        z-index: 5;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
    }

    /* Gradient Cover Fallback */
    .default-event-gradient {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #db2777 100%);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        padding: 40px;
    }
    .default-event-gradient::before,
    .default-event-gradient::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.45;
    }
    .default-event-gradient::before {
        width: 250px;
        height: 250px;
        background: #f43f5e;
        top: -30px;
        left: -30px;
        animation: floatBlob1 8s ease-in-out infinite alternate;
    }
    .default-event-gradient::after {
        width: 300px;
        height: 300px;
        background: #06b6d4;
        bottom: -60px;
        right: -60px;
        animation: floatBlob2 10s ease-in-out infinite alternate;
    }
    @keyframes floatBlob1 {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(30px, 15px) scale(1.1); }
    }
    @keyframes floatBlob2 {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(-20px, -30px) scale(0.95); }
    }

    .default-event-gradient .gradient-content {
        position: relative;
        z-index: 2;
    }
    .default-event-gradient .gradient-content i {
        font-size: 58px;
        margin-bottom: 16px;
        display: block;
        opacity: 0.95;
        animation: floatAnim 3s ease-in-out infinite;
    }
    .default-event-gradient .gradient-content h3 {
        font-size: 32px;
        font-weight: 800;
        margin: 0;
        letter-spacing: -0.5px;
        text-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    @keyframes floatAnim {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    /* Event Info Card */
    .event-details-card {
        background: #ffffff;
        border-radius: 24px;
        padding: 32px;
        box-shadow: var(--premium-shadow);
        border: 1px solid var(--border-color);
        margin-bottom: 24px;
    }
    .event-details-card .event-time-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff1f2;
        color: var(--primary-color);
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 18px;
    }
    .event-details-card h1 {
        font-size: 32px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.25;
        margin-bottom: 16px;
        letter-spacing: -0.5px;
    }
    .event-details-card .event-location {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--slate-text);
        font-size: 16px;
        font-weight: 500;
    }
    .event-details-card .event-location i {
        font-size: 18px;
    }

    /* Tabs Nav styling */
    .ct-tabs-container {
        background: #f1f5f9;
        border-radius: 16px;
        padding: 5px;
        margin-bottom: 24px;
        display: inline-flex;
        border: 1px solid var(--border-color);
    }
    .ct-tabs-container .nav-tabs {
        border: none !important;
        display: flex;
        gap: 4px;
    }
    .ct-tabs-container .nav-link {
        border: none !important;
        background: transparent !important;
        color: var(--light-slate);
        font-weight: 600;
        padding: 10px 24px;
        border-radius: 12px;
        transition: all 0.2s ease;
        font-size: 14px;
    }
    .ct-tabs-container .nav-link:hover {
        color: #0f172a;
    }
    .ct-tabs-container .nav-link.active {
        background: #ffffff !important;
        color: var(--primary-color) !important;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
    }

    /* Tab Content Box */
    .tab-content-container {
        background: #ffffff;
        border-radius: 24px;
        padding: 32px;
        box-shadow: var(--premium-shadow);
        border: 1px solid var(--border-color);
    }
    .tab-content-container h3 {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 18px;
        letter-spacing: -0.3px;
    }
    .tab-content-container p {
        color: var(--slate-text);
        line-height: 1.8;
        font-size: 16px;
        margin: 0;
    }

    /* Sidebar Widgets */
    .premium-widget {
        background: #ffffff;
        border-radius: 24px;
        padding: 28px;
        box-shadow: var(--premium-shadow);
        border: 1px solid var(--border-color);
        margin-bottom: 24px;
    }
    .premium-widget .widget-title {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 20px;
        position: relative;
        padding-left: 14px;
        letter-spacing: -0.3px;
    }
    .premium-widget .widget-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 4px;
        bottom: 4px;
        width: 4px;
        background: var(--primary-color);
        border-radius: 4px;
    }

    /* Action Buttons in Widgets */
    .btn-action-primary {
        background: linear-gradient(135deg, #ff6b76 0%, #ff4d5a 100%);
        color: #ffffff !important;
        border: none;
        border-radius: 14px;
        padding: 14px 24px;
        font-weight: 700;
        width: 100%;
        display: block;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: var(--btn-shadow);
    }
    .btn-action-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 77, 90, 0.35);
    }
    .btn-action-primary:active {
        transform: translateY(0);
    }
    .btn-action-secondary {
        background: #f1f5f9;
        color: var(--slate-text) !important;
        border: none;
        border-radius: 14px;
        padding: 14px 24px;
        font-weight: 700;
        width: 100%;
        display: block;
        text-decoration: none;
        transition: all 0.25s ease;
    }
    .btn-action-secondary:hover {
        background: #e2e8f0;
        color: #0f172a !important;
        transform: translateY(-1px);
    }

    /* Guest Stats Grid */
    .guest-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 5px;
    }
    .guest-stat-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 18px 12px;
        text-align: center;
        border: 1px solid var(--border-color);
        transition: transform 0.2s ease;
    }
    .guest-stat-card:hover {
        transform: translateY(-2px);
        border-color: #cbd5e1;
    }
    .guest-stat-card .num {
        display: block;
        font-size: 28px;
        font-weight: 800;
        color: var(--primary-color);
        line-height: 1.2;
    }
    .guest-stat-card .label {
        font-size: 13px;
        color: var(--light-slate);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 4px;
    }

    /* Invite Search Input */
    .invite-search-container {
        position: relative;
        margin-bottom: 16px;
    }
    .invite-search-container input {
        width: 100%;
        padding: 12px 16px 12px 42px;
        border-radius: 14px;
        border: 1px solid #cbd5e1;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.25s ease;
        background: #f8fafc;
    }
    .invite-search-container input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(255, 77, 90, 0.15);
        outline: none;
        background: #ffffff;
    }
    .invite-search-container .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--light-slate);
        font-size: 15px;
    }

    /* Guest Profile List Items */
    .invite-friend-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-color);
    }
    .invite-friend-item:last-child {
        border-bottom: none;
    }
    .invite-friend-item img {
        border: 2px solid #ffffff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    
    /* Popular Events Cards */
    .popular-event-card {
        display: flex;
        gap: 14px;
        padding: 14px;
        border-radius: 18px;
        border: 1px solid var(--border-color);
        margin-bottom: 14px;
        transition: all 0.25s ease;
    }
    .popular-event-card:last-child {
        margin-bottom: 0;
    }
    .popular-event-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }
    .popular-event-card img {
        width: 75px;
        height: 75px;
        object-fit: cover;
        border-radius: 12px;
    }
    .popular-event-card .info {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
    }
    .popular-event-card .cat {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 4px;
        letter-spacing: 0.5px;
    }
    .popular-event-card h4 {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
        line-height: 1.4;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .popular-event-card h4 a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s;
    }
    .popular-event-card h4 a:hover {
        color: var(--primary-color);
    }
    .popular-event-card .date {
        font-size: 12px;
        color: var(--light-slate);
        font-weight: 500;
    }

    .displaynone {
        display: none !important;
    }

    .rsvp-guest-prompt p {
        font-weight: 500;
        line-height: 1.5;
    }

    /* Mobile Adaptability */
    @media (max-width: 991px) {
        .event-cover-container {
            aspect-ratio: 16 / 9;
            border-radius: 20px;
        }
        .floating-date-badge {
            top: 16px;
            left: 16px;
            padding: 8px 14px;
            min-width: 65px;
        }
        .floating-date-badge .day {
            font-size: 24px;
        }
        .floating-date-badge .month {
            font-size: 10px;
        }
        .event-details-card {
            padding: 20px;
            border-radius: 20px;
        }
        .event-details-card h1 {
            font-size: 24px;
        }
        .tab-content-container {
            padding: 20px;
            border-radius: 20px;
        }
    }
    
    @media (max-width: 575px) {
        .event-cover-container {
            aspect-ratio: 4 / 3;
            min-height: 200px;
        }
        .event-details-card {
            padding: 16px 12px;
            border-radius: 16px;
        }
        .event-details-card h1 {
            font-size: 20px;
        }
        .tab-content-container {
            padding: 16px 10px;
            border-radius: 16px;
        }
        .floating-views {
            bottom: 16px;
            right: 16px;
            padding: 6px 12px;
            font-size: 11px;
        }
        .ct-tabs-container {
            display: flex;
            width: 100%;
            border-radius: 12px;
        }
        .ct-tabs-container .nav-tabs {
            width: 100%;
        }
        .ct-tabs-container .nav-item {
            flex: 1;
            text-align: center;
        }
        .ct-tabs-container .nav-link {
            width: 100%;
            padding: 8px 5px;
            font-size: 13px;
        }
    }

    .sponsor-item-link {
        transition: transform 0.2s ease;
    }
    .sponsor-item-link:hover {
        transform: translateY(-2px);
    }

    /* Mobile optimization for posts inside discussion tab */
    @media (max-width: 575px) {
        .discuss-wrap .single-entry {
            border-radius: 16px !important;
            margin-bottom: 16px !important;
            border-color: rgba(226, 232, 240, 0.8) !important;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.03) !important;
        }
        .discuss-wrap .single-entry .entry-inner {
            padding: 14px 12px !important;
        }
        .discuss-wrap .ava-desc {
            margin-left: 10px !important;
        }
        .discuss-wrap .ava-desc h3 {
            font-size: 14px !important;
            font-weight: 600 !important;
            display: flex !important;
            align-items: center !important;
            flex-wrap: wrap !important;
            gap: 6px !important;
            margin-bottom: 4px !important;
        }
        .discuss-wrap .ava-desc h3 a.text-black {
            font-weight: 700 !important;
            color: #0f172a !important;
        }
        /* Style the Follow/Unfollow links as premium badges */
        .discuss-wrap .ava-desc h3 a:not(.text-black) {
            font-size: 11px !important;
            padding: 3px 10px !important;
            background: #fff1f2 !important;
            color: var(--primary-color) !important;
            border-radius: 20px !important;
            text-decoration: none !important;
            font-weight: 700 !important;
            display: inline-flex !important;
            align-items: center !important;
            transition: all 0.2s ease !important;
            border: 1px solid rgba(255, 77, 90, 0.15) !important;
        }
        .discuss-wrap .ava-desc h3 a:not(.text-black):hover {
            background: var(--primary-color) !important;
            color: #ffffff !important;
        }
        .discuss-wrap .meta-time {
            font-size: 11px !important;
        }
        /* Optimizing the stats section (Likes, Comments, Share) */
        .discuss-wrap .entry-meta {
            padding: 10px 4px !important;
            font-size: 12px !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }
        .discuss-wrap .entry-meta .react-count {
            font-size: 12px !important;
            margin-left: 6px !important;
            white-space: nowrap !important;
        }
        .discuss-wrap .post-comment ul {
            display: flex !important;
            gap: 10px !important;
        }
        .discuss-wrap .post-comment ul li {
            margin-left: 0 !important;
            font-size: 12px !important;
        }
        .discuss-wrap .post-comment ul li a {
            color: var(--light-slate) !important;
            text-decoration: none !important;
        }
        /* Optimizing footer action buttons (Like, Comment, Share) */
        .discuss-wrap .post-actions {
            display: flex !important;
            width: 100% !important;
            padding: 4px 0 !important;
        }
        .discuss-wrap .post-action a {
            padding: 8px 2px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            color: var(--light-slate) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 4px !important;
        }
        .discuss-wrap .post-action a i,
        .discuss-wrap .post-action a img {
            font-size: 14px !important;
            width: 14px !important;
            height: 14px !important;
        }
        /* Comment form mobile tweaks */
        .discuss-wrap .comment-form {
            padding: 10px 8px !important;
            border-radius: 12px !important;
            margin: 8px !important;
            background-color: #f8fafc !important;
        }
        .discuss-wrap .comment-input-wrap input {
            padding: 8px 12px !important;
            font-size: 13px !important;
            border-radius: 20px !important;
        }
        .discuss-wrap .comment-input-wrap .send-btn {
            right: 8px !important;
            font-size: 13px !important;
        }
        .discuss-wrap .newsfeed-form .btn-trans,
        .tab-pane .newsfeed-form .btn-trans {
            font-size: 13px !important;
            padding: 15px 10px !important;
        }
        
        /* Reaction icon wrapper layout */
        .discuss-wrap .post-react {
            display: inline-flex !important;
            align-items: center !important;
        }
        .discuss-wrap .react-icons {
            display: inline-flex !important;
            align-items: center !important;
            padding: 0 !important;
            margin: 0 !important;
            list-style: none !important;
        }
        .discuss-wrap .react-icons li {
            margin-right: -4px !important;
        }
        .discuss-wrap .react-icons li img {
            width: 18px !important;
            height: 18px !important;
            object-fit: contain !important;
        }
    }

    /* Comment reaction capsule overlap & empty capsule fixes */
    .comment-wrap .comment-content a.comment-reaction-capsule:not(:has(.reaction-icon)) {
        display: none !important;
    }
    .comment-wrap .comment-content {
        margin-bottom: 16px !important;
    }
</style>   

<!-- Content Section Start -->
<div class="single-event-wrap">
    <div class="container">
        <!-- Breadcrumbs -->
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pl-0 pr-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('pages') }}">
                                <i class="fas fa-bars"></i>
                                Home
                            </a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('event') }}">All Categories</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('event.category.city',['category_slug'=>$category->category_slug,'city_slug'=>$city->city_slug]) }}">{{$city->city_name}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('event.city.area',['city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug]) }}">{{$area->area_name}}</a></li>

                        @foreach($parent_categories as $key => $parent_category)
                            <li class="breadcrumb-item"><a href="{{ route('event.category.city.area', ['city_slug'=>$city->city_slug,'category_slug'=>$category->category_slug, 'area_slug'=>$area->area_slug]) }}">{{ $parent_category->category_name }}</a></li>
                        @endforeach
                        <li class="breadcrumb-item active">{{ $category->category_name }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Cover Image -->
        <div class="row">
            <div class="col-12">
                <div class="event-cover-container">
                    @php
                        $bannerUrl = get_event_banner_image($event, 'coverphoto');
                        $isPlaceholder = empty($event->banner) || str_contains($bannerUrl, 'default') || str_contains($bannerUrl, 'placeholder');
                    @endphp
                    @if(!$isPlaceholder)
                        <img src="{{ $bannerUrl }}" alt="{{$event->title}}">
                    @else
                        <div class="default-event-gradient">
                            <div class="gradient-content">
                                <i class="fa-solid fa-champagne-glasses"></i>
                                <h3>{{$event->title}}</h3>
                            </div>
                        </div>
                    @endif
                    <div class="event-cover-overlay"></div>
                    <div class="floating-date-badge">
                        <span class="day">{{ date('d', strtotime($event->event_date))  }}</span>
                        <span class="month">{{ date('M', strtotime($event->event_date))  }}</span>
                    </div>

                    <div class="floating-views">
                        <i class="fa-regular fa-eye"></i>
                        <span>{{ count(json_decode($events->view ?? '[]')) }} {{ get_phrase('Views') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="row g-4 mt-1">
            <!-- Left Side: Content & Discussion -->
            <div class="col-lg-8 col-md-12">
                <div class="event-details-card">
                    @php  $postOfThisEvent = \App\Models\Posts::where('publisher','event')->where('publisher_id',$event->id)->first();@endphp
                    
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="event-time-badge">
                            <i class="fa-regular fa-calendar-days"></i>
                            <span>{{ date('l, d F Y', strtotime($event->event_date)) }} at {{ $event->event_time }}</span>
                        </div>
                        
                        <div class="post-controls dropdown dotted">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical text-secondary" style="font-size: 18px;"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @if($postOfThisEvent != null)
                                    <li>
                                        <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.main_content.share_post_modal', 'post_id' => $postOfThisEvent->post_id] )}}', '{{get_phrase('Share Event')}}');" class="dropdown-item"> <i class="fa-solid fa-share-nodes me-2"></i> {{get_phrase('Share')}}</a>
                                    </li>
                                @else
                                    <li>
                                        <a href="#" class="dropdown-item"> <i class="fa-solid fa-share-nodes me-2"></i> {{get_phrase('Create post to share')}}</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    
                    <h1>{{$event->title}}</h1>
                    <div class="event-location mt-2">
                        <i class="fa-solid fa-location-dot text-danger"></i>
                        <span>{{ $event->location }}</span>
                    </div>
                </div> <!-- Card End -->

                <!-- Nav Tabs container -->
                <div class="ct-tabs-container">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="about-tab" data-bs-toggle="tab"
                                data-bs-target="#about" type="button" role="tab" aria-controls="about"
                                aria-selected="true"><i class="fa-solid fa-circle-info me-2"></i>{{ get_phrase('About') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="discussion-tab" data-bs-toggle="tab"
                                data-bs-target="#discussion" type="button" role="tab"
                                aria-controls="discussion" aria-selected="false"><i class="fa-regular fa-comments me-2"></i>{{ get_phrase('Discussion') }}</button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content Box -->
                <div class="tab-content tab-content-container" id="myTabContent">
                    <div class="tab-pane fade show active" id="about" role="tabpanel"
                        aria-labelledby="about-tab">
                        <h3>{{ get_phrase('Event Details') }}</h3>
                        <p>
                            @php echo script_checker($event->description, false); @endphp
                        </p>
                    </div> <!-- Tab Pane End -->

                    <div class="tab-pane fade" id="discussion" role="tabpanel" aria-labelledby="discussion-tab">
                        {{-- include the post feature --}}
                        @include('frontend.main_content.create_post', ['event_id' => $event->id])

                        <div class="discuss-wrap">
                            <h3 class="h6 my-3">Recent Activity</h3>
                            @include('frontend.main_content.posts',['type'=>'user_post'])
                        </div>
                    </div><!-- Tab Pane End -->
                </div> <!-- Tab Content End -->
            </div>

            <!-- Right Side: RSVPs, Guests, Following, Popular Events -->
            <div class="col-lg-4 col-md-12">
                <aside class="sidebar plain-sidebar">
                    
                    <!-- RSVP Widget -->
                    <div class="premium-widget">
                        <h3 class="widget-title">{{ get_phrase('RSVP Status') }}</h3>
                        <div class="d-flex flex-column gap-2">
                            @if(auth()->user())
                                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('event.going',$event->id); ?>')" class="btn-action-primary text-center @if (in_array(auth()->user()->id, json_decode($event->going_users_id))) displaynone @endif" id="goingId{{ $event->id }}"><i class="fa-solid fa-calendar-check me-2"></i> {{get_phrase('Going')}}</a>
                                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('event.notgoing',$event->id); ?>')" class="btn-action-secondary text-center @if (!in_array(auth()->user()->id, json_decode($event->going_users_id))) displaynone @endif" id="notGoingId{{ $event->id }}"><i class="fa-solid fa-calendar-minus me-2"></i> {{get_phrase('Cancel RSVP')}}</a>

                                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('event.interested',$event->id); ?>')" class="btn-action-primary text-center @if (in_array(auth()->user()->id, json_decode($event->interested_users_id))) displaynone @endif" id="interestedId{{ $event->id }}"><i class="fa-regular fa-star me-2"></i> {{get_phrase('Interested')}}</a>
                                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('event.notinterested',$event->id); ?>')" class="btn-action-secondary text-center @if (!in_array(auth()->user()->id, json_decode($event->interested_users_id))) displaynone @endif" id="notInterestedId{{ $event->id }}"><i class="fa-solid fa-star-half-stroke me-2"></i> {{get_phrase('Not Interested')}}</a>

                                <a href="javascript:void(0)" onclick="openReportModal({{ $event->id }}, '{{ $event->title }}')" class="btn-action-secondary text-center mt-2"><i class="fa-solid fa-circle-exclamation me-2"></i> {{get_phrase('Report Event')}}</a>
                            @else
                                <div class="rsvp-guest-prompt text-center py-3">
                                    <p class="text-muted mb-3" style="font-size: 14px;">Are you planning to attend? Log in to RSVP and invite friends.</p>
                                    <a href="{{ route('login') }}" class="btn-action-primary text-center d-inline-block w-auto px-4"><i class="fa-solid fa-right-to-bracket me-2"></i> {{get_phrase('Log In to RSVP')}}</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Guests widget -->
                    <div class="premium-widget">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="widget-title m-0">{{ get_phrase('Guests')}}</h3>
                            <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.events.view-all', 'event_id' => $event->id])}}', '{{get_phrase('All Going And Interested User')}}');" data-bs-toggle="modal"
                                data-bs-target="#viewAll" class="fw-bold text-danger text-decoration-none" style="font-size: 14px;">{{get_phrase('View All')}}</a>
                        </div>
                        <div class="guest-stats">
                            <div class="guest-stat-card">
                                @php
                                    $directly_going_data = json_decode($event->going_users_id)!=null ? count(json_decode($event->going_users_id)) : 0;
                                    $invite_going_data = is_array($invited_friend_going) ? count($invited_friend_going) : (is_numeric($invited_friend_going) ? $invited_friend_going : 0);
                                    $total = $directly_going_data + $invite_going_data;
                                @endphp
                                <span class="num">{{ $total }}</span>
                                <span class="label">Going</span>
                            </div>
                            <div class="guest-stat-card">
                                <span class="num">{{ json_decode($event->interested_users_id)!=null ? count(json_decode($event->interested_users_id)) : 0 }}</span>
                                <span class="label">Interested</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sponsored Ads Widget -->
                    @if($sponsorPost->isNotEmpty())
                        <div class="premium-widget d-lg-none">
                            <h3 class="widget-title">{{ get_phrase('Sponsored') }}</h3>
                            <div class="sponsors">
                                @foreach ($sponsorPost as $sponsor)
                                    <a target="_blank" href="{{ $sponsor->ext_url }}" class="sponsor-item-link d-flex align-items-center mb-3 text-decoration-none">
                                        <img src="{{ get_sponsor_image($sponsor->image, 'thumbnail') }}" class="rounded me-3" style="width: 55px; height: 55px; object-fit: cover;" alt="{{ $sponsor->name }}">
                                        <div class="sponsor-info">
                                            <h6 class="mb-1 text-dark fw-bold" style="font-size: 13px;">{{ ellipsis($sponsor->name, 30) }}</h6>
                                            <p class="text-muted mb-0" style="font-size: 11px; line-height: 1.4;">{{ ellipsis(strip_tags($sponsor->description), 70) }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(auth()->user())
                        <!-- Go With Following widget -->
                        <div class="premium-widget">
                            <h3 class="widget-title">{{ get_phrase('Go With Following') }}</h3>
                            <div class="invite-search-container">
                                <input type="text" id="myInputSearch" onkeyup="mySearchFunction()" placeholder="Search friends...">
                                <span class="search-icon fas fa-search"></span>
                            </div>
                            
                            <div class="invite-wrap overflow-auto mt-2" style="max-height: 250px;">
                                <table id="myTable" class="w-100">
                                    <tbody class="searchTbody">
                                        @foreach ($friends as $friend )
                                            @php $invited_friend_id = $friend->requester==auth()->user()->id ? $friend->accepter:$friend->requester; @endphp
                                            @php $inviteablefrienddetails= DB::table('users')->where('id', $invited_friend_id)->first(); @endphp
                                            @php $invite_details= DB::table('invites')->where('invite_reciver_id', $invited_friend_id)->where('event_id', $event->id)->first(); @endphp
                                            @if($inviteablefrienddetails)
                                                <tr>
                                                    <td>
                                                        <div class="invite-friend-item">
                                                            <div class="d-flex align-items-center">
                                                                <a href="{{route('user.profile.view', $inviteablefrienddetails->id)}}"><img width="36" height="36" class="rounded-circle me-2" src="{{get_user_image($inviteablefrienddetails->photo, 'optimized')}}" alt=""></a>
                                                                <h3 class="h6 mb-0" style="font-size: 14px;"><a href="{{route('user.profile.view', $inviteablefrienddetails->id)}}" class="text-decoration-none text-dark fw-semibold">{{ ellipsis( $inviteablefrienddetails->name, 20 ) }}</a></h3>
                                                            </div>
                                                            <div>
                                                                @if (!empty($invite_details) && $invite_details->invite_reciver_id == $invited_friend_id && $invite_details->is_accepted != '1')
                                                                    <button class="btn btn-sm btn-light text-primary" data-bs-toggle="tooltip" title="{{ get_phrase('Invited') }}"> <span class="fas fa-check"></span></button>
                                                                @elseif (!empty($invite_details) && $invite_details->invite_reciver_id == $invited_friend_id && $invite_details->is_accepted == '1' )
                                                                    <button class="btn btn-sm btn-light text-success" data-bs-toggle="tooltip" title="{{ get_phrase('Going') }}"> <i class="far fa-calendar-check"></i> </button>
                                                                @else
                                                                    <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('event.invite', ['invited_friend_id' => $invited_friend_id, 'requester_id' => auth()->user()->id, 'event_id' => $event->id]); ?>')" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="{{ get_phrase('Send invitation') }}"><i class="fas fa-location-arrow"></i></a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Popular Events Widget -->
                    <div class="premium-widget">
                        <h3 class="widget-title mb-3">{{ get_phrase('Popular Events') }}</h3>
                        @php $index = 1; @endphp
                        @foreach ($popularevents as $key => $popularevent)
                            @if($popularevent['city_slug'] && $popularevent['area_slug'] && $popularevent['category_slug'])
                                <div class="popular-event-card">
                                    <img src="{{ get_event_banner_image((object) $popularevent, 'thumbnail') }}" alt="event">
                                    <div class="info">
                                        <span class="cat">{{ $popularevent['category_name'] }}</span>
                                        <h4>
                                            <a href="{{ route('single.event', ['city_slug' => $popularevent['city_slug'], 'area_slug' => $popularevent['area_slug'], 'category_slug' => $popularevent['category_slug'], 'event_slug' => $popularevent['event_slug']]) }}">
                                                {{ ellipsis($popularevent['title'], 45) }}
                                            </a>
                                        </h4>
                                        <span class="date">{{ date('d F Y', strtotime($popularevent['event_date'])) }}</span>
                                    </div>
                                </div>
                            @endif

                            @php
                                if ($index == 5) {
                                    break;
                                } else {
                                    $index++;
                                }
                            @endphp
                        @endforeach
                    </div>

                    <!-- Featured Products Widget -->
                    @if(isset($featuredProducts) && $featuredProducts->isNotEmpty())
                        <div class="premium-widget">
                            <h3 class="widget-title mb-3">{{ get_phrase('Featured Products') }}</h3>
                            <div class="featured-products">
                                @foreach($featuredProducts as $product)
                                    @php
                                        $productRoute = route('single.product', [
                                            'city_slug' => $product->city_slug ?? 'city',
                                            'area_slug' => $product->area_slug ?? 'area',
                                            'category_slug' => $product->page_category_slug ?? 'category',
                                            'item_slug' => $product->page_slug ?? 'item',
                                            'product_category_slug' => $product->product_category_slug ?? 'subcategory',
                                            'product_slug' => $product->product_slug ?? 'product'
                                        ]);
                                    @endphp
                                    <div class="d-flex align-items-center mb-3">
                                        <a href="{{ $productRoute }}">
                                            <img src="{{ get_marketplace_banner_image($product, 'thumbnail') }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;" alt="{{ $product->title }}">
                                        </a>
                                        <div class="text-truncate" style="flex: 1; min-width: 0;">
                                            <h6 class="mb-1 text-dark fw-bold" style="font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <a href="{{ $productRoute }}" class="text-decoration-none text-dark">{{ ellipsis($product->title, 40) }}</a>
                                            </h6>
                                            <small class="text-muted" style="font-size: 11px;">{{ $product->city_name ?? '' }}, {{ $product->area_name ?? '' }}</small><br>
                                            <strong class="text-danger" style="font-size: 12px; font-weight: 700;">
                                                {{ $product->currency_symbol ?? '₹' }}{{ $product->product_selling_price }}
                                            </strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </aside>
            </div>
        </div>
    </div>
</div>

@include('frontend.main_content.scripts')

<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Report Issue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reportForm" action="{{ route('report.group') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Hidden Fields -->
                    <input type="hidden" id="type" name="type">
                    <input type="hidden" id="entity_id" name="entity_id">

                    <!-- Group Name (Pre-filled) -->
                    <div class="mb-3">
                        <label for="group_name" class="form-label">Event Name</label>
                        <input type="text" class="form-control" id="group_name" name="group_name" readonly>
                    </div>

                    <!-- Full Name -->
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name (Optional)</label>
                        <input type="text" class="form-control" id="full_name" name="full_name">
                    </div>

                    <!-- Email Address (Required) -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number (Optional)</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>

                    <!-- Reason for Reporting -->
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Reporting *</label>
                        <select class="form-select" id="reason" name="reason" required>
                            <option value="">Select Reason</option>
                            <option value="Spam">Spam</option>
                            <option value="Inappropriate Content">Inappropriate Content</option>
                            <option value="Harassment/Bullying">Harassment/Bullying</option>
                            <option value="Fake Group">Fake Group</option>
                            <option value="Other">Other (Specify Below)</option>
                        </select>
                    </div>

                    <!-- Additional Comments -->
                    <div class="mb-3">
                        <label for="additional_comments" class="form-label">Additional Comments (Optional)</label>
                        <textarea class="form-control border" id="additional_comments" name="additional_comments" rows="3"></textarea>
                    </div>

                    <!-- File Upload (Proof) -->
                    <div class="mb-3">
                        <label for="proof_attachment" class="form-label">Attach Proof (Optional)</label>
                        <input type="file" class="form-control" id="proof_attachment" name="proof_attachment" accept="image/*, .pdf, .docx">
                    </div>

                    <!-- Would You Like a Response? -->
                    <div class="mb-3">
                        <label class="form-label">Would you like a response?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="response_required" id="response_yes" value="Yes" checked>
                            <label class="form-check-label" for="response_yes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="response_required" id="response_no" value="No">
                            <label class="form-check-label" for="response_no">No</label>
                        </div>
                    </div>

                    <!-- CAPTCHA Verification -->
                    <div class="mb-3">
                        <label class="form-label">CAPTCHA Verification *</label>
                        <div class="g-recaptcha" data-sitekey="YOUR_SITE_KEY"></div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-danger w-100">Submit Report</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openReportModal(groupId, groupName) {
        $('#entity_id').val(groupId);
        $('#group_name').val(groupName);
        $('#type').val('event');
        $('#reportModal').modal('show');
    }

    function toggleOtherReason() {
        if ($('#reason').val() === 'Other') {
            $('#otherReasonDiv').show();
        } else {
            $('#otherReasonDiv').hide();
            $('#other_reason').val('');
        }
    }
</script>
