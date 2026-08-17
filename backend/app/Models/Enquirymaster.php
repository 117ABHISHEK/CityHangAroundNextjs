<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne; 
use DB;
class Enquirymaster extends Model
{
    use HasFactory;

    protected $table = 'enquirymaster';
    
    protected $fillable = ['name', 'mobileno', 'cityid', 'productid', 'createdAt', 'userid'];

    public function marketplace()
    {
        return $this->belongsTo(Marketplace::class, 'productid', 'id');
    }
    public function purchases()
{
    return $this->hasMany(LeadPurchases::class, 'lead_id');
}

public function category()
{
    return $this->belongsTo(Category::class, 'category_id', 'id');
}

// Add relation to categories through Marketplace
    public function categories()
    {
        return $this->hasManyThrough(Category::class, Marketplace::class, 'id', 'id', 'productid', 'category_id');
    }

    public function buyerLeadStage(): HasOne
    {
        return $this->hasOne(BuyerLeadStage::class, 'enquiry_id', 'id');
    }



}
