<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PublicController;

/*
|--------------------------------------------------------------------------
| API Routes — Next.js Frontend
|--------------------------------------------------------------------------
|
| All routes are prefixed with /api (via RouteServiceProvider).
| Public routes: /api/public/*
| Auth routes:   /api/auth/*
|
*/

// ─── Health Check ────────────────────────────────────────────────────

Route::get('/health', function () {
    return response()->json([
        'status'    => 'healthy',
        'timestamp' => now()->toISOString(),
        'message'   => 'Laravel API is running',
    ]);
});

// ─── Public Endpoints (No Auth Required) ─────────────────────────────

Route::prefix('public')->group(function () {

    // Cities
    Route::get('/cities', [PublicController::class, 'cities']);
    Route::post('/cities/search', [PublicController::class, 'searchCities']);
    Route::get('/cities/{state_id}', [PublicController::class, 'citiesByState']);

    // Areas
    Route::get('/areas/{city_id}', [PublicController::class, 'areasByCity']);

    // Page Categories
    Route::get('/categories', [PublicController::class, 'categories']);
    Route::get('/categories/parent', [PublicController::class, 'parentCategories']);
    Route::get('/categories/{city_id}', [PublicController::class, 'categoriesByCity']);
    Route::get('/subcategories/{category_id}', [PublicController::class, 'subcategories']);

    // Event Categories
    Route::get('/event-categories', [PublicController::class, 'eventCategories']);
    Route::get('/event-categories/parent', [PublicController::class, 'eventParentCategories']);

    // Product Categories
    Route::get('/product-categories', [PublicController::class, 'productCategories']);
    Route::get('/product-categories/parent', [PublicController::class, 'productParentCategories']);
    Route::get('/brands', [PublicController::class, 'brands']);

    // Group Categories
    Route::get('/group-categories', [PublicController::class, 'groupCategories']);
    Route::get('/group-categories/parent', [PublicController::class, 'groupParentCategories']);

    // Blog Categories
    Route::get('/blog-categories', [PublicController::class, 'blogCategories']);
    Route::get('/blog-categories/parent', [PublicController::class, 'blogParentCategories']);

    // Events
    Route::get('/events', [PublicController::class, 'events']);
    Route::get('/events/scroll', [PublicController::class, 'eventsByScroll']);
    Route::get('/events/{city_slug}', [PublicController::class, 'eventsByCity']);
    Route::get('/events/category/{category_slug}', [PublicController::class, 'eventsByCategory']);
    Route::get('/events/{category_slug}-in-{city_slug}', [PublicController::class, 'eventsByCategoryInCity']);

    // Products / Deals
    Route::get('/products', [PublicController::class, 'products']);
    Route::get('/products/scroll', [PublicController::class, 'productsByScroll']);
    Route::get('/products/{city_slug}', [PublicController::class, 'productsByCity']);

    // Pages / Business Listings
    Route::get('/pages', [PublicController::class, 'pages']);
    Route::get('/pages/scroll', [PublicController::class, 'pagesByScroll']);
    Route::get('/pages/{city_slug}', [PublicController::class, 'pagesByCity']);

    // Groups
    Route::get('/groups', [PublicController::class, 'groups']);
    Route::get('/groups/scroll', [PublicController::class, 'groupsByScroll']);
    Route::get('/groups/{city_slug}', [PublicController::class, 'groupsByCity']);

    // Blogs
    Route::get('/blogs', [PublicController::class, 'blogs']);
    Route::get('/blogs/scroll', [PublicController::class, 'blogsByScroll']);
    Route::get('/blogs/{city_slug}', [PublicController::class, 'blogsByCity']);
});

// ─── Auth-Protected Endpoints ────────────────────────────────────────

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // TODO: Add auth-protected API routes here as needed:
    // Route::post('/events', [EventApiController::class, 'store']);
    // Route::post('/products', [ProductApiController::class, 'store']);
    // etc.
});
