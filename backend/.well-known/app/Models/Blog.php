<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Blog extends Model
{
    use HasFactory;
  use Searchable;


    public function getUser(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function cagtegory(){
        return $this->belongsTo(Blogcategory::class,'category_id');
    }

    public function categories()
{
    return $this->belongsToMany(Blogcategory::class, 'blog_category', 'blog_id', 'category_id');
}
public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function category() {
    return $this->belongsTo(Blogcategory::class,'category_id');
}
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
public function lastCategory()
{
    return $this->categories->last();
}
public function lastCategories()
{
    return $this->belongsTo(Blogcategory::class, 'category_id'); // ya actual column jo bhi ho
}

}
