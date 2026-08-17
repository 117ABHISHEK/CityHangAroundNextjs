@php use Illuminate\Support\Str; @endphp

<div class="story-viewers-list">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h6 class="mb-0">{{ get_phrase('Viewer list') }}</h6>
            <small class="text-muted">{{ $story->name ?? '' }}</small>
        </div>
        <span class="badge bg-secondary">{{ $viewers->count() }}</span>
    </div>

    @if($viewers->isEmpty())
        <div class="text-center py-3 text-muted">{{ get_phrase('No views yet') }}</div>
    @else
        <div class="story-viewers-list__items">
            @foreach($viewers as $viewer)
                <div class="story-viewers-list__item d-flex align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ get_user_image($viewer->photo ?? '', 'optimized') }}" alt="{{ $viewer->name ?? '' }}" class="story-viewers-list__avatar">
                        <div>
                            <div class="fw-semibold">{{ $viewer->name ?? '' }}</div>
                            <div class="small text-muted">{{ date_formatter($viewer->viewed_at, 2) }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .story-viewers-list__items {
        display: grid;
        gap: 0.65rem;
    }

    .story-viewers-list__item {
        padding: 0.8rem 0.85rem;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.06);
    }

    .story-viewers-list__avatar {
        width: 38px;
        height: 38px;
        border-radius: 999px;
        object-fit: cover;
    }
</style>
