@php 
    use Illuminate\Support\Str; 
    use Illuminate\Support\Facades\DB;
@endphp

<div class="stg-wrap" id="stg-wrap-story-gallery">
    <div class="story-content-box" style="min-height: 500px; border-radius: 12px; overflow: hidden; background: #000; position: relative;">
        
        @if($user_stories->isEmpty())
            <div style="min-height:500px; display:flex; align-items:center; justify-content:center; color:#fff;">
                <p>No active stories found.</p>
            </div>
        @else
            {{-- Instagram-style Story Carousel --}}
            <div id="userStoryCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
                
                {{-- Progress bars at the top --}}
                <div class="carousel-indicators" style="top: 10px; bottom: auto; margin: 0 10px; gap: 4px; z-index: 20;">
                    @foreach($user_stories as $index => $story)
                        <button type="button" data-bs-target="#userStoryCarousel" data-bs-slide-to="{{ $index }}" 
                                class="{{ $story->story_id == $initial_story_id ? 'active' : '' }}" 
                                style="height: 3px; border-radius: 2px; margin: 0; flex: 1; border: none;"></button>
                    @endforeach
                </div>

                <div class="carousel-inner">
                    @foreach($user_stories as $index => $story)
                        <div class="carousel-item {{ $story->story_id == $initial_story_id ? 'active' : '' }}">
                            
                            @if($story->content_type == 'text')
                                @php
                                    $text_info = json_decode($story->description, true);
                                    $bg  = '#' . ltrim($text_info['bg-color'] ?? 'ffffff', '#');
                                    $clr = '#' . ltrim($text_info['color']    ?? '000000', '#');
                                @endphp
                                <div style="min-height:500px; display:flex; align-items:center; justify-content:center;
                                            background-color:{{ $bg }}; padding:40px;">
                                    <h2 style="color:{{ $clr }}; text-align:center; word-break:break-word; margin:0; font-weight:600; font-family: 'Outfit', sans-serif;">
                                        {{ $text_info['text'] ?? '' }}
                                    </h2>
                                </div>
                            @else
                                @php 
                                    $media = $story->media_list->first(); 
                                @endphp

                                @if($media)
                                    @if($media->file_type == 'video')
                                        <div style="background:#000; height:500px; display:flex; align-items:center; justify-content:center;">
                                            <video class="d-block w-100" style="max-height:500px; object-fit:contain;" controls autoplay muted>
                                                <source src="{{ Str::startsWith($media->file_name, 'http') ? $media->file_name : asset('storage/story/videos/' . $media->file_name) }}">
                                            </video>
                                        </div>
                                    @else
                                        <div style="background:#000; height:500px; display:flex; align-items:center; justify-content:center;">
                                            <img class="d-block w-100" src="{{ Str::startsWith($media->file_name, 'http') ? $media->file_name : asset('storage/story/images/' . $media->file_name) }}" 
                                                 style="max-height:500px; object-fit:contain;">
                                        </div>
                                    @endif
                                @else
                                    <div style="min-height:500px; display:flex; align-items:center; justify-content:center; color:#fff;">
                                        <p>Media missing.</p>
                                    </div>
                                @endif
                            @endif

                            {{-- Story Header (User Info) --}}
                            <div style="position:absolute; top:30px; left:20px; color:#fff; text-shadow: 0 2px 4px rgba(0,0,0,0.5); z-index:15;">
                                <div class="d-flex align-items-center">
                                    <img src="{{ get_user_image($story->photo, 'optimized') }}" class="rounded-circle border border-2 border-white shadow" width="45" height="45" style="object-fit: cover;">
                                    <div class="ms-2">
                                        <div class="fw-bold" style="font-size: 1.1rem;">{{ $story->name }}</div>
                                        <small class="opacity-75">{{ date_formatter($story->created_at, 2) }}</small>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Navigation Arrows --}}
                @if($user_stories->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#userStoryCarousel" data-bs-slide="prev" style="width: 15%; z-index: 25;">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#userStoryCarousel" data-bs-slide="next" style="width: 15%; z-index: 25;">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>

<style>
    /* Premium Story Viewer Styles */
    #userStoryCarousel .carousel-item {
        transition: transform 0.3s ease-in-out;
    }
    #userStoryCarousel .carousel-indicators [data-bs-target] {
        background-color: rgba(255,255,255,0.3);
    }
    #userStoryCarousel .carousel-indicators .active {
        background-color: #fff;
    }
    #stg-wrap-story-gallery {
        box-shadow: 0 15px 35px rgba(0,0,0,0.6);
        border-radius: 12px;
        overflow: hidden;
    }
    /* Hide default video play button overlap if any */
    video::-webkit-media-controls-panel {
        background-image: none !important;
    }
</style>