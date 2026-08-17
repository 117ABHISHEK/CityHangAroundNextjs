@foreach ($events as $key => $event )



@php  
 $postOfThisEvent = \App\Models\Posts::where('publisher','event')->where('publisher_id',$event->id)->first(); 
 if(!empty($postOfThisEvent->post_id)){
    $postId = $postOfThisEvent->post_id;
 }else{
    $postId = 0;
 }


@endphp



    <?php

         $city= DB::table('cities')
         ->where('id', $event->city_id)
         ->first();

         $area= DB::table('areas')
         ->where('city_id', $event->city_id)
         ->where('id', $event->area_id)
         ->first();
         
         // $item_categories = DB::table('event_category')
         // ->where('event_id', $event->id)
         // ->get();
 
         
         // $item_count=count($item_categories);
         // $categoriesss = DB::table('eventcategories')
         //     ->where('id', $item_categories[$item_count-1]->category_id)
         //     ->get();
                                 
         // $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 

         // Fetch all categories associated with the event
            $item_categories = DB::table('event_category')
                ->where('event_id', $event->id)
                ->get();

            // Get the total count of item categories
            $item_count = count($item_categories);

            // Initialize a variable to keep track of the valid category slug
            $catslug = null;

            // Loop through the item categories to check if the category exists in the eventcategories table
            foreach ($item_categories as $item) {
                // Fetch the event category by ID
                $categoriesss = DB::table('eventcategories')
                    ->where('id', $item->category_id)
                    ->get();

                // Check if a category exists, and select the first valid category slug
                if ($categoriesss->isNotEmpty()) {
                    $catslug = $categoriesss[0]->category_slug;
                    break; // Break if a valid category is found
                }
            }

            // If no valid category was found, skip the data
            if ($catslug === null) {
                // Optionally, you can log this or handle this case as per your needs
                // For now, we're skipping processing if no valid category is found.
                return; // Skip further processing if no valid category is found
            }

            // If you reach here, it means a valid category slug was found and you can proceed with the rest of your logic.

    ?>
<div class="col-lg-6 col-xl-4 col-md-4 col-sm-6 single-item-countable" id="event-{{ $event->id }}">
            
    <div class="card event-card p-2">
        <a href="{{ route('single.event',[ 'id' => $event->id,'city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug,'category_slug'=>$catslug,'event_slug'=>$event->event_slug]) }}">
            <div class="event-image thumbnail-210-200" style="background-image: url('{{ get_event_banner_image($event, 'thumbnail') }}')">
                <div class="event-date">
                    @php $date = explode("-",$event->event_date); @endphp
                    <span>{{ $date['2']}}</span>
                </div>
            </div>
        </a>
        <div class="event-text">
        <h6><a href="{{ route('event.category',['category_slug'=>$catslug]) }}">  {{ $categoriesss[0]->category_name }}</a></h6>
            <small class="event-meta">{{ date('l', strtotime($event->event_date)) }}, {{ date('d F Y', strtotime($event->event_date))  }}, at {{ $event->event_time }}</small>
            <h3><a class="ellipsis-line-2" href="{{ route('single.event',[ 'id' => $event->id,'city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug,'category_slug'=>$catslug,'event_slug'=>$event->event_slug]) }}">{{ ellipsis($event->title, 100) }}</a></h3>
            <div class="organiser d-flex mt-3 align-items-center">
                <a href="#"><img src="{{get_user_image($event->getUser->photo, 'optimized')}}" width="35" class="user-round" alt=""></a>
                <div class="ognr-info ms-2">
                    <h6 class="m-0"><a href="#">{{ $event->getUser->name }}</a></h6>
                    <small class="mute">{{ $event->location }}</small>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                @if(auth()->user())
                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('event.going',$event->id); ?>')" class="btn btn-primary @if (in_array(auth()->user()->id, json_decode($event->going_users_id))) displaynone @endif" id="goingId{{ $event->id }}"> {{get_phrase('Going')}}</a>
                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('event.notgoing',$event->id); ?>')" class="btn btn-secondary @if (!in_array(auth()->user()->id, json_decode($event->going_users_id))) displaynone @endif" id="notGoingId{{ $event->id }}"> {{get_phrase('Cancel')}}</a>

                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('event.interested',$event->id); ?>')" class="btn btn-primary @if (in_array(auth()->user()->id, json_decode($event->interested_users_id))) displaynone @endif" id="interestedId{{ $event->id }}"> {{get_phrase('Interested')}}</a>
                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('event.notinterested',$event->id); ?>')" class="btn btn-secondary @if (!in_array(auth()->user()->id, json_decode($event->interested_users_id))) displaynone @endif" id="notInterestedId{{ $event->id }}"> {{get_phrase('Not Interested')}}</a>
                @endif
                
                <div class="post-controls dropdown">
                    <div class="dropdown">
                        <button class="btn btn-secondary" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis"></i> 
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                             @if(auth()->user())
                            @if ($event->user_id==auth()->user()->id || auth()->user()->user_role=="admin")
                            <li>
                            <a href="{{ route('events.edit',['id'=>$event->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit me-1"></i> {{ get_phrase("Edit Event") }}</a>
                                <!-- <button onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.events.edit_event', 'event_id' => $event->id] )}}', '{{get_phrase('Edit Event')}}');" class="dropdown-item btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#createEvent"><i class="fa fa-edit me-1"></i> {{get_phrase('Edit Event')}}</button> -->
                            </li>
                            <li>
                                <a href="javascript:void(0)" onclick="confirmAction('<?php echo route('event.delete', ['event_id' => $event->id]); ?>', true)" class="dropdown-item btn btn-primary btn-sm"><i class="fa fa-trash me-1"></i> {{get_phrase('Delete Event')}}</a>
                            </li>
                            @endif
                            @if ($postId!=0)
                            <li>
                                <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.main_content.share_post_modal', 'post_id' => $postId] )}}', '{{get_phrase('Share Event')}}');" class="dropdown-item "><i class="fa fa-share me-1"></i> {{get_phrase('Share Event')}}</a>
                            </li>
                            @endif
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if (isset($search)&&!empty($search))
    @if ($key==2)
    @break
    @endif
@endif

@endforeach
