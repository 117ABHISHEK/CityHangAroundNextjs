<!-- Content Section Start -->
<div class="event-page-wrap">
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
                                 <!-- <li class="breadcrumb-item">
                                    <a href="{{ route('userevent') }}">
                                      Event
                                    
                                    </a>
                                </li> -->

                                 <li class="breadcrumb-item"><a>{{ $category->category_name }}</a></li>
                                
</ol>
</nav>
</div>
</div>
     <div class="d-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h3 class="h5 pt-3"><span ><img width="12" src="{{ asset('assets/frontend/images/stickies-fill.png') }}" alt=""></span> {{ get_phrase('events') }}</h3>
        <div class="inline-btn w-50">
            <a href="{{ route('admin.event.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle me-1"></i>{{ get_phrase('Add Event') }}</a>
        </div>
    </div>
   
        <h1 class="font-weight-light text-primary">{{ $category->category_name }}</h1>
        
   
    
    <div class="event-wrap row" >
        @include('frontend.events.custom_event_single') 
    </div>

</div>





<!-- Content Section End -->