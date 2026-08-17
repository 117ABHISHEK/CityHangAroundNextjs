<style>
    .chat-wrapper {
        background: linear-gradient(to right, #f8f9fa, #eef1f5);
        border: 1px solid #dce3ea;
        border-radius: 12px;
        padding: 20px;
        height: 500px;
        overflow-y: auto;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .chat-message {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 18px;
        max-width: 85%;
    }

    .chat-message.admin {
        flex-direction: row;
        margin-right: auto;
    }

    .chat-message.user {
        flex-direction: row-reverse;
        margin-left: auto;
    }

    .chat-bubble {
        background-color: #ffffff;
        padding: 12px 18px;
        border-radius: 18px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        max-width: 100%;
        word-break: break-word;
        font-size: 14px;
        line-height: 1.5;
    }

    .chat-message.user .chat-bubble {
        background: linear-gradient(to bottom right, #c9f1ff, #dff7ff);
        text-align: right;
    }

    .sender-name {
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 4px;
        color: #333;
    }

    .message-time {
        font-size: 11px;
        color: #888;
        margin-top: 6px;
        text-align: right;
    }

    .sender-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #d0d7de;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .chat-footer {
        position: sticky;
        bottom: 0;
        background: #fff;
        padding: 15px 0 10px;
        border-top: 1px solid #e3e3e3;
    }

    .chat-image {
        max-width: 240px;
        border-radius: 10px;
        margin-top: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.07);
    }

    .input-group {
        gap: 8px;
    }

    .btn.btn-primary {
        background: linear-gradient(135deg, #007bff, #3399ff);
        border: none;
    }

    .btn.btn-primary:hover {
        background: linear-gradient(135deg, #0056d2, #1d7be0);
    }
</style>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">💬 Chat with {{ $conversation->user->name ?? 'User' }}</h4>
        <span class="text-muted">Product: {{ $conversation->marketplace->title ?? 'N/A' }}</span>
    </div>

    <div class="chat-wrapper mb-4" id="chat-box">
        @forelse($messages as $msg)
            <div class="chat-message {{ $msg->sender_id == auth()->id() ? 'user' : 'admin' }}">
                <img src="{{ $msg->sender->photo ?? asset('assets/default-avatar.png') }}"
                     alt="avatar"
                     class="sender-avatar">

                <div class="chat-bubble">
                    <div class="sender-name">
                        {{ $msg->sender_id == auth()->id() ? 'You' : $msg->sender->name ?? 'User' }}
                    </div>

                    @if($msg->message)
                        <div>{{ e($msg->message) }}</div>
                    @endif

                    @if($msg->image)
                        @php
                            $imageUrl = Str::startsWith($msg->image, 'https')
                                ? $msg->image
                                : asset('marketplace/chat_images/' . $msg->image);
                        @endphp
                        <div class="mt-2">
                            <img src="{{ $imageUrl }}" class="chat-image" alt="sent image">
                        </div>
                    @endif

                    <div class="message-time">{{ $msg->created_at->format('d M Y, h:i A') }}</div>
                </div>
            </div>
        @empty
            <p class="text-muted text-center mt-4">No messages yet.</p>
        @endforelse
    </div>

    <form id="chat-form" enctype="multipart/form-data" class="chat-footer">
        @csrf
        <div class="input-group">
            <input type="text" name="message" id="message-input" class="form-control" placeholder="Type your message...">
            <input type="file" name="image" id="image-input" class="form-control" accept="image/*">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-paper-plane me-1"></i> Send
            </button>
        </div>
    </form>
</div>

<script>
    let lastMessageId = {{ optional($messages->last())->id ?? 0 }};

    document.addEventListener('DOMContentLoaded', () => {
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;

        const chatForm = document.getElementById('chat-form');

        chatForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const messageInput = document.getElementById('message-input');
            const imageInput = document.getElementById('image-input');

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('message', messageInput.value.trim());
            if (imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }

            fetch("{{ route('admin.market.conversations.message', $conversation->id) }}", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                const html = `
                    <div class="chat-message user">
                        <img src="${data.sender_photo}" class="sender-avatar" alt="avatar">
                        <div class="chat-bubble">
                            <div class="sender-name">You</div>
                            ${data.message ? `<div>${escapeHtml(data.message)}</div>` : ''}
                            ${data.image_url ? `<div class="mt-2">
                                <img src="${data.image_url.startsWith('http') ? data.image_url : '/marketplace/chat_images/' + data.image_url}" class="chat-image" />
                            </div>` : ''}
                            <div class="message-time">${data.time}</div>
                        </div>
                    </div>
                `;
                chatBox.insertAdjacentHTML('beforeend', html);
                chatBox.scrollTop = chatBox.scrollHeight;
                messageInput.value = '';
                imageInput.value = '';
                lastMessageId = data.id;
            })
            .catch(err => {
                alert("Failed to send message.");
                console.error(err);
            });
        });

        function escapeHtml(text) {
            return text.replace(/&/g, "&amp;")
                       .replace(/</g, "&lt;")
                       .replace(/>/g, "&gt;");
        }

        function fetchNewMessages() {
            fetch("{{ route('admin.market.chat.fetch', ['id' => $conversation->id]) }}?last_id=" + lastMessageId)
                .then(res => res.json())
                .then(data => {
                     console.log("New messages:", data.messages); 
                    const newMessages = data.messages.filter(m => m.sender_id !== {{ auth()->id() }});
                    newMessages.forEach(msg => {
                        const html = `
                            <div class="chat-message admin">
                                <img src="${msg.sender_photo}" class="sender-avatar" alt="avatar">
                                <div class="chat-bubble">
                                    <div class="sender-name">${msg.sender_name}</div>
                                    ${msg.message ? `<div>${escapeHtml(msg.message)}</div>` : ''}
                                    ${msg.image_url ? `<div class="mt-2">
                                        <img src="${msg.image_url}" class="chat-image" />
                                    </div>` : ''}
                                    <div class="message-time">${msg.time}</div>
                                </div>
                            </div>
                        `;
                        chatBox.insertAdjacentHTML('beforeend', html);
                        chatBox.scrollTop = chatBox.scrollHeight;
                        lastMessageId = msg.id;
                    });
                })
                .catch(err => console.error("Polling error:", err));
        }

        setInterval(fetchNewMessages, 5000);
    });
</script>
