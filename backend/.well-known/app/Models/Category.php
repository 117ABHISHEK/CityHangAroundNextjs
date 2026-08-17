<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Category extends Model
{
    use HasFactory;
  use Searchable;

    protected $table = 'categories';
    protected $fillable = [
        'product_category_name',
        'category_parent_id',
        'lead_price' // ✅ Allow mass assignment for lead_price
    ];

    public function parent()
{
    return $this->belongsTo(Category::class, 'category_parent_id');
}

    // Relationship: Get subcategories (children)
    public function children()
    {
        return $this->hasMany(Category::class, 'category_parent_id');
    }

    // Recursive function to update lead_price for all subcategories
    public function updateLeadPriceRecursively($newPrice)
    {
        // Update the current category
        $this->update(['lead_price' => $newPrice]);

        // Recursively update all subcategories
        foreach ($this->children as $child) {
            $child->updateLeadPriceRecursively($newPrice);
        }
    }
    public function subcategories()
{
    return $this->hasMany(Category::class, 'category_parent_id');
}


public function enquiries()
    {
        return $this->hasMany(Enquirymaster::class, 'productid', 'id');
    }

    public function marketplaces()
{
    return $this->hasMany(Marketplace::class, 'category', 'id'); 
}



public function attributes() {
    return $this->hasMany(Attribute::class);
}

public function products() {
    return $this->hasMany(Product::class);
}

public function pivotMarketplaces()
{
    return $this->belongsToMany(Marketplace::class, 'category_product', 'product_category_id', 'product_id');
}
public function creator()
{
    return $this->belongsTo(User::class, 'product_category_createdby');
}


public function productCategories()
{
    return $this->hasMany(CategoryProduct::class, 'product_category_id');
}

}
