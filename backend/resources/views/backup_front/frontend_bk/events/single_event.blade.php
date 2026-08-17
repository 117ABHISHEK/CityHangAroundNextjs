<style>
    .avatar-details {
        position: relative;
        color: white;
    }

    .mobile-number {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.7); /* dark background for visibility */
        color: #fff;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
        z-index: 10;
        text-decoration: none;
        transition: background 0.3s ease;
    }

    .mobile-number:hover {
        background: rgba(0, 0, 0, 0.9);
        text-decoration: underline;
    }
</style>   
<!-- Content Section Start -->
<div class="single-event-wrap">
<div class="col-md-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-white pl-0 pr-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('pages') }}">
                                        <i class="fas fa-bars"></i>
                                       Home
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('event') }}">All Categories</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('event.category.city',['category_slug'=>$category->category_slug,'city_slug'=>$city->city_slug]) }}">{{$city->city_name}}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('event.city.area',['city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug]) }}">{{$area->area_name}}</a></li>


                                @foreach($parent_categories as $key => $parent_category)
                                    <li class="breadcrumb-item"><a href="{{ route('event.category.city.area', ['city_slug'=>$city->city_slug,'category_slug'=>$category->category_slug, 'area_slug'=>$area->area_slug]) }}">{{ $parent_category->category_name }}</a></li>
                                @endforeach
                                <li class="breadcrumb-item"><a href="{{ route('event.category.city.area', ['city_slug'=>$city->city_slug,'category_slug'=>$category->category_slug, 'area_slug'=>$area->area_slug]) }}">{{ $category->category_name }}</a></li>
                            </ol>
                        </nav>
                    </div>
        <div class="event-image event-cover">
            <img class="w-100" src="{{ viewImage('event',$event->banner,'coverphoto') }}" class="img-fluid" alt="Event">
            <div class="event-date">
                <span>{{ date('d M', strtotime($event->event_date))  }}</span>
            </div>

            <div class="mobile-number">
                <span>{{ count(json_decode($events->view ?? '[]')) }} {{ get_phrase('Views') }}</span>

            </div>
        </div>
        <div class="row gx-3 mt-3">
            <div class="col-lg-7 col-sm-12">
                <div class="card rounded p-3">
                    @php  $postOfThisEvent = \App\Models\Posts::where('publisher','event')->where('publisher_id',$event->id)->first();@endphp
                    <div class="post-controls dropdown dotted">
                        <a class="nav-link dropdown-toggle ms-auto text-end m-0 p-0 w-25" href="#" id="navbarDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @if($postOfThisEvent != null)
                                <li>
                                    <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.main_content.share_post_modal', 'post_id' => $postOfThisEvent->post_id] )}}', '{{get_phrase('Share Event')}}');" class="dropdown-item "> {{get_phrase('Share')}}</a>
                                </li>
                            @else
                                <li>
                                    <a href="#" class="dropdown-item "> {{get_phrase('Create post to share')}}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    
                    <span class="text-primary">{{ date('l', strtotime($event->event_date)) }}, {{ date('d F Y', strtotime($event->event_date))  }}, at {{ $event->event_time }}</span>
                    <h2 class="h5"> {{$event->title}}</h2>
                    <span>{{ $event->location }}</span>
                </div> <!-- Card End -->
                <!-- Profile Nav End -->
                <div class="event-tab ct-tab bg-white p-3 border rounded mt-3">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="about-tab" data-bs-toggle="tab"
                                data-bs-target="#about" type="button" role="tab" aria-controls="about"
                                aria-selected="true">{{ get_phrase('About') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="discussion-tab" data-bs-toggle="tab"
                                data-bs-target="#discussion" type="button" role="tab"
                                aria-controls="discussion" aria-selected="false">{{ get_phrase('Discussion') }}</button>
                        </li>
                    </ul>
                </div> <!-- Friends Tab End -->
                <div class="tab-content card rounded p-3 mt-3" id="myTabContent">
                    <div class="tab-pane fade show active" id="about" role="tabpanel"
                        aria-labelledby="about-tab">
                        <h2 class="h6">{{ get_phrase('Details') }}</h2>
                        <p>
                            @php echo script_checker($event->description, false); @endphp
                        </p>
                    </div> <!-- Tab Pane End -->

                    

                    <div class="tab-pane fade" id="discussion" role="tabpanel"  aria-labelledby="discussion-tab">
                        {{--  include the post feature   --}}
                        @include('frontend.main_content.create_post', ['event_id' => $event->id])

                        <div class="discuss-wrap">
                            <h3 class="h6 my-3">Recent Activity</h3>
                            @include('frontend.main_content.posts',['type'=>'user_post'])
                        </div>
                    </div><!-- Tab Pane End -->
                </div> <!-- Tab Content End -->
            </div>
            <div class="col-lg-5 col-sm-12">
                <aside class="sidebar plain-sidebar">
                    

                      @if(auth()->user())
                    <div class="widget p-3">
                        <div class="justify-content-between">
                           
                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('event.going',$event->id); ?>')" class="w-100 mb-2 btn btn-primary @if (in_array(auth()->user()->id, json_decode($event->going_users_id))) displaynone @endif" id="goingId{{ $event->id }}"> {{get_phrase('Going')}}</a>
                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('event.notgoing',$event->id); ?>')" class="w-100 mb-2 btn btn-secondary @if (!in_array(auth()->user()->id, json_decode($event->going_users_id))) displaynone @endif" id="notGoingId{{ $event->id }}"> {{get_phrase('Cancel')}}</a>

                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('event.interested',$event->id); ?>')" class="w-100 mb-2 btn btn-primary @if (in_array(auth()->user()->id, json_decode($event->interested_users_id))) displaynone @endif" id="interestedId{{ $event->id }}"> {{get_phrase('Interested')}}</a>
                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('event.notinterested',$event->id); ?>')" class="w-100 mb-2 btn btn-secondary @if (!in_array(auth()->user()->id, json_decode($event->interested_users_id))) displaynone @endif" id="notInterestedId{{ $event->id }}"> {{get_phrase('Not Interested')}}</a>

                            <a href="javascript:void(0)" onclick="openReportModal({{ $event->id }}, '{{ $event->title }}')"  class="w-100 mb-2 btn btn-primary @if (in_array(auth()->user()->id, json_decode($event->going_users_id))) displaynone @endif" id="goingId{{ $event->id }}"> {{get_phrase('Report')}}</a>
                          
                        </div>
                    </div> <!-- Widget End -->
                    <div class="widget">
                        <div class="d-flex justify-content-between">
                            <h3 class="widget-title">{{ get_phrase('Guests')}}</h3>
                            <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.events.view-all', 'event_id' => $event->id])}}', '{{get_phrase('All Going And Interested User')}}');" data-bs-toggle="modal"
                                data-bs-target="#viewAll" class="fw-bold text-primary">{{get_phrase('View All')}}</a>
                        </div>
                        <div class="d-flex justify-content-between my-3">
                            <div class="going">
                                @php
                                    $directly_going_data = json_decode($event->going_users_id)!=null ? count(json_decode($event->going_users_id)) : "0";
                                    $invite_going_data = $invited_friend_going;
                                    $total = $directly_going_data + $invite_going_data;
                                @endphp
                                <span class="rounded-2">{{ $total }} </span>
                                Going
                            </div>
                            <div class="going">
                                <span class="rounded-2">{{ json_decode($event->interested_users_id)!=null ? count(json_decode($event->interested_users_id)) : "0" }}</span>
                                Interested
                            </div>
                        </div>
                    </div> <!-- Widget End -->
                    <div class="widget">
                        <h3 class="widget-title">{{ get_phrase('Go With Following') }}</h3>
                        <div class="gr-search">
                            <form action="#">
                                <input type="text" class="bg-secondary rounded" id="myInputSearch" onkeyup="mySearchFunction()" placeholder="Search">
                                <span class="i fa fa-search"></span>
                            </form>
                        </div>
                        
                        <div class="invite-wrap overflow-auto mt-3">
                            <table id="myTable" class="w-100">
                                <tbody class="searchTbody">
                                    @foreach ($friends as $friend )

                                    {{--  asiging user as requester or getting request as friend whos are inviteable --}}
                                    @php $invited_friend_id = $friend->requester==auth()->user()->id ? $friend->accepter:$friend->requester; @endphp

                                    {{--  getiing user data for view   --}}
                                    @php  $inviteablefrienddetails= DB::table('users')->where('id', $invited_friend_id)->first(); @endphp
                                    {{--  chekcing invite is already done or not   --}}
                                    @php  $invite_details= DB::table('invites')->where('invite_reciver_id', $invited_friend_id)->where('event_id', $event->id)->first(); @endphp
                                    @if($inviteablefrienddetails)
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between s-invite">
                                                    <div class="ava-img d-flex align-items-center">
                                                        <a href="{{route('user.profile.view', $inviteablefrienddetails->id)}}"><img width="40" class="user-round" src="{{get_user_image($inviteablefrienddetails->photo, 'optimized')}}" alt=""></a>
                                                        <h3 class="h6 mb-0"><a href="{{route('user.profile.view', $inviteablefrienddetails->id)}}">{{  ellipsis( $inviteablefrienddetails->name,20 )   }}</a></h3>
                                                    </div>
                                                    <div class="invite_button_css">
                                                        @if (!empty($invite_details) && $invite_details->invite_reciver_id == $invited_friend_id && $invite_details->is_accepted != '1')
                                                            <button class="btn px-1 py-0 me-1 text-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ get_phrase('Invited') }}"> <span class="fas fa-check"></span></button>
                                                        @elseif (!empty($invite_details) && $invite_details->invite_reciver_id == $invited_friend_id && $invite_details->is_accepted == '1' )
                                                            <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ get_phrase('Going') }}" class="btn px-1 py-0 me-1 text-success"> <i class="far fa-calendar-check"></i> </button>
                                                        @else
                                                            <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ get_phrase('Send invitations') }}" href="javascript:void(0)" onclick="ajaxAction('<?php echo route('event.invite', ['invited_friend_id' => $invited_friend_id, 'requester_id' => auth()->user()->id, 'event_id' => $event->id]); ?>')" class="btn px-1 py-0 me-1"><i class="fas fa-location-arrow"></i></a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div><!-- Widget End -->
  @endif
                    <div class="widget p-event-widget">
    <h3 class="widget-title mb-3">{{ get_phrase('Popular Events') }}</h3>
    @php $index = 1; @endphp
    @foreach ($popularevents as $key => $popularevent)
        <?php
        $city = DB::table('cities')
            ->where('id', $popularevent['city_id'])
            ->first();

        $area = DB::table('areas')
            ->where('city_id', $popularevent['city_id'])
            ->where('id', $popularevent['area_id'])
            ->first();

        $item_categories = DB::table('event_category')
            ->where('event_id', $popularevent['id']) // Use $popularevent['id'] instead of $event->id
            ->get();

        $item_count = count($item_categories);

        if ($item_count > 0) {
            $last_category_id = $item_categories[$item_count - 1]->category_id;

            $categoriesss = DB::table('eventcategories')
                ->where('id', $last_category_id)
                ->get();

            $catslug = count($categoriesss) > 0 ? $categoriesss[0]->category_slug : null;
        } else {
            $catslug = null;
        }
        ?>

        @if($catslug)
            <div class="popular-event">
                <div class="p-2 border rounded-3">
                    <img class="img-fluid w-100" src="{{ viewImage('event', $popularevent['banner'], 'thumbnail') }}" alt="">
                    <div class="pp-info">
                        <h6><a href="">{{ $categoriesss[0]->category_name }}</a></h6>
                        <span class="text-primary">{{ date('l', strtotime($popularevent['event_date'])) }}, {{ date('d F Y', strtotime($popularevent['event_date'])) }}</span>
                        <h6>
                            <a href="{{ route('single.event', ['city_slug' => $city->city_slug, 'area_slug' => $area->area_slug, 'category_slug' => $catslug, 'event_slug' => $popularevent['event_slug']]) }}">
                                {{ ellipsis($popularevent['title'], 50) }}
                            </a>
                        </h6>
                        <div class="d-flex mt-2">
                            <a href="{{ route('user.profile.view', $popularevent['user_id']) }}">
                                <img src="{{ get_user_image($popularevent['photo'], 'optimized') }}" width="30" class="cicle user-round" alt="">
                            </a>
                            <div class="ava-info ms-2">
                                <h3 class="h6 mb-0"><a href="#">{{ $popularevent['post_user'] }}</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- Event Widget End -->
        @endif

        @php
            if ($index == 5) {
                break;
            } else {
                $index++;
            }
        @endphp

    @endforeach
