<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'user_id',
        'full_name',
        'email',
        'phone',
        'business_name',
        'business_address',
        'ownership_proof',
        'additional_comments',
    ];

    public function page()
{
    return $this->belongsTo(Page::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}

}
