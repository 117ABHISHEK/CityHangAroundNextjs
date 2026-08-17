<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryView extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'viewer_id',
        'viewed_at',
    ];

    public function story()
    {
        return $this->belongsTo(Stories::class, 'story_id', 'story_id');
    }

    public function viewer()
    {
        return $this->belongsTo(User::class, 'viewer_id', 'id');
    }
}
