@php $user_info = Auth()->user() @endphp



@include('frontend.main_content.create_post_modal')





<script src="https://cdn.tailwindcss.com"></script>
<style>
    .logo-color {
        color: #ff4939;
    }
    .btn-trans {
        background: transparent;
        border: none;
        outline: none;
        width: 100%;
        text-align: left;
        color: #888;
    }
</style>

<div class="w-full mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Left Column (Timeline Feed) -->
    <div class="space-y-6">
        <!-- Story Section -->
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center space-x-4 overflow-x-auto pb-4 story-scroll-container">
                <style>
                    .story-scroll-container::-webkit-scrollbar {
                        height: 5px;
                    }
                    .story-scroll-container::-webkit-scrollbar-track {
                        background: #f1f1f1;
                        border-radius: 10px;
                    }
                    .story-scroll-container::-webkit-scrollbar-thumb {
                        background: #ff4939;
                        border-radius: 10px;
                    }
                    .story-card { min-width: 90px; width: 90px; height: 130px; }
                    .add-story-btn { 
                        position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%);
                        background-color: #ff4939; color: white; width: 28px; height: 28px;
                        border-radius: 50%; display: flex; align-items: center; justify-content: center;
                        font-size: 20px; border: 2px solid white; z-index: 10;
                        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                    }
                    .story-card:hover { transform: scale(1.02); transition: transform 0.2s; }
                </style>

                <!-- My Story Card -->
                <div class="story-card rounded-lg overflow-hidden relative shadow-sm border border-gray-100 cursor-pointer flex-shrink-0" 
                     @if(isset($my_story)) onclick="loadStoryDetailsOnModal('{{ $my_story->story_id }}')" @endif>
                    
                    @if(isset($my_story))
                        @if($my_story->content_type == 'text')
                            @php $text_info = json_decode($my_story->description, true); @endphp
                            <div class="w-full h-full flex items-center justify-center text-[10px] p-2 text-center font-bold break-words" 
                                 style="color:#{{ $text_info['color'] ?? '000' }}; background-color:#{{ $text_info['bg-color'] ?? 'fff' }};">
                                {{ Str::limit($text_info['text'] ?? '', 50) }}
                            </div>
                        @else
                            @php $media_file = $my_story->mediaFiles->first(); @endphp
                            @if($media_file)
                                @if($media_file->file_type == 'video')
                                    <video class="w-full h-full object-cover"><source src="{{ asset('storage/story/videos/'.$media_file->file_name) }}"></video>
                                @else
                                    <img src="{{ asset('storage/story/images/'.$media_file->file_name) }}" class="w-full h-full object-cover" alt="My Story">
                                @endif
                            @endif
                        @endif
                    @else
                        <img src="{{get_user_image(Auth()->user()->photo)}}" alt="Create Story" class="w-full h-full object-cover opacity-60" />
                        <div class="absolute inset-0 bg-black bg-opacity-10"></div>
                    @endif

                    <!-- + Button (Always opens create form) -->
                    <div class="add-story-btn" title="{{ get_phrase('Create Story') }}" onclick="event.stopPropagation(); createStoryForm('frontend.story.create_story')">
                        <i class="fa fa-plus"></i>
                    </div>
                </div>

                <!-- Friends Stories -->
                @foreach ($stories as $story)
                    <div class="story-card rounded-lg overflow-hidden relative shadow-sm border border-gray-100 cursor-pointer flex-shrink-0" 
                         onclick="loadStoryDetailsOnModal('{{ $story->story_id }}')">
                        @if($story->content_type == 'text')
                            @php $text_info = json_decode($story->description, true); @endphp
                            <div class="w-full h-full flex items-center justify-center text-[10px] p-2 text-center font-bold break-words" 
                                 style="color:#{{ $text_info['color'] ?? '000' }}; background-color:#{{ $text_info['bg-color'] ?? 'fff' }};">
                                {{ Str::limit($text_info['text'] ?? '', 50) }}
                            </div>
                        @else
                            @php $media_file = $story->mediaFiles->first(); @endphp
                            @if($media_file)
                                @if($media_file->file_type == 'video')
                                    <video class="w-full h-full object-cover"><source src="{{ asset('storage/story/videos/'.$media_file->file_name) }}"></video>
                                @else
                                    <img src="{{ asset('storage/story/images/'.$media_file->file_name) }}" class="w-full h-full object-cover" alt="Story">
                                @endif
                            @endif
                        @endif
                        <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 w-9 h-9 rounded-full border-2 border-white overflow-hidden shadow-md">
                            <img src="{{ get_user_image($story->photo) }}" class="w-full h-full object-cover" alt="{{ $story->name }}">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Post Input -->
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center space-x-3">
                <img src="{{ get_user_image(Auth()->user()->photo, 'optimized') }}"
                    class="w-10 h-10 rounded-full object-cover" alt="User" />
                <button class="btn-trans" data-bs-toggle="modal" data-bs-target="#createPost">
                    {{ get_phrase("What's on your mind, :name?", ['name' => auth()->user()->name]) }}
                </button>
            </div>
        </div>

        <!-- Timeline Posts Feed — ONLY real posts here, no widgets -->
        <div id="timeline-posts" class="space-y-4">
            @include('frontend.main_content.posts', ['types' => ['user_post', 'page', 'events', 'blogs']])
        </div>

    </div>

    <!-- Sidebar Column -->
    <div class="space-y-6">
        <!-- Feature Listings -->
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-bold">Trending Listings</h2>
                <a href="{{ route('pages') }}" class="text-sm logo-color hover:underline">View more</a>
            </div>
           <div class="grid grid-cols-3  gap-3">
    @php $count = 0; @endphp
    @foreach ($recentBusinesses as $business)
        @if ($count == 3)
            @break
        @endif
        <a href="{{ route('single.page', [
    'city_slug' => $business->city->city_slug,
    'area_slug' => $business->area->area_slug,
    'category_slug' => $business->categories->first()->category_slug,
    'item_slug' => $business->item_slug,
]) }}" class="block">
            <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm flex flex-col h-full">
                <img src="{{ get_page_logo($business->logo, 'logo') }}" alt=""
                    class="w-full h-24 object-cover flex-shrink-0" />
                <div class="p-2 flex flex-col justify-between flex-grow">
                    <p class="font-semibold text-sm line-clamp-2">{{ strip_tags($business->title ?? '') }}</p>
                    <p class="text-xs text-gray-500  mt-1">
                        {{ $business->category->name ?? ($business->categories->first()->name ?? 'Business') }}
                    </p>
                </div>
            </div>
        </a>
        @php $count++; @endphp
    @endforeach
