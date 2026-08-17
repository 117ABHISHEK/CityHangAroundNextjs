@php
    $citySlug = $city->city_slug ?? 'all';
@endphp

<style>
    .search-breadcrumb .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 20px;
        font-size: 14px;
    }
    .search-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        content: "/";
        color: #9ca3af;
    }
    .search-breadcrumb a {
        color: #4b5563;
        text-decoration: none;
    }
    .search-breadcrumb .active {
        color: #111827;
        font-weight: 500;
    }


    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 20px;
    }

    .listing-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
        padding: 15px;
        text-align: center;
    }

    .listing-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-color: #d1d5db;
    }

    .listing-image-wrap {
        position: relative;
        padding-top: 100%; /* Square aspect ratio */
        background: #f3f4f6;
        border-radius: 50%; /* Make it a circle */
        margin: 0 auto 15px auto; /* Center it */
        overflow: hidden;
        width: 120px; /* Controlled size for circle */
        height: 120px; /* Match width */
        padding-top: 0; /* Override padding-top since we use fixed height */
        border: 3px solid #fff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .listing-image-wrap img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .listing-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-grow: 1;
    }

    .listing-title {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 6px;
        line-height: 1.3;
    }

    .listing-location {
        font-size: 13px;
        color: #4b5563;
        margin-bottom: 10px;
        line-height: 1.4;
        min-height: 36px;
    }

    .listing-footer {
        margin-top: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 12px;
        color: #6b7280;
    }

    .listing-footer i {
        color: #9ca3af;
        font-size: 14px;
    }

    .pagination-wrap {
        margin-top: 40px;
        display: flex;
        justify-content: center;
    }
</style>

{{-- ── Breadcrumbs ───────────────────────────────────────────────────── --}}
<div class="search-breadcrumb">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('pages') }}"><i class="fas fa-home"></i> Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('pages') }}">Business</a>
            </li>
            @if($category)
                <li class="breadcrumb-item active" aria-current="page">{{ $category->category_name }}</li>
            @endif
        </ol>
    </nav>
</div>


{{-- ── Category Title ────────────────────────────────────────────────── --}}
<h3 class="section-title">
    {{ $category->category_name ?? 'Business Listings' }}
</h3>

{{-- ── Results Grid ──────────────────────────────────────────────────── --}}
<div class="row g-4" id="pagedata">
    @forelse ($mypages as $mypage)
        @php
            $catslug = $mypage->catslug ?? 'all';
            $catname = $mypage->catname ?? 'Uncategorized';
            $likecount = $mypage->likes_count ?? 0;
            
            $singleRoute = route('single.page', [
                'city_slug' => $mypage->city_slug ?? 'city',
                'area_slug' => $mypage->area_slug ?? 'area',
                'category_slug' => $catslug,
                'item_slug' => $mypage->item_slug
            ]);
        @endphp

        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
            <div class="listing-card">
                <a href="{{ $singleRoute }}" class="listing-image-wrap">
                    @php
                        // BANNER-FIRST LOGIC: Use coverphoto as primary if available, else fallback to logo, then category icon
                        if (!empty($mypage->coverphoto)) {
                            $logo_url = get_page_banner_image($mypage, 'coverphoto');
                        } else {
                            $logo_url = get_page_logo($mypage->logo, 'logo', $mypage->categories);
                        }
                        $is_default = str_contains($logo_url, 'default.png');
                    @endphp
                    @if($is_default)
                        <div class="d-flex align-items-center justify-content-center w-100 h-100 bg-light">
                            <i class="fas fa-hotel fa-3x text-secondary"></i>
                        </div>
                    @else
                        <img src="{{ $logo_url }}" alt="{{ $mypage->title }}" class="w-100 h-100" style="object-fit: cover;" onerror="this.onerror=null; this.src='{{ asset('storage/pages/logo/default.png') }}';">
                    @endif
                </a>
                <div class="listing-content">
                    <a href="{{ $singleRoute }}" class="text-decoration-none">
                        <h4 class="listing-title">{{ $mypage->title }}</h4>
                    </a>
                    <div class="listing-location">
                        {{ $catname }}<br>
                        {{ $mypage->area_name ? $mypage->area_name.',' : '' }} {{ $mypage->city_name }}
                    </div>
                    <div class="listing-footer">
                        <i class="fas fa-thumbs-up"></i>
                        <span>{{ $likecount }} People like this</span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="text-gray-400 mb-3">
                <i class="fas fa-search fa-3x"></i>
            </div>
            <h4>No listings found</h4>
            <p class="text-gray-500">Try adjusting your filters to find what you're looking for.</p>
        </div>
    @endforelse
</div>

{{-- ── Pagination ────────────────────────────────────────────────────── --}}
@if($mypages->hasPages())
    <div class="pagination-wrap">
        {{ $mypages->links() }}
    </div>
@endif
