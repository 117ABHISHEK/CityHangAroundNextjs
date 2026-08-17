<div class="profile-wrap new">
    <!-- Cover Photo Banner -->
    <div class="profile-cover rounded">
        <div class="profile-header" style="background-image: url('{{get_cover_photo($user_info->cover_photo)}}');">
            <!-- Edit Cover Photo Button -->
            <button onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.profile.edit_cover_photo'])}}', '{{get_phrase('Update your cover photo')}}');" class="edit-cover-btn">
                <i class="fa fa-camera"></i>{{get_phrase('Edit Cover Photo')}}
            </button>
        </div>
    </div>
    
    <!-- Profile Info Section - Below the banner -->
    <div class="profile-info-container">
        <div class="container">
            <div class="profile-info-section">
                <!-- Profile Avatar -->
                <div class="profile-avatar-container">
                    <div class="profile-pic">
                        <img src="{{get_user_image($user_info->photo, 'optimized')}}" alt="Profile Picture">
                    </div>
                </div>
                
                <!-- Profile Details -->
                <div class="profile-details">
                    <h2 class="profile-name">{{auth()->user()->name}}</h2>
                    <p class="profile-description">{{$user_info->about ?: 'cool person'}}</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="profile-actions">
                    <!-- Views Button -->
                    <div class="action-button views-btn">
                        <i class="fa fa-eye"></i>
                        @php
                            $profile_views = \App\Models\UserActivityLog::where('content_type', 'profile')
                                ->where('content_id', auth()->user()->id)
                                ->where('event_name', 'view')
                                ->count();
                        @endphp
                        <span>{{ $profile_views }} {{ get_phrase('Views') }}</span>
                    </div>
                    
                    <!-- Followers Button -->
                    <div class="action-button followers-btn">
                        <i class="fa fa-users"></i>
                        @php
                            $followers_count = \App\Models\Follower::where('follow_id', auth()->user()->id)->count();
                        @endphp
                        <span>{{ $followers_count }} {{ get_phrase('Followers') }}</span>
                    </div>
                    
                    <!-- Chat with Us Button -->
                    <button onclick="openChatWithUs()" class="action-button chat-btn">
                        <i class="fa fa-comments"></i>
                        <span>{{ get_phrase('Chat with us') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="profile-content mt-3">
        <div>
        <nav class="profile-nav border bg-white mb-3">
                    <ul class="nav align-items-center justify-content-start">
                        <li class="nav-item @if(Route::currentRouteName() == 'profile') active @endif"><a href="{{route('profile')}}" class="nav-link">{{get_phrase('Buzz')}}</a></li>
                        <!-- <li class="nav-item @if(Route::currentRouteName() == 'profile.friends') active @endif"><a href="{{route('profile.friends')}}" class="nav-link">{{get_phrase('Friends')}}</a></li> -->
                        <li class="nav-item @if(Route::currentRouteName() == 'profile.friends') active @endif"><a href="{{route('profile.friends')}}" class="nav-link">{{get_phrase('Follower')}}</a></li>
                        <li class="nav-item @if(Route::currentRouteName() == 'profile.photos') active @endif"><a href="{{route('profile.photos')}}" class="nav-link">{{get_phrase('Photo')}}</a></li>
                        <li class="nav-item @if(Route::currentRouteName() == 'profile.videos') active @endif"><a href="{{route('profile.videos')}}" class="nav-link">{{get_phrase('Video')}}</a></li>

                        <li class="nav-item @if(Route::currentRouteName() == 'profile.page') active @endif"><a href="{{route('profile.page')}}" class="nav-link">{{get_phrase('Merchants')}}</a></li>
                        <li class="nav-item @if(Route::currentRouteName() == 'profile.events') active @endif"><a href="{{route('profile.events')}}" class="nav-link">{{get_phrase('Event')}}</a></li>
                        <li class="nav-item @if(Route::currentRouteName() == 'profile.blogs') active @endif"><a href="{{route('profile.blogs')}}" class="nav-link">{{get_phrase('Blog')}}</a></li>
                        <li class="nav-item @if(Route::currentRouteName() == 'profile.groups') active @endif"><a href="{{route('profile.groups')}}" class="nav-link">{{get_phrase('Community')}}</a></li>
                        <li class="nav-item @if(Route::currentRouteName() == 'profile.products') active @endif"><a href="{{route('profile.products')}}" class="nav-link">{{get_phrase('Deals')}}</a></li>
                    </ul>
                </nav>

        </div>
        <div class="row gx-3">
            <div class="col-lg-7 col-sm-12">
               

                @if(Route::currentRouteName() == 'profile.friends')
                    @include('frontend.profile.friends')
                @elseif(Route::currentRouteName() == 'profile.photos')
                    @include('frontend.profile.photos')
                @elseif(Route::currentRouteName() == 'profile.videos')
                    @include('frontend.profile.videos')
                    @elseif(Route::currentRouteName() == 'profile.page')
                    @include('frontend.profile.pages')
                    @elseif(Route::currentRouteName() == 'profile.events')
                    @include('frontend.profile.events')
                    @elseif(Route::currentRouteName() == 'profile.blogs')
                    @include('frontend.profile.blogs')
                    @elseif(Route::currentRouteName() == 'profile.groups')
                    @include('frontend.profile.groups')
                    @elseif(Route::currentRouteName() == 'profile.products')
                    @include('frontend.profile.products')
                @else
                    @include('frontend.main_content.create_post')

                    <div id="profile-timeline-posts">
                        @include('frontend.main_content.posts',['type'=>'user_post'])
                    </div>

                    @include('frontend.main_content.scripts')
                @endif
            </div>
            <!-- COL END -->
            <div class="col-lg-5 col-sm-12">
                @include('frontend.profile.profile_info',['type'=>"my_account"])
            </div>
        </div>
    </div>
    <!-- Profile content End -->
</div>

<!-- Chat with Us Modal -->
<div class="modal fade" id="chatWithUsModal" tabindex="-1" aria-labelledby="chatWithUsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chatWithUsModalLabel">
                    <i class="fa fa-comments me-2"></i>{{ get_phrase('Chat with Us') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="chat-container">
                    <div class="chat-messages" id="chatMessages" style="height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                        <!-- Messages will be loaded here -->
                    </div>
                    <div class="chat-input">
                        <form id="chatForm" class="d-flex gap-2">
                            <input type="text" id="chatMessageInput" class="form-control" placeholder="{{ get_phrase('Type your message...') }}" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('frontend.footer')
@include('frontend.profile.scripts')

<style>
/* Profile Header Styling */
.profile-header {
    position: relative;
    height: 280px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    border-radius: 12px;
    overflow: hidden;
    background-color: #f5f5f5;
}

/* Edit Cover Photo Button */
.edit-cover-btn {
    position: absolute;
    top: 20px;
    right: 20px;
    background-color: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 20px;
    padding: 8px 16px;
    font-size: 14px;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 10;
}

.edit-cover-btn:hover {
    background-color: rgba(255, 255, 255, 1);
    color: #333;
}

.edit-cover-btn i {
    margin-right: 6px;
}

/* Profile Info Container - Below the banner */
.profile-info-container {
    background-color: #f5f5f5;
    padding: 20px 0;
    margin-top: -20px;
    border-radius: 0 0 12px 12px;
}

/* Profile Info Section */
.profile-info-section {
    display: flex;
    align-items: center;
    gap: 20px;
    color: #333;
}

/* Profile Avatar Container */
.profile-avatar-container {
    flex-shrink: 0;
}

.profile-pic {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    border: 4px solid white;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    position: relative;
}

.profile-pic img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
}

/* Profile Details */
.profile-details {
    flex: 1;
}

.profile-name {
    font-size: 28px;
    font-weight: 600;
    margin: 0 0 8px 0;
    color: #333;
}

.profile-description {
    font-size: 16px;
    margin: 0;
    color: #666;
    max-width: 400px;
    line-height: 1.4;
}

/* Action Buttons */
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

.followers-btn {
    background-color: #f0f0f0;
    color: #666;
}

.followers-btn:hover {
    background-color: #e0e0e0;
    color: #333;
}

.chat-btn {
    background-color: #dc3545;
    color: white;
    border: none;
}

.chat-btn:hover {
    background-color: #c82333;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
}

.action-button i {
    font-size: 16px;
}

/* Navigation Styling */
.profile-nav {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.profile-nav .nav-link {
    color: #666;
    font-weight: 500;
    padding: 12px 20px;
    border-radius: 0;
    transition: all 0.3s ease;
}

.profile-nav .nav-link:hover {
    color: #333;
    background-color: #f8f9fa;
}

.profile-nav .nav-item.active .nav-link {
    color: #dc3545;
    border-bottom: 3px solid #dc3545;
    background-color: transparent;
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-info-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        text-align: center;
    }
    
    .profile-actions {
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
        width: 100%;
    }
    
    .action-button {
        padding: 8px 12px;
        font-size: 12px;
    }
    
    .profile-name {
        font-size: 24px;
    }
    
    .profile-description {
        font-size: 14px;
    }
    
    .profile-pic {
        width: 120px;
        height: 120px;
    }
}

@media (max-width: 576px) {
    .profile-header {
        height: 240px;
    }
    
    .profile-info-container {
        padding: 15px 0;
    }
    
    .profile-actions {
        flex-direction: column;
        align-items: stretch;
        width: 100%;
    }
    
    .action-button {
        justify-content: center;
    }
    
    .profile-pic {
        width: 100px;
        height: 100px;
    }
}

/* Chat Modal Styling */
.chat-container {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
}

.chat-messages {
    background: white;
}

.message {
    margin-bottom: 10px;
    padding: 8px 12px;
    border-radius: 15px;
    max-width: 80%;
    word-wrap: break-word;
}

.message.user {
    background: #007bff;
    color: white;
    margin-left: auto;
}

.message.support {
    background: #e9ecef;
    color: #333;
}

.message-time {
    font-size: 0.7rem;
    opacity: 0.7;
    margin-top: 2px;
}
</style>

<script>
function openChatWithUs() {
    $('#chatWithUsModal').modal('show');
    loadChatMessages();
}

function loadChatMessages() {
    // Load initial support message
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.innerHTML = `
        <div class="message support">
            <div>Hello! How can we help you today?</div>
            <div class="message-time">${new Date().toLocaleTimeString()}</div>
        </div>
    `;
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

document.getElementById('chatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const input = document.getElementById('chatMessageInput');
    const message = input.value.trim();
    
    if (message) {
        const chatMessages = document.getElementById('chatMessages');
        
        // Add user message
        const userMessage = document.createElement('div');
        userMessage.className = 'message user';
        userMessage.innerHTML = `
            <div>${message}</div>
            <div class="message-time">${new Date().toLocaleTimeString()}</div>
        `;
        chatMessages.appendChild(userMessage);
        
        // Clear input
        input.value = '';
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        // Send message to backend
        fetch('{{ route("chat.with.us") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf_token"]').getAttribute('content')
            },
            body: JSON.stringify({
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const supportMessage = document.createElement('div');
                supportMessage.className = 'message support';
                supportMessage.innerHTML = `
                    <div>${data.message}</div>
                    <div class="message-time">${new Date().toLocaleTimeString()}</div>
                `;
                chatMessages.appendChild(supportMessage);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const supportMessage = document.createElement('div');
            supportMessage.className = 'message support';
            supportMessage.innerHTML = `
                <div>Sorry, there was an error sending your message. Please try again.</div>
                <div class="message-time">${new Date().toLocaleTimeString()}</div>
            `;
            chatMessages.appendChild(supportMessage);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        });
    }
});

// Track profile view when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Track profile view
    fetch('{{ route("profile.track_view") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf_token"]').getAttribute('content')
        }
    });
});
</script>