<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'marketplace_id', 'rating', 'review','type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

    public function marketplace()
    {
        return $this->belongsTo(Marketplace::class);
    }
}
