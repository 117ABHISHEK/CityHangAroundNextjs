<?php

use App\Http\Controllers\AdminCrudController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CustomUserController;
use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Profile;
use App\Http\Controllers\Report\SearchController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\PaymentHistory;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpamWordController;
use App\Http\Controllers\EnquiryLeadStageController;

use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionFeatureController;
use App\Http\Controllers\SubscriptionFeatureMappingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventMasterController;
use App\Http\Controllers\CustomPageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CampaignTemplateController;
use App\Http\Controllers\MailingListController;
use App\Http\Controllers\CategoryBulkUpdateController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PageChatController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\MarketplaceChatController;
use App\Http\Controllers\ActivityController;



Route::get('/search-globally', [SearchController::class, 'search_globally'])->name('search.globally');
Route::get('profile/about', [Profile::class, 'aboutPage'])
    ->middleware('auth', 'verified', 'activity', 'prevent-back-history')
    ->name('profile.about.page');


// ✅ Public search route - accessible without login (used by dropdown menus)
Route::controller(SearchController::class)->middleware('activity', 'prevent-back-history')->group(function () {
    Route::get('/search', 'search')->name('search');
});

// Auth-protected search sub-routes (social features, require login)
Route::controller(SearchController::class)->middleware('auth', 'verified', 'activity', 'prevent-back-history')->group(function () {
    Route::get('/search/people/', 'search_people')->name('search.people');
    Route::get('/search/post/', 'search_post')->name('search.post');
    Route::get('/search/video/', 'search_video')->name('search.video');
    Route::get('/search/product/', 'search_product')->name('search.product');
    Route::get('/search/page/', 'search_page')->name('search.page');
    Route::get('/search/group/', 'search_group')->name('search.group.specific');
    Route::get('/search/event/', 'search_event')->name('search.event');
});


// SEO Search Routes
Route::get('search/{city_slug}/{category_slug}', [\App\Http\Controllers\Report\SearchController::class, 'search'])->name('search.seo');
Route::get('search/{category_slug}', [\App\Http\Controllers\Report\SearchController::class, 'search'])->name('search.category.only');







