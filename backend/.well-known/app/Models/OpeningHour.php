<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningHour extends Model
{
    use HasFactory;

    protected $table = 'opening_hours';

    protected $fillable = [
        'page_id',
        'day',
        'open',
        'close',
        'closed',
    ];

    protected $casts = [
        'closed' => 'boolean',
        'open' => 'datetime:H:i',
        'close' => 'datetime:H:i',
    ];

    /**
     * Get the page that owns the opening hours.
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
