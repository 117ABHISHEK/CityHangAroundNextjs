<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageConversation;
use App\Models\PageMessage;
use Illuminate\Support\Str;
use App\Models\FileUploader;
class AdminChatController extends Controller
{
    // Show all conversations to admin
    public function index(Request $request)
{
    $user = auth()->user();
    $userId = $user->id;

    $ownedPageIds = \App\Models\Page::where('user_id', $userId)->pluck('id')->toArray();

    $query = \App\Models\PageConversation::with('user', 'page')
        ->where(function ($q) use ($userId, $ownedPageIds) {
            $q->whereIn('page_id', $ownedPageIds)
              ->orWhere('user_id', $userId);
        });

    // ✅ Date Filter
    if ($request->filled('from')) {
        $query->whereDate('created_at', '>=', $request->from);
    }

    if ($request->filled('to')) {
        $query->whereDate('created_at', '<=', $request->to);
    }

    $conversations = $query->latest()->paginate(10);

    $page_data['conversations'] = $conversations;
    $page_data['view_path'] = 'chat.index';

    return view('backend.index', $page_data);
}



    // Show specific chat by conversation id
    public function show($id)
{
    $conversation = \App\Models\PageConversation::with(['user', 'page', 'messages.sender'])->findOrFail($id);
    $user = auth()->user();

    $isPageOwner = $conversation->page && $conversation->page->user_id === $user->id;
    $isInitiator = $conversation->user_id === $user->id;

    if (!($isPageOwner || $isInitiator)) {
        abort(403, 'Unauthorized');
    }

    $page_data['conversation'] = $conversation;
    $page_data['messages'] = $conversation->messages()->with('sender')->oldest()->get();
    $page_data['view_path'] = 'chat.show';

    return view('backend.index', $page_data);
}


    // Send message as admin
   public function sendMessage(Request $request, $id)
{
    $request->validate([
        'message' => 'nullable|string|max:1000',
        'image' => 'nullable|image|max:2048',
    ]);

    $conversation = PageConversation::findOrFail($id);

    $data = [
        'conversation_id' => $conversation->id,
        'sender_id' => auth()->id(),
        'message' => $request->message,
    ];
    $image = $request->file('image');
     $imagePath = null;
    if ($image && !empty($image)) {
    $imagePath = FileUploader::upload($image, 'public/pages/chat_images', 250);
    if ($imagePath) {
        $data['image'] = $imagePath;
        }
    }

    $imageUrl = null;
        if ($imagePath) {
            $imageUrl = Str::startsWith($imagePath, 'https')
                ? $imagePath
                : asset('pages/chat_images/' . $imagePath);
        }

    $message = PageMessage::create($data);

    return response()->json([
        'id' => $message->id, // ✅ THIS is necessary!
        'message' => $message->message,
        'image_url' =>  $imageUrl,
        'time' => $message->created_at->format('d M Y, h:i A'),
        'sender_photo' => auth()->user()->photo ?? asset('assets/default-avatar.png')
    ]);
}

public function fetchpagechatMessages($id)
{
    $conversation = PageConversation::with('messages.sender')->findOrFail($id);
    $lastId = request('last_id', 0); // default 0
    $messages = $conversation->messages
       ->where('id', '>', $lastId)
        ->sortBy('created_at')
        ->values();

    return response()->json([
        'messages' => $messages->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'sender_name' => $msg->sender->name ?? 'Unknown',
                'sender_photo' => $msg->sender->photo ?? asset('assets/default-avatar.png'),
                'message' => $msg->message,
                'image_url' => $msg->image
                    ? (Str::startsWith($msg->image, 'https') ? $msg->image : asset('pages/chat_images/' . $msg->image))
                    : null,
                'time' => $msg->created_at->format('d M Y, h:i A'),
            ];
        }),
    ]);
}


}
