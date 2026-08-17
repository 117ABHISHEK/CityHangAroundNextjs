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
        Schema::table('enquirymaster', function (Blueprint $table) {
            if (!Schema::hasColumn('enquirymaster', 'area_id')) {
                $table->integer('area_id')->nullable()->after('cityid');
            }
            if (!Schema::hasColumn('enquirymaster', 'custom_product')) {
                $table->string('custom_product', 255)->nullable()->after('productid');
            }
        });

        // Make userid and productid nullable to support guest submissions and custom products
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE enquirymaster ALTER COLUMN userid DROP NOT NULL');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE enquirymaster ALTER COLUMN productid DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enquirymaster', function (Blueprint $table) {
            $table->dropColumn(['area_id', 'custom_product']);
        });
    }
};
