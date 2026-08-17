<!-- Profile Nav End -->
<div class="friends-tab ct-tab bg-white p-3">
    <div class="d-flex align-items-center justify-content-between p-3">
        <h3 class="h6 fw-7 m-0">{{get_phrase('Your videos')}}</h3>
        <div class="gap-2">
            <a onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.profile.add_video'])}}', '{{get_phrase('Add Video')}}');" data-bs-toggle="modal" data-bs-target="#videoCreateModal"
                class="btn btn-primary"> {{ get_phrase('Add Video') }}
            </a>
        </div>
    </div>
	
    <div class="photo-list mt-3">
        <div class="flex-wrap" id="allVideos">
            @include('frontend.profile.video_single')
        </div>
    </div>

</div> <!-- Friends Tab End -->