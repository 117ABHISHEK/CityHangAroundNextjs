<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{Stories, Posts, Comments, Feeling_and_activities, CommonModels, Live_streamings, Users, Friendships, Media_files, Albums, Notification, User, FileUploader,Event,Group,Marketplace,Group_member};
use App\Models\Page_like;
use Session, Image;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use DB;
use App\Helpers\CityHelper;
use App\Services\UserActivityService;
use Illuminate\Support\Facades\Cache;
class Profile extends Controller
{
    private $user;
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth()->user();
            return $next($request);
        });
    }

    private function get_profile_data()
    {
        $user = $this->user;
        $userId = $user->id;

        // User Stats
        // 1. Posts count
        $posts_count = Posts::where('user_id', $userId)->where('posts.publisher', 'post')->count();

        // 2. Followers count
        $followers_count = \App\Models\Follower::where('follow_id', $userId)->count();

        // 3. Following count (friends who have accepted)
        $following_count = DB::table('friendships')->where(function ($q) use ($userId) {
            $q->where('accepter', $userId)
              ->orWhere('requester', $userId);
        })->where('is_accepted', 1)->count();

        return [
            'posts_count' => $posts_count,
            'followers_count' => $followers_count,
            'following_count' => $following_count,
            'user' => $user,
            'user_info' => $user,
        ];
    }


    public function profile()
    {
        $userId = $this->user->id;
        $page_data['all_cities'] = CityHelper::getActiveCities();

        // User's own + tagged posts - limited to 9 for initial fast load
        $posts = Posts::where(function ($query) use ($userId) {
                $query->whereJsonContains('posts.tagged_user_ids', [$userId])
                    ->where('posts.privacy', '!=', 'private')
                    ->orWhere('posts.user_id', $userId);
            })
            ->where('posts.publisher', 'post')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.name', 'users.photo', 'users.friends', 'posts.created_at as created_at')
            ->orderBy('posts.post_id', 'DESC')
            ->take(9)
            ->get();

        $page_data['posts'] = $posts;
        $page_data = array_merge($page_data, $this->get_profile_data());
        $page_data['view_path'] = 'frontend.profile.index';

        return view('frontend.index', $page_data);
    }

    public function aboutPage()
    {
        $page_data['all_cities'] = CityHelper::getActiveCities();
        $page_data = array_merge($page_data, $this->get_profile_data());
        $page_data['view_path'] = 'frontend.profile.index';

        return view('frontend.index', $page_data);
    }


    function load_my_profile()
    {
        $page_data['state'] = \App\Models\State::find(auth()->user()->state_id);
        $page_data['city']  = \App\Models\City::find(auth()->user()->city_id);
        $page_data['area']  = \App\Models\Area::find(auth()->user()->area_id);

        $page_data['all_cities'] = CityHelper::getActiveCities();
        $page_data['user_info'] = $this->user;
        $page_data['view_path'] = 'frontend.profile.edit_profile';
        return view('frontend.index', $page_data);
    }

    function load_post_by_scrolling(Request $request)
    {
        //For my own profile
        $posts =  Posts::where(function ($query) {
            $query->whereJsonContains('posts.tagged_user_ids', [$this->user->id])
                ->where('posts.privacy', '!=', 'private')
                ->orWhere('posts.user_id', $this->user->id);
        })
            ->where('posts.publisher', 'post')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.name', 'users.photo', 'users.friends', 'posts.created_at as created_at')
            ->skip($request->offset)->take(3)->orderBy('posts.post_id', 'DESC')->get();


        $page_data['posts'] = $posts;
        $page_data['user_info'] = $this->user;
        $page_data['type'] = 'user_post';
        $page_data = array_merge($page_data, $this->getPostCommentAggregates($posts));
        return view('frontend.main_content.posts', $page_data);
    }

    private function getPostCommentAggregates($posts): array
    {
        $postIds = collect($posts)->pluck('post_id')->filter()->unique()->values()->all();
        if (empty($postIds)) {
            return ['comment_counts' => [], 'latest_comments' => []];
        }

        $commentCounts = DB::table('comments')
            ->select('id_of_type', DB::raw('COUNT(*) as total'))
            ->where('is_type', 'post')
            ->whereIn('id_of_type', $postIds)
            ->groupBy('id_of_type')
            ->pluck('total', 'id_of_type')
            ->toArray();

        $latestMainCommentIds = DB::table('comments')
            ->select(DB::raw('MAX(comment_id) as comment_id'))
            ->where('is_type', 'post')
            ->where('parent_id', 0)
            ->whereIn('id_of_type', $postIds)
            ->groupBy('id_of_type')
            ->pluck('comment_id')
            ->filter()
            ->values()
            ->all();

        $latestComments = [];
        if (!empty($latestMainCommentIds)) {
            $rows = DB::table('comments')
                ->join('users', 'comments.user_id', '=', 'users.id')
                ->whereIn('comments.comment_id', $latestMainCommentIds)
                ->select('comments.*', 'users.name', 'users.photo')
                ->get();

            foreach ($rows as $row) {
                $latestComments[$row->id_of_type] = collect([$row]);
            }
        }

        return [
            'comment_counts' => $commentCounts,
            'latest_comments' => $latestComments,
        ];
    }


    

    function friends()
    {

        $page_data['all_cities'] = CityHelper::getActiveCities();

        $friendships = Friendships::where(function ($query) {
            $query->where('accepter', $this->user->id)
                ->orWhere('requester', $this->user->id);
        })
            ->where('is_accepted', 1)
            ->orderBy('friendships.importance', 'desc')
            ->take(15)->get();

        $friend_requests = Friendships::where('accepter', $this->user->id)
            ->where('is_accepted', '!=', 1)
            ->take(15)->get();

        $page_data['friendships'] = $friendships;
        $page_data['friend_requests'] = $friend_requests;

        $page_data = array_merge($page_data, $this->get_profile_data());
        $page_data['view_path'] = 'frontend.profile.index';
        return view('frontend.index', $page_data);
    }

    function photos()
    {
        
         $page_data['all_cities'] = CityHelper::getActiveCities();

        $all_photos = Media_files::where('user_id', $this->user->id)
            ->where('file_type', 'image')
            ->whereNull('story_id')
            ->whereNull('product_id')
            ->whereNull('page_id')
            ->whereNull('group_id')
            ->whereNull('chat_id')
            ->orderBy('id', 'DESC')->get();

        $all_albums = Albums::where('user_id', $this->user->id)
            ->whereNull('page_id')
            ->whereNull('group_id')
            ->take(6)->orderBy('id', 'DESC')->get();

        $page_data['all_photos'] = $all_photos;
        $page_data['all_albums'] = $all_albums;
        $page_data = array_merge($page_data, $this->get_profile_data());
        $page_data['view_path'] = 'frontend.profile.index';
        return view('frontend.index', $page_data);
    }

    function load_photos(Request $request)
    {
        $all_photos = Media_files::where('user_id', $this->user->id)
            ->where('file_type', 'image')
            ->whereNull('story_id')
            ->whereNull('product_id')
            ->whereNull('page_id')
            ->whereNull('group_id')
            ->whereNull('chat_id')
            ->skip($request->offset)->take(12)->orderBy('id', 'DESC')->get();

        $page_data['all_photos'] = $all_photos;
        $page_data['user_info'] = $this->user;
        return view('frontend.profile.photo_single', $page_data);
    }

    function album($action_type, Request $request)
    {
        // return $action_type;
        $error = null;

        if ($action_type == 'form') {
            return view('frontend.profile.album_create_form');
        } elseif ($action_type == 'delete') {
            DB::table('albums')->where('id', $request->album_id)->delete();
            DB::table('media_files')->where('album_id', $request->album_id)->delete();

            $response = array('alertMessage' => get_phrase('Album deleted successfully'), 'hideElem' => '#photoAlbum'.$request->album_id);
            return json_encode($response);

        } elseif ($action_type == 'store') {
            $album_show_on= 'profile';
            $rules = array('title' => 'required|max:255', 'privacy' => 'required', 'thumbnail' => 'image|nullable');
            $validator = Validator::make($request->all(), $rules);
            // Validate the input and return correct response
            if ($validator->fails()) {
                return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
            }



            $data['user_id'] = $this->user->id;
            $data['title'] = $request->title;
            $data['sub_title'] = $request->sub_title;
            $data['privacy'] = $request->privacy;
            if (isset($request->page_id) && !empty($request->page_id)) {
                $data['page_id'] = $request->page_id;
                $album_show_on= 'page';
            }
            if (isset($request->group_id) && !empty($request->group_id)) {
                $data['group_id'] = $request->group_id;
                $album_show_on= 'group';
            }
            $data['created_at'] = time();
            $data['updated_at'] = $data['created_at'];


            if ($request->thumbnail) {
                $file_name = FileUploader::upload($request->thumbnail,'public/storage/thumbnails/album', 800);

                $data['thumbnail'] = $file_name;
            }


            $album_id = Albums::insertGetId($data);
            $page_data['all_albums'] = Albums::where('id', $album_id)->get();

            $album_view = view('frontend.profile.album_single', $page_data)->render();
            $response = array('hideCustomModal' => 1, 'appendAfterElement' => '#'.$album_show_on.'-album-row .col-create-album:first-child', 'content' => $album_view);

            echo json_encode($response);
        }
    }

    function load_albums(Request $request)
    {
        $all_albums = Albums::where('user_id', $this->user->id)
            ->whereNull('page_id')
            ->whereNull('group_id')
            ->skip($request->offset)->take(20)->orderBy('id', 'DESC')->get();

        $page_data['all_albums'] = $all_albums;
        $page_data['user_info'] = $this->user;
        return view('frontend.profile.album_single', $page_data);
    }

    function videos()
    {
        
         $page_data['all_cities'] = CityHelper::getActiveCities();

        $all_videos = Media_files::where('user_id', $this->user->id)
            ->where('file_type', 'video')
            ->take(24)->orderBy('id', 'DESC')->get();

        $page_data['all_videos'] = $all_videos;
        $page_data = array_merge($page_data, $this->get_profile_data());
        $page_data['view_path'] = 'frontend.profile.index';
        return view('frontend.index', $page_data);
    }


    function events(){
        $page_data['all_cities'] = CityHelper::getActiveCities();
        
        $page_data['events'] = Event::where('user_id', $this->user->id)->where('event_status',2)
        ->where('events.event_date', '>=', Carbon::now())
        ->whereNull('group_id')->orderBy('id', 'DESC')->get();

        $page_data = array_merge($page_data, $this->get_profile_data());
        $page_data['view_path'] = 'frontend.profile.index';
        return view('frontend.index', $page_data);

    }

    function groups(){

        $page_data['all_cities'] = CityHelper::getActiveCities();
        
        $page_data['groups'] =Group::select('groups.*', 'groupcategories.category_name')
        ->join('group_category', 'groups.id', '=', 'group_category.group_id')
        ->join('groupcategories', 'group_category.category_id', '=', 'groupcategories.id')
        ->where('groups.user_id', $this->user->id)->get();

        $page_data = array_merge($page_data, $this->get_profile_data());
        $page_data['view_path'] = 'frontend.profile.index';
        return view('frontend.index', $page_data);
    }


    function pages(){

        $page_data['all_cities'] = CityHelper::getActiveCities();

        $page_data['pages'] = DB::table('pages')->select('pages.id','pages.item_slug','pages.logo','pages.title','cities.city_slug','areas.area_slug'
        ,'cities.city_name','areas.area_name','states.state_name')
        ->join('cities','cities.id','pages.city_id')
        ->join('areas','areas.id','pages.area_id')
        ->join('states','states.id','pages.state_id')
        ->join('page_category','page_category.page_id','pages.id')
        ->distinct('pages.id')->orderBy('pages.id','DESC')
        ->where('user_id',$this->user->id)
        ->where('pages.item_status',2)
        ->orderBy('pages.id','DESC')->orderBy('id','DESC')->paginate(6);

        $page_data = array_merge($page_data, $this->get_profile_data());
        $page_data['view_path'] = 'frontend.profile.index';
        return view('frontend.index', $page_data);
    }

    function products(){

        $page_data['all_cities'] = CityHelper::getActiveCities();
        
        $page_data['products'] = Marketplace::
        select('marketplaces.*')
        ->join('pages','marketplaces.page_id','pages.id')
        ->join('cities','pages.city_id','cities.id')
        ->join('category_product','marketplaces.id','category_product.product_id')
        ->distinct('marketplaces.id')
        ->orderBy('marketplaces.id', 'DESC')
        ->where('marketplaces.product_status',2)
        ->where('marketplaces.user_id',$this->user->id)->orderBy('id','DESC')->get();

        $page_data = array_merge($page_data, $this->get_profile_data());
        $page_data['view_path'] = 'frontend.profile.index';
        return view('frontend.index', $page_data);
    }


    function blogs(){
        $page_data['all_cities'] = CityHelper::getActiveCities();
        
        $page_data['blogs'] = DB::table('blogs')->select('blogs.*','cities.city_slug','areas.area_slug',
        'cities.city_name','areas.area_name','states.state_name','users.name as username','users.id as userid' )
        ->leftJoin('cities','cities.id','blogs.city_id')
        ->leftJoin('areas','areas.id','blogs.area_id')
        ->leftJoin('states','states.id','blogs.state_id')
        ->join('blog_category','blog_category.blog_id','blogs.id')
        ->join('users','users.id','blogs.user_id')
        ->distinct('blogs.id')->where('user_id',$this->user->id)
        ->where('blogs.blog_status',2)->orderBy('blogs.id', 'DESC')->get();

        $page_data = array_merge($page_data, $this->get_profile_data());
        $page_data['view_path'] = 'frontend.profile.index';
        return view('frontend.index', $page_data);
    }

    function load_videos(Request $request)
    {
        $all_videos = Media_files::where('user_id', $this->user->id)
            ->where('file_type', 'video')
            ->skip($request->offset)->take(12)->orderBy('id', 'DESC')->get();

        $page_data['all_videos'] = $all_videos;
        $page_data['user_info'] = $this->user;
        return view('frontend.profile.video_single', $page_data);
    }

    function load_my_friends(Request $request)
    {
        $friendships = Friendships::where(function ($query) {
            $query->where('accepter', $this->user->id)
                ->orWhere('requester', $this->user->id);
        })
            ->where('is_accepted', 1)
            ->orderBy('friendships.importance', 'desc')
            ->skip($request->offset)->take(15)->get();

        $page_data['friendships'] = $friendships;
        $page_data['user_info'] = $this->user;
        return view('frontend.profile.friends_single_data', $page_data);
    }

    function load_my_friend_requests(Request $request)
    {
        $friend_requests = Friendships::where('accepter', $this->user->id)
            ->where('is_accepted', '!=', 1)
            ->skip($request->offset)->take(15)->get();

        $page_data['friend_requests'] = $friend_requests;
        $page_data['user_info'] = $this->user;
        return view('frontend.profile.friend_requests_single_data', $page_data);
    }

    function accept_friend_request(Request $request)
    {
        
        $response = array();

        $is_updated = Friendships::where('accepter', $this->user->id)
            ->where('requester', $request->user_id)
            ->update(['is_accepted' => 1]);


        if ($is_updated == 1) {
            //update my friends id to my friend list
            $my_friends = User::where('id', $this->user->id)->value('friends');
            $my_friends = json_decode($my_friends);
            if(is_array($my_friends)){
                array_push($my_friends, (int)$request->user_id);
            }else{
                $my_friends = [(int)$request->user_id];
            }
            $my_friends = json_encode($my_friends);

            User::where('id', $this->user->id)->update(['friends' => $my_friends]);


            //update my id to my friend list
            $my_friends_of_friends = User::where('id', $request->user_id)->value('friends');
            $my_friends_of_friends = json_decode($my_friends_of_friends);

            if(is_array($my_friends_of_friends)){
                array_push($my_friends_of_friends, (int)$this->user->id);
            }else{
                $my_friends_of_friends = [(int)$this->user->id];
            }
            $my_friends_of_friends = json_encode($my_friends_of_friends);

            User::where('id', $request->user_id)->update(['friends' => $my_friends_of_friends]);


            //Send notification
            Notification::where('sender_user_id',(int)$request->user_id)->where('reciver_user_id',$this->user->id)->update(['status'=>'1','view'=>'1']);
            $notify = new Notification();
            $notify->sender_user_id = auth()->user()->id;
            $notify->reciver_user_id = (int)$request->user_id;
            $notify->type = "friend_request_accept";
            $notify->save();

            $response = array('alertMessage' => get_phrase('Friend request accepted'), 'showElem' => "#friendRequestAcceptedBtn$request->user_id", 'hideElem' => "#friendRequestConfirmBtn$request->user_id");
        }

        return json_encode($response);
    }

    
    function delete_friend_request(Request $request)
    {
        $response = array();

        $row = Friendships::where('accepter', $this->user->id)
            ->where('requester', $request->user_id)
            ->where('is_accepted', '!=', 1);


        if ($row->get()->count() > 0) {
            Friendships::where('id', $row->value('id'))->delete();
            $response = array('alertMessage' => get_phrase('Friend request deleted'), 'fadeOutElem' => "#friendRequest$request->user_id");
        }

        return json_encode($response);
    }

    function about($action_type, Request $request)
    {
        $response = array();

        if ($action_type = 'update') {
            $data['about'] = $request->about;
            Users::where('id', $this->user->id)->update($data);
            $response = array('alertMessage' => get_phrase('Your bio updated'), 'hideElem' => '.edit-bio-form', 'showElem' => '.edit-bio-btn', 'elemSelector' => '.my-about', 'content' => htmlspecialchars(nl2br($request->about)));
        }

        return json_encode($response);
    }

    function my_info($action_type, Request $request)
    {
        $response = array();

        if ($action_type == 'edit') {
            $page_data['user_info'] = Users::where('id', $this->user->id)->first();
            return view('frontend.profile.my_info_edit', $page_data);
        } elseif ($action_type == 'update') {
            $data['job'] = $request->job;
            $data['studied_at'] = $request->studied_at;
            $data['address'] = $request->address;
            $data['gender'] = $request->gender;

            $is_updated = Users::where('id', $this->user->id)->update($data);

            $page_data['user_info'] = Users::where('id', $this->user->id)->first();
            $user_frofile_info = view('frontend.profile.my_info', $page_data)->render();
            $response = array('hideCustomModal' => 1, 'elemSelector' => '#my-profile-info', 'content' => $user_frofile_info, 'alertMessage' => get_phrase('Profile info updated'));
        }

        return json_encode($response);
    }

    function upload_photo($photo_type, Request $request)
    {
        if ($photo_type == 'cover_photo') {
            // Validate the input and return correct response
            $rules = array('cover_photo' => 'mimes:jpeg,jpg,png,gif|required');
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
            }

            $file_name = FileUploader::upload($request->cover_photo,'public/storage/cover_photo', 1120);

            //Update to database
            $data['cover_photo'] = $file_name;
            Users::where('id', $this->user->id)->update($data);
            Cache::forget("user_cover_{$this->user->id}");

            //Ajax flush message
            Session::flash('success_message', get_phrase('Cover photo updated'));
            return json_encode(array('reload' => 1));
        } else {
            return json_encode(array('alertMessage' => json_encode($request->all())));
        }
    }

    function update_profile(Request $request)
    {

        $rules = array(
            'profile_photo' => 'mimes:jpeg,jpg,png,gif|nullable',
            'name' => 'max:255|required',
            'nickname' => 'max:255|nullable',
            'marital_status' => 'max:255|nullable',
            'phone' => 'max:20|nullable',
            'date_of_birth' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
        }

        if ($request->profile_photo && !empty($request->profile_photo)) {

            $file_name = FileUploader::upload($request->profile_photo,'public/storage/userimage', 800);

            //Create post for updating profile photo
            $this->create_profile_photo_post($request->profile_photo, $file_name);

            //Update to database
            $data['photo'] = $file_name;
            Cache::forget("user_photo_{$this->user->id}");
        }


        $data['name'] = $request->name;
        $data['nickname'] = $request->nickname;
        $data['marital_status'] = $request->marital_status;
        $data['phone'] = $request->phone;
        $data['date_of_birth'] = strtotime($request->date_of_birth);
        $data['state_id'] = $request->state;
        $data['city_id'] = $request->city;
        $data['area_id'] = $request->area;
        Users::where('id', $this->user->id)->update($data);

         if (auth()->user()){
            app(UserActivityService::class)->log(auth()->user()->id, 'profile_update', 'profile',$this->user->id,$this->user->id);
        }

        //Ajax flush message
        Session::flash('success_message', get_phrase('Profile updated successfully'));
        return redirect()->route('profile');

    }

    function create_profile_photo_post($image, $file_name)
    {

        FileUploader::upload($image,'public/storage/post/images/'.$file_name, 800);

        $data['user_id'] = $this->user->id;
        $data['privacy'] = 'public';
        $data['publisher'] = 'post';
        $data['publisher_id'] = $this->user->id;
        $data['post_type'] = 'profile_picture';
        $data['tagged_user_ids'] = json_encode(array());
        $data['activity_id'] = 0;
        $data['location'] = '';
        $data['description'] = '';
        $data['status'] = 'active';
        $data['user_reacts'] = json_encode(array());
        $data['created_at'] = time();
        $data['updated_at'] = $data['created_at'];
        $post_id = Posts::insertGetId($data);

        //Stored to media files table 
        $media_file_data = array('user_id' => $this->user->id, 'post_id' => $post_id, 'file_name' => $file_name, 'file_type' => 'image', 'privacy' => 'public');
        $media_file_data['created_at'] = time();
        $media_file_data['updated_at'] = $media_file_data['created_at'];
        Media_files::create($media_file_data);
    }

    function create_cover_photo_post($image, $file_name)
    {
        $post = new Posts();
        $post->user_id = $this->user->id;
        $post->publisher = 'post';
        $post->privacy = 'public';
        $post->post_type = 'cover_photo';
        $post->post_content = 'Updated cover photo';
        $post->post_image = $file_name;
        $post->status = 'active';
        $post->save();

        // Log activity
        if (auth()->user()){
            app(UserActivityService::class)->log(auth()->user()->id, 'profile_update', 'profile', auth()->user()->id, auth()->user()->id);
        }
    }

    /**
     * Track profile view
     */
    public function trackView()
    {
        if (auth()->check()) {
            app(UserActivityService::class)->log(
                auth()->user()->id, 
                'view', 
                'profile', 
                auth()->user()->id, 
                auth()->user()->id
            );
        }
        
        return response()->json(['success' => true]);
    }

    /**
     * Upload video for profile
     */
    public function upload_video(Request $request)
    {
        // Validation Rules
        $rules = [
            'video' => 'required|file|mimes:mp4,mov,wmv,mkv,webm,avi,m4v|max:500000', // 500MB limit
            'description' => 'nullable|string|max:1000',
            'privacy' => 'required|string|in:public,private'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['validationError' => $validator->getMessageBag()->toArray()]);
        }

        // Store Original Video
        $originalVideo = $request->file('video');
        $fileName = time() . '_' . $this->user->id . '.' . $originalVideo->getClientOriginalExtension();
        $originalVideo->move(public_path('storage/videos/'), $fileName);

        // Create Post Entry
        $post = new Posts();
        $post->user_id = $this->user->id;
        $post->publisher = 'post';
        $post->publisher_id = $this->user->id;
        $post->post_type = 'video';
        $post->privacy = $request->privacy;
        $post->description = $request->description ?: '';
        $post->tagged_user_ids = json_encode([]);
        $post->user_reacts = json_encode([]);
        $post->status = 'active';
        $post->created_at = now();
        $post->updated_at = now();
        $post->save();

        // Store to media files table
        $media_file_data = [
            'user_id' => $this->user->id, 
            'post_id' => $post->post_id, 
            'file_name' => $fileName, 
            'file_type' => 'video', 
            'privacy' => $request->privacy,
            'created_at' => time(),
            'updated_at' => time()
        ];
        Media_files::create($media_file_data);

        // Log activity
        if (auth()->user()){
            app(UserActivityService::class)->log(auth()->user()->id, 'video_post', 'profile', $post->post_id, $post->post_id);
        }

        // Success Message
        Session::flash('success_message', get_phrase('Video uploaded successfully'));
        
        return response()->json(['reload' => 1]);
    }
}
