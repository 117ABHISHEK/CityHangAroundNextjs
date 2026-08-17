<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Page extends Model
{
    use HasFactory;
  use Searchable;


    // pages.category_id is a CSV varchar field — cannot be used as a FK
    // Use the getCategoryAttribute() accessor below instead
    public function getCategory()
    {
        return $this->belongsTo(Pagecategory::class, 'category_id');
    }
    
    public function getUser(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function marketplace()
    {
        return $this->hasOne(Marketplace::class);
    }
    

    public function openingHours()
    {
        return $this->hasMany(OpeningHour::class);
    }

    public function categories() {
        return $this->belongsToMany(Pagecategory::class, 'page_category', 'page_id', 'category_id');
    }
    
    public function likes() {
        return $this->hasMany(Page_like::class, 'page_id');
    }
    
    
    
    public function area() {
        return $this->belongsTo(Area::class, 'area_id');
    }
    
    public function state() {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function pageCategories() {
        return $this->belongsToMany(Pagecategory::class, 'page_category', 'page_id', 'category_id');
    }

    public function likedByUsers()
{
    return $this->belongsToMany(User::class, 'page_likes', 'page_id', 'user_id')->withTimestamps();
}

public function faqs()
    {
        return $this->hasMany(Faq::class);
    }

    public function media()
{
    return $this->hasMany(PageMedia::class, 'page_id'); // Adjust if your relationship is different
}

    public function mailingLists()
    {
        return $this->belongsToMany(MailingList::class, 'mailing_list_page', 'page_id', 'mailing_list_id')
                    ->withTimestamps(); // Optional: track created_at/updated_at
    }

   


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

        public function products()
{
    return $this->hasMany(Marketplace::class, 'page_id');
}

    /**
     * Get the primary category for this page.
     * pages.category_id is a CSV varchar field (e.g. "28295,28299"),
     * so we extract the first ID and look it up.
     */
    public function getCategoryAttribute()
    {
        if (empty($this->category_id)) {
            return null;
        }
        $firstId = explode(',', $this->category_id)[0];
        return Pagecategory::find($firstId);
    }


// App\Models\Page.php

public function conversations()
{
    return $this->hasMany(PageConversation::class, 'page_id');
}

public function activeSubscription()
{
    return $this->belongsTo(User::class, 'user_id')->whereHas('subscriptions', function ($q) {
        $q->where('status', 'active')
          ->where('expires_at', '>=', now())
          ->where('offered_services', 'like', '%listings%');
    });
}

public function userSubscription(): HasOne {
        return $this->hasOne(UserSubscription::class, 'user_id', 'user_id')
            ->where('status', 'active')
            ->where('expires_at', '>=', now());
    }

    public function subscription(): HasOneThrough {
        return $this->hasOneThrough(
            Subscription::class,
            UserSubscription::class,
            'user_id',         // foreign key in user_subscriptions
            'id',              // foreign key in subscriptions
            'user_id',         // local key in pages
            'subscription_id'  // local key in user_subscriptions
        );
    }

}