Route::get('/test-mail', function () {
    try {
        Mail::raw('Test email from Laravel', function ($message) {
            $message->to('garg.sanjay5@gmail.com')
                    ->subject('Test Email');
        });
        return 'Email sent!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});



// Main sitemap index
Route::get('/sitemap', [SitemapController::class, 'index']);
Route::get('/sitemap.xml', [SitemapController::class, 'index']);

// Dynamic sitemaps for different content types
// Multiple page sitemaps for performance (split large dataset)
Route::get('/sitemap/pages-{part}.xml', [SitemapController::class, 'pagesSitemap'])->where('part', '[0-9]+');
// Legacy single pages sitemap (redirects to part 1)
Route::get('/sitemap/pages.xml', function() {
    return redirect('/sitemap/pages-1.xml');
});

Route::get('/sitemap/events.xml', [SitemapController::class, 'eventsSitemap']);
Route::get('/sitemap/marketplace.xml', [SitemapController::class, 'marketplaceSitemap']);
Route::get('/sitemap/blogs.xml', [SitemapController::class, 'blogsSitemap']);
Route::get('/sitemap/videos.xml', [SitemapController::class, 'videosSitemap']);
Route::get('/sitemap/posts.xml', [SitemapController::class, 'postsSitemap']);
Route::get('/sitemap/groups.xml', [SitemapController::class, 'groupsSitemap']);
Route::get('/sitemap/static.xml', [SitemapController::class, 'staticSitemap']);

// Legacy sitemap routes (keep for backward compatibility)
Route::get('/sitemaplisting', [SitemapController::class, 'sitemaplisting']);
Route::get('/sitemaplisting2', [SitemapController::class, 'sitemaplisting2']);
Route::get('/sitemaplisting3', [SitemapController::class, 'sitemaplisting3']);
Route::get('/sitemaplisting4', [SitemapController::class, 'sitemaplisting4']);
Route::get('/sitemaplisting5', [SitemapController::class, 'sitemaplisting5']);
Route::get('/sitemaplisting6', [SitemapController::class, 'sitemaplisting6']);

Route::get('/sitemappagecategorylisting', [SitemapController::class, 'sitemappagecategorylisting']);
Route::get('/sitemappagecategorylisting2', [SitemapController::class, 'sitemappagecategorylisting2']);
Route::get('/sitemappagecategorylisting3', [SitemapController::class, 'sitemappagecategorylisting3']);
Route::get('/sitemappagecategorylisting4', [SitemapController::class, 'sitemappagecategorylisting4']);
Route::get('/sitemappagecategorylisting5', [SitemapController::class, 'sitemappagecategorylisting5']);
Route::get('/sitemappagecategorylisting6', [SitemapController::class, 'sitemappagecategorylisting6']);
// Admin Subscription Routes
Route::prefix('admin')->name('admin.')->middleware('auth', 'verified', 'admin', 'prevent-back-history')->group(function () {

    // Subscription Routes
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    // Route::get('/subscriptions/{id}/edit', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
    Route::get('subscriptions/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
Route::get('/ajax/areas/{city_id}', [SubscriptionController::class, 'getAreasByCity']);

    Route::put('subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
Route::delete('subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');


Route::get('/transactions/report', [SubscriptionController::class, 'adminTransactionsReport'])
    ->name('transactions.report'); 
    Route::get('/user/search', [SubscriptionController::class, 'searchUsers'])->name('user.search');


    // Feature Routes
    Route::get('/features', [SubscriptionFeatureController::class, 'index'])->name('features.index');
    Route::get('/features/create', [SubscriptionFeatureController::class, 'create'])->name('features.create');
    Route::post('/features', [SubscriptionFeatureController::class, 'store'])->name('features.store');
    
    Route::get('features/{feature}/edit', [SubscriptionFeatureController::class, 'edit'])->name('features.edit');
    Route::put('features/{feature}', [SubscriptionFeatureController::class, 'update'])->name('features.update');


    Route::delete('/features/{feature}', [SubscriptionFeatureController::class, 'destroy'])->name('features.destroy');

    // Subscription Feature Mapping Routes
    Route::get('/mappings', [SubscriptionFeatureMappingController::class, 'index'])->name('mappings.index');
    Route::get('/mappings/create', [SubscriptionFeatureMappingController::class, 'create'])->name('mappings.create');
    Route::post('/mappings', [SubscriptionFeatureMappingController::class, 'store'])->name('mappings.store');
    Route::get('mappings/{mapping}/edit', [SubscriptionFeatureMappingController::class, 'edit'])->name('mappings.edit');
    Route::put('mappings/{mapping}', [SubscriptionFeatureMappingController::class, 'update'])->name('mappings.update');
    Route::delete('/mappings/{mapping}', [SubscriptionFeatureMappingController::class, 'destroy'])->name('mappings.destroy');

});

Route::resource('event_master', EventMasterController::class)
    ->middleware(['auth', 'verified', 'activity', 'prevent-back-history'])
    ->names([
        'index'   => 'admin.event.index',
        'create'  => 'admin.event.score.create',
        'store'   => 'admin.event.score.store',
        'edit'    => 'admin.event.score.edit',
        'update'  => 'admin.event.score.update',
        'destroy' => 'admin.event.score.destroy'
    ]);


Route::middleware(['auth', 'verified', 'admin', 'activity', 'prevent-back-history'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{id}/edit-lead-price', [CategoryController::class, 'edit'])->name('categories.editLeadPrice');
    Route::post('/categories/{id}/update-lead-price', [CategoryController::class, 'update'])->name('categories.updateLeadPrice');
    Route::post('/categories/{id}/delete-lead-price', [CategoryController::class, 'deleteLeadPrice'])->name('categories.deleteLeadPrice');
});



// events route

Route::controller(EventController::class)->middleware('auth', 'verified', 'activity', 'prevent-back-history')->group(function () {
    
    Route::get('event-categories-autocomplete-ajax','dataAjax');
    Route::post('/ajax/store/event/categories', 'storecategories')->name('ajax.store.event.categories');
    Route::get('/ajax/parent/event/catgories', 'jsonGetParentCategories')->name('page.json.parent.event.catgories');
    Route::get('/ajax/event/catgories', 'jsonGetCategories')->name('page.json.event.catgories');
    Route::get('/events/create', 'create')->name('events.create');
    Route::get('events/edit/{id}', 'edit')->name('events.edit');
    Route::get('user/event', 'userevent')->name('userevent');
    Route::post('/event/store', 'store')->name('event.store');
    Route::post('/event/update/{id}', 'update')->name('event.update');
    Route::get('event/delete', 'event_delete')->name('event.delete');
    Route::get('event/view/{id}', 'single_event')->name('event.view');
    // event going 
    Route::get('event/going/{id}', 'event_going')->name('event.going');
    Route::get('event/notgoing/{id}', 'event_notgoing')->name('event.notgoing');
    // event interested 
    Route::get('event/interested/{id}', 'event_interested')->name('event.interested');
    Route::get('event/notinterested/{id}', 'event_notinterested')->name('event.notinterested');
    // load event 
    Route::get('/load_event_by_scrolling', 'load_event_by_scrolling')->name('load_event_by_scrolling');

    // invite route to friend 
    Route::get('/event/invite/{invited_friend_id}/{requester_id}/{event_id}', 'event_invite')->name('event.invite');
    // view all route 
    Route::get('/share/event/', 'shareevent')->name('event.share');


    Route::post('event/invites/sent', 'sent_invition')->name('event.invition');
    Route::get('/search_user_for_event_inviting', 'search_user_for_event_inviting')->name('search_user_for_event_inviting');
});

// marketplace route 


Route::controller(MarketplaceController::class)->middleware('auth', 'verified', 'activity', 'prevent-back-history')->group(function () {
    

    Route::get('/search-pages',  'searchPages');


    Route::get('/product-category/get-parent/{id}', 'getParent');

    Route::get('/check-if-subcategory', 'checkIfSubcategory');
Route::get('/category-autocomplete', 'autocomplete');

Route::get('/check-if-subcategory',  'checkIfproductSubcategory');


Route::get('ajax/product/areas/{city_id}', 'jsonGetProductAreasByCity')->name('json.product.area');
    Route::post('/product-categories-create-from-select2', 'createCategoryFromSelect2');
    Route::get('/products/create', 'create')->name('pages.create.product');
     Route::get('/product/suggestions', 'getSuggestions')->name('product.suggestions');
     Route::get('/product/details', 'getDetails')->name('product.details');
    Route::get('/get-category-names', 'getCategoryNames')->name('category.names');;
    
    Route::get('/products/edit/{product_id}', 'edit')->name('product.edit');
    
    Route::get('/ajax/productparentcatgories', 'jsonGetParentCategories')->name('page.json.parent.product.catgories');
    Route::get('/ajax/produ/ctcatgories', 'jsonGetCategories')->name('page.json.product.catgories');
    Route::post('/ajax/storeproductcategories', 'storecategories')->name('ajax.storeproductcategories');
    Route::post('/ajax/storeparentcategories', 'storeparentcategories')->name('ajax.storeproductcategories.parent');

    Route::get('/ajax/productbrand', 'jsonGetproductbrand')->name('product.json.brand');

    Route::post('/ajax/storebrand', 'storebrand')->name('ajax.store.brand');

    Route::get('product-categories-autocomplete-ajax', 'dataAjax');
    Route::get('user/product', 'userproduct')->name('userproduct');
    Route::POST('/product/store', 'store')->name('product.store');
    Route::post('/update/product/{id}', 'update')->name('product.update');
    Route::get('product/delete', 'product_delete')->name('product.delete');
    Route::get('/load_product_by_scrolling', 'load_product_by_scrolling')->name('load_product_by_scrolling');
    
    

    Route::get('/product/saved/', 'saved_product')->name('product.saved');

    Route::get('save/product/{id}', 'save_for_later')->name('save.product.later');
    Route::get('unsave/product/{id}', 'unsave_for_later')->name('unsave.product.later');
    Route::get('product/iframe/view/{id}', 'single_product_ifrane')->name('single.product.iframe');
});

Route::controller(MarketplaceController::class)->group(function () {
    Route::get('ajax/cities/enquiry','getCities')->name('ajax.cities.enquiry');
    Route::get('ajax/products','getProducts')->name('ajax.products');
    Route::post('/enquiry','storeenquiry')->name('enquiry.store');
});

//  blog 
Route::controller(BlogController::class)->middleware('auth', 'verified', 'activity', 'prevent-back-history')->group(function () {
    
    Route::get('/create/blog', 'create')->name('create.blog.legacy');
    Route::get('my/blog', 'myblog')->name('myblog');
    Route::POST('/blog/store', 'store')->name('blog.store');
    Route::get('/edit/blog/{id}', 'edit')->name('blog.edit');
    Route::post('/update/blog/{id}', 'update')->name('blog.update');
    Route::get('blog/delete', 'delete')->name('blog.delete');
    Route::get('/load_blog_by_scrolling', 'load_blog_by_scrolling')->name('load_blog_by_scrolling');
    // Route::get('blog/view/{id}', 'single_blog')->name('single.blog');
    // Route::get('/blog/category/{category}', 'category_blog')->name('category.blog');
    Route::get('/blog/search/', 'search')->name('search.blog');

    Route::get('/ajax/get-pages',  'getPages')->name('ajax.get.pages');


    Route::get('blog-categories-autocomplete-ajax', 'dataAjax');
    Route::post('/blog-categories-create-from-select2', 'createCategoryFromSelect2');
    Route::post('/ajax/storeblogcategories', 'storeblogcategories')->name('ajax.store.blog.categories');
    Route::get('/ajax/blog/catgories', 'jsonGetBlogCategories')->name('page.json.blog.catgories');
    Route::get('/ajax/blog/parentcatgories', 'jsonGetBlogParentCategories')->name('page.json.parent.blog.catgories');
    
});


//  page 
Route::controller(PageController::class)->middleware('auth', 'verified', 'activity', 'prevent-back-history')->group(function () {
    Route::post('/claim-listing/submit', 'submitClaim')->name('claim.listing.submit');
    Route::get('/categories/search', 'search')->name('categories.search');
Route::get('/categories/selected', 'selected')->name('categories.selected');

Route::post('/page-categories-create-from-select2', 'createCategoryFromSelect2');
    

    Route::post('/page/save-draft', 'saveIncomplete')->name('page.save.draft');
    Route::get('/listings/incomplete','showIncompleteListings')->name('listings.incomplete');
    Route::get('/incomplete-listings/{id}/resume', 'resumeIncompleteListing')->name('incomplete.resume');
Route::delete('/incomplete-listings/{id}', 'deleteIncompleteListing')->name('incomplete.delete');

    Route::get('/get-states/{country_id}', 'getStates');


    Route::delete('/media/{id}', 'destroy');
    
    Route::get('/pages/user', 'userpages')->name('pages.user');
    Route::get('/pages/suggested', 'suggestedpages')->name('pages.suggested');
    Route::get('/pages/joined', 'joinedpages')->name('pages.joined');
    Route::get('/pages/incomplete', 'incompletepages')->name('pages.incomplete');
    Route::get('/pages/create', 'create')->name('pages.create');
    Route::post('/page/check-match', 'checkMatch')->name('page.check.match');
    Route::get('/page/suggestions', 'getPageSuggestions')->name('page.suggestions');


    Route::get('pages/edit/{id}', 'edit')->name('pages.edit');
    Route::POST('/page/store', 'store')->name('page.store');
   Route::get('pages/delete', 'page_delete')->name('pages.delete');
    Route::post('/update/page/{id}', 'update')->name('page.update');
    Route::post('/update/coverphoto/page/{id}', 'updatecoverphoto')->name('page.coverphoto');
    Route::post('/update/info/page/{id}', 'updateinfo')->name('page.update.info');
    Route::get('/load_page_by_scrolling', 'load_page_by_scrolling')->name('load_page_by_scrolling');
    // Route::get('page/view/{id}', 'single_page')->name('single.page');
    
   
    Route::get('/page/load_videos', 'load_videos')->name('page.load_videos');

    Route::get('page/like/{id}', 'like')->name('page.like');
    Route::get('page/dislike/{id}', 'dislike')->name('page.dislike');
    Route::get('categories-autocomplete-ajax', 'dataAjax');
    Route::get('states-autocomplete-ajax', 'StatedataAjax');
    Route::get('country-autocomplete-ajax', 'CountrydataAjax');
    Route::get('area-autocomplete-ajax', 'AreadataAjax');
    Route::get('city-autocomplete-ajax', 'CitydataAjax');
    Route::get('ajax/cities/{state_id}', 'jsonGetCitiesByState')->name('json.city');
    Route::get('ajax/areas/{city_id}', 'jsonGetAreasByCity')->name('json.area');


    
    Route::post('/ajax/storecategories', 'storecategories')->name('ajax.storecategories');
    
    Route::get('ajax/categories/{city_id}', 'jsonGetCategoriesByCity')->name('json.category.city');

    Route::post('/ajax/storecategories', 'storecategories')->name('ajax.storecategories');
    Route::post('/ajax/storecities', 'storecities')->name('ajax.storecities');
    Route::post('/ajax/storeareas', 'storeareas')->name('ajax.storeareas');
    Route::get('/ajax/catgories', 'jsonGetCategories')->name('page.json.catgories');
    Route::get('/ajax/parentcatgories', 'jsonGetParentCategories')->name('page.json.parent.catgories');

    Route::get('pages/delete-faq/{id}', 'deletefaq')->name('pages.faq.delete');
});

//  group 
Route::controller(GroupController::class)->middleware('auth', 'verified', 'activity', 'prevent-back-history')->group(function () {
    
    Route::post('/report/group', 'submitReport')->name('report.group');
    Route::post('/group-categories-create-from-select2', 'createCategoryFromSelect2');

    Route::get('group-categories-autocomplete-ajax', 'dataAjax');
    Route::post('/ajax/store/group/categories', 'storecategories')->name('ajax.store.group.categories');
    Route::get('/ajax/parent/group/catgories', 'jsonGetParentCategories')->name('page.json.parent.group.catgories');
    Route::get('/ajax/parent/group/catgories', 'jsonGetParentCategories')->name('page.json.parent.group.catgories');
    Route::get('/ajax/group/catgories', 'jsonGetCategories')->name('page.json.group.catgories');

    Route::get('/groups/create', 'create')->name('groups.create');
    Route::get('/groups/edit/{id}', 'edit')->name('groups.edit');
    Route::POST('/group/store', 'store')->name('group.store');
    Route::get('user/group', 'userpgroup')->name('usergroup');
    Route::post('/update/group/{id}', 'update')->name('group.update');
    Route::post('/update/coverphoto/group/{id}', 'updatecoverphoto')->name('group.coverphoto');
    Route::get('/group/peopel/info/{id}', 'peopelinfo')->name('group.people.info');
    Route::get('group/view/details/{id}', 'single_group')->name('single.group.details');
    Route::get('group/photo/view/{id}', 'group_photos')->name('single.group.photos');
    Route::get('all/peopel/group/view/{id}', 'all_people_group')->name('all.people.group.view');
    Route::get('/group/event/view/{id}', 'group_event')->name('group.event.view');
    Route::get('group/join/{id}', 'join')->name('group.join');
    Route::get('group/rjoin/{id}', 'rjoin')->name('group.rjoin');
    Route::get('group/search/view', 'search_group')->name('search.group');
    Route::get('group/all/view', 'group_all_view')->name('all.group.view');
    Route::get('group/user/create', 'group_user_create')->name('group.user.created');
    Route::get('group/user/joined', 'group_user_joined')->name('group.user.joined');
    Route::post('album/add/image', 'add_album_image')->name('add.image.album');
    Route::post('group/invites/sent', 'sent_invition')->name('group.invition');
    Route::get('/search_friends_for_inviting', 'search_friends_for_inviting')->name('search_friends_for_inviting');
    Route::get('/load_groups_by_scrolling', 'load_groups_by_scrolling')->name('load_groups_by_scrolling');
});


//  video 
Route::controller(VideoController::class)->middleware('auth', 'verified', 'activity', 'prevent-back-history')->group(function () {
    Route::get('/videos', 'videos')->name('videos');
    Route::POST('/videos/sorts/store', 'store')->name('videos.store');
    Route::get('/video/details/info/{id}', 'videoinfo')->name('video.detail.info');
    Route::get('/shorts', 'shorts')->name('shorts');
    Route::get('save/video/short/{id}', 'save_for_later')->name('save.video.later');
    Route::get('/load_videos_by_scrolling', 'load_videos_by_scrolling')->name('load_videos_by_scrolling');
    Route::get('/load_shorts_by_scrolling', 'load_shorts_by_scrolling')->name('load_shorts_by_scrolling');

    Route::get('save/video/short/{id}', 'save_for_later')->name('save.video.later');
    Route::get('unsave/video/short/{id}', 'unsave_for_later')->name('unsave.video.later');

    Route::get('saved/video/view', 'save_all')->name('save.all.view');

    Route::get('video/delete', 'video_delete')->name('video.delete');
});

//  video 
Route::controller(ChatController::class)->middleware('auth', 'verified', 'activity', 'prevent-back-history')->group(function () {
    Route::get('/chat/inbox/{reciver}/{product?}/', 'chat')->name('chat');
    Route::POST('/chat/save', 'chat_save')->name('chat.save');
    Route::get('chat/own/remove/{id}', 'remove_chat')->name('remove.chat');
    Route::POST('/my_message_react', 'react_chat')->name('react.chat');
    Route::get('/chat/profile/search/', 'search_chat')->name('search.chat');

    Route::get('/chat/inbox/load/data/ajax/', 'chat_load')->name('chat.load');
    Route::get('/chat/inbox/read/message/ajax/', 'chat_read_option')->name('chat.read');
    
    // Chat with us functionality
    Route::post('/chat/with-us', 'chatWithUs')->name('chat.with.us');
});

//  follow 
Route::controller(FollowController::class)->middleware('auth', 'verified', 'activity', 'prevent-back-history')->group(function () {
    Route::get('user/account/follow/{id}', 'follow')->name('user.follow');
    Route::get('user/account/unfollow/{id}', 'unfollow')->name('user.unfollow');
});


//  whole website ssearch  
// ✅ Public search route - accessible without login (used by dropdown menus)
Route::controller(SearchController::class)->middleware('activity', 'prevent-back-history')->group(function () {
    Route::get('/search', 'search')->name('search');
  
  });

// Auth-protected search sub-routes (social features, require login)
Route::controller(SearchController::class)->middleware('auth', 'verified', 'activity', 'prevent-back-history')->group(function () {
    Route::get('/search/people/', 'search_people')->name('search.people');
    Route::get('/search/post/', 'search_post')->name('search.post');
    Route::get('/search/video/', 'search_video')->name('search.video');
    Route::get('/search/product/', 'search_product')->name('search.product');
    Route::get('/search/page/', 'search_page')->name('search.page');
    Route::get('/search/group/', 'search_group')->name('search.group.specific');
    Route::get('/search/event/', 'search_event')->name('search.event');
    
});
Route::get('/search-globally', [SearchController::class,'search_globally'])->name('search.globally');

Route::controller(CustomUserController::class)->middleware('auth', 'verified', 'activity', 'prevent-back-history')->group(function () {
    Route::get('user/view-profile/{id}', 'view_profile_data')->name('user.profile.view');
    Route::get('/user/load_post_by_scrolling', 'load_post_by_scrolling')->name('user.load_post_by_scrolling');
    Route::get('user/password/change', 'changepass')->name('user.password.change');
    Route::POST('user/password/update', 'updatepass')->name('user.password.update');
    Route::get('user/friend/{id}', 'friend')->name('user.friend');
    Route::get('user/unfriend/{id}', 'unfriend')->name('user.unfriend');

    Route::get('/user/friends/{id}', 'friends')->name('user.friends');
    Route::get('/user/photos/{id}', 'photos')->name('user.photos');
    Route::get('/user/videos/{id}', 'videos')->name('user.videos');

    Route::get('video/delete/{id}', 'delete_mediafile')->name('delete.mediafile');
    Route::get('download/media/file/{id}', 'download_mediafile')->name('download.mediafile');
    Route::get('download/media/file/image/{id}', 'download_mediafile_image')->name('download.mediafile.image');
});



//  setting frontend
Route::controller(SettingController::class)->group(function () {
    Route::get('about/page/view/', 'about_view')->name('about.view')->middleware('auth', 'verified', 'prevent-back-history');
    Route::get('policy/page/view/', 'policy_view')->name('policy.view')->middleware('auth', 'verified', 'prevent-back-history');
    Route::get('contact/us/view/', 'contact_view')->name('contact.view');
    Route::POST('contact/us/send/', 'contact_send')->name('contact.send');

    Route::get('term/condition/view/', 'term_view')->name('term.view');



    Route::get('admin/about-page/data/', 'update_about_page_data')->name('admin.about.page.data.view')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/about-page/data/update/{id}', 'update_about_page_data_update')->name('admin.about.page.data.update')->middleware('auth', 'verified', 'admin', 'prevent-back-history');

    Route::POST('admin/privacy/page/data/update/{id}', 'update_privacy_page_data_update')->name('admin.privacy.page.data.update')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    
    Route::POST('admin/term/page/data/update/{id}', 'update_term_page_data_update')->name('admin.term.page.data.update')->middleware('auth', 'verified', 'admin', 'prevent-back-history');

    Route::get('admin/reported/post/', 'reported_post_to_admin')->name('admin.reported.post.view')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/reported/post/delete/{id}', 'reported_post_remove_by_admin')->name('admin.reported.post.delete.by.admin')->middleware('auth', 'verified', 'admin', 'prevent-back-history');

    Route::get('admin/live-video-setting/view', 'live_video_edit_form')->name('admin.live-video.view')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::post('admin/live-video-setting/update', 'live_video_update')->name('admin.live-video.update')->middleware('auth', 'verified', 'admin', 'prevent-back-history');


    Route::get('admin/smtp-setting/view/', 'smtp_settings_view')->name('admin.smtp.settings.view')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/smtp-setting/save/{id}', 'smtp_settings_save')->name('admin.smtp.settings.view.save')->middleware('auth', 'verified', 'admin', 'prevent-back-history');

    // system settings 
    Route::get('admin/system-setting/view/', 'system_settings_view')->name('admin.system.settings.view')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/system-setting/save/', 'system_settings_save')->name('admin.system.settings.view.save')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/system-setting/logo/save/', 'system_settings_logo_save')->name('admin.system.settings.logo.view.save')->middleware('auth', 'verified', 'admin', 'prevent-back-history');


    Route::get('admin/settings/amazon_s3', 'amazon_s3')->name('admin.settings.amazon_s3');
    Route::post('admin/settings/amazon_s3/update', 'amazon_s3_update')->name('admin.settings.amazon_s3.update');
});

Route::get('run_queue', function () {
    Artisan::call('queue:work', ['--timeout'=>1200 ,'--tries'=>5, '--stop-when-empty' => true]);
    //dd("Queue is running now");

})->name('admin.page.queue');

Route::prefix('admin')->middleware('auth', 'verified', 'admin', 'prevent-back-history')->group(function () {
    Route::resource('enquiry-lead-stages', EnquiryLeadStageController::class)
    ->names([
        'index' => 'enquiry-lead-stages.index',
        'create' => 'enquiry-lead-stages.create',
        'store' => 'enquiry-lead-stages.store',
        'edit' => 'enquiry-lead-stages.edit',
        'update' => 'enquiry-lead-stages.update',
        'destroy' => 'enquiry-lead-stages.destroy',
        'show' => 'enquiry.lead-stages.show'
    ]);

});


Route::prefix('chat')->middleware('auth')->group(function () {
    Route::get('/page/{page}', [PageChatController::class, 'index'])->name('chat.page');
    Route::get('/conversation/{conversation}', [PageChatController::class, 'showMessages'])->name('chat.messages');
    Route::post('/send', [PageChatController::class, 'sendMessage'])->name('chat.send');
   Route::get('/fetch/{id}', [PageChatController::class, 'fetchpagechatMessages'])->name('chat.fetch');

});
Route::prefix('chat/marketplace')->middleware('auth')->group(function () {
    Route::get('/{marketplace}', [MarketplaceChatController::class, 'index'])->name('chat.marketplace');
    Route::post('/send', [MarketplaceChatController::class, 'sendMessage'])->name('chat.marketplace.send');
    Route::get('/fetch/{id}', [MarketplaceChatController::class, 'fetchMarketplaceMessages'])->name('chat.marketplace.fetch');
});



//  admin all crud 
Route::controller(AdminCrudController::class)->middleware('auth', 'verified', 'admin', 'prevent-back-history')->group(function () {


     Route::get('admin/chat/conversations',  'page_enquiry_index')->name('admin.all.conversations.index');
    Route::get('admin/chat-conversations/{id}',  'page_enquiry_show')->name('admin.all.conversations.show');
    Route::post('admin/chat/conversations/{id}/message',  'page_enquiry_sendMessage')->name('admin.all.conversations.message');
    Route::get('admin/chat/{id}',  'fetchpagechatMessages')->name('admin.page.chat.fetch');

      Route::get('admin/market-chat/conversations',  'market_enquiry_index')->name('admin.all.market.conversations.index');
    Route::get('admin/market-chat-conversations/{id}',  'market_enquiry_show')->name('admin.all.market.conversations.show');
    Route::post('admin/market-chat/conversations/{id}/message',  'market_enquiry_sendMessage')->name('admin.all.market.conversations.message');
    Route::get('admin/market-chat/{id}',  'fetchMarketChatMessages')->name('admin.all.market.chat.fetch');


    Route::get('admin/manage/approval','manage_approval')->name('manage.approval');
    Route::post('/admin/manage/toggle/{id}','toggleServiceStatus')->name('manage.toggle');


    Route::get('admin/listings/incomplete','showallIncompleteListings')->name('admin.listings.incomplete');
    // Route for Admin and User to access the lead purchase report
    Route::get('admin/lead-purchase-report',  'leadPurchaseReport')->name('admin.lead.purchase.report');


    Route::get('admin/wallet-report', 'wallet_report')->name('admin.wallet.report');
    Route::get('admin/wallet-transactions/{user_id}', 'wallet_transactions')->name('admin.wallet.transactions');
    
    

    Route::get('admin/dashboard/', 'admin_dashboard')->name('admin.dashboard')->middleware('auth', 'verified', 'admin', 'prevent-back-history');


    Route::get('admin/users/', 'users')->name('admin.users')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::post('/admin/users/data', 'serverSideUsersData')->name('admin.server_side_users_data');

    Route::get('admin/user/add', 'user_add')->name('admin.user.add')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::post('admin/user/store', 'user_store')->name('admin.user.store')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/user-edit/{id}', 'user_edit')->name('admin.user.edit')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::post('admin/user/update/{id}', 'user_update')->name('admin.user.update')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/user/delete/{id}', 'user_delete')->name('admin.user.delete')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/user-status/{id}', 'user_status')->name('admin.user.status')->middleware('auth', 'verified', 'admin', 'prevent-back-history');

    Route::any('admin/server_side_users_data', 'server_side_users_data')->name('admin.server_side_users_data.legacy');


    // Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth']], function() {
    //     Route::get('/users', 'AdminController@user_index')->name('admin.users.index');
    //     Route::get('/users/create', 'AdminController@user_create')->name('admin.users.create');
    //     Route::post('/users', 'AdminController@user_store')->name('admin.users.store');
    //     Route::get('/users/{user}/edit', 'AdminController@user_edit')->name('admin.users.edit');
    //     Route::put('/users/{user}', 'AdminController@user_update')->name('admin.users.update');
    //     Route::delete('/users/{user}', 'AdminController@user_destroy')->name('admin.users.destroy');
    // });


    Route::get('admin/videos', 'videos')->name('admin.videos');
    Route::post('/admin/video/approve','approve')->name('admin.video.approve');
    Route::post('/admin/video/approve-multiple', 'approveMultiple')->name('admin.video.approve.multiple');
Route::post('/admin/video/approve-all', 'approveAll')->name('admin.video.approve.all');


Route::get('/admin/group-categories',  'group_category_index')->name('admin.group.categories');
Route::get('/admin/group-categories/create', 'group_category_create')->name('admin.create.group.category');
Route::post('/admin/group-categories/store',  'group_category_store')->name('admin.store.group.category');
Route::get('/admin/group-categories/edit/{id}', 'group_category_edit')->name('admin.edit.group.category');
Route::put('/admin/group-categories/update/{id}',  'group_category_update')->name('admin.update.group.category');
Route::delete('/admin/group-categories/delete/{id}', 'group_category_destroy')->name('admin.delete.group.category');



    Route::get('admin/group', 'groups')->name('admin.group')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/group/create', 'group_create')->name('admin.group.create')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/group-edit/{id}', 'group_edit')->name('admin.group.edit')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::post('admin/group/created/', 'group_created')->name('admin.group.created')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::post('admin/group/updated/{id}', 'group_updated')->name('admin.group.updated')->middleware('auth', 'verified', 'admin', 'prevent-back-history');



    Route::get('admin/change/password', 'admin_change_password')->name('admin.change.password')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/profile/', 'admin_profile')->name('admin.profile')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/profile/update/', 'admin_profile_update')->name('admin.profile.update')->middleware('auth', 'verified', 'admin', 'prevent-back-history');

Route::get('admin/claims/search', 'searchclaimListings')->name('admin.claims.search');

    Route::get('/admin/claim-listings', 'claimListings')->name('admin.claim_listings');
    Route::post('/admin/claim/update-status',  'updateClaimStatus')->name('admin.claim.update');
    Route::get('/admin/reports',  'reportsList')->name('admin.reports.list');


    Route::get('/admin/tickets',  'ticket_list')->name('admin.tickets.list');
    Route::get('/admin/tickets/{ticket}',  'ticket_show')->name('admin.tickets.show');
    Route::put('/admin/tickets/{ticket}',  'ticket_update')->name('admin.tickets.update');

    Route::get('admin/pages/search','searchPages')->name('admin.pages.search');

    Route::get('admin/pending-pages/search','searchPendingages')->name('admin.pages.pending.search');
    Route::post('/admin/page/bulk-action','bulkAction')->name('admin.page.bulk_action');


    Route::post('/admin/page/toggle-verified', 'toggleVerified')->name('admin.page.toggleVerified');

    Route::get('admin/page', 'pages')->name('admin.page')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/pending-page', 'pendingpages')->name('admin.page.pending')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/page/create', 'page_create')->name('admin.page.create')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/page/sync', 'page_sync')->name('admin.page.sync')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/page-edit/{id}', 'page_edit')->name('admin.page.edit')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::post('admin/page/created/', 'page_created')->name('admin.page.created')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::post('admin/page/updated/{id}', 'page_updated')->name('admin.page.updated')->middleware('auth', 'verified', 'admin', 'prevent-back-history');


    Route::get('admin/blog', 'blogs')->name('admin.blog')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/blog/create', 'blog_create')->name('admin.blog.create')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/blog-edit/{id}', 'blog_edit')->name('admin.blog.edit')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/blog-listing-search', 'blogListingSearch')->name('admin.blog.listing.search')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::post('admin/blog/created/', 'blog_created')->name('admin.blog.created')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::post('admin/blog/updated/{id}', 'blog_updated')->name('admin.blog.updated')->middleware('auth', 'verified', 'admin', 'prevent-back-history');



    Route::get('admin/page/search-category','jsonSearch')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('/admin/category/check-exists',  'checkIfCategoryExists');


    Route::get('admin/page/category-view/', 'view_category')->name('admin.view.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/page/category-create/', 'create_category')->name('admin.create.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/page/category-save/', 'save_category')->name('admin.save.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/page/category/edit/{id}', 'edit_category')->name('admin.edit.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/page/category/update/{id}', 'update_category')->name('admin.update.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/page/category/delete/{id}', 'delete_category')->name('admin.delete.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');

    //user suggestion
    Route::get('admin/user/page/category-view/', 'view_user_suggest_category')->name('admin.user.page.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/user/product/category-view/', 'view_user_suggest_product_category')->name('admin.user.product.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/user/event/category-view/', 'view_user_suggest_event_category')->name('admin.user.event.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/user/city-view/', 'view_user_suggest_city')->name('admin.user.city')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/user/city/edit/{id}', 'edit_city')->name('admin.user.city.edit')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/user/city/update/{id}', 'update_city')->name('admin.update.user.city')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/user/city/delete/{id}', 'delete_city')->name('admin.delete.user.city')->middleware('auth', 'verified', 'admin', 'prevent-back-history');

    Route::get('admin/user/area-view/', 'view_user_suggest_area')->name('admin.user.area')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/user/area/edit/{id}', 'edit_area')->name('admin.user.area.edit')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/user/area/update/{id}', 'update_area')->name('admin.update.user.area')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/user/area/delete/{id}', 'delete_area')->name('admin.delete.user.area')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    
    
    //event
    Route::get('admin/event-view/', 'view_all_event')->name('admin.view.event')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/event/upcoming/', 'view_upcoming_event')->name('admin.upcoming.event')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/event/previous/', 'view_previous_event')->name('admin.previous.event')->middleware('auth', 'verified', 'admin', 'prevent-back-history');

    Route::get('admin/event/create/', 'create_event')->name('admin.event.create')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/event/store/', 'store')->name('admin.event.store')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/event/edit/{id}', 'edit_event')->name('admin.edit.event')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/event/update/{id}', 'update_event')->name('admin.update.event')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    

    Route::get('admin/event/category-view/', 'view_event_category')->name('admin.view.event.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/event/category-create/', 'create_event_category')->name('admin.create.event.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/event/category-save/', 'save_event_category')->name('admin.save.event.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/event/category/edit/{id}', 'edit_event_category')->name('admin.edit.event.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/event/category/update/{id}', 'update_event_category')->name('admin.update.event.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/event/category/delete/{id}', 'delete_event_category')->name('admin.delete.event.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');

    //product
    Route::get('admin/products/ajax', 'productAjax')->name('admin.products.ajax');

    Route::get('admin/product', 'product')->name('admin.product')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/product/create', 'product_create')->name('admin.product.create')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/product-edit/{id}', 'product_edit')->name('admin.product.edit')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/product-listing-search', 'productListingSearch')->name('admin.product.listing.search')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::post('admin/product/created/', 'product_created')->name('admin.product.created')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::post('admin/product/updated/{id}', 'product_updated')->name('admin.product.updated')->middleware('auth', 'verified', 'admin', 'prevent-back-history');


     Route::get('admin/product-categories', 'viewProductCategories')->name('admin.view.product.category');
    Route::get('admin/product-categories/ajax', 'ajaxProductCategories')->name('admin.view.product.category.ajax');
   
    // product category 
    Route::get('admin/product/category-view/', 'view_product_category')->name('admin.view.product.category.legacy')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/product/category/create/', 'create_product_category')->name('admin.create.product.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/product/category/save/', 'save_product_category')->name('admin.save.product.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/product/category/edit/{id}', 'edit_product_category')->name('admin.edit.product.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/product/category/update/{id}', 'update_product_category')->name('admin.update.product.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/product/category/delete/{id}', 'delete_product_category')->name('admin.delete.product.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/product/enquiry-view/', 'view_product_enquiry')->name('admin.view.product.enquiry')->middleware('auth', 'verified', 'admin', 'prevent-back-history');

    // product brand 
    Route::get('admin/product/brand-view/', 'view_brand_category')->name('admin.view.product.brand')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/product/brand-create/', 'create_brand_category')->name('admin.create.product.brand')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/product/brand/save/', 'save_brand_category')->name('admin.save.product.brand')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/product/brand/edit/{id}', 'edit_brand_category')->name('admin.edit.product.brand')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/product/brand/update/{id}', 'update_brand_category')->name('admin.update.product.brand')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/product/brand/delete/{id}', 'delete_brand_category')->name('admin.delete.product.brand')->middleware('auth', 'verified', 'admin', 'prevent-back-history');


    // blog  category  
    Route::get('admin/blog/category-view/', 'view_blog_category')->name('admin.view.blog.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::post('/admin/blog-categories/ajax','blogCategoryAjax')->name('admin.blog.categories.ajax');


    Route::get('admin/blog/category-create/', 'create_blog_category')->name('admin.create.blog.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/blog/category/save/', 'save_blog_category')->name('admin.save.blog.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/blog/category/edit/{id}', 'edit_blog_category')->name('admin.edit.blog.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/blog/category/update/{id}', 'update_blog_category')->name('admin.update.blog.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/blog/category/delete/{id}', 'delete_blog_category')->name('admin.delete.blog.category')->middleware('auth', 'verified', 'admin', 'prevent-back-history');


    Route::get('admin/settings/payment', 'payment_settings')->name('admin.settings.payment')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('/admin/payment-gateway/create', 'create_payment_gateway')->name('admin.payment_gateway.create')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::post('/admin/payment-gateway/store',  'store_payment_gateway')->name('admin.payment_gateway.store')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/payment_gateway/edit/{id}', 'payment_gateway_edit')->name('admin.payment_gateway.edit')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::post('admin/payment_gateway/update/{id}', 'payment_gateway_update')->name('admin.payment_gateway.update')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/payment_gateway/status/{id}', 'payment_gateway_status')->name('admin.payment_gateway.status')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/payment_gateway/environment/{id}', 'payment_gateway_environment')->name('admin.payment_gateway.environment')->middleware('auth', 'verified', 'admin', 'prevent-back-history');


    //System About routes
    Route::get('admin/settings/about', 'about')->name('admin.about');
    Route::any('admin/save_valid_purchase_code/{action_type?}', 'save_valid_purchase_code')->name('admin.save_valid_purchase_code');

    //spam words
    Route::get('/admin/spam-words', [SpamWordController::class, 'index'])->name('spam.index');
    Route::post('/admin/spam-words', [SpamWordController::class, 'store'])->name('spam.store');
    Route::post('admin/spam/update', [SpamWordController::class, 'update'])->name('spam.update');
    Route::delete('/admin/spam-words/{id}', [SpamWordController::class, 'destroy'])->name('spam.destroy');

    // CSV Upload & Download Routes
    Route::post('admin/spam-words/import', [SpamWordController::class, 'import'])->name('spam.import');
    Route::get('admin/spam-words/download-template', [SpamWordController::class, 'downloadTemplate'])->name('spam.downloadTemplate');



  
    Route::get('/states',  'state_index')->name('admin.state');
    Route::get('/states/create',  'state_create')->name('admin.states.create');
    Route::post('/states',  'state_store')->name('admin.states.store');
    Route::get('/states/edit/{id}',  'state_edit')->name('admin.states.edit');
    Route::put('/states/update/{id}',  'state_update')->name('admin.states.update');
    Route::delete('/states/{id}',  'state_destroy')->name('admin.states.destroy');
      

    Route::get('/cities',  'city_index')->name('admin.cities'); // View all cities
    Route::get('/cities/create',  'city_create')->name('admin.cities.create'); // Show add city form
    Route::post('/cities',  'city_store')->name('admin.cities.store'); // Store new city
    Route::get('/cities/{city}',  'city_show')->name('admin.cities.show'); // Show single city details
    Route::get('/cities/{city}/edit',  'city_edit')->name('admin.cities.edit'); // Show edit form
    Route::put('/cities/{city}',  'city_update')->name('admin.cities.update'); // Update city
    Route::delete('/cities/{city}',  'city_destroy')->name('admin.cities.destroy'); // Delete city

    // Country Management Routes
    Route::get('/countries',  'country_index')->name('admin.countries'); // View all countries
    Route::get('/countries/create',  'country_create')->name('admin.countries.create'); // Show add country form
    Route::post('/countries',  'country_store')->name('admin.countries.store'); // Store new country
    Route::get('/countries/{country}',  'country_show')->name('admin.countries.show'); // Show single country details
    Route::get('/countries/{country}/edit',  'country_edit')->name('admin.countries.edit'); // Show edit form
    Route::put('/countries/{country}',  'country_update')->name('admin.countries.update'); // Update country
    Route::delete('/countries/{country}',  'country_destroy')->name('admin.countries.destroy'); // Delete country

    Route::get('/areas', 'area_index')->name('admin.areas'); // List Areas
    Route::get('/areas/create', 'area_create')->name('admin.areas.create'); // Show Add Area Form
    Route::post('/areas', 'area_store')->name('admin.areas.store'); // Store New Area
    Route::get('/areas/{area}/edit', 'area_edit')->name('admin.areas.edit'); // Show Edit Form
    Route::put('/areas/{area}', 'area_update')->name('admin.areas.update'); // Update Area
    Route::delete('/areas/{area}', 'area_destroy')->name('admin.areas.destroy'); // Delete Area
    
    // AJAX routes for area management
    Route::get('/ajax/cities-by-state', 'getCitiesByState')->name('admin.ajax.cities.by.state');
    Route::get('/ajax/countries', 'getCountries')->name('admin.ajax.countries');
});



