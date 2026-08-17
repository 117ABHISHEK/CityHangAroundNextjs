{{-- JSON-LD Review Schema --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Review",
    "itemReviewed": {
        "@type": "LocalBusiness",
        "name": "Top 5 LocalBusiness in {{ $city->city_name ?? 'City' }}",
        "url": "{{ request()->fullUrl() }}",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "{{ $city->city_name ?? '' }}"
        }
    },
    "author": "Users",
    "reviewRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.3",
        "ratingCount": "{{ rand(90, 99) }}",
        "bestRating": "5"
    }
}
</script>

{{-- Breadcrumb --}}
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white pl-0 pr-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('pages') }}">
                        <i class="fas fa-bars"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('pages') }}">All Categories</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('page.city', ['city_slug' => $city->city_slug]) }}">
                        {{ $city->city_name }}
                    </a>
                </li>
                <li class="breadcrumb-item active">{{ $area->area_name }}</li>
            </ol>
        </nav>
    </div>
</div>

{{-- Listings Section --}}
<div class="row" id="pagedata">
    <div class="col-12 mb-4">
        <h1 class="font-weight-light text-primary">
            Find Top-Rated Businesses, Services & Events in {{ $area->area_name }}, {{ $city->city_name }}!
        </h1>
    </div>

   @foreach ($mypages as $mypage)
    @php
        $categories = $mypage->pageCategories ?? collect();
        $lastCategory = $categories->last();
        $catslug = $lastCategory->category_slug ?? null;
        $catname = $lastCategory->category_name ?? null;
        $likecount = $mypage->likes_count ?? 0;
    @endphp

    @if ($mypage->city && $mypage->area && $lastCategory && $mypage->item_slug)
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-4" id="page-{{ $mypage->id }}">
            <div class="card sugg-card p-3 shadow-sm rounded h-100 d-flex flex-column justify-content-between">

                

                @php
                    $singlePageUrl = route('single.page', [
                        'city_slug' => $mypage->city->city_slug,
                        'area_slug' => $mypage->area->area_slug,
                        'category_slug' => $lastCategory->category_slug,
                        'item_slug' => $mypage->item_slug,
                    ]);
                @endphp

               <a href="{{ $singlePageUrl }}" 
                   class="mb-2 d-block rounded bg-light" 
                   style="background-image: url('{{ get_page_logo($mypage->logo, 'logo') }}'); height: 140px; background-size: cover; background-position: center;">
                </a>

                <div class="smp-info">
                    <a href="{{ $singlePageUrl }}" >
                        <h4 class="h6 text-dark">{{ $mypage->title }}</h4>
                    </a>

                    <a href="{{ route('page.category', ['category_slug' => $catslug]) }}" class="text-primary font-weight-bold mb-2 d-block">
                    {{ $catname }}
                </a>

                    <a href="{{ route('page.category.city', [
                        'category_slug' =>  $lastCategory->category_slug,
                        'city_slug' => $mypage->city->city_slug
                    ]) }}" class="text-muted small">
                        {{ $mypage->area->area_name }}, {{ $mypage->city->city_name }}
                        
                    </a>
                    <br>

                    <a href="{{ $singlePageUrl }}"  class="text-primary small">
                        <i class="fa fa-thumbs-up"></i> {{ $likecount }} {{ get_phrase('People like this') }}
                    </a>
                </div>
            </div>
        </div>
    @endif
@endforeach


    <div class="col-12">
        {{ $mypages->links() }}
    </div>
</div>

{{-- Summary Box --}}
<div class="container mx-auto p-6 bg-white shadow-md rounded-lg mt-5" hidden>
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">
            Top Businesses in {{ $area->area_name }}, {{ $city->city_name }}
        </h2>
        <p class="text-lg text-gray-600 mt-2">
            Best Deals & Offers in {{ $area->area_name }}, {{ $city->city_name }}
        </p>
        <p class="text-lg text-gray-600 mt-2">
            Customer Reviews: Highly Rated Services in {{ $area->area_name }}, {{ $city->city_name }}
        </p>
    </div>

    <div class="bg-gray-50 p-6 rounded-lg">
        <p class="text-lg text-gray-700 leading-relaxed">
            Looking for trusted businesses, services, and exclusive deals in 
            <strong>{{ $area->area_name }}, {{ $city->city_name }}</strong>? 
            City Hangaround connects you with top-rated local businesses to help you make the best choices.
        </p>

        <div class="mt-6">
            <h3 class="text-xl font-semibold text-gray-800">🔎 What You Can Find:</h3>
            <ul class="list-disc list-inside text-gray-700 mt-3 space-y-2">
                <li>✅ <strong>Top-Rated Businesses</strong> – Verified listings with customer reviews.</li>
                <li>✅ <strong>Best Deals & Discounts</strong> – Save money with exclusive offers on services.</li>
                <li>✅ <strong>Nearby Listings</strong> – Find businesses and services in your area.</li>
                <li>✅ <strong>User Ratings & Reviews</strong> – Choose based on real customer experiences.</li>
            </ul>
        </div>

        <div class="mt-6">
            <p class="text-lg text-gray-700">
                📍 Searching for the best services in 
                <strong>{{ $area->area_name }}, {{ $city->city_name }}</strong>? 
                Use our <strong>search feature</strong> to explore local businesses, deals, and services today!
            </p>
        </div>

        <div class="mt-6 bg-blue-100 p-4 rounded-lg">
            <p class="text-lg font-semibold text-gray-800">💡 Own a Business in {{ $area->area_name }}, {{ $city->city_name }}?</p>
            <p class="text-gray-700">List it for <strong>FREE</strong> on City Hangaround and attract more customers!</p>
        </div>
    </div>

    {{-- FAQs --}}
    <div class="mt-10">
        <h3 class="text-2xl font-semibold text-gray-800">Frequently Asked Questions</h3>
        <div class="mt-6 space-y-6">
            @php
                $faqs = [
                    "What are the best businesses in $area->area_name, $city->city_name?" => 
                        "City Hangaround lists top-rated businesses and service providers in $area->area_name, $city->city_name, based on customer reviews and ratings.",
                    "How can I find the best deals on services in $area->area_name, $city->city_name?" => 
                        "Visit our Deals section to explore discounted services, promotions, and seasonal offers in your area.",
                    "Can I list my business in $area->area_name, $city->city_name for free?" => 
                        "Yes! Business owners can list their services for free on City Hangaround and gain more visibility.",
                    "How do I choose the best business or service in $area->area_name?" => 
                        "Check customer reviews, ratings, and business details to compare and select the best service for your needs.",
                    "How often are listings and deals updated?" => 
                        "Our team updates listings daily to ensure the latest information on businesses and services.",
                    "How can I contact Cityhangaround for support?" => 
                        "For any inquiries, visit our Contact Us page or email us at cityhangaround@gmail.com."
                ];
            @endphp

            @foreach ($faqs as $question => $answer)
                <div class="border-b border-gray-300 pb-4">
                    <h4 class="text-lg font-semibold text-gray-800">{{ $question }}</h4>
                    <p class="text-gray-700 mt-2">{{ $answer }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
