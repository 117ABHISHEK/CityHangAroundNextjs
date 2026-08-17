<style>
    .profile-actions {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-shrink: 0;
    }

    .action-button {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 20px;
        border: none;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        background-color: #f0f0f0;
        color: #666;
    }

    .views-btn {
        background-color: #f0f0f0;
        color: #666;
    }

    .views-btn:hover {
        background-color: #e0e0e0;
        color: #333;
    }


    .chat-btn {
        background-color: #ff4b4b;
        ;
        color: white;
        border: none;
    }

    .chat-btn:hover {
        background-color: #ff4b4b;
        ;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px #ff4b4b;

    }

    .followers-btn {
        background-color: #f0f0f0;
        color: #666;
    }

    .followers-btn:hover {
        background-color: #e0e0e0;
        color: #333;
    }

    .action-button i {
        font-size: 16px;
    }

    .report-btn {
        background-color: #f0f0f0;
        color: #666;
    }

    .report-btn:hover {
        background-color: #e0e0e0;
        color: #333;
    }

    .claim-btn {
        background-color: #e7f1ff;
        color: #0d6efd;
    }

    .claim-btn:hover {
        background-color: #d0e3ff;
        color: #0a58ca;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.25);
    }

    .claim-btn-pending {
        background-color: #fff3cd;
        color: #856404;
        cursor: not-allowed;
        opacity: 0.7;
    }


    .avatar-details {
        position: relative;
        color: white;
    }


    .call-us {
        padding: 3px;
        margin: 0px;
        background-color: #f0f0f0;
        color: #666;
    }


    .call-us:hover {
        background-color: #e0e0e0;
        color: #333;
    }

    .chat-button {
        background: #FF4939;
        border: 1px solid white;
        color: rgb(17, 16, 16);
        border-radius: 40px;
        padding: 2px 6px;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 0 10px rgba(1, 1, 1, 0.4);
    }

    .chat-button:hover {
        background: #FF4939;
        box-shadow: 0 0 14px #FF4939;
    }

    .chat-button i {
        font-size: 14px;
    }
</style>
<div class="profile-cover rounded overflow-hidden shadow mb-4">

    @php
        $cat_name = $page->categories->last()->category_name ?? ($page->categories->first()->category_name ?? '');
        $banner_image = get_page_banner_image($page, 'coverphoto');
        $isPageOwner = auth()->check() && $page->user_id == auth()->id();
        $canReportPage = auth()->check() && !empty($page->id);
        $hasPendingClaim = false;
        if (auth()->check() && !$isPageOwner) {
            $hasPendingClaim = \App\Models\ClaimListing::where('page_id', $page->id)
                ->where('user_id', auth()->id())
                ->where('is_approved', 'N')
                ->exists();
        }
    @endphp

    {{-- Banner with background image and avatar --}}
    <div class="position-relative"
        style="height: 240px; background-image: url('{{ $banner_image }}'); background-position: center; background-size: cover; background-repeat: no-repeat;">


        {{-- Edit Cover Button --}}
        @if($isPageOwner)
            <button
                onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.pages.edit-cover-photo', 'page_id' => $page->id]) }}', '{{ get_phrase('Update your cover photo') }}');"
                class="edit-cover btn position-absolute top-0 end-0 m-3 z-2"
                style="background: rgba(0,0,0,0.5); color: #fff; border: none;">
                <i class="fa fa-camera"></i> {{ get_phrase('Edit Cover Photo') }}
            </button>
        @endif


        {{-- Logo Avatar --}}

    </div>

    {{-- Company Info Below Banner --}}
    <div class="bg-white px-4 py-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

            <!-- LEFT : LOGO + TITLE -->
            <div class="d-flex align-items-center gap-3">

                <!-- Logo -->
                <div class="rounded-circle border border-3 border-white shadow"
                    style="width:90px;height:90px;overflow:hidden;flex-shrink:0; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                    @php
                        // Profile Page Avatar: Strictly use Logo (or Default Category Icon like Burger)
                        $profile_image = get_page_logo($page->logo, 'logo', $page->categories);
                        $avatar_style = empty($page->logo) ? 'object-fit: contain;' : 'object-fit: cover;';
                    @endphp
                    <img src="{{ $profile_image }}" class="w-100 h-100" style="{{ $avatar_style }}"
                         onerror="this.onerror=null;this.src='{{ asset('storage/pages/logo/default.png') }}';">
                </div>

                <!-- Name + Address -->
                <div>
                    <h4 class="fw-bold mb-1 fs-4">{{ $page->title }}</h4>
                    @if($page->address)
                        <p class="text-muted small mb-0 fs-5">{{ $page->address }}</p>
                    @endif
                </div>
            </div>

            <!-- RIGHT : ACTION BUTTONS -->
            <div class="profile-actions">

                <!-- Views Button -->
                <div class="action-button views-btn">
                    <i class="fa fa-eye me-2"></i>
                    {{ is_array(json_decode($pages->view, true)) ? count(json_decode($pages->view, true)) : 0 }}
                    {{ get_phrase('Views') }}
                </div>


                <!-- Phone -->
                <!-- <div class="action-button call-us ">
            
                
            @if(!empty($page->item_phone))
                <a href="tel:{{ $page->item_phone }}"
                   class="btn   ">
                    <i class="fa fa-phone"></i>
                    {{ get_phrase('Call Now') }}
                </a>
            @endif