//  setting frontend
Route::controller(SponsorController::class)->group(function () {
    // blog  category  
    Route::get('admin/sponsor/view/', 'view_sponsor')->name('admin.view.sponsor')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/sponsor/create/', 'create_sponsor')->name('admin.create.sponsor')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/sponsor/save/', 'save_sponsor')->name('admin.save.sponsor')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/sponsor/edit/{id}', 'edit_sponsor')->name('admin.edit.sponsor')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::POST('admin/sponsor/update/{id}', 'update_sponsor')->name('admin.update.sponsor')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
    Route::get('admin/sponsor/delete/{id}', 'delete_sponsor')->name('admin.delete.sponsor')->middleware('auth', 'verified', 'admin', 'prevent-back-history');
});



Route::controller(NotificationController::class)->middleware('auth', 'verified', 'activity')->group(function () {
    Route::get('/all/notification', 'notifications')->name('notifications');
    Route::get('/accept/friend/request/notification/{id}', 'accept_friend_notification')->name('accept.friend.request.from.notification');
    Route::get('/decline/friend/request/notification/{id}', 'decline_friend_notification')->name('decline.friend.request.from.notification');

    Route::get('/accept/group/request/notification/{id}/{group_id}', 'accept_group_notification')->name('accept.group.request.from.notification');
    Route::get('/decline/group/request/notification/{id}/{group_id}', 'decline_group_notification')->name('decline.group.request.from.notification');

    Route::get('/accept/event/request/notification/{id}/{event_id}', 'accept_event_notification')->name('accept.event.request.from.notification');
    Route::get('/decline/event/request/notification/{id}/{event_id}', 'decline_event_notification')->name('decline.event.request.from.notification');

    Route::get('/mark/as/read/notification/{id}', 'mark_as_read')->name('mark.as.read.notification');
});


