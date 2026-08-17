<style>
  .sugg-card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    padding: 12px; /* Reduce padding */
    border-radius: 10px; /* Optional: Make it slightly rounded */
}

.sugg-card a {
    display: block;
    font-size: 14px; /* Reduce font size for links */
    margin-bottom: 5px; /* Reduce space between links */
}

.thumbnail-110-106 {
    width: 100%;
    height: 100px; /* Ensure uniform image height */
    background-size: cover;
    background-position: center;
    border-radius: 8px; /* Optional: Rounded corners */
}

.sugg-card h4 {
    font-size: 16px; /* Adjust heading size */
    line-height: 1.3;
    margin-bottom: 5px;
}

.sugg-card .text-muted {
    font-size: 12px; /* Reduce font size for like count */
    margin-bottom: 5px;
}

.sugg-card .btn-primary {
    font-size: 14px;
    padding: 6px 10px; /* Reduce button size */
    margin-top: auto; /* Push button to bottom */
}

.col-xl-3, .col-lg-4, .col-md-3, .col-sm-4, .col-6 {
    padding: 5px; /* Reduce space between columns */
}


</style>   
<div class="suggest-wrap row">
    @foreach ($mypages as $page)
        @php
            $lastCategory = $page->categories->last();
            $likeCount = $page->likes->count();
            $isLiked = $page->likes->contains('user_id', auth()->id());
        @endphp

        <div class="col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card sugg-card p-2 rounded">

                @if ($page->city && $page->area && $lastCategory && $page->item_slug)
                    <a href="{{ route('single.page', [
                        'city_slug' => $page->city->city_slug,
                        'area_slug' => $page->area->area_slug,
                        'category_slug' => $lastCategory->category_slug,
                        'item_slug' => $page->item_slug
                    ]) }}" class="mb-2 thumbnail-110-auto" style="background-image: url('{{ get_page_logo($page->logo, 'logo') }}')"></a>
                @endif

                <h4>
                    @if ($page->city && $page->area && $lastCategory && $page->item_slug)
                        <a href="{{ route('single.page', [
                            'city_slug' => $page->city->city_slug,
                            'area_slug' => $page->area->area_slug,
                            'category_slug' => $lastCategory->category_slug,
                            'item_slug' => $page->item_slug
                        ]) }}">
                            {{ $page->title}}
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