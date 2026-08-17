<div class="profile-wrap">
    @include('frontend.pages.timeline-header')

    <div class="profile-content mt-3">
        <div class="profile-inner-nav-outer">
            @include('frontend.pages.inner-nav')
        </div>

<div class="friends-tab ct-tab">
    <div class="photo-list mt-3">
        <h4 class="h6 mb-3">{{get_phrase('Your Products')}}</h4>
        <div class="flex-wrap" >
            @include('frontend.pages.product_single')
        </div>
    </div>

</div> <!-- Friends Tab End -->
</div>
</div>