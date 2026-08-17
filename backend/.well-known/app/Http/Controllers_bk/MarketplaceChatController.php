<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use App\Models\MarketplaceConversation;
use App\Models\MarketplaceMessage;
use Illuminate\Http\Request;
use App\Helpers\CityHelper;
use App\Models\FileUploader;
use Illuminate\Support\Str;
use App\Services\UserActivityService;
class MarketplaceChatController extends Controller
{
    public function index(Marketplace $marketplace)
    {
        $page_data['all_cities'] = CityHelper::getActiveCities();
        $userId = auth()->id();
        $page_data['marketplace'] = $marketplace;

        // Define header
        $page_data['header'] = 'Chat with ' . ($marketplace->getUser->name ?? 'Seller');

        // Find or create conversation
        $page_data['conversation'] = MarketplaceConversation::firstOrCreate([
            'marketplace_id' => $marketplace->id,
            'user_id' => $userId,
        ]);

        $page_data['messages'] = $page_data['conversation']
            ->messages()
            ->with('sender')
            ->latest()
            ->take(50)
            ->get()
            ->reverse();

        $page_data['view_path'] = 'frontend.chat_marketplace.marketplace';

        return view('frontend.index', $page_data);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:marketplace_conversations,id',
            'message' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $conversation = MarketplaceConversation::findOrFail($request->conversation_id);
        $senderId = auth()->id();
        $hasSentBefore = $conversation->messages()->where('sender_id', $senderId)->exists();

        $messageText = $request->input('message');
        $image = $request->file('image');

        // 👇 IF FIRST TIME AND NO MESSAGE OR IMAGE, SET DEFAULT
        if (!$hasSentBefore && empty($messageText) && !$image) {
            $messageText = 'Hi, I am interested in your product.';
        }

        // 👇 Prevent sending nothing
        if (empty($messageText) && !$image) {
            return response()->json(['error' => 'Message or image required'], 422);
        }

        // ✅ Use FileUploader
        $imagePath = null;
        if ($image && !empty($image)) {
            $imagePath = FileUploader::upload($image, 'public/marketplace/chat_images', 250);
        }

        $imageUrl = null;
        if ($imagePath) {
            $imageUrl = Str::startsWith($imagePath, 'https')
                ? $imagePath
                : asset('marketplace/chat_images/' . $imagePath);
        }

       try {
                $newMessage = MarketplaceMessage::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $senderId,
                    'message' => $messageText,
                    'image' => $imagePath,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to save marketplace message', [
                    'error' => $e->getMessage()
                ]);
                return response()->json(['error' => 'Failed to send message'], 500);
            }

    if (auth()->user()){
          app(UserActivityService::class)->log(auth()->user()->id, 'enquiry_product', 'product', $conversation->marketplace_id, $conversation->marketplace_id);
     }
        return response()->json([
            'sender_photo' => $newMessage->sender->photo ?? asset('assets/default-avatar.png'),
            'message' => $newMessage->message,
            'image_url' => $imageUrl,
            'time' => $newMessage->created_at->format('d M Y, h:i A'),
        ]);
    }

    public function fetchMarketplaceMessages(Request $request, $id)
    {
        $lastId = $request->get('last_id', 0);

        $conversation = MarketplaceConversation::with('messages.sender')->findOrFail($id);

        $messages = $conversation->messages()
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'asc')
            ->get();

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
                        ? (Str::startsWith($msg->image, 'https') ? $msg->image : asset('marketplace/chat_images/' . $msg->image))
                        : null,
                    'time' => $msg->created_at->format('d M Y, h:i A'),
                ];
            }),
        ]);
    }
}
