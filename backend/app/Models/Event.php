<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Event extends Model
{
    use HasFactory;
    use Searchable;


    public function getUser(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function inviteEvent(){
        return $this->hasMany(Invite::class,'event_id');
    }

    
public function categories()
{
    return $this->belongsToMany(Eventcategory::class, 'event_category', 'event_id', 'category_id');
}

public function city()
{
    return $this->belongsTo(City::class, 'city_id');
}

public function area()
{
    return $this->belongsTo(Area::class, 'area_id');
}

public function events()
{
    return $this->hasMany(Events::class, 'city_id', 'id');
}
public function lastCategory()
{
    return $this->categories->last();
}

   public function user()
{
    return $this->belongsTo(User::class);
}
// In App\Models\Event
public function state()
{
    return $this->belongsTo(State::class, 'state_id');
}
public function category()
{
    return $this->belongsTo(Eventcategory::class, 'category_id');
}

}
