<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'amount', 'transaction_id', 'bank_name', 
        'payment_method', 'status', 'type', 'description','user_subscription_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
