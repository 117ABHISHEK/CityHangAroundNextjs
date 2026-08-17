<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionFeatureMapping extends Model
{
    use HasFactory;

    protected $fillable = ['subscription_id', 'feature_id', 'value']; 

    // Relationship with Subscription
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    // Relationship with Feature
    public function feature()
    {
        return $this->belongsTo(SubscriptionFeature::class, 'feature_id');
    }
}

