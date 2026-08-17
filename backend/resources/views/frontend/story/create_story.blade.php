

@php use Illuminate\Support\Str; @endphp

<div class="cts-wrap p-3">
    {{-- Header Section --}}
    <div class="entry-header d-flex justify-content-between mb-4">
        <div class="ava-info d-flex align-items-center">
            <div class="flex-shrink-0">
                <img src="{{get_user_image(Auth()->user()->id, 'optimized')}}" class="rounded-circle" alt="..." style="width: 45px; height: 45px; object-fit: cover;">
            </div>
            <div class="ava-desc ms-2">
                <h6 class="mb-0">{{Auth()->user()->name}}</h6>
            </div>
        </div>
        <div class="dropdown">
            <button class="btn btn-gray dropdown-toggle" type="button" id="privacyDroupdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-earth-americas"></i> {{get_phrase('Public')}}
            </button>
            <ul class="dropdown-menu" aria-labelledby="privacyDroupdownBtn">
                <li><a class="dropdown-item" href="javascript:void(0)" onclick="story_privacy('private', this)"><i class="fa-solid fa-user"></i> {{get_phrase('Only Me')}}</a></li>
                <li><a class="dropdown-item" href="javascript:void(0)" onclick="story_privacy('friends', this)"><i class="fa-solid fa-users"></i> {{get_phrase('Friends')}}</a></li>
                <li><a class="dropdown-item" href="javascript:void(0)" onclick="story_privacy('public', this)"><i class="fa-solid fa-earth-americas"></i> {{get_phrase('Public')}}</a></li>
            </ul>
        </div>
    </div>

    {{-- 
        THE MASTER FORM 
        Using one single form for both types is the most stable way for CloudPanel.
    --}}
    <form action="{{route('create_story')}}" method="POST" enctype="multipart/form-data" id="master-story-form">
        @csrf
        <input type="hidden" name="content_type" id="story-content-type" value="text">
        <input type="hidden" name="publisher" value="user">
        <input type="hidden" name="privacy" class="story_privacy" value="public">
        
        {{-- Fields for Text Story --}}
        <input type="hidden" name="color" class="color-input-field" value="000">
        <input type="hidden" name="bg-color" class="bg-color-input-field" value="fff">

        {{-- TEXT SECTION --}}
        <div id="text-story-section" class="story-ui-section">
            <div class="form-group">
                <textarea name="description" class="form-control input-prev-text" rows="5" placeholder="{{get_phrase('What is on your mind?')}}" style="border-radius: 10px; padding: 15px;"></textarea>
            </div>
        </div>

        {{-- FILE SECTION (Hidden by default) --}}
        <div id="file-story-section" class="story-ui-section" style="display: none;">
            <label for="file-upload-input-live" class="upload-box p-5 text-center d-block w-100"
                 style="cursor: pointer; border: 2px dashed #adadad; border-radius: 10px; background: #f9f9f9;">
                
                <img src="{{asset('assets/frontend/uploader/file.png')}}" width="50" class="mb-3"><br>
                <h5 class="text-muted">{{get_phrase('Click to select Photos or Videos')}}</h5>
                <p class="small text-secondary">MP4, JPG, PNG supported</p>
                
                {{-- Feedback Area --}}
                <div id="file-status-message" class="mt-3 fw-bold text-primary"></div>
            </label>
            {{-- The actual input - outside the label click zone issues --}}
            <input type="file" id="file-upload-input-live" name="story_files[]" multiple style="position:absolute; left:-9999px; opacity:0; width:1px; height:1px;">
        </div>
    </form>

    {{-- TYPE SELECTION BUTTONS --}}
    <div class="story-options mt-4">
        <div class="row g-2">
            <div class="col-6">
                <div class="option-card p-3 border text-center text-type-btn active-opt" 
                     onclick="toggleStoryMode('text')" style="cursor: pointer; border-radius: 8px;">
                    <img src="{{asset('assets/frontend/images/text-height.png')}}" width="25">
                    <p class="mb-0 mt-1 small fw-bold">{{get_phrase('Text Story')}}</p>
                </div>
            </div>
            <div class="col-6">
                <div class="option-card p-3 border text-center file-type-btn" 
                     onclick="toggleStoryMode('file')" style="cursor: pointer; border-radius: 8px;">
                    <img src="{{asset('assets/frontend/uploader/file.png')}}" width="25">
                    <p class="mb-0 mt-1 small fw-bold">{{get_phrase('Photo / Video')}}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FOOTER SUBMIT --}}
<div class="modal-footer border-0">
    <button type="button" class="btn btn-primary w-100 py-3" onclick="submitMasterForm();" style="border-radius: 8px; font-weight: 600;">
        {{get_phrase('Share to Story')}}
    </button>
</div>

<style>
    .active-opt { border: 2px solid #007bff !important; background: #eef6ff !important; color: #007bff; }
    .upload-box:hover { background: #f0f0f0 !important; border-color: #007bff !important; }
    .cursor-pointer { cursor: pointer; }
</style>

<script>
    "use strict";

    // Toggle between Text and File Modes
    function toggleStoryMode(mode) {
        if(mode === 'file') {
            $('#text-story-section').hide();
            $('#file-story-section').show();
            $('#story-content-type').val('file');
            $('.file-type-btn').addClass('active-opt');
            $('.text-type-btn').removeClass('active-opt');
        } else {
            $('#file-story-section').hide();
            $('#text-story-section').show();
            $('#story-content-type').val('text');
            $('.text-type-btn').addClass('active-opt');
            $('.file-type-btn').removeClass('active-opt');
        }
    }

    // Update text when files are selected
    $('#file-upload-input-live').on('change', function() {
        var files = this.files.length;
        if(files > 0) {
            $('#file-status-message').html('<i class="fa fa-check-circle"></i> ' + files + ' file(s) selected');
        }
    });

    // Final Submission Logic
    function submitMasterForm() {
        var type = $('#story-content-type').val();
        
        if(type === 'file' && $('#file-upload-input-live')[0].files.length === 0) {
            alert('Please select at least one photo or video.');
            return;
        }

        $('#master-story-form').submit();
    }
</script>