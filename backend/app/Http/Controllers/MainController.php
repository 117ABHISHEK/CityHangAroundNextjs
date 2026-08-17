<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Cache;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

//used models
use App\Models\{Stories, Posts, Comments, Feeling_and_activities, CommonModels, Live_streamings, Media_files, Post_share, Report, User, FileUploader};

//For used ZOOM
use App\Traits\ZoomMeetingTrait;

use Session, Image, Share;

//Used for Form data validation
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\JoinClause;
use App\Helpers\CityHelper;

use Illuminate\Support\Facades\URL;
use Artesaos\SEOTools\Facades\SEOMeta;
use App\Services\UserActivityService;
use App\Models\City;
use App\Models\Area;
use App\Models\Blog;
use App\Models\Event;
use App\Models\Marketplace;
use App\Models\Page;
use App\Models\Pagecategory;
use App\Models\Blogcategory;
use App\Models\Eventcategory;
use App\Models\Video;
use App\Models\Setting;
class MainController extends Controller
{
    use ZoomMeetingTrait;

    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    private $user;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth()->user();
            return $next($request);
        });

        if (!headers_sent()) {
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
        }
    }


    function main()
    {
        // Cache data for 1 hour to improve performance
        $page_data = Cache::remember('home_page_data', 3600, function () {
            return [
                'all_cities' => CityHelper::getActiveCities(),
                
                // Eager load state and pre-compute page counts (Approved pages only)
                'top_cities' => City::with('state') 
                    ->where('is_approved', 'Y')
                    ->withCount(['pages' => function ($query) {
                        $query->where('item_status', 2);
                    }])
                    ->orderByDesc('pages_count')
                    ->take(10)
                    ->get(),

                'top_categories' => Pagecategory::where('is_parent', 'Yes')
                    ->where('is_approved', 'Y')
                    ->withCount(['pages' => function ($query) {
                        $query->where('item_status', 2);
                    }])
                    ->orderByDesc('pages_count')
                    ->take(6)
                    ->get(),
                
                // Dynamic stats for the counter section
                'stats' => [
                    'businesses' => Page::where('item_status', 2)->count(),
                    'campaigns' => Marketplace::where('product_status', 2)->count(), 
                    'users' => User::count(),
                ]
            ];
        });

        return view('index', $page_data);
    }


    public function loadCitiesajax(Request $request)
    {
        $filter = trim($request->input('filter'));

        if (empty($filter)) {
            return response()->json(['cities' => []]);
        }

        // If very short search (1-2 chars), strictly use prefix search for index performance
        if (strlen($filter) < 3) {
            $filtered_cities = City::where('is_approved', 'Y')
                ->where('city_name', 'like', $filter . '%')
                ->orderBy('city_name', 'asc')
                ->limit(30)
                ->get(['id', 'city_slug', 'city_name'])
                ->toArray();
        } else {
            // For 3+ characters, fetch prefix matches first, then infix matches
            $prefix_cities = City::where('is_approved', 'Y')
                ->where('city_name', 'like', $filter . '%')
                ->orderBy('city_name', 'asc')
                ->limit(30)
                ->get(['id', 'city_slug', 'city_name'])
                ->toArray();

            $infix_cities = [];
            if (count($prefix_cities) < 30) {
                $exclude_ids = array_column($prefix_cities, 'id');
                $infix_cities = City::where('is_approved', 'Y')
                    ->whereNotIn('id', $exclude_ids)
                    ->where('city_name', 'like', '%' . $filter . '%')
                    ->orderBy('city_name', 'asc')
                    ->limit(30 - count($prefix_cities))
                    ->get(['id', 'city_slug', 'city_name'])
                    ->toArray();
            }

            $filtered_cities = array_merge($prefix_cities, $infix_cities);
        }

        return response()->json(['cities' => $filtered_cities]);
    }

    public function loadAllCitiesJson()
    {
        $cities = \Cache::remember('active_cities_json_v1', 86400, function() {
            return City::where('is_approved', 'Y')
                ->whereHas('pages', function ($q) {
                    $q->where('item_status', 2);
                })
                ->orderBy('city_name', 'asc')
                ->get(['id', 'city_slug', 'city_name'])
                ->toArray();
        });
        return response()->json($cities);
    }

    public function universalSearch(Request $request)
    {
        $page_data['all_cities'] = CityHelper::getActiveCities();

        try {
            $input = strtolower(trim($request->input('search')));
            $tokens = preg_split('/[\s,]+/', $input);

            // Try to detect a city
            $city = City::whereRaw('LOWER(city_name) = ?', [$input])->first();
            $city_id = $city ? $city->id : null;

            foreach ($tokens as $token) {
                if (!$city_id) {
                    $matchedCity = City::whereRaw('LOWER(city_name) = ?', [$token])->first();
                    if ($matchedCity) {
                        $city_id = $matchedCity->id;
                        break;
                    }
                }
            }

            // 🔍 Marketplace
            $marketplaces = Marketplace::with(['page.area.city', 'reviews', 'productCategories', 'page.categories'])
                ->where('product_status', 2)
                ->where(function ($query) use ($tokens, $input) {
                    $query->where('title', 'like', "%$input%");

                    foreach ($tokens as $token) {
                        $query->orWhere('title', 'like', "%$token%")
                            ->orWhereHas(
                                'productCategories',
                                fn($qc) =>
                                $qc->where('product_category_name', 'like', "%$token%")
                            )
                            ->orWhereHas(
                                'page',
                                fn($qp) =>
                                $qp->where('title', 'like', "%$token%")
                                    ->orWhereHas(
                                        'categories',
                                        fn($qcat) =>
                                        $qcat->where('category_name', 'like', "%$token%")
                                    )
                            );
                    }
                })
                ->when(
                    $city_id,
                    fn($q) =>
                    $q->whereHas(
                        'page.city',
                        fn($qc) =>
                        $qc->where('id', $city_id)
                    )
                )
                ->limit(20)
                ->get();

            // 🔍 Blogs
            $blogs = Blog::with(['user', 'city', 'area', 'category'])
                ->where('blog_status', 2)
                ->where(function ($q) use ($tokens, $input) {
                    $q->where('title', 'like', "%$input%")
                        ->orWhere('description', 'like', "%$input%");
                    foreach ($tokens as $token) {
                        $q->orWhere('title', 'like', "%$token%")
                            ->orWhere('description', 'like', "%$token%");
                    }
                })
                ->when(
                    $city_id,
                    fn($q) =>
                    $q->where('city_id', $city_id)
                )
                ->limit(20)
                ->get();

            // 🔍 Events
            $events = Event::with(['city', 'area', 'categories'])
                ->where('event_status', 2)
                ->where(function ($q) use ($tokens, $input) {
                    $q->where('title', 'like', "%$input%")
                        ->orWhere('description', 'like', "%$input%");
                    foreach ($tokens as $token) {
                        $q->orWhere('title', 'like', "%$token%")
                            ->orWhere('description', 'like', "%$token%");
                    }
                })
                ->when(
                    $city_id,
                    fn($q) =>
                    $q->where('city_id', $city_id)
                )
                ->limit(20)
                ->get();

            // 🔍 Pages
            $pages = Page::with(['city', 'area', 'pageCategories'])
                ->where(function ($q) use ($tokens, $input) {
                    $q->where('title', 'like', "%$input%");
                    foreach ($tokens as $token) {
                        $q->orWhere('title', 'like', "%$token%");
                    }
                })
                ->when(
                    $city_id,
                    fn($q) =>
                    $q->where('city_id', $city_id)
                )
                ->limit(20)
                ->get();

            $page_data['blogs'] = $blogs;
            $page_data['events'] = $events;
            $page_data['products'] = $marketplaces;
            $page_data['mypages'] = $pages;
            $page_data['view_path'] = 'frontend.searchs.index';

            return view('frontend.index', $page_data);

        } catch (\Exception $e) {
            \Log::error("Universal Search Error: " . $e->getMessage());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }







    function timeline()
    {

        SEOMeta::setTitle('CityHangaround – Local City Guide, Business Listings, Deals & Events');
        SEOMeta::setDescription('Discover top local businesses, best deals, trending events, and city updates on CityHangaround. Your one-stop social marketplace for products, services, blogs, and influencer content across Indian cities');
        SEOMeta::setCanonical(URL::current());
        $page_data['all_cities'] = CityHelper::getActiveCities();

        // User's own latest story
        $page_data['my_story'] = Stories::where('user_id', $this->user->id)
            ->where('status', 'active')
            ->where('created_at', '>=', (time() - 86400))
            ->orderBy('story_id', 'DESC')
            ->first();

        // Optimize: Fetch only the latest active story ID per user to avoid fetching thousands and filtering in PHP
        $latestStoryIds = Stories::where('status', 'active')
            ->where('created_at', '>=', (time() - 86400))
            ->groupBy('user_id')
            ->selectRaw('MAX(story_id) as story_id');

        // Friends stories (excluding self) - optimized with subquery
        $stories = Stories::whereIn('stories.story_id', $latestStoryIds)
            ->join('users', 'stories.user_id', '=', 'users.id')
            ->whereJsonContains('users.friends', [$this->user->id])
            ->where('stories.user_id', '!=', $this->user->id)
            ->where('stories.privacy', '!=', 'private')
            ->select('stories.*', 'users.name', 'users.photo')
            ->with('mediaFiles')
            ->orderBy('stories.story_id', 'DESC')
            ->get();

        // Timeline Feed (Left Column) - Works for guests & authenticated users
        $postsQuery = Posts::where('posts.status', 'active')
            ->where('posts.report_status', '0')
            ->join('users', 'posts.user_id', '=', 'users.id');

        if (auth()->check()) {
            $userId = auth()->id();
            $postsQuery->where(function ($query) use ($userId) {
                $query->whereJsonContains('users.friends', [$userId])
                    ->where('posts.privacy', '!=', 'private')
                    ->orWhere('posts.user_id', $userId)
                    ->orWhere('posts.privacy', 'public');
            });
        } else {
            $postsQuery->where('posts.privacy', 'public');
        }

        $posts = $postsQuery->whereIn('posts.publisher', ['post', 'page', 'group', 'event'])
            ->where('posts.publisher', '!=', 'video_and_shorts')
            ->with([
                'getUser',
                'media_files',
                'page.city',
                'page.area',
                'page.categories',
                'group.city',
                'group.area',
                'group.category',
                'event.city',
                'event.area',
                'event.category'
            ])
            ->select('posts.*', 'users.name', 'users.photo', 'users.friends', 'posts.created_at as created_at')
            ->orderByDesc('posts.created_at')
            ->orderByDesc('posts.post_id')
            ->take(5)
            ->get();

        try {
            $recentData = Cache::remember('timeline_recent_listings_v3', 300, function () {
                // 1. Businesses (Trending Listings)
                $businesses = Page::with(['city', 'area', 'categories'])
                    ->where('item_status', 2)
                    ->orderBy('created_at', 'DESC')
                    ->take(3)
                    ->get()
                    ->map(function ($item) {
                        return (object)[
                            'logo' => $item->logo,
                            'title' => $item->title,
                            'item_slug' => $item->item_slug,
                            'city' => (object)['city_slug' => optional($item->city)->city_slug],
                            'area' => (object)['area_slug' => optional($item->area)->area_slug],
                            'categories' => collect([
                                (object)[
                                    'category_slug' => optional($item->categories->first())->category_slug,
                                    'name' => optional($item->categories->first())->name,
                                ]
                            ]),
                            'category' => (object)['name' => optional($item->category)->name],
                        ];
                    });

                // 2. Products (Trending Deals)
                $products = Marketplace::with(['page.city', 'page.area', 'page.categories', 'productCategories'])
                    ->where('product_status', 2)
                    ->orderBy('created_at', 'DESC')
                    ->take(3)
                    ->get()
                    ->map(function ($item) {
                        $page = $item->page;
                        $firstCat = optional($page)->categories ? optional($page->categories)->first() : null;
                        $prodLastCat = optional($item->productCategories)->last();
                        return (object)[
                            'title' => $item->title,
                            'image' => $item->image,
                            'product_slug' => $item->product_slug,
                            'page' => $page ? (object)[
                                'city' => (object)['city_slug' => optional($page->city)->city_slug],
                                'area' => (object)['area_slug' => optional($page->area)->area_slug],
                                'item_slug' => $page->item_slug,
                                'categories' => collect([
                                    (object)[
                                        'category_slug' => optional($firstCat)->category_slug,
                                        'category_name' => optional($firstCat)->category_name,
                                    ]
                                ]),
                            ] : null,
                            'productCategories' => collect([
                                (object)[
                                    'product_category_slug' => optional($prodLastCat)->product_category_slug,
                                ]
                            ]),
                        ];
                    });

                // 3. Events (Trending Events)
                $events = Event::with(['city', 'area', 'categories'])
                    ->where('event_status', 2)
                    ->orderBy('created_at', 'DESC')
                    ->take(3)
                    ->get()
                    ->map(function ($item) {
                        return (object)[
                            'id' => $item->id,
                            'title' => $item->title,
                            'banner' => $item->banner,
                            'event_slug' => $item->event_slug,
                            'city' => (object)['city_slug' => optional($item->city)->city_slug],
                            'area' => (object)['area_slug' => optional($item->area)->area_slug],
                            'categories' => collect([
                                (object)[
                                    'category_slug' => optional($item->categories->first())->category_slug,
                                    'name' => optional($item->categories->first())->name,
                                ]
                            ]),
                            'category' => (object)['name' => optional($item->category)->name],
                        ];
                    });

                return [
                    'businesses' => $businesses,
                    'products' => $products,
                    'events' => $events,
                ];
            });

            $recentBusinesses = $recentData['businesses'];
            $recentProducts = $recentData['products'];
            $recentEvents = $recentData['events'];
            $recentBlogs = collect();
            $recentVideos = collect();
        } catch (\Exception $e) {
            // If there's an error, set empty collections
            $recentBusinesses = collect();
            $recentProducts = collect();
            $recentBlogs = collect();
            $recentEvents = collect();
            $recentVideos = collect();

            \Log::error('Error fetching recent listings: ' . $e->getMessage());
        }

        $userId = auth()->id();
        $followedUserIds = [];
        if (auth()->check()) {
            $followedUserIds = \App\Models\Follower::where('user_id', $userId)->pluck('follow_id')->toArray();
        }

        $postIds = collect($posts)->pluck('post_id')->filter()->unique()->values()->all();
        $postShareCounts = [];
        if (!empty($postIds)) {
            $postShareCounts = DB::table('post_shares')
                ->whereIn('post_id', $postIds)
                ->select('post_id', DB::raw('COUNT(*) as total'))
                ->groupBy('post_id')
                ->pluck('total', 'post_id')
                ->toArray();
        }

        $page_data['stories'] = $stories;
        $page_data['posts'] = $posts;
        $page_data['type'] = 'user_post';
        $page_data['followed_user_ids'] = $followedUserIds;
        $page_data['post_share_counts'] = $postShareCounts;
        // PERFORMANCE: avoid N+1 comment queries inside Blade
        $page_data = array_merge($page_data, $this->getPostCommentAggregates($posts));
        $page_data['recentBusinesses'] = $recentBusinesses;
        $page_data['recentProducts'] = $recentProducts;
        $page_data['recentBlogs'] = $recentBlogs;
        $page_data['recentEvents'] = $recentEvents;
        $page_data['recentVideos'] = $recentVideos;
        $page_data['view_path'] = 'frontend.main_content.index';
        return view('frontend.index', $page_data);
    }

    function load_post_by_scrolling(Request $request)
    {

        $postsQuery = Posts::where('posts.status', 'active')
            ->where('posts.report_status', '0')
            ->join('users', 'posts.user_id', '=', 'users.id');

        if (auth()->check()) {
            $userId = auth()->id();
            $postsQuery->where(function ($query) use ($userId) {
                $query->whereJsonContains('users.friends', [$userId])
                    ->where('posts.privacy', '!=', 'private')
                    ->orWhere('posts.user_id', $userId)
                    ->orWhere('posts.privacy', 'public');
            });
        } else {
            $postsQuery->where('posts.privacy', 'public');
        }

        $posts = $postsQuery->whereIn('posts.publisher', ['post', 'page', 'group', 'event'])
            ->where('posts.publisher', '!=', 'video_and_shorts')
            ->with([
                'getUser',
                'media_files',
                'page.city',
                'page.area',
                'page.categories',
                'group.city',
                'group.area',
                'group.category',
                'event.city',
                'event.area',
                'event.category'
            ])
            ->select('posts.*', 'users.name', 'users.photo', 'users.friends', 'posts.created_at as created_at')
            ->orderByDesc('posts.created_at')
            ->orderByDesc('posts.post_id')
            ->skip($request->offset)
            ->take(3)
            ->get();

        $userId = auth()->id();
        $followedUserIds = [];
        if (auth()->check()) {
            $followedUserIds = \App\Models\Follower::where('user_id', $userId)->pluck('follow_id')->toArray();
        }

        $postIds = collect($posts)->pluck('post_id')->filter()->unique()->values()->all();
        $postShareCounts = [];
        if (!empty($postIds)) {
            $postShareCounts = DB::table('post_shares')
                ->whereIn('post_id', $postIds)
                ->select('post_id', DB::raw('COUNT(*) as total'))
                ->groupBy('post_id')
                ->pluck('total', 'post_id')
                ->toArray();
        }

        $page_data['user_info'] = $this->user;
        $page_data['posts'] = $posts;
        $page_data['type'] = 'user_post';
        $page_data['followed_user_ids'] = $followedUserIds;
        $page_data['post_share_counts'] = $postShareCounts;
        // PERFORMANCE: avoid N+1 comment queries inside Blade
        $page_data = array_merge($page_data, $this->getPostCommentAggregates($posts));
        return view('frontend.main_content.posts', $page_data);
    }

    /**
     * Build comment counts + latest main-comment per post in a couple queries (performance).
     */
    private function getPostCommentAggregates($posts): array
    {
        $postIds = collect($posts)->pluck('post_id')->filter()->unique()->values()->all();
        if (empty($postIds)) {
            return ['comment_counts' => [], 'latest_comments' => []];
        }

        // Total comments per post (main + sub)
        $commentCounts = DB::table('comments')
            ->select('id_of_type', DB::raw('COUNT(*) as total'))
            ->where('is_type', 'post')
            ->whereIn('id_of_type', $postIds)
            ->groupBy('id_of_type')
            ->pluck('total', 'id_of_type')
            ->toArray();

        // Latest main comment per post (parent_id = 0)
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
                // Blade expects a collection (it calls ->count(), loops, etc.)
                $latestComments[$row->id_of_type] = collect([$row]);
            }
        }

        return [
            'comment_counts' => $commentCounts,
            'latest_comments' => $latestComments,
        ];
    }

    function create_post(Request $request)
    {
        $user_id = $this->user->id;
        $lockKey = 'post_lock_' . $user_id;

        /**
         * Backend duplicate request protection (atomic lock)
         * - Cache::add is atomic across supported drivers (prevents race condition)
         * - Lock is short-lived so users can post again quickly
         */
        if (!Cache::add($lockKey, true, 10)) {
            return json_encode(array('alertMessage' => get_phrase('Your post is already being processed. Please wait.')));
        }

        $submission_id = $request->input('submission_id');
        if ($submission_id) {
            $uuidLockKey = 'post_uuid_lock_' . $submission_id;
            if (!Cache::add($uuidLockKey, true, 60)) {
                return json_encode(array('alertMessage' => get_phrase('Duplicate submission detected.')));
            }
        }

        try {
            // PERFORMANCE: Cache spam words (avoid DB hit each post)
            $spamWords = Cache::remember('spam_words_v1', 3600, function () {
                $spamWordsString = DB::table('spam_words')->pluck('word')->first();
                if (!$spamWordsString) {
                    return [];
                }
                return array_values(array_filter(array_map('trim', explode(',', strtolower($spamWordsString)))));
            });

            if (!empty($spamWords)) {
                $description = strtolower(trim((string) $request->description));
                foreach ($spamWords as $spamWord) {
                    if ($spamWord !== '' && str_contains($description, $spamWord)) {
                        Session::flash('spam_error', 'Your post contains restricted words. Please remove them.');
                        return response()->json(['reload' => 1]);
                    }
                }
            }

            //Data validation
            $rules = array('privacy' => ['required', Rule::in(['private', 'public', 'friends'])]);
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
            }

            if (is_array($request->multiple_files) && $request->multiple_files[0] != null) {
                //Data validation
                $rules = array('multiple_files.*' => 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,wmv,avi,webm|max:500000');
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $validation_errors = $validator->getMessageBag()->toArray();
                    foreach ($validation_errors as $key => $validation_error) {
                        $fileIndex = explode('.', $key);
                        if (array_key_exists('multiple_files.' . $fileIndex[1], $validation_errors)) {
                            $validation_errors['multiple_files'] = $validation_errors['multiple_files.' . $fileIndex[1]];
                        }
                        unset($validation_errors['multiple_files.' . $fileIndex[1]]);
                    }

                    return json_encode(array('validationError' => $validation_errors));
                }
            }

            // DB transaction safety
            DB::beginTransaction();

            $data['user_id'] = $this->user->id;
            $data['privacy'] = $request->privacy;

            if (isset($request->publisher) && !empty($request->publisher)) {
                $data['publisher'] = $request->publisher;
            } else {
                $data['publisher'] = 'post';
            }

            if (isset($request->event_id) && !empty($request->event_id)) {
                $data['publisher_id'] = $request->event_id;
            } elseif (isset($request->page_id) && !empty($request->page_id)) {
                $data['publisher_id'] = $request->page_id;
            } elseif (isset($request->group_id) && !empty($request->group_id)) {
                $data['publisher_id'] = $request->group_id;
            } else {
                $data['publisher_id'] = $this->user->id;
            }

            //post type
            if (isset($request->post_type) && !empty($request->post_type)) {
                $data['post_type'] = $request->post_type;
            } else {
                $data['post_type'] = 'general';
            }

            if (isset($request->tagged_users_id) && is_array($request->tagged_users_id)) {
                $tagged_users = $request->tagged_users_id;
            } else {
                $tagged_users = array();
            }
            $data['tagged_user_ids'] = json_encode($tagged_users);

            if (isset($request->feeling_and_activity_id) && !empty($request->feeling_and_activity_id)) {
                $data['activity_id'] = $request->feeling_and_activity_id;
            } else {
                $data['activity_id'] = 0;
            }

            if (isset($request->address) && !empty($request->address)) {
                $data['location'] = $request->address;
            } else {
                $data['location'] = '';
            }

            if (isset($request->description) && !empty($request->description)) {
                $data['description'] = $request->description;
            } else {
                $data['description'] = '';
            }

            $data['status'] = 'active';
            $data['user_reacts'] = json_encode(array());
            $data['shared_user'] = json_encode(array());
            $data['created_at'] = time();
            $data['updated_at'] = $data['created_at'];

            // Auto detect city/area/product, route to community, and add badges
            Posts::autoRouteAndTagPost($data);

            $post_id = Posts::insertGetId($data);

            if (auth()->user()) {
                app(UserActivityService::class)->log(auth()->user()->id, 'post', $data['publisher'], $data['publisher_id'], $post_id);
            }

            //add media files
            if (is_array($request->multiple_files) && $request->multiple_files[0] != null) {
                foreach ($request->multiple_files as $key => $media_file) {
                    $file_name = random(40);
                    $file_extention = strtolower($media_file->getClientOriginalExtension());
                    if ($file_extention == 'avi' || $file_extention == 'mp4' || $file_extention == 'webm' || $file_extention == 'mov' || $file_extention == 'wmv' || $file_extention == 'mkv') {
                        $file_name = FileUploader::upload($media_file, 'public/storage/post/videos/' . $file_name . '.' . $file_extention);
                        $file_type = 'video';
                    } else {
                        $file_name = FileUploader::upload($media_file, 'public/storage/post/images/' . $file_name . '.' . $file_extention, 1000, null, 300);
                        $file_type = 'image';
                    }

                    $media_file_data = array(
                        'user_id' => auth()->user()->id, 
                        'post_id' => $post_id, 
                        'file_name' => $file_name, 
                        'file_type' => $file_type, 
                        'privacy' => $request->privacy
                    );

                    if (isset($request->page_id) && !empty($request->page_id)) {
                        $media_file_data['page_id'] = $request->page_id;
                    } elseif (isset($request->group_id) && !empty($request->group_id)) {
                        $media_file_data['group_id'] = $request->group_id;
                    }

                    $media_file_data['created_at'] = time();
                    $media_file_data['updated_at'] = $media_file_data['created_at'];
                    Media_files::create($media_file_data);
                }
            }

            DB::commit();

            // Avoid extra DB query: build the post payload from known data
            $post = (object) array_merge($data, [
                'post_id' => $post_id,
                'name' => $this->user->name,
                'photo' => $this->user->photo,
                'friends' => $this->user->friends,
                'created_at' => $data['created_at'],
            ]);

            if ($data['post_type'] == 'live_streaming') {
                //Live streaming
                $this->create_live_streaming('post', $post_id);
                $response = array(
                    'url' => url('/live/' . $post_id), 
                    'reload' => 0, 
                    'status' => 1, 
                    'function' => 0, 
                    'messageShowOn' => '[name=about]', 
                    'message' => get_phrase('Post has been added to your timeline')
                );
            } else {
                // Render the new post HTML — wrapped separately so a render failure
                // never masks the fact that the post was successfully saved to DB.
                try {
                    $posts = collect([$post]);
                    $post_html = view('frontend.main_content.posts', [
                        'posts' => $posts,
                        'type' => 'user_post',
                        'comment_counts' => [$post_id => 0],
                        'latest_comments' => [$post_id => collect()],
                    ])->render();

                    $response = array(
                        'prependElement' => '#timeline-posts, #profile-timeline-posts, #user-timeline-posts',
                        'content' => $post_html,
                        'alertMessage' => get_phrase('Your post has been published successfully'),
                        'reload' => 0
                    );
                } catch (\Exception $renderEx) {
                    // Post IS saved — render just failed. Reload the page so user sees it.
                    \Log::warning('Post #' . $post_id . ' created but HTML render failed: ' . $renderEx->getMessage());
                    $response = array(
                        'alertMessage' => get_phrase('Your post has been published successfully'),
                        'reload' => 1
                    );
                }
            }

            Cache::forget("posts_{$request->page_id}");

        } catch (\Exception $e) {
            DB::rollBack();
            $detailedMsg = 'Failed to create post. Error: ' . $e->getMessage() . ' in ' . basename($e->getFile()) . ' on line ' . $e->getLine();
            $response = array('alertMessage' => get_phrase($detailedMsg));
            \Log::error('Create Post Error: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
        } finally {
            Cache::forget($lockKey);
        }

        return json_encode($response);
    }

    function edit_post_form($id)
    {
        $page_data['post'] = Posts::where('post_id', $id)->first();
        return view('frontend.main_content.edit_post_modal', $page_data);
    }


    function edit_post($id, Request $request)
    {
        //$posts = Posts::where('id', $id)->first();

        $rules = array('privacy' => ['required', Rule::in(['private', 'public', 'friends'])]);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
        }


        if (is_array($request->multiple_files) && $request->multiple_files[0] != null) {
            //Data validation

            $rules = array('multiple_files.*' => 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,wmv,avi,webm|max:20480');
            // $rules = array('multiple_files.*' => 'mimes:mp4,mov,wmv,avi,WEBM,mkv|max:20048');
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $validation_errors = $validator->getMessageBag()->toArray();
                foreach ($validation_errors as $key => $validation_error) {
                    $fileIndex = explode('.', $key);
                    if (array_key_exists('multiple_files.' . $fileIndex[1], $validation_errors)) {
                        $validation_errors['multiple_files'] = $validation_errors['multiple_files.' . $fileIndex[1]];
                    }
                    unset($validation_errors['multiple_files.' . $fileIndex[1]]);
                }

                return json_encode(array('validationError' => $validation_errors));
            }
        }



        $data['privacy'] = $request->privacy;


        if (isset($request->tagged_users_id) && is_array($request->tagged_users_id)) {
            $tagged_users = $request->tagged_users_id;
            $data['tagged_user_ids'] = json_encode($tagged_users);
        }


        if (isset($request->feeling_and_activity_id) && !empty($request->feeling_and_activity_id)) {
            $data['activity_id'] = $request->feeling_and_activity_id;
        }

        if (isset($request->description) && !empty($request->description)) {
            $data['description'] = $request->description;
        }

        $data['updated_at'] = time();

        Posts::where('post_id', $id)->update($data);

        //add media files
        if (is_array($request->multiple_files) && $request->multiple_files[0] != null) {
            //Data validation

            $rules = array('multiple_files.*' => 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,wmv,avi,webm|max:20480', );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $validation_errors = $validator->getMessageBag()->toArray();
                foreach ($validation_errors as $key => $validation_error) {
                    $fileIndex = explode('.', $key);
                    if (array_key_exists('multiple_files.' . $fileIndex[1], $validation_errors)) {
                        $validation_errors['multiple_files'] = $validation_errors['multiple_files.' . $fileIndex[1]];
                    }
                    unset($validation_errors['multiple_files.' . $fileIndex[1]]);
                }

                return json_encode(array('validationError' => $validation_errors));
            }



            foreach ($request->multiple_files as $key => $media_file) {
                $file_name = random(40);
                $file_extention = strtolower($media_file->getClientOriginalExtension());
                if ($file_extention == 'avi' || $file_extention == 'mp4' || $file_extention == 'webm' || $file_extention == 'mov' || $file_extention == 'wmv' || $file_extention == 'mkv') {
                    $media_file->move('public/storage/post/videos/', $file_name . '.' . $file_extention);
                    $file_type = 'video';
                } else {
                    FileUploader::upload($media_file, 'public/storage/post/images/' . $file_name . '.' . $file_extention, 1000, null, 300);
                    $file_type = 'image';
                }
                $file_name = $file_name . '.' . $file_extention;


                $media_file_data = array('user_id' => auth()->user()->id, 'post_id' => $id, 'file_name' => $file_name, 'file_type' => $file_type, 'privacy' => $request->privacy);


                if (isset($request->page_id) && !empty($request->page_id)) {
                    $media_file_data['page_id'] = $request->page_id;
                } elseif (isset($request->group_id) && !empty($request->group_id)) {
                    $media_file_data['group_id'] = $request->group_id;
                } else {
                }
                $media_file_data['created_at'] = time();
                $media_file_data['updated_at'] = $media_file_data['created_at'];
                Media_files::create($media_file_data);
            }
        }




        //Ajax flush message
        Session::flash('success_message', get_phrase('Your post has been updated'));
        $response = array('reload' => 1);
        return json_encode($response);
    }


    function create_live_streaming($publisher, $publisher_id)
    {
        // return $publisher;
        $post_details = Posts::where('posts.status', 'active')
            ->where('posts.post_id', $publisher_id)
            ->join('users', 'posts.user_id', '=', 'users.id')->first();

        $live_streaming = Live_streamings::where('publisher', $publisher)
            ->where('user_id', $this->user->id)->where('publisher_id', $publisher_id);


        if ($live_streaming->count() > 0) {
            //Update
            $meeting_details = json_decode($live_streaming->value('details'), true);
            if (!empty($post_details->description)) {
                $live_topic = ellipsis($post_details->description, 200);
            } else {
                $live_topic = "Live";
            }

            $meeting_details['topic'] = $live_topic;
            $meeting_details['start_time'] = $this->toZoomTimeFormat(time());

            $path = 'meetings/' . $meeting_details['id'];
            $response = $this->zoomPatch($path, [
                'topic' => $meeting_details['topic'],
                'type' => self::MEETING_TYPE_SCHEDULE,
                'start_time' => $meeting_details['start_time'],
                'duration' => 40,
                'agenda' => null,
                'settings' => [
                    'host_video' => true,
                    'participant_video' => true,
                    'waiting_room' => false,
                ]
            ]);

            $data['publisher'] = $publisher;
            $data['publisher_id'] = $publisher_id;
            $data['details'] = json_encode($meeting_details);
            $data['updated_at'] = time();
            Live_streamings::where('streaming_id', $live_streaming->value('streaming_id'))->update($data);
        } else {
            if (!empty($post_details->description)) {
                $live_topic = ellipsis($post_details->description, 200);
            } else {
                $live_topic = "Live";
            }

            //Create
            $path = 'users/me/meetings';
            $response = $this->zoomPost($path, [
                'topic' => $live_topic,
                'type' => self::MEETING_TYPE_SCHEDULE,
                'start_time' => $this->toZoomTimeFormat(time()),
                'duration' => 40,
                'agenda' => null,
                'settings' => [
                    'host_video' => true,
                    'participant_video' => true,
                    'waiting_room' => false,
                ]
            ]);

            $var = array('success' => $response->status() === 201, 'data' => $response->body());

            $data['publisher'] = $publisher;
            $data['publisher_id'] = $publisher_id;
            $data['user_id'] = $this->user->id;
            $data['details'] = $var['data'];
            $data['created_at'] = time();
            $data['updated_at'] = $data['created_at'];
            Live_streamings::create($data);
        }
    }

    function live($post_id)
    {

        $post_details = Posts::where(function ($query) {
            $query->whereJsonContains('users.friends', [$this->user->id])
                ->where('posts.privacy', '!=', 'private')
                ->orWhere('posts.user_id', $this->user->id);
        })
            ->where('posts.post_id', $post_id)
            ->where('posts.status', 'active')
            ->join('users', 'posts.user_id', '=', 'users.id');

        if ($post_details->count() > 0) {

            $post_details = $post_details->first();

            $live_streaming = Live_streamings::where('publisher', 'post')
                ->where('publisher_id', $post_id)
                ->where('user_id', $post_details->user_id);

            if ($live_streaming->get()->count() > 0) {
                $live_streaming = $live_streaming->first();

                $page_data['meeting_details'] = json_decode($live_streaming->details, true);

                if ($post_details->user_id == $this->user->id) {
                    $page_data['host'] = 1;
                    $page_data['isSupportAV'] = 1;
                    $page_data['disableJoinAudio'] = 0;
                } else {
                    $page_data['host'] = 0;
                    $page_data['isSupportAV'] = 0;
                    $page_data['disableJoinAudio'] = 1;
                }
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }

        $page_data['post_details'] = $post_details;

        return view('frontend.live_streaming.index', $page_data);
    }

    function live_ended($post_id)
    {
        Posts::where('post_id', $post_id)->update(['description' => json_encode(['live_video_ended' => 'yes'])]);
        return redirect()->route('timeline');
    }

    function search_friends_for_tagging(Request $request)
    {
        $friends = DB::table('users')->whereJsonContains('friends', [$this->user->id])
            ->where('name', 'like', '%' . $request->search_value . '%')
            ->take(30)->get();

        $data['friends'] = $friends;
        return view('frontend.main_content.friend_list_for_tagging', $data);
    }


    function my_react(Request $request)
    {
        $form_data = $request->all();

        if ($form_data['type'] == 'post') {
            $post_data = Posts::where('post_id', $form_data['post_id'])->get()->first();

            $all_reacts = json_decode($post_data['user_reacts'], true);


            if ($form_data['request_type'] == 'update') {
                $all_reacts[$this->user->id] = $form_data['react'];
            }

            if ($form_data['request_type'] == 'toggle') {
                if (array_key_exists($this->user->id, $all_reacts)) {
                    unset($all_reacts[$this->user->id]);
                } else {
                    $all_reacts[$this->user->id] = 'like';
                }
            }

            $data['user_reacts'] = json_encode($all_reacts);
            Posts::where('post_id', $form_data['post_id'])->update($data);


            $page_data['user_reacts'] = $all_reacts;
            $page_data['user_info'] = $this->user;
            $page_data['ajax_call'] = true;
            $page_data['my_react'] = true;
            $page_data['post_react'] = true;

            $post = Posts::where('posts.post_id', $form_data['post_id'])
                ->join('users', 'posts.user_id', '=', 'users.id')
                ->select('posts.*', 'users.name', 'users.photo', 'users.friends', 'posts.created_at as created_at')->get()->first();

            if ($post && auth()->user()) {
                $userId = auth()->user()->id;

                if (
                    $form_data['request_type'] == 'toggle' &&
                    array_key_exists($userId, $all_reacts) === false
                ) {
                    // User removed their like → delete activity
                    app(UserActivityService::class)->deleteBylikeActivityId($form_data['post_id']);
                } else {
                    // User added/updated like → log activity
                    app(UserActivityService::class)->log($userId, 'like', $post->publisher, $post->publisher_id, $form_data['post_id']);
                }
            }


            if ($form_data['response_type'] == 'number') {
                return count($all_reacts);
            } else {
                return view('frontend.main_content.post_reacts', $page_data);
            }
        }
    }

    function my_comment_react(Request $request)
    {
        $form_data = $request->all();

        $comment_data = Comments::where('comment_id', $form_data['comment_id'])->get()->first();

        $all_reacts = json_decode($comment_data['user_reacts'], true);







        if ($form_data['request_type'] == 'update') {
            $all_reacts[$this->user->id] = $form_data['react'];
        }

        if ($form_data['request_type'] == 'toggle') {
            if (array_key_exists($this->user->id, $all_reacts)) {
                unset($all_reacts[$this->user->id]);
            } else {
                $all_reacts[$this->user->id] = 'like';
            }
        }

        $data['user_reacts'] = json_encode($all_reacts);
        Comments::where('comment_id', $form_data['comment_id'])->update($data);

        if ($comment_data && auth()->user()) {
            $userId = auth()->user()->id;

            if (
                $form_data['request_type'] == 'toggle' &&
                array_key_exists($userId, $all_reacts) === false
            ) {
                // User removed like on comment → delete activity
                app(UserActivityService::class)->deleteBylikeActivityId($form_data['comment_id']);
            } else {
                // User added/updated like → log activity
                app(UserActivityService::class)->log($userId, 'like', $comment_data->is_type, $comment_data->id_of_type, $form_data['comment_id']);
            }
        }


        $page_data['user_comment_reacts'] = $all_reacts;
        $page_data['user_info'] = $this->user;
        $page_data['ajax_call'] = true;
        $page_data['my_react'] = true;
        $page_data['comment_react'] = true;
        return view('frontend.main_content.comment_reacts', $page_data);
    }

    function load_post_comments(Request $request)
    {
        $post = Posts::where('posts.status', 'active')
            ->where('posts.post_id', $request->post_id)
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.name', 'users.photo', 'users.friends', 'posts.created_at as created_at')->get()->first();

        $comments = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->where('comments.is_type', $request->type)
            ->where('comments.id_of_type', $request->post_id)
            ->where('comments.parent_id', $request->parent_id)
            ->select('comments.*', 'users.name', 'users.photo')
            ->orderBy('comment_id', 'DESC')->skip($request->total_loaded_comments)->take(3)->get();

        $page_data['post'] = $post;
        $page_data['type'] = $request->type;
        $page_data['post_id'] = $request->post_id;
        if ($request->parent_id == 0) {
            $page_data['comments'] = $comments;
            return view('frontend.main_content.comments', $page_data);
        } else {
            $page_data['child_comments'] = $comments;
            return view('frontend.main_content.child_comments', $page_data);
        }
    }

    function post_comment(Request $request)
    {
        $form_data = $request->all();


        //$description = $form_data['description'];

        // Fetch spam words from the database
        // Fetch spam words as a single string from the first row
        $spamWordsString = DB::table('spam_words')->pluck('word')->first();

        // Ensure it's not empty
        if (!$spamWordsString) {
            return response()->json(['error' => false]); // No spam words exist
        }

        // Convert comma-separated string to an array & clean up
        $spamWords = array_map('trim', explode(',', strtolower($spamWordsString)));

        // Normalize user input
        $description = strtolower(trim($form_data['description']));

        // Check if any spam word exists in the input text
        foreach ($spamWords as $spamWord) {
            if (str_contains($description, $spamWord)) {
                Session::flash('spam_error', 'Your post contains restricted words. Please remove them.');
                return response()->json(['reload' => 1]);
            }
        }

        $data['description'] = $form_data['description'];




        if ($form_data['comment_id'] > 0) {
            $data['updated_at'] = time();
            Comments::where('comment_id', $form_data['comment_id'])->where('user_id', $this->user->id)->update($data);
            $comment_id = $form_data['comment_id'];
        } else {
            $data['parent_id'] = $form_data['parent_id'];
            $data['user_id'] = $this->user->id;
            $data['is_type'] = $form_data['type'];
            $data['id_of_type'] = $form_data['post_id'];
            $data['user_reacts'] = json_encode(array());
            $data['created_at'] = time();
            $data['updated_at'] = $data['created_at'];
            $comment_id = Comments::insertGetId($data);
        }






        $post = Posts::where('posts.post_id', $form_data['post_id'])
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.name', 'users.photo', 'users.friends', 'posts.created_at as created_at')->get()->first();

        //  if($post){  
        //         if (auth()->user()){
        //                     //app(UserActivityService::class)->log(auth()->user()->id, 'comment', $post->publisher, $post->post_id,$comment_id);
        //         }
        //  }else{

        $comments = Comments::where('comment_id', $comment_id)->where('user_id', auth()->user()->id)
            ->select('comments.*')->get()
            ->first();
        //print_r($comments);exit;
        if (auth()->user()) {
            app(UserActivityService::class)->log(auth()->user()->id, 'comment', $comments->is_type, $comments->id_of_type, $comment_id);
        }
        //  }
        $comments = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->where('comments.is_type', $form_data['type'])
            ->where('comments.comment_id', $comment_id)->get();

        $page_data['post'] = $post;
        $page_data['type'] = $form_data['type'];
        $page_data['post_id'] = $form_data['post_id'];

        $total_comments = Comments::where('is_type', $form_data['type'])->where('id_of_type', $form_data['post_id'])->get()->count();

        if ($request->parent_id == 0) {
            $page_data['comments'] = $comments;
            return view('frontend.main_content.comments', $page_data);
        } else {
            $page_data['child_comments'] = $comments;
            return view('frontend.main_content.child_comments', $page_data);
        }
    }

    function preview_post(Request $request)
    {

        //Previw post
        $posts = Posts::where(function ($query) {
            $query->where('posts.privacy', '!=', 'private')
                ->orWhere('posts.user_id', $this->user->id);
        })
            ->where('posts.post_id', $request->post_id)
            ->where('posts.status', 'active')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.name', 'users.photo', 'users.friends', 'posts.created_at as created_at')
            ->take(1)->orderBy('posts.post_id', 'DESC')->get();


        $page_data['posts'] = $posts;
        $page_data['file_name'] = $request->file_name;
        $page_data['user_info'] = $this->user;
        return view('frontend.main_content.preview_post', $page_data);
    }





    public function post_comment_count(Request $request)
    {
        $form_data = $request->all();
        return $total_child_comments = Comments::where('is_type', $form_data['type'])->where('id_of_type', $form_data['post_id'])->get()->count();
    }




    public function single_post($id = null)
    {

        $page_data['all_cities'] = CityHelper::getActiveCities();

        // Handle case where no ID is provided
        if ($id === null) {
            $page_data['post'] = '';
            $page_data['view_path'] = 'frontend.main_content.custom_shared_view';
            return view('frontend.index', $page_data);
        }

        $post = Posts::where('post_id', $id)->first();
        if (!empty($post)) {
            $page_data['post'] = $post;
            $page_data['user_info'] = auth()->user();
            $page_data['type'] = 'user_post';
            $page_data['view_path'] = 'frontend.main_content.single-post';

            if (isset($_GET['shared'])) {
                return view('frontend.main_content.custom_shared_view', $page_data);
            } else {
                return view('frontend.index', $page_data);
            }
        } else {

            if (isset($_GET['shared'])) {
                $page_data['post'] = '';
                return view('frontend.main_content.custom_shared_view', $page_data);
            } else {
                $page_data['post'] = '';
                $page_data['view_path'] = 'frontend.main_content.custom_shared_view';
                return view('frontend.index', $page_data);
            }
        }
    }




    public function save_post_report(Request $request)
    {

        $report = new Report();

        $report->user_id = auth()->user()->id;
        $report->post_id = $request->post_id;
        $report->report = $request->report;
        $report->save();
        $post = Posts::where('posts.post_id', $request->post_id)
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.name', 'users.photo', 'users.friends', 'posts.created_at as created_at')->get()->first();


        if (auth()->user()) {
            app(UserActivityService::class)->log(auth()->user()->id, 'report', $post->publisher, $post->publisher_id, $request->post_id);
        }
        Session::flash('success_message', get_phrase('Report Done Successfully'));
        return json_encode(array('reload' => 1));
    }


    public function comment_delete()
    {
        $response = array();
        $comment_id = $_GET['comment_id'];
        $done = Comments::where('comment_id', $comment_id)->delete();
        if ($done) {
            $response = array('alertMessage' => get_phrase('Comment Deleted Successfully'), 'fadeOutElem' => "#comment_" . $_GET['comment_id']);
        }
        if ($comment_id) {
            if (auth()->user()) {
                app(UserActivityService::class)->deleteByActivityId($comment_id);
            }
        }
        return json_encode($response);
    }



    public function share_group(Request $request)
    {
        $postshare = new Post_share();
        $postshare->user_id = auth()->user()->id;
        $postshare->post_id = $request->shared_post_id;
        $postshare->shared_on = 'group';
        $postshare->save();


        $post = new Posts();
        $post->user_id = auth()->user()->id;
        $post->publisher = 'group';
        $post->publisher_id = $request->group_id;
        $post->post_type = "general";
        $post->privacy = "public";
        $post->tagged_user_ids = json_encode(array());
        if (isset($request->shared_post_id) && !empty($request->shared_post_id)) {
            $post->description = $request->message;
        }
        if (isset($request->shared_product_id) && !empty($request->shared_product_id)) {
            $post->description = $request->productUrl;
        }
        $post->status = 'active';
        $post->user_reacts = json_encode(array());
        $post->shared_user = json_encode(array());
        $time = time();
        $post->created_at = $time;
        $post->updated_at = $time;
        $done = $post->save();

        $response = array('alertMessage' => get_phrase('Posted On Group Successfully'));
        return json_encode($response);
    }


    public function share_my_timeline(Request $request)
    {
        $postshare = new Post_share();
        $postshare->user_id = auth()->user()->id;
        $postshare->post_id = $request->shared_post_id;
        $postshare->shared_on = 'group';
        $postshare->save();


        $post = new Posts();
        $post->user_id = auth()->user()->id;
        $post->publisher = 'post';
        $post->publisher_id = auth()->user()->id;
        $post->post_type = "share";
        $post->privacy = "public";
        $post->tagged_user_ids = json_encode(array());
        if (isset($request->shared_post_id) && !empty($request->shared_post_id)) {
            $post->description = $request->postUrl;
        }
        if (isset($request->shared_product_id) && !empty($request->shared_product_id)) {
            $post->description = $request->productUrl;
        }
        $post->status = 'active';
        $post->user_reacts = json_encode(array());
        $post->shared_user = json_encode(array());
        $time = time();
        $post->created_at = $time;
        $post->updated_at = $time;
        $done = $post->save();

        Session::flash('success_message', get_phrase('Posted On My Timeline Successfully'));
        return json_encode(array('url' => route('profile')));
    }



    // post delete 

    public function post_delete()
    {
        $response = array();
        $post_id = $_GET['post_id'] ?? null;
        
        if (!$post_id) {
            return json_encode(array('alertMessage' => get_phrase('Invalid Post ID')));
        }

        $post = Posts::where('post_id', $post_id)->first();

        if (!$post) {
            return json_encode(array('alertMessage' => get_phrase('Post not found')));
        }

        // Backend Authorization: ONLY the post owner/creator can delete their post
        if ($post->user_id != auth()->user()->id) {
            return json_encode(array('alertMessage' => get_phrase('Unauthorized: You cannot delete this post')));
        }

        // Delete related media files
        $media_files = Media_files::where('post_id', $post_id)->get();
        foreach ($media_files as $media) {
            if ($media->file_type == 'video') {
                remove_file('public/storage/post/videos/' . $media->file_name);
            } else {
                remove_file('public/storage/post/images/' . $media->file_name);
            }
            $media->delete();
        }

        // Delete related comments
        Comments::where('is_type', 'post')->where('id_of_type', $post_id)->delete();

        // Delete user activity logs
        app(UserActivityService::class)->deleteLogsByContent($post_id, $post->publisher);

        // Delete post
        $done = $post->delete();
        if ($done) {
            $response = array('alertMessage' => get_phrase('Post Deleted Successfully'), 'fadeOutElem' => "#postIdentification" . $post_id);
        } else {
            $response = array('alertMessage' => get_phrase('Failed to delete post'));
        }
        return json_encode($response);
    }




    public function custom_shared_post_view($id)
    {


        $post = Posts::where(function ($query) {
            $query->whereJsonContains('users.friends', [$this->user->id])
                ->where('posts.privacy', '!=', 'private')
                ->orWhere('posts.user_id', $this->user->id);
        })
            ->where('posts.post_id', $id)
            ->where('posts.status', 'active')
            ->where('posts.report_status', '0')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.name', 'users.photo', 'users.friends', 'posts.created_at as created_at')->first();


        $page_data['post'] = $post;
        $page_data['type'] = 'user_post';
        return view('frontend.main_content.custom_shared_view', $page_data);
    }

    function delete_media_file($id)
    {
        $media_file = Media_files::where('id', $id)->where('user_id', auth()->user()->id);
        if ($media_file->count() > 0) {
            remove_file('public/storage/post/images/' . $media_file->first()->file_name);
            Media_files::find($id)->delete();
            $response = array('alertMessage' => get_phrase('Image deleted successfully'), 'fadeOutElem' => "#previous-uploaded-img-" . $id);
        } else {
            $response = array('alertMessage' => get_phrase('Image not found'));
        }

        return json_encode($response);
    }
}
