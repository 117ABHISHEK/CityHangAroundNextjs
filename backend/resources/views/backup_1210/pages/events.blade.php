<div class="profile-wrap">
    @include('frontend.pages.timeline-header')

    <div class="profile-content mt-3">
        <div class="profile-inner-nav-outer">
            @include('frontend.pages.inner-nav')
        </div>

        <div class="row gx-3">
            <div class="col-lg-12 col-sm-12">
                <div class="friends-tab events">
                    <div class="photo-list mt-3">
                        <h4 class="h6 mb-3">{{ get_phrase('Your Events') }}</h4>
                        <div class="flex-wrap" >
                            @include('frontend.profile.event_single')
                        </div>
                    </div>
                </div> <!-- Friends Tab End -->
            </div>
        </div>
    </div>
</div>
