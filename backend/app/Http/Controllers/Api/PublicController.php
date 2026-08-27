<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\CityHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Public JSON API endpoints for the Next.js frontend.
 *
 * All routes are under /api/public/* and require NO authentication.
 * These return clean JSON (no Blade views).
 */
class PublicController extends Controller
{
    /**
     * Wrap a callback and return an empty array on database errors.
     * Prevents 500s when tables don't exist (e.g. fresh SQLite).
     */
    private function safeQuery(callable $callback)
    {
        try {
            return response()->json($callback());
        } catch (\Exception $e) {
            report($e);
            return response()->json([]);
        }
    }

    // ─── Cities ──────────────────────────────────────────────────────

    /**
     * GET /api/public/cities
     * All active cities (those with approved pages).
     */
    public function cities()
    {
        return $this->safeQuery(fn() => CityHelper::getActiveCities());
    }

    /**
     * POST /api/public/cities/search
     * Search cities by filter string (for the city picker search box).
     */
    public function searchCities(Request $request)
    {
        $filter = trim($request->input('filter', ''));

        if (empty($filter)) {
            return response()->json([]);
        }

        return $this->safeQuery(fn() =>
            DB::table('cities')
                ->where('is_approved', 'Y')
                ->where('city_name', 'like', '%' . $filter . '%')
                ->orderBy('city_name', 'asc')
                ->limit(30)
                ->get(['id', 'city_slug', 'city_name'])
        );
    }

    /**
     * GET /api/public/cities/{state_id}
     * Cities in a specific state.
     */
    public function citiesByState(int $state_id)
    {
        return $this->safeQuery(fn() =>
            DB::table('cities')
                ->select('id', 'city_name', 'city_slug')
                ->where('state_id', $state_id)
                ->where('is_approved', 'Y')
                ->orderBy('city_name', 'asc')
                ->get()
        );
    }

    // ─── Areas ───────────────────────────────────────────────────────

    /**
     * GET /api/public/areas/{city_id}
     * Areas in a specific city.
     */
    public function areasByCity(int $city_id)
    {
        return $this->safeQuery(fn() =>
            DB::table('areas')
                ->select('id', 'area_name', 'city_id')
                ->where('city_id', $city_id)
                ->orderBy('area_name', 'asc')
                ->get()
        );
    }

    // ─── Categories (Page) ───────────────────────────────────────────

    /**
     * GET /api/public/categories
     * All page categories with parent info.
     */
    public function categories()
    {
        return $this->safeQuery(fn() =>
            DB::table('pagecategories')
                ->select('pagecategories.id', 'pagecategories.category_name', 'cat.category_name as parent')
                ->leftJoin('pagecategories as cat', 'cat.id', '=', 'pagecategories.category_parent_id')
                ->orderBy('id', 'asc')
                ->get()
        );
    }

    /**
     * GET /api/public/categories/parent
     * Parent page categories only.
     */
    public function parentCategories()
    {
        return $this->safeQuery(fn() =>
            DB::table('pagecategories')
                ->whereNull('pagecategories.category_parent_id')
                ->get()
        );
    }

    /**
     * GET /api/public/categories/{city_id}
     * Page categories for a specific city.
     */
    public function categoriesByCity(int $city_id)
    {
        return $this->safeQuery(fn() =>
            DB::table('pagecategories')
                ->select('pagecategories.id', 'pagecategories.category_name', 'pagecategories.category_parent_id')
                ->join('page_category', 'page_category.category_id', '=', 'pagecategories.id')
                ->join('pages', 'pages.id', '=', 'page_category.page_id')
                ->where('pages.city_id', $city_id)
                ->where('pages.item_status', 2)
                ->distinct()
                ->get()
        );
    }

    /**
     * GET /api/public/subcategories/{category_id}
     * Subcategories for a given parent category.
     */
    public function subcategories(int $category_id)
    {
        return $this->safeQuery(fn() =>
            DB::table('pagecategories')
                ->where('pagecategories.category_parent_id', $category_id)
                ->orderBy('category_name', 'asc')
                ->get()
        );
    }

