

<style>
  .sugg-card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    padding: 12px;
    border-radius: 10px;
}

.sugg-card a {
    display: block;
    font-size: 14px;
    margin-bottom: 5px;
}

/* Use a fresh class to avoid conflicts */
.cover-thumb {
    display: block;
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    background: #f2f2f2;
    border: 1px solid rgba(0,0,0,0.08);
}

/* Ensure images are not hidden by global CSS */
.sugg-card img {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    max-width: 100%;
}

.sugg-card h4 {
    font-size: 16px;
    line-height: 1.3;
    margin-bottom: 5px;
}

.sugg-card .text-muted {
    font-size: 12px;
    margin-bottom: 5px;
}

.sugg-card .btn-primary {
    font-size: 14px;
    padding: 6px 10px;
    margin-top: auto;
}

.col-xl-3, .col-lg-4, .col-md-3, .col-sm-4, .col-6 {
    padding: 5px;
}
</style>

<div class="suggest-wrap row">
    @foreach ($mypages as $page)
@php
    $lastCategory = $page->categories->last();
    $likeCount = $page->likes->count();
    $isLiked = $page->likes->contains('user_id', auth()->id());

    $coverPhotoUrl = get_page_banner_image($page, 'coverphoto');

    // ✅ Route guard
    $canRoute = $page->city && $page->area && $lastCategory && $page->item_slug;
@endphp
        
        

        <div class="col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card sugg-card p-2 rounded">

                {{-- Force-render cover photo unconditionally --}}
                <a href="{{ $canRoute
    ? route('single.page', [
        'city_slug' => $page->city->city_slug,
        'area_slug' => $page->area->area_slug,
        'category_slug' => $lastCategory->category_slug,
        'item_slug' => $page->item_slug
    ])
    : 'javascript:void(0)' }}"
   class="mb-2 d-block"
   data-cover="{{ $coverPhotoUrl }}"
>

                <h4>
                    @if ($page->city && $page->area && $lastCategory && $page->item_slug)
                        <a href="{{ route('single.page', [
                            'city_slug' => $page->city->city_slug,
                            'area_slug' => $page->area->area_slug,
                            'category_slug' => $lastCategory->category_slug,
                            'item_slug' => $page->item_slug
                        ]) }}">
                            {{ $page->title }}
                        </a>
                    @else
                        {{ $page->title }}
                    @endif
                </h4>

                @if ($lastCategory)
                    <a href="{{ route('page.category', ['category_slug' => $lastCategory->category_slug]) }}">
                        {{ $lastCategory->category_name }}
                    </a>
                @endif

                @if ($page->city && $page->state && $lastCategory ) 
                    <h4>
                        <a href="{{ route('page.category.city', [
                            'category_slug' => $lastCategory?->category_slug,
                            'city_slug' => $page->city->city_slug
                        ]) }}">
                            {{ $page->area->area_name }}, {{ $page->city->city_name }}
                        </a>
                    </h4>
                @endif

                @if ($page->item_status == 1)
                    <div class="text-danger small mb-1">
                        <i class="fa fa-clock-o"></i> Pending Approval
                    </div>
                @endif

                <span class="small text-muted">
                    <i class="fa fa-thumbs-up"></i> {{ $likeCount }} {{ get_phrase('People follow this') }}
                </span>

                @if ($isLiked)
                    <a href="javascript:void(0)" onclick="ajaxAction('{{ route('page.dislike', $page->id) }}')" class="btn btn-primary mt-2">
                        <i class="fa fa-thumbs-up"></i> {{ __('Following') }}
                    </a>
                @else
                    <a href="javascript:void(0)" onclick="ajaxAction('{{ route('page.like', $page->id) }}')" class="btn btn-primary mt-2">
                        <i class="fa fa-thumbs-up"></i> {{ get_phrase('Follow') }}
                    </a>
                @endif

              
            </div>
        </div>
    @endforeach
</div>
