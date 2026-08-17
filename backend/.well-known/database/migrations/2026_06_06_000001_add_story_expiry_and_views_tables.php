<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            if (!Schema::hasColumn('stories', 'expires_at')) {
                $table->unsignedBigInteger('expires_at')->nullable()->after('updated_at');
                $table->index(['user_id', 'status', 'expires_at'], 'stories_user_status_expires_index');
            }
        });

        DB::statement("UPDATE stories SET expires_at = CAST(created_at AS UNSIGNED) + 86400 WHERE expires_at IS NULL AND created_at REGEXP '^[0-9]+$'");

        Schema::create('story_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('story_id');
            $table->unsignedBigInteger('viewer_id');
            $table->unsignedBigInteger('viewed_at');
            $table->timestamps();

            $table->unique(['story_id', 'viewer_id'], 'story_views_unique_story_viewer');
            $table->index(['story_id', 'viewed_at'], 'story_views_story_viewed_index');
            $table->index(['viewer_id', 'viewed_at'], 'story_views_viewer_viewed_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_views');

        Schema::table('stories', function (Blueprint $table) {
            if (Schema::hasColumn('stories', 'expires_at')) {
                $table->dropIndex('stories_user_status_expires_index');
                $table->dropColumn('expires_at');
            }
        });
    }
};
