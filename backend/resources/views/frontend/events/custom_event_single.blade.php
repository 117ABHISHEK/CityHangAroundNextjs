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
         
         $item_categories = DB::table('event_category')
         ->where('event_id', $event->id)
         ->get();
 
         
         $item_count=count($item_categories);
         $categoriesss = DB::table('eventcategories')
             ->where('id', $item_categories[$item_count-1]->category_id)
             ->get();
                                 
         $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 
    ?>
<div class="col-lg-6 col-xl-4 col-md-4 col-sm-6 single-item-countable" id="event-{{ $event->id }}">
            
    <div class="card event-card p-2">
        <a href="{{ route('single.event',['city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug,'category_slug'=>$catslug,'event_slug'=>$event->event_slug]) }}">
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
            <h3><a class="ellipsis-line-2" href="{{ route('single.event',['city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug,'category_slug'=>$catslug,'event_slug'=>$event->event_slug]) }}">{{ ellipsis($event->title, 100) }}</a></h3>
            <div class="organiser d-flex mt-3 align-items-center">
                <a href="#"><img src="{{get_user_image($event->userphoto, 'optimized')}}" width="35" class="user-round" alt=""></a>
                <div class="ognr-info ms-2">
                    <h6 class="m-0"><a href="#">{{ $event->username }}</a></h6>
                    <small class="mute">{{ $event->location }}</small>
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