Route::controller(LanguageController::class)->middleware('auth', 'verified', 'activity', 'admin')->group(function () {
    Route::get('admin/all-language/settings', 'language')->name('admin.language.settings');
    Route::POST('admin/create/language/', 'language_add')->name('admin.language.create');
    Route::POST('admin/languages/update/{language}', 'language_update')->name('admin.languages.update');
    Route::get('admin/languages/edit/phrase/{language}', 'edit_phrase')->name('admin.languages.edit.phrase');
    Route::post('admin/languages/update/phrase/{id}', 'update_phrase')->name('admin.languages.update.phrase');
});

Route::controller(PaymentHistory::class)->middleware('auth', 'verified', 'activity', 'admin', 'prevent-back-history')->group(function () {
    Route::get('admin/payment-histories', 'index')->name('admin.payment_histories');
});

Route::get('ajax/eventareas/{city_id}', [EventController::class, 'jsonGetAreasByCityforitem'] )->name('json.eventareas');
Route::group(['prefix' => 'event'], function() {

    Route::get('/all', [EventController::class, 'allevents'])->name('event');

    // Route for category in city and area page (specific pattern)
    Route::get('{city_slug}/{category_slug}-in-{area_slug}', [EventController::class, 'eventcategoryByCityArea'])
    ->name('event.category.city.area')
    ->where('city_slug', '[A-Za-z0-9-%&]+')
    ->where('category_slug', '[A-Za-z0-9-%&]+')
    ->where('area_slug', '[A-Za-z0-9-%&]+');
    // Route for category in city page (specific pattern)
    Route::get('{category_slug}-in-{city_slug}', [EventController::class, 'eventcategoryByCity'])
    ->name('event.category.city')
    ->where('category_slug', '[A-Za-z0-9-%&]+')
    ->where('city_slug', '[A-Za-z0-9-%&]+');

    Route::get('category/{category_slug}', [EventController::class, 'eventcategory'])->name('event.category');
    Route::get('{city_slug}/{area_slug}', [EventController::class, 'eventarea'])->name('event.city.area');

    Route::get('/{city_slug}', [EventController::class, 'eventcity'])->name('event.city');

    Route::get('{city_slug}/{area_slug}/{category_slug}/{event_slug}',[EventController::class, 'single_event'] )->name('single.event');
});

