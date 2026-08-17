<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chat;
use App\Models\Friendships;
use App\Models\Media_files;
use App\Models\Message_thrade;
use App\Models\FileUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use Image;
use DB;
class ChatController extends Controller
{
    public function chat($reciver,$product=null){
        $user_id  = auth()->user()->id;
         $all_cities= DB::table('cities')->select('cities.*')
        ->join('pages','pages.city_id','cities.id')
        ->join('page_category','page_category.page_id','pages.id')
        ->join('pagecategories','page_category.category_id','=','pagecategories.id')
        ->distinct('cities.id')
        ->where('pages.item_status',2)
        ->orderBy('cities.city_name','asc')->get();
        $messageThrade = Message_thrade::where(function ($query) use($reciver,$user_id) {
            $query->where('sender_id', $reciver)
                ->where('reciver_id', $user_id);
        })->orWhere(function ($query) use($reciver,$user_id) {
            $query->where('sender_id', $user_id)
                ->where('reciver_id', $reciver);
        })->first();

        $reciver_data = User::find($reciver);
        if(!empty($messageThrade)){
            Chat::where('message_thrade',$messageThrade->id)->where('reciver_id',$reciver)->where('read_status','0')->update(['read_status' => '1']);
            $message = Chat::where('message_thrade',$messageThrade->id)->orderBy('id','DESC')->limit('20')->get();
        }else{
            $message = [];
        }
        if(isset($product)&&$product!=null){
            $product_url = url('/').'/product/view/'.$product;
        }else{
            $product_url=null;
        }
        $previousChatList = Message_thrade::where('reciver_id',auth()->user()->id)->orWhere('sender_id',auth()->user()->id)->orderBy('id','DESC')->get();
        return view('frontend.chat.index',compact('reciver_data','message','previousChatList','product_url','product','all_cities'));
    }


    public function chat_save(Request $request){
        $reciver = $request->reciver_id;
        $user_id = auth()->user()->id;
        
        $firstmessageThrade = Message_thrade::where(function ($query) use($reciver,$user_id) {
            $query->where('sender_id', $reciver)
                ->where('reciver_id', $user_id);
        })->orWhere(function ($query) use($reciver,$user_id) {
            $query->where('sender_id', $user_id)
                ->where('reciver_id', $reciver);
        })
        ->first();

        
        $messageThradeCount = Message_thrade::where(function ($query) use($reciver,$user_id) {
            $query->where('sender_id', $reciver)
                ->where('reciver_id', $user_id);
        })->orWhere(function ($query) use($reciver,$user_id) {
            $query->where('sender_id', $user_id)
                ->where('reciver_id', $reciver);
        })
        ->count();

        if($messageThradeCount <= 0){
            $messageThrade = new Message_thrade();
            $messageThrade->sender_id = auth()->user()->id;
            $messageThrade->reciver_id = $request->reciver_id;
            $messageThrade->chatcenter = $request->messagecenter;
            $done = $messageThrade->save();
            if($done){
                $chat = new Chat();
                $chat->reciver_id = $request->reciver_id;
                $chat->sender_id = auth()->user()->id;
                $chat->chatcenter = $request->messagecenter;
                $chat->message = $request->message;
                $chat->message_thrade = $messageThrade->id;
                $chat->thumbsup = $request->thumbsup;
                $chat->file ='1';
                $chat->save();
                $last_chat_id = $chat->id;

                $page_data['message'] = $chat;
                $page_data['user_info'] = auth()->user();
                $message = view('frontend.chat.single-message', $page_data)->render();
                $url = url('/').'/chat/inbox/'.$request->reciver_id;
                if(isset($request->product_id)&&!empty($request->product_id)){
                    $response = array('appendElement' => '#message_body','content' => $message,'clickTo'=>'#messageResetBox','replaceUrl' => '#message_body','url' => $url);
                }else{
                    $response = array('appendElement' => '#message_body','content' => $message,'clickTo'=>'#messageResetBox');
                }
                
                return json_encode($response);
            }
        }else{
            $chat = new Chat();
            $chat->reciver_id = $request->reciver_id;
            $chat->sender_id = auth()->user()->id;
            $chat->chatcenter = $request->messagecenter;
            $chat->message = $request->message;
            $chat->message_thrade = $firstmessageThrade->id;
            $chat->thumbsup = $request->thumbsup;
            $chat->file ='1';
            $chat->save();
            $last_chat_id = $chat->id;

            $page_data['message'] = $chat;
            $page_data['user_info'] = auth()->user();
            $message = view('frontend.chat.single-message', $page_data)->render();
            $url = url('/').'/chat/inbox/'.$request->reciver_id;
            if(isset($request->product_id)&&!empty($request->product_id)){
                $response = array('appendElement' => '#message_body','content' => $message,'clickTo'=>'#messageResetBox','replaceUrl' => '#message_body','url' => $url);
            }else{
                $response = array('appendElement' => '#message_body','content' => $message,'clickTo'=>'#messageResetBox');
            }
            
            return json_encode($response);
        }
    }

    public function remove_chat($id){
        $chat = Chat::find($id);
        if($chat->sender_id == auth()->user()->id){
            $chat->delete();
            Session::flash('success_message', get_phrase('Chat removed successfully'));
        }else{
            Session::flash('error_message', get_phrase('You are not authorized to remove this chat'));
        }
        return redirect()->back();
    }

    public function react_chat(Request $request){
        $chat = Chat::find($request->chat_id);
        if($chat->sender_id == auth()->user()->id){
            $chat->thumbsup = $request->thumbsup;
            $chat->save();
            return json_encode(array('status' => 'success'));
        }else{
            return json_encode(array('status' => 'error'));
        }
    }

    public function search_chat(Request $request){
        $search = $request->search;
        $user_id = auth()->user()->id;
        
        $users = User::where('name', 'like', '%'.$search.'%')
            ->where('id', '!=', $user_id)
            ->get();
        
        return view('frontend.chat.search_results', compact('users'));
    }

    public function chat_load(Request $request){
        $messageThrade = Message_thrade::where('id', $request->message_thrade_id)->first();
        $messages = Chat::where('message_thrade', $messageThrade->id)->orderBy('id', 'ASC')->get();
        
        $page_data['messages'] = $messages;
        $page_data['user_info'] = auth()->user();
        return view('frontend.chat.messages', $page_data);
    }

    public function chat_read_option(Request $request){
        $messageThrade = Message_thrade::where('id', $request->message_thrade_id)->first();
        Chat::where('message_thrade', $messageThrade->id)
            ->where('reciver_id', auth()->user()->id)
            ->where('read_status', '0')
            ->update(['read_status' => '1']);
        
        return json_encode(array('status' => 'success'));
    }

    /**
     * Handle chat with us functionality
     */
    public function chatWithUs(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Store the message in a support chat table or send to support system
        // For now, we'll just return a success response
        // You can integrate with your support system here
        
        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent to our support team. We will get back to you soon.',
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
    }
}



