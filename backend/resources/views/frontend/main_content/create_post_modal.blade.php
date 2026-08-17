@php $user_info = $user_info ?? auth()->user(); @endphp
<!-- Modal -->
<form id="createPostForm" action="{{route('create_post')}}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="submission_id" name="submission_id" value="">
    <input type="hidden" id="post_privacy" name="privacy" value="public">
    <input type="hidden" id="post_type" name="post_type" value="general">
    @isset($event_id)
        <input type="hidden" id="event_id" name="event_id" value="{{ $event_id }}"> 
        <input type="hidden" id="publisher" name="publisher" value="event"> 
    @endisset
    @isset($page_id)
        <input type="hidden" id="page_id" name="page_id" value="{{ $page_id }}"> 
        <input type="hidden" id="publisher" name="publisher" value="page"> 
    @endisset

    @isset($group_id)
        <input type="hidden" id="group_id" name="group_id" value="{{ $group_id }}"> 
        <input type="hidden" id="publisher" name="publisher" value="group"> 
    @endisset

    <div class="modal fade" id="createPost" tabindex="-1" aria-labelledby="createPostLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title" id="exampleModalLabel">{{get_phrase('Create Post')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"><i class="fa fa-close"></i></button>
                </div>
                <div class="modal-body">
                    <div class="entry-header d-flex justify-content-between">
                        @if (isset($page_id)&&!empty($page_id))
                            @php
                                
                                $page =DB::table('pages')->select('pages.id','pages.item_slug','pages.logo','pages.title','cities.city_slug','areas.area_slug','pagecategories.category_slug')
        ->join('cities','cities.id','pages.city_id')
        ->join('areas','areas.id','pages.area_id')
        ->join('page_category','page_category.page_id','pages.id')
        ->leftjoin('page_likes','page_likes.page_id','pages.id')
        ->join(DB::raw('(select max(page_category.id) as max,max(page_category.category_id) as category_id,page_id
                    from page_category
                    inner join pagecategories on pagecategories.id =page_category.category_id  group by page_id) t1'), 
                function($join)
                {
                $join->on('t1.page_id', '=', 'pages.id');
                })
        ->join('pagecategories','t1.category_id','=','pagecategories.id')
        ->distinct('pages.id')
        ->where('pages.id',$page_id)->get();
                            @endphp
                            <a href="{{route('single.page',['city_slug'=>$page[0]->city_slug,'area_slug'=>$page[0]->area_slug,'category_slug'=>$page[0]->category_slug,'item_slug'=>$page[0]->item_slug])}}" class="author-thumb d-flex align-items-center">
                                <img src="{{get_page_logo($page[0]->logo, 'logo')}}" width="40px" class="rounded-circle" alt="">
                                <h6 class="ms-2 mt-2">{{$page[0]->title}}</h6>
                            </a>
                        @else
                         @if(auth()->user())
                            <a href="{{route('profile')}}" class="author-thumb d-flex align-items-center">
                                <img src="{{get_user_image($user_info->photo, 'optimized')}}" width="40px" class="rounded-circle" alt="">
                                <h6 class="ms-2 mt-2">{{$user_info->name}}</h6>
                            </a>
                        @endif
                         @endif
                        <div class="entry-status">
                            <div class="dropdown">
                                <button class="btn btn-gray dropdown-toggle" type="button" id="postPrivacyDroupdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-earth-americas"></i> {{get_phrase('Public')}}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="postPrivacyDroupdownBtn">
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="post_privacy('private', this, 'postPrivacyDroupdownBtn', 'post_privacy')"><i class="fa-solid fa-user"></i> {{get_phrase('Only Me')}}</a>
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="post_privacy('friends', this, 'postPrivacyDroupdownBtn', 'post_privacy')"><i class="fa-solid fa-users"></i> {{get_phrase('Following')}}</a>
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="post_privacy('public', this, 'postPrivacyDroupdownBtn', 'post_privacy')"><i class="fa-solid fa-user-group"></i> {{get_phrase('Public')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @php
    // Decode the JSON response if available
    $response = session('json_response') ? json_decode(session('json_response'), true) : null;
@endphp

@if ($response && isset($response['validationError']['description']))
    <div class="alert alert-danger">
        {{ $response['validationError']['description'] }}
    </div>
@endif
 @if(auth()->user())
                    <textarea name="description" id="post_article" placeholder="{{  get_phrase("What's on your mind ____", [auth()->user()->name]) }}?"></textarea>
@endif
                    <div id="tab-file" class="post-inner file-tab cursor-pointer p-0 mt-2">
                        <span class="close-btn z-index-2000"><i class="fa fa-close"></i></span>

                        <!--Uploader start-->
                        <div class="file-uploader">
                            <label for="multiFileUploader">
                                <i class="fa-solid fa-cloud-arrow-up text-secondary"></i>
                                <p>{{get_phrase('Click to browse')}}</p>
                            </label>
                            <input type="file" class="fileUploader position-absolute visibility-hidden" name="multiple_files[]" id="multiFileUploader" accept=".jpg,.jpeg,.png,.gif,.mp4,.mov,.wmv,.avi,.mkv,.webm" multiple/>
                            <div class="preview-files">
                                <div class="row justify-content-start px-3"></div>
                            </div>
                        </div>


                <!-- Upload Progress Container -->
                <div id="uploadProgress" class="d-none mt-3">
                    <div class="bg-light p-3 rounded border shadow-sm">
                        <div class="d-flex align-items-center mb-2">
                            <div class="circular-progress me-3">
                                <svg width="60" height="60">
                                    <circle class="bg" cx="30" cy="30" r="26"></circle>
                                    <circle class="fg" cx="30" cy="30" r="26"></circle>
                                </svg>
                                <div class="progress-info">
                                    <span class="percent">0%</span>
                                </div>
                            </div>
                            <div class="progress-details flex-grow-1">
                                <div class="fw-bold text-success mb-1">
                                    <span class="upload-status-label">{{get_phrase('Uploading...')}}</span> 
                                    <span class="percent-label">0%</span>
                                </div>
                                <small id="uploadFileName" class="text-muted d-block text-truncate" style="max-width: 180px;"></small>
                            </div>
                        </div>
                        
                        <!-- Linear Progress Bar (Extra Clarity) -->
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="text-center mt-1">
                            <small class="text-muted status-text">{{get_phrase('Please wait, your post is being processed.')}}</small>
                        </div>
                    </div>
                </div>
                        <!--Uplodaer end-->

                    </div>

                    <div class="post-inner py-3" id="tab-tag">
                        <h4 class="h5"> <a href="javascript:void(0)" onclick="$('#tab-tag').removeClass('current')" class="prev-btn"><i class="fa fa-long-arrow-left"></i></a>{{get_phrase('Tag People')}}
                        </h4>
                        <div class="tag-wrap">
                            
                            <div class="post-tagged">
                                <h4>{{get_phrase('Tagged')}}</h4>
                                <div class="tag-card" id="taggedUsers"></div>
                                <div class="suggesions">
                                    <input class="mt-3" onkeyup="searchFriendsForTagging(this, '#friendsForTagging')" type="search" placeholder="{{get_phrase('Search more peoples')}}">
                                    <h4>{{ get_phrase('Suggestions')}}</h4>

                                    <div class="progress suggestions-loaging-bar d-none"><div class="indeterminate"></div></div>
 @if(auth()->user())
                                    <div class="tag-peoples" id="friendsForTagging">
                                        @php
                                            $friends = \Cache::remember('user_tagging_friends_cached_v1_' . Auth()->user()->id, 300, function() {
                                                return DB::table('users')->whereJsonContains('friends', [Auth()->user()->id])->take(5)->get();
                                            });
                                        @endphp
                                        @include('frontend.main_content.friend_list_for_tagging', array('friends' => $friends))
                                    </div>
                                    @endif
                                </div>
                            </div>

                        </div><!-- Tag People End -->
                    </div>

                    <!-- @include('frontend.main_content.create_post_felling_and_activity') -->

                    @include('frontend.main_content.create_post_location')
                    
                    <!-- Location Tab End -->
                    <div class="modal-footer text-center justify-content-center p-3">
                        <button type="button" data-tab="tab-file" class="btn btn-secondary post-action-btn"><img
                                src="{{asset('storage/images/image.svg')}}" alt="photo">
                                <span>{{get_phrase('Photo')}}/{{get_phrase('Video')}}</span></button>

                        <button type="button" data-tab="tab-tag" class="btn btn-secondary post-action-btn"><img
                                src="{{asset('storage/images/peoples.png')}}" alt="photo"><span>{{get_phrase('Tag People')}}</span></button>
                        {{-- <button type="button" data-tab="tab-feeling" class="btn btn-secondary post-action-btn"><img
                                src="{{asset('storage/images/forum.svg')}}" alt="photo"><span>{{get_phrase('Feelings')}} / {{get_phrase('Activity')}}</span></button> --}}
                        <button type="button" onclick="loadMaps('map')" data-tab="tab-location" class="btn btn-secondary post-action-btn"><img
                                src="{{asset('storage/images/location.png')}}" alt="photo"><span>{{get_phrase('Location')}}</span></button>
                        <button type="button" class="btn btn-secondary post-action-btn" onclick="confirmLiveStreaming()"><img src="{{asset('storage/images/camera.svg')}}"
                                alt="photo"><span>{{get_phrase('Live Video')}}</span></button>
                        <button type="submit" class="btn btn-primary mt-3 rounded w-100 btn-lg">{{get_phrase('Publish')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Create Post Modal End -->
</form>
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('button[data-tab]');
    if (!btn) return;

    const tabId = btn.getAttribute('data-tab');

    // hide all tabs
    document.querySelectorAll('.post-inner').forEach(tab => {
        tab.classList.remove('current');
    });

    // show selected tab
    const activeTab = document.getElementById(tabId);
    if (activeTab) {
        activeTab.classList.add('current');
    }
});
</script>
<style>
    .post-inner {
    display: none;
}

.post-inner.current {
    display: block;
}

/* All action buttons in modal footer: icon + text side by side */
.modal-footer .btn {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    white-space: nowrap !important;
}
.modal-footer .btn img {
    width: 18px;
    height: 18px;
    object-fit: contain;
    flex-shrink: 0;
}

</style>
<style>
    .Common{ background-color:orange; }
    #uploadProgress { overflow: hidden; }

    /* Circular Progress Bar Styles */
    .circular-progress {
        position: relative;
        width: 60px;
        height: 60px;
    }
    .circular-progress svg {
        transform: rotate(-90deg);
        width: 60px;
        height: 60px;
    }
    .circular-progress circle {
        fill: none;
        stroke-width: 5;
        stroke-linecap: round;
    }
    .circular-progress circle.bg {
        stroke: #e9ecef;
    }
    .circular-progress circle.fg {
        stroke: #198754; /* Success green */
        stroke-dasharray: 164; /* 2 * PI * radius (26) */
        stroke-dashoffset: 164;
        transition: stroke-dashoffset 0.2s ease-out;
    }
    .circular-progress .progress-info {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-weight: bold;
        color: #198754;
        transition: all 0.3s ease;
    }
    .circular-progress .progress-info .percent {
        font-size: 11px;
    }
    
    /* When 100% / Success: Whole Circle Green */
    .circular-progress.success circle.bg {
        stroke: #198754;
        opacity: 0.2;
    }
    .circular-progress.success .progress-info {
        background: #198754;
        color: #fff;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .circular-progress.success .progress-info .percent {
        font-size: 18px;
    }
    .circular-progress.error .progress-info {
        color: #dc3545;
    }
    .circular-progress.error .fg {
        stroke: #dc3545;
    }
    
    #story-preview-content img, 
    #story-preview-content video {
        max-width: 100%;
        max-height: 250px;
        border-radius: 4px;
        display: block;
        margin: auto;
    }
</style>
<script>
/* Reset progress bar logic */
    var bar  = document.querySelector('#uploadProgress .fg');
    var percentText = document.querySelector('#uploadProgress .percent');
    var linearBar = document.querySelector('#uploadProgress .progress-bar');
    var percentLabel = document.querySelector('#uploadProgress .percent-label');
    var wrap = document.getElementById('uploadProgress');
    var container = document.querySelector('.circular-progress');

    function resetUploadUI() {
        if (wrap) wrap.classList.add('d-none');
        if (container) container.classList.remove('success', 'error');
        if (bar) {
            bar.style.strokeDashoffset = '164';
        }
        if (percentText) {
            percentText.textContent = '0%';
        }
        if (linearBar) {
            linearBar.style.width = '0%';
        }
        if (percentLabel) {
            percentLabel.textContent = '0%';
        }
    }
    
    resetUploadUI();

    function generatePostSubmissionId() {
        var el = document.getElementById('submission_id');
        if (el) {
            el.value = 'post-' + Date.now() + '-' + Math.random().toString(36).substring(2, 11);
        }
    }
    
    $(document).ready(function() {
        generatePostSubmissionId();
        
        // Regenerate token when modal is opened to ensure a fresh token for every new post
        $('#createPost').on('show.bs.modal', function () {
            generatePostSubmissionId();
        });
        
        // Also regenerate after form submission is processed
        $('#createPostForm').on('submit', function() {
            setTimeout(generatePostSubmissionId, 1000);
        });
    });
</script>