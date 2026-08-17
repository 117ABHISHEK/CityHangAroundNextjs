<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stories extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'story_id';

    protected $keyType = 'int';

    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'story_id',
        'user_id',
        'publisher',
        'publisher_id',
        'privacy',
        'content_type',
        'description',
        'created_at',
        'updated_at',
        'expires_at',
        'status',
    ];

    public function mediaFiles()
    {
        return $this->hasMany(Media_files::class, 'story_id', 'story_id');
    }

    public function views()
    {
        return $this->hasMany(StoryView::class, 'story_id', 'story_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
