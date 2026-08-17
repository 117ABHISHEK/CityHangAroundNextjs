<!-- Content Section Start -->
<div class="event-page-wrap">
    <div
        class="d-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h1 class="font-weight-light text-primary">{{ $category->category_name }}</h1>
        
    </div>
    
    <div class="event-wrap row" >
        @include('frontend.events.custom_event_single') 
    </div>

</div>





<!-- Content Section End -->