Route::get('ajax/groupareas/{city_id}', [GroupController::class, 'jsonGetAreasByCityforitem'] )->name('json.groupareas');

Route::group(['prefix' => 'group'], function() {
    Route::get('/category/{category_slug}',[GroupController::class, 'category_group'])->name('category.group');

    Route::get('{city_slug}/{category_slug}-in-{area_slug}', [GroupController::class, 'categorycityarea'])->name('group.category.city.area')
    ->where('city_slug', '[A-Za-z0-9-%&]+')
    ->where('category_slug', '[A-Za-z0-9-%&]+')
    ->where('area_slug', '[A-Za-z0-9-%&]+');

    Route::get('/{category_slug}-in-{city_slug}', [GroupController::class, 'groupcategorycity'])->name('group.category.city')
    ->where('category_slug', '[A-Za-z0-9-%&]+')
    ->where('city_slug', '[A-Za-z0-9-%&]+');;

    Route::get('{city_slug}/{area_slug}', [GroupController::class, 'group_area'])->name('group.city.area');

    
    Route::get('/{city_slug}', [GroupController::class, 'group_city'])->name('group.city');
    // Route::get('{city_slug?}/{area_slug?}/{category_slug}/{group_slug}', [GroupController::class, 'single_group'])->name('single.group');
    


    
    });
    Route::get('/groups',  [GroupController::class,'groups'])->name('groups');
    Route::get('group-view/{category_slug}/{group_slug}/{city_slug?}/{area_slug?}', [GroupController::class, 'single_group'])->name('single.group');
