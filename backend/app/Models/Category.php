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

    // Cannot use standard hasMany — marketplaces.category is CSV text, not a proper FK
    // Returns a query builder for marketplaces that contain this category ID
    public function marketplaces()
    {
        return Marketplace::whereRaw("CAST(? AS text) = ANY(string_to_array(category, ',')", [$this->id]);
    }



    public function getMarketplacesAttribute()
    {
        return Marketplace::whereRaw("categories.id = ANY(string_to_array(marketplaces.category, ',')::bigint[])")
            ->select('marketplaces.*')
            ->get();
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
