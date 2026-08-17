<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<style>
.btn-danger {
    background-color: #dc3545 !important; /* Bootstrap danger red */
    color: #fff !important; /* White text */
    border: none !important;
}
</style>   
<div class="col-lg-5">
    <aside class="sidebar group-sidebar plain-sidebar">
       
        <div class="widget intro-widget">
              @if(auth()->user())
            @php $join = \App\Models\Group_member::where('group_id',$group->id)->where('user_id',auth()->user()->id)->count(); @endphp
            @if ($join>0)
                @if ($group->user_id==auth()->user()->id)
                <a href="javascript:void(0)" class="btn btn-primary me-2 my-1"><i  class="fa-solid fa-users"></i> {{ get_phrase('Joined') }}</a>
                @else
                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('group.rjoin',$group->id); ?>')" class="btn btn-primary me-2 my-1"><i
                    class="fa-solid fa-users"></i>{{ get_phrase('Joined') }}</a>
                @endif
            @else
            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('group.join',$group->id); ?>')" class="btn btn-primary my-1">{{ get_phrase('Join') }}</a>
            @endif
            
            <a data-bs-toggle="modal" data-bs-target="#newGroup" href="#" class="btn btn-primary my-1"><i class="fa fa-circle-plus"></i> {{ get_phrase('Invite') }}</a>
            @endif
            
            <h3 class="widget-title mt-4">{{ get_phrase('About') }}</h3>
            <p class="text-center">@php echo script_checker($group->about, false); @endphp</p>
        </div>
       
        <div class="widget gw-info">
            <h3 class="widget-title mb-4">{{ get_phrase('Info') }}</h3>
            <ul>
                <li><i class="fa-solid fa-earth-americas"></i> <strong>{{ $group->privacy}} </strong></li>

                <li><i class="fa-solid fa-location-dot"></i>
                    <strong>{{ $group->location }}</strong>
                </li>

                <li><i class="fa-solid fa-users"></i><strong> {{ $group->group_type}}
                    </strong></li>
            </ul>
        </div>
        <div class="widget">
            
            <h4 class="widget-title">{{ get_phrase('Recent Media') }}</h4>
          
            <div class="row row-cols-3 g-1 mt-3">
                @foreach(\App\Models\Media_files::where('group_id', $group->id)
                    ->whereNull('album_id')
                    ->whereNull('product_id')
                    ->whereNull('page_id')
                    ->take(10)->orderBy('id', 'DESC')->get(); as $media_file)
                    @if($media_file->file_type == 'video')
                        <div class="single-item-countable col">
                            <video muted controlsList="nodownload" class="img-thumbnail w-100 user_info_custom_height">
                                <source src="{{get_post_video($media_file->file_name)}}" type="">
                            </video>
                        </div>
                    @else
                        <div class="single-item-countable col">
                            <img class="img-thumbnail w-100 user_info_custom_height" src="{{get_post_image($media_file->file_name, 'optimized')}}">
                        </div>
                    @endif
                @endforeach
            </div>

            <a href="{{ route('single.group.photos',$group->id) }}" class="btn btn-primary mt-3 d-block mx-auto">{{ get_phrase('See More') }}</a>
        </div><!--  Widget End -->
        <div class="widget friend-widget">
            <div
                class="widget-header mb-3 d-flex justify-content-between align-items-center">
                <h4 class="widget-title mb-0">{{ get_phrase('Recent Members') }}</h4>
            </div>
            <div class="row row-cols-3 g-1 mt-3">
            @foreach ( \App\Models\Group_member::where('group_id',$group->id)->where('is_accepted','1')->orderBy('id','DESC')->limit('8')->get(); as $key => $groupmember )
                <div class="col">
                    <a href="{{ route('user.profile.view',$groupmember->getUser->id) }}" class="friend d-block">
                        <img width="100%" class="rounded" src="{{ get_user_image($groupmember->getUser->photo,'optimized') }}" alt="">
                        <h6 class="small">{{ $groupmember->getUser->name }}</h6>
                    </a>
                </div>
            @endforeach
            </div>
            
            <a href="{{ route('all.people.group.view',$group->id) }}" class="btn btn-primary mt-3 d-block mx-auto">{{ get_phrase('See More') }}</a>
            <a href="javascript:void(0);" 
                onclick="openReportModal({{ $group->id }}, '{{ $group->title }}')" 
                class="btn btn-primary mt-3 d-block mx-auto">
                    {{ get_phrase('Report') }}
                </a>


        </div><!--  Widget End -->

    </aside>
</div> <!-- Group Sidebar End -->

<!-- Report Modal -->
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
                        <label for="group_name" class="form-label">Group Name</label>
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




@include('frontend.groups.invite')

<script>
    function openReportModal(groupId, groupName) {
        $('#entity_id').val(groupId);
        $('#group_name').val(groupName);
        $('#type').val('group');
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
