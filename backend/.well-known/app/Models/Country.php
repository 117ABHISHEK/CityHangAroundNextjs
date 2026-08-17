<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';

    protected $fillable = [
        'country_name',
        'country_code',
        'country_abbr',
        'country_slug',
        'country_flag',
        'country_about',
        'description',
        'createdBy',
        'is_approved'
    ];

    /**
     * Relationship with States
     */
    public function states()
    {
        return $this->hasMany(State::class, 'country_id');
    }

    /**
     * Relationship with Cities (through states)
     */
    public function cities()
    {
        return $this->hasManyThrough(City::class, State::class, 'country_id', 'state_id');
    }

    /**
     * Relationship with User (creator)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }

    /**
     * Scope to filter approved countries
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', 'Y');
    }

    /**
     * Get total pages count for this country
     */
    public function getPagesCountAttribute()
    {
        return $this->cities()->withCount('pages')->get()->sum('pages_count');
    }
}
