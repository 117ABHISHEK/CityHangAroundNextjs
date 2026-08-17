<div class="page-wrap">
    <div class="d-md-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h3 class="h5 pt-3"><span><i class="fa-solid fa-file-video"></i></span> {{ get_phrase('Watch') }}</h3>
        <div class="">
            <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.video-shorts.create'])}}', '{{get_phrase('Create Video & Shorts ')}}');" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="" class="btn btn-primary"> <i
                class="fa-solid fa-plus-circle me-2"></i>{{get_phrase('Create')}}</a>
            <a href="{{ route('shorts') }}" class="btn btn-primary"><i class="fa-solid fa-clapperboard me-2"></i>{{get_phrase('Shorts')}}</a> 
            <a href="{{ route('videos') }}" class="btn btn-primary"><i class="fa-solid fa-clapperboard me-2"></i>{{get_phrase('Videos')}}</a>
            <a href="{{ route('save.all.view') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Saved Video"><span><i class="fa-solid fa-bookmark"></i></span></a>
        </div>
    </div>
    <div id="videoShowDatas">
        @include('frontend.video-shorts.single-video')
    </div>
</div>

<style>
/* Video sizing adjustments */
.entry-content video {
    width: 100%;
    max-height: 400px;
    object-fit: contain;
    background: #000;
    border-radius: 8px;
}

/* Ensure videos don't stretch too much */
.plyr-js {
    max-height: 400px !important;
    height: auto !important;
}

/* Video container adjustments */
.entry-content {
    padding: 0;
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}

/* Responsive video sizing */
@media (max-width: 768px) {
    .entry-content video {
        max-height: 300px;
    }
    
    .plyr-js {
        max-height: 300px !important;
    }
}

@media (max-width: 576px) {
    .entry-content video {
        max-height: 250px;
    }
    
    .plyr-js {
        max-height: 250px !important;
    }
}
</style>

@include('frontend.main_content.scripts')
@include('frontend.common_scripts')