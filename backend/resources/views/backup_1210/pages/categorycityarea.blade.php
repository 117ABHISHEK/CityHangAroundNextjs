<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "Top 10 {{ $category->category_name }} in {{ $area->area_name }}, {{ $city->city_name }}",
  "numberOfItems": {{ min(10, $mypages->count()) }},
  "itemListElement": [
    @foreach ($mypages->take(10) as $index => $item)
        @php
            $primaryCategory = $item->categories->last(); // Or first() if preferred
            $catslug = $primaryCategory?->category_slug ?? 'general';
            $citySlug = $item->city?->city_slug ?? 'unknown-city';
            $areaSlug = $item->area?->area_slug ?? 'unknown-area';
        @endphp
        {
            "@type": "ListItem",
            "position": {{ $index + 1 }},
            "name": "{{ $item->title }}",
            "url": "{{ route('single.page', [
                'city_slug' => $citySlug,
                'area_slug' => $areaSlug,
                'category_slug' => $catslug,
                'item_slug' => $item->item_slug
            ]) }}"
        }@if(!$loop->last),@endif
    @endforeach
  ]
}
</script>


<div class="row">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-white pl-0 pr-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('pages') }}">
                                        <i class="fas fa-bars"></i>
                                       Home
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('pages') }}">All Categories</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('page.category.city', ['category_slug'=>$category->category_slug, 'city_slug'=>$city->city_slug]) }}">{{ $city->city_name }}</a></li>


                                @foreach($parent_categories as $key => $parent_category)
                                    <li class="breadcrumb-item"><a href="{{ route('page.category.city', ['category_slug'=>$parent_category->category_slug, 'city_slug'=>$city->city_slug]) }}">{{ $parent_category->category_name }}</a></li>
                                @endforeach
                                <li class="breadcrumb-item"><a href="{{ route('page.category.city', ['category_slug'=>$category->category_slug, 'city_slug'=>$city->city_slug]) }}">{{ $category->category_name }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
  
<div class="suggest-wrap row" id="pagedata">
<h1 class="font-weight-light text-primary">
Explore the Best {{ $category->category_name }} Services in {{$area->area_name}}, {{$city->city_name}}!
    </h1>

    @foreach ($mypages as $key => $mypage)
    @php
        $category = $mypage->categories->last(); // or first() if preferred
        $catslug = $category?->category_slug ?? 'general';
        $citySlug = $mypage->city?->city_slug ?? 'unknown-city';
        $areaSlug = $mypage->area?->area_slug ?? 'unknown-area';
        $cityName = $mypage->city?->city_name ?? '';
        $stateName = $mypage->state?->state_name ?? '';
    @endphp

    <div class="col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6 mb-3" id="page-{{ $mypage->id }}">
        <div class="card sugg-card p-2 rounded">
           

            <a href="{{ route('single.page', [
                'city_slug' => $citySlug,
                'area_slug' => $areaSlug,
                'category_slug' => $catslug,
                'item_slug' => $mypage->item_slug
            ]) }}"
            class="mb-2 thumbnail-110-106" 
            style="background-image: url('{{ get_page_logo($mypage->logo, 'logo') }}')"></a>

            <div class="smp-info">
                <a href="{{ route('single.page', [
                    'city_slug' => $citySlug,
                    'area_slug' => $areaSlug,
                    'category_slug' => $catslug,
                    'item_slug' => $mypage->item_slug
                ]) }}">
                    <h4 class="h6">{{ $mypage->title }}</h4>
                </a>

                 @if ($category)
                <a href="{{ route('page.category', ['category_slug' => $catslug]) }}">
                    {{ $category->category_name }}
                </a>
            @endif
             <br>
                <a href="{{ route('page.category.city', [
                    'category_slug' => $catslug,
                    'city_slug' => $citySlug
                ]) }}">
                    {{ $mypage->area->area_name }}, {{ $mypage->city->city_name }}  
                </a>
                <br>

                <a href="{{ route('single.page', [
                    'city_slug' => $citySlug,
                    'area_slug' => $areaSlug,
                    'category_slug' => $catslug,
                    'item_slug' => $mypage->item_slug
                ]) }}">
                    <span><i class="fa fa-thumbs-up"></i> {{ $mypage->likes->count() }} {{ get_phrase('People like this') }}</span>
                </a>
            </div>
        </div>
    </div>
