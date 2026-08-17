<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Index cities.city_slug
        try {
            Schema::table('cities', function (Blueprint $table) {
                if (Schema::hasColumn('cities', 'city_slug')) {
                    $table->index('city_slug', 'cities_city_slug_idx');
                }
            });
        } catch (\Exception $e) {
            // Ignore if index already exists
        }

        // 2. Index pagecategories.category_parent_id
        try {
            Schema::table('pagecategories', function (Blueprint $table) {
                if (Schema::hasColumn('pagecategories', 'category_parent_id')) {
                    $table->index('category_parent_id', 'pagecategories_parent_id_idx');
                }
            });
        } catch (\Exception $e) {
            // Ignore if index already exists
        }

        // 3. Index page_category.category_id
        try {
            Schema::table('page_category', function (Blueprint $table) {
                if (Schema::hasColumn('page_category', 'category_id')) {
                    $table->index('category_id', 'page_category_category_id_idx');
                }
            });
        } catch (\Exception $e) {
            // Ignore if index already exists
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            try {
                $table->dropIndex('cities_city_slug_idx');
            } catch (\Exception $e) {
            }
        });

        Schema::table('pagecategories', function (Blueprint $table) {
            try {
                $table->dropIndex('pagecategories_parent_id_idx');
            } catch (\Exception $e) {
            }
        });

        Schema::table('page_category', function (Blueprint $table) {
            try {
                $table->dropIndex('page_category_category_id_idx');
            } catch (\Exception $e) {
            }
        });
    }
};