    // ─── Event Categories ────────────────────────────────────────────

    /**
     * GET /api/public/event-categories
     * All event categories.
     */
    public function eventCategories()
    {
        return $this->safeQuery(fn() =>
            DB::table('eventcategories')
                ->select('eventcategories.id', 'eventcategories.category_name', 'cat.category_name as parent')
                ->leftJoin('eventcategories as cat', 'cat.id', '=', 'eventcategories.category_parent_id')
                ->orderBy('id', 'asc')
                ->get()
        );
    }

    /**
     * GET /api/public/event-categories/parent
     * Parent event categories only.
     */
    public function eventParentCategories()
    {
        return $this->safeQuery(fn() =>
            DB::table('eventcategories')
                ->whereNull('eventcategories.category_parent_id')
                ->get()
        );
    }

    // ─── Product Categories ──────────────────────────────────────────

    /**
     * GET /api/public/product-categories
     * All product categories.
     */
    public function productCategories()
    {
        return $this->safeQuery(fn() =>
            DB::table('productcategories')
                ->select('productcategories.id', 'productcategories.product_category_name as category_name', 'cat.product_category_name as parent')
                ->leftJoin('productcategories as cat', 'cat.id', '=', 'productcategories.product_category_parent_id')
                ->orderBy('id', 'asc')
                ->get()
        );
    }

    /**
     * GET /api/public/product-categories/parent
     * Parent product categories only.
     */
    public function productParentCategories()
    {
        return $this->safeQuery(fn() =>
            DB::table('productcategories')
                ->whereNull('productcategories.product_category_parent_id')
                ->get()
        );
    }

    /**
     * GET /api/public/brands
     * All product brands.
     */
    public function brands()
    {
        return $this->safeQuery(fn() =>
            DB::table('brands')
                ->orderBy('name', 'asc')
                ->get()
        );
    }

    // ─── Group Categories ────────────────────────────────────────────

    /**
     * GET /api/public/group-categories
     * All group categories.
     */
    public function groupCategories()
    {
        return $this->safeQuery(fn() =>
            DB::table('groupcategories')
                ->select('groupcategories.id', 'groupcategories.category_name', 'cat.category_name as parent')
                ->leftJoin('groupcategories as cat', 'cat.id', '=', 'groupcategories.category_parent_id')
                ->orderBy('id', 'asc')
                ->get()
        );
    }

    /**
     * GET /api/public/group-categories/parent
     * Parent group categories only.
     */
    public function groupParentCategories()
    {
        return $this->safeQuery(fn() =>
            DB::table('groupcategories')
                ->whereNull('groupcategories.category_parent_id')
                ->get()
        );
    }

    // ─── Blog Categories ─────────────────────────────────────────────

    /**
     * GET /api/public/blog-categories
     * All blog categories.
     */
    public function blogCategories()
    {
        return $this->safeQuery(fn() =>
            DB::table('blogcategories')
                ->select('blogcategories.id', 'blogcategories.category_name', 'cat.category_name as parent')
                ->leftJoin('blogcategories as cat', 'cat.id', '=', 'blogcategories.category_parent_id')
                ->orderBy('id', 'asc')
                ->get()
        );
    }

    /**
     * GET /api/public/blog-categories/parent
     * Parent blog categories only.
     */
    public function blogParentCategories()
    {
        return $this->safeQuery(fn() =>
            DB::table('blogcategories')
                ->whereNull('blogcategories.category_parent_id')
                ->get()
        );
    }

    // ─── Events (Public) ─────────────────────────────────────────────

    /**
     * GET /api/public/events
     * All upcoming public events.
     */
    public function events()
    {
        return $this->safeQuery(fn() =>
            DB::table('events')
                ->where('privacy', 'public')
                ->where('events.event_date', '>=', Carbon::now())
                ->whereNull('group_id')
                ->orderBy('id', 'DESC')
                ->limit(50)
                ->get()
        );
    }

