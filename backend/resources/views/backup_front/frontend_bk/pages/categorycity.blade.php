<script type="application/ld+json">
            {
            @php

            $ratcount = rand(90,99);
        @endphp

        "@context":"https://schema.org",
        "@type":"Review",
        "itemReviewed":{
        "@type":"LocalBusiness",
  	"name": "Top 10 {{$category->category_name}} in {{$city->city_name}}",
                 "url":"{{$_SERVER['REQUEST_URI']}}",
                 "address":{"@type":"PostalAddress","addressLocality":"{{$city->city_name}}"}},
                 "author":"Users",
                 "ReviewRating":{
                    "@type":"AggregateRating",
                    "ratingValue":"9.3",
                    "ratingCount":"{{$ratcount}}",
                    "bestRating":"10"
            }}
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
Explore the Best {{ $category->category_name }} Services in  {{$city->city_name}}
    </h1>

    @foreach ($mypages as $key => $mypage)
    @php
            $itemCategories = $mypage->pageCategories;
            $lastCategory = $itemCategories->last();
        @endphp

    
    <div class="col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6 mb-3" id="page-{{ $mypage->id }}">
        
    
    <div class="card sugg-card p-2 rounded">
   
               <a href="{{ route('single.page',['city_slug'=>$mypage->city->city_slug,'area_slug'=>$mypage->area->area_slug,'category_slug'=>$lastCategory->category_slug,'item_slug'=>$mypage->item_slug]) }}" class="mb-2 thumbnail-110-106" style="background-image: url('{{ get_page_logo($mypage->logo, 'logo') }}')"></a>
              
           
            <div class="smp-info">
                <a href="{{ route('single.page',['city_slug'=>$mypage->city->city_slug,'area_slug'=>$mypage->area->area_slug,'category_slug'=>$lastCategory->category_slug,'item_slug'=>$mypage->item_slug]) }}"> <h4 class="h6">{{ $mypage->title}}</h4> </a>

                 <a href="{{ route('page.category',['category_slug'=>$lastCategory->category_slug]) }}">
            {{ $lastCategory->category_name }}
             </a>
             <br>
              
                <a href="{{ route('page.category.city',['category_slug'=>$lastCategory->category_slug,'city_slug'=>$mypage->city->city_slug]) }}">
                  
                {{ $mypage->area->area_name }}, {{ $mypage->city->city_name }}
                </a>
             <br>
                @php
                    $likecount = \App\Models\Page_like::where('page_id',$mypage->id)->count();
                @endphp
                <a href="{{ route('single.page',['city_slug'=>$mypage->city->city_slug,'area_slug'=>$mypage->area->area_slug,'category_slug'=>$lastCategory->category_slug,'item_slug'=>$mypage->item_slug]) }}"><span><i class="fa fa-thumbs-up"></i>{{ $likecount }} {{ get_phrase('People follow this') }}</span></a>
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


<div class="container mx-auto px-4 py-12 bg-white" hidden>
    <!-- H2 Titles -->
    <h2 class="text-3xl font-bold text-center text-gray-900 mb-6">Top {{ $category->category_name }} Businesses in {{ $city->city_name }}</h2>
    <h2 class="text-2xl font-semibold text-center text-gray-800 mb-4">Best Deals & Offers on {{ $category->category_name }} in {{ $city->city_name }}</h2>
    <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Customer Reviews: Top-Rated {{ $category->category_name }} in {{ $city->city_name }}</h2>

    <!-- Dynamic Content -->
    <div class="bg-white shadow-md rounded-xl p-8 border">
        <p class="text-lg text-gray-700 leading-relaxed">
            Looking for the best {{ $category->category_name }} services in {{ $city->city_name }}? City Hangaround makes it easy to find, compare, and choose the top-rated businesses and service providers near you.
        </p>

        <!-- What's Inside -->
        <div class="mt-6">
            <h3 class="text-2xl font-semibold text-blue-600 mb-4">🔎 What You Can Find in {{ $city->city_name }}’s {{ $category->category_name }} Section:</h3>
            <ul class="list-none space-y-4 text-gray-700">
                <li class="flex items-center space-x-3">
                    <span class="text-blue-500 text-2xl">✅</span>
                    <strong>Top Businesses & Service Providers –</strong> Verified listings with customer reviews.
                </li>
                <li class="flex items-center space-x-3">
                    <span class="text-blue-500 text-2xl">✅</span>
                    <strong>Best Deals & Discounts –</strong> Save on services, products, and more.
                </li>
                <li class="flex items-center space-x-3">
                    <span class="text-blue-500 text-2xl">✅</span>
                    <strong>Location-Based Listings –</strong> Easily find businesses near your area.
                </li>
                <li class="flex items-center space-x-3">
                    <span class="text-blue-500 text-2xl">✅</span>
                    <strong>User Ratings & Reviews –</strong> Choose trusted service providers based on customer feedback.
                </li>
            </ul>
        </div>

        <!-- Search Prompt -->
        <div class="mt-8 text-center">
            <p class="text-lg text-gray-700 font-semibold">📍 Looking for the best {{ $category->category_name }} in {{ $city->city_name }}?</p>
            <p class="text-gray-600">Use our search feature to explore top businesses, deals, and services today!</p>
        </div>

        <!-- Business Owner Call to Action -->
        <div class="mt-8 bg-blue-50 border-l-4 border-blue-600 p-6 rounded-lg">
            <p class="text-lg font-bold text-blue-700">💡 Own a {{ $category->category_name }} Business in {{ $city->city_name }}?</p>
            <p class="text-gray-700">List it for <strong>FREE</strong> on City Hangaround and increase visibility!</p>
        </div>
    </div>

    <!-- FAQs Section -->
    <div class="mt-12">
        <h2 class="text-3xl font-extrabold text-center text-gray-900 mb-8">FAQs</h2>
        <div class="space-y-6">
            <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                <summary class="font-semibold text-lg">1. What are the best {{ $category->category_name }} businesses in {{ $city->city_name }}?</summary>
                <p class="mt-2 text-gray-600">We list top-rated {{ $category->category_name }} businesses in {{ $city->city_name }} based on customer reviews, services, and deals.</p>
            </details>

            <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                <summary class="font-semibold text-lg">2. How can I find the best deals on {{ $category->category_name }} services in {{ $city->city_name }}?</summary>
                <p class="mt-2 text-gray-600">Visit our Deals page to explore discounted services, special offers, and seasonal promotions in {{ $city->city_name }}.</p>
            </details>

            <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                <summary class="font-semibold text-lg">3. Can I add my {{ $category->category_name }} business in {{ $city->city_name }} for free?</summary>
                <p class="mt-2 text-gray-600">Yes! Business owners can list their services for free on City Hangaround and connect with potential customers.</p>
            </details>

            <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                <summary class="font-semibold text-lg">4. How do I choose the best {{ $category->category_name }} service in {{ $city->city_name }}?</summary>
                <p class="mt-2 text-gray-600">Check customer ratings, reviews, and business details to compare and select the best option for your needs.</p>
            </details>

            <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                <summary class="font-semibold text-lg">5. How often are listings and deals updated?</summary>
                <p class="mt-2 text-gray-600">Our team updates listings and deals daily, ensuring you get the most relevant and up-to-date information.</p>
            </details>

            <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                <summary class="font-semibold text-lg">6. How can I contact City Hangaround for support?</summary>
                <p class="mt-2 text-gray-600">For any inquiries, visit our Contact Us page or email us at cityhangaround@gmail.com.</p>
            </details>
        </div>
    </div>

</div>
