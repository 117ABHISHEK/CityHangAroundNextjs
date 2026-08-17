<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ModalController, MainController, StoryController, Profile, Updater, InstallController};
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\UserActivityController;
use App\Helpers\CityHelper;
use App\Models\City;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\Event\EventController;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

use App\Http\Controllers\MenuDemoController;
//use App\Http\Controllers\UniversalAnalyticsController;
//use App\Http\Controllers\DevDashboardController;


Route::get('/search', [SearchController::class, 'search'])
    ->name('search')
    ->middleware(['web', 'auth', 'verified', 'activity', 'prevent-back-history']);

//Route::get('/dev-dashboard', [DevDashboardController::class, 'index']);



//Route::get('/admin/universal-dashboard', [UniversalAnalyticsController::class, 'index']);







Route::get('/master-dashboard', [MenuDemoController::class, 'masterDashboard']);



Route::get('/build-master-aggregate', [MenuDemoController::class, 'buildMasterAggregate']);
Route::get('/build-content-master', [MenuDemoController::class, 'buildContentMaster']);


Route::get('/build-city-guide-aggregate', [MenuDemoController::class, 'buildCityGuideAggregate']);
Route::get('/build-market-aggregate', [MenuDemoController::class, 'buildMarketAggregate']);
Route::get('/build-community-aggregate', [MenuDemoController::class, 'buildCommunityAggregate']);
Route::get('/build-event-aggregate', [MenuDemoController::class, 'buildEventAggregate']);
Route::get('/build-blog-aggregate', [MenuDemoController::class, 'buildBlogAggregate']);


Route::get('/menu-demo', [MenuDemoController::class, 'viewMenu'])->name('menu.demo.view');



// Smart City Search Route
Route::get('/get-smart-cities', [MenuDemoController::class, 'getSmartCities'])->name('city.smart_search');
Route::get('/get-menu-by-city', [MenuDemoController::class, 'getAjaxMenu']);






