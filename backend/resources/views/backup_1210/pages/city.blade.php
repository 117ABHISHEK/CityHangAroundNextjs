<script type="application/ld+json">
            {
                 "@context":"https://schema.org",
                 "@type":"Review","itemReviewed":{
                 "@type":"LocalBusiness",
                 "name":"Top 5 LocalBusiness in {{$city->city_name}}",
                 "url":"{{$_SERVER['REQUEST_URI']}}",
                 "address":{"@type":"PostalAddress","addressLocality":"{{$city->city_name}}"}},
                 "author":"Users",
                 "ReviewRating":{
                    "@type":"AggregateRating",
                    "ratingValue":"4.1",
                    "ratingCount":"14198",
                    "bestRating":"5"
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
                                <li class="breadcrumb-item"><a href="">{{ $city->city_name }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
               
<div class="suggest-wrap row" id="pagedata">
<h1 class="font-weight-light text-primary">
Explore {{$city->city_name}} – Your Ultimate City Guide!
</h1>



    @foreach ($categories as $key => $category)
    <h2 class="font-weight-light text-primary">
     {{$category->category_name}}
    </h2>
   
    <?php 
    
     $mypages=App\Http\Controllers\PageController::getpagesbycategoryid($category->id,$city->id);
    
    ?>
     @foreach ($mypages as $key => $mypage)
    <?php
         
         $item_categories = DB::table('page_category')
    ->where('page_id', $mypage->id)
    ->get();

$item_count = count($item_categories);

$catslug = null;
$categoryName = null;

if ($item_count > 0) {
    $category_id = $item_categories[$item_count - 1]->category_id;

    $category = DB::table('pagecategories')->where('id', $category_id)->first();

    if ($category) {
        $catslug = $category->category_slug;
        $categoryName = $category->category_name;
    }
}

        
    ?>
    
    <div class="col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6 mb-3" id="page-{{ $mypage->id }}">
        
   @if($catslug)
    <div class="card sugg-card p-2 rounded">
         
    <a href="{{ route('page.category',['category_slug'=>$catslug]) }}">
            {{ $categoryName }}
             </a>
            <a href="{{ route('single.page',['city_slug'=>$mypage->city_slug,'area_slug'=>$mypage->area_slug,'category_slug'=>$catslug,'item_slug'=>$mypage->item_slug]) }}">
                <img src="{{ get_page_logo($mypage->logo, 'logo') }}" class="rounded-8px" width="90px" alt="">
            </a>
           
            <div class="smp-info">
                <a href="{{ route('single.page',['city_slug'=>$mypage->city_slug,'area_slug'=>$mypage->area_slug,'category_slug'=>$catslug,'item_slug'=>$mypage->item_slug]) }}"> <h4 class="h6">{{ $mypage->title }}</h4> </a>
              
                <a href="{{ route('page.category.city',['category_slug'=>$catslug,'city_slug'=>$mypage->city_slug]) }}">
            {{ $mypage->area_name }}, {{ $city->city_name }}  
             </a>
             <br>
                @php
                    $likecount = \App\Models\Page_like::where('page_id',$mypage->id)->count();
                @endphp
                <a href="{{ route('single.page',['city_slug'=>$mypage->city_slug,'area_slug'=>$mypage->area_slug,'category_slug'=>$catslug,'item_slug'=>$mypage->item_slug]) }}"><span><i class="fa fa-thumbs-up"></i>{{ $likecount }} {{ get_phrase('People follow this') }}</span></a>
            </div>
        </div>

        </div>
         @endif
     @endforeach   
     @if($category && $category->category_slug)
    <a href="{{ route('page.category', ['category_slug' => $category->category_slug]) }}" style="text-align:center;">View All</a>
@endif

    
    @endforeach

   
    
</div>
<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-12 bg-white" hidden>
        <!-- H1 Title -->
        <h1 class="text-5xl font-extrabold text-center text-gray-900 mb-8">
            Explore {{ $city->city_name }} – Find Deals, Listings & More!
        </h1>

        <!-- H2 Sections -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 text-center mb-12">
            <h2 class="text-2xl font-semibold text-gray-700 p-4 border rounded-lg shadow-sm">Top Business Listings in {{ $city->city_name }}</h2>
            <h2 class="text-2xl font-semibold text-gray-700 p-4 border rounded-lg shadow-sm">Best Deals & Discounts in {{ $city->city_name }}</h2>
            <h2 class="text-2xl font-semibold text-gray-700 p-4 border rounded-lg shadow-sm">Upcoming Events in {{ $city->city_name }} – Stay Updated!</h2>
            <h2 class="text-2xl font-semibold text-gray-700 p-4 border rounded-lg shadow-sm">Things to Do in {{ $city->city_name }} – Explore Local Attractions</h2>
        </div>

        <!-- Dynamic Content Section -->
        <div class="bg-white shadow-md rounded-xl p-8 border">
            <p class="text-lg text-gray-700 leading-relaxed">
                Welcome to City Hangaround – {{ $city->city_name }}’s ultimate local directory! Whether you're looking for top-rated restaurants, shopping destinations, entertainment options, or exclusive deals, we’ve got you covered.
            </p>

            <!-- What's Inside -->
            <div class="mt-6">
                <h3 class="text-2xl font-semibold text-blue-600 mb-4">🔎 What Can You Find in {{ $city->city_name }}?</h3>
                <ul class="list-none space-y-4 text-gray-700">
                    <li class="flex items-center space-x-3">
                        <span class="text-blue-500 text-2xl">✅</span>
                        <strong>Best Restaurants in {{ $city->city_name }} –</strong> Find the top dining spots with great offers.
                    </li>
                    <li class="flex items-center space-x-3">
                        <span class="text-blue-500 text-2xl">✅</span>
                        <strong>Shopping & Malls –</strong> Explore the latest fashion, electronics, and more.
                    </li>
                    <li class="flex items-center space-x-3">
                        <span class="text-blue-500 text-2xl">✅</span>
                        <strong>Local Services –</strong> From salons to repair shops, find trusted professionals.
                    </li>
                    <li class="flex items-center space-x-3">
                        <span class="text-blue-500 text-2xl">✅</span>
                        <strong>Exciting Events –</strong> Stay updated with concerts, exhibitions, and festivals.
                    </li>
                    <li class="flex items-center space-x-3">
                        <span class="text-blue-500 text-2xl">✅</span>
                        <strong>Exclusive Deals –</strong> Save big on food, fashion, travel, and entertainment.
                    </li>
                </ul>
            </div>

            <!-- Search Prompt -->
            <div class="mt-8 text-center">
                <p class="text-lg text-gray-700 font-semibold">📍 Looking for something specific?</p>
                <p class="text-gray-600">Use our search feature to explore businesses, deals, and events in {{ $city->city_name }}!</p>
            </div>

            <!-- Business Owner Call to Action -->
            <div class="mt-8 bg-blue-50 border-l-4 border-blue-600 p-6 rounded-lg">
                <p class="text-lg font-bold text-blue-700">💡 Are You a Business Owner?</p>
                <p class="text-gray-700">List your business for <strong>FREE</strong> on City Hangaround and attract more customers today!</p>
            </div>
        </div>

        <!-- FAQs Section -->
        <div class="mt-12">
            <h2 class="text-3xl font-extrabold text-center text-gray-900 mb-8">FAQs</h2>
            <div class="space-y-6">
                <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                    <summary class="font-semibold text-lg">1. What services can I find in {{ $city->city_name }} on City Hangaround?</summary>
                    <p class="mt-2 text-gray-600">We provide business listings, deals, events, and attractions in {{ $city->city_name }}, helping you find everything you need in your city.</p>
                </details>

                <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                    <summary class="font-semibold text-lg">2. How do I find the best deals in {{ $city->city_name }}?</summary>
                    <p class="mt-2 text-gray-600">Visit our Deals page to discover exclusive discounts on food, shopping, entertainment, and more in {{ $city->city_name }}.</p>
                </details>

                <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                    <summary class="font-semibold text-lg">3. Can I list my business in {{ $city->city_name }} for free?</summary>
                    <p class="mt-2 text-gray-600">Yes! Business owners can add their listings for free and connect with more customers in {{ $city->city_name }}.</p>
                </details>

                <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                    <summary class="font-semibold text-lg">4. How often are listings and deals updated?</summary>
                    <p class="mt-2 text-gray-600">Our listings and deals are updated daily to ensure you always get the latest offers.</p>
                </details>

                <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                    <summary class="font-semibold text-lg">5. How do I search for businesses in {{ $city->city_name }}?</summary>
                    <p class="mt-2 text-gray-600">Use our search feature to enter a business name, category, or location and find what you're looking for.</p>
                </details>

                <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                    <summary class="font-semibold text-lg">6. How can I contact City Hangaround for support?</summary>
                    <p class="mt-2 text-gray-600">For any queries, visit our Contact Us page or email us at cityhangaround@gmail.com.</p>
                </details>
            </div>
        </div>
    </div>