</div><!-- Widget End -->

                </aside>
            </div>
        </div>
    </div>



<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Report Issue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reportForm" action="{{ route('report.group') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Hidden Fields -->
                    <input type="hidden" id="type" name="type">
                    <input type="hidden" id="entity_id" name="entity_id">

                    <!-- Group Name (Pre-filled) -->
                    <div class="mb-3">
                        <label for="group_name" class="form-label">Event Name</label>
                        <input type="text" class="form-control" id="group_name" name="group_name"  readonly>
                    </div>

                    <!-- Full Name -->
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name (Optional)</label>
                        <input type="text" class="form-control" id="full_name" name="full_name">
                    </div>

                    <!-- Email Address (Required) -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number (Optional)</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>

                    <!-- Reason for Reporting -->
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Reporting *</label>
                        <select class="form-select" id="reason" name="reason" required>
                            <option value="">Select Reason</option>
                            <option value="Spam">Spam</option>
                            <option value="Inappropriate Content">Inappropriate Content</option>
                            <option value="Harassment/Bullying">Harassment/Bullying</option>
                            <option value="Fake Group">Fake Group</option>
                            <option value="Other">Other (Specify Below)</option>
                        </select>
                    </div>

                    <!-- Additional Comments -->
                    <div class="mb-3">
                        <label for="additional_comments" class="form-label">Additional Comments (Optional)</label>
                        <textarea class="form-control border" id="additional_comments" name="additional_comments" rows="3"></textarea>
                    </div>

                    <!-- File Upload (Proof) -->
                    <div class="mb-3">
                        <label for="proof_attachment" class="form-label">Attach Proof (Optional)</label>
                        <input type="file" class="form-control" id="proof_attachment" name="proof_attachment" accept="image/*, .pdf, .docx">
                    </div>

                    <!-- Would You Like a Response? -->
                    <div class="mb-3">
                        <label class="form-label">Would you like a response?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="response_required" id="response_yes" value="Yes" checked>
                            <label class="form-check-label" for="response_yes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="response_required" id="response_no" value="No">
                            <label class="form-check-label" for="response_no">No</label>
                        </div>
                    </div>

                    <!-- CAPTCHA Verification -->
                    <div class="mb-3">
                        <label class="form-label">CAPTCHA Verification *</label>
                        <div class="g-recaptcha" data-sitekey="YOUR_SITE_KEY"></div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-danger w-100">Submit Report</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openReportModal(groupId, groupName) {
        $('#entity_id').val(groupId);
        $('#group_name').val(groupName);
        $('#type').val('event');
        $('#reportModal').modal('show');
    }

    function toggleOtherReason() {
        if ($('#reason').val() === 'Other') {
            $('#otherReasonDiv').show();
        } else {
            $('#otherReasonDiv').hide();
            $('#other_reason').val('');
        }
    }

//     $('#reportForm').submit(function (e) {
//     e.preventDefault();

//     let formData = new FormData(this); // ✅ FormData supports file uploads

//     $.ajax({
//         url: "{{ route('report.group') }}",
//         method: "POST",
//         data: formData,
//         processData: false,  // ✅ Prevent jQuery from converting FormData into a string
//         contentType: false,  // ✅ Ensure correct content type for file upload
//         success: function (response) {
//             alert(response.message);
//             $('#reportModal').modal('hide');
//             $('#reportForm')[0].reset();
//         },
//         error: function (xhr) {
//             let errors = xhr.responseJSON.errors;
//             let errorMessage = "Error: " + xhr.responseJSON.message + "\n";
            
//             if (errors) {
//                 $.each(errors, function (key, value) {
//                     errorMessage += value + "\n"; // Append validation errors
//                 });
//             }

//             alert(errorMessage);
//         }
//     });
// });

</script>
