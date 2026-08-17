@php 

    $media_files = \App\Models\Media_files::where('user_id', Auth()->user()->id)
    ->whereNull('story_id')
    ->whereNull('product_id')
    ->whereNull('page_id')
    ->whereNull('group_id')
    ->whereNull('chat_id')->take(9)->orderBy('id', 'desc')->get(); 

@endphp


<aside class="sidebar plain-sidebar">
    <div class="widget intro-widget">
        <h4>{{get_phrase('Intro')}}</h4>

        <div class="my-about mb-3">
            @php echo script_checker($user_info->about) @endphp
        </div>
        @if (isset($type)&&$type=="my_account")
        <button onclick="toggleBio(this, '.edit-bio-form')" class="edit-bio-btn btn btn-primary w-100">{{get_phrase('Edit Bio')}}</button>
        @endif

        <form class="ajaxForm d-hidden edit-bio-form" action="{{route('profile.about', ['action_type' => 'update'])}}" method="post">
            @CSRF
            <div class="mb-3">
            <textarea name="about" class="form-control" id="about" maxlength="250" oninput="updateCharCount()">{{$user_info->about}}</textarea>
            <small id="charCount">250 characters remaining</small>
            </div>
            <div class="mb-3">
                    <button type="submit" class="btn btn-primary w-100">{{get_phrase('Save Bio')}}</button>
            </div>
        </form>
    </div>
    
    <!-- Followers Widget -->
    <div class="widget followers-widget">
        <div class="widget-header mb-3 d-flex justify-content-between align-items-center">
            <h4 class="widget-title">{{get_phrase('Followers')}}</h4>
            <span>
                @php
                    $followers_count = \App\Models\Follower::where('follow_id', auth()->user()->id)->count();
                @endphp
                {{ $followers_count }} {{get_phrase('Followers')}}
            </span>
        </div>
        
        <div class="row row-cols-3 g-1 mt-3">
            @php
                $followers = \App\Models\Follower::where('follow_id', auth()->user()->id)
                    ->with('follower')
                    ->take(6)
                    ->get();
            @endphp
            
            @foreach($followers as $follower)
                @php $follower_user = \App\Models\User::find($follower->user_id); @endphp
                @if($follower_user)
                <div class="col">
                    <a href="{{ route('user.profile.view', $follower_user->id) }}" class="follower d-block">
                        <img width="100%" src="{{get_user_image($follower_user->photo, 'optimized')}}" alt="">
                        <h6 class="small">{{$follower_user->name}}</h6>
                    </a>
                </div>
                @endif
            @endforeach
        </div>
        
        @if($followers_count > 6)
        <a href="{{route('profile.friends')}}" class="btn btn-primary mt-3 d-block mx-auto">{{get_phrase('See All')}}</a>
        @endif
    </div>
    
    <div class="widget" id="my-profile-info">
        @include('frontend.profile.my_info')
    </div>
    <div class="widget">
        <h4 class="widget-title">{{get_phrase('Photo')}}/{{get_phrase('Video')}}</h4>
        <div id="sidebarPhotoAndVideos" class="row row-cols-3 row-cols-md-5 row-cols-lg-2 row-cols-xl-3 g-1 mt-3">
            @include('frontend.profile.sidebar_photos_and_videos')
        </div>
        <a href="{{route('profile.photos')}}" class="btn btn-primary mt-3 d-block mx-auto">{{get_phrase('See More')}}</a>
    </div>
    <!--  Widget End -->
    <div class="widget friend-widget">
        @php
            $friends = DB::table('friendships')->where(function ($query) {
            $query->where('accepter', Auth()->user()->id)
                ->orWhere('requester', Auth()->user()->id);
            })
            ->where('is_accepted', 1)
            ->orderBy('friendships.importance', 'desc');
        @endphp
        <div
            class="widget-header mb-3 d-flex justify-content-between align-items-center">
            <h4 class="widget-title">{{get_phrase('Following')}}</h4>
            <span>{{$friends->get()->count()}} {{get_phrase('Following')}}</span>
        </div>

        <div class="row row-cols-3 g-1 mt-3">
            @foreach($friends->take(6)->get() as $friend)
                @if($friend->requester == Auth()->user()->id)
                    @php $friends_user_data = DB::table('users')->where('id', $friend->accepter)->first(); @endphp
                @else
                    @php $friends_user_data = DB::table('users')->where('id', $friend->requester)->first(); @endphp
                @endif
                <div class="col">
                    <a href="{{ route('user.profile.view',$friends_user_data->id) }}" class="friend d-block">
                        <img width="100%" src="{{get_user_image($friends_user_data->photo, 'optimized')}}" alt="">
                        <h6 class="small">{{$friends_user_data->name}}</h6>
                    </a>
                </div>
            @endforeach
        </div>
        <a href="{{route('profile.friends')}}" class="btn btn-primary mt-3 d-block mx-auto">{{get_phrase('See All')}}</a href="{{route('profile.friends')}}">

        <a href="javascript:void(0);" 
                onclick="openReportModal({{ $user_info->id }}, '{{ $user_info->name }}')" 
                class="btn btn-primary mt-3 d-block mx-auto">
                    {{ get_phrase('Report') }}
                </a>
    </div>
    <!--  Widget End -->
    
    <!--  Widget End -->
</aside>
<!--  Sidebar End -->

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
                        <label for="group_name" class="form-label">Profile Name</label>
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
        $('#type').val('profile');
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

<script>
function updateCharCount() {
    let textarea = document.getElementById("about");
    let charCount = document.getElementById("charCount");
    let remaining = 250 - textarea.value.length;
    charCount.textContent = remaining + " characters remaining";
}
</script>