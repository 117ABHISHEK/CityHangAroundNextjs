<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserActivityLog;
use App\Models\City;
use App\Models\Posts;
use App\Models\Page;
use App\Models\User;
use App\Models\Marketplace;

use App\Models\Group;
use App\Models\Event;
use App\Models\Blog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
class UserActivityController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('activityLogs'); // relation defined hona chahiye
        $page_data['user']= $user;
        $page_data['score'] =$user->total_score;
        $page_data['view_path'] ='activity.index';
        return view('backend.index', $page_data);
    }


 public function cityBreakdown()
{
    $page = request()->get('page', 1);

    $user = Auth::user();
    $logs = UserActivityLog::where('user_id', $user->id)->get();

    $cityScores = [];

    foreach ($logs as $log) {
        $city_id = null;

        // 1. Profile Update
        if ($log->event_name === 'profile_update') {
            $city_id = optional($user->city)->id;
        }

        // 2. Follow event (profile or page)
        elseif ($log->event_name === 'follow') {
            if ($log->content_type === 'profile') {
                $followedUser = User::find($log->content_id);
                $city_id = optional($followedUser->city)->id;
            } elseif ($log->content_type === 'page') {
                $page = Page::find($log->content_id);
                $city_id = optional($page)->city_id;
            }
            elseif ($log->content_type === 'group') {
                $page = Group::find($log->content_id);
                $city_id = optional($page)->city_id;
            }
        }

         elseif ($log->event_name === 'comment' && $log->content_type === 'post') {
           
                $post = Posts::where('post_id', $log->content_id)->first();
                $followedUser = User::find($post->user_id);
                $city_id = optional($followedUser->city)->id;
        }


          elseif ($log->event_name === 'comment' &&  in_array($log->content_type, ['page', 'event', 'group',  'product'])) {
             $post = Posts::where('post_id', $log->content_id)->first();
             if($post){
                switch ($post->publisher) {
              
                case 'page':
                    $page = Page::find($post->publisher_id);
                    $city_id = optional($page)->city_id;
                    break;
                case 'event':
                    $event = Event::find($post->publisher_id);
                    $city_id = optional($event)->city_id;
                    break;
                case 'group':
                    $group = Group::find($post->publisher_id);
                    $city_id = optional($group)->city_id;
                    break;
                case 'blog':
                    $blog = Blog::find($post->publisher_id);
                    $city_id = optional($blog)->city_id;
                    break;
                case 'product':
                    $marketplace = Marketplace::find($post->publisher_id);
                    $city_id = optional($marketplace?->page)->city_id;
                    break;
            }
        }
        
        }


        elseif ($log->event_name === 'comment' &&  in_array($log->content_type, ['blog'])) {
            
              
                    $blog = Blog::find($log->content_id);
                    $city_id = optional($blog)->city_id;
                }

        
        

          elseif ($log->event_name === 'post' && $log->content_type === 'post') {
                $followedUser = User::find($log->content_id);
                $city_id = optional($followedUser->city)->id;
        }

         elseif ($log->event_name === 'like' && $log->content_type === 'post') {
                $post = Posts::where('post_id', $log->content_id)->first();
                if($post){
                    if($post->publisher=='page'){
                        $page = Page::find($post->publisher_id);
                        $city_id = optional($page)->city_id;
                    }
                    elseif($post->publisher=='event'){
                    $event = Event::find($post->publisher_id);
                    $city_id = optional($event)->city_id;
                    }
                    elseif($post->publisher=='group'){
                         $group = Group::find($post->publisher_id);
                    $city_id = optional($group)->city_id;
                    }
                    elseif($post->publisher=='blog'){
                        $blog = Blog::find($post->publisher_id);
                    $city_id = optional($blog)->city_id;
                    }
                    elseif($post->publisher=='product'){
                         $marketplace = Marketplace::find($post->publisher_id);
                    $city_id = optional($marketplace?->page)->city_id;
                    }
                    else{
                    $followedUser = User::find($post->user_id);
                    $city_id = optional($followedUser->city)->id;
                    }
                   
                }
                else{

                    $post = Posts::where('post_id', $log->activity_id)->first();
                    if($post){
                        $followedUser = User::find($post->user_id);
                        $city_id = optional($followedUser->city)->id;
                    }
                }
        }

        
        
        

        // 3. Like or View or Review (Page, Event, Group, Blog, Product)
        elseif (
            in_array($log->event_name, ['view', 'review','like','enquiry_product','enquiry_listing','category_suggest',
            'blog_listing','report','user_registration','event_listing','video_post','listing','marketplace_listing','marketplace_enquiry',
            'group_listing','post','claim_listing'
            ]) &&
            in_array($log->content_type, ['page', 'event', 'group', 'blog', 'product'])
        ) {
            switch ($log->content_type) {
              
                case 'page':
                    $page = Page::find($log->content_id);
                    $city_id = optional($page)->city_id;
                    break;
                case 'event':
                    $event = Event::find($log->content_id);
                    $city_id = optional($event)->city_id;
                    break;
                case 'group':
                    $group = Group::find($log->content_id);
                    $city_id = optional($group)->city_id;
                    break;
                case 'blog':
                    $blog = Blog::find($log->content_id);
                    $city_id = optional($blog)->city_id;
                    break;
                case 'product':
                    $marketplace = Marketplace::find($log->content_id);
                    $city_id = optional($marketplace?->page)->city_id;
                    break;
            }
        }

        // 4. Post-based actions (comment, like, share etc.)
       // 4. Post-based actions (comment, like, share etc.)
        elseif ($log->content_type && $log->content_id) {
            $post = Posts::where('post_id', $log->content_id)->first();
            if ($post) {
                switch ($post->publisher) {
                    case 'page':
                        $city_id = optional(Page::find($post->publisher_id))->city_id;
                        break;
                    case 'group':
                        $city_id = optional(Group::find($post->publisher_id))->city_id;
                        break;
                    case 'event':
                        $city_id = optional(Event::find($post->publisher_id))->city_id;
                        break;
                    case 'blog':
                        $city_id = optional(Blog::find($post->publisher_id))->city_id;
                        break;
                }
            }
        }

        // Score Add
        if ($city_id) {
            $cityScores[$city_id] = ($cityScores[$city_id] ?? 0) + $log->score;
        }
    }

    // Get city names
    $cityData = City::whereIn('id', array_keys($cityScores))->get()->keyBy('id');

    // Prepare result array
    $result = [];
    foreach ($cityScores as $city_id => $score) {
       $result[] = [
            'id' => $city_id,
            'city_name' => $cityData[$city_id]->city_name ?? 'Unknown City',
            'total_score' => $score,
        ];

    }

    // Sort descending by total_score
    usort($result, function ($a, $b) {
        return $b['total_score'] <=> $a['total_score'];
    });

    $collection = collect($result);
    $page = request()->get('page', 1);
    $perPage = 10;
    $paginatedResult = new LengthAwarePaginator(
        $collection->forPage($page, $perPage),
        $collection->count(),
        $perPage,
        $page,
        ['path' => url()->current()]
    );

    return view('backend.index', [
        'cityScores' => $paginatedResult,
        'user' => $user,
        'view_path' => 'activity.city_breakdown',
    ]);
}
// public function cityActivityReport($cityId)
// {
//     $user = Auth::user();
//     $logs = UserActivityLog::where('user_id', $user->id)->orderBy('created_at')->get();

