<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'pag_faq'; // 👈 important
    protected $fillable = ['page_id', 'question', 'answer'];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
