<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'privacy',
        'file',
        'view',
        'description'
    ];

    public function getUser(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    // Get thumbnail from video file (assuming first frame or default image)
    public function getThumbnailAttribute()
    {
        // If there's a dedicated thumbnail field, use it
        if (isset($this->attributes['thumbnail'])) {
            return $this->attributes['thumbnail'];
        }
        
        // Otherwise, return a default video thumbnail
        return 'default-video.png';
    }
}
