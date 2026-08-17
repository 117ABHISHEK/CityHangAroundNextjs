<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Area extends Model
{
    

    use HasFactory;
    use Searchable;


    protected $fillable = ['city_id', 'area_name', 'area_slug', 'createdBy', 'is_approved'];


    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function creator()
{
    return $this->belongsTo(\App\Models\User::class, 'createdBy');
}

public function pages()
{
    return $this->hasMany(\App\Models\Page::class, 'area_id');
}
public function events()
    {
        return $this->hasMany(Event::class, 'area_id');
    }

    // ✅ Add this custom relationship using HasManyThrough
public function marketplaces()
{
    return $this->hasManyThrough(
        Marketplace::class,
        Page::class,
        'area_id',        // Foreign key on Page table
        'page_id',        // Foreign key on Marketplace table
        'id',             // Local key on Area
        'id'              // Local key on Page
    );
}

}
