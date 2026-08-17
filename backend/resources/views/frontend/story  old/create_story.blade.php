<div class="cts-wrap">
    <div class="entry-header d-flex justify-content-between mb-5">
        <div class="ava-info d-flex align-items-center">
            <div class="flex-shrink-0">
                <img src="{{get_user_image(Auth()->user()->id, 'optimized')}}" class="rounded-circle user_image_show_on_modal" alt="...">
            </div>
            <div class="ava-desc ms-2">
                <h6 class="mb-0">{{Auth()->user()->name}}</h6>
            </div>
        </div>
        <div class="dropdown">
            <button class="btn btn-gray dropdown-toggle" type="button" id="privacyDroupdownBtn"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-earth-americas"></i> {{get_phrase('Public')}}
            </button>
            <ul class="dropdown-menu" aria-labelledby="privacyDroupdownBtn">
                <li><a class="dropdown-item" href="javascript:void(0)" onclick="story_privacy('private', this)"><i class="fa-solid fa-user"></i> {{get_phrase('Only Me')}}</a>
                </li>
                <li><a class="dropdown-item" href="javascript:void(0)" onclick="story_privacy('friends', this)"><i class="fa-solid fa-users"></i> {{get_phrase('Friends')}}</a>
                </li>
                <li><a class="dropdown-item" href="javascript:void(0)" onclick="story_privacy('public', this)"><i class="fa-solid fa-user-group"></i> {{get_phrase('Public')}}</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="{{route('create_story')}}" class="text-form text-story-form" method="POST">
                @Csrf
                <textarea name="description" onkeyup="$('.input-prev-text').text($(this).val())" placeholder="What's on your mind John?" spellcheck="false"></textarea>
                <img class="text-imogi" src="{{asset('storage/images/smile.png')}}" alt="">

                <input type="hidden" value="fafafa" class="bg-color-input-field" name="bg-color">
                <input type="hidden" value="636363" class="color-input-field" name="color">
                <input type="hidden" value="user" name="publisher">
                <input type="hidden" value="text" name="content_type">
                <input type="hidden" value="public" class="story_privacy" name="privacy">

                <div class="color-plate">
                    <a href="javascript:void(0)" class="color-circle" onclick="selectColor('f00')"></a>
                    <a href="javascript:void(0)" class="color-circle" onclick="selectColor('5a2ff9')"></a>
                    <a href="javascript:void(0)" class="color-circle" onclick="selectColor('ff7856')"></a>
                    <a href="javascript:void(0)" class="color-circle" onclick="selectColor('f92fd7')"></a>
                    <a href="javascript:void(0)" class="color-circle" onclick="selectColor('2f94f9')"></a>
                    <a href="javascript:void(0)" class="color-circle" onclick="selectColor('000000')"></a>
                </div>
                <div class="input-prev input-prev-text"></div>
            </form>
            <form action="{{route('create_story')}}" method="post" class="text-form d-hidden file-story-form" enctype="multipart/form-data">
                @Csrf
                <textarea name="description" class="d-hidden"></textarea>
                <input type="hidden" value="user" name="publisher">
                <input type="hidden" value="file" name="content_type">
                <input type="hidden" value="public" class="story_privacy" name="privacy">

                <img class="text-imogi" src="{{asset('storage/images/smile.png')}}" alt="">
                <div class="input-prev">
                    <a href="javascript:void(0)" onclick="$('#file-story-input').click()" class="d-block body-bg file">
                        {{-- <img src="{{asset('storage/images/file.png')}}" alt=""> --}}
                        <i class="fa-solid fa-file"></i>
                        {{get_phrase('Create Photo / Video Story')}}</a>
                    <input type="file" id="file-story-input" class="hidden-file-input" name="story_files[]" accept="image/*,video/*">
                </div>

                <!-- Story Preview Container -->
                <div id="story-preview-container" class="d-none mt-3 position-relative">
                    <div class="preview-card p-2 border rounded bg-white shadow-sm">
                        <div id="story-preview-content" class="text-center overflow-hidden rounded" style="max-height: 250px;">
                            <!-- Preview will be injected here -->
                        </div>
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 bg-white rounded-circle p-2 shadow-sm" style="transform: translate(50%, -50%);" onclick="removeStoryFiles()"></button>
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
                            <small class="text-muted status-text">{{get_phrase('Please wait, your story is being processed.')}}</small>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="story-options gap-3 align-items-center justify-content-between">
        <div class="row">
            <div class="col-md-6">
                <a href="#" onclick="storyType(this, 'text-type-story')" class="mt-2 border text-type-story">
                    {{-- <img src="{{asset('storage/images/text-height.png')}}" alt=""> --}}
                    <i class="fa-solid fa-text-height"></i>
                    {{get_phrase('Create a Text Story')}}</a>
            </div>
            <div class="col-md-6">
                <a href="#" onclick="storyType(this, 'file-type-story')" href="#" class="mt-2 file-type-story">
                    {{-- <img src="{{asset('storage/images/file.png')}}" alt=""> --}}
                    <i class="fa-solid fa-file"></i>
                    {{get_phrase('Create Photo / Video Story')}}</a>
        </div>
    </div>
</div>

<div class="d-flex story-buttons gap-3 mt-4 ">
    <a href="javascript:void(0)" onclick="createStory('text-story-form');" class="btn common_btn  border border-danger text-story-submit">{{get_phrase('Share to story')}}</a>
    <a href="javascript:void(0)" onclick="createStory('file-story-form');" class="btn common_btn  border border-danger file-story-form d-hidden">{{get_phrase('Share to story')}}</a>
    <a href="javascript:void(0)" class="btn common_btn border border-danger " data-bs-dismiss="modal" aria-label="Close">{{get_phrase('Discard')}}</a>
</div>
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


</script>