/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::group(['domain' => '{subdomain}.localhost'], function(){ 
//     Route::any('/sssss', function($subdomain) {
//         return 'Subdomain ' . $subdomain; 
//     }); 
// });


/*
|--------------------------------------------------------------------------
| HEALTH ROUTE (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->get('/health', HealthCheckResultsController::class)
    ->name('health');

Route::get('/qna', function () {
    return view('frontend.qna_index'); // landing.blade.php inside resources/views/
});

Route::get('ajax/cities/enquiry', [EnquiryController::class, 'index'])
    ->name('ajax.cities.enquiry'); 
 Route::get('/', [MainController::class, 'main'])->name('main');
 // routes/api.php
 
  Route::post('/load-cities-ajax', [MainController::class, 'loadCitiesajax'])->name('load-cities-ajax');
  Route::get('/load-all-cities-json', [MainController::class, 'loadAllCitiesJson'])->name('load-all-cities-json');

Route::get('/search/all', [MainController::class, 'universalSearch'])->name('universal.search');


Route::get('/test-blaze', function () {
    try {
        // Attempt to list all files in the bucket
        $files = Storage::disk('blaze_s3')->allFiles();
        return response()->json(['success' => true, 'files' => $files]);
    } catch (\Exception $e) {
        // If there is an error, return the error message
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
});

//temporay router for landing page
Route::get('/landing', function () {
    return view('landing'); // landing.blade.php inside resources/views/
});


Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// It is duplicate route so commented 
// Route::get('/', function () {
//     $page_data['all_cities'] = CityHelper::getActiveCities();

//     $topCities = City::withCount('pages')
//         ->where('is_approved', 'Y')
//         ->orderByDesc('pages_count')
//         ->take(10)
//         ->get();

//     $page_data['top_cities'] = $topCities;

//     return view('index', $page_data);
// });


 // Route::get('event/view/{id}', [EventController::class, 'show'])->name('single.event');


Route::get('auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback'])->name('facebook.callback');



Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return 'Application cache cleared';
});

Route::get('/queue-run', function () {

Artisan::call('queue:restart');

return 'Queue running';
});



Route::get('/auth-checker', function () {
    if(auth::check()){
        return true;
    }else{
        return false;
    }
})->name('auth-checker');

//Passing param
Route::get('/users/{user_id}', function ($user_id) {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';

Route::get('language/switch/{language}', function (Request $request, $language) {
    $request->session()->put('active_language',$language);
    return redirect()->back();
})->name('language.switch');


//Modal controllers group routing
Route::controller(ModalController::class)->middleware('auth', 'verified', 'activity')->group(function () {
    Route::any('/load_modal_content/{view_path}', 'common_view_function')->name('load_modal_content');
});

//Home controllers group routing
Route::controller(MainController::class)->middleware('auth', 'verified', 'activity', 'prevent-back-history')->group(function () {
    Route::get('/home', 'timeline')->name('timeline');
    Route::post('/create_post', 'create_post')->name('create_post');
    Route::get('/edit_post_form/{id}', 'edit_post_form')->name('edit_post_form');
    Route::post('/edit_post/{id}', 'edit_post')->name('edit_post');
    Route::get('/load_post_by_scrolling', 'load_post_by_scrolling')->name('load_post_by_scrolling');
    Route::post('/my_react', 'my_react')->name('my_react');
    Route::get('/my_comment_react', 'my_comment_react')->name('my_comment_react');
    Route::get('/post_comment', 'post_comment')->name('post_comment');
    Route::get('/load_post_comments', 'load_post_comments')->name('load_post_comments');
    Route::get('/search_friends_for_tagging', 'search_friends_for_tagging')->name('search_friends_for_tagging');



    Route::get('/live/{post_id}', 'live')->name('live');
    Route::get('/live-ended/{post_id}', 'live_ended')->name('zoom-meeting-leave-url');
    


    Route::get('/view/single/post/{id?}', 'single_post')->name('single.post');

    Route::get('/preview_post', 'preview_post')->name('preview_post');

    Route::get('/post_comment_count', 'post_comment_count')->name('post_comment_count');

    Route::post('/post/report/save/', 'save_post_report')->name('save.post.report');

    Route::get('/delete/my/post', 'post_delete')->name('post.delete');

    Route::get('comment/delete', 'comment_delete')->name('comment.delete');

    Route::post('share/on/group', 'share_group')->name('share.group.post');
    Route::post('share/on/my/timeline', 'share_my_timeline')->name('share.my.timeline');

    // share page view 
    Route::get('custom/shared/post/view/{id}', 'custom_shared_post_view')->name('custom.shared.post.view');

    //remove media files
    Route::get('media/file/delete/{id}', 'delete_media_file')->name('media.file.delete');
});

//Story controllers group routing
Route::controller(StoryController::class)->middleware('auth', 'verified', 'activity')->group(function () {
    Route::post('/create_story', 'create_story')->name('create_story');

    Route::any('/stories/{offset?}/{limit?}', 'stories')->name('stories');

    Route::any('/stories/{offset?}/{limit?}', 'stories')->name('stories');
    Route::any('/story_details/{story_id}/{offset?}/{limit?}', 'story_details')->name('story_details');
    Route::any('/single_story_details/{story_id}', 'single_story_details')->name('single_story_details');
});

//Profile controllers group routing
Route::controller(Profile::class)->middleware('auth', 'verified', 'activity', 'prevent-back-history')->group(function () {
    Route::get('/profile', 'profile')->name('profile');
    Route::get('/profile/load_post_by_scrolling', 'load_post_by_scrolling')->name('profile.load_post_by_scrolling');
    Route::get('/profile/friends', 'friends')->name('profile.friends');

    Route::get('/profile/photos', 'photos')->name('profile.photos');
    Route::get('/profile/load_photos', 'load_photos')->name('profile.load_photos');

    Route::any('/profile/album/{action_type?}', 'album')->name('profile.album');
    Route::get('/profile/load_albums', 'load_albums')->name('profile.load_albums');

    Route::get('/profile/videos', 'videos')->name('profile.videos');
    Route::get('/profile/load_videos', 'load_videos')->name('profile.load_videos');
    Route::post('/profile/upload_video', 'upload_video')->name('profile.upload_video');


    Route::get('/profile/page', 'pages')->name('profile.page');
    
    Route::get('/blog/create', [BlogController::class, 'create'])->name('create.blog');

    Route::get('/profile/blogs', 'blogs')->name('profile.blogs');
    Route::get('/profile/events', 'events')->name('profile.events');
    Route::get('/profile/groups', 'groups')->name('profile.groups');
    Route::get('/profile/products', 'products')->name('profile.products');

    Route::get('/profile/load_my_friends', 'load_my_friends')->name('profile.load_my_friends');
    Route::get('/profile/load_my_friend_requests', 'load_my_friend_requests')->name('profile.load_my_friend_requests');

    Route::post('/profile/accept_friend_request', 'accept_friend_request')->name('profile.accept_friend_request');
    Route::get('/profile/delete_friend_request', 'delete_friend_request')->name('profile.delete_friend_request');

    Route::post('/profile/about/{action_type?}', 'about')->name('profile.about');
    Route::any('/profile/my_info/{action_type?}', 'my_info')->name('profile.my_info');
    Route::get('/profile/load_photo_and_videos', 'load_photo_and_videos')->name('profile.load_photo_and_videos');

    Route::post('/profile/upload_photo/{photo_type}', 'upload_photo')->name('profile.upload_photo');

    Route::get('/profile/update_profile', 'load_my_profile')->name('profile.load_my_profile');
    Route::post('/profile/update_profile/', 'update_profile')->name('profile.update_profile');
    
    // Profile view tracking
    Route::post('/profile/track_view', 'trackView')->name('profile.track_view');
});

//Updater routes are here
Route::controller(Updater::class)->middleware('auth', 'verified', 'activity')->group(function () {

    Route::post('admin/addon/create', 'update')->name('admin.addon.create');
    Route::post('admin/addon/update', 'update')->name('admin.addon.update');
    Route::post('admin/product/update', 'update')->name('admin.product.update');

});
//End Updater routes

Route::prefix('event')->group(function () {

    Route::get('{city}/{area}/{category}/{event}',
        [EventController::class, 'single_event']);

});


Route::get('/user/activity', [UserActivityController::class, 'index'])->name('user.activity');
// web.php ya admin.php (jo bhi dashboard ke liye use kar rahe ho)
Route::get('user/activity/cities', [UserActivityController::class, 'cityBreakdown'])->name('user.activity.cities');
Route::get('user/activity/city/{cityId}', [UserActivityController::class, 'cityActivityReport'])->name('city.activity.report');





Route::post('/submit-contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.submit');

Route::get('/admin/contact-queries', [App\Http\Controllers\Admin\ContactQueryController::class, 'index'])
    ->name('admin.contact.queries');














//Installation routes
Route::controller(InstallController::class)->group(function () {

    Route::get('/install_ended', 'index');
    Route::get('install/step0', 'step0')->name('step0');
    Route::get('install/step1', 'step1')->name('step1');
    Route::get('install/step2', 'step2')->name('step2');
    Route::any('install/step3', 'step3')->name('step3');
    Route::get('install/step4', 'step4')->name('step4');
    Route::get('install/step4/{confirm_import}', 'confirmImport')->name('step4.confirm_import');
    Route::get('install/install', 'confirmInstall')->name('confirm_install');
    Route::post('install/validate', 'validatePurchaseCode')->name('install.validate');
    Route::any('install/finalizing_setup', 'finalizingSetup')->name('finalizing_setup');
    Route::get('install/success', 'success')->name('success');

});
//Installation routes

// Simple page view route
Route::get('/page/{id}', function($id) {
    $page = \App\Models\Page::with(['user', 'category', 'city', 'area', 'categories'])->find($id);
    if (!$page) {
        abort(404);
    }
   $categorySlug =
        optional($page->category)->category_slug
        ?? optional($page->categories->first())->category_slug
        ?? 'category';
    return redirect()->route('single.page', [
        'city_slug' =>optional ($page->city)->city_slug ?: 'city',
        'area_slug' =>optional ($page->area)->area_slug ?: 'area', 
        'category_slug' =>optional($page->category)->category_slug ?: optional($page->categories)->first()->category_slug ?: 'category',
        'item_slug' => $page->item_slug ?? $page->id
    ]);
})->name('page.view.simple');


// Route for Subscription page opens up by clicking promotion

Route::get('/public-subscriptions', function () {
    $subscriptions = Subscription::with('features')->get();
    return view('public.public_subscriptions', compact('subscriptions'));
})->name('public.subscriptions');


use Illuminate\Support\Facades\Cache;

Route::get('/test-redis-cache', function () {
    Cache::put('test-key', 'Yes, Redis cache works!', 10);
    return Cache::get('test-key');
});

use App\Http\Controllers\AdminCrudController;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (PROTECTED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('backend.index');
    })->name('admin.dashboard');

    Route::get('/product-categories', [AdminCrudController::class, 'view_product_category'])
        ->name('admin.product.categories');

    Route::get('/dev-tools', [\App\Http\Controllers\DevToolsController::class, 'index'])->name('admin.dev-tools');
    Route::post('/dev-tools/clear', [\App\Http\Controllers\DevToolsController::class, 'clear'])->name('admin.dev-tools.clear');
    Route::get('/dev-tools/log/download/{filename}', [\App\Http\Controllers\DevToolsController::class, 'downloadLog'])->name('admin.dev-tools.download-log');
    Route::get('/dev-tools/log/delete/{filename}', [\App\Http\Controllers\DevToolsController::class, 'deleteLog'])->name('admin.dev-tools.delete-log');
    Route::get('/dev-tools/logs/delete-all', [\App\Http\Controllers\DevToolsController::class, 'deleteAllLogs'])->name('admin.dev-tools.delete-all-logs');

});


/*testing*/
Route::get('/clean-top-cities', function () {
    $dir = public_path();
    $files = scandir($dir);
    $target = $dir . '/get_top_cities.php';
    $msg = "Files in public_path ($dir):\n" . implode("\n", $files);
    if (file_exists($target)) {
        @unlink($target);
        $msg .= "\n\nDELETED successfully!";
    } else {
        $msg .= "\n\nget_top_cities.php not found at: $target";
    }
    $doc_root = $_SERVER['DOCUMENT_ROOT'] ?? '';
    if ($doc_root && $doc_root !== $dir) {
        $msg .= "\n\nFiles in DOCUMENT_ROOT ($doc_root):\n" . implode("\n", scandir($doc_root));
        $target2 = $doc_root . '/get_top_cities.php';
        if (file_exists($target2)) {
            @unlink($target2);
            $msg .= "\n\nDELETED from DOCUMENT_ROOT!";
        }
    }
    return response($msg)->header('Content-Type', 'text/plain');
});

Route::get('/test-chatgpt', function () {
    return 'TEST123456789';
});


