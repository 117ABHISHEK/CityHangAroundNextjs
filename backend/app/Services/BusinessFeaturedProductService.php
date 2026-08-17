<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BusinessFeaturedProductService
{
    private const CACHE_VERSION = 'v1';

    public function forBusiness(Page $business, int $limit = 8): Collection
    {
        $limit = max(1, min($limit, 10));
        [$categoryIds, $parentFamilyIds] = $this->categoryContext($business);

        $cacheKey = implode('_', [
            'business_featured_products',
            self::CACHE_VERSION,
            $business->id,
            $business->city_id ?: 0,
            $business->area_id ?: 0,
            md5(implode(',', $categoryIds)),
            md5(implode(',', $parentFamilyIds)),
            $limit,
        ]);

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use (
            $business,
            $categoryIds,
            $parentFamilyIds,
            $limit
        ) {
            $baseQuery = $this->baseQuery();

            // Priority 1: exact business category and exact area.
            if ($business->area_id && $categoryIds !== []) {
                $products = $this->withBusinessCategories(
                    (clone $baseQuery)->where('business_pages.area_id', $business->area_id),
                    $categoryIds
                )->limit($limit)->get();

                if ($products->isNotEmpty()) {
                    return $products;
                }
            }

            // Priority 2: exact business category and city.
            if ($business->city_id && $categoryIds !== []) {
                $products = $this->withBusinessCategories(
                    (clone $baseQuery)->where('business_pages.city_id', $business->city_id),
                    $categoryIds
                )->limit($limit)->get();

                if ($products->isNotEmpty()) {
                    return $products;
                }
            }

            // Priority 3: sibling/child business categories in the same city.
            if ($business->city_id && $parentFamilyIds !== []) {
                $products = $this->withBusinessCategories(
                    (clone $baseQuery)->where('business_pages.city_id', $business->city_id),
                    $parentFamilyIds
                )->limit($limit)->get();

                if ($products->isNotEmpty()) {
                    return $products;
                }
            }

            // Priority 4: featured products from another category, but still local.
            if ($business->city_id) {
                $products = (clone $baseQuery)
                    ->where('business_pages.city_id', $business->city_id)
                    ->where('marketplaces.item_featured', 1)
                    ->limit($limit)
                    ->get();

                if ($products->isNotEmpty()) {
                    return $products;
                }
            }

            // Priority 5: global published products, used only when every local tier is empty.
            return (clone $baseQuery)->limit($limit)->get();
        });
    }

    private function categoryContext(Page $business): array
    {
        $categoryIds = DB::table('page_category')
            ->where('page_id', $business->id)
            ->pluck('category_id')
            ->merge(
                collect(explode(',', (string) $business->category_id))
                    ->map(static fn ($id) => (int) trim($id))
                    ->filter()
            )
            ->map(static fn ($id) => (int) $id)
            ->unique()
            ->sort()
            ->values();

        if ($categoryIds->isEmpty()) {
            return [[], []];
        }

        $categories = DB::table('pagecategories')
            ->whereIn('id', $categoryIds)
            ->get(['id', 'category_parent_id']);

        $parentIds = $categories
            ->pluck('category_parent_id')
            ->filter()
            ->map(static fn ($id) => (int) $id)
            ->unique()
            ->values();

        $parentFamilyIds = DB::table('pagecategories')
            ->where(function (Builder $query) use ($categoryIds, $parentIds) {
                $query->whereIn('category_parent_id', $categoryIds);

                if ($parentIds->isNotEmpty()) {
                    $query->orWhereIn('category_parent_id', $parentIds)
                        ->orWhereIn('id', $parentIds);
                }
            })
            ->whereNotIn('id', $categoryIds)
            ->pluck('id')
            ->map(static fn ($id) => (int) $id)
            ->unique()
            ->sort()
            ->values()
            ->all();

        return [$categoryIds->all(), $parentFamilyIds];
    }

    private function baseQuery(): Builder
    {
        return DB::table('marketplaces')
            ->join('pages as business_pages', function ($join) {
                $join->on('business_pages.id', '=', 'marketplaces.page_id')
                    ->where('business_pages.item_status', 2);
            })
            ->join('cities', 'cities.id', '=', 'business_pages.city_id')
            ->leftJoin('areas', 'areas.id', '=', 'business_pages.area_id')
            ->leftJoin('pagecategories as primary_page_category', function ($join) {
                $join->on('primary_page_category.id', '=', DB::raw("ANY(string_to_array(business_pages.category_id, ',')::bigint[])"));
            })
            ->leftJoin('currencies', 'currencies.id', '=', 'marketplaces.currency_id')
            ->where('marketplaces.product_status', 2)
            ->select([
                'marketplaces.id',
                'marketplaces.title',
                'marketplaces.product_slug',
                'marketplaces.image',
                'marketplaces.category',
                'marketplaces.product_selling_price',
                'currencies.symbol as currency_symbol',
                'cities.city_slug',
                'cities.city_name',
                'areas.area_slug',
                'areas.area_name',
                'primary_page_category.category_slug as page_category_slug',
                'business_pages.item_slug as page_slug',
                'marketplaces.item_featured',
                'marketplaces.created_at',
                DB::raw('(SELECT categories.product_category_slug
                    FROM category_product
                    INNER JOIN categories ON categories.id = category_product.product_category_id
                    WHERE category_product.product_id = marketplaces.id
                    ORDER BY category_product.id
                    LIMIT 1) as product_category_slug'),
            ])
            ->distinct()
            ->orderByDesc('marketplaces.item_featured')
            ->orderByDesc('marketplaces.created_at')
            ->orderByDesc('marketplaces.id');
    }

    private function withBusinessCategories(Builder $query, array $categoryIds): Builder
    {
        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));
        return $query->where(function (Builder $categoryQuery) use ($placeholders, $categoryIds) {
            $categoryQuery
                ->whereRaw("EXISTS (SELECT 1 FROM unnest(string_to_array(business_pages.category_id, ',')::bigint[]) AS x(val) WHERE val IN ($placeholders))", $categoryIds)
                ->orWhereExists(function (Builder $pivotQuery) use ($categoryIds) {
                    $pivotQuery->selectRaw('1')
                        ->from('page_category')
                        ->whereColumn('page_category.page_id', 'business_pages.id')
                        ->whereIn('page_category.category_id', $categoryIds);
                });
        });
    }
}
