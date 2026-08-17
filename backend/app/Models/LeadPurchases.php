<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadPurchases extends Model
{
    use HasFactory;
    protected $table = 'lead_purchases';
    protected $fillable = ['lead_id', 'user_id', 'purchased_at','transactionid'];
    public $timestamps = false; // 
    public function lead()
    {
        return $this->belongsTo(Enquirymaster::class, 'lead_id');
    }
}
