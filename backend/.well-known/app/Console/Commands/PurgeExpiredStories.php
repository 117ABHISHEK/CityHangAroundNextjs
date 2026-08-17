<?php

namespace App\Console\Commands;

use App\Models\Stories;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PurgeExpiredStories extends Command
{
    protected $signature = 'stories:purge-expired';

    protected $description = 'Delete story records and media that have expired after 24 hours';

    public function handle(): int
    {
        $now = now()->timestamp;

        $expiredStories = Stories::query()
            ->where(function ($query) use ($now) {
                $query->whereNotNull('expires_at')
                    ->where('expires_at', '<', $now)
                    ->orWhere(function ($fallback) use ($now) {
                        $fallback->whereNull('expires_at')
                            ->whereRaw('(CAST(created_at AS UNSIGNED) + 86400) < ?', [$now]);
                    });
            })
            ->get();

        if ($expiredStories->isEmpty()) {
            $this->info('No expired stories found.');
            return self::SUCCESS;
        }

        $storyIds = $expiredStories->pluck('story_id');
        $mediaFiles = DB::table('media_files')->whereIn('story_id', $storyIds)->get();

        foreach ($mediaFiles as $mediaFile) {
            if (Str::startsWith((string) $mediaFile->file_name, ['http://', 'https://'])) {
                continue;
            }

            $localImagePath = public_path('storage/story/images/' . $mediaFile->file_name);
            $localVideoPath = public_path('storage/story/videos/' . $mediaFile->file_name);

            if (File::exists($localImagePath)) {
                File::delete($localImagePath);
            }

            if (File::exists($localVideoPath)) {
                File::delete($localVideoPath);
            }
        }

        DB::table('story_views')->whereIn('story_id', $storyIds)->delete();
        DB::table('media_files')->whereIn('story_id', $storyIds)->delete();
        Stories::whereIn('story_id', $storyIds)->delete();

        $this->info("Deleted {$expiredStories->count()} expired stories.");

        return self::SUCCESS;
    }
}