//     $filteredLogs = [];

//     foreach ($logs as $log) {
//         $detectedCityId = $this->getCityIdFromLog($log, $user);

//         if ($detectedCityId == $cityId) {
//             $date = $log->created_at->format('Y-m-d');

//             $details = $log->activity_details ?? '';

//             // Add additional context based on content type
//             switch ($log->content_type) {
//                 case 'post':
//                     $post = Posts::where('post_id', $log->content_id)->first();
//                     if ($post) {
//                         $owner = User::find($post->user_id);
//                         $details = 'Post by ' . optional($owner)->name . ' — ' . \Str::limit(strip_tags($post->description), 50);
//                     }
//                     break;

//                 case 'page':
//                     $page = Page::find($log->content_id);
//                     if ($page) {
//                         $details = 'Page: ' . $page->title;
//                     }
//                     break;

//                 case 'event':
//                     $event = Event::find($log->content_id);
//                     if ($event) {
//                         $details = 'Event: ' . $event->title;
//                     }
//                     break;

//                 case 'group':
//                     $group = Group::find($log->content_id);
//                     if ($group) {
//                         $details = 'Group: ' . $group->title;
//                     }
//                     break;

//                 case 'blog':
//                     $blog = Blog::find($log->content_id);
//                     if ($blog) {
//                         $details = 'Blog: ' . $blog->title;
//                     }
//                     break;

