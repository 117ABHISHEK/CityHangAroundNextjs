<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\MarketAggregate;
use Illuminate\Support\Facades\Cache;

class MenuDemoController extends Controller
{

    // ================= SMART CITY SEARCH =================

    public function getSmartCities(Request $request)
    {
        $search = $request->get('term');

        $cities = DB::table('cities as c')
            ->leftJoin('content_master as cm', function ($join) {
                $join->on('cm.city_id', '=', 'c.id')
                    ->where('cm.source_type', '=', 'category_count');
            })
            ->where('c.city_name', 'LIKE', '%' . $search . '%')
            ->where('c.is_approved', 'Y')
            ->where('cm.total_count', '>', 0)
            ->select('c.id', 'c.city_name', 'c.city_slug')
            ->distinct()
            ->limit(10)
            ->get();

        return response()->json($cities);
    }


    // ================= CORE MENU LOGIC (🔥 MAIN FUNCTION) =================

    public function getMenuFromMaster($contentType, $cityId = null)
    {
        $query = DB::table('content_master')
            ->where('source_type', 'category_count')
            ->where('status', $contentType);

        // If cityId is provided, filter by city. Otherwise, aggregate across all cities.
        if ($cityId && $cityId !== 'null' && $cityId !== '') {
            $query->where('city_id', $cityId);
        } else {
            // Aggregate logic for GLOBAL view
            return DB::table('content_master')
                ->where('source_type', 'category_count')
                ->where('status', $contentType)
                ->select(
                    'category_id as subcategory_id',
                    'category_name as name',
                    'parent_category_name as parent',
                    DB::raw('SUM(total_count) as count')
                )
                ->groupBy('category_id', 'category_name', 'parent_category_name')
                ->havingRaw('SUM(total_count) > ?', [0])
                ->orderByDesc('count')
                ->get();
        }

        return $query
            ->where('total_count', '>', 0)
            ->orderByDesc('total_count')
            ->select(
                'parent_category_name as parent',
                'category_name as name',
                'category_id as subcategory_id',
                'total_count as count'
            )
            ->get();
    }


    // ================= VIEW MENU =================

    public function viewMenu(Request $request)
    {
        // $cityId = $request->get('city_id'); // optional
        $cityId = session('selected_city_id');

        $newCityGuide = $this->getMenuFromMaster('listing', $cityId);
        $marketplaceData = $this->getMenuFromMaster('marketplace', $cityId);
        $communityData = $this->getMenuFromMaster('community', $cityId);
        $eventData = $this->getMenuFromMaster('event', $cityId);
        $blogData = $this->getMenuFromMaster('blog', $cityId);

        return view('frontend.menu_demo', compact(
            'newCityGuide',
            'marketplaceData',
            'communityData',
            'eventData',
            'blogData',
            'cityId'
        ));
    }


    // ================= AJAX MENU =================

    public function getAjaxMenu(Request $request)
    {
        $cityId = $request->city_id;
        $cacheKey = "ajax_menu_data_v4_" . ($cityId ?: 'global');

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($cityId) {
            return [
                'city_guide' => $this->getFilteredHtml('listing', $cityId),
                'marketplace' => $this->getFilteredHtml('marketplace', $cityId),
                'community' => $this->getFilteredHtml('community', $cityId),
                'event' => $this->getFilteredHtml('event', $cityId),
                'blog' => $this->getFilteredHtml('blog', $cityId),
            ];
        });

