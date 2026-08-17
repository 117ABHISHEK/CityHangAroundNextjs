<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IncompleteListing extends Model
{
    use HasFactory;

    // In IncompleteListing model
protected $fillable = ['user_id', 'data', 'listing_id'];


    protected $casts = [
        'data' => 'object',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
