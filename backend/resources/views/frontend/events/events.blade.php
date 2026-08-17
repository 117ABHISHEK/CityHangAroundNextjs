
<div
        class="d-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h1 class="font-weight-light text-primary">Explore Exciting Events Happening in Your City & Area</h1>
        
    </div>
    

<!-- Content Section Start -->
<div class="event-page-wrap">
    <div
        class="d-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h3 class="h5 pt-3"><span><i class="fa-solid fa-calendar-days"></i></span> {{ get_phrase('Events') }}</h3>
        <div class="">
            <!-- <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.events.create_event'])}}', '{{get_phrase('Create Event')}}');" data-bs-toggle="modal"
                data-bs-target="#createEvent" class="btn btn-primary btn-sm"> <i class="fa fa-plus-circle m-0"></i> <div class="d-none d-md-inline-block">{{get_phrase('Create Event')}}</div></a> -->
                 @if(auth()->user())
                <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm mt-1">{{ get_phrase("Create Event") }}</a>
            <a href="{{ route('userevent') }}" class="btn btn-primary btn-sm mt-1">{{ get_phrase("My Event") }}</a>
            @endif
        </div>
    </div>
    
    <div class="event-wrap row" id="eventdata">
        @include('frontend.events.event-single') 
    </div>

</div>

@include('frontend.footer')


<div class="d-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white"  style="display:none!important">
    <div class="container mt-4" hidden>
    <h2 class="text-center">Find the Best Upcoming Events in Your City & Area </h2>
    
    <ul class="list-unstyled text-center mt-3">
        <li><strong>🎵 Concerts, Festivals & Entertainment –</strong> Enjoy the Best Experiences</li>
        <li><strong>💼 Business & Networking Events –</strong> Expand Your Connections</li>
        <li><strong>📚 Workshops & Seminars –</strong> Learn New Skills & Grow Professionally</li>
        <li><strong>🎭 Community & Cultural Events –</strong> Celebrate & Connect with Locals</li>
    </ul>

    <h3 class="mt-4">FAQ’s</h3>

    <div class="mt-3">
        <h4>How to do event listing?</h4>
        <p>✅ Step 1 - Register on the website</p>
        <p>✅ Step 2 - Add your event>here</a></p>
    </div>

    <div class="mt-3">
        <h4>How to promote an event?</h4>
        <p>Please">contact us</a> for paid promotion on this page.</p>
    </div>
</div>
</div>


<!-- Content Section End -->