//      Route::get(
//     'group/category/{category_slug}/{group_slug}',
//     [GroupController::class, 'categoryGroupSingle']
// )->name('group.category.single');
    Route::get('/group/category/{category_slug}', [GroupController::class, 'category_group'])->name('category.group');
    Route::post('/discussion/save', [GroupController::class, 'store']);

   

Route::get('ajax/blogareas/{city_id}')->name('json.blog.areas');

// Direct route for blog listing and search
Route::get('/blogs', [BlogController::class,'blogs'])->middleware('auth', 'verified', 'activity', 'prevent-back-history')->name('blogs.direct');

Route::group(['prefix' => 'blog'], function() {
    Route::get('/all', [BlogController::class,'blogs'])->name('blogs');
    Route::get('/category/{category_slug}',[BlogController::class, 'category_blog'])->name('category.blog');

    Route::get('{city_slug}/{category_slug}-in-{area_slug}', [BlogController::class, 'blogcategorycityarea'])->name('blog.category.city.area')
    ->where('city_slug', '[A-Za-z0-9-%&]+')
    ->where('category_slug', '[A-Za-z0-9-%&]+')
    ->where('area_slug', '[A-Za-z0-9-%&]+');

    Route::get('/{category_slug}-in-{city_slug}', [BlogController::class, 'blogcategorycity'])->name('blog.category.city')
    ->where('category_slug', '[A-Za-z0-9-%&]+')
    ->where('city_slug', '[A-Za-z0-9-%&]+');
    Route::get('{city_slug}/{area_slug}', [BlogController::class, 'area'])->name('blog.city.area');
    Route::get('/{city_slug}', [BlogController::class, 'city'])->name('blog.city');
//Route::get('{city_slug}/{area_slug}/{category_slug}/{blog_slug}',[BlogController::class, 'single_blog'])->name('single.blog');
});
Route::get('blog-view/{category_slug}/{blog_slug}/{city_slug?}/{area_slug?}', [BlogController::class, 'single_blog'])->name('single.blog');

