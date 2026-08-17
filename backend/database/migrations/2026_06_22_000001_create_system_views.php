<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. marketplaces_master view (skipped due to permission/ownership conflict on live server)
        /*
        DB::statement("
            CREATE OR REPLACE VIEW marketplaces_master AS
            SELECT 
                m.id AS product_id,
                m.title,
                m.product_slug,
                CAST(m.category AS UNSIGNED) AS category_id,
                c.product_category_name AS category_name,
                c.category_parent_id AS parent_category_id,
                pc.product_category_name AS parent_category_name,
                m.location,
                m.price,
                m.product_status,
                m.product_featured,
                (SELECT COUNT(*) FROM marketplace_messages mm JOIN marketplace_conversations mc ON mm.conversation_id = mc.id WHERE mc.marketplace_id = m.id) AS total_messages,
                (SELECT COUNT(*) FROM marketplace_conversations mc WHERE mc.marketplace_id = m.id) AS total_conversations,
                m.status,
                m.created_at,
                m.updated_at
            FROM marketplaces m
            LEFT JOIN categories c ON CAST(m.category AS UNSIGNED) = c.id
            LEFT JOIN categories pc ON c.category_parent_id = pc.id
        ");
        */

        // 2. blog_master view
        DB::statement("
            CREATE OR REPLACE VIEW blog_master AS
            SELECT 
                b.id AS blog_id,
                b.title,
                b.blog_slug,
                COALESCE(NULLIF(TRIM(SPLIT_PART(b.category_id, ',', 1)), ''), '0')::BIGINT AS category_id,
                bc.category_name AS category_name,
                bc.category_parent_id AS parent_category_id,
                pbc.category_name AS parent_category_name,
                b.city_id,
                b.area_id,
                b.publication_status,
                b.status,
                b.blog_status,
                b.created_at,
                b.updated_at
            FROM blogs b
            LEFT JOIN blogcategories bc ON COALESCE(NULLIF(TRIM(SPLIT_PART(b.category_id, ',', 1)), ''), '0')::BIGINT = bc.id
            LEFT JOIN blogcategories pbc ON bc.category_parent_id = pbc.id
        ");

        // 3. events_full view
        DB::statement("
            CREATE OR REPLACE VIEW events_full AS
            SELECT 
                id AS event_id,
                user_id,
                group_id,
                publisher,
                publisher_id,
                event_status,
                title,
                event_slug,
                category_id,
                description,
                event_date,
                event_time,
                location,
                state_id,
                city_id,
                area_id,
                going_users_id,
                interested_users_id,
                thumbnail,
                banner,
                privacy,
                created_at,
                updated_at,
                view
            FROM events
        ");

        // 4. category_counts_master view
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

            -- Event category counts
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
            JOIN `groups` g ON gcat.group_id = g.id
            JOIN groupcategories gc ON gcat.category_id = gc.id
            LEFT JOIN groupcategories pgc ON gc.category_parent_id = pgc.id
            WHERE g.group_status = 2 AND g.status = 1
            GROUP BY g.city_id, g.area_id, gc.id, gc.category_name, gc.category_parent_id, pgc.category_name
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // DB::statement("DROP VIEW IF EXISTS marketplaces_master");
        DB::statement("DROP VIEW IF EXISTS blog_master");
        DB::statement("DROP VIEW IF EXISTS events_full");
        DB::statement("DROP VIEW IF EXISTS category_counts_master");
    }
};
