<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>

<div class="marketplace-wrap">
    <div class="d-md-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h3 class="h5"><span><i class="fa fa-flag"></i></span> {{ get_phrase('Pages') }}</h3>
        @if(auth()->user())
        <!--<div class="pagebtnListing"> -->
                <!-- <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.marketplace.create_product'])}}', '{{get_phrase('Create Product')}}');" class="btn btn-primary" data-bs-toggle="modal"
        <!--            data-bs-target="#createProduct" class="btn btn-primary"> <i class="fa fa-plus-circle"></i></a> -->
        <!--            <a href="{{ route('pages.create') }}" class="btn btn-primary">-->
        <!--        <i class="fa fa-plus-circle"></i> {{ get_phrase('Create Page') }}-->
        <!--    </a>-->
        <!--    <a href="{{ route('pages.user') }}" class="btn  mx-1">{{ get_phrase('My Pages') }}</a>-->
        <!--    <a href="{{ route('pages.suggested') }}" class="btn  mx-1">{{ get_phrase('Suggested Pages') }}</a>-->
        <!--    <a href="{{ route('pages.joined') }}" class="btn  mx-1">{{ get_phrase('Joined Pages') }}</a>-->
        <!--    <a href="{{ route('pages.incomplete') }}" class="btn  mx-1">{{ get_phrase('Incomplete Pages') }}</a>-->
            
        <!--</div>-->
        <!---->
        <div class="pagebtnListing">
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('pages.create') }}" class="btn btn-primary">
            <i class="fa fa-plus-circle"></i> {{ get_phrase('Create Page') }}
        </a>
        <a href="{{ route('pages.user') }}" class="btn btn-outline-primary">
            {{ get_phrase('My Pages') }}
        </a>
        <a href="{{ route('pages.suggested') }}" class="btn btn-outline-primary">
            {{ get_phrase('Suggested Pages') }}
        </a>
        <a href="{{ route('pages.joined') }}" class="btn btn-outline-primary">
            {{ get_phrase('Joined Pages') }}
        </a>
        <a href="{{ route('pages.incomplete') }}" class="btn btn-outline-primary">
            {{ get_phrase('Incomplete Pages') }}
        </a>
    </div>
</div>

        <!---->
        @endif
    </div>
    





    <!-- Product Listing Start -->
     
    <div class="product-listing"> 
    
        <div class="row g-3" id="@if(str_contains(url()->current(), '/productdata')) single-item-countable @endif">
            @include('frontend.pages.single-page')
        </div>
    </div>
     <!-- pagination -->
     <div class="pagination-area" style="text-align:center;">
                            <div aria-label="Page navigation example">
                                <ul class="pagination">
                               {{ $mypages->links() }}
                                </ul>
                            </div>
                        </div>
                        <!-- pagination end -->
</div>
     @include('frontend.footer')

<div class="container mx-auto px-6 py-16 bg-white" hidden>
    <!-- H1 Title -->
    <h1 class="text-5xl font-bold text-center text-gray-900 leading-tight">
        Explore <span class="text-blue-600">City Hangaround</span> – Find Deals, Listings & More!
    </h1>

    <!-- Subtitle Sections -->
    <div class="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-center">
        <div class="p-6 bg-gray-50 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-gray-800">🔍 What Can You Find?</h2>
        </div>
        <div class="p-6 bg-gray-50 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-gray-800">🏢 Browse Business Listings</h2>
        </div>
        <div class="p-6 bg-gray-50 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-gray-800">💰 Discover the Best Deals</h2>
        </div>
        <div class="p-6 bg-gray-50 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-gray-800">📢 Get City Updates</h2>
        </div>
        <div class="p-6 bg-gray-50 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-gray-800">🤝 Connect Locally</h2>
        </div>
    </div>

    <!-- Dynamic Content Section -->
    <div class="mt-12 bg-white shadow-md rounded-xl p-8 border">
        <p class="text-lg text-gray-700 leading-relaxed">
            Welcome to City Hangaround’s Pages Directory, your one-stop destination for finding everything local! Whether you're looking for exclusive deals, top business listings, trending events, or community discussions, you’ll find it all right here.
        </p>

        <!-- What's Inside -->
        <div class="mt-6">
            <h3 class="text-2xl font-semibold text-blue-600 mb-4">🔎 What’s Inside?</h3>
            <ul class="list-none space-y-4 text-gray-700">
                <li class="flex items-center space-x-3">
                    <span class="text-blue-500 text-2xl">✅</span>
                    <strong>Business Listings –</strong> Discover top local businesses across multiple categories.
                </li>
                <li class="flex items-center space-x-3">
                    <span class="text-blue-500 text-2xl">✅</span>
                    <strong>Best Deals & Discounts –</strong> Save money on restaurants, shopping, entertainment, and more.
                </li>
                <li class="flex items-center space-x-3">
                    <span class="text-blue-500 text-2xl">✅</span>
                    <strong>City-Based Information –</strong> Find businesses and events in your city with a simple search.
                </li>
                <li class="flex items-center space-x-3">
                    <span class="text-blue-500 text-2xl">✅</span>
                    <strong>Community Engagement –</strong> Join local discussions, groups, and social events.
                </li>
            </ul>
        </div>

        <!-- Search Prompt -->
        <div class="mt-8 text-center">
            <p class="text-lg text-gray-700 font-semibold">📍 Looking for something specific?</p>
            <p class="text-gray-600">Use our search feature to explore businesses, categories, and deals in your area!</p>
        </div>

        <!-- Business Owner Call to Action -->
        <div class="mt-8 bg-blue-50 border-l-4 border-blue-600 p-6 rounded-lg">
            <p class="text-lg font-bold text-blue-700">💡 Are you a Business Owner?</p>
            <p class="text-gray-700">List your business for <strong>FREE</strong> on City Hangaround and reach more customers today!</p>
        </div>
    </div>

    <!-- FAQs Section -->
    <div class="mt-12">
        <h2 class="text-3xl font-extrabold text-center text-gray-900 mb-8">FAQs</h2>
        <div class="space-y-6">
            <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                <summary class="font-semibold text-lg">1. What can I find on the Cityhangaround Pages Directory?</summary>
                <p class="mt-2 text-gray-600">Our pages directory includes business listings, deals, events, and community updates for different cities and categories.</p>
            </details>

            <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                <summary class="font-semibold text-lg">2. How do I search for businesses or deals in my city?</summary>
                <p class="mt-2 text-gray-600">Simply use the search bar to enter your city or category and find relevant listings instantly.</p>
            </details>

            <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                <summary class="font-semibold text-lg">3. Can I list my business for free on Cityhangaround?</summary>
                <p class="mt-2 text-gray-600">Yes! Business owners can add their listings for free and attract more customers.</p>
            </details>

            <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                <summary class="font-semibold text-lg">4. How often are listings and deals updated?</summary>
                <p class="mt-2 text-gray-600">We update our business listings and deals daily, so you always get the latest offers and information.</p>
            </details>

            <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                <summary class="font-semibold text-lg">5. Is Cityhangaround available in multiple cities?</summary>
                <p class="mt-2 text-gray-600">Yes! Cityhangaround operates in multiple cities across India, offering location-based deals and business listings.</p>
            </details>

            <details class="bg-white border rounded-lg p-4 shadow-sm cursor-pointer">
                <summary class="font-semibold text-lg">6. How can I contact customer support?</summary>
                <p class="mt-2 text-gray-600">For any queries, visit our Contact Us page or email us at [cityhangaround@gmail.com].</p>
            </details>
        </div>
    </div>
</div>


