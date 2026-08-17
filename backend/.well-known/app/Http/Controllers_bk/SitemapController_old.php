<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use App\Models\Page;
use App\Models\Pagecategory;
use App\Models\City;

class SitemapController extends Controller
{
    public function index()
{
    ini_set('memory_limit', '512M');

    $urls = [];

    // Add static homepage URL
    $urls[] = [
        'loc' => url('/'),
        'lastmod' => now()->toAtomString(),
    ];

    $processed = 0;
    $maxRows = 50000;

    Page::with(['city', 'area', 'pagecategories'])
        ->where('item_status', 2)
        ->orderBy('id')
        ->chunk(1000, function ($chunkedPages) use (&$urls, &$processed, $maxRows) {
            foreach ($chunkedPages as $page) {
                if ($processed >= $maxRows) {
                    // Stop processing further chunks
                    return false;
                }

                $city_slug = $page->city->city_slug ?? 'city';
                $area_slug = $page->area->area_slug ?? 'area';
                $category_slug = optional($page->pagecategories->last())->category_slug ?? 'category';
                $item_slug = $page->item_slug;

                $urls[] = [
                    'loc' => route('single.page', [
                        'city_slug' => $city_slug,
                        'area_slug' => $area_slug,
                        'category_slug' => $category_slug,
                        'item_slug' => $item_slug,
                    ]),
                    'lastmod' => optional($page->updated_at)->toAtomString(),
                ];

                $processed++;
            }
        });

    $content = view('sitemap.xml', ['urls' => $urls]);

    return Response::make($content, 200)->header('Content-Type', 'application/xml');
}


}