//                 case 'product':
//                     $product = Marketplace::find($log->content_id);
//                     if ($product) {
//                         $details = 'Product: ' . $product->title;
//                     }
//                     break;

//                 case 'profile':
//                     $targetUser = User::find($log->content_id);
//                     if ($targetUser) {
//                         $details = 'User: ' . $targetUser->name;
//                     }
//                     break;
//             }

//             $filteredLogs[$date][] = [
//                 'event' => $log->event_name,
//                 'type' => $log->content_type,
//                 'score' => $log->score,
//                 'time' => $log->created_at->format('h:i A'),
//                 'details' => $details,
//             ];
//         }
//     }

//     $city = City::find($cityId);

//     return view('backend.index', [
//         'cityLogs' => $filteredLogs,
//         'cityName' => $city->city_name ?? 'Unknown City',
//         'view_path' => 'activity.city_activity_detail',
//     ]);
// }


public function cityActivityReport($cityId)
{
    $user = Auth::user();
    $logs = UserActivityLog::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

    //print_r($logs);exit;
    $flatLogs = [];

    foreach ($logs as $log) {
        $detectedCityId = $this->getCityIdFromLog($log, $user);
//print_r($detectedCityId);exit;
        if ($detectedCityId == $cityId) {
            $details = $log->activity_details;

            // If details are not already available, build them dynamically
            if (!$details) {
                $details = $this->generateActivityDetails($log);
            }

            $flatLogs[] = [
                'date' => $log->created_at->format('Y-m-d'),
                'event' => $log->event_name,
                'type' => $log->content_type,
                'score' => $log->score,
                'time' => $log->created_at->format('h:i A'),
                'details' => $details,
            ];
        }
    }

    // Paginate flat logs
    $page = request()->get('page', 1);
    $perPage = 10;
    $collection = collect($flatLogs);

    $paginatedLogs = new LengthAwarePaginator(
        $collection->forPage($page, $perPage),
        $collection->count(),
        $perPage,
        $page,
        ['path' => url()->current()]
    );

    $city = City::find($cityId);

    return view('backend.index', [
        'cityLogs' => $paginatedLogs,
        'cityName' => $city->city_name ?? 'Unknown City',
        'view_path' => 'activity.city_activity_detail',
    ]);
}
private function generateActivityDetails($log)
{
    // 1. Profile Update
    if ($log->event_name === 'profile_update') {
        $user = User::find($log->user_id);
        $url = route('user.profile.view', ['id' => $user->id]);
        return 'Updated own profile in city: <a href="' . $url . '">' . optional($user->city)->city_name. '</a>';
    }

    // 2. Follow (profile/page/group)
    if ($log->event_name === 'follow') {
        if ($log->content_type === 'profile') {
            $targetUser = User::find($log->content_id);
            return 'Followed user: ' . optional($targetUser)->name;
        } elseif ($log->content_type === 'page') {
            // $page = Page::find($log->content_id);
            // return 'Followed page: ' . optional($page)->title;
            $page = Page::with(['city', 'area', 'pageCategories'])->find($log->content_id);

                    $category = $page->pageCategories->last(); // last category as per your request

                    if ($page && $page->city && $page->area && $category) {
                        $url = route('single.page', [
                            'city_slug' => $page->city->city_slug,
                            'area_slug' => $page->area->area_slug,
                            'category_slug' => $category->category_slug,
                            'item_slug' => $page->item_slug,
                        ]);
                    }
                           return 'Followed page: <a href="' . $url . '"> ' . optional($page)->title . '</a>';

        } elseif ($log->content_type === 'group') {
           
            $group = Group::find($log->content_id);

                $slug = \Str::slug($group->last_category?->category_slug ?? 'uncategorized');

                $url = route('single.group', [
                    'category_slug' => $slug,
                    'group_slug'    => $group->group_slug,
                    'city_slug'     => $group->city?->city_slug,
                    'area_slug'     => $group->area?->area_slug,
                ]);
                 return 'Followed group: <a href="' . $url . '"> ' . optional($group)->title . '</a>';
            
        }
    }



    // 3. Comment or Like on a Post
    if (in_array($log->event_name, ['comment', 'like']) && $log->content_type === 'post') {
        
        $post = Posts::where('post_id', $log->content_id)->first();
        if ($post) {
            $userName = optional(User::find($post->user_id))->name ?? 'Unknown User';
            // $summary = ucfirst($log->event_name) . 'ed on post by ' . $userName;
            $summary = '<a href="' . route('single.post', ['id' => $post->post_id]) . '">'
         . ucfirst($log->event_name) . 'ed on post by ' . $userName . '</a>';

         if($post->description){
            $summary .= ' — "' . \Str::limit(strip_tags($post->description), 50) . '"';
         }
            

            switch ($post->publisher) {
                case 'page':
                   $page = Page::with(['city', 'area', 'pageCategories'])->find($post->publisher_id);

                    $category = $page->pageCategories->last(); // last category as per your request

                    if ($page && $page->city && $page->area && $category) {
                        $url = route('single.page', [
                            'city_slug' => $page->city->city_slug,
                            'area_slug' => $page->area->area_slug,
                            'category_slug' => $category->category_slug,
                            'item_slug' => $page->item_slug,
                        ]);

                        $summary .= ' (<a href="' . $url . '">on Page: ' . e($page->title) . '</a>)';
                    } else {
                        $summary .= ' (on Page: ' . optional($page)->title . ')';
                    }

                    break;
                case 'group':
                    $group = Group::find($post->publisher_id);

                $slug = \Str::slug($group->last_category?->category_slug ?? 'uncategorized');

                $url = route('single.group', [
                    'category_slug' => $slug,
                    'group_slug'    => $group->group_slug,
                    'city_slug'     => $group->city?->city_slug,
                    'area_slug'     => $group->area?->area_slug,
                ]);
                    
                    $summary .= ' (in Group: <a href="' . $url . '">' . optional($group)->title . '</a>)';

                    break;
                case 'event':
                    $event = Event::find($post->publisher_id);

                $url = route('single.event', [
                    'city_slug' => $event->city?->city_slug ?? 'city',
                    'area_slug' => $event->area?->area_slug ?? 'area',
                    'category_slug' => $event->lastCategory()?->category_slug ?? 'uncategorized',
                    'event_slug' => $event->event_slug ?? 'event',
                ]);
                    $summary .= ' (at Event: <a href="' . $url . '">' . optional($event)->title . '</a>)';
                    break;
                case 'blog':
                    $blog = Blog::find($log->content_id);
                    $summary .= ' (Blog: ' . optional($blog)->title . ')';
                    break;
                case 'product':
                    $product = Marketplace::find($post->publisher_id);
                    $summary .= ' (Product: ' . optional($product)->title . ')';
                    break;
            }

            return $summary;
        } 
        elseif($log->event_name=='like'){
            $post = Posts::where('post_id', $log->activity_id)->first();
           
           
            $userName = optional(User::find($post->user_id))->name ?? 'Unknown User';
            // $summary = ucfirst($log->event_name) . 'ed on post by ' . $userName;
            $summary = '<a href="' . route('single.post', ['id' => $post->post_id]) . '">'
         . ucfirst($log->event_name) . 'ed on post by ' . $userName . '</a>';

         return $summary;
        }
        else {
            return ucfirst($log->event_name) . 'ed on a deleted or unknown post.';
        }
    }

    

    // 4. Comment on page/event/group/blog/product directly
    if (
        $log->event_name === 'comment' &&
        in_array($log->content_type, ['page', 'event', 'group', 'product'])
    ) {
        
         $post = Posts::where('post_id', $log->content_id)->first();
        switch ($post->publisher) {
            case 'page':
                $page = Page::with(['city', 'area', 'pageCategories'])->find($post->publisher_id);

                    $category = $page->pageCategories->last(); // last category as per your request

                    if ($page && $page->city && $page->area && $category) {
                        $url = route('single.page', [
                            'city_slug' => $page->city->city_slug,
                            'area_slug' => $page->area->area_slug,
                            'category_slug' => $category->category_slug,
                            'item_slug' => $page->item_slug,
                        ]);

                        return ' Commented on page (<a href="' . $url . '">on Page: ' . e($page->title) . '</a>)';
                    } else {
                        return ' ( on Page: ' . optional($page)->title . ')';
                    }
            case 'event':
                $event = Event::find($post->publisher_id);

                $url = route('single.event', [
                    'city_slug' => $event->city?->city_slug ?? 'city',
                    'area_slug' => $event->area?->area_slug ?? 'area',
                    'category_slug' => $event->lastCategory()?->category_slug ?? 'uncategorized',
                    'event_slug' => $event->event_slug ?? 'event',
                ]);
                return 'Commented on event: <a href="' . $url . '">' . optional($event)->title . '</a>';
            case 'group':
                $group = Group::find($post->publisher_id);

                $slug = \Str::slug($group->last_category?->category_slug ?? 'uncategorized');

                $url = route('single.group', [
                    'category_slug' => $slug,
                    'group_slug'    => $group->group_slug,
                    'city_slug'     => $group->city?->city_slug,
                    'area_slug'     => $group->area?->area_slug,
                ]);
                return 'Commented in group: <a href="' . $url . '">' . optional($group)->title . '</a>';
            case 'blog':
                $blog = Blog::find($post->publisher_id);

            $url = route('single.blog', [
                'category_slug' => $blog->lastCategory()?->category_slug ?? 'uncategorized',
                'blog_slug'     => $blog->blog_slug ?? 'blog',
                'city_slug'     => $blog->city?->city_slug ?? 'city',
                'area_slug'     => $blog->area?->area_slug ?? 'area',
            ]);
                return 'Commented on blog: <a href="' . $url . '">' . optional($blog)->title . '</a>' ;
            case 'product':
                $product = Marketplace::find($post->publisher_id);
                return 'Commented on product: ' . optional($product)->title;
        }
    }
    
    if($log->event_name === 'comment' && $log->content_type=='blog')
    {
     
          
                $blog = Blog::find($log->content_id);

            $url = route('single.blog', [
                'category_slug' => $blog->lastCategory()?->category_slug ?? 'uncategorized',
                'blog_slug'     => $blog->blog_slug ?? 'blog',
                'city_slug'     => $blog->city?->city_slug ?? 'city',
                'area_slug'     => $blog->area?->area_slug ?? 'area',
            ]);
                return 'Commented on blog: <a href="' . $url . '">' . optional($blog)->title . '</a>' ;
           
        }


    

    // ✅ MOVE THIS UP: 5. Post inside group/page/event/etc.
    if (
        $log->event_name === 'post' &&
        in_array($log->content_type, ['group', 'page', 'event', 'blog', 'product'])
    ) {
        switch ($log->content_type) {
            case 'group':
                $group = Group::find($log->content_id);

                $slug = \Str::slug($group->last_category?->category_slug ?? 'uncategorized');

                $url = route('single.group', [
                    'category_slug' => $slug,
                    'group_slug'    => $group->group_slug,
                    'city_slug'     => $group->city?->city_slug,
                    'area_slug'     => $group->area?->area_slug,
                ]);
                return 'Post in group: <a href="' . $url . '">' . optional($group)->title . '</a>';
            case 'page':
                $page = Page::with(['city', 'area', 'pageCategories'])->find($log->content_id);

                    $category = $page->pageCategories->last(); // last category as per your request

                    if ($page && $page->city && $page->area && $category) {
                        $url = route('single.page', [
                            'city_slug' => $page->city->city_slug,
                            'area_slug' => $page->area->area_slug,
                            'category_slug' => $category->category_slug,
                            'item_slug' => $page->item_slug,
                        ]);
                    }
                return 'Post on page: <a href="' . $url . '"> ' . optional($page)->title . '</a>';
            case 'event':
                $event = Event::find($log->content_id);

                $url = route('single.event', [
                    'city_slug' => $event->city?->city_slug ?? 'city',
                    'area_slug' => $event->area?->area_slug ?? 'area',
                    'category_slug' => $event->lastCategory()?->category_slug ?? 'uncategorized',
                    'event_slug' => $event->event_slug ?? 'event',
                ]);
                return 'Post at event: <a href="' . $url . '">' . optional($event)->title . '</a>';
            case 'blog':
                // $blog = Blog::find($log->content_id);
                // return 'Post on blog: ' . optional($blog)->title;
                $blog = Blog::find($log->content_id);

            $url = route('single.blog', [
                'category_slug' => $blog->lastCategory()?->category_slug ?? 'uncategorized',
                'blog_slug'     => $blog->blog_slug ?? 'blog',
                'city_slug'     => $blog->city?->city_slug ?? 'city',
                'area_slug'     => $blog->area?->area_slug ?? 'area',
            ]);

            $summary .= ' (Post on Blog: <a href="' . $url . '">' . optional($blog)->title . '</a>)';

            case 'product':
                $product = Marketplace::find($log->content_id);
                return 'Post on product: ' . optional($product)->title;
        }
    }

    elseif ($log->event_name === 'post' && $log->content_type === 'post') {
                $followedUser = User::find($log->content_id);
                $city_id = optional($followedUser->city)->id;
        }
    // 6. User posted a standalone post (not inside group etc.)
    if ($log->event_name === 'post' && $log->content_type === 'post') {
          $followedUser = User::find($log->content_id);
         $post = Posts::where('post_id', $log->activity_id)->first();
        if ($followedUser && $post) {
             $summary = '<a href="' . route('single.post', ['id' => $post->post_id]) . '"></a>';

            $summary .= ' — "' . \Str::limit(strip_tags($post->description), 50) . '"';
            //$text = \Str::limit(strip_tags($post->description), 50);
            return 'Posted: "' . $summary . '"';
        }
    }

    // 7. Generic events (like view, listing, etc.)
    if (
        in_array($log->event_name, [
            'view', 'review', 'like', 'enquiry_product', 'enquiry_listing', 'category_suggest',
            'blog_listing', 'report', 'user_registration', 'event_listing', 'video_post',
            'listing', 'marketplace_listing', 'marketplace_enquiry', 'group_listing','claim_listing',
        ]) && in_array($log->content_type, ['page', 'event', 'group', 'blog', 'product'])
    ) {
        switch ($log->content_type) {
            case 'page':
                 $page = Page::with(['city', 'area', 'pageCategories'])->find($log->content_id);

                    $category = $page->pageCategories->last(); // last category as per your request

                    if ($page && $page->city && $page->area && $category) {
                        $url = route('single.page', [
                            'city_slug' => $page->city->city_slug,
                            'area_slug' => $page->area->area_slug,
                            'category_slug' => $category->category_slug,
                            'item_slug' => $page->item_slug,
                        ]);
                    }
                           //return 'Followed page: <a href="' . $url . '"> ' . optional($page)->title . '</a>';
                return ucfirst($log->event_name) . ' on page: ' . '<a href="' . $url . '"> ' . optional($page)->title . '</a>';
            case 'event':
                $event = Event::find($log->content_id);

                $url = route('single.event', [
                    'city_slug' => $event->city?->city_slug ?? 'city',
                    'area_slug' => $event->area?->area_slug ?? 'area',
                    'category_slug' => $event->lastCategory()?->category_slug ?? 'uncategorized',
                    'event_slug' => $event->event_slug ?? 'event',
                ]);
                return ucfirst($log->event_name) . ' on event: <a href="' . $url . '">' . optional($event)->title . '</a>';
            case 'group':
                // $group = Group::find($log->content_id);
                // return ucfirst($log->event_name) . ' in group: ' . optional($group)->title;
                $group = Group::find($log->content_id);

                $slug = \Str::slug($group->last_category?->category_slug ?? 'uncategorized');

                $url = route('single.group', [
                    'category_slug' => $slug,
                    'group_slug'    => $group->group_slug,
                    'city_slug'     => $group->city?->city_slug,
                    'area_slug'     => $group->area?->area_slug,
                ]);

                return ucfirst($log->event_name) . ' in group: <a href="' . $url . '">' . e($group->title) . '</a>';
            case 'blog':
               
                $blog = Blog::find($log->content_id);

            $url = route('single.blog', [
                'category_slug' => $blog->lastCategory()?->category_slug ?? 'uncategorized',
                'blog_slug'     => $blog->blog_slug ?? 'blog',
                'city_slug'     => $blog->city?->city_slug ?? 'city',
                'area_slug'     => $blog->area?->area_slug ?? 'area',
            ]);
                return ucfirst($log->event_name) . ' on blog: <a href="' . $url . '">' . optional($blog)->title . '</a>' ;
            case 'product':
                $product = Marketplace::with(['page.city', 'page.area', 'page.pageCategories', 'productCategories'])->find($log->content_id);

                if ($product && $product->page && $product->page->city && $product->page->area) {

                    $page = $product->page;
                    $citySlug = $page->city->city_slug;
                    $areaSlug = $page->area->area_slug;
                    $pageCategorySlug = $page->pageCategories->last()?->category_slug ?? 'uncategorized';
                    $pageSlug = $page->item_slug;

                    $productCategorySlug = $product->productCategories->last()?->product_category_slug ?? 'uncategorized';
                    $productSlug = $product->product_slug;

                    $url = route('single.product', [
                        'city_slug' => $citySlug,
                        'area_slug' => $areaSlug,
                        'category_slug' => $pageCategorySlug,
                        'item_slug' => $pageSlug,
                        'product_category_slug' => $productCategorySlug,
                        'product_slug' => $productSlug,
                    ]);

                    return ucfirst($log->event_name).' (<a href="' . $url . '">on Product: ' . e($product->title) . '</a>)';
                } else {
                    return ucfirst($log->event_name).' (on Product: ' . optional($product)->title . ')';
                }

                //return ucfirst($log->event_name) . ' on product: ' . optional($product)->title;
        }
    }

    // 8. Profile visits, likes etc.
    if ($log->content_type === 'profile') {
        $targetUser = User::find($log->content_id);
        if ($targetUser) {
    $url = route('user.profile.view', ['id' => $targetUser->id]);
    return ucfirst($log->event_name) . ' on user: <a href="' . $url . '">' . e($targetUser->name) . '</a>';
        }

        return ucfirst($log->event_name) . ' on unknown user';
    }

    // 9. Fallback
    return 'Activity on unknown content';
}





