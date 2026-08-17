@foreach ($events as $key => $event)

    @php
        $postOfThisEvent = $event->post ?? null;
        $postId = $postOfThisEvent?->post_id ?? 0;

        $categories = $event->categories;
        $lastCategory = $categories->last();

        $city = $event->city;
        $area = $event->area;

        $catslug = $lastCategory?->category_slug;
        $catname = $lastCategory?->category_name;
    @endphp

    <div class="col-lg-6 col-xl-4 col-md-4 col-sm-6 single-item-countable" id="event-{{ $event->id }}">
        <div class="card event-card p-2">
            @if ($lastCategory && $city && $area)
            <a href="{{ route('single.event', [
                'city_slug' => $city->city_slug,
                'area_slug' => $area->area_slug,
                'category_slug' => $catslug,
                'event_slug' => $event->event_slug
            ]) }}">
            @endif
                <div class="event-image thumbnail-210-200" style="background-image: url('{{ viewImage("event", $event->banner, "thumbnail") }}')">
                    <div class="event-date">
                        @php $date = explode("-", $event->event_date); @endphp
                        <span>{{ $date[2] }}</span>
                    </div>
                </div>
            </a>
            <div class="event-text">
                @if($lastCategory)
                    <h6>
                        <a href="{{ route('event.category', ['category_slug' => $catslug]) }}">
                            {{ $catname }}
                        </a>
                    </h6>
                @endif
                <small class="event-meta">
                    {{ date('l, d F Y', strtotime($event->event_date)) }},
                    at {{ $event->event_time }}
                </small>
                <h3>
                      @if ($lastCategory && $city && $area)
                    <a class="ellipsis-line-2" href="{{ route('single.event', [
                        'city_slug' => $city->city_slug,
                        'area_slug' => $area->area_slug,
                        'category_slug' => $catslug,
                        'event_slug' => $event->event_slug
                    ]) }}">{{ ellipsis($event->title, 100) }}</a>
                    @endif
                </h3>
                <div class="organiser d-flex mt-3 align-items-center">
                    <a href="#"><img src="{{ get_user_image($event->userphoto, 'optimized') }}" width="35" class="user-round" alt=""></a>
                    <div class="ognr-info ms-2">
                        <h6 class="m-0"><a href="#">{{ $event->username }}</a></h6>
                        <small class="mute">{{ $event->location }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Break loop after 3 if search --}}
    @if (isset($search) && !empty($search) && $key == 2)
        @break
    @endif

@endforeach
