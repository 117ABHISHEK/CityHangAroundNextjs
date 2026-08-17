@php $user_info = $user_info ?? auth()->user(); @endphp
<div class="modal fade" id="createPost" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {{-- pura post form yahan --}}
            <div class="newsfeed-form single-entry">
    <div class="entry-inner">
        <div class="create-entry">
            @if (isset($page_id)&&!empty($page_id))
            @php
            // Get the last category slug
            $catslug = $page->categories->last()->category_slug ?? null;
        @endphp
                <a href="{{route('single.page',['city_slug'=>$page->city->city_slug,'area_slug'=>$page->area->area_slug,'category_slug'=>$catslug,'item_slug'=>$page->item_slug])}}" class="author-thumb d-flex align-items-center">
                    <img src="{{get_page_logo($page->logo, 'logo')}}" width="40px" height="40px" class="rounded-circle" alt="">
                </a>
            @else
            @if(auth()->user())
                <a href="{{route('profile')}}" class="author-thumb d-flex align-items-center">
                    <img src="{{get_user_image($user_info->photo, 'optimized')}}" width="40px" height="40px" class="rounded-circle" alt="">
                </a>
                 @endif
            @endif
             @if(auth()->user())
            <button class="btn-trans" data-bs-toggle="modal" data-bs-target="#createPost" >
                {{  get_phrase("What's on your mind ____", [auth()->user()->name]) }}?
            </button>
 @endif
            @if (isset($page_id)&&!empty($page_id))
                @include('frontend.main_content.create_post_modal',['page_id'=>$page_id])
            @elseif (isset($group_id)&&!empty($group_id))
                @include('frontend.main_content.create_post_modal',['group_id'=>$group_id])
            @else
                @include('frontend.main_content.create_post_modal')
            @endif

        </div>
        @if(Route::currentRouteName() == 'timeline'||Route::currentRouteName() == 'profile' || Route::currentRouteName() == 'single.group' || Route::currentRouteName() == 'profile.scheduled_posts')
            <div class="post-options justify-content-center">
                <button class="btn" data-bs-toggle="modal" data-bs-target="#createPost"><img src="{{asset('storage/images/image.svg')}}" alt="photo"> {{get_phrase('Photo')}}/{{get_phrase('Video')}}</button>
                <button class="btn" data-bs-toggle="modal" data-bs-target="#createPost"><img src="{{asset('storage/images/location.png')}}" alt="photo"> {{get_phrase('Location')}}</button>
                <button class="btn" data-bs-toggle="modal" data-bs-target="#createPost"><img src="{{asset('storage/images/camera.svg')}}" alt="photo"> {{get_phrase('Live Video')}}</button>
                <button class="btn" data-bs-toggle="modal" data-bs-target="#createPost"><img src="{{asset('storage/images/plus-circle-fill.svg')}}" alt="photo"> {{get_phrase('More')}}</button>
            </div>
        @endif
    </div>
</div>
        </div>
    </div>
</div>

<style>
.post-options .btn {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    white-space: nowrap !important;
}
.post-options .btn img {
    width: 18px;
    height: 18px;
    object-fit: contain;
    flex-shrink: 0;
}
</style>
