 @php $user_info = Auth()->user() @endphp
   @include('frontend.main_content.create_post_modal')

<script src="https://cdn.tailwindcss.com"></script>
    <style>
      .logo-color {
        color: #ff4939;
      }
    </style>

    <div class="max-w-7xl mx-auto px-4 py-6 grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Left Column (Timeline Feed) -->
      <div class="space-y-6">
        <!-- Story Section -->
        <div class="bg-white p-4 rounded-lg shadow">
          <div class="flex items-center space-x-4">
            <div class="w-20 h-28 rounded-lg overflow-hidden relative">
              <a href="javascript:void(0)" onclick="createStoryForm('frontend.story.create_story')" src="{{get_user_image(Auth()->user()->photo)}}" class="story-entry m-0">
              
              <img
                src="https://plus.unsplash.com/premium_photo-1679088032275-b4fb24933d5a?q=80&w=688&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                alt="Story"
                class="w-full h-full object-cover"
                   /></a>
              <span
                style="background-color: #ff4939"
                class="absolute bottom-2 left-1/2 transform -translate-x-1/2 text-white text-sm px-2 py-1 rounded"
                >+</span
              >
            </div>
          </div>
        </div>

        <!-- Post Input -->
        <div class="bg-white p-4 rounded-lg shadow">
          <div class="flex items-center space-x-3">
            <img
              src="https://plus.unsplash.com/premium_photo-1679088032275-b4fb24933d5a?q=80&w=688&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
              class="w-10 h-10 rounded-full"
              alt="User"
            />
            <button class="btn-trans" data-bs-toggle="modal" data-bs-target="#createPost"  
                    >
                {{  get_phrase("What's on your mind ____", [auth()->user()->name]) }}?
            </button>
          </div>
        </div>

      
      @foreach ($vidoes as $video )
        @php
            $post = DB::table('posts')->where('privacy', '!=', 'private')
            ->where('publisher', 'video_and_shorts')
            ->where('publisher_id', $video->id)
            ->first();
        @endphp
        @php
        
        $total_comments = DB::table('comments')->where('comments.is_type', 'post')->where('comments.id_of_type', $post->post_id)->where('comments.parent_id', 0)->get()->count();


        $comments = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->where('comments.is_type', 'post')
            ->where('comments.id_of_type', $post->post_id)
            ->where('comments.parent_id', 0)
            ->select('comments.*', 'users.name', 'users.photo')
            ->orderBy('comment_id', 'DESC')->take(1)->get();


        $tagged_user_ids = json_decode($post->tagged_user_ids);
        

    @endphp
    @php $user_reacts = json_decode($post->user_reacts, true); @endphp
        <div class="bg-white rounded-lg shadow">
          <!-- Post header -->
          <div class="p-4">
            <div class="flex items-center justify-between mb-3">
              <div class="flex items-center space-x-3">
                <img
                  src="{{ get_user_image($video->getUser->photo,'optimized') }}"
                  class="w-10 h-10 rounded-full"
                  alt="User"
                />
                <div>
                  <p class="font-semibold">{{ $video->getUser->name }} 
                     @php
                                $follow = \App\Models\Follower::where('user_id',auth()->user()->id)->where('follow_id',$video->getUser->id)->count();
                            @endphp
                            @if ($follow>0)
                                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('user.unfollow',$video->getUser->id); ?>')">{{ get_phrase('Unfollow') }}</a> 
                            @else
                                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('user.follow',$video->getUser->id); ?>')">{{ get_phrase('Follow') }}</a> 
                            @endif
                  </p>
                  <p class="text-xs text-gray-500 flex items-center space-x-1">
                 @php
$timezone = optional(Auth::user())->timezone;

if (!$timezone || !in_array($timezone, timezone_identifiers_list())) {
    $timezone = config('app.timezone') ?: 'UTC';
}
@endphp

<span>
    {{ \Carbon\Carbon::parse($video->created_at)->timezone($timezone)->format('M d') }}
    at
    {{ \Carbon\Carbon::parse($video->created_at)->timezone($timezone)->format('h:i A') }}
