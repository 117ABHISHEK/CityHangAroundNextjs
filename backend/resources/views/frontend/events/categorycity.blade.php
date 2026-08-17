<!-- Content Section Start -->
<div class="event-page-wrap">
    <div
        class="d-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h1 class="font-weight-light text-primary">{{ $category->category_name }} in {{$city->city_name}}</h1>
        
    </div>
    
    <div class="event-wrap row" >
        @if(count($events) > 0)
            @include('frontend.events.custom_event_single') 
        @else
            <div class="col-12 text-center py-5">
                <div class="empty-state py-4">
                    <i class="far fa-calendar-times fa-3x text-muted mb-3" style="color: #cbd5e1 !important;"></i>
                    <h5 class="text-secondary" style="font-weight: 600;">No active events found</h5>
                    <p class="text-muted small">There are currently no upcoming events in this category. Check back later!</p>
                </div>
            </div>
        @endif
    </div>

</div>





<!-- Content Section End -->