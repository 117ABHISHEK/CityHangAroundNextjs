
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
                                    <a href="{{ route('event') }}">
                                        <i class="fas fa-bars"></i>
                                       Home
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('event') }}">All Categories</a></li>
                                <li class="breadcrumb-item"><a href="">{{ $city->city_name }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
@foreach ($categories as $key => $category)
   
<?php 
    
     $events=App\Http\Controllers\Event\EventController::geteventbycategoryid($category->id,$city->id);
    
    ?>
<!-- Content Section Start -->
<div class="event-page-wrap">
    <div
        class="d-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h1 class="text-center text-dark">Explore Exciting Events in {{ $city->city_name }} – What’s Happening Near You?</h1>
        
    </div>
    
    <div class="event-wrap row" >
        @include('frontend.events.custom_event_single') 
    </div>

</div>
<a href="{{ route('event.category',['category_slug'=>$category->category_slug]) }}" style="text-align:center;">View All</a>
@endforeach




<div class="container mt-4 p-4 bg-white shadow-sm rounded">
    <h1 class="text-center text-dark">Explore Exciting Events in {{ $city->city_name }} – What’s Happening Near You?</h1>

    <h2 class="mt-4 text-primary">Top Upcoming Events in {{ $city->city_name }}</h2>

    <ul class="list-unstyled mt-3">
        <li><strong>🎵 Concerts & Music Festivals in {{ $city->city_name }} –</strong> Enjoy Live Performances</li>
        <li><strong>💼 Business & Networking Events in {{ $city->city_name }} –</strong> Connect & Grow</li>
        <li><strong>📚 Workshops & Educational Events in {{ $city->city_name }} –</strong> Learn & Develop</li>
        <li><strong>🎭 Community & Cultural Events in {{ $city->city_name }} –</strong> Celebrate Local Traditions</li>
    </ul>

    <h3 class="mt-4 text-secondary">FAQ</h3>

    <div class="mt-3">
        <h4 class="text-dark">How to List Your Event in {{ $city->city_name }}?</h4>
        <p>✅ Step 1 - Register on the website</p>
        <p>✅ Step 2 - Add your event <a href="{{ route('events.create') }}" class="text-primary">here</a></p>
    </div>

    <div class="mt-3">
        <h4 class="text-dark">How to Get More Attendees for Your Event in {{ $city->city_name }}?</h4>
        <p>Please <a href="{{ route('contact.view') }}" class="text-primary">contact us</a> for paid promotion on this page.</p>
    </div>
</div>

<!-- Content Section End -->