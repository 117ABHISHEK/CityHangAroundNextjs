<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixCategorySlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:category-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix category slugs by removing special characters';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting to fix category slugs...');
        
        // Fix page categories
        $this->info('Fixing page categories...');
        $pageCategories = DB::table('pagecategories')->get();
        $updated = 0;
        foreach ($pageCategories as $category) {
            $cleanSlug = clean_slug($category->category_name);
            if ($cleanSlug !== $category->category_slug) {
                DB::table('pagecategories')
                    ->where('id', $category->id)
                    ->update(['category_slug' => $cleanSlug]);
                $updated++;
            }
        }
        $this->info("Updated {$updated} page categories");
        
        // Fix product categories
        $this->info('Fixing product categories...');
        $productCategories = DB::table('categories')->get();
        $updated = 0;
        foreach ($productCategories as $category) {
            $cleanSlug = clean_slug($category->product_category_name);
            if ($cleanSlug !== $category->product_category_slug) {
                DB::table('categories')
                    ->where('id', $category->id)
                    ->update(['product_category_slug' => $cleanSlug]);
                $updated++;
            }
        }
        $this->info("Updated {$updated} product categories");
        
        // Fix blog categories
        $this->info('Fixing blog categories...');
        $blogCategories = DB::table('blogcategories')->get();
        $updated = 0;
        foreach ($blogCategories as $category) {
            $cleanSlug = clean_slug($category->category_name);
            if ($cleanSlug !== $category->category_slug) {
                DB::table('blogcategories')
                    ->where('id', $category->id)
                    ->update(['category_slug' => $cleanSlug]);
                $updated++;
            }
        }
        $this->info("Updated {$updated} blog categories");
        
        $this->info('Category slug fixing completed!');
        return Command::SUCCESS;
    }
}
