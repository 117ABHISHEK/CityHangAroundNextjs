<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetExpiredFeaturedItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:expired-featured';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = now();

$models = [
    \App\Models\Page::class,
    \App\Models\Blog::class,
    \App\Models\Event::class,
    \App\Models\Marketplace::class,
    \App\Models\Group::class,
];

foreach ($models as $model) {
    $model::where('item_featured', 1)
        ->where(function ($query) use ($now) {
            $query->where(function ($q) use ($now) {
                $q->whereNull('priority_until_city')
                  ->orWhere('priority_until_city', '<', $now);
            })->where(function ($q) use ($now) {
                $q->whereNull('priority_until_area')
                  ->orWhere('priority_until_area', '<', $now);
            });
        })
        ->update(['item_featured' => 0]);
}


    $this->info('Expired featured items reset successfully.');
    }
}
