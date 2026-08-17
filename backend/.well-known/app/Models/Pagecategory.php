<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Pagecategory extends Model
{
    use HasFactory;
  use Searchable;


    public function pages()
{
    return $this->belongsToMany(Page::class, 'page_category', 'category_id', 'page_id');
}
public function pagesCount()
{
    return $this->pages()->count();
}

public function parentCategory() {
    return $this->belongsTo(Pagecategory::class, 'category_parent_id');
}

public function childCategories() {
    return $this->hasMany(Pagecategory::class, 'category_parent_id');
}

public function creator() {
    return $this->belongsTo(User::class, 'category_createdby');
}

public function pageCategories()
{
    return $this->belongsToMany(Category::class, 'page_category', 'page_id', 'category_id');
}



}
