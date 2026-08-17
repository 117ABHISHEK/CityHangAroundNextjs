<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageMessage extends Model
{
    protected $fillable = ['conversation_id', 'sender_id', 'message', 'image'];

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function conversation() {
        return $this->belongsTo(PageConversation::class, 'conversation_id');
    }
}
