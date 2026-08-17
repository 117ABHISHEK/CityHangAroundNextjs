@php use Illuminate\Support\Str; @endphp

<div class="timeline-carousel px-3 bg-white owl-carousel">
    <!--  avatar end -->
    <a href="#" class="story-entry story-entry-details active" onclick="loadSingleStoryDetailsOnModal('{{$story_details->story_id}}', this)">
        <div class="avatar-online d-flex align-items-center mb-2">
            <div class="avatar-img"> <img src="{{get_user_image($story_details->photo, 'optimized')}}" alt="">
            </div>
            <div class="avatar-info ms-2">
                <h4 class="ava-nave">{{$story_details->name}}</h4>
                <div class="activity-time small-text text-muted">{{date_formatter($story_details->created_at, 2)}}</div>
            </div>
        </div>
    </a><!--  avatar end -->

    @foreach ($stories as $story)
        <!--  avatar end -->
        <a href="#" class="story-entry story-entry-details" onclick="loadSingleStoryDetailsOnModal('{{$story->story_id}}', this)">
            <div class="avatar-online d-flex align-items-center mb-2">
                <div class="avatar-img"> <img src="{{get_user_image($story->photo, 'optimized')}}" alt="">
                </div>
                <div class="avatar-info ms-2">
                    <h4 class="ava-nave">{{$story->name}}</h4>
                    <div class="activity-time small-text text-muted">{{date_formatter($story->created_at, 2)}}</div>
                </div>
            </div>
        </a><!--  avatar end -->
    @endforeach
</div> <!-- Online Status End -->


<div class="stg-wrap" id="stg-wrap-story-gallery">
    <div class="story-gallery owl-carousel">
        <div class="st-item">
            <div class="carousel-inner mb-5">
                <div class="stc-wrap">
                    <div class="st-child-gallery stc-bg owl-carousel">
                        @if($story_details->content_type == 'text')
                            @php
                                $text_info = json_decode($story_details->description, true);
                            @endphp
                            <div class="stories-view-container p-4 text-center" style="min-height: 300px; display: flex; align-items: center; justify-content: center; border-radius: 10px; color: {{ '#'.($text_info['color'] ?? '000') }}; background-color: {{ '#'.($text_info['bg-color'] ?? 'fff') }};">
                                <h3 style="color: inherit;">{{$text_info['text'] ?? ''}}</h3>
                            </div>  
                        @else
                            @php $media_files = DB::table('media_files')->where('story_id', $story_details->story_id)->get(); @endphp
                          @foreach($media_files as $media_file)

    @if($media_file->file_type == 'video')

        @if(Str::startsWith($media_file->file_name, 'http'))
            <video class="plyr-js w-100" autoplay controlsList="nodownload">
                <source src="{{$media_file->file_name}}">
            </video>
        @else
            <video class="plyr-js w-100" autoplay controlsList="nodownload">
                <source src="{{asset('storage/story/videos/'.$media_file->file_name)}}">
            </video>
        @endif

    @else

        @if(Str::startsWith($media_file->file_name, 'http'))
            <img class="w-100" src="{{$media_file->file_name}}">
        @else
            <img class="w-100" src="{{asset('storage/story/images/'.$media_file->file_name)}}">
        @endif

    @endif

@endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var firstStory = document.querySelector(".story-entry-details.active");
    if(firstStory){
        firstStory.click();
    }
});
</script>