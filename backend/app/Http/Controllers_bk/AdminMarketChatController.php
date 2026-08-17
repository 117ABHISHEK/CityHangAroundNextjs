<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marketplace;
use App\Models\MarketplaceConversation;
use App\Models\MarketplaceMessage;
use Illuminate\Support\Str;
use App\Models\FileUploader;

class AdminMarketChatController extends Controller
{
    // ✅ Show all conversations to admin
    public function index(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;

        $ownedMarketplaceIds = Marketplace::where('user_id', $userId)->pluck('id')->toArray();

        $query = MarketplaceConversation::with('user', 'marketplace')
            ->where(function ($q) use ($userId, $ownedMarketplaceIds) {
                $q->whereIn('marketplace_id', $ownedMarketplaceIds)
                  ->orWhere('user_id', $userId);
            });

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $conversations = $query->latest()->paginate(10);

        return view('backend.index', [
            'conversations' => $conversations,
            'view_path' => 'market_chat.index',
        ]);
    }

    // ✅ Show chat details
    public function show($id)
    {
        $conversation = MarketplaceConversation::with(['user', 'marketplace', 'messages.sender'])->findOrFail($id);
        $user = auth()->user();

        $isMarketplaceOwner = $conversation->marketplace && $conversation->marketplace->user_id === $user->id;
        $isInitiator = $conversation->user_id === $user->id;

        if (!($isMarketplaceOwner || $isInitiator)) {
            abort(403, 'Unauthorized');
        }

        return view('backend.index', [
            'conversation' => $conversation,
            'messages' => $conversation->messages()->with('sender')->oldest()->get(),
            'view_path' => 'market_chat.show',
        ]);
    }

    // ✅ Send message in conversation
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048',
        ]);


       
        $conversation = MarketplaceConversation::findOrFail($id);

        $data = [
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ];

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = FileUploader::upload($request->file('image'), 'public/marketplace/chat_images', 250);
            if ($imagePath) {
                $data['image'] = $imagePath;
            }
        }

        $imageUrl = $imagePath
            ? (Str::startsWith($imagePath, 'https') ? $imagePath : asset('marketplace/chat_images/' . $imagePath))
            : null;

        $message = MarketplaceMessage::create($data);

        return response()->json([
            'id' => $message->id,
            'message' => $message->message,
            'image_url' => $imageUrl,
            'time' => $message->created_at->format('d M Y, h:i A'),
            'sender_photo' => auth()->user()->photo ?? asset('assets/default-avatar.png'),
        ]);
    }

    // ✅ Fetch messages after specific ID
    public function fetchMarketplaceMessages($id)
    {
        $conversation = MarketplaceConversation::with('messages.sender')->findOrFail($id);
        $lastId = request('last_id', 0);

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
                        ? (Str::startsWith($msg->image, 'https') ? $msg->image : asset('marketplace/chat_images/' . $msg->image))
                        : null,
                    'time' => $msg->created_at->format('d M Y, h:i A'),
                ];
            }),
        ]);
    }
}