Route::group(['prefix' => 'deals'], function() {

   Route::get('/', [MarketplaceController::class, 'allproducts'])->name('allproducts');
   Route::get('/product/filter/{category?}/{max?}/{min?}/{brand?}/{location?}', [MarketplaceController::class, 'filter'])->name('filter.product');


    Route::get('{city_slug}/{category_slug}-in-{area_slug}', [MarketplaceController::class, 'productcategorycityarea'])->name('product.category.city.area')
    ->where('city_slug', '[A-Za-z0-9-%&]+')
    ->where('category_slug', '[A-Za-z0-9-%&]+')
    ->where('area_slug', '[A-Za-z0-9-%&]+');

     Route::get('/{category_slug}-in-{city_slug}', [MarketplaceController::class, 'productcategorycity'])->name('product.category.city')
    ->where('category_slug', '[A-Za-z0-9-%&]+')
    ->where('city_slug', '[A-Za-z0-9-%&]+');

    Route::get('category/{category_slug}', [MarketplaceController::class, 'productcategory'])->name('product.category');

    Route::get('{city_slug}/{area_slug}', [MarketplaceController::class, 'productarea'])->name('product.city.area');
   
   
   

     Route::get('{city_slug}', [MarketplaceController::class, 'productcity'])->name('product.city')->where('city_slug', '[A-Za-z0-9-%&]+');

   

    Route::get('{city_slug}/{area_slug}/{category_slug}/{item_slug}/{product_category_slug}/{product_slug}',[MarketplaceController::class, 'single_product'] )->name('single.product')
    ->where('city_slug', '[A-Za-z0-9-%&]+')
    ->where('category_slug', '[A-Za-z0-9-%&]+')
    ->where('item_slug', '[A-Za-z0-9-%&]+')
    ->where('product_category_slug', '[A-Za-z0-9-%&]+')
    ->where('product_slug', '[A-Za-z0-9-%&]+');

   
});



// Route for category in city and area page (specific pattern)
Route::get('{city_slug}/{category_slug}-in-{area_slug}', [PageController::class, 'categoryByCityArea'])
    ->name('page.category.city.area')
    ->where('city_slug', '[A-Za-z0-9-%&]+')
    ->where('category_slug', '[A-Za-z0-9-%&]+')
    ->where('area_slug', '[A-Za-z0-9-%&]+');

// Route for category in city page (specific pattern)

// SEO Search Routes
Route::get('{city_slug}/{category_slug}', [\App\Http\Controllers\Report\SearchController::class, 'search'])->name('search.seo.wildcard');
Route::get('search/{category_slug}', [\App\Http\Controllers\Report\SearchController::class, 'search'])->name('search.category.only');
Route::get('{category_slug}-in-{city_slug}', [PageController::class, 'categoryByCity'])
    ->name('page.category.city')
    ->where('category_slug', '[A-Za-z0-9-%&]+')
    ->where('city_slug', '[A-Za-z0-9-%&]+');
// Route for category page (most general pattern)
Route::get('category/{category_slug}', [PageController::class, 'category'])->name('page.category');
// Route for city-area page (more general pattern)
Route::get('{city_slug}/{area_slug}', [PageController::class, 'area'])->name('page.city.area');

Route::get('/pages',[PageController::class, 'pages'])->name('pages');

Route::get('/{city_slug}', [PageController::class, 'city'])->name('page.city');

Route::get('ajax/itemareas/{city_id}', [PageController::class, 'jsonGetAreasByCityforitem'])->name('json.itemareas');
Route::get('ajax/cities', [PageController::class, 'jsonGetAllCities'])->name('json.cities');
Route::get('ajax/subcategories/{category_id}', [PageController::class, 'jsonGetSubcategoriesByCategory'])->name('json.subcategories');



Route::get('{city_slug}/{area_slug}/{category_slug}/{item_slug}',[PageController::class,'single_page'] )->name('single.page');


Route::get('{city_slug}/{area_slug}/{category_slug}/{item_slug}/blogs', [PageController::class,'blogs'])->name('pages.blogs');
    Route::get('{city_slug}/{area_slug}/{category_slug}/{item_slug}/events', [PageController::class,'events'])->name('pages.events');
    Route::get('{city_slug}/{area_slug}/{category_slug}/{item_slug}/groups', [PageController::class,'groups'])->name('pages.groups');
    Route::get('{city_slug}/{area_slug}/{category_slug}/{item_slug}/deals', [PageController::class,'products'])->name('pages.products');
    Route::get('{city_slug}/{area_slug}/{category_slug}/{item_slug}/about', [PageController::class,'pageinfo'])->name('pages.info');
 Route::get('{city_slug}/{area_slug}/{category_slug}/{item_slug}/photo', [PageController::class,'page_photos'])->name('single.page.photos');
    Route::get('{city_slug}/{area_slug}/{category_slug}/{item_slug}/videos', [PageController::class,'videos'])->name('page.videos');

