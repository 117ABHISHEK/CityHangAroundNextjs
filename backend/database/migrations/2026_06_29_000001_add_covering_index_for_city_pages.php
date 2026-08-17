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
        // Add covering index to completely eliminate filesort on city page query
        try {
            Schema::table('pages', function (Blueprint $table) {
                if (Schema::hasColumns('pages', ['city_id', 'item_status', 'item_featured', 'id'])) {
                    $table->index(['city_id', 'item_status', 'item_featured', 'id'], 'pages_covering_city_page_idx');
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
        Schema::table('pages', function (Blueprint $table) {
            try {
                $table->dropIndex('pages_covering_city_page_idx');
            } catch (\Exception $e) {
            }
        });
    }
};