        return response()->json($data);
    }


    // ================= HTML BUILDER =================

    private function getFilteredHtml($type, $cityId = null)
    {
        $items = collect();

        if ($type === 'listing') {
            $query = DB::table('page_category')
                ->join('pages', 'page_category.page_id', '=', 'pages.id')
                ->join('pagecategories', 'page_category.category_id', '=', 'pagecategories.id')
                ->where('pages.item_status', 2);

            if ($cityId && $cityId !== 'null' && $cityId !== '') {
                $query->where('pages.city_id', $cityId);
            }

            $items = $query->select(
                'pagecategories.id as subcategory_id',
                'pagecategories.category_name as subcategory_name',
                DB::raw('COUNT(*) as total_count')
            )
                ->groupBy('pagecategories.id', 'pagecategories.category_name')
                ->havingRaw('COUNT(*) > ?', [0])
                ->orderBy('total_count', 'desc')
                ->get();

        } elseif ($type === 'marketplace') {
            $query = DB::table('marketplaces')
                ->join('pages', 'marketplaces.page_id', '=', 'pages.id')
                ->join('categories', function($join) {
                    $join->on(DB::raw('COALESCE(NULLIF(TRIM(SPLIT_PART(marketplaces.category, \',\', 1)), \'\'), \'0\')::BIGINT'), '=', 'categories.id');
                })
                ->where('marketplaces.product_status', 2)
                ->where('pages.item_status', 2);

            if ($cityId && $cityId !== 'null' && $cityId !== '') {
                $query->where('pages.city_id', $cityId);
            }

            $items = $query->select(
                'categories.id as subcategory_id',
                'categories.product_category_name as subcategory_name',
                DB::raw('COUNT(*) as total_count')
            )
                ->groupBy('categories.id', 'categories.product_category_name')
                ->havingRaw('COUNT(*) > ?', [0])
                ->orderBy('total_count', 'desc')
                ->get();

        } elseif ($type === 'community') {
            $query = DB::table('group_category')
                ->join('groups', 'group_category.group_id', '=', 'groups.id')
                ->join('groupcategories', 'group_category.category_id', '=', 'groupcategories.id')
                ->where('groups.group_status', 2)
                ->where('groups.status', 1);

            if ($cityId && $cityId !== 'null' && $cityId !== '') {
                $query->where('groups.city_id', $cityId);
            }

            $items = $query->select(
                'groupcategories.id as subcategory_id',
                'groupcategories.category_name as subcategory_name',
                DB::raw('COUNT(*) as total_count')
            )
                ->groupBy('groupcategories.id', 'groupcategories.category_name')
                ->havingRaw('COUNT(*) > ?', [0])
                ->orderBy('total_count', 'desc')
                ->get();

        } elseif ($type === 'event') {
            $query = DB::table('event_category')
                ->join('events', 'event_category.event_id', '=', 'events.id')
                ->join('eventcategories', 'event_category.category_id', '=', 'eventcategories.id')
                ->where('events.event_status', 2)
                ->where('events.event_date', '>=', DB::raw('CAST(CURRENT_DATE AS TEXT)'));

            if ($cityId && $cityId !== 'null' && $cityId !== '') {
                $query->where('events.city_id', $cityId);
            }

            $items = $query->select(
                'eventcategories.id as subcategory_id',
                'eventcategories.category_name as subcategory_name',
                DB::raw('COUNT(*) as total_count')
            )
                ->groupBy('eventcategories.id', 'eventcategories.category_name')
                ->havingRaw('COUNT(*) > ?', [0])
                ->orderBy('total_count', 'desc')
                ->get();

        } elseif ($type === 'blog') {
            $query = DB::table('blog_category')
                ->join('blogs', 'blog_category.blog_id', '=', 'blogs.id')
                ->join('blogcategories', 'blog_category.category_id', '=', 'blogcategories.id')
                ->leftJoin('pages', 'pages.id', '=', 'blogs.list_id')
                ->where('blogs.blog_status', 2);

            if ($cityId && $cityId !== 'null' && $cityId !== '') {
                $query->where(function ($q) use ($cityId) {
                    $q->where('blogs.city_id', $cityId)
                      ->orWhere('pages.city_id', $cityId);
                });
            }

            $items = $query->select(
                'blogcategories.id as subcategory_id',
                'blogcategories.category_name as subcategory_name',
                DB::raw('COUNT(*) as total_count')
            )
                ->groupBy('blogcategories.id', 'blogcategories.category_name')
                ->havingRaw('COUNT(*) > ?', [0])
                ->orderBy('total_count', 'desc')
                ->get();
        }

        if ($items->isEmpty()) {
            return $this->getCtaColumn($type)
                . '<div class="no-cat-msg">No categories found for this area.</div>';
        }


        // 2. Distribute items into columns using interleaved (row-by-row) distribution
        $columnCount = 4;
        $columns = array_fill(0, $columnCount, []);
        
        foreach ($items->values() as $index => $item) {
            $colIdx = $index % $columnCount;
            $columns[$colIdx][] = $item;
        }

        $citySlug = null;
        if ($cityId && $cityId !== 'null' && $cityId !== '') {
            $city = DB::table('cities')->where('id', $cityId)->first();
            if ($city) {
                $citySlug = $city->city_slug;
            }
        }

        // Load all slugs for the type in a single fast query (or cache) to eliminate N+1 queries in the loop
        $slugs = [];
        if ($type === 'listing') {
            $slugs = Cache::remember("slugs_pagecategories", 3600, function() {
                return DB::table('pagecategories')->pluck('category_slug', 'id')->toArray();
            });
        } elseif ($type === 'marketplace') {
            $slugs = Cache::remember("slugs_categories", 3600, function() {
                return DB::table('categories')->pluck('product_category_slug', 'id')->toArray();
            });
        } elseif ($type === 'community') {
            $slugs = Cache::remember("slugs_groupcategories", 3600, function() {
                return DB::table('groupcategories')->pluck('category_slug', 'id')->toArray();
            });
        } elseif ($type === 'event') {
            $slugs = Cache::remember("slugs_eventcategories", 3600, function() {
                return DB::table('eventcategories')->pluck('category_slug', 'id')->toArray();
            });
        } elseif ($type === 'blog') {
            $slugs = Cache::remember("slugs_blogcategories", 3600, function() {
                return DB::table('blogcategories')->pluck('category_slug', 'id')->toArray();
            });
        }

        $html = '';
        foreach ($columns as $columnItems) {
            if (empty($columnItems)) continue;
            $html .= '<div class="mega-column">';
            foreach ($columnItems as $item) {
                $url = '#';
                $slug = isset($slugs[$item->subcategory_id]) ? $slugs[$item->subcategory_id] : null;
                
                if ($type === 'listing') {
                    if ($citySlug) {
                        $url = url($citySlug . '/' . ($slug ?: $item->subcategory_id));
                    } else {
                        $url = url('search/' . ($slug ?: $item->subcategory_id));
                    }
                } elseif ($type === 'marketplace') {
                    if ($citySlug) {
                        $url = url('deals/' . ($slug ?: $item->subcategory_id) . '-in-' . $citySlug);
                    } else {
                        $url = url('deals/category/' . ($slug ?: $item->subcategory_id));
                    }
                } elseif ($type === 'community') {
                    if ($citySlug) {
                        $url = url('group/' . ($slug ?: $item->subcategory_id) . '-in-' . $citySlug);
                    } else {
                        $url = url('group/category/' . ($slug ?: $item->subcategory_id));
                    }
                } elseif ($type === 'event') {
                    if ($citySlug) {
                        $url = url('event/' . ($slug ?: $item->subcategory_id) . '-in-' . $citySlug);
                    } else {
                        $url = url('event/category/' . ($slug ?: $item->subcategory_id));
                    }
                } elseif ($type === 'blog') {
                    if ($citySlug) {
                        $url = url('blog/' . ($slug ?: $item->subcategory_id) . '-in-' . $citySlug);
                    } else {
                        $url = url('blog/category/' . ($slug ?: $item->subcategory_id));
                    }
                }

                $html .= '<a href="' . $url . '" title="' . $item->total_count . ' items">';
                $html .= $item->subcategory_name;
                $html .= '</a>';
            }
            $html .= '</div>';
        }
        // CTA strip is prepended at top — return it first then the columns
        return $this->getCtaColumn($type) . $html;
    }

    private function getCtaColumn($type)
    {
        $configs = [
            'listing'     => ['label' => 'Add Business',    'url' => '/admin/page/create', 'color' => '#ff4b4b', 'bg' => '#fff5f5'],
            'marketplace' => ['label' => 'Add Deals',        'url' => '/products/create',   'color' => '#5b2ff9', 'bg' => '#f5f2ff'],
            'event'       => ['label' => 'Add Event',        'url' => '/events/create',      'color' => '#0ea5e9', 'bg' => '#f0f9ff'],
            'blog'        => ['label' => 'Add Blog',         'url' => '/blog/create',        'color' => '#10b981', 'bg' => '#f0fdf4'],
            'community'   => ['label' => 'Start Discussion', 'url' => '/groups',             'color' => '#f59e0b', 'bg' => '#fffbeb'],
        ];


        if (!isset($configs[$type])) return '';
        $c = $configs[$type];

        // Compact one-line strip — sits at top of mega menu
        return '<div class="mega-cta-strip" style="--cta-color:' . $c['color'] . ';--cta-bg:' . $c['bg'] . '">'
            . '<a href="' . $c['url'] . '" class="mcta-btn" style="background:' . $c['color'] . '">'
            . $c['label'] . ' &rarr;'
            . '</a>'
            . '</div>';
    }


    /**
     * ================================================
     * NEW UNIFIED DATA SYSTEM (content_master)
     * ================================================
     * This implements the logic provided in query.txt
     */
    public function buildContentMaster()
    {
        try {
            // Recreate view to ensure event counts only include active/upcoming events
            DB::statement("
                CREATE OR REPLACE VIEW category_counts_master AS
                
                -- Marketplace category counts
                SELECT 
                    COALESCE(NULLIF(TRIM(SPLIT_PART(m.category, ',', 1)), ''), '0')::BIGINT AS subcategory_id,
                    c.product_category_name AS subcategory_name,
                    c.category_parent_id AS parent_category_id,
                    pc.product_category_name AS parent_category_name,
                    p.city_id,
                    p.area_id,
                    COUNT(*) AS total_count,
                    1 AS rank_order,
                    'marketplace' AS content_type,
                    NOW() AS created_at,
                    NOW() AS updated_at
                FROM marketplaces m
                JOIN pages p ON m.page_id = p.id
                LEFT JOIN categories c ON COALESCE(NULLIF(TRIM(SPLIT_PART(m.category, ',', 1)), ''), '0')::BIGINT = c.id
                LEFT JOIN categories pc ON c.category_parent_id = pc.id
                WHERE m.product_status = 2
                GROUP BY p.city_id, p.area_id, m.category, c.product_category_name, c.category_parent_id, pc.product_category_name

                UNION ALL

                -- Blog category counts
                SELECT 
                    bc.id AS subcategory_id,
                    bc.category_name AS subcategory_name,
                    bc.category_parent_id AS parent_category_id,
                    pbc.category_name AS parent_category_name,
                    b.city_id,
                    b.area_id,
                    COUNT(*) AS total_count,
                    1 AS rank_order,
                    'blog' AS content_type,
                    NOW() AS created_at,
                    NOW() AS updated_at
                FROM blog_category bcat
                JOIN blogs b ON bcat.blog_id = b.id
                JOIN blogcategories bc ON bcat.category_id = bc.id
                LEFT JOIN blogcategories pbc ON bc.category_parent_id = pbc.id
                WHERE b.blog_status = 2
                GROUP BY b.city_id, b.area_id, bc.id, bc.category_name, bc.category_parent_id, pbc.category_name

                UNION ALL

                -- Event category counts (ONLY active/upcoming events count)
                SELECT 
                    ec.id AS subcategory_id,
                    ec.category_name AS subcategory_name,
                    ec.category_parent_id AS parent_category_id,
                    pec.category_name AS parent_category_name,
                    e.city_id,
                    e.area_id,
                    COUNT(*) AS total_count,
                    1 AS rank_order,
                    'event' AS content_type,
                    NOW() AS created_at,
                    NOW() AS updated_at
                FROM event_category ecat
                JOIN events e ON ecat.event_id = e.id
                JOIN eventcategories ec ON ecat.category_id = ec.id
                LEFT JOIN eventcategories pec ON ec.category_parent_id = pec.id
                WHERE e.event_status = 2 AND e.event_date >= CAST(CURRENT_DATE AS TEXT)
                GROUP BY e.city_id, e.area_id, ec.id, ec.category_name, ec.category_parent_id, pec.category_name

                UNION ALL

                -- Community category counts
                SELECT 
                    gc.id AS subcategory_id,
                    gc.category_name AS subcategory_name,
                    gc.category_parent_id AS parent_category_id,
                    pgc.category_name AS parent_category_name,
                    g.city_id,
                    g.area_id,
                    COUNT(*) AS total_count,
                    1 AS rank_order,
                    'community' AS content_type,
                    NOW() AS created_at,
                    NOW() AS updated_at
                FROM group_category gcat
                JOIN groups g ON gcat.group_id = g.id
                JOIN groupcategories gc ON gcat.category_id = gc.id
                LEFT JOIN groupcategories pgc ON gc.category_parent_id = pgc.id
                WHERE g.group_status = 2 AND g.status = 1
                GROUP BY g.city_id, g.area_id, gc.id, gc.category_name, gc.category_parent_id, pgc.category_name
            ");

            // STEP 1: Truncate existing data (Table schema is now managed manually in DB)
            DB::table('content_master')->truncate();

            // STEP 2: Insert Data
            DB::statement("
                INSERT INTO content_master (
                    source_type, source_id, title, slug,
                    category_id, category_name, parent_category_id, parent_category_name,
                    location, city_id, area_id, state_id,
                    price, product_status, product_featured, total_messages, total_conversations,
                    publication_status,
                    event_date, event_time, description, user_id, event_status,
                    total_count, rank_order,
                    status, created_at, updated_at
                )
                /* 1. MARKETPLACE ITEMS (Joined with pages for city/area) */
                SELECT 
                    'marketplace', mm.product_id,
                    mm.title,
                    mm.product_slug,
                    mm.category_id,
                    mm.category_name,
                    mm.parent_category_id,
                    mm.parent_category_name,
                    mm.location,
                    p.city_id, p.area_id, p.state_id, mm.price,
                    mm.product_status,
                    mm.product_featured,
                    mm.total_messages, mm.total_conversations,
                    NULL, NULL, NULL, NULL, p.user_id, NULL, NULL, NULL,
                    mm.status,
                    mm.created_at, mm.updated_at
                FROM marketplaces_master mm
                JOIN marketplaces m ON mm.product_id = m.id
                JOIN pages p ON m.page_id = p.id
                
                UNION ALL

                /* 2. BLOG ITEMS */
                SELECT 
                    'blog', blog_id,
                    title,
                    blog_slug,
                    category_id,
                    category_name,
                    parent_category_id,
                    parent_category_name,
                    NULL, city_id, area_id, NULL, NULL, NULL, NULL, NULL, NULL,
                    publication_status,
                    NULL, NULL, NULL, NULL, NULL, NULL, NULL,
                    status,
                    created_at, updated_at
                FROM blog_master
                
                UNION ALL

                /* 3. EVENT ITEMS */
                SELECT 
                    'event', event_id,
                    title,
                    event_slug,
                    NULL, NULL, NULL, NULL,
                    location,
                    city_id, area_id, state_id, NULL, NULL, NULL, NULL, NULL, NULL,
                    event_date,
                    event_time,
                    description,
                    user_id, event_status, NULL, NULL,
                    CAST(event_status AS TEXT),
                    created_at, updated_at
                FROM events_full
                
                UNION ALL

                /* 4. CATEGORY COUNTS (Aggregated from category_counts_master view for marketplace, blog, event, community) */
                SELECT 
                    'category_count', NULL, NULL, NULL, subcategory_id,
                    subcategory_name,
                    parent_category_id,
                    parent_category_name,
                    NULL, city_id, area_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
                    total_count, rank_order,
                    content_type,
                    created_at, updated_at
                FROM category_counts_master

                UNION ALL

                /* 10b. LISTING CATEGORY COUNTS */
                SELECT 
                    'category_count', NULL, NULL, NULL, pc.id,
                    pc.category_name,
                    pc.category_parent_id,
                    p_pc.category_name,
                    NULL, p.city_id, p.area_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
                    COUNT(*), 1,
                    'listing',
                    NOW(), NOW()
                FROM page_category pcat
                JOIN pages p ON pcat.page_id = p.id
                JOIN pagecategories pc ON pcat.category_id = pc.id
                LEFT JOIN pagecategories p_pc ON pc.category_parent_id = p_pc.id
                WHERE p.item_status = 2
                GROUP BY p.city_id, p.area_id, pc.id, pc.category_name, pc.category_parent_id

                UNION ALL

                /* 11. LISTING/PAGE ITEMS */
                SELECT 
                    'listing', p.id,
                    p.title,
                    p.item_slug,
                    pc.id,
                    pc.category_name,
                    pc.category_parent_id,
                    p_pc.category_name,
                    p.address,
                    p.city_id, p.area_id, p.state_id, NULL, NULL, NULL, NULL, NULL, NULL,
                    NULL, NULL, p.description,
                    p.user_id, NULL, NULL, NULL,
                    p.item_status,
                    p.created_at, p.updated_at
                FROM pages p
                JOIN page_category pcat ON p.id = pcat.page_id
                JOIN pagecategories pc ON pcat.category_id = pc.id
                LEFT JOIN pagecategories p_pc ON pc.category_parent_id = p_pc.id
                JOIN (
                    SELECT page_id, MAX(id) as latest_page_category_id 
                    FROM page_category 
                    GROUP BY page_id
                ) latest_pc ON pcat.id = latest_pc.latest_page_category_id
                WHERE p.item_status = 2
            ");

            return response()->json(['message' => 'Unified Content Master built successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function buildMasterAggregate()
    {
        DB::table('master_aggregates')->truncate();

        $modules = [
            'city_guide' => 'city_guide_aggregates',
            'marketplace' => 'market_aggregates',
            'community' => 'community_aggregates',
            'event' => 'event_aggregates',
            'blog' => 'blog_aggregates'
        ];

        foreach ($modules as $module => $table) {
            $rows = DB::table($table)->get();

            foreach ($rows as $row) {
                DB::table('master_aggregates')->insert([
                    'module' => $module,
                    'category_id' => $row->category_id,
                    'parent_id' => $row->parent_id ?? null,
                    'total_count' => $row->total_count,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return "Master aggregate built successfully";
    }


    public function buildMarketAggregate()
    {
        $categories = DB::table('categories')
            ->select('id', 'product_category_name', 'category_parent_id')
            ->get()
            ->keyBy('id');

        DB::table('market_aggregates')->truncate();

        foreach ($categories as $catId => $cat) {

            $total = DB::table('marketplaces')
                ->whereNotNull('category')
                ->where('status', 1)
                ->where('is_approved', 'Y')
                ->whereRaw("? = ANY(string_to_array(category, ',')::bigint[])", [$catId])
                ->count();

            if ($total > 0) {
                MarketAggregate::create([
                    'module' => 'marketplace',
                    'category_id' => $catId,
                    'parent_id' => $cat->category_parent_id,
                    'total_count' => $total
                ]);
            }
        }

        return "Market aggregate built successfully";
    }

}