</div>

        </div>

        <!-- Feature Deals -->
       <div class="bg-white p-4 rounded-lg shadow">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-lg font-bold">Trending Deals</h2>
        <a href="{{ route('allproducts') }}" class="text-sm hover:underline logo-color">View more</a>
    </div>
    <div class="grid grid-cols-3 gap-3">
        @php $count = 0; @endphp
        @foreach ($recentProducts as $product)
            @if ($count == 3)
                @break
            @endif

            @php
                $page = $product->page;
                $city_slug = $page?->city?->city_slug ?? 'city';
                $area_slug = $page?->area?->area_slug ?? 'area';
                $item_slug = $page?->item_slug ?? 'item';
                $catSlug = $page?->categories?->last()?->category_slug ?? 'category';
                $productCatSlug = $product->productCategories?->last()?->product_category_slug ?? 'subcategory';
            @endphp

            <a href="{{ route('single.product', [
                'city_slug' => $city_slug,
                'area_slug' => $area_slug,
                'category_slug' => $catSlug,
                'item_slug' => $item_slug,
                'product_category_slug' => $productCatSlug,
                'product_slug' => $product->product_slug ?? 'product'
            ]) }}">
                <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm">
                    <img src="{{ $product->image ? (filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('storage/marketplace/' . $product->image)) : asset('assets/frontend/images/default-product.png') }}"
                        alt="{{ strip_tags($product->title ?? '') }}" class="w-full h-24 object-cover" />
                    <div class="p-2">
                        <p class="font-semibold text-sm">{{ strip_tags($product->title ?? '') }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $page?->categories?->last()?->category_name ?? 'Product' }}
                        </p>
                    </div>
                </div>
            </a>

            @php $count++; @endphp
        @endforeach
    </div>
</div>


        <!-- Feature Events -->
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-bold">Trending Events</h2>
                <a href="{{ route('event') }}" class="text-sm hover:underline logo-color">View more</a>
            </div>
            <div class="grid grid-cols-3 gap-3">
               @php $count = 0; @endphp
@foreach ($recentEvents as $event)
    @if ($count == 3)
        @break
    @endif

    <a href="{{ route('single.event', [
        'id' => $event->id,
        'city_slug' => $event->city->city_slug,
        'area_slug' => $event->area->area_slug,
        'category_slug' => $event->categories->first()->category_slug ?? $event->category->category_slug ?? 'event',
        'event_slug' => $event->event_slug,
    ]) }}" class="block">
        <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm">
            <img src="{{ get_event_banner_image($event, 'thumbnail') }}"
                alt="{{ strip_tags($event->title ?? '') }}" class="w-full h-24 object-cover" />
            <div class="p-2">
                <p class="font-semibold text-sm">{{ strip_tags($event->title ?? '') }}</p>
                <p class="text-xs text-gray-500">
                    {{ $event->category->name ?? ($event->categories->first()->name ?? 'Event') }}
                </p>
            </div>
        </div>
    </a>

    @php $count++; @endphp
@endforeach



            </div>
        </div>
    </div>

</div>

@include('frontend.main_content.scripts')
