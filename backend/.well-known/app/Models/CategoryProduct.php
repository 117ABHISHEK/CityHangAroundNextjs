<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    protected $table = 'category_product'; // explicitly set if not default

    protected $fillable = [
        'product_id',
        'product_category_id',
        // add other fields if needed
    ];

    public function marketplace()
    {
        return $this->belongsTo(Marketplace::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'product_category_id');
    }
}
