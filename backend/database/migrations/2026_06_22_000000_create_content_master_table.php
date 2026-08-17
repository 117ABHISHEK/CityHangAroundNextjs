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
        Schema::create('content_master', function (Blueprint $table) {
            $table->id();
            $table->string('source_type')->nullable();
            $table->bigInteger('source_id')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->string('category_name')->nullable();
            $table->bigInteger('parent_category_id')->nullable();
            $table->string('parent_category_name')->nullable();
            $table->string('location')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('area_id')->nullable();
            $table->bigInteger('state_id')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->string('product_status')->nullable();
            $table->string('product_featured')->nullable();
            $table->integer('total_messages')->nullable();
            $table->integer('total_conversations')->nullable();
            $table->string('publication_status')->nullable();
            $table->string('event_date')->nullable();
            $table->string('event_time')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('event_status')->nullable();
            $table->integer('total_count')->nullable();
            $table->integer('rank_order')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index('source_type');
            $table->index('source_id');
            $table->index('city_id');
            $table->index('area_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_master');
    }
};
