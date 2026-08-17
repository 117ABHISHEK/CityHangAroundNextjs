<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class MarketplaceConversation extends Model
{
    protected $fillable = ['marketplace_id', 'user_id'];

   public function marketplace()
{
    return $this->belongsTo(Marketplace::class, 'marketplace_id');
}


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(MarketplaceMessage::class, 'conversation_id');
    }
}
