
@php use Illuminate\Support\Str; @endphp

<div class="timeline-carousel owl-carousel owl-loaded owl-drag mb-3" id="storiesSection">

    <!-- CREATE STORY -->
    <a href="javascript:void(0)"
       onclick="createStoryForm('frontend.story.create_story')"
       class="story-entry m-0">

        <div class="story-create-item"
             style="background-image:url('{{ get_user_image(Auth()->user()->photo) }}')">
        </div>

        <div class="d-flex text-center ct-story">
            <span><i class="fa fa-plus"></i></span>
            <p>{{ get_phrase('Create story') }}</p>
        </div>

        <div class="story-shadow">
            <div class="story-text"></div>
        </div>
    </a>


    <!-- STORIES -->
    @foreach ($stories as $story)

        <a href="javascript:void(0)"
           class="story-entry creat-story m-0"
           onclick="loadStoryDetailsOnModal('{{ $story->story_id }}')">

            <!-- USER PHOTO -->
            <div class="story-small-img">
                <img src="{{ get_user_image($story->photo, 'optimized') }}" alt="photo">
            </div>


            {{-- TEXT STORY --}}
            @if($story->content_type == 'text')

                @php
                    $text_info = json_decode($story->description, true);
                @endphp

                <div class="stories-view"
                     style="color:#{{ $text_info['color'] }};
                            background-color:#{{ $text_info['bg-color'] }};">
                    {{ $text_info['text'] }}
                </div>


            {{-- MEDIA STORY --}}
            @else

                @php
                    $media_file = DB::table('media_files')
                        ->where('story_id', $story->story_id)
                        ->first();
                @endphp

                @if($media_file)

                    {{-- VIDEO --}}
                    @if($media_file->file_type == 'video')

                        @if(Str::startsWith($media_file->file_name,'http'))

                            <video muted class="plyr-js initialized">
                                <source src="{{ $media_file->file_name }}">
                            </video>

                        @else

                            <video muted class="plyr-js initialized">
                                <source src="{{ asset('storage/story/videos/'.$media_file->file_name) }}">
                            </video>

                        @endif


                    {{-- IMAGE --}}
                    @else

                        @if(Str::startsWith($media_file->file_name,'http'))

                            <figure class="avatar-img rounded"
                                    style="background-image:url('{{ $media_file->file_name }}')">
                            </figure>

                        @else

                            <figure class="avatar-img rounded"
                                    style="background-image:url('{{ asset('storage/story/images/'.$media_file->file_name) }}')">
                            </figure>

                        @endif

                    @endif

                @endif

            @endif


            <!-- STORY TEXT -->
            <div class="story-shadow">
                <div class="story-text">
                    <h4 class="text-nav">{{ $story->name }}</h4>
                    <p class="text-des">{{ date_formatter($story->created_at, 2) }}</p>
                </div>
            </div>

        </a>

    @endforeach

</div>

@include('frontend.story.scripts')