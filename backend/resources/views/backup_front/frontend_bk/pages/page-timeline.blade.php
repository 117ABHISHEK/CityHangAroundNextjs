<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Restaurant",
  "name": "{{ $page->title }}",
  "image": [
    @foreach($page->media->take(3) as $photo)
      "{{ asset('uploads/media/' . $photo->file_path) }}"@if(!$loop->last),@endif
    @endforeach
  ],
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ $page->address }}",
    "addressLocality": "{{ $page->area->area_name ?? '' }}",
    "addressRegion": "{{ $page->city->city_name ?? '' }}",
    "postalCode": "{{ $page->pincode ?? '' }}",
    "addressCountry": "IN"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": {{ $page->latitude ?? 0 }},
    "longitude": {{ $page->longitude ?? 0 }}
  },
  "url": "{{ url()->current() }}",
  "telephone": "{{ $page->contact ?? '' }}",
  "servesCuisine": "{{ $page->categories->pluck('category_name')->implode(', ') }}",
  "priceRange": "$$",
  "openingHoursSpecification": [
    @foreach($page->openingHours as $hour)
      {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": "{{ ucfirst($hour->day_of_week) }}",
        "opens": "{{ $hour->open_time }}",
        "closes": "{{ $hour->close_time }}"
      }@if(!$loop->last),@endif
    @endforeach
  ],
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "{{ $rating ?? 4.0 }}",
    "reviewCount": "{{ $review_count ?? 1 }}"
  },
  "review": [
    @if(count($reviews))
      @foreach($reviews as $review)
      {
        "@type": "Review",
        "reviewRating": {
          "@type": "Rating",
          "ratingValue": "{{ $review->rating ?? 4 }}",
          "bestRating": "5"
        },
        "author": {
          "@type": "Person",
          "name": "{{ $review->user->name ?? 'Anonymous' }}"
        },
        "reviewBody": {!! json_encode($review->review ?? '') !!}
      }@if(!$loop->last),@endif
      @endforeach
    @else
      {
        "@type": "Review",
        "reviewRating": {
          "@type": "Rating",
          "ratingValue": "4",
          "bestRating": "5"
        },
        "author": {
          "@type": "Person",
          "name": "CityHangaround User"
        },
        "reviewBody": "Great place and service!"
      }
    @endif
  ]
}
</script>

<div class="row">
    {{-- Ensure $page is not null and categories exist --}}
    @if(!empty($page) && $page->categories->isNotEmpty())
        @php
            // Get the last category slug
            $catslug = $page->categories->last()->category_slug ?? null;
        @endphp
        <input type="hidden" id="category-slug" value="{{ $catslug }}">
    @endif

    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white pl-0 pr-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('pages') }}">
                        <i class="fas fa-bars"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('pages') }}">All Categories</a>
                </li>
                {{-- Check if the page exists and if categories and city_slug are available --}}
               
                @if(!empty($page))
                    <li class="breadcrumb-item">
                        <a href="{{ route('page.category.city', ['category_slug' => $catslug, 'city_slug' => $page->city->city_slug]) }}">
                            {{ $page->city->city_name }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('page.city.area', ['city_slug' => $page->city->city_slug, 'area_slug' => $page->area->area_slug]) }}">
                            {{ $page->area->area_name }}
                        </a>
                    </li>

                    {{-- Loop through parent categories if they exist --}}
                    @foreach($parent_categories as $parent_category)
                        <li class="breadcrumb-item">
                            <a href="{{ route('page.category.city.area', ['city_slug' => $page->city->city_slug, 'category_slug' => $parent_category->category_slug, 'area_slug' => $page->area->area_slug]) }}">
                                {{ $parent_category->category_name }}
                            </a>
                        </li>
                    @endforeach

                    {{-- Display current category --}}
                    <li class="breadcrumb-item">
                        <a href="{{ route('page.category.city.area', ['city_slug' => $page->city->city_slug, 'category_slug' => $category->category_slug, 'area_slug' => $page->area->area_slug]) }}">
                            {{ $category->category_name }}
                        </a>
                    </li>
                @endif
            </ol>
        </nav>
    </div>
</div>

<div class="profile-wrap">
    @include('frontend.pages.timeline-header')
    <div class="profile-content mt-3">
        <div class="">  
            @include('frontend.pages.inner-nav')
        </div>
        <div class="row gx-3">
            <div class="col-lg-7 col-sm-12">
                {{-- Check if the authenticated user is the page owner --}}
                @if(auth()->user())
                    @if(!empty($page) && $page->user_id == auth()->user()->id)
                        @include('frontend.main_content.create_post', ['page_id' => $page->id])
                    @endif
                @endif

                {{-- Fetch the comments for the page --}}
                @php
                    if(!empty($page)) {
                        $comments = DB::table('comments')
                            ->join('users', 'comments.user_id', '=', 'users.id')
                            ->where('comments.is_type', 'page')
                            ->where('comments.id_of_type', $page->id)
                            ->where('comments.parent_id', 0)
                            ->select('comments.*', 'users.name', 'users.photo')
                            ->orderBy('comment_id', 'DESC')
                            ->take(1)
                            ->get();

                        $total_comments = DB::table('comments')
                            ->where('comments.is_type', 'page')
                            ->where('comments.id_of_type', $page->id)
                            ->where('comments.parent_id', 0)
                            ->get()->count();
                    }
                @endphp

                {{-- Include comments --}}
                @if(!empty($page))
                    @include('frontend.main_content.comments', ['comments' => $comments, 'post_id' => $page->id, 'type' => "page"])
                @endif

                {{-- Include posts --}}
                @include('frontend.main_content.posts', ['type' => "page"])
            </div>

            <div class="col-lg-5 col-sm-12">
                @include('frontend.pages.bio')
            </div>
        </div>
    </div>
</div>

@include('frontend.main_content.scripts')
