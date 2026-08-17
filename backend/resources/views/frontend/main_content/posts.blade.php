@if(!isset($posts) || count($posts) == 0)
    <div class="no-posts-placeholder">
        <i class="fa-regular fa-camera fa-2x mb-3 text-muted"></i>
        <p class="text-muted">{{ get_phrase('No posts to show yet.') }}</p>
    </div>
@endif

<style>
/* =============================================
   INSTAGRAM-QUALITY FEED UI — FINAL POLISH
   ============================================= */

/* Prevent dropdown clipping */
.single-entry, .entry-inner {
    overflow: visible !important;
}
.post-controls.dropdown .dropdown-menu {
    z-index: 1060 !important;
}

.no-posts-placeholder {
    text-align: center;
    padding: 48px 24px;
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    margin-top: 8px;
}

/* === POST CARD === */
.single-entry {
    background: #fff !important;
    border: 1px solid #dbdbdb !important;
    border-radius: 12px !important;
    box-shadow: none !important;
    margin-bottom: 16px !important;
    padding: 0 !important;
    overflow: visible !important;
    transition: box-shadow 0.2s ease;
}
.single-entry:hover {
    box-shadow: 0 2px 12px rgba(0,0,0,0.08) !important;
}
.entry-inner { padding: 0 !important; }

