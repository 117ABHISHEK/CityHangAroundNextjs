<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;

    public $timestamps = false;
protected $primaryKey = 'post_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id', 'user_id', 'publisher', 'publisher_id', 'post_type', 'privacy', 'tagged_user_ids', 'feel_and_activity', 'location', 'description', 'user_reacts', 'status', 'created_at', 'updated_at'
    ];

    public function getUser(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function media_files(){
        return $this->hasMany(Media_files::class, 'post_id', 'post_id');
    }
    public function publisherModel()
    {
        return $this->morphTo(null, 'publisher', 'publisher_id');
    }
public function page()
{
    return $this->belongsTo(\App\Models\Page::class, 'publisher_id');
}
// In App\Models\Posts.php
public function group()
{
    return $this->belongsTo(Group::class, 'publisher_id');
}
public function event()
{
    return $this->belongsTo(Event::class, 'publisher_id');
}

    /**
     * Automatically extract location (city/area) and product from the description,
     * route the post to the appropriate community group, and append metadata badges.
     */
    public static function autoRouteAndTagPost(&$data)
    {
        $description = $data['description'] ?? '';
        if (empty($description)) {
            return;
        }

        // 1. Tokenize description into 1, 2, and 3 word ngrams
        $cleanText = strtolower(preg_replace('/[^a-zA-Z0-9\s]/', ' ', $description));
        $words = array_values(array_filter(explode(' ', $cleanText)));
        if (empty($words)) {
            return;
        }

        $tokens = [];
        $count = count($words);
        for ($i = 0; $i < $count; $i++) {
            $tokens[] = $words[$i];
            if ($i < $count - 1) {
                $tokens[] = $words[$i] . '-' . $words[$i + 1];
            }
            if ($i < $count - 2) {
                $tokens[] = $words[$i] . '-' . $words[$i + 1] . '-' . $words[$i + 2];
            }
        }
        $tokens = array_unique($tokens);

        // 2. Fetch all matching areas and cities using whereIn (Indexed & Optimized)
        $matchedCities = \Illuminate\Support\Facades\DB::table('cities')
            ->whereIn('city_slug', $tokens)
            ->where('is_approved', 'Y')
            ->get();

        $matchedAreas = \Illuminate\Support\Facades\DB::table('areas')
            ->whereIn('area_slug', $tokens)
            ->where('is_approved', 'Y')
            ->get();

        $city = null;
        $area = null;

        if ($matchedCities->isNotEmpty()) {
            if ($matchedAreas->isNotEmpty()) {
                // Try to find an area that belongs to one of the matched cities
                $cityIds = $matchedCities->pluck('id')->toArray();
                foreach ($matchedAreas as $ma) {
                    if (in_array($ma->city_id, $cityIds)) {
                        $area = $ma;
                        $city = $matchedCities->firstWhere('id', $ma->city_id);
                        break;
                    }
                }
            }
            
            // If no area matched the city, just take the first matched city
            if (!$city) {
                $city = $matchedCities->first();
            }
        } elseif ($matchedAreas->isNotEmpty()) {
            // No city matched directly, so use the first matched area and fetch its city
            $area = $matchedAreas->first();
            $city = \Illuminate\Support\Facades\DB::table('cities')
                ->where('id', $area->city_id)
                ->first();
        }

        // 3. Fetch Product / Category Name
        $productMatch = null;
        
        $categoryMatch = \Illuminate\Support\Facades\DB::table('categories')
            ->whereIn('product_category_slug', $tokens)
            ->first();
            
        if ($categoryMatch) {
            $productMatch = $categoryMatch->product_category_name;
        } else {
            $p = \Illuminate\Support\Facades\DB::table('marketplaces')
                ->whereIn('product_slug', $tokens)
                ->first();
            if ($p) {
                $productMatch = $p->title;
            } else {
                $commonProducts = ['iphone', 'ipad', 'macbook', 'samsung', 'oneplus', 'mobile', 'phone', 'laptop', 'car', 'bike', 'tv', 'fridge', 'shoes', 'clothes'];
                foreach ($commonProducts as $cp) {
                    if (in_array($cp, $tokens)) {
                        $productMatch = ucfirst($cp);
                        break;
                    }
                }
            }
        }

        // 4. Route to Area/City Group (Community)
        if ($city && isset($data['publisher']) && $data['publisher'] === 'post') {
            $groupId = null;
            if ($area) {
                $group = \Illuminate\Support\Facades\DB::table('groups')
                    ->where('area_id', $area->id)
                    ->where('group_status', 2)
                    ->first();
                if ($group) {
                    $groupId = $group->id;
                } else {
                    $groupSlug = \Illuminate\Support\Str::slug($area->area_name . '-community');
                    $existingSlugCount = \Illuminate\Support\Facades\DB::table('groups')
                        ->where('group_slug', 'like', $groupSlug . '%')
                        ->count();
                    if ($existingSlugCount > 0) {
                        $groupSlug .= '-' . rand(100, 999);
                    }

                    $groupId = \Illuminate\Support\Facades\DB::table('groups')->insertGetId([
                        'user_id' => '1',
                        'title' => $area->area_name . ' Community',
                        'subtitle' => 'Local community group for ' . $area->area_name . ', ' . $city->city_name,
                        'group_slug' => $groupSlug,
                        'category_id' => '2',
                        'state_id' => $city->state_id,
                        'city_id' => $city->id,
                        'area_id' => $area->id,
                        'group_status' => 2,
                        'status' => '1',
                        'privacy' => 'public',
                        'about' => '<p>Welcome to the ' . $area->area_name . ' Community group!</p>',
                        'item_featured' => 0
                    ]);

                    \Illuminate\Support\Facades\DB::table('group_category')->insert([
                        'group_id' => $groupId,
                        'category_id' => 2
                    ]);

                    \Illuminate\Support\Facades\DB::table('group_members')->insert([
                        'group_id' => $groupId,
                        'user_id' => 1,
                        'role' => 'admin',
                        'is_accepted' => '1',
                        'created_at' => time(),
                        'updated_at' => time()
                    ]);
                }
                $data['location'] = $area->area_name . ', ' . $city->city_name;
            } else {
                $group = \Illuminate\Support\Facades\DB::table('groups')
                    ->where('city_id', $city->id)
                    ->whereNull('area_id')
                    ->where('group_status', 2)
                    ->first();
                if ($group) {
                    $groupId = $group->id;
                } else {
                    $groupSlug = \Illuminate\Support\Str::slug($city->city_name . '-community');
                    $existingSlugCount = \Illuminate\Support\Facades\DB::table('groups')
                        ->where('group_slug', 'like', $groupSlug . '%')
                        ->count();
                    if ($existingSlugCount > 0) {
                        $groupSlug .= '-' . rand(100, 999);
                    }

                    $groupId = \Illuminate\Support\Facades\DB::table('groups')->insertGetId([
                        'user_id' => '1',
                        'title' => $city->city_name . ' Community',
                        'subtitle' => 'Local community group for ' . $city->city_name,
                        'group_slug' => $groupSlug,
                        'category_id' => '2',
                        'state_id' => $city->state_id,
                        'city_id' => $city->id,
                        'area_id' => null,
                        'group_status' => 2,
                        'status' => '1',
                        'privacy' => 'public',
                        'about' => '<p>Welcome to the ' . $city->city_name . ' Community group!</p>',
                        'item_featured' => 0
                    ]);

                    \Illuminate\Support\Facades\DB::table('group_category')->insert([
                        'group_id' => $groupId,
                        'category_id' => 2
                    ]);

                    \Illuminate\Support\Facades\DB::table('group_members')->insert([
                        'group_id' => $groupId,
                        'user_id' => 1,
                        'role' => 'admin',
                        'is_accepted' => '1',
                        'created_at' => time(),
                        'updated_at' => time()
                    ]);
                }
                $data['location'] = $city->city_name;
            }

            if ($groupId) {
                $data['publisher'] = 'group';
                $data['publisher_id'] = $groupId;
            }
        }

        // 5. Append Visual Badges to the Post Description
        if ($city || $productMatch) {
            $badgesHtml = '<br><br><div class="post-meta-badges mt-2 d-flex flex-wrap gap-1" style="font-family: inherit; font-size: 12px; margin-top: 10px;">';
            if ($city) {
                $locText = $area ? ($area->area_name . ', ' . $city->city_name) : $city->city_name;
                $badgesHtml .= '<span class="location-badge" style="background: #fff0eb; color: #ff4939; padding: 4px 10px; border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; border: 1px solid #ffe3db;"><i class="fas fa-map-marker-alt"></i> ' . e($locText) . '</span>';
            }
            if ($productMatch) {
                $badgesHtml .= '<span class="product-badge" style="background: #eef2ff; color: #4f46e5; padding: 4px 10px; border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; border: 1px solid #e0e7ff;"><i class="fas fa-shopping-bag"></i> ' . e($productMatch) . '</span>';
            }
            $badgesHtml .= '</div>';
            $data['description'] .= $badgesHtml;
        }
    }
}
