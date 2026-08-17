<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Cache;
use Laravel\Scout\Searchable;



class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
  use Searchable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_role',
        'user_name',
        'nickname',
        'username',
        'gender',
        'friends',
        'followers',
        'studied_at',
        'profession',
        'job',
        'marital_status',
        'date_of_birth',
        'photo',
        'about',
        'phone',
        'address',
        'cover_photo',
        'status',
        'timezone',
        'lastactive',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // /**
    //  * The attributes that should be cast.
    //  *
    //  * @var array<string, string>
    //  */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];



    public function isOnline(){
        return Cache::has('user-is-online-'.$this->id);
    }

    public static function get_user_image($file_name = "", $optimized = ""){
        $optimized = $optimized.'/';
        if(base_path('public/storage/userimage/'.$optimized.$file_name) && is_file('public/storage/userimage/'.$optimized.$file_name)){
            return asset('storage/userimage/'.$optimized.$file_name);
        }else{
            return asset('storage/userimage/default.png');
        }
    }

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'user_id');
    }


    // app/Models/User.php
public function getTimezoneAttribute($value)
{
    return $value ?: config('app.timezone') ?: 'UTC';
}


public function updateScoreBasedOnActivity($activity)
{
    // Load scores from event_masters table
    $scoreRules = Cache::remember('event_scores', 3600, function () {
        return DB::table('event_masters')->pluck('score', 'event_name')->toArray();
    });

    $scoreToAdd = $scoreRules[$activity] ?? 0;

    // Optional: update user.score if you store total score
    // $this->score += $scoreToAdd;
    // $this->save();

    // Get event ID for this activity
    $event = DB::table('event_masters')->where('event_name', $activity)->first();

    if ($event) {
        DB::table('user_activity_scores')->insert([
            'user_id' => $this->id,
            'event_id' => $event->id,
            'activity_type' => $activity,
            'score' => $scoreToAdd,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    return $this->score;
}

 public function subscriptions()
{
    return $this->hasMany(UserSubscription::class);
}

// In User.php model

public function city()
{
    return $this->belongsTo(City::class, 'city_id');
}

public function state()
{
    return $this->belongsTo(State::class, 'state_id');
}

public function area()
{
    return $this->belongsTo(Area::class, 'area_id');
}
public function activityLogs()
{
    return $this->hasMany(UserActivityLog::class, 'user_id');
}

public function getTotalScoreAttribute()
{
    return $this->activityLogs()->sum('score');
}

public function activeSubscription()
{
    return $this->hasOne(UserSubscription::class)
                ->where('status', 'active')
                ->where('expires_at', '>=', now())
                ->with('subscription');
}

/**
 * Get the followers of this user
 */
public function followers()
{
    return $this->hasMany(Follower::class, 'follow_id');
}

/**
 * Get the users this user is following
 */
public function following()
{
    return $this->hasMany(Follower::class, 'user_id');
}

/**
 * Get the count of followers
 */
public function getFollowersCountAttribute()
{
    return $this->followers()->count();
}

/**
 * Get the count of users being followed
 */
public function getFollowingCountAttribute()
{
    return $this->following()->count();
}

}
