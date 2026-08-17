<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marketplace extends Model
{
    use HasFactory;

    public function getUser(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function getCategory(){
        return $this->belongsTo(Category::class,'category');
    }
    public function getBrand(){
        return $this->belongsTo(Brand::class,'brand');
    }

    public function getCurrency(){
        return $this->belongsTo(Currency::class,'currency_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    
//     public function categories()
// {
//     return Category::whereIn('id', explode(',', $this->category))->get();
// }

    

// public function page()
// {
//     return $this->belongsTo(Page::class, 'page_id', 'id');
// }
public function page()
{
    return $this->belongsTo(Page::class, 'page_id');
}
// public function categories()
// {
//     return Category::whereIn('id', explode(',', $this->category))->get();
// }
public function categories()
{
    return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'product_category_id');
}

public function city()
{
    return $this->belongsTo(City::class, 'cityid');
}

public function category()
{
    return $this->belongsTo(Category::class, 'category'); // Ensure 'category' is the correct foreign key
}
public function getMasterCategoryLeadPriceAttribute()
{
    // Get the first category ID from the comma-separated list
    $categoryId = explode(',', $this->category)[0] ?? null;

    if (!$categoryId) {
        return null; // Return null if no category found
    }

    // Fetch lead price for the master category
    return Category::where('id', $categoryId)->value('lead_price');
}


public function enquiries()
    {
        return $this->hasMany(Enquirymaster::class);
    }

public function getCategoryObjectsAttribute()
{
    // Convert comma-separated category IDs into an array
    $categoryIds = explode(',', $this->category); 

    // Fetch categories from the database
    return Category::whereIn('id', $categoryIds)->get();
}

public function product()
    {
        return $this->belongsTo(Product::class, 'productid');
    }




   
    
    public function attributeValues() {
        return $this->hasMany(AttributeValue::class);
    }

   
    public function reviews()
{
    return $this->hasMany(Review::class, 'marketplace_id');
}

    
    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }
    public function productCategories()
{
    return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'product_category_id');
}
    


}
