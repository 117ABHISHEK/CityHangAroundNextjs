<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'follow_id',
        'page_id',
        'group_id',
        'created_at',
        'updated_at'
    ];
    
    /**
     * Get the user who is following
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Get the user being followed
     */
    public function following()
    {
        return $this->belongsTo(User::class, 'follow_id');
    }
}
