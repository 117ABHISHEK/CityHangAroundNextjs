<style>
    .avatar-details {
        position: relative;
        color: white;
    }

    .mobile-number {
        position: absolute;
        bottom: 3px;
        right: 5px;
         background: linear-gradient(135deg, #404040, #3f3f3f);
        box-shadow: 0 4px 10px rgba(0,0,0,0.25);
        color: #fff;
        padding: 6px 6px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
        z-index: 10;
        text-decoration: none;
        transition: background 1.5s ease;
    }

    .mobile-number:hover {
      transform: translateY(-2px);
      box-shadow: 5px 6px 14px rgb(224, 224, 224);
      
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
      background:#FF4939;
      box-shadow: 0 0 14px #FF4939;
    }

    .chat-button i {
      font-size: 14px;
    }
</style>
<div class="profile-cover rounded overflow-hidden shadow mb-4">

    {{-- Banner with background image and avatar --}}
   <div class="position-relative sdsd"
     style="height: 240px; 
            background: url('{{ !empty($page->coverphoto) 
                ? "https://cityhangaround.com/storage/pages/coverphoto/{$page->coverphoto}" 
                : "https://cityhangaround.com/storage/pages/coverphoto/default/default.jpg" }}') 
            center/cover no-repeat;">
</div>
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.5);"></div>

        {{-- Edit Cover Button --}}
        @if(auth()->user() && $page->user_id == auth()->user()->id)
            <button onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.pages.edit-cover-photo', 'page_id' => $page->id]) }}', '{{ get_phrase('Update your cover photo') }}');"
                class="edit-cover btn position-absolute top-0 end-0 m-3 z-2">
                <i class="fa fa-camera"></i> {{ get_phrase('Edit Cover Photo') }}
            </button>
        @endif

        {{-- Logo Avatar --}}
        <div class="position-absolute bottom-0 start-0 translate-middle-y ms-4 mb-n4" style="z-index: 1;">
            <div class="rounded-circle border border-3 border-white shadow" style="width: 100px; height: 100px; overflow: hidden;">
                <img src="{{ get_page_logo($page->logo, 'logo') }}" alt="Logo" class="img-fluid w-100 h-100 object-fit-cover">
            </div>
        </div>
    </div>

    {{-- Company Info Below Banner --}}
    <div class="bg-white px-4 pt-5 pb-3">
        {{-- Title + Address --}}
       <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
            <h3 class="fw-bold mb-1">{{ $page->title }}</h3>
            @if($page->address)
                <p class="text-muted small mb-0 fw-bold">{{ $page->address }}</p>
            @endif
        </div>
    
        {{-- Show Edit Profile button only to page owner --}}
        @if(auth()->user() && $page->user_id == auth()->user()->id)
            <a href="{{ route('pages.edit', ['id' => $page->id]) }}" class="btn btn-primary mt-2 mt-md-0">
                <i class="fa fa-pen me-1"></i> {{ get_phrase('Edit Profile') }}
            </a>
        @endif
    </div>

        

        {{-- Views + Chat in Separate Row --}}
        <div class="d-flex flex-wrap gap-3 align-items-center mb-2">
            {{-- Views --}}
            <span class="bg-dark text-white rounded-pill px-3 py-2 d-flex align-items-center" style="font-size: 14px;">
                <i class="fa fa-eye me-2"></i>
                {{ is_array(json_decode($pages->view, true)) ? count(json_decode($pages->view, true)) : 0 }} {{ get_phrase('Views') }}
            </span>
            
             {{-- Phone --}}
               @if(!empty($page->item_phone))
                <a href="tel:{{ $page->item_phone }}"
                   class="btn btn-sm rounded-pill d-flex align-items-center gap-2"
                   style="background-color: #f3f3f3; color: black; border: none; font-size: 14px; text-decoration: none;"
                   onmouseover="this.style.backgroundColor='#000'; this.style.color='#fff'; this.querySelector('i').style.color='#fff';"
                   onmouseout="this.style.backgroundColor='#f3f3f3'; this.style.color='black'; this.querySelector('i').style.color='black';">
                    <i class="fa fa-phone"></i> {{ $page->item_phone }}
                </a>
            @endif


            {{-- Chat --}}
            @php
                $authUser = auth()->user();
                $isOwner = $authUser && $authUser->id === $page->user_id;
            @endphp

            @if(!$isOwner)
                <a 
                    @if($authUser)
                        href="{{ route('chat.page', ['page' => $page->id]) }}"
                    @else
                        href="javascript:void(0);" onclick="showLoginPrompt();"
                    @endif
                    class="btn btn-sm rounded-pill d-flex align-items-center gap-2 text-white"
                       style="background-color: #ff4939; font-size: 14px; text-decoration: none; border: none;"
                       onmouseover="this.style.backgroundColor='#e53c2e';"
                       onmouseout="this.style.backgroundColor='#ff4939';">
                    <i class="fa fa-comments text-muted"></i> {{ get_phrase('Chat with us') }}
                </a>
            @endif
        </div>
    </div>
</div>


<script>
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
                    <i class="fa fa-comments" style="font-size: 28px; color: #4CAF50;"></i>
                </div>

                <h4 style="margin-bottom: 10px;">Please log in to chat</h4>
                <p style="margin-bottom: 20px; color: #555;">Login or register to start a conversation.</p>

                <div style="margin-bottom: 15px;">
                    <a href="{{ route('login') }}" style="
                        display: inline-block;
                        background: #4CAF50;
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
</script>

