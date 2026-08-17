<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use Session;

//used models
use App\Models\FileUploader;
use App\Models\{Stories, Media_files};

class StoryController extends Controller
{
    private $user;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth()->user();
            return $next($request);
        });
    }

    function stories($offset = 0, $limit = 5)
    {

        $stories = DB::table('stories')
            ->join('users', 'stories.user_id', '=', 'users.id')
            ->leftJoin('media_files', 'stories.story_id', '=', 'media_files.story_id')
            ->select(
                'stories.*',
                'users.name',
                'users.photo',
                'media_files.file_name',
                'media_files.file_type'
            )
            ->where(function ($query) {
                $query->whereJsonContains('users.friends', [$this->user->id])
                    ->orWhere('stories.user_id', $this->user->id);
            })
            ->where('stories.status', 'active')
            ->where('stories.created_at', '>=', (time() - 86400))
            ->groupBy('stories.story_id')
            ->orderBy('stories.story_id', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get();





        // //Stories
        // $stories =  DB::table('stories')
        //     ->join('users', 'stories.user_id', '=', 'users.id')
        //     ->select('stories.*', 'users.name', 'users.photo', 'users.friends', 'stories.created_at as created_at')
        //     ->where(function ($query) {
        //         $query->whereJsonContains('users.friends', [$this->user->id])
        //             ->where('stories.privacy', '!=', 'private')
        //             ->orWhere('stories.user_id', $this->user->id);
        //     })
        //     ->where('stories.status', 'active')
        //     ->where('stories.created_at', '>=', (time() - 86400))
        //     ->skip($offset)->take($limit)->orderBy('stories.story_id', 'DESC')->get();

        $page_data['stories'] = $stories;
        return view('frontend.story.single_story', $page_data);
    }

    function story_details($story_id = "", $offset = 0, $limit = 10)
    {

        //First 10 stories
        $stories =  DB::table('stories')
            ->join('users', 'stories.user_id', '=', 'users.id')
            ->select('stories.*', 'users.name', 'users.photo', 'users.friends', 'stories.created_at as created_at')
            ->where(function ($query) {
                $query->whereJsonContains('users.friends', [$this->user->id])
                    ->orWhere('stories.user_id', $this->user->id);
            })
            ->where('stories.privacy', '!=', 'private')
            ->where('stories.created_at', '>=', (time() - 86400))
            ->where('stories.status', 'active')
            ->whereNotIn('stories.story_id', [$story_id])->orderBy('stories.story_id', 'DESC')->get();

        //Stories
        $story_details =  DB::table('stories')
            ->select('stories.*', 'users.name', 'users.photo', 'users.friends', 'stories.created_at as created_at')
            ->join('users', 'stories.user_id', '=', 'users.id')
            ->where('stories.story_id', $story_id)->get()->first();

        $page_data['stories'] = $stories;
        $page_data['story_details'] = $story_details;
        return view('frontend.story.story_details', $page_data);
    }


    function single_story_details($story_id = "")
    {
        // First find the story to identify the user
        $initial_story = DB::table('stories')->where('story_id', $story_id)->first();
        if(!$initial_story) return "Story not found.";

        // Fetch all active stories for this user within 24 hours
        // Instagram-style: Oldest first (ASC)
        $user_stories = DB::table('stories')
            ->select('stories.*', 'users.name', 'users.photo')
            ->join('users', 'stories.user_id', '=', 'users.id')
            ->where('stories.user_id', $initial_story->user_id)
            ->where('stories.status', 'active')
            ->where('stories.created_at', '>=', (time() - 86400))
            ->orderBy('stories.created_at', 'ASC')
            ->get();

        // Optimization: Fetch all media for these stories in ONE query to avoid N+1
        $story_ids = $user_stories->pluck('story_id');
        $all_media = DB::table('media_files')
            ->whereIn('story_id', $story_ids)
            ->get()
            ->groupBy('story_id');

        // Attach media to each story object
        foreach($user_stories as $story) {
            $story->media_list = $all_media->get($story->story_id, collect());
        }

        $page_data['user_stories'] = $user_stories;
        $page_data['initial_story_id'] = $story_id;

        return view('frontend.story.single_story_details', $page_data);
    }







    // function single_story_details($story_id = "")
    // {
    //     //Stories
    //     $story_details =  DB::table('stories')
    //         ->select('stories.*', 'users.name', 'users.photo', 'users.friends', 'stories.created_at as created_at')
    //         ->join('users', 'stories.user_id', '=', 'users.id')
    //         ->where('stories.story_id', $story_id)->get()->first();

    //     $page_data['story_details'] = $story_details;
    //     return view('frontend.story.single_story_details', $page_data);
    // }

    function create_story(Request $request)
    {
        $all_data = $request->all();   // 👈 ADD THIS

        $data['publisher'] = $all_data['publisher'];
        $data['content_type'] = $all_data['content_type'];

        if ($request->publisher == 'user') {
            $data['publisher_id'] = $this->user->id;
        } else {
            $data['publisher_id'] = $this->user->id;
        }

        if ($request->content_type == 'text') {

            if (!empty($request->description)) {
                $data['description'] = json_encode(
                    array('color' => $all_data['color'], 'bg-color' => $all_data['bg-color'], 'text' => $all_data['description'])
                );
            } else {
                return redirect('/home');
            }
        }

        $data['privacy'] = $request->privacy;
        $data['created_at'] = time();
        $data['updated_at'] = $data['created_at'];
        $data['user_id'] = $this->user->id;
        $data['status'] = 'active';
        $story_id = Stories::insertGetId($data);

        if ($request->content_type != 'text') {
            // Ensure directories exist
            if (!File::isDirectory(public_path('storage/story/images'))) {
                File::makeDirectory(public_path('storage/story/images'), 0777, true, true);
            }
            if (!File::isDirectory(public_path('storage/story/videos'))) {
                File::makeDirectory(public_path('storage/story/videos'), 0777, true, true);
            }

            //add media files
            if($request->hasFile('story_files')){
                foreach ($request->file('story_files') as $media_file) {
                    $file_extention = strtolower($media_file->getClientOriginalExtension());
                    if ($file_extention == 'avi' || $file_extention == 'mp4' || $file_extention == 'webm' || $file_extention == 'mov' || $file_extention == 'wmv' || $file_extention == 'mkv') {
                        $file_name = FileUploader::upload($media_file, public_path('storage/story/videos'));
                        $file_type = 'video';
                    } else {
                        $file_name = FileUploader::upload($media_file, public_path('storage/story/images'), 800);
                        $file_type = 'image';
                    }

                    $media_file_data = array(
                        'user_id' => $this->user->id, 
                        'story_id' => $story_id, 
                        'file_name' => $file_name, 
                        'file_type' => $file_type, 
                        'privacy' => $request->privacy ?? 'public'
                    );
                    $media_file_data['created_at'] = time();
                    $media_file_data['updated_at'] = $media_file_data['created_at'];
                    
                    Media_files::create($media_file_data);
                }
            }
        }

        if ($request->ajax()) {
            Session::flash('success_message', get_phrase('Story has been published'));
            return json_encode(array('reload' => 1));
        } else {
            Session::flash('success_message', get_phrase('Story has been published'));
            return redirect()->back();
        }
    }
}