</span>

                    <!-- Globe icon -->
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="w-3 h-3"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 3c4.97 0 9 4.03 9 9s-4.03 9-9 9-9-4.03-9-9 4.03-9 9-9z"
                      />
                    </svg>
                  </p>
                </div>
              </div>
              <!-- Options menu -->
              <button class="text-gray-500 hover:bg-gray-100 p-1 rounded-full">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-5 h-5"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 12h.01M12 12h.01M18 12h.01"
                  />
                </svg>
              </button>
            </div>

            <!-- Post image -->
           <video class="plyr-js w-100" onplay="pauseOtherVideos(this)" controls src="{{asset('storage/videos/'.$video->file)}}">

            <!-- Post actions -->
            <div class="flex space-x-6 text-gray-600 text-sm">
              <button class="logo-color" href="javascript:void(0)" onclick="myReact('post', 'like', 'toggle', {{$post->post_id}})" id="my_post_reacts<?php echo $post->post_id; ?>">@include('frontend.main_content.post_reacts', ['my_react' => true,'user_info'=>$user_info])

                 <ul class="react-list">
                            <li><a href="javascript:void(0)" onclick="myReact('post', 'like', 'update', {{$post->post_id}})"><img src="{{asset('storage/images/like.svg')}}" alt="Like" style="margin-right: 1px;"></a>
                            </li>
                            <li><a href="javascript:void(0)" onclick="myReact('post', 'love', 'update', {{$post->post_id}})"><img src="{{asset('storage/images/love.svg')}}" alt="Love" style="width: 30px; margin-top: 2px;"></a>
                            </li>
                            <li><a href="javascript:void(0)" onclick="myReact('post', 'haha', 'update', {{$post->post_id}})"><img src="{{asset('storage/images/haha.svg')}}" alt="Haha"></a>
                            </li>
                            <li><a href="javascript:void(0)" onclick="myReact('post', 'sad', 'update', {{$post->post_id}})"><img src="{{asset('storage/images/sad.svg')}}" class="mx-1" alt="Sad"></a>
                            </li>
                            <li><a href="javascript:void(0)" onclick="myReact('post', 'angry', 'update', {{$post->post_id}})"><img src="{{asset('storage/images/angry.svg')}}" alt="Angry"></a>
                            </li>
                        </ul>
              </button>
              <button class="logo-color entry-react" href="javascript:void(0)" onclick="$('#user-comments-{{$post->post_id}}').toggle();">💬 {{get_phrase('Comments')}}</button>
              <button class="logo-color entry-react" data-bs-toggle="modal" data-bs-target="#exampleModal"><a
                            href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.main_content.share_post_modal', 'post_id' => $post->post_id] )}}', '{{get_phrase('Share post')}}');">🔗 Share</button>
            </div>

            <div class="user-comments d-hidden bg-white" id="user-comments-{{$post->post_id}}">
                <div class="comment-form d-flex p-3 bg-secondary">
                    <img src="{{get_user_image(Auth()->user()->photo, 'optimized')}}" alt="" class="rounded-circle img-fluid" width="40px">
                    <form action="javascript:void(0)" class="w-100 ms-2" method="post">
                        <input class="form-control py-3" onkeypress="postComment(this, 0, {{$post->post_id}}, 0,'post');" rows="1" placeholder="Write Comments">
                    </form>
                </div>
                <ul class="comment-wrap p-3 pb-0 list-unstyled" id="comments{{$post->post_id}}">
                    @include('frontend.main_content.comments',['comments'=>$comments,'post_id'=>$post->post_id,'type'=>"post"])
                </ul>

                @if($comments->count() < $total_comments)
                    <a class="btn p-3 pt-0" onclick="loadMoreComments(this, {{$post->post_id}}, 0, {{$total_comments}},'post')">{{get_phrase('View more')}}</a>
                @endif
            </div>

          </div>
        </div>
         @endforeach


    @include('frontend.initialize')

       
      </div>

      <!-- Sidebar Column -->
      <div class="space-y-6">
        <!-- Feature Listings -->

        <div class="bg-white p-4 rounded-lg shadow ">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-bold">Trending Listings Videos</h2>
            <a href="#" class="text-sm hover:underline logo-color"
              >Watch more</a
            >
          </div>
          <div class="grid grid-cols-3 gap-3">

             @php $count = 0; @endphp
             @foreach($recentBusinesses as $business)
             @if($count == 3)
                @break
            @endif
            <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm">
              <div class="relative">
                <img
                  src="{{ get_page_logo($business->logo, 'logo') }}"
                  alt="Video thumbnail"
                  class="w-full h-24 object-cover"
                />
                <span class="absolute inset-0 flex items-center justify-center">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="white"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    class="w-10 h-10 text-red-600 drop-shadow"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M14.752 11.168l-5.197-3.028A1 1 0 008 9.028v5.944a1 1 0 001.555.832l5.197-3.028a1 1 0 000-1.664z"
                    />
                  </svg>
                </span>
              </div>
              <div class="p-2">
                <p class="font-semibold text-sm">{{ strip_tags($business->title ?? '') }}</p>
                <p class="text-xs text-gray-500">{{ $business->category->name ?? $business->categories->first()->name ?? 'Business' }}</p>
              </div>
            </div>
             @php $count++; @endphp
            @endforeach



          </div>
        </div>

        <!-- Feature Deals -->

        <div class="bg-white p-4 rounded-lg shadow mt-6">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-bold">Trending Deals Videos</h2>
            <a href="#" class="text-sm hover:underline logo-color"
              >Watch more</a
            >
          </div>
          <div class="grid grid-cols-3 gap-3">

              @php $count = 0; @endphp
             @foreach($recentProducts as $product)
              @if($count == 3)
                  @break
              @endif
            <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm">
              <div class="relative">
                <img
                src="{{ $product->image ? (filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('storage/marketplace/' . $product->image)) : asset('assets/frontend/images/default-product.png') }}"dpr=2"
                alt="Video thumbnail" class="w-full h-24 object-cover" />
                <span class="absolute inset-0 flex items-center justify-center">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="white"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    class="w-10 h-10 text-red-600 drop-shadow"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M14.752 11.168l-5.197-3.028A1 1 0 008 9.028v5.944a1 1 0 001.555.832l5.197-3.028a1 1 0 000-1.664z"
                    />
                  </svg>
                </span>
              </div>
              <div class="p-2">
                <p class="font-semibold text-sm">{{ strip_tags($product->title ?? '') }}</p>
                <p class="text-xs text-gray-500">{{ $product->category->product_category_name ?? 'Product' }}</p>
              </div>
            </div>
            @php $count++; @endphp
           @endforeach

          </div>
        </div>

        <!-- Feature Events -->

        <div class="bg-white p-4 rounded-lg shadow mt-6">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-bold">Trending Blogs Videos</h2>
            <a href="#" class="text-sm hover:underline logo-color"
              >Watch more</a
            >
          </div>
          <div class="grid grid-cols-3 gap-3">
            <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm">
              <div class="relative">
                <img
                  src="https://images.pexels.com/photos/2263436/pexels-photo-2263436.jpeg?auto=compress&cs=tinysrgb&w=200&h=150&dpr=2"
                  alt="Video thumbnail"
                  class="w-full h-24 object-cover"
                />
                <span class="absolute inset-0 flex items-center justify-center">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="white"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    class="w-10 h-10 text-red-600 drop-shadow"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M14.752 11.168l-5.197-3.028A1 1 0 008 9.028v5.944a1 1 0 001.555.832l5.197-3.028a1 1 0 000-1.664z"
                    />
                  </svg>
                </span>
              </div>
              <div class="p-2">
                <p class="font-semibold text-sm">Summer Travel Vlog</p>
                <p class="text-xs text-gray-500">10 mins watch</p>
              </div>
            </div>
            <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm">
              <div class="relative">
                <img
                  src="https://images.pexels.com/photos/461198/pexels-photo-461198.jpeg?auto=compress&cs=tinysrgb&w=200&h=150&dpr=2"
                  alt="Video thumbnail"
                  class="w-full h-24 object-cover"
                />
                <span class="absolute inset-0 flex items-center justify-center">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="white"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    class="w-10 h-10 text-red-600 drop-shadow"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M14.752 11.168l-5.197-3.028A1 1 0 008 9.028v5.944a1 1 0 001.555.832l5.197-3.028a1 1 0 000-1.664z"
                    />
                  </svg>
                </span>
              </div>
              <div class="p-2">
                <p class="font-semibold text-sm">Gadget Review</p>
                <p class="text-xs text-gray-500">8 mins watch</p>
              </div>
            </div>
            <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm">
              <div class="relative">
                <img
                  src="https://images.pexels.com/photos/466685/pexels-photo-466685.jpeg?auto=compress&cs=tinysrgb&w=200&h=150&dpr=2"
                  alt="Video thumbnail"
                  class="w-full h-24 object-cover"
                />
                <span class="absolute inset-0 flex items-center justify-center">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="white"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    class="w-10 h-10 text-red-600 drop-shadow"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M14.752 11.168l-5.197-3.028A1 1 0 008 9.028v5.944a1 1 0 001.555.832l5.197-3.028a1 1 0 000-1.664z"
                    />
                  </svg>
                </span>
              </div>
              <div class="p-2">
                <p class="font-semibold text-sm">Cooking Masterclass</p>
                <p class="text-xs text-gray-500">12 mins watch</p>
              </div>
            </div>
          </div>
        </div>
        <!-- Featured Blogs -->

        <div class="bg-white p-4 rounded-lg shadow mt-6">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-bold">Trending Events Videos</h2>
            <a href="#" class="text-sm hover:underline logo-color"
              >Watch more</a
            >
          </div>
          <div class="grid grid-cols-3 gap-3">
            <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm">
              <div class="relative">
                <img
                  src="https://images.pexels.com/photos/261579/pexels-photo-261579.jpeg?auto=compress&cs=tinysrgb&w=200&h=150&dpr=2"
                  alt="Video thumbnail"
                  class="w-full h-24 object-cover"
                />
                <span class="absolute inset-0 flex items-center justify-center">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="white"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    class="w-10 h-10 text-red-600 drop-shadow"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M14.752 11.168l-5.197-3.028A1 1 0 008 9.028v5.944a1 1 0 001.555.832l5.197-3.028a1 1 0 000-1.664z"
                    />
                  </svg>
                </span>
              </div>
              <div class="p-2">
                <p class="font-semibold text-sm">Summer Travel Vlog</p>
                <p class="text-xs text-gray-500">10 mins watch</p>
              </div>
            </div>
            <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm">
              <div class="relative">
                <img
                  src="https://images.pexels.com/photos/374885/pexels-photo-374885.jpeg?auto=compress&cs=tinysrgb&w=200&h=150&dpr=2"
                  alt="Video thumbnail"
                  class="w-full h-24 object-cover"
                />
                <span class="absolute inset-0 flex items-center justify-center">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="white"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    class="w-10 h-10 text-red-600 drop-shadow"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M14.752 11.168l-5.197-3.028A1 1 0 008 9.028v5.944a1 1 0 001.555.832l5.197-3.028a1 1 0 000-1.664z"
                    />
                  </svg>
                </span>
              </div>
              <div class="p-2">
                <p class="font-semibold text-sm">Gadget Review</p>
                <p class="text-xs text-gray-500">8 mins watch</p>
              </div>
            </div>
            <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm">
              <div class="relative">
                <img
                src="https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg?auto=compress&cs=tinysrgb&w=200&h=150&
                alt="Video thumbnail" class="w-full h-24 object-cover" />
                <span class="absolute inset-0 flex items-center justify-center">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="white"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    class="w-10 h-10 text-red-600 drop-shadow"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M14.752 11.168l-5.197-3.028A1 1 0 008 9.028v5.944a1 1 0 001.555.832l5.197-3.028a1 1 0 000-1.664z"
                    />
                  </svg>
                </span>
              </div>
              <div class="p-2">
                <p class="font-semibold text-sm">Cooking Masterclass</p>
                <p class="text-xs text-gray-500">12 mins watch</p>
              </div>
            </div>
          </div>
        </div>

       
        <div class="bg-white p-4 rounded-lg shadow mt-6">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-bold">Featured Videos</h2>
            <a href="#" class="text-sm hover:underline logo-color"
              >Watch more</a
            >
          </div>
          <div class="grid grid-cols-3 gap-3">

            @php
                $videoInfo = \App\Models\Page::where('item_status', 2)
                    ->whereNotNull('featured_video')
                    ->where('featured_video', '!=', '')
                    ->orderByDesc('item_featured')
                    ->orderBy('id', 'DESC')
                    ->paginate(3);
            @endphp

             @foreach ($videoInfo as $key => $page)
        @if ($page->featured_video && $page->featured_video != '') 
            <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm">
              <div class="relative">
                <video width="100%" controls>
                            <source src="{{ $page->featured_video }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                <span class="absolute inset-0 flex items-center justify-center">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="white"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    class="w-10 h-10 text-red-600 drop-shadow"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M14.752 11.168l-5.197-3.028A1 1 0 008 9.028v5.944a1 1 0 001.555.832l5.197-3.028a1 1 0 000-1.664z"
                    />
                  </svg>
                </span>
              </div>
              <!-- <div class="p-2">
                <p class="font-semibold text-sm">Summer Travel Vlog</p>
                <p class="text-xs text-gray-500">10 mins watch</p>
              </div> -->
            </div>
                  @endif
                @endforeach

          </div>
        </div>
      </div>
    </div>