private function getCityIdFromLog($log, $user)
{
    $city_id = null;

    if ($log->event_name === 'profile_update') {
        $city_id = optional($user->city)->id;
    }

    elseif ($log->event_name === 'follow') {
        if ($log->content_type === 'profile') {
            $followedUser = User::find($log->content_id);
            $city_id = optional($followedUser->city)->id;
        } elseif ($log->content_type === 'page') {
            $page = Page::find($log->content_id);
            $city_id = optional($page)->city_id;
        } elseif ($log->content_type === 'group') {
            $group = Group::find($log->content_id);
            $city_id = optional($group)->city_id;
        }
    }

    elseif ($log->event_name === 'comment' && $log->content_type === 'post') {
        $post = Posts::where('post_id', $log->content_id)->first();
        $followedUser = User::find($post->user_id ?? null);
        $city_id = optional($followedUser->city)->id;
    }

    elseif ($log->event_name === 'comment' && in_array($log->content_type, ['page', 'event', 'group', 'product'])) {
        $post = Posts::where('post_id', $log->content_id)->first();
        if ($post) {
            switch ($post->publisher) {
                case 'page':
                    $city_id = optional(Page::find($post->publisher_id))->city_id;
                    break;
                case 'event':
                    $city_id = optional(Event::find($post->publisher_id))->city_id;
                    break;
                case 'group':
                    $city_id = optional(Group::find($post->publisher_id))->city_id;
                    break;
                case 'blog':
                    $city_id = optional(Blog::find($post->publisher_id))->city_id;
                    break;
                case 'product':
                    $marketplace = Marketplace::find($post->publisher_id);
                    $city_id = optional($marketplace?->page)->city_id;
                    break;
            }
        } else {
            switch ($log->content_type) {
                case 'page': $city_id = optional(Page::find($log->content_id))->city_id; break;
                case 'event': $city_id = optional(Event::find($log->content_id))->city_id; break;
                case 'group': $city_id = optional(Group::find($log->content_id))->city_id; break;
                case 'product': $city_id = optional(Marketplace::find($log->content_id)?->page)->city_id; break;
            }
        }
    }

    elseif ($log->event_name === 'comment' && $log->content_type === 'blog') {
        $city_id = optional(Blog::find($log->content_id))->city_id;
    }

    elseif ($log->event_name === 'post' && $log->content_type === 'post') {
        $followedUser = User::find($log->content_id);
        $city_id = optional($followedUser->city)->id;
    }

    elseif ($log->event_name === 'like' && $log->content_type === 'post') {
        $post = Posts::where('post_id', $log->content_id)->first();
        if ($post) {
            switch ($post->publisher) {
                case 'page': $city_id = optional(Page::find($post->publisher_id))->city_id; break;
                case 'event': $city_id = optional(Event::find($post->publisher_id))->city_id; break;
                case 'group': $city_id = optional(Group::find($post->publisher_id))->city_id; break;
                case 'blog': $city_id = optional(Blog::find($post->publisher_id))->city_id; break;
                case 'product': $city_id = optional(Marketplace::find($post->publisher_id)?->page)->city_id; break;
                default:
                    $followedUser = User::find($post->user_id);
                    $city_id = optional($followedUser->city)->id;
            }
        } else {
            $post = Posts::where('post_id', $log->activity_id)->first();
            $followedUser = User::find($post->user_id ?? null);
            $city_id = optional($followedUser->city)->id;
        }
    }

    elseif (
        in_array($log->event_name, [
            'view', 'review','like','enquiry_product','enquiry_listing','category_suggest',
            'blog_listing','report','user_registration','event_listing','video_post','listing',
            'marketplace_listing','marketplace_enquiry','group_listing','post','claim_listing'
        ]) &&
        in_array($log->content_type, ['page', 'event', 'group', 'blog', 'product'])
    ) {
        switch ($log->content_type) {
            case 'page': $city_id = optional(Page::find($log->content_id))->city_id; break;
            case 'event': $city_id = optional(Event::find($log->content_id))->city_id; break;
            case 'group': $city_id = optional(Group::find($log->content_id))->city_id; break;
            case 'blog': $city_id = optional(Blog::find($log->content_id))->city_id; break;
            case 'product': $city_id = optional(Marketplace::find($log->content_id)?->page)->city_id; break;
        }
    }

    elseif ($log->content_type && $log->content_id) {
        $post = Posts::where('post_id', $log->content_id)->first();
        if ($post) {
            switch ($post->publisher) {
                case 'page': $city_id = optional(Page::find($post->publisher_id))->city_id; break;
                case 'group': $city_id = optional(Group::find($post->publisher_id))->city_id; break;
                case 'event': $city_id = optional(Event::find($post->publisher_id))->city_id; break;
                case 'blog': $city_id = optional(Blog::find($post->publisher_id))->city_id; break;
            }
        }
    }

    return $city_id;
}


}
