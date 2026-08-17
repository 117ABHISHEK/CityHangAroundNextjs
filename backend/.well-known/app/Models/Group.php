<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public function getMember(){
        return $this->hasMany(Group_member::class,'group_id');
    }

    // In App\Models\Group.php

public function category()
{
    return $this->belongsTo(Groupcategory::class, 'category_id');
}

public function city()
{
    return $this->belongsTo(City::class, 'city_id');
}

public function area()
{
    return $this->belongsTo(Area::class, 'area_id');
}
public function categories()
{
    return $this->belongsToMany(Groupcategory::class, 'group_category', 'group_id', 'category_id');
}
// Group.php

public function getLastCategoryAttribute()
{
    $ids = explode(',', $this->category_id);
    $lastId = end($ids);

    return Groupcategory::find($lastId);
}
public function lastCategory()
{
    return $this->belongsTo(Groupcategory::class, 'category_id');
}

// Group.php
public function groupCategories()
{
    return $this->belongsToMany(Groupcategory::class, 'group_category', 'group_id', 'category_id');
}


}
