<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class State extends Model
{
    use HasFactory;
  use Searchable;

    protected $table = 'states'; 
    protected $fillable = [
        'country_id', 
        'state_name', 
        'state_abbr', 
        'state_slug', 
        'state_country_abbr',
        'createdby',
        'is_approved'
    ];

    /**
     * Relationship with Country
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * Relationship with Cities
     */
    public function cities()
    {
        return $this->hasMany(City::class, 'state_id');
    }

    /**
     * Relationship with User (creator)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'createdby');
    }

    /**
     * Scope to filter approved states
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', 'Y');
    }
}
