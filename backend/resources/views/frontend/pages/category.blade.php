<!-- <h1 class="font-weight-light text-primary">Featured Video</h1>
<div class="sameHeight suggest-wrap row" id="pagedata">
    @foreach ($videoInfo as $key => $page)
        @if ($page->featured_video && $page->featured_video != '') 
            <div class="col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6 mb-3" id="page-{{ $page->id }}">
                <div class="card sugg-card p-2 rounded">
                    <div class="smp-info">
                      
                        <video width="100%" controls>
                            <source src="{{ $page->featured_video }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
 -->
<html>
<style type="text/css">
    .thumbImg video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .thumbImg {
        display: block;
        width: 100%;
        height: 200px;
        overflow: hidden;
        position: relative;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 12px 12px 0 0;
        background-color: #f3f4f6;
    } 
    .sugg-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(229, 231, 235, 0.7);
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }
    .sugg-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.06);
        border-color: rgba(255, 90, 95, 0.3);
    }
    .smp-info {
        padding: 12px 8px;
    }
    .smp-info h4 {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 6px;
        line-height: 1.4;
        transition: color 0.2s ease;
    }
    .smp-info h4:hover {
        color: #ff5a5f;
    }
    .row > [class*="col-"] {
        padding-right: 0px;
        padding-left: 15px;
    }




</style>
<body>

<div class="col-md-12 ml-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white pl-0 pr-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('pages') }}">
                        <i class="fas fa-bars"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('pages') }}">Business</a>
                </li>
                
               
             
                    <li class="breadcrumb-item">
                       
                            {{ $category->category_name }}
                        </a>
                    </li>
</ol>
</nav>
</div>

<div class="d-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h3 class="h5 pt-3"><span ><img width="12" src="{{ asset('assets/frontend/images/stickies-fill.png') }}" alt=""></span> {{ get_phrase('Business') }}</h3>
        <div class="inline-btn w-50">
            <a href="{{ route('pages.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle me-1"></i>{{ get_phrase('Add Business') }}</a>
        </div>
    </div>


<h1 class="font-weight-light text-primary mb-2 ml-3 ">{{ $category->category_name }}</h1>
<div class=" row sameHieght suggest-wrap  " id="pagedata">

    @foreach ($mypages as $key => $page)

        @php
            $itemCategories = $page->pageCategories;
            $lastCategory = $itemCategories->last();
        @endphp

        @if ($page->city && $page->area && $lastCategory && $page->item_slug)

            <div class="col-xl-3 col-lg-3 col-md-2 col-sm-4 col-6 mb-3" id="page-{{ $page->id }}">
                <div class="card sugg-card p-2 rounded {{ $page->featured_video }}">
                   

                   <!--  <a class="thumbImg" 
                       href="{{ route('single.page', [
                            'city_slug' => $page->city->city_slug,
                            'area_slug' => $page->area->area_slug,
                            'category_slug' => $lastCategory->category_slug,
                            'item_slug' => $page->item_slug
                        ]) }}"
                       style="background-image: url({{ get_page_logo($page->logo, 'logo') }})">
                    </a> -->
                    @if(!empty($page->featured_video))
                        {{-- Show video instead of background image --}}
                        <a class="thumbImg "
                           href="{{ route('single.page', [
                                'city_slug' => $page->city->city_slug,
                                'area_slug' => $page->area->area_slug,
                                'category_slug' => $lastCategory->category_slug,
                                'item_slug' => $page->item_slug
                            ]) }}">
                            <video autoplay muted loop playsinline>
                                <source src="{{ $page->featured_video }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </a>
                    @else
                        {{-- Fallback to logo image --}}
                        <a class="thumbImg block overflow-hidden"
                           href="{{ route('single.page', [
                                'city_slug' => $page->city->city_slug,
                                'area_slug' => $page->area->area_slug,
                                'category_slug' => $lastCategory->category_slug,
                                'item_slug' => $page->item_slug
                            ]) }}">
                            <img src="{{ get_page_logo($page->logo, 'logo') }}" 
                                 style="width: 100%; height: 110px; object-fit: cover; border-radius: 10px;" 
                                 alt="{{ $page->title }}"
                                 onerror="this.onerror=null; this.src='{{ asset('storage/pages/logo/default.png') }}';">
                        </a>
                    @endif



                    <div class="smp-info">
                        <a href="{{ route('single.page', [
                                'city_slug' => $page->city->city_slug,
                                'area_slug' => $page->area->area_slug,
                                'category_slug' => $lastCategory->category_slug,
                                'item_slug' => $page->item_slug
                            ]) }}">
                            <h4 class="h6 mt-1 ">{{ $page->title }}</h4>
                        </a>

                         <a href="{{ route('page.category', ['category_slug' => $lastCategory->category_slug]) }}">
                        {{ $lastCategory->category_name }}
                    </a>

                        <a href="{{ route('page.category.city', [
                                'category_slug' => $lastCategory->category_slug,
                                'city_slug' => $page->city->city_slug
                            ]) }}">
                            {{ $page->area->area_name }}, {{ $page->city->city_name }}
                        </a>
                        <br>

                        @php
                            $likecount = $page->likes_count ?? 0;
                        @endphp
                        <a href="{{ route('single.page', [
                                'city_slug' => $page->city->city_slug,
                                'area_slug' => $page->area->area_slug,
                                'category_slug' => $lastCategory->category_slug,
                                'item_slug' => $page->item_slug
                            ]) }}">
                            <span><i class="fa fa-thumbs-up mr-2"></i>{{ $likecount }} {{ get_phrase('People like this') }}</span>
                        </a>
                    </div>
                </div>
            </div>

        @endif

    @endforeach
                        </div>

    <div class="row">
        <div class="col-12">
            {{ $mypages->links() }}
        </div>
    </div>
                        </body>
                        </html>
