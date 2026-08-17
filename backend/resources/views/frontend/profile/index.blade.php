<div class="profile-wrap">
<style>
/* =============================================
   INSTAGRAM-QUALITY PROFILE UI — FINAL POLISH
   ============================================= */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

.profile-wrap, .profile-wrap * {
    font-family: 'Inter', sans-serif !important;
}

/* === COVER PHOTO === */
.profile-cover {
    position: relative;
    height: 220px;
    border-radius: 12px;
    overflow: hidden;
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4c1d95 100%);
}
.profile-cover .profile-header {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
.edit-cover.btn {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: rgba(0,0,0,0.5) !important;
    color: #fff !important;
    border: 1px solid rgba(255,255,255,0.3) !important;
    font-size: 12px !important;
    font-weight: 500 !important;
    padding: 6px 14px !important;
    border-radius: 8px !important;
    backdrop-filter: blur(4px);
    transition: all 0.2s;
}
.edit-cover.btn:hover { background: rgba(0,0,0,0.7) !important; }

/* === PROFILE CARD === */
.insta-profile-header {
    background: #fff;
    border: 1px solid #dbdbdb;
    border-radius: 12px;
    margin-top: 12px;
    padding: 24px !important;
    box-shadow: none;
}

/* === AVATAR === */
.profile-avatar-img {
    width: 128px;
    height: 128px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #dbdbdb;
}

/* === STATS ROW === */
.profile-stats-row {
    display: flex;
    gap: 32px;
    align-items: center;
    padding: 14px 0;
    border-top: 1px solid #f0f0f0;
    border-bottom: 1px solid #f0f0f0;
    margin: 10px 0;
}
.stat-item { text-align: center; }
.stat-item .stat-num {
    font-size: 17px;
    font-weight: 700;
    color: #0f172a;
    display: block;
}
.stat-item .stat-label {
    font-size: 13px;
    color: #8e8e8e;
}

/* === ACTION BUTTONS === */
.btn-profile-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    font-size: 13.5px !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
    transition: all 0.18s ease !important;
    text-decoration: none !important;
    cursor: pointer;
}
.btn-edit-profile {
    background: #f0f0f0 !important;
    color: #262626 !important;
    border: 1px solid transparent !important;
}
.btn-edit-profile:hover { background: #e0e0e0 !important; }

/* === PROFILE NAV TABS === */
.profile-nav {
    border: none !important;
    border-bottom: 1px solid #dbdbdb !important;
    background: transparent !important;
    margin: 16px 0 !important;
    padding: 0 !important;
}
.profile-nav .nav {
    gap: 0 !important;
    justify-content: center !important;
}
.profile-nav .nav-item { margin: 0 !important; }
.profile-nav .nav-link {
    font-size: 12px !important;
    font-weight: 500 !important;
    color: #8e8e8e !important;
    text-transform: uppercase !important;
    letter-spacing: 1px !important;
    padding: 14px 20px !important;
    border: none !important;
    border-top: 2px solid transparent !important;
    border-radius: 0 !important;
    transition: all 0.18s ease !important;
    background: transparent !important;
}
.profile-nav .nav-item.active .nav-link,
.profile-nav .nav-link:hover {
    color: #0f172a !important;
    font-weight: 700 !important;
    border-top-color: #0f172a !important;
    background: transparent !important;
}

/* === VIEW TOGGLE === */
.view-toggle-wrap {
    display: flex;
    justify-content: center;
    gap: 2px;
    margin-bottom: 16px;
    border-bottom: 1px solid #dbdbdb;
    padding-bottom: 0;
}
.btn-view-mode {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 12px 20px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    letter-spacing: 0.8px !important;
    text-transform: uppercase !important;
    color: #8e8e8e !important;
    border: none !important;
    background: transparent !important;
    border-bottom: 2px solid transparent !important;
    border-radius: 0 !important;
    transition: all 0.18s ease !important;
}
.btn-view-mode.active {
    color: #0f172a !important;
    border-bottom-color: #0f172a !important;
}
.btn-view-mode:hover:not(.active) { color: #475569 !important; }

/* === GRID VIEW === */
#postsGridView {
    max-width: 935px;
    margin: 0 auto !important;
}
.insta-grid-item {
    position: relative;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    border-radius: 4px;
    cursor: pointer;
    background: #f1f5f9;
}
.grid-media {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s ease;
}
.insta-grid-item:hover .grid-media { transform: scale(1.05); }
.video-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(0,0,0,0.65);
    color: #fff;
    padding: 3px 7px;
    border-radius: 4px;
    font-size: 11px;
}
.grid-text-fallback {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px;
}
.grid-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    opacity: 0;
    transition: opacity 0.2s ease;
    color: #fff;
    font-weight: 700;
    font-size: 16px;
    z-index: 2;
}
.insta-grid-item:hover .grid-overlay { opacity: 1; }
.overlay-stat {
    display: flex;
    align-items: center;
    gap: 5px;
}