    /**
     * GET /api/public/events/{city_slug}
     * Events in a specific city.
     */
    public function eventsByCity(string $city_slug)
    {
        return $this->safeQuery(function() {
            $city = DB::table('cities')->where('city_slug', $city_slug)->first();
            if (!$city) return [];

            return DB::table('events')
                ->where('events.city_id', $city->id)
                ->where('privacy', 'public')
                ->where('events.event_date', '>=', Carbon::now())
                ->orderBy('events.event_date', 'asc')
                ->limit(50)
                ->get();
        });
    }

    /**
     * GET /api/public/events/category/{category_slug}
     * Events by category.
     */
    public function eventsByCategory(string $category_slug)
    {
        return $this->safeQuery(function() {
            $category = DB::table('eventcategories')->where('category_slug', $category_slug)->first();
            if (!$category) return [];

            return DB::table('events')
                ->where('events.category_id', $category->id)
                ->where('privacy', 'public')
                ->where('events.event_date', '>=', Carbon::now())
                ->orderBy('events.event_date', 'asc')
                ->limit(50)
                ->get();
        });
    }

    /**
     * GET /api/public/events/{category_slug}-in-{city_slug}
     * Events by category in a city.
     */
    public function eventsByCategoryInCity(string $category_slug, string $city_slug)
    {
        return $this->safeQuery(function() {
            $category = DB::table('eventcategories')->where('category_slug', $category_slug)->first();
            $city = DB::table('cities')->where('city_slug', $city_slug)->first();
            if (!$category || !$city) return [];

            return DB::table('events')
                ->where('events.category_id', $category->id)
                ->where('events.city_id', $city->id)
                ->where('privacy', 'public')
                ->where('events.event_date', '>=', Carbon::now())
                ->orderBy('events.event_date', 'asc')
                ->limit(50)
                ->get();
        });
    }

    /**
     * GET /api/public/events/scroll?offset=0&limit=20
     * Infinite-scroll loader for events (JSON, not Blade).
     */
    public function eventsByScroll(Request $request)
    {
        $offset = (int) $request->input('offset', 0);
        $limit = min((int) $request->input('limit', 20), 50);

        return $this->safeQuery(fn() =>
            DB::table('events')
                ->where('privacy', 'public')
                ->where('events.event_date', '>=', Carbon::now())
                ->whereNull('group_id')
                ->orderBy('id', 'DESC')
                ->skip($offset)
                ->take($limit)
                ->get()
        );
    }

    // ─── Products / Deals (Public) ───────────────────────────────────

    /**
     * GET /api/public/products
     * All products.
     */
    public function products()
    {
        return $this->safeQuery(fn() =>
            DB::table('marketplaces')
                ->orderBy('id', 'DESC')
                ->limit(50)
                ->get()
        );
    }

    /**
     * GET /api/public/products/{city_slug}
     * Products in a city.
     */
    public function productsByCity(string $city_slug)
    {
        return $this->safeQuery(function() {
            $city = DB::table('cities')->where('city_slug', $city_slug)->first();
            if (!$city) return [];

            return DB::table('marketplaces')
                ->join('pages', 'pages.id', '=', 'marketplaces.page_id')
                ->where('pages.city_id', $city->id)
                ->orderBy('marketplaces.id', 'DESC')
                ->limit(50)
                ->get();
        });
    }

    /**
     * GET /api/public/products/scroll?offset=0&limit=20
     * Infinite-scroll loader for products.
     */
    public function productsByScroll(Request $request)
    {
        $offset = (int) $request->input('offset', 0);
        $limit = min((int) $request->input('limit', 20), 50);

        return $this->safeQuery(fn() =>
            DB::table('marketplaces')
                ->orderBy('id', 'DESC')
                ->skip($offset)
                ->take($limit)
                ->get()
        );
    }

