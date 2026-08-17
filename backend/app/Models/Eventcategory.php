<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventcategory extends Model
{
    use HasFactory;



    public function parent()
{
    return $this->belongsTo(Eventcategory::class, 'category_parent_id');
}

public function children()
{
    return $this->hasMany(Eventcategory::class, 'category_parent_id');
}

public function creator()
{
    return $this->belongsTo(User::class, 'category_createdby');
}

public function events()
{
    return $this->belongsToMany(Event::class, 'event_category', 'category_id', 'event_id');
}
public function categories()
{
    return $this->belongsToMany(Eventcategory::class, 'event_category', 'event_id', 'category_id');
}



}
