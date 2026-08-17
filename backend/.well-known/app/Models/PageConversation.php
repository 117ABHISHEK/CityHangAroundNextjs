<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageConversation extends Model
{
    protected $fillable = ['page_id', 'user_id'];

    public function page() {
        return $this->belongsTo(Page::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function messages() {
        return $this->hasMany(PageMessage::class, 'conversation_id');
    }

    
}
