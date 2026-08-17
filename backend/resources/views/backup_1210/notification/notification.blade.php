<div class="all-notify-control header-controls bg-white shadow-sm">
    <div class="notify-control">
        <div class="notify-inner shadow-sm">
            <h3 class="h4 py-4 border-bottom ">{{ get_phrase('Notifications') }}</h3>
            <div class="new-notif mt-4 pb-1">
                <h4 class="notify-title">{{get_phrase('New')}} <span>{{ count($new_notification) }}</span></h4>
                <ul>
                    @foreach ($new_notification as $newNotification )
                        @if ($newNotification->type=="event")
                        <?php


       $events= DB::table('events')
         ->where('id', $newNotification->event_id)
         ->first();

                 
                    //print_r($events);exit;

         $city= DB::table('cities')
         ->where('id', $events->city_id)
         ->first();

         $area= DB::table('areas')
         ->where('city_id', $events->city_id)
         ->where('id', $events->area_id)
         ->first();
         
         $item_categories = DB::table('event_category')
         ->where('event_id', $events->id)
         ->get();
 
         
         $item_count=count($item_categories);
         $categoriesss = DB::table('eventcategories')
             ->where('id', $item_categories[$item_count-1]->category_id)
             ->get();
                                 
         $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 
    ?>
                        <li class="notify-item eventn-item">
                            <div class="avatar-details">
                                <div class="d-flex">
                                    <div class="noti-avata me-3"><img width="48" class="rounded-circle" src="{{ get_user_image($newNotification->getUserData->id,"optimized") }}"alt="">
                                        <span class="notify-type"><i
                                                class="fa fa-calendar"></i></span>
                                    </div>
                                    <div class="noti-details">
                                        <h4><a href="{{ route('user.profile.view',$newNotification->getUserData->id) }}">{{ ucfirst($newNotification->getUserData->name) }}</a><span> {{ get_phrase('invited you to like') }} </span> <a href="{{ route('single.event',['city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug,'category_slug'=>$catslug,'event_slug'=>$events->event_slug])}} </a>   </h4>
                                        @if ($newNotification->status=='0')
                                        <div class="btn-inline">
                                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('accept.event.request.from.notification',['id'=>$newNotification->sender_user_id,'event_id'=>$newNotification->getEventData->id]); ?>')" class="btn btn-primary">{{ get_phrase('Accept') }}</a>
                                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('decline.event.request.from.notification',['id'=>$newNotification->sender_user_id,'event_id'=>$newNotification->getEventData->id]); ?>')" class="btn btn-danger">{{ get_phrase('Decline') }}</a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <span class="noti-time">{{ $newNotification->created_at->timezone(Auth::user()->timezone)->diffForHumans() }}</span>
                        </li>
                        @elseif ($newNotification->type=="group")
                        <li class="notify-item">
                            <div class="avatar-details">
                                <div class="d-flex">
                                    <div class="noti-avata me-3"><img width="48" class="rounded-circle" src="{{ get_user_image($newNotification->getUserData->id,"optimized") }}" alt="">
                                        <span class="notify-type"><i
                                                class="fa fa-users"></i></span>
                                    </div>
                                    <div class="noti-details">
                                        <h4><a href="{{ route('user.profile.view',$newNotification->getUserData->id) }}">{{ ucfirst($newNotification->getUserData->name) }}</a> <span>{{ get_phrase('invited you to like') }} </span> {{ ucfirst($newNotification->getGroupData->title) }}</h4>
                                        @if ($newNotification->status=='0')
                                        <div class="btn-inline">
                                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('accept.group.request.from.notification',['id'=>$newNotification->sender_user_id,'group_id'=>$newNotification->getGroupData->id]); ?>')" class="btn btn-primary">{{ get_phrase('Accept') }}</a>
                                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('decline.group.request.from.notification',['id'=>$newNotification->sender_user_id,'group_id'=>$newNotification->getGroupData->id]); ?>')" class="btn btn-danger">{{ get_phrase('Decline') }}</a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <span class="noti-time">{{ $newNotification->created_at->timezone(Auth::user()->timezone)->diffForHumans() }}</span>
                        </li>
                        @elseif ($newNotification->type=="profile")
                        <li class="notify-item friendr-item">
                            <div class="avatar-details">
                                <div class="d-flex">
                                    <div class="noti-avata me-3"><img width="48" class="rounded-circle" src="{{ get_user_image($newNotification->getUserData->id,"optimized") }}" alt="">
                                        <span class="notify-type"><i
                                                class="fa fa-user"></i></span>
                                    </div>
                                    <div class="noti-details">
                                        <h4> <a href="{{ route('user.profile.view',$newNotification->getUserData->id) }}">{{ ucfirst($newNotification->getUserData->name) }}</a> <span>{{ get_phrase('sent you Follow Request') }}</h4>
                                        @if ($newNotification->status=='0')
                                        <div class="btn-inline">
                                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('accept.friend.request.from.notification',$newNotification->sender_user_id); ?>')" class="btn btn-primary">{{ get_phrase('Accept') }}</a>
                                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('decline.friend.request.from.notification',$newNotification->sender_user_id); ?>')" class="btn btn-danger">{{ get_phrase('Decline') }}</a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <span class="noti-time">{{ $newNotification->created_at->timezone(Auth::user()->timezone)->diffForHumans() }}</span>
                        </li>
                        @elseif ($newNotification->type=="friend_request_accept")
                        <li class="notify-item friendr-item">
                            <div class="avatar-details">
                                <div class="d-flex">
                                    <div class="noti-avata me-3"><img width="48" class="rounded-circle" src="{{ get_user_image($newNotification->getUserData->id,"optimized") }}" alt="">
                                        <span class="notify-type"><i
                                                class="fa fa-user"></i></span>
                                    </div>
                                    <div class="noti-details">
                                        <h4><a href="{{ route('user.profile.view',$newNotification->getUserData->id) }}">{{ ucfirst($newNotification->getUserData->name) }}</a><span> {{ get_phrase('accepted
                                            Your Follow Request') }} </h4>

                                        @if ($newNotification->status=='0')
                                            <div class="btn-inline">
                                                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('mark.as.read.notification',$newNotification->id); ?>')" class="btn btn-primary">{{ get_phrase('Mark As Read') }}</a>
                                            </div>
                                        @endif
                                       
                                    </div>
                                </div>
                            </div>
                            <span class="noti-time">{{ $newNotification->created_at->timezone(Auth::user()->timezone)->diffForHumans() }}</span>
                        </li>
                        @elseif ($newNotification->type=="group_invitation_accept")
                        <li class="notify-item friendr-item">
                            <div class="avatar-details">
                                <div class="d-flex">
                                    <div class="noti-avata me-3"><img width="48" class="rounded-circle" src="{{ get_user_image($newNotification->getUserData->id,"optimized") }}" alt="">
                                        <span class="notify-type"><i
                                                class="fa fa-user"></i></span>
                                    </div>
                                    <div class="noti-details">
                                        <h4><a href="{{ route('user.profile.view',$newNotification->getUserData->id) }}">{{ ucfirst($newNotification->getUserData->name) }}</a><span> {{ get_phrase('accepted
                                            Your Group Invitation Request') }} </h4>
                                        @if ($newNotification->status=='0')
                                            <div class="btn-inline">
                                                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('mark.as.read.notification',$newNotification->id); ?>')" class="btn btn-primary">{{ get_phrase('Mark As Read') }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <span class="noti-time">{{ $newNotification->created_at->timezone(Auth::user()->timezone)->diffForHumans() }}</span>
                        </li>
                        @elseif ($newNotification->type=="event_invitation_accept")
                        <li class="notify-item friendr-item">
                            <div class="avatar-details">
                                <div class="d-flex">
                                    <div class="noti-avata me-3"><img width="48" class="rounded-circle" src="{{ get_user_image($newNotification->getUserData->id,"optimized") }}" alt="">
                                        <span class="notify-type"><i
                                                class="fa fa-user"></i></span>
                                    </div>
                                    <div class="noti-details">
                                        <h4><a href="{{ route('user.profile.view',$newNotification->getUserData->id) }}">{{ ucfirst($newNotification->getUserData->name) }}</a><span> {{ get_phrase('accepted
                                            Your Event Invitation Request') }} </h4>
                                        @if ($newNotification->status=='0')
                                            <div class="btn-inline">
                                                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('mark.as.read.notification',$newNotification->id); ?>')" class="btn btn-primary">{{ get_phrase('Mark As Read') }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <span class="noti-time">{{ $newNotification->created_at->timezone(Auth::user()->timezone)->diffForHumans() }}</span>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="new-notif mt-4 pb-1">
                @if (count($older_notification)>0)
                    <h4 class="notify-title pb-1">Earlier <span>{{ count($older_notification) }}</span></h4>
                @endif
                
                <ul>
                    @foreach ($older_notification as $newNotification )
                    @if ($newNotification->type=="event")
                    <?php


       $events= DB::table('events')
         ->where('id', $newNotification->event_id)
         ->first();

                 
                    //print_r($events);exit;

         $city= DB::table('cities')
         ->where('id', $events->city_id)
         ->first();

         $area= DB::table('areas')
         ->where('city_id', $events->city_id)
         ->where('id', $events->area_id)
         ->first();
         
         $item_categories = DB::table('event_category')
         ->where('event_id', $events->id)
         ->get();
 
         
         $item_count=count($item_categories);
         $categoriesss = DB::table('eventcategories')
             ->where('id', $item_categories[$item_count-1]->category_id)
             ->get();
                                 
         $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 
    ?>
                        <li class="notify-item eventn-item">
                            <div class="avatar-details">
                                <div class="d-flex">
                                    <div class="noti-avata me-3"><img width="48" class="rounded-circle" src="{{ get_user_image($newNotification->getUserData->id,"optimized") }}"alt="">
                                        <span class="notify-type"><i
                                                class="fa fa-calendar"></i></span>
                                    </div>
                                    <div class="noti-details">
                                        <h4><a href="{{ route('user.profile.view',$newNotification->getUserData->id) }}">{{ ucfirst($newNotification->getUserData->name) }}</a><span> {{ get_phrase('invited you to like') }} </span> <a href="{{ route('single.event',['city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug,'category_slug'=>$catslug,'event_slug'=>$events->event_slug])}} </a>   </h4>
                                        @if ($newNotification->status=='0')
                                        <div class="btn-inline">
                                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('accept.event.request.from.notification',['id'=>$newNotification->sender_user_id,'event_id'=>$newNotification->getEventData->id]); ?>')" class="btn btn-primary">{{ get_phrase('Accept') }}</a>
                                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('decline.event.request.from.notification',['id'=>$newNotification->sender_user_id,'event_id'=>$newNotification->getEventData->id]); ?>')" class="btn btn-danger">{{ get_phrase('Decline') }}</a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <span class="noti-time">{{ $newNotification->created_at->timezone(Auth::user()->timezone)->diffForHumans() }}</span>
                        </li>
                        @elseif ($newNotification->type=="group")
                        <li class="notify-item">
                            <div class="avatar-details">
                                <div class="d-flex">
                                    <div class="noti-avata me-3"><img width="48" class="rounded-circle" src="{{ get_user_image($newNotification->getUserData->id,"optimized") }}" alt="">
                                        <span class="notify-type"><i
                                                class="fa fa-users"></i></span>
                                    </div>
                                    <div class="noti-details">
                                        <h4><a href="{{ route('user.profile.view',$newNotification->getUserData->id) }}">{{ ucfirst($newNotification->getUserData->name) }}</a> <span>{{ get_phrase('invited you to like') }} </span> {{ ucfirst($newNotification->getGroupData->title) }}</h4>
                                        @if ($newNotification->status=='0')
                                        <div class="btn-inline">
                                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('accept.group.request.from.notification',['id'=>$newNotification->sender_user_id,'group_id'=>$newNotification->getGroupData->id]); ?>')" class="btn btn-primary">{{ get_phrase('Accept') }}</a>
                                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('decline.group.request.from.notification',['id'=>$newNotification->sender_user_id,'group_id'=>$newNotification->getGroupData->id]); ?>')" class="btn btn-danger">{{ get_phrase('Decline') }}</a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <span class="noti-time">{{ $newNotification->created_at->timezone(Auth::user()->timezone)->diffForHumans() }}</span>
                        </li>
                        @elseif ($newNotification->type=="profile")
                        <li class="notify-item friendr-item">
                            <div class="avatar-details">
                                <div class="d-flex">
                                    <div class="noti-avata me-3"><img width="48" class="rounded-circle" src="{{ get_user_image($newNotification->getUserData->id,"optimized") }}" alt="">
                                        <span class="notify-type"><i
                                                class="fa fa-user"></i></span>
                                    </div>
                                    <div class="noti-details">
                                        <h4> <a href="{{ route('user.profile.view',$newNotification->getUserData->id) }}">{{ ucfirst($newNotification->getUserData->name) }}</a> <span>{{ get_phrase('sent you Follow Request') }}</h4>
                                        @if ($newNotification->status=='0')
                                        <div class="btn-inline">
                                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('accept.friend.request.from.notification',$newNotification->sender_user_id); ?>')" class="btn btn-primary">{{ get_phrase('Accept') }}</a>
                                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('decline.friend.request.from.notification',$newNotification->sender_user_id); ?>')" class="btn btn-danger">{{ get_phrase('Decline') }}</a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <span class="noti-time">{{ $newNotification->created_at->timezone(Auth::user()->timezone)->diffForHumans() }}</span>
                        </li>
                        @elseif ($newNotification->type=="friend_request_accept")
                        <li class="notify-item friendr-item">
                            <div class="avatar-details">
                                <div class="d-flex">
                                    <div class="noti-avata me-3"><img width="48" class="rounded-circle" src="{{ get_user_image($newNotification->getUserData->id,"optimized") }}" alt="">
                                        <span class="notify-type"><i
                                                class="fa fa-user"></i></span>
                                    </div>
                                    <div class="noti-details">
                                        <h4><a href="{{ route('user.profile.view',$newNotification->getUserData->id) }}">{{ ucfirst($newNotification->getUserData->name) }}</a><span> {{ get_phrase('accepted
                                            Your Follow Request') }} </h4>
                                       
                                    </div>
                                </div>
                            </div>
                            <span class="noti-time">{{ $newNotification->created_at->timezone(Auth::user()->timezone)->diffForHumans() }}</span>
                        </li>
                        @elseif ($newNotification->type=="group_invitation_accept")
                        <li class="notify-item friendr-item">
                            <div class="avatar-details">
                                <div class="d-flex">
                                    <div class="noti-avata me-3"><img width="48" class="rounded-circle" src="{{ get_user_image($newNotification->getUserData->id,"optimized") }}" alt="">
                                        <span class="notify-type"><i
                                                class="fa fa-user"></i></span>
                                    </div>
                                    <div class="noti-details">
                                        <h4><a href="{{ route('user.profile.view',$newNotification->getUserData->id) }}">{{ ucfirst($newNotification->getUserData->name) }}</a><span> {{ get_phrase('accepted
                                            Your Group Invitation Request') }} </h4>
                                       
                                    </div>
                                </div>
                            </div>
                            <span class="noti-time">{{ $newNotification->created_at->timezone(Auth::user()->timezone)->diffForHumans() }}</span>
                        </li>
                        @elseif ($newNotification->type=="event_invitation_accept")
                        <li class="notify-item friendr-item">
                            <div class="avatar-details">
                                <div class="d-flex">
                                    <div class="noti-avata me-3"><img width="48" class="rounded-circle" src="{{ get_user_image($newNotification->getUserData->id,"optimized") }}" alt="">
                                        <span class="notify-type"><i
                                                class="fa fa-user"></i></span>
                                    </div>
                                    <div class="noti-details">
                                        <h4><a href="{{ route('user.profile.view',$newNotification->getUserData->id) }}">{{ ucfirst($newNotification->getUserData->name) }}</a><span> {{ get_phrase('accepted
                                            Your Event Invitation Request') }} </h4>
                                       
                                    </div>
                                </div>
                            </div>
                            <span class="noti-time">{{ $newNotification->created_at->timezone(Auth::user()->timezone)->diffForHumans() }}</span>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div> <!-- Earlier widget End -->
           
        </div>
    </div>
</div>