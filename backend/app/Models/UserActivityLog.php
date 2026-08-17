<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $table = 'user_activity_logs';

    protected $fillable = [
        'user_id',
        'event_name',
        'content_type',
        'content_id',
        'activity_id',
        'score',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // In UserActivityLog.php
        public function city()
        {
            return $this->belongsTo(\App\Models\City::class, 'city_id');
        }

        public function post()
{
    return $this->belongsTo(Post::class, 'content_id');
}
public function page()
{
    return $this->belongsTo(Page::class, 'content_id');
}
public function pages()
{
    return $this->belongsTo(Page::class, 'content_id')->where('content_type', 'page');
}

public function product()
{
    return $this->belongsTo(Marketplace::class, 'content_id')->where('content_type', 'product');
}

public function blog()
{
    return $this->belongsTo(Blog::class, 'content_id')->where('content_type', 'blog');
}

public function group()
{
    return $this->belongsTo(Group::class, 'content_id')->where('content_type', 'group');
}
public function event()
{
    return $this->belongsTo(Event::class, 'content_id');
}
}
