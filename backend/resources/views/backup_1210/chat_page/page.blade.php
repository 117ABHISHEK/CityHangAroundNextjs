<style>
    .chat-wrapper {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        max-width: 700px;
        margin: auto;
    }

    .chat-title {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .chat-box {
        height: 400px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 15px;
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 8px;
        border: 1px solid #e4e4e4;
    }

    .chat-msg {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        max-width: 80%;
    }

    .chat-msg.user {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .chat-msg.other {
        align-self: flex-start;
        flex-direction: row;
    }

    .chat-bubble {
        background-color: #eaeaea;
        padding: 10px 15px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.4;
        position: relative;
        max-width: 100%;
        word-wrap: break-word;
    }

    .chat-msg.user .chat-bubble {
        background-color: #d1f3ff;
    }

    .sender-info {
        font-size: 12px;
        color: #555;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .message-time {
        font-size: 11px;
        color: #999;
        margin-top: 4px;
    }

    .sender-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #ccc;
    }

    .chat-form {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .chat-form input[type="text"],
    .chat-form input[type="file"] {
        flex: 1 1 auto;
        padding: 10px 15px;
        border-radius: 20px;
        border: 1px solid #ccc;
        outline: none;
    }

    .chat-form button {
        padding: 12px 20px;
        border: none;
        background-color: #007bff;
        color: white;
        border-radius: 20px;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .chat-form button:hover {
        background-color: #0056b3;
    }
</style>

<div class="chat-wrapper">
    <div class="chat-title">Chat with {{ $page->title ?? 'Page Owner' }}</div>

    <div id="chat-box" class="chat-box">
        @foreach($messages as $msg)
            <div class="chat-msg {{ $msg->sender_id === auth()->id() ? 'user' : 'other' }}">
                <img src="{{ $msg->sender->photo ?? asset('storage/userimage/default.png') }}" class="sender-avatar" alt="avatar">
                <div>
                    <div class="chat-bubble">
                        <div class="sender-info">
                            {{ $msg->sender_id === auth()->id() ? 'You' : $msg->sender->name }}
                        </div>
                        @if($msg->message)
                            <div>{{ e($msg->message) }}</div>
                        @endif
                        @if($msg->image)
                            @php
                                $imageUrl = Str::startsWith($msg->image, 'https')
                                    ? $msg->image
                                    : asset('pages/chat_images/' . $msg->image);
                            @endphp
                            <div class="mt-2">
                                <img src="{{ $imageUrl }}" style="max-width: 200px; border-radius: 10px;" alt="sent image">
                            </div>
                        @endif
                        <div class="message-time">{{ $msg->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <form id="chat-form" class="chat-form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
        @php
            $hasUserChatted = $messages->where('sender_id', auth()->id())->count() > 0;
        @endphp
        <input type="text" name="message" id="message-input"
               placeholder="Type your message..."
               value="{{ $hasUserChatted ? '' : 'Hi, I am interested in your page.' }}" />
        <input type="file" name="image" id="image-input" accept="image/*">
        <button type="submit">Send</button>
    </form>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const chatForm = document.getElementById('chat-form');
    const chatBox = document.getElementById('chat-box');
    const messageInput = document.getElementById('message-input');
    const imageInput = document.getElementById('image-input');
    const hasUserChatted = {{ $hasUserChatted ? 'true' : 'false' }};
    const defaultMessage = "Hi, I am interested in your page.";
    const CURRENT_USER_ID = {{ auth()->id() }};
    let lastMessageId = {{ $messages->last()->id ?? 0 }};

    function sanitizeHTML(str) {
        if (typeof str !== "string") return '';
        return str.replace(/&/g, "&amp;")
                  .replace(/</g, "&lt;")
                  .replace(/>/g, "&gt;");
    }

    function appendMessage(data, forceUserSide = false) {
    const isCurrentUser = forceUserSide || (data.sender_id == CURRENT_USER_ID);
    const senderClass = isCurrentUser ? 'user' : 'other';
    const senderName = isCurrentUser ? 'You' : sanitizeHTML(data.sender_name || 'Unknown');

    const avatar = data.sender_photo || '{{ asset('storage/userimage/default.png') }}';

    const messageHTML = `
        <div class="chat-msg ${senderClass}">
            <img src="${avatar}" class="sender-avatar" alt="avatar" onerror="this.src='{{ asset('storage/userimage/default.png') }}'">
            <div>
                <div class="chat-bubble">
                    <div class="sender-info">${senderName}</div>
                    ${data.message ? `<div>${sanitizeHTML(data.message)}</div>` : ''}
                    ${data.image_url ? `
                        <div class="mt-2">
                            <img src="${data.image_url}" style="max-width: 200px; border-radius: 10px;" />
                        </div>` : ''}
                    <div class="message-time">${data.time}</div>
                </div>
            </div>
        </div>
    `;

    chatBox.insertAdjacentHTML('beforeend', messageHTML);
    chatBox.scrollTop = chatBox.scrollHeight;
}


    function sendMessage(message = '', imageFile = null) {
        const formData = new FormData();
        formData.append('conversation_id', '{{ $conversation->id }}');
        if (message) formData.append('message', message);
        if (imageFile) formData.append('image', imageFile);

        fetch("{{ route('chat.send') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            appendMessage(data, true); // force user side

            messageInput.value = '';
            imageInput.value = '';
            lastMessageId = data.id;
        })
        .catch(err => {
            alert("Message failed to send");
            console.error(err);
        });
    }

    // if (!hasUserChatted) {
    //     sendMessage(defaultMessage);
    // }

    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const rawMessage = messageInput.value.trim();
        const imageFile = imageInput.files.length > 0 ? imageInput.files[0] : null;

        if (!rawMessage && !imageFile) {
            alert("Message or image required");
            return;
        }

        sendMessage(rawMessage, imageFile);
    });

    function fetchNewMessages() {
        fetch(`{{ route('chat.fetch', ['id' => $conversation->id]) }}?last_id=${lastMessageId}&_=${Date.now()}`)
            .then(res => res.json())
            .then(data => {
                if (!data.messages || data.messages.length === 0) return;

                data.messages.forEach(msg => {
                    if (msg.id > lastMessageId) {
                        appendMessage(msg);
                        lastMessageId = msg.id;
                    }
                });

                lastMessageId = data.messages[data.messages.length - 1].id;
            })
            .catch(err => console.error("Polling error:", err));
    }

    setInterval(fetchNewMessages, 5000);
});
</script>
