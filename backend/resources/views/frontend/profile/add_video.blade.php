<form class="ajaxForm" action="{{ route('profile.upload_video') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="entry-header d-flex justify-content-between">
        <div class="ava-info d-flex align-items-center">
            <div class="flex-shrink-0">
                <img src="{{ get_user_image(auth()->user()->photo,'optimized') }}" class="rounded-circle user_image_show_on_modal" alt="...">
            </div>
            <div class="ava-desc ms-2">
                <h3 class="mb-0 h6">{{ auth()->user()->name }}</h3>
                <span class="meta-time text-muted"><a href="#">{{ auth()->user()->profession }}</a></span>
            </div>
        </div>
        <div class="post-controls dropdown">
            <select name="privacy" id="privacy" class="form-control bg-secondary border-0">
                <option value="public">{{ get_phrase('Public') }}</option>
                <option value="private">{{ get_phrase('Private') }}</option>
            </select>
        </div>
    </div>
    
    <div class="form-group pt-2">
        <label for="description">{{ get_phrase('Description') }}</label>
        <textarea class="bg-secondary border-0 form-control" name="description" rows="3" placeholder="{{ get_phrase('Write something about your video...') }}"></textarea>
    </div>
    
    <div class="form-group">
        <label for="video">{{ get_phrase('Video File') }}</label>
        <input type="file" name="video" id="video" class="form-control bg-secondary border-0" accept="video/*" required>
        <small class="text-muted">{{ get_phrase('Supported formats: MP4, MOV, WMV, MKV, WEBM, AVI, M4V (Max: 500MB)') }}</small>
    </div>
    
    <button type="submit" class="w-100 btn btn-primary mt-3">{{ get_phrase('Upload Video') }}</button>
</form>

@include('frontend.initialize')
