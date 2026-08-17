<div class="profile-wrap">
    @include('frontend.pages.timeline-header')

    <div class="profile-content mt-3">
        <div class="profile-inner-nav-outer">
            @include('frontend.pages.inner-nav')
        </div>
<!-- Profile Nav End -->
<div class="friends-tab ct-tab p-0">
	
    <div class="photo-list mt-3">
        <h4 class="h6 mb-3">{{get_phrase('Your Groups')}}</h4>
        <div class="flex-wrap" >
            @include('frontend.pages.group_single')
        </div>
    </div>

</div> <!-- Friends Tab End -->
</div>
</div>