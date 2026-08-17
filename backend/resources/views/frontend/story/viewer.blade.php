@php
    use Illuminate\Support\Str;

    $viewerStories = $user_stories->map(function ($story) {
        return [
            'story_id' => (int) $story->story_id,
            'user_id' => (int) $story->user_id,
            'name' => $story->name,
            'photo' => $story->photo,
            'content_type' => $story->content_type,
            'description' => $story->description,
            'privacy' => $story->privacy,
            'created_at' => (string) $story->created_at,
            'updated_at' => (string) ($story->updated_at ?? $story->created_at),
            'expires_at' => (int) ($story->expires_at ?? 0),
            'media_items' => collect($story->media_items ?? [])->map(function ($media) {
                return [
                    'id' => $media->id ?? null,
                    'file_name' => $media->file_name ?? null,
                    'file_type' => $media->file_type ?? null,
                ];
            })->values()->all(),
        ];
    })->values()->all();

    $viewerData = [
        'initial_story_id' => (int) $initial_story_id,
        'is_admin' => (bool) $is_admin,
        'story_owner' => [
            'name' => $story_owner->name ?? '',
            'photo' => $story_owner->photo ?? '',
            'story_id' => (int) ($story_owner->story_id ?? $initial_story_id),
        ],
        'stories' => $viewerStories,
        'track_url_template' => url('/stories/__story_id__/view'),
        'viewers_url_template' => url('/stories/__story_id__/viewers'),
    ];
@endphp

<script type="application/json" id="story-viewer-data">{!! json_encode($viewerData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

<div id="story-viewer-shell"
     class="story-viewer-shell">
    <div class="story-viewer-frame">
        <div class="story-viewer-progress" id="story-progress-bars"></div>

        <div class="story-viewer-header">
            <div class="d-flex align-items-center gap-2">
                <img id="story-owner-avatar"
                     src="{{ get_user_image($story_owner->photo ?? '') }}"
                     alt="{{ $story_owner->name ?? '' }}"
                     class="story-viewer-avatar">
                <div>
                    <h5 id="story-owner-name" class="story-viewer-name mb-0">{{ $story_owner->name ?? '' }}</h5>
                    <div id="story-owner-time" class="story-viewer-time">{{ date_formatter($story_owner->created_at ?? now()->timestamp, 2) }}</div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @if($is_admin)
                    <button type="button" class="story-viewer-viewers-btn" id="story-viewers-toggle">
                        <i class="fa fa-users me-1"></i>{{ get_phrase('Viewers') }}
                    </button>
                @endif
                <button type="button" class="story-viewer-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>

        <div class="story-viewer-stage-wrap">
            <button type="button" class="story-nav story-nav-prev" id="story-prev-btn" aria-label="Previous story">
                <i class="fa fa-chevron-left"></i>
            </button>

            <div class="story-viewer-stage" id="story-stage">
                <div class="story-viewer-loading">
                    <div class="spinner-border text-light" role="status"></div>
                </div>
            </div>

            <button type="button" class="story-nav story-nav-next" id="story-next-btn" aria-label="Next story">
                <i class="fa fa-chevron-right"></i>
            </button>
        </div>

        <div class="story-viewer-footer">
            <div class="story-viewer-footer__meta">
                <span id="story-slide-counter">1 / {{ max(1, count($viewerStories)) }}</span>
                <span id="story-view-count" class="ms-2"></span>
            </div>

            @if($is_admin)
                <button type="button" class="story-viewer-viewers-link" id="story-viewers-link">
                    {{ get_phrase('Show viewer list') }}
                </button>
            @endif
        </div>

        @if($is_admin)
            <div class="story-viewers-panel d-none" id="story-viewers-panel"></div>
        @endif
    </div>
</div>

<style>
    .story-viewer-shell {
        width: 100%;
    }

    .story-viewer-frame {
        position: relative;
        width: 100%;
        min-height: min(84vh, 780px);
        background: #020617;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 24px 80px rgba(0, 0, 0, 0.4);
        color: #fff;
    }

    .story-viewer-progress {
        display: flex;
        gap: 4px;
        padding: 10px 10px 0;
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        z-index: 5;
    }

    .story-progress-segment {
        flex: 1;
        height: 3px;
        background: rgba(255, 255, 255, 0.22);
        border-radius: 999px;
        overflow: hidden;
    }

    .story-progress-segment__fill {
        width: 0;
        height: 100%;
        background: #fff;
        border-radius: inherit;
        transition: width linear;
    }

    .story-viewer-header {
        position: absolute;
        top: 10px;
        left: 0;
        right: 0;
        z-index: 6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.85rem 1rem 0;
        margin-top: 10px;
        pointer-events: none;
    }

    .story-viewer-header > * {
        pointer-events: auto;
    }

    .story-viewer-avatar {
        width: 44px;
        height: 44px;
        border-radius: 999px;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,0.9);
    }

    .story-viewer-name {
        font-size: 0.95rem;
        font-weight: 700;
        line-height: 1.1;
    }

    .story-viewer-time {
        font-size: 0.72rem;
        opacity: 0.72;
    }

    .story-viewer-close,
    .story-viewer-viewers-btn,
    .story-viewer-viewers-link {
        border: 0;
        background: rgba(15, 23, 42, 0.55);
        color: #fff;
        border-radius: 999px;
        height: 38px;
        padding: 0 0.9rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.88rem;
        font-weight: 600;
    }

    .story-viewer-close {
        width: 38px;
        padding: 0;
        font-size: 1rem;
    }

    .story-viewer-stage-wrap {
        position: relative;
        min-height: 72vh;
        display: grid;
        place-items: stretch;
    }

    .story-viewer-stage {
        min-height: 72vh;
        display: grid;
        place-items: center;
        background: #020617;
    }

    .story-viewer-media,
    .story-viewer-media img,
    .story-viewer-media video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .story-viewer-media img,
    .story-viewer-media video {
        max-height: 72vh;
    }

    .story-viewer-text {
        width: 100%;
        min-height: 72vh;
        display: grid;
        place-items: center;
        padding: 2rem;
        text-align: center;
        word-break: break-word;
    }

    .story-viewer-text h2 {
        max-width: 24rem;
        font-size: clamp(1.5rem, 4vw, 2.5rem);
        line-height: 1.15;
        font-weight: 800;
        margin: 0;
    }

    .story-viewer-loading {
        display: grid;
        place-items: center;
        min-height: 72vh;
    }

    .story-nav {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 18%;
        z-index: 4;
        border: 0;
        background: transparent;
        color: #fff;
        display: grid;
        place-items: center;
        font-size: 1.4rem;
    }

    .story-nav-prev {
        left: 0;
    }

    .story-nav-next {
        right: 0;
    }

    .story-viewer-footer {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem;
        background: linear-gradient(180deg, rgba(2, 6, 23, 0), rgba(2, 6, 23, 0.88));
    }

    .story-viewer-footer__meta {
        font-size: 0.82rem;
        opacity: 0.9;
    }

    .story-viewers-panel {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 66px;
        max-height: 36vh;
        overflow: auto;
        background: rgba(15, 23, 42, 0.96);
        backdrop-filter: blur(14px);
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        padding: 0.75rem 1rem 1rem;
    }

    @media (max-width: 767.98px) {
        .story-viewer-frame {
            border-radius: 0;
            min-height: 100vh;
        }

        .story-viewer-stage,
        .story-viewer-text,
        .story-viewer-loading {
            min-height: 100vh;
        }

        .story-nav {
            width: 30%;
        }
    }
</style>
