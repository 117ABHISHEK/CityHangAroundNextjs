<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use App\Models\Event;
use App\Models\Blog;
use App\Models\Video;
use App\Models\Posts;
use App\Models\Marketplace;
use App\Models\Group;
use DB;

class SitemapController extends Controller
{
    private const PAGE_SITEMAP_LIMIT = 40000;
    private const PAGE_CONTENT_COLUMNS = [
        'description',
        'why_visit_us',
        'our_story',
        'policy',
    ];

    private function normalizeContentText(?string $html): string
    {
        $decoded = html_entity_decode($html ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $decoded = str_replace("\u{00A0}", ' ', $decoded);
        $decoded = strip_tags($decoded);
        $decoded = preg_replace('/\s+/u', ' ', $decoded ?? '');

        return trim($decoded ?? '');
    }

    private function hasMeaningfulContent(?string ...$contents): bool
    {
        foreach ($contents as $content) {
            if ($this->normalizeContentText($content) !== '') {
                return true;
            }
        }

        return false;
    }

    private function meaningfulPagesBaseQuery()
    {
        return DB::table('pages')
            ->where('pages.item_status', 2)
            ->whereNotNull('pages.item_slug')
            ->whereRaw("TRIM(pages.item_slug) <> ''")
            ->where(function ($query) {
                foreach (self::PAGE_CONTENT_COLUMNS as $column) {
                    $query->orWhere(function ($contentQuery) use ($column) {
                        $contentQuery->whereNotNull("pages.{$column}")
                            ->whereRaw("TRIM(pages.{$column}) <> ''");
                    });
                }
            });
    }

    private function pageSitemapQuery()
    {
        $latestCategorySubquery = DB::table('page_category')
            ->selectRaw('MAX(id) as max_id, MAX(category_id) as category_id, page_id')
            ->groupBy('page_id');

        return $this->meaningfulPagesBaseQuery()
            ->join('cities', 'cities.id', '=', 'pages.city_id')
            ->join('areas', 'areas.id', '=', 'pages.area_id')
            ->joinSub($latestCategorySubquery, 'latest_page_category', function ($join) {
                $join->on('latest_page_category.page_id', '=', 'pages.id');
            })
            ->join('pagecategories', 'pagecategories.id', '=', 'latest_page_category.category_id');
    }
    
    private function getPageSitemapParts()
    {
        $totalPages = $this->pageSitemapQuery()->distinct('pages.id')->count('pages.id');
        $limit = self::PAGE_SITEMAP_LIMIT;
        return ceil($totalPages / $limit);
    }

    public function index()
{
    // Create sitemap index with all content types
    $sitemaps = [];
    
    // Add multiple page sitemaps (split into parts for performance)
    $totalParts = $this->getPageSitemapParts();
    for ($i = 1; $i <= $totalParts; $i++) {
        $sitemaps[] = [
            'loc' => url("/sitemap/pages-{$i}.xml"),
            'lastmod' => now()->toAtomString(),
        ];
    }
    
    // Add other content types
    $otherSitemaps = [
        [
            'loc' => url('/sitemap/events.xml'),
            'lastmod' => now()->toAtomString(),
        ],
        [
            'loc' => url('/sitemap/marketplace.xml'),
            'lastmod' => now()->toAtomString(),
        ],
        [
            'loc' => url('/sitemap/blogs.xml'),
            'lastmod' => now()->toAtomString(),
        ],
        [
            'loc' => url('/sitemap/videos.xml'),
            'lastmod' => now()->toAtomString(),
        ],
        [
            'loc' => url('/sitemap/posts.xml'),
            'lastmod' => now()->toAtomString(),
        ],
        [
            'loc' => url('/sitemap/groups.xml'),
            'lastmod' => now()->toAtomString(),
        ],
        [
            'loc' => url('/sitemap/static.xml'),
            'lastmod' => now()->toAtomString(),
        ],
    ];
    
    $sitemaps = array_merge($sitemaps, $otherSitemaps);

    $content = view('sitemap.index', ['sitemaps' => $sitemaps]);
    return response($content, 200)->header('Content-Type', 'application/xml');
}

    public function sitemaplisting()
    {
        ini_set('memory_limit', '512M');

        $urls = [];

        // Add static homepage URL
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toAtomString(),
        ];


        $items = DB::select(DB::raw('select DISTINCT pages.id,pages.item_slug,cities.city_slug,areas.area_slug,pagecategories.category_slug,pages.updated_at from pages
        inner join cities on cities.id=pages.city_id
        inner JOIN areas on areas.id=pages.area_id
        inner join(select max(id) as max,max(category_id) as category_id,page_id
            from page_category  group by page_id) t1
            on t1.page_id=pages.id
            inner JOIN pagecategories on pagecategories.id=t1.category_id
        where  pages.item_status=2 and pages.id>0 and pages.id<50001
        '));


        foreach($items as $key => $item)
        {

            $urls[] = [
                'loc' => route('single.page', [
                    'city_slug' => $item->city_slug,
                    'area_slug' =>$item->area_slug,
                    'category_slug' => $item->category_slug,
                    'item_slug' => $item->item_slug,
                ]),
                'lastmod' => optional($item->updated_at)->toAtomString(),
            ];
        }

        $content = view('sitemap.xml', ['urls' => $urls]);

        return Response::make($content, 200)->header('Content-Type', 'application/xml');
    }


public function sitemaplisting2()
{
    ini_set('memory_limit', '512M');

    $urls = [];

    // Add static homepage URL
    $urls[] = [
        'loc' => url('/'),
        'lastmod' => now()->toAtomString(),
    ];


    $items = DB::select(DB::raw('select DISTINCT pages.id,pages.item_slug,cities.city_slug,areas.area_slug,pagecategories.category_slug,pages.updated_at from pages
    inner join cities on cities.id=pages.city_id
    inner JOIN areas on areas.id=pages.area_id
    inner join(select max(id) as max,max(category_id) as category_id,page_id
        from page_category  group by page_id) t1
        on t1.page_id=pages.id
        inner JOIN pagecategories on pagecategories.id=t1.category_id
     where  pages.item_status=2 and pages.id>50000 and pages.id<100001
    '));


    foreach($items as $key => $item)
    {

        $urls[] = [
            'loc' => route('single.page', [
                'city_slug' => $item->city_slug,
                'area_slug' =>$item->area_slug,
                'category_slug' => $item->category_slug,
                'item_slug' => $item->item_slug,
            ]),
            'lastmod' => optional($item->updated_at)->toAtomString(),
        ];
    }

    $content = view('sitemap.xml', ['urls' => $urls]);

    return Response::make($content, 200)->header('Content-Type', 'application/xml');
}



public function sitemaplisting3()
{
    ini_set('memory_limit', '512M');

    $urls = [];

    // Add static homepage URL
    $urls[] = [
        'loc' => url('/'),
        'lastmod' => now()->toAtomString(),
    ];


    $items = DB::select(DB::raw('select DISTINCT pages.id,pages.item_slug,cities.city_slug,areas.area_slug,pagecategories.category_slug,pages.updated_at from pages
    inner join cities on cities.id=pages.city_id
    inner JOIN areas on areas.id=pages.area_id
    inner join(select max(id) as max,max(category_id) as category_id,page_id
        from page_category  group by page_id) t1
        on t1.page_id=pages.id
        inner JOIN pagecategories on pagecategories.id=t1.category_id
     where  pages.item_status=2 and pages.id>100000 and pages.id<150001
    '));


    foreach($items as $key => $item)
    {

        $urls[] = [
            'loc' => route('single.page', [
                'city_slug' => $item->city_slug,
                'area_slug' =>$item->area_slug,
                'category_slug' => $item->category_slug,
                'item_slug' => $item->item_slug,
            ]),
            'lastmod' => optional($item->updated_at)->toAtomString(),
        ];
    }

    $content = view('sitemap.xml', ['urls' => $urls]);

    return Response::make($content, 200)->header('Content-Type', 'application/xml');
}



public function sitemaplisting4()
{
    ini_set('memory_limit', '512M');

    $urls = [];

    // Add static homepage URL
    $urls[] = [
        'loc' => url('/'),
        'lastmod' => now()->toAtomString(),
    ];


    $items = DB::select(DB::raw('select DISTINCT pages.id,pages.item_slug,cities.city_slug,areas.area_slug,pagecategories.category_slug,pages.updated_at from pages
    inner join cities on cities.id=pages.city_id
    inner JOIN areas on areas.id=pages.area_id
    inner join(select max(id) as max,max(category_id) as category_id,page_id
        from page_category  group by page_id) t1
        on t1.page_id=pages.id
        inner JOIN pagecategories on pagecategories.id=t1.category_id
     where  pages.item_status=2 and pages.id>150000 and pages.id<200001
    '));


    foreach($items as $key => $item)
    {

        $urls[] = [
            'loc' => route('single.page', [
                'city_slug' => $item->city_slug,
                'area_slug' =>$item->area_slug,
                'category_slug' => $item->category_slug,
                'item_slug' => $item->item_slug,
            ]),
            'lastmod' => optional($item->updated_at)->toAtomString(),
        ];
    }

    $content = view('sitemap.xml', ['urls' => $urls]);

    return Response::make($content, 200)->header('Content-Type', 'application/xml');
}



public function sitemaplisting5()
{
    ini_set('memory_limit', '512M');

    $urls = [];

    // Add static homepage URL
    $urls[] = [
        'loc' => url('/'),
        'lastmod' => now()->toAtomString(),
    ];


    $items = DB::select(DB::raw('select DISTINCT pages.id,pages.item_slug,cities.city_slug,areas.area_slug,pagecategories.category_slug,pages.updated_at from pages
    inner join cities on cities.id=pages.city_id
    inner JOIN areas on areas.id=pages.area_id
    inner join(select max(id) as max,max(category_id) as category_id,page_id
        from page_category  group by page_id) t1
        on t1.page_id=pages.id
        inner JOIN pagecategories on pagecategories.id=t1.category_id
     where  pages.item_status=2 and pages.id>200000 and pages.id<250001
    '));


    foreach($items as $key => $item)
    {

        $urls[] = [
            'loc' => route('single.page', [
                'city_slug' => $item->city_slug,
                'area_slug' =>$item->area_slug,
                'category_slug' => $item->category_slug,
                'item_slug' => $item->item_slug,
            ]),
            'lastmod' => optional($item->updated_at)->toAtomString(),
        ];
    }

    $content = view('sitemap.xml', ['urls' => $urls]);

    return Response::make($content, 200)->header('Content-Type', 'application/xml');
}



public function sitemaplisting6()
{
    ini_set('memory_limit', '512M');

    $urls = [];

    // Add static homepage URL
    $urls[] = [
        'loc' => url('/'),
        'lastmod' => now()->toAtomString(),
    ];


    $items = DB::select(DB::raw('select DISTINCT pages.id,pages.item_slug,cities.city_slug,areas.area_slug,pagecategories.category_slug,pages.updated_at from pages
    inner join cities on cities.id=pages.city_id
    inner JOIN areas on areas.id=pages.area_id
    inner join(select max(id) as max,max(category_id) as category_id,page_id
        from page_category  group by page_id) t1
        on t1.page_id=pages.id
        inner JOIN pagecategories on pagecategories.id=t1.category_id
     where  pages.item_status=2 and pages.id>250000 and pages.id<300001
    '));


    foreach($items as $key => $item)
    {

        $urls[] = [
            'loc' => route('single.page', [
                'city_slug' => $item->city_slug,
                'area_slug' =>$item->area_slug,
                'category_slug' => $item->category_slug,
                'item_slug' => $item->item_slug,
            ]),
            'lastmod' => optional($item->updated_at)->toAtomString(),
        ];
    }

    $content = view('sitemap.xml', ['urls' => $urls]);

    return Response::make($content, 200)->header('Content-Type', 'application/xml');
}


    public function sitemappagecategorylisting()
    {
        ini_set('memory_limit', '512M');

        $urls = [];

        // Add static homepage URL
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toAtomString(),
        ];


        $items =DB::table('pagecategories')->select('pagecategories.*','cities.*')
        ->join('page_category','pagecategories.id','=','page_category.category_id')
        ->join('pages','pages.id','=','page_category.page_id')
        ->join('cities','cities.id','=','pages.city_id')
        ->distinct('pagecategories.category_name')
        ->orderBy('pagecategories.category_name', 'asc')
        ->where('pages.item_status','=',2)
        ->where('pages.id','>',0)
        ->where('pages.id','<',50001)
        ->get();

      
        foreach($items as $key => $item)
        {

            $urls[] = [
                'loc' => route('page.category.city', [
                    'city_slug' => $item->city_slug,
                    'category_slug' => $item->category_slug,
                ]),
                'lastmod' => optional($item->updated_at)->toAtomString(),
            ];
        }

        $content = view('sitemap.xml', ['urls' => $urls]);

        return Response::make($content, 200)->header('Content-Type', 'application/xml');
    }


    public function sitemappagecategorylisting2()
    {
        ini_set('memory_limit', '512M');

        $urls = [];

        // Add static homepage URL
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toAtomString(),
        ];


        $items =DB::table('pagecategories')->select('pagecategories.*','cities.*')
        ->join('page_category','pagecategories.id','=','page_category.category_id')
        ->join('pages','pages.id','=','page_category.page_id')
        ->join('cities','cities.id','=','pages.city_id')
        ->distinct('pagecategories.category_name')
        ->orderBy('pagecategories.category_name', 'asc')
        ->where('pages.item_status','=',2)
        ->where('pages.id','>',50000)
        ->where('pages.id','<',100001)
        ->get();

      
        foreach($items as $key => $item)
        {

            $urls[] = [
                'loc' => route('page.category.city', [
                    'city_slug' => $item->city_slug,
                    'category_slug' => $item->category_slug,
                ]),
                'lastmod' => optional($item->updated_at)->toAtomString(),
            ];
        }

        $content = view('sitemap.xml', ['urls' => $urls]);

        return Response::make($content, 200)->header('Content-Type', 'application/xml');
    }


    public function sitemappagecategorylisting3()
    {
        ini_set('memory_limit', '512M');

        $urls = [];

        // Add static homepage URL
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toAtomString(),
        ];


        $items =DB::table('pagecategories')->select('pagecategories.*','cities.*')
        ->join('page_category','pagecategories.id','=','page_category.category_id')
        ->join('pages','pages.id','=','page_category.page_id')
        ->join('cities','cities.id','=','pages.city_id')
        ->distinct('pagecategories.category_name')
        ->orderBy('pagecategories.category_name', 'asc')
        ->where('pages.item_status','=',2)
        ->where('pages.id','>',100000)
        ->where('pages.id','<',150001)
        ->get();

      
        foreach($items as $key => $item)
        {

            $urls[] = [
                'loc' => route('page.category.city', [
                    'city_slug' => $item->city_slug,
                    'category_slug' => $item->category_slug,
                ]),
                'lastmod' => optional($item->updated_at)->toAtomString(),
            ];
        }

        $content = view('sitemap.xml', ['urls' => $urls]);

        return Response::make($content, 200)->header('Content-Type', 'application/xml');
    }

    public function sitemappagecategorylisting4()
    {
        ini_set('memory_limit', '512M');

        $urls = [];

        // Add static homepage URL
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toAtomString(),
        ];


        $items =DB::table('pagecategories')->select('pagecategories.*','cities.*')
        ->join('page_category','pagecategories.id','=','page_category.category_id')
        ->join('pages','pages.id','=','page_category.page_id')
        ->join('cities','cities.id','=','pages.city_id')
        ->distinct('pagecategories.category_name')
        ->orderBy('pagecategories.category_name', 'asc')
        ->where('pages.item_status','=',2)
        ->where('pages.id','>',150000)
        ->where('pages.id','<',200001)
        ->get();

      
        foreach($items as $key => $item)
        {

            $urls[] = [
                'loc' => route('page.category.city', [
                    'city_slug' => $item->city_slug,
                    'category_slug' => $item->category_slug,
                ]),
                'lastmod' => optional($item->updated_at)->toAtomString(),
            ];
        }

        $content = view('sitemap.xml', ['urls' => $urls]);

        return Response::make($content, 200)->header('Content-Type', 'application/xml');
    }


    public function sitemappagecategorylisting5()
    {
        ini_set('memory_limit', '512M');

        $urls = [];

        // Add static homepage URL
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toAtomString(),
        ];


        $items =DB::table('pagecategories')->select('pagecategories.*','cities.*')
        ->join('page_category','pagecategories.id','=','page_category.category_id')
        ->join('pages','pages.id','=','page_category.page_id')
        ->join('cities','cities.id','=','pages.city_id')
        ->distinct('pagecategories.category_name')
        ->orderBy('pagecategories.category_name', 'asc')
        ->where('pages.item_status','=',2)
        ->where('pages.id','>',200000)
        ->where('pages.id','<',250001)
        ->get();

      
        foreach($items as $key => $item)
        {

            $urls[] = [
                'loc' => route('page.category.city', [
                    'city_slug' => $item->city_slug,
                    'category_slug' => $item->category_slug,
                ]),
                'lastmod' => optional($item->updated_at)->toAtomString(),
            ];
        }

        $content = view('sitemap.xml', ['urls' => $urls]);

        return Response::make($content, 200)->header('Content-Type', 'application/xml');
    }


    public function sitemappagecategorylisting6()
    {
        ini_set('memory_limit', '512M');

        $urls = [];

        // Add static homepage URL
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toAtomString(),
        ];


        $items =DB::table('pagecategories')->select('pagecategories.*','cities.*')
        ->join('page_category','pagecategories.id','=','page_category.category_id')
        ->join('pages','pages.id','=','page_category.page_id')
        ->join('cities','cities.id','=','pages.city_id')
        ->distinct('pagecategories.category_name')
        ->orderBy('pagecategories.category_name', 'asc')
        ->where('pages.item_status','=',2)
        ->where('pages.id','>',250000)
        ->where('pages.id','<',300001)
        ->get();

      
        foreach($items as $key => $item)
        {

            $urls[] = [
                'loc' => route('page.category.city', [
                    'city_slug' => $item->city_slug,
                    'category_slug' => $item->category_slug,
                ]),
                'lastmod' => optional($item->updated_at)->toAtomString(),
            ];
        }

        $content = view('sitemap.xml', ['urls' => $urls]);

        return Response::make($content, 200)->header('Content-Type', 'application/xml');
    }

    // Dynamic sitemap methods for different content types

    public function pagesSitemap($part = 1)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300); // 5 minutes
        
        // Validate part number
        $totalParts = $this->getPageSitemapParts();
        if ($part < 1 || $part > $totalParts) {
            abort(404, 'Sitemap part not found');
        }
        
        $urls = [];
        $limit = self::PAGE_SITEMAP_LIMIT; // URLs per sitemap file
        $offset = ($part - 1) * $limit;

        $pages = $this->pageSitemapQuery()
            ->select(
                'pages.id',
                'pages.item_slug',
                'pages.updated_at',
                'pages.description',
                'pages.why_visit_us',
                'pages.our_story',
                'pages.policy',
                'cities.city_slug',
                'areas.area_slug',
                'pagecategories.category_slug'
            )
            ->orderBy('pages.id')
            ->limit($limit)
            ->offset($offset)
            ->get();

        foreach($pages as $page) {
            if (!$this->hasMeaningfulContent(
                $page->description,
                $page->why_visit_us,
                $page->our_story,
                $page->policy
            ) || !$page->city_slug || !$page->area_slug || !$page->category_slug || !$page->item_slug) {
                continue;
            }

            $urls[] = [
                'loc' => route('single.page', [
                    'city_slug' => $page->city_slug,
                    'area_slug' => $page->area_slug,
                    'category_slug' => $page->category_slug,
                    'item_slug' => $page->item_slug
                ]),
                'lastmod' => optional($page->updated_at)->format('c'),
                'priority' => '0.8',
                'changefreq' => 'weekly'
            ];
        }

        $content = view('sitemap.xml', ['urls' => $urls]);
        return Response::make($content, 200)->header('Content-Type', 'application/xml');
    }

    public function eventsSitemap()
    {
        ini_set('memory_limit', '512M');
        $urls = [];

        $events = Event::where('event_status', 2)
            ->where('privacy', 'public')
            ->with(['city', 'area', 'category'])
            ->select('id', 'title', 'updated_at', 'event_slug', 'city_id', 'area_id', 'category_id')
            ->get();

        foreach($events as $event) {
            // Skip if any required relationship or slug is missing
            if (!$event->city || !$event->area || !$event->category || !$event->event_slug) {
                continue;
            }

            $urls[] = [
                'loc' => route('single.event', [
                    'city_slug' => $event->city->city_slug,
                    'area_slug' => $event->area->area_slug,
                    'category_slug' => $event->category->category_slug,
                    'event_slug' => $event->event_slug
                ]),
                'lastmod' => optional($event->updated_at)->toAtomString(),
                'priority' => '0.7',
                'changefreq' => 'daily'
            ];
        }

        $content = view('sitemap.xml', ['urls' => $urls]);
        return Response::make($content, 200)->header('Content-Type', 'application/xml');
    }

    public function marketplaceSitemap()
    {
        ini_set('memory_limit', '512M');
        $urls = [];

        $products = Marketplace::where('product_status', 2)
            ->with(['page.city', 'page.area', 'page.categories', 'productCategories'])
            ->select('id', 'title', 'updated_at', 'product_slug', 'page_id')
            ->get();

        foreach($products as $product) {
            $page = $product->page;
            if (!$page) continue;

            $city = $page->city;
            $area = $page->area;
            $itemCategory = $page->categories->last(); // Get the primary category
            $productCategory = $product->productCategories->last();

            // Skip if any required slug is missing
            if (!$city || !$area || !$itemCategory || !$productCategory) {
                continue;
            }

            $urls[] = [
                'loc' => route('single.product', [
                    'city_slug' => $city->city_slug,
                    'area_slug' => $area->area_slug,
                    'category_slug' => $itemCategory->category_slug,
                    'item_slug' => $page->item_slug,
                    'product_category_slug' => $productCategory->product_category_slug,
                    'product_slug' => $product->product_slug
                ]),
                'lastmod' => optional($product->updated_at)->toAtomString(),
                'priority' => '0.8',
                'changefreq' => 'weekly'
            ];
        }

        $content = view('sitemap.xml', ['urls' => $urls]);
        return Response::make($content, 200)->header('Content-Type', 'application/xml');
    }

    public function blogsSitemap()
    {
        ini_set('memory_limit', '512M');
        $urls = [];

        $blogs = Blog::where('blog_status', 2)
            ->whereNotNull('blog_slug')
            ->whereRaw("TRIM(blog_slug) <> ''")
            ->whereNotNull('description')
            ->whereRaw("TRIM(description) <> ''")
            ->with([
                'categories:id,category_slug',
                'city:id,city_slug',
                'area:id,area_slug',
            ])
            ->select('id', 'title', 'updated_at', 'blog_slug', 'city_id', 'area_id', 'description')
            ->get();

        foreach($blogs as $blog) {
            $category = $blog->categories->first(); // Get the first category
            
            // Skip if blog_slug or category is missing
            if (!$category || !$category->category_slug || !$this->hasMeaningfulContent($blog->description)) {
                continue;
            }

            $urls[] = [
                'loc' => route('single.blog', [
                    'category_slug' => $category->category_slug,
                    'blog_slug' => $blog->blog_slug,
                    'city_slug' => $blog->city->city_slug ?? null,
                    'area_slug' => $blog->area->area_slug ?? null
                ]),
                'lastmod' => optional($blog->updated_at)->toAtomString(),
                'priority' => '0.7',
                'changefreq' => 'monthly'
            ];
        }

        $content = view('sitemap.xml', ['urls' => $urls]);
        return Response::make($content, 200)->header('Content-Type', 'application/xml');
    }

    public function videosSitemap()
    {
        ini_set('memory_limit', '512M');
        $urls = [];

        $videos = Video::where('privacy', 'public')
            ->select('id', 'title', 'updated_at')
            ->get();

        foreach($videos as $video) {
            $urls[] = [
                'loc' => route('video.detail.info', ['id' => $video->id]),
                'lastmod' => optional($video->updated_at)->toAtomString(),
                'priority' => '0.6',
                'changefreq' => 'weekly'
            ];
        }

        $content = view('sitemap.xml', ['urls' => $urls]);
        return Response::make($content, 200)->header('Content-Type', 'application/xml');
    }

    public function postsSitemap()
    {
        ini_set('memory_limit', '512M');
        $urls = [];

        $posts = Posts::where('privacy', 'public')
            ->where('post_type', 'general')
            ->where('publisher', 'post')
            ->select('post_id', 'description', 'created_at')
            ->orderBy('post_id', 'DESC')
            ->get();

        foreach($posts as $post) {
            $urls[] = [
                'loc' => route('single.post', ['id' => $post->post_id]),
                'lastmod' => optional($post->created_at)->format('c'),
                'priority' => '0.5',
                'changefreq' => 'daily'
            ];
        }

        $content = view('sitemap.xml', ['urls' => $urls]);
        return Response::make($content, 200)->header('Content-Type', 'application/xml');
    }

    public function groupsSitemap()
    {
        ini_set('memory_limit', '512M');
        $urls = [];

        $groups = Group::where('privacy', 'public')
            ->select('id', 'title', 'updated_at')
            ->get();

        foreach($groups as $group) {
            $urls[] = [
                'loc' => route('single.group.details', ['id' => $group->id]),
                'lastmod' => optional($group->updated_at)->toAtomString(),
                'priority' => '0.6',
                'changefreq' => 'weekly'
            ];
        }

        $content = view('sitemap.xml', ['urls' => $urls]);
        return Response::make($content, 200)->header('Content-Type', 'application/xml');
    }

    public function staticSitemap()
    {
        $urls = [];

        // Add static pages
        $staticRoutes = [
            ['route' => 'timeline', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['route' => 'allproducts', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['route' => 'pages', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['route' => 'groups', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['route' => 'event', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['route' => 'blogs', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['route' => 'videos', 'priority' => '0.7', 'changefreq' => 'weekly'],
        ];

        foreach($staticRoutes as $routeInfo) {
            $urls[] = [
                'loc' => route($routeInfo['route']),
                'lastmod' => now()->toAtomString(),
                'priority' => $routeInfo['priority'],
                'changefreq' => $routeInfo['changefreq']
            ];
        }

        $content = view('sitemap.xml', ['urls' => $urls]);
        return Response::make($content, 200)->header('Content-Type', 'application/xml');
    }

}
