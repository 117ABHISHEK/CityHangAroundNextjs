<?php

namespace App\Http\Controllers;

use App\Models\FileUploader;
use App\Models\Media_files;
use App\Models\StoryView;
use App\Models\Stories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class StoryController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    private function activeTimestamp(): int
    {
        return now()->timestamp - 86400;
    }

    private function storyLifetimeEndsAt(): int
    {
        return now()->timestamp + 86400;
    }

    private function isAdmin(): bool
    {
        return (string) ($this->user->user_role ?? '') === 'admin';
    }

    private function applyActiveStoryFilter($query)
    {
        return $query->where(function ($builder) {
            $builder->whereNotNull('stories.expires_at')
                ->where('stories.expires_at', '>=', now()->timestamp)
                ->orWhere(function ($fallback) {
                    $fallback->whereNull('stories.expires_at')
                        ->where('stories.created_at', '>=', $this->activeTimestamp());
                });
        });
    }

    private function applyVisibilityFilter($query, int $viewerId)
    {
        return $query->where(function ($builder) use ($viewerId) {
            $builder->where('stories.user_id', $viewerId)
                ->orWhere('stories.privacy', 'public')
                ->orWhere(function ($friendsQuery) use ($viewerId) {
                    $friendsQuery->where('stories.privacy', 'friends')
                        ->whereJsonContains('users.friends', [$viewerId]);
                });
        });
    }

    private function baseVisibleStoryQuery(int $viewerId)
    {
        $query = DB::table('stories')
            ->join('users', 'stories.user_id', '=', 'users.id')
            ->where('stories.status', 'active');

        // IMPORTANT:
        // This builder is used inside fromSub() (derived table). Do NOT allow implicit select(*)
        // with joins because both `stories` and `users` have duplicate column names like `status`.
        // Select only the columns needed for grouping (user_id/story_id/created_at).
        $query->select(
            'stories.story_id',
            'stories.user_id',
            'stories.created_at'
        );

        $this->applyActiveStoryFilter($query);
        $this->applyVisibilityFilter($query, $viewerId);

        return $query;
    }

    private function attachStoryMedia($stories)
    {
        if ($stories->isEmpty()) {
            return $stories;
        }

        $mediaByStory = DB::table('media_files')
            ->whereIn('story_id', $stories->pluck('story_id'))
            ->orderBy('id', 'ASC')
            ->get()
            ->groupBy('story_id');

        foreach ($stories as $story) {
            $story->media_items = $mediaByStory->get($story->story_id, collect())->values();
            $story->preview_media = $story->media_items->first();
        }

        return $stories;
    }

    private function loadUserStories(int $storyOwnerId, int $viewerId, bool $bypassVisibility = false)
    {
        $query = DB::table('stories')
            ->join('users', 'stories.user_id', '=', 'users.id')
            ->where('stories.user_id', $storyOwnerId)
            ->where('stories.status', 'active');

        $this->applyActiveStoryFilter($query);
        if (!$bypassVisibility) {
            $this->applyVisibilityFilter($query, $viewerId);
        }

        $stories = $query
            // Never select('stories.*') when joining tables; be explicit to avoid duplicate/ambiguous columns.
            ->select(
                'stories.story_id',
                'stories.user_id',
                'stories.publisher',
                'stories.publisher_id',
                'stories.privacy',
                'stories.content_type',
                'stories.description',
                'stories.created_at',
                'stories.updated_at',
                'stories.expires_at',
                'stories.status',
                'users.name',
                'users.photo',
                'users.friends'
            )
            ->orderBy('stories.created_at', 'ASC')
            ->orderBy('stories.story_id', 'ASC')
            ->get();

        return $this->attachStoryMedia($stories);
    }

    private function loadSingleStoryById(int $storyId, int $viewerId, bool $bypassVisibility = false)
    {
        $story = DB::table('stories')
            ->join('users', 'stories.user_id', '=', 'users.id')
            ->where('stories.story_id', $storyId)
            ->where('stories.status', 'active');

        $this->applyActiveStoryFilter($story);
        if (!$bypassVisibility) {
            $this->applyVisibilityFilter($story, $viewerId);
        }

        // Never select('stories.*') when joining tables; be explicit to avoid duplicate/ambiguous columns.
        $story = $story->select(
            'stories.story_id',
            'stories.user_id',
            'stories.publisher',
            'stories.publisher_id',
            'stories.privacy',
            'stories.content_type',
            'stories.description',
            'stories.created_at',
            'stories.updated_at',
            'stories.expires_at',
            'stories.status',
            'users.name',
            'users.photo',
            'users.friends'
        )
            ->first();

        if (!$story) {
            return null;
        }

        $storyCollection = collect([$story]);
        return $this->attachStoryMedia($storyCollection)->first();
    }

    private function storyRailQuery(int $viewerId)
    {
        $visibleStories = $this->baseVisibleStoryQuery($viewerId);

        $latestPerUser = DB::query()
            ->fromSub($visibleStories, 'visible_stories')
            ->selectRaw('user_id, COUNT(*) as story_count, MAX(story_id) as latest_story_id, MAX(CAST(created_at AS UNSIGNED)) as latest_story_time')
            ->groupBy('user_id');

        return DB::table('stories')
            ->join('users', 'stories.user_id', '=', 'users.id')
            ->joinSub($latestPerUser, 'story_groups', function ($join) {
                $join->on('stories.story_id', '=', 'story_groups.latest_story_id');
            })
            ->select(
                'stories.story_id',
                'stories.user_id',
                'stories.publisher',
                'stories.publisher_id',
                'stories.privacy',
                'stories.content_type',
                'stories.description',
                'stories.created_at',
                'stories.updated_at',
                'stories.expires_at',
                'stories.status',
                'users.name',
                'users.photo',
                'story_groups.story_count',
                'story_groups.latest_story_time'
            )
            ->orderByDesc('story_groups.latest_story_time')
            ->orderByDesc('stories.story_id');
    }

    public function stories($offset = 0, $limit = 5)
    {
        $stories = $this->storyRailQuery($this->user->id)
            ->skip((int) $offset)
            ->take((int) $limit)
            ->get();

        $this->attachStoryMedia($stories);

        return view('frontend.story.index', [
            'stories' => $stories,
        ]);
    }

    public function story_details($story_id = "", $offset = 0, $limit = 10)
    {
        $initialStory = $this->loadSingleStoryById((int) $story_id, $this->user->id);

        if (!$initialStory) {
            abort(404);
        }

        $userStories = $this->loadUserStories((int) $initialStory->user_id, $this->user->id);
        $entryStory = $userStories->first() ?: $initialStory;

        $page_data = [
            'initial_story_id' => (int) $entryStory->story_id,
            'story_owner' => $initialStory,
            'user_stories' => $userStories,
            'is_admin' => $this->isAdmin(),
        ];

        return view('frontend.story.viewer', $page_data);
    }

    public function single_story_details($story_id = "")
    {
        $initialStory = $this->loadSingleStoryById((int) $story_id, $this->user->id);

        if (!$initialStory) {
            abort(404);
        }

        $userStories = $this->loadUserStories((int) $initialStory->user_id, $this->user->id);
        $entryStory = $userStories->first() ?: $initialStory;

        return view('frontend.story.viewer', [
            'initial_story_id' => (int) $entryStory->story_id,
            'story_owner' => $initialStory,
            'user_stories' => $userStories,
            'is_admin' => $this->isAdmin(),
        ]);
    }

    public function create_story(Request $request)
    {
        $request->validate([
            'content_type' => ['required', 'in:text,file'],
            'privacy' => ['required', 'in:public,friends,private'],
        ]);

        $commonData = [
            'publisher' => $request->input('publisher', 'user'),
            'publisher_id' => $this->user->id,
            'privacy' => $request->input('privacy', 'public'),
            'user_id' => $this->user->id,
            'status' => 'active',
            'created_at' => now()->timestamp,
            'updated_at' => now()->timestamp,
            'expires_at' => $this->storyLifetimeEndsAt(),
        ];

        $createdStoryIds = [];

        if ($request->content_type === 'text') {
            if (!$request->filled('description')) {
                $message = get_phrase('Please enter story text before publishing.');
                return $request->ajax()
                    ? response()->json(['alertMessage' => $message], 422)
                    : redirect()->back()->with('error_message', $message);
            }

            $story = Stories::create($commonData + [
                'content_type' => 'text',
                'description' => json_encode([
                    'color' => $request->input('color', '000'),
                    'bg-color' => $request->input('bg-color', 'fff'),
                    'text' => $request->input('description'),
                ]),
            ]);

            $createdStoryIds[] = $story->story_id;
        } else {
            $uploadedFiles = $request->file('story_files', []);

            if (empty($uploadedFiles)) {
                $message = get_phrase('Please select at least one photo or video.');
                return $request->ajax()
                    ? response()->json(['alertMessage' => $message], 422)
                    : redirect()->back()->with('error_message', $message);
            }

            foreach ($uploadedFiles as $uploadedFile) {
                if (!$uploadedFile) {
                    continue;
                }

                $fileExtension = strtolower($uploadedFile->getClientOriginalExtension());
                $isVideo = in_array($fileExtension, ['avi', 'mp4', 'webm', 'mov', 'wmv', 'mkv'], true);

                if ($isVideo) {
                    if (!File::isDirectory(public_path('storage/story/videos'))) {
                        File::makeDirectory(public_path('storage/story/videos'), 0777, true, true);
                    }

                    $fileName = FileUploader::upload($uploadedFile, public_path('storage/story/videos'));
                    $fileType = 'video';
                } else {
                    if (!File::isDirectory(public_path('storage/story/images'))) {
                        File::makeDirectory(public_path('storage/story/images'), 0777, true, true);
                    }

                    $fileName = FileUploader::upload($uploadedFile, public_path('storage/story/images'), 1200);
                    $fileType = 'image';
                }

                $story = Stories::create($commonData + [
                    'content_type' => 'file',
                ]);

                $createdStoryIds[] = $story->story_id;

                Media_files::create([
                    'user_id' => $this->user->id,
                    'story_id' => $story->story_id,
                    'file_name' => $fileName,
                    'file_type' => $fileType,
                    'privacy' => $request->input('privacy', 'public'),
                    'created_at' => now()->timestamp,
                    'updated_at' => now()->timestamp,
                ]);
            }
        }

        Session::flash('success_message', get_phrase('Story has been published'));

        $response = [
            'reload' => 1,
            'created_story_ids' => $createdStoryIds,
        ];

        return $request->ajax()
            ? response()->json($response)
            : redirect()->back();
    }

    public function track_view(Request $request, $story_id = null)
    {
        $storyId = (int) ($story_id ?? $request->input('story_id'));

        $story = $this->loadSingleStoryById($storyId, $this->user->id, true);

        if (!$story) {
            return response()->json(['message' => 'Story not found.'], 404);
        }

        StoryView::updateOrCreate(
            [
                'story_id' => $storyId,
                'viewer_id' => $this->user->id,
            ],
            [
                'viewed_at' => now()->timestamp,
            ]
        );

        return response()->json([
            'success' => true,
            'view_count' => StoryView::where('story_id', $storyId)->count(),
        ]);
    }

    public function viewers($story_id = null)
    {
        if (!$this->isAdmin()) {
            abort(403);
        }

        $story = $this->loadSingleStoryById((int) $story_id, $this->user->id, true);

        if (!$story) {
            abort(404);
        }

        $viewers = DB::table('story_views')
            ->join('users', 'story_views.viewer_id', '=', 'users.id')
            ->where('story_views.story_id', (int) $story_id)
            ->select(
                'users.id',
                'users.name',
                'users.photo',
                'story_views.viewed_at'
            )
            ->orderByDesc('story_views.viewed_at')
            ->get();

        return view('frontend.story.viewers', [
            'story' => $story,
            'viewers' => $viewers,
        ]);
    }
}
