<?php
// app/Models/MailingList.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailingList extends Model
{
    use HasFactory;

    // Table associated with the model
    protected $table = 'mailing_lists';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'created_by',
    ];

    // Relationship: A MailingList can have many Pages
    public function pages()
    {
        return $this->belongsToMany(Page::class, 'mailing_list_page', 'mailing_list_id', 'page_id')
                    ->withTimestamps(); // Optional: track created_at/updated_at
    }

    // Relationship: A MailingList belongs to a User (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subscribers()
{
    return $this->hasMany(Subscriber::class);
}

}