</div> -->
                <!-- Followers Button -->
                <div class="action-button followers-btn">
                    <i class="fa fa-users"></i>
                    @php
                        $page_followers_count = Cache::remember("page_followers_count_{$page->id}", 600, function() use ($page) {
                            return \App\Models\Follower::where('page_id', $page->id)->count();
                        });
                    @endphp
                    <span>{{ $page_followers_count }} {{ get_phrase('Followers') }}</span>
                </div>

                <!-- Chat with Us Button -->
                <button onclick="openChatWithUs()" class="action-button chat-btn">
                    <i class="fa fa-comments"></i>
                    <span>{{ get_phrase('Chat with us')}}</span>
                </button>

                <!-- Report Button -->
                @if($canReportPage)
                    <button onclick="openReportModal({{ $page->id }}, '{{ $page->title }}')"
                        class="action-button report-btn">
                        <i class="fa fa-flag" style="color:#ff4b4b;"></i>
                        <span>{{ get_phrase('Report')}}</span>
                    </button>
                @endif

                <!-- Claim Listing Button -->
                @if(auth()->check() && !$isPageOwner && !$hasPendingClaim)
                    <button data-bs-toggle="modal" data-bs-target="#claimListingModal"
                        class="action-button claim-btn">
                        <i class="fa fa-handshake"></i>
                        <span>{{ get_phrase('Claim Listing') }}</span>
                    </button>
                @elseif(auth()->check() && !$isPageOwner && $hasPendingClaim)
                    <button disabled class="action-button claim-btn-pending">
                        <i class="fa fa-clock"></i>
                        <span>{{ get_phrase('Claim Pending') }}</span>
                    </button>
                @endif

            </div>
        </div>
    </div>
</div>



<script>
    let reportModalInstance = null;

    function showLoginPrompt() {
        const modalHtml = `
            <div id="modalBackdrop" style="
                position: fixed;
                top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 9998;"></div>

            <div id="loginPromptModal" style="
                position: fixed;
                top: 50%; left: 50%;
                transform: translate(-50%, -50%);
                background: #fff;
                padding: 25px;
                width: 90%;
                max-width: 400px;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                z-index: 9999;
                font-family: sans-serif;
                text-align: center;
            ">
                <div style="margin-bottom: 20px;">
                    <i class="fa fa-comments" style="font-size: 28px; color: #ff4939;"></i>
                </div>

                <h4 style="margin-bottom: 10px;">Please log in to chat</h4>
                <p style="margin-bottom: 20px; color: #555;">Login or register to start a conversation.</p>

                <div style="margin-bottom: 15px;">
                    <a href="{{ route('login') }}" style="
                        display: inline-block;
                        background: #ff4939;
                        color: #fff;
                        padding: 10px 20px;
                        border-radius: 6px;
                        text-decoration: none;
                        margin-bottom: 10px;
                    ">Login</a>
                </div>

                <div style="margin-bottom: 15px;">
                    <a href="{{ route('register') }}" style="
                        display: inline-block;
                        background: #f0f0f0;
                        color: #333;
                        padding: 10px 20px;
                        border-radius: 6px;
                        text-decoration: none;
                        border: 1px solid #ccc;
                    ">Register</a>
                </div>

                <button onclick="closeLoginPrompt()" style="
                    background: none;
                    border: none;
                    color: #888;
                    text-decoration: underline;
                    font-size: 14px;
                    cursor: pointer;
                ">Cancel</button>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }

    function closeLoginPrompt() {
        const modal = document.getElementById('loginPromptModal');
        const backdrop = document.getElementById('modalBackdrop');
        if (modal) modal.remove();
        if (backdrop) backdrop.remove();
    }

    function openChatWithUs() {
        @if(auth()->check())
            window.location.href = "{{ route('chat.page', ['page' => $page->id]) }}";
        @else
            showLoginPrompt();
        @endif
    }
</script>

<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Report Issue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reportForm" action="{{ route('report.group') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Hidden Fields -->
                    <input type="hidden" id="type" name="type" value="page">
                    <input type="hidden" id="entity_id" name="entity_id">

                    <!-- Entity Name (Pre-filled) -->
                    <div class="mb-3">
                        <label for="group_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="group_name" name="group_name" readonly>
                    </div>

                    <!-- Full Name -->
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name (Optional)</label>
                        <input type="text" class="form-control" id="full_name" name="full_name"
                            value="{{ auth()->user()->name ?? '' }}">
                    </div>

                    <!-- Email Address (Required) -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ auth()->user()->email ?? '' }}" required>
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
                            <option value="Fake Content">Fake Content</option>
                            <option value="Other">Other (Specify Below)</option>
                        </select>
                    </div>

                    <!-- Additional Comments -->
                    <div class="mb-3">
                        <label for="additional_comments" class="form-label">Additional Comments (Optional)</label>
                        <textarea class="form-control border" id="additional_comments" name="additional_comments"
                            rows="3"></textarea>
                    </div>

                    <!-- File Upload (Proof) -->
                    <div class="mb-3">
                        <label for="proof_attachment" class="form-label">Attach Proof (Optional)</label>
                        <input type="file" class="form-control" id="proof_attachment" name="proof_attachment"
                            accept="image/*, .pdf, .docx">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn  w-100" style="background-color: #FF4939; color:#fff;">Submit
                        Report</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openReportModal(entityId, entityName, entityType = 'page') {
        document.getElementById('type').value = entityType;
        document.getElementById('entity_id').value = entityId;
        document.getElementById('group_name').value = entityName;

        if (!reportModalInstance) {
            const reportModalElement = document.getElementById('reportModal');
            reportModalInstance = bootstrap.Modal.getOrCreateInstance(reportModalElement);
        }

        requestAnimationFrame(() => {
            reportModalInstance.show();
        });
    }
</script>
