<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'duration', 'offer_price','offered_services','offered_cities', 'area_durations'];

    public function features()
    {
        return $this->belongsToMany(SubscriptionFeature::class, 'subscription_feature_mappings', 'subscription_id', 'feature_id')
                    ->withPivot('value')
                    ->withTimestamps();
    }

    // App\Models\Subscription.php
public function hasListingAccessForPage(Page $page): bool
{
    $areaDurations = json_decode($this->area_durations, true);
    $cityId = (string) $page->city_id;
    $areaId = (string) $page->area_id;

    if (!isset($areaDurations['listings'])) return false;

    foreach ($areaDurations['listings'] as $entity) {
        if (
            (isset($entity['city']) && $entity['city'] === $cityId) ||
            (isset($entity[$areaId]) && $entity[$areaId] > 0)
        ) {
            return true;
        }
    }

    return false;
}

}
