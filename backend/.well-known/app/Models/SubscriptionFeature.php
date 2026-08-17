<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionFeature extends Model
{
    use HasFactory;

    protected $fillable = ['feature_name'];

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'subscription_feature_mappings')
                    ->withPivot('value')
                    ->withTimestamps();
    }
}
