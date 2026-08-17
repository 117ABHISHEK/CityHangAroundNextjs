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
        Schema::table('marketplaces', function (Blueprint $table) {
            // Check if column exists before adding index to prevent error
            if (Schema::hasColumn('marketplaces', 'title')) {
                $table->index('title', 'marketplaces_title_idx');
            }
            if (Schema::hasColumn('marketplaces', 'page_id')) {
                $table->index('page_id', 'marketplaces_page_id_idx');
            }
            if (Schema::hasColumns('marketplaces', ['user_id', 'status'])) {
                $table->index(['user_id', 'status'], 'marketplaces_user_status_idx');
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'category_parent_id')) {
                $table->index('category_parent_id', 'categories_parent_id_idx');
            }
        });

        Schema::table('pages', function (Blueprint $table) {
            if (Schema::hasColumn('pages', 'city_id')) {
                $table->index('city_id', 'pages_city_id_idx');
            }
            if (Schema::hasColumn('pages', 'user_id')) {
                $table->index('user_id', 'pages_user_id_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketplaces', function (Blueprint $table) {
            $table->dropIndex('marketplaces_title_idx');
            $table->dropIndex('marketplaces_page_id_idx');
            $table->dropIndex('marketplaces_user_status_idx');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_parent_id_idx');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex('pages_city_id_idx');
            $table->dropIndex('pages_user_id_idx');
        });
    }
};
