<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use App\Models\Media_files;
use App\Models\SavedProduct;
use App\Models\FileUploader;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ManageApproval;
use Illuminate\Http\Request;
use App\Models\Review;
use Image;
use Session;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Artesaos\SEOTools\Facades\SEOMeta;
use App\Mail\ProductMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Helpers\CityHelper;
use Illuminate\Support\Facades\Cache;
use App\Services\UserActivityService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class MarketplaceController extends Controller
{
    private function getMarketFilterData(Request $request, $category_id = null)
    {
        $filter_city     = $request->input('city_filter') ?: $request->input('city');
        $filter_area     = $request->input('area_filter', $request->input('area', '0'));
        $filter_sort_by  = $request->input('filter_sort_by', 'newest');
        $filter_category = $category_id ?: $request->input('category_filter', $request->input('category', '0'));

        // All cities with active products — using master table for speed
        $cityCacheKey = "active_market_cities_cat_" . ($category_id ?: 'all') . "_v2";
        $all_cities = Cache::remember($cityCacheKey, 3600, function () use ($category_id) {
            return DB::table('cities')
                ->join('category_counts_master', 'category_counts_master.city_id', '=', 'cities.id')
                ->where('category_counts_master.content_type', 'marketplace')
                ->where('category_counts_master.total_count', '>', 0)
                ->when($category_id, fn($q) => $q->where('category_counts_master.subcategory_id', $category_id))
                ->select('cities.id', 'cities.city_name', 'cities.city_slug')
                ->distinct()
                ->orderBy('cities.city_name')
                ->get();
        });

        // Dynamic categories for sidebar
        $catCacheKey = "market_parent_cats_city_{$filter_city}_area_{$filter_area}_v2";
        $sidebar_product_categories = Cache::remember($catCacheKey, 1800, function () use ($filter_city, $filter_area) {
             $counts = DB::table('category_counts_master')
                ->select('subcategory_id', DB::raw('SUM(total_count) as total'))
                ->where('content_type', 'marketplace')
                ->when($filter_city, fn($q) => $q->where('city_id', $filter_city))
                ->when($filter_area && $filter_area !== '0', fn($q) => $q->where('area_id', $filter_area))
                ->groupBy('subcategory_id');

            return DB::table('categories')
                ->select('categories.id', 'categories.product_category_name', 'categories.product_category_slug', 'counts.total as product_count')
                ->joinSub($counts, 'counts', 'counts.subcategory_id', '=', 'categories.id')
                ->where(function ($q) {
                    $q->where('categories.category_parent_id', 0)->orWhereNull('categories.category_parent_id');
                })
                ->orderBy('categories.product_category_name')
                ->get();
        });

        // Current active areas for filtering
        $filter_areas = ($filter_city && $filter_city !== '0')
            ? Cache::remember("active_market_areas_city_{$filter_city}_cat_" . ($category_id ?: 'all') . "_v2", 3600, function () use ($filter_city, $category_id) {
                return DB::table('areas')
                    ->join('category_counts_master', 'category_counts_master.area_id', '=', 'areas.id')
                    ->where('category_counts_master.city_id', $filter_city)
                    ->where('category_counts_master.content_type', 'marketplace')
                    ->where('category_counts_master.total_count', '>', 0)
                    ->when($category_id, fn($q) => $q->where('category_counts_master.subcategory_id', $category_id))
                    ->select('areas.id', 'areas.area_name', 'areas.area_slug')
                    ->distinct()
                    ->orderBy('areas.area_name')
                    ->get();
            })
            : collect();

        return [
            'all_cities'                 => $all_cities,
            'sidebar_product_categories' => $sidebar_product_categories,
            'filter_areas'               => $filter_areas,
            'filter_city'                => $filter_city,
            'filter_area'                => $filter_area,
            'filter_sort_by'             => $filter_sort_by,
            'filter_category'            => $filter_category
        ];
    }
    public function updateLeadPrice(Request $request, $categoryId)
    {
        $request->validate([
            "lead_price" => "required|numeric|min:0",
        ]);

        $category = Category::findOrFail($categoryId);
        $newPrice = $request->input("lead_price");

        // Update price recursively
        $category->updateLeadPriceRecursively($newPrice);

        return response()->json([
            "message" => "Lead price updated successfully",
        ]);
    }

    public function getCities(Request $request)
    {
        $search = trim($request->get("q")); 

        $cacheKey = 'enquiry_cities_v6_' . md5(strtolower($search));
        $unique_results = Cache::remember($cacheKey, 300, function () use ($search) {
            if (empty($search)) {
                // Pre-populate with first 30 cities
                $query = DB::table('cities')
                    ->select('id as city_id', 'city_name', DB::raw('NULL as area_id'), DB::raw('NULL as area_name'))
                    ->orderBy('city_name', 'asc')
                    ->limit(30)
                    ->get();
            } else {
                // Split search term by spaces or commas
                $parts = array_filter(explode(' ', str_replace(',', ' ', $search)));
                $parts = array_values($parts);

                if (count($parts) >= 2) {
                    // E.g. "Lucknow Hazratganj"
                    $cityPart = $parts[0];
                    $areaPart = $parts[1];

                    $query = DB::table('areas')
                        ->join('cities', 'areas.city_id', '=', 'cities.id')
                        ->where('cities.city_name', 'like', "{$cityPart}%")
                        ->where('areas.area_name', 'like', "{$areaPart}%")
                        ->select('cities.id as city_id', 'cities.city_name', 'areas.id as area_id', 'areas.area_name')
                        ->limit(30)
                        ->get();
                } else {
                    // Single search term (e.g. "Luck" or "Hazrat")
                    // 1. Search in cities starting with the term (fast prefix index match)
                    $cities = DB::table('cities')
                        ->where('city_name', 'like', "{$search}%")
                        ->select('id as city_id', 'city_name', DB::raw('NULL as area_id'), DB::raw('NULL as area_name'))
                        ->limit(15)
                        ->get();

                    // 2. Search in areas starting with the term (fast prefix index match)
                    $areas = DB::table('areas')
                        ->join('cities', 'areas.city_id', '=', 'cities.id')
                        ->where('areas.area_name', 'like', "{$search}%")
                        ->select('cities.id as city_id', 'cities.city_name', 'areas.id as area_id', 'areas.area_name')
                        ->limit(20)
                        ->get();

                    // 3. Fallback: only if prefix search yields few results, do anywhere wildcard search
                    if ($cities->count() + $areas->count() < 5) {
                        $citiesFallback = DB::table('cities')
                            ->where('city_name', 'like', "%{$search}%")
                            ->select('id as city_id', 'city_name', DB::raw('NULL as area_id'), DB::raw('NULL as area_name'))
                            ->limit(10)
                            ->get();

                        $areasFallback = DB::table('areas')
                            ->join('cities', 'areas.city_id', '=', 'cities.id')
                            ->where('areas.area_name', 'like', "%{$search}%")
                            ->select('cities.id as city_id', 'cities.city_name', 'areas.id as area_id', 'areas.area_name')
                            ->limit(15)
                            ->get();

                        $query = $cities->merge($areas)->merge($citiesFallback)->merge($areasFallback);
                    } else {
                        $query = $cities->merge($areas);
                    }
                }
            }

            $results = [];
            foreach ($query as $row) {
                $text = $row->city_name;
                $id = $row->city_id . '_0';
                if (!empty($row->area_name)) {
                    $text .= ', ' . $row->area_name;
                    $id = $row->city_id . '_' . $row->area_id;
                }
                $results[] = [
                    'id' => $id,
                    'text' => $text
                ];
            }

            return collect($results)->unique('id')->values()->all();
        });

        return response()->json($unique_results);
    }

    public function getProducts(Request $request)
    {
        $search = $request->get("q"); 
        $location = $request->get("location");

        $query = DB::table("marketplaces")
            ->join("pages", "marketplaces.page_id", "=", "pages.id")
            ->where("marketplaces.product_status", 2)
            ->whereNotNull("marketplaces.title")
            ->where("marketplaces.title", "!=", "")
            ->select("marketplaces.id as id", DB::raw("TRIM(marketplaces.title) as text"));

        if (!empty($search)) {
            $query->where("marketplaces.title", "like", "%{$search}%");
        }

        $products = $query->distinct()->limit(30)->get();

        return response()->json($products);
    }

    public function storeenquiry(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "mobile" => "required|digits:10",
            "city_id" => "required",
            "area_id" => "nullable",
            "product_id" => "nullable",
            "custom_product" => "nullable|string|max:255",
        ]);

        if (empty($validated['product_id']) && empty($validated['custom_product'])) {
            return response()->json(['message' => 'Please select or type a product.'], 422);
        }

        // Insert the new enquiry using DB::table()
        DB::table("enquirymaster")->insert([
            "name" => $validated["name"],
            "mobileno" => $validated["mobile"],
            "cityid" => $validated["city_id"],
            "area_id" => empty($validated["area_id"]) || $validated["area_id"] == '0' ? null : $validated["area_id"],
            "productid" => empty($validated["product_id"]) ? null : $validated["product_id"],
            "custom_product" => empty($validated["custom_product"]) ? null : $validated["custom_product"],
            "userid" => auth()->id(),
            "createdAt" => now() // Use 'createdAt' column matching DESCRIBE output
        ]);

        if (auth()->check()) {
            $itemId = !empty($validated["product_id"]) ? $validated["product_id"] : 0;
            app(UserActivityService::class)->log(
                auth()->id(),
                "marketplace_enquiry",
                "product",
                $itemId,
                $itemId
            );
        }

        // Notify all sellers of that area/city (IndiaMart-style)
        $sellerQuery = DB::table('pages')
            ->where('item_status', 2)
            ->where('city_id', $validated['city_id']);

        if (!empty($validated['area_id']) && $validated['area_id'] != '0') {
            $sellerQuery->where('area_id', $validated['area_id']);
        }

        $sellerUserIds = $sellerQuery->pluck('user_id')->unique()->all();

        // Fallback to city-wide sellers if no sellers found in specific area
        if (empty($sellerUserIds) && !empty($validated['area_id']) && $validated['area_id'] != '0') {
            $sellerUserIds = DB::table('pages')
                ->where('item_status', 2)
                ->where('city_id', $validated['city_id'])
                ->pluck('user_id')
                ->unique()
                ->all();
        }

        $senderId = auth()->id() ?: null;
        foreach ($sellerUserIds as $sellerId) {
            // Avoid notifying oneself if the buyer is also a seller
            if ($senderId && $sellerId == $senderId) {
                continue;
            }

            try {
                DB::table('notifications')->insert([
                    'sender_user_id' => $senderId,
                    'reciver_user_id' => $sellerId,
                    'type' => 'marketplace_enquiry',
                    'status' => '0',
                    'view' => '0',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                \Log::warning("Could not insert notification for guest enquiry: " . $e->getMessage());
            }
        }

        // Return response with success message
        return response()->json([
            "message" => "Your enquiry has been submitted successfully!",
        ]);
    }

    public function allproducts(Request $request)
    {
        // Get current URL
        $currentUrl = URL::current();

        // Detect current page number from query parameters (e.g., ?page=2)
        $page = request()->query("page", 1); // default to 1 if 'page' not present

        // If on a pagination page (page > 1), set canonical to first page URL
        if ($page > 1) {
            // Remove the 'page' query parameter from current URL to get canonical URL
            $canonicalUrl = URL::current(); // base URL without query parameters
            $query = request()->query();
            unset($query["page"]); // remove page param

            if (!empty($query)) {
                $canonicalUrl .= "?" . http_build_query($query);
            }
        } else {
            // On first page, canonical is current URL
            $canonicalUrl = $currentUrl;
        }

        SEOMeta::setTitle(
            "Best Local Deals & Discounts | Save on Restaurants, Shopping & services "
        );
        SEOMeta::setDescription(
            "Find the best local deals, discounts, and offers on restaurants, shopping, entertainment, and more. Explore exclusive savings in your city with Cityhangaround!"
        );
        SEOMeta::setKeywords([
            "local deals",
            "discounts",
            "offers",
            "best deals near me",
            "city discounts",
            "shopping deals",
            "restaurant deals",
            "entertainment offers",
            "local savings",
        ]);

        SEOMeta::setCanonical($canonicalUrl);

        $page_data = [];

        // If on homepage (root URL), forget the selected city from session to avoid polluting default state
        if ($request->is('/')) {
            session()->forget(['selected_city_id', 'selected_city_name']);
        }

        // If user selected a city in header, it is stored in session('selected_city_id')
        if (
            !$request->filled('city_filter') &&
            !$request->filled('city') &&
            session('selected_city_id')
        ) {
            $request->merge(['city_filter' => session('selected_city_id')]);
        }

        $page_data = array_merge($page_data, $this->getMarketFilterData($request));

        // Specialized Collections for Product Landing
        $page_data["all_printable_categories"] = Cache::remember(
            "leaf_marketplace_categories_v2",
            3600,
            function () {
                return \App\Models\Category::whereExists(function ($q) {
                    $q->select(\DB::raw(1))
                        ->from("marketplaces")
                        ->where("marketplaces.product_status", 2)
                        ->whereRaw(
                            "categories.id = ANY(
                                ARRAY(
                                    SELECT NULLIF(TRIM(category_id), '')::BIGINT
                                    FROM unnest(string_to_array(marketplaces.category, ',')) AS category_id
                                    WHERE NULLIF(TRIM(category_id), '') IS NOT NULL
                                )
                            )"
                        );
                })
                    ->orderByDesc("id")
                    ->distinct()
                    ->get();
            }
        );

        $page_data["all_product_cities"] = $page_data['all_cities'];
        $page_data["all_product_areas"]  = $page_data['filter_areas'];

        $products_query = Marketplace::with([
            "page.city",
            "page.area.city",
            "productCategories",
            "page.categories",
            "getCurrency",
        ])
            ->join("pages", "marketplaces.page_id", "=", "pages.id")
            ->withAvg([
                "reviews as avg_rating" => function ($q) {
                    $q->where("type", "product");
                }
            ], "rating")
            ->where("marketplaces.product_status", 2)
            ->select("marketplaces.*");

        // City Filter (fast join-based)
        if ($request->filled("city_filter")) {
            $products_query->where("pages.city_id", $request->city_filter);
        }

        // Area Filter
        if ($request->filled("area_filter") && $request->area_filter != 0) {
            $products_query->where("pages.area_id", $request->area_filter);
        }

        // Sorting Logic
        $sort_by = $request->input("filter_sort_by", "newest");
        $page_data["filter_sort_by"] = $sort_by;

        // Prioritize featured, then by sorting
        $products_query->orderByDesc("marketplaces.item_featured");
        $products_query->orderBy(
            "marketplaces.created_at",
            $sort_by === "oldest" ? "asc" : "desc"
        );

        // Paginate
        $page_data["products"] = $products_query->paginate(12);

        $page_data["view_path"] = "frontend.marketplace.products";

        return view("frontend.product_index", $page_data);
    }

    public function Category($id)
    {
        $category = Category::find($id);

        return response()->json([
            "parent_id" => $category->category_parent_id,
        ]);
    }

    public function productcategory(Request $request, string $category_slug)
    {
        $category = Category::where(
            "product_category_slug",
            $category_slug
        )->first();
        abort_if(!$category, 404);
        $page_data["category"] = $category;

        $page_data = array_merge($page_data, $this->getMarketFilterData($request, $category->id));

        $page_data["all_product_cities"] = $page_data['all_cities'];

        // Cache subcategories of current category
        $page_data["all_printable_categories"] = Cache::remember("prod_subcats_{$category->id}_v2", 3600, function () use ($category) {
            return DB::table("categories")
                ->select("id", "product_category_name", "product_category_slug")
                ->where("category_parent_id", $category->id)
                ->orderBy("product_category_name", "asc")
                ->get();
        });

        SEOMeta::setTitle(
            "Best " .
            $category->product_category_name .
            " Deals – Save Big on Top Offers & Discounts"
        );
        SEOMeta::setDescription(
            "Find the best " .
            $category->product_category_name .
            " deals and exclusive discounts on restaurants, shopping, salons, spas, and entertainment. Get the hottest offers and save more with City Hangaround!"
        );
        SEOMeta::setKeywords([
            $category->product_category_name . " deals",
            "best " . $category->product_category_name . " discounts",
            "shopping deals",
            "restaurant offers",
            "top savings on " . $category->product_category_name,
            "limited-time " . $category->product_category_name . " promotions",
            "exclusive " . $category->product_category_name . " coupons",
        ]);

        SEOMeta::setCanonical(URL::current());

        $filter_city = $request->city ?? null;
        $filter_area = $request->area ?? "0";

        $page_data["filter_city"] = $filter_city;
        $page_data["filter_area"] = $filter_area;
        $products_query = Marketplace::with([
                "page.area.city",
                "productCategories",
                "page.categories",
                "getCurrency",
            ])
            ->withAvg([
                "reviews as avg_rating" => function ($q) {
                    $q->where("type", "product");
                },
            ], "rating")
            ->join("pages", "marketplaces.page_id", "=", "pages.id")
            ->join("category_product as cp_cat", "marketplaces.id", "=", "cp_cat.product_id")
            ->where("cp_cat.product_category_id", $category->id)
            ->where("marketplaces.product_status", 2)
            ->when(
                !empty($filter_city),
                fn($q) => $q->where("pages.city_id", $filter_city)
            )
            ->when(
                !empty($filter_area) && $filter_area !== "0",
                fn($q) => $q->where("pages.area_id", $filter_area)
            )
            ->select("marketplaces.*")
            ->orderByDesc("marketplaces.item_featured")
            ->orderByDesc("marketplaces.created_at")
            ->orderByDesc("marketplaces.id");

        // Sorting (optional override)
        $filter_sort_by = $request->input("filter_sort_by", "newest");
        $page_data["filter_sort_by"] = $filter_sort_by;

        if ($filter_sort_by === "oldest") {
            $products_query->orderBy("marketplaces.created_at", "ASC");
        }

        $paid_items = $products_query->paginate(10);

        $paid_items->appends([
            "filter_sort_by" => $filter_sort_by,
            "filter_city" => $filter_city,
            "filter_area" => $filter_area,
        ]);

        $page_data["products"] = $paid_items;
        $page_data["total_products"] = $paid_items->total();

        $page_data["view_path"] = "frontend.marketplace.category_products";
        return view("frontend.product_category_index", $page_data);
    }

    public function productarea(Request $request, $city_slug, $area_slug)
    {
        $city = DB::table("cities")
            ->select("cities.*")
            ->where("city_slug", $city_slug)
            ->first();
        abort_if(!$city, 404);

        $area = DB::table("areas")
            ->select("areas.*")
            ->where("city_id", $city->id)
            ->where("area_slug", $area_slug)
            ->first();
        abort_if(!$area, 404);

        $page_data["city"] = $city;
        $page_data["area"] = $area;

        $request->merge(['city_filter' => $city->id, 'area_filter' => $area->id]);
        $page_data = array_merge($page_data, $this->getMarketFilterData($request));

        $page_data["all_product_cities"] = $page_data['all_cities'];
        $page_data['all_areas'] = $page_data['filter_areas'];

        $page_data["all_printable_categories"] = $page_data["sidebar_product_categories"];

        if (!is_null($city)) {
            SEOMeta::setTitle(
                "Top deals, offers, discount " .
                $area->area_name .
                ", " .
                $city->city_name
            );
            SEOMeta::setDescription(
                "Top deals, offers, discount " .
                $area->area_name .
                "," .
                $city->city_name .
                ", best product, services "
            );
            SEOMeta::setKeywords([
                $area->area_name . " deals",
                "best deals in " . $area->area_name . " " . $city->city_name,
                "discounts in " . $area->area_name,
                "shopping offers " . $area->area_name,
                "restaurant deals in " .
                $area->area_name .
                " " .
                $city->city_name,
                "best savings in " . $area->area_name,
            ]);

            SEOMeta::setCanonical(URL::current());

            $paid_items_query = Marketplace::with([
                "page.area.city",
                "productCategories",
                "page.categories",
                "getCurrency",
            ])
                ->withAvg(
                    [
                        "reviews as avg_rating" => function ($q) {
                            $q->where("type", "product");
                        },
                    ],
                    "rating"
                )
                ->join("pages", "marketplaces.page_id", "=", "pages.id")
                ->where("marketplaces.product_status", 2)
                ->where("pages.city_id", $city->id)
                ->where("pages.area_id", $area->id)
                ->select("marketplaces.*");

            // Apply filters
            if (
                $request->filled("category_filter") &&
                $request->category_filter != "0"
            ) {
                $paid_items_query->join("category_product as cp_area", "marketplaces.id", "=", "cp_area.product_id")
                    ->where("cp_area.product_category_id", $request->category_filter);
            }

            if ($request->filled("search_filter")) {
                $search = $request->search_filter;
                $paid_items_query->where(function ($query) use ($search) {
                    $query
                        ->where("title", "like", "%" . $search . "%")
                        ->orWhere("description", "like", "%" . $search . "%")
                        ->orWhereHas("productCategories", function ($q) use ($search) {
                            $q->where(
                                "product_category_name",
                                "like",
                                "%" . $search . "%"
                            );
                        });
                });
            }

            $paid_items_query->where(function ($query) use ($city, $area) {
                $query
                    ->whereHas("page.user.userSubscriptions", function ($q) use ($city, $area) {
                        $q->where("status", "active")
                            ->where("expires_at", ">=", now())
                            ->whereHas("subscription", function ($subQ) use ($city, $area) {
                                $subQ
                                    ->where(function ($sq) {
                                        $sq->where(
                                            "offered_services",
                                            "like",
                                            "%marketplace%"
                                        )->orWhereNull("offered_services");
                                    })
                                    ->where(function ($sq) use ($city, $area) {
                                        $sq->whereNull("area_durations")
                                            ->orWhereRaw(
                                                "EXISTS(SELECT 1 FROM jsonb_each_text(area_durations::jsonb->'marketplace') WHERE value = CAST(? AS TEXT))",
                                                [$city->id]
                                            )
                                            ->orWhereRaw(
                                                "EXISTS(SELECT 1 FROM jsonb_each_text(area_durations::jsonb->'marketplace') WHERE value = CAST(? AS TEXT))",
                                                [$area->id]
                                            );
                                    });
                            });
                    })
                    ->orWhereDoesntHave("page.user.userSubscriptions");
            });

            $paid_items_query->orderByDesc("marketplaces.item_featured"); 
            $filter_sort_by = $request->input("filter_sort_by", "newest");
            $page_data["filter_sort_by"] = $filter_sort_by;

            if ($filter_sort_by === "oldest") {
                $paid_items_query->orderBy("marketplaces.created_at", "ASC");
            } else {
                $paid_items_query->orderBy("marketplaces.created_at", "DESC");
            }

            $paid_items = $paid_items_query
                ->orderBy("marketplaces.id", "DESC")
                ->paginate(50);

            $paid_items->appends($request->all());
            $page_data["products"] = $paid_items;
            $page_data["total_products"] = $paid_items->total();

            $page_data["view_path"] = "frontend.marketplace.productarea";
            return view("frontend.product_area_filter_index", $page_data);
        } else {
            abort(404);
        }
    }

    public function productcity(Request $request, $city_slug)
    {
        $city = Cache::remember("city_slug_{$city_slug}", 3600, function () use ($city_slug) {
            return DB::table("cities")->where("city_slug", $city_slug)->first();
        });
        abort_if(!$city, 404);

        $page_data["city"] = $city;

        $request->merge(['city_filter' => $city->id]);
        $page_data = array_merge($page_data, $this->getMarketFilterData($request, null));

        $page_data["all_product_cities"] = $page_data['all_cities'];
        $page_data["all_product_areas"]  = $page_data['filter_areas'];
        $page_data["all_printable_categories"] = $page_data["sidebar_product_categories"];

        SEOMeta::setTitle("Top deals, offers, discount in " . $city->city_name);
        SEOMeta::setDescription("Top deals, offers, discount in " . $city->city_name . " best product, services");
        SEOMeta::setKeywords([
            "best deals today in " . $city->city_name,
            "latest discounts in " . $city->city_name,
            "exclusive offers in " . $city->city_name,
            "top deals online " . $city->city_name,
            "daily deals and discounts in " . $city->city_name,
            "limited-time offers in " . $city->city_name,
            "hot deals and promotions " . $city->city_name,
            "flash sale deals " . $city->city_name,
            "best savings online " . $city->city_name,
            "seasonal discount deals " . $city->city_name,
        ]);

        SEOMeta::setCanonical(URL::current());

        $paid_items_query = Marketplace::with([
                "page.area.city",
                "productCategories",
                "page.categories",
                "getCurrency",
            ])
            ->join("pages", "marketplaces.page_id", "=", "pages.id")
            ->where("marketplaces.product_status", 2)
            ->where("pages.city_id", $city->id)
            ->select("marketplaces.*")
            ->withAvg(["reviews as avg_rating" => fn($q) => $q->where("type", "product")], "rating");

        // Apply filters
        $filter_city = $request->input('city', $city->id);
        $filter_area = $request->input('area');
        $filter_category = $request->input('category');
        $filter_sort_by = $request->input("filter_sort_by", "newest");

        if (!empty($filter_area) && $filter_area != "0") {
            $paid_items_query->where("pages.area_id", $filter_area);
        }

        if (!empty($filter_category) && $filter_category != "0") {
            $paid_items_query->join("category_product as cp_filter", "marketplaces.id", "=", "cp_filter.product_id")
                ->where("cp_filter.product_category_id", $filter_category);
        }

        if ($request->filled("search_filter")) {
            $search = $request->search_filter;
            $paid_items_query->where(function ($query) use ($search) {
                $query->where("marketplaces.title", "like", "%" . $search . "%")
                    ->orWhere("marketplaces.description", "like", "%" . $search . "%");
            });
        }

        $paid_items_query->orderBy("marketplaces.id", "DESC");
        $paid_items_query->orderByDesc("marketplaces.item_featured");

        if ($filter_sort_by === "oldest") {
            $paid_items_query->orderBy("marketplaces.created_at", "ASC");
        } elseif ($filter_sort_by === "highest-rated") {
            $paid_items_query->orderByDesc("avg_rating");
        } elseif ($filter_sort_by === "lowest-rated") {
            $paid_items_query->orderBy("avg_rating", "ASC");
        } else {
            $paid_items_query->orderBy("marketplaces.created_at", "DESC");
        }

        $paid_items = $paid_items_query->distinct("marketplaces.id")->paginate(50);
        $paid_items->appends($request->all());

        $page_data["products"] = $paid_items;
        $page_data["total_products"] = $paid_items->total();

        $page_data["view_path"] = "frontend.marketplace.productcity";
        return view("frontend.product_city_filter_index", $page_data);
    }

    public function productcategorycity(
        Request $request,
        $category_slug,
        $city_slug
    ) {
        $category = Category::where(
            "product_category_slug",
            $category_slug
        )->first();
        abort_if(!$category, 404);

        $city = DB::table("cities")
            ->select("cities.*")
            ->where("city_slug", $city_slug)
            ->first();
        abort_if(!$city, 404);

        $page_data["city"] = $city;
        $page_data["category"] = $category;
        
        $request->merge(['city_filter' => $city->id]);
        $page_data = array_merge($page_data, $this->getMarketFilterData($request, $category->id));

        $page_data['market_categories'] = Cache::remember("market_cats_city_{$city->id}_cat_{$category->id}", 3600, function () use ($city, $category) {
            return DB::table('categories')->select('categories.id', 'categories.product_category_name', 'categories.product_category_slug')
                ->join('category_product', 'category_product.product_category_id', '=', 'categories.id')
                ->join('marketplaces', 'marketplaces.id', '=', 'category_product.product_id')
                ->join('pages', 'marketplaces.page_id', '=', 'pages.id')
                ->where('pages.city_id', $city->id)
                ->where('marketplaces.product_status', 2)
                ->where(function ($query) use ($category) {
                     $query->where('category_product.product_category_id', $category->id)
                           ->orWhere('categories.category_parent_id', $category->id);
                })
                ->distinct()
                ->orderBy('categories.id', 'DESC')
                ->get();
        });

        // Calculate parent categories to avoid undefined variable error in view breadcrumbs
        $parent_categories = [];
        $current = $category;
        while ($current && $current->category_parent_id > 0) {
            $parent = Category::find($current->category_parent_id);
            if ($parent) {
                $parent_categories[] = $parent;
                $current = $parent;
            } else {
                break;
            }
        }
        $page_data["parent_categories"] = array_reverse($parent_categories);

        if (!is_null($category) && !is_null($city)) {
            SEOMeta::setTitle(
                "Best " .
                $category->product_category_name .
                " Deals in " .
                $city->city_name
            );
            SEOMeta::setDescription(
                "Get best" .
                " " .
                $category->product_category_name .
                " deals in" .
                " " .
                $city->city_name
            );

            SEOMeta::setCanonical(URL::current());

            $paid_items_query = Marketplace::with([
                    "page.area.city",
                    "productCategories",
                    "page.categories",
                    "getCurrency",
                ])
                ->withAvg(["reviews as avg_rating" => fn($q) => $q->where("type", "product")], "rating")
                ->join("pages", "marketplaces.page_id", "=", "pages.id")
                ->join("category_product", "marketplaces.id", "=", "category_product.product_id")
                ->join("categories as cat_filter", "cat_filter.id", "=", "category_product.product_category_id")
                ->where("marketplaces.product_status", 2)
                ->where("pages.city_id", $city->id)
                ->where(function ($q) use ($category) {
                    $q->where("category_product.product_category_id", $category->id)
                      ->orWhere("cat_filter.category_parent_id", $category->id);
                })
                ->select("marketplaces.*")
                ->orderByDesc("marketplaces.item_featured")
                ->orderByDesc("marketplaces.id");
            
            $filter_sort_by = $request->input("filter_sort_by", "newest");
            $page_data["filter_sort_by"] = $filter_sort_by;

            if ($filter_sort_by === "newest") {
                $paid_items_query->orderBy("marketplaces.created_at", "DESC");
            } elseif ($filter_sort_by === "oldest") {
                $paid_items_query->orderBy("marketplaces.created_at", "ASC");
            }
            $paid_items = $paid_items_query->paginate(50);
            $paid_items->appends($request->all());

            $page_data["products"] = $paid_items;

            $page_data["view_path"] = "frontend.marketplace.productcategorycity";
            return view("frontend.product_filter_index", $page_data);
        } else {
            abort(404);
        }
    }

    public function productcategorycityarea(
        Request $request,
        $city_slug,
        $category_slug,
        $area_slug
    ) {
        $category = Category::where(
            "product_category_slug",
            $category_slug
        )->first();
        abort_if(!$category, 404);

        $city = DB::table("cities")
            ->select("cities.*")
            ->where("city_slug", $city_slug)
            ->first();
        abort_if(!$city, 404);

        $area = DB::table("areas")
            ->select("areas.*")
            ->where("area_slug", $area_slug)
            ->where("city_id", $city->id)
            ->first();
        abort_if(!$area, 404);
        
        $page_data["city"] = $city;
        $page_data["area"] = $area;
        $page_data["category"] = $category;
        
        $request->merge(['city_filter' => $city->id, 'area_filter' => $area->id]);
        $page_data = array_merge($page_data, $this->getMarketFilterData($request, $category->id));

        $page_data['market_categories'] = Cache::remember("market_cats_city_{$city->id}_area_{$area->id}_cat_{$category->id}", 3600, function () use ($city, $area, $category) {
            return DB::table('categories')->select('categories.id', 'categories.product_category_name', 'categories.product_category_slug')
                ->join('category_product', 'category_product.product_category_id', '=', 'categories.id')
                ->join('marketplaces', 'marketplaces.id', '=', 'category_product.product_id')
                ->join('pages', 'marketplaces.page_id', '=', 'pages.id')
                ->where('pages.city_id', $city->id)
                ->where('pages.area_id', $area->id)
                ->where('marketplaces.product_status', 2)
                ->where(function ($query) use ($category) {
                     $query->where('category_product.product_category_id', $category->id)
                           ->orWhere('categories.category_parent_id', $category->id);
                })
                ->distinct()
                ->orderBy('categories.id', 'DESC')
                ->get();
        });

        // Calculate parent categories to avoid undefined variable error in view breadcrumbs
        $parent_categories = [];
        $current = $category;
        while ($current && $current->category_parent_id > 0) {
            $parent = Category::find($current->category_parent_id);
            if ($parent) {
                $parent_categories[] = $parent;
                $current = $parent;
            } else {
                break;
            }
        }
        $page_data["parent_categories"] = array_reverse($parent_categories);

        SEOMeta::setTitle(
            "Best " . $category->product_category_name . " Deals in " . $area->area_name . " - " . $city->city_name
        );
        SEOMeta::setDescription(
            "Get best " . $category->product_category_name . " deals in " . $area->area_name . " - " . $city->city_name
        );
        SEOMeta::setCanonical(URL::current());

        $paid_items_query = Marketplace::with([
                "page.area.city",
                "productCategories",
                "page.categories",
                "getCurrency",
            ])
            ->withAvg(["reviews as avg_rating" => fn($q) => $q->where("type", "product")], "rating")
            ->join("pages", "marketplaces.page_id", "=", "pages.id")
            ->join("category_product", "marketplaces.id", "=", "category_product.product_id")
            ->join("categories as cat_f", "cat_f.id", "=", "category_product.product_category_id")
            ->where("pages.city_id", $city->id)
            ->where("pages.area_id", $area->id)
            ->where("marketplaces.product_status", 2)
            ->where(function ($q) use ($category) {
                $q->where("category_product.product_category_id", $category->id)
                  ->orWhere("cat_f.category_parent_id", $category->id);
            })
            ->select("marketplaces.*")
            ->orderByDesc("marketplaces.item_featured")
            ->orderByDesc("marketplaces.id");
            
        $filter_sort_by = $request->input("filter_sort_by", "newest");
        $page_data["filter_sort_by"] = $filter_sort_by;

        if ($filter_sort_by === "newest") {
            $paid_items_query->orderBy("marketplaces.created_at", "DESC");
        } elseif ($filter_sort_by === "oldest") {
            $paid_items_query->orderBy("marketplaces.created_at", "ASC");
        }
        $paid_items = $paid_items_query->paginate(50);
        $paid_items->appends($request->all());

        $page_data["products"] = $paid_items;
        $page_data["total_products"] = $paid_items->total(); 

        $page_data["view_path"] = "frontend.marketplace.productcategorycityarea";
        return view("frontend.product_category_city_area_index", $page_data);
    }

    public function jsonGetAreasByCityforproduct(int $city_id)
    {
        $areas = DB::table("areas")
            ->select("areas.*")
            ->join("cities", "cities.id", "areas.city_id")
            ->join("pages", "pages.area_id", "areas.id")
            ->join("marketplaces", "marketplaces.page_id", "pages.id")
            ->join(
                "category_product",
                "marketplaces.id",
                "category_product.product_id"
            )
            ->join(
                "categories",
                "category_product.product_category_id",
                "=",
                "categories.id"
            )
            ->distinct("areas.id")
            ->orderBy("areas.id", "ASC")
            ->where("marketplaces.product_status", 2)
            ->where("areas.city_id", $city_id)
            ->where("pages.city_id", $city_id)
            ->get();

        return response()->json($areas);
    }

    public function searchPages(Request $request)
    {
        $searchTerm = $request->input("q"); 

        $pages = DB::table("pages")
            ->select("pages.id", "pages.title")
            ->join("page_category", "page_category.page_id", "pages.id")
            ->where("pages.item_status", 2)
            ->where("pages.user_id", auth()->user()->id)
            ->where("pages.title", "like", "%" . $searchTerm . "%") 
            ->distinct("pages.id")
            ->orderBy("pages.id", "DESC")
            ->get();

        return response()->json($pages);
    }

    public function create()
    {
        $page_data["all_cities"] = CityHelper::getActiveCities();
        $page_data["listing"] = [];

        SEOMeta::setTitle(
            "Free Local Business Product & Service Listing | CityHangaround"
        );
        SEOMeta::setDescription(
            "Promote your local business by listing products and services for free on CityHangaround. Connect with nearby customers, increase visibility, and grow your presence in your city today!"
        );
        SEOMeta::setCanonical(URL::current());

        $page_data["parent"] = DB::table("categories")
            ->where("categories.category_parent_id", 0)
            ->get();
        $page_data["view_path"] = "frontend.marketplace.create_product";
        return view("frontend.form_index", $page_data);
    }

    public function createCategoryFromSelect2(Request $request)
    {
        $duplicateCount = DB::table("categories")
            ->where("product_category_name", $request->category_name)
            ->count();

        if ($duplicateCount === 0) {
            $category = new Category();

            $category->product_category_name = $request->category_name;
            $category->product_category_slug = clean_slug(
                $request->category_name
            );
            $category->category_parent_id = 0; 
            $category->product_category_description = "";
            $category->product_category_createdby = auth()->user()->id;

            $category->save();

            if (auth()->user()) {
                app(UserActivityService::class)->log(
                    auth()->user()->id,
                    "category_suggest",
                    "market_category",
                    $category->id,
                    $category->id
                );
            }

            return response()->json([
                "id" => $category->id,
                "product_category_name" => $category->product_category_name,
            ]);
        } else {
            $existing = DB::table("categories")
                ->where("product_category_name", $request->category_name)
                ->first();

            return response()->json([
                "id" => $existing->id,
                "product_category_name" => $existing->product_category_name,
                "duplicate" => true,
            ]);
        }
    }

    public function edit(Request $request)
    {
        $page_data["all_cities"] = CityHelper::getActiveCities();
        $page_data["listing"] = [];

        $page_data["parent"] = DB::table("categories")
            ->where("categories.category_parent_id", 0)
            ->get();
        $page_data["product_id"] = $request->product_id;
        $page_data["view_path"] = "frontend.marketplace.edit_product";
        return view("frontend.index", $page_data);
    }

    public function jsonGetParentCategories()
    {
        $parents = DB::table("categories")
            ->select(
                "categories.id",
                "categories.product_category_name",
                "cat.product_category_name as parent"
            )
            ->leftjoin(
                "categories as cat",
                "cat.id",
                "=",
                "categories.category_parent_id"
            )
            ->orderby("id", "asc")
            ->get()
            ->toJson();

        return response()->json($parents);
    }

    public function jsonGetproductbrand()
    {
        $brands = DB::table("brands")
            ->select("brands.id", "brands.name")
            ->orderby("name", "asc")
            ->get()
            ->toJson();

        return response()->json($brands);
    }

    public function storeparentcategories(Request $request)
    {
        $duplicatecount = DB::table("categories")
            ->where("product_category_name", $request->category_name)
            ->count();

        if ($duplicatecount == 0) {
            $category = new Category();

            $category->product_category_name = $request->category_name;
            $category->product_category_slug = clean_slug(
                $request->category_name
            );
            $category->category_parent_id = 0;
            $category->product_category_description = "";
            $category->product_category_createdby = auth()->user()->id;

            $category->save();

            \Session::flash("flash_message", __("Created"));
            \Session::flash("flash_type", "success");
            return response()->json(1);
        } else {
            return response()->json("duplicate");
        }
    }

    public function storebrand(Request $request)
    {
        $duplicatecount = DB::table("brands")
            ->where("name", $request->brandname)
            ->count();

        if ($duplicatecount == 0) {
            $brand = new Brand();

            $brand->name = $request->brandname;
            $brand->created_at = strtotime(date("d M, Y"));
            $brand->updated_at = strtotime(date("d M, Y"));

            $brand->save();

            \Session::flash("flash_message", __("Created"));
            \Session::flash("flash_type", "success");
            return response()->json(1);
        } else {
            return response()->json("duplicate");
        }
    }

    public function storecategories(Request $request)
    {
        $duplicatecount = DB::table("categories")
            ->where("product_category_name", $request->category_name)
            ->count();

        if ($duplicatecount == 0) {
            $category = new Category();

            $category->product_category_name = $request->category_name;
            $category->product_category_slug = strtolower(
                str_replace(" ", "-", $request->category_name)
            );
            $category->category_parent_id = $request->category_parent_id;
            $category->product_category_description = "";
            $category->product_category_createdby = auth()->user()->id;

            $category->save();

            if (auth()->user()) {
                app(UserActivityService::class)->log(
                    auth()->user()->id,
                    "category_suggest",
                    "market_category",
                    $category->id,
                    $category->id
                );
            }

            \Session::flash("flash_message", __("Created"));
            \Session::flash("flash_type", "success");
            return response()->json(1);
        } else {
            return response()->json("duplicate");
        }
    }

    public function dataAjax(Request $request)
    {
        $data = [];
        if ($request->has("q")) {
            $search = $request->q;
            $query = DB::table("categories")
                ->select("id", "product_category_name")
                ->where("product_category_name", "LIKE", "$search%")
                ->where("category_parent_id", "!=", 0);

            if ($request->has("parent_id") && !empty($request->parent_id) && $request->parent_id != '0') {
                $query->where("category_parent_id", $request->parent_id);
            }

            $data = $query->get();
        }
        return response()->json($data);
    }

    public function userproduct()
    {
        $page_data["all_cities"] = CityHelper::getActiveCities();

        $products_query = Marketplace::with([
            "page.area.city",
            "reviews",
            "productCategories",
            "page.categories",
            "getCurrency",
        ])
            ->withAvg(
                [
                    "reviews as avg_rating" => function ($q) {
                        $q->where("type", "product");
                    },
                ],
                "rating"
            )
            ->where("user_id", auth()->user()->id)
            ->where("product_status", 2)
            ->orderBy("id", "DESC");

        $products = $products_query->get();

        $page_data["products"] = $products;
        $page_data["view_path"] = "frontend.marketplace.user_products";

        return view("frontend.index", $page_data);
    }

    public function getSuggestions(Request $request)
    {
        $query = $request->input("query");
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }
        $products = Marketplace::where("title", "LIKE", "%$query%")
            ->limit(10)
            ->get([
                "id",
                "title",
            ]);
        return response()->json($products);
    }

    public function getDetails(Request $request)
    {
        $product = Marketplace::findOrFail($request->id);
        return response()->json($product->append('category_objects'));
    }

    public function getCategoryNames(Request $request)
    {
        if (!$request->has("ids")) {
            return response()->json(
                ["error" => "No category IDs provided"],
                400
            );
        }

        $categoryIds = explode(",", $request->ids); 
        $categories = Category::whereIn("id", $categoryIds)->get([
            "id",
            "product_category_name",
        ]);

        return response()->json($categories);
    }

    public function jsonGetProductAreasByCity(int $city_id)
    {
        $areas = \App\Models\Area::where("city_id", $city_id)
            ->select("id", "area_name")
            ->whereIn("id", function($query) use ($city_id) {
                $query->select("pages.area_id")
                      ->from("pages")
                      ->join("marketplaces", "marketplaces.page_id", "=", "pages.id")
                      ->where("pages.city_id", $city_id)
                      ->whereNotNull("pages.area_id")
                      ->distinct();
            })
            ->get()
            ->toJson();

        return response()->json($areas);
    }
    
    public function store(Request $request)
    {
        $rules = [
            "producttype" => "required|max:255",
            "productnaturetype" => "required",
            "parent" => "required",
            "category" => "required",
            "title" => "required|max:255",
            "brand" => "required",
            "page_id" => "nullable",
            "featured_video" => "nullable|mimes:mp4,avi,mkv,flv,webm|max:512000",
        ];
        $attributes = ['page_id' => 'Listing Page'];
        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product_slug = preg_replace("/[^A-Za-z0-9 ]/", "", $request->title);
        $multiSelectArray = (array)$request->category;
        if (!in_array($request->parent, $multiSelectArray)) {
            $multiSelectArray[] = $request->parent;
        }

        $categories_id = implode(",", $multiSelectArray);
        $resolvedPageId = $request->page_id;
        if (empty($resolvedPageId) && auth()->check()) {
            $resolvedPageId = DB::table("pages")->where("user_id", auth()->user()->id)->orderBy("id", "desc")->value("id");
        }
        
        if (empty($resolvedPageId)) {
            $errorMessage = "Please select a Listing Page.";
            if ($request->ajax()) return response()->json(["status" => "error", "errors" => [$errorMessage]], 422);
            return redirect()->back()->withErrors(["page_id" => $errorMessage])->withInput();
        }

        $approval = ManageApproval::where("title", "marketpalce")->first();
        $product_status = ($approval && $approval->status == 1) || (auth()->check() && auth()->user()->user_role == "admin") ? 2 : 1;

        $marketplace = new Marketplace();
        $marketplace->product_type = $request->producttype;
        $marketplace->product_status = $product_status;
        $marketplace->product_nature_type = $request->productnaturetype;
        $marketplace->user_id = auth()->user()->id;
        $marketplace->title = $request->title;
        $marketplace->product_slug = Str::slug($product_slug);
        $marketplace->brand = $request->brand;
        $marketplace->category = $categories_id;
        $marketplace->currency_id = $request->currency;
        $marketplace->page_id = $resolvedPageId;
        $marketplace->product_original_price = $request->price;
        $marketplace->product_selling_price = $request->selling_price;
        $marketplace->video_url = $request->video_url;
        $marketplace->product_featured_service = $request->featured;
        $marketplace->startdate = $request->start_date;
        $marketplace->enddate = $request->end_date;
        $marketplace->location = $request->location;
        $marketplace->condition = $request->condition;
        $marketplace->status = $request->status;
        $marketplace->buy_link = $request->buy_link;
        $marketplace->description = $request->description;

        if ($request->hasFile("featured_video")) {
            $marketplace->featured_video = FileUploader::upload($request->featured_video, "public/storage/pages/logo", 512000);
        }

        $user = auth()->user();
        $activeSubscription = $user->activeSubscription()->with("subscription")->first();
        if ($activeSubscription && $activeSubscription->subscription && Str::contains($activeSubscription->subscription->offered_services, "marketplace")) {
            $durations = json_decode($activeSubscription->subscription->area_durations, true);
            $cityDays = $durations["marketplace"]["city"] ?? 0;
            $areaDays = $durations["marketplace"]["area"] ?? 0;
            $subscriptionStart = Carbon::parse($activeSubscription->created_at ?? now());

            if ($cityDays > 0) $marketplace->priority_until_city = $subscriptionStart->copy()->addDays($cityDays);
            if ($areaDays > 0) $marketplace->priority_until_area = $subscriptionStart->copy()->addDays($areaDays);
            if ($subscriptionStart->copy()->addDays(max($cityDays, $areaDays))->isFuture()) $marketplace->item_featured = 1;
        }

        DB::transaction(function () use ($marketplace, $multiSelectArray, $product_slug, $request) {
            $marketplace->save();
            $product_id = $marketplace->id;

            $marketplace->categories()->sync($multiSelectArray);

            $slug = Str::slug($product_slug);
            if (DB::table("marketplaces")->where("product_slug", $slug)->count() > 1) {
                $marketplace->update(["product_slug" => $slug . "-" . $product_id]);
            } else {
                $marketplace->update(["product_slug" => $slug]);
            }

            if (is_array($request->multiple_files) && $request->multiple_files[0] != null) {
                foreach ($request->multiple_files as $key => $media_file) {
                    $file_name = FileUploader::upload($media_file, "public/storage/marketplace/thumbnail", 315);
                    FileUploader::upload($media_file, "public/storage/marketplace/coverphoto/" . $file_name, 315);
                    Media_files::create(["user_id" => auth()->user()->id, "product_id" => $product_id, "file_name" => $file_name, "file_type" => "image", "created_at" => time(), "updated_at" => time()]);
                    if ($key == 0) {
                        $marketplace->update(["image" => $file_name]);
                    }
                }
            }
        });

        $product_id = $marketplace->id;

        app(UserActivityService::class)->log(auth()->user()->id, "marketplace_listing", "product", $product_id, $product_id);
        Mail::to($user->email)->queue(new ProductMail($user));

        if ($request->ajax()) return response()->json(['status' => 'success', 'redirect_url' => route("userproduct")]);
        return redirect()->route("userproduct");
    }

    public function update(Request $request, $id)
    {
        $rules = [
            "producttype" => "required|max:255",
            "productnaturetype" => "required",
            "parent" => "required",
            "category" => "required",
            "title" => "required|max:255",
            "brand" => "required",
            "page_id" => "required",
        ];
        $attributes = ['page_id' => 'Listing Page'];
        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            if ($request->ajax()) return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()], 422);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $marketplace = Marketplace::find($id);
        $product_slug = preg_replace("/[^A-Za-z0-9 ]/", "", $request->title);
        $multiSelectArray = (array)$request->category;
        if (!in_array($request->parent, $multiSelectArray)) $multiSelectArray[] = $request->parent;

        $approval = ManageApproval::where("title", "marketpalce")->first();
        $product_status = ($approval && $approval->status == 1) || (auth()->check() && auth()->user()->user_role == "admin") ? 2 : $marketplace->product_status;

        DB::transaction(function () use ($marketplace, $multiSelectArray, $product_slug, $product_status, $request, $id) {
            $marketplace->update([
                "product_type" => $request->producttype,
                "product_status" => $product_status,
                "product_nature_type" => $request->productnaturetype,
                "title" => $request->title,
                "product_slug" => Str::slug($product_slug),
                "brand" => $request->brand,
                "category" => implode(",", $multiSelectArray),
                "currency_id" => $request->currency,
                "page_id" => $request->page_id,
                "product_original_price" => $request->price,
                "product_selling_price" => $request->selling_price,
                "video_url" => $request->video_url,
                "product_featured_service" => $request->featured,
                "startdate" => $request->start_date,
                "enddate" => $request->end_date,
                "location" => $request->location,
                "condition" => $request->condition,
                "status" => $request->status,
                "buy_link" => $request->buy_link,
                "description" => $request->description,
            ]);

            if ($request->hasFile("featured_video")) {
                $marketplace->update(["featured_video" => FileUploader::upload($request->featured_video, "public/storage/pages/logo", 512000)]);
            }

            // Sync categories (automatically removes deselected categories)
            $marketplace->categories()->sync($multiSelectArray);

            if (is_array($request->multiple_files) && $request->multiple_files[0] != null) {
                $previousfiles = Media_files::where("product_id", $id)->get();
                foreach ($previousfiles as $prev) {
                    removeFile("marketplace", $prev->file_name);
                    $prev->delete();
                }

                foreach ($request->multiple_files as $key => $media_file) {
                    $file_name = FileUploader::upload($media_file, "public/storage/marketplace/thumbnail", 315);
                    FileUploader::upload($media_file, "public/storage/marketplace/coverphoto/" . $file_name, 315);
                    Media_files::create(["user_id" => auth()->user()->id, "product_id" => $id, "file_name" => $file_name, "file_type" => "image", "created_at" => time(), "updated_at" => time()]);
                    if ($key == 0) $marketplace->update(["image" => $file_name]);
                }
            }
        });

        Session::flash("success_message", get_phrase("Marketplace Product Updated Successfully"));
        return json_encode(["reload" => 1]);
    }

    public function product_delete()
    {
        $market = Marketplace::find($_GET["product_id"]);
        $imagename = $market->image;
        if ($market->delete()) {
            removeFile("marketplace", $imagename);
            return json_encode(["alertMessage" => get_phrase("Product Deleted Successfully"), "fadeOutElem" => "#product-" . $_GET["product_id"]]);
        }
    }

    public function load_product_by_scrolling(Request $request)
    {
        $page_data["products"] = Marketplace::orderBy("id", "DESC")->skip($request->offset)->take(6)->get();
        return view("frontend.marketplace.product-single", $page_data);
    }

    public function single_product($city_slug, $area_slug, $category_slug, $item_slug, $product_category_slug, $product_slug)
    {
        $page_data["all_cities"] = CityHelper::getActiveCities();
        $category = Category::where("product_category_slug", $product_category_slug)->first();
        abort_if(!$category, 404);

        $city = DB::table("cities")->where("city_slug", $city_slug)->first();
        abort_if(!$city, 404);

        $product = Marketplace::where("product_slug", $product_slug)
            ->where("product_status", 2)
            ->whereHas("page")
            ->with(["page.city", "page.area", "page.categories", "productCategories"])
            ->first();

        abort_if(!$product, 404, "Product not found");

        if (auth()->user()) {
            app(UserActivityService::class)->log(auth()->user()->id, "view", "product", $product->id, $product->id);
        }

        $metaTitle = "{$product->title} Deal " . ($product->productCategories->last()?->product_category_name ?? "") . " " . trim(($product->page->area?->area_name ?? "") . "," . ($product->page->city?->city_name ?? "")) . " - " . ($product->page->title ?? "");
        SEOMeta::setTitle($metaTitle);
        SEOMeta::setDescription("{$product->title} deal at Rs {$product->product_selling_price} " . ($product->productCategories->last()?->product_category_name ?? "") . " " . trim(($product->page->area?->area_name ?? "") . " " . str_replace(["(", ")"], "", $product->page->city?->city_name ?? "")) . " {$product->page->title} - Brand - " . ($product->getBrand?->name ?? ""));
        SEOMeta::setKeywords(["local deals", "discounts", "offers", "best deals near me", "city discounts", "shopping deals", "restaurant deals", "entertainment offers", "local savings"]);

        $product_view_data = $product->view ? json_decode($product->view, true) : [];
        if (auth()->user() && !in_array(auth()->user()->id, $product_view_data)) {
            $product_view_data[] = auth()->user()->id;
            $product->view = json_encode($product_view_data);
            $product->save();
        }

        $page_data["product"] = $product;
        $page_data["reviews"] = Review::where("marketplace_id", $product->id)->with("user")->where("type", "product")->latest()->take(5)->get();
        $page_data["has_more_reviews"] = Review::where("marketplace_id", $product->id)->where("type", "product")->count() > 5;
        $page_data["related_product"] = Cache::remember("product_related_v5_{$product->id}_{$product->page->city_id}_{$product->page->area_id}", 7200, function () use ($product) {
            $relatedQuery = Marketplace::where("product_status", 2)
                ->whereHas("page")
                ->whereHas("page", fn($query) => $query->where("city_id", $product->page->city_id))
                ->where(fn($q) => $q->where("brand", $product->brand)->orWhere("category", $product->category))
                ->with(["page.city", "page.area", "page.categories", "productCategories", "getCurrency"])
                ->withAvg(["reviews as avg_rating" => fn($q) => $q->where("type", "product")], "rating");

            if ($product->page->area_id) {
                $areaProducts = (clone $relatedQuery)
                    ->whereHas("page", fn($query) => $query->where("area_id", $product->page->area_id))
                    ->limit(6)
                    ->get();

                if ($areaProducts->isNotEmpty()) {
                    return $areaProducts;
                }
            }

            return $relatedQuery->limit(6)->get();
        });
        $page_data["product_image"] = Media_files::where("product_id", $product->id)->where("file_type", "image")->get();
        
        // Pass city, category and fetch sidebar data for the 3-column filter layout compatibility
        $page_data['category'] = $category;
        $page_data['city'] = $city;
        $page_data['area'] = $product->page->area;
        $page_data['is_single_product'] = true;

        $request = request();
        $page_data = array_merge($page_data, $this->getMarketFilterData($request, $category->id));

        $page_data['market_categories'] = Cache::remember("market_cats_city_{$city->id}_cat_{$category->id}", 3600, function () use ($city, $category) {
            return DB::table('categories')->select('categories.id', 'categories.product_category_name', 'categories.product_category_slug')
                ->join('category_product', 'category_product.product_category_id', '=', 'categories.id')
                ->join('marketplaces', 'marketplaces.id', '=', 'category_product.product_id')
                ->join('pages', 'marketplaces.page_id', '=', 'pages.id')
                ->where('pages.city_id', $city->id)
                ->where('marketplaces.product_status', 2)
                ->where(function ($query) use ($category) {
                     $query->where('category_product.product_category_id', $category->id)
                           ->orWhere('categories.category_parent_id', $category->id);
                })
                ->distinct()
                ->orderBy('categories.id', 'DESC')
                ->get();
        });

        // Skip the heavy active products pagination query since $products is unused on the single product details page
        $page_data["filter_sort_by"] = $request->input("filter_sort_by", "newest");
        $page_data["products"] = collect();

        $page_data["view_path"] = "frontend.marketplace.single_product";
        return view("frontend.product_filter_index", $page_data);
    }

    public function filter(Request $request)
    {
        $search = $request->search;
        $page_data["all_cities"] = CityHelper::getActiveCities();
        $query = Marketplace::where("status", 1);

        if (!empty($search)) {
            $query->where(fn($q) => $q->where("title", "like", "%$search%")->orWhere("description", "like", "%$search%"));
        }

        $page_data["products"] = $query->paginate(12);
        $page_data["view_path"] = "frontend.marketplace.products";
        return view("frontend.index", $page_data);
    }

    public function saved_product()
    {
        $page_data["all_cities"] = CityHelper::getActiveCities();
        $page_data["saved_products"] = SavedProduct::with(["productData.getUser", "productData.page.area.city", "productData.page.categories", "productData.productCategories"])->where("user_id", auth()->id())->get();
        $page_data["view_path"] = "frontend.marketplace.saved_product";
        return view("frontend.index", $page_data);
    }

    public function save_for_later($id)
    {
        SavedProduct::create(["user_id" => auth()->id(), "product_id" => $id]);
        Session::flash("success_message", get_phrase("Saved Successfully"));
        return json_encode(["reload" => 1]);
    }

    public function unsave_for_later($id)
    {
        if (SavedProduct::where("product_id", $id)->where("user_id", auth()->id())->delete()) {
            Session::flash("success_message", get_phrase("Unsaved Successfully"));
            return json_encode(["reload" => 1]);
        }
    }

    public function single_product_ifrane($id)
    {
        $product = Marketplace::find($id);
        $page_data["product"] = $product;
        $page_data["product_image"] = Media_files::where("product_id", $id)->where("file_type", "image")->get();
        return view("frontend.marketplace.single_product_iframe", $page_data);
    }

    public function checkIfSubcategory(Request $request)
    {
        $category = Category::find($request->id);
        if (!$category || $category->category_parent_id === null) return response()->json(["is_subcategory" => false]);
        return response()->json(["is_subcategory" => true, "subcategory_id" => $category->id, "subcategory_name" => $category->product_category_name, "parent_id" => $category->category_parent_id]);
    }

    public function autocomplete(Request $request)
    {
        return response()->json(Category::where("product_category_name", "like", $request->q . "%")->orderBy("product_category_name", "asc")->get());
    }

    public function checkIfproductSubcategory(Request $request)
    {
        $category = Category::find($request->id);
        if (!$category) return response()->json(["success" => false, "message" => "Category not found."], 404);
        if ($category->category_parent_id) {
            $parent = Category::find($category->category_parent_id);
            return response()->json(["is_subcategory" => true, "subcategory_id" => $category->id, "subcategory_name" => $category->product_category_name, "parent_id" => $parent?->id, "parent_name" => $parent?->product_category_name]);
        }
        return response()->json(["is_subcategory" => false, "category_id" => $category->id, "category_name" => $category->product_category_name]);
    }

    public function jsonGetSubcategoriesByCategory(Request $request)
    {
        return response()->json(DB::table("categories")->join("category_product", "category_product.product_category_id", "=", "categories.id")->join("marketplaces", "marketplaces.id", "=", "category_product.product_id")->where("categories.category_parent_id", $request->category_id)->where("marketplaces.product_status", 2)->distinct("categories.id")->select("categories.*")->get());
    }
}
