<?php

namespace App\Services;

use App\Models\Pagecategory;
use Illuminate\Support\Facades\Cache;

class CategoryCitySeoService
{
    public function description(Pagecategory $category, $city): string
    {
        $categoryName = $this->formatLabel($category->category_name);
        $cityName = $this->formatLabel($city->city_name);

        $childCategoryNames = Cache::remember("category_city_meta_children_v2_{$category->id}", 3600, function () use ($category) {
            return $category->childCategories()
                ->where('is_approved', 'Y')
                ->whereNotNull('category_name')
                ->whereRaw("TRIM(category_name) <> ''")
                ->orderBy('id')
                ->pluck('category_name')
                ->map(fn ($name) => $this->formatLabel($name))
                ->filter()
                ->unique(fn ($name) => mb_strtolower($name))
                ->take(4)
                ->values()
                ->all();
        });

        if (empty($childCategoryNames)) {
            return "Discover the best {$categoryName} in {$cityName} on CityHangAround.";
        }

        return "Discover the best {$categoryName} in {$cityName}. Find " . implode(', ', $childCategoryNames) . ' on CityHangAround.';
    }

    private function formatLabel($value): string
    {
        $value = trim(preg_replace('/\s+/', ' ', (string) $value));
        if ($value === '') return $value;

        return $value === mb_strtolower($value)
            ? mb_convert_case($value, MB_CASE_TITLE, 'UTF-8')
            : $value;
    }
}