@endforeach


    <div class="row">
                        <div class="col-12">
                            {{ $mypages->links() }}
                        </div>
                    </div>
</div>


<div class="mt-6 bg-white shadow-lg rounded-lg p-6" style="padding:20px;" hidden>
    <!-- Section Title -->
    <div class="text-center">
        <h2 class="text-3xl font-bold text-gray-800">
            Top {{ $category->category_name }} Businesses in {{ $area->area_name }}, {{ $city->city_name }}
        </h2>
        <p class="text-lg text-gray-600 mt-2">
            Best Deals & Discounts on {{ $category->category_name }} in {{ $area->area_name }}.
        </p>
    </div>

    <!-- Dynamic Content -->
    <div >
        <p class="text-lg text-gray-700">
            Looking for the best <strong>{{ $category->category_name }}</strong> services in 
            <strong>{{ $area->area_name }}, {{ $city->city_name }}</strong>? 
            City Hangaround helps you find, compare, and choose top-rated businesses and service providers in your area.
        </p>

        <div class="mt-4">
            <h3 class="text-xl font-semibold text-gray-800">🔎 What You Can Find:</h3>
            <ul class="list-disc list-inside text-gray-700 mt-2 space-y-1">
                <li>✅ <strong>Top {{ $category->category_name }} Businesses</strong> – Verified listings with customer reviews.</li>
                <li>✅ <strong>Best Deals & Discounts</strong> – Save money with exclusive offers.</li>
                <li>✅ <strong>Location-Based Listings</strong> – Find services near you.</li>
                <li>✅ <strong>User Ratings & Reviews</strong> – Choose businesses based on real experiences.</li>
            </ul>
        </div>

        <div class="mt-6">
            <p class="text-lg text-gray-700">
                📍 Searching for the best <strong>{{ $category->category_name }}</strong> in 
                <strong>{{ $area->area_name }}, {{ $city->city_name }}</strong>? 
                Use our <strong>search feature</strong> to find top businesses, deals, and services today!
            </p>
        </div>

        <div class="mt-6 bg-gray-100 p-4 rounded-lg">
            <p class="text-lg font-semibold text-gray-800">💡 Own a {{ $category->category_name }} Business?</p>
            <p class="text-gray-700">List it for <strong>FREE</strong> on City Hangaround and attract more customers today!</p>
        </div>
    </div>

    <!-- FAQs -->
    <div class="mt-10">
        <h3 class="text-2xl font-semibold text-gray-800">Frequently Asked Questions</h3>
        <div class="mt-4 space-y-4">
            @php
                $faqs = [
                    "What are the best $category->category_name businesses in $area->area_name, $city->city_name?" => 
                        "We list top-rated businesses based on customer reviews, service quality, and best deals.",
                    "How can I find the best deals on $category->category_name services in $area->area_name, $city->city_name?" => 
                        "Check out our Deals page for discounts, special offers, and seasonal promotions.",
                    "Can I add my $category->category_name business for free?" => 
                        "Yes! List your business for free on City Hangaround and connect with more customers.",
                    "How do I choose the best $category->category_name service?" => 
                        "Compare customer ratings, reviews, and business details to find the right option.",
                    "How often are listings and deals updated?" => 
                        "Our team updates them daily to keep you informed.",
                    "How can I contact City Hangaround for support?" => 
                        "Visit our Contact Us page or email us at cityhangaround@gmail.com."
                ];
            @endphp

            @foreach ($faqs as $question => $answer)
            <div class="border-b border-gray-300 pb-3">
                <h4 class="text-lg font-semibold text-gray-800">{{ $question }}</h4>
                <p class="text-gray-700 mt-1">{{ $answer }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
