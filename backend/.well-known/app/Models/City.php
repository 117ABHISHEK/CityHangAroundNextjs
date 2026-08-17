<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class City extends Model
{
    use HasFactory;
  use Searchable;


    protected $table = 'cities'; // Table name

    protected $fillable = [
        'state_id',
        'city_name',
        'city_state', // ✅ Add this if missing
        'city_slug',
        'city_lat',
        'city_lng',
        'city_image',
        'city_about',
        'description',
        'createdBy',
        'is_approved'
    ];
    

    public function areas()
    {
        return $this->hasMany(Area::class, 'city_id');
    }

    /**
     * Relationship with State (if applicable)
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    /**
     * Relationship with User (if applicable)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }

    /**
     * Scope to filter approved cities
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', 'Y');
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function marketplaces()
    {
        return $this->hasManyThrough(Marketplace::class, Page::class);
    }

    public function enquiries()
    {
        return $this->hasManyThrough(Enquirymaster::class, Marketplace::class, 'city_id', 'marketplace_id');
    }

    public function events()
{
    return $this->hasMany(Event::class, 'city_id', 'id');
}



}
