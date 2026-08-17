<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('country_name');
            $table->string('country_code', 3)->unique(); // ISO 3-letter country code
            $table->string('country_slug')->unique();
            $table->string('country_flag')->nullable(); // Flag image path
            $table->text('country_about')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('createdBy')->nullable();
            $table->enum('is_approved', ['Y', 'N'])->default('N');
            $table->timestamps();

            $table->foreign('createdBy')->references('id')->on('users')->onDelete('set null');
            $table->index(['country_name', 'country_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
};
