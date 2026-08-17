<?php
// app/Http/Controllers/PageChatController.php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageConversation;
use App\Models\PageMessage;
use Illuminate\Http\Request;
use App\Helpers\CityHelper;
use App\Models\FileUploader;
use Illuminate\Support\Str;
use App\Services\UserActivityService;
class PageChatController extends Controller
{
    public function index(Page $page)
{
    $page_data['all_cities'] = CityHelper::getActiveCities();
    $userId = auth()->id();
    $page_data['page']=$page;
    // Define header
    $page_data['header'] = 'Chat with ' . ($page->getUser->name ?? 'Page Owner');

    // Find or create conversation between this user and page owner
    $page_data['conversation'] = PageConversation::firstOrCreate([
        'page_id' => $page->id,
        'user_id' => $userId,
    ]);

    $page_data['messages'] = $page_data['conversation']
        ->messages()
        ->with('sender')
        ->latest()
        ->take(50)
        ->get()
        ->reverse();

    $page_data['view_path'] = 'frontend.chat_page.page';

    return view('frontend.index', $page_data);
}


   public function sendMessage(Request $request)
{
    $request->validate([
        'conversation_id' => 'required|exists:page_conversations,id',
        'message' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
    ]);

    $conversation = PageConversation::findOrFail($request->conversation_id);
    $senderId = auth()->id();
    $hasSentBefore = $conversation->messages()->where('sender_id', $senderId)->exists();

    $messageText = $request->input('message');
    $image = $request->file('image');

    // 👇 IF FIRST TIME AND NO MESSAGE OR IMAGE, SET DEFAULT
    if (!$hasSentBefore && empty($messageText) && !$image) {
        $messageText = 'Hi, I am interested in your page.';
    }

    // 👇 Prevent sending nothing
    if (empty($messageText) && !$image) {
        return response()->json(['error' => 'Message or image required'], 422);
    }

    // ✅ Use your FileUploader class instead of default storage
    $imagePath = null;
    if ($image && !empty($image)) {
        $imagePath = FileUploader::upload($image, 'public/pages/chat_images', 250);
    }

    $imageUrl = null;
        if ($imagePath) {
            $imageUrl = Str::startsWith($imagePath, 'https')
                ? $imagePath
                : asset('pages/chat_images/' . $imagePath);
        }

    $newMessage = $conversation->messages()->create([
        'sender_id' => $senderId,
        'message' => $messageText,
        'image' => $imagePath,
    ]);


     if (auth()->user()){
          app(UserActivityService::class)->log(auth()->user()->id, 'enquiry_listing', 'page', $conversation->page_id, $conversation->page_id);
     }

    return response()->json([
        'sender_photo' => $newMessage->sender->photo ?? asset('assets/default-avatar.png'),
        'message' => $newMessage->message,
        'image_url' => $imageUrl,
        'time' => $newMessage->created_at->format('d M Y, h:i A'),
    ]);
}




    public function showMessages($conversationId)
    {
        $messages = PageMessage::where('conversation_id', $conversationId)
            ->with('sender')
            ->latest()
            ->take(50)
            ->get()
            ->reverse();

        return response()->json(['messages' => $messages]);
    }


    // PageChatController.php

// public function fetchpagechatMessages($id)
// {
//     $conversation = PageConversation::with('messages.sender')->findOrFail($id);

//     $messages = $conversation->messages
//         ->sortBy('created_at')
//         ->values();

//     return response()->json([
//         'messages' => $messages->map(function ($msg) {
//             return [
//                 'id' => $msg->id,
//                 'sender_id' => $msg->sender_id,
//                 'sender_name' => $msg->sender->name ?? 'Unknown',
//                 'sender_photo' => $msg->sender->photo ?? asset('assets/default-avatar.png'),
//                 'message' => $msg->message,
//                 'image_url' => $msg->image
//                     ? (Str::startsWith($msg->image, 'https') ? $msg->image : asset('pages/chat_images/' . $msg->image))
//                     : null,
//                 'time' => $msg->created_at->format('d M Y, h:i A'),
//             ];
//         }),
//     ]);
// }
public function fetchpagechatMessages(Request $request, $id)
{
    $lastId = $request->get('last_id', 0);

    $conversation = PageConversation::with('messages.sender')->findOrFail($id);
    

    $messages = $conversation->messages()
        ->where('id', '>', $lastId)
        ->orderBy('created_at', 'asc')
        ->get();

    // 🛑 Add this for debugging
    // \Log::info("Fetching chat", [
    //     'conversation_id' => $id,
    //     'last_id' => $lastId,
    //     'fetched_ids' => $messages->pluck('id')->toArray(),
    // ]);

    return response()->json([
        'messages' => $messages->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'sender_name' => $msg->sender->name ?? 'Unknown',
                'sender_photo' => ($photo = $msg->sender->photo ?? null) && Str::startsWith($photo, 'http')
                ? $photo
                : asset('assets/default-avatar.png'),

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
