<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groupcategory extends Model
{
    use HasFactory;

    protected $table = 'groupcategories';

    protected $fillable = ['category_name', 'category_parent_id'];
    public function parent()
    {
        return $this->belongsTo(GroupCategory::class, 'category_parent_id');
    }

    public function groups()
{
    return $this->hasMany(Group::class, 'category_id');
}

}
