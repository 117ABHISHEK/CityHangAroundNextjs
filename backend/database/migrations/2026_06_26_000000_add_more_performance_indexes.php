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
        try {
            Schema::table('cities', function (Blueprint $table) {
                if (Schema::hasColumn('cities', 'city_name')) {
                    $table->index('city_name', 'cities_city_name_idx');
                }
            });
        } catch (\Exception $e) {
            // Index might already exist, ignore error
        }

        try {
            Schema::table('pages', function (Blueprint $table) {
                if (Schema::hasColumns('pages', ['city_id', 'item_status'])) {
                    $table->index(['city_id', 'item_status'], 'pages_city_status_composite_idx');
                }
            });
        } catch (\Exception $e) {
            // Index might already exist, ignore error
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            try {
                $table->dropIndex('cities_city_name_idx');
            } catch (\Exception $e) {
                // Ignore error if index doesn't exist
            }
        });

        Schema::table('pages', function (Blueprint $table) {
            try {
                $table->dropIndex('pages_city_status_composite_idx');
            } catch (\Exception $e) {
                // Ignore error if index doesn't exist
            }
        });
    }
};
