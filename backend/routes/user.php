<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{UserController};
use App\Http\Controllers\{PaymentHistory};
use App\Http\Controllers\{TicketController,WalletController};
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\HelpCenterController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminChatController;
use App\Http\Controllers\AdminMarketChatController;

//User controllers group routing


Route::controller(UserController::class)->middleware('auth', 'user', 'verified', 'activity', 'prevent-back-history')->group(function () {
    // Route::get('user/dashboard', 'dashboard')->name('user.dashboard');
     Route::get('user/dashboard/view', 'dashboard')->name('user.dashboard');

     Route::post('user/buyer-lead-stage/store','storeBuyerLeadStage')->name('buyer.leadStage.store');



    // Route::get('user/ads', 'ads')->name('user.ads');

    Route::get('user/view/ads', 'ads')->name('user.ads');
    Route::get('user/ad/create', 'ad_create')->name('user.ad.create');
    Route::post('user/ad/store', 'ad_store')->name('user.ad.store');
    Route::get('user/ad/edit/{id}', 'ad_edit')->name('user.ad.edit');
    Route::post('user/ad/update/{id}', 'ad_update')->name('user.ad.update');
    // Route::get('user/ad/status/{id}', 'ad_status')->name('user.ad.status');
    Route::get('user/ad-delete/{id}', 'ad_delete')->name('user.ad.delete');
    //Route::get('user/ad/activation/{id}', 'ad_activation')->name('user.ad.activation');user.ad.ad_charge_by_daterange
    Route::get('user/ad/ad_charge_by_daterange', 'ad_charge_by_daterange')->name('user.ad.ad_charge_by_daterange');
    Route::post('user/ad/payment_configuration/{id}', 'payment_configuration')->name('user.ad.payment_configuration');
    Route::get('user/ad/payment_success/{identifier}', 'payment_success')->name('user.ad.payment_success');
    Route::get('user/product/enquiry-view/', 'view_product_enquiry')->name('user.product.enquiry');

    Route::get('user/pages/view/', 'view_pages')->name('user.pages');
    Route::get('user/products/view/', 'view_products')->name('user.products');
    Route::get('user/events/view/', 'view_events')->name('user.events');

    Route::get('user/page/create', 'page_create')->name('user.page.create');
    Route::post('user/page/created/', 'page_created')->name('user.page.created');
    Route::get('user/listings/incomplete','showIncompleteListings')->name('user.listings.incomplete');


    Route::get('user/support/tickets-view/', 'ticket_index')->name('user.tickets');
    Route::get('user/support/tickets-create', 'ticket_create')->name('tickets.create');
    Route::post('user/support/tickets', 'ticket_store')->name('tickets.store');
    Route::get('user/support-tickets/{ticket}', 'ticket_show')->name('tickets.show');
    Route::get('user/support/tickets/{ticket}/edit', 'ticket_edit')->name('admin.tickets.edit');
    Route::put('user/tickets/{ticket}','ticket_update')->name('tickets.update');
    Route::delete('admin/tickets/{ticket}',  'ticket_destroy')->name('admin.tickets.destroy');



    

});
Route::controller(WalletController::class)->middleware('auth', 'user', 'verified', 'activity', 'prevent-back-history')->group(function () {
Route::get('user/wallet/view',  'index')->name('wallet.index');
    Route::post('user/wallet/add',  'addMoney')->name('wallet.add');
    Route::post('user/wallet/use',  'useMoney')->name('wallet.use');
    Route::post('/wallet/payment-success', 'paymentSuccess')->name('wallet.payment.success');
});

Route::get('user/subscriptions/view', [SubscriptionController::class, 'showPlans'])->middleware('auth', 'user', 'verified', 'activity', 'prevent-back-history')->name('user.subscriptions');
Route::post('user/subscribe', [SubscriptionController::class, 'subscribe'])->name('user.subscribe.index');
Route::post('/user/subscription/payment-success', [SubscriptionController::class,'paymentSuccess'])->name('user.subscription.payment.success');
Route::get('/user/subscribe/{id}', [SubscriptionController::class,'subscribe'])->name('user.subscribe');
Route::get('/user/subscribe-free/{id}', [SubscriptionController::class,'subscribeFree'])->name('user.subscribe.free');
Route::post('/user/create-razorpay-order', [SubscriptionController::class,'createRazorpayOrder'])->name('create.razorpay.order');