/* === FEED VIEW === */
#postsFeedView {
    max-width: 614px;
    margin: 0 auto !important;
}

/* === BIO AREA === */
.profile-bio {
    font-size: 14px;
    line-height: 1.6;
    color: #262626;
    white-space: pre-line;
}

/* === HIDE LEGACY WIDGETS === */
.profile-wrap .intro-widget { display: none !important; }

/* === MOBILE === */
@media (max-width: 767px) {
    .profile-cover { height: 140px; border-radius: 0; }
    .insta-profile-header { border-radius: 0; margin-top: 0; padding: 16px !important; border-left: none; border-right: none; }
    .profile-avatar-img { width: 90px; height: 90px; }
    .profile-stats-row { gap: 20px; justify-content: center; }
    .profile-nav .nav-link { padding: 12px 12px !important; font-size: 11px !important; letter-spacing: 0.5px !important; }
    #postsFeedView { max-width: 100%; }
    .btn-view-mode { padding: 10px 14px !important; font-size: 11px !important; }
}
@media (max-width: 480px) {
    .profile-stats-row { gap: 14px; }
    .stat-item .stat-num { font-size: 15px; }
    .profile-avatar-img { width: 76px; height: 76px; }
}
</style>

    {{-- COVER PHOTO --}}
    <div class="profile-cover">
        <div class="profile-header" style="background-image: url('{{ get_cover_photo($user_info->cover_photo) }}');"></div>
        <button onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.profile.edit_cover_photo']) }}', '{{ get_phrase('Update your cover photo') }}');" class="edit-cover btn">
            <i class="fa fa-camera me-1"></i>{{ get_phrase('Edit Cover Photo') }}
        </button>
    </div>

    {{-- PROFILE INFO CARD --}}
    <div class="insta-profile-header">
        <div class="row align-items-center">
            {{-- Avatar --}}
            <div class="col-auto">
                <img class="profile-avatar-img"
                     src="{{ get_user_image($user_info->photo, 'optimized') }}"
                     alt="{{ $user_info->name }}"
                     loading="lazy">
            </div>

            {{-- Info --}}
            <div class="col">
                {{-- Name + Actions Row --}}
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <h2 class="m-0 fw-bold" style="font-size: 22px; color: #262626; letter-spacing: -0.3px;">{{ $user_info->name }}</h2>
                    <a href="{{ route('profile.load_my_profile', ['id' => auth()->user()->id]) }}" class="btn btn-profile-action btn-edit-profile">
                        <i class="fa-solid fa-pen-to-square"></i>{{ get_phrase('Edit Profile') }}
                    </a>
                </div>

                {{-- Nickname --}}
                @if($user_info->nickname)
                    <div class="text-muted mb-1" style="font-size: 13.5px;">{{ $user_info->nickname }}</div>
                @endif

                {{-- Stats --}}
                <div class="profile-stats-row">
                    <div class="stat-item">
                        <span class="stat-num">{{ $posts_count ?? 0 }}</span>
                        <span class="stat-label">{{ get_phrase('posts') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-num">{{ $followers_count ?? 0 }}</span>
                        <span class="stat-label">{{ get_phrase('followers') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-num">{{ $following_count ?? 0 }}</span>
                        <span class="stat-label">{{ get_phrase('following') }}</span>
                    </div>
                </div>

                {{-- Bio --}}
                @if($user_info->about)
                    <p class="profile-bio mb-0">{!! script_checker($user_info->about) !!}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- PROFILE TABS --}}
    <div class="profile-content mt-2">
        <nav class="profile-nav">
            <ul class="nav">
                <li class="nav-item @if(Route::currentRouteName() == 'profile') active @endif">
                    <a href="{{ route('profile') }}" class="nav-link"><i class="fa-solid fa-border-all me-1"></i>{{ get_phrase('Posts') }}</a>
                </li>
                <li class="nav-item @if(Route::currentRouteName() == 'profile.products') active @endif">
                    <a href="{{ route('profile.products') }}" class="nav-link"><i class="fa-solid fa-bag-shopping me-1"></i>{{ get_phrase('Deals') }}</a>
                </li>
                <li class="nav-item @if(Route::currentRouteName() == 'profile.events') active @endif">
                    <a href="{{ route('profile.events') }}" class="nav-link"><i class="fa-solid fa-calendar me-1"></i>{{ get_phrase('Event') }}</a>
                </li>
                <li class="nav-item @if(Route::currentRouteName() == 'profile.blogs') active @endif">
                    <a href="{{ route('profile.blogs') }}" class="nav-link"><i class="fa-solid fa-pen me-1"></i>{{ get_phrase('Blog') }}</a>
                </li>
                <li class="nav-item @if(Route::currentRouteName() == 'profile.about.page') active @endif">
                    <a href="{{ route('profile.about.page') }}" class="nav-link"><i class="fa-solid fa-circle-info me-1"></i>{{ get_phrase('About') }}</a>
                </li>
            </ul>
        </nav>

        {{-- CONTENT AREA --}}
        <div class="row gx-3">
            <div class="col-12" id="profileLeftColumn">
                @if(Route::currentRouteName() == 'profile.events')
                    @include('frontend.profile.events')
                @elseif(Route::currentRouteName() == 'profile.blogs')
                    @include('frontend.profile.blogs')
                @elseif(Route::currentRouteName() == 'profile.products')
                    @include('frontend.profile.products')
                @elseif(Route::currentRouteName() == 'profile.about.page')
                    @include('frontend.profile.profile_info', ['type' => 'my_account'])
                @else
                    {{-- GRID / FEED TOGGLE --}}
                    <div class="view-toggle-wrap" id="viewToggleBar">
                        <button class="btn-view-mode active" id="btnGridView" title="Grid View">
                            <i class="fa-solid fa-border-all"></i> POSTS
                        </button>
                        <button class="btn-view-mode" id="btnFeedView" title="Feed View">
                            <i class="fa-solid fa-list"></i> FEED
                        </button>
                    </div>

                    {{-- INSTAGRAM GRID --}}
                    <div id="postsGridView" class="row row-cols-3 g-1">
                        @forelse($posts as $post)
                            <div class="col">
                                <div class="insta-grid-item" onclick="window.location.href='{{ route('single.post', $post->post_id) }}'">
                                    @php
                                        $media = DB::table('media_files')->where('post_id', $post->post_id)->first();
                                        $likes = json_decode($post->user_reacts, true);
                                        $likes_count = is_array($likes) ? count($likes) : 0;
                                        $comments_count = DB::table('comments')->where('is_type', 'post')->where('id_of_type', $post->post_id)->count();
                                    @endphp

                                    @if($media && $media->file_type == 'image')
                                        <img src="{{ get_post_image($media->file_name, 'optimized') }}" alt="" class="grid-media" loading="lazy">
                                    @elseif($media && $media->file_type == 'video')
                                        <div class="video-thumbnail-container" style="width:100%;height:100%;position:relative;">
                                            <video src="{{ asset('storage/videos/'.$media->file_name) }}" class="grid-media" muted preload="none"></video>
                                            <span class="video-badge"><i class="fa-solid fa-video"></i></span>
                                        </div>
                                    @else
                                        <div class="grid-text-fallback">
                                            <p class="m-0 text-white fw-medium text-center" style="font-size: 12.5px; line-height: 1.5; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical;">{{ strip_tags($post->description) }}</p>
                                        </div>
                                    @endif

                                    <div class="grid-overlay">
                                        <span class="overlay-stat"><i class="fa-solid fa-heart"></i> {{ $likes_count }}</span>
                                        <span class="overlay-stat"><i class="fa-solid fa-comment"></i> {{ $comments_count }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <i class="fa-solid fa-camera-retro fa-3x text-muted mb-3 d-block"></i>
                                <h6 class="text-muted fw-normal">{{ get_phrase('No Posts Yet') }}</h6>
                                <p class="text-muted" style="font-size:13px;">{{ get_phrase('Share your first moment!') }}</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- FEED VIEW (hidden by default) --}}
                    <div id="postsFeedView" class="d-none">
                        @include('frontend.main_content.create_post')
                        <div id="profile-timeline-posts">
                            @include('frontend.main_content.posts', ['type' => 'user_post'])
                        </div>
                        @include('frontend.main_content.scripts')
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

@include('frontend.profile.scripts')

<script>
$(document).ready(function() {
    var savedView = localStorage.getItem('profileView') || 'grid';
    if (savedView === 'feed') {
        $('#postsGridView').addClass('d-none');
        $('#postsFeedView').removeClass('d-none');
        $('#btnFeedView').addClass('active');
        $('#btnGridView').removeClass('active');
    }

    $('#btnGridView').click(function() {
        $('.btn-view-mode').removeClass('active');
        $(this).addClass('active');
        $('#postsGridView').removeClass('d-none');
        $('#postsFeedView').addClass('d-none');
        localStorage.setItem('profileView', 'grid');
    });

    $('#btnFeedView').click(function() {
        $('.btn-view-mode').removeClass('active');
        $(this).addClass('active');
        $('#postsGridView').addClass('d-none');
        $('#postsFeedView').removeClass('d-none');
        localStorage.setItem('profileView', 'feed');
    });
});
</script>
