<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    // Add category_id to the fillable array to allow mass assignment
    protected $fillable = [
        'name',           // other attributes you want to be mass assignable
        'category_id',    // add category_id here
    ];

    // Optionally, you can use guarded to specify fields you want to exclude from mass assignment
    // protected $guarded = ['id']; // Or, use this if you want to guard fields explicitly

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
