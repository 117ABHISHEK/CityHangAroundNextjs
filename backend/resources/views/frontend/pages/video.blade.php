

<div class="profile-wrap">
    @include('frontend.pages.timeline-header')
    <div class="profile-content mt-3">
    <div class="profile-inner-nav-outer">  @include('frontend.pages.inner-nav')</div>
        <div class="row gx-3">
            <div class="col-lg-7 col-sm-12">
                
                
                <!-- Profile Nav End -->
                <div class="friends-tab ct-tab bg-white p-3">
                    
                    
                    <div class="d-flex align-items-center justify-content-between p-3">
                        <h4 class="h6 fw-7 m-0">{{get_phrase('Your videos')}}</h4>
                        <div class="gap-2">
                            <a onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.profile.add_video','page_id'=>$page->id])}}', '{{get_phrase('Add Video')}}');" data-bs-toggle="modal" data-bs-target="#videoCreateModal"
                                class="btn btn-primary"> {{ get_phrase('Add Video') }}
                            </a>
                        </div>
                    </div>
                   

                </div> <!-- Friends Tab End -->
                
            </div> <!-- COL END -->
            <div class="col-lg-5 col-sm-12">
                @include('frontend.pages.bio')
            </div>
        </div>
    </div> <!-- Profile content End -->
</div>


