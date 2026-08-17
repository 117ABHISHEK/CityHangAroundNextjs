<!-- Content Section Start -->


<div class="event-page-wrap">
    <div
        class="d-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h3 class="h5 pt-3"><span><i class="fa-solid fa-calendar-days"></i></span> {{ get_phrase('Events') }}</h3>
        <div class="">
            <!-- <button onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.events.create_event'])}}', '{{get_phrase('Create Event')}}');" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                data-bs-target="#createEvent"><i class="fa fa-plus-circle m-0"></i> <div class="d-none d-md-inline-block">{{get_phrase('Create Event')}}</div></button> -->
                <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm">{{ get_phrase("Create Event") }}</a>
            
        </div>
    </div>
    
    <div class="event-wrap row">
        @foreach ($events as $event )
        <?php

                       

            $city= DB::table('cities')
            ->where('id', $event->city_id)
            ->first();

            $area= DB::table('areas')
            ->where('city_id', $event->city_id)
            ->where('id', $event->area_id)
            ->first();

            $item_categories = DB::table('event_category')
            ->where('event_id',$event->id)
            ->get();


            $item_count=count($item_categories);
            $categoriesss = DB::table('eventcategories')
                ->where('id', $item_categories[$item_count-1]->category_id)
                ->get();
                                    
            $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 
            ?>
        @php  $postOfThisEvent = \App\Models\Posts::where('publisher','event')->where('publisher_id',$event->id)->first(); @endphp
        <div class="col-lg-6" id="event-{{ $event->id }}">
            
            <div class="card event-card p-2">
                <a href="{{ route('single.event',['city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug,'category_slug'=>$catslug,'event_slug'=>$event->event_slug]) }}">
                    <div class="event-image thumbnail-210-200" style="background-image: url('{{ get_event_banner_image($event, 'thumbnail') }}')">
                        {{--  just pass {folder name},{file name}and {folder type} then wait in viewImage function  --}}
                        <div class="event-date">
                            @php $date = explode("-",$event->event_date); @endphp
                            <span>{{ $date['2']}}</span>
                        </div>
                    </div>
                </a>
                <div class="event-text">
                    <small class="event-meta">{{ date('D', strtotime($event->event_date)) }}, {{ date('d F Y', strtotime($event->event_date))  }}, at {{ $event->event_time }}</small>
                    <h3><a class="ellipsis-line-2" href="{{ route('single.event',['city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug,'category_slug'=>$catslug,'event_slug'=>$event->event_slug]) }}">{{$event->title}}</a></h3>
                    <div class="organiser d-flex mt-3 align-items-center">
                        <a href="#"><img src="{{get_user_image($event->getUser->photo, 'optimized')}}"  width="35" class="user-round" alt=""></a>
                        <div class="ognr-info ms-2">
                            <h6 class="m-0"><a href="#">{{ $event->getUser->name }}</a></h6>
                            <small class="mute">{{ $event->location }}</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <a href="#" class="btn btn-primary">{{ get_phrase('Going')}}</a>
                        <a href="#" class="btn btn-secondary">{{ get_phrase('Interested')}}</a>
                        @if ($event->user_id==auth()->user()->id)
                        <div class="post-controls dropdown">
                            <div class="dropdown">
                                <button class="btn btn-secondary" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i> 
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li>
                                    <a href="{{ route('events.edit',['id'=>$event->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit me-1"></i> {{ get_phrase("Edit Event") }}</a>
                                        <!-- <button onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.events.edit_event', 'event_id' => $event->id] )}}', '{{get_phrase('Edit Event')}}');" class="dropdown-item btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#createEvent"><i class="fa fa-edit me-1"></i> {{get_phrase('Edit Event')}}</button> -->
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" onclick="confirmAction('<?php echo route('event.delete', ['event_id' => $event->id]); ?>', true)" class="dropdown-item btn btn-primary btn-sm"><i class="fa fa-trash me-1"></i> {{get_phrase('Delete Event')}}</a>
                                    </li>

                                    @if($postOfThisEvent != null)
                                        <li>
                                            <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.main_content.share_post_modal', 'post_id' => $postOfThisEvent->post_id] )}}', '{{get_phrase('Share Event')}}');" class="dropdown-item "><i class="fa fa-share me-1"></i> {{get_phrase('Share Event')}}</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        @else
                            @if($postOfThisEvent != null)
                                <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.main_content.share_post_modal', 'post_id' => $postOfThisEvent->post_id] )}}', '{{get_phrase('Share Event')}}');" class="dropdown-item "><i class="fa fa-share me-1"></i> {{get_phrase('Share Event')}}</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
</div>

</div>






<!-- Content Section End -->