/* === POST HEADER === */
.entry-header {
    padding: 12px 16px !important;
    border-bottom: none !important;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.entry-header .ava-info {
    display: flex;
    align-items: center;
    gap: 10px;
}
.entry-header .user_image_show_on_modal {
    width: 40px !important;
    height: 40px !important;
    border: 2px solid #f0f0f0;
    object-fit: cover;
    border-radius: 50%;
}
.entry-header .ava-desc h3 {
    font-size: 14px !important;
    font-weight: 600 !important;
    color: #0f172a !important;
    margin: 0 !important;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 4px;
    line-height: 1.4 !important;
}
.entry-header .ava-desc h3 a {
    color: #0f172a !important;
    font-weight: 600 !important;
    text-decoration: none !important;
}
.entry-header .ava-desc h3 a:hover { color: #6366f1 !important; }
.entry-header .meta-time {
    font-size: 11.5px !important;
    color: #8e8e8e !important;
    margin-top: 1px;
    display: block;
}
.entry-header .meta-privacy {
    font-size: 10px !important;
    color: #8e8e8e !important;
    margin-left: 3px;
}

/* === POST BODY === */
.entry-content {
    padding: 4px 16px 12px !important;
    font-size: 14px !important;
    line-height: 1.65 !important;
    color: #262626 !important;
}
.entry-content p { margin-bottom: 6px !important; }

/* === POST MEDIA — full-width, no padding === */
.entry-content .post-media-wrap {
    margin: 0 -16px;
}
.entry-content .post-media-wrap img,
.entry-content .post-media-wrap video {
    width: 100%;
    display: block;
    max-height: 600px;
    object-fit: cover;
}

/* === SHARED POST EMBED === */
.shared-post-embed {
    background: #fafafa !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 10px !important;
    padding: 14px !important;
    margin-top: 10px !important;
}
.shared-post-embed .fw-semibold {
    font-size: 13.5px !important;
    font-weight: 600 !important;
    color: #262626 !important;
}

/* === REACTIONS & STATS BAR === */
.entry-meta {
    padding: 8px 16px !important;
    border-top: 1px solid #efefef !important;
    border-bottom: 1px solid #efefef !important;
    font-size: 13px !important;
    color: #8e8e8e !important;
    background: transparent !important;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.entry-meta a {
    color: #262626 !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    font-size: 13px !important;
}
.entry-meta .post-comment ul {
    display: flex;
    gap: 12px;
    list-style: none;
    margin: 0;
    padding: 0;
}
.entry-meta .post-comment ul li a {
    color: #8e8e8e !important;
    font-weight: 400 !important;
    font-size: 13px !important;
}

/* === ACTION BAR === */
.entry-footer {
    padding: 4px 8px !important;
    border-top: none !important;
    background: transparent !important;
}
.post-actions {
    display: flex;
    align-items: center;
}
.post-action {
    flex: 1;
    text-align: center;
}
.post-action a {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 0;
    font-size: 13.5px !important;
    font-weight: 600 !important;
    color: #262626 !important;
    border-radius: 8px !important;
    transition: background 0.15s ease !important;
    text-decoration: none !important;
}
.post-action a:hover {
    background: #f5f5f5 !important;
    color: #0f172a !important;
}
.post-action i { font-size: 18px !important; }

/* === REACTIONS POPUP === */
.post-react { position: relative; }
.post-react .react-list {
    position: absolute;
    bottom: 50px;
    left: 50%;
    transform: translateX(-50%) scale(0.88);
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 30px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    display: flex;
    padding: 6px 10px;
    gap: 6px;
    list-style: none;
    margin: 0;
    opacity: 0;
    visibility: hidden;
    transition: all 0.22s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    z-index: 1000;
    white-space: nowrap;
}
.post-react:hover .react-list {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) scale(1);
}
.react-list li { transition: transform 0.18s ease; }
.react-list li:hover { transform: scale(1.3); }
.react-list img { width: 26px; height: 26px; cursor: pointer; }

/* === COMMENTS SECTION === */
.user-comments {
    border-top: 1px solid #efefef;
}
.comment-form {
    background: #fafafa !important;
    border-bottom: 1px solid #efefef !important;
    padding: 12px 16px !important;
}
.comment-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid #dbdbdb;
    border-radius: 22px;
    padding-right: 10px;
    transition: border-color 0.2s;
}
.comment-input-wrap:focus-within { border-color: #6366f1; }
.comment-input {
    border: none !important;
    box-shadow: none !important;
    padding: 8px 14px !important;
    background: transparent !important;
    font-size: 13.5px !important;
    border-radius: 22px !important;
}
.comment-input-wrap .send-btn {
    background: none !important;
    border: none !important;
    color: #6366f1 !important;
    cursor: pointer;
    padding: 4px 6px;
    font-size: 15px;
    transition: transform 0.15s;
}
.comment-input-wrap .send-btn:hover { transform: scale(1.15); }
.comment-wrap { background: #fff !important; }

/* === MOBILE RESPONSIVE === */
@media (max-width: 576px) {
    .single-entry { border-radius: 0 !important; border-left: none !important; border-right: none !important; margin-bottom: 8px !important; }
    .entry-header { padding: 10px 12px !important; }
    .entry-content { padding: 4px 12px 10px !important; font-size: 13.5px !important; }
    .entry-meta { padding: 8px 12px !important; }
    .entry-footer { padding: 2px 6px !important; }
    .comment-form { padding: 10px 12px !important; }
    .post-action a { font-size: 12.5px !important; padding: 9px 0 !important; }
    .post-action i { font-size: 16px !important; }
}
</style>

@foreach($posts as $loopIndex => $post)
    @php
        $total_comments = isset($comment_counts) ? ($comment_counts[$post->post_id] ?? 0) : null;
        if (is_null($total_comments)) {
            $total_comments = DB::table('comments')
                ->where('comments.is_type', 'post')
                ->where('comments.id_of_type', $post->post_id)
                ->count();
        }

        $comments = isset($latest_comments) ? ($latest_comments[$post->post_id] ?? collect()) : null;
        if (is_null($comments)) {
            $comments = DB::table('comments')
                ->join('users', 'comments.user_id', '=', 'users.id')
                ->where('comments.is_type', 'post')
                ->where('comments.id_of_type', $post->post_id)
                ->where('comments.parent_id', 0)
                ->select('comments.*', 'users.name', 'users.photo')
                ->orderBy('comment_id', 'DESC')->take(1)->get();
        }

        $tagged_user_ids = json_decode($post->tagged_user_ids);
    @endphp
    @php $user_reacts = json_decode($post->user_reacts, true); @endphp

    <div class="single-item-countable single-entry" id="postIdentification{{ $post->post_id }}">
        <div class="entry-inner">

            {{-- POST HEADER --}}
            <div class="entry-header">
                <div class="ava-info">
                    @if (isset($type) && $type == "page")
                        <img src="{{ get_page_logo($post->logo, 'logo') }}" class="rounded-circle user_image_show_on_modal" loading="lazy" alt="">
                    @elseif (isset($type) && $type == "video")
                        <img src="{{ get_user_image($post->photo, 'optimized') }}" class="rounded-circle user_image_show_on_modal" loading="lazy" alt="">
                    @elseif (isset($type) && $type == "user_post")
                        <img src="{{ get_user_image(isset($post->name) ? ($post->photo ?? 'default.png') : $post->user_id, 'optimized') }}" class="rounded-circle user_image_show_on_modal" loading="lazy" alt="">
                    @else
                        <img src="{{ get_user_image(isset($post->name) ? ($post->photo ?? 'default.png') : $post->user_id, 'optimized') }}" class="rounded-circle user_image_show_on_modal" loading="lazy" alt="">
                    @endif

                    <div class="ava-desc">
                        <h3 class="mb-0">
                            @if (isset($type) && $type == "page")
                                <a href="{{ route('single.page', ['city_slug' => $post->city_slug, 'area_slug' => $post->area_slug, 'category_slug' => $post->category_slug, 'item_slug' => $post->item_slug]) }}">{{ $post->title }}</a>
                            @elseif (isset($type) && $type == "group")
                                <a href="{{ route('user.profile.view', $post->user_id) }}">{{ $post->name }}</a>
                            @else
                                @php
                                    $isPage = $post->publisher === 'page';
                                    $isGroup = $post->publisher === 'group';
                                    $isEvent = $post->publisher === 'event';
                                    $isUser = !$isPage && !$isGroup && !$isEvent;
                                    $displayName = $isPage ? ($post->page->title ?? 'Page')
                                        : ($isGroup ? ($post->group->title ?? 'Group')
                                        : ($isEvent ? ($post->event->title ?? 'Event')
                                        : ($post->getUser->name ?? 'User')));
                                    if ($isPage) {
                                        $lastCategory = optional($post->page->categories)->last();
                                        $profileLink = route('single.page', ['city_slug' => $post->page->city->city_slug ?? 'city', 'area_slug' => $post->page->area->area_slug ?? 'area', 'category_slug' => $lastCategory->category_slug ?? 'category', 'item_slug' => $post->page->item_slug ?? 'item']);
                                    } elseif ($isGroup) {
                                        $profileLink = route('single.group', ['id' => $post->group->id ?? 0, 'category_slug' => optional($post->group->category)->category_slug ?? 'category', 'group_slug' => $post->group->group_slug ?? 'group', 'city_slug' => $post->group->city->city_slug ?? 'city', 'area_slug' => $post->group->area->area_slug ?? 'area']);
                                    } elseif ($isEvent) {
                                        $profileLink = route('single.event', ['id' => $post->event->id, 'city_slug' => $post->event->city->city_slug ?? 'city', 'area_slug' => $post->event->area->area_slug ?? 'area', 'category_slug' => optional($post->event->category)->category_slug ?? 'category', 'event_slug' => $post->event->event_slug ?? 'event']);
                                    } else {
                                        $profileLink = route('user.profile.view', $post->user_id);
                                    }
                                @endphp
                                <a href="{{ $profileLink }}">{{ $displayName }}</a>
                                @if(auth()->user() && $post->user_id != auth()->user()->id)
                                    @php
                                        $follow = 0;
                                        if (auth()->user()) {
                                            $followedList = $followed_user_ids ?? null;
                                            if (is_null($followedList)) {
                                                $followedList = \App\Models\Follower::where('user_id', auth()->user()->id)->pluck('follow_id')->toArray();
                                            }
                                            $follow = in_array($post->user_id, $followedList) ? 1 : 0;
                                        }
                                    @endphp
                                    @if($follow > 0)
                                        <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('user.unfollow', $post->user_id); ?>')" style="font-size:12px;color:#6366f1;font-weight:400;">· {{ get_phrase('Unfollow') }}</a>
                                    @else
                                        <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('user.follow', $post->user_id); ?>')" style="font-size:12px;color:#6366f1;font-weight:400;">· {{ get_phrase('Follow') }}</a>
                                    @endif
                                @endif
                            @endif

                            @if($post->post_type == 'cover_photo') <small class="text-muted fw-normal">{{ get_phrase('changed cover photo') }}</small> @endif
                            @if($post->post_type == 'share') <small class="text-muted fw-normal">{{ get_phrase('shared a post') }}</small> @endif
                            @if(!empty($post->location)) <small class="text-muted fw-normal">{{ get_phrase('in') }}</small> <a href="https://www.google.com/maps/place/{{ $post->location }}" target="_blank" style="font-size:13px;">{{ $post->location }}</a> @endif
                        </h3>
                        <span class="meta-time">
                            {{ date_formatter($post->created_at, 2) }}
                            @if($post->privacy == 'public')
                                <span class="meta-privacy"><i class="fa-solid fa-earth-americas"></i></span>
                            @elseif($post->privacy == 'private')
                                <span class="meta-privacy"><i class="fa-solid fa-lock"></i></span>
                            @else
                                <span class="meta-privacy"><i class="fa-solid fa-users"></i></span>
                            @endif
                        </span>
                    </div>
                </div>

                {{-- 3-dot menu --}}
                <div class="post-controls dropdown dotted">
                    <a class="dropdown-toggle" href="#" id="navbarDropdown{{ $post->post_id }}" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown{{ $post->post_id }}">
                        <input type="hidden" id="copy_post_{{ $post->post_id }}" value="{{ route('single.post', $post->post_id) }}">
                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="copyToClipboard('copy_post_{{ $post->post_id }}')"><img src="{{ asset('storage/images/link.png') }}" alt="" width="16" class="me-2">{{ get_phrase('Copy Link') }}</a></li>
                        @if(auth()->user())
                            @if($post->user_id == auth()->user()->id)
                                @if($post->post_type != 'live_streaming' && $post->location == '')
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="showCustomModal('<?php echo route('edit_post_form', $post->post_id); ?>', '{{ get_phrase('Edit post') }}', 'lg')"><img src="{{ asset('storage/images/edit.png') }}" alt="" width="16" class="me-2">{{ get_phrase('Edit') }}</a></li>
                                @endif
                                <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="confirmAction('<?php echo route('post.delete', ['post_id' => $post->post_id]); ?>', true)"><img src="{{ asset('storage/images/trash.png') }}" alt="" width="16" class="me-2">{{ get_phrase('Delete') }}</a></li>
                            @endif
                        @endif
                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.main_content.create_report', 'post_id' => $post->post_id]) }}', '{{ get_phrase('Report Post') }}');" data-bs-toggle="modal" data-bs-target="#createEvent"><img src="{{ asset('storage/images/report.png') }}" alt="" width="16" class="me-2">{{ get_phrase('Report') }}</a></li>
                    </ul>
                </div>
            </div>
            {{-- END HEADER --}}

            {{-- POST BODY --}}
            <div class="entry-content">
                @if($post->post_type == 'general' || $post->post_type == 'profile_picture' || $post->post_type == 'cover_photo')
                    @php echo script_checker($post->description); @endphp
                    @include('frontend.main_content.media_type_post_view')
                    @if(!empty($post->location))
                        @include('frontend.main_content.location_type_post_view')
                    @endif
                @elseif($post->post_type == 'share')
                    @php
                        $sharedPostId = null;
                        if (!empty($post->description)) {
                            $segments = explode('/', rtrim($post->description, '/'));
                            $sharedPostId = end($segments);
                            $sharedPostId = is_numeric($sharedPostId) ? (int)$sharedPostId : null;
                        }
                        $sharedPost = $sharedPostId ? \Cache::remember('shared_post_data_v1_' . $sharedPostId, 86400, function() use ($sharedPostId) {
                            return DB::table('posts')->leftJoin('users', 'posts.user_id', '=', 'users.id')->where('posts.post_id', $sharedPostId)->select('posts.*', 'users.name as user_name', 'users.photo as user_photo')->first();
                        }) : null;
                    @endphp
                    <div class="shared-post-embed">
                        @if($sharedPost)
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <img src="{{ get_user_image($sharedPost->user_photo ?? null, 'optimized') }}" class="rounded-circle" style="width:34px;height:34px;object-fit:cover;" loading="lazy" onerror="this.src='/storage/pages/logo/default.png'" alt="">
                                <div>
                                    <div class="fw-semibold">{{ $sharedPost->user_name ?? get_phrase('User') }}</div>
                                    <div style="font-size:11px;color:#8e8e8e;">{{ date_formatter($sharedPost->created_at, 2) }}</div>
                                </div>
                            </div>
                            @if(!empty($sharedPost->description) && $sharedPost->post_type !== 'share')
                                <p style="font-size:13.5px;color:#262626;margin:0 0 8px;">{{ \Illuminate\Support\Str::limit($sharedPost->description, 300) }}</p>
                            @endif
                            @if($sharedPost->post_type === 'image' || $sharedPost->post_type === 'photo')
                                @php $sharedImages = DB::table('post_images')->where('post_id', $sharedPost->post_id)->get(); @endphp
                                @foreach($sharedImages->take(1) as $img)
                                    <img src="{{ asset('storage/post/images/'.$img->image) }}" class="w-100 rounded-2" style="max-height:280px;object-fit:cover;" loading="lazy" onerror="this.src='/storage/pages/logo/default.png'" alt="">
                                @endforeach
                            @endif
                        @else
                            <p class="text-muted mb-0" style="font-size:13px;"><i class="fa fa-lock me-1"></i>{{ get_phrase('This post is no longer available.') }}</p>
                        @endif
                    </div>
                @elseif($post->post_type == 'live_streaming')
                    @include('frontend.main_content.live_streaming_type_post_view')
                @endif
            </div>
            {{-- END POST BODY --}}

            {{-- STATS ROW --}}
            <div class="entry-meta">
                <a href="javascript:void(0)" id="post_reacts<?php echo $post->post_id; ?>">
                    @include('frontend.main_content.post_reacts', ['post_react' => true])
                </a>
                <div class="post-comment">
                    <ul>
                        <li><a onclick="$('#user-comments-{{ $post->post_id }}').toggle();" href="javascript:void(0)"><span id="post_comment_count{{ $post->post_id }}">{{ $total_comments }}</span> {{ get_phrase('Comments') }}</a></li>
                        @php
                            $sharecount = isset($post_share_counts) ? ($post_share_counts[$post->post_id] ?? 0) : null;
                            if (is_null($sharecount)) {
                                $sharecount = \App\Models\Post_share::where('post_id', $post->post_id)->count();
                            }
                        @endphp
                        <li><a href="javascript:void(0)"><span>{{ $sharecount }}</span> {{ get_phrase('Shares') }}</a></li>
                    </ul>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="entry-footer">
                <div class="post-actions">
                    {{-- LIKE with reaction popup --}}
                    <div class="post-action post-react">
                        <a href="javascript:void(0)" onclick="myReact('post','like','toggle',{{ $post->post_id }})" id="my_post_reacts{{ $post->post_id }}">
                            @include('frontend.main_content.post_reacts', ['my_react' => true])
                        </a>
                        <ul class="react-list">
                            <li><a onclick="myReact('post','like','update',{{ $post->post_id }})"><img src="{{ asset('storage/images/like.svg') }}" loading="lazy"></a></li>
                            <li><a onclick="myReact('post','love','update',{{ $post->post_id }})"><img src="{{ asset('storage/images/love.svg') }}" loading="lazy"></a></li>
                            <li><a onclick="myReact('post','haha','update',{{ $post->post_id }})"><img src="{{ asset('storage/images/haha.svg') }}" loading="lazy"></a></li>
                            <li><a onclick="myReact('post','sad','update',{{ $post->post_id }})"><img src="{{ asset('storage/images/sad.svg') }}" loading="lazy"></a></li>
                            <li><a onclick="myReact('post','angry','update',{{ $post->post_id }})"><img src="{{ asset('storage/images/angry.svg') }}" loading="lazy"></a></li>
                        </ul>
                    </div>

                    {{-- COMMENT --}}
                    <div class="post-action">
                        <a href="javascript:void(0)" onclick="$('#user-comments-{{ $post->post_id }}').toggle();">
                            <i class="fa-regular fa-comment"></i>
                            <span>{{ get_phrase('Comment') }}</span>
                        </a>
                    </div>

                    {{-- SHARE --}}
                    <div class="post-action">
                        <a href="javascript:void(0)" onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.main_content.share_post_modal', 'post_id' => $post->post_id]) }}', 'Share post')">
                            <i class="fa-solid fa-share-nodes"></i>
                            <span>{{ get_phrase('Share') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- COMMENTS SECTION --}}
            <div class="user-comments d-hidden bg-white" id="user-comments-{{ $post->post_id }}">
                <div class="comment-form d-flex align-items-center gap-2">
                    @if(Auth()->check())
                        <img src="{{ get_user_image(Auth()->user()->photo, 'optimized') }}" alt="" class="rounded-circle flex-shrink-0" width="36" height="36" loading="lazy" onerror="this.src='/storage/pages/logo/default.png'" style="object-fit:cover;">
                        <div class="comment-input-wrap flex-grow-1">
                            <input class="form-control comment-input" onkeypress="postComment(this, 0, {{ $post->post_id }}, 0, 'post');" placeholder="{{ get_phrase('Add a comment…') }}">
                            <button class="send-btn" type="button" onclick="submitComment(this, 0, {{ $post->post_id }}, 0, 'post')" title="Send">
                                <i class="fa fa-paper-plane"></i>
                            </button>
                        </div>
                    @else
                        <div class="comment-input-wrap flex-grow-1">
                            <input class="form-control comment-input" readonly style="cursor:pointer;" onclick="window.location.href='{{ route('login') }}'" placeholder="{{ get_phrase('Login to comment…') }}">
                        </div>
                    @endif
                </div>
                <ul class="comment-wrap p-3 pb-0 list-unstyled" id="comments{{ $post->post_id }}">
                    @include('frontend.main_content.comments', ['comments' => $comments, 'post_id' => $post->post_id, 'type' => 'post'])
                </ul>
                @if($comments->count() < $total_comments)
                    <a class="btn px-3 pt-0 pb-2" style="font-size:13px;color:#6366f1;" onclick="loadMoreComments(this, {{ $post->post_id }}, 0, {{ $total_comments }}, 'post')">{{ get_phrase('View more comments') }}</a>
                @endif
            </div>

        </div>
    </div>

    @if(isset($search) && !empty($search))
        @if($loopIndex == 2) @break @endif
    @endif
@endforeach

@include('frontend.initialize')