Route::post('user/wallet/payment', [SubscriptionController::class, 'payWithWallet'])->name('user.wallet.payment');


Route::get('user/transactions/report', [SubscriptionController::class, 'transactions_report'])->name('transactions.report');


Route::post('user/marketplace/reviews', [ReviewController::class, 'store'])
->name('marketplace.reviews.store');


Route::post('user/pages/reviews', [ReviewController::class, 'storepagesreview'])
->name('pages.reviews.store');

Route::post('user/blog-reviews/reviews', [ReviewController::class, 'storeblog'])
->name('marketplace.reviews.blog.store');
Route::get('user/marketplace/{id}', [ReviewController::class, 'loadMoreReviews'])->name('marketplace.reviews.load_more');

Route::get('user/blog-reviews/{id}', [ReviewController::class, 'loadMoreblogReviews'])->name('marketplace.blog.reviews.load_more');


Route::get('user/pages-reviews/{id}', [ReviewController::class, 'loadMorepagesReviews'])->name('marketplace.pages.reviews.load_more');



Route::controller(PaymentHistory::class)->middleware('auth', 'user', 'verified', 'activity', 'prevent-back-history')->group(function () {
    Route::get('user/payment-histories', 'index')->name('user.payment_histories');
});

Route::controller(HelpCenterController::class)->middleware('auth', 'user', 'verified', 'activity', 'prevent-back-history')->group(function () {
    Route::get('user/help/search','search')->name('user.help.search');
    Route::get('user/help/{id}','show')->name('user.help.show');
    Route::get('user/search/live','liveSearch')->name('search.live');

    });


// Admin panel conversation routes
Route::controller(AdminChatController::class)->middleware('auth', 'user', 'verified', 'activity', 'prevent-back-history')->group(function () {
   Route::get('user/chat/conversations', [AdminChatController::class, 'index'])->name('admin.conversations.index');
Route::get('user/chat-conversations/{id}', [AdminChatController::class, 'show'])->name('admin.conversations.show');
Route::post('user/chat/conversations/{id}/message', [AdminChatController::class, 'sendMessage'])->name('admin.conversations.message');
Route::get('user/chat/{id}', [AdminChatController::class, 'fetchpagechatMessages'])->name('admin.chat.fetch');



});


// Admin panel conversation routes
Route::controller(AdminMarketChatController::class)->middleware('auth', 'user', 'verified', 'activity', 'prevent-back-history')->group(function () {
   Route::get('user/market-chat/conversations', [AdminMarketChatController::class, 'index'])->name('admin.market.conversations.index');
Route::get('user/market-chat-conversations/{id}', [AdminMarketChatController::class, 'show'])->name('admin.market.conversations.show');
Route::post('user/market-chat/conversations/{id}/message', [AdminMarketChatController::class, 'sendMessage'])->name('admin.market.conversations.message');
Route::get('user/market-chat/{id}', [AdminMarketChatController::class, 'fetchMarketplaceMessages'])->name('admin.market.chat.fetch');



});


Route::get('user/leads/view', [LeadController::class, 'index'])->name('leads.index');
Route::post('user/leads/buy/{id}', [LeadController::class, 'buyLead'])->name('leads.buy');
Route::get('user/leads-view/{id}', [LeadController::class, 'viewLead'])->name('leads.view');

Route::post('/leads/buy/wallet', [LeadController::class, 'buyLeadFromWallet'])->name('leads.buy.wallet');
Route::post('/leads/buy/online', [LeadController::class, 'buyLeadOnline'])->name('leads.buy.online');
Route::match(['get', 'post'], 'user/payment/success', [LeadController::class, 'paymentSuccess'])
    ->name('user.payment.success');

    Route::get('user/fetch/states', [LeadController::class, 'fetchStates'])->name('user.fetch.states');
Route::get('user/fetch/cities', [LeadController::class, 'fetchCities'])->name('user.fetch.cities');
Route::get('user/fetch/areas', [LeadController::class, 'fetchAreas'])->name('user.fetch.areas');
Route::get('user/fetch/categories', [LeadController::class, 'fetchCategories'])->name('user.fetch.categories');
Route::get('user/fetch/get-areas-by-city', [LeadController::class, 'getAreasByCity'])->name('get.areas.by.city');


Route::get('user/lead/purchase-report', [LeadController::class, 'leadPurchaseReport'])->name('user.lead.purchase.report');





