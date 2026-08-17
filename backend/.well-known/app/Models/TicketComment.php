<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'user_id', 'admin_id', 'comment', 'screenshot'];
    public $timestamps = false; // Add this line
    protected $dates = ['created_at'];
    // Relationship with Ticket (ONE Ticket has MANY Comments)
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Relationship with User (if comment is from a user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Admin (if comment is from an admin)
    public function admin()
    {
        return $this->belongsTo(User::class);
    }
}
