<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = ['mailing_list_id', 'email', 'name'];

    public function mailingList()
    {
        return $this->belongsTo(MailingList::class);
    }
}
