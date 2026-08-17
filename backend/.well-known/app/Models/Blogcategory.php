<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Blogcategory extends Model
{
    use HasFactory;
  use Searchable;



   public function blogs()
{
    return $this->belongsToMany(Blog::class, 'blog_category', 'category_id', 'blog_id');
}


}
