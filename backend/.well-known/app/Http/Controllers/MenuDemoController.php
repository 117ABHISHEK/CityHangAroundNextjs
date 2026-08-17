<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\MarketAggregate;

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
                ->having('count', '>', 0)
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

        $data = [
            'city_guide' => $this->getFilteredHtml('listing', $cityId),
            'marketplace' => $this->getFilteredHtml('marketplace', $cityId),
            'community' => $this->getFilteredHtml('community', $cityId),
            'event' => $this->getFilteredHtml('event', $cityId),
            'blog' => $this->getFilteredHtml('blog', $cityId),
        ];

        return response()->json($data);
    }


    // ================= HTML BUILDER =================

    private function getFilteredHtml($type, $cityId = null)
    {
        // 1. Fetch data based on city or global aggregation
        $query = DB::table('content_master')
            ->where('source_type', 'category_count')
            ->where('status', $type);

        if ($cityId && $cityId !== 'null' && $cityId !== '') {
            $query->where('city_id', $cityId);
        }

        // Group by subcategory_id to ensure uniqueness in the menu
        $items = $query->select(
            'category_id as subcategory_id',
            'category_name as subcategory_name',
            DB::raw('SUM(total_count) as total_count')
        )
            ->groupBy('category_id', 'category_name')
            ->having('total_count', '>', 0)
            ->orderBy('total_count', 'desc')
            ->get();

        if ($items->isEmpty()) {
            return '<div class="p-3 text-muted">No categories found for this area.</div>';
        }

        // 2. Distribute items into columns using interleaved (row-by-row) distribution
        $columnCount = 4;
        $columns = array_fill(0, $columnCount, []);
        
        foreach ($items->values() as $index => $item) {
            $colIdx = $index % $columnCount;
            $columns[$colIdx][] = $item;
        }

        $html = '';
        foreach ($columns as $columnItems) {
            if (empty($columnItems)) continue;
            $html .= '<div class="mega-column">';
            foreach ($columnItems as $item) {
                $url = url("search?cat={$item->subcategory_id}" . ($cityId ? "&city={$cityId}" : ''));
                $html .= '<a href="' . $url . '" title="' . $item->total_count . ' items">';
                $html .= $item->subcategory_name;
                $html .= '</a>';
            }
            $html .= '</div>';
        }

        return $html;
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
                    CONVERT('marketplace' USING utf8) COLLATE utf8_general_ci, mm.product_id,
                    CONVERT(mm.title USING utf8) COLLATE utf8_general_ci,
                    CONVERT(mm.product_slug USING utf8) COLLATE utf8_general_ci,
                    mm.category_id,
                    CONVERT(mm.category_name USING utf8) COLLATE utf8_general_ci,
                    mm.parent_category_id,
                    CONVERT(mm.parent_category_name USING utf8) COLLATE utf8_general_ci,
                    CONVERT(mm.location USING utf8) COLLATE utf8_general_ci,
                    p.city_id, p.area_id, p.state_id, mm.price,
                    CONVERT(mm.product_status USING utf8) COLLATE utf8_general_ci,
                    CONVERT(mm.product_featured USING utf8) COLLATE utf8_general_ci,
                    mm.total_messages, mm.total_conversations,
                    NULL, NULL, NULL, NULL, p.user_id, NULL, NULL, NULL,
                    CONVERT(mm.status USING utf8) COLLATE utf8_general_ci,
                    mm.created_at, mm.updated_at
                FROM marketplaces_master mm
                JOIN marketplaces m ON mm.product_id = m.id
                JOIN pages p ON m.page_id = p.id
                
                UNION ALL

                /* 2. BLOG ITEMS */
                SELECT 
                    CONVERT('blog' USING utf8) COLLATE utf8_general_ci, blog_id,
                    CONVERT(title USING utf8) COLLATE utf8_general_ci,
                    CONVERT(blog_slug USING utf8) COLLATE utf8_general_ci,
                    category_id,
                    CONVERT(category_name USING utf8) COLLATE utf8_general_ci,
                    parent_category_id,
                    CONVERT(parent_category_name USING utf8) COLLATE utf8_general_ci,
                    NULL, city_id, area_id, NULL, NULL, NULL, NULL, NULL, NULL,
                    CONVERT(publication_status USING utf8) COLLATE utf8_general_ci,
                    NULL, NULL, NULL, NULL, NULL, NULL, NULL,
                    CONVERT(status USING utf8) COLLATE utf8_general_ci,
                    created_at, updated_at
                FROM blog_master
                
                UNION ALL

                /* 3. EVENT ITEMS */
                SELECT 
                    CONVERT('event' USING utf8) COLLATE utf8_general_ci, event_id,
                    CONVERT(title USING utf8) COLLATE utf8_general_ci,
                    CONVERT(event_slug USING utf8) COLLATE utf8_general_ci,
                    NULL, NULL, NULL, NULL,
                    CONVERT(location USING utf8) COLLATE utf8_general_ci,
                    city_id, area_id, state_id, NULL, NULL, NULL, NULL, NULL, NULL,
                    CONVERT(event_date USING utf8) COLLATE utf8_general_ci,
                    CONVERT(event_time USING utf8) COLLATE utf8_general_ci,
                    CONVERT(description USING utf8) COLLATE utf8_general_ci,
                    user_id, event_status, NULL, NULL,
                    CONVERT(CAST(event_status AS CHAR) USING utf8) COLLATE utf8_general_ci,
                    created_at, updated_at
                FROM events_full
                
                UNION ALL

                /* 4. CATEGORY COUNTS (Aggregated directly for Marketplace, rest from category_counts_master) */
                SELECT 
                    CONVERT('category_count' USING utf8) COLLATE utf8_general_ci, NULL, NULL, NULL, subcategory_id,
                    CONVERT(subcategory_name USING utf8) COLLATE utf8_general_ci,
                    parent_category_id,
                    CONVERT(parent_category_name USING utf8) COLLATE utf8_general_ci,
                    NULL, city_id, area_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
                    total_count, rank_order,
                    CONVERT(content_type USING utf8) COLLATE utf8_general_ci,
                    created_at, updated_at
                FROM category_counts_master
                WHERE content_type != 'marketplace'
                
                UNION ALL

                /* 5. NEW DEALS SYSTEM (Joined with deal_locations) */
                SELECT 
                    CONVERT('deal' USING utf8) COLLATE utf8_general_ci, d.id,
                    CONVERT(d.title USING utf8) COLLATE utf8_general_ci,
                    CONVERT(d.slug USING utf8) COLLATE utf8_general_ci,
                    d.category_id,
                    CONVERT(dc.name USING utf8) COLLATE utf8_general_ci,
                    dc.parent_id,
                    CONVERT((SELECT name FROM deal_categories WHERE id = dc.parent_id) USING utf8) COLLATE utf8_general_ci,
                    NULL, dl.city_id, dl.area_id, NULL, d.deal_price,
                    CONVERT(d.status USING utf8) COLLATE utf8_general_ci,
                    CONVERT(d.is_featured USING utf8) COLLATE utf8_general_ci,
                    NULL, NULL, NULL, NULL, NULL,
                    CONVERT(d.description USING utf8) COLLATE utf8_general_ci,
                    d.seller_id, NULL, NULL, NULL,
                    CONVERT(d.status USING utf8) COLLATE utf8_general_ci,
                    d.created_at, d.updated_at
                FROM deals d
                JOIN deal_locations dl ON d.id = dl.deal_id
                LEFT JOIN deal_categories dc ON d.category_id = dc.id
                WHERE d.status = 'live'

                UNION ALL

                /* 6. MARKETPLACE CATEGORY COUNTS (Legacy) */
                SELECT 
                    CONVERT('category_count' USING utf8) COLLATE utf8_general_ci, NULL, NULL, NULL, mm.category_id,
                    CONVERT(mm.category_name USING utf8) COLLATE utf8_general_ci,
                    mm.parent_category_id,
                    CONVERT(mm.parent_category_name USING utf8) COLLATE utf8_general_ci,
                    NULL, p.city_id, p.area_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
                    COUNT(*), 1,
                    CONVERT('marketplace' USING utf8) COLLATE utf8_general_ci,
                    NOW(), NOW()
                FROM marketplaces_master mm
                JOIN marketplaces m ON mm.product_id = m.id
                JOIN pages p ON m.page_id = p.id
                WHERE mm.product_status = 2
                GROUP BY p.city_id, p.area_id, mm.category_id, mm.category_name, mm.parent_category_id, mm.parent_category_name

                UNION ALL

                /* 7. NEW DEALS CATEGORY COUNTS */
                SELECT 
                    CONVERT('category_count' USING utf8) COLLATE utf8_general_ci, NULL, NULL, NULL, d.category_id,
                    CONVERT(dc.name USING utf8) COLLATE utf8_general_ci,
                    dc.parent_id,
                    CONVERT((SELECT name FROM deal_categories WHERE id = dc.parent_id) USING utf8) COLLATE utf8_general_ci,
                    NULL, dl.city_id, dl.area_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
                    COUNT(*), 1,
                    CONVERT('deal' USING utf8) COLLATE utf8_general_ci,
                    NOW(), NOW()
                FROM deals d
                JOIN deal_locations dl ON d.id = dl.deal_id
                LEFT JOIN deal_categories dc ON d.category_id = dc.id
                WHERE d.status = 'live'
                GROUP BY dl.city_id, dl.area_id, d.category_id, dc.name, dc.parent_id

                UNION ALL

                /* 8. COMMUNITY CATEGORY COUNTS */
                SELECT 
                    CONVERT('category_count' USING utf8) COLLATE utf8_general_ci, NULL, NULL, NULL, gc.id,
                    CONVERT(gc.category_name USING utf8) COLLATE utf8_general_ci,
                    gc.category_parent_id,
                    CONVERT((SELECT category_name FROM groupcategories WHERE id = gc.category_parent_id) USING utf8) COLLATE utf8_general_ci,
                    NULL, g.city_id, g.area_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
                    COUNT(*), 1,
                    CONVERT('community' USING utf8) COLLATE utf8_general_ci,
                    NOW(), NOW()
                FROM group_category gcat
                JOIN `groups` g ON gcat.group_id = g.id
                JOIN groupcategories gc ON gcat.category_id = gc.id
                WHERE g.group_status = 2 AND g.status = 1
                GROUP BY g.city_id, g.area_id, gc.id, gc.category_name, gc.category_parent_id

                UNION ALL

                /* 9. EVENT CATEGORY COUNTS */
                SELECT 
                    CONVERT('category_count' USING utf8) COLLATE utf8_general_ci, NULL, NULL, NULL, ec.id,
                    CONVERT(ec.category_name USING utf8) COLLATE utf8_general_ci,
                    ec.category_parent_id,
                    CONVERT((SELECT category_name FROM eventcategories WHERE id = ec.category_parent_id) USING utf8) COLLATE utf8_general_ci,
                    NULL, e.city_id, e.area_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
                    COUNT(*), 1,
                    CONVERT('event' USING utf8) COLLATE utf8_general_ci,
                    NOW(), NOW()
                FROM event_category ecat
                JOIN events_full e ON ecat.event_id = e.event_id
                JOIN eventcategories ec ON ecat.category_id = ec.id
                WHERE e.event_status = 2 OR e.event_status = 1
                GROUP BY e.city_id, e.area_id, ec.id, ec.category_name, ec.category_parent_id

                UNION ALL

                /* 10. BLOG CATEGORY COUNTS */
                SELECT 
                    CONVERT('category_count' USING utf8) COLLATE utf8_general_ci, NULL, NULL, NULL, bc.id,
                    CONVERT(bc.category_name USING utf8) COLLATE utf8_general_ci,
                    bc.category_parent_id,
                    CONVERT((SELECT category_name FROM blogcategories WHERE id = bc.category_parent_id) USING utf8) COLLATE utf8_general_ci,
                    NULL, b.city_id, b.area_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
                    COUNT(*), 1,
                    CONVERT('blog' USING utf8) COLLATE utf8_general_ci,
                    NOW(), NOW()
                FROM blog_category bcat
                JOIN blog_master b ON bcat.blog_id = b.blog_id
                JOIN blogcategories bc ON bcat.category_id = bc.id
                WHERE b.status = 'approved' OR b.status = '1'
                GROUP BY b.city_id, b.area_id, bc.id, bc.category_name, bc.category_parent_id
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
                ->whereRaw("FIND_IN_SET(?, category)", [$catId])
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