Route::get('ajax/productareas/{city_id}',[MarketplaceController::class,'jsonGetAreasByCityforproduct'] )->name('json.productareas');
Route::get('ajax/marketplace/subcategories',[MarketplaceController::class,'jsonGetSubcategoriesByCategory'] )->name('json.get.subcategories.by.category');






Route::get('admin/custom/pages', [CustomPageController::class, 'index'])->name('custom_pages.list');
Route::get('admin/custom-pages/create', [CustomPageController::class, 'create'])->name('custom_pages.create');
Route::post('admin/custom/pages/store', [CustomPageController::class, 'store'])->name('custom_pages.store');

// Edit Page
Route::get('admin/custom/pages/edit/{id}', [CustomPageController::class, 'edit'])->name('custom_pages.edit');
Route::post('admin/custom/pages/update/{id}', [CustomPageController::class, 'update'])->name('custom_pages.update');

// Delete Page
Route::delete('admin/custom/pages/delete/{id}', [CustomPageController::class, 'destroy'])->name('custom_pages.destroy');

// Dynamic page rendering
Route::get('/pages/custom/{slug}', [CustomPageController::class, 'show'])->name('custom_pages.show');
Route::post('/admin/custom_pages/toggle/{id}', [CustomPageController::class, 'toggleStatus'])->name('custom_pages.toggle');



Route::prefix('admin')->name('admin.')->group(function () {
    // Route to search categories (for AJAX autocomplete)
    Route::get('attributes/categories/search', [AttributeController::class, 'searchCategories'])->name('product.categories.search');

    // Route to view all attributes for a selected category
    Route::get('attributes/view', [AttributeController::class, 'index'])->name('view.attributes.index');
    
    // Route to create a new attribute (the create form)
    Route::get('attributes/create', [AttributeController::class, 'create'])->name('attributes.create');
    
    // Route to store a new attribute and its values
    Route::post('attributes/store', [AttributeController::class, 'store'])->name('attributes.store');
    
    // Route to show the form for editing an existing attribute
    Route::get('attributes-edit/{id}', [AttributeController::class, 'edit'])->name('attributes.edit');
    
    // Route to update the attribute details and its values
    Route::post('attributes/{id}/update', [AttributeController::class, 'update'])->name('attributes.update');
    
    // Route to delete an existing attribute
    Route::delete('attributes/{id}', [AttributeController::class, 'destroy'])->name('attributes.destroy');
});


Route::prefix('admin')->name('admin.')->group(function () {
    // Route to view all campaigns
    Route::get('campaigns/view', [CampaignController::class, 'index'])->name('campaigns.index');
    
    // Route to show the form for creating a new campaign
    Route::get('campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    
    // Route to store a new campaign
    Route::post('campaigns/store', [CampaignController::class, 'store'])->name('campaigns.store');
    
    // Route to show a single campaign (for viewing details)
    Route::get('campaigns/{id}', [CampaignController::class, 'show'])->name('campaigns.show');
    
    // Route to show the form for editing a campaign
    Route::get('campaigns/{id}', [CampaignController::class, 'edit'])->name('campaigns.edit');
    
    // Route to update an existing campaign
    Route::put('campaigns/{id}', [CampaignController::class, 'update'])->name('campaigns.update');
    
    // Route to delete an existing campaign
    Route::delete('campaigns/{id}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');

    Route::post('campaigns/{id}/send', [CampaignController::class, 'send'])->name('admin.campaigns.send');

});

Route::prefix('admin')->name('admin.')->group(function () {
    // Route to view all campaigns
    Route::get('campaign_templates/view', [CampaignTemplateController::class, 'index'])->name('campaign_templates.index');
    
    // Route to show the form for creating a new campaign
    Route::get('campaign_templates/create', [CampaignTemplateController::class, 'create'])->name('campaign_templates.create');
    
    // Route to store a new campaign
    Route::post('campaign_templates/store', [CampaignTemplateController::class, 'store'])->name('campaign_templates.store');
    
   
    // Route to show the form for editing a campaign
    Route::get('campaign_templates/{id}', [CampaignTemplateController::class, 'edit'])->name('campaign_templates.edit');
    
    // Route to update an existing campaign
    Route::put('campaign_templates/{id}', [CampaignTemplateController::class, 'update'])->name('campaign_templates.update');
    
    // Route to delete an existing campaign
    Route::delete('campaign_templates/{id}', [CampaignTemplateController::class, 'destroy'])->name('campaign_templates.destroy');
});



use App\Http\Controllers\Admin\HelpArticleController;



Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('help-articles/view', [HelpArticleController::class, 'index'])->name('help-articles.index');
    Route::get('help-articles/create', [HelpArticleController::class, 'create'])->name('help-articles.create');
    Route::post('help-articles', [HelpArticleController::class, 'store'])->name('help-articles.store');
    Route::get('help-articles/{id}', [HelpArticleController::class, 'edit'])->name('help-articles.edit');
    Route::put('help-articles/{id}', [HelpArticleController::class, 'update'])->name('help-articles.update');
    Route::delete('help-articles/{id}', [HelpArticleController::class, 'destroy'])->name('help-articles.destroy');
});


Route::prefix('admin')->name('admin.')->group(function () {
    // Route to view all campaigns
    Route::get('mailing_lists/view', [MailingListController::class, 'index'])->name('mailing_lists.index');
    
    // Route to show the form for creating a new campaign
    Route::get('mailing_lists/create', [MailingListController::class, 'create'])->name('mailing_lists.create');
    
    // Route to store a new campaign
    Route::post('mailing_lists/store', [MailingListController::class, 'store'])->name('mailing_lists.store');
    
   
    // Route to show the form for editing a campaign
    Route::get('mailing_lists/{id}', [MailingListController::class, 'edit'])->name('mailing_lists.edit');
    
    // Route to update an existing campaign
    Route::put('mailing_lists/{id}', [MailingListController::class, 'update'])->name('mailing_lists.update');
    
    // Route to delete an existing campaign
    Route::delete('mailing_lists/{id}', [MailingListController::class, 'destroy'])->name('mailing_lists.destroy');

    // Handle bulk action (create/transfer)
    Route::post('mailing-lists/bulk-action/store', [MailingListController::class, 'handleBulkAction'])->name('mailing_lists.bulk_action');

    Route::get('mailing-lists/pages-{listId}', [MailingListController::class, 'getPagesByList']);
    Route::get('mailing-lists/page-all', [MailingListController::class, 'getAllPages']);
    // Get areas by city
    Route::get('areas-by-city', [MailingListController::class, 'getAreasByCity']);
  

});

Route::get('/track-email', [\App\Http\Controllers\TrackingController::class, 'track'])->name('track.email');

Route::get('/admin/category/bulk-update', [CategoryBulkUpdateController::class, 'showForm'])
    ->name('admin.category.bulk-update');

Route::post('/admin/category/bulk-update', [CategoryBulkUpdateController::class, 'handleUpdate'])
    ->name('admin.category.bulk-update.submit');

Route::get('/admin/ajax/page-categories', [CategoryBulkUpdateController::class, 'ajaxSearchCategories'])
    ->name('admin.category.ajax-search');


    Route::name('admin.')->middleware(['auth', 'admin'])->group(function () {
        Route::get('admin/services/view', [ServiceController::class, 'index'])->name('services.index');
        Route::get('admin/services/create', [ServiceController::class, 'create'])->name('services.create');
        Route::post('admin/services/create', [ServiceController::class, 'store'])->name('services.store');
    });
    



Route::name('admin.')->middleware(['auth', 'admin'])->group(function () {
Route::get('admin/user/activity', [ActivityController::class, 'index'])->name('user.activity');
// web.php ya admin.php (jo bhi dashboard ke liye use kar rahe ho)
Route::get('admin/activity-cities/{user_id}', [ActivityController::class, 'cityBreakdown'])->name('user.activity.cities');
Route::get('activity-city/{cityId}/{user_id}', [ActivityController::class, 'cityActivityReport'])->name('city.activity.report');
 });





//Route::resource('campaign_templates', CampaignTemplateController::class);