    // ─── Pages / Business Listings (Public) ──────────────────────────

    /**
     * GET /api/public/pages
     * All active pages.
     */
    public function pages()
    {
        return $this->safeQuery(fn() =>
            DB::table('pages')
                ->where('item_status', 2)
                ->orderBy('id', 'DESC')
                ->limit(50)
                ->get()
        );
    }

    /**
     * GET /api/public/pages/{city_slug}
     * Pages in a city.
     */
    public function pagesByCity(string $city_slug)
    {
        return $this->safeQuery(function() {
            $city = DB::table('cities')->where('city_slug', $city_slug)->first();
            if (!$city) return [];

            return DB::table('pages')
                ->where('pages.city_id', $city->id)
                ->where('item_status', 2)
                ->orderBy('id', 'DESC')
                ->limit(50)
                ->get();
        });
    }

    /**
     * GET /api/public/pages/scroll?offset=0&limit=20
     * Infinite-scroll loader for pages.
     */
    public function pagesByScroll(Request $request)
    {
        $offset = (int) $request->input('offset', 0);
        $limit = min((int) $request->input('limit', 20), 50);

        return $this->safeQuery(fn() =>
            DB::table('pages')
                ->where('item_status', 2)
                ->orderBy('id', 'DESC')
                ->skip($offset)
                ->take($limit)
                ->get()
        );
    }

    // ─── Groups (Public) ─────────────────────────────────────────────

    /**
     * GET /api/public/groups
     * All public groups.
     */
    public function groups()
    {
        return $this->safeQuery(fn() =>
            DB::table('groups')
                ->where('privacy', 'public')
                ->orderBy('id', 'DESC')
                ->limit(50)
                ->get()
        );
    }

    /**
     * GET /api/public/groups/{city_slug}
     * Groups in a city.
     */
    public function groupsByCity(string $city_slug)
    {
        return $this->safeQuery(function() {
            $city = DB::table('cities')->where('city_slug', $city_slug)->first();
            if (!$city) return [];

            return DB::table('groups')
                ->where('groups.city_id', $city->id)
                ->orderBy('id', 'DESC')
                ->limit(50)
                ->get();
        });
    }

    /**
     * GET /api/public/groups/scroll?offset=0&limit=20
     * Infinite-scroll loader for groups.
     */
    public function groupsByScroll(Request $request)
    {
        $offset = (int) $request->input('offset', 0);
        $limit = min((int) $request->input('limit', 20), 50);

        return $this->safeQuery(fn() =>
            DB::table('groups')
                ->where('privacy', 'public')
                ->orderBy('id', 'DESC')
                ->skip($offset)
                ->take($limit)
                ->get()
        );
    }

    // ─── Blogs (Public) ──────────────────────────────────────────────

    /**
     * GET /api/public/blogs
     * All blogs.
     */
    public function blogs()
    {
        return $this->safeQuery(fn() =>
            DB::table('blogs')
                ->orderBy('id', 'DESC')
                ->limit(50)
                ->get()
        );
    }

    /**
     * GET /api/public/blogs/{city_slug}
     * Blogs in a city.
     */
    public function blogsByCity(string $city_slug)
    {
        return $this->safeQuery(function() {
            $city = DB::table('cities')->where('city_slug', $city_slug)->first();
            if (!$city) return [];

            return DB::table('blogs')
                ->where('blogs.city_id', $city->id)
                ->orderBy('id', 'DESC')
                ->limit(50)
                ->get();
        });
    }

    /**
     * GET /api/public/blogs/scroll?offset=0&limit=20
     * Infinite-scroll loader for blogs.
     */
    public function blogsByScroll(Request $request)
    {
        $offset = (int) $request->input('offset', 0);
        $limit = min((int) $request->input('limit', 20), 50);

        return $this->safeQuery(fn() =>
            DB::table('blogs')
                ->orderBy('id', 'DESC')
                ->skip($offset)
                ->take($limit)
                ->get()
        